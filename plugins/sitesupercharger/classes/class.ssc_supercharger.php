<?php
if ( ! defined( 'ABSPATH' ) ) header("location:/");
require_once( ABSPATH . 'wp-admin/includes/image.php' );

class Ssc_SuperCharger {

	protected static $path_to_storage = "http://files.sitesupercharger.com/";	
	protected static $path_to_app = "https://app.sitesupercharger.com/";
	protected static $maxExecutionTime = 60;
	protected static $readConfigInterval = 300; // every 5 minutes
	protected static $processQueueInterval = 60; // every minute
	protected static $pageInfo, $guid, $slug, $phoneSwap;
	protected static $maxImageSize = 1024;
	protected static $ssc_plugin_version = '5.3.1';



	/**
	 * Sets up all the hooks needed.
	 *
	 * @since 1.0.0
	 *
	 * @global $wpdb
	 *
	 * @param  none
	 * @return none.
	 */
	public static function init(){
		set_time_limit(120); // Still only expect a limit of 60s
		global $wpdb;
		
		if(!headers_sent() && !session_id()) session_start();
		
		self::$guid = get_option('ssc_guid');
		
		add_action('wp_head',array('Ssc_SuperCharger', 'insertMetaTags'));
		
		self::initPhoneSwap();
		
		add_action( 'admin_menu', array('Ssc_SuperCharger', 'sscAdminMenu') );
		add_shortcode( 'sitesupercharger', array('Ssc_SuperCharger', 'showPage') );
		add_filter( 'the_content', array('Ssc_SuperCharger', 'interlinking'), 9999 );
		add_filter( 'post_link_category', array('Ssc_SuperCharger', 'primaryCategoryPermalink'), 10, 3 );
		
		add_action( 'wp_ajax_adminUpdate', array( 'Ssc_SuperCharger', 'adminUpdate' ) );
		add_action( 'wp_ajax_adminUpdateCompleted', array( 'Ssc_SuperCharger', 'adminUpdateCompleted' ) );
		add_action( 'wp_ajax_nopriv_readConfig', array( 'Ssc_SuperCharger', 'readConfig' ) );
		add_action( 'wp_ajax_readConfig', array( 'Ssc_SuperCharger', 'readConfig' ) );
		add_action( 'wp_ajax_nopriv_processQueue', array( 'Ssc_SuperCharger', 'processQueue' ) );
		add_action( 'wp_ajax_processQueue', array( 'Ssc_SuperCharger', 'processQueue' ) );
		add_action( 'wp_ajax_nopriv_processGroup', array( 'Ssc_SuperCharger', 'processGroup' ) );
		add_action( 'wp_ajax_processGroup', array( 'Ssc_SuperCharger', 'processGroup' ) );
		add_action( 'wp_ajax_nopriv_processImages', array( 'Ssc_SuperCharger', 'processImages' ) );
		add_action( 'wp_ajax_processImages', array( 'Ssc_SuperCharger', 'processImages' ) );
		add_action( 'wp_ajax_nopriv_processCompleted', array( 'Ssc_SuperCharger', 'processCompleted' ) );
		add_action( 'wp_ajax_processCompleted', array( 'Ssc_SuperCharger', 'processCompleted' ) );
		add_action( 'wp_ajax_adminRebuild', array( 'Ssc_SuperCharger', 'adminRebuild' ) );
		
		// get post id from url to determine if this is a SSC page
		if ( (! empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') ||
			(! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ||
			(! empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ) {
			$server_request_scheme = 'https';
		} else {
			$server_request_scheme = 'http';
		}
		$thisUrl = sanitize_url($server_request_scheme."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
		$post_id = url_to_postid($thisUrl);

		if(get_option( 'ssc_404_inactive_url' )){
			$isParent = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = '_ssc_parent_page'", $post_id));
			if($isParent){
				wp_delete_post($post_id);
			}
		}
		if(get_option( 'ssc_301_redirect_url' )){

			// figure out if the page we're on is a parent page or an SEO landing page
			$isParent = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = '_ssc_parent_page'", $post_id));
			if($isParent){
				//redirect
				header("HTTP/1.1 301 Moved Permanently");
				header("Location: " . get_option( 'ssc_301_redirect_url' ));
				exit();
			}

			$pagemeta = $wpdb->get_row("SELECT * FROM $wpdb->postmeta JOIN $wpdb->posts ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE $wpdb->postmeta.meta_key = '_ssc_guid' AND $wpdb->posts.ID = '".($post_id)."' AND $wpdb->posts.post_type IN ('page','post') AND $wpdb->posts.post_status = 'publish'");
			if(!is_null($pagemeta)){
				//this is a SSC page
				switch($pagemeta->post_type){
					case "page":
						//redirect
						header("HTTP/1.1 301 Moved Permanently");
						header("Location: " . get_option( 'ssc_301_redirect_url' ));
						break;
					case "post":
						break;
				}
			}
		}

		if(!get_site_transient('ssc_readConfig_lock') && !get_site_transient('ssc_processQueue_lock')){
			set_site_transient('ssc_readConfig_lock', time(), self::$readConfigInterval);
			set_site_transient('ssc_processQueue_lock', time(), self::$processQueueInterval);
			
			self::asyncThread( 'readConfig' );
		}else{
			if(get_site_transient('ssc_readConfig_lock')  &&  (intval(get_site_transient('ssc_readConfig_lock')) +  self::$readConfigInterval < time())){
				self::logEvent("Manually deleting 'ssc_readConfig_lock' because " . (intval(get_site_transient('ssc_readConfig_lock')) + self::$readConfigInterval) . " < " . time());
				delete_site_transient('ssc_readConfig_lock');
			}
		}


		if(!get_site_transient('ssc_processQueue_lock')){
			set_site_transient('ssc_processQueue_lock', time(), self::$processQueueInterval);
			
			self::logEvent( "== starting processQueue from init" );
			self::asyncThread( 'processQueue', array(0) );
		}
		
	}



	/**
	 * Replaces the shortcode [sitesupercharger] with that page's content.
	 *
	 * @since 1.0.0
	 *
	 * @global $wpdb
	 *
	 * @param  none
	 * @return string HTML output for page.
	 */
	public static function showPage(){
		global $wpdb;

		

		$page_guid = "";

		if(self::$slug != basename(get_permalink())){
			self::$slug = basename(get_permalink());

			$pagemeta = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->postmeta JOIN $wpdb->posts ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE $wpdb->postmeta.meta_key = '_ssc_guid' AND $wpdb->posts.post_name = '%s' AND $wpdb->posts.post_type IN ('page','post') AND $wpdb->posts.post_status = 'publish'", self::$slug));
			
			if(!is_null($pagemeta)){
				$page_guid = $pagemeta->meta_value;

				self::$pageInfo = self::loadXML(self::$path_to_storage . self::$guid . "/" . $page_guid . ".xml");

				self::$pageInfo->{'post-type'} = "post";
			}
		}


		// use the slug to figure out what page we're on
		$post_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = '%s' AND post_status = 'publish' AND post_type IN ('post','page')", self::$slug));


		// figure out if the page we're on is a parent page or an SEO landing page
		$isParent = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = '_ssc_parent_page'", $post_id));
		if( $isParent ){
			// if it's a parent page the content will just be a list of links child pages
			$children = $wpdb->get_results($wpdb->prepare("SELECT post_title, guid FROM $wpdb->posts WHERE post_type = 'page' AND post_status = 'publish' AND post_parent = %d", $post_id));

			

			$landingHTML = self::loadXML(self::$path_to_storage . self::$guid . "/" . $page_guid . "directory.xml");

			// echo '<pre>';
			// var_dump(self::$path_to_storage . self::$guid . "/" . $page_guid . "directory.xml");
			// echo '</pre>';


			if($landingHTML != NULL){ // if the directory.xml file exists then display the HTML from it
				$output = (string) $landingHTML->{'blog-body'}[0];
			}else{ // if directory.xml doesn't exist for this site then display a list of pages
				$output = "<ul>";
				foreach($children AS $child){
					$output .= "<li><a href='".$child->guid."' title='".$child->post_title."'>".$child->post_title."</a></li>";
				}
				$output .= "</ul>";
			}

			
		}else{
			// $pageInfo is initiated in insertMetaTags()
			switch(self::$pageInfo->{'post-type'}){
				case "page": if(get_option( 'ssc_301_redirect_url' )){header("Location: " . get_option( 'ssc_301_redirect_url' )); exit();} else { $content = str_replace("<<","&lt;&lt;",self::$pageInfo->{'page-body'}); break;}
				case "post": $content = self::interlinking( self::$pageInfo->{'video-embed-code'}." ".self::$pageInfo->{'blog-body'} ); break;

			}

			$content = self::replaceImages($content);

			$output = "<div id=\"page_content\">".$content."</div>";
		}

		return $output; 
	}
	
	
	
	/**
	 * Runs at the wp_head hook to add custom meta tags for the page.
	 *
	 * @since 1.0.0
	 *
	 * @global $wpdb
	 *
	 * @param  none
	 * @return none
	 */
	public static function insertMetaTags() {
		global $wpdb;

		self::$slug = basename(get_permalink());

		$pagemeta = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->postmeta JOIN $wpdb->posts ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE $wpdb->postmeta.meta_key = '_ssc_guid' AND $wpdb->posts.post_name = '%s' AND $wpdb->posts.post_type IN ('page','post') AND $wpdb->posts.post_status = 'publish'", self::$slug));

		if(!is_null($pagemeta)){
			switch($pagemeta->post_type){
				case "page":
					$page_guid = $pagemeta->meta_value;
					$output = "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=Edge\" />";
					$output .= "<link href=\"\" rel=\"shortcut icon\" />";
					$output .= "<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\" />";

					self::$pageInfo = self::loadXML(self::$path_to_storage . self::$guid . "/" . $page_guid . ".xml");

					self::$pageInfo->{'post-type'} = "page";

					$output .= "<link rel=\"canonical\" href=\"".(self::$pageInfo->{'page-meta-link'})."\" />";
					$output .= "<meta name=\"keywords\" content=\"".(self::$pageInfo->{'page-meta-keywords'})."\" />";
					$output .= "<meta property=\"description\" content=\"".(self::$pageInfo->{'page-meta-description'})."\" />";
					$output .= "<meta property=\"og:locale\" content=\"".(self::$pageInfo->{'page-meta-og-locale'})."\" />";
					$output .= "<meta property=\"og:type\" content=\"".(self::$pageInfo->{'page-meta-og-type'})."\" />";
					$output .= "<meta property=\"og:title\" content=\"".(self::$pageInfo->{'page-meta-og-title'})."\" />";
					$output .= "<meta property=\"og:description\" content=\"".(self::$pageInfo->{'page-meta-og-description'})."\" />";
					$output .= "<meta property=\"og:url\" content=\"".(self::$pageInfo->{'page-meta-og-url'})."\" />";
					$output .= "<meta property=\"og:site_name\" content=\"".(self::$pageInfo->{'page-meta-og-sitename'})."\" />";
					$output .= "<meta property=\"og:image\" content=\"".(self::$pageInfo->{'page-meta-og-image'})."\" />";
				break;

				case "post":
					$page_guid = $pagemeta->meta_value;

					self::$pageInfo = self::loadXML(self::$path_to_storage.self::$guid."/".$page_guid.".xml");

					self::$pageInfo->{'post-type'} = "post";

					$output = "<meta property=\"og:description\" content=\"".(self::$pageInfo->{'meta-description'})."\" />";
					$output .= "<meta property=\"og:title\" content=\"".(self::$pageInfo->{'title'})."\" />";
					$og_image = self::getFirstImage(self::$pageInfo->{'blog-body'});
					if($og_image !== 0){
						$output .= "<meta property=\"og:image\" content=\"".$og_image."\" />";
					}
				break;
			}
			
			$allowed_html = array(
				'meta' => array(
					'http-equiv'  => array(),
					'content'    => array(),
					'name'  => array(),
					'property' => array()
				 ),
				 'link' => array(
					'rel' => array(),
					'href' => array()
				 )
			);

			echo wp_kses($output, $allowed_html);
		}
	}



	/**
	 * Runs at the the_content hook to add <a> tags to keywords that link to random Landing Pages.
	 *
	 * @since 2.0.0
	 *
	 * @global $post
	 *
	 * @param string $content The content of the page or post.
	 * @return string The content after the <a> tags have been added.
	 */
	public static function interlinking( $content ) {
		global $post;

		//This is code for phone number swap, has nothing to do with interlinking
		if(self::$phoneSwap) $content .= "<div id='ssc_phone_original' style='display:none;'>".get_option( 'ssc_main_phone' )."</div><div id='ssc_phone_replace' style='display:none;'>".get_option( 'ssc_alt_phone' )."</div>";
		//Done with phone swap code
		
		$keywords = get_option( 'ssc_interlinking_keywords' );
		$landingLinks = get_option( 'ssc_interlinking_links' );

		if(!is_array($landingLinks) OR !is_array($keywords)) return $content;

		//find a random starting index in each array of landing page keywords based on the page slug
		$slug = get_post( $post )->post_name;

		foreach($landingLinks AS $key => $value){
			$i = ((int)filter_var(substr(md5($slug),0,3),FILTER_SANITIZE_NUMBER_INT) % count($value));
			$index[$key] = $i;
		}

		$modifiedContent = $content;
		$originalLen = strlen($modifiedContent);

		$regex = "/";
		foreach($keywords AS $keyword){
			$regex .= "\b".str_replace("/","\/",$keyword)."\b|";
		}

		$regex = substr($regex,0,-1);
		$regex .= "/i";

		// remove any extra whitespaces
		$regex = preg_replace('/\s+/', ' ',$regex);

		preg_match_all($regex,$content,$matches,PREG_OFFSET_CAPTURE);
		foreach($matches[0] AS $match){
			$linkIt = 1;

			//find last <A> tag before the keyword
			$a_open = strrpos(substr($content, 0, $match[1]), "<a");
			if($a_open !== FALSE){
				//find first </A> tag after the previously found <A> tag
				$a_close = strpos($content, "/a>", $a_open);
				//if the keyword is between those two tags then don't link the keyword (it's already a link)
				if($match[1] > $a_open AND $match[1] < $a_close) $linkIt = 0;
			}
			if($linkIt){
				//find last < before the keyword
				$tag_open = strrpos(substr($content, 0, $match[1]), "<");
				if($tag_open !== FALSE){
					//find first > tag after the previously found <
					$tag_close = strpos($content, ">", $tag_open);
					//if the keyword is between the < and > then don't link the keyword (it's inside a tag)
					if($match[1] > $tag_open AND $match[1] < $tag_close) $linkIt = 0;
				}
			}
			if($linkIt){
				$match_keyword = strtolower($match[0]);
				$index[$match_keyword]++;
				if(!is_countable($landingLinks[$match_keyword]) || $index[$match_keyword] >= count($landingLinks[$match_keyword])) $index[$match_keyword] = 0;
				$url = $landingLinks[$match_keyword][$index[$match_keyword]];
				$pos = strlen($modifiedContent) - $originalLen + $match[1];
				$modifiedContent = substr($modifiedContent,0,$pos)."<a href='".$url."/'>".substr($modifiedContent,$pos,strlen($match[0]))."</a>".substr($modifiedContent,$pos+strlen($match[0]));
			}
		}
		return $modifiedContent;
	}
	
	
	
	/**
	 * Reads Config.xml file and puts all pages and posts in a table for processing.
	 * ASYNCHRONOUS
	 *
	 * @since 1.0.0
	 *
	 * @global $wpdb
	 *
	 * @param  none
	 * @return none
	 */
	public static function readConfig(){
		global $wpdb;
		
		if(strlen(self::$guid) == 0) return;
		if(!self::remoteFileExists(self::$path_to_storage .self::$guid . "/config.xml")) return;
		self::logEvent("Reading config: " . self::$path_to_storage .self::$guid . "/config.xml");
		
		self::truncateLogFile();
		
		// create the tables if they don't already exist
		if($wpdb->get_var("SHOW TABLES LIKE '".$wpdb->prefix."ssc_pages'") != $wpdb->prefix."ssc_pages") {
			self::logEvent("Creating database tables in readConfig()");
			
			self::createDbTables();
			
			update_option( 'ssc_config_last_modified', 'reset' );
		}
		
		update_option( 'ssc_last_check', current_time('timestamp') );
		
		$lastModified = self::getLastModified(self::$path_to_storage . self::$guid . "/config.xml");
		self::logEvent("Last-Modified: ".$lastModified);
		
		self::logEvent( "Actual Last-Modified: " . $lastModified );
		self::logEvent( "Stored Last-Modified: " . get_option( 'ssc_config_last_modified' ) );
		
		if($lastModified != get_option( 'ssc_config_last_modified' )){
			update_option( 'ssc_config_last_modified' , $lastModified);
			update_option( 'ssc_last_update', current_time('timestamp') );
			delete_option( 'ssc_last_update_completion' );
			set_site_transient('ssc_processQueue_lock', time(), 60);
			
			
			// check to see if there are any images to be uploaded
			if(self::remoteFileExists(self::$path_to_storage . self::$guid . "/uploaded_images/uploaded_images.xml")){
				$imageInfo = self::loadXML(self::$path_to_storage . self::$guid . "/uploaded_images/uploaded_images.xml");
				
				$totalImages = count($imageInfo->images->image);
				update_option( 'ssc_image_count', $totalImages );
				
				// loop through each <image> element in the config file and add to database to be downloaded
				foreach($imageInfo->images->image AS $image){
					$image_name = (string)$image->{'image-name'};
					
					$wpdb->replace( $wpdb->prefix.'ssc_images',
						array( 
							'fileName'     => $image_name
						)
					);
				}
			}else{
				update_option( 'ssc_image_count', 0 );
			}
			
			// mark all pages for deletion
			self::markPages();
			
			$configInfo = self::loadXML(self::$path_to_storage . self::$guid . "/config.xml");
		
			$totalPages = count($configInfo->pages->page);
			$totalPosts = count($configInfo->posts->blog);
			update_option( 'ssc_page_count', $totalPages );
			update_option( 'ssc_post_count', $totalPosts );
		
			// we want more items in each group than there are threads, but we want the number of threads to scale with the number of total items
			$threadCount = sqrt($totalPages + $totalPosts) / 2;
			if($threadCount > 5) $threadCount = 5;
			$groupSize = floor(($totalPages + $totalPosts) / $threadCount);
			
			if($groupSize == 0) $groupSize = 1;
			
			$processGroup = -1;
			$counter = 0;

			self::logEvent( "Page count: " . count($configInfo->pages->page) );

			// loop through each <page> element in the config file
			foreach($configInfo->pages->page AS $page){
				if($counter % $groupSize == 0) $processGroup++;
				$counter++;

				$page_id = (string)$page->{'page-id'};
				$page_title = (string)$page->{'page-title'};
				$page_meta_link = (string)$page->{'page-meta-link'};
				
				$wpdb->insert( $wpdb->prefix.'ssc_pages', 
					array( 
						'id'     => $page_id,
						'title'    => $page_title,
						'metaLink'    => $page_meta_link,
						'processGroup' => $processGroup
					)
				);
			}
			$count = $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_pages`" );
			self::logEvent("Pages inserted in database: " . $count );
			
			$counter = 0;
			
			self::logEvent( "Post count: " . count($configInfo->posts->blog) );
			
			$groupSize = floor($groupSize / 3); // posts take longer so make the groups half the size
			
			if($groupSize == 0) $groupSize = 1;
			
			// loop through each <post> element in the config file
			foreach($configInfo->posts->blog AS $post){

				if($counter % $groupSize == 0) $processGroup++;
				$counter++;
				
				$post_id = (string)$post->{'blog-id'};
				$post_title = (string)$post->{'title'};
				$post_date = (string)$post->{'publish-date'};
				$post_social_link = (string)$post->{'sm-post-link'};
				$post_primary_category = (string)$post->{'primary-category'};
				$post_categories = array();
				foreach($post->categories->name AS $category){
					$post_categories[] = (string)$category;
				}
				
				$post_tags = array();
				foreach($post->tags->name AS $tag){
					$post_tags[] = (string)$tag;
				}
				
				$wpdb->insert( $wpdb->prefix.'ssc_posts', 
					array( 
						'id'     => $post_id,
						'title'    => $post_title,
						'publishDate'    => $post_date . ' UTC',
						'primaryCategory'    => $post_primary_category,
						'categories'    => json_encode($post_categories),
						'tags'    => json_encode($post_tags),
						'socialLink'    => $post_social_link,
						'processGroup' => $processGroup
					)
				);

			}
			
			self::logEvent("Posts inserted in database: " . $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_posts`" ) );
			
			//if there is a main and alternate phone number then save them to the database
			if($configInfo->{'alt-phone'} AND $configInfo->{'main-phone'}){
				update_option( 'ssc_alt_phone', (string)$configInfo->{'alt-phone'} );
				update_option( 'ssc_main_phone', (string)$configInfo->{'main-phone'} );
			}else{
				delete_option( 'ssc_alt_phone' );
				delete_option( 'ssc_main_phone' );
			}
			
			
			//if there is a 301 redirect url then save it to the database
			if($configInfo->{'redirect301-url'}){
				update_option( 'ssc_301_redirect_url', sanitize_url((string)$configInfo->{'redirect301-url'} ));
			}else{
				delete_option( 'ssc_301_redirect_url' );
			}

			//if there is a 301 redirect url then save it to the database
			if($configInfo->{'inactive404-url'}){
				update_option( 'ssc_404_inactive_url', sanitize_url((string)$configInfo->{'inactive404-url'} ));
			}else{
				delete_option( 'ssc_404_inactive_url' );
			}

			//if there are interlinking keywords then store them and the page links in the database
			if(is_countable($configInfo->interlinking->keyword) && count($configInfo->interlinking->keyword)){
				// loop through each <page> element in the config file
				foreach($configInfo->pages->page AS $page){
					list($keyword,$location) = explode(" in ", (string)$page->{'page-title'});
					$keyword = strtolower(preg_replace('/\s+/', ' ',$keyword));
					$landingLinks[$keyword][] = sanitize_url($page->{'page-meta-link'});
				}
				foreach($configInfo->interlinking->keyword AS $keyword){
					$sanitizedKeyword = sanitize_text_field((string)$keyword);
					if($sanitizedKeyword != ""){
						$keywords[] = $sanitizedKeyword;
					}
				}
				update_option( 'ssc_interlinking_keywords', $keywords);
				update_option( 'ssc_interlinking_links', $landingLinks);
			}else{
				delete_option( 'ssc_interlinking_keywords' );
				delete_option( 'ssc_interlinking_links' );
			}


			
			self::logEvent( "== starting processQueue from readConfig" );
			self::asyncThread( 'processQueue', array(0) );
		}else{
			self::logEvent( "config.xml hasn't been updated" );

			//if it's been a day (86400 seconds) and the local time is between 1 and 5 am, then double check that all the pages/posts have been created
			//if( ((intval(current_time('timestamp')) - intval(get_option( 'ssc_last_update' ))) > 3600)   &&   (date_i18n("a",current_time('timestamp')) == "am" && date_i18n("g",current_time('timestamp')) < 5) ){
			//	self::logEvent("Daily, late-night double check of config.xml");
				
				//todo
			//}
			//update_option( 'ssc_config_last_modified', 'reset' );
			
		}
		
		wp_die();
	}


	
	/**
	 * Process the queue of posts and pages in the database that were added by readConfig()
	 * ASYNCHRONOUS
	 *
	 * @since 4.3.0
	 *
	 * @global $wpdb
	 *
	 * @param  $_POST[0] is lower bound on the group number to start on
	 * @return none
	 */
	public static function processQueue(){
		$startTime = microtime(true);
		if(is_numeric($_POST[0])){
			$groupNumber = intval($_POST[0]);
		}else{
			wp_die();
		}
		global $wpdb;
		set_site_transient('ssc_processQueue_lock', time(), self::$processQueueInterval);
		
		
		$totalItems = $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_pages`" ) + $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_posts`" );
		self::logEvent("Starting to processes the queue. Queue size: " . $totalItems);
		
		$types = array( "pages", "posts" );
		
		// dispatch the threads that create the pages and posts, one thread for each processGroup
		foreach($types AS $type){
			$groups = $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT processGroup FROM ".$wpdb->prefix."ssc_".$type." WHERE processGroup >= %d", $groupNumber ) );
			$groupIndex = 0;
			
			while( $groupIndex < count($groups)  &&  $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM ".$wpdb->prefix."ssc_".$type." WHERE processGroup = %d", $groups[$groupIndex] ) ) ){
				set_site_transient('ssc_processQueue_lock', time(), self::$maxExecutionTime);
				self::logEvent( "calling group " . $groups[$groupIndex]);
				self::asyncThread('processGroup', array($type, $groups[$groupIndex]));
				sleep(2); // creating too many threads too quickly sometimes causes problems
				$groupIndex++;

				// if we've reached 90% of the max execution time then start a new processQueue thread and kill this one
				if( (microtime(true) - $startTime) > (self::$maxExecutionTime * .9) ){
					self::logEvent( "RESTARTING processQueue due to long run time" );
					self::asyncThread('processQueue', array($groups[$groupIndex]));
					wp_die();
				}
			}
		}
		
		$totalItems = $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_pages`" ) + $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_posts`" );
		if($totalItems == 0){
			if($wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_images`" )){
				self::asyncThread('processImages');
			}
		}
		
		self::logEvent( "processing the queue (time: " . (microtime(true) - $startTime) . ")" );
		
		wp_die();
	}

	/**
	 * Check the plugin is updated or not
	 * ASYNCHRONOUS
	 *
	 * @since 5.3.1
	 *
	 * @return none
	 */

	public static function Ssc_plugin_update_check(){
		if ( get_site_option( 'ssc_plugin_version' ) !== self::$ssc_plugin_version ) {
			self::logEvent( "== Plugin is updated. Rebuild the Process. ==" );
			update_site_option( 'ssc_plugin_version', self::$ssc_plugin_version );
	        self::adminRebuild();
	    }
	}


	/**
	 * Start the process from the start
	 * ASYNCHRONOUS
	 *
	 * @since 5.2.6
	 *
	 * @return none
	 */
	public static function adminRebuild(){
		self::logEvent( "== starting adminRebuild ==" );
		update_option( 'ssc_config_last_modified', 'reset' );
		delete_option( 'ssc_last_update_completion' );
		self::asyncThread( 'readConfig' );
		sleep(5);
	}
	
	
	
	/**
	 * Process the assigned group of posts or pages in the database
	 * ASYNCHRONOUS
	 *
	 * @since 5.0.0
	 *
	 * @global $wpdb
	 *
	 * @param  $_POST[0] is either "pages" or "posts"
	 * @param  $_POST[1] the group number
	 * @return none
	 */
	public static function processGroup(){
		$startTime = microtime(true);
		$groupType = ($_POST[0] == "pages" ? "pages" : "posts");
		if(is_numeric($_POST[1])){
			$groupNumber = intval($_POST[1]);
		}else{
			wp_die();
		}
		global $wpdb;
		
		self::logEvent( "starting group " . $groupNumber );
		
		while(null !== $row = $wpdb->get_row( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."ssc_".$groupType." WHERE processGroup = %d LIMIT 0,1", $groupNumber))){
			set_site_transient('ssc_processQueue_lock', time(), self::$maxExecutionTime);
			if($groupType == "pages"){
				$post_parent_id = self::checkParentPages($row->metaLink);
				self::checkPage($row->id, $row->title, $post_parent_id);
			}
			if($groupType == "posts"){
				self::checkPost($row->id, $row->title, $row->primaryCategory, json_decode($row->categories), json_decode($row->tags), $row->publishDate, $row->socialLink);
			}
			$wpdb->delete( $wpdb->prefix."ssc_".$groupType, array( 'id' => $row->id ) );
			
			// if we've reached 90% of the max execution time then start a new processGroup thread for the same processGroup and kill this one
			if( (microtime(true) - $startTime) > (self::$maxExecutionTime * .9) ){
				self::logEvent( "RESTARTING processGroup due to long run time" );
				self::asyncThread('processGroup', array($groupType, $groupNumber));
				wp_die();
			}
		}
		
		self::logEvent( "ending group " . $groupNumber . "(done in " . (microtime(true) - $startTime) . " secs)" );
		
		self::asyncThread('processCompleted');
		
		wp_die();
	}
	
	
	
	/**
	 * Process (download) the images that were in uploaded_images.xml
	 * ASYNCHRONOUS
	 *
	 * @since 5.0.0
	 *
	 * @global $wpdb
	 *
	 * @param  none
	 * @return none
	 */
	public static function processImages(){
		$startTime = microtime(true);
		global $wpdb;
		
		self::logEvent( "starting images " );
		
		while(null !== $row = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."ssc_images LIMIT 0,1" )){
			set_site_transient('ssc_processQueue_lock', time(), self::$maxExecutionTime);
			
			self::downloadImage($row->fileName);
			
			$wpdb->delete( $wpdb->prefix."ssc_images", array( 'fileName' => $row->fileName ) );
			
			// if we've reached 50% of the max execution time then start a new processImages thread and kill this one
			if( (microtime(true) - $startTime) > (self::$maxExecutionTime * .5) ){
				self::logEvent( "RESTARTING processImages due to long run time" );
				self::asyncThread('processImages');
				wp_die();
			}
		}
		
		self::logEvent( "ending images (done in " . (microtime(true) - $startTime) . " secs)" );
		
		self::asyncThread('processCompleted');
		
		wp_die();
	}
	
	
	
	/**
	 * Called when a group processing thread ends to check if processing is done, or stuck
	 * ASYNCHRONOUS
	 *
	 * @since 5.0.0
	 *
	 * @global $wpdb
	 *
	 * @param  none
	 * @return none
	 */
	public static function processCompleted(){
		global $wpdb;
		
		$totalItems = $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_pages`" ) + $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_posts`" );
		if($totalItems == 0){
			if($wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_images`" )){
				self::asyncThread('processImages');
			}
		}
		
		$totalItems = $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_pages`" ) + $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_posts`" ) + $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_images`" );
		if($totalItems == 0){
			// done
			if(!get_site_transient('ssc_processCompleted_lock')){
				set_site_transient('ssc_processCompleted_lock', time(), 10);
			
				self::logEvent( "== DONE! ==" );
				
				self::deletePages();

				self::reWriteSlugs();
				
				update_option( 'ssc_last_update_completion', current_time('timestamp') );
			}
		}else{
			sleep(10);
			$totalItemsRecheck = $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_pages`" ) + $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_posts`" ) + $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_images`" );
			if($totalItems == $totalItemsRecheck){
				// not done, but stuck so restart processQueue
				if(!get_site_transient('ssc_processCompleted_lock')){
					set_site_transient('ssc_processCompleted_lock', time(), 10);
					
					self::logEvent( "restarting processQueue from processCompleted because it's stuck" );
					self::asyncThread( 'processQueue', array(0) );
				}
			}else{
				// not done and not stuck, so don't need to do anything
			}
		}
		
		wp_die();
	}
	
	
	
	/**
	 * Checks to see if a page already exists.
	 *
	 * @since 3.3.0
	 *
	 * @global $wpdb
	 *
	 * @param string $page_id This is the GUID for the page.
	 * @param string $page_title The title of the page.
	 * @param int $post_parent_id The id from the wp_posts table that will be used as the new page's parent.
	 * @return none
	 */
	private static function checkPage($page_id, $page_title, $post_parent_id){
		global $wpdb;

		// check to see if the page is already in the database
		$page_row = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $wpdb->posts JOIN $wpdb->postmeta ON {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID WHERE ( meta_key = '_ssc_guid' ) AND post_title = '%s' AND post_parent = %d AND post_type = 'page'", array( $page_title, $post_parent_id)),ARRAY_A);

		if(!is_null($page_row)){
			// if page is already in the database then remove the mark for deletion
			$wpdb->delete($wpdb->postmeta, array("post_id" => $page_row["ID"], "meta_key" => "_ssc_delete"));
			// update the page's guid, which might have changed
			update_post_meta($page_row["ID"], '_ssc_guid', $page_id);
		}else{
			// if the page doesn't exist then create it
			self::createPage($page_id, $page_title, $post_parent_id);
		}
	}
	
	
	
	/**
	 * Creates a single SEO page.
	 *
	 * @since 1.0.0
	 *
	 * @global $wpdb
	 *
	 * @param string $page_id This is the GUID for the page.
	 * @param string $page_title The title of the page.
	 * @param int $post_parent_id The id from the wp_posts table that will be used as the new page's parent.
	 * @return none
	 */
	protected static function createPage($page_id, $page_title, $post_parent_id) {
		global $wpdb;
		
		// Create post object
		$my_post = array(
			'post_title' => $page_title,
			'post_type' => 'page',
			'post_content' => '[sitesupercharger]',
			'post_status' => 'publish',
			'post_parent' => $post_parent_id
		);

		// Insert the post into the database
		$insertId = wp_insert_post( $my_post );

		// Add or Update the meta field in the database.
		add_post_meta($insertId, '_wp_page_template', 'default', true );
		add_post_meta($insertId, '_ssc_guid', $page_id, true );
	}

	/**
	 * Convert xmlobject to simple array.
	 *
	 * @since 5.3.0
	 *
	 * @param object $xmlObject This is the xmlObject.
	 * @param array $out nested value.
	 * 
	 * @return array converted object into array
	 */
	protected static function xml2array( $xmlObject, $out = array () ) {
	    foreach ( (array) $xmlObject as $index => $node )
	        $out[$index] = ( is_object ( $node ) ) ? self::xml2array ( $node ) : $node;

	    return $out;
	}

	/**
	 * Get NodeData of passed argument.
	 *
	 * @since 5.3.0
	 *
	 * @param string $key This is the key of node.
	 * @param string $value This is the value of node.
	 * @param string $dataFor This is the key of main node.
	 * 
	 * @return mixed node data if found else false
	 */
	protected static function getNodeData( $key, $value, $dataFor = 'page' ){
		if( $key == '' ){
			return false;
		}

		if( $value == '' ){
			return false;
		}

		$configInfo = self::loadXML(self::$path_to_storage . self::$guid . "/config.xml");
		$data = array();
		if( $dataFor == 'page' ){
			$data = $configInfo->pages;
			$main_key = 'page';
		} else if( $dataFor == 'post' ) {
			$data = $configInfo->posts;
			$main_key = 'blog';
		}

		if( empty( $data ) ){
			return false;
		}

		$data = self::xml2array( $data );

		$key = array_search( $value, array_column( $data[$main_key], $key ) );
		
		return $data[$main_key][$key];


	}


	/**
	 * Get post author by post id.
	 *
	 * @since 5.3.0
	 *
	 * @global $wpdb
	 *
	 * @param string $post_id This is the GUID for the post.
	 * @return mixed author_id if found else false
	 */
	protected static function getAuthor( $post_id, $dataFor = 'page' ) {
		if( $dataFor == 'page' ){
			$key = 'page-id';
		} else {
			$key = 'blog-id';
		}

		$getNodeValue = self::getNodeData( $key, $post_id, 'post' );
		
		if( ! $getNodeValue || empty( $getNodeValue ) || ! is_object( $getNodeValue ) ){
			return false;
		}

		$post_author_name = (string) $getNodeValue->{'author'};
		
		if( $post_author_name == '' ){
			return false;
		}

		$user = get_user_by( 'login', $post_author_name );
		if( $user ){
			return $user->ID;
		}

		return false;

	}
	
	
	/**
	 * Checks to see if a post already exists.
	 *
	 * @since 3.3.0
	 *
	 * @global $wpdb
	 *
	 * @param string $post_id This is the GUID for the post.
	 * @param string $post_title The title of the post.
	 * @param string array $post_categories The categories of the post.
	 * @param string $post_date The date when the post should be published.
	 * @return none
	 */
	protected static function checkPost($post_id, $post_title, $post_primary_category, $post_categories, $post_tags, $post_date, $post_callback_url){
		global $wpdb;

		// check to see if the post is already in the database
		$post_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts JOIN $wpdb->postmeta ON {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID WHERE (meta_key = '_ssc_guid') AND meta_value = '%s' AND post_type = 'post'", $post_id),ARRAY_A);

		if(!is_null($post_row)){
			// if post is already in the database then remove the mark for deletion
			$wpdb->delete($wpdb->postmeta, array("post_id" => $post_row["ID"], "meta_key" => "_ssc_delete"));

			// update the post's guid, which might have changed
			update_post_meta($post_row["ID"], '_ssc_guid', $post_id);

			// update the post's publish date, which might have changed
			date_default_timezone_set( get_option( 'timezone_string' ) );
			$timestamp = strtotime($post_date);
			$post_status = (($timestamp > time())?'future':'publish');
			$post_date_local = date("Y-m-d H:i:s", $timestamp);

			// need to update excerpt, which might have changed
			$post_info = self::loadXML(self::$path_to_storage . self::$guid . "/" . $post_id . ".xml");
			$featuredimage_1 = $post_info->{'featuredimage-1'};
			if(!empty($featuredimage_1)){
				$post_excerpt = self::getExcerpt($post_info->{'blog-body'}, $withimg=FALSE);
			} else {
				$post_excerpt = self::getExcerpt($post_info->{'blog-body'}, $withimg=TRUE);
			}

			$featured_image_id = self::getAttachmentId($post_info->{'blog-body'});

			delete_post_meta($post_row["ID"], '_thumbnail_id', $featured_image_id); 

			// update the categories, which might have changed
			// start with primary category
			if($post_primary_category != ""){
				$post_primary_category_term = term_exists($post_primary_category, 'category');
				if($post_primary_category_term == 0 || $post_primary_category_term == null){
					$new_cat = wp_insert_term($post_primary_category, 'category');
					$post_primary_category_id = $new_cat['term_id'];
				}else{
					$post_primary_category_id = $post_primary_category_term['term_id'];
				}
				$cat_id_array[] = $post_primary_category_id;
			}

			// Loop through each category and make an array of the category ids.
			foreach($post_categories AS $category){
				if($category != ""){ //skip blank terms
					$term = term_exists($category, 'category');
					// check if category exists
					if ($term == 0 || $term == null) {
						$new_cat = wp_insert_term($category, 'category');
						$this_id = $new_cat['term_id'];
					}else{
						$this_id = $term['term_id'];
					}

					//make sure this category isn't already being used as the primary category
					if($this_id != $post_primary_category_id){
						$cat_id_array[] = $this_id;
					}
				}
			}
			wp_set_post_terms($post_row["ID"], $cat_id_array, 'category', false);

			// Set the 'term_order' of the primary category to 99
			$wpdb->query($wpdb->prepare("UPDATE $wpdb->term_relationships SET term_order = 99 WHERE object_id = %d AND term_taxonomy_id = %d", array($post_row["ID"], $post_primary_category_id)));

			//remove the filter that strips out <iframe> tags
			kses_remove_filters();

			if((string)$post_info->{'video-embed-code'} != ""){
				$post_excerpt = (string)$post_info->{'video-embed-code'}." ".$post_excerpt;
			}

			$post_content = "[sitesupercharger]";

			// Create post object
			$my_post = array(
				'ID' => $post_row["ID"],
				'post_title' => $post_title,
				'post_status' => $post_status,
				'post_date' => $post_date_local,
				'post_date_gmt' => '', //this automatically updates to the correct gmt time, status won't update without this
				'post_excerpt' => $post_excerpt
			);

			$post_author = self::getAuthor( $post_id, 'post' );
			if( $post_author ){
				$my_post['post_author'] = $post_author;
			}

			// Insert the post into the database
			$insertId = wp_update_post( $my_post );

			$featuredimage_1 = $post_info->{'featuredimage-1'};
			if($featuredimage_1 != ""){
				$featured_image_id_1 = self::downloadImage(end(explode('/', $featuredimage_1)), true);
				if(!empty($featured_image_id_1)){
					update_post_meta($insertId, '_thumbnail_id', $featured_image_id_1 );
				}
			}

			$featuredimage_2 = $post_info->{'featuredimage-2'};
			if($featuredimage_2 != ""){
				$featured_image_id_2 = self::downloadImage(end(explode('/', $featuredimage_2)), true);
				if(!empty($featured_image_id_2)){
					update_post_meta($insertId, 'kd_featured-image-2_post_id', $featured_image_id_2 );
				}
			}

			$featuredimage_3 = $post_info->{'featuredimage-3'};
			if($featuredimage_3 != ""){
				$featured_image_id_3 = self::downloadImage(end(explode('/', $featuredimage_3)), true);
				if(!empty($featured_image_id_3)){
					update_post_meta($insertId, 'kd_featured-image-3_post_id', $featured_image_id_3 );
				}
			}

			$featuredimage_4 = $post_info->{'featuredimage-4'};
			if($featuredimage_4 != ""){
				$featured_image_id_4 = self::downloadImage(end(explode('/', $featuredimage_4)), true);
				if(!empty($featured_image_id_4)){
					update_post_meta($insertId, 'kd_featured-image-4_post_id', $featured_image_id_4 );
				}
			}

			$featuredimage_5 = $post_info->{'featuredimage-5'};
			if($featuredimage_5 != ""){
				$featured_image_id_5 = self::downloadImage(end(explode('/', $featuredimage_5)), true);
				if(!empty($featured_image_id_5)){
					update_post_meta($insertId, 'kd_featured-image-5_post_id', $featured_image_id_5 );
				}
			}

			//turn filters back on
			kses_init_filters();


			if(count($post_row) > 1){
				//delete extra rows
			}
		}else{
			// if the post doesn't exist then create it
			self::createPost($post_id, $post_title, $post_primary_category, $post_categories, $post_tags, $post_date, $post_callback_url);
		}
	}
	
	
	
	/**
	 * Creates a single SEO post.
	 *
	 * @since 2.0.0
	 *
	 * @global $wpdb
	 *
	 * @param string $post_id This is the GUID for the post.
	 * @param string $post_title The title of the post.
	 * @param string array $post_categories The categories of the post.
	 * @param string $post_date The date when the post should be published.
	 * @return none
	 */
	protected static function createPost($post_id, $post_title, $post_primary_category, $post_categories, $post_tags, $post_date, $post_callback_url) {
		global $wpdb;

		$post_primary_category_id = 0;
		
		//get primary category id
		if($post_primary_category != ""){
			$post_primary_category_term = term_exists($post_primary_category, 'category');
			if($post_primary_category_term == 0 || $post_primary_category_term == null){
				$new_cat = wp_insert_term($post_primary_category, 'category');
				$post_primary_category_id = $new_cat['term_id'];
			}else{
				$post_primary_category_id = $post_primary_category_term['term_id'];
			}
		}

		// This array will hold the category ids
		$cat_id_array = array();

		// Loop through each category and make an array of the category ids.
		foreach($post_categories AS $category){
			if($category != ""){ //skip blank terms
				$term = term_exists($category, 'category');
				// check if category exists
				if ($term == 0 || $term == null) {
					$new_cat = wp_insert_term($category, 'category');
					$this_id = $new_cat['term_id'];
				}else{
					$this_id = $term['term_id'];
				}

				//if primary category is blank then make this category primary
				if(!$post_primary_category_id > 0){
					$post_primary_category_id = $this_id;
				}else{
					//make sure this category isn't already being used as the primary category
					if($this_id != $post_primary_category_id){
						$cat_id_array[] = $this_id;
					}
				}
			}
		}

		$tag_array = [];
		// Loop through each tag and make an array of the tag ids.
		foreach($post_tags AS $tag){
			if($tag != ""){ //skip blank terms
				$tag_array[] = $tag;
			}
		}

		date_default_timezone_set( get_option( 'timezone_string' ) );

		$timestamp = strtotime($post_date);
		$post_status = (($timestamp > time())?'future':'publish');
		$post_date_local = date("Y-m-d H:i:s", $timestamp);

		$post_info = self::loadXML(self::$path_to_storage .self::$guid . "/" . $post_id . ".xml");

		$featuredimage_1 = $post_info->{'featuredimage-1'};
		if(!empty($featuredimage_1)){
			$post_excerpt = self::getExcerpt($post_info->{'blog-body'}, $withimg=FALSE);
		} else {
			$post_excerpt = self::getExcerpt($post_info->{'blog-body'}, $withimg=TRUE);
		}
		

		//remove the filter that strips out <iframe> tags
		kses_remove_filters();

		if((string)$post_info->{'video-embed-code'} != ""){
			$post_excerpt = (string)$post_info->{'video-embed-code'}." ".$post_excerpt;
		}

		$post_content = "[sitesupercharger]";

		// check AGAIN to see if the post is already in the database
		$post_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts JOIN $wpdb->postmeta ON {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID WHERE (meta_key = '_ssc_guid') AND meta_value = '%s' AND post_type = 'post'", $post_id),ARRAY_A);

		if(is_null($post_row)){
			
			// Create post object
			$my_post = array(
				'post_title' => $post_title,
				'post_type' => 'post',
				'post_content' => $post_content,
				'post_status' => $post_status,
				'post_date' => $post_date_local,
				'post_category' => array( $post_primary_category_id ),
				'tags_input' => $tag_array,
				'post_excerpt' => $post_excerpt
			);

			$post_author = self::getAuthor( $post_id, 'post' );
			if( $post_author ){
				$my_post['post_author'] = $post_author;
			}

			// Insert the post into the database
			$insertId = wp_insert_post( $my_post );
			
			// Add or Update the meta field in the database.
			add_post_meta($insertId, '_ssc_guid', $post_id, true );

			// Add the rest of the categories
			wp_set_post_terms($insertId, $cat_id_array, 'category', true);

			// Set the 'term_order' of the primary category to 99
			$wpdb->query($wpdb->prepare("UPDATE $wpdb->term_relationships SET term_order = 99 WHERE object_id = %d AND term_taxonomy_id = %d", array($insertId, $post_primary_category_id)));

			//turn filters back on
			kses_init_filters();

			$post_guid = get_the_guid($insertId);

			$post_callback_url .= "&BlogURL=".urlencode($post_guid);
			$callback_result = self::sscCallback($post_callback_url);
			if($callback_result[0] == "false"){
				// This fails whenever the callback is called for a post it has already been called for, so frequently. So not logging it anymore.
				//self::logEvent("Warning: callback failed (".$callback_result[1]."): ".$post_callback_url);
			}

			$featuredimage_1 = $post_info->{'featuredimage-1'};
			if($featuredimage_1 != ""){
				$tmp = explode('/', $featuredimage_1);
				$featured_image_id_1 = self::downloadImage(end($tmp), true);
				add_post_meta($insertId, '_thumbnail_id', $featured_image_id_1, true );
			}

			$featuredimage_2 = $post_info->{'featuredimage-2'};
			if($featuredimage_2 != ""){
				$tmp = explode('/', $featuredimage_2);
				$featured_image_id_2 = self::downloadImage(end($tmp), true);
				add_post_meta($insertId, 'kd_featured-image-2_post_id', $featured_image_id_2, true );
			}

			$featuredimage_3 = $post_info->{'featuredimage-3'};
			if($featuredimage_3 != ""){
				$tmp = explode('/', $featuredimage_3);
				$featured_image_id_3 = self::downloadImage(end($tmp), true);
				add_post_meta($insertId, 'kd_featured-image-3_post_id', $featured_image_id_3, true );
			}

			$featuredimage_4 = $post_info->{'featuredimage-4'};
			if($featuredimage_4 != ""){
				$tmp = explode('/', $featuredimage_4);
				$featured_image_id_4 = self::downloadImage(end($tmp), true);
				add_post_meta($insertId, 'kd_featured-image-4_post_id', $featured_image_id_4, true );
			}

			$featuredimage_5 = $post_info->{'featuredimage-5'};
			if($featuredimage_5 != ""){
				$tmp = explode('/', $featuredimage_5);
				$featured_image_id_5 = self::downloadImage(end($tmp), true);
				add_post_meta($insertId, 'kd_featured-image-5_post_id', $featured_image_id_5, true );
			}
		}else{
			self::logEvent("ERROR: Found duplicate post on second check (THIS SHOULD NEVER HAPPEN): ".$post_title);
		}
	}
	
	
	
	/**
	 * Checks a given URL for which parent pages need to be created.
	 *
	 * @since 1.0.0
	 *
	 * @global $wpdb
	 *
	 * @param string $page_meta_link This is the full URL to a page (ex. http://www.domain.com/path/to/file).
	 * @return int The id from the wp_posts table for the last parent page.
	 */
	private static function checkParentPages($page_meta_link) {
		global $wpdb;

		// strip out just the path info from the URL
		$page_path = parse_url($page_meta_link, PHP_URL_PATH);

		// strip out just the path info from the Site URL
		$site_path = parse_url(get_site_url(), PHP_URL_PATH);

		// remove any site directories from the SSC path path and trim any slashes
		$path = trim(preg_replace('/^' . preg_quote($site_path, '/') . '/', '', $page_path),"/");

		// $parents will be an array of each node of the path, including the actual page at the end
		$parents = explode("/",$path);

		$create_parents = 0;
		$post_parent_id = 0;

		// if $parents[0] is blank then no parents are needed
		if($parents[0] != ""){
			foreach($parents AS $index => $parent){

				// skip the last piece of the path, that is the actual page not one of the parents
				if($index < (count($parents)-1)){ 
					if(!$create_parents){

						// search for the parent page in the database
						$parent_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_name = '%s' AND post_parent = %d AND post_type='page' AND post_status='publish'", array($parent, $post_parent_id)),ARRAY_A);

						if(!is_null($parent_row)){
							// if the page exists then set the $post_parent_id to the id of the page
							$post_parent_id = $parent_row["ID"];
							$wpdb->delete($wpdb->postmeta, array("post_id" => $post_parent_id, "meta_key" => "_ssc_delete"));
						}else{
							// if the page doesn't exist
							// set $create_parents to 1 so that it stops looking for pages and just creates the rest of the parent pages needed
							$create_parents = 1;
							$post_parent_id = self::createParentPage($parent,$post_parent_id);
						}
					}else{
						$post_parent_id = self::createParentPage($parent,$post_parent_id);
					}
				}
			}
		}
		return $post_parent_id;
	}
	
	
	
	/**
	 * Creates a single SEO parent page.
	 *
	 * @since 1.0.0
	 *
	 * @global $wpdb
	 *
	 * @param string $parent The title of the page.
	 * @param int $post_parent_id The id from the wp_posts table that will be used as the new page's parent.
	 * @return int The id from the wp_posts table for the page just created.
	 */
	private static function createParentPage($parent, $post_parent_id) {
		global $wpdb;
		
		self::logEvent( "creating parent page: " . $parent );

		// Create post object
		$my_post = array(
			'post_title'    => ucwords(preg_replace('/-/', ' ', $parent)),
			'post_type'    => 'page',
			'post_status'   => 'publish',
			'post_content' => '[sitesupercharger]',
			'post_parent' => $post_parent_id
		);

		// Insert the post into the database
		$insertId = wp_insert_post( $my_post );

		// Add or Update the meta field in the database.
		add_post_meta($insertId, '_ssc_parent_page', '1', true );

		return $insertId;
	}
	
	
	
	/**
	 * Get first 50 words of a blog post
	 *
	 * @since 3.2.0
	 *
	 * @param string $fullText The full text of the blog post.
	 * @param bool $withimg To include images or not
	 * @return string The first 50 words of the blog post.
	 */
	private static function getExcerpt($fullText, $withimg){
		$wordCount = 50;
		$wordsFound = 0;
		if($withimg == FALSE){
			$fullText = (string)self::removeImages($fullText);
		} else {
			$fullText = (string)self::replaceImages($fullText);
		}
		$lastWhite = 0;
		$inTag = 0;
		$inSQuote = 0;
		$inDQuote = 0;
		for($i=0; $i < strlen($fullText); $i++){
			if(!$inTag){
				if(ctype_space($fullText[$i])){
					if(!$lastWhite) $wordsFound++;
					$lastWhite = 1;
				}else{
					$lastWhite = 0;
					if($fullText[$i] == "<") $inTag = 1;
				}
			}else{
				if($fullText[$i] == "'"){
					if(!$inSQuote) $inSQuote = 1;
					else{ $inSQuote = 0; $inDQuote = 0; }
				}
				if($fullText[$i] == '"'){
					if(!$inDQuote) $inDQuote = 1;
					else{ $inDQuote = 0; $inSQuote = 0; }
				}

				if($fullText[$i] == ">" AND !$inSQuote AND !$inDQuote) $inTag = 0;
			}
			if($wordsFound == $wordCount) break;
		}
		return substr($fullText, 0, $i)." [...]";
	}
	
	
	
	/**
	 * Runs at the activation hook to start a new hourly hook and also runs readConfig() for the first time.
	 *
	 * @since 1.0.0
	 *
	 * @param none
	 * @return none
	 */
	public static function activation() {
		
		self::createDbTables();
		
		self::logEvent( "Activation", true );
	}



	/**
	 * Creates the needed database tables
	 *
	 * @since 5.1.2
	 *
	 * @param none
	 * @return none
	 */
	public static function createDbTables() {
		global $wpdb;
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		$charset_collate = $wpdb->get_charset_collate();
		
		$table_name = $wpdb->prefix . "ssc_pages"; 
		$sql = "CREATE TABLE $table_name (
		  id varchar(36) NOT NULL,
		  title varchar(255) NOT NULL,
		  metaLink varchar(255) NOT NULL,
		  processGroup mediumint(9) DEFAULT 0 NOT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate;";
		dbDelta( $sql );
		
		$table_name = $wpdb->prefix . "ssc_posts"; 
		$sql = "CREATE TABLE $table_name (
		  id varchar(36) NOT NULL,
		  title varchar(255) NOT NULL,
		  publishDate varchar(32) NOT NULL,
		  primaryCategory varchar(255) NOT NULL,
		  categories text NOT NULL,
		  tags text NOT NULL,
		  socialLink varchar(255) NOT NULL,
		  processGroup mediumint(9) DEFAULT 0 NOT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate;";
		dbDelta( $sql );
		
		$table_name = $wpdb->prefix . "ssc_images"; 
		$sql = "CREATE TABLE $table_name (
		  fileName varchar(255) NOT NULL,
		  PRIMARY KEY  (fileName)
		) $charset_collate;";
		dbDelta( $sql );
	}



	/**
	 * Runs at the deactivation hook to stop the hourly hook and also runs deletePages() to remove pages created.
	 *
	 * @since 1.0.0
	 *
	 * @param none
	 * @return none
	 */
	public static function deactivation() {
		global $wpdb;
		
		$inProgress = ($wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_pages`" ) OR $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_posts`" ) OR $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_images`" ));
		
		$table_name = $wpdb->prefix . "ssc_pages";
		$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
		$table_name = $wpdb->prefix . "ssc_posts";
		$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
		$table_name = $wpdb->prefix . "ssc_images";
		$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
		
		if($inProgress){
			sleep(10); // this should help any content-creating threads end before deleting all the content
		}
		
		self::markPages();
		self::deletePages();
		self::deleteImages();
		
		// Just in case, delete anything in the post table who's content is just '[sitesupercharger]' (this will take care of revisions too)
		$wpdb->query("DELETE FROM $wpdb->posts WHERE post_content = '[sitesupercharger]'");
		
		delete_site_transient( 'ssc_readConfig_lock' );
		delete_site_transient( 'ssc_processQueue_lock' );
		delete_site_transient( 'ssc_processCompleted_lock' );
		
		delete_option( 'ssc_alt_phone' );
		delete_option( 'ssc_config_last_modified' );
		delete_option( 'ssc_force_update' );
		delete_option( 'ssc_group_size' );
		delete_option( 'ssc_image_count' );
		delete_option( 'ssc_last_check' );
		delete_option( 'ssc_last_check_result' );
		delete_option( 'ssc_last_update' );
		delete_option( 'ssc_last_update_completion' );
		delete_option( 'ssc_locked' );
		delete_option( 'ssc_log' );
		delete_option( 'ssc_main_phone' );
		delete_option( 'ssc_page_count' );
		delete_option( 'ssc_post_count' );
		delete_option( 'ssc_queue' );
		delete_option( 'ssc_queue_cancel' );
		delete_option( 'ssc_queue_current_page' );
		delete_option( 'ssc_queue_current_page_timestamp' );
		delete_option( 'ssc_queue_hook_timestamp' );
		delete_option( 'ssc_queue_length' );
		delete_option( 'ssc_queue_length_total' );
		delete_option( 'ssc_queue_page_processed' );
		delete_option( 'ssc_queue_page_skipped' );
		delete_option( 'ssc_queue_post_processed' );
		delete_option( 'ssc_queue_post_skipped' );
		delete_option( 'ssc_update_end' );
		delete_option( 'ssc_update_method' );
		delete_option( 'ssc_update_result' );
		delete_option( 'ssc_update_start' );

		delete_site_option( 'ssc_plugin_version' );
	}
	
	
	
	/**
	 * Determine if the phone number should be swapped
	 *
	 * @since 5.0.0
	 *
	 * @param none
	 * @return none
	 */
	private static function initPhoneSwap() {
		global $wpdb;
	
		$http_referer = (isset($_SERVER['HTTP_REFERER'])) ? sanitize_url($_SERVER['HTTP_REFERER']) : "";
		$http_host = sanitize_text_field($_SERVER["HTTP_HOST"]);
		$request_uri = sanitize_url($_SERVER["REQUEST_URI"]);
		
		if(!isset($_COOKIE["ssc_phoneSwap"])){
			if(strpos($http_referer,site_url()) === false){
				//referer is an outside site

				//check if referer is google, facebook, linkedin, or twitter; if so, then do phone swap
				$referer_domain = parse_url($http_referer, PHP_URL_HOST);
				if(strpos($referer_domain, "google.com") !== FALSE){
					self::$phoneSwap = 1;
					$cookie_status = setcookie("ssc_phoneSwap", 1, time()+365*86400, "/");
				}elseif(strpos($referer_domain, "facebook.com") !== FALSE){
					self::$phoneSwap = 1;
					$cookie_status = setcookie("ssc_phoneSwap", 1, time()+365*86400, "/");
				}elseif(strpos($referer_domain, "linkedin.com") !== FALSE){
					self::$phoneSwap = 1;
					$cookie_status = setcookie("ssc_phoneSwap", 1, time()+365*86400, "/");
				}elseif(strpos($referer_domain, "twitter.com") !== FALSE){
					self::$phoneSwap = 1;
					$cookie_status = setcookie("ssc_phoneSwap", 1, time()+365*86400, "/");
				}
				
				if(self::$phoneSwap != 1){
					//check if we're on a SSC landing page; if so, then do phone swap
					$urlSearchTerm = "%".$http_host.$request_uri;
					$page = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE $wpdb->posts.guid LIKE '%s' AND $wpdb->posts.post_type IN ('page','post') AND $wpdb->posts.post_status = 'publish' AND ($wpdb->postmeta.meta_key = '_ssc_guid' OR $wpdb->postmeta.meta_key = '_ssc_parent_page')", $urlSearchTerm));

					if(!is_null($page)){
						//set cookie to 1, meaning page is a SSC pages
						self::$phoneSwap = 1;
						$cookie_status = setcookie("ssc_phoneSwap", 1, time()+365*86400, "/");
					}else{
						//set cookie to 0, meaning page is not a SSC pages
						self::$phoneSwap = 0;
						$cookie_status = setcookie("ssc_phoneSwap", 0, time()+365*86400, "/");
					}
				}
			}else{
				//set cookie to 0, meaning refer is an internal page so nothing else matters
				self::$phoneSwap = 0;
				if(!headers_sent())
					$cookie_status = setcookie("ssc_phoneSwap", 0, time()+365*86400, "/");
			}
		}else{
			self::$phoneSwap = (int)$_COOKIE["ssc_phoneSwap"];
		}
		
		
		if(self::$phoneSwap){
			wp_register_script( 'phone-script', plugins_url( '/js/phoneReplace.js', __FILE__ ) );
			wp_enqueue_script( 'phone-script' );
		}
	}
	
	
	
	/**
	 * Generates the HTML for the settings page in WP Admin.
	 *
	 * @since 1.0.0
	 *
	 * @param none
	 * @return none
	 */
	public static function adminOptions() {
		global $wpdb;

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		if( isset($_POST['ssc_guid']) ){
			update_option( 'ssc_guid', preg_replace('/[^\w-]/', '', sanitize_text_field($_POST['ssc_guid']) ) );
			echo '<div class="updated"><p><strong>GUID saved</strong></p></div>';
		}
		
		$ssc_guid = get_option( 'ssc_guid' );
		
		$lastCheck = get_option( 'ssc_last_check' ) ? date_i18n("M j, Y g:ia",get_option( 'ssc_last_check' )) : "Never";
		$lastUpdate = get_option( 'ssc_last_update' ) ? date_i18n("M j, Y g:ia",get_option( 'ssc_last_update' )) : "Never";
		
		$images_total = get_option( 'ssc_image_count' );
		$pages_total = get_option( 'ssc_page_count' );
		$posts_total = get_option( 'ssc_post_count' );
		$images_completed = $images_total - $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_images`" );
		$pages_completed = $pages_total - $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_pages`" );
		$posts_completed = $posts_total - $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_posts`" );
		$images_bar_width = ($images_total > 0) ? $images_completed * 100 / $images_total : 0;
		$pages_bar_width = ($pages_total > 0) ? $pages_completed * 100 / $pages_total : 0;
		$posts_bar_width = ($posts_total > 0) ? $posts_completed * 100 / $posts_total : 0;
		
		$inProgress = ( get_option( 'ssc_last_update' ) && !get_option( 'ssc_last_update_completion' ) );
		
		$actualPageCount = $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."postmeta`  LEFT JOIN `".$wpdb->prefix."posts` ON post_id = ID WHERE meta_key = '_ssc_guid' AND post_type = 'page'" );
		$actualParentPageCount = $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."postmeta` where meta_key = '_ssc_parent_page'" );
		$actualPostCount = $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."postmeta`  LEFT JOIN `".$wpdb->prefix."posts` ON post_id = ID WHERE meta_key = '_ssc_guid' AND post_type = 'post'" );
		$processDuration = get_option( 'ssc_last_update_completion' ) - get_option( 'ssc_last_update' );

		// Now display the settings editing screen
		?>
		<style>
		#guid_wrapper{
			margin:0 auto;
			width: 463px;
		}
		#guid_label{
			font-size: 20px;
			font-weight: bold;
			line-height: 60px;
			padding-bottom: 20;
		}
		#guid_field{
			padding: 2px 8px;
			width: 400px;
			font-size: 20px;
		}
		.last_update{
			color: #d9d9d9;
			float: right;
		}
		.ssc-form-field {
			display: flex;
			align-items: center;
			justify-content: start;
		}
		.ssc-form-field .submit {
		    margin: 0 0 0 10px;
		    padding: 0;
		}
		.rebuild-process {
			margin-top: 10px;
		}
		
		.ssc_adminPanes{
			height: 125px;
			margin: 0 auto;
			overflow: hidden;
			position: relative;
		}
		.ssc_slide{
			position: absolute;
			top: 0;
			transition: all 1s;
			width: 100%;
		}
		.ssc_slide.done{
			top: -125px;
		}
		.ssc_slide > div{
			height: 125px;
		}
		
		.progress_wrapper{
			width: 50%;
			height: 24px;
			position: relative;
		}
		.progress_label{
			display: inline-block;
			vertical-align: middle;
			width: 54px;
			text-align: right;
		}
		.progress_bar_wrapper{
			display: inline-block;
			vertical-align: middle;
			width: calc(100% - 114px);
			height: 20px;
			background: white;
			border-radius: 5px;
			border: 1px solid black;
		}
		.progress_bar_fill{
			height: 20px;
			position: relative;
			border-bottom-right-radius: 4px;
			border-top-right-radius: 4px;
		}
		#progress_bar_images{
			background: #834ac3;
			transition: all 200ms;
		}
		#progress_bar_pages{
			background: #8bc34a;
		}
		#progress_bar_posts{
			background: #c3664a;
		}
		.progress_bar_fill div{
			position: absolute;
			right: 5px;
			color: white;
			font-weight:bold;
		}
		.progress_bar_total{
			display: inline-block;
			vertical-align: middle;
			width: 50px;
		}
		.waiting{
			text-align: center;
			font-size: 20px;
			padding-top: 30px;
		}
		.subtext{
			font-size: 12px;
			color: #888888;
		}
		.ssc_link {
			font-size: 16px;
			text-decoration: underline;
			margin-right: 10px;
		}
		</style>
		<div class="wrap">
			<h1>SiteSuperCharger Settings</h1>
			<form name="form1" method="post" action="">
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row"><label for="ssc_guid">GUID</label></th>
							<td>
								<div class="ssc-form-field">
									<input id="ssc_guid" class="regular-text" name="ssc_guid" type="text" value="<?php echo esc_attr($ssc_guid); ?>">
									<p class="submit"><input type="submit" name="Submit" class="button-primary" value="Save Changes" /></p>
								</div>
							</td>
						</tr>
						<tr>
							<th scope="row"><label>Status</label></th>
							<td>
								<div class='ssc_adminPanes'>
									<div class='ssc_slide'>
										<?php
										if($ssc_guid == ""){
											echo "<p class='description'>Enter a valid GUID.</p>";
										}else{
											if($lastCheck == "Never"){
												echo "<div><p class='description'>Awaiting first update.</p></div>";
											}
											if($inProgress){
												?>
												<div>
													<p style="margin-bottom: 10px">Content creation in progress.</p>
													<div class="progress_wrapper">
														<div class="progress_label">Pages:&nbsp;</div>
														<div class="progress_bar_wrapper">
															<div class="progress_bar_fill" id="progress_bar_pages" style="width:<?php echo esc_attr($pages_bar_width); ?>%; min-width:15px;"><div><?php echo esc_html($pages_completed); ?></div></div>
														</div>
														<div class="progress_bar_total">of <?php echo esc_html($pages_total); ?></div>
													</div>
													<div class="progress_wrapper">
														<div class="progress_label">Posts:&nbsp;</div>
														<div class="progress_bar_wrapper">
															<div class="progress_bar_fill" id="progress_bar_posts" style="width:<?php echo esc_attr($posts_bar_width); ?>%; min-width:15px;"><div><?php echo esc_html($posts_completed); ?></div></div>
														</div>
														<div class="progress_bar_total">of <?php echo esc_html($posts_total); ?></div>
													</div>
													<?php if($images_total > 0){ ?>
													<div class="progress_wrapper">
														<div class="progress_label">Images:&nbsp;</div>
														<div class="progress_bar_wrapper">
															<div class="progress_bar_fill" id="progress_bar_images" style="width:<?php echo esc_attr($images_bar_width); ?>%; min-width:15px;"><div><?php echo esc_html($images_completed); ?></div></div>
														</div>
														<div class="progress_bar_total">of <?php echo esc_html($images_total); ?></div>
													</div>
													<?php } ?>
												</div>
												<?php
											}
											?>
											<div><p style="margin-bottom: 10px">Content creation completed.</p>
												<div>SSC Pages: <span id="ssc_actualPageCount"><?php echo esc_html($actualPageCount); ?></span> (+<span id="ssc_actualParentPageCount"><?php echo esc_html($actualParentPageCount); ?></span> parent page<span id="ssc_actualParentPageCountPlural"><?php if($actualParentPageCount > 1) echo "s" ?></span>)</div>
												<div>SSC Posts: <span id="ssc_actualPostCount"><?php echo esc_html($actualPostCount); ?></span></div>
												<div class="subtext" style="margin-bottom:10px;">Created in <span id="ssc_processDuration"><?php echo  esc_html($processDuration); ?></span>s</div>
												<div class="subtext" style="line-height:10px;">Last Checkup: <span id="ssc_lastCheck"><?php echo  esc_html($lastCheck); ?></span></div>
												<div class="subtext">Last Update: <span id="ssc_lastUpdate"><?php echo  esc_html($lastUpdate); ?></span></div>
											</div>
											<?php
										}
										?>
									</div>
								</div>
								<p class="submit"><input type="submit" name="rebuild" class="button-primary rebuild-process" value="Rebuild" /></p>
								<div class="ssc-parent-content">
									<?php
									$feedbackPage = $wpdb->get_var( "SELECT ID FROM ".$wpdb->prefix."posts WHERE post_title = 'Feedback' AND post_type = 'page' AND post_status = 'publish' ORDER BY post_date ASC, ID ASC LIMIT 0, 1 " );
									if( !empty( $feedbackPage ) ){
										$feedbackAncestors = get_post_ancestors( $feedbackPage );
										if( !empty( $feedbackAncestors ) ){
											$topAncestor = end( $feedbackAncestors );
											?>
											<a class="ssc_link" target="_blank" href="<?php echo get_the_permalink( $topAncestor ); ?>">Directory</a>
											<?php
										}
										?>
										<a class="ssc_link" target="_blank" href="<?php echo get_the_permalink( $feedbackPage ); ?>">Feedback</a>
										<?php
									}
									?>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
		<?php
		if($inProgress){ ?>
			<script type='text/javascript'>
			jQuery(document).ready(function($){
				function getRemaining(){
					var data = { 'action':'adminUpdate' };

					jQuery.post(ajaxurl, data, function(response){
						var types = response.split("|");
						var image_parts = types[0].split(",");
						var page_parts = types[1].split(",");
						var post_parts = types[2].split(",");
						var isDone = parseInt(types[3]);

						$("#progress_bar_images").css({width : image_parts[0]+'%'})
						$("#progress_bar_images div").html(image_parts[1]);
							
						if(image_parts[1] > 9) $("#progress_bar_images").css('min-width','22px');
						
						$("#progress_bar_pages").animate({width : page_parts[0]+'%'}, 1000, function(){
							$("#progress_bar_pages div").html(page_parts[1]);
						});
						if(page_parts[1] > 9) $("#progress_bar_pages").css('min-width','22px');
						
						$("#progress_bar_posts").animate({width : post_parts[0]+'%'}, 1000, function(){
							$("#progress_bar_posts div").html(post_parts[1]);
						});
						if(post_parts[1] > 9) $("#progress_bar_posts").css('min-width','22px');
					
						if(isDone){
							//done
							var data = { 'action':'adminUpdateCompleted' };
							jQuery.post(ajaxurl, data, function(response){
								var parts = response.split("|");
								var actualPageCount = parts[0];
								var actualParentPageCount = parts[1];
								var actualPostCount = parts[2];
								var processDuration = parts[3];
								var lastCheck = parts[4];
								var lastUpdate = parts[5];
								
								$("#ssc_actualPageCount").html(actualPageCount);
								$("#ssc_actualParentPageCount").html(actualParentPageCount);
								$("#ssc_actualParentPageCountPlural").html(actualParentPageCount > 1 ? "s" : "");
								$("#ssc_actualPostCount").html(actualPostCount);
								$("#ssc_processDuration").html(processDuration);
								$("#ssc_lastCheck").html(lastCheck);
								$("#ssc_lastUpdate").html(lastUpdate);
						
								$(".ssc_slide").addClass("done");
							});
							
						}else{
							setTimeout (getRemaining, 1000);
						}
					}).fail(function(){
						setTimeout (getRemaining, 2000);
					});
				}
				getRemaining();
			});
			</script> <?php
		}
		?>
		<script type="text/javascript">
			jQuery( ".rebuild-process" ).on( 'click', function( e ){
				e.preventDefault();
				var data = { 'action' : 'adminRebuild' };

				jQuery.post(ajaxurl, data, function(response){
					console.log( response );
					location.reload();
				}).fail(function(){
					alert( 'Please try later.' );
				});
			});
		</script>
		<?php
	}
	
	
	
	/**
	 * Returns the info to JS for the progess bar updates on admin settings page while content is being created
	 * ASYNCHRONOUS
	 *
	 * @since 5.0.0
	 *
	 * @param none
	 * @return none
	 */
	public static function adminUpdate() {
		global $wpdb;

		$images_total = get_option( 'ssc_image_count' );
		$pages_total = get_option( 'ssc_page_count' );
		$posts_total = get_option( 'ssc_post_count' );
		$images_completed = $images_total - $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_images`" );
		$pages_completed = $pages_total - $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_pages`" );
		$posts_completed = $posts_total - $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."ssc_posts`" );
		$images_bar_width = $pages_bar_width = $posts_bar_width = 0;
		if( $images_total != 0 ){
			$images_bar_width = round($images_completed * 100 / $images_total);
		}
		if( $pages_total != 0 ){
			$pages_bar_width = round($pages_completed * 100 / $pages_total);
		}
		if( $posts_total != 0 ){
			$posts_bar_width = round($posts_completed * 100 / $posts_total);
		}
		$isDone = ($images_total == $images_completed  &&  $pages_total == $pages_completed  &&  $posts_total == $posts_completed) ? 1 : 0;

		echo esc_html($images_bar_width . "," . $images_completed . "|" . $pages_bar_width . "," . $pages_completed . "|" . $posts_bar_width . "," . $posts_completed . "|" . $isDone);
		
		wp_die();
	}
	
	
	
	/**
	 * Returns the info to JS for content creation stats to be displayed on admin settings page
	 * ASYNCHRONOUS
	 *
	 * @since 5.0.0
	 *
	 * @param none
	 * @return none
	 */
	public static function adminUpdateCompleted() {
		global $wpdb;

		$actualPageCount = $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."postmeta`  LEFT JOIN `".$wpdb->prefix."posts` ON post_id = ID WHERE meta_key = '_ssc_guid' AND post_type = 'page'" );
		$actualParentPageCount = $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."postmeta` where meta_key = '_ssc_parent_page'" );
		$actualPostCount = $wpdb->get_var( "SELECT COUNT(*) FROM `".$wpdb->prefix."postmeta`  LEFT JOIN `".$wpdb->prefix."posts` ON post_id = ID WHERE meta_key = '_ssc_guid' AND post_type = 'post'" );
		$processDuration = get_option( 'ssc_last_update_completion' ) - get_option( 'ssc_last_update' );
		$lastCheck = get_option( 'ssc_last_check' ) ? date_i18n("M j, Y g:ia",get_option( 'ssc_last_check' )) : "Never";
		$lastUpdate = get_option( 'ssc_last_update' ) ? date_i18n("M j, Y g:ia",get_option( 'ssc_last_update' )) : "Never";

		echo esc_html($actualPageCount . "|" . $actualParentPageCount . "|" . $actualPostCount . "|" . $processDuration . "|" . $lastCheck . "|" . $lastUpdate);
		
		wp_die();
	}
	
	
	
	/**
	 * Runs the given function (action) asynchronously
	 *
	 * @since 5.0.0
	 *
	 * @param string $action The name of the function to run asynchronously (must have a registered action in init)
	 * @param array $data (optional) Data passed to the action function via the $_POST variable
	 * @return none
	 */
	private static function asyncThread($action, $data = array()){
		$url = add_query_arg(array('action' => $action, 'nonce' => wp_create_nonce($action)), admin_url('admin-ajax.php'));
		$args = array(
				'method'	=> 'POST',
				'redirection' => 0.1,
				'timeout'   => 0.1,
				'httpversion' => 1.0,
				'blocking'  => false,
				'body'      => $data,
				'cookies'   => array(),
				'sslverify' => apply_filters( 'https_local_ssl_verify', false )
			);
		wp_remote_post( esc_url_raw( $url ), $args );
	}
	
	
	
	/**
	 * Runs at the post_link_category to fix permalinks with %category% to display the select Primary Category instead of the category with the lowest id.
	 *
	 * @since 4.4.0
	 *
	 * @global $wpdb
	 *
	 * @param int $default The default category it uses for the permalink url, which is determined by the category with the lowest id
	 * @param array int $cats An array of all the other categories assigned to this post
	 * @param object $post The post of the permalink that we're dealing with
	 * @return int The category id of the category to be used in the URL
	 */
	public static function primaryCategoryPermalink( $default, $cats, $post ) {
		global $wpdb;

		$primary = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->term_relationships WHERE object_id = %d AND term_order = 99", $post->ID));

		if(!is_null($primary)){
			// if a primary category is found then use it in the permalink URL
			return $primary->term_taxonomy_id;
		}

		return $default;
	}
	
	
	
	/**
	 * Replace img tag src with correct path to images
	 *
	 * @since 2.2.0
	 *
	 * @global $wpdb
	 *
	 * @param string $content The content of the page or post.
	 * @return string The content after the <img> tags have been modified.
	 */
	private static function replaceImages( $content ) {
		global $wpdb;

		$dom = new DOMDocument();
		@$dom->loadHTML($content);
		$imageTags = $dom->getElementsByTagName('img');

		foreach($imageTags as $tag) {
			$imageSource = $tag->getAttribute('src');

			$post_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_ssc_image' AND meta_value = '%s'", addslashes($imageSource)));
			if( $post_id ){
				$full_path = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = '%s' AND meta_key = '_wp_attached_file'", $post_id));
				if( $full_path ){
					$upload_dir = wp_upload_dir();
					$tag->setAttribute('src',$upload_dir["baseurl"]."/".$full_path);
				}
			}
		}

		//The DOMDocument automatically adds doctype, html, and body tags that we don't want, so we strip them out here
		$strip_HTML_body = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $dom->saveHTML()));

		return $strip_HTML_body;
	}
	
	
	
	/**
	 * Remove img tag from excerpt
	 *
	 * @since 2.2.0
	 *
	 * @global $wpdb
	 *
	 * @param string $content The content of the page or post.
	 * @return string The content after the <img> tags have been modified.
	 */
	private static function removeImages( $content ) {
		$excerpt_content = preg_replace("/<img[^>]+\>/i", "", $content); 
		return $excerpt_content;
	}
	
	
	
	/**
	 * Replace first img tag src with correct path to images
	 *
	 * @since 4.0.11
	 *
	 * @global $wpdb
	 *
	 * @param string $content The content of the page or post.
	 * @return string The content after the <img> tags have been modified.
	 */
	private static function getFirstImage( $content ) {
		global $wpdb;

		$dom = new DOMDocument();
		@$dom->loadHTML($content);
		$imageTags = $dom->getElementsByTagName('img');

		$tag = $imageTags->item(0);
		if($tag instanceof \DOMElement){
			$imageSource = $tag->getAttribute('src');

			$post_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_ssc_image' AND meta_value = '%s'", addslashes($imageSource)));
			if( $post_id ){
				$full_path = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = '%s' AND meta_key = '_wp_attached_file'", $post_id));
				if( $full_path ){
					$upload_dir = wp_upload_dir();
					$tag->setAttribute('src',$upload_dir["baseurl"]."/".$full_path);
				}
			}
			return $upload_dir["baseurl"]."/".$full_path;
		}else{
			return 0;
		}

		return 0;
	}
	
	
	
	/**
	 * Get Attachment ID from image
	 *
	 * @since 4.0.11
	 *
	 * @global $wpdb
	 *
	 * @param string $content The content of the page or post.
	 * @return string The content after the <img> tags have been modified.
	 */
	private static function getAttachmentId( $content ) {
		global $wpdb;

		$dom = new DOMDocument();
		@$dom->loadHTML($content);
		$imageTags = $dom->getElementsByTagName('img');

		$tag = $imageTags->item(0);
		if($tag instanceof \DOMElement){
			$imageSource = $tag->getAttribute('src');

			$post_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_ssc_image' AND meta_value = '%s'", addslashes($imageSource)));

			return $post_id;
		}else{
			return 0;
		}
		return 0;
	}
	
	
	
	/**
	 * Downloads An Image
	 *
	 * @since 2.2.0
	 *
	 * @global $wpdb
	 *
	 * @param string $image The file name of the image.
	 * @param bool $isFeatured (optional) Set to true if downloading featured images for a post
	 * @return int Post ID
	 */
	private static function downloadImage( $image, $isFeatured = false ) {
		global $wpdb;

		//self::logEvent("  Downloading image: ".$image);

		if($isFeatured){
			$path = self::$path_to_app . "Uploads/" . self::$guid . "/" . rawurlencode($image);
		}else{
			$path = self::$path_to_storage . self::$guid . "/uploaded_images/" . rawurlencode($image);
		}

		//check to see if the image is already uploaded
		$imgmeta = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE $wpdb->postmeta.meta_key = '_ssc_image' AND $wpdb->postmeta.meta_value = \"%s\"", $image));
		if(is_null($imgmeta)){
			//check if image exists on the remote server
			if(self::remoteFileExists($path)){
				$uploaddir = wp_upload_dir();
				$uploadfile = $uploaddir['path'] . '/' . str_replace(" ", "_", $image);
				
				list($width, $height, $type, $attr) = getimagesize($path);

				$iResource = imagecreatefromstring(file_get_contents($path));

				if($width > self::$maxImageSize || $height > self::$maxImageSize){
					if($width > $height){
						$new_width = self::$maxImageSize;
						$new_height = round(self::$maxImageSize / $width * $height);
					}else{
						$new_height = self::$maxImageSize;
						$new_width = round(self::$maxImageSize / $height * $width);
					}
					$iResource = imagescale($iResource, $new_width, $new_height);
				}

				switch($type){
						case 1: imagegif($iResource, $uploadfile); break;
						case 2: imagejpeg($iResource, $uploadfile); break;
						case 3: imagepng($iResource, $uploadfile); break;
				}
				
				imagedestroy($iResource);

				$wp_filetype = wp_check_filetype(basename($image), null );
				
				$attachment = array(
				    'post_mime_type' => $wp_filetype['type'],
				    'post_title' => $image,
				    'post_content' => '',
				    'post_status' => 'inherit'
				);


				$attach_id = wp_insert_attachment( $attachment, $uploadfile );
				$imagenew = get_post( $attach_id );
				$fullsizepath = get_attached_file( $imagenew->ID );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $fullsizepath );
				wp_update_attachment_metadata( $attach_id, $attach_data );
				add_post_meta($attach_id, '_ssc_image', $image, true );
				return $attach_id;
			}else{
				self::logEvent("Image not found on remote server: ".$image);
			}
		} else {
			self::logEvent("  Image already exists: ".$image);
			return $imgmeta->post_id;
		}
	}
		
	
		
	/**
	 * Mark all the pages created by SiteSuperCharger for future deletion.
	 *
	 * @since 3.3.0
	 *
	 * @global $wpdb
	 * @access Called by readConfig() and readConfig_deactivation()
	 *
	 * @param none
	 * @return none
	 */
	private static function markPages() {
		global $wpdb;
		$marking_pages = array();

		$page_ids = $wpdb->get_results("SELECT post_id FROM $wpdb->postmeta WHERE (meta_key = '_ssc_guid' OR meta_key = '_ssc_parent_page') AND post_id NOT IN (SELECT DISTINCT post_id FROM $wpdb->postmeta WHERE meta_key = '_ssc_delete')");
		foreach( $page_ids AS $page_id ) {
			array_push( $marking_pages, $page_id->post_id );
		}

		$other_page_ids = $wpdb->get_results( "SELECT id FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON ( $wpdb->posts.ID = $wpdb->postmeta.post_id AND $wpdb->postmeta.meta_key = '_ssc_guid' ) LEFT JOIN $wpdb->postmeta AS mt1 ON ( $wpdb->posts.ID = mt1.post_id AND mt1.meta_key = '_ssc_parent_page' ) WHERE 1=1 AND $wpdb->posts.post_content LIKE '%[sitesupercharger]%' AND ( $wpdb->postmeta.post_id IS NULL OR mt1.post_id IS NULL )" );
		foreach( $other_page_ids AS $page_id ){
			array_push( $marking_pages, $page_id->id );
		}

		foreach( $marking_pages AS $page_id ){
			$wpdb->insert($wpdb->postmeta, array( 'post_id' => $page_id, 'meta_key' => '_ssc_delete', 'meta_value' => 1));
		}
	}
	
	
	
	/**
	 * Deletes all the pages created by SiteSuperCharger that have been marked for deletion.
	 *
	 * @since 1.0.0
	 *
	 * @global $wpdb
	 *
	 * @param none
	 * @return none
	 */
	private static function deletePages() {
		global $wpdb;

		$delete_page_ids = $wpdb->get_results("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_ssc_delete'");
		
		self::logEvent("Deleting " . count($delete_page_ids) . " pages/posts");
		
		if( !empty( $delete_page_ids ) ){
			$delete_page_chunks = array_chunk( $delete_page_ids, 1 );
			foreach ( $delete_page_chunks as $key => $delete_data ) {
				$page_id_list = "";
				foreach( $delete_data AS $delete_page_id ) {
					$page_id_list .= ",".$delete_page_id->post_id;
				}
				$page_id_list = trim( $page_id_list, "," );
				self::logEvent("Deleting " . $page_id_list . " pages/posts");

				if( $page_id_list != "" ){
					$wpdb->query($wpdb->prepare("DELETE FROM $wpdb->posts WHERE ID IN (%s)", $page_id_list));
					$wpdb->query($wpdb->prepare("DELETE FROM $wpdb->postmeta WHERE post_id IN (%s)", $page_id_list));
					$wpdb->query($wpdb->prepare("DELETE FROM $wpdb->term_relationships WHERE object_id IN (%s)", $page_id_list));
				}
			}
		}
		
	}
	
	
	
	/**
	 * Deletes all the images added by SiteSuperCharger.
	 *
	 * @since 2.2.0
	 *
	 * @global $wpdb
	 * @access Called by readConfig_deactivation()
	 *
	 * @param none
	 * @return none
	 */
	private static function deleteImages() {
		global $wpdb;

		$delete_image_ids = $wpdb->get_results("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_ssc_image'");
		foreach($delete_image_ids AS $delete_image_id){
			wp_delete_attachment($delete_image_id->post_id);
		}
	}


	/**
	 * Rewrite slug of all contnet created by SiteSuperCharger.
	 *
	 * @since 5.2.5
	 *
	 * @global $wpdb
	 *
	 * @param none
	 * @return none
	 */
	private static function reWriteSlugs(){
		global $wpdb;
		$content_ids = $wpdb->get_results( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_ssc_guid'" );
		self::logEvent("Rewriting slugs " . count($content_ids) . " pages/posts");
		if( !empty( $content_ids ) ){
			foreach( $content_ids AS $content_id ){
				wp_update_post( array(
	        		'ID' => $content_id->post_id,
	        		'post_name' => ''
	    		));
			}
		}
	}
	
	
	
	/**
	 * Calls the URL given by SSC to let SSC know the blog has posted
	 *
	 * @since 4.2.0
	 *
	 * @param string $url The full URL to be called.
	 * @return array First element is "true" if the callback was successfully received by SSC, "false" for anything else. Second element is an error message.
	 */
	private static function sscCallback($url){
		$result = wp_remote_get($url);
		if(is_wp_error($result)){
			$ret = array("false", $result->get_error_message());
		}else{
			$statusCode = $result['response']['code'];
			if($statusCode == 200){
				$result_parts = explode(",",trim($result['body'],"{}"));
				foreach($result_parts AS $result_part){
					$divPos = strpos($result_part,":");
					$key = substr($result_part,0,$divPos);
					$value = substr($result_part,$divPos+1);
					$results[trim($key,'"')] = trim($value,'"');
				}
				if($results["success"] == "true"){
					$ret = array("true","");
				}else{
					$ret = array("false",$results["message"]);
				}
				
			}
		}
		
		return $ret;
	}
	
	
	
	/**
	 * Adds a menu item for SiteSuperCharger to the WP Admin under the Settings menu
	 *
	 * @since 1.0.0
	 *
	 * @param none
	 * @return none
	 */
	public static function sscAdminMenu() {
		add_options_page( 'SiteSuperCharger Plugin Options', 'SiteSuperCharger', 'manage_options', 'ssc-admin-options', array('Ssc_SuperCharger','adminOptions') );
	}
	
	

	/**
	 * Checks if a given URL exists on a remote server
	 *
	 * @since 5.1.0
	 *
	 * @param string $url The full URL for the config.xml to check the modified date of.
	 * @return string The date the config.xml file was last modified.
	 */
	private static function getLastModified($url){
		$response = wp_remote_get($url);
		$last_modified = wp_remote_retrieve_header( $response, 'last-modified' );
		return $last_modified;
	}


	
	/**
	 * Checks if a given URL exists on a remote server
	 *
	 * @since 1.0.0
	 *
	 * @param string $url The full URL for the file to be checked for.
	 * @return bool True if the file exists, otherwise false.
	 */
	private static function remoteFileExists($url){
		$ret = false;
		
		$response = wp_remote_head($url);
		
		if(!is_wp_error($response)){
			$statusCode = $response['response']['code'];
			if($statusCode == 200){
				$ret = true;
			}
		}
		
		return $ret;
	}
	
	
	
	/**
	 * Loads XML data from external file
	 *
	 * @since 4.1.0
	 *
	 * @param string $filename The URL of the XML file to load
	 * @return XML object.
	 */
	private static function loadXML($filename){
		$xml = wp_remote_get($filename);
		return simplexml_load_string($xml['body']);
	}
	
	
	
	/**
	 * Sends event to the logging system
	 *
	 * @since 4.1.0
	 *
	 * @param string $message The text you want logged
	 * @param bool $new (optional) If true, a new log file is created.
	 * @return none
	 */
	private static function logEvent($message, $new = false){
		$attr = ($new)? "w" : "a";
		$myfile = fopen(plugin_dir_path(__FILE__) . "log.txt", $attr);
		fwrite($myfile, date_i18n("M j, Y g:i:sa",current_time('timestamp')) . ": " . $message . "\n");
		fclose($myfile);
	}
	
	
	
	/**
	 * Restarts the log file if it gets too large
	 *
	 * @since 5.0.0
	 *
	 * @param none
	 * @return none
	 */
	private static function truncateLogFile(){
		$bytes = filesize(plugin_dir_path(__FILE__) . "log.txt");
		if($bytes > 1000000){
			self::logEvent( "Log file truncated for size", true );
		}
	}

}