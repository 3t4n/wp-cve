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
namespace Quick_Event_Manager\Plugin\Core;

/**
 * used for shared data
 */
class Utilities
{
    /**
     * @var
     */
    protected static  $instance ;
    public function __construct()
    {
    }
    
    /**
     * @return Utilities
     */
    public static function get_instance()
    {
        if ( null == self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function get_date_format()
    {
        $fmt = get_option( 'date_format' );
        return apply_filters( 'qem_date_format', $fmt );
    }
    
    public function get_time_format()
    {
        $fmt = get_option( 'time_format' );
        return apply_filters( 'qem_time_format', $fmt );
    }

}