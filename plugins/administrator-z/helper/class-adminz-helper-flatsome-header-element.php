<?php 
namespace Adminz\Helper;
// use WP_Query;

class ADMINZ_Helper_Flatsome_Header_Element{
	public $name;
	public $title;
	public $callback;
	public $option = 'comming soon';

	function __construct() {

	}

	function general_header_element(){
		if(!$this->name){
			echo __('Missing element name','administrator-z');
			return;
		}
		if(!$this->title){
			echo __('Missing element title','administrator-z');
			return;
		}
		if(!$this->callback){
			echo __('Missing element callback','administrator-z');
			return;
		}

		add_filter( 'flatsome_header_element', function($arr){
			$arr[$this->name] = $this->title;
			return $arr;
		});

		add_action( 'flatsome_header_elements', function($slug){
			if($slug == $this->name){
				echo call_user_func($this->callback);
			}
		});	


	}

	// NOTE: only for adminz plugin using ================================
	function create_adminz_header_element(){
		$this->adminz_theme_locations = [
			'desktop'=>[
				'additional-menu' => 'Additional Menu', 
				'another-menu' => 'Another Menu', 
				'extra-menu' => 'Extra Menu' 
			],
			'sidebar' => [
				'additional-menu-sidebar' => 'Additional Menu - Sidebar', 
				'another-menu-sidebar' => 'Another Menu - Sidebar', 
				'extra-menu-sidebar' => 'Extra Menu - Sidebar' 
			]		
		];
		add_action( 'init', [$this,'adminz_register_my_menus']);
		add_filter( 'flatsome_header_element', [$this,'adminz_register_header_element']);
		add_action( 'flatsome_header_elements', [$this,'adminz_do_header_element']);	
	}

	function adminz_register_my_menus() {
		foreach ($this->adminz_theme_locations as $key => $value) {
			register_nav_menus($value);	
		}		
	}

	function adminz_register_header_element($arr){

		foreach ($this->adminz_theme_locations as $navtype => $navgroup) {
			foreach ($navgroup as $key => $value) {
				$arr[$key] = $value;	
			}			
		}
		return $arr;
	}

	function adminz_do_header_element($slug){
		foreach ($this->adminz_theme_locations as $navtype => $navgroup) {
			foreach ($navgroup as $key => $value) {
				$walker	= 'FlatsomeNavDropdown';
				if ($navtype == 'sidebar') $walker = 'FlatsomeNavSidebar';
				
				if($slug == $key){
					flatsome_header_nav($key,$walker);
				}
			}
			
		}
	}
	
}


/*
	CODE EXAMPLE 
	$element = new \Adminz\Helper\ADMINZ_Helper_Flatsome_Header_Element;
    $element->name = "bic_map";
    $element->title= 'BIC Map';
    $element->callback = function (){
        ob_start();
        echo '<pre>'; print_r(88); echo '</pre>';
        return ob_get_clean(); 
    };
    $element->general_header_element();

*/