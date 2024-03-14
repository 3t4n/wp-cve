<?php

class ET_Builder_Module_br_compare_widget extends ET_Builder_Module {

	public $slug       = 'et_pb_brcmp_compare_widget';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => '',
	);
    function init() {
        $this->name       = __( 'Compare Widget', 'products-compare-for-woocommerce' );
		$this->folder_name = 'et_pb_berocket_modules';
		$this->main_css_element = '%%order_class%%';

        $this->whitelisted_fields = array(
            'fast_compare',
            'type',
            'toolbar',
        );

        $this->fields_defaults = array(
            'fast_compare'          => array('off'),
            'type'                  => array('image'),
            'toolbar'               => array('off'),
        );

		$this->advanced_fields = array(
			'fonts'           => array(
				'button'   => array(
					'label'        => et_builder_i18n( 'Compare Button' ),
					'css'          => array(
						'main'      => "{$this->main_css_element} .berocket_compare_widget .berocket_open_compare",
						'important' => 'plugin_only',
					),
				),
				'check'   => array(
					'label'        => et_builder_i18n( 'Product label' ),
					'css'          => array(
						'main'      => "{$this->main_css_element} .berocket_compare_widget ul li span",
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
            'fast_compare' => array(
                "label"             => esc_html__( 'Fast compare to load compare table via AJAX', 'products-compare-for-woocommerce' ),
                'type'              => 'yes_no_button',
                'options'           => array(
                    'off' => esc_html__( "No", 'et_builder' ),
                    'on'  => esc_html__( 'Yes', 'et_builder' ),
                ),
            ),
            'type' => array(
                "label"           => esc_html__( 'Type', 'products-compare-for-woocommerce' ),
                'type'            => 'select',
                'options'         => array(
                    'image' => esc_html__( 'Image', 'products-compare-for-woocommerce' ),
                    'text'  => esc_html__( 'Text', 'products-compare-for-woocommerce' ),
                )
            ),
            'toolbar' => array(
                "label"             => esc_html__( 'Is ToolBar', 'products-compare-for-woocommerce' ),
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
        ob_start();
        the_widget( 'berocket_compare_products_widget', $atts );
        return ob_get_clean();
    }
}

new ET_Builder_Module_br_compare_widget;
