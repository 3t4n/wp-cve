<?php
class Captionpix_Theme extends Captionpix_Module {

	private $updater;
	private $themes = array();
  	private $themesets = array();
	private $defaults = array(
	
 		'crystal' => array('framecolor' => 'transparent',
 			'imgborder' => 'none', 'imgbordercolor' => '', 'imgbordersize' => '0', 'imgmargin' => '0', 'imgpadding' => '0',
 			'captionfontcolor' => '#000000', 'captionfontfamily' => 'Arial', 'captionfontsize' => '13', 'captionfontstyle' => 'italic',
 			'captionpaddingtop' => '5','captionpaddingbottom' => '5',
   			'nooverrides' => 'theme'),		
   			
		'lifted-corners' => array( 'align' => 'left', 'captionclass' => 'nostyle'),

		'wp-caption' => array( 'align' => 'left','framesize='=>'6', 'captionclass' => 'nostyle')

		);


	function init() { 
	   $this->updater = $this->plugin->get_module('api');
	}

	public function get_theme_names() {
    	return array_keys($this->get_themes());
	}

	public function get_theme_names_in_order() {
 		$result = array();
    	$names = $this->get_theme_names();
    	foreach ($names as $name) $result[$name] = str_replace(' ','-',ucwords(str_replace('-',' ',$name))); 
		asort($result); //sort alphabetically
		return $result;
	}

	public function get_themes_in_set($myset,$cache=true) {
		if ((false == $cache) || count($this->themesets) == 0) $this->refresh_themesets($cache);
		if (is_array($this->themesets) && (count($this->themesets) > 0)) 
			return array_keys($this->themesets,$myset);
 		else
 			return array();
 	}

	public function get_theme($theme_name) {
   	 	$themes = $this->get_themes();
   	 	if ($theme_name && $themes && array_key_exists($theme_name,$themes))
        	return $themes[$theme_name];
    	else
        	return $this->get_default_theme();
	}

 	private function get_default_theme() {
 		return $this->defaults['crystal'];
    }
    
	private function get_themes ($cache = true) {
   		if (!$cache || (count($this->themes) == 0)) $this->refresh_themes($cache);
   		return $this->themes;
   	}

	private function refresh_themes ($cache=true) {
		$themes = $this->defaults;
   		$more_themes = $this->updater->update($cache);
   		if (is_array($more_themes) && (count($more_themes) > 0)) $themes = array_merge($more_themes,$themes);
        foreach ($themes as $key => $theme) { //allow local overrides of image file locations, local, amazon s3, cdn using constants placed in wp-config.php
			if (array_key_exists('framebackground',$theme)) $themes[$key]['framebackground'] = str_replace('CAPTIONPIX_FRAMES_URL',CAPTIONPIX_FRAMES_URL,$theme['framebackground']);
			if (array_key_exists('frameborder',$theme)) $themes[$key]['frameborder'] = str_replace('CAPTIONPIX_BORDERS_URL',CAPTIONPIX_BORDERS_URL,$theme['frameborder']);
		}
   		$this->themes = $themes; //update instance value
	}

	private function refresh_themesets ($cache=true) {
   		$this->themesets = $this->updater->update($cache,'themesets');
	}

}
