<?php

namespace Discutea\RatingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Discutea\RatingBundle\Entity\RatingManager;
use Discutea\RatingBundle\Entity\VoteManager;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class RatingController
 * @package Discutea\RatingBundle\Controller
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
class RatingController extends AbstractController
{
    /**
     * @var RatingManager
     */
    private $ratingManager;

    /**
     * @var VoteManager
     */
    private $voteManager;

    /**
     * @var array
     */
    private $discuteaRatingConfig;

    /**
     * RatingController constructor.
     * @param RatingManager $ratingManager
     * @param VoteManager $voteManager
     * @param array $discuteaRatingConfig
     */
    public function __construct(RatingManager $ratingManager, VoteManager $voteManager, array $discuteaRatingConfig)
    {
        $this->ratingManager = $ratingManager;
        $this->voteManager = $voteManager;
        $this->discuteaRatingConfig = $discuteaRatingConfig;
    }

    /**
     * @param $id
     * @return Response
     */
    public function showRate($id): Response
    {
        if (null === $rating = $this->ratingManager->findOneById($id)) {
            $rating = $this->ratingManager->createRating($id);
            $this->ratingManager->saveRating($rating);
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
     * @param bool $jsonld
     * @return Response
     */
    public function control($id, Request $request, $jsonld = false): Response
    {
        if (null === $rating = $this->ratingManager->findOneById($id)) {
            $rating = $this->ratingManager->createRating($id);
            $this->ratingManager->saveRating($rating);
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
                $vote = $this->voteManager
                    ->findOneByRatingAndVoter($rating, $this->getUser());

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
     *
     * @param $id
     * @param $value
     * @param Request $request
     * @param RouterInterface $router
     * @return Response
     */
    public function addVote($id, $value, Request $request, RouterInterface $router): Response
    {
        if (null === $rating = $this->ratingManager->findOneById($id)) {
            throw new NotFoundHttpException('Rating not found');
        }

        if (null === $rating->getSecurityRole() || $this->isGranted($rating->getSecurityRole())) {
            throw new AccessDeniedHttpException('You can not perform the evaluation');
        }

        $maxValue = $this->discuteaRatingConfig['max_value'];

        if (!is_numeric($value) || $value < 0 || $value > $maxValue) {
            throw new BadRequestHttpException(sprintf('You must specify a value between 0 and %d', $maxValue));
        }

        $user = $this->getUser();

        if ($this->discuteaRatingConfig['unique_vote'] &&
            null !== $this->voteManager->findOneByRatingAndVoter($rating, $user)
        ) {
            throw new AccessDeniedHttpException('You have already rated');
        }

        $vote = $this->voteManager->createVote($rating, $user);
        $vote->setValue($value);

        $this->voteManager->saveVote($vote);

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
