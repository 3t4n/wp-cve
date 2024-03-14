<?php
/**
 * @file
 * Файл опций плагина для похожих продуктов
 * Version: 0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'woocommerce_get_sections_products', 'glideffxf_related_add_section' );

/**
 * Add section setting
 *
 * @param mixed $sections setting plugin.
 *
 * @return $sections
 */
function glideffxf_related_add_section( $sections ) {

	$sections['glideffxf_related'] = __( 'Related Product Carousel', 'carousel-upsells-and-related-product-for-woocommerce' );
	return $sections;

}

/**
 * Add settings to the specific section we created before
 */
add_filter( 'woocommerce_get_settings_products', 'glideffxf_related_all_settings', 10, 2 );

/**
 *  All settings
 *
 * @param mixed $settings Setting Related Carusel.
 * @param mixed $current_section .
 *
 * @return array
 */
function glideffxf_related_all_settings( $settings, $current_section ) {

	


	if ( 'glideffxf_related' === $current_section ) {

		

		$settings_related = array();

		$settings_related[] = array(
			'name' => __( 'General Setting Related Products', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'type' => 'title',
			'desc' => __( 'On this page you can customize the carousel for similar products.', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'id'   => 'glideffxf_related',
		);

		$settings_related[] = array(
			'name'      => __( 'Disable carousel related', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'type'      => 'checkbox',
			'default'   => 'no',
			'id'        => 'glideffxf_related_no_varusel',
			'css'       => 'width:100%;display:block',
			'desc_tip'  => __( 'Some templates use their own related products, so this carousel may not work. If so, check the box. Nevertheless, we want to make the plugin better, write to us about it on the user support forum, and also indicate the full name of the topic so that it works correctly. We will try to adapt the carousel specifically for your template.<br><br><b>However, other visible settings may work</b>. ', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'desc'      => __( 'Disable carousel and return to standard function Woocommerce', 'carousel-upsells-and-related-product-for-woocommerce' ),
		);

		$settings_related[] = array(
			'name'      => __( 'Section header', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'type'      => 'text',
			'default'   => 'Related products',
			'placeholder' => 'For example Related products',
			'desc_tip'  => __( 'If the field is empty then the title tag will not be displayed, it will just be a carousel of related products', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'id'        => 'glideffxf_related_title',
			'css'       => 'width:100%;display:block',
			'desc'      => __( 'The title of this section will be displayed above the carousel.<br>Replaces the standard phrase of related products', 'carousel-upsells-and-related-product-for-woocommerce' ),
		);

		$settings_related[] = array(
			'name'      => __( 'Autoplay', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'desc'      => __( 'Enable autoplay', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'desc_tip'  => __( 'You can set additional options - these are time intervals for scrolling and stop hovering.<br>If enabled, the carousel of related products will be scrolled with a certain time interval, which you can also set', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'type'      => 'checkbox',
			'default'   => 'yes',
			'id'        => 'glideffxf_related_autoplay',
			'css'       => 'display:none',
		);

		$settings_related[] = array(
			'name'      => __( 'Hover Stop', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'type'      => 'checkbox',
			'default'   => 'no',
			'id'        => 'glideffxf_related_hover_stop',
			'css'       => 'display:none',
			'desc_tip'  => __( 'If checked, the carousel will be paused on hover', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'desc'      => __( 'Bring to a stop on hover', 'carousel-upsells-and-related-product-for-woocommerce' ),
		);

		$settings_related[] = array(
			'title'     => __( 'Time interval', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'desc_tip'  => __( 'Time is set in milliseconds. Recommended time 3000 they are 3 seconds', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'id'        => 'glideffxf_related_interval',
			'type'      => 'number',
			'custom_attributes' => array(
				'min'    => 2000,
				'step'   => 1000,
				'max'    => 15000,
			),
			'default'   => '3000',
			'css'       => 'width:100%;display:block',
			'desc'      => __( 'This is the time interval with which the carousel will change products', 'carousel-upsells-and-related-product-for-woocommerce' ),
		);

		$settings_related[] = array(
			'title'     => __( 'Quantity Related', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'desc_tip'  => __( 'We recommend setting an average of about 12 common products for a more optimized carousel. The more products you demonstrate, the more system resources are consumed.', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'id'        => 'glideffxf_related_quantity',
			'type'      => 'number',
			'custom_attributes' => array(
				'min'    => 6,
				'step'   => 1,
				'max'    => 32,
			),
			'default'   => '12',
			'css'       => 'width:100%;display:block',
			'desc'      => __( 'The total number of products in the carousel of related products', 'carousel-upsells-and-related-product-for-woocommerce' ),
		);

		$settings_related[] = array(
			'title'     => __( 'Number of Visible Products', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'desc_tip'  => __( 'Most templates use a grid of 3 or 4 products. By default, 4 items are displayed in the carousel. You can change this number.', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'id'        => 'glideffxf_related_visible',
			'type'      => 'number',
			'custom_attributes' => array(
				'min'    => 1,
				'step'   => 1,
				'max'    => 6,
			),
			'default'   => '4',
			'css'       => 'width:100%;display:block',
			'desc'      => __( 'Set the optimal value based on the design of your template', 'carousel-upsells-and-related-product-for-woocommerce' ),
		);

		$settings_related[] = array(
			'title'     => __( 'Large monitors', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'desc_tip'  => __( 'Most templates use a grid of 3 or 4 products. By default, 4 items are displayed in the carousel. You can change this number.', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'id'        => 'glideffxf_related_lm',
			'type'      => 'number',
			'custom_attributes' => array(
				'min'    => 1,
				'step'   => 1,
				'max'    => 6,
			),
			'default'   => '4',
			'class'     => 'manage_stock_field',
			'css'       => 'width:100%;display:block',
			'desc'      => __( 'Adaptability adjustment on large monitors. Enter the number of products displayed', 'carousel-upsells-and-related-product-for-woocommerce' ),
		);

		$settings_related[] = array(
			'title'     => __( 'Tablet displays', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'desc_tip'  => __( 'Most templates use a grid of 3 or 4 products. By default, 4 items are displayed in the carousel. You can change this number.', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'id'        => 'glideffxf_related_td',
			'type'      => 'number',
			'custom_attributes' => array(
				'min'    => 1,
				'step'   => 1,
				'max'    => 6,
			),
			'default'   => '3',
			'class'     => 'manage_stock_field',
			'css'       => 'width:100%;display:block',
			'desc'      => __( 'Adaptability setting on tablet screens. Enter the number of products displayed', 'carousel-upsells-and-related-product-for-woocommerce' ),
		);

		$settings_related[] = array(
			'title'     => __( 'Mobile displays', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'desc_tip'  => __( 'Most templates use a grid of 3 or 4 products. By default, 4 items are displayed in the carousel. You can change this number.', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'id'        => 'glideffxf_related_md',
			'type'      => 'number',
			'custom_attributes' => array(
				'min'    => 1,
				'step'   => 1,
				'max'    => 6,
			),
			'default'   => '2',
			'class'     => 'manage_stock_field',
			'css'       => 'width:100%;display:block',
			'desc'      => __( 'Adaptability setting on mobile displays. Enter the number of products displayed', 'carousel-upsells-and-related-product-for-woocommerce' ),
		);

		$settings_related[] = array(
			'name'      => __( 'Mobile notification', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'type'      => 'checkbox',
			'default'   => 'no',
			'id'        => 'glideffxf_related_mobile_notification',
			'css'       => 'width:100%;display:block',
			'desc_tip'  => __( 'Mobile notification appears only on mobile devices with a hint that you can scroll the carousel with your finger.', 'carousel-upsells-and-related-product-for-woocommerce' ).' <a href="#" class="ffxf_dashed" onclick="return window.ffxfMobileNotif(this);" >'.__( 'See an example and details', 'carousel-upsells-and-related-product-for-woocommerce' ).'</a>',
			'desc'      => __( 'Enable notifications for mobile devices.', 'carousel-upsells-and-related-product-for-woocommerce' ),
		);

		$settings_related[] = array(
			'title'    => __( 'Color mobile notification', 'woocommerce' ),
			/* translators: %s: default color */
			'desc'     =>  __( 'The default color is soft black #333333. Color applies to the icon, frame and text.', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'id'       => 'glideffxf_related_mobile_tooltip_color',
			'type'     => 'color',
			'css'      => 'width:6em;',
			'default'  => '#333333',
			'autoload' => false,
			'desc_tip' => true,
		);


		$settings_related[] = array( 'type' => 'sectionend', 'id' => 'glideffxf_related' );

		/**
		 * Additional carousel settings
		 */
		$settings_related[] = array(
			'name' => __( 'Additional carousel settings', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'type' => 'title',
			'desc' => __( 'Set up your carousel for related products. We tried to give all the necessary tips right in the settings. You can set the central mode, navigation, transition animation, and more.', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'id'   => 'glideffxf_related_visual',
		);

		$settings_related[] = array(
			'name'      => __( 'Center mode', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'type'      => 'checkbox',
			'default'   => 'no',
			'id'        => 'glideffxf_related_center_mode',
			'css'       => 'width:100%;display:block',
			'desc_tip'  => __( 'Trim the edges of the carousel. For more information on this feature,', 'carousel-upsells-and-related-product-for-woocommerce' ).' <a href="#" class="ffxf_center_mode" onclick="return window.ffxfResModalShow(this);" >'.__( 'click on this link', 'carousel-upsells-and-related-product-for-woocommerce' ).'</a>. '.__( 'If you want to see live how it works without leaving the plugin settings then click on this link - ', 'carousel-upsells-and-related-product-for-woocommerce' ).'<a class="ffxf_dashed" id="ffxf_center_mode_DEMO" href="#">'.__( 'DEMO setting Center Mode', 'carousel-upsells-and-related-product-for-woocommerce' ).'</a>',
			'desc'      => __( 'Trims the carousel around the edges showing part of the next slide.', 'carousel-upsells-and-related-product-for-woocommerce' ),
		);

		$settings_related[] = array(
			'name'      => __( 'Center mode in mobile', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'type'      => 'checkbox',
			'default'   => 'no',
			'id'        => 'glideffxf_related_center_mode_mobile',
			'css'       => 'width:100%;display:block',
			'desc_tip'  => __( 'If this option is enabled, then the central mode will be applied only on the mobile device, ignoring other permissions. We also recommend installing 1 product on the mobile resolution.', 'carousel-upsells-and-related-product-for-woocommerce' ).' <a href="#" class="ffxf_dashed" onclick="return window.ffxfResModalCM(this);" >'.__( 'See how it looks', 'carousel-upsells-and-related-product-for-woocommerce' ).'</a>',
			'desc'      => __( 'Enable central mode on mobile device only.', 'carousel-upsells-and-related-product-for-woocommerce' ),
		);

		$settings_related[] = array(
			'title'     => __( 'Peek left', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'desc_tip'  => __( 'This setting depends on the central mode. It works in pixels, but accuracy may not match due to the layout of your theme.', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'id'        => 'glideffxf_related_center_mode_left',
			'type'      => 'number',
			'custom_attributes' => array(
				'min'    => 0,
				'step'   => 10,
				'max'    => 150,
			),
			'default'   => '100',
			'css'       => 'width:100%;display:block',
			'desc'      => __( 'The value must be no more than 100.', 'carousel-upsells-and-related-product-for-woocommerce' ),
		);

		$settings_related[] = array(
			'title'     => __( 'Peek right', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'desc_tip'  => __( 'This setting depends on the central mode. It works in pixels, but accuracy may not match due to the layout of your theme.', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'id'        => 'glideffxf_related_center_mode_right',
			'type'      => 'number',
			'custom_attributes' => array(
				'min'    => 0,
				'step'   => 10,
				'max'    => 150,
			),
			'default'   => '100',
			'css'       => 'width:100%;display:block',
			'desc'      => __( 'The value must be no more than 100.', 'carousel-upsells-and-related-product-for-woocommerce' ),
		);

		$settings_related[] = array(
			'name'      => __( 'Transition Animation', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'id'        => 'glideffxf_related_animation',
			'type'      => 'select',
			'class'   => 'wc-enhanced-select',
			'default'   => 'cubic-bezier(0.165, 0.840, 0.440, 1.000)',
			'desc_tip'  => __( 'This setting depends on the central mode. It works in pixels, but accuracy may not match due to the layout of your theme.', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'desc'      => __( 'Animation slide transition, there are several. You can watch the', 'carousel-upsells-and-related-product-for-woocommerce' ).' <a href="#" class="ffxf_dashed" id="ffxfDemoAnim" >'.__( 'DEMO mode right here', 'carousel-upsells-and-related-product-for-woocommerce' ).'</a> '.__( 'and select the desired animation', 'carousel-upsells-and-related-product-for-woocommerce' ),

			'options'   => array(
				'cubic-bezier(0.165, 0.840, 0.440, 1.000)' => __( 'default', 'carousel-upsells-and-related-product-for-woocommerce' ),
				'linear' => __( 'linear', 'carousel-upsells-and-related-product-for-woocommerce' ),
				'ease' => __( 'ease', 'carousel-upsells-and-related-product-for-woocommerce' ),
				'ease-in' => __( 'ease-in', 'carousel-upsells-and-related-product-for-woocommerce' ),
				'ease-out'    => __( 'ease-out', 'carousel-upsells-and-related-product-for-woocommerce' ),
				'ease-in-out' => __( 'ease-in-out', 'carousel-upsells-and-related-product-for-woocommerce' ),
				'cubic-bezier(0.680, -0.550, 0.265, 1.550)'    => __( 'bounce', 'carousel-upsells-and-related-product-for-woocommerce' ),
			),

		);

		$settings_related[] = array(
			'title'     => __( 'Animation Duration', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'desc_tip'  => __( 'The default is 400. Measured in milliseconds.', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'id'        => 'glideffxf_releted_animationDuration',
			'type'      => 'number',
			'custom_attributes' => array(
				'min'    => 0,
				'step'   => 100,
				'max'    => 5000,
			),
			'default'   => '400',
			'css'       => 'width:100%;display:block',
			'desc'      => __( 'You can customize the time of the carousel scroll transition animation. In order to see in action, ', 'carousel-upsells-and-related-product-for-woocommerce' ).'<a class="ffxf_dashed" id="glideffxf_animationDuration_link" href="#">'.__( 'click on the DEMO link', 'carousel-upsells-and-related-product-for-woocommerce' ).'</a>',
		);


		$settings_related[] = array(
			'title'     => __( 'Size of the gap', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'desc_tip'  => __( 'The default is 10. Measured in pixels.', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'id'        => 'glideffxf_releted_gap',
			'type'      => 'number',
			'custom_attributes' => array(
				'min'    => 0,
				'step'   => 1,
				'max'    => 100,
			),
			'default'   => '10',
			'css'       => 'width:100%;display:block',
			'desc'      => __( 'A size of the gap added between slides. If you want to test how it works, ', 'carousel-upsells-and-related-product-for-woocommerce' ).'<a class="ffxf_dashed" id="glideffxf_gap_link" href="#">'.__( 'click DEMO link', 'carousel-upsells-and-related-product-for-woocommerce' ).'</a>',
		);

		$settings_related[] = array(
			'name'      => __( 'Navigation icons', 'easy-woocommerce-auto-sku-generator' ),
			'id'        => 'glideffxf_releted_navigation',
			'type'      => 'radio',
			'default'   => 'one_',
			'class'     => 'glideffxf_navigation',


			'options'   => array(
				'one_'   => '',
				'two_'   => '',
				'three_' => '',
				'four_'  => '',
				'five_'  => '',
				'six_'  => '',
				'seven_'  => '',
				'eight_'  => '',
				'nine_'  => '',
				'ten_'  => '',
				'eleven_'  => '',
				'twelve_'  => '',
				'thirteen_'  => '',
				'fourteen_'  => '',
				'fifteen_'  => '',
				'sixteen_'  => '',

			),

		);

		$settings_related[] = array(
			'title'    => __( 'Arrows background', 'woocommerce' ),
			/* translators: %s: default color */
			'desc'     =>  __( 'The default color is soft black #333333', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'id'       => 'glideffxf_releted_picker',
			'type'     => 'color',
			'css'      => 'width:6em;',
			'default'  => '#333333',
			'autoload' => false,
			'desc_tip' => true,
		);

		$settings_related[] = array( 'type' => 'sectionend', 'id' => 'glideffxf_related_visual' );

		/**
		 * Additional carousel settings
		 */
		$settings_related[] = array(
			'name' => __( 'If not work releted products', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'type' => 'title',
			'desc' => __( 'What if related ones do not work in your template? This can happen for various reasons, perhaps your template implements a custom function for related products or applies a certain filter. Be sure to write to the user support forum and report a problem. If we can identify the problem, we will add a number of fixes specifically for your template.', 'carousel-upsells-and-related-product-for-woocommerce' ).'<br><br>'.__( 'It may also be that your template already has settings for related products, and they simply are not configured. For example, in some templates in the settings there is a parameter that allows you to set the number of displayed products - in this case, it will be enough just to specify the correct quantity in the settings of your template. However, you can always try to apply one of the functions below.', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'id'   => 'glideffxf_upsells_visual',
		);

		$settings_related[] = array(
			'name'      => __( 'Apply higher priority filter', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'type'      => 'checkbox',
			'id'        => 'glideffxf_releted_filter_fix',
			'css'       => 'min-width:300px;display:block',
			'desc'      => __( 'Apply filter <code>woocommerce_output_related_products_args()</code> with higher priority', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'desc_tip'  => __( 'Applying this option can help fix the carousel if this filter is used in your template. If this parameter is used, a filter will be applied that uses your template, but with a higher priority. Please note that we cannot guarantee the correct operation with the settings "Number of sales sold" or "Up-Sells Count" in your template, these parameters will be overridden by the plugin.', 'carousel-upsells-and-related-product-for-woocommerce' ),
		);

		$settings_related[] = array(
			'name'      => __( 'Overwrite WooCommerce template function', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'type'      => 'checkbox',
			'id'        => 'glideffxf_releted_function_fix',
			'css'       => 'min-width:300px;display:block',
			'desc'      => __( 'Override <code>woocommerce_output_related_products()</code> direct function', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'desc_tip'  => __( 'Some templates override this function and assign the number of related products. If you use this parameter, the function will be overridden, and using the plugin you can control the number of products. We cannot guarantee stable operation using this parameter in your template, but in some cases it helps.', 'carousel-upsells-and-related-product-for-woocommerce' ),
		);

		$settings_related[] = array(
			'name'      => __( 'Initialize JS carousel', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'type'      => 'checkbox',
			'id'        => 'glideffxf_releted_javascript_fix',
			'css'       => 'min-width:300px;display:block',
			'desc'      => __( 'Initialize JS after loading the whole page', 'carousel-upsells-and-related-product-for-woocommerce' ),
			'desc_tip'  => __( 'This can help if for some reason the wp_footer hook is overridden or missing. You can also set this parameter by default for more successful page optimization (increases PageSpeed ​​Insights).' ),
		);

		$settings_related[] = array( 'type' => 'sectionend', 'id' => 'glideffxf_releted_fix_theme' );


		return $settings_related;

	} else {
			return $settings;
	}
}