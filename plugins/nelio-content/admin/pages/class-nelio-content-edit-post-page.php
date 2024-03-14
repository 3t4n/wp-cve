<?php
/**
 * This file customizes the post edit screen.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/pages
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class that registers required UI elements to customize post edit screen.
 */
class Nelio_Content_Edit_Post_Page {

	public function init() {

		add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ), 5 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'register_assets' ), 5 );

		add_action( 'admin_enqueue_scripts', array( $this, 'maybe_enqueue_classic_editor_assets' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'maybe_enqueue_gutenberg_assets' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'maybe_add_mce_translations' ) );
		add_filter( 'mce_external_plugins', array( $this, 'maybe_add_mce_plugin' ) );
		add_filter( 'mce_buttons', array( $this, 'add_mce_buttons' ) );
		add_filter( 'tiny_mce_before_init', array( $this, 'add_mce_tags' ) );

	}//end init()

	public function register_assets() {

		wp_register_style(
			'nelio-content-edit-post',
			nelio_content()->plugin_url . '/assets/dist/css/edit-post.css',
			array( 'nelio-content-components' ),
			nc_get_script_version( 'edit-post' )
		);

		nc_register_script_with_auto_deps( 'nelio-content-edit-post', 'edit-post', true );
		nc_register_script_with_auto_deps( 'nelio-content-gutenberg-editor', 'gutenberg-editor', true );
		nc_register_script_with_auto_deps( 'nelio-content-classic-editor', 'classic-editor', true );

	}//end register_assets()

	public function maybe_enqueue_gutenberg_assets() {

		if ( ! $this->is_calendar_post_type() ) {
			return;
		}//end if

		$this->enqueue_edit_post_style();

		wp_enqueue_script( 'nelio-content-gutenberg-editor' );
		wp_add_inline_script(
			'nelio-content-gutenberg-editor',
			sprintf(
				'NelioContent.initPage( %s );',
				wp_json_encode( $this->get_init_args() )
			)
		);

	}//end maybe_enqueue_gutenberg_assets()

	public function maybe_enqueue_classic_editor_assets() {

		if ( ! $this->is_classic_editor() ) {
			return;
		}//end if

		if ( ! $this->is_calendar_post_type() ) {
			return;
		}//end if

		$this->enqueue_edit_post_style();

		wp_enqueue_script( 'nelio-content-classic-editor' );
		wp_add_inline_script(
			'nelio-content-classic-editor',
			sprintf(
				'NelioContent.initPage( %s );',
				wp_json_encode( $this->get_init_args() )
			)
		);

	}//end maybe_enqueue_classic_editor_assets()

	public function maybe_add_mce_translations() {
		if ( ! $this->is_calendar_post_type() ) {
			return;
		}//end if

		$translations = array(
			'pluginUrl'       => __( 'https://neliosoftware.com/content/', 'nelio-content' ),
			'description'     => _x( 'Social Automations by Nelio Content', 'text', 'nelio-content' ),
			'createAction'    => _x( 'Create Social Message', 'command', 'nelio-content' ),
			'highlightAction' => _x( 'Highlight for Auto Sharing', 'command', 'nelio-content' ),
			'removeAction'    => _x( 'Remove Highlight', 'command', 'nelio-content' ),
		);

		wp_add_inline_script(
			'wp-tinymce-root',
			sprintf(
				'NelioContentTinyMCEi18n = %s;',
				wp_json_encode( $translations )
			)
		);
	}//end maybe_add_mce_translations()

	public function maybe_add_mce_plugin( $plugins ) {
		if ( ! $this->is_calendar_post_type() ) {
			return $plugins;
		}//end if

		$asset = include nelio_content()->plugin_path . '/assets/dist/js/tinymce-actions.asset.php';

		$plugins['nelio_content'] = add_query_arg(
			'version',
			$asset['version'],
			nelio_content()->plugin_url . '/assets/dist/js/tinymce-actions.js'
		);

		return $plugins;
	}//end maybe_add_mce_plugin()

	public function add_mce_buttons( $buttons ) {
		$buttons[] = 'nelio_content';
		return $buttons;
	}//end add_mce_buttons()

	public function add_mce_tags( $options ) {
		$append = function( $arr, $key, $value, $sep = ',' ) {
			if ( ! isset( $arr[ $key ] ) || empty( $arr[ $key ] ) ) {
				$arr[ $key ] = '';
			} else {
				$arr[ $key ] .= $sep;
			}//end if
			$arr[ $key ] .= $value;
			return $arr;
		};

		$options = $append( $options, 'custom_elements', '~ncshare' );
		$options = $append( $options, 'extended_valid_elements', 'ncshare[class]' );
		$options = $append( $options, 'content_style', 'ncshare { background: #ffffaa; }', ' ' );
		$options = $append( $options, 'content_style', 'ncshare.nc-has-caret { background: #ffee00; }', ' ' );
		$options = $append( $options, 'content_style', 'ncshare.nc-has-caret ncshare { background: transparent }', ' ' );

		return $options;
	}//end add_mce_tags()

	private function is_classic_editor() {
		if ( ! function_exists( 'get_current_screen' ) ) {
			return false;
		}//end if

		$screen = get_current_screen();
		if ( method_exists( $screen, 'is_block_editor' ) && $screen->is_block_editor() ) {
			return false;
		}//end if

		$settings = Nelio_Content_Settings::instance();
		return in_array( $screen->id, $settings->get( 'calendar_post_types', array() ), true );
	}//end is_classic_editor()

	private function is_calendar_post_type() {
		$settings = Nelio_Content_Settings::instance();
		return in_array( get_post_type(), $settings->get( 'calendar_post_types', array() ), true );
	}//end is_calendar_post_type()

	private function enqueue_edit_post_style() {
		wp_enqueue_style( 'nelio-content-edit-post' );

		// Gutenberg.
		wp_add_inline_style(
			'nelio-content-edit-post',
			'.rich-text ncshare { background: #ffa } .rich-text:focus ncshare[data-rich-text-format-boundary] { background: #fe0 }'
		);

		// TinyMCE.
		wp_add_inline_style(
			'nelio-content-edit-post',
			sprintf(
				'.mce-toolbar .mce-btn .mce-i-nelio-content-icon:before{background:none;background-image:url(%s);background-size:1em 1em;content:"";display:block;font-size:20px;height:1em;opacity:0.67;width:1em;}',
				nelio_content()->plugin_url . '/assets/dist/images/logo.svg'
			)
		);
	}//end enqueue_edit_post_style()

	private function get_init_args() {
		$post_id     = $this->get_current_post_id();
		$settings    = Nelio_Content_Settings::instance();
		$post_helper = Nelio_Content_Post_Helper::instance();

		return array(
			'attributes' => array(
				'externalFeatImage' => $this->get_external_featured_image( $post_id ),
				'followers'         => $post_helper->get_post_followers( $post_id ),
				'references'        => $post_helper->get_references( $post_id, 'all' ),
			),
			'postId'     => $post_id,
			'settings'   => array(
				'dynamicSections'        => array(
					'externalFeatImage' => $settings->get( 'use_external_featured_image' ),
					'notifications'     => $settings->get( 'use_notifications' ),
				),
				'nonce'                  => wp_create_nonce( "nelio_content_save_post_{$post_id}" ),
				'qualityAnalysis'        => array(
					'canImageBeAutoSet' => 'disabled' !== $settings->get( 'auto_feat_image' ),
					'isFullyIntegrated' => $this->is_quality_analysis_fully_integrated(),
					'isYoastIntegrated' => $this->is_yoast_integrated(),
					'supportsFeatImage' => current_theme_supports( 'post-thumbnails' ),
				),
				'autoShareEndModes'      => nc_get_auto_share_end_modes(),
				/** This filter is documented in includes/utils/class-nelio-content-post-saving.php */
				'shouldAuthorBeFollower' => apply_filters( 'nelio_content_notification_auto_subscribe_post_author', true ),
			),
		);
	}//end get_init_args()

	private function get_current_post_id() {
		global $post;
		return isset( $_GET['post'] ) ? absint( $_GET['post'] ) : $post->ID; // phpcs:ignore
	}//end get_current_post_id()

	private function is_quality_analysis_fully_integrated() {
		/**
		 * Returns whether the quality analysis should be fully integrated with WordPress or not,
		 * using default sidebars and metaboxes.
		 *
		 * If it isn’t, Nelio Content will only use its own areas to display QA.
		 *
		 * @param $is_visible boolean whether the quality analysis is fully integrated with WP.
		 *                            Default: `true`.
		 *
		 * @since 2.0.0
		 */
		return apply_filters( 'nelio_content_is_quality_analysis_fully_integrated', true );
	}//end is_quality_analysis_fully_integrated()

	private function is_yoast_integrated() {
		if (
			! is_plugin_active( 'wordpress-seo/wp-seo.php' ) &&
			! is_plugin_active( 'wordpress-seo-premium/wp-seo-premium.php' )
		) {
			return false;
		}//end if

		/**
		 * Whether Yoast should be integrated with Nelio Content’s quality analysis or not.
		 *
		 * @param $integrated boolean Default: true.
		 *
		 * @since 2.0.0
		 */
		return apply_filters( 'nelio_content_is_yoast_integrated_in_quality_analysis', true );
	}//end is_yoast_integrated()

	private function get_external_featured_image( $post_id ) {
		return array(
			'url' => get_post_meta( $post_id, '_nelioefi_url', true ),
			'alt' => get_post_meta( $post_id, '_nelioefi_alt', true ),
		);
	}//end get_external_featured_image()

	private function get_post_followers( $post ) {
		if ( empty( $post ) ) {
			return array();
		}//end if
		if ( empty( $post['followers'] ) ) {
			return array();
		}//end if
		return $post['followers'];
	}//end get_post_followers()

}//end class
