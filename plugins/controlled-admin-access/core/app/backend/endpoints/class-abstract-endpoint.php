<?php

namespace WPRuby_CAA\Core\App\Backend\Endpoints;


abstract class Abstract_Endpoint {

	public function __construct()
	{
		add_action('wp_ajax_' . $this->action(), [$this, 'process']);
	}

	public function process()
	{

		check_ajax_referer( $this->action(), '_ajax_nonce' );

		$data = json_decode(file_get_contents('php://input'), true);

		$response = $this->callback($data);

		echo json_encode($response);
		exit;
	}

	/**
	 * @param array|string $errors
	 */
	protected function abort($errors)
	{
		$errorsMessages = [$errors];

		echo json_encode(['errors' => $errorsMessages]);
		exit;
	}

	/**
	 * @param array $response
	 */
	protected function output($response)
	{
		echo json_encode($response);
		exit;
	}

	protected function ok()
	{
		$this->output(['ok' => true]);
	}


	/**
	 * @param mixed
	 * @return array
	 */
	public abstract function callback($data);

	/**
	 * @return string
	 */
	public abstract function action();

}
