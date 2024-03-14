<?php
/**
 * @package PrivatePostDefault
 * @version 1.0
 */
/*
Plugin Name: PrivatePostDefault
Plugin URI: http://wordpress.org/extend/plugins/privatepostdefault/
Description: Make all post private default.
Author: Ronaldo Richieri
Version: 1.0
Author URI: http://richieri.com/
*/
function default_post_visibility(){
global $post;

if ( 'publish' == $post->post_status ) {
    $visibility = 'public';
    $visibility_trans = __('Public');
} elseif ( !empty( $post->post_password ) ) {
    $visibility = 'password';
    $visibility_trans = __('Password protected');
} elseif ( $post_type == 'post' && is_sticky( $post->ID ) ) {
    $visibility = 'public';
    $visibility_trans = __('Public, Sticky');
} else {
    $post->post_password = '';
    $visibility = 'private';
    $visibility_trans = __('Private');
} ?>

<script type="text/javascript">
    (function($){
        try {
            $('#post-visibility-display').text('<?php echo $visibility_trans; ?>');
            $('#hidden-post-visibility').val('<?php echo $visibility; ?>');
            $('#visibility-radio-<?php echo $visibility; ?>').attr('checked', true);
        } catch(err){}
    }) (jQuery);
</script>
<?php
}

add_action( 'post_submitbox_misc_actions' , 'default_post_visibility' );

?>
