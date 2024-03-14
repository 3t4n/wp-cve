<?php  if ( ! defined( 'ABSPATH' ) ) exit;

spl_autoload_register( array('EPHD_Autoloader', 'autoload') );

/**
 * A class which contains the autoload function, that the spl_autoload_register
 * will use to autoload PHP classes.
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 */
class EPHD_Autoloader {

	public static function autoload( $class ) {
		static $classes = null;

		if ( $classes === null ) {
			$classes = array(

				// CORE
				'ephd_utilities'                    =>  'includes/class-ephd-utilities.php',
				'ephd_core_utilities'               =>  'includes/class-ephd-core-utilities.php',
				'ephd_html_elements'                =>  'includes/class-ephd-html-elements.php',
				'ephd_html_admin'                   =>  'includes/class-ephd-html-admin.php',
				'ephd_html_forms'                   =>  'includes/class-ephd-html-forms.php',
				'ephd_input_filter'                 =>  'includes/class-ephd-input-filter.php',
				'ephd_multilang_utilities'          =>  'includes/class-ephd-multilang-utilities.php',

				// SYSTEM
				'ephd_logging'                      =>  'includes/system/class-ephd-logging.php',
				'ephd_kb_core_utilities'            =>  'includes/system/class-ephd-kb-core-utilities.php',
				'ephd_help_upgrades'                =>  'includes/system/class-ephd-help-upgrades.php',
				'ephd_upgrades'                     =>  'includes/system/class-ephd-upgrades.php',
				'ephd_file_manager'                 =>  'includes/system/class-ephd-file-manager.php',
				'ephd_delete_hd'                	=>  'includes/system/class-ephd-delete-hd.php',
				'ephd_deactivate_feedback'          =>  'includes/system/class-ephd-deactivate-feedback.php',
				'ephd_typography'                   =>  'includes/system/class-ephd-typography.php',
				'ephd_db'                           =>  'includes/system/class-ephd-db.php',
				'ephd_wpml'                         =>  'includes/system/class-ephd-wpml.php',
				'ephd_admin_ui_access'              =>  'includes/system/class-ephd-admin-ui-access.php',

				// ADMIN CORE
				'ephd_admin_notices'                =>  'includes/admin/class-ephd-admin-notices.php',

				// NEED HELP
				'ephd_need_help_page'               =>  'includes/admin/need-help/class-ephd-need-help-page.php',
				'ephd_need_help_features'           =>  'includes/admin/need-help/class-ephd-need-help-features.php',
				'ephd_need_help_contact_us'         =>  'includes/admin/need-help/class-ephd-need-help-contact-us.php',

				// WIDGETS
				'ephd_widgets_page'                 =>  'includes/admin/widgets/class-ephd-widgets-page.php',
				'ephd_widgets_display'              =>  'includes/admin/widgets/class-ephd-widgets-display.php',
				'ephd_widgets_ctrl'                 =>  'includes/admin/widgets/class-ephd-widgets-ctrl.php',
				'ephd_widgets_db'                   =>  'includes/admin/widgets/class-ephd-widgets-db.php',

				// ANALYTICS
				'ephd_analytics_page'               =>  'includes/admin/analytics/class-ephd-analytics-page.php',
				'ephd_analytics_ctrl'               =>  'includes/admin/analytics/class-ephd-analytics-ctrl.php',
				'ephd_analytics_db'                 =>  'includes/admin/analytics/class-ephd-analytics-db.php',

				// CONTACT FORM
				'ephd_submissions_db'               =>  'includes/admin/contact-form/class-ephd-submissions-db.php',
				'ephd_contact_form_page'            =>  'includes/admin/contact-form/class-ephd-contact-form-page.php',
				'ephd_contact_form_display'         =>  'includes/admin/contact-form/class-ephd-contact-form-display.php',
				'ephd_contact_form_ctrl'            =>  'includes/admin/contact-form/class-ephd-contact-form-ctrl.php',

				// FAQs and ARTICLES
				'ephd_faqs_page'                    =>  'includes/admin/faqs/class-ephd-faqs-page.php',
				'ephd_faqs_display'                 =>  'includes/admin/faqs/class-ephd-faqs-display.php',
				'ephd_faqs_ctrl'                    =>  'includes/admin/faqs/class-ephd-faqs-ctrl.php',
				'ephd_faqs_db'                      =>  'includes/admin/faqs/class-ephd-faqs-db.php',

				// HD CONFIGURATION
				'ephd_admin_ctrl'                   =>  'includes/admin/hd-configuration/class-ephd-admin-ctrl.php',
				'ephd_config_db'                    =>  'includes/admin/hd-configuration/class-ephd-config-db.php',
				'ephd_config_specs'                 =>  'includes/admin/hd-configuration/class-ephd-config-specs.php',
				'ephd_config_page'                  =>  'includes/admin/hd-configuration/class-ephd-config-page.php',
				'ephd_config_tools_page'            =>  'includes/admin/hd-configuration/class-ephd-config-tools-page.php',
				'ephd_settings_controller'          =>  'includes/admin/hd-configuration/class-ephd-settings-controller.php',
				'ephd_premade_designs'              =>  'includes/admin/hd-configuration/class-ephd-premade-designs.php',
				'ephd_export_import'                =>  'includes/admin/hd-configuration/class-ephd-export-import.php',

				// OpenAI
				'ephd_openai'                       =>  'includes/admin/openai/class-ephd-openai.php',
				'ephd_ai_help_sidebar'              =>  'includes/admin/openai/class-ephd-ai-help-sidebar.php',
				'ephd_ai_help_sidebar_ctrl'              =>  'includes/admin/openai/class-ephd-ai-help-sidebar-ctrl.php',


				// FEATURES - HELP DIALOG
				'ephd_help_dialog_front_ctrl'       =>  'includes/features/help-dialog/class-ephd-help-dialog-front-ctrl.php',
				'ephd_help_dialog_handler'          =>  'includes/features/help-dialog/class-ephd-help-dialog-handler.php',
				'ephd_help_dialog_view'             =>  'includes/features/help-dialog/class-ephd-help-dialog-view.php',

				// FEATURES - SEARCH
				'ephd_search'                       =>  'includes/features/search/class-ephd-search.php',
				'ephd_search_db'                    =>  'includes/features/search/class-ephd-search-db.php',
				'ephd_search_query'                 =>  'includes/features/search/class-ephd-search-query.php',
				'ephd_search_query_posts'           =>  'includes/features/search/class-ephd-search-query-posts.php',
				'ephd_search_query_faqs'            =>  'includes/features/search/class-ephd-search-query-faqs.php',
				'ephd_search_query_extras'          =>  'includes/features/search/class-ephd-search-query-extras.php',
			);
		}

		$cn = strtolower( $class );
		if ( isset( $classes[ $cn ] ) ) {
			/** @noinspection PhpIncludeInspection */
			include_once( plugin_dir_path( Echo_Help_Dialog::$plugin_file ) . $classes[ $cn ] );
		}
	}
}
