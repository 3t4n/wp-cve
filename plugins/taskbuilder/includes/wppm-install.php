<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WPPM_Install' ) ) :

final class WPPM_Install {
	public function __construct() {
		$this->check_version();
	}
	/**
     * Check version of Taskbuilder
     */
    public function check_version(){
		$installed_version = get_option( 'wppm_version' );
        if( $installed_version != WPPM_VERSION ){
          	$this->create_db_tables();
			add_action( 'init', array($this,'upgrade'), 101 );
        }
	}
	/**** Create mysql tables****/
	public function create_db_tables() {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wppm_project (
			id integer NOT NULL AUTO_INCREMENT,
			created_by integer,
			project_name VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
			description LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
			start_date datetime,
			end_date datetime,
			status integer,
			cat_id integer,
			users VARCHAR( 200 ) NULL DEFAULT '0',
			date_created datetime,
			PRIMARY KEY  (id)
		);";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wppm_project_meta (
			id integer NOT NULL AUTO_INCREMENT,
			project_id integer,
			meta_key LONGTEXT NULL DEFAULT NULL,
			meta_value LONGTEXT NULL DEFAULT NULL,
			PRIMARY KEY  (id)
		);";
		dbDelta( $sql );
			
		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wppm_project_categories (
			id integer NOT NULL AUTO_INCREMENT,
			name TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
			load_order int(11) NOT NULL DEFAULT '1',
			PRIMARY KEY  (id)
		);";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wppm_project_statuses (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			name varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
			color varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
			bg_color varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
			load_order int(11) NOT NULL DEFAULT '1',
			PRIMARY KEY  (id)
		);";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wppm_task_statuses (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			name varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
			color varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
			bg_color varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
			load_order int(11) NOT NULL DEFAULT '1',
			PRIMARY KEY  (id)
		);";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wppm_task_priorities (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			name varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
			color varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
			bg_color varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
			load_order int(11) NOT NULL DEFAULT '1',
			PRIMARY KEY  (id)
		);";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wppm_task (
			id integer NOT NULL AUTO_INCREMENT,
			created_by integer,
			task_name VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
			description LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
			project integer,
			start_date datetime,
			end_date datetime,
			status integer,
			priority integer,
			users VARCHAR( 200 ) NULL DEFAULT '0',
			date_created datetime,
			task_auth_code LONGTEXT NULL DEFAULT NULL,
			active int(11) DEFAULT 1,
			PRIMARY KEY  (id)
		);";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wppm_task_meta (
			id integer NOT NULL AUTO_INCREMENT,
			task_id integer,
			meta_key LONGTEXT NULL DEFAULT NULL,
			meta_value LONGTEXT NULL DEFAULT NULL,
			PRIMARY KEY  (id)
		);";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wppm_checklist (
			id integer NOT NULL AUTO_INCREMENT,
			task_id integer,
			checklist_name VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
			created_by integer,
			PRIMARY KEY  (id)
		);";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wppm_checklist_items (
			id integer NOT NULL AUTO_INCREMENT,
			checklist_id integer,
			item_name VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
			checked integer,
			members VARCHAR( 200 ) NULL DEFAULT '0',
			due_date datetime,
			PRIMARY KEY  (id)
		);";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wppm_task_comment (
			id integer NOT NULL AUTO_INCREMENT,
			task_id integer,
			body LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
			attachment_ids TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
			create_time datetime,
			created_by integer,
			PRIMARY KEY  (id)
		);";
		dbDelta( $sql );
		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wppm_attachments (
			id BIGINT NOT NULL AUTO_INCREMENT,
			name VARCHAR(200) NOT NULL,
			file_name VARCHAR(200) NOT NULL,
			file_path VARCHAR(500) NOT NULL,
			is_image INT(1) NOT NULL DEFAULT 0,
			is_active INT(1) NOT NULL DEFAULT 0,
			is_uploaded INT(1) NOT NULL DEFAULT 0,
			date_created DATETIME NOT NULL,
			PRIMARY KEY (id)
		);";
		dbDelta( $sql );
		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wppm_project_users (
			id BIGINT NOT NULL AUTO_INCREMENT,
			proj_id integer,
			user_id integer,
			role_id integer,
			assigned_by integer,
			PRIMARY KEY (id)
		);";
		dbDelta( $sql );
	}

	public function upgrade(){
		global $wpdb;
		$installed_version = get_option( 'wppm_version' );
		$installed_version = $installed_version ? $installed_version : 0;
		if($installed_version < '1.0.0'){
			//User Roles
			$user_role = get_option('wppm_user_role');
			if(empty($user_role)){
				$user_role = array(
					1 => array(
						'label' => __('manager','taskbuilder'),
					),
					2 => array(
						'label' => __('co-worker','taskbuilder'),
					)
				);
				update_option('wppm_user_role',$user_role);
			}

			$wppm_email_notificatins = get_option('wppm_email_notification');
			if(empty($wppm_email_notificatins)){
				$wppm_email_notificatins = array(
					1=>array(
						'type'=>'new_project',
						'subject'=>__('New project has been created:{project_name}','taskbuilder'),
						'body'=>__('<p><strong>{user_name}</strong> assigned project to you.</p> <p>Below are details of the project :</p><p><strong> Assign to:-</strong>{project_assigned_users}</p><p><strong> Start Date:-</strong>{project_start_date} </p><p> <strong> End Date:- </strong> {project_end_date} </p> <p><strong>Description:-</strong>{project_description}</p>','taskbuilder'),
						'recipients'=>array(1,2)
					),
					2=>array(
						'type'=>'new_task',
						'subject'=>__('New task has been created: {task_name}','taskbuilder'),
						'body'=>__('<p><strong>{user_name}</strong> assigned task to you.</p><p>Below are details of the task :</p> <p><strong>Assign to:-</strong>{task_assigned_users} </p><p><strong> Start Date:-</strong>{task_start_date}</p><p><strong>End Date:-</strong>{task_end_date} </p><p> <strong>Description:-</strong>{task_description}</p>','taskbuilder'),
						'recipients'=>array(1,2)
					),
					3=>array(
						'type'=>'change_project_status',
						'subject'=>__('{project_name} Project\'s status has been changed from {old_project_status} to {new_project_status}','taskbuilder'),
						'body'=>__('<p><strong>{user_name}</strong> changed project\'s status to <strong>{new_project_status}</strong> </p> <p>Below are details of the project:</p><p><strong>Assign to:- </strong> {project_assigned_users}</p><p><strong>Start Date:-</strong>{project_start_date} </p> <p><strong>End Date:-</strong>{project_end_date} </p> <p><strong>Description:-</strong>{project_description}</p>','taskbuilder'),
						'recipients'=>array(1,2)
					),
					4=>array(
						'type'=>'change_task_status',
						'subject'=>__('{task_name} Task\'s status has been changed from {old_task_status} to {new_task_status}','taskbuilder'),
						'body'=>__('<p><strong>{user_name}</strong> changed task status to <strong>{new_task_status} </strong><p> Below are details of the task:</p> <p><strong>Assign to:-</strong>{task_assigned_users} </p> <p><strong>Start Date:-</strong>{task_start_date}</p><p><strong>End Date:-</strong>{task_end_date} </p> <p><strong>Description:-</strong>{task_description}</p>','taskbuilder'),
						'recipients'=>array(1,2)
					),
					5=>array(
						'type'=>'change_project_assign_users',
						'subject'=>__('{project_name} Project\'s assigned users changed from {previously_assigned_project_users} to {project_assigned_users}','taskbuilder'),
						'body'=>__('<p><strong>{user_name}</strong> changed project\'s assign users to <strong> {project_assigned_users}</strong></p> <p>Below are details of the project :</p><p><strong> Assign to:-</strong>{project_assigned_users}</p><p><strong> Start Date:-</strong>{project_start_date} </p><p> <strong> End Date:- </strong> {project_end_date} </p> <p><strong>Description:-</strong>{project_description}</p>','taskbuilder'),
						'recipients'=>array(1,2)
					),
					6=>array(
						'type'=>'change_task_assign_users',
						'subject'=>__('{task_name} Task\'s assigned users change from {previously_assigned_task_users} to {task_assigned_users}','taskbuilder'),
						'body'=>__('<p><strong>{user_name}</strong> changed task\'s assign users to <strong>{task_assigned_users}</strong></p><p>Below are details of the task :</p> <p><strong>Assign to:-</strong>{task_assigned_users} </p><p><strong> Start Date:-</strong>{task_start_date}</p><p><strong>End Date:-</strong>{task_end_date} </p><p> <strong>Description:-</strong>{task_description}</p>','taskbuilder'),
						'recipients'=>array(1,2)
					),
					7=>array(
						'type'=>'new_discussion',
						'subject'=>__('[{project_name}][{task_name}] {last_comment_user_name} started new discussion:','taskbuilder'),
						'body'=>__('<p><strong>{last_comment_user_name}</strong> wrote:</p> <p>{comment_body}</p>','taskbuilder'),
						'recipients'=>array(1,2)
					)
				);
				update_option('wppm_email_notification',$wppm_email_notificatins);
			}

			update_option('wppm_default_task_list_view',1);

			$default_statuses = $wpdb->get_var("select count(*) from {$wpdb->prefix}wppm_project_statuses");
			if (empty($default_statuses)) {
				$wpdb->insert($wpdb->prefix . "wppm_project_statuses", array('id' => 1,'name' => __('New','taskbuilder'),'color'=>'#ffffff','bg_color'=>'#306EFF','load_order'=>1));
				$wpdb->insert($wpdb->prefix . "wppm_project_statuses", array('id' => 2,'name' => __('In Progress','taskbuilder'),'color'=>'#ffffff','bg_color'=>'#FCD12A','load_order'=>2));
				$wpdb->insert($wpdb->prefix . "wppm_project_statuses", array('id' => 3,'name' => __('Hold','taskbuilder'),'color'=>'#ffffff','bg_color'=>'#D3D3D3','load_order'=>3));
				$wpdb->insert($wpdb->prefix . "wppm_project_statuses", array('id' => 4,'name' => __('Completed','taskbuilder'),'color'=>'#ffffff','bg_color'=>'#5cb85c','load_order'=>4));
			}
			$wppm_default_project_status = get_option('wppm_default_project_status',true);
			if($wppm_default_project_status == ''){
				update_option('wppm_default_project_status',1);
			}
			$default_task_statuses = $wpdb->get_var("select count(*) from {$wpdb->prefix}wppm_task_statuses");
			if (empty($default_task_statuses)) {
				$wpdb->insert($wpdb->prefix . "wppm_task_statuses",array('id' => 1,'name' => __('Todo','taskbuilder'),'color'=>'#ffffff','bg_color'=>'#306EFF','load_order'=>1));
				$wpdb->insert($wpdb->prefix . "wppm_task_statuses",array('id' => 2,'name' => __('In Progress','taskbuilder'),'color'=>'#ffffff','bg_color'=>'#FCD12A','load_order'=>2));
				$wpdb->insert($wpdb->prefix . "wppm_task_statuses",array('id' => 3,'name' => __('Hold','taskbuilder'),'color'=>'#ffffff','bg_color'=>'#D3D3D3','load_order'=>3));
				$wpdb->insert($wpdb->prefix . "wppm_task_statuses",array('id' => 4,'name' => __('Completed','taskbuilder'),'color'=>'#ffffff','bg_color'=>'#5cb85c','load_order'=>4));
			}
			$wppm_default_task_status = get_option('wppm_default_task_status',true);
			if($wppm_default_task_status == ''){
				update_option('wppm_default_task_status',1);
			}
		}
		if($installed_version < '1.0.2'){
			update_option('wppm_project_time', 1);
		}
		if($installed_version < '2.0.0'){
			update_option('wppm_default_project_date', 1);
			update_option('wppm_default_task_date', 1);
		}
		if($installed_version < '2.0.2'){
			update_option('wppm_default_edit_tasks_permission', 0);
		}
		if($installed_version < '2.0.4'){
			update_option('wppm_public_projects_permission',0);
		}
		if($installed_version < '2.0.8'){
			update_option('wppm_task_time',1);
		}
		// update wppm_version option to plugin version
		update_option( 'wppm_version', WPPM_VERSION );
	}
}

endif;

new WPPM_Install();