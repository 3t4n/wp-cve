<?php

require_once FNSF_AF2_MENU_PARENTS_CLASS;
class Fnsf_Af2Formulare extends Fnsf_Af2MenuTable{

    protected function fnsf_get_heading() { return __('Forms', 'funnelforms-free'); }
    protected function fnsf_get_menu_functions_search_() { return 'Form title (backend)'; }
    protected function fnsf_get_menu_action_button_add_post_() { 
        $formular_posts = $this->Admin->fnsf_af2_get_posts( FNSF_FORMULAR_POST_TYPE );

        if(sizeof($formular_posts) >= 5) return null;
        return array('page' => FNSF_FORMULAR_SLUG, 'post_type' => FNSF_FORMULAR_POST_TYPE, 'builder' => FNSF_FORMULARBUILDER_SLUG); 
    }
    protected function fnsf_get_menu_action_button_copy_posts_() { return true; }
    protected function fnsf_get_menu_action_button_delete_posts_() { return true; }
    protected function fnsf_get_post_type_constant() { return FNSF_FORMULAR_POST_TYPE; }
    protected function fnsf_get_table_builder_load_array_() { return array( 'page' => FNSF_FORMULARBUILDER_SLUG, 'id_label' => 'ID'); }
    protected function fnsf_get_table_builder_load_array_ids_() { return array( 
        array( 'page' => FNSF_LEADS_SLUG, 'id_param' => 'form_id', 'id_label' => 'ID' ) 
    ); }
    protected function fnsf_get_table_columns() {
        return array(
            array( 'lable' => 'ID', 'translate' => false, 'highlight' => false,                                     'width' => '110px', 'flex' => '',  'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => true),
            array( 'lable' => 'Form title (backend)', 'translate' => false, 'highlight' => true, 'url' => true,  'width' => '',      'flex' => '0.4', 'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => false),
            array( 'lable' => 'WordPress shortcode', 'translate' => true, 'highlight' => false,            'width' => '280px', 'flex' => '',  'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => false),
            array( 'lable' => 'Popup shortcode', 'translate' => true, 'highlight' => false,                         'width' => '190px', 'flex' => '0.4',  'max-width' => '', 'min-width' => '', 'button' => true, 'buttonclass' => 'secondary_outline', 'btn_disabled' => true, 'buttonid' => true, 'uid' => false),
            array( 'lable' => 'External embed code', 'translate' => true, 'highlight' => false,                'width' => '190px', 'flex' => '0.4',  'max-width' => '', 'min-width' => '', 'button' => true, 'buttonclass' => 'secondary_outline', 'btn_disabled' => true, 'buttonid' => true, 'uid' => false),
            array( 'lable' => 'Leads', 'translate' => true, 'highlight' => true,                                    'width' => '190px', 'flex' => '0.4',  'max-width' => '', 'min-width' => '', 'button' => true, 'buttonclass' => 'primary', 'url' => true, 'urlnum' => 0, 'uid' => false)
        );
    }
    protected function fnsf_edit_posts_for_table($posts) {
        $new_posts = array();

        require_once FNSF_AF2_MISC_FUNCTIONS_PATH;
        
        foreach($posts as $post) {

            $post_content = fnsf_af2_get_post_content($post);

            // remove warnigns
          /*  $title = isset($post_content['name']) && !empty($post_content['name']) ? $post_content['name'] : __('Form title', 'funnelforms-free'); */

            if(isset($post_content['name']) &&  !empty($post_content['name'])){
                $title = $post_content['name'] ; 
            }else{ 
                $title =  __('Form title', 'funnelforms-free') ; 
            }

            $new_post = array();
            $new_post['ID'] = get_post_field('ID', $post );
            $new_post['Form title (backend)'] = $title;
            $new_post['WordPress shortcode'] = '[funnelforms id="'.get_post_field('ID', $post ).'"]';
            $new_post['Popup shortcode'] = __('Generate','funnelforms-free');
            $new_post['External embed code'] = __('Generate','funnelforms-free');
            $new_post['Leads'] = __('Show leads','funnelforms-free');

          /*  $new_post['error'] = isset($post_content['error']) ? $post_content['error'] : false; */
            if(isset($post_content['error']) ){
                $new_post['error'] = $post_content['error'] ; 
            }else{ 
                $new_post['error'] =   false;
            }
            
            array_push($new_posts, $new_post);
        }

        return $new_posts;
    }


}
