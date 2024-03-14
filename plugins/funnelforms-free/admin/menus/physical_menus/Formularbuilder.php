<?php

require_once FNSF_AF2_MENU_PARENTS_CLASS;
class Fnsf_Af2Formularbuilder extends Fnsf_Af2MenuBuilder {

    public function __construct($Admin) {
        parent::__construct($Admin);
    }

    protected function fnsf_get_builder_heading() { return array('label' => 'Form editor', 'icon' => 'fas fa-edit'); }
    protected function fnsf_get_builder_sidebar_data_() { return array('label' => 'Elements', 'icon' => 'fas fa-atom'); }
    protected function fnsf_get_builder_template() { return FNSF_AF2_BUILDER_TEMPLATE_FORMULAR; }

    protected function fnsf_get_builder_sidebar_select_filter_() {
        require_once FNSF_AF2_CATEGORY_HANDLER_PATH;
        $all_cats = fnsf_get_all_categories();
        return array(
            'select_class' => 'af2_category_select',
            'empty_value' => 'All Categories',
            'selection_values' => $all_cats
        );
    }


    protected function fnsf_get_close_editor_url() { return admin_url('/admin.php?page='.FNSF_FORMULAR_SLUG ); }

    protected function fnsf_get_menu_builder_control_buttons_() { return array(
        array('id' => 'af2_goto_formularbuilder_settings', 'icon' => 'fas fa-cog', 'label' => __('Design & Settings', 'funnelforms-free')),
        array('id' => 'af2_sort_form_questions', 'icon' => 'fas fa-sync', 'label' => __('Sort','funnelforms-free'))
        ); }
    protected function fnsf_get_builder_sidebar_content_element_class_() { return 'af2_array_add_draggable'; }
    protected function fnsf_get_builder_pre_heading_buttons_() { return array(array('id' => 'af2_zoom_out', 'icon' => 'fas fa-minus'), array('id' => 'af2_zoom_in', 'icon' => 'fas fa-plus')); }

    protected function fnsf_af2_own_save_button_id_() { return 'af2_save_post_'; } 

    protected function fnsf_get_builder_sidebar_content_elements_() {

        require_once FNSF_AF2_CATEGORY_HANDLER_PATH;
        $elements_array = array();

        $frage_posts = $this->Admin->fnsf_af2_get_posts(FNSF_FRAGE_POST_TYPE);

        foreach($frage_posts as $post) {

            require_once FNSF_AF2_MISC_FUNCTIONS_PATH;
            $post_content = fnsf_af2_get_post_content($post);

            $id = get_post_field('ID', $post );
            $category_id = fnsf_get_category_id_of_element($id);
           /* $title = isset($post_content['name']) ? $post_content['name'] : '';  */

            if(isset($post_content['name'])){
                $title = $post_content['name'] ; 
            }else{ 
                $title = ''; 
            }
            /*$typ = isset($post_content['typ']) ? $this->Admin->fnsf_af2_convert_question_type($post_content['typ']) : null;  */
            if(isset($post_content['typ'])){ 
              $typ =   $this->Admin->fnsf_af2_convert_question_type($post_content['typ']) ;
            }else{
              $typ =   null ;
            }

            if($typ != null && $typ != 'Nur in Pro Version') {
                $post_array = array('label' => $title, 'image' => $this->Admin->fnsf_af2_get_question_type_resource_by_label($typ), 'elementid' => $id, 'select_value' => $category_id);
                if(!(isset($post_content['error']) && $post_content['error'] == 'true')) array_push($elements_array, $post_array);
            }
        }

        $kontaktformular_posts = $this->Admin->fnsf_af2_get_posts(FNSF_KONTAKTFNSF_FORMULAR_POST_TYPE);

        foreach($kontaktformular_posts as $post) {

            require_once FNSF_AF2_MISC_FUNCTIONS_PATH;
            $post_content = fnsf_af2_get_post_content($post);

            $id = get_post_field('ID', $post );
            $category_id = fnsf_get_category_id_of_element($id);
            /*$title = isset($post_content['name']) ? $post_content['name'] : '';*/
             if(isset($post_content['name'])){
                $title = $post_content['name'] ; 
            }else{ 
                $title = ''; 
            }
            $post_array = array('label' => $title, 'icon' => 'fas fa-envelope', 'elementid' => $id, 'select_value' => $category_id);
            if(!(isset($post_content['error']) && $post_content['error'] == 'true')) array_push($elements_array, $post_array);
        }

        array_push($elements_array, array('label' => __('URL redirect', 'funnelforms-free'), 'icon' => 'fas fa-external-link-alt', 'elementid' => 'redirect:'));

        return $elements_array;
    }

    protected function fnsf_get_builder_script() { return 'af2_formularbuilder'; }
    protected function fnsf_get_builder_style() { return 'af2_formularbuilder_style'; }
    protected function fnsf_get_builder_script_object_name() { return 'af2_formularbuilder_object';  }
    protected function fnsf_get_builder_script_localize_array() { 
        $bsID = sanitize_text_field($_GET['id']);
        return array(
            'own_id' => $bsID ,
            'fragen_contents' => $this->get_fragen_contents_for_element_sidebar(),
            'kontaktformular_contents' => $this->fnsf_get_kontaktformular_contents_for_element_sidebar(),
            'redirect_formularbuilder_settings_url' => admin_url('/admin.php?page='.FNSF_FORMULARBUILDER_SETTINGS_SLUG.'&id='.$bsID ),
            'standard_success_image' => plugins_url('/res/images/success_standard.png', AF2F_PLUGIN),
            'strings' => array(
                'addconnection' => __('Add connection', 'funnelforms-free'),
                'addcondition' => __('Add condition', 'funnelforms-free'),
                'redirect' => __('URL redirect', 'funnelforms-free'),
                'redirect_placeholder' => __('Enter URL...', 'funnelforms-free'),
                'redirect_checkbox' => __('Open in new window', 'funnelforms-free'),
                'no_element_error' => __('Element deleted', 'funnelforms-free'),
                'interface' => __('Interface', 'funnelforms-free'),
                'editinterface' => __('Edit', 'funnelforms-free'),
                'dealsnprojects:' => __('Only in Pro Version', 'funnelforms-free'),
                'activecampaign:' => __('Only in Pro Version', 'funnelforms-free'),
                'fincrm:' => __('Only in Pro Version', 'funnelforms-free'),
                'hubspot:' => __('Only in Pro Version', 'funnelforms-free'),
                'pipedrive:' => __('Only in Pro Version', 'funnelforms-free'),
                'getresponse:' => __('Only in Pro Version', 'funnelforms-free'),
                'question' => __('Question', 'funnelforms-free'),
                'contact_form' => __('Contact form', 'funnelforms-free'),
                'redirect' => __('Redirect', 'funnelforms-free'),
                'error' => __('Error', 'funnelforms-free'),
                'choose' => __('Choose...', 'funnelforms-free'),
                'success_text' => __('Thank you! The form was sent successfully!', 'funnelforms-free'),
            ),
        );
    }

    protected function fnsf_load_resources() {
        wp_enqueue_script('af2_interact_js');
        wp_enqueue_script('zoom');
        wp_enqueue_script('af2_svg_handler');
        wp_enqueue_script('af2_dragscroll');
        wp_enqueue_script('af2_drag_drop');
        
        parent::fnsf_load_resources();
    }

    protected function get_fragen_contents_for_element_sidebar() {
        $elements_array = array();

        $frage_posts = $this->Admin->fnsf_af2_get_posts(FNSF_FRAGE_POST_TYPE);

        foreach($frage_posts as $post) {

            require_once FNSF_AF2_MISC_FUNCTIONS_PATH;
            $post_content = fnsf_af2_get_post_content($post);
            $id = get_post_field('ID', $post );

            $post_array = array('elementid' => $id, 'content' => $post_content);

            if(!(isset($post_content['error']) && $post_content['error'] == 'true')) array_push($elements_array, $post_array);
        }

        return $elements_array;
    }

    protected function fnsf_get_kontaktformular_contents_for_element_sidebar() {
        $elements_array = array();

        $kontaktformular_posts = $this->Admin->fnsf_af2_get_posts(FNSF_KONTAKTFNSF_FORMULAR_POST_TYPE);

        foreach($kontaktformular_posts as $post) {

            require_once FNSF_AF2_MISC_FUNCTIONS_PATH;
            $post_content = fnsf_af2_get_post_content($post);
            $id = get_post_field('ID', $post );

            $post_array = array('elementid' => $id, 'content' => $post_content);

            if(!(isset($post_content['error']) && $post_content['error'] == 'true')) array_push($elements_array, $post_array);
        }

        return $elements_array;
    }


    public static function fnsf_save_function($content) {
        $own_content = $content;
        $echo_content = array();

        if(!isset($own_content['name']) || trim($own_content['name']) == '') {
            array_push($echo_content, array('label' => __('No title specified!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '#af2_goto_formularbuilder_settings'));
        }

        $keys = array('fe_title', 'global_next_text', 'global_prev_text');
        foreach($own_content['styling'] as $key => $value) {
            if(in_array($key, $keys)) continue;
            if(!isset($value) || trim($value) == '') {
                array_push($echo_content, array('label' => __('Design values can not be saved empty!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '#af2_goto_formularbuilder_settings'));
            }
        }

        if(isset($own_content['sections']) && is_array($own_content['sections']) && sizeof($own_content['sections']) > 6) {
            array_push($echo_content, array('label' => __('In the Free Version, a maximum of 6 elements can be arranged in a row!', 'funnelforms-free'), 'type' => 'af2_error'));
        }


        array_push($echo_content, array('label' => __('Saved successfully!', 'funnelforms-free'), 'type' => 'af2_success'));


        $own_content['error'] = false;

        foreach( $echo_content as $content ) {
            if($content['type'] == 'af2_error') {
                $own_content['error'] = true;
                break;
            }
        }

        echo json_encode($echo_content);
        return $own_content;
    }
}