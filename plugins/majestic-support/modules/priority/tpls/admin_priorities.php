<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$majesticsupport_js ="
    function resetFrom() {
        document.getElementById('title').value = '';
        document.getElementById('majesticsupportform').submit();
    }
    jQuery(document).ready(function () {
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
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('priorities'); ?>
        <div id="msadmin-data-wrp">
            <form class="mjtc-filter-form" name="majesticsupportform" id="majesticsupportform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_priority&mjslay=priorities"),"priorities")); ?>">
                <?php echo wp_kses(MJTC_formfield::MJTC_text('title', majesticsupport::$_data['filter']['title'], array('placeholder' => esc_html(__('Title', 'majestic-support')),'class' => 'mjtc-form-input-field')), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('MS_form_search', 'MS_SEARCH'), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('go', esc_html(__('Search', 'majestic-support')), array('class' => 'button mjtc-form-search')), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_button(esc_html(__('Reset', 'majestic-support')), esc_html(__('Reset', 'majestic-support')), array('class' => 'button mjtc-form-reset', 'onclick' => 'resetFrom();')), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_select('pagesize', array((object) array('id'=>20,'text'=>20), (object) array('id'=>50,'text'=>50), (object) array('id'=>100,'text'=>100)), majesticsupport::$_data['filter']['pagesize'],esc_html(__("Records per page",'majestic-support')), array('class' => 'mjtc-form-select-field mjtc-right','onchange'=>'document.majesticsupportform.submit();')), MJTC_ALLOWED_TAGS); ?>
            </form>
            <?php if (!empty(majesticsupport::$_data[0])) { ?>
                <form class="msadmin-form" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_majesticsupport&task=saveordering"),"save-ordering")); ?>">
                    <table id="majestic-support-table">
                        <thead>
                        <tr class="majestic-support-table-heading">
                            <th><?php echo esc_html(__('Ordering', 'majestic-support')); ?></th>
                            <th class="left"><?php echo esc_html(__('Title', 'majestic-support')); ?></th>
                            <?php if(in_array('overdue', majesticsupport::$_active_addons)){ ?>
                                <th>
                                    <?php echo esc_html(__('Date Interval', 'majestic-support')); ?>&nbsp;<?php $data = '('.esc_html(__('Days', 'majestic-support')).'/'.esc_html(__('Hours', 'majestic-support')).')'; 
                                        echo wp_kses($data, MJTC_ALLOWED_TAGS);
                                    ?>
                                </th>
                                <th><?php echo esc_html(__('Ticket Overdue', 'majestic-support')); ?></th>
                            <?php } ?>
                            <th><?php echo esc_html(__('Public', 'majestic-support')); ?></th>
                            <th><?php echo esc_html(__('Default', 'majestic-support')); ?></th>
                            <th><?php echo esc_html(__('Order', 'majestic-support')); ?></th>
                            <th><?php echo esc_html(__('Action', 'majestic-support')); ?></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $number = 0;
                        $count = COUNT(majesticsupport::$_data[0]) - 1; //For zero base indexing
                        $pagenum = MJTC_request::MJTC_getVar('pagenum', 'get', 1);
                        $islastordershow = MJTC_pagination::MJTC_isLastOrdering(majesticsupport::$_data['total'], $pagenum);
                        foreach (majesticsupport::$_data[0] AS $priority) {
                            $isdefault = ($priority->isdefault == 1) ? 'good.png' : 'close.png';
                            $ispublic = ($priority->ispublic == 1) ? 'good.png' : 'close.png';
                            $ticketoverduetype = ($priority->overduetypeid == 1) ? 'Days' : 'Hours';
                            ?>

                            <tr id="id_<?php echo esc_attr($priority->id); ?>">
                                <td class="mjtc-textaligncenter ms-order-grab-column">
                                    <span class="majestic-support-table-responsive-heading">
                                        <?php echo esc_html(__('Ordering', 'majestic-support')); echo esc_html(" : "); ?>
                                    </span>
                                    <img alt="<?php echo esc_html(__('grab','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL) . 'includes/images/list-full.png'?>"/>
                                </td>

                                <td class="left"><span class="majestic-support-table-responsive-heading"><?php
                                        echo esc_html(__('Title', 'majestic-support'));
                                        echo esc_html(" : ");
                                        ?></span><a title="<?php echo esc_attr(__('Priority','majestic-support')); ?>" href="?page=majesticsupport_priority&mjslay=addpriority&majesticsupportid=<?php echo esc_attr($priority->id); ?>"><?php echo esc_html(majesticsupport::MJTC_getVarValue($priority->priority)); ?></a></td>
                                <?php if(in_array('overdue', majesticsupport::$_active_addons)){ ?>
                                    <td><span class="majestic-support-table-responsive-heading"><?php
                                        echo esc_html(__('Date Interval', 'majestic-support'));
                                        echo esc_html(" : ");
                                        ?></span><?php echo esc_html(majesticsupport::MJTC_getVarValue($priority->overdueinterval)); ?></td>
                                    <td><span class="majestic-support-table-responsive-heading"><?php
                                        echo esc_html(__('Ticket Overdue', 'majestic-support'));
                                        echo esc_html(" : ");
                                        ?></span><?php echo esc_html(majesticsupport::MJTC_getVarValue($ticketoverduetype)); ?></td>
                                <?php } ?>
                                <td><span class="majestic-support-table-responsive-heading"><?php
                                        echo esc_html(__('Public', 'majestic-support'));
                                        echo esc_html(" : ");
                                        ?></span> <img alt="<?php echo esc_html(__('Public','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/<?php echo esc_attr($ispublic); ?>" /></td>
                                <td><span class="majestic-support-table-responsive-heading"><?php
                                    echo esc_html(__('Default', 'majestic-support'));
                                    echo esc_html(" : ");
                                    ?></span>
                                    <?php $url = '?page=majesticsupport_priority&task=makedefault&action=mstask&priorityid='.esc_attr($priority->id);
                                    if($pagenum > 1){
                                        $url .= '&pagenum=' . $pagenum;
                                    }?><a title="<?php echo esc_attr(__('Default','majestic-support')); ?>" href="<?php echo esc_url(wp_nonce_url($url, 'make-default')); ?>" ><img alt="<?php echo esc_html(__('Default','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/<?php echo esc_attr($isdefault); ?>" /></a></td>
                                <td><span class="majestic-support-table-responsive-heading"><?php
                            echo esc_html(__('Color', 'majestic-support'));
                            echo esc_html(" : ");
                            ?></span> <span class="mjtc-support-admin-prirrity-color" style="background:<?php echo esc_attr($priority->prioritycolour); ?>;color:#ffffff;"> <?php echo esc_html($priority->prioritycolour); ?></span></td>
                                <td>
                                    <a title="<?php echo esc_attr(__('Edit','majestic-support')); ?>" class="action-btn" href="?page=majesticsupport_priority&mjslay=addpriority&majesticsupportid=<?php echo esc_attr($priority->id); ?>"><img alt="<?php echo esc_html(__('Edit','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/edit.png" /></a>&nbsp;&nbsp;
                                    <a title="<?php echo esc_attr(__('Delete','majestic-support')); ?>" class="action-btn" onclick="return confirm('<?php echo esc_html(__('Are you sure you want to delete it?', 'majestic-support')); ?>');" href="<?php echo esc_url(wp_nonce_url('?page=majesticsupport_priority&task=deletepriority&action=mstask&priorityid='.esc_attr($priority->id),'delete-priority'));?>"><img alt="<?php echo esc_html(__('Delete','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/delete.png" /></a>
                                </td>
                            </tr>
                        <?php
                        $number++;
                    }
                    ?>
                    </tbody>
                    </table>
                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('fields_ordering_new', '123'), MJTC_ALLOWED_TAGS); ?>
                       <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                       <?php echo wp_kses(MJTC_formfield::MJTC_hidden('ordering_for', 'priority'), MJTC_ALLOWED_TAGS); ?>
                       <?php echo wp_kses(MJTC_formfield::MJTC_hidden('pagenum_for_ordering', MJTC_request::MJTC_getVar('pagenum', 'get', 1)), MJTC_ALLOWED_TAGS); ?>
                       <div class="mjtc-form-button" style="display: none;">
                           <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('save', esc_html(__('Save Ordering', 'majestic-support')), array('class' => 'button mjtc-form-save')), MJTC_ALLOWED_TAGS); ?>
                       </div>
                   </form>
                <?php
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
