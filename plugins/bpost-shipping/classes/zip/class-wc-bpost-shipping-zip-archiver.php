<?php

namespace WC_BPost_Shipping\Zip;

use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;
use WC_BPost_Shipping\Label\WC_BPost_Shipping_Label_Path_Resolver;

class WC_BPost_Shipping_Zip_Archiver {
	/** @var string */
	private $zip_temp_file;

	/** @var \ZipArchive */
	private $zip_archive;
	/** @var WC_BPost_Shipping_Adapter_Woocommerce */
	private $adapter;
	/** @var WC_BPost_Shipping_Label_Path_Resolver */
	private $label_path_resolver;

	/**
	 * WC_BPost_Shipping_Zip_Archiver constructor.
	 *
	 * @param WC_BPost_Shipping_Adapter_Woocommerce $adapter
	 * @param \ZipArchive $zip_archive
	 * @param WC_BPost_Shipping_Label_Path_Resolver $label_path_resolver
	 */
	public function __construct(
		WC_BPost_Shipping_Adapter_Woocommerce $adapter,
		\ZipArchive $zip_archive,
		WC_BPost_Shipping_Label_Path_Resolver $label_path_resolver
	) {
		$this->adapter     = $adapter;
		$this->zip_archive = $zip_archive;

		$this->zip_temp_file = $adapter->wp_tempnam( 'zip' );

		$open_status = $this->zip_archive->open( $this->zip_temp_file, \ZipArchive::OVERWRITE );
		if ( true !== $open_status ) {
			throw new \InvalidArgumentException( 'Zip file opening error' );
		}
		$this->label_path_resolver = $label_path_resolver;
	}

	/**
	 * @param array $contents
	 */
	public function build_archive( array $contents ) {
		// Stuff with content
		foreach ( $contents as $filename => $content ) {
			$this->zip_archive->addFromString( $filename, $content );
		}

		// Close
		$this->zip_archive->close();
	}

	/**
	 * @param string $zip_filename
	 */
	public function send_archive( $zip_filename ) {
		header( 'Content-Type: application/zip' );
		header( 'Content-Length: ' . filesize( $this->zip_temp_file ) );
		header( 'Content-Disposition: attachment; filename="' . $zip_filename . '.zip"' );
		readfile( $this->zip_temp_file );
		unlink( $this->zip_temp_file );
	}


}
