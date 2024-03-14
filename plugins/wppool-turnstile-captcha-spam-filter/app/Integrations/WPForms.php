<?php
/**
 * Main plugin class.
 *
 * @since   1.0.0
 * @package EasyCloudflareTurnstile
 */

namespace EasyCloudflareTurnstile\Integrations;

// if direct access than exit the file.
defined( 'ABSPATH') || exit;

/**
 * Manages WPForms turnstile integration.
 *
 * @since 1.0.1
 */
class WPForms {



	/**
	 * Contains turnstile context id.
	 *
	 * @var number
	 */
	private $turnstile_context_id;

	/**
	 * The Array of Allowed Tags
	 *
	 * @var array
	 */
	private $allowed_tags;

	/**
	 * The Plugin
	 *
	 * @var array
	 */
	private $plugin;

	/**
	 * Return Multiple form ID
	 *
	 * @var array|mixed
	 */
	private $form_ids;

	/**
	 * Counting the Form
	 *
	 * @var int
	 */
	private $form_counter;


	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		if ( ! class_exists( 'WPForms_Process') || ! wp_turnstile()->settings->get( 'site_key') || ! wp_turnstile()->settings->get( 'secret_key' ) ) {
			return;
		}

		$this->plugin = wp_turnstile()->integrations->get( 'wpforms' );

		if ( ! wp_validate_boolean( $this->plugin )) {
			return;
		}

		$this->turnstile_context_id = wp_rand();
		$this->form_ids = [];
		$this->form_counter = 0;

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wpforms_display_fields_after', [ $this, 'render' ] );
		add_filter( 'wpforms_process_complete', [ $this, 'verify' ], 10, 3 );
	}

	/**
	 * Enqueue turnstile challenges script on the footer.
	 *
	 * @param  integrar $form_id ID for every form.
	 *
	 * @return void
	 */
	public function enqueue_scripts( $form_id )
	{
		wp_register_script(
			'ect-wpf-turnstile-challenges',
			'//challenges.cloudflare.com/turnstile/v0/api.js?onload=ectWPfTurnstileCb',
			[],
			wp_turnstile()->api_version,
			true
		);

		$site_key = wp_turnstile()->settings->get( 'site_key' );
		if ( $this->form_ids ) {
			$script = 'window.ectWPfTurnstileCb = function () { ';
			foreach ( $this->form_ids as $form_id ) {
				$script .= "turnstile.render('#ect-wpforms-turnstile-container-{$this->turnstile_context_id}-{$form_id}', {
					sitekey: '" . esc_attr( $site_key ) . "',
					callback: function(token) {
						var forms = document.querySelectorAll('.wpforms-submit');
						  forms.forEach(function(form){
							form.style.pointerEvents = 'auto';
							form.style.opacity = '1';
						  });
					}
		});";
			}

			$script .= '};';

			wp_add_inline_script( 'ect-wpf-turnstile-challenges', $script, 'before' );
			wp_enqueue_script( 'ect-wpf-turnstile-challenges' );
		}
	}

	/**
	 * Verify contact form submit submissions.
	 *
	 * @since 1.0.2
	 *
	 * @param array $fields    Form fields.
	 * @param array $entry     Form submission raw data.
	 * @param array $form_data Form related data.
	 * @return mixed           Ongoing request flow.
	 */
	public function verify( $fields, $entry, $form_data ) 	{ // phpcs:ignore
		$message = wp_turnstile()->settings->get( 'error_msg', __( 'Please verify you are human', 'wppool-turnstile' ) );

		if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST['cf-turnstile-response'])) { // phpcs:ignore
			$token    = sanitize_text_field($_POST['cf-turnstile-response']); // phpcs:ignore
			$response = wp_turnstile()->helpers->validate_turnstile( $token );

			if ( ! ( isset( $response['success'] ) && wp_validate_boolean( $response['success'] ) ) ) {
				$error_code = $response['error-codes'][0] ?? null;
				$message   = wp_turnstile()->common->get_error_message( $error_code );
				wpforms()->process->errors[ $form_data['id'] ]['header'] = $message;
			}
		} else {
			wpforms()->process->errors[ $form_data['id'] ]['header'] = $message;
		}
	}

	/**
	 * Turnstile integration shortcode.
	 *
	 * @since 1.0.2
	 */
	public function render()
	{
		$this->form_counter++;
		$form_id = $this->form_counter;
		$this->form_ids[] = $form_id;

		$html = sprintf(
			'<div class="ect-turnstile-container" id="ect-wpforms-turnstile-container-%s-%s" data-sitekey="%s" data-theme="%s" data-submit-button="%s" data-size="%s"></div>',
			$this->turnstile_context_id,
			$form_id,
			esc_attr( wp_turnstile()->settings->get( 'site_key' ) ),
			esc_attr( wp_turnstile()->settings->get( 'theme', 'light' ) ),
			esc_attr( wp_validate_boolean( wp_turnstile()->settings->get( 'button_access', 'false')) ),
			'normal'
		);

		if (wp_validate_boolean( wp_turnstile()->settings->get( 'button_access', 'false'))) {
			$html .= '<style>
				.wpforms-submit {
					pointer-events: none;
					opacity: .5;
				}
			</style>';
		}

		$this->allowed_tags = wp_kses_allowed_html( 'post');
		$this->allowed_tags['style'] = $this->allowed_tags;
		$this->enqueue_scripts( $form_id);

		echo wp_kses( $html, $this->allowed_tags);
	}
}