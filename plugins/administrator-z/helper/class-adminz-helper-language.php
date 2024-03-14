<?php 
namespace Adminz\Helper;
use Adminz\Admin\ADMINZ_Woocommerce;
use Adminz\Admin\ADMINZ_ContactGroup;
use Adminz\Admin\ADMINZ_Flatsome;

class ADMINZ_Helper_Language{
	static $name = "Administrator Z";
	function __construct() {
		
	}	
	static function get_pll_string($option,$default=false){
		if(!function_exists('pll__')) {
			return self::get_convert_array_option($option,$default);
		}else{
			return pll__(self::get_convert_array_option($option,$default));
		}
	}
	static function register_pll_string($option,$option_group,$multipleline=false){
		if(!function_exists('pll_register_string')) return;		
		$value = self::get_convert_array_option($option);
		pll_register_string($option_group,$value,self::$name,$multipleline);
	}
	static function get_convert_array_option($option,$default=false){
		if(!$option) return ; 
		$option = str_replace(["][","[","]"],["/","/","/"],$option);
		$array = array_filter(explode("/",$option));
		if(count($array)>1){		
			switch ($array[0]) {
				case 'adminz_woocommerce':
					$temp = ADMINZ_Woocommerce::$options;
					break;
				case 'adminz_contactgroup':
					$temp = ADMINZ_ContactGroup::$options;
					break;
				case 'adminz_flatsome':
					$temp = ADMINZ_Flatsome::$options;
					break;									
				default:
					$temp = get_option($array[0],$default);			
					break;
			}


			for ($i=1; $i < count($array); $i++) {
				if(isset($temp[$array[$i]])){
					$temp = $temp[$array[$i]];
				}				
			}
			return $temp;
		}else{
			return get_option($option,$default);
		}
		return ; 
	}
}