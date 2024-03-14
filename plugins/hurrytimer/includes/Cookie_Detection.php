<?php

namespace Hurrytimer;

use Hurrytimer\Utils\Helpers;

class Cookie_Detection
{
    const COOKIE_PREFIX = '_ht_CDT-';
    const RESET_FLAG = '_hurrytimer_reset_compaign_flag';

    protected $campaignId;

    public function __construct( $campaignId )
    {
        $this->campaignId = $campaignId;
    }

    public function updateOrCreate( $endDateTimeTS )
    {
        setcookie( self::cookieName( $this->campaignId ), $endDateTimeTS,
            time() + YEAR_IN_SECONDS,
            COOKIEPATH,
            COOKIE_DOMAIN );

    }

    /**
     * Find Campaign timer cookie.
     *
     *
     * @return int|null
     */
    public function getCurrentUserEndDate()
    {

        $endDateTS = self::cookieName( $this->campaignId );

        if ( !isset( $_COOKIE[ $endDateTS ] ) || empty( $_COOKIE[ $endDateTS ] ) ) {
            return null;
        }

        return $_COOKIE[ $endDateTS ];
    }

    /**
     * Cookie name with given Campaign id.
     *
     * @param int $compaignId
     *
     * @return string
     */
    public static function cookieName( $compaignId )
    {
        return self::COOKIE_PREFIX . $compaignId;
    }

    public function getCurrentUserResetToken()
    {

        $resetToken = self::cookieName( $this->campaignId ) . '_reset_token';

        return empty( $_COOKIE[ $resetToken ] ) ? false : $_COOKIE[ $resetToken ];

    }

    public function deleteCampaignResetTokenCookie($campaignId = null)
    {
        $_campaignId = $campaignId ?: $this->campaignId;
        $resetToken = self::cookieName( $_campaignId ) . '_reset_token';
        setcookie( $resetToken, '', time() - YEAR_IN_SECONDS );
        unset( $_COOKIE[ $resetToken ] );
    }

    public function deleteCampaignCookie( $campaignId = null )
    {
        $_campaignId = $campaignId ?: $this->campaignId;
        $cookie_name = Cookie_Detection::cookieName( $_campaignId );
        unset( $_COOKIE[ $cookie_name ] );
        setcookie( $cookie_name, '', time() - YEAR_IN_SECONDS );
    }

    public function deleteAllCookies()
    {
        $campaigns = Helpers::getCampaigns();
        foreach ( $campaigns as $campaign ) {
            $this->deleteCampaignCookie( $campaign->ID );
            $this->deleteCampaignResetTokenCookie($campaign->ID);
        }
    }

}
