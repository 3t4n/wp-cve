<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_View_Events extends WADA_View_BaseList
{
    const VIEW_IDENTIFIER = 'wada-events';
    const LIST_FORM_ID = 'wada-event-list-form';
    const AJAX_ACTION = '_wada_ajax_events_list';
    const NONCE_ACTION = 'wada-ajax-events-list-nonce';
    const NONCE_NAME = '_wada_ajax_events_list_nonce';

    public function __construct($viewConfig = array()) {
        $this->parentHeadline = __('Audit', 'wp-admin-audit');
        $this->parentHeadlineLink = admin_url('admin.php?page=wp-admin-audit&subpage=audit');
        $this->viewHeadline =  __('Event Log', 'wp-admin-audit');
        $this->csvExport = array('width' => 300, 'height' => 200);
        parent::__construct( array(
            'singular' => __( 'Event', 'wp-admin-audit' ), //singular name of the grouped records
            'plural'   => __( 'Events', 'wp-admin-audit' ), //plural name of the grouped records
            'ajax'     => true // does this table support ajax?
        ), $viewConfig );
        add_action('admin_footer', array($this, 'loadJavascriptActions'));
        WADA_ScriptUtils::loadSelect2();
    }

    public function getDefaultOrder(){ // override parent to sort by two columns by default
        return array('id', 'DESC');
    }

    function get_columns() {
        $colArray = array(
            'title'    => __( 'Sensor / Event', 'wp-admin-audit' ),
            'severity'    => __( 'Severity', 'wp-admin-audit' ),
            'occurred_on'    => __( 'Date', 'wp-admin-audit' ),
            'user_name'    => __( 'User', 'wp-admin-audit' ),
            'sensor_name' => __( 'Event Type', 'wp-admin-audit' )
        );
        if(WADA_Replicator_Base::isReplicationActive()){
            $colArray['replication_done'] = __( 'Replication', 'wp-admin-audit' );
        }
        $colArray['id_only'] = __( 'ID', 'wp-admin-audit' );
        $searchTerm = $this->getSearchTerm();
        if($searchTerm){
            $colArray['search_res'] = __( 'Search result', 'wp-admin-audit' );
        }
        return $colArray;
    }

    public function get_sortable_columns() {
        $colArray = array(
            'title' => array('sensor_name', 'asc'),
            'severity' => array('severity', 'desc'),
            'occurred_on' => array('occurred_on', 'desc'),
            'user_name' => array('user_name', 'asc'),
            'sensor_name' => array('sensor_name', 'asc'),
            'id_only' => array('id', 'asc')
        );
        if(WADA_Replicator_Base::isReplicationActive()){
            $colArray['replication_done'] = array('replication_done', 'asc');
        }
        return $colArray;
    }

    function column_title($item) {
        //WADA_Log::debug('item: '.print_r($item, true));
        $titleUrl = admin_url(sprintf(
                'admin.php?page=wp-admin-audit-events&subpage=event-details&amp;sid=%s',
            absint( $item['id'] )
        ));
        $name = is_null($item['sensor_name']) ? sprintf(__('Sensor ID %d', 'wp-admin-audit'), $item['sensor_id']) : $item['sensor_name'];
        return sprintf(
                '<a href="'.$titleUrl.'"><strong>%s</strong></a>',
                $this->formatSearchResult($name, $this->getSearchTerm())
        );
    }

    function column_severity($item){
        return esc_html($item['severity_text']);
    }

    function column_occurred_on($item){
        return $this->column_localized_timestamp($item['occurred_on']);
    }

    function column_user_name($item){
        if($item['user_id'] > 0){
            return $item['user_name'];
        }elseif($item['user_id'] == WADA_Sensor_Base::WADA_PSEUDO_USER_ID){
            return '<span class="wada-greyed-out wada-italic">'.esc_html(__('WP Admin Audit', 'wp-admin-audit')).'</span>';
        }else{
            return '';
        }
    }

    function column_replication_done($item){
        $status = '';
        switch(intval($item['replication_done'])){
            case -1:
                $status = '<span class="wada-error">'.__('Failed', 'wp-admin-audit').'</span>';
                break;
            case 0:
                $status = '<span class="wada-warning">'.__('Pending', 'wp-admin-audit').'</span>';
                break;
            case 1:
                $status = __('Done', 'wp-admin-audit');
                break;
            default:
                $status = '<span class="wada-warning">'.sprintf(__('Unknown value: %s', 'wp-admin-audit'), intval($item['replication_done'])).'</span>';
        }
        echo $status;
    }

    public function eventSearchAjaxResponse(){
        $this->prepare_items();
        $events = array();
        WADA_Log::debug('eventSearchAjaxResponse items '.print_r($this->items, true));
        foreach ($this->items as $item) {
            $occurredOn = WADA_DateUtils::formatUTCasDatetimeForWP($item['occurred_on']);
            $itemObj = new stdClass();
            $itemObj->id = $item['id'];
            $itemObj->select_option = '#'.$item['id'].' '.$item['sensor_name'] . ' / '.$occurredOn;
            $events[] = $itemObj;
        }
        $response = array('success' => true, 'events' => $events);
        die( json_encode( $response ) );
    }

    protected function getSensorFilterFromRequest(){
        return $this->getIntSelectionFromRequest('sensor', null, array_keys($this->getSensorOptions()));
    }

    protected function getSeverityFilterFromRequest(){
        $ret = $this->getIntSelectionFromRequest('severity', null, array_keys(WADA_Model_Sensor::getSeverityLevels(true)));
        //WADA_Log::debug('getSeverityFilterFromRequest ret: '.$ret.' (is null: '.is_null($ret).')');
        if(is_null($ret)){
            // because the value intval(null) is 0
            // and the value 0 is actually a valid filter for severity=debug,
            // so use -1 to show this filter is not active
            $ret = -1;
        }
        return $ret;
    }

    protected function getDayLimitFilterFromRequest(){
        return $this->getIntSelectionFromRequest('days', 0, $this->getDayLimitOptions());
    }

    protected function getSensorOptions(){
        global $wpdb;

        $sensorOptions = array();
        $firstOption = new stdClass();
        $firstOption->id = 0;
        $firstOption->name = __('All event types', 'wp-admin-audit');
        $firstOption->cat = '&nbsp;';
        $sensorOptions[0] = $firstOption;

        $sql= "SELECT sen.id, sen.name, sen.event_group, sen.event_category"
            ." FROM ".WADA_Database::tbl_sensors() ." sen"
            ." WHERE sen.id <> '".WADA_Sensor_Base::EVT_PLG_PSEUDO."'"
            ." AND sen.id IN (SELECT DISTINCT sensor_id AS id FROM ".WADA_Database::tbl_events().")"; // only show sensors to filter with that have events in the log
        $sensors = $wpdb->get_results($sql);
        foreach($sensors AS $sensor){
            $opt = new stdClass();
            $opt->id = $sensor->id;
            $opt->name = $sensor->name;
            $opt->cat = WADA_Model_Sensor::getEventGroupName($sensor->event_group);
            $sensorOptions[$sensor->id] = $opt;
        }

        return $sensorOptions;
    }

    protected function getDayLimitOptions(){
        return array(0, 7, 30, 90);
    }

    protected function displayAfterList(){

        /*  */

        parent::displayAfterList();
    }

    protected function getSeverityDropdownOptions(){
        $severityLevels = array('-1' => __('All severity levels'));
        return $severityLevels + WADA_Model_Sensor::getSeverityLevels(true);
    }

    protected function getFilterControls($filterControls = array()){
        $eventGroupFilter = new stdClass();
        $eventGroupFilter->type = 'select';
        $eventGroupFilter->value = $this->getSensorFilterFromRequest();
        $eventGroupFilter->field = 'sensor';
        $eventGroupFilter->label = null; // no need for label
        $eventGroupFilter->selectOptions = $this->getSensorOptions();
        $eventGroupFilter->options = array('use_optgroup'=>true);
        $filterControls[] = $eventGroupFilter;

        $severityFilter = new stdClass();
        $severityFilter->type = 'select';
        $severityFilter->value = $this->getSeverityFilterFromRequest();
        $severityFilter->field = 'severity';
        $severityFilter->label = null; // no need for label
        $severityFilter->selectOptions = $this->getSeverityDropdownOptions();
        $filterControls[] = $severityFilter;

        return parent::getFilterControls($filterControls);
    }

    protected function performAdditionalItemPreparation(){
        $severityLevels = WADA_Model_Sensor::getSeverityLevels();
        foreach($this->items AS $key => $item){
            if($item && array_key_exists('severity', $item) && array_key_exists($item['severity'], $severityLevels)){
                $this->items[$key]['severity_text'] = $severityLevels[$item['severity']];
            }else{
                $this->items[$key]['severity_text'] = '';
            }

            if($item && array_key_exists('sensor_name', $item)) {
                $this->items[$key]['summary_short'] = '#' . $item['id'] . ' ' . $item['sensor_name'];
            }else{
                $this->items[$key]['summary_short'] = '';
            }

        }
        return $this->items;
    }

    protected function areFiltersActive(){
        $sensorId = $this->getSensorFilterFromRequest();
        if($sensorId > 0){
            return true;
        }
        $severityFilter = $this->getSeverityFilterFromRequest();
        if($severityFilter >= 0){
            return true;
        }
        $dayFilter = $this->getDayLimitFilterFromRequest();
        if($dayFilter > 0){
            return true;
        }
        return false;
    }

    protected function getItemsQuery($searchTerm = null){
        $sensorId = $this->getSensorFilterFromRequest();
        $severity = $this->getSeverityFilterFromRequest();
        $dayLimit = $this->getDayLimitFilterFromRequest();
        $withNotificationLog = array_key_exists('w_noti_log', $_GET) && (intval($_GET['w_noti_log']) > 0);
        $sql= "SELECT evt.id, evt.occurred_on, evt.sensor_id, "
            ."evt.user_id, CASE WHEN evt.user_id = ".WADA_Sensor_Base::WADA_PSEUDO_USER_ID." THEN 'WP Admin Audit' ELSE evt.user_name END AS user_name, evt.user_email, "
            ."evt.object_type, evt.object_id, evt.source_ip, evt.replication_done, "
            ."sen.name as sensor_name, sen.severity, sen.event_group, sen.event_category "
            .($searchTerm ? ", ei_search_res.search_res as search_res " : ", NULL AS search_res ")
            ."FROM ".WADA_Database::tbl_events() . " evt "
            ."LEFT JOIN ".WADA_Database::tbl_sensors() ." sen ON (evt.sensor_id = sen.id)";

        $notificationLogFilter = '';
        if($withNotificationLog){
            $notificationLogFilter .= ' EXISTS (';
            $notificationLogFilter .= '    SELECT event_notification_id FROM '.WADA_Database::tbl_event_notification_log().' nlog ';
            $notificationLogFilter .= '    LEFT JOIN '.WADA_Database::tbl_event_notifications().' en ON (nlog.event_notification_id = en.id)';
            $notificationLogFilter .= '    WHERE event_id = evt.id';
            $notificationLogFilter .= ' )';
        }
        if($searchTerm) {
            $sql .=
            "LEFT JOIN ("
                . "SELECT event_id, GROUP_CONCAT(DISTINCT search_res ORDER BY search_res SEPARATOR ', ') AS search_res "
                . "FROM ( "
                . "	SELECT event_id, info_value as search_res FROM " . WADA_Database::tbl_event_infos() . " ei1 WHERE info_value LIKE '%" . $searchTerm . "%' "
                . "	UNION SELECT event_id, info_value as search_res FROM " . WADA_Database::tbl_event_infos() . " ei2 WHERE prior_value LIKE '%" . $searchTerm . "%' "
                . ") ei_res  "
                . "GROUP BY event_id "
            . ") ei_search_res ON (evt.id = ei_search_res.event_id)";

            $fieldsToSearchIn = array('evt.user_name', 'evt.user_email', 'evt.source_ip', 'sen.name');
            $sep = " LIKE '%".$searchTerm."%') OR (";
            $whereCond = implode($sep, $fieldsToSearchIn);
            $whereCond .= " LIKE '%".$searchTerm."%')";
            $whereCond .= " OR ei_search_res.event_id IS NOT NULL )";
            if($sensorId && $sensorId > 0){
                $whereCond .= ' AND (evt.sensor_id = ' . intval($sensorId) .')';
            }
            if(intval($severity) >= 0) { // severity = 0 = debug = valid filter
                $whereCond .= ' AND (sen.severity = ' . intval($severity) .')';
            }
            if($dayLimit > 0){
                WADA_Log::debug('Filter with dayLimit: '.$dayLimit);
                $whereCond .= ' AND (evt.occurred_on >= DATE(NOW() - INTERVAL '.intval($dayLimit).' DAY))';
            }
            if($withNotificationLog){
                $whereCond .= ' AND '.$notificationLogFilter;
            }
            $sql .= ' WHERE ( ('.$whereCond;
        }else{
            $whereCond = array();
            if($sensorId && $sensorId > 0){
                $whereCond[] = 'evt.sensor_id = ' . intval($sensorId);
            }
            if(intval($severity) >= 0) { // severity = 0 = debug = valid filter
                $whereCond[] = 'sen.severity = ' . intval($severity);
            }
            if($dayLimit > 0){
                WADA_Log::debug('Filter with dayLimit: '.$dayLimit);
                $whereCond[] = '(evt.occurred_on >= DATE(NOW() - INTERVAL '.intval($dayLimit).' DAY))';
            }
            if($withNotificationLog){
                $whereCond[] = $notificationLogFilter;
            }
            if(count($whereCond) > 0) {
                $sql .= (' WHERE ' . implode(' AND ', $whereCond));
            }
        }
        return $sql;
    }


    function loadJavascriptActions(){ // override with extending functionality ?>
        <script type="text/javascript">
            function <?php $this->jsPrefix(); ?>getAdditionalQueryVariables(thisListRef){
                let data = {
                    sensor: jQuery('select[name=sensor]').val() || '',
                    severity: jQuery('select[name=severity]').val() || ''
                };
                return data;
            }
            function <?php $this->jsPrefix(); ?>getAdditionalParametersFromUrl(thisListRef, query){
                let data = {
                    sensor: thisListRef.__query( query, 'sensor' ) || '',
                    severity: thisListRef.__query( query, 'severity' ) || '',
                };
                return data;
            }
            function <?php $this->jsPrefix(); ?>registerAdditionalListEvents(thisListRef){
                jQuery('#sensor').on('select2:select select2:clear', {thisList: thisListRef}, function (e) {
                    let data = e.data.thisList.getQueryVariablesFromInputs();
                    e.data.thisList.update(data);
                });
                jQuery('#severity').on('change', {thisList: thisListRef}, function (e) {
                    let data = e.data.thisList.getQueryVariablesFromInputs();
                    e.data.thisList.update(data);
                });
            }
            function <?php $this->jsPrefix(); ?>unregisterAdditionalListEvents(){
                jQuery('#sensor').off('select2:select select2:clear');
                jQuery('#severity').off('change');
            }
            function <?php $this->jsPrefix(); ?>startCsvExport(thisListRef, csvSep, clickedCSVButton){
                let actionStr = '_wada_ajax_events_csv_export';
                let dayLimit = parseInt(jQuery(clickedCSVButton).data('day-limit'));
                let data = thisListRef.getQueryVariablesFromInputs();
                jQuery.extend(data, {
                    days: dayLimit,
                    csvsep: csvSep
                });
                doCSVAjaxRequest(actionStr, data, '<?php echo esc_js(strtolower($this->_args['plural'])); ?>');
            }
            jQuery(document).ready(function() {
                jQuery('#sensor').select2({
                    closeOnSelect: true,
                    multiple: false,
                    dropdownAutoWidth: true,
                    placeholder: '<?php echo esc_js(__('All event types', 'wp-admin-audit')); ?>',
                    allowClear: true, // for remove/unselect button
                }).val("").trigger('change');
            });
        </script>
        <?php
        parent::loadJavascriptActions(); // make sure base class also loads JS
    }

}