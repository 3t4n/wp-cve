<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_EventDetailsBase implements WADA_Layout_LayoutInterface
{
    const LAYOUT_IDENTIFIER = 'wada-layout-event-details';
    public $event;
    
    public function __construct($event = null){
        if($event){
            $this->event = $event;
        }
        add_action('admin_footer', array($this, 'loadJavascriptActions'));
    }

    protected function extractEventInfoFromArray($infos, $key, $default=null){
        $res = array_search($key, array_column($infos, 'info_key'));
        if($res === false){
            return $default;
        }
        return $infos[$res];
    }

    protected function extractValueFromInfoArray($infos, $key, $default=null){
        $infoObj = $this->extractEventInfoFromArray($infos, $key);
        if($infoObj){
            return ((property_exists($infoObj, 'info_value')) ? $infoObj->info_value : $default);
        }
        return $default;
    }

    protected function extractPriorValueFromInfoArray($infos, $key, $default=null){
        $infoObj = $this->extractEventInfoFromArray($infos, $key);
        if($infoObj){
            return ((property_exists($infoObj, 'prior_value')) ? $infoObj->prior_value : $default);
        }
        return $default;
    }

    protected function extractCurrentOrPriorValueFromInfoArray($infos, $key, $default=null){
        $currValue = $this->extractValueFromInfoArray($infos, $key);
        $priorValue = $this->extractPriorValueFromInfoArray($infos, $key);
        return ($currValue ? $currValue : ($priorValue ? $priorValue : $default));
    }

    protected function renderDefaultEventInfosRowCells($field, $infoValue, $priorValue,
                                                       $cssClassCurrent = '', $cssClassPrior = '',
                                                       $titleCurrent = '', $titlePrior = '',
                                                       $styleLabel = '', $styleCurrent = '', $stylePrior = ''){
        $innerHtml = '<td class="label" style="'. esc_attr($styleLabel).'">' . esc_html($field) . '</td>';
        $innerHtml .= '<td class="value curr-value ' . $cssClassCurrent . '" title="'. esc_attr($titleCurrent).'" style="'. esc_attr($styleCurrent).'">' . esc_html($infoValue) . '</td>';
        $innerHtml .= '<td class="value prior-value ' . $cssClassPrior . '" title="'. esc_attr($titlePrior).'" style="'. esc_attr($stylePrior).'">' . esc_html($priorValue) . '</td>';
        return $innerHtml;
    }

    protected function isDifferenceRenderingActive(){
        $uiSettingDiff = get_user_option('wada_ui_event_details_show_diff');
        if(empty($uiSettingDiff)){
            $uiSettingDiff = 'separate-view'; //  default = normal rendering
            update_user_option(get_current_user_id(), 'wada_ui_event_details_show_diff', $uiSettingDiff);
        }
        if($uiSettingDiff === 'diff' || $uiSettingDiff === 'diff-prior'){
            return true; // render differences
        }
        return false;
    }

    protected function isInDifferingRenderingPriorValueNeeded(){
        $uiSettingDiff = get_user_option('wada_ui_event_details_show_diff');
        if($uiSettingDiff === 'diff-prior'){
            return true;
        }
        return false;
    }

    protected function isSkipEventInfoRowIfIdentical(){
        $uiSettingIdentical = get_user_option('wada_ui_event_details_show_identical');
        if(empty($uiSettingIdentical)){
            $uiSettingIdentical = 'show-identical'; //  default = normal rendering
            update_user_option(get_current_user_id(), 'wada_ui_event_details_show_identical', $uiSettingIdentical);
        }
        if($uiSettingIdentical === 'hide-identical'){
            return true; // skip/hide identical values
        }
        return false;
    }

    protected function renderDefaultEventInfosRow($field, $infoValue, $priorValue,
                                                  $cssClassCurrent = '', $cssClassPrior = '',
                                                  $titleCurrent = '', $titlePrior = '',
                                                  $styleLabel = '', $styleCurrent = '', $stylePrior = ''
    ){
        $isIdentical = ((is_null($infoValue) && is_null($priorValue)) || ($infoValue === $priorValue));
        $skipIdentical = $this->isSkipEventInfoRowIfIdentical();
        if($isIdentical && $skipIdentical){
            return '';
        }
        $isDiffRenderingActive = $this->isDifferenceRenderingActive();
        if($isDiffRenderingActive){
            $showPriorValue = $this->isInDifferingRenderingPriorValueNeeded();
            return $this->renderFileContentDiff($field, $infoValue, $priorValue, $showPriorValue);
        }
        $innerHtml = '<tr>';
        $innerHtml .= $this->renderDefaultEventInfosRowCells($field, $infoValue, $priorValue, $cssClassCurrent, $cssClassPrior, $titleCurrent, $titlePrior, $styleLabel, $styleCurrent, $stylePrior);
        $innerHtml .= '</tr>';
        return $innerHtml;
    }

    protected function renderDefaultEventInfosRowWithExplanation($field, $infoValue, $priorValue, $explanationValue,
                                                                 $cssClassCurrent = '', $cssClassPrior = '', $cssClassExplanation = '',
                                                                 $titleCurrent = '', $titlePrior = '', $titleExplanation = '',
                                                                 $styleLabel = '', $styleCurrent = '', $stylePrior = '', $styleExplanation = ''){
        $innerHtml = '<tr>';
        $innerHtml .= $this->renderDefaultEventInfosRowCells($field, $infoValue, $priorValue, $cssClassCurrent, $cssClassPrior, $titleCurrent, $titlePrior, $styleLabel, $styleCurrent, $stylePrior);
        $innerHtml .= '<td class="desc ' . $cssClassExplanation . '" title="'. esc_attr($titleExplanation).'" style="'. esc_attr($styleExplanation).'">' . esc_html($explanationValue) . '</td>';
        $innerHtml .= '</tr>';
        return $innerHtml;
    }

    protected function renderDefaultEventInfosRowWithSingleValue($field, $infoValue,
                                                                 $cssClassCurrent = '',
                                                                 $titleCurrent = '',
                                                                 $styleLabel = '', $styleCurrent = ''){
        $innerHtml = '<tr>';
        $innerHtml .= '<td class="label" style="'. esc_attr($styleLabel).'">' . esc_html($field) . '</td>';
        $innerHtml .= '<td class="value curr-value ' . $cssClassCurrent . '" title="'. esc_attr($titleCurrent).'" style="'. esc_attr($styleCurrent).'">' . esc_html($infoValue) . '</td>';
        $innerHtml .= '</tr>';
        return $innerHtml;
    }


    protected function renderDefaultEventInfosRowWithSingleValueWithExplanation($field, $infoValue, $explanationValue,
                                                                                $cssClassCurrent = '', $cssClassExplanation = '',
                                                                                $titleCurrent = '', $titleExplanation = '',
                                                                                $styleLabel = '', $styleCurrent = '', $styleExplanation = ''
    ){
        $innerHtml = '<tr>';
        $innerHtml .= '<td class="label" style="'. esc_attr($styleLabel).'">' . esc_html($field) . '</td>';
        $innerHtml .= '<td class="value curr-value ' . $cssClassCurrent . '" title="'. esc_attr($titleCurrent).'" style="'. esc_attr($styleCurrent).'">' . esc_html($infoValue) . '</td>';
        $innerHtml .= '<td class="desc ' . $cssClassExplanation . '" title="'. esc_attr($titleExplanation).'" style="'. esc_attr($styleExplanation).'">' . esc_html($explanationValue) . '</td>';
        $innerHtml .= '</tr>';
        return $innerHtml;
    }

    protected function skipLine($field, $infoValue, $priorValue){
        WADA_Log::debug('skipLine!');
        return ''; // told ya!
    }

    protected function renderDeletedLines($field, $infoValue, $priorValue){
        $cssClassCurrent = 'wada-del';
        $field = str_replace('DEL_DATA_', '', $field);
        $priorValue = $infoValue;
        $infoValue = __('(deleted)', 'wp-admin-audit');
        return $this->renderDefaultEventInfosRow($field, $infoValue, $priorValue, $cssClassCurrent);
    }

    protected function renderBooleanValueLine($field, $infoValue, $priorValue){
        $currTitle = $infoValue;
        $priorTitle = $priorValue;
        if($infoValue === '0' || $infoValue === '' || is_null($infoValue)){
            $infoValue = __('No', 'wp-admin-audit');
        }elseif($infoValue === '1'){
            $infoValue = __('Yes', 'wp-admin-audit');
        }
        if($priorValue === '0' || $priorValue === '' || is_null($priorValue)){
            $priorValue = __('No', 'wp-admin-audit');
        }elseif($priorValue === '1'){
            $priorValue = __('Yes', 'wp-admin-audit');
        }
        return $this->renderDefaultEventInfosRow($field, $infoValue, $priorValue, '', '', $currTitle, $priorTitle);
    }

    public function renderFileContentDiff($field, $infoValue, $priorValue, $showPriorValue = false){
        $pluginBaseName = basename(realpath(__DIR__.'/../../../'));
        $pathToFineDiffLib = '/'.$pluginBaseName.'/classes/Lib/PHP-FineDiff-multibyte/GorHill/FineDiff/';
        require_once(WP_PLUGIN_DIR . $pathToFineDiffLib . 'FineDiff.php');
        require_once(WP_PLUGIN_DIR . $pathToFineDiffLib . 'FineDiffOp.php');
        require_once(WP_PLUGIN_DIR . $pathToFineDiffLib . 'FineDiffOps.php');
        require_once(WP_PLUGIN_DIR . $pathToFineDiffLib . 'FineDiffInsertOp.php');
        require_once(WP_PLUGIN_DIR . $pathToFineDiffLib . 'FineDiffCopyOp.php');
        require_once(WP_PLUGIN_DIR . $pathToFineDiffLib . 'FineDiffDeleteOp.php');
        require_once(WP_PLUGIN_DIR . $pathToFineDiffLib . 'FineDiffReplaceOp.php');
        require_once(WP_PLUGIN_DIR . $pathToFineDiffLib . 'FineDiffHTML.php');

        $opcodes = GorHill\FineDiff\FineDiff::getDiffOpcodes($priorValue, $infoValue, GorHill\FineDiff\FineDiff::$sentenceGranularity);
        $renderedDiff = GorHill\FineDiff\FineDiffHTML::renderDiffToHTMLFromOpcodes($priorValue, $opcodes);

        $innerHtml = '<tr class="show-text-diff">';
        $innerHtml .= '<td class="label" style="">' . esc_html($field) . '</td>';
        $innerHtml .= '<td class="value curr-value" title="" style="">' . '<pre>' . $renderedDiff . '</pre>' . '</td>';

        if($showPriorValue){
            $innerHtml .= '<td class="value prior-value" title="" style="">' . '<pre>' . esc_html($priorValue) . '</pre>' . '</td>';
        }
        $innerHtml .= '</tr>';
        return $innerHtml;
    }

    protected function renderTitleAndDefaultEventInfos($title, $subtitle, $specialInfoKeys = array(), $additionalParams = array()){
        $html = '<h3>'.$title.'</h3><h4>'.$subtitle.'</h4>';

        if(!$this->event->infos || count($this->event->infos) == 0){
            $innerHtml = '';
        }else {
            $method = $this->getEventInfoTableRenderMethod();
            if(is_callable(array($this, $method))){
                $innerHtml = call_user_func(array($this, $method), $specialInfoKeys, $additionalParams);
            }else{
                $innerHtml = call_user_func(array($this, 'renderDefaultEventInfosTable'), $specialInfoKeys, $additionalParams);
            }
        }

        add_filter( 'safe_style_css', function( $styles ) {
            $styles[] = 'display'; // allow since we need it for display:none for the icon control bar
            return $styles;
        } );

        echo wp_kses_post($html.$innerHtml);
    }

    /**
     * @param array $specialInfoKeys
     * Example: array(
     * *            array( 'sensor_id' => 12, 'info_key'           => 'POST_STATUS_TRANSITION', 'callback' => ($this, 'skipLine') ),
     *              array( 'sensor_id' => 5,  'info_key_prefix'    => 'DEL_DATA_',              'callback' => ($this, 'renderDeletedLines') )
     * )
     * @param array $additionalParams
     * Example: array(
     *              'ONLY_SINGLE_VALUE'
     * )
     * @return string
     */
    public function renderDefaultEventInfosTable($specialInfoKeys = array(), $additionalParams = array()){
        $innerHtml = '';

        if($this->event && $this->event->infos){
            $showSeparateViewLabel = esc_attr(__('Show current and prior values separately', 'wp-admin-audit'));
            $showDiffViewLabel = esc_attr(__('Highlight the differences', 'wp-admin-audit'));
            $showIdenticalValuesLabel = esc_attr(__('Show identical values', 'wp-admin-audit'));
            $hideIdenticalValuesLabel = esc_attr(__('Hide identical values', 'wp-admin-audit'));

            $innerHtml = '<div class="wada-event-details-control-bar">';
            $innerHtml .= '<ul class="wada-event-details-control-bar-icons" style="display: none;">';

            $innerHtml .=  '<li id="show-separate-view" title="'.$showSeparateViewLabel.'"><span class="dashicons dashicons-leftright"></span></li>';
            $innerHtml .=  '<li id="show-diff-view" title="'.$showDiffViewLabel.'"><span class="dashicons dashicons-slides"></span></li>';

            $innerHtml .=  '<li id="show-identical" title="'.$showIdenticalValuesLabel.'"><span class="dashicons dashicons-visibility"></span></li>';
            $innerHtml .=  '<li id="hide-identical" title="'.$hideIdenticalValuesLabel.'"><span class="dashicons dashicons-hidden"></span></li>';
            $innerHtml .= '</ul>';
            $innerHtml .= '</div>';
            $innerHtml .= $this->renderDefaultEventInfosTableContent($specialInfoKeys, $additionalParams);
        }
        return $innerHtml;
    }

    public function renderDefaultEventInfosTableContent($specialInfoKeys = array(), $additionalParams = array()){
        $innerHtml = '';
        $onlySingleValue = in_array('ONLY_SINGLE_VALUE', $additionalParams);
        $commonCellStyle = array_key_exists('COMMON_CELL_STYLE', $additionalParams) ? $additionalParams['COMMON_CELL_STYLE'] : false;

        $labelStyle = $currStyle = $priorStyle = '';
        if($commonCellStyle){
            $labelStyle = $commonCellStyle;
            $currStyle = $commonCellStyle;
            $priorStyle = $commonCellStyle;
        }

        $innerHtml .= '<div id="wada-detail-table-container">';
        $innerHtml .= '<span class="spinner event-details-table-spinner ajax-progress-spinner" style="display: none;"></span>';
        $innerHtml .= '<table class="data wada-detail-table">';
        $innerHtml .= '<tbody>';
        $innerHtml .= '<tr>';
        $innerHtml .= '<th class="label">' . esc_html(__('Field', 'wp-admin-audit')) . '</th>';
        $isDiffRenderingActive = $this->isDifferenceRenderingActive();
        $showPriorValue = $this->isInDifferingRenderingPriorValueNeeded();
        if($onlySingleValue || ($isDiffRenderingActive && !$showPriorValue)){
            $innerHtml .= '<th class="label">' . esc_html(__('Value', 'wp-admin-audit')) . '</th>';
        }else {
            $innerHtml .= '<th class="label">' . esc_html(__('New value', 'wp-admin-audit')) . '</th>';
            $innerHtml .= '<th class="label">' . esc_html(__('Prior value', 'wp-admin-audit')) . '</th>';
        }
        $innerHtml .= '</tr>';
        foreach ($this->event->infos as $info) {
            $renderedInfo = false;
            foreach($specialInfoKeys AS $specialInfoKey){
                $sensorIdMatch = (!array_key_exists('sensor_id', $specialInfoKey) || ($specialInfoKey['sensor_id'] === $this->event->sensor_id));
                $infoKeyMatch = (array_key_exists('info_key', $specialInfoKey) && $specialInfoKey['info_key'] === $info->info_key);
                $infoKeyPrefixMatch = (array_key_exists('info_key_prefix', $specialInfoKey) && (strpos($info->info_key , $specialInfoKey['info_key_prefix']) === 0));

                if($sensorIdMatch && ($infoKeyMatch || $infoKeyPrefixMatch)){

                    if($infoKeyMatch){
                        $info->info_key = (array_key_exists('info_key_label', $specialInfoKey)) ? $specialInfoKey['info_key_label'] : $info->info_key;
                    }

                    if(array_key_exists('callback', $specialInfoKey) && is_callable($specialInfoKey['callback'])){
                        WADA_Log::debug('going to execute: '.print_r($specialInfoKey['callback'], true));
                        $innerHtml .= call_user_func($specialInfoKey['callback'], $info->info_key, $info->info_value, $info->prior_value);
                        $renderedInfo = true; // make sure we do not execute the default method below
                        break; // no need to search on
                    }
                }
            }
            if(!$renderedInfo) {
                if($onlySingleValue){
                    $innerHtml .= $this->renderDefaultEventInfosRowWithSingleValue($info->info_key, $info->info_value,
                        '', '',
                        $labelStyle, $currStyle);
                }else{
                    $innerHtml .= $this->renderDefaultEventInfosRow($info->info_key, $info->info_value, $info->prior_value,
                        '', '',
                        '', '',
                        $labelStyle, $currStyle, $priorStyle);
                }
            }
        }
        $innerHtml .= '</tbody>';
        $innerHtml .= '</table>';
        $innerHtml .= '</div>';
        return $innerHtml;
    }

    public function display($returnAsString = false){ // overwritten in the subclasses to show a less generic layout
        if($returnAsString){
            ob_start();
        }
        echo wp_kses_post($this->renderDefaultEventInfosTable());
        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }

    public function getEventTitleAndSubtitle(){ // overwritten in the subclasses
        $title = __('Default event title', 'wp-admin-audit');
        $subtitle = '';
        return array($title, $subtitle);
    }

    public function getSpecialInfoKeys(){ // overwritten in the subclasses
        return array();
    }

    public function getEventInfoTableRenderMethod(){ // overwritten in the subclasses
        return 'renderDefaultEventInfosTable';
    }

    /**
     * @param stdClass $event
     * @return string
     */
    public static function getEventObjectDetailsTable($event, $useHtml=true){
        if(!$event->object_type){
            return '';
        }
        switch ($event->object_type) {
            case WADA_Sensor_Base::OBJ_TYPE_CORE_USER:
                $user = get_userdata(absint($event->object_id));
                $innerHtml = (new WADA_Layout_UserOutline($user, $event->object_id))->display(true);
                break;
            case WADA_Sensor_Base::OBJ_TYPE_CORE_POST:
                $post = get_post(absint($event->object_id));
                $innerHtml = (new WADA_Layout_PostOutline($post, $event))->display(true);
                break;
            case WADA_Sensor_Base::OBJ_TYPE_CORE_MENU:
            case WADA_Sensor_Base::OBJ_TYPE_CORE_TERM:
                $term = get_term(absint($event->object_id));
                $innerHtml = (new WADA_Layout_TermOutline($term, $event))->display(true);
                break;
            case WADA_Sensor_Base::OBJ_TYPE_CORE_COMMENT:
                $comment = get_comment(absint($event->object_id));
                $innerHtml = (new WADA_Layout_CommentOutline($comment, $event))->display(true);
                break;
            case WADA_Sensor_Base::OBJ_TYPE_PLG_WADA_NOTIFICATION:
                $model = new WADA_Model_Notification($event->object_id);
                $innerHtml = (new WADA_Layout_WADANotificationOutline($model->_data, $event->object_id))->display(true);
                break;
            case WADA_Sensor_Base::OBJ_TYPE_PLG_WADA_SENSOR:
                $model = new WADA_Model_Sensor($event->object_id);
                $innerHtml = esc_html('#'.$event->object_id.' '.$model->_data->name);
                break;
            default:
                if($useHtml) {
                    $innerHtml = '<table class="data wada-detail-table">';
                    $innerHtml .= '<tbody>';
                    $innerHtml .= '<tr>';
                    $innerHtml .= '<td class="label">'.esc_html($event->object_type).'</td>';
                    $innerHtml .= '<td class="value">'.intval($event->object_id).'</td>';
                    $innerHtml .= '</tr>';
                    $innerHtml .= '</tbody>';
                    $innerHtml .= '</table>';
                }else{
                    $innerHtml = $event->object_type . ' '. $event->object_id;
                }
        }
        $innerHtml = apply_filters('wp_admin_audit_html_event_object_details_content', $innerHtml, $event, $useHtml);

        $header = self::getObjectTypeDescription($event);
        $header = apply_filters('wp_admin_audit_html_event_object_details_header', $header, $event, $useHtml);
        if($useHtml) {
            $html = '<h3>'.$header .'</h3>';
            $html .= '<div class="'.self::LAYOUT_IDENTIFIER.' wada-event-object-details-tbl">';
            $html .= $innerHtml;
            $html .= '</div>';
        }else{
            $html = $header . "\r\n" . $innerHtml;
        }
        return apply_filters('wp_admin_audit_html_event_object_details', $html, $event, $useHtml);
    }

    public static function getObjectTypeDescription($event, $default=null){
        switch($event->object_type){
            case WADA_Sensor_Base::OBJ_TYPE_CORE_COMMENT:
                $objectTypeDesc = __('Comment', 'wp-admin-audit');
                break;
            case WADA_Sensor_Base::OBJ_TYPE_CORE_USER:
                $objectTypeDesc = __('User subject', 'wp-admin-audit');
                break;
            case WADA_Sensor_Base::OBJ_TYPE_CORE_POST:
                switch($event->event_group){
                    case WADA_Sensor_Base::GRP_MEDIA:
                        $objectTypeDesc = __('Media subject', 'wp-admin-audit');
                        break;
                    case WADA_Sensor_Base::GRP_POST:
                    default:
                        $objectTypeDesc = __('Post subject', 'wp-admin-audit');
                }
                break;
            case WADA_Sensor_Base::OBJ_TYPE_CORE_MENU:
            case WADA_Sensor_Base::OBJ_TYPE_CORE_TERM:
                $objectTypeDesc = WADA_TermUtils::getTaxonomyNameBySensor($event->sensor_id);
                break;
            case WADA_Sensor_Base::OBJ_TYPE_PLG_WADA_NOTIFICATION:
                $objectTypeDesc = __('Notification', 'wp-admin-audit');
                break;
            case WADA_Sensor_Base::OBJ_TYPE_PLG_WADA_SENSOR:
                $objectTypeDesc = __('Sensor', 'wp-admin-audit');
                break;
            default:
                if(is_null($default)){
                    $objectTypeDesc = __('Object data', 'wp-admin-audit');
                }else {
                    $objectTypeDesc = $default;
                }
        }
        return $objectTypeDesc;
    }

    function loadJavascriptActions(){ // override with extending functionality
        $renderDiff = $this->isDifferenceRenderingActive();
        $uiSettingDiff = ($renderDiff ? 'diff' : 'separate-view');
        $hideIdentical = $this->isSkipEventInfoRowIfIdentical();
        $uiSettingIdentical = ($hideIdentical ? 'hide-identical' : 'show-identical');
        ?>
        <input id="ui-setting-diff" name="ui-setting-diff" type="hidden" value="<?php echo esc_attr($uiSettingDiff) ?>" />
        <input id="ui-setting-identical" name="ui-setting-identical" type="hidden" value="<?php echo esc_attr($uiSettingIdentical) ?>" />
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery('#show-separate-view').on('click', function(e){
                    jQuery('#ui-setting-diff').val('separate-view');
                    updateUserInterface();
                    updateEventTable();
                });
                jQuery('#show-diff-view').on('click', function(e){
                    jQuery('#ui-setting-diff').val('diff');
                    updateUserInterface();
                    updateEventTable();
                });
                jQuery('#show-identical').on('click', function(e){
                    jQuery('#ui-setting-identical').val('show-identical');
                    updateUserInterface();
                    updateEventTable();
                });
                jQuery('#hide-identical').on('click', function(e){
                    jQuery('#ui-setting-identical').val('hide-identical');
                    updateUserInterface();
                    updateEventTable();
                });

                function updateEventTable(){
                    jQuery('.wada-event-details-control-bar-icons').hide();
                    jQuery('.event-details-table-spinner').addClass('is-active').show();
                    let data = {};
                    jQuery.ajax({
                        url: ajaxurl,
                        data: jQuery.extend({
                            _wpnonce: jQuery('#_wpnonce').val(),
                            action: '_wada_ajax_reload_event_details_table',
                            id: <?php echo $this->event->id; ?>,
                            diff: jQuery('#ui-setting-diff').val(),
                            skid: jQuery('#ui-setting-identical').val()
                        }, data),
                        success: function (response) {
                            jQuery('.event-details-table-spinner').hide().removeClass('is-active');
                            jQuery('.wada-event-details-control-bar-icons').show();
                            let resp = jQuery.parseJSON(response);
                            if(resp && resp.success){
                                jQuery('#wada-detail-table-container').html(resp.content);
                            }else{
                                console.log(response);
                                console.log(resp);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            jQuery('.event-details-table-spinner').hide().removeClass('is-active');
                            jQuery('.wada-event-details-control-bar-icons').show();
                            console.log(textStatus);
                            console.log(errorThrown);
                        }
                    });
                }

                function updateUserInterface(){
                    if(isSeparateView()){
                        jQuery('#show-diff-view').show();
                        jQuery('#show-separate-view').hide();
                    }else{
                        jQuery('#show-separate-view').show();
                        jQuery('#show-diff-view').hide();
                    }
                    if(isHideIdentical()){
                        jQuery('#show-identical').show();
                        jQuery('#hide-identical').hide();
                    }else{
                        jQuery('#hide-identical').show();
                        jQuery('#show-identical').hide();
                    }
                }

                function isSeparateView(){
                    let uiSettingDiff = jQuery('#ui-setting-diff').val();
                    if(uiSettingDiff === 'separate-view'){
                        return true;
                    }
                    return false; // diff view
                }

                function isHideIdentical(){
                    let uiSettingIdentical = jQuery('#ui-setting-identical').val();
                    if(uiSettingIdentical === 'hide-identical'){
                        return true;
                    }
                    return false; // show identical
                }

                jQuery('.wada-event-details-control-bar-icons').show(); // init
                updateUserInterface(); // init
            });
        </script>
        <?php
    }

    /**
     * @param stdClass $event
     * @return WADA_Layout_EventDetailsBase
     */
    public static function getEventDetailsLayout($event){
        switch ($event->sensor_id) {
            case WADA_Sensor_Base::EVT_CORE_UPDATE:
                $layout = new WADA_Layout_CoreEvent($event);
                break;
            case WADA_Sensor_Base::EVT_MEDIA_CREATE:
            case WADA_Sensor_Base::EVT_MEDIA_UPDATE:
            case WADA_Sensor_Base::EVT_MEDIA_DELETE:
                $layout = new WADA_Layout_MediaEvent($event);
            break;
            case WADA_Sensor_Base::EVT_POST_CREATE:
            case WADA_Sensor_Base::EVT_POST_UPDATE:
            case WADA_Sensor_Base::EVT_POST_DELETE:
            case WADA_Sensor_Base::EVT_POST_TRASHED:
            case WADA_Sensor_Base::EVT_POST_PUBLISHED:
            case WADA_Sensor_Base::EVT_POST_UNPUBLISHED:
            case WADA_Sensor_Base::EVT_POST_CATEGORY_ASSIGN_UPDATE:
            case WADA_Sensor_Base::EVT_POST_TAG_ASSIGN_UPDATE:
                $layout = new WADA_Layout_PostEvent($event);
                break;
            case WADA_Sensor_Base::EVT_CATEGORY_CREATE:
            case WADA_Sensor_Base::EVT_CATEGORY_UPDATE:
            case WADA_Sensor_Base::EVT_CATEGORY_DELETE:
            case WADA_Sensor_Base::EVT_POST_TAG_CREATE:
            case WADA_Sensor_Base::EVT_POST_TAG_UPDATE:
            case WADA_Sensor_Base::EVT_POST_TAG_DELETE:
            case WADA_Sensor_Base::EVT_MENU_CREATE:
            case WADA_Sensor_Base::EVT_MENU_UPDATE:
            case WADA_Sensor_Base::EVT_MENU_DELETE:
                $layout = new WADA_Layout_TermEvent($event);
                break;

            case WADA_Sensor_Base::EVT_COMMENT_CREATE:
            case WADA_Sensor_Base::EVT_COMMENT_UPDATE:
            case WADA_Sensor_Base::EVT_COMMENT_DELETE:
            case WADA_Sensor_Base::EVT_COMMENT_TRASHED:
            case WADA_Sensor_Base::EVT_COMMENT_UNTRASHED:
            case WADA_Sensor_Base::EVT_COMMENT_APPROVED:
            case WADA_Sensor_Base::EVT_COMMENT_UNAPPROVED:
            case WADA_Sensor_Base::EVT_COMMENT_SPAMMED:
                $layout = new WADA_Layout_CommentEvent($event);
                break;
            case WADA_Sensor_Base::EVT_FILE_THEME_FILE_EDIT:
            case WADA_Sensor_Base::EVT_FILE_PLUGIN_FILE_EDIT:
                $layout = new WADA_Layout_FileEvent($event);
                break;
            case WADA_Sensor_Base::EVT_SETTING_GENERAL_UPDATE:
            case WADA_Sensor_Base::EVT_SETTING_WRITING_UPDATE:
            case WADA_Sensor_Base::EVT_SETTING_READING_UPDATE:
            case WADA_Sensor_Base::EVT_SETTING_DISCUSSION_UPDATE:
            case WADA_Sensor_Base::EVT_SETTING_MEDIA_UPDATE:
            case WADA_Sensor_Base::EVT_SETTING_PERMALINK_UPDATE:
            case WADA_Sensor_Base::EVT_SETTING_PRIVACY_UPDATE:
                $layout = new WADA_Layout_SettingEvent($event);
                break;
            case WADA_Sensor_Base::EVT_USER_REGISTRATION:
            case WADA_Sensor_Base::EVT_USER_LOGIN:
            case WADA_Sensor_Base::EVT_USER_LOGIN_FAILED:
            case WADA_Sensor_Base::EVT_USER_LOGOUT:
            case WADA_Sensor_Base::EVT_USER_UPDATE:
            case WADA_Sensor_Base::EVT_USER_DELETE:
            case WADA_Sensor_Base::EVT_USER_PASSWORD_RESET:
                $layout = new WADA_Layout_UserEvent($event);
                break;
            case WADA_Sensor_Base::EVT_PLUGIN_INSTALL:
            case WADA_Sensor_Base::EVT_PLUGIN_DELETE:
            case WADA_Sensor_Base::EVT_PLUGIN_ACTIVATE:
            case WADA_Sensor_Base::EVT_PLUGIN_DEACTIVATE:
            case WADA_Sensor_Base::EVT_PLUGIN_UPDATE:
                $layout = new WADA_Layout_PluginEvent($event);
                break;
            case WADA_Sensor_Base::EVT_PLG_WADA_SENSOR_UPDATE:
            case WADA_Sensor_Base::EVT_PLG_WADA_SETTINGS_UPDATE:
            case WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_CREATE:
            case WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_UPDATE:
            case WADA_Sensor_Base::EVT_PLG_WADA_NOTIFICATION_DELETE:
                $layout = new WADA_Layout_WADAEvent($event);
                break;
            case WADA_Sensor_Base::EVT_THEME_INSTALL:
            case WADA_Sensor_Base::EVT_THEME_DELETE:
            case WADA_Sensor_Base::EVT_THEME_SWITCH:
            case WADA_Sensor_Base::EVT_THEME_UPDATE:
                $layout = new WADA_Layout_ThemeEvent($event);
                break;
            default:
                $layout = new self($event);
        }
        return apply_filters('wp_admin_audit_html_event_details_layout', $layout, $event);
    }
}