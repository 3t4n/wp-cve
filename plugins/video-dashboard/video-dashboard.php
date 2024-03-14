<?php
/*
Plugin Name: Video Dashboard
Plugin URI: http://brianjohnsondesign.com/wordpress-plugins/video-dashboard
Description: A plugin to display embeddable media into the WordPress dashboard
Version: 1.2.1.1
Author: Brian Johnson
Author URI: http://brianjohnsondesign.com
License: GPLv2 or later
*/


/* Global Variables */

$vdb_prefix      = 'vdb_';
$vdb_plugin_name = 'Video Dashboard';
$vdb_options     = get_option('vdb_settings');

/*Includes*/

include('includes/admin-page.php');




add_action('wp_dashboard_setup', 'vdb_dashboard'); //Run plugin when dashboard is loaded
register_activation_hook(__FILE__, 'vdb_set_up_options'); //Run function to load default values

function vdb_set_up_options() //Function to load default values
{
    add_option('vdb_settings', array(
        'minimum_role' => 'administrator',
        'youtube_number' => '10'
    ));
}


function vdb_load_scripts($hook) //Register our stylesheet
{
    
    if ($hook != 'index.php') //Only if it's in the admin area
        return;
    
    wp_register_style('vdb-style', plugins_url('includes/video-dashboard.css', __FILE__));
    wp_enqueue_style('vdb-style');
}
add_action('admin_enqueue_scripts', 'vdb_load_scripts');



function vdb_dashboard() //Display Videos
{
    
    $vdb_options = get_option('vdb_settings');
    
    //Specify which roles have permissions, given the minimum value
    if ($vdb_options['minimum_role'] == 'subscriber') {
        $roles = array(
            'subscriber',
            'contributor',
            'author',
            'editor'
        );
    }
    if ($vdb_options['minimum_role'] == 'contributor') {
        $roles = array(
            'contributor',
            'author',
            'editor'
        );
    }
    if ($vdb_options['minimum_role'] == 'author') {
        $roles = array(
            'author',
            'editor'
        );
    }
    if ($vdb_options['minimum_role'] == 'editor') {
        $roles = array(
            'editor'
        );
    }
    if ($vdb_options['minimum_role'] == 'administrator') {
        $roles = array(
            'administrator'
        );
    }
    
    //Check role
    $in_role = vdb_check_user_role($roles);
    
    //Load dashboard widget if they are an acceptable role
    if ($in_role) {
        wp_add_dashboard_widget('vdb_youtube_videos', 'Videos', 'vdb_display_youtube'); //Add YouTube Videos
    } else {
        // User not in role, do nothing
    }
}

//Function to check user role
function vdb_check_user_role($roles, $user_id = NULL)
{
    if ($user_id)
        $user = get_userdata($user_id);
    else
        $user = wp_get_current_user();
    
    if (empty($user))
        return FALSE;
    
    if (!in_array('administrator', $roles)) //Add admins even if they aren't specified
        $roles[] = 'administrator';
    
    foreach ($user->roles as $role) {
        if (in_array($role, $roles)) {
            return TRUE;
        }
    }
    return FALSE;
}



function vdb_display_youtube()
{
    
    global $vdb_options;
    
    echo '<div class="wrap"></div>';
    
    for ($i = 1; $i <= $vdb_options['youtube_number']; $i++) { //Loop through the number of videos
        
        if (array_key_exists('youtube_id' . $i, $vdb_options)) { //Make sure there is something entered in the field, otherwise do nothing with it
            $youtube_location = $vdb_options['youtube_id' . $i];
            
            if (!empty($youtube_location)) {
                if (strpos($youtube_location, 'v=') !== false) { //If it's the full url
                    $url = $youtube_location;
                    parse_str(parse_url($youtube_location, PHP_URL_QUERY), $my_array_of_vars); //Strip the video ID from the URL
                    $video_id = $my_array_of_vars['v'];
                } else {
                    $video_id = $youtube_location; //Otherwise it must be the video ID, just display that
                }
                
                if (strlen($video_id) != '11') {
					
					//It doesn't appear to be valid YouTube, so let's check and see if it's Vimeo.
					//Start VIMEO Section
					preg_match('/vimeo\.com\/([0-9]{1,10})/', $youtube_location, $matches);
					$vimeo_id = $matches[1];
					if ($vimeo_id) {
						
						?>
                        <div class="video-container">
                        <iframe src="https://player.vimeo.com/video/<?php echo $vimeo_id; ?>" width="640" height="360" frameborder="0" style="width:100%;" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>
						<?php
						
					}
					//END Vimeo Section
					
					else { //It's not Vimeo either, so display error
                    echo '<p>Error: Video #' . $i . " does not appear to be a valid YouTube or Vimeo URL.</p>";
					}
                } //Make sure it's a valid 11-character string
                else {
?>

                <div class="video-container"><iframe src="//www.youtube.com/embed/<?php
                    echo $video_id;
?>" frameborder="0" allowfullscreen></iframe></div>
		
 <?php
                } //end else
            } //End if !empty($youtube_location)
        }
        ;
    } //End the Loop
}
