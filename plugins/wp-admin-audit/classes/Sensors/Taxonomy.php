<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Sensor_Taxonomy extends WADA_Sensor_Base
{
    protected $priorTerms = null;
    protected $relevantTaxonomies = null;

    public function __construct($sensorGroup = WADA_Sensor_Base::GRP_TAXONOMY){
        parent::__construct($sensorGroup);
        $this->priorTerms = array();
        $this->relevantTaxonomies = array('category', 'post_tag');
    }

    public function registerSensor(){
        add_action('created_category', array($this, 'onCreatedCategory'), 10, 2);
        add_action('created_post_tag', array($this, 'onCreatedPostTag'), 10, 2);
        add_action('edit_terms', array($this, 'onTermAboutToUpdate'), 10, 2);
        add_action('edited_category', array($this, 'onEditedCategory'), 10, 2);
        add_action('edited_post_tag', array($this, 'onEditedPostTag'), 10, 2);
        add_action('delete_category', array($this, 'onDeleteCategory'), 10, 4);
        add_action('delete_post_tag', array($this, 'onDeletePostTag'), 10, 4);
        //WADA_Log::debug('Active sensors for group '.$this->sensorGroup.': '.print_r($this->activeSensors, true));
    }

    /**
     * @param int $termId
     * @param int $tt_id
     * @return bool|int
     */
    public function onCreatedCategory($termId, $tt_id){
        if(!$this->isActiveSensor(self::EVT_CATEGORY_CREATE)) return $this->skipEvent(self::EVT_CATEGORY_CREATE);
        $newCategory = get_term($termId, 'category');
        WADA_Log::debug('onCreatedCategory termId: '.$termId.', tt_id: '.$tt_id.', newCategory: '.print_r($newCategory, true));
        return $this->prepareTermChangesAndStoreEvent(self::EVT_CATEGORY_CREATE, $termId, $newCategory);
    }

    /**
     * @param int $termId
     * @param int $tt_id
     * @return bool|int
     */
    public function onCreatedPostTag($termId, $tt_id){
        if(!$this->isActiveSensor(self::EVT_POST_TAG_CREATE)) return $this->skipEvent(self::EVT_POST_TAG_CREATE);
        $newPostTag = get_term($termId, 'post_tag');
        WADA_Log::debug('onCreatedPostTag termId: '.$termId.', tt_id: '.$tt_id.', newPostTag: '.print_r($newPostTag, true));
        return $this->prepareTermChangesAndStoreEvent(self::EVT_POST_TAG_CREATE, $termId, $newPostTag);
    }

    /**
     * @param int $termId
     * @param string $taxonomy
     */
    public function onTermAboutToUpdate($termId, $taxonomy){
        if(in_array($taxonomy, $this->relevantTaxonomies)){
            WADA_Log::debug('onTermAboutToUpdate termId: '.$termId.', taxonomy: '.$taxonomy);
            $this->priorTerms[$termId] = get_term($termId, $taxonomy);
            WADA_Log::debug('priorTerms: '.print_r($this->priorTerms, true));
        }
    }

    /**
     * @param int $termId
     * @param int $tt_id
     * @return bool|int
     */
    public function onEditedCategory($termId, $tt_id){
        if(!$this->isActiveSensor(self::EVT_CATEGORY_UPDATE)) return $this->skipEvent(self::EVT_CATEGORY_UPDATE);
        $categoryAfterEdit = get_term($termId, 'category');
        $categoryBeforeEdit = array_key_exists($termId, $this->priorTerms) ? $this->priorTerms[$termId] : null;
        WADA_Log::debug('onEditedCategory termId: '.$termId.', tt_id: '.$tt_id.', categoryAfterEdit: '.print_r($categoryAfterEdit, true));
        return $this->prepareTermChangesAndStoreEvent(self::EVT_CATEGORY_UPDATE, $termId, $categoryAfterEdit, $categoryBeforeEdit);
    }

    /**
     * @param int $termId
     * @param int $tt_id
     * @return bool|int
     */
    public function onEditedPostTag($termId, $tt_id){
        if(!$this->isActiveSensor(self::EVT_POST_TAG_UPDATE)) return $this->skipEvent(self::EVT_POST_TAG_UPDATE);
        $postTagAfterEdit = get_term($termId, 'post_tag');
        $postTagBeforeEdit = array_key_exists($termId, $this->priorTerms) ? $this->priorTerms[$termId] : null;
        WADA_Log::debug('onEditedPostTag termId: '.$termId.', tt_id: '.$tt_id.', postTagAfterEdit: '.print_r($postTagAfterEdit, true));
        return $this->prepareTermChangesAndStoreEvent(self::EVT_POST_TAG_UPDATE, $termId, $postTagAfterEdit, $postTagBeforeEdit);
    }

    /**
     * @param int $termId
     * @param int $tt_id
     * @param WP_Term $deleted_term
     * @param array $object_ids
     * @return bool|int
     */
    public function onDeleteCategory($termId, $tt_id, $deleted_term, $object_ids){
        if(!$this->isActiveSensor(self::EVT_CATEGORY_DELETE)) return $this->skipEvent(self::EVT_CATEGORY_DELETE);
        WADA_Log::debug('onDeleteCategory termId: '.$termId.', tt_id: '.$tt_id.' deleted_term: '.print_r($deleted_term, true).', object_ids: '.print_r($object_ids, true));
        return $this->prepareTermChangesAndStoreEvent(self::EVT_CATEGORY_DELETE, $termId, $deleted_term);
    }

    /**
     * @param int $termId
     * @param int $tt_id
     * @param WP_Term $deleted_term
     * @param array $object_ids
     * @return bool|int
     */
    public function onDeletePostTag($termId, $tt_id, $deleted_term, $object_ids){
        if(!$this->isActiveSensor(self::EVT_POST_TAG_DELETE)) return $this->skipEvent(self::EVT_POST_TAG_DELETE);
        WADA_Log::debug('onDeletePostTag termId: '.$termId.', tt_id: '.$tt_id.' deleted_term: '.print_r($deleted_term, true).', object_ids: '.print_r($object_ids, true));
        return $this->prepareTermChangesAndStoreEvent(self::EVT_POST_TAG_DELETE, $termId, $deleted_term);
    }

    /**
     * @param int $sensorId
     * @param int $termId
     * @param WP_Term $currentTerm
     * @param WP_Term|null $priorTerm
     * @return bool
     */
    protected function prepareTermChangesAndStoreEvent($sensorId, $termId, $currentTerm, $priorTerm=null){
        if(is_null($priorTerm)){
            $priorTerm = new stdClass();
        }
        $infos = WADA_TermUtils::getTermChanges($currentTerm, $priorTerm);
        $eventData = array('infos' => $infos);
        return $this->storeTermEvent($sensorId, $eventData, $termId);
    }

    /**
     * @param int $userId
     * @param int $targetObjectId
     * @param string|null $targetObjectType
     * @return array
     */
    protected function getEventDefaults($userId = 0, $targetObjectId = 0, $targetObjectType = self::OBJ_TYPE_CORE_TERM){
        // change to parent function is that we default to passing in the object type of WP Term
        return parent::getEventDefaults($userId, $targetObjectId, $targetObjectType);
    }

    /**
     * @param int $sensorId
     * @param array $termData
     * @param int $targetTermId
     * @return bool
     */
    protected function storeTermEvent($sensorId, $termData = array(), $targetTermId = 0){
        $executingUserId = get_current_user_id();
        $event = (object)(array_merge($this->getEventDefaults($executingUserId, $targetTermId), $termData));
        // WADA_Log::debug('storeTermEvent (sensor: '.$sensorId.'), event: '.print_r($event, true));
        $res = $this->storeEvent($sensorId, $event);
        if($res){
            $this->eventTracker[] = array('term_id' => $targetTermId, 'sensor_id' => $sensorId);
        }
        return $res;
    }

}