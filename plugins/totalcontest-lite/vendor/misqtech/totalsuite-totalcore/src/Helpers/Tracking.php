<?php

namespace TotalContestVendors\TotalCore\Helpers;


use TotalContestVendors\TotalCore\Application;

/**
 * Class Tracking
 *
 * @package TotalContestVendors\TotalCore\Helpers
 */
class Tracking
{
    /**
     * @param string $screen
     */
    static public function trackScreens($screen) {
        $key = Application::getInstance()->env( 'tracking-key' );

        $tracking = (array) get_option( $key, [
            'screens'  => [],
            'features' => []
        ] );


        $tracking['screens'][] = [
            'screen' => $screen,
            'date'   => date( DATE_ATOM )
        ];

        update_option( $key, $tracking );
    }

    /**
     * @param string $action
     * @param string $target
     */
    static public function trackEvents($action, $target) {
        $key = Application::getInstance()->env( 'tracking-key' );

        $tracking = (array) get_option( $key, [
            'screens'  => [],
            'features' => []
        ] );

        $tracking['features'][] = [
            'action' => $action,
            'label'  => $target,
            'date'   => date( DATE_ATOM )
        ];

        update_option( $key, $tracking );
    }
}