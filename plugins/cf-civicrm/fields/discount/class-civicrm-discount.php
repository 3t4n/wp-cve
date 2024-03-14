<?php

/**
 * CiviCRM Caldera Forms Discount (CiviDiscount) Field Class.
 *
 * @since 1.0
 */
class CiviCRM_Caldera_Forms_Field_Discount {

	/**
	 * Plugin reference.
	 *
	 * @since 1.0
	 */
	public $plugin;

	/**
	 * Field key name.
	 *
	 * @since 1.0
	 * @var string Field key name
	 */
	public $key_name = 'civicrm_discount';

	/**
	 * Initialises this object.
	 *
	 * @since 1.0
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		// register Caldera Forms callbacks
		$this->register_hooks();

	}

	/**
	 * Register hooks.
	 *
	 * @since 1.0
	 */
	public function register_hooks() {

		// add custom fields to Caldera UI
		add_filter( 'caldera_forms_get_field_types', [ $this, 'register_field_type' ] );
		add_filter( 'caldera_forms_render_get_form', [ $this, 'localize_scripts' ], 10 );

	}

	/**
	 * Adds the field definition for this field type to Caldera UI.
	 *
	 * @uses 'caldera_forms_get_field_types' filter
	 *
	 * @since 1.0
	 *
	 * @param array $field_types The existing fields configuration
	 * @return array $field_types The modified fields configuration
	 */
	public function register_field_type( $field_types ) {

		$field_types[$this->key_name] = [
			'field' => __( 'CiviCRM Discount', 'cf-civicrm' ),
			'file' => CF_CIVICRM_INTEGRATION_PATH . 'fields/discount/field.php',
			'category' => __( 'CiviCRM', 'cf-civicrm' ),
			'description' => __( 'CiviCRM Discount field (CiviDiscount integration)', 'cf-civicrm' ),
			'setup' => [
				'template' => CF_CIVICRM_INTEGRATION_PATH . 'fields/discount/config.php',
				'preview' => CF_CIVICRM_INTEGRATION_PATH . 'fields/discount/preview.php',
				'default' => [
					'placeholder' => __( 'Discount Code', 'cf-civicrm' ),
					'button_text' => __( 'Apply Discount', 'cf-civicrm' ),
				],
			],
			'scripts' => [
				CF_CIVICRM_INTEGRATION_URL . 'fields/discount/js/cividiscount.js'
			]
		];

		return $field_types;

	}

	/**
	 * Localize scripts
	 *
	 * @since 1.0
	 * @param array $form Form config
	 * @return array $form Form config
	 */
	public function localize_scripts( $form ) {

		if ( Caldera_Forms_Field_Util::has_field_type( $this->key_name, $form ) )
			wp_localize_script( 'cf-cividiscountjs', 'cfc', [ 'url' => admin_url( 'admin-ajax.php' ) ] );

		return $form;
	}

}
