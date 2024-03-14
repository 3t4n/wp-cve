<?php

/**
 * helper Class to load freemius SDK
 */
namespace WidgetForEventbriteAPI\Includes;

class Freemius_Config
{
    public function init()
    {
        /** @var \Freemius $wfea_fs Freemius global object. */
        global  $wfea_fs ;
        
        if ( !isset( $wfea_fs ) ) {
            // Include Freemius SDK.
            require_once WIDGET_FOR_EVENTBRITE_API_PLUGIN_DIR . '/includes/vendor/freemius/wordpress-sdk/start.php';
            $wfea_fs = fs_dynamic_init( array(
                'id'             => '1330',
                'slug'           => 'widget-for-eventbrite-api',
                'type'           => 'plugin',
                'public_key'     => 'pk_97d4242a859ccad67940512ad19ab',
                'is_premium'     => false,
                'premium_suffix' => '( Pro )',
                'has_addons'     => true,
                'has_paid_plans' => true,
                'trial'          => array(
                'days'               => 14,
                'is_require_payment' => true,
            ),
                'navigation'     => 'tabs',
                'menu'           => array(
                'slug'    => 'widget-for-eventbrite-api-settings',
                'contact' => false,
                'support' => false,
                'parent'  => array(
                'slug' => 'options-general.php',
            ),
            ),
                'is_live'        => true,
            ) );
        }
        
        $options = get_option( 'widget-for-eventbrite-api-settings' );
        if ( empty($options['key']) ) {
            $wfea_fs->add_filter(
                'connect_url',
                function ( $url ) {
                $url = admin_url( 'options-general.php?page=widget-for-eventbrite-api-setup-wizard' );
                return $url;
            },
                10,
                1
            );
        }
        $wfea_fs->add_filter( 'plugin_icon', function () {
            return WIDGET_FOR_EVENTBRITE_API_PLUGIN_DIR . 'admin/images/icon.svg';
        } );
        // free code users if they ever had a licence show only the support link which is the free forum
        $wfea_fs->add_filter(
            'is_submenu_visible',
            function ( $is_visible, $menu_id ) {
            if ( 'contact' === $menu_id ) {
                return false;
            }
            if ( 'support' === $menu_id ) {
                return true;
            }
            return $is_visible;
        },
            10,
            2
        );
        // hide deactivation form
        $wfea_fs->add_filter( 'show_deactivation_feedback_form', function ( $bool ) {
            return false;
        } );
        return $wfea_fs;
    }

}