<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
$status = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wppm_project_statuses" );
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wppm_project_categories" );
$proj_per_page = 20;
$page_no = (isset($_POST['page_no'])) ? intval(sanitize_text_field($_POST['page_no'])): '0';
$search_tag = isset($_POST['project_search']) ? sanitize_text_field($_POST['project_search']) : '';
$order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) :"ASC";
$sort_by = isset($_POST['sort_by']) ? sanitize_text_field($_POST['sort_by']):"project_name";
$filter_by = isset($_POST['wppm_project_filter']) ? sanitize_text_field($_POST['wppm_project_filter']) : "all";
$current_date = date('Y-m-d');
$wppm_pl_filter = "1=1";
$filters = array(
  'sort_by' => $sort_by,
  'order' => $order,
  'search' => $search_tag,
  'filter'=>$filter_by
);
$pl_filters = isset( $_COOKIE['wppm_proj_filters'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['wppm_proj_filters'] ) ) : $filters;
if(!is_array($pl_filters)){
  $pl_filters  = json_decode($pl_filters);
  $pl_filters_arr = (array) $pl_filters;
} else{
  $pl_filters_arr = $filters;
}
$search_tag = isset($_POST['project_search']) ? sanitize_text_field($_POST['project_search']) : $pl_filters_arr['search'];
$order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) :$pl_filters_arr['order'];
$sort_by = isset($_POST['sort_by']) ? sanitize_text_field($_POST['sort_by']):$pl_filters_arr['sort_by'];
$filter_by = isset($_POST['wppm_project_filter']) ? sanitize_text_field($_POST['wppm_project_filter']) : $pl_filters_arr['filter'];
if($sort_by == ""){
  $sort_by = 'project_name';
}
$filters = array(
  'sort_by' => $sort_by,
  'order' => $order,
  'search'=>$search_tag,
  'filter'=>$filter_by
);
setcookie('wppm_proj_filters',wp_json_encode( $filters ),time() + 3600);
if($pl_filters_arr['sort_by']=='status'){
  $sort_by = 'proj_statuses.name';
}
if($pl_filters_arr['sort_by']=='category'){
  $sort_by = 'proj_categories.name';
}
if($pl_filters_arr['sort_by']=='start_date'){
  $sort_by = 'start_date';
}
if($pl_filters_arr['sort_by']=='end_date'){
  $sort_by = 'end_date';
}
if($sort_by=='category'){
  $sort_by = 'proj_categories.name';
}
if($filter_by=='completed'){
  $wppm_pl_filter = "status='4'";
}elseif($filter_by=='overdue'){
  $wppm_pl_filter = "status!='4' AND (CAST(end_date AS DATE)) < '$current_date'";
}elseif($filter_by == 'unassigned'){
  $wppm_pl_filter = "users=''";
}elseif($filter_by == 'new'){
	$wppm_pl_filter = "status='1'";
}elseif($filter_by == 'inprogress'){
	$wppm_pl_filter = "status='2'";
}elseif($filter_by == 'hold'){
	$wppm_pl_filter = "status='3'";
}elseif($filter_by == 'mine'){
	$wppm_pl_filter = "(FIND_IN_SET('$current_user->ID',users)>0)";
}
$wppm_project_time = get_option('wppm_project_time');
$wppm_default_project_date = get_option('wppm_default_project_date');
$sort_by = apply_filters('wppm_project_list_sort_by_query',$sort_by);
$order = apply_filters('wppm_project_list_order_query',$order);
$search_tag_text = '%'.$search_tag.'%';
if(!empty($search_tag)){
  $search_tag_text = '%'.$search_tag.'%';
  $query = ("SELECT Project.*
      FROM {$wpdb->prefix}wppm_project AS Project 
      Left join {$wpdb->prefix}wppm_project_statuses proj_statuses ON Project.status = proj_statuses.id
      Left join {$wpdb->prefix}wppm_project_categories proj_categories ON Project.cat_id = proj_categories.id
      Left join {$wpdb->base_prefix}users user ON (FIND_IN_SET(user.ID,Project.users)>0) AND (user.display_name LIKE '$search_tag_text')
      Left join {$wpdb->prefix}wppm_project_meta proj_meta ON Project.id = proj_meta.project_id
  ");
  if($current_user->has_cap('manage_options')){
    $where = " where $wppm_pl_filter AND (Project.project_name LIKE '$search_tag_text' OR proj_statuses.name LIKE '$search_tag_text' OR proj_categories.name LIKE '$search_tag_text' OR ( user.display_name LIKE '$search_tag_text'))";
  }else{
    $where = " where $wppm_pl_filter AND (Project.project_name LIKE '$search_tag_text' OR proj_statuses.name LIKE '$search_tag_text' OR proj_categories.name LIKE '$search_tag_text' OR ( user.display_name LIKE '$search_tag_text')) AND ((FIND_IN_SET('$current_user->ID',Project.users)>0) OR (Project.id = proj_meta.project_id AND proj_meta.meta_key='public_project' AND proj_meta.meta_value=1))";
  }
}else{
  if($sort_by=='project_name'|| $sort_by=='start_date' || $sort_by=='end_date'){
      $query = ( "SELECT Project.* FROM {$wpdb->prefix}wppm_project AS Project
        Left join {$wpdb->prefix}wppm_project_meta proj_meta ON Project.id = proj_meta.project_id
        ");
  } else{
    $query = ( "SELECT Project.* FROM {$wpdb->prefix}wppm_project AS Project
      Left join {$wpdb->prefix}wppm_project_statuses proj_statuses ON Project.status = proj_statuses.id
      Left join {$wpdb->prefix}wppm_project_categories proj_categories ON Project.cat_id = proj_categories.id
      Left join {$wpdb->prefix}wppm_project_meta proj_meta ON Project.id = proj_meta.project_id
    ");
  }
  if($current_user->has_cap('manage_options')){
    $where =  " where $wppm_pl_filter";
  }else{
    $where = " where ($wppm_pl_filter AND (FIND_IN_SET('$current_user->ID',Project.users)>0 OR (Project.id = proj_meta.project_id AND proj_meta.meta_key='public_project' AND proj_meta.meta_value=1)))";
  }
}
$no_of_rows = ( "SELECT count(*) FROM ($query");
$no_of_rows = apply_filters('wppm_project_list_no_of_rows',$no_of_rows,$wppm_pl_filter,$search_tag);
$where = apply_filters('wppm_project_list_query_where',$where, $wppm_pl_filter,$search_tag);
$query .= $where." Group by Project.id ORDER BY $sort_by $order";
$no_of_rows .= $where." Group by Project.id) AS Project";
$totalrows = $wpdb->get_var( $no_of_rows );
$query = apply_filters('wppm_project_list_query',$query);
$limit_start=$page_no*$proj_per_page;
$limit="\n LIMIT ".$limit_start.",".$proj_per_page." ";
$query = $query.$limit;
$wppm_fillter = $wpdb->get_results($query);
?>
<form id="wppm_project_list_frm">
  <div id="wppm_project_list_container" class="wppm_bootstrap">
    <div class="row">
      <div class="col-sm-6" id="wppm_heading_inline">
        <span class="wppm-heading-inline">
          <?php echo esc_html_e('Projects','taskbuilder');?>
        </span>
        <?php if($current_user->has_cap('manage_options')){ ?>
                  <img width="25" height="30" id="wppm_add_new_project_btn" class="wppm_add_new_project_btn" onclick="wppm_add_new_project()" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/add.svg'); ?>" alt="add">
        <?php } ?>
      </div>
      <div id="wppm_project_and_task_search" class="col-sm-6">
          <img width="25px" id="wppm_project_search_filter_img" height="15px" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/search.svg'); ?>" alt="search">
          <input type="search" id="wppm_project_search_filter" name="wppm_project_search_filter" class="form-control form-control-sm" aria-controls="selection-datatable" placeholder="<?php echo esc_attr__('Search','taskbuilder');?>" value="<?php echo (!empty($search_tag)) ? esc_attr($search_tag) : "" ?>">
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6" id="wppm_project_filter_container">
        <div class="wppm-filter-item">
          <label for="wppm_project_filter"> <?php echo esc_html_e('Filter','taskbuilder');?></label>
          <select id="wppm_project_filter" name="wppm_project_filter" onchange="wppm_apply_project_filter()">
            <option value="all"<?php echo ($filter_by == "all")? 'selected':""?>><?php echo esc_html_e('All','taskbuilder');?></option>
            <option value="new" <?php echo ($filter_by == "new")? 'selected':""?>><?php echo  esc_html_e('New','taskbuilder');?></option>
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
              <span class="wppm-link" onclick="wppm_pl_reset_filter()"> <?php echo esc_html_e('Reset','taskbuilder');?></span>
          </div>
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <table id="wppm_project_table" class="wppm_table table">
        <tr>
          <th class="wppm_table_header">
            <span id="wppm_proj_name_th"><?php echo esc_html_e('Project','taskbuilder'); ?></span>
            <img width="16" height="16" onclick="wppm_sort_up_project_list('project_name','ASC')" style = "<?php echo ($sort_by == 'project_name' && $order == 'DESC') ? 'display:inline': 'display:none;'?>" class="wppm_chevron_img" id="wppm_chevron_sort_up_project_name_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_up_icon.svg'); ?>" alt="chevron_sort_up_icon">
            <img width="16" height="16" onclick="wppm_sort_up_project_list('project_name','DESC')" style = "<?php echo ($sort_by == 'project_name' && $order == 'ASC') ? 'display:inline': 'display:none;'?>" class="wppm_chevron_img" id="wppm_chevron_sort_down_project_name_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_down_icon.svg'); ?>" alt="chevron_sort_down_icon">
          </th>
          <th class="wppm_table_header">
            <span id="wppm_project_status_th"><?php echo esc_html_e('Status','taskbuilder'); ?></span>
            <img width="16" height="16" onclick="wppm_sort_up_project_list('status','ASC')" style = "<?php echo ($sort_by == 'proj_statuses.name' && $order == 'DESC') ? 'display:inline': 'display:none;'?>" class="wppm_chevron_img" id="wppm_chevron_sort_up_project_status_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_up_icon.svg'); ?>" alt="chevron_sort_up_icon">
            <img width="16" height="16" onclick="wppm_sort_up_project_list('status','DESC')" style = "<?php echo ($sort_by == 'proj_statuses.name' && $order == 'ASC') ? 'display:inline': 'display:none;'?>" class="wppm_chevron_img" id="wppm_chevron_sort_down_project_status_img" class="wppm_chevron_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_down_icon.svg'); ?>" alt="chevron_sort_down_icon">
          </th>
          <th class="wppm_table_header"><?php echo esc_html_e('Users','taskbuilder'); ?></th>
          <th class="wppm_table_header">
            <span id="wppm_project_category_th"><?php echo esc_html_e('Category','taskbuilder'); ?></span>
            <img width="16" height="16" onclick="wppm_sort_up_project_list('category','ASC')" style = "<?php echo ($sort_by == 'proj_categories.name' && $order == 'DESC') ? 'display:inline': 'display:none;'?>" class="wppm_chevron_img" id="wppm_chevron_sort_up_project_cat_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_up_icon.svg'); ?>" alt="chevron_sort_up_icon">
            <img width="16" height="16" onclick="wppm_sort_up_project_list('category','DESC')" style = "<?php echo ($sort_by == 'proj_categories.name' && $order == 'ASC') ? 'display:inline': 'display:none;'?>" class="wppm_chevron_img" id="wppm_chevron_sort_down_project_cat_img" class="wppm_chevron_img"  src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_down_icon.svg'); ?>" alt="chevron_sort_down_icon">
          </th>
          <?php if($wppm_default_project_date==1){ ?>
                  <th class="wppm_table_header">
                    <span id="wppm_project_start_date_th"><?php echo esc_html_e('Start Date','taskbuilder'); ?></span>
                    <img width="16" height="16" onclick="wppm_sort_up_project_list('start_date','ASC')" style = "<?php echo ($sort_by == 'start_date' && $order == 'DESC') ? 'display:inline': 'display:none;'?>" class="wppm_chevron_img" id="wppm_chevron_sort_up_project_start_date_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_up_icon.svg'); ?>" alt="chevron_sort_up_icon">
                    <img width="16" height="16" onclick="wppm_sort_up_project_list('start_date','DESC')" style = "<?php echo ($sort_by == 'start_date' && $order == 'ASC') ? 'display:inline': 'display:none;'?>" class="wppm_chevron_img" id="wppm_chevron_sort_down_project_start_date_img" class="wppm_chevron_img"  src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_down_icon.svg'); ?>" alt="chevron_sort_down_icon">
                  </th>
                  <th class="wppm_table_header">
                    <span id="wppm_project_end_date_th"><?php echo esc_html_e('End Date','taskbuilder'); ?></span>
                    <img width="16" height="16" onclick="wppm_sort_up_project_list('end_date','ASC')" style = "<?php echo ($sort_by == 'end_date' && $order == 'DESC') ? 'display:inline': 'display:none;'?>" class="wppm_chevron_img" id="wppm_chevron_sort_up_project_end_date_img" src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_up_icon.svg'); ?>" alt="chevron_sort_up_icon">
                    <img width="16" height="16" onclick="wppm_sort_up_project_list('end_date','DESC')" style = "<?php echo ($sort_by == 'end_date' && $order == 'ASC') ? 'display:inline': 'display:none;'?>" class="wppm_chevron_img" id="wppm_chevron_sort_down_project_end_date_img" class="wppm_chevron_img"  src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/sort_down_icon.svg'); ?>" alt="chevron_sort_down_icon">
                  </th>
          <?php } ?>
          <th class="wppm_table_header"><?php echo esc_html_e('No. of tasks','taskbuilder'); ?></th>
          <th class="wppm_table_header"></th>
        </tr>
        <tbody>
          <?php
          $total_projects=0;
          $users = array();
          if(!empty($wppm_fillter)){ 
            foreach( $wppm_fillter as $project ){ 
                $total_projects++;
                $status = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wppm_project_statuses where id=$project->status" );
                $users = explode(",",$project->users);
                if(isset($project->cat_id)){
                  $category = $wpdb->get_var( "SELECT name FROM {$wpdb->prefix}wppm_project_categories where id=$project->cat_id" );
                }
                if(isset($project->id)){
                  if($current_user->has_cap('manage_options')){
                    $no_of_tasks = $wpdb->get_var("SELECT count(*) FROM {$wpdb->prefix}wppm_task WHERE project=$project->id ");
                  }else{
                    $query =("SELECT count(*) FROM  
                            ( SELECT Task.*
                            FROM {$wpdb->prefix}wppm_task AS Task
                            Left join {$wpdb->prefix}wppm_project proj ON Task.project = proj.id
                            Left join {$wpdb->prefix}wppm_project_users proj_users ON Task.project = proj_users.proj_id
                            Left join {$wpdb->prefix}wppm_project_meta proj_meta ON proj.id = proj_meta.project_id
                            where Task.project = $project->id AND ((FIND_IN_SET('$current_user->ID',Task.users)>0) OR (Task.created_by= '$current_user->ID') OR (proj_users.user_id = '$current_user->ID' AND proj_users.role_id = 1 ) OR (proj.id = proj_meta.project_id AND proj_meta.meta_key='public_project' AND proj_meta.meta_value=1)) Group by Task.id ) AS Task");
                    $no_of_tasks = $wpdb->get_var($query);
                  }
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
                <tr onclick="wppm_open_project(<?php echo esc_attr($project->id) ?>)">
                  <td><?php echo esc_html_e($project->project_name,'taskbuilder') ?></td>
                  <td><span class="wppm_td_project_status" style="background-color:<?php echo esc_attr($status->bg_color);?>; color: <?php echo esc_attr($status->color); ?>"><?php echo esc_html_e($status->name,'taskbuilder') ?></span></td>
                  <td>
                    <?php 
                      $i=0;
                      if(!empty(array_filter($users))){
                        foreach($users as $user){ 
                          $i++;
                          if( $i <= 4 ){
                            $user_info = get_user_by('id', $user );
                            if(!empty($user_info)){
                              ?>
                              <a href="#" title="<?php echo esc_html_e($user_info->display_name) ?>">
                                <?php echo get_avatar($user_info->ID, 25, "mysteryman");?>
                              </a> <?php
                            }
                          }
                        }
                      } else{
                        _e('None','wp_projects');
                      }
                    if($i > 4){
                    ?>
                    <a href="#" class="wppm_avatar">
                      <span id="wppm_avatar" style="background-color:black;" class="avatar">+<?php echo esc_html($i-4) ?></span>
                    </a>
                    <?php } ?>
                  </td>
                  <td class="wppm_table_td"><?php echo isset($category) ? esc_html_e($category,'taskbuilder'): "" ?></td>
                  <?php if($wppm_default_project_date==1){ ?>
                          <td class="wppm_table_td"><?php echo isset($proj_start_date) ? esc_html($proj_start_date): "" ?></td>
                          <?php $style = ($project->status!=4 && $proj_end_date < $current_date) ? "color:#FF0000":"color:#2C3E50"; ?>
                          <td class="wppm_table_td"><span style="<?php echo esc_attr($style); ?>"><?php echo isset($proj_end_date) ? esc_html($proj_end_date):"" ?></td></span>
                  <?php } ?>
                  <td class="wppm_table_td"><?php echo isset($no_of_tasks) ? esc_html($no_of_tasks):"" ?></td>
                  <td class="wppm_delete_action">
                    <?php $style = (($current_user->ID && $current_user->has_cap('manage_options')) || ($wppmfunction->has_project_permission('delete_project',esc_attr($project->id))))? "display:inline":"display:none"; ?>
                    <span style="<?php echo esc_attr($style); ?>" onclick="wppm_delete_project(<?php echo esc_attr($project->id) ?>)"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/trash.svg'); ?>" alt="delete"></span>
                    <?php ?>
                  </td>
                </tr>
              <?php
            } 
          } 
          ?>
        </tbody>
      </table>
      <?php 
      $total_pages=ceil($totalrows/$proj_per_page);
      $current_page=$page_no+1;
      $prev_page_no=$current_page-1;
      $prev_class=($prev_page_no==0)?'disabled':'';
      $next_page_no=($total_pages==$current_page)? $current_page-1:$current_page;
      $next_class=($total_pages==$current_page)?'disabled':'';
      ?>
      <div class="wppm_project_result" style="<?php echo esc_attr($total_pages==0)? '':'display: none;';?>"><?php echo esc_html_e('Your search request returned no results.','taskbuilder');?></div>
      <hr style="<?php echo esc_attr($total_pages==0)? '':'display: none;';?>">
    </div>
    <div class="row wppm_proj_pagination_container">
      <div class="col-sm-4">
        <?php echo esc_html_e('Total:','taskbuilder'); ?>&nbsp;<?php echo esc_html($total_projects) ?>&nbsp;<?php echo esc_html_e('of','taskbuilder') ?>&nbsp;<?php echo esc_html($totalrows) ?>&nbsp;<?php echo esc_html_e('Projects','taskbuilder')?>
      </div>
      <div class="col-sm-4">
        <div class="wppm_proj_pagination" style="<?php echo esc_attr($total_pages==0)? "display:none;":"display:flex;"?>">
          <span class="wppm-pagination-txt">
            <span><?php echo esc_html($current_page); ?>&nbsp;<?php echo esc_html_e('of','taskbuilder');?>&nbsp;<?php echo esc_html($total_pages); ?>&nbsp;<?php echo esc_html_e('Page','taskbuilder'); ?></span>
          </span>
          <span 
            <?php echo esc_html($prev_class) ?>
            style="<?php echo (isset($prev_class) && esc_attr($prev_class) == 'disabled') ? 'display:none;':'display:block;'?>">
            <a class="wppm_pagination_prev" onclick="return wppm_load_prev_page(<?php echo esc_attr($prev_page_no) ?>,<?php echo esc_attr($page_no) ?>,'<?php echo esc_attr($sort_by)?>','<?php echo esc_attr($order) ?>');"><?php echo esc_html_e('PREV','taskbuilder');?></a>
          </span>
          <span 
            <?php echo esc_attr($next_class) ?>
            style="<?php echo (isset($next_class) && $next_class == 'disabled')?'display:none;':'display:block;'?>cursor:pointer;">
            <a class="wppm_pagination_next" onclick="return wppm_load_next_page('<?php echo esc_attr($next_page_no) ?>','<?php echo esc_attr($page_no) ?>','<?php echo esc_attr($sort_by)?>','<?php echo esc_attr($order) ?>');"><?php echo esc_html_e('NEXT','taskbuilder');?></a>
          </span>
        </div>
      </div>
    </div>
  </div>
</form>
<script type="text/javascript">
  jQuery( document ).ready( function( jQuery ) {
    jQuery("input[name='wppm_project_search_filter']").keypress(function(e) {
          //Enter key
      if (e.which == 13) {
        wppm_project_search_filter();
      }
    });
    jQuery("#wppm_proj_name_th").hover(function(){
      jQuery('#wppm_chevron_sort_up_project_name_img').show();
      jQuery('#wppm_chevron_sort_down_project_name_img').hide();
    });
    jQuery("#wppm_project_status_th").hover(function(){
      jQuery('#wppm_chevron_sort_up_project_status_img').show();
      jQuery('#wppm_chevron_sort_down_project_status_img').hide();
    });
    jQuery("#wppm_project_category_th").hover(function(){
      jQuery('#wppm_chevron_sort_up_project_cat_img').show();
      jQuery('#wppm_chevron_sort_down_project_cat_img').hide();
    });
    jQuery("#wppm_project_start_date_th").hover(function(){
      jQuery('#wppm_chevron_sort_up_project_start_date_img').show();
      jQuery('#wppm_chevron_sort_down_project_start_date_img').hide();
    });
    jQuery("#wppm_project_end_date_th").hover(function(){
      jQuery('#wppm_chevron_sort_up_project_end_date_img').show();
      jQuery('#wppm_chevron_sort_down_project_end_date_img').hide();
    });
    jQuery(".wppm_delete_action").on("click", function(e){
      e.preventDefault();
      return false;
    });

    jQuery('#wppm_project_list_frm').on('submit', function (e) {
      var dataform = new FormData(jQuery('#wppm_project_list_frm')[0]);
        jQuery.ajax({
          url: wppm_admin.ajax_url,
          type: 'POST',
          data: dataform,
          processData: false,
          contentType: false,
          success: function () {
            alert('form was submitted');
          }
        });
    });
  });
</script>
<?php
add_action('wp_footer', 'wppm_page_inline_script', 999999999999999999);
do_action('wppm_after_shortcode_loaded');
if(!function_exists('wppm_page_inline_script')) {
  function wppm_page_inline_script() { ?>
    <script type="text/javascript">
      jQuery( document ).ready( function( jQuery ) {
        wppm_get_project_list();
      });
    </script>
  <?php } 
}  
?>