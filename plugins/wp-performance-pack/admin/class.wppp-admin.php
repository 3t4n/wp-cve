<?php
/**
 * Admin settings base class (abstract). Adds admin menu and contains functions for both
 * simple and advanced view.
 *
 * @author Björn Ahrens <bjoern@ahrens.net>
 * @package WP Performance Pack
 * @since 0.8
 */

class WPPP_Admin {
	private $current_tab = '';
	private $view = 'simple';
	private $wppp = NULL;
	private $renderer = NULL;
	private $show_update_info = false;

	public function __construct( $wppp_parent ) {
		$this->wppp = $wppp_parent;

		if ( $this->wppp->is_network ) {
			add_action( 'network_admin_menu', array( $this, 'add_menu_page' ) );
		} else {
			// register setting only if not multisite - multisite performs validate on its own
			register_setting( 'wppp_options', WP_Performance_Pack::wppp_options_name, array( $this, 'validate_single_site' ) );
			add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		}
		add_action( 'wp_ajax_hidewpppsupportbox', function() { $today = new DateTime(); set_transient( 'wppp-support-box', $today->format('Y-m-d'), DAY_IN_SECONDS ); } );
	}

	public function add_menu_page() {
		if ( $this->wppp->is_network ) {
			$wppp_options_hook = add_submenu_page( 'settings.php', 'WP Performance Pack', 'Performance Pack', 'manage_options', 'wppp_options_page', array( $this, 'do_options_page' ) );
		} else {
			$wppp_options_hook = add_options_page( 'WP Performance Pack', 'Performance Pack', 'manage_options', 'wppp_options_page', array( $this, 'do_options_page' ) );
		}
		add_action('load-'.$wppp_options_hook, array ( $this, 'load_admin_page' ) );
	}

	/*
	 * Save and validate settings functions
	 */

	public function opts_diff_from_default( $input ) {
		$defopts = $this->wppp->get_options_default();
		foreach ( $defopts as $optname => $opt ) {
			if ( isset( $input[ $optname ] ) && ( $input[ $optname ] === $opt ) ) {
				unset( $input[ $optname ] );
			}
		}
		return $input;
	}

	public function validate_single_site( $input ) {
		$res = $this->validate( $input );
		$res = $this->opts_diff_from_default( $res );
		return $res;
	}

	public function validate( $input ) {
		$output = $this->wppp->options; // default output are current settings
		$current = ( isset ( $_GET['tab'] ) ) ? $_GET['tab'] : 'general';
		
		if ( ( $input === NULL ) && ( $current === 'general' ) ) {
			// No options are set, disable all modules
			foreach ( WP_Performance_Pack::$modinfo as $modname => $modinstance ) {
				$output[ 'mod_' . $modname ] = false;
			}			
		} else { //if ( isset( $input ) && is_array( $input ) ) {
			if ( isset( $this->wppp->modules[ $current ] ) ) {
				// process module options
				if ( $this->wppp->modules[ $current ] !== NULL )
					$output = $this->wppp->modules[ $current ]->validate_options( $input, $output, WP_Performance_Pack::$modinfo[ $current ] );
			} else {
				// process WPPP general options
				foreach ( WP_Performance_Pack::$options_default as $key => $val ) {
					if ( isset( $input[$key] ) ) {
						// validate set input values
						$output[ $key ] = ( $input[ $key ] == 'true' ? true : false );
						unset( $input[$key] );
					} else {
						// not set values are assumed as false or the respective value (not necessarily the default value)
						$output[ $key ] = false;
					} // if isset...
				} // foreach
				
				// process module activation
				foreach ( WP_Performance_Pack::$modinfo as $modname => $modinstance ) {
					if ( isset( $input[ 'mod_' . $modname ] ) ) {
						// validate set input values
						$output[ 'mod_' . $modname ] = ( $input[ 'mod_' . $modname ] == 'true' ? true : false );
						unset( $input[ 'mod_' . $modname ] );
					} else {
						// not set values are assumed as false or the respective value (not necessarily the default value)
						$output[ 'mod_' . $modname ] = false;
					} // if isset...
				} // foreach
			}
		}		
		return $output;
	}

	function update_wppp_settings () {
		if ( current_user_can( 'manage_network_options' ) ) {
			check_admin_referer( 'update_wppp', 'wppp_nonce' );
			$input = array();
			$def_opts = $this->wppp->get_options_default();
			foreach ( $def_opts as $key => $value ) {
				if ( isset( $_POST['wppp_option'][$key] ) ) {
					$input[$key] = sanitize_text_field( $_POST['wppp_option'][$key] );
				}
			}
			$this->wppp->options = $this->validate( $input );
			$this->wppp->update_option( WP_Performance_Pack::wppp_options_name, $this->wppp->options );
		}
	}

	/*
	 * Settings page functions
	 */

	private function load_renderer () {
		if ( $this->renderer == NULL) {
			include( sprintf( "%s/class.admin-renderer.php", dirname( __FILE__ ) ) );
			if ( isset( $this->wppp->modules[ $this->current_tab ] ) ) {
				$this->renderer = $this->wppp->modules[ $this->current_tab ]->load_renderer();
			} else {
				$this->renderer = new WPPP_Admin_Renderer( $this->wppp );
			}
		}
	}

	function load_admin_page () {
		if ( $this->wppp->is_network ) {
			if ( isset( $_GET['action'] ) && $_GET['action'] === 'update_wppp' ) {
				$this->update_wppp_settings();
				$this->show_update_info = true;
			}
		}
		if ( $this->current_tab === '' ) {
			$this->current_tab = ( isset ( $_GET['tab'] ) && isset( $this->wppp->modules[ $_GET['tab'] ] ) ) ? $_GET[ 'tab' ] : 'general';
		}
		$this->load_renderer();
		$this->renderer->enqueue_scripts_and_styles();
		$this->renderer->add_help_tab();
		$this->renderer->add_help_sidebar();
	}

	public function do_options_page() {
		$current = ( isset ( $_GET['tab'] ) ) ? $_GET['tab'] : 'general';
		if ( $this->wppp->is_network ) {
			$formaction = network_admin_url('settings.php?page=wppp_options_page&action=update_wppp&tab=' . $current);
		} else {
			$formaction = admin_url( 'options.php?tab=' . $current );
		}

		if ( $this->show_update_info ) {
			echo '<div class="updated"><p>', __( 'Settings saved.', 'wp-performance-pack' ), '</p></div>';
		}

		$this->load_renderer();
		$this->renderer->render_page( $formaction );
	}
}
