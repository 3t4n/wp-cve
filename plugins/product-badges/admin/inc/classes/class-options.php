<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Lion_Badge_Options extends Lion_Badge_Option {

	public function __construct() {
		add_action( 'save_post', array( $this, 'save_badge' ) );
	}

	/* 
	 * Store default settings
	 */
	private function default_settings() {

		$default_settings = array(
			'badge' => array(
				'shape' => array( 'badge' => 'circle' ),
				'shape_style' => array(
					'background' => '#DE0606',
					'size' => '100',
				),
				'text' => array(
					'text' => '',
					'font_family' => 'Arial',
					'font_size' => '14',
					'color' => '#FFFFFF',
					'align' => 'center',
					'padding_top' => 0,
					'padding_right' => 0,
					'padding_bottom' => 0,
					'padding_left' => 0,
				),
				'position' => array(
					'top' => -29,
					'right' => 0,
					'left' => 57,
				),
				'products' => array(
					'display_for_all_sale_products' => 0,
					'select' => array(),
				),
				'categories' => array(
					'select' => array(),
				),
			),
		);

		return $default_settings;
	}

	/**
	 * Save all the badge settings
	 *
	 * @param type $badge_id 
	 */
	public function save_badge( $badge_id ) {
		if ( isset( $_POST[ self::OPTIONS_NAME ] ) ) {
			$default_settings = $this->default_settings();

			foreach ( $default_settings[ self::OPTIONS_NAME ] as $group => $values ) {
				foreach ( $values as $var => $value ) {
					$value = ( ! isset( $_POST[ self::OPTIONS_NAME ][$group][$var] ) ) ? $default_settings[ self::OPTIONS_NAME ][$group][$var] : $_POST[ self::OPTIONS_NAME ][$group][$var];

					if ( is_array( $value ) )
						$value = maybe_serialize( $value );

					$meta_key = '_' . self::OPTIONS_NAME . '_' . $group . '_' . $var;

					update_post_meta( $badge_id, sanitize_text_field( $meta_key ), sanitize_text_field( $value ) );
				}
			}
		}
	}
}

new Lion_Badge_Options();
