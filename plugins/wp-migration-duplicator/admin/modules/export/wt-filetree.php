<?php
/**
 *  Tree structure for exporting plugin
 *
 * @link       
 * @since 1.1.7  
 *
 * @package  Wp_Migration_Duplicator  
 */
if (!defined('ABSPATH')) {
    exit;
}

class Wt_File_Tree {

	private $parent_directory;

	function __construct( $parent ='' ) {
		$this->parent_directory = $parent;
	}
	/**
	 * Create File tree.
	 * @since 1.1.7
	 */
    public function php_file_tree( $directory,$excluded_items = array(), $extensions = array(), $cron=false ) {
		if( substr($directory, -1) == "/" ) $directory = substr($directory, 0, strlen($directory) - 1);
		$mgdp_file_tree_html = wp_cache_get( 'wt_mgdp_file_tree_html' );

		if ( false === $mgdp_file_tree_html ) {
			return $this->wt_file_tree_dir($directory, $excluded_items, $extensions, $cron);
		}else{
			if( true === $cron ){
				$mgdp_file_tree_html = str_replace('mgdp-exclude-file', 'mgdp-exclude-file-cron', $mgdp_file_tree_html);
			}
			return $mgdp_file_tree_html;
		}
    }
	/**
	 * Create Folder tree usnig given path
	 * @since 1.1.7
	 */
    public function wt_file_tree_dir( $directory, $excluded_items= array(), $extensions = array(), $cron=false, $first_call = true ) {
		// Get and sort directories/files
                set_time_limit(0);
                ini_set('max_execution_time', -1);
                ini_set('memory_limit', -1);
		if( function_exists("scandir") ) $file = scandir($directory); else $file = php4_scandir($directory);
		natcasesort($file);
		// Make directories first
		$files = $dirs = array();
		foreach($file as $this_file) {
			if( is_dir("$directory/$this_file" ) ) $dirs[] = $this_file; else $files[] = $this_file;
		}
		$file = array_merge($dirs, $files);
		
		// Filter unwanted extensions
		if( !empty($extensions) ) {
			foreach( array_keys($file) as $key ) {
				if( !is_dir("$directory/$file[$key]") ) {
					$ext = substr($file[$key], strrpos($file[$key], ".") + 1); 
					if( !in_array($ext, $extensions) ) unset($file[$key]);
				}
			}
		}
		$php_file_tree = '';
		if( count($file) > 2 ) { // Use 2 instead of 0 to account for . and .. "directories"
			$php_file_tree = "<ul";
			if( $first_call ) { $php_file_tree .= " class=\"mgdp-file-tree\""; $first_call = false; }
			$php_file_tree .= ">";
			foreach( $file as $this_file ) {
				if( $this_file != "." && $this_file != ".." && !  in_array( str_replace($this->parent_directory.'/','', $directory."/".$this_file),$excluded_items ) ) {

					$input_value = str_replace($this->parent_directory.'/','', $directory."/".$this_file);

					if( is_dir("$directory/$this_file") ) {
						// Directory
						$php_file_tree .= "<li class=\"mgdp-directory\">";
						if( true === $cron){
							$php_file_tree .= "<input type=\"checkbox\" name=\"mgdp-exclude-file-cron\" value=\"".$input_value."\" checked/>";
						}else{
							$php_file_tree .= "<input type=\"checkbox\" name=\"mgdp-exclude-file\" value=\"".$input_value."\" checked/>";
						}
						$php_file_tree .= "<a path=\"".$directory."/".$this_file."\" href=\"#\">" . htmlspecialchars($this_file) . "</a>";
						$php_file_tree .= "</li>";
					} else {

						$ext = "ext-" . substr($this_file, strrpos($this_file, ".") + 1); 
						$file_path = "$directory/" . urlencode($this_file);
						$php_file_tree .= "<li  class=\"pft-file " . strtolower($ext) . "\">";
						if( true === $cron){
							$php_file_tree .= "<input type=\"checkbox\" name=\"mgdp-exclude-file-cron\" value=\"".$input_value."\" checked/>";
						}else{
							$php_file_tree .= "<input type=\"checkbox\" name=\"mgdp-exclude-file\" value=\"".$input_value."\" checked/>";
						}
						$php_file_tree .= "<a file=\"".$file_path."\" >" . htmlspecialchars($this_file) . "</a></li>";
					}
				}
			}
			$php_file_tree .= "</ul>";
		}
		wp_cache_set( 'wt_mgdp_file_tree_html', $php_file_tree, '', MINUTE_IN_SECONDS );
		return $php_file_tree;
    }
    
    // For PHP4 compatibility
	public function php4_scandir( $dir ) {
		$dh  = opendir( $dir );
		while( false !== ($filename = readdir($dh)) ) {
			$files[] = $filename;
		}
		sort($files);
		return($files);
	}
    

}