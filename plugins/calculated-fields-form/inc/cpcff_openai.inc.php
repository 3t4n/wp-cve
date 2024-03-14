<?php
if(
	! defined( 'CP_CALCULATEDFIELDSF_DISCOUNT_CODES_TABLE_NAME' ) ||
	! defined( 'CP_CALCULATEDFIELDSF_DEFAULT_CURRENCY' )
)
{
	print 'Direct access not allowed.';
    exit;
}

// CONSTANTS
define ( 'CFF_AI_PROMPT', 'limits answers to javascript and css topics' );
define ( 'CFF_AI_MODEL', 'gpt-3.5-turbo' );
define ( 'CFF_AI_TEMPERATURE', 1.0 );
define ( 'CFF_AI_FREQ_PENALTY', 0.0 );
define ( 'CFF_AI_PRES_PENALTY', 0.0 );
define ( 'CFF_AI_MAX_TOKENS', 4090 );

require_once dirname(__FILE__) . '/../vendors/openai/class.phpopenaichat.php';

if ( current_user_can( 'manage_options' ) ) {
	// Registering the OpenAI API Key
	if (
		! empty( $_POST['cff-action'] ) &&
		wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cff-action'] ) ), 'cff-ai-assistan-register-action' )
	) {
		if( isset( $_POST['cff-openai-api-key'] ) ) {
			$openai_api_key = sanitize_text_field( $_POST['cff-openai-api-key'] );
			if( ! empty( $openai_api_key ) ) {
				update_option( 'cff_openai_api_key', $openai_api_key );
				print 'ok';
				exit;
			}
		}
		_e( 'OpenAI API Key is required', 'calculated-fields-form' );
		exit;
	}

	// Making questions
	if (
		! empty( $_POST['cff-action'] ) &&
		wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cff-action'] ) ), 'cff-ai-assistan-question-action' )
	) {
		$openai_api_key = get_option( 'cff_openai_api_key', '' );
		$output = array();
		if ( ! empty( $openai_api_key ) ) {
			if ( isset( $_POST['cff-openai-question'] ) ) {
				$openai_question = wp_kses_post( wp_unslash( $_POST['cff-openai-question'] ) );
				if( ! empty( $openai_question ) ) {
					$openAIChat = new PHPOpenAIChat( $openai_api_key );
					$openAIChat->model = CFF_AI_MODEL;
					$openAIChat->temperature  = CFF_AI_TEMPERATURE;
					$openAIChat->freq_penalty = CFF_AI_FREQ_PENALTY;
					$openAIChat->pres_penalty = CFF_AI_PRES_PENALTY;
					$openAIChat->set_max_tokens( CFF_AI_MAX_TOKENS );

					$messages = array();

					try {
						try {
							$messages = $openAIChat->set_agent( $messages, "You are an assistant cat that can speak english and is named Henry." );
							$messages = $openAIChat->add_prompt_to_messages( $messages, $openai_question . ' ' . CFF_AI_PROMPT );
							$response = $openAIChat->sendMessage( $messages );

							$text = '';
							if ( ! empty( $response ) ) {
								if (
									is_array( $response ) &&
									isset( $response['error'] ) &&
									is_array( $response['error'] )
								) {
									if ( isset( $response['error']['message'] ) ) {
										$text .= $response['error']['message'];
									}
									if ( isset( $response['error']['code'] ) ) {
										$text .= ( ! empty( $text ) ? "\n" : "" ) . $response['error']['code'];
									}
									$output['type'] = 'error';
								} else {
									$text .= $openAIChat->get_response_text( $response );
									$output['type'] = 'mssg';

									// Store messages history in session variables
									if ( session_id() == '' ) {
										session_start();
									}

									if( ! empty ( $_SESSION['cff-openai-messages'] ) ) {
										$messages_records = $_SESSION['cff-openai-messages'];
									} else {
										$messages_records = array();
									}

									array_unshift( $messages_records, array( 'type' => 'answer', 'text' => $text ) );
									array_unshift( $messages_records, array( 'type' => 'question', 'text' => $openai_question ) );

									$_SESSION['cff-openai-messages'] = $messages_records;
								}
							}
							$output['message'] = str_replace( "\n", "<br>", htmlentities( $text ) );
						} catch (Exception $exp ) {
							$output['message'] = $exp->getMessage();
							$output['type'] = 'error';
						}
					} catch ( Error $err ) {
						$output['message'] = $err->getMessage();
						$output['type'] = 'error';
					}
				}
			} else {
				$output['message'] = __( 'You must enter a question', 'calculated-fields-form' );
				$output['type'] = 'error';
			}
		} else {
			$output['message'] = __( 'OpenAI API Key is required, please, press the settings button, and enter your API Key.', 'calculated-fields-form' );
			$output['type'] = 'warning';
		}
		print json_encode( $output );
		exit;
	}
}