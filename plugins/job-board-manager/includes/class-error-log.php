<?php



if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_job_bm_error_log{
	
	public function __construct(){

		//add_action('add_meta_boxes', array($this, 'meta_boxes_job'));
		//add_action('save_post', array($this, 'meta_boxes_job_save'));

		}
		
		

	public function job_bm_error_data($error_data){
		$error_data = $error_data[0];
		//update_option('job_bm_error_log',$error_data);

		
		$file = job_bm_plugin_dir."error-log/logs.txt";
		// Open the file to get existing content
/*

		$current = file_get_contents($file);

		$current .= $error_data['type'].' - '.$error_data['status'].' - '.$error_data['message'].'\n';
		$current = str_replace('\n', PHP_EOL, $current);

		file_put_contents($file, $current);

*/
		$text = $error_data['type'].' - '.$error_data['status'].' - '.$error_data['message'];
		$file = fopen($file,'a');
		
		fwrite($file, $text);
		fclose($file);
		

		
		}
		


	}
	
new class_job_bm_emails();