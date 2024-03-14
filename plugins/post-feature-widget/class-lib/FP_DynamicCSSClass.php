<?php

/**
 *
 * Class FP Dynamic CSS
 *
 * Extending A5 Dynamic Files
 *
 * Presses the dynamical CSS of the Featured Post Widget into a virtual style sheet
 *
 */

class FP_DynamicCSS extends A5_DynamicFiles {
	
	private static $options;
	
	function __construct() {
		
		self::$options =  get_option('pf_options');
		
		if (isset(self::$options['inline'])) self::$options['inline'] = false;
		
		if (!array_key_exists('priority', self::$options)) self::$options['priority'] = false;
		
		if (!array_key_exists('compress', self::$options)) self::$options['compress'] = true;
		
		$this->a5_styles('wp', 'all', self::$options['inline'], self::$options['priority']);
		
		$fpw_styles = self::$options['css_cache'];
		
		if (!$fpw_styles) :
		
			$eol = (self::$options['compress']) ? '' : "\n";
			$tab = (self::$options['compress']) ? '' : "\t";
			
			$css_selector = 'widget_featured_post_widget[id^="featured_post_widget"]';
			
			$fpw_styles = (!self::$options['compress']) ? $eol.'/* CSS portion of the Featured Post Widget */'.$eol.$eol : '';
			
			if (!empty(self::$options['css'])) :
			
				$style.=$eol.$tab.str_replace('; ', ';'.$eol.$tab, str_replace(array("\r\n", "\n", "\r"), ' ', self::$options['css']));
				
				$fpw_styles .= parent::build_widget_css($css_selector, '').'{'.$eol.$tab.$style.$eol.'}'.$eol;
				
			endif;
			
			$fpw_styles .= parent::build_widget_css($css_selector, 'img').'{'.$eol.$tab.'height: auto;'.$eol.$tab.'max-width: 100%;'.$eol.'}'.$eol;
			
			self::$options['css_cache'] = $fpw_styles;
			
			update_option('pf_options', self::$options);
			
		endif;
		
		parent::$wp_styles .= $fpw_styles;

	}
	
} // FP_Dynamic CSS

?>