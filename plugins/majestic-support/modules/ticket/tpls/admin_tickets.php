<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('majesticsupport-jquery-ui-css', MJTC_PLUGIN_URL . 'includes/css/jquery-ui-smoothness.css');
    wp_enqueue_style('majesticsupport-status-graph', MJTC_PLUGIN_URL . 'includes/css/status_graph.css');
?>
<?php
$majesticsupport_js ="
    function resetFrom() {
        var form = jQuery('form#majesticsupportform');
        form.find('input[type=text], input[type=email], input[type=password], textarea').val('');
        form.find('input:checkbox').removeAttr('checked');
        form.find('select').prop('selectedIndex', 0);
        form.find('input[type=\'radio\']').prop('checked', false);
        document.getElementById('majesticsupportform').submit();
    }
    jQuery(document).ready(function(){
        jQuery('.date,.custom_date').datepicker({dateFormat: 'yy-mm-dd'});
        jQuery('select.mjtc-admin-sort-select').on('change',function(e){
            e.preventDefault();
            var sortby = jQuery('.mjtc-admin-sort-select option:selected').val();
            jQuery('input#sortby').val(sortby);
            jQuery('form#majesticsupportform').submit();
        });
        jQuery('a.mjtc-admin-sort-btn').on('click',function(e){
            e.preventDefault();
            var sortby = jQuery('.mjtc-admin-sort-select option:selected').val();
            jQuery('input#sortby').val(sortby);
            jQuery('form#majesticsupportform').submit();
        });
        jQuery('a.mjtc-support-link').click(function(e){
            e.preventDefault();
            var list = jQuery(this).attr('data-tab-number');
            jQuery('input#list').val(list);
            jQuery('form#majesticsupportform').submit();
        });
        jQuery('span.mjtc-support-closedby-wrp').hover(
            function(e){
                jQuery(this).find('span.mjtc-support-closed-date').css('display','inline-block');
            },
            function(e){
                jQuery(this).find('span.mjtc-support-closed-date').css('display','none');
            }
        );
    });

    function setDepartmentFilter( depid ){
        jQuery('#departmentid').val( depid );
        jQuery('form#majesticsupportform').submit();
    }

    function setFromNameFilter( email ){
        jQuery('#email').val( email );
        jQuery('form#majesticsupportform').submit();
    }

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
MJTC_message::MJTC_getMessage(); ?>
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php
        if(current_user_can('ms_support_ticket')){
            MJTC_includer::MJTC_getClassesInclude('msadminsidemenu');
        }
        ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('admin_tickets'); ?>
        <div id="msadmin-data-wrp" class="p0 bg-n bs-n b0">
            <?php
            $list = MJTC_request::MJTC_getVar('list', null, null);
            if($list == null){
                $list = majesticsupport::$_search['ticket']['list'];
            }
            $open = ($list == 1) ? 'active' : '';
            $answered = ($list == 2) ? 'active' : '';
            $overdue = ($list == 3) ? 'active' : '';
            $closed = ($list == 4) ? 'active' : '';
            $alltickets = ($list == 5) ? 'active' : '';
            $field_array = MJTC_includer::MJTC_getModel('fieldordering')->getFieldTitleByFieldfor(1);
            ?>
            <?php
            $open_percentage = 0;
            $close_percentage = 0;
            $overdue_percentage = 0;
            $answered_percentage = 0;
            $allticket_percentage = 0;
            if(isset(majesticsupport::$_data['count']) && isset(majesticsupport::$_data['count']['allticket']) && majesticsupport::$_data['count']['allticket'] != 0){
                $open_percentage = round((majesticsupport::$_data['count']['openticket'] / majesticsupport::$_data['count']['allticket']) * 100);
                $close_percentage = round((majesticsupport::$_data['count']['closedticket'] / majesticsupport::$_data['count']['allticket']) * 100);
                $overdue_percentage = round((majesticsupport::$_data['count']['overdueticket'] / majesticsupport::$_data['count']['allticket']) * 100);
                $answered_percentage = round((majesticsupport::$_data['count']['answeredticket'] / majesticsupport::$_data['count']['allticket']) * 100);
            }
            if(isset(majesticsupport::$_data['count']) && isset(majesticsupport::$_data['count']['allticket']) && majesticsupport::$_data['count']['allticket'] != 0){
                $allticket_percentage = 100;
            }
            ?>
            <div class="mjtc-support-count">
                <div class="mjtc-support-link">
                    <a class="mjtc-support-link <?php echo esc_attr($open); ?> mjtc-support-green" href="#" data-tab-number="1" title="<?php echo esc_attr(__('Open Ticket','majestic-support')); ?>">
                        <div class="mjtc-support-cricle-wrp" data-per="<?php echo esc_attr($open_percentage); ?>" >
                            <div class="mjtc-mr-rp" data-progress="<?php echo esc_attr($open_percentage); ?>">
                                <div class="circle">
                                    <div class="mask full">
                                         <div class="fill mjtc-support-open"></div>
                                    </div>
                                    <div class="mask half">
                                        <div class="fill mjtc-support-open"></div>
                                        <div class="fill fix"></div>
                                    </div>
                                    <div class="shadow"></div>
                                </div>
                                <div class="inset">
                                </div>
                            </div>
                        </div>
                        <div class="mjtc-support-link-text mjtc-support-green">
                            <?php
                                echo esc_html(__('Open', 'majestic-support'));
                                if(majesticsupport::$_config['count_on_myticket'] == 1){
                                    $data = ' ( '.esc_html(majesticsupport::$_data['count']['openticket']).' )';
                                    echo wp_kses($data, MJTC_ALLOWED_TAGS);
                                }
                            ?>
                        </div>
                    </a>
                </div>
                <div class="mjtc-support-link">
                    <a class="mjtc-support-link <?php echo esc_attr($answered); ?> mjtc-support-brown" href="#" data-tab-number="2" title="<?php echo esc_attr(__('answered ticket','majestic-support')); ?>">
                        <div class="mjtc-support-cricle-wrp" data-per="<?php echo esc_attr($answered_percentage); ?>" >
                            <div class="mjtc-mr-rp" data-progress="<?php echo esc_attr($answered_percentage); ?>">
                                <div class="circle">
                                    <div class="mask full">
                                         <div class="fill mjtc-support-answer"></div>
                                    </div>
                                    <div class="mask half">
                                        <div class="fill mjtc-support-answer"></div>
                                        <div class="fill fix"></div>
                                    </div>
                                    <div class="shadow"></div>
                                </div>
                                <div class="inset">
                                </div>
                            </div>
                        </div>
                        <div class="mjtc-support-link-text mjtc-support-brown">
                            <?php
                                echo esc_html(__('Answered', 'majestic-support'));
                                if(majesticsupport::$_config['count_on_myticket'] == 1){
                                    $data = ' ( '.esc_html(majesticsupport::$_data['count']['answeredticket']).' )';
                                    echo wp_kses($data, MJTC_ALLOWED_TAGS);
                                }
                            ?>
                        </div>
                    </a>
                </div>
                <?php if(in_array('overdue', majesticsupport::$_active_addons)){ ?>
                    <div class="mjtc-support-link">
                        <a class="mjtc-support-link <?php echo esc_attr($overdue); ?> mjtc-support-orange" href="#" data-tab-number="3" title="<?php echo esc_attr(__('overdue ticket','majestic-support')); ?>">
                            <div class="mjtc-support-cricle-wrp" data-per="<?php echo esc_attr($overdue_percentage); ?>" >
                                <div class="mjtc-mr-rp" data-progress="<?php echo esc_attr($overdue_percentage); ?>">
                                    <div class="circle">
                                        <div class="mask full">
                                             <div class="fill mjtc-support-overdue"></div>
                                        </div>
                                        <div class="mask half">
                                            <div class="fill mjtc-support-overdue"></div>
                                            <div class="fill fix"></div>
                                        </div>
                                        <div class="shadow"></div>
                                    </div>
                                    <div class="inset">
                                    </div>
                                </div>
                            </div>
                            <div class="mjtc-support-link-text mjtc-support-orange">
                                <?php
                                    echo esc_html(__('Overdue', 'majestic-support'));
                                    if(majesticsupport::$_config['count_on_myticket'] == 1){
                                        $data = ' ( '.esc_html(majesticsupport::$_data['count']['overdueticket']).' )';
                                        echo wp_kses($data, MJTC_ALLOWED_TAGS);
                                    }
                                ?>
                            </div>
                        </a>
                    </div>
                <?php } ?>
                <div class="mjtc-support-link">
                    <a class="mjtc-support-link <?php echo esc_attr($closed); ?> mjtc-support-red" href="#" data-tab-number="4" title="<?php echo esc_attr(__('closed ticket','majestic-support')); ?>">
                        <div class="mjtc-support-cricle-wrp" data-per="<?php echo esc_attr($close_percentage); ?>" >
                            <div class="mjtc-mr-rp" data-progress="<?php echo esc_html($close_percentage); ?>">
                                <div class="circle">
                                    <div class="mask full">
                                         <div class="fill mjtc-support-close"></div>
                                    </div>
                                    <div class="mask half">
                                        <div class="fill mjtc-support-close"></div>
                                        <div class="fill fix"></div>
                                    </div>
                                    <div class="shadow"></div>
                                </div>
                                <div class="inset">
                                </div>
                            </div>
                        </div>
                        <div class="mjtc-support-link-text mjtc-support-red">
                            <?php
                                echo esc_html(__('Closed', 'majestic-support'));
                                if(majesticsupport::$_config['count_on_myticket'] == 1){
                                    $data = ' ( '.esc_html(majesticsupport::$_data['count']['closedticket']).' )';
                                    echo wp_kses($data, MJTC_ALLOWED_TAGS);
                                }
                            ?>
                        </div>
                    </a>
                </div>
                <div class="mjtc-support-link">
                    <a class="mjtc-support-link <?php echo esc_attr($alltickets); ?> mjtc-support-blue" href="#" data-tab-number="5" title="<?php echo esc_attr(__('All Tickets','majestic-support')); ?>">
                        <div class="mjtc-support-cricle-wrp" data-per="<?php echo esc_attr($allticket_percentage); ?>">
                            <div class="mjtc-mr-rp" data-progress="<?php echo esc_attr($allticket_percentage); ?>">
                                <div class="circle">
                                    <div class="mask full">
                                         <div class="fill mjtc-support-allticket"></div>
                                    </div>
                                    <div class="mask half">
                                        <div class="fill mjtc-support-allticket"></div>
                                        <div class="fill fix"></div>
                                    </div>
                                    <div class="shadow"></div>
                                </div>
                                <div class="inset">
                                </div>
                            </div>
                        </div>
                        <div class="mjtc-support-link-text mjtc-support-blue">
                            <?php
                                echo esc_html(__('All Tickets', 'majestic-support'));
                                if(majesticsupport::$_config['count_on_myticket'] == 1){
                                    $data = ' ( '.esc_html(majesticsupport::$_data['count']['allticket']).' )';
                                    echo wp_kses($data, MJTC_ALLOWED_TAGS);
                                }
                            ?>
                        </div>
                    </a>
                </div>
            </div>
            <?php
            $uid = MJTC_request::MJTC_getVar('uid',null,0);
            if(is_numeric($uid) && $uid){
                $formaction = wp_nonce_url(admin_url("admin.php?page=majesticsupport_ticket&mjslay=tickets&uid=".esc_attr($uid)),"my-ticket");
            }else{
                $formaction = wp_nonce_url(admin_url("admin.php?page=majesticsupport_ticket&mjslay=tickets"),"my-ticket");
            }
            ?>
            <form class="mjtc-filter-form mt0 mjtc-admin-ticket-filter mjtc-admin-ticket-filter-overall-wrapper " name="majesticsupportform" id="majesticsupportform" method="post" action="<?php echo esc_url($formaction); ?>">
                <?php
                if (isset($field_array['subject'])) {
                    echo wp_kses(MJTC_formfield::MJTC_text('subject', majesticsupport::$_data['filter']['subject'], array('placeholder' => majesticsupport::MJTC_getVarValue($field_array['subject']),'class' => 'mjtc-form-input-field')), MJTC_ALLOWED_TAGS);
                }
                if (isset($field_array['fullname'])) {
                    echo wp_kses(MJTC_formfield::MJTC_text('name', majesticsupport::$_data['filter']['name'], array('placeholder' => esc_html(__('Ticket Creator Name', 'majestic-support')),'class' => 'mjtc-form-input-field')), MJTC_ALLOWED_TAGS);
                }
                if (isset($field_array['email'])) {
                    echo wp_kses(MJTC_formfield::MJTC_text('email', majesticsupport::$_data['filter']['email'], array('placeholder' => majesticsupport::MJTC_getVarValue($field_array['email']),'class' => 'mjtc-form-input-field')), MJTC_ALLOWED_TAGS);
                }
                if ( in_array('agent',majesticsupport::$_active_addons)) {
                    echo wp_kses(MJTC_formfield::MJTC_select('staffid', MJTC_includer::MJTC_getModel('agent')->getStaffForCombobox(), majesticsupport::$_data['filter']['staffid'], esc_html(__('Select Agent','majestic-support')), array('class' => 'mjtc-form-select-field')), MJTC_ALLOWED_TAGS);
                }
                if (isset($field_array['department'])) {
                    echo wp_kses(MJTC_formfield::MJTC_select('departmentid', MJTC_includer::MJTC_getModel('department')->getDepartmentForCombobox(), majesticsupport::$_data['filter']['departmentid'], esc_html(__('Select','majestic-support')).' '.esc_attr(majesticsupport::MJTC_getVarValue($field_array['department'])), array('class' => 'mjtc-form-select-field')), MJTC_ALLOWED_TAGS);
                }
                if (isset($field_array['priority'])) {
                    echo wp_kses(MJTC_formfield::MJTC_select('priority', MJTC_includer::MJTC_getModel('priority')->getPriorityForCombobox(), majesticsupport::$_data['filter']['priority'], esc_html(__('Select','majestic-support')).' '.esc_attr(majesticsupport::MJTC_getVarValue($field_array['priority'])), array('class' => 'mjtc-form-select-field')), MJTC_ALLOWED_TAGS);
                }
                echo wp_kses(MJTC_formfield::MJTC_text('datestart', majesticsupport::$_data['filter']['datestart'], array('placeholder' => esc_html(__('From Date', 'majestic-support')), 'class' => 'date mjtc-form-date-field')), MJTC_ALLOWED_TAGS);
                echo wp_kses(MJTC_formfield::MJTC_text('dateend', majesticsupport::$_data['filter']['dateend'], array('placeholder' => esc_html(__('To Date', 'majestic-support')), 'class' => 'date mjtc-form-date-field')), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_text('ticketid', majesticsupport::$_data['filter']['ticketid'], array('placeholder' => esc_html(__('Ticket ID', 'majestic-support')),'class' => 'mjtc-form-input-field')), MJTC_ALLOWED_TAGS); ?>
                <?php if(class_exists('WooCommerce') && in_array('woocommerce', majesticsupport::$_active_addons)){  ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_text('orderid', majesticsupport::$_data['filter']['orderid'], array('placeholder' => majesticsupport::MJTC_getVarValue($field_array['wcorderid']),'class' => 'mjtc-form-input-field')), MJTC_ALLOWED_TAGS); ?>
                <?php } ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('MS_form_search', 'MS_SEARCH'), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('sortby', majesticsupport::$_data['filter']['sortby']), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('list', $list), MJTC_ALLOWED_TAGS); ?>

                <?php
                    $customfields = MJTC_includer::MJTC_getObjectClass('customfields')->userFieldsForSearch(1);
                    foreach ($customfields as $field) {
                        MJTC_includer::MJTC_getObjectClass('customfields')->MJTC_formCustomFieldsForSearch($field, $k, 1);
                    }
                ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('go', esc_html(__('Search', 'majestic-support')), array('class' => 'button mjtc-form-search')), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_button(esc_html(__('Reset', 'majestic-support')), esc_html(__('Reset', 'majestic-support')), array('class' => 'button mjtc-form-reset', 'onclick' => 'resetFrom();')), MJTC_ALLOWED_TAGS); ?>
            </form>
            <?php
            $link = '?page=majesticsupport_ticket';
            if (majesticsupport::$_sortorder == 'ASC')
                $img = "sorting-white-1.png";
            else
                $img = "sorting-white-2.png";
            ?>
            <div class="mjtc-admin-heading">
                <div class="mjtc-admin-head-txt">
                    <?php 
                    $list = majesticsupport::$_data['list'];
                    if($list == 1){
                        echo esc_html(__('Open','majestic-support')).' ';
                    } elseif ($list == 2){
                        echo esc_html(__('Answered','majestic-support')).' ';
                    } elseif ($list == 3){
                        echo esc_html(__('Overdue','majestic-support')).' ';
                    } elseif ($list == 5){
                        echo esc_html(__('All','majestic-support')).' ';
                    } elseif ($list == 4){
                        echo esc_html(__('Closed','majestic-support')).' ';
                    }?>
                    <?php echo esc_html(__('Tickets', 'majestic-support')); ?>
                </div>
                <div class="mjtc-admin-sorting">
                    <select class="mjtc-admin-sort-select">
                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['subject'])); ?>
                        <option value="<?php echo esc_attr(majesticsupport::$_sortlinks['subject']); ?>" <?php if (majesticsupport::$_sorton == 'subject') echo esc_attr('selected') ?>><?php echo esc_html(__("Subject",'majestic-support')); ?></option>
                        <?php
                        if (isset($field_array['priority'])) { ?>
                            <option value="<?php echo esc_attr(majesticsupport::$_sortlinks['priority']); ?>"  <?php if (majesticsupport::$_sorton == 'priority') echo esc_attr('selected') ?>><?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['priority'])); ?></option>
                        <?php } ?>
                        <option value="<?php echo esc_attr(majesticsupport::$_sortlinks['ticketid']); ?>"  <?php if (majesticsupport::$_sorton == 'ticketid') echo esc_attr('selected') ?>><?php echo esc_html(__("Ticket ID",'majestic-support')); ?></option>
                        <option value="<?php echo esc_attr(majesticsupport::$_sortlinks['isanswered']); ?>"  <?php if (majesticsupport::$_sorton == 'isanswered') echo esc_attr('selected') ?>><?php echo esc_html(__("Answered",'majestic-support')); ?></option>
                        <option value="<?php echo esc_attr(majesticsupport::$_sortlinks['status']); ?>"  <?php if (majesticsupport::$_sorton == 'status') echo esc_attr('selected') ?>><?php echo esc_html(__("Status",'majestic-support')); ?></option>
                        <option value="<?php echo esc_attr(majesticsupport::$_sortlinks['created']); ?>"  <?php if (majesticsupport::$_sorton == 'created') echo esc_attr('selected') ?>><?php echo esc_html(__("Created",'majestic-support')); ?></option>
                    </select>
                    <a href="#" class="mjtc-admin-sort-btn" title="<?php echo esc_attr(__('sort','majestic-support')); ?>">
                        <img alt="<?php echo esc_html(__('sort','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL . 'includes/images/' . $img) ?>">
                    </a>
                </div>
            </div>
            <?php
            if (!empty(majesticsupport::$_data[0])) {
                ?>
                <!-- Tabs Area -->
                <?php
                foreach (majesticsupport::$_data[0] AS $ticket) {
                    if ($ticket->status == 0) {
                        $style = "#159667;";
                        $status = esc_html(__('New', 'majestic-support'));
                    } elseif ($ticket->status == 1) {
                        $style = "#D78D39;";
                        $status = esc_html(__('Waiting Reply', 'majestic-support'));
                    } elseif ($ticket->status == 2) {
                        $style = "#EDA900;";
                        $status = esc_html(__('In Progress', 'majestic-support'));
                    } elseif ($ticket->status == 3) {
                        $style = "#2168A2;";
                        $status = esc_html(__('Replied', 'majestic-support'));
                    } elseif ($ticket->status == 4) {
                        $style = "#3D355A;";
                        $status = esc_html(__('Closed', 'majestic-support'));
                    } elseif ($ticket->status == 5) {
                        $style = "#E91E63;";
                        $status = esc_html(__('Close due to merge', 'majestic-support'));
                    }
                    $ticketviamail = '';
                    if ($ticket->ticketviaemail == 1)
                        $ticketviamail = esc_html(__('Created via Email', 'majestic-support'));
                    ?>
                    <div class="mjtc-support-wrapper">
                        <div class="mjtc-support-toparea">
                            <div class="mjtc-support-pic">
                                <?php echo wp_kses(ms_get_avatar(MJTC_includer::MJTC_getModel('majesticsupport')->getWPUidById($ticket->uid)), MJTC_ALLOWED_TAGS); ?>
                            </div>
                            <div class="mjtc-support-data">
                                <div class="mjtc-support-left">
                                    <div class="mjtc-support-data-row">
                                        <?php
                                        if (isset($field_array['fullname'])) { ?>
                                            <span class="mjtc-support-user" style="cursor:pointer;" onClick="setFromNameFilter('<?php echo esc_js($ticket->email); ?>');"><?php echo esc_html($ticket->name); ?></span>
                                        <?php 
                                        }
                                        if ($ticket->status == 4 && majesticsupport::$_config['show_closedby_on_admin_tickets'] == 1) { ?>
                                            <span class="mjtc-support-closedby-wrp">
                                                <span class="mjtc-support-closedby">
                                                    <?php echo esc_html(MJTC_includer::MJTC_getModel('ticket')->getClosedBy($ticket->closedby)); ?>
                                                </span>
                                                <span class="mjtc-support-closed-date">
                                                    <?php echo esc_html(__("Closed on", 'majestic-support')). " " . esc_html(date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime($ticket->closed))); ?>
                                                </span>
                                            </span>
                                        <?php } ?>
                                    </div>
                                    <div class="mjtc-support-data-row">
                                        <a title="<?php echo esc_attr(__('Subject','majestic-support')); ?>" class="mjtc-support-det-link" href="?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=<?php echo esc_attr($ticket->id); ?>"><?php echo esc_html($ticket->subject); ?></a>
                                    </div>
                                    <?php if (isset($field_array['department'])) { ?>
                                        <div class="mjtc-support-data-row">
                                            <div class="mjtc-support-data-row-rec">
                                                <span class="mjtc-support-title"><?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['department'])); ?>&nbsp;:&nbsp;</span>
                                                <span class="mjtc-support-value" style="cursor:pointer;" onClick="setDepartmentFilter('<?php echo esc_js($ticket->departmentid); ?>');"><?php echo esc_html(majesticsupport::MJTC_getVarValue($ticket->departmentname)); ?></span>
                                            </div>
                                        </div>
                                        <?php
                                    } ?>
                                    <?php
                                        majesticsupport::$_data['custom']['ticketid'] = $ticket->id;
                                        $customfields = MJTC_includer::MJTC_getObjectClass('customfields')->MJTC_userFieldsData(1, 1);
                                        foreach ($customfields as $field) {
                                            $ret = MJTC_includer::MJTC_getObjectClass('customfields')->MJTC_showCustomFields($field,1, $ticket->params);
                                            ?>
                                            <div class="mjtc-support-data-row mjtc-sprt-custm-flds-wrp">
                                                <div class="mjtc-support-data-row-rec">
                                                    <span class="mjtc-support-title"><?php echo esc_html($ret['title']); ?>&nbsp;:&nbsp;</span>
                                                    <span class="mjtc-support-value" style="cursor:pointer;"><?php echo wp_kses($ret['value'], MJTC_ALLOWED_TAGS); ?></span>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    ?>
                                </div>
                                <div class="mjtc-support-right">

                                    <span class="mjtc-support-value mjtc-support-creade-via-email-spn"><?php echo esc_html($ticketviamail); ?></span>
                                    <?php
                                    $counter = 'one';
                                    if ($ticket->lock == 1) { ?>
                                        <img class="ticketstatusimage <?php echo esc_attr($counter); $counter = 'two'; ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/lock.png"; ?>" alt="<?php echo esc_html(__('The ticket is locked', 'majestic-support')); ?>" title="<?php echo esc_attr(__('The ticket is locked', 'majestic-support')); ?>" />
                                    <?php } ?>
                                    <?php if ($ticket->isoverdue == 1) { ?>
                                        <img class="ticketstatusimage <?php echo esc_attr($counter); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL) . "includes/images/over-due.png"; ?>" alt="<?php echo esc_html(__('This ticket is marked as overdue', 'majestic-support')); ?>" title="<?php echo esc_attr(__('This ticket is marked as overdue', 'majestic-support')); ?>" />
                                    <?php } ?>
                                    <span class="mjtc-support-status" style="color:<?php echo esc_attr($style); ?>">
                                        <?php echo esc_html($status); ?>
                                    </span>
                                    <?php
                                    if (isset($field_array['priority'])) { ?>
                                        <span class="mjtc-support-priority mjtc-support-wrapper-textcolor" style="background:<?php echo esc_attr($ticket->prioritycolour); ?>;">
                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($ticket->priority)); ?>
                                        </span>
                                        <?php
                                    } ?>
                                    <div class="mjtc-support-data1">
                                        <div class="mjtc-support-data1-row">
                                            <div class="mjtc-support-data1-title"><?php echo esc_html(__('Ticket ID', 'majestic-support')).':'; ?></div>
                                            <div class="mjtc-support-data1-value"><?php echo esc_html($ticket->ticketid); ?></div>
                                        </div>
                                        <?php if (empty($ticket->lastreply) || $ticket->lastreply == '0000-00-00 00:00:00') { ?>
                                        <div class="mjtc-support-data1-row">
                                            <div class="mjtc-support-data1-title"><?php echo esc_html(__('Created', 'majestic-support')).':'; ?></div>
                                            <div class="mjtc-support-data1-value"><?php echo esc_html(date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))); ?></div>
                                        </div>
                                        <?php } else { ?>
                                        <div class="mjtc-support-data1-row">
                                            <div class="mjtc-support-data1-title"><?php echo esc_html(__('Last Reply', 'majestic-support')).':'; ?></div>
                                            <div class="mjtc-support-data1-value"><?php echo esc_html(date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime($ticket->lastreply))); ?></div>
                                        </div>
                                        <?php } ?>
                                        <?php if (in_array('agent',majesticsupport::$_active_addons) && majesticsupport::$_config['show_assignto_on_admin_tickets'] == 1 && isset($field_array['assignto'])) { ?>
                                            <div class="mjtc-support-data1-row">
                                                <div class="mjtc-support-data1-title"><?php echo esc_html(majesticsupport::MJTC_getVarValue($field_array['assignto'])); ?></div>
                                                <div class="mjtc-support-data1-value"><?php echo esc_html($ticket->staffname); ?></div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mjtc-support-bottom-data-part">
                            <div class="mjtc-support-datapart-buttons-action">
                                <a class="mjtc-support-datapart-action-btn button" title="<?php echo esc_attr(__('Edit Ticket', 'majestic-support')); ?>" href="?page=majesticsupport_ticket&mjslay=addticket&majesticsupportid=<?php echo esc_attr($ticket->id); ?>"><img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/edit-2.png" /><?php echo esc_html(__('Edit Ticket', 'majestic-support')); ?></a>
                                <a class="mjtc-support-datapart-action-btn button" title="<?php echo esc_attr(__('Delete Ticket', 'majestic-support')); ?>"  onclick="return confirm('<?php echo esc_html(__('Are you sure you want to delete it?', 'majestic-support')); ?>');" href="<?php echo esc_url(wp_nonce_url('?page=majesticsupport_ticket&task=deleteticket&action=mstask&internalid='.esc_attr($ticket->internalid).'&ticketid='.esc_attr($ticket->id),'delete-ticket'));?>">
                                    <img alt="<?php echo esc_html(__('Delete', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/delete-2.png" />
                                    <?php echo esc_html(__('Delete Ticket', 'majestic-support')); ?></a>
                                <a title="<?php echo esc_attr(__('Enforce delete', 'majestic-support')); ?>" class="mjtc-support-datapart-action-btn button"  onclick="return confirm('<?php echo esc_html(__('Are you sure to enforce delete', 'majestic-support')); ?>');" href="<?php echo esc_url(wp_nonce_url('?page=majesticsupport_ticket&task=enforcedeleteticket&action=mstask&ticketid='.esc_attr($ticket->id),'enforce-delete-ticket'))?>"><img src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/forced-delete.png" alt="<?php echo esc_html(__('Enforce delete', 'majestic-support')); ?>" /><?php echo esc_html(__('Enforce delete', 'majestic-support')); ?></a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                if (majesticsupport::$_data[1]) {
                    $data = '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(majesticsupport::$_data[1]) . '</div></div>';
                    echo wp_kses($data, MJTC_ALLOWED_TAGS);
                }
            } else {
                MJTC_layout::MJTC_getNoRecordFound();
            }
            ?>
        </div>
    </div>
</div>
