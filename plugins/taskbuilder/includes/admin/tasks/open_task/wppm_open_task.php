<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb,$wppmfunction;
$id = isset($_POST) && isset($_POST['id']) ? intval(sanitize_text_field($_POST['id'])) : "0";
$task_list_view = get_option('wppm_default_task_list_view');
$wppm_default_task_date = get_option('wppm_default_task_date');
$wppm_task_time = get_option('wppm_task_time');
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || ($wppmfunction->has_permission('view_task',$id)))) {exit;}
$proj_id = isset($_POST) && isset($_POST['proj_id']) ? intval(sanitize_text_field($_POST['proj_id'])) : 0;
$auth_id = $wppmfunction->wppm_get_auth_code($id);
$auth_id = sanitize_text_field($auth_id);
$current_date = date('Y-m-d');
if(!empty($id)){
  $task = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wppm_task where id=".$id);
  $project_data = $wppmfunction->get_project($task->project);
  $task_comment = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wppm_task_comment where task_id=".$id." ORDER BY create_time DESC");
}
$user_role = $wpdb->get_var( "SELECT role_id FROM {$wpdb->prefix}wppm_project_users where proj_id = $task->project AND user_id = $current_user->ID");
if(!empty($task->project)){
  $project_name = $wpdb->get_var( "SELECT project_name FROM {$wpdb->prefix}wppm_project where id=".$task->project);
}
$wppm_checklist=$wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wppm_checklist where task_id=".$task->id );
if(!empty($task->status)){
  $task_status = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wppm_task_statuses where id=".$task->status);
}
if(!empty($task->priority)){
  $task_priority = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wppm_task_priorities where id=".$task->priority);
}
if(!empty($task->users)){
  $users = explode(",",$task->users);
} 

if(!empty($task->description)){
  $task_description = stripslashes((htmlspecialchars_decode($task->description, ENT_QUOTES)));
}

if($wppm_task_time == 1){
  $task_start_date = $task->start_date;
  $task_end_date = $task->end_date;
} elseif($wppm_task_time == 0){
    $tsDate = new DateTime($task->start_date);
    $teDate = new DateTime($task->end_date);
    $task_start_date = $tsDate->format('Y-m-d');
    $task_end_date = $teDate->format('Y-m-d');
}
?>
<form id="wppm_open_task" method="post">
  <div class="wppm_headers row">
      <div class="col-sm-8">
        <span class="wppm-heading-inline">
          <?php echo esc_html_e('Task','taskbuilder');?>
        </span>
        <?php if($proj_id==0) { ?>
                <span onclick="wppm_add_new_task(<?php echo esc_attr($proj_id) ?>)" class="wppm_add_new_task_btn" id="wppm_add_new_task_btn" title="Create Task"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/plus.svg'); ?>" alt="add"></span>
        <?php } 
        else{ ?>
            <span class="wppm_add_new_task_btn" id="wppm_add_new_task_btn" title="Create Task" onclick="wppm_create_project_task(<?php echo esc_attr($proj_id) ?>)"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/plus.svg'); ?>" alt="add"></span>
        <?php } ?>
        <?php if($proj_id==0) { 
                 $task_list_function =  ($task_list_view==1)?"wppm_get_task_list()":"wppm_view_task_search_filter()";
                ?>
                <span class="wppm-task-list-btn" id="wppm_task_list_btn" title="Task List" onclick="<?php echo $task_list_function ?>"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/list.svg'); ?>" alt="list"></span>
        <?php } 
        else{
          ?>
          <span class="wppm-task-list-btn" id="wppm_task_list_btn" title="Task List" onclick="wppm_get_project_tasks(<?php echo esc_attr($proj_id) ?>)"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/list.svg'); ?>" alt="list"></span>
        <?php } ?>
        <?php $style = (($current_user->ID && $current_user->has_cap('manage_options')) || ($wppmfunction->has_permission('delete_task',$id)))? "display:inline":"display:none"; ?>
       <?php $clone_style = (($current_user->ID && $current_user->has_cap('manage_options')) || ($wppmfunction->has_permission('clone_task',$id)))? "display:inline":"display:none"; ?>
        <span class="wppm-delete_task_btn" id="wppm_delete_task_btn" title="Delete Task" onclick="wppm_get_delete_task(<?php echo esc_attr($id) ?>)" style="<?php echo esc_attr($style) ?>"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/bin.svg'); ?>" alt="delete"></span>
        <div class="wppm-clone_task_btn" id="wppm_clone_task_btn" title="Clone Task" onclick="wppm_clone_task(<?php echo esc_attr($id) ?>)" style="<?php echo esc_attr($clone_style) ?>"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/duplicate.svg'); ?>" alt="clone"></div>
        <?php do_action('wppm_individual_task_button',$id);?>
      </div>
  </div>
  <div id="wppm_load_individual_task_container" class="row">
    <div class="col-sm-8 wppm_body col-md-9">
      <div class="wppm_task_details_container">
        <div class="row">
          <div class="col-sm-12">
            <span class="wppm_task_label"> <?php echo esc_html_e($task->task_name,'taskbuilder');
              if ($wppmfunction->has_permission('change_task_details',$id) || $current_user->has_cap('manage_options')) { ?>
                  <span id="wppm_individual_edit_task_subject" onclick="wppm_edit_task_details(<?php echo esc_attr($id) ?>,<?php echo esc_attr($proj_id) ?>)" class="btn btn-sm wppm_action_btn" style="background-color:#FFFFFF !important;color:#000000 !important;"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/edit_01.svg'); ?>" alt="edit"></span>
              <?php } ?>
              </span>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-3">
            <span class="wppm_task_details_label"><?php echo esc_html_e('Project:','taskbuilder');?></span>
          </div>
          <div class="col-sm-9">
            <span class="wppm_task_details"><?php echo (isset($project_name))? esc_html_e($project_name,'taskbuilder'):""  ?></span>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-3">
            <span class="wppm_task_details_label"><?php echo esc_html_e('Created On:','taskbuilder');?></span>
          </div>
          <div class="col-sm-9">
            <span class="wppm_task_details"><?php echo (isset($task->date_created))? esc_html($task->date_created):"" ?></span>
          </div>
        </div>
        <?php if($wppm_default_task_date == 1){ ?>
                <div class="row">
                  <div class="col-sm-3">
                    <span class="wppm_task_details_label"><?php echo esc_html_e('Start Date:','taskbuilder');?></span>
                  </div>
                  <div class="col-sm-9">
                    <span class="wppm_task_details"><?php echo (isset($task_start_date))? esc_html($task_start_date):"" ?></span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-3">
                    <span class="wppm_task_details_label"><?php echo esc_html_e('End Date:','taskbuilder');?></span>
                  </div>
                  <div class="col-sm-9">
                    <?php $style = ($task->status!=4 && $task->end_date < $current_date) ? "color:#FF0000":"color:#2C3E50"; ?>
                    <span class="wppm_task_details" style="<?php echo esc_attr($style); ?>"><?php echo (isset($task_end_date))? esc_html($task_end_date):"" ?></span>
                  </div>
                </div>
        <?php } ?>
        <div class="row">
          <div class="col-sm-3">
            <span class="wppm_task_details_label"><?php echo esc_html_e('Task Priority:','taskbuilder');?></span>
          </div>
          <div class="col-sm-9">
            <span class="wppm_task_details_priority"><span class="wppm_admin_label" style="background-color:<?php echo (isset($task_priority->bg_color))? esc_html($task_priority->bg_color):""?>;color:<?php echo (isset($task_priority->color))? esc_html($task_priority->color):""?>;"><?php echo (isset($task_priority->name)) ? esc_html_e($task_priority->name,'taskbuilder'):"" ?> </span></span>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-3">
            <span class="wppm_task_details_label"><?php echo esc_html_e('Description:','taskbuilder');?></span>
          </div>
          <?php
          $allowedtags = array( 'br' => array(), 'abbr' => array('title' => array(),), 'p' => array(), 'strong' => array(), 'a' => array('href' => array(), 'title' => array(),'target'=> array(), 'rel'=>array()),'em' =>array(),'span' =>array(), 'blockquote'=>array('cite'  => array(),),'div' => array('class' => array(),'title' => array(),'style' => array(),),'ul'=>array(),'li'=>array(),'ol'=>array(),'img' => array( 'alt'=> array(),'class' => array(),'height' => array(),'src'=> array(),'width'=> array(),));?>
          <div class="col-sm-9">
            <span class="wppm_task_description"><?php echo (isset($task_description))? wp_kses(wpautop($task_description),$allowedtags):"" ?></span>
          </div>
        </div>
        <?php do_action('wppm_after_task_details_in_open_task',$task->id, $task->project);?>
      </div>
      <div class="wppm_task_checklist_container" id="wppm_task_checklist_container">
        <div id="wppm_checklist_container">
          <?php 
          if(!empty($wppm_checklist)){
            foreach( $wppm_checklist as $key=>$list){
              ?>
              <div class="row">
                <div class="col-sm-8">
                  <span class="wppm_checklist_label"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/checklist.svg'); ?>" alt="checklist"><?php echo esc_html_e($list->checklist_name,'taskbuilder');?></span>
                </div>
                <?php $check_style = (($list->created_by == $current_user->ID || $current_user->has_cap('manage_options') || $user_role == 1))? "display:inline":"display:none"; ?>
                <?php $disabled = (($list->created_by == $current_user->ID || $current_user->has_cap('manage_options') || $user_role == 1))? "":"disabled"; ?>
                <div class="col-sm-4" style="text-align:right;<?php echo esc_attr($check_style) ?>">
                  <a class="wppm_delete_checklist" onclick="wppm_delete_checklist(<?php echo esc_attr($list->id);?>,<?php echo esc_attr($task->id);?>,<?php echo esc_attr($proj_id);?>)"><?php echo esc_html_e('Delete Checklist','taskbuilder');?></a>
                </div>
              </div> 
              <div class="wppm_progress_bar_container row">
                <div class="col-sm-4">
                    <span class="wppm_progressbar_label" id="wppm_progressbar_label_<?php echo esc_attr($list->id)?>">0%</span>
                </div>
                <div class="wppm_progress_bar col-sm-8">
                  <div id="CheckProgress_<?php echo esc_attr($list->id);?>" class="wppm_checkprogress" role="progressbar" style="width: 0%" aria-valuemin="0" aria-valuemax="100"></div>   
                </div>
              </div>
              <?php
              $wppm_checklist_items = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wppm_checklist_items where checklist_id=".$list->id);
              ?>
              <?php
                if(!empty( $wppm_checklist_items)){
                  foreach($wppm_checklist_items as $key=>$val){
                    $checked = $val->checked == 1 ? 'checked="checked"' : '';
                    ?>
                    <div id="wppm_checklist_<?php echo esc_attr($val->id) ?>" class="row" style="margin-bottom:10px;font: 15px 'Helvetica Neue',Helvetica,Arial,sans-serif;">
                      <div class="col-sm-8" id="wppm_checklist_item_container_<?php echo esc_attr($val->id) ?>">
                        <input id="wppm_checklist_item_<?php echo esc_attr($val->id) ?>" class="wppm_checklist_item_<?php echo esc_attr($val->checklist_id) ?>" onchange="wppm_checklist_progress(<?php echo esc_attr($val->checklist_id) ?>,<?php echo esc_attr($val->id) ?>,<?php echo esc_attr($task->id) ?>)" type="checkbox" name="wppm_checklist" <?php echo esc_attr($checked) ?> value="<?php echo esc_attr($val->id) ?>" <?php echo esc_attr($disabled) ?> style="margin:5px;"><label style="padding-top: 5px;font-weight: normal;font: 13px 'Helvetica Neue',Helvetica,Arial,sans-serif;"><?php  echo esc_html_e($val->item_name,'taskbuilder') ?></label>
                      </div>
                      <?php if ($list->created_by == $current_user->ID || $current_user->has_cap('manage_options') || $user_role == 1) { ?>
                      <div class="col-sm-4" id="wppm_checklist_action_<?php echo esc_attr($val->id) ?>" style="text-align:right; display:none;cursor:pointer;">
                          <span onclick=wppm_delete_checklist_item(<?php echo esc_attr($val->id) ?>,<?php echo esc_attr($val->checklist_id) ?>,<?php echo esc_attr($proj_id);?>,<?php echo esc_attr($id) ?>)><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/trash.svg'); ?>" alt="delete"></span>
                      </div>
                      <?php } ?>
                    </div>
                    <?php
                  }
                } ?>
              <div class="row">
                <div class="col-sm-12 wppm_add_checklist_item_container_<?php echo esc_attr($list->id) ?>" style="padding-top:15px;">
                  <a class="wppm_add_checklist" id="wppm_add_checklist_item_btn_<?php echo esc_attr($list->id) ?>" onclick="wppm_add_checklist_item(<?php echo esc_attr($list->id)?>)"><?php echo esc_html_e('+ Add item','taskbuilder');?></a>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12" style="display:none;" id="wppm_add_checklist_label_container_<?php echo esc_attr($list->id) ?>">
                  <div class="row" id="wppm_add_new_checklist_item_<?php echo esc_attr($list->id) ?>">
                    <div class="col-sm-12">
                      <input type="text" id="wppm_checklist_item_label_<?php echo esc_attr($list->id) ?>" class="wppm_checklist_item_label_<?php echo esc_attr($list->id) ?>" placeholder="<?php echo esc_attr__('Add an item','taskbuilder') ?>" name="wppm_checklist_item_label" style="font: 13px 'Helvetica Neue',Helvetica,Arial,sans-serif;">
                      <input type="button" value="Add" class="btn btn-success btn-sm wppm_add_new_item_btn_<?php echo esc_attr($list->id); ?>" onclick="wppm_add_new_checklist_item(<?php echo esc_attr($list->id)?>,<?php echo esc_attr($task->id)?>,<?php echo esc_attr($proj_id) ?>)">
                      <span onclick="wppm_remove_add_checklist_container(<?php echo esc_attr($list->id) ?>)"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/cancel.svg'); ?>" alt="delete"></span>
                    </div>
                  </div>
                  </br>
              </div>
            </div>
            <hr class="wppm_checklist_divider">
            <?php
            }
          } ?>
        </div>
        <div id="wppm_add_checklist_container">
          <div class="row">
            <div class="col-sm-12">
              <span id="wppm_add_checklist" onclick="wppm_add_checklist()"> <?php echo esc_html_e('+Add a checklist','taskbuilder')?> </span>
            </div>
          </div>
          <div class="row" id="wppm_add_new_checklist" style="display:none;">
            <div class="col-sm-12">
              <input type="text" class="wppm_checklist_label" placeholder="<?php echo esc_attr__('Please insert checklist title','taskbuilder');?>" id="wppm_checklist_label" name="wppm_checklist_label">
              <input type="button" value="Add" id="wppm_checklist_btn" class="btn btn-success btn-sm" onclick="wppm_add_new_checklist(<?php echo esc_attr($id)?>,<?php echo esc_attr($proj_id) ?>)">
              <span onclick="wppm_remove_add_checklist()"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/cross1.svg'); ?>"></span>
            </div>
          </div>
          </br>
        </div>
      </div>
      <div id="wppm_activity_container">
        <div class="row">
          <div class="col-sm-12" style="margin-top:5px;">
            <span class="wppm_task_discussion"><?php echo esc_html_e('Comment','taskbuilder')?></span><br>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <textarea id="wppm_task_comment" placeholder="<?php echo esc_attr__('Write a comment...','taskbuilder');?>" onclick="wppm_add_task_comment()" name="wppm_task_comment"></textarea>
          </div>
        </div>
        <div class="row" id="wppm_comment_btn">
          <div class="col-sm-6">
            <span id="wppm_attach_file" onclick="wppm_upload_file()"><?php echo esc_html_e('Attach Files','taskbuilder')?></span>
          </div>
          <div class="col-sm-6">
            <button type="button" id="wppm_individual_cancel_comment_btn" onclick="wppm_cancel_comment();" class="wppm-btn-cancel">
              <?php echo esc_html_e('Cancel','taskbuilder')?> 
            </button>
            <button type="button" id="wppm_individual_submit_task_comment_btn" onclick="wppm_submit_task_comment(<?php echo esc_attr($id); ?>,<?php echo esc_attr($proj_id); ?>);" class="wppm-btn">
              <?php echo esc_html_e('SEND','taskbuilder')?> 
            </button>
          </div>
        </div>
        <div class="row">
          <div class="wppm_attachment_container col-sm-12" style="display:none;">
          </div>
        </div>
      </div>
      <div class="wppm_thread_container">
        <?php 
            if(!empty($task_comment)){
              foreach($task_comment as $comment){
                $user = get_userdata( $comment->created_by );
                $comment_body = stripslashes((htmlspecialchars_decode($comment->body, ENT_QUOTES)));
                $comment_body = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $comment_body);
                $date = date("Y-m-d H:i:s" ,strtotime($comment->create_time));
                $thread_date = date("d F Y, h:i:s A" ,strtotime($date));
                $attachment_ids = isset($comment->attachment_ids)? $comment->attachment_ids : '';
                if($attachment_ids!=""){
                  $attachments = explode(",",$attachment_ids);
                } else {
                  $attachments = array();
                }
                ?>
                <div class="wppm_thread">
                  <div class="wppm_thread_avatar">
                    <?php echo (get_avatar($comment->created_by, 40));?>
                  </div>
                  <div class="wppm_thread_body">
                    <div class="wppm_thread_user_name">
                      <strong><?php echo esc_html($user->display_name) ?></strong> <small class="wppm_comment_date"><i><?php echo esc_html($thread_date) ?></i></small>
                      <div class="wppm_thread_action">
                        <?php if(( ($current_user->has_cap('manage_options')) || ($wppmfunction->has_comment_permission('edit_task_comment',$id,$comment->id)))){ ?>
                          <span title="Edit this thread" onclick="wppm_edit_thread(<?php echo esc_attr($comment->id)?>,<?php echo esc_attr($id) ?>,<?php echo esc_attr($proj_id) ?>)"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/edit1.svg'); ?>" alt="edit"></span>
                          <span title="Delete this thread" onclick="wppm_delete_thread(<?php echo esc_attr($comment->id) ?>,<?php echo esc_attr($id) ?>,<?php echo esc_attr($proj_id) ?>)"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/trash.svg'); ?>" alt="delete"></span>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="wppm_thread_messege">
                      <?php 
                      $allowedtags = array( 'br' => array(), 'abbr' => array('title' => array(),), 'p' => array(), 'strong' => array(), 'a' => array('href' => array(), 'title' => array(), 'target'=> array(), 'rel'=>array()),'em' =>array(),'span' =>array(), 'blockquote'=>array('cite'  => array(),),'div' => array('class' => array(),'title' => array(),'style' => array(),),'ul'=>array(),'li'=>array(),'ol'=>array(),'img' => array( 'alt'=> array(),'class' => array(),'height' => array(),'src'=> array(),'width'=> array(),));
                      if(!empty($comment_body)){
                        echo wp_kses(wpautop($comment_body),$allowedtags) ;
                      } ?>
                    </div>
                    <?php 
                    if(!empty($attachments)):?> <br>
                      <strong class="wppm_attachment_title"><?php echo esc_html_e('Attachments','taskbuilder');?>:</strong><br>
                      <table class="wppm_attachment_tbl">
                        <tbody>
                        <?php
                        if(!empty($attachments)){
                          foreach( $attachments as $attachment ):
                            $attach = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wppm_attachments where id=".$attachment);
                            $download_url = site_url('/').'?wppm_attachment='.$attachment.'&tid='.$id.'&tac='.$auth_id;
                            $findStr = ".txt";
                            if(!empty( $attach)){
                              $attachment_name = preg_replace('/' . $findStr . '/', "", $attach->name, 1);
                              $attachment_name = sanitize_file_name($attachment_name);
                            }
                            if((!empty($attachment_name))){
                              ?>
                              <tr class="wppm_attachment_tr">
                                <td>
                                  <a class="wppm_attachment_link" href="<?php echo esc_url($download_url) ?>" target="_blank">
                                  <span class="wppm_attachment_file_name" style="padding: 7px;"><?php echo isset($attach->file_name) ? esc_html($attach->file_name):"";?></span></a>
                                  <?php if((($comment->created_by == $current_user->ID) || ($current_user->has_cap('manage_options')) || ($wppmfunction->has_comment_permission('edit_task_comment',$id,$comment->id)))){ ?>
                                    <span class="wppm_thread_action_btn" onclick="wppm_thread_attachment_remove(this,<?php echo esc_attr($attach->id); ?>,<?php echo esc_attr($comment->id); ?>,<?php echo esc_attr($id); ?>,<?php echo esc_attr($proj_id);?>)"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/cancel.svg'); ?>" alt="cancel"></span>
                                  <?php } ?>
                                </td>
                              </tr>
                            <?php }
                          endforeach;
                        } ?>
                        </tbody>
                      </table>
                    <?php do_action('wppm_after_comment_attachment',$id,$comment->id);?>
                    <?php endif;?>
                  </div>
                </div>
            <?php } 
            } ?>
      </div>
    </div>
    <div class="wppm_individual_task_widget col-sm-4 wppm_sidebar col-md-3">
      <div class="row wppm_widget" id="wppm_status_widget">
        <h4 class="widget_header"><?php echo esc_html_e('Status','taskbuilder')?>
        <?php if ($wppmfunction->has_permission('change_status',$id) || $current_user->has_cap('manage_options')) { ?>
                <span class="wppm_edit_task_details_widget" onclick="wppm_edit_task_status(<?php echo esc_attr($id) ?>,<?php echo esc_attr($proj_id) ?>)"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/edit_01.svg'); ?>" alt="edit"></span></h4>
        <?php } ?>
        <hr class="widget_divider">
        <div class="wppm_sidebar_labels"><span class="wppm_label_info"><?php echo esc_html_e('Status','taskbuilder')?>:</span> <span class="wppm_admin_label" style="background-color:<?php echo (isset($task_status->bg_color))? esc_attr($task_status->bg_color):""?>;color:<?php echo (isset($task_status->color))? esc_attr($task_status->color):""?>;"><?php echo (isset($task_status->name))? esc_attr($task_status->name):"" ?> </span></div>
      </div>
      <div class="row wppm_widget" id="wppm_raisedby_widget">
        <h4 class="widget_header">
        <?php echo esc_html_e('Task Creator','taskbuilder')?>
        <?php if ($wppmfunction->has_permission('change_raised_by',$id ) || $current_user->has_cap('manage_options')) { ?>
                <span class="wppm_edit_task_details_widget" onclick="wppm_edit_task_creator(<?php echo esc_attr($id) ?>,<?php echo esc_attr($proj_id) ?>)"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/edit_01.svg'); ?>" alt="edit"></span></h4>
        <?php } ?>
        <hr class="widget_divider"> <?php
        $user_info = get_userdata($task->created_by);
        ?>
        <div style="padding:2px 0;">
            <span class="wppm_task_user_avatar"><?php echo (get_avatar($task->created_by, 25, "mysteryman")); ?></span>
            <span class="wppm_task_user_names"><?php echo esc_html($user_info->display_name); ?></span>
        </div>
      </div>
      <div class="row wppm_widget" id="wppm_task_users_widget_container">
        <h4 class="widget_header">
        <?php if ($wppmfunction->has_permission('assign_task_users',$id ) || $current_user->has_cap('manage_options')) { ?>
                  <span class="wppm_edit_task_details_widget" onclick="wppm_edit_task_users(<?php echo esc_attr($id) ?>,<?php echo esc_attr($proj_id) ?>)"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/edit_01.svg'); ?>" alt="edit"></span>
        <?php } ?>
        <span><?php echo esc_html_e('Users','taskbuilder')?></span></h4>
        <hr class="widget_divider">
        <div id="wppm_task_users">
          <?php 
          $i=0;
          if(!empty($users)){
            foreach($users as $user) { 
              $user_data = get_userdata($user);
              if(!empty($project_data['users'])){
                $proj_users = explode(',',$project_data['users']);
                if(in_array($user,$proj_users)){
                  $i++;
                  ?>
                  <div style="padding:2px 0;">
                      <span class="wppm_task_user_avatar"><?php echo (get_avatar($user, 25, "mysteryman")); ?></span>
                      <span class="wppm_task_user_names"><?php echo ((!empty($user_data))? esc_html($user_data->display_name) :"") ?></span>
                  </div>
                  <?php 
                }
              }
            }
          }
          if($i==0){ ?>
            <span class="wppm_display_user_none"><?php echo esc_html_e('None','taskbuilder'); ?></span> <?php
          } 
          ?>
        </div>
      </div>
      <?php do_action('wppm_after_task_widgets',$id,$proj_id);?>
    </div>
    <input type="hidden" id="wppm_nonce" value="<?php echo wp_create_nonce()?>">
    <input type="file" id="wppm_attachment_upload" class="hidden" onchange="">
  </div>
</form>
<script>
jQuery( document ).ready( function( jQuery ) {
  <?php foreach ($wppm_checklist as $chklist) { ?>
          var $checkboxes = jQuery('.wppm_checklist_item_'+<?php echo esc_attr($chklist->id);?>);
          var $progress = jQuery('#CheckProgress_'+<?php echo esc_attr($chklist->id);?>);
          var total = $checkboxes.length;
          var checked = jQuery('.wppm_checklist_item_'+<?php echo esc_attr($chklist->id);?>).filter(':checked').length;
          var progressWidth = Math.ceil((checked / total) * 100);
          jQuery('#CheckProgress_'+<?php echo esc_attr($chklist->id);?>).css('background','#5ba4cf');
          jQuery('#CheckProgress_'+<?php echo esc_attr($chklist->id);?>).css('width', progressWidth + '%');
          if(progressWidth>0){
            jQuery('#wppm_progressbar_label_'+<?php echo esc_attr($chklist->id);?>).text(progressWidth+'%');
          }
    <?php $wppm_checklist_items = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wppm_checklist_items where checklist_id=".$chklist->id);
          foreach ($wppm_checklist_items as $chkitems) { 
          ?>  jQuery( '#wppm_checklist_'+<?php echo esc_attr($chkitems->id);?>).on("mouseover", function () {
                jQuery( '#wppm_checklist_action_'+<?php echo esc_attr($chkitems->id);?>).css("display","inline");
                jQuery( '#wppm_checklist_'+<?php echo esc_attr($chkitems->id);?>).css("background-color","#e7e8ea");
              })
              jQuery( '#wppm_checklist_'+<?php echo esc_attr($chkitems->id);?>).on("mouseout", function () {
                jQuery( '#wppm_checklist_action_'+<?php echo esc_attr($chkitems->id);?>).css("display","none");
                jQuery( '#wppm_checklist_'+<?php echo esc_attr($chkitems->id);?>).css("background-color","#ffffff");
              })
          <?php
          }
    } 
  ?>
});

function wppm_add_task_comment(){
  tinymce.remove();
  tinymce.init({ 
    selector:'#wppm_task_comment',
    body_id: 'wppm_task_comment',
    directionality : '<?php //echo 'rtl'; ?>',
    menubar: false,
    statusbar: false,
    height : '200',
    width  : '100%',
    plugins: [
        'lists link image directionality paste'
    ],
    image_advtab: true,
    toolbar: 'bold italic underline blockquote | alignleft aligncenter alignright | bullist numlist | rtl | link image',
    branding: false,
    autoresize_bottom_margin: 20,
    browser_spellcheck : true,
    relative_urls : false,
    remove_script_host : false,
    convert_urls : true,
    paste_as_text: true,
    setup: function (editor) {
    }
  });
  jQuery('#wppm_comment_btn').show();
}

function wppm_add_checklist_item(checklist_id){
  jQuery('#wppm_add_checklist_label_container_'+checklist_id).show();
  jQuery('#wppm_add_checklist_item_btn_'+checklist_id).hide();
  jQuery('.wppm_add_checklist_label_container_'+checklist_id).show();
  jQuery('.wppm_checklist_item_label_'+checklist_id).css('height', '30px');
  jQuery('.wppm_add_new_item_btn_'+checklist_id).css('margin', '10px');
}

function wppm_add_checklist(){
  jQuery('#wppm_add_checklist').hide();
  jQuery('#wppm_add_new_checklist').show();
}

function wppm_remove_add_checklist_container(checklist_id){
  jQuery('#wppm_add_checklist_label_container_'+checklist_id).hide();
  jQuery('#wppm_add_checklist_item_btn_'+checklist_id).show();
}

function wppm_remove_add_checklist(){
  jQuery('#wppm_add_new_checklist').hide();
  jQuery('#wppm_add_checklist').show();
}

function wppm_upload_file(){
    jQuery('#wppm_attachment_upload').unbind('change');
    jQuery('#wppm_attachment_upload').on('change', function() {
      var flag = false;
      var file = this.files[0];
      jQuery('#wppm_attachment_upload').val('');
      var allowedExtension = ['exe', 'php','js'];
	    var file_name_split = file.name.split('.');
	    var file_extension = file_name_split[file_name_split.length-1];
      file_extension = file_extension.toLowerCase();	
      if( (jQuery.inArray(file_extension,allowedExtension)  > -1)){
        flag = true;
        alert("<?php _e('Attached file type not allowed!','taskbuilder')?>");
      };
      <?php
      $max_upload = (int)(ini_get('upload_max_filesize')); ?>
      var current_filesize=file.size/1000000;
      if(current_filesize > <?php echo esc_attr($max_upload); ?>){
				flag = true;
				alert("<?php _e('File size exceed allowed limit!','taskbuilder')?>");
			}
      if (!flag){
        var html_str = '<div class="wppm_attachment">'+
                          '<div class="progress" style="float: none !important; width: unset !important;">'+
                              '<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">'+
                                file.name+
                              '</div>'+
                              '<span class="wppm_attachment_cancel" onclick="wppm_attachment_cancel(this);" style="display:none;"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/cross_icon.svg'); ?>" alt="delete_icon"></span>'+
                          '</div>'+
                        '</div>';
          jQuery('.wppm_attachment_container').show();
          jQuery('.wppm_attachment_container').append(html_str);
          var attachment = jQuery('.wppm_attachment_container').find('.wppm_attachment').last();
          var data = new FormData();
            data.append('file', file);
            data.append('arr_name', file.name);
            data.append('action', 'wppm_upload_file');
            data.append('nonce', jQuery('#wppm_nonce').val().trim());
            jQuery.ajax({
              type: 'post',
              url: wppm_admin.ajax_url,
              data: data,
              xhr: function(){
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt){
                  if (evt.lengthComputable) {
                    var percentComplete = Math.floor((evt.loaded / evt.total) * 100);
                    jQuery(attachment).find('.progress-bar').css('width',percentComplete+'%');
                  }
                }, false);
                return xhr;
              },
              processData: false,
              contentType: false,
              success: function(response) {
                var return_obj=JSON.parse(response);
                jQuery(attachment).find('.wppm_attachment_cancel').show();
                if( parseInt(return_obj.id) != 0 ){
                  jQuery(attachment).append('<input type="hidden" class="wppm_comment_attachment" name="wppm_comment_attachment[]" value="'+return_obj.id+'">');
                  jQuery(attachment).find('.progress-bar').addClass('progress-bar-success');
                }else {
                  jQuery(attachment).find('.progress-bar').addClass('progress-bar-danger');
                }
              }
            });    
      }
    });
    jQuery('#wppm_attachment_upload').trigger('click');
}
</script>