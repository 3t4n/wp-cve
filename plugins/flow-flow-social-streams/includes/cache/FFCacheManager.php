<?php namespace flow\cache;
if ( ! defined( 'WPINC' ) ) die;

use DateTime;
use DateTimeZone;
use Exception;
use flow\db\FFDB;
use flow\db\FFDBManager;
use flow\settings\FFSettingsUtils;
use flow\settings\FFGeneralSettings;
use flow\settings\FFStreamSettings;
use flow\social\FFFeed;
use stdClass;

/**
 * Flow-Flow.
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>

 * @link      http://looks-awesome.com
 * @copyright Looks Awesome
 *
 * @property FFStreamSettings stream
 */
class FFCacheManager {
	/** @var  FFDBManager */
    private $db;
	private $force;
	private $stream;
	private $hash = '';
	private $errors = [];
	
	function __construct($context = null, $force = false){
		$this->force = $force;
		$this->db = $context['db_manager'];
	}
	
	/**
	 * @param array $feeds
	 * @param bool $disableCache
	 *
	 * @throws Exception
	 * @return array
	 */
	public function posts($feeds, $disableCache){
		if (isset($_REQUEST['clean']) && $_REQUEST['clean']) $this->db->clean();
		if (isset($_REQUEST['clean-stream']) && $_REQUEST['clean-stream']) $this->db->clean( [ $this->stream->getId() ] );
		if ($this->force){
			$hasNewItems = false;
			$this->hash = time();
			/** @var FFFeed $feed */
			foreach ( $feeds as $feed_id => $feed ) {
				try{
                    $status = ['status' => empty($feed->feed->errors) ? '1' : '0', 'errors' => serialize([])];
					if ($this->expiredLifeTime($feed_id)) {
						$exist_feed_ids = $this->db->getIdPosts($feed_id);
						
						$posts = $feed->posts(empty($exist_feed_ids));
						
						$errors = $feed->errors();
						$countGotPosts = sizeof( $posts );
						$criticalError = ($countGotPosts == 0 && sizeof($errors) > 0 && $feed->hasCriticalError());
						$status = ['last_update' => $criticalError ? 0 : time(), 'errors' => $this->serializeErrors($errors), 'status' => (int)(!$criticalError)];
						
						if (!$criticalError){
							list($new_posts, $existed_posts) = $this->separation($exist_feed_ids, $posts);
							$countPosts4Insert = sizeof($new_posts);
							if (FFDB::beginTransaction()){
								if ('facebook' == $feed->getType()){
									if ($countPosts4Insert > 0) {
										$this->save( $feed, $new_posts);
									}
                                    $this->db->updateAdditionalInfo($existed_posts);
								}
								else {
									$this->save( $feed, $posts);
								}

								if ($countPosts4Insert > 0) {
									$hasNewItems = true;
								}
							}
						}
						if (isset($feed->feed->system_enabled) && $feed->feed->system_enabled != (int)!$criticalError) {
							$this->db->systemDisableSource($feed_id, (int)!$criticalError);
						}
                    }
                    $this->db->saveSource($feed_id, $status);
                    FFDB::commit();
                }
				catch( Exception $e){
					FFDB::rollback();
					$hasNewItems = false;
					$errors = [];
					$errors[] = [
						'type' => $feed->getType(),
						'message' => $e->getMessage(),
						'code' => $e->getCode()
					];
                    $status = ['last_update' => 0, 'errors' => $this->serializeErrors($errors), 'status' => 0, 'system_enabled' => 0, 'send_email' => 0];
                    $this->db->saveSource($feed->id(), $status);
					FFDB::commit();
				}
			}
			
			if ($hasNewItems){
				$this->removeOldRecords();
				FFDB::commit();
			}
			
			FFDB::rollbackAndClose();
			return [];
		} else {
			if (empty($_REQUEST['hash']) || $disableCache){
				$this->force = true;
				$_REQUEST['force'] = true;
				$this->posts($feeds, $disableCache);
				unset($_REQUEST['force']);
				$_REQUEST['hash'] = $this->hash();
			}
			return $this->get();
		}
	}
	
	public function hash(){
		return $this->encodeHash($this->hash);
	}

	public function transientHash($streamId){
		$hash = $this->db->getLastUpdateHash($streamId);
		return (false !== $hash) ? $this->encodeHash($hash) : '';
	}

	public function errors(){
		return $this->errors;
	}

	/**
	 * @param FFStreamSettings $stream
	 * @return void
	 */
	public function setStream($stream) {
		$this->stream = $stream;
	}

	protected function getGetFields(){
		$select  = "post.post_id as id, post.post_type as type, post.user_nickname as nickname, ";
		$select .= "post.user_pic as userpic, ";
		$select .= "post.post_timestamp as system_timestamp, ";
		$select .= "post.user_link as userlink, post.post_permalink as permalink, ";
		$select .= "post.image_url, post.image_width, post.image_height, post.media_url, post.media_type, ";
		$select .= "post.user_counts_media, post.user_counts_follows, post.user_counts_followed_by, ";
		$select .= "post.post_source, post.post_additional, post.feed_id, ";
		$select .= "post.user_screenname as screenname, post.post_header, post.post_text as text, post.user_bio ";
		return $select;
	}

	protected function getGetFilters(){
		$args[] = FFDB::conn()->parse('stream.stream_id = ?s', $this->stream->getId());
		$args[] = FFDB::conn()->parse('cach.enabled = 1');
		$args[] = FFDB::conn()->parse('cach.boosted = \'nope\'');
		if ($this->stream->showOnlyMediaPosts()) $args[] = "post.image_url IS NOT NULL";
		if (isset($_REQUEST['hash']))
			if (isset($_REQUEST['recent'])){
				$args[] = FFDB::conn()->parse('post.creation_index > ?s', $this->decodeHash($_REQUEST['hash']));
			} else {
				$args[] = FFDB::conn()->parse('post.creation_index <= ?s', $this->decodeHash($_REQUEST['hash']));
			}
		return $args;
	}

	/** @noinspection PhpUnusedParameterInspection */
	protected function getOnlyNew($moderation){
		return [];
	}

    /**
     * @return mixed
     */
    private function get(){
        $where = implode(' AND ', $this->getGetFilters());

	    $moderation = [];
	    foreach ( $this->stream->getAllFeeds() as $feed ) {
		    $moderation[$feed['id']] = FFSettingsUtils::YepNope2ClassicStyleSafe($feed, 'mod', false);
	    }

	    $limit = null;
	    $offset = null;
	    $result = [];
	    if (!isset($_REQUEST['recent'])){
		    $page = isset($_REQUEST['page']) ? (int) $_REQUEST['page'] : 0;
		    $limit = $this->stream->getCountOfPostsOnPage();
		    $offset = $page * $limit;

		    if ($page == 0){
			    $result = $this->getOnlyNew($moderation);
			    if (!isset($_REQUEST['countOfPages'])){
				    $totalCount = $this->db->countPostsIf($where);
				    if ($totalCount === false) $totalCount = 0;
				    $countOfPages = ($limit > $totalCount) ? 1 : ceil($totalCount / $limit);
				    $_REQUEST['countOfPages'] = $countOfPages;
			    }
		    }
	    }
	    $resultFromDB = $this->db->getPostsIf($this->getGetFields(), $where, 'post.post_timestamp DESC, post.post_id', $offset, $limit);
	    if (false === $resultFromDB) $resultFromDB = [];

	    foreach ( $resultFromDB as $row ) {
		    $result[] = $this->buildPost($row, $moderation[$row['feed_id']]);
	    }

	    $this->hash = $this->db->getHashIf($where);
	    FFDB::close();
	    return $result;
    }

    /**
     * @param $feed
     * @param $value
     *
     * @throws Exception
     * @return void
     */
    private function save( $feed, $value ) {
        if (sizeof($value) > 0) {
            $timezone = get_option( 'timezone_string' );

            $only4insertPartOfSqlTemplate =
                FFDB::conn()->parse('`creation_index`=?i', $this->hash);

            $status = $this->getDefaultStreamStatus($feed);
            foreach ($value as $id => $post){
                $feed_id = $post->feed_id;

                $imagePartOfSql = '';
                if (isset($post->img)){
                    $img = (array)$post->img;
                    if (sizeof($img) == 3) {
                        $imagePartOfSql = FFDB::conn()->parse('`image_url`=?s, `image_width`=?i, `image_height`=?i,',
                            $img['url'], $img['width'], $img['height']);
                    }
                }


                $only4insertPartOfSql = FFDB::conn()->parse('?p, ?u', $only4insertPartOfSqlTemplate, [
                    'feed_id' => $feed_id,
                    'post_id' => $post->id,
                    'post_type' => $post->type,
                    'post_permalink' => $post->permalink,
                    'user_nickname' => $post->nickname,
                    'user_screenname' => $post->screenname,
                    'user_pic' => $post->userpic,
                    'user_counts_media' => isset($post->userMeta->counts->media) ? $post->userMeta->counts->media : 0,
                    'user_counts_follows' => isset($post->userMeta->counts->follows) ? $post->userMeta->counts->follows : 0,
                    'user_counts_followed_by' => isset($post->userMeta->counts->followed_by) ? $post->userMeta->counts->followed_by : 0,
                    'user_link' => $post->userlink,
                    'post_source' => isset($post->source) ? $post->source : '',
                    'post_status' => $status
                ]);

                if (!isset($post->additional)) $post->additional = [];
                $common = [
                    'post_header' => @FFDB::conn()->conn->real_escape_string(trim($post->header)),
                    'post_text'   => $this->prepareText($post->text),
                    'post_timestamp' => $this->correctionTimeZone($post->system_timestamp, $timezone),
                    'post_additional' => json_encode($post->additional)
                ];

                $this->db->addOrUpdatePost($only4insertPartOfSql, $imagePartOfSql, '', $common);
            }
        }
    }

    private function correctionTimeZone($date, $timezone){
        // Create datetime object with desired timezone
        $timezone = empty($timezone) ? 'UTC' : $timezone;
        $local_timezone = new DateTimeZone($timezone);
        $date_time = new DateTime('now', $local_timezone);
        $offset = $date_time->format('P'); // + 05:00

        // Convert offset to number of hours
        $offset = explode(':', $offset);
        $offset2 = '';
        if($offset[1] == 00){ $offset2 = ''; }
        if($offset[1] == 30){ $offset2 = .5; }
        if($offset[1] == 45){ $offset2 = .75; }
	    $hours = floatval($offset[0] . $offset2);

        // Convert hours to seconds
        $seconds = $hours * 3600;

        // Add/Subtract number of seconds from given unix/gmt/utc timestamp
        $result = floor( $date + $seconds );
        return (int) $result;
    }

	/**
	 * @param $feedId
	 *
	 * @return bool
	 */
	private function expiredLifeTime($feedId){
		if (isset($_REQUEST['force']) && $_REQUEST['force']) return true;

		/** @noinspection SqlResolve */
		$sql = FFDB::conn()->parse('SELECT `cach`.`feed_id` FROM ?n `cach` WHERE `cach`.`feed_id`=?s AND (`cach`.last_update + `cach`.cache_lifetime * 60) < UNIX_TIMESTAMP()', $this->db->cache_table_name, $feedId);
		return (false !== FFDB::conn()->getOne($sql));
	}

	/**
	 * @param array $row
	 * @param bool $moderation
	 *
	 * @return stdClass
	 */
	protected function buildPost($row, $moderation = false){
		$post = new stdClass();
		$post->id = $row['id'];
		$post->type = $row['type'];
		$post->nickname = $row['nickname'];
		$post->userpic = $row['userpic'];
		$post->system_timestamp = $row['system_timestamp'];
		$post->timestamp = FFSettingsUtils::classicStyleDate($row['system_timestamp'], FFGeneralSettings::get()->dateStyle());
		$post->userlink = $row['userlink'];
		$post->user_counts_media = $row['user_counts_media'];
		$post->user_counts_follows = $row['user_counts_follows'];
		$post->user_counts_followed_by = $row['user_counts_followed_by'];
		$post->permalink = $row['permalink'];
        $post->screenname = $row['screenname'];
        $post->header = stripslashes($row['post_header']);
        $post->text = stripslashes($row['text']);

		$post->mod = $moderation;
		$post->feed = $row['feed_id'];
		$post->with_comments = false;

		if (!empty($row['post_source'])) $post->source = $row['post_source'];
		if ($row['image_url'] != null){
			$url = $row['image_url'];
			$width = $row['image_width'];
			$tWidth = $this->stream->getImageWidth();
			$height = FFSettingsUtils::getScaleHeight($tWidth, $width, $row['image_height']);
			if (($row['image_width'] == '-1') && ($row['image_height'] == '-1')) {
				$post->img = ['url' => $url, 'type' => 'image'];
			}
			else {
				$post->img = ['url' => $url, 'width' => $tWidth, 'height' => $height, 'type' => 'image'];
			}
			if ($post->type == 'twitter') {
				$post->text = str_replace('%WIDTH%', $post->img['width'], $post->text);
				$post->text = str_replace('%HEIGHT%', $post->img['height'], $post->text);
			}
		}
		$post->additional = json_decode($row['post_additional']);
		return $post;
	}
	
	private function separation( $exist_feed_ids, $posts ){
		$existed_posts = [];
		foreach ( $exist_feed_ids as $id ) {
			if (isset($posts[$id])) {
				$existed_posts[] = $posts[$id];
				unset($posts[$id]);
			}
		}
		return [$posts, $existed_posts];
	}
	
	private function encodeHash($hash){
		if (!empty($hash)){
			$postfix  = hash('md5', serialize($this->stream->original()));
			$postfix .= hash('md5', serialize(FFGeneralSettings::get()->original()));
			$postfix .= hash('md5', serialize(FFGeneralSettings::get()->originalAuth()));
			return $hash . "." . $postfix;
		}
		return $hash;
	}

	protected function decodeHash($hash){
		$pos = strpos($hash, ".");
		if ($pos === false) return $hash;
		if ($pos == 0) return '';
		return substr($hash, 0, $pos);
	}

	private function getDefaultStreamStatus($feed) {
		if ($this->moderation($feed)){
			if (defined('FF_SOFT_MODERATION_POLICY') && FF_SOFT_MODERATION_POLICY){
				return 'approved';
			}
			return 'new';
		}
		return 'approved';
	}

	private function moderation($feed) {
		if (isset($feed->feed->mod)){
			return FFSettingsUtils::YepNope2ClassicStyle($feed->feed->mod, false);
		}
		return false;
	}

	private function removeOldRecords() {
		$settings = $this->db->getGeneralSettings();
		$this->db->removeOldRecords($settings->getCountOfPostsByFeed());
	}

	private function prepareText( $text ) {
		$text = str_replace("\r\n", "<br>", $text);
		$text = str_replace("\n", "<br>", $text);
		$text = trim($text);
		return @FFDB::conn()->conn->real_escape_string($text);
	}

    private function serializeErrors($errors){
        foreach ( $errors as &$error ) {
            if (isset($error['url'])){
                $error['url'] = $this->prepareString4Serialize($error['url']);
            }
            if (isset($error['message'])){
                $error['message'] = $this->prepareString4Serialize($error['message']);
            }
        }
        return serialize($errors);
    }

    private function prepareString4Serialize( $str ) {
        $str = str_replace('?n', '%3fn', $str);
        $str = str_replace('?s', '%3fs', $str);
        $str = str_replace('?i', '%3fi', $str);
        $str = str_replace('?u', '%3fu', $str);
        $str = str_replace('?a', '%3fa', $str);
        $str = str_replace('?p', '%3fp', $str);
        return $str;
    }
}