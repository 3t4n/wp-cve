<?php
 
/*
* Plugin Name: Duplicate Title Validate
* Description: this plugin help , not allow publish Duplicate Title . 
* Author: hasan movahed
* Version: 1.0
* Author URI: http://www.wallfa.com
*/
 
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
 
//jQuery to send AJAX request 
function duplicate_titles_enqueue_scripts( $hook ) {
 
    if( !in_array( $hook, array( 'post.php', 'post-new.php' , 'edit.php'))) return;
    wp_enqueue_script('duptitles',
    wp_enqueue_script('duptitles',plugins_url().'/duplicate-title-validate/js/duptitles.js',
                                                       array( 'jquery' )), array( 'jquery' )  );
}
    
add_action( 'admin_enqueue_scripts', 'duplicate_titles_enqueue_scripts', 2000 );
add_action('wp_ajax_title_checks', 'duplicate_title_checks_callback');
 
 
/// callback ajax 
function duplicate_title_checks_callback() {
 
		function title_checks() {
            
            global $wpdb;
            
			$title = $_POST['post_title'];
			$post_id = $_POST['post_id'];
 
			$titles = "SELECT post_title FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' 
						AND post_title = '{$title}' AND ID != {$post_id} ";
 
			$results = $wpdb->get_results($titles);
 
			if($results) {
				return "<span style='color:red'>". _e( 'Duplicate title detected, please change the title.' , 'dublicate-title-validate' ) ." </span>";
			} else {
				return '<span style="color:green">'._e('This title is unique.' , 'dublicate-title-validate').'</span>';
			}
            
		}		
		echo title_checks();
		die();
	}
 
// this chek backend title and if Duplicate update status draft .
function duplicate_titles_wallfa_bc( $post )
{
	global $wpdb ;
	$title = $_POST['post_title'] ;
	$post_id = $post ;
 
 
 
	$wtitles = "SELECT post_title FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' 
						AND post_title = '{$title}' AND ID != {$post_id} " ;
 
	$wresults = $wpdb->get_results( $wtitles ) ;
 
	if ( $wresults )
	{
		$wpdb->update( $wpdb->posts, array( 'post_status' =>
				'draft' ), array( 'ID' => $post ) ) ;
        $arr_params = array( 'message' => '10', 'wallfaerror' => '1' )  ;      
		$location = add_query_arg( $arr_params , get_edit_post_link( $post , 'url' ) ) ;
		wp_redirect( $location  ) ;
        
        exit ; 
        
        
	}
}
 
 
add_action( 'publish_post',
	'duplicate_titles_wallfa_bc' ) ;
 
/// handel error for back end 
function not_published_error_notice() {
    if(isset($_GET['wallfaerror']) == 1 ){
               ?>
               <div class="updated">
               <p style='color:red' ><?php _e('Title used for this post appears to be a duplicate. Please modify the title. You may also want to revise the URL slug to make sure it is unique as well.' , 'dublicate-title-validate') ?></p>
               </div>
               <?php
        }
}
add_action( 'admin_notices', 'not_published_error_notice' );        
 
 
function duplicate_titles_wallfa_action_init()
{
// Localization
load_plugin_textdomain('dublicate-title-validate',false,dirname(plugin_basename(__FILE__)).'/langs/');
}
 
// Add actions
add_action('init', 'duplicate_titles_wallfa_action_init');
 
 
 
 
function disable_autosave()
{
	wp_deregister_script( 'autosave' ) ;
}
add_action( 'wp_print_scripts', 'disable_autosave' ) ;
 
?>