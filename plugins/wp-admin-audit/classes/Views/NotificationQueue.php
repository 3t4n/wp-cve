<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_View_NotificationQueue extends WADA_View_BaseList
{
    const VIEW_IDENTIFIER = 'wada-notification-queue';
    const LIST_FORM_ID = 'wada-notification-queue-list-form';
    const AJAX_ACTION = '_wada_ajax_notification_queue_list';
    const NONCE_ACTION = 'wada-ajax-notification_queue_list-nonce';
    const NONCE_NAME = '_wada_ajax_notification_queue_list_nonce';

    public $viewMode;
    public $eventNotificationObj;
    public $notificationObj;
    public $eventObj;

    public function __construct($viewConfig = array()) {
        $this->viewMode = $this->getViewMode();
        $this->buildViewHeadline();
        parent::__construct( array(
            'singular' => __( 'Queue log', 'wp-admin-audit' ), //singular name of the grouped records
            'plural'   => __( 'Queue log entries', 'wp-admin-audit' ), //plural name of the grouped records
            'ajax'     => true // does this table support ajax?
        ), $viewConfig);
        add_action('admin_footer', array($this, 'loadJavascriptActions'));
    }

    protected function buildViewHeadline(){
        $this->viewHeadline =  __('Notification Queue', 'wp-admin-audit');
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
        return array(
            'cb'      => '<input type="checkbox" />',
            'title'    => __( 'ID', 'wp-admin-audit' ),
            'channel_type' => __( 'Channel', 'wp-admin-audit' ),
            'email_address' => __('Email address', 'wp-admin-audit'),
            'attempt_count' => __('#Attempts', 'wp-admin-audit')
        );
    }

    public function get_sortable_columns() {
        return array(
            'title' => array('id', 'desc'),
            'channel_type' => array('channel_type', 'asc'),
            'email_address' => array('email_address', 'asc'),
            'attempt_count' => array('attempt_count', 'asc')
        );
    }

    public function get_bulk_actions() {
        return array(
            '_wada_ajax_notification_queue_list_bulk_delete' => __('Delete', 'wp-admin-audit')
        );
    }

    protected function deleteQueueEntry($id){
        WADA_Log::debug('deleteQueueEntry ID '.$id);
        $queueEntry = WADA_Notification_Queue::getQueueEntry($id);
        $msgDeletedBy = sprintf(__('User ID %d', 'wp-admin-audit'), get_current_user_id());
        WADA_Notification_Log::logNotificationQueueEntryDeletedByQueueId($id, $queueEntry->channel_type, array($queueEntry->email_address), $msgDeletedBy, $queueEntry->attempt_count);
        return WADA_Notification_Queue::removeFromQueue($id);
    }

    public function process_bulk_action() {
        $action = $this->current_action();
        WADA_Log::debug(static::VIEW_IDENTIFIER.'->process_bulk_action, action: '.$action);
        switch ($action) {
            case 'delete':
                $nonce = sanitize_text_field( $_REQUEST['_wpnonce'] );
                if ( ! wp_verify_nonce( $nonce, 'wada_notification_queue_deletion' ) ) {
                    die( 'Stop CSRF!' );
                } else {
                    WADA_Log::debug('process_bulk_action delete single queue entry ID '.absint( $_GET['id'] ));
                    $this->deleteQueueEntry( absint( $_GET['id'] ) );
                }
                break;
            case '_wada_ajax_notification_queue_list_bulk_delete':
                $queueIds = array_key_exists('cb', $_GET) ? array_map('intval', $_GET['cb']) : array();
                WADA_Log::debug('process_bulk_action bulk delete queue entries '.print_r($queueIds, true));
                foreach($queueIds AS $queueId){
                    $this->deleteQueueEntry($queueId);
                }
                break;
            default:
                WADA_Log::debug('process_bulk_action (NotificationQueue): action: '.$action);
                break;
        }
    }

    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="bulk_entries[]" value="%s" />', absint($item['id'])
        );
    }

    protected function getUrlParametersActive($leadingAmpersand=true){
        $eventNotificationId = $this->getEventNotificationIdFromRequest();
        $notificationId = $this->getNotificationIdFromRequest();
        $eventId = $this->getEventIdFromRequest();

        $params = array();
        if($eventNotificationId>0) {
            $params['enid'] = $eventNotificationId;
        }
        if($notificationId>0) {
            $params['nid'] = $notificationId;
        }
        if($eventId>0) {
            $params['eid'] = $eventId;
        }
        return ($leadingAmpersand ? '&' : '').http_build_query($params);
    }

    function column_title($item) {
        $deleteNonce = wp_create_nonce( 'wada_notification_queue_deletion' );
        WADA_Log::debug('Queue item: '.print_r($item, true));
        $title = '#'.absint($item['id']);

        $params = $this->getUrlParametersActive();
        $deleteUrl = admin_url(sprintf(
                'admin.php?page=wp-admin-audit-notifications&subpage=queue&action=delete&id=%s&_wpnonce=%s',
                absint( $item['id'] ),
                $deleteNonce
        )).$params;

        $actions = array(
            'delete' => sprintf(
                    '<a href="'.$deleteUrl.'">%s</a>',
                __('Delete', 'wp-admin-audit')
            )
        );
        return esc_html($title) . $this->row_actions( $actions );
    }

    public function no_items() {
        echo '<p>';
        if ( ! empty( $_REQUEST['s'] ) ) {
            $s = esc_html(wp_unslash(sanitize_text_field($_REQUEST['s'])));
            printf( __('Nothing found for: %s', 'wp-admin-audit'), '<strong>' . $s . '</strong>' );
        } else {
            _e('No entries in queue', 'wp-admin-audit');
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
        if($eventNotificationId == 0 && $notificationId > 0){ // no matter if event filter is active or not (because event filter can be secondary filter while in notification mode)
            return 'notification';
        }
        if($eventNotificationId == 0 && $notificationId == 0 && $eventId > 0){
            return 'event';
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

    protected function getEventOptions(){
        $eventOptions = array();
        $eventOptions[0] = __('All events', 'wp-admin-audit');

        $notificationId = $this->getNotificationIdFromRequest();
        global $wpdb;
        $sql = 'SELECT DISTINCT ev.*, sen.name as sensor_name, sen.severity as sensor_severity'
            . ' FROM '.WADA_Database::tbl_event_notifications().' enoti'
            . ' LEFT JOIN '.WADA_Database::tbl_events().' ev ON (enoti.event_id = ev.id)'
            . ' LEFT JOIN '.WADA_Database::tbl_sensors().' sen ON (ev.sensor_id = sen.id)'
            . ' WHERE enoti.id IN (SELECT event_notification_id FROM '.WADA_Database::tbl_notification_queue_map().')'
            . (($notificationId > 0) ? ' AND enoti.notification_id=\''.intval($notificationId).'\'' : '')
            . ' ORDER BY ev.id ASC';
        $events = $wpdb->get_results($sql);
        if($events && count($events)){
            foreach($events AS $event){
                $occurredOn = WADA_DateUtils::formatUTCasDatetimeForWP($event->occurred_on);
                $eventOptions[$event->id] = '#'.$event->id.' '.$event->sensor_name.' / '.$occurredOn;
            }
        }

        return $eventOptions;
    }

    protected function getFilterControls($filterControls = array()){

        $eventFilter = new stdClass();
        $eventFilter->type = 'select';
        $eventFilter->value = $this->getEventIdFromRequest();
        $eventFilter->field = 'event_id';
        $eventFilter->label = null; // no need for label
        $eventFilter->selectOptions = $this->getEventOptions();

        if($this->viewMode !== 'event-notification') {
            $filterControls[] = $eventFilter;
        }

        return parent::getFilterControls($filterControls);
    }

    protected function getItemsQuery($searchTerm = null){
        $sql = "SELECT * FROM ".WADA_Database::tbl_notification_queue()." qu";

        $whereForEventNotification = array();
        $eventNotificationId = $this->getEventNotificationIdFromRequest();
        $notificationId = $this->getNotificationIdFromRequest();
        $eventId = $this->getEventIdFromRequest();

        if($eventNotificationId){
            $whereForEventNotification[] = 'id=\''.$eventNotificationId.'\'';
        }
        if($notificationId){
            $whereForEventNotification[] = 'notification_id=\''.$notificationId.'\'';
        }
        if($eventId){
            $whereForEventNotification[] = 'event_id=\''.$eventId.'\'';
        }
        if(count($whereForEventNotification)){
            $sql .= " WHERE qu.id IN ("
                        ." SELECT queue_id FROM " . WADA_Database::tbl_notification_queue_map()
                        ." WHERE event_notification_id IN ("
                            ." SELECT id FROM " .WADA_Database::tbl_event_notifications()
                            ." WHERE ". implode(' AND ', $whereForEventNotification)
                        .")"
                    .")";
        }

        if($searchTerm) {
            $fieldsToSearchIn = array('qu.channel_type', 'qu.email_address');
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
                let data = {
                    eid: eventId,
                    nid: thisListRef.__query(document.location.href, 'nid') || '',
                    enid: thisListRef.__query(document.location.href, 'enid') || ''
                };
                return data;
            }
            function <?php $this->jsPrefix(); ?>getAdditionalParametersFromUrl(thisListRef, query){
                let data = {
                    eid: thisListRef.__query(document.location.href, 'eid') || '',
                    nid: thisListRef.__query(document.location.href, 'nid') || '',
                    enid: thisListRef.__query(document.location.href, 'enid') || ''
                };
                return data;
            }
            function <?php $this->jsPrefix(); ?>registerAdditionalListEvents(thisListRef){
                if(jQuery('#event_id').length) {
                    jQuery('#event_id').on('change', {thisList: thisListRef}, function (e) {
                        let data = e.data.thisList.getQueryVariablesFromInputs();
                        e.data.thisList.update(data);
                    });
                }
                jQuery('#<?php $this->listFormId(); ?> .row-actions .delete a').click( function( event ) {
                    if( !confirm( '<?php echo esc_js(__('Are you sure you want to delete the queue entry?', 'wp-admin-audit')); ?>' ) ) {
                        event.preventDefault();
                    }
                });
            }
            function <?php $this->jsPrefix(); ?>unregisterAdditionalListEvents(thisListRef){
                if(jQuery('#event_id').length) {
                    jQuery('#event_id').off('change');
                }
                jQuery('#<?php $this->listFormId(); ?> .row-actions .delete a').off('click');
            }
            function <?php $this->jsPrefix(); ?>validationPreSubmitIsOkay(submitButtonId){
                if(submitButtonId == 'doaction'){
                    let action = jQuery('#bulk-action-selector-top').val();
                    if(action == '_wada_ajax_notification_queue_list_bulk_delete'){
                        let selectedCheckboxes = jQuery('input[name="bulk_entries[]"]:checked').map(function(){return jQuery(this).val();}).get();
                        if(selectedCheckboxes.length < 1){
                            alert('<?php echo esc_js(__('Please select at least one entry', 'wp-admin-audit')); ?>' );
                            return false;
                        }
                        return confirm( '<?php echo esc_js(__('Are you sure you want to delete the selected entries?', 'wp-admin-audit')); ?>' );
                    }
                }
                return true; // default = all okay, can submit
            }
        </script>
        <?php
        parent::loadJavascriptActions(); // make sure base class also loads JS
    }

}