<?php namespace flow\social;
if ( ! defined( 'WPINC' ) ) die;

use Exception;
use flow\social\cache\FFImageSizeCacheBase;
use InstagramAPI\Exception\InstagramException;
use stdClass;

/**
 * Flow-Flow.
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>

 * @link      http://looks-awesome.com
 * @copyright 2014-2016 Looks Awesome
 */
abstract class FFBaseFeed implements FFFeed{
	/** @var stdClass */
	public $feed;

	private $id;
	/** @var FFImageSizeCacheBase */
	protected $cache;
	private $count;
	private $imageWidth;
	private $type;
	/**
	 * Exclude words
	 * @var  array $filterByWords */
	private $filterByWords;
	/**
	 * Include words
	 * @var array $include */
	private $include;
	private $criticalError = true;
	/** @var array $errors */
	protected $errors;
	protected $context;

	function __construct( $type ) {
		$this->type = $type;
	}

	public function getType(){
		return $this->type;
	}

	public function id(){
        return $this->id;
    }

    public function getCount(){
        return $this->count;
    }

    /**
     * @return int
     */
    public function getImageWidth(){
        return $this->imageWidth;
    }

    /**
     * @return int
     */
    public function getAllowableWidth(){
        return 200;
    }

	/**
	 * @param $context
	 * @param $feed
	 *
	 * @return void
	 */
	public function init($context, $feed){
		$this->context = $context;
		$this->errors = [];
		$this->imageWidth = defined('FF_MAX_IMAGE_WIDTH') ? FF_MAX_IMAGE_WIDTH : 300;
		
		if (!is_null($feed)){
			$this->cache = $context['image_size_cache'];
			$this->feed = $feed;
			$this->id = $feed->id;
			if ($feed->last_update === 'N/A' && isset($context['count_posts_4init'])){
				$this->count = $context['count_posts_4init'];
			}
			else {
				$this->count = isset($feed->posts) ? intval($feed->posts) : 10;
			}
			if (isset($feed->{'include'}) && strlen($feed->{'include'}) > 0) {
				$this->include =  explode(',', $feed->{'include'});
				if ($this->include === false) $this->include = array();
			} else {
				$this->include = array();
			}
			if (isset($feed->{'filter-by-words'}) && strlen($feed->{'filter-by-words'}) > 0) {
				$this->filterByWords =  explode(',', $feed->{'filter-by-words'});
				if ($this->filterByWords === false) $this->filterByWords = array();
			} else {
				$this->filterByWords = array();
			}
		}
	}

    public final function posts($is_empty_feed) {
        $result = [];
        try {
            if ($is_empty_feed) {
                $this->count = defined('FF_FEED_INIT_COUNT_POSTS') ? FF_FEED_INIT_COUNT_POSTS : 50;
            }
            if ($this->beforeProcess()) {
                $this->deferredInit($this->feed);
                if (sizeof($this->errors) == 0){
                    do {
                        $result += $this->onePagePosts();
                    } while ($this->nextPage($result));
                    return $this->afterProcess($result);
                }
            }
        } catch (LASocialException $se){
            $error = $se->getSocialError();
            $error['type'] = $this->getType();
            $this->errors[] = $error;
            error_log($se->getMessage());
	        // error_log(print_r($se, true));
        } catch (InstagramException $ie){
            error_log('Failed request. Message: ' . $ie->getMessage());
            error_log('Failed request. Trace: ' . $ie->getTraceAsString());
            $message = 'Something went wrong. Please report issue.';
            if (!empty($ie->getCode())){
                $code = $ie->getCode();
                switch ($code) {
                    case 100: $text = 'Continue'; break;
                    case 101: $text = 'Switching Protocols'; break;
                    case 200: $text = 'OK'; break;
                    case 201: $text = 'Created'; break;
                    case 202: $text = 'Accepted'; break;
                    case 203: $text = 'Non-Authoritative Information'; break;
                    case 204: $text = 'No Content'; break;
                    case 205: $text = 'Reset Content'; break;
                    case 206: $text = 'Partial Content'; break;
                    case 300: $text = 'Multiple Choices'; break;
                    case 301: $text = 'Moved Permanently'; break;
                    case 302: $text = 'Moved Temporarily'; break;
                    case 303: $text = 'See Other'; break;
                    case 304: $text = 'Not Modified'; break;
                    case 305: $text = 'Use Proxy'; break;
                    case 400: $text = 'Bad Request'; break;
                    case 401: $text = 'Unauthorized'; break;
                    case 402: $text = 'Payment Required'; break;
                    case 403: $text = 'Forbidden'; break;
                    case 404: $text = 'Not Found'; break;
                    case 405: $text = 'Method Not Allowed'; break;
                    case 406: $text = 'Not Acceptable'; break;
                    case 407: $text = 'Proxy Authentication Required'; break;
                    case 408: $text = 'Request Time-out'; break;
                    case 409: $text = 'Conflict'; break;
                    case 410: $text = 'Gone'; break;
                    case 411: $text = 'Length Required'; break;
                    case 412: $text = 'Precondition Failed'; break;
                    case 413: $text = 'Request Entity Too Large'; break;
                    case 414: $text = 'Request-URI Too Large'; break;
                    case 415: $text = 'Unsupported Media Type'; break;
                    case 500: $text = 'Internal Server Error'; break;
                    case 501: $text = 'Not Implemented'; break;
                    case 502: $text = 'Bad Gateway'; break;
                    case 503: $text = 'Service Unavailable'; break;
                    case 504: $text = 'Gateway Time-out'; break;
                    case 505: $text = 'HTTP Version not supported'; break;
                    default:
                        $text = 'Unknown http status code "' . htmlentities($code) . '"';
                        break;
                }
                $message = "Response code is {$code}({$text}). " . $message;
            }
            $this->errors[] = [
                'type'    => $this->getType(),
                'message' => $message
            ];
        } catch (Exception $e){
            $this->errors[] = [
                'type' => $this->getType(),
                'message' => $e->getMessage(),
            ];
            error_log($e->getMessage());
            error_log(print_r($e, true));
        }
        $this->criticalError = true;
        return $result;
    }

	/**
	 * @param stdClass $feed
	 * @return void
	 */
	protected abstract function deferredInit($feed);
	protected abstract function onePagePosts( );

    /**
     * @return array
     */
    public function errors() {
        return $this->errors;
    }

	/**
	 * @param $url
	 * @param $width
	 * @param $height
	 * @param bool $scale
	 *
	 * @return array
	 */
    protected function createImage($url, $width = null, $height = null, $scale = true){
    	if ($width != -1 && $height != -1) {
		    if ($width == null || $height == null){
			    $size = $this->cache->size($url);
			    $width = $size['width'];
			    $height = $size['height'];
		    }
		    if ($scale){
			    $tWidth = $this->getImageWidth();
			    return array('url' => $url, 'width' => $tWidth, 'height' => FFFeedUtils::getScaleHeight($tWidth, $width, $height));
		    }
	    }
	    return array('url' => $url, 'width' => $width, 'height' => $height);
    }

	protected function createMedia($url, $width = null, $height = null, $type = 'image', $scale = false){
		if ($type == 'html'){
			return array('type' => $type, 'html' => $url);
		}
		if ($width == null || $height == null){
			$size = $this->cache->size($url);
			$width = $size['width'];
			$height = $size['height'];
		}
		if ($type == 'image' && $scale == true && $width > 600){
			$height = FFFeedUtils::getScaleHeight(600, $width, $height);
			$width = 600;
		}
		return array('type' => $type, 'url' => $url, 'width' => $width, 'height' => $height);
	}

    /**
     * @param string $link
     * @param string $name
     * @param mixed $image
     * @param mixed $width
     * @param mixed $height
     * @return array
     */
    protected function createAttachment($link, $name, $image = null, $width = null, $height = null){
        if ($image != null){
            if (is_string($image)) $image = $this->createImage($image, $width, $height);
            if ($image['width'] > $this->getAllowableWidth())
                return array( 'type' => 'article', 'url' => $link, 'displayName' => $name, 'image' => $image);
        }
        return array( 'type' => 'article', 'url' => $link, 'displayName' => $name);
    }

    protected function includePost($post)
    {
        if(count($this->include) == 0) return true;

        foreach ( $this->include as $word ) {
            $word = mb_strtolower($word);
            $firstLetter = mb_substr($word, 0, 1);

            if ($firstLetter !== false){
                switch ($firstLetter) {
                    case '@':
                        $word = mb_substr($word, 1);
                        if ((mb_strpos(mb_strtolower($post->screenname), $word) !== false) || (mb_strpos(mb_strtolower($post->nickname), $word) !== false)) {
                            return true;
                        }
                        break;
                    case '#':
                        $word = mb_substr($word, 1);
                        if (mb_strpos(mb_strtolower($post->permalink), $word) !== false) {
                            return true;
                        }
                        break;
                    case '$':
                        $word = mb_substr($word, 1);
                        if ( !empty($word) && ((mb_strpos( mb_strtolower( strip_tags( $post->text ) ), '#' . $word) !== false) || (isset($post->header) && mb_strpos( mb_strtolower( strip_tags( $post->header ) ), '#' . $word) !== false))) {
                            return true;
                        }
                        break;
                     default:
                        if ( !empty($word) && ((mb_strpos( mb_strtolower( strip_tags( $post->text ) ), $word) !== false) || (isset($post->header) && mb_strpos( mb_strtolower( strip_tags( $post->header ) ), $word) !== false))) {
                            return true;
                        }
                        break;
                }
            }
        }

        return false;
    }

    protected function excludePost($post)
    {
        if(count($this->filterByWords) == 0) return true;

        foreach ( $this->filterByWords as $word ) {
            $word = mb_strtolower($word);
            $firstLetter = mb_substr($word, 0, 1);
            if ($firstLetter !== false){
                switch ($firstLetter) {
                    case '@':
                        $word = mb_substr($word, 1);
                        if ((mb_strpos(mb_strtolower($post->screenname), $word) !== false) || (mb_strpos(mb_strtolower($post->nickname), $word) !== false)) {
                            return false;
                        }
                        break;
                    case '#':
                        $word = mb_substr($word, 1);
                        if (mb_strpos(mb_strtolower($post->permalink), $word) !== false) {
                            return false;
                        }
                        break;
                    default:
                        if (!empty($word) && ((mb_strpos(mb_strtolower($post->text), $word) !== false) || (isset($post->header) && mb_strpos(mb_strtolower($post->header), $word) !== false))) {
                            return false;
                        }
                }
            }
        }

        return true;
    }

	/**
	 * @param stdClass $post
	 * @return bool
	 */
	protected function isSuitablePost($post){
		if ($post == null) return false;

		$suitable = $this->includePost($post);
		if( $suitable ){
            $suitable = $this->excludePost($post);
        }

		return $suitable;
	}

	/**
	 * @return bool
	 */
	protected function beforeProcess(){
		return (sizeof($this->errors) == 0);
	}

    /**
     * @param $result array
     * @return array
     */
    protected function afterProcess($result){
        $this->cache->save();
	    $this->criticalError = empty($result) && sizeof($this->errors) > 0;
        return $result;
    }

	public function hasCriticalError() {
		return $this->criticalError;
	}

	/**
	 * @param array $result
	 * @return bool
	 */
	protected function nextPage($result){
		return false;
	}

    /**
     * @param $url
     * @param int $timeout
     * @param bool $header
     * @param bool $log
     *
     * @return array
     * @throws LASocialRequestException
     * @throws Exception
     *
     * @deprecated
     */
	protected function getFeedData($url, $timeout = 60, $header = false, $log = true){
		return FFFeedUtils::getFeedDataWithThrowException($url, $timeout, $header, $log, $this->feed->use_curl_follow_location, $this->feed->use_ipv4);
	}

	/**
	 * TODO Remove this method. Need to use LASocialException
	 *
	 * @deprecated
	 * @param $message
	 * @return string
	 */
	protected function filterErrorMessage($message){
		if (is_array($message)){
			if (sizeof($message) > 0 && isset($message[0]['msg'])){
				return stripslashes(htmlspecialchars($message[0]['msg'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
			}
			else {
				return '';
			}
		}
		return stripslashes(htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
	}

	protected function print2log($msg){
		error_log($msg);
	}
} 