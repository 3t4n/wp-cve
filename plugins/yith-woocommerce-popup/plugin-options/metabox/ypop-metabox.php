<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH Woocommerce Popup
 * @since   1.0.0
 * @author  YITH <plugins@yithemes.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

$type_of_content = array(
	'newsletter' => __( 'Newsletter', 'yith-woocommerce-popup' ),
);

if ( function_exists( 'WC' ) ) {
	$type_of_content['woocommerce'] = __( 'WooCommerce', 'yith-woocommerce-popup' );
}

$integration_types = YITH_Popup_Newsletter()->get_integration();
$options           = array(
	'label'    => __( 'Popup Settings', 'yith-woocommerce-popup' ),
	'pages'    => 'yith_popup',
	'context'  => 'normal',
	'priority' => 'default',
	'tabs'     => array(
		/*************************************
		 * CONTENT TAB
		 */
		'content'       => array(
			'label'  => __( 'Content', 'yith-woocommerce-popup' ),
			'fields' => apply_filters(
				'ypop_content_metabox',
				array(

					/*************************************
					 * GENERAL OPTIONS
					 */
					'enable_popup'          => array(
						'label' => __( 'Enable popup', 'yith-woocommerce-popup' ),
						'desc'  => '',
						'type'  => 'onoff',
						'std'   => 'yes',

					),
					'content_type'          => array(
						'label'   => __( 'Content type', 'yith-woocommerce-popup' ),
						'desc'    => __( 'Select the type of the content', 'yith-woocommerce-popup' ),
						'type'    => 'select',
						'class'   => 'wc-enhanced-select',
						'std'     => 'newsletter',
						'options' => $type_of_content,
					),

					/*************************************
					 * THEME 1 CONTENT
					 */
					'theme1_header'         => array(
						'label' => __( 'Header', 'yith-woocommerce-popup' ),
						'type'  => 'text',
						'desc'  => __( 'Add the header content of the popup', 'yith-woocommerce-popup' ),
						'std'   => __( 'SIGN UP TO OUR NEWSLETTER AND SAVE 25% OFF FOR YOUR NEXT PURCHASE', 'yith-woocommerce-popup' ),
						'deps'  => array(
							'ids'    => '_template_name',
							'values' => 'theme1',
						),
					),
					'theme1_content'        => array(
						'label' => __( 'Content', 'yith-woocommerce-popup' ),
						'type'  => 'textarea-editor',
						'desc'  => __( 'Add the content of the popup', 'yith-woocommerce-popup' ),
						'std'   => '<h3>Increase more than 500% of Email Subscribers!</h3>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis viverra, urna vitae vehicula congue, purus nibh vestibulum lacus, sit amet tristique ante odio.</p>',
						'deps'  => array(
							'ids'    => '_template_name',
							'values' => 'theme1',
						),
					),
					'theme1_footer_content' => array(
						'label' => __( 'Footer content', 'yith-woocommerce-popup' ),
						'type'  => 'textarea-editor',
						'desc'  => __( 'Add the footer of the popup', 'yith-woocommerce-popup' ),
						'std'   => '<img src="' . YITH_YPOP_TEMPLATE_URL . '/themes/theme1/images/icon-lock.png"> Your Information will never be shared with any third party.',
						'deps'  => array(
							'ids'    => '_template_name',
							'values' => 'theme1',
						),
					),

				)
			),
		),
		/*************************************
		 * LAYOUT TAB
		 */
		'layout'        => array(
			'label'  => __( 'Layout', 'yith-woocommerce-popup' ),
			'fields' => apply_filters(
				'ypop_layout_metabox',
				array(

					/*************************************
					 * THEME 1 LAYOUT
					 */

					'theme1_width'                        => array(
						'label' => __( 'Width', 'yith-woocommerce-popup' ),
						'type'  => 'number',
						'desc'  => __( 'Select the width of the popup.', 'yith-woocommerce-popup' ),
						'min'   => 10,
						'max'   => 2000,
						'std'   => 550,
						'deps'  => array(
							'ids'    => '_template_name',
							'values' => 'theme1',
						),
					),
					'theme1_height'                       => array(
						'label' => __( 'Height', 'yith-woocommerce-popup' ),
						'type'  => 'number',
						'desc'  => __( 'Select the height of the popup. Leave 0 to set it automatically', 'yith-woocommerce-popup' ),
						'min'   => 0,
						'max'   => 2000,
						'std'   => 0,

						'deps'  => array(
							'ids'    => '_template_name',
							'values' => 'theme1',
						),
					),
					'theme1_body_bg_color'                => array(
						'label' => __( 'Background color', 'yith-woocommerce-popup' ),
						'desc'  => __( 'Select the background color of the popup', 'yith-woocommerce-popup' ),
						'type'  => 'colorpicker',
						'std'   => '#ffffff',
						'deps'  => array(
							'ids'    => '_template_name',
							'values' => 'theme1',
						),
					),
					'theme1_header_bg_image'              => array(
						'label' => __( 'Header background image', 'yith-woocommerce-popup' ),
						'desc'  => __( 'Select the background image for the header', 'yith-woocommerce-popup' ),
						'type'  => 'upload',
						'std'   => YITH_YPOP_TEMPLATE_URL . '/themes/theme1/images/header.png',
						'deps'  => array(
							'ids'    => '_template_name',
							'values' => 'theme1',
						),
					),
					'theme1_header_height'                => array(
						'label' => __( 'Header height', 'yith-woocommerce-popup' ),
						'type'  => 'number',
						'desc'  => __( 'Select the height of the header popup', 'yith-woocommerce-popup' ),
						'min'   => 0,
						'max'   => 2000,
						'std'   => 159,
						'deps'  => array(
							'ids'    => '_template_name',
							'values' => 'theme1',
						),
					),
					'theme1_header_color'                 => array(
						'label' => __( 'Header color', 'yith-woocommerce-popup' ),
						'type'  => 'colorpicker',
						'std'   => '#ffffff',
						'desc'  => __( 'Select the color of the header', 'yith-woocommerce-popup' ),
						'deps'  => array(
							'ids'    => '_template_name',
							'values' => 'theme1',
						),
					),
					'theme1_footer_bg_color'              => array(
						'label' => __( 'Footer background color', 'yith-woocommerce-popup' ),
						'desc'  => __( 'Select the background color of the footer', 'yith-woocommerce-popup' ),
						'type'  => 'colorpicker',
						'std'   => '#f4f4f4',
						'deps'  => array(
							'ids'    => '_template_name',
							'values' => 'theme1',
						),
					),
					'theme1_label_position'               => array(
						'label'   => __( 'Position of the field title in newsletter content type', 'yith-woocommerce-popup' ),
						'desc'    => __( 'Select the position of the label ', 'yith-woocommerce-popup' ),
						'type'    => 'select',
						'std'     => 'label',
						'options' => array(
							'label'       => __( 'Label', 'yith-woocommerce-popup' ),
							'placeholder' => __( 'Placeholder', 'yith-woocommerce-popup' ),
						),
						'deps'    => array(
							'ids'    => '_template_name',
							'values' => 'theme1',
						),
					),
					'theme1_submit_button_bg_color'       => array(
						'label' => __( 'Background color for submit button', 'yith-woocommerce-popup' ),
						'desc'  => __( 'Select the background color for submit button', 'yith-woocommerce-popup' ),
						'type'  => 'colorpicker',
						'std'   => '#ff8a00',
						'deps'  => array(
							'ids'    => '_template_name',
							'values' => 'theme1',
						),
					),
					'theme1_submit_button_bg_color_hover' => array(
						'label' => __( 'Background color on hover for submit button', 'yith-woocommerce-popup' ),
						'desc'  => __( 'Select the background color on hover for submit button', 'yith-woocommerce-popup' ),
						'type'  => 'colorpicker',
						'std'   => '#db7600',
						'deps'  => array(
							'ids'    => '_template_name',
							'values' => 'theme1',
						),
					),
					'theme1_submit_button_color'          => array(
						'label' => __( 'Color for submit button', 'yith-woocommerce-popup' ),
						'desc'  => __( 'Select the text color for submit button', 'yith-woocommerce-popup' ),
						'type'  => 'colorpicker',
						'std'   => '#ffffff',
						'deps'  => array(
							'ids'    => '_template_name',
							'values' => 'theme1',
						),
					),

					/*************************************
					 * COMMON LAYOUT OPTIONS
					 */
					'checkzone_bg_color'                  => array(
						'label' => __( 'Background color for the hiding text area', 'yith-woocommerce-popup' ),
						'desc'  => __( 'Select the background color for the hiding text area', 'yith-woocommerce-popup' ),
						'type'  => 'colorpicker',
						'std'   => 'transparent',
					),
					'checkzone_text_color'                => array(
						'label' => __( 'Text color for the hiding text', 'yith-woocommerce-popup' ),
						'desc'  => __( 'Select the text color for the hiding text', 'yith-woocommerce-popup' ),
						'type'  => 'colorpicker',
						'std'   => '#333333',
					),


				)
			),
		),
		'display'       => array(
			'label'  => __( 'Display Settings', 'yith-woocommerce-popup' ),
			'fields' => apply_filters(
				'ypop_display_metabox',
				array(

					'overlay_opacity' => array(
						'label' => __( 'Overlay opacity', 'yith-woocommerce-popup' ),
						'desc'  => '',
						'type'  => 'slider',
						'min'   => 0,
						'max'   => 100,
						'step'  => 10,
						'std'   => 50,
					),

				)
			),
		),
		'customization' => array(
			'label'  => __( 'Customization', 'yith-woocommerce-popup' ),
			'fields' => apply_filters(
				'ypop_customization_metabox',
				array(
					'ypop_css'        => array(
						'label' => __( 'CSS', 'yith-woocommerce-popup' ),
						'desc'  => '',
						'type'  => 'textarea',
						'std'   => '',
					),
					'sep'             => array(
						'type' => 'sep',
					),
					'ypop_javascript' => array(
						'label' => __( 'JavaScript', 'yith-woocommerce-popup' ),
						'desc'  => '',
						'type'  => 'textarea',
						'std'   => '',
					),
				)
			),
		),
		'newsletter'    => apply_filters(
			'yith-popup-newsletter-metabox', //phpcs:ignore
			array(
				'label'  => __( 'Newsletter', 'yith-woocommerce-popup' ),
				'fields' => array(
					'newsletter-integration'          => array(
						'label'   => __( 'Form integration preset', 'yith-woocommerce-popup' ),
						'desc'    => __( 'Select what kind of newsletter service you want to use, or set a custom form.', 'yith-woocommerce-popup' ),
						'type'    => 'select',
						'class'   => 'wc-enhanced-select',
						'options' => $integration_types,
						'std'     => 'custom',
					),

					'newsletter-action'               => array(
						'label' => __( 'Form action', 'yith-woocommerce-popup' ),
						'desc'  => __( 'The attribute "action" of the form.', 'yith-woocommerce-popup' ),
						'type'  => 'text',
						'std'   => '',
						'deps'  => array(
							'ids'    => '_newsletter-integration',
							'values' => 'custom',
						),
					),

					'newsletter-method'               => array(
						'label'   => __( 'Request method', 'yith-woocommerce-popup' ),
						'desc'    => __( 'The attribute "method" of the form.', 'yith-woocommerce-popup' ),
						'type'    => 'select',
						'class'   => 'wc-enhanced-select',
						'options' => array(
							'post' => __( 'POST', 'yith-woocommerce-popup' ),
							'get'  => __( 'GET', 'yith-woocommerce-popup' ),
						),
						'std'     => 'post',
						'deps'    => array(
							'ids'    => '_newsletter-integration',
							'values' => 'custom',
						),
					),

					'newsletter-show-name'            => array(
						'label' => __( 'Show name field', 'yith-woocommerce-popup' ),
						'desc'  => __( 'Show the "Name" field in the newsletter', 'yith-woocommerce-popup' ),
						'type'  => 'onoff',
						'std'   => 'no',
						'deps'  => array(
							'ids'    => '_newsletter-integration',
							'values' => 'custom',
						),
					),

					'newsletter-name-label'           => array(
						'label' => __( 'Name field label', 'yith-woocommerce-popup' ),
						'desc'  => __( 'The label for "Name" field', 'yith-woocommerce-popup' ),
						'type'  => 'text',
						'std'   => 'Your Name',
						'deps'  => array(
							'ids'    => '_newsletter-integration',
							'values' => 'custom',
						),
					),

					'newsletter-name-name'            => array(
						'label' => __( '"Name" attribute of the Name field', 'yith-woocommerce-popup' ),
						'desc'  => __( 'The "Name" attribute of the Name field.', 'yith-woocommerce-popup' ),
						'type'  => 'text',
						'std'   => 'ypop_name',
						'deps'  => array(
							'ids'    => '_newsletter-integration',
							'values' => 'custom',
						),
					),

					'newsletter-email-label'          => array(
						'label' => __( 'Email field label', 'yith-woocommerce-popup' ),
						'desc'  => __( 'The label for the "Email" field', 'yith-woocommerce-popup' ),
						'type'  => 'text',
						'std'   => 'Email',
						'deps'  => array(
							'ids'    => '_newsletter-integration',
							'values' => 'custom',
						),
					),

					'newsletter-email-name'           => array(
						'label' => __( '"Name" attribute for Email field', 'yith-woocommerce-popup' ),
						'desc'  => __( 'The attribute "Name" of the email address field.', 'yith-woocommerce-popup' ),
						'type'  => 'text',
						'std'   => 'ypop_email',
						'deps'  => array(
							'ids'    => '_newsletter-integration',
							'values' => 'custom',
						),
					),
					'newsletter-add-privacy-checkbox' => array(
						'label' => __( 'Add Privacy Policy', 'yith-woocommerce-popup' ),
						'desc'  => '',
						'type'  => 'onoff',
						'std'   => 'no',
						'deps'  => array(
							'ids'    => '_newsletter-integration',
							'values' => 'custom',
						),
					),
					'newsletter-privacy-name'         => array(
						'label' => __( '"Name" attribute of the Privacy field', 'yith-woocommerce-popup' ),
						'desc'  => __( 'The "Name" attribute of the Privacy field.', 'yith-woocommerce-popup' ),
						'type'  => 'text',
						'std'   => 'ypop_privacy',
						'deps'  => array(
							'ids'    => '_newsletter-integration',
							'values' => 'custom',
						),
					),
					'newsletter-privacy-label'        => array(
						'label' => __( 'Privacy Policy Label', 'yith-woocommerce-popup' ),
						'desc'  => '',
						'type'  => 'text',
						'std'   => __( 'I have read and agree to the website terms and conditions.', 'yith-woocommerce-popup' ),
						'deps'  => array(
							'ids'    => '_newsletter-integration',
							'values' => 'custom',
						),
					),

					'newsletter-privacy-description'  => array(
						'label' => __( 'Privacy Policy Description', 'yith-woocommerce-popup' ),
						'desc'  => __( 'You can use the shortcode [privacy_policy] (from WordPress 4.9.6) to add the link to privacy policy page', 'yith-woocommerce-popup' ),
						'type'  => 'textarea',
						'std'   => __( 'Your personal data will be used to process your request, support your experience throughout this website, and for other purposes described in our [privacy_policy].', 'yith-woocommerce-popup' ),
						'deps'  => array(
							'ids'    => '_newsletter-integration',
							'values' => 'custom',
						),
					),

					'newsletter-submit-label'         => array(
						'label' => __( 'Submit button label', 'yith-woocommerce-popup' ),
						'desc'  => __( 'This field is not always used. It depends on the style of the form.', 'yith-woocommerce-popup' ),
						'type'  => 'text',
						'std'   => 'Add Me',
						'deps'  => array(
							'ids'    => '_newsletter-integration',
							'values' => 'custom',
						),
					),

					'newsletter-hidden-fields'        => array(
						'label' => __( 'Hidden fields', 'yith-woocommerce-popup' ),
						'desc'  => __( 'Type here all hidden field names and values in a serial way. Example: name1=value1&name2=value2.', 'yith-woocommerce-popup' ),
						'type'  => 'text',
						'std'   => '',
						'deps'  => array(
							'ids'    => '_newsletter-integration',
							'values' => 'custom',
						),
					),
				),
			)
		),

	),
);

if ( function_exists( 'WC' ) ) {
	$woocommerce_options = array(
		'woocommerce' => array(
			'label'  => __( 'WooCommerce', 'yith-woocommerce-popup' ),
			'fields' => apply_filters(
				'ypop_woocommerce_metabox',
				array(

					'ypop_products'     => array(
						'label'    => __( 'Select products', 'yith-woocommerce-popup' ),
						'desc'     => '',
						'type'     => 'ajax-products',
						'multiple' => true,
						'options'  => array(),
						'std'      => array(),

					),


					'show_title'        => array(
						'label' => __( 'Show name of product', 'yith-woocommerce-popup' ),
						'desc'  => '',
						'type'  => 'checkbox',
						'std'   => 'yes',
					),


					'show_thumbnail'    => array(
						'label' => __( 'Show thumbnail of product', 'yith-woocommerce-popup' ),
						'desc'  => '',
						'type'  => 'checkbox',
						'std'   => 'yes',
					),

					'show_price'        => array(
						'label' => __( 'Show price of product', 'yith-woocommerce-popup' ),
						'desc'  => '',
						'type'  => 'checkbox',
						'std'   => 'yes',
					),

					'show_add_to_cart'  => array(
						'label' => __( 'Show Add to Cart', 'yith-woocommerce-popup' ),
						'desc'  => '',
						'type'  => 'checkbox',
						'std'   => 'yes',
					),

					'add_to_cart_label' => array(
						'label' => __( '"Add to cart" Label', 'yith-woocommerce-popup' ),
						'desc'  => '',
						'type'  => 'text',
						'std'   => __( 'Add to cart', 'yith-woocommerce-popup' ),
					),

					'show_summary'      => array(
						'label' => __( 'Show summary', 'yith-woocommerce-popup' ),
						'desc'  => '',
						'type'  => 'checkbox',
						'std'   => 'yes',
					),



				)
			),
		),
	);

	$options['tabs'] = array_merge( $options['tabs'], $woocommerce_options );
}

return $options;


