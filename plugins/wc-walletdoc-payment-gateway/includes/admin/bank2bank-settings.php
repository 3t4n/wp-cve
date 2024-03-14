<?php 

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

return array(

			'enabled' => array(

				'title' => __( 'Enable/Disable', 'woocommerce' ),

				'type' => 'checkbox',

				'label' => __( 'Enable Bank2Bank', 'bank2bank' ),

				'default' => 'yes'

			),

			'title' => array(

				'title' => __( 'Title*', 'woocommerce' ),

				'type' => 'text',

				'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),

				'default' => __( 'Bank2Bank EFT', 'bank2bank' ),

				'desc_tip'      => true,

			),

			'description' => array(

				'title'       => __( 'Description', 'woocommerce' ),

				'type'        => 'text',

				'desc_tip'    => true,

				'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce' ),

				'default'     => __( 'You will be redirected to Walletdoc', 'bank2bank' )

			),

		

		);
