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
 * Manages Formidable turnstile integration.
 *
 * @since 1.0.1
 */
final class Formidable {

	/**
	 * Contains turnstile context id.
	 *
	 * @var number
	 */
	private $turnstile_context_id;

	/**
	 * Contains HTML for rendering the content.
	 *
	 * @var mixed
	 */
	private $html;

	/**
	 * Contains HTML Tags ALlowed
	 *
	 * @var mixed
	 */
	private $allowed_tags;

	/**
	 * Placement of ECT widget
	 *
	 * @var mixed
	 */
	private $ect_placement;

		/**
		 * Return Multiple form ID
		 *
		 * @var array|mixed
		 */
	private $form_ids;

	/**
	 * The Plugin
	 *
	 * @var array
	 */
	private $plugin;

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
		if ( ! function_exists( 'load_formidable_forms' ) || ! wp_turnstile()->settings->get( 'site_key' ) || ! wp_turnstile()->settings->get( 'secret_key' ) ) {
			return;
		}

		$this->plugin = wp_turnstile()->integrations->get( 'formidable' );

		if ( ! wp_validate_boolean( $this->plugin ) ) {
			return;
		}

		$this->turnstile_context_id = wp_rand();
		$this->form_ids = [];
		$this->form_counter = 0;

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 20 );
		add_filter( 'frm_validate_entry', [ $this, 'verify' ], 20, 2 );
		add_filter( 'frm_submit_button_html', [ $this, 'render_turnstile_field' ], 10, 2 );
	}

	/**
	 * Turnstile integration shortcode.
	 *
	 * @param  mixed $button Formidable Submit Button.
	 * @param  array $args Getting arguments of Form.
	 * @return mixed|html The ongoing request flow.
	 */
	public function render_turnstile_field( $button, $args )
	{
		if ( $args['form']->id ) {
			// Get Disable ID Option.
			$disabled_ids = ! empty( get_option( 'ect_disabled_ids' ) ) ? get_option( 'ect_disabled_ids' ) : '';
			$formidable_id_disable = ( isset( $disabled_ids['formidable'] ) && ! empty( $disabled_ids['formidable'] ) ) ? sanitize_text_field( $disabled_ids['formidable'] ) : '';
			$disable_id = explode( ',', $formidable_id_disable );
			if ( in_array( $args['form']->id, $disable_id, true ) ) {
				return $button;
			}

			$this->form_counter++;
			$form_id = $this->form_counter;
			$this->form_ids[] = $form_id;
			$this->html = sprintf(
				'<div class="ect-turnstile-container" id="ect-formidable-turnstile-container-%s-%s" data-sitekey="%s" data-theme="%s" data-submit-button="%s" data-retry-interval="1000" data-action="%s", data-size="%s"></div>',
				$this->turnstile_context_id,
				$form_id,
				wp_turnstile()->settings->get( 'site_key' ),
				wp_turnstile()->settings->get( 'theme', 'light' ),
				esc_attr( wp_validate_boolean( wp_turnstile()->settings->get( 'button_access', 'false' ) ) ),
				'formidable-' . $form_id,
				'normal'
			);

			if ( wp_validate_boolean( wp_turnstile()->settings->get( 'button_access', 'false' ) ) ) {
				$this->html .= '<style>
				.frm_forms .frm_button_submit{
					pointer-events: none;
					opacity: .5;
				}
			</style>';
			}

			$this->allowed_tags = wp_kses_allowed_html( 'post' );
			$this->allowed_tags['style'] = $this->allowed_tags;
			$this->enqueue_scripts( $form_id );
			// GET Placement Option.
			$this->ect_placement = get_option( 'ect_placement' );
			$this->ect_placement = ( isset( $this->ect_placement ) && is_array( $this->ect_placement ) ) ? sanitize_text_field( $this->ect_placement['formidable'] ) : '';
			if ( $this->ect_placement ) {
				if ( 'before' === $this->ect_placement ) {
					return wp_kses( $this->html, $this->allowed_tags ) . $button;
				} elseif ( 'after' === $this->ect_placement ) {
					return $button . wp_kses( $this->html, $this->allowed_tags );
				}
			} else {
				return wp_kses( $this->html, $this->allowed_tags ) . $button;
			}
		}
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
			'ect-formidable-turnstile-challenges',
			'//challenges.cloudflare.com/turnstile/v0/api.js?onload=ectFormidableCb',
			[],
			wp_turnstile()->api_version,
			true
		);

		$site_key = wp_turnstile()->settings->get( 'site_key' );
		if ( $this->form_ids ) {
			$script = 'window.ectFormidableCb = function () { ';
			foreach ( $this->form_ids as $form_id ) {
				$script .= "turnstile.render('#ect-formidable-turnstile-container-{$this->turnstile_context_id}-{$form_id}', {
					sitekey: '" . esc_attr( $site_key ) . "',
					callback: function(token) {                      
						var forms = document.querySelectorAll('.frm_forms .frm_button_submit');
                        forms.forEach(function(form){
							form.style.pointerEvents = 'auto';
							form.style.opacity = '1';
						});
					}
				});";
			}

			$script .= '};';
			wp_add_inline_script( 'ect-formidable-turnstile-challenges', $script, 'before' );
			wp_enqueue_script( 'ect-formidable-turnstile-challenges' );
		}
	}

	/**
	 * Verify contact form submit submissions.
	 *
	 * @param  array $errors The error value on submission.
	 * @return mixed Ongoing request flow.
	 */
	public function verify( $errors )
	{
		if ( ! isset( $_POST['cf-turnstile-response'] ) ) { //phpcs:ignore
			return;
		}

		$token = sanitize_text_field( $_POST['cf-turnstile-response'] ); // phpcs:ignore.
		$response = wp_turnstile()->helpers->validate_turnstile( $token );
		if ( ! ( isset( $response['success'] ) && wp_validate_boolean( $response['success'] ) ) ) {
			$error_code = $response['error-codes'][0] ?? null;
			$message    = wp_turnstile()->common->get_error_message( $error_code );
			$errors['my_error'] = $message;
		}

		return $errors;
	}
}