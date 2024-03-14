<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_View_Notifications extends WADA_View_BaseList
{
    const VIEW_IDENTIFIER = 'wada-notifications';
    const LIST_FORM_ID = 'wada-notification-list-form';
    const AJAX_ACTION = '_wada_ajax_notifications_list';
    const NONCE_ACTION = 'wada-ajax-notifications-list-nonce';
    const NONCE_NAME = '_wada_ajax_notifications_list_nonce';

    public function __construct($viewConfig = array()) {
        $this->viewHeadline =  __('Notifications', 'wp-admin-audit');
        parent::__construct( array(
            'singular' => __( 'Notification', 'wp-admin-audit' ), //singular name of the grouped records
            'plural'   => __( 'Notifications', 'wp-admin-audit' ), //plural name of the grouped records
            'ajax'     => true // does this table support ajax?
        ), $viewConfig );
        add_action('admin_footer', array($this, 'loadJavascriptActions'));
    }

    function getAddNewPageUrl(){
        return admin_url( 'admin.php?page=wp-admin-audit-notifications&subpage=wizard' );
    }

    function get_columns() {
        $colArray = array(
            'title'    => __( 'Name', 'wp-admin-audit' ),
            'active'    => __( 'Active', 'wp-admin-audit' ),
            'sen_trigger_cr'    => __( '#Sensor triggers', 'wp-admin-audit' ),
            'sev_trigger_cr'    => __( '#Severity triggers', 'wp-admin-audit' ),
            'other_target_cr'   => __( '#Recipients', 'wp-admin-audit' ),
            'role_target_cr'    => __( 'Recipients (#roles)', 'wp-admin-audit' ),
            'id_only'    => __( 'ID', 'wp-admin-audit' )
        );
        $searchTerm = $this->getSearchTerm();
        if($searchTerm){
            $colArray['search_res'] = __( 'Search result', 'wp-admin-audit' );
        }
        return $colArray;
    }

    public function get_sortable_columns() {
        return array(
            'title' => array('name', 'asc'),
            'active' => array('active', 'desc'),
            'sen_trigger_cr' => array('sen_trigger_cr', 'asc'),
            'sev_trigger_cr' => array('sev_trigger_cr', 'asc'),
            'other_target_cr' => array('other_target_cr', 'asc'),
            'role_target_cr' => array('role_target_cr', 'asc'),
            'id_only' => array('id', 'asc')
        );
    }

    public function process_bulk_action() {
        switch ($this->current_action()) { //Detect when a bulk action is being triggered...
            case 'delete':
                $nonce = sanitize_text_field( $_REQUEST['_wpnonce'] );
                if ( ! wp_verify_nonce( $nonce, 'wada_notification_deletion' ) ) {
                    die( 'Stop CSRF!' );
                } else {
                    $this->deleteNotification( absint( $_GET['id'] ) );
                }
                break;
            default:
                // code...
                break;
        }
    }

    protected function deleteNotification($id){
        WADA_Log::debug('deleteNotification ID '.$id);
        $model = new WADA_Model_Notification($id);
        $model->delete();
    }

    function column_title($item) {
        $nonce = wp_create_nonce( 'wada_notification_view' );
        $deleteNonce = wp_create_nonce( 'wada_notification_deletion' );
        $editUrl = admin_url(sprintf(
                'admin.php?page=wp-admin-audit-notifications&subpage=wizard&sid=%s&amp;_wpnonce=%s',
                absint( $item['id'] ),
                $nonce
        ));
        $deleteUrl = admin_url(sprintf(
            'admin.php?page=wp-admin-audit-notifications&action=delete&id=%s&_wpnonce=%s',
            absint( $item['id'] ),
            $deleteNonce
        ));
        $logUrl = admin_url(sprintf(
            'admin.php?page=wp-admin-audit-notifications&subpage=log&nid=%s',
            absint( $item['id'] )
        ));
        $title = sprintf(
                '<a href="'.$editUrl.'"><strong>%s</strong></a>',
                $this->formatSearchResult(esc_html($item['name']), $this->getSearchTerm())
        );

        $actions = array(
            'edit' => sprintf(
                '<a href="'.$editUrl.'">%s</a>',
                __('Edit', 'wp-admin-audit')
            ),
            'delete' => sprintf(
                '<a href="'.$deleteUrl.'">%s</a>',
                __('Delete', 'wp-admin-audit')
            ),
            'log' => sprintf(
                    '<a href="'.$logUrl.'">%s</a>',
                    __('View log', 'wp-admin-audit')
            )
        );
        if(array_key_exists('nr_queue_entries', $item) && $item['nr_queue_entries'] > 0){
            $queueUrl = admin_url(sprintf(
                'admin.php?page=wp-admin-audit-notifications&subpage=queue&nid=%s',
                absint( $item['id'] )
            ));
            $actions['queue'] = sprintf(
                    '<a href="'.$queueUrl.'">%s</a>',
                    __('View queue', 'wp-admin-audit')
            );
        }
        return $title . $this->row_actions( $actions );
    }

    function column_active($item){
        return '<input type="checkbox" class="wada-ui-toggle sensor-status-toggle" '
            .'id="active'.absint($item['id']).'" name="active'.absint($item['id']).'" value="1" '
            .($item['active'] == 1 ? 'checked' : '').' >';
    }

    function toggleActionAjaxResponse(){
        WADA_Log::debug(static::VIEW_IDENTIFIER.'->toggleActionAjaxResponse');
        check_ajax_referer(static::NONCE_ACTION, static::NONCE_NAME);

        $id = isset( $_REQUEST['id'] ) ? absint( $_REQUEST['id'] ) : 0;
        $active = !isset($_REQUEST['active']) || $_REQUEST['active'] == 'true';
        if($id){
            $res = WADA_Model_Notification::setActiveStatus($id, $active);
            $response = array('success' => $res);
        }else{
            $response = array('success' => false, 'error' => 'No ID found');
        }

        WADA_Log::debug(static::VIEW_IDENTIFIER.'->toggleActionAjaxResponse: '.print_r($response, true));

        die( json_encode( $response ) );
    }

    protected function handleFormSubmissions(){
        if(isset($_POST['submit'])){
            check_admin_referer(self::VIEW_IDENTIFIER);
        }
    }

    protected function performAdditionalItemPreparation(){
        foreach($this->items AS $key => $item){
            $this->items[$key]['nr_queue_entries'] = WADA_Notification_Queue::getNrOfQueueEntries(0, 0, $this->items[$key]['id']);
        }
        return $this->items;
    }

    protected function getItemsQuery($searchTerm = null){
        global $wpdb;
        $sql = "SELECT noti.id, noti.active, noti.name, "
                ."IFNULL(tg_role.role_target_cr, 0) AS role_target_cr, "
                ."IFNULL(tg_other.other_target_cr, 0) AS other_target_cr, "
                ."IFNULL(sev_tr.sev_trigger_cr, 0) AS sev_trigger_cr, "
                ."IFNULL(sen_tr.sen_trigger_cr, 0) AS sen_trigger_cr "
                .($searchTerm ? ", ei_search_res.search_res as search_res " : ", NULL AS search_res ")
                ."FROM ".WADA_Database::tbl_notifications() . " noti "
                ."LEFT JOIN (SELECT notification_id, COUNT(*) AS role_target_cr FROM ".WADA_Database::tbl_notification_targets()." WHERE target_type = 'wp_role' GROUP BY notification_id) tg_role ON ( noti.id = tg_role.notification_id ) "
                ."LEFT JOIN (SELECT notification_id, COUNT(*) AS other_target_cr FROM ".WADA_Database::tbl_notification_targets()." WHERE target_type <> 'wp_role' GROUP BY notification_id) tg_other ON ( noti.id = tg_other.notification_id ) "
                ."LEFT JOIN (SELECT notification_id, COUNT(*) AS sev_trigger_cr FROM ".WADA_Database::tbl_notification_triggers()." WHERE trigger_type = 'severity' GROUP BY notification_id) sev_tr ON ( noti.id = sev_tr.notification_id ) "
                ."LEFT JOIN (SELECT notification_id, COUNT(*) AS sen_trigger_cr FROM ".WADA_Database::tbl_notification_triggers()." WHERE trigger_type = 'sensor' GROUP BY notification_id) sen_tr ON ( noti.id = sen_tr.notification_id ) "
        ;
        
        if($searchTerm) {
            $sql .=
            "LEFT JOIN ("
            . "SELECT notification_id, GROUP_CONCAT(DISTINCT search_res ORDER BY search_res SEPARATOR ', ') AS search_res "
            . "FROM ( "
            . "SELECT notification_id, CONVERT(CONCAT('Email',': ',target_str_id) USING utf8mb4) AS search_res FROM ".WADA_Database::tbl_notification_targets()." WHERE target_type = 'email' and channel_type = 'email' and target_str_id LIKE '%" . $searchTerm . "%' "
            . "UNION SELECT notification_id, CONVERT(CONCAT('Role',': ',target_str_id) USING utf8mb4) AS search_res FROM ".WADA_Database::tbl_notification_targets()." WHERE target_type = 'wp_role' and channel_type = 'email' and target_str_id LIKE '%" . $searchTerm . "%' "
            . "UNION SELECT notification_id, search_res FROM ("
            . "     SELECT usr_targets.notification_id, CONVERT(CONCAT('User',': ','#' ,wpusr.ID, ' / ', wpusr.user_login, ' / ', wpusr.user_email, ' / ', wpusr.display_name) USING utf8mb4) AS search_res"
            . "     FROM (select * FROM ".WADA_Database::tbl_notification_targets()." WHERE target_type = 'wp_user') usr_targets"
            . "     LEFT JOIN ".$wpdb->prefix."users wpusr ON (usr_targets.target_id = wpusr.ID)"
            . "     WHERE (wpusr.user_login LIKE '%" . $searchTerm . "%' OR wpusr.user_email LIKE '%" . $searchTerm . "%' OR wpusr.display_name LIKE '%" . $searchTerm . "%' )"
            . ") usrs"
            . ") ei_res  "
            . "GROUP BY notification_id "
            . ") ei_search_res ON (noti.id = ei_search_res.notification_id)";

            $fieldsToSearchIn = array('noti.name');
            $sep = " LIKE '%".$searchTerm."%') OR (";
            $whereCond = implode($sep, $fieldsToSearchIn);
            $whereCond .= " LIKE '%".$searchTerm."%')";
            $whereCond .= " OR ei_search_res.notification_id IS NOT NULL )";
            $sql .= ' WHERE ( ('.$whereCond;
        }

        return $sql;
    }

    public function displayComingSoon(){
        $pluginSlug = basename(realpath(__DIR__.'/../../'));
        $assetsUrl = trailingslashit( plugins_url($pluginSlug) );
    ?>
        <div class="wrap">
            <h1><?php _e('Notifications', 'wp-admin-audit'); ?></h1>
            <form id="<?php echo self::VIEW_IDENTIFIER; ?>" method="post">
                <?php wp_nonce_field(self::VIEW_IDENTIFIER); ?>
                <input type="hidden" name="page" value="<?php echo esc_attr($this->getCurrentPage()); ?>" />
                <div>
                    <div>
                        <h3><?php _e('A powerful notification system', 'wp-admin-audit'); ?>
                            <span class="available-in-pro-edition"><?php echo ' &mdash; '.'<a href="https://wpadminaudit.com/pricing?utm_source=wada-plg&utm_medium=referral&utm_campaign=available-pro&utm_content=noti" target="_blank">'.sprintf(__('Available in %s', 'wp-admin-audit'), WADA_Version::getMinV4Ft(WADA_Version::FT_ID_NOTI)).'</a>'; ?></span>
                        </h3>
                        <ul class="wada-ul">
                            <li><?php _e('get immediately notified about security relevant activities', 'wp-admin-audit'); ?></li>
                            <li><?php _e('switch on / off notifications for certain sensors and/or certain admins', 'wp-admin-audit'); ?></li>
                            <li><?php _e('choose your preferred channel between email & text messages', 'wp-admin-audit'); ?></li>
                        </ul>
                    </div>
                    <div>
                        <ul class="wada-ul-nostyle wada-ul-big-gaps wada-striped-list wada-center wada-coming-soon-notification">
                            <li><img src="<?php echo ($assetsUrl.'assets/img/screens/notification_add_step01.png'); ?>" style="max-width:600px" /></li>
                            <li><img src="<?php echo ($assetsUrl.'assets/img/screens/notification_add_step02.png'); ?>" style="max-width:600px" /></li>
                            <li><img src="<?php echo ($assetsUrl.'assets/img/screens/notification_add_step03.png'); ?>" style="max-width:600px" /></li>
                            <li><img src="<?php echo ($assetsUrl.'assets/img/screens/notification_add_step04.png'); ?>" style="max-width:600px" /></li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
    <?php
    }


    function loadJavascriptActions(){ // override with extending functionality ?>
        <script type="text/javascript">
            function <?php $this->jsPrefix(); ?>registerAdditionalListEvents(){
                jQuery('#<?php $this->listFormId(); ?> .sensor-status-toggle').on('change', function (e) {
                    let id = parseInt(this.name.substr(6)); // taking name and stripping 'active' prefix
                    let active = jQuery(this).prop('checked');
                    if(id > 0){
                        toggleNotificationStatus({'id':id, 'active':active});
                    }
                });
                jQuery('#<?php $this->listFormId(); ?> .row-actions .delete a').click( function( event ) {
                    if( !confirm( '<?php echo esc_js(__('Are you sure you want to delete the notification?', 'wp-admin-audit')); ?>' ) ) {
                        event.preventDefault();
                    }
                });
            }
            function <?php $this->jsPrefix(); ?>unregisterAdditionalListEvents(){
                jQuery('#sensor').off('change');
                jQuery('#<?php $this->listFormId(); ?> .row-actions .delete a').off('click');
            }
            function toggleNotificationStatus(data){
                jQuery.ajax({
                        url: ajaxurl,
                        data: jQuery.extend({
                    <?php echo static::NONCE_NAME; ?>: jQuery('#<?php echo static::NONCE_NAME; ?>').val(),
                        action: '_wada_ajax_notifications_status_toggle'
                }, data),
                    success: function (response) {
                        var response = jQuery.parseJSON(response);
                        if(response && response.success){
                            console.log(data);
                        }else{
                            jQuery('#active'+data.id).prop('checked', !data.active); // something went wrong saving, revert UI
                        }
                    }
                });
            }
        </script>
        <?php
        parent::loadJavascriptActions(); // make sure base class also loads JS
    }

}