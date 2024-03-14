<?php




function idea_push_update_user_profile(){
    
    $firstName = idea_push_sanitization_validation($_POST['firstName'],'name');
    $lastName = idea_push_sanitization_validation($_POST['lastName'],'name');
    $email = idea_push_sanitization_validation($_POST['email'],'email');
    // $password = idea_push_sanitization_validation($_POST['password'],'name');
    $boardNumber = intval($_POST['boardNumber']);

    if($firstName == false || $lastName == false || $email == false  || $boardNumber == false){
        wp_die(); 
    }
    
    

    $individualBoardSetting = idea_push_get_board_settings($boardNumber);
    $multiIp = $individualBoardSetting[27];

    if(!isset($multiIp)){
        $multiIp = 'No';
    }

    $currentUserId = idea_push_check_if_non_logged_in_user_is_guest($multiIp);
    

    //only proceed if theres a valid user id
    if($currentUserId !== false){
        wp_update_user( array( 'ID' => $currentUserId, 'first_name' => $firstName, 'last_name' => $lastName, 'user_email' => $email ));
    
        if(isset($_FILES['attachment'])){
                
            $uploadedFile = $_FILES['attachment']; 

            if ( ! function_exists( 'wp_handle_upload' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
            }



            $upload_overrides = array( 'test_form' => false );
            $moveFile = wp_handle_upload( $uploadedFile, $upload_overrides );



            if ( $moveFile && ! isset( $moveFile['error'] ) ) {

                $filePath = $moveFile['file'];
                $fileType = wp_check_filetype( basename( $filePath ), null );

                $wp_upload_dir = wp_upload_dir();

                $attachmentData = array(
                    'guid'           => $wp_upload_dir['url'] . '/' . basename( $filePath ), 
                    'post_mime_type' => $fileType['type'],
                    'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filePath ) ),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );

                $attach_id = wp_insert_attachment( $attachmentData, $filePath);

                require_once( ABSPATH . 'wp-admin/includes/image.php' );

                $attach_data = wp_generate_attachment_metadata($attach_id, $filePath);

                wp_update_attachment_metadata($attach_id, $attach_data);

                $imageURL = wp_get_attachment_image_src( $attach_id, 'full', false);
                
                update_user_meta( $currentUserId, 'ideaPushImage', $imageURL[0] );
                
            } else {

            }


        }
        
        echo $imageURL;
    }


    
    wp_die();    
}

add_action( 'wp_ajax_update_user_profile', 'idea_push_update_user_profile' );
add_action( 'wp_ajax_nopriv_update_user_profile', 'idea_push_update_user_profile' );



?>