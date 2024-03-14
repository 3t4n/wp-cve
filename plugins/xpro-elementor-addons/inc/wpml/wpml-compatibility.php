<?php

namespace XproElementorAddons\Inc;

class Xpro_WPML_Compatibility {

	private static $_instance = null;

	private function __construct() {

		add_filter( 'wpml_elementor_widgets_to_translate', array( $this, 'wpml_widgets' ) );
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function wpml_widgets( $widgets ) {

		include_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/wpml/widgets/business-hours.php';
		include_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/wpml/widgets/contact-form.php';
		include_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/wpml/widgets/horizontal-timeline.php';
		include_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/wpml/widgets/hot-spot.php';
		include_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/wpml/widgets/info-list.php';
		include_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/wpml/widgets/news-ticker.php';
		include_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/wpml/widgets/pricing.php';
		include_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/wpml/widgets/simple-gallery.php';
		include_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/wpml/widgets/simple-portfolio.php';
		include_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/wpml/widgets/social-icon.php';
		include_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/wpml/widgets/social-share.php';
		include_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'inc/wpml/widgets/hero-slider.php';

		$widgets_map = array(
			'xpro-animated-link'       => array(
				'conditions' => array( 'widgetType' => 'xpro-animated-link' ),
				'fields'     => array(
					array(
						'field'       => 'text',
						'type'        => __( 'Animated Link: Text', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
				),
			),
			'xpro-author-box'          => array(
				'conditions' => array( 'widgetType' => 'xpro-author-box' ),
				'fields'     => array(
					array(
						'field'       => 'author_name',
						'type'        => __( 'Author Box: Name', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'author_bio',
						'type'        => __( 'Author Box: BIO', 'xpro-elementor-addons' ),
						'editor_type' => 'VISUAL',
					),
					array(
						'field'       => 'link_text',
						'type'        => __( 'Author Box: Link Text', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
				),
			),
			'xpro-auto-content'        => array(
				'conditions' => array( 'widgetType' => 'xpro-auto-content' ),
				'fields'     => array(
					array(
						'field'       => 'text_editor_description',
						'type'        => __( 'AI Content: Editor', 'xpro-elementor-addons' ),
						'editor_type' => 'VISUAL',
					),
				),
			),
			'xpro-before-after'        => array(
				'conditions' => array( 'widgetType' => 'xpro-before-after' ),
				'fields'     => array(
					array(
						'field'       => 'before_label',
						'type'        => __( 'Before After: Before Label', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'after_label',
						'type'        => __( 'Before After: After Label', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
				),
			),
			'xpro-block-quote'         => array(
				'conditions' => array( 'widgetType' => 'xpro-block-quote' ),
				'fields'     => array(
					array(
						'field'       => 'quote_title',
						'type'        => __( 'Block Quote: Title', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'quote_designation',
						'type'        => __( 'Block Quote: Designation', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'quote_description',
						'type'        => __( 'Block Quote: Description', 'xpro-elementor-addons' ),
						'editor_type' => 'AREA',
					),
				),
			),
			'xpro-business-hours'      => array(
				'conditions'        => array( 'widgetType' => 'xpro-business-hours' ),
				'fields'            => array(
					array(
						'field'       => 'title',
						'type'        => __( 'Business Hour: Title', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'sub_title',
						'type'        => __( 'Business Hour: Sub Title', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
				),
				'integration-class' => __NAMESPACE__ . '\\WPML_Business_Hours',
			),
			'xpro-button'              => array(
				'conditions' => array( 'widgetType' => 'xpro-button' ),
				'fields'     => array(
					array(
						'field'       => 'text',
						'type'        => __( 'Button: Text', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
				),
			),
			'xpro-contact-form'        => array(
				'conditions'        => array( 'widgetType' => 'xpro-contact-form' ),
				'fields'            => array(
					array(
						'field'       => 'form_name',
						'type'        => __( 'Contact Form: Form Name', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'button_text',
						'type'        => __( 'Contact Form: Button Text', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'success_message',
						'type'        => __( 'Contact Form: Success Message', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'error_message',
						'type'        => __( 'Contact Form: Error Message', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'required_field_message',
						'type'        => __( 'Contact Form: Require Field Message', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'captcha_message',
						'type'        => __( 'Contact Form: Captcha Message', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
				),
				'integration-class' => __NAMESPACE__ . '\\WPML_Contact_Form',
			),
			'xpro-content-toggle'      => array(
				'conditions' => array( 'widgetType' => 'xpro-content-toggle' ),
				'fields'     => array(
					array(
						'field'       => 'primary_label',
						'type'        => __( 'Content Toggle: Primary Label', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'primary_editor',
						'type'        => __( 'Content Toggle: Primary Editor', 'xpro-elementor-addons' ),
						'editor_type' => 'VISUAL',
					),
					array(
						'field'       => 'secondary_label',
						'type'        => __( 'Content Toggle: Secondary Label', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'secondary_editor',
						'type'        => __( 'Content Toggle: Secondary Editor', 'xpro-elementor-addons' ),
						'editor_type' => 'VISUAL',
					),
				),
			),
			'xpro-counter'             => array(
				'conditions' => array( 'widgetType' => 'xpro-counter' ),
				'fields'     => array(
					array(
						'field'       => 'title',
						'type'        => __( 'Counter: Title', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'description',
						'type'        => __( 'Counter: Description', 'xpro-elementor-addons' ),
						'editor_type' => 'AREA',
					),
					array(
						'field'       => 'badge_text',
						'type'        => __( 'Counter: Badge Text', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
				),
			),
			'xpro-course-grid'         => array(
				'conditions' => array( 'widgetType' => 'xpro-course-grid' ),
				'fields'     => array(
					array(
						'field'       => 'readmore_text',
						'type'        => __( 'Course Grid: Read More Text', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'author_title',
						'type'        => __( 'Course Grid: Author Title', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
				),
			),
			'xpro-custom-field'        => array(
				'conditions' => array( 'widgetType' => 'xpro-custom-field' ),
				'fields'     => array(
					array(
						'field'       => 'cf_label',
						'type'        => __( 'Custom Field: Label', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
				),
			),
			'xpro-drop-cap'            => array(
				'conditions' => array( 'widgetType' => 'xpro-drop-cap' ),
				'fields'     => array(
					array(
						'field'       => 'dropcap_description',
						'type'        => __( 'Drop Cap: Description', 'xpro-elementor-addons' ),
						'editor_type' => 'VISUAL',
					),
				),
			),
			'xpro-heading'             => array(
				'conditions' => array( 'widgetType' => 'xpro-heading' ),
				'fields'     => array(
					array(
						'field'       => 'title_before',
						'type'        => __( 'Simple Heading: Title Before', 'xpro-elementor-addons' ),
						'editor_type' => 'AREA',
					),
					array(
						'field'       => 'title_center',
						'type'        => __( 'Simple Heading: Title Center', 'xpro-elementor-addons' ),
						'editor_type' => 'AREA',
					),
					array(
						'field'       => 'title_after',
						'type'        => __( 'Simple Heading: Title After', 'xpro-elementor-addons' ),
						'editor_type' => 'AREA',
					),
				),
			),
			'xpro-horizontal-timeline' => array(
				'conditions'        => array( 'widgetType' => 'xpro-horizontal-timeline' ),
				'fields'            => array(),
				'integration-class' => __NAMESPACE__ . '\\WPML_Horizontal_Timeline',
			),
			'xpro-hot-spot'            => array(
				'conditions'        => array( 'widgetType' => 'xpro-hot-spot' ),
				'integration-class' => __NAMESPACE__ . '\\WPML_Hot_Spot',
			),
			'xpro-icon-box'            => array(
				'conditions' => array( 'widgetType' => 'xpro-icon-box' ),
				'fields'     => array(
					array(
						'field'       => 'title',
						'type'        => __( 'Icon Box: Title', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'description',
						'type'        => __( 'Icon Box: Description', 'xpro-elementor-addons' ),
						'editor_type' => 'AREA',
					),
					array(
						'field'       => 'badge_text',
						'type'        => __( 'Icon Box: Badge Text', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
				),
			),
			'xpro-infolist'            => array(
				'conditions'        => array( 'widgetType' => 'xpro-infolist' ),
				'fields'            => array(),
				'integration-class' => __NAMESPACE__ . '\\WPML_Info_List',
			),
			'xpro-news-ticker'         => array(
				'conditions'        => array( 'widgetType' => 'xpro-news-ticker' ),
				'fields'            => array(
					array(
						'field'       => 'title',
						'type'        => __( 'News Ticker: Title', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
				),
				'integration-class' => __NAMESPACE__ . '\\WPML_News_Ticker',
			),
			'xpro-post-grid'           => array(
				'conditions' => array( 'widgetType' => 'xpro-post-grid' ),
				'fields'     => array(
					array(
						'field'       => 'readmore_text',
						'type'        => __( 'Post Grid: Read More', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'author_title',
						'type'        => __( 'Post Grid: Author Title', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'prev_label',
						'type'        => __( 'Post Grid: Prev Label', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'next_label',
						'type'        => __( 'Post Grid: Next Label', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
				),
			),
			'xpro-pricing'             => array(
				'conditions'        => array( 'widgetType' => 'xpro-pricing' ),
				'fields'            => array(
					array(
						'field'       => 'title',
						'type'        => __( 'Pricing: Title', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'price',
						'type'        => __( 'Pricing: Price', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'period',
						'type'        => __( 'Pricing: Period', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'features_title',
						'type'        => __( 'Pricing: Features Title', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'item_description',
						'type'        => __( 'Pricing: Description', 'xpro-elementor-addons' ),
						'editor_type' => 'VISUAL',
					),
					array(
						'field'       => 'button_title',
						'type'        => __( 'Pricing: Button Title', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'badge_text',
						'type'        => __( 'Pricing: Badge Text', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
				),
				'integration-class' => __NAMESPACE__ . '\\WPML_Pricing',
			),
			'xpro-progress-bar'        => array(
				'conditions' => array( 'widgetType' => 'xpro-progress-bar' ),
				'fields'     => array(
					array(
						'field'       => 'title',
						'type'        => __( 'Progress Bar: Title', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
				),
			),
			'xpro-promo-box'           => array(
				'conditions' => array( 'widgetType' => 'xpro-promo-box' ),
				'fields'     => array(
					array(
						'field'       => 'title',
						'type'        => __( 'Promo Box: Title', 'xpro-elementor-addons' ),
						'editor_type' => 'AREA',
					),
					array(
						'field'       => 'sub_title',
						'type'        => __( 'Promo Box: Sub Title', 'xpro-elementor-addons' ),
						'editor_type' => 'AREA',
					),
					array(
						'field'       => 'description',
						'type'        => __( 'Promo Box: Description', 'xpro-elementor-addons' ),
						'editor_type' => 'VISUAL',
					),
					array(
						'field'       => 'badge_text',
						'type'        => __( 'Promo Box: Badge Text', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'button_text',
						'type'        => __( 'Promo Box: Button Text', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
				),
			),
			'xpro-search'              => array(
				'conditions' => array( 'widgetType' => 'xpro-search' ),
				'fields'     => array(
					array(
						'field'       => 'placeholder',
						'type'        => __( 'Search: Placeholder', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'button_text',
						'type'        => __( 'Search: Button Text', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
				),
			),
			'xpro-simple-gallery'      => array(
				'conditions'        => array( 'widgetType' => 'xpro-simple-gallery' ),
				'fields'            => array(),
				'integration-class' => __NAMESPACE__ . '\\WPML_Simple_Gallery',
			),
			'xpro-simple-portfolio'    => array(
				'conditions'        => array( 'widgetType' => 'xpro-simple-portfolio' ),
				'fields'            => array(),
				'integration-class' => __NAMESPACE__ . '\\WPML_Simple_Portfolio',
			),
			'xpro-social-icon'         => array(
				'conditions'        => array( 'widgetType' => 'xpro-social-icon' ),
				'fields'            => array(),
				'integration-class' => __NAMESPACE__ . '\\WPML_Social_Icon',
			),
			'xpro-social-share'        => array(
				'conditions'        => array( 'widgetType' => 'xpro-social-share' ),
				'fields'            => array(),
				'integration-class' => __NAMESPACE__ . '\\WPML_Social_Share',
			),
			'xpro-step-flow'           => array(
				'conditions' => array( 'widgetType' => 'xpro-step-flow' ),
				'fields'     => array(
					array(
						'field'       => 'step_flow_title',
						'type'        => __( 'Step Flow: Title', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'step_flow_description',
						'type'        => __( 'Step Flow: Description', 'xpro-elementor-addons' ),
						'editor_type' => 'VISUAL',
					),
					array(
						'field'       => 'step_flow_badge_text',
						'type'        => __( 'Step Flow: Badge Text', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
				),
			),
			'xpro-team'                => array(
				'conditions' => array( 'widgetType' => 'xpro-team' ),
				'fields'     => array(
					array(
						'field'       => 'title',
						'type'        => __( 'Team: Name', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'designation',
						'type'        => __( 'Team: Designation', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'description',
						'type'        => __( 'Team: Description', 'xpro-elementor-addons' ),
						'editor_type' => 'AREA',
					),
				),
			),
			'xpro-testimonial'         => array(
				'conditions' => array( 'widgetType' => 'xpro-testimonial' ),
				'fields'     => array(
					array(
						'field'       => 'name',
						'type'        => __( 'Testimonial: Name', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'designation',
						'type'        => __( 'Testimonial: Designation', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'description',
						'type'        => __( 'Testimonial: Description', 'xpro-elementor-addons' ),
						'editor_type' => 'AREA',
					),
				),
			),
			'xpro-woo-product-grid'    => array(
				'conditions' => array( 'widgetType' => 'xpro-woo-product-grid' ),
				'fields'     => array(
					array(
						'field'       => 'prev_label',
						'type'        => __( 'Product Grid: Prev Label', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'next_label',
						'type'        => __( 'Product Grid: Next Label', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
				),
			),
			'xpro-pie-chart'           => array(
				'conditions' => array( 'widgetType' => 'xpro-pie-chart' ),
				'fields'     => array(
					array(
						'field'       => 'title',
						'type'        => __( 'Pie Chart: Title', 'xpro-elementor-addons' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'description',
						'type'        => __( 'Pie Chart: Description', 'xpro-elementor-addons' ),
						'editor_type' => 'AREA',
					),
				),
			),
			'xpro-hero-slider'         => array(
				'conditions'        => array( 'widgetType' => 'xpro-hero-slider' ),
				'integration-class' => __NAMESPACE__ . '\\WPML_Hero_Slider',
			),
		);

		foreach ( $widgets_map as $key => $data ) {

			$widget_name = $key;

			$entry = array(
				'conditions' => array(
					'widgetType' => $widget_name,
				),
				'fields'     => isset( $data['fields'] ) ? $data['fields'] : array(),
			);

			if ( isset( $data['integration-class'] ) ) {
				$entry['integration-class'] = $data['integration-class'];
			}

			$widgets[ $widget_name ] = $entry;
		}

		return $widgets;
	}

}

Xpro_WPML_Compatibility::instance();
