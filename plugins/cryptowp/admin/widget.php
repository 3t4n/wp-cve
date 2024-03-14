<?php
/**
 * Register the Crypto Listings Widget and build admin + frontend interfaces.
 *
 * @since 1.0
 */

class CryptoWP_Widget extends WP_Widget {

	/**
	 * Properties used throughout Widget.
	 */

	public $strings;

	/**
	 * Set up Widget.
	 *
	 * @since 1.0
	 */

	public function __construct() {
		$this->strings = cryptowp_strings();
		$this->option = get_option( 'cryptowp' );
		parent::__construct( 'cryptowp_widget', $this->strings['crypto'], array(
			'description' => $this->strings['widget_description'],
			'customize_selective_refresh' => true
		) );
	}

	/**
	 * Build Widget frontend display.
	 *
	 * @since 1.0
	 */

	public function widget( $args, $val ) {
		$coins = ! empty( $val['coins'] ) ? array_keys( $val['coins'] ) : '';
		if ( empty( $coins ) )
			return;
		$id = $args['widget_id'];
		$title = $val['title'];
		$text = $val['text'];
		$columns = $val['columns'];
		$layout = $val['layout'];
		$hide_icon = $val['hide_icon'];
		$hide_percent = $val['hide_percent'];
		$classes = $val['classes'];
		$currency_sign = ! empty( $this->option['currency_sign'] ) ? $this->option['currency_sign'] : '$';
		$classes = ! empty( $classes ) ? " $classes" : '';
		include( cryptowp_template( 'widget' ) );
	}

	/**
	 * This function fires when the wiget is saved, cleans up/sanitizes
	 * data to securely save to the database.
	 *
	 * @since 1.0
	 */

	public function update( $new, $old ) {
		$save = array();
		$save['title'] = sanitize_text_field( $new['title'] );
		$save['text'] = sanitize_text_field( $new['text'] );
		$save['hide_icon'] = isset( $new['hide_icon'] ) ? true : false;
		$save['hide_percent'] = isset( $new['hide_percent'] ) ? true : false;
		$save['columns'] = preg_replace( '/\D/', '', $new['columns'] );
		$save['layout'] = in_array( $new['layout'], array( 'list' ) ) ? $new['layout'] : '';
		$save['classes'] = esc_attr( $new['classes'] );
		if ( ! empty( $new['coins'] ) )
			foreach ( $new['coins'] as $coin => $value )
				$save['coins'][$coin] = ! empty( $new['coins'][$coin] ) ? true : false;
		return $save;
	}

	/**
	 * Build Widget backend form interface.
	 *
	 * @since 1.0
	 */

	public function form( $val ) {
		$strings = $this->strings;
		$val = wp_parse_args( (array) $val, array(
			'title' => '',
			'text' => '',
			'coins' => array(),
			'columns' => '',
			'layout' => '',
			'classes' => '',
			'hide_icon' => '',
			'hide_percent' => ''
		) );
		$show_columns = ! empty( $val['layout'] ) && $val['layout'] == 'list' ? 'none' : 'block';
		include( CRYPTOWP_DIR . 'templates/admin/widget-form.php' );
	}

}