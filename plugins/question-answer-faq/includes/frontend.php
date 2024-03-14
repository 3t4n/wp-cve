<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// ---------------------------------Permission --------------------------------------
function mideal_faq_permission( $roles ) {
    $allowed_roles = array( 'editor', 'administrator' );
    if( array_intersect($allowed_roles, $roles ) ) {
        return true;
    } else {
        return false;
    }
}

//------------------------------- Shortcode--------------------------------------------
add_shortcode('mideal-faq', 'mideal_faq_list');

function mideal_faq_list() {
    $mideal_faq_list = '<h2>'.__("List a question", "question-answer-faq").'</h2>';

    $user = wp_get_current_user();
    $user_faq_admin = mideal_faq_permission($user->roles);

// wp_dropdown_roles( get_option('default_role') ); 

    if($user_faq_admin=='true') {
        $post_status = 'any';
    } else {
        $post_status = 'publish';
    }

    $posts_per_page = get_option( 'mideal_faq_setting_pagination_number', 5);
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    $args = array(
        'posts_per_page' => $posts_per_page,
        'paged' => $paged, 
        'post_type' => 'mideal_faq',
        'orderby' => 'date',
        'order'   => 'DESC',
        'post_status' => $post_status
    );

    $faq_array = new WP_Query( $args );

    $mideal_faq_list .= '<ul id="mideal-faq-list" class="media-list">';
    if ( $faq_array->have_posts() ) {
        foreach ( $faq_array->posts as $key => $post ) {
            $mideal_faq_list .= "<li class='media-list-item";
            if( $post->post_status!="publish" ){
                $mideal_faq_list .= " no-published";
            }
            $mideal_faq_list .= "' data-id='".$post->ID."'>

            <div class='faq-header'><div class='faq-name'>".$post->post_title."</div><div class='faq-date'>".$post->post_date."</div></div>
            <div class='faq-question'>";
            $user_email = get_post_meta( $post->ID, 'mideal_faq_email', true );
            $url_default_avatar = MQA_PLUGIN_URL.'/img/avatar-default.png';
            $user_avatar_url = 'https://www.gravatar.com/avatar/'.md5( strtolower( trim( $user_email ) ) ).'?d='.$url_default_avatar.'&s=80';
            $mideal_faq_list .= "<img class='media-object chat-avatar' src='".$user_avatar_url."' alt='avatar'>
            <div class='chat-text' style='border-color:".get_option( 'mideal_faq_setting_question_background',"#eef1f5").";background:".get_option( 'mideal_faq_setting_question_background',"#eef1f5").";color:".get_option( 'mideal_faq_setting_question_color_text',"#444").";'>".nl2br($post->post_content)."</div>
            </div>";
            $answer_text = get_post_meta( $post->ID, 'mideal_faq_answer', true );
            if ($answer_text) {
                $mideal_faq_list .= "<div class='faq-answer'>
                <div class='faq-header'>".esc_attr( get_option( 'mideal_faq_setting_answer_name', __("Answer", "question-answer-faq")) )."</div>

                <div class='clearfix'></div>
                <img class='media-object chat-avatar' src='";if(get_option("mideal_faq_setting_answer_image")){$mideal_faq_list .= get_option("mideal_faq_setting_answer_image");}else{$mideal_faq_list .= MQA_PLUGIN_URL."/img/avatar-default.png";} $mideal_faq_list .= "' alt='avatar'>
                <div class='chat-text' style='border-color:".get_option( 'mideal_faq_setting_answer_background',"#3cb868").";background:".get_option( 'mideal_faq_setting_answer_background',"#3cb868").";color:".get_option( 'mideal_faq_setting_answer_color_text','#FFFFFF').";'>".nl2br($answer_text)."</div>
                </div>";
            }

            if( 'true' == $user_faq_admin ){
                $mideal_faq_list .= '<div class="mideal-faq-admin-btn">';
                if( $answer_text ) {
                    $text_btn_reply = __( "Edit", "question-answer-faq" );
                    $class_btn_action = 'mideal-answer-edit';
                } else {
                    $text_btn_reply = __( "Reply", "question-answer-faq" );
                    $class_btn_action = 'mideal-answer-reply';
                }
                $mideal_faq_list .= '<a target="_blanc" class="btn btn-xs btn-success '. $class_btn_action.'" data-id="'.$post->ID.'" href="/wp-admin/post.php?post='.$post->ID.'&action=edit">'.$text_btn_reply.'</a>';
                // echo '<a class="btn btn-xs btn-success" href="'.get_edit_post_link($post->ID).'">'.$text_btn_reply.'</a>';
                if($post->post_status == 'publish'){
                    $mideal_faq_list .= '<a class="btn btn-default btn-xs mideal-faq-publish-post" data-status="'.$post->post_status.'" data-id="'.$post->ID.'" href="#">'.__("Unpublish", "question-answer-faq").'</a>';
                } else {
                    $mideal_faq_list .= '<a class="btn btn-default btn-xs mideal-faq-publish-post" data-status="'.$post->post_status.'" data-id="'.$post->ID.'" href="#">'.__("Publish", "question-answer-faq").'</a>';
                }
                $mideal_faq_list .= '<a href="#" class="btn btn-xs btn-danger mideal-faq-delete-post" data-id="'.$post->ID.'">'.__( "Delete", "question-answer-faq" ).'</a>';
                $mideal_faq_list .= '</div>';
            }
            $mideal_faq_list .= "<hr>";
            $mideal_faq_list .= "</li>";
        }
    } else {
        $mideal_faq_list .= "<li class='media'>".__( "No question", "question-answer-faq" )."</li>";
    }


    //------------------------ Pagination ----------------------
    $big = 999999999;
    $pages = paginate_links(array(
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format' => '',
        'current' => $paged,
        'total' => $faq_array->max_num_pages,
        'type' => 'array',
        'prev_next' => true,
        'prev_text' => '<',
        'next_text' => '>',
            ));
    if( $pages ){
        $pages = str_replace( '/page/1/', '', $pages );
        $insert_style2 = " style='background:".get_option( 'mideal_faq_setting_pagination_color',"#3cb868").";border-color:".get_option( 'mideal_faq_setting_pagination_color',"#3cb868").";' aria-current";
        $pages = str_replace( 'aria-current', $insert_style2, $pages );
        $mideal_faq_list .= '<ul class="pagination">';
        foreach ( $pages as $i => $page ) {
            if ( $paged == 1 && $i == 0 ) {
                $mideal_faq_list .= "<li class='active'>$page</li>";
            } else {
                if ($paged != 1 && $paged == $i) {
                    $mideal_faq_list .= "<li class='active'>$page</li>";
                } else {
                    $mideal_faq_list .= "<li>$page</li>";
                }
            }
        }
        $mideal_faq_list .= '</ul>';
    }
    wp_reset_postdata();
    $mideal_faq_list .= "</ul>";

    return $mideal_faq_list;
}


// ------------------- add new question----------------

add_shortcode( 'mideal-faq-form', 'mideal_faq_form' );

function mideal_faq_form() {

    $form_mideal = '<h2>'.__( 'Add question', 'question-answer-faq' ).'</h2>
        <form id="form-mideal-faq">

        <div class="form-group">';
        if(!get_option( 'mideal_faq_setting_dont_show_label' )){
            $form_mideal .= '<label>'.__("Name", "question-answer-faq").'<span class="red">*</span>:</label>';
        }
        $form_mideal .= '<input type="text" name="mideal_faq_name" class="form-control" placeholder="'.__("Name", "question-answer-faq").'">
        </div>
        <div class="form-group">';
        if(!get_option( 'mideal_faq_setting_dont_show_label' )){
            $form_mideal .='<label>'.__("E-mail", "question-answer-faq").'<span class="red">*</span>:</label>';
        }
        $form_mideal .= '<input type="text" name="mideal_faq_email" class="form-control" placeholder="'.__("Your E-mail", "question-answer-faq").'">
        </div>
        <div class="form-group">';
        if(!get_option( 'mideal_faq_setting_dont_show_label' )){
            $form_mideal .='<label>'.__("Question", "question-answer-faq").'<span class="red">*</span>:</label>';
        }
        $form_mideal .= '<textarea name="mideal_faq_question" class="form-control" placeholder="'.__("Your question", "question-answer-faq").'"></textarea>
        </div>';

    if(get_option( 'mideal_faq_setting_recaptcha' )){
        $form_mideal .= '<div class="form-group">
            <div class="g-recaptcha" data-sitekey="'.get_option( 'mideal_faq_setting_recaptcha_key').'"></div>
            </div>';
    }
    
    if(get_option( 'mideal_faq_setting_button_big_size' )){
        $class_big_size=" big-size";
    }else{
        $class_big_size="";
    }
    $form_mideal .= '<div class="form-group sent-group">
        <div class="message-error-sent"></div>
        <input class="btn btn-primary sent-mideal-faq'.$class_big_size.'" style="color:'.get_option( 'mideal_faq_setting_button_color_text',"#FFFFFF").';background:'.get_option( 'mideal_faq_setting_button_background',"#3cb868").';" type="submit" value="'.__("Ask a question", "question-answer-faq").'">
        </div>

        </form>';
        return $form_mideal;
}


// ------------------- Add post ajax----------------
if( defined('DOING_AJAX') && DOING_AJAX ) {
    add_action('wp_ajax_mideal_faq_add', 'mideal_faq_add_callback');
    add_action('wp_ajax_nopriv_mideal_faq_add', 'mideal_faq_add_callback');
}

function mideal_faq_add_callback() {
    $nonce = $_POST['nonce'];

    if ( ! wp_verify_nonce( $nonce, 'midealfaqajax-nonce' ) ){
        die ( 'Stop!');
    }

    if(get_option( 'mideal_faq_setting_recaptcha' )){
        if (!$_POST["g-recaptcha-response"]) {
            die ( 'norecaptcha');
        }

        $secret = get_option( 'mideal_faq_setting_recaptcha_key_secret');
        $response=$_POST["g-recaptcha-response"];
        $verify=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
        $captcha_success=json_decode($verify);

        if ($captcha_success->success==false) {
            die ( 'norecaptcha');
        }
    }

    $post_data = array(
        'post_title'    => sanitize_text_field( $_POST['mideal_faq_name'] ),
        'post_content'  => sanitize_textarea_field($_POST['mideal_faq_question']),
        'post_status'   => 'pending',
        'post_type'  => 'mideal_faq',
    );

    $post_id = wp_insert_post( $post_data );
    if( $post_id ){
        if( is_email( $_POST['mideal_faq_email'] ) ){
            $user_email = sanitize_email( $_POST['mideal_faq_email']);
            update_post_meta( $post_id, 'mideal_faq_email', $user_email );
        }


        //sent notification on email
        $sendto   = get_option('mideal_faq_setting_email',get_option('admin_email'));
        $subject  = __("New question on site", "question-answer-faq");

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html;charset=utf-8 \r\n";
        if(get_option('mideal_faq_setting_email2')){
            $headers .= "Cc: ".get_option('mideal_faq_setting_email2')." \r\n";
        }

        $username  = sanitize_text_field($_POST['mideal_faq_name']);
        $usermail = sanitize_email($_POST['mideal_faq_email']);
        $faq_content  = sanitize_textarea_field($_POST['mideal_faq_question']);
        $msg  = "<html><body style='font-family:Arial,sans-serif;'>";
        $msg .= "<h2 style='font-weight:bold;border-bottom:1px dotted #ccc;'>".__('New question on site', 'question-answer-faq').":</h2>\r\n";
        $msg .= "<p><strong>".__('Name', 'question-answer-faq').":</strong> ".$username."</p>\r\n";
        $msg .= "<p><strong>".__('E-mail', 'question-answer-faq').":</strong> ".$usermail."</p>\r\n";
        $msg .= "<p><strong>".__('Question', 'question-answer-faq').":</strong> ".nl2br($faq_content)."</p>\r\n";
        $msg .= "<p><strong><a href='".get_edit_post_link($post_id)."'>".__('Reply', 'question-answer-faq')."</a></strong></p>\r\n";
        $msg .= "</body></html>";

        wp_mail( $sendto, $subject, $msg, $headers );

    }
    wp_die();
}


// ------------------- Delete post ajax----------------

if( defined('DOING_AJAX') && DOING_AJAX ) {
    add_action('wp_ajax_mideal_faq_delete', 'mideal_faq_delete_callback');
}

function mideal_faq_delete_callback() {
    $nonce = $_POST['nonce'];
    $user = wp_get_current_user();
    $user_faq_admin = mideal_faq_permission($user->roles);

    if ( ! wp_verify_nonce( $nonce, 'midealfaqajax-nonce' ) ){
        die ( 'Stop!');
    }

    if ( $user_faq_admin!='true' ) {
        die ('Stop!');
    }

    if(intval($_POST['ID'])){
        wp_delete_post($_POST['ID'] );
    }
    wp_die();
}

// ------------------- Save post ajax----------------

if( defined('DOING_AJAX') && DOING_AJAX ) {
    add_action('wp_ajax_mideal_faq_save', 'mideal_faq_save_callback');
}

function mideal_faq_save_callback() {
    $nonce = $_POST['nonce'];
    $user = wp_get_current_user();
    $user_faq_admin = mideal_faq_permission($user->roles);

    if ( ! wp_verify_nonce( $nonce, 'midealfaqajax-nonce' ) ){
        die ( 'Stop!');
    }

     if ( $user_faq_admin!='true' ) {
         die ('Stop!');
     }


    $mideal_faq_answer = sanitize_textarea_field($_POST['mideal_faq_answer']);
    print_r($mideal_faq_answer);
    if(intval($_POST['ID'])){
        update_post_meta( $_POST['ID'], 'mideal_faq_answer', $mideal_faq_answer );
    }


    wp_die();
}



// ------------------- Publish post ajax----------------

if( defined('DOING_AJAX') && DOING_AJAX ) {
    add_action('wp_ajax_mideal_faq_publish', 'mideal_faq_publish_callback');
}

function mideal_faq_publish_callback() {
    $nonce = $_POST['nonce'];
    $user = wp_get_current_user();
    $user_faq_admin = mideal_faq_permission($user->roles);

    if ( ! wp_verify_nonce( $nonce, 'midealfaqajax-nonce' ) ){
        die ( 'Stop!');
    }

    if ( $user_faq_admin!='true' ) {
        die ('Stop!');
    }


    if( $_POST['post_status'] != 'publish'){
        if(intval($_POST['ID'])){
            wp_publish_post( $_POST['ID'] );
        }
    } else {
        if(intval($_POST['ID'])){
            $post_data = array(
                'ID'    => $_POST['ID'],
                'post_status'   => 'pending',
                'post_type'  => 'mideal_faq'
            );
            wp_update_post( $post_data );
        }
    }

    wp_die();
}