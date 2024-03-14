<?php namespace flow\social;

use Cake\Cache\SimpleCacheEngine;
use Exception;
use flow\social\cache\FFImageSizeCacheBase;
use flow\social\cache\LAFacebookCacheManager;
use stdClass;

/**
 *
 * @author    navdeykin <navdeykin@gmail.com>
 * @copyright 2014-2020 Looks Awesome
 */
class FFInstagramOfficialAPI implements FFInstagramAPI {
    private static $version = 'v7.0';

    private $size = 0;
    private $pagination = true;
    private $count = 0;
    private $url;
    private $base_url;
    /** @var LAFacebookCacheManager */
    private $facebookCache;
    /** @var SimpleCacheEngine */
    private $cache;
    /** @var FFImageSizeCacheBase */
    private $imageCache;

    public function init( $context, $feed ) {
        $this->facebookCache = $context['facebook_cache'];
        $this->cache = FFFeedUtils::getCache($context);
        $this->imageCache = $context['image_size_cache'];
    }

    /**
     * @param $feed
     * @param $count
     *
     * @throws LASocialException
     */
    public function deferredInit( $feed, $count ) {
        $this->count = $count;
        $accessToken = $this->facebookCache->getAccessToken();
        if (isset($feed->{'timeline-type'})) {
            $version = self::$version;
            switch ($feed->{'timeline-type'}) {
                case 'user_timeline':
                    $content = FFFeedUtils::preparePrefixContent($feed->content, '@');
                    $page = $this->getPageId();
                    $fields    = "website,followers_count,follows_count,media_count,username,name,profile_picture_url,biography,media.limit({$count}){comments_count,like_count,media_type,caption,children{media_url,media_type},permalink,timestamp,media_url}";
                    if (!empty($content)){
                        $fields    = "business_discovery.username({$content}){{$fields}}";
                    }
                    $fields = urlencode($fields);
                    $this->url = "https://graph.facebook.com/{$version}/{$page}?fields={$fields}&access_token={$accessToken}";
                    break;
                case 'tag':
                    $content = FFFeedUtils::preparePrefixContent($feed->content, '#');
                    $page = $this->getPageId();
                    $hashtagId = $this->getHashtagId($content, $page);
                    $fields = urlencode('caption,children{media_url,media_type},comments_count,id,like_count,media_type,media_url,permalink,timestamp');
                    $this->url = "https://graph.facebook.com/{$version}/{$hashtagId}/top_media?user_id={$page}&fields={$fields}&access_token={$accessToken}";
                    break;
            }
            $this->base_url = $this->url;
        }
    }

    public function onePagePosts() {
        $result = [];
        $data = FFFeedUtils::getFeedDataWithThrowException($this->url);
        if (isset($data['response']) && is_string($data['response'])){
            $response = $data['response'];
            //fix malformed
            //http://stackoverflow.com/questions/19981442/decoding-instagram-reply-php
            //In case of a problem, comment out this line
            $response = html_entity_decode($response);
            $page = json_decode($response);
            $userMeta = null;
            $with_user_meta = false;
            if (isset($page->business_discovery)){
                $userMeta = $this->fillUser($page->business_discovery);
                $page = $page->business_discovery->media;
                $with_user_meta = true;
            }

            if (isset($page->paging->cursors->after))
                $this->url = str_replace('media.limit', urlencode("media.after({$page->paging->cursors->after}).limit"), $this->base_url);
            else
                $this->pagination = false;

            foreach ($page->data as $item) {
                $item->user = $with_user_meta ? $userMeta : $this->getUser();
                $post = $this->parsePost($item);

                if($with_user_meta){
                    $post->userMeta = $userMeta;
                }
                $result[] = $post;
            }
        } else {
            throw new LASocialException('FFInstagram has returned the empty data.', [ 'url' => $this->url ] );
        }
        return $result;
    }

    public function nextPage( $result ) {
        if ($this->pagination){
            $size = sizeof($result);
            if ($size == $this->size) {
                return false;
            }
            else {
                $this->size = $size;
                return $this->count > $size;
            }
        }
        return false;
    }

    private function parsePost($post) {
        $tc             = new stdClass();
        $tc->id         = (string) $post->id;
        $tc->header     = '';
        $tc->nickname   = (string) $post->user->username;
        $tc->screenname = FFFeedUtils::removeEmoji( (string) $post->user->full_name );
        if ( function_exists( 'mb_convert_encoding' ) ) {
            $tc->screenname = mb_convert_encoding( $tc->screenname, 'HTML-ENTITIES', 'UTF-8' );
        } else if ( function_exists( 'iconv' ) ) {
            $tc->screenname = iconv( 'UTF-8', 'HTML-ENTITIES', $tc->screenname );
        }
        $tc->userpic          = (string) $post->user->profile_picture;
        $tc->system_timestamp = strtotime( $post->timestamp );
        $tc->text             = isset( $post->caption ) ? FFFeedUtils::hashtagLinks( $post->caption ) : '';
        $tc->userlink         = 'http://instagram.com/' . $tc->nickname;
        $tc->permalink        = $post->permalink;
        $tc->additional       = [ 'likes' => (string) $post->like_count, 'comments' => (string) $post->comments_count ];

        $image_thumbnail_url = '';
        $width = $height = '300';
        if ($post->media_type == 'VIDEO'){
            $oembed_url = "https://graph.facebook.com/v8.0/instagram_oembed?url={$post->permalink}&access_token={$this->facebookCache->getAccessToken()}}";
            $data = FFFeedUtils::getFeedData($oembed_url);
            if (isset($data['response']) && is_string($data['response'])) {
                $response = $data['response'];
                $response = html_entity_decode( $response );
                $oembed   = json_decode( $response );
                $image_thumbnail_url = $oembed->thumbnail_url;
                $width = $oembed->thumbnail_width;
                $height = $oembed->thumbnail_height;
            }
        }
        else if ($post->media_type == 'CAROUSEL_ALBUM') {
            foreach ($post->children->data as $item){
                if ($item->media_type === 'IMAGE'){
                    $image_thumbnail_url = $item->media_url;
                    $s                   = $this->imageCache->size( $image_thumbnail_url );
                    $width               = $s['width'];
                    $height              = $s['height'];
                    break;
                }
            }
        }
        else {
            $image_thumbnail_url = $post->media_url;
            $s                   = $this->imageCache->size( $image_thumbnail_url );
            $width               = $s['width'];
            $height              = $s['height'];
        }
        $tc->img = [ 'url' => $image_thumbnail_url, 'width' => 300, 'height' => FFFeedUtils::getScaleHeight(300, $width, $height) ];
        return $tc;
    }

    private function fillUser($post){
        $result = new stdClass();
        $result->username = $post->username;
        $result->full_name = $post->name;
        $result->id = $post->id;
        $result->bio = isset($post->biography) ? $post->biography : '';
        $result->website = isset($post->website) ? $post->website : '';
        $result->counts = new stdClass();
        $result->counts->media = $post->media_count;
        $result->counts->follows = $post->follows_count;
        $result->counts->followed_by = $post->followers_count;
        $result->profile_picture = $post->profile_picture_url;
        return $result;
    }

    /**
     * @return mixed
     * @throws LASocialException
     */
    private function getPageId() {
        $version = self::$version;
        $accessToken = $this->facebookCache->getAccessToken();

	    try {
		    // update 31/01/2023, making one request instead two


		    $fields = urlencode('id,access_token,instagram_business_account{id}');
		    $url = "https://graph.facebook.com/{$version}/me/accounts?fields={$fields}&access_token={$accessToken}";

		    $facebookPageId = $this->cache->get(md5('facebookPageId'));
		    $instagram_business_account = $this->cache->get(md5('instagram_business_account'));

		    if (is_null($facebookPageId)){
			    $request = FFFeedUtils::getFeedDataWithThrowException($url);
			    $json = json_decode($request['response']);
			    if($json->data) {
				    foreach ( $json->data as $item ) {
					    if ( ! isset( $item->access_token ) && ! isset( $item->instagram_business_account ) ) {
						    continue;
					    }
					    $facebookPageId = $item->id;
					    $this->cache->set(md5('facebookPageId'), $facebookPageId, 60 * 60 * 24);//one day
					    $instagram_business_account = $item->instagram_business_account->id;
					    $this->cache->set(md5('instagram_business_account'), $instagram_business_account, 60 * 60 * 24);//one day
					    break;
				    }
			    }
		    }

		    return $instagram_business_account;
	    }
        catch ( LASocialException $e ){
            throw $e;
        }
        catch ( Exception $exception){
            throw new LASocialException('Failed to get instagram business account id', [], $exception);
        }
    }

    /**
     * @param $hashtag
     * @param $page
     *
     * @return
     * @throws LASocialException
     * @throws LASocialRequestException
     */
    private function getHashtagId($hashtag, $page){
        $version = self::$version;
        $accessToken = $this->facebookCache->getAccessToken();
        $url = "https://graph.facebook.com/{$version}/ig_hashtag_search?user_id={$page}&q={$hashtag}&access_token={$accessToken}";
        $hashtagId = $this->cache->get(md5($url));
        if (is_null($hashtagId)){
            $request = FFFeedUtils::getFeedDataWithThrowException($url);
            $json = json_decode($request['response']);
            if (isset($json->data)){
                foreach ( $json->data as $item ) {
                    if (isset($item->id) && !empty($item->id)){
                        $this->cache->set(md5($url), $item->id, 60 * 60 * 24 * 7);//week
                        return $item->id;
                    }
                }
            }
            throw new LASocialException('This tag does not exists or it has been hidden by Instagram');
        }
        return $hashtagId;
    }

    private function getUser(){
        $default = new stdClass();
        $default->username = '';
        $default->full_name = '';
        $default->id = '';
        $default->bio = '';
        $default->website = '';
        $default->profile_picture = '';
        return $default;
    }
}