<?php

namespace TotalContest\Writers;

/**
 * JSON Writer.
 * @package TotalContestVendors\TotalCore\Export\Writers
 */
class PartialJsonWriter extends PartialWriter {
	/**
	 * Content type.
	 *
	 * @return string
	 */
	public function getContentType() {
		return 'text/html; charset=UTF-8';
	}

	/**
	 * File extension.
	 *
	 * @return string
	 */
	public function getDefaultExtension() {
		return 'json';
	}

	/**
	 * Get content.
	 *
	 * @param array $columns
	 * @param array $data
	 *
	 * @return mixed|string
	 */
	public function getContent( array $columns, array $data ) {
		if ( $this->isFirstLine() ) {
			return '[';
		}

		if ( $this->isLastLine() ) {
			return ']';
		}

		$columns = array_map( function ( $column ) {
			return $column->title;
		}, $columns );

		$data = array_map( function ( $item ) use ( $columns ) {
			return array_combine( $columns, $item );
		}, $data );

		return substr( substr( json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ), 1 ), 0, - 1 ) . ',';
	}
}
