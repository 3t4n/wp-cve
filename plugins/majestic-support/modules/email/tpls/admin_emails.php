<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$majesticsupport_js ="
    function resetFrom() {
        document.getElementById('email').value = '';
        document.getElementById('majesticsupportform').submit();
    }

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  
<?php MJTC_message::MJTC_getMessage(); ?>
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('systememails'); ?>
        <div id="msadmin-data-wrp">
            <form class="mjtc-filter-form" name="majesticsupportform" id="majesticsupportform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_email&mjslay=emails"),"emails")); ?>">
                <?php echo wp_kses(MJTC_formfield::MJTC_text('email', majesticsupport::$_data['filter']['email'], array('placeholder' => esc_html(__('Email', 'majestic-support')),'class' => 'mjtc-form-input-field')), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('MS_form_search', 'MS_SEARCH'), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('go', esc_html(__('Search', 'majestic-support')), array('class' => 'button mjtc-form-search')), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_button('reset', esc_html(__('Reset', 'majestic-support')), array('class' => 'button mjtc-form-reset', 'onclick' => 'resetFrom();')), MJTC_ALLOWED_TAGS); ?>
            </form>
            <span id="mjtc-systemail" class="mjtc-admin-infotitle"><img alt="<?php echo esc_attr(__('info','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/infoicon.png" /><?php echo esc_html(__('System email used for sending email', 'majestic-support')); ?></span>
            <?php if (!empty(majesticsupport::$_data[0])) { ?>
            <table id="majestic-support-table">
                <tr class="majestic-support-table-heading">
                    <th class="left w60"><?php echo esc_html(__('Email Address', 'majestic-support')); ?></th>
                    <th><?php echo esc_html(__('Auto Response', 'majestic-support')); ?></th>
                    <th><?php echo esc_html(__('Created', 'majestic-support')); ?></th>
                    <th><?php echo esc_html(__('Action', 'majestic-support')); ?></th>
                </tr>
                <?php
                foreach (majesticsupport::$_data[0] AS $email) {
                    $autoresponse = ($email->autoresponse == 1) ? 'good.png' : 'close.png';
                    ?>
                    <tr>
                        <td class="left w60"><span class="majestic-support-table-responsive-heading"><?php echo esc_html(__('Email Address', 'majestic-support'));
                echo esc_html(" : "); ?></span><a title="<?php echo esc_attr(__('Email','majestic-support')); ?>" href="?page=majesticsupport_email&mjslay=addemail&majesticsupportid=<?php echo esc_attr($email->id); ?>"><?php echo esc_html($email->email); ?></a></td>
                        <td><span class="majestic-support-table-responsive-heading"><?php echo esc_html(__('Auto Response', 'majestic-support'));
                echo esc_html(" : "); ?></span><img alt="<?php echo esc_html(__('Auto Response','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/<?php echo esc_attr($autoresponse); ?>" /></td>
                        <td><span class="majestic-support-table-responsive-heading"><?php echo esc_html(__('Created', 'majestic-support'));
                echo esc_html(" : "); ?></span><?php echo esc_html(date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime($email->created))); ?></td>
                        <td >
                            <a title="<?php echo esc_attr(__('Edit','majestic-support')); ?>" class="action-btn" href="?page=majesticsupport_email&mjslay=addemail&majesticsupportid=<?php echo esc_attr($email->id); ?>"><img alt="<?php echo esc_html(__('Edit','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/edit.png" /></a>
                            <a title="<?php echo esc_attr(__('Delete','majestic-support')); ?>" class="action-btn" onclick="return confirm('<?php echo esc_html(__('Are you sure you want to delete it?', 'majestic-support')); ?>');" href="<?php echo esc_url(wp_nonce_url('?page=majesticsupport_email&task=deleteemail&action=mstask&emailid=' .esc_attr($email->id),'delete-email')); ?>"><img alt="<?php echo esc_html(__('Delete','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/delete.png" /></a>
                        </td>
                    </tr>
                <?php }
            ?>
            </table>
            <?php
            if (majesticsupport::$_data[1]) {
                $emailData = '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(majesticsupport::$_data[1]) . '</div></div>';
                echo wp_kses($emailData, MJTC_ALLOWED_TAGS);
            }
        } else {// User is guest
            MJTC_layout::MJTC_getNoRecordFound();
        }
        ?>
        </div>
    </div>
</div>
