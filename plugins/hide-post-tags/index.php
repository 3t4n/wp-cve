<?php
/*
Plugin Name: Hide Post Tags
Description: Just install and activate the plugin to hide all your posts tags.
Version: 1.0
Author: Danymhanna
Author URI: https://profiles.wordpress.org/danymhanna/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/


if (is_admin())
   {   
      function hpt_hideposttags_admin_restrict_data() 
		{  
			
			add_options_page('Hide Post Tags', 'Hide Post Tags', 'manage_options', 'hide_post_tags', 'hpt_hideposttags');
		}   
       add_action('admin_menu','hpt_hideposttags_admin_restrict_data'); 
   }
   	function hpt_hideposttags()
	{
		global $wpdb;

	
		?>
<div class="main-used-container">
    <div class="main-notices">
		<div class="notices">
			<div class="logo-container"><img class="main-image" width="150" src="<?php echo plugin_dir_url( __FILE__ ) ?>/assets/img/correct.png"/></div>
			<h2 style="text-align: center;">Hide Post Tags</h2>
			<p style="text-align: center;margin-bottom:2%;">The plugin is now active and post tags are now hidden. If you wish to disable this feature simply deactivate the plugin.</p>
		</div>
    </div>
</div>
<?php
}

register_activation_hook(__FILE__, 'hpt_hideposttags_my_plugin_activate');
register_deactivation_hook( __FILE__, 'hpt_hideposttags_my_plugin_deactivation' );

add_action('admin_init', 'hpt_hideposttags_my_plugin_redirect');

function hpt_hideposttags_my_plugin_activate() {
    add_option('hpt_hideposttags_my_plugin_do_activation_redirect', true);
}
function hpt_hideposttags_my_plugin_deactivation() {
    
}
function hpt_hideposttags_my_plugin_redirect() {
    if (get_option('hpt_hideposttags_my_plugin_do_activation_redirect', false)) {
        delete_option('hpt_hideposttags_my_plugin_do_activation_redirect');
        wp_redirect(get_site_url()."/wp-admin/options-general.php?page=hide_post_tags");
    }
}

function hpt_hideposttags_custom_added_text() {
    ?>
	<script>
		var tagslinks = document.getElementsByClassName("tags-links");
		for (i = 0; i < tagslinks.length; ++i){
		   tagslinks[i].style.display = "none";
		}
		
		var posttags = document.getElementsByClassName("post-tags");
		for (i = 0; i < posttags.length; ++i){
		   posttags[i].style.display = "none";
		}
		
		var widget_tag_cloud = document.getElementsByClassName("widget_tag_cloud");
		for (i = 0; i < widget_tag_cloud.length; ++i){
		   widget_tag_cloud[i].style.display = "none";
		}
	</script>
	
<?php } 

add_action('wp_footer', 'hpt_hideposttags_custom_added_text');

function hpt_hideposttags_admin_style() {
	wp_enqueue_style('admin-styles', plugin_dir_url( __FILE__ ).'/assets/css/admin-style.css');
}
add_action('admin_enqueue_scripts', 'hpt_hideposttags_admin_style');