<?php

/**
 * OpenAI utility class
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_OpenAI {

	/**
	 * API URL base: the base URL part for API end-points.
	 */
	const API_V1_URL = 'https://api.openai.com/v1';

	/**
	 * API URL end-point: Creates a completion for the provided prompt and parameters
	 * For more details see the documentation at https://platform.openai.com/docs/api-reference/completions
	 */
	const COMPLETIONS_URL = '/completions';

	/**
	 * API URL end-point: Creates a new edit for the provided input, instruction, and parameters.
	 * For more details see the documentation at https://platform.openai.com/docs/api-reference/edits
	 */
	const EDITS_URL = '/edits';

	/**
	 * The model ID to use for completions API requests
	 * For models overview see the documentation at https://platform.openai.com/docs/models/overview
	 * For more information about completions requests: https://platform.openai.com/docs/guides/completion/prompt-design and https://platform.openai.com/docs/guides/completion/inserting-text
	 *
	 * @var string
	 */
	// private $completions_model;

	/**
	 * The model ID to use for edits API requests
	 * You can use the text-davinci-edit-001 or code-davinci-edit-001 model with this endpoint.
	 * For models overview see the documentation: https://platform.openai.com/docs/models/overview
	 * For more information about edits requests: https://platform.openai.com/docs/guides/completion/editing-text
	 *
	 * @var string
	 */
	// private $edits_model;

	/**
	 * OPTIONAL API parameter: The maximum number of tokens to generate in the completion.
	 * The token count of your prompt plus max_tokens cannot exceed the model's context length.
	 * Most models have a context length of 2048 tokens (except for the newest models, which support 4096).
	 * For more details see the documentation at https://platform.openai.com/docs/api-reference/completions/create#completions/create-max_tokens
	 *
	 * Default: 16
	 * @var int
	 */
	// private $max_tokens;

	/**
	 * OPTIONAL API parameter: What sampling temperature to use.
	 * Higher values means the model will take more risks.
	 * Try 0.9 for more creative applications, and 0 (argmax sampling) for ones with a well-defined answer.
	 * We generally recommend altering this or top_p but not both.
	 * For more details see the documentation at https://platform.openai.com/docs/api-reference/completions/create#completions/create-temperature
	 *
	 * Default: 1
	 * @var float
	 */
	// private $temperature;

	/**
	 * OPTIONAL API parameter: How many completions to generate for each prompt.
	 * Note: Because this parameter generates many completions, it can quickly consume your token quota.
	 * Use carefully and ensure that you have reasonable settings for max_tokens and stop.
	 * For more details see the documentation at https://platform.openai.com/docs/api-reference/completions/create#completions/create-n
	 *
	 * Default: 1
	 * @var int
	 */
	// private $n;

	/**
	 * REQUIRED API parameter: The instruction that tells the model how to edit the prompt.
	 *
	 * @var string
	 */
	// private $instruction;

	/**
	 * Text message containing error description returned from API.
	 * Empty string on success API response.
	 *
	 * @var string
	 */
	public $error_message = '';

	/**
	 * Number of tokens used for latest API request
	 *
	 * @var string
	 */
	public $tokens_used = 0;

	// TODO: get list of available models GET to https://api.openai.com/v1/models
	// TODO: Allow user to save an available model in settings
	// Info: https://beta.openai.com/docs/api-reference/models

	/**
	 * Create completion
	 * More Info: https://beta.openai.com/docs/api-reference/completions/create
	 *
	 * @param $model
	 * @param $prompt
	 * @param $temperature
	 * @return string
	 */
	public function complete( $model, $prompt, $temperature ) {

		$choices = $this->make_api_request( self::API_V1_URL . self::COMPLETIONS_URL, array(
			'model'         => trim( $model ),
			'prompt'        => trim( $prompt ),
			'max_tokens'    => 2048, //(int)$this->global_config['openai_max_tokens'],
			'temperature'   => $temperature,
			'n'             => 1,
		) );

		return count( $choices ) > 0 ? trim( $choices[0]['text'] ) : '';
	}

	/**
	 * Edit input text
	 *
	 * @param $model
	 * @param $prompt
	 * @param $input
	 * @param $temperature
	 * @return string
	 */
	public function edit( $model, $prompt, $input, $temperature ) {

		$choices = $this->make_api_request( self::API_V1_URL . self::EDITS_URL, array(
			'model'         => trim( $model ),
			'instruction'   => trim( $prompt ),
			'input'         => trim( $input ),
			'temperature'   => $temperature,
			'n'             => 1,
		) );

		return count( $choices ) > 0 ? trim( $choices[0]['text'] ) : '';
	}

	/**
	 * Make API request
	 *
	 * @param $url
	 * @param $args
	 * @return array
	 */
	private function make_api_request( $url, $args ) {

		// reset error message
		$this->error_message = '';

		// validate API key
		$api_key = ephd_get_instance()->global_config_obj->get_value( 'openai_api_key' );
		if ( empty( $api_key ) ) {
			$this->error_message = sprintf( __( 'Please enter your OpenAI API key in the %s plugin Advanced Settings %s', 'help-dialog' ),  '<a href="' .
                                                  esc_url( admin_url( 'admin.php?page=ephd-help-dialog-advanced-config#settings' ) ) . '" target="_blank">', '</a>' );
			return [];
		}

		$http_result = wp_remote_post(
			$url,
			array(
				'headers'   => array(
					'Content-Type' => 'application/json',
					'Authorization' => 'Bearer ' . $api_key,
				),
				'body'      => wp_json_encode( $args ),
				'timeout'   => 120, // some of OpenAI API requests can take up to a few minutes
			)
		);

		// check for WP error
		if ( is_wp_error( $http_result ) ) {
			$error_message = $http_result->get_error_message();
			EPHD_Logging::add_log( 'WP error on OpenAI API response. Error details: ' . $error_message );
			$this->error_message = EPHD_Utilities::report_generic_error( 501, $error_message );
			return [];
		}

		if ( empty( $http_result['body'] ) ) {
			EPHD_Logging::add_log( 'Empty body on OpenAI API response.' );
			$this->error_message = EPHD_Utilities::report_generic_error( 502 );
			return [];
		}

		// retrieve API response
		$api_result = json_decode( $http_result['body'], true );

		// validate decoded JSON
		if ( empty( $api_result ) ) {
			EPHD_Logging::add_log( 'Unable to decode JSON from OpenAI API response.' );
			$this->error_message = EPHD_Utilities::report_generic_error( 503 );
			return [];
		}

		// validate HTTP code - for detailed description about each response code look to https://beta.openai.com/docs/guides/error-codes/api-errors
		if ( $http_result['response']['code'] != 200 ) {
			EPHD_Logging::add_log( 'OpenAI API request failed. HTTP response code: ' . $http_result['response']['code'] . '. API error message: ' . $api_result['error']['message'] );
			$this->error_message = $api_result['error']['message'];
			return [];
		}

		// save tokens usage
		$this->tokens_used = $api_result['usage']['total_tokens'];

		return is_array( $api_result['choices'] ) ? $api_result['choices'] : [];
	}
}
