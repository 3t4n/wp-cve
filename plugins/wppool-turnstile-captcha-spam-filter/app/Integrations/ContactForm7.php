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
 * Manages ContactForm7 turnstile integration.
 *
 * @since 1.0.1
 */
class ContactForm7 {

	/**
	 * Contains turnstile context id.
	 *
	 * @var number
	 */
	private $turnstile_context_id;

	/**
	 * Form Name
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Plugin Variable
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
		if ( ! class_exists( 'WPCF7_Submission' ) || ! wp_turnstile()->settings->get( 'site_key' ) || ! wp_turnstile()->settings->get( 'secret_key' ) ) {
			return;
		}

		$this->plugin = wp_turnstile()->integrations->get( 'cf7' );
		$this->name = wp_turnstile()->integrations->get_name( 'cf7' );
		$this->form_ids = [];
		$this->form_counter = 0;

		if ( ! wp_validate_boolean( $this->plugin ) ) {
			return;
		}

		$this->turnstile_context_id = wp_rand();

		add_filter( 'wpcf7_form_elements', [ $this, 'ect_wpcf7_form_elements' ] );
		add_shortcode( 'easy_cloudflare_turnstile', [ $this, 'shortcode' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_filter( 'wpcf7_validate', [ $this, 'verify' ], 20, 2 );
		add_action( 'wpcf7_init', [ $this, 'add_form_tag_button' ], 10, 0 );
		add_action( 'wpcf7_admin_init', [ $this, 'add_tag_generator_button' ], 55, 0 );
	}

	/**
	 * Display shortcode inside cf7 form.
	 *
	 * @since  1.0.1
	 * @param  html $form Form markup.
	 * @return html
	 */
	public function ect_wpcf7_form_elements( $form )
	{
		$form = do_shortcode( $form );
		return $form;
	}

	/**
	 * Turnstile integration shortcode.
	 *
	 * @param  mixed $atts Shortcode attributes.
	 * @return mixed|html
	 */
	public function shortcode( $atts ) { // phpcs:ignore
		$this->form_counter++;
		$form_id = $this->form_counter;
		$this->form_ids[] = $form_id;

		$html = sprintf(
			'<div class="ect-turnstile-container" id="ect-cf-turnstile-container-%s-%s" data-sitekey="%s" data-theme="%s" data-submit-button="%s" data-size="%s"></div>',
			$this->turnstile_context_id,
			$form_id,
			wp_turnstile()->settings->get( 'site_key' ),
			wp_turnstile()->settings->get( 'theme', 'light' ),
			esc_attr( wp_validate_boolean( wp_turnstile()->settings->get( 'button_access', 'false' ) ) ),
			'normal',
		);

		$this->enqueue_scripts( $form_id );

		if ( wp_validate_boolean( wp_turnstile()->settings->get( 'button_access', 'false' ) ) ) {
			$html .= '<style>
				.wpcf7-form-control.wpcf7-submit {
					pointer-events: none;
					opacity: .5;
				}
			</style>';
		}

		return $html;
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
			'ect-cf7-turnstile-challenges',
			'//challenges.cloudflare.com/turnstile/v0/api.js?onload=ectCf7Cb',
			[],
			wp_turnstile()->api_version,
			true
		);

		$site_key = wp_turnstile()->settings->get( 'site_key' );
		if ( $this->form_ids ) {
			$script = 'window.ectCf7Cb = function () { ';
			foreach ( $this->form_ids as $form_id ) {
				$script .= "turnstile.render('#ect-cf-turnstile-container-{$this->turnstile_context_id}-{$form_id}', {
						sitekey: '" . esc_attr( $site_key ) . "',
						callback: function(token) {
							var forms = document.querySelectorAll('.wpcf7-form-control.wpcf7-submit');
							forms.forEach(function(form){
								form.style.pointerEvents = 'auto';
								form.style.opacity = '1';
							});
						}
					});";
			}
			$script .= '}';

			wp_add_inline_script( 'ect-cf7-turnstile-challenges', $script, 'before' );
			wp_enqueue_script( 'ect-cf7-turnstile-challenges' );
		}
	}

	/**
	 * Verify contact form submit submissions.
	 *
	 * @param  mixed $request The submit request.
	 * @return mixed          Ongoing request flow.
	 */
	public function verify( $request )
	{
		if ( ! class_exists( 'WPCF7_Submission' ) ) {
			return $request;
		}

		// phpcs:ignore
		$_wpcf7 = ! empty( $_POST['_wpcf7'] ) ? absint( $_POST['_wpcf7'] ) : 0;

		if ( empty( $_wpcf7 ) ) {
			return $request;
		}

		$submission = \WPCF7_Submission::get_instance();
		$data       = $submission->get_posted_data();

		$cf7_text = do_shortcode( '[contact-form-7 id="' . $_wpcf7 . '"]' );
		$site_key = wp_turnstile()->settings->get( 'site_key' );

		if ( false === strpos( $cf7_text, $site_key ) ) {
			return $request;
		}

		$message = wp_turnstile()->settings->get( 'error_msg', __( 'Please verify you are human', 'wppool-turnstile' ) );

		if ( empty( $data['cf-turnstile-response'] ) ) {
			$request->invalidate([
				'type' => 'turnstile',
				'name' => 'wppool-turnstile',
			], $message);

			return $request;
		}

		$token    = $data['cf-turnstile-response'];
		$response = wp_turnstile()->helpers->validate_turnstile( $token );

		if ( ! ( isset( $response['success'] ) && wp_validate_boolean( $response['success'] ) ) ) {
			$error_code = $response['error-codes'][0] ?? null;
			$message    = wp_turnstile()->common->get_error_message( $error_code );

			$request->invalidate([
				'type' => 'turnstile',
				'name' => 'wppool-turnstile',
			], $message);
		}

		return $request;
	}

	/**
	 * Add form tag in contact form 7 form editor.
	 *
	 * @since 1.0.1
	 */
	public function add_form_tag_button()
	{
		wpcf7_add_form_tag( 'easy_cloudflare_turnstile', [ $this, 'shortcode' ] );
	}

	/**
	 * Button tag generator.
	 *
	 * @since 1.0.1
	 */
	public function add_tag_generator_button()
	{
		$tag_generator = \WPCF7_TagGenerator::get_instance();

		$tag_generator->add(
			'easy_cloudflare_turnstile',
			__( 'easy cloudflare turnstile', 'wppool-turnstile' ),
			[ $this, 'button' ],
			''
		);
	}

	/**
	 * Form button html.
	 *
	 * @param  mixed $form The contact form.
	 * @param  mixed $args Form arguments.
	 * @return void
	 */
	public function button( $form, $args = [] ) { // phpcs:ignore
		$args = wp_parse_args( $args, [] );
		?>
		<div class="insert-box">
			<input
				type="text"
				name="easy_cloudflare_turnstile"
				class="tag code"
				readonly="readonly"
				onfocus="this.select()"
			/>
			<div class="submitbox">
				<input
					type="button"
					class="button button-primary insert-tag"
					value="<?php echo esc_attr( __( 'Insert Tag', 'wppool-turnstile' ) ); ?>"
				/>
			</div>
		</div>
		<?php
	}
}