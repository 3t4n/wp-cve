<?php

namespace WpLHLAdminUi\Logger;

use WpLHLAdminUi\Helpers\FolderPathUtil;
use WpLHLAdminUi\Helpers\DateTimeUtil;

class TxtLogger {

	private $file_directory = '';
	private $file_url = '';
	private $filename = '';
	private $dateTimeUtil = null;
	private $options = [
		'timestamp' => true,
		'delimiter' => ' :: '
	];

	public function __construct($folder_name, $filename,  $options = false) {

		// Date
		$this->dateTimeUtil = new DateTimeUtil();

		// Folder
		$folderPath = new FolderPathUtil($folder_name);
		$this->file_directory = $folderPath->getDirectory();
		$this->file_url = $folderPath->getUrl();

		// File name
		$this->filename = sanitize_file_name($filename);

		// Options
		if (!empty($options)) {
			$this->options = array_merge($this->options, $options);
		}
	}

	/**
	 * Empty Log
	 */
	public function empty_log() {

		if (!is_dir($this->file_directory)) {
			mkdir($this->file_directory, 0755, true);
		}
		$file = fopen($this->file_directory . '/' . $this->filename, 'r+');

		ftruncate($file, 0);
		fclose($file);

		return false;
	}

	/**
	 * Write to Quicklog
	 */
	public function write_log($message) {

		if (!is_dir($this->file_directory)) {
			mkdir($this->file_directory, 0755, true);
		}

		$time_stamp = '';
		if ($this->options['timestamp']) {
			$time_stamp = $this->dateTimeUtil->date_time_w_sec();
		}

		$message = $time_stamp . $this->options['delimiter'] . $message . PHP_EOL;
		$file = fopen($this->file_directory . '/' . $this->filename, 'a');

		$write = fputs($file, $message);
		fclose($file);

		return false;
	}

	/**
	 * Print full Log as TextArea
	 */
	public function get_log_as_textarea() {

		$log = $this->file_directory . '/' . $this->filename;
		// $file = fopen( $this->file_directory . '/' . $this->filename, 'r' );

		echo '<textarea name="message" rows="20" cols="220" readonly>';
		echo file_get_contents($log);
		echo '</textarea>';

		// fclose( $file );

		return false;
	}

	/**
	 * Read Log size
	 */
	public function get_log_size() {

		$log = $this->file_directory . '/' . $this->filename;
		echo round(filesize($log) / 1000, 2) . "KB";

		return false;
	}


	/**
	 * Read full Log as HTML
	 */
	public function get_log_as_html() {

		$log = $this->file_directory . '/' . $this->filename;
		echo nl2br(file_get_contents($log));
		return false;
	}
}
