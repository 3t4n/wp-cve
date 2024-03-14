<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function tx_shortcodes_button() {

   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
      return;
   }

   if ( get_user_option('rich_editing') == 'true' ) {
      add_filter( 'mce_external_plugins', 'tx_add_plugin' );
      add_filter( 'mce_buttons', 'tx_register_button' );
   }

}
add_action('init', 'tx_shortcodes_button');
//add_action('admin_head', 'tx_shortcodes_button');

function tx_add_plugin( $plugin_array ) {
   $plugin_array['txshortcodes'] = plugin_dir_url( __FILE__ ) . 'tx-shortcodes.js';
   return $plugin_array;
}

function tx_register_button( $buttons ) {
   array_push( $buttons, "|", "txshortcodes" );
   return $buttons;
}


// recent posts [tx_blog items="3" colums="6" showcat="show" category_id="8,9"]

if ( !function_exists('tx_blog_function') ) :

function tx_blog_function($atts, $content = null) {
	
   	$atts = shortcode_atts(array(
      	'items' => 4,
      	'columns' => 4,
      	'showcat' => 'show',
      	'category_id' => '',
		'show_pagination' => 'no',		
      	'carousel' => 'no',								
   	), $atts);
	
	
	$width = 600;
	$height = 360;
	
	$post_in_cat = tx_shortcodes_comma_delim_to_array( $atts['category_id'] );
	$post_comments = '';

	$posts_per_page = intval( $atts['items'] );
	$total_column = intval( $atts['columns'] );
	$tx_category = $atts['showcat'];
	$tx_carousel = $atts['carousel'];
	
	$return_string = '';
	
	if( $tx_carousel == 'no' ) {
   		$return_string .= '<div class="tx-blog tx-post-row tx-masonry">';
	} else
	{
   		$return_string .= '<div class="tx-blog tx-post-row tx-carousel" data-columns="'.esc_attr($total_column).'">';		
	}
	
	wp_reset_query();
	global $post;
	
	$args = array(
		'posts_per_page' => $posts_per_page,
		'orderby' => 'date', 
		'order' => 'DESC',
		'ignore_sticky_posts' => 1,
		'category__in' => $post_in_cat, //use post ids		
	);

	if ($atts['show_pagination'] == 'yes' && $atts['carousel'] == 'no' )
	{	
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$args['paged'] = $paged;
		$args['prev_text'] = __('&laquo;','tx');
		$args['next_text'] = __('&raquo;','tx');
		$args['show_all'] = false;
	}

	
	query_posts( $args );
   
	if ( have_posts() ) : while ( have_posts() ) : the_post();
	
		$post_comments = get_comments_number();
			
		$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );

		$thumb_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		if($thumb_image_url)
		{
			$thumb_image_url = aq_resize( $thumb_image_url[0], $width, $height, true, true, true );
		}
	
		$return_string .= '<div class="tx-blog-item tx-post-col-'.esc_attr($total_column).'"><div class="tx-border-box">';

		if ( has_post_thumbnail() ) { 
			$return_string .= '<div class="tx-blog-img"><a href="'.esc_url($full_image_url[0]).'" class="tx-colorbox">';
			$return_string .= '<img src="'.esc_url($thumb_image_url).'" alt="" class="blog-image" /></a><span class="tx-post-comm"><span>'.esc_html($post_comments).'</span></span></div>';
		} else
		{
			$return_string .= '<div class="tx-blog-imgpad"></div>';
		}
		
		$return_string .= '<div class="tx-post-content"><h3 class="tx-post-title"><a href="'.esc_url(get_permalink()).'">'.esc_html(get_the_title()).'</a></h3>';
		if ( $tx_category == "show" )
		{
			$return_string .= '<div class="tx-category">'.get_the_category_list( ', ' ).'</div>';	
		} else
		{
			$return_string .= '<div style="height: 16px;"></div>';
		}
		
		$return_string .= '<div class="tx-blog-content">'.esc_html(get_the_excerpt()).'</div>';

		$return_string .= '<div class="tx-meta">';
		$return_string .= '<span class="tx-author">By : <a href="'.esc_url( get_author_posts_url( get_the_author_meta("ID") ) ).'">'.esc_html(get_the_author()).'</a></span>';
		$return_string .= '<span class="tx-date"> | '.esc_html(get_the_date('M j, Y')).'</span>';
		$return_string .= '</div>';
		
		
		$return_string .= '</div></div></div>';		
		
		
	endwhile; else :
		$return_string .= '<div class="tx-noposts"><p>'. esc_html__("Sorry, no posts matched your criteria. Please add some posts with featured images.", "tx") .'</p></div>';
	endif;
  
   	$return_string .= '</div>';

	if ($atts['show_pagination'] == 'yes' && $atts['carousel'] == 'no' ) {
		$return_string .= '<div class="nx-paging"><div class="nx-paging-inner">'.paginate_links( $args ).'</div></div>';
	}

   	wp_reset_query();

   	return $return_string;
}

endif;



// heading
if ( !function_exists('tx_heading_function') ) :

function tx_heading_function($atts, $content = null) {
	
	//[tx_heading style=”default” heading_text=”Heading Text” tag=”h1″ size=”24″ margin=”24″]
	$allowed_tags = array("h1", "h2", "h3", "h4", "h5", "h6");
	$heading_tag = "h2";
	
   	$atts = shortcode_atts(array(
      	'style' => 'default',
      	'heading_text' => 'Heading Text',
      	'tag' => 'h2',
      	'size' => '24',	
      	'margin' => '24',
      	'align' => 'left',
      	'class' => '',
   	), $atts);

	if( in_array( strtolower( $atts['tag'] ), $allowed_tags ) )
	{
		$heading_tag = strtolower( $atts['tag'] );
	}
	
	$return_string ='';

   	$return_string .= '<div class="tx-heading" style="margin-bottom:'.esc_attr($atts['margin']).'px; text-align: '.esc_attr($atts['align']).';">';
   	$return_string .= '<'.esc_attr($heading_tag).' class="tx-heading-tag" style="font-size:'.esc_attr($atts['size']).'px;">';	
	$return_string .= do_shortcode(esc_html($atts['heading_text']));
   	$return_string .= '</'.esc_attr($heading_tag).'>';
   	$return_string .= '</div>';	

   	return $return_string;
}

endif;


// row
if ( !function_exists('tx_row_function') ) :

function tx_row_function($atts, $content = null) {
	
   	$atts = shortcode_atts(array(
      	'class' => '',
   	), $atts);
	
	$return_string ='';

   	$return_string .= '<div class="tx-row '.esc_attr($atts['class']).'">';
	$return_string .= do_shortcode(wp_kses_post($content));
   	$return_string .= '</div>';

   	return $return_string;
}

endif;


// columns
if ( !function_exists('tx_column_function') ) :

function tx_column_function($atts, $content = null) {
	
   	$atts = shortcode_atts(array(
      	'size' => '1/4',
		'class' => '',
   	), $atts);
	
	$return_string ='';
	$column_class = 'tx-column-size-';
	
	if ( $atts['size'] == '1/1' ) 
	{
		$column_class .= '1-1';
	} elseif ( $atts['size'] == '1/2' )
	{
		$column_class .= '1-2';
	} elseif ( $atts['size'] == '1/3' )
	{
		$column_class .= '1-3';
	} elseif ($atts['size'] == '1/4' )
	{
		$column_class .= '1-4';
	} elseif ($atts['size'] == '2/3' )
	{
		$column_class .= '2-3';
	} elseif ($atts['size'] == '3/4' )
	{
		$column_class .= '3-4';
	}

   	$return_string .= '<div class="tx-column ' .esc_attr($column_class). '">';
	$return_string .= do_shortcode(wp_kses_post($content));
   	$return_string .= '</div>';

   	return $return_string;
}

endif;



// spacer
if ( !function_exists('tx_spacer_function') ) :

function tx_spacer_function($atts, $content = null) {
	
   	$atts = shortcode_atts(array(
      	'class' => '',
		'size' => '16',
   	), $atts);
	
	$return_string ='';

   	$return_string .= '<div class="tx-spacer clearfix" style="height: '.esc_attr($atts['size']).'px"></div>';

   	return $return_string;
}

endif;



// devider [tx_devider size="24"]
if ( !function_exists('tx_divider_function') ) :

function tx_divider_function($atts, $content = null) {
	
   	$atts = shortcode_atts(array(
      	'class' => '',
		'size' => '16',
   	), $atts);
	
	$return_string ='';

   	$return_string .= '<div class="tx-divider clearfix" style="margin-top: '.esc_attr($atts['size']).'px;margin-bottom: '.esc_attr($atts['size']).'px"></div>';

   	return $return_string;
}

endif;


// recent posts
if ( !function_exists('tx_testimonial_function') ) :

function tx_testimonial_function($atts, $content = null) {
	
   	$atts = shortcode_atts(array(
      	'posts' => 6,
   	), $atts);
	
   
   	$posts_per_page = $atts['posts'];
	$posts_per_page = intval( $posts_per_page );
	
	$return_string = '';
	$return_string .= '<div class="tx-testiin">';
   	$return_string .= '<div class="tx-testimonials">';
 
  
	wp_reset_query();
	global $post;
	
	$args = array(
		'posts_per_page' => $posts_per_page,
		'post_type' => 'testimonials',
		'fullwidth' => 0,		
		'orderby' => 'date', 
		'order' => 'DESC'
	);

	query_posts( $args );   
   
	if ( have_posts() ) : while ( have_posts() ) : the_post();
	
		$testi_name = esc_attr(rwmb_meta('tx_testi_name'));
		$testi_desig = esc_attr(rwmb_meta('tx_testi_desig'));
		$testi_organ = esc_attr(rwmb_meta('tx_testi_company'));				
		

		$return_string .= '<div class="tx-testi-item" style="">';
		$return_string .= '<span class="tx-testi-text">'.esc_html(get_the_content()).'</span>';
		$return_string .= '<span class="tx-testi-name">'.$testi_name.'</span>';
		$return_string .= '<span class="tx-testi-desig">'.$testi_desig.', </span>';
		$return_string .= '<span class="tx-testi-org">'.$testi_organ.'</span>';						
		$return_string .= '</div>';
	endwhile; else :
		$return_string .= '<div class="tx-noposts"><p>'.esc_html__("Sorry, no testimonial matched your criteria. Add few testimonials.", "tx").'</p></div>';
	endif;
  
   	$return_string .= '</div></div>';

   	wp_reset_query();
   	return $return_string;
}

endif;


// button 
if ( !function_exists('tx_button_function') ) :

function tx_button_function($atts, $content = null) {
	
   	$atts = shortcode_atts(array(
      	'style' => '',
		'text' => '',
		'url' => '',
		'color' => '',
		'textcolor' => '',
		'target' => 'self',						
		'class' => '',
   	), $atts);
	
	$return_string ='';

   	$return_string .= '<a class="tx-button" href="'.esc_url($atts['url']).'" target="_'.esc_attr($atts['target']).'" style="color: '.esc_attr($atts['textcolor']).'; background-color: '.esc_attr($atts['color']).'">'.esc_html($atts['text']).'</a>';

   	return $return_string;
}

endif;


// Call to act
if ( !function_exists('tx_calltoact_function') ) :

function tx_calltoact_function($atts, $content = null) {
	
   	$atts = shortcode_atts(array(
      	'button_text' => '',
		'url' => '',
		'class' => '',
   	), $atts);
	
	$cta_text = esc_html($content);
	
	$return_string ='';
	
   	$return_string .= '<div class="tx-cta" style=""><div class="tx-cta-text">'.esc_html($content).'</div><a href="'.esc_url($atts['url']).'" class="cta-button">'.esc_attr($atts['button_text']).'</a><div class="clear"></div></div>';

   	return $return_string;
}

endif;



// Call to act [tx_services title="Services Title" icon="fa-heart"]Services content[/tx_services]
if ( !function_exists('tx_services_function') ) :

function tx_services_function($atts, $content = null) {
	
   	$atts = shortcode_atts(array(
      	'style' => 'default',	
      	'title' => '',
		'icon' => '',
		'class' => '',
   	), $atts);
	
	$style_class = '';
	
	$service_text = do_shortcode(esc_html($content));
	$service_icon = esc_attr($atts['icon']);
	$service_title = esc_attr($atts['title']);
	$style_class = esc_attr($atts['style']);
	
	$return_string ='';
	
   	$return_string .= '<div class="tx-service '.$style_class.'" style="">';
	$return_string .= '<div class="tx-service-icon"><span><i class="fa '.$service_icon.'"></i></span></div>';
	$return_string .= '<div class="tx-service-title">'.$service_title.'</div>';
	$return_string .= '<div class="tx-service-text">'.$service_text.'</div>';		
	$return_string .= '</div>';

   	return $return_string;
}

endif;


// portfolio [tx_portfolio items="6" columns="3"]
if ( !function_exists('tx_portfolio_function') ) :

function tx_portfolio_function($atts, $content = null) {
	
   	$atts = shortcode_atts(array(
      	'style' => 'default',
      	'items' => 4,
      	'columns' => 4,
		'hide_cat' => 'no',
		'hide_excerpt' => 'no',
		'show_pagination' => 'no',
		'carousel' => 'no',
		'blog_term' => '',
   	), $atts);
	
   
   	$style_class = '';
   	$posts_per_page = intval( $atts['items'] );
   	$total_column = intval( $atts['columns'] );
	$tx_carousel = $atts['carousel'];
	
	$width = 600;
	$height = 480;	
	
	if ( $atts['style'] == 'gallery' )
	{
		$style_class = 'folio-style-gallery';
	}

	
	$return_string = '';

	if( $tx_carousel == 'no' ) {
   		$return_string .= '<div class="tx-portfolio tx-post-row tx-masonry '.esc_attr($style_class).'">';
	} else
	{
   		$return_string .= '<div class="tx-portfolio tx-post-row tx-carousel" data-columns="'.esc_attr($total_column).'">';		
	}
	
	$cat_slug = '';
	
	if( !empty($atts['blog_term']) )
	{
		$cat_slug = $atts['blog_term'];
	} 
  
	wp_reset_query();
	global $post;
	
	$args = array(
		'posts_per_page' => $posts_per_page,
		'post_type' => 'portfolio',
		'orderby' => 'date',
		'order' => 'DESC',
		'portfolio-category' => $cat_slug, //use post ids	
	);

	if ($atts['show_pagination'] == 'yes' && $atts['carousel'] == 'no' )
	{
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$args['paged'] = $paged;
		$args['prev_text'] = __('&laquo;','tx');
		$args['next_text'] = __('&raquo;','tx');
	}

	query_posts( $args );

	if ( have_posts() ) : while ( have_posts() ) : the_post();
	
		$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
		
		$thumb_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		if($thumb_image_url)
		{
			$thumb_image_url = aq_resize( $thumb_image_url[0], $width, $height, true, true, true );		
		}

		$return_string .= '<div class="tx-portfolio-item tx-post-col-'.esc_attr($total_column).'"><div class="tx-border-box">';
		

		if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
			$return_string .= '<div class="tx-folio-img">';
			$return_string .= '<div class="tx-folio-img-wrap"><img src="'.esc_url($thumb_image_url).'" alt="" class="folio-img" /></div>';
			$return_string .= '<div class="folio-links"><span>';	
			$return_string .= '<a href="'.esc_url(get_permalink()).'" class="folio-linkico"><i class="fa fa-link"></i></a>';	
			$return_string .= '<a href="'.esc_url($full_image_url[0]).'" class="tx-colorbox folio-zoomico"><i class="fa fa-search-plus"></i></a>';										
			$return_string .= '</span></div>';			
			$return_string .= '</div>';			
		} 

		$return_string .= '<span class="folio-head">';
		$return_string .= '<h3 class="tx-folio-title"><a href="'.esc_url(get_permalink()).'">'.esc_html(get_the_title()).'</a></h3>';
		if ( $atts['hide_cat'] == 'no' ) { // check if the post has a Post Thumbnail assigned to it.
			$return_string .= '<div class="tx-folio-category">'.esc_html(tx_folio_term( 'portfolio-category' )).'</div>';
		} else
		{
			$return_string .= '<div style="display: block; clear: both; height: 16px;"></div>';
		}
		$return_string .= '</span>';
		if ( $atts['hide_excerpt'] == 'no' && $atts['style'] != 'gallery' ) { // check if the post has a Post Thumbnail assigned to it.
			$return_string .= '<div class="tx-folio-content">'.esc_html(get_the_excerpt()).'</div>';
		}
			
		$return_string .= '</div></div>';
	endwhile; else :
		$return_string .= '<div class="tx-noposts"><p>'.esc_html__("Sorry, no portfolio matched your criteria. Please add few portfolio along with featured images.", "tx").'</p></div>';
	endif;
  
   	$return_string .= '</div>';
	
	if ($atts['show_pagination'] == 'yes' && $atts['carousel'] == 'no' )
	{	
		$return_string .= '<div class="nx-paging"><div class="nx-paging-inner">'.paginate_links( $args ).'</div></div>';
	}
	

   	wp_reset_query();
	
   	return $return_string;
}

endif;


// Products Carousels
if ( !function_exists('tx_prodscroll_function') ) :

function tx_prodscroll_function($atts, $content = null) {
	
	//[tx_prodscroll type="products" ids="21,28,54,87" columns="4" items="8"]
	
   	$atts = shortcode_atts(array(
      	'type' => 'products',
		'ids' => '',
		'columns' => '4',
		'items' => '8',
		'class' => '',
   	), $atts);
	
	$return_string ='';
	$prod_shortcode = '';
	
	
	if ( !empty($atts['ids']) && ( $atts['type'] == 'product_categories' || $atts['type'] == 'products' ))
	{
		if ( $atts['type'] == 'product_categories' )
		{
			$prod_shortcode = '['.esc_attr($atts['type']).' number="'.esc_attr($atts['items']).'" columns="'.esc_attr($atts['columns']).'" ids="'.esc_attr($atts['ids']).'"]';
		} else
		{
			$prod_shortcode = '['.esc_attr($atts['type']).' per_page="'.esc_attr($atts['items']).'" columns="'.esc_attr($atts['columns']).'" ids="'.esc_attr($atts['ids']).'"]';
		}
	} else
	{
		if ( $atts['type'] == 'product_categories' )
		{
			$prod_shortcode = '['.esc_attr($atts['type']).' number="'.esc_attr($atts['items']).'" columns="'.esc_attr($atts['columns']).'"]';
		} else
		{
			$prod_shortcode = '['.esc_attr($atts['type']).' per_page="'.esc_attr($atts['items']).'" columns="'.esc_attr($atts['columns']).'"]';
		}		
	}
	
	$return_string = '<div class="tx-prod-carousel" data-columns="'.esc_attr($atts['columns']).'">'.do_shortcode( $prod_shortcode ).'</div>';

   	return $return_string;
}

endif;

// itrans slider
if ( !function_exists('tx_slider_function') ) :

function tx_slider_function($atts, $content = null) {
	
   	$atts = shortcode_atts(array(
      	'items' => 10,
      	'category' => '',
		'delay' => 8000,
		'parallax' => 'yes',
		'transition' => 'slide',
		'align' => 'left',		
		'title' => 'show',
		'desc' => 'show',
		'link' => 'show',
		'height' => 420,	
		'textbg' => 'shadow',										
      	'class' => '',								
   	), $atts);
	

	$return_string = '';
	$cat_slug = '';
	
	if( !empty($atts['category']) )
	{
		$cat_slug = $atts['category'];
	}
	
	$textbg_class = $atts['textbg'];

	$posts_per_page = intval( $atts['items'] );
	$tx_class = $atts['class'];
	$tx_delay = $atts['delay'];
	$tx_parallax = $atts['parallax'];
	
	$tx_transition = $atts['transition'];
	$tx_title = $atts['title'];
	$tx_desc = $atts['desc'];
	$tx_link = $atts['link'];	
	$tx_align = $atts['align'];
	$tx_height = $atts['height'];				
	
	
	$return_string .= '<div class="tx-slider '.esc_attr($textbg_class).'" data-delay="'.esc_attr($tx_delay).'" data-parallax="'.esc_attr($tx_parallax).'" data-transition="'.esc_attr($tx_transition).'">';		
	
	
	wp_reset_query();
	global $post;
	
	$args = array(
		'post_type' => 'itrans-slider',
		'posts_per_page' => $posts_per_page,
		'orderby' => 'date', 
		'order' => 'DESC',
		'ignore_sticky_posts' => 1,
		'itrans-slider-category' => $cat_slug, //use post ids				
	);

	$full_image_url = '';
	$large_image_url = '';
	$image_url = '';
	$width = 1200;
	$height = (int)$tx_height;

	query_posts( $args );
   
	if ( have_posts() ) : while ( have_posts() ) : the_post();
	
		$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );	
		$image_url = aq_resize( $full_image_url[0], $width, $height, true, false, true );

		$slide_link_text = rwmb_meta('tx_slide_link_text');
		$show_link_url = rwmb_meta('tx_slide_link_url');		
		
		$return_string .= '<div class="tx-slider-item">';
		$return_string .= '<div class="tx-slider-box">';
		
		if ( has_post_thumbnail() ) { 
			$return_string .= '<div class="tx-slider-img"><a href="'.esc_url($large_image_url[0]).'" class="tx-colorbox">';
			$return_string .= '<img src="'.esc_url($image_url['0']).'" alt="" class="blog-image" /></a>';
			$return_string .= '</div>';
		} 
		/**/
		$return_string .= '<div class="tx-slide-content"><div class="tx-slide-content-inner" style="text-align:'.esc_attr($tx_align).';">';
		if ( $tx_title == 'show' )
		{
			$return_string .= '<h3 class="tx-slide-title">'.esc_html(get_the_title()).'</h3>';
		}
		if ( $tx_desc == 'show' ) {
			$return_string .= '<div class="tx-slide-details"><p>'.esc_html(tx_custom_excerpt(32)).'</p></div>';
		}
		if ( $tx_link == 'show' ) {
			$return_string .= '<div class="tx-slide-button"><a href="'.esc_url( $show_link_url ).'">'.esc_html( $slide_link_text ).'</a></div>';		
		}
		$return_string .= '</div></div></div></div>';		
		
		
	endwhile; else :
		$return_string .= '<div class="tx-noposts"><p>'.esc_html__("Sorry, no slider matched your criteria. Please add few slides via menu \"itrans slider\" along with featured image.", "tx").'</p></div>';
	endif;
  
   	$return_string .= '</div>';

   	wp_reset_query();
   	return $return_string;
}

endif;


// team shortcode
if ( !function_exists('tx_team_function') ) :

function tx_team_function($atts, $content = null) {
	
   	$atts = shortcode_atts(array(
      	'items' => 4,
      	'columns' => 4,
      	'class' => '',								
   	), $atts);
	

	$return_string = '';

	$posts_per_page = intval( $atts['items'] );
	$columns = intval( $atts['columns'] );	
	$tx_class = $atts['class'];

	
	$return_string .= '<div class="tx-team tx-'.esc_attr($columns).'-column-team">';		
	
	wp_reset_query();
	global $post;
	
	$args = array(
		'post_type' => 'team',
		'posts_per_page' => $posts_per_page,
		'orderby' => 'date', 
		'order' => 'DESC',
		'ignore_sticky_posts' => 1,
	);

	$full_image_url = '';
	$large_image_url = '';
	$image_url = '';
	$width = 400;
	$height = 400;

	query_posts( $args );
   
	if ( have_posts() ) : while ( have_posts() ) : the_post();
	
		$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		$image_url = aq_resize( $full_image_url[0], $width, $height, true, false, true );

		$designation = esc_attr(rwmb_meta('tx_designation'));
		$team_email = esc_attr(rwmb_meta('tx_team_email'));
		$team_phone = esc_attr(rwmb_meta('tx_team_phone'));
		$team_twitter = esc_url(rwmb_meta('tx_team_twitter'));
		$team_facebook = esc_url(rwmb_meta('tx_team_facebook'));
		$team_gplus = esc_url(rwmb_meta('tx_team_gplus'));
		$team_skype = esc_attr(rwmb_meta('tx_team_skype'));
		$team_linkedin = esc_url(rwmb_meta('tx_team_linkedin'));								
		
		$return_string .= '<div class="tx-team-item">';
		$return_string .= '<div class="tx-team-box">';
		
		if ( has_post_thumbnail() ) { 
			$return_string .= '<div class="tx-team-img">';
			$return_string .= '<img src="'.esc_url($image_url['0']).'" alt="" class="team-image" />';
			
		/*
		if($team_email) { $return_string .= '<span class="tx-temail">'.$team_email.'</span>'; }
		if($team_phone) { $return_string .= '<span class="tx-phone">'.$team_phone.'</span>'; }
		*/
		$return_string .= '<div class="tx-team-socials">';
		if($team_twitter) { $return_string .= '<span class="tx-twitter"><a href="'.$team_twitter.'"><i class="fa fa-twitter"></i></a></span>'; }
		if($team_facebook) { $return_string .= '<span class="tx-facebook"><a href="'.$team_facebook.'"><i class="fa fa-facebook"></i></a></span>'; }
		if($team_gplus) { $return_string .= '<span class="tx-gplus"><a href="'.$team_gplus.'"><i class="fa fa-google-plus"></i></a></span>'; }
		if($team_skype) { $return_string .= '<span class="tx-skype"><a href="skype:'.$team_skype.'"><i class="fa fa-skype"></i></a></span>'; }
		if($team_linkedin) { $return_string .= '<span class="tx-linkedin"><a href="'.$team_linkedin.'"><i class="fa fa-linkedin"></i></a></span>'; }
		$return_string .= '</div>';			
			
			$return_string .= '</div>';
		} 
		/**/
		$return_string .= '<div class="tx-team-content"><div class="tx-team-content-inner" style="">';
		$return_string .= '<h3 class="">'.esc_html(get_the_title()).'</h3>';
		$return_string .= '<div class="desig">'.$designation.'</div>';		
		$return_string .= '</div></div></div></div>';		
		
		
	endwhile; else :
		$return_string .= '<div class="tx-noposts"><p>'.esc_html__("Sorry, no team member matched your criteria. Please add few team member along with featured image.", "tx").'</p></div>';
	endif;
  
   	$return_string .= '</div>';

   	wp_reset_query();
   	return $return_string;
}

endif;

// Animate
if ( !function_exists('tx_animate_function') ) :

function tx_animate_function($atts, $content = null) {
	
	//[tx_heading style=”default” heading_text=”Heading Text” tag=”h1″ size=”24″ margin=”24″]
	
   	$atts = shortcode_atts(array(
      	'animation' => 'bounceIn',
      	'duration' => 1,
      	'delay' => .4,
      	'inline' => 'no',	
      	//'content' => '',
   	), $atts);
	
	$container_tag = "div";
	
	if ( $atts['inline'] == "yes")
	{
		$container_tag = "span";
	}
	
	$return_string ='';

   	$return_string .= '<'.esc_attr($container_tag).' class="tx-animate" style="visibility:hidden;" data-animation="' . esc_attr($atts['animation']) . '" data-animation-duration="' . esc_attr($atts['duration']) . '" data-animation-delay="' . esc_attr($atts['delay']) . '">';
	$return_string .= do_shortcode(wp_kses_post($content));
   	$return_string .= '</'.esc_attr($container_tag).'>';	

   	return $return_string;
}

endif;


// Fancy Block
if ( !function_exists('tx_fancyblock_function') ) :

function tx_fancyblock_function($atts, $content = null) {
	
	//[tx_heading style=”default” heading_text=”Heading Text” tag=”h1″ size=”24″ margin=”24″]
	
   	$atts = shortcode_atts(array(
      	'height' => '',
      	'padding' => '32',
      	'bgcolor' => '',
      	'overlay' => '',		
      	'bgurl' => '',	
      	'attachment' => 'fixed',
      	'bgsize' => 'cover',
      	'fullwidth' => 'no',
   	), $atts);
	
	$fw_style = '';
	$fw_class = '';
	
	if ( $atts['fullwidth'] == 'yes' )
	{
		$fw_class = 'tx-fullwidthrow';
	}		
	
	if ( !empty($atts['height']) )
	{
		$fw_style .= 'height: '.esc_attr($atts['height']).'px; ';
	}	
	
	//if ( !empty($atts['bgcolor']) )
	//{
		//$fw_style .= 'background-color: '.$atts['bgcolor'].'; ';
	//}
	
	if ( !empty($atts['bgurl']) )
	{
		$fw_style .= 'background-image: url('.esc_url($atts['bgurl']).'); ';
	}
	
	$fw_style .= 'background-attachment: '.esc_attr($atts['attachment']).'; ';
	$fw_style .= 'background-size: '.esc_attr($atts['bgsize']).'; ';
	
	
	
	$return_string ='';

   	$return_string .= '<div class="tx-row '.esc_attr($fw_class).' tx-fwidth" style="">';
   	$return_string .= '<div class="tx-fw-inner" style="background-color: '.esc_attr($atts['bgcolor']).'; '.$fw_style.'">';	
   	$return_string .= '<div class="tx-fw-overlay" style="padding-bottom:'.esc_attr($atts['padding']).'px; padding-top:'.esc_attr($atts['padding']).'px; background-color: rgba(0,0,0,'.esc_attr($atts['overlay']).');">';
   	$return_string .= '<div class="tx-fw-content">';	
	$return_string .= do_shortcode(wp_kses_post($content));
   	$return_string .= '</div>';	
   	$return_string .= '</div>';
   	$return_string .= '</div>';	
   	$return_string .= '</div>';	


   	return $return_string;
}

endif;


// Video/hero Slider
if ( !function_exists('tx_vslider_function') ) :

function tx_vslider_function($atts, $content = null) {
	
	//[tx_vslider height="72" vurl="http://youtube.com/videourl" bgcolor="#dd3333" overlay="0.5" bgurl="http://localhost/i-max/wp-content/uploads/2016/07/Surface-Magenta-Touch-Cover.png" attachment="fixed" bgsize="cover" imgurl="http://localhost/i-max/wp-content/uploads/2015/01/i-create-logo.png" title="Slide Title" linktext="Link Text" linkurl="http://www.google.com/"]Slide Content Here[/tx_vslider]

   	$atts = shortcode_atts(array(
		'height' => '72',
		'reduct' => 0,		
		'vurl' => '',
		'bgcolor' => '#dd3333',
		'overlay' => '',
		'bgurl' => '',
		'attachment' => 'fixed',
		'bgsize' => 'cover',
		'imgurl' => '',
		'title' => 'Slide Title',
		'linktext' => 'Link Text', 
		'linkurl' => 'http://www.templatesnext.org/',
			
   	), $atts);
	
	$slider_text = do_shortcode($content);
	$video_id = "";
	$img_css = "";
	$bg_image = esc_url($atts['bgurl']);
	$attachment = esc_attr($atts['attachment']);
	$bgsize =  esc_attr($atts['bgsize']);
	$logoimage = esc_url($atts['imgurl']);
	$overlay =  esc_attr($atts['overlay']);
	$reduct =  esc_attr($atts['reduct']);
	
	$button_bg_color = esc_attr('#373737');
	
	if ( strpos( $atts['vurl'], 'youtu' ) !== false ) 
	{	
		$video_id = ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $atts['vurl'], $match ) ) ? $match[1] : false;
	}
	
	$img_css .= ' background-image: url('.$bg_image.');';
	$img_css .= ' background-position: center center;';
	$img_css .= ' background-repeat: no-repeat;';
	$img_css .= ' background-attachment: ' . $attachment . ';';
	$img_css .= ' background-size: '.$bgsize.';';
	
	$return_string ='';
   	$return_string .= '<div class="tx-vslider" data-vslider-height="'.esc_attr($atts['height']).'" data-vslider-reduct="'.$reduct.'" >';		
   	$return_string .= '<div class="tx-imagebg" style="'.$img_css.'"></div>';	
   	$return_string .= '<div class="tx-video-background">';

   	$return_string .= '<div class="tx-vslider-content '.$overlay.'">';
   	$return_string .= '<div class="content-wrap" style="text-align: center;">';
	
   	if( !empty($logoimage) ){
		$return_string .= '<img class="vslider-img" src="'.$logoimage.'" alt="" />';
	}
	
   	$return_string .= '<h2 class="vslider-title">'.esc_attr($atts['title']).'</h2>';
	
	if( !empty($slider_text) ){
   		$return_string .= '<p class="vslider-content">'.esc_attr($slider_text).'</p>';
	}
	
	if( !empty($atts['linkurl']) && !empty($atts['linktext']) ){
   		$return_string .= '<a class="vslider_button button" href="'.esc_attr($atts['linkurl']).'" style="background-color:'.$button_bg_color.'">'.esc_attr($atts['linktext']).'</a>';
	}
	
	$return_string .= '<div class="clear"></div>';
   	$return_string .= '</div>';	
   	$return_string .= '</div>';
	if( !empty($video_id) && !wp_is_mobile() ){
		$return_string .= '<div class="tx-video-foreground">';
		$return_string .= '<iframe src="https://www.youtube.com/embed/'.esc_attr($video_id).'?controls=0&amp;showinfo=0&amp;rel=0&amp;autoplay=1&amp;loop=1&amp;mute=1&amp;playlist='.esc_attr($video_id).'" frameborder="0" mute=1 allow="autoplay" allowfullscreen></iframe>';
		$return_string .= '</div>';	
	}	
   	$return_string .= '</div>';	
   	$return_string .= '</div> <!-- tx-tx-vslider" -->';	


   	return $return_string;
}

endif;


// YouTube Video [tx_youtube youtube_url="https://www.youtube.com/watch?v=KJ9NNiDlic8" width="600" controls="1" autoplay="1"]
if ( !function_exists('tx_youtube_function') ) :

function tx_youtube_function($atts) {
	
   	$atts = shortcode_atts(array(
      	'youtube_url' => '',	
      	'width' => '',
		'controls' => 1,
		'autoplay' => 0,
   	), $atts);
	
	$youtube_url = esc_url($atts['youtube_url']);
	$controls = esc_attr($atts['controls']);
	$autoplay = esc_attr($atts['autoplay']);
	$width = esc_attr($atts['width']);
	
	if( $width != "" )
	{
		$width = $width."px";
	} else
	{
		$width = "100%";
	}
	
	if ( strpos( $youtube_url, 'youtu' ) !== false ) 
	{	
		$video_id = ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $youtube_url, $match ) ) ? $match[1] : false;
	}	
	
	$return_string ='';
	if ( !empty($video_id) ) {
		$return_string .= '<div class="tx-youtube-outerwarp" style="width: '.$width.'">';
		$return_string .= '<div class="tx-youtube-warp" style="">';
		$return_string .= '<iframe src="https://www.youtube.com/embed/'.esc_attr($video_id).'?controls='.$controls.'&amp;showinfo=0&amp;rel=0&amp;autoplay='.$autoplay.'" frameborder="0" allow="autoplay" allowfullscreen></iframe>';	
		$return_string .= '</div></div>';
	} else
	{
		$return_string .= '<div class="tx-noposts"><p>'.esc_html__("Please use proper YouTube video URL.", "tx").'</p></div>';
	}

   	return $return_string;
}

endif;

// Skill bar or progress bar
if ( !function_exists('tx_progressbar_function') ) :

function tx_progressbar_function($atts, $content = null) {
	
	//[tx_progressbar skill_name=”HTML5″ percent=”90″ barcolor=”#dd3333″ trackcolor=”#dd9b9b” barheight=”28″ candystrip=”no”] 
	
   	$atts = shortcode_atts(array(
      	'skill_name' => 'HTML5',
      	'percent' => 72,
      	'barcolor' => '#dd3333',	
      	'trackcolor' => '#dd9b9b',
      	'barheight' => 25,
      	'candystrip' => 'no',
   	), $atts);
	
	$tx_class = "candystrip";
			
	if ( $atts['candystrip'] == 'yes')
	{
		$tx_class = "";
	}		
	
	$return_string ='';

	$return_string .= '<div class="prograss-container" data-progress-percent="' .esc_attr( $atts['percent']) . '">';
		$return_string .= '<div class="pbar-outer" style="height: ' . esc_attr($atts['barheight']) . 'px; line-height: ' . esc_attr($atts['barheight']) . 'px; background-color: ' . esc_attr($atts['trackcolor']) . ';">';
			$return_string .= '<div class="pbar-text">'. esc_attr($atts['skill_name']) .' <span class="bpercent"></span></div>';
			$return_string .= '<div class="pbar-inner" style="background-color:' . esc_attr($atts['barcolor']) . '; height: ' . esc_attr($atts['barheight']) . 'px;">';
				$return_string .= '<div class="'.esc_attr($tx_class).'"></div>';
			$return_string .= '</div>';
		$return_string .= '</div>';
	$return_string .= '</div>';	


   	return $return_string;
}
endif;

// Shape Dividers
//[tx_shapedivider divider_type="slanted" bg_color_1="#FFFFFF" bg_color_2="#CCCCCC"	z_index="1" height="100" top_margin="12" class="class-1"]
if ( !function_exists('tx_shapedivider_function') ) :
function tx_shapedivider_function($atts, $content = null) {
	
	$atts = shortcode_atts( array(
			'divider_type'      => 'slanted',
			'bg_color_1'   		=> '#FFFFFF',
			'bg_color_2'   		=> '#CCCCCC',								
			'z_index'   		=> 1,
			'height'   			=> 100,	
			'top_margin'   		=> '',																
			'class'				=> ''
	), $atts );
		
	return txo_shape_seperator( esc_attr($atts['divider_type']), esc_attr($atts['bg_color_1']), esc_attr($atts['bg_color_2']), esc_attr($atts['z_index']), esc_attr($atts['height']), esc_attr($atts['top_margin']) );		

}
endif;



function tx_register_shortcodes(){
	add_shortcode('tx_recentposts', 'tx_recentposts_function');
	add_shortcode('tx_row', 'tx_row_function');
	add_shortcode('tx_column', 'tx_column_function');
	add_shortcode('tx_spacer', 'tx_spacer_function');	
	add_shortcode('tx_testimonial', 'tx_testimonial_function');	
	add_shortcode('tx_button', 'tx_button_function');
	add_shortcode('tx_calltoact', 'tx_calltoact_function');
	add_shortcode('tx_services', 'tx_services_function');
	add_shortcode('tx_portfolio', 'tx_portfolio_function');	
	add_shortcode('tx_blog', 'tx_blog_function');
	add_shortcode('tx_divider', 'tx_divider_function');	
	add_shortcode('tx_prodscroll', 'tx_prodscroll_function');
	add_shortcode('tx_heading', 'tx_heading_function');
	add_shortcode('tx_slider', 'tx_slider_function');
	
	add_shortcode('tx_team', 'tx_team_function');
	add_shortcode('tx_animate', 'tx_animate_function');
	add_shortcode('tx_fancyblock', 'tx_fancyblock_function');
	add_shortcode('tx_vslider', 'tx_vslider_function');	
	add_shortcode('tx_youtube', 'tx_youtube_function');	
	add_shortcode('tx_progressbar', 'tx_progressbar_function');	
	add_shortcode('tx_shapedivider', 'tx_shapedivider_function');												
}

add_action( 'init', 'tx_register_shortcodes');

