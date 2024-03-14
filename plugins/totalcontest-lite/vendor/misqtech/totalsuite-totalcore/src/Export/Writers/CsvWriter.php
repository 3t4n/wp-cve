<?php

namespace TotalContestVendors\TotalCore\Export\Writers;

use TotalContestVendors\TotalCore\Export\Writer as WriterAbstract;

/**
 * CSV Writer.
 * @package TotalContestVendors\TotalCore\Export\Writers
 */
class CsvWriter extends WriterAbstract {
	const READ_CHUNK_SIZE = 1024;
	const CHARSET_UTF8 = 1;
	const CHARSET_ISO = 2;

	/**
	 * @var string $delimiter
	 */
	public $delimiter = ',';
	/**
	 * @var string $enclosure
	 */
	public $enclosure = '"';
	/**
	 * @var int $charset
	 */
	public $charset = self::CHARSET_UTF8;

	/**
	 * Get content type.
	 *
	 * @return string
	 */
	public function getContentType() {
		return 'text/csv; charset=' . ( $this->charset == self::CHARSET_UTF8 ? 'UTF-8' : 'ISO-8859-1' );
	}

	/**
	 * Get extension.
	 *
	 * @return string
	 */
	public function getDefaultExtension() {
		return 'csv';
	}

	/**
	 * Get content.
	 *
	 * @param array $columns
	 * @param array $data
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function getContent( array $columns, array $data ) {
		// Create a temporary filestream to use PHP CSV methods
		$fd = fopen( 'php://temp', 'r+' );

		// Write headers
		if ( $this->includeColumnHeaders ) {
			$columnHeaders = [];

			foreach ( $columns as $column ) {
				$columnHeaders[] = $column->title;
			}

			fputcsv( $fd, $columnHeaders, $this->delimiter, $this->enclosure );
		}

		// Write content
		foreach ( $data as $row ) {
			if ( ! is_array( $row ) ) {
				throw new \Exception( 'Row is not an array.' );
			}

			foreach ( $row as &$field ) {
				if ( $field instanceof \DateTime ) {
					$field = $field->format( 'Y-m-d H:i:s' );
				}
			}

			fputcsv( $fd, $row, $this->delimiter, $this->enclosure );
		}

		// Read content
		rewind( $fd );
		$content = '';
		while ( $chunk = fread( $fd, self::READ_CHUNK_SIZE ) ) {
			$content .= $chunk;
		}

		// Clean up
		fclose( $fd );

		// For MS Office
		$content = 'sep=,' . PHP_EOL . $content;

		// Return correctly encoded content
		switch ( $this->charset ) {
			case self::CHARSET_ISO:
				return utf8_decode( $content );
			default:
				return $content;
		}
	}
}