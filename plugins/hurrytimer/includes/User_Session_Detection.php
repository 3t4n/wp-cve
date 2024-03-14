<?php

namespace Hurrytimer;

class User_Session_Detection
{

    const  TS_META_KEY = '_hurryt_end_ts';
    const  RESET_META_KEY = '_hurryt_reset_token';
    protected $campaignEndTSKey;
    protected $campaignResetTokenKey;
    protected $campaignId;

    public function __construct( $campaignId )
    {
        $this->campaignId = $campaignId;
        $this->campaignEndTSKey = self::TS_META_KEY . '_' . $this->campaignId;
        $this->campaignResetTokenKey = self::RESET_META_KEY . '_' . $this->campaignId;
    }

    /**
     * Get end date time timestamp of the campaign and current user.
     */
    public function getEndDateTime()
    {
        if ( is_user_logged_in() ) {
            return get_user_meta( get_current_user_id(), $this->campaignEndTSKey, true );
        }
    }

    public function forgetCurrentCampaignOfCurrentUser()
    {
        if ( is_user_logged_in() ) {
            delete_user_meta( get_current_user_id(), $this->campaignEndTSKey );
        }

    }

    public static function forgetAllCampaignsOfCurrentUser()
    {

        if ( is_user_logged_in() ) {
            global $wpdb;

            $wpdb->delete( $wpdb->usermeta, [
                'meta_key' => self::TS_META_KEY . '%',
                'user_id' => get_current_user_id()
            ], [ '%s', '%d' ] );

            $wpdb->delete( $wpdb->usermeta, [
                'meta_key' => self::RESET_META_KEY . '%',
                'user_id' => get_current_user_id()
            ], [ '%s', '%d' ] );

        }
    }

    public function forgetCurrentCampaignOfAllUsers()
    {
        global $wpdb;

        $wpdb->delete( $wpdb->usermeta, [ 'meta_key' => $this->campaignEndTSKey ] );

    }


    public static function forgetAllCampaignsOfAllUsers()
    {
        global $wpdb;
        $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE %s", self::TS_META_KEY . '%' ) );
        $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE %s", self::RESET_META_KEY . '%' ) );

    }

    public function updateOrCreateCurrentUserEndDateTime( $endDateTimeTS )
    {


        if ( is_user_logged_in() ) {
            if ( empty( $endDateTimeTS ) ) {
                delete_user_meta( get_current_user_id(), $this->campaignEndTSKey );
            } else {
                update_user_meta( get_current_user_id(), $this->campaignEndTSKey, $endDateTimeTS );
            }
        }

    }

    public function getCurrentUserResetToken()
    {
        if ( is_user_logged_in() ) {
            $ts = get_user_meta( get_current_user_id(), $this->campaignResetTokenKey, true );
            return $ts ?: false;
        }

        return false;
    }

    public function updateOrCreateCurrentUserResetToken( $resetToken )
    {
        if ( is_user_logged_in() ) {
            update_user_meta( get_current_user_id(), $this->campaignResetTokenKey, $resetToken );
        }
    }

    // TODO:
    // - clean up when campaign is deleted.

}