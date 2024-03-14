<?php








class HkPermaCat extends HkPermaCat_HkTools{

	public $isScategoryActivated=false;
	protected $startup = true;
	
	public $scategory_legacy_tag = '%scategory%';
	public $category_tag = '%category%';


	public function __construct(){
		parent::__construct();
		
		
		
		$this->setFilters();
	}
	
	public function startup(){
		$this->isScategoryActivated = class_exists('sCategoryPermalink');
	}
	
	
	public function setFilters(){
	
		/** Admin pages */
		add_action('admin_print_styles-post.php',		array($this, 'insertPostStyle'));
		add_action('admin_print_styles-post-new.php',	array($this, 'insertPostStyle'));
		
		add_action('admin_footer-post.php',		array($this, 'insertPostJS'));
		add_action('admin_footer-post-new.php',	array($this, 'insertPostJS'));
		
		add_action('admin_notices',array($this, 'scategory_warning'),1);

		/** Actions */
		add_action('transition_post_status', array($this, 'savePost'), -999, 3);
		
		/** Filters */
		add_filter('post_rewrite_rules', array($this, 'setLegacyVerbosePageRules'),0);
		add_filter('pre_post_link', array($this, 'parseLink'), 10, 2);	// filter only available on WP 3
		add_filter('post_link', array($this, 'parseLinkLegacy'), 7, 2);
	
	}
	
	
	
	
	public function insertPostStyle(){
		if(!$this->isScategoryActivated){
			echo "<style type=\"text/css\">.scategory_link{vertical-align:middle;display:none;cursor:pointer;cursor:hand}</style>\n";
		}
	}
	
	public function insertPostJS(){
		if(!$this->isScategoryActivated){
		
			$url = $this->plugin_dir_url . '/scategory_permalink.js';
			echo "<script type=\"text/javascript\" src=\"$url\"></script>\n";
			
			
			global $post;

			$categoryID = '';
			if($post->ID) {
				$categoryID = get_post_meta($post->ID, '_category_permalink', true);
			}
			echo "<script type=\"text/javascript\">jQuery('#categorydiv').sCategoryPermalink({current: '$categoryID'});</script>\n";
	
		}
	}

	public function savePost($new_status, $old_status, $post){
		
		// get parameter passed when post was saved
		$category_permalink = $_POST['scategory_permalink'];

		// if parameter was found, get all post categories and try to match passed category id with a post cat
		$found = false;
		if (isset($category_permalink)){
			$cats = wp_get_post_categories($post->ID);

			foreach($cats as $cid){
				if($cid == $category_permalink){
					$found = true;
					break;
				}
			}

		}

		// if we found a match, save/update this post category permalink
		if($found){
			if(!update_post_meta($post->ID, '_category_permalink', $category_permalink))
				add_post_meta($post->ID, '_category_permalink',  $category_permalink, true);
		}
		// if we didn't find a match, delete the postmeta if it exists
		//else{
//			delete_post_meta($post->ID, '_category_permalink');
		//}
}


	public function scategory_warning(){
		global $wp_version;
		$permastruct = get_option('permalink_structure');
		$has_legacy_tag = $this->hasNeedle($permastruct,$this->scategory_legacy_tag);
		$is_WP3 = $this->hasNeedle($wp_version,'3.');
		
		if($has_legacy_tag && $is_WP3){
			$permalink_admin = site_url('wp-admin/options-permalink.php', 'admin');
			$newPermastruct = str_replace($this->scategory_legacy_tag,$this->category_tag,$permastruct);
			
			if($this->hasNeedle($_SERVER['REQUEST_URI'],"options-permalink.php")){
?>
<div class="updated fade">
	<p>Your current Permalink Structure is <em><?php echo $permastruct; ?></em>, you are safe to replace it to <strong><?php echo $newPermastruct; ?></strong>.</p>
</div>
<?php		}
			else{
?>
<div class="updated fade">
	<p><strong>Hikari Category Permalink</strong> plugin noted you are using the legacy permalink tag <em>%scategory%</em>. This tag is not required anymore, and custom categories can be used in posts permalinks with the default <em>%category%</em> tag.</p><p>If you are using Wordpress 3.0 or newer and have no specific reason to use <em>%<strong>s</strong>category%</em>, please refere to your <a href="<?php echo $permalink_admin; ?>">Permalink admin page</a> and update your Permalink Structure.</p>
</div>
<?php		}
		}
	
	}


	public function setLegacyVerbosePageRules($post_rewrite){
		global $wp_rewrite;
		$has_scategory = $this->hasNeedle($wp_rewrite->permalink_structure,
					$this->scategory_legacy_tag);


		// this code is required when legacy (from sCategory Permalink plugin) %scategory% rewrite tag is present in permastruct
		// !!!!!!!!! IT HAS A BUG, multipaged comments are **NOT** supported when it's used !!!!!!!!!
		// to avoid it, just replace %scategory% from default %category% in /wp-admin/options-permalink.php
		if($has_scategory){
			$wp_rewrite->add_rewrite_tag($this->scategory_legacy_tag, '(.+?)', 'category_name=');

			if (preg_match("/^[^%]*%scategory%/", $wp_rewrite->permalink_structure) )
				$wp_rewrite->use_verbose_page_rules = true;

			$scategory_structure = $wp_rewrite->root . "%scategory%/%postname%";
			return array_merge($post_rewrite, $wp_rewrite->generate_rewrite_rules($scategory_structure));
		}
		else return $post_rewrite;
}

	// this filter is called *before* Wordpress starts converting permastruct into a permalink, it requires WP3's 'pre_post_link' filter to work
	public function parseLink($permalink, $post){
		if(!empty($permalink)
//					&& !in_array($post->post_status, array('draft', 'pending'))
				){

			// if sCategory Permalink plugin's legacy rewrite tag (%scategory%) is found on permastruct, replace it for default category tag (%category%)
			$permalink = str_replace($this->scategory_legacy_tag,$this->category_tag,$permalink);
			
			// if category tag (%category%) is found on permalink, replace it for our custom category permalink
			// else, just return $permalink, nothing must me changed
			if( $this->hasNeedle($permalink,$this->category_tag) ){
				$category_permalink = get_post_meta($post->ID, '_category_permalink', true);
				
				// if this post has a custom category permalink defined with postmeta, use it to replace the category tag (%category%)
				// else, if no postmeta is set, just return $permalink leaving the category tag (%category%) alone, so that Wordpress deals with it in its default
				if($category_permalink){
					$cat = get_category($category_permalink);

					// ouch! get_category() wasn't able to retrieve a category based on $category_permalink!
					//if that happened, we can't do much, return for now
					if(!empty($cat)){
						$category = $cat->slug;
						if($parent = $cat->parent)
							$category = get_category_parents($parent, false, '/', true) . $category;

						// just to avoid leaving a bug behind
						if(!empty($category)){
							$permalink = str_replace($this->category_tag, $category, $permalink);
						}
					
					}
				
				}
				
			}
			
		}
		return $permalink;
	}
	
	// this filter is called if the plugin is used in a Wordpress prior to version 3.0
	// in WP 3.0 and after, it should never be needed!
	public function parseLinkLegacy($permalink, $post){

		// first, let's run the main filter
		// if this plugin is used in Wordpress prior to version 3.0, it may return an incomplete permalink with a remaining default category tag (%category%)
		//		this happens when sCategory Permalink plugin's legacy rewrite tag (%scategory%) is in use
		//		and the post has no custom category permalink set in its postmeta
		$permalink = $this->parseLink($permalink, $post);

		
		// our last chance, now we must eliminate this little bastard tag if it still exists!!
		if( $this->hasNeedle($permalink,$this->category_tag) ){
			$category='';

			$cats = get_the_category($post->ID);
			usort($cats, '_usort_terms_by_ID'); // order by ID
			$cat = $cats[0];

			if(empty($cat)){
				$default_category = get_category(get_option('default_category'));
				$category = is_wp_error($default_category) ? '' : $default_category->slug;
			}
			else{
				$category = $cat->slug;
				if($parent = $cat->parent)
					$category = get_category_parents($parent, false, '/', true) . $category;
			}
		
			$permalink = str_replace($this->category_tag, $category, $permalink);
		}
	
		return $permalink;
	}

}

$hkPermaCat = new HkPermaCat();