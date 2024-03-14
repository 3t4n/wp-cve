<?php namespace flow\social;

use flow\social\cache\FFImageSizeCacheBase;
use InstagramAPI\Endpoints;
use InstagramAPI\Exception\InstagramAuthException;
use InstagramAPI\Exception\InstagramException;
use InstagramAPI\Exception\InstagramNotFoundException;
use InstagramAPI\Instagram;
use InstagramAPI\Model\Account;
use InstagramAPI\Model\Media;
use stdClass;
use Unirest\Request;

/**
 *
 * @author    navdeykin <navdeykin@gmail.com>
 * @copyright 2014-2020 Looks Awesome
 */
class FFInstagramOpenAPI implements FFInstagramAPI {
    private $url;
    private $userMeta = null;
    private $timeline;
    private $accounts = [];
    private $api;
    private $username = null;
    private $password = null;
    private $count = 0;
    private $feed;
    private $context;
    /** @var FFImageSizeCacheBase */
    private $imageCache;

    public function init( $context, $feed ) {
        $this->feed = $feed;
        $this->context = $context;
        $this->imageCache = $context['image_size_cache'];

        Request::verifyPeer(false);
        Request::verifyHost(false);
        Request::curlOpt( CURLOPT_IPRESOLVE, $feed->use_ipv4 ? CURL_IPRESOLVE_V4 : CURL_IPRESOLVE_V6);
        Request::curlOpt( CURLOPT_FOLLOWLOCATION, true);

        $this->username = $feed->instagram_login;
        $this->password = $feed->instagram_password;
    }

    public function deferredInit( $feed, $count ) {
        $this->count = $count;
        $accessToken = $feed->instagram_access_token;
        $this->url = "https://api.instagram.com/v1/users/self/media/recent/?access_token={$accessToken}&count={$count}&hl=en";
        if (isset($feed->{'timeline-type'})) {
            $this->timeline = $feed->{'timeline-type'};
            switch ($this->timeline) {
                case 'user_timeline':
                    $content = FFFeedUtils::preparePrefixContent($feed->content, '@');
                    $this->url = $content;
                    break;
                case 'tag':
                    $tag = FFFeedUtils::preparePrefixContent($feed->content, '#');
                    $this->url = $tag;
                    break;
                case 'location':
                    break;
                case 'coordinates':
                    $coordinates = explode(',', $feed->content);
                    $lat = trim($coordinates[0]);
                    $lng = trim($coordinates[1]);
                    $this->url = "https://api.instagram.com/v1/media/search?lat={$lat}&lng={$lng}&access_token={$accessToken}&count={$count}&hl=en";
                    break;
            }
        }
    }

    public function onePagePosts() {
        $instagram = $this->getApi();
        $medias = [];
        $forced_loading_of_post = false;
        $account = null;
        switch ($this->timeline){
            case 'user_timeline':
                $account = $this->getAccount($this->url);
                $this->userMeta = $this->fillUser($this->url);
                $account_id = $account->getId();
                $medias = $instagram->getMediasByUserId($account_id, $this->count);
                break;
            case 'tag':
                $medias = $instagram->getMediasByTag($this->url, $this->count);
                $forced_loading_of_post = true;
                break;
            case 'location':
                $locationID = $this->feed->content;
                $medias = $instagram->getMediasByLocationId($locationID, $this->count);
        }

        $result = [];
        $owner = null;
        foreach ( $medias as $media ) {
            try {
                if (is_null($owner) || $owner->getId() !== $media->getOwner()->getId()){
                    if (!is_null($account)){
                        $owner = $account;
                    }
                    else {
                        $media = $instagram->getMediaByUrl( $media->getLink() );
                        $owner = $media->getOwner();
                    }
                }

                $post = $this->altParsePost( $media, $owner, $forced_loading_of_post );
                if ( ! empty( $this->userMeta ) ) {
                    $post->userMeta = $this->userMeta;
                }
                $result[] = $post;
            } catch ( InstagramException $e ) {
            } catch ( InstagramNotFoundException $e ) {
            }
        }
        return $result;
    }

    public function nextPage( $result ) {
        return false;
    }

    /**
     * @param $item
     *
     * @return array
     * @throws InstagramAuthException
     * @throws InstagramException
     * @throws InstagramNotFoundException
     */
    public function getComments($item) {
        if (empty($item) || is_object($item)){
            return [];
        }

        $result = [];
        $objectId = $item;
        $instagram = $this->getApi();
        $code = $this->getCodeFromId($objectId);
        $mediaLink = Endpoints::getMediaPageLink($code);
        $media = $instagram->getMediaByUrl($mediaLink);
        $comments = array_slice($media->getComments(), 0, 5);
        foreach ( $comments as $comment ) {
            $from = new stdClass();
            $from->id = $comment->getOwner()->getId();
            $from->username = $comment->getOwner()->getUsername();
            $from->full_name = $comment->getOwner()->getFullName();
            $from->profile_picture = $comment->getOwner()->getProfilePicUrl();

            $c = new stdClass();
            $c->id = $comment->getId();
            $c->text = $comment->getText();
            $c->created_time = $comment->getCreatedAt();
            $c->from = $from;
            $result[] = $c;
        }
        return $result;
    }


    /**
     * @return Instagram
     * @throws InstagramAuthException
     * @throws InstagramException
     */
    private function getApi(){
        if ($this->api == null){
            if (!empty($this->username) && !empty($this->password)){
                $cache = FFFeedUtils::getCache($this->context);
                $this->api = Instagram::withCredentials($this->username, $this->password, $cache);
                $this->api->login();
            }
            else
            {
                $this->api = new Instagram();
            }
        }
        return $this->api;
    }

    /**
     * @param Media $post
     * @param $account
     * @param bool $forced_loading_of_post
     *
     * @return stdClass
     * @noinspection PhpUnusedParameterInspection
     */
    private function altParsePost($post, $account, $forced_loading_of_post = false) {
        $tc = new stdClass();
        $tc->id = $post->getId();
        $tc->header = '';
        $tc->nickname = $account->getUsername();
        $tc->screenname = FFFeedUtils::removeEmoji($account->getFullName());
        $tc->userpic = !empty($account->getProfilePicUrlHd()) ? $account->getProfilePicUrlHd() : $account->getProfilePicUrl();
        $tc->system_timestamp = $post->getCreatedTime();
        $tc->carousel = [];

        $max_thumbnail = $min_thumbnail = 0;
        foreach ( $post->getThumbnailResources() as $width => $thumbnail ) {
            if ($max_thumbnail < $width && $width <= 1000) $max_thumbnail = $width;
            if (300 < $width && $width <= 700) $min_thumbnail = $width;
        }

        if ($min_thumbnail == 0) $min_thumbnail = $max_thumbnail;
        $min_thumbnail = $post->getThumbnailResources()[$min_thumbnail];
        $max_thumbnail = $post->getThumbnailResources()[$max_thumbnail];

        $tc->img = [ 'url' => $min_thumbnail->url, 'width' => $min_thumbnail->width, 'height' => $min_thumbnail->height ];

        if (Media::TYPE_VIDEO == $post->getType()){
            $tc->media = [ 'type' => 'video/mp4', 'url' => $post->getVideoStandardResolutionUrl(), 'width' => $max_thumbnail->width, 'height' => $max_thumbnail->height ];
        }
        else if (Media::TYPE_SIDECAR == $post->getType() && !empty($post->getSidecarMedias())){
            foreach ($post->getSidecarMedias()  as $item ) {
                $tc->carousel[] = $this->getMedia( $item );
            }
            $tc->media = $tc->carousel[0];
        }
        else if (Media::TYPE_CAROUSEL == $post->getType() && !empty($post->getCarouselMedia())){
            foreach ($post->getCarouselMedia()  as $item ) {
                $tc->carousel[] = $this->getMedia( $item );
            }
            $tc->media = $tc->carousel[0];
        }
        else {
            $tc->media = $this->getMedia( $post );
        }
        $tc->text = FFFeedUtils::hashtagLinks($post->getCaption());
        $tc->userlink = 'http://instagram.com/' . $tc->nickname;
        $tc->permalink = $post->getLink();
        $tc->location = empty($post->getLocation()) ? null : (object)$post->getLocation();
        $tc->additional = [ 'likes' => (string)$post->getLikesCount(), 'comments' => (string)$post->getCommentsCount() ];

        return $tc;
    }

    private function getCodeFromId($id) {
        $parts = explode('_', $id);
        $id = $parts[0];
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
        $code = '';
        while ($id > 0) {
            if (PHP_INT_SIZE === 4 && function_exists('bcmod')){
                /** @noinspection PhpElementIsNotAvailableInCurrentPhpVersionInspection */
                $remainder = bcmod($id, 64);
                $t         = bcsub($id, $remainder);
                $id        = bcdiv($t, 64);
            }
            else {
                $remainder = $id % 64;
                $id = ($id - $remainder) / 64;
            }
            $code = $alphabet{$remainder} . $code;
        }
        return $code;
    }

    /**
     * @param $username
     *
     * @return stdClass
     * @throws InstagramAuthException
     * @throws InstagramException
     * @throws LASocialException
     */
    private function fillUser($username){
        $account = $this->getAccount($username);
        $result = new stdClass();
        $result->username = $account->getUsername();
        $result->full_name = $account->getFullName();
        $result->id = $account->getId();
        $result->bio = $account->getBiography();
        $result->website = $account->getExternalUrl();
        $result->counts = new stdClass();
        $result->counts->media = $account->getMediaCount();
        $result->counts->follows = $account->getFollowsCount();
        $result->counts->followed_by = $account->getFollowedByCount();
        $result->profile_picture = $account->getProfilePicUrlHd();
        return $result;
    }

    /**
     * @param string $username
     *
     * @return Account
     * @throws InstagramAuthException
     * @throws InstagramException
     * @throws LASocialException
     */
    private function getAccount($username){
        if (!array_key_exists($username, $this->accounts)){
            try {
                $this->accounts[$username] = $this->getApi()->getAccount($username);
            } catch ( InstagramNotFoundException $e ) {
                throw new LASocialException('Username not found', [], $e);
            }
        }
        return $this->accounts[$username];
    }

    /**
     * @param string $id
     *
     * @return Account
     * @throws InstagramAuthException
     * @throws InstagramException
     * @throws LASocialException
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function getAccountById($id){
        if (!array_key_exists($id, $this->accounts)){
            try {
                $this->accounts[$id] = $this->getApi()->getAccountById($id);
            } catch ( InstagramNotFoundException $e ) {
                throw new LASocialException('Username not found', [], $e);
            }
        }
        return $this->accounts[$id];
    }

    /**
     * @param Media $item
     * @return array
     */
    private function getMedia( Media $item ) {
        $width          = max( array_keys( $item->getThumbnailResources() ) );
        $max_thumbnail  = $item->getThumbnailResources()[ $width ];
        $type           = Media::TYPE_IMAGE == $item->getType() ? Media::TYPE_IMAGE : 'video/mp4';
        return [ 'type'   => $type,
                 'url'    => $max_thumbnail->url,
                 'width'  => $max_thumbnail->width,
                 'height' => $max_thumbnail->height
        ];
    }
}