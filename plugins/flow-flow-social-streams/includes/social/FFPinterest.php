<?php namespace flow\social;
if ( ! defined( 'WPINC' ) ) die;

use DateTime;
use SimpleXMLElement;

/**
 * Flow-Flow.
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>

 * @link      http://looks-awesome.com
 * @copyright 2014-2016 Looks Awesome
 */
class FFPinterest extends FFHttpRequestFeed {
    private $pin;
    private $pins;
    private $nickname;
    private $additionalUrl;
    private $originalContent;
    private $image;
    private $screenName;
    private $profileImage;
    private $hideCaption;

    public function __construct() {
        parent::__construct( 'pinterest' );
        $this->pins = [];
        $this->hideCaption = false;
    }

    protected function getId($item){
        return hash('md5', isset($item->guid) ? (string)$item->guid : (string) $item->link);
    }

    public function deferredInit($feed) {
        $this->url = $feed->content;
        $this->hideCaption = $feed->{'hide-caption'};
        if (isset($feed->{'channel-name'})) $this->screenName = $feed->{'channel-name'};
        $this->profileImage = isset($feed->{'avatar-url'}) && trim($feed->{'avatar-url'}) != ''?
            $feed->{'avatar-url'} : $this->context['plugin_url'] . '/' . $this->context['slug'] . '/assets/avatar_default_rss.png';

        $sp = explode('/', $feed->content);
        if (sizeof($sp) < 2){
            $this->nickname = $feed->content;
            $this->url = "https://www.pinterest.com/{$feed->content}/feed.rss";
            $this->additionalUrl = "https://api.pinterest.com/v3/pidgets/users/{$feed->content}/pins/";
        }
        else {
            $this->nickname = $sp[0];
            $content = $sp[0] . '/' . urlencode($sp[1]);
            $this->url = "https://www.pinterest.com/{$content}.rss";
            $this->additionalUrl = "https://api.pinterest.com/v3/pidgets/boards/{$content}/pins/";
        }
        $this->profileImage = $this->context['plugin_url'] . '/' . $this->context['slug'] . '/assets/avatar_default.png';
    }

    /**
     * @param $request
     *
     * @return array|SimpleXMLElement
     * @throws LASocialRequestException
     */
    protected function items($request){
        $this->setAdditionalInfo();

        libxml_use_internal_errors(true);
        $pxml = new SimpleXMLElement($request);
        $result = [];
        if ($pxml && isset($pxml->channel)) {
            if (!isset($this->screenName) || strlen($this->screenName) == 0) {
                $this->screenName = (string)$pxml->channel->title;
            }
            if (sizeof($pxml->channel->item) > $this->getCount())
                for ($i=0; $i < $this->getCount(); $i++)  $result[] = $pxml->channel->item[$i];
            else
                $result = $pxml->channel->item;
        }
        libxml_clear_errors();
        libxml_use_internal_errors(false);

        return $result;
    }

    protected function prepare($item){
        $this->originalContent = (string)$item->description;
        $key = str_replace('https:', 'http:', (string)$item->guid);
        array_key_exists($key, $this->pins) ? $this->pin = $this->pins[$key] : $this->pin = null;
        $this->image = null;
        return parent::prepare( $item );
    }

    protected function getScreenName($item){
        return is_null($this->pin) ? $this->screenName : $this->pin->pinner->full_name;
    }

    protected function getProfileImage($item){
        $url = is_null($this->pin) ? $this->profileImage : $this->pin->pinner->image_small_url;
        $url = str_replace('30x30_', '140x140_', $url);
        return $url;
    }

    protected function getContent($item){
        return is_null($this->pin) ? ($this->hideCaption ? '' : $item->title) : $this->pin->description;
    }

    protected function getHeader($item){
        return '';
    }

    protected function getSystemDate($item){
        if (isset($item->pubDate)) return strtotime($item->pubDate);
        $d = new DateTime(); return $d->getTimestamp();
    }

    protected function getPermalink($item){
        return (string)$item->guid;
    }

    protected function getUserlink($item){
        return is_null($this->pin) ? 'http://www.pinterest.com/' . $this->nickname : $this->pin->pinner->profile_url;
    }

    protected function getImage($item){
        return $this->image;
    }

    protected function customize($post, $item){
        $post = parent::customize($post, $item);
        $post->nickname = $this->nickname;
        return $post;
    }

    protected function getAdditionalInfo( $item ) {
        $additional = parent::getAdditionalInfo( $item );
        $additional['likes']  = (string)@$this->pin->board->pin_count;
        $additional['shares'] = (string)@$this->pin->repin_count;
        return $additional;
    }

    protected function showImage($item){
        if (!is_null($this->pin) && isset($this->pin->images->{'237x'})){
            $x237 = $this->pin->images->{'237x'};
            $this->image = $this->createImage($x237->url, $x237->width, $x237->height);
        } else {
            $this->image = $this->createImage(FFFeedUtils::getUrlFromImg($this->originalContent));
        }
        return true;
    }

    /**
     * @throws LASocialRequestException
     */
    private function setAdditionalInfo(){
        $data = FFFeedUtils::getFeedDataWithThrowException($this->additionalUrl, 60, false, true, $this->feed->use_curl_follow_location, $this->feed->use_ipv4);
        if (sizeof($data['errors']) > 0){
            $this->errors[] = [
                'type'    => 'pinterest',
                'message' =>  print_r(isset($data['errors']['msg']) ? $data['errors']['msg'] : 'No error message', true),
                'url' => $this->additionalUrl
            ];
        } else {
            $response = json_decode($data['response']);
            foreach ($response->data->pins as $pin){
                $this->pins["http://www.pinterest.com/pin/{$pin->id}/"] = $pin;
            }
        }
    }
}