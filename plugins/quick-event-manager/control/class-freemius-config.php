<?php

/**
 * @copyright (c) 2019.
 * @author            Alan Fuller (support@fullworks)
 * @licence           GPL V3 https://www.gnu.org/licenses/gpl-3.0.en.html
 * @link                  https://fullworks.net
 *
 * This file is part of  a Fullworks plugin.
 *
 *   This plugin is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This plugin is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with  this plugin.  https://www.gnu.org/licenses/gpl-3.0.en.html
 */
namespace Quick_Event_Manager\Plugin\Control;

use  Freemius ;
class Freemius_Config
{
    public function init()
    {
        global  $qem_fs ;
        
        if ( !isset( $qem_fs ) ) {
            // Include Freemius SDK.
            require_once QUICK_EVENT_MANAGER_PLUGIN_DIR . 'vendor/freemius/wordpress-sdk/start.php';
            $qem_fs = fs_dynamic_init( array(
                'id'             => '5344',
                'slug'           => 'quick-event-manager',
                'type'           => 'plugin',
                'public_key'     => 'pk_779c63e99180c8ac9ea9ff1450bb8',
                'is_premium'     => false,
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => array(
                'days'               => 14,
                'is_require_payment' => true,
            ),
                'navigation'     => 'tabs',
                'menu'           => array(
                'slug'    => 'quick-event-manager',
                'contact' => false,
                'support' => true,
                'parent'  => array(
                'slug' => 'options-general.php',
            ),
            ),
                'is_live'        => true,
            ) );
        }
        
        $qpp_key = get_option( 'qpp_key', array(
            'authorised' => false,
        ) );
        if ( !isset( $qpp_key['authorised'] ) ) {
            $qpp_key['authorised'] = false;
        }
        
        if ( false === $qpp_key['authorised'] ) {
            update_option( 'qem_freemius_state', array(
                'authorised' => $qem_fs->can_use_premium_code(),
            ) );
        } else {
            update_option( 'qem_freemius_state', array(
                'authorised' => true,
            ) );
        }
        
        // @TODO remove when premium code removed
        if ( true === $qpp_key['authorised'] ) {
            $qem_fs->add_filter(
                'is_submenu_visible',
                function ( $is_visible, $menu_id ) {
                if ( 'contact' === $menu_id ) {
                    return false;
                }
                if ( 'support' === $menu_id ) {
                    return false;
                }
                return $is_visible;
            },
                10,
                2
            );
        }
        return $qem_fs;
    }

}