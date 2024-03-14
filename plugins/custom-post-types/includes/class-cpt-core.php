<?php

defined( 'ABSPATH' ) || exit;

final class CPT_Core extends CPT_Component {
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * @return void
	 */
	public function define_constants() {
		if ( ! defined( 'CPT_VERSION' ) ) {
			define( 'CPT_VERSION', get_file_data( CPT_PLUGIN_FILE, array( 'Version' => 'Version' ), false )['Version'] );
		}
		if ( ! defined( 'CPT_NAME' ) ) {
			define( 'CPT_NAME', get_file_data( CPT_PLUGIN_FILE, array( 'Plugin Name' => 'Plugin Name' ), false )['Plugin Name'] );
		}
		if ( ! defined( 'CPT_PRO_MIN_VERSION' ) ) {
			define( 'CPT_PRO_MIN_VERSION', '4.0.0' );
		}
		if ( ! defined( 'CPT_PATH' ) ) {
			define( 'CPT_PATH', plugin_dir_path( CPT_PLUGIN_FILE ) );
		}
		if ( ! defined( 'CPT_URL' ) ) {
			define( 'CPT_URL', plugin_dir_url( CPT_PLUGIN_FILE ) );
		}
		if ( ! defined( 'CPT_PLUGIN_URL' ) ) {
			define( 'CPT_PLUGIN_URL', 'https://totalpress.org/plugins/custom-post-types?utm_source=wp-dashboard&utm_medium=installed-plugin&utm_campaign=custom-post-types' );
		}
		if ( ! defined( 'CPT_PLUGIN_DEV_URL' ) ) {
			define( 'CPT_PLUGIN_DEV_URL', 'https://www.andreadegiovine.it/?utm_source=wp-dashboard&utm_medium=installed-plugin&utm_campaign=custom-post-types' );
		}
		if ( ! defined( 'CPT_PLUGIN_DOC_URL' ) ) {
			define( 'CPT_PLUGIN_DOC_URL', 'https://totalpress.org/docs/custom-post-types.html?utm_source=wp-dashboard&utm_medium=installed-plugin&utm_campaign=custom-post-types' );
		}
		if ( ! defined( 'CPT_PLUGIN_DONATE_URL' ) ) {
			define( 'CPT_PLUGIN_DONATE_URL', 'https://totalpress.org/donate?utm_source=wp-dashboard&utm_medium=installed-plugin&utm_campaign=custom-post-types' );
		}
		if ( ! defined( 'CPT_PLUGIN_WPORG_URL' ) ) {
			define( 'CPT_PLUGIN_WPORG_URL', 'https://wordpress.org/plugin/custom-post-types' );
		}
		if ( ! defined( 'CPT_PLUGIN_SUPPORT_URL' ) ) {
			define( 'CPT_PLUGIN_SUPPORT_URL', 'https://wordpress.org/support/plugin/custom-post-types' );
		}
		if ( ! defined( 'CPT_PLUGIN_REVIEW_URL' ) ) {
			define( 'CPT_PLUGIN_REVIEW_URL', 'https://wordpress.org/support/plugin/custom-post-types/reviews/#new-post' );
		}
		if ( ! defined( 'CPT_HOOK_PREFIX' ) ) {
			define( 'CPT_HOOK_PREFIX', 'cpt_' );
		}
		if ( ! defined( 'CPT_UI_PREFIX' ) ) {
			define( 'CPT_UI_PREFIX', 'manage_cpt' );
		}
		if ( ! defined( 'CPT_OPTIONS_PREFIX' ) ) {
			define( 'CPT_OPTIONS_PREFIX', 'custom_post_types_' );
		}
		if ( ! defined( 'CPT_NONCE_KEY' ) ) {
			define( 'CPT_NONCE_KEY', 'cpt-nonce' );
		}
	}

	/**
	 * @return void
	 */
	public function includes() {
		include_once CPT_PATH . '/includes/class-cpt-admin-pages.php';
		include_once CPT_PATH . '/includes/class-cpt-admin-notices.php';
		include_once CPT_PATH . '/includes/class-cpt-ajax.php';
		include_once CPT_PATH . '/includes/class-cpt-plugin.php';
		include_once CPT_PATH . '/includes/class-cpt-ui.php';
		include_once CPT_PATH . '/includes/class-cpt-utils.php';
		include_once CPT_PATH . '/includes/class-cpt-field-groups.php';
		include_once CPT_PATH . '/includes/class-cpt-fields.php';
		include_once CPT_PATH . '/includes/class-cpt-post-types.php';
		include_once CPT_PATH . '/includes/class-cpt-taxonomies.php';
		include_once CPT_PATH . '/includes/class-cpt-shortcodes.php';

		include_once CPT_PATH . '/includes/abstracts/class-cpt-field.php';
		include_once CPT_PATH . '/includes/fields/class-cpt-field-text.php';
		include_once CPT_PATH . '/includes/fields/class-cpt-field-number.php';
		include_once CPT_PATH . '/includes/fields/class-cpt-field-textarea.php';
		include_once CPT_PATH . '/includes/fields/class-cpt-field-tinymce.php';
		include_once CPT_PATH . '/includes/fields/class-cpt-field-range.php'; // PRO
		include_once CPT_PATH . '/includes/fields/class-cpt-field-checkbox.php';
		include_once CPT_PATH . '/includes/fields/class-cpt-field-radio.php';
		include_once CPT_PATH . '/includes/fields/class-cpt-field-select.php';
		include_once CPT_PATH . '/includes/fields/class-cpt-field-switch.php'; // PRO
		include_once CPT_PATH . '/includes/fields/class-cpt-field-tel.php';
		include_once CPT_PATH . '/includes/fields/class-cpt-field-email.php';
		include_once CPT_PATH . '/includes/fields/class-cpt-field-password.php'; // PRO
		include_once CPT_PATH . '/includes/fields/class-cpt-field-link.php'; // PRO
		include_once CPT_PATH . '/includes/fields/class-cpt-field-date.php';
		include_once CPT_PATH . '/includes/fields/class-cpt-field-time.php';
		include_once CPT_PATH . '/includes/fields/class-cpt-field-color.php';
		include_once CPT_PATH . '/includes/fields/class-cpt-field-file.php';
		include_once CPT_PATH . '/includes/fields/class-cpt-field-embed.php'; // PRO
		include_once CPT_PATH . '/includes/fields/class-cpt-field-map.php'; // PRO
		include_once CPT_PATH . '/includes/fields/class-cpt-field-post-rel.php';
		include_once CPT_PATH . '/includes/fields/class-cpt-field-tax-rel.php';
		include_once CPT_PATH . '/includes/fields/class-cpt-field-user-rel.php'; // PRO
		include_once CPT_PATH . '/includes/fields/class-cpt-field-html.php';
		include_once CPT_PATH . '/includes/fields/class-cpt-field-separator.php'; // PRO
		include_once CPT_PATH . '/includes/fields/class-cpt-field-repeater.php';

		include_once CPT_PATH . '/includes/compatibilities/v4.php';
//		include_once CPT_PATH . '/includes/compatibilities/acf.php';
//		include_once CPT_PATH . '/includes/compatibilities/saswp.php';
	}

	/**
	 * @return void
	 */
	public function init_hooks() {
		cpt_plugin()->init_hooks();
		cpt_ajax()->init_hooks();
		cpt_ui()->init_hooks();
		cpt_admin_pages()->init_hooks();
		cpt_admin_notices()->init_hooks();
		cpt_post_types()->init_hooks();
		cpt_taxonomies()->init_hooks();
		cpt_field_groups()->init_hooks();
		cpt_shortcodes()->init_hooks();
	}
}
