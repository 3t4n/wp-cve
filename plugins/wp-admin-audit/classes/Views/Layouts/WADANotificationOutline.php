<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_WADANotificationOutline implements WADA_Layout_LayoutInterface
{
    const LAYOUT_IDENTIFIER = 'wada-layout-wada-notification-outline';
    public $notificationObj;
    public $notificationId;

    /**
     * @param object $notificationObj
     * @param int $notificationId
     */
    public function __construct($notificationObj, $notificationId){
        $this->notificationObj = $notificationObj;
        $this->notificationId = $notificationId;
    }

    public function display($returnAsString = false){
        if($returnAsString){
            ob_start();
        }
        if($this->notificationObj):
    ?>
        <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
            <table class="data wada-detail-table">
                <tbody>
                <tr>
                    <td class="label"><?php _e('ID', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->notificationObj->id); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Name', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->notificationObj->name); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Active', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo (intval($this->notificationObj->active) > 0 ? __('Yes', 'wp-admin-audit') : __('No', 'wp-admin-audit')); ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php
        else: ?>
        <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
            <table class="data wada-detail-table">
                <tbody>
                <tr>
                    <td class="label"><?php _e('ID', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->notificationId); ?></td>
                </tr>
                <tr>
                    <td class="value" colspan="2"><strong><?php _e('Notification no longer existing', 'wp-admin-audit'); ?></strong></td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php
        endif;

        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }
}