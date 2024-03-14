<?php
/**
 * The admin-options page of the plugin.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/admin
 */

namespace Podcast_Player\Backend\Admin;

use Podcast_Player\Helper\Functions\Getters as Get_Fn;
use Podcast_Player\Helper\Functions\Utility as Utility_Fn;
use Podcast_Player\Helper\Store\StoreManager;

/**
 * The admin-options page of the plugin.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/admin
 * @author     vedathemes <contact@vedathemes.com>
 */
class Options {

	/**
	 * Holds the instance of this class.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    object
	 */
	protected static $instance = null;

	/**
	 * Holds different modules of Podcast player options page.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array $sections
	 */
	private $modules = array();

	/**
	 * Holds different sections of Podcast player options page.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array $sections
	 */
	private $sections = array();

	/**
	 * Holds different blocks of Podcast player documentation page.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array $doc_blocks
	 */
	private $doc_blocks = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Declare different modules of Podcast player options page (Sections).
		$this->modules = array(
			'options'     => array(
				'label' => esc_html__( 'Home', 'podcast-player' ),
			),
			'settings' => array(
				'label' => esc_html__( 'Settings', 'podcast-player' ),
			),
			'toolkit'  => array(
				'label' => esc_html__( 'Toolkit', 'podcast-player' ),
			),
			'help'     => array(
				'label' => esc_html__( 'Help & Support', 'podcast-player' ),
			),
			'products'  => array(
				'label' => esc_html__( 'Other Products', 'podcast-player' ),
			),
		);

		// Declare different sections of Podcast player options page (Sections).
		$this->sections = array(
			'general'  => esc_html__( 'General', 'podcast-player' ),
			'optimize' => esc_html__( 'Optimization & Security', 'podcast-player' ),
			'advanced' => esc_html__( 'Advanced', 'podcast-player' ),
		);

		// Declare different sections for podcast player documentation page.
		$this->doc_blocks = array(
			'getting_started' => esc_html__( 'Getting Started', 'podcast-player' ),
			'faq'             => esc_html__( 'Frequently Asked Questions', 'podcast-player' ),
		);
	}

	/**
	 * Register hooked functions.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
        $inst = self::get_instance();
		add_action( 'admin_menu', array( $inst, 'add_options_page' ) );
		add_action( 'admin_init', array( $inst, 'add_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $inst, 'page_scripts' ) );
		add_action( 'podcast_player_options_page_content', array( $inst, 'display_content' ) );
		add_action( 'wp_ajax_pp_feed_editor', array( $inst, 'feed_editor_new' ) );
		add_action( 'wp_ajax_nopriv_pp_feed_editor', array( $inst, 'feed_editor_new' ) );
    }

	/**
	 * Array of setting fields.
	 *
	 * Array of settings fields to be used on pp options page.
	 *
	 * @since    1.0.0
	 */
	public function get_setting_fields() {
		return apply_filters(
			'podcast_player_setting_fields',
			array(
				'refresh_interval' => array(
					'name'        => esc_html__( 'Podcast update interval (in minutes).', 'podcast-player' ),
					'id'          => 'refresh_interval',
					'description' => esc_html__( 'Check and Update your podcast automatically after every mentioned time interval. By default your podcast will be auto updated after every 720 minutes (i.e., 12 hours).', 'podcast-player' ),
					'link'        => '',
					'type'        => 'number',
					'default'     => 720,
					'section'     => 'general',
					'input_attrs' => array(
						'step' => 5,
						'min'  => 0,
						'size' => 3,
					),
				),
				'hide_data'        => array(
					'name'        => esc_html__( 'Minimize unintentional exposure of podcast data', 'podcast-player' ),
					'id'          => 'hide_data',
					'description' => esc_html__( 'Minimize unintentional display of podcast data like audio url and podcast feed url from front-end page source.', 'podcast-player' ),
					'link'        => '',
					'type'        => 'checkbox',
					'default'     => '',
					'section'     => 'general',
				),
				'update_method' => array(
					'name'        => esc_html__( 'Update podcasts using WP Cron.', 'podcast-player' ),
					'id'          => 'update_method',
					'description' => esc_html__( 'Default update method is very efficient. However, if that\'s not working due to caching plugin, you can use cron update method.', 'podcast-player' ),
					'link'        => '',
					'type'        => 'checkbox',
					'default'     => '',
					'section'     => 'general',
				),
				'img_save'         => array(
					'name'        => esc_html__( 'Image Optimization', 'podcast-player' ),
					'id'          => 'img_save',
					'description' => esc_html__( 'Download podcast images to your WordPress media folder and display smaller sized images in the player.', 'podcast-player' ),
					'link'        => '',
					'type'        => 'checkbox',
					'default'     => 'yes',
					'section'     => 'optimize',
				),
				'rel_external'     => array(
					'name'        => esc_html__( 'Add rel attributes.', 'podcast-player' ),
					'id'          => 'rel_external',
					'description' => esc_html__( 'Add noopener, noreferrer and nofollow rel attributes to external links in episode content. It should improve SEO and security of your website.', 'podcast-player' ),
					'link'        => '',
					'type'        => 'checkbox',
					'default'     => '',
					'section'     => 'optimize',
				),
				'use_mejs_audio'         => array(
					'name'        => esc_html__( 'Use old Mediaelement JS.', 'podcast-player' ),
					'id'          => 'use_mejs_audio',
					'description' => esc_html__( 'Podcast player will not use Mediaelement JS for audio podcasts. If you face any issues with the new method, enable this option. Please report the issue as well, as this option is temporary and will be removed in future updates.', 'podcast-player' ),
					'link'        => '',
					'type'        => 'checkbox',
					'default'     => '',
					'section'     => 'advanced',
				),
				'keep_old'         => array(
					'name'        => esc_html__( 'Keep older episodes data.', 'podcast-player' ),
					'id'          => 'keep_old',
					'description' => esc_html__( 'Keep episodes data, even if deleted from the feed. If enabled, You can manually delete this data from "Toolkit > Feed Updation Tool > Delete Podcast Data".', 'podcast-player' ),
					'link'        => '',
					'type'        => 'checkbox',
					'default'     => '',
					'section'     => 'advanced',
				),
				'is_ajax'          => array(
					'name'        => esc_html__( 'Enable Ajax Website Compatibility.', 'podcast-player' ),
					'id'          => 'is_ajax',
					'description' => esc_html__( 'Select this option if your website pages are loaded asynchronously using Ajax theme.', 'podcast-player' ),
					'link'        => '',
					'type'        => 'checkbox',
					'default'     => '',
					'section'     => 'advanced',
				),
				'timezone'         => array(
					'name'        => esc_html__( 'Set timezone for episode date.', 'podcast-player' ),
					'id'          => 'timezone',
					'description' => esc_html__( 'Select timezone to be used for Podcast episode dates.', 'podcast-player' ),
					'link'        => '',
					'type'        => 'select',
					'choices'     => array(
						''      => esc_html__( 'GMT/ UTC Timezone', 'podcast-player' ),
						'local' => esc_html__( 'Website Local Timezone', 'podcast-player' ),
						'feed'  => esc_html__( 'Feed Data Timezone', 'podcast-player' ),
					),
					'default'     => '',
					'section'     => 'advanced',
				),
			)
		);
	}

	/**
	 * Add plugin specific options page.
	 *
	 * @since    1.5
	 */
	public function add_options_page() {
		$suffix = add_menu_page(
			esc_html__( 'Podcast Player', 'podcast-player' ),
			esc_html__( 'Podcast Player', 'podcast-player' ),
			'manage_options',
			'pp-options',
			array( $this, 'options_page' ),
			'data:image/svg+xml;base64,PCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjMyIiBoZWlnaHQ9IjMyIiB2aWV3Qm94PSIwIDAgMzIgMzIiPgo8cGF0aCBmaWxsPSIjZmZmIiBkPSJNMzIgMTZjMC04LjgzNy03LjE2My0xNi0xNi0xNnMtMTYgNy4xNjMtMTYgMTZjMCA2Ljg3NyA0LjMzOSAxMi43MzkgMTAuNDI4IDE1LjAwMmwtMC40MjggMC45OThoMTJsLTAuNDI4LTAuOTk4YzYuMDg5LTIuMjYzIDEwLjQyOC04LjEyNSAxMC40MjgtMTUuMDAyek0xNS4yMTIgMTkuODM4Yy0wLjcxMy0wLjMwNi0xLjIxMi0xLjAxNC0xLjIxMi0xLjgzOCAwLTEuMTA1IDAuODk1LTIgMi0yczIgMC44OTUgMiAyYzAgMC44MjUtMC40OTkgMS41MzMtMS4yMTIgMS44MzlsLTAuNzg4LTEuODM5LTAuNzg4IDEuODM4ek0xNi44MjEgMTkuOTE1YzEuODE1LTAuMzc5IDMuMTc5LTEuOTg4IDMuMTc5LTMuOTE1IDAtMi4yMDktMS43OTEtNC00LTRzLTQgMS43OTEtNCA0YzAgMS45MjggMS4zNjQgMy41MzUgMy4xOCAzLjkxM2wtMi4zMzIgNS40NDFjLTIuODUxLTEuMjIzLTQuODQ4LTQuMDU2LTQuODQ4LTcuMzU1IDAtNC40MTggMy41ODItOC4zNzUgOC04LjM3NXM4IDMuOTU3IDggOC4zNzVjMCAzLjI5OS0xLjk5NyA2LjEzMS00Ljg0OCA3LjM1NWwtMi4zMzEtNS40Mzl6TTIxLjUxNCAzMC44NjZsLTIuMzEtNS4zOWMzLjk1MS0xLjMzNiA2Ljc5Ni01LjA3MyA2Ljc5Ni05LjQ3NiAwLTUuNTIzLTQuNDc3LTEwLTEwLTEwcy0xMCA0LjQ3Ny0xMCAxMGMwIDQuNDAyIDIuODQ1IDguMTQgNi43OTYgOS40NzZsLTIuMzEgNS4zOWMtNC45ODctMi4xNC04LjQ4MS03LjA5NS04LjQ4MS0xMi44NjYgMC03LjcyOSA2LjI2Ni0xNC4zNyAxMy45OTUtMTQuMzdzMTMuOTk1IDYuNjQxIDEzLjk5NSAxNC4zN2MwIDUuNzcxLTMuNDk0IDEwLjcyNi04LjQ4MSAxMi44NjZ6Ij48L3BhdGg+Cjwvc3ZnPgo='
		);

		$submenu_pages = array(
			'pp-settings' => __( 'Settings', 'podcast-player' ),
			'pp-toolkit'  => __( 'Toolkit', 'podcast-player' ),
			'pp-help'      => __( 'Help & Support', 'podcast-player' ),
			'pp-products' => __( 'Other Products', 'podcast-player' ),
		);

		foreach ( $submenu_pages as $key => $label ) {
			add_submenu_page(
				'pp-options',
				$label,
				$label,
				'manage_options',
				$key,
				array( $this, 'options_page' )
			);
		}
	}

	/**
	 * Display podcast player options page.
	 *
	 * @since    1.0.0
	 */
	public function add_settings() {
		$fields = $this->get_setting_fields();

		foreach ( $this->sections as $key => $label ) {
			$section = "pp_{$key}_section";
			$setting = "pp_{$key}_settings";

			register_setting(
				'pp_options_group',
				'pp-common-options',
				array( $this, 'sanitize_common_options' )
			);

			add_settings_section( $section, '', '__return_empty_string', $setting );

			foreach ( $fields as $field ) {
				if ( $field['section'] === $key ) {
					$link  = $field['link'] ? sprintf( '<a href="%s" target="_blank">(?)</a>', esc_url( $field['link'] ) ) : '';
					$title = sprintf( '<span class="pp-opt-title">%1$s</span><span class="pp-opt-desc">%2$s %3$s</span>', $field['name'], $field['description'], $link );
					add_settings_field(
						$field['id'],
						$title,
						array( $this, 'display_setting' ),
						$setting,
						$section,
						array( 'params' => $field )
					);
				}
			}
		}
	}

	/**
	 * Function to display the settings on the page.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Setting field arguments.
	 */
	public function display_setting( $args ) {

		$params = $args['params'];
		$id     = $params['id'];
		$opt    = get_option( 'pp-common-options' );
		$type   = $params['type'];
		$field  = '';

		$iatt      = isset( $params['input_attrs'] ) ? $params['input_attrs'] : array();
		$inputattr = '';
		foreach ( $iatt as $att => $value ) {
			$inputattr .= esc_html( $att ) . '="' . esc_attr( $value ) . '" ';
		}

		if ( false === $opt ) {
			$val = $params['default'];
		} else {
			if ( 'number' === $type ) {
				$val = is_array( $opt ) && isset( $opt[ $id ] ) ? absint( $opt[ $id ] ) : $params['default'];
			} else {
				$val = is_array( $opt ) && isset( $opt[ $id ] ) ? sanitize_text_field( $opt[ $id ] ) : $params['default'];
			}
		}

		// Prepare markup for custom widget options.
		switch ( $type ) {
			case 'checkbox':
				$field = sprintf( '<input name="pp-common-options[%1$s]" id="%1$s" type="checkbox" value="yes" %2$s /><div class="slider"></div>', $id, checked( $val, 'yes', false ) );
				$field = sprintf( '<label class="switch">%s</label>', $field );
				break;
			case 'number':
				$field = sprintf( '<input name="pp-common-options[%1$s]" id="%1$s" type="number" value="%2$s" class="numbox" %3$s />', $id, absint( $val ), $inputattr );
				break;
			case 'select':
				$options = $params['choices'];
				$field   = '';
				foreach ( $options as $value => $label ) {
					$field .= sprintf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $value ), selected( $value, $val, false ), esc_html( $label ) );
				}
				$field = sprintf( '<select id="%1$s" name="pp-common-options[%1$s]">%2$s</select>', $id, $field );
				break;
			default:
				break;
		}

		echo $field; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Function to validate plugin options.
	 *
	 * @since    1.0.0
	 *
	 * @param array|false $input Podcast Option Value.
	 */
	public function sanitize_common_options( $input ) {
		$all_options = $this->get_setting_fields();
		$new_input   = array();
		if ( is_array( $input ) ) {
			foreach ( $all_options as $option => $args ) {
				$type = $args['type'];
				switch ( $type ) {
					case 'checkbox':
						$new_input[ $option ] = isset( $input[ $option ] ) && 'yes' === $input[ $option ] ? 'yes' : 'no';
						break;
					case 'number':
						$new_input[ $option ] = isset( $input[ $option ] ) ? absint( $input[ $option ] ) : $args['default'];
						break;
					case 'select':
						$new_input[ $option ] = isset( $input[ $option ] ) ? sanitize_text_field( $input[ $option ] ) : $args['default'];
						break;
				}
			}
		}
		return $new_input;
	}

	/**
	 * Function to add options page content.
	 *
	 * @since    1.0.0
	 */
	public function display_content() {
		global $pagenow;
		if ( 'admin.php' === $pagenow && isset( $_GET['page'] ) ) {
			switch ( $_GET['page'] ) {
				case 'pp-settings':
					$current_page = 'settings';
					break;
				case 'pp-toolkit':
					$current_page = 'toolkit';
					break;
				case 'pp-help':
					$current_page = 'help';
					break;
				case 'pp-products':
					$current_page = 'products';
					break;
				default:
					$current_page = 'home';
					break;
			}
			include_once PODCAST_PLAYER_DIR . 'backend/admin/templates/main.php';
		}
	}

	/**
	 * Render  Plus settings page.
	 *
	 * @since    1.0.0
	 */
	public function options_page() {
		do_action( 'podcast_player_options_page_content', 'pp-options' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function page_scripts() {
		$current_screen = get_current_screen();
		$load_on = array(
			'toplevel_page_pp-options',
			'podcast-player_page_pp-settings',
			'podcast-player_page_pp-toolkit',
			'podcast-player_page_pp-products',
			'podcast-player_page_pp-help',
		);
		if ( $current_screen && in_array( $current_screen->id, $load_on, true ) ) {

			/**
			 * Enqueue admin scripts.
			 */
			wp_enqueue_script(
				'ppadminoptions',
				PODCAST_PLAYER_URL . 'backend/js/admin-options.build.js',
				array( 'jquery-ui-tabs' ),
				PODCAST_PLAYER_VERSION,
				true
			);

			/**
			 * Enqueue admin stylesheet.
			 */
			wp_enqueue_style(
				'ppadminoptions',
				PODCAST_PLAYER_URL . 'backend/css/admin-options.css',
				array(),
				PODCAST_PLAYER_VERSION,
				'all'
			);

			// Theme localize scripts data.
			wp_localize_script(
				'ppadminoptions',
				'ppjsAdminOpt',
				apply_filters(
					'podcast_player_admin_options',
					array(
						'ajaxurl'  => admin_url( 'admin-ajax.php' ),
						'security' => wp_create_nonce( 'podcast-player-admin-options-ajax-nonce' ),
						'messages' => array(
							'running'        => esc_html__( 'Performing Action', 'podcast-player' ),
							'nourl'          => esc_html__( 'Please provide a valid podcast', 'podcast-player' ),
							'nosource'       => esc_html__( 'Please provide a valid source', 'podcast-player' ),
							'tlabel'         => esc_html__( 'Edit Title', 'podcast-player' ),
							'alabel'         => esc_html__( 'Edit Author', 'podcast-player' ),
							'slabel'         => esc_html__( 'Add/ Edit Season', 'podcast-player' ),
							'catlabel'       => esc_html__( 'Add/ Edit categories', 'podcast-player' ),
							'feLabel'        => esc_html__( 'Add/ Edit Custom Featured Image', 'podcast-player' ),
							'catph'          => esc_html__( 'Comma separated episode categories', 'podcast-player' ),
							'update'         => esc_html__( 'Save Changes', 'podcast-player' ),
							'add'            => esc_html__( '+ Add', 'podcast-player' ),
							'all'            => esc_html__( 'Select All', 'podcast-player' ),
							'iall'           => esc_html__( 'Unselect All', 'podcast-player' ),
							'iselection'     => esc_html__( 'Inverse Selection', 'podcast-player' ),
							'abort'          => esc_html__( 'Import Aborted', 'podcast-player' ),
							'aborting'       => esc_html__( 'Aborting Import', 'podcast-player' ),
							'nochange'       => esc_html__( 'Nothing to update', 'podcast-player' ),
							'loadmore'       => esc_html__( 'Load More Episodes', 'podcast-player' ),
							'removeFeatured' => esc_html__( 'Remove Custom Image', 'podcast-player' ),
							'uploadFeatured' => esc_html__( 'Upload Custom Image', 'podcast-player' ),
							'setimg'         => esc_html__( 'Set Image', 'podcast-player' ),
							'btn_text'       => esc_html__( 'Select', 'podcast-player' ),
							'img_text'       => esc_html__( 'Set Image', 'podcast-player' ),
						),
					)
				)
			);
		}
	}

	/**
	 * Array of questions.
	 *
	 * Array of documentation questions, links and appropriate sections.
	 *
	 * @since    1.0.0
	 */
	public function get_docs() {

		return apply_filters(
			'podcast_player_documentation',
			array(
				array(
					'q' => esc_html__( 'How to activate  Plus plugin?', 'podcast-player' ),
					'l' => 'https://www.premiumwp.com/',
					's' => 'getting_started',
				),
				array(
					'q' => esc_html__( 'How to activate  Plus plugin?', 'podcast-player' ),
					'l' => 'https://www.premiumwp.com/',
					's' => 'getting_started',
				),
				array(
					'q' => esc_html__( 'How to activate  Plus plugin?', 'podcast-player' ),
					'l' => 'https://www.premiumwp.com/',
					's' => 'getting_started',
				),
				array(
					'q' => esc_html__( 'How to activate  Plus plugin?', 'podcast-player' ),
					'l' => 'https://www.premiumwp.com/',
					's' => 'getting_started',
				),
			)
		);
	}

	/**
	 * Get properly framed A tag link to be used on documentation pages.
	 *
	 * @since 3.3.0
	 *
	 * @param str  $link URL to be used as href value.
	 * @param str  $text Link Text.
	 * @param str  $classes Link HTML classes.
	 * @param bool $echo Echo or return.
	 */
	public function mlink( $link, $text, $classes = '', $echo = true ) {
		$markup = '';
		if ( $link && $text ) {
			$text    = esc_html( $text ) . '<span class="dashicons dashicons-external"></span>';
			$classes = $classes ? 'class="' . esc_attr( $classes ) . '"' : '';
			$markup  = sprintf(
				'<a %s href="%s" rel="noopener noreferrer nofollow" target="_blank">%s</a>',
				$classes,
				esc_url( $link ),
				$text
			);
		}

		if ( $echo ) {
			echo $markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $markup;
		}
    }

    /**
     * New method to handle feed editor ajax calls.
     *
     * @since 1.0.0
     */
    public function feed_editor_new() {
		check_ajax_referer( 'podcast-player-admin-options-ajax-nonce', 'security' );

		$type = isset( $_POST['atype'] ) ? sanitize_text_field( wp_unslash( $_POST['atype'] ) ) : 'refresh';
		$fprn = isset( $_POST['feedUrl'] ) ? sanitize_text_field( wp_unslash( $_POST['feedUrl'] ) ) : '';	
        
        if ( ! $fprn ) {
			$output = array(
				'error' => esc_html__( 'Invalid feed key provided.', 'podcast-player' ),
			);
			echo wp_json_encode( $output );
			wp_die();
		}

        $store_manager = StoreManager::get_instance();
		$time = $fprn ? 'pp_feed_time_' . $fprn : false;
		$message = '';
		$error   = '';

		// Prepare markup for custom widget options.
		switch ( $type ) {
			case 'refresh':
                delete_transient( $time );
				$feed = Get_Fn::get_feed_data( $fprn );
				if ( is_wp_error( $feed ) ) {
					$error = '<p><strong>' . esc_html__( 'RSS Error:', 'podcast-player' ) . '</strong> ' . esc_html( $feed->get_error_message() ) . '</p>';
				} else {
					$message = esc_html__( 'Podcast Updated Successfully.', 'podcast-player' );
				}
				break;
			case 'reset':
				delete_transient( $time );
				$store_manager->delete_podcast( $fprn );

				// TEMPORARY as custom data will be moved from the options table.
				delete_option( 'pp_feed_data_custom_' . $fprn );

				Utility_Fn::refresh_index_new();
				$message = esc_html__( 'Podcast Deleted Successfully.', 'podcast-player' );
				break;
			default:
				$error = esc_html__( 'Unexpected user input.', 'podcast-player' );
				break;
        }

		if ( '' !== $error ) {
			echo wp_json_encode( array( 'error' => $error ) );
			wp_die();
		}

		// Ajax output to be returened.
		$output = array( 'message' => $message );
		echo wp_json_encode( $output );
		wp_die();
    } 

	/**
	 * Returns the instance of this class.
	 *
	 * @since  1.0.0
	 *
	 * @return object Instance of this class.
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}
