<?php
if (!defined('WPINC')) {
    die('Closed');
}
wp_enqueue_script('script_rm_moment');
wp_enqueue_script('script_rm_daterangepicker');
wp_enqueue_style('style_rm_daterangepicker');
$gopt=new RM_Options;
$blocked_emails=$gopt->get_value_of('banned_email');

$user_sort = isset($_GET['rm_sort']) ? sanitize_text_field($_GET['rm_sort']) : 'latest';
$rm_interval = isset($_GET['rm_interval']) ? sanitize_text_field($_GET['rm_interval']) : '';
$start_date = '';
$end_date = '';
if(!empty($rm_interval)){
   $interval = explode('-',(string)$rm_interval);
   $start_date = $interval[0];
   $end_date = $interval[1];
}
/* Sort Started */
$sorting = isset($_GET['rm_sort']) ? sanitize_text_field($_GET['rm_sort']) : 'latest';
$order_by = 'date';
$order = 'desc';
$new_date_sorting = 'oldest';
$new_user_sorting = '0toz';
if ($sorting == '0toz') {
    $order_by = 'username';
    $order = 'asc';
    $new_user_sorting = 'zto0';
}
if ($sorting == 'zto0') {
    $order_by = 'username';
    $order = 'desc';
    $new_user_sorting = '0toz';
}
if ($sorting == 'latest') {
    $order_by = 'date';
    $order = 'desc';
    $new_date_sorting = 'oldest';
}
if ($sorting == 'oldest') {
    $order_by = 'date';
    $order = 'asc';
    $new_date_sorting = 'latest';
}
/* Sorting */

    $rm_status = isset($_GET['rm_status']) ? sanitize_text_field($_GET['rm_status']) : 'all';
    $all_active_users_query = new WP_User_Query(
                            array(
                                'fields'=>'ID',
                                'meta_query' => array(
                                    'relation' => 'OR',
                                    array(
                                        'key' => 'rm_user_status',
                                        'value' => '1',
                                        'compare' => '!='
                                    ),
                                    array(
                                        'key' => 'rm_user_status',
                                        'value' => '1',
                                        'compare' => 'NOT EXISTS'
                                    )
                                )
                            )

    );
    $all_active_users = call_user_func(array($all_active_users_query, 'get_total'));
    //$all_active_users = $all_active_users_query->total_users;
    //get_user_count
    $all_deactive_users = count(get_users(
                    array('meta_query' => array(
                            array(
                                'key' => 'rm_user_status',
                                'value' => '1',
                                'compare' => '='
                            )
                        ))
    ));
    $all_deactive_users_query = new WP_User_Query(
                            
                            array(
                                'fields'=>'ID',
                                'meta_query' => array(
                                    'relation' => 'OR',
                                    array(
                                        'key' => 'rm_user_status',
                                        'value' => '1',
                                        'compare' => '='
                                    )
                                )
                            )

    );
    $all_deactive_users = call_user_func(array($all_deactive_users_query, 'get_total'));
    
    global $wp_roles;
    $all_roles = $wp_roles->roles;
    $user_ids = array();
    ?>
    <!-----Operationsbar Starts----->

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet">
    <div class="wrap">
        <div class="rmagic rmagic-wide rm-user-manager">
            <h1 class="wp-heading-inline"><?php esc_html_e('Users'); ?></h1>
            <a href="<?php echo admin_url('user-new.php');?>" class="page-title-action">Add New</a>
            <hr class="wp-header-end">
            <form id="rm_user_manager_sideform" action="<?php echo esc_url(add_query_arg('rm_reqpage', '1')); ?>" method="GET">
                    <div class="rm-box-w-100 rm-text-right alignright rm-mb-2">
                   <?php if($data->total_page) { ?>
                <div class="alignright">
                    <div class="rm-di-flex rm-box-center">
                        <span class="rm-white-space"><?php _e('Results per page', 'custom-registration-form-builder-with-submission-manager'); ?> &rarr;</span>
                        <select class="rm-pager-toggle" onchange="set_user_entry_depth(this);">
                            <option value="10" <?php echo $data->filter->pagination->entries_per_page == 10 ? esc_attr('selected') : ''; ?>>Page 1-10</option>
                            <option value="20" <?php echo $data->filter->pagination->entries_per_page == 20 ? esc_attr('selected') : ''; ?>>Page 1-20</option>
                            <option value="30" <?php echo $data->filter->pagination->entries_per_page == 30 ? esc_attr('selected') : ''; ?>>Page 1-30</option>
                            <option value="40" <?php echo $data->filter->pagination->entries_per_page == 40 ? esc_attr('selected') : ''; ?>>Page 1-40</option>
                            <option value="50" <?php echo $data->filter->pagination->entries_per_page == 50 ? esc_attr('selected') : ''; ?>>Page 1-50</option>
                        </select>
                    </div>
                </div>
                <?php } ?>
               </div>
                <p class="search-box">
                    <label class="screen-reader-text" for="user-search-input"><?php esc_html_e('Search Users','custom-registration-form-builder-with-submission-manager');?>:</label>
                    <input type="search" id="user-search-input" name="rm_to_search" value="<?php echo sanitize_text_field($data->filter->filters['rm_to_search']); ?>">
                    <input type="submit" id="search-submit" class="button" onclick="rm_user_search();" value="<?php esc_html_e('Search Users','custom-registration-form-builder-with-submission-manager');?>">
                </p>
                
                
                
                <ul class="subsubsub">
                    <input type="hidden" name="rm_status" value="<?php echo $rm_status; ?>">
                    <input type="hidden" name="rm_slug" value="" id="rm_slug_input_field"/>
                    <input type="hidden" name="page" value="rm_user_manage"/>
                    <input type="hidden" name="rm_sort" value="<?php echo isset($_GET['rm_sort']) && in_array($_GET['rm_sort'], array('latest','oldest','0toz','zto0')) ? sanitize_text_field($_GET['rm_sort']) : 'latest';?>"/>
                    <li class="all"><a href="?page=rm_user_manage" class="<?php if ($rm_status == 'all') { echo 'current';} ?>" aria-current="page">All <span class="count">(<?php echo get_user_count(); ?>)</span></a> |</li>

                    <li class="active"><a href="?page=rm_user_manage&rm_status=active" class="<?php if ($rm_status == 'active') { echo 'current';} ?>">Active <span class="count">(<?php echo $all_active_users; ?>)</span></a> |</li>
                    <li class="pending"><a href="?page=rm_user_manage&rm_status=pending" class="<?php if ($rm_status == 'pending') { echo 'current';} ?>">Inactive <span class="count">(<?php echo $all_deactive_users; ?>)</span></a></li>
                </ul>
             

         
                <div class="tablenav top rm-tablenav-top">
                    <div class="alignleft actions bulkactions">
                        <label for="bulk-action-selector-top"  class="screen-reader-text"><?php _e('Select bulk action', 'custom-registration-form-builder-with-submission-manager'); ?></label>
                        <select name="action" id="rm_user_bulk_actions">
                            <option value="-1"><?php _e('Bulk actions','custom-registration-form-builder-with-submission-manager');?></option>
                            <option value="activate"><?php _e('Activate','custom-registration-form-builder-with-submission-manager');?></option>
                            <option value="deactivate"><?php _e('Deactivate','custom-registration-form-builder-with-submission-manager');?></option>
                            <option value="delete"><?php _e('Delete','custom-registration-form-builder-with-submission-manager');?></option>
                        </select>
                        <input type="button" id="doaction" onclick="rm_bulk_actions()" class="button action" value="Apply">
                    </div>
                    <div class="alignleft actions">
                        <select name="rm_change_user_role" id="rm_change_user_role">
                            <option value=""><?php _e('Change role to...','custom-registration-form-builder-with-submission-manager');?></option>
                            <?php
                            if (!empty($all_roles)) {
                                foreach ($all_roles as $role_key => $role) {
                                    ?>   
                                    <option value="<?php esc_attr_e($role_key); ?>"><?php esc_attr_e($role['name']); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <input type="button" id="rm_change_role" onclick="rm_update_users_role()" class="button action" value="<?php _e('Change','custom-registration-form-builder-with-submission-manager');?>">
                    </div>
                    <div class="alignleft actions">
                        
                        <input type="text" name="rm_interval" id="rm_users_date" placeholder="<?php _e('All Dates','custom-registration-form-builder-with-submission-manager');?>" value="<?php echo $rm_interval;?>" readonly/>
                        <!--<span class="rm-user-clear-date material-icons" onclick="rm_clear_date_format(this)" style="<?php if(empty($rm_interval)){echo 'display:none;';}?>">clear</span>-->
                    </div>

                    <div class="alignleft actions">
                        <select name="rm_user_role" id="rm_user_role_filters">
                            <option value="all"><?php _e('All Roles','custom-registration-form-builder-with-submission-manager');?></option>
                            <?php
                            $rm_user_role = isset($_GET['rm_user_role']) ? sanitize_text_field($_GET['rm_user_role']) : '';
                            if (!empty($all_roles)) {
                                foreach ($all_roles as $role_key => $role) {
                                    ?>   
                                    <option value="<?php esc_attr_e($role_key); ?>" <?php echo selected($role_key, $rm_user_role); ?>><?php esc_attr_e($role['name']); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <input type="submit" id="rm_update_role" class="button action" value="<?php _e('Filter','custom-registration-form-builder-with-submission-manager');?>">
                    </div>
                    <div class="alignleft actions">
                        <a href="<?php echo admin_url('admin.php?page=rm_user_role_manage');?>" target="_blank" class="button action" >Add Role</a>
                    </div>
                    <?php if($data->total_page){?>
                    <div class="tablenav-pages"><span class="displaying-num"><?php echo sprintf(__('%s items', 'custom-registration-form-builder-with-submission-manager'), $data->filter->total_entries); ?></span>
                        <span class="pagination-links">
                        <?php if ($data->filter->pagination->curr_page == 1) { ?>
                                <span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                                <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                            <?php } else { ?>
                                <a class="first-page button" href="javascript:void(0)" onclick="rm_load_prev_page('first');"><span class="screen-reader-text"><?php _e('First Page','custom-registration-form-builder-with-submission-manager');?></span><span aria-hidden="true">«</span></a>
                                <a class="prev-page button" href="javascript:void(0)" onclick="rm_load_prev_page('prev');"><span class="screen-reader-text"><?php _e('Previous Page','custom-registration-form-builder-with-submission-manager');?></span><span aria-hidden="true">‹</span></a>
                        <?php } ?>
                            <span class="paging-input">
                                <label for="current-page-selector" class="screen-reader-text">Current Page</label>
                                <input name="rm_reqpage" class="current-page" id="current-page-selector" type="text" value="<?php echo $data->filter->pagination->curr_page ?>" size="1" min="1" max="<?php echo $data->filter->pagination->total_pages; ?>" aria-describedby="table-paging">
                                <span class="tablenav-paging-text"> of <span class="total-pages"><?php echo $data->total_page; ?></span></span>
                            </span>
                        <?php if ($data->filter->pagination->curr_page >= $data->total_page) { ?>
                                <span class="screen-reader-text">Next page</span><span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
                                <span class="screen-reader-text">Last page</span><span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span>
                        <?php } else { ?>
                            <a class="next-page button" href="javascript:void(0)" onclick="rm_load_next_page('next');"><span class="screen-reader-text"><?php _e('Next Page','custom-registration-form-builder-with-submission-manager');?></span><span aria-hidden="true">›</span></a>
                            <a class="last-page button" href="javascript:void(0)" onclick="rm_load_next_page('last');"><span class="screen-reader-text"><?php _e('Last Page','custom-registration-form-builder-with-submission-manager');?></span><span aria-hidden="true">»</span></a>
                        <?php } ?>
                        </span>    
                    </div>
                    <?php }?>
                </div>
            </form>
            
                <form method="POST" name="rm_user_manage" id="rm_user_manager_form">
                    
                    <?php wp_nonce_field('rm_user_manage'); ?>
                    <input type="hidden" name="rm_slug" value="" id="rm_slug_input_field">
                    <table class="rm_inbox_table wp-list-table widefat striped table-view-list">
                        <thead>
                            <tr>
                                <td scope="col" class="manage-column check-column">
                                    <label class="screen-reader-text" for="cb-select-all-1"><?php _e('Select All','custom-registration-form-builder-with-submission-manager');?></label>
                                    <input class="rm_checkbox_group" onclick="rm_submission_selection_toggle(this)" type="checkbox" name="rm_select_all">
                                </td>
                                
                                <th scope="col" id="username" class="manage-column column-username column-primary <?php if($order_by=='username'){ echo 'sorted '.$order;}else{ echo 'sortable';} ?> " aria-sort="ascending" abbr="Username">
                                    <a href="<?php echo esc_url(add_query_arg('rm_sort', $new_user_sorting));?>">
                                        <span><?php _e('User','custom-registration-form-builder-with-submission-manager');?></span>
                                        <span class="sorting-indicators">
                                            <span class="sorting-indicator asc" aria-hidden="true"></span>
                                            <span class="sorting-indicator desc" aria-hidden="true"></span>
                                                
                                        </span>
                                    </a>
                                </th>
                                <th scope="col" class="manage-column"><?php _e('Name','custom-registration-form-builder-with-submission-manager');?></th>
                                <th scope="col" class="manage-column"><?php _e('Role','custom-registration-form-builder-with-submission-manager');?></th>
                                <th scope="col" class="manage-column rm-text-center"><?php _e('Submissions','custom-registration-form-builder-with-submission-manager');?></th>
                                <th scope="col" class="manage-column rm-text-center"><span class="material-icons">mail</span></th>
                                <th scope="col" class="manage-column rm-text-center"><?php _e('Revenue','custom-registration-form-builder-with-submission-manager');?></th>
                                <th scope="col" id="date" class="manage-column column-date column-primary rm-text-center <?php if($order_by=='date'){ echo 'sorted '.$order;}else{ echo 'sortable';} ?> " aria-sort="ascending" abbr="Username">
                                    <a href="<?php echo esc_url(add_query_arg('rm_sort', $new_date_sorting));?>">
                                        <span><?php _e('Registered On','custom-registration-form-builder-with-submission-manager');?></span>
                                        <span class="sorting-indicators">
                                            <span class="sorting-indicator asc" aria-hidden="true"></span>
                                            <span class="sorting-indicator desc" aria-hidden="true"></span>
                                                
                                        </span>
                                    </a>
                                </th>
                                <th scope="col" class="manage-column"><?php _e('Last login','custom-registration-form-builder-with-submission-manager');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($data->users) && is_array($data->users) || is_object($data->users)) {
                                foreach ($data->users as $user):
                                    $user_ids[] = $user->ID;
                                    $user_info = get_userdata($user->ID);
                                    $is_disabled = (int) get_user_meta($user->ID, 'rm_user_status', true);
                                    if ($is_disabled == 1){
                                        $status = 'deactive';
                                        $user_status = RM_UI_Strings::get('LABEL_DEACTIVATED');
                                    }else{
                                        $status = 'active';
                                        $user_status = RM_UI_Strings::get('LABEL_ACTIVATED');
                                    }
                                    ?>
                                    <tr>
                                        <th scope="row" class="check-column">
                                            <input class="rm_checkbox_group rm_sub_checkbox" type="checkbox" data-email="<?php echo esc_attr($user->user_email); ?>" <?php echo get_current_user_id() == $user->ID ? 'disabled' : ''; ?> value="<?php echo esc_attr($user->ID); ?>" name="rm_users[]">
                                        </th>
                                        <td class="column-username title column-title has-row-actions has-row-actions column-primary">
                                            
                                            <span class="rm-position-relative">
                                            <?php echo get_avatar($user->ID, 32, '', '', array('class' => 'rm-inline-block rm-rounded-circle rm-object-cover-fit rm-mr-2')); ?>
                                            <?php if(RM_Utilities::is_user_online($user->ID)){?>
                                                <span class="rm-login-user-status rm-login-user-online rm-position-absolute" style="right: 6px;"><i class="fa fa-circle"></i></span>
                                            <?php }?>
                                            </span> 
                                            <strong>
                                                <a href="<?php echo esc_url(admin_url('admin.php?page=rm_user_view&user_id='.$user->ID));?>" class="row-title <?php if(is_array($blocked_emails) && in_array($user->user_email, $blocked_emails)){echo 'rm-blocked-user rm-text-danger';}?>">
                                                    <?php echo esc_html($user->user_email); ?>
                                                </a>  
                                                <span class="rm-user-status post-state"><?php if (isset($status) && $status == 'deactive') { echo esc_html_e('— Inactive','custom-registration-form-builder-with-submission-manager');} ?></span>
                                            </strong>
                                      
                                            <div class="row-actions">
                                                <span class="rm-user-view"><a href="?page=rm_user_view&user_id=<?php echo esc_attr($user->ID); ?>" class=""><?php _e('View','custom-registration-form-builder-with-submission-manager');?>  </a>|</span>
                                                <?php 
                                                if(get_current_user_id() != $user->ID){
                                                if (isset($status) && $status == 'active') {
                                                    ?>
                                                    <span class="rm-user-deactive"><a href="javascript:void(0)" onclick="rm_user_actions(this, '<?php echo esc_attr($user->ID); ?>', 'deactivate')" class=""><?php _e('Deactivate','custom-registration-form-builder-with-submission-manager');?>  </a>|</span>

                                                    <?php
                                                    } else {?>
                                                    <span class="rm-user-active"><a href="javascript:void(0)" onclick="rm_user_actions(this, '<?php echo esc_attr($user->ID); ?>', 'activate')" class=""><?php _e('Activate','custom-registration-form-builder-with-submission-manager');?>  </a>|</span>
                                                    <?php
                                                }
                                                }
                                                if (defined('REGMAGIC_ADDON')){
                                                if(is_array($blocked_emails) && in_array($user->user_email, $blocked_emails)){?>
                                                <span class="rm-user-unblock"><a href="javascript:void(0)"  onclick="rm_user_actions(this, '<?php echo esc_attr($user->ID); ?>', 'unblock_email')" class=""><?php _e('Unblock Email','custom-registration-form-builder-with-submission-manager');?>  </a>|</span>
                                                <?php } else {?>
                                                <span class="rm-user-block"><a href="javascript:void(0)"  onclick="rm_user_actions(this, '<?php echo esc_attr($user->ID); ?>', 'block_email')" class=""><?php _e('Block Email','custom-registration-form-builder-with-submission-manager');?>  </a>|</span>
                                                
                                                <?php } }?>
                                                <?php if(get_current_user_id() != $user->ID){?>
                                                <span class="rm-user-delete trash"><a href="javascript:void(0)" class="submitdelete" aria-label="Delete Submission"  onclick="rm_user_actions(this, '<?php echo esc_attr($user->ID); ?>', 'delete')"><?php _e('Delete','custom-registration-form-builder-with-submission-manager');?> </a> |</span>
                                                <?php }?>
                                                <span class="rm-user-login-details inline"><a href="<?php echo esc_url(admin_url('admin.php?page=rm_user_view&section_id=ui-id-5&user_id='.$user->ID));?>" class=""><?php _e('Login Details','custom-registration-form-builder-with-submission-manager');?>  </a>|</span>
                                                <span class="rm-user-send-email"><a href="javascript:void(0)"  onclick="rm_show_send_mail_popup(<?php echo esc_attr($user->ID); ?>, '<?php echo esc_attr($user->user_email); ?>', 'send_email')" class=""><?php _e('Send Email','custom-registration-form-builder-with-submission-manager');?></a></span>

                                            </div>
                                        </td>

                                        <td><?php if (empty($user_info->display_name)){
                                            echo $user_info->first_name;
                                        }else{
                                            echo $user_info->display_name;
                                        }
                                        ?></td>
                                        <td>
                                            <?php
                                            if (isset($user->roles) && !empty($user->roles)) {
                                                echo ucfirst(implode(', ', $user->roles));
                                            } else {
                                                echo esc_html('None','custom-registration-form-builder-with-submission-manager');
                                            }
                                            ?>
                                        </td>
                                        <td class="rm-text-center" id="user-submissions-<?php esc_html_e($user->ID);?>">
                                            <?php 
                                            /*$total_submissions = isset($user->submissions) && !empty($user->submissions) ? count($user->submissions) : 0;
                                            if($total_submissions){
                                                ?>
                                                <a href="<?php echo esc_url(admin_url('admin.php?page=rm_user_view&section_id=ui-id-2&user_id='.$user->ID));?>"><?php echo $total_submissions;?></a>
                                                <?php
                                            }else{
                                                echo '<span aria-hidden="true">—</span>';
                                            }*/
                                            ?>
                                        </td>
                                        <td class="rm-text-center" id="user-emails-<?php esc_html_e($user->ID);?>">
                                            <?php
                                            /*if($user->sent_emails){
                                                ?>
                                            <a href="<?php echo esc_url(admin_url('admin.php?page=rm_user_view&section_id=ui-id-4&user_id='.$user->ID));?>"><?php echo esc_html($user->sent_emails);?></a>
                                                <?php
                                            }else{
                                                echo '<span aria-hidden="true">—</span>';
                                            }*/
                                            ?>
                                        </td>
                                        <td class="rm-text-center" id="user-revenue-<?php esc_html_e($user->ID);?>">
                                            <?php
                                            /*$revenue = isset($user->total_revenue) ? $user->total_revenue : 0;
                                            if($revenue){
                                                ?>
                                                <a href="<?php echo esc_url(admin_url('admin.php?page=rm_user_view&section_id=ui-id-3&user_id='.$user->ID));?>"><?php echo RM_Utilities::get_formatted_price(round($revenue, 2));?></a>
                                                <?php
                                            }else{
                                                echo '<span aria-hidden="true">—</span>';
                                            }
                                            */
                                            ?>
                                        </td>
                                        <td class=""><?php echo esc_html(RM_Utilities::localize_time($user->user_registered)); ?></td>
                                        <td class="">
                                            <?php
                                            $last_login = '';
                                            $login_details = RM_DBManager::get_login_log_by_email($user_info->user_email);
                                            if (isset($login_details) && !empty($login_details)) {
                                                foreach ($login_details as $login) {
                                                    if ($login->status == 1) {
                                                        $time = isset($login->time) && !empty($login->time) ? $login->time : '';
                                                        if ($time) {
                                                            $last_login = date('M d, Y @ h:i a', strtotime($time));
                                                        }
                                                        break;
                                                    }
                                                }
                                            }
                                            if($last_login){
                                                echo $last_login;
                                            }else{
                                                echo '<span aria-hidden="true">—</span>';
                                            }
                                            ?>
                                        </td>

                                    </tr>

                                    <?php
                                endforeach;
                            }else{
                                ?>
                                    <tr>
                                        <td colspan="9">
                                            <?php _e('No User Found.','custom-registration-form-builder-with-submission-manager');?>
                                        </td>
                                    </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td scope="col" class="manage-column check-column">
                                    <label class="screen-reader-text" for="cb-select-all-1"><?php _e('Select All','custom-registration-form-builder-with-submission-manager');?></label>
                                    <input class="rm_checkbox_group" onclick="rm_submission_selection_toggle(this)" type="checkbox" name="rm_select_all">
                                </td>
                                
                                <th scope="col" id="username" class="manage-column column-username column-primary <?php if($order_by=='username'){ echo 'sorted '.$order;}else{ echo 'sortable';} ?> " aria-sort="ascending" abbr="Username">
                                    <a href="<?php echo esc_url(add_query_arg('rm_sort', $new_user_sorting));?>">
                                        <span><?php _e('User','custom-registration-form-builder-with-submission-manager');?></span>
                                        <span class="sorting-indicators">
                                            <span class="sorting-indicator asc" aria-hidden="true"></span>
                                            <span class="sorting-indicator desc" aria-hidden="true"></span>
                                                
                                        </span>
                                    </a>
                                </th>
                                <th scope="col" class="manage-column"><?php _e('Name','custom-registration-form-builder-with-submission-manager');?></th>
                                <th scope="col" class="manage-column"><?php _e('Role','custom-registration-form-builder-with-submission-manager');?></th>
                                <th scope="col" class="manage-column rm-text-center"><?php _e('Submissions','custom-registration-form-builder-with-submission-manager');?></th>
                                <th scope="col" class="manage-column rm-text-center"><span class="material-icons">mail</span></th>
                                <th scope="col" class="manage-column rm-text-center"><?php _e('Revenue','custom-registration-form-builder-with-submission-manager');?></th>
                                <th scope="col" id="date" class="manage-column column-date column-primary rm-text-center <?php if($order_by=='date'){ echo 'sorted '.$order;}else{ echo 'sortable';} ?> " aria-sort="ascending" abbr="Username">
                                    <a href="<?php echo esc_url(add_query_arg('rm_sort', $new_date_sorting));?>">
                                        <span><?php _e('Registered On','custom-registration-form-builder-with-submission-manager');?></span>
                                        <span class="sorting-indicators">
                                            <span class="sorting-indicator asc" aria-hidden="true"></span>
                                            <span class="sorting-indicator desc" aria-hidden="true"></span>
                                                
                                        </span>
                                    </a>
                                </th>
                                <th scope="col" class="manage-column "><?php _e('Last login','custom-registration-form-builder-with-submission-manager');?></th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="rmagic-popup-container">
                            <div id="rm_user_delete_popup" class="rm-modal rm-box-modal-view" style="display: none;">
                                <div  class="rm-box-modal-overlay rm-modal-overlay-fade-in"></div>
                                
                                <div class="rm-popup-content rm-modal-dialog rm-modal-dialog-centered rm-box-modal-wrap rm-modal-lg rm-modal-out rm-user-delete-popup">
                                    <div class="rm-modal-body rm-overflow-hidden">
                                      

                                            <div class="rm-box-modal-titlebar rm-bg-white rm-border rm-box-w-100 rm-px-3 rm-py-2 rm-position-relative">

                                                <span class="rm-modal-close rm-mt-2 rm-px-1"><span class="material-icons"> close </span></span>

                                                <div class="rm-modal-title rm-fw-bold">
                                                    <?php _e("Delete Users", 'custom-registration-form-builder-with-submission-manager'); ?>
                                                </div>
                                            </div>
                                            <div class="rm-user-delete-popup-wrap rm-modal-content-wrap rm-box-wrap rm-py-3" style="background-color: #f3f6f9;">
                                            <div class="rm-modal-container">
                                                <div class="rm-user-delete-wrap">
                                                    <span class="user_msg1"></span>    
                                                    <div class="rm_user_datails"></div><br>
                                                    <span class="user_msg2"></span>
                                                    <div class="rm_delete_options">
                                                        <ul style="list-style:none;">
                                                            <li>
                                                                <label>
                                                                    <input type="radio" name="rm_delete_option" value="delete">
                                                                    <?php _e("Delete all content", 'custom-registration-form-builder-with-submission-manager'); ?>
                                                                </label>
                                                            </li>
                                                            <li>
                                                                <input checked type="radio" name="rm_delete_option" value="reassign">
                                                                <label for="delete_option"><?php _e("Attribute all content to:", 'custom-registration-form-builder-with-submission-manager'); ?></label> 
                                                                <?php $users = get_users(array('role'=>'administrator')); ?>
                                                                <select name="rm_reassign_user" id="rm_reassign_user" class="">
                                                                    <?php foreach ($users as $single_user): ?>
                                                                        <option value="<?php echo esc_attr($single_user->ID); ?>"><?php echo esc_html($single_user->email) . ' (' . esc_html($single_user->display_name) . ')'; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>    
                                                            </li>    
                                                        </ul>                
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="rm-modal-footer rm-text-right rm-border-0 rm-mt-3">
                                                <div class="rm-difl rm_user_delete-cancel-bt rm-d-none"><a href="javascript:void(0)" class="rm-model-cancel">← &nbsp;<?php _e("Cancel", 'custom-registration-form-builder-with-submission-manager'); ?></a></div>
                                                <div class="rm-difl rm_user_delete-bt">
                                                    <a onclick="rm_confirm_user_deletion()"><?php _e("Confirm Deletion", 'custom-registration-form-builder-with-submission-manager'); ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div id="rm_user_send_email_list_popup" class="rm-modal rm-box-modal-view " style="display: none;">
                                <div class="rm-box-modal-overlay rm-modal-overlay-fade-in"></div>
                                
                                <div class="rm-popup-content rm-modal-dialog rm-modal-dialog-centered rm-box-modal-wrap rm-modal-lg rm-modal-out rm-user-send-email-popup">
                                  <div class="rm-modal-body rm-overflow-hidden">
                                    <div class="rm-box-modal-titlebar rm-bg-white rm-border rm-box-w-100 rm-px-3 rm-py-2 rm-position-relative">
                                     
                                        <span class="rm-modal-close rm-mt-2 rm-px-1"><span class="material-icons"> close </span></span>
                                        
                                           <div class="rm-modal-title rm-fw-bold">
                                            <?php _e("Send Email", 'custom-registration-form-builder-with-submission-manager'); ?>
                                        </div>
                                    </div>
                                    <div class="rm-user-send-email-wrap rm-modal-content-wrap rm-box-wrap rm-py-3" style="background-color: #f3f6f9;">
                            
                                            <div class="rm-box-row rm-mb-3 rm_popup_send_email_row">
                                                <div class="rm-box-col-12 rm-d-flex rm-align-items-center">
                                                    <span class="rm-box-w-10 rm_popup_send_email">
                                                        <?php _e('To', 'custom-registration-form-builder-with-submission-manager') ?>
                                                    </span> 
                                                    <div><?php echo get_avatar($user->ID, 32, '', '', array('class' => 'rm-inline-block rm-rounded-circle rm-object-cover-fit rm-mr-1')); ?></div>
                                                    <div id="rm-user-email"></div>
                                                    <input type="email" id="rm_popup_send_email_to" hidden class="rm-form-control rm-d-none">
                                                    
                                                   
                                                </div>
                                            </div>
                                            <div class="rm-box-row rm-mb-3 rm_popup_send_email_row">
                                                <div class="rm-box-col-12 rm-d-flex rm-align-items-center">
                                                <span class="rm-box-w-10 rm_popup_send_email">
                                                    <?php _e('Subject', 'custom-registration-form-builder-with-submission-manager') ?>
                                                </span> 
                                                <input type="text" id="rm_popup_send_email_sub" class="rm-form-control">
                                                </div>
                                            </div>
                                            <div class="rm-box-row rm_popup_send_email_row">
                                                <div class="rm-box-col-12 rm-d-flex rm-items-start">
                                                <span class="rm-box-w-10 rm_popup_send_email"><?php _e('Message', 'custom-registration-form-builder-with-submission-manager') ?></span> 
                                                <textarea id="rm_popup_send_email_body" class="rm-form-control"></textarea>
                                                </div>
                                            </div>
                                        
                                    <div class="rm-modal-footer rm-text-right rm-border-0 rm-mt-3">
                                        <div class="rm-difl rm_user_send_email-cancel-bt rm-d-none"><a href="javascript:void(0)" class="rm-model-cancel">← &nbsp;<?php _e("Cancel", 'custom-registration-form-builder-with-submission-manager'); ?></a></div>
                                        <div class="rm-difl rm_user_send_email-bt">
                                            <button type="button" class="button button-primary" id="rm_popup_send_email_button" onclick="rm_user_send_email_submit()"><?php _e("Send", 'custom-registration-form-builder-with-submission-manager'); ?></button>
                                        </div>
                                    </div>
                            
                                    </div>

                  
                                </div>
                                </div>
                            </div>

                        </div>
                </form>

            <div id="rm_footer_filter">
                

                <div class="tablenav top rm-tablenav-top">
                    <div class="alignleft actions bulkactions">
                        <label for="bulk-action-selector-top"  class="screen-reader-text"><?php _e('Select bulk action', 'custom-registration-form-builder-with-submission-manager'); ?></label>
                        <select name="action" onchange="rm_change_user_bulk_option(this)">
                            <option value="-1"><?php _e('Bulk actions','custom-registration-form-builder-with-submission-manager');?></option>
                            <option value="activate"><?php _e('Activate','custom-registration-form-builder-with-submission-manager');?></option>
                            <option value="deactivate"><?php _e('Deactivate','custom-registration-form-builder-with-submission-manager');?></option>
                            <option value="delete"><?php _e('Delete','custom-registration-form-builder-with-submission-manager');?></option>
                        </select>
                        <input type="button" id="doaction" onclick="rm_bulk_actions()" class="button action" value="Apply">
                    </div>
                    <div class="alignleft actions">
                        <select name="rm_change_user_role" onchange="rm_change_user_roles(this)">
                            <option value=""><?php _e('Change role to...','custom-registration-form-builder-with-submission-manager');?></option>
                            <?php
                            if (!empty($all_roles)) {
                                foreach ($all_roles as $role_key => $role) {
                                    ?>   
                                    <option value="<?php esc_attr_e($role_key); ?>"><?php esc_attr_e($role['name']); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <input type="button" id="rm_change_role" onclick="rm_update_users_role()" class="button action" value="<?php _e('Change','custom-registration-form-builder-with-submission-manager');?>">
                    </div>
                    <?php if($data->total_page){?>
                    <div class="tablenav-pages"><span class="displaying-num"><?php echo sprintf(__('%s items', 'custom-registration-form-builder-with-submission-manager'), $data->filter->total_entries); ?></span>
                        <span class="pagination-links">
                            
                        <?php if ($data->filter->pagination->curr_page == 1) { ?>
                                <span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                                <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                            <?php } else { ?>
                                <a class="first-page button" href="javascript:void(0)" onclick="rm_load_prev_page('first');"><span class="screen-reader-text"><?php _e('First Page','custom-registration-form-builder-with-submission-manager');?></span><span aria-hidden="true">«</span></a>
                                <a class="prev-page button" href="javascript:void(0)" onclick="rm_load_prev_page('prev');"><span class="screen-reader-text"><?php _e('Previous Page','custom-registration-form-builder-with-submission-manager');?></span><span aria-hidden="true">‹</span></a>
                        <?php } ?>
                            <span class="paging-input">
                                <label for="current-page-selector" class="screen-reader-text">Current Page</label>
                                <input name="rm_reqpage" class="current-page" id="current-page-selector" type="text" value="<?php echo $data->filter->pagination->curr_page ?>" size="1" min="1" max="<?php echo $data->filter->pagination->total_pages; ?>" aria-describedby="table-paging">
                                <span class="tablenav-paging-text"> of <span class="total-pages"><?php echo $data->total_page; ?></span></span>
                            </span>
                        <?php if ($data->filter->pagination->curr_page >= $data->total_page) { ?>
                                <span class="screen-reader-text">Next page</span><span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
                                <span class="screen-reader-text">Last page</span><span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span>
                        <?php } else { ?>
                            <a class="next-page button" href="javascript:void(0)" onclick="rm_load_next_page('next');"><span class="screen-reader-text"><?php _e('Next Page','custom-registration-form-builder-with-submission-manager');?></span><span aria-hidden="true">›</span></a>
                            <a class="last-page button" href="javascript:void(0)" onclick="rm_load_next_page('last');"><span class="screen-reader-text"><?php _e('Last Page','custom-registration-form-builder-with-submission-manager');?></span><span aria-hidden="true">»</span></a>
                        <?php } ?>
                        </span>    
                    </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    


    <pre class="rm-pre-wrapper-for-script-tags"><script type="text/javascript">
        jQuery(document).ready(function(e){
            <?php if($user_ids){?>
                var data = {
                    'action': 'rm_user_additional_details',
                    'rm_slug'  : 'rm_user_additional_details',
                    'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>',
                    'user_ids': <?php echo json_encode($user_ids);?>
                };
                jQuery.post(ajaxurl, data, function (response) {
                    
                    if(!jQuery.isEmptyObject(response.data)){
                        jQuery.each(response.data, function(i, user) {
                            var userID = user.ID;
                            if(user.submissions){
                                jQuery('#user-submissions-'+userID).html('<a target="_blank" href="<?php echo esc_url(admin_url('admin.php?page=rm_user_view&section_id=ui-id-2&user_id='));?>'+userID+'">'+user.submissions+'</a>');
                            }else{
                                jQuery('#user-submissions-'+userID).html('—');
                            }
                            if(user.total_revenue){
                                jQuery('#user-emails-'+userID).html('<a target="_blank" href="<?php echo esc_url(admin_url('admin.php?page=rm_user_view&section_id=ui-id-3&user_id='));?>'+userID+'">'+user.total_revenue+'</a>');
                            }else{
                                jQuery('#user-emails-'+userID).html('—');
                            }
                            if(user.sent_emails){
                                jQuery('#user-revenue-'+userID).html('<a target="_blank" href="<?php echo esc_url(admin_url('admin.php?page=rm_user_view&section_id=ui-id-4&user_id='));?>'+userID+'">'+user.sent_emails+'</a>');
                            }else{
                                jQuery('#user-revenue-'+userID).html('—');
                            }
                        });
                    }
                });
            <?php
            }
            ?>
        });
        function rm_update_users_role() {
            var user_ids = jQuery.map(jQuery('input[name="rm_users[]"]:checked'), function (c) {
                return c.value;
            });
            if (jQuery.isEmptyObject(user_ids)) {
                return;
            }
            if (jQuery('#rm_change_user_role').val() !== '') {
                var data = {
                    'action': 'rm_update_users_role',
                    'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>',
                    'role': jQuery('#rm_change_user_role option:selected').val(),
                    'user_ids': user_ids
                };
                jQuery.post(ajaxurl, data, function (response) {
                    if (response.data === true) {
                        location.reload();
                    }
                });
            }
        }
        function rm_user_actions(element, user_id, action) {
            //console.log(action);
            jQuery('input[name="rm_users[]"]:checkbox').removeAttr('checked');

            jQuery(element).closest('tr').find('input[name="rm_users[]"]').prop('checked', true);
            if (action === 'activate') {
                var form = jQuery("#rm_user_manager_form");
                jQuery('input#rm_slug_input_field').val('rm_user_activate');
                form.submit();
            } else if (action === 'deactivate') {
                var form = jQuery("#rm_user_manager_form");
                jQuery('input#rm_slug_input_field').val('rm_user_deactivate');
                form.submit();
            } else if (action === 'delete') {
                delete_action();
            } else if(action === 'block_email'){
                var form = jQuery("#rm_user_manager_form");
                jQuery('input#rm_slug_input_field').val('rm_user_block_user_email');
                form.submit();
            } else if(action === 'unblock_email'){
                var form = jQuery("#rm_user_manager_form");
                jQuery('input#rm_slug_input_field').val('rm_user_unblock_user_email');
                form.submit();
            }
        }
        function delete_action() {
            var selected = [];
            jQuery.each(jQuery("input[name='rm_users[]']:checked"), function () {
                selected.push({id: jQuery(this).val(), email: jQuery(this).data('email')});
            });
            if (jQuery.isEmptyObject(selected)) {
                return;
            }
            var html = '';
            for (var i = 0; i < selected.length; i++) {
                html += 'ID #' + selected[i].id + ": " + selected[i].email + "<br>";
                jQuery("#rm_reassign_user option[value='" + selected[i].id + "']").remove();
            }
            var delete_pop_container = jQuery("#rm_user_delete_popup");
            if (selected.length > 1) {
                delete_pop_container.find(".user_msg1").html('You have specified these users for deletion:');
                delete_pop_container.find(".user_msg2").html('What should be done with content owned by these users?');
            } else {
                delete_pop_container.find(".user_msg1").html('You have specified this user for deletion:');
                delete_pop_container.find(".user_msg2").html('What should be done with content owned by this user?');
            }
            delete_pop_container.find(".rm_user_datails").html(html);
            jQuery("#rm_user_delete_popup").show();
            jQuery('#rm_user_delete_popup .rm-box-modal-overlay').removeClass('rm-modal-overlay-fade-in').addClass('rm-modal-overlay-fade-out');
            jQuery('#rm_user_delete_popup .rm-box-modal-wrap').removeClass('rm-modal-out').addClass('rm-modal-in');
           
        }
        function rm_bulk_actions() {
            var action = jQuery('#rm_user_bulk_actions').val();
            var selected = [];
            jQuery.each(jQuery("input[name='rm_users[]']:checked"), function () {
                selected.push({id: jQuery(this).val(), email: jQuery(this).data('email')});
            });
            if (jQuery.isEmptyObject(selected)) {
                return;
            }
            if (action === 'delete') {
                delete_action();

            } else if (action === 'activate') {

                var form = jQuery("#rm_user_manager_form");
                jQuery('input#rm_slug_input_field').val('rm_user_activate');
                form.submit();

            } else if ((action === 'deactivate')) {
                var form = jQuery("#rm_user_manager_form");
                jQuery('input#rm_slug_input_field').val('rm_user_deactivate');
                form.submit();
            }

        }
        function rm_confirm_user_deletion() {
            var form = jQuery("#rm_user_manager_form");
            jQuery('input#rm_slug_input_field').val('rm_user_delete');
            form.submit();
        }
        function rm_show_send_mail_popup(user_id, user_email, action)
        {
            jQuery("#rm_popup_send_email_button").html("<?php _e('Send', 'custom-registration-form-builder-with-submission-manager') ?>");
            jQuery('#rm_popup_send_email_to').val(user_email);
            jQuery('#rm-user-email').html(user_email);
            jQuery('#rm_user_send_email_list_popup').show();
            jQuery('.rm-box-modal-overlay').removeClass('rm-modal-overlay-fade-in').addClass('rm-modal-overlay-fade-out');
            jQuery('.rm-box-modal-wrap').removeClass('rm-modal-out').addClass('rm-modal-in');
        }
        function flash_element(x) {
            x.each(function () {
                jQuery(this).css("border", "1px solid #FF6C6C");
                jQuery(this).fadeIn(100).fadeOut(1000, function () {
                    jQuery(this).css("border", "");
                    jQuery(this).fadeIn(100);
                    jQuery(this).val('');
                });
            });

        }
        function rm_user_send_email_submit()
        {
            if (!rm_validate_fields())
                return;
            //Disable send button to prevent multiple send requests.
            jQuery("#rm_popup_send_email_button").prop('disabled', true);
            jQuery("#rm_popup_cancel_email_button").prop('disabled', true);
            jQuery("#rm_popup_send_email_button").html("<i><?php _e('Sending...', 'custom-registration-form-builder-with-submission-manager') ?></i>");

            var address = jQuery('#rm_popup_send_email_to').val();
            var subject = jQuery('#rm_popup_send_email_sub').val();
            var message = jQuery('#rm_popup_send_email_body').val();
            var ajaxnonce = '<?php echo wp_create_nonce('rm_send_email_user_view'); ?>';
            var data = {action: 'send_email_user_view', to: address, sub: subject, body: message, rm_ajaxnonce: ajaxnonce};
            jQuery.post(ajaxurl, data, function () {
                jQuery('#rm_user_send_email_list_popup').hide();
                alert('<?php _e('Email has been sent successfully.', 'custom-registration-form-builder-with-submission-manager') ?>');
            });
        }

        function rm_validate_fields()
        {
            var jqel_subject = jQuery('#rm_popup_send_email_sub');
            var jqel_message = jQuery('#rm_popup_send_email_body');
            var is_valid = true;
            if (jqel_message.val().toString().trim() === '')
            {
                flash_element(jqel_message);
                is_valid = false;
            }

            if (jqel_subject.val().toString().trim() === '')
            {
                flash_element(jqel_subject);
                is_valid = false;
            }

            return is_valid;

        }
        function rm_load_next_page(next) {
            if (next == 'next') {
                jQuery('input[name=rm_reqpage]').val('<?php echo esc_html($data->filter->pagination->curr_page + 1); ?>');
            }
            if (next == 'last') {
                jQuery('input[name=rm_reqpage]').val('<?php echo esc_html($data->total_page); ?>');
            }
            jQuery('input[name=rm_search_initiated]').prop("disabled", true);
            //jQuery('form#rm_user_manager_sideform').submit();
            jQuery('#rm_user_manager_sideform').submit();
        }

        function rm_load_prev_page(prev) {
            if (prev == 'prev') {
                jQuery('input[name=rm_reqpage]').val('<?php echo esc_html($data->filter->pagination->curr_page - 1); ?>');
            }
            if (prev == 'first') {
                jQuery('input[name=rm_reqpage]').val('1');
            }
            jQuery('input[name=rm_search_initiated]').prop("disabled", true);
            jQuery('#rm_user_manager_sideform').submit();
            //jQuery('form#rm_user_manager_sideform').submit();
        }
        function rm_change_user_bulk_option(element){
            var selvalue = jQuery(element).val();
            jQuery('#rm_user_bulk_actions').val(selvalue);
        }
        function rm_change_user_roles(element){
            var selvalue = jQuery(element).val();
            jQuery('#rm_change_user_role').val(selvalue);
        }
        jQuery(function() {
            var start = moment(<?php if(!empty($start_date)){echo "'".esc_html($start_date)."'";}?>);
            var end = moment(<?php if(!empty($end_date)){echo "'".esc_html($end_date)."'";}?>);
            function cb(start, end) {
                jQuery('#rm_users_date').val(start.format('MM/DD/YYYY') + '-' + end.format('MM/DD/YYYY'));
            }

            jQuery('#rm_users_date').daterangepicker({
                startDate: start,
                endDate: end,
                maxDate: new Date(),
                ranges: {
                   'Today': [moment(), moment()],
                   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                   'This Month': [moment().startOf('month'), moment().endOf('month')],
                   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                locale: { cancelLabel: 'Clear' }  
            }, cb);
            jQuery('#rm_users_date').on('cancel.daterangepicker', function(ev, picker) {
                //do something, like clearing an input
                jQuery('#rm_users_date').val('');
                jQuery('.rm-user-clear-date').show();
              });
            jQuery('#rm_users_date').on('apply.daterangepicker', function(ev, picker) {
                jQuery(this).val(picker.startDate.format('MM/DD/YYYY') + '-' + picker.endDate.format('MM/DD/YYYY'));
                jQuery('.rm-user-clear-date').show();
            });  
            cb(start, end);
            <?php if(empty($rm_interval)){?>
                    jQuery('#rm_users_date').val('');
            <?php } ?>
        });
        
        function rm_clear_date_format(element){
            jQuery('#rm_users_date').val('');
            jQuery(element).hide();
        }
        function set_user_entry_depth(element) {
            var selectedVal = jQuery(element).find('option').filter(':selected').val();
            var postData = {'action' : 'rm_set_user_entry_depth', 'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>', 'value' : selectedVal};
            jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', postData, function(response) {
                if(response.success) {
                    jQuery('#current-page-selector').val(1);
                    //window.location = "<?php echo admin_url('admin.php?page=rm_user_manage');?>";
                    //location.reload();
                    jQuery('#rm_user_manager_sideform').submit();
                }
            });
        }

        jQuery('.rm-modal-close, .rm-modal-overlay, .rm-model-cancel').click(function () {
            jQuery(this).parents('#rm_user_delete_popup').hide();
            jQuery(this).parents('#rm_user_send_email_list_popup').hide();
        });
        
        function rm_user_search(){
            jQuery('#current-page-selector').val('1');
        }

        jQuery(document).on("keydown", "input", function(e) {
            if (e.which==13) e.preventDefault();
        });
     </script></pre>
