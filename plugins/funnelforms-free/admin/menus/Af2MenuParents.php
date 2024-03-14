<?php

abstract class Fnsf_Af2Menu {
    // Needed Everywhere
    protected $menu_type = null;
    protected $Admin = null;

    // Needed in no Builders
    protected $heading = null;
    protected $menu_functions_button = null; // optional
    protected $menu_functions_search = null; // optional
    protected $menu_functions_select = null; // optional
    protected $menu_hook_inline_checkbox = null; // optional
    protected $menu_hook_inline_search = null; // optional
    protected $menu_hook_extra_title = null; // optional
    protected $menu_hook_inline_button_form = null; // optional

    protected $menu_blur_option = null;

    // Needed in Tables
    protected $menu_action_button_add_post = null; // optional
    protected $menu_action_button_copy_posts = null; // optional
    protected $menu_action_button_delete_posts = null; // optional
    protected $table_columns = null;
    protected $posts = null;
    protected $post_type_constant = null;
    protected $table_builder_load_url = null; // optional
    protected $table_builder_load_url_id = null; // optional
    protected $table_builder_load_url_ids_array = null; // optional

    protected $custom_template = null; // optional

    // Needed in Customs
    protected $menu_custom_template = null;
    protected $af2_custom_contents = null; // optional
    protected $show_sidebar = null; //optional

    // Needed in Builders
    protected $builder_heading = null;
    protected $builder_sidebar_data = null; // optional
    protected $builder_sidebar_select_filter = null; // optional
    protected $builder_sidebar_content_elements = null; // optional
    protected $builder_sidebar_content_element_class = null; // optional
    protected $builder_sidebar_edit = null; // optional
    protected $builder_template = null;
    protected $menu_builder_control_buttons = null; // optional
    protected $menu_builder_pre_control_buttons = null; // optional
    protected $builder_pre_heading_buttons = null; // optional
    protected $af2_builder_custom_contents = null; // optional
    protected $af2_own_save_button_id = null; // optional
    protected $close_editor_url = null; // optional

    // Functions
    public function __construct($Admin) {
        $this->Admin = $Admin;

        add_action('wp_loaded',array($this,'do_request_handling'));
    }

    abstract protected function fnsf_load_resources();
    abstract protected function fnsf_load_all_values();

    public function fnsf_get_content() {
        $this->fnsf_load_all_values();
        $this->fnsf_load_resources();

        $menu_type = $this->menu_type;

        $heading = $this->heading;

        $custom_template = $this->custom_template;

        $menu_functions_button = $this->menu_functions_button;
        $menu_functions_search = $this->menu_functions_search;
        $menu_functions_select = $this->menu_functions_select;

        $menu_action_button_add_post = $this->menu_action_button_add_post;
        $menu_action_button_copy_posts = $this->menu_action_button_copy_posts;
        $menu_action_button_delete_posts = $this->menu_action_button_delete_posts;
        $menu_hook_inline_checkbox = $this->menu_hook_inline_checkbox;
        $menu_hook_inline_search = $this->menu_hook_inline_search;
        $menu_hook_extra_title = $this->menu_hook_extra_title;
        $menu_hook_inline_button_form = $this->menu_hook_inline_button_form;
        $menu_blur_option = $this->menu_blur_option;
        $table_columns = $this->table_columns;
        $posts = $this->posts;
        $post_type_constant = $this->post_type_constant;
        $table_builder_load_url = $this->table_builder_load_url;
        $table_builder_load_url_id = $this->table_builder_load_url_id;
        $table_builder_load_url_ids_array = $this->table_builder_load_url_ids_array;

        $menu_custom_template = $this->menu_custom_template;
        $af2_custom_contents = $this->af2_custom_contents;
        $show_sidebar = $this->show_sidebar;

        $builder_heading = $this->builder_heading;
        $close_editor_url = $this->close_editor_url;
        $builder_sidebar_data = $this->builder_sidebar_data;
        $builder_sidebar_select_filter = $this->builder_sidebar_select_filter;
        $builder_sidebar_content_elements = $this->builder_sidebar_content_elements;
        $builder_sidebar_content_element_class = $this->builder_sidebar_content_element_class;
        $builder_sidebar_edit = $this->builder_sidebar_edit;
        $builder_template = $this->builder_template;
        $menu_builder_control_buttons = $this->menu_builder_control_buttons;
        $menu_builder_pre_control_buttons = $this->menu_builder_pre_control_buttons;
        $builder_pre_heading_buttons = $this->builder_pre_heading_buttons;
        $af2_builder_custom_contents = $this->af2_builder_custom_contents;
        $af2_own_save_button_id = $this->af2_own_save_button_id;

        include FNSF_AF2_MENU_WRAPPER_VIEW;
    }

    public function do_request_handling() { return null; }
}


abstract class Fnsf_Af2MenuTable extends Fnsf_Af2Menu {
    
    public function __construct($Admin) {
        parent::__construct($Admin);
        $this->menu_type = 'table';
        
        $this->post_type_constant = $this->fnsf_get_post_type_constant();
    }

    protected function fnsf_load_all_values() {
        $this->heading = $this->fnsf_get_heading();

        $this->custom_template = $this->fnsf_get_custom_template_();

        $this->menu_functions_button = $this->fnsf_get_menu_functions_button_();
        $this->menu_functions_search = $this->fnsf_get_menu_functions_search_();
        $this->menu_functions_select = $this->fnsf_get_menu_functions_select_();

        $this->menu_action_button_add_post = null;
        $this->menu_action_button_copy_posts = $this->fnsf_get_menu_action_button_copy_posts_();
        $this->menu_action_button_delete_posts = $this->fnsf_get_menu_action_button_delete_posts_();
        $this->menu_hook_inline_checkbox = $this->fnsf_get_menu_hook_inline_checkbox_();
        $this->menu_hook_inline_search = $this->fnsf_get_menu_hook_inline_search_();
        $this->menu_hook_extra_title = $this->fnsf_get_menu_hook_extra_title_();
        $this->menu_blur_option = $this->fnsf_get_menu_blur_option_();
        $this->menu_hook_inline_button_form = $this->fnsf_get_menu_hook_inline_button_form_();
        $add_post_array = $this->fnsf_get_menu_action_button_add_post_();
        if(isset($add_post_array) && is_array($add_post_array) && sizeof($add_post_array) == 3 
            && isset($add_post_array['page']) && isset($add_post_array['post_type']) && isset($add_post_array['builder'])) {
            $this->menu_action_button_add_post = admin_url('/admin.php?page='.$add_post_array['page'].'&action=af2CreatePost&custom_post_type='.$add_post_array['post_type'].'&redirect_slug='.$add_post_array['builder'].'&time='.time());
        }

        $this->table_builder_load_url = null;
        $this->table_builder_load_url_id = null;
        $table_builder_load_array = $this->fnsf_get_table_builder_load_array_();
        if(isset($table_builder_load_array) && is_array($table_builder_load_array) && sizeof($table_builder_load_array) == 2 
            && isset($table_builder_load_array['page']) && isset($table_builder_load_array['id_label']) ) {
            $this->table_builder_load_url = admin_url('/admin.php?page=').$table_builder_load_array['page'].'&id=';
            $this->table_builder_load_url_id = $table_builder_load_array['id_label'];
        }

        $this->table_builder_load_url_ids_array = null;
        $table_builder_load_array_ids = $this->fnsf_get_table_builder_load_array_ids_();
        if(isset($table_builder_load_array_ids) && is_array($table_builder_load_array_ids)) {
            $this->table_builder_load_url_ids_array = array();
            foreach($table_builder_load_array_ids as $field) {
                if(isset($field['page']) && isset($field['id_label']) && isset($field['id_param']) ) {
                    $array = array();
                    $array['page'] = admin_url('/admin.php?page=').$field['page'].'&'.$field['id_param'].'=';
                    $array['id'] = $field['id_label'];

                    array_push($this->table_builder_load_url_ids_array, $array);
                }
            }
        }
            
        $this->table_columns = $this->fnsf_get_table_columns();
    }

    protected function fnsf_load_resources() {
        require_once FNSF_AF2_RESOURCE_HANDLER_PATH;
        load_basic_admin_menu_resources();
    }

    public function fnsf_get_content() {
        $this->Admin->fnsf_af2_delete_drafts($this->post_type_constant);
        $this->posts = $this->fnsf_edit_posts_for_table($this->get_posts());
        parent::fnsf_get_content();
    }

    abstract protected function fnsf_get_heading();
    protected function fnsf_get_menu_functions_button_() { return null; }
    protected function fnsf_get_menu_functions_search_() { return null; }
    protected function fnsf_get_menu_functions_select_() { return null; }
    
    protected function fnsf_get_custom_template_() { return null; }

    protected function fnsf_get_menu_action_button_add_post_() { return null; }
    protected function fnsf_get_menu_action_button_copy_posts_() { return null; }
    protected function fnsf_get_menu_action_button_delete_posts_() { return null; }
    protected function fnsf_get_menu_hook_inline_checkbox_() { return null; }
    protected function fnsf_get_menu_hook_inline_search_() { return null; }
    protected function fnsf_get_menu_hook_extra_title_() { return null; }
    protected function fnsf_get_menu_hook_inline_button_form_() { return null; }
    protected function fnsf_get_menu_blur_option_() { return false; }
    abstract protected function fnsf_get_table_columns();
    protected function get_posts() { return $this->Admin->fnsf_af2_get_posts($this->post_type_constant); }
    abstract protected function fnsf_edit_posts_for_table($posts);
    abstract protected function fnsf_get_post_type_constant();

    protected function fnsf_get_table_builder_load_array_() { return null; }
    protected function fnsf_get_table_builder_load_array_ids_() { return null; }
}

abstract class Fnsf_Af2MenuCustom extends Fnsf_Af2Menu {

    public function __construct($Admin) {
        parent::__construct($Admin);
        $this->menu_type = 'custom';
    }

    protected function fnsf_load_all_values() {
        $this->heading = $this->fnsf_get_heading();
        $this->menu_functions_button = $this->fnsf_get_menu_functions_button_();
        $this->menu_functions_search = $this->fnsf_get_menu_functions_search_();
        $this->menu_functions_select = $this->fnsf_get_menu_functions_select_();


        $this->menu_hook_inline_checkbox = $this->fnsf_get_menu_hook_inline_checkbox_();
        $this->menu_hook_inline_search = $this->fnsf_get_menu_hook_inline_search_();
        $this->menu_hook_extra_title = $this->fnsf_get_menu_hook_extra_title_();
        $this->menu_hook_inline_button_form = $this->fnsf_get_menu_hook_inline_button_form_();

        $this->menu_custom_template = $this->fnsf_get_menu_custom_template();
        $this->af2_custom_contents = $this->fnsf_get_af2_custom_contents_();
        $this->show_sidebar = $this->fnsf_get_show_sidebar_();
        $this->menu_blur_option = $this->fnsf_get_menu_blur_option_();
    }

    protected function fnsf_load_resources() {
        require_once FNSF_AF2_RESOURCE_HANDLER_PATH;
        load_basic_admin_menu_resources();
    }

    abstract protected function fnsf_get_heading();
    protected function fnsf_get_menu_functions_button_() { return null; }
    protected function fnsf_get_menu_functions_search_() { return null; }
    protected function fnsf_get_menu_functions_select_() { return null; }


    protected function fnsf_get_menu_hook_inline_checkbox_() { return null; }
    protected function fnsf_get_menu_hook_inline_search_() { return null; }
    protected function fnsf_get_menu_hook_extra_title_() { return null; }
    protected function fnsf_get_menu_hook_inline_button_form_() { return null; }

    abstract protected function fnsf_get_menu_custom_template();
    protected function fnsf_get_af2_custom_contents_() { return null; }
    protected function fnsf_get_show_sidebar_() { return false; }
    protected function fnsf_get_menu_blur_option_() { return false; }
}

abstract class Fnsf_Af2MenuBuilder extends Fnsf_Af2Menu {
    public function __construct($Admin) {
        parent::__construct($Admin);
        $this->menu_type = 'builder';
    }

    protected function fnsf_load_all_values() { 
        $this->builder_heading = $this->fnsf_get_builder_heading();
        $this->close_editor_url = $this->fnsf_get_close_editor_url();
        $this->builder_sidebar_data = $this->fnsf_get_builder_sidebar_data_();
        $this->builder_sidebar_select_filter = $this->fnsf_get_builder_sidebar_select_filter_();
        $this->builder_sidebar_content_elements = $this->fnsf_get_builder_sidebar_content_elements_();
        $this->builder_sidebar_content_element_class = $this->fnsf_get_builder_sidebar_content_element_class_();
        $this->builder_sidebar_edit = $this->fnsf_get_builder_sidebar_edit_();
        $this->builder_template = $this->fnsf_get_builder_template();
        $this->menu_builder_control_buttons = $this->fnsf_get_menu_builder_control_buttons_();
        $this->menu_builder_pre_control_buttons = $this->fnsf_get_menu_builder_pre_control_buttons_();
        $this->builder_pre_heading_buttons = $this->fnsf_get_builder_pre_heading_buttons_();
        $this->af2_builder_custom_contents = $this->fnsf_get_af2_builder_custom_contents_();
        $this->af2_own_save_button_id = $this->fnsf_af2_own_save_button_id_();
        $this->menu_blur_option = $this->fnsf_get_menu_blur_option_();
    }


    protected function fnsf_get_af2_builder_custom_contents_() { return null; }
    protected function fnsf_get_menu_blur_option_() { return false; }
    protected function fnsf_af2_own_save_button_id_() { return 'fnsf_af2_save_post'; }

    protected function fnsf_load_resources() {
        require_once FNSF_AF2_RESOURCE_HANDLER_PATH;
        $builder_localize_array = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('af2_FE_nonce'),
            'admin_url' => admin_url('/admin.php?page='),
            'post_id' => $this->fnsf_get_builder_post_id_(),
            'af2_save_object' => $this->fnsf_get_builder_save_object_(), 
            'sidebar_elements' => $this->fnsf_get_builder_sidebar_edit_elements_(),
            'page' => sanitize_key($_GET['page']),
            'strings' => array(
                'speichern' => __('Save...', 'funnelforms-free'),
                'error' => __('An error has occurred! Click to display the error!', 'funnelforms-free'),
                'support' => __('An error has occurred! Please contact the support!', 'funnelforms-free'),
                'pro' => __('PRO', 'funnelforms-free'),
            )
        );
        load_basic_admin_builder_resources($builder_localize_array);

        wp_localize_script( $this->fnsf_get_builder_script(), $this->fnsf_get_builder_script_object_name(), $this->fnsf_get_builder_script_localize_array());
        wp_enqueue_style($this->fnsf_get_builder_style());
        wp_enqueue_script($this->fnsf_get_builder_script());
    }

    abstract public static function fnsf_save_function($content);

    abstract protected function fnsf_get_builder_heading();
    abstract protected function fnsf_get_close_editor_url();
    protected function fnsf_get_builder_sidebar_data_() { return null; }
    protected function fnsf_get_builder_sidebar_select_filter_() { return null; }
    protected function fnsf_get_builder_sidebar_content_elements_() { return null; }
    protected function fnsf_get_builder_sidebar_content_element_class_() { return ''; }
    protected function fnsf_get_builder_sidebar_edit_() { return null; }
    abstract protected function fnsf_get_builder_template();

    protected function fnsf_get_menu_builder_control_buttons_() { return null; }
    protected function fnsf_get_menu_builder_pre_control_buttons_() { return null; }
    protected function fnsf_get_builder_pre_heading_buttons_() { return null; }

    protected function fnsf_get_builder_sidebar_edit_elements_() { return array(); }
    protected function fnsf_get_builder_save_object_() { 
        $bsoID = sanitize_text_field($_GET['id']);
        if(!isset($bsoID)){
            return array();
        } 
        
        $associated_post = get_post( sanitize_text_field($_GET['id']));
        require_once FNSF_AF2_MISC_FUNCTIONS_PATH;
        $post_content_array = fnsf_af2_get_post_content($associated_post);
        return $post_content_array; 
    }
    protected function fnsf_get_builder_post_id_() {
        if(!isset($_GET['id'])) { return null; }
        return sanitize_text_field($_GET['id']);
    }

    abstract protected function fnsf_get_builder_script();
    abstract protected function fnsf_get_builder_style();
    abstract protected function fnsf_get_builder_script_object_name();
    abstract protected function fnsf_get_builder_script_localize_array();

}

; ?>
