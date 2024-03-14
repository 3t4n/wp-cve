<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Sensor_Media extends WADA_Sensor_Post
{
    public $firstContext = null;

    public function __construct(){
        parent::__construct(WADA_Sensor_Base::GRP_MEDIA);
    }

    public function registerSensor(){
        add_action('add_attachment', array($this, 'onAddMedia'));
        add_action('attachment_updated', array($this, 'onEditMedia'), 10, 3);
        add_action('delete_attachment', array($this, 'onDeleteMedia'), 10, 2);
        add_filter('wp_handle_upload', array($this, 'recordUploadContext'), 10, 2);
        //WADA_Log::debug('Active sensors for group '.$this->sensorGroup.': '.print_r($this->activeSensors, true));
    }

    /**
     * @param array $upload
     * @param string $context
     */
    public function recordUploadContext($upload, $context){
        $this->firstContext = $context;
        WADA_Log::debug('recordUploadContext context: '.$context.', upload: '.print_r($upload, true));
        return $upload;
    }

    /**
     * @param int $postId
     */
    public function onAddMedia($postId){
        if(!$this->isActiveSensor(self::EVT_MEDIA_CREATE)) return $this->skipEvent(self::EVT_MEDIA_CREATE);
        WADA_Log::debug('onAddMedia postId: '.$postId);
        $post = get_post($postId);
        WADA_Log::debug('onAddMedia post: '.print_r($post, true));
        $infos = $this->getPostEventDetails($post);
        $infos[] = $this->firstContext ? self::getEventInfoElement('context', $this->firstContext) : null;
        $eventData = array('infos' => $infos);
        WADA_Log::debug('onAddMedia eventData: '.print_r($eventData, true));
        return $this->storePostEvent(self::EVT_MEDIA_CREATE, $eventData, $postId);
    }

    /**
     * @param int $postId
     */
    public function onEditMedia($postId, $postAfter, $postBefore){
        if(!$this->isActiveSensor(self::EVT_MEDIA_UPDATE)) return $this->skipEvent(self::EVT_MEDIA_UPDATE);
        WADA_Log::debug('onEditMedia postId: '.$postId.', before: '.print_r($postBefore, true).', after: '.print_r($postAfter, true));
        $infos = $this->getPostEventDetails($postAfter, $postBefore);
        $infos[] = $this->firstContext ? self::getEventInfoElement('context', $this->firstContext) : null;
        $eventData = array('infos' => $infos);
        WADA_Log::debug('onEditMedia eventData: '.print_r($eventData, true));
        return $this->storePostEvent(self::EVT_MEDIA_UPDATE, $eventData, $postId);
    }

    /**
     * @param int $postId
     * @param WP_Post $post
     */
    public function onDeleteMedia($postId, $post){
        if(!$this->isActiveSensor(self::EVT_MEDIA_DELETE)) return $this->skipEvent(self::EVT_MEDIA_DELETE);
        WADA_Log::debug('onDeleteMedia postId: '.$postId.', post: '.print_r($post, true));
        $infos = $this->getPostEventDetails($post);
        $eventData = array('infos' => $infos);
        WADA_Log::debug('onDeleteMedia eventData: '.print_r($eventData, true));
        return $this->storePostEvent(self::EVT_MEDIA_DELETE, $eventData, $postId);
    }

}