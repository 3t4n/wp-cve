<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_View_Diagnosis extends WADA_View_BaseForm
{
    const VIEW_IDENTIFIER = 'wada-diagnosis';

    public function __construct() {
        add_action('admin_footer', array($this, 'loadJavascriptActions'));
    }

    protected function handleFormSubmissions(){
        if(isset($_POST['submit'])){
            check_admin_referer(self::VIEW_IDENTIFIER);
        }
    }

    protected function displayForm(){
        global $wpdb, $wp_version;
        $allSettings = WADA_Settings::getAllSettings(true);
        $extensions = WADA_Extensions::getAllExtensions();
        $debugUrlFlag = array_key_exists('wada-dbg', $_GET);
    ?>
        <div class="wrap">
            <h1><?php _e('System diagnosis', 'wp-admin-audit'); ?></h1>
            <form id="<?php echo self::VIEW_IDENTIFIER; ?>" method="post">
                <?php wp_nonce_field(self::VIEW_IDENTIFIER); ?>
                <input type="hidden" name="page" value="<?php echo esc_attr($this->getCurrentPage()); ?>" />
                <input type="hidden" id="wada-keyaction-nonce" value="<?php echo wp_create_nonce('wada_keyaction'); ?>" />
                <input type="hidden" id="wada-diagnosis-stats-nonce" value="<?php echo wp_create_nonce('wada_diagnosis_stats'); ?>" />

                <table class="form-table wada-system-diagnosis">
                    <tbody>
                    <tr>
                        <th><?php echo WADA_Version::getProductName(); ?></th>
                        <td><?php echo WADA_Version::getProductVersion(true); ?> (DB: <?php echo WADA_Settings::getDatabaseVersion(); ?>)</td>
                    </tr>
                    <?php
                    /*  */
                    ?>

                    <tr>
                        <th><?php _e('Site', 'wp-admin-audit'); ?></th>
                        <td><?php echo get_site_url(); ?></td>
                    </tr>
                    <tr>
                        <th><?php _e('Operating system', 'wp-admin-audit'); ?></th>
                        <td><?php echo php_uname(); ?></td>
                    </tr>
                    <tr>
                        <th><?php _e('Database version', 'wp-admin-audit'); ?></th>
                        <td><?php echo esc_html($wpdb->db_version()); ?></td>
                    </tr>
                    <tr>
                        <th><?php _e('Database prefix', 'wp-admin-audit'); ?></th>
                        <td><?php echo esc_html($wpdb->prefix); ?></td>
                    </tr>
                    <tr>
                        <th><?php _e('Database collation', 'wp-admin-audit'); ?></th>
                        <td><?php echo esc_html($wpdb->get_charset_collate()); ?><br/>
                        <?php echo $wpdb->prefix.'users'.'.user_email: '.WADA_Database::getCollation($wpdb->prefix.'users', 'user_email') ?><br/>
                        <?php echo $wpdb->prefix.'wada_events'.'.user_email: '.WADA_Database::getCollation($wpdb->prefix.'wada_events', 'user_email') ?></td>
                    </tr>
                    <tr>
                        <th><?php _e('WordPress version', 'wp-admin-audit'); ?></th>
                        <td><?php echo esc_html($wp_version); ?></td>
                    </tr>
                    <tr>
                        <th><?php _e('PHP version', 'wp-admin-audit'); ?></th>
                        <td><?php echo phpversion(); ?></td>
                    </tr>
                    <tr>
                        <th><?php _e('PHP max. execution time', 'wp-admin-audit'); ?></th>
                        <td><?php echo ini_get('max_execution_time'); ?></td>
                    </tr>
                    <tr>
                        <th><?php _e('PHP memory limit', 'wp-admin-audit'); ?></th>
                        <td><?php echo ini_get('memory_limit'); ?></td>
                    </tr>
                    <tr>
                        <th><?php _e('PHP disabled functions', 'wp-admin-audit'); ?></th>
                        <td><?php
                            $disabledFunctions = ini_get('disable_functions');
                            if ($disabledFunctions!=''){
                                $dfArr = explode(',', $disabledFunctions);
                                sort($dfArr);
                                echo implode(', ', $dfArr);
                            }else{
                                _e('None', 'wp-admin-audit');
                            } ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Timezone', 'wp-admin-audit'); ?></th>
                        <td><?php
                            try {
                                $nowTime = new DateTime('now');
                                $timezoneTime = new DateTime('now', wp_timezone());
                                echo esc_html($timezoneTime->format('Y-m-d H:i:s') . ' ('.wp_timezone()->getName().')').'<br/>'.esc_html($nowTime->format('Y-m-d H:i:s') . ' (UTC)');
                            } catch (Exception $e) {
                                _e('Error', 'wp-admin-audit').': '.esc_html($e->getMessage());
                            }?>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Log file', 'wp-admin-audit'); ?></th>
                        <td><?php echo WADA_Log::getLogFile() .'<br/>'.WADA_FileUtils::getFileSizeOfFile(WADA_Log::getLogFile()); ?><br/><?php
                            $dwlLogNonce = wp_create_nonce('wada_dwl_log');
                            $preLogNonce = wp_create_nonce('wada_pre_log');
                            $delLogNonce = wp_create_nonce('wada_del_log');
                            $discoverInstNonce = wp_create_nonce('wada_sensor_discover_install');
                            ?>
                            <div style="margin-top:10px;"><span id="downloadLog" class="wada-link"><?php _e( 'Download log', 'wp-admin-audit' );?></span></div>
                            <input type="hidden" id="wada-dwl-nonce" value="<?php echo $dwlLogNonce; ?>" />
                            <input type="hidden" id="wada-pre-nonce" value="<?php echo $preLogNonce; ?>" />
                            <input type="hidden" id="wada-del-nonce" value="<?php echo $delLogNonce; ?>" />
                            <input type="hidden" id="wada-discover-install-nonce" value="<?php echo $discoverInstNonce; ?>" />
                            <div style="margin-top:10px;"><span id="previewLog" class="wada-link"><?php _e( 'Preview log file', 'wp-admin-audit' );?></span></div>
                            <div style="margin-top:10px;"><span id="deleteLog" class="wada-link"><?php _e( 'Delete log file', 'wp-admin-audit' );?></span></div>
                        </td>
                    </tr>
                    <?php if(WADA_Version::getFtSetting(WADA_Version::FT_ID_NOTI) && WADA_Notification_Queue::getNextUnprocessedEvent()): ?>
                    <tr>
                        <th><?php _e('Queue', 'wp-admin-audit'); ?></th>
                        <td><?php
                            $processQueueNonce = wp_create_nonce('wada_process_queue');
                            ?>
                            <input type="hidden" id="wada-process-queue-nonce" value="<?php echo $processQueueNonce; ?>" />
                            <div style="margin-top:10px;"><span id="processQueue" class="wada-link"><?php _e( 'Process queue', 'wp-admin-audit' );?></span></div>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th><?php _e('Events', 'wp-admin-audit'); ?></th>
                        <td class="event-log-stats-row">
                            <span class="spinner event-log-stats-spinner ajax-progress-spinner" style="display: none;"></span>
                            <div id="event-log-stats" style="display:none;">
                                <table class="form-table wada-small-table">
                                    <tr id="event-log-stats-total-row"><td><?php _e('#Events (total)', 'wp-admin-audit'); ?></td><td><span id="event-log-stats-total"></span></td></tr>
                                    <tr id="event-log-stats-top5-sensors-row"><td style="width:200px;"><?php _e('Top 5 event types', 'wp-admin-audit'); ?></td><td>
                                            <ul id="event-log-stats-top5-sensors">
                                                <li class="top5-0"></li>
                                                <li class="top5-1"></li>
                                                <li class="top5-2"></li>
                                                <li class="top5-3"></li>
                                                <li class="top5-4"></li>
                                            </ul>
                                        </td></tr>
                                    <tr id="event-log-stats-1d-row"><td><?php _e('#Events (one day)', 'wp-admin-audit'); ?></td><td><span id="event-log-stats-1d"></span></td></tr>
                                    <tr id="event-log-stats-7d-row"><td><?php _e('#Events (7 days)', 'wp-admin-audit'); ?></td><td><span id="event-log-stats-7d"></span></td></tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Sensors', 'wp-admin-audit'); ?></th>
                        <td><div><?php
                            $statsModel = new WADA_Model_Stats();
                            $sensorStats = $statsModel->getSensorCounts();
                            ?>
                            <table>
                                <tr>
                                    <td><?php _e('Total', 'wp-admin-audit'); ?></td>
                                    <td><?php echo $sensorStats->totalSensors; ?></td>
                                </tr>
                                <tr>
                                    <td><?php _e('Active', 'wp-admin-audit'); ?></td>
                                    <td><?php echo $sensorStats->bySensorStatus->active; ?></td>
                                </tr>
                                <tr>
                                    <td><?php _e('Inactive', 'wp-admin-audit'); ?></td>
                                    <td><?php echo $sensorStats->bySensorStatus->inactive; ?></td>
                                </tr>
                            </table><?php
                             ?>
                            </div>
                            <div style="margin-top:10px;"><span id="discoverInstallSensors" class="wada-link"><?php _e( 'Discover & install sensors', 'wp-admin-audit' );?></span></div>
                        </td>
                    </tr>
                    <?php
                    if($debugUrlFlag){?>
                        <tr>
                            <th><?php _e( 'Miscellaneous', 'wp-admin-audit' ); echo ' / '; _e( 'Debug', 'wp-admin-audit' ); ?></th>
                            <td><?php
                                $debugActionNonce = wp_create_nonce('wada_debug_action');
                                ?>
                                <div style="margin-top:10px;"><span id="optionActionCreate" class="wada-link wadaDebugAction" data-debug-action="create-option">OPTION ACTION - Create</span></div>
                                <div style="margin-top:10px;"><span id="optionActionUpdate" class="wada-link wadaDebugAction" data-debug-action="update-option">OPTION ACTION - Update</span></div>
                                <div style="margin-top:10px;"><span id="optionActionDelete" class="wada-link wadaDebugAction" data-debug-action="delete-option">OPTION ACTION - Delete</span></div>
                                <input type="hidden" id="wada-debug-action-nonce" value="<?php echo $debugActionNonce; ?>" />
                            </td>
                        </tr>
                    <?php
                    }?>
                    <tr>
                        <th><?php _e( 'Settings', 'wp-admin-audit' ); ?></th>
                        <td>
                            <table><?php foreach($allSettings AS $key => $obj){ ?>
                                <tr>
                                    <td><?php echo esc_html($key); ?></td>
                                    <td><?php echo esc_html($obj->niceValue); ?></td>
                                </tr><?php
                            }?>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e( 'Extensions', 'wp-admin-audit' ); ?></th>
                        <td>
                            <table>
                                <tr>
                                    <th><?php _e('ID', 'wp-admin-audit'); ?></th>
                                    <th><?php _e('Name', 'wp-admin-audit'); ?></th>
                                    <th><?php _e('Active', 'wp-admin-audit'); ?></th>
                                    <th><?php _e('Folder', 'wp-admin-audit'); ?></th>
                                </tr>
                                <?php foreach($extensions AS $extension){ ?>
                                <tr>
                                    <td><?php echo esc_html($extension->id); ?></td>
                                    <td><?php echo esc_html($extension->name); ?></td>
                                    <td><?php echo esc_html($extension->active); ?></td>
                                    <td><?php echo esc_html($extension->plugin_folder); ?></td>
                                </tr><?php
                                }?>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </div>
    <?php
    }

    function loadJavascriptActions(){ ?>
        <script type="text/javascript">
            (function ($) {
                $('#downloadLog').on('click', function (e) {
                    e.preventDefault();
                    let data = {};
                    jQuery.ajax({
                        url: ajaxurl,
                        data: jQuery.extend({
                            _wpnonce: jQuery('#wada-dwl-nonce').val(),
                            action: '_wada_ajax_download_log'
                        }, data),
                        success: function(data) {
                            // Make log file downloadable
                            var downloadLink = document.createElement("a");
                            var fileData = ['\ufeff'+data];

                            var blobObject = new Blob(fileData,{
                                type: "text/plain;charset=utf-8;"
                            });

                            var url = URL.createObjectURL(blobObject);
                            downloadLink.href = url;
                            let currDate = new Date();
                            downloadLink.download = "wada-"+currDate.toISOString().split('T')[0]+"-"+currDate.getHours()+currDate.getMinutes()+currDate.getSeconds()+".log";

                            // Actually download CSV
                            document.body.appendChild(downloadLink);
                            downloadLink.click();
                            document.body.removeChild(downloadLink);
                        }
                    });
                });

                $('#deleteLog').on('click', function (e) {
                    e.preventDefault();
                    if( !confirm( '<?php echo esc_js(__('Are you sure you want to delete the log file contents?', 'wp-admin-audit')); ?>' ) ) {
                        return false;
                    }
                    let data = {};
                    jQuery.ajax({
                        url: ajaxurl,
                        data: jQuery.extend({
                            _wpnonce: jQuery('#wada-del-nonce').val(),
                            action: '_wada_ajax_delete_log'
                        }, data),
                        success: function (response) {
                            var resp = jQuery.parseJSON(response);
                            if(resp && resp.success){
                                alert(resp.msg);
                            }else{
                                console.log(response);
                                console.log(resp);
                            }
                        }
                    });
                });

                $('#previewLog').on('click', function (e) {
                    e.preventDefault();
                    let data = {};
                    jQuery.ajax({
                        url: ajaxurl,
                        data: jQuery.extend({
                            _wpnonce: jQuery('#wada-pre-nonce').val(),
                            action: '_wada_ajax_preview_log',
                            nr_lines: 100
                        }, data),
                        success: function (response) {
                            console.log(response);
                            var resp = jQuery.parseJSON(response);
                            console.log(resp);
                        }
                    });
                });

                $('#processQueue').on('click', function (e) {
                    e.preventDefault();
                    let data = {};
                    jQuery.ajax({
                        url: ajaxurl,
                        data: jQuery.extend({
                            _wpnonce: jQuery('#wada-process-queue-nonce').val(),
                            action: '_wada_ajax_process_queue'
                        }, data),
                        success: function (response) {
                            var resp = jQuery.parseJSON(response);
                            if(resp && resp.success){
                                alert(resp.msg);
                            }else{
                                console.log(response);
                                console.log(resp);
                            }
                        }
                    });
                });

                $('#discoverInstallSensors').on('click', function (e) {
                    e.preventDefault();
                    let data = {};
                    jQuery.ajax({
                        url: ajaxurl,
                        data: jQuery.extend({
                            _wpnonce: jQuery('#wada-discover-install-nonce').val(),
                            action: '_wada_ajax_discover_install_sensors'
                        }, data),
                        success: function (response) {
                            var resp = jQuery.parseJSON(response);
                            if(resp && resp.success){
                                alert(resp.msg);
                            }else{
                                console.log(response);
                                console.log(resp);
                            }
                        }
                    });
                });

                $('.wadaDebugAction').on('click', function (e) {
                    e.preventDefault();
                    console.log(e.target);
                    let debugAction = jQuery(e.target).data('debugAction');
                    console.log(debugAction);
                    let data = {};
                    jQuery.ajax({
                        url: ajaxurl,
                        url: ajaxurl,
                        data: jQuery.extend({
                            _wpnonce: jQuery('#wada-debug-action-nonce').val(),
                            action: '_wada_ajax_debug_action',
                            debugAction: debugAction
                        }, data),
                        success: function (response) {
                            var resp = jQuery.parseJSON(response);
                            if(resp && resp.success){
                                alert(resp.msg);
                            }else{
                                console.log(response);
                                console.log(resp);
                            }
                        }
                    });
                });


                function processLicenseKeyCheckResults(response){
                    $('.license-spinner').hide().removeClass('is-active');
                    console.log(response);
                    let data = jQuery.parseJSON(response);
                    console.log(data);
                    if(data){
                        $('#check-license-status').hide();
                        if(data.error){
                            $('#license-message').html(data.message+'<br/>'+data.error);
                        }else{
                            $('#license-message').html(data.message);
                        }

                        if(data.license){
                            $('#license-details').show();
                            let licenseStatus = data.license.licenseStatus;
                            $('#license-detail-status').removeClass('wada-green-highlight').removeClass('wada-orange-highlight');
                            if(licenseStatus){
                                if(licenseStatus == 'active'){
                                    licenseStatus = '<?php echo(esc_js(__('Active', 'wp-admin-audit'))); ?>';
                                    $('#license-detail-status').addClass('wada-green-highlight');
                                }else if(licenseStatus == 'expired'){
                                    licenseStatus = '<?php echo(esc_js(__('Expired', 'wp-admin-audit'))); ?>';
                                    $('#license-detail-status').addClass('wada-orange-highlight');
                                }
                            }else{
                                licenseStatus = '<?php echo(esc_js(__('Invalid', 'wp-admin-audit'))); ?>';
                                $('#license-detail-status').addClass('wada-orange-highlight');
                            }
                            $('#license-detail-status').html(licenseStatus);
                            if(data.license.licenseStatus){
                                let color = 'grey';
                                let boldRule = '';
                                if(data.license.daysLeft <= 30){
                                    color = 'orange';
                                    boldRule = 'font-weight:bold;'
                                }
                                if(data.license.daysLeft <= 10){
                                    color = 'red';
                                }

                                let expiresOnText = data.license.expiresOn+' <span style="color:'+color+';'+boldRule+'">('+data.license.daysLeftText+')</span>';

                                let isExpiredText = '<?php echo(esc_js(__('No', 'wp-admin-audit'))); ?>';
                                $('#license-detail-expired').removeClass('wada-error');
                                if(data.license.isExpired){
                                    isExpiredText = '<?php echo(esc_js(__('Yes', 'wp-admin-audit'))); ?>';
                                    $('#license-detail-expired').addClass('wada-error');
                                }

                                $('#license-detail-instance').removeClass('wada-license-instance-test').removeClass('wada-license-instance-prod');
                                let instanceDetailHtml = '';
                                if(data.license.isTestInstall === 1){
                                    $('#license-detail-instance').addClass('wada-license-instance-test');
                                    instanceDetailHtml = '<?php echo(esc_js(__('Test system', 'wp-admin-audit'))); ?>';
                                    if(data.license.mainInstall){
                                        instanceDetailHtml += ' (<?php echo(esc_js(__('Production system', 'wp-admin-audit'))); ?>: ';
                                        instanceDetailHtml += data.license.mainInstall;
                                        instanceDetailHtml += ')';
                                    }
                                }else{
                                    $('#license-detail-instance').addClass('wada-license-instance-prod');
                                    instanceDetailHtml = '<?php echo(esc_js(__('Production system', 'wp-admin-audit'))); ?>';
                                }
                                $('#license-detail-instance').html(instanceDetailHtml);

                                $('#license-detail-instance-row').show();
                                $('#license-detail-expired').html(isExpiredText);
                                $('#license-detail-valid-from').html(data.license.validFrom);
                                $('#license-detail-expires-on').html(expiresOnText);
                                $('#license-detail-expired-row').show();
                                $('#license-detail-valid-from-row').show();
                                $('#license-detail-expires-on-row').show();
                            }else{
                                $('#license-detail-instance-row').hide();
                                $('#license-detail-expired-row').hide();
                                $('#license-detail-valid-from-row').hide();
                                $('#license-detail-expires-on-row').hide();
                            }
                        }
                    }
                }

                function doLicenseStatusCheck(){
                    $('.license-spinner').addClass('is-active').show();
                    jQuery.ajax({
                        url: ajaxurl,
                        data: {
                            _wpnonce: jQuery('#wada-keyaction-nonce').val(),
                            action: '_wada_ajax_check_key_status'
                        },
                        success: processLicenseKeyCheckResults
                    });
                }

                function processEventLogStatResults(response) {
                    $('.event-log-stats-spinner').hide().removeClass('is-active');
                    console.log(response);
                    let data = jQuery.parseJSON(response);
                    console.log(data);
                    if (data) {
                        $('#event-log-stats-total').html(data.event_counts.totalEvents);
                        data.top_five.forEach(function(topEl, index){
                            $('#event-log-stats-top5-sensors li.top5-'+index).html(topEl.nr_events + ' (' + topEl.sensor_name + ', ID ' + topEl.sensor_id + ')');
                        });
                        $('#event-log-stats-1d').html(data.event_last_1d);
                        $('#event-log-stats-7d').html(data.event_last_7d);
                        $('#event-log-stats').show();

                    }
                }

                function loadEventLogStats(){
                    $('.event-log-stats-spinner').addClass('is-active').show();
                    jQuery.ajax({
                        url: ajaxurl,
                        data: {
                            _wpnonce: jQuery('#wada-diagnosis-stats-nonce').val(),
                            action: '_wada_ajax_get_event_log_stats'
                        },
                        success: processEventLogStatResults
                    });
                }

                loadEventLogStats(); // on init

                <?php

                /*  */
                ?>

            })(jQuery);
        </script>
        <?php
    }



    function deleteLogAjaxResponse(){
        WADA_Log::debug('deleteLogAjaxResponse');
        check_ajax_referer('wada_del_log');

        $logFile = WADA_Log::getLogFile();

        $res = false;
        if(unlink($logFile)){
            $msg =  __( 'Deleted log file', 'wp-admin-audit' );
            $res = true;
        }else{
            $msg = __( 'Failed to delete log file', 'wp-admin-audit' );
        }
        try{
            WADA_Log::initFile();
        }catch(RuntimeException $e){
            /// no action here
        }

        $response = array('success' => $res, 'msg' => $msg);

        die( json_encode( $response ) );
    }

    function downloadLogAjaxRespsonse(){
        if(!check_admin_referer('wada_dwl_log')){
            die('Not allowed to download attachment');
        }
        $filePath = WADA_Log::getLogFile();
        $fileName = 'wada.log';

        // Throw away any output sent up until this point
        ob_end_clean();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . rawurldecode( $fileName ) . '"' );
        header('Content-Transfer-Encoding: binary' );
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0' );
        header('Pragma: public' );
        header('Content-Length: ' . filesize( $filePath ) );
        $out = fopen( 'php://output', 'w' );
        readfile( $filePath, false, $out );
        die();
    }

    function previewLogAjaxRespsonse(){
        if(!check_admin_referer('wada_pre_log')){
            die('Not allowed to preview log');
        }
        $res = false;
        $nrLines = array_key_exists('nr_lines', $_GET) ? absint($_GET['nr_lines']) : 100;
        if($nrLines > 10000){
            $nrLines = 10000;
        }
        $lastLines = WADA_Log::tailFromFile($nrLines);
        if($lastLines){
            $res = true;
        }

        $response = array('success' => $res, 'lines' => $lastLines);
        die( json_encode( $response ) );
    }

    function debugActionResponse(){
        WADA_Log::debug('debugActionResponse');
        check_ajax_referer('wada_debug_action');

        $res = null;
        $debugAction = isset( $_REQUEST['debugAction'] ) ? sanitize_text_field($_REQUEST['debugAction']) : null;
        $msg = $debugAction;
        switch($debugAction){
            case 'create-option':
                $res = add_option('wada_debug_test_option', '10');
                $msg = $debugAction.', '.get_option('wada_debug_test_option');
                break;
            case 'update-option':
                $oldValue = absint(get_option('wada_debug_test_option', '10'));
                $res = update_option('wada_debug_test_option', ++$oldValue);
                $msg = $debugAction.', '.get_option('wada_debug_test_option');
                break;
            case 'delete-option':
                $res = delete_option('wada_debug_test_option');
                $msg = $debugAction.', '.get_option('wada_debug_test_option', 'NONE');
                break;
        }

        $response = array('success' => $res, 'msg' => $msg);

        die( json_encode( $response ) );
    }

    function getEventLogStatsAjaxResponse(){
        WADA_Log::debug('debugActionResponse');
        check_ajax_referer('wada_diagnosis_stats');

        $statsModel = new WADA_Model_Stats();
        $eventCounts = $statsModel->getEventCounts();
        $topFiveEventTypes = $statsModel->getTopEventTypes(5);
        $nrEventsLast1d = $statsModel->getNrEventsOfLastXDays(1);
        $nrEventsLast7d = $statsModel->getNrEventsOfLastXDays(7);

        $response = array('success' => true,
            'event_counts' => $eventCounts,
            'top_five' => $topFiveEventTypes,
            'event_last_1d' => $nrEventsLast1d,
            'event_last_7d' => $nrEventsLast7d
        );

        die( json_encode( $response ) );
    }

    function discoverInstallSensorsAjaxResponse(){
        WADA_Log::debug('discoverInstallSensorsAjaxResponse');
        check_ajax_referer('wada_sensor_discover_install');

        require_once realpath(__DIR__.'/..').'/Setup.php';
        $wadaSetup = new WADA_Setup();
        $nrSensorsAdded = $wadaSetup->setupSensorsIfNeeded();
        WADA_Log::debug('discoverInstallSensorsAjaxResponse added (core): '.$nrSensorsAdded);

        $extensions = WADA_Extensions::getAllExtensions(true);
        $extensionIds = array();
        foreach($extensions AS $extension){
            $extensionIds[$extension->plugin_folder] = $extension->id;
        }

        $extensionSensorInventory = apply_filters( 'wp_admin_audit_collect_extension_sensor_inventory', array(), $extensionIds );
        WADA_Log::debug('discoverInstallSensorsAjaxResponse extensionSensorInventory: '.print_r($extensionSensorInventory, true));
        WADA_Log::debug('discoverInstallSensorsAjaxResponse extensions: '.print_r($extensions, true));
        foreach($extensions AS $extension){
            if(array_key_exists($extension->plugin_folder, $extensionSensorInventory)){
                WADA_Log::debug('Adding sensors of extension '.$extension->plugin_folder);
                $sensors = $extensionSensorInventory[$extension->plugin_folder];
                $nrSensorsAdded += WADA_Setup::addSensorsToDatabase($sensors);
            }
        }
        WADA_Log::debug('discoverInstallSensorsAjaxResponse nrSensorsAdded after all activity: '.$nrSensorsAdded);

        if($nrSensorsAdded > 0){
            $msg = sprintf(__('%d sensors added', 'wp-admin-audit'), $nrSensorsAdded);
        }else{
            $msg = __('No sensors added', 'wp-admin-audit');
        }

        $response = array('success' => true, 'msg' => $msg);

        die( json_encode( $response ) );

    }
}