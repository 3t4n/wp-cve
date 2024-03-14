<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
if ( ! class_exists( 'WPPM_Functions' ) ) :
    final class WPPM_Functions {
        // Array sanitization
        public static function sanitize_array($array) {
            if(!empty($array)){
                foreach ( $array as $key => $value ) {
                    if ( is_array( $value ) ) {
                        $value = $this->sanitize_array($value);
                    }
                    else {
                        $value = sanitize_text_field( $value );
                    }
                }
            }
            return $array;
        }

        public static function create_project($args){
            global $wpdb,$current_user;
            $values = array(
                'created_by'=>$current_user->ID,
                'project_name'=>$args['name'],
                'description'=>isset($args['wppm_proj_description']) ? $args['wppm_proj_description']:"",
                'start_date'=>isset($args['wppm_start_date']) ?$args['wppm_start_date']:"",
                'end_date'=>isset($args['wppm_end_date'])? $args['wppm_end_date']:"",
                'status'=>isset($args['status'])? $args['status']:"1",
                'cat_id'=>isset($args['wppm_create_project_category']) ? $args['wppm_create_project_category']:"",
                'users'=>isset($args['user_names']) ? implode(",",$args['user_names']):"",
                'date_created'=>isset($args['date_created'])? $args['date_created']:date("Y-m-d h:i:sa"),
            );
            $wpdb->insert($wpdb->prefix .'wppm_project', $values);
            $project_id = $wpdb->insert_id;
            return $project_id;
        }

        public static function create_task($args){
            global $wpdb,$current_user,$wppmfunction;
            $values = array(
                'created_by'=>(isset($args['created_by'])) ? $args['created_by']: $current_user->ID,
                'task_name'=>$args['name'],
                'description'=>(isset($args['wppm_task_description']))?$args['wppm_task_description']:"",
                'project'=>(isset($args['wppm_task_project']))?$args['wppm_task_project']:"",
                'start_date'=>(isset($args['wppm_task_start_date']))?$args['wppm_task_start_date']:"",
                'end_date'=>(isset($args['wppm_task_end_date']))?$args['wppm_task_end_date']:"",
                'status'=>(isset($args['status']))?$args['status']:"1",
                'priority'=>(isset($args['wppm_create_task_priority']))?$args['wppm_create_task_priority']:"",
                'users'=>(!empty($args['user_names']))?implode(",",$args['user_names']):"",
                'date_created'=>isset($args['date_created'])? $args['date_created']:date("Y-m-d h:i:sa"),
                'task_auth_code'=> $wppmfunction->getRandomString(10),
                'active'=>1
            );
            $wpdb->insert($wpdb->prefix .'wppm_task', $values);
            $task_id = $wpdb->insert_id;
            return $task_id;
        }

        public static function create_checklist($args){
            global $wpdb,$current_user,$wppmfunction;
            $values = array(
                'task_id'=>$args['task_id'],
                'checklist_name' => $args['checklist_name'],
                'created_by'=> $args['created_by']
            );
            $wpdb->insert($wpdb->prefix .'wppm_checklist', $values);
            $checklist_id = $wpdb->insert_id;
            return $checklist_id;
        }

        public static function create_checklist_item($args){
            global $wpdb,$current_user,$wppmfunction;
            $chk_items_values = array(
                'checklist_id' => $args['checklist_id'],
                'item_name' => $args['item_name'],
                'checked'=>$args['checked'],
                'members'=>$args['members'],
                'due_date'=> $args['due_date']
            );
            $wpdb->insert($wpdb->prefix .'wppm_checklist_items', $chk_items_values);
            $checklist_item_id = $wpdb->insert_id;
            return $checklist_item_id;
        }

        public static function wppm_submit_task_comment($args){
            global $wpdb;
            $wpdb->insert($wpdb->prefix.'wppm_task_comment',$args);
            $comment_id = $wpdb->insert_id;
            return $comment_id;
        }

        // Random string
        public static function getRandomString($length = 8) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $string = '';
            for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
            }
            return $string;
        }

        public static function wppm_get_auth_code($id){
            global $wpdb;
            $auth_code = $wpdb->get_var( "SELECT task_auth_code FROM {$wpdb->prefix}wppm_task where id=$id" );
            $auth_code = isset($auth_code) ? $auth_code : "";
            return $auth_code;
        }

        public static function get_task_fields($task_id,$select_field){
            global $wpdb;
            $task_field_value = '';
            if (apply_filters('wppm_get_select_field',true,$task_id,$select_field)) {
              $get_task_field_value = $wpdb->get_var(" SELECT $select_field FROM {$wpdb->prefix}wppm_task WHERE id='$task_id' ");
              $task_field_value = $get_task_field_value ? $get_task_field_value : '';
            }
            return stripslashes($task_field_value);
        }

        public static function get_project_fields($proj_id,$select_field){
            global $wpdb;
            $project_field_value = '';
            if (apply_filters('wppm_get_select_proj_field',true,$proj_id,$select_field)) {
              $get_project_field_value = $wpdb->get_var(" SELECT $select_field FROM {$wpdb->prefix}wppm_project WHERE id='$proj_id' ");
              $project_field_value = $get_project_field_value ? $get_project_field_value : '';
            }
            return stripslashes($project_field_value);
        }

        public function get_task($task_id){
            global $wpdb;
            $task_data = array();
            $task = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_task WHERE id='$task_id' ");
            if( $task ){
                $task_data = json_decode(json_encode($task), true);
            }
            return $task_data;
        }

        public function get_checklist($task_id){
            global $wpdb;
            $checklist_data = array();
            $checklist = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wppm_checklist WHERE task_id='$task_id' ");
            if( $checklist ){
                $checklist_data = json_decode(json_encode($checklist), true);
            }
            return $checklist_data;
        }

        public function get_checklist_items($checklist_id){
            global $wpdb;
            $checklist_items_data = array();
            $checklist_items = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wppm_checklist_items WHERE checklist_id='$checklist_id' ");
            if( $checklist_items ){
                $checklist_items_data = json_decode(json_encode($checklist_items), true);
            }
            return $checklist_items_data;
        }

        public function get_project($project_id){
            global $wpdb;
            $project_data = array();
            $project = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_project WHERE id='$project_id' ");
            if( $project ){
                $project_data = json_decode(json_encode($project), true);
            }
            return $project_data;
        }

        public function get_attachment($attachment_id){
            global $wpdb;
            $attachment_data = array();
            $attachment = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_attachments WHERE id='$attachment_id' ");
            if( $attachment ){
                $attachment_data = json_decode(json_encode($attachment), true);
            }
            return $attachment_data;
        }

        public function has_permission($permission, $task_id){
            global $current_user,$wppmfunction,$wpdb;
            $wppm_edit_tasks_permission = get_option('wppm_default_edit_tasks_permission');
            if(empty($comment_id)){
                $comment_id = 0;
            }
            $task_data = $wppmfunction->get_task($task_id);
            $task_comment = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_task_comment WHERE task_id = $task_id AND id = $comment_id");
            
            if(!empty($task_data['project'])){
                $project_id = $task_data['project'];
                $project_user_role = $wpdb->get_var("SELECT role_id FROM {$wpdb->prefix}wppm_project_users WHERE proj_id = $project_id AND user_id = $current_user->ID");
                $project_users = $wpdb->get_var("SELECT users FROM {$wpdb->prefix}wppm_project WHERE id = $project_id");
                $project_users_arr = explode(',',(string)$project_users);
            }
            $response = false;
            $flag = false;
            if(!empty($task_data)){
                $co_worker = $task_data['users'];
                $co_worker_array = explode(",",(string)$co_worker);
            }
            if((!empty($project_user_role)) && ($project_user_role == 1) && in_array($current_user->ID,$project_users_arr)){
                $flag= true;
            }
            if(!empty($project_id)){
                $public_proj_meta = $wpdb->get_var( "SELECT meta_value FROM {$wpdb->prefix}wppm_project_meta where project_id= $project_id AND meta_key='public_project'");
            }
            switch ($permission) {
                case 'change_status':
                case 'view_task':
                    ((!empty($co_worker_array)) && (in_array($current_user->ID,$co_worker_array )) )|| ($current_user->ID == $task_data['created_by']) ||  $public_proj_meta==1 || ($flag==true) ? $response = true: $response = false;
                    break;
                case 'change_task_details':
                    (($flag==true) || ($current_user->ID == $task_data['created_by']) || $wppm_edit_tasks_permission == 1) ? $response = true: $response = false;
                    break;
                case 'delete_task':
                case 'clone_task':
                    (($flag==true) || ($current_user->ID == $task_data['created_by'])) ? $response = true: $response = false;
                    break;
                case 'assign_task_users':
                case 'change_raised_by':
                    (($flag==true) ? $response = true: $response = false);
                    break;
            }
            return apply_filters( 'wppm_has_permission', $response, $task_id, $permission );
        }

        public function has_comment_permission($permission, $task_id,$comment_id){
            global $current_user,$wppmfunction,$wpdb;
            if(empty($comment_id)){
                $comment_id = 0;
            }
            $task_data = $wppmfunction->get_task($task_id);
            $project_user = array();
            $task_comment = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_task_comment WHERE task_id = $task_id AND id = $comment_id");
            
            if(isset($task_data['project'])){
                $project_id = $task_data['project'];
                $project_user = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_project_users WHERE proj_id = $project_id AND user_id = $current_user->ID");
                $project_users = $wpdb->get_var("SELECT users FROM {$wpdb->prefix}wppm_project WHERE id = $project_id");
                $project_users_arr = explode(',',(string)$project_users);
            }
            $response = false;
            $flag = false;
            if(!empty($task_data)){
                $co_worker = $task_data['users'];
                $co_worker_array = explode(",",(string)$co_worker);
            }
            if((!empty($project_user)) && ($project_user->role_id == 1) && in_array($current_user->ID,$project_users_arr)){
                $flag= true;
            }
            switch ($permission) {
                case 'delete_task_thread':
                case 'edit_task_comment':
                (($flag==true) || ($current_user->ID == $task_comment->created_by)) ? $response = true: $response = false;
                break;
            }
            return apply_filters( 'wppm_has_comment_permission', $response, $task_id, $comment_id, $permission );
        }

        public function has_project_permission($permission,$project_id){
            global $current_user,$wppmfunction,$wpdb ;
            $project_data = $wppmfunction->get_project($project_id);
            $project_user = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_project_users WHERE proj_id = $project_id AND user_id = $current_user->ID");
            $project_users = $wpdb->get_var("SELECT users FROM {$wpdb->prefix}wppm_project WHERE id = $project_id");
            $project_users_arr = explode(',',(string)$project_users);
            $user = wp_get_current_user();
            if(!empty($project_data)){
                $co_worker = $project_data['users'];
                $co_worker_array = explode(",",(string)$co_worker);
            }
            switch ($permission) {
                case 'view_project':
                ((!empty($co_worker_array)) && (in_array($current_user->ID,$co_worker_array )) )? $response = true: $response = false;
                  break;
                case 'change_project_status':
                case 'assign_project_users':
                case 'change_project_raised_by':
                case 'change_project_details':
                case 'delete_project':
                    $flag = false;
                    if( (!empty($project_user)) && ($project_user->role_id == 1) && in_array($current_user->ID,$project_users_arr)){
                        $flag = true;
                    }
                    ((!empty($co_worker_array)) && (in_array($current_user->ID,$co_worker_array) && $flag==true)) ? $response = true: $response = false;
                    break;
            }
            return apply_filters( 'wppm_has_project_permission', $response, $project_id, $permission );
        }

        public function change_status($task_id, $status_id){
            global $wpdb,$wppmfunction;
            $task_data = $wppmfunction->get_task($task_id);
            $prev_status = $task_data['status'];
            $values=array(
                'status'=>$status_id
            );
            $wpdb->update($wpdb->prefix.'wppm_task', $values, array('id'=>$task_id));
            $old_task_status_meta = $wppmfunction->get_task_meta($task_id,'old_task_status',true);
            if(!empty($old_task_status_meta)){
                $wppmfunction->delete_task_meta($task_id,'old_task_status');
            }
            $wppmfunction->add_task_meta($task_id,'old_task_status',$prev_status);
            
            do_action('wppm_set_change_task_status', $task_id, $status_id, $prev_status);
        }

        public function change_raised_by($task_id, $user_id){
            global $wpdb;
            $values=array(
                'created_by'=>$user_id
            );
            $wpdb->update($wpdb->prefix.'wppm_task', $values, array('id'=>$task_id));
        }

        public function change_task_label($task_id, $task_label){
            global $wpdb;
            $values=array(
                'task_name'=>stripslashes($task_label)
            );
            $wpdb->update($wpdb->prefix.'wppm_task', $values, array('id'=>$task_id));
        }

        public function change_task_project_label($task_id, $project_label){
            global $wpdb;
            $values=array(
                'project'=>$project_label
            );
            $wpdb->update($wpdb->prefix.'wppm_task', $values, array('id'=>$task_id));
        }

        public function change_start_date($task_id, $task_start_date){
            global $wpdb;
            $values=array(
                'start_date'=>$task_start_date
            );
            $wpdb->update($wpdb->prefix.'wppm_task', $values, array('id'=>$task_id));
        }

        public function change_end_date($task_id, $task_end_date){
            global $wpdb;
            $values=array(
                'end_date'=>$task_end_date
            );
            $wpdb->update($wpdb->prefix.'wppm_task', $values, array('id'=>$task_id));
        }

        public function change_priority($task_id, $task_priority){
            global $wpdb;
            $values=array(
                'priority'=>$task_priority
            );
            $wpdb->update($wpdb->prefix.'wppm_task', $values, array('id'=>$task_id));
        }

        public function change_description($task_id, $task_description){
            global $wpdb;
            $values=array(
                'description'=>$task_description
            );
            $wpdb->update($wpdb->prefix.'wppm_task', $values, array('id'=>$task_id));
        }

        public function change_project($task_id,$proj_id){
            global $wpdb,$wppmfunction;
            $task_data = $wppmfunction->get_task($task_id);
            $old_proj = $task_data['project'];
            $project_data = $wppmfunction->get_project($proj_id);
            $task_assign_users = $task_data['users'];
            $tusers_array = array();
            if(!empty($task_data['users'])){
                $task_users_array = explode(",",(string)$task_assign_users);
            }
            if(!empty($project_data['users'])){
                $project_users = explode(",",(string)$project_data['users']);
            }
            if(!empty($project_users)){
                if(!empty($task_users_array)){
                    foreach($task_users_array as $tuser){
                        if(!in_array($tuser,$project_users)){
                            $wpdb->delete( $wpdb->prefix.'wppm_project_users', array( 'proj_id' => $proj_id,'user_id'=>$tuser) );
                        }
                        elseif(in_array($tuser,$project_users)){
                            $tusers_array[] = $tuser; 
                            $sql = 'SELECT role_id FROM ' . $wpdb->prefix . 'wppm_project_users WHERE proj_id = ' . $old_proj .' AND user_id = '.$tuser;
                            $old_proj_user_role =  $wpdb->get_var( $sql );
                            $sql = 'SELECT role_id FROM ' . $wpdb->prefix . 'wppm_project_users WHERE proj_id = ' . $proj_id .' AND user_id = '.$tuser;
                            $proj_users_role =  $wpdb->get_var( $sql );
                            if(!empty( $old_proj_user_role) && $old_proj_user_role!=$proj_users_role){
                                $value=array(
                                    'role_id'=>$proj_users_role
                                );
                                $wpdb->update($wpdb->prefix.'wppm_project_users', $value, array('proj_id'=>$proj_id, 'user_id'=>$tuser));
                            }
                        }
                    }
                }  
            }
            $tusers = implode(",",$tusers_array);
            $values=array(
                'project'=>$proj_id,
                'users'=>$tusers
            );
            $wpdb->update($wpdb->prefix.'wppm_task', $values, array('id'=>$task_id));
        }

        public function get_task_comment($comment_id){
            global $wpdb;
            $task_comment_data = array();
            $task_comment = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_task_comment WHERE id=$comment_id ");
            if( $task_comment ){
                $task_comment_data = json_decode(json_encode($task_comment), true);
            }
            return $task_comment_data;
        }

        public function change_task_comment($comment_id,$comment_body){
            global $wpdb;
            $values=array(
                'body'=>$comment_body
            );
            $wpdb->update($wpdb->prefix.'wppm_task_comment', $values, array('id'=>$comment_id));
        }
        public function change_project_label($project_id,$project_label){
            global $wpdb;
            $values=array(
                'project_name'=>$project_label
            );
            $wpdb->update($wpdb->prefix.'wppm_project', $values, array('id'=>$project_id));
        }

        public function change_project_start_date($project_id, $project_start_date){
            global $wpdb;
            $values=array(
                'start_date'=>$project_start_date
            );
            $wpdb->update($wpdb->prefix.'wppm_project', $values, array('id'=>$project_id));
        }

        public function change_project_end_date($project_id, $project_end_date){
            global $wpdb;
            $values=array(
                'end_date'=>$project_end_date
            );
            $wpdb->update($wpdb->prefix.'wppm_project', $values, array('id'=>$project_id));
        }

        public function change_category($project_id, $project_category){
            global $wpdb;
            $values=array(
                'cat_id'=>$project_category
            );
            $wpdb->update($wpdb->prefix.'wppm_project', $values, array('id'=>$project_id));
        }

        public function change_project_description($project_id, $project_description){
            global $wpdb;
            $values=array(
                'description'=>$project_description
            );
            $wpdb->update($wpdb->prefix.'wppm_project', $values, array('id'=>$project_id));
        }

        public function change_project_status($project_id,$status_id){
            global $wpdb,$wppmfunction;
            $proj_data = $wppmfunction->get_project($project_id);
            $prev_status = $proj_data['status'];
            $values=array(
                'status'=>$status_id
            );
            $wpdb->update($wpdb->prefix.'wppm_project', $values, array('id'=>$project_id));
            $old_project_status_meta = $wppmfunction->get_project_meta($project_id,'old_project_status',true);
            if(!empty($old_project_status_meta)){
                $wppmfunction->delete_project_meta($project_id,'old_project_status');
            }
            $wppmfunction->add_project_meta($project_id,'old_project_status',$prev_status);
            do_action('wppm_set_change_project_status', $project_id, $status_id, $prev_status);
        }

        public function change_project_raised_by($project_id, $user_id){
            global $wpdb;
            $values=array(
                'created_by'=>$user_id
            );
            $wpdb->update($wpdb->prefix.'wppm_project', $values, array('id'=>$project_id));
        }

        // Email Notification types
        public function get_email_notification_types(){
            $notification_types = array(
            'new_project'=>__('New Project','taskbuilder'),
            'new_task'=>__('New Task','taskbuilder'),
            'change_project_status'       => __('Change Project Status','taskbuilder'),
            'change_task_status'    => __('Change Task Status','taskbuilder'),
            'change_project_assign_users' => __('Change Project Assign Users','taskbuilder'),
            'change_task_assign_users'    => __('Change Task Assign Users','taskbuilder'),
            'new_discussion'     => __('New Comment','taskbuilder'),
            );
            return apply_filters('wppm_en_types',$notification_types);
        }

        public function check_rtl(){
            $rtl_locale = array('ar','ary','ckb','haz','he_IL','fa_IR','ps','ur','ug_CN');
            if(in_array(get_locale(),$rtl_locale)){
              return 'rtl';
            }else{
              return 'ltr';
            } 
        }

        public function add_project_meta($proj_id,$meta_key,$meta_value){
            global $wpdb;
            $wpdb->insert( 
              $wpdb->prefix . 'wppm_project_meta', 
              array(
                'project_id' => $proj_id,
                'meta_key' => $meta_key,
                'meta_value' =>$meta_value
            ));
        }

        public function add_task_meta($task_id,$meta_key,$meta_value){
            global $wpdb;
            $wpdb->insert( 
              $wpdb->prefix . 'wppm_task_meta', 
              array(
                'task_id' => $task_id,
                'meta_key' => $meta_key,
                'meta_value' =>$meta_value
            ));
        }

        public function get_project_meta($project_id,$meta_key,$flag = false){
            global $wpdb,$wppmfunction;
            if($flag){
                $get_meta = $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}wppm_project_meta WHERE project_id = '$project_id' AND meta_key = '$meta_key' ");
                $project_meta = isset($get_meta) ? stripslashes($get_meta) : '';
                
            } else {
                $project_meta = array();
                $results = $wpdb->get_results("SELECT meta_value FROM {$wpdb->prefix}wppm_project_meta WHERE project_id = '$project_id' AND meta_key = '$meta_key'");
                if( (!empty($results)) ){
                    foreach ($results as $result) {
                        if(!empty($result)){
                            $project_meta[]= stripslashes($result->meta_value);
                        }
                    }
                }
            }
            return $project_meta;
        }

        public function get_task_meta($task_id,$meta_key,$flag = false){
            global $wpdb,$wppmfunction;
            if($flag){
                $get_meta = $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}wppm_task_meta WHERE task_id = '$task_id' AND meta_key = '$meta_key' ");
                $task_meta = isset($get_meta) ? stripslashes($get_meta) : '';
                
            } else {
                $task_meta = array();
                $results = $wpdb->get_results("SELECT meta_value FROM {$wpdb->prefix}wppm_task_meta WHERE task_id = '$task_id' AND meta_key = '$meta_key'");
                if( (!empty($results)) ){
                    foreach ($results as $result) {
                        if(!empty($result)){
                            $task_meta[]= stripslashes($result->meta_value);
                        }
                    }
                }
            }
            return $task_meta;
        }

        public function get_previously_assigned_users($project_id){
            global $wpdb,$wppmfunction;
            $prev_assigned_users = $wppmfunction->get_project_meta($project_id,'prev_assigned_users');
            $user_emails = array();
            if(!empty($prev_assigned_users)){
                foreach ($prev_assigned_users as $user) {
                    $userdata = get_userdata($user);
                    $user_emails[] = $userdata->user_email;
                    
                }
            }
            return apply_filters( 'wppm_get_prev_assigned_users_emails', $user_emails ,$project_id);
        }

        public function get_previously_assigned_task_users($task_id){
            global $wpdb,$wppmfunction;
            $prev_assigned_task_users = $wppmfunction->get_task_meta($task_id,'prev_assigned_task_users');
            $user_emails = array();
            if(!empty($prev_assigned_task_users)){
                foreach ($prev_assigned_task_users as $user) {
                    if(!empty($user)){
                        $userdata = get_userdata($user);
                        $user_emails[] = $userdata->user_email;
                    }
                }
            }
            return apply_filters( 'wppm_get_prev_assigned_task_users_emails', $user_emails ,$task_id);
        }
        /**
         * Update project meta for project
         */
        function update_project_meta($project_id ,$meta_key ,$meta_value){
            global $wpdb;
            $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wppm_project_meta WHERE project_id = '$project_id' AND meta_key = $meta_key");
            $wpdb->update($wpdb->prefix.'wppm_project_meta', $meta_value, array('project_id'=>$project_id,'meta_key' => $meta_key));
        }
        
        /**
        * Update task meta for task
        */
        function update_task_meta($task_id ,$meta_key ,$meta_value){
            global $wpdb;
            $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wppm_task_meta WHERE task_id = '$task_id' AND meta_key = $meta_key");
            $wpdb->update($wpdb->prefix.'wppm_task_meta', $meta_value, array('task_id'=>$task_id,'meta_key' => $meta_key));
        }

        function delete_project_meta($project_id  ,$meta_key){
            global $wpdb;
            $wpdb->delete( $wpdb->prefix.'wppm_project_meta', array( 'project_id' => $project_id,'meta_key'=>$meta_key) );
        }

        function delete_task_meta($task_id ,$meta_key){
            global $wpdb;
            $wpdb->delete( $wpdb->prefix.'wppm_task_meta', array( 'task_id' => $task_id,'meta_key'=>$meta_key) );
        }

        public function replace_macro($str,$project_id){
            include WPPM_ABSPATH . 'includes/replace_macro.php';
            return $str;
        }

        public function replace_task_macro($str,$task_id){
            include WPPM_ABSPATH . 'includes/replace_task_macro.php';
            return $str;
        }

        public function get_old_project_status_name($id){
            global $wppmfunction, $wpdb;
            $old_project_status = $wppmfunction->get_project_meta($id,'old_project_status',true);
            $status_name = $wpdb->get_var("SELECT name FROM {$wpdb->prefix}wppm_project_statuses WHERE id ='$old_project_status'");
            return $status_name;
        }

        public function get_new_project_status_name($status_id){
            global $wpdb;
            $status_name = $wpdb->get_var("SELECT name FROM {$wpdb->prefix}wppm_project_statuses WHERE id ='$status_id'");
            return $status_name;
        }

        public function get_old_task_status_name($task_id){
            global $wppmfunction, $wpdb;
            $old_task_status = $wppmfunction->get_task_meta($task_id,'old_task_status',true);
            $status_name = $wpdb->get_var("SELECT name FROM {$wpdb->prefix}wppm_task_statuses WHERE id ='$old_task_status'");
            return $status_name;
        }

        public function get_new_task_status_name($status_id){
            global $wpdb;
            $status_name = $wpdb->get_var("SELECT name FROM {$wpdb->prefix}wppm_task_statuses WHERE id ='$status_id'");
            return $status_name;
        }

        public function get_project_category_name($cat_id){
            global $wpdb;
            $cat_name = $wpdb->get_var("SELECT name FROM {$wpdb->prefix}wppm_project_categories WHERE id ='$cat_id'");
            return $cat_name;
        }

        public function get_project_assigned_users_names($project_id){
            global $wppmfunction;
            $wppm_project_data = $wppmfunction->get_project($project_id);
            if(!empty($wppm_project_data['users'])){
                $wppm_project_assign_users_array = explode(",", $wppm_project_data['users']);
            }else{
                $wppm_project_assign_users_array = array();
            }
            $users_names = array();
            if(!empty($wppm_project_assign_users_array)){
                foreach ($wppm_project_assign_users_array as $user_id) {
                    if(!empty($user_id)){
                        $userdata = get_userdata( $user_id );
                        if(!empty($userdata)){
                            $users_names[] = $userdata->display_name;
                        }
                    }
                }
            }
            return implode(', ', $users_names);
        }

        public function get_task_assigned_users_names($task_id){
            global $wppmfunction;
            $wppm_task_data = $wppmfunction->get_task($task_id);
            $wppm_task_assign_users_array = explode(",", (string)$wppm_task_data['users']);
            $users_names = array();
            if(!empty($wppm_task_assign_users_array)){
                foreach ($wppm_task_assign_users_array as $user_id) {
                    if(!empty($user_id)){
                        $userdata = get_userdata( $user_id );
                        if(!empty($userdata)){
                            $users_names[] = $userdata->display_name;
                        }
                    }
                }
            }
            return implode(', ', $users_names);
        }

        public function get_project_previously_assigned_users_names($project_id){
            global $wppmfunction;
            $wppm_project_assign_users = $wppmfunction->get_project_meta($project_id,'prev_assigned_users');            
            $users_names = array();
            if(!empty($wppm_project_assign_users)){
                foreach ($wppm_project_assign_users as $user) {
                    if(!empty($user)){
                        $userdata = get_userdata( $user );
                        $users_names[] = $userdata->display_name; 
                    }  
                }
            }
            return implode(', ', $users_names);
        }

        public function get_task_previously_assigned_users_names($task_id){
            global $wppmfunction;
            $wppm_task_assign_users = $wppmfunction->get_task_meta($task_id,'prev_assigned_task_users');            
            $users_names = array();
            if(!empty($wppm_task_assign_users)){
                foreach ($wppm_task_assign_users as $user) {
                    if(!empty($user)){
                        $userdata = get_userdata( $user );
                        $users_names[] = $userdata->display_name;
                    } 
                }
            }
            return implode(', ', $users_names);
        }

        public function get_task_priority_name($priority){
            global $wpdb;
            $priority_name = $wpdb->get_var("SELECT name FROM {$wpdb->prefix}wppm_task_priorities WHERE id ='$priority'");
            return $priority_name;
        }

        public function get_last_comment_user_name($task_id){
            global $wpdb;
            $task_comment_creator = $wpdb->get_var("SELECT created_by FROM {$wpdb->prefix}wppm_task_comment WHERE (id=(select MAX(id) from {$wpdb->prefix}wppm_task_comment) AND task_id = $task_id)");
            if(!empty($task_comment_creator)){
                $user = get_userdata( $task_comment_creator );
                return $user->display_name;
            }
        }

        public function get_last_comment_body($task_id){
            global $wpdb;
            $task_comment = $wpdb->get_var("SELECT body FROM {$wpdb->prefix}wppm_task_comment WHERE (id=(select MAX(id) from {$wpdb->prefix}wppm_task_comment) AND task_id = $task_id)");
            return $task_comment;
        }
        
        public function create_duplicate_task($ptask_id, $project_id){
            include WPPM_ABSPATH . 'includes/admin/tasks/open_task/wppm_set_clone_task.php';
        }
    }


endif;
$GLOBALS['wppmfunction'] =  new WPPM_Functions();