<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_UserOverview implements WADA_Layout_LayoutInterface
{
    const LAYOUT_IDENTIFIER = 'wada-layout-user-overview';
    public $user;
    public $userId;

    /**
     * @param object $user
     */
    public function __construct($user, $userId){
        $this->user = $user;
        $this->userId = $userId;

        WADA_Log::debug('UserOverview user: '.print_r($this->user, true));
    }

    protected function getDateOrTrackedSince($dateFromDb, $printWithWarning=false, $warningText=''){
        $trackedSince = WADA_DateUtils::formatUTCasDatetimeForWP($this->user->tracked_since);
        $html = '';
        if($printWithWarning){
            $html .= '<span class="wada-warning" title="'.esc_attr($warningText).'">';
        }
        if(is_null($dateFromDb)){
            $html .= '<span class="wada-asterisk">'.__('Never', 'wp-admin-audit').'</span> <span class="wada-greyed-out">('.sprintf(__('tracked since: %s'), $trackedSince).')</span>';
        }else{
            $html .= WADA_DateUtils::formatUTCasDatetimeForWP($dateFromDb);
        }
        if($printWithWarning){
            $html .= '</span>';
        }
        return $html;
    }

    public function display($returnAsString = false){
        if($returnAsString){
            ob_start();
        }
        if($this->user):
            $wpUserEditLink = sprintf(admin_url('user-edit.php?user_id=%d'), $this->userId);
        ?>
        <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
            <table class="form-table wada-compact-table">
                <tbody>
                <tr>
                    <th scope="row"><label><?php _e('ID', 'wp-admin-audit'); ?></label></th>
                    <td><?php echo esc_html($this->user->user_id); ?></td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Name', 'wp-admin-audit'); ?></label></th>
                    <td><?php echo esc_html($this->user->first_name . ' ' . $this->user->last_name); ?></td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Username', 'wp-admin-audit'); ?></label></th>
                    <td><?php echo esc_html($this->user->user_login); ?></td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Email', 'wp-admin-audit'); ?></label></th>
                    <td><?php echo esc_html($this->user->user_email); ?></td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Roles', 'wp-admin-audit'); ?></label></th>
                    <td><?php echo esc_html(implode(', ', $this->user->rolesNiceName)); ?></td>
                </tr>
                <tr>
                    <td colspan="2"><a href="<?php echo $wpUserEditLink; ?>"><?php _e('Edit user', 'wp-admin-audit'); ?></a></td>
                </tr>
                <tr class="wada-spacer-row">
                    <td colspan="2">&nbsp;</td>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Last seen', 'wp-admin-audit'); ?></label></th>
                    <td><?php echo $this->getDateOrTrackedSince($this->user->last_seen); ?></td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Last login', 'wp-admin-audit'); ?></label></th>
                    <td><?php echo $this->getDateOrTrackedSince($this->user->last_login); ?></td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Last password change', 'wp-admin-audit'); ?></label></th>
                    <td><?php
                        $userRole = current($this->user->roles);
                        $warnAsExpired = (WADA_Settings::isUserAccountEnforcePwChangeEnabled()
                            && ($this->user->last_pw_change_days_ago >= WADA_Settings::getUserAccountEnforcePwChangeEveryXDays()))
                            && (in_array($userRole, WADA_Settings::getUserAccountEnforcePwChangeRolesInScope()));
                        $warnText = $warnAsExpired ? __('Password expired', 'wp-admin-audit') : '';
                        echo $this->getDateOrTrackedSince($this->user->last_pw_change, $warnAsExpired, $warnText);
                        ?></td>
                </tr>
                <?php if($this->user->last_pw_change_reminder): ?>
                    <tr>
                        <th scope="row"><label><?php _e('Password change reminder sent', 'wp-admin-audit'); ?></label></th>
                        <td><?php echo $this->getDateOrTrackedSince($this->user->last_pw_change_reminder); ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <th scope="row"><label><?php _e('Registered at', 'wp-admin-audit'); ?></label></th>
                    <td><?php echo $this->getDateOrTrackedSince($this->user->user_registered); ?></td>
                </tr>
                <tr class="wada-spacer-row">
                    <td colspan="2">&nbsp;</td>
                    <td colspan="2">&nbsp;</td>
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