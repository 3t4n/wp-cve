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
 * Manages Jetpack turnstile integration.
 *
 * @since 1.0.1
 */
class Jetpack {


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
		if ( ! class_exists( 'ZeroBSCRM' ) || ! wp_turnstile()->settings->get( 'site_key' ) || ! wp_turnstile()->settings->get( 'secret_key' ) ) {
			return;
		}

		$this->plugin = wp_turnstile()->integrations->get( 'jetpack' );
		if ( ! wp_validate_boolean( $this->plugin ) ) {
			return;
		}

		if ( wp_validate_boolean( $this->plugin ) ) {
			$this->turnstile_context_id = wp_rand();
			$this->form_ids = [];
			$this->form_counter = 0;

			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
			add_action( 'wp_footer', [ $this, 'render' ] );
			add_filter( 'jetpack_form_verify', [ $this, 'verify' ] );
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
			'ect-jetpack-turnstile-challenges',
			'//challenges.cloudflare.com/turnstile/v0/api.js?onload=ectJetPackFormsTurnstileCb',
			[],
			wp_turnstile()->api_version,
			true
		);

		if ( $this->form_ids ) {
			$site_key = wp_turnstile()->settings->get( 'site_key' );
				$script = 'jQuery(document).ready(function($) {
				window.ectJetPackFormsTurnstileCb = function () { ';
			foreach ( $this->form_ids as $form_id ) {
				$script .= "turnstile.render('#ect-jetpack-turnstile-container-{$this->turnstile_context_id}-{$form_id}', {
					sitekey: '" . esc_attr( $site_key ) . "',
					callback: function(token) {
						var submitBtn = document.querySelectorAll('.zbsFormWrap input[type=\"submit\"]');
                        submitBtn.forEach(function(submit){
							submit.style.pointerEvents = 'auto';
							submit.style.opacity = '1';
						});
					}
				});";
			}
				$script .= '}});';

				wp_add_inline_script( 'ect-jetpack-turnstile-challenges', $script, 'before' );
				wp_enqueue_script( 'ect-jetpack-turnstile-challenges' );
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
				'<div class="ect-turnstile-container" id="ect-jetpack-turnstile-container-%s-%s" data-sitekey="%s" data-theme="%s" data-submit-button="%s" data-size="%s"></div>',
				$this->turnstile_context_id,
				$form_id,
				esc_attr( wp_turnstile()->settings->get( 'site_key' ) ),
				esc_attr( wp_turnstile()->settings->get( 'theme', 'light' ) ),
				esc_attr( wp_validate_boolean( wp_turnstile()->settings->get( 'button_access', 'false' ) ) ),
				'normal'
			);

		if ( wp_validate_boolean( wp_turnstile()->settings->get( 'button_access', 'false' ) ) ) {
			$html .= '<style>
				.zbsFormWrap input[type="submit"]{
					pointer-events: none;
					opacity: .5;
				}
				#ect-jetpack-turnstile-container-' . $this->turnstile_context_id . '-' . $form_id . '{
					text-align:left;
					margin: 5px auto;
				}
			</style>';
		}

			$this->allowed_tags = wp_kses_allowed_html( 'post' );
			$this->allowed_tags['style'] = $this->allowed_tags;
			$this->enqueue_scripts( $form_id );
		?>
<script>
	jQuery(document).ready(function($) {
		jetpackFormSubmit = $('.zbsFormWrap .send');
		if (jetpackFormSubmit.length) {
			var htmlContent = `<?php echo wp_kses( $html, $this->allowed_tags ); ?>`;
			jetpackFormSubmit.before(htmlContent);
		}
	});
</script>
			<?php
	}

	/**
	 * Verify Jetpack submit submissions.
	 *
	 * @return mixed Ongoing request flow.
	 */
	public function verify() { 		if (isset($this->plugin) && !isset($_POST['cf-turnstile-response'])) { //phpcs:ignore
			return;
	}
	$errors = [];
	$token = sanitize_text_field($_POST['cf-turnstile-response']); // phpcs:ignore.
		$response = wp_turnstile()->helpers->validate_turnstile( $token );
	if ( ! ( isset( $response['success'] ) && wp_validate_boolean( $response['success'] ) ) ) {
		$error_code = isset( $response['error-codes'][0] ) ? $response['error-codes'][0] : null;
		$message    = wp_turnstile()->common->get_error_message( $error_code );
		$errors['jetpack'] = $message;
		if ( isset( $errors ) && ! empty( $errors ) ) {
			return $errors;
		}
	}
	}
}