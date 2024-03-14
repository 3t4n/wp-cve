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
                <?php
                $majesticsupport_js ="
                    function resetFrom() {
                        document.getElementById('ms-title').value = '';
                        return true;
                    }

                ";
                wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
                ?>  
                <?php MJTC_message::MJTC_getMessage(); ?>
                <?php include_once(MJTC_PLUGIN_PATH . 'includes/header.php'); ?>
                <div class="mjtc-support-top-sec-header">
                    <img class="mjtc-transparent-header-img1" alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/tp-image.png" />
                    <div class="mjtc-support-top-sec-left-header">
                        <div class="mjtc-support-main-heading">
                            <?php echo esc_html(__("Smart Replies",'majestic-support')); ?>
                        </div>
                        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageBreadcrumps('smartreplies'); ?>
                    </div>
                    <div class="mjtc-support-top-sec-right-header">
                        <a <?php echo esc_attr($id); ?> href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'smartreply', 'mjslay'=>'addsmartreply'))); ?>"
                            class="mjtc-support-button-header"><?php echo esc_html(__("Add Smart Reply",'majestic-support')); ?>
                        </a>
                    </div>
                </div>
                <div class="mjtc-support-cont-main-wrapper mjtc-support-cont-main-wrapper-with-btn">
                    <div class="mjtc-support-cont-wrapper mjtc-support-cont-wrapper-color">
                        <div class="mjtc-support-smartreplies-wrapper">
                            <div class="mjtc-support-top-search-wrp">
                                <div class="mjtc-support-search-fields-wrp">
                                    <form class="mjtc-filter-form" name="majesticsupportform" id="majesticsupportform" method="POST" action="<?php echo esc_url(wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'smartreply', 'mjslay'=>'smartreplies')),"smart-replies")); ?>">
                                        <div class="mjtc-support-fields-wrp">
                                            <div class="mjtc-support-form-field mjtc-support-form-field-download-search">
                                                <?php echo wp_kses(MJTC_formfield::MJTC_text('ms-title', majesticsupport::parseSpaces(majesticsupport::$_data['filter']['ms-title']), array('placeholder' => esc_html(__('Search', 'majestic-support')), 'class' => 'mjtc-support-field-input')), MJTC_ALLOWED_TAGS); ?>
                                            </div>
                                            <div class="mjtc-support-search-form-btn-wrp mjtc-support-search-form-btn-wrp-download ">
                                                <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('ms-go', esc_html(__('Search', 'majestic-support')), array('class' => 'mjtc-search-button')), MJTC_ALLOWED_TAGS); ?>
                                                <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('ms-reset', esc_html(__('Reset', 'majestic-support')), array('class' => 'mjtc-reset-button', 'onclick' => 'return resetFrom();')), MJTC_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('MS_form_search', 'MS_SEARCH'), MJTC_ALLOWED_TAGS); ?>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('mspageid', get_the_ID()), MJTC_ALLOWED_TAGS); ?>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('mjtcslay', 'smartreplies'), MJTC_ALLOWED_TAGS); ?>
                                    </form>
                                </div>
                            </div>
                            <?php
                            if (!empty(majesticsupport::$_data[0])) { ?>
                                <div class="mjtc-support-download-content-wrp">
                                    <div class="mjtc-support-table-wrp">
                                        <?php
                                        foreach (majesticsupport::$_data[0] AS $smartreply) { ?>
                                            <div class="ms-smart-reply-listing-wrp">
                                                <div class="ms-smart-reply-listing-head">
                                                    <div class="ms-smart-reply-listing-head-left">
                                                        <a title="<?php echo esc_attr(__('Title','majestic-support')); ?>" href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'smartreply', 'mjslay'=>'addsmartreply', 'majesticsupportid'=>$smartreply->id))); ?>">
                                                            <?php echo esc_html(majesticsupport::MJTC_getVarValue($smartreply->title)); ?>
                                                        </a>
                                                    </div>
                                                    <div class="ms-smart-reply-listing-head-right">
                                                        <a title="<?php echo esc_attr(__('Edit','majestic-support')); ?>" class="action-btn" href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'smartreply', 'mjslay'=>'addsmartreply', 'majesticsupportid'=>$smartreply->id))); ?>"><img alt="<?php echo esc_html(__('Edit','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/edit.png" /></a>&nbsp;&nbsp;
                                                        <a title="<?php echo esc_attr(__('Delete','majestic-support')); ?>" class="action-btn" onclick="return confirm('<?php echo esc_html(__('Are you sure you want to delete it?', 'majestic-support')); ?>');" href="<?php echo esc_url(wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'smartreply', 'task'=>'deletesmartreply', 'action'=>'mstask', 'smartreplyid'=>$smartreply->id, 'mspageid'=>get_the_ID())),'delete-smartreply')); ?>"><img alt="<?php echo esc_html(__('Delete','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/delete.png" /></a>
                                                    </div>
                                                </div>
                                                <div class="ms-smart-reply-listing-body">
                                                    <?php 
                                                    $ticketsubject = preg_replace("/\\\\'/", "'", $smartreply->ticketsubjects);

                                                    $ticketsubjects = json_decode($ticketsubject);
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
                                        } ?>
                                    </div>
                                </div>
                                </div>
                                <?php
                                if (majesticsupport::$_data[1]) {
                                    $data = '<div class="tablenav"><div class="tablenav-pages" >' . wp_kses_post(majesticsupport::$_data[1]) . '</div></div>';
                                    echo wp_kses($data, MJTC_ALLOWED_TAGS);
                                }
                            } else { // Record Not FOund
                                MJTC_layout::MJTC_getNoRecordFound();
                            }
                } else {
                    MJTC_layout::MJTC_getStaffMemberDisable();
                }
        } else { // user not Staff
            MJTC_layout::MJTC_getNotStaffMember();
        }
    } else {// User is guest
        $redirect_url = majesticsupport::makeUrl(array('mjsmod'=>'role','mjslay'=>'roles'));
        $redirect_url = MJTC_majesticsupportphplib::MJTC_safe_encoding($redirect_url);
        MJTC_layout::MJTC_getUserGuest($redirect_url);
    }
    } else { // User permission not granted
        MJTC_layout::MJTC_getPermissionNotGranted();
    }
} else { // System is offline
    MJTC_layout::MJTC_getSystemOffline();
}
?>

        </div>
    </div>
</div>