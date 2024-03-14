<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BB_Njba_lite_WPML {
	static public function init() {
		add_filter( 'wpml_beaver_builder_modules_to_translate', __CLASS__ . '::bb_njba_module_translate', 10, 1 );
	}

	static public function bb_njba_module_translate( $njba_modules ) {
			$njba_config = array(
				'njba-accordion' => array(
					'fields'            => array(),
					'integration-class' => 'WPML_NJBA_Accordion',
				),
				'njba-advance-cta' => array(
					'fields' => array(
						array(
							'field'       => 'main_title',
							'type'        => __( 'Call to Action - Title', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'sub_title',
							'type'        => __( 'Call to Action - Subtitle', 'bb-njba' ),
							'editor_type' => 'TEXTAREA',
						),
						array(
							'field'       => 'separator_text_select',
							'type'        => __( 'Call to Action - Separator Text', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'button_text',
							'type'        => __( 'Call to Action - Button Text', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'link',
							'type'        => __( 'Call to Action - Button link', 'bb-njba' ),
							'editor_type' => 'LINK',
						),
					),
				),
				'njba-tabs' => array(
					'fields'            => array(),
					'integration-class' => 'WPML_NJBA_Tabs',
				),
				'njba-alert-box' => array(
					'fields' => array(
						array(
							'field'       => 'main_title',
							'type'        => __('Alert Box - Title', 'bb-njba'),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'sub_title',
							'type'        => __('Alert Box - Subtitle', 'bb-njba'),
							'editor_type' => 'LINE',
						),
					),
				),
				'njba-button' => array(
					'fields' => array(
						array(
							'field'       => 'button_text',
							'type'        => __('Button - Button Text', 'bb-njba'),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'link',
							'type'        => __( 'Button - Button link', 'bb-njba' ),
							'editor_type' => 'LINK',
						),
					),
				),
				'njba-contact-form'       => array(
					'fields' => array(
						array(
							'field'       => 'custom_title',
							'type'        => __( 'Contact Form - Custom Title', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'custom_description',
							'type'        => __( 'Contact Form - Custom Description', 'bb-njba' ),
							'editor_type' => 'TEXTAREA',
						),
						array(
							'field'       => 'first_name_label',
							'type'        => __( 'Contact Form - First Name', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'first_name_placeholder',
							'type'        => __( 'Contact Form - First Name Placeholder', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'last_name_label',
							'type'        => __( 'Contact Form - Last Name', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'last_name_placeholder',
							'type'        => __( 'Contact Form - Last Name Placeholder', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'email_label',
							'type'        => __( 'Contact Form - Email', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'email_placeholder',
							'type'        => __( 'Contact Form - Email Placeholder', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'subject_label',
							'type'        => __( 'Contact Form - Subject', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'subject_placeholder',
							'type'        => __( 'Contact Form - Subject Placeholder', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'phone_label',
							'type'        => __( 'Contact Form - Phone', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'phone_placeholder',
							'type'        => __( 'Contact Form - Phone Placeholder', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'msg_label',
							'type'        => __( 'Contact Form - Message', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'msg_placeholder',
							'type'        => __( 'Contact Form - Message Placeholder', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'success_message',
							'type'        => __( 'Contact Form - Success Message', 'bb-njba' ),
							'editor_type' => 'TEXTAREA',
						),
						array(
							'field'       => 'success_url',
							'type'        => __( 'Contact Form - Success URL', 'bb-njba' ),
							'editor_type' => 'LINK',
						),
						array(
							'field'       => 'email_success',
							'type'        => __( 'Contact Form - Success Email', 'bb-njba' ),
							'editor_type' => 'LILINENK',
						),
						array(
							'field'       => 'email_error',
							'type'        => __( 'Contact Form - Error Email', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'btn_text',
							'type'        => __( 'Contact Form - Button Text', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
					),
				),
				'njba-flip-box' => array(
					'fields' => array(
						array(
							'field'       => 'title_front',
							'type'        => __( 'Flipbox - Front Title', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'desc_front',
							'type'        => __( 'Flipbox - Front Desc', 'bb-njba' ),
							'editor_type' => 'TEXTAREA',
						),
						array(
							'field'       => 'title_back',
							'type'        => __( 'Flipbox - Back Title', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'desc_back',
							'type'        => __( 'Flipbox - Back Desc', 'bb-njba' ),
							'editor_type' => 'TEXTAREA',
						),
					),
				),
				'njba-heading' => array(
					'fields' => array(
						array(
							'field'       => 'main_title',
							'type'        => __( 'Heading - Main Title', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'sub_title',
							'type'        => __( 'Heading - Sub Title', 'bb-njba' ),
							'editor_type' => 'TEXTAREA',
						),
						array(
							'field'       => 'separator_text_select',
							'type'        => __( 'Heading - Separator Text', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
					),
				),
				'njba-highlight-box' => array(
					'fields' => array(
						array(
							'field'       => 'main_title',
							'type'        => __( 'Highlight Box - Main Title', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'sub_title',
							'type'        => __( 'Highlight Box - Sub Title', 'bb-njba' ),
							'editor_type' => 'TEXTAREA',
						),
						array(
							'field'       => 'caption_title',
							'type'        => __( 'Highlight Box - Caption Title', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
					),
				),
				'njba-image-hover-two' => array(
					'fields' => array(
						array(
							'field'       => 'caption',
							'type'        => __( 'Image Hover Box Two - Main Caption', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'sub_caption',
							'type'        => __( 'Image Hover Box Two - Sub Caption', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
					),
				),
				'njba-image-hover' => array(
					'fields' => array(
						array(
							'field'       => 'caption',
							'type'        => __( 'Image Hover Box - Main Caption', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
					),
				),
				'njba-image-panels' => array(
					'fields'            => array(),
					'integration-class' => 'WPML_NJBA_Image_Panels',
				),
				'njba-infobox-two' => array(
					'fields' => array(
						array(
							'field'       => 'main_title',
							'type'        => __( 'InfoBox - Title', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'sub_title',
							'type'        => __( 'InfoBox - Subtitle', 'bb-njba' ),
							'editor_type' => 'TEXTAREA',
						),
						array(
							'field'       => 'infobox_two_text_select',
							'type'        => __( 'InfoBox - Text', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
					),
				),
				'njba-infobox' => array(
					'fields' => array(
						array(
							'field'       => 'heading_prefix',
							'type'        => __( 'InfoBox - Prefix', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'title',
							'type'        => __( 'InfoBox - Title', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'text',
							'type'        => __( 'InfoBox - Description', 'bb-njba' ),
							'editor_type' => 'TEXTAREA',
						),
						array(
							'field'       => 'button_text',
							'type'        => __( 'InfoBox - Button Text', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
					),
				),
				'njba-infolist' => array(
					'fields'            => array(),
					'integration-class' => 'WPML_NJBA_InfoList',
				),
				'njba-logo-grid-carousel' => array(
					'fields'            => array(),
					'integration-class' => 'WPML_NJBA_Logo_Grid',
				),
				'njba-opening-hours' => array(
					'fields'            => array(),
					'integration-class' => 'WPML_NJBA_Opening_Hours',
				),
				'njba-price-box' => array(
					'fields'            => array(),
					'integration-class' => 'WPML_NJBA_PriceBox',
				),
				'njba-post-grid'       => array(
					'fields' => array(
						array(
							'field'       => 'more_link_text',
							'type'        => __( 'Post Grid - Button Text', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'no_results_message',
							'type'        => __( 'Post Grid - No Results Message', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
					),
				),
				'njba-post-list'       => array(
					'fields' => array(
						array(
							'field'       => 'more_link_text',
							'type'        => __( 'Post List - Button Text', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'no_results_message',
							'type'        => __( 'Post List - No Results Message', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
					),
				),
				'njba-quote-box' => array(
					'fields' => array(
						array(
							'field'       => 'name',
							'type'        => __( 'Quote Box - Name', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'profile',
							'type'        => __( 'Quote Box - Profile', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'content',
							'type'        => __( 'Quote Box - Content Type', 'bb-njba' ),
							'editor_type' => 'TEXTAREA',
						),
					),
				),
				'njba-separator' => array(
					'fields' => array(
						array(
							'field'       => 'separator_text_select',
							'type'        => __( 'Separator - Text', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
					),
				),
				'njba-slider' => array(
					'fields'            => array(),
					'integration-class' => 'WPML_NJBA_Slider',
				),
				'njba-subscribe-form' => array(
					'fields' => array(
						array(
							'field'       => 'custom_title',
							'type'        => __( 'Subscribe Form - Title', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'custom_description',
							'type'        => __( 'Subscribe Form - Description', 'bb-njba' ),
							'editor_type' => 'TEXTAREA',
						),
						array(
							'field'       => 'input_fname_placeholder',
							'type'        => __( 'Subscribe Form - First Name Placeholder', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'input_lname_placeholder',
							'type'        => __( 'Subscribe Form - Last Name Placeholder', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'input_email_placeholder',
							'type'        => __( 'Subscribe Form - Email Placeholder', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
						array(
							'field'       => 'button_text',
							'type'        => __( 'Subscribe Form - Button Text', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
					),
				),
				'njba-teams' => array(
					'fields'            => array(),
					'integration-class' => 'WPML_NJBA_Teams',
				),
				'njba-testimonials' => array(
					'fields'            => array(
						array(
							'field'       => 'heading',
							'type'        => __( 'Testimonial - Heading', 'bb-njba' ),
							'editor_type' => 'LINE',
						),
					),
					'integration-class' => 'WPML_NJBA_Testimonials',
				),
			);

		foreach ( $njba_config as $njba_module_name => $njba_module_fields ) {
			$njba_module_fields['conditions'] = array( 'type' => $njba_module_name );
			$njba_modules[ $njba_module_name ]     = $njba_module_fields;
		}

		self::njba_load_files();

		return $njba_modules;
	}
	
	static private function njba_load_files() {
		require_once NJBA_MODULE_DIR . 'classes/wpml/class-wpml-njba-accordion.php';
		require_once NJBA_MODULE_DIR . 'classes/wpml/class-wpml-njba-tabs.php';
		require_once NJBA_MODULE_DIR . 'classes/wpml/class-wpml-njba-teams.php';
		require_once NJBA_MODULE_DIR . 'classes/wpml/class-wpml-njba-pricebox.php';
		require_once NJBA_MODULE_DIR . 'classes/wpml/class-wpml-njba-openinghours.php';
		require_once NJBA_MODULE_DIR . 'classes/wpml/class-wpml-njba-infolist.php';
		require_once NJBA_MODULE_DIR . 'classes/wpml/class-wpml-njba-imagepanels.php';
		require_once NJBA_MODULE_DIR . 'classes/wpml/class-wpml-njba-logogrid.php';
		require_once NJBA_MODULE_DIR . 'classes/wpml/class-wpml-njba-slider.php';
		require_once NJBA_MODULE_DIR . 'classes/wpml/class-wpml-njba-testimonials.php';
	}
}

BB_Njba_lite_WPML::init();