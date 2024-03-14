<?php
/**
 * Add a settings tab to the settings WooCommerce
 *
 * @param array $settings_tabs
 *
 * @since  1.0.0
 * @access public
 *
 * @return array
 */
function add_settings_tab( $settings_tabs ) {
	$settings_tabs['getnet-settings'] = __( 'Getnet' );

	return $settings_tabs;
}

/**
 * Output the tab content
 *
 * @since  1.0.0
 * @access public
 *
 */
function tab_content() {
	woocommerce_admin_fields( gn_get_fields() );
	printf(
		'<h3>Caso não tenha cadastro na plataforma de e-commerce ou possua apenas a maquininha, solicite o cadastro  <a href="%1$s" target="_blank"> no formulário no final da página.</h3>',
		'https://site.getnet.com.br/ecommerce'
	);
}
/**
 * Get the setting fields
 *
 * @since  1.0.0
 * @access private
 *
 * @return array $setting_fields
 */
function gn_get_fields() {

	$setting_fields = [
		'section_title' => [
			'name' => __( 'Getnet Settings' ),
			'type' => 'title',
			'desc' => '',
			'id'   => 'wc_getnet_settings_title'
		],
		'section_environment' => [
			'name'    => __( 'Environment' ),
			'type'    => 'select',
			'desc'    => __( 'Selecione o ambiente, sandbox, homologação ou produção.' ),
			'id'      => 'wc_getnet_settings_environment',
			'options'          => [
				'sandbox'    => __( 'Sandbox' ),
				'homolog'    => __( 'Homologação' ),
				'production' => __( 'Produção' ),
			],
		],
		'section_seller_id' => [
			'name'              => __( 'Seller ID' ),
			'type'              => 'text',
			'desc'              => __( 'Seller ID gerado na minha conta GETNET.' ),
			'id'                => 'wc_getnet_settings_seller_production_id',
		],
		'section_client_id' => [
			'name'              => __( 'Client ID' ),
			'type'              => 'text',
			'desc'              => __( 'Client ID gerado na minha conta GETNET.' ),
			'id'                => 'wc_getnet_settings_client_production_id',
		],
		'section_client_secret' => [
			'name'              => __( 'Client Secret' ),
			'type'              => 'password',
			'desc'              => __( 'Client Secret gerado na minha conta GETNET.' ),
			'id'                => 'wc_getnet_settings_client_production_secret',
		],
		'section_seller_homolog_id' => [
			'name'              => __( 'Seller ID' ),
			'type'              => 'text',
			'desc'              => __( 'Homolog Seller ID gerado na minha conta GETNET.' ),
			'id'                => 'wc_getnet_settings_seller_homolog_id',
		],
		'section_client_homolog_id' => [
			'name'              => __( 'Client ID' ),
			'type'              => 'text',
			'desc'              => __( 'Homolog Client ID gerado na minha conta GETNET.' ),
			'id'                => 'wc_getnet_settings_client_homolog_id',
		],
		'section_client_homolog_secret' => [
			'name'              => __( 'Client Secret' ),
			'type'              => 'password',
			'desc'              => __( 'Homolog Client Secret gerado na minha conta GETNET.' ),
			'id'                => 'wc_getnet_settings_client_homolog_secret',
		],
		'section_seller_sandbox_id' => [
			'name'              => __( 'Seller ID' ),
			'type'              => 'text',
			'desc'              => __( 'Sandbox Seller ID gerado na minha conta GETNET.' ),
			'id'                => 'wc_getnet_settings_seller_sandbox_id',
		],
		'section_client_sandbox_id' => [
			'name'              => __( 'Client ID' ),
			'type'              => 'text',
			'desc'              => __( 'Sandbox Client ID gerado na minha conta GETNET.' ),
			'id'                => 'wc_getnet_settings_client_sandbox_id',
		],
		'section_client_sandbox_secret' => [
			'name'              => __( 'Client Secret' ),
			'type'              => 'password',
			'desc'              => __( 'Sandbox Client Secret gerado na minha conta GETNET.' ),
			'id'                => 'wc_getnet_settings_client_sandbox_secret',
		],
		'section_end' => [
			'type' => 'sectionend',
			'id'   => 'wc_getnet_settings_section_end'
		]
	];

	return apply_filters( 'wc_getnet_tab_settings', $setting_fields );
}

/**
 * Update the settings
 *
 * @since  1.0.0
 * @access public
 */
function update_settings() {
	woocommerce_update_options( gn_get_fields() );
}
