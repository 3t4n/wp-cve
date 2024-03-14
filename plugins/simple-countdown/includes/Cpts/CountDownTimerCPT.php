<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Cpts;

defined( 'ABSPATH' ) || exit;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\CountDownTimerCPTSettings;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Utils\NoticeUtilsTrait;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Cpts\CountDownFormTrait;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Cpts\CountDownStylesTrait;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Cpts\CptsBase\Cpt;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Utils\GeneralUtilsTrait;

/**
 * Quick CountDown Timer CPT CLass.
 */
class CountDownTimerCPT extends Cpt {

	use GeneralUtilsTrait, NoticeUtilsTrait, CountDownFormTrait, CountDownStylesTrait;

	/**
	 * Singleton Instance.
	 *
	 * @var self
	 */
	private static $instance = null;

	/**
	 * Metaboxes templates.
	 *
	 * @var array
	 */
	private $metaboxes_templates = array();

	/**
	 * Metaboxes Arguments.
	 *
	 * @var array
	 */
	private $metaboxes_args = array();

	/**
	 * Settings
	 *
	 * @var Settings
	 */
	public $settings;

	/**
	 * Timer Form Subscriptions Key.
	 *
	 * @var string
	 */
	private $form_subscriptions_key;

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
	 * Set CPT Key.
	 *
	 * @return string
	 */
	public static function _get_cpt_key() {
		return str_replace( '-', '_', self::$plugin_info['classes_prefix'] . '-timer' );
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		parent::__construct();
	}

	/**
	 * Setup CPT Arguments.
	 *
	 * @return void
	 */
	protected function setup_cpt_args() {
		$this->setup();
	}

	/**
	 * Setup.
	 *
	 * @return void
	 */
	private function setup() {
		$this->form_subscriptions_key = self::$plugin_info['classes_prefix'] . '-form-subscriptions';
		$this->metaboxes_templates    = array(
			'metabox-configs'       => 'timer-configurations-metabox-template.php',
			'metabox-form'          => 'timer-subscribe-form-metabox-template.php',
			'metabox-preview'       => 'timer-preview-metabox-template.php',
			'metabox-related'       => 'timer-related-metabox-template.php',
			'metabox-subscriptions' => 'timer-subscriptions-metabox-template.php',
		);
		$this->metaboxes_args         = array(
			'plugin_info' => self::$plugin_info,
			'core'        => self::$core,
			'cpt'         => $this,
		);
		$this->settings               = CountDownTimerCPTSettings::init();
		$this->cpt_args               = array(
			'public'    => false,
			'supports'  => array( 'title', 'excerpt', 'author', 'custom-fields' ),
			'show_ui'   => true,
			'menu_icon' => 'dashicons-clock',
			'labels'    => array(
				'name'           => esc_html_x( 'CountDown Timers', 'simple-countdown' ),
				'singular_name'  => esc_html_x( 'CountDown Timer', 'simple-countdown' ),
				'menu_name'      => esc_html_x( 'Simple Countdown', 'simple-countdown' ),
				'name_admin_bar' => esc_html_x( 'CountDown Timer', 'simple-countdown' ),
				'add_new_item'   => esc_html__( 'Add New CountDown Timer', 'simple-countdown' ),
				'new_item'       => esc_html__( 'New CountDown Timer', 'simple-countdown' ),
				'edit_item'      => esc_html__( 'Edit CountDown Timer', 'simple-countdown' ),
			),
		);
	}

	/**
	 * Hooks.
	 *
	 * @return void
	 */
	protected function hooks() {
		add_action( 'init', array( $this, 'countdown_timer_shortcode' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
		add_action( 'add_meta_boxes', array( $this, 'metaboxes' ) );
		add_action( 'wp_ajax_nopriv_' . self::$plugin_info['classes_prefix'] . '-subscribe-form-submit', array( $this, 'ajax_subscribe_form' ) );
		add_action( 'wp_ajax_' . self::$plugin_info['classes_prefix'] . '-subscribe-form-submit', array( $this, 'ajax_subscribe_form' ) );
		add_action( 'wp_ajax_' . self::$plugin_info['classes_prefix'] . '-clear-form-subscriptions', array( $this, 'ajax_clear_subscriptions' ) );
		add_action( 'wp_ajax_' . self::$plugin_info['classes_prefix'] . '-get-timer-timestamp', array( $this, 'ajax_get_timer_timestamp' ) );
	}

	/**
	 * Front Assets.
	 *
	 * @return void
	 */
	public function admin_assets() {
		if ( $this->is_cpt_page() ) {
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_media();
			wp_enqueue_editor();
			wp_enqueue_code_editor(
				array(
					'type' => 'text/html',
				)
			);
			self::notice_assets();
			wp_enqueue_style( self::$plugin_info['name'] . '-bootstrap-css', self::$core->core_assets_lib( 'bootstrap', 'css' ), array(), self::$plugin_info['version'], 'all' );
			wp_enqueue_style( self::$plugin_info['name'] . '-flipdown-css', self::$plugin_info['url'] . 'assets/libs/flipdown.min.css', array(), self::$plugin_info['version'], 'all' );
			wp_enqueue_style( self::$plugin_info['name'] . '-countdown-styles', self::$plugin_info['url'] . 'assets/dist/css/admin/countdown-timer-cpt.min.css', array(), self::$plugin_info['version'], 'all' );
			wp_enqueue_script( self::$plugin_info['name'] . '-bootstrap-js', self::$core->core_assets_lib( 'bootstrap.bundle', 'js' ), array( 'jquery' ), self::$plugin_info['version'], true );
			wp_enqueue_script( self::$plugin_info['name'] . '-settings-actions-js', self::$plugin_info['url'] . 'includes/Settings/assets/dist/js/settings-actions.min.js', array( 'jquery' ), self::$plugin_info['version'], true );
			wp_enqueue_script( self::$plugin_info['name'] . '-countdown-timer-js', self::$plugin_info['url'] . 'assets/dist/js/admin/countdown-timer-cpt.min.js', array( 'jquery', 'wp-color-picker' ), self::$plugin_info['version'], true );
			wp_localize_script(
				self::$plugin_info['name'] . '-settings-actions-js',
				str_replace( '-', '_', self::$plugin_info['name'] . '-localized-data' ),
				array(
					'prefix'         => self::$plugin_info['name'],
					'classes_prefix' => self::$plugin_info['classes_prefix'],
				)
			);
			wp_localize_script(
				self::$plugin_info['name'] . '-countdown-timer-js',
				str_replace( '-', '_', self::$plugin_info['name'] . '-localized-data' ),
				array(
					'ajaxUrl'                  => admin_url( 'admin-ajax.php' ),
					'prefix'                   => self::$plugin_info['name'],
					'classes_prefix'           => self::$plugin_info['classes_prefix'],
					'classes_general'          => self::$plugin_info['classes_general'],
					'labels'                   => array(
						'flipDownHeading'  => array(
							'days'    => esc_html__( 'Days', 'simple-countdown' ),
							'hours'   => esc_html__( 'Hours', 'simple-countdown' ),
							'minutes' => esc_html__( 'Minutes', 'simple-countdown' ),
							'seconds' => esc_html__( 'Seconds', 'simple-countdown' ),
						),
						'clearListConfirm' => esc_html__( 'The emails list will be deleted. proceed?', 'simple-countdown' ),
					),
					'nonce'                    => wp_create_nonce( self::$plugin_info['name'] . '-admin-nonce' ),
					'clearSubscriptionsAction' => self::$plugin_info['classes_prefix'] . '-clear-form-subscriptions',
					'timerTimerStampAction'    => self::$plugin_info['classes_prefix'] . '-get-timer-timestamp',
				)
			);
		}
	}

	/**
	 * Countdown Timer Shortcode.
	 *
	 * @return void
	 */
	public function countdown_timer_shortcode() {
		add_shortcode( str_replace( '-', '_', self::$plugin_info['classes_prefix'] . '-countdown' ), array( $this, 'countdown_shortcode' ) );
	}

	/**
	 * Timer Metaboxes.
	 *
	 * @return void
	 */
	public function metaboxes() {
		add_meta_box( self::$plugin_info['classes_prefix'] . '-timer-configurations', esc_html__( 'Timer Configurations', 'simple-countdown' ), array( $this, 'timer_config_metabox' ), self::_get_cpt_key(), 'normal', 'high' );
		add_meta_box( self::$plugin_info['classes_prefix'] . '-timer-related', esc_html__( 'Timer Related', 'simple-countdown' ), array( $this, 'timer_config_related_metabox' ), self::_get_cpt_key(), 'normal', 'high' );
		add_meta_box( self::$plugin_info['classes_prefix'] . '-timer-subscribe-form', esc_html__( 'Timer Subscibe Form', 'simple-countdown' ) . self::$core->pro_btn( '', 'Pro', '', '', true ), array( $this, 'timer_config_subscribe_form_metabox' ), self::_get_cpt_key(), 'normal', 'high' );
		add_meta_box( self::$plugin_info['classes_prefix'] . '-timer-subscriptions', esc_html__( 'Timer Form Subscriptions Emails', 'simple-countdown' ) . self::$core->pro_btn( '', 'Pro', '', '', true ), array( $this, 'timer_config_subscriptions_metabox' ), self::_get_cpt_key(), 'normal', 'high' );
	}

	/**
	 * Timer Configurations Section Metabox.
	 *
	 * @param \WP_Post $post
	 * @return void
	 */
	public function timer_config_metabox( $post ) {
		$this->metaboxes_args['post_id'] = $post->ID;
		load_template(
			self::$plugin_info['templates_path'] . 'cpts/' . $this->metaboxes_templates['metabox-configs'],
			false,
			$this->metaboxes_args,
		);
	}

	/**
	 * Timer Subscribe Form Section Metabox.
	 *
	 * @param \WP_Post $post
	 * @return void
	 */
	public function timer_config_subscribe_form_metabox( $post ) {
		$this->metaboxes_args['post_id'] = $post->ID;
		load_template(
			self::$plugin_info['templates_path'] . 'cpts/' . $this->metaboxes_templates['metabox-form'],
			false,
			$this->metaboxes_args,
		);
	}

	/**
	 * Timer Preview Section Metabox.
	 *
	 * @param \WP_Post $post
	 * @return void
	 */
	public function timer_config_related_metabox( $post ) {
		$this->metaboxes_args['post_id'] = $post->ID;
		load_template(
			self::$plugin_info['templates_path'] . 'cpts/' . $this->metaboxes_templates['metabox-related'],
			false,
			$this->metaboxes_args,
		);
	}

	/**
	 * Timer Subscriptions Section Metabox.
	 *
	 * @param \WP_Post $post
	 * @return void
	 */
	public function timer_config_subscriptions_metabox( $post ) {
		$this->metaboxes_args['post_id'] = $post->ID;
		load_template(
			self::$plugin_info['templates_path'] . 'cpts/' . $this->metaboxes_templates['metabox-subscriptions'],
			false,
			$this->metaboxes_args,
		);
	}

	/**
	 * Countdown Timer Shortcode.
	 *
	 * @param array $attrs
	 * @return string
	 */
	public function countdown_shortcode( $attrs ) {
		if ( empty( $attrs['id'] ) ) {
			return '';
		}

		$post_id    = absint( sanitize_text_field( wp_unslash( $attrs['id'] ) ) );
		$timer_post = get_post( $post_id );

		if ( ! is_a( $timer_post, '\WP_Post' ) ) {
			return '';
		}

		if ( 'publish' !== $timer_post->post_status ) {
			return '';
		}

		$timer_settings  = $this->settings->get_settings( null, $post_id );
		$target_time     = $timer_settings['timer_interval'];
		$timezone_string = $timer_settings['timer_timezone'];
		$target_timezone = $this->adjust_timezone( $timezone_string );

		try {
			$arrival_time = \DateTime::createFromFormat( 'Y-m-d\TH:i', $target_time, $target_timezone )->getTimestamp();
		} catch ( \Exception $e ) {
			return '';
		}

		$current_time = $this->get_current_time()->getTimestamp();

		if ( ! is_numeric( $arrival_time ) || (int) $arrival_time !== $arrival_time ) {
			return '';
		}

		if ( $current_time > $arrival_time ) {
			return '';
		}
		ob_start();

		$timer_redirect_url      = $this->get_timer_redirect_url( $post_id );
		$timer_has_complete_text = $this->timer_has_complete_text( $post_id );
		$timer_hide_division     = $this->timer_hide_divisions( $post_id );
		?>
		<div id="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-countdown-timer-' . $post_id ); ?>" class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-countdown-timer-container' ); ?>">
			<div class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-countdown-timer-wrapper' ); ?>">
				<?php
				$timer_title = $this->get_timer_title( $post_id );
				if ( ! empty( $timer_title ) ) :
					?>
				<div class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-timer-title-wrapper' ); ?>">
					<?php echo wp_kses_post( $timer_title ); ?>
				</div>
				<?php endif; ?>
				<div
					class="flipdown <?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-flipper' ); ?> flipper-dark <?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-flipdown' ); ?>"
					data-datetime="<?php echo esc_attr( $arrival_time ); ?>"
					data-template="ddd|HH|ii|ss"
					data-labels="Days|Hours|Minutes|Seconds"
					data-now="<?php echo esc_attr( $current_time ); ?>"
					data-redirect="<?php echo esc_url_raw( $timer_redirect_url ); ?>"
					data-completetext="<?php echo esc_attr( $timer_has_complete_text ); ?>"
					data-hidedivisions="<?php echo esc_attr( $timer_hide_division ); ?>"
				>
				</div>
			</div>
			<div style="display:none;" class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-complete-text' ); ?>">
				<?php $this->timer_complete_text( $post_id ); ?>
			</div>
			<style>
				<?php $this->get_countdown_styles( $post_id, true, $post_id ); ?>
			</style>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Get Current Time based on Timezone.
	 *
	 * @param string $timezone
	 * @return \DateTimeImmutable
	 */
	private function get_current_time( $timezone = null ) {
		return ( new \DateTimeImmutable( 'now', $timezone ? $timezone : wp_timezone() ) );
	}

	/**
	 * Get Timezone string for DateTimeZone.
	 *
	 * @param string $timezone_string
	 * @return string
	 */
	private function get_timezone_string( $timezone_string ) {
		if ( preg_match( '/^UTC[+-]/', $timezone_string ) ) {
			$timezone_string = preg_replace( '/UTC\+?/', '', $timezone_string );
			$offset          = (float) $timezone_string;
			$hours           = (int) $offset;
			$minutes         = ( $offset - $hours );
			$sign            = ( $offset < 0 ) ? '-' : '+';
			$abs_hour        = abs( $hours );
			$abs_mins        = abs( $minutes * 60 );
			$timezone_string = sprintf( '%s%02d:%02d', $sign, $abs_hour, $abs_mins );
		}
		return $timezone_string;
	}

	/**
	 * Adjust Timezone.
	 *
	 * @param string $timezone_string
	 * @return \DateTimeZone
	 */
	public function adjust_timezone( $timezone_string ) {
		$timezone_string = $this->get_timezone_string( $timezone_string );
		try {
			$target_timezone = new \DateTimeZone( $timezone_string );
		} catch ( \Exception $e ) {
			$target_timezone = new \DateTimeZone( wp_timezone_string() );
		}

		return $target_timezone;
	}

	/**
	 * Get Timer Redirect URL.
	 *
	 * @param int $post_id
	 * @return string
	 */
	private function get_timer_redirect_url( $post_id ) {
		return $this->settings->get_settings( 'timer_redirect_url', $post_id );
	}

	/**
	 * Timer Complete Text.
	 *
	 * @param int $post_id
	 * @return void
	 */
	private function timer_complete_text( $post_id ) {
		$timer_complete_text = $this->settings->get_settings( 'related_complete_text', $post_id );
		echo wp_kses_post( $timer_complete_text );
	}

	/**
	 * Timer has Complete Text.
	 *
	 * @param int $post_id
	 * @return boolean
	 */
	private function timer_has_complete_text( $post_id ) {
		return ! empty( $this->settings->get_settings( 'related_complete_text', $post_id ) ) ? 1 : 0;
	}

	/**
	 * Timer hide divisions.
	 *
	 * @param int $post_id
	 * @return boolean
	 */
	private function timer_hide_divisions( $post_id ) {
		return ( 'on' === $this->settings->get_settings( 'related_hide_division', $post_id ) ) ? 1 : 0;
	}

	/**
	 * Timer Title.
	 *
	 * @param int $post_id
	 * @return string
	 */
	private function get_timer_title( $post_id ) {
		$timer_title = $this->settings->get_settings( 'related_title', $post_id );
		if ( ! $timer_title ) {
			return '';
		}

		$timer_title_tag = $this->settings->get_settings( 'related_title_type', $post_id );
		$tags            = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
		$timer_title_tag = ( $timer_title_tag && in_array( $timer_title_tag, $tags ) ) ? $timer_title_tag : 'h3';

		ob_start();
		?>
		<<?php echo esc_attr( $timer_title_tag ); ?> class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-timer-title' ); ?>"><?php printf( esc_html__( '%s', '%s' ), $timer_title, self::$plugin_info['text_domain'] ); ?></<?php echo esc_attr( $timer_title_tag ); ?>>
		<?php
		return ob_get_clean();
	}


	/**
	 * AJAX Get Timer Timestamp.
	 *
	 * @return void
	 */
	public function ajax_get_timer_timestamp() {
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], self::$plugin_info['name'] . '-admin-nonce' ) ) {
			self::expired_response();
		}

		$arrival_time = ! empty( $_POST['arrivalTime'] ) ? sanitize_text_field( wp_unslash( $_POST['arrivalTime'] ) ) : '';
		$timezone     = ! empty( $_POST['timezone'] ) ? sanitize_text_field( wp_unslash( $_POST['timezone'] ) ) : '';

		if ( empty( $timezone ) || empty( $arrival_time ) ) {
			self::invalid_submitted_data_response();
		}

		$timezone = $this->adjust_timezone( $timezone );

		try {
			$timestamp = \DateTime::createFromFormat( 'Y-m-d\TH:i', $arrival_time, $timezone )->getTimestamp();
			self::ajax_response(
				'',
				'success',
				200,
				'getTimeStamp',
				array(
					'timestamp' => $timestamp,
				)
			);
		} catch ( \Exception $e ) {
			self::invalid_submitted_data_response();
		}
	}

}
