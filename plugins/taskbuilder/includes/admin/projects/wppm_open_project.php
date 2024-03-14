<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$current_user,$wppmfunction;
$wppm_project_time = get_option('wppm_project_time');
$wppm_default_project_date = get_option('wppm_default_project_date');
$wppm_public_projects_permission = get_option('wppm_public_projects_permission');
$current_date = date('Y-m-d');
$id = isset($_POST) && isset($_POST['id']) ? intval(sanitize_text_field($_POST['id'])) : 0;
if (!$id) {exit;}
$project = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wppm_project where id = $id" );
$users = explode(",",$project->users);
$wppm_users_role = get_option('wppm_user_role');
if(isset($project->status)){
  $project_status = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_project_statuses where id=".$project->status);
}
if(isset($project->cat_id)){
  $project_category = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_project_categories where id=".$project->cat_id);
}
if(!empty($project->description)){
  $project_description = stripslashes((htmlspecialchars_decode($project->description, ENT_QUOTES)));
}
if($wppm_project_time == 1){
 $proj_start_date = $project->start_date;
 $proj_end_date = $project->end_date;
} elseif($wppm_project_time == 0){
  $psDate = new DateTime($project->start_date);
  $peDate = new DateTime($project->end_date);
  $proj_start_date = $psDate->format('Y-m-d');
  $proj_end_date = $peDate->format('Y-m-d');
}

?>
<form id="wppm_open_project" method="post">
  <div class="row">
    <div class="col-sm-12">
      <span class="wppm-heading-inline"><?php echo esc_html_e('Project','taskbuilder');?></span>
      <?php if($current_user->has_cap('manage_options')){ ?>
              <span class="wppm-add-new-btn btn-primary" id="wppm_add_new_project_btn" onclick="wppm_add_new_project()"><span style="margin-right:5px;"><img id="wppm_add_new_project_icon" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/plus_icon.svg'); ?>" alt="plus_icon"></span><span><?php echo esc_html_e('Add New','taskbuilder');?></span></span>
      <?php } ?>
      <span class="wppm-add-new-btn btn-primary" id="wppm_project_list" onclick="wppm_get_project_list()" ><span style="margin-right:5px;"><img id ="wppm_project_list_icon" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/list-symbol.svg'); ?>" alt="list-symbol"></span><span><?php echo esc_html_e('Project List','taskbuilder');?></span></span>
      <span class="wppm-add-new-btn btn-primary" id="wppm_project_tasks" onclick="wppm_get_project_tasks(<?php echo esc_attr($id) ?>)"><span style="margin-right:5px;"><img id ="wppm_task_list_icon" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/task_list.svg'); ?>" alt="task_list"></span><span><?php echo esc_html_e('Tasks','taskbuilder');?></span></span>
      <?php $style = (($current_user->ID && $current_user->has_cap('manage_options')) || ($wppmfunction->has_project_permission('delete_project',$project->id)))? "display:inline":"display:none"; ?>
      <span class="wppm-add-new-btn btn-primary" id="wppm_delete_project_btn" onclick="wppm_delete_project(<?php echo esc_attr($id) ?>)" style="<?php echo esc_attr($style) ?>"><span style="margin-right:5px;"><img id="wppm_delete_project_icon" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/delete2.svg'); ?>" alt="delete"></span><span><?php echo esc_html_e('Delete','taskbuilder');?></span></span>
      <?php $visibility_style = (($current_user->ID && $current_user->has_cap('manage_options')) && ($wppm_public_projects_permission==1))? "display:inline":"display:none"; ?>
      <span class="wppm-add-new-btn btn-primary" id="wppm_project_visibility" onclick="wppm_get_project_visibility(<?php echo esc_attr($id) ?>)" style="<?php echo esc_attr($visibility_style) ?>"><span style="margin-right:5px;"><img id ="wppm_task_list_icon" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/wppm_visibility.svg'); ?>" alt="project_visibility"></span><span><?php echo esc_html_e('Project Visibility','taskbuilder');?></span></span>
      <?php echo do_action('wppm_after_open_project_buttons',$project->id);?>
    </div>
  </div>
  <div id="wppm_load_individual_project_container" class="row">
      <div class="col-sm-8 wppm_body col-md-9">
        <div class="row">
          <div class="col-sm-12" id="wppm_project_details_container">
            <div class="row">
              <div class="col-sm-12">
                <span class="wppm_project_label"> <?php echo esc_html($project->project_name);
                      if (($wppmfunction->has_project_permission('change_project_details',$id)) || ($current_user->has_cap('manage_options'))) { ?>
                        <span id="wppm_individual_edit_project_subject" onclick="wppm_edit_proj_details(<?php echo esc_attr($id) ?>)" class="btn btn-sm wppm_action_btn" style="background-color:#FFFFFF !important;color:#000000 !important;"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/edit_01.svg'); ?>" alt="edit"></span>
                <?php } ?>
                </span>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-3">
                <span class="wppm_project_details_label"><?php echo esc_html_e('Created On:','taskbuilder');?></span>
              </div>
              <div class="col-sm-9">
                <span class="wppm_project_details"><?php echo esc_html($project->date_created) ?></span>
              </div>
            </div>
            <?php if($wppm_default_project_date==1) { ?>
                    <div class="row">
                      <div class="col-sm-3">
                        <span class="wppm_project_details_label"><?php echo esc_html_e('Start Date:','taskbuilder');?></span>
                      </div>
                      <div class="col-sm-9">
                        <span class="wppm_project_details"><?php echo (isset($proj_start_date))? esc_html($proj_start_date): "" ?></span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-3">
                      <span class="wppm_project_details_label"><?php echo esc_html_e('End Date:','taskbuilder');?></span>
                      </div>
                      <div class="col-sm-9">
                      <?php $style = ($project->status!=4 && $proj_end_date < $current_date) ? "color:#FF0000":"color:#2C3E50"; ?>
                        <span class="wppm_project_details" style="<?php echo esc_attr($style); ?>"><?php echo (isset($proj_end_date))? esc_html($proj_end_date):"" ?></span>
                      </div>
                    </div>
            <?php } ?>
            <div class="row">
              <div class="col-sm-3">
                <span class="wppm_project_details_label"><?php echo esc_html_e('Project Category:','taskbuilder');?></span>
              </div>
              <div class="col-sm-9">
                <span class="wppm_project_details"><?php echo (isset($project_category->name))? esc_html_e($project_category->name,'taskbuilder'):"" ?></span>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-3">
                <span class="wppm_project_details_label"><?php echo esc_html_e('Description:','taskbuilder');?></span>
              </div>
              <?php
              $allowedtags = array( 'br' => array(), 'abbr' => array('title' => array(),), 'p' => array(), 'strong' => array(), 'a' => array('href' => array(), 'title' => array(),'target'=> array(), 'rel'=>array()),'em' =>array(),'span' =>array(), 'blockquote'=>array('cite'  => array(),),'div' => array('class' => array(),'title' => array(),'style' => array(),),'ul'=>array(),'li'=>array(),'ol'=>array(),'img' => array( 'alt'=> array(),'class' => array(),'height' => array(),'src'=> array(),'width'=> array(),));
              ?>
              <div class="col-sm-9 wppm_project_description">
                <span><?php  echo (isset($project_description))?  wp_kses(wpautop($project_description),$allowedtags):"" ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="wppm_individual_project_widget col-sm-4 wppm_sidebar col-md-3">
        <div class="row wppm_widget" id="wppm_project_status_widget">
            <h4 class="widget_header"><?php echo esc_html_e('Status','taskbuilder')?>
            <?php $style = ($wppmfunction->has_project_permission('change_project_status',$id) || $current_user->has_cap('manage_options'))? "display:inline":"display:none"; ?>
            <span class="wppm_edit_project_details_widget" style="<?php echo esc_attr($style) ?>" onclick="wppm_edit_project_status(<?php echo esc_attr($id) ?>)"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/edit_01.svg'); ?>" alt="edit"></span></h4>
            <hr class="widget_divider">
            <div class="wppm_sidebar_labels"><span class="wppm_label_info"><?php echo esc_html_e('Status','taskbuilder')?>:</span> <span class="wppm_admin_label" style="background-color:<?php echo esc_attr($project_status->bg_color) ?>;color:<?php echo esc_attr($project_status->color)?>;"><?php echo esc_attr($project_status->name) ?> </span></div>
        </div>
        <div class="row wppm_widget" id="wppm_project_raisedby_widget">
          <h4 class="widget_header">
            <?php if ($current_user->has_cap('manage_options')) { ?>
                    <span class="wppm_edit_project_details_widget" onclick="wppm_edit_project_creator(<?php echo esc_attr($id) ?>)"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/edit_01.svg'); ?>" alt="edit"></span>
            <?php } ?>
            <span><?php echo esc_html_e('Project Creator','taskbuilder')?></span></h4>
            <hr class="widget_divider"> <?php
            $user_info = get_userdata($project->created_by);
            ?>
            <div id="wppm_project_creator">
              <div style="padding:2px 0;">
                  <span class="wppm_project_user_avatar"><?php echo (get_avatar($project->created_by, 25, "mysteryman")); ?></span>
                  <span class="wppm_project_user_names"><?php echo (!empty($user_info)) ? esc_html_e($user_info->display_name,'taskbuilder') : "";?></span>
              </div>
            </div>
        </div>
        <div class="row wppm_widget" id="wppm_project_users_widget_container">
          <h4 class="widget_header">
          <?php if ($wppmfunction->has_project_permission('assign_project_users',$id ) || $current_user->has_cap('manage_options')) { ?>
                    <span class="wppm_edit_project_details_widget" onclick="wppm_get_users(<?php echo esc_attr($id) ?>)"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/edit_01.svg'); ?>" alt="edit"></span>
          <?php } ?>
          <span><?php echo esc_html_e('Users','taskbuilder')?></span></h4>
          <hr class="widget_divider">
          <div id="wppm_project_users">
            <?php 
            if(!(empty(array_filter($users)))){
              foreach($users as $user) { 
                $user_data = get_userdata($user);
                $project_user = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_project_users WHERE proj_id = $id AND user_id = $user");
                ?>
                <div style="padding:2px 0;">
                  <span class="wppm_project_user_avatar"><?php echo (get_avatar($user, 25, "mysteryman")); ?></span>
                  <span class="wppm_project_user_names"><?php echo esc_html_e($user_data->display_name,'taskbuilder'); ?></span>
                  <?php 
                  if(!empty($wppm_users_role)){ 
                    foreach($wppm_users_role as $key=>$role){
                      if(!empty($role)){
                        foreach($role as $k=>$val){
                          if( !empty($project_user) && $key == $project_user->role_id){ ?>
                            <span class="wppm_project_user_role">(<?php echo esc_html_e($role['label'],'taskbuilder'); ?>)</span><?php 
                          }
                        }
                      }
                    }
                  }
                  ?>
                </div>
              <?php 
              }
            } else {
              ?><span class="wppm_project_users_not_assign_label"> <?php echo esc_html_e('None','taskbuilder'); ?></span><?php
            }
            ?>
          </div>
        </div>
      </div>
  </div>
</form>
<script>
jQuery( document ).ready( function( jQuery ) {
  tinymce.remove();
  tinymce.init({ 
    selector:'#wppm_proj_description',
    body_id: 'wppm_proj_description',
    directionality : '<?php //echo 'rtl'; ?>',
    menubar: false,
    statusbar: false,
    height : '200',
    plugins: [
        'lists link image directionality'
    ],
    image_advtab: true,
    toolbar: 'bold italic underline blockquote | alignleft aligncenter alignright | bullist numlist | rtl | link image',
    branding: false,
    autoresize_bottom_margin: 20,
    browser_spellcheck : true,
    relative_urls : false,
    remove_script_host : false,
    convert_urls : true,
    setup: function (editor) {
    }
  });
});
</script>