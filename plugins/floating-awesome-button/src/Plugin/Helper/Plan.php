<?php

namespace Fab\Helper;

!defined( 'WPINC ' ) or die;
/**
 * Helper library for Fab plugins
 *
 * @package    Fab
 * @subpackage Fab\Includes
 */
trait Plan
{
    /**
     * Get Premium Plan Info
     * @return bool
     */
    public function isPremiumPlan()
    {
        /** Get Plan from config.json file */
        $plugin = \Fab\Plugin::getInstance();
        $plan = $plugin->getConfig()->premium;
        /** Freemius - Check Premium Plan */
        if ( function_exists( 'fab_freemius' ) ) {
        }
        return $plan;
    }
    
    /**
     * Get Upgrade URL
     * @return string
     */
    public function getUpgradeURL()
    {
        return ( function_exists( 'fab_freemius' ) ? fab_freemius()->get_upgrade_url() : false );
    }

}