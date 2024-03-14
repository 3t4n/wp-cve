<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $current_user,$wpdb,$wppmfunction;
$task_per_page = 20;
$page_no = isset($_POST['page_no']) ? intval(sanitize_text_field($_POST['page_no'])): '0';
$search_tag = isset($_POST['task_search']) ? sanitize_text_field($_POST['task_search']) : '';
$order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) :"ASC";
$sort_by = isset($_POST['sort_by']) ? sanitize_text_field($_POST['sort_by']):"task_name";
$filter_by = isset($_POST['wppm_task_filter']) ? sanitize_text_field($_POST['wppm_task_filter']) : "all";
$current_date = date('Y-m-d');
$wppm_task_time = get_option('wppm_task_time');
$filters = array(
  'sort_by' => $sort_by,
  'order' => $order,
  'search' => $search_tag,
  'filter'=>$filter_by
);
$tl_filters = isset( $_COOKIE['wppm_filters'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['wppm_filters'] ) ) : $filters;
if(!is_array($tl_filters)){
    $tl_filters  = json_decode($tl_filters);
    $tl_filters_arr = (array) $tl_filters;
} else{
  $tl_filters_arr = $filters;
}
$wppm_tl_filter = "1=1";
$order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) :$tl_filters_arr['order'];
$sort_by = isset($_POST['sort_by']) ? sanitize_text_field($_POST['sort_by']):$tl_filters_arr['sort_by'];
$search_tag = isset($_POST['task_search']) ? sanitize_text_field($_POST['task_search']) : $tl_filters_arr['search'];
$filter_by = isset($_POST['wppm_task_filter']) ? sanitize_text_field($_POST['wppm_task_filter']) : $tl_filters_arr['filter'];
if($sort_by == ""){
  $sort_by = 'task_name';
}
$filters = array(
  'sort_by' => $sort_by,
  'order' => $order,
  'search'=>$search_tag,
  'filter'=>$filter_by
);
setcookie('wppm_filters',wp_json_encode( $filters ),time() + 3600);

if($tl_filters_arr['sort_by']=='project'){
  $sort_by = 'proj.project_name';
}
if($tl_filters_arr['sort_by']=='start_date'){
  $sort_by = 'Task.start_date';
}
if($tl_filters_arr['sort_by']=='end_date'){
  $sort_by = 'Task.end_date';
}
if($tl_filters_arr['sort_by']=='status'){
  $sort_by = 'task_statuses.name';
}
if($tl_filters_arr['sort_by']=='priority'){
  $sort_by='task_priorities.name';
}
if($filter_by=='completed'){
  $wppm_tl_filter = "Task.status='4'";
}elseif($filter_by=='overdue'){
  $wppm_tl_filter = "Task.status!='4' AND (CAST(Task.end_date AS DATE)) < '$current_date'";
}elseif($filter_by == 'unassigned'){
  $wppm_tl_filter = "Task.users=''";
}elseif($filter_by == 'todo'){
  $wppm_tl_filter = "Task.status='1'";
}elseif($filter_by == 'inprogress'){
  $wppm_tl_filter = "Task.status='2'";
}elseif($filter_by == 'hold'){
  $wppm_tl_filter = "Task.status='3'";
}elseif($filter_by == 'mine'){
  $wppm_tl_filter = "(FIND_IN_SET('$current_user->ID',Task.users)>0)";
}
$wppm_default_task_date = get_option('wppm_default_task_date');
$search_tag_text = '%'.$search_tag.'%';
if(!empty($search_tag)){
  $search_tag_text = '%'.$search_tag.'%';
  $query = ("SELECT Task.*
              FROM {$wpdb->prefix}wppm_task AS Task
              Left join {$wpdb->prefix}wppm_project proj ON Task.project = proj.id
              Left join {$wpdb->prefix}wppm_task_statuses task_statuses ON Task.status = task_statuses.id
              Left join {$wpdb->prefix}wppm_task_priorities task_priorities ON Task.priority = task_priorities.id
              Left join {$wpdb->prefix}wppm_project_users proj_users ON Task.project = proj_users.proj_id
              Left join {$wpdb->base_prefix}users user ON (FIND_IN_SET(user.id,Task.users)>0) AND user.display_name LIKE '$search_tag_text'
              Left join {$wpdb->prefix}wppm_project_meta proj_meta ON Task.project = proj_meta.project_id");          
} 
else{ 
  if($current_user->has_cap('manage_options')){
    if($sort_by=='task_name'|| $sort_by=='start_date' || $sort_by=='end_date'){
      $query = ( "SELECT Task.* FROM {$wpdb->prefix}wppm_task AS Task
      Left join {$wpdb->prefix}wppm_project_meta proj_meta ON Task.project = proj_meta.project_id
      ");
    } else{
      $query = ( "SELECT Task.* FROM {$wpdb->prefix}wppm_task AS Task
      Left join {$wpdb->prefix}wppm_project proj ON Task.project = proj.id
      Left join {$wpdb->prefix}wppm_task_statuses task_statuses ON Task.status = task_statuses.id
      Left join {$wpdb->prefix}wppm_task_priorities task_priorities ON Task.priority = task_priorities.id
      Left join {$wpdb->prefix}wppm_project_meta proj_meta ON Task.project = proj_meta.project_id
    ");
    }
  }else{
    $query = ( "SELECT Task.*
      FROM {$wpdb->prefix}wppm_task AS Task
      Left join {$wpdb->prefix}wppm_project proj ON Task.project = proj.id
      Left join {$wpdb->prefix}wppm_project_users proj_users ON Task.project = proj_users.proj_id
      Left join {$wpdb->prefix}wppm_task_statuses task_statuses ON Task.status = task_statuses.id
      Left join {$wpdb->prefix}wppm_task_priorities task_priorities ON Task.priority = task_priorities.id
      Left join {$wpdb->prefix}wppm_project_meta proj_meta ON Task.project = proj_meta.project_id
      ");
  }
}
$no_of_rows = ( "SELECT count(*) FROM ($query");
if(!empty($search_tag )){
  if($current_user->has_cap('manage_options')){
    $where = " where $wppm_tl_filter AND (Task.task_name LIKE '$search_tag_text' OR proj.project_name LIKE '$search_tag_text' OR task_statuses.name LIKE '$search_tag_text' OR task_priorities.name LIKE '$search_tag_text' OR user.display_name LIKE '$search_tag_text')";
  } else{
    $where = " where $wppm_tl_filter AND (((FIND_IN_SET('$current_user->ID',Task.users)>0) OR (Task.created_by= '$current_user->ID') OR (proj_users.user_id = '$current_user->ID' AND proj_users.role_id = 1 AND (FIND_IN_SET('$current_user->ID',proj.users)>0)) OR (Task.project = proj_meta.project_id AND proj_meta.meta_key='public_project' AND proj_meta.meta_value=1)) AND (Task.task_name LIKE '$search_tag_text' OR proj.project_name LIKE '$search_tag_text' OR task_statuses.name LIKE '$search_tag_text' OR task_priorities.name LIKE '$search_tag_text' OR user.display_name LIKE '$search_tag_text'))";
  }
}else{
  if($current_user->has_cap('manage_options')){
    $where = " where $wppm_tl_filter";
  } else{
    $where = " where $wppm_tl_filter AND ((FIND_IN_SET('$current_user->ID',Task.users)>0) OR (Task.created_by= '$current_user->ID') OR (proj_users.user_id = '$current_user->ID' AND proj_users.role_id = 1 AND (FIND_IN_SET('$current_user->ID',proj.users)>0)) OR (Task.project = proj_meta.project_id AND proj_meta.meta_key='public_project' AND proj_meta.meta_value=1))";
  }
}
$query = apply_filters('wppm_task_list_list_view_query',$query,$search_tag_text);
$no_of_rows= apply_filters('wppm_task_list_number_of_rows_query',$no_of_rows,$search_tag_text);
$where = apply_filters('wppm_task_list_query_where',$where, $wppm_tl_filter,$search_tag_text);
$no_of_rows_where = $where." Group by Task.id) AS Task";
$no_of_rows .= $no_of_rows_where;
$no_of_rows = apply_filters('wppm_task_list_no_of_rows',$no_of_rows,$wppm_tl_filter,$search_tag_text);
$totalrows = $wpdb->get_var($no_of_rows);
$query .= $where." Group by Task.id ORDER BY $sort_by $order";
$query = apply_filters('wppm_task_list_query',$query);
$limit_start=$page_no*$task_per_page;
$limit="\n LIMIT ".$limit_start.",".$task_per_page." ";
$task_id_array = array();
$query = $query.$limit;
$wppm_task_fillter = $wpdb->get_results($query);
?>
<form id="wppm_task_list_frm">
  <div id="wppm_task_list_container">
    <div class="row">
      <div class="col-sm-6">
        <span class="wppm-heading-inline"> <?php echo esc_html_e('Tasks','taskbuilder');?> </span>
        <span class="wppm-add-new-btn btn-primary" onclick="wppm_add_new_task()"><img class="wppm_add_new_task_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/plus_icon.svg'); ?>" alt="add_icon"><?php echo esc_html_e('Add New','taskbuilder');?></span>
      </div>
      <div id="wppm_task_search" class="col-sm-6">
        <input type="search" id="wppm_task_search_filter" name="wppm_task_search_filter" class="form-control form-control-sm" aria-controls="selection-datatable" placeholder="<?php echo esc_attr__('Search','taskbuilder');?>" value="<?php echo (!empty($search_tag)) ? esc_attr($search_tag) : "" ?>">
        <img width="25" height="15" class="wppm_task_search_filter_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/search.svg'); ?>" alt="search">
      </div>
    </div>
    <div class="row">
        <div class="col-sm-6" id="wppm_task_filter_container">
          <div class="wppm-filter-item">
            <label for="wppm_task_filter"> <?php echo esc_html_e('Filter','taskbuilder');?></label>
            <select id="wppm_task_filter" name="wppm_task_filter" onchange="wppm_apply_task_filter()">
              <option value="all"<?php echo ($filter_by == "all")? 'selected':""?>><?php echo esc_html_e('All','taskbuilder');?></option>
              <option value="todo" <?php echo ($filter_by == "todo")? 'selected':""?>><?php echo  esc_html_e('Todo','taskbuilder');?></option>
              <option value="inprogress" <?php echo ($filter_by == "inprogress")? 'selected':""?>><?php echo  esc_html_e('In Progress','taskbuilder');?></option>
              <option value="hold" <?php echo ($filter_by == "hold")? 'selected':""?>><?php echo  esc_html_e('Hold','taskbuilder');?></option>
              <option value="completed" <?php echo ($filter_by == "completed")? 'selected':""?>><?php echo  esc_html_e('Completed','taskbuilder');?></option>
              <option value="mine" <?php echo ($filter_by == "mine")? 'selected':""?>><?php echo  esc_html_e('Mine','taskbuilder');?></option>
              <option value="unassigned" <?php echo ($filter_by == "unassigned")? 'selected':""?>><?php echo  esc_html_e('Unassigned','taskbuilder');?></option>
              <option value="overdue" <?php echo ($filter_by == "overdue")? 'selected':""?>><?php echo  esc_html_e('Overdue','taskbuilder');?></option>
            </select>
          </div>
          <div class="wppm_display_submit">
            <div class="wppm-filter-actions">
                <span class="wppm-link" onclick="wppm_tl_reset_filter()"> <?php echo esc_html_e('Reset','taskbuilder');?></span>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <span id="wppm_grid_view_btn" onclick="wppm_view_task_search_filter(<?php echo esc_attr($page_no)?>)"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/grid_view.svg'); ?>" alt="grid_view"><span><?php echo esc_html_e('Grid view','taskbuilder');?></span></span>
        </div>
    </div>
    <div id="wppm_task_table_container" class="wppm_task_table_container table-responsive">
      <table id="wppm_task_table" class="wppm_table table">
        <thead>
          <tr>
            <th class="wppm_table_header">
              <div id="wppm_task_name_container">
                <span id="wppm_task_name_th"><?php echo esc_html_e('Task name','taskbuilder'); ?></span>
                <img width="16" height="16" style = "<?php echo ($sort_by == 'task_name' && $order == 'DESC') ? 'display:inline': 'display:none;'?>" onclick="wppm_sort_up_task_list('task_name','ASC')" class="wppm_chevron_img" id="wppm_chevron_sort_up_task_name_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_up_icon.svg'); ?>" alt="chevron_sort_up_icon">
                <img width="16" height="16" style = "<?php echo ($sort_by == 'task_name' && $order == 'ASC') ? 'display:inline': 'display:none;'?>" onclick="wppm_sort_up_task_list('task_name','DESC')" class="wppm_chevron_img" id="wppm_chevron_sort_down_task_name_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_down_icon.svg'); ?>" alt="chevron_sort_down_icon">
              </div>
            </th>
            <th class="wppm_table_header">
              <span id="wppm_project_name_th"><?php echo esc_html_e('Project','taskbuilder'); ?></span>
              <img width="16" height="16" style = "<?php echo ($sort_by == 'proj.project_name' && $order == 'DESC') ? 'display:inline': 'display:none;'?>" onclick="wppm_sort_up_task_list('project','ASC')" class="wppm_chevron_img" id="wppm_chevron_sort_up_project_list_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_up_icon.svg'); ?>" alt="chevron_sort_up_icon">
              <img width="16" height="16" style = "<?php echo ($sort_by == 'proj.project_name' && $order == 'ASC') ? 'display:inline': 'display:none;'?>" onclick="wppm_sort_up_task_list('project','DESC')" class="wppm_chevron_img" id="wppm_chevron_sort_down_project_list_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_down_icon.svg'); ?>" alt="chevron_sort_down_icon">
            </th>
            <th class="wppm_table_header">
              <span id="wppm_status_th"><?php echo esc_html_e('Status','taskbuilder'); ?></span>
              <img width="16" height="16" style = "<?php echo ($sort_by == 'task_statuses.name' && $order == 'DESC') ? 'display:inline': 'display:none;'?>" onclick="wppm_sort_up_task_list('status','ASC')" class="wppm_chevron_img" id="wppm_chevron_sort_up_status_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_up_icon.svg'); ?>" alt="chevron_sort_up_icon">
              <img width="16" height="16" style = "<?php echo ($sort_by == 'task_statuses.name' && $order == 'ASC') ? 'display:inline': 'display:none;'?>" onclick="wppm_sort_up_task_list('status','DESC')" class="wppm_chevron_img" id="wppm_chevron_sort_down_status_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_down_icon.svg'); ?>" alt="chevron_sort_down_icon">
            </th>
            <th class="wppm_table_header">
              <span id="wppm_priority_th"><?php echo esc_html_e('Priority','taskbuilder'); ?></span>
              <img width="16" height="16" style = "<?php echo ($sort_by == ('task_priorities.name') && $order == 'DESC') ? 'display:inline': 'display:none;'?>" onclick="wppm_sort_up_task_list('priority','ASC')" class="wppm_chevron_img" id="wppm_chevron_sort_up_priority_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_up_icon.svg'); ?>" alt="chevron_sort_up_icon">
              <img width="16" height="16" style = "<?php echo ($sort_by == ('task_priorities.name') && $order == 'ASC') ? 'display:inline': 'display:none;'?>" onclick="wppm_sort_up_task_list('priority','DESC')" class="wppm_chevron_img" id="wppm_chevron_sort_down_priority_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_down_icon.svg'); ?>" alt="chevron_sort_down_icon">
            </th>
            <?php if($wppm_default_task_date == 1) { ?>
                    <th class="wppm_table_header">
                      <span id="wppm_start_date_th"><?php echo esc_html_e('Start date','taskbuilder'); ?></span>
                      <img width="16" height="16" style = "<?php echo ($sort_by == 'Task.start_date' && $order == 'DESC') ? 'display:inline': 'display:none;'?>" onclick="wppm_sort_up_task_list('start_date','ASC')" class="wppm_chevron_img" id="wppm_chevron_sort_up_start_date_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_up_icon.svg'); ?>" alt="chevron_sort_up_icon">
                      <img width="16" height="16" style = "<?php echo ($sort_by == 'Task.start_date' && $order == 'ASC') ? 'display:inline': 'display:none;'?>" onclick="wppm_sort_up_task_list('start_date','DESC')" class="wppm_chevron_img" id="wppm_chevron_sort_down_start_date_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_down_icon.svg'); ?>" alt="chevron_sort_down_icon">
                    </th>
                    <th class="wppm_table_header">
                      <span id="wppm_end_date_th"><?php echo esc_html_e('End date','taskbuilder'); ?></span>
                      <img width="16" height="16" style = "<?php echo ($sort_by == 'Task.end_date' && $order == 'DESC') ? 'display:inline': 'display:none;'?>" onclick="wppm_sort_up_task_list('end_date','ASC')" class="wppm_chevron_img" id="wppm_chevron_sort_up_end_date_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_up_icon.svg'); ?>" alt="chevron_sort_up_icon">
                      <img width="16" height="16" style = "<?php echo ($sort_by == 'Task.end_date' && $order == 'ASC') ? 'display:inline': 'display:none;'?>" onclick="wppm_sort_up_task_list('end_date','DESC')" onclick="wppm_sort_up_task_list('end_date','DESC')" class="wppm_chevron_img" id="wppm_chevron_sort_down_end_date_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_down_icon.svg'); ?>" alt="chevron_sort_down_icon">
                    </th>
            <?php } ?> 
            <th class="wppm_table_header">
              <?php echo esc_html_e('Assign To','taskbuilder'); ?>
            </th>
            <th class="wppm_table_header">
              <?php echo esc_html_e('Progress','taskbuilder'); ?>
            </th>
            <?php do_action('wppm_table_header');?>
          </tr>
        </thead>
        <tbody>
          <?php 
          $task_index = 0;
          if(!empty($wppm_task_fillter)){
            foreach($wppm_task_fillter as $task) {
                $checklists = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wppm_checklist where task_id = $task->id" );
                if(!empty($task)){
                  $task_index++;
                  $total=$wpdb->get_var("SELECT COUNT(Items.id) AS check_id
                    FROM {$wpdb->prefix}wppm_checklist_items AS Items
                    Left join {$wpdb->prefix}wppm_checklist checklist ON Items.checklist_id = checklist.id
                    WHERE checklist.task_id = $task->id");
                  $total_checked_items=$wpdb->get_var("SELECT COUNT(Items.id) AS check_id
                    FROM {$wpdb->prefix}wppm_checklist_items AS Items
                    Left join {$wpdb->prefix}wppm_checklist checklist ON Items.checklist_id = checklist.id
                    WHERE checklist.task_id = $task->id AND Items.checked=1");
                  
                  if(isset($task->status)){
                    $status = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wppm_task_statuses where id = $task->status" );
                  }
                  if(!empty($task->priority)){
                    $priority = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wppm_task_priorities where id = $task->priority" );
                  }
                  if(isset($task->project)){
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
                  <tr onclick= "if(link)wppm_open_task(<?php echo esc_attr($task->id) ?>)" >
                    <td>
                      <?php echo esc_html_e($task->task_name,'taskbuilder') ?>
                    </td>
                    <td>
                      <?php if(!empty($project->project_name)){
                      echo esc_html_e($project->project_name,'taskbuilder');
                      }
                      ?>
                    </td>
                    <td>
                    <?php if(!empty($task->status)){ ?>
                              <span class="wppm_td_task_status" style="background-color:<?php echo (isset($status->bg_color))? esc_attr($status->bg_color):"";?>; color: <?php echo (isset($status->color))? esc_attr($status->color):""; ?>"><?php echo (isset($status->name))? esc_html_e($status->name,'taskbuilder'):"" ?></span>
                    <?php } ?>
                    </td>
                    <td>
                      <?php if(!empty($task->priority)){ ?>
                              <span class ="wppm_td_task_priority" style="background-color:<?php echo (isset($priority->bg_color))? esc_attr($priority->bg_color):"" ;?>; color: <?php echo (isset($priority->color))? esc_attr($priority->color):""; ?>"><?php echo (isset($priority->name))? esc_html_e($priority->name,'taskbuilder'):"" ?></span>
                      <?php } ?>
                    </td>
                    <?php if($wppm_default_task_date == 1) { ?>
                            <td>
                            <?php if(!empty($task_start_date)){ ?>
                                      <?php echo esc_html_e($task_start_date,'taskbuilder');
                                  } ?>
                            </td>
                            <td>
                            <?php if(!empty($task_end_date)){ 
                                  $style = ($task->status!=4 && $task_end_date < $current_date) ? "color:#FF0000":"color:#2C3E50";
                                  ?>
                                  <span style="<?php echo esc_attr($style); ?>"><?php echo esc_html_e($task_end_date,'taskbuilder');?></span><?php
                                  } ?>
                            </td>
                    <?php } ?>
                    </td>
                    <td style="text-align:center;">
                      <?php 
                        $project_data = $wppmfunction->get_project($task->project);
                        $i=0;
                        if(!empty($project_data['users'])){
                          $proj_users = explode(',',$project_data['users']);
                        }
                        if(!empty($task->users)){
                          $users = explode(",",$task->users);
                          foreach($users as $user){ 
                            if( (!empty($proj_users)) && (in_array($user,$proj_users))){
                              $i++;
                              if( $i <= 4 ){
                                $userdata = get_userdata( $user ); 
                                if(!empty($userdata)){
                                  ?>
                                  <a href="#" title="<?php echo esc_attr($userdata->display_name); ?>">
                                    <?php echo get_avatar($userdata->ID, 25, "mysteryman")?>
                                  </a> <?php
                                }
                              }
                            }
                          }
                        } 
                        if($i > 4){
                          ?>
                          <a href="#" class="wppm_avatar">
                            <span id="wppm_avatar" style="background-color:black;" class="avatar">+<?php echo esc_html($i-4) ?></span>
                          </a>
                        <?php } ?>
                        <?php
                        if($i==0){
                          echo esc_html_e('None','taskbuilder');
                        } ?>
                    </td>
                    <td>
                      <?php
                      $completion = 0;
                      if ($total != 0 && $total != NULL) {
                        $completion = intval($total_checked_items/$total*100);
                      }
                      echo esc_html($completion).'%';
                      ?>
                    </td>
                    <?php do_action('wppm_table_column');?>
                  </tr>
        <?php   }
            } 
          }
          ?>
        </tbody>
      </table>
      <?php
      $current_page=$page_no+1;
      $total_pages=ceil($totalrows/$task_per_page);
      $prev_page_no=$current_page-1;
      $prev_class=($prev_page_no==0)?'disabled':'';
      $next_page_no=($total_pages==$current_page)? $current_page-1:$current_page;
      $next_class=($total_pages==$current_page)?'disabled':'';
      ?>
      <div class="row" style="background-color:#ffffff;">
        <div class="col-sm-12">
          <div class="wppm_task_result" style="<?php echo esc_attr($total_pages==0)? '':'display: none;';?>"><?php _e('Your search request returned no results.','taskbuilder');?></div>
          <hr style="<?php echo esc_attr($total_pages==0)? '':'display: none;';?>">
        </div>
      <div>
    </div>
  </div>
  <div class="row wppm_task_pagination_container">
    <div class="col-sm-4">
      <?php echo esc_html_e('Total:','taskbuilder'); ?>&nbsp;<?php echo esc_attr($task_index) ?>&nbsp;<?php echo esc_html_e('of','taskbuilder') ?>&nbsp;<?php echo esc_html($totalrows) ?>&nbsp;<?php echo esc_html_e('Tasks','taskbuilder')?>
    </div>
    <div class="wppm_task_pagination col-sm-4" style="<?php echo esc_attr($total_pages==0)? "display:none;":"display:flex;"?>">
      <span class="wppm-pagination-txt">
          <span><?php echo esc_html($current_page)?>&nbsp;<?php echo esc_html_e('of','taskbuilder');?>&nbsp;<?php echo esc_html($total_pages); ?>&nbsp;<?php echo esc_html_e('Page','taskbuilder'); ?></span>
      </span>
      <span <?php echo esc_attr($prev_class) ?> 
        style="<?php echo (isset($prev_class) && esc_attr($prev_class)== 'disabled') ? 'display:none;':'display:block;'?>cursor:pointer;">
        <a class="wppm_pagination_prev" onclick="return wppm_load_prev_task_page(<?php echo esc_attr($prev_page_no) ?>,<?php echo esc_attr($page_no) ?>,'<?php echo esc_attr($sort_by)?>','<?php echo esc_attr($order) ?>');"><?php echo esc_html_e('PREV','taskbuilder');?></a>
      </span>
      <span <?php echo esc_attr($next_class) ?>
        style="<?php echo (isset($next_class) && esc_attr($next_class) == 'disabled')?'display:none;':'display:block;'?>cursor:pointer;">
        <a class="wppm_pagination_next" onclick="return wppm_load_next_task_page(<?php echo esc_attr($next_page_no) ?>,<?php echo esc_attr($page_no) ?>,'<?php echo esc_attr($sort_by)?>','<?php echo esc_attr($order) ?>');"><?php echo esc_html_e('NEXT','taskbuilder');?></a>
      </span>
    </div>
  </div>
</form>
<script type="text/javascript">
  var link = true;
  jQuery( document ).ready( function( jQuery ) {
    jQuery("input[name='wppm_task_search_filter']").keypress(function(e) {
          //Enter key
      if (e.which == 13) {
        wppm_task_search_filter();
      }
    });
    jQuery("#wppm_task_name_th").hover(function(){
      jQuery('#wppm_chevron_sort_up_task_name_img').show();
      jQuery('#wppm_chevron_sort_down_task_name_img').hide();
    });
    jQuery("#wppm_project_name_th").hover(function(){
      jQuery('#wppm_chevron_sort_up_project_list_img').show();
      jQuery('#wppm_chevron_sort_down_project_list_img').hide();
    });
    jQuery("#wppm_status_th").hover(function(){
      jQuery('#wppm_chevron_sort_up_status_img').show();
      jQuery('#wppm_chevron_sort_down_status_img').hide();
    });
    jQuery("#wppm_priority_th").hover(function(){
      jQuery('#wppm_chevron_sort_up_priority_img').show();
      jQuery('#wppm_chevron_sort_down_priority_img').hide();
    });
    jQuery("#wppm_start_date_th").hover(function(){
      jQuery('#wppm_chevron_sort_up_start_date_img').show();
      jQuery('#wppm_chevron_sort_down_start_date_img').hide();
    });
    jQuery("#wppm_end_date_th").hover(function(){
      jQuery('#wppm_chevron_sort_up_end_date_img').show();
      jQuery('#wppm_chevron_sort_down_end_date_img').hide();
    });
    
  })
</script>

