<?php

namespace DynamicContentForElementor;

/**
 * Main Helper Class
 *
 * @since 0.1.0
 */
class DynamicContentForElementor_Helper {

    public static function get_post_data($args) {
        $defaults = array(
            'posts_per_page' => 5,
            'offset' => 0,
            'category' => '',
            'category_name' => '',
            'orderby' => 'date',
            'order' => 'DESC',
            'include' => '',
            'exclude' => '',
            'meta_key' => '',
            'meta_value' => '',
            'post_type' => 'post',
            'post_mime_type' => '',
            'post_parent' => '',
            'author' => '',
            'author_name' => '',
            'post_status' => 'publish',
            'suppress_filters' => true
        );

        $atts = wp_parse_args($args, $defaults);

        $posts = get_posts($atts);

        return $posts;
    }

    public static function get_post_types() {
        $args = array(
            'public' => true
        );

        $skip_post_types = ['attachment','elementor_library','oceanwp_library'];

        $post_types = get_post_types($args);
        $post_types = array_diff($post_types,$skip_post_types);
        return $post_types;
    }

    public static function get_pages() {
        $args = array(
            'sort_order' => 'desc',
            'sort_column' => 'menu_order',
            'hierarchical' => 1,
            'exclude' => '',
            'include' => '',
            'meta_key' => '',
            'meta_value' => '',
            'authors' => '',
            'child_of' => 0,
            'parent' => -1,
            'exclude_tree' => '',
            'number' => '',
            'offset' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        $listPage = [];
        foreach ($pages as $page) {
            //$option = '<option value="' . get_page_link( $page->ID ) . '">';
            //$option .= $page->post_title;
            //$option .= '</option>';
            //echo $option;
            $listPage[$page->ID] = $page->post_title;
        }

        return $listPage;
    }

    public static function get_taxonomyterms() {
        $args = array(
            'public' => true,
            '_builtin' => false
        );
        $output = 'objects'; // or objects
        $operator = 'and'; // 'and' or 'or'
        $taxonomies = get_taxonomies($args, $output, $operator);
        $listTax = [];
        $listTax[''] = 'None';
        $listTax['category'] = 'Categories posts (category)';
        $listTax['post_tag'] = 'Tags posts (post_tag)';
        if ($taxonomies) {
            foreach ($taxonomies as $taxonomy) {
                //echo '<p>' . $taxonomy . '</p>';
                $listTax[$taxonomy->name] = $taxonomy->label . ' (' . $taxonomy->name . ')';
                //$listPage[$page->ID] = $page->post_title.$isparent;
            }
        }

        return $listTax;
    }

    public static function get_parentpages() {
        //
        $args = array(
            'sort_order' => 'DESC',
            'sort_column' => 'menu_order',
            'numberposts' => -1,
            // 'hierarchical' => 1,
            // 'exclude' => '',
            // 'include' => '',
            // 'meta_key' => '',
            // 'meta_value' => '',
            // 'authors' => '',
            // 'child_of' => 0,
            // 'parent' => -1,
            // 'exclude_tree' => '',
            // 'number' => '',
            // 'offset' => 0,
            'post_type' => $typesRegistered,
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        $listPage = [];

        foreach ($pages as $page) {

            $children = get_children('post_parent=' . $page->ID);
            $parents = get_post_ancestors($page->ID);
            $isparent = '';
            // !$parents &&
            if (count($children) > 0) {
                $isparent = ' (Parent)';
            }
            $listPage[$page->ID] = $page->post_title . $isparent;
        }

        return $listPage;
    }

    public static function get_post_settings($settings) {
        $post_args['post_type'] = $settings['post_type'];

        if ($settings['post_type'] == 'post') {
            $post_args['category'] = $settings['category'];
        }

        $post_args['posts_per_page'] = $settings['num_posts'];
        $post_args['offset'] = $settings['post_offset'];
        $post_args['orderby'] = $settings['orderby'];
        $post_args['order'] = $settings['order'];

        return $post_args;
    }

    public static function get_excerpt_by_id($post_id, $excerpt_length) {
        $the_post = get_post($post_id); //Gets post ID

        $the_excerpt = null;
        if ($the_post) {
            $the_excerpt = $the_post->post_excerpt ? $the_post->post_excerpt : $the_post->post_content;
        }

        // $the_excerpt = ($the_post ? $the_post->post_content : null);//Gets post_content to be used as a basis for the excerpt
        //echo $the_excerpt;
        $the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
        $words = explode(' ', $the_excerpt, $excerpt_length + 1);

        if (count($words) > $excerpt_length) :
            array_pop($words);
            //array_push($words, '…');
            $the_excerpt = implode(' ', $words);
            $the_excerpt .= '...';  // Don't put a space before
        endif;

        return $the_excerpt;
    }
// ************************************** ALL POST SINGLE IN ALL REGISTER TYPE ***************************/
public static function get_all_posts($myself = null, $group = false) {
        $args = array(
            'public' => true,
                //'_builtin' => false,
        );

        $output = 'names'; // names or objects, note names is the default
        $operator = 'and'; // 'and' or 'or'
        $posttype_all = get_post_types($args, $output, $operator);

        $type_excluded = array('elementor_library', 'oceanwp_library','ae_global_templates');
        $typesRegistered = array_diff($posttype_all, $type_excluded);
        // Return elementor templates array

        $templates[0] = 'None';

        $exclude_io = array( );
        if( isset($myself) && $myself ){
            //echo 'ei: '.$settings['exclude_io'].' '.count($exclude_io);
            $exclude_io = array( $myself );
        }

        $get_templates = get_posts(array('post_type' => $typesRegistered, 'numberposts' => -1, 'post__not_in' => $exclude_io, 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'DESC'));

        if (!empty($get_templates)) {
            foreach ($get_templates as $template) {
                
                if($group){
                    $templates[$template->post_type]['options'][$template->ID] = $template->post_title;
                    $templates[$template->post_type]['label'] = $template->post_type;
                }else{
                    $templates[$template->ID] = $template->post_title;
                }
            }
        }

        return $templates;
    }
// ************************************** ELEMENTOR ***************************/
    public static function get_all_template( $def=null ) {

        $type_template = array('elementor_library', 'oceanwp_library');

        // Return elementor templates array

        if( $def ){
            $templates[0] = 'Default';
            $templates[1] = 'NO';
        }else{
            $templates[0] = 'NO';
        }

        $get_templates = get_posts(array('post_type' => $type_template, 'numberposts' => -1, 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'DESC'));

        if (!empty($get_templates)) {
            foreach ($get_templates as $template) {
                $templates[$template->ID] = $template->post_title;
            }
        }

        return $templates;
    }

    public static function get_thumbnail_sizes() {
        $sizes = get_intermediate_image_sizes();
        foreach ($sizes as $s) {
            $ret[$s] = $s;
        }

        return $ret;
    }

    public static function get_post_orderby_options() {
        $orderby = array(
            'ID' => 'Post Id',
            'author' => 'Post Author',
            'title' => 'Title',
            'date' => 'Date',
            'modified' => 'Last Modified Date',
            'parent' => 'Parent Id',
            'rand' => 'Random',
            'comment_count' => 'Comment Count',
            'menu_order' => 'Menu Order',
        );

        return $orderby;
    }
    public static function get_placeholder_image_src($size=null) {
        $placeholder_image = DCE_URL . 'images/placeholder.jpg';

        return $placeholder_image;
    }
    public static function get_anim_timingFunctions() {
        
        $tf_p = [
            'none' => __( 'None', DCE_TEXTDOMAIN ),
            'linear' => __( 'Linear', DCE_TEXTDOMAIN ),
            'ease' => __( 'Ease', DCE_TEXTDOMAIN ),
            'ease-in' => __( 'Ease In', DCE_TEXTDOMAIN ),
            'ease-out' => __( 'Ease Out', DCE_TEXTDOMAIN ),
            'ease-in-out' => __( 'Ease In Out', DCE_TEXTDOMAIN ),
            
            'cubic-bezier(0.755, 0.05, 0.855, 0.06)' => __( 'easeInQuint', DCE_TEXTDOMAIN ),
            'cubic-bezier(0.23, 1, 0.32, 1)' => __( 'easeOutQuint', DCE_TEXTDOMAIN ),
            'cubic-bezier(0.86, 0, 0.07, 1)' => __( 'easeInOutQuint', DCE_TEXTDOMAIN ),

            'cubic-bezier(0.6, 0.04, 0.98, 0.335)' => __( 'easeInCirc', DCE_TEXTDOMAIN ),
            'cubic-bezier(0.075, 0.82, 0.165, 1)' => __( 'easeOutCirc', DCE_TEXTDOMAIN ),
            'cubic-bezier(0.785, 0.135, 0.15, 0.86)' => __( 'easeInOutCirc', DCE_TEXTDOMAIN ),
            
            'cubic-bezier(0.95, 0.05, 0.795, 0.035)' => __( 'easeInExpo', DCE_TEXTDOMAIN ),
            'cubic-bezier(0.19, 1, 0.22, 1)' => __( 'easeOutExpo', DCE_TEXTDOMAIN ),
            'cubic-bezier(1, 0, 0, 1)' => __( 'easeInOutExpo', DCE_TEXTDOMAIN ),

            'cubic-bezier(0.6, -0.28, 0.735, 0.045)' => __( 'easeInBack', DCE_TEXTDOMAIN ),
            'cubic-bezier(0.175, 0.885, 0.32, 1.275)' => __( 'easeOutBack', DCE_TEXTDOMAIN ),
            'cubic-bezier(0.68, -0.55, 0.265, 1.55)' => __( 'easeInOutBack', DCE_TEXTDOMAIN ),
        ];
        
        return $tf_p;
    }
    public static function getRoles($everyone = true) {
        $all_roles = wp_roles()->roles;
        //var_dump($all_roles); die();
        $ret = array();
        if ($everyone) {
            $ret['everyone'] = 'Everyone';
        }
        foreach ($all_roles as $key => $value) {
            $ret[$key] = $value['name'];
        }
        return $ret;
    }
    public static function getUserMeta( $idUser = 1 ) {
        $all_userMeta = get_user_meta( $idUser );
        //$all_userMeta = get_metadata('user',1);

        //var_dump($all_userMeta); die();
        $ret['none'] = 'None';
        foreach ($all_userMeta as $key => $value) {
            $ret[$key] = $key; //$value;
        }
        return $ret;
    }

    public static function getAll_acf($group = false) {

        $acfList = [];
        
        $acfList[0] = 'Select the Field';
        $tipo = 'acf-field';

        $get_templates = get_posts(array('post_type' => $tipo, 'numberposts' => -1, 'post_status' => 'publish', 'orderby' => 'title'));

        if (!empty($get_templates)) {
            ///print_r($template);
            /*
              foreach ( $this->get_acf_field() as $key => $value ) {
              //printf( '$idSelect = "%1$s";', get_settings('acf_field_list') );

              }
             */
            foreach ($get_templates as $template) {
                $gruppoAppartenenza = get_the_title($template->post_parent);
                //print_r($template);
                //$acfList[ $template->ID ] =  $template->post_parent .' - '. $template->ID .' - '. $template->post_title .' - '.$template->post_name; //post_name, post_title //print_r($template);
                $arrayField = maybe_unserialize($template->post_content);
                if ($arrayField['type'] == 'text' ||
                    $arrayField['type'] == 'textarea' ||
                    $arrayField['type'] == 'select' ||
                    $arrayField['type'] == 'number' ||
                    $arrayField['type'] == 'oembed' ||
                    $arrayField['type'] == 'file' ||
                    $arrayField['type'] == 'url' ||
                    $arrayField['type'] == 'image' ||
                    $arrayField['type'] == 'wysiwyg') {

                    if($group){
                        $acfList[$gruppoAppartenenza]['options'][$template->post_excerpt] = $template->post_title . '[' . $template->post_excerpt . '] (' . $arrayField['type'] . ')';
                        $acfList[$gruppoAppartenenza]['label'] = $gruppoAppartenenza;
                    }else{
                        $acfList[$template->post_excerpt] = $template->post_title . ' (' . $arrayField['type'] . ')'; //.var_export(maybe_unserialize($template->post_content), true); //post_name,
                    }
                    

                }
            }
        }
        /* $groupID = '109';
          $acfList = [];
          $fields = get_fields($groupID);

          $fields = get_field_objects();
          if( $fields )
          {
          foreach( $fields as $field_name => $field )
          {
          if( $field['value'] )
          {
          echo '<dl>';
          echo '<dt>' . $field['label'] . '</dt>';
          echo '<dd>' . $field['value'] . '</dd>';
          echo '</dl>';
          $acfList[ ] =  $field['value'];
          }
          }
          } */
        return $acfList;
    }
    public static function get_acf_field_urlfile() {
        $acfList = [];
        $acfList[0] = 'Select the Field';
        $tipo = 'acf-field';
        $get_templates = get_posts(array('post_type' => $tipo, 'numberposts' => -1, 'post_status' => 'publish'));
        if (!empty($get_templates)) {

            foreach ($get_templates as $template) {
                $gruppoAppartenenza = $template->post_parent;
                $arrayField = maybe_unserialize($template->post_content);

                if ( $arrayField['type'] == 'url' || $arrayField['type'] == 'file') {
                    $acfList[$template->post_excerpt] = $template->post_title . '(' . $arrayField['type'] . ')'; //.$template->post_content; //post_name,
                }
            }
        }
        return $acfList;
    }
}
