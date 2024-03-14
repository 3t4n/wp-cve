<?php

namespace StillBE\Plugin\CombineSocialPhotos;

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




class Other_Products {


	public static function show() {

		require_once( ABSPATH. 'wp-admin/includes/plugin.php' );

		$plugins  = get_plugins();
		$products = self::get_products();

		$others = array();

		foreach( $products as $slug => $data ) {
			$is_not_installed = true;
			foreach( $plugins as $key => $plugin ) {
				if( 0 === strpos( $key, $slug. '/' ) ) {
					$is_not_installed = false;
					break;
				}
			}
			if( $is_not_installed ) {
				$others[] = $data;
			}
		}

		if( empty( $others ) ) {
			return;
		}

		echo '<aside class="sb-csp-other-plugins-wrapper">';
		echo   '<p>'. esc_html__( 'Also try these plugins!', 'still-be-combine-social-photos' ). '</p>';
		echo   '<ul class="sb-csp-other-plugins">';
		foreach( $others as $other ) {
			echo '<li>';
			echo   '<a href="'. esc_url( $other['download'] ). '">'. esc_html( $other['name'] ). '</a>';
			echo   '<span>'. esc_html( $other['outline'] ). '</span>';
			echo '</li>';
		}
		echo   '</ul>';
		echo '</aside>';

	}


	public static function get_products() {

		return array(
			'still-be-image-quality-control' => array(
				'name'     => __( 'Image Quality Control | Still BE', 'still-be-combine-social-photos' ),
				'outline'  => __( 'Control the compression quality level of each image size individually to speed your site up display.', 'still-be-combine-social-photos' ),
				'download' => admin_url( '/plugin-install.php?s=Image%20Quality%20Control%20%7C%20Still%20BE&tab=search&type=term' ),
			),
			'still-be-combine-social-photos' => array(
				'name'     => __( 'Combine Social Photos | Still BE', 'still-be-combine-social-photos' ),
				'outline'  => __( 'Provides Instagram embedding functionality exclusively for WP Block Editor.', 'still-be-combine-social-photos' ),
				'download' => admin_url( '/plugin-install.php?s=Combine%20Social%20Photos%20%7C%20Still%20BE&tab=search&type=term' ),
			),
		//	'still-be-creators-own-market'   => array(
		//		'name'     => __( 'Creators Own Market | Still BE', 'still-be-combine-social-photos' ),
		//		'outline'  => __( 'Add download sales functions for creators or developers and open your own shop.', 'still-be-combine-social-photos' ),
		//		'download' => admin_url( '/plugin-install.php?s=Creators%20Own%20Market%20%7C%20Still%20BE&tab=search&type=term' ),   // In Deveropment...
		//	),
		);

	}


}