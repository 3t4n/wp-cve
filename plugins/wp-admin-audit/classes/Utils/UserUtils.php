<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_UserUtils
{

    /**
     * @param WP_User $previousUser
     * @param WP_User $currentUser
     * @return array
     */
	public static function getChangedAttributes($previousUser, $currentUser, $metaDataChanges = array()){
		$changedAttributes = array();
		if(($previousUser instanceof WP_User) && ($currentUser instanceof WP_User)) {
            $changedAttributes = array_merge($changedAttributes, self::getChangedTopLevelAttributes($previousUser, $currentUser));
            $changedAttributes = array_merge($changedAttributes, self::getChangedRoles($previousUser, $currentUser));
            if($metaDataChanges && count($metaDataChanges)) {
                $changedAttributes = array_merge($changedAttributes, self::getChangedUserMeta($metaDataChanges));
            }
        }else{
		    WADA_Log::debug('getChangedAttributes No comparison possible');
            WADA_Log::debug('getChangedAttributes previousUser: '.print_r($previousUser, true));
            WADA_Log::debug('getChangedAttributes currentUser: '.print_r($currentUser, true));
        }
		return $changedAttributes;
	}

    /**
     * @return string[]
     */
	public static function getTopLevelAttributes(){
	    return array(
            'user_login', 'user_pass', 'user_nicename', 'user_email', 'user_url',
            'user_registered', 'user_activation_key', 'user_status', 'display_name'
        );
    }

    /**
     * @param WP_User $previousUser
     * @param WP_User $currentUser
     * @return array
     */
	protected static function getChangedTopLevelAttributes($previousUser, $currentUser){
        $attributes2Check = self::getTopLevelAttributes();
        return WADA_CompUtils::getChangedAttributes($previousUser, $currentUser, $attributes2Check);
    }

    /**
     * @param WP_User $previousUser
     * @param WP_User $currentUser
     * @return array
     */
    protected static function getChangedRoles($previousUser, $currentUser){
        $changedRoles = array();

        if($previousUser && property_exists($previousUser, 'roles')
            && $currentUser && property_exists($currentUser, 'roles')){
            sort($previousUser->roles); // sort roles
            sort($currentUser->roles); // sort roles
            $previousRoles = implode(', ', $previousUser->roles);
            $currentRoles = implode(', ', $currentUser->roles);

            if($previousRoles !== $currentRoles){
                $changedRoles[] = array(
                    'info_key' => 'roles',
                    'info_value' => $currentRoles,
                    'prior_value' => $previousRoles
                );
            }
        }

        return $changedRoles;
    }

    /**
     * @param array $metaDataChanges
     * @return array
     */
    protected static function getChangedUserMeta($metaDataChanges){
        $changedUserMeta = array();

        foreach($metaDataChanges AS $metaKey => $metaDataChange) {
            $prevValue = $metaDataChange['prev'];
            $currValue = $metaDataChange['curr'];

            $prevValueStr = WADA_PHPUtils::flattenArray($prevValue);
            $currValueStr = WADA_PHPUtils::flattenArray($currValue);

            if($prevValueStr != $currValueStr){
                $changedUserMeta[] = array(
                    'info_key' => $metaKey,
                    'info_value' => $currValueStr,
                    'prior_value' => $prevValueStr
                );
            }
        }

        return $changedUserMeta;
    }

    public static function doUserAccountAutoAdjustments(){
        /* @@REMOVE_START_WADA_startup@@ */
        /*  */
        /* @@REMOVE_END_WADA_startup@@ */

    }

    public static function getInactiveUsersQuery($inactiveSinceDays, $userIdsInScope, $currUtc){
        global $wpdb;
        $sql= "SELECT wpusr.ID as id, wada_usr.not_seen_since, wada_usr.last_seen, wada_usr.tracked_since"
            ." FROM ".$wpdb->prefix . "users wpusr "
            ." LEFT JOIN ("
            ." SELECT GREATEST(COALESCE(last_seen, tracked_since), COALESCE(last_login, tracked_since), COALESCE(last_pw_change, tracked_since)) AS not_seen_since,"
            ." wada_users.*"
            ." FROM ".WADA_Database::tbl_users() ." wada_users"
            ." ) wada_usr ON (wpusr.ID = wada_usr.user_id)"
            ." WHERE wpusr.ID IN (" . implode(',', $userIdsInScope) . ")"
            ." AND wada_usr.not_seen_since < DATE_SUB('".$currUtc."', INTERVAL ".intval($inactiveSinceDays)." DAY)";
        return $sql;
    }

    public static function sendUserPasswordChangeReminders(){
        /* @@REMOVE_START_WADA_startup@@ */
        /*  */
        /* @@REMOVE_END_WADA_startup@@ */
    }

    /**
     * @param WADA_Model_User $userModel
     * @return bool|mixed|void
     */
    public static function sendPasswordChangeReminderWhenPasswordExpired($userModel){
        add_filter( 'wp_mail_content_type', function( $content_type ) {
            return 'text/html';
        });

        // TODO IMPLEMENT Email contents filter
        $to = $userModel->_data->user_email;
        $subject = __('Password expired', 'wp-admin-audit');

        $url = get_site_url();
        $websiteLink = '<a href="'.esc_attr($url).'">'.$url.'</a>';
        $body = '<p>'.sprintf(__('Your password at %s expired', 'wp-admin-audit'), $websiteLink).'</p>';

        WADA_Log::debug('sendPasswordChangeReminderWhenPasswordExpired send to: '.$to.', subject: '.$subject.', body: '.$body);

        $headers = array(
            'Content-Type: text/html; charset=UTF-8'
        );

        $canSendEmail = WADA_Settings::canSendEmailTo($to, $subject);
        if($canSendEmail){
            return wp_mail($to, $subject, $body, $headers);
        }
        WADA_Log::debug('sendPasswordChangeReminderWhenPasswordExpired sending disabled');
        return true;
    }


    /**
     * @param WADA_Model_User $userModel
     * @return bool|mixed|void
     */
    public static function sendPasswordChangeReminderBeforeExpiry($userModel, $daysUntilExpiry){
        add_filter( 'wp_mail_content_type', function( $content_type ) {
            return 'text/html';
        });

        // TODO IMPLEMENT Email contents filter
        $to = $userModel->_data->user_email;
        $subject = sprintf(__('Password will expire in %d days', 'wp-admin-audit'), $daysUntilExpiry);

        $url = get_site_url();
        $websiteLink = '<a href="'.esc_attr($url).'">'.$url.'</a>';
        $body = '<p>'.sprintf(__('Your password at %s will expire in %d days. Please change it before it expires.', 'wp-admin-audit'), $websiteLink, $daysUntilExpiry).'</p>';

        WADA_Log::debug('sendPasswordChangeReminderBeforeExpiry send to: '.$to.', subject: '.$subject.', body: '.$body);

        $headers = array(
            'Content-Type: text/html; charset=UTF-8'
        );

        $canSendEmail = WADA_Settings::canSendEmailTo($to, $subject);
        if($canSendEmail){
            return wp_mail($to, $subject, $body, $headers);
        }
        WADA_Log::debug('sendPasswordChangeReminderBeforeExpiry sending disabled');
        return true;
    }

    public static function getUsersHavingPwChangeNeedQuery($earliestNotificationDaysPrior, $lastNotificationDaysPrior, $changeFreqInDays, $userIdsInScope, $currUtc){
        global $wpdb;
        $sql= "SELECT wpusr.ID as id, wada_usr.tracked_since, wada_usr.last_pw_change, wada_usr.last_pw_change_days_ago,"
            ." wada_usr.last_pw_change_reminder, wada_usr.last_pw_change_reminder_days_before_expiry,"
            ." pw_expired, days_left_until_expiry"
            ." FROM ".$wpdb->prefix . "users wpusr "
            ." LEFT JOIN ("
                ." SELECT COALESCE(last_pw_change, tracked_since) AS last_pw_change, user_id, tracked_since, last_pw_change_reminder,"
                ." TIMESTAMPDIFF(DAY, COALESCE(last_pw_change, tracked_since), '".$currUtc."') AS last_pw_change_days_ago,"
                ." CASE WHEN TIMESTAMPDIFF(DAY, COALESCE(last_pw_change, tracked_since), '".$currUtc."') >".intval($changeFreqInDays)." THEN 1 ELSE 0 END AS pw_expired,"
                ." TIMESTAMPDIFF(DAY, '".$currUtc."', DATE_ADD(last_pw_change, INTERVAL ".intval($changeFreqInDays)." DAY)) AS days_left_until_expiry,"
                ." CASE WHEN last_pw_change_reminder IS NULL THEN NULL ELSE TIMESTAMPDIFF(DAY, last_pw_change_reminder, DATE_ADD(last_pw_change, INTERVAL ".intval($changeFreqInDays)." DAY)) END AS last_pw_change_reminder_days_before_expiry"
                ." FROM ".WADA_Database::tbl_users() ." wada_users"
            ." ) wada_usr ON (wpusr.ID = wada_usr.user_id)"
            ." WHERE wpusr.ID IN (" . implode(',', $userIdsInScope) . ")"
            ." AND ("
                ." ((pw_expired = 1) AND ((last_pw_change_reminder IS NULL) OR (last_pw_change_reminder < DATE_ADD(last_pw_change, INTERVAL ".intval($changeFreqInDays)." DAY))))"
                ." OR"
                ." ((pw_expired = 0) AND ((last_pw_change_reminder IS NULL) OR (last_pw_change_reminder_days_before_expiry > ".intval($lastNotificationDaysPrior).")))"
                .")"
        ;
        return $sql;
    }

}
