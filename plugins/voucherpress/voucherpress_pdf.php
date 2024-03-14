<?php
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class voucherpress_pdf extends TCPDF {

	var $voucher_image;
	var $voucher_image_w;
	var $voucher_image_h;
	var $voucher_image_dpi;

	//Page header
	public function Header() {
		// Full background image
		$auto_page_break = $this->AutoPageBreak;
		$this->SetAutoPageBreak(false, 0);
		$img_file = @$image;
		$this->Image($this->voucher_image, $x=0, $y=0, $this->voucher_image_w, $this->voucher_image_h, $type='', $link='', $align='', $resize=false, $this->voucher_image_dpi, $palign='', $ismask=false, $imgmask=false, $border=0);
		$this->SetAutoPageBreak($auto_page_break);
	}
}
?>