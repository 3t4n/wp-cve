<?php

// GET FEATURED IMAGE
function pin_generator_get_featured_image($post_ID) {
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);
    if ($post_thumbnail_id) {
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'featured_preview');
        return $post_thumbnail_img[0];
    }
}

// ADD NEW COLUMN
function pin_generator_columns_head($defaults) {
    $defaults['pin_generator_column'] = 'Pin Generator';
    return $defaults;
}
add_filter('manage_posts_columns', 'pin_generator_columns_head');

// SHOW THE PIN GENERATOR COLUMN CONTENT
function pin_generator_columns_content($column_name, $post_ID) {

    $placeholder_pin_image = PIN_GENERATOR_PLUGIN_URL . "assets/pin-placeholder.jpg";

    if ($column_name == 'pin_generator_column') {
        $post_pin_image = get_post_meta($post_ID, "pingen_pin_image_url", true);
        $post_featured_image_id = get_post_thumbnail_id( $post_ID);
        //$pin_image_upload = get_site_url() . "wp-admin/upload.php" .

        if($post_pin_image == ""){
            $post_pin_image = $placeholder_pin_image;
        }

        $pg_html = '<div class="pg-outer-div">';
        $pg_html = '<div class="pg-div">';

        // Pin title field
        $pg_html .= '<a id="pinGenImageAnchor'. esc_attr($post_ID) . '" href="'. esc_url($post_pin_image) .'" target="_blank">';
        $pg_html .= '<img id="pinGenImage'. esc_attr($post_ID) . '" src="' . esc_url($post_pin_image) . '" class="pin-image" onerror="this.onerror=null;this.src=\'' . esc_url($placeholder_pin_image) . '\';" />';
        $pg_html .= '</a>';

        $pg_html .= '<div class="pg-buttons">';

        $pg_html .= '<form id="pinTitleForm'. esc_attr($post_ID) . '" class="pg-title-form">';
        $pg_html .= '<input type="text" id="pinTitle'. esc_attr($post_ID) . '" class="pg-pin-title-input" name="pinTitle" value="' . sanitize_text_field(get_post_meta($post_ID, "pingen_pin_text", true)) . '" placeholder="Input the pin title">';
        $pg_html .= '<button type="submit" class="pg-save-button" value="'. esc_attr($post_ID) .'">Save</button>';
        $pg_html .= '</form>';

        // Show pin checkbox
        $isChecked = get_post_meta($post_ID, "pingen_show_pin", true) == true ? "checked" : "";
        $pg_html .= '<div>';
        $pg_html .= '<input type="checkbox" id="showPinCheckbox'. esc_attr($post_ID) . '" class="show-pin-checkbox" name="show_pin_checkbox'. esc_attr($post_ID) .'" value="'. esc_attr($post_ID) .'" ' . esc_attr($isChecked) . '><label for="show_pin_checkbox'. esc_attr($post_ID) .'"> Show pin in post</label><br>';
        $pg_html .= '</div>';

        $pg_html .= '<div>';
        $pg_html .= '<button type="button" class="pg-generate-button" value="'. esc_attr($post_ID) .'">Generate new pin</button>';

        // Get settings page url
        $pg_settings_page_url = esc_url( add_query_arg(
            'page',
            'pin-generator-settings',
            get_admin_url() . 'admin.php'
        ) );

        $pg_html .= '<a href="' . $pg_settings_page_url . '" target="_blank"><span class="dashicons dashicons-admin-settings"></span></a>';
        $pg_html .= '</div>';

       $pg_html .= '</div>';

        $pg_html .= '</div>'; //pg-buttons
        $pg_html .= '</div>'; //pg-div

        $pg_html .= '<div id="statusDiv'. esc_attr($post_ID) . '">';
        $pg_html .= '</div>';

        $pg_html .= '</div>'; //pg-outer-div

        // Escape
        $arr = array(   'div' => array(
                            'id' => array(),
                            'class' => array(),
                        ), 
                        'a' => array(
                            'id' => array(),
                            'href' => array(),
                            'target' => array()
                        ), 
                        'img' => array(
                            'id' => array(),
                            'src' => array(),
                            'class' => array(),
                            'onerror' => array()
                        ), 
                        'form' => array(
                            'id' => array(),
                            'class' => array()
                        ), 
                        'input' => array(
                            'type' => array(),
                            'id' => array(),
                            'class' => array(),
                            'name' => array(),
                            'value' => array(),
                            'placeholder' => array(),
                            'checked' => array(),
                        ), 
                        'label' => array(
                            'for' => array()
                        ),
                        'br' => array(),
                        'span' => array(
                            'class' => array()
                        ),
                        'button' => array(
                            'type' => array(),
                            'class' => array(),
                            'value' => array()
                        ) );
        echo wp_kses($pg_html, $arr);
    }
}
add_action('manage_posts_custom_column', 'pin_generator_columns_content', 10, 2);

function pin_generator_save_pin_title(){
    // Validate
    if(is_string($_POST["pinTitle"]) && is_string($_POST["postID"])){
        update_post_meta( sanitize_text_field($_POST["postID"]) , 'pingen_pin_text', sanitize_text_field($_POST["pinTitle"]));
    }else{
        wp_send_json_error('Please use valid input for title', 400) ;
    }
    
    die();
}
add_action('wp_ajax_pin_generator_save_pin_title', 'pin_generator_save_pin_title');

function pin_generator_save_show_pin(){
    // Validate
    if($_POST["showPin"] == "true" || $_POST["showPin"] == "false" && is_string($_POST["postID"])){
        update_post_meta( sanitize_text_field($_POST["postID"]) , 'pingen_show_pin', rest_sanitize_boolean($_POST["showPin"]));
    }else{
        wp_send_json_error('Please use valid input for a checkbox', 400) ;
    }

    die();
}
add_action('wp_ajax_pin_generator_save_show_pin', 'pin_generator_save_show_pin');

function pin_generator_generate_pin(){

    // Sanitize inputs
    $post_ID = sanitize_text_field($_POST["postID"]);
    $post_title = sanitize_text_field($_POST["pinTitle"]);

    // Validate incoming variables
    if(is_string($post_title) && is_string($post_ID)){

        // Create short site url
        $site_url = get_site_url();
        $short_url = is_string(explode('//', $site_url)[1]) ? explode('//', $site_url)[1] : "";

        $design_options = get_option("pin_generator_design_settings");

        // Get color
        $color_without_hash = substr($design_options['color'], 1 );

        // Get template
        $template = $design_options['template'];

        // Get the posts featured image
        $post_featured_image = pin_generator_get_featured_image($post_ID);
        
        // Get post URL
        $post_permalink = wp_http_validate_url(get_permalink($post_ID)) ? get_permalink($post_ID) : "";
        
        // Get access key from options
        $pin_gen_access_key = is_string(get_option("pin_generator_access_key")) ? get_option("pin_generator_access_key") : "";
        
        //http://localhost:5002/pingenerator-43a15/europe-west3
        //https://europe-west3-pingenerator-43a15.cloudfunctions.net
        $pg_url = 'https://europe-west3-pingenerator-43a15.cloudfunctions.net/highMemLimitApi/scraping/getSinglePin?' . 
                    'template=' . $template .
                    '&title=' . urlencode($post_title) . 
                    '&imageURL=' . urlencode($post_featured_image) .
                    '&URL='. $post_permalink .
                    '&URLText=' . $short_url . 
                    '&color=' . $color_without_hash;

        $args = array(
            'headers' => array(
                'access_key' => $pin_gen_access_key,
                'timeout' => 20,
                'sslverify' => false
            )
        );

        // Generate pin from PG server
        $response = wp_remote_get($pg_url, $args);

        if(is_wp_error($response)){
            wp_send_json_error('ERROR: ' . $response->get_error_message(), 403);
        }else{
            $response_code = wp_remote_retrieve_response_code($response);

            if($response_code == 401){
                wp_send_json_error('User account not found. Sign up for a free Pin Genertor account <a href="https://pingenerator.com/signup" target="_blank">here</a>', 401) ;
            }
            else if($response_code == 402){
                wp_send_json_error('Pin limit reached. Please update your pin limit <a href="https://pingenerator.com/pricing" target="_blank">here</a>', 402) ;
            }else if($response_code == 403){
                wp_send_json_error('Please use valid access key. You can get a FREE access key from <a href="https://pingenerator.com/profile" target="_blank">your Pin Generator profile page</a>', 403) ;
            }else if($response_code == 500){
                wp_send_json_error('Server error', 500) ;
            }else{
                $type = wp_remote_retrieve_header( $response, 'content-type' );

                if (!$type)
                    return false;
            
                // Save image
                $imageName = $post_title . '-generated-pin-' . $post_ID . ".jpeg";

                if(is_string($imageName)){
                    $mirror = wp_upload_bits( $imageName, null, wp_remote_retrieve_body( $response ) );
                }
                
                $generated_image_url = $mirror['url'];
                
                if($mirror['error']){
                    wp_send_json_success($mirror['error']);
                } else {
                    // echo $mirror['file'];
                    // echo $mirror['url'];
                    // echo $mirror['type'];
            
                    $attachment = array(
                        'post_title'=> $post_title,
                        'post_mime_type' => $type
                    );
                
                    $attach_id = wp_insert_attachment( $attachment, $mirror['file'], intval($post_ID) );
                
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                
                    $attach_data = wp_generate_attachment_metadata( $attach_id, $mirror['file'] );
                
                    wp_update_attachment_metadata( $attach_id, $attach_data );
                
                    //return $attach_id;
                
                    // Add image to post meta
                    if(is_string($generated_image_url)){
                        update_post_meta(intval($post_ID), 'pingen_pin_image_url', esc_url_raw( $generated_image_url ));
                    }
                    
                
                    // Return the new generated pin image url to the ajax function
                    wp_send_json_success( $generated_image_url );
                }
            }
        }
    }else{
        wp_send_json_error('Please use valid values when generating a pin', 400) ;
    }

    wp_die();
}
add_action('wp_ajax_pin_generator_generate_pin', 'pin_generator_generate_pin');