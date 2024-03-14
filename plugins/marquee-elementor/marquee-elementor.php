<?php
/**
 * Plugin Name: Marquee Elementor with Posts
 * Description: Marquee Elementor is a free plugin to make your own marquee in elementor in easy and professional way.
 * Author:      Anas Edreesi
 * Author URI:  https://anas.edreesi.com
 * Text Domain: marquee-elementor
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Version:     1.2.0
 *
 * @package Marquee Elementor
 **/

namespace MRQ;
defined( 'ABSPATH' ) || exit;

if ( ! version_compare( PHP_VERSION, '7.4', '>=' ) ) {
	add_action( 'admin_notices', function() {
    $message = sprintf( esc_html__( 'Marquee Elementor requires PHP version %s+, plugin is currently NOT RUNNING.', 'elementor' ), '7.4' );
    $html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
    echo wp_kses_post( $html_message );
  });
} elseif ( ! version_compare( get_bloginfo( 'version' ), '5.2', '>=' ) ) {
	add_action( 'admin_notices', function() {
    $message = sprintf( esc_html__( 'Marquee Elementor requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'elementor' ), '5.2' );
    $html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
    echo wp_kses_post( $html_message );
  } );
} 


class marqquee_elementor_loader{

  private static $_instance = null;

  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }


  private function include_widgets_files(){
    require_once dirname( __FILE__ ) . '/widgets/marquee.php' ;
    require_once dirname( __FILE__ ) . '/widgets/posts.php' ;
  }

  public function register_widgets(){

    $this->include_widgets_files();

    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\marquee());
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\posts());

  }

  public function __construct(){
    add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets'], 99);
    add_action( 'elementor/editor/before_enqueue_styles', [$this, 'adminstyle']);
    add_action( 'wp_enqueue_scripts', [$this, 'userstyle'],99);
  }
  function adminstyle() {
    wp_register_style( 'mrqicon', plugins_url( 'assets/css/mrqicon.css', __FILE__ ) );
    wp_enqueue_style( 'mrqicon' );  
  }
  function userstyle() {
    wp_register_style( 'style', plugins_url( 'assets/css/style.css', __FILE__ ) );
    wp_enqueue_style( 'style' );    
  }  
}



//add_action( 'elementor/editor/before_enqueue_styles', 'my_plugin_editor_styles' );
// Instantiate Plugin Class
marqquee_elementor_loader::instance();
