<?php
namespace ScssPhp\ScssPhp;
class Lenix_Scss_Compiler {
	
	private function compile_dirs(){
		
		$dirs = $this->get_dirs();
		
		if(!$dirs){
			return false;
		}
		
		foreach($dirs as $dir){
			$this->compile_directory($dir['scss'],$dir['css']);
		}
		
	}
	
	private function compile_directory($scss,$css){
		
		$scss_dir = WP_CONTENT_DIR.'/'.$scss.'/';
		$css_dir = WP_CONTENT_DIR.'/'.$css.'/';
		if (!is_dir($scss_dir) || !is_dir($css_dir)){
			return false;
		}
		
		
		$compiler = new Lenix_Scss_Dir_Compiler(
		  $scss_dir,
		  $css_dir,
		  'ScssPhp\ScssPhp\Formatter\Compressed'
		);
			
	}
	
	private function get_dirs(){
		
		$op_str = get_option( 'lenix_scss_options' )['lenix_scss_dirs'];
		parse_str($op_str, $op_arr);
		if(!empty($op_arr['dirs'])){
			return $op_arr['dirs'];
		} else {
			return false;
		}
		
	}
	
	public function __construct(){
		$this->compile_dirs();
	}
	
}
new Lenix_Scss_Compiler();