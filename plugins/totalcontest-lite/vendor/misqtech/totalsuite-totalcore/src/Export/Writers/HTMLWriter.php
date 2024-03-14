<?php

namespace TotalContestVendors\TotalCore\Export\Writers;

use TotalContestVendors\TotalCore\Export\Writer as WriterAbstract;

/**
 * HTML Writer.
 * @package TotalContestVendors\TotalCore\Export\Writers
 */
class HTMLWriter extends WriterAbstract {
	const READ_CHUNK_SIZE = 1024;

	const CHARSET_UTF8 = 1;
	const CHARSET_ISO = 2;

	/**
	 * @var int $charset
	 */
	public $charset = self::CHARSET_UTF8;

	/**
	 * Content type.
	 *
	 * @return string
	 */
	public function getContentType() {
		return 'text/html; charset=' . ( $this->charset === self::CHARSET_UTF8 ? 'UTF-8' : 'ISO-8859-1' );
	}

	/**
	 * File extension.
	 *
	 * @return string
	 */
	public function getDefaultExtension() {
		return 'html';
	}

	/**
	 * Get content.
	 *
	 * @param array $columns
	 * @param array $data
	 *
	 * @return string
	 */
	public function getContent( array $columns, array $data ) {
		// Create a temporary filestream
		$fileStream = fopen( 'php://temp', 'r+' );

		// Header
		fwrite( $fileStream, $this->getHeader() );

		// Write headers
		$callback = function ( $column ) {
			return $this->getCell( $column->title );
		};

		if ( $this->includeColumnHeaders ):
			fwrite( $fileStream, $this->getRow( implode( '', array_map( $callback, $columns ) ) ) );
		endif;

		// Write content
		foreach ( $data as $row ) :
			if ( ! is_array( $row ) ):
				throw new \RuntimeException( 'Row is not an array.' );
			endif;

			foreach ( $row as &$field ) :
				if ( $field instanceof \DateTime ):
					$field = $field->format( 'Y-m-d H:i:s' );
				elseif ( is_array( $field ) ):
					$field = json_encode( $field, JSON_UNESCAPED_UNICODE );
				endif;
			endforeach;

			fwrite( $fileStream, $this->getRow( implode( '', array_map( [ $this, 'getCell' ], $row ) ) ) );
		endforeach;

		// Footer
		fwrite( $fileStream, $this->getFooter() );

		// Read content
		rewind( $fileStream );

		$content = '';
		while ( $chunk = fread( $fileStream, self::READ_CHUNK_SIZE ) ):
			$content .= $chunk;
		endwhile;

		// Clean up
		fclose( $fileStream );

		// Return correctly encoded content
		switch ( $this->charset ):
			case self::CHARSET_ISO:
				return utf8_decode( $content );
			default:
				return $content;
		endswitch;
	}

	/**
	 * File header.
	 *
	 * @param string $title
	 *
	 * @return string
	 */
	protected function getHeader( $title = '' ) {
		$title = strip_tags( $title );

		return "<!doctype html><html lang=\"en\"><head><meta charset=\"UTF-8\"><title>{$title}</title><style type=\"text/css\">* {margin:0;padding:0;border:0;outline:0;font-size:100%;font-family: \"Helvetica Neue\", Helvetica, Arial, sans-serif;vertical-align:baseline;background:transparent;}table {width: 100%;min-height: .01%;overflow-x: auto;max-width: 100%;margin-bottom: 1rem;border-spacing: 0;border-collapse: collapse;}table th,table td {padding: .75rem;line-height: 1.5;vertical-align: top;border-top: 1px solid #eceeef;}table tbody tr:nth-of-type(odd) {background-color: #f9f9f9;}</style></head><body><table>";
	}

	/**
	 * Table cell.
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	protected function getCell( $content = '' ) {
		return "<td>{$content}</td>";
	}

	/**
	 * Table row.
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	protected function getRow( $content = '' ) {
		return "<tr>{$content}</tr>";
	}

	/**
	 * File footer.
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	protected function getFooter( $content = '' ) {
		return "</table>{$content}</body></html>";
	}
}
