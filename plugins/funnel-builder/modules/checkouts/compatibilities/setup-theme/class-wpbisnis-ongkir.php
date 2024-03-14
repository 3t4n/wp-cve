<?php

/**
 * WPBisnis - WooCommerce Indo Ongkir
 *  https://www.wpbisnis.com/item/woocommerce-indo-ongkir
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_WPbisnis_ONGKIR
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_WPbisnis_ONGKIR {
	public function __construct() {
		$this->setup_field();
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_action' ] );
	}

	public function remove_action() {
		$instance = WFACP_Common::remove_actions( 'init', 'WPBisnis_WC_Indo_Ongkir_Init', 'load_textdomain' );
		if ( $instance instanceof WPBisnis_WC_Indo_Ongkir_Init && method_exists( $instance, 'enqueue_scripts' ) ) {
			$instance->enqueue_scripts();
		}
	}

	public function setup_field() {


		if ( ! class_exists( 'WPBisnis_WC_Indo_Ongkir_Init' ) || ! class_exists( 'WFACP_Add_Address_Field' ) ) {
			return;
		}


		new WFACP_Add_Address_Field( 'indo_ongkir_kota', [
			'type'        => 'select',
			'options'     => array( '' => '' ),
			'label'       => esc_attr__( 'Kota / Kabupaten', 'wpbisnis-wc-indo-ongkir' ),
			'placeholder' => esc_attr__( 'Pilih Kota / Kabupaten...', 'wpbisnis-wc-indo-ongkir' ),
			'class'       => [ 'form-row-wide' ],
			'cssready'    => [ 'wfacp-col-full' ],
			'required'    => false,
		] );

		new WFACP_Add_Address_Field( 'indo_ongkir_kecamatan', [
			'type'        => 'select',
			'options'     => array( '' => '' ),
			'label'       => esc_attr__( 'Kecamatan', 'wpbisnis-wc-indo-ongkir' ),
			'placeholder' => esc_attr__( 'Pilih Kecamatan...', 'wpbisnis-wc-indo-ongkir' ),
			'class'       => [ 'form-row-wide' ],
			'cssready'    => [ 'wfacp-col-full' ],
			'required'    => false,
			'priority'    => 22,
		] );

		// For Shipping
		new WFACP_Add_Address_Field( 'indo_ongkir_kota', [
			'type'        => 'select',
			'options'     => array( '' => '' ),
			'label'       => esc_attr__( 'Kota / Kabupaten', 'wpbisnis-wc-indo-ongkir' ),
			'placeholder' => esc_attr__( 'Pilih Kota / Kabupaten...', 'wpbisnis-wc-indo-ongkir' ),
			'class'       => [ 'form-row-wide' ],
			'cssready'    => [ 'wfacp-col-full' ],
			'required'    => false,
		], 'shipping' );

		new WFACP_Add_Address_Field( 'indo_ongkir_kecamatan', [
			'type'        => 'select',
			'options'     => array( '' => '' ),
			'label'       => esc_attr__( 'Kecamatan', 'wpbisnis-wc-indo-ongkir' ),
			'placeholder' => esc_attr__( 'Pilih Kecamatan...', 'wpbisnis-wc-indo-ongkir' ),
			'class'       => [ 'form-row-wide' ],
			'cssready'    => [ 'wfacp-col-full' ],
			'required'    => false,
			'priority'    => 22,
		], 'shipping' );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_WPbisnis_ONGKIR(), 'WPbisnis_ONGKIR' );
