<?php
/*
Plugin Name: Image Title Remove
Plugin URI:  https://developer.wordpress.org/plugins/image-title-remove
Description: Remove titles for all images. It will remove image title if you have too many random hashtag image names on website. So it will not show on hover as text.
Version:     1.0
Author:      Slavisa Ristic
Author URI:  http://www.slavisaristic.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

function add_custom_script_image(){
?>
<script>
jQuery(window).load(function(){
	jQuery('img').removeAttr('title');       
});
</script>
<?php
}
add_action('wp_footer', 'add_custom_script_image');