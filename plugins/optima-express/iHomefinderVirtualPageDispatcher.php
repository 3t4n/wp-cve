<?php

/**
 *
 * This singleton class is used to filter the content of iHomefinder VirtualPages.
 * We use the iHomefinderVirtualPageFactory class to retrieve the
 * proper VirtualPage implementation.
 *
 * @author ihomefinder
 */
class iHomefinderVirtualPageDispatcher {
	
	private static $instance;
	
	private $virtualPage = null;
	private $content = null;
	private $excerpt = null;
	private $title = null;
	private $initialized = false;
	private $enqueueResource;
	private $displayRules;
	
	private function __construct() {
		$this->enqueueResource = iHomefinderEnqueueResource::getInstance();
		$this->displayRules = iHomefinderDisplayRules::getInstance();
	}
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	private function initialize() {
		global $wp_query;
		$postsCount = $wp_query->post_count;
		$type = get_query_var(iHomefinderConstants::IHF_TYPE_URL_VAR);
		//we only try to initialize, if we are accessing a virtual page
		//which does not have any true posts in the global posts array	
		
		if(!$this->initialized && $postsCount === 0 && !empty($type)) {
			$this->initialized = true;
			$wp_query->is_page = true;
			$wp_query->is_singular = true;
			$wp_query->posts = $this->postCleanUp(null);
			$wp_query->post_count = 1;
			$wp_query->found_posts = 1;
			$this->virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage($type);
			$this->virtualPage->getContent();
			$this->content = (string) $this->virtualPage->getBody();
			$this->excerpt = (string) $this->virtualPage->getBody();
			$this->title = (string) $this->virtualPage->getTitle();
			if(!$this->displayRules->isKestrelAll()) {
				$this->enqueueResource->addToHeader($this->virtualPage->getHead());
			}
			$this->enqueueResource->addToMetaTags($this->virtualPage->getMetaTags());
			//turn off some filters on ihf pages
			$this->removeFilters();
			$this->removeCaching();
		}
	}
	
	/**
	 * removes filters that can cause issues on virtual pages
	 */
	private function removeFilters() {
		$tags = array(
			"the_content",
			"the_excerpt"
		);
		$functionNames = array(
			"wpautop",
			"wptexturize",
			"convert_chars"
		);
		foreach($tags as $tag) {
			foreach($functionNames as $functionName) {
				remove_filter($tag, $functionName);
			}
		}
	}
	
	/**
	 * disables caching plugins on virtual pages
	 */
	private function removeCaching() {
		$constants = array(
			"DONOTCACHEPAGE",
			"DONOTCACHEDB",
			"DONOTMINIFY",
			"DONOTCDN",
			"DONOTCACHCEOBJECT"
		);
		foreach($constants as $constant) {
			if(!defined($constant)) {
				define($constant, true);
			}
		}
	}
	
	/**
	 * Cleanup state after filtering. This fixes an issue
	 * where widgets display different loop content, such
	 * as featured posts.
	 */
	private function afterFilter() {
		$this->initialized = false;
	}
	
	/**
	 * We identify iHomefinder requests based on the query_var
	 * iHomefinderConstants::IHF_TYPE_URL_VAR.
	 * Set the proper title and update the posts array to contain only
	 * a single posts. This will get updated in another action later
	 * during processing. We cannot set the post content here, because
	 * Wordpress does some odd formatting of the post_content, if we
	 * add it here (see the getContent method below, where content is properly set)
	 *
	 * @param $posts
	 */
	public function postCleanUp($posts) {
		$this->initialize();
		if($this->initialized) {
			$post = new stdClass();
			$post->post_author = 0;
			$post->post_name = "";
			$post->post_type = "page";
			$post->post_title = $this->title;
			$post->post_date = current_time("mysql");
			$post->post_date_gmt = current_time("mysql", 1);
			$post->post_content = $this->content;
			$post->post_excerpt = $this->excerpt;
			$post->post_status = "publish";
			$post->comment_status = "closed";
			$post->ping_status = "closed";
			$post->post_password = "";
			$post->post_parent = -1;
			$post->post_modified = current_time("mysql");
			$post->post_modified_gmt = current_time("mysql", 1);
			$post->comment_count = 0;
			$post->menu_order = 0;
			$post->post_category = array(1); // the default "Uncategorized"
			$post->ID = 0;
			$posts = array($post);
		}
		return $posts;
	}
	
	/**
	 * Sets the page template used for our virtual pages
	 * The page templates are set in Wordpress admin.
	 * 
	 * @param $pageTemplate
	 */
	public function getPageTemplate($pageTemplate) {
		$this->initialize();
		if($this->initialized) {
			$virtualPageTemplate = $this->virtualPage->getPageTemplate();
			//If the $virtualPageTemplate is NOT empty, then reset $pageTemplate
			if(!empty($virtualPageTemplate)) {
				$templates = array($virtualPageTemplate);
				//gets the disk location of the template
				$pageTemplate = locate_template($templates); 
			}				
		}
		return $pageTemplate;
	}
	
	/**
	 * For the ihf plugin page, we replace the content, with data retrieved from the iHomefinder servers.
	 *
	 * @param $content
	 */
	public function getContent($content) {
		$this->initialize();
		if($this->initialized) {
			$content = $this->content;
		}
		//reset init params
		$this->afterFilter();
		return $content;
	}
	
	/**
	 * For the ihf plugin page, we replace the excerpt, with data retrieved from the iHomefinder servers.
	 *
	 * @param $content
	 */
	public function getExcerpt($excerpt) {
		$this->initialize();
		if($this->initialized) {
			$excerpt = $this->excerpt;
		}
		//reset init params
		$this->afterFilter();
		return $excerpt;
	}
	
	/**
	 * If this is a virtual page, clear out any comments
	 */
	public function clearComments($comments) {
		if(get_query_var(iHomefinderConstants::IHF_TYPE_URL_VAR)) {
			$comments = array();
		}
		return $comments;
	}
	
}