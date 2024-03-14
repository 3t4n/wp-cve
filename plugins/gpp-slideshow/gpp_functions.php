<?php

/*-----------------------------------------------------------------------------------*/
/* Outputs all the images attached to a post */
/*-----------------------------------------------------------------------------------*/

function gpp_gallery_images( $size = 'large' ) {
	global $post;
	$meta = get_post_meta( $post->ID, 'gpp_gallery_hiddenids', true );
	if( isset( $meta ) && $meta != "" ) {
		$images = explode( ',', $meta );
	} else {
		$images = get_children( array( 'post_parent' => get_the_ID(), 'post_type' => 'attachment', 'order' => 'ASC', 'orderby' => 'menu_order ID', 'post_mime_type' => 'image' ) );
	}

	if ( $images ) {

		echo "\n\n",'<!-- #slideshow -->',"\n",
					'<div class="gpp_slideshow_wrapper" class="clearfix">',"\n",
					'<div class="flexslider">',"\n",
					'<div class="gpp_slideshow_menu" class="clearfix">',"\n",
					"\t",'<div class="slideshow_options">',"\n",
						"\t\t",'<a class="show_thumbnails" href="#" title="Show thumbnails">Show thumbnails</a>',"\n",
						"\t\t",'<a class="show_captions" href="#" title="Caption">Caption</a>',"\n",
					"\t",'</div>',"\n",

                        "\t",'<div class="slideshow_nav">',"\n",
                        "\t",'<ul class="flex-direction-nav">',"\n",
                        "\t",'<li>',"\n",
    						"\t\t",'<a href="#" class="prev flex-prev" title="Previous">Previous</a>',"\n",
    						"\t",'</li>',"\n",
    						"\t",'<li>',"\n",
    						"\t\t",'<a href="#" class="next flex-next" title="Next">Next</a>',"\n",
    						"\t",'</li>',"\n",
    					"\t",'</ul>',"\n",
    					"\t",'</div>',"\n",

				'</div>',"\n",
				'<div class="clear"></div>',"\n";


		echo '<ul class="gpp_slideshow_thumbnails" style="display:none">',"\n";

		foreach( $images as $image ) {
			if( isset( $meta ) && $meta != "" ) {
				$attachmentimage = wp_get_attachment_image( $image, 'thumbnail' );
			} else {
				$attachmentimage = wp_get_attachment_image( $image->ID, 'thumbnail' );
			}
			echo "\t",'<li><a href="#">',"\n";
			echo "\t\t",$attachmentimage.apply_filters( 'the_title', isset( $parent->post_title ) ),"\n";
			echo "\t",'</a></li>',"\n";
		}

		echo	'</ul>',"\n",
					'<div class="clear"></div>',"\n",
					'<div id="slideshowloader"></div>',"\n";



		echo "\n",'<ul class="slides">',"\n";

		foreach( $images as $image ) {
			if( isset( $meta ) && $meta != "" ) {
				$attachmentimage = wp_get_attachment_image( $image, 'large' );
			} else {
				$attachmentimage = wp_get_attachment_image( $image->ID, 'large' );
			}
			$imagemeta =  get_post($image);
			$caption = $imagemeta->post_excerpt;
  			$description = $imagemeta->post_content;
			echo "\t",'<li>',"\n";
			echo "\t\t",$attachmentimage.apply_filters( 'the_title', isset( $parent->post_title ) ),"\n";
			if ( isset( $caption ) )
				echo "\t\t",'<div class="flex-caption" style="display:none">' . $caption . '</div>',"\n";
			echo "\t",'</li>',"\n";
		}
		echo '</ul><!-- .slides -->',"\n";
		echo '</div><!-- .flexslider -->',"\n";
		echo '</div><!-- .gpp_slideshow_wrapper -->',"\n\n";
	}
}


/*-----------------------------------------------------------------------------------*/
/* Prints the plugin credits */
/*-----------------------------------------------------------------------------------*/

function gpp_gallery_credits() {
	echo '<div id="credits"><p>Plugin by <a href="http://graphpaperpress.com" target="_blank"> Graph Paper Press</a></p></div>';
}

/*-----------------------------------------------------------------------------------*/
/* Print the name of the user-defined slug */
/*-----------------------------------------------------------------------------------*/

function gpp_gallery_slug() {

	$gallery = get_option( 'gpp_gallery' );

	if ( empty ( $gallery[ 'galleries' ] ) )
		$slug = "Galleries"; // if plural name is empty
	else
		$slug = $gallery[ 'galleries' ]; // plural name

	echo $slug;

}

/*-----------------------------------------------------------------------------------*/
/* Change icons in admin for custom post type */
/*-----------------------------------------------------------------------------------*/

add_action( 'admin_head', 'gpp_gallery_admin_head' );

/**
* Change the icon on every page where post type is workshop
* Also save template paths to vars
*/
function gpp_gallery_admin_head() {
	global $post_type;

	$post_type = isset( $post_type ) ? $post_type : '';
	$_GET[ 'post_type' ] = isset( $_GET[ 'post_type' ] ) ? $_GET[ 'post_type' ] : '';
	$_GET[ 'post' ] = isset( $_GET[ 'post' ] ) ? $_GET[ 'post' ] : '';
	?>
	<style>
	<?php if ( ( $_GET[ 'post_type' ] == 'gallery' ) || ( $post_type == 'gallery' ) || ( get_post_type( $_GET[ 'post' ] ) == 'gallery' ) ) : ?>
	#icon-edit, #icon-post {
		background:transparent url('<?php echo GPP_GALLERY_PLUGIN_URL . '/img/icon.png'; ?>') no-repeat;
		background-position: -4px -7px;
		height: 45px;
		width: 45px;
	}
	#credits {
		background:transparent url('<?php echo GPP_GALLERY_PLUGIN_URL . '/img/gpp.png'; ?>') no-repeat;
		padding-left: 70px;
		min-height: 50px;
		margin: 50px 0 0;
	}
	#credits p {
		padding-top: 25px;
	}
	<?php endif; ?>
	</style>

	<?php
}

/***********  replace default gallery shortcode with gpp-slideshow-gallery *************/
add_action( 'wp_head', 'add_gpp_gallery' );
function add_gpp_gallery() {
  $gpp = get_option( 'gpp_gallery' );

	if ( isset( $gpp[ 'pages' ] ) && $gpp[ 'pages' ] == '1' && !is_page_template( 'page-blog.php' ) ) {

		remove_shortcode( 'gallery', 'gallery_shortcode' );
		add_shortcode( 'gallery', 'gpp_gallery_shortcode' );

	} else {

		if ( is_single() || is_page() && !is_page_template( 'page-blog.php' ) ) {
			remove_shortcode( 'gallery', 'gallery_shortcode' );
			add_shortcode( 'gallery', 'gpp_gallery_shortcode' );
		}
	}
}

//replace default gallery shortcode by image slider if not blog category
function gpp_gallery_shortcode( $attr ) {
	$gpp = get_option( 'gpp_gallery' );


	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $attr[ 'ids' ] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr[ 'orderby' ] ) )
			$attr[ 'orderby' ] = 'post__in';
		$attr[ 'include' ] = $attr[ 'ids' ];
	}

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters( 'post_gallery', '', $attr );
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr[ 'orderby' ] ) ) {
		$attr[ 'orderby' ] = sanitize_sql_orderby( $attr[ 'orderby' ] );
		if ( !$attr[ 'orderby' ] )
			unset( $attr[ 'orderby' ] );
	}

	extract( shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'columns'    => 3,
		'size'       => 'thumbnail-50',
		'include'    => '',
		'exclude'    => ''
	), $attr ) );

	$id = intval( $id );
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty( $include ) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty( $exclude ) ) {
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty( $attachments ) )
		return '';


	ob_start();
	echo '<div class="flexslider">',"\n",
	        '<div class="gpp_slideshow_menu" class="clearfix">',
					"\t",'<div class="slideshow_options">',
						"\t\t",'<a class="show_thumbnails" href="#" title="Show thumbnails">Show thumbnails</a>',
						"\t\t",'<a class="show_captions" href="#" title="Caption">Caption</a>',
					"\t",'</div>',

                         "\t",'<div class="slideshow_nav">',"\n",
                            "\t",'<ul class="flex-direction-nav">',"\n",
                            "\t",'<li>',"\n",
        						"\t\t",'<a href="#" class="prev flex-prev" title="Previous">Previous</a>',"\n",
        						"\t",'</li>',"\n",
        						"\t",'<li>',"\n",
        						"\t\t",'<a href="#" class="next flex-next" title="Next">Next</a>',"\n",
        						"\t",'</li>',"\n",
        					"\t",'</ul>',"\n",
        					"\t",'</div>',"\n",

				'</div>',
				'<div class="clear"></div>';


		echo '<ul class="gpp_slideshow_thumbnails" style="display:none">';

		foreach ( $attachments as $id => $attachment ) {
			$attachmentimage = wp_get_attachment_image( $id, 'thumbnail' );
			echo "\t",'<li><a href="#">';
			echo "\t\t",$attachmentimage.apply_filters( 'the_title', isset( $parent->post_title ) );
			echo "\t",'</a></li>';
		}

		echo	'</ul>',
					'<div class="clear"></div>',
					'<div id="slideshowloader"></div>';

		 echo "\n",'<ul class="slides">',"\n";

		foreach ( $attachments as $id => $attachment ) {
			$attachmentimage = wp_get_attachment_image( $id, 'large');
			$title = $attachment->post_title;
			$description = $attachment->post_content;
			$caption = $attachment->post_excerpt;
			echo "\t",'<li>',"\n";
			echo "\t\t",$attachmentimage.apply_filters( 'the_title', isset( $parent->post_title ) );
			if ( isset( $caption ) )
				echo "\t\t",'<div class="flex-caption" style="display:none">'.$caption.'</div>';
			echo "\t",'</li>',"\n";
		}
		echo '</ul><!-- .slides -->',"\n";
		echo '</div><!-- .flexslider -->',"\n";
		$gallery = ob_get_clean();
		return $gallery;
		ob_end_clean();
 }