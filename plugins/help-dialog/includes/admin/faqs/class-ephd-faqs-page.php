<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display Help Dialog FAQs page
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_FAQs_Page {

	private $message = array(); // error/warning/success messages
	private $all_faqs;
	private $widgets_config;

	public function __construct( $widgets_config ) {
		$this->widgets_config = $widgets_config;
		$faqs_db_handler = new EPHD_FAQs_DB();
		$this->all_faqs = $faqs_db_handler->get_all_faqs();
	}

	/**
	 * Displays the Help Dialog FAQs page with top panel
	 */
	public function display_page() {

		$admin_page_views = $this->get_regular_view_config();

		EPHD_HTML_Admin::admin_page_css_missing_message( true );    ?>

		<!-- Admin Page Wrap -->
		<div id="ephd-admin-page-wrap">

            <div class="ephd-faqs-articles-page-container">				<?php

				/**
				 * ADMIN HEADER
				 */
				EPHD_HTML_Admin::admin_header();

				/**
				 * ADMIN TOP PANEL
				 */
				// EPHD_HTML_Admin::admin_toolbar( $admin_page_views );

	            /**
	             * ADMIN SECONDARY TABS
	             */
	            // EPHD_HTML_Admin::admin_secondary_tabs( $admin_page_views );

				/**
				 * LIST OF SETTINGS IN TABS
				 */
				EPHD_HTML_Admin::admin_settings_tab_content( $admin_page_views, 'ephd-config-wrapper' );

	            // Widget preview
	            $this->display_widget_preview_box();

	            // FAQs editor
	            EPHD_Core_Utilities::display_wp_editor( $this->widgets_config[EPHD_Config_Specs::DEFAULT_ID]['widget_id'] );

	            // Confirmation pop-up to delete a Question
	            EPHD_Widgets_Page::delete_question_confirm__dialog( $this->widgets_config );

	            // Confirmation pop-up to delete FAQs
	            EPHD_HTML_Forms::dialog_confirm_action( array(
		            'id'                => 'ephd-fp__delete-faqs-confirmation',
		            'title'             => __( 'Deleting FAQs', 'help-dialog' ),
		            'body'              => __( 'Are you sure you want to delete the FAQs? You cannot undo this action.', 'help-dialog' ),
		            'accept_label'      => __( 'Delete', 'help-dialog' ),
		            'accept_type'       => 'warning',
		            'show_cancel_btn'   => 'yes',
		            'form_method'       => 'post',
	            ) );        ?>

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
	 * Return configuration array for FAQs view in FAQs admin page
	 *
	 * @return array
	 */
	private function get_faqs_boxes() {

		$faqs_boxes = [];

		// existing FAQs
		foreach ( $this->widgets_config as $widget ) {
			$questions = $this->get_faqs_questions( $widget );
			$faqs_boxes[] = array(
				'class' => 'ephd-admin__item-preview ephd-admin__item-preview--' . $widget['widget_id'],
				'html'  => EPHD_HTML_Admin::get_item_preview_box( $widget, array(
					'key'                   => 'widget',
					'sub_items_list'        => $questions,
					'sub_items_title'       => __( 'Questions', 'help-dialog' ),
					'icon_html'             => self::get_faqs_icon_html()
					//'bottom_items_title'    => __( 'Shown on these pages', 'help-dialog' )
			 ) ) );
		}

		// add new faq box
		/* $faqs_boxes[] = array(
			'class' => 'ephd-admin__item-preview ephd-admin__add-new-item-preview',
			'html'  => $this->get_add_new_faq_box_html( array(
                    'icon_html' => self::get_faqs_icon_html()
                )
            )
		); */

		// form to create/edit FAQs
		$faqs_boxes[] = array(
			'class' => 'ephd-fp__faqs-form',
			'html' => $this->get_faqs_form_box_html( $this->widgets_config[EPHD_Config_Specs::DEFAULT_ID] ),
		);

		return $faqs_boxes;
	}

	/**
	 * Add new faq box
	 *
	 * @param array $args
	 *
	 * @return false|string
	 */
	private function get_add_new_faq_box_html( $args=array() ) {
		ob_start(); ?>

        <!-- Header -->
        <div class="ephd-admin__item-preview__header">
            <h4 class="ephd-admin__item-preview__title">    <?php
                if ( isset( $args['icon_html'] ) ) {
	                echo wp_kses_post( $args['icon_html'] );
                }   ?>
                <span class="ephd-admin__item-preview__title-text">   <?php
	                esc_html_e( 'Create New FAQ', 'help-dialog' );  ?>
                </span>
            </h4>
        </div>

        <!-- Content -->
        <div class="ephd-admin__item-preview__content">
            <p class="ephd-admin__item-preview__sub-items-title"> <?php
				esc_html_e( 'Add a new FAQ so that you can:', 'help-dialog' ); ?>
            </p>
            <ul class="ephd-admin__item-preview__sub-items-list">
                <li class="ephd-admin__item-preview__sub-item">   <?php
					esc_html_e( 'show different FAQs on different pages', 'help-dialog' );  ?>
                </li>
                <li class="ephd-admin__item-preview__sub-item">   <?php
					esc_html_e( 'enable search in another Knowledge Base', 'help-dialog' ); ?>
                </li>
                <li class="ephd-admin__item-preview__sub-item">   <?php
					esc_html_e( 'use custom colors and text (future feature)', 'help-dialog' ); ?>
                </li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="ephd-admin__item-preview__footer">
            <!-- Actions -->
            <div class="ephd-admin__item-preview__actions">   <?php
				EPHD_HTML_Elements::text( array( 'label' => 'Nickname', 'name' => 'faqs_name' ) );
				EPHD_HTML_Elements::submit_button_v2( __( 'Create New FAQs', 'help-dialog' ), 'ephd_faqs', '', '', false, false, 'ephd-success-btn ephd-fp__create-new-faqs-btn' );  ?>
            </div>
        </div>  <?php

		return ob_get_clean();
	}

	/**
	 * Form to Create/Edit a list of Questions
	 *
	 * @param $widget
	 *
	 * @return false | string
	 */
	public function get_faqs_form_box_html( $widget ) {

		ob_start();     ?>

		<input type="hidden" value="<?php echo esc_attr( $widget['widget_id'] ); ?>" name="widget_id" >  <?php

		EPHD_HTML_Admin::display_admin_form_header( array(
			'icon_html'     => '<span class="ephdfa ep_font_icon_faq_icons ephd-admin__form-title-icon"></span>',
			'title'         => $widget['faqs_name'],
			'actions_html'  => self::get_faqs_form_actions_html(),
			'title_desc'    => 'FAQ Name: ',
			'desc'          => 'FAQ Settings',
		) );    ?>

		<!-- FAQs Form Content -->
		<div class="ephd-fp__faqs-form__content">

			<!-- Row Questions -->
            <div class="ephd-fp__faqs-form__row ephd-fp__questions-wrap">   <?php
                // All Questions
	            echo EPHD_Widgets_Page::get_tab_content_widget_faqs( $widget, $this->all_faqs ); ?>
			</div><!-- End Row Questions -->

		</div><!-- End Content -->  <?php

		return ob_get_clean();
	}

	/**
	 * Display HD Widget preview
	 */
	private function display_widget_preview_box() { ?>
        <!-- Preview -->
        <div class="ephd-fp__widget-preview">
            <div class="ephd-fp__widget-preview-tooltip"><?php
				esc_html_e( 'This is an example of the current Widget configuration. Drag and drop questions to order them.', 'help-dialog' ); ?>
            </div>
            <div class="ephd-fp__widget-preview-content"></div>
        </div>  <?php
	}

	/**
	 * Display Single FAQ
	 *
	 * @param array $args
	 */
	public static function display_single_faq( $args = array() ) {	?>
		<li data-id="<?php echo esc_attr( $args['container_ID'] ); ?>" class="ephd-faq-question-container ephd-faq-question ephd-faq-question--<?php echo esc_attr( $args['container_ID'] ); echo empty( $args['disabled'] ) ? ' ephd-faq-question--active' : ''; ?>" data-modified="<?php echo esc_attr( $args['modified'] ); ?>">
                <div class="ephd-faq-question__buttons">
                    <div class="ephd-faq-question__icon ephdfa ephdfa-bars" title="<?php esc_attr_e( 'Move Top/Down', 'help-dialog' ); ?>"></div>
                    <div class="ephd-faq-question__edit ephdfa ephdfa-pencil-square" title="<?php esc_attr_e( 'Edit Question', 'help-dialog' ); ?>"></div>
                    <div class="ephd-faq-question__delete ephdfa ephdfa-trash" title="<?php esc_attr_e( 'Delete Question', 'help-dialog' ); ?>"></div>
                    <div class="ephd-faq-question__move_right ephdfa ephdfa-times" title="<?php esc_attr_e( 'Remove from Widget', 'help-dialog' ); ?>"></div>
                </div>
                <div class="ephd-faq-question__text"><?php echo esc_html( $args['name'] ); ?></div>
                <div class="ephd-faq-question__add ephd-success-btn" title="<?php esc_attr_e( 'Add this question to the FAQ', 'help-dialog' ); ?>"><?php esc_html_e( 'Add', 'help-dialog' ); ?> <i class="ephdfa ephdfa-chevron-right"></i></div>
		</li>	<?php
	}

	/**
	 * Get configuration array for FAQs views of Help Dialog admin page
	 *
	 * @return array
	 */
	private function get_regular_view_config() {

		/**
		 * VIEW: FAQs
		 */
		$views_config[] = array(

			// Shared
			'active' => true,
			'list_key' => 'faqs',

			// Top Panel Item
			'label_text' => __( 'FAQs', 'help-dialog' ),
			'icon_class' => 'ephdfa ep_font_icon_faq_icons',

			// Boxes List
			'list_top_actions_html' => EPHD_HTML_Admin::get_welcome_message( 'img/faqs-form-preview.jpg', __( 'Configure Help Dialog FAQs', 'help-dialog' ),
						__( 'Create Questions and Answers and group them in FAQ groups.', 'help-dialog' ) ), // An FAQ group can be show in any Help Dialog widget.', 'help-dialog' ) )
			'boxes_list' => $this->get_faqs_boxes()
		);

		/**
		 * TODO future
		 * VIEW: Articles
		 */
		/*$views_config[] = array(

			// Shared
			'list_key' => 'articles',

			// Top Panel Item
			'label_text' => __( 'Articles', 'help-dialog' ),
			'icon_class' => 'ephdfa ephdfa-file-text-o',

			// Boxes List
			'list_top_actions_html' => '',
			'boxes_list' => [],
		);*/

		/**
		 * TODO future
		 * VIEW: Search
		 */
		/*$views_config[] = array(

			// Shared
			'list_key' => 'search',

			// Top Panel Item
			'label_text' => __( 'Search', 'help-dialog' ),
			'icon_class' => 'ephdfa ephdfa-search',

			// Boxes List
			'list_top_actions_html' => '',
			'boxes_list' => [],
		);*/

		return $views_config;
	}

	public static function get_faqs_icon_html() {
		ob_start();     ?>
		<span class="ephdfa ep_font_icon_faq_icons ephd-admin__item-preview__title-icon"></span>     <?php
		return ob_get_clean();
	}

	/**
	 * Return array of Questions signed to given widget
	 *
	 * @param $widget
	 *
	 * @return array
	 */
	public function get_faqs_questions( $widget ) {
		$questions = [];
		foreach ( $widget['faqs_sequence'] as $faq_id ) {
			foreach ( $this->all_faqs as $faq ) {
				if ( $faq_id == $faq->faq_id ) {
					$questions[] = $faq;
					break;
				}
			}
		}
		return $questions;
	}

	/**
	 * Get HTML for single FAQ item
	 *
	 * @param $id
	 * @param $faq_question
	 * @param $faq_answer
	 * @return string
	 */
	public static function get_faq_item_html( $id, $faq_question, $faq_answer ) {

		ob_start();     ?>

		<div role="listitem" aria-label="<?php echo wp_kses_post( $faq_question ); ?>" tabindex="0" class="ephd-hd-faq__list__item-container" data-id="<?php echo esc_attr( $id ); ?>">
			<div class="ephd-hd__item__question">

				<div class="ephd-hd__item__question__icon ephdfa ephdfa-question-circle"></div>

				<div class="ephd-hd__item__question__text">						<?php
					echo wp_kses_post( $faq_question ); ?>
				</div>

				<div class="ephd-hd__item__toggle__icon ephdfa ephdfa-chevron-down"></div>

			</div>

			<div class="ephd-hd__item__answer">

				<div class="ephd-hd__item__answer__text">						<?php
					echo wp_kses_post( wpautop( $faq_answer ) ); ?>
				</div>

			</div>
		</div> <?php

		$result = ob_get_clean();

		return empty( $result ) ? '' : $result;
	}

	/**
	 * Get HTML of FAQs form
	 *
	 * @return false|string
	 */
	private static function get_faqs_form_actions_html() {

		ob_start();  ?>
			<button class="ephd-primary-btn ephd_cancel_faqs">
				<i class="ephdfa ephdfa-chevron-left"></i>  <?php
				esc_html_e( 'Back', 'help-dialog' );   ?>
			</button>   <?php
			EPHD_HTML_Elements::submit_button_v2( __( 'Save', 'help-dialog' ), 'ephd_save_faqs', 'ephd__save_faqs_wrap', '', false, '', 'ephd-success-btn' );

		return ob_get_clean();
	}
}
