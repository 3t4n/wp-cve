<?php
if ( ! defined( 'WPINC' ) ) die;

$id = isset( $_REQUEST['id'] ) ? absint($_REQUEST['id']) : '1'; 
global $wpdb; 
$shortcode_ids = $wpdb->get_col( "SELECT id FROM {$wpdb->prefix}sform_shortcodes" );
$settings = get_option("sform_settings");
$color = ! empty( $settings['admin_color'] ) ? esc_attr($settings['admin_color']) : 'default';
$admin_notices = ! empty( $settings['admin_notices'] ) ? esc_attr($settings['admin_notices']) : 'false';
$notice = '';
$view = isset( $_REQUEST['view'] ) && !empty( $_REQUEST['view'] ) ? '&view=' . sanitize_text_field($_REQUEST['view']) : '&view=all';
$pagenum = isset( $_REQUEST['paged'] ) && !empty( $_REQUEST['paged'] ) ? '&paged=' . absint($_REQUEST['paged']) : '';
$order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? '&order=' . $_REQUEST['order'] : '';
$orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array('subject', 'email', 'date'))) ? '&orderby=' . $_REQUEST['orderby'] : ''; 
$back_link = get_admin_url(get_current_blog_id(), 'admin.php?page=sform-forms') .$view.$pagenum.$order.$orderby;
?>

<div id="sform-wrap" class="sform">

<div id="new-release" class="<?php if ( $admin_notices == 'true' ) {echo 'invisible';} ?>"><?php echo apply_filters( 'sform_update', $notice ); ?>&nbsp;</div>
	
<div class="full-width-bar <?php echo $color ?>">
<h1 class="title <?php echo $color ?>"><span class="dashicons dashicons-tag responsive"></span><?php _e( 'Form', 'simpleform' ); if ( in_array($id, $shortcode_ids) ) { ?><a href="<?php echo esc_url($back_link)?>"><span class="dashicons dashicons-list-view icon-button admin <?php echo $color ?>"></span><span class="wp-core-ui button admin back-list <?php echo $color ?>"><?php _e( 'Back to forms', 'simpleform' ) ?></span></a> <?php } ?></h1>
</div>

<?php 
if ( in_array($id, $shortcode_ids) ) {

$attributes = get_option("sform_{$id}_attributes") != false ? get_option("sform_{$id}_attributes") : get_option("sform_attributes");
$form_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}sform_shortcodes WHERE id = %d", $id) );
$relocation = esc_attr($form_data->relocation) ? 'true' : 'false';
$moveto = esc_attr($form_data->moveto) != '0' ? esc_attr($form_data->moveto) : '';
$to_be_moved = esc_attr($form_data->to_be_moved) ? esc_attr($form_data->to_be_moved) : '';
$onetime_moving = esc_attr($form_data->onetime_moving) ? 'true' : 'false';
$notifications_settings = esc_attr($form_data->override_settings) ? 'true' : 'false';
$deletion = esc_attr($form_data->deletion) ? 'true' : 'false';
$storing = esc_attr($form_data->storing) ? 'true' : 'false';
if ( esc_attr($form_data->status) == 'published' ) { $status = _x( 'Published', 'Singular noun', 'simpleform' ); }
elseif ( esc_attr($form_data->status) == 'draft' ) { $status = __( 'Draft','simpleform'); }
else { $status = _x( 'Trashed', 'Singular noun', 'simpleform' ); }

$shortcode = $id == '1' ? 'simpleform' : 'simpleform id="'.$id.'"';
/* translators: It is used in place of placeholder %1$s in the string: "%1$s or %2$s the page content" */
$edit = __( 'Edit','simpleform');
/* translators: It is used in place of placeholder %2$s in the string: "%1$s or %2$s the page content" */
$view = __( 'view','simpleform');
$show_for = ! empty( $attributes['show_for'] ) && !isset($_GET['showfor']) ? esc_attr($attributes['show_for']) : 'all';
if ( $show_for == 'out' ) { $target = __( 'Logged-out users','simpleform'); }
elseif ( $show_for == 'in' ) { $target = __( 'Logged-in users','simpleform'); }
else { $target = __( 'Everyone','simpleform'); }
$role = ! empty( $attributes['user_role'] ) ? esc_attr($attributes['user_role']) : 'any';
global $wp_roles;
$role_name = $role == 'any' ? __( 'Any','simpleform') : translate_user_role($wp_roles->roles[$role]['name']);

// Get a pages list sorted by name where the form is used
$allpagesid = $wpdb->get_col( "SELECT id FROM {$wpdb->prefix}posts WHERE post_type != 'attachment' AND post_type != 'revision' AND post_status != 'trash' AND post_title != '' AND post_content != '' ORDER BY post_title ASC" );
$util = new SimpleForm_Util();
$ids_array = $util->form_pages($id);
$ordered_list = array_intersect($allpagesid,$ids_array);
$pages = '';
if( !empty($ordered_list) ) { 
foreach ($ordered_list as $page) { 
if( get_post_status($page) == 'draft' || get_post_status($page) == 'publish' ) {
$publish_link = '<strong><a href="' . get_edit_post_link($page) . '" target="_blank" class="publish-link">' . __( 'Publish now','simpleform') . '</a></strong>';	
$post_status = get_post_status($page) == 'draft' ? __( 'Page in draft status not yet published','simpleform').'&nbsp;-&nbsp;' . $publish_link : sprintf( __('%1$s or %2$s', 'simpleform'), '<strong><a href="' . get_edit_post_link($page) .'" target="_blank" style="text-decoration: none;">'. $edit .'</a></strong>', '<strong><a href="' . get_page_link($page) . '" target="_blank" style="text-decoration: none;">'. $view .'</a></strong>' );
switch (get_post_type($page)) {
case get_post_type($page) == 'wp_template':
$page_type = sprintf(__('"%s" template','simpleform'), get_the_title($page) );
$page_link = '<strong><a href="' . admin_url( 'site-editor.php?postType=wp_template' ) .'" target="_blank" style="text-decoration: none;">'. $edit .'</a></strong>';
break;
case get_post_type($page) == 'wp_template_part':
$page_type = sprintf(__('"%s" area','simpleform'), get_the_title($page) );
$page_link = '<strong><a href="' . admin_url( 'site-editor.php?postType=wp_template_part' ) .'" target="_blank" style="text-decoration: none;">'. $edit .'</a></strong>';
break;
default:
$page_type = sprintf(__('"%s" page','simpleform'), get_the_title($page) );
$page_link = $post_status;
}
$pages .= '<span>' .$page_type.'</span><span class="">&nbsp;[&nbsp;' . $page_link . '&nbsp;]<br>'; 
}
} 
}
$widget_block = get_option("widget_block") != false ? get_option("widget_block") : array();
if ( !empty($widget_block) ) {
//$block_id_list = esc_attr($form_data->widget_id) ? esc_attr($form_data->widget_id) : '';
$block_id_list = esc_attr($form_data->form_widgets) ? esc_attr($form_data->form_widgets) : '';

$block_id_array = $block_id_list ? explode(',',$block_id_list) : array();
if ($block_id_array) {
   foreach($block_id_array as $item) { 
   $split_key = ! empty($item) ? explode('block-', $item) : '';
   $block_key = isset($split_key[1]) ? $split_key[1] : '0';
   // Remove any non-existent ids
   if ( !in_array($block_key,array_keys($widget_block)) ) {
	$remove_id = array($item); 
    $new_ids = implode(",", array_diff($block_id_array,$remove_id));
    $wpdb->update($wpdb->prefix . 'sform_shortcodes', array('widget_id' => $new_ids), array('id' => $id ));
   }
   else { 
	$block_widget_area = $util->widget_area_name($block_key);
  	$pages .=  $block_widget_area ? '"'.$block_widget_area .'"&nbsp;'.__('widget area','simpleform').'&nbsp;[&nbsp;<strong><a href="' . self_admin_url('widgets.php') . '" target="_blank" style="text-decoration: none;">'. __( 'Edit','simpleform') .'</a></strong>&nbsp;]<br>' : ''; 
   }
   }
}   
}

// Check for form embedded in widget area 
$sform_widget = get_option("widget_sform_widget") != false ? get_option("widget_sform_widget") : array();
$widget_id = $wpdb->get_var( "SELECT widget FROM {$wpdb->prefix}sform_shortcodes WHERE id = {$id}" );
if ( !empty($sform_widget) && $widget_id != '0' && in_array($widget_id, array_keys($sform_widget)) ) { 
$widget_visibility = ! empty($sform_widget[$widget_id]['sform_widget_visibility']) ? $sform_widget[$widget_id]['sform_widget_visibility'] : 'all';
$hidden_pages = ! empty($sform_widget[$widget_id]['sform_widget_hidden_pages']) ? $sform_widget[$widget_id]['sform_widget_hidden_pages'] : '';        
$visible_pages = ! empty($sform_widget[$widget_id]['sform_widget_visible_pages']) ? $sform_widget[$widget_id]['sform_widget_visible_pages'] : '';
$show_for = ! empty($sform_widget[$widget_id]['sform_widget_audience']) ? $sform_widget[$widget_id]['sform_widget_audience'] : 'all';
if ( $show_for == 'out' ) { $target = __( 'Logged-out users','simpleform'); }
elseif ( $show_for == 'in' ) { $target = __( 'Logged-in users','simpleform'); }
else { $target = __( 'Everyone','simpleform'); }
$role = ! empty($sform_widget[$widget_id]['sform_widget_role']) ? $sform_widget[$widget_id]['sform_widget_role'] : 'any';
global $wp_roles;
$role_name = $role == 'any' ? __( 'Any','simpleform') : translate_user_role($wp_roles->roles[$role]['name']);      
if ( $widget_visibility == 'hidden' ) {	
   if ( ! empty($hidden_pages)) { 
     $pages_array = explode(',',$hidden_pages);
     $ordered_pages_array = array_intersect( $allpagesid, $pages_array);
	 $hidden_list = '';
     foreach ($ordered_pages_array as $post) { 
     if ( get_post_status($post) == 'draft' || get_post_status($post) == 'publish' ) {
         $publish_link = '<strong><a href="' . get_edit_post_link($post) . '" target="_blank" class="publish-link">' . __( 'Publish now','simpleform') . '</a></strong>';	
         $post_status = get_post_status($post) == 'draft' ? __( 'Page in draft status not yet published','simpleform').'&nbsp;-&nbsp;' . $publish_link : sprintf( __('%1$s or %2$s', 'simpleform'), '<strong><a href="' . get_edit_post_link($post) .'" target="_blank" style="text-decoration: none;">'. $edit .'</a></strong>', '<strong><a href="' . get_page_link($post) . '" target="_blank" style="text-decoration: none;">'. $view .'</a></strong>' );
         $hidden_list .= '<span>"' . get_the_title($post). '"&nbsp;'.__('page','simpleform').'</span><span class="">&nbsp;[&nbsp;' . $post_status . '&nbsp;]<br>'; 
     }
     }
     $pages .= empty($pages) ? '<span>' . __( 'Visible in all pages where the widget area is present except for the pages listed:','simpleform') . '</span><br>' . $hidden_list : '<br>' . '<span>' . __( 'Visible in all pages where the widget area is present except for the pages listed:','simpleform') . '</span><br>' . $hidden_list; 
   }
   else { $pages .= empty($pages) ? __( 'Visible in all pages where the widget area is present','simpleform') : '<br>' . __( 'Visible in all pages where the widget area is present','simpleform'); }
}
elseif ( $widget_visibility == 'visible' ) { 
   if ( ! empty($visible_pages)) {
     $pages_array = explode(',',$visible_pages);
     $ordered_pages_array = array_intersect( $allpagesid, $pages_array);
     $visible_list = '';		    
     foreach ($ordered_pages_array as $post) { 
     if( get_post_status($post) == 'draft' || get_post_status($post) == 'publish' ) {
         $publish_link = '<strong><a href="' . get_edit_post_link($post) . '" target="_blank" class="publish-link">' . __( 'Publish now','simpleform') . '</a></strong>';	
         $post_status = get_post_status($post) == 'draft' ? __( 'Page in draft status not yet published','simpleform').'&nbsp;-&nbsp;' . $publish_link : sprintf( __('%1$s or %2$s', 'simpleform'), '<strong><a href="' . get_edit_post_link($post) .'" target="_blank" style="text-decoration: none;">'. $edit .'</a></strong>', '<strong><a href="' . get_page_link($post) . '" target="_blank" style="text-decoration: none;">'. $view .'</a></strong>' );
         $visible_list .= '<span>"' . get_the_title($post). '"&nbsp;'.__('page','simpleform').'</span><span class="">&nbsp;[&nbsp;' . $post_status . '&nbsp;]<br>'; 
     }
     }
     $pages .=  empty($pages) ? __( 'Visible only in the listed pages where the widget area is present:','simpleform') . '<br>' . $visible_list : '<br>' . __( 'Visible only in the listed pages where the widget area is present:','simpleform') . '<br>' . $visible_list;
   }
   else { $pages .=  empty($pages) ? __( 'No page selected yet where the widget area is present','simpleform') : '<br>' . __( 'No page selected yet where the widget area is present','simpleform'); }
}
else {
   $pages .= empty($pages) ? __( 'Visible in all pages where the widget area is present','simpleform') : '<br>' . __( 'Visible in all pages where the widget area is present','simpleform'); 
}
}
if ( empty($pages) ) { 
	$prebuilt_page = '<b>' . __( 'pre-built page','simpleform') . '</b>';
	$form_page_ID = ! empty( $settings['form_pageid'] ) ? esc_attr($settings['form_pageid']) : '';  
	$prebuilt_page_link = ! empty($form_page_ID) ? '<a href="' . get_edit_post_link($form_page_ID) .'" target="_blank" style="text-decoration: none;">'. $prebuilt_page .'</a>' : '';
	$default_message_starting = __('The form has not yet been published','simpleform');
	$default_message_ending = ! empty($prebuilt_page_link) && $id == '1' ? '.&nbsp;' . sprintf('Get started with the %s!', $prebuilt_page_link ) : '';
	$pages = '<span>' . $default_message_starting . $default_message_ending . '</span>';	
}
$icon = SIMPLEFORM_URL . 'admin/img/copy_icon.png';
$tzcity = get_option('timezone_string'); 
$tzoffset = get_option('gmt_offset');
if ( ! empty($tzcity)) { 
$current_time_timezone = date_create('now', timezone_open($tzcity));
$timezone_offset =  date_offset_get($current_time_timezone);
$submission_timestamp = strtotime(esc_attr($form_data->creation)) + $timezone_offset;
}
else { 
$timezone_offset =  $tzoffset * 3600;
$submission_timestamp = strtotime(esc_attr($form_data->creation)) + $timezone_offset;  
}
$creation_date = date_i18n(get_option('date_format'),$submission_timestamp);
$where_day = ' AND date >= UTC_TIMESTAMP() - INTERVAL 24 HOUR';
$where_week = ' AND date >= UTC_TIMESTAMP() - INTERVAL 7 DAY';
$where_month = ' AND date >= UTC_TIMESTAMP() - INTERVAL 30 DAY';
$where_year = ' AND date >= UTC_TIMESTAMP() - INTERVAL 1 YEAR';
$where_submissions = defined('SIMPLEFORM_SUBMISSIONS_NAME') && $storing == 'true' ? "AND object != '' AND object != 'not stored'" : '';
// USE MOVING CHECK FOR REDUCE QUERIES
$count_all = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = $id $where_submissions");
$count_last_day = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = $id $where_submissions $where_day");
$count_last_week = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = $id $where_submissions $where_week");
$count_last_month = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = $id $where_submissions $where_month");
$count_last_year = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions WHERE form = $id $where_submissions $where_year");
$type = $widget_id != '0' && in_array($widget_id, array_keys($sform_widget)) ? '&nbsp[&nbsp' . __( 'shown on widget area', 'simpleform' ) . '&nbsp]' : '';

if ( $widget_id != '0' && in_array($widget_id, array_keys($sform_widget)) ) {
	
	 global $wp_registered_sidebars, $sidebars_widgets; foreach ( $sidebars_widgets as $sidebar => $widgets ) { if ( is_array( $widgets ) && $sidebar !== 'wp_inactive_widgets' ) { foreach ( $widgets as $key => $value ) { if ( strpos($value, 'sform_widget-'.$widget_id ) !== false ) { $widget_area = isset($wp_registered_sidebars[$sidebar]['name']) ? $wp_registered_sidebars[$sidebar]['name'] : ''; } } } }
	
}

?>

<div id="page-description"><p><?php _e( 'Full details on the form, including locks. You can move entries to another form, restore entries or delete the form:','simpleform') ?></p></div>

<div id="editor-tabs"><a class="form-button last <?php echo $color ?>" href="<?php $arg = $id != '1' ? '&form='. $id : ''; echo admin_url('admin.php?page=sform-settings') . $arg; ?>" target="_blank"><span><span class="dashicons dashicons-admin-settings"></span><span class="text"><?php _e( 'Settings', 'simpleform' ) ?></span></span></a><a class="form-button form-page <?php echo $color ?>" href="<?php $arg = $id != '1' ? '&form='. $id : ''; echo admin_url('admin.php?page=sform-editor') . $arg; ?>" target="_blank"><span><span class="dashicons dashicons-editor-table"></span><span class="text"><?php _e( 'Editor', 'simpleform' ) ?></span></span></a></div>
		
<form id="card" method="post" class="<?php echo $color ?>">
		
<h2 id="h2-specifics" class="options-heading"><span class="heading" section="specifics"><?php _e( 'Specifics', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 specifics"></span></span></h2>

<div class="section specifics"><table class="form-table specifics"><tbody>

<tr><th class="option"><span><?php _e('Form Name','simpleform') ?></span></th><td class="plaintext"><?php esc_attr_e($form_data->name) ?></td></tr>

<tr><th class="option"><span><?php _e('Form ID','simpleform') ?></span></th><td class="plaintext"><?php esc_attr_e($form_data->id) ?></td></tr>

<?php if ( $widget_id != '0' && in_array($widget_id, array_keys($sform_widget)) ) { ?>

<tr><th class="option"><span><?php _e('Shortcode','simpleform') ?></span></th><td class="plaintext"><?php  _e('Unavailable for widgets','simpleform') ?></td></tr>

<?php } 
else { ?>

<tr class=""><th class="option"><span><?php _e('Shortcode','simpleform') ?></span></th><td class="plaintext icon"><span id="shortcode">[<?php echo $shortcode ?>]</span><button id="shortcode-copy"><img src="<?php echo $icon ?>"></button><span id="shortcode-tooltip" class="unseen"><?php _e('Copy shortcode','simpleform') ?></span></td></tr>

<?php } ?>

<tr><th class="option"><span><?php _e('Status','simpleform') ?></span></th><td class="plaintext"><?php echo $status ?></td></tr>

<tr><th class="option"><span><?php _e('Creation Date','simpleform') ?></span></th><td class="plaintext"><?php echo $creation_date ?></td></tr>

<tr><th class="option"><span><?php _e('Entries','simpleform') ?></span></th><td id="tdentries" class="plaintext"><span id="entries"><?php esc_attr_e($form_data->entries) ?></span></td></tr>
	
<tr class="trmoved <?php if ( $form_data->entries == '0' && $form_data->moved_entries == '0' ) { echo 'unseen'; } ?>"><th class="option"><span><?php _e('Moved Entries','simpleform') ?></span></th><td class="plaintext"><span id="moved-entries"><?php esc_attr_e($form_data->moved_entries) ?></span></td></tr>

<?php // if ( esc_attr($form_data->status) == 'published' ) { ?>

<tr><th class="option"><span><?php _e('Visible to','simpleform') ?></span></th><td class="plaintext"><?php echo $target ?></td></tr>
	
<tr class="trlevel <?php if ( $show_for !='in') { echo 'unseen'; } ?>"><th class="option"><span><?php _e('Restricted to','simpleform') ?></span></th><td class="plaintext"><?php echo $role_name ?></td></tr>

<?php if ( ! empty($widget_area) ) { ?>

<tr><th class="option"><span><?php _e('Visible on','simpleform') ?></span></th><td class="plaintext widget"><?php echo $widget_area .'&nbsp;'.__('widget area','simpleform'); ?>&nbsp;[&nbsp;<a href="<?php echo self_admin_url('widgets.php') ?>" target="_blank" style="text-decoration: none"><b><?php _e( 'Edit widget', 'simpleform' ) ?></b></a>&nbsp;]</td></tr>

<tr><th class="option"><span><?php _e('Widget Visibility Rules','simpleform') ?></span></th><td class="used-page last"><?php echo $pages ?></td></tr> 

<?php } 
	
else { ?>	

<tr><th class="option"><span><?php _e('Visible on','simpleform') ?></span></th><td class="used-page last"><?php echo $pages ?></td></tr> 
	
<?php }
	
// } ?>

</tbody></table></div>

<h2 id="h2-admin" class="options-heading"><span class="heading" section="admin"><?php _e( 'Locks', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 admin"></span></span></h2>

<div class="section admin"><table class="form-table admin"><tbody>
	
<?php if ( count($shortcode_ids) > 1 ) { ?>	
	
<tr><th class="option"><span><?php _e('Moving','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input <?php if ( esc_attr($form_data->status) == 'trash' ) { echo 'disabled'; } ?>"><input type="checkbox" name="relocation" id="relocation" class="sform-switch" value="false" <?php if ( esc_attr($form_data->status) == 'trash' ) { echo 'disabled="disabled"'; } else { checked( $relocation, 'true'); } ?>><span></span></label><label for="relocation" class="switch-label <?php if ( esc_attr($form_data->status) == 'trash' ) { echo 'disabled'; } ?>"><?php _e( 'Allow the entries to be moved from one form to another','simpleform') ?></label></div></td></tr>

<tr class="trmoving <?php if ( $relocation != 'true' ) { echo 'unseen'; } ?>"><th class="option"><span><?php _e('Move To','simpleform') ?></span></th><td class="select"><select name="moveto" id="moveto" class="sform <?php echo $color ?>"><option value=""><?php _e( 'Select a form to move entries to', 'simpleform' ) ?></option><?php $forms = $wpdb->get_results( "SELECT id, name FROM {$wpdb->prefix}sform_shortcodes WHERE id != '$id' AND status != 'trash' ORDER BY name ASC", 'ARRAY_A' ); foreach($forms as $form) { $formid = $form['id']; $name = $form['name']; $selected_option = $moveto != '' && $to_be_moved != '' && $onetime_moving != 'true' ? selected($moveto,$formid) : ''; echo '<option value="'.$formid.'" ' . $selected_option .'>'.$name.'</option>'; } ?></select><span class="message unseen"></span></td></tr>

<tr class="trmoveto <?php if ( $relocation !='true' || ( $form_data->entries == '0' && $to_be_moved != 'next' ) || $moveto == '' || $onetime_moving == 'true' ) { echo 'unseen'; } ?>"><th class="option"><span><?php _e('Entries to be moved','simpleform') ?></span></th><td class="select"><select name="starting" id="starting" class="sform <?php echo $color ?>"><option value=""><?php _e( 'Select entries', 'simpleform' ) ?></option><?php if ( $count_all != $count_last_year /* || $options > 1 */ ) { ?><option value="all"><?php _e( 'All', 'simpleform' ) ?></option><?php } if ( $count_last_year > 0 && $count_last_year != $count_last_month ) { ?><option value="lastyear"><?php _e( 'Last year', 'simpleform' ) ?></option><?php } if ( $count_last_month > 0 && $count_last_month != $count_last_week ) { ?><option value="lastmonth"><?php _e( 'Last month', 'simpleform' ) ?></option><?php } if ( $count_last_week > 0 && $count_last_week != $count_last_day ) { ?><option value="lastweek"><?php _e( 'Last week', 'simpleform' ) ?></option><?php } if ( $count_last_day > 0 ) { ?><option value="lastday"><?php _e( 'Last day', 'simpleform' ) ?></option><?php } ?><option value="next" <?php selected($to_be_moved,'next') ?>><?php _e( 'Not received yet', 'simpleform' ) ?></option></select><span class="message unseen"></span></td></tr>

<tr class="tronetime unseen"><th class="option"><span><?php _e('One-time Moving','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="onetime" id="onetime" class="sform-switch" value="false" <?php checked( $onetime_moving, 'true') ?>><span></span></label><label for="onetime" class="switch-label"><?php _e( 'Disable moving after entriess have been moved','simpleform') ?></label></div><p class="description onetime invisible"><?php _e('The moving is kept active for next entries that will be received', 'simpleform' ) ?></p></td></tr>

<tr class="trsettings <?php if ( $relocation != 'true' || $moveto == '' || $to_be_moved == '' || ( $to_be_moved != 'next' && $onetime_moving == 'true' ) ) { echo 'unseen'; } ?>"><th class="option"><span><?php _e('Notifications','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="settings" id="settings" class="sform-switch" value="false" <?php checked( $notifications_settings, 'true') ?>><span></span></label><label for="settings" class="switch-label"><?php _e( 'Use the notifications settings of form to which entries are moved','simpleform') ?></label></div><p class="description settings <?php if ( $notifications_settings == 'true' ) { echo 'invisible'; } ?>"><?php _e('By default, the moved entries comply with the notifications settings of form from which are moved', 'simpleform' ) ?></p></td></tr>

<tr class="trrestore <?php if ( $form_data->moved_entries == '0' ) { echo 'unseen'; } ?>"><th class="option"><span><?php _e('Restore entries','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="restore" id="restore" class="sform-switch" value="false"><span></span></label><label for="restore" class="switch-label"><?php _e( 'Restore the moved entries','simpleform') ?></label></div></td></tr>

<?php } ?>

<tr><th class="option"><span><?php _e('Deletion','simpleform') ?></span></th><td class="checkbox-switch <?php if ( $id == '1' ) { echo 'last notes default'; } ?>"><div class="switch-box"><label class="switch-input <?php if ( $id == '1' || esc_attr($form_data->status) == 'trash' ) { echo 'disabled'; } ?>"><input type="checkbox" name="deletion-form" id="deletion-form" class="sform-switch" value="false" <?php if ( $id == '1' || esc_attr($form_data->status) == 'trash' ) { echo 'disabled="disabled"'; } checked( $deletion, 'true'); ?>><span></span></label><label for="deletion-form" class="switch-label <?php if ( $id == '1' || esc_attr($form_data->status) == 'trash' ) { echo 'disabled'; } ?>"><?php _e( 'Allow the form to be deleted','simpleform'); ?></label></div></div><?php if ( $id == '1' ) { ?> <p class="description invisible"><?php _e('The default form cannot be deleted', 'simpleform' ); ?></p><?php } ?></td></tr>

</tbody></table></div>

<div id="card-submit-wrap">

<?php if ( count($shortcode_ids) > 1 ) { ?>	

<div id="alert-wrap">
<noscript><div id="noscript"><?php _e('You need JavaScript enabled to edit form. Please activate it. Thanks!', 'simpleform' ) ?></div></noscript>
<div id="message-wrap" class="message"></div>
</div>

<div id="form-buttons">
<input type="submit" name="save-card" id="save-card" class="submit-button" value="<?php esc_attr_e( 'Save Changes', 'simpleform' ) ?>">
<?php if ( esc_attr($form_data->status) != 'trash' ) { ?>
<span id="deletion-toggle" style="margin-left: 30px; float: none !important; padding: 10px 12px !important; border-radius: 8px !important; line-height: 2.15384615;" class="submit-button XXXdeletion <?php if ( $deletion !='true') {echo 'unseen ';} echo $color; ?>"><?php _e( 'Delete Form', 'simpleform' ) ?></span>
<?php } ?>

</div>

<input type="hidden" id="form-id" name="form-id" value="<?php echo $id ?>">
<input type="hidden" id="form-to" name="form-to" value="<?php if ( $moveto != '' && $to_be_moved != '' && $onetime_moving != 'true' ) { $to_name = $wpdb->get_var( $wpdb->prepare( "SELECT name FROM {$wpdb->prefix}sform_shortcodes WHERE id = %d", $form_data->moveto) ); echo $to_name; } ?>">
<input type="hidden" id="submissions" name="submissions" value="<?php esc_attr_e($form_data->entries) ?>">
<input type="hidden" id="moved-submissions" name="moved-submissions" value="<?php esc_attr_e($form_data->moved_entries) ?>">
<?php  wp_nonce_field( 'ajax-verification-nonce', 'verification_nonce'); ?>

</form>

<?php if ( esc_attr($form_data->status) != 'trash' ) { ?>
<div id="deletion-notice" class="unseen">
<form id="deletion" method="post"   style="">
<input type="hidden" id="form-id" name="form-id" value="<?php echo $id ?>">
<input type="hidden" id="form-name" name="form-name" value="<?php esc_attr_e($form_data->name) ?>">
<div id="hidden-confirm"></div>
<h3 class="deletion"><span class="dashicons dashicons-trash"></span><?php _e( 'Delete Form', 'simpleform' ) ?>:&nbsp;<?php esc_attr_e($form_data->name) ?></h3><div class="disclaimer"><span id="default">
<?php 
// _e( 'Deleting a form is permanent. Once a form is deleted, it can\'t be restored. All submissions to that form are permanently deleted too.', 'simpleform' ) 
$automatic_delete = ! empty( $settings['automatic_delete'] ) && esc_attr($settings['automatic_delete']) == 'true' ? sprintf( _n( 'The deleted form will be purged after %s day.', 'The deleted form will be purged after %s days.', $automatic_delete ), $automatic_delete ) : '.';
_e( 'Deleting a form involves its permanent removal from pages and widgets, and its moving to trash.', 'simpleform' );
echo '&nbsp;';
//	_e( 'Note that the deleted form will be moved to the trash folder, and will be purged after 30 days.', 'simpleform' ) 
_e( 'That gives you a chance to restore the form in case you change your mind, but you\'ll need to re-insert it into a page or widget to make it visible again.', 'simpleform' )
?>
</span><span id="confirm"></span></div><div id="deletion-buttons"><div class="delete cancel"><?php _e( 'Cancel', 'simpleform' ) ?></div><input type="submit" class="delete" id="deletion-confirm" name="deletion-confirm" value="<?php esc_attr_e( 'Continue with deletion', 'simpleform' ) ?>"></div><?php wp_nonce_field( 'sform_nonce_deletion', 'sform_nonce'); ?>
</form>	
</div>
<?php } ?>

<?php } ?>

</div>

<?php
} 

else { ?>
<div id="page-description"><p><?php _e( 'It seems the form is no longer available!','simpleform') ?></p></div>
<p><span class="wp-core-ui button unavailable <?php echo $color ?>"><a href="<?php echo menu_page_url( 'sform-forms', false ); ?>"><?php _e('Reload the forms page','simpleform') ?></a></span></p>
<?php } ?>
	
</div>