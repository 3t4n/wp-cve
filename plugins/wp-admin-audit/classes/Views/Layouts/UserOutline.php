<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_UserOutline implements WADA_Layout_LayoutInterface
{
    const LAYOUT_IDENTIFIER = 'wada-layout-user-outline';
    public $wpUser;
    public $userId;

    /**
     * @param WP_User $wpUser
     */
    public function __construct($wpUser, $userId){
        $this->wpUser = $wpUser;
        $this->userId = $userId;
    }

    public function display($returnAsString = false){
        if($returnAsString){
            ob_start();
        }
        if($this->wpUser):
    ?>
        <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
            <?php
            $displayName = (property_exists($this->wpUser, 'display_name') && $this->wpUser->display_name && strlen(trim($this->wpUser->display_name)) > 0) ? $this->wpUser->display_name : ($this->wpUser->first_name . ' ' . $this->wpUser->last_name);
            $registeredAt = WADA_DateUtils::formatWPDatetimeForWP($this->wpUser->user_registered);
            ?>
            <table class="data wada-detail-table">
                <tbody>
                <tr>
                    <td class="label"><?php _e('ID', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->wpUser->ID); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Username', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->wpUser->user_login); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Name', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($displayName); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Email', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->wpUser->user_email); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Registered at', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($registeredAt); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('User roles', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html(implode(', ', $this->wpUser->roles)); ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php
        elseif($this->userId == WADA_Sensor_Base::WADA_PSEUDO_USER_ID): ?>
            <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
                <table class="data wada-detail-table">
                    <tbody>
                    <tr>
                        <td class="value" colspan="2"><strong><?php _e('Triggered by WP Admin Audit', 'wp-admin-audit'); ?></strong></td>
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
                        <td class="value"><?php echo esc_html($this->userId); ?></td>
                    </tr>
                    <tr>
                        <td class="value" colspan="2"><strong><?php _e('User no longer existing', 'wp-admin-audit'); ?></strong></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        <?php
        endif;

        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }
}