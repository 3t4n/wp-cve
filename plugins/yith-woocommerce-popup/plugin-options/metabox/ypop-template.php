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

$template_metabox = array(
	'label'    => __( 'Popup Template', 'yith-woocommerce-popup' ),
	'pages'    => 'yith_popup',
	'context'  => 'normal',
	'priority' => 'high',
	'tabs'     => array(
		'template' => array(
			'label'  => __( 'Template', 'yith-woocommerce-popup' ),
			'fields' => apply_filters(
				'ypop_template_metabox',
				array(
					'template_name' => array(
						'label'   => __( 'Template', 'yith-woocommerce-popup' ),
						'type'    => 'select',
						'class'   => 'wc-enhanced-select',
						'desc'    => '',
						'options' => YITH_Popup()->template_list,
						'std'     => 'none',
					),

				)
			),
		),
	),
);

foreach ( YITH_Popup()->template_list as $key => $template ) {
	$template_metabox['tabs']['template']['fields'][ 'preview_' . $key ] = array(
		'label' => '',
		'type'  => 'preview',
		'std'   => YITH_YPOP_TEMPLATE_URL . '/themes/' . $key . '/preview/preview.jpg',
		'deps'  => array(
			'ids'    => '_template_name',
			'values' => $key,
		),
	);
}

return $template_metabox;
