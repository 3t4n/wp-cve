<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display Help Dialog Widgets page
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Widgets_Page {

	const ORDER_LOCATIONS_BY = ['post_title', 'post_modified', 'unassigned_first', 'assigned_first'];

	private $message = array(); // error/warning/success messages
	private $all_widget_ids;
	private $all_faqs;

	private $global_config;
	private $widgets_config;

	private $widget_specs;

	public function __construct( $widgets_config ) {

		$this->widgets_config = $widgets_config;
		$this->widget_specs = EPHD_Config_Specs::get_fields_specification( EPHD_Widgets_DB::EPHD_WIDGETS_CONFIG_NAME );
		$this->all_widget_ids = array_keys( $this->widgets_config );

        // Global config and specs
		$this->global_config = ephd_get_instance()->global_config_obj->get_config( true );

		$faqs_db_handler = new EPHD_FAQs_DB();
		$this->all_faqs = $faqs_db_handler->get_all_faqs();
	}

	/**
	 * Displays the Help Dialog Widgets page with top panel
	 */
	public function display_page() {

		if ( is_wp_error( $this->global_config ) ) {
			EPHD_HTML_Admin::display_config_error_page( $this->global_config );
			return;
		}

		$admin_page_views = $this->get_regular_view_config();

		EPHD_HTML_Admin::admin_page_css_missing_message( true );    ?>

		<!-- Admin Page Wrap -->
		<div id="ephd-admin-page-wrap">

            <div class="ephd-widgets-page-container">				<?php

				/**
				 * ADMIN HEADER
				 */
				EPHD_HTML_Admin::admin_header();

                /**
	             * LIST OF SETTINGS IN TABS
	             */
	            EPHD_HTML_Admin::admin_settings_tab_content( $admin_page_views, 'ephd-config-wrapper' );

                // FAQs editor
	            EPHD_Core_Utilities::display_wp_editor( $this->widgets_config[ EPHD_Widgets_DB::DEFAULT_ID]['widget_id'] );

	            // Confirmation pop-up to delete a Widget
	            EPHD_HTML_Forms::dialog_confirm_action( array(
		            'id'                => 'ephd-wp__delete-widget-confirmation',
		            'title'             => __( 'Deleting Widget', 'help-dialog' ),
		            'body'              => __( 'Are you sure you want to delete the Widget? You cannot undo this action.', 'help-dialog' ),
		            'accept_label'      => __( 'Delete', 'help-dialog' ),
		            'accept_type'       => 'warning',
		            'show_cancel_btn'   => 'yes',
		            'form_method'       => 'post',
	            ) );

	            // Confirmation pop-up to delete a Question
	            self::delete_question_confirm__dialog( $this->widgets_config );

	            // Confirmation pop-up to delete FAQs
	            EPHD_HTML_Forms::dialog_confirm_action( array(
		            'id'                => 'ephd-fp__delete-faqs-confirmation',
		            'title'             => __( 'Deleting FAQs', 'help-dialog' ),
		            'body'              => __( 'Are you sure you want to delete the FAQs? You cannot undo this action.', 'help-dialog' ),
		            'accept_label'      => __( 'Delete', 'help-dialog' ),
		            'accept_type'       => 'warning',
		            'show_cancel_btn'   => 'yes',
		            'form_method'       => 'post',
	            ) );    ?>

                <div class="ephd-bottom-notice-message fadeOutDown"></div>

            </div>

        </div>        <?php

		/**
		 * Show any notifications
		 */
		foreach ( $this->message as $class => $message ) {
			echo  EPHD_HTML_Forms::notification_box_bottom( $message, '', $class );
		}
	}

	/**
	 * Return HTML for delete question Confirm Dialog
	 *
	 * @param $widgets_config
	 */
	public static function delete_question_confirm__dialog( $widgets_config ) {

		ob_start(); ?>
        <p><?php echo __( 'Are you sure you want to delete the question? You cannot undo this action.', 'help-dialog' ); ?></p>
        <div class="ephd-admin__confirm-dialog-assigned-widgets">
            <p><?php echo __( 'Deleting this question will remove it from all assigned Widgets:', 'help-dialog' );  ?></p>
            <ul>    <?php
			foreach ( $widgets_config as $widget ) { ?>
                <li class="ephd-admin__confirm-dialog-assigned-widget" data-widget-id="<?php echo esc_attr( $widget['widget_id'] ); ?>" data-faq-sequence="<?php echo esc_attr( implode( ',', array_filter( $widget['faqs_sequence'] ) ) ); ?>">
					<?php echo esc_html( $widget['widget_name'] ); ?>
                </li>  <?php
			}   ?>
            </ul>
        </div>  <?php

		$dialog_confirm_body = ob_get_clean();

		EPHD_HTML_Forms::dialog_confirm_action( array(
			'id'                => 'ephd-fp_delete-question-confirmation',
			'title'             => __( 'Deleting Question', 'help-dialog' ),
			'body'              => $dialog_confirm_body,
			'accept_label'      => __( 'Delete', 'help-dialog' ),
			'accept_type'       => 'warning',
			'form_inputs'       => array( '<input type="hidden" value="" id="ephd-fp_delete-question-confirmation-id">' ),
			'show_cancel_btn'   => 'yes',
			'form_method'       => 'post',
		) );

	}

	/**
	 * Display HD Widget preview
	 */
    private function display_widget_preview_box() { ?>
        <!-- Preview -->
        <div class="ephd-wp__widget-preview" data-preview-size="<?php echo esc_attr( $this->global_config['dialog_width'] ); ?>">
            <div class="ephd-wp__widget-preview-tooltip">
				<div class="ephd-wp__widget-preview-tooltip--chat" style="display:none;"><?php
					esc_html_e( 'Live preview of the Chat tab.', 'help-dialog' ); ?></div>
				<div class="ephd-wp__widget-preview-tooltip--faqs" style="display:none;"><?php
					esc_html_e( 'Live preview of the FAQs tab. Drag and drop questions to order them.', 'help-dialog' ); ?></div>
				<div class="ephd-wp__widget-preview-tooltip--contact" style="display:none;"><?php
					esc_html_e( 'Live preview of the Contact Form tab.', 'help-dialog' ); ?></div>
            </div>
	        <div class="ephd-wp__widget-preview-page">
		        <div class="ephd-wp__widget-preview-page-header">
			        <svg width="40" height="8" viewBox="0 0 40 8" fill="none" xmlns="http://www.w3.org/2000/svg">
				        <circle cx="4" cy="4" r="4" fill="#C6D7E3"></circle>
				        <circle cx="20" cy="4" r="4" fill="#C6D7E3"></circle>
				        <circle cx="36" cy="4" r="4" fill="#C6D7E3"></circle>
			        </svg>
		        </div>
		        <div class="ephd-wp__widget-preview-page-body">
			        <div class="ephd-wp__widget-preview-content"></div>
		        </div>
	        </div>
        </div>  <?php
    }

	/**
	 * Get boxes configuration array for Pop-ups tab
	 *
	 * @return array
	 */
	private function get_widgets_boxes() {

		$widgets_boxes = [];

        // license issues boxes
		$license_issues = self::get_license_issues_boxes();
		$widgets_boxes = array_merge( $widgets_boxes, $license_issues );

		// existing Widgets
		foreach ( $this->widgets_config as $widget ) {
			$widgets_boxes[] = $this->get_config_of_widget_preview_box( $widget );
		}

		// form to create/edit Widget
		$widgets_boxes[] = array(
			'class' => 'ephd-wp__widget-form',
			'html'  => $this->get_widget_form( $this->widgets_config[EPHD_Widgets_DB::DEFAULT_ID] ),
		);

		return $widgets_boxes;
	}

	/**
	 * Get configuration array for License Issues errors
	 *
	 * @return array
	 */
	private static function get_license_issues_boxes() {

		$error_boxes = array();

		// License issue messages from add-ons
		$add_on_messages = apply_filters( 'ephd_add_on_license_message', array() );

		if ( ( ! empty( $add_on_messages ) && is_array( $add_on_messages ) ) || did_action( 'hd_overview_add_on_errors' ) ) {

			$licenses_tab_url = admin_url( 'admin.php?page=ephd-help-dialog-advanced-config#licenses' );
			$licenses_tab_button = '<a href="' . esc_url( $licenses_tab_url ) . '" class="ephd-primary-btn"> ' . esc_html__( 'Fix the Issue', 'help-dialog' ) . '</a>';

			foreach ( $add_on_messages as $add_on_name => $add_on_message ) {

				// Add 'See Your License' button html
				$add_on_message .= $licenses_tab_button;

				$error_boxes[] = array(
					'icon_class' => 'ephdfa-exclamation-circle',
					'class' => 'ephd-admin__boxes-list__box__addons-license',
					'title' => $add_on_name . ': ' . __( 'License issue', 'help-dialog' ),
					'description' => '',
					'html' => $add_on_message,
				);
			}
		}

		return $error_boxes;
	}

	/**
	 * Return HTML for editor form of a single widget
	 *
	 * @param $widget
	 *
	 * @return false|string
	 */
	public function get_widget_form( $widget ) {

		ob_start();     ?>

		<input type="hidden" value="<?php echo esc_attr( $widget['widget_id'] ); ?>" name="widget_id" />
		<input type="hidden" value="<?php echo esc_attr( $widget['widget_status'] ); ?>" name="widget_status" /><?php

		// Widget preview
		$this->display_widget_preview_box();

		$preview_url = EPHD_Core_Utilities::get_first_widget_page_url( $widget );

		EPHD_HTML_Admin::display_admin_form_header( array(
			'icon_html'     => EPHD_HTML_Admin::get_hd_icon_html( 'ephd-admin__form-title-icon' ),
			'title'         => $widget['widget_name'],
			'title_desc'    => __( 'Widget Name: ', 'help-dialog' ),
			'desc'          => __( 'Widget Settings', 'help-dialog' ),
			'actions_html'  => self::get_widget_form_actions_html( $widget ),
			'preview_url'   => $preview_url
		) );

		$tabs_config = [
			'locations' => [
				'label' => __( 'Locations / Triggers', 'help-dialog' ),
				'tabs'  => [],
			],
			'structure' => [
				'label' => __( 'Structure', 'help-dialog' ),
				'tabs'  => [],
			],
			'tab_features' => [
				'label' => __( 'Main Features', 'help-dialog' ),
				'tabs'  => [],
			],
			'design' => [
				'label' => __( 'Design', 'help-dialog' ),
				'tabs'  => [],
			],
		];

		// Locations/Triggers: Pages
		$tabs_config['locations']['tabs'][] = array(
			'title'     => __( 'Pages', 'help-dialog' ),
			'icon'      => 'ephdfa ephdfa-file-text-o',
			'key'       => 'pages',
			'active'    => true,
			'contents'  => array(
				array(
					'title'         => __( 'Show On Pages', 'help-dialog' ),
					'desc'          => __( 'Choose pages to display the Help Dialog widget. Add more pages by searching for them.', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_widget_pages( $widget ),
					'read_more_url' => 'https://www.helpdialog.com/documentation/',
					'read_more_text'=> __( 'Read More', 'help-dialog' ),
				),
			),
		);

		// Locations/Triggers: Triggers
		$tabs_config['locations']['tabs'][] = array(
			'title'     => __( 'Triggers', 'help-dialog' ),
			'icon'      => 'ephdfa ephdfa-eye',
			'key'       => 'triggers',
			'active'    => false,
			'contents'  => array(
				array(
					'title'         => __( 'Display Widget After Delay', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_trigger_delay( $widget ),
				),
				array(
					'title'         => __( 'Display Widget After Scroll', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_trigger_scroll( $widget ),
				),
				/*array(
					'title'         => __( 'Display Widget After Number of Days and Hours', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_trigger_days_and_hours( $widget ),
				),*/
			),
		);

		// Structure: Launcher
		$tabs_config['structure']['tabs'][] = array(
			'title'         => __( 'Launcher', 'help-dialog' ),
			'icon'          => 'ephdfa ephdfa-font ephdfa-comments-o',
			'key'           => 'launcher',
			'active'        => false,
			'contents'  => array(
				array(
					'title'         => __( 'Settings', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_global_launcher_settings( $widget ),
				),
				array(
					'title'         => __( 'Initial Message', 'help-dialog' ) . ' (' . __( 'Optional', 'help-dialog' ) . ')',
					'body_html'     => $this->get_tab_content_initial_message( $widget ),
				),
			),
			'data'      => array( 'preview' => 2 ),
		);

		// Structure: Dialog Settings
		$tabs_config['structure']['tabs'][] = array(
			'title'         => __( 'Dialog', 'help-dialog' ),
			'icon'          => 'ephdfa ephd-dialog-icon',
			'key'           => 'global-settings',
			'active'        => false,
			'contents'  => array(
				array(
					'title'         => __( 'Dialog Set Up', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_global_dialog_settings( $widget ),
				),
				array(
					'title'         => __( 'Article/Post Preview', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_article_preview_settings(),
				),
			),
			'data'      => array( 'preview' => 1 ),
		);

		// Structure: Search
		$tabs_config['structure']['tabs'][] = array(
			'title'     => __( 'Search', 'help-dialog' ),
			'icon'      => 'ephdfa ephdfa-search',
			'key'       => 'search',
			'active'    => false,
			'contents'  => array(
				array(
					'title'         => __( 'Search', 'help-dialog' ),
					'desc'          => __( 'Choose Search to show in the Help Dialog widget.', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_widget_search( $widget ),
				),
			),
			'data'      => array( 'preview' => 1 ),
		);

		// Structure: Messages
		/* FUTURE TODO $tabs_config['structure']['tabs'][] = array(
			'title'     => __( 'Messages', 'help-dialog' ),
			'icon'      => 'ephdfa ephdfa-commenting-o',
			'key'       => 'messages',
			'active'    => false,
			'contents'  => array(),
		); */

		// General tab contents
		$general_tab_contents = array(
			array(
				'title'     => __( 'Widget Nickname', 'help-dialog' ),
				'body_html' => $this->get_tab_content_global_widget_settings( $widget ),
			),
		);

		// show Delete box only for non-default existing Widgets
		if ( $widget['widget_id'] > EPHD_Config_Specs::DEFAULT_ID ) {
			$general_tab_contents[] = array(
				'title'     => __( 'Delete This Widget', 'help-dialog' ),
				'body_html' => $this->get_tab_content_delete_widget( $widget ),
			);
		}

		// Structure: General
		$tabs_config['structure']['tabs'][] = array(
			'title'     => __( 'General', 'help-dialog' ),
			'icon'      => 'ephdfa ephdfa-cog',
			'key'       => 'general',
			'active'    => false,
			'contents'  => $general_tab_contents,
		);

		// Tab Features: Chat
		$tabs_config['tab_features']['tabs'][] = array(
			'title'         => __( 'Chat', 'help-dialog' ),
			'icon'          => 'ephdfa ephdfa-comments-o',
			'key'           => 'chat',
			'active'        => false,
			'contents'  => array(
				array(
					'title'         => __( 'Chat', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_chat_settings( $widget ),
				),
			),
			'data'      => array( 'preview' => 1 ),
		);

		// Tab Features: FAQs
		$tabs_config['tab_features']['tabs'][] = array(
			'title'     => __( 'FAQs', 'help-dialog' ),
			'icon'      => 'ephdfa ephd-faqs-icon',
			'key'       => 'faqs',
			'active'    => false,
			'contents'  => array(
				array(
					'title'         => __( 'FAQs', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_faqs_settings( $widget ),
				),
				array(
					'title'         => __( 'Add Questions to the Help Dialog', 'help-dialog' ),
					'desc'          => __( 'All questions will be searchable, but only questions added to the Widget here will be shown when the Widget is displayed.', 'help-dialog' ),
					'body_html'     => self::get_tab_content_widget_faqs( $widget, $this->all_faqs, true ),
				),
			),
			'data'      => array( 'preview' => 1 ),
		);

		// Tab Features: Contact Form
		$tabs_config['tab_features']['tabs'][] = array(
			'title'         => __( 'Contact Form', 'help-dialog' ),
			'icon'          => 'ephdfa ephdfa-envelope-o',
			'key'           => 'contact-form',
			'active'        => false,
			'contents'  => array(
				array(
					'title'         => __( 'Contact Form', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_contact_form_settings( $widget ),
				),
			),
			'data'      => array( 'preview' => 1 ),
		);

		// Design: Colors
		$tabs_config['design']['tabs'][] = array(
			'title'         => __( 'Colors', 'help-dialog' ),
			'icon'          => 'ephdfa ephdfa-paint-brush',
			'key'           => 'colors',
			'active'        => false,
			'contents'  => array(
				array(
					'title'         => __( 'Predefined Colors', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_predefined_colors( $widget ),
				),
				array(
					'title'         => __( 'Launcher', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_colors_launcher( $widget ),
				),
				array(
					'title'         => __( 'Help Dialog Window', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_colors_hd_window( $widget ),
				),
				array(
					'title'         => __( 'Search Results', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_colors_search( $widget ),
				),
				array(
					'title'         => __( 'FAQ Questions', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_colors_faqs( $widget ),
				),
				array(
					'title'         => __( 'Single Article', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_colors_article( $widget ),
				),
				array(
					'title'         => __( 'Back Button', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_colors_back_button( $widget ),
				),
				array(
					'title'         => __( 'Contact Us Submit Button', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_colors_contact( $widget ),
				),
				array(
					'title'         => __( 'Contact Us Acceptance Checkbox', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_colors_contact_acceptance( $widget ),
				),
				array(
					'title'         => __( 'Chat', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_colors_chat( $widget ),
				),
			),
			'data'      => array( 'preview' => 1 ),
		);

		// Design: Labels
		$tabs_config['design']['tabs'][] = array(
			'title'         => __( 'Labels', 'help-dialog' ),
			'icon'          => 'ephdfa ephdfa-font',
			'key'           => 'labels',
			'active'        => false,
			'contents'  => array(
				array(
					'title'         => __( 'Launcher', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_labels_launcher( $widget ),
				),
				array(
					'title'         => __( 'FAQ', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_labels_faqs( $widget ),
				),
				array(
					'title'         => __( 'Search Results', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_labels_search( $widget ),
				),
				array(
					'title'         => __( 'Contact Form', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_labels_contact( $widget ),
				),
				array(
					'title'         => __( 'Chat', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_labels_chat( $widget ),
				),
				array(
					'title'         => __( 'Other', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_labels_other( $widget ),
				),
			),
			'data'      => array( 'preview' => 1 ),
		); ?>

		<!-- Widget Form Body -->
		<div class="ephd-wp__widget-form__body">    <?php
			EPHD_HTML_Admin::display_admin_form_tabs( $tabs_config );   ?>
		</div><!-- End Widget Form Body --> <?php

		return ob_get_clean();
	}

	/**
	 * Get configuration array for Widgets views of Help Dialog admin page
	 *
	 * @return array
	 */
	private function get_regular_view_config() {

		/**
		 * VIEW: WP Editor for 'No Results Content' option
		 */
		if ( EPHD_Utilities::get( 'ephd_fe_option' ) == 'no-results-found-content-html' ) {

			$widget_id = EPHD_Utilities::get( 'widget_id' );
			if ( empty( $widget_id ) ) {
				return null;
			}

			$views_config[] = array(

				// Shared
				'active' => true,
				'list_key' => 'tiny-mce-input',

				// Boxes List
				'boxes_list' => array(

					// Box: No results found
					array(
						'class' => 'ephd-wp__tiny-mce-input ephd-wp__tiny-mce-input--no-results-found-content-html',
						'html'  => $this->get_no_results_content_box_html( $widget_id ),
					),
				),
			);

			return $views_config;
		}

		/**
		 * VIEW: Widgets
		 */
		$views_config[] = array(

			// Shared
			'active' => true,
			'list_key' => 'widgets',

			// Boxes List
			'boxes_list' => $this->get_widgets_boxes(),
		);

		return $views_config;
	}

	/**
	 * Return or display a certain type (page, post, cpt) of Locations that are not assigned in any Widgets
	 *
	 * @param $locations_type
	 * @param false $return_html
	 * @param string $search_value
	 * @param array $excluded_ids
	 * @param string $location_page_filtering
	 * @param int $widget_id
	 * @param string $language
	 * @return false|string|void
	 */
	public function get_available_locations_list( $locations_type, $return_html=false, $search_value='', $excluded_ids=[], $location_page_filtering = 'include', $widget_id = 0, $language = 'all' ) {

		$locations = $this->get_locations( $locations_type, 'post_title', $search_value, $excluded_ids, [], true, $language );

		if ( $return_html ) {
			ob_start();
		}

		$page_on_front = get_option( 'page_on_front' );

		foreach ( $locations as $location ) {
			$location_title = strlen( $location->post_title ) > 25 ? substr( $location->post_title, 0, 25 ) . '...' : $location->post_title;

			$assigned_widget_id = '';
			$assigned_widget_name = '';

			if ( $location_page_filtering == 'exclude' && ! empty( $location->ID ) && $page_on_front == $location->ID ) {
				continue;
			}

			if ( $location_page_filtering == 'include' ) {

				// do not show page which is used as static Home Page; because we always show 'Home Page' as independent to any page ID
				if ( ! empty( $location->ID ) && $page_on_front == $location->ID ) {
					continue;
				}

				$post_type = $location->post_type != 'page' && $location->post_type != 'post' ? 'cpt' : $location->post_type;

				$assigned_widget = EPHD_Core_Utilities::get_widget_by_page( $location->ID, $post_type, true );

				if ( ! empty( $assigned_widget ) ) {
					$assigned_widget_id = $assigned_widget['widget_id'];
					$assigned_widget_name = $assigned_widget['widget_name'];
				}
			} ?>
			<li class="ephd-wp__location ephd-wp__location--selected" data-id="<?php echo esc_attr( $location->ID ); ?>" data-assigned_widget_id="<?php echo esc_attr( $assigned_widget_id ); ?>">
				<span><?php echo esc_html( $location_title ); ?></span> <?php

				if ( ! empty( $assigned_widget_id ) && $assigned_widget_id != $widget_id ) { ?>
					<span class="ephd-wp__location-assigns ephd-wp__location-assigns-text">   <?php
						echo '(' . esc_html__( 'Included in: ', 'help-dialog' ) . esc_html( $assigned_widget_name . ')' );   ?></span> <?php
				} ?>
			</li>   <?php
		}

		if ( $return_html ) {
			return ob_get_clean();
		}
	}

	/**
	 * Return or display a certain type (page, post, cpt) of Locations for a given Widget
	 *
	 * @param $locations_type
	 * @param $widget_id
	 */
	private function get_widget_locations_list( $locations_type, $widget_id ) {

        // Limit of displayed locations
		$limit = 15;

		$include_locations = $this->get_selected_widgets_locations( $locations_type, [$widget_id] );

		$locations = empty( $include_locations ) ? [] : $this->get_locations( $locations_type, 'post_title', '', [], $include_locations, false );

		ob_start(); ?>
        <ul class="ephd-wp__selected-locations-list">   <?php
            $count = 0;
		    foreach ( $locations as $location ) {
			    $location_title = strlen( $location->post_title ) > 40 ? substr( $location->post_title, 0, 40 ) . '...' : $location->post_title;    ?>
			    <li class="ephd-wp__location ephd-wp__location--selected <?php echo ( ++$count > $limit ) ? 'ephd-wp__location--hidden' : ''; ?>" data-id="<?php echo esc_attr( $location->ID ); ?>">
				    <span><?php echo esc_html( $location_title ); ?></span>
			    </li>   <?php
		    }   ?>
        </ul>   <?php
        $selected_locations_html = ob_get_clean();

        echo $selected_locations_html;  ?>

        <div class="ephd-wp__selected-locations-popup">
            <a class="ephd-wp__popup-show-btn <?php echo ( $count <= $limit ) ? esc_attr( 'ephd-wp__popup-show-btn--hidden' ) : ''; ?>">
                <?php esc_html_e( 'View All', 'help-dialog' ); ?>
            </a>    <?php
            EPHD_HTML_Admin::widget_details_popup(
                __( 'Selected Locations', 'help-dialog' ) . ' (' . ucfirst( $locations_type ) . 's)',
                $selected_locations_html
            );   ?>
        </div>  <?php
	}

	/**
	 * Return KB custom post types options
	 *
	 * @return array
	 */
	private function get_kb_cpt_options() {
		$kb_cpt = array();
		foreach ( $this->get_cpt_locations() as $cpt ) {
			if ( EPHD_KB_Core_Utilities::is_kb_post_type( $cpt->post_type ) ) {
				$kb_cpt[$cpt->ID] = $cpt->post_title;
			}
		}
		return $kb_cpt;
	}

	/**
	 * Display select field option for Features tab
	 *
	 * @param $widget
	 * @param $options
	 * @param $option_name
	 * @param $tooltip
	 *
	 * @param string $no_option_title
	 */
	private function display_widget_feature_option_select_field( $widget, $options, $option_name, $no_option_title, $tooltip ) { ?>
		<!-- Widget Feature Option Field -->
		<div class="ephd-wp__feature-option-field ephd-wp__feature-option-field--select">
			<div class="ephd-wp__feature-option-field-title">
				<span><?php echo esc_html( $this->widget_specs[$option_name]['label'] ); ?></span>  <?php
				EPHD_HTML_Elements::display_tooltip( $this->widget_specs[$option_name]['label'], $tooltip );   ?>
			</div>
			<div class="ephd-wp__feature-option-field-content">
				<select name="<?php echo esc_attr( $option_name ); ?>" autocomplete="off" data-value="<?php echo esc_attr( $widget[$option_name] ); ?>">
					<option value="off"><?php echo esc_html( $no_option_title ); ?></option>     <?php
					foreach ( $options as $value => $title ) {  ?>
						<option value="<?php echo esc_attr( $value ); ?>"<?php echo selected( $value, $widget[$option_name] ); ?>><?php echo esc_html( $title ); ?></option>     <?php
					}   ?>
				</select>
			</div>
		</div>      <?php
	}

	/**
	 * Display toggle field option for Features tab
	 *
	 * @param $widget
	 * @param $option_name
	 * @param $tooltip
	 */
	private function display_widget_feature_option_toggle_field( $widget, $option_name, $tooltip ) {    ?>
		<!-- Widget Feature Option Field -->
		<div class="ephd-wp__feature-option-field ephd-wp__feature-option-field--text ephd-wp__feature-option-field--<?php echo $option_name; ?>">
			<div class="ephd-wp__feature-option-field-title">
				<span><?php echo esc_html( $this->widget_specs[$option_name]['label'] ); ?></span>  <?php
				EPHD_HTML_Elements::display_tooltip( $this->widget_specs[$option_name]['label'], $tooltip );   ?>
			</div>
			<ul class="ephd-wp__feature-option-field-content">  <?php
				EPHD_HTML_Elements::checkbox_toggle( array(
					'id'            => $option_name . '__' . $widget['widget_id'],
					'name'          => $option_name,
					'checked'       => ( $widget[$option_name] == 'on' || $widget[$option_name] == 'show_search' ),
					'toggleOnText'  => 'on',
					'toggleOffText' => 'off',
					'input_group_class' => ( $option_name == 'search_option' ) ? 'ephd-admin__input-update_preview' : '',
				) );    ?>
			</ul>
		</div>  <?php
	}

	/**
	 * Return list of cpt Locations
	 *
	 * @param array $included_locations
	 * @param array $excluded_locations
	 * @param bool $include_all_if_empty
	 * @return array
	 */
	private function get_cpt_locations( $included_locations=[], $excluded_locations=[], $include_all_if_empty=true ) {

        $white_cpt_list = EPHD_Utilities::get_cpts_whitelist();

		$locations = array();

		$custom_post_types = EPHD_Utilities::get_post_type_labels( [], $white_cpt_list );
	    foreach ( $custom_post_types as $cpt => $cpt_title ) {

		    if ( in_array( $cpt, $excluded_locations ) ) {
                continue;
		    }

		    // if included locations is empty then include all CPTs
            if ( ! in_array( $cpt, $included_locations ) && ! $include_all_if_empty ) {
                continue;
            }

            $location = new stdClass();
            $location->ID = $cpt;
            $location->post_title = $cpt_title;
            $location->post_type = $cpt;
            $location->url = '';
            $locations[] = $location;
        }

        return $locations;
	}

	/**
	 * Return list of a certain type of Locations (page, post, or cpt)
	 *
	 * @param $widget_locations_type
	 * @param $order_by
	 * @param $search_value
	 * @param $excluded_locations
	 * @param $included_locations
	 * @param bool $include_all_if_empty
	 * @param string $language
	 * @return array
	 */
	private function get_locations( $widget_locations_type, $order_by, $search_value, $excluded_locations, $included_locations, $include_all_if_empty=true, $language = 'all' ) {

		// for CPT we just return list of CPT names but do not need post ids or titles
        if ( $widget_locations_type == 'cpt' ) {
            return $this->get_cpt_locations( $included_locations, $excluded_locations, $include_all_if_empty );
        }

		if ( ! in_array( $order_by, self::ORDER_LOCATIONS_BY ) ) {
			$order_by = self::ORDER_LOCATIONS_BY[0];
		}

		// if home page is not an actual page then include it as the first list entry by default only for:
		// - the first page if search_value is empty
		// - or if default Home Page title contains the search_value
		$home_page_title = __( 'Home Page', 'help-dialog' );
		$page_on_front = get_option( 'page_on_front' );
		$static_home_page_title = empty( $page_on_front ) ? '' : ' (' . get_the_title( $page_on_front ) . ')';

		/**
		 * 1. $include_all_if_empty true means search request
		 * 2. Always show for search requests depends on exclude
		 * 3. Always show when included (filled included means NOT search request)
		 */
		$home_page_available = ( $include_all_if_empty && ! in_array( (string)EPHD_Config_Specs::HOME_PAGE, $excluded_locations ) ) || in_array( EPHD_Config_Specs::HOME_PAGE, $included_locations );

		$use_empty_front_page = $widget_locations_type == 'page' && ! in_array( (string)EPHD_Config_Specs::HOME_PAGE, $excluded_locations ) && $home_page_available;
		$is_home_page_in_search = ! empty( $search_value ) && stripos( $home_page_title, $search_value ) !== false;

		$home_page = null;
		if ( $use_empty_front_page && ( $is_home_page_in_search || empty( $search_value ) ) ) {
			$home_page = new stdClass();
			$home_page->ID = EPHD_Config_Specs::HOME_PAGE;
			$home_page->post_title = $home_page_title . $static_home_page_title;
			$home_page->post_type = 'page';
		}

		global $wpdb;

		$params = array();

		// to retrieve list of Location objects
		$query_sql = "SELECT ID, post_title, post_type";

		// start assembling the SQL query
		$query_sql .= " FROM $wpdb->posts WHERE post_status IN ('publish', 'private', 'draft')";

		// excluded Location ids
		if ( ! empty( $excluded_locations ) ) {
			$params = array_merge( $params, $excluded_locations );
			$sql_id_placeholders = array_fill( 0, count( $excluded_locations ), '%d' );
			$query_sql .= " AND ID NOT IN(" . implode( ', ', $sql_id_placeholders ) . ")";
		}

		// included Location ids
		if ( ! empty( $included_locations ) ) {
			$params = array_merge( $params, $included_locations );
			$sql_id_placeholders = array_fill( 0, count( $included_locations ), '%d' );
			$query_sql .= " AND ID IN(" . implode( ', ', $sql_id_placeholders ) . ")";
		}

		// specify post types of Locations
		$params[] = $widget_locations_type;
		$query_sql .= " AND post_type = %s AND post_mime_type = ''";

		// optionally use search string
		if ( ! empty( $search_value ) ) {
			$params[] = '%' . $wpdb->esc_like( $search_value ) . '%';
			$query_sql .= " AND post_title LIKE %s";
		}

		$params[] = $order_by;
		$query_sql .= " ORDER BY %s ASC";

		// query Locations
		$locations = $wpdb->get_results( $wpdb->prepare( $query_sql, $params ) );
		if ( ! is_array( $locations ) ) {
			$locations = array();
		}

		// filter Locations by language
		if ( $language != 'all' ) {
			foreach ( $locations as $key => $post ) {
				$post_lang = EPHD_Multilang_Utilities::get_post_language( $post->ID );
				if ( $language != $post_lang ) {
					unset( $locations[$key] );
				}
			}
		}

		// add default Home Page to found Locations (ignore ordering for the default Home Page for now to simplify the logic)
		if ( ! empty( $home_page ) && ( $language == 'all' || $language == EPHD_Multilang_Utilities::get_default_language() ) ) {
			array_unshift( $locations, $home_page );
		}

		return $locations;
	}

	/**
	 * Return list of Location ids that are assigned to the currently selected Widgets
	 *
	 * @param $locations_type
	 * @param $selected_widget_ids
	 *
	 * @return array
	 */
	private function get_selected_widgets_locations( $locations_type, $selected_widget_ids ) {

		if ( in_array( 'all', $selected_widget_ids, true ) ) {
			$selected_widget_ids = $this->all_widget_ids;
		}

		$selected_widgets_location_posts = [];
		foreach ( $this->widgets_config as $widget ) {
			if ( ! in_array( $widget['widget_id'], $selected_widget_ids ) ) {
				continue;
			}
			$selected_widgets_location_posts = array_merge( $selected_widgets_location_posts, $widget['location_' . $locations_type . 's_list'] );
		}

		return $selected_widgets_location_posts;
	}

	/**
	 * Display list of selected Locations with search input
	 *
	 * @param $locations_type
	 * @param $widget_id
	 * @param $tooltip
	 * @param $cpt_tooltip_names
     * @param $kb_ad_button
	 */
	private function display_locations_field( $locations_type, $widget_id, $tooltip , $cpt_tooltip_names, $kb_ad_button=false ) {

		$locations_search_title = ''; //__( 'Search', 'help-dialog' );
		switch ( $locations_type ) {
			case 'page':
				$locations_search_title .= __( 'Add Pages', 'help-dialog' );
				$locations_search_placeholder = __( 'type to find page', 'help-dialog' );
				break;

			case 'post':
				$locations_search_title .= __( 'Add Posts', 'help-dialog' );
				$locations_search_placeholder = __( 'type to find post', 'help-dialog' );
				break;

			case 'cpt':
				$locations_search_title .= __( 'Add CPTs', 'help-dialog' );
				$locations_search_placeholder = __( 'type to find Custom Post Types', 'help-dialog' );
				break;

			default:
				$locations_search_placeholder = '';
				break;
		}   ?>

		<!-- Locations Field -->
		<div class="ephd-wp__locations-list-option">
			<div class="ephd-wp__locations-list-select ephd-wp__locations-list-select--<?php echo esc_attr( $locations_type ); ?>">
				<div class="ephd-wp__locations-list-search-title"><span>
					<?php echo esc_html( $locations_search_title ); ?></span>  <?php
					EPHD_HTML_Elements::display_tooltip( $locations_search_title, $tooltip );   ?>
				</div>
				<div class="ephd-wp__locations-list-search-body">
					<div class="ephd-wp__locations-list-input-wrap">

						<!-- Search Input -->
						<input class="ephd-wp__locations-list-input"
						       type="text"
						       value=""
						       data-post-type="<?php echo esc_attr( $locations_type ); ?>"
						       placeholder="<?php echo esc_attr( $locations_search_placeholder ); ?>">  <?php

                        // Install KB button
                        if ( ! empty( $kb_ad_button ) && ! EPHD_KB_Core_Utilities::is_kb_or_amag_enabled() ) {  ?>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=ephd-help-dialog#our-free-plugins' ) ); ?>" class="ephd-primary-btn"><?php esc_html_e( 'Install Knowledge Base', 'help-dialog' ) ?></a>   <?php
	                    }   ?>

						<!-- List of Locations -->
						<div class="ephd-wp__locations-list-wrap">
							<ul class="ephd-wp__found-locations-list" style="display:none;"></ul>   <?php
                            $this->get_widget_locations_list( $locations_type, $widget_id );     ?>
						</div>		<?php

						if ( empty( $cpt_tooltip_names ) && $locations_type == 'cpt' ) {
							echo '<div class="ephd-no-cpt-message">' . esc_html__( 'No supported Custom Post Type detected. Please contact us if yours is missing.', 'help-dialog' ) . '</div>';
						}			?>
					</div>
				</div>
			</div>
		</div>  <?php
	}

	/**
	 * Return an array of selected Locations for a Widget by post type (pages, posts, CPTs)
	 *
	 * @param $widget
	 *
	 * @return array
	 */
	private function get_widget_locations( $widget ) {

		$widget_locations = array(
            'pages' => [],
            'posts' => [],
            'cpts'  => [],
        );
		$include_locations = array_merge( $widget['location_pages_list'], $widget['location_posts_list'] );
		foreach( $include_locations as $include_location ) {

			// handle Home Page location separately
			if ( $include_location == EPHD_Config_Specs::HOME_PAGE ) {
				$home_page = new stdClass();
				$home_page->ID = EPHD_Config_Specs::HOME_PAGE;
				$home_page->post_title = __( 'Home Page', 'help-dialog' );
				$home_page->post_type = 'page';
				$home_page->url = home_url();
				$widget_locations['pages'][] = $home_page;
				continue;
			}

			$post = get_post( $include_location );
			if ( empty( $post ) || ! $post instanceof WP_Post ) {
				continue;
			}

			// add post/page
			$location = new stdClass();
			$location->ID = $post->ID;
			$location->post_title = $post->post_title;
			$location->post_type = $post->post_type;
			$location->url = get_permalink( $post->ID );
			$widget_locations[$post->post_type . 's'][] = $location;
		}

		// add cpt
		$widget_locations['cpts'] = $this->get_cpt_locations( $widget['location_cpts_list'], [], false );

		return $widget_locations;
	}

	/**
	 * Return HTML for Pages content in Pages tab of Widget form
	 *
	 * @param $widget
	 *
	 * @return false|string
	 */
	private function get_tab_content_widget_pages( $widget ) {

		ob_start();

		// hard coded all suported CPTs
		$supported_cpts_names_list = array(
			EPHD_KB_Core_Utilities::KB_POST_TYPE_PREFIX => 'Knowledge Base',
			'ip_lesson'     => 'Lesson (LearnPress)',
			'ip_quiz'       => 'Quiz (LearnPress)',
			'ip_question'   => 'Question (LearnPress)',
			'ip_course'     => 'Course (LearnPress)',
			'sfwd-lessons'  => 'Lessons (LearnDash)',
			'sfwd-quiz'     => 'Quiz (LearnDash)',
			'sfwd-topic'    => 'Topic (LearnDash)',
			'forum'         => 'Forum (bbPress)',
			'topic'         => 'Topic (bbPress)',
			'product'       => 'Product (WooCommerce)',
			'download'      => 'Download (Easy Digital Downloads)'
		);

		$cpt_tooltip_names = '';
		foreach ( EPHD_Utilities::get_cpts_whitelist() as $cpt ) {
			$cpt_name = empty( $supported_cpts_names_list[$cpt] ) ? $cpt : $supported_cpts_names_list[$cpt];
			$cpt_tooltip_names .= '<li>' . $cpt_name . '</li>';
		}

		// add dropdown to filter locations by language if multilanguage plugin is available or default (to simplify logic)
		$multilang_plugin = EPHD_Multilang_Utilities::get_multilang_plugin_name();
		if ( empty( $multilang_plugin ) ) { ?>
			<input type="hidden" name="location_language_filtering" value="<?php echo esc_attr( $this->widget_specs['location_language_filtering']['default'] ); ?>"/>  <?php
		} else {
			$language_options = EPHD_Multilang_Utilities::get_language_options();
			EPHD_HTML_Elements::dropdown( [
				'name'              => 'location_language_filtering',
				'input_group_class' => 'ephd-dropdown-group-container',
				'label_class'       => 'ephd-main_label',
				'value'             => $widget['location_language_filtering'],
				'label'             => $this->widget_specs['location_language_filtering']['label'],
				'options'           => array_merge_recursive( [ 'all' => __( 'All Languages', 'help-dialog' ) ], $language_options ),
			] );
		}

		// select type of the page filtering: include/exclude
		EPHD_HTML_Elements::radio_buttons_horizontal( [
			'value'             => $widget['location_page_filtering'],
			'specs'             => 'location_page_filtering',
			'tooltip_body'      => __( 'Select how the fields below will be used.', 'help-dialog' ),
		] );

		// selected Locations and search inputs
		$this->display_locations_field( 'page', $widget['widget_id'], __( 'Select pages to display the Widget on', 'help-dialog' ), $cpt_tooltip_names );
		$this->display_locations_field( 'post', $widget['widget_id'], __( 'Select posts to display the Widget on', 'help-dialog'  ), $cpt_tooltip_names );

		// show CPTs of supported types if any
		$cpt_tooltip_desc = __( 'Select posts in Custom Post Types to display the Widget on. List of supported Custom Post Types:', 'help-dialog' ) . '<br/>';
		$cpt_tooltip_desc .= '	<ul>
									<li>LearnPress</li>
									<li>LearnDash</li>
									<li>bbPress</li>
									<li>WooCommerce</li>
									<li>Easy Digital Downloads</li>
								</ul>';
		$cpt_tooltip_desc .= __( 'If we are missing a Custom Post Type, please contact us.', 'help-dialog' );

		$this->display_locations_field( 'cpt', $widget['widget_id'], $cpt_tooltip_desc, $cpt_tooltip_names, true );

		return ob_get_clean();
	}

	/**
	 * Return HTML for Search content in Search tab of Widget form
	 *
	 * @param $widget
	 *
	 * @return false|string
	 */
	private function get_tab_content_widget_search( $widget ) {

		ob_start();

		// Search Input
		$this->display_widget_feature_option_toggle_field( $widget, 'search_option', __( 'Turn this option ON to enable search in the Widget', 'help-dialog' ) );

		// Search: Posts
		$this->display_widget_feature_option_toggle_field( $widget, 'search_posts', __( 'Turn this option ON to enable search Posts in the Widget', 'help-dialog' ) );

		// Search: Knowledge Base
        if ( EPHD_KB_Core_Utilities::is_kb_or_amag_enabled() ) {
	        $this->display_widget_feature_option_select_field( $widget, $this->get_kb_cpt_options(), 'search_kb',
		        __( 'Do not search', 'help-dialog' ), __( 'Select Knowledge Base to display the Widget', 'help-dialog' ) );
        } else {    ?>
            <div class="ephd-wp__feature-option-field">
                <span><?php echo esc_html( $this->widget_specs['search_kb']['label'] ); ?></span>  <?php
	            EPHD_HTML_Elements::display_tooltip( $this->widget_specs['search_kb']['label'], __( 'Select Knowledge Base to display the Widget', 'help-dialog' ) ); ?>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=ephd-help-dialog#our-free-plugins' ) ); ?>" class="ephd-primary-btn"><?php esc_html_e( 'Install Knowledge Base', 'help-dialog' ) ?></a>
            </div>  <?php
        }

		return ob_get_clean();
	}

	/**
	 * Return HTML for FAQs content in FAQs tab of Widget form
	 *
	 * @param $widget
	 * @param $all_faqs
     * @param $return_html
	 *
	 * @return false|string|void
	 */
    public static function get_tab_content_widget_faqs( $widget, $all_faqs, $return_html=false ) {

        if ( ! empty( $return_html ) ) {
	        ob_start();
        }   ?>

        <!-- All Questions -->
        <div class="ephd-all-questions-container">

            <div class="ephd-all-questions__body-container">

                <div class="ephd-body__top-section">

                    <div class="ephd__top-section__filter">
                        <label class="ephd_all_articles_filter__label" for="ephd_all_articles_filter"><?php esc_html_e( 'Search by Title', 'help-dialog' ); ?></label>
                        <input class="ephd_all_articles_filter__input" id="ephd_all_articles_filter" type="text">
                    </div>
                    <div class="ephd__top-section__link">
                        <a href="#" id="ephd-fp__add_new_question" class="ephd-primary-btn"><?php esc_html_e( 'Create a Question', 'help-dialog' ); ?></a>
                    </div>

                </div>

				<ul class="ephd-all-questions-list-container"> <?php
                    foreach ( $all_faqs as $faq ) {
	                    EPHD_FAQs_Page::display_single_faq( array(
							'container_ID'  => $faq->faq_id,
							'name'          => $faq->question,
							'modified'      => strtotime( $faq->date_modified ),
							'disabled'      => in_array( $faq->faq_id, $widget['faqs_sequence'] ),
                            'direction'     => 'right'
						) );
                    }   ?>
                </ul>   <?php

                $faqs_sequence = array();
	            foreach ( $widget['faqs_sequence'] as $article_id ) {
		            foreach ( $all_faqs as $faq ) {
			            if ( $article_id == $faq->faq_id ) {
				            $faqs_sequence[] = $faq->faq_id;
			            }
		            }
	            }   ?>
                <input type="hidden" name="faqs_sequence" class="ephd-fp__selected-questions" value="<?php echo implode( ',', $faqs_sequence ); ?>">
                <div class="ephd-faq-question__buttons_template">
                    <div class="ephd-faq-question ephd-faq-question__buttons" data-id="">
                        <div class="ephd-faq-button-control ephd-faq-question__edit ephdfa ephdfa-pencil-square" title="<?php esc_attr_e( 'Edit Question', 'help-dialog' ); ?>"></div>
                        <div class="ephd-faq-button-control ephd-faq-question__move_right ephdfa ephdfa-times" title="<?php esc_attr_e( 'Remove from Widget', 'help-dialog' ); ?>"></div>
                    </div>
                </div>
            </div>  <?php

			if ( empty( $all_faqs ) ) {
				EPHD_HTML_Forms::notification_box_middle( array(
					'id'   => 'ephd-admin__no-question-message',
					'type' => 'success-no-icon',
					'desc' => __( 'You have not created any questions yet.', 'help-dialog' ),
				) );
			} else {
				EPHD_HTML_Forms::notification_box_middle( array(
					'id'   => 'ephd-admin__assigned-question-message',
					'type' => 'success-no-icon',
					'desc' => __( 'All questions have been assigned.', 'help-dialog' ),
				) );
			}           ?>

        </div>  <?php

	    if ( ! empty( $return_html ) ) {
		    return ob_get_clean();
	    }
    }

	/**
	 * Return HTML for Predefined Colors content in Colors tab of Widget form
	 *
	 * @param $widget
	 *
	 * @return false|string
	 */
	private function get_tab_content_predefined_colors( $widget ) {

		ob_start();     ?>

		<!-- Predefined Colors -->
		<div class="ephd-wp__options-container ephd-wp__options-container--predefined-colors ephd-admin__input-update_preview">
			<div class="ephd-wp__options-two-cols-wrap">    <?php

				$color_sets = EPHD_Premade_Designs::get_color_sets();
				foreach ( $color_sets as $id => $colors_set ) {
					$currently_active = ( $id == 'default' && empty( $this->widgets_config ) );
					$font_main_color = empty( $colors_set['config']['main_title_text_color'] ) ? $widget['main_title_text_color'] : $colors_set['config']['main_title_text_color'];
					$background_main_color = empty( $colors_set['config']['background_color'] ) ? $widget['background_color'] : $colors_set['config']['background_color'];    ?>

					<div class="ephd-wp__options-container__option">
						<label class="ephd-wp__option__label" style="background-color:<?php echo esc_attr( $background_main_color ); ?>;color:<?php echo esc_attr( $font_main_color ); ?>;">
							<input type="radio" name="colors_set" value="<?php echo $id; ?>"<?php checked( true, $currently_active ); ?> data-choose="<?php esc_attr_e( 'Choose', 'help-dialog' ); ?>">
						</label>
					</div>  <?php
				}   ?>

			</div>
		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return HTML for Widgets Global Widget Settings in Global Settings tab of Widget form
     *
     * @param $widget
     *
	 * @return false|string
	 */
    private function get_tab_content_global_widget_settings( $widget ) {
	    ob_start(); ?>
        <!-- Global Widget Settings Form -->
        <div class="ephd-wp__options-container ephd-wp__options-container--global-settings">    <?php
		    EPHD_HTML_Elements::text( ['value' => $widget['widget_name'], 'specs' => 'widget_name'] );  ?>
        </div>  <?php

	    return ob_get_clean();
    }

	/**
	 * Return HTML for Delete Widgets Box
	 *
	 * @param $widget
	 *
	 * @return false|string
	 */
	private function get_tab_content_delete_widget( $widget ) {
		ob_start(); ?>
        <!-- Global Widget Settings Form -->
        <div class="ephd-wp__options-container ephd-wp__options-container--global-settings">    <?php
	        EPHD_HTML_Elements::submit_button_v2( __( 'Delete', 'help-dialog' ), 'ephd_delete_widget', 'ephd-wp__delete-widget-wrap', '', false, '', 'ephd-error-btn' );    ?>
        </div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return HTML for Widgets Article/Post preview Settings in Global Settings tab of Widget form
	 *
	 * @return false|string
	 */
	private function get_tab_content_article_preview_settings() {

		ob_start(); ?>
		<!-- Article/Post preview Settings Form -->
		<div class="ephd-wp__options-container ephd-wp__options-container--preview-settings">   <?php
			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'value'             => $this->global_config['preview_post_mode'],
				'specs'             => 'preview_post_mode',
				'tooltip_body'      => __( 'Search results can list matching articles. Choose how the article preview is displayed within the Help Dialog widget.', 'help-dialog' ),
			] );

			if ( EPHD_KB_Core_Utilities::is_kb_or_amag_enabled() ) {
				EPHD_HTML_Elements::radio_buttons_horizontal( [
					'value'             => $this->global_config['preview_kb_mode'],
					'specs'             => 'preview_kb_mode',
					'tooltip_body'      => __( 'Search results can list matching articles. Choose how the article preview is displayed within the Help Dialog widget.', 'help-dialog' ),
				] );
			} ?>
		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return HTML for Global Launcher Settings in Settings tab of Widget form
	 *
     * @param $widget
     *
	 * @return false|string
	 */
	private function get_tab_content_global_launcher_settings( $widget ) {

		ob_start(); ?>
        <!-- Global Launcher Settings Form -->
        <div class="ephd-wp__options-container ephd-wp__options-container--global-settings">   <?php
			EPHD_HTML_Elements::radio_buttons_horizontal( [
                    'value'             => $widget['launcher_mode'],
                    'specs'             => 'launcher_mode',
				    'input_group_class' => 'ephd-radio-horizontal-button-group-container ephd-admin__input-update_preview',
                    'tooltip_body'      => __( 'The Help Dialog opens when a user clicks on the launch icon. Choose to show an icon or both an icon and text.', 'help-dialog' ) . '<a href="https://www.helpdialog.com/documentation/configure-widgets/#articleTOC_6" target="_blank">' . __( 'Learn More.', 'help-dialog' ) . '</a>',
            ] );
			EPHD_HTML_Elements::radio_buttons_icon_selection( [ 'value' => $widget['launcher_icon'], 'specs' => 'launcher_icon', 'input_group_class' => 'ephd-admin__input-update_preview' ] );
	        EPHD_HTML_Elements::radio_buttons_horizontal( [
                    'value'             => $widget['launcher_location'],
                    'specs'             => 'launcher_location',
                    'input_group_class' => 'ephd-radio-horizontal-button-group-container ephd-admin__input-update_preview',
            ] );
			EPHD_HTML_Elements::text( ['value' => $widget['launcher_bottom_distance'], 'specs' => 'launcher_bottom_distance', 'input_size' => 'small'] );

	        EPHD_HTML_Elements::text( ['value' => $widget['launcher_start_wait'], 'specs' => 'launcher_start_wait', 'input_size' => 'small'] );    ?>
        </div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return HTML for Initial Message in Settings tab of Widget form
	 *
	 * @param $widget
	 * @return false|string
	 */
	private function get_tab_content_initial_message( $widget ) {

		ob_start(); ?>
		<!-- Initial Message Form -->
		<div class="ephd-wp__options-container ephd-wp__options-container--initial-message">   <?php
			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'value'             => $widget['initial_message_toggle'],
				'specs'             => 'initial_message_toggle',
				'input_group_class' => 'ephd-radio-horizontal-button-group-container ephd-admin__input-update_preview',
				'tooltip_body'      => __( 'Initial Message is displayed above the Help Dialog Launcher.', 'help-dialog' ),
			] );
			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'value'             => $widget['initial_message_mode'],
				'specs'             => 'initial_message_mode',
				'tooltip_body'      => __( 'Choose if to display an icon near the initial message', 'help-dialog' ),
				'input_group_class' => 'ephd-admin__input-update_preview'
			] );
			EPHD_HTML_Elements::textarea( ['value' => $widget['initial_message_text'], 'specs' => 'initial_message_text', 'input_group_class' => 'ephd-admin__input-update_preview'   ] );
			EPHD_HTML_Elements::text( ['value' => $widget['initial_message_image_url'], 'specs' => 'initial_message_image_url', 'input_group_class' => 'ephd-admin__input-update_preview' ] );   ?>
			<input type="hidden" name="initial_message_id" value="<?php echo esc_attr( $widget['initial_message_id'] ); ?>">
		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return HTML for Global Dialog Settings in Settings tab of Widget form
	 *
	 * @return false|string
	 */
	private function get_tab_content_global_dialog_settings( $widget ) {

		ob_start(); ?>
		<!-- Global Dialog Settings Form -->
		<div class="ephd-wp__options-container ephd-wp__options-container--global-settings">    <?php
            EPHD_HTML_Elements::text( ['value' => $this->global_config['logo_image_url'], 'specs' => 'logo_image_url', 'input_group_class' => 'ephd-admin__input-update_preview' ] );

			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'specs'             => 'dialog_width',
				'value'             => $this->global_config['dialog_width'],
				'input_group_class' => 'ephd-admin__input-update_preview'
			] );
			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'value'             => $widget['launcher_powered_by'],
				'specs'             => 'launcher_powered_by',
				'tooltip_body'      => __( 'Powered By promotion to help others discover Help Dialog.', 'help-dialog' ),
				'input_group_class' => 'ephd-admin__input-update_preview'
			] );
            EPHD_HTML_Elements::text( [
                'value'             => $this->global_config['mobile_break_point'],
	            'specs'             => 'mobile_break_point', 'input_size' => 'small',
	            'tooltip_body'      => __( 'This value is the screen size. When the screen becomes this small, the Dialog will become the full width of the page, making it more usable on small screens.', 'help-dialog' )
            ] );
			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'value'             => $this->global_config['tabs_sequence'],
				'specs'             => 'tabs_sequence',
				'input_group_class' => 'ephd-admin__input-update_preview'
			] );  ?>
		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return HTML for Top Text Settings in Labels tab
	 *
	 * @param $widget
	 * @return false|string
	 */
	private function get_tab_content_labels_launcher( $widget ) {
		ob_start(); ?>

		<!-- Top Text Labels Settings Form -->
		<div class="ephd-wp__options-container ephd-wp__options-container--labels ephd-admin__input-update_preview">    <?php
			EPHD_HTML_Elements::text( ['value' => $widget['launcher_text'], 'specs' => 'launcher_text'] ); ?>
		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return HTML for FAQ List Settings in Labels tab
	 *
	 * @param $widget
	 * @return false|string
	 */
	private function get_tab_content_labels_faqs( $widget ) {
		ob_start(); ?>

        <!-- FAQ List Labels Settings Form -->
        <div class="ephd-wp__options-container ephd-wp__options-container--labels ephd-admin__input-update_preview">    <?php
            EPHD_HTML_Elements::text( ['value' => $widget['faqs_top_tab'], 'specs' => 'faqs_top_tab'] );
	        EPHD_HTML_Elements::text( ['value' => $widget['welcome_title'], 'specs' => 'welcome_title', 'tooltip_body' => __( 'Allowed HTML tags: a, strong, i, b.', 'help-dialog' ),] );
	        EPHD_HTML_Elements::textarea( ['value' => $widget['welcome_text'], 'specs' => 'welcome_text', 'tooltip_body' => __( 'Allowed HTML tags: a, strong, i, b.', 'help-dialog' ),] );
			EPHD_HTML_Elements::text( ['value' => $widget['search_input_placeholder'], 'specs' => 'search_input_placeholder'] );
			EPHD_HTML_Elements::text( ['value' => $widget['article_read_more_text'], 'specs' => 'article_read_more_text'] );    ?>
        </div>  <?php

		return ob_get_clean();
    }

	/**
	 * Return HTML for Search Results Settings in Labels tab
	 *
	 * @param $widget
	 * @return false|string
	 */
    private function get_tab_content_labels_search( $widget ) {
	    ob_start(); ?>

        <!-- Search Results Labels Settings Form -->
        <div class="ephd-wp__options-container ephd-wp__options-container--labels">    <?php
		    EPHD_HTML_Elements::text( ['value' => $widget[
                    'search_results_title'], 'specs' => 'search_results_title',
			        'input_group_class' => 'ephd-input-group--separator-after',
            ] );
            EPHD_HTML_Elements::text( ['value' => $widget['breadcrumb_home_text'], 'specs' => 'breadcrumb_home_text'] );
            EPHD_HTML_Elements::text( ['value' => $widget['breadcrumb_search_result_text'], 'specs' => 'breadcrumb_search_result_text'] );
            EPHD_HTML_Elements::text( ['value' => $widget[
                    'breadcrumb_article_text'],
                    'specs' => 'breadcrumb_article_text',
	                'input_group_class' => 'ephd-input-group--separator-after',
            ] );
            EPHD_HTML_Elements::text( ['value' => $widget['found_faqs_tab_text'], 'specs' => 'found_faqs_tab_text'] );
            EPHD_HTML_Elements::text( ['value' => $widget['found_articles_tab_text'], 'specs' => 'found_articles_tab_text'] );
            EPHD_HTML_Elements::text( ['value' => $widget[
                    'found_posts_tab_text'], 'specs' => 'found_posts_tab_text',
	                'input_group_class' => 'ephd-input-group--separator-after',
            ] );
            EPHD_HTML_Elements::text( ['value' => $widget['no_results_found_title_text'], 'specs' => 'no_results_found_title_text'] );
            EPHD_HTML_Elements::text( ['value' => $widget['protected_article_placeholder_text'], 'specs' => 'protected_article_placeholder_text'] );
	        EPHD_HTML_Elements::text( ['value' => $widget['search_input_label'], 'specs' => 'search_input_label'] );
	        EPHD_HTML_Elements::text( ['value' => $widget['search_instruction_text'], 'specs' => 'search_instruction_text'] );
			EPHD_HTML_Elements::text( ['value' => $widget['article_back_button_text'], 'specs' => 'article_back_button_text'] );  ?>


            <div class="ephd-input-group ephd-admin__text-field ">
			    <label><?php echo esc_html( $this->widget_specs['no_results_found_content_html']['label'] ); ?></label>
			    <div class="input_container ">
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=ephd-help-dialog-widgets&ephd_fe_option=no-results-found-content-html&widget_id=' ) . $widget['widget_id'] ) ?>" target="_blank">
                        <?php echo __( 'Edit', 'help-dialog' ); ?>
                    </a>
			    </div>
		    </div>
        </div>  <?php

	    return ob_get_clean();
    }

	/**
	 * Return HTML for Contact Form Settings in Labels tab
	 *
	 * @param $widget
	 * @return false|string
	 */
    private function get_tab_content_labels_contact( $widget ) {
	    ob_start(); ?>

        <!-- Contact Form Labels Settings Form -->
        <div class="ephd-wp__options-container ephd-wp__options-container--labels ephd-admin__input-update_preview">    <?php
	        EPHD_HTML_Elements::text( ['value' => $widget['contact_us_top_tab'], 'specs' => 'contact_us_top_tab'] );
            EPHD_HTML_Elements::text( ['value' => $widget['contact_welcome_title'], 'specs' => 'contact_welcome_title', 'tooltip_body' => __( 'Allowed HTML tags: a, strong, i, b.', 'help-dialog' ),] );
	        EPHD_HTML_Elements::textarea( ['value' => $widget['contact_welcome_text'], 'specs' => 'contact_welcome_text', 'tooltip_body' => __( 'Allowed HTML tags: a, strong, i, b.', 'help-dialog' ),] );
            EPHD_HTML_Elements::text( ['value' => $widget['contact_name_text'], 'specs' => 'contact_name_text'] );
            EPHD_HTML_Elements::text( ['value' => $widget['contact_user_email_text'], 'specs' => 'contact_user_email_text'] );
            EPHD_HTML_Elements::text( ['value' => $widget['contact_subject_text'], 'specs' => 'contact_subject_text'] );
	        EPHD_HTML_Elements::text( ['value' => $widget[
                    'contact_acceptance_title'], 'specs' => 'contact_acceptance_title',
		            'input_group_class' => 'ephd-input-group--separator-before',
	        ] );
	        EPHD_HTML_Elements::textarea( [
                'value' => $widget['contact_acceptance_text'],
		        'specs' => 'contact_acceptance_text',
				'tooltip_body' => __( 'Allowed HTML tags: a, strong, i, b.', 'help-dialog' ),
		        'input_group_class' => 'ephd-input-group--separator-after',
			] );
            EPHD_HTML_Elements::text( ['value' => $widget['contact_comment_text'], 'specs' => 'contact_comment_text'] );
            EPHD_HTML_Elements::text( ['value' => $widget['contact_button_title'], 'specs' => 'contact_button_title'] );
            EPHD_HTML_Elements::textarea( ['value' => $widget['contact_success_message'], 'specs' => 'contact_success_message'] );  ?>
        </div>  <?php

	    return ob_get_clean();
    }

	/**
	 * Return HTML for Contact Form Settings in Labels tab
	 *
	 * @return false|string
	 */
	private function get_tab_content_labels_chat( $widget ) {
		ob_start(); ?>

		<!-- Contact Form Labels Settings Form -->
		<div class="ephd-wp__options-container ephd-wp__options-container--labels ephd-admin__input-update_preview">    <?php
			EPHD_HTML_Elements::text( ['value' => $widget['channel_header_top_tab'], 'specs' => 'channel_header_top_tab'] );
			EPHD_HTML_Elements::text( ['value' => $widget['channel_header_title'], 'specs' => 'channel_header_title','tooltip_body' => __( 'Allowed HTML tags: a, strong, i, b.', 'help-dialog' ),] );
			EPHD_HTML_Elements::textarea( ['value' => $widget['channel_header_sub_title'], 'specs' => 'channel_header_sub_title','tooltip_body' => __( 'Allowed HTML tags: a, strong, i, b.', 'help-dialog' ),] );
			EPHD_HTML_Elements::textarea( ['value' => $widget['chat_welcome_text'], 'specs' => 'chat_welcome_text'] );
			EPHD_HTML_Elements::text( ['value' => $widget['channel_phone_label'], 'specs' => 'channel_phone_label'] );
			EPHD_HTML_Elements::text( ['value' => $widget['channel_whatsapp_label'], 'specs' => 'channel_whatsapp_label'] );
			EPHD_HTML_Elements::text( ['value' => $widget['channel_whatsapp_welcome_message'], 'specs' => 'channel_whatsapp_welcome_message'] );
			EPHD_HTML_Elements::text( ['value' => $widget['channel_custom_link_label'], 'specs' => 'channel_custom_link_label'] );  ?>
		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return HTML for Other Settings in Labels tab
	 *
	 * @return false|string
	 */
	private function get_tab_content_labels_other( $widget ) {
		ob_start(); ?>

		<!-- Other Labels Settings Form -->
		<div class="ephd-wp__options-container ephd-wp__options-container--labels ephd-admin__input-update_preview">    <?php
			EPHD_HTML_Elements::text( ['value' => $widget['no_result_contact_us_text'], 'specs' => 'no_result_contact_us_text'] );			?>
		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return HTML for Launcher Color settings
	 *
	 * @param $widget
	 * @return false|string
	 */
    private function get_tab_content_colors_launcher( $widget ) {
	    ob_start(); ?>

        <!-- Launcher Color Settings Form -->
        <div class="ephd-wp__options-container ephd-wp__options-container--labels">    <?php
	        EPHD_HTML_Elements::color( ['value' => $widget['launcher_background_color'], 'specs' => 'launcher_background_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['launcher_background_hover_color'], 'specs' => 'launcher_background_hover_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['launcher_icon_color'], 'specs' => 'launcher_icon_color'] );
            EPHD_HTML_Elements::color( ['value' => $widget['launcher_icon_hover_color'], 'specs' => 'launcher_icon_hover_color'] );  ?>
        </div>  <?php

	    return ob_get_clean();
    }

	/**
	 * Return HTML for Help Dialog Window Color settings
	 *
	 * @param $widget
	 * @return false|string
	 */
    private function get_tab_content_colors_hd_window( $widget ) {
	    ob_start(); ?>

        <!-- Help Dialog Window Color Settings Form -->
        <div class="ephd-wp__options-container ephd-wp__options-container--labels">    <?php
	        EPHD_HTML_Elements::color( ['value' => $widget['background_color'], 'specs' => 'background_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['not_active_tab_color'], 'specs' => 'not_active_tab_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['tab_text_color'], 'specs' => 'tab_text_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['main_title_text_color'], 'specs' => 'main_title_text_color'] );
            EPHD_HTML_Elements::color( ['value' => $widget['welcome_title_color'], 'specs' => 'welcome_title_color'] );
            EPHD_HTML_Elements::color( ['value' => $widget['welcome_title_link_color'], 'specs' => 'welcome_title_link_color'] ); ?>
        </div>  <?php

	    return ob_get_clean();
    }

	/**
	 * Return HTML for Search Results Color settings
	 *
	 * @param $widget
	 * @return false|string
	 */
    private function get_tab_content_colors_search( $widget ) {
	    ob_start(); ?>

        <!-- Search Results Color Settings Form -->
        <div class="ephd-wp__options-container ephd-wp__options-container--labels">    <?php
	        EPHD_HTML_Elements::color( ['value' => $widget['found_faqs_article_active_tab_color'], 'specs' => 'found_faqs_article_active_tab_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['found_faqs_article_tab_color'], 'specs' => 'found_faqs_article_tab_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['article_post_list_title_color'], 'specs' => 'article_post_list_title_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['article_post_list_icon_color'], 'specs' => 'article_post_list_icon_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['breadcrumb_color'], 'specs' => 'breadcrumb_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['breadcrumb_background_color'], 'specs' => 'breadcrumb_background_color'] );
            EPHD_HTML_Elements::color( ['value' => $widget['breadcrumb_arrow_color'], 'specs' => 'breadcrumb_arrow_color'] );  ?>
        </div>  <?php

	    return ob_get_clean();
    }

	/**
	 * Return HTML for FAQ Questions Color settings
	 *
	 * @param $widget
	 * @return false|string
	 */
    private function get_tab_content_colors_faqs( $widget ) {
	    ob_start(); ?>

        <!-- FAQ Questions Color Settings Form -->
        <div class="ephd-wp__options-container ephd-wp__options-container--labels">    <?php
	        EPHD_HTML_Elements::color( ['value' => $widget['faqs_qa_border_color'], 'specs' => 'faqs_qa_border_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['faqs_question_text_color'], 'specs' => 'faqs_question_text_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['faqs_question_background_color'], 'specs' => 'faqs_question_background_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['faqs_question_active_text_color'], 'specs' => 'faqs_question_active_text_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['faqs_question_active_background_color'], 'specs' => 'faqs_question_active_background_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['faqs_answer_text_color'], 'specs' => 'faqs_answer_text_color'] );
            EPHD_HTML_Elements::color( ['value' => $widget['faqs_answer_background_color'], 'specs' => 'faqs_answer_background_color'] );  ?>
        </div>  <?php

	    return ob_get_clean();
    }

	/**
	 * Return HTML for Single Article Color settings
	 *
	 * @param $widget
	 * @return false|string
	 */
    private function get_tab_content_colors_article( $widget ) {
	    ob_start(); ?>

        <!-- Single Article Color Settings Form -->
        <div class="ephd-wp__options-container ephd-wp__options-container--labels">    <?php
	        EPHD_HTML_Elements::color( ['value' => $widget['single_article_read_more_text_color'], 'specs' => 'single_article_read_more_text_color'] );
            EPHD_HTML_Elements::color( ['value' => $widget['single_article_read_more_text_hover_color'], 'specs' => 'single_article_read_more_text_hover_color'] );  ?>
        </div>  <?php

	    return ob_get_clean();
    }

	/**
	 * Return HTML for Back Button Color settings
	 *
	 * @param $widget
	 * @return false|string
	 */
    private function get_tab_content_colors_back_button( $widget ) {
	    ob_start(); ?>

        <!-- Back Button Color Settings Form -->
        <div class="ephd-wp__options-container ephd-wp__options-container--labels">    <?php
	        EPHD_HTML_Elements::color( ['value' => $widget['back_text_color'], 'specs' => 'back_text_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['back_text_color_hover_color'], 'specs' => 'back_text_color_hover_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['back_background_color'], 'specs' => 'back_background_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['back_background_color_hover_color'], 'specs' => 'back_background_color_hover_color'] );  ?>
        </div>  <?php

	    return ob_get_clean();
    }

	/**
	 * Return HTML for Contact Us Submit Button Color settings
	 *
	 * @param $widget
	 * @return false|string
	 */
    private function get_tab_content_colors_contact( $widget ) {
	    ob_start(); ?>

        <!-- Contact Us Submit Button Color Settings Form -->
        <div class="ephd-wp__options-container ephd-wp__options-container--labels">    <?php
	        EPHD_HTML_Elements::color( ['value' => $widget['contact_submit_button_color'], 'specs' => 'contact_submit_button_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['contact_submit_button_hover_color'], 'specs' => 'contact_submit_button_hover_color'] );
	        EPHD_HTML_Elements::color( ['value' => $widget['contact_submit_button_text_color'], 'specs' => 'contact_submit_button_text_color'] );
		    EPHD_HTML_Elements::color( ['value' => $widget['contact_submit_button_text_hover_color'], 'specs' => 'contact_submit_button_text_hover_color'] );  ?>
        </div>  <?php

	    return ob_get_clean();
    }

	/**
	 * Return HTML for Acceptance Checkbox Color settings
	 *
	 * @param $widget
	 * @return false|string
	 */
    private function get_tab_content_colors_contact_acceptance( $widget ) {
	    ob_start(); ?>

	    <!-- Contact Us Acceptance Checkbox Color Settings Form -->
	    <div class="ephd-wp__options-container ephd-wp__options-container--labels">    <?php
		    EPHD_HTML_Elements::color( ['value' => $widget['contact_acceptance_background_color'], 'specs' => 'contact_acceptance_background_color'] );  ?>
	    </div>  <?php

	    return ob_get_clean();
    }

	/**
	 * Return HTML for Chat Color settings
	 *
	 * @param $widget
	 * @return false|string
	 */
	private function get_tab_content_colors_chat( $widget ) {
		ob_start(); ?>

		<!-- Chat Color Settings Form -->
		<div class="ephd-wp__options-container ephd-wp__options-container--labels">    <?php
			EPHD_HTML_Elements::color( ['value' => $widget['channel_phone_color'], 'specs' => 'channel_phone_color'] );
			EPHD_HTML_Elements::color( ['value' => $widget['channel_phone_hover_color'], 'specs' => 'channel_phone_hover_color'] );

			EPHD_HTML_Elements::color( ['value' => $widget['channel_whatsapp_color'], 'specs' => 'channel_whatsapp_color'] );
			EPHD_HTML_Elements::color( ['value' => $widget['channel_whatsapp_hover_color'], 'specs' => 'channel_whatsapp_hover_color'] );

			EPHD_HTML_Elements::color( ['value' => $widget['channel_link_color'], 'specs' => 'channel_link_color'] );
			EPHD_HTML_Elements::color( ['value' => $widget['channel_link_hover_color'], 'specs' => 'channel_link_hover_color'] );
			EPHD_HTML_Elements::color( ['value' => $widget['channel_label_color'], 'specs' => 'channel_label_color'] );  ?>
		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return HTML for Copy Design From content in Copy tab of Widget form
	 *
	 * @param $widget
	 *
	 * @return false|string
	 */
	private function get_tab_content_copy_design_form( $widget ) {
		ob_start();     ?>

		<!-- Copy Design From -->
		<div class="ephd-wp__options-container ephd-wp__options-container--copy-design-from">
			<div class="ephd-wp__options-container__option">
				<select name="saved_preset_id" autocomplete="off" data-value="0">
					<option value="<?php echo esc_attr( $widget['design_id'] ); ?>" selected>==== <?php esc_html_e( 'Current Font and Size', 'help-dialog' ); ?> ====</option>  <?php
					foreach ( $this->widgets_config as $one_widget ) {
						if ( $one_widget['widget_id'] == $widget['widget_id'] ) {
							continue;
						}   ?>
						<option value="<?php echo esc_attr( $one_widget['design_id'] ); ?>"><?php echo esc_html( $one_widget['widget_name'] ); ?></option>   <?php
					}   ?>
				</select>
			</div>
		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return HTML for Copy Design To content in Copy tab of Widget form
	 *
	 * @param $widget
	 *
	 * @return false|string
	 */
	/** Future code
	private function get_tab_content_copy_design_to( $widget ) {
		ob_start();     ?>

		<!-- Copy Design To -->
		<div class="ephd-wp__options-container ephd-wp__options-container--copy-design-to">
			<div class="ephd-wp__options-container__option">
				<select autocomplete="off" data-value="0">
					<option value="0" selected>==== <?php esc_html_e( 'Select Widget', 'help-dialog' ); ?> ====</option>  <?php
					foreach ( $this->widgets_config as $one_widget ) {
						if ( $one_widget['widget_id'] == $widget['widget_id'] ) {
							continue;
						}   ?>
						<option value="<?php echo esc_attr( $one_widget['design_id'] ); ?>"><?php echo esc_html( $one_widget['widget_name'] ); ?></option>   <?php
					}   ?>
				</select>
			</div>
		</div>  <?php

		return ob_get_clean();
	} */

	/**
	 * Display CTA content box in Appearance tab
	 *
	 * @param $desc
	 * @param $cta_url
	 * @param $cta_text
	 * @param $css_class
	 * @param $icon_class
	 *
	 * @return false|string
	 */
	private function get_cta_content( $desc, $cta_url, $cta_text, $css_class='', $icon_class='' ) {

		ob_start();     ?>

		<div class="ephd-wp__widget-form__cta <?php echo esc_attr( $css_class ); ?>">
			<div class="ephd-wp__widget-form__cta-desc"><?php echo esc_html( $desc ); ?></div>
			<div class="ephd-wp__widget_form__cta-actions">
				<a class="ephd-primary-btn ephd-wp__widget-form__cta-link" href="<?php echo esc_url( $cta_url ) ?>" target="_blank">    <?php
					echo esc_html( $cta_text );
					if ( ! empty( $icon_class ) ) { ?>
						<i class="<?php echo esc_attr( $icon_class ); ?> ephd-wp__widget-form__cta-link-icon"></i><?php
					}   ?>
				</a>
			</div>
		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return configuration array for preview box of Widget
	 *
	 * @param $widget
	 * @param bool $return_html
	 *
	 * @return array
	 */
	public function get_config_of_widget_preview_box( $widget, $return_html=false ) {

		$locations = $this->get_widget_locations( $widget );

		$locations_labels = ( $widget['location_page_filtering'] == 'exclude' ) ? array(
			'posts' => __( 'Shown on all Posts except these Posts', 'help-dialog' ),
			'pages' => __( 'Shown on all Pages except these Pages', 'help-dialog' ),
			'cpts'  => __( 'Shown on all CPTs except these CPTs', 'help-dialog' ),
        ) : array(
			'posts' => __( 'Shown on these Posts', 'help-dialog' ),
			'pages' => __( 'Shown on these Pages', 'help-dialog' ),
			'cpts'  => __( 'Shown on these CPTs', 'help-dialog' ),
		);

		$no_locations_text = __( 'No pages assigned.', 'help-dialog' );

		$faqs_page_handler = new EPHD_FAQs_Page( $this->widgets_config );
		$faqs = $faqs_page_handler->get_faqs_questions( $widget );
		$faqs_label = __( 'Questions', 'help-dialog' );
		$no_faqs_text = __( 'No FAQs assigned.', 'help-dialog' );

		$widget_status = $widget['widget_status'] == 'draft' ? $this->widget_specs['widget_status']['options']['draft'] : 'Published';

		$preview_url = EPHD_Core_Utilities::get_first_widget_page_url( $widget );

		return array(
			'class'         => 'ephd-admin__widget-preview ephd-admin__widget-preview--' . $widget['widget_id'],
			'return_html'   => $return_html,
			'html'          => EPHD_HTML_Admin::get_widget_preview_box( $widget, array(
                'locations_list'    => $locations,
                'locations_title'   => $locations_labels,
				'no_locations_text' => $no_locations_text,
				'faqs_list'         => $faqs,
				'faqs_title'        => $faqs_label,
				'no_faqs_text'      => $no_faqs_text,
				'status'            => $widget_status,
				'preview_url'       => $preview_url ) ) );
	}

	/**
	 * Return admin box with WP editor for 'No Results Content' option
	 *
	 * @param $widget_id
	 *
	 * @return false|string
	 */
	private function get_no_results_content_box_html( $widget_id ) {

		ob_start(); ?>

		<input type="hidden" name="widget_id" value="<?php echo esc_attr( $widget_id ); ?>">

		<div class="ephd-wp__tiny-mce-input__header">
			<div class="ephd-wp__tiny-mce-input__title-wrap">
				<h4 class="ephd-wp__tiny-mce-input__title">
					<span class="ephd-wp__tiny-mce-input__title-label"><?php esc_html_e( 'The text displayed to the user if no search results are found', 'help-dialog' ); ?></span>
				</h4>
				<div class="ephd-wp__tiny-mce-input__actions">  <?php
					EPHD_HTML_Elements::submit_button_v2( __( 'Save', 'help-dialog' ), 'ephd_tiny_mce_input_save', 'ephd-wp__tiny-mce-input__save-btn', '', false, '', 'ephd-success-btn' );  ?>
				</div>
			</div>
		</div>

		<div class="ephd-wp__tiny-mce-input__body">
			<div class="ephd-wp__tiny-mce-input__desc"><?php esc_html_e( 'If a user searches for answers and no match is found, the following text will be shown to the user:', 'help-dialog' ); ?></div>   <?php

			// WP Editor
			wp_editor( $this->widgets_config[$widget_id]['no_results_found_content_html'],
				'no_results_found_content_wpeditor',
				array( 'default_editor' => 'TinyMCE',
					'media_buttons' => false,
					'textarea_name' => 'no_results_found_content_html',
					'tinymce' => array(
						'setup' => "function( ed ) {
										jQuery( '#no_results_found_content_wpeditor' ).attr( 'maxlength', '" . $this->widget_specs['no_results_found_content_html']['max'] . "' );
									    ed.on( 'keypress', function(e) { if ( ed.getContent().length > " . $this->widget_specs['no_results_found_content_html']['max'] . " ) { tinymce.dom.Event.cancel(e); } } );
									}"
					) ) );  ?>

		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Get HTML of Widget form
	 *
	 * @param $widget
	 *
	 * @return false|string
	 */
	private static function get_widget_form_actions_html( $widget ) {

		ob_start(); ?>
        <div class="ephd-wp__widget-action__save-wrap">
            <div class="ephd-wp__widget-action__save-btns-wrap">
                <button class="ephd-primary-btn ephd_cancel_widget">
					<i class="ephdfa ephdfa-chevron-left"></i>  <?php
                    esc_html_e( 'Back', 'help-dialog' );   ?>
                </button>   <?php
                    $save_button_title = ( $widget['widget_status'] == 'published' && $widget['widget_id'] > 0 ) ? __( 'Save', 'help-dialog' ) : __( 'Save and Publish', 'help-dialog' );
					EPHD_HTML_Elements::submit_button_v2( $save_button_title, 'ephd_publish_widget', 'ephd-wp__widget-action__publish-btn', '', false, '', 'ephd-success-btn' );   ?>
				<div class="ephd-success-btn ephd-wp__widget-action__save-options-toggle">
					<i class="ephdfa ephdfa-chevron-down"></i>
				</div>
            </div>
			<div class="ephd-wp__widget-action__save-btn ephd-wp__widget-action__save-options-list" style="display:none;">   <?php
                EPHD_HTML_Elements::submit_button_v2( __( 'Save as Draft', 'help-dialog' ), 'ephd_draft_widget', 'ephd-wp__widget-action__draft-btn', '', false, '', 'ephd-success-btn' );   ?>
            </div>
        </div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return HTML for FAQs settings
	 *
	 * @param $widget
	 * @return false|string
	 */
	private function get_tab_content_faqs_settings( $widget ) {

		ob_start(); ?>
		<!-- FAQs Settings Form -->
		<div class="ephd-wp__options-container ephd-wp__options-container--faqs-settings">    <?php
			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'value'             => $widget['display_faqs_tab'],
				'specs'             => 'display_faqs_tab',
				'tooltip_body'      => __( 'Enable or disable the FAQs Tab', 'help-dialog' ) . /** ' <a href="https://www.helpdialog.com/documentation/configure-widgets/#articleTOC_6" target="_blank">' . __( 'Learn More.', 'help-dialog' ) . */ '</a>',
				'input_group_class' => 'ephd-admin__input-update_preview'
			] );    ?>
		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return HTML for Contact Form settings
	 *
	 * @param $widget
	 * @return false|string
	 */
	private function get_tab_content_contact_form_settings( $widget ) {

		ob_start(); ?>
		<!-- Contact Form Settings Form -->
		<div class="ephd-wp__options-container ephd-wp__options-container--contact-form-settings">    <?php
			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'value'             => $widget['display_contact_tab'],
				'specs'             => 'display_contact_tab',
				'tooltip_body'      => __( 'Enable or disable the Contact Form Tab', 'help-dialog' ) . /** ' <a href="https://www.helpdialog.com/documentation/configure-widgets/#articleTOC_6" target="_blank">' . __( 'Learn More.', 'help-dialog' ) . */ '</a>',
				'input_group_class' => 'ephd-admin__input-update_preview'
			] );
			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'specs'         => 'contact_name_toggle',
				'value'         => $widget['contact_name_toggle'],
				'tooltip_body'  => __( 'Add name input to Contact Form', 'help-dialog' ),
				'input_group_class' => 'ephd-admin__input-update_preview'
			] );
			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'specs'         => 'contact_subject_toggle',
				'value'         => $widget['contact_subject_toggle'],
				'tooltip_body'  => __( 'Add subject input to Contact Form', 'help-dialog' ),
				'input_group_class' => 'ephd-admin__input-update_preview'
			] );
			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'value'        => $widget['contact_acceptance_checkbox'],
				'specs'        => 'contact_acceptance_checkbox',
				'tooltip_body' => __( 'Add acceptance checkbox to Contact Form', 'help-dialog' ),
				'input_group_class' => 'ephd-admin__input-update_preview'
			] );
			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'value'        => $widget['contact_acceptance_title_toggle'],
				'specs'        => 'contact_acceptance_title_toggle',
				'tooltip_body' => __( 'Add acceptance title to Contact Form', 'help-dialog' ),
				'input_group_class' => 'ephd-admin__input-update_preview'
			] );    ?>
		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return HTML for Chat settings
	 *
	 * @param $widget
	 * @return false|string
	 */
	private function get_tab_content_chat_settings( $widget ) {

		ob_start(); ?>
		<!-- Chat Settings Form -->
		<div class="ephd-wp__options-container ephd-wp__options-container--chat-settings">    <?php
			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'value'             => $widget['display_channels_tab'],
				'specs'             => 'display_channels_tab',
				'tooltip_body'      => __( 'Enable or disable the Chat Tab', 'help-dialog' ) . /** ' <a href="https://www.helpdialog.com/documentation/configure-widgets/#articleTOC_6" target="_blank">' . __( 'Learn More.', 'help-dialog' ) . */ '</a>',
                'input_group_class' => 'ephd-input-group--separator-after ephd-admin__input-update_preview',
			] );
			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'value'             => $widget['channel_phone_toggle'],
				'specs'             => 'channel_phone_toggle',
				'input_group_class' => 'ephd-admin__input-update_preview'
			] );

			EPHD_HTML_Elements::text( [
				'value'         => $widget['channel_phone_country_code'],
				'specs'         => 'channel_phone_country_code',
				'tooltip_body'  => __( 'Look up your country code:', 'help-dialog' ) . ' <a href="https://countrycode.org" target="_blank">' . __( 'Learn More.', 'help-dialog' ) . '</a>',
				'input_size'    => 'small'
			] );

			EPHD_HTML_Elements::text( [
				'value'         => $widget['channel_phone_number'],
				'specs'         => 'channel_phone_number',
				'input_size'    => 'small',
			] );

			EPHD_HTML_Elements::text( [
                'value'             => $widget['channel_phone_number_image_url'],
                'specs'             => 'channel_phone_number_image_url',
                'input_group_class' => 'ephd-admin__input-update_preview ephd-input-group--separator-after'
            ] );

			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'value'             => $widget['channel_whatsapp_toggle'],
				'specs'             => 'channel_whatsapp_toggle',
				'input_group_class' => 'ephd-admin__input-update_preview'
			] );

			EPHD_HTML_Elements::text( [
				'value'         => $widget['channel_whatsapp_phone_country_code'],
				'specs'         => 'channel_whatsapp_phone_country_code',
				'tooltip_body'  => __( 'Look up your country code:', 'help-dialog' ) . ' <a href="https://countrycode.org" target="_blank">' . __( 'Learn More.', 'help-dialog' ) . '</a>',
				'input_size'    => 'small'
			] );

			EPHD_HTML_Elements::text( [
				'value'         => $widget['channel_whatsapp_phone_number'],
				'specs'         => 'channel_whatsapp_phone_number',
				'input_size'    => 'small'
			] );

			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'value'             => $widget['channel_whatsapp_web_on_desktop'],
				'specs'             => 'channel_whatsapp_web_on_desktop',
			] );

			EPHD_HTML_Elements::text( [
				'value'             => $widget['channel_whatsapp_number_image_url'],
				'specs'             => 'channel_whatsapp_number_image_url',
				'input_group_class' => 'ephd-admin__input-update_preview ephd-input-group--separator-after'
			] );

			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'value'             => $widget['channel_custom_link_toggle'],
				'specs'             => 'channel_custom_link_toggle',
				'input_group_class' => 'ephd-admin__input-update_preview'
			] );

			EPHD_HTML_Elements::text( [
				'value'         => $widget['channel_custom_link_url'],
				'specs'         => 'channel_custom_link_url',
				'input_size'    => 'small'
			] );

			EPHD_HTML_Elements::text( [
				'value'             => $widget['channel_custom_link_image_url'],
				'specs'             => 'channel_custom_link_image_url',
				'input_group_class' => 'ephd-admin__input-update_preview'
			] );
			?>
		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return HTML for Delay Trigger settings
	 *
	 * @return false|string
	 */
	private function get_tab_content_trigger_delay( $widget ) {

		ob_start(); ?>
		<!-- Delay Trigger Settings Form -->
		<div class="ephd-wp__options-container ephd-wp__options-container--trigger-delay-settings">    <?php
			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'value'         => $widget['trigger_delay_toggle'],
				'specs'         => 'trigger_delay_toggle',
				'tooltip_body'  => __( 'Enable or disable the Delay Trigger. After the widget has appeared for the first time, it will always be visible on load.', 'help-dialog' )
			] );
			EPHD_HTML_Elements::text( [
				'value'         => $widget['trigger_delay_seconds'],
				'specs'         => 'trigger_delay_seconds',
				'input_size'    => 'small'
			] );    ?>
		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return HTML for Scroll Trigger settings
	 *
	 * @return false|string
	 */
	private function get_tab_content_trigger_scroll( $widget ) {

		ob_start(); ?>
		<!-- Scroll Trigger Settings Form -->
		<div class="ephd-wp__options-container ephd-wp__options-container--trigger-scroll-settings">    <?php
			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'value'         => $widget['trigger_scroll_toggle'],
				'specs'         => 'trigger_scroll_toggle',
				'tooltip_body'  => __( 'Enable or disable the Scroll Trigger. After the user scrolls a specified percentage of the page length, the Help Dialog widget will appear. This is useful in helping the user if they are unable to find what they need while scrolling.', 'help-dialog' )
			] );
			EPHD_HTML_Elements::text( [
				'value'         => $widget['trigger_scroll_percent'],
				'specs'         => 'trigger_scroll_percent',
				'input_size'    => 'small'
			] );    ?>
		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return HTML for Days and Hours Trigger settings
	 *
	 * @return false|string
	 */
	private function get_tab_content_trigger_days_and_hours( $widget ) {

		ob_start(); ?>
		<!-- Days and Hours Trigger Settings Form -->
		<div class="ephd-wp__options-container ephd-wp__options-container--trigger-scroll-settings">    <?php
			EPHD_HTML_Elements::radio_buttons_horizontal( [
				'value'         => $widget['trigger_days_and_hours_toggle'],
				'specs'         => 'trigger_days_and_hours_toggle',
				'tooltip_body'  => __( 'Enable or disable the Days and Hours Trigger. After the widget appeared for the first time, it will always be visible on-load - once the user is aware of the widget, the user expects it to always appear.', 'help-dialog' )
			] );
			EPHD_HTML_Elements::dropdown( [
				'value'         => $widget['trigger_days'],
				'specs'         => 'trigger_days',
			] );
			EPHD_HTML_Elements::text( [
				'value'         => $widget['trigger_hours_from'],
				'specs'         => 'trigger_hours_from',
				'input_size'    => 'small'
			] );
			EPHD_HTML_Elements::text( [
				'value'         => $widget['trigger_hours_to'],
				'specs'         => 'trigger_hours_to',
				'input_size'    => 'small'
			] );    ?>
		</div>  <?php

		return ob_get_clean();
	}
}
