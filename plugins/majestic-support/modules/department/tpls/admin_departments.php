<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$majesticsupport_js ="
    function resetFrom() {
        document.getElementById('departmentname').value = '';
        document.getElementById('majesticsupportform').submit();
    }

    jQuery(document).ready(function () {
        jQuery('div#jsvm_full_background').click(function () {
            searchclosePopup();
        });

        jQuery('table#majestic-support-table tbody').sortable({
            handle : '.ms-order-grab-column',
            update  : function () {
                jQuery('.mjtc-form-button').slideDown('slow');
                var abc =  jQuery('table#majestic-support-table tbody').sortable('serialize');
                jQuery('input#fields_ordering_new').val(abc);
            }
        });
    });

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  

<?php
wp_enqueue_script('jquery-ui-sortable');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_style('majesticsupport-jquery-ui-css', MJTC_PLUGIN_URL . 'includes/css/jquery-ui-smoothness.css');
MJTC_message::MJTC_getMessage(); ?>
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('depertments'); ?>
        <div id="msadmin-data-wrp">
            <form class="mjtc-filter-form" name="majesticsupportform" id="majesticsupportform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_department&mjslay=departments"),"departments")); ?>">
                <?php echo wp_kses(MJTC_formfield::MJTC_text('departmentname', majesticsupport::$_data['filter']['departmentname'], array('placeholder' => esc_html(__('Department Name', 'majestic-support')),'class' => 'mjtc-form-input-field')), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('MS_form_search', 'MS_SEARCH'), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('go', esc_html(__('Search', 'majestic-support')), array('class' => 'button mjtc-form-search')), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_button('reset', esc_html(__('Reset', 'majestic-support')), array('class' => 'button mjtc-form-reset', 'onclick' => 'resetFrom();')), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_select('pagesize', array((object) array('id'=>20,'text'=>20), (object) array('id'=>50,'text'=>50), (object) array('id'=>100,'text'=>100)), majesticsupport::$_data['filter']['pagesize'],esc_html(__("Records per page",'majestic-support')), array('class' => 'mjtc-form-select-field mjtc-right','onchange'=>'document.majesticsupportform.submit();')), MJTC_ALLOWED_TAGS); ?>
            </form>
            <?php if (!empty(majesticsupport::$_data[0])) { ?>
                <form class="msadmin-form" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_majesticsupport&task=saveordering"),"save-ordering")); ?>">
                    <table id="majestic-support-table">
                        <tr class="majestic-support-table-heading">
                            <th><?php echo esc_html(__('Ordering', 'majestic-support')); ?></th>
                            <th class="left"><?php echo esc_html(__('Department Name', 'majestic-support')); ?></th>
                            <th class="left"><?php echo esc_html(__('Outgoing Email', 'majestic-support')); ?></th>
                            <th><?php echo esc_html(__('Default', 'majestic-support')); ?></th>
                            <th><?php echo esc_html(__('Status', 'majestic-support')); ?></th>
                            <th><?php echo esc_html(__('Created', 'majestic-support')); ?></th>
                            <th><?php echo esc_html(__('Action', 'majestic-support')); ?></th>
                        </tr>
                        <?php
                        $number = 0;
                        $count = COUNT(majesticsupport::$_data[0]) - 1; //For zero base indexing
                        $pagenum = MJTC_request::MJTC_getVar('pagenum', 'get', 1);
                        $islastordershow = MJTC_pagination::MJTC_isLastOrdering(majesticsupport::$_data['total'], $pagenum);
                        foreach (majesticsupport::$_data[0] AS $department) {
                            if ($department->isdefault == 1) {
                                $default = 'good.png';
                            } elseif ($department->isdefault == 2) {
                                $default = 'double_tick.png';
                            } else {
                                $default = 'close.png';
                            }
                            $status = ($department->status == 1) ? 'good.png' : 'close.png';
                            ?>
                            <tr id="id_<?php echo esc_attr($department->id); ?>" style="width: 100%;" >
                                <td class="mjtc-textaligncenter ms-order-grab-column">
                                    <span class="majestic-support-table-responsive-heading">
                                        <?php echo esc_html(__('Ordering', 'majestic-support')); echo esc_html(" : "); ?>
                                    </span>
                                    <img alt="<?php echo esc_html(__('grab','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL) . 'includes/images/list-full.png'?>"/>
                                </td>
                                <td class="left ms-left-row"><span class="majestic-support-table-responsive-heading"><?php echo esc_html(__('Department', 'majestic-support'));
                        echo esc_html(" : "); ?></span><a title="<?php echo esc_attr(__('Department','majestic-support')); ?>" href="?page=majesticsupport_department&mjslay=adddepartment&majesticsupportid=<?php echo esc_attr($department->id); ?>"><?php echo esc_html(majesticsupport::MJTC_getVarValue($department->departmentname)); ?></a></td>
                                <td class="left"><span class="majestic-support-table-responsive-heading"><?php echo esc_html(__('Outgoing Email', 'majestic-support'));
                        echo esc_html(" : "); ?></span><?php echo esc_html($department->outgoingemail); ?></td>
                                <td><span class="majestic-support-table-responsive-heading"><?php echo esc_html(__('Status', 'majestic-support'));
                                echo esc_html(" : "); ?></span>
                                <?php if($department->isdefault == 2){ ?>
                                    <a title="<?php echo esc_attr(__('Default','majestic-support')); ?>"> <img alt="<?php echo esc_html(__('Default','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL)  .'includes/images/' . esc_attr($default); ?>"/> </a>
                                <?php }else{ ?>
                                    <a title="<?php echo esc_attr(__('Default','majestic-support')); ?>" href="<?php echo esc_url(wp_nonce_url('?page=majesticsupport_department&task=changedefault&action=mstask&departmentid='. esc_attr($department->id) .'&default='. esc_attr($department->isdefault), 'change-default'));?>"> <img alt="<?php echo esc_html(__('Default','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL  .'includes/images/' . esc_attr($default)); ?>"/> </a>
                                <?php } ?>
                                </td>
                                <td><span class="majestic-support-table-responsive-heading"><?php echo esc_html(__('Status', 'majestic-support'));
                                echo esc_html(" : "); ?></span><a title="<?php echo esc_attr(__('Status','majestic-support')); ?>" href="<?php echo esc_url(wp_nonce_url('?page=majesticsupport_department&task=changestatus&action=mstask&departmentid='.esc_attr($department->id),'change-status'));?>"> <img alt="<?php echo esc_html(__('Status','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL . 'includes/images/' . esc_attr($status)); ?>"/> </a></td>
                                <td><span class="majestic-support-table-responsive-heading"><?php echo esc_html(__('Created', 'majestic-support'));
                        echo esc_html(" : "); ?></span><?php echo esc_html(date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime($department->created))); ?></td>
                                <td>
                                    <a title="<?php echo esc_attr(__('Edit','majestic-support')); ?>" class="action-btn" href="?page=majesticsupport_department&mjslay=adddepartment&majesticsupportid=<?php echo esc_attr($department->id); ?>"><img alt="<?php echo esc_html(__('Edit','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/edit.png" /></a>&nbsp;&nbsp;
                                    <a title="<?php echo esc_attr(__('Delete','majestic-support')); ?>" class="action-btn" onclick="return confirm('<?php echo esc_html(__('Are you sure you want to delete it?', 'majestic-support')); ?>');" href="<?php echo esc_url(wp_nonce_url('?page=majesticsupport_department&task=deletedepartment&action=mstask&departmentid='.esc_attr($department->id),'delete-department'));?>"><img alt="<?php echo esc_html(__('Delete','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/delete.png" /></a></td>
                            </tr>
                        <?php
                        $number++;
                }
                    ?>
                    </table>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('fields_ordering_new', '123'), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('ordering_for', 'department'), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('pagenum_for_ordering', MJTC_request::MJTC_getVar('pagenum', 'get', 1)), MJTC_ALLOWED_TAGS); ?>
                    <div class="mjtc-form-button" style="display: none;">
                        <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('save', esc_html(__('Save Ordering', 'majestic-support')), array('class' => 'button mjtc-form-save')), MJTC_ALLOWED_TAGS); ?>
                    </div>
                </form>
                <?php
                if (majesticsupport::$_data[1]) {
                    $deptData = '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(majesticsupport::$_data[1]) . '</div></div>';
                    echo wp_kses($deptData, MJTC_ALLOWED_TAGS);
                }
            } else {
                MJTC_layout::MJTC_getNoRecordFound();
            }
            ?>
        </div>
    </div>
</div>
