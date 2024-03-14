<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $current_user,$wpdb,$wppmfunction;
$project_id = isset($_POST['id']) ? intval(sanitize_text_field($_POST['id'])) : 0 ;
$wppm_task_time = get_option('wppm_task_time');
if($current_user->has_cap('manage_options')){
  $query = ( "SELECT * FROM {$wpdb->prefix}wppm_task where project=$project_id");
  $tasks = $wpdb->get_results($query);
} else{
$query = ( "SELECT Task.*
      FROM {$wpdb->prefix}wppm_task AS Task
      Left join {$wpdb->prefix}wppm_project proj ON Task.project = proj.id
      Left join {$wpdb->prefix}wppm_project_users proj_users ON Task.project = proj_users.proj_id
      Left join {$wpdb->prefix}wppm_project_meta proj_meta ON Task.project = proj_meta.project_id
      where Task.project = $project_id AND ((FIND_IN_SET('$current_user->ID',Task.users)>0) OR (Task.   created_by= '$current_user->ID') OR (proj_users.user_id = '$current_user->ID' AND proj_users.role_id = 1 AND (FIND_IN_SET('$current_user->ID',proj.users)>0)) OR (Task.project = proj_meta.project_id AND proj_meta.meta_key='public_project' AND proj_meta.meta_value=1))Group by Task.id");
$tasks = $wpdb->get_results($query);
}
ob_start();
?>
<form>
  <div class="table-responsive">
		<table id="tbl_project_tasks" class="table table-striped table-bordered" cellspacing="0" width="100%">
				<thead>
  				<tr>
  					<th class="wppm_table_header"><?php echo esc_html_e('Task id','taskbuilder');?> </th>
            <th class="wppm_table_header"><?php echo esc_html_e('Task Name','taskbuilder');?> </th>
						<th class="wppm_table_header"><?php echo esc_html_e('Task Status','taskbuilder');?> </th>
            <th class="wppm_table_header"><?php echo esc_html_e('Task Priority','taskbuilder');?> </th>
            <th class="wppm_table_header"><?php echo esc_html_e('Assign To','taskbuilder');?> </th>
            <th class="wppm_table_header"><?php echo esc_html_e('Start Date','taskbuilder');?> </th>
  				  <th class="wppm_table_header"><?php echo esc_html_e('End Date','taskbuilder');?> </th>
          </tr>
        </thead>
        <tbody>
          <?php
          if(!empty($tasks)){
						foreach ($tasks as $task) {
              $status = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wppm_task_statuses where id = $task->status" );
              $users = array();
              if($task->users != null){
                $users = explode(",",$task->users);
              }
              if(!empty($task->priority)){
                $priority = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wppm_task_priorities where id = $task->priority" );
              }
              if(!empty($task->project)){
                $project = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wppm_project where id = $task->project" );
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
              <tr onclick= "wppm_open_project_tasks(<?php echo esc_attr($task->id) ?>,<?php echo esc_attr($task->project) ?>)" >
                <td>
                  <?php echo esc_html_e($task->id,'taskbuilder') ?>
                </td>
                <td>
                  <?php echo esc_html_e($task->task_name,'taskbuilder') ?>
                </td>
                <td>
                  <span class="wppm_td_task_status" style="background-color:<?php echo (isset($status->bg_color))? esc_attr($status->bg_color):"";?>; color: <?php echo (isset($status->color))? esc_attr($status->color):""; ?>"><?php echo (isset($status->name))? esc_html_e($status->name,'taskbuilder'):"" ?></span>
                </td>
                <td>
                  <span class ="wppm_td_task_priority" style="background-color:<?php echo (isset($priority->bg_color))? esc_attr($priority->bg_color):"";?>; color: <?php echo (isset($priority->color))? esc_attr($priority->color):""; ?>"><?php echo (isset($priority->name))? esc_html_e($priority->name,'taskbuilder'):"" ?></span>
                </td>
                <td>
                  <?php 
                    $i=0;
                    if(!empty($users)){
                      foreach($users as $user){ 
                        $i++;
                        if( $i <= 4 ){
                          $userdata = get_userdata( $user ); ?>
                          <a href="#" title="<?php echo esc_attr($userdata->display_name) ?>">
                            <?php echo (get_avatar($userdata->ID, 25, "mysteryman"))?>
                          </a> <?php
                        }
                      }
                    } else {
                      _e('None','taskbuilder');
                    }
                    if($i > 4){
                      ?>
                      <a href="#" class="wppm_avatar">
                        <span id="wppm_avatar" style="background-color:black;" class="avatar">+<?php echo esc_html($i-4) ?></span>
                      </a>
                  <?php } ?>
                </td>
                <td>
                  <?php echo (isset($task_start_date))? esc_html_e($task_start_date,'taskbuilder'):""?>
                </td>
                <td>
                  <?php echo (isset($task_end_date))? esc_html_e($task_end_date,'taskbuilder'):""?>
                </td>
              </tr>
            <?php } 
          }
          ?>
        </tbody>
		</table>
	</div>
</form>
<script type="text/javascript">
  jQuery(document).ready(function() {
	var table = jQuery('#tbl_project_tasks').DataTable({
		"aLengthMenu": [[4, 8, 12, -1], [4, 8, 12, "All"]],
		"columnDefs": [
		{ orderable: false, targets: -1 }
		]
	});
	jQuery('div.dataTables_filter input', table.table().container()).focus();	
} );
</script>
<?php
$body = ob_get_clean();
ob_start();
?>
<div class="col-md-3" style="text-align: right;">
		<button type="button" class="btn wppm_popup_close" onclick="wppm_modal_close();"><?php echo esc_html_e('Close','taskbuilder');?></button>
</div><?php

$footer = ob_get_clean();

$response = array(
  'body'      => $body,
	'footer'    => $footer
);

echo json_encode($response);