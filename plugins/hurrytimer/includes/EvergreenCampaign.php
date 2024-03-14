<?php

namespace Hurrytimer;

use Hurrytimer\Utils\Helpers;

class EvergreenCampaign extends Campaign
{
    /**
     * @var Cookie_Detection
     */
    protected $cookieDetection;

    /**
     * @var IP_Detection
     */
    protected $ipDetection;

    /**
     * @var User_Session_Detection $userSessionDetection
     */
    protected $userSessionDetection;

    const RESET_FLAG = '_hurrytimer_reset_compaign_flag';

    public function __construct( $id = null )
    {
        parent::__construct( $id );

        $this->loadSettings();

        $this->ipDetection          = new IP_Detection( $id );
        $this->cookieDetection      = new Cookie_Detection( $id );
        $this->userSessionDetection = new User_Session_Detection( $id );
    }

    public function isCookieMethodEnabled()
    {
        return in_array( C::DETECTION_METHOD_COOKIE, $this->detectionMethods, true );
    }

    public function isIPMethodEnabled()
    {
        return in_array( C::DETECTION_METHOD_IP, $this->detectionMethods, true );
    }

    public function isUserSessionMethodEnabled()
    {
        return apply_filters( 'hurryt_enable_user_session_detection',
            in_array( C::DETECTION_METHOD_USER_SESSION, $this->detectionMethods, true ) );
    }

    /**
     * Reset timer.
     *
     * @param string $scope
     */

    public function reset( $scope = 'admin' )
    {

        // Reset the given campaign for the current admin.
        if ( $scope === 'admin' ) {
            $this->ipDetection->forget( $this->get_id(), true );
            $this->userSessionDetection->forgetCurrentCampaignOfCurrentUser();

        } else {
            // Reset the given campaign for all users.
            $this->ipDetection->forget( $this->get_id() );
            $this->userSessionDetection->forgetCurrentCampaignOfAllUsers();
            $this->cookieDetection->deleteCampaignCookie();
            $this->cookieDetection->deleteCampaignResetTokenCookie();
        }
        $this->enqueueResetRequest();


    }

    public function enqueueResetRequest( $allCampaigns = false )
    {
        if ( $allCampaigns ) {
            $campaignIds = Helpers::getCampaigns( [ 'fields' => 'ids' ] );
        } else {
            $campaignIds[] = $this->get_id();
        }

        foreach ( $campaignIds as $campaignId ) {
            update_post_meta( $campaignId, self::RESET_FLAG, time() );
        }
    }


    function resetAll( $scope = 'admin' )
    {
        if ( $scope === 'admin' ) {
                $this->ipDetection->forgetAll( true );
                User_Session_Detection::forgetAllCampaignsOfCurrentUser();
        } else {
                $this->cookieDetection->deleteAllCookies();
                $this->ipDetection->forgetAll();
                User_Session_Detection::forgetAllCampaignsOfAllUsers();

        }
        $this->enqueueResetRequest( true );

    }


    public function getInitiatedResetToken()
    {
        return get_post_meta( $this->get_id(), self::RESET_FLAG, true );
    }

    public function shouldResetTimer()
    {
        // Check if there a reset request.
        $initialResetToken = $this->getInitiatedResetToken();
        
        // If No reset request found, then resume timer.
        if ( empty( $initialResetToken ) ) {
            return false;
        }

        $cookieResetToken = $this->isCookieMethodEnabled() ? $this->cookieDetection->getCurrentUserResetToken() : null;

        list( $ipId, $ipResetToken ) = $this->isIPMethodEnabled() ? $this->ipDetection->getCurrentUserResetToken() : null;

        $sessionResetToken = $this->isUserSessionMethodEnabled() ?
            $this->userSessionDetection->getCurrentUserResetToken() :
            null;

        $resetToken = max( $ipResetToken, $cookieResetToken, $sessionResetToken );
        $this->ipDetection->updateCurrentUserResetToken( $ipId, $resetToken );
        $this->userSessionDetection->updateOrCreateCurrentUserResetToken( $resetToken );

        // Not reset yet for this user.
        if ( empty( $resetToken ) ) {
            return true;
        }
        // A new request is made.
        if ( $initialResetToken > $resetToken ) {
            return true;
        }
    }


    /**
     * Returns client expiration time.
     *
     * @return int
     */
    public function getEndDate()
    {

        $cookieEndDateTimeTS = $this->isCookieMethodEnabled() ? $this->cookieDetection->getCurrentUserEndDate() : null;
        list( $ipId, $ipEndDateTimeTS ) = $this->isIPMethodEnabled() ? $this->ipDetection->getCurrentUserEndDate() : null;

        $userSessionEndDateTimeTS = $this->isUserSessionMethodEnabled() ? $this->userSessionDetection->getEndDateTime() : null;

        $endDateTimeTS = max( $cookieEndDateTimeTS, $ipEndDateTimeTS, $userSessionEndDateTimeTS );

        // First visit, load a fresh timer.
        if ( empty( $endDateTimeTS ) ) {
            return null;
        }

        // Make the timestamp is up-to-date across all methods.
        $this->ipDetection->updateOrCreate( $ipId, $endDateTimeTS );

        $initialResetToken = $this->getInitiatedResetToken();
        $this->ipDetection->updateCurrentUserResetToken( $ipId, $initialResetToken );
        $this->userSessionDetection->updateOrCreateCurrentUserEndDateTime( $endDateTimeTS );
        $this->userSessionDetection->updateOrCreateCurrentUserResetToken( $initialResetToken );
        return $endDateTimeTS;
    }

    function setEndDate( $timestamp )
    {
        $this->cookieDetection->updateOrCreate( $timestamp );
        list( $ipId ) = $this->ipDetection->getCurrentUserEndDate();
        $this->ipDetection->updateOrCreate( $ipId, $timestamp );
        $this->userSessionDetection->updateOrCreateCurrentUserEndDateTime( $timestamp );
        return $this->getEndDate();
    }

}
