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
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('erasedatarequests'); ?>
        <div id="msadmin-data-wrp">
            <form class="mjtc-filter-form" name="majesticsupportform" id="majesticsupportform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_gdpr&mjslay=erasedatarequests"),"erase-data-requests")); ?>">
                <?php echo wp_kses(MJTC_formfield::MJTC_text('email', majesticsupport::$_data['filter']['email'], array('placeholder' => esc_html(__('User Email', 'majestic-support')),'class' => 'mjtc-form-input-field')), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('MS_form_search', 'MS_SEARCH'), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('go', esc_html(__('Search', 'majestic-support')), array('class' => 'button mjtc-form-search')), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_button('reset', esc_html(__('Reset', 'majestic-support')), array('class' => 'button mjtc-form-reset', 'onclick' => 'resetFrom();')), MJTC_ALLOWED_TAGS); ?>
            </form>
            <?php if (!empty(majesticsupport::$_data[0])) { ?>
                <table id="majestic-support-table">
                    <tr class="majestic-support-table-heading">
                        <th class="left"><?php echo esc_html(__('Subject', 'majestic-support')); ?></th>
                        <th class="left"><?php echo esc_html(__('Message', 'majestic-support')); ?></th>
                        <th ><?php echo esc_html(__('Email', 'majestic-support')); ?></th>
                        <th><?php echo esc_html(__('Request Status', 'majestic-support')); ?></th>
                        <th><?php echo esc_html(__('Created', 'majestic-support')); ?></th>
                        <th><?php echo esc_html(__('Action', 'majestic-support')); ?></th>
                    </tr>
                    <?php
                    foreach (majesticsupport::$_data[0] AS $request) {
                        ?>
                        <tr>
                            <td class="left">
                                <span class="majestic-support-table-responsive-heading">
                                    <?php echo esc_html(__('Subject', 'majestic-support'));echo esc_html(" : "); ?>
                                </span>
                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($request->subject)); ?>
                            </td>
                            <td class="left">
                                <span class="majestic-support-table-responsive-heading">
                                    <?php echo esc_html(__('Message', 'majestic-support'));echo esc_html(" : "); ?>
                                </span>
                                <?php echo wp_kses($request->message, MJTC_ALLOWED_TAGS); ?>
                            </td>
                            <td>
                                <span class="majestic-support-table-responsive-heading">
                                    <?php echo esc_html(__('Email', 'majestic-support')); echo esc_html(" : "); ?>
                                </span>
                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($request->user_email)); ?>
                            </td>
                            <td>
                                <span class="majestic-support-table-responsive-heading">
                                    <?php echo esc_html(__('Request Status', 'majestic-support')); echo esc_html(" : "); ?>
                                </span>
                                <?php
                                    if($request->status == 1){
                                        echo esc_html(__('Awaiting response','majestic-support'));
                                    }elseif($request->status == 2){
                                        echo esc_html(__('Erased identifying data','majestic-support'));
                                    }else{
                                        echo esc_html(__('Deleted','majestic-support'));
                                    }
                                ?>
                            </td>
                            <td>
                                <span class="majestic-support-table-responsive-heading">
                                    <?php echo esc_html(__('Created', 'majestic-support'));echo esc_html(" : "); ?>
                                </span>
                                <?php echo esc_html(date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime($request->created))); ?>
                            </td>
                            <td>
                                <a title="<?php echo esc_attr(__('Erase identifying data', 'majestic-support'));?>" class="action-btn" onclick="return confirm('<?php echo esc_html(__('Are you sure to erase identifying data', 'majestic-support')); ?>');" href="<?php echo esc_url(wp_nonce_url('?page=majesticsupport_gdpr&task=eraseidentifyinguserdata&action=mstask&majesticsupportid='.esc_attr($request->uid),'erase-userdata'));?>">
                                    <?php echo esc_html(__('Erase identifying data', 'majestic-support'));?>
                                </a>
                                <a title="<?php echo esc_attr(__('Delete data', 'majestic-support'));?>" class="action-btn" onclick="return confirm('<?php echo esc_html(__('Are you sure you want to delete it?', 'majestic-support')); ?>');" href="<?php echo esc_url(wp_nonce_url('?page=majesticsupport_gdpr&task=deleteuserdata&action=mstask&majesticsupportid='.esc_attr($request->uid),'delete-userdata'));?>">
                                    <?php echo esc_html(__('Delete data', 'majestic-support'));?>
                                </a>
                            </td>
                        </tr>
                    <?php
                }
                ?>
                </table>
                <?php
                if (majesticsupport::$_data[1]) {
                    $reqData = '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . wp_kses_post(majesticsupport::$_data[1]) . '</div></div>';
                    echo wp_kses($reqData, MJTC_ALLOWED_TAGS);
                }
            } else {
                MJTC_layout::MJTC_getNoRecordFound();
            }
            ?>
    </div>
</div>
