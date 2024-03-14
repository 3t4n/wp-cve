<?php
/*
* Plugin Name: Facebook OpenGraph/OG Tags for Single Posts
* Description: This plugin includes the Facebook OG tags into your blog's single posts which include Blog Title, Post Title, Description and Featured Image (if available). If you're facing issues in the thumbnails while pasting your blog post link on Facebook, this plugin is must for you. 
* Version: 1.4
* Plugin URI: https://github.com/anonymousguyx/facebook-opengraph-tags
* Author: Zeeshan Ahmed
* Author URI: http://www.fiverr.com/zeeshanx
* Tested up to: 5.5.1
* 
*/

// Header Scripts
add_action('wp_head', 'opengraphsingle');
function opengraphsingle(){
	if ( is_single() ) {
		echo '<meta property="og:site_name" content="',esc_attr(bloginfo('name')),'"/>';
		echo '<meta property="og:url" content="',esc_attr(the_permalink()),'"/>';
		echo '<meta property="og:title" content="',esc_attr(wp_strip_all_tags(the_title('','',false))),'"/>';
		echo '<meta property="og:description" content="',esc_attr(wp_strip_all_tags(get_the_excerpt())),'"/>';
		if(has_post_thumbnail()){
			$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			echo '<meta property="og:image" content="',$image[0],'"/>';
		}
		else {
			echo '<meta property="og:image" content="',esc_attr(get_option( 'default-image' )),'"/>';
		}
	}
}

// Settings Menu
add_action('admin_menu', 'opengraphsingle_menu');
function opengraphsingle_menu(){
	add_menu_page( 'Facebook Single Posts OG', 'Facebook OG', 'administrator', 'fb-opengraph-tags', 'opengraphsingle_menu_page', 'dashicons-facebook', '50' );
}

add_action( 'admin_init', 'opengraph_options' );

function opengraph_options(){
	register_setting( 'meta-data', 'default-image' );
}

function opengraphsingle_menu_page(){ ?>
	<div class="wrap">
		<h1 class="ogtitle"> Facebook OG Tags Settings </h1>
		<form class="ogdata" action="options.php" method="post">
		<?php settings_fields( 'meta-data' ); ?>
		<?php do_settings_sections( 'opengraphsingle_menu' ); ?>
		<h3> Default Image URL </h3>
		<p class="ogpara">If a single posts doesn't contain any featured image then this image will be used as a featured image.</p>
		<input type="text" name="default-image" value="<?php echo esc_attr( get_option( 'default-image' )); ?>">
		<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

// Load Styles
function load_styles(){
	wp_register_style( 'ogstyles', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', false, 'v.1.0' );
	wp_enqueue_style( 'ogstyles' );
}
add_action( 'admin_enqueue_scripts', 'load_styles' );

?>
