<?php

Namespace Unbloater;

defined( 'ABSPATH' ) || die();

class Unbloater_Admin {
	
	/**
	 * Class constructor
	 */
	public function __construct() {
		if( Unbloater_Helper::is_ub_active_for_network() ) {
			// Add network admin menu item
			add_action( 'network_admin_menu', array( $this, 'add_network_options_page' ) );
			// Add network options saving function
			add_action( 'network_admin_edit_unbloater', array( $this, 'save_network_options' ) );
			// Add network admin success notice
			add_action( 'network_admin_notices', array( $this, 'network_admin_updated_notice' ) );
			// Add plugin list settings link
			add_filter( 'network_admin_plugin_action_links_unbloater/unbloater.php', array( $this, 'settings_link_filter' ), 10, 4 );
		} else {
			// Add admin menu item
			add_action( 'admin_menu', array( $this, 'add_options_page' ) );
			// Add plugin list settings link
			add_filter( 'plugin_action_links_unbloater/unbloater.php', array( $this, 'settings_link_filter' ), 10, 4 );
		}
    }
	
    /**
	 * Add submenu page
	 */
    public function add_options_page() {
        add_submenu_page(
            'options-general.php',
            __( 'Unbloater Settings', 'unbloater' ),
            __( 'Unbloater', 'unbloater' ),
            'manage_options',
            'unbloater',
            array( $this, 'render_plugin_settings_page' )
        );
    }
	
    /**
	 * Add network submenu page
	 */
    public function add_network_options_page() {
        add_submenu_page(
            'settings.php',
            __( 'Unbloater Settings', 'unbloater' ),
            __( 'Unbloater', 'unbloater' ),
            'manage_network_options',
            'unbloater',
            array( $this, 'render_plugin_settings_page' )
        );
    }
	
	/**
	 * Save options on network settings screen
	 */
	public function save_network_options() {
		
		if( !current_user_can( 'manage_network_options' ) )
			return;
		
		if( isset( $_POST['unbloater_settings'] ) ) {
			$options = $_POST['unbloater_settings'];
			update_network_option( null, 'unbloater_settings', $options );
		}
		
		wp_redirect(
			add_query_arg(
				array(
					'page' => 'unbloater',
					'updated' => 'true'
				),
				network_admin_url( 'settings.php' )
			)
		);
		exit;
	}
	
	/**
	 * Success admin notice after saving network options
	 */
	public function network_admin_updated_notice() {
		if( isset( $_GET['page'] ) && 'unbloater' == $_GET['page'] && isset( $_GET['updated'] ) && 'true' == $_GET['updated'] ) {
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php _e( 'Settings saved.', 'unbloater' ); ?></p>
		</div>
		<?php
		}
	}
	
	/**
	 * Custom do_settings_sections function for custom output
	 */
	public function do_settings_sections( $page ) {
		global $wp_settings_sections, $wp_settings_fields;
	 
		if( !isset( $wp_settings_sections[$page] ) )
			return;
		
		foreach( (array) $wp_settings_sections[$page] as $section ) {
			
			if( $section['title'] )
				echo "<h2 id='{$section['id']}'>{$section['title']}</h2>\n";
			
			if( $section['callback'] )
				call_user_func( $section['callback'], $section );
			
			if( !isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section['id']] ) )
				continue;
			
			echo '<table class="form-table" role="presentation">';
			do_settings_fields( $page, $section['id'] );
			echo '</table>';
		}
		
	}
	
	/**
	 * Render plugin settings page
	 */
	public function render_plugin_settings_page() {
		
		if( !Unbloater_Helper::is_ub_active_for_network() && !current_user_can( 'manage_options' ) )
			return;
		if( Unbloater_Helper::is_ub_active_for_network() && !current_user_can( 'manage_network_options' ) )
			return;
		?>
		
		<style>
		.unbloater-page .unbloater-page-description {
			font-size: 15px;
		}
		.unbloater-page .unbloater-page-description + hr {
			margin: 2em 0;
		}
		.unbloater-page .quick-nav {
		}
		.unbloater-page .form-table th,
		.unbloater-page .form-table td {
			padding: 1.5em 1.5em 0.5em 0;
		}
		.unbloater-page .form-table:last-of-type {
			margin-bottom: 3em;
		}
		.unbloater-page .setting-disabled-message {
			line-height: 1.3;
			margin: 0 0 0.5em;
		}
		.unbloater-page .setting-disabled {
			opacity: 0.5;
		}
		.unbloater-page h2 {
			padding-top: 3em;
			margin-top: 0;
		}
		</style>
		
        <div class="wrap unbloater-page">
			
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<p class="unbloater-page-description"><?php _e( "Use the below settings to unbloat your site from unwanted output. Remove admin nags and notifications, unnecessary items and performance-draining code. Please note that some options should only be set if you understand their consequences. If in doubt, leave options unchecked.", 'unbloater' ); ?></p>
			
			<hr/>
			
			<nav class="quick-nav">
				<p><?php _e( 'Jump to section', 'unbloater' ) ?>:&nbsp;
					<a href="#unbloater_section_core_backend"><?php _e( 'Core (Backend)', 'unbloater' ); ?></a>
					&bull;
					<a href="#unbloater_section_core_frontend"><?php _e( 'Core (Frontend)', 'unbloater' ); ?></a>
					<?php if( Unbloater_Helper::is_wp_version_at_least( '5.0' ) || Unbloater_Helper::is_plugin_active( 'gutenberg/gutenberg.php' ) ) { ?>
					&bull;
					<a href="#unbloater_section_block_editor"><?php _e( 'Block Editor', 'unbloater' ); ?></a>
					<?php } ?>
					<?php
					$this->maybe_print_quicknav_item( array( 'advanced-custom-fields/acf.php', 'advanced-custom-fields-pro/acf.php' ), 'Advanced Custom Fields', 'acf' );
					$this->maybe_print_quicknav_item( 'autoptimize/autoptimize.php', 'Autoptimize', 'autoptimize' );
					$this->maybe_print_quicknav_item( 'seo-by-rank-math/rank-math.php', 'Rank Math', 'rankmath' );
					$this->maybe_print_quicknav_item( 'searchwp/index.php', 'SearchWP', 'searchwp' );
					$this->maybe_print_quicknav_item( 'autodescription/autodescription.php', 'The SEO Framework', 'autodescription' );
					$this->maybe_print_quicknav_item( 'woocommerce/woocommerce.php', 'WooCommerce', 'woocommerce' );
					?>
				</p>
			</nav>			
			
			<form action="<?php echo Unbloater_Helper::is_ub_active_for_network() ? esc_url( add_query_arg( 'action', 'unbloater', network_admin_url( 'edit.php' ) ) ) : 'options.php'; ?>" method="post">
				
				<?php
				settings_fields( 'unbloater' );
				$this->do_settings_sections( 'unbloater' );
				submit_button( __( 'Save Changes', 'unbloater' ) );
				?>
				
			</form>
			
		</div>
		
        <?php
	}
	
	/**
	 * Settings link function
	 */
	public function settings_link_filter( $actions, $plugin_file, $plugin_data, $context ) {
		$settings_url = add_query_arg(
			array(
				'page' => 'unbloater'
			),
			Unbloater_Helper::is_ub_active_for_network() ? network_admin_url( 'settings.php' ) : admin_url( 'options-general.php' )
		);
		$settings = '<a href="' . $settings_url . '">' . __( 'Settings', 'unbloater' ) . '</a>';
		array_unshift( $actions, $settings );
		return $actions;
	}
	
	/**
	 * Print quick navigation item if plugin ins active
	 */
	public function maybe_print_quicknav_item( $plugin_file, $name, $slug ) {
		if( Unbloater_Helper::is_plugin_active( $plugin_file ) ) {
		?>
		&bull;
		<a href="#unbloater_section_<?php echo $slug; ?>"><?php echo $name; ?></a>
		<?php
		}
	}
	
}
