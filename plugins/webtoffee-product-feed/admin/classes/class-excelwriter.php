<?php
/**
 * CSV writing section of the plugin
 *
 * @link       
 *
 * @package  Wt_Import_Export_For_Woo 
 */
if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__. '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Webtoffee_Product_Feed_Sync_Basic_Excelwriter
{
	public $file_path='';
	public $data_ar='';
	public $csv_delimiter='';
	public $use_bom=false;
	public $spreadsheet;
	public function __construct($file_path, $offset, $csv_delimiter=",", $use_bom=false)
	{
		$this->csv_delimiter=$csv_delimiter;
		$this->file_path=$file_path;
                $this->use_bom = $use_bom;                
		$this->get_file_pointer($offset);
	}
	
	/**
	* This is used in XML to CSV converting 
	*/
	public function write_row($row_data, $offset=0, $is_last_offset=false)
	{
		if($is_last_offset)
		{
			$this->close_file_pointer();
		}else
		{
			if($offset==0) /* set heading */
			{
				$this->fput_csv($this->file_pointer, array_keys($row_data), $this->csv_delimiter);
			}
			$this->fput_csv($this->file_pointer, $row_data, $this->csv_delimiter);
		}
	}

	/**
	* 	Create CSV 
	*
	*/
	public function write_to_file($export_data, $offset, $is_last_offset, $to_export)
	{		
		$this->export_data=$export_data;	
		$this->set_head($export_data, $offset, $this->csv_delimiter);
		$this->set_content($export_data, $this->csv_delimiter);
		$this->close_file_pointer();
	}
	private function get_file_pointer($offset)
	{
		if($offset==0)
		{
			$this->spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		}else
		{
			$this->spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($this->file_path);
		}
	}
	private function close_file_pointer()
	{
		if($this->spreadsheet!=null)
		{
			$this->spreadsheet->disconnectWorksheets();
		}
	}
	/**
	 * Escape a string to be used in a CSV context
	 *
	 * Malicious input can inject formulas into CSV files, opening up the possibility
	 * for phishing attacks and disclosure of sensitive information.
	 *
	 * Additionally, Excel exposes the ability to launch arbitrary commands through
	 * the DDE protocol.
	 *
	 * @see http://www.contextis.com/resources/blog/comma-separated-vulnerabilities/
	 * @see https://hackerone.com/reports/72785
	 *
	 * @param string $data CSV field to escape.
	 * @return string
	 */
	public function escape_data( $data )
	{
		$active_content_triggers = apply_filters( 'wt_escape_operators_list', array( '=', '+', '-', '@' ) );

		if ( in_array( mb_substr( $data, 0, 1 ), $active_content_triggers, true ) ) {
			$data = "'" . $data;
		}

		return $data;
	}
	public function format_data( $data, $column = '' )
	{
		if ( ! is_scalar( $data ) ) {
			if ( is_a( $data, 'WC_Datetime' ) ) {
				$data = $data->date( 'Y-m-d G:i:s' );
			} else {
				$data = ''; // Not supported.
			}
		} elseif ( is_bool( $data ) ) {
			$data = $data ? 1 : 0;
		}

		$use_mb = function_exists( 'mb_detect_encoding' );

		$keep_encoding = apply_filters('wt_iew_importer_keep_encoding', true);
		if ( $use_mb && $keep_encoding ) {
			$encoding = mb_detect_encoding( $data, 'UTF-8, ISO-8859-1', true );                         
			if('UTF-8' !== $encoding){                            
				$data = utf8_encode( $data );
			}
		}
		return $this->escape_data( $data );
	}
	private function set_content($export_data, $delm = ',')
	{
		if (isset($export_data) && isset($export_data['body_data']) && count($export_data['body_data']) > 0) {
			$row_datas = array_values($export_data['body_data']);
			$rowArray = [];

			foreach ($row_datas as $row_data) {
				foreach ($row_data as $key => $value) {
					$row_data[$key] = $this->format_data($value);
				}

				$rowArray[] = $row_data;
			}

			$startRow = $this->spreadsheet->getActiveSheet()->getHighestRow() + 1;
			$endRow = $startRow + count($rowArray) - 1;

			$this->spreadsheet->getActiveSheet()->insertNewRowBefore($startRow, count($rowArray));
			$this->spreadsheet->getActiveSheet()->fromArray($rowArray, null, 'A' . $startRow);

			// Adjust auto-filter range if needed
			$this->spreadsheet->getActiveSheet()->setAutoFilter("A{$startRow}:Z{$endRow}");

			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->spreadsheet);
			$writer->save($this->file_path);
		}
	}
	private function set_head($export_data, $offset, $delm=',')
	{
		if($offset==0 && isset($export_data) && isset($export_data['head_data']) && count($export_data['head_data'])>0)
		{
			foreach($export_data['head_data'] as $key => $value) 
			{
				$export_data['head_data'][$key]=$this->format_data($value);
			}
			
			//error_log(print_r($export_data['head_data'],1));
			$this->spreadsheet->setActiveSheetIndex(0);
			$this->spreadsheet->getActiveSheet()->fromArray($export_data['head_data'],NULL,'A1');
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->spreadsheet);
			$writer->save($this->file_path);

			
			
		}
	}
	private function fput_csv($fp, $row, $delm=',', $encloser='"' )
	{
		fputcsv($fp,$row,$delm,$encloser);
	}
	private function array_to_csv($arr, $delm=',', $encloser='"')
	{
		$fp=fopen('php://memory','rw');
		foreach($arr as $row)
		{
			$this->fput_csv($fp, $row, $delm, $encloser);
		}
		rewind($fp);
		$csv=stream_get_contents($fp);
		fclose($fp);
		return $csv;
	}
}