<?php
/**
 * Dropp PDF
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp\Models;

use Dropp\Actions\Make_Directory_Recursively_Action;
use Dropp\API;
use Exception;
use WP_Filesystem_Base;

/**
 * Dropp PDF
 */
class Dropp_PDF extends Model {

	protected $barcode = false;
	public Dropp_Consignment $consignment;

	public array $errors = [];

	/**
	 * Construct
	 */
	public function __construct( Dropp_Consignment $consignment, $barcode = false ) {
		$this->consignment = $consignment;
		$this->barcode     = $barcode;
	}

	/**
	 * To array
	 *
	 * @return array Array representation.
	 */
	public function to_array(): array {
		return [
			'barcode' => $this->barcode,
		];
	}

	protected function get_endpoint(): string {
		if ( $this->barcode ) {
			return "web/pdf/getpdf/{$this->consignment->dropp_order_id}/{$this->barcode}/";
		}

		return "orders/pdf/{$this->consignment->dropp_order_id}";
	}

	/**
	 * Request
	 *
	 * @return string          This object.
	 * @throws Exception $e     Sending exception.
	 */
	public function remote_get(): string {
		$api       = new API();
		$api->test = $this->consignment->test;

		$endpoint = $this->get_endpoint();
		$response = $api->get( $endpoint, 'raw' );
		if ( ! $response['headers'] ) {
			throw new Exception( __( 'Missing response headers', 'dropp-for-woocommerce' ) );
		}
		if ( 'application/json' === $response['headers']->offsetGet( 'content-type' ) ) {
			$data           = json_decode( $response['body'], true );
			$this->errors[] = $data['error'];
			throw new Exception( __( 'API Error', 'dropp-for-woocommerce' ) );
		}
		if ( 'application/pdf' !== $response['headers']->offsetGet( 'content-type' ) ) {
			throw new Exception( __( 'Invalid PDF', 'dropp-for-woocommerce' ) );
		}

		return $response['body'];
	}

	/**
	 * Get filename
	 *
	 * @return  string Filename.
	 */
	public function get_filename(): string {
		$uploads_dir = self::get_dir();
		$filename    = $uploads_dir['subdir'] . '/' . $this->consignment->dropp_order_id . '.pdf';
		if ( $this->barcode ) {
			$filename = $uploads_dir['subdir'] . '/' . $this->consignment->dropp_order_id . '-' . $this->barcode . '.pdf';
		}

		return $filename;
	}

	/**
	 * Get content.
	 *
	 * First attempts to get a downloaded PDF, then tries to get from remote.
	 *
	 * @return string              PDF content.
	 * @throws Exception $e        Sending exception.
	 */
	public function get_content(): string {
		/** @var WP_Filesystem_Base $wp_filesystem */
		global $wp_filesystem;

		require_once ABSPATH . '/wp-admin/includes/file.php';
		WP_Filesystem();

		$filename    = $this->get_filename();
		$wp_filesystem->connect();
		if ( $wp_filesystem->exists( $filename ) ) {
			return $wp_filesystem->get_contents( $filename );
		}

		$pdf = $this->remote_get();
		if (! (new Make_Directory_Recursively_Action)(dirname($filename), 3)) {
			return $pdf;
		}
		if (! $wp_filesystem->touch($filename) ) {
			return $pdf;
		}
		$wp_filesystem->put_contents( $filename, $pdf );
		return $pdf;
	}

	/**
	 * Get dir
	 *
	 * @return array
	 */
	public static function get_dir(): array {
		$uploads_dir = wp_upload_dir();
		if ( $uploads_dir['error'] ) {
			return $uploads_dir;
		}

		$uploads_dir['baseurl'] .= '/dropp-labels';
		$uploads_dir['basedir'] .= '/dropp-labels';

		$uploads_dir['subdir']  = $uploads_dir['basedir'];
		$uploads_dir['path']    = $uploads_dir['basedir'];
		$uploads_dir['url']     = $uploads_dir['baseurl'];

		$year                  = gmdate( 'Y' );
		$uploads_dir['subdir'] .= "/$year";
		$uploads_dir['url']    .= "/$year";
		$uploads_dir['path']   .= "/$year";

		$month                 = gmdate( 'm' );
		$uploads_dir['subdir'] .= "/$month";
		$uploads_dir['url']    .= "/$month";
		$uploads_dir['path']   .= "/$month";

		return $uploads_dir;
	}
}
