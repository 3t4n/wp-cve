<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_View_EventDetails extends WADA_View_BaseForm
{
    const VIEW_IDENTIFIER = 'wada-event-details';
    public $item = null;

    public function __construct($id = null){
        $this->viewHeadline = __('Event details', 'wp-admin-audit');
        $this->parentHeadlineLink = admin_url('admin.php?page=wp-admin-audit-events');
        $this->parentHeadline =  __('Event Log', 'wp-admin-audit');
        if($id){
            $model = new WADA_Model_Event($id);
            $this->item = $model->_data;
            $this->viewHeadline = __('Event details', 'wp-admin-audit').' - '. esc_html('#'.absint($this->item->id) . ' '.$this->item->sensor_name);
        }
    }

    protected function handleFormSubmissions(){
        if(isset($_POST['submit'])){
            check_admin_referer(self::VIEW_IDENTIFIER);
        }
    }

    public function renderEventDetailsAjaxResponse(){
        WADA_Log::debug('renderEventDetailsAjaxResponse');
        check_ajax_referer(self::VIEW_IDENTIFIER);
        if(array_key_exists('diff', $_REQUEST)){
            $uiSettingDiff = sanitize_text_field($_REQUEST['diff']);
            if(in_array($uiSettingDiff, array('diff', 'diff-prior', 'separate-view'))){
                update_user_option(get_current_user_id(), 'wada_ui_event_details_show_diff', $uiSettingDiff);
            }
        }
        if(array_key_exists('skid', $_REQUEST)){
            $uiSettingIdentical = sanitize_text_field($_REQUEST['skid']);
            if(in_array($uiSettingIdentical, array('show-identical', 'hide-identical'))){
                update_user_option(get_current_user_id(), 'wada_ui_event_details_show_identical', $uiSettingIdentical);
            }
        }

        $eventDetailsLayout = WADA_Layout_EventDetailsBase::getEventDetailsLayout($this->item);
        $content = $eventDetailsLayout->renderDefaultEventInfosTableContent();
        $response = array('success' => true, 'content' => $content);
        die( json_encode( $response ) );
    }

    protected function displayForm(){
    ?>
        <div class="wrap">
            <?php $this->printHeadersAndBreadcrumb(); ?>
            <form id="<?php echo self::VIEW_IDENTIFIER; ?>" method="post">
                <?php wp_nonce_field(self::VIEW_IDENTIFIER); ?>
                <input type="hidden" name="page" value="<?php echo esc_attr($this->getCurrentPage()); ?>" />

                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-2">
                        <div id="post-body-content">


                            <div id="postbox-container-2" class="postbox-container">
                                <div id="" class="">
                                    <div id="wada-event-details-data-header" class="postbox">
                                        <div class="inside">
                                            <div class="wada-event-details-data-container">
                                                <div class="wada-event-details-data-column">
                                                    <h3><?php _e('General data', 'wp-admin-audit'); ?></h3>
                                                    <?php (new WADA_Layout_EventOutline($this->item))->display(); ?>
                                                </div>
                                                <div class="wada-event-details-data-column">
                                                    <?php
                                                    if($this->item->object_id):
                                                        echo WADA_Layout_EventDetailsBase::getEventObjectDetailsTable($this->item);
                                                    endif;
                                                    ?>
                                                </div>
                                                <div class="wada-event-details-data-column">
                                                    <?php if(WADA_Version::getFtSetting(WADA_Version::FT_ID_INTEG_CHK)
                                                    || (WADA_Version::getFtSetting(WADA_Version::FT_ID_REPLICATE) && WADA_Replicator_Base::isReplicationActive())): ?>
                                                    <h3><?php _e('Integrity', 'wp-admin-audit'); ?></h3>

                                                    <div>
                                                        <table class="data wada-detail-table">
                                                            <tbody>
                                                            <?php
                                                            $headerStr = esc_html(substr($this->item->check_value_head, 0, 7));
                                                            $fullStr = esc_html(substr($this->item->check_value_full, 0, 7));
                                                            if(WADA_Version::getFtSetting(WADA_Version::FT_ID_INTEG_CHK)){
                                                                $headerStr = $this->item->audit_head ? '<span class="wada-green">'.$headerStr.'</span>' : '<span class="wada-error">'.$headerStr.'</span>';
                                                                $fullStr = $this->item->audit_full ? '<span class="wada-green">'.$fullStr.'</span>' : '<span class="wada-error">'.$fullStr.'</span>';
                                                                ?>
                                                                <tr>
                                                                    <td class="label"><?php _e('Header hash', 'wp-admin-audit'); ?></td>
                                                                    <td class="value"><span title="<?php echo esc_html($this->item->check_value_head); ?>"><?php echo $headerStr; ?></span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="label"><?php _e('Full hash', 'wp-admin-audit'); ?></td>
                                                                    <td class="value"><span title="<?php echo esc_html($this->item->check_value_full); ?>"><?php echo $fullStr; ?></span></td>
                                                                </tr><?php
                                                            }
                                                            ?>
                                                            <?php if(WADA_Replicator_Base::isReplicationActive()): ?>
                                                            <tr>
                                                                <td class="label"><?php _e('Replication', 'wp-admin-audit'); ?></td>
                                                                <td class="value"><?php
                                                                $status = '';
                                                                    switch(intval($this->item->replication_done)){
                                                                        case -1:
                                                                            $status = '<span class="wada-error">'.__('Failed', 'wp-admin-audit').'</span>';
                                                                            break;
                                                                        case 0:
                                                                            $status = '<span class="wada-warning">'.__('Pending', 'wp-admin-audit').'</span>';
                                                                            break;
                                                                        case 1:
                                                                            $status = '<span class="wada-green">'.__('Done', 'wp-admin-audit').'</span>';
                                                                            break;
                                                                        default:
                                                                            $status = '<span class="wada-warning">'.sprintf(__('Unknown value: %s', 'wp-admin-audit'), intval($this->item->replication_done)).'</span>';
                                                                    }
                                                                    echo $status; ?></td>
                                                            </tr>
                                                            <?php endif; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <div id="wada-event-details-data-infos" class="postbox ">
                                        <div class="inside">
                                            <?php WADA_Layout_EventDetailsBase::getEventDetailsLayout($this->item)->display(); ?>
                                        </div>
                                    </div>
                                    <?php
                                    if(WADA_Version::getFtSetting(WADA_Version::FT_ID_NOTI)){
                                        $notifications = WADA_Notification_Queue::getEventNotificationsForEventId($this->item->id);
                                        ?>
                                        <div id="wada-event-details-data-notifications" class="postbox">
                                            <div class="inside">
                                                <h3><?php _e('Notifications', 'wp-admin-audit'); ?></h3>
                                                <?php (new WADA_Layout_EventNotifications($notifications))->display(); ?>
                                            </div>
                                        </div>
                                        <?php
                                    } ?>
                                </div>
                            </div>


                            <div id="postbox-container-1" class="postbox-container">
                                <div id="side-sortables" class="meta-box-sortables">
                                    <?php (new WADA_Layout_ActingUser($this->item))->display(); ?>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </form>
        </div>
    <?php
    }
}