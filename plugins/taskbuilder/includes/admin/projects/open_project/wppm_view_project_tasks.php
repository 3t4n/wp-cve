<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wpdb,$current_user, $wppmfunction;
$task_per_page = 20;
$total_no_of_rows = 0;
$totalrows_tasks = 0;
$page_no = isset($_POST['page_no']) ? intval(sanitize_text_field($_POST['page_no'])) : '0';
$task_status = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wppm_task_statuses ORDER BY load_order ASC" );
$wppm_task_time = get_option('wppm_task_time');
$search_tag = isset($_POST['task_search']) ? sanitize_text_field($_POST['task_search']) : '';
$filter_by = isset($_POST['wppm_task_filter']) ? sanitize_text_field($_POST['wppm_task_filter']) : "all";
$filters = array(
	'search' => $search_tag,
	'filter'=>$filter_by
);
$grid_view_filter = $filters ;
$tl_filters = isset( $_COOKIE['wppm_grid_view_filters'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['wppm_grid_view_filters'] ) ) : $grid_view_filter;
$current_date = date('Y-m-d');
$wppm_tl_filter = "1=1";
if(!is_array($tl_filters)){
	$tl_filters  = json_decode($tl_filters);
	$tl_filters_arr = (array) $tl_filters;
} else{
$tl_filters_arr = $filters;
}
$search_tag = isset($_POST['task_search']) ? sanitize_text_field($_POST['task_search']) : $tl_filters_arr['search'];
$filter_by = isset($_POST['wppm_task_filter']) ? sanitize_text_field($_POST['wppm_task_filter']) : $tl_filters_arr['filter'];
$filters = array(
	'search'=>$search_tag,
	'filter'=>$filter_by
);
setcookie('wppm_grid_view_filters',wp_json_encode( $filters ),time() + 3600);

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
if(!empty($task_status)){
	foreach($task_status as $status) {
		if(!empty($search_tag)){
			$search_tag_text = '%'.$search_tag.'%';
			if($current_user->has_cap('manage_options')){
				$query = ("SELECT Task.*
							FROM {$wpdb->prefix}wppm_task AS Task
							Left join {$wpdb->prefix}wppm_project proj ON Task.project = proj.id
							Left join {$wpdb->prefix}wppm_task_statuses task_statuses ON Task.status = task_statuses.id
							Left join {$wpdb->prefix}wppm_task_priorities task_priorities ON Task.priority = task_priorities.id
							Left join {$wpdb->base_prefix}users user ON (FIND_IN_SET(user.id,Task.users)>0) AND user.display_name LIKE '$search_tag_text'
							Left join {$wpdb->prefix}wppm_project_meta proj_meta ON Task.project = proj_meta.project_id
						");
				$no_of_rows = ( "SELECT count(*) FROM ($query");
				$where = " where $wppm_tl_filter AND (Task.task_name LIKE '$search_tag_text' OR proj.project_name LIKE '$search_tag_text' OR task_statuses.name LIKE '$search_tag_text' OR task_priorities.name LIKE '$search_tag_text' OR user.display_name LIKE '$search_tag_text') AND Task.status= $status->id";
			} else{
				$query = ("SELECT Task.*
					FROM {$wpdb->prefix}wppm_task AS Task
					Left join {$wpdb->prefix}wppm_project proj ON Task.project = proj.id
					Left join {$wpdb->prefix}wppm_task_statuses task_statuses ON Task.status = task_statuses.id
					Left join {$wpdb->prefix}wppm_task_priorities task_priorities ON Task.priority = task_priorities.id
					Left join {$wpdb->prefix}wppm_project_users proj_users ON Task.project = proj_users.proj_id
					Left join {$wpdb->base_prefix}users user ON (FIND_IN_SET(user.id,Task.users)>0) AND user.display_name LIKE '$search_tag_text'
					Left join {$wpdb->prefix}wppm_project_meta proj_meta ON Task.project = proj_meta.project_id
					");
				$no_of_rows = ( "SELECT count(*) FROM ($query");
				$where = " where $wppm_tl_filter AND (((FIND_IN_SET('$current_user->ID',Task.users)>0) OR (Task.created_by= '$current_user->ID') OR (proj_users.user_id = '$current_user->ID' AND proj_users.role_id = 1 AND (FIND_IN_SET('$current_user->ID',proj.users)>0)) OR (Task.project = proj_meta.project_id AND proj_meta.meta_key='public_project' AND proj_meta.meta_value=1)) AND (Task.task_name LIKE '$search_tag_text' OR proj.project_name LIKE '$search_tag_text' OR task_statuses.name LIKE '$search_tag_text' OR task_priorities.name LIKE '$search_tag_text' OR user.display_name LIKE '$search_tag_text')) AND Task.status= $status->id";
			}
		}else{
			if($current_user->has_cap('manage_options')){
				$query = ( "SELECT Task.* FROM {$wpdb->prefix}wppm_task AS Task
							Left join {$wpdb->prefix}wppm_project_meta proj_meta ON Task.project = proj_meta.project_id
				");
				$no_of_rows = ( "SELECT count(*) FROM ($query");
				$where = " where $wppm_tl_filter AND Task.status= $status->id";
			}else{
				$query = ( "SELECT Task.*
					FROM {$wpdb->prefix}wppm_task AS Task
					Left join {$wpdb->prefix}wppm_project proj ON Task.project = proj.id
					Left join {$wpdb->prefix}wppm_project_users proj_users ON Task.project = proj_users.proj_id
					Left join {$wpdb->prefix}wppm_project_meta proj_meta ON Task.project = proj_meta.project_id
					");
				$no_of_rows = ( "SELECT count(*) FROM ($query");
				$where = " where $wppm_tl_filter AND ((FIND_IN_SET('$current_user->ID',Task.users)>0) OR (Task.created_by= '$current_user->ID') OR (proj_users.user_id = '$current_user->ID' AND proj_users.role_id = 1 AND (FIND_IN_SET('$current_user->ID',proj.users)>0)) OR (Task.project = proj_meta.project_id AND proj_meta.meta_key='public_project' AND proj_meta.meta_value=1)) AND Task.status= $status->id";
			}
		} 
		$query = apply_filters('wppm_query_for_grid_view',$query);
		$no_of_rows = apply_filters('wppm_number_of_rows_query_for_grid_view',$no_of_rows);
		$where = apply_filters('wppm_task_list_where_for_grid_view',$where,$wppm_tl_filter,$search_tag);
		$no_of_rows .= $where;
		$no_of_rows .= " Group by Task.id) AS Task";
		$no_of_rows = apply_filters('wppm_task_list_no_of_rows_for_grid_view',$no_of_rows);
		$totalrows = $wpdb->get_var($no_of_rows);
		$totalrows_tasks +=$totalrows;
		if($total_no_of_rows > $totalrows){
			$totalrows = $total_no_of_rows;
		}else{
			$total_no_of_rows = $totalrows;
		}
		$limit_start=$page_no*$task_per_page;
		$limit="\n LIMIT ".$limit_start.",".$task_per_page." ";
		$current_page=$page_no+1;
		$query = apply_filters('wppm_tasks_grid_view_query',$query,$search_tag);
		$where .= " Group by Task.id";
		$query .= $where;
		$query = apply_filters('wppm_task_list_grid_view_query',$query);
		$query = $query.$limit;
		$wppm_task_fillter[] = $wpdb->get_results($query);
	}
	$total_pages=ceil($total_no_of_rows/$task_per_page);
	$prev_page_no=$current_page-1;
	$prev_class=(!$prev_page_no)?'disabled':'';
	$next_page_no=($total_pages==$current_page)? $current_page-1:$current_page;
	$next_class=($total_pages==$current_page)?'disabled':'';
}
?>
<form name="wppm_view_project_task" id="wppm_view_project_task">
	<div class="row">
		<div class="col-sm-6">
			<span class="wppm-heading-inline"> <?php echo esc_html_e('Tasks','taskbuilder');?> </span>
			<span class="wppm-add-new-btn btn-primary" onclick="wppm_add_new_task()"><img class="wppm_add_new_task_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/plus_icon.svg'); ?>" alt="add"><?php echo esc_html_e('Add New','taskbuilder');?></span>
			<span class="wppm-add-new-btn btn-primary" id="wppm_task_list" onclick="wppm_get_task_list()" ><span><img class="wppm_task_list_image" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/list-symbol.svg'); ?>" alt="list"></span><span><?php echo esc_html_e('Task List','taskbuilder');?></span></span>
		</div>
		<div id="wppm_task_search" class="col-sm-6">
			<input type="search" id="wppm_view_task_search_filter" name="wppm_view_task_search_filter" class="form-control form-control-sm" aria-controls="selection-datatable" placeholder="<?php echo esc_attr('Search','taskbuilder')?>" value="<?php echo (!empty($search_tag)) ? esc_attr($search_tag) : "" ?>">
			<span><img width="25" height="15" class="wppm_task_search_filter_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/search.svg'); ?>" alt="search"></span>
		</div>
	</div>
	<div class="row">
      <div class="col-sm-6" id="wppm_task_filter_container">
        <div class="wppm-filter-item">
          <label for="wppm_task_filter"> <?php echo esc_html_e('Filter','taskbuilder');?></label>
          <select id="wppm_task_filter" name="wppm_task_filter" onchange="wppm_apply_task_filter_grid_view()">
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
              <span class="wppm-link" onclick="wppm_tl_reset_grid_view_filter()"> <?php echo esc_html_e('Reset','taskbuilder');?></span>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
	  	<span id="wppm_list_view_btn" onclick="wppm_get_task_list()"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/listv.svg'); ?>" alt="list"><span style="margin-left:5px;"><?php echo esc_html_e('List view','taskbuilder');?></span></span>
      </div>
  	</div>
	<div class="wppm_task_container" id="wppm_task_container">
		<?php 
		if(!empty($task_status)){
			foreach($task_status as $status) {
				?>
				<div class="wppm_task_list" id="wppm_task_list_<?php echo esc_attr($status->id)?>">
					<div class="row">
						<div class="col-sm-12 wppm_card_header" style="background-color:<?php echo esc_attr($status->bg_color)?>;color:<?php echo esc_attr($status->color);?>;"> 
							<span class="wppm_status_name"><?php echo esc_html_e($status->name,'taskbuilder');?></span>
							<span class="wppm_add_new_icon" onclick="wppm_add_new_task()"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/add_new1.svg'); ?>" alt="add"></span>
						</div>
					</div>
					<div id="wppm_card_body_container_<?php echo esc_attr($status->id) ?>" style="height:100%;">
					<?php
					$total_checklist_items=0;
					$total_checked_items=0;
					$task_per_status = false;
					$total_tasks = 0;
					if(!empty($wppm_task_fillter)){
						foreach($wppm_task_fillter as $key=>$tasks){
							foreach($tasks as $task){
								$total_tasks++;
								if((!empty($task)) && $task->status==$status->id){
									$task_per_status = true;
									if(isset($task->priority)){
										$task_priority = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wppm_task_priorities where id = $task->priority" );
									}
									if($wppm_task_time == 1){
										$task_end_date = $task->end_date;
									}elseif($wppm_task_time == 0){
										$teDate = new DateTime($task->end_date);
										$task_end_date = $teDate->format('Y-m-d');
									}
									if(!empty($task->id)){
										$checklists = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wppm_checklist where task_id = $task->id" );
										$total=$wpdb->get_var("SELECT COUNT(Items.id) AS check_id
											FROM {$wpdb->prefix}wppm_checklist_items AS Items
											Left join {$wpdb->prefix}wppm_checklist checklist ON Items.checklist_id = checklist.id
											WHERE checklist.task_id = $task->id");
										$total_checked_items=$wpdb->get_var("SELECT COUNT(Items.id) AS check_id
											FROM {$wpdb->prefix}wppm_checklist_items AS Items
											Left join {$wpdb->prefix}wppm_checklist checklist ON Items.checklist_id = checklist.id
											WHERE checklist.task_id = $task->id AND Items.checked=1");
										?>
										<div class="wppm_card_body" onclick="wppm_open_task(<?php echo esc_attr($task->id)?>)" id="wppm_draggable_card_<?php echo esc_attr($task->id)?>">
											<div class="row">
												<div class="col-sm-6">
													<span class="wppm_td_task_priority" style="background-color:<?php echo (!empty($task_priority->bg_color))? esc_attr($task_priority->bg_color):"";?>;color:<?php echo (!empty($task_priority->color))? esc_attr($task_priority->color):"";?>"><?php echo (!empty($task_priority->name))? esc_html($task_priority->name):"" ?></span>
												</div>
												<div class="wppm_card_task_action col-sm-6">
													<span class="wppm_checklist_total_checked_item"><?php echo esc_html($total_checked_items.'/'.$total)?></span><span class="wppm_total_checked_item"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/checked.svg'); ?>" alt="checked"></span>
													<span class="wppm_show_dropdown_menu" onclick="wppm_show_dropdown_menu(<?php echo esc_attr($task->id);?>)" data-popover="wppm-dropdown-menu-<?php echo esc_attr($task->id);?>" id="wppm_task_action_<?php echo esc_attr($task->id);?>"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/vertical_dot.svg'); ?>" alt="vertical_dot"></span>
													<div class="gpopover" id="wppm-dropdown-menu-<?php echo esc_attr($task->id);?>">
														<a class="dropdown-item" href="#"><?php echo esc_html_e('Edit','taskbuilder');?></a>
														<a class="dropdown-item" href="#"><?php echo esc_html_e('Delete','taskbuilder');?></a>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<span class="wppm_task_name_grid_view"><?php echo esc_html_e($task->task_name,'taskbuilder');?></span> 
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6 wppm_task_due_date_grid_view">
												<?php $style = ($task->status!=4 && $task->end_date < $current_date) ? "color:#FF0000":"color:#2C3E50"; ?>
													<small style="<?php echo esc_attr($style); ?>"><?php echo (isset($task_end_date))? esc_html_e($task_end_date,'taskbuilder') :"" ?></small>
												</div> 
												<div class="col-sm-6" class="wppm_card_task_users" style="text-align:right;">
													<?php
													$task_users = explode(',',$task->users);
													$i=0;
													$project_data = $wppmfunction->get_project($task->project);
													if(!empty($project_data['users'])){
														$proj_users = explode(',',$project_data['users']);
													}
													if(!empty($task_users)){
														foreach($task_users as $user){
															if( (!empty($proj_users)) && (in_array($user,$proj_users))){ 
																$i++;
																if( $i <= 4 ){
																	if(!empty($user)){
																		$userdata = get_userdata( $user );
																		?>
																		<a href="#"  title="<?php echo esc_attr($userdata->display_name)?>">
																			<?php echo get_avatar($user, 25, "mysteryman");?>
																		</a>
																<?php } 
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
												</div>
											</div>
										</div>
										<?php
									}
								}
							}
						} 
					}
					if($task_per_status==false){
						?>
						<div class="row">
							<div class="col-sm-12 wppm_empty_container">
								<?php echo esc_html_e('Empty','taskbuilder'); ?>
							</div>
						</div>
						<?php
					}
					?>
					</div>
				</div>
			<?php } 
		}
		?>
		<input type="hidden" action="wppm_drag_and_drop_card">
	</div>
	<div class="row wppm_task_pagination_container">
		<div class="col-sm-4">
			<?php echo esc_html_e('Total:','taskbuilder'); ?>&nbsp;<?php echo esc_html($total_tasks); ?>&nbsp;<?php echo esc_html_e( 'of','taskbuilder') ?>&nbsp;<?php echo esc_html($totalrows_tasks) ?>&nbsp;<?php echo esc_html_e('Tasks','taskbuilder')?>
		</div>
		<div class="wppm_task_pagination col-sm-4" style="<?php echo (esc_attr($total_pages)==0)? "display:none;":"display:flex;"?>">
			<span class="wppm-pagination-txt">
				<span><?php echo esc_html($current_page)?>&nbsp;<?php echo esc_html_e('of','taskbuilder');?>&nbsp;<?php echo esc_html($total_pages); ?>&nbsp;<?php echo esc_html_e('Page','taskbuilder'); ?></span>
			</span>
			<span <?php echo esc_attr($prev_class) ?> 
			style="<?php echo (isset($prev_class) && esc_attr($prev_class) == 'disabled') ? 'display:none;':'display:block;'?>cursor:pointer;">
			<a class="wppm_pagination_prev" onclick="return wppm_load_prev_task_page_card_view(<?php echo esc_attr($prev_page_no) ?>,<?php echo esc_attr($page_no)?>);"><?php echo esc_html_e('PREV','taskbuilder');?></a>
			</span>
			<span <?php echo esc_attr($next_class) ?>
			style="<?php echo (isset($next_class) && esc_attr($next_class) == 'disabled')?'display:none;':'display:block;'?>cursor:pointer;">
			<a class="wppm_pagination_next" onclick="return wppm_load_next_task_page_card_view(<?php echo esc_attr($next_page_no) ?>,<?php echo esc_attr($page_no) ?>);"><?php echo esc_html_e('NEXT','taskbuilder');?></a>
			</span>
		</div>
	</div>
</form>
<script type="text/javascript">
	jQuery( document ).ready( function( jQuery ) {
		dragula([
			<?php foreach($task_status as $status) { ?>
				document.getElementById("wppm_card_body_container_<?php echo esc_attr($status->id) ?>"),
			<?php } ?>
		], {removeOnSpill: false})
		.on('drop', function (el,target,source) { 
			wppm_drag_drop_card(el,target,source);
		});
	
		jQuery("input[name='wppm_view_task_search_filter']").keypress(function(e) {
			//Enter key
			if (e.which == 13) {
				e.preventDefault();
				wppm_display_grid_view();
			}
		});
  	}) 
	function wppm_show_dropdown_menu(task_id){
		jQuery("#wppm_task_action_"+task_id).gpopover({width: 50});
	}

	function wppm_drag_drop_card(el,target,source){
		var data = {
			action: 'wppm_drag_and_drop_card',
			el:el.id,
			target: target.id,
			source:source.id
		};
		jQuery.post(wppm_admin.ajax_url, data, function(response) {
			wppm_display_grid_view();
		});
	}
</script>