<?php

namespace Discutea\RatingBundle;

/**
 * Class DiscuteaRatingEvents
 * @package Discutea\RatingBundle
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
class RatingEvents
{
    const RATING_CREATE = 'discutea_rating.event.rating.create';
    const RATING_PRE_PERSIST = 'discutea_rating.event.rating.pre_persist';
    const RATING_POST_PERSIST = 'discutea_rating.event.rating.post_persist';

    const VOTE_CREATE = 'discutea_rating.event.vote.create';
    const VOTE_PRE_PERSIST = 'discutea_rating.event.vote.pre_persist';
    const VOTE_POST_PERSIST = 'discutea_rating.event.vote.post_persist';
}
