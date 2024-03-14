<?php
define('FILE_LOG', SOCIAL2BLOG_PLUGINGDIR . '/log.txt');

/**
 * Social2blog_Log
 * @author bauhausk
 *
 */
class Social2blog_Log {

	/**
	 * Messaggi log generici
	 * @param unknown $message
	 */
	public static function log($message) {
		$db_backtrace = debug_backtrace();
		$backtrace_function = $db_backtrace[1]['function'];
		$backtrace_line = $db_backtrace[1]['line'];
		$backtrace_file = $db_backtrace[1]['file'];
		//$message = str_ireplace("\t", " ", $message);

		if (SOCIAL2BLOG_DEBUG == true){
			$messaggio = "[LOG] ".$backtrace_file."->".$backtrace_function."() [".$backtrace_line."]: ".print_r($message, true);
			error_log( $messaggio );
			Social2blog_Log::log2file( $messaggio );
		}

	}
	/**
	 * Messaggi odebug
	 * @param unknown $message
	 */
	public static function debug($message) {
		$db_backtrace = debug_backtrace();
		$backtrace_function = $db_backtrace[1]['function'];
		$backtrace_line = $db_backtrace[1]['line'];
		$backtrace_file = $db_backtrace[1]['file'];
		//$message = str_ireplace("\t", " ", $message);

		if (SOCIAL2BLOG_DEBUG == true){
			$messaggio = "[DEBUG] ".$backtrace_file."->".$backtrace_function."() [".$backtrace_line."]: ".print_r($message, true);
			error_log( $messaggio );
			Social2blog_Log::log2file( $messaggio );
		}
	}
	/**
	 * Messaggi di errore
	 * @param unknown $message
	 */
	public static function error($message) {

		$db_backtrace = debug_backtrace();
		$backtrace_function = $db_backtrace[1]['function'];
		$backtrace_line = $db_backtrace[1]['line'];
		$backtrace_file = $db_backtrace[1]['file'];
		$message = str_ireplace("\t", " ", $message);

		$messaggio = "[ERROR] ".$backtrace_file."->".$backtrace_function."() [".$backtrace_line."]: ".print_r($message, true);

		error_log( $messaggio );
		if (SOCIAL2BLOG_DEBUG == true){
			Social2blog_Log::log2file( $messaggio );
		}

	}
	/**
	 * Messaggi ingo
	 * @param unknown $message
	 */
	public static function info($message) {
		if (SOCIAL2BLOG_DEBUG == true)
			error_log(print_r("[INFO]".$message, true));
	}

	/**
	 * Messaggi warning
	 * @param unknown $message
	 */
	public static function warning($message) {
		$db_backtrace = debug_backtrace();
		$backtrace_function = $db_backtrace[1]['function'];
		$backtrace_line = $db_backtrace[1]['line'];
		$backtrace_file = $db_backtrace[1]['file'];

		$messaggio = "[WARNING] ".$backtrace_file."->".$backtrace_function."() [".$backtrace_line."]: ".print_r($message, true);

		error_log( $messaggio );
		if (SOCIAL2BLOG_DEBUG == true){
			Social2blog_Log::log2file( $messaggio );
		}

	}

	/**
	 * Messaggi warning
	 * @param unknown $message
	 */
	 public static function log2file($message){
		 file_put_contents(FILE_LOG, $message.PHP_EOL, FILE_APPEND);
	}
}
