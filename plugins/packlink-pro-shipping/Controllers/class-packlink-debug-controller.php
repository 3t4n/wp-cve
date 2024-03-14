<?php
/**
 * Packlink PRO Shipping WooCommerce Integration.
 *
 * @package Packlink
 */

namespace Packlink\WooCommerce\Controllers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Logeecom\Infrastructure\Configuration\Configuration;
use Logeecom\Infrastructure\ORM\Exceptions\QueryFilterInvalidParamException;
use Logeecom\Infrastructure\ServiceRegister;
use Packlink\BusinessLogic\Controllers\DebugController;
use Packlink\WooCommerce\Components\Utility\Debug_Helper;
use Packlink\WooCommerce\Components\Utility\Shop_Helper;

/**
 * Class Packlink_Debug_Controller
 *
 * @package Packlink\WooCommerce\Controllers
 */
class Packlink_Debug_Controller extends Packlink_Base_Controller {

	const SYSTEM_INFO_FILE_NAME = 'packlink-debug-data.zip';

	/**
	 * Debug controller.
	 *
	 * @var DebugController
	 */
	private $controller;

	/**
	 * Packlink_Debug_Controller constructor.
	 */
	public function __construct() {
		$this->controller = new DebugController();
	}

	/**
	 * Retrieves debug status.
	 */
	public function get_status() {
		$status = array(
			'status'      => $this->controller->getStatus(),
			'downloadUrl' => Shop_Helper::get_controller_url( 'Debug', 'download' ),
		);

		$this->return_json( $status );
	}

	/**
	 * Sets debug status.
	 */
	public function set_status() {
		$this->validate( 'yes', true );
		$raw     = $this->get_raw_input();
		$payload = json_decode( $raw, true );
		if ( array_key_exists( 'status', $payload ) ) {
			$this->controller->setStatus( $payload['status'] );
		}
	}

	/**
	 * Starts download of debug information.
	 *
	 * @throws QueryFilterInvalidParamException Database not accessible error.
	 */
	public function download() {
		$this->validate( 'no', true );

		$this->return_file( Debug_Helper::get_system_info(), static::SYSTEM_INFO_FILE_NAME );
	}

	/**
	 * Checks server's SSL settings.
	 */
	public function checkSSL() {
		$data = wp_remote_get( 'https://www.howsmyssl.com/a/check' );

		$this->return_json( $data );
	}

	/**
	 * Tests cURL request to the async process controller.
	 */
	public function testCurl() {
		$this->validate( 'no', true );

		//@codingStandardsIgnoreStart
		/** @var Configuration $config */
		$config = ServiceRegister::getService( Configuration::CLASS_NAME );
		$url    = $config->getAsyncProcessUrl( 'test' );

		/** @noinspection PhpComposerExtensionStubsInspection */
		$curl    = curl_init();
		$verbose = fopen( 'php://temp', 'wb+' );
		/** @noinspection CurlSslServerSpoofingInspection */
		/** @noinspection PhpComposerExtensionStubsInspection */
		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL            => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_HEADER         => true,
				// CURLOPT_SSLVERSION      => CURL_SSLVERSION_TLSv1_0,
				// CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_TIMEOUT        => 2,
				CURLOPT_CUSTOMREQUEST  => 'POST',
				CURLOPT_VERBOSE        => true,
				CURLOPT_STDERR         => $verbose,
				// CURLOPT_SSL_CIPHER_LIST => 'TLSv1.2',
				CURLOPT_HTTPHEADER     => array(
					'Cache-Control: no-cache',
				),
			)
		);

		/** @noinspection PhpComposerExtensionStubsInspection */
		$response = curl_exec( $curl );

		rewind( $verbose );
		echo '<pre>', stream_get_contents( $verbose );

		/** @noinspection PhpComposerExtensionStubsInspection */
		curl_close( $curl );

		echo $response;

		echo '</pre>';
		exit;
		//@codingStandardsIgnoreEnd
	}

	/**
	 * Sets file specified by $filePath as response.
	 *
	 * @param string $file_path Temporary file path.
	 * @param string $output_file_name Output file name.
	 */
	private function return_file( $file_path, $output_file_name = '' ) {
		$file_name = '' !== $output_file_name ? $output_file_name : basename( $file_path );

		header( 'Content-Description: File Transfer' );
		header( 'Content-Type: application/octet-stream' );
		header( 'Content-Disposition: attachment; filename=' . $file_name );
		header( 'Expires: 0' );
		header( 'Cache-Control: must-revalidate' );
		header( 'Pragma: public' );
		header( 'Content-Length: ' . filesize( $file_path ) );
		readfile( $file_path ); // phpcs:ignore

		status_header( 200 );
		die();
	}
}
