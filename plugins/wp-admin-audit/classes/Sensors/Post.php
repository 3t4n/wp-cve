<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Sensor_Post extends WADA_Sensor_Base
{
    protected $priorPost = null;
    protected $priorPostMeta = null;
    protected $priorPermalink = null;
    protected $priorTemplateSlug = null;

    public function __construct($sensorGroup = WADA_Sensor_Base::GRP_POST){
        parent::__construct($sensorGroup);
    }

    public function registerSensor(){
        add_action('pre_post_update', array($this, 'onPostAboutToUpdate'), 10, 2);
        add_action('wp_after_insert_post', array($this, 'onAfterPostInsert'), 10, 4);
        add_action('set_object_terms', array($this, 'onPostTermsUpdate'), 10, 6);
        add_action('transition_post_status', array($this, 'onPostTransitionStatus'), 10, 3);
        add_action('delete_post', array($this, 'onPostDelete'), 10, 2);
        add_action('update_post_meta', array($this, 'onPostMetaAboutToUpdate'), 10, 4);
        //WADA_Log::debug('Active sensors for group '.$this->sensorGroup.': '.print_r($this->activeSensors, true));
    }

    protected function getWP_PostAttributesToRecord(){
        return array(
            'ID', 'post_author', 'post_date', 'post_date_gmt', 'post_content', 'post_title', 'post_excerpt',
            'post_status', 'comment_status', 'ping_status', 'post_password', 'post_name',
            'to_ping', 'pinged',
            'post_modified', 'post_modified_gmt', 'post_content_filtered', 'post_parent', 'guid', 'menu_order',
            'post_type', 'post_mime_type', 'comment_count', 'filter');
    }

    /**
     * @param WP_Post $post
     */
    protected function isPostEventToBeIgnored($post){
        $supportedPostEvents = array('page', 'post'); // not putting attachment here -> considered media event
        if(in_array($post->post_type, $supportedPostEvents)){
            return false;
        }
        WADA_Log::debug('Post / Ignore post event of type '.$post->post_type.' (ID '.$post->ID.')');
        return true;
    }

    /**
     * @param int $postId
     * @param array $data
     */
    public function onPostAboutToUpdate($postId, $data){
        $this->priorPost = get_post(intval($postId));
        $this->priorPostMeta = get_post_meta($postId);
        $this->priorPermalink = get_permalink($postId);
        $this->priorTemplateSlug = get_page_template_slug($postId);
        WADA_Log::debug('onPostAboutToUpdate postId '.$postId
            .', data: '.print_r($data, true)
            .', priorPost: '.print_r($this->priorPost, true)
            .', priorPostMeta: '.print_r($this->priorPostMeta, true)
            .', priorPermalink: '.print_r($this->priorPermalink, true)
            .', priorTemplateSlug: '.print_r($this->priorTemplateSlug, true)
        );
    }

    /**
     * @param int $metaId ID of updated metadata entry
     * @param int $postId ID of the object metadata is for
     * @param string $metaKey Metadata key
     * @param mixed $metaValue Metadata value
     */
    public function onPostMetaAboutToUpdate($metaId, $postId, $metaKey, $metaValue){
        $priorMetaValue = get_post_meta($postId, $metaKey);
        $this->priorPostMeta[$metaKey] = $priorMetaValue;
        WADA_Log::debug('onPostMetaAboutToUpdate metaId: '.$metaId
            .', postId: '.$postId
            .', metaKey: '.$metaKey
            .', metaValue: '.print_r($metaValue, true)
            .', priorValue: '.print_r($priorMetaValue, true)
        );
    }

    /**
     * @param string $newStatus
     * @param string $oldStatus
     * @param WP_Post $post
     * @return bool|int
     */
    public function onPostTransitionStatus($newStatus, $oldStatus, $post){
        if($this->isPostEventToBeIgnored($post)) return $this->skipEvent('[POST TRANSITION PSEUDO]', false);
        if($newStatus === $oldStatus) return $this->skipEvent('[POST TRANSITION PSEUDO]', false, 'No status change (both '.$newStatus.')');
        WADA_Log::debug('onPostTransitionStatus status (old->new): '.$oldStatus.' -> '.$newStatus. ' for post ID '.$post->ID.', post: '.print_r($post, true));

        $sensorId = 0;
        if(($newStatus !== 'auto-draft' && $newStatus !== 'trash') && ($oldStatus === 'new' || $oldStatus === 'auto-draft')){
            $sensorId = WADA_Sensor_Base::EVT_POST_CREATE;
        }else if(($newStatus === 'publish') && ($oldStatus === 'pending' || $oldStatus === 'draft' || $oldStatus === 'future' || $oldStatus === 'trash')){
            $sensorId = WADA_Sensor_Base::EVT_POST_PUBLISHED;
        }else if($oldStatus === 'publish'){
            $sensorId = WADA_Sensor_Base::EVT_POST_UNPUBLISHED;
        }else if($newStatus === 'trash'){
            $sensorId = WADA_Sensor_Base::EVT_POST_TRASHED;
        }

        if(!$sensorId) return $this->skipEvent('[POST TRANSITION PSEUDO]', false, 'Seems we are not interested in (old->new): '.$oldStatus.' -> '.$newStatus.' for post ID '.$post->ID);
        if(!$this->isActiveSensor($sensorId)) return $this->skipEvent($sensorId);

        WADA_Log::debug('onPostTransitionStatus Looking back: '.print_r($this->eventTracker, true));
        $postBeforeStatusChange = get_post($post->ID);
        // $infos = WADA_CompUtils::getChangedAttributes($postBeforeStatusChange, $post, self::getWP_PostAttributesToRecord());
        $infos = array_merge(array(self::getEventInfoElement('POST_STATUS_TRANSITION', $newStatus, $oldStatus)), $this->getPostEventDetails($post, $this->priorPost)); // record the full thing

        $eventData = array('infos' => $infos);
        return $this->storePostEvent($sensorId, $eventData, $post->ID);
    }

    /**
     * @param int $postId
     * @param array $terms 	An array of object term IDs or slugs
     * @param array $tt_ids An array of term taxonomy IDs
     * @param string $taxonomy Taxonomy slug
     * @param bool $append Whether to append new terms to the old terms
     * @param array $old_tt_ids Old array of term taxonomy IDs
     */
    public function onPostTermsUpdate($postId, $terms, $tt_ids, $taxonomy, $append, $old_tt_ids){
        $post = get_post($postId);
        if(!$post && $this->isPostEventToBeIgnored($post)){
            return 0;
        }

        $sensorId = null;
        switch($taxonomy){
            case 'category':
                $sensorId = WADA_Sensor_Base::EVT_POST_CATEGORY_ASSIGN_UPDATE;
                break;
            case 'post_tag':
                $sensorId = WADA_Sensor_Base::EVT_POST_TAG_ASSIGN_UPDATE;
                break;
            default:
                WADA_Log::info('onPostTermsUpdate skipping/ignoring taxonomy '.$taxonomy);
        }
        if($sensorId){
            $infos = WADA_PostUtils::getChangedTerms($taxonomy, $tt_ids, $old_tt_ids, $append);
            if($infos && count($infos)) {
                WADA_Log::debug('Post/onPostTermsUpdate id: '.$postId
                    .', terms: '.print_r($terms, true)
                    .', tt_ids: '.print_r($tt_ids, true).', taxonomy: '.$taxonomy
                    .', append: '.($append ? 'yes':'no')
                    .', old_tt_ids: '.print_r($old_tt_ids, true));
                $eventData = array('infos' => $infos);
                return $this->storePostEvent($sensorId, $eventData, $postId);
            }else{
                WADA_Log::debug('onPostTermsUpdate skip because no changed to '.$taxonomy);
            }
        }
        return 0;
    }

    /**
     * @param int $postId Post ID
     * @param WP_Post $post Post object
     * @param bool $update Whether this is an existing post being updated
     * @param null|WP_Post $postBefore Null for new posts, the WP_Post object prior to the update for updated posts
     */
    public function onAfterPostInsert($postId, $post, $update, $postBefore=null){
        WADA_Log::debug('onAfterPostInsert');
        if(!$this->isActiveSensor(self::EVT_POST_CREATE)
            && !$this->isActiveSensor(self::EVT_POST_UPDATE)) return $this->skipEvent(self::EVT_POST_UPDATE);

        if($this->isPostEventToBeIgnored($post)) return $this->skipEvent(self::EVT_POST_UPDATE, false);

        WADA_Log::debug('onAfterPostInsert Looking back: '.print_r($this->eventTracker, true));
        WADA_Log::debug('onAfterPostInsert for postId '.$postId.' (update: '.($update?'yes':'no').'), post: '.print_r($post, true).', postBefore: '.print_r($postBefore, true));
        if($post->post_status === 'auto-draft'){
            return $this->skipEvent(self::EVT_POST_UPDATE, false, 'Skip recording auto-draft as update for post ID '.$post->ID);
        }

        $pastPostEvent = array_search($post->ID, array_column($this->eventTracker, 'post_id'));
        if($pastPostEvent !== false){
            $pastPostEventSensorId = $this->eventTracker[$pastPostEvent]['sensor_id'];
            WADA_Log::debug('onAfterPostInsert Found past event in tracker, sensor ID: '.$pastPostEventSensorId);
            if($pastPostEventSensorId === WADA_Sensor_Base::EVT_POST_CREATE
                || $pastPostEventSensorId === WADA_Sensor_Base::EVT_POST_PUBLISHED
                || $pastPostEventSensorId === WADA_Sensor_Base::EVT_POST_UNPUBLISHED
                || $pastPostEventSensorId === WADA_Sensor_Base::EVT_POST_TRASHED){
                return $this->skipEvent(self::EVT_POST_UPDATE, false, 'Skip recording update since we recorded status transition for post ID '.$post->ID);
            }
        }

        $infos = $this->getPostEventDetails($post, $this->priorPost);
        $infos = array_merge($infos, $this->getPostMetaEventDetails($post->ID));
        $eventData = array('infos' => $infos);
        return $this->storePostEvent(self::EVT_POST_UPDATE, $eventData, $postId);
    }

    /**
     * @param int $postId
     * @param WP_Post $post
     */
    public function onPostDelete($postId, $post){
        WADA_Log::debug('onPostDelete');
        if(!$this->isActiveSensor(self::EVT_POST_DELETE)) return $this->skipEvent(self::EVT_POST_DELETE);

        if($this->isPostEventToBeIgnored($post)) return $this->skipEvent(self::EVT_POST_DELETE, false);

        WADA_Log::debug('onPostDelete Looking back: '.print_r($this->eventTracker, true));
        WADA_Log::debug('onPostDelete for postId '.$postId.', post: '.print_r($post, true));

        $infos = $this->getPostEventDetails($post, $this->priorPost);
        $eventData = array('infos' => $infos);
        return $this->storePostEvent(self::EVT_POST_DELETE, $eventData, $postId);
    }

    /**
     * @param WP_Post $currentPost
     * @param WP_Post|null $priorPost
     * @return array
     */
    protected function getPostEventDetails($currentPost, $priorPost=null){
        $infos = array();
        $attributesToRecord = $this->getWP_PostAttributesToRecord();
        if($currentPost){
            foreach($attributesToRecord as $attribute){
                if(isset($currentPost->$attribute) && $priorPost && isset($priorPost->$attribute)) {
                    $infos[] = self::getEventInfoElement($attribute, $currentPost->$attribute, $priorPost->$attribute);
                }else if(isset($currentPost->$attribute)) {
                    $infos[] = self::getEventInfoElement($attribute, $currentPost->$attribute);
                }
            }
        }
        return $infos;
    }

    protected function getPostMetaEventDetails($postId){
        $infos = array();
        $currPostMeta = get_post_meta($postId);
        $currPermalink = get_permalink($postId);
        $currTemplateSlug = get_page_template_slug($postId);

        WADA_Log::debug('getPostMetaEventDetails curr meta: '.print_r($currPostMeta, true));
        WADA_Log::debug('getPostMetaEventDetails prior meta: '.print_r($this->priorPostMeta, true));
        WADA_Log::debug('getPostMetaEventDetails curr permalink: '.$currPermalink.', prior: '.$this->priorPermalink);
        WADA_Log::debug('getPostMetaEventDetails curr template slug: '.$currTemplateSlug.', prior: '.$this->priorTemplateSlug);

        $ignoreMetaKeys = array('_edit_lock', '_wp_page_template'); // irrelevant or tracked separately
        $infos = array_merge($infos, WADA_PostUtils::getChangedMeta($currPostMeta, $this->priorPostMeta, $ignoreMetaKeys));
        $infos[] = WADA_PostUtils::getChangedPermalink($currPermalink, $this->priorPermalink); // works tracking the change
        $infos[] = WADA_PostUtils::getChangedTemplateSlug($currTemplateSlug, $this->priorTemplateSlug); // not working: this seems not altered at the time of post being saved, so here not recording the change
        WADA_Log::debug('getPostMetaEventDetails: '.print_r($infos, true));

        return $infos;
    }

    /**
     * @param int $userId
     * @param int $targetObjectId
     * @param string|null $targetObjectType
     * @return array
     */
    protected function getEventDefaults($userId = 0, $targetObjectId = 0, $targetObjectType = self::OBJ_TYPE_CORE_POST){
        // change to parent function is that we default to passing in the object type of WP Post
        return parent::getEventDefaults($userId, $targetObjectId, $targetObjectType);
    }

    /**
     * @param int $sensorId
     * @param array $eventData
     * @param int $targetPostId
     * @return bool
     */
    protected function storePostEvent($sensorId, $eventData = array(), $targetPostId = 0){
        $executingUserId = get_current_user_id();
        $event = (object)(array_merge($this->getEventDefaults($executingUserId, $targetPostId), $eventData));
        // WADA_Log::debug('storePostEvent (sensor: '.$sensorId.'), event: '.print_r($event, true));
        $res = $this->storeEvent($sensorId, $event);
        if($res){
            $this->eventTracker[] = array('post_id' => $targetPostId, 'sensor_id' => $sensorId);
        }
        return $res;
    }

}