<?php

class Fnsf_Af2AdminHelper {

    public function __construct() {
        add_action('init', array($this, 'fnsf_af2_menu_get_hooks'));
    }

    public function fnsf_af2_menu_get_hooks() {

        if(isset($_GET['action'])){
            $action =  sanitize_text_field($_GET['action']);
        }else{
            $action =  null ;
        }

        switch($action) {
            case 'af2CreatePost': {
                if(!isset($_GET['custom_post_type'])) return;
                if(!isset($_GET['redirect_slug'])) return;
                $rslugHelperf = sanitize_text_field($_GET['redirect_slug']);
                $adptype = sanitize_text_field($_GET['custom_post_type']);
                $empty_array = urlencode(serialize(array("af2_valuable" => true)));
                $post_id = wp_insert_post(array('post_content' => $empty_array, 'post_type' => $adptype) );

                nocache_headers();
                wp_safe_redirect(admin_url('/admin.php?page='.$rslugHelperf.'&id='.$post_id ));
                exit;
                break;
            }
            case 'af2LoadPost': {
                if(!isset($_GET['post_id'])) return;
                $pidHelper   = sanitize_text_field($_GET['post_id']);
                $rslugHelper = sanitize_text_field($_GET['redirect_slug']);
                if(!isset($_GET['redirect_slug'])) return;
                wp_safe_redirect( admin_url('/admin.php?page='.$rslugHelper.'&id='.$pidHelper));
                exit;
                break;
            }
            default: {
                break;
            }
        }

        return;
    }


    public function fnsf_af2_delete_drafts( $post_type )
    {
        // exception

        if($post_type == FNSF_REQUEST_POST_TYPE) return;
        $posts = get_posts([
            'post_type' => $post_type,
            'post_status' => 'draft',
            'numberposts' => -1,
            'order'    => 'ASC'
        ]);

        for( $i = 0; $i < sizeof( $posts ); $i++ )
        {
            $id = get_post_field('ID', $posts[$i] );
            wp_delete_post( $id );
        }
    }

    public function fnsf_af2_get_posts( $post_type, array $parameters = array(), $order = 'ASC', $drafts = false, $numberposts = -1 ) {

        $pre_query_config = array(
            'post_type' => $post_type,
            'post_status' => 'any',
            'posts_per_page' => -1,
            'order'    => $order,
        );

        $query_config = array_merge($pre_query_config, $parameters);
        $query = new WP_Query($query_config);
        $posts = $query->posts;
        $all_posts = array();

        foreach($posts as $post) {
            if($post->post_status == 'draft' && !$drafts) {
                continue;
            }
            array_push($all_posts, $post);
        }

        $paged_posts = $all_posts;

        if(isset($parameters['paged']) && isset($parameters['offset']) && $numberposts != -1) {
            $paged_posts = array_slice( $all_posts, $parameters['offset'], $numberposts );
        }

        return $paged_posts;
    }

    public function fnsf_af2_convert_question_type( $question_type ) {
        switch($question_type) {
            case "af2_select": {
                return 'Single selection';
            }
            case "af2_multiselect": {
                return 'Multiple selection';
            }
            case "af2_textfeld": {
                return 'Text row';
            }
            case "af2_textbereich": {
                return 'Text area';
            }
            case "af2_datum": {
                return 'Nur in Pro Version';
            }
            case "af2_slider": {
                return 'Nur in Pro Version';
            }
            case "af2_content": {
                return 'Nur in Pro Version';
            }
            case "af2_dateiupload": {
                return 'Nur in Pro Version';
            }
            case "af2_dropdown": {
                return 'Nur in Pro Version';
            }
            case "af2_adressfeld": {
                return 'Nur in Pro Version';
            }
            case "af2_terminbuchung": {
                return 'Nur in Pro Version';
            }
            default: {
                return 'Kein Fragetyp';
            }
        }
    }

    public function fnsf_af2_get_question_type_resource_by_label($label) {
        $val = '';
        switch($label) {
            case 'Single selection': {
                $val = '/res/images/question_types/single_selection.png';
                break;
            }
            case 'Multiple selection': {
                $val = '/res/images/question_types/multiple_selection.png';
                break;
            }
            case 'Text row': {
                $val = '/res/images/question_types/text_row.png';
                break;
            }
            case 'Text area': {
                $val = '/res/images/question_types/text_area.png';
                break;
            }
            case 'Date': {
                $val = '/res/images/question_types/date.png';
                break;
            }
            case 'Slider': {
                $val = '/res/images/question_types/slider.png';
                break;
            }
            case 'HTML content': {
                $val = '/res/images/question_types/html.png';
                break;
            }
            case 'File upload': {
                $val = '/res/images/question_types/file_upload.png';
                break;
            }
            case 'Dropdown': {
                $val = '/res/images/question_types/dropdown.png';
                break;
            }
            case 'Address': {
                $val = '/res/images/question_types/address.png';
                break;
            }
            case 'Appointment booking': {
                $val = '/res/images/question_types/appointment_booking.png';
                break;
            }
            default: {
                break;
            }
        }

        return $val;
    }

    public function fnsf_af2_read_template( $template, $param ) {
        ob_start();
        include $template;
        return ob_get_clean();
    }

    public function fnsf_convert_datestring_to_array($date) {
        $date = strtotime($date);
        $year = date('Y', $date);
        $month = date('m', $date);
        $day = date('d', $date);
        $hour = date('H', $date);
        $minute = date('i', $date);
        $second = date('s', $date);

        return array(
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'hour' => $hour,
            'minute' => $minute,
            'second' => $second,
        );
    }

    public function fnsf_af2_save_post() {

        if ( !current_user_can( 'edit_others_posts' ) ) {
            die( 'Permission denied' );
        }

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'af2_FE_nonce' ) ) {
            die( 'Permission denied' );
        }

        $obj = sanitize_text_field($_POST);
        $unsanitizedObj = json_decode(stripslashes($_POST['json']), true);
        $helpPostjson = sanitize_text_field($_POST['json']) ;
        if(isset($helpPostjson)) {
            $obj = json_decode(stripslashes($helpPostjson), true);
        }

        if($obj['page'] == FNSF_KONTAKTFNSF_FORMULARBUILDER_SLUG || $obj['page'] == FNSF_KONTAKTFNSF_FORMULARBUILDER_SETTINGS_SLUG) {
            $allowed_html = array(
                'a'     => array(
                    'href' => array()
                )
            );

            $i = 0;
            foreach($obj['content']['questions'] as $question) {
                if($question['typ'] == 'checkbox_type') {
                    $obj['content']['questions'][$i]['text'] = wp_kses($unsanitizedObj['content']['questions'][$i]['text'], $allowed_html);
                }
                $i++;
            }
        }

        if(isset($obj['post_id']) && isset($obj['content'])) {

            $post_content = $obj['content'];
            $post_content['af2_valuable'] = null;
            switch($obj['page']) {
                case FNSF_FRAGENBUILDER_SLUG: { $post_content = Fnsf_Af2Fragenbuilder::fnsf_save_function($obj['content']); break; }
                case FNSF_KONTAKTFNSF_FORMULARBUILDER_SLUG: { $post_content = Fnsf_Af2Kontaktformularbuilder::fnsf_save_function($obj['content']); break; }
                case FNSF_KONTAKTFNSF_FORMULARBUILDER_SETTINGS_SLUG: { $post_content = Fnsf_Af2KontaktformularbuilderSettings::fnsf_save_function($obj['content']); break; }
                case FNSF_FORMULARBUILDER_SLUG: {
                    $post_content = Fnsf_Af2Formularbuilder::fnsf_save_function($obj['content']);
                    break;
                }
                case FNSF_FORMULARBUILDER_SETTINGS_SLUG: { $post_content = Fnsf_Af2FormularbuilderSettings::fnsf_save_function($obj['content']); break; }
                default: {
                    break;
                }
            }

            wp_update_post(array('ID' => $obj['post_id'], 'post_status' => 'privat', 'post_title' => '', 'post_content' => urlencode(serialize($post_content))));
        }

        die();
    }

    public function fnsf_copy_posts() {

        if ( !current_user_can( 'edit_others_posts' ) ) {
            die( 'Permission denied' );
        }

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'af2_FE_nonce' ) ) {
            die( 'Permission denied' );
        }

        if( isset($_POST['post_ids']) && is_array($_POST['post_ids'])) {
            $copyPost = rest_sanitize_array($_POST['post_ids']);
            foreach($copyPost as $post_id) {
                $content = get_post_field( 'post_content', $post_id );
                $title = get_post_field( 'post_title', $post_id );
                $type = get_post_field( 'post_type', $post_id );

                $post_content_array = unserialize(urldecode($content));

                if($type == FNSF_FRAGE_POST_TYPE || $type == FNSF_KONTAKTFNSF_FORMULAR_POST_TYPE || $type == FNSF_FORMULAR_POST_TYPE){
                    $post_content_array['name'] = $post_content_array['name'].' - '.__('Copy', 'funnelforms-free');
                }

                $content = urlencode(serialize($post_content_array));

                wp_insert_post( array('post_title' => $title, 'post_content' => $content, 'post_status' => 'privat', 'post_type' => $type) );
            }
        }
        die();
    }

    public function fnsf_delete_posts() {

        if ( !current_user_can( 'edit_others_posts' ) ) {
            die( 'Permission denied' );
        }

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'af2_FE_nonce' ) ) {
            die( 'Permission denied' );
        }

        if( isset($_POST['post_ids']) && is_array($_POST['post_ids'])) {
             $deletePost = rest_sanitize_array($_POST['post_ids']);
             
            foreach($deletePost as $post_id) wp_delete_post( $post_id );
        }
        die();
    }

    
}
