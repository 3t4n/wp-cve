<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_PostEvent extends WADA_Layout_EventDetailsBase
{
    const LAYOUT_IDENTIFIER = 'wada-layout-post-event';

    public function getEventTitleAndSubtitle(){ // overwrites parent
        if($this->event->object_id > 0) {
            $title = sprintf(__('Post ID %d', 'wp-admin-audit'), $this->event->object_id);
        }else{
            $title = __('Post event', 'wp-admin-audit');
        }
        $subtitle = '';
        $transitionInfo = $this->extractEventInfoFromArray($this->event->infos, 'POST_STATUS_TRANSITION');
        if($transitionInfo){
            $subtitle = sprintf(__('Status changed from <em>%s</em> to <em>%s</em>', 'wp-admin-audit'), $transitionInfo->prior_value, $transitionInfo->info_value);
        }

        $postType = $this->extractValueFromInfoArray($this->event->infos, 'post_type');
        switch($postType){
            case 'post':
                $postTypeName = __('Post', 'wp-admin-audit');
                break;
            case 'page':
                $postTypeName = __('Page', 'wp-admin-audit');
                break;
            case 'attachment':
                $postTypeName = __('Attachment', 'wp-admin-audit');
                break;
            default:
                $postTypeName = __('Post', 'wp-admin-audit');
        }

        $postTitle = $this->extractValueFromInfoArray($this->event->infos, 'post_title');

        if($postTitle){
            switch($this->event->sensor_id){
                case WADA_Sensor_Base::EVT_POST_CREATE:
                    $title = sprintf(__('%s "%s" (ID %d) was created', 'wp-admin-audit'), $postTypeName, $postTitle, $this->event->object_id);
                    break;
                case WADA_Sensor_Base::EVT_POST_UPDATE:
                    $title = sprintf(__('%s "%s" (ID %d) was updated', 'wp-admin-audit'), $postTypeName, $postTitle, $this->event->object_id);
                    break;
                case WADA_Sensor_Base::EVT_POST_DELETE:
                    $title = sprintf(__('%s "%s" (ID %d) was deleted', 'wp-admin-audit'), $postTypeName, $postTitle, $this->event->object_id);
                    break;
                case WADA_Sensor_Base::EVT_POST_TRASHED:
                    $title = sprintf(__('%s "%s" (ID %d) was trashed', 'wp-admin-audit'), $postTypeName, $postTitle, $this->event->object_id);
                    break;
                case WADA_Sensor_Base::EVT_POST_PUBLISHED:
                    $title = sprintf(__('%s "%s" (ID %d) was published', 'wp-admin-audit'), $postTypeName, $postTitle, $this->event->object_id);
                    break;
                case WADA_Sensor_Base::EVT_POST_UNPUBLISHED:
                    $title = sprintf(__('%s "%s" (ID %d) was unpublished', 'wp-admin-audit'), $postTypeName, $postTitle, $this->event->object_id);
                    break;
                case WADA_Sensor_Base::EVT_POST_CATEGORY_ASSIGN_UPDATE:
                    $title = sprintf(__('%s "%s" (ID %d) category assignments updated', 'wp-admin-audit'), $postTypeName, $postTitle, $this->event->object_id);
                    break;
                case WADA_Sensor_Base::EVT_POST_TAG_ASSIGN_UPDATE:
                    $title = sprintf(__('%s "%s" (ID %d) tag assignments updated', 'wp-admin-audit'), $postTypeName, $postTitle, $this->event->object_id);
                    break;
            }
        }else{
            switch($this->event->sensor_id){
                case WADA_Sensor_Base::EVT_POST_CREATE:
                    $title = sprintf(__('%s ID %d was created', 'wp-admin-audit'), $postTypeName, $this->event->object_id);
                    break;
                case WADA_Sensor_Base::EVT_POST_UPDATE:
                    $title = sprintf(__('%s ID %d was updated', 'wp-admin-audit'), $postTypeName, $this->event->object_id);
                    break;
                case WADA_Sensor_Base::EVT_POST_DELETE:
                    $title = sprintf(__('%s ID %d was deleted', 'wp-admin-audit'), $postTypeName, $this->event->object_id);
                    break;
                case WADA_Sensor_Base::EVT_POST_TRASHED:
                    $title = sprintf(__('%s ID %d was trashed', 'wp-admin-audit'), $postTypeName, $this->event->object_id);
                    break;
                case WADA_Sensor_Base::EVT_POST_PUBLISHED:
                    $title = sprintf(__('%s ID %d was published', 'wp-admin-audit'), $postTypeName, $this->event->object_id);
                    break;
                case WADA_Sensor_Base::EVT_POST_UNPUBLISHED:
                    $title = sprintf(__('%s ID %d was unpublished', 'wp-admin-audit'), $postTypeName, $this->event->object_id);
                    break;
                case WADA_Sensor_Base::EVT_POST_CATEGORY_ASSIGN_UPDATE:
                    $title = sprintf(__('%s ID %d category assignments updated', 'wp-admin-audit'), $postTypeName, $this->event->object_id);
                    break;
                case WADA_Sensor_Base::EVT_POST_TAG_ASSIGN_UPDATE:
                    $title = sprintf(__('%s ID %d tag assignments updated', 'wp-admin-audit'), $postTypeName, $this->event->object_id);
                    break;
            }
        }

        return array($title, $subtitle);
    }

    public function getSpecialInfoKeys(){
        return array(
                array('info_key' => 'POST_STATUS_TRANSITION', 'callback' => array($this, 'skipLine'))
        );
    }

    protected function preparePostTermExplanations(){
        for($i=0; $i<count($this->event->infos); $i++){
            $explanation = '';
            $info = $this->event->infos[$i];

            switch($info->info_key){
                case 'category':
                    if($info->info_value && !$info->prior_value){
                        $explanation = __('Category added', 'wp-admin-audit');
                    }elseif(!$info->info_value && $info->prior_value) {
                        $explanation = __('Category removed', 'wp-admin-audit');
                    }
                    break;
                case 'post_tag':
                    if($info->info_value && !$info->prior_value){
                        $explanation = __('Tag added', 'wp-admin-audit');
                    }elseif(!$info->info_value && $info->prior_value) {
                        $explanation = __('Tag removed', 'wp-admin-audit');
                    }
                    break;
            }
            $this->event->infos[$i]->explanation_value = $explanation;
        }
    }

    public function renderPostTermChangeTable(){
        $innerHtml = '';
        $fieldTitle = __('Field', 'wp-admin-audit');
        switch($this->event->sensor_id){
            case WADA_Sensor_Base::EVT_POST_CATEGORY_ASSIGN_UPDATE:
                $fieldTitle = __('Category', 'wp-admin-audit');
                break;
            case WADA_Sensor_Base::EVT_POST_TAG_ASSIGN_UPDATE:
                $fieldTitle = __('Tag', 'wp-admin-audit');
                break;
        }
        if($this->event && $this->event->infos){
            $innerHtml = '<table class="data wada-detail-table">';
            $innerHtml .= '<tbody>';
            $innerHtml .= '<tr>';
            $innerHtml .= '<th class="label">' . esc_html($fieldTitle) . '</th>';
            $innerHtml .= '<th class="label">' . esc_html(__('Description / Explanation', 'wp-admin-audit')) . '</th>';
            $innerHtml .= '</tr>';
            $this->preparePostTermExplanations();
            foreach ($this->event->infos as $info) {
                $field = $info->info_value ? $info->info_value : $info->prior_value;
                $innerHtml .= $this->renderDefaultEventInfosRowWithSingleValue($field, $info->explanation_value);
            }
            $innerHtml .= '</tbody>';
            $innerHtml .= '</table>';
        }
        return $innerHtml;
    }

    public function getEventInfoTableRenderMethod(){ // overwriting parent method
        switch($this->event->sensor_id){
            case WADA_Sensor_Base::EVT_POST_CATEGORY_ASSIGN_UPDATE:
            case WADA_Sensor_Base::EVT_POST_TAG_ASSIGN_UPDATE:
                $method = 'renderPostTermChangeTable';
                break;
            default:
                $method = 'renderDefaultEventInfosTable';
        }
        return $method;
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
                case WADA_Sensor_Base::EVT_POST_DELETE:
                    $additionalParams[] = 'ONLY_SINGLE_VALUE';
            }

            $this->renderTitleAndDefaultEventInfos($title, $subtitle, $specialInfoKeys, $additionalParams);
            ?>
        </div>
    <?php
        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }
}