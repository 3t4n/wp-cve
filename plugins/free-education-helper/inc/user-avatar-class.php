 <?php

 class Education_Pro_Local_Avatars {

    function __construct(){
      add_filter( 'get_avatar', array ( $this, 'get_avatar' ), 10, 5 );
      add_action( 'admin_init', array ( $this, 'admin_init' ) );
      add_action( 'show_user_profile', array ( $this, 'edit_user_profile' ) );
      add_action( 'edit_user_profile', array ( $this, 'edit_user_profile' ) );
      add_action( 'personal_options_update', array ( $this, 'edit_user_profile_update' ) );
      add_action( 'edit_user_profile_update', array ( $this, 'edit_user_profile_update' ) );
      add_filter( 'avatar_defaults', array ( $this, 'avatar_defaults' ) );
  }

  function get_avatar( $avatar='', $id_or_email, $size='96', $default='', $alt=false ) {
    if ( is_numeric( $id_or_email ) )
        $user_id=(int)$id_or_email;
    elseif ( is_string( $id_or_email ) && ( $user=get_user_by('email',$id_or_email) ) )
        $user_id=$user->ID;
    elseif ( is_object( $id_or_email ) && !empty( $id_or_email->user_id ) )
        $user_id=(int)$id_or_email->user_id;
    if ( empty( $user_id ) )
        return $avatar;
    $auth = get_the_author_meta( 'user_login', $user_id );
    $wp_content = WP_CONTENT_DIR;
    $wp_content_url = WP_CONTENT_URL;
    $filename=$wp_content . '/images/authors/' . $auth . '.jpg';
    if ( file_exists( $filename ) ) {
        $avatar=$wp_content_url . '/images/authors/' . $auth . '.jpg';
        $meta=array (
            'full'=>$avatar
        );
        if ( !get_user_meta( $user_id, 'simple_local_avatar', true ) )
            update_user_meta( $user_id, 'simple_local_avatar', $meta );
        update_user_meta ( $user_id, 'big_avatar', $meta );
    }
    $local_avatars = get_user_meta( $user_id, 'simple_local_avatar', true );
    if ( empty( $local_avatars ) || empty( $local_avatars[ 'full' ] ) )
        return $avatar;
    $size=(int)$size;
    if ( empty( $alt ) )
        $alt=get_the_author_meta( 'display_name', $user_id );
    // generate a new size
    if ( empty( $local_avatars[ $size ] ) ) {
        $upload_path=wp_upload_dir();
        $avatar_full_path = str_replace( $upload_path[ 'baseurl' ], $upload_path[ 'basedir' ], $local_avatars[ 'full' ] );
     
        // deal with original being >= to original image (or lack of sizing ability)
        $local_avatars[ $size ]=is_wp_error( $avatar_full_path )
         ? $local_avatars[ $size ]=$local_avatars[ 'full' ]
         : str_replace( $upload_path[ 'basedir' ], $upload_path[ 'baseurl' ], $avatar_full_path );
         update_user_meta( $user_id, 'simple_local_avatar', $local_avatars );
    } elseif ( substr( $local_avatars[ $size ], 0, 4 ) != 'http' ) {
        $local_avatars[ $size ]=site_url( $local_avatars[ $size ] );
    }
    $author_class=is_author( $user_id ) ? ' current-author' : '';
    $avatar="<img alt='" . esc_attr( $alt ) . "' src='" . $local_avatars[ $size ] . "' class='avatar avatar-{$size}{$author_class} photo' height='{$size}' width='{$size}' />";
    return apply_filters( 'simple_local_avatar', $avatar );
}
function admin_init() {
    register_setting( 'discussion', 'education_pro_local_avatars_caps', array ( $this, 'sanitize_options' ) );
    add_settings_field( 'education-pro-local-avatars-caps', __( 'Local Avatar Permissions', 'free-education-helper' ), array ( $this, 'avatar_settings_field' ), 'discussion', 'avatars' );
}
function sanitize_options( $input ) {
    $new_input[ 'education_pro_local_avatars_caps' ]=empty( $input[ 'education_pro_local_avatars_caps' ] ) ? 0 : 1;
    return $new_input;
}
function avatar_settings_field( $args ) {
    $options=get_option( 'education_pro_local_avatars_caps' );
    echo '
    <label for="education_pro_local_avatars_caps">
    <input type="checkbox" name="education_pro_local_avatars_caps" id="education_pro_local_avatars_caps" value="1" ' .checked( $options[ 'education_pro_local_avatars_caps' ], 1, false ) . ' />
    ' . esc_html__( 'Only allow users with file upload capabilities to upload local avatars (Authors and above)', 'free-education-helper' ) . '
    </label>
    ';
}
function edit_user_profile( $profileuser ) {
    ?>
    <h3><?php esc_attr_e( 'Avatar', 'free-education-helper' ); ?></h3>

    <table class="form-table">
        <tr>
            <th><label for="education-pro-local-avatar"><?php esc_attr_e( 'Upload Avatar', 'free-education-helper' ); ?></label>
            </th>
            <td style="width: 50px;" valign="top">

                <?php echo get_avatar( $profileuser->ID ); ?>
            </td>
            <td>
                <?php
                $options=get_option( 'education_pro_local_avatars_caps' );
                if ( empty( $options[ 'education_pro_local_avatars_caps' ] ) || current_user_can( 'upload_files' ) ) {
                    do_action( 'simple_local_avatar_notices' );
                    wp_nonce_field( 'simple_local_avatar_nonce', '_simple_local_avatar_nonce', false );
                    ?>
                    <input type="file" name="education-pro-local-avatar" id="education-pro-local-avatar"/><br/>
                    <?php
                    if ( empty( $profileuser->simple_local_avatar ) )
                        echo '<span class="description">' . esc_html__( 'No local avatar is set. Use the upload field to add a local avatar.', 'free-education-helper' ) . '</span>';
                    else
                        echo '
                    <input type="checkbox" name="education-pro-local-avatar-erase" value="1" /> ' . esc_html__( 'Delete local avatar', 'free-education-helper' ) . '<br />
                    <span class="description">' . esc_html__( 'Replace the local avatar by uploading a new avatar, or erase the local avatar (falling back to a gravatar) by checking the delete option.', 'free-education-helper' ) . '</span>
                    ';
                } else {
                    if ( empty( $profileuser->simple_local_avatar ) )
                        echo '<span class="description">' . esc_html__( 'No local avatar is set. Set up your avatar at Gravatar.com.', 'free-education-helper' ) . '</span>';
                    else
                        echo '<span class="description">' . esc_html__( 'You do not have media management permissions. To change your local avatar, contact the blog administrator.', 'free-education-helper' ) . '</span>';
                }
                ?>
            </td>
        </tr>
    </table>
    <script type="text/javascript">var form = document.getElementById('your-profile');
    form.encoding = 'multipart/form-data';
form.setAttribute('enctype', 'multipart/form-data');</script>
<?php
}
function edit_user_profile_update( $user_id ) {
            if ( !isset( $_POST[ '_simple_local_avatar_nonce' ] ) || !wp_verify_nonce( $_POST[ '_simple_local_avatar_nonce' ], 'simple_local_avatar_nonce' ) ) //security
            return;
            if ( !empty( $_FILES[ 'education-pro-local-avatar' ][ 'name' ] ) ) {
                $mimes=array (
                    'jpg|jpeg|jpe'=>'image/jpeg',
                    'gif'=>'image/gif',
                    'png'=>'image/png',
                    'bmp'=>'image/bmp',
                    'tif|tiff'=>'image/tiff'
                );
                // front end (theme my profile etc) support
                if ( !function_exists( 'wp_handle_upload' ) )
                    require_once( ABSPATH . 'wp-admin/includes/file.php' );
                $this->avatar_delete( $user_id ); // delete old images if successful
                $avatar=wp_handle_upload( $_FILES[ 'education-pro-local-avatar' ], array ( 'mimes'=>$mimes, 'test_form'=>false, 'unique_filename_callback'=>array ( $this, 'unique_filename_callback' ) ) );
                if ( empty( $avatar[ 'file' ] ) ) { // handle failures
                    switch ( $avatar[ 'error' ] ) {
                        case 'File type does not meet security guidelines. Try another.' :
                        add_action( 'user_profile_update_errors', create_function( '$a', '$a->add("avatar_error",__("Please upload a valid image file for the avatar.","education-pro-local-avatars"));' ) );
                        break;
                        default :
                        add_action( 'user_profile_update_errors', create_function( '$a', '$a->add("avatar_error","<strong>".__("There was an error uploading the avatar:","education-pro-local-avatars")."</strong> ' . esc_attr( $avatar[ 'error' ] ) . '");' ) );
                    }
                    return;
                }
                update_user_meta( $user_id, 'simple_local_avatar', array ( 'full'=>$avatar[ 'url' ] ) ); // save user information (overwriting old)
            } elseif ( !empty( $_POST[ 'education-pro-local-avatar-erase' ] ) ) {
                $this->avatar_delete( $user_id );
            }
        }
        /**
         * remove the custom get_avatar hook for the default avatar list output on options-discussion.php
         */
        function avatar_defaults( $avatar_defaults ) {
            remove_action( 'get_avatar', array ( $this, 'get_avatar' ) );
            return $avatar_defaults;
        }
        /**
         * delete avatars based on user_id
         */
        function avatar_delete( $user_id ) {
            $old_avatars=get_user_meta( $user_id, 'simple_local_avatar', true );
            $auth=get_the_author_meta( 'user_login', $user_id );
            $wp_content=WP_CONTENT_DIR;
            $filename=$wp_content . '/images/authors/' . $auth . '.jpg';
            if ( file_exists( $filename ) ) {
                unlink( $filename );
            }
            else {
                $upload_path=wp_upload_dir();
                if ( is_array( $old_avatars ) ) {
                    foreach ( $old_avatars as $old_avatar ) {
                        $old_avatar_path=str_replace( $upload_path[ 'baseurl' ], $upload_path[ 'basedir' ], $old_avatar );
                        unlink( $old_avatar_path );
                    }
                }
            }
            delete_user_meta( $user_id, 'simple_local_avatar' );
        }
        function unique_filename_callback( $dir, $user_id, $ext ) {
            global $user_id;
            $user_picname =  get_the_author_meta ( 'display_name', $user_id );
            $user = wp_get_current_user();
            $name = sanitize_file_name( $user_picname . '_avatar' );
            $number = 1;
            while ( file_exists( $dir . "/$name$ext" ) ) {
                $name = $name . '_' . $number;
                $number++;
            }
            return $name . $ext;
        }
    }
    $education_pro_local_avatars = new Education_Pro_Local_Avatars;
    function get_simple_local_avatar( $id_or_email, $size='300', $default='', $alt=false ) {
        global $education_pro_local_avatars;
        $avatar=$education_pro_local_avatars->get_avatar( '', $id_or_email, $size, $default, $alt );
        if ( empty ( $avatar ) )
            $avatar=get_avatar( $id_or_email, $size, $default, $alt );
        return $avatar;
    }