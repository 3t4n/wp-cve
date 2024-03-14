<?php

/**
 *
 * Class A5 Option Page
 *
 * @ A5 Plugin Framework
 * Version: 1.0 beta 20160127
 *
 * Gets all sort of containers for the flexible A5 settings pages
 *
 */

class A5_OptionPage {
	
	/**
	 *
	 * Opening and closing the option page / form (automatically closed with page)
	 *
	 */
	static function open_page($plugin_name, $url = false, $plugin_slug = false, $title = false) {
		
		$eol = "\n";
		
		$tab = "\t";
		
		echo $eol.'<div class="wrap">';
		
		if ($url) echo $eol.$tab.'<a href="'.$url.'" title="'.$title.'"><div id="a5-logo" style="background: url(\''.plugins_url($plugin_slug.'/img/a5-icon-34.png').'\'); float: left; width: 32px; height: 32px; margin: 5px;"></div></a>';
		
		echo $eol.$tab.'<h2>'.$plugin_name.' '.__('Settings').'</h2>'.$eol;
	
	}
	 
	static function open_form($action) {
		
		$eol = "\n";
		
		echo $eol.'<form action="'.$action.'" method="post">'.$eol;
		
	} 
	 
	static function close_page() {
		
		$eol = "\n";
		
		$tab = "\t";
		
		echo $eol.$tab.'</form>'.$eol.'</div>'.$eol;
		
	}
	
	/**
	 *
	 * Building the menu for the tabs
	 *
	 */
	static function nav_menu($args) {
		
		$eol = "\n";
		
		$tab = "\t";
		
		extract ($args);
		
		echo '<h2 class="nav-tab-wrapper">';
		
		foreach ($menu_items as $menu_item => $args) :
		
			$id = (array_key_exists('id', $args)) ? ' id="'.$args['id'].'"' : '';
		
			echo $eol.$tab.'<a'.$id.' class="nav-tab'.$args['class'].'" href="?page='.$page.'&tab='.$menu_item.'">'.$args['text'].'</a>';
		
		endforeach;
		
		echo $eol.'</h2>'.$eol;
	
	}
	
	/**
	 *
	 * Opening and closing the tabs, columns etc.
	 *
	 */
	static function open_tab($columns = false) {
		
		$eol = "\n";
		
		$n = ($columns) ? '-'.$columns : '';
		
		$columns = ($columns) ? ' columns-'.$columns : '';
		
		echo $eol.'<div id="poststuff">'.$eol.'<div id="post-body" class="metabox-holder'.$columns.'">'.$eol.'<div id="postbox-container'.$n.'" class="postbox-container">';
		
	}
	
	static function close_tab() {
		
		$eol = "\n";
		
		echo $eol.'</div>'.$eol.'</div>'.$eol.'</div>'.$eol;
		
	}
	
	/**
	 *
	 * Wrapping sections into postboxes
	 *
	 */
	static function postbox($label, $id, $sections ) {
		
		if (!is_array($sections)) $sections = array($sections);
		
		$postbox = self::open_postbox($label, $id);
		
		ob_start();
		
		foreach ($sections as $section) do_settings_sections($section);
			
		$postbox .= ob_get_contents();
		
		ob_end_clean();
		
		$postbox .= self::close_postbox();
		
		return $postbox;
		
	}
	
	/**
	 *
	 * Opening and closing the postboxes
	 *
	 */
	static function open_postbox($label, $id, $closed = false) {
		
		$eol = "\n";
		
		$tab = "\t";
		
		$dtab = $tab.$tab;
		
		$class = (false === $closed) ? '' : ' closed';
	
		$output = $eol.'<div id="'.$id.'" class="postbox'.$class.'">'.$eol.$tab.'<div class="handlediv" title="'.__('Click to toggle').'">'.$eol.$dtab.'<br />'.$eol.$tab.'</div>'.$eol.$tab;
			
		$output .= $eol.'<h3 class="hndle">'.$eol.$dtab.'<span>'.$label.'</span>'.$eol.$tab.'</h3>'.$eol.$tab.'<div class="inside">'.$eol.$tab;	
		
		return $output;
		
	}
	
	static function close_postbox() {
		
		$eol = "\n";
		
		return $eol.'</div>'.$eol.'</div>';
		
	}
	
	/**
	 *
	 * Wrapping postboxes into sortables
	 *
	 */
	static function sortable($id, $postboxes) {
		
		if (!is_array($postboxes)) $postboxes = array($postboxes);
		
		$sortable = self::open_sortable($id);
		
		foreach ($postboxes as $postbox) :
		
			$sortable .= $postbox;
			
		endforeach;
		
		$sortable .= self::close_sortable();
		
		echo $sortable;
		
	}
	
	/**
	 *
	 * Opening and closing the postboxes
	 *
	 */
	static function open_sortable($id) {
		
		$eol = "\n";
		
		$tab = "\t";
	
		$output = $eol.'<div id="'.$id.'-sortables" class="meta-box-sortables ui-sortable">'.$eol.$tab;
		
		return $output;
		
	}
	
	static function close_sortable() {
		
		return "\n</div>";
		
	}
	
	/**
	 *
	 * Changing to the next column
	 *
	 */
	 static function column($n) {
		 
		 $eol = "\n";
		 
		 echo $eol.'</div>'.$eol.'<div id="postbox-container-'.$n.'" class="postbox-container">';
		 
	 }
	 
	/**
	 *
	 * Wrapping different sections in containers inside postbox
	 *
	 */
	static function wrapper($id, $label, $postbox_id, $sections, $atts = false) {
		
		$wrapper = self::open_sortable($id);
			
		$wrapper .=  self::open_postbox($label, $postbox_id);
		
		foreach ($sections as $section) $wrapper .= self::wrap_section($section, $atts);
		
		$wrapper .= self::clear_it(false);
		
		$wrapper .= self::close_postbox();
		
		$wrapper .= self::close_sortable();
		
		echo $wrapper;
			
	}
	
	/**
	 *
	 * Wrapping section in container
	 *
	 */
	static function wrap_section($section_id, $attributes = false) {
		
		$eol = "\n";
		
		$tab = "\t";
		
		ob_start();
		
		do_settings_sections($section_id);
		
		$section = ob_get_contents();
		
		ob_end_clean();
	
		return self::tag_it($section, 'div', 1, $attributes);
		
	}
	
	/**
	 *
	 * Wrapping elements into html tags
	 *
	 */
	static function tag_it($element, $tag, $indent = false, $atts = false, $echo = false) {
	
		$eol = "\n";
		
		$tab = "\t";
		
		$attributes = '';
		
		if (false !== $atts) :
			
			foreach ($atts as $attribute => $value) $attributes .= ' '.$attribute.'="'.$value.'"';
		
		endif;
		
		$item = $eol;
		
		if (false != $indent) :
		
			for ($i = 0; $i <= $indent; $i++) $item .= $tab;
			
		endif;
		
		$item .= '<'.$tag.$attributes.'>'.$element.'</'.$tag.'>';
		
		if (false === $echo) return $item;
		
		echo $item; 
	
	}
	
	/**
	 *
	 * Wrapping fields in unordered lists
	 *
	 * uses tag_it function
	 *
	 */
	static function list_it($fields, $header = false, $atts = false, $list_atts = false, $echo = true) {
	
		$eol = "\n";
		
		$list = '';
		
		$list_items = '';
		
		if (false != $header) $list .= $header.$eol;
		
		foreach ($fields as $field) $list_items .= self::tag_it($field, 'li', 1, $list_atts);
		
		$list .= self::tag_it($list_items, 'ul', $atts);
		
		if (false === $echo) return $list;
		
		echo $list;
	
	}
	
	/**
	 *
	 * Putting the clear both div
	 *
	 * uses tag_it function
	 *
	 */
	static function clear_it($echo = true) {
	
		$clear_both = self::tag_it('', 'div', false, array('style' => 'clear: both;'));
		
		if (false === $echo) return $clear_both;
		
		echo $clear_both;
	
	}
	
	/**
	 *
	 * Output options for debugging
	 *
	 */
	static function debug_info($options, $label) {
	
		$postbox = self::open_postbox($label, 'debug-info', true);
		
		$postbox .= self::tag_it(a5_get_version(), 'p');
		
		if (!is_array($options)) $options = array ($options);
		
		$opt_str = '';
		
		foreach ($options as $key => $value) :
		
			$key = str_replace('_', '&nbsp;', $key);
			
			if (is_array($value)) :
			
				ob_start();
		
				var_dump($value);
				
				$value = ob_get_contents();
				
				$value = self::tag_it($value, 'pre', 3);
				
				ob_end_clean();
				
			else:
			
				if (true === $value) $value = 'true';
			
				if (false === $value) $value = 'false';
				
				if (NULL === $value) $value = 'NULL';
				
				if (empty($value)) $value = __('Not set', 'custom-login-page');
			
				$value = str_replace(array("\r\n", "\n", "\r"), '<br />', $value);
			
			endif;
			
			$key = self::tag_it(ucwords($key).':', 'td', 2, array('style' => 'width: 25%; border: solid 1px'));
			
			$value = self::tag_it($value, 'td', 2, array('style' => 'border: solid 1px'));
			
			$opt_str .= self::tag_it($key.$value, 'tr', 1);
		
		endforeach;
		
		$postbox .= self::tag_it($opt_str, 'table', 0, array('style' => 'border-collapse: collapse'));
		
		$postbox .= self::close_postbox();
		
		return $postbox;
		
	}
	
	/**
	 *
	 * Output help box
	 *
	 */
	static function help_box($text, $label) {
		
		$eol = "\n";
		
		$tab = "\t";
		
		$postbox = self::open_postbox($label, sanitize_key($label));
		
		$postbox .= $text;
		
		$postbox .= self::close_postbox();
		
		return $postbox;
		
	}
	
	/**
	 *
	 * Output donation box
	 *
	 */
	static function donation_box($text, $label, $paypal = false, $flattr = false) {
		
		$eol = "\n";
		
		$tab = "\t";
		
		$postbox = self::open_postbox($label, 'donations');
		
		$postbox .= $text;
		
		if ($paypal) :
		
			$postbox .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">'.$eol.$tab.'<input type="hidden" name="cmd" value="_s-xclick">'.$eol.$tab;
			$postbox .= '<input type="hidden" name="hosted_button_id" value="'.$paypal.'">'.$eol.$tab;
			$postbox .= '<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">'.$eol.$tab;
			$postbox .= '<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">'.$eol.$tab.'</form>'.$eol.$tab;
			
		endif;
		
		if ($flattr) :
		
			$postbox .= $eol.$tab.'<a href="https://flattr.com/submit/auto?user_id=tepelstreel&url='.$flattr.'" target="_blank">'.$eol.$tab;
			$postbox .= '<img src="//api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0"></a>'.$eol.$tab;
			
		endif;
		
		$postbox .= self::close_postbox();
		
		return $postbox;
		
	}

	
} // A5_OptionPage

?>