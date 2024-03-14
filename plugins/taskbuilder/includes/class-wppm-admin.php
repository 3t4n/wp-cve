<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPPM_Admin' ) ) :
  
  final class WPPM_Admin {
    
    // constructor
    public function __construct() {
      add_action( 'admin_enqueue_scripts', array( $this, 'loadScripts') );
      add_action( 'admin_menu', array($this,'wppm_register_dashboard_menu') );
      add_action( 'wp_ajax_wppm_add_new_project',array($this,'wppm_add_new_project'));
      add_action( 'wp_ajax_wppm_open_project',array($this,'wppm_open_project'));
      add_action( 'wp_ajax_wppm_get_users',array($this,'wppm_get_users'));
      add_action( 'wp_ajax_wppm_filter_autocomplete',array($this,'wppm_filter_autocomplete'));
      add_action( 'wp_ajax_wppm_get_general_setings',array($this,'wppm_get_general_settings'));
      add_action( 'wp_ajax_wppm_get_category_settings',array($this,'wppm_get_category_settings'));
      add_action( 'wp_ajax_wppm_get_add_category',array($this,'wppm_get_add_category'));
      add_action( 'wp_ajax_wppm_set_add_category',array($this,'wppm_set_add_category'));
      add_action( 'wp_ajax_wppm_get_edit_category',array($this,'wppm_get_edit_category'));
      add_action( 'wp_ajax_wppm_set_edit_category',array($this,'wppm_set_edit_category'));
      add_action( 'wp_ajax_wppm_delete_category',array($this,'wppm_delete_category'));
      add_action( 'wp_ajax_wppm_get_proj_status_settings',array($this,'wppm_get_proj_status_settings'));
      add_action( 'wp_ajax_wppm_get_add_proj_status',array($this,'wppm_get_add_proj_status'));
      add_action( 'wp_ajax_wppm_set_category_order',array($this,'wppm_set_category_order'));
      add_action( 'wp_ajax_wppm_set_add_status',array($this,'wppm_set_add_status'));
      add_action( 'wp_ajax_wppm_get_edit_proj_status',array($this,'wppm_get_edit_proj_status'));
      add_action( 'wp_ajax_wppm_set_edit_status',array($this,'wppm_set_edit_status'));
      add_action( 'wp_ajax_wppm_delete_status',array($this,'wppm_delete_status'));
      add_action( 'wp_ajax_wppm_set_status_order',array($this,'wppm_set_status_order'));
      add_action( 'wp_ajax_wppm_create_project',array($this,'wppm_create_project'));
      add_action('wp_ajax_wppm_get_project_list',array($this,'wppm_get_project_list'),10,2);
      add_action('wp_ajax_wppm_view_project_tasks',array($this,'wppm_view_project_tasks'));
      add_action('wp_ajax_wppm_add_new_task',array($this,'wppm_add_new_task'));
      add_action('wp_ajax_wppm_get_task_list',array($this,'wppm_get_task_list'));
      add_action('wp_ajax_wppm_get_priority_settings',array($this,'wppm_get_priority_settings'));
      add_action('wp_ajax_wppm_add_task_priority',array($this,'wppm_add_task_priority'));
      add_action('wp_ajax_wppm_set_add_priority',array($this,'wppm_set_add_priority'));
      add_action('wp_ajax_wppm_get_edit_priority',array($this,'wppm_get_edit_priority'));
      add_action('wp_ajax_wppm_set_edit_priority',array($this,'wppm_set_edit_priority'));
      add_action('wp_ajax_wppm_delete_task_priority',array($this,'wppm_delete_task_priority'));
      add_action('wp_ajax_wppm_set_priority_order',array($this,'wppm_set_priority_order'));
      add_action('wp_ajax_wppm_get_task_status_settings',array($this,'wppm_get_task_status_settings'));
      add_action('wp_ajax_wppm_get_add_task_status',array($this,'wppm_get_add_task_status'));
      add_action('wp_ajax_wppm_set_add_task_status',array($this,'wppm_set_add_task_status'));
      add_action('wp_ajax_wppm_get_edit_task_status',array($this,'wppm_get_edit_task_status'));
      add_action('wp_ajax_wppm_set_edit_task_status',array($this,'wppm_set_edit_task_status'));
      add_action('wp_ajax_wppm_delete_task_status',array($this,'wppm_delete_task_status'));
      add_action('wp_ajax_wppm_set_task_status_order',array($this,'wppm_set_task_status_order'));
      add_action('wp_ajax_wppm_create_task',array($this,'wppm_create_task'));
      add_action('wp_ajax_wppm_get_task_list_card_view',array($this,'wppm_get_task_list_card_view'));
      add_action('wp_ajax_wppm_drag_and_drop_card',array($this,'wppm_drag_and_drop_card'));
      add_action('wp_ajax_wppm_open_task',array($this,'wppm_open_task'));
      add_action('wp_ajax_wppm_add_new_checklist',array($this,'wppm_add_new_checklist'));
      add_action('wp_ajax_wppm_add_new_checklist_item',array($this,'wppm_add_new_checklist_item'));
      add_action('wp_ajax_wppm_delete_checklist',array($this,'wppm_delete_checklist'));
      add_action('wp_ajax_wppm_set_checklist_progress',array($this,'wppm_set_checklist_progress'));
      add_action('wp_ajax_wppm_set_project_users',array($this,'wppm_set_project_users'));
      add_action('wp_ajax_wppm_get_task_users',array($this,'wppm_get_task_users'));
      add_action('wp_ajax_wppm_set_task_users',array($this,'wppm_set_task_users'));
      add_action('wp_ajax_wppm_submit_task_comment',array($this,'wppm_submit_task_comment'));
      add_action('wp_ajax_wppm_upload_file',array($this,'wppm_upload_file'));
      add_action('wp_ajax_wppm_get_en_general_setting',array($this,'wppm_get_en_general_setting'));
      add_action('wp_ajax_wppm_set_en_general_settings',array($this,'wppm_set_en_general_settings'));
      add_action('wp_ajax_wppm_get_en_task_notifications',array($this,'wppm_get_en_task_notifications'));
      add_action('wp_ajax_wppm_edit_task_status',array($this,'wppm_edit_task_status'));
      add_action('wp_ajax_wppm_set_change_task_status',array($this,'wppm_set_change_task_status'));
      add_action('wp_ajax_wppm_edit_task_creator',array($this,'wppm_edit_task_creator'));
      add_action('wp_ajax_wppm_set_change_raised_by',array($this,'wppm_set_change_raised_by'));
      add_action('wp_ajax_wppm_edit_task_details',array($this,'wppm_edit_task_details'));
      add_action('wp_ajax_wppm_set_change_task_details',array($this,'wppm_set_change_task_details'));
      add_action('wp_ajax_wppm_edit_task_thread',array($this,'wppm_edit_task_thread'));
      add_action('wp_ajax_wppm_set_edit_task_thread',array($this,'wppm_set_edit_task_thread'));
      add_action('wp_ajax_wppm_delete_task_thread',array($this,'wppm_delete_task_thread'));
      add_action('wp_ajax_wppm_set_delete_thread',array($this,'wppm_set_delete_thread'));
      add_action('wp_ajax_wppm_edit_project_details',array($this,'wppm_edit_project_details'));
      add_action('wp_ajax_wppm_set_change_project_details',array($this,'wppm_set_change_project_details'));
      add_action('wp_ajax_wppm_edit_project_status',array($this,'wppm_edit_project_status'));
      add_action('wp_ajax_wppm_set_change_project_status',array($this,'wppm_set_change_project_status'),10,3);
      add_action('wp_ajax_wppm_edit_project_creator',array($this,'wppm_edit_project_creator'));
      add_action('wp_ajax_wppm_set_change_project_raised_by',array($this,'wppm_set_change_project_raised_by'));
      add_action('wp_ajax_wppm_get_delete_project',array($this,'wppm_get_delete_project'));
      add_action('wp_ajax_wppm_set_delete_project',array($this,'wppm_set_delete_project'));
      add_action('wp_ajax_wppm_get_delete_task',array($this,'wppm_get_delete_task'));
      add_action('wp_ajax_wppm_set_delete_task',array($this,'wppm_set_delete_task'));
      add_action('wp_ajax_wppm_get_edit_email_notification',array($this,'wppm_get_edit_email_notification'));
      add_action('wp_ajax_wppm_get_templates',array($this,'wppm_get_templates'));
      add_action('wp_ajax_wppm_set_edit_email_notification',array($this,'wppm_set_edit_email_notification'));
      add_action('wppm_set_change_project_status',array($this,'wppm_en_change_project_status'),100,3);
      add_action('wppm_set_project_users',array($this,'wppm_en_set_project_users'),100,1);
      add_action('wppm_after_set_change_task_status',array($this,'wppm_after_set_change_task_status'),100,3);
      add_action('wppm_set_task_users',array($this,'wppm_en_set_task_users'),100,1);
      add_action('wppm_after_task_created',array($this,'wppm_en_task_created'),100,1);
      add_action('wppm_after_project_created',array($this,'wppm_set_project_meta'));
      add_action('wppm_after_project_created',array($this,'wppm_en_project_created'),100,1);
      add_action('wppm_after_submit_task_comment',array($this,'wppm_en_submit_task_comment'),10,2);
      add_action('wp_ajax_wppm_set_general_settings',array($this,'wppm_set_general_settings'));
      add_action('wp_ajax_wppm_get_project_tasks',array($this,'wppm_get_project_tasks'));
      add_action('wp_ajax_wppm_remove_thread_attachment',array($this,'wppm_remove_thread_attachment'),10,3);
      add_action('wp_ajax_wppm_remove_checklist_item',array($this,'wppm_remove_checklist_item'),10,4);
      add_action('wp_ajax_wppm_clone_task',array($this,'wppm_clone_task'),10,1);
      add_action('wp_ajax_wppm_set_clone_task',array($this,'wppm_set_clone_task'),10,2);
      add_action('wp_ajax_wppm_get_advanced_settings',array($this,'wppm_get_advanced_settings'));
      add_action('wp_ajax_wppm_set_advanced_settings',array($this,'wppm_set_advanced_settings'));
      add_action('wp_ajax_wppm_get_project_visibility',array($this,'wppm_get_project_visibility'),10,1);
      add_action('wp_ajax_wppm_change_project_visibility',array($this,'wppm_change_project_visibility'),10,2);
    }
    
    // Load admin scripts
    public function loadScripts(){
      if(isset($_REQUEST['page']) && preg_match('/wppm-/',sanitize_text_field($_REQUEST['page']))) :
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_editor();
        //bootstrap
        wp_enqueue_style('wppm-bootstrap-css', WPPM_PLUGIN_URL.'asset/css/wppm_bootstrap.css?version='.WPPM_VERSION );
        //admin scripts
        wp_enqueue_script('wppm-admin', WPPM_PLUGIN_URL.'asset/js/admin.js?version='.WPPM_VERSION, array('jquery'), null, true);
        //wp_enqueue_script('wppm-public', WPPM_PLUGIN_URL.'asset/js/public.js?version='.WPPM_VERSION, array('jquery'), null, true);
        wp_enqueue_script('wppm-modal', WPPM_PLUGIN_URL.'asset/js/modal.js?version='.WPPM_VERSION, array('jquery'), null, true);

        // wp_enqueue_style('wppm-public-css', WPPM_PLUGIN_URL . 'asset/css/public.css?version='.WPPM_VERSION );
        wp_enqueue_style('wppm-admin-css', WPPM_PLUGIN_URL . 'asset/css/admin.css?version='.WPPM_VERSION );
        wp_enqueue_style('wppm-modal-css', WPPM_PLUGIN_URL . 'asset/css/modal.css?version='.WPPM_VERSION );
        
        // flatpickr
        wp_enqueue_script('wppm-flatpickr-js', WPPM_PLUGIN_URL.'asset/lib/flatpickr/flatpickr.js?version='.WPPM_VERSION, ['jquery'], null, true);
        wp_enqueue_style('wppm-flatpickr-css', WPPM_PLUGIN_URL . 'asset/lib/flatpickr/flatpickr.min.css?version='.WPPM_VERSION );

        //select2
        wp_enqueue_script('wppm-select2-js', WPPM_PLUGIN_URL.'asset/lib/select2/select2.min.js?version='.WPPM_VERSION, ['jquery'], null, true);
        wp_enqueue_style('wppm-select2-css', WPPM_PLUGIN_URL . 'asset/lib/select2/select2.min.css?version='.WPPM_VERSION );

        //gpopover
        wp_enqueue_script('wppm-gpopover-js', WPPM_PLUGIN_URL.'asset/lib/gpopover/jquery.gpopover.js?version='.WPPM_VERSION, ['jquery'], null, true);
        wp_enqueue_style('wppm-gpopover-css', WPPM_PLUGIN_URL . 'asset/lib/gpopover/jquery.gpopover.css?version='.WPPM_VERSION );
        
        //dragula
        wp_enqueue_script('wppm-dragula-js', WPPM_PLUGIN_URL.'asset/lib/dragula/dragula.min.js?version='.WPPM_VERSION, ['jquery'], null, true);
        wp_enqueue_style('wppm-dragula-css', WPPM_PLUGIN_URL . 'asset/lib/dragula/dragula.min.css?version='.WPPM_VERSION );
        
        //datatable
        wp_enqueue_script('wppm-datatable-js', WPPM_PLUGIN_URL.'asset/lib/DataTables/datatables.min.js?version='.WPPM_VERSION, ['jquery'], null, true);
        wp_enqueue_style('wppm-datatable-css', WPPM_PLUGIN_URL.'asset/lib/DataTables/datatables.min.css?version='.WPPM_VERSION );
        
        //localize script
        $loading_html = '<div class="wppm_loading_icon"><img src="'.WPPM_PLUGIN_URL.'asset/images/ajax-loading.gif"></div>';
        $localize_script_data = apply_filters( 'wppm_admin_localize_script', array(
            'ajax_url'             => admin_url( 'admin-ajax.php' ),
            'loading_html'         => $loading_html,
            'add_new_category'     => __('Add New Category','taskbuilder'), 
            'edit_category'        =>__('Edit Category','taskbuilder'),
            'please_wait'          =>__('Please wait ...','taskbuilder'),
            'confirm'              =>__('Are you sure?','taskbuilder'),
            'add_new_status'       =>__('Add New Status','taskbuilder'),
            'edit_status'          =>__('Edit status','taskbuilder'),
            'add_new_priority'      =>__('Add New Priority','taskbuilder'),
            'templates'            => __('Templates','taskbuilder')
        ));
        wp_localize_script( 'wppm-admin', 'wppm_admin', $localize_script_data );
      endif;
    }
    
    // Dashboard Menus
    public function wppm_register_dashboard_menu(){
      global $current_user,$wppmfunction;
      add_menu_page( 
        __( 'Projects', 'taskbuilder'),
        __( 'Projects','taskbuilder'),
        'read',
        'wppm-projects',
        array($this,'wppm_projects'),
        WPPM_PLUGIN_URL.'asset/images/tasklist.svg',
        25
      );
      add_submenu_page(
        'wppm-projects',
        __('Project List', 'taskbuilder' ),
        __('Projects', 'taskbuilder' ),
        'read',
        'wppm-projects',
        array($this,'wppm_projects')
      );
      
      add_submenu_page(
        'wppm-projects',
        __('Tasks', 'taskbuilder' ),
        __('Tasks', 'taskbuilder' ),
        'read',
        'wppm-tasks',
        array($this,'wppm_tasks')
      );

      add_submenu_page(
        'wppm-projects',
        __('Email Notifications', 'taskbuilder' ),
        __('Email Notifications', 'taskbuilder' ),
        'manage_options',
        'wppm-email-notifications',
        array($this,'wppm_email_notifications')
      );

      do_action('wppm_add_submenu_page');

      add_submenu_page(
        'wppm-projects',
        __('Settings', 'taskbuilder' ),
        __('Settings', 'taskbuilder' ),
        'manage_options',
        'wppm-settings',
        array($this,'settings')
      );

      add_submenu_page(
        'wppm-projects',
        __('License', 'taskbuilder' ),
        __('License', 'taskbuilder' ),
        'manage_options',
        'wppm-license',
        array($this,'licenses')
      );

      add_submenu_page(
        'wppm-projects',
        __('Addons', 'taskbuilder' ),
        __('Addons', 'taskbuilder' ),
        'manage_options',
        'wppm-addons',
        array($this,'wppm_addons')
      );
    
    }
    
    public function wppm_projects(){
      include WPPM_ABSPATH.'includes/admin/projects/projects.php';
    }

    public function wppm_add_new_project(){
      include WPPM_ABSPATH.'includes/admin/projects/wppm_add_new_project.php';
      die();
    }

    public function wppm_tasks(){
      include WPPM_ABSPATH.'includes/admin/tasks/wppm_tasks.php';
    }

    public function wppm_email_notifications(){
      include WPPM_ABSPATH.'includes/admin/email_notifications/wppm_email_notifications.php';
    }

    public function settings(){
      include WPPM_ABSPATH.'includes/admin/settings.php';
    }

    public function licenses(){
      include WPPM_ABSPATH.'includes/admin/licenses.php';
    }
    
    public function wppm_open_project(){
      include WPPM_ABSPATH.'includes/admin/projects/wppm_open_project.php';
      die();
    }

    public function wppm_get_users(){
      include WPPM_ABSPATH.'includes/admin/projects/get_users.php';
      die();
    }

    public function wppm_filter_autocomplete(){
      include WPPM_ABSPATH.'includes/admin/projects/wppm_filter_autocomplete.php';
      die();
    }

    public function wppm_get_general_settings(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_get_general_settings.php';
      die();
    }
    
    public function wppm_get_category_settings(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_get_category_settings.php';
      die();
    }

    public function wppm_get_add_category(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_get_add_category.php';
      die();
    }

    public function wppm_set_add_category(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_set_add_category.php';
      die();
    }

    public function wppm_get_edit_category(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_get_edit_category.php';
      die();
    }

    public function wppm_set_edit_category(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_set_edit_category.php';
      die();
    }

    public function wppm_delete_category(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_delete_category.php';
      die();
    }

    public function wppm_get_proj_status_settings(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_get_proj_status_settings.php';
      die();
    }

    public function wppm_get_add_proj_status(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_get_add_proj_status.php';
      die();
    }

    public function wppm_set_category_order(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_set_category_order.php';
      die();
    }

    public function wppm_set_add_status(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_set_add_status.php';
      die();
    }

    public function wppm_get_edit_proj_status(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_get_edit_proj_status.php';
      die();
    }

    public function wppm_set_edit_status(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_set_edit_status.php';
      die();
    }

    public function wppm_delete_status(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_delete_status.php';
      die();
    }

    public function wppm_set_status_order(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_set_status_order.php';
      die();
    }

    public function wppm_create_project(){
      include WPPM_ABSPATH.'includes/admin/projects/wppm_create_project.php';
      die();
    }

    public function wppm_en_project_created($project_id){
      include WPPM_ABSPATH.'includes/admin/email_notifications/wppm_en_project_created.php';
    }

    public function wppm_get_project_list(){
      include WPPM_ABSPATH.'includes/admin/projects/projects_list.php';
      die();
    }

    public function wppm_view_project_tasks(){
      include WPPM_ABSPATH.'includes/admin/projects/open_project/wppm_view_project_tasks.php';
      die();
    }

    public function wppm_add_new_task(){
      include WPPM_ABSPATH.'includes/admin/tasks/wppm_add_new_task.php';
      die();
    }

    public function wppm_get_task_list(){
      include WPPM_ABSPATH.'includes/admin/tasks/wppm_tasks_list.php';
      die();
    }

    public function wppm_get_priority_settings(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_get_priority_settings.php';
      die();
    }

    public function wppm_add_task_priority(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_add_task_priority.php';
      die();
    }

    public function wppm_set_add_priority(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_set_add_priority.php';
      die();
    }

    public function wppm_get_edit_priority(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_get_edit_priority.php';
      die();
    }

    public function wppm_set_edit_priority(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_set_edit_priority.php';
      die();
    }

    public function wppm_delete_task_priority(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_delete_task_priority.php';
      die();
    }

    public function wppm_set_priority_order(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_set_priority_order.php';
      die();
    }

    public function wppm_get_task_status_settings(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_get_task_status_settings.php';
      die();
    }

    public function wppm_get_add_task_status(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_get_add_task_status.php';
      die();
    }

    public function wppm_set_add_task_status(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_set_add_task_status.php';
      die();
    }

    public function wppm_get_edit_task_status(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_get_edit_task_status.php';
      die();
    }

    public function wppm_set_edit_task_status(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_set_edit_task_status.php';
      die();
    }

    public function wppm_delete_task_status(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_delete_task_status.php';
      die();
    }

    public function wppm_set_task_status_order(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_set_task_status_order.php';
      die();
    }

    public function wppm_create_task(){
      include WPPM_ABSPATH.'includes/admin/tasks/wppm_create_task.php';
      die();
    }

    public function wppm_get_task_list_card_view(){
      include WPPM_ABSPATH.'includes/admin/projects/open_project/wppm_view_project_tasks.php';
      die();
    }

    public function wppm_drag_and_drop_card(){
      include WPPM_ABSPATH.'includes/admin/projects/open_project/wppm_drag_and_drop_card.php';
      die();
    }

    public function wppm_open_task(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/wppm_open_task.php';
      die();
    }

    public function wppm_add_new_checklist(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/checklist/wppm_add_new_checklist.php';
      die();
    }

    public function wppm_add_new_checklist_item(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/checklist/wppm_add_new_checklist_item.php';
      die();
    }

    public function wppm_delete_checklist(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/checklist/wppm_delete_checklist.php';
      die();
    }

    public function wppm_set_checklist_progress(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/checklist/wppm_set_checklist_progress.php';
      die();
    }

    public function wppm_set_project_users(){
      include WPPM_ABSPATH.'includes/admin/projects/open_project/wppm_set_project_users.php';
      die();
    }

    public function wppm_get_task_users(){
      include WPPM_ABSPATH.'includes/admin/tasks/wppm_get_task_users.php';
      die();
    }

    public function wppm_set_task_users(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/wppm_set_task_users.php';
      die();
    }

    public function wppm_submit_task_comment(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/wppm_submit_task_comment.php';
      die();
    }

    public function wppm_upload_file(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/wppm_upload_file.php';
      die();
    }

    public function wppm_get_en_general_setting(){
      include WPPM_ABSPATH.'includes/admin/email_notifications/wppm_get_en_general_setting.php';
      die();
    }

    public function wppm_set_en_general_settings(){
      include WPPM_ABSPATH.'includes/admin/email_notifications/wppm_set_en_general_setting.php';
      die();
    }

    public function wppm_get_en_task_notifications(){
      include WPPM_ABSPATH.'includes/admin/email_notifications/wppm_get_en_task_notifications.php';
      die();
    }

    public function wppm_edit_task_status(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/wppm_edit_task_status.php';
      die();
    }

    public function wppm_set_change_task_status(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/wppm_set_change_task_status.php';
      die();
    }

    public function wppm_edit_task_creator(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/wppm_edit_task_creator.php';
      die();
    }

    public function wppm_set_change_raised_by(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/wppm_set_change_raised_by.php';
      die();
    }

    public function wppm_edit_task_details(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/wppm_edit_task_details.php';
      die();
    }

    public function wppm_set_change_task_details(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/wppm_set_change_task_details.php';
      die();
    }

    public function wppm_edit_task_thread(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/wppm_edit_task_thread.php';
      die();
    }

    public function wppm_set_edit_task_thread(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/wppm_set_edit_task_thread.php';
      die();
    }

    public function wppm_delete_task_thread(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/wppm_delete_task_thread.php';
      die();
    }

    public function wppm_set_delete_thread(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/wppm_set_delete_thread.php';
      die();
    }

    public function wppm_edit_project_details(){
      include WPPM_ABSPATH.'includes/admin/projects/open_project/wppm_edit_project_details.php';
      die();
    }

    public function wppm_set_project_meta($project_id){
      include WPPM_ABSPATH.'includes/admin/projects/wppm_set_project_meta.php';
    }

    public function wppm_set_change_project_details(){
      include WPPM_ABSPATH.'includes/admin/projects/open_project/wppm_set_change_project_details.php';
      die();
    }

    public function wppm_edit_project_status(){
      include WPPM_ABSPATH.'includes/admin/projects/open_project/wppm_edit_project_status.php';
      die();
    }

    public function wppm_set_change_project_status(){
      include WPPM_ABSPATH.'includes/admin/projects/open_project/wppm_set_change_project_status.php';
      die();
    }

    public function wppm_edit_project_creator(){
      include WPPM_ABSPATH.'includes/admin/projects/open_project/wppm_edit_project_creator.php';
      die();
    }

    public function wppm_set_change_project_raised_by(){
      include WPPM_ABSPATH.'includes/admin/projects/open_project/wppm_set_change_project_raised_by.php';
      die();
    }

    public function wppm_get_delete_project(){
      include WPPM_ABSPATH.'includes/admin/projects/wppm_get_delete_project.php';
      die();
    }

    public function wppm_set_delete_project(){
      include WPPM_ABSPATH.'includes/admin/projects/wppm_set_delete_project.php';
      die();
    }

    public function wppm_get_delete_task(){
      include WPPM_ABSPATH.'includes/admin/tasks/wppm_get_delete_task.php';
      die();
    }

    public function wppm_set_delete_task(){
      include WPPM_ABSPATH.'includes/admin/tasks/wppm_set_delete_task.php';
      die();
    }

    public function wppm_get_edit_email_notification(){
      include WPPM_ABSPATH.'includes/admin/email_notifications/wppm_get_edit_email_notification.php';
      die();
    }
    
    public function wppm_get_templates(){
      include WPPM_ABSPATH.'includes/admin/email_notifications/wppm_get_templates.php';
      die();
    }

    public function wppm_set_edit_email_notification(){
      include WPPM_ABSPATH.'includes/admin/email_notifications/wppm_set_edit_email_notification.php';
      die();
    }

    public function wppm_en_change_project_status($project_id, $status_id, $prev_status){
      include WPPM_ABSPATH.'includes/admin/email_notifications/wppm_en_change_project_status.php';
    }

    public function wppm_en_set_project_users($project_id){
      include WPPM_ABSPATH.'includes/admin/email_notifications/wppm_en_set_project_users.php';
    }

    public function wppm_after_set_change_task_status($task_id,$status_id,$prev_status ){
      include WPPM_ABSPATH.'includes/admin/email_notifications/wppm_en_set_change_task_status.php';
    }

    public function wppm_en_set_task_users($task_id){
      include WPPM_ABSPATH.'includes/admin/email_notifications/wppm_en_set_task_users.php';
    }

    public function wppm_en_submit_task_comment($task_id,$comment_id){
      include WPPM_ABSPATH.'includes/admin/email_notifications/wppm_en_submit_task_comment.php';
    }

    public function wppm_en_task_created($task_id){
      include WPPM_ABSPATH.'includes/admin/email_notifications/wppm_en_task_created.php';
    }

    public function wppm_set_general_settings(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_set_general_settings.php';
      die();
    }

    public function wppm_get_project_tasks(){
      include WPPM_ABSPATH.'includes/admin/projects/open_project/wppm_get_project_tasks.php';
      die();
    }
    
    public function wppm_remove_thread_attachment(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/wppm_thread_attachment_remove.php';
      die();
    }

    public function wppm_remove_checklist_item(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/checklist/wppm_remove_checklist_item.php';
      die();
    }

    public function wppm_addons(){
      include WPPM_ABSPATH.'includes/admin/addons.php';
    }

    public function wppm_clone_task(){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/wppm_clone_task.php';
      die();
    }

    public function wppm_set_clone_task($task_id){
      include WPPM_ABSPATH.'includes/admin/tasks/open_task/wppm_set_clone_task.php';
      die();
    }

    public function wppm_get_advanced_settings(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_get_advanced_settings.php';
      die();
    }

    public function wppm_set_advanced_settings(){
      include WPPM_ABSPATH.'includes/admin/settings/wppm_set_advanced_settings.php';
      die();
    }

    public function wppm_get_project_visibility(){
      include WPPM_ABSPATH.'includes/admin/projects/open_project/wppm_get_project_visibility.php';
      die();
    }

    public function wppm_change_project_visibility(){
      include WPPM_ABSPATH.'includes/admin/projects/open_project/wppm_change_project_visibility.php';
      die();
    }
  }
  
endif;

new WPPM_Admin();