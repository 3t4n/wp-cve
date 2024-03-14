<?php

class arforms_divi_module extends ET_Builder_Module{
    public $slug       = 'arformsdivi';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://www.arformsplugin.com',
		'author'     => 'Repute Infosystems',
		'author_uri' => 'https://www.arformsplugin.com',
	);

	public function init() {
		$this->name = esc_html__( 'ARForms', 'arforms-form-builder' );
	}

	public function get_fields() {

		global $arfliteversion, $wpdb, $tbl_arf_forms, $arfliteformhelper, $arformsmain;

        $where_clause = ' AND arf_is_lite_form = 1';
		if( $arformsmain->arforms_is_pro_active() ){
			$where_clause = ' AND arf_is_lite_form = 0';
		}
		$arforms_forms_lite_data = $wpdb->get_results( 'SELECT * FROM `' . $tbl_arf_forms . "` WHERE is_template=0 AND (status is NULL OR status = '' OR status = 'published') {$where_clause} ORDER BY id DESC" );//phpcs:ignore
		$arforms_forms_lite_list = array();
		$n                       = 0;

		$arforms_forms_lite_list['0']            =__("Please select a form",'arforms-form-builder');

		foreach ( $arforms_forms_lite_data as $k => $value ) {
			$arforms_forms_lite_list[$value->id]    = $value->name . ' (id: ' . $value->id . ')';
		}


		return array(
			'form_id'    => [
				'label'           => esc_html__( 'Form', 'arforms-form-builder' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'main_content',
				'options'         => $arforms_forms_lite_list,
			],
		);
	}

	public function get_advanced_fields_config() {

		return [
			'link_options' => false,
			'text'         => false,
			'background'   => false,
			'borders'      => false,
			'box_shadow'   => false,
			'button'       => false,
			'filters'      => false,
			'fonts'        => false,
		];
	}

	public function render( $attrs, $content = null, $render_slug='' ) {
		return do_shortcode( sprintf( '[ARForms id="%1$s"]', $this->props['form_id'] ) );
	}
}

new arforms_divi_module;