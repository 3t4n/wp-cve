<?php if (!defined('ABSPATH')) {exit;}
/**
* Writes plugin logs
*
* @link			https://icopydoc.ru/
* @since		1.3.0
*/

final class GUPFW_Error_Log {
	protected $text_to_log;
	protected $log_dir_name; // /home/site.ru/public_html/wp-content/uploads/best-rating-pageviews
	protected $log_file_name; // /home/site.ru/public_html/wp-content/uploads/best-rating-pageviews/plugin.log

	public function __construct($text_to_log, $log_dir_name = GUPFW_PLUGIN_UPLOADS_DIR_PATH) {
		$this->text_to_log = $text_to_log;
		if (is_dir($log_dir_name)) {
			$this->log_file_name = $log_dir_name.'/plugin.log';
		} else {
			if (mkdir($log_dir_name)) {
				$this->log_file_name = $log_dir_name.'/plugin.log';
			} else {
				$this->log_file_name = false;
				error_log('ERROR: Report from Class ErrorLog: No folder "'.$log_dir_name.'"; Line: '.__LINE__, 0);
			}
		}

		if ($this->keeplogs_status()) { // если включено вести логи
			if ($this->get_filename() === false) {
				return;
			} else {
				$this->save_log($text_to_log);
			}
		}
	}

	public function __toString() {
		return $this->get_array_as_string($this->text_to_log);
	}

	protected function save_log($text_to_log) {
		if (is_array($text_to_log)) {$r = get_array_as_string($text_to_log); unset($text_to_log); $text_to_log = $r;} 
		file_put_contents($this->get_filename(), '['.date('Y-m-d H:i:s').'] '.$text_to_log.PHP_EOL, FILE_APPEND);
	}

	protected function get_filename() {
		return $this->log_file_name;
	}

	protected function keeplogs_status() {
		if (is_multisite()) {
			$v = get_blog_option(get_current_blog_id(), 'gupfw_keeplogs');
		} else {
			$v = get_option('gupfw_keeplogs');
		}	
		if ($v === 'on') {
			return true;
		} else {
			return false;
		}
	}

	protected function get_array_as_string($text, $new_line = PHP_EOL, $i = 0, $res = '') {
		$tab = ''; for ($x = 0; $x < $i; $x++) {$tab = '---'.$tab;}
		if (is_object($text)) {$text = (array)$text;}
		if (is_array($text)) { 
			$i++;
			foreach ($text as $key => $value) {
				if (is_array($value)) {	// массив
					$res .= $new_line .$tab."[$key] => (".gettype($value).")";
					$res .= $tab.$this->get_array_as_string($value, $new_line, $i);
				} else { // не массив
					$res .= $new_line .$tab."[$key] => (".gettype($value).")". $value;
				}
			}
		} else {
			$res .= $new_line .$tab.$text;
		}
		return $res;
	}
}
?>