<?php
defined('ABSPATH') || exit;

class ImageLinks_Deactivator {
	public function deactivate() {
		global $wpdb;
		$table = $wpdb->prefix . IMAGELINKS_PLUGIN_NAME;

		$sql = $wpdb->prepare("SELECT COUNT(*) FROM {$table}");
		$count = $wpdb->get_var($sql);
		
		if($count > 0) {
			return;
		}

		$sql = $wpdb->prepare("DROP TABLE IF EXISTS {$table}");
		$wpdb->query($sql);
		
		delete_option('imagelinks_db_version');
		delete_option('imagelinks_activated');
		delete_option('imagelinks_settings');
		
		$this->delete_files(IMAGELINKS_PLUGIN_UPLOAD_DIR . '/');
	}
	
	private function delete_files($target) {
		if(is_dir($target)) {
			$files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned
			foreach($files as $file) {
				$this->delete_files($file);
			}
			rmdir($target);
		} else if(is_file($target)) {
			unlink($target);
		}
	}
}