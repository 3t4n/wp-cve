<?php

function mtw_title( $atts ) {
	$atts = shortcode_atts( array(
		'id' => ''
	), $atts );
	
	global $wp_query;
	
	if( ( is_tax() || is_category() ) && !in_the_loop() )
	{
		$term = $wp_query->get_queried_object();
    	return $term->name;
	}
	elseif( is_archive() && !in_the_loop() )
	{
		return get_post_type_object( get_post_type() )->label;
	}
	elseif ( is_home() && is_front_page()  && !in_the_loop()  ) 
	{
		return get_bloginfo('name');
	}
	elseif( is_home() && !is_front_page()  && !in_the_loop() )
	{
		return wp_title('', false);
	}
	elseif( !is_home() && is_front_page()  && !in_the_loop() && !is_page() )
	{
		return get_bloginfo('name');
	}
	else
	{
		return do_shortcode( get_the_title($atts['id']) );
	}
}
add_shortcode( 'mtw_title','mtw_title' );
add_shortcode( 'mw_title','mtw_title' );

function mtw_permalink( $atts ) {

	global $post;
	global $wp_query;

	$atts = shortcode_atts( array(
		'id' => '',
		'name' => '',
		'title' => '',
		'post_type' => 'page'
	), $atts );

	if( !$wp_query->is_mtw_term_item )
	{
		if( $atts['name'] != '' )
		{
			if( is_a( ( $post = get_page_by_path( $atts['name'], 'OBJECT',  $atts['post_type'] ) ), 'WP_Post') )
			{
				$atts['id'] = $post->ID ;
			}
		}
		if( $atts['title'] != '' )
		{
			if( is_a( ( $post = get_page_by_title( $atts['name'], 'OBJECT',  $atts['post_type'] ) ), 'WP_Post') )
			{
				$atts['id'] = $post->ID ;
			}
		}
		return get_permalink( $atts['id'] );
	}
	else
	{
		return get_term_link( $wp_query->queried_object );
	}
}
add_shortcode( 'mtw_permalink','mtw_permalink' );
add_shortcode( 'mw_permalink','mtw_permalink' );

function mtw_content( $atts ) {

	global $wp_query;
	$atts = shortcode_atts( array(
		'default' => 'values',
		'max' => ''
	), $atts );

	if( ( $wp_query->is_tax() || $wp_query->is_category() || $wp_query->is_tag() ) && ( !in_the_loop() || ( in_the_loop() && $wp_query->is_mtw_term_item ) ) )
	{
		return term_description();
	}
	else
	{
		global $post;
		$content = do_shortcode( apply_filters('the_content',  $post->post_content ) );

		if( $atts['max'] != '' )
		{
			$content = strip_tags($content);
			if( mb_strlen($content) > $atts['max'])
			{
				$content = mb_substr($content, 0, $atts['max']) . '...';
			}
		}
		return '<div class="wp-content shortcode">' . $content . '</div>';
	}
}
add_shortcode( 'mtw_content','mtw_content' );
add_shortcode( 'mw_content','mtw_content' );


function mtw_excerpt( $atts ) {
	global $post;
	return get_the_excerpt( $post );
}
add_shortcode( 'mtw_excerpt','mtw_excerpt' );
add_shortcode( 'mw_excerpt','mtw_excerpt' );

function mtw_post_date()
{
	return get_the_date();
}
add_shortcode( 'mtw_date','mtw_post_date' );
add_shortcode( 'mw_date','mtw_post_date' );


function mtw_thumbnail( $atts ) {
	$atts = shortcode_atts( array(
		'id' => '',
		'size'=>'',
		'w' => 0,
		'h' => 0
	), $atts );

	if($atts['id'] == '')
	{
		$atts['id'] = get_the_id();
	}

	if($atts['size'] == '')
	{
		$atts['size'] =  'thumbnail';
	}

	$size = $atts['size'];

	if( $atts['w'] != 0 && $atts['h'] != 0 )
	{
		$size = array( $atts['w'], $atts['h'], 1 );

		$get_size = apply_filters( 'mtw_thumbnail_get_size', $size );

		$src = apply_filters( 'mtw_thumbnail_src', wp_get_attachment_image_src(  get_post_thumbnail_id( $atts['id'] ) , $get_size ) );

		$src =  $src[0] ;

		ob_start();
		?>
		<div style="
			width:100%;
			height:<?php echo $size[1] ?>px;
			background-image:url(<?php echo $src ?>);
			background-size: cover;
		"></div>
		<?php
		return ob_get_clean();
	}

	return get_the_post_thumbnail($atts['id'], $size );
}
add_shortcode( 'mtw_thumbnail','mtw_thumbnail' );
add_shortcode( 'mw_thumbnail','mtw_thumbnail' );



function mtw_sidebar( $atts ) {
	$atts = shortcode_atts( array(
		'name' => ''
	), $atts );
	ob_start();
	get_sidebar($atts['name']);
	return ob_get_clean();
}
add_shortcode( 'mtw_sidebar','mtw_sidebar' );
add_shortcode( 'mw_sidebar','mtw_sidebar' );


function mtw_categories( $atts ) {
	
	global $post;

	$terms = wp_get_post_terms( $post->ID, 'category' );
	$names = array();

	foreach ($terms as $key => $term) 
	{
		$names[] = '<a href="'. get_term_link( $term ) . '">' . $term->name . "</a>";
	}

	return implode(', ', $names);
}
add_shortcode( 'mtw_categories','mtw_categories' );
add_shortcode( 'mw_categories','mtw_categories' );


function mtw_taxonomy( $atts ) {
	
	$atts = shortcode_atts( array(
		'tax' => 'category'
	), $atts );

	global $post;

	$terms = wp_get_post_terms( $post->ID, $atts['tax'] );
	$names = array();

	if( is_wp_error( $terms ) )
	{
		return '';
	}

	foreach ($terms as $key => $term) 
	{
		$names[] = '<a href="'. get_term_link( $term ) . '">' . $term->name . "</a>";
	}
	return implode(', ', $names);
}
add_shortcode( 'mtw_tax','mtw_taxonomy' );
add_shortcode( 'mw_tax','mtw_taxonomy' );

function mtw_get_term_link( $atts )
{
	$atts = shortcode_atts( array(
		'term' => '',
		'taxonomy' => 'category'
	), $atts );
	$url = get_term_link( $atts['term'], $atts['taxonomy'] );

	if( is_string( $url ) )
	{
		return $url;
	}

}
add_shortcode( 'mtw_get_term_link' , 'mtw_get_term_link' );
add_shortcode( 'mw_get_term_link' , 'mtw_get_term_link' );


function mtw_tags( $atts ) {
		
	global $post;

	$terms = wp_get_post_terms( $post->ID, 'post_tag' );
	$names = array();

	foreach ($terms as $key => $term) 
	{
		$names[] = '<a href="'. get_term_link( $term ) . '">' . $term->name . "</a>";
	}

	if( $names )
	{
		return implode(', ', $names);
	}
}
add_shortcode( 'mtw_tags','mtw_tags' );
add_shortcode( 'mw_tags','mtw_tags' );

function mtw_archive_title( $atts ) {
	
	if( is_home() )
	{
		$title = get_post( get_option( 'page_for_posts' ) )->post_title;
	}
	else
	{
		$title = post_type_archive_title();	
	}

	return apply_filters( 'mtw_archive_title', $title );
	

	// do shortcode actions here
}
add_shortcode( 'mtw_archive_title','mtw_archive_title' );
add_shortcode( 'mw_archive_title','mtw_archive_title' );

function mtw_term_description( $atts )
{
	$atts = shortcode_atts( array(
		'term_id' => NULL,
		'taxonomy' => NULL
	), $atts );
	//return term_description( $atts['term_id'], $atts['taxonomy'] );
	return term_description();
}
add_shortcode( "mtw_term_description", "mtw_term_description" );
add_shortcode( "mw_term_description", "mtw_term_description" );

function mtw_get_next_post_url( $atts )
{
	$atts = shortcode_atts( array(
		'in_same_term' => false,
		'excluded_terms' => '',
		'taxonomy' => 'category'
	), $atts );

	$next_post = get_adjacent_post( $atts['in_same_term'] , $atts['excluded_terms'], false , $atts['taxonomy'] );
	if( is_object( $next_post ) )
	{
		return get_permalink( $next_post->ID ); 
	}
}
add_shortcode( 'mtw_get_next_post_url', 'mtw_get_next_post_url' );
add_shortcode( 'mw_get_next_post_url', 'mtw_get_next_post_url' );

function mtw_get_prev_post_url( $atts )
{
	$atts = shortcode_atts( array(
		'in_same_term' => false,
		'excluded_terms' => '',
		'taxonomy' => 'category'
	), $atts );

	$next_post = get_adjacent_post( $atts['in_same_term'] , $atts['excluded_terms'], true , $atts['taxonomy'] );

	if( is_object( $next_post ) )
	{
		return get_permalink( $next_post->ID ); 
	}
}
add_shortcode( 'mtw_get_prev_post_url', 'mtw_get_prev_post_url' );
add_shortcode( 'mw_get_prev_post_url', 'mtw_get_prev_post_url' );

function mtw_back_to_archive_url()
{
	global $post;
	return get_post_type_archive_link( $post->post_type );
}
add_shortcode( 'mtw_back_to_archive_url', 'mtw_back_to_archive_url' );
add_shortcode( 'mw_back_to_archive_url', 'mtw_back_to_archive_url' );

function mtw_define_global_authordata_if_empty()
{
	global $post;
	global $authordata;
	if( empty($authordata) )
	{
		$authordata = new WP_User( $post->post_author );
		return $authordata;
	}
}

function mtw_author_name()
{
	global $post;
	mtw_define_global_authordata_if_empty();	
	return get_the_author_meta('display_name');
}
add_shortcode( 'mtw_author_name', 'mtw_author_name' );
add_shortcode( 'mw_author_name', 'mtw_author_name' );


function mtw_author_meta( $atts ) {
	$atts = shortcode_atts( array(
		'key' => 'description',
		'id' => false
	), $atts );
	mtw_define_global_authordata_if_empty();
	return get_the_author_meta( $atts['key'], $atts['id'] );
}
add_shortcode( 'mtw_author_meta','mtw_author_meta' );
add_shortcode( 'mw_author_meta','mtw_author_meta' );

function mtw_author_avatar( $atts ) {
	$atts = shortcode_atts( array(
		'size' => 32
	), $atts );
	mtw_define_global_authordata_if_empty();
	return get_avatar( get_the_author_meta( 'ID' ), $atts['size'] );
}
add_shortcode( 'mtw_author_avatar','mtw_author_avatar' );
add_shortcode( 'mw_author_avatar','mtw_author_avatar' );


function mtw_post_count_comments( $atts ) {
	global $post;
	return wp_count_comments( $post->ID )->approved;
}
add_shortcode( 'mtw_post_count_comments','mtw_post_count_comments' );
add_shortcode( 'mw_post_count_comments','mtw_post_count_comments' );

function mtw_bloginfo( $atts ) {
	$atts = shortcode_atts( array(
		'key' => 'name'
	), $atts );

	return get_bloginfo( $atts['key'] );
}
add_shortcode( 'mtw_bloginfo','mtw_bloginfo' );
add_shortcode( 'mw_bloginfo','mtw_bloginfo' );

function mtw_post_meta( $atts ) {
	global $post;
	$atts = shortcode_atts( array(
		'id' => $post->ID,
		'key' => 'name'
	), $atts );

	return get_post_meta( $atts['id'], $atts['key'], true );
}
add_shortcode( 'mtw_post_meta','mtw_post_meta' );
add_shortcode( 'mw_post_meta','mtw_post_meta' );

function mtw_term_meta( $atts ) {
	global $wp_query;
	if( function_exists('get_term_meta'))
	{
		$atts = shortcode_atts( array(
			'id' => $wp_query->get_queried_object()->term_id,
			'key' => 'name'
		), $atts );
		
		return get_term_meta( $atts['id'], $atts['key'], true );
	}
	else
	{
		return '';
	}
}
add_shortcode( 'mtw_term_meta','mtw_term_meta' );
add_shortcode( 'mw_term_meta','mtw_term_meta' );


function mw_noconflict( $atts, $content )
{

	if( !session_id() )
	{
		session_start();
	}

	$_SESSION['mw-noconflict'] = $content;

	$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$url_query = parse_url($url, PHP_URL_QUERY);

	if ($url_query) {
	    $url .= '&mw-noconflict=1';
	} else {
	    $url .= '?mw-noconflict=1';
	}
	return '<iframe class="mw-noconflict-iframe" src="' . $url . '"></iframe>' ;
}
add_shortcode( 'mw_noconflict', 'mw_noconflict' );

function mw_noconflict_wp_loaded( $template )
{
	global $wp_query;
	global $post;

	if( $_GET['mw-noconflict'] )
	{
		if( !session_id() )
		{
			session_start();
		}
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<?php
			wp_head();
			?>
			<style type="text/css">
				body,html{
					padding: 0 !important;
					margin: 0 !important;
					background: transparent !important;
				}
				#wpadminbar
				{
					display: none;
				}
			</style>
		</head>
		<body>
			<?php
			echo do_shortcode( $_SESSION['mw-noconflict'] );
			wp_footer();
			?>
		</body>
		</html>
		<?php
		exit();
	}
	return $template;
}
add_filter( 'template_include', 'mw_noconflict_wp_loaded', 100 );
?>