    
<form id="the-drop" method="post" action="#" enctype="multipart/form-data"  >
    <div id="the-dropped" class="dropzone">
        <div class="dz-message">
            <span class="upload-icon dashicons dashicons-images-alt2"></span>
            <p><?php _e('Drag and Drop images here or click to upload.','bulk-images-to-posts'); ?></p>
        </div>
        </div>
          <div class="fallback">
              <input type="file" name="bipImage" id="bip_upload"  multiple="true" />
    <input class="btn" id="submit_bip_upload" name="submit_bip_upload" type="submit" value="Upload" />
  </div>
    
    <input type="hidden" name="bipSubmitted" id="bipSubmitted" value="true" />
    <?php wp_nonce_field( 'bip_upload', 'bip_upload_nonce' ); ?>
</form>

<?php   
    if ( isset( $_POST['bipSubmitted'] ) 
    && isset( $_POST['bip_upload_nonce'] ) 
    && wp_verify_nonce( $_POST['bip_upload_nonce'], 'bip_upload' ) ) { 
    
        // Let WordPress handle the upload.
        // Remember, 'bip_upload' is the name of our file input in our form above.
        // $attachment_id = media_handle_upload( 'bipImage', 0 );
    
        // $attachment = get_post( $attachment_id );
        // $uploadTitleType = get_option('bip_image_title');
        // if ( $uploadTitleType == 0) {
        //     $theoriginaltitle = basename( get_attached_file( $attachment_id ) );
        // } else {
        //    $theoriginaltitle = $attachment->post_title; 
        //     // Check to see if no title set
        //    if ( empty($theoriginaltitle)) {
        //     $theoriginaltitle = basename( get_attached_file( $attachment_id ) );
        //    }
        // }        
        
        // $titleWithoutExtension = substr($theoriginaltitle, 0, strpos($theoriginaltitle, "."));
        // $thetitle = str_replace("-"," ",$titleWithoutExtension);
        // $uploadPostType = get_option('bip_post_type');
        // $uploadPostStatus = get_option('bip_post_status');
        // $uploadTaxonomy = get_option('bip_taxonomy');
        // $uploadTerms = get_option('bip_terms');
        // $uploadImageContent = get_option('bip_image_content');
        // $uploadImageContentSize = get_option('bip_image_content_size');

        $attachment_id = media_handle_upload( 'bipImage', 0 );

        $attachment = get_post( $attachment_id );
        
        $uploadTitleType = get_option('bip_image_title');
        
        if ( $uploadTitleType == 0) {
            $theoriginaltitle = basename( get_attached_file( $attachment_id ) );
            $titleWithoutExtension = substr($theoriginaltitle, 0, strpos($theoriginaltitle, "."));
            $thetitle = str_replace("-"," ",$titleWithoutExtension);
        } else {
            $theoriginaltitle = get_the_title($attachment_id); // changed $attachment->post_title to get_the_title($attachment_id);
            $thetitle = $theoriginaltitle;
                // Check to see if no title set
                if ( empty($theoriginaltitle)) {
                    $theoriginaltitle = basename( get_attached_file( $attachment_id ) );
                    $titleWithoutExtension = substr($theoriginaltitle, 0, strpos($theoriginaltitle, "."));
                    $thetitle = str_replace("-"," ",$titleWithoutExtension);
                }
        }

        $uploadPostType = get_option('bip_post_type');
        $uploadPostStatus = get_option('bip_post_status');
        $uploadTaxonomy = get_option('bip_taxonomy');
        $uploadTerms = get_option('bip_terms');
        $uploadImageContent = get_option('bip_image_content');
        $uploadImageContentSize = get_option('bip_image_content_size');

        $postContent = "";

        if($uploadImageContent == 1){
            $image = wp_get_attachment_image_src( $attachment_id, $uploadImageContentSize);
            $imageUrl = $image[0];
            $image_tag = '<p><img src="'.$imageUrl.'" alt="'. $thetitle .'"/></p>';
            $postContent = $image_tag;
        }

   

        $post_information = array(
            'post_title' => $thetitle,
            'post_type' => $uploadPostType,
            'post_status' => $uploadPostStatus,
            'post_content' => $postContent,
            'tax_input' => $uploadTerms,
                 
        );
        
        $the_post_id = wp_insert_post( $post_information );
    
        // attach media to post
        wp_update_post( array(
            'ID' => $attachment_id,
            'post_parent' => $the_post_id,
        ) );

        set_post_thumbnail( $the_post_id , $attachment_id);
    

        if ( is_wp_error( $attachment_id ) ) {
            _e('There was an error uploading the image.','bulk-images-to-posts');
        } else {
            // The bip was uploaded successfully!
            _e('Success','bulk-images-to-posts');
        }
}

?>
