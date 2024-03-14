<?php

class ET_Builder_Module_compare_button extends ET_Builder_Module {

	public $slug       = 'et_pb_br_compare_button';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => '',
		'author'     => '',
		'author_uri' => '',
	);
    function init() {
        $this->name       = __( 'Compare Button', 'products-compare-for-woocommerce' );
		$this->folder_name = 'et_pb_berocket_modules';
		$this->main_css_element = '%%order_class%%';

        $this->whitelisted_fields = array(
            'product',
            'fast_compare',
            'added_compare',
            'add_compare',
        );

        $this->fields_defaults = array(
            'product'           => array(''),
            'fast_compare'      => array('on', 'add_default_setting'),
            'added_compare'     => array('', 'add_default_setting'),
            'add_compare'       => array('', 'add_default_setting'),
        );

		$this->advanced_fields = array(
			'fonts'           => array(
				'button'   => array(
					'label'        => et_builder_i18n( 'Button' ),
					'css'          => array(
						'main'      => "{$this->main_css_element} .br_compare_button .br_compare_button_text",
						'important' => 'plugin_only',
					),
				),
				'check'   => array(
					'label'        => et_builder_i18n( 'Check mark' ),
					'css'          => array(
						'main'      => "{$this->main_css_element} .br_compare_button .fa",
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
			'borders'       => array(
                'default' => array(
                    'css' => array(
                        'main' => array(
                            'border_radii'  => "{$this->main_css_element} div a.br_compare_button.button",
                            'border_styles' => "{$this->main_css_element} div a.br_compare_button.button"
                        )
                    )
                )
            ),
			'box_shadow'    => false,
			'button'        => false,
			'filters'       => false,
			'margin_padding'=> false,
			'max_width'     => false,
		);
    }

    function get_fields() {
        $fields = array(
            'product' => array(
                'label'            => esc_html__( 'Product', 'et_builder' ),
                'type'             => 'select_product',
                'description'      => esc_html__( 'Here you can select the Product.', 'et_builder' ),
                'searchable'       => true,
                'displayRecent'    => false,
                'default'          => 'current',
                'post_type'        => 'product',
                'computed_affects' => array(
                    '__product',
                ),
            ),
            'fast_compare' => array(
                "label"             => esc_html__( 'Fast compare', 'products-compare-for-woocommerce' ),
                'type'              => 'yes_no_button',
                'options'           => array(
                    'off' => esc_html__( "No", 'et_builder' ),
                    'on'  => esc_html__( 'Yes', 'et_builder' ),
                ),
            ),
            'add_compare' => array(
                "label"             => esc_html__( 'Add to compare button', 'products-compare-for-woocommerce' ),
                'type'              => 'text',
            ),
            'added_compare' => array(
                "label"             => esc_html__( 'Add to compare button if product added', 'products-compare-for-woocommerce' ),
                'type'              => 'text',
            ),
        );

        return $fields;
    }

    function render( $atts, $content = null, $function_name = '' ) {
        $atts = BRCMP_CompareExtension::convert_on_off($atts);
        if( ! empty($atts['product']) ) {
            if( $atts['product'] == 'latest' ) {
                global $wpdb;
                $atts['product'] = $wpdb->get_var("SELECT ID FROM {$wpdb->posts} WHERE post_type = 'product' AND post_status = 'publish' ORDER BY ID DESC LIMIT 1");
            } elseif( $atts['product'] == 'current' ) {
                $atts['product'] = '';
            }
        }
        ob_start();
        do_action('br_compare_button_options', $atts);
        return ob_get_clean();
    }
}

new ET_Builder_Module_compare_button;
