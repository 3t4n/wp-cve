<?php
/*
Plugin Name: WP Post Navigation
Version: 1.2.4
Description: Show Previous and Next Post Links at Posts.
Author: Anas Mir
Author URI: http://sharp-coders.com/author/anasmir
Plugin URI: http://sharp-coders.com/wp-post-navigation
*/

/*Check Version*/
global $wp_version;
$exit_msg="WP Requires Latest version, Your version is old";
if(version_compare($wp_version, "3.0", "<"))
{
	exit($exit_msg);
}

if(!class_exists('WPPostNavigation')):
	class WPPostNavigation{
		private $config = array('hasImage'=>'','prePost'=>'','nextPost'=>'','is_reversed'=>false,'is_active'=>false);
		function load_settings()
		{
			$options = $this->get_wp_post_navigation_options();
			$navi = $options['nav_within_cat'] == "1"? true: false;
			$next_post = get_next_post($navi);
			$pre_post = get_previous_post($navi);
			if($options['navi_img'] != "1")
			{
				if($options['is_custom'] != "1"){
					$pre_navigation = $pre_post->ID!=""?'<a href="'. get_permalink($pre_post->ID).'">'.$pre_post->post_title.'</a>':'';
					$next_navigation = $next_post->ID!=""?'<a href="'. get_permalink($next_post->ID).'">'.$next_post->post_title.'</a>':'';
				}else{
					$pre_navigation = $pre_post->ID!=""?'<a href="'. get_permalink($pre_post->ID).'">'.$options['custom_pre'].'</a>':'';
					
					$next_navigation = $next_post->ID!=""?'<a href="'. get_permalink($next_post->ID).'">'.$options['custom_next'].'</a>':'';
				}
					
			}else{
				$pre_navigation = $pre_post->ID!=""?'<a href="'. get_permalink($pre_post->ID).'"><img src="'.$options['pre_img_link'].'" /></a>':'';
				$next_navigation = $next_post->ID!=""?'<a href="'. get_permalink($next_post->ID).'"><img src="'.$options['next_img_link'].'" /></a>':'';
			}
			$img = $options['navi_img'] == "1"? "-1": '';
			$this->config["is_reversed"] = $options['is_reversed'];
			$this->config["hasImage"] = $img;
			$nex = ($this->config["is_reversed"] == "1")?$next_navigation:$pre_navigation;
			$pre = ($this->config["is_reversed"] == "1")?$pre_navigation:$next_navigation;
			$this->config["prePost"] = $pre;
			$this->config["nextPost"] = $nex;
			$this->config["is_active"] = $options['is_active'];
			$this->config["position"] = $options['position'];
		}
		function WP_Pre_Next_Navigation($content)
		{
			global $post;
			$this->load_settings();
			if($this->config["is_active"] == "1"){
				if(is_single()){
					switch($this->config["position"]){
							case "both":
								return '<div class="wp-post-navigation">
									   <div class="wp-post-navigation-pre'.$this->config["hasImage"].'">
									   '.$this->config["prePost"].'
									   </div>
									   <div class="wp-post-navigation-next'.$this->config["hasImage"].'">
									   '.$this->config["nextPost"].'
									   </div>
									</div>'.$content.'<div class="wp-post-navigation">
									   <div class="wp-post-navigation-pre'.$this->config["hasImage"].'">
									   '.$this->config["prePost"].'
									   </div>
									   <div class="wp-post-navigation-next'.$this->config["hasImage"].'">
									   '.$this->config["nextPost"].'
									   </div>
									</div>';
								break;
							case "top":
									return '<div class="wp-post-navigation">
									   <div class="wp-post-navigation-pre'.$this->config["hasImage"].'">
									   '.$this->config["prePost"].'
									   </div>
									   <div class="wp-post-navigation-next'.$this->config["hasImage"].'">
									   '.$this->config["nextPost"].'
									   </div>
									</div>'.$content;
								break;
							case "bottom":
								return $content.'<div class="wp-post-navigation">
									   <div class="wp-post-navigation-pre'.$this->config["hasImage"].'">
									   '.$this->config["prePost"].'
									   </div>
									   <div class="wp-post-navigation-next'.$this->config["hasImage"].'">
									   '.$this->config["nextPost"].'
									   </div>
									</div>';
								break;
							default:
								return $content;
								break;
					}
				}
			}
			return $content;
		}
		function WP_Custom_Post_Navigation()
		{
			if(is_single())
			{
				return '<div class="wp-post-navigation">
						   <div class="wp-post-navigation-pre'.$this->config["hasImage"].'" >
						   '.$this->config["prePost"].'
						   </div>
						   <div class="wp-post-navigation-next'.$this->config["hasImage"].'">
						   '.$this->config["nextPost"].'
						   </div>
						</div>';
			}
		}
		function handle_wp_post_navigation_options()
		{
			$settings = $this->get_wp_post_navigation_options();
			if (isset($_POST['submitted']))
			{
				//check security
				check_admin_referer('wp-post-navigation-by-sharp-coders');
				$settings['nav_within_cat'] = isset($_POST['nav_within_cat'])? "1" : "0" ;
				$settings['is_active'] = isset($_POST['is_active'])? "1" : "0" ;
				$settings['is_reversed'] = isset($_POST['is_reversed'])? "1" : "0" ;
				$settings['position'] = isset($_POST['position'])? $_POST['position'] : "bottom" ;
				$settings['style'] = isset($_POST['style_css'])? $_POST['style_css'] : "" ;
				$settings['is_custom'] = isset($_POST['is_custom'])? "1" : "0" ;
				$settings['custom_pre'] = isset($_POST['custom_pre'])? $_POST['custom_pre'] : "" ;
				$settings['custom_next'] = isset($_POST['custom_next'])? $_POST['custom_next'] : "" ;
				$settings['navi_img'] = isset($_POST['navi_img'])? "1" : "0" ;
				$settings['pre_img_link'] = isset($_POST['pre_img_link'])? $_POST['pre_img_link'] : "" ;
				$settings['next_img_link'] = isset($_POST['next_img_link'])? $_POST['next_img_link'] : "" ;
				
				update_option("wp_post_navigation_options", serialize($settings));
				echo '<div class="updated fade"><p>Setting Updated!</p></div>';
			}
			$action_url = $_SERVER['REQUEST_URI'];
			include 'wp-post-navigation-admin-options.php';
		}

		function get_wp_post_navigation_options()
		{
			$options = unserialize(get_option("wp_post_navigation_options"));
			return $options;
		}
		function WP_Post_Navigation_install()
		{
			$options = $this->get_wp_post_navigation_options();
			$options = array(
				'is_active' => (isset($options) && is_array($options) && isset($options["is_active"]))?$options["is_active"]:'1',
				'is_reversed' => (isset($options) && is_array($options) && isset($options["is_reversed"]))?$options["is_reversed"]:'0',
				'position' => (isset($options) && is_array($options) && isset($options["position"]))?$options["position"]:'bottom',
				'nav_within_cat' => (isset($options) && is_array($options) && isset($options["nav_within_cat"]))?$options["nav_within_cat"]:'1',
				'style' => (isset($options) && is_array($options) && isset($options["style"]))?$options["style"]:'text-decoration: none;
font:bold 16px sans-serif, arial;
color: #666;',
				'is_custom' => (isset($options) && is_array($options) && isset($options["is_custom"]))?$options["is_custom"]:'0',
				'custom_pre' => (isset($options) && is_array($options) && isset($options["custom_pre"]))?$options["custom_pre"]:'Previous Post',
				'custom_next' => (isset($options) && is_array($options) && isset($options["custom_next"]))?$options["custom_next"]:'Next Post',
				'navi_img' => (isset($options) && is_array($options) && isset($options["navi_img"]))?$options["navi_img"]:'0',
				'pre_img_link' => (isset($options) && is_array($options) && isset($options["pre_img_link"]))?$options["pre_img_link"]:'',
				'next_img_link' => (isset($options) && is_array($options) && isset($options["next_img_link"]))?$options["next_img_link"]:'',
				
			);
			add_option("wp_post_navigation_options", serialize($options));
		}
		function wp_admin_menu()
		{
			add_options_page('WP Post Navigation', 'WP Post Navigation', 10, basename(__FILE__), array(&$this, 'handle_wp_post_navigation_options'));
		}

		function wp_post_navigation_stylesheet() {
			wp_register_style( 'wp-post-navigation-style', plugins_url('style.css', __FILE__) );
			wp_enqueue_style( 'wp-post-navigation-style' );
		}
		function wp_post_navigation_HeadAction()
		{
			$settings = $this->get_wp_post_navigation_options();
			echo '<style type="text/css">
					.wp-post-navigation a{
					'.$settings['style'].'
					}
				 </style>';
		}
	}
else:
	exit('WPPostNavigation Already Exists');
endif;

$WPPostNavigation = new WPPostNavigation();
if(isset($WPPostNavigation)){
	register_activation_hook(__FILE__, array(&$WPPostNavigation, 'WP_Post_Navigation_install'));
	add_filter('wp_head', array(&$WPPostNavigation, 'wp_post_navigation_HeadAction'));
	add_filter('the_content', array(&$WPPostNavigation, 'WP_Pre_Next_Navigation'));
	add_action('admin_menu', array(&$WPPostNavigation, 'wp_admin_menu'));
	add_action( 'wp_enqueue_scripts', array(&$WPPostNavigation, 'wp_post_navigation_stylesheet'));
	add_shortcode('WPNextPrevious', array(&$WPPostNavigation, 'WP_Custom_Post_Navigation'));
}


?>