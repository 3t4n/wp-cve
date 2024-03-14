<div class="my-avatar">
    <div id="user-profile-img">
        <div class="profile-thumb">
            <?php
            if (!empty( $author_picture_id )) {
                $author_picture_id = intval( $author_picture_id );
                if ($author_picture_id) {
                    echo wp_get_attachment_image( $author_picture_id, array( 270, 270 ) );
                    echo '<input type="hidden" class="profile-picture-id" id="profile-picture-id" name="profile-picture-id" value="' . esc_attr( $author_picture_id ) . '"/>';
                }
            } else {
                print '<img id="profile-image" src="' . esc_url( $user_custom_picture ) . '" alt="user image" >';
            }
            ?>
        </div>
    </div><!-- end of user profile image -->
    <div class="profile-img-controls">
        <div id="errors-log"></div>
        <div id="plupload-container"></div>
    </div>
    <a id="select-profile-image" class="btn btn-primary btn-block btn-3d"
       href="javascript:;"><?php esc_html_e( 'Update Profile Picture', 'ocbee-core' ); ?></a>

    <span class="profile-img-info"><?php esc_html_e( '*minimum 270px x 270px', 'ocbee-core' ); ?><br/></span>
</div>