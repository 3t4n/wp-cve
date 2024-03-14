<?php
class WPFingerprint_Runner{
	public $plugins;
	public $hash;
	public $model_checksums;
	public $diff;
	public $model_diffs;
	public $transport;
	private $path;

	function __construct()
	{
		if(isset($this->path)){
			$this->path = plugin_dir_path( __FILE__ );
		}
		if(empty($this->plugins)){
			$this->load('plugins');
			$this->plugins = new WPFingerprint_Plugins;
		}

		if(empty($this->hash)){
			$this->load('hash');
			$this->hash = new WPFingerprint_Hash;
		}
		if(empty($this->model_checksums)){
			$this->load('model-checksums');
			$this->model_checksums = new WPFingerprint_Model_Checksums;
		}
		if(empty($this->diff)){

			$this->load('diff');
			$this->diff = new WPFingerprint_Diff;
		}
		if(empty($this->model_diffs)){
			$this->load('model-diffs');
			$this->model_diffs = new WPFingerprint_Model_Diffs;
		}
	}
	//poormans autoloader
	function load($class)
	{
		return require_once $this->path . 'class-wpfingerprint-'.$class.'.php';
	}


	/*
	 * Run command, Compares Checksums
	 *
	 */

	function run( $plugin = false )
	{
		$time = time();
		/*
		 * Step 1 - Get plugins to check
		 */
		$plugins_list = array();
		$plugin_info = array();
		if(!isset($plugin) || !$plugin)
		{

			//Get all plugins
			$plugins_list = $this->plugins->get_all_plugin_names();
		}
		else{
			$plugins_list = $this->plugins->get_some_plugins_names($plugin);
		}
		//We have nothing to do.
		if(empty($plugins_list)) return false;
		/*
		 * Step 2 - Get local checksums.
		 */
		$local_checksums = array();
		foreach($plugins_list as $plugin_name)
		{
			$local_checksums[$plugin_name] = $this->generate_plugin_checksum( $plugin_name );
		}

		//We have nothing to do.
		if(empty($local_checksums)) return false;

		 /*
		  * Step 3, 4 - Get Remote Checksums
			*/

			$remote_checksums = array();
			foreach($plugins_list as $plugin_name)
			{
				$remote_get = array();
				$remote_get = $this->generate_remote_plugin_checksum( $plugin_name );
				if(!empty($remote_get)){
					$remote_checksums[$plugin_name] = $remote_get['checksums'];
					$plugin_info[$plugin_name] = array(
					'source' =>	$remote_get['source'],
					'version' => $remote_get['version'],
					);
				}
			}

			//We have nothing to do.
			if(empty($remote_checksums)) return false;

			/*
			 * Step 5,6,7 - Do comparison
			 */
			 $added_files = array();
			 $not_valid_checksums = array();
			 $file_diffs = array();

			 foreach($plugins_list as $plugin_name)
 			{
				$compare = array();
				if(!empty($remote_checksums[$plugin_name]) && is_array($remote_checksums[$plugin_name]))
				{
					$compare = $this->compare_checksums(	$local_checksums[$plugin_name], $remote_checksums[$plugin_name]);
				}
				if(!empty($compare['added']))
				{
					$added_files[$plugin_name] = $compare['added'];
				}
				if(!empty($compare['invalid']))
				{
				/*
				 * Step 8 - Check if Support Diff
				 */
				 //Temporary removed due to Diff issues
				if($this->diff_available($plugin_info[$plugin_name]['source']))
					{
						$version =  $plugin_info[$plugin_name]['version'];
						$files = array_keys($compare['invalid']);
						$file_diffs[$plugin_name] = $this->generate_plugin_diff($plugin_name, $version, $files, $plugin_info[$plugin_name]['source']);
						if(is_array($file_diffs) && !empty($file_diffs))
						{
							foreach($file_diffs as $file_diff_file => $file_diff_contents)
							{
								if(empty($file_diff_file) || !is_array($file_diff_file))
								{
									//Remove from file Diffs and $not_valid_checksums
									unset($compare['invalid'][$file_diff_file]);
									unset($file_diffs[$file_diff_file]);
								}
							}
						}
					}
					$not_valid_checksums[$plugin_name] = $compare['invalid'];
				}
 			}
			/*
			 * Step 10 & 11 Get Previous Fails, Store new ones
			 */

			 $previous_fails = $this->get_previous_fails();
			 $update_ids = array();
			 $remove_ids = array();
			 if(!empty($previous_fails))
			 {
				 foreach($previous_fails as $plugin => $versions)
				 {
					 if(array_key_exists($plugin, $not_valid_checksums))
					 {
						 foreach($versions as $version => $content)
						 {
							 if($plugin_info[$plugin]['version'] == $version )
							 {
								 foreach($content as $file => $file_checksums)
								 {
									 if(array_key_exists($file, $not_valid_checksums[$plugin]))
									 {
										 //ok compare the Local Checksums
										 if($file_checksums['local_checksum'] == $not_valid_checksums[$plugin][$file][0])
										 {
											 //Update the time sheet
											 $update_ids[] = $file_checksums['id'];
											 //unset
											 unset($not_valid_checksums[$plugin][$file]);
										 }
									 }
									 else{
										 //Remove ID
										 $remove_ids[] = $file_checksums['id'];
									 }
								 }
							 }
							 else{
								 foreach($content as $file)
								 {
									 //Remove no longer seen checksums by Version
									 $remove_ids[] = $file['id'];
								 }
							 }
						 }

					 }
				 }

			 }
			 //Remove IDs
			 if(!empty($remove_ids) && is_array($remove_ids))
			 {
				 $this->remove_checksums($remove_ids);
			 }
			 //Update IDs
			 if(!empty($update_ids) && is_array($update_ids))
			 {
				 $this->update_checksums($update_ids);
			 }
			  //Add New Checksums
				if(!empty($not_valid_checksums) && is_array($not_valid_checksums))
				{
					foreach($not_valid_checksums as $plugin => $files)
					{
						if(is_array($files) && !empty($files))
						{
							$this->add_checksums($plugin, $plugin_info[$plugin]['version'],$files, $plugin_info[$plugin]['source']);
						}
						else{
							//Clean it up
							unset($not_valid_checksums[$plugin]);
						}
					}
				}

			 /*
			  * Tidy up
				*/
				//Finished
				update_option( 'wpfingerprint_last_run', time() );
				$return = array(
					'time_taken' => time() - $time,
					'new_issues' => count($not_valid_checksums,1),
					'removed_isues' => count($remove_ids,0),
					'updated_issues' => count($update_ids,0)
				);
				do_action('wp_fingerprint_runner',array($not_valid_checksums,$return));
				return $return;
	}

	function compare_checksums($local_checksums, $remote_checksums)
	{
		$added_file = array();
		$invalid_checksum = array();
		foreach($local_checksums as $file => $checksum)
		{
			/*
			 * Step 5/6 - filter files
			 */
			if(!$this->plugins->filter_files($file)){
				/*
				 * Step 7 - Look for added and invalid checksums
				 */
				if(!isset($remote_checksums[$file]))
				{
					$added_file[$file] = $checksum;
				}
				elseif($remote_checksums[$file] != $checksum)
				{
					if(!is_array($remote_checksums[$file]) || !in_array($checksum, $remote_checksums[$file]))
					{
						$invalid_checksum[$file] = array($checksum,$remote_checksums[$file]);
					}
				}
			}
		}
		return array(
			'added' => $added_file,
			'invalid' => $invalid_checksum
		);
	}

	function diff_available( $transport )
	{
		$transport_object = 'transport-'.$transport;
		return $this->$transport_object->get_option('diff');
	}

	function generate_plugin_checksum( $plugin = false )
	{
		if(!isset($plugin) || $plugin == false) return;

		$path = $this->plugins->path($plugin);
		$files = $this->plugins->get_files($path);
		$checksums = array();

		foreach ( $files as $file )
		{
			$checksums[ltrim($file,'/')] = $this->hash->get_sha256( $path.$file );
		}
		return $checksums;
	}

	function generate_remote_plugin_checksum( $plugin = false )
	{
		if(!isset($plugin) || $plugin == false) return;
		$transports = $this->select_transport( $plugin );
		$version = $this->plugins->get_plugin_version( $plugin );
		foreach($transports as $transport)
		{
			$transport_object = 'transport-'.$transport;
			$remote_checksums = $this->$transport_object->get_plugin_checksums( $plugin, $version );
			if(!empty($remote_checksums) )
			{
				 return array(
					 'source' => $transport,
					 'version' => $version,
					 'checksums' => $remote_checksums,
				 );
			}
		}
		return false;

	}

	function generate_plugin_diff( $plugin, $version, $files, $transport)
	{
		if(!isset($plugin) || $plugin == false) return false;
		if(!isset($version) || $version == false) return false;
		$transport_object = 'transport-'.$transport;
		$diffs = array();
		foreach ( $files as $file )
		{
			$local_file = $this->plugins->read_file_contents($plugin,$file);
			$remote_file = $this->$transport_object->get_plugin_file($plugin,$version,$file);

			$diff_file = $this->diff->check_file_diff($local_file,$remote_file);
			$diffs[$file] = $this->diff->show_diffs($diff_file);
		}
		return $diffs;
	}

	function get_previous_fails()
	{
		$get_fails = $this->model_checksums->get();
		if( empty($get_fails) ) return false;

		$failed_plugins = array();
		foreach($get_fails as $fail)
		{
			$checksum = array(
				'id' => $fail->id,
				'local_checksum' => $fail->checksum_local,
				'remote_checksum' => $fail->checksum_remote
			);
			$failed_plugins[$fail->plugin][$fail->version][$fail->filename] = $checksum;
		}
		return $failed_plugins;
	}

	function remove_checksums($ids)
	{
		foreach($ids as $id)
		{
			$this->model_checksums->remove($id);
		}
	}

	function update_checksums($ids)
	{
		foreach($ids as $id)
		{
			$this->model_checksums->update_last_checked($id);
		}
	}
	function add_checksums($plugin, $version, $files, $source)
	{
		foreach($files as $filename => $data)
		{
			$checksums = array(
				'local' => $data[0],
				'remote' => $data[1],
				'source' => $source
			);
			$this->model_checksums->set($plugin, $version,$filename,$checksums);
		}
	}

	private function select_transport( $plugin = false )
	{
		$transports = array(
			'wporg',
			'local',
			'wpfingerprint'
		);
		//Add and load our transports
		foreach( $transports as $transport )
		{
			$transport_object = 'transport-'.$transport;
			$this->load(	$transport_object );
			$transport_name = 'WPFingerprint_Transport_'.ucwords( $transport );
			$this->$transport_object = new $transport_name;
		}
		//Add your own transport such as a remote repository, need to load your own handler
		return apply_filters( 'wp_fingerprint_transports', $transports, $plugin );
	}

	function report( $plugin = false)
	{
		$report = array();
		$files = $this->model_checksums->get($plugin);
		foreach($files as $file)
		{
			$report[] = array(
				'plugin' => $file->plugin,
				'file' => $file->filename,
				'checksum_local' => $file->checksum_local,
				'checksum_remote' => $file->checksum_remote,
				'last_checked' => $file->last_checked
			);
		}
		return $report;
	}
}
