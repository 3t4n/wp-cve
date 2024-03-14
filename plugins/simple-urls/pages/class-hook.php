<?php
/**
 * SURLs - Hook.
 *
 * @package Pages
 */

namespace LassoLite\Pages;

use LassoLite\Admin\Constant;

use LassoLite\Classes\Amazon_Api;
use LassoLite\Classes\Enum;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Page;
use LassoLite\Classes\Setting;
use LassoLite\Classes\Shortcode;

/**
 * Hook.
 */
class Hook {
	/**
	 * Current template
	 *
	 * @var string $current_template
	 */
	public $current_template = '';

	/**
	 * Is show old menus
	 *
	 * @var bool $show_old_menus
	 */
	private $show_old_menus = false;

	/**
	 * Register hooks
	 */
	public function register_hooks() {
		add_action( 'admin_init', array( $this, 'amazon_api_pre_populated_automatically' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );
		add_action( 'admin_menu', array( $this, 'build_admin_menu' ), 2 );

		$lasso_shortcode = new Shortcode();
		add_shortcode( 'lasso', array( $lasso_shortcode, 'lasso_lite_core_shortcode' ) );
		add_filter( 'pre_do_shortcode_tag', array( $this, 'filter_pre_do_shortcode_lasso_lite' ), 10, 4 );

		add_action( 'wp_head', array( $this, 'lasso_custom_css' ) ); // ? frontend
		add_action( 'admin_head', array( $this, 'lasso_custom_css' ) ); // ? admin
		add_action( 'admin_head', array( $this, 'lasso_custom_menu' ) ); // ? admin
		add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts_frontend' ) ); // ? frontend

		add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_styles' ) );

		// ? re-order submenu pages
		add_filter( 'custom_menu_order', array( $this, 'lasso_order_submenu' ) );

		// ? lasso gutenberg block
		add_action( 'enqueue_block_editor_assets', array( $this, 'lasso_lite_gutenberg_block' ) );

		// ? add Lasso button into TinyMCE
		add_filter( 'mce_external_plugins', array( $this, 'lasso_add_tinymce_plugin' ) );
		add_filter( 'mce_buttons', array( $this, 'lasso_lite_register_my_tc_button' ) );

		add_filter( 'simple_urls_redirect_url', array( $this, 'lasso_lite_redirect' ), 10, 1 );

		add_action( 'admin_footer', array( $this, 'lasso_lite_admin_footer_editor_gutenberg' ) );

		add_filter( 'rest_pre_echo_response', array( $this, 'update_post_type_gutenberg' ), 200, 3 );

		add_filter( 'wp_link_query', array( $this, 'update_post_type_classic_editor' ), 10, 1 );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer' ), 100, 2 );
		add_filter( 'update_footer', '__return_empty_string', 11 );

		$setting = new Setting();
		if ( $setting->is_surls_page() ) {
			// ? plugin: Pretty Links Pro
			if ( class_exists( 'PrliUpdateController' ) ) {
				Helper::remove_action( 'admin_notices', array( 'PrliUpdateController', 'activation_warning' ) );
			}
		}

		// ? FIX: CONFLICT WITH OTHER PLUGINS
		// ? remove js files from other plugins in Lasso pages
		if ( $setting->is_surls_page() ) {
			// ? plugin: SEO Booster
			if ( class_exists( 'Seobooster2' ) ) {
				remove_action( 'admin_print_footer_scripts', array( 'Seobooster2', 'admin_print_footer_scripts' ) );
			}

			// ? plugin: ShortPixel Adaptive Images
			if ( class_exists( 'ShortPixel\AI\Notice' ) && class_exists( 'ShortPixelAI' ) ) {
				$spai  = \ShortPixelAI::_();
				$spain = \ShortPixel\AI\Notice::_( $spai );
				remove_action( 'admin_footer', array( $spain, 'enqueueAdminScripts' ) );
				remove_action( 'admin_bar_menu', array( $spai, 'toolbar_styles' ), 999 );
			}

			// ? plugin: WZone - WooCommerce Amazon Affiliates (WooZone)
			if ( class_exists( 'WooZone' ) ) {
				global $WooZone; // phpcs:ignore
				remove_action( 'init', array( $WooZone, 'initThePlugin' ), 5 ); // phpcs:ignore
			}

			// ? plugin: Client Portal
			$lasso_page = Helper::GET()['page'] ?? ''; // phpcs:ignore
			if ( class_exists( 'CCGClientPortal' ) && Helper::add_prefix_page( Enum::PAGE_DASHBOARD ) === $lasso_page ) {
				global $zohopwp; // phpcs:ignore
				remove_action( 'admin_menu', array( $zohopwp, 'ccgclientportal_admin_menu' ) ); // phpcs:ignore
			}

			if ( class_exists( 'wpe_admin_pointers' ) ) {
				global $wpe_admin_pointers; // phpcs:ignore
				remove_action( 'admin_enqueue_scripts', array( $wpe_admin_pointers, 'custom_admin_pointers_header' ) ); // phpcs:ignore
			}

			// ? plugin: tagDiv Composer
			if ( function_exists( 'td_change_backbone_js_hook' ) ) {
				remove_action( 'print_media_templates', 'td_change_backbone_js_hook' );
			}

			// ? plugin: Pretty Links Pro
			if ( class_exists( 'PrliUpdateController' ) ) {
				Helper::remove_action( 'admin_notices', array( 'PrliUpdateController', 'activation_warning' ) );
			}

			if ( Helper::is_earnist_plugin_loaded() ) {
				Helper::remove_action( 'admin_print_footer_scripts', array( 'EarnistProductPicker', 'register_tinymce_quicktags' ) );
			}
			if ( Helper::is_shortcode_start_rating_plugin_loaded() ) {
				global $ShortcodeStarRating; // phpcs:ignore
				remove_action( 'admin_print_footer_scripts', array( $ShortcodeStarRating, 'appthemes_add_quicktags' ) ); // phpcs:ignore
			}
			if ( Helper::is_plugin_easy_table_of_contents_activated() ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'remove_easy_table_of_content_action_admin_print_footer_scripts' ), 20 ); // ? admin
			}

			remove_all_actions( 'admin_footer' );
			add_action( 'admin_footer', array( $this, 'lasso_print_media_templates' ) );
			add_action( 'admin_footer', array( $this, 'lasso_organize_menu' ), 100 );
		}

		// ? fix conflict with plugin Affiliate URL Automation
		if ( class_exists( 'AffiliateURLs' ) ) {
			global $AffiliateURLs; // phpcs:ignore
			remove_filter( 'the_content', array( &$AffiliateURLs, 'the_content' ), 12 ); // phpcs:ignore
		}

		// ? remove js files from other plugins in WP post/page pages
		if ( Helper::is_wordpress_post() ) {
			if ( class_exists( 'WooZone' ) ) {
				global $WooZone; // phpcs:ignore
				remove_action( 'init', array( $WooZone, 'initThePlugin' ), 5 ); // phpcs:ignore
			}
		}

		if ( Helper::is_gravity_perks_plugin_active() ) {
			remove_action( 'admin_print_footer_scripts', array( 'GWPerks', 'welcome_pointer_script' ), 10 ); // phpcs:ignore
		}

		/*add_action( 'admin_notices', array( $this, 'lasso_lite_custom_dashboard_banner' ) );*/
	}


	/**
	 * Check if Lasso Lite shortcode then render, else return false to running the next shortcode processes
	 *
	 * @param boolean $false This is the default value that will be returned if the shortcode is not found.
	 * @param string  $tag The shortcode tag.
	 * @param array   $attr The attributes passed to the shortcode like so: [shortcode attr1="value" /].
	 * @param array   $m The regex for the shortcode.
	 *
	 * @return string|boolean return value is the output of the lasso_lite_core_shortcode function.
	 */
	public function filter_pre_do_shortcode_lasso_lite( $false, $tag, $attr, $m ) {
		if ( 'lasso' === $tag ) {
			return ( new Shortcode() )->lasso_lite_core_shortcode( $attr );
		}

		return false;
	}

	/**
	 * Admin menu
	 */
	public function build_admin_menu() {
		$get_page  = Helper::GET()['page'] ?? '';
		$post_type = Helper::GET()['post_type'] ?? '';

		$dashboard_slug = Helper::add_prefix_page( Enum::PAGE_DASHBOARD );
		// ? Switch to new/old UI: We should easy redirect to the suitable dashboard.
		if ( 'switch-new-ui' === $get_page ) {
			update_option( Enum::SWITCH_TO_NEW_UI, 1 );
			wp_redirect( Page::get_page_url( $dashboard_slug ) ); // phpcs:ignore
			exit;
		} elseif ( 'switch-old-ui' === $get_page ) {
			update_option( Enum::LASSO_LITE_ACTIVE, 0 ); // ? fix conflict with L.235
			update_option( Enum::SWITCH_TO_NEW_UI, 0 );
			wp_redirect( Page::get_page_url() ); // phpcs:ignore
			exit;
		}

		// ? Reset onboarding for testing
		$reset_onboarding = Helper::GET()[ Enum::RESET_WELCOME_PAGE ] ?? '';
		if ( $reset_onboarding ) {
			Helper::update_option( Enum::IS_VISITED_WELCOME_PAGE, 0 );
			update_option( Enum::LASSO_LITE_ACTIVE, 1 );
		}

		// ? Reset request review for testing
		$reset_review = Helper::GET()[ Enum::RESET_REQUEST_REVIEW ] ?? '';
		if ( $reset_review ) {
			Helper::update_option( Constant::LASSO_OPTION_REVIEW_ALLOW, 1 );
			Helper::update_option( Constant::LASSO_OPTION_REVIEW_SNOOZE, 0 );
			Helper::update_option( Constant::LASSO_OPTION_REVIEW_LINK_COUNT, 0 );
		}

		// ? The new Lasso Lite active should be redirect to welcome page
		$onboarding_page                 = Helper::add_prefix_page( Enum::PAGE_ONBOARDING );
		$should_redirect_to_welcome_page = get_option( Enum::LASSO_LITE_ACTIVE ) && ! Helper::get_option( Enum::IS_VISITED_WELCOME_PAGE );
		if ( SIMPLE_URLS_SLUG === $post_type && $onboarding_page !== $get_page && $should_redirect_to_welcome_page ) {
			wp_redirect( Page::get_page_url( $onboarding_page ) ); // phpcs:ignore
			exit;
		} elseif ( $onboarding_page === $get_page && ! $should_redirect_to_welcome_page ) {
			wp_redirect( Page::get_page_url( $dashboard_slug ) ); // phpcs:ignore
			exit;
		}

		// ? Redirect to dashboard page if page is "surl-dashboard" and missing "post_type" parameter
		if ( $dashboard_slug === $get_page && ! $post_type ) {
			wp_redirect( Page::get_page_url( $dashboard_slug ) ); // phpcs:ignore
			exit;
		}

		// ? If the customer install from new UI, we should only show the new UI's menus without switch UI feature
		if ( get_option( Enum::LASSO_LITE_ACTIVE ) ) {
			update_option( Enum::SWITCH_TO_NEW_UI, 1 );
			$this->apply_new_ui_menus();
		} else { // ? The old client that upgrade to the new UI
			$switch_ui  = 'switch-old-ui';
			$page_title = 'Switch to old UI';

			// ? The client was switched to new UI by click "Switch To New UI"
			if ( Helper::is_lite_using_new_ui() ) {
				$this->apply_new_ui_menus();
			} else { // ? The client was still using the old UI
				$this->show_old_menus = true;
				$switch_ui            = 'switch-new-ui';
				$page_title           = 'Switch to new UI';
			}

			add_submenu_page(
				'edit.php?post_type=' . SIMPLE_URLS_SLUG,
				$page_title,
				$page_title,
				'manage_options',
				Page::get_page_url( $switch_ui )
			);
		}
	}

	/**
	 * Apply new UI's menus
	 */
	private function apply_new_ui_menus() {
		$get_page  = Helper::GET()['page'] ?? '';
		$post_type = Helper::GET()['post_type'] ?? '';

		// ? Redirect to new UI's Dashboard if new UI enabled and the link is old UI
		if ( SIMPLE_URLS_SLUG === $post_type && ! $get_page ) {
			wp_redirect( Page::get_lite_page_url( Enum::PAGE_DASHBOARD ) ); // phpcs:ignore
			exit;
		}

		$new_ui_pages = Helper::available_pages();
		$parent_slug  = 'edit.php?post_type=' . SIMPLE_URLS_SLUG;
		foreach ( $new_ui_pages as $page ) {
			if ( $get_page === $page->slug ) {
				$this->current_template = $page->template;
			}

			add_submenu_page(
				$parent_slug,
				$page->title,
				$page->title,
				'manage_options',
				$page->slug,
				array( $this, 'render_html' )
			);
		}
	}


	/**
	 * Render html for pages
	 */
	public function render_html() {
		Helper::include_with_variables( Helper::get_path_views_folder() . $this->current_template, array(), false );
	}

	/**
	 * Load css files
	 */
	public function add_styles() {
		if ( ! Helper::is_lite_using_new_ui() ) {
			return;
		}

		$setting = new Setting();
		// @codingStandardsIgnoreStart

		// ? Everywhere in the Lasso Lite post-type (add/edit/reports/settings/wizard)
		if ( $setting->is_surls_page() ) {
			Helper::enqueue_style( 'bootstrap-css', 'bootstrap.min.css' );
			Helper::enqueue_style( 'bootstrap-select-css', 'bootstrap-select.min.css' );
			Helper::enqueue_style( 'simple-panigation-css', 'simplePagination.css' );
			Helper::enqueue_style( 'select2-css', 'select2.min.css' );
			Helper::enqueue_style( 'lasso-quill', 'quill.snow.css' );
			Helper::enqueue_style( 'lasso-lite', 'lasso-lite.css' );
			Helper::enqueue_style( 'lasso-display-modal', 'lasso-display-modal.css' );
			Helper::enqueue_style( 'lasso-dashboard', 'lasso-dashboard.css' );
			Helper::enqueue_style( 'lasso-lite-custom', 'lite-custom.css' );
		}

		if ( $setting->is_setting_display_page() || $setting->is_setting_onboarding_page() ) {
			Helper::enqueue_style( 'spectrum', 'spectrum.min.css' );
		}

		// ? LOAD LASSO DISPLAY MODAL CSS ON POST AND PAGE EDIT ONLY
		if ( $setting->is_wordpress_post() || $setting->is_custom_post() ) {
			Helper::enqueue_style( 'bootstrap-grid-css', 'bootstrap-grid.min.css' );
			Helper::enqueue_style( 'lasso-display-modal', 'lasso-display-modal.css' );
			Helper::enqueue_style( 'simple-pagination', 'simplePagination.css' );
			Helper::enqueue_style( 'lasso-quill', 'quill.snow.css' );
			Helper::enqueue_style( 'lasso-lite', 'lasso-lite.css' );
		}

		// @codingStandardsIgnoreEnd
	}

	/**
	 * Load js files
	 */
	public function add_scripts() {
		if ( ! Helper::is_lite_using_new_ui() ) {
			return;
		}

		$get               = Helper::GET(); // phpcs:ignore
		$page              = $get['page'] ?? false;
		$setting           = new Setting();
		$post_type         = $setting->get['post_type'] ?? false;
		$setting_data      = Setting::get_settings();
		$support_enabled   = $setting_data[ Enum::SUPPORT_ENABLED ] ?? false;
		$support_enabled   = ! $support_enabled ? 1 : 0;
		$data_passed_to_js = array(
			'ajax_url'                  => admin_url( 'admin-ajax.php' ),
			'site_url'                  => site_url(),
			'plugin_url'                => SIMPLE_URLS_URL,
			'rewrite_slug_default'      => Enum::REWRITE_SLUG_DEFAULT,
			'simple_urls_slug'          => SIMPLE_URLS_SLUG,
			'page_url_details'          => Enum::PAGE_URL_DETAILS,
			'setup_progress'            => Helper::get_setup_progress_information(),
			'optionsNonce'              => wp_create_nonce( Constant::LASSO_LITE_NONCE . wp_salt() ),
			'should_open_support_modal' => $support_enabled,
			'amazon_tracking_id_regex'  => Amazon_Api::TRACKING_ID_REGEX,
			'is_onboard_page'           => $setting->is_setting_onboarding_page(),
			'block_customize'           => Constant::BLOCK_CUSTOMIZE,
		);

		if ( SIMPLE_URLS_SLUG === $post_type ) {
			wp_dequeue_script( 'up_admin_script' ); // ? fix js conflict with plugin: Download plugin
		}

		// @codingStandardsIgnoreStart
		if ( $setting->is_wordpress_post() || $setting->is_surls_page() || $setting->is_custom_post() ) {
			wp_enqueue_script( 'jquery-migrate' ); // ? fix jQuery(...).live is not a function
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-effects-core' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-tooltip' );

			Helper::enqueue_script( 'lasso-icons', 'fontawesome.min.js', array( 'jquery' ) );
			Helper::enqueue_script( 'lasso-icons-regular', 'regular.min.js', array( 'jquery' ) );
			Helper::enqueue_script( 'lasso-icons-solid', 'solid.min.js', array( 'jquery' ) );
			Helper::enqueue_script( 'lasso-icons-brands', 'brands.min.js', array( 'jquery' ) );
			Helper::enqueue_script( 'circle-progress', 'circle-progress.min.js', array( 'jquery' ) );

			Helper::enqueue_script( 'lasso-quill', 'quill.min.js' );
			wp_enqueue_media();
			Helper::enqueue_script( 'moment-js', 'moment.min.js', array( 'jquery' ) );
			Helper::enqueue_script( 'select2-js', 'select2.full.min.js', array( 'jquery' ) );
			Helper::enqueue_script( SIMPLE_URLS_SLUG . '-jq-auto-complete-js', 'jquery-autocomplete.js' );

			Helper::enqueue_script( 'popper-js', 'popper.min.js', array( 'jquery' ) );
			Helper::enqueue_script( 'bootstrap-js', 'bootstrap.min.js', array( 'jquery' ) );
			Helper::enqueue_script( 'bootstrap-select-js', 'bootstrap-select.min.js', array( 'jquery' ) );
			Helper::enqueue_script( 'pagination-js', 'jquery.simplePagination.js', array( 'jquery' ) );
			Helper::enqueue_script( 'jsrender', 'jsrender.min.js' );

			wp_localize_script( 'jquery', 'lassoLiteOptionsData', $data_passed_to_js );
			Helper::enqueue_script( 'lasso-helper', 'lasso-helper.js', array( 'jquery' ) );
			Helper::enqueue_script( 'url-add', 'url-add.js', array( 'jquery' ) );
			Helper::enqueue_script( 'support', 'support.js', array( 'jquery' ) );

			if ( SIMPLE_URLS_SLUG . '-' . Enum::PAGE_URL_DETAILS === $page ) {
				Helper::enqueue_script( 'url-details', 'url-details.js', array( 'jquery' ) );
			}
		}

		if ( $setting->is_dashboard_page() ) {
			Helper::enqueue_script( 'dashboard', 'dashboard.js', array( 'jquery' ) );
		}

		if ( $setting->is_setting_display_page() || $setting->is_setting_onboarding_page() ) {
			Helper::enqueue_script( 'spectrum-js', 'spectrum.min.js', array( 'jquery' ) );
			Helper::enqueue_script( 'settings-display-js', 'settings-display.js', array( 'jquery' ) );
		}

		if ( $setting->is_setting_amazon_page() || $setting->is_setting_onboarding_page() ) {
			Helper::enqueue_script( 'settings-amazon', 'settings-amazon.js', array( 'jquery' ) );
		}

		if ( $setting->is_import_page() || $setting->is_setting_onboarding_page() ) {
			Helper::enqueue_script( 'settings-import', 'settings-import.js', array( 'jquery' ) );
		}

		if ( $setting->is_setting_onboarding_page() ) {
			Helper::enqueue_script( 'onboarding-js', 'onboarding.js', array( 'jquery' ) );
			Helper::enqueue_script( 'settings-js', 'settings.js', array( 'jquery' ) );
			Helper::enqueue_script( 'settings-display-js', 'settings-display.js', array( 'jquery' ) );
		}

		if ( $setting->is_setting_general_page() ) {
			Helper::enqueue_script( 'settings-general', 'settings-general.js', array( 'jquery' ) );
		}

		if ( $setting->is_group_detail_page() || $setting->is_group_page() ) {
			Helper::enqueue_script( 'groups', 'groups.js', array( 'jquery' ) );
		}

		// @codingStandardsIgnoreEnd
	}

	/**
	 * DISPLAYS CSS FOR FRONTEND OF SITE
	 */
	public function add_scripts_frontend() {
		Helper::enqueue_style( 'lasso-lite', 'lasso-lite.css' );
	}

	/**
	 * DISPLAYS CUSTOM CSS FOR FRONTEND OF SITE
	 */
	public function lasso_custom_css() {
		if ( ! Helper::is_lite_using_new_ui() ) {
			return;
		}

		$settings = Setting::get_settings();

		// @codingStandardsIgnoreStart
		echo '<style type="text/css">
			:root{
				--lasso-main: ' . $settings['display_color_main'] . ' !important;
				--lasso-title: ' . $settings['display_color_title'] . ' !important;
				--lasso-button: ' . $settings['display_color_button'] . ' !important;
				--lasso-secondary-button: ' . $settings['display_color_secondary_button'] . ' !important;
				--lasso-button-text: ' . $settings['display_color_button_text'] . ' !important;
				--lasso-background: ' . $settings['display_color_background'] . ' !important;
				--lasso-pros: ' . $settings['display_color_pros'] . ' !important;
				--lasso-cons: ' . $settings['display_color_cons'] . ' !important;
			}
		</style>';

		// fix fontawesome js render svg (from other plugins) instead of using css
		echo '
			<script type="text/javascript">
				// Notice how this gets configured before we load Font Awesome
				window.FontAwesomeConfig = { autoReplaceSvg: false }
			</script>
		';
		// @codingStandardsIgnoreEnd
	}

	/**
	 * Order menu and submenu of Lasso
	 *
	 * @param boolean $custom_menu Whether custom ordering is enabled. Default false.
	 */
	public function lasso_order_submenu( $custom_menu ) {
		global $submenu;

		$new_submenu        = array();
		$menu_key           = 'edit.php?post_type=' . SIMPLE_URLS_SLUG;
		$lasso_lite_submenu = @$submenu[ $menu_key ]; // phpcs:ignore

		$parent_slug    = 'edit.php?post_type=' . SIMPLE_URLS_SLUG;
		$hide_menu      = array(
			SIMPLE_URLS_SLUG . '-' . Enum::PAGE_SETTINGS_GENERAL,
			SIMPLE_URLS_SLUG . '-' . Enum::PAGE_SETTINGS_AMAZON,
			SIMPLE_URLS_SLUG . '-' . Enum::PAGE_URL_DETAILS,
			SIMPLE_URLS_SLUG . '-' . Enum::PAGE_OPPORTUNITIES,
			SIMPLE_URLS_SLUG . '-' . Enum::PAGE_TABLES,
			SIMPLE_URLS_SLUG . '-' . Enum::PAGE_GROUPS,
			SIMPLE_URLS_SLUG . '-' . Enum::PAGE_GROUP_DETAIL,
		);
		$surl_dashboard = array(
			SIMPLE_URLS_SLUG . '-' . Enum::PAGE_DASHBOARD,
			'post-new.php?post_type=' . SIMPLE_URLS_SLUG,
		);

		if ( ! empty( $lasso_lite_submenu ) ) {
			foreach ( $lasso_lite_submenu as $subpage ) {
				// ? Show the new menus
				if ( ! $this->show_old_menus && in_array( $subpage[2], $surl_dashboard, true ) ) { // ? Hide "Add new" and SURL Dashboard
					continue;
				} elseif ( ! $this->show_old_menus && $parent_slug === $subpage[2] ) { // ? Change title of root dashboard(all posts) menu
					$subpage[0]    = 'Dashboard';
					$new_submenu[] = $subpage;
				} elseif ( ! in_array( $subpage[2], $hide_menu, true ) ) {
					if ( SIMPLE_URLS_SLUG . '-' . Enum::PAGE_SETTINGS_DISPLAY === $subpage[2] ) {
						$subpage[0] = 'Settings';
					}

					$new_submenu[] = $subpage;
				}
			}

			$new_submenu[90] = array(
				'Support',
				'manage_options',
				Constant::LASSO_SUPPORT_URL,
			);

			$new_submenu[100] = array(
				'<b class="green">Upgrade to Pro</b>',
				'manage_options',
				Constant::LASSO_UPGRADE_URL,
			);
		}

		// ? ERROR | Overriding WordPress globals is prohibited. Found assignment to $submenu
		// phpcs:ignore
		$submenu[ $menu_key ] = $new_submenu;
	}

	/**
	 * Load lasso js file in Gutenberg editor
	 */
	public function lasso_lite_gutenberg_block() {
		if ( ! Helper::is_lasso_pro_installed() ) {
			Helper::enqueue_script( 'lasso-lite-gutenberg-block', 'lasso-lite-gutenberg-block.js', array( 'wp-blocks', 'wp-editor' ), true );
			Helper::enqueue_script( 'display-add', 'display-add.js', array( 'jquery' ) );
			Helper::enqueue_script( 'url-add', 'url-add.js', array( 'jquery' ) );
		}
	}

	/**
	 * Load lasso js file in Classic editor (TinyMCE)
	 *
	 * @param array $plugin_array An array of external TinyMCE plugins.
	 */
	public function lasso_add_tinymce_plugin( $plugin_array ) {
		if ( ! Helper::is_lasso_pro_plugin_active() ) {
			$lasso_setting = new Setting();
			if ( Helper::is_classic_editor() && ( $lasso_setting->is_wordpress_post() || $lasso_setting->is_custom_post() ) ) {
				$plugin_array['lasso_lite_tc_button'] = SIMPLE_URLS_URL . '/admin/assets/js/lasso-lite-display-modal.js?v=' . strval( @filemtime( SIMPLE_URLS_DIR . '/admin/assets/js/lasso-display-modal.js' ) ); // phpcs:ignore
				Helper::enqueue_script( 'display-add', 'display-add.js', array( 'jquery' ) );
				Helper::enqueue_script( 'url-add', 'url-add.js', array( 'jquery' ) );
			}
		}

		return $plugin_array;
	}

	/**
	 * Add Lasso button to Classic editor (TinyMCE)
	 *
	 * @param array $buttons First-row list of buttons.
	 */
	public function lasso_lite_register_my_tc_button( $buttons ) {
		if ( ! Helper::is_lasso_pro_plugin_active() ) {
			array_push( $buttons, 'lasso_lite_tc_button' );
			array_push( $buttons, 'lasso_grid_button' );
		}

		return $buttons;
	}

	/**
	 * Render template in admin footer on screen editor Gutenberg
	 */
	public function lasso_lite_admin_footer_editor_gutenberg() {
		if ( ! Helper::is_lasso_pro_plugin_active() ) {
			$lasso_setting = new Setting();

			if ( $lasso_setting->is_wordpress_post() || $lasso_setting->is_custom_post() ) {
				$current_screen = get_current_screen();
				// ? Check to make sure render html only block editor Gutenberg
				if ( ( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() )
					|| ( function_exists( 'is_gutenberg_page' ) ) && is_gutenberg_page()
				) {
					echo Helper::get_display_modal_html(); // phpcs:ignore
				}
			}
		}
	}

	/**
	 * Custom target menu
	 */
	public function lasso_custom_menu() {
		?>
		<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				jQuery( "ul#adminmenu a[href$='<?php echo Constant::LASSO_SUPPORT_URL; // phpcs:ignore ?>']" ).attr( 'target', '_blank' );
				jQuery( "ul#adminmenu a[href$='<?php echo Constant::LASSO_UPGRADE_URL; // phpcs:ignore ?>']" ).attr( 'target', '_blank' );
			} );
		</script>
		<?php
	}

	/**
	 * Add Amazon tracking id to redirect link
	 *
	 * @param string $redirect Redirect link.
	 */
	public function lasso_lite_redirect( $redirect ) {
		if ( Amazon_Api::is_amazon_url( $redirect ) ) {
			$redirect = Amazon_Api::get_amazon_product_url( $redirect );
		}

		return $redirect;
	}

	/**
	 * Pull Amazon API information is stored in AAWP, or Amalinks Pro automatically.
	 *
	 * @return $this
	 */
	public function amazon_api_pre_populated_automatically() {
		if ( Helper::get_option( Enum::IS_PRE_POPULATED_AMAZON_API ) ) {
			return $this;
		}

		$api_key     = '';
		$api_secret  = '';
		$country     = '';
		$tracking_id = '';

		// ? AAWP plugin
		if ( Helper::is_aawp_active() ) {
			$aawp_api = get_option( 'aawp_api' );

			$api_key     = $aawp_api['key'] ?? '';
			$api_secret  = $aawp_api['secret'] ?? '';
			$country     = $aawp_api['country'] ?? '';
			$tracking_id = $aawp_api['associate_tag'] ?? '';
		}

		// ? AmaLinksPro plugin
		if ( empty( $api_key ) && empty( $api_secret ) && empty( $tracking_id ) && Helper::is_amalinks_pro_active() ) {
			$api_key    = get_option( 'amalinkspro-options_amazon_api_access_key', '' );
			$api_secret = get_option( 'amalinkspro-options_amazon_api_secret_key', '' );
			$country    = get_option( 'amalinkspro-options_default_amazon_search_locale', '' );
			if ( ! empty( $country ) ) {
				$tracking_id = get_option( 'amalinkspro-options_' . $country . '_amazon_associate_ids_0_associate_id', '' );
			}
		}

		$lasso_options        = Setting::get_settings();
		$amazon_access_key_id = $lasso_options['amazon_access_key_id'] ?? '';
		$amazon_secret_key    = $lasso_options['amazon_secret_key'] ?? '';
		$amazon_tracking_id   = $lasso_options['amazon_tracking_id'] ?? '';

		if ( empty( $amazon_access_key_id ) && empty( $amazon_secret_key ) && empty( $amazon_tracking_id )
			&& ! empty( $api_key ) && ! empty( $api_secret ) && ! empty( $tracking_id ) && ! empty( $country )
		) {
			$country = strtolower( $country );

			Setting::set_setting( 'amazon_access_key_id', $api_key );
			Setting::set_setting( 'amazon_secret_key', $api_secret );
			Setting::set_setting( 'amazon_default_tracking_country', $country );
			Setting::set_setting( 'amazon_tracking_id', $tracking_id );

			Helper::update_option( Enum::IS_PRE_POPULATED_AMAZON_API, 1 );
		}

		return $this;
	}

	/**
	 * Remove action "admin_print_footer_scripts" of "Easy Table of Contents" plugin
	 */
	public function remove_easy_table_of_content_action_admin_print_footer_scripts() {
		Helper::remove_action( 'admin_print_footer_scripts', array( 'eztoc_pointers', 'admin_print_footer_scripts' ) );
	}

	/**
	 * Prints the templates used in the media manager.
	 */
	public function lasso_print_media_templates() {
		if ( file_exists( ABSPATH . WPINC . '/media-template.php' ) && ! function_exists( 'wp_print_media_templates' ) ) {
			require_once ABSPATH . WPINC . '/media-template.php';
		}
		wp_print_media_templates();
	}

	/**
	 * Add active class to the current page
	 */
	public function lasso_organize_menu() {
		$setting_page = ( new Setting() )->is_setting_page();
		?>
		<script>
			jQuery(document).ready(function(){
				var lasso_menu = jQuery('#menu-posts-surl');
				if(lasso_menu.is('.wp-has-current-submenu, .wp-menu-open')){
					var submenu = lasso_menu.find('ul.wp-submenu').find('li');
					if(submenu != undefined) {
						if(!submenu.hasClass('current') || submenu.hasClass('current').length == 0) {
							submenu.eq(1).addClass('current');
						}

						// add class `current` for Settings menu
						if('<?php echo (int) $setting_page; ?>' == '1') {
							submenu.removeClass('current');
							lasso_menu.find('ul.wp-submenu').find('li:contains("Settings")').addClass('current');
						}
					}
				}
			});
		</script>
		<?php
	}

	/**
	 * Register a category
	 */
	public function register_taxonomy() {
		// ? register custom taxonomy
		register_taxonomy(
			Constant::LASSO_CATEGORY,
			Constant::LASSO_POST_TYPE,
			array(
				'label'        => __( 'Lasso Categories' ),
				'rewrite'      => array( 'slug' => Constant::LASSO_CATEGORY ),
				'hierarchical' => false,
				'public'       => false,
				'labels'       => array(
					'add_new_item' => __( 'Add New Category' ),
					'edit_item'    => __( 'Edit Categories' ),
				),
			)
		);
	}

	/**
	 * Use Amazon product url instead Lasso url when search/insert a link into a post/page
	 *
	 * @param array  $response Response data to send to the client.
	 * @param object $server (WP_REST_Server) Server instance.
	 * @param object $request (WP_REST_Request) Request used to generate the response.
	 */
	public function update_post_type_gutenberg( $response, $server, $request ) {
		$params = $request->get_params();
		$type   = $params['type'] ?? '';
		if ( 'post' === $type && is_array( $response ) && '/wp/v2/search' === $request->get_route() && count( $response ) > 0 ) {
			foreach ( $response as $key => $post ) {
				if ( 'post' === $post['type'] && Constant::LASSO_POST_TYPE === $post['subtype'] ) {
					$response[ $key ]['subtype'] = Constant::LASSO_PRO_POST_TYPE; // ? update post type to Pro
				}
			}
		}

		return $response;
	}

	/**
	 * Custom post type to Lasso Branding
	 *
	 * @param array $results Search results.
	 *
	 * @return mixed
	 */
	public function update_post_type_classic_editor( $results ) {
		if ( ! empty( $results ) ) {
			foreach ( $results as &$result ) {
				$type = get_post_type( $result['ID'] );
				if ( Constant::LASSO_POST_TYPE === $type ) {
					$result['info'] = Constant::LASSO_BRAND;
				}
			}
		}
		return $results;
	}

	/**
	 * Update the text in the footer
	 *
	 * @param string $text Text.
	 */
	public function admin_footer( $text ) {
		global $current_screen;

		$setting = new Setting();

		if ( ! empty( $current_screen->id ) && $setting->is_surls_page() ) {
			$url  = Enum::LASSO_REVIEW_URL;
			$text = sprintf(
				wp_kses(
					'Enjoying %1$s? Please rate <a href="%2$s" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%3$s" target="_blank" rel="noopener">WordPress.org</a> to help us spread the word. Thanks from the Lasso team!',
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
							'rel'    => array(),
						),
					)
				),
				'<strong>Lasso Lite</strong>',
				$url,
				$url
			);
		}

		return $text;
	}

	/**
	 * Show Performance promotion
	 *
	 * @return void
	 */
	/*public function lasso_lite_custom_dashboard_banner() {
		global $pagenow;
		$html = '';
		if ( 'index.php' === $pagenow ) {
			Helper::enqueue_style( 'lasso-lite-admin', 'lasso-lite-admin.css' );
			Helper::enqueue_script( 'lasso-lite-admin-js', 'lasso-lite-admin.js', array( 'jquery' ) );

			$dismiss = intval( Helper::get_option( Constant::LASSO_OPTION_DISMISS_PERFORMANCE_NOTICE, 0 ) );
			$html    = Helper::include_with_variables(
				SIMPLE_URLS_DIR . '/admin/views/notifications/performance.php',
				array(
					'dismiss' => $dismiss,
				)
			);
		}
		echo $html; // phpcs:ignore
	}*/

}
