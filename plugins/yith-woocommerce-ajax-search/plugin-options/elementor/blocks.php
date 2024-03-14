<?php

$blocks = array(
	'yith-ywcas-widget'      => array(
		'style'                        => 'ywcas-frontend',
		'title'                        => esc_html_x( 'Classic Search', '[elementor]: block name', 'yith-woocommerce-ajax-search' ),
		'description'                  => esc_html_x( 'Add the classic search block', '[elementor]: block description', 'yith-woocommerce-ajax-search' ),
		'shortcode_name'               => 'yith_woocommerce_ajax_search',
		'elementor_map_from_gutenberg' => true,
		'elementor_icon'               => 'eicon-kit-details',
		'editor_render_cb'             => 'ywcas_show_elementor_preview',
		'do_shortcode'                 => true,
		'keywords'                     => array(
			esc_html_x( 'Search', '[elementor]: keywords', 'yith-woocommerce-ajax-search' ),
			esc_html_x( 'Ajax Search Widget', '[elementor]: keywords', 'yith-woocommerce-ajax-search' ),
		),
		'attributes'                   => array()
	)
);

return apply_filters( 'ywcas_elementor_blocks', $blocks );