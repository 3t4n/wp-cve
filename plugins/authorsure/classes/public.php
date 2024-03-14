<?php
class authorsure {

 	private static $authorsure_count = 0;
	private static $author = false;
	private static $intro = '';

	public static function init() {		
		add_shortcode('authorsure_authors', array(__CLASS__,'list_authors'));	
		add_shortcode('authorsure_author_box', array(__CLASS__,'show_author_box'));
		add_shortcode('authorsure_author_profiles', array(__CLASS__,'show_author_profiles'));
		add_action('wp', array(__CLASS__,'prepare')); 
	}	

	public static function prepare() {		
		self::add_head(); //additions to head section
		self::add_single_author(); //adds author rel=author to posts and pages
		self::add_archive_author(); //add author rel=author link to archives
		self::add_author_bio(); //add author rel=me links to author page
		self::tweak_if_genesis(); //tweak settings if a Genesis theme	
		self::avatar_cache_settings(); //maybe stop avatar being cached	
	}
	
	private static function get_author_link($id) {
		return '<a rel="author" href="'. get_author_posts_url($id).'" class="authorsure-author-link">'.get_the_author_meta('display_name', $id ).'</a>';
	}

	private static function avatar_cache_settings() {
		if (authorsure_options::get_option('donotcache_avatar'))
			add_filter( 'jetpack_photon_skip_image', array(__CLASS__,'donotcache_avatar'), 10, 3 );
	}

	private static function get_avatar($id) {
		return get_avatar( get_the_author_meta('email', $id), authorsure_options::get_option('box_gravatar_size') );
	}

	private static function about_author($id) {
		if ($about = authorsure_options::get_option('box_about')) $about .= '&nbsp;';
		if (!($title_tag = authorsure_options::get_option('box_title_tag'))) $title_tag = 'h4';
		return sprintf( '<%3$s>%1$s%2$s</%3$s>', $about, self::get_author_link($id), $title_tag);
	}

	private static function get_title($id) {
		if  (authorsure_options::get_option('author_show_title')) {
			$author_name = get_the_author_meta('display_name',$id);
			if ($prefix = authorsure_options::get_option('author_about'))
				$title = sprintf('%1$s %2$s',$prefix, $author_name);
			else
				$title = $author_name;
			return sprintf( '<h2 class="authorsure-author-title">%1$s</h2>',$title);				
		} else {
			return '';
		}
	}

	private static function preserve_bio_links($user, $role) {
		$cap = 'edit_posts';
		switch ($role) {
			case 'administrator' : $cap = 'manage_options'; break;	
			case 'editor' : $cap = 'edit_others_posts'; break;	
			case 'author' : $cap = 'publish_posts'; break;	
		}	
		return user_can($user, $cap);
	}

	private static function get_bio($id) {
	    $nofollow = authorsure_options::get_option('author_bio_nofollow_links'); //setting for author page
	    $strip = ! self::preserve_bio_links($id, authorsure_options::get_option('minimum_role_for_bio_links'));
		switch (authorsure_options::get_option('author_bio')) {
			case 'summary':  return self::get_summary_bio($id, $nofollow, $strip); break;
			case 'extended':  return self::get_extended_bio($id, $nofollow, $strip); break;
		}
		return '';
	}	
	
	private static function get_summary_bio($id, $nofollow, $strip ) {
		return self::get_filtered_bio($id,'description', $nofollow, $strip);
	}	

	private static function get_extended_bio($id, $nofollow, $strip) { //return extended bio if present else return standard bio
		if ($extended_bio =  self::get_filtered_bio($id, authorsure_options::get_extended_bio_key(), $nofollow, $strip))
			return $extended_bio;
		else
			return self::get_summary_bio($id, $nofollow, $strip) ;
	}
	
	private static function get_filtered_bio($id, $key, $nofollow, $strip) {
		return self::filter_links(wpautop( get_the_author_meta($key, $id) ), $nofollow, $strip);
	}	

	private static function get_box($author) {
	    $nofollow = authorsure_options::get_option('box_nofollow_links'); //setting for author box
	    $strip = ! self::preserve_bio_links($author, authorsure_options::get_option('minimum_role_for_box_links'));	
	    $profiles = authorsure_options::get_option('box_show_profiles') ? self::get_profiles($author,true) : ''; //profile icons only 
		return sprintf('<div class="authorsure-author-box">%1$s%2$s%3$s%4$s</div><div class="clear"></div>',
			self::get_avatar($author->ID), self::about_author($author->ID), self::get_summary_bio($author->ID, $nofollow, $strip ), $profiles );
	}
	
	private static function get_footnote($id, $last_updated_time, $last_updated_date) {	
		$link = self::get_author_link($id) ;
		if ( empty($link)) return ''; 
		$author = sprintf( '<span style="float:none" class="author vcard"><span class="fn">%1$s</span></span>', $link);
		$updated_at = authorsure_options::get_option('footnote_show_updated_date') ?
			sprintf( ' %1$s <time itemprop="dateModified" datetime="%2$s">%3$s</time>',authorsure_options::get_option('footnote_last_updated_at'),$last_updated_time, $last_updated_date) : '';
		$alignment = authorsure_options::get_option('footnote_alignment') ;

		return sprintf( '<p id="authorsure-last-updated" class="updated %5$s" itemscope="itemscope" itemtype="http://schema.org/WebPage" itemid="%1$s">%2$s %3$s%4$s.</p>', 
			get_permalink(), authorsure_options::get_option('footnote_last_updated_by'), $author, $updated_at, $alignment);
	}
	
	private static function skype_me ($name, $img, $nolabels) {
		if ($pos = strpos($name,'/status')) $name = substr($name,0,$pos) ;
		if (($nolabels==false) && ($pos > 0)) {
			wp_enqueue_script('skypeCheck', 'http://download.skype.com/share/skypebuttons/js/skypeCheck.js',array(),'v2.2',true);
			$img .= sprintf('&nbsp;<img src="http://mystatus.skype.com/bigclassic/%1$s" style="border: none;" width="100" height="24" alt="My status" />',$name);
		}		
		return sprintf('<li style="list-style-type: none;"><a href="skype:%1$s?call" title="Contact me on Skype">%2$s</a></li>', $name, $img);		
	}

	private static function contact_me_link($href, $channel, $desc, $icons_only, $target) {
		return sprintf('<li style="list-style-type: none;"><a href="%1$s" %4$s%5$stitle="Follow me on %2$s">%3$s</a></li>',
				$href, ucwords($channel), $desc, $icons_only ? '' : 'rel="me" ', $target ? ' target="_blank"' : '');
	}
	
	private static function get_profiles($user, $icons_only = false) {
		$s='';
		$profiles = authorsure_options::get_pro_options();
		$no_labels = $icons_only ? true : authorsure_options::get_option('author_profiles_no_labels'); //fetch option if not set by param
		$target = authorsure_options::get_option('author_profiles_new_window');
		add_filter('user_contactmethods', array('authorsure_options',$no_labels ? 'add_contactmethods_nolabels' : 'add_contactmethods'),10,1);
		foreach (_wp_get_user_contactmethods( $user ) as $name => $desc) {
			if (array_key_exists($name,$profiles) && !empty($user->$name))
				if ('skype'==$name)
					$s .= self::skype_me($user->$name,$desc,$no_labels);
				elseif (('twitter'==$name) && is_null(parse_url($user->$name,PHP_URL_HOST))) //not a URL
					$s .= self::contact_me_link('http://twitter.com/'.$user->$name.'/', $name, $desc, $icons_only, $target);
				else
					$s .= self::contact_me_link($user->$name, $name, $desc, $icons_only, $target);
		}	
		if (empty($s))
			return '';
		elseif ($no_labels)
			return sprintf('<ul class="single-line"><span>%1$s</span>%2$s</ul>',authorsure_options::get_option('author_find_more'), $s);
		else
			return sprintf('<p>%1$s</p><ul>%2$s</ul>',authorsure_options::get_option('author_find_more'), $s);
	}
	
	private static function get_archive_term_id() {
		global $wp_query;
		if (is_archive() && ($term = $wp_query->get_queried_object()))
			return $term->term_id;
		else
			return false;
	}

	private static function get_archive_author() {
		if (($author = authorsure_options::get_archive_option(self::get_archive_term_id(), 'author'))
		&& ($author > 0))
			return $author; //return author explicitly chosen for this archive
		else
			return authorsure_options::get_option('archive_author_id'); //return the default author for archives
	}
	
	private static function get_archive_intro() {
		if ($intro = authorsure_options::get_archive_option(self::get_archive_term_id(),'intro'))
			return sprintf ('<div class="authorsure-archive-intro">%1$s</div>',stripslashes($intro));
		else
			return '';
	}
	
	private static function get_last_update() {
		global $wp_query;
		$args = array_merge ($wp_query->query, array('posts_per_page' => 1, 'orderby' => 'modified', 'order' => 'DESC'));
		$posts = get_posts($args);	//merge with existing query but tweak to get the last modified post for this archive
		if( is_array($posts) && (count($posts) > 0)) {
			$t = get_post_modified_time('c',false, $posts[0]); //express as time 
			$d = apply_filters('get_the_modified_date', 
				get_post_modified_time(get_option('date_format'),false, $posts[0]), ''); //observe formats and date filters
			return array('datetime' => $t, 'date' => $d);
		}
		return false;
	}
		
	private static function show_author_profile($user) {
		if 	( authorsure_options::is_author($user)) {
			if ($archive_heading = authorsure_options::get_option('author_archive_heading'))
				$subtitle = sprintf('<p id="authorsure-posts-heading">%1$s</p>',$archive_heading);
			else 
				$subtitle = '';
			$title = self::get_title($user->ID);
			if (authorsure_options::get_option('author_show_avatar')) $title .= self::get_avatar($user->ID);

			echo sprintf('<div id="authorsure-author-profile">%1$s%2$s%3$s<div class="clear"></div>%4$s</div>',
				$title, self::get_bio($user->ID), self::get_profiles($user), $subtitle);
		}
	}

	//obtain user fron parameter or context
	private static function derive_user($attr) {
		if (is_array($attr) && array_key_exists('id',$attr)) {
			$id= $attr['id'];
		} else { //try looking in the post
			global $post;
			$id = ($post && property_exists($post,'post_author') && isset($post->post_author)) ? $post->post_author : 0;
		}
		if ($id > 0)
			$user_obj = new WP_User($id);
		else //try the URL
			$user_obj = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
		
		return ($user_obj && ($user_obj->ID > 0)) ? $user_obj : false	;
	}

	private static function get_home_author() {
		return authorsure_options::get_option('home_author');
	}
	
	private static function get_home_author_rel() {
		$method = 'googleplus';
		$user_id = self::get_home_author();
		if ($user_id) {
			add_filter('user_contactmethods', array('authorsure_options','add_contactmethods_nolabels'),10,1);
 			if (($user = new WP_User($user_id))
			&& $user->has_prop($method)
			&& ($url = $user->get($method))) 
				return $url;
		}
		return false;
	}

    private static function get_author_link_eligibility($post_author, $post_id, $post_type) {
		if (is_front_page() && authorsure_options::get_option('hide_box_on_front_page')) return false;

		if (is_singular() && authorsure_options::is_author($post_author) )
			switch ($post_type) {
				case 'post':  
					return ! get_post_meta($post_id, authorsure_options::get_hide_author_box_key(), true);
				case 'page': { 
					if (authorsure_options::get_option('hide_box_on_pages'))
						return get_post_meta($post_id, authorsure_options::get_show_author_box_key(), true);
					else
						return ! get_post_meta($post_id, authorsure_options::get_hide_author_box_key(), true);
				}
				default: 
					$key = strtolower($post_type).'s';
					if (($custom = authorsure_options::get_option('show_box_on_custom'))
					&& is_array($custom) && array_key_exists($key,$custom) && $custom[$key])
						return ! get_post_meta($post_id, authorsure_options::get_hide_author_box_key(), true);
					else
						return get_post_meta($post_id, authorsure_options::get_show_author_box_key(), true);
			}
		else
			return false; //not an individual page or not an author
    }

	public static function get_blog_author_link($id) {
		return '<a rel="me" href="'. get_author_posts_url($id).'">'.get_bloginfo().'</a>';
	}

	//link (rel="author") the post/post to the author page in a post footnote 
	public static function append_post_author_footnote($content) {
		global $post;
		if (self::get_author_link_eligibility($post->post_author, $post->ID, $post->post_type) ) {
			$footnote = self::get_footnote($post->post_author,get_post_modified_time('c'),get_the_modified_date());	
			if (authorsure_options::get_option('footnote_prepend_entry'))
				$content = $footnote . $content;	
			else
				$content = $content . $footnote;
		}
		return $content;
	}

	//link (rel="author") the post/post to the author page in an author box at the foot of the post
	public static function append_post_author_box($content) {
		global $post;
		if (($user = self::derive_user(array('id' => $post->post_author))) 
		&& self::get_author_link_eligibility($post->post_author, $post->ID, $post->post_type) ) {
			if (authorsure_options::get_option('box_prepend_entry'))
				$content = self::get_box($user) . $content;	
			else
				$content = $content . self::get_box($user);	
		}		
		return $content;
	}

	//add primary author contact links to the about page
	public static function append_primary_author($content) {
		global $post;
		$about_page = authorsure_options::get_option('menu_about_page');
		$primary = authorsure_options::get_option('menu_primary_author');
		if ($primary && $about_page && is_page($about_page)) {
			$author = new WP_User($primary);
			$content .=  sprintf('<div id="authorsure-author-profile">%1$s</div>', self::get_profiles($author));
		}
		return $content;
	}

	//add a header to author page to link to Google (rel="me")
	public static function insert_author_bio() {
		global $post;
		if (is_author() && !is_feed()) {  //we're on an author page and it is not a feed
			$author_hook_index = authorsure_options::get_author_page_hook_index(); 
	    	self::$authorsure_count += 1;
	    	if ($author_hook_index == self::$authorsure_count)  { //only add the bio once on the specified instance
				$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
				self::show_author_profile($curauth);
			}
		}
	}

	//link the home page and possibly the archive pages to GooglePlus Page (rel="publisher")
	public static function add_publisher_rel() {
		if (($publisher = authorsure_options::get_publisher())
		&& (is_front_page() || (is_archive() && ('publisher'==authorsure_options::get_option('archive_link'))))) 
			printf ('<link rel="publisher" href="%1$s" />', AUTHORSURE_GOOGLEPLUS_URL.$publisher);
	}

	//link the home page with (rel="author")
	public static function add_author_rel() {
		if ($url = self::get_home_author_rel()) printf ('<link rel="author" href="%1$s" />', $url);
	}
	
	public static function add_head() {
		global $post;
		if (authorsure_options::get_publisher()) add_action('wp_head', array(__CLASS__,'add_publisher_rel')) ; //add publisher link		
		if (is_front_page() && self::get_home_author()) add_action('wp_head', array(__CLASS__,'add_author_rel')) ; //add author link	
		$author_rel = authorsure_options::get_option('author_rel');
		$about_page = authorsure_options::get_option('menu_about_page');	
		if (('box'==$author_rel) 
		|| ('footnote'==$author_rel) 
		|| is_author() 
		|| ($about_page && is_page($about_page))
		|| ((is_page() || is_single()) && ($id = get_queried_object_id()) && get_post_meta($id,authorsure_options::get_include_css_key()))) { 
			//include css for author boxes and on author page
    		add_action('wp_enqueue_scripts', array(__CLASS__,'enqueue_css')) ; //add CSS
		}
	}

	public static function enqueue_css() {	
    	wp_enqueue_style( AUTHORSURE, AUTHORSURE_PLUGIN_URL.'styles/public.css',array(),AUTHORSURE_VERSION);
	}
	
    //** filter for use at the get_the_author_description hook **/
	public static function append_profiles($content, $user_id = false) {
		$args = array();
		if ($user_id && ($user_id > 0)) $args['id'] = $user_id;

		if 	(is_author() //only run on author pages
		&&	($user = self::derive_user($args))
		&&	authorsure_options::is_author($user))
			$content = sprintf('%1$s<div id="authorsure-author-profile">%2$s</div>', $content, self::get_profiles($user));
		return $content;
	}	

	public static function add_single_author() {	
		if (is_singular()) {	
			//additions to posts and pages
			$author_rel = authorsure_options::get_option('author_rel');
			switch($author_rel) {
				case 'menu': add_filter('the_content', array(__CLASS__,'append_primary_author')); break;
				case 'footnote': add_filter('the_content', array(__CLASS__,'append_post_author_footnote')); break;
				case 'box': add_filter('the_content', array(__CLASS__,'append_post_author_box')); break;
				default: 	
			}
			//additions to author pages 
			if ($author_rel != 'menu') 
				if (authorsure_options::get_option('author_page_filter_bio')) 
					add_filter('get_the_author_description', array(__CLASS__,'append_profiles'),10,2); //append profiles to existing bio
				else
					add_action(authorsure_options::get_author_page_hook(), array(__CLASS__,'insert_author_bio')); //add bio to author page
		}
	}

    public static function add_archive_author () {
		if (is_archive() && ! (is_front_page() || is_author() || is_paged()) ) {
			if (authorsure_options::get_option('archive_intro_enabled') && (self::$intro = self::get_archive_intro())) 
				add_action('loop_start', array(__CLASS__, 'show_archive_intro'));
				
			if (($archive_hook = authorsure_options::get_archive_hook())
			&& (self::$author = self::get_archive_author())) {  //get the archive author for later
				add_action($archive_hook, array(__CLASS__, 'show_archive_primary_author')); //add archive
			}
		}
    }  
         
    public static function filter_links( $content, $nofollow = false, $strip = false) {
    	if ($nofollow || $strip)
			return preg_replace_callback( '/<a([^>]*)>(.*?)<\/a[^>]*>/is', 
				array( AUTHORSURE, $strip ? 'make_links_text_only' : 'nofollow_link' ), $content ) ;
		else
			return $content ;
    }		

    public static function nofollow_link($matches) { //make link nofollow
		$attrs = shortcode_parse_atts( stripslashes ($matches[ 1 ]) );
		$atts='';
		foreach ( $attrs AS $key => $value ) {
			$key = strtolower($key);
			if ('rel' != $key) $atts .= sprintf('%1$s="%2$s" ', $key, $value);
		}
		$atts = substr( $atts, 0, -1 );
		return sprintf('<a rel="nofollow" %1$s>%2$s</a>', $atts, $matches[ 2 ]);
	}

    public static function make_links_text_only($matches) { //return only text of link
		return $matches[ 2 ];
	}
	
	public static function donotcache_avatar($donotcache, $src, $tag) {
        if ( strpos($src, 'gravatar') !== FALSE ) $donotcache = true;  //do not cache avatars
        return $donotcache;
	}

	public static function add_author_bio() {
		//additions to author pages 
		$author_rel = authorsure_options::get_option('author_rel');
		if ($author_rel != 'menu') 
			if (authorsure_options::get_option('author_page_filter_bio')) 
				add_filter('get_the_author_description', array(__CLASS__,'append_profiles'),10,2); //append profiles to existing bio
			else
				add_action(authorsure_options::get_author_page_hook(), array(__CLASS__,'insert_author_bio')); //add bio to author page	
	}
	
	//add a top section to archive page 
	public static function show_archive_intro() {
		echo self::$intro;
	}
	
	//add a section to archive page with rel=author to primary author
	public static function show_archive_primary_author( ) {
		if (self::$authorsure_count == 0) { 
	    	self::$authorsure_count += 1;
	    	if($last_update = self::get_last_update())
	    		echo self::get_footnote(self::$author,$last_update['datetime'], $last_update['date']);	
		}
	}

	public static function strip_rel_author ($content, $args) { 
		return str_replace(' rel="author"','',$content); 
	}
	
	public static function tweak_if_genesis() {
		if ( basename( TEMPLATEPATH ) == 'genesis') { //disable Genesis authorship as AuthorSure gives more granular control
			add_filter( 'genesis_formatting_allowedtags', array(__CLASS__,'genesis_allow_arel') );
			remove_filter( 'user_contactmethods', 'genesis_user_contactmethods' ); //avoid collisions  
			remove_action( 'wp_head', 'genesis_rel_author' );  //kill off link in the head  
			if (authorsure_options::get_option('author_rel') != 'byline') //maybe kill the rel="author" link in the byline
				add_filter('genesis_post_author_posts_link_shortcode', array(__CLASS__, 'strip_rel_author'),20,2); 
		}	
	}

	//shortcode for adding author profiles into a page
	public static function show_author_profiles($attr) {
		if ($user = self::derive_user($attr)) 
			return self::get_profiles($user) ;
		else
			return '';	
	}

	//shortcode for adding author box into a page
	public static function show_author_box($attr) {
		if ($user = self::derive_user($attr)) 
			return self::get_box($user) ;
		else
			return '';	
	}
	
	//shortcode for listing all authors short bios on a page
	public static function list_authors($attr = array()) {
  		$params = shortcode_atts( array('orderby' => 'display_name'), $attr ); //apply plugin defaults  	
		$args = $params['orderby'] == 'custom' ?
				array('meta_key' => authorsure_options::get_author_index_key(), 'orderby' => 'meta_value') :
				array('who' => 'authors', 'orderby' => $params['orderby']);
		$authors = get_users($args);
		$s='';
		foreach ($authors as $author) {
			if (get_user_option(authorsure_options::get_show_author_key(), $author->ID))		
				$s .= self::get_box($author);
		}
		return $s;
	}

}
