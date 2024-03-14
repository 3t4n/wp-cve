<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="ms-main-up-wrapper">
    <?php
if (majesticsupport::$_config['offline'] == 2) {
    if (majesticsupport::$_data['permission_granted'] == 1) {
        if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid() != 0) {
            if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
                if (majesticsupport::$_data['staff_enabled']) { ?>
    <!-- admin -->
    <?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('majesticsupport-jquery-ui-css', MJTC_PLUGIN_URL . 'includes/css/jquery-ui-smoothness.css');
$mjtc_scriptdateformat = MJTC_includer::MJTC_getModel('majesticsupport')->MJTC_getDateFormat();
wp_enqueue_script('majesticsupport-google-charts', MJTC_PLUGIN_URL . 'includes/js/google-charts.js');
wp_register_script( 'majesticsupport-google-charts-handle', '' );
wp_enqueue_script( 'majesticsupport-google-charts-handle' );
    $majesticsupport_js ="
        jQuery(document).ready(function($) {
            $('.custom_date').datepicker({
                dateFormat: '". esc_html($mjtc_scriptdateformat)."'
            });
        });
        google.load('visualization', '1', {
            packages: ['corechart']
        });
        google.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('date', '". esc_html(__('Dates','majestic-support'))."');
            data.addColumn('number', '". esc_html(__('New','majestic-support'))."');
            data.addColumn('number', '". esc_html(__('Answered','majestic-support'))."');
            data.addColumn('number', '". esc_html(__('Pending','majestic-support'))."');
            data.addColumn('number', '". esc_html(__('Overdue','majestic-support'))."');
            data.addColumn('number', '". esc_html(__('Closed','majestic-support'))."');
            data.addRows([
                ". majesticsupport::$_data['line_chart_json_array']."
            ]);

            var options = {
                colors: ['#159667', '#2168A2', '#f39f10', '#B82B2B', '#3D355A'],
                curveType: 'function',
                legend: {
                    position: 'bottom'
                },
                pointSize: 6,
                // This line will make you select an entire row of data at a time
                focusTarget: 'category',
                chartArea: {
                    width: '90%',
                    top: 50
                }
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
            chart.draw(data, options);
        }

    ";
    wp_add_inline_script('majesticsupport-google-charts-handle',$majesticsupport_js);
    $majesticsupport_js ="
        function resetFrom() {
            document.getElementById('ms-date-start').value = '';
            document.getElementById('ms-date-end').value = '';
            return true;
        }
    ";
    wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
    include_once(MJTC_PLUGIN_PATH . 'includes/header.php'); ?>
    <div class="mjtc-support-top-sec-header">
        <img class="mjtc-transparent-header-img1" alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
            src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/tp-image.png" />
        <div class="mjtc-support-top-sec-left-header">
            <div class="mjtc-support-main-heading">
                <?php echo esc_html(__("Agent detail Report",'majestic-support')); ?>
            </div>
            <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageBreadcrumps('agentdetailreport'); ?>
        </div>
    </div>
    <div class="mjtc-support-cont-main-wrapper">
        <div class="mjtc-support-cont-wrapper mjtc-support-cont-wrapper-color">
            <div class="mjtc-support-staff-report-wrapper">
                <div class="mjtc-support-top-search-wrp">
                    <div class="mjtc-support-search-fields-wrp">
                        <form class="mjtc-filter-form" name="majesticsupportform" id="majesticsupportform" method="POST"
                            action="<?php echo esc_url(wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'reports', 'mjslay'=>'staffdetailreport')),"staff-detail-report")); ?>">
                            <?php
                $curdate = date_i18n('Y-m-d');
                $enddate = date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime("now -1 month"));
                $date_start = !empty(majesticsupport::$_data['filter']['ms-date-start']) ? majesticsupport::$_data['filter']['ms-date-start'] : $curdate;
                $date_end = !empty(majesticsupport::$_data['filter']['ms-date-end']) ? majesticsupport::$_data['filter']['ms-date-end'] : $enddate; ?>
                            <?php echo wp_kses("<input type='hidden' name='ms-id' value='" . esc_attr(majesticsupport::$_data['staff_report']->id) . "'/>", MJTC_ALLOWED_TAGS); ?>
                            <div class="mjtc-support-fields-wrp">
                                <div class="mjtc-support-form-field">
                                    <?php echo wp_kses(MJTC_formfield::MJTC_text('ms-date-start', date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime($date_start)), array('class' => 'custom_date mjtc-support-field-input','placeholder' => esc_html(__('Start Date','majestic-support')))), MJTC_ALLOWED_TAGS); ?>
                                </div>
                                <div class="mjtc-support-form-field">
                                    <?php echo wp_kses(MJTC_formfield::MJTC_text('ms-date-end', date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime($date_end)), array('class' => 'custom_date mjtc-support-field-input','placeholder' => esc_html(__('End Date','majestic-support')))), MJTC_ALLOWED_TAGS); ?>
                                </div>
                            </div>
                            <div class="mjtc-support-search-form-btn-wrp">
                                <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('ms-go', esc_html(__('Search', 'majestic-support')), array('class' => 'mjtc-search-button', 'onclick' => 'return addSpaces();')), MJTC_ALLOWED_TAGS); ?>
                                <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('ms-reset', esc_html(__('Reset', 'majestic-support')), array('class' => 'mjtc-reset-button', 'onclick' => 'return resetFrom();')), MJTC_ALLOWED_TAGS); ?>

                            </div>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('MS_form_search', 'MS_SEARCH'), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('mspageid', get_the_ID()), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('mjtcslay', 'staffdetailreport'), MJTC_ALLOWED_TAGS); ?>
                        </form>
                    </div>
                </div>
                <div id="curve_chart" style="height:400px;width:100%;float: left; "></div>
            </div>
            <div class="mjtc-support-downloads-wrp">
                <div class="mjtc-support-downloads-heading-wrp">
                    <?php echo esc_html(__('Agent Report', 'majestic-support')) ?>
                </div>
                <?php
                $agent = majesticsupport::$_data['staff_report'];
                if(!empty($agent)){ ?>
                    <div class="mjtc-admin-staff-wrapper padding">
                        <div class="mjtc-col-md-4 nopadding mjtc-festaffreport-img">
                            <div class="mjtc-report-staff-image-wrapper">
                                <?php
                                if($agent->photo){
                                    $maindir = wp_upload_dir();
                                    $path = $maindir['baseurl'];

                                    $imageurl = $path."/".majesticsupport::$_config['data_directory']."/staffdata/staff_".$agent->id."/".$agent->photo;
                                }else{
                                    $imageurl = MJTC_PLUGIN_URL."includes/images/defaultprofile.png";
                                }
                            ?>
                                <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" class="mjtc-report-staff-pic" src="<?php echo esc_url($imageurl); ?>" />
                            </div>
                            <div class="mjtc-report-staff-cnt">
                                <div class="mjtc-report-staff-name">
                                    <?php
                                    if($agent->firstname && $agent->lastname){
                                        $agentname = $agent->firstname . ' ' . $agent->lastname;
                                    }else{
                                        $agentname = $agent->display_name;
                                    }
                                    echo esc_html(majesticsupport::MJTC_getVarValue($agentname));
                                ?>
                                </div>
                                <div class="mjtc-report-staff-username">
                                    <?php
                                    if($agent->display_name){
                                        $username = $agent->display_name;
                                    }else{
                                        $username = $agent->user_nicename;
                                    }
                                    echo esc_html(majesticsupport::MJTC_getVarValue($username));
                                ?>
                                </div>
                                <div class="mjtc-report-staff-email">
                                    <?php
                                    if($agent->email){
                                        $email = $agent->email;
                                    }else{
                                        $email = $agent->user_email;
                                    }
                                    echo esc_html($email);
                                ?>
                                </div>
                            </div>
                        </div>
                        <div class="mjtc-col-md-8 nopadding mjtc-festaffreport-data">
                            <div class="mjtc-col-md-2 mjtc-col-md-offset-1 mjtc-admin-report-box box1">
                                <span class="mjtc-report-box-number"><?php echo esc_html($agent->openticket); ?></span>
                                <span class="mjtc-report-box-title"><?php echo esc_html(__('New','majestic-support')); ?></span>
                                <div class="mjtc-report-box-color"></div>
                            </div>
                            <div class="mjtc-col-md-2 mjtc-admin-report-box box2">
                                <span class="mjtc-report-box-number"><?php echo esc_html($agent->answeredticket); ?></span>
                                <span class="mjtc-report-box-title"><?php echo esc_html(__('Answered','majestic-support')); ?></span>
                                <div class="mjtc-report-box-color"></div>
                            </div>
                            <div class="mjtc-col-md-2 mjtc-admin-report-box box3">
                                <span class="mjtc-report-box-number"><?php echo esc_html($agent->pendingticket); ?></span>
                                <span class="mjtc-report-box-title"><?php echo esc_html(__('Pending','majestic-support')); ?></span>
                                <div class="mjtc-report-box-color"></div>
                            </div>
                            <?php if(in_array('overdue', majesticsupport::$_active_addons)){ ?>
                                <div class="mjtc-col-md-2 mjtc-admin-report-box box4">
                                    <span class="mjtc-report-box-number"><?php echo esc_html($agent->overdueticket); ?></span>
                                    <span class="mjtc-report-box-title"><?php echo esc_html(__('Overdue','majestic-support')); ?></span>
                                    <div class="mjtc-report-box-color"></div>
                                </div>
                            <?php } ?>
                            <div class="mjtc-col-md-2 mjtc-admin-report-box box5">
                                <span class="mjtc-report-box-number"><?php echo esc_html($agent->closeticket); ?></span>
                                <span class="mjtc-report-box-title"><?php echo esc_html(__('Closed','majestic-support')); ?></span>
                                <div class="mjtc-report-box-color"></div>
                            </div>
                        </div>
                    </div>
                    <?php
                } ?>
            </div>
            <?php
            if(!empty(majesticsupport::$_data['staff_tickets'])){ ?>
                <div class="mjtc-support-downloads-wrp">
                    <div class="mjtc-support-downloads-heading-wrp">
                        <?php echo esc_html(__('Agent Tickets', 'majestic-support')) ?>
                    </div>
                    <div class="mjtc-support-download-content-wrp mjtc-support-download-content-wrp-mtop">
                        <div class="mjtc-support-table-wrp">
                            <div class="mjtc-support-table-header">
                                <div class="mjtc-support-table-header-col mjtc-col-md-4 mjtc-col-xs-4">
                                    <?php echo esc_html(__('Subject', 'majestic-support')); ?></div>
                                <div class="mjtc-support-table-header-col mjtc-col-md-3 mjtc-col-xs-3">
                                    <?php echo esc_html(__('Status', 'majestic-support')); ?></div>
                                <div class="mjtc-support-table-header-col mjtc-col-md-3 mjtc-col-xs-3">
                                    <?php echo esc_html(__('Priority', 'majestic-support')); ?></div>
                                <div class="mjtc-support-table-header-col mjtc-col-md-2 mjtc-col-xs-2">
                                    <?php echo esc_html(__('Created', 'majestic-support')); ?></div>
                            </div>
                            <div class="mjtc-support-table-body">
                                <?php
                                foreach(majesticsupport::$_data['staff_tickets'] AS $ticket){ ?>
                                <div class="mjtc-support-data-row">
                                    <div class="mjtc-support-table-body-col mjtc-col-md-4 mjtc-col-xs-4">
                                        <span
                                            class="mjtc-support-display-block"><?php echo esc_html(__('Subject','majestic-support')); ?>:</span>
                                        <span class="mjtc-support-title"><a class="mjtc-support-title-anchor"
                                                target="_blank"
                                                href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket','mjslay'=>'ticketdetail','majesticsupportid'=>$ticket->id))); ?>"><?php echo esc_html(majesticsupport::MJTC_getVarValue($ticket->subject)); ?></a></span>
                                    </div>
                                    <div class="mjtc-support-table-body-col mjtc-col-md-3 mjtc-col-xs-3">
                                        <span
                                            class="mjtc-support-display-block"><?php echo esc_html(__('Status','majestic-support')); ?>:</span>
                                        <?php
                                            // 0 -> New Ticket
                                            // 1 -> Waiting admin/staff reply
                                            // 2 -> in progress
                                            // 3 -> waiting for customer reply
                                            // 4 -> close ticket
                                            switch($ticket->status){
                                                case 0:
                                                    $status = '<font color="#159667">'.esc_html(__('New','majestic-support')).'</font>';
                                                    if($ticket->isoverdue == 1)
                                                        $status = '<font color="#B82B2B">'.esc_html(__('Overdue','majestic-support')).'</font>';
                                                break;
                                                case 1:
                                                    $status = '<font color="#f39f10">'.esc_html(__('Pending','majestic-support')).'</font>';
                                                    if($ticket->isoverdue == 1)
                                                        $status = '<font color="#B82B2B">'.esc_html(__('Overdue','majestic-support')).'</font>';
                                                break;
                                                case 2:
                                                    $status = '<font color="#f39f10">'.esc_html(__('In Progress','majestic-support')).'</font>';
                                                    if($ticket->isoverdue == 1)
                                                        $status = '<font color="#B82B2B">'.esc_html(__('Overdue','majestic-support')).'</font>';
                                                break;
                                                case 3:
                                                    $status = '<font color="#2168A2">'.esc_html(__('Answered','majestic-support')).'</font>';
                                                    if($ticket->isoverdue == 1)
                                                        $status = '<font color="#B82B2B">'.esc_html(__('Overdue','majestic-support')).'</font>';
                                                break;
                                                case 4:
                                                    $status = '<font color="#3D355A">'.esc_html(__('Closed','majestic-support')).'</font>';
                                                break;
                                                case 5:
                                                    $status = '<font color="#3D355A">'.esc_html(__('Merged','majestic-support')).'</font>';
                                                break;
                                            }
                                            echo wp_kses($status, MJTC_ALLOWED_TAGS);
                                        ?>
                                    </div>
                                    <div class="mjtc-support-table-body-col mjtc-col-md-3 mjtc-col-xs-3">
                                        <span
                                            class="mjtc-support-display-block"><?php echo esc_html(__('Priority','majestic-support')); ?>:</span>
                                        <span class="mjtc-support-priority"
                                            style="background-color:<?php echo esc_attr($ticket->prioritycolour); ?>;"><?php echo esc_html(majesticsupport::MJTC_getVarValue($ticket->priority)); ?></span>
                                    </div>
                                    <div class="mjtc-support-table-body-col mjtc-col-md-2 mjtc-col-xs-2">
                                        <span
                                            class="mjtc-support-display-block"><?php echo esc_html(__('Created','majestic-support')); ?>:</span>
                                        <?php echo esc_html(date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))); ?>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if (majesticsupport::$_data[1]) {
                    $data = '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(majesticsupport::$_data[1]) . '</div></div>';
                    echo wp_kses($data, MJTC_ALLOWED_TAGS);
                }
            }  else {
                MJTC_layout::MJTC_getNoRecordFound();
            }?>
            <!-- END admin -->
                <?php
                } else {
                    MJTC_layout::MJTC_getStaffMemberDisable();
                }
            } else {
                MJTC_layout::MJTC_getNotStaffMember();
            }
        } else {
            $redirect_url = majesticsupport::makeUrl(array('mjsmod'=>'reports','mjslay'=>'staffreports'));
            $redirect_url = MJTC_majesticsupportphplib::MJTC_safe_encoding($redirect_url);
            MJTC_layout::MJTC_getUserGuest($redirect_url);
        }
    } else { // User permission not granted
        MJTC_layout::MJTC_getPermissionNotGranted();
    }
} else {
    MJTC_layout::MJTC_getSystemOffline();
} ?>
        </div>
    </div>
</div>
