<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_CommentEvent extends WADA_Layout_EventDetailsBase
{
    const LAYOUT_IDENTIFIER = 'wada-layout-comment-event';

    public function getEventTitleAndSubtitle(){ // overwrites parent
        if($this->event->object_id > 0) {
            $title = sprintf(__('%s ID %d', 'wp-admin-audit'), __('Comment', 'wp-admin-audit'), $this->event->object_id);
        }else{
            $title = sprintf(__('%s event', 'wp-admin-audit'),  __('Comment', 'wp-admin-audit'));
        }
        $subtitle = '';

        switch($this->event->sensor_id){
            case WADA_Sensor_Base::EVT_COMMENT_CREATE:
                $title = sprintf(__('%s ID %d was created', 'wp-admin-audit'), __('Comment', 'wp-admin-audit'), $this->event->object_id);
                break;
            case WADA_Sensor_Base::EVT_COMMENT_UPDATE:
                $title = sprintf(__('%s ID %d was updated', 'wp-admin-audit'), __('Comment', 'wp-admin-audit'), $this->event->object_id);
                break;
            case WADA_Sensor_Base::EVT_COMMENT_DELETE:
                $title = sprintf(__('%s ID %d was deleted', 'wp-admin-audit'), __('Comment', 'wp-admin-audit'), $this->event->object_id);
                break;
            case WADA_Sensor_Base::EVT_COMMENT_TRASHED:
                $title = sprintf(__('%s ID %d was trashed', 'wp-admin-audit'), __('Comment', 'wp-admin-audit'), $this->event->object_id);
                break;
            case WADA_Sensor_Base::EVT_COMMENT_UNTRASHED:
                $title = sprintf(__('%s ID %d was restored', 'wp-admin-audit'), __('Comment', 'wp-admin-audit'), $this->event->object_id);
                break;
            case WADA_Sensor_Base::EVT_COMMENT_APPROVED:
                $title = sprintf(__('%s ID %d was approved', 'wp-admin-audit'), __('Comment', 'wp-admin-audit'), $this->event->object_id);
                break;
            case WADA_Sensor_Base::EVT_COMMENT_UNAPPROVED:
                $title = sprintf(__('%s ID %d was unapproved', 'wp-admin-audit'), __('Comment', 'wp-admin-audit'), $this->event->object_id);
                break;
            case WADA_Sensor_Base::EVT_COMMENT_SPAMMED:
                $title = sprintf(__('%s ID %d was marked as spam', 'wp-admin-audit'), __('Comment', 'wp-admin-audit'), $this->event->object_id);
                break;
        }
        return array($title, $subtitle);
    }

    public function display($returnAsString = false){
        if($returnAsString){
            ob_start();
        }
    ?>
        <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
            <?php
            list($title, $subtitle) = $this->getEventTitleAndSubtitle();
            $specialInfoKeys = $this->getSpecialInfoKeys();

            $additionalParams = array();
            switch($this->event->sensor_id){
                case WADA_Sensor_Base::EVT_COMMENT_CREATE:
                case WADA_Sensor_Base::EVT_COMMENT_DELETE:
                    $additionalParams[] = 'ONLY_SINGLE_VALUE';
            }

            $this->renderTitleAndDefaultEventInfos($title, $subtitle, $specialInfoKeys, $additionalParams);
            ?>
        </div>
    <?php
        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }
}