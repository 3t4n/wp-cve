<?php
/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'EverestThemes_Stats' ) ) {
	return;
}

class EverestThemes_Stats {

	private static $instances = array();

	/**
	 * Returns instance.
	 *
	 * @param string $plugin_file
	 * @param string $logo_url
	 * @return EverestThemes_Stats
	 */
	public static function get_instance( $plugin_file, $logo_url = '' ) {

		$hash = md5( $plugin_file );

		if ( ! isset( self::$instances[ $hash ] ) ) {
			self::$instances[ $hash ] = new self( $plugin_file, $logo_url );
		}

		return self::$instances[ $hash ];
	}

	private $api_url = 'https://stats.everestthemes.com/wp-json/everestthemes/v1/stats';
	private $logo_url;
	private $plugins;
	private $plugin_file;
	private $plugin_data;
	private $transient_key = '';

	private $called = false;

	public function __construct( $plugin_file, $logo_url = '' ) {

		$this->logo_url = $logo_url;

		$this->transient_key = 'everestthemes_stats_last_synced_' . md5( $plugin_file );

		$this->plugin_file = $plugin_file;

		if ( defined( 'EVERESTTHEMES_STATS_API_URL' ) ) {
			$this->api_url = EVERESTTHEMES_STATS_API_URL;
		}

		if ( ! $this->is_sendable() ) {
			return;
		}

		$this->plugin_data = get_file_data(
			$this->plugin_file,
			array(
				'Name'       => 'Plugin Name',
				'Version'    => 'Version',
				'TextDomain' => 'Text Domain',
			)
		);

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$this->plugins = get_plugins();

		$this->handle_deactivation_feedback();
		add_action( 'admin_footer', array( $this, 'prepare_deactivation_feedback' ) );
	}

	public function is_sendable() {
		if ( ! defined( 'EVERESTTHEMES_STATS_API_URL' ) ) {
			return ! $this->is_localhost();
		}

		return true;
	}

	public function init() {
		$last_synced = get_transient( $this->transient_key );

		if ( $last_synced ) {
			return;
		}

		$this->send();

		set_transient( $this->transient_key, time(), WEEK_IN_SECONDS );
	}

	public function send( $additional_data = array() ) {

		if ( $this->called ) {
			/**
			 * Prevent sending multiple requests in one run.
			 */
			return;
		}

		$this->called = true;

		if ( ! $this->is_sendable() ) {
			return;
		}

		$headers = array(
			'user-agent' => $this->plugin_data['TextDomain'] . '/' . $this->plugin_data['Version'] . '; ' . get_bloginfo( 'url' ),
		);

		$body = array(
			'agent' => $this->plugin_data,
			'data'  => array_merge( $this->get_data(), $additional_data )
		);

		return wp_remote_post(
			$this->api_url,
			array(
				'blocking' => true,
				'headers'  => $headers,
				'body'     => $body,
			)
		);
	}

	protected function handle_deactivation_feedback() {

		if ( ! isset( $_POST["everestthemes_stats_{$this->plugin_data['TextDomain']}_nonce"] ) ) {
			return;
		}

		if ( empty( $_POST["everestthemes_stats_{$this->plugin_data['TextDomain']}_feedback"]['title'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST["everestthemes_stats_{$this->plugin_data['TextDomain']}_nonce"], "everestthemes_stats_{$this->plugin_data['TextDomain']}_nonce" ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$title_hash = md5( $_POST["everestthemes_stats_{$this->plugin_data['TextDomain']}_feedback"]['title'] );

		$this->send(
			array(
				'deactivate' => true,
				'feedback'   => array(
					'title' => ! empty( $_POST["everestthemes_stats_{$this->plugin_data['TextDomain']}_feedback"]['title'] ) ? $_POST["everestthemes_stats_{$this->plugin_data['TextDomain']}_feedback"]['title'] : '',
					'input' => ! empty( $_POST["everestthemes_stats_{$this->plugin_data['TextDomain']}_feedback"]['input'][ $title_hash ] ) ? $_POST["everestthemes_stats_{$this->plugin_data['TextDomain']}_feedback"]['input'][ $title_hash ] : '',
				),
			)
		);

		wp_safe_redirect( $_POST["everestthemes_stats_{$this->plugin_data['TextDomain']}_deactivation_link"] );
		exit;

	}

	public function prepare_deactivation_feedback() {
		$is_plugins_page = in_array( get_current_screen()->id, array( 'plugins', 'plugins-network' ), true );

		if ( ! $is_plugins_page ) {
			return;
		}

		$deactivate_reasons = array(
			array(
				'title'             => esc_html__( 'Temporary deactivation' ),
				'input_placeholder' => '',
			),
			array(
				'title'             => esc_html__( 'I no longer need the plugin' ),
				'input_placeholder' => '',
			),
			array(
				'title'             => esc_html__( 'I did not find the feature I was looking for' ),
				'input_placeholder' => esc_html__( 'If possible, please elaborate on this' ),
			),
			array(
				'title'             => esc_html__( 'I found the plugin complex to use' ),
				'input_placeholder' => esc_html__( 'If possible, please elaborate on this' ),
			),
			array(
				'title'             => esc_html__( 'I found a better alternative.' ),
				'input_placeholder' => esc_html__( 'If possible, please mention the alternatives' ),
			),
			array(
				'title'             => esc_html__( 'Plugin did not work as expected.' ),
				'input_placeholder' => esc_html__( 'If possible, please elaborate on this' ),
			),
			array(
				'title'             => esc_html__( 'Other' ),
				'input_placeholder' => esc_html__( 'If possible, please elaborate on this' ),
			),
		);

		$dialog_box_id = "everestthemes-stats-{$this->plugin_data['TextDomain']}-dialog-box";

		?>
		<style>
			#<?php echo esc_attr( $dialog_box_id ); ?> {
				padding: 15px;
				border: none;
			}
			#<?php echo esc_attr( $dialog_box_id ); ?>::backdrop {
				background-color: rgba(0, 0, 0, 0.72);
			}

			#<?php echo esc_attr( $dialog_box_id ); ?> .everestthemes-stats-feedback-header {
				position: relative;
				margin-bottom: 10px;
			}

			#<?php echo esc_attr( $dialog_box_id ); ?> .everestthemes-stats-feedback-header .close-deactivate-feedback-popup {
				position: absolute;
				right: 0;
				cursor: pointer;
				border: none;
			}

			#<?php echo esc_attr( $dialog_box_id ); ?> .everestthemes-stats-input-wrapper input[type=text] {
				display: none;
			}

			#<?php echo esc_attr( $dialog_box_id ); ?> .everestthemes-stats-input-wrapper input[type=radio]:checked+label+input[type=text] {
				display: block;
			}

			#<?php echo esc_attr( $dialog_box_id ); ?> {
				width: 500px;
				padding: 40px;
			}
			#<?php echo esc_attr( $dialog_box_id ); ?>  .everestthemes-stats-feedback-header {
				display: flex;
				flex-wrap: nowrap;
				align-items: center;
				justify-content: space-start;
				gap: 10px;
				margin-bottom: 20px;
			}

			#<?php echo esc_attr( $dialog_box_id ); ?>  .everestthemes-stats-feedback-header img {
				width: 50px;
				height: 50px;
			}
			#<?php echo esc_attr( $dialog_box_id ); ?>  .everestthemes-stats-feedback-header strong {
				font-size: 18px;
				font-weight: 600;
				line-height: 1.2;
			}

			#<?php echo esc_attr( $dialog_box_id ); ?> form {
				display: flex;
				flex-direction: column;
				gap: 14px;
			}

			#<?php echo esc_attr( $dialog_box_id ); ?> form input[type=text] {
				margin-top: 10px;
				width: 100%;
			}

			#<?php echo esc_attr( $dialog_box_id ); ?> .everestthemes-stats-feedback-btn-wrapper {
				display: flex;
				align-items: center;
				justify-content: flex-start;
				gap: 20px;
				margin-top: 10px;
			}

			#<?php echo esc_attr( $dialog_box_id ); ?> .everestthemes-stats-feedback-btn-wrapper button {
				border: none;
				border-radius: 5px;
				padding: 10px 26px;
				cursor: pointer;
				transition: all 0.2s ease-in-out;
			}
			#<?php echo esc_attr( $dialog_box_id ); ?> .everestthemes-stats-feedback-btn-wrapper button[type=submit] {
				background: green;
				color: #ffffff;
			}
			#<?php echo esc_attr( $dialog_box_id ); ?> .everestthemes-stats-feedback-btn-wrapper button[type=button] {
				border: 1px solid;
				background: transparent;
			}

			#<?php echo esc_attr( $dialog_box_id ); ?> .everestthemes-stats-feedback-btn-wrapper button:hover {
				opacity: 0.7;
			}

		</style>
		<dialog id="<?php echo esc_attr( $dialog_box_id ); ?>">

			<div class="everestthemes-stats-feedback-header">
				<?php if ( $this->logo_url ) { ?>
					<img src="<?php echo esc_url( add_query_arg( 't', time(), $this->logo_url ) ); ?>" onerror="this.remove()">
				<?php } ?>
				<strong><?php esc_html_e( 'Quick Feedback' ); ?></strong>
				<a class="close-deactivate-feedback-popup"><span class="dashicons dashicons-no-alt"></span></a>
			</div>

			<form method="post">
				<?php
				if ( ! empty( $deactivate_reasons ) && is_array( $deactivate_reasons ) ) {
					foreach ( $deactivate_reasons as $index => $deactivate_reason ) {

						$field_id = "everestthemes-stats-{$this->plugin_data['TextDomain']}-{$index}";

						?>
						<div class="everestthemes-stats-input-wrapper">
							<input required id="<?php echo esc_attr( "{$field_id}-radio" ); ?>" type="radio" name="everestthemes_stats_<?php echo esc_attr( $this->plugin_data['TextDomain'] ); ?>_feedback[title]" value="<?php echo esc_attr( $deactivate_reason['title'] ); ?>">
							<label for="<?php echo esc_attr( "{$field_id}-radio" ); ?>"><?php echo esc_attr( $deactivate_reason['title'] ); ?></label>

							<?php
							if ( ! empty( $deactivate_reason['input_placeholder'] ) ) {
								?>
								<input
									id="<?php echo esc_attr( "{$field_id}-input" ); ?>"
									type="text"
									name="everestthemes_stats_<?php echo esc_attr( $this->plugin_data['TextDomain'] ); ?>_feedback[input][<?php echo esc_attr( md5( $deactivate_reason['title'] ) ); ?>]"
									placeholder="<?php echo esc_attr( $deactivate_reason['input_placeholder'] ); ?>"
									value=""
									autocomplete="off"
									>
								<?php
							}
							?>
						</div>
						<?php
					}
				}
				?>

				<div class="everestthemes-stats-feedback-btn-wrapper">
					<?php wp_nonce_field( "everestthemes_stats_{$this->plugin_data['TextDomain']}_nonce", "everestthemes_stats_{$this->plugin_data['TextDomain']}_nonce" ) ?>
					<input type="hidden" name="<?php echo esc_attr( "everestthemes_stats_{$this->plugin_data['TextDomain']}_deactivation_link" ); ?>" value="">
					<button type="submit"><?php esc_html_e( 'Submit &amp; Deactivate' ); ?></button>
					<button type="button" id="skip-feedback"><?php echo esc_html_e( 'Skip &amp; Deactivate' ); ?></button>
				</div>
			</form>
		</dialog>
		<script>
			(function() {
				const deactivateBtn = document.getElementById('deactivate-<?php echo esc_attr( $this->plugin_data['TextDomain'] ); ?>');

				if (!deactivateBtn) {
					return;
				}

				const dialogBox = document.getElementById('<?php echo esc_attr( $dialog_box_id ); ?>');
				const dialogCloseBtn = dialogBox.querySelector('.everestthemes-stats-feedback-header .close-deactivate-feedback-popup');
				const skipFeedbackBtn = dialogBox.querySelector('form #skip-feedback');

				deactivateBtn.addEventListener("click", function(e) {
					e.preventDefault();
					dialogBox.showModal();
					document.querySelector('input[name="<?php echo esc_attr( "everestthemes_stats_{$this->plugin_data['TextDomain']}_deactivation_link" ); ?>"]').setAttribute("value", deactivateBtn.getAttribute('href'));
				});

				dialogCloseBtn.addEventListener("click", function() {
					dialogBox.close();
				});

				skipFeedbackBtn.addEventListener('click', function() {
					window.location.href = deactivateBtn.getAttribute('href');
				});

			}());
		</script>
		<?php
	}

	protected function is_localhost() {
		$whitelist   = array( '127.0.0.1', '::1' );
		$remote_addr = ! empty( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		if ( in_array( $remote_addr, $whitelist, true ) ) {
			return true;
		}
	}

	private function get_timezone() {

		if ( function_exists( 'wp_timezone_string' ) ) {
			return wp_timezone_string();
		}

		$timezone_string = get_option( 'timezone_string' );

		if ( $timezone_string ) {
			return $timezone_string;
		}

		$offset  = (float) get_option( 'gmt_offset' );
		$hours   = (int) $offset;
		$minutes = ( $offset - $hours );

		$sign      = ( $offset < 0 ) ? '-' : '+';
		$abs_hour  = abs( $hours );
		$abs_mins  = abs( $minutes * 60 );
		$tz_offset = sprintf( '%s%02d:%02d', $sign, $abs_hour, $abs_mins );

		return $tz_offset;
	}

	private function get_installed_themes() {
		$themes = wp_get_themes();

		$installed_themes = array();

		if ( ! empty( $themes ) && is_array( $themes ) ) {
			foreach ( $themes as $theme ) {
				$installed_themes[] = array(
					'name'    => $theme->name,
					'version' => $theme->version,
				);
			}
		}

		return $installed_themes;
	}

	private function get_active_plugins() {
		$active_plugins = array();

		if ( ! empty( $this->plugins ) && is_array( $this->plugins ) ) {
			foreach ( $this->plugins as $plugin_slug => $plugin ) {
				if ( is_plugin_active( $plugin_slug ) ) {
					$active_plugins[ $plugin_slug ] = array(
						'name'    => $plugin['Name'],
						'version' => $plugin['Version'],
					);
				}
			}
		}

		return $active_plugins;
	}

	private function get_paused_plugins() {
		$paused_plugins = array();

		if ( ! empty( $this->plugins ) && is_array( $this->plugins ) ) {
			foreach ( $this->plugins as $plugin_slug => $plugin ) {
				if ( is_plugin_inactive( $plugin_slug ) ) {
					$paused_plugins[ $plugin_slug ] = array(
						'name'    => $plugin['Name'],
						'version' => $plugin['Version'],
					);
				}
			}
		}

		return $paused_plugins;
	}

	protected function get_data() {
		global $wpdb;

		$theme       = wp_get_theme();
		$admin_email = get_bloginfo( 'admin_email' );
		$userdata    = get_user_by( 'email', $admin_email );

		return array(
			'timestamp'        => time(),
			'website_url'      => get_bloginfo( 'url' ),
			'first_name'       => $userdata->first_name,
			'last_name'        => $userdata->last_name,
			'admin_email'      => $admin_email,
			'active_plugins'   => $this->get_active_plugins(),
			'paused_plugins'   => $this->get_paused_plugins(),
			'active_theme'     => $theme->name,
			'installed_themes' => $this->get_installed_themes(),
			'timezone'         => $this->get_timezone(),
			'wp_version'       => get_bloginfo( 'version' ),
			'php_version'      => phpversion(),
			'is_multisite'     => is_multisite(),
			'multisite_counts' => function_exists( 'get_blog_count' ) ? (int) get_blog_count() : 1,
			'is_wp_com'        => defined( 'IS_WPCOM' ) && IS_WPCOM,
			'is_wp_com_vip'    => ( defined( 'WPCOM_IS_VIP_ENV' ) && WPCOM_IS_VIP_ENV ) || ( function_exists( 'wpcom_is_vip' ) && wpcom_is_vip() ),
			'server_engine'    => ( ! empty( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : 'N/A' ),
			'db_version'       => $wpdb->db_version(),
		);
	}

}

