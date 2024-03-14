<?php
/*
Plugin Name: AW WordPress Yearly Category Archives
Plugin URI: hhttp://www.andy-warren.net
Description: This plugin will allow for yearly archives of specific categories.
Version: 1.2.8
Author: Andy Warren
Author URI: http://www.andy-warren.net

License:

	Copyright 2013  Andy Warren  (email : andy@andy-warren.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    
Or:

        DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE 
      Version 2, December 2004 - http://www.wtfpl.net/ 

 Copyright (C) 2013 Andy Warren <andy@andy-warren.net> 

 Everyone is permitted to copy and distribute verbatim or modified 
 copies of this license document, and changing it is allowed as long 
 as the name is changed. 

            DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE 
   TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION 

  0. You just DO WHAT THE FUCK YOU WANT TO.


*******************************  
Whichever license you prefer ;-)
*******************************

*/

// Add the menu page for the plugin
function aw_yca_add_menu_page(){
    add_menu_page( 'Yearly Category Archives', 'Yearly Category Archives', 'activate_plugins', 'aw-yearly-category-archives/aw_yearlyCategory_menu_page.php', '', plugins_url( 'aw-yearly-category-archives/img/read.png' ), 100 );
    add_submenu_page( 'aw-yearly-category-archives/aw_yearlyCategory_menu_page.php', 'Settings', 'Settings', 'activate_plugins', 'aw-yearly-category-archives/aw_yearlyCategory_settings_page.php' );
}
add_action( 'admin_menu', 'aw_yca_add_menu_page');

// Add the plugin's admin page JS/CSS files
function aw_enqueue_yearly_category_css() {
	wp_register_style('aw_yearly_category_css', plugins_url('/css/aw_yearly_category.css', __FILE__));
	wp_enqueue_style('aw_yearly_category_css');
	wp_register_script('aw_wp_yca_js', plugins_url('/js/aw_wp_yca_js.js', __FILE__));
	wp_enqueue_script('aw_wp_yca_js');
}
add_action( 'admin_init', 'aw_enqueue_yearly_category_css' );

// Add the plugin's frontend CSS file
function aw_enqueue_frontend_css() {
	wp_register_style('aw_frontend_css', plugins_url('/css/aw_frontend.css', __FILE__));
	wp_enqueue_style('aw_frontend_css');
	wp_register_script('frontend_js', plugins_url('/js/frontend.js', __FILE__), array('jquery'));
}
add_action( 'wp_enqueue_scripts', 'aw_enqueue_frontend_css');

// Create the year links to display on the site
function aw_create_year_links($atts) { 
	extract(shortcode_atts( array(
		'cat' => '1',
		'postslug' => '',
		'dropdown' => 'no',
	), $atts, 'aw_year_links' ));
	
	ob_start();
				
	$dateArray = array();
	 
	$myposts = get_posts(array('posts_per_page' => -1, 'post_type' => 'any', 'category' => $cat, 'post_status' => 'publish', 'orderby' => 'post_date'));
	
	foreach ($myposts as $post) { 
		$postdate = mysql2date('Y', $post->post_date);
		$dateArray[] = $postdate;				
	}
	
	if ($post) {
		
		$yearsListArray = array();
			
		$earliestPostDate = min($dateArray);
		$latestPostDate = max($dateArray);
		
		if ($dropdown == 'yes') {
			wp_enqueue_script('frontend_js');
			echo '<select class="awYearsDropdown">';
		} else {				
			echo '<ul class="awDatesUL">';
		}		
		while ($earliestPostDate <= $latestPostDate) {
			$pieces = explode(" ", $earliestPostDate++);
			
			foreach ($pieces as $piece) {
				$currentYear = date('Y');
				if ($piece <= $currentYear) {

					if ($dropdown == 'yes') {
						$piece = '<option class="awDropdownOption" value="' . esc_url( home_url() ) . '/' . $postslug .  '/?' . $piece . '">' . $piece . '</option>';
					} else {
						$piece = '<li class="awDatesLI"><a href="' . esc_url( home_url() ) . '/' . $postslug .  '/?' . $piece . '">' . $piece . '</a></li>';
					}
					
					$yearsListArray[] = $piece;
					
				}				
			}
			
		}
		
		rsort($yearsListArray);
		
		foreach ($yearsListArray as $yearLink) {
			
			echo($yearLink);
			
		}
		
		if ($dropdown == 'yes') {
			echo "</select>";
		} else {						
			echo '</ul>';
		}		
	} else {
		echo '<p>The are no posts in this category click <a href="' . home_url() .  '">here</a> to return to the home page.</p>';
	}
	
	return ob_get_clean();
	
} // end aw_create_year_links()

// Add shortcode [aw_year_links] to display yearly links
add_shortcode( 'aw_year_links', 'aw_create_year_links' );
?>
<?php
// Run aw_wp_yca_activate() when plugin is activated
register_activation_hook(__FILE__,'aw_wp_yca_activate');

// Run aw_wp_yca_deactivate() when plugin is deactivated
register_deactivation_hook( __FILE__, 'aw_wp_yca_deactivate' );

// Function to create new database field in wp_options
function aw_wp_yca_activate() {
	add_option('aw_wp_yca_postcontent', '', '', 'no');
	add_option('aw_wp_yca_customhtmlphp', '', '', 'no');
}

// Delete the aw_wp_yca_postcontent database field in wp_options
function aw_wp_yca_deactivate() {
	delete_option('aw_wp_yca_postcontent');
	delete_option('aw_wp_yca_customhtmlphp');
}

function aw_settings_page() {
	
if (isset($_POST["update_settings"])) {
	check_admin_referer( 'aw-yearly-category-archives-update-options' );
	//$customPostLayout = esc_attr($_POST["post-layout"]);
	$customPostLayout = $_POST["post-layout"];
	$newEvalString = stripslashes($customPostLayout);  
	update_option('aw_wp_yca_postcontent', $newEvalString);
	$customHtmlPhp = $_POST["customhtmlphp"];
	update_option('aw_wp_yca_customhtmlphp', $customHtmlPhp);
	$checkboxchecked = get_option( 'aw_wp_yca_customhtmlphp');		
}
echo $radioButtonValue;
	
?>
<div class="wrap">
	<h1 class="awMenuPageHeader">AW WordPress Yearly Category Archives Settings</h1>
	
	<p>
		<strong>Here you can choose to input your own custom post layout to be used in the output loop.</strong>
		<br/>
		For example you could use:<br/>
		<code>
			&lt;div id="postWrapper"&gt;<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&lt;h2&gt;&lt;?php the_title(); ?&gt;&lt;/h2&gt;<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&lt;?php the_content(); ?&gt;<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&lt;?php the_post_thumbnail(); ?&gt;<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&lt;a href="&lt;?php the_permalink(); ?&gt;"&gt;Continue Reading&lt;/a&gt;<br/>
			&lt;/div&gt;
		</code>
		<br/><br/>
		<strong>If unchecked the plugin will output its own post structure.  Don't forget to click the "Update Options" button.</strong>
	</p>
	
	<form id="aw_wp_yca_form" method="post" action="">
		<?php wp_nonce_field('aw-yearly-category-archives-update-options'); ?>
		<span id="radioButtonsWatcher">
			<?php $checkboxchecked2 = get_option( 'aw_wp_yca_customhtmlphp'); ?>
			<label for="customhtml">Check this box to include custom HTML and/or WordPress PHP template tags in the output loop.</label>
			<input type="checkbox" name="customhtmlphp" id="useCustomHtmlPhp" value="yes" <?php if($checkboxchecked == 'yes' OR $checkboxchecked2 == 'yes') { echo 'checked="checked"'; } ?> />
			<br/><br/>
		</span>
		<span id="codeSubmitWrapper">
			<label for="post-layout">Use this textarea input include any HTML or WordPress template tags you wish to use in the output loop.<br/><strong>All custom PHP in must be wrapped in</strong> <code>&lt;?php ?&gt;</code>.</label>
			<br/>
			<textarea id="codeTextArea" name="post-layout" rows="20" cols="120"><?php echo get_option('aw_wp_yca_postcontent'); ?></textarea>
			<br/>			
		</span>	
	    <input type="hidden" name="update_settings" value="Y" />
	    <input id="submitCode" class="button-primary" type="submit" name="Submit Code" value="<?php _e( 'Update Options' ); ?>" />	
	</form>
	

</div>
<?php } 
// Show post archives by year and category
function aw_show_posts_by_year_and_cat($atts) {
	extract(shortcode_atts(array(
		'cat' => '1',
		'readmore' => 'Read More',
		'publishedon' => 'M jS, Y',
		'showsubheader' => 'yes',
	), $atts, 'aw_show_posts' ));
	
	ob_start();
	
	if ($showsubheader == 'yes') {
	
		echo '<h3 class="awyca_subheader">Category: ' . get_cat_name($cat) . ' - Year: ' . $_SERVER["QUERY_STRING"] . '</h3>';
		
	}
	
	// Start the loop to display posts
	
    $showPosts = get_option('posts_per_page');
	
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	
	$customLayout = get_option('aw_wp_yca_postcontent');
	
	$postLoop = new WP_Query(array('posts_per_page' => -1, 'post_type' => 'any', 'cat' => $cat, 'post_status' => 'publish', 'orderby' => 'post_date', 'order' => 'DESC', 'paged' => $paged));
	while ( $postLoop->have_posts() ) : $postLoop->the_post();
	
	$postdate = get_the_date('Y');
	$postContent = get_the_content();
	$contentPieces = explode(" ", $postContent);
    $first_25_excerpt = implode(" ", array_splice($contentPieces, 0, 25));
    $checkboxchecked3 = get_option( 'aw_wp_yca_customhtmlphp');
	
	if ($_SERVER["QUERY_STRING"] == $postdate) { 
		if ($checkboxchecked3=='yes') {
			$myEvalString="?> ".get_option('aw_wp_yca_postcontent'). "<?php ;";
			eval($myEvalString);
		} else {	
	?>
	
		<div class="awyca_postWrapper">
	
			<h3><?php the_title(); ?></h3>
			
			<?php echo '<p class="awPublishedOnDate">Published on ' . get_the_date($publishedon) . '</p>'; ?>
			
			<?php echo '<p class="awPostExcerpt">' . strip_tags($first_25_excerpt) . '...<a class="awReadMore" href="' . get_permalink() . '">' . $readmore . '</a></p>'; ?>
			
			<hr class="awPostDivider"/>
		
		</div>
	
	<?php }} endwhile; ?>
	
<?php return ob_get_clean(); } // end aw_show_posts_by_year_and_cat()

// Add shortcode [aw_show_posts] to display yearly links
add_shortcode( 'aw_show_posts', 'aw_show_posts_by_year_and_cat' );		
?>