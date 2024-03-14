<?php
/**
 * Booking
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp\Models;

use Dropp\API;
use Exception;
use WC_Log_Levels;
use WC_Logger;

/**
 * Dropp PDF
 */
class Dropp_Return_PDF extends Dropp_PDF {

	protected function get_endpoint(): string {
		return "orders/returnpdf/{$this->consignment->return_barcode}";
	}

	/**
	 * Get filename
	 *
	 * @return  string Filename.
	 */
	public function get_filename(): string {
		$uploads_dir = self::get_dir();

		return $uploads_dir['subdir'] . '/' . $this->consignment->return_barcode . '.pdf';
	}
}
