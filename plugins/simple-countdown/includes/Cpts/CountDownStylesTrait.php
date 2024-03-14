<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Cpts;

defined( 'ABSPATH' ) || exit;

/**
 * CountDown Styles Related.
 */
trait CountDownStylesTrait {

	/**
	 * Timer Divisions.
	 *
	 * @var array
	 */
	private $timer_divisions = array( 'days', 'hours', 'minutes', 'seconds' );

	/**
	 * Get Countdown Styles.
	 *
	 * @param boolean $echo
	 * @return void
	 */
	public function get_countdown_styles( $id_identifier, $echo = false, $post_id = null ) {
		$countdown_settings = $this->settings->get_settings( null, $post_id );
		if ( ! $echo ) {
			ob_start();
		}
		$main_timer_id = '#' . esc_attr( self::$plugin_info['classes_prefix'] . '-countdown-timer-' . $id_identifier );

		// Countdown Main Style.
		$this->get_countdown_main_styles( $main_timer_id );

		if ( ! $echo ) {
			return ob_get_clean();
		}
	}

	/**
	 * Get Counter Division.
	 *
	 * @param string $settings_key
	 * @return string
	 */
	private function get_counter_division( $settings_key ) {
		foreach ( $this->timer_divisions as $division ) {
			if ( str_contains( $settings_key, $division ) ) {
				return $division;
			}
		}
		return '';
	}

	/**
	 * Get Countdown Main Styles.
	 *
	 * @param string $timer_id
	 * @param boolean $echo
	 * @return mixed
	 */
	private function get_countdown_main_styles( $timer_id, $echo = true ) {
		$main_timer_container_class = '.' . esc_attr( self::$plugin_info['classes_prefix'] . '-countdown-timer-container' );
		$main_timer_wrapper_class   = '.' . esc_attr( self::$plugin_info['classes_prefix'] . '-countdown-timer-wrapper' );
		if ( ! $echo ) {
			ob_start();
		}
		?>
		<?php echo esc_attr( $main_timer_container_class ); ?> {
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			width: 100%;
		}
		<?php echo esc_attr( $main_timer_wrapper_class ); ?> {
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: stretch;
			max-width: 100%;
		}
		.<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-flipper' ); ?> {
			width: 100%;
		}
		<?php echo esc_attr( $timer_id ); ?> .<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-timer-title-wrapper' ); ?> {
			display: flex;
			justify-content: center;
		}
		<?php
		if ( ! $echo ) {
			return ob_get_clean();
		}
	}
}
