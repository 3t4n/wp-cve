<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
wp_enqueue_script('majesticsupport-notify-app', MJTC_PLUGIN_URL . 'includes/js/firebase-app.js');
wp_enqueue_script('majesticsupport-notify-message', MJTC_PLUGIN_URL . 'includes/js/firebase-messaging.js');
wp_enqueue_script('majesticsupport-google-charts', MJTC_PLUGIN_URL . 'includes/js/google-charts.js');

wp_enqueue_style('majesticsupport-status-graph', MJTC_PLUGIN_URL . 'includes/css/status_graph.css');
do_action('ticket-notify-generate-token');
MJTC_message::MJTC_getMessage();
?>
<?php
$majesticsupport_js ="
    google.load('visualization', '1', {packages: ['corechart']});
    google.setOnLoadCallback(drawStackChartHorizontal);
    google.setOnLoadCallback(drawTodayTicketsChart);

    function drawStackChartHorizontal() {
      var data = google.visualization.arrayToDataTable([
        ".
            wp_kses(majesticsupport::$_data['stack_chart_horizontal']['title'], MJTC_ALLOWED_TAGS).",".
            wp_kses(majesticsupport::$_data['stack_chart_horizontal']['data'], MJTC_ALLOWED_TAGS) ."
        ]);

        var view = new google.visualization.DataView(data);

        var options = {
            height: 300,
            chartArea: {
                width: '80%'
            },
            legend: {
                position: 'top',
            },
            curveType: 'function',
            colors: ['#B82B2B', '#621166', '#2168A2', '#159667'],
        };
        var chart = new google.visualization.AreaChart(document.getElementById('stack_chart_horizontal'));
        chart.draw(view, options);
    }

    function drawTodayTicketsChart() {
        var data = google.visualization.arrayToDataTable([
            ".
                wp_kses(majesticsupport::$_data['today_ticket_chart']['title'], MJTC_ALLOWED_TAGS).",".
                wp_kses(majesticsupport::$_data['today_ticket_chart']['data'], MJTC_ALLOWED_TAGS)."
            
        ]);

        var view = new google.visualization.DataView(data);

        var options = {
            height: 300,
            chartArea: {
                width: '70%',
                left: 30
            },
            legend: {
                position: 'right'
            },
            hAxis: {
                textPosition: 'none'
            },
            colors: ". wp_kses(majesticsupport::$_data['stack_chart_horizontal']['colors'], MJTC_ALLOWED_TAGS).",
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('today_ticket_chart'));
        chart.draw(view, options);
    }

";
//custom handle use because of this add script after chart library include
wp_register_script( 'majesticsupport-inlinescript-handle', '' );
wp_enqueue_script( 'majesticsupport-inlinescript-handle' );

wp_add_inline_script('majesticsupport-inlinescript-handle',$majesticsupport_js);
?>  
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <div id="mjtc-main-cp-wrapper">
            <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('dashboard'); ?>
            <div id="msadmin-data-wrp" class="p0 bg-n bs-n b0">
                <?php if(get_option( 'ms_hide_msadmin_top_banner') != 1){ ?>
                <?php } ?>
                <div class="mjtc-support-stats">
                    <a class="mjtc-support-stats-link mjtc-support-stats-new" href="?page=majesticsupport_ticket" data-tab-number="1">
                        <div class="mjtc-support-stats-top" data-tab-number="1">
                            <div class="mjtc-support-stats-count">
                                <?php echo esc_html(majesticsupport::$_data['ticket_total']['openticket']); ?>
                            </div>
                            <div class="mjtc-support-stats-title">
                                <?php echo esc_html(__('New Tickets', 'majestic-support')); ?>
                            </div>
                        </div>
                        <div class="mjtc-support-stats-bottom">
                            <img class="mjtc-cp-baner-img" alt="<?php echo esc_html(__('addon','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/ticket-line.png" />
                        </div>
                    </a>
                    <a class="mjtc-support-stats-link mjtc-support-stats-answered" href="?page=majesticsupport_ticket" data-tab-number="2">
                        <div class="mjtc-support-stats-top" data-tab-number="1">
                            <div class="mjtc-support-stats-count">
                                <?php echo esc_html(majesticsupport::$_data['ticket_total']['answeredticket']); ?>
                            </div>
                            <div class="mjtc-support-stats-title">
                                <?php echo esc_html(__('Answered Tickets', 'majestic-support')); ?>
                            </div>
                        </div>
                        <div class="mjtc-support-stats-bottom">
                            <img class="mjtc-cp-baner-img" alt="<?php echo esc_html(__('addon','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/my-ticket-line.png" />
                        </div>
                    </a>
                    <a class="mjtc-support-stats-link mjtc-support-stats-pending" href="?page=majesticsupport_ticket" data-tab-number="1">
                        <div class="mjtc-support-stats-top" data-tab-number="1">
                            <div class="mjtc-support-stats-count">
                                <?php echo esc_html(majesticsupport::$_data['ticket_total']['pendingticket']); ?>
                            </div>
                            <div class="mjtc-support-stats-title">
                                <?php echo esc_html(__('Pending Tickets', 'majestic-support')); ?>
                            </div>
                        </div>
                        <div class="mjtc-support-stats-bottom">
                            <img class="mjtc-cp-baner-img" alt="<?php echo esc_html(__('addon','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/status-line.png" />
                        </div>
                    </a>
                    <?php if(in_array('overdue', majesticsupport::$_active_addons)){ ?>
                        <a class="mjtc-support-stats-link mjtc-support-stats-overdue" href="?page=majesticsupport_ticket" data-tab-number="3">
                            <div class="mjtc-support-stats-top" data-tab-number="1">
                                <div class="mjtc-support-stats-count">
                                    <?php echo esc_html(majesticsupport::$_data['ticket_total']['overdueticket']); ?>
                                </div>
                                <div class="mjtc-support-stats-title">
                                    <?php echo esc_html(__('Overdue Tickets', 'majestic-support')); ?>
                                </div>
                            </div>
                            <div class="mjtc-support-stats-bottom">
                                <img class="mjtc-cp-baner-img" alt="<?php echo esc_html(__('addon','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/over-due-line.png" />
                            </div>
                        </a>
                    <?php } else { ?>
                        <a class="mjtc-support-stats-link mjtc-support-stats-all" href="?page=majesticsupport_ticket" data-tab-number="5">
                            <div class="mjtc-support-stats-top" data-tab-number="5">
                                <div class="mjtc-support-stats-count">
                                    <?php echo esc_html(majesticsupport::$_data['ticket_total']['allticket']); ?>
                                </div>
                                <div class="mjtc-support-stats-title">
                                    <?php echo esc_html(__('All Tickets', 'majestic-support')); ?>
                                </div>
                            </div>
                            <div class="mjtc-support-stats-bottom">
                                <img class="mjtc-cp-baner-img" alt="<?php echo esc_html(__('addon','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/all-tickets.png" />
                            </div>
                        </a>
                    <?php } ?>
                </div>

                <!-- update available alert -->
                <?php if (majesticsupport::$_data['update_avaliable_for_addons'] != 0) {?>
                    <div class="mjtc-update-alert-wrp">
                        <div class="mjtc-update-alert-image">
                            <img alt="<?php echo esc_attr(__('Update','majestic-support')); ?>" class="mjtc-update-alert-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/update-icon.png"/>
                        </div>
                        <div class="mjtc-update-alert-cnt">
                                <?php echo esc_html(__("Hey there! We have recently launched a fresh update for the add-ons. Don't forget to update the add-ons to enjoy the greatest features!",'majestic-support')); ?>
                        </div>
                        <a href="?page=majesticsupport_premiumplugin&mjslay=addonstatus" class="mjtc-update-alert-btn" title="<?php echo esc_attr(__('View','majestic-support')); ?>">
                            <?php echo esc_html(__('View Addone Status','majestic-support')); ?>
                        </a>
                    </div>
                <?php } ?>
                <div class="mjtc-cp-cnt-sec">
                    <div class="mjtc-cp-cnt-right">
                    <div class="mjtc-cp-cnt">
                            <div class="mjtc-cp-cnt-title">
                                <span class="mjtc-cp-cnt-title-txt">
                                    <?php echo esc_html(__('Today Tickets', 'majestic-support')); ?>
                                </span>
                            </div>
                            <div id="mjtc-pm-grapharea">
                                <div id="today_ticket_chart" style="width:100%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mjtc-cp-cnt-left">
                    <?php
                            $open_percentage = 0;
                            $close_percentage = 0;
                            $answered_percentage = 0;
                            $pending_percentage = 0;
                            $overdue_percentage = 0;
                            if(isset(majesticsupport::$_data['ticket_total']) && isset(majesticsupport::$_data['ticket_total']['allticket']) && majesticsupport::$_data['ticket_total']['allticket'] != 0){
                                $open_percentage = round((majesticsupport::$_data['ticket_total']['openticket'] / majesticsupport::$_data['ticket_total']['allticket']) * 100);
                                $overdue_percentage = round((majesticsupport::$_data['ticket_total']['overdueticket'] / majesticsupport::$_data['ticket_total']['allticket']) * 100);
                                $answered_percentage = round((majesticsupport::$_data['ticket_total']['answeredticket'] / majesticsupport::$_data['ticket_total']['allticket']) * 100);
                                $pending_percentage = round((majesticsupport::$_data['ticket_total']['pendingticket'] / majesticsupport::$_data['ticket_total']['allticket']) * 100);
                            }
                            if(isset(majesticsupport::$_data['ticket_total']['allticket']) && isset(majesticsupport::$_data['ticket_total']['allticket']) && majesticsupport::$_data['ticket_total']['allticket'] != 0){
                                $allticket_percentage = 100;
                            }
                        ?>
                        <div class="mjtc-cp-cnt">
                            <div class="mjtc-cp-cnt-title">
                                <span class="mjtc-cp-cnt-title-txt">
                                    <?php echo esc_html(__('Statistics', 'majestic-support')); ?>
                                </span>
                                <span class="mjtc-cp-cnt-title-txt mjtc-cp-cnt-title-txt-right">
                                    <?php 
                                    $curdate = date_i18n('Y-M-d'); $fromdate = date_i18n('Y-M-d', MJTC_majesticsupportphplib::MJTC_strtotime("now -1 month"));
                                    echo esc_html($fromdate) .' - '. esc_html($curdate);
                                    ?>
                                </span>
                            </div>
                            <div id="mjtc-pm-grapharea">
                                <div id="stack_chart_horizontal" style="width:100%;"></div>
                            </div>
                        </div>   
                    </div>
                </div>

                <?php
                $field_array = MJTC_includer::MJTC_getModel('fieldordering')->getFieldTitleByFieldfor(1);
                ?>
                <div class="mjtc-cp-cnt-divider">
                    <div class="mjtc-cp-cnt-divider-left">
                        <div class="mjtc-cp-cnt-sec mjtc-cp-tkt">
                            <div class="mjtc-cp-cnt-title">
                                <span class="mjtc-cp-cnt-title-txt">
                                    <?php echo esc_html(__('Latest Tickets', 'majestic-support')); ?>
                                </span>
                                <?php if(count(majesticsupport::$_data['tickets']) > 0){ ?>
                                <a href="?page=majesticsupport_ticket" class="mjtc-cp-cnt-title-btn"
                                    title="<?php echo esc_attr(__('View All Tickets', 'majestic-support')); ?>">
                                    <?php echo esc_html(__('View All Tickets', 'majestic-support')); ?>
                                </a>
                                <?php } ?>
                            </div>
                            <div class="mjtc-support-admin-cp-tickets">
                                <?php if(count(majesticsupport::$_data['tickets']) > 0){
                                    foreach (majesticsupport::$_data['tickets'] AS $ticket): ?>
                                        <div class="mjtc-cp-tkt-list">
                                            <div class="mjtc-cp-tkt-list-left">
                                                <div class="mjtc-cp-tkt-image">
                                                    <?php echo wp_kses(ms_get_avatar(MJTC_includer::MJTC_getModel('majesticsupport')->getWPUidById($ticket->uid)), MJTC_ALLOWED_TAGS); ?>
                                                </div>
                                                <div class="mjtc-cp-tkt-cnt">
                                                    <?php
                                                    if (isset($field_array['fullname'])) { ?>
                                                        <div class="mjtc-cp-tkt-info name"><?php echo esc_html($ticket->name); ?>
                                                        </div>
                                                        <?php
                                                    }
                                                    if (isset($field_array['subject'])) { ?>
                                                        <div class="mjtc-cp-tkt-info subject">
                                                            <a title="<?php echo esc_attr(__('Subject','majestic-support')); ?>"
                                                                href="?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=<?php echo esc_attr($ticket->id); ?>"><?php echo esc_html($ticket->subject); ?></a>
                                                        </div>
                                                        <?php
                                                    }
                                                    if (isset($field_array['department'])) { ?>
                                                        <div class="mjtc-cp-tkt-info dept">
                                                            <span class="mjtc-cp-tkt-info-label">
                                                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['department'])). " : "; ?>
                                                            </span>
                                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($ticket->departmentname)); ?>
                                                        </div>
                                                        <?php
                                                    } ?>
                                                </div>
                                            </div>
                                            <div class="mjtc-cp-tkt-list-left-below-section">
                                                <div class="mjtc-cp-tkt-crted">
                                                    <?php echo esc_html(date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))); ?>
                                                </div>
                                                <?php
                                                if (isset($field_array['priority'])) { ?>
                                                    <div class="mjtc-cp-tkt-prorty">
                                                        <span style="background-color:<?php echo esc_attr($ticket->prioritycolour); ?>;">
                                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($ticket->priority)); ?>
                                                        </span>
                                                    </div>
                                                    <?php
                                                } ?>
                                            </div>
                                        </div>
                                        <?php
                                    endforeach;
                                }else{ ?>
                                <div class="ms_no_record">
                                    <?php echo esc_html(__("No Record Found",'majestic-support')); ?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="mjtc-cp-cnt-divider-right">
                        <div class="mjtc-cp-cnt-title">
                            <span class="mjtc-cp-cnt-title-txt">
                                <?php echo esc_html(__('Quick Links', 'majestic-support')); ?>
                            </span>
                        </div>
                        <div id="mjtc-wrapper-menus">
                            <a title="<?php echo esc_attr(__('Tickets', 'majestic-support')); ?>" class="mjtc-admin-menu-link"
                                href="?page=majesticsupport_ticket"> <img alt="<?php echo esc_html(__('Tickets', 'majestic-support')); ?>"
                                    class="msmenu-img"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/tickets.png" />
                                <div class="msmenu-text"><?php echo esc_html(__('Tickets', 'majestic-support')); ?></div>
                            </a>
                            <a title="<?php echo esc_attr(__('Smart Replies', 'majestic-support')); ?>" class="mjtc-admin-menu-link"
                                href="?page=majesticsupport_smartreply"> <img alt="<?php echo esc_html(__('Tickets', 'majestic-support')); ?>"
                                    class="msmenu-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/smart-reply.png" />
                                <div class="msmenu-text"><?php echo esc_html(__('Smart Replies', 'majestic-support')); ?></div>
                            </a>
                            <a title="<?php echo esc_attr(__('Department','majestic-support')); ?>"
                                class="mjtc-admin-menu-link" href="?page=majesticsupport_department"><img
                                    alt="<?php echo esc_html(__('Department','majestic-support')); ?>" class="msmenu-img"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/department.png" />
                                <div class="msmenu-text"><?php echo esc_html(__('Departments', 'majestic-support')); ?></div>
                            </a>
                            <a title="<?php echo esc_attr(__('Priority','majestic-support')); ?>" class="mjtc-admin-menu-link"
                                href="?page=majesticsupport_priority"><img alt="<?php echo esc_html(__('Priority','majestic-support')); ?>"
                                    class="msmenu-img"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/priorities.png" />
                                <div class="msmenu-text"><?php echo esc_html(__('Priorities', 'majestic-support')); ?></div>
                            </a>
                            <?php if( in_array('multiform', majesticsupport::$_active_addons) ){ ?>
                                <a title="<?php echo esc_attr(__('Multiforms','majestic-support')); ?>"
                                    class="mjtc-admin-menu-link" href="?page=majesticsupport_multiform"><img
                                        alt="<?php echo esc_html(__('Multiforms','majestic-support')); ?>" class="msmenu-img"
                                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/multi-form.png" />
                                    <div class="msmenu-text"><?php echo esc_html(__('Multiforms', 'majestic-support')); ?></div>
                                </a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('Field Ordering','majestic-support')); ?>"
                                    class="mjtc-admin-menu-link" href="?page=majesticsupport_fieldordering&fieldfor=1"><img
                                        alt="<?php echo esc_html(__('Field Ordering','majestic-support')); ?>" class="msmenu-img"
                                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/fields.png" />
                                    <div class="msmenu-text"><?php echo esc_html(__('Fields', 'majestic-support')); ?></div>
                                </a>
                            <?php } ?>
                            <a title="<?php echo esc_attr(__('Settings','majestic-support')); ?>"
                                class="mjtc-admin-menu-link" href="?page=majesticsupport_configuration&msconfigid=general"><img
                                    alt="<?php echo esc_html(__('Settings','majestic-support')); ?>" class="msmenu-img"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/config.png" />
                                <div class="msmenu-text"><?php echo esc_html(__('Settings', 'majestic-support')); ?>
                                </div>
                            </a>
                            <a title="<?php echo esc_attr(__('Department Reports','majestic-support')); ?>"
                                class="mjtc-admin-menu-link"
                                href="<?php echo esc_url(admin_url('admin.php?page=majesticsupport_reports&mjslay=departmentreport')); ?>"><img
                                    alt="<?php echo esc_html(__('Department Reports','majestic-support')); ?>"
                                    class="msmenu-img"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/department-report.png" />
                                <div class="msmenu-text"><?php echo esc_html(__('Department Reports','majestic-support')); ?>
                                </div>
                            </a>
                            <a title="<?php echo esc_attr(__('User report','majestic-support')); ?>"
                                class="mjtc-admin-menu-link"
                                href="<?php echo esc_url(admin_url('admin.php?page=majesticsupport_reports&mjslay=userreport')); ?>"><img
                                    alt="<?php echo esc_html(__('User report','majestic-support')); ?>" class="msmenu-img"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/user-reports.png" />
                                <div class="msmenu-text"><?php echo esc_html(__('User Reports', 'majestic-support')); ?></div>
                            </a>
                            <?php /* ?>
                            <a title="<?php echo esc_attr(__('Translations')); ?>" class="mjtc-admin-menu-link"
                                href="#"><img
                                    alt="<?php echo esc_html(__('Translations','majestic-support')); ?>" class="msmenu-img"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/translations.png" />
                                <div class="msmenu-text"><?php echo esc_html(__('Translations')); ?></div>
                            </a>
                            <?php */ ?>
                            <a title="<?php echo esc_attr(__('Email','majestic-support')); ?>" class="mjtc-admin-menu-link"
                                href="?page=majesticsupport_email"><img alt="<?php echo esc_html(__('Email','majestic-support')); ?>"
                                    class="msmenu-img"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/system-email.png" />
                                <div class="msmenu-text"><?php echo esc_html(__('System Emails', 'majestic-support')); ?>
                                </div>
                            </a>
                            <a title="<?php echo esc_attr(__('email template','majestic-support')); ?>"
                                class="mjtc-admin-menu-link" href="?page=majesticsupport_emailtemplate"><img
                                    alt="<?php echo esc_html(__('email template','majestic-support')); ?>" class="msmenu-img"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/email-template.png" />
                                <div class="msmenu-text"><?php echo esc_html(__('Email Templates', 'majestic-support')); ?>
                                </div>
                            </a>
                            <a title="<?php echo esc_attr(__('add missing users','majestic-support')); ?>"
                                class="mjtc-admin-menu-link"
                                href="<?php echo esc_url(wp_nonce_url('?page=majesticsupport_majesticsupport&task=addmissingusers&action=mstask','add-missing-users'));?>"><img
                                    alt="<?php echo esc_html(__('user','majestic-support')); ?>" class="msmenu-img"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/wp-user.png" />
                                <div class="msmenu-text"><?php echo esc_html(__('Sync WP Users', 'majestic-support')); ?></div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="mjtc-cp-cnt-divider">
                    <div class="mjtc-cp-cnt-divider-left mjtc-cp-cnt-smart-reply-left">
                        <div class="mjtc-cp-cnt-sec mjtc-cp-tkt">
                            <div class="mjtc-cp-cnt-title">
                                <span class="mjtc-cp-cnt-title-txt">
                                    <?php echo esc_html(__('Smart Reply', 'majestic-support')); ?>
                                </span>
                                <a href="?page=majesticsupport_smartreply&mjslay=addsmartreply" class="mjtc-cp-cnt-title-btn"
                                    title="<?php echo esc_attr(__('Add New Smart Reply', 'majestic-support')); ?>">
                                    <?php echo esc_html(__('Add Smart Reply', 'majestic-support')); ?>
                                </a>
                            </div>
                            <?php if(count(majesticsupport::$_data['smartreply']) > 0){ ?>
                                <div class="mjtc-support-admin-cp-tickets">
                                    <?php foreach (majesticsupport::$_data['smartreply'] AS $smartreply) { ?>
                                        <div class="mjtc-cp-tkt-list">
                                            <div class="mjtc-cp-tkt-list-text">
                                                <?php echo esc_html($smartreply->title); ?>
                                            </div>
                                            <div class="mjtc-cp-tkt-list-number">
                                                (<?php echo esc_html($smartreply->usedby); ?>)
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php }else{ ?>
                                <div class="mjtc-support-admin-cp-tickets mjtc-smart-reply-border">
                                    <div class="mjtc-cp-add-smart-reply">
                                        <div class="mjtc-cp-add-smart-header">
                                            <?php echo esc_html(__("Smart Reply",'majestic-support')); ?>
                                        </div>
                                        <div class="mjtc-cp-add-smart-body">
                                            <p>
                                                <?php echo esc_html(__("Suggest a quick and most relevant reply to the customer's query.",'majestic-support')); ?>
                                            </p>
                                        </div>
                                        <div class="mjtc-cp-add-smart-footer">
                                            <a title="<?php echo esc_attr(__('Add Reply','majestic-support')); ?>" class="mjtc-admin-menu-link" href="?page=majesticsupport_smartreply&mjslay=addsmartreply">
                                                <?php echo esc_html(__("Add Reply",'majestic-support')); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="mjtc-cp-cnt-divider-right mjtc-cp-cnt-smart-reply-right">
                        <div class="mjtc-cp-cnt-sec mjtc-cp-tkt">
                            <div class="mjtc-cp-cnt-title">
                                <span class="mjtc-cp-cnt-title-txt">
                                    <?php echo esc_html(__('Agents', 'majestic-support')); ?>
                                </span>
                                <?php if (in_array('agent', majesticsupport::$_active_addons)) { ?>
                                    <a href="?page=majesticsupport_agent&mjslay=addstaff" class="mjtc-cp-cnt-title-btn"
                                        title="<?php echo esc_attr(__('Add New Agent', 'majestic-support')); ?>">
                                        <?php echo esc_html(__('Add New Agent', 'majestic-support')); ?>
                                    </a>
                                <?php } ?>
                            </div>
                            <?php if (in_array('agent', majesticsupport::$_active_addons)) { ?>
                                <div class="mjtc-support-admin-cp-tickets">
                                    <?php
                                    if(count(majesticsupport::$_data['agents']) > 0){
                                        foreach (majesticsupport::$_data['agents'] AS $agent) { ?>
                                            <div class="mjtc-cp-tkt-list mjtc-cp-tkt-list-new">
                                                <div class="mjtc-cp-tkt-list-text">
                                                    <span style="margin-right: 10px;">
                                                        <?php if($agent->staffphoto) {
                                                            $maindir = wp_upload_dir();
                                                            $path = $maindir['baseurl'];
                                                            $file = $path.'/'.esc_attr(majesticsupport::$_config['data_directory']).'/staffdata/staff_'.esc_html($agent->staffid).'/'.esc_html($agent->staffphoto);
                                                        ?>
                                                            <img alt="<?php echo esc_html(__('Agent','majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url($file);?> ">
                                                        <?php } else {
                                                            echo wp_kses(ms_get_avatar(MJTC_includer::MJTC_getModel('majesticsupport')->getWPUidById($agent->staffid)), MJTC_ALLOWED_TAGS);
                                                        } ?>    
                                                    </span>
                                                    <?php echo esc_html($agent->staffname); ?>
                                                </div>
                                                <p class="mjtc-cp-tkt-list-number">
                                                    (<?php echo esc_html($agent->totalticket); ?>)
                                                </p>
                                            </div>
                                            <?php
                                        }
                                    }else { ?>
                                        <div class="mjtc-cp-add-smart-reply">
                                            <div class="mjtc-cp-add-smart-header">
                                                <?php echo esc_html(__("Agent",'majestic-support')); ?>
                                            </div>
                                            <div class="mjtc-cp-add-smart-body">
                                                <p>
                                                    <?php echo esc_html(__("The system has no agents add an agent to maximize your productivity.",'majestic-support')); ?>
                                                </p>
                                            </div>
                                            <div class="mjtc-cp-add-smart-footer">
                                                <a title="<?php echo esc_attr(__('add agent','majestic-support')); ?>" class="mjtc-admin-menu-link" href="?page=majesticsupport_agent&mjslay=addstaff">
                                                    <?php echo esc_html(__("Add Agent",'majestic-support')); ?>
                                                </a>
                                            </div>
                                        </div>
                                        <?php
                                    } ?>
                                </div>
                            <?php } else { ?>
                                <div class="mjtc-support-admin-cp-tickets mjtc-smart-reply-border">
                                    <div class="mjtc-cp-add-smart-reply">
                                        <div class="mjtc-cp-add-smart-header">
                                            <?php echo esc_html(__("Agent Add-on Not Installed",'majestic-support')); ?>
                                        </div>
                                        <div class="mjtc-cp-add-smart-body">
                                            <p>
                                                <?php echo esc_html(__("Don't limit yourself—install the Agent addon for maximum productivity.",'majestic-support')); ?>
                                            </p>
                                        </div>
                                        <div class="mjtc-cp-add-smart-footer">
                                            <a title="<?php echo esc_attr(__('agent addone','majestic-support')); ?>" class="mjtc-admin-menu-link" href="https://www.majesticsupport.com/product/agents/">
                                                <?php echo esc_html(__("Install Agent Addon",'majestic-support')); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="mjtc-cp-cnt-divider mjtc-cp-reports-divider">
                    <div class="mjtc-cp-cnt-divider-left">
                        <div class="mjtc-cp-cnt-sec mjtc-cp-tkt">
                            <div class="mjtc-cp-cnt-title">
                                <span class="mjtc-cp-cnt-title-txt">
                                    <?php echo esc_html(__('Tickets By Departments', 'majestic-support')); ?>
                                </span>
                            </div>
                            <div class="mjtc-support-admin-cp-tickets">
                                <?php if(count(majesticsupport::$_data['tickets_by_department']) > 0){
                                    foreach (majesticsupport::$_data['tickets_by_department'] AS $dept): ?>
                                        <div class="mjtc-cp-tkt-list">
                                            <div class="mjtc-cp-tkt-list-text">
                                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($dept->departmentname)); ?>
                                            </div>
                                            <div class="mjtc-cp-tkt-list-number">
                                                <?php
                                                    if (isset($dept->usedby)) {
                                                        $usedby = $dept->usedby;
                                                    } else {
                                                        $usedby = 0;
                                                    }
                                                ?>
                                                (<?php echo esc_html($usedby); ?>)
                                            </div>
                                        </div>
                                        <?php
                                    endforeach;
                                }else{ ?>
                                    <div class="mjtc-cp-add-smart-reply">
                                        <div class="mjtc-cp-add-smart-header">
                                            <?php echo esc_html(__("Departments",'majestic-support')); ?>
                                        </div>
                                        <div class="mjtc-cp-add-smart-body">
                                            <p>
                                                <?php echo esc_html(__("The best support plugin for the majestic support has everything you need.",'majestic-support')); ?>
                                            </p>
                                        </div>
                                        <div class="mjtc-cp-add-smart-footer">
                                            <a title="<?php echo esc_attr(__('Add Department','majestic-support')); ?>" class="mjtc-admin-menu-link" href="?page=majesticsupport_department&mjslay=adddepartment">
                                                <?php echo esc_html(__("Add Department",'majestic-support')); ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="mjtc-cp-cnt-divider-left">
                        <div class="mjtc-cp-cnt-sec mjtc-cp-tkt">
                            <div class="mjtc-cp-cnt-title">
                                <span class="mjtc-cp-cnt-title-txt">
                                    <?php echo esc_html(__('Tickets By Priority', 'majestic-support')); ?>
                                </span>
                            </div>
                            <div class="mjtc-support-admin-cp-tickets">
                                <?php if(count(majesticsupport::$_data['tickets_by_priority']) > 0){
                                    foreach (majesticsupport::$_data['tickets_by_priority'] AS $priority): ?>
                                        <div class="mjtc-cp-tkt-list mjtc-cp-priority-list">
                                            <div class="mjtc-cp-tkt-list-text">
                                                <span class="mjtc-cp-priority-color" style="background-color: <?php echo esc_attr($priority->prioritycolour)?>;"></span>
                                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($priority->priority)); ?>
                                            </div>
                                            <div class="mjtc-cp-tkt-list-number">
                                                <?php
                                                    if (isset($priority->usedby)) {
                                                        $usedby = $priority->usedby;
                                                    } else {
                                                        $usedby = 0;
                                                    }
                                                ?>
                                                (<?php echo esc_html($usedby); ?>)
                                            </div>
                                        </div>
                                        <?php
                                    endforeach;
                                }else{ ?>
                                    <div class="mjtc-cp-add-smart-reply">
                                        <div class="mjtc-cp-add-smart-header">
                                            <?php echo esc_html(__("Priority",'majestic-support')); ?>
                                        </div>
                                        <div class="mjtc-cp-add-smart-body">
                                            <p>
                                                <?php echo esc_html(__("The best support plugin for the majestic support has everything you need.",'majestic-support')); ?>
                                            </p>
                                        </div>
                                        <div class="mjtc-cp-add-smart-footer">
                                            <a title="<?php echo esc_attr(__('Add Priority','majestic-support')); ?>" class="mjtc-admin-menu-link" href="?page=majesticsupport_priority&mjslay=addpriority">
                                                <?php echo esc_html(__("Add Priority",'majestic-support')); ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="mjtc-cp-cnt-divider-right">
                        <div class="mjtc-cp-cnt-sec mjtc-cp-tkt">
                            <div class="mjtc-cp-cnt-title">
                                <span class="mjtc-cp-cnt-title-txt">
                                    <?php echo esc_html(__('Ticket History', 'majestic-support')); ?>
                                </span>
                            </div>
                            <div class="mjtc-support-admin-cp-tickets">
                                <?php if(in_array('tickethistory', majesticsupport::$_active_addons)){ ?>
                                    <?php if(count(majesticsupport::$_data['tickethistory']) > 0){
                                        foreach (majesticsupport::$_data['tickethistory'] AS $history): ?>
                                            <div class="mjtc-cp-tkt-list">
                                                <div class="mjtc-cp-tkt-list-text">
                                                    <?php echo esc_html(majesticsupport::MJTC_getVarValue($history->message)); ?>
                                                </div>
                                            </div>
                                            <?php
                                        endforeach;
                                    }else{ ?>
                                        <div class="mjtc-cp-add-smart-reply">
                                            <div class="mjtc-cp-add-smart-header">
                                                <?php echo esc_html(__("Ticket History",'majestic-support')); ?>
                                            </div>
                                            <div class="mjtc-cp-add-smart-body">
                                                <p>
                                                    <?php echo esc_html(__("The best support plugin for the majestic support has everything you need.",'majestic-support')); ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } else {?>
                                    <div class="mjtc-cp-add-smart-reply">
                                        <div class="mjtc-cp-add-smart-header">
                                            <?php echo esc_html(__("Ticket History",'majestic-support')); ?>
                                        </div>
                                        <div class="mjtc-cp-add-smart-body">
                                            <p>
                                                <?php echo esc_html(__("The best support plugin for the majestic support has everything you need.",'majestic-support')); ?>
                                            </p>
                                        </div>
                                        <div class="mjtc-cp-add-smart-footer">
                                            <a title="<?php echo esc_attr(__('Install Addone','majestic-support')); ?>" class="mjtc-admin-menu-link" href="https://www.majesticsupport.com/product/ticket-history/">
                                                <?php echo esc_html(__("Install Addone",'majestic-support')); ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mjtc-cp-fed-ad-wrp">
                    <?php $fullwidthclass = "";
                    require_once MJTC_PLUGIN_PATH.'includes/addon-updater/msupdater.php';
                    $MJTC_SUPPORTTICKETUpdater  = new MJTC_SUPPORTTICKETUpdater();
                    $cdnversiondata = $MJTC_SUPPORTTICKETUpdater->MJTC_getPluginVersionDataFromCDN();
                    if(count(majesticsupport::$_active_addons) >= 37 ){
                        $fullwidthclass = "style=width:100% !important";
                    }?>
                    <div class="mjtc-cp-addon-wrp">
                        <div class="mjtc-cp-cnt-title">
                            <span class="mjtc-cp-cnt-title-txt">
                                <?php echo esc_html(__('Add-ons', 'majestic-support')); ?>
                            </span>
                            <a href="?page=majesticsupport_premiumplugin&mjslay=addonstatus" class="mjtc-cp-cnt-title-btn" title="<?php echo esc_attr(__('View Details', 'majestic-support')); ?>">
                                <?php echo esc_html(__('View Details', 'majestic-support')); ?>
                            </a>
                        </div>
                        <div class="mjtc-cp-addon-list">
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-agent');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Agent','majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/agent.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Agents', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if ( !in_array('agent',majesticsupport::$_active_addons)) { ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-agent/majestic-support-agent.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-agent&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/agents/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-agent',$cdnversiondata->majesticsupportagent);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-autoclose');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Auto Close Ticket','majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/autoclose.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Auto Close Ticket', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if ( !in_array('autoclose',majesticsupport::$_active_addons)) { ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-autoclose/majestic-support-autoclose.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-autoclose&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/close-ticket/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-autoclose',$cdnversiondata->majesticsupportautoclose);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-feedback');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Feedback', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/feedback.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Feedback', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('feedback', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-feedback/majestic-support-feedback.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-feedback&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/feedback/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-feedback',$cdnversiondata->majesticsupportfeedback);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-helptopic');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Help Topics', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/helptopic.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Help Topics', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('helptopic', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-helptopic/majestic-support-helptopic.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-helptopic&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/helptopic/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-helptopic',$cdnversiondata->majesticsupporthelptopic);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-note');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Private Note', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/note.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Private Note', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('note', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-note/majestic-support-note.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-note&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/internal-note/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-note',$cdnversiondata->majesticsupportnote);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-knowledgebase');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Knowledge Base', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/knowledgebase.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Knowledge Base', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('knowledgebase', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-knowledgebase/majestic-support-knowledgebase.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-knowledgebase&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/knowledge-base/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-knowledgebase',$cdnversiondata->majesticsupportknowledgebase);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-maxticket');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Max Ticket', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/maxticket.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Max Tickets', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('maxticket', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-maxticket/majestic-support-maxticket.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-maxticket&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/max-ticket/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-maxticket',$cdnversiondata->majesticsupportmaxticket);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-mergeticket');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Merge Ticket', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/mergeticket.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Merge Tickets', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('mergeticket', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-mergeticket/majestic-support-mergeticket.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-mergeticket&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/merge-ticket/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-mergeticket',$cdnversiondata->majesticsupportmergeticket);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-overdue');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Overdue', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/overdue.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Overdue', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('overdue', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-overdue/majestic-support-overdue.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-overdue&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/overdue/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-overdue',$cdnversiondata->majesticsupportoverdue);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-smtp');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('SMTP', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/smtp.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('SMTP', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('smtp', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-smtp/majestic-support-smtp.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-smtp&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/smtp/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-smtp',$cdnversiondata->majesticsupportsmtp);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-tickethistory');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Ticket History', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/tickethistory.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Ticket History', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('tickethistory', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-tickethistory/majestic-support-tickethistory.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-tickethistory&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/ticket-history/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-tickethistory',$cdnversiondata->majesticsupporttickethistory);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-cannedresponses');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Premade Responses', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/cannedresponses.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Premade Responses', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('cannedresponses', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-cannedresponses/majestic-support-cannedresponses.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-cannedresponses&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/canned-responses/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-cannedresponses',$cdnversiondata->majesticsupportcannedresponses);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-emailpiping');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Email Piping', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/emailpiping.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Email Piping', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('emailpiping', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-emailpiping/majestic-support-emailpiping.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-emailpiping&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/email-piping/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-emailpiping',$cdnversiondata->majesticsupportemailpiping);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-timetracking');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Time Tracking', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/timetracking.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Time Tracking', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('timetracking', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-timetracking/majestic-support-timetracking.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-timetracking&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/time-tracking/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-timetracking',$cdnversiondata->majesticsupporttimetracking);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-useroptions');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('User Options', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/useroptions.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('User Options', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('useroptions', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-useroptions/majestic-support-useroptions.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-useroptions&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/user-options/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-useroptions',$cdnversiondata->majesticsupportuseroptions);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-actions');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Actions', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/actions.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Ticket Actions', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('actions', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-actions/majestic-support-actions.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-actions&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/actions/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-actions',$cdnversiondata->majesticsupportactions);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-announcement');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Announcements', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/announcement.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Announcements', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('announcement', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-announcement/majestic-support-announcement.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-announcement&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/announcements/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-announcement',$cdnversiondata->majesticsupportannouncement);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-banemail');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Ban Email', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/banemail.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Ban Email', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('banemail', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-banemail/majestic-support-banemail.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-banemail&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/ban-email/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-banemail',$cdnversiondata->majesticsupportbanemail);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-notification');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Desktop Notifications', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/notification.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Desktop Notifications', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('notification', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-notification/majestic-support-notification.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-notification&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/desktop-notification/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-notification',$cdnversiondata->majesticsupportnotification);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-export');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Export', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/export.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Export', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('export', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-export/majestic-support-export.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-export&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/export/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-export',$cdnversiondata->majesticsupportexport);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-download');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Downloads', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/download.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Downloads', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('download', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-download/majestic-support-download.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-download&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/downloads/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-download',$cdnversiondata->majesticsupportdownload);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-faq');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__("FAQs", 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/faq.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__("FAQs", 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('faq', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-faq/majestic-support-faq.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-faq&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/faq/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-faq',$cdnversiondata->majesticsupportfaq);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-dashboardwidgets');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Admin Widgets', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/dashboardwidgets.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Admin Widgets', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('dashboardwidgets', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-dashboardwidgets/majestic-support-dashboardwidgets.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-dashboardwidgets&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/admin-widget/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-dashboardwidgets',$cdnversiondata->majesticsupportdashboardwidgets);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-mail');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Internal Mail', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/mail.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Internal Mail', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('mail', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-mail/majestic-support-mail.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-mail&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/internal-mail/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-mail',$cdnversiondata->majesticsupportmail);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-widgets');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Front-End Widgets', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/widgets.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Front-End Widgets', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('widgets', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-widgets/majestic-support-widgets.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-widgets&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/widget/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-widgets',$cdnversiondata->majesticsupportwidgets);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-woocommerce');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('WooCommerce', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/woocommerce.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('WooCommerce', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('woocommerce', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-woocommerce/majestic-support-woocommerce.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-woocommerce&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/woocommerce/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-woocommerce',$cdnversiondata->majesticsupportwoocommerce);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-privatecredentials');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Private Credentials', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/privatecredentials.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Private Credentials', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('privatecredentials', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-privatecredentials/majestic-support-privatecredentials.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-privatecredentials&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/private-credentials/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-privatecredentials',$cdnversiondata->majesticsupportprivatecredentials);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-envatovalidation');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('envato', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/envatovalidation.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Envato Validation', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('envatovalidation', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-envatovalidation/majestic-support-envatovalidation.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-envatovalidation&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/envato/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-envatovalidation',$cdnversiondata->majesticsupportenvato);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-mailchimp');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('mailchimp', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/mailchimp.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Mailchimp', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('mailchimp', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-mailchimp/majestic-support-mailchimp.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-mailchimp&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/mail-chimp/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-mailchimp',$cdnversiondata->majesticsupportmailchimp);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-paidsupport');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('paidsupport', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/paidsupport.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Paid Support', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('paidsupport', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-paidsupport/majestic-support-paidsupport.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-paidsupport&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/paid-support/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-paidsupport',$cdnversiondata->majesticsupportpaidsupport);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-easydigitaldownloads');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('easy digital download', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/easydigitaldownloads.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Easy Digital Download', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('easydigitaldownloads', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-easydigitaldownloads/majestic-support-easydigitaldownloads.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-easydigitaldownloads&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/easy-digital-download/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-easydigitaldownloads',$cdnversiondata->majesticsupportedd);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-multilanguageemailtemplates');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Multi Language Email Templates', 'majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/multilanguageemailtemplates.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Multi Language Email Templates', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if(!in_array('multilanguageemailtemplates', majesticsupport::$_active_addons)){ ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-multilanguageemailtemplates/majestic-support-multilanguageemailtemplates.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-multilanguageemailtemplates&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/multi-language-email-templates";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-multilanguageemailtemplates',$cdnversiondata->majesticsupportmultilanguagetemplates);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-emailcc');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Email Cc','majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/emailcc.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Email Cc', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if ( !in_array('emailcc',majesticsupport::$_active_addons)) { ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-emailcc/majestic-support-emailcc.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-emailcc&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/emailcc/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-emailcc',$cdnversiondata->majesticsupportemailcc);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-multiform');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Multi Forms','majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/multiform.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Multi Forms', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if ( !in_array('multiform',majesticsupport::$_active_addons)) { ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-multiform/majestic-support-multiform.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-multiform&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/multiform/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-multiform',$cdnversiondata->majesticsupportmultiform);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-agentautoassign');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Agent Auto Assign','majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/agentautoassign.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Agent Auto Assign', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if ( !in_array('agentautoassign',majesticsupport::$_active_addons)) { ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-agentautoassign/majestic-support-agentautoassign.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-agentautoassign&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/agentautoassign/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-agentautoassign',$cdnversiondata->majesticsupportagentautoassign);
                                ?>
                            </div>
                            <?php
                                $addonBackground = '';
                                $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo('majestic-support-ticketclosereason');
                            ?>
                            <div class="mjtc-cp-addon" style="background-color: <?php echo esc_attr($addonBackground); ?>;">
                                <div class="mjtc-cp-addon-image">
                                    <img alt="<?php echo esc_html(__('Ticket Closed Reason','majestic-support')); ?>" class="mjtc-cp-addon-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/addon/ticketclosereason.png"/>
                                </div>
                                <div class="mjtc-cp-addon-cnt">
                                    <div class="mjtc-cp-addon-tit">
                                        <?php echo esc_html(__('Ticket Closed Reason', 'majestic-support')); ?>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <span class="mjtc-cp-addon-desc-title">
                                            <?php echo esc_html(__('Status', 'majestic-support')).': '; ?>
                                        </span>
                                        <span class="mjtc-cp-addon-desc-value">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['status'])); ?>
                                        </span>
                                    </div>
                                    <div class="mjtc-cp-addon-desc">
                                        <?php echo esc_html(__('Version', 'majestic-support')).': '; ?>
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($addoneinfo['version'])); ?>
                                    </div>
                                </div>
                                <?php if ( !in_array('ticketclosereason',majesticsupport::$_active_addons)) { ?>
                                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-ticketclosereason/majestic-support-ticketclosereason.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=majestic-support-ticketclosereason&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://majesticsupport.com/product/ticketclosereason/";
                                    } ?>
                                    <a href="<?php echo esc_url($url); ?>" class="mjtc-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo esc_html($text); ?>
                                    </a>
                                <?php }
                                mjtc_printAddoneStatus('majestic-support-ticketclosereason',$cdnversiondata->majesticsupportticketclosereason);
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php /* } */ ?>
                </div>

                <div class="mjtc-cp-cnt-sec mjtc-cp-video-baner">
                    <div class="mjtc-cp-video-baner-cnt">
                        <div class="mjtc-cp-video-baner-tit">
                            <?php echo esc_html(__('Quick Installation Guide','majestic-support')); ?>
                        </div>
                        <div class="mjtc-cp-video-baner-btn-wrp">
                            <a target="_blank" href="https://www.youtube.com/watch?v=lHAacpG-O0M&t=16s&ab_channel=MajesticSupport"
                                class="mjtc-cp-video-baner-btn mjtc-cp-video-baner-1">
                                <img alt="<?php echo esc_html(__('arrow','majestic-support')); ?>"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/play-btn.png" />
                                <?php echo esc_html(__('How To Setup','majestic-support')); ?>
                            </a>
                            <a target="_blank" href="https://www.youtube.com/watch?v=JrdZLoGiHsA&ab_channel=MajesticSupport"
                                class="mjtc-cp-video-baner-btn mjtc-cp-video-baner-2">
                                <img alt="<?php echo esc_html(__('arrow','majestic-support')); ?>"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/play-btn.png" />
                                <?php echo esc_html(__('System Emails','majestic-support')); ?>
                            </a>
                            <a target="_blank" href="https://www.youtube.com/watch?v=dYniAnKyv-Q&ab_channel=MajesticSupport"
                                class="mjtc-cp-video-baner-btn mjtc-cp-video-baner-3">
                                <img alt="<?php echo esc_html(__('arrow','majestic-support')); ?>"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/play-btn.png" />
                                <?php echo esc_html(__('Ticket Creation','majestic-support')); ?>
                            </a>
                            <a target="_blank" href="https://www.youtube.com/watch?v=8dIMdKuTLx4&t=6s&ab_channel=MajesticSupport"
                                class="mjtc-cp-video-baner-btn mjtc-cp-video-baner-4">
                                <img alt="<?php echo esc_html(__('arrow','majestic-support')); ?>"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/play-btn.png" />
                                <?php echo esc_html(__('Fields Manager','majestic-support')); ?>
                            </a>
                            <a target="_blank" href="#"
                                class="mjtc-cp-video-baner-btn mjtc-cp-video-baner-5">
                                <img alt="<?php echo esc_html(__('arrow','majestic-support')); ?>"
                                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/play-btn.png" />
                                <?php echo esc_html(__('Email Notification Problems','majestic-support')); ?>
                            </a>
                        </div>

                    </div>
                    <img class="mjtc-cp-video-baner-close-img" alt="<?php echo esc_html(__('close','majestic-support')); ?>"
                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/close-red-bg.png" />
                </div>
                <?php
                    $bg_no = rand(1,4);
                    $class = "mjtc-cp-baner-bg0".$bg_no;
                ?>
                <div class="mjtc-cp-cnt-sec mjtc-cp-baner <?php echo esc_attr($class); ?>">
                    <div class="mjtc-cp-baner-inner-wrp">
                        <div class="mjtc-cp-baner-icon-wrp">
                            <img class="mjtc-cp-baner-icon mjtc-cp-baner-icon-puzzel-img" alt="<?php echo esc_html(__('addon','majestic-support')); ?>"
                            src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/banner/addon-icon.png" />
                        </div>
                        <div class="mjtc-cp-baner-cnt">
                            <div class="mjtc-cp-banner-tit-bold">
                                <?php $data = esc_html(__('Premium Addons List','majestic-support')).' & '.esc_html(__('Features','majestic-support'));
                                echo esc_html($data); ?>
                            </div>
                            <div class="mjtc-cp-banner-desc">
                                <?php echo esc_html(__('The best support system plugin for WordPress has everything you need.','majestic-support')); ?>
                            </div>
                            <div class="mjtc-cp-banner-btn-wrp">
                                <a href="?page=majesticsupport_premiumplugin&mjslay=addonfeatures" class="mjtc-cp-banner-btn">
                                    <img alt="<?php echo esc_html(__('Addons','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/banner/addon-list.png" />
                                    <?php echo esc_html(__('All Add-ons','majestic-support')); ?>
                                </a>
                                <a href="?page=majesticsupport_premiumplugin&mjslay=step1" class="mjtc-cp-banner-btn">
                                    <img alt="<?php echo esc_html(__('Add','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/banner/add-adon.png" />
                                    <?php echo esc_html(__('Add New Add-ons','majestic-support')); ?>
                                </a>
                            </div>
                        </div>
                        <img class="mjtc-cp-baner-img" alt="<?php echo esc_html(__('addon','majestic-support')); ?>"
                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/banner/addon-image.png" />
                        <img class="mjtc-cp-baner-img2" alt="<?php echo esc_html(__('addon','majestic-support')); ?>"
                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/admincp/banner/addon-image-2.png" />
                    </div>
                </div>
            </div>
        </div>
        <?php
        $majesticsupport_js ="
            jQuery(document).ready(function() {
                jQuery('span.dashboard-icon').find('span.download').hover(function() {
                    jQuery(this).find('span').toggle('slide');
                }, function() {
                    jQuery(this).find('span').toggle('slide');
                });

                jQuery('a.mjtc-support-stats-link').click(function(e) {
                    e.preventDefault();
                    var list = jQuery(this).attr('data-tab-number');
                    var oldUrl = jQuery(this).attr('href');
                    var newUrl = oldUrl + '&list=' + list;
                    window.location.href = newUrl;
                });
            });

        ";
        wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
        ?>  
    </div>
</div>
<?php
function mjtc_printAddoneStatus($key1,$cdnversion){
    $matched = 0;
    $version = "";
    $installed_plugins = get_plugins();
    foreach ($installed_plugins as $name => $value) {
        $install_plugin_name = MJTC_majesticsupportphplib::MJTC_str_replace(".php","",MJTC_majesticsupportphplib::MJTC_basename($name));
        if($key1 == $install_plugin_name){
            $matched = 1;
            $version = $value["Version"];
            $install_plugin_matched_name = $install_plugin_name;
        }
    }
    if($matched == 1){ //installed
        $name = $key1;
        $title = 'auto close';
        $img = MJTC_majesticsupportphplib::MJTC_str_replace("majestic-support-", "", $key1).'.png';
        if($cdnversion > $version){ // new version available
            $status = 'update_available';
        }else{
            $status = 'updated';
        }
    } else {
        $status = '';
    }


    $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo($name);
    if ($status == 'update_available') {
        $wrpclass = 'ms-admin-addon-status ms-admin-addons-status-update-wrp';
        $btnclass = 'ms-admin-addons-update-btn';
        $btntxt = 'Update Now';
        $btnlink = 'id="ms-admin-addons-update" data-for="'.esc_attr($name).'"';
        $msg = '<span id="ms-admin-addon-status-cdnversion">'.esc_html(__('New Update Version','majestic-support'));
        $msg .= '';
        $msg .= esc_html(__('is Available','majestic-support')).'</span>';
    } elseif ($status == 'expired') {
        $wrpclass = 'ms-admin-addon-status ms-admin-addons-status-expired-wrp';
        $btnclass = 'ms-admin-addons-expired-btn';
        $btntxt = 'Expired';
        $btnlink = '';
        $msg = '';
    } elseif ($status == 'updated') {
        $wrpclass = 'ms-admin-addon-status';
        $btnclass = 'ms-admin-addons-updated-btn';
        $btntxt = 'Updated';
        $btnlink = '';
        $msg = '';
    } else {
        $wrpclass = 'ms-admin-addon-status';
        $btnclass = 'ms-admin-addons-buy-btn';
        $btntxt = 'Buy Now';
        $btnlink = 'href="https://majesticsupport.com/add-ons/"';
        $msg = '';
    }
    $html = '
    <div class="mjtc-cp-addon-msg-wrp">
        <span class="mjtc-cp-addon-msg '.esc_attr($btnclass).'">
            <a '.esc_attr($btnlink).' >'.esc_html(majesticsupport::MJTC_getVarValue($btntxt)).'</a>
        </span>
    </div>';
    echo wp_kses($html, MJTC_ALLOWED_TAGS);
}
$majesticsupport_js ="
jQuery(document).ready(function() {
    jQuery(document).on('click', 'a.mjtc-btn-install-now', function() {
        jQuery(this).attr('disabled', true);
        jQuery(this).html('Installing.....!');
        jQuery(this).removeClass('mjtc-btn-install-now');
        var pluginslug = jQuery(this).attr('data-slug');
        var buttonclass = jQuery(this).attr('class');
        jQuery(this).addClass('mjtc-installing-effect');
        if (pluginslug != '') {
            jQuery.post(ajaxurl, {
                action: 'mjsupport_ajax',
                mjsmod: 'majesticsupport',
                task: 'installPluginFromAjax',
                pluginslug: pluginslug,
                '_wpnonce': '". esc_attr(wp_create_nonce('install-plugin-ajax'))."'
            }, function(data) {
                if (data == 1) {
                    jQuery('span.mjtc-product-install-btn a.' + buttonclass).attr('disabled',
                        false);
                    jQuery('span.mjtc-product-install-btn a.' + buttonclass).html('Active Now');
                    jQuery('span.mjtc-product-install-btn a.' + buttonclass).addClass(
                        'mjtc-btn-active-now mjtc-btn-green');
                    jQuery('span.mjtc-product-install-btn a.' + buttonclass).removeClass(
                        'mjtc-installing-effect');
                } else {
                    jQuery('span.mjtc-product-install-btn a.' + buttonclass).attr('disabled',
                        false);
                    jQuery('span.mjtc-product-install-btn a.' + buttonclass).html(
                        'Please try again');
                    jQuery('span.mjtc-product-install-btn a.' + buttonclass).addClass(
                        'mjtc-btn-install-now');
                    jQuery('span.mjtc-product-install-btn a.' + buttonclass).removeClass(
                        'mjtc-installing-effect');
                }
            });
        }
    });

    jQuery(document).on('click', 'a.mjtc-btn-active-now', function() {
        jQuery(this).attr('disabled', true);
        jQuery(this).html('Activating.....!');
        jQuery(this).removeClass('mjtc-btn-active-now');
        var pluginslug = jQuery(this).attr('data-slug');
        var buttonclass = jQuery(this).attr('class');
        if (pluginslug != '') {
            jQuery.post(ajaxurl, {
                action: 'mjsupport_ajax',
                mjsmod: 'majesticsupport',
                task: 'activatePluginFromAjax',
                pluginslug: pluginslug,
                '_wpnonce': '". esc_attr(wp_create_nonce('activate-plugin-ajax'))."'
            }, function(data) {
                if (data == 1) {
                    jQuery('a[data-slug=' + pluginslug + ']').html('Activated');
                    jQuery('a[data-slug=' + pluginslug + ']').addClass('mjtc-btn-activated');
                    window.location.reload();
                }
            });
        }
    });
});

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  
