<?php

/**
 * @copyright (c) 2020.
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

use  Gamajo_Template_Loader ;
class Admin_Template_Loader extends Gamajo_Template_Loader
{
    public static  $html_output = '' ;
    protected  $filter_prefix = 'quick-event-manager-admin' ;
    protected  $theme_template_directory = 'quick-event-manager-admin' ;
    protected  $plugin_directory = QUICK_EVENT_MANAGER_PLUGIN_DIR ;
    protected  $plugin_template_directory = 'ui/admin/templates' ;
    public function __construct()
    {
        add_filter( $this->filter_prefix . '_template_paths', function ( $file_paths ) {
            $root = trailingslashit( dirname( dirname( QUICK_EVENT_MANAGER_PLUGIN_DIR ) ) ) . trailingslashit( $this->filter_prefix );
            
            if ( isset( $file_paths[1] ) ) {
                $file_paths[2] = trailingslashit( $file_paths[1] ) . 'parts';
                $file_paths[3] = trailingslashit( $file_paths[1] ) . 'loops';
                $file_paths[4] = trailingslashit( $file_paths[1] ) . 'reports';
            }
            
            $file_paths[11] = trailingslashit( $file_paths[10] ) . 'parts';
            $file_paths[12] = trailingslashit( $file_paths[10] ) . 'loops';
            $file_paths[13] = trailingslashit( $file_paths[10] ) . 'reports';
            $file_paths[20] = $root . 'quick-event-manager';
            $file_paths[21] = $root . 'quick-event-manager/parts';
            $file_paths[22] = $root . 'quick-event-manager/loops';
            $file_paths[23] = $root . 'quick-event-manager/reports';
            global  $qem_fs ;
            $file_paths[] = dirname( $this->plugin_directory . $this->plugin_template_directory ) . '/templates__free';
            $file_paths[] = dirname( $this->plugin_directory . $this->plugin_template_directory ) . '/templates__free/parts';
            $file_paths[] = dirname( $this->plugin_directory . $this->plugin_template_directory ) . '/templates__free/loops';
            $file_paths[] = dirname( $this->plugin_directory . $this->plugin_template_directory ) . '/templates__free/reports';
            ksort( $file_paths );
            return $file_paths;
        }, 0 );
    }
    
    public function set_output( $html )
    {
        self::$html_output .= $html;
    }
    
    public function get_output()
    {
        return self::$html_output;
    }

}