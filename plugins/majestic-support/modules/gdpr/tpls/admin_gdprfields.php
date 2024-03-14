<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php MJTC_message::MJTC_getMessage(); ?>
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('gdpr'); ?>
        <div id="msadmin-data-wrp">
            <?php if (!empty(majesticsupport::$_data[0])) { ?>
                <table id="majestic-support-table">
                    <tr class="majestic-support-table-heading">
                        <th class="left"><?php echo esc_html(__('Field Title', 'majestic-support')); ?></th>
                        <th><?php echo esc_html(__('Field Text', 'majestic-support')); ?></th>
                        <th><?php echo esc_html(__('Required', 'majestic-support')); ?></th>
                        <th><?php echo esc_html(__('Ordering', 'majestic-support')); ?></th>
                        <th><?php echo esc_html(__('Link Type', 'majestic-support')); ?></th>
                        <th><?php echo esc_html(__('Link', 'majestic-support')); ?></th>
                        <th><?php echo esc_html(__('Action', 'majestic-support')); ?></th>
                    </tr>
                    <?php
                    foreach (majesticsupport::$_data[0] AS $field) {
                        $termsandconditions_text = '';
                        $termsandconditions_linktype = '';
                        $termsandconditions_link = '';
                        $termsandconditions_page = '';
                        if(isset($field->userfieldparams) && $field->userfieldparams != '' ){
                            $userfieldparams = json_decode($field->userfieldparams,true);
                            $termsandconditions_text = isset($userfieldparams['termsandconditions_text']) ? $userfieldparams['termsandconditions_text'] :'' ;
                            $termsandconditions_linktype = isset($userfieldparams['termsandconditions_linktype']) ? $userfieldparams['termsandconditions_linktype'] :'' ;
                            $termsandconditions_link = isset($userfieldparams['termsandconditions_link']) ? $userfieldparams['termsandconditions_link'] :'' ;
                            $termsandconditions_page = isset($userfieldparams['termsandconditions_page']) ? $userfieldparams['termsandconditions_page'] :'' ;
                            if($termsandconditions_linktype == 2){
                                $page_title_link = get_the_title($termsandconditions_page);
                            }else{
                                $page_title_link = $termsandconditions_link;
                            }
                        }?>
                        <tr class="mjtc-filter-form-data">
                            <td class="left">
                                <span class="majestic-support-table-responsive-heading">
                                    <?php echo esc_html(__('Field Title', 'majestic-support'));echo esc_html(" : "); ?>
                                </span>
                                <a href="?page=majesticsupport_gdpr&mjslay=addgdprfield&majesticsupportid=<?php echo esc_attr($field->id); ?>" title="<?php echo esc_attr(__('Field Title','majestic-support')); ?>">
                                    <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?>
                                </a>
                            </td>
                            <td>
                                <span class="majestic-support-table-responsive-heading">
                                    <?php echo esc_html(__('Field Text', 'majestic-support'));echo esc_html(" : "); ?>
                                </span>
                                <?php echo esc_html($termsandconditions_text); ?>
                            </td>
                            <td>
                                <span class="majestic-support-table-responsive-heading">
                                    <?php echo esc_html(__('Required', 'majestic-support'));echo esc_html(" : "); ?>
                                </span>
                                <?php if ($field->required == 1) { ?>
                                    <img alt="<?php echo esc_html(__('good','majestic-support')); ?>" height="15" width="15" src="<?php echo esc_url(MJTC_PLUGIN_URL) . 'includes/images/good.png'; ?>" />
                                <?php }else{ ?>
                                    <img alt="<?php echo esc_html(__('Close','majestic-support')); ?>" height="15" width="15" src="<?php echo esc_url(MJTC_PLUGIN_URL) . 'includes/images/close.png'; ?>" />
                                <?php } ?>
                            </td>
                            <td>
                                <span class="majestic-support-table-responsive-heading">
                                    <?php echo esc_html(__('Ordering', 'majestic-support')); echo esc_html(" : "); ?>
                                </span>
                                <?php  echo esc_html($field->ordering); ?>
                            </td>
                            <td>
                                <span class="majestic-support-table-responsive-heading">
                                    <?php echo esc_html(__('Link Type', 'majestic-support')); echo esc_html(" : "); ?>
                                </span>
                                <?php if($termsandconditions_linktype == 2){
                                    echo esc_html(__('Wordpress Page','majestic-support'));
                                }else if($termsandconditions_linktype == 1){
                                    echo esc_html(__('Direct URL','majestic-support'));
                                } ?>
                            </td>
                            <td>
                                <span class="majestic-support-table-responsive-heading">
                                    <?php echo esc_html(__('Page Title or URL', 'majestic-support')); echo esc_html(" : "); ?>
                                </span>
                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($page_title_link)); ?>
                            </td>
                            <td>
                                <a title="<?php echo esc_attr(__('Edit','majestic-support')); ?>" class="action-btn" href="?page=majesticsupport_gdpr&mjslay=addgdprfield&majesticsupportid=<?php echo esc_attr($field->id); ?>"><img alt="<?php echo esc_attr(__('Edit','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/edit.png" /></a>&nbsp;&nbsp;
                                <a title="<?php echo esc_attr(__('Delete','majestic-support')); ?>" class="action-btn" onclick="return confirm('<?php echo esc_html(__('Are you sure you want to delete it?', 'majestic-support')); ?>');" href="<?php echo esc_url(wp_nonce_url('?page=majesticsupport_gdpr&task=deletegdpr&action=mstask&gdprid='.esc_attr($field->id),'delete-gdpr'));?>"><img alt="<?php echo esc_html(__('Delete','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/delete.png" /></a>
                            </td>
                        </tr>
                    <?php
            }
                ?>
                </table>
        </div>
            <?php
        } else {
            MJTC_layout::MJTC_getNoRecordFound();
        }
        ?>
    </div>
</div>
