<?php
namespace Enteraddons\Core\Base;
/**
 * Enteraddons admin class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

if( !defined( 'WPINC' ) ) {
    die;
}

if( !class_exists('Widgets_Base') ) {

class Widgets_Base {

	private static $instance = null;

	function __construct() {
		$this->init_hooks();
	}
	
	//
	public static function getInstance() {

		if( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init_hooks() {
		add_action( 'elementor/elements/categories_registered', [ $this, 'registered_category'] );
		// Register New Widgets
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
	}

	//
	public function registered_category() {
        \Elementor\Plugin::instance()->elements_manager->add_category( 'enteraddons-elements-category', [
            'title' => esc_html__( 'Enteraddons', 'enteraddons' ),
        ], 1 );
        \Elementor\Plugin::instance()->elements_manager->add_category( 'enteraddons-header-footer-category', [
            'title' => esc_html__( 'Enteraddons Header Footer', 'enteraddons' ),
        ], 1 );
	}

    /**
     * Widgets Register 
     *
     *
     * @since 1.0.0
     *
     * @access public
     */

	public function register_widgets( $widgets_manager ) {

		$widgets = new \Enteraddons\Inc\Widgets_List();
		$activeWidget = get_option( ENTERADDONS_OPTION_KEY );
		if( !empty( $activeWidget['widgets'] ) && is_array( $activeWidget['widgets'] ) ) {
			foreach( $widgets->getAllElements() as $widget ) {
				if( !empty( $widget['name'] ) && in_array( $widget['name'], $activeWidget['widgets']  ) ) {
					$widgetName = $widget['name'];
					$prepareClassName = $this->class_name_prepare( $widgetName );
					$namespace = !empty( $widget['is_pro'] ) && $widget['is_pro'] == true ? 'EnteraddonsPro' : 'Enteraddons';
					$className = '\\'.$namespace.'\Widgets\\'.$prepareClassName.'\\'.$prepareClassName;
					if( class_exists( $className ) ) {
						$widgets_manager->register(  new $className );
					}
				}
			}
		}
	}

	/**
     * File name prepare
     * From widgets list
     *
     * @since 1.0.0
     *
     * @access public
     */
	public function file_name_prepare( $name ) {
		return str_replace( ['_', ' '], '-', $name );
	}
	/**
     * Class name prepare
     * From widgets list
     *
     * @since 1.0.0
     *
     * @access public
     */
	public function class_name_prepare( $name ) {
		return ucwords( str_replace( ['-', ' '], '_',  $name ), '_' );
	}

}

}
