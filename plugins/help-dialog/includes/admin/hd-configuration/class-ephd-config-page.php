<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display Help Dialog configuration page
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Config_Page {

	private $message = array(); // error/warning/success messages

	public function __construct() {
		$this->message = EPHD_Admin_Ctrl::handle_form_actions();
	}

	/**
	 * Displays the Help Dialog Config page with top panel
	 */
	public function display_page() {

		if ( ! current_user_can( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) ) ) {
			EPHD_Utilities::ajax_show_error_die( __( 'You do not have permission to edit Help Dialog.', 'help-dialog' ) );
			return;
		}

		$global_config = ephd_get_instance()->global_config_obj->get_config( true );
		if ( is_wp_error( $global_config ) ) {
			EPHD_HTML_Admin::display_config_error_page( $global_config );
			return;
		}

		$global_specs = EPHD_Config_Specs::get_fields_specification( EPHD_Config_DB::EPHD_GLOBAL_CONFIG_NAME );

		$admin_page_views = $this->get_regular_views_config( $global_config, $global_specs );

		EPHD_HTML_Admin::admin_page_css_missing_message( true );    ?>

		<!-- Admin Page Wrap -->
		<div id="ephd-admin-page-wrap">

			<div class="ephd-configuration-page-container">     <?php

				/**
				 * ADMIN HEADER
				 */
				EPHD_HTML_Admin::admin_header();

				/**
				 * ADMIN TOP PANEL
				 */
				EPHD_HTML_Admin::admin_toolbar( $admin_page_views );

				/**
				 * ADMIN SECONDARY TABS
				 */
				EPHD_HTML_Admin::admin_secondary_tabs( $admin_page_views );

				/**
				 * LIST OF SETTINGS IN TABS
				 */
				EPHD_HTML_Admin::admin_settings_tab_content( $admin_page_views, 'ephd-config-wrapper' );    ?>

				<div class="ephd-bottom-notice-message fadeOutDown"></div>
			</div>
		</div>	    <?php

		/**
		 * Show any notifications
		 */
		foreach ( $this->message as $class => $message ) {
			echo EPHD_HTML_Forms::notification_box_bottom( $message, '', $class );
		}
	}

	/**
	 * Show actions row for Settings tab
	 *
	 * @return false|string
	 */
	private static function settings_tab_actions_row() {

		ob_start();		?>

		<div class="ephd-admin__list-actions-row"><?php
			EPHD_HTML_Elements::submit_button_v2( __( 'Save Settings', 'help-dialog' ), 'ephd_hd_save_settings_btn', 'ephd__hdl__action__save_order', '', true, '', 'ephd-success-btn');    ?>
		</div>      <?php

		return ob_get_clean();
	}

    private static function get_hidden_help_dialog_element_classes_form( $global_config ) {
	    ob_start();

	    EPHD_HTML_Elements::textarea( array(
		    'specs'      => 'kb_article_hidden_classes',
		    'value'      => $global_config['kb_article_hidden_classes'],
		    'input_size' => 'large',
		    'rows'       => 3,
		    'desc'       => __( 'Add a comma separated list of CSS classes', 'help-dialog' ),
	    ) );

	    return ob_get_clean();
    }

	/**
	 * Show License boxes
	 *
	 * @param $license_content
	 * @return array[]
	 */
	private static function show_license_boxes( $license_content ) {

		ob_start();

		if ( ! empty( $license_content ) ) {    ?>
            <div class="add_on_container">
                <section id="ephd-licenses">
                    <ul>  	<!-- Add-on name / License input / status  -->   <?php
						echo $license_content;      ?>
                    </ul>
                </section>
            </div>      <?php
		}

		$license_content = ob_get_clean();

		return array(

			// Box: Licenses
			array(
				'title' => __( 'License for Help Dialog PRO', 'help-dialog' ),
				'description' => self::get_licenses_box_description(),
				'html' => $license_content,
			)
		);
	}

	/**
	 * Get description for Licenses box
	 *
	 * @return string
	 */
	private static function get_licenses_box_description() {
		return sprintf( __( 'You can access your license account %s here%s' , 'help-dialog' ), '<a href="https://www.helpdialog.com/account-dashboard/" target="_blank" rel="noopener">', '</a>' ) .
		       '<br />' . sprintf( __( 'Please refer to the %s documentation%s for help with your license account and any other issues.', 'help-dialog' ), '<a href="https://www.helpdialog.com/documentation/license-faqs/" target="_blank" rel="noopener">', '</a>');
	}

	/**
	 * Get HTML for Excluded User Roles box on Settings tab
	 *
	 * @param $included_roles
	 * @return false|string
	 */
	private static function settings_tab_included_user_roles_box( $included_roles ) {
		ob_start();

		$editable_roles = [];
		foreach ( get_editable_roles() as $role_name => $role_info ) {
			$editable_roles[$role_name] = $role_info['name'];
		}

		EPHD_HTML_Elements::checkboxes_multi_select( array(
				'label'             => __( 'Include specific user roles from Private FAQs access', 'help-dialog' ),
				'name'              => 'private_faqs_included_roles',
				'options'           => array_merge( $editable_roles ),
				'value'             => $included_roles,
				'main_tag'          => 'div',
				'input_class'       => '',
				'input_group_class' => 'ephd-admin__checkboxes-multiselect',
			)
		);

		return ob_get_clean();
	}

	/**
	 * Get configuration array for regular views of Help Dialog Configuration page
	 *
	 * @param $global_config
	 * @param $global_specs
	 *
	 * @return array[]
	 */
	private function get_regular_views_config( $global_config, $global_specs ) {

		$regular_views = [];

		/**
		 * VIEW: SETTINGS
		 */
		$delete_hd_handler = new EPHD_Delete_HD();
		$regular_views[] = array(

			// Shared
			'active' => true,
			'list_key' => 'settings',

			// Top Panel Item
			'label_text' => __( 'Settings', 'help-dialog' ),
			'icon_class' => 'ephdfa ephdfa-cogs',

			// Boxes List
			'list_top_actions_html' => self::settings_tab_actions_row(),
			'boxes_list' => array(

				array(
					'title'         => __( 'OpenAI', 'help-dialog' ),
					'html'          => self::get_openai_form( $global_config ),
				),
				// Box: WPML
				array(
					'title'         => __( 'Polylang/WPML Setup', 'help-dialog' ),
					'html'          => self::get_polylang_wpml_form( $global_config ),
				),
				// Box: Hide KB article elements
				array(
					'title'         => __( 'Hide Some Content of KB Articles', 'help-dialog' ),
					'html'          => self::get_hidden_help_dialog_element_classes_form( $global_config ),
				),
				// Box: included User Roles for Help Dialog Menu
				EPHD_Admin_UI_Access::get_access_box( $global_config ),
				// Box: included User Roles for Private FAQs
				/* array(
					'title' => $global_specs['private_faqs_included_roles']['label'],
					'html'  => self::settings_tab_included_user_roles_box( $global_config['private_faqs_included_roles'] ),
				), */
				// Box: reset config
				array(
					'title' => __( 'Reset HD config', 'help-dialog' ),
					'html'  => self::get_reset_settings_html(),
				),
				// Box: Delete All Help Dialog Data
				array(
					'title' => __( 'Help Dialog Plugin Removal', 'help-dialog' ),
					'html' => $delete_hd_handler->get_delete_all_help_dialog_data_form(),
				),
			),
		);

		/**
		 * VIEW: DEBUG
		 */
		$regular_views[] = array(

			// Shared
			'list_key' => 'debug',

			// Top Panel Item
			'label_text' => __( 'Debug', 'help-dialog' ),
			'icon_class' => 'ephdfa ephdfa-info-circle',

			// Boxes List
			'boxes_list' => array(

				// Box: Information required for support
				array(
					'title' => __( 'Information required for support', 'help-dialog' ),
					'description' => __( 'Enable debugging when instructed by the Echo team.', 'help-dialog' ),
					'html' => EPHD_Settings_Controller::display_debug_info(),
				),
			),
		);

		/**
		 * VIEW: TOOLS
		 */
		$regular_views[] = EPHD_Config_Tools_Page::get_tools_view_config();


		/**
		 * View: Licenses
		 */
		$license_content = '';
		if ( current_user_can( 'manage_options' ) ) {
			$license_content = apply_filters( 'ephd_license_fields', $license_content );
		}

		if ( ! empty( $license_content ) ) {
			$regular_views[] = [

				// Shared
				'list_id'    => 'echd_license_tab',
				'list_key'   => 'licenses',

				// Top Panel Item
				'label_text' => __( 'Licenses', 'help-dialog' ),
				'icon_class' => 'ephdfa ephdfa-key',

				// Boxes List
				'boxes_list' => self::show_license_boxes( $license_content ),
			];
		}

		return $regular_views;
	}

	/**
	 * @return false|string
	 */
	private static function get_reset_settings_html() {

		// only administrators can handle this page
		if ( ! current_user_can('manage_options') ) {
			return '';
		}

		ob_start(); ?>
		<form class="ephd-reset-all-configs__form" action="" method="post">

			<p class="ephd-reset-all-configs__form-title"><?php echo sprintf( esc_html__( 'Write "%s" in the below input box if you want to reset ALL Help Dialog configuration.', 'help-dialog' ), 'reset' ); ?></p><?php

			EPHD_HTML_Elements::text_basic( array(
				'value' => '',
				'name'    => 'reset_text',
			) );

			EPHD_HTML_Elements::submit_button_v2( esc_html__( 'Reset Configuration', 'help-dialog' ), 'ephd_reset_all', '', '', true, '', 'ephd-error-btn' );   ?>

		</form>     <?php

		return ob_get_clean();
	}

	/**
	 * @return false|string
	 */
	private static function get_polylang_wpml_form( $global_config ) {

		// only administrators can handle this page
		if ( ! current_user_can('manage_options') ) {
			return '';
		}

		ob_start(); ?>
		<form class="ephd-polylang-wpml__form" action="" method="post"> <?php

			EPHD_HTML_Elements::checkbox_toggle( array(
				'id'            => 'wpml_toggle',
				'name'          => 'wpml_toggle',
				'specs'         => 'wpml_toggle',
				'text'          => esc_html__( 'Polylang/WPML Setup', 'help-dialog' ),
				'toggleOnText'  => esc_html__( 'yes', 'help-dialog' ),
				'toggleOffText'  => esc_html__( 'no', 'help-dialog' ),
				'checked'       => $global_config['wpml_toggle'] == 'on',
				'input_group_class' => 'ephd-input-group'
			) ); ?>

		</form>     <?php

		return ob_get_clean();
	}

	private static function get_openai_form( $global_config ) {

		// only administrators can handle this page
		if ( ! current_user_can('manage_options') ) {
			return '';
		}

		ob_start();

		EPHD_HTML_Elements::text( [
			'value'         => $global_config['openai_api_key'],
			'specs'         => 'openai_api_key',
			'input_size'    => 'large',
			'tooltip_body'  => esc_html__( 'Enter your OpenAI API key.', 'help-dialog' ) . ' <a href="https://beta.openai.com/account/api-keys" target="_blank" rel="noopener">' . esc_html__( 'Get OpenAI API Key', 'help-dialog' ) . '</a>',
		] );

		/** EPHD_HTML_Elements::text( [
			'value'         => $global_config['openai_max_tokens'],
			'specs'         => 'openai_max_tokens',
			'input_size'    => 'small',
			'tooltip_body'  => __( 'The maximum number of tokens to generate in the completion. The token count of your prompt plus max_tokens cannot exceed the model\'s context length. Most models have a context length ' .
			                       'of 2048 tokens (except for the newest models, which support 4096).', 'help-dialog' ) . ' <a href="https://platform.openai.com/docs/api-reference/completions/create#completions/create-max_tokens" 
                                target="_blank" rel="noopener">' . __( 'See it in OpenAI Documentation', 'help-dialog' ) . '</a>',
		] ); */

		return ob_get_clean();
	}
}
