<?php namespace flow\social;
if ( ! defined( 'WPINC' ) ) die;

use Exception;

interface FFInstagramAPI{
    public function init($context, $feed);
    public function deferredInit($feed, $count);

    /**
     * @return mixed
     * @throws Exception
     */
    public function onePagePosts();
    public function nextPage($result);
}