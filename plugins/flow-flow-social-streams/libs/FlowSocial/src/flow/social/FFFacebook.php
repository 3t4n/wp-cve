<?php namespace flow\social;
if ( ! defined( 'WPINC' ) ) die;

use Exception;
use flow\social\cache\LAFacebookCacheManager;
use stdClass;

/**
 * Flow-Flow.
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>

 * @link      http://looks-awesome.com
 * @copyright Looks Awesome
 */
class FFFacebook extends FFHttpRequestFeed implements LAFeedWithComments {
	const API_VERSION = 'v3.3';

	/** @var  string | bool */
	private $accessToken;
	/** @var LAFacebookCacheManager */
	private $facebookCache;

	/** @var bool */
	private $hideStatus = true;
	private $image;
	private $media;
	private $carousel = [];
	private $images;

	private $new_post_ids;

	public function __construct() {
		parent::__construct( 'facebook' );
	}

	public function init( $context, $feed ) {
		parent::init( $context, $feed );
		$this->facebookCache = $this->context['facebook_cache'];
	}

	/**
	 * @param $item
	 *
	 * @return array
	 * @throws Exception, LASocialRequestException
	 */
	public function getComments($item) {
		if (is_object($item)){
			$result = [];
			if (isset($item->comments->data)){
				foreach ($item->comments->data as $comment){
					$result[] = $this->getComment($comment);
				}
			}
			return $result;
		}

		$objectId = $item;
		$accessToken = $this->facebookCache->getAccessToken();
		if ($this->accessToken === false){
			$this->errors[] = $this->facebookCache->getError();
			throw new Exception();
		}
		$api = FFFacebook::API_VERSION;
		$url = "https://graph.facebook.com/{$api}/{$objectId}/comments?total_count={$this->getCount()}&access_token={$accessToken}";

		$request = $this->getFeedData($url);
		$json = json_decode($request['response']);

		if (!is_object($json) || (is_object($json) && sizeof($json->data) == 0)) {
			if (isset($request['errors']) && is_array($request['errors'])){
				if (!empty($request['errors'])){
					foreach ( $request['errors'] as $error ) {
						$error['type'] = 'facebook';
						$this->errors[] = $error;
						throw new Exception();
					}
				}
			}
			else {
				$this->errors[] = ['type'=>'facebook', 'message' => 'Bad request, access token issue. <a href="http://docs.social-streams.com/article/55-400-bad-request" target="_blank">Troubleshooting</a>.', 'url' => $url];
				throw new Exception();
			}
			return [];
		}
		else {
			if($json->data){
				// return first 5 comments
				$data = array_slice($json->data, 0, 5);
				$result = [];
				foreach ($data as $item){
					$result[] = $this->getComment($item);
				}
				return $result;
			}else{
				$this->errors[] = [
					'type' => 'facebook',
					'message' => 'Comments issue',
					'url' => $url
				];
				throw new Exception();
			}
		}
	}

	protected function deferredInit($feed) {
		$this->images = [];
		if (isset($feed->{'timeline-type'})) {
			$timeline = $feed->{'timeline-type'} == 'user_timeline' ? 'page_timeline' : $feed->{'timeline-type'};
			$locale = defined('FF_LOCALE') ? 'locale=' . FF_LOCALE : 'locale=en_US';
			$api = FFFacebook::API_VERSION;
			switch ($timeline) {
				case 'group':
				case 'feed':
					$groupId = (string)$feed->content;
					$fields    = 'fields=';
					$fields    = $fields . 'likes.summary(true),comments.summary(true),shares,';
					$fields    = $fields . 'id,created_time,from,message,picture,full_picture,attachments{media,subattachments},status_type,story';
					$this->url        = "https://graph.facebook.com/{$api}/{$groupId}/feed?{$fields}&limit={$this->getCount()}&{$locale}";
					$this->hideStatus = false;
					break;
				case 'page_timeline':
					$page_id = (string)$feed->content;

					try {
						$request = $this->getFeedData("https://graph.facebook.com/{$api}/me/accounts?access_token={$this->accessToken}");
						$json = json_decode($request['response']);
						if($json->data) {
							foreach ( $json->data as $item ) {
								if (($page_id == $item->id) || ($page_id == $item->name)) {
									$this->accessToken = $item->access_token;
									$page_id = 'me';
									break;
								}
							}
						}
					}
					catch (LASocialRequestException $exception){
						error_log($exception->getMessage());
					}

					$fields    = 'fields=';
					$fields    = $fields . 'likes.summary(true),comments.summary(true),shares,permalink_url,';
					$fields    = $fields . 'id,created_time,from,message,picture,full_picture,attachments,status_type,story';
					$this->url        = "https://graph.facebook.com/{$api}/{$page_id}/posts?{$fields}&limit={$this->getCount()}&{$locale}";
					$this->hideStatus = false;
					break;
				case 'album':
					$fields    = 'fields=';
					$fields    = $fields . 'likes.summary(true),comments.summary(true),';
					$fields    = $fields . 'id,created_time,from,link,name,picture,source';
					$albumId = (string)$feed->content;
					$this->url = "https://graph.facebook.com/{$api}/{$albumId}/photos?{$fields}&limit={$this->getCount()}&{$locale}";
					$this->hideStatus = false;
					break;
				default:
					$fields    = 'fields=';
					$fields    = $fields . 'likes.summary(true),comments.summary(true),shares,';
					$fields    = $fields . 'id,created_time,from,link,message,name,object_id,picture,full_picture,attachments{media,subattachments},source,status_type,story';
					$this->url = "https://graph.facebook.com/{$api}/me/home?{$fields}&limit={$this->getCount()}&{$locale}";
			}
		}
	}

	protected function beforeProcess() {
		$this->accessToken = $this->facebookCache->getAccessToken();
		if ($this->accessToken === false){
			$this->errors[] = $this->facebookCache->getError();
			return false;
		}
		$this->facebookCache->startCounter();
		return true;
	}

	protected function afterProcess( $result ) {
		$this->facebookCache->stopCounter();
		return parent::afterProcess( $result );
	}

	protected function getUrl() {
		return $this->getUrlWithToken($this->url);
	}

	protected function items( $request ) {
		$pxml = json_decode($request);
		if (isset($pxml->data)) {
			$ids = $this->facebookCache->getIdPosts($this->id());
			$new_ids = [];
			foreach ( $pxml->data as $item ) {
				$new_ids[] = $this->getId($item);
			}
			$this->new_post_ids = [];
			$diff = array_diff($new_ids, $ids);
			foreach ( $diff as $id ) {
				$this->new_post_ids[$id] = 1;
			}

			return $pxml->data;
		}
		return [];
	}

	protected function isSuitableOriginalPost( $post ) {
		if ($this->hideStatus) return false;
		if (isset($post->status_type) && $post->status_type == 'tagged_in_photo') return false;
		if (!isset($post->created_time)) return false;

		$hasLimit = $this->facebookCache->hasLimit();
		if (!$hasLimit){
			$this->errors[] = [
				'type' => 'facebook',
				'message' => 'Your site has hit the Facebook API rate limit. <a href="http://docs.social-streams.com/article/133-facebook-app-request-limit-reached" target="_blank">Troubleshooting</a>.'
			];
		}
		return $hasLimit;
	}

	protected function prepare( $item ) {
		$this->image = null;
		$this->media = null;
		return parent::prepare( $item );
	}

	protected function getHeader($item){
		if (isset($item->status_type) && $item->status_type == 'added_photos'){
			return '';
		}
		if (isset($item->name)){
			return $item->name;
		}
		return '';
	}

	protected function showImage($item){
		if (isset($item->status_type) && $item->status_type == 'added_video'){
			if (isset($item->attachments) && isset($item->attachments->data) && sizeof($item->attachments->data) > 0){
				$subattachments = $this->getSubattachments($item);
				if (sizeof($subattachments) > 0){
					$image= $subattachments[0];
					$width = $image->width;
					$height = $image->height;
					if ($width > 600) {
						$height = FFFeedUtils::getScaleHeight(600, $width, $height);
						$width = 600;
					}
					$this->image = $this->createImage($image->src, $width, $height);

					if (isset($item->attachments->data[0]->target)){
						$videoId = $item->attachments->data[0]->target->id;
						if (isset($item->from->id)){
							$this->media = $this->createMedia('https://www.facebook.com/plugins/video.php?app_id=&container_width=0&href=https%3A%2F%2Ffacebook.com%2F' . $item->from->id . '%2Fvideos%2F' . $videoId . '%2F&locale=en_US&sdk=joey' /* . '&appId=140463540033583' */, $width, $height, 'video');
						}
						else {
							$this->media = $this->createMedia('http://www.facebook.com/video/embed?video_id=' . $item->object_id, $width, $height, 'video');
						}
					}

					$this->carousel = $subattachments;
					return true;
				}
			}
		}

		if (isset($item->attachments->data) && sizeof($item->attachments->data) > 0){
			$subattachments = $this->getSubattachments($item);
			if (sizeof($subattachments) > 0){
				$image = $subattachments[0];
				$this->image = $this->createImage($image->src, $image->width, $image->height);
				$this->media = $this->createMedia($image->src, $image->width, $image->height, 'image', true);
				$this->carousel = $subattachments;
				return true;
			}
		}

		if (isset($item->source)){
			$this->image = $this->createImage($item->source);
			$this->media = $this->createMedia($item->source);
			return true;
		}

		return false;
	}

	protected function getCarousel( $item ) {
		$carousel = parent::getCarousel($item);
		$subattachments = $this->carousel;

		if (sizeof($subattachments) > 1){
			foreach ($subattachments as $image){
				$carousel[] = $this->createMedia($image->src, $image->width, $image->height);
			}
			// change 28.08.20
			// otherwise first pic is duplicated
			array_shift( $carousel );

		}
		return $carousel;
	}

	protected function getImage( $item ) {
		return $this->image;
	}

	protected function getMedia( $item ) {
		return $this->media;
	}

	protected function getScreenName($item){
		return $item->from->name;
	}

	protected function getContent($item){
		if (isset($item->message)) return self::wrapHashTags(FFFeedUtils::wrapLinks($item->message), $item->id);
		if (isset($item->story)) return (string)$item->story;
		return '';
	}

	protected function getProfileImage( $item ) {
		$url = "https://graph.facebook.com/" . FFFacebook::API_VERSION . "/{$item->from->id}/picture?width=80&height=80";
		if (!array_key_exists($url, $this->images)){
			$this->images[$url] = $this->getLocation($url);
		}
		return $this->images[$url];
	}

	protected function getId( $item ) {
		return $item->id;
	}

	protected function getSystemDate( $item ) {
		return strtotime($item->created_time);
	}

	protected function getUserlink( $item ) {
		return 'https://www.facebook.com/'.$item->from->id;
	}

	protected function getPermalink( $item ) {
		if (isset($item->link)){
			return $item->link;
		}
		$parts = explode('_', $item->id);
		return 'https://www.facebook.com/'.$parts[0].'/posts/'.$parts[1];
	}

	protected function getAdditionalInfo( $item ) {
		$additional = parent::getAdditionalInfo( $item );
		$additional['likes']      = (string)@$item->likes->summary->total_count;
		$additional['comments']   = isset($item->comments->summary->total_count) ? (string)@$item->comments->summary->total_count : '';
		$additional['shares']     = isset($item->shares) ? (string)@$item->shares->count : '0';
		return $additional;
	}

	/**
	 * @param string $text
	 * @param string $id
	 *
	 * @return mixed
	 */
	private function wrapHashTags($text, $id){
		//return preg_replace('/#([\\d\\w]+)/', '<a href="https://www.facebook.com/hashtag/$1?source=feed_text&story_id='.$id.'">$0</a>', $text);//old
		/** @noinspection RegExpRedundantEscape */
		return preg_replace("/#([A-Za-z0-9ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöùúûüýÿ\/\.]*)/u", "<a href=\"https://www.facebook.com/hashtag/$1?source=feed_text&story_id='.$id.'\">#$1</a>", $text);
	}

	private function getLocation($url, $with_token = true) {
		if (defined('FF_DONT_USE_GET_HEADERS') && FF_DONT_USE_GET_HEADERS){
			$location = @$this->getCurlLocation($this->getUrlWithToken( $url ));
			if (!empty($location) && $location != 0) {
				return $location;
			}
		}
		else {
			$headers = $this->getHeadersSafe($with_token ? $this->getUrlWithToken( $url ) : $url , 1);
			if (isset($headers["Location"])) {
				return $headers["Location"];
			} else {
				$location = @$this->getCurlLocation($with_token ? $this->getUrlWithToken( $url ) : $url);
				if (!empty($location) && $location != 0) {
					return $location;
				}
			}
		}

		$location = str_replace('/v2.3/', '/', $url);
		$location = str_replace('/v2.4/', '/', $location);
		$location = str_replace('/v2.5/', '/', $location);
		$location = str_replace('/v2.6/', '/', $location);
		$location = str_replace('/v2.7/', '/', $location);
		$location = str_replace('/v2.8/', '/', $location);
		return $location;
	}

	private function getCurlLocation($url) {
		$curl = curl_init();
		curl_setopt_array( $curl, [
			CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36',
			CURLOPT_HEADER => true,
			CURLOPT_NOBODY => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => $url
		]);
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT_MS, 5000);
		curl_setopt( $curl, CURLOPT_TIMEOUT, 60);
		$headers = explode( "\n", curl_exec( $curl ) );
		curl_close( $curl );

		$location = '';
		foreach ( $headers as $header ) {
			if (strpos($header, "ocation:")) {
				$location = substr($header, 10);
				break;
			}
		}
		return $location;
	}

	private function getHeadersSafe($url, $format){
		if ( ini_get( 'allow_url_fopen' ) ) {
			return get_headers( $url, $format );
		} else {
			$ch = curl_init( $url );
			curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36');
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_HEADER, true );
			curl_setopt( $ch, CURLOPT_NOBODY, true );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT_MS, 5000);
			curl_setopt( $ch, CURLOPT_TIMEOUT, 60);
			$content = curl_exec( $ch );
			curl_close( $ch );
			return [$content];
		}
	}

	private function getUrlWithToken($url){
		$this->facebookCache->addRequest();

		$token = $this->accessToken;
		return $url . "&access_token={$token}";
	}

	private function getSubattachments($item){
		$attachments = [];
		if (isset($item->attachments) && isset($item->attachments->data) && sizeof($item->attachments->data) > 0){
			$data = $item->attachments->data[0];
			if (isset($data->media->image)){
				if (isset($item->status_type) && $item->status_type == 'shared_story'){
					$attachments[] = $data->media->image;
					return $attachments;
				}
				else $attachments[] = $data->media->image;
			}
			if (isset($data->subattachments) && isset($data->subattachments->data)){
				foreach ($data->subattachments->data as $el){
					$attachments[] = $el->media->image;
				}
			}
		}
		return $attachments;
	}

	/**
	 * TODO: check API in future, 'from' field was deprecated since Feb 2018
	 *
	 * @param $item
	 *
	 * @return stdClass
	 */
	private function getComment($item){
		$obj = new stdClass();
		$obj->id = $item->id;
		$obj->from = isset($item->from) ? $item->from : '';
		$obj->text = $item->message;
		$obj->created_time = $this->getSystemDate($item);
		return $obj;
	}

	private function getPageId(){
		$accessToken = $this->facebookCache->getAccessToken();
		if ($this->accessToken === false){
			$this->errors[] = $this->facebookCache->getError();
			throw new Exception();
		}
		$api = FFFacebook::API_VERSION;
		$url = "https://graph.facebook.com/{$api}/me/accounts?access_token={$accessToken}";

		$request = $this->getFeedData($url);
		$json = json_decode($request['response']);

		$pages = $json[ 'data' ];

		// get first in array

		$id = isset( $pages[ 0 ] ) && $pages[ 0 ][ 'id' ];

		return $id;
	}
}