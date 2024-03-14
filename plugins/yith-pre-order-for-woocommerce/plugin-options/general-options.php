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
	'general' => apply_filters(
		'ywpo_general_options',
		array(
			'general_options_start'   => array(
				'id'   => 'ywpo_general_options_start',
				'type' => 'sectionstart',
			),
			'general_options_title'   => array(
				'name' => __( 'General Options', 'yith-pre-order-for-woocommerce' ),
				'id'   => 'ywpo_general_options_title',
				'type' => 'title',
				'desc' => '',
			),
			'enable_pre_order'        => array(
				'name'      => __( 'Enable all Pre-Order features for your visitors', 'yith-pre-order-for-woocommerce' ),
				'desc'      => __( 'Enable to show all pre-orders options on the frontend.', 'yith-pre-order-for-woocommerce' ),
				'id'        => 'yith_wcpo_enable_pre_order',
				'type'      => 'yith-field',
				'yith-type' => 'onoff',
				'default'   => 'yes',
			),
			'remove_pre_order_status' => array(
				'name'      => __( 'Disable pre-order mode when the product becomes available', 'yith-pre-order-for-woocommerce' ),
				'desc'      => __( 'Enable to automatically remove the pre-order mode when the availability date is reached. If this option is disabled, you will need to remove the pre-order status manually on the product page.', 'yith-pre-order-for-woocommerce' ),
				'id'        => 'yith_wcpo_enable_pre_order_purchasable',
				'type'      => 'yith-field',
				'yith-type' => 'onoff',
				'default'   => 'yes',
			),
			'general_options_end'     => array(
				'id'   => 'ywpo_general_options_end',
				'type' => 'sectionend',
			),
		)
	),
);
