<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH\PreOrder\PluginOptions
 * @author YITH <plugins@yithemes.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) || ! defined( 'YITH_WCPO_VERSION' ) ) {
	exit;
}

return array(
	'style' => apply_filters(
		'ywpo_style_options',
		array(
			'style_options_start'       => array(
				'id'   => 'ywpo_style_options_start',
				'type' => 'sectionstart',
			),
			'style_title'               => array(
				'name' => __( 'Plugin Style', 'yith-pre-order-for-woocommerce' ),
				'id'   => 'ywpo_style_options_title',
				'type' => 'title',
				'desc' => '',
			),
			'show_regular_price'        => array(
				'name'      => __( 'Product price', 'yith-pre-order-for-woocommerce' ),
				'desc'      => __( 'Choose how to show the price of a pre-order product.', 'yith-pre-order-for-woocommerce' ),
				'id'        => 'yith_wcpo_show_regular_price',
				'type'      => 'yith-field',
				'yith-type' => 'radio',
				'options'   => array(
					'no'  => __( 'Hide the regular sale price', 'yith-pre-order-for-woocommerce' ),
					'yes' => __( 'Show the regular sale price crossed out', 'yith-pre-order-for-woocommerce' ),
				),
				'default'   => 'yes',
			),
			'default_add_to_cart_label' => array(
				'name'              => __( 'Pre-order button label', 'yith-pre-order-for-woocommerce' ),
				'desc'              => __( 'Enter the label that replaces the default "Add to cart" label in products in pre-order mode.', 'yith-pre-order-for-woocommerce' ),
				'id'                => 'yith_wcpo_default_add_to_cart_label',
				'type'              => 'yith-field',
				'yith-type'         => 'text',
				'default'           => __( 'Pre-order now', 'yith-pre-order-for-woocommerce' ),
				'custom_attributes' => 'style="width:200px"',
			),
			'availability_date_text'    => array(
				'name'          => __( 'Text to show in products with availability date', 'yith-pre-order-for-woocommerce' ),
				'desc'          => __( 'Enter the default text to inform users about the availability date.', 'yith-pre-order-for-woocommerce' ) . '<br>' .
								/* translators: placeholders with HTML format that must no be modified. %1$s: '<strong>{availability_date}</strong>' %2$s: '<strong>{availability_time}</strong>' */
								sprintf( __( 'Use %1$s and %2$s to show the date and time.', 'yith-pre-order-for-woocommerce' ), '<strong>{availability_date}</strong>', '<strong>{availability_time}</strong>' ),
				'id'            => 'yith_wcpo_default_availability_date_label',
				'type'          => 'yith-field',
				'yith-type'     => 'textarea-editor',
				'media_buttons' => false,
				'textarea_rows' => 5,
				/* translators: placeholders %1$s: date %2$s: time */
				'default'       => sprintf( __( 'Available on: %1$s at %2$s', 'yith-pre-order-for-woocommerce' ), '{availability_date}', '{availability_time}' ),
			),
			'availability_in_shop'      => array(
				'name'      => __( 'Show availability text in the Shop pages', 'yith-pre-order-for-woocommerce' ),
				'desc'      => __( 'Enable to show the availability text in the Shop pages.', 'yith-pre-order-for-woocommerce' ),
				'id'        => 'ywpo_availability_in_shop',
				'type'      => 'yith-field',
				'yith-type' => 'onoff',
				'default'   => 'yes',
			),
			'style_options_end'         => array(
				'id'   => 'ywpo_style_options_end',
				'type' => 'sectionend',
			),
		)
	),
);
