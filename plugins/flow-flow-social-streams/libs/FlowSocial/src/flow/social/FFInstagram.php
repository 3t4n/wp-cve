<?php namespace flow\social;
use Exception;

if ( ! defined( 'WPINC' ) ) die;

/**
 * Flow-Flow.
 *
 * @property FFInstagramAPI $api
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>

 * @link      http://looks-awesome.com
 * @copyright 2014-2020 Looks Awesome
 */
class FFInstagram extends FFBaseFeed implements LAFeedWithComments{
	private $api;

	public function __construct() {
		parent::__construct( 'instagram' );
	}

    public function init( $context, $feed ) {
        parent::init( $context, $feed );
        $officialAPI = isset($feed->{'api-type'}) && $feed->{'api-type'} === 'official2';
        $this->api = ($officialAPI)? new FFInstagramOfficialAPI() : new FFInstagramOpenAPI();
        $this->api->init($context, $feed);
    }

    public function deferredInit($feed) {
        $this->api->deferredInit($feed, $this->getCount());
    }

    /**
     * @return array
     * @throws LASocialException
     * @throws Exception
     * @throws LASocialException
     */
    public function onePagePosts() {
        $result = [];
        try {
            foreach (  $this->api->onePagePosts() as $post ) {
                $post->smart_order = 0;
                $post->feed_id = $this->id();
                $post->type = $this->getType();
                if ($this->isSuitablePost($post)) $result[$post->id] = $post;
            }
        } catch ( LASocialRequestException $sre ) {
            error_log($sre->getMessage());
//            error_log(print_r($sre, true));
            $request_errors = $sre->getRequestErrors();
            $message = is_object($request_errors) ? 'Error getting data from instagram server' : $this->filterErrorMessage($request_errors);
            if ($message === 'Invalid user id'){
                $message = 'Non-business accounts are not supported';
            }
            throw new LASocialException($message);
        }
        return $result;
    }

    /**
     * @param $item
     *
     * @return array
     */
    public function getComments($item) {
        return $this->api->getComments($item);
    }

    protected function nextPage( $result ) {
        return $this->api->nextPage($result);
    }
}