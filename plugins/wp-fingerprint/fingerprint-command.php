<?php
class Fingerprint_Command extends WP_CLI_Command {

	/*
	 * Check Plugin against Checksums
	 * @alias run
	 */
	function check( $args, $assoc_args )
	{
		$wpfingerprint = new WPFingerprint_Plugin();
		if(isset( $wpfingerprint ))
		{
			$plugin = false;
			if(isset($args[0])){
				$plugin = $args[0];
			}
			$diffs = $wpfingerprint->runner($plugin);
			$message = 'WP Fingerprint has found: ';
			$message .= strval($diffs['new_issues']).' New Issues ';
			$message .= 'has updated '.strval($diffs['updated_issues']).' issues';
			$message .= ' and removed '.strval($diffs['removed_isues']).' issues';
			WP_CLI::success( $message );
		}else{
			WP_CLI::error( 'Error Running WP Fingerprint' );
		}

	}
	/*
	 * Create Checksum for a given plugin
	 *
	 */
	function generate( $args, $assoc_args )
	{
		$wpfingerprint = new WPFingerprint_Plugin();
		if(empty($args)) $args = array();
		$generate = $wpfingerprint->generate($args[0]);
		if(!empty($generate) || !is_array($generate)){
			WP_CLI::error( 'Generate Plugin failed' );
		}
		else{
			return json_encode($generate);
		}
	}
	/*
	 * Show any plugins that have currently failed.
	 *
	 */
	function report( $args, $assoc_args )
	{
		$format = 'table';
		if(isset($assoc_args['format']))
		{
			$format_options = array(
				'table','csv','yaml','json'
			);
			if(in_array( $assoc_args['format'], $format_options ))
			{
				$format = $assoc_args['format'];
			}
		}
		$plugin = false;
		if(!empty($args))
		{
			$plugin = $args[0];
		}
		$wpfingerprint = new WPFingerprint_Plugin();
		$report = $wpfingerprint->runner->report($plugin);
		WP_CLI\Utils\format_items( $format, $report, 'plugin,file,checksum_local, checksum_remote, last_checked' );
	}

	function diff( $args, $assoc_args )
	{
		if(!empty($args))
		{
			$plugin = $args[0];
			$file = $args[1];
		}
		if(!isset($plugin))
		{
			WP_CLI::error( 'Need to specify a plugin' );
			die;
		}
		if(!isset($file))
		{
			WP_CLI::error( 'Need to specify a file' );
			die;
		}
		$wpfingerprint = new WPFingerprint_Plugin();
		$version = $wpfingerprint->runner->plugins->get_plugin_version($plugin);
		$wpfingerprint->runner->load('transport-wporg');
		$transport_name = 'WPFingerprint_Transport_Wporg';
 		$transport_name = new $transport_name;
		$local_file = $wpfingerprint->runner->plugins->read_file_contents($plugin,$file);
		if(!$local_file)
		{
			WP_CLI::error( 'Local file could not be found' );
			die;
		}
		$remote_file = $transport_name->get_plugin_file($plugin,$version,$file);
		if(!$remote_file)
		{
			WP_CLI::error( 'Remote file could not be found' );
			die;
		}
		$diff_file = $wpfingerprint->runner->diff->check_file_diff($local_file,$remote_file);
		$format = 'table';
		if(isset($assoc_args['format']))
		{
			$format_options = array(
				'table','csv','yaml','json'
			);
			if(in_array( $assoc_args['format'], $format_options ))
			{
				$format = $assoc_args['format'];
			}
		}
		$report = $wpfingerprint->runner->diff->show_diffs($diff_file);
		WP_CLI\Utils\format_items( $format, $report, 'line,local,remote' );
	}
	/*
	 * Delete the logs
	 *
	 */
	function clear( $args, $assoc_args )
	{
		$wpfingerprint = new WPFingerprint_Plugin();
		$wpfingerprint->runner->model_checksums->clear();
		WP_CLI::success( 'Wiped Logs' );
	}
}
WP_CLI::add_command( 'fingerprint', 'Fingerprint_Command' );
