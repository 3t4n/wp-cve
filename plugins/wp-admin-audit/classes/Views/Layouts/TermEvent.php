<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_TermEvent extends WADA_Layout_EventDetailsBase
{
    const LAYOUT_IDENTIFIER = 'wada-layout-term-event';

    public function getEventTitleAndSubtitle(){ // overwrites parent
        $taxonomy = WADA_TermUtils::getTaxonomyNameBySensor($this->event->sensor_id);
        if($this->event->object_id > 0) {
            $title = sprintf(__('%s ID %d', 'wp-admin-audit'), $taxonomy, $this->event->object_id);
        }else{
            $title = sprintf(__('%s event', 'wp-admin-audit'), $taxonomy);
        }
        $subtitle = '';

        switch($this->event->sensor_id){
            case WADA_Sensor_Base::EVT_CATEGORY_CREATE:
            case WADA_Sensor_Base::EVT_POST_TAG_CREATE:
            case WADA_Sensor_Base::EVT_MENU_CREATE:
                $title = sprintf(__('%s ID %d was created', 'wp-admin-audit'), $taxonomy, $this->event->object_id);
                break;
            case WADA_Sensor_Base::EVT_CATEGORY_UPDATE:
            case WADA_Sensor_Base::EVT_POST_TAG_UPDATE:
            case WADA_Sensor_Base::EVT_MENU_UPDATE:
                $title = sprintf(__('%s ID %d was updated', 'wp-admin-audit'), $taxonomy, $this->event->object_id);
                break;
            case WADA_Sensor_Base::EVT_CATEGORY_DELETE:
            case WADA_Sensor_Base::EVT_POST_TAG_DELETE:
            case WADA_Sensor_Base::EVT_MENU_DELETE:
                $title = sprintf(__('%s ID %d was deleted', 'wp-admin-audit'), $taxonomy, $this->event->object_id);
                break;
        }
        return array($title, $subtitle);
    }


    public function getSpecialInfoKeys(){
        return array(
            array('info_key' => 'NAV_ITEM_ADDED', 'info_key_label' => __('Menu item added', 'wp-admin-audit')),
            array('info_key' => 'NAV_ITEM_REMOVED', 'info_key_label' => __('Menu item removed', 'wp-admin-audit')),
            array('info_key' => 'NAV_ITEM_DELETED', 'info_key_label' => __('Menu item deleted', 'wp-admin-audit'))
        );
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
                case WADA_Sensor_Base::EVT_CATEGORY_CREATE:
                case WADA_Sensor_Base::EVT_POST_TAG_CREATE:
                case WADA_Sensor_Base::EVT_MENU_CREATE:
                case WADA_Sensor_Base::EVT_CATEGORY_DELETE:
                case WADA_Sensor_Base::EVT_POST_TAG_DELETE:
                case WADA_Sensor_Base::EVT_MENU_DELETE:
                    $additionalParams[] = 'ONLY_SINGLE_VALUE';
            }

            $this->renderTitleAndDefaultEventInfos($title, $subtitle, $specialInfoKeys, $additionalParams);
            ?>
        </div>
    <?php
        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }
}