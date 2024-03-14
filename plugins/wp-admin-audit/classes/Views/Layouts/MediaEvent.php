<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_MediaEvent extends WADA_Layout_EventDetailsBase
{
    const LAYOUT_IDENTIFIER = 'wada-layout-media-event';

    public function getEventTitleAndSubtitle(){ // overwrites parent
        if($this->event->object_id > 0) {
            $title = sprintf(__('Media ID %d', 'wp-admin-audit'), $this->event->object_id);
        }else{
            $title = __('Media event', 'wp-admin-audit');
        }
        $subtitle = '';

        $postType = $this->extractValueFromInfoArray($this->event->infos, 'post_type');
        switch($postType){
            case 'attachment':
                $postTypeName = __('Attachment', 'wp-admin-audit');
                break;
            default:
                $postTypeName = __('Post', 'wp-admin-audit');
        }

        switch($this->event->sensor_id){
            case WADA_Sensor_Base::EVT_MEDIA_CREATE:
                $title = sprintf(__('%s ID %d was created', 'wp-admin-audit'), $postTypeName, $this->event->object_id);
                break;
            case WADA_Sensor_Base::EVT_MEDIA_UPDATE:
                $title = sprintf(__('%s ID %d was updated', 'wp-admin-audit'), $postTypeName, $this->event->object_id);
                break;
            case WADA_Sensor_Base::EVT_MEDIA_DELETE:
                $title = sprintf(__('%s ID %d was deleted', 'wp-admin-audit'), $postTypeName, $this->event->object_id);
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
            $this->renderTitleAndDefaultEventInfos($title, $subtitle);
            ?>
        </div>
    <?php
        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }
}