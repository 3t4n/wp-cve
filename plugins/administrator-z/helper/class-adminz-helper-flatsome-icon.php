<?php 
namespace Adminz\Helper;
class ADMINZ_Helper_Flatsome_Icon{
	public $icon_list = [
		// 'expand' => '1'
	];

	function __construct() {
		
	}

	function init_change_icon(){

		if(empty($this->icon_list)){
			echo 'no icon list';
			return;
		}

		add_filter('flatsome_icon',function($icon_html, $name, $size, $atts ){
			preg_match_all('/icon-([^ ]+)/', $name, $matches);
			if(isset($matches[1][0])){
				$icon = $matches[1][0];

				if(array_key_exists($icon, $this->icon_list)){
					$icon_html = $this->icon_list[$icon];
					$icon_html = $this->clean_svg($icon_html);
				}
			}

			// echo '<pre>'; print_r($atts); echo '</pre>';
			return $icon_html;
		},10,4);
	}

	function clean_svg($html){
	    $html = preg_replace('/<!--(.*?)-->/s', '', $html);
	    $html = preg_replace('/(width|height)="[^"]*"/', 'width="1em"', $html);
	    $html = preg_replace('/fill="[^"]*"/', 'fill="currentColor"', $html);

	    return $html;
	}
}


/*

	$a = new Adminz\Helper\ADMINZ_Helper_Flatsome_Icon;
	$a->icon_list = [
		'expand' => '1'
	];
	$a->init_change_icon();

*/