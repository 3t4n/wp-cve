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

$post_types = get_post_types();

$esclude_post_types = apply_filters(
	'ypop_hide_metabox_ctp',
	array(
		'attachment'        => 'attachment',
		'revision'          => 'revision',
		'nav_menu_item'     => 'nav_menu_item',
		'yith_popup'        => 'yith_popup',
		'shop_order'        => 'shop_order',
		'shop_order_refund' => 'shop_order_refund',
		'shop_coupon'       => 'shop_coupon',
		'shop_webhook'      => 'shop_webhook',
		'product_variation' => 'product_variation',
		'contact-form'      => 'contact-form',
	)
);
$popup_list         = array(
	'default' => __( 'Global setting options', 'yith-woocommerce-popup' ),
	'disable' => __( 'Disable', 'yith-woocommerce-popup' ),
) + YITH_Popup()->get_popups_list();

$cpt_metabox = array(
	'label'    => __( 'YITH WooCommerce Popup ', 'yith-woocommerce-popup' ),
	'pages'    => array_diff( $post_types, $esclude_post_types ),
	'context'  => 'normal',
	'priority' => 'high',
	'tabs'     => array(
		'options' => array(
			'label'  => __( 'Template', 'yith-woocommerce-popup' ),
			'fields' => apply_filters(
				'ypop_cpt_metabox',
				array(
					'welcome_popup' => array(
						'label'   => __( 'Popup', 'yith-woocommerce-popup' ),
						'type'    => 'select',
						'class'   => 'wc-enhanced-select',
						'desc'    => '',
						'options' => $popup_list,
						'std'     => 'default',
					),
				)
			),
		),
	),
);

return $cpt_metabox;
