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
 * Manages WpDiscuz turnstile integration.
 *
 * @since 1.0.1
 */
class WpDiscuz {

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
		if ( ! class_exists( 'WpdiscuzCore' ) || ! wp_turnstile()->settings->get( 'site_key' ) || ! wp_turnstile()->settings->get( 'secret_key' ) ) {
			return;
		}

		$wpdiscuz = wp_turnstile()->integrations->get( 'wpdiscuz');

		if ( ! wp_validate_boolean( $wpdiscuz)) {
			return;
		}

		$this->turnstile_context_id = wp_rand();

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ]);
		add_action( 'wpdiscuz_submit_button_before', [ $this, 'render' ], 10, 3);
		add_action( 'wpdiscuz_before_thread_list', [ $this, 'render_comment' ], 10, 3 );
		add_action( 'wpdiscuz_after_comment_post', [ $this, 'verify' ], 10, 2 );
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
			'ect-wpdiscuz-turnstile-challenges',
			'//challenges.cloudflare.com/turnstile/v0/api.js?onload=ectWpDiscuzTurnstileCb',
			[],
			wp_turnstile()->api_version,
			true
		);

		$site_key = wp_turnstile()->settings->get( 'site_key' );
		if ( $this->form_ids ) {
			$script = 'window.ectWpDiscuzTurnstileCb = function () { ';
			foreach ( $this->form_ids as $form_id ) {
				$script .= "turnstile.render('#ect-wpdiscuz-turnstile-container-{$this->turnstile_context_id}-{$form_id}', {
				sitekey: '" . esc_attr( $site_key ) . "',
				callback: function(token) {
					var submitBtn = document.querySelectorAll('.wpd-main-form-wrapper input[type=submit]');
					if(submitBtn){
					submitBtn.forEach(function(submit){
						submit.style.pointerEvents = 'auto';
						submit.style.opacity = '1';
					});
				}

					var commSubmit = document.querySelectorAll('.wpd-secondary-form-wrapper input[type=submit]');
					if(commSubmit){
					commSubmit.forEach(function(submit){
						submit.style.pointerEvents = 'auto';
						submit.style.opacity = '1';
					});
				}					
				}
		});";
			}
			$script .= '};';

			wp_add_inline_script( 'ect-wpdiscuz-turnstile-challenges', $script, 'before' );
			wp_enqueue_script( 'ect-wpdiscuz-turnstile-challenges' );
		}
	}

	/**
	 *  Turnstile Integration Shortcode.
	 *
	 * @param  mixed  $currentUser Current User Priviledges.
	 * @param  number $uniqueId The Form ID of MailChimp Form.
	 * @param  mixed  $isMainForm Returns the main form name.
	 * @return mixed|html The ongoing request flow.
	 */
	public function render( $currentUser, $uniqueId, $isMainForm )
	{
		if ( is_user_logged_in() ) {
			return;
		}
		if ( $currentUser && 'main' === $isMainForm ) {
			$this->form_counter++;
			$form_id = $this->form_counter;
			$this->form_ids[] = $form_id;

			$html = sprintf(
				'<div class="ect-turnstile-container" id="ect-wpdiscuz-turnstile-container-%s-%s" data-sitekey="%s" data-theme="%s" data-submit-button="%s" data-size="%s"></div>',
				$this->turnstile_context_id,
				$form_id,
				esc_attr( wp_turnstile()->settings->get( 'site_key' ) ),
				esc_attr( wp_turnstile()->settings->get( 'theme', 'light' ) ),
				esc_attr( wp_validate_boolean( wp_turnstile()->settings->get( 'button_access', 'false' ))),
				'normal'
			);

			if (wp_validate_boolean( wp_turnstile()->settings->get( 'button_access', 'false' ))) {
				$html .= '<style>
				.wpd-main-form-wrapper input[type=submit] {
					pointer-events: none;
					opacity: .5;
				}
			</style>';
			}

			$this->allowed_tags = wp_kses_allowed_html( 'post' );
			$this->allowed_tags['style'] = $this->allowed_tags;
			$this->enqueue_scripts( $form_id );
			echo wp_kses( $html, $this->allowed_tags );
		}
	}


	/**
	 *  Turnstile Integration Shortcode for Replies.
	 *
	 * @param  mixed  $post Current Post.
	 * @param  mixed  $currentUser CurrentUser priviledge.
	 * @param  number $commentsCount Number of Commments.
	 * @return mixed|html The ongoing request flow.
	 */
	public function render_comment( $post, $currentUser, $commentsCount )
	{
		if ( is_user_logged_in() ) {
			return;
		}
		if ( $currentUser ) {
			$this->form_counter++;
			$form_id = $this->form_counter;
			$this->form_ids[] = $form_id;

			$html = sprintf(
				'<div class="ect-turnstile-container" id="ect-wpdiscuz-turnstile-container-%s-%s" data-sitekey="%s" data-theme="%s" data-submit-button="%s" data-size="%s"></div>',
				$this->turnstile_context_id,
				$form_id,
				esc_attr( wp_turnstile()->settings->get( 'site_key') ),
				esc_attr( wp_turnstile()->settings->get( 'theme', 'light')),
				esc_attr( wp_validate_boolean( wp_turnstile()->settings->get( 'button_access', 'false'))),
				'normal'
			);

			if (wp_validate_boolean( wp_turnstile()->settings->get( 'button_access', 'false' ) ) ) {
				$html .= '<style>
				.wpd-secondary-form-wrapper input[type=submit]{
					pointer-events: none;
					opacity: .5;
				}
			</style>';
			}

			$this->allowed_tags = wp_kses_allowed_html( 'post' );
			$this->allowed_tags['style'] = $this->allowed_tags;
			$this->enqueue_scripts( $form_id );
			echo wp_kses( $html, $this->allowed_tags );
		}
	}

	/**
	 * Verify WpDiscuz form submissions.
	 *
	 * @param  mixed $new_comment Checking for the comments.
	 * @param  mixed $currentUser priviledges.
	 *
	 * @return void
	 */
	public function verify($new_comment, $currentUser)     { // phpcs:ignore
		$message = wp_turnstile()->settings->get( 'error_msg', __( 'Please verify you are human', 'wppool-turnstile' ) );

        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST['cf-turnstile-response'])) { // phpcs:ignore
            $token    = sanitize_text_field($_POST['cf-turnstile-response']); // phpcs:ignore
			$response = wp_turnstile()->helpers->validate_turnstile( $token );

			if ( ! ( isset( $response['success'] ) && wp_validate_boolean( $response['success'] ) ) ) {
				$error_code = $response['error-codes'][0] ?? null;
				$message   = wp_turnstile()->common->get_error_message( $error_code );
				echo wp_kses( wp_turnstile()->common->terminate_request( $message ), wp_kses_allowed_html( 'post' ) );
			}
		}
	}
}