<?php

require_once FNSF_AF2_MENU_PARENTS_CLASS;
class Fnsf_Af2Kontaktformulare extends Fnsf_Af2MenuTable {

    protected function fnsf_get_heading() { return __('Contact forms','funnelforms-free'); }
    protected function fnsf_get_menu_functions_search_() { return array('Contact form title (backend)', 'Contact form title (frontend)'); }
    protected function fnsf_get_menu_action_button_add_post_() { return array('page' => FNSF_KONTAKTFNSF_FORMULAR_SLUG, 'post_type' => FNSF_KONTAKTFNSF_FORMULAR_POST_TYPE, 'builder' => FNSF_KONTAKTFNSF_FORMULARBUILDER_SLUG); }
    protected function fnsf_get_menu_action_button_copy_posts_() { return true; }
    protected function fnsf_get_menu_action_button_delete_posts_() { return true; }
    protected function fnsf_get_post_type_constant() { return FNSF_KONTAKTFNSF_FORMULAR_POST_TYPE; }
    protected function fnsf_get_table_builder_load_array_() { return array( 'page' => FNSF_KONTAKTFNSF_FORMULARBUILDER_SLUG, 'id_label' => 'ID'); }
    protected function fnsf_get_table_columns() {
        require_once FNSF_AF2_CATEGORY_HANDLER_PATH;
        return array(
            array( 'lable' => 'ID', 'translate' => false, 'highlight' => false,                                 'width' => '110px',     'flex' => '',   'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => true),
            array( 'lable' => 'Contact form title (backend)', 'translate' => false, 'highlight' => true,      'width' => '',          'flex' => '1',  'max-width' => '', 'min-width' => '', 'button' => false, 'url' => true, 'uid' => false),
            array( 'lable' => 'Contact form title (frontend)', 'translate' => false, 'highlight' => false,    'width' => '',          'flex' => '1',  'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => false),
            array( 'lable' => 'Author', 'translate' => false, 'highlight' => false,                              'width' => '200px',     'flex' => '',   'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => false),
            array( 'lable' => 'Category', 'translate' => true, 'highlight' => false,   'width' => '',      'flex' => '0.5',  'min-width' => '', 'max-width'=> '', 'button' => false, 'uid' => false, 'select' => 
                array('selection_values' => fnsf_get_all_categories(), 'select_class' => 'af2_category_selection', 'empty_value' => __('Not assigned' , 'funnelforms-free'))
            ),
        ); 
        // return array(
        //     array( 'lable' => __('ID', 'funnelforms-free'), 'translate' => false, 'highlight' => false,                                 'width' => '110px',     'flex' => '',   'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => true),
        //     array( 'lable' => __('Contact form title (backend)', 'funnelforms-free'), 'translate' => false, 'highlight' => true,      'width' => '',          'flex' => '1',  'max-width' => '', 'min-width' => '', 'button' => false, 'url' => true, 'uid' => false),
        //     array( 'lable' => __('Contact form title (frontend)', 'funnelforms-free'), 'translate' => false, 'highlight' => false,    'width' => '',          'flex' => '1',  'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => false),
        //     array( 'lable' => __('Author', 'funnelforms-free'), 'translate' => false, 'highlight' => false,                              'width' => '200px',     'flex' => '',   'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => false),
        //     array( 'lable' => __('Category', 'funnelforms-free'), 'translate' => true, 'highlight' => false,   'width' => '',      'flex' => '0.5',  'min-width' => '', 'max-width'=> '', 'button' => false, 'uid' => false, 'select' => 
        //         array('selection_values' => fnsf_get_all_categories(), 'select_class' => 'af2_category_selection', 'empty_value' => 'Not assigned')
        //     ),
        // ); 

    }

    protected function fnsf_get_custom_template_() { return FNSF_AF2_CUSTOM_TEMPLATE_CATEGORY; }

    protected function fnsf_get_menu_functions_button_() { 
        return array(
            'icon' => 'fas fa-cog', 
            'label' => __('Edit categories','funnelforms-free'),
            'triggerId' => 'af2_open_categories_model',
            'modelTarget' => 'modal-categories',
            'dataAttributes' => array(
                'target' => 'modal-categories',
            )
        ); 
    }

    protected function fnsf_get_menu_functions_select_() {
        require_once FNSF_AF2_CATEGORY_HANDLER_PATH;
        $all_cats = fnsf_get_all_categories();

        if(isset($_GET['category_id'])){
            $get_menu_fselect  = sanitize_text_field($_GET['category_id']);
            $selectedWdt = intval($get_menu_fselect) ;
        }else{
            $selectedWdt  = 'all';
        }

        return array( 
            'title' => __('Category:', 'funnelforms-free'), 
            'id' => 'choose_category',
            'getattribute' => 'category_id',
            'link' => admin_url('/admin.php?page='.FNSF_KONTAKTFNSF_FORMULAR_SLUG),
            'selected' => $selectedWdt ,
            'all_label' => __('No category selected', 'funnelforms-free'),
            'options' => $all_cats,
        );
    }

    protected function fnsf_edit_posts_for_table($posts) {
        $new_posts = array();

        require_once FNSF_AF2_MISC_FUNCTIONS_PATH;
        foreach($posts as $post) {

            require_once FNSF_AF2_CATEGORY_HANDLER_PATH;
            $id = get_post_field('ID', $post );
            $category_id = fnsf_get_category_id_of_element($id);

            $post_content = fnsf_af2_get_post_content($post);

            // remove warnings
           /* $name = isset($post_content['name']) && !empty($post_content['name']) ? $post_content['name'] : __('Contact form title (backend)', 'funnelforms-free'); */

            if(isset($post_content['name']) && !empty($post_content['name']) ){
            $name = $post_content['name'] ;
            }else{
                $name  =  __('Contact form title (backend)', 'funnelforms-free') ;
            } 
            
           /* $cftitle = isset($post_content['cftitle']) && !empty($post_content['cftitle']) ? $post_content['cftitle'] : __('Contact form title (frontend)', 'funnelforms-free'); */

            if(isset($post_content['cftitle']) && !empty($post_content['cftitle']) ){
            $cftitle = $post_content['cftitle'] ;
            }else{
                $cftitle  =  __('Contact form title (backend)', 'funnelforms-free') ;
            } 

            $new_post = array();
            $new_post['ID'] = $id;
            $new_post['Contact form title (backend)'] = $name;
            $new_post['Contact form title (frontend)'] =  $cftitle;
            $new_post['Author'] = get_the_author_meta( 'display_name', get_post_field('post_author', $post ) );
            $new_post['Category'] = $category_id;

            /* $new_post['error'] = isset($post_content['error']) ? $post_content['error'] : false; */

            if(isset($post_content['error']) ){
            $new_post['error'] = $post_content['error'] ;
            }else{
                $new_post['error']  =  false;
            } 
            
            if(isset($_GET['category_id'])) {
                $catid_tab = sanitize_text_field($_GET['category_id']) ; 
                if(intval($catid_tab) === $category_id || $catid_tab === 'all') array_push($new_posts, $new_post);
            }
            else array_push($new_posts, $new_post);
        }

        return $new_posts;
    }


    protected function fnsf_load_resources() {
        wp_localize_script( 'af2_category_script', 'af2_category_object', array(
            'reload_url' => admin_url('/admin.php?page='.FNSF_KONTAKTFNSF_FORMULAR_SLUG.'&show_modal=show_category_modal'),
            'reload_url_nomodal' => admin_url('/admin.php?page='.FNSF_KONTAKTFNSF_FORMULAR_SLUG),
        ));
        wp_enqueue_script('af2_category_script');
        wp_enqueue_style('af2_category');

        parent::fnsf_load_resources();
    }
}