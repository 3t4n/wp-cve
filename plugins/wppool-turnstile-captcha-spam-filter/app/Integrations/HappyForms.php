<?php
/**
 * Main plugin class.
 *
 * @since   1.0.0
 * @package EasyCloudflareTurnstile
 */

namespace EasyCloudflareTurnstile\Integrations;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Manages HappyForms turnstile integration.
 *
 * @since 1.0.1
 */
final class HappyForms {

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
		if ( ! function_exists( 'HappyForms' ) || ! wp_turnstile()->settings->get( 'site_key' ) || ! wp_turnstile()->settings->get( 'secret_key' ) ) {
			return;
		}

		$this->plugin = wp_turnstile()->integrations->get( 'happyforms' );

		if ( ! wp_validate_boolean( $this->plugin ) ) {
			return;
		}

		$this->turnstile_context_id = wp_rand();
		$this->form_ids = [];
		$this->form_counter = 0;

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'happyforms_form_submit_before', [ $this, 'render' ] );
		add_filter( 'happyforms_parts_after', [ $this, 'verify' ], 20 );
	}

	/**
	 * Enqueue turnstile challenges script on the footer.
	 *
	 *  @param  integrar $form_id ID for every form.
	 *
	 * @return void
	 */
	public function enqueue_scripts( $form_id )
	{
			wp_register_script(
				'ect-happyforms-turnstile-challenges',
				'//challenges.cloudflare.com/turnstile/v0/api.js?onload=ectHappyFormsTurnstileCb',
				[],
				wp_turnstile()->api_version,
				true
			);

			$site_key = wp_turnstile()->settings->get( 'site_key' );
		if ( $this->form_ids ) {
			$script = 'window.ectHappyFormsTurnstileCb = function () { ';
			foreach ( $this->form_ids as $form_id ) {
				$script .= "turnstile.render('#ect-happyforms-turnstile-container-{$this->turnstile_context_id}-{$form_id}', {
					sitekey: '" . esc_attr( $site_key ) . "',
					callback: function(token) {
						var submitBtn = document.querySelectorAll('.happyforms-form .happyforms-part--submit .happyforms-submit');
                        submitBtn.forEach(function(submit){
							submit.style.pointerEvents = 'auto';
							submit.style.opacity = '1';
						});
					}
				});";
			}

			$script .= '};';

			wp_add_inline_script( 'ect-happyforms-turnstile-challenges', $script, 'before' );
			wp_enqueue_script( 'ect-happyforms-turnstile-challenges' );
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
			'<div class="ect-turnstile-container" id="ect-happyforms-turnstile-container-%s-%s" data-sitekey="%s" data-theme="%s" data-submit-button="%s" data-size="%s"></div>',
			$this->turnstile_context_id,
			$form_id,
			esc_attr( wp_turnstile()->settings->get( 'site_key' ) ),
			esc_attr( wp_turnstile()->settings->get( 'theme', 'light' ) ),
			esc_attr( wp_validate_boolean( wp_turnstile()->settings->get( 'button_access', 'false' ) ) ),
			'normal'
		);

		if ( wp_validate_boolean( wp_turnstile()->settings->get( 'button_access', 'false' ) ) ) {
			$html .= '<style>
				.happyforms-form .happyforms-part--submit .happyforms-submit {
					pointer-events: none;
					opacity: .5;
				}

                .happyforms-part--submit{
                    display:flex;
                    flex-direction:column;
                    justify-content:flex-start;
                    align-items:start;
                }
			</style>';
		}

		$this->allowed_tags = wp_kses_allowed_html( 'post' );
		$this->allowed_tags['style'] = $this->allowed_tags;
		$this->enqueue_scripts( $form_id );
		echo wp_kses( $html, $this->allowed_tags ) . '<br />';
	}


	/**
	 * Verify Happyform submit submissions.
	 *
	 * @return mixed Ongoing request flow.
	 */
	public function verify()
	{
		if ( ! isset( $_POST['cf-turnstile-response'] ) ) { //phpcs:ignore
			return;
		}
		$token = sanitize_text_field( $_POST['cf-turnstile-response'] ); // phpcs:ignore.
		$response = wp_turnstile()->helpers->validate_turnstile( $token );
		if ( ! ( isset( $response['success'] ) && wp_validate_boolean( $response['success'] ) ) ) {
			$error_code = $response['error-codes'][0] ?? null;
			$message    = wp_turnstile()->common->get_error_message( $error_code );
			$errors['happyforms'] = $message;
		}

		return $errors;
	}
}