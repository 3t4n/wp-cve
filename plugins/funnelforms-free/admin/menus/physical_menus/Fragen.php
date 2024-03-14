<?php

require_once FNSF_AF2_MENU_PARENTS_CLASS;
class Fnsf_Af2Fragen extends Fnsf_Af2MenuTable {

    protected function fnsf_get_heading() { return __('Questions', 'funnelforms-free'); }
    protected function fnsf_get_menu_functions_search_() { return "Question title"; }
    protected function fnsf_get_menu_action_button_add_post_() { return array('page' => FNSF_FRAGE_SLUG, 'post_type' => FNSF_FRAGE_POST_TYPE, 'builder' => FNSF_FRAGENBUILDER_SLUG); }
    protected function fnsf_get_menu_action_button_copy_posts_() { return true; }
    protected function fnsf_get_menu_action_button_delete_posts_() { return true; }
    protected function fnsf_get_post_type_constant() { return FNSF_FRAGE_POST_TYPE; }
    protected function fnsf_get_table_builder_load_array_() { return array( 'page' => FNSF_FRAGENBUILDER_SLUG, 'id_label' => 'ID'); }
    protected function fnsf_get_table_columns() {
        require_once FNSF_AF2_CATEGORY_HANDLER_PATH;
        return array(
            array( 'lable' => 'ID', 'translate' => false, 'highlight' => false,         'width' => '110px', 'flex' => '',     'min-width' => '', 'max-width'=> '', 'button' => false, 'uid' => true),
            array( 'lable' => 'Question title', 'translate' => false, 'highlight' => true, 'width' => '',      'flex' => '1',    'min-width' => '', 'max-width'=> '', 'button' => false, 'url' => true, 'uid' => false),
            array( 'lable' => 'Question type', 'translate' => true, 'highlight' => false,   'width' => '',      'flex' => '0.5',  'min-width' => '', 'max-width'=> '', 'button' => false, 'uid' => false),
            array( 'lable' => 'Author', 'translate' => false, 'highlight' => false,      'width' => '',      'flex' => '0.5',  'min-width' => '', 'max-width'=> '', 'button' => false, 'uid' => false),
            array( 'lable' => 'Category', 'translate' => true, 'highlight' => false,   'width' => '',      'flex' => '0.5',  'min-width' => '', 'max-width'=> '', 'button' => false, 'uid' => false, 'select' => 
                array('selection_values' => fnsf_get_all_categories(), 'select_class' => 'af2_category_selection', 'empty_value' => __('Not assigned' , 'funnelforms-free'))
            ),
        ); 
    }

    protected function fnsf_get_custom_template_() { return FNSF_AF2_CUSTOM_TEMPLATE_CATEGORY; }

    protected function fnsf_get_menu_functions_button_() { 
        return array(
            'icon' => 'fas fa-cog', 
            'label' => __('Edit categories', 'funnelforms-free'),
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
            $menusltCat = sanitize_text_field($_GET['category_id']);
            $selectedWdt = intval($menusltCat)  ;
        }else{
            $selectedWdt  = 'all';
        }

        return array( 
            'title' => __('Category:', 'funnelforms-free'), 
            'id' => 'choose_category',
            'getattribute' => 'category_id',
            'link' => admin_url('/admin.php?page='.FNSF_FRAGE_SLUG),
            'selected' => $selectedWdt,
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



            if(isset($post_content['name']) &&  !empty($post_content['name'])){
                $title = $post_content['name'] ; 
            }else{ 
                $title =  __('Question title', 'funnelforms-free') ; 
            }


            $new_post = array();
            $new_post['ID'] = $id;
            $new_post['Question title'] = $title;

            if(isset($post_content['typ'])){
                $new_post['Question type'] = $this->Admin->fnsf_af2_convert_question_type($post_content['typ']) ; 
            }else{ 
                $new_post['Question type'] =  'No question type selected!' ; 
            }

            $new_post['Author'] = get_the_author_meta( 'display_name', get_post_field('post_author', $post ) );
            $new_post['Category'] = $category_id;

           /* $new_post['error'] = isset($post_content['error']) ? $post_content['error'] : false; */

            if(isset($post_content['error'])){
                $new_post['error'] = $post_content['error'] ; 
            }else{ 
                $new_post['error'] =  false ;
            }

            if(isset($_GET['category_id'])) {
                $catID = sanitize_text_field($_GET['category_id']);
                if(intval($catID) === $category_id || $catID === 'all') array_push($new_posts, $new_post);
            }
            else array_push($new_posts, $new_post);
        }

        return $new_posts;
    }


    protected function fnsf_load_resources() {
        wp_localize_script( 'af2_category_script', 'af2_category_object', array(
            'reload_url' => admin_url('/admin.php?page='.FNSF_FRAGE_SLUG.'&show_modal=show_category_modal'),
            'reload_url_nomodal' => admin_url('/admin.php?page='.FNSF_FRAGE_SLUG),
        ));
        wp_enqueue_script('af2_category_script');
        wp_enqueue_style('af2_category');


        parent::fnsf_load_resources();
    }
}
