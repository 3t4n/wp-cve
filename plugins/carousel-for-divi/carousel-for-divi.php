<?php
/*
  Plugin Name:        Carousel for Divi
  Plugin URI:         https://danielvoelk.de/en/divi-carousel/
  Description:        A plugin to transform every module to a carousel in the Divi theme.
  Version:            1.2.6
  Requires at least:  4.9
  Requires PHP:       7.2
  Author:             Daniel Völk
  Author URI:         https://shop.danielvoelk.de/
  License:            GPL2
  License URI:        https://www.gnu.org/licenses/gpl-2.0.html
  
  Carousel for Divi is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 2 of the License, or
  any later version.
  
  Carousel for Divi is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.
  
  You should have received a copy of the GNU General Public License
  along with Carousel for Divi. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
  */

  /** Our plugin class */
  class DiviCarousel {
    public function __construct() {

      /** add filter files */
      add_action( 'wp_enqueue_scripts', [ $this, 'dc_add_files' ] );  

      /** add Upgrade link */
      add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), [ $this, 'filter_action_links' ] );

      /** add Documentation link */
      add_filter( 'plugin_row_meta', [ $this, 'add_documentation_link' ], 10, 2 );
 
    }

    /** Step 2 (add item). */
    public function my_plugin_menu() {
      $page_title = 'Divi Carousel Options';
      $menu_title = 'Divi Carousel';
      $capability = 'manage_options'; // Only users that can manage options can access this menu item.
      $menu_slug  = 'divi-filter'; // unique identifier.
      $callback   = [ $this, 'my_plugin_options' ];
      add_options_page( $page_title, $menu_title, $capability, $menu_slug, $callback );
    }

    public function filter_action_links( $links ) {

      $links['upgrade'] = '<a style="font-weight: bold;" href="https://shop.danielvoelk.de/" target="_blank">Go Premium</a>';

      return $links;
    }
    
    public function dc_add_files() {

      wp_register_script('dc-script', plugins_url('dc-script.js', __FILE__), array('jquery'),'1.2.6', true);
      wp_enqueue_script('dc-script');

      wp_register_style('dc-style', plugins_url('dc-style.css', __FILE__), array(), '1.2.6');
      wp_enqueue_style('dc-style');
    
    }

    public function add_documentation_link( $links, $file ) {    
      if ( plugin_basename( __FILE__ ) == $file ) {
        $row_meta = array(
          'docs'    => '<a href="https://carousel.danielvoelk.de/" target="_blank">Documentation</a>'
        );

        return array_merge( $links, $row_meta );
      }
      return (array) $links;
    }

  }

new DiviCarousel();