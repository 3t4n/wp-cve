<?php
/*
Plugin Name: Power-Ups for Elementor 
Plugin URI: https://elementor.wppug.com/power-ups-for-elementor/
Description: Add new addons, widgets and features for Elementor page builder, like Slider, Team, Testimonials, Post Carousel, Countdown and Portfolio.
Author: RexDot
Version: 1.2.2
Author URI: https://wppug.com
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'pwre_fs' ) ) {
   // Create a helper function for easy SDK access.
   function pwre_fs() {
       global $pwre_fs;

       if ( ! isset( $pwre_fs ) ) {
           // Include Freemius SDK.
           require_once dirname(__FILE__) . '/freemius/start.php';

           $pwre_fs = fs_dynamic_init( array(
               'id'                  => '7823',
               'slug'                => 'power-ups-for-elementor',
               'type'                => 'plugin',
               'public_key'          => 'pk_162d875ae24ef7dabdf08365afdca',
               'is_premium'          => false,
               'has_addons'          => false,
               'has_paid_plans'      => false,
               'menu'                => array(
                   'slug'           => 'powerups_for_elementor',
                   'first-path'     => 'admin.php?page=powerups_for_elementor',
               ),
           ) );
       }

       return $pwre_fs;
   }

   // Init Freemius.
   pwre_fs();
   // Signal that SDK was initiated.
   do_action( 'pwre_fs_loaded' );
}

//Create Elementor Category
if ( ! function_exists('elpug_powerups_cat') ) {   
   function elpug_powerups_cat() {
      \Elementor\Plugin::$instance->elements_manager->add_category( 
         'elpug-elements',
         [
            'title' => __( 'Powerfolio / Power-Ups for Elementor', 'elpug' ),
            'icon' => 'fa fa-plug', //default icon
         ],
         2 // position
      );
   }

   add_action( 'elementor/init', 'elpug_powerups_cat');
}

/*
 * Plugin Options
 */
require ('panel.php');

/*
 * Elementor Portfolio
 */
if ( ! class_exists('ELPT_portfolio_Post_Types') ) {
   if ( get_option("elpug_portfolio_switch") == 1) {
      require ('modules/portfolio/elementor-portfolio.php');
   }
}
/*
 * Slider
 */
if ( ! function_exists('elpug_slider_module') ) {
   if ( get_option("elpug_slider_switch") == 1) {
	  require ('modules/slider-addon-for-elementor/slider-addon-for-elementor.php');
   }
}
/*
 * Blogroll
 */
if ( ! function_exists('elpug_blogroll_module') ) {
   if ( get_option("elpug_blogroll_switch") == 1) {
	  require ('modules/pug-blogroll/pug-blogroll.php');
   }
}

/*
 * Team
 */
if ( ! function_exists('elpug_team_module') ) {
   if ( get_option("elpug_team_switch") == 1) {
	  require ('modules/team-addon-for-elementor/team-addon-for-elementor.php');
   }
}

/*
 * Testimonials
 */
if ( ! function_exists('elpug_testimonials_module') ) {
   if ( get_option("elpug_testimonials_switch") == 1) {
	  require ('modules/testimonial-addon-for-elementor/testimonial-addon-for-elementor.php');
   }
}
/*
 * Countdown
 */
if ( ! function_exists('elpug_countdown_module') ) {
   if ( get_option("elpug_countdown_switch") == 1) {
      require ('modules/countdown/countdown.php');
   }
}
/*
 * Magic Buttons
 */
if ( ! function_exists('pwr_magic_buttons_load') ) {
   if ( get_option("elpug_magic_buttons_switch") == 1) {
      require ('modules/magic-buttons-for-elementor/magic-buttons-for-elementor.php');
   }
}




