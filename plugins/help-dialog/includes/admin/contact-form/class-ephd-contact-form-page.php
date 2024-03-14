<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display Help Dialog Submissions page
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Contact_Form_Page {

	private $widgets_config;
	private $current_submissions = [];
	private $total_submissions_number = 0;
	private $messages = array(); // error/warning/success messages

	public function __construct( $widgets_config ) {
		$this->widgets_config = $widgets_config;
	}

	/**
	 * Displays the Help Dialog Submissions page with top panel
	 */
	public function display_page() {

		if ( ! current_user_can( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) ) ) {
			echo '<p>' . esc_html__( 'You do not have permission to edit Help Dialog.', 'help-dialog' ) . '</p>';
			return;
		}

		$this->get_submissions_data();

		$admin_page_views = $this->get_regular_views_config();

		EPHD_HTML_Admin::admin_page_css_missing_message( true );    ?>

		<!-- Admin Page Wrap -->
		<div id="ephd-admin-page-wrap">

			<div class="ephd-contact-form-page-container">				<?php
				/**
				 * ADMIN HEADER
				 */
				EPHD_HTML_Admin::admin_header();

				/**
				 * ADMIN TOP PANEL
				 */
				EPHD_HTML_Admin::admin_toolbar( $admin_page_views );

				/**
				 * LIST OF SETTINGS IN TABS
				 */
				EPHD_HTML_Admin::admin_settings_tab_content( $admin_page_views, 'ephd-submissions-wrapper' );

				// Confirmation pop-up to delete a Contact Form Design
				EPHD_HTML_Forms::dialog_confirm_action( array(
					'id'                => 'ephd-cf__delete-contact-form-confirmation',
					'title'             => __( 'Deleting Design', 'help-dialog' ),
					'body'              => __( 'Are you sure you want to delete the Design? You cannot undo this action.', 'help-dialog' ),
					'accept_label'      => __( 'Delete', 'help-dialog' ),
					'accept_type'       => 'warning',
					'show_cancel_btn'   => 'yes',
					'form_method'       => 'post',
				) );    ?>

			</div>
		</div>		<?php

		/**
		* Show any notifications
		*/
		foreach ( $this->messages as $class => $message ) {
			echo  EPHD_HTML_Forms::notification_box_bottom( $message, '', $class );
		}   ?>
		<div class="ephd-bottom-notice-message fadeOutDown"></div>  <?php
	}

	/**
	 * Retrieve all submissions
	 */
	private function get_submissions_data() {

		$handler = new EPHD_Submissions_DB();
		$this->current_submissions = $handler->get_submissions();
		if ( is_wp_error( $this->current_submissions ) ) {
			$this->messages['error'] = EPHD_Utilities::report_generic_error( 411, $this->current_submissions );
			$this->current_submissions = [];
		}

		$this->total_submissions_number = $handler->get_total_number_of_submissions();
	}

	/**
	 * Return configuration for Contact Form Editor tab
	 *
	 * @return array
	 */
	private function get_contact_form_editor_boxes() {

		$global_config = ephd_get_instance()->global_config_obj->get_config();

		$contact_form_boxes = [];

        // Email Settings for Submissions  Box
		$contact_form_boxes[] = array(
			'title' => __( 'Email Settings', 'help-dialog' ),
			'class' => 'ephd-cf__email-form',
			'html'  => $this->get_submissions_email_box_html( $global_config['contact_submission_email'] ),
		);

		// Email Delivery Test Box
		$contact_form_boxes[] = array(
			'title' => __( 'Email Delivery Test', 'help-dialog' ),
			'class' => 'ephd-cf__test-email-form',
			'html'  => $this->get_test_contact_form_submission_box_html( $global_config['contact_submission_email'] ),
		);

		return $contact_form_boxes;
	}

	/**
	 * Display Contact Submission Email form
	 *
	 * @param $contact_submission_email
	 *
	 * @return string
	 */
    private function get_submissions_email_box_html( $contact_submission_email ) {

	    ob_start();     ?>

        <!-- Design Form Content -->
        <div class="ephd-cf__email-form__content">
            <ul class="ephd-cf__email-fields">   <?php
	            EPHD_HTML_Elements::text( [
		            'value'        => $contact_submission_email,
		            'specs'        => 'contact_submission_email',
		            'tooltip_body' => __( 'Enter an email address in order to receive email notifications when a user submits a message through the Contact form.', 'help-dialog' ),
	            ] );    ?>
            </ul>
        </div>  <?php

	    return ob_get_clean();
    }

	/**
	 * Email Delivery Test form
     *
	 * @param $contact_submission_email
     *
	 * @return string
	 */
	private function get_test_contact_form_submission_box_html( $contact_submission_email ) {

		ob_start();     ?>

        <!-- Test Submissions Email Form -->
        <div class="ephd-cf__test-email-form">
            <div class="ephd-cf__test-email-form__desc">    <?php
                echo esc_html__( 'Test email notifications from contact form submissions.', 'help-dialog' ); ?>
            </div>
            <div class="ephd-cf__test-email-form__actions">

                <input type="hidden" name="widget_id" value="<?php echo esc_attr( $this->widgets_config[EPHD_Config_Specs::DEFAULT_ID]['widget_id'] ); ?>">
                <input type="hidden" name="widget_name" value="<?php echo esc_attr( $this->widgets_config[EPHD_Config_Specs::DEFAULT_ID]['widget_name'] ); ?>">
                <input type="hidden" name="page_id" value="9999999">
                <input type="hidden" name="page_name" value="<?php echo esc_attr__( 'Email Delivery Test Page', 'help-dialog' ); ?>">
                <input type="hidden" name="submission_email_test" value="true">

                <input type="hidden" name="email" value="<?php echo esc_attr( $contact_submission_email ); ?>">
                <input type="hidden" name="user_first_name" value="<?php echo esc_attr__( 'Test User', 'help-dialog' ); ?>">
                <input type="hidden" name="subject" value="<?php echo esc_attr__( 'Test Message from the Help Dialog Submission', 'help-dialog' ); ?>">
                <input type="hidden" name="comment" value="<?php echo esc_attr__( 'This text is a user comment from the Help Dialog submission.', 'help-dialog' ); ?>">
                <input type="hidden" name="acceptance" value="1">  <?php

                EPHD_HTML_Elements::submit_button_v2( __( 'Test Submission', 'help-dialog' ), 'ephd_test_contact_form_submission', '', '', false, '', 'ephd-primary-btn' );    ?>
            </div>
        </div>  <?php

		return ob_get_clean();
	}

	/**
	 * Show actions row for Settings tab
	 *
	 * @return false|string
	 */
	private static function settings_tab_actions_row() {

		ob_start();		?>

        <div class="ephd-admin__list-actions-row"><?php
			EPHD_HTML_Elements::submit_button_v2( __( 'Save Settings', 'help-dialog' ), 'ephd_save_contact_form_settings', '', '', false, '', 'ephd-success-btn');    ?>
        </div>      <?php

		return ob_get_clean();
	}

	/**
	 * Get configuration array for regular views of Help Dialog Submissions admin page
	 *
	 * @return array[]
	 */
	private function get_regular_views_config() {

		/**
		 * VIEW: Contact Form Setup
		 */
		$views_config[] = array(

			// Shared
			'active' => true,
			'list_key' => 'contact-form-setup',

			// Top Panel Item
			'label_text' => __( 'Email Notification', 'help-dialog' ),
			'icon_class' => 'ephdfa ephdfa-paint-brush',

			'list_top_actions_html' => self::settings_tab_actions_row(),

			// Boxes List
			'boxes_list' => $this->get_contact_form_editor_boxes(),
		);

		/**
		 * VIEW: Submissions
		 */
		$views_config[] = array(

			// Shared
			'active' => true,
			'list_key' => 'new-submissions',

			// Top Panel Item
			'label_text' => __( 'Submissions', 'help-dialog' ),
			'icon_class' => 'ephdfa ephdfa-envelope-o ephd-icon--black',

			// Boxes List
			'list_bottom_actions_html' => count( $this->current_submissions ) > 0 ? self::get_submissions_actions() : '',
			'boxes_list' => array(

				// Box: Submissions
				array(
					'class' => 'ephd-cf__submissions-list',
					'title' => __( 'Contact Us Entries', 'help-dialog' ),
					'description' => $this->get_submissions_list_description(),
					'html' => EPHD_HTML_Forms::get_html_table(
						$this->current_submissions,
						$this->total_submissions_number,
						EPHD_Submissions_DB::PRIMARY_KEY,
						EPHD_Submissions_DB::get_submission_column_fields(),
						EPHD_Submissions_DB::get_submission_row_fields(),
						EPHD_Submissions_DB::get_submission_optional_row_fields(),
						'ephd_submissions_load_more'
					),
				),
			),
		);

		/** TODO future
		 * VIEW: Archived Submissions
		 */
		/*$views_config[] = array(

			// Shared
			'list_key' => 'archived-submissions',

			// Top Panel Item
			'label_text' => __( 'Archived Submissions', 'help-dialog' ),
			'icon_class' => 'ephdfa ephdfa-archive',

			// Boxes List
			'list_top_actions_html' => '',
			'boxes_list' => [],
		);*/

		/** TODO future
		 * VIEW: Notification Rules
		 */
		/*$views_config[] = array(

			// Shared
			'list_key' => 'notifications-rules',

			// Top Panel Item
			'label_text' => __( 'Notification Rules', 'help-dialog' ),
			'icon_class' => 'ephdfa ephdfa-flag',

			// Boxes List
			'list_top_actions_html' => '',
			'boxes_list' => $this->get_notification_rules_boxes(),
		);*/

		/** TODO future
		 * VIEW: Contact Form Design
		 */
		/* $views_config[] = array(

			// Shared
			'active' => true,
			'list_key' => 'contact-form-design',

			// Top Panel Item
			'label_text' => __( 'Contact Form Design', 'help-dialog' ),
			'icon_class' => 'ephdfa ephdfa-paint-brush',

			// Boxes List
			'list_top_actions_html' => EPHD_HTML_Admin::get_welcome_message( 'img/contact-form-preview-2.jpg', __( 'Configure Contact Form', 'help-dialog' ),
							__( 'Change the contact form title and input labels.', 'help-dialog' ) ),
			'list_bottom_actions_html' => self::get_designs_bottom_actions_row(),
			'boxes_list' => $this->get_contact_form_boxes(),
		); */

		return $views_config;
	}

	/**
	 * Get actions for Submissions view
	 *
	 * @return false|string
	 */
	private static function get_submissions_actions() {

		ob_start();		?>

		<!-- Delete All Items -->
		<div class="ephd-admin__list-actions-row">    <?php

			EPHD_HTML_Elements::submit_button_v2( esc_html__( 'Clear Table', 'help-dialog' ), '', 'ephd-admin__items-list__delete-all', '', '', '', 'ephd-error-btn' );

			// Dialog box form
			EPHD_HTML_Forms::dialog_confirm_action( array(
				'id'                => 'ephd-admin__items-list__delete-all_confirmation',
				'title'             => __( 'Deleting Submissions', 'help-dialog' ),
				'body'              => __( 'Are you sure you want to delete all submissions? You cannot undo this action.', 'help-dialog' ),
				'accept_label'      => __( 'Delete', 'help-dialog' ),
				'accept_type'       => 'warning',
				'form_inputs'       => array( '<input type="hidden" name="action" value="ephd_submissions_delete_all">' ),
				'show_cancel_btn'   => 'yes',
				'form_method'       => 'post',
			) );    ?>

		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Get description for Submissions list
	 *
	 * @return false|string
	 */
	private function get_submissions_list_description() {

		ob_start();     ?>

		<span><?php esc_html_e( 'The following are user submissions from the Contact form. Total submissions found:', 'help-dialog' ); ?> </span>
		<span class="ephd-admin__items-list__totally-found"><?php echo esc_html( $this->total_submissions_number ); ?></span><?php

		return ob_get_clean();
	}





	/** FUTURE CODE */
	/**
	 * Return configuration boxes for Notification Rules tab
	 *
	 * @return array
	 *//*
	private function get_notification_rules_boxes() {

		// Box: Notification Rules
		$notification_rules_boxes[] = array(
			'class' => '',
			'html'  => $this->get_notification_rules_box_html(),
		);

		return $notification_rules_boxes;
	}
*/
	/**
	 * Return HTML for Notification Rules boxes
	 *
	 * @return false|string
	 *//*
	public function get_notification_rules_box_html() {

		ob_start();     ?>

		<div>   <?php

			/* foreach ( $this->notification_rules_config as $notification_rule ) {    ?>
				<div>FUTURE TODO</div><?php
			} */  /*?>

		</div>      <?php

		return ob_get_clean();
	}

	private static function get_designs_bottom_actions_row() {

		ob_start(); ?>

		<div class="ephd-admin__list-actions-row">  <?php
			EPHD_HTML_Elements::submit_button_v2( __( 'Create a New Design for the Contact Form', 'help-dialog' ), 'ephd_contact_form', '', '', false, false, 'ephd-success-btn ephd-cf__create-new-design-btn' ); ?>
		</div>  <?php

		return ob_get_clean();
	}


	/**
	 * Display CTA content box
	 *
	 * @param $desc
	 * @param $cta_url
	 * @param $cta_text
	 * @param $css_class
	 * @param $icon_class
	 *
	 * @return false|string
	 *//*
	private function get_cta_content( $desc, $cta_url, $cta_text, $css_class='', $icon_class='' ) {

		ob_start();     ?>

		<div class="ephd-admin__cf-form__cta <?php echo esc_attr( $css_class ); ?>">
			<div class="ephd-admin__cf-form__cta-desc"><?php echo esc_html( $desc ); ?></div>
			<div class="ephd-admin__cf_form__cta-actions">
				<a class="ephd-primary-btn ephd-admin__cf-form__cta-link" href="<?php echo esc_url( $cta_url ) ?>" target="_blank">    <?php
					echo esc_html( $cta_text );
					if ( ! empty( $icon_class ) ) { ?>
					<i class="<?php echo esc_attr( $icon_class ); ?> ephd-admin__cf-form__cta-link-icon"></i><?php
					}   ?>
				</a>
			</div>
		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return form HTML for Create/Edit Contact Form Design
	 *
	 * @param $contact_form
	 *
	 * @return false|string
	 *//*
	public function get_design_form_box_html( $contact_form ) {

		ob_start();     ?>

		<input type="hidden" name="contact_form_id" value="<?php echo esc_attr( $contact_form['contact_form_id'] ); ?>">    <?php

		EPHD_HTML_Admin::display_admin_form_header( array(
			'icon_html'     => '<span class="ephdfa ephdfa-envelope-o ephd-admin__form-title-icon"></span>',
			'title'         => $contact_form['contact_form_name'],
			'actions_html'  => self::get_contact_form_actions_html( $contact_form ),
			'title_desc'    => '',
			'desc'          => '',
		) );    ?>

		<!-- Design Form Content -->
		<div class="ephd-cf__design-form__content">
			<ul class="ephd-cf__design-fields">   <?php
				EPHD_HTML_Elements::text( ['name' => 'contact_form_name', 'label' => $this->contact_form_specs['contact_form_name']['label'], 'value' => $contact_form['contact_form_id'] == 0 ? '' : $contact_form['contact_form_name'],
										   'type' => $this->contact_form_specs['contact_form_name']['type'], 'input_group_class' => ' ephd-admin__' . $this->contact_form_specs['contact_form_name']['type'] . '-field'] );
				EPHD_HTML_Elements::text( ['specs' => 'contact_welcome_title', 'value' => $contact_form['contact_welcome_title'] ] );
				EPHD_HTML_Elements::text( ['specs' => 'contact_welcome_text', 'value' => $contact_form['contact_welcome_text'] ] );
				EPHD_HTML_Elements::text( ['specs' => 'contact_name_text', 'value' => $contact_form['contact_name_text'] ] );
				EPHD_HTML_Elements::text( ['specs' => 'contact_user_email_text', 'value' => $contact_form['contact_user_email_text'] ] );
				EPHD_HTML_Elements::text( ['specs' => 'contact_subject_text', 'value' => $contact_form['contact_subject_text'] ] );
				EPHD_HTML_Elements::text( ['specs' => 'contact_comment_text', 'value' => $contact_form['contact_comment_text'] ] );
				EPHD_HTML_Elements::text( ['specs' => 'contact_button_title', 'value' => $contact_form['contact_button_title'] ] );
				EPHD_HTML_Elements::textarea( ['specs' => 'contact_success_message', 'value' => $contact_form['contact_success_message'] ] );   ?>
			</ul>   <?php

			EPHD_HTML_Forms::notification_box_middle( array(
				'type' => 'success-no-icon',
				'desc' => __( 'The contact form is designed with a built-in protection of submissions by bots.', 'help-dialog' ),
			) );

			// show Delete button only for non-default existing Design (id can be 0 for newly created Design)
			if ( $contact_form['contact_form_id'] > EPHD_Config_Specs::DEFAULT_ID ) {
				EPHD_HTML_Elements::submit_button_v2( __( 'Delete', 'help-dialog' ), 'ephd_delete_contact_form', 'ephd-cf__delete-contact-form-wrap', '', false, '', 'ephd-error-btn' );
			}   ?>

		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return configuration for Contact Form Design tab
	 *
	 * @return array
	 *//*
	private function get_contact_form_boxes() {

		$contact_form_boxes = [];

		// existing Contact Form Designs
		foreach ( $this->contact_forms_config as $contact_form ) {
			$contact_form_boxes[] = $this->get_config_of_contact_form_preview_box( $contact_form );
		}

		// form to create/edit Contact Form Design
		$contact_form_boxes[] = array(
			'class' => 'ephd-cf__design-form',
			'html'  => $this->get_design_form_box_html( $this->contact_forms_config[EPHD_Config_Specs::DEFAULT_ID] ),
		);

		return $contact_form_boxes;
	}

	/**
	 * Return icon HTML for preview box of Contact Form Design
	 *
	 * @return false|string
	 *//*
	public static function get_contact_form_icon_html() {
		ob_start();     ?>
		<span class="ephdfa ephdfa-envelope-o ephd-admin__item-preview__title-icon"></span>     <?php
		return ob_get_clean();
	}

	/**
	 * Return configuration array for preview box of Contact Form
	 *
	 * @param $contact_form
	 * @param bool $return_html
	 * @param bool $active
	 * @param bool $limited_preview
	 *
	 * @return array
	 *//*
	public function get_config_of_contact_form_preview_box( $contact_form, $return_html=false, $active=true, $limited_preview=true ) {

		$widgets_list = self::get_widgets_list( $contact_form );
		$widgets_list_title = __( 'Used on these Widgets', 'help-dialog' ) . ':';
		$no_widgets_text = __( 'Not assigned to any Widget', 'help-dialog' );

		// do not show Widgets list if:
		// - user has only single Contact design
		// - and it is used for all existing Widgets
		if ( count( $this->contact_forms_config ) == 1 && count( $widgets_list ) == count( $this->widgets_config ) ) {
			$widgets_list = [];
			$widgets_list_title = __( 'Used on all widgets', 'help-dialog' );
			$no_widgets_text = '';
		}

		return array(
			'class'         => 'ephd-admin__item-preview ephd-admin__item-preview--' . $contact_form['contact_form_id'] . ( $active ? ' ephd-admin__item-preview--active' : '' ),
			'return_html'   => $return_html,
			'html'          => EPHD_HTML_Admin::get_item_preview_box( $contact_form, array(
				'key'                   => 'contact_form',
				'sub_items_list'        => $widgets_list,
				'sub_items_title'       => $widgets_list_title,
				'sub_item_icon'         => 'ep_font_icon_help_dialog',
				'icon_html'             => self::get_contact_form_icon_html(),
				'no_sub_items_text'     => $no_widgets_text,
				'limit_sub_items'       => $limited_preview ) ) );
	}

	/**
	 * Return list of Widgets where the current Contact Form is used on
	 *
	 * @param $contact_form
	 *
	 * @return array
	 *//*
	private function get_widgets_list( $contact_form ) {
		$widgets_list = [];
		foreach ( $this->widgets_config as $widget ) {
			if ( $widget['contact_form_id'] != $contact_form['contact_form_id'] ) {
				continue;
			}
			$widget_in_list = new stdClass();
			$widget_in_list->post_title = $widget['widget_name'] . ' ' . __( 'Widget', 'help-dialog' );
			array_push( $widgets_list, $widget_in_list );
		}
		return $widgets_list;
	}



	/**
	 * Get HTML of Contact Form Design form
	 *
	 * @param $contact_form
	 *
	 * @return false|string
	 *//*
	private static function get_contact_form_actions_html( $contact_form ) {

		ob_start();

		// Edit Existing Design
		if ( $contact_form['contact_form_id'] > 0 ) {   ?>
			<button class="ephd-primary-btn ephd_cancel_contact_form">
				<i class="ephdfa ephdfa-chevron-left"></i>  <?php
				esc_html_e( 'Back', 'help-dialog' );   ?>
			</button>   <?php
			EPHD_HTML_Elements::submit_button_v2( __( 'Save', 'help-dialog' ), 'ephd_save_contact_form', 'ephd__save_contact_form_wrap', '', false, '', 'ephd-success-btn' );

			// Create New Design
		} else {
			EPHD_HTML_Elements::submit_button_v2( __( 'Create', 'help-dialog' ), 'ephd_save_contact_form', 'ephd__save_contact_form_wrap', '', false, '', 'ephd-success-btn' );
			EPHD_HTML_Elements::submit_button_v2( __( 'Cancel', 'help-dialog' ), 'ephd_cancel_contact_form', 'ephd-cf__cancel_contact_form_wrap', '', false, '', 'ephd-error-btn' );
		}

		return ob_get_clean();
	}
*/
}
