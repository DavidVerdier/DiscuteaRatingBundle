<?php

namespace Discutea\RatingBundle\Controller;

use Discutea\RatingBundle\Entity\Rating;
use Discutea\RatingBundle\Repository\RatingRepository;
use Discutea\RatingBundle\Repository\VoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class RatingController
 * @package Discutea\RatingBundle\Controller
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
class RatingController extends AbstractController
{
    /**
     * @var VoteRepository
     */
    private $voteRepository;

    /**
     * @var array
     */
    private $discuteaRatingConfig;

    /**
     * RatingController constructor.
     * @param VoteRepository $voteRepository
     * @param array $discuteaRatingConfig
     */
    public function __construct(VoteRepository $voteRepository, array $discuteaRatingConfig)
    {
        $this->voteRepository = $voteRepository;
        $this->discuteaRatingConfig = $discuteaRatingConfig;
    }

    /**
     * @param $id
     * @param RatingRepository $ratingRepository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function showRate($id, RatingRepository $ratingRepository): Response
    {
        if (null === $rating = $ratingRepository->findOneBy(['id' => $id])) {
            $rating = $ratingRepository->createRating($id);
            $ratingRepository->saveRating($rating);
        }

        return $this->render('@DiscuteaRatingBundle/Rating/star.html.twig', array(
            'rating' => $rating,
            'rate'   => $rating->getRate(),
            'maxValue' => $this->discuteaRatingConfig['max_value'],
        ));
    }

    /**
     * @param $id
     * @param Request $request
     * @param RatingRepository $ratingRepository
     * @param bool $jsonld
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function control($id, Request $request, RatingRepository $ratingRepository, $jsonld = false): Response
    {
        if (null === $rating = $ratingRepository->findOneBy(['id' => $id])) {
            $rating = $ratingRepository->createRating($id);
            $ratingRepository->saveRating($rating);
        }

        // check if the user has permission to express the vote on entity Rating
        if ($this->isGranted($rating->getSecurityRole())) {
            $viewName = 'star';
        } else {
            // check if the voting system allows multiple votes. Otherwise
            // check if the user has already expressed a preference
            if (!$this->discuteaRatingConfig['unique_vote']) {
                $viewName = 'choice';
            } else {
                $vote = $this->voteRepository->findOneByRatingAndVoter($rating, $this->getUser());

                $viewName = null === $vote ? 'choice' : 'star';
            }
        }

        $datas = array(
            'rating' => $rating,
            'rate'   => $rating->getRate(),
            'params' => $request->get('params', array()),
            'maxValue' => $this->discuteaRatingConfig['max_value'],
        );

        if (true === $jsonld)
        {
            return $this->render('@DiscuteaRating/Rating/structured_data.json.twig', $datas);
        }

        return $this->render('@DiscuteaRating/Rating/'.$viewName.'.html.twig', $datas);
    }

    /**
     * @Route("/vote/add/{id}/{value}", name="discutea_rating_add_vote")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     *
     * @param Rating $rating
     * @param $value
     * @param Request $request
     * @param RouterInterface $router
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addVote(Rating $rating, $value, Request $request, RouterInterface $router): Response
    {
        if (null === $rating->getSecurityRole() || $this->isGranted($rating->getSecurityRole())) {
            throw new AccessDeniedHttpException('You can not perform the evaluation');
        }

        $maxValue = $this->discuteaRatingConfig['max_value'];

        if (!is_numeric($value) || $value < 0 || $value > $maxValue) {
            throw new BadRequestHttpException(sprintf('You must specify a value between 0 and %d', $maxValue));
        }

        $user = $this->getUser();

        if ($this->discuteaRatingConfig['unique_vote'] &&
            null !== $this->voteRepository->findOneByRatingAndVoter($rating, $user)
        ) {
            throw new AccessDeniedHttpException('You have already rated');
        }

        $vote = $this->voteRepository->createVote($rating, $user);
        $vote->setValue($value);

        $this->voteRepository->saveVote($vote);

        if ($request->isXmlHttpRequest()) {
            return $this->forward('DiscuteaRatingBundle:Rating:showRate', array(
                'id' => $rating->getId()
            ));
        }

        if (null === $redirectUri = $request->headers->get('referer', $rating->getPermalink())) {
            $pathToRedirect = $this->discuteaRatingConfig['base_path_to_redirect'];
            if ($router->getRouteCollection()->get($pathToRedirect)) {
                $redirectUri = $this->generateUrl($pathToRedirect);
            } else {
                $redirectUri = $pathToRedirect;
            }
        }

        return $this->redirect($redirectUri);
    }
}
