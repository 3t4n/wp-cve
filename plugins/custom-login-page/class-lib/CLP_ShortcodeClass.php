<?php

/**
 *
 * Class Shortcode adds and displays the Shortcode
 *
 * @ Custom Login Page
 *
 */
class CLP_Shortcode {
	
	static $options;
	
	function __construct($multisite) {
		
		self::$options = ($multisite) ? get_site_option('clp_shortcode_options') : get_option('clp_shortcode_options');
		$widget_options = ($multisite) ? get_site_option('clp_widget_options') : get_option('clp_widget_options');
		
		self::$options['_login_form_top'] = @$widget_options['login_form_top'];
		self::$options['_login_form'] = @$widget_options['login_form'];
		self::$options['_login_form_bottom'] = @$widget_options['login_form_bottom'];
		
		add_shortcode('a5-login-form', array($this, 'clp_display'));
		
		if (!empty(self::$options['login_form_top'])) add_filter('login_form_top', array($this, 'print_login_form_top'));
		if (!empty(self::$options['login_form'])) add_filter('login_form_middle', array($this, 'print_login_form_middle'));
		if (!empty(self::$options['login_form_bottom'])) add_filter('login_form_bottom', array($this, 'print_login_form_bottom'));
	
	}
	
	function print_login_form_top() {
		
		return self::$options['login_form_top'];
	
	}
	
	function print_login_form_middle() {
		
		return self::$options['login_form'];
	
	}
	
	function print_login_form_bottom() {
		
		return self::$options['login_form_bottom'];
	
	}

	/**
	 *
	 * Display the login form with shortcode
	 *
	 */
	public function clp_display() {
		
		$eol="\n";
		
		ob_start();
		
		echo '<div class="a5_custom_login_container" style="margin: 5px; padding: 20px;">'.$eol;
		
		if (!is_user_logged_in()) :
		
			$formargs['redirect'] = (isset(self::$options['redirect']) && !empty(self::$options['redirect'])) ? self::$options['redirect'] : home_url($_SERVER['REQUEST_URI']);						
			$formargs['form_id'] = (isset(self::$options['form_id']) && !empty(self::$options['form_id'])) ? self::$options['form_id'] : 'loginform';
			$formargs['label_username'] = (isset(self::$options['label_username']) && !empty(self::$options['label_username'])) ? self::$options['label_username'] : __('Username');
			$formargs['label_password'] = (isset(self::$options['label_password']) && !empty(self::$options['label_password'])) ? self::$options['label_password'] : __('Password');
			$formargs['label_remember'] = (isset(self::$options['label_remember']) && !empty(self::$options['label_remember'])) ? self::$options['label_remember'] : __('Remember Me');
			$formargs['label_log_in'] = (isset(self::$options['label_log_in']) && !empty(self::$options['label_log_in'])) ? self::$options['label_log_in'] : __('Log In');
			$formargs['id_username'] = (isset(self::$options['id_username']) && !empty(self::$options['id_username'])) ? self::$options['id_username'] : 'user_login';
			$formargs['id_password'] = (isset(self::$options['id_password']) && !empty(self::$options['id_password'])) ? self::$options['id_password'] : 'user_pass';
			$formargs['id_remember'] = (isset(self::$options['id_remember']) && !empty(self::$options['id_remember'])) ? self::$options['id_remember'] : 'rememberme';
			$formargs['id_submit'] = (isset(self::$options['id_submit']) && !empty(self::$options['id_submit'])) ? self::$options['id_submit'] : 'wp-submit';
			$formargs['remember'] = (isset(self::$options['hide_remember']) && !empty(self::$options['hide_remember'])) ? false : true;
			$formargs['value_username'] = (isset(self::$options['value_username']) && !empty(self::$options['value_username'])) ? self::$options['value_username'] : NULL;
			$formargs['value_remember'] = (isset(self::$options['value_remember']) && !empty(self::$options['value_remember'])) ? true : false;
			
			if (isset(self::$options['title']) && !empty(self::$options['title'])) $title_tag = ' title="'.self::$options['title'].'"';
			
			if (isset(self::$options['logo']) && !empty(self::$options['logo'])) $img_tag = '<img src="'.self::$options['logo'].'"'.$title_tag.' />';
			
			if (isset($img_tag)) echo (isset(self::$options['url']) && !empty(self::$options['url'])) ? '<a href="'.self::$options['url'].'"'.$title_tag.'>'.$img_tag.'</a>' : $img_tag;
			
			if (isset(self::$options['login_message']) && !empty(self::$options['login_message'])) echo self::$options['login_message'];
			
			wp_login_form($formargs);
			
			if (isset(self::$options['login_footer']) && !empty(self::$options['login_footer'])) : echo self::$options['login_footer']; 
			
			endif;
		
		else :
		
			wp_loginout( home_url() );
			
			echo ' | ';
			
		endif;
		
		wp_register('', '');
		
		echo '</div>'.$eol;
		
		$output = ob_get_contents();
		
		if (!empty(self::$options['_login_form_top'])) $output = str_replace(self::$options['_login_form_top'], self::$options['login_form_top'], $output);
		if (!empty(self::$options['_login_form'])) $output = str_replace(self::$options['_login_form'], self::$options['login_form'], $output);
		if (!empty(self::$options['_login_form_bottom'])) $output = str_replace(self::$options['_login_form_bottom'], self::$options['login_form_bottom'], $output);
		
		ob_end_clean();
		
		return $output;
		
	}

}

?>