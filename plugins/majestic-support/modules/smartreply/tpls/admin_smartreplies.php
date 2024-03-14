<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$majesticsupport_js ="
    function resetFrom() {
        document.getElementById('title').value = '';
        document.getElementById('majesticsupportform').submit();
    }
";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  
<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_style('majesticsupport-jquery-ui-css', MJTC_PLUGIN_URL . 'includes/css/jquery-ui-smoothness.css');
MJTC_message::MJTC_getMessage(); ?>
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('smartreply'); ?>
        <div id="msadmin-data-wrp">
            <form class="mjtc-filter-form" name="majesticsupportform" id="majesticsupportform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_smartreply&mjslay=smartreplies"),"smart-replies")); ?>">
                <?php echo wp_kses(MJTC_formfield::MJTC_text('title', majesticsupport::$_data['filter']['title'], array('placeholder' => esc_html(__('Title', 'majestic-support')),'class' => 'mjtc-form-input-field')), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('MS_form_search', 'MS_SEARCH'), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('go', esc_html(__('Search', 'majestic-support')), array('class' => 'button mjtc-form-search')), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_button(esc_html(__('Reset', 'majestic-support')), esc_html(__('Reset', 'majestic-support')), array('class' => 'button mjtc-form-reset', 'onclick' => 'resetFrom();')), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_select('pagesize', array((object) array('id'=>20,'text'=>20), (object) array('id'=>50,'text'=>50), (object) array('id'=>100,'text'=>100)), majesticsupport::$_data['filter']['pagesize'],esc_html(__("Records per page",'majestic-support')), array('class' => 'mjtc-form-select-field mjtc-right','onchange'=>'document.majesticsupportform.submit();')), MJTC_ALLOWED_TAGS); ?>
            </form>
            <?php if (!empty(majesticsupport::$_data[0])) { ?>
                <form class="msadmin-form" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_majesticsupport&task=saveordering"),"save-ordering")); ?>">
                    <?php
                    $number = 0;
                    $pagenum = MJTC_request::MJTC_getVar('pagenum', 'get', 1);
                    $islastordershow = MJTC_pagination::MJTC_isLastOrdering(majesticsupport::$_data['total'], $pagenum);
                    foreach (majesticsupport::$_data[0] AS $smartreply) {
                        ?>
                        <div class="ms-smart-reply-listing-wrp">
                            <div class="ms-smart-reply-listing-head">
                                <div class="ms-smart-reply-listing-head-left">
                                    <a title="<?php echo esc_attr(__('Title','majestic-support')); ?>" href="?page=majesticsupport_smartreply&mjslay=addsmartreply&majesticsupportid=<?php echo esc_attr($smartreply->id); ?>">
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($smartreply->title)); ?>
                                    </a>
                                </div>
                                <div class="ms-smart-reply-listing-head-right">
                                    <a title="<?php echo esc_attr(__('Edit','majestic-support')); ?>" class="action-btn" href="?page=majesticsupport_smartreply&mjslay=addsmartreply&majesticsupportid=<?php echo esc_attr($smartreply->id); ?>"><img alt="<?php echo esc_html(__('Edit','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/edit.png" /></a>&nbsp;&nbsp;
                                    <a title="<?php echo esc_attr(__('Delete','majestic-support')); ?>" class="action-btn" onclick="return confirm('<?php echo esc_html(__('Are you sure you want to delete it?', 'majestic-support')); ?>');" href="<?php echo esc_url(wp_nonce_url('?page=majesticsupport_smartreply&task=deletesmartreply&action=mstask&smartreplyid='.esc_attr($smartreply->id),'delete-smartreply'));?>"><img alt="<?php echo esc_html(__('Delete','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/delete.png" /></a>
                                </div>
                            </div>
                            <div class="ms-smart-reply-listing-body">
                                <?php 
                                $ticketsubjects = json_decode($smartreply->ticketsubjects);
                                foreach ($ticketsubjects as $ticketsubject) { ?>
                                    <div class="ms-smart-reply-listing-ticket-subject">
                                        <?php echo esc_html(majesticsupport::MJTC_getVarValue($ticketsubject)); ?>
                                    </div>
                                <?php } ?>
                                <div class="ms-smart-reply-listing-ticket-reply">
                                    <img alt="<?php echo esc_html(__('Edit','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/smart-reply/text.png" />
                                    <span><?php echo esc_html(esc_html(majesticsupport::MJTC_getVarValue(MJTC_majesticsupportphplib::MJTC_strip_tags($smartreply->reply)))); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php
                        $number++;
                    }
                    ?>
                       <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
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
