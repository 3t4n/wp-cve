<?php defined( 'ABSPATH' ) or die( __('No script kiddies please!', 'wp-user-merger') );
	
	//if(!is_admin()){ require_once ABSPATH . 'wp-admin/includes/user.php'; }
	
	if ( !current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'wp-user-merger' ) );
	}
	global $wpus_data, $wpus_pro, $wpus_premium_link, $wp_roles, $wpus_url, $wpsu_options;
		
	$users = get_users(array('number'=>1)); 
	$roles = $wp_roles->roles;
	//pree($_POST);
	$post_status = false;
    $merge_status = false;
    $wpsu_user_searchable = array_key_exists('user_searchable', $wpsu_options) && $wpsu_options['user_searchable'];
	$current_theme = str_replace(array(' ', '-'), '_', strtolower(get_option('current_theme')));


	if(isset($_POST['start_merge'])){
	//pree($_POST);
	
	if ( 
		! isset( $_POST['start_merge_field'] ) 
		|| ! wp_verify_nonce( $_POST['start_merge_field'], 'start_merge_action' ) 
	) {
	 
	   _e('Sorry, your nonce did not verify.', 'wp-user-merger');
	   exit;
	 
	} else {
	


		$delete_user_id = null;
		$reasign_user_id = null;
		$merge_status_remarks = null;
		$post_array = sanitize_wpus_data($_POST['posts']);
		$user_id = sanitize_wpus_data($_POST['ID']);
		$user_id = (is_numeric($user_id)?$user_id:0);		
		$user_login = sanitize_wpus_data($_POST['user_login']);
		$user_nicename = sanitize_wpus_data($_POST['user_nicename']);
		$user_email = sanitize_email($_POST['user_email']);
		$user_url = esc_url($_POST['user_url']);
		$display_name = sanitize_wpus_data($_POST['display_name']);
		$role = sanitize_wpus_data($_POST['roles']);
		$name_array = explode(" ",$display_name);
		$first_name = array_key_exists(0, $name_array) ? $name_array[0] : '';
		$last_name = array_key_exists(1, $name_array) ? $name_array[1] : '';
		$user_ids = sanitize_wpus_data($_POST['user_ids']);
		if($post_array == null){$post_array = array();};
		
		foreach($user_ids as $del_user){
			if($del_user != $user_id){
				$delete_user_id = $del_user;
			}
		}
		//wpus_pree($user_id);wpus_pree($user_ids);wpus_pree($delete_user_id);exit;
		do_action('wpus_before_delete_user', $delete_user_id, $user_id);


		if(!(in_array($user_id, $post_array))){
			add_action('delete_user', 'wpus_delete_user');
		}
		
		
		
		if(in_array($delete_user_id, $post_array)){
			$reasign_user_id = $user_id;
		}
		
	   
		//wpus_pree($user_ids);wpus_pree($reasign_user_id);exit;
	
		$userdata = array(
			'ID'                    => $user_id,    //(int) User ID. If supplied, the user will be updated.
			//'user_login'            => $user_login,   //(string) The user's login username.
			'user_nicename'         => $user_nicename,   //(string) The URL-friendly user name.
			'user_url'              => $user_url,   //(string) The user URL.
			'user_email'            => $user_email,   //(string) The user email address.
			'display_name'          => $display_name,   //(string) The user's display name. Default is the user's username.
			'nickname'              => $user_nicename,   //(string) The user's nickname. Default is the user's username.
			'first_name'            => $first_name,   //(string) The user's first name. For new users, will be used to build the first part of the user's display name if $display_name is not specified.
			'last_name'             => $last_name,   //(string) The user's last name. For new users, will be used to build the second part of the user's display name if $display_name is not specified.
			'role'                  => $role  //(string) User's role.          
		);
		
		//pree($delete_user_id);
		//pree($reasign_user_id);
	
		if(function_exists('wpus_save_merged_data')){
			wpus_save_merged_data($user_id, $delete_user_id, $post_array);
		}
		
		if($reasign_user_id != null){
	
			$order_ids_to_assign = wpus_get_order_ids_by_user($delete_user_id);			
			wpus_reassign_shop_orders($order_ids_to_assign, $reasign_user_id);
			wpus_reassign_order_billing_shipping($order_ids_to_assign, $reasign_user_id);
			wp_delete_user($delete_user_id, $reasign_user_id);
			$user_data = wp_update_user($userdata);
	
		}else {              
			 
	
			wp_delete_user($delete_user_id);
			$merged_array['post_deleted'] = true;
			$user_data =  wp_update_user($userdata);
	
		}

	

		if($user_data == $user_id && $reasign_user_id == null){
		$merge_status = true;
		$merge_status_remarks = __('Merge action was successful.', 'wp-user-merger');
	 
	} else if ($user_data == $user_id && ($reasign_user_id != null || in_array($user_id,$post_array))){
		$merge_status = true;
		$merge_status_remarks = __('User profiles are merged successfully.', 'wp-user-merger');
	}
	
		
		 
	//     $user_new_data = get_user_by('ID',$user_id);
	//    echo 'new data';
	//     pree($user_new_data);
	
	//  pree($userdata);
	}
	
	}
	
	if(isset($_POST['merge_user'])){
		if ( 
			! isset( $_POST['merge_user_field'] ) 
			|| ! wp_verify_nonce( $_POST['merge_user_field'], 'merge_user_action' ) 
		) {
		 
		   _e('Sorry, your nonce did not verify.', 'wp-user-merger');
		   exit;
		 
		} else {
		 
		   // process form data
		
			//$_POST = sanitize_wpus_data($_POST);

			$post_status = true;
			$first_user = get_user_by('ID',sanitize_wpus_data($_POST['first_user']));
			$second_user = get_user_by('ID',sanitize_wpus_data($_POST['second_user']));
			$first_user_meta = get_user_meta(sanitize_wpus_data($_POST['first_user']));
			$second_user_meta = get_user_meta(sanitize_wpus_data($_POST['second_user']));


		

		}
	
	}
	
	?>
	
	


	
	
	<div class="container-fluid wpus_wrapper_div wrap">

        <div class="pl-3 row mb-2 mt-4">
            <div class="icon32" id="icon-options-general"><br></div><h4><i class="fas fa-cogs"></i> &nbsp;<?php echo $wpus_data['Name']; ?> <?php echo '('.$wpus_data['Version'].($wpus_pro?') Pro':')'); ?> - <?php echo __('Settings', 'wp-user-merger'); ?></h4>
			<?php if(!$wpus_pro): ?>
                <a href="<?php echo esc_url($wpus_premium_link); ?>" target="_blank" class="btn btn-info btn-sm" style="position:absolute; right:15px; width:100px; color:#fff;"><?php echo __('Go Premium', 'wp-user-merger'); ?></a>
			<?php endif; ?>
        </div>


        <h2 class="nav-tab-wrapper">
            <a class="nav-tab"><i style="color:#A28906" class="fas fa-toggle-on"></i> <?php echo __('Optional', 'wp-user-merger'); ?></a>
            <a class="nav-tab nav-tab-active"><i style="color:#089F75" class="fas fa-tablets"></i> DB <?php echo __('User Merger', 'wp-user-merger'); ?></a>
            <a class="nav-tab"><i style="color:#2850C6" class="fas fa-undo"></i> <?php echo __('Restore', 'wp-user-merger'); ?></a>            
            <a class="nav-tab"><i style="color:#c85cb2" class="fas fa-code"></i> <?php echo __('Developers', 'wp-user-merger'); ?></a>
            
            <a class="nav-tab" style="float:right"><i style="color:#FF151C" class="fas fa-headset"></i> <?php echo __('Help', 'wp-user-merger'); ?></a>
        </h2>

		<?php

		if(isset($_GET['wpus_restore_user_id'])){
			?>
			 <div class="row mt-3">
            
            
            <div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong><?php _e('Success', 'wp-user-merger'); ?>!</strong> <?php _e('User Restored Successfully', 'wp-user-merger') ; ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?php _e('Close', 'wp-user-merger'); ?>"></button>
</div>

			</div>

			<?php
		}

		?>

		<div class="nav-tab-content hide wpsu_input_wrapper">
        
            

            <div class="wpsu_setting_alert alert d-none w-75 mt-4 mb-4">
                <strong><?php _e('Success!', 'wp-user-merger'); ?></strong> <?php _e('Settings are updated successfully.', 'wp-user-merger'); ?>
            </div>

            <form class="wp_user_merger_form">

                <?php wp_nonce_field( 'wpsu_nonce_action', 'wpsu_nonce' ); ?>

                <?php 

                           

                ?>

                <div class="row mt-3">
                    <div class="col-md-3">
                        <label for="wpsu_user_searchable">
                            <?php _e('Make User List Searchable (AJAX Based)', 'wp-user-merger'); ?>:
                        </label>                                                
                    </div>
                    <div class="col-md-4">                                						
                        <label class="wpsu_switch">
                            <input id="wpsu_user_searchable" type="checkbox" <?php checked($wpsu_user_searchable); ?>/>
                            <span class="wpsu_slider"></span>
                            <input type="hidden" name="wpsu_options[user_searchable]" value="" />
                        </label>                                                    
                    </div>
                </div>

            </form>
        
        </div>

        <div class="pl-3 nav-tab-content">





            <?php if($merge_status == true){ ?>
            <div class="row mt-3">
            
            
            
            <div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong><?php _e('Success', 'wp-user-merger'); ?>!</strong> <?php echo $merge_status_remarks; ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?php _e('Close', 'wp-user-merger'); ?>"></button>
</div>

            </div>
            <?php }else{ ?>
            <div class="row mt-3">
            
            
            
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong><?php _e('Info', 'wp-user-merger'); ?>!</strong> <?php _e('Your are going to merge', 'wp-user-merger'); ?> <?php if($post_status){ echo $first_user->data->display_name.' into '.$second_user->data->display_name; }else{ ?><?php _e('User1 into User2', 'wp-user-merger'); ?><?php } ?>.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?php _e('Close', 'wp-user-merger'); ?>"></button>
</div>

            </div>
            <?php } ?>

            <?php if(!$post_status && !$merge_status){ ?>
            <form method="post" class="wpus_merge_selection">
            <?php wp_nonce_field( 'merge_user_action', 'merge_user_field' ); ?>
            <div class="row">
                <div class="col-md-5 pl-0">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><?php _e('User 1', 'wp-user-merger'); ?></div>
                        <div class="panel-body">
                            <div class="form-group">

                                <label for="wpsu_user_1"><?php _e('Users list', 'wp-user-merger'); ?>:</label>
                                <select class="<?php echo !$wpsu_user_searchable ? 'form-control' : '' ; ?>" id="wpsu_user_1" name="first_user">
                                    <?php

                                    if(!$wpsu_user_searchable){

                                    
                                    foreach ($roles as $role_key => $role_value) {
                                        $user_by_role = get_users(array('role__in'=>$role_key, 'orderby'=>'email'));
                                        if(!empty($user_by_role)){
                                    ?>
                                    <option disabled class="disable"><?php echo $role_value['name'] ?></option>
                                    <?php
                                        foreach ($user_by_role as $user) {
                                            # code...
                                        ?>
                                    <option value="<?php echo $user->data->ID; ?>"><?php echo $user->data->user_email; ?> (<?php echo $user->data->display_name; ?> - ID: <?php echo $user->data->ID; ?>)
                                    </option>

                                    <?php } } }
                                    }
                                    ?>


                                </select>
                                
                                <div class="wpsu_user_1_assets wpsu_user_assets">
                                	<strong><?php _e('Database Entries List', 'wp-user-merger'); ?>: (<span></span>) <i class="fas fa-minus-square"></i><i class="fas fa-plus-square"></i></strong>
                                	<ul></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-offset-2 col-md-5">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><?php _e('User 2', 'wp-user-merger'); ?></div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="wpsu_user_2"><?php _e('Users list', 'wp-user-merger'); ?>:</label>
                                <select class="<?php echo !$wpsu_user_searchable ? 'form-control' : '' ; ?>" id="wpsu_user_2" name="second_user">
                                    <?php

                                    if(!$wpsu_user_searchable){
                                    
                                        foreach ($roles as $role_key => $role_value) {
                                            $user_by_role = get_users(array('role__in'=>$role_key, 'orderby'=>'email'));
                                            if(!empty($user_by_role)){
                                        ?>
                                        <option disabled class="disable"><?php echo $role_value['name'] ?></option>
                                        <?php
                                            foreach ($user_by_role as $user) {
                                                # code...
                                            ?>
                                        <option value="<?php echo $user->data->ID; ?>"><?php echo $user->data->user_email; ?> (<?php echo $user->data->display_name; ?> - ID: <?php echo $user->data->ID; ?>)
                                        </option>

                                        <?php } } }
                                    
                                    }

                                    ?>


                                </select>
                                <div class="wpsu_user_2_assets wpsu_user_assets">
                                	<strong><?php _e('Database Entries List', 'wp-user-merger'); ?>: (<span></span>) <i class="fas fa-minus-square"></i><i class="fas fa-plus-square"></i></strong>
                                	<ul></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">

                
                
                <div class="alert alert-warning alert-dismissible fade show wpsu_same_users mt-3" role="alert">
  <strong><?php _e('Warning', 'wp-user-merger'); ?>!</strong> <?php _e('Same user cannot be selected for the merge action.', 'wp-user-merger'); ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?php _e('Close', 'wp-user-merger'); ?>"></button>
</div>

            </div>

            <div class="row">
                <div class="col-md-2 pl-0 mt-3">
                    <button type="submit" id="merge_btn" class="btn btn-block btn-success btn-sm w-100" name="merge_user"><?php _e('Merge', 'wp-user-merger'); ?></button>
                </div>

            </div>
            </form>
            <?php } ?>


            <?php if($post_status == true){ ?>
            <hr>

            <div class="row">
            
            
            
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong><?php _e('Warning', 'wp-user-merger'); ?></strong> <?php _e('Are you sure, you want to merge', 'wp-user-merger'); ?> <?php echo $first_user->data->display_name ?> <?php _e('into', 'wp-user-merger'); ?> <?php echo $second_user->data->display_name ?>?
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?php _e('Close', 'wp-user-merger'); ?>"></button>
</div>

            </div>
            <form method="post" class="wpsu-form-check">
            <?php wp_nonce_field( 'start_merge_action', 'start_merge_field' ); ?>

            <?php if($wpus_pro): ?>
            <label class="switch mb-3">
              <input type="checkbox">
              <span class="slider round"></span>
            </label>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-info d-none">
                        <div class="panel-heading"><?php _e('User 1 Fields', 'wp-user-merger'); ?></div>
                        <div class="panel-body">

                            <div class="form-check">

                                <input type="radio" class="form-check-input" name="display_name"
                                        value="<?php echo $first_user->data->display_name ?>" required />
                                        <label class="form-check-label">
                                    <?php _e('Display Name', 'wp-user-merger'); ?>: <?php echo $first_user->data->display_name ?>
                                </label>
                            </div>


                            <div class="form-check">

                                <input type="radio" class="form-check-input check_id" name="ID"
                                        value="<?php echo $first_user->data->ID ?>" required />
                                <input type="hidden" name="user_ids[]" value="<?php echo $first_user->data->ID ?>">
                                <label class="form-check-label">
                                    <?php _e('User ID', 'wp-user-merger'); ?>: <?php echo $first_user->data->ID ?>
                                </label>
                            </div>
                            <div class="form-check">

                                <input type="radio" class="form-check-input" name="user_login"
                                        value="<?php echo $first_user->data->user_login ?> " required />
                                        <label class="form-check-label">
                                    <?php _e('Login', 'wp-user-merger'); ?>:	<?php echo $first_user->data->user_login ?>
                                </label>
                            </div>

                            <div class="form-check">

                                <input type="radio" class="form-check-input" name="user_nicename"
                                        value="<?php echo $first_user->data->user_nicename ?>" required />
                                        <label class="form-check-label">
                                    <?php _e('Profile Permalink', 'wp-user-merger'); ?>: <?php echo $first_user->data->user_nicename ?>
                                </label>
                            </div>

                            <div class="form-check">

                                <input type="radio" class="form-check-input" name="user_email"
                                        value="<?php echo $first_user->data->user_email ?>" required />
                                        <label class="form-check-label">
                                    <?php _e('Email Address', 'wp-user-merger'); ?>: <?php echo $first_user->data->user_email ?>
                                </label>
                            </div>

                            <div class="form-check">

                                <input type="radio" class="form-check-input" name="user_url"
                                        value="<?php echo $first_user->data->user_url ?>" required />
                                        <label class="form-check-label">
                                    URL: <?php echo $first_user->data->user_url ?>
                                </label>
                            </div>

                            <div class="form-check">

                                <input type="radio" class="form-check-input" name="user_status"
                                        value="<?php echo $first_user->data->user_status ?>" required />
                                        <label class="form-check-label">
                                    Status: <?php echo $first_user->data->user_status ?>
                                </label>
                            </div>

                            <div class="form-check">

                                <input type="radio" class="form-check-input" name="roles"
                                        value="<?php echo $first_user->roles[0] ?>" required />
                                        <label class="form-check-label">
                                    <?php _e('User Role', 'wp-user-merger'); ?>: <?php echo $first_user->roles[0] ?>
                                </label>
                            </div>

                            <hr>

                            <h5><?php _e('First User Meta', 'wp-user-merger'); ?></h5>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="<?php echo $first_user->data->user_url ?>" name="url[]" checked="checked" /> URL
                                </label>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="<?php echo $first_user->data->ID ?>" name="posts[]" checked="checked" /> <?php _e('Posts', 'wp-user-merger'); ?>
                                </label>
                            </div>



                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-info d-none">
                        <div class="panel-heading"><?php _e('User 2 Fields', 'wp-user-merger'); ?></div>
                        <div class="panel-body">
                            <div class="form-check">

                                <input type="radio" class="form-check-input" name="display_name"
                                        value="<?php echo $second_user->data->display_name ?>" checked="checked" />
                                        <label class="form-check-label">
                                     <?php _e('Display Name', 'wp-user-merger'); ?>: <?php echo $second_user->data->display_name ?>
                                </label>
                            </div>

                            <div class="form-check">

                                <input type="radio" class="form-check-input check_id" name="ID"
                                        value="<?php echo $second_user->data->ID ?>" checked="checked" />
                                        <input type="hidden" name="user_ids[]" value="<?php echo $second_user->data->ID ?>">
                                        <label class="form-check-label">
                                    <?php _e('User ID', 'wp-user-merger'); ?>: <?php echo $second_user->data->ID ?>

                                </label>
                            </div>
                            <div class="form-check">

                                <input type="radio" class="form-check-input" name="user_login"
                                        value="<?php echo $second_user->data->user_login ?>" checked="checked" />
                                        <label class="form-check-label">
                                    <?php _e('Login', 'wp-user-merger'); ?>: <?php echo $second_user->data->user_login ?>
                                </label>
                            </div>

                            <div class="form-check">

                                <input type="radio" class="form-check-input" name="user_nicename"
                                        value="<?php echo $second_user->data->user_nicename ?>" checked="checked" />
                                        <label class="form-check-label">
                                    <?php _e('Profile Permalink', 'wp-user-merger'); ?>: <?php echo $second_user->data->user_nicename ?>
                                </label>
                            </div>

                            <div class="form-check">

                                <input type="radio" class="form-check-input" name="user_email"
                                        value="<?php echo $second_user->data->user_email ?>" checked="checked" />
                                        <label class="form-check-label">
                                    <?php _e('Email Address', 'wp-user-merger'); ?>: <?php echo $second_user->data->user_email ?>
                                </label>
                            </div>

                            <div class="form-check">

                                <input type="radio" class="form-check-input" name="user_url"
                                        value="<?php echo $second_user->data->user_url ?>" checked="checked" />
                                        <label class="form-check-label">
                                    URL: <?php echo $second_user->data->user_url ?>
                                </label>
                            </div>

                            <div class="form-check">

                                <input type="radio" class="form-check-input" name="user_status"
                                        value="<?php echo $second_user->data->user_status ?>" checked="checked" />
                                        <label class="form-check-label">
                                    <?php _e('Status', 'wp-user-merger'); ?>: <?php echo $second_user->data->user_status ?>
                                </label>
                            </div>

                            <div class="form-check">

                                <input type="radio" class="form-check-input" name="roles"
                                        value="<?php echo isset($second_user->roles[0]) ? $second_user->roles[0] : 'none'  ?>" checked="checked" />
                                        <label class="form-check-label">
                                    <?php _e('User Role', 'wp-user-merger'); ?>: <?php echo isset($second_user->roles[0]) ? $second_user->roles[0] : 'None' ?>
                                </label>
                            </div>

                            <hr>

                            <h5><?php _e('Second User Meta', 'wp-user-merger'); ?></h5>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="<?php echo $second_user->data->user_url ?>" name="url[]" checked="checked" /> URL
                                </label>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="<?php echo $second_user->data->ID ?>" name="posts[]" checked="checked" /> <?php _e('Posts', 'wp-user-merger'); ?>
                                </label>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
            <div class="row">
                <div class="col-md-3 mt-4 pl-0">
                    <button type="submit" id="start_mrg_btn" class="btn btn-block btn-success" name="start_merge"><?php _e('Yes, please proceed and merge these two', 'wp-user-merger'); ?></button>
                </div>
            </div>

            </form>

            <?php } ?>
        </div>

        <div class=" nav-tab-content hide">

            <?php





            ?>

            <div class="row mt-3">
                <div class="col-md-12">


                    <?php if($wpus_pro){ ?>
                    <div class="h5"><i class="fas fa-users"></i> <?php _e('Merged Users List', 'wp-user-merger'); ?>:</div>
                    <?php } ?>

                    <ul class="list-group">
                        <?php

                            if(function_exists('wpum_merged_user_list_html')){

	                           echo  wpum_merged_user_list_html();

                            }else{

	                            
                                ?>
                                <li class=""><div class="list-group-item list-group-item-warning text-center"><?php _e('This is a premium feature.', 'wp-user-merger'); ?></div>
                                </li>
                                
                                <li><a href="<?php echo $wpus_url; ?>/img/restore-users.png" data-type="screenshot"><img src="<?php echo $wpus_url; ?>/img/restore-users.png" height="100px" /></a></li>
                     
                                <li><a href="<?php echo $wpus_url; ?>/img/selection-fields.png" data-type="screenshot"><img src="<?php echo $wpus_url; ?>/img/selection-fields.png" height="100px" /></a></li>

                                <?php												
	

                            }

                        ?>
                    </ul>


                </div>

            </div>

        </div>

		<div class="nav-tab-content hide wpsu-hooks">
        
            <div class="row mt-3">
              <div class="col-md-12">       
                	
                <div class="alert alert-success alert-dismissible fade show mt-3 mb-3" role="alert">
  <strong><?php _e('Hooks', 'wp-user-merger'); ?>!</strong> <?php _e('Useful action and filter hooks.', 'wp-user-merger'); ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?php _e('Close', 'wp-user-merger'); ?>"></button>
</div>

                  <ul>
                   	  
                      <li><i class="fas fa-link"></i><br />
<code style="font-weight:bold;">function <?php echo $current_theme; ?>_before_delete_user_callback($delete_id=0, $merged_id=0){<br />
                          <br />
}<br />
add_action('wpus_before_delete_user', '<?php echo $current_theme; ?>_before_delete_user_callback', 10, 2);</code></li>

					<li> <i class="fab fa-wordpress"></i> <code>delete_user</code> <a href="https://developer.wordpress.org/reference/hooks/delete_user/" target="_blank" title="<?php _e('Reference Check', 'wp-user-merger'); ?>"><i style="color:#099" class="fas fa-folder-open"></i></a></li>
                    
                    <li> <i class="fab fa-wordpress"></i> <code>deleted_user</code> <a href="https://developer.wordpress.org/reference/hooks/deleted_user/" target="_blank" title="<?php _e('Reference Check', 'wp-user-merger'); ?>"><i style="color:#099" class="fas fa-folder-open"></i></a></li>
                    
                    <li> <i class="fab fa-wordpress"></i> <code>delete_user_meta</code> <a href="https://developer.wordpress.org/reference/hooks/delete_user_meta/" target="_blank" title="<?php _e('Reference Check', 'wp-user-merger'); ?>"><i style="color:#099" class="fas fa-folder-open"></i></a> </li>
                    
                  </ul>
                    
                </div>
			</div>                

		</div>            

        <div class="nav-tab-content hide">
        
            <div class="row mt-3">
                <div class="col-md-12">                
        

 <ul class="position-relative m-0 p-0">
            <li><a class="btn btn-sm btn-info" href="https://wordpress.org/support/plugin/wp-user-merger/" target="_blank"><?php _e('Open a Ticket on Support Forums', 'wp-user-merger'); ?> &nbsp;<i class="fas fa-tag"></i></a></li>
            <li><a class="btn btn-sm btn-warning" href="http://demo.androidbubble.com/contact/" target="_blank"><?php _e('Contact Developer', 'wp-user-merger'); ?> &nbsp;<i class="fas fa-headset"></i></a></li>
            <li><a class="btn btn-sm btn-secondary" href="<?php echo $wpus_premium_link; ?>/?help" target="_blank"><?php _e('Need Urgent Help?', 'wp-user-merger'); ?> &nbsp;<i class="fas fa-phone"></i></i></a></li>
            <li><iframe width="560" height="315" src="https://www.youtube.com/embed/VyaF_20bg2U" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></li>
        </ul>
                            
                </div>
            </div>
        
        </div>

        


	</div>

    <script type="text/javascript" language="javascript">

        jQuery(document).ready(function($){


            <?php if(isset($_GET['t'])): $t = sanitize_wpus_data($_GET['t']); ?>

                $('.nav-tab-wrapper .nav-tab:nth-child(<?php echo $t+1; ?>)').click();

            <?php endif; ?>

        });

    </script>
    <style type="text/css">
		.woocommerce-message, .update-nag, #message, .notice.notice-error, .error.notice, div.notice, div.fs-notice, div.wrap > div.updated{ display:none !important; }
	</style>