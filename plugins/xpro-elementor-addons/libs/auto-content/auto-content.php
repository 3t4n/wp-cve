<?php
/**
 * AI Assistant
 *
 * @package XproElementorAddons
 */
namespace XproElementorAddons\Libs;

use Xpro_Elementor_Addons;
use XproElementorAddons\Libs\Dashboard\Classes\Xpro_Elementor_Dashboard_Utils;

class Xpro_Chat_GPT {

	private static $instance = null;

	private $api_key;
	private $content = '';
	private $errors = [];
	private $requestBody = [];
	private $requestApi = 'https://api.openai.com/v1/completions';

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	public function init() {

		$user_settings = Xpro_Elementor_Dashboard_Utils::instance()->get_option( 'xpro_elementor_user_data', array() );
		$this->api_key       = isset( $user_settings['openAi']['api_key'] ) ? $user_settings['openAi']['api_key'] : '';

		add_action( 'admin_menu', array( $this, 'register_settings_submenus' ), 98 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		//Ajax Callback
		add_action( 'wp_ajax_xpro_ai_chat_box', array( $this, 'ai_chat_box' ) );
		add_action( 'wp_ajax_nopriv_xpro_ai_chat_box', array( $this, 'ai_chat_box' ) );

		add_action( 'wp_ajax_xpro_ai_chat_save_settings', array( $this, 'save_settings' ) );
		add_action( 'wp_ajax_xpro_ai_chat_save_settings', array( $this, 'save_settings' ) );


	}

	public function register_settings_submenus() {

		add_submenu_page(
			Xpro_Elementor_Addons::PAGE_SLUG,
			esc_html__( 'Ai ChatGPT', 'xpro-elementor-addons' ),
			esc_html__( 'Ai ChatGPT', 'xpro-elementor-addons' ),
			'manage_options',
			'xpro-auto-content',
			array( $this, 'register_settings_contents' ),
			1
		);

	}

	public function register_settings_contents() {
		include __DIR__ . '/views/ai-chat-bot.php';
	}

	public function enqueue_scripts() {

		$screen = get_current_screen();

		if ( 'xpro-addons_page_xpro-auto-content' === $screen->base ) {
			wp_enqueue_style( 'xpro-auto-content', XPRO_ELEMENTOR_ADDONS_ASSETS . 'admin/css/auto-content.css', false, XPRO_ELEMENTOR_ADDONS_VERSION );
			wp_enqueue_script( 'xpro-auto-content', XPRO_ELEMENTOR_ADDONS_ASSETS . 'admin/js/auto-content.js', array( 'jquery' ), XPRO_ELEMENTOR_ADDONS_VERSION, true );

			wp_localize_script(
				'xpro-auto-content',
				'XproAssistant',
				array(
					'botImage'    => esc_url( XPRO_ELEMENTOR_ADDONS_ASSETS . 'admin/images/xpro-assistant.png' ),
					'userImage'   => get_avatar_url(get_the_author_meta( 'ID' ), array('size' => 'thumbnail')),
					'homeUrl' => get_site_url(),
					'ajaxURL'   => admin_url( 'admin-ajax.php' ),
					'nonce'  => wp_create_nonce( 'xpro-auto-content-nonce' ),
					'serverBusy'  => __('Server is busy please try again later.','xpro-elementor-addons'),
				)
			);

		}
	}

	public function ai_chat_box()
	{
		$nonce = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';

		if ( ! (wp_verify_nonce( $nonce, 'xpro-auto-content-nonce' ) || wp_verify_nonce( $nonce, 'xpro-editor-nonce' )) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Error: Invalid nonce verification.', 'xpro-elementor-addons' ) ) );
		}

		if (!$this->api_key) {
			wp_send_json_error( array( 'message' => esc_html__( 'API key not found. Please enter OpenAi API key first.', 'xpro-elementor-addons' ) ) );
		}

		if (!$_POST['prompt']) {
			wp_send_json_error( array( 'message' => esc_html__( 'Please enter your prompt before!', 'xpro-elementor-addons' ) ) );
		}

		$settings        = get_option( 'xpro_assistant_settings' );
		$temperature     = isset( $settings['temperature'] ) ? $settings['temperature'] : '0.7';
		$tokens          = isset( $settings['tokens'] ) ? $settings['tokens'] : '2048';
		$randomness      = isset( $settings['randomness'] ) ? $settings['randomness'] : '1';
		$frequency       = isset( $settings['frequency'] ) ? $settings['frequency'] : '0';
		$presence        = isset( $settings['presence'] ) ? $settings['presence'] : '0';

		$prompt = sanitize_text_field($_POST['prompt']);
		$temperature =  wp_unslash( $_POST[ 'temperature' ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'temperature' ] )) : $temperature;
		$tokens = wp_unslash( $_POST[ 'tokens' ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'tokens' ] ) ) : $tokens;
		$randomness = wp_unslash( $_POST[ 'randomness' ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'randomness' ] ) ) : $randomness;
		$frequency = wp_unslash( $_POST[ 'frequency' ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'frequency' ] ) ) : $frequency;
		$presence = wp_unslash( $_POST[ 'presence' ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'presence' ] ) ) : $presence;


		if($_POST['keyword'] != ''){
			$prompt.= '. Keywords to place: '.sanitize_text_field($_POST['keyword']).'.\n';
		}

		if($_POST['language'] != ''){
			$prompt.= 'Write in this language: '.sanitize_text_field($_POST['keyword']).'.\n';
		}

		$this->requestBody = [
			'model'             => 'text-davinci-003',
			'prompt'            => $prompt,
			'temperature'       => (float) $temperature,
			'max_tokens'        => (int) $tokens,
			'top_p'             => (float) $randomness,
			'frequency_penalty' => (float) $frequency,
			'presence_penalty'  => (float) $presence,
		];

		$this->parse_response($this->send_request());

		if (!empty($this->errors)) {
			wp_send_json_error( array( 'message' => $this->errors ) );
		}

		wp_send_json_success(array( 'message' => $this->content ));
	}

	private function send_request() {
		$args = [
			'headers' => [
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $this->api_key,
			],
			'method'  => 'POST',
			'timeout' => 45,
			'body'    => wp_json_encode($this->requestBody)
		];

		$response = wp_remote_post($this->requestApi, $args);

		if (!is_wp_error($response) && isset($response['response'])) {
			$response = (object) [
				'status'  => $response['response']['code'],
				'message' => $response['response']['message'],
				'code'    => $response['response']['code'],
				'body'    => json_decode($response['body']),
			];
		}

		return $response;
	}

	/**
	 * @param object $response
	 * @param bool $content
	 * @return void
	 */
	private function parse_response(object $response, bool $content = false) : void
	{
		if (isset($response->status) && 200 === $response->status) {
			if (!empty($choices = $response->body->choices ?? [])) {
				if (!$content) {
					foreach ($choices as $choice) {
						$this->content .= $choice->text;
					}
				} else {
					$content = '';
					foreach ($choices as $choice) {
						$content .= $choice->text;
					}
					$this->content = $content . $this->content;
				}
			} else {
				$this->errors[] = ( esc_html__('Not enough choices generated', 'xpro-elementor-addons') );
			}
		}
	}

	public function save_settings()
	{
		$nonce = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';

		if ( ! (wp_verify_nonce( $nonce, 'xpro-auto-content-nonce' ) || wp_verify_nonce( $nonce, 'xpro-editor-nonce' )) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Error: Invalid nonce verification.', 'xpro-elementor-addons' ) ) );
		}

		$data = [];

		$data['temperature'] = sanitize_text_field( wp_unslash( $_POST[ 'temperature' ] ) );
		$data['tokens'] = sanitize_text_field( wp_unslash( $_POST[ 'tokens' ] ) );
		$data['randomness'] = sanitize_text_field( wp_unslash( $_POST[ 'randomness' ] ) );
		$data['frequency'] = sanitize_text_field( wp_unslash( $_POST[ 'frequency' ] ) );
		$data['presence'] = sanitize_text_field( wp_unslash( $_POST[ 'presence' ] ) );
		$data['output_language'] = sanitize_text_field( wp_unslash( $_POST[ 'output_language' ] ) );
		$data['speak_language'] = sanitize_text_field( wp_unslash( $_POST[ 'speak_language' ] ) );
		$data['speak_rate'] = sanitize_text_field( wp_unslash( $_POST[ 'speak_rate' ] ) );
		$data['speak_pitch'] = sanitize_text_field( wp_unslash( $_POST[ 'speak_pitch' ] ) );

		update_option('xpro_assistant_settings',$data);

		wp_send_json_success(array( 'message' => esc_html__( 'Settings has been saved!', 'xpro-elementor-addons' ) ));

		wp_send_json($data);

	}

}

Xpro_Chat_GPT::instance();