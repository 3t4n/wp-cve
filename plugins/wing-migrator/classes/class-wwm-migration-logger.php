<?php


class Wwm_Logger {
	/** @var resource */
	private $logfile;

	/**
	 * Wwm_Logger constructor.
	 * @param string $file_path
	 */
	public function __construct( $file_path ) {
		$this->logfile = @fopen( $file_path, 'a+' );
	}

	public function __destruct() {
		$this->close();
	}

	public function close() {
		if ( isset( $this->logfile ) ) {
			@fclose( $this->logfile );
		}
	}

	public function debug( $message ) {
		$this->logging( 'DEBUG', $message );
	}

	public function info( $message ) {
		$this->logging( 'INFO', $message );
	}

	public function warning( $message ) {
		$this->logging( 'WARN', $message );
	}

	public function error( $message ) {
		$this->logging( 'ERROR', $message );
	}

	/**
	 * @param string $message
	 * @param Exception $e
	 */
	public function exception( $message, $e ) {
		$msg = "{$message}\n{$e->getMessage()}\n{$e->getTraceAsString()}";
		$this->logging( 'ERROR', $msg );
	}

	private function logging( $log_level, $message ) {
		$formated_date = date( "Y-m-d H:i:s" );
		$message_line = "{$formated_date}\t[{$log_level}]\t{$message}\n";
		@fwrite( $this->logfile, $message_line );
	}
}
