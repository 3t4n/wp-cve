<?php
class authorsure_options {

    private static $extended_bio_metakey = 'authorsure_extended_bio';
    private static $hide_author_box_metakey = 'authorsure_hide_author_box'; //used for exceptions where the default is to show the author box
    private static $show_author_box_metakey = 'authorsure_show_author_box'; //used for exceptions where the default is to hide the author box
    private static $show_author_metakey = 'authorsure_show_author_on_list';
    private static $author_index = 'authorsure_author_index';
    private static $include_css_metakey = 'authorsure_include_css';
 	    
	private static $defaults = array(
 		'author_rel' => 'byline',  //menu, byline, footnote, box
 		'publisher_rel' => '',  //Google Plus URL of publisher	    
 	    'footnote_last_updated_by' => 'Last updated by',
 	    'footnote_last_updated_at' => 'at',
 		'footnote_show_updated_date' => true,
		'footnote_prepend_entry' => false,
		'footnote_alignment' => 'left',
 	    'box_about' => 'About', 
 	    'box_title_tag' => 'h4',  	    		
 	    'box_gravatar_size' => 60,
		'box_prepend_entry' => false,
 	    'box_nofollow_links' => false,
 	    'box_show_profiles' => false,
 	    'show_box_on_custom' => false,
 	    'hide_box_on_pages' => false,
		'hide_box_on_front_page' => false,
 	    'menu_about_page' => '',
 	    'menu_primary_author' => '', 	  
		'home_author' => '',
 	    'author_page_hook' => 'loop_start',
 	    'author_page_hook_index' => '1',
		'author_page_filter_bio' => false,   
 	    'author_show_title' => true,
 	    'author_show_avatar' => false,
 	    'author_about' => 'About', 		
 	    'author_bio' => 'summary',
 	    'author_bio_nofollow_links' => false,
	    'author_find_more' => 'Find more about me on:',
		'author_profiles_image_size' => 16,	
		'author_profiles_no_labels' => false,	
		'author_profiles_new_window' => false,		 
	    'author_archive_heading'=> 'Here are my most recent posts',
		'archive_link' => 'publisher', //publisher, top or bottom
		'archive_intro_enabled' => false,
		'archive_last_updated_by' => 'Last updated by',
		'archive_author_id' => 0,
		'archive_hook' => false,
		'minimum_role_for_authorship' => 'contributor',
		'minimum_role_for_bio_links' => 'contributor',
		'minimum_role_for_box_links' => 'contributor',
		'donotcache_avatar' => 'false'		
    );  
      
	private static $pro_defaults = array(
	    'facebook' => array('Facebook','Facebook URL'), 
	    'flickr' => array('Flickr', 'Flickr Profile URL'),
	    'googleplus'=> array('Google Plus', 'Google Plus Profile URL'), 
	    'linkedin' => array('LinkedIn', 'Linked In Profile URL'), 
	    'pinterest' => array('Pinterest', 'Pinterest Profile URL'), 
	    'skype' => array('Skype', 'Skype Name'),
	    'twitter'=> array('Twitter','Twitter Name'),
	    'youtube'=> array('YouTube', 'YouTube Channel URL')
    );    

    private static $options = array();   
    private static $pro_options = array();    
    private static $term_options = array();    
    private static $author = false;  
    private static $intro = '';
    
    private static function get_defaults() {
		return self::$defaults;
    }

    private static function get_pro_defaults() {
		return self::$pro_defaults;
    }

	public static function init() {
		add_action( 'wp_loaded', array(__CLASS__,'wordpress_allow_arel') );	
	}

	public static function get_pro_options ($cache = true) {
		if ($cache && (count(self::$pro_options) > 0)) return self::$pro_options;
		$defaults = self::get_pro_defaults();
		self::$pro_options = apply_filters('authorsure_more_contactmethods',self::get_pro_defaults()); 
   		return self::$pro_options;
	}

	public static function get_options ($cache = true) {
		if ($cache && (count(self::$options) > 0)) return self::$options;
		$defaults = self::get_defaults();
		$options = get_option('authorsure_options');
		self::$options = empty($options) ? $defaults : wp_parse_args($options, $defaults); 
   		return self::$options;
	}

	public static function get_term_options ($cache = true) {
		if ($cache && (count(self::$term_options) > 0)) return self::$term_options;
		$options = get_option('authorsure_term_options');
		self::$term_options = empty($options) ? array() : $options; 
   		return self::$term_options;
	}

	public static function get_option($option_name) {
	    $options = self::get_options();
	    if ($option_name && $options && array_key_exists($option_name,$options)) 
	    	return $options[$option_name];
	    else
	        return false;
	}
	
	public static function get_archive_hook() {
	    $archive_link = self::get_option('archive_link');
	    switch ($archive_link) {
	    	case 'top': return 'loop_start';
			case 'bottom': return 'loop_end';
			default: return '';
	    } 
	}

	public static function get_author_page_hook() {
		$hook = self::get_option('author_page_hook');
		if (empty($hook)) $hook = self::$defaults['author_page_hook']; 
		return $hook;
	}

	public static function get_author_page_hook_index() {
		$hook_index = self::get_option('author_page_hook_index');
		if (empty($hook_index)) $hook_index = self::$defaults['author_page_hook_index']; 
		return $hook_index;
	}

	public static function get_author_index_key() {
		    return self::$author_index;
	}

	public static function get_show_author_key() {
		    return self::$show_author_metakey;
	}

	public static function get_extended_bio_key() {
		    return self::$extended_bio_metakey;
	}

	public static function get_hide_author_box_key() {
		    return self::$hide_author_box_metakey;
	}

	public static function get_show_author_box_key() {
		    return self::$show_author_box_metakey;
	}
	
	public static function get_include_css_key() {
		    return self::$include_css_metakey;
	}
	
	public static function sanitize_publisher($url, $is_url = false) {
		if (! is_null(parse_url($url, PHP_URL_SCHEME)))
			$url = parse_url($url, PHP_URL_PATH);
		if (strpos($url,'/') !== FALSE) {
			$parts = explode('/',$url);
			$id = $parts[0] ? $parts[0] : $parts[1];
		} else {
			$id = $url;
		}
		return $is_url ? (AUTHORSURE_GOOGLEPLUS_URL . $id . '/') : $id;
	}	
	
	public static function get_publisher() {
		return self::get_option('publisher_rel');	
	}
	
	public static function get_archive_option($term_id, $key) {
		if (!$term_id || !$key) return false;
		$options = self::get_term_options();
		$arc_options= (is_array($options) && array_key_exists($term_id, $options)) ? $options[$term_id] : array();
		return is_array($arc_options) && array_key_exists($key, $arc_options) ? $arc_options[$key] : false;
	}
	
	public static function save_options ($options) {
		$result = update_option('authorsure_options',$options);
		self::get_options(false); //update cache
		return $result;
	}

	public static function save_archive_option ($term_id, $values) {
		if (! $term_id || ! $values || !is_array($values) || !is_numeric($term_id)) return false;
	    $term_options = self::get_term_options(false); //get the option to update	    
		$term_options[$term_id] = $values; //update it
		$result = update_option('authorsure_term_options', $term_options); //save to the database 
		self::get_term_options(false); //update cache
		return $result;
	}

	private static function allow_img($allowedtags) {
		if ( !array_key_exists('img', $allowedtags) 
		|| (array_key_exists('img',$allowedtags) && !array_key_exists('src', $allowedtags['img']))) {
			$allowedtags['img']['src'] = array ();
			$allowedtags['img']['title'] = array ();
			$allowedtags['img']['alt'] = array ();
			$allowedtags['img']['height'] = array ();			
			$allowedtags['img']['width'] = array ();	
		}
		return $allowedtags;
	}
	
	private static function allow_arel($allowedtags) {
		if ( !array_key_exists('a', $allowedtags) 
		|| (array_key_exists('a',$allowedtags) && !array_key_exists('rel', $allowedtags['a'])))
			$allowedtags['a']['rel'] = array ();
		return $allowedtags;
	}	
	
	public static function wordpress_allow_img() {
		global $allowedtags;
		$allowedtags = self::allow_img($allowedtags);
	}

	public static function wordpress_allow_arel() {
		global $allowedtags;
		$allowedtags = self::allow_arel($allowedtags);
	}

	public function genesis_allow_arel($allowedtags) {
		return self::allow_arel($allowedtags);
	}	

	public static function genesis_allow_img($allowedtags) {
		return self::allow_img($allowedtags);
	}

	public static function is_author($user) {
		$cap = 'edit_posts';
		switch (self::get_option('minimum_role_for_authorship')) {
			case 'administrator' : $cap = 'manage_options'; break;	
			case 'editor' : $cap = 'edit_others_posts'; break;	
			case 'author' : $cap = 'publish_posts'; break;	
		}	
		return user_can($user, $cap);
	}

	public static function get_icon($profile, $label, $size ) {
		return sprintf('<img src="%1$s" alt="%2$s" />%3$s',
			AUTHORSURE_PLUGIN_URL.'images/'.$size.'px/'.$profile.'.png', $profile, $label);
	}

	public static function add_contactmethods( $contactmethods, $label_index=0, $size=0) {
		if ($size==0) $size = self::get_option('author_profiles_image_size');
		$profiles = self::get_pro_options();
		if (is_array($profiles)) 
			foreach ($profiles as $profile => $labels) 
				//if (!array_key_exists($profile,$contactmethods)) 
					$contactmethods[$profile] = self::get_icon($profile, $label_index<0 ? '' : ('&nbsp;'.$labels[$label_index]),$size);
		return $contactmethods;
	}

	public static function add_contactmethods_nolabels( $contactmethods) {
		return self::add_contactmethods( $contactmethods, -1);
	}	

	public static function add_contactmethods_profile( $contactmethods) {
		return self::add_contactmethods( $contactmethods, 1, 16);
	}	

}
