<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options;
# loc bai viet và hình ảnh của user
if (isset($foxtool_options['user-post1'])){
function foxtool_posts_useronly( $wp_query ) {
    if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/edit.php' ) !== false ) {
        if ( !current_user_can('manage_options') ) {
            global $current_user;
            $wp_query->set( 'author', $current_user->ID );
        }
    }
}
add_filter('parse_query', 'foxtool_posts_useronly' );
// hinh anh media chi admin moi thay het
function foxtool_user_attachments( $query ) {
    $user_id = get_current_user_id();
    if ( $user_id && !current_user_can('manage_options') ) {
        $query['author'] = $user_id;
    }
    return $query;
}
add_filter( 'ajax_query_attachments_args', 'foxtool_user_attachments' );
}

# chi admin mới vào được trang quản trị
if (isset($foxtool_options['user-wp1'])){
function foxtool_restrict_admin_access() {
    if (is_user_logged_in()) {
        if (is_admin() && !defined('DOING_AJAX') && !current_user_can('administrator')) {
            wp_redirect(home_url());
            exit;
        }
    }
}
add_action('admin_init', 'foxtool_restrict_admin_access');
}
# tuy chon tắt thanh bar
if(isset($foxtool_options['user-bar1'])){
function foxtool_disable_admin_bar() {
    global $foxtool_options;
    if (isset($foxtool_options['user-bar11'])) {
        if ($foxtool_options['user-bar11'] == 'All') {
            show_admin_bar(false);
        } elseif ($foxtool_options['user-bar11'] == 'User' && !current_user_can('administrator')) {
            show_admin_bar(false);
        }
    }
}
add_action('after_setup_theme', 'foxtool_disable_admin_bar');
}
# chức năng upload avtatar
if (isset($foxtool_options['user-upav1'])){
function foxtool_avatar_scripts(){
    wp_enqueue_media();
	wp_enqueue_script('foxtool-avatar', FOXTOOL_URL . 'link/upload-avatar.js', array(), FOXTOOL_VERSION);
}
add_action('admin_enqueue_scripts', 'foxtool_avatar_scripts');
function foxtool_profile_fields( $user ) {
    $foxtool_pic = ($user!=='add-new-user') ? get_user_meta($user->ID, 'foxtoolpic', true): false;
    if( !empty($foxtool_pic) ){
        $image = wp_get_attachment_image_src( $foxtool_pic, 'medium' );
    } ?>
	<div class="thongtin-av">
		<div style="margin-top:20px;">
		<img id="foxtool-img" src="<?php echo !empty($foxtool_pic) ? $image[0] : ''; ?>" style="<?php echo  empty($foxtool_pic) ? 'display:none;' :'' ?>width:100px;height:100px;object-fit: cover;object-position: 50% 50%;border-radius:100%;" />
		</div>
		<div>
		<a id="reset-hinh-anh" style="color:#999;font-size:14px;text-decoration: none;cursor: pointer;padding:5px;display:none;"><i style="font-size:12px;margin-right:4px;" class="far fa-trash-alt"></i> <?php _e('Delete avatar', 'foxtool'); ?></a>
		<input type="button" data-id="foxtool_image_id" data-src="foxtool-img" class="button foxtool-image" name="foxtool_image" id="foxtool-image" value="<?php _e('Profile picture', 'foxtool') ?>" />
		<input type="hidden" class="button" name="foxtool_image_id" id="foxtool_image_id" value="<?php echo !empty($foxtool_pic) ? $foxtool_pic : ''; ?>" />
		</div>
	</div>
    <?php
}
add_action( 'show_user_profile', 'foxtool_profile_fields' );
add_action( 'edit_user_profile', 'foxtool_profile_fields' );
add_action( 'user_new_form', 'foxtool_profile_fields' );
function fox_profile_update($user_id){
        $foxtool_pic = empty($_POST['foxtool_image_id']) ? '' : $_POST['foxtool_image_id'];
        update_user_meta($user_id, 'foxtoolpic', $foxtool_pic);
}
add_action( 'personal_options_update', 'fox_profile_update' );
add_action( 'edit_user_profile_update', 'fox_profile_update' );
// add img avatar
function foxtool_custom_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
    $user = false;
    if ( is_numeric( $id_or_email ) ) {
        $id = (int) $id_or_email;
        $user = get_user_by( 'id' , $id );
    } elseif ( is_object( $id_or_email ) ) {
        if ( ! empty( $id_or_email->user_id ) ) {
            $id = (int) $id_or_email->user_id;
            $user = get_user_by( 'id' , $id );
        }
    } else {
        $user = get_user_by( 'email', $id_or_email );
    }
    if($user){
        $custom_avatar  =   get_user_meta( $user->data->ID, 'foxtoolpic', true );
 
        if( !empty($custom_avatar) ){
            $image  =   wp_get_attachment_image_src($custom_avatar, array('30' , '30'));
            if( $image ){
                $avatar = "<img src='{$image[0]}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' loading='lazy' />";
            }
        }
    }
    return $avatar;
}
add_filter( 'get_avatar' , 'foxtool_custom_avatar' , 1 , 5 );
// add url avatar
function foxtool_custom_avatar_url( $url, $id_or_email, $args) {
    $user = false;
    if ( is_numeric( $id_or_email ) ) {
        $id = (int) $id_or_email;
        $user = get_user_by( 'id' , $id );
    } elseif ( is_object( $id_or_email ) ) {
        if ( ! empty( $id_or_email->user_id ) ) {
            $id = (int) $id_or_email->user_id;
            $user = get_user_by( 'id' , $id );
        }
    } else {
        $user = get_user_by( 'email', $id_or_email );
    }
    if($user){
        $custom_avatar  =   get_user_meta( $user->data->ID, 'foxtoolpic', true );
        if( !empty($custom_avatar) ){
            $image  =   wp_get_attachment_image_src($custom_avatar, array('30' , '30'));
            if( $image ){
                $url = "{$image[0]}";
            }
        }
    }
    return $url;
}
add_filter( 'get_avatar_url' , 'foxtool_custom_avatar_url' , 10 , 3 );
}
# show id user admin user
if (isset($foxtool_options['user-id1'])){
function foxtool_modify_user_table( $column ) {
    $column['user_id'] = 'User ID';
    return $column;
}
add_filter('manage_users_columns', 'foxtool_modify_user_table');
function foxtool_display_user_id( $val, $column_name, $user_id ) {
    if ( 'user_id' === $column_name ) {
        return $user_id;
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'foxtool_display_user_id', 10, 3 );
}
