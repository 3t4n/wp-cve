<?php namespace flow\social;
if ( ! defined( 'WPINC' ) ) die;

use Cake\Cache\Engine\FileEngine;
use Cake\Cache\SimpleCacheEngine;
use Exception;

/**
 * Flow-Flow.
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 * @link      http://looks-awesome.com
 * @copyright 2014-2016 Looks Awesome
 */
class FFFeedUtils{
	private static $USER_AGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36';

    /**
     * @param int $templateWidth
     * @param int $originalWidth
     * @param int $originalHeight
     * @return int|string
     */
    public static function getScaleHeight($templateWidth, $originalWidth, $originalHeight){
        if (isset($originalWidth) && isset($originalHeight) && !empty($originalWidth)){
            $k = $templateWidth / $originalWidth;
            return (int)round( $originalHeight * $k );
        }
        return '';
    }

    /**
     * @param $url
     * @param int $timeout
     * @param bool $header
     * @param bool $log
     * @param bool $followLocation
     * @param bool $useIpv4
     *
     * @return array
     * @throws LASocialRequestException
     * @throws Exception
     */
    public static function getFeedDataWithThrowException($url, $timeout = 60, $header = false, $log = true, $followLocation = true, $useIpv4 = true){
        $response = self::getFeedData($url, $timeout, $header, $log, $followLocation, $useIpv4);

        if (sizeof($response['errors']) > 0){
            $message = isset($response['errors'][0]['msg']) ? $response['errors'][0]['msg'] : '';
            if ($message == 'An unknown error has occurred.' && strpos($url, 'comments.summary(true),') > 0){
                $url = str_replace('comments.summary(true),', '', $url);
                $response = self::getFeedDataWithThrowException($url, $timeout, $header, $log, $followLocation, $useIpv4);
            }
            else {
                throw new LASocialRequestException($url, $response['errors'], $message);
            }
        }
        return $response;
    }

	/**
	 * @param string $url
	 * @param int $timeout
	 * @param bool|array $header
	 * @param bool $log
	 *
	 * @param bool $followLocation
	 * @param bool $useIpv4
	 *
	 * @return array
	 */
	public static function getFeedData($url, $timeout = 60, $header = false, $log = true, $followLocation = true, $useIpv4 = true){
		$c = curl_init();
		curl_setopt($c, CURLOPT_USERAGENT, self::$USER_AGENT);
		curl_setopt($c, CURLOPT_URL,$url);
		curl_setopt($c, CURLOPT_POST, 0);
		curl_setopt($c, CURLOPT_FAILONERROR, true);
		
		// Enable if you have 'Network is unreachable' error
		if ($useIpv4) curl_setopt( $c, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		if ($followLocation) curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($c, CURLOPT_AUTOREFERER, true);
		curl_setopt($c, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($c, CURLOPT_VERBOSE, false);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        /** @noinspection PhpElementIsNotAvailableInCurrentPhpVersionInspection */
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
		if (isset($_COOKIE['XDEBUG_SESSION']) && $_COOKIE['XDEBUG_SESSION'] == 'PHPSTORM') curl_setopt($c, CURLOPT_COOKIE, 'XDEBUG_SESSION=PHPSTORM');
		if ($timeout != null)   curl_setopt($c, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($c, CURLOPT_CONNECTTIMEOUT_MS, 5000);
		if (is_array($header))  curl_setopt($c, CURLOPT_HTTPHEADER, $header);
		$page = ($followLocation) ? curl_exec($c) : self::curl_exec_follow($c);
		$error = curl_error($c);
		$errors = array();
		if (strlen($error) > 0){
			if ($log) {
				if (isset($_REQUEST['debug'])) {
					echo 'DEBUG:: <br>';
					var_dump($error);
					echo '<br>';
					var_dump(debug_backtrace());
					echo 'URL: ' . $url;
					echo '<br>-------<br>';
					error_log(print_r($error, true));
					error_log(print_r(debug_backtrace(), true));
					error_log($url);
				}
			}
			if (strpos($error, 'Failed to connect') !== false && strpos($error, 'Network is unreachable') !== false){
				curl_setopt($c, CURLOPT_FAILONERROR, false);
				$page = ($followLocation) ? curl_exec($c) : self::curl_exec_follow($c);
				$error2 = curl_error($c);
				if (strlen($error2) > 0){
					$error .= '. Please, enable "USE IPV4 PROTOCOL" option at the settings tab.';
					$errors[] = array('msg' => $error, 'url' => $url);
					error_log('FFFeedUtils line 110 :: ' . $error);
					error_log('FFFeedUtils line 111 :: ' . $error2);
				}
				curl_close($c);
				return array('response' => $page, 'errors' => $errors);
			}
			else if ((strpos($url, 'https://graph.facebook.com') === 0) ||
			         (strpos($url, 'https://api.instagram.com') === 0) ||
			         (strpos($url, 'https://api.linkedin.com') === 0) ||
			         (strpos($url, 'https://www.googleapis.com') === 0)
			) {
				curl_setopt($c, CURLOPT_FAILONERROR, false);
				$body = ($followLocation) ? curl_exec($c) : self::curl_exec_follow($c);
				$body = json_decode($body);
				if (isset($body->error->message)) $error = $body->error->message;
				else if (isset($body->meta->error_message)) $error = $body->meta->error_message;
				else if (isset($body->message)) $error = $body->message;
				else if (!is_null($body)) $error = 'Problem with parsing the error data';//print_r($body, true);
			}
			$errors[] = array('msg' => $error, 'url' => $url);
		} else if (200 != $http_code = curl_getinfo($c, CURLINFO_HTTP_CODE)){
			$errors[] = ['msg' => 'Unexpected HTTP code: ' . $http_code, 'url' => $url];
		}
		curl_close($c);
		return array('response' => $page, 'errors' => $errors);
    }

	/**
	 * @param string $text
	 * @return mixed
	 */
	public static function removeEmoji($text) {
		if (defined('FF_REMOVE_EMOJI') && !FF_REMOVE_EMOJI){
			return $text;
		}

		// Match Emoticons
		$regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
		$clean_text = preg_replace($regexEmoticons, '', $text);

		// Match Miscellaneous Symbols and Pictographs
		$regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
		$clean_text = preg_replace($regexSymbols, '', $clean_text);

		// Match Transport And Map Symbols
		$regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
		$clean_text = preg_replace($regexTransport, '', $clean_text);

		// Match Miscellaneous Symbols
		$regexMisc = '/[\x{2600}-\x{26FF}]/u';
		$clean_text = preg_replace($regexMisc, '', $clean_text);

		// Match Dingbats
		$regexDingbats = '/[\x{2700}-\x{27BF}]/u';
		$clean_text = preg_replace($regexDingbats, '', $clean_text);

		return $clean_text;
	}

	/**
	 * @param string $source
	 * @return mixed
	 */
	public static function wrapLinks($source){
		$pattern = '/(https?:\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?)/i';
		$replacement = '<a href="$1">$1</a>';
		return preg_replace($pattern, $replacement, $source);
	}

	public static function getUrlFromImg($tag){
		preg_match("/\<img.+src\=(?:\"|\')(.+?)(?:\"|\')(?:.+?)\>/", $tag, $matches);
		return $matches[1];
	}

	public static function preparePrefixContent($content, $prefix){
		if (strpos($content, $prefix) === 0){
			return str_replace($prefix, '', $content);
		}
		return $content;
	}

	private static function curl_exec_follow($ch, &$maxRedirect = null) {
		$mr = $maxRedirect === null ? 5 : intval($maxRedirect);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

		if ($mr > 0) {
			$original_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
			$newUrl = $original_url;

			$rch = curl_copy_handle($ch);

			curl_setopt($rch, CURLOPT_HEADER, true);
			curl_setopt($rch, CURLOPT_NOBODY, true);
			curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
			do {
				curl_setopt($rch, CURLOPT_URL, $newUrl);
				$header = curl_exec($rch);
				if (curl_errno($rch)) {
					$code = 0;
				} else {
					$code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
					if ($code == 301 || $code == 302) {
						preg_match('/Location:(.*?)\n/i', $header, $matches);
						$newUrl = trim(array_pop($matches));

						// if no scheme is present then the new url is a
						// relative path and thus needs some extra care
						if(!preg_match("/^https?:/i", $newUrl)){
							$newUrl = $original_url . $newUrl;
						}
					} else {
						$code = 0;
					}
				}
			} while ($code && --$mr);

			curl_close($rch);

			if (!$mr) {
				if ($maxRedirect === null)
					trigger_error('Too many redirects.', E_USER_WARNING);
				else
					$maxRedirect = 0;

				return false;
			}
			curl_setopt($ch, CURLOPT_URL, $newUrl);
		}
		return curl_exec($ch);
	}

    public static function hashtagLinks($text) {
        $result = preg_replace('~(\#)([^\s!,. /()"\'?]+)~', '<a href="https://www.instagram.com/explore/tags/$2">#$2</a>', $text);
        $result = FFFeedUtils::removeEmoji($result);
        return $result;
    }


    /**
     * @param null | array $context
     *
     * @return SimpleCacheEngine
     */
    public static function getCache($context = null){
        $slug = 'flow-flow';
        if (!is_null($context) && isset($context['slug'])){
            $slug = $context['slug'];
        }
        if (defined('FF_CACHE_PATH')){
            $session_folder = FF_CACHE_PATH . '/sessions';
        }
        else {
            $session_folder = WP_CONTENT_DIR . '/resources/' . $slug . '/cache/sessions';
        }
        $fileEngine = new FileEngine();
        $fileEngine->init([
            'duration' => '+1 hours',
            'path' => $session_folder,
            'prefix' => 'ff_instagram_']);
        return new SimpleCacheEngine($fileEngine);
    }
}
