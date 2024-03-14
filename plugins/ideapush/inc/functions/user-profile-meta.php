<?php

    add_action( 'show_user_profile', 'idea_push_profile_image' );
    add_action( 'edit_user_profile', 'idea_push_profile_image' );

    function idea_push_profile_image( $user ) { 

        ?>
        <h3><?php _e('IdeaPush Profile Image', 'ideapush'); ?></h3>

        <table class="form-table">
            <tr>
                <th><label for="ideaPushImage"><?php _e('Upload an Image', 'ideapush'); ?></label></th>
                <td>
                    <input type="text" name="ideaPushImage" id="ideaPushImage" value="<?php echo esc_attr( get_the_author_meta( 'ideaPushImage', $user->ID ) ); ?>" class="regular-text" /> <input type="button" name="upload-btn" id="upload-btn" class="button-secondary idea_push_custom_user_profile_image" value="Upload Image">
                    
                    <br />
                </td>
            </tr>
            <tr>
                <td>
                    <img id="ideaPushImagePreview" style="object-fit: cover; border-radius: 50% !important; box-shadow: 0px 0px 5px 0px rgba(0,0,0,.15);" alt="ideaPushImage" src="<?php echo esc_attr( get_the_author_meta( 'ideaPushImage', $user->ID ) ); ?>" class="avatar avatar-96 photo" height="96" width="96">        
                </td>    
            </tr>


            <?php
                //lets only show the below field if the user is admin or ideapush manager
                $currentUser = get_current_user_id();
                global $ideapush_is_pro;

                if($ideapush_is_pro == "YES"){
                    if(user_can( $currentUser, 'administrator' ) || user_can( $currentUser, 'idea_push_manager' )){
                        ?>
                        <tr>
                            <th><label for="ideaPushVotesRemaining"><?php _e('Votes Remaining', 'ideapush'); ?></label></th>
                            <td>
                                <input type="number" name="ideaPushVotesRemaining" id="ideaPushVotesRemaining" value="<?php echo esc_attr( get_the_author_meta( 'ideaPushVotesRemaining', $user->ID ) ); ?>" class="regular-text" /> 
                            </td>
                        </tr>
                        <?php
                    }  
                } 

            ?>

        </table>
        <?php 
    }





    add_action( 'personal_options_update', 'idea_push_profile_image_save' );
    add_action( 'edit_user_profile_update', 'idea_push_profile_image_save' );

    function idea_push_profile_image_save( $user_id ) {
        if ( !current_user_can( 'edit_user', $user_id ) ) { 
            return false; 
        }
        update_user_meta( $user_id, 'ideaPushImage', $_POST['ideaPushImage'] );

        global $ideapush_is_pro;
        if($ideapush_is_pro == "YES"){
            update_user_meta( $user_id, 'ideaPushVotesRemaining', $_POST['ideaPushVotesRemaining'] );
        }
    }






?>