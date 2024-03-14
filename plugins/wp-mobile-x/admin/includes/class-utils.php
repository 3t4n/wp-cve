<?php defined( 'ABSPATH' ) || exit;
class WPCOM_ADMIN_UTILS_FREE{
    public static function get_all_pages(){
        $pages = get_pages(array('post_type' => 'page','post_status' => 'publish'));
        $res = array();
        if($pages){
            foreach ($pages as $page) {
                $p = array(
                    'ID' => $page->ID,
                    'title' => $page->post_title
                );
                $res[] = $p;
            }
        }
        return $res;
    }

    public static function panel_script(){
        // Load CSS
        wp_enqueue_style('plugin-panel-free', WPCOM_ADMIN_FREE_URI . 'css/panel.css', false, WPCOM_ADMIN_FREE_VERSION, 'all');
        wp_register_style('material-icons', WPCOM_ADMIN_FREE_URI . 'css/material-icons.css', false, WPCOM_ADMIN_FREE_VERSION);
        wp_enqueue_style('material-icons');
        wp_enqueue_style( 'wp-color-picker' );

        // Load JS
        wp_enqueue_script('plugin-panel-free', WPCOM_ADMIN_FREE_URI . 'js/panel.js', array('jquery', 'jquery-ui-core', 'wp-color-picker'), WPCOM_ADMIN_FREE_VERSION, true);
        wp_enqueue_media();
    }

    public static function editor_settings($args = array()){
        return array(
            'textarea_name' => $args['textarea_name'],
            'textarea_rows' => $args['textarea_rows'],
            'tinymce'       => array(
                'height'        => 150,
                'toolbar1' => 'formatselect,fontsizeselect,bold,blockquote,forecolor,alignleft,aligncenter,alignright,link,unlink,bullist,numlist,fullscreen,wp_help',
                'toolbar2' => '',
                'toolbar3' => '',
            )
        );
    }

    public static function category( $tax = 'category', $filter = false ){
        $args = array(
            'taxonomy' => $tax,
            'hide_empty' => false
        );
        if($filter) $args['suppress_filter'] = true;
        $categories = get_terms($args);

        $cats = array();
        if( $categories && !is_wp_error($categories) ) {
            foreach ($categories as $cat) {
                $cats[$cat->term_id] = $cat->name;
            }
        }

        return $cats;
    }

    public static function allowed_html(){
        $tags = wp_kses_allowed_html('post');
        if(!isset($tags['form'])){
            $tags['form'] = array(
                'class' => true,
                'id' => true,
                'action' => true,
                'accept' => true,
                'accept-charset' => true,
                'enctype' => true,
                'method' => true,
                'name' => true,
                'target' => true
            );
        }
        if(!isset($tags['input'])){
            $tags['input'] = array(
                'class' => true,
                'id' => true,
                'name' => true,
                'value' => true,
                'type' => true,
                'placeholder' => true,
                'disabled' => true,
                'checked' => true,
                'maxlength' => true,
                'data-*' => true,
                'autocomplete' => true
            );
        }
        if(!isset($tags['select'])){
            $tags['select'] = array(
                'class' => true,
                'id' => true,
                'name' => true,
                'value' => true,
                'type' => true,
                'placeholder' => true,
                'disabled' => true,
                'checked' => true,
                'maxlength' => true,
                'data-*' => true,
                'autocomplete' => true
            );
            $tags['option'] = array(
                'name' => true,
                'value' => true,
                'type' => true,
                'disabled' => true,
                'selected' => true
            );
        }
        if(!isset($tags['textarea'])){
            $tags['textarea'] = array(
                'class' => true,
                'id' => true,
                'name' => true,
                'value' => true,
                'placeholder' => true,
                'disabled' => true,
                'maxlength' => true,
                'rows' => true,
                'data-*' => true,
            );
        }
        if(!isset($tags['button'])){
            $tags['button'] = array(
                'class' => true,
                'id' => true,
                'name' => true,
                'value' => true,
                'disabled' => true
            );
        }
        if(!isset($tags['i'])){
            $tags['i'] = array(
                'class' => true
            );
        }
        if(!isset($tags['svg'])){
            $tags['svg'] = array(
                'class' => true,
                'aria-hidden' => true
            );
        }
        if(!isset($tags['use'])){
            $tags['use'] = array(
                'xlink:href' => true
            );
        }
        if(isset($tags['div'])){
            $tags['div']['filter'] = true;
        }
        return $tags;
    }
}