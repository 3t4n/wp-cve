<?php
/*
Plugin Name: Tabs popular posts and latest posts
Description: This is a jquery based lightweight plugin to create a new wordpress tabbed widget to display recent posts and popular posts.
Author: Gopi Ramasamy
Version: 3.9
Plugin URI: http://www.gopiplus.com/work/2012/11/24/wordpress-plugin-tabs-widget-popular-posts-and-latest-posts/
Author URI: http://www.gopiplus.com/work/2012/11/24/wordpress-plugin-tabs-widget-popular-posts-and-latest-posts/
Donate link: http://www.gopiplus.com/work/2012/11/24/wordpress-plugin-tabs-widget-popular-posts-and-latest-posts/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: tabs-popular-vs-latest
Domain Path: /languages
*/

global $wpdb, $wp_version;

// Main method to load tabber widget
function TabsPosts()
{
	global $wpdb;
	$tplp_popular_posts = get_option('tplp_popular_posts');
	$tplp_latest_posts = get_option('tplp_latest_posts');
	$tplp_popular_title = get_option('tplp_popular_title');
	$tplp_latest_title = get_option('tplp_latest_title');
	if(!is_numeric($tplp_popular_posts)) { $tplp_popular_posts = 5 ;}
	if(!is_numeric($tplp_latest_posts)) { $tplp_latest_posts = 5 ;}
	?>
	<div id="TabsPostsTabber">
		<ul class="TabsPostsTabs">
			<li><a href="#TabsPostsLeft"><?php echo $tplp_popular_title; ?></a></li>
			<li><a href="#TabsPostsRight"><?php echo $tplp_latest_title; ?></a></li>
		</ul>
		<div class="clear"></div>
		<div class="TabsPostsInside">
			<div id="TabsPostsLeft">
				<?php tabs_popular_posts($tplp_popular_posts); ?>
			</div>
			<div id="TabsPostsRight">
				<?php tabs_latest_posts($tplp_latest_posts); ?>    
			</div>
			<div class="clear" style="display: none;"></div>
		</div>
		<div class="clear"></div>
	</div>
	<?php
}

add_shortcode( 'tabs-posts', 'tabs_shortcode' );

function tabs_shortcode( $atts ) 
{
	global $wpdb;
	//return TabsPosts();
	
	//[tabs-posts id="1"]
	$tplp_popular_posts = get_option('tplp_popular_posts');
	$tplp_latest_posts = get_option('tplp_latest_posts');
	$tplp_popular_title = get_option('tplp_popular_title');
	$tplp_latest_title = get_option('tplp_latest_title');
	if(!is_numeric($tplp_popular_posts)) { $tplp_popular_posts = 5 ;}
	if(!is_numeric($tplp_latest_posts)) { $tplp_latest_posts = 5 ;}

	$tabs = "";
	
	$tabs .= '<div id="TabsPostsTabber">';
		$tabs .= '<ul class="TabsPostsTabs">';
			$tabs .= '<li><a href="#TabsPostsLeft">'.$tplp_popular_title.'</a></li>';
			$tabs .= '<li><a href="#TabsPostsRight">'.$tplp_latest_title.'</a></li>';
		$tabs .= '</ul>';
		$tabs .= '<div class="clear"></div>';
		$tabs .= '<div class="TabsPostsInside">';
			$tabs .= '<div id="TabsPostsLeft">';
				
				$popular = new WP_Query('orderby=comment_count&posts_per_page='.$tplp_popular_posts);
				$popular_post_num = 1;
				while ($popular->have_posts()) : $popular->the_post();
					$tabs .= '<div><a title="'.get_the_title().'" href="'.get_the_permalink().'">'.get_the_title().'</a></div>';
				endwhile; 
				
			$tabs .= '</div>';
			$tabs .= '<div id="TabsPostsRight">';
			
				$the_query = new WP_Query('showposts='. $tplp_latest_posts .'&orderby=post_date&order=desc');
				$recent_post_num = 1;		
				while ($the_query->have_posts()) : $the_query->the_post(); 
					$tabs .= '<div><a title="'.get_the_title().'" href="'.get_the_permalink().'">'.get_the_title().'</a></div>';
				endwhile; 
			 
			$tabs .= '</div>';
			$tabs .= '<div class="clear" style="display: none;"></div>';
		$tabs .= '</div>';
		$tabs .= '<div class="clear"></div>';
	$tabs .= '</div>';
	
	return $tabs;
}

/*Function to call when plugin get activated*/
function tabs_popular_latest_posts_install() 
{
	global $wpdb, $wp_version;
	add_option('tplp_popular_title', "Popular");
	add_option('tplp_popular_posts', "5");
	
	add_option('tplp_latest_title', "Recent");
	add_option('tplp_latest_posts', "5");
}

/*Function to Call when plugin get deactivated*/
function tabs_popular_latest_posts_deactivation() 
{
	// No action on plugin deactivation
}

/*Load javascript files for plugins*/
function tabs_popular_latest_posts_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script('jquery');
		wp_enqueue_style( 'tplp_style', get_option('siteurl').'/wp-content/plugins/tabs-widget-popular-posts-and-latest-posts/inc/style.css');
		wp_enqueue_script( 'tplp_script', get_option('siteurl').'/wp-content/plugins/tabs-widget-popular-posts-and-latest-posts/inc/script.js', '', '1.0', true);
	}
}   

/*Tabber plugin widget control*/
function tabs_popular_latest_posts_control() 
{
	$tplp_popular_posts = get_option('tplp_popular_posts');
	$tplp_latest_posts = get_option('tplp_latest_posts');
	$tplp_popular_title = get_option('tplp_popular_title');
	$tplp_latest_title = get_option('tplp_latest_title');
	
	if (isset($_POST['tplp_submit'])) 
	{
		$tplp_popular_posts = sanitize_text_field($_POST['tplp_popular_posts']);
		$tplp_latest_posts = sanitize_text_field($_POST['tplp_latest_posts']);
		$tplp_popular_title = sanitize_text_field($_POST['tplp_popular_title']);
		$tplp_latest_title = sanitize_text_field($_POST['tplp_latest_title']);
		
		update_option('tplp_popular_posts', $tplp_popular_posts );
		update_option('tplp_latest_posts', $tplp_latest_posts );
		update_option('tplp_popular_title', $tplp_popular_title );
		update_option('tplp_latest_title', $tplp_latest_title );
	}
	echo '<p>'.__('Popular posts tab title:', 'tabs-popular-vs-latest').'<br><input  style="width: 200px;" type="text" value="';
	echo $tplp_popular_title . '" name="tplp_popular_title" id="tplp_popular_title" /></p>';
	echo '<p>'.__('Number of popular posts to show:', 'tabs-popular-vs-latest').'<br><input  style="width: 200px;" type="text" value="';
	echo $tplp_popular_posts . '" name="tplp_popular_posts" id="tplp_popular_posts" /></p>';
		
	echo '<p>'.__('Latest posts tab title:', 'tabs-popular-vs-latest').'<br><input  style="width: 200px;" type="text" value="';
	echo $tplp_latest_title . '" name="tplp_latest_title" id="tplp_latest_title" /></p>';
	echo '<p>'.__('Number of latest posts to show:', 'tabs-popular-vs-latest').'<br><input  style="width: 200px;" type="text" value="';
	echo $tplp_latest_posts . '" name="tplp_latest_posts" id="tplp_latest_posts" /></p>';
	
	echo '<input type="hidden" id="tplp_submit" name="tplp_submit" value="1" />';
	
	echo '<p>';
	_e('Check official website for more information', 'tabs-popular-vs-latest');
	?> 
	<a target="_blank" href="http://www.gopiplus.com/work/2012/11/24/wordpress-plugin-tabs-widget-popular-posts-and-latest-posts/">
	<?php _e('click here', 'tabs-popular-vs-latest'); ?></a></p><?php
}

/*Method to load tabber widget*/
function tabs_popular_latest_posts_widget($args) 
{
	TabsPosts();
}

/*Method to load popular posts*/
function tabs_popular_posts( $posts = 5 ) 
{
	$popular = new WP_Query('orderby=comment_count&posts_per_page='.$posts);
	$popular_post_num = 1;
	while ($popular->have_posts()) : $popular->the_post();
	?>
	<div><a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php the_title(); ?></a></div>
	<?php
	endwhile; 
}

/*Method to load latest posts*/
function tabs_latest_posts( $posts = 5 ) 
{
	$the_query = new WP_Query('showposts='. $posts .'&orderby=post_date&order=desc');
	$recent_post_num = 1;		
	while ($the_query->have_posts()) : $the_query->the_post(); 
	?>
	<div><a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php the_title(); ?></a></div>
	<?php
	endwhile; 
}

/*Method to initiate sidebar widget & control*/
function tabs_popular_latest_posts_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget( 'tabs-popular-vs-latest',
			__('Tabs popular posts and latest posts', 'tabs-popular-vs-latest'), 'tabs_popular_latest_posts_widget');
	}
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control( 'tabs-popular-vs-latest', 
			array( __('Tabs popular posts and latest posts', 'tabs-popular-vs-latest'), 'widgets'), 'tabs_popular_latest_posts_control');
	} 
}

/*Plugin textdomain*/
function tabs_popular_latest_posts_textdomain() 
{
	  load_plugin_textdomain( 'tabs-popular-vs-latest', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/*Plugin hook*/
add_action('plugins_loaded', 'tabs_popular_latest_posts_textdomain');
add_action("plugins_loaded", "tabs_popular_latest_posts_init");
add_action('wp_enqueue_scripts', 'tabs_popular_latest_posts_add_javascript_files');
register_activation_hook(__FILE__, 'tabs_popular_latest_posts_install');
register_deactivation_hook(__FILE__, 'tabs_popular_latest_posts_deactivation');
?>