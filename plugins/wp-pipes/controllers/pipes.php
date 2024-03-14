<?php

/**
 * @package          WP Pipes plugin - PIPES
 * @version          $Id: pipes.php 141 2014-01-24 10:36:21Z tung $
 * @author           thimpress.com
 * @copyright        2014 thimpress.com. All rights reserved.
 * @license          GNU/GPL v3, see LICENSE
 */
defined('PIPES_CORE') or die('Restricted access');

class PIPESControllerPipes extends Controller
{
	public function __construct()
	{
	}

	function display($cachable = false, $urlparams = false)
	{
		return;
	}

	public function edit()
	{
		try {
			$id  = isset($_GET['id']) ? (int) sanitize_text_field($_GET['id']) : '';
			$edit_nonce_token = isset($_GET['edit_nonce']) ? $_GET['edit_nonce'] : '';

			$nonce_action = 'edit_none_token';

			if (!wp_verify_nonce($edit_nonce_token, $nonce_action)) {
				throw new Exception("Nonce verification failed! Unauthorized edition for pipe with ID: " . $id);
			}

			$url = admin_url() . 'admin.php?page=' . PIPES::$__page_prefix . '.pipe&id=' . $id;
			header('Location: ' . $url);
		} catch (Exception $e) {
			$res = $e->getMessage();
			$_SESSION['PIPES']['messages'][] = array('type' => 'error', 'msg' => $res);
		}
	}

	// Add nonce token
	public function delete()
	{
		// Initialize $res as an empty string 
		$res = '';
		try {
			$mod = $this->getModel('pipes');
			// Make sure the id is an absolute integer
			$id = isset($_GET['id']) ? absint($_GET['id']) : 0;
			$nonce = isset($_GET['delete_nonce']) ? $_GET['delete_nonce'] : '';

			// Define the nonce action. Make sure it matches the one used when generating the nonce.
			$nonce_action = 'delete_none_token';

			if (!wp_verify_nonce($nonce, $nonce_action)) {
				throw new Exception("Nonce verification failed! Unauthorized deletion for pipe with ID: " . $id);
			} else {
				// Nonce is valid, perform the delete operation for this ID
				$res = $mod->delete($id);
				$_SESSION['PIPES']['messages'][] = array('type' => 'message', 'msg' => $res);
			}
		} catch (Exception $e) {
			$res = $e->getMessage();
			$_SESSION['PIPES']['messages'][] = array('type' => 'error', 'msg' => $res);
		}
		if (isset($_SERVER['HTTP_REFERER'])) {
			$url = remove_query_arg(array('id', 'action', 'action2', 'nonce'), $_SERVER['HTTP_REFERER']);
			wp_safe_redirect($url);
		} else {
			echo "Oops! Something went wrong. Please go back to the previous page.";
		}

		exit();
	}

	public function copy()
	{
		$mod = $this->getModel('pipes');
		$id  = isset($_GET['id']) ? array_map('sanitize_text_field', $_GET['id']) : 0;

		$nonce = isset($_GET['pipe_nonce']) ? $_GET['pipe_nonce'] : '';
		$nonce_action = 'pipe_non';
		// Check the validity of the nonce before copying

		if (!wp_verify_nonce($nonce, $nonce_action)) {
			// Nonce verification failed
			$res = 'Nonce verification failed!';
		} else {
			if ($id == '') {
				$res = "Please pick up at least 1 pipe first!";
			} else {
				$res = $mod->copy($id);
			}
		}
		PIPES::add_message($res);
		if (isset($_SERVER['HTTP_REFERER'])) {
			$url = remove_query_arg(array('id', 'action', 'action2', 'nonce'), $_SERVER['HTTP_REFERER']);
			header('Location: ' . $url);
		} else {
			echo "Oops! Something went wrong. Please go back to the previous page.";
		}

		exit();
	}

	public function publish()
	{
		$mod = $this->getModel('pipes');
		$id  = isset($_GET['id']) ? array_map('sanitize_text_field', $_GET['id']) : 0;

		$nonce = isset($_GET['pipe_nonce']) ? $_GET['pipe_nonce'] : '';
		$nonce_action = 'pipe_non';

		// Check the validity of the nonce before publishing
		if (!wp_verify_nonce($nonce, $nonce_action)) {
			// Nonce verification failed
			$res = 'Nonce verification failed!';
		} else {
			if ($id == '') {
				$res = "Please pick up at least 1 pipe first!";
			} else {
				$res = $mod->change_status($id, 1);
			}
		}
		PIPES::add_message($res);

		if (isset($_SERVER['HTTP_REFERER'])) {
			$url = remove_query_arg(array('id', 'action', 'action2', 'nonce'), $_SERVER['HTTP_REFERER']);
			header('Location: ' . $url);
		} else {
			echo "Oops! Something went wrong. Please go back to the previous page.";
		}


		exit();
		//$this->display();
	}

	public function create_tables()
	{
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		#--------------------------------------------------
		# Add user_meta for all admins
		#--------------------------------------------------
		$users     = get_users();
		$user_meta = array('pipes_help_box' => 1, 'pipes_per_page' => 20, 'addons_per_page' => 20);
		foreach ($users as $user) {
			if (is_super_admin($user->ID)) {
				foreach ($user_meta as $meta_key => $value) {
					$meta_value = get_user_meta($user->ID, $meta_key, true);
					if ($meta_value == '') {
						update_user_meta($user->ID, $meta_key, $value);
					}
				}
			}
		}


		#--------------------------------------------------
		# Create Items table
		#--------------------------------------------------


		if ($wpdb->has_cap('collation')) {
			if (!empty($wpdb->charset)) {
				$collation .= "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if (!empty($wpdb->collate)) {
				$collation .= " COLLATE $wpdb->collate";
			}
		}

		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'wppipes_items` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(255) NOT NULL,
			`published` tinyint(1) NOT NULL,
			`engine` varchar(100) NOT NULL,
			`engine_params` text NOT NULL,
			`adapter` varchar(100) NOT NULL,
			`adapter_params` text NOT NULL,
			`inherit` int(11) NOT NULL DEFAULT "0",
			`inputs` text NOT NULL,
			`outputs` text NOT NULL,
			PRIMARY KEY (`id`)
	  	) ' . $collation;
		dbDelta($sql);


		#--------------------------------------------------
		# Create Pipes table
		#--------------------------------------------------
		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'wppipes_pipes` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`code` varchar(100) NOT NULL,
			`name` varchar(100) NOT NULL,
			`item_id` int(11) NOT NULL,
			`params` text NOT NULL,
			`ordering` int(11) NOT NULL,
			PRIMARY KEY (`id`)
		) ' . $collation;
		dbDelta($sql);
		if (isset($_SERVER['HTTP_REFERER'])) {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			echo "Oops! Something went wrong. Please go back to the previous page.";
		}

		exit();
	}

	// Handle nonce token of restore feature
	public function pipes_restore_default_options()
	{
		$res = '';
		try {
			global $pipes_settings;
			include_once(dirname(dirname(__FILE__)) . DS . 'settings-init.php');

			// Get nonce token from url
			$nonce = isset($_GET['nonce']) ? $_GET['nonce'] : '';
			$nonce_action = 'nonce_token';

			if (!wp_verify_nonce($nonce, $nonce_action)) {
				throw new Exception("Nonce verification failed!");
			}

			foreach ($pipes_settings as $section) {
				foreach ($section as $value) {
					if (isset($value['default']) && isset($value['id'])) {
						update_option($value['id'], $value['default']);
						$res = "Restore successfully.";
					}
				}
			}
			$_SESSION['PIPES']['messages'][] = array('type' => 'message', 'msg' => $res);
		} catch (Exception $e) {
			$res = $e->getMessage();
			$_SESSION['PIPES']['messages'][] = array('type' => 'error', 'msg' => $res);
		}

		if (isset($_SERVER['HTTP_REFERER'])) {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			echo "Oops! Something went wrong. Please go back to the previous page.";
		}

		exit();
	}


	// Add nonce token for feature: Clear cache and delete the folder that created in uploads/wppipes 
	public function delete_cache_folder()
	{
		$res = '';
		try {
			$nonce = isset($_GET['nonce']) ? $_GET['nonce'] : '';
			$nonce_action = 'nonce_token';

			if (!wp_verify_nonce($nonce, $nonce_action)) {
				throw new Exception("Nonce verification failed!");
			}

			$dirPath = OGRAB_CACHE;
			$this->deleteDirCache($dirPath);

			$res = "Delete cache folder successfully.";
			$_SESSION['PIPES']['messages'][] = array('type' => 'message', 'msg' => $res);
		} catch (Exception $e) {
			$res = $e->getMessage();
			$_SESSION['PIPES']['messages'][] = array('type' => 'error', 'msg' => $res);
		}


		// PIPES::add_message($res);
		if (isset($_SERVER['HTTP_REFERER'])) {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			echo "Oops! Something went wrong. Please go back to the previous page.";
		}

		exit();
	}

	function deleteDirCache($dirPath)
	{
		if (!is_dir($dirPath)) {
			throw new InvalidArgumentException("$dirPath must be a directory");
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				self::deleteDirCache($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dirPath);
	}

	public function move_to_draft()
	{
		if (isset($_GET['id']) && isset($_GET['nonce']) && wp_verify_nonce($_GET['nonce'], 'move_to_draft_action')) {
			$mod = $this->getModel('pipes');
			$id  = isset($_GET['id']) ? array_map('sanitize_text_field', $_GET['id']) : 0;
			if ($id == '') {
				$res = "Please pick up at least 1 pipe first!";
			} else {
				$res = $mod->change_status($id, 0);
			}
			PIPES::add_message($res);

			if (isset($_SERVER['HTTP_REFERER'])) {
				$url = remove_query_arg(array('id', 'action', 'action2'), $_SERVER['HTTP_REFERER']);
				header('Location: ' . $url);
			} else {
				echo "Oops! Something went wrong. Please go back to the previous page.";
			}

			exit();
		} else {
			$res = "Nonce verification failed!";
			PIPES::add_message($res);
		}
	}

	public function update_meta()
	{
		if (isset($_POST['uid']) && isset($_POST['select']) && isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'update_meta_action')) {
			$user  = sanitize_text_field($_POST['uid']);
			$value = sanitize_text_field($_POST['select']);
			update_user_meta($user, 'pipes_help_box', $value);

			return 'Success!';
		} else {
			$res = "Nonce verification failed!";
			PIPES::add_message($res);
		}
	}

	public function export_to_share()
	{
		$mod = $this->getModel('pipes');
		$id  = isset($_GET['id']) ? (is_array($_GET['id']) ? array_map('sanitize_text_field', $_GET['id']) : $_GET['id']) : 0;
		if ($id == '') {
			PIPES::add_message("Please pick up at least 1 pipe first!");
			if (isset($_SERVER['HTTP_REFERER'])) {
				$url = remove_query_arg(array('id', 'action', 'action2'), $_SERVER['HTTP_REFERER']);
				header('Location: ' . $url);
			} else {
				echo "Oops! Something went wrong. Please go back to the previous page.";
			}
			exit();
		}
		$set_template = isset($_GET['set_template']) ? sanitize_text_field($_GET['set_template']) : 0;
		$res          = $mod->export_to_share($id);
		//PIPES::add_message($res->msg);
		if (count($res->result) == 1) {
			$file_name = sanitize_title($res->result[0]->name) . '.pipe';
		} else {
			$file_name = 'pipes-' . date('d-m-Y', time()) . '.pipe';
		}
		$upload_dir = wp_upload_dir();
		if ($set_template) {
			$file_name = $upload_dir['basedir'] . DS . 'wppipes' . DS . 'templates' . DS . $file_name;
			if (!is_file($file_name)) {
				ogbFolder::create($upload_dir['basedir'] . DS . 'wppipes' . DS . 'templates');
			}
		}
		/*$fp = fopen( $file_name, 'w' );
		foreach ( $res->result as $result ) {
			fwrite( $fp, json_encode( $result ) . "\n" );
		}
//var_dump(filesize("$file_name"));die;
		fclose( $fp );*/
		$output_content = '';
		foreach ($res->result as $result) {
			$output_content .= json_encode($result) . "\n";
		}
		if ($set_template) {
			PIPES::add_message($res->msg);
			$url = admin_url() . 'admin.php?page=' . PIPES::$__page_prefix . '.pipe&id=' . $id;
			header('Location: ' . $url);
		}
		ob_start();
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		//header( "Content-Length: " . filesize( "$file_name" ) . ";" );
		header("Content-Disposition: attachment; filename=$file_name");
		header("Content-Transfer-Encoding: binary");

		//readfile( $file_name );
		echo $output_content;
		ob_end_flush();
		exit();
	}

	public function import_from_file()
	{
		$upload_dir = wp_upload_dir();
		$mod        = $this->getModel('pipes');
		$id         = isset($_GET['id']) ? (int) sanitize_text_field($_GET['id']) : 0;
		$file_name  = isset($_GET['file_name']) ? sanitize_text_field($_GET['file_name']) : '';
		if (isset($_FILES["file_import"]["name"])) {
			$filename = $_FILES["file_import"]["tmp_name"];
		} elseif (isset($_GET['url'])) {
			$filename = sanitize_text_field($_GET['url']);
		} elseif (is_file($upload_dir['basedir'] . DS . 'wppipes' . DS . 'templates' . DS . $file_name)) {
			$filename = $upload_dir['basedir'] . DS . 'wppipes' . DS . 'templates' . DS . $file_name;
		}
		$file_content = file_get_contents($filename);
		$items        = explode("\n", $file_content);
		$new_pipes    = array();
		if ($file_content == '') {
			$new_pipes[] = "The file has not content!";
		}
		foreach ($items as $value) {
			if ($value != '') {
				if (substr($value, 0, 1) == '{') {
					$item = json_decode($value);
				} else {
					$item = json_decode(substr($value, 3));
				}
				if (!is_object($item)) {
					$new_pipes[] = "There is something wrong with the structure of file's content!";
					continue;
				}
				$item->current_id = $id;
				$new_pipes[]      = $mod->import_from_file($item);
			}
		}
		$message = implode("</br>", $new_pipes);
		PIPES::add_message($message);
		if (isset($_GET['url'])) {
			if (isset($_SERVER['HTTP_REFERER'])) {
				$url = remove_query_arg(array('task', 'url'), $_SERVER['HTTP_REFERER']);
				header('Location: ' . $url);
			} else {
				echo "Oops! Something went wrong. Please go back to the previous page.";
			}

			exit();
		} elseif ($id > 0) {
			$url = admin_url() . 'admin.php?page=' . PIPES::$__page_prefix . '.pipe&id=' . $id;
			header('Location: ' . $url);
			exit();
		}
	}
}
