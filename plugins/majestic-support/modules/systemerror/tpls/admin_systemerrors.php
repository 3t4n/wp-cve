<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$majesticsupport_js ="
    function resetFrom() {
        document.getElementById('error').value = '';
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
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('systemerror'); ?>
        <div id="msadmin-data-wrp">
            <?php
            if (!empty(majesticsupport::$_data[0])) {
                ?>
                <table id="majestic-support-table">
                    <tr class="majestic-support-table-heading">
                        <th class="left w70"><?php echo esc_html(__('Error', 'majestic-support')); ?></th>
                        <th><?php echo esc_html(__('Created', 'majestic-support')); ?></th>
                        <th><?php echo esc_html(__('Action', 'majestic-support')); ?></th>
                    </tr>
                    <?php
                    foreach (majesticsupport::$_data[0] AS $systemerror) {
                        $isview = ($systemerror->isview == 1) ? 'close.png' : 'good.png';
                        ?>
                        <tr>
                            <td class="left w70"><span class="majestic-support-table-responsive-heading"><?php
                                    echo esc_html(__('Error', 'majestic-support'));
                                    echo esc_html(" : ");
                                    ?></span><?php echo esc_html($systemerror->error); ?></td>
                            <td><span class="majestic-support-table-responsive-heading"><?php
                            echo esc_html(__('Created', 'majestic-support'));
                            echo esc_html(" : ");
                                    ?></span><?php echo esc_html(date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime($systemerror->created))); ?></td>
                            <td>
                                <a title="<?php echo esc_attr(__('Delete','majestic-support')); ?>" class="action-btn" onclick="return confirm('<?php echo esc_html(__('Are you sure you want to delete it?', 'majestic-support')); ?>');" href="<?php echo esc_url(wp_nonce_url('?page=majesticsupport_systemerror&task=deletesystemerror&action=mstask&systemerrorid='.esc_attr($systemerror->id),'delete-systemerror'));?>"><img alt="<?php echo esc_html(__('Delete','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/delete.png" /></a>
                            </td>
                        </tr>
                <?php }
                ?>
                </table>
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
