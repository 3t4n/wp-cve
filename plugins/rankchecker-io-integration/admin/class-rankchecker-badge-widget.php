<?php

/**
 * Class Rankchecker_Badge_Widget
 */
class Rankchecker_Badge_Widget extends WP_Widget {

	public $secret;

	/**
	 * Rankchecker_Badge_Widget constructor.
	 */
	public function __construct() {

		parent::__construct( 'rankchecker_badge_widget', 'Rankchecker Badge', array(
			'description' => 'Display Rankchecker.io Badge',
		) );

		$settings     = $this->get_settings();
		$this->secret = $settings[ array_key_first( $settings ) ][ 'secret' ] ?? null;

		if ( is_active_widget( false, false, $this->id_base ) || is_customize_preview() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'load_rankchecker_js' ) );
		}

	}

	/**
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		if ( empty( $instance[ 'secret' ] ) ) {
			echo '<p>Configure Rankchecker widget in Admin Panel first</p>';

			return;
		}

		echo '<div id="rankchecker-badge"></div>';

	}

	public function form( $instance ) {
		$title = $instance[ 'secret' ] ?? ''; ?>

        <p>
            <label for="<?= $this->get_field_id( 'secret' ); ?>">Badge Token:</label>
            <input required type="text" class="widefat" id="<?= $this->get_field_id( 'secret' ); ?>" name="<?= $this->get_field_name( 'secret' ); ?>" value="<?= esc_attr( $title ); ?>">
        </p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {

		$instance             = [];
		$instance[ 'secret' ] = ( ! empty( $new_instance[ 'secret' ] ) ) ? strip_tags( $new_instance[ 'secret' ] ) : '';

		return $instance;

	}

	public function load_rankchecker_js() {

		if ( empty( $this->secret ) ) {
			return;
		}

		wp_enqueue_script( 'rankchecker_badge_js', "https://rankchecker.io/badges/js/{$this->secret}.js", array(), null, true );

	}

}