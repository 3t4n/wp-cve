<?php
class authorsure_profile {
    
	static function init() {
		add_action('load-profile.php', array(__CLASS__, 'load_page'));	
		add_action('load-user-edit.php', array(__CLASS__, 'load_page'));	
		add_action('edit_user_profile_update', array(__CLASS__, 'save_user'));
		add_action('personal_options_update', array(__CLASS__, 'save_profile'));		
	}

	static function get_user() {
		global $user_id;
		wp_reset_vars(array('user_id'));  //get ID of user being edited if not editing own profile 
		return (isset($user_id) && ($user_id > 0)) ? new WP_User((int) $user_id) : wp_get_current_user();	        
	}

	static function load_page() {
		$profile = self::get_user();
		if (authorsure_options::is_author($profile)) {
			add_filter('user_contactmethods', array('authorsure_options','add_contactmethods_profile'),10,1);		
			add_filter( 'genesis_formatting_allowedtags', array('authorsure_options','genesis_allow_img') );
			if (!self::is_profile() || current_user_can('manage_options'))
				add_action( self::is_profile() ? 'show_user_profile' : 'edit_user_profile', array(__CLASS__,'show_authors_panel'),8,2);
			if ('extended'==authorsure_options::get_option('author_bio'))
				add_action( self::is_profile() ? 'show_user_profile' : 'edit_user_profile', array(__CLASS__,'show_extended_bio'),8,2);
			authorsure_options::wordpress_allow_img();
			add_action ('admin_enqueue_scripts',array(__CLASS__, 'enqueue_styles'));
			$current_screen = get_current_screen();
			if (method_exists($current_screen,'add_help_tab')) {
    		   $current_screen->add_help_tab( array(
        		'id'	=> 'authorsure_instructions_tab',
        		'title'	=> __('AuthorSure Instructions'),
        		'content'	=> '<h3>AuthorSure Instructions For Authors</h3>
<ol>
<li>Sign up for a Google account</li>
<li>Upload a photo to your Google profile</li>
<li>Add a contributor link that refers to your author page on this site. E.g. '.get_author_posts_url($profile->ID).'</li>
<li>Update your profile below with your Google Plus Profile URL</li>
<li>Enter the URL of a post you have written into the <a href="http://www.google.com/webmasters/tools/richsnippets">Google Rich Snippets Testing Tool</a> and 
check the page is valid and that your photo appears in the preview of the search results</li>
<li>Submit a <a href="http://www.authorsure.com/authorship-request">Authorship Request</a> to Google</li>
</ol>') );

	    		$current_screen->add_help_tab( array(
		        	'id'	=> 'authorsure_help_tab',
    		    	'title'	=> __('AuthorSure Profiles'),
        			'content'	=> __(
'<h3>AuthorSure Profiles</h3><p>In the <b>Contact Info</b> section below you can specify links to your other profies such as GooglePlus, Facebook, Twitter, etc. </p>
<p>The Authorsure plugin will show these links on your Author page with the rel="me" attribute set for authentication purposes.</p>
<p>It is important to fill in your <b>GooglePlus Profile URL</b> below if you want to verify your author profile with Google.</p>')) );
			}
		}
	}
	
	static function enqueue_styles() {
    	wp_enqueue_style( 'authorsure-admin', AUTHORSURE_PLUGIN_URL.'styles/admin.css',array(),AUTHORSURE_VERSION);
 	}

	static function show_extended_bio($user) {
		$label = __('Extended Biographical Info');
		$help = __('Supply an extended bio to go on your author page. This can include links, images and videos.');
		$key = authorsure_options::get_extended_bio_key();
		$bio =  get_user_option($key, $user->ID);
		print <<< EXTENDED_BIO
<table class="form-table">
<tr>
	<th><label for="{$key}">{$label}</label></th>
	<td><textarea name="{$key}" id="{$key}" rows="10" cols="30">{$bio}</textarea><br />
	<span class="description">{$help}</span></td>
</tr>
</table>
EXTENDED_BIO;
    }
    
	static function show_authors_panel($user) {
		$label1 = __('Include on Author List');
		$help1 = __('Check the box to include the author in the list of authors. The author list is displayed by placing the shortcode [authorsure_authors] on a page.');
		$key1 = authorsure_options::get_show_author_key();
		$label2 = __('Position on Author List');
		$help2 = __('Enter a sequence number if you want a custom sort order for your author list when using the shortcode [authorsure_authors].');
		$key2 = authorsure_options::get_author_index_key();
		$author_index = get_user_option($key2, $user->ID);
		$show_author = get_user_option($key1, $user->ID);
		$show = $show_author ? 'checked="checked"' : '';
		print <<< AUTHOR_LIST
<h3 class="icon-title">AuthorSure Bio Settings</h3>
<table class="form-table">
<tr>
	<th><label for="{$key1}">{$label1}</label></th>
	<td><input class="valinp" type="checkbox" name="{$key1}" id="{$key1}" {$show} value="1" /><br />
	<span class="description">{$help1}</span></td>
</tr>
<tr>
	<th><label for="{$key2}">{$label2}</label></th>
	<td><input class="valinp" type="name" size="4" name="{$key2}" id="{$key2}" value="{$author_index}" /><br />
	<span class="description">{$help2}</span></td>
</tr>
</table>
AUTHOR_LIST;
    }    
	
	static function is_profile() {
		return defined('IS_PROFILE_PAGE') && IS_PROFILE_PAGE; 
	}
	
	static function save_profile($user_id) {
		if (self::is_profile()) self::save($user_id);
	}

	static function save_user($user_id) {
		if ( ! self::is_profile()) self::save($user_id);
	}

	static function save($user_id) {
		$profiles = authorsure_options::get_pro_options();
		if (is_array($profiles)) 
			foreach ($profiles as $key => $labels) 
				if (array_key_exists($key,$_POST)) {
					$val = strtolower(trim($_POST[$key]));
					if ($key == 'skype') {
						$_POST[$key] = self::sanitize_skype($val);
					} elseif (($key == 'twitter') && is_null(parse_url($val,PHP_URL_HOST))) {
						$_POST[$key] = esc_attr(str_replace('@','',$val));
					} else
						$_POST[$key] = esc_url($val);
				}
		$extended_bio_key = authorsure_options::get_extended_bio_key();	
		$old_val =  get_user_option($extended_bio_key, $user_id);		
		$new_val = array_key_exists($extended_bio_key,$_POST) ? $_POST[$extended_bio_key] : '';
		if  ($old_val != $new_val) update_usermeta( $user_id, $extended_bio_key, $_POST[$extended_bio_key]);		
		
		$show_author_key = authorsure_options::get_show_author_key();	
		$new_val = array_key_exists($show_author_key,$_POST) ? $_POST[$show_author_key] : false;
		$old_val =  get_user_option($show_author_key, $user_id);
		if  ($old_val != $new_val) update_usermeta( $user_id, $show_author_key, $new_val);			

		$author_index_key = authorsure_options::get_author_index_key();	
		$new_val = array_key_exists($author_index_key,$_POST) ? $_POST[$author_index_key] : false;
		$old_val =  get_user_option($author_index_key, $user_id);
		if  ($old_val != $new_val) update_usermeta( $user_id, $author_index_key, $new_val);	
	}	

	static function sanitize_skype($skype_name) {
		if ((strlen($skype_name) > 6) && ('skype:'==substr($skype_name,0,6))) $skype_name = substr($skype_name,6);
		if (strpos($skype_name,'?') == FALSE) 
			return $skype_name;
		else
			return substr($skype_name,0, strpos($skype_name,'?'));
	}

}
