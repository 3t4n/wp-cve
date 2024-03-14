<?php
namespace WPRemoteMediaExt\RemoteMediaExt\Accounts\Instagram;

use WPRemoteMediaExt\RemoteMediaExt\Accounts\AbstractRemoteAccount;
use WPRemoteMediaExt\RemoteMediaExt\Accounts\AbstractRemoteService;
use WPRemoteMediaExt\RemoteMediaExt\Accounts\SessionQuery;
use WPRemoteMediaExt\RemoteMediaExt\Library\MediaTemplate;
use WPRemoteMediaExt\WPCore\Cache\Transient;
use WPRemoteMediaExt\WPCore\View;
use WPRemoteMediaExt\WPForms\FieldSet;

class Service extends AbstractRemoteService
{
    protected $useragent = '';

    public function __construct()
    {
        parent::__construct(__('Instagram', 'remote-medias-lite'), 'instagram');

        $client = Client::factory();
        $this->setClient($client);
    }

    public function init()
    {
        if (is_admin()) {
            $this->mediaSettings = array('uploadTemplate' => 'media-upload-instagram-upgrade');
            $this->hook(new MediaTemplate(new View($this->getViewsPath().'admin/media-upload-instagram.php')));

            //FieldSets need to be initialized early because they hook needed JS and CSS for fields added
            $this->initFieldSet();
        }
    }

    public function initFieldSet()
    {
        $this->fieldSet = new FieldSet();
        $field = array(
            'label' => __("Instagram Username", 'remote-medias-lite'),
            'type' => 'Text',
            'class' => $this->getSlug(),
            'id' => 'remote_user_id',
            'name' => 'account_meta['.$this->getSlug().'][instagram_remote_user_id]',
            'desc' => __("Insert the Instagram user for this library", 'remote-medias-lite'),
        );
        $this->fieldSet->addField($field);
    }

    public function validate()
    {
        $this->useragent = $_SERVER['HTTP_USER_AGENT'];

         $data = array();

        $params = array(
            'username' => $this->account->get('instagram_remote_user_id'),
            'command.headers' => array(
                'user-agent' => $this->useragent,
            )
        );
        
        try {
            $command = $this->client->getCommand('MainPage', $params);
            $response = $this->client->execute($command);

            if (empty($response) ||
                empty($response['profilePageContainerUri']) ||
                empty($response['channelId']) ||
                empty($response['csrf_token']) ||
                empty($response['rhx_gis'])
            ) {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function getUserInfo()
    {
        return false;
    }

    //As of April 2018 Query hash, rhx_gis, crsf_token are needed to extract paging data
    public function getQueryData()
    {
        $this->useragent = $_SERVER['HTTP_USER_AGENT'];

        $data = array();

        $params = array(
            'username' => $this->account->get('instagram_remote_user_id'),
            'command.headers' => array(
                'user-agent' => $this->useragent,
            )
        );

        $command = $this->client->getCommand('MainPage', $params);
        $response = $this->client->execute($command);
        
        if (empty($response) || empty($response['profilePageContainerUri'])) {
            return $data;
        }

        $data = $response;

        $params = array(
            'uri' => $data['profilePageContainerUri'],
            'command.headers' => array(
                'user-agent' => $this->useragent,
            )
        );

        $command = $this->client->getCommand('ProfilePageContainer', $params);
        $response = $this->client->execute($command);

        if (empty($response) || empty($response['queryId'])) {
            return $data;
        }

        $data['queryId'] = $response['queryId'];

        return $data;
    }

    public function getUserMedias($page = 1, $perpage = 40)
    {
        $medias = array();
        $response = null;

        $username = $this->account->get('instagram_remote_user_id');

        $params = array(
            'username' => $username,
        );
        
        $pageDataTransient = new Transient('rmlInstagramTokens', 2*MINUTE_IN_SECONDS, array($params));
        $pageData = $pageDataTransient->get();

        if ($pageData === false) {
            $pageData = array();
        }

        //If cached already
        if (!empty($pageData[$page]) && !empty($pageData[$page]['data'])) {
            return $pageData[$page]['data'];
        }

        $after = '';
        if (!empty($pageData[$page]) && !empty($pageData[$page]['end_cursor'])) {
            $after = $pageData[$page]['end_cursor'];
        }

        //Get latest query data needed to perform graphql query
        $queryData = $this->getQueryData();

        $params = array(
            'query_hash' => $queryData['queryId'],
            'variables' => json_encode(array(
                'id' => $queryData['channelId'],
                "first" => $perpage,
                "after" => $after
            )),
            'command.headers' => array(
                'user-agent' => $this->useragent,
                'referer' => 'https://www.instagram.com/'.$username.'/',
                'cache-control' => 'no-cache',
            )
        );

        //As per April 2018
        //https://github.com/postaddictme/instagram-php-scraper/issues/325
        //User agent MUST BE same across all requests
        $params['command.headers']['x-instagram-gis'] = md5(
            $queryData['rhx_gis'].':'.
            // $queryData['csrf_token'].':'.
            //$params['command.headers']['user-agent'].':'.
            $params['variables']
        );

        $command = $this->client->getCommand('GraphQL', $params);
        $command->prepare();
        $command->getRequest()->addCookie('csrftoken', $queryData['csrf_token']);

        $response = $this->client->execute($command);

        if (empty($response) ||
            empty($response->data) ||
            empty($response->data->user) ||
            empty($response->data->user->edge_owner_to_timeline_media) ||
            empty($response->data->user->edge_owner_to_timeline_media->edges)
        ) {
            return $medias;
        }

        //Set medias data
        $medias   = $response->data->user->edge_owner_to_timeline_media->edges;

        //No need to cache data as it is better cached in getUserAttachments
        // $pageData[$page]['data'] = $medias;
        // $pageDataTransient->set($pageData);

        if (!empty($response->data->user->edge_owner_to_timeline_media->page_info)) {
            $pageInfo = $response->data->user->edge_owner_to_timeline_media->page_info;

            //Set next page token if available
            if (isset($pageInfo) &&
                isset($pageInfo->has_next_page) &&
                $pageInfo->has_next_page &&
                !empty($pageInfo->end_cursor)
            ) {
                $pageData[$page+1]['end_cursor'] = $pageInfo->end_cursor;
                $pageDataTransient->set($pageData);
            }
        }
        
        return $medias;
    }

    //Thanks to https://github.com/postaddictme/instagram-php-scraper
    public function getUserAttachments()
    {
        $page = 1;
        $perpage = 40;
        $searchTerm = '';
        $medias = array();
        $cacheEnable = true;

        if (isset($_POST['query']['paged'])) {
            $page = absint($_POST['query']['paged']);
        }

        if (isset($_POST['query']['posts_per_page'])) {
            $perpage = absint($_POST['query']['posts_per_page']);
        }

        if (isset($_POST['query']['s'])) {
            $searchTerm = sanitize_text_field($_POST['query']['s']);
        }

        $mediaMinOffset = ($page - 1) * $perpage;

        $cacheKey = md5('ocsrmlig'.$this->account->get('instagram_remote_user_id'));
        
        $cache = new SessionQuery($cacheKey, 'ig');
        $cache->setMaxPageDataCount(240);

        $medias = $cache->get($page, $perpage);

        //If null then data need to be feth to session cache
        //Or if cache did not return a full page and cache is not full
        if (is_null($medias) ||
            (count($medias) < $perpage && !$cache->isFull())
        ) {
            //Load 240 medias at a time in cache to limit queries to instagram
            $remotepagecount = 240;
            $pageToLoad = $cache->getLastPage() + 1;

            $newmedias = $this->getUserMedias($pageToLoad, $remotepagecount);

            $cache->load($newmedias, $remotepagecount);

            $medias = $cache->get($page, $perpage);
        }

        $attachments = array();

        foreach ($medias as $i => $media) {
            $remoteMedia = new RemoteMedia($media->node);
            $remoteMedia->setAccount($this->getAccount());
            $attachments[$i] = $remoteMedia->toMediaManagerAttachment();

            //Make sure order stay the same has received
            $attachments[$i]['menuOrder'] = intval($mediaMinOffset + $i);
        }

        return $attachments;
    }
}
