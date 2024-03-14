<?php
/**
 * Plugin Name: New Post Notification
 * Plugin URI:  http://kilozwo.de/wordpress-new-post-notification-plugin
 * Description: Simply notifies users if a new post has been published. This is an addon for User-Access-Manager. Users will only be notified if they have access. Your subscribers can also decide which posts they would like to be noticed on.
 * Version:     1.0.10
 * Author:      Jan Eichhorn
 * Author URI:  http://kilozwo.de
 * License:     GPLv2
 */

// load textdomain
load_plugin_textdomain('npn_plugin', false, basename( dirname( __FILE__ ) ) . '/languages' );

// Do something when a post gets published //
add_filter ( 'publish_post', 'npn_notify' );

function npn_notify($post_ID) {

  	// get the Userdata //
  	$meta_query = new WP_Query( array( 'meta_key' => 'npn_post_notify', 'meta_value' => '1' ) );
  	$args = array(
  		'meta_query' => $meta_query,
  		);
  	$users = get_users( );

    // get the postobject //
    $postobject = get_post($post_ID);
    $postcontent = get_post_field('post_content', $post_ID);
    $postthumb = get_the_post_thumbnail( $post_ID, 'medium');

    // get allowed groups to access post //
    $allowed_groups = npn_get_allowed_groups($post_ID);

    // Use HTML-Mails
    add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));

    // Go through the users and check the access //
    foreach ($users as $user){
      $access = false;
      if (empty($allowed_groups)) $access = true;                               // always notify every user on public posts
      foreach ($user->caps as $key => $value){                                  // assigned capabilities to certain user
        if (in_array($key,$allowed_groups) AND $value == 1) $access = true;     // check if user is in the right user-group
        if ($key == 'administrator') $access = true;                            // Admins always get a mail.
      }

      // Check if category is chosen by user
      $user_cats = get_user_meta($user->ID, 'npn_mailnotify_category');
      $cat_chosen = false;
      if ($user_cats[0]=='') {
        $cat_chosen = true;
      }
      else
      {
        foreach ($postobject->post_category as $postcats) {
            if (in_array($postcats,explode(',',$user_cats[0]))) $cat_chosen = true;
        }
      }

      // send Mail if User activated Notification and there was no notification before.
      if ($access==true AND $cat_chosen==true AND get_the_author_meta( 'npn_mailnotify', $user->ID )=='1' 
      AND get_post_meta( $post_ID, 'npn_notified', true) != '1') {
        wp_mail( $user->data->user_email, '['.get_option('blogname').'] '.__('New Post','npn_plugin').': '
        .$postobject->post_title, npn_generate_mail_content($postobject,$postcontent,$postthumb,$user->ID));  
      }
     }
     
     // Use default plain
     add_filter('wp_mail_content_type',create_function('', 'return "text/plain"; '));
     
    update_post_meta($post_ID, 'npn_notified', '1', true);
    return $post_ID;
}

function npn_get_allowed_groups($postID){
	global $wpdb;
	$allowed_group_ids = $wpdb->get_results( "SELECT group_id FROM ".$wpdb->prefix."uam_accessgroup_to_object WHERE object_id = ".$postID." AND object_type = 'post' ");
    $allowed_group_names = array();
    foreach ($allowed_group_ids as $group) {
      array_push($allowed_group_names,npn_get_group_name($group->group_id));
    }
    return $allowed_group_names;
}

function npn_get_group_name($groupID){
    global $wpdb;
    $groupname = $wpdb->get_results( "SELECT object_id FROM ".$wpdb->prefix."uam_accessgroup_to_object WHERE group_id = ".$groupID." AND object_type = 'role' ");
    return $groupname[0]->object_id;
}

function npn_generate_mail_content($postobject,$postcontent,$postthumb,$userid){
    $userdata = get_userdata($userid);
    $authordata = get_userdata($postobject->post_author);
    $mailcontent = __('Hello','npn_plugin').' '.$userdata->first_name.',<br>';
    $mailcontent .= $authordata->first_name.' '.__('published a new post','npn_plugin').' '.__('at','npn_plugin').' '.get_option('blogname').':<br>';
    $mailcontent .= '<h2><a href="'.$postobject->guid.'&refferer=mailnotify&uid='.$userid.'">'.$postobject->post_title.'</a></h2>'.implode(' ', array_slice(explode(' ', $postcontent), 0, 40)).' <a href="'.$postobject->guid.'&refferer=mailnotify&uid='.$userid.'">[...]</a>';
    $mailcontent .= '<br><br><small>'.__('You can deactivate the subscription in your','npn_plugin').' <a href="'.get_bloginfo('wpurl').'/wp-admin/profile.php">'.__('Profile','npn_plugin').'</a> .'.__('It is also possible to choose categories.','npn_plugin').'</small>';

    return $mailcontent;
}

// Settings in Profile //

function npn_add_custom_user_profile_fields( $user ) {
  if (get_the_author_meta( 'npn_mailnotify', $user->ID ) == '1') $checked = 'checked'; else $checked = '';
  $categories = get_categories( array('hide_empty'=>0, 'order_by'=>'name') );
  $user_cats = get_user_meta($user->ID, 'npn_mailnotify_category');
?>
	<h3><?php _e('Notificationservice','npn_plugin'); ?></h3>

	<table class="form-table">
		<tr>
			<th>
				<label for="npn_mailnotify"><?php _e('Email Subscription','npn_plugin'); ?></label>
            </th>
			<td>
				<input type="checkbox" name="npn_mailnotify" id="npn_mailnotify" value="1" <?php echo $checked; ?>/>
				<span class="description"><?php _e('Notify me via email if a new post is published. ','npn_plugin'); echo(' '); _e('If you don\'t want to get all the stuff, choose your categories below. ','npn_plugin'); echo(' '); _e('Choosing none means getting all. ','npn_plugin'); ?></span>
			</td>
		</tr>
        <?php
        foreach ($categories as $category) {
          $category_checked='';
          if (in_array($category->cat_ID,explode(',',$user_cats[0]))) $category_checked='checked';
        ?>
        </tr>
            <th>
				<label for="npn_mailnotify_category_<?php echo($category->name); ?>"><?php echo($category->name); ?></label>
            </th>
            <td>
                <input type="checkbox" name="npn_mailnotify_category[]" id="npn_mailnotify_category_<?php echo($category->name); ?>" value="<?php echo($category->cat_ID); ?>" <?php echo $category_checked; ?>/>
                <span class="description"><?php echo($category->description); ?></span>
            </td>
        </tr>
        <?php
        }
        ?>
	</table>
<?php }

function npn_save_custom_user_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return FALSE;
    // Notify the Administrator if anybody activates or deactivates the nofifications.
    $user = get_userdata($user_id);
    $usermeta = get_user_meta($user_id, 'npn_mailnotify');
    if ($_POST['npn_mailnotify']=='1' AND $usermeta[0] !='1') wp_mail(get_option('admin_email'),$user->first_name.' '.__('activated subscription to posts.','npn_plugin'),$user->first_name.' '.$user->last_name);
    if ($_POST['npn_mailnotify']!='1' AND $usermeta[0] =='1') wp_mail(get_option('admin_email'),$user->first_name.' '.__('deactivated subscription to posts.','npn_plugin'),$user->first_name.' '.$user->last_name);

    if(isset($_POST['npn_mailnotify'])){
      update_usermeta( $user_id, 'npn_mailnotify', $_POST['npn_mailnotify']);
    }
    else
    {
      update_usermeta( $user_id, 'npn_mailnotify', '0');
    }
        
    if(isset($_POST['npn_mailnotify_category'])){
      update_usermeta( $user_id, 'npn_mailnotify_category', implode(',',$_POST['npn_mailnotify_category']));
    }
    else
    {
      update_usermeta( $user_id, 'npn_mailnotify_category', '');
    }
}

add_action( 'show_user_profile', 'npn_add_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'npn_add_custom_user_profile_fields' );

add_action( 'personal_options_update', 'npn_save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'npn_save_custom_user_profile_fields' );

// adds mailnotify abo when user registeres //
add_action('user_register', 'npn_defaultnotify');

function npn_defaultnotify($user_id) {
    add_user_meta( $user_id, 'npn_mailnotify', '1' );
}

// adds extra column in user_table
add_filter('manage_users_columns', 'npn_add_mailnotify_column');
function npn_add_mailnotify_column($columns) {
    $columns['npn_mailnotify'] = __('Mail subscription','npn_plugin');
    return $columns;
}

add_action('manage_users_custom_column',  'npn_add_mailnotify_column_content', 10, 3);
function npn_add_mailnotify_column_content($value, $column_name, $user_id) {
    $user = get_userdata( $user_id );
	if ( 'npn_mailnotify' == $column_name ){
        $mailstatus = get_user_meta($user_id, 'npn_mailnotify');
        $user_cats = get_user_meta($user->ID, 'npn_mailnotify_category');
		if ($mailstatus[0]=='1') {
        $user_cats = explode(',',$user_cats[0]);
        $out = '';
        foreach ($user_cats as $category){
          $out .= get_cat_name($category).', ';        
        }
        if ($out == ', ') {return __('All categories','npn_plugin');} else return $out;    
      } 
      else 
      {
        return __('not active','npn_plugin');
      }
    }
    return $value;
}

/* Not yet active.
// activate subscription to all users when first activating the plugin
register_activation_hook(__FILE__,'npn_activate_subscription');

function npn_activate_subscription(){
    $users = get_users( );

    foreach ($users as $user){
        $subscription_status = get_user_meta($user->ID,'npn_mailnotify');
        if ($subscription_status[0] != "0") add_user_meta($user->ID,'npn_mailnotify',"1");
    }

}

*/


?>