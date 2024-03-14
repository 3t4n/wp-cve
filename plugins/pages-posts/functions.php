<?php
if(!function_exists('pagesPostsContentFilter'))
{
	/**
	 * Filter out content
	 * @access public
	 * @author Rich Gubby
	 * @since 1.1
	 * @return mixed
	 */
	function pagesPostsContentFilter($contentIn)
	{
		// If is the home page, an archive, or search results
		if(is_front_page() || is_archive() || is_search())
		{
			global $post;
			$content = $post->post_excerpt;
		}

		// If an excerpt is set in the Optional Excerpt box
		if($content)
		{
			$content = apply_filters('the_excerpt', $content);
		} else
		{
			$content = $contentIn;
			$content = str_replace(']]>', ']]&gt;', $content);
			$content = strip_tags($content);
			
			$excerpt_length = apply_filters('excerpt_length', 55);
			$excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
			
			$words = preg_split("/[\n\r\t ]+/", $content, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
			
			if ( count($words) > $excerpt_length ) 
			{
				array_pop($words);
				$content = implode(' ', $words);
				$content = $content.$excerpt_more;
			} else 
			{
				$content = implode(' ', $words);
			}
		}
		return $content;
	}
}

if(!function_exists('pagesPostsPageFilter'))
{
	function pagesPostsPageFilter()
	{
		global $existingContent;
		echo $existingContent;
	}
}

if(!function_exists('pagesPostsMenu'))
{
	/**
	 * Set the current page ID
	 * @access public
	 * @author Rich Gubby
	 * @since 1.5
	 * @return integer
	 */
	function pagesPostsMenu()
	{
		global $pages_posts_current_page_id;
		return $pages_posts_current_page_id;
	}
}
if(!function_exists('pagesPostsInit'))
{
	/**
	 * Initialize Pages Posts when viewing a page
	 * @access public
	 * @author Rich Gubby
	 * @since 1.0
	 * @return mixed
	 */
	function pagesPostsInit()
	{
		global $post;
		global $pages_posts_current_page_id;
		$pages_posts_current_page_id = $post->ID;
		
		$cat = false;
		$tag = false;
		
		$existingArr = pages_posts_get_settings();
		
		if(isset($existingArr[$post->ID]))
		{
			$type = $existingArr[$post->ID]['type'];
			switch($type)
			{
				case 'c': $cat = $existingArr[$post->ID]['val'];break;
				case 't': $tag = $existingArr[$post->ID]['val'];break;
			}

			// Set a global variable so we know we're using this plugin
			GLOBAL $IS_PAGES_POSTS;
			$IS_PAGES_POSTS = true;
		} else
		{
			return false;
		}
		
		// Display original text before posts
		if(get_option('page_posts_settings_show_text_'.$post->ID) === '1')
		{
			global $existingContent;
			$existingContent = $post->post_content;
			add_filter('loop_start', 'pagesPostsPageFilter');
		}
		
		// Display full post or excerpt
		$displayType = get_option('page_posts_settings_excerpt_'.$post->ID);

		if($displayType == 'excerpt')
		{
			add_filter('the_content', 'pagesPostsContentFilter');
		}
		
		// Temporarily add this page to the page_for_posts setting
		add_filter('pre_option_page_for_posts', 'pagesPostsMenu');
		
		// Setup how many posts per page we want
		if(!$postsPerPage = get_option('page_posts_settings_per_page_'.$post->ID))
		{
			$postsPerPage = get_option('posts_per_page');
		}
		
		// Show as a home page (no by default)
		if(!$showAsHome = get_option('page_posts_settings_show_as_home_'.$post->ID)) $showAsHome = false;
		
		switch($type)
		{
			case 'c':
				if($cat == 0)
				{
					global $wp_query;
					$args = array_merge(array('post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => $postsPerPage), $wp_query->query);
		
					unset($args['pagename']);
					unset($args['page_id']);
		
					$wp_query = null;
					unset($GLOBALS['wp_query']);
					$GLOBALS['wp_query'] =& new WP_Query();
					$GLOBALS['wp_query']->query($args);
					
					if($showAsHome)
					{
						// Flag as a home page
						$GLOBALS['wp_query']->is_home = true;
						$GLOBALS['wp_query']->is_archive = false;
						$GLOBALS['wp_query']->is_category = false;
					}
					
				} else if($cat > 0)
				{
					global $wp_query;
					$args = array_merge(array('cat' => $cat, 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => $postsPerPage), $wp_query->query);

					unset($args['pagename']);
					unset($args['page_id']);
		
					$wp_query = null;
					unset($GLOBALS['wp_query']);
					$GLOBALS['wp_query'] =& new WP_Query('is_home=true');
					$GLOBALS['wp_query']->query($args);
					
					if($showAsHome)
					{
						// Flag as a home page
						$GLOBALS['wp_query']->is_home = true;
						$GLOBALS['wp_query']->is_archive = false;
						$GLOBALS['wp_query']->is_category = false;
					}
				}
				break;
			case 't':
				if($tag == 0)
				{
					global $wp_query;
					$args = array_merge(array('post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => $postsPerPage), $wp_query->query);
		
					unset($args['pagename']);
					unset($args['page_id']);
		
					$wp_query = null;
					unset($GLOBALS['wp_query']);
					$GLOBALS['wp_query'] =& new WP_Query();
					$GLOBALS['wp_query']->query($args);
					
					if($showAsHome)
					{
						// Flag as a home page
						$GLOBALS['wp_query']->is_home = true;
						$GLOBALS['wp_query']->is_archive = false;
						$GLOBALS['wp_query']->is_category = false;
					}
					
				} else if($tag > 0)
				{
					global $wp_query;
					$args = array_merge(array('tag__in' => array($tag), 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => $postsPerPage), $wp_query->query);

					unset($args['pagename']);
					unset($args['page_id']);
		
					$wp_query = null;
					unset($GLOBALS['wp_query']);
					$GLOBALS['wp_query'] =& new WP_Query();
					$GLOBALS['wp_query']->query($args);
					
					if($showAsHome)
					{
						// Flag as a home page
						$GLOBALS['wp_query']->is_home = true;
						$GLOBALS['wp_query']->is_archive = false;
						$GLOBALS['wp_query']->is_category = false;
					}
				}
		}
	}
}

if(!function_exists('pagesPostsAdmin'))
{
	/**
	 * Pages Posts Admin Page
	 * 
	 * @access public
	 * @author Rich Gubby
	 * @since 1.0
	 * @return void
	 */
	function pagesPostsAdmin()
	{
		// Delete a page from the array
		if(isset($_GET['deletepage']) AND is_numeric($_GET['deletepage']) AND $_GET['deletepage'] > 0)
		{
			$existingArr = pages_posts_get_settings();
			unset($existingArr[$_GET['deletepage']]);
			update_option('page_posts_settings', pages_posts_get_settings_string(true, $existingArr));
			$updateOption = true;
			
			delete_option('page_posts_settings_excerpt_'.$_GET['deletepage']);
			delete_option('page_posts_settings_show_text_'.$_GET['deletepage']);
			delete_option('page_posts_settings_per_page_'.$_GET['deletepage']);
			delete_option('page_posts_settings_show_as_home_'.$_GET['deletepage']);
		}
	
		if (isset($_POST['info_update']) OR isset($_POST['info_update_add'])) 
		{			
			$updateOption = false;
			
			if(isset($_POST['pages_posts_new_page']) AND $_POST['pages_posts_new_page'] > 0 AND isset($_POST['pages_posts_new_cat']) AND isset($_POST['info_update_add']))
			{
				// Get existing option value
				$existing = get_option('page_posts_settings');
				
				switch($_POST['pages_posts_new_type'])
				{
					case 'c':$value = $_POST['pages_posts_new_cat'];break;
					case 't':$value = $_POST['pages_posts_new_tag'];break;
				}
				$existing .= $_POST['pages_posts_new_page'].'='.$_POST['pages_posts_new_type'].$value.';';
				
				update_option('page_posts_settings', $existing);
				$updateOption = true;
				
				if(isset($_POST['page_posts_new_excerpt']))
				{
					update_option('page_posts_settings_excerpt_'.$_POST['pages_posts_new_page'], $_POST['page_posts_new_excerpt']);
				}
				if(isset($_POST['page_posts_new_show_text']))
				{
					update_option('page_posts_settings_show_text_'.$_POST['pages_posts_new_page'], $_POST['page_posts_new_show_text']);
				}
				if(isset($_POST['page_posts_new_per_page']))
				{
					update_option('page_posts_settings_per_page_'.$_POST['pages_posts_new_page'], $_POST['page_posts_new_per_page']);
				}
				if(isset($_POST['page_posts_new_show_as_home']))
				{
					update_option('page_posts_settings_show_as_home_'.$_POST['pages_posts_new_page'], $_POST['page_posts_new_show_as_home']);
				}
			}
			
			if(isset($_POST['info_update']))
			{
			
				$newSettings = array();
				foreach($_POST as $key => $val)
				{
					if(preg_match('/^pages_posts_page_/i', $key, $matches))
					{
						$type = substr($key, strlen('pages_posts_page_'),1);
						$value = substr($key, (strlen('pages_posts_page_')+1), strlen($key));
						$newSettings[$value] = array('type' => $type, 'val' => $val);
						
						if(isset($_POST['page_posts_settings_excerpt_'.$value]))
						{
							update_option('page_posts_settings_excerpt_'.$value, $_POST['page_posts_settings_excerpt_'.$value]);
						}
						if(isset($_POST['page_posts_settings_show_text_'.$value]))
						{
							update_option('page_posts_settings_show_text_'.$value, $_POST['page_posts_settings_show_text_'.$value]);
						}
						if(isset($_POST['page_posts_settings_per_page_'.$value]))
						{
							update_option('page_posts_settings_per_page_'.$value, $_POST['page_posts_settings_per_page_'.$value]);
						}
						if(isset($_POST['page_posts_settings_show_as_home_'.$value]))
						{
							update_option('page_posts_settings_show_as_home_'.$value, $_POST['page_posts_settings_show_as_home_'.$value]);
						}
					}
				}
				
				update_option('page_posts_settings', pages_posts_get_settings_string(true, $newSettings));
				$updateOption = true;
			}
		}
		
		if(isset($updateOption) AND $updateOption == true)
		{
			echo "<div class='updated fade'><p>Pages Posts options updated.</p></div>";
		}
		
		echo '<div class="wrap">';
		echo '<div class="half1">';
		echo '<form method="post" action="options-general.php?page=pagesposts.php">';
		
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		$color = get_user_option('admin_color', $user_id);

		if($color == 'classic')
		{
			$icons = 'icons32-vs.png';
		} else
		{
			$icons = 'icons32.png';
		}
		echo '<div class="header" style="background:transparent url('.admin_url().'/images/'.$icons.') no-repeat scroll -312px -5px;">&nbsp;</div><h2>Pages Posts Settings</h2>';
		echo '<p><small>By: Rich Gubby</small></p>';
		echo '<table class="form-table" cellspacing="2" cellpadding="5">';
		
		// Settings in here
		echo '<tr><td><h3>Add Page to display Posts</h3></td></tr>';
	
		// Display pages to choose from 
		$pages = get_posts(array('post_type' => 'page', 'numberposts' => -1, 'orderby' => 'post_title', 'order' => 'ASC'));
		$existingArr = pages_posts_get_settings();
		
		$pagesArr = array();
		$pagesArr[0] = 'Please choose a page...';
		
		foreach($pages as $val)
		{
			if(!array_key_exists($val->ID, $existingArr))
			{
				$pagesArr[$val->ID] = $val->post_title;
			}
		}

		echo pages_posts_admin_option('select', array('label' => 'Page Name', 'name' => 'pages_posts_new_page', 'value' => 0, 'options' => $pagesArr, 'description' => '<br />Add new page to display posts'));
		echo pages_posts_admin_option('select', array('label' => 'Type', 'name' => 'pages_posts_new_type', 'value' => 'c', 'options' => array('c' => 'Category', 't' => 'Tag'), 'description' => '<br />Display posts from either a category or a tag'));

		// Display categories to choose from
		$cats = get_categories(array('echo' => 0));
		$catArray = array();
		$catArray[0] = 'All';
			
		foreach($cats as $val)
		{
			$catArray[$val->term_id] = $val->name;
		}
		
		// Categories
		echo pages_posts_admin_option('select', array('label' => 'Category', 'name' => 'pages_posts_new_cat', 'value' => 0, 'options' => $catArray, 'description' => '<br />If you\'re using categories, which category do you want to display on this page?'));
		
		$tags = get_tags(array('echo' => 0));
		$tagArray = array();
		$tagArray[0] = 'All';
		
		foreach($tags as $val)
		{
			$tagArray[$val->term_id] = $val->name;
		}
		
		// Tags
		echo pages_posts_admin_option('select', array('label' => 'Tag', 'name' => 'pages_posts_new_tag', 'value' => 0, 'options' => $tagArray, 'description' => '<br />If you\'re using tags, which tag do you want to display on this page?'));
		
		// Show full post or excerpt
		echo pages_posts_admin_option('select', array('label' => __('Excerpt or full post'), 'name' => 'page_posts_new_excerpt', 'value' => 'full', 'options' => array('full' => 'Full Post', 'excerpt' => 'Excerpt Only'), 'description' => '<br />Display the full post or just an excerpt'));
		
		// Show original page text before posts
		echo pages_posts_admin_option('select', array('label' => __('Show original page text before posts'), 'name' => 'page_posts_new_show_text', 'value' => 1, 'options' => array(0 => 'No', 1 => 'Yes'), 'description' => '<br />Display your original page text before the posts'));
		
		// Number of posts
		echo pages_posts_admin_option('input', array('label' => __('Posts per page'), 'name' => 'page_posts_new_per_page', 'size' => 5, 'value' => get_option('posts_per_page')));

		// Show as a home page
		echo pages_posts_admin_option('select', array('label' => __('Show as though a home page'), 'name' => 'page_posts_new_show_as_home', 'value' => 1, 'options' => array(0 => 'No', 1 => 'Yes'), 'description' => '<br />Display and look as though a home page'));
		
		echo '</table><table class="form-table" cellspacing="2" cellpadding="5" width="100%"><tr><td><p class="submit"><input class="button-primary" type="submit" name="info_update_add" value="Add Page" /></p></td></tr></table>';		
		echo '<table class="form-table" cellspacing="2" cellpadding="5">';
		echo '<tr><td colspan="2"><hr /></td></tr><tr><td><h3>Edit existing Pages</h3></td></tr>';
		
		$i = 1;
		$count = count($existingArr);
		foreach($existingArr as $page => $val)
		{
			// Page Name
			$pageObj = get_post($page);
			
			echo pages_posts_admin_option('text', array('value' => 'Configure Page & Post Settings for: <strong>'.$pageObj->post_title.'</strong>'));
			
			switch($val['type'])
			{
				case 'c': echo pages_posts_admin_option('select', array('label' => 'Display posts from Category', 'name' => 'pages_posts_page_'.$val['type'].$page, 'value' => $val['val'], 'options' => $catArray, 'description' => '<br />By specifying a category here, your page will display posts only from this category')); break;
				case 't': echo pages_posts_admin_option('select', array('label' => 'Display posts from Tag', 'name' => 'pages_posts_page_'.$val['type'].$page, 'value' => $val['val'], 'options' => $tagArray, 'description' => '<br />By specifying a tag here, your page will display posts only from this tag')); break;
			}
			
			// Show full post or excerpt
			echo pages_posts_admin_option('select', array('label' => __('Excerpt or full post'), 'name' => 'page_posts_settings_excerpt_'.$page, 'value' => get_option('page_posts_settings_excerpt_'.$page), 'options' => array('full' => 'Full Post', 'excerpt' => 'Excerpt Only'), 'description' => '<br />Display the full post or just an excerpt'));
		
			// Show original page text before posts
			echo pages_posts_admin_option('select', array('label' => __('Show original page text before posts'), 'name' => 'page_posts_settings_show_text_'.$page, 'value' => get_option('page_posts_settings_show_text_'.$page), 'options' => array(0 => 'No', 1 => 'Yes'), 'description' => '<br />Display your original page text before the posts'));
			
			// Number of posts
			if(!$postsPerPage = get_option('page_posts_settings_per_page_'.$page))
			{
				$postsPerPage = get_option('posts_per_page');
			}
			echo pages_posts_admin_option('input', array('label' => __('Posts per page'), 'size' => 5, 'name' => 'page_posts_settings_per_page_'.$page, 'value' => $postsPerPage));
			
			// Show as a home page
			echo pages_posts_admin_option('select', array('label' => __('Show as though a home page'), 'name' => 'page_posts_settings_show_as_home_'.$page, 'value' => get_option('page_posts_settings_show_as_home_'.$page), 'options' => array(0 => 'No', 1 => 'Yes'), 'description' => '<br />Display and look as though a home page'));
			
			// Delete option
			echo '<tr><td class="submitbox"><div id="delete-action"><a onclick="return confirm(\'Are you sure you want to delete?\')" href="options-general.php?page=pagesposts&deletepage='.$page.'" class="submitdelete deletion">Delete</a></div></td></tr>';
			
			if($count > 1 AND ($i < $count))
			{
				echo '<tr><td colspan="2"><hr /></td></tr>';
			}
			$i++;
		}
		
		if(empty($existingArr))
		{
			echo '<tr><td>You currently have no pages set to display posts - you can add one above</td></tr>';
		}
		
		echo '</table><table class="form-table" cellspacing="2" cellpadding="5" width="100%"><tr><td><p class="submit"><input class="button-primary" type="submit" name="info_update" value="Save Changes" /></p></td></tr></table>';
		echo '</form><p>&nbsp;</p></div>
		
		<div class="half2">
			<h3>Donate</h3>
			<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=rgubby%40googlemail%2ecom&lc=GB&item_name=Richard%20Gubby%20%2d%20WordPress%20plugins&currency_code=GBP&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted"><img class="floatright" src="'.WP_PLUGIN_URL.'/'.basename(dirname(__FILE__)).'/donate.png" /></a>
			<p>If you like this plugin, keep it Ad free and in a constant state of development by <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=rgubby%40googlemail%2ecom&lc=GB&item_name=Richard%20Gubby%20%2d%20WordPress%20plugins&currency_code=GBP&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted">donating</a> to the cause!</p> 
			<h3>Follow me</h3>
			<p>
			<a href="http://twitter.com/zqxwzq"><img class="floatleft" src="'.WP_PLUGIN_URL.'/'.basename(dirname(__FILE__)).'/follow.png" /></a>
			<p>I\'m on Twitter - make sure you <a href="http://twitter.com/zqxwzq">follow me</a>!</p>
			
			<h3>Other plugins you might like...</h3>
			<h4>Wapple Architect Mobile Plugin</h4>
			<a href="plugin-install.php?tab=search&type=term&s=wapple"><img class="floatright" src="'.WP_PLUGIN_URL.'/'.basename(dirname(__FILE__)).'/WAMP.png" alt="Wapple Architect Mobile Plugin" title="Wapple Architect Mobile Plugin" /></a>
			<p>The Wapple Architect Mobile Plugin for WordPress mobilizes your blog so your visitors can read your posts whilst they are on their mobile phone!</p>
			<p>Head over to <a href="http://wordpress.org/extend/plugins/wapple-architect/">http://wordpress.org/extend/plugins/wapple-architect/</a> and install it now
			or jump straight to the <a href="plugin-install.php?tab=search&type=term&s=wapple">Plugin Install Page</a></p>
			
			<h4>WordPress Mobile Admin</h4>
			<a href="plugin-install.php?tab=search&type=term&s=wordpress+mobile+admin+wapple"><img class="title floatleft" src="'.WP_PLUGIN_URL.'/'.basename(dirname(__FILE__)).'/WMA.png" alt="WordPress Mobile Admin" title="WordPress Mobile Admin" /></a>
			<p>WordPress Mobile Admin allows you to create posts from your 
			mobile, upload photots, moderate comments and perform basic post/page management.</p>
			<p>Download it from <a href="http://wordpress.org/extend/plugins/wordpress-mobile-admin/">http://wordpress.org/extend/plugins/wordpress-mobile-admin/</a> or
			jump straight to the <a href="plugin-install.php?tab=search&type=term&s=wordpress+mobile+admin+wapple">Plugin Install Page</a>
		
		</div>
		</div>';
	}
}

if(!function_exists('pagesPostsAddAdminPage'))
{
	/**
	 * Setup the Admin Page
	 * 
	 * @access public
	 * @author Rich Gubby
	 * @since 1.0
	 * @return void
	 */
	function pagesPostsAddAdminPage()
	{
		add_options_page('Pages Posts Options', 'Pages Posts', 'administrator', 'pagesposts', 'pagesPostsAdmin');
	}
}

if(!function_exists('pages_posts_admin_save_option'))
{
	/**
	 * Save an admin option
	 * 
	 * @param string $option
	 * @access public
	 * @author Rich Gubby
	 * @since 1.0
	 * @return void
	 */
	function pages_posts_admin_save_option($option)
	{		
		if($_POST[$option] != get_option($option))
		{
			update_option($option, $_POST[$option]);
			return true;
		}
	}
}

if(!function_exists('pages_posts_admin_option'))
{
	/**
	 * Display an admin option
	 * 
	 * @param string $type
	 * @param array $options
	 * @access public
	 * @author Rich Gubby
	 * @since 1.0
	 * @return void
	 */
	function pages_posts_admin_option($type, $options = array())
	{
		$string  = '<tr>';
		if($type != 'text')
		{
			$string .= '<th width="30%" valign="top">';
			
			if(isset($options['name']))
			{
				$string .= '<label for="'.$options['name'].'">'.$options['label'].': </label>';
			}
			$string .= '</th>';
			$string .= '<td>';
		}  else
		{
			$string .= '<td colspan="2">';
		}
		
		switch($type)
		{
			case 'input' : 
				if(!isset($options['size']))
				{
					$options['size'] = 40;
				}
				if(isset($options['before']) AND $options['before'] != '')
				{
					$string .= $options['before'];
				}
				$string .= '<input';
				
				if($options['size'] == 40)
				{
					$string .= ' class="regular-text"';
				}
				$string .= ' size="'.$options['size'].'" type="text" name="'.$options['name'].'" id="'.$options['name'].'" value="'.$options['value'].'" />';
				
				if(isset($options['after']) AND $options['after'] != '')
				{
					$string .= $options['after'];
				}
				break;
			case 'select' :
				$string .= '<select name="'.$options['name'].'">';
				
				foreach($options['options'] as $key => $val)
				{
					$string .= '<option value="'.$key.'"';
				
					if($key == $options['value'])
					{
						$string .= ' selected="selected"';
					}
					
					$string .= '>'.$val.'</option>';
				}
				
				$string .= '</select>';
				break;
			case 'text' :
				$string .= '<p>';
				if(isset($options['bold'])) $string .= '<strong>';
				if(isset($options['italic'])) $string .= '<em>';
				
				$string .= $options['value'];
				
				if(isset($options['italic'])) $string .= '</em>';
				if(isset($options['bold'])) $string .= '</strong>';
				$string .= '</p>';
				break;
		}
		
		if(isset($options['description']) && $type != 'text')
		{
			$string .= '<span class="description">'.$options['description'].'</span>';
		}
		
		$string .= '</td></tr>';
		return $string;
	}
}

if(!function_exists('pages_posts_get_settings'))
{
	/**
	 * Get the Pages Posts Settings
	 * 
	 * Stored in a database in key=val; pairs
	 * 
	 * @access public
	 * @author Rich Gubby
	 * @since 1.0
	 * @return array
	 */
	function pages_posts_get_settings()
	{
		$existing = get_option('page_posts_settings');
		$existingArr = array();
		
		foreach(explode(';', $existing) as $val)
		{
			
			if(isset($val) AND $val != '')
			{
				list($page,$cat) = explode('=', $val);
				$existingArr[$page] = array('type' => substr($cat,0,1), 'val' => substr($cat,1,strlen($cat)));
			}
		}
		
		return $existingArr;
	}
}

if(!function_exists('pages_posts_get_settings_string'))
{
	/**
	 * Return a stringified version of Pages Posts Settings - pass in newVals to create a new string
	 * 
	 * @param boolean $setNewVals
	 * @param array $newVals
	 * @access public
	 * @author Rich Gubby
	 * @since 1.0
	 * @return string
	 */
	function pages_posts_get_settings_string($setNewVals = false, $newVals = array())
	{
		$string = '';
		
		if($setNewVals === false)
		{
			$newVals = pages_posts_get_settings();
		}
		
		foreach($newVals as $page => $cat)
		{
			$string .= $page.'='.$cat['type'].$cat['val'].';';
		}
		
		return $string;
	}
}


if(!function_exists('pages_posts_register_head'))
{
	/**
	 * Setup admin CSS
	 * 
	 * @access public
	 * @author Rich Gubby
	 * @since 1.0
	 * @return void
	 */
	function pages_posts_register_head()
	{
		$url = WP_PLUGIN_URL.'/'.basename(dirname(__FILE__)).'/pagesposts.css';
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$url."\" />\n";	
	}
}