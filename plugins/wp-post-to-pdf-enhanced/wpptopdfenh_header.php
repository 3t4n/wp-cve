<?php
/**
 * wpptopdfenh_header.php
 *
 * This file contains header and footer functions
 *
 */

if ( ! class_exists( 'TCPDF' ) )
	require_once WPPTOPDFENH_PATH . '/tcpdf/tcpdf.php';
// if we have not set the option to include the header on all pages, define a constant
if ( isset( $this->options['headerAllPages'] ) ) {
	if ( $this->options['headerAllPages'] == 'first' ) {
		define( 'WPPTOPDFENH_HEADER_FIRST_PAGE_ONLY', 'Y' );
	}
	if ( $this->options['headerAllPages'] == 'none' ) {
		define( 'WPPTOPDFENH_HEADER_SUPPRESSED', 'Y' );
	}
}
// if we have enabled a custom footer, define those values as constants
if ( isset( $this->options['customFooter'] ) ) {
	define( 'WPPTOPDFENH_FOOTER_WIDTH', $this->options['footerWidth'] );
	define( 'WPPTOPDFENH_FOOTER_MIN_HEIGHT', $this->options['footerMinHeight'] );
	define( 'WPPTOPDFENH_FOOTER_X', $this->options['footerX'] );
	define( 'WPPTOPDFENH_FOOTER_Y', $this->options['footerY'] );
	define( 'WPPTOPDFENH_CUSTOM_FOOTER', $this->options['customFooterText'] );
	define( 'WPPTOPDFENH_FOOTER_BORDER', $this->options['footerBorder'] );
	define( 'WPPTOPDFENH_FOOTER_FILL', $this->options['footerFill'] );
	define( 'WPPTOPDFENH_FOOTER_ALIGN', $this->options['footerAlign'] );
	define( 'WPPTOPDFENH_FOOTER_PAD', $this->options['footerPad'] );
}
// Extend the TCPDF class to print the header only on the first page
class MYPDF extends TCPDF {
	public function header() {
		if ( defined( 'WPPTOPDFENH_HEADER_SUPPRESSED' ) ) {
			$this->setPrintHeader( false );
		} elseif ( defined( 'WPPTOPDFENH_HEADER_FIRST_PAGE_ONLY' ) ) {
			parent::header();
			$this->setPrintHeader( false );
		} else {
			// call parent header method for header on all pages
			parent::header();
		}
	}
	public function Footer() {
		if ( defined( 'WPPTOPDFENH_CUSTOM_FOOTER' ) ) {
			$this->writeHTMLCell( WPPTOPDFENH_FOOTER_WIDTH, WPPTOPDFENH_FOOTER_MIN_HEIGHT, WPPTOPDFENH_FOOTER_X, WPPTOPDFENH_FOOTER_Y, WPPTOPDFENH_CUSTOM_FOOTER, WPPTOPDFENH_FOOTER_BORDER, 0, WPPTOPDFENH_FOOTER_FILL, true, WPPTOPDFENH_FOOTER_ALIGN, WPPTOPDFENH_FOOTER_PAD );
		} else {
			// call parent footer method for default footer
			parent::Footer();
		}
	}
}
