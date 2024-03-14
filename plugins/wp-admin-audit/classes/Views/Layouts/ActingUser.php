<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_ActingUser implements WADA_Layout_LayoutInterface
{
    const LAYOUT_IDENTIFIER = 'wada-layout-acting-user';
    public $item = null;

    /**
     * @param object $item
     */
    public function __construct($item){
        $this->item = $item;
    }

    public function display($returnAsString = false){
        if($returnAsString){
            ob_start();
        }
        $display = ($this->item->sensor_id != WADA_Sensor_Base::EVT_USER_LOGIN_FAILED);
        $nonDisplayMessage = ($this->item->sensor_id != WADA_Sensor_Base::EVT_USER_LOGIN_FAILED) ? '' : __('User details unknown (authentication failed)', 'wp-admin-audit');
    ?>
        <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
            <div id="wada-event-details-user-info-box" class="postbox ">
                <h2 class=""><span><?php _e('Acting User', 'wp-admin-audit') . ' - ' . __('User Details', 'wp-admin-audit'); ?></span></h2>
                <div class="inside"><?php
                    if($display):
                    ?>
                        <div id="wada-event-details-user-info-box-details">
                            <?php if($this->item->user_id > 0): ?>
                                <div id="recorded-user-info">
                                    <h4 class="title"><?php _e('User details at the time of the event', 'wp-admin-audit'); ?></h4>
                                    <?php echo esc_html($this->item->user_name . ' <' . $this->item->user_email) . '>'; ?>
                                </div>
                                <div id="current-user-info">
                                    <h4 class="title"><?php _e('Current user account details', 'wp-admin-audit'); ?></h4>
                                    <?php if(!($this->item->user)): ?>
                                        <em><?php echo sprintf(__('User account with ID %d not existing (anymore).', 'wp-admin-audit'), $this->item->user_id); ?></em>
                                    <?php else:
                                        ($userOutline = new WADA_Layout_UserOutline($this->item->user, $this->item->user_id))->display();
                                        ?>
                                        <div class="wada-central-container">
                                            <a href="<?php echo sprintf(admin_url('admin.php?page=wp-admin-audit-users&subpage=user-details&sid=%d'), $this->item->user_id); ?>"><?php echo __('View user details', 'wp-admin-audit'); ?></a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php elseif($this->item->user_id == WADA_Sensor_Base::WADA_PSEUDO_USER_ID): ?>
                                <div id="recorded-user-info">
                                    <?php _e('Triggered by WP Admin Audit', 'wp-admin-audit'); ?>
                                </div>
                            <?php else: ?>
                                <div id="recorded-user-info">
                                    <?php _e('User details not stored (user not logged in)', 'wp-admin-audit'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php
                else: ?>
                    <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
                        <?php echo $nonDisplayMessage; ?>
                    </div>
                <?php
                endif;
                ?>
            </div>
        </div>
        <?php

        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }
}