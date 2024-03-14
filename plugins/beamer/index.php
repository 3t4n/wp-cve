<?php
	/*
	Plugin Name: Beamer
	Plugin URI: http://www.getbeamer.com/
	Description: Beamer is a smart and easy-to-use newsfeed and changelog you can install on your site or app to announce relevant news, latest features, and updates.
	Version: 5.3
	Author: Beamer
	Author URI: http://www.getbeamer.com/
	License: GPLv2 or later
	Text Domain: beamer

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
	*/

	// BEAMER VERSION ---------------------------------------------------------------------------
	function bmr_version(){
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if( function_exists('get_plugin_data') ){
		    $plugin_data = get_plugin_data(__FILE__, false, false);
		    return $plugin_data['Version'];
		}else{
			return 'legacy';
		}
	}

	// LOG TOOL
	if (!function_exists('bmr_write_log')) {
	    function bmr_write_log ( $log )  {
	        if ( true === WP_DEBUG ) {
	            if ( is_array( $log ) || is_object( $log ) ) {
	                error_log( print_r( $log, true ) );
	            } else {
	                error_log( $log );
	            }
	        }
	    }
	}

	// BEAMER URLs ---------------------------------------------------------------------------
	// (just a shorthand for the most used external urls)
	function bmr_url($target, $mode = 'app', $echo = false) {
		$url = 'https://'.$mode.'.getbeamer.com/'.$target;
		if($echo == true){
			echo $url;
		}else{
			return $url;
		}
	}

	function bmr_url_signup($echo = false, $ref = true){
		if($ref == true){
			$url = bmr_url('signup', 'app', false).'?ref=wp_plugin';
		}else{
			$url = bmr_url('signup', 'app', false);
		}
		if($echo == true){
			echo $url;
		}else{
			return $url;
		}
	}

	function bmr_url_settings($echo = false){
		$url = get_admin_url().'options-general.php?page=beamer-settings';
		if($echo == true){
			echo $url;
		}else{
			return $url;
		}
	}

	// CHECK CURRENT SCREEN
	function bmr_is_settings_page(){
		if( get_current_screen()->id == 'settings_page_beamer-settings' ){
			return true;
		} else {
			return false;
		}
	}

	// BEAMER OPTIONS PAGE ---------------------------------------------------------------------------
	// (add admin options page)
	include('beamer-settings.php');

	function bmr_enqueue_styles() {
		wp_enqueue_style('beamer-styles', plugins_url('css/beamer-admin.css',__FILE__));
	}

	function bmr_options_styles() {
		$screen = bmr_is_settings_page();
		if ( $screen == true ) {
			bmr_enqueue_styles();
		}
	}
	add_action('admin_enqueue_scripts', 'bmr_options_styles');

	// CALL SETTINGS ---------------------------------------------------------------------------
	// (get the settings)
	function bmr_get_settings() {
		return get_option( 'beamer_settings_option_name' );
	}

	// CALL SETTING ---------------------------------------------------------------------------
	// (get the individual setting)
	function bmr_get_setting($field = 'product_id'){
		$options = bmr_get_settings();
		if( isset($options[$field]) ){
			$value = $options[$field];
			return $value;
		}else{
			return null;
		}
	}

	// BEAMER CORE ---------------------------------------------------------------------------
	// (check all settings and create an array and discard those that are not in use)
	function bmr_parse_settings() {
		if( bmr_get_setting('product_id') != ''){
			// Create array
			$bmrscript = array();
			// Product ID (always required)
			$bmrscript['product_id'] = 'product_id : "'.bmr_get_setting('product_id').'"';
			// Selector (required unless alert is checked)
			if( bmr_get_setting('selector') ){
				$bmrscript['selector'] = 'selector : "'.bmr_get_setting('selector').'"';
			}
			// Position modifiers ----------------------------------------------------------------------
			$top = bmr_get_setting('top');
			$right = bmr_get_setting('right');
			$bottom = bmr_get_setting('bottom');
			$left = bmr_get_setting('left');
			// Advenced settings ---------------------------------------------------------------------
			if( bmr_get_setting('display') ){
				$bmrscript['display'] = 'display : "'.bmr_get_setting('display').'"';
			}
			if( isset($top) && !empty($top) && $top != 0 ){
				$bmrscript['top'] = 'top : '.bmr_get_setting('top');
			}
			if( isset($right) && !empty($right) && $right != 0 ){
				$bmrscript['right'] = 'right : '.bmr_get_setting('right');
			}
			if( isset($bottom) && !empty($bottom) && $bottom != 0 ){
				$bmrscript['bottom'] = 'bottom : '.bmr_get_setting('bottom');
			}
			if( isset($left) && !empty($left) && $left != 0 ){
				$bmrscript['left'] = 'left : '.bmr_get_setting('left');
			}
			if( bmr_get_setting('button_position') ){
				$bmrscript['button_position'] = 'button_position : "'.bmr_get_setting('button_position').'"';
			}
			if( bmr_get_setting('button_default') == 'off' ){
				$bmrscript['button'] = 'button : false';
			}
			// Filters and callbacks ------------------------------------------------------------
			if( bmr_get_setting('language') ){
				$bmrscript['language'] = 'language : "'.bmr_get_setting('language').'"';
			}
			if( bmr_get_setting('mobile') == true ){
				$bmrscript['mobile'] = 'mobile : false';
			}
			if( bmr_get_setting('delay') > 0 ){
				$bmrscript['delay'] = 'delay : '.bmr_get_setting('delay');
			}
			if( bmr_get_setting('lazy') == true ){
				$bmrscript['lazy'] = 'lazy : true';
			}
			if( bmr_get_setting('alert') == true ){
				$bmrscript['alert'] = 'alert : false';
			}
			if( bmr_get_setting('callback') ){
				$bmrscript['callback'] = 'callback : '.bmr_get_setting('callback');
			}
			if( bmr_get_setting('button_icon') != 'flame' ){
				$bmrscript['icon'] = 'icon : "'.bmr_get_setting('button_icon').'"';
			}
			if( bmr_get_setting('unread_counter') == true ){
				$bmrscript['counter'] = 'counter : false';
			}
			if( bmr_get_setting('push_prompt') != 'default' ){
				$bmrscript['push_prompt'] = 'notification_prompt : "'.bmr_get_setting('push_prompt').'"';
			}
			// User Settings ----------------------------------------------------------------------
			if( bmr_get_setting('user') == true OR bmr_get_setting('userfilter') == true ){
				if( is_user_logged_in() ){
					$this_user = wp_get_current_user();
					// Send user data
					if( bmr_get_setting('user') == true ){
						$this_name = $this_user->user_firstname;
						$this_surname = $this_user->user_lastname;
						$this_alias = $this_user->user_login;
						$this_email = $this_user->user_email;
						if($this_name != '' && $this_surname != ''){
							$bmrscript['user_firstname'] = 'user_firstname : "'.$this_name.'"';
							$bmrscript['user_lastname'] = 'user_lastname : "'.$this_surname.'"';
						}else{
							$bmrscript['user_firstname'] = 'user_firstname : "'.$this_alias.'"';
						}
						$bmrscript['user_email'] = 'user_email : "'.$this_email.'"';
					}
					// Send user filter
					if( bmr_get_setting('userfilter') == true ){

						$user_roles = $this_user->roles;
						$this_roles = array();
						if ( in_array( 'subscriber', $user_roles, true ) ) {
						    $this_roles['wp_sub'] = 'wp_sub';
						}
						if ( in_array( 'colaborator', $user_roles, true ) ) {
						    $this_roles['wp_colab'] = 'wp_colab';
						}
						if ( in_array( 'author', $user_roles, true ) ) {
						    $this_roles['wp_author'] = 'wp_author';
						}
						if ( in_array( 'editor', $user_roles, true ) ) {
						    $this_roles['wp_editor'] = 'wp_editor';
						}
						if ( in_array( 'administrator', $user_roles, true ) ) {
						    $this_roles['wp_admin'] = 'wp_admin';
						}

						if( isset($this_roles) && !empty($this_roles) ){
							$roles = implode(';', $this_roles);
							$filterquery = 'wp_logged;'.$roles;
						}else{
							$filterquery = 'wp_logged';
						}
						$filterlist = bmr_get_setting('filters') ? $filterquery.';'.bmr_get_setting('filters') : $filterquery;
						$bmrscript['filter'] = 'filter : "'.$filterlist.'"';
					}
				}
			}

			// Filters ----------------------------------------------------------------------
			if( bmr_get_setting('filters') && bmr_get_setting('userfilter') != true ){
				$bmrscript['filter'] = 'filter : "'.bmr_get_setting('filters').'"';
			}

			// Wordpress referal ----------------------------------------------------------------------
			$thisver = str_replace( ".", "", bmr_version() );
			$bmrscript['source'] = 'source: "wordpress'.$thisver.'"';
			// Compile Beamer Script
			return $bmrscript;
		}else{
			return null;
		}
	}

	// BEAMER TRIGGER ---------------------------------------------------------------------------
	// (easy to add menu item)
	if ( !class_exists('bmr_menu_metabox')) {
	    class bmr_menu_metabox {
	        public function add_nav_menu_meta_boxes() {
	        	add_meta_box(
	        		'bmr_nav_link',
	        		__('Beamer'),
	        		array( $this, 'nav_menu_link'),
	        		'nav-menus',
	        		'side',
	        		'low'
	        	);
	        }
	        public function nav_menu_link() {
	        	echo ('
	        	<div id="posttype-bmr-custom-button" class="posttypediv">
	        		<div id="tabs-panel-wishlist-login" class="tabs-panel tabs-panel-active">
	        			<ul id ="wishlist-login-checklist" class="categorychecklist form-no-clear">
	        				<li>
	        					<label class="menu-item-title">
	        						<input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1"> 									Beamer Trigger
	        					</label>
	        					<input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
	        					<input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="What\'s New">
	        					<input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]" value="">
	        					<input type="hidden" class="menu-item-classes" name="menu-item[-1][menu-item-classes]" value="beamerTrigger">
	        				</li>
	        			</ul>
	        		</div>
	        		<p class="button-controls">
	        			<span class="add-to-menu">
	        				<input type="submit" class="button-secondary submit-add-to-menu right" value="Add to Menu" name="add-post-type-menu-item" id="submit-posttype-bmr-custom-button">
	        				<span class="spinner"></span>
	        			</span>
	        		</p>
	        	</div>
				');
			}
	    }
	}
	$custom_nav = new bmr_menu_metabox;
	add_action('admin_init', array($custom_nav, 'add_nav_menu_meta_boxes'));

		// Custom trigger icon
		function bmr_trigger_icon(){
			if( bmr_get_setting('menu_custom') == true ){
				$icon = bmr_get_setting('menu_icon') ?: 'notifications';
				$fontsize = bmr_get_setting('menu_font') ?: 'inherit';
				$color = bmr_get_setting('menu_color') ?: 'inherit';
				$hover = bmr_get_setting('menu_hover') ?: 'inherit';

				$html = array();
				$html[] = '<style>';

					$html[] = '@import url(\'https://fonts.googleapis.com/icon?family=Material+Icons\');';
					$html[] = '.beamerTrigger a, .beamer_beamerSelector a {display:none !important;}';

					$html[] = '.beamerTrigger:before, .beamer_beamerSelector:before {';
						$html[] = 'font-family: \'Material Icons\';';
						$html[] = 'line-height: 1;';
						$html[] = 'vertical-align: middle;';
						$html[] = 'content: "'.$icon.'";';
						if( $fontsize != 'inherit' ){ $html[] = 'font-size: '.$fontsize.'px;'; }
						if( $color != 'inherit' ){ $html[] = 'color: #'.$color.';'; }
					$html[] = '}';

					if( $hover != 'inherit' ){
						$html[] = '.beamerTrigger:hover:before, .beamer_beamerSelector:hover:before {color: #'.$hover.'; -webkit-transition: inherit; transition: inherit;}';
					}

				$html[] = '</style>';
				echo ( implode(' ', $html) );
			}
		}
		add_action('wp_head', 'bmr_trigger_icon');

	// BEAMER SCRIPT ---------------------------------------------------------------------------
	function bmr_create_script() {
		// This calls the current settings
		$settings = bmr_parse_settings();
		// This creates the embedded script
		if($settings != null && $settings != ''){
			return 'var beamer_config = { '.implode(", ", $settings).' };';
		}else{
			return null;
		}
	}

		// BEAMER FILTERS ---------------------------------------------------------------------------
		function bmr_filter($name, $condition){
			// Check for the filter if it is active on settings page
			if(bmr_get_setting($name) == true && $condition == true){
				return true;
			}else{
				return false;
			}
		}

		function bmr_filter_script() {
			if( bmr_filter('master', true) == true ){
				// if the master switch is checked
				$show = false;
			}else{
				// Create filters array
				$filters = array();
				if(bmr_filter('nohome', is_home()) == true){ $filters['nohome'] = true; }
				if(bmr_filter('noposts', is_single()) == true){ $filters['noposts'] = true; }

				if( bmr_get_setting('nofront') == true && bmr_get_setting('nopages') == true ){
					if( is_front_page() OR is_page() ){
						$filters['nofront'] = true; $filters['nopages'] = true;
					}
				}elseif( bmr_get_setting('nofront') != true && bmr_get_setting('nopages') == true ){
					if( !is_front_page() && is_page() ){
						$filters['nopages'] = true;
					}
				}elseif( bmr_get_setting('nofront') == true && bmr_get_setting('nopages') != true ){
					if( is_front_page() ){
						$filters['nofront'] = true;
					}
				}

				if(bmr_filter('noarchive', is_archive()) == true){ $filters['noarchive'] = true; }
				// Check if the special ID filter is on
				if(bmr_get_setting('noid') != '' && !is_home() && !is_archive()){
					$ignore = bmr_get_setting('noid');
					$parsed = str_replace(' ', '', $ignore);
					$list = explode(',', $parsed);
					foreach($list as $filter){
						if(get_the_ID() == $filter){ $filters['noid-'.$filter] = true; }
					}
				}
				// Check if the special post type filter is on
				if(bmr_get_setting('notype') != '' && !is_home() && !is_archive()){
					$ignore = bmr_get_setting('notype');
					$parsed = str_replace(' ', '', $ignore);
					$list = explode(',', $parsed);
					foreach($list as $filter){
						if(get_post_type( get_the_id() ) == $filter){
							$filters['notype-'.$filter] = true;
						}
					}
				}
				// Check the operator
				if( bmr_get_setting('filterop') == 'only' ){
					// show true if the filters are true and the operator is only
					$show = !empty($filters) && in_array(true, $filters, true) ? true : false;
				}else{
					// show false if the filters are true and the operator is not (or fallback)
					$show = !empty($filters) && in_array(true, $filters, true) ? false : true;
				}

				// Check if log filter is on
				if(bmr_filter('logged', !is_user_logged_in()) == true){ $show = false; }
			}
			return $show;
		}

	// BEAMER ENQUEUE ---------------------------------------------------------------------------
	function bmr_enqueue_scripts() {
		// Get the script
		$script = bmr_create_script();
		// check always that there is a product ID
		if(bmr_get_setting('product_id') != '' && bmr_filter_script() == true){
		    wp_enqueue_script( 'beamer', bmr_url('js/beamer-embed.js', 'app'), array(), null );
		    wp_add_inline_script('beamer', $script, 'before');
		}
	}
	add_action('wp_enqueue_scripts', 'bmr_enqueue_scripts');

	function bmr_enqueue_defer($tag, $handle) {
	    if ( 'beamer' !== $handle ){
			return $tag;
	    }else{
			return str_replace( ' src', ' defer="defer" src', $tag );
	    }
	}
	add_filter('script_loader_tag', 'bmr_enqueue_defer', 10, 2);

	// BEAMER API ---------------------------------------------------------------------------
	function bmr_api_url($path = 'posts', $id = null){
		$url = bmr_url('v0', 'api');
		if( $id != null ){
			return $url.'/'.$path.'/'.$id;
		}else{
			return $url.'/'.$path;
		}
	}

	// Ping API and check if the API key is valid
	function bmr_api_ping(){

		$api_key = bmr_get_setting('api_key');
		$api_url = bmr_api_url('ping');
		$args = array(
			'method' => 'POST',
		    'headers' => array(
		        'Content-Type' => 'application/json',
		        'Beamer-Api-Key' => $api_key,
		        'User-Agent' => 'WordPress Plugin PING (v'.bmr_version().'/php'.phpversion().')'
		    )
		);
		$response = wp_remote_request( $api_url, $args );
		$body     = wp_remote_retrieve_body( $response );
		$http_code = wp_remote_retrieve_response_code( $response );
		//$result = json_decode($body, true);
		if ( is_wp_error( $response ) || ! isset( $response['body'] ) ) {
		    return false;
		}else{
			return true;
		}

	}

	function bmr_api_ping_result( $prev, $new ){
		if( $prev != $new ){
			if( bmr_api_ping() == true ){
				update_option('bmr_api_ping_check', 'ok');
			}else{
				update_option('bmr_api_ping_check', 'error');
			}
		}
	}
	add_action('update_option_beamer_settings_option_name', 'bmr_api_ping_result', 10, 2);

	// Check for API errors and compile list
	function bmr_api_errors(){
		$api_errors = array();
		if( bmr_get_setting('api_key') == '' ){
			// No key
			$api_errors['201'] = '<strong>No Beamer API key was found:</strong> Please provide one in your <a href="'.bmr_url_settings().'">Beamer Settings page</a>';
		}else{
			// Wrong key
			if( get_option('bmr_api_ping_check', 'error') == 'error' ){
				$api_errors['202'] = '<strong>Invalid Beamer API key:</strong> Please provide a valid one in your <a href="'.bmr_url_settings().'">Beamer Settings page</a>';
			}
		}
		return $api_errors;
	}

	// BEAMER NOTICE ---------------------------------------------------------------------------
	add_action('admin_notices', 'bmr_notices');
	function bmr_notices() {
		$screen = bmr_is_settings_page();
		// product ID missing (101)
		if( bmr_get_setting('product_id') == ''){
			if( $screen == false ){
				// Show this in any page
				$notice = '<strong>Success, Beamer is installed!</strong> Click <a href="'.bmr_url_settings().'">here</a> to connect to your Beamer account. If you don\'t have a Beamer account, <a href="'.bmr_url_signup().'">get one for free.</a>';
			} else {
				// Show this in settings page
				$notice = '<strong>You need to add your Product ID.</strong> Fill the "Product ID" field with the number provided in the top bar of your <a href="'.bmr_url('home').'" target="_blank">Beamer Dashboard</a>. If you don\'t have a Beamer account, <a href="'.bmr_url_signup().'">get one for free.</a>';
			}
			echo('<div id="bmr-error-101" class="notice update-nag"><p>'.$notice.'</p></div>');
		}
		// API suggestion
		if( bmr_get_setting('api_key') == '' && $screen == true ){
			$notice = 'Did you know that you can publish in Wordpress and Beamer simultaneously? Scroll down to the <strong>Beamer API</strong> section and activate the <strong>API feature.</strong>';
			echo('<div id="bmr-api-suggest" class="notice update-nag"><p>'.$notice.'</p></div>');
		}
		// if API error (201-401)
	    if( bmr_get_setting('api_set') == true){
			$errors = bmr_api_errors();
			foreach($errors as $key => $error){
				echo('<div id="bmr-error-'.$key.'" class="notice error"><p>'.$error.'</p></div>');
			}
	    }
	}

	// If no errors are found and API is set to true add the API module
	if( bmr_get_setting('api_set') == true && !bmr_api_errors() ){
		include('beamer-api.php');
	}

	// BEAMER REVIEWS ---------------------------------------------------------------------------
	add_action('admin_notices', 'bmr_reviews');
	function bmr_reviews() {
		$screen = bmr_is_settings_page();
		if( $screen == true ){
			$notice = '<b>Do you like using Beamer?</b> Support us by leaving a review.';
			echo('<div id="bmr-api-review" class="notice update-nag is-dismissible"><p>'.$notice.'<a class="bmrButton" href="https://wordpress.org/support/plugin/beamer/reviews/" target="_blank" rel="nofollow">Share the love</a></p></div>');
		}
	}
?>