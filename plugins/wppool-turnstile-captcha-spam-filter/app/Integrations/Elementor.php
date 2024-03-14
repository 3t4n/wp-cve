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
 * Manages Elementor turnstile integration.
 *
 * @since 1.0.1
 */
class Elementor {



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
		if ( ! wp_turnstile()->settings->get( 'site_key' ) || ! wp_turnstile()->settings->get( 'secret_key' ) ) {
			return;
		}

		$this->plugin = wp_turnstile()->integrations->get( 'elementor' );

		if ( ! wp_validate_boolean( $this->plugin ) ) {
			return;
		}

		$this->turnstile_context_id = wp_rand();
		$this->form_ids = [];
		$this->form_counter = 0;

		add_action( 'elementor/widget/render_content', [ $this, 'easy_turnstile_elementor_form' ], 10, 2 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'elementor_pro/forms/validation', [ $this, 'verify' ], 10, 2 );
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
				'ect-elementor-turnstile-challenges',
				'//challenges.cloudflare.com/turnstile/v0/api.js?onload=ectElementorTurnstileCb',
				[],
				wp_turnstile()->api_version,
				true
			);

			$site_key = wp_turnstile()->settings->get( 'site_key' );
		if ( $this->form_ids ) {
			$script = 'window.ectElementorTurnstileCb = function () { ';
			foreach ( $this->form_ids as $form_id ) {
				$script .= "turnstile.render('#ect-turnstile-container-{$this->turnstile_context_id}-{$form_id}', {
						sitekey: '" . esc_attr( $site_key ) . "',
						callback: function(token) {
							var forms = document.querySelectorAll('.elementor-field-type-submit button[type=submit]');
							if(forms){
								forms.forEach(function(form){
									form.style.pointerEvents = 'auto';
									form.style.opacity = '1';
								});
							}

							var submitBtn = document.querySelectorAll('.elementor-field-type-submit');
							if(submitBtn){
								submitBtn.forEach(function(submit){
									submit.style.display = 'block';
								});
							}
						}			
					});";
			}
			$script .= '};';
			wp_add_inline_script( 'ect-elementor-turnstile-challenges', $script, 'footer' );
			wp_enqueue_script( 'ect-elementor-turnstile-challenges' );
		}
	}

	/**
	 * Render Elementor form submit submissions.
	 *
	 * @param mixed|html $content total form DOM element.
	 * @param object     $widget    form objects.
	 * @return mixed|html render the total form
	 */
	public function easy_turnstile_elementor_form( $content, $widget )
	{
		$attr = [ 'form', 'login' ];
		if ( in_array( $widget->get_name(), $attr, true ) ) {
			$this->form_counter++;
			$form_id = $this->form_counter;
			$this->form_ids[] = $form_id;
			$html = sprintf(
				'<div class="ect-turnstile-container" id="ect-turnstile-container-%s-%s" data-sitekey="%s" data-theme="%s" data-submit-button="%s" data-retry="auto" data-retry-interval="1000" data-action="%s" data-size="%s"></div>',
				$this->turnstile_context_id,
				$form_id,
				esc_attr( wp_turnstile()->settings->get( 'site_key' ) ),
				esc_attr( wp_turnstile()->settings->get( 'theme', 'light' ) ),
				esc_attr( wp_validate_boolean( wp_turnstile()->settings->get( 'button_access', 'false' ) ) ),
				'elementor-' . $form_id,
				'normal',
			);

			if ( wp_validate_boolean( wp_turnstile()->settings->get( 'button_access', 'false' ) ) ) {
				$html .= '<style>.elementor-field-type-submit button[type=submit]{ pointer-events: none; opacity: .5 ;}
				</style>';
			}
			$this->allowed_tags = wp_kses_allowed_html( 'post' );
			$this->allowed_tags['style'] = $this->allowed_tags;
			$this->enqueue_scripts( $form_id );
			// matches with Button submit of content.
			$pattern = '/(<button[^>]*type="submit"[^>]*>.*?<\/button>)/is';
			if ( preg_match( $pattern, $content, $matches ) ) {
				$submit_button = $matches[0];
				$content = str_replace( $submit_button, wp_kses( $html, $this->allowed_tags ) . '' . $submit_button, $content );
			}
			return $content;
		} else {
			return $content;
		}
	}


	/**
	 * Verify Elementor form submissions.
	 *
	 * @since 2.1.0
	 *
	 * @param object $record Form fields.
	 * @param object $ajax_handler Form related data.
	 * @return mixed verification of the form.
	 */
	public function verify( $record, $ajax_handler ) 	{ // phpcs:ignore.
		$message  = wp_turnstile()->settings->get( 'error_msg', __( 'Please verify you are human', 'wppool-turnstile' ) );
		$fields = $record->get_field([
			'id' => 'ticket_id',
		]);
		if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['cf-turnstile-response']) && !empty($_POST['cf-turnstile-response'])) { // phpcs:ignore
			$token    = sanitize_text_field($_POST['cf-turnstile-response']); // phpcs:ignore
			$response = wp_turnstile()->helpers->validate_turnstile( $token );

			if ( ! ( isset( $response['success'] ) && wp_validate_boolean( $response['success'] ) ) ) {
				$field = current( $fields );
				$error_code = $response['error-codes'][0] ?? null;
				$message   = wp_turnstile()->common->get_error_message( $error_code );
				$ajax_handler->add_error_message( $message );
				$ajax_handler->add_error( $field['id'], esc_html__( 'Invalid Turnstile.', 'wp-turnstile' ) );
				$ajax_handler->is_success = false;
			}
		} else {
			$ajax_handler->add_error_message( $message );
			$ajax_handler->add_error( 'invalid_turnstile', $message );
			$ajax_handler->is_success = false;
		}
	}
}