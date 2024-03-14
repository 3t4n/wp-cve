<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jsjob-res-tables', JSJOBS_PLUGIN_URL . 'includes/js/responsivetable.js');
?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php 
    $msgkey = JSJOBSincluder::getJSModel('systemerror')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    ?>
    <span class="js-admin-title">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Error Log', 'js-jobs'); ?>
    </span>
    <?php
    if (!empty(jsjobs::$_data[0])) {
        ?>
        <table id="js-table">
            <thead>
                <tr>
                    <th class="left-row"><?php echo __('Error', 'js-jobs'); ?></th>
                    <th ><?php echo __('View', 'js-jobs'); ?></th>
                    <th ><?php echo __('Date', 'js-jobs'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach (jsjobs::$_data[0] AS $systemerror) {
                    $isview = ($systemerror->isview == 1) ? 'no.png' : 'yes.png';
                    ?>
                    <tr valign="top">
                        <td class="left-row">
                            <?php echo esc_html($systemerror->error); ?>
                        </td>
                        <td>
                            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/<?php echo esc_attr($isview); ?>" />
                        </td>
                        <td>
                            <?php 
                                echo esc_html(date_i18n(jsjobs::$_configuration['date_format'], jsjobslib::jsjobs_strtotime($systemerror->created))); ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <?php
        if (jsjobs::$_data[1]) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(jsjobs::$_data[1]) . '</div></div>';
        }
    } else {
        $msg = __('No record found','js-jobs');
        JSJOBSlayout::getNoRecordFound($msg);
    }
    ?>
</div>
</div>

