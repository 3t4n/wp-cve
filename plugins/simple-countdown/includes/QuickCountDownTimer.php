<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR;

defined( 'ABSPATH' ) || exit;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Base;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Cpts\CountDownTimerCPT;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Cpts\CountDownStylesTrait;

/**
 * Quick CountDown Timer CLass.
 */
class QuickCountDownTimer extends Base {

	use CountDownStylesTrait;

	/**
	 * Singleton Instance.
	 *
	 * @var self
	 */
	private static $instance = null;

	/**
	 * Singleton Init.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->hooks();
	}

	/**
	 * Hooks.
	 *
	 * @return void
	 */
	private function hooks() {
		add_action( 'init', array( $this, 'quick_countdown_timer_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_assets' ) );
		add_filter( 'plugin_action_links_' . self::$plugin_info['basename'], array( $this, 'settings_link' ), 10, 1 );
	}

	/**
	 * Settings Link.
	 *
	 * @param array $links
	 * @return array
	 */
	public function settings_link( $links ) {
		$links[] = '<a href="' . esc_url_raw( admin_url( 'edit.php?post_type=' . CountDownTimerCPT::_get_cpt_key() ) ) . '" >' . esc_html__( 'Timers', 'gpls-core-plugins-pro' ) . '</a>';
		$links[] = '<a target="_blank" style="font-weight:bolder;" href="' . self::$plugin_info['pro_link'] . '" >' . esc_html__( 'Get Pro', 'gpls-core-plugins-pro' ) . '</a>';

		return $links;
	}

	/**
	 * Front Assets.
	 *
	 * @return void
	 */
	public function front_assets() {
		wp_enqueue_style( self::$plugin_info['name'] . '-front-flipdown-css', self::$plugin_info['url'] . 'assets/libs/flipdown.min.css', array(), self::$plugin_info['version'], 'all' );
		wp_enqueue_script( self::$plugin_info['name'] . '-front-countdown-timer-js', self::$plugin_info['url'] . 'assets/dist/js/front/front-countdown-timer.min.js', array( 'jquery' ), self::$plugin_info['version'], true );
		wp_localize_script(
			self::$plugin_info['name'] . '-front-countdown-timer-js',
			str_replace( '-', '_', self::$plugin_info['name'] . '-localized-data' ),
			array(
				'ajaxUrl'          => admin_url( 'admin-ajax.php' ),
				'prefix'           => self::$plugin_info['name'],
				'classes_prefix'   => self::$plugin_info['classes_prefix'],
				'labels'           => array(
					'flipDownHeading' => array(
						'days'    => esc_html__( 'Days', 'simple-countdown' ),
						'hours'   => esc_html__( 'Hours', 'simple-countdown' ),
						'minutes' => esc_html__( 'Minutes', 'simple-countdown' ),
						'seconds' => esc_html__( 'Seconds', 'simple-countdown' ),
					),
					'invalidEmail'     => esc_html__( 'Please enter an email address', 'simple-countdown' ),
				),
				'nonce'            => wp_create_nonce( self::$plugin_info['name'] . '-nonce' ),
				'submitFormAction' => self::$plugin_info['classes_prefix'] . '-subscribe-form-submit',
			)
		);
	}

	/**
	 * Quick Countdown Timer Shortcode.
	 *
	 * @return void
	 */
	public function quick_countdown_timer_shortcode() {
		add_shortcode( str_replace( '-', '_', self::$plugin_info['classes_prefix'] . '-quick-countdown' ), array( $this, 'countdown_shortcode' ) );
	}

	/**
	 * Countdown Timer Shortcode.
	 *
	 * @param array $attrs
	 * @return string
	 */
	public function countdown_shortcode( $attrs ) {
		if ( empty( $attrs['datetime'] ) ) {
			return '';
		}

		$target_time   = (int) sanitize_text_field( wp_unslash( $attrs['datetime'] ) );
		$current_time  = ( current_datetime()->getTimestamp() );
		$timer_id      = ! empty( $attrs['id'] ) ? sanitize_title( sanitize_text_field( wp_unslash( $attrs['id'] ) ) ) : sanitize_title( wp_generate_password( 8, false, false ) );

		if ( ! is_numeric( $target_time ) || (int) $target_time !== $target_time ) {
			return '';
		}

		if ( $current_time > $target_time ) {
			return '';
		}
		ob_start();
		?>
		<div id="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-countdown-timer-' . $timer_id ); ?>" class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-countdown-timer-container ' . self::$plugin_info['classes_prefix'] . '-quick-countdown-timer' ); ?>">
			<div class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-countdown-timer-wrapper' ); ?>">
				<div
					class="flipdown <?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-flipper' ); ?> flipper-dark <?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-flipdown' ); ?>"
					data-datetime="<?php echo esc_attr( $target_time ); ?>"
					data-template="ddd|HH|ii|ss"
					data-labels="Days|Hours|Minutes|Seconds"
					data-now="<?php echo esc_attr( $current_time ); ?>"
				>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
