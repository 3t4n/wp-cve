<?php

require_once FNSF_AF2_MENU_PARENTS_CLASS;
class Fnsf_Af2Leads extends Fnsf_Af2MenuTable {

    public $pageOffset;
    public $numPages;
    public $pagination = true;

    protected function fnsf_get_heading() { return __('Leads','funnelforms-free'); }
    protected function fnsf_get_menu_action_button_delete_posts_() { return true; }
    protected function fnsf_get_post_type_constant() { return FNSF_REQUEST_POST_TYPE; }
    protected function get_posts() {
        $pageOffset = isset($_GET['page_offset']) ? sanitize_text_field($_GET['page_offset']) : 0;
        $form_id = isset($_GET['form_id']) ? sanitize_text_field($_GET['form_id']) : 'all';
        $draft = (isset($_GET['draft']) && $_GET['draft'] == 'true') ? true : false;

        $this->pageOffset = intval($pageOffset);
        $numPerPage = 10;
        $offset = $this->pageOffset * $numPerPage;

        $allPosts = $this->Admin->fnsf_af2_get_posts($this->post_type_constant, array(), 'DESC', $draft, -1);
        $total = $this->fnsf_af2_process_leads($allPosts, $form_id);
        $total = count($total);

        $this->numPages = ceil($total / $numPerPage);

        $posts = $this->Admin->fnsf_af2_get_posts($this->post_type_constant, array('offset' => $offset, 'paged' => $pageOffset), 'DESC', $draft, $numPerPage);

        return $posts;
    }
    protected function fnsf_get_table_builder_load_array_() { return array( 'page' => FNSF_LEADS_DETAILS_SLUG, 'id_label' => 'ID'); }
    protected function fnsf_get_menu_hook_inline_search_() { return "Name"; }

    protected function fnsf_get_menu_hook_inline_button_form_() { 
        // $menu_hook_form = sanitize_text_field($_GET['form_id']) ;
        if(isset($_GET['form_id'])) {
            $form_id = sanitize_text_field($_GET['form_id']) ;
            }else{
                $form_id  =  'all';
            } 
        return array( 
            'icon' => 'fas fa-download', 
            'label' => __('Export CSV file' , 'funnelforms-free'),  
            'id' => 'af2_export_csv', 
            'id_label' => 'form_id',
            'id_value' => $form_id,
            'bonus_label' => 'af2_lead_download',
            'bonus_value' => 'true',
            'bonus_class' => 'af2_btn_disabled af2_btn_disabled_free',
        ); 
    }
    protected function fnsf_get_menu_hook_extra_title_() { 

        if(isset($_GET['form_id']) ){
            $title = sanitize_text_field($_GET['form_id']) ;
            }else{
                $title  =  'all';
            } 
        if($title == 'all') {
            $title = __('All forms', 'funnelforms-free');
        }
        else {
            require_once FNSF_AF2_MISC_FUNCTIONS_PATH;
            $post = get_post( $title );
            $post_content = fnsf_af2_get_post_content($post);
            /* $title = isset($post_content['name']) && !empty($post_content['name']) ? $post_content['name'] : 'Form title'; */

            if(isset($post_content['name']) && !empty($post_content['name'])){
            $title = $post_content['name'] ;
            }else{
                $title  =  'Form title';
            } 
        }
        

        return array( 'label' => __('Form title' , 'funnelforms-free'), 'value' => $title); 
    }
    protected function fnsf_get_menu_functions_select_() { 

        $posts = $this->Admin->fnsf_af2_get_posts(FNSF_FORMULAR_POST_TYPE);

        $all_forms = array();

        require_once FNSF_AF2_MISC_FUNCTIONS_PATH;
        foreach($posts as $post) {
            $id = get_post_field( 'ID', $post );
            
            $post_content = fnsf_af2_get_post_content($post);

            // remove warnigns
           /*  $title = isset($post_content['name']) && !empty($post_content['name']) ? $post_content['name'] : __('Form title', 'funnelforms-free'); */


            if(isset($post_content['name']) && !empty($post_content['name']) ){
            $title = $post_content['name'] ;
            }else{
                $title  =   __('Form title', 'funnelforms-free') ;
            } 

            array_push($all_forms, array('value' => $id, 'label' => $title));
        }
        if(isset($_GET['form_id'])){
            $selectedWdt = sanitize_text_field($_GET['form_id']) ;
        }else{
            $selectedWdt  = 'all';
        }

        return array( 
            'title' => __('Select form:','funnelforms-free'), 
            'id' => 'form_id_chooser',
            'getattribute' => 'form_id',
            'link' => admin_url('/admin.php?page='.FNSF_LEADS_SLUG),
            'selected' =>  $selectedWdt ,
            'all_label' => __('All forms', 'funnelforms-free'),
            'options' => $all_forms,
        ); 
    }
    
    protected function fnsf_get_table_columns() {
        return array(
            array( 'lable' => 'ID', 'translate' => false, 'highlight' => true,                  'width' => '110px', 'flex' => '',  'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => true, 'url' => true),
            array( 'lable' => 'Date / Time', 'translate' => false, 'highlight' => false,    'width' => '230px', 'flex' => '',  'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => false, 'url' => true),
            array( 'lable' => 'Name', 'translate' => true, 'highlight' => false,                'width' => '',      'flex' => '1', 'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => false),
            array( 'lable' => 'E-mail', 'translate' => false, 'highlight' => false,             'width' => '',      'flex' => '1', 'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => false),
            array( 'lable' => 'Phone', 'translate' => false, 'highlight' => false,            'width' => '',      'flex' => '1', 'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => false),
            array( 'lable' => 'Details', 'translate' => true, 'highlight' => false,             'width' => '160px', 'flex' => '',  'max-width' => '', 'min-width' => '', 'button' => true, 'url' => true, 'buttonclass' => 'primary', 'uid' => false),
            array( 'lable' => 'post_status', 'hidden' => true, 'translate' => false, 'highlight' => false,  'width' => '160px', 'flex' => '',  'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => false),
        ); 
    }
    protected function fnsf_edit_posts_for_table($posts) {
            if(isset($_GET['form_id']) ){
                $form_id = sanitize_text_field($_GET['form_id']) ;
            }else{
                    $form_id  =  'all';
            }


        $leads = $this->fnsf_af2_process_leads($posts, $form_id);
            
        $new_posts = array();

        foreach($leads as $lead) {
            $new_post = array();
            $new_post['ID'] = $lead['_id'];
            $new_post['Date / Time'] = $lead['_date'];
            $new_post['Name'] = esc_html($lead['_name']);
            $new_post['E-mail'] = esc_html($lead['_mail']);
            $new_post['Phone'] = esc_html($lead['_phone']);
            $new_post['Details'] = esc_html($lead['_details']);
            $new_post['post_status'] = $lead['_post_status'];

            array_push($new_posts, $new_post);
        }

        return $new_posts;
    }

    private function fnsf_af2_process_leads($posts, $form_id, $detailed = false) {
        $new_posts = array();

        require_once FNSF_AF2_MISC_FUNCTIONS_PATH;
        foreach($posts as $post) {

            $post_content = fnsf_af2_get_post_content($post);

            // remove warnigns
            
            $name = '-';
            $email = '-';
            $phone = '-';

            if(isset($post_content['contact_form'])) {
                foreach($post_content['contact_form'] as $answer) {
                    if(isset($answer['typ']) && $answer['typ'] == 'text_type_name' && $name == '-') $name = $answer['input'];
                    if(isset($answer['typ']) && $answer['typ'] == 'text_type_phone' && $phone == '-') $phone = $answer['input'];
                    if(isset($answer['typ']) && $answer['typ'] == 'text_type_mail' && $email == '-') $email = $answer['input'];
                }    
            }

            
            
            $new_post = array();
            $new_post['_id'] = get_post_field('ID', $post );
            $new_post['_date'] = get_post_field('post_date', $post);
            $new_post['_name'] = $name;
            $new_post['_mail'] = $email;
            $new_post['_phone'] = $phone;
            $new_post['_details'] = __('Details' , 'funnelforms-free');
            $new_post['_post_status'] = get_post_field('post_status', $post);


            if( $detailed ) {
                $form_questions = array();
                $form_fields = array();
                $form_analytics = array();

                if(isset( $post_content['questions'] ) && is_array( $post_content['questions'] )) {
                    foreach( $post_content['questions'] as $field ) {
                        if(!is_array($field)) continue;
                        $new_field = array( 'label' => $field['frage'], 'value' => $field['antwort'], 'id' => $field['frage'] );
                        array_push($form_questions, $new_field);
                    }
                }

                if(isset( $post_content['contact_form'] ) && is_array( $post_content['contact_form'] )) {
                    foreach( $post_content['contact_form'] as $field ) {
                        if(!is_array($field)) continue;
                        $id = $field['id'];
                        if(isset($field['label']) && !empty($field['label'])){
                            $label = $field['label'] ;
                        }else{
                                $label = $field['id'];
                        }
                        /* $label = isset($field['label']) && !empty($field['label']) ? $field['label'] : $field['id']; */
                        $new_field = array( 'label' => $label, 'value' => $field['input'], 'id' => $id );
                        array_push($form_fields, $new_field);
                    }
                }
        
                if(isset( $post_content['analyticsData'] ) && is_array( $post_content['analyticsData'] )) {
                    foreach( $post_content['analyticsData'] as $field ) {
                        if(!is_array($field)) continue;
                        $label = $field['id'];
                        if($label == 'queryString') $label = __('Received URL parameters', 'funnelforms-free');
                        if($label == 'url') $label = __('URL on which the form was submitted', 'funnelforms-free');
                        $new_field = array( 'label' => $label, 'value' => $field['value'], 'id' => $field['id'] );
                        array_push($form_analytics, $new_field);
                    }
                }

                $detailed_array = array_merge($form_analytics, $form_fields, $form_questions);

                foreach( $detailed_array as $detail ) {
                    $new_post[$detail['id']] = $detail['value'];
                }

                $new_post['_af2_detailed'] = $detailed_array;
            }

            if($form_id == 'all' || (isset($post_content['form_id']) && $form_id == $post_content['form_id'])) array_push($new_posts, $new_post);
        }

        return $new_posts;
    }

    protected function fnsf_load_resources() {
        wp_enqueue_script('af2_leads');

        parent::fnsf_load_resources();
    }
}
