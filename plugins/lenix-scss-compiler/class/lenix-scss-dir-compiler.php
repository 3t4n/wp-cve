<?php
namespace ScssPhp\ScssPhp;


class Lenix_Scss_Dir_Compiler {	
	
	private $scss_dir;
	private $css_dir;
	private $compiler;
	private $log_file;
	private $errors = array();
	
	private function is_need_to_recompile(){
		
		$recompile = apply_filters('lenix_force_recompile',false);
		if($recompile){
			return true;
		}
		
		$disable_recompile = apply_filters('lenix_disable_recompile',false);
		if($disable_recompile){
			return false;
		}
		
		$latest_scss = $this->get_last_update_file($this->scss_dir,'scss');
		$latest_css	 = $this->get_last_update_file($this->css_dir,'css');
		
		return (int) $latest_scss > $latest_css;
	}
	
	private function compile_file($scss_file){
		
		$temp = LENIX_SCSS_COMPILER_TEMP;
		
		$input = $this->scss_dir.$scss_file;
		$output_name = preg_replace("/\.[^$]*/",".css", $scss_file);
		$output = $this->css_dir.$output_name;
		
		if ( is_writable($temp) ) {
			
			try {
				
				$css = $this->compiler->compile( file_get_contents($input) );
				file_put_contents($temp.basename($output), $css);
				
			} catch (Exception $exception) {
				
				$this->add_errors(
					array (
						'file' => basename($input),
						'message' => $exception->getMessage(),
					)
				);
				
			}
			
		} else {
			
			$this->add_errors(
				array (
					'file' => $temp,
					'message' => "File Permission Error, permission denied. Please make the temp directory writable."
				)
			);
			
		}
	}
	
	private function compile_directory(){
		
		if(!$this->is_need_to_recompile()){
			return;
		}

		
		$input_files = $this->get_input_files();
		foreach ($input_files as $scss_file) {
			$this->compile_file($scss_file);
		}

		$this->handle_errors();
	}
	
	private function add_errors($errors){
		array_push($this->errors, $errors);
	}
	
	private function print_or_log_errors(){
		$need_to_print_errors = !is_admin() && count($this->errors);
		
		if ( $need_to_print_errors ) {
			$this->print_errors();
		} else {
			$this->log_errors();
		}
		
		$this->clean_error_log_file();
	}
	
	private function print_errors(){
		
		echo '<pre dir="ltr" width="100%;" style="position:relative;z-index:9999999999;">';
		echo '<h3 style="margin: 15px 0;">Sass Compiling Error</h3>';

		foreach( $this->errors as $error) {
			echo '<p>';
			echo '<strong>'. $error['file'] .'</strong> <br/><em>"'. $error['message'] .'"</em>';
			echo '</p>';
		}

		echo '</pre>';
		
	}
	
	private function log_errors(){
		
		foreach ($this->errors as $error) {
			$error_string = date('m/d/y g:i:s', time()) .': ';
			$error_string .= $error['file'] .' - '. $error['message'] . PHP_EOL;
			file_put_contents($this->log_file, $error_string, FILE_APPEND);
			$error_string = "";
		}
		
	}
	
	private function clean_error_log_file(){
		
		if ( file_exists($this->log_file) ) {
			if ( filesize($this->log_file) > 999999) {
				$log_contents = file_get_contents($this->log_file);
				$log_arr = explode("\n", $log_contents);
				$new_contents_arr = array_slice($log_arr, count($log_arr)/2);
				$new_contents = implode(PHP_EOL, $new_contents_arr) . 'LOG FILE CLEANED ' . date('n/j/y g:i:s', time());
				file_put_contents($this->log_file, $new_contents);
			}
		}
		
	}
	
	private function get_dir($dir){
		return new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator(WP_CONTENT_DIR.'/'.$dir)
		);
	}
	
	private function get_input_files(){
		
		$files = array();
		
		foreach( new \DirectoryIterator($this->scss_dir) as $file ) {
			if (substr($file, 0, 1) != "_" && $this->is_extension($file,'scss') ) {
			  array_push($files, $file->getFilename());
			}
		}
		
		return $files;
	}
	
	private function get_last_update_file($dir,$extension){

		$latest_file = 0;
		
		foreach ( new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir)) as $file ) {
			if (pathinfo($file->getFilename(), PATHINFO_EXTENSION) == $extension) {
			  $file_time = $file->getMTime();

			  if ( (int) $file_time > $latest_file) {
				$latest_file = $file_time;
			  }
			}
		  }
		
		return $latest_file;
	}
	
	private function is_extension($file_ob,$extension){
		return pathinfo( $file_ob->getFilename(), PATHINFO_EXTENSION ) == $extension;
	}
	
	private function handle_errors(){
		
		$temp = LENIX_SCSS_COMPILER_TEMP;
		
		if ( count($this->errors) < 1 ) {
			if  ( is_writable($this->css_dir) ) {
			  foreach (new \DirectoryIterator($temp) as $temp_file) {
				if ( $this->is_extension($temp_file,'css')) {
				  file_put_contents($this->css_dir.$temp_file, file_get_contents($temp.$temp_file));
				  unlink($temp.$temp_file->getFilename()); // Delete file on successful write
				}
			  }
			} else {
			  $errors = array(
				'file' => 'CSS Directory',
				'message' => "File Permissions Error, permission denied. Please make your CSS directory writable."
			  );
			  array_push($this->errors, $errors);
			}
		}
		
		$this->print_or_log_errors();
	}
	
	public function set_variables() {
		$variables = apply_filters('lenix_scss_compiler_vars',array());
		$this->compiler->setVariables($variables);
	}
	
	private function on_construct(){
		
		$this->compiler->setFormatter( 'ScssPhp\ScssPhp\Formatter\Compressed' );
		$this->compiler->setImportPaths( $this->scss_dir );
		$this->compile_directory();
	}
	
	
	public function __construct($scss_dir, $css_dir){
		
		$this->scss_dir = $scss_dir;
		$this->css_dir = $css_dir;
		$this->log_file = $scss_dir.'error_log.log';
		$this->compiler	= new Compiler();
		$this->on_construct();
	}

}
