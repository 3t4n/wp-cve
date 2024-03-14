<?php
class Captionpix_Core extends Captionpix_Module {

    private $autocaption = false;

	public function init() {
		add_filter('widget_text', 'do_shortcode', 11);
        add_shortcode('captionpix', array($this,'display'));
		//add_filter('the_content', array('captionpix','autocaption'), 10); //autocaptioning coming in a later release
	}

    function autocaption_options() {
        
        return array (
		  'none' => 'None',
		  'title' => 'Use Image Title as Caption',
		  'alt' => 'Use Image Alt as Caption',
		  'post' => 'Use Post Title as Caption'
		);
	}

    function autocaption($content) {
    	if (is_home() || is_single() || is_page()) {
			$options = $this->get_defaults();
			$this->autocaption = array_key_exists('autocaption',$options) ? $options['autocaption'] : 'none';
			if ($this->autocaption != 'none') 
				$content = preg_replace_callback(
    			    '/<img\s[^>]*>/i',
    			    function($matches) { return $this->autocaption_image($matches[0]); },
    	    		$content
    			);
    	}
		return $content;
	}

	function autocaption_image($img) {
  		$class=preg_match('/class="[^"]*"/i', $img, $matches) ?  $matches[1] :'';
   		if (strpos($class,'caption-pix-outer') !== FALSE) return $img;

		$src= preg_match('/src="(.*)"/i', $img, $matches) ? $matches[1] : '';
		$title = preg_match('/title="([^"]*)"/i', $img, $matches) ? $matches[1] : '';
		$alt=preg_match('/alt="([^"]*)"/i', $img, $matches) ?  $matches[1] :'';
    	switch ($this->autocaption) {
    		case "post": {
    			global $post;
    			$caption = $post->post_title;
				break;
				}
    		case "alt": { $caption = $alt; break; }
    		case "title":
    		default:
    			{ $caption = $title; break;}
    	}
    	$params = array();
    	$params['imgurl']= $src;
   	 	$params['imgtitle']= $title;
		$params['imgalt'] = $alt;
		$params['captiontext'] = $caption;
		return $this->display($params);
	}


	function display($attr) {
		$disp = new Captionpix_Display($this);
		return $disp->show($attr);
	}

    function get_theme_factory() {
        return $this->plugin->get_module('theme');
    }

    function get_options($cache= true) {
        return $this->options->get_options($cache);
    }

    function save_options($options) {
        return $this->options->save_options($options);
    }

}
