<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Settings;

defined( 'ABSPATH' ) || exit;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsBase\Settings;

use function GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\Fields\CountDownTimerCPT\setup_settings_fields;

/**
 * Countdown Timer CPT Settings.
 */
class CountDownTimerCPTSettings extends Settings {

	/**
	 * Singular Instance.
	 *
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * Prepare Settings.
	 *
	 * @return void
	 */
	protected function prepare() {
		$this->is_cpt = str_replace( '-', '_', self::$plugin_info['classes_prefix'] . '-timer' );
		$this->id     = self::$plugin_info['name'] . '-countdown-timer-cpt-settings';
		$this->fields = setup_settings_fields( self::$core, self::$plugin_info );
	}

	/**
	 * Hooks.
	 *
	 * @return void
	 */
	protected function hooks() {
		// Timezone Field.
		add_action( $this->id . '-settings-field-html-timer_timezone', array( $this, 'timezone_settings_field' ), 100, 1 );
        add_action( $this->id . '-just-after-settings-field-timer_interval', array( $this, 'timer_inteval_reset_btn' ), 100, 1 );
	}

    /**
     * Timer Interval Field Reset Btn.
     *
     * @param array $field
     * @return void
     */
    public function timer_inteval_reset_btn( $field ) {
        ?>
        <button class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-timer-interval-reset-btn' ); ?> btn btn-primary d-none" data-val="<?php echo esc_attr( $field['value'] ); ?>"><?php esc_html_e( 'Reset' ); ?></button>
        <?php
    }

	/**
	 * TimeZone Field.
	 *
	 * @param array $field
	 * @return void
	 */
	public function timezone_settings_field( $field ) {
		?>
		<select id="timer_timezone" name="<?php echo esc_attr( $this->id . '[' . $field['key'] . ']' ); ?>" aria-describedby="timezone-description" disabled="disabled">
			<?php
			echo wp_timezone_choice( $field['value'], get_user_locale() );
			?>
		</select>
		<?php
	}

	/**
	 * Get Site TimeZone String.
	 *
	 * @return void
	 */
	public static function get_site_timezone() {
		$current_offset = get_option( 'gmt_offset' );
		$tzstring       = get_option( 'timezone_string' );

		if ( false !== strpos( $tzstring, 'Etc/GMT' ) ) {
			$tzstring = '';
		}

		if ( empty( $tzstring ) ) {
			if ( 0 == $current_offset ) {
				$tzstring = 'UTC+0';
			} elseif ( $current_offset < 0 ) {
				$tzstring = 'UTC' . $current_offset;
			} else {
				$tzstring = 'UTC+' . $current_offset;
			}
		}
		return $tzstring;
	}
}
