<?php

/*
Plugin Name: SEO Super Comments
Version: 0.7.2
Plugin URI: http://www.prelovac.com/vladimir/wordpress-plugins/seo-super-comments
Author: Vladimir Prelovac
Author URI: http://www.prelovac.com/vladimir
Description: Use your blog comments to get more traffic.

*/

// credits 
// http://scott.sherrillmix.com/blog/blogger/creating-a-better-fake-post-with-a-wordpress-plugin/
// http://www.semiologic.com/software/wp-tweaks/dofollow/

// Avoid name collisions.
if (!class_exists('SEOSuperComments')):
    class SEOSuperComments
    {
        
        // Name for our options in the DB
        var $DB_option = 'seo-super-comments';
        
        
        // Initialize WordPress hooks
        function SEOSuperComments()
        {
            add_filter('get_comment_author_link', array(
                &$this,
                'SEOSuperComments_author_link'
            ), 10);
            //add_filter('comment_text',  array(&$this, 'SEOSuperComments_process_text'), 10);						
            add_filter('single_post_title', array(
                &$this,
                'SEOSuperComments_post_title'
            ), 10);
            add_action('parse_query', array(
                &$this,
                'parse'
            ));
            //add_filter('the_posts',array(&$this,'detectPost'));
            
            add_action('admin_menu', array(
                &$this,
                'SEOSuperComments_admin_menu'
            ));
        }
        
        // credits Denis de Bernardy
        function strip_nofollow($text = '')
        {
            return preg_replace_callback("/<\s*a\s+(.+?)>/is", array(
                &$this,
                'strip_nofollow_callback'
            ), $text);
        }
        
        function strip_nofollow_callback($match)
        {
            $attr = $match[1];
            $attr = " $attr ";
            $attr = preg_replace("/
		\s
		rel\s*=\s*(['\"])
		([^\\1]*?\s+)?
		nofollow
		(\s+[^\\1]*?)?
		\\1
		/ix", " rel=$1$2$3$1", $attr);
            $attr = preg_replace("/
		\s
		rel\s*=\s*(['\"])\s*\\1
		/ix", '', $attr);
            $attr = trim($attr);
            return '<a ' . $attr . '>';
        }
        
        function SEOSuperComments_GetCommentators()
        {
            global $wpdb;
            
            $days        = 30;
            $mincomcount = 5;
            
            
            $sql = "SELECT COUNT(comment_ID) AS total_comments, comment_author, comment_author_email, comment_author_url FROM $wpdb->comments 
				WHERE comment_approved='1' AND comment_author_email<>'' AND (comment_date>DATE_ADD(CURDATE(), INTERVAL -$days DAY)) 
				GROUP BY comment_author_email ORDER BY total_comments DESC, comment_author ASC";
            $rs  = mysql_query($sql);
            while ($row = mysql_fetch_assoc($rs)) {
                if ($row['total_comments'] >= $mincomcount)
                    echo $row['comment_author'] . ' ' . $row['comment_author_email'] . ' ' . $row['comment_author_url'] . ' ' . $row['total_comments'] . '<br/>';
            }
            
        }
        
        function SEOSuperComments_author_link($link)
        {
            global $comment, $wpdb;
            
            
            if (is_admin() || get_comment_type() != 'comment')
                return $link;
            
            
            //if (strlen(strip_tags($comment->comment_content)) < 60)
            //return $link;
            
            $options     = $this->get_options();
            $days        = 30;
            $mincomcount = 5;
            
            
            foreach (explode("\n", $options['specialurls']) as $line) {
                if (trim($line) && $link && strpos($link, trim($line)) !== FALSE)
                    return $this->strip_nofollow($link);
            }
            
            $comcount = $wpdb->get_var($wpdb->prepare("select count(1) as comments from $wpdb->comments where comment_approved = '1' and `comment_author_url` LIKE '%s' and `comment_author_email` = '%s' and `comment_author` LIKE '%s' and comment_date>DATE_ADD(CURDATE(), INTERVAL -$days DAY)", $comment->comment_author_url, $comment->comment_author_email, $comment->comment_author));
            
            
            if ($comcount < $mincomcount)
                return strip_tags($link);
            else
                return $this->strip_nofollow($link);
            
            $cid = $comment->comment_ID;
            
            if (strpos($link, 'http://') === false)
                $link = '<a href="?cid=' . $cid . '">' . $link . '</a>';
            else {
                $link = preg_replace('/href=(\'|")([^\'"]+)/', 'href=$1?cid=' . $cid, $link);
                $link = preg_replace('/
		(
			<a
			\s+
			.*
			\s+
			rel=["\']
			[a-z0-9\s\-_\|\[\]]*
		)
		(
			\b
			nofollow
			\b
		)
		(
			[a-z0-9\s\-_\|\[\]]*
			["\']
			.*
			>
		)
		/isUx', "$1$3", $link);
                
                # clean up rel=""
                $link = str_replace(array(
                    ' rel=""',
                    " rel=''"
                ), '', $link);
                
            }
            if ($comment->comment_author_url && $comment->comment_author_url != 'http://') {
                $url  = parse_url($comment->comment_author_url);
                $host = str_replace('http://', '', str_replace('www.', '', $url['host']));
            } else
                $host = '';
            
            return $link . ($options['show_url'] == 'on' ? ' <span class="ssc_url">' . strtolower($host) . '</span>' : '');
        }
        
        
        function SEOSuperComments_process_text($text)
        {
            global $comment, $post;
            
            //if (strlen(strip_tags($text))<60)
            //return $text;
            
            
            $cid    = $comment->comment_ID;
            $anchor = $this->GetExcerpt($text, 4);
            
            $text = $text . '<div style="float:right"><a href="?cid=' . $cid . '">' . $anchor . '</a></div>';
            
            return ($text);
            
        }
        
        function SEOSuperComments_post_title($title)
        {
            if ($_REQUEST['cid']) {
                $mycomment = get_comment($_REQUEST['cid']);
                $mypost    = get_post($mycomment->comment_post_ID);
                
                return $this->GetExcerpt($mycomment->comment_content, 8);
            } else
                return $title;
        }
        
        
        
        function parse()
        {
            global $wp_query; // <-- important query stuff in here
            
            if ($_REQUEST['cid'] > 0) {
                add_action('template_redirect', array(
                    &$this,
                    'TemplateRedirect'
                ));
            }
        }
        // Handle our options
        function get_options()
        {
            
            $options = array(
                'show_url' => '',
                'template' => 'single.php',
                'nofollow' => '',
                'specialurls' => ''
            );
            
            $saved = get_option($this->DB_option);
            
            
            if (!empty($saved)) {
                foreach ($saved as $key => $option)
                    $options[$key] = $option;
            }
            
            if ($saved != $options)
                update_option($this->DB_option, $options);
            
            return $options;
            
        }
        
        
        
        // Set up everything
        function install()
        {
            
        }
        
        function SEOSuperComments_admin_menu()
        {
            add_options_page('SEO Super Comments Options', 'SEO Super Comments', 'manage_options', basename(__FILE__), array(
                &$this,
                'handle_options'
            ));
        }
        
        function GetExcerpt($text, $length = 20)
        {
            $text  = strip_tags($text);
            $words = explode(' ', $text, $length + 1);
            if (count($words) > $length) {
                array_pop($words);
                $text = implode(' ', $words);
            }
            return ucfirst($text);
        }
        
        
        function get_author_comments($pid, $author, $cid, $email)
        {
            global $wpdb;
            
            $result = '';
            $author = addslashes($author);
            
            $comments = $wpdb->get_results($wpdb->prepare("SELECT comment_author, comment_author_url, comment_content, comment_author_email FROM $wpdb->comments WHERE comment_approved = '1' AND comment_author_email ='%s' AND comment_post_ID = '$pid' AND NOT comment_ID='$cid' ORDER BY comment_date_gmt DESC LIMIT 5", $email));
            if ($comments) {
                $result .= '<p><h4>' . $author . ' also commented</h4></p>';
                $result .= "<ul>";
                foreach ($comments as $comment) {
                    $result .= '<li>' . $comment->comment_content . '</li>';
                }
                $result .= "</ul>";
            }
            
            
            $comments = $wpdb->get_results($wpdb->prepare("SELECT comment_author, comment_author_url, comment_content, comment_post_ID, comment_ID, comment_author_email FROM $wpdb->comments WHERE comment_approved = '1' AND comment_author_email ='%s' AND NOT comment_post_ID = '$pid' ORDER BY comment_date_gmt DESC LIMIT 5", $email));
            if ($comments) {
                $result .= '<p><h4>Recent comments by ' . $author . '</h4></p>';
                $result .= "<ul>";
                foreach ($comments as $comment) {
                    
                    $result .= '<li><a href="' . clean_url(get_comment_link($comment->comment_ID), null, 'display') . '">' . get_the_title($comment->comment_post_ID) . '</a><br />' . $comment->comment_content . '</li>';
                }
                $result .= "</ul>";
            }
            
            return $result;
            
        }
        
        
        function CreatePost()
        {
            $mycomment = get_comment($_REQUEST['cid']);
            $mypost    = get_post($mycomment->comment_post_ID);
            $options   = $this->get_options();
            
            if ($options['nofollow'])
                $rel = " rel='nofollow' ";
            
            
            /**
             * Create a fake post.
             */
            $post = new stdClass;
            
            /**
             * The author ID for the post. Usually 1 is the sys admin. Your
             * plugin can find out the real author ID without any trouble.
             */
            $post->post_author = 1;
            
            /**
             * The safe name for the post. This is the post slug.
             */
            //$post->post_name = $this->page_slug;
            
            /**
             * Not sure if this is even important. But gonna fill it up anyway.
             */
            //$post->guid = get_bloginfo('wpurl') . '/' . $this->page_slug;
            
            /**
             * The title of the page.
             */
            $post->post_title = $this->GetExcerpt($mycomment->comment_content, 8) . ' ...';
            
            /**
             * This is the content of the post. This is where the output of
             * your plugin should go. Just store the output from all your
             * plugin function calls, and put the output into this var.
             */
            if ($mycomment->comment_author_url)
                $author_link = '<span class="ssc_info">Comment posted on <a href="' . get_permalink($mypost->ID) . '">' . $mypost->post_title . '</a> by <a ' . $rel . ' href="' . $mycomment->comment_author_url . '">' . $mycomment->comment_author . '</a></span>';
            else
                $author_link = '<span class="ssc_info">Comment posted <a href="' . get_permalink($mypost->ID) . '">' . $mypost->post_title . '</a> by ' . $mycomment->comment_author . '.</span>';
            
            //$post_link='Read the original post: <a href="'.get_permalink($mypost->ID).'">'.$mypost->post_title.'</a>'; 	
            
            $author_comments = $this->get_author_comments($mypost->ID, $mycomment->comment_author, $mycomment->comment_ID, $mycomment->comment_author_email);
            
            $post->post_content = "<p>$author_link</p><p>$post_link</p>" . $mycomment->comment_content . "<p>$author_comments</p>";
            
            
            /**
             * Fake post ID to prevent WP from trying to show comments for
             * a post that doesn't really exist.
             */
            $post->ID = $mypost->ID;
            
            /**
             * Static means a page, not a post.
             */
            $post->post_status = 'static';
            
            
            /**
             * Turning off comments for the post.
             */
            $post->comment_status = 'closed';
            
            /**
             * Let people ping the post? Probably doesn't matter since
             * comments are turned off, so not sure if WP would even
             * show the pings.
             */
            $post->ping_status = 'closed';
            
            $post->comment_count = 0;
            
            /**
             * You can pretty much fill these up with anything you want. The
             * current date is fine. It's a fake post right? Maybe the date
             * the plugin was activated?
             */
            $post->post_date     = current_time('mysql');
            $post->post_date_gmt = current_time('mysql', 1);
            
            return $post;
        }
        
        /**
         * Called by the 'template_redirect' action
         */
        function TemplateRedirect()
        {
            global $wp_query;
            
            /**
             * Make sure the user selected template file actually exists. If
             * not we're kinda screwed.
             */
            
            $options = $this->get_options();
            $page    = $options['template'];
            
            /*if (is_single())
            $page='single.php';
            else if (is_page())
            $page='page.php';
            */
            
            if (!file_exists(TEMPLATEPATH . '/' . $page))
                $page = 'index.php';
            
            
            /**
             * What we are going to do here, is create a fake post. A post
             * that doesn't actually exist. We're gonna fill it up with
             * whatever values you want. The content of the post will be
             * the output from your plugin. The questions and answers.
             */
            $post = $this->CreatePost();
            
            
            /**
             * Clear out any posts already stored in the $wp_query->posts array.
             */
            $wp_query->posts      = array();
            $wp_query->post_count = 0;
            
            
            
            /**
             * Now add our fake post to the $wp_query->posts var. When ?The Loop?
             * begins, WordPress will find one post: The one fake post we just
             * created.
             */
            $wp_query->posts[]    = $post;
            $wp_query->post_count = 1;
            
            
            /**
             * And load up the template file.
             */
            load_template(TEMPLATEPATH . '/' . $page);
            
            /**
             * YOU MUST DIE AT THE END. BAD THINGS HAPPEN IF YOU DONT
             */
            die();
            
        }
        
        
        function handle_options()
        {
            
            $options = $this->get_options();
            if (isset($_POST['submitted'])) {
                
                check_admin_referer('seo-super-comments');
                
                $options['show_url']    = $_POST['show_url'];
                $options['nofollow']    = $_POST['nofollow'];
                $options['template']    = $_POST['template'];
                $options['specialurls'] = $_POST['specialurls'];
                
                
                update_option($this->DB_option, $options);
                echo '<div class="updated fade"><p>Plugin settings saved.</p></div>';
            }
            
            $action_url  = $_SERVER['REQUEST_URI'];
            $show_url    = $options['show_url'] == 'on' ? 'checked' : '';
            $nofollow    = $options['nofollow'] == 'on' ? 'checked' : '';
            $template    = $options['template'];
            $specialurls = stripslashes($options['specialurls']);
            
            $nonce = wp_create_nonce('seo-super-comments');
            
            $imgpath = trailingslashit(get_option('siteurl')) . 'wp-content/plugins/seo-super-comments/i';
            echo <<<END
<div class="wrap smartyoutube" >
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>SEO Super Comments</h2>
	<div id="poststuff" style="margin-top:10px;">
		 <div id="sideblock" style="float:right;width:270px;margin-left:10px;"> 

		 <iframe width=270 height=800 frameborder="0" src="http://www.prelovac.com/plugin/news.php?id=9&utm_source=plugin&utm_medium=plugin&utm_campaign=SEO%2BSuper%2BComments"></iframe>

 	</div>


	 <div id="mainblock" style="width:710px">
	 
		<div class="dbx-content">
		 	<form name="SEOSC" action="$action_url" method="post">
		 		  <input type="hidden" id="_wpnonce" name="_wpnonce" value="$nonce" />
					<input type="hidden" name="submitted" value="1" /> 
					<h2>Overview</h2>
					
					<p>SEO Super Comments will automatically turn your comments into new pages, indexable in search engines.</p>
					
					<input type="checkbox" name="show_url" $show_url /><label for="show_url"> Show comment author urls</label>  <br>
					
					<input type="checkbox" name="nofollow" $nofollow /><label for="nofollow"> Nofollow author URL</label>  <br>
          
          <br /><label for="template">Template file in use</label><br>
          <input style="border:1px solid #D1D1D1; width:165px;"  id="template" name="template" value="$template"/>
 					<br />
 					<br />
 					
 					The following comment author sites should have their links intact (and dofollowed). Type in websites without http:// and www. (ie. only google.com). One site per line.
					<textarea name="specialurls" id="specialurls" rows="15" cols="90" >$specialurls</textarea>
					<br><br> 
					
					<div class="submit"><input type="submit" name="Submit" value="Update options" class="button-primary" /></div>					
			</form>
		</div>
		
		<br/><br/><h3>&nbsp;</h3>	
	 </div>

	</div>
	
<h5>WordPress experimental plugin by <a href="http://www.prelovac.com/vladimir/">Vladimir Prelovac</a></h5>
</div>
END;
            $this->SEOSuperComments_GetCommentators();
            
        }
        
        
        function detectPost($posts)
        {
            global $wp;
            global $wp_query;
            /**
             * Check if the requested page matches our target 
             */
            
            if (
            //strtolower($wp->request) == strtolower($this->page_slug) //|| 
                
            //$wp->query_vars['cid'] 
                $_REQUEST['cid'] > 0) {
                //Add the fake post
                $posts   = NULL;
                $posts[] = $this->CreatePost();
                
                /**
                 * Trick wp_query into thinking this is a page (necessary for wp_title() at least)
                 * Not sure if it's cheating or not to modify global variables in a filter 
                 * but it appears to work and the codex doesn't directly say not to.
                 */
                $wp_query->is_page             = true;
                //Not sure if this one is necessary but might as well set it like a true page
                $wp_query->is_singular         = true;
                $wp_query->is_home             = false;
                $wp_query->is_archive          = false;
                $wp_query->is_category         = false;
                //Longer permalink structures may not match the fake post slug and cause a 404 error so we catch the error here
                //unset($wp_query->query["error"]);
                $wp_query->query_vars["error"] = "";
                $wp_query->is_404              = false;
                
            }
            return $posts;
        }
        
    }
endif;

if (class_exists('SEOSuperComments')):
    $SEOSuperComments = new SEOSuperComments();
    if (isset($SEOSuperComments)) {
        register_activation_hook(__FILE__, array(
            &$SEOSuperComments,
            'install'
        ));
    }
endif;

?>