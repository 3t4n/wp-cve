<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Sensor_Comment extends WADA_Sensor_Base
{
    protected $priorComments = null;

    public function __construct($sensorGroup = WADA_Sensor_Base::GRP_COMMENT){
        parent::__construct($sensorGroup);
        $this->priorComments = array();
    }

    public function registerSensor(){
        add_filter('wp_update_comment_data', array($this, 'filterBeforeCommentIsUpdated'), 10, 3);
        add_action('edit_comment', array($this, 'onCommentUpdated'), 10, 2);
        add_action('transition_comment_status', array($this, 'onCommentStatusChange'), 10, 3);
        add_action('trashed_comment', array($this, 'onCommentTrashed'), 10, 2);
        add_action('untrashed_comment', array($this, 'onCommentUntrashed'), 10, 2);
        add_action('deleted_comment', array($this, 'onCommentDeleted'), 10, 2);
        add_action('wp_insert_comment', array($this, 'onCommentCreatedV1'), 2, 2);
        add_action('comment_post', array($this, 'onCommentCreatedV2'), 2, 3);
        //WADA_Log::debug('Active sensors for group '.$this->sensorGroup.': '.print_r($this->activeSensors, true));
    }

    /**
     * @param int $commentId
     * @param WP_Comment $comment
     * @return int|bool
     */
    public function onCommentCreatedV1($commentId, $comment){
        if(!$this->isActiveSensor(self::EVT_COMMENT_CREATE)) return $this->skipEvent(self::EVT_COMMENT_CREATE);
        WADA_Log::debug('onCommentCreatedV1');
        WADA_Log::debug('onCommentCreatedV1 commentId: '.$commentId.', comment obj: '.print_r($comment, true));
        return $this->prepareCommentChangesAndStoreEvent(self::EVT_COMMENT_CREATE, $commentId, $comment);
    }

    /**
     * @param int $commentId
     * @param int|string $commentApproved
     * @param array $commentData
     * @return int|bool
     */
    public function onCommentCreatedV2($commentId, $commentApproved, $commentData){
        if(!$this->isActiveSensor(self::EVT_COMMENT_CREATE)) return $this->skipEvent(self::EVT_COMMENT_CREATE);
        WADA_Log::debug('onCommentCreatedV2');
        $newComment = get_comment($commentId);
        WADA_Log::debug('onCommentCreatedV2 commentId: '.$commentId.', commentApproved: '.$commentApproved
            .', commentData: '.print_r($commentData, true)
            .', stored comment obj: '.print_r($newComment, true));
        return $this->prepareCommentChangesAndStoreEvent(self::EVT_COMMENT_CREATE, $commentId, $newComment);
    }

    /**
     * @param array|WP_Error $data  The new, processed comment data, or WP_Error.
     * @param array $comment The old, unslashed comment data.
     * @param array $commentArray The new, raw comment data.
     */
    public function filterBeforeCommentIsUpdated($data, $comment, $commentArray){
        if($data instanceof WP_Error){
            // nothing we can do here
            return $data;
        }
        if(array_key_exists('comment_ID', $data)){
            $commentId = $data['comment_ID'];
        }else{
            $commentId = $comment['comment_ID'];
        }

        $oldComment = get_comment($commentId);
        $this->priorComments[$commentId] = $oldComment;

        return $data; // this is a filter after all
    }

    /**
     * @param int $commentId
     * @param array $commentData
     */
    public function onCommentUpdated($commentId, $commentData){
        if(!$this->isActiveSensor(self::EVT_COMMENT_UPDATE)) return $this->skipEvent(self::EVT_COMMENT_UPDATE);
        $currentComment = get_comment($commentId);
        $priorComment = array_key_exists($commentId, $this->priorComments) ? $this->priorComments[$commentId] : null;
        return $this->prepareCommentChangesAndStoreEvent(self::EVT_COMMENT_UPDATE, $commentId, $currentComment, $priorComment);
    }

    /**
     * @param int|string $newStatus
     * @param int|string $oldStatus
     * @param WP_Comment $comment
     * @return int|bool
     */
    public function onCommentStatusChange($newStatus, $oldStatus, $comment){
        WADA_Log::debug('onCommentStatusChange commentId '.$comment->comment_ID.' status change '.$oldStatus.' -> '.$newStatus.', comment: '.print_r($comment, true));

        $sensorIdOfStatusChange = null;
        $newStatus = strval($newStatus);
        switch($newStatus){
            case 'approve':
            case 'approved':
            case '1':
                $sensorIdOfStatusChange = self::EVT_COMMENT_APPROVED;
                break;
            case 'hold':
            case 'unapprove':
            case 'unapproved':
            case '0':
                $sensorIdOfStatusChange = self::EVT_COMMENT_UNAPPROVED;
                break;
            case 'spam':
            case 'spammed':
                $sensorIdOfStatusChange = self::EVT_COMMENT_SPAMMED;
                break;
            case 'trash':
            case 'trashed':
                $sensorIdOfStatusChange = null; // handled through method onCommentTrashed, nothing to do here
                break;
            case 'untrash':
            case 'untrashed':
                $sensorIdOfStatusChange = null; // handled through method onCommentUntrashed, nothing to do here

        }
        if($sensorIdOfStatusChange){
            if(!$this->isActiveSensor($sensorIdOfStatusChange)) return $this->skipEvent($sensorIdOfStatusChange);
            $commentOld = new WP_Comment($comment);
            $commentOld->comment_approved = $oldStatus;
            $comment->comment_approved = $newStatus;
            return $this->prepareCommentChangesAndStoreEvent($sensorIdOfStatusChange, $comment->comment_ID, $comment, $commentOld);
        }else{
            return 0; // skip because non-applicable
        }
    }

    /**
     * @param int $commentId
     * @param WP_Comment $comment
     */
    public function onCommentTrashed($commentId, $comment){
        if(!$this->isActiveSensor(self::EVT_COMMENT_TRASHED)) return $this->skipEvent(self::EVT_COMMENT_TRASHED);
        WADA_Log::debug('onCommentTrashed commentId '.$commentId.', comment: '.print_r($comment, true));
        return $this->prepareCommentChangesAndStoreEvent(self::EVT_COMMENT_TRASHED, $commentId, $comment);
    }

    /**
     * @param int $commentId
     * @param WP_Comment $comment
     */
    public function onCommentUntrashed($commentId, $comment){
        if(!$this->isActiveSensor(self::EVT_COMMENT_UNTRASHED)) return $this->skipEvent(self::EVT_COMMENT_UNTRASHED);
        WADA_Log::debug('onCommentUntrashed commentId '.$commentId.', comment: '.print_r($comment, true));
        return $this->prepareCommentChangesAndStoreEvent(self::EVT_COMMENT_UNTRASHED, $commentId, $comment);
    }

    /**
     * @param int $commentId
     * @param WP_Comment $comment
     */
    public function onCommentDeleted($commentId, $comment){
        if(!$this->isActiveSensor(self::EVT_COMMENT_DELETE)) return $this->skipEvent(self::EVT_COMMENT_DELETE);
        WADA_Log::debug('onCommentDeleted commentId '.$commentId.', comment: '.print_r($comment, true));
        return $this->prepareCommentChangesAndStoreEvent(self::EVT_COMMENT_DELETE, $commentId, $comment);
    }

    /**
     * @param int $sensorId
     * @param int $commentId
     * @param WP_Comment $currentComment
     * @param object|null $priorComment
     * @return bool
     */
    protected function prepareCommentChangesAndStoreEvent($sensorId, $commentId, $currentComment, $priorComment=null){
        if(is_null($priorComment)){
            $priorComment = new stdClass();
        }
        $infos = WADA_CommentUtils::getCommentChanges($currentComment, $priorComment);
        $eventData = array('infos' => $infos);
        return $this->storeCommentEvent($sensorId, $eventData, $commentId);
    }

    /**
     * @param int $userId
     * @param int $targetObjectId
     * @param string|null $targetObjectType
     * @return array
     */
    protected function getEventDefaults($userId = 0, $targetObjectId = 0, $targetObjectType = self::OBJ_TYPE_CORE_COMMENT){
        // change to parent function is that we default to passing in the object type of WP Comment
        return parent::getEventDefaults($userId, $targetObjectId, $targetObjectType);
    }

    /**
     * @param int $sensorId
     * @param array $commentData
     * @param int $targetCommentId
     * @return bool
     */
    protected function storeCommentEvent($sensorId, $commentData = array(), $targetCommentId = 0){
        $executingUserId = get_current_user_id();
        $event = (object)(array_merge($this->getEventDefaults($executingUserId, $targetCommentId), $commentData));
        $res = $this->storeEvent($sensorId, $event);
        if($res){
            $this->eventTracker[] = array('comment_id' => $targetCommentId, 'sensor_id' => $sensorId);
        }
        return $res;
    }

}