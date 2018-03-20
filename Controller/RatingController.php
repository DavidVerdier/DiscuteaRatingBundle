<?php

namespace Discutea\RatingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class RatingController
 * @package Discutea\RatingBundle\Controller
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
class RatingController extends Controller
{
    public function showRateAction($id, Request $request)
    {
        $ratingManager = $this->get('discutea_rating.manager.rating');

        if (null === $rating = $ratingManager->findOneById($id)) {
            $rating = $ratingManager->createRating($id);
            $ratingManager->saveRating($rating);
        }

        return $this->render('@DiscuteaRatingBundle/Rating/star.html.twig', array(
            'rating' => $rating,
            'rate'   => $rating->getRate(),
            'maxValue' => $this->getParameter('discutea_rating.max_value'),
        ));
    }

    public function controlAction($id, Request $request, $jsonld = false)
    {
        $ratingManager = $this->get('discutea_rating.manager.rating');

        if (null === $rating = $ratingManager->findOneById($id)) {
            $rating = $ratingManager->createRating($id);
            $ratingManager->saveRating($rating);
        }

        // check if the user has permission to express the vote on entity Rating
        if (!$this->get('security.authorization_checker')->isGranted($rating->getSecurityRole())) {
            $viewName = 'star';
        } else {
            // check if the voting system allows multiple votes. Otherwise
            // check if the user has already expressed a preference
            if (!$this->getParameter('discutea_rating.unique_vote')) {
                $viewName = 'choice';
            } else {
                $vote = $this->get('discutea_rating.manager.vote')
                    ->findOneByRatingAndVoter($rating, $this->getUser());

                $viewName = null === $vote ? 'choice' : 'star';
            }
        }

        $datas = array(
            'rating' => $rating,
            'rate'   => $rating->getRate(),
            'params' => $request->get('params', array()),
            'maxValue' => $this->getParameter('discutea_rating.max_value'),
        );

        if (true === $jsonld)
        {
            return $this->render('@DiscuteaRating/Rating/structured_data.json.twig', $datas);
        }

        return $this->render('@DiscuteaRating/Rating/'.$viewName.'.html.twig', $datas);
    }

    /**
     * @Route("/vote/add/{id}/{value}", name="discutea_rating_add_vote")
     */
    public function addVoteAction($id, $value, Request $request)
    {
        if (null === $rating = $this->get('discutea_rating.manager.rating')->findOneById($id)) {
            throw new NotFoundHttpException('Rating not found');
        }

        if (null === $rating->getSecurityRole() || !$this->get('security.authorization_checker')->isGranted($rating->getSecurityRole())) {
            throw new AccessDeniedHttpException('You can not perform the evaluation');
        }

        $maxValue = $this->getParameter('discutea_rating.max_value');

        if (!is_numeric($value) || $value < 0 || $value > $maxValue) {
            throw new BadRequestHttpException(sprintf('You must specify a value between 0 and %d', $maxValue));
        }

        $user = $this->getUser();
        $voteManager = $this->get('discutea_rating.manager.vote');

        if ($this->getParameter('discutea_rating.unique_vote') &&
            null !== $voteManager->findOneByRatingAndVoter($rating, $user)
        ) {
            throw new AccessDeniedHttpException('You have already rated');
        }

        $vote = $voteManager->createVote($rating, $user);
        $vote->setValue($value);

        $voteManager->saveVote($vote);

        if ($request->isXmlHttpRequest()) {
            return $this->forward('DiscuteaRatingBundle:Rating:showRate', array(
                'id' => $rating->getId()
            ));
        }

        if (null === $redirectUri = $request->headers->get('referer', $rating->getPermalink())) {
            $pathToRedirect = $this->getParameter('discutea_rating.base_path_to_redirect');
            if ($this->get('router')->getRouteCollection()->get($pathToRedirect)) {
                $redirectUri = $this->generateUrl($pathToRedirect);
            } else {
                $redirectUri = $pathToRedirect;
            }
        }

        return $this->redirect($redirectUri);
    }
}
