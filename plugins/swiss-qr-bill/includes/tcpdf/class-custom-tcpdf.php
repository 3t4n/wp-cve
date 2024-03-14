<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
require_once plugin_dir_path( WC_SWISS_QR_BILL_FILE ) . 'includes/tcpdf/tcpdf.php';

/**
 * Class Custom_TCPDF
 * To override the header and footer of PDF
 */
class Custom_TCPDF extends TCPDF {
	public $order;

	public function __construct( $order, $orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false ) {
		parent::__construct( $orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa );

		$this->order = $order;
	}

	public function Header() {
		if ( (int) $this->PageNo() == 1 ) {
			return;
		}
		// Order items header
		$order    = $this->order;
		$page_num = $this->PageNo();

		ob_start();
		include plugin_dir_path( WC_SWISS_QR_BILL_FILE ) . 'includes/tcpdf/templates/parts/order-items-header.php';
		$order_items_header = ob_get_clean();
		$this->writeHTML( $order_items_header, true, false, true, false, '' );
	}
}