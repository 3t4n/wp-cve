<?php

namespace Hurrytimer;

/**
 *
 * This class handle actions executions
 *
 * Class ActionManager
 *
 * @package Hurrytimer
 */
class ActionManager
{
    /**
     * @var Campaign
     */
    protected $campaign;

    public function __construct( $campaign )
    {
        $this->campaign = $campaign;
        add_filter( 'wp_insert_post_data', function ( $data ) {
            global $hurryt_saving_post;
            $hurryt_saving_post = true;

            return $data;
        } );
        add_action( 'save_post', function () {
            global $hurryt_saving_post;
            $hurryt_saving_post = false;
        } );

    }

    function is_disabled()
    {
        $disable_actions = hurryt_is_admin_area() && hurryt_settings()[ 'disable_actions' ];
        return filter_var( apply_filters( 'hurryt_disable_actions', $disable_actions ), FILTER_VALIDATE_BOOLEAN );
    }

    public function run()
    {

        if ( $this->is_disabled() ) {
            return;
        }

        /**
         * @deprecated 2.3.0 Use `hurryt_campaign_finished` instead.
         */
        do_action( "hurryt_{$this->campaign->get_id()}_campaign_ended", $this->campaign );

        do_action( "hurryt_campaign_finished", $this->campaign->get_id() );

        foreach ( $this->campaign->actions as $action ) {
            switch ( $action[ 'id' ] ) {

                case C::ACTION_REDIRECT;
                    $this->redirect_to( $action[ 'redirectUrl' ] );
                    break;
                case C::ACTION_CHANGE_STOCK_STATUS:
                    $this->change_stock_status( $action[ 'wcStockStatus' ] );
                    break;
            }
        }
    }

    function redirect_to( $url )
    {
        global $hurryt_saving_post;
        if ( $hurryt_saving_post ) {
            return;
        }

        if ( !empty( trim( $url ) ) ) {
            wp_redirect( $url );
            return;
        }
    }

    function change_stock_status( $stock_status )
    {
        $wc_campaign = new WCCampaign();
        $wc_campaign->change_stock_status( $this->campaign, $stock_status );

        return;
    }
}
