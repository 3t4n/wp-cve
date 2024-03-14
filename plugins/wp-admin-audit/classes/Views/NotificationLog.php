<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_View_NotificationLog extends WADA_View_BaseList
{
    const VIEW_IDENTIFIER = 'wada-notification-log';
    const LIST_FORM_ID = 'wada-notification-log-list-form';
    const AJAX_ACTION = '_wada_ajax_notification_log_list';
    const NONCE_ACTION = 'wada-ajax-notification_log_list-nonce';
    const NONCE_NAME = '_wada_ajax_notification_log_list_nonce';
    public $viewMode;
    public $eventNotificationObj;
    public $notificationObj;
    public $eventObj;

    public function __construct($viewConfig = array()) {
        WADA_ScriptUtils::loadSelect2();
        $this->viewMode = $this->getViewMode();
        $this->buildViewHeadline();
        parent::__construct( array(
            'singular' => __( 'Notification log', 'wp-admin-audit' ), //singular name of the grouped records
            'plural'   => __( 'Notification log entries', 'wp-admin-audit' ), //plural name of the grouped records
            'ajax'     => true // does this table support ajax?
        ), $viewConfig );
        add_action('admin_footer', array($this, 'loadJavascriptActions'));
    }

    protected function buildViewHeadline(){
        $this->viewHeadline =  __('Notification Log', 'wp-admin-audit');
        if($this->viewMode === 'event-notification'){
            $this->eventNotificationObj = WADA_Notification_Queue::getEventNotification($this->getEventNotificationIdFromRequest());
            if($this->eventNotificationObj){
                $eventStr = '#'.$this->eventNotificationObj->event_id.' ('.$this->eventNotificationObj->sensor_name.')';
                $notificationStr = '#'.$this->eventNotificationObj->notification_id.' ('.$this->eventNotificationObj->notification_name.')';
                $this->viewSubHeadline = sprintf(__('Event %s – Notification %s'), $eventStr, $notificationStr);
            }
        }elseif($this->viewMode == 'notification'){
            $notificationModel = new WADA_Model_Notification($this->getNotificationIdFromRequest());
            $this->notificationObj = $notificationModel->_data;
            if($this->notificationObj){
                $notificationStr = '#'.$this->notificationObj->id.' ('.$this->notificationObj->name.')';
                $this->viewSubHeadline = sprintf(__('Notification %s'), $notificationStr);
            }
        }elseif($this->viewMode == 'event'){
            $eventModel = new WADA_Model_Event($this->getEventIdFromRequest());
            $this->eventObj = $eventModel->_data;
            if($this->eventObj) {
                $eventStr = '#'.$this->eventObj->id.' ('.$this->eventObj->sensor_name.')';
                $this->viewSubHeadline = sprintf(__('Event %s'), $eventStr);
            }
        }
    }

    function get_columns() {
        $cols = array(
            'title'    => __( 'ID', 'wp-admin-audit' ),
            'event_time' => __( 'Date', 'wp-admin-audit' ),
            'event_description' => __('Description', 'wp-admin-audit'),
            'recipients' => __('Recipients', 'wp-admin-audit'),
            'event_link' => __('Event', 'wp-admin-audit'),
        );
        return $cols;
    }

    public function get_sortable_columns() {
        return array(
            'event_time' => array('event_time', 'asc'),
            'title' => array('id', 'desc'),
            'event_link' => array('event_id', 'asc')
        );
    }

    public function getDefaultOrder(){ // override parent to sort by two columns by default
        return array('event_time ASC, ID ASC', '');
    }

    function column_title($item) {
        WADA_Log::debug('Log item: '.print_r($item, true));
        $title = '#'.absint($item['id']) . ' '.$item['event_type_name'];
        return $this->formatSearchResult(esc_html($title), $this->getSearchTerm());
    }

    function column_event_time($item) {
        return esc_html($item['event_time_str']);
    }

    function column_event_link($item){
        return $item['event_link']; // no html escape, this is built ok
    }

    function column_recipients($item) {

        if($item['channel_type'] === 'logsnag'){
            $recipients = 'Logsnag API';
        }else{
            $recipients = $item['recips'];
            if($recipients){
                $recipients = json_decode($recipients);
                if(is_array($recipients)){
                    $recipients = implode(', ', $recipients);
                }else{
                    if(!is_scalar($recipients)){
                        $recipients = print_r($recipients, true);
                    }
                }
            }
        }
        return $this->formatSearchResult(esc_html($recipients), $this->getSearchTerm());
    }

    public function no_items() {
        echo '<p>';
        if ( ! empty( $_REQUEST['s'] ) ) {
            $s = esc_html(wp_unslash(sanitize_text_field($_REQUEST['s'])));
            printf( __('Nothing found for: %s', 'wp-admin-audit'), '<strong>' . $s . '</strong>' );
        } elseif ( $this->areFiltersActive() ) {
            _e('No entries found for filter selection', 'wp-admin-audit');
        } else {
            _e('No entries in log', 'wp-admin-audit');
        }
        echo '</p>';
    }

    protected function handleFormSubmissions(){
        if(isset($_POST['submit'])){
            check_admin_referer(self::VIEW_IDENTIFIER);
        }
    }
    
    protected function renderHtmlAboveList(){
        if($this->viewMode === 'event-notification'){
            echo '<h4>'.__('Event details', 'wp-admin-audit').'</h4>';
            if($this->eventNotificationObj) {
                $eventModel = new WADA_Model_Event($this->eventNotificationObj->event_id);
                $eventModel->_data->nr_queue_entries = WADA_Notification_Queue::getNrOfQueueEntries($this->eventNotificationObj->id, 0, 0);
                (new WADA_Layout_EventOutline($eventModel->_data))->display();
            }
        }elseif($this->viewMode == 'notification'){
            if($this->notificationObj) {
                $this->notificationObj->events = WADA_Notification_Queue::getEventNotificationsForNotificationId($this->notificationObj->id);
                $this->notificationObj->nr_queue_entries = WADA_Notification_Queue::getNrOfQueueEntries(0, 0, $this->notificationObj->id);
                (new WADA_Layout_NotificationOutline($this->notificationObj))->display();
            }
        }elseif($this->viewMode == 'event'){
            echo '<h4>'.__('Event details', 'wp-admin-audit').'</h4>';
            if($this->eventObj) {
                $this->eventObj->nr_queue_entries = WADA_Notification_Queue::getNrOfQueueEntries(0, $this->eventObj->id, 0);
                (new WADA_Layout_EventOutline($this->eventObj))->display();
            }
        }
        if($this->viewMode != 'mixed'){
            echo '<h4>'.__('Notification log', 'wp-admin-audit').'</h4>';
        }
    }

    protected function getViewMode(){
        $eventNotificationId = $this->getEventNotificationIdFromRequest();
        $notificationId = $this->getNotificationIdFromRequest();
        $eventId = $this->getEventIdFromRequest();
        if($eventNotificationId > 0 && $notificationId == 0 && $eventId == 0){
            return 'event-notification';
        }
        if($eventNotificationId == 0 && $notificationId > 0 && $eventId == 0){
            return 'notification';
        }
        if($eventNotificationId == 0 && $notificationId == 0 && $eventId > 0){
            return 'event';
        }
        if($eventNotificationId == 0 && $notificationId == 0 && $eventId == 0){
            return 'no-filter';
        }
        return 'mixed'; // two or three filters are in place
    }

    protected function getEventNotificationIdFromRequest($default = 0){
        if (isset($_GET['enid'])){
            return absint($_GET['enid']);
        }
        return $default;
    }

    protected function getNotificationIdFromRequest($default = 0){
        if (isset($_GET['nid'])){
            return absint($_GET['nid']);
        }
        return $default;
    }

    protected function getEventIdFromRequest($default = 0){
        if (isset($_GET['eid'])){
            return absint($_GET['eid']);
        }
        return $default;
    }

    protected function getLogEventTypeFromRequest($default = 0){
        if (isset($_GET['et'])){
            return absint($_GET['et']);
        }
        return $default;
    }

    protected function getLogEventTypeOptions(){
        $logEventTypeOptions = array();
        $logEventTypeOptions[0] = __('All types', 'wp-admin-audit');
        $allEventTypes = WADA_Notification_Log::getAllNotificationEventTypeNames();
        foreach($allEventTypes AS $eventTypeCode => $eventTypeName){
            $logEventTypeOptions[$eventTypeCode] = $eventTypeName;
        }
        return $logEventTypeOptions;
    }

    protected function areFiltersActive(){
        $eventId = 0;
        if($this->viewMode === 'no-filter' || $this->viewMode === 'event') {
            $eventId = $this->getEventIdFromRequest();
        }
        $eventType = $this->getLogEventTypeFromRequest();

        if($eventId > 0 || $eventType > 0){
            return true;
        }
        return false;
    }

    protected function getFilterControls($filterControls = array()){

        $hiddenViewMode = new stdClass();
        $hiddenViewMode->type = 'hidden';
        $hiddenViewMode->field = 'view_mode';
        $hiddenViewMode->value = $this->getViewMode();
        $filterControls[] = $hiddenViewMode;

        if($this->viewMode === 'no-filter' || $this->viewMode === 'notification') {
            $eventFilter = new stdClass();
            $eventFilter->type = 'select';
            $eventFilter->value = $this->getEventIdFromRequest();
            $eventFilter->field = 'event_id';
            $eventFilter->label = null; // no need for label
            $eventFilter->selectOptions = array();  // event options are coming via Ajax from server (after user enters search entry)
            $filterControls[] = $eventFilter;

            $hiddenInput = new stdClass();
            $hiddenInput->type = 'hidden';
            $hiddenInput->field = 'wada_event_search';
            $hiddenInput->value = wp_create_nonce('wada_event_search');
            $filterControls[] = $hiddenInput;
        }

        $typeFilter = new stdClass();
        $typeFilter->type = 'select';
        $typeFilter->value = $this->getLogEventTypeFromRequest();
        $typeFilter->field = 'event_type';
        $typeFilter->label = null; // no need for label
        $typeFilter->selectOptions = $this->getLogEventTypeOptions();
        $filterControls[] = $typeFilter;

        return parent::getFilterControls($filterControls);
    }

    protected function performAdditionalItemPreparation(){
        foreach($this->items AS $key => $item){
            $this->items[$key]['event_type_name'] = WADA_Notification_Log::getNotificationEventTypeName($item['event_type']);
            $this->items[$key]['event_description'] = WADA_Notification_Log::getNotificationEventDescription((object)$item);
            $this->items[$key]['event_time_str'] = WADA_DateUtils::formatUTCasDatetimeForWP($item['event_time']);

            $linkUrl = sprintf(admin_url('admin.php?page=wp-admin-audit-events&subpage=event-details&sid=%d'), $item['event_id']);
            $linkText = (new WADA_Model_Event($item['event_id']))->_data->summary_short;
            $eventLink = sprintf('<a href="%s">%s</a>', $linkUrl, $linkText);
            $this->items[$key]['event_link'] = $eventLink;
        }
        return $this->items;
    }

    protected function getItemsQuery($searchTerm = null){
        $sql = "SELECT"
                ." nlog.id, nlog.event_notification_id, nlog.event_time, nlog.event_type, nlog.channel_type, nlog.recips, nlog.int_val1, nlog.int_val2, nlog.int_val3, nlog.int_val4, nlog.msg, "
                ." evno.event_id, evno.notification_id, evno.sent, evno.sent_on, evno.send_errors,"
                ." noti.name AS notification_name"
                ." FROM ".WADA_Database::tbl_event_notification_log() . " nlog "
                ." LEFT JOIN ".WADA_Database::tbl_event_notifications() . " evno ON (nlog.event_notification_id = evno.id)"
                ." LEFT JOIN ".WADA_Database::tbl_notifications() . " noti ON (evno.notification_id = noti.id)";

        $whereForEventNotification = array();
        $eventNotificationId = $this->getEventNotificationIdFromRequest();
        $notificationId = $this->getNotificationIdFromRequest();
        $eventId = $this->getEventIdFromRequest();
        $eventType = $this->getLogEventTypeFromRequest();

        if($eventNotificationId){
            $whereForEventNotification[] = 'id=\''.$eventNotificationId.'\'';
        }
        if($notificationId){
            $whereForEventNotification[] = 'notification_id=\''.$notificationId.'\'';
        }
        if($eventId){
            $whereForEventNotification[] = 'event_id=\''.$eventId.'\'';
        }
        if($eventType){
            $whereForEventNotification[] = 'event_type=\''.$eventType.'\'';
        }
        if(count($whereForEventNotification)){
            $sql .= " WHERE nlog.event_notification_id IN (SELECT id FROM " . WADA_Database::tbl_event_notifications() . " WHERE " . implode(' AND ', $whereForEventNotification) . ")";
        }

        if($searchTerm) {
            $fieldsToSearchIn = array('nlog.recips', 'nlog.msg', 'nlog.channel_type', 'noti.name');
            $sep = " LIKE '%".$searchTerm."%') OR (";
            $whereCond = implode($sep, $fieldsToSearchIn);
            $whereCond .= " LIKE '%".$searchTerm."%')";
            if(count($whereForEventNotification)){
                $sql .= " AND ( (".$whereCond." )";
            }else{
                $sql .= " WHERE (".$whereCond;
            }
        }

        return $sql;
    }

    function loadJavascriptActions(){ // override with extending functionality ?>
        <script type="text/javascript">
            function <?php $this->jsPrefix(); ?>getAdditionalQueryVariables(thisListRef){
                let eventId = (thisListRef.__query(document.location.href, 'eid') || '');
                if(jQuery('select[name=event_id]').length){
                    eventId = jQuery('select[name=event_id]').val() || eventId;
                }
                let eventType = jQuery('select[name=event_type]').val() || '';
                let data = {
                    et: eventType,
                    eid: eventId,
                    nid: thisListRef.__query(document.location.href, 'nid') || '',
                    enid: thisListRef.__query(document.location.href, 'enid') || ''
                };
                return data;
            }
            function <?php $this->jsPrefix(); ?>getAdditionalParametersFromUrl(thisListRef, query){
                let data = {
                    et: thisListRef.__query(document.location.href, 'et') || '',
                    eid: thisListRef.__query(document.location.href, 'eid') || '',
                    nid: thisListRef.__query(document.location.href, 'nid') || '',
                    enid: thisListRef.__query(document.location.href, 'enid') || ''
                };
                return data;
            }
            function <?php $this->jsPrefix(); ?>registerAdditionalListEvents(thisListRef){
                if(jQuery('#event_id').length) {
                    jQuery('#event_id').on('select2:select select2:clear', {thisList: thisListRef}, function (e) {
                        let eventId = jQuery('select[name=event_id]').val();
                        let searchParams = new URLSearchParams(window.location.search);
                        if(eventId === null){
                            searchParams.delete("eid");
                        }else {
                            searchParams.set("eid", eventId);
                        }
                        let newRelativePathQuery = window.location.pathname + '?' + searchParams.toString();
                        history.pushState(null, '', newRelativePathQuery);
                        let data = e.data.thisList.getQueryVariablesFromInputs();
                        e.data.thisList.update(data);
                    });
                }
                jQuery('#event_type').on('change', {thisList: thisListRef}, function (e) {
                    let data = e.data.thisList.getQueryVariablesFromInputs();
                    e.data.thisList.update(data);
                });
            }
            function <?php $this->jsPrefix(); ?>unregisterAdditionalListEvents(thisListRef){
                if(jQuery('#event_id').length) {
                    jQuery('#event_id').off('select2:select select2:clear');
                }
                jQuery('#event_type').off('change');
            }
            jQuery(document).ready(function() {
                jQuery('#event_id').select2({  // select2 initialize
                    dropdownAutoWidth: true,
                    placeholder: '<?php echo esc_js(__('All events', 'wp-admin-audit')); ?>',
                    allowClear: true, // for remove/unselect button
                    ajax: {
                        url: ajaxurl, // AJAX URL is predefined in WordPress admin
                        dataType: 'json',
                        delay: 250, // delay in ms while typing when to perform a AJAX search
                        data: function (params) {
                            return {
                                _wpnonce: jQuery('#wada_event_search').val(),
                                action: '_wada_ajax_event_search',
                                s: params.term, // search query
                                'w_noti_log': 1
                            };
                        },
                        processResults: function( response ) {
                            let options = [];
                            if(response && response.success){
                                // data is the array of arrays, and each of them contains ID and the Label of the option
                                jQuery.each( response.events, function( index, event ) { // do not forget that "index" is just auto incremented value
                                    options.push( { id: event.id, text: event.select_option  } );
                                });
                            }
                            return {
                                results: options
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 3 // the minimum of symbols to input before perform a search
                });
            });
        </script>
        <?php
        parent::loadJavascriptActions(); // make sure base class also loads JS
    }

}