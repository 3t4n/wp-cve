<?php
namespace WPRemoteMediaExt\RemoteMediaExt\Accounts\Youtube;

use WPRemoteMediaExt\RemoteMediaExt\Accounts\AbstractRemoteAccount;
use WPRemoteMediaExt\RemoteMediaExt\Accounts\AbstractRemoteService;
use WPRemoteMediaExt\RemoteMediaExt\Library\MediaTemplate;
use WPRemoteMediaExt\WPCore\Cache\Transient;
use WPRemoteMediaExt\WPCore\View;
use WPRemoteMediaExt\WPForms\FieldSet;

class Service extends AbstractRemoteService
{
    protected $key = "AIzaSyB9Dk0uisM1dAnvAT0AKgVBwAF3TlIC-aI";

    public function __construct()
    {
        parent::__construct(__('Youtube', 'remote-medias-lite'), 'youtube');

        $client = Client::factory();
        $this->setClient($client);
    }

    public function init()
    {
        if (is_admin()) {

            $this->mediaSettings = array('uploadTemplate' => 'media-upload-youtube-upgrade');
            $this->hook(new MediaTemplate(new View($this->getViewsPath().'admin/media-upload-youtube.php')));

            //FieldSets need to be initialized early because they hook needed JS and CSS for fields added
            $this->initFieldSet();
        }
    }

    public function initFieldSet()
    {
        $this->fieldSet = new FieldSet();

        $userSettingsUrl = 'https://www.youtube.com/account_advanced';

        $field = array(
            'label' => __("Source type", 'remote-medias-lite'),
            'type' => 'Select',
            'class' => $this->getSlug().' allyoutube',
            'id' => 'yt_source_type',
            'default' => 'ytchannel',
            'options' => array(
                'ytchannel' => __("YouTube Channel", 'remote-medias-lite'),
                'ytplaylist' => __("YouTube Playlist", 'remote-medias-lite'),
            ),
            'name' => 'account_meta['.$this->getSlug().'][youtube_yt_source_type]',
            'desc' => __("Select which type of youtube source you want this library to access", 'remote-medias-lite'),
        );
        $this->fieldSet->addField($field);

        $field = array(
            'label' => __("YouTube Channel ID", 'remote-medias-lite'),
            'type' => 'Text',
            'class' => $this->getSlug().' ytchannel',
            'id' => 'remote_user_id',
            'name' => 'account_meta['.$this->getSlug().'][youtube_remote_user_id]',
            'desc' => sprintf(__("Insert the Youtube Channel ID for this library. You can get yours on %syour account information page%s or extract it from channel URL like <b>www.youtube.com/channel/[channel ID]</b>", 'remote-medias-lite'), '<a href="'.$userSettingsUrl.'" target="_blank">', '</a>'),
        );
        $this->fieldSet->addField($field);
        $field = array(
            'label' => __("YouTube Playlist ID", 'remote-medias-lite'),
            'type' => 'Text',
            'class' => $this->getSlug().' ytplaylist',
            'id' => 'playlist_id',
            'name' => 'account_meta['.$this->getSlug().'][youtube_playlist_id]',
            'desc' => __("Insert the Playlist ID for this library. Playlists IDs can be found from playlist URL like <b>www.youtube.com/playlist?list=[playlist ID]</b>.", 'remote-medias-lite'),
        );
        $this->fieldSet->addField($field);
    }

    public function setAccount(AbstractRemoteAccount $account)
    {
        $this->account = $account;
    }

    public function validate()
    {
        $sourceType = $this->account->get('youtube_yt_source_type', 'ytchannel');

        switch ($sourceType) {
            case 'ytplaylist':
                $playlistId = $this->account->get('youtube_playlist_id', '');
                if (empty($playlistId)) {
                    return false;
                }

                $playlist = $this->getPlaylistItems($playlistId, 1);
        
                if (!empty($playlist['etag'])) {
                    return true;
                }

                return false;
            case 'ytchannel':
            default:
                //If playlist ID is not set, validate using channel ID
                $channels = $this->getUserChannels('id', 1);

                foreach ($channels['items'] as $channel) {
                    if (!empty($channel['id'])) {
                        return true;
                    }
                }

                return false;
        }
        

        return false;
    }

    public function getUserChannels($part = 'contentDetails', $maxResults = 50)
    {
        $error = false;
        $channels = array(
            'items' => array()
        );

        $params = array(
            'part' => $part,
            'id' => $this->account->get('youtube_remote_user_id'),
            'maxResults' => $maxResults,
            'key' => $this->key,
        );

        try {
            $command = $this->client->getCommand('ListChannels', $params);
            $channels = $this->client->execute($command);

        } catch (HttpException\ClientErrorResponseException $e) {
            //Might return
            //Client error response
            // [status code] 404
            // [reason phrase] Not Found
            // print_r($e->getMessage());
            $channels['items'] = array();
            $error = true;
        } catch (\Exception $e) {
            // print_r($e->getMessage());
            // print_r($e->getRequest());
            $channels['items'] = array();
            $error = true;
        }

        //If no error occured return results
        if (!empty($channels['items'])) {
            return $channels;
        }

        //For backward compatibility, fallback trying to get channels by username when error occured by id
        $params['forUsername'] = $params['id'];
        unset($params['id']);

        try {
            $command = $this->client->getCommand('ListChannels', $params);
            $channels = $this->client->execute($command);

        } catch (HttpException\ClientErrorResponseException $e) {
            $channels['items'] = array();
            $error = true;
        } catch (\Exception $e) {
            $channels['items'] = array();
            $error = true;
        }

        //If a single channel is found by username get channel ID and update current account setting
        if (!empty($channels['items']) &&
            count($channels['items']) == 1 &&
            !empty($channels['items'][0]) &&
            !empty($channels['items'][0]['id'])
        ) {
            $testChannelID = $channels['items'][0]['id'];

            $params = array(
                'part' => 'id',
                'id' => $channels['items'][0]['id'],
                'maxResults' => 1,
                'key' => $this->key,
            );

            try {
                $command = $this->client->getCommand('ListChannels', $params);
                $testchannels = $this->client->execute($command);
                // print_r($testchannels);
                if (!empty($testchannels['items']) &&
                    count($testchannels['items']) == 1 &&
                    !empty($testchannels['items'][0]) &&
                    !empty($testchannels['items'][0]['id'])
                ) {
                    $this->account->set('youtube_remote_user_id', $params['id']);
                    $this->account->save();
                }

            } catch (\Exception $e) {
                // print_r($e->getMessage());
                // print_r($e->getRequest());
                $channels['items'] = array();
                $error = true;
            }
        }
        return $channels;
    }

    public function getPlaylistItems($playlistId, $page = 1, $maxResults = 40)
    {
        $playlist = array(
            'items' => array()
        );
        $params = array(
            'part' => 'snippet',
            'playlistId' => $playlistId,
            'maxResults' => $maxResults,
            'key' => $this->key,
        );

        $pageTokensTransient = new Transient('rmlYtPlItemsTokens', 15*MINUTE_IN_SECONDS, array($params));
        $pageTokens = $pageTokensTransient->get();

        if ($pageTokens === false) {
            $pageTokens = array();
        }

        if (!empty($pageTokens[$page])) {
            $params['pageToken'] = $pageTokens[$page];
        }
        // var_dump($pageTokens);
        // var_dump($page);
        try {
            $command = $this->client->getCommand('ListPlaylistItems', $params);
            $playlist = $this->client->execute($command);

        } catch (HttpException\ClientErrorResponseException $e) {
            //Might return
            //Client error response
            // [status code] 404
            // [reason phrase] Not Found
            $playlist['items'] = array();
        } catch (\Exception $e) {
            $playlist['items'] = array();
        }

        //Set next page token if available
        if (isset($playlist['nextPageToken'])) {
            $pageTokens[$page+1] = $playlist['nextPageToken'];
            $pageTokensTransient->set($pageTokens);
        }

        return $playlist;
    }

    public function getUserMedias($page = 1, $perpage = 40)
    {
        $medias = array();
        $sourceType = $this->account->get('youtube_yt_source_type', 'ytchannel');

        $playlistId = $this->account->get('youtube_playlist_id', '');

         switch ($sourceType) {
            
            case 'ytchannel':
            default:
                $channels = $this->getUserChannels();

                foreach ($channels['items'] as $channel) {
                    //If upload playlist not available return nothing
                    if (!isset($channel['contentDetails']['relatedPlaylists']['uploads'])) {
                        return array();
                    }
                    $playlistId = $channel['contentDetails']['relatedPlaylists']['uploads'];
                }
            case 'ytplaylist':
                $playlist = $this->getPlaylistItems($playlistId, $page, $perpage);
        }

        foreach ($playlist['items'] as $item) {
            $medias[] = $item;
        }

        return $medias;
    }

    public function getUserAttachments()
    {
        $page = 1;
        $perpage = 40;
        $searchTerm = '';
        
        if (isset($_POST['query']['paged'])) {
            $page = absint($_POST['query']['paged']);
        }

        if (isset($_POST['query']['posts_per_page'])) {
            $perpage = absint($_POST['query']['posts_per_page']);
        }

        if (isset($_POST['query']['s'])) {
            $searchTerm = sanitize_text_field($_POST['query']['s']);
        }

        $medias = $this->getUserMedias($page, $perpage);
        // $medias = $response->getAll();

        $mediaMinOffset = ($page - 1) * $perpage;
        $attachments = array();

        foreach ($medias as $i => $media) {
            $remoteMedia = new RemoteMedia($media);
            $remoteMedia->setAccount($this->getAccount());
            $attachments[$i] = $remoteMedia->toMediaManagerAttachment();
            //Make sure order stay the same has received
            $attachments[$i]['menuOrder'] = intval($mediaMinOffset + $i);

            //Sometimes youtube returns the same video twice in a page
            //Since the media library JS merge by id, this cause the page to be less then full count
            //preventing page index to increment therefore always requesting the same page endlessy
            //Make sure id are different even if video to prevent this behavior
            $attachments[$i]['id'] = $attachments[$i]['id'].'_'.$attachments[$i]['menuOrder'];
        }

        return $attachments;
    }
}
