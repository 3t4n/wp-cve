<?php
/*
Plugin Name:  Image For Open Graph Meta
Plugin URI:  https://formativeinfotech.com/
Description: This plugin inserts og(Open Graph) Tags into your Website for more effective Whatsapp/Facebook sharing.
Version: 1.0
Author: SHANKAR SINGH AANJANA & VAIBHAV GANGRADE
Author URI: https://www.linkedin.com/in/er-shankar-singh-aanjana-a8506b33
*/
 

//function to limit description to 300 characters
function fboglimit($var, $limit) {
    if ( strlen($var) > $limit ) {
        return substr($var, 0, $limit) . '...';
    }
    else {
        return $var;
    }
}

// Set your Open Graph Meta Tags
function fbogmeta_header() {
    if (is_single()) {
        //getting the right post content
        $postsubtitrare = get_post_meta($post->ID, 'id-subtitrare', true);
        $post_subtitrare = get_post($postsubtitrare);
        $content = fboglimit(strip_tags($post_subtitrare-> post_content),297);
        ?>
        <meta property="og:title" content="<?php the_title(); ?>"/>
        <meta property="og:description" content="<?php echo $content; ?>" />
        <meta property="og:url" content="<?php the_permalink(); ?>"/>
        <?php $fb_image = wp_get_attachment_image_src(get_post_thumbnail_id(     get_the_ID() ), 'thumbnail'); ?>
        <?php if ($fb_image) : ?>
        <meta property="og:image" content="<?php echo $fb_image[0]; ?>" />
        <?php endif; ?>
        <meta property="og:type" content="<?php
        if (is_single() || is_page()) { echo "article"; } else { echo "website";}     ?>"
        />
        <meta property="og:site_name" content="<?php bloginfo('name'); ?>"/>
        <?php
        }
        }
add_action('wp_head', 'fbogmeta_header');