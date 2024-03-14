<?php
/**
 * @author Björn Ahrens
 * @package WP Performance Pack
 * @since 2.2
 */

class WPPP_Disable_Widgets extends WPPP_Module {
	private $known_default_widgets = NULL;
	private $default_widget_loaded = false;

	public function load_renderer () {
		return new WPPP_Disable_Widgets_Advanced( $this->wppp );
	}

	function early_init() {
		$this->known_default_widgets = $this->wppp->get_option( 'wppp_known_default_widgets' );

		// prevents "wp-includes/default-widgets.php" from loading (used in "wp-includes/functions.php /wp_maybe_load_widgets")
		add_filter( 'load_default_widgets', '__return_false' );
		// This action (function defined in "wp-includes/widgets.php") would try to register all widget classes from "default-widgets.php"
		remove_action( 'init', 'wp_widgets_init', 1 );
	}

	function init() {
		// Load and register only the via WPPP enabled core widgets
		if ( is_array( $this->known_default_widgets ) ) {
			foreach( $this->known_default_widgets as $widget => $files ) {
				if ( !in_array( $widget, $this->wppp->options[ 'disabled_widgets' ] ) ) {
					foreach ( $files as $file )
						include_once( ABSPATH . WPINC . '/widgets/' . $file );
					register_widget( $widget );
					$this->default_widget_loaded = true;
				}
			}
		}

		// Because "load_default_widgets" in early_init did return false, the widget submenu wasn't added yet
		add_action( '_admin_menu', 'wp_widgets_add_menu' );

		// The "original" action "wp_widgets_init" normally fires the action "widgets_init".
		// This hasn't happend yet (because the action was removed in early_init) do so now
		do_action( 'widgets_init' );
	}

	function validate_options( &$input, $output, $default ) {
		// settings form returns in wppp_options[ 'enable_widgets' ] an array of all enabled widgets,
		// but the settings save disabled widgets
		$disabled = array();
		foreach( $this->known_default_widgets as $widget => $files ) {
			if ( !isset( $input[ 'disabled_widgets' ] ) || !in_array( $widget, $input[ 'disabled_widgets' ] ) )
				$disabled[] = $widget;
		}
		$input[ 'disabled_widgets' ] = $disabled;
		$output = parent::validate_options( $input, $output, $default );
		return $output;
	}
}