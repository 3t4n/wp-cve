<?php

class ET_Builder_Module_br_compare_table extends ET_Builder_Module {

	public $slug       = 'et_pb_brcmp_compare_table';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => '',
	);
    function init() {
        $this->name       = __( 'Compare Table', 'products-compare-for-woocommerce' );
		$this->folder_name = 'et_pb_berocket_modules';
		$this->main_css_element = '%%order_class%%';

        $this->whitelisted_fields = array(
            'addthis',
        );

        $this->fields_defaults = array(
            'addthis'              => array('on'),
        );

		$this->advanced_fields = array(
			'fonts'           => array(
				'product_title'   => array(
					'label'        => et_builder_i18n( 'Product Title' ),
					'css'          => array(
						'main'      => "{$this->main_css_element} .br_new_compare_block .br_top_table .br_main_top th h1, {$this->main_css_element} .br_new_compare_block .br_top_table .br_main_top th h2, {$this->main_css_element} .br_new_compare_block .br_top_table .br_main_top th h3, {$this->main_css_element} .br_new_compare_block .br_top_table .br_main_top th h4",
						'important' => 'plugin_only',
					),
				),
				'product_price'   => array(
					'label'        => et_builder_i18n( 'Product Price' ),
					'css'          => array(
						'main'      => "{$this->main_css_element} .br_new_compare_block .br_top_table .br_compare_price *",
						'important' => 'plugin_only',
					),
				),
				'attribute_name'   => array(
					'label'        => et_builder_i18n( 'Attribute Names' ),
					'css'          => array(
						'main'      => "{$this->main_css_element} .br_new_compare .br_left_table tr th",
						'important' => 'plugin_only',
					),
				),
				'attribute_value'   => array(
					'label'        => et_builder_i18n( 'Attribute Names' ),
					'css'          => array(
						'main'      => "{$this->main_css_element} .br_new_compare .br_right_table tr td, {$this->main_css_element} .br_new_compare .br_right_table tr td *",
						'important' => 'plugin_only',
					),
				),
			),
			'link_options'  => false,
			'visibility'    => false,
			'text'          => false,
			'transform'     => false,
			'animation'     => false,
			'background'    => false,
			'borders'       => false,
			'box_shadow'    => false,
			'button'        => false,
			'filters'       => false,
			'margin_padding'=> false,
			'max_width'     => false,
		);
    }

    function get_fields() {
        $fields = array(
            'addthis' => array(
                "label"             => esc_html__( 'Display text', 'products-compare-for-woocommerce' ),
                'type'              => 'yes_no_button',
                'options'           => array(
                    'off' => esc_html__( "No", 'et_builder' ),
                    'on'  => esc_html__( 'Yes', 'et_builder' ),
                ),
            ),
        );

        return $fields;
    }

    function render( $atts, $content = null, $function_name = '' ) {
        $atts = BRCMP_CompareExtension::convert_on_off($atts);
        $filter = do_shortcode('[br_compare_table addthis=' . (empty($atts['addthis']) ? '0' : '1') . ']');
        return $filter;
    }
}

new ET_Builder_Module_br_compare_table;
