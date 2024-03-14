<?php

/**
 * This is an improved fork of fusonic-spreadsheet-export package.
 */

namespace TotalContestVendors\TotalCore\Export;

/**
 * Class Spreadsheet.
 * @package TotalContestVendors\TotalCore\Export
 */
class Spreadsheet {
	/**
	 * @var bool $appendDefaultExtension
	 */
	public $appendDefaultExtension = true;
	/**
	 * @var array $columns
	 */
	protected $columns = [];
	/**
	 * @var array $data
	 */
	protected $data = [];

	/**
	 * Add columns.
	 *
	 * @param Column $column
	 * @param        $amount
	 *
	 * @return $this
	 */
	public function addColumns( Column $column, $amount ) {
		for ( $i = 0; $i < $amount; $i ++ ) {
			$this->addColumn( $column );
		}

		return $this;
	}

	/**
	 * Add column.
	 *
	 * @param Column $column
	 *
	 * @return $this
	 */
	public function addColumn( Column $column ) {
		$this->columns[] = $column;

		return $this;
	}

	/**
	 * Add row.
	 *
	 * @param array $data
	 *
	 * @return $this
	 */
	public function addRow( array $data ) {
		$this->data[] = $data;

		return $this;
	}

	/**
	 * Download.
	 *
	 * @param Writer $writer
	 * @param null   $filename
	 */
	public function download( Writer $writer, $filename = null ) {
		$content = $this->get( $writer );

		// Send headers
		$this->sendGeneralHeaders();
		header( 'Content-Type: ' . $writer->getContentType() );
		header( 'Content-Length: ' . strlen( $content ) );
		if ( $filename !== null ) {
			$extension = pathinfo( $filename, PATHINFO_EXTENSION );
			if ( ! $extension && $this->appendDefaultExtension ) {
				$filename .= "." . $writer->getDefaultExtension();
			}

			header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
		}

		echo $content;
	}

	/**
	 * Get.
	 *
	 * @param Writer $writer
	 *
	 * @return mixed
	 */
	public function get( Writer $writer ) {
		return $writer->getContent( $this->columns, $this->data );
	}

	/**
	 * Send headers.
	 */
	protected function sendGeneralHeaders() {
		header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header( 'Pragma: public' ); // For Internet Explorer
	}

	/**
	 * Save to path.
	 *
	 * @param Writer $writer
	 * @param        $path
	 */
	public function save( Writer $writer, $path ) {
		$extension = pathinfo( $path, PATHINFO_EXTENSION );
		if ( ! $extension && $this->appendDefaultExtension ) {
			$path .= '.' . $writer->getDefaultExtension();
		}

		file_put_contents( $path, $this->get( $writer ) );
	}
}
