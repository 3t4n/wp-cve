<?php
/**
 * The superclass of FDP
 *
 * Handles the structure of the FDP backend pages
 *
 * @class       Eos_Fdp_Plugins_Manager_Page
 * @version     1.0.0
 * @package     Freesoul Deactivate Plugins\Abstracts
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Abstract FDP Plugins Manager Page
 *
 * Implemented by classes using the same pattern.
 *
 * @version  1.0.0
 * @package  Freesoul Deactivate Plugins\Abstracts
 */
abstract class Eos_Fdp_Plugins_Manager_Page {

	/**
	 * Page title.
	 *
	 * @since 1.9.0
	 * @var string
	 */
	public $title;

	/**
	 * Page description.
	 *
	 * @since 1.9.0
	 * @var string
	 */
	public $description;

	/**
	 * Active plugins.
	 *
	 * @since 1.9.0
	 * @var array
	 */
	public $active_plugins  = array();

	/**
	 * Inactive plugins.
	 *
	 * @since 1.9.0
	 * @var array
	 */
	public $inactive_plugins  = array();

	/**
	 * Plugins which have a folder in the plugins directory.
	 *
	 * @since 1.9.0
	 * @var array
	 */
	public $plugins_by_dirs = array();

	/**
	 * Section ID.
	 *
	 * @since 1.9.0
	 * @var string
	 */
	public $section_id;

	/**
	 * Activation.
	 *
	 * @since 1.9.0
	 * @var bool
	 */
	public $active;

	/**
	 * Page slug.
	 *
	 * @since 1.9.0
	 * @var string
	 */
	public $page_slug;

	/**
	 * Page icon.
	 *
	 * @since 1.9.0
	 * @var string
	 */
	public $dashicon = false;

	/**
	 * Saving button CSS class.
	 *
	 * @since 1.9.0
	 * @var string
	 */
	public $button_class;

	/**
	 * Section CSS class.
	 *
	 * @since 1.9.0
	 * @var string
	 */
	public $section_common_class;

	/**
	 * Default constructor.
	 *
	 * @param string $page_slug Page slug
	 * @param string $title Page title
	 * @param string|bool $dashicon Page icon.
	 * @param bool $active activation.
	 */
	public function __construct( $page_slug, $title = '', $dashicon = false, $active = true, $description = '' ) {
		if (
		apply_filters(
			'fdp_hide_' . sanitize_key( str_replace( 'eos_dp_', '', $page_slug ) ),
			(
			! current_user_can( 'activate_plugins' )
			&& ! current_user_can( 'fdp_plugins_viewer' )
			&& ! defined( 'FDP_EMERGENCY_LOG_ADMIN' )
			)
		)
		) {
			?>
	  	<h2><?php esc_html_e( 'Sorry, you have not the right for this page', 'freesoul-deactivate-plugins' ); ?></h2>
			<?php
			return;
		}
		$this->title       = $title;
		$this->description = $description;
		$this->active      = $active;
		$this->page_slug   = 'eos_dp_' . str_replace( 'eos_dp_', '', $page_slug );
			
		add_filter(
			'fdp_pages',
			function( $arr ) {
				if ( ! in_array( $this->page_slug, $arr ) ) {
					$arr[] = sanitize_key( $this->page_slug );
				}
				return $arr;
			}
		);
		if ( $dashicon ) {
			$this->dashicon = $dashicon;
		}
		$this->section_id      = str_replace( '_', '-', $page_slug ) . '-section';
		$this->active_plugins  = $this->get_active_plugins();
		$this->plugins_by_dirs = $this->get_plugins();
		$inactive_plugins = array();
		if( $this->plugins_by_dirs && !empty( $this->plugins_by_dirs ) ){
			$active_plugins_names = array_map( 'dirname', $this->active_plugins );
			foreach( array_keys( $this->plugins_by_dirs ) as $inactive_plugin_file ){
				$inactive_plugin = dirname( $inactive_plugin_file );
				if( !in_array( $inactive_plugin, $inactive_plugins ) && strlen( $inactive_plugin ) > 1 && !in_array( $inactive_plugin, $active_plugins_names ) ){
					$inactive_plugins[] = $inactive_plugin;
				}
			}
		}
		$this->inactive_plugins = array_unique( $inactive_plugins );
		$this->init();
		$this->header();
		$this->before_section( $this->page_slug );
		$this->section( $this->page_slug );
		$this->after_section( $this->page_slug );
		$this->footer( $this->button_class );
		$this->after_footer( $this->page_slug );
	}

	/**
	 * Initialization.
	 *
	 * @since  1.9.0
	 */	
	public function init() {

	}

	/**
	 * Output the before section.
	 *
	 * @since  1.9.0
	 * @param  string $page_slug The page slug.
	 */
	abstract function before_section( $page_slug );

	/**
	 * Output the section.
	 *
	 * @since  1.9.0
	 * @param  string $page_slug The page slug.
	 */
	public function section( $page_slug ) {
		$nonces = $this->get_nonces_map();
		if ( isset( $nonces[ $page_slug ] ) ) {
			wp_nonce_field( $nonces[ $page_slug ], $nonces[ $page_slug ] );
		}
		?>
	<section id="<?php echo esc_attr( $this->section_id ); ?>" class="
							<?php
							echo esc_attr( apply_filters( 'fdp_section_class_name', 'eos-dp-section' ) );
							echo ' ' . esc_attr( $this->section_common_class );
							?>
	" data-page_slug="<?php echo esc_attr( $page_slug ); ?>">
		<?php do_action( 'eos_dp_before_wrapper' ); ?>
	  <div id="eos-dp-wrp">
		<table id="eos-dp-setts"  data-zoom="1"<?php echo wp_kses_post( $this->dataset ); ?>>
		  <tbody class="<?php echo esc_attr( apply_filters( 'fdp_tbody_css_class', str_replace( '_', '-', esc_attr( $page_slug ) ) ) ); ?>">
		  <?php $this->tableHead( $page_slug ); ?>
		  <?php $this->tableBody( $page_slug ); ?>
		  </tbody>
		</table>
		<?php
		do_action( 'fdp_after_table' );
		do_action( 'fdp_after_table_' . $this->section_id );
		?>
	  </div>
	</section>
		<?php
		do_action( 'fdp_after_section' );
		do_action( 'fdp_after_section_' . $this->section_id );
	}

	/**
	 * After section.
	 *
	 * @since  2.1.5
	 * @param  string $page_slug The page slug.
	 * 
	 */
	public function after_section( $page_slug ) {

	}

	/**
	 * After section final.
	 *
	 * @since  2.1.5
	 * @param  string $page_slug The page slug.
	 * 
	 */
	final public function after_footer( $page_slug ) {
		$fdp = array(
			'pro' 			 => array( 'active' => defined( 'FDP_PRO_ACTIVE' ) ),
			'active_plugins' => $this->active_plugins,
			'plugins_count'  => count( $this->active_plugins ),
			'page_slug' 	 => $this->page_slug,
			'free'			 => array( 'version' => EOS_DP_VERSION ),
			'ajaxurl'		 => admin_url( 'admin-ajax.php' ),
			'max_rows_reached'	 => esc_html( 'You have reached the maximum number of rows available with the free version', 'freesoul-deactivate-plugins' ),
			'max_limit_reached'	 => esc_html( 'Max limit reached', 'freesoul-deactivate-plugins' )
		);
		if( defined( 'EOS_DP_PRO_VERSION' ) ) {
			$fdp['pro']['version'] = EOS_DP_PRO_VERSION;
		}
		?>
		<div id="fdp-general-msg" class="eos-hidden" style="border:1px solid #D3C4B8;position:fixed;width:500px;height:300px;z-index:999999;background:#fff;top:50%;<?php echo is_rtl() ? 'right' : 'left'; ?>:50%;margin-<?php echo is_rtl() ? 'right' : 'left'; ?>:-250px;margin-top:-150px">
			<h2 id="fdp-general-msg-title" style="background:#D3C4B8;color:#fff;padding:10px;margin-top:-10px;position:relative;top:0"></h2>
			<p id="fdp-general-msg-body" style="padding:10px"></p>
			<div style="position:absolute;padding:10px;bottom:0;left:0;right:0;text-align:<?php echo is_rtl() ? 'left' : 'right'; ?>">
				<button id="fdp-close-general-message" class="button" onclick="fdp.close_alert();"><?php esc_html_e( 'Close' ); ?></button>
			</div>
		</div>		
		<script id="fdp-js">
		var fdp = <?php echo wp_json_encode( $fdp ); ?>;
		fdp.general_message = document.getElementById('fdp-general-msg');
		fdp.alert = function(title,message){
			document.getElementById('fdp-general-msg-title').innerText = title;
			document.getElementById('fdp-general-msg-body').innerText = message;			
			fdp.general_message.className = '';
		}
		fdp.close_alert = function(){			
			fdp.general_message.className = 'eos-hidden';
		}
		</script>
		<?php
	}

	/**
	 * Page header.
	 *
	 * @since  1.9.0
	 * 
	 */
	public function header() {
		require_once EOS_DP_PLUGIN_DIR . '/admin/templates/partials/eos-dp-navigation.php';
		eos_dp_alert_plain_permalink();
		if ( ! isset( $_GET['export_file'] ) ) {
			eos_dp_navigation();
		}
	}

	/**
	 * Page footer.
	 *
	 * @since  1.9.0
	 * 
	 */
	public function footer( $button_class ) {
		require_once EOS_DP_PLUGIN_DIR . '/admin/templates/partials/eos-dp-footer.php';
		if ( current_user_can( 'activate_plugins' ) ) {
			eos_dp_save_button( $this->$button_class );
		}
	}

	/**
	 * Get all plugins.
	 *
	 * @since  1.9.0
	 * @return array
	 */
	public function get_plugins() {
		$plugin_root = WP_PLUGIN_DIR;
		// Files in wp-content/plugins directory.
		$plugins_dir  = @ opendir( $plugin_root );
		$plugin_files = array();
		if ( $plugins_dir ) {
			while ( ( $file = readdir( $plugins_dir ) ) !== false ) {
				if ( substr( $file, 0, 1 ) == '.' || strpos( '_' . $file, 'freesoul-deactivate-plugins' ) > 0 ) {
					continue;
				}
				if ( is_dir( $plugin_root . '/' . $file ) ) {
					$plugins_subdir = @ opendir( $plugin_root . '/' . $file );
					if ( $plugins_subdir ) {
						while ( ( $subfile = readdir( $plugins_subdir ) ) !== false ) {
							if ( substr( $subfile, 0, 1 ) == '.' ) {
									continue;
							}
							if ( substr( $subfile, -4 ) == '.php' ) {
									$plugin_files[] = "$file/$subfile";
							}
						}
						closedir( $plugins_subdir );
					}
				} else {
					if ( substr( $file, -4 ) == '.php' ) {
						$plugin_files[] = $file;
					}
				}
			}
			closedir( $plugins_dir );
		}
		if ( empty( $plugin_files ) ) {
			return array();
		}
		foreach ( $plugin_files as $plugin_file ) {
			if ( ! is_readable( "$plugin_root/$plugin_file" ) ) {
				continue;
			}
			$plugins[ plugin_basename( $plugin_file ) ] = 1;
		}
		uasort( $plugins, 'eos_dp_sort_uname_callback' );
		return apply_filters( 'eos_dp_get_plugins', $plugins );
	}

	/**
	 * Get active plugins.
	 *
	 * @since  1.9.0
	 * @return array
	 */
	public function get_active_plugins() {
		return eos_dp_active_plugins();
	}

	/**
	 * Get array of nonces.
	 *
	 * @since  1.9.0
	 * @return array
	 */	
	abstract function get_nonces_map();

	/**
	 * Output table body.
	 *
	 * @since  1.9.0
	 * @param  string $page_slug The page slug.
	 */	
	abstract public function tableBody( $page_slug );
}
