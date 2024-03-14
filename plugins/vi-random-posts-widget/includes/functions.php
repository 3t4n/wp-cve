<?php

function virp_get_default_args() {

	$defaults = array(
		'title'             => esc_attr__( 'Random Posts', 'virp' ),
		'title_url'         => '',

		'limit'             => 5,
		'orderby'           => 'rand',
		'post_type'         => 'post',
		'post_status'       => 'publish',
		'cat'               => array(),


		'thumbnail'         => false,
		'thumbnail_size'    => 'virp-thumbnail',
		'thumbnail_align'   => 'left',
		'thumbnail_custom'  => false,
		'thumbnail_width'   => '',
		'thumbnail_height'  => '',

		'excerpt'           => false,
		'excerpt_length'    => 10,
		'readmore'          => false,
		'readmore_text'     => __( 'More', 'virp' ),
		'date'              => false,
		'format'     		  => 'F,j Y',
		'comment_count'	  => false,
		'author_name'		  => false,
		'show_category'	  => false,
		'show_tags'			  => false,			
		'before'            => '',
		'after'             => ''
	);

	// filtering the default arguments
	return apply_filters( 'virp_default_args', $defaults );

}

// Get arguments
function virp_random_posts( $args = array() ) {
	echo virp_get_random_posts( $args );
}

// Creating Front End Display for posts
function virp_get_random_posts( $args = array() ) {

	$html = '';

	// Get default arguments.
	$defaults = virp_get_default_args();

	// Merge the input arguments and the defaults.
	$args = wp_parse_args( $args, $defaults );

	extract( $args );
	do_action( 'virp_before_loop' );
	
	$posts = virp_get_posts( $args );
	
	if ( $posts->have_posts() ) :

		$html = '<div id="virp-random-posts" class="virp-random-' . sanitize_html_class( $args['post_type'] ) .'">';

			$html .= '<ul class="virp-ul">';
if($args['thumbnail_size'] == 'medium'){$floatv = 'float:none;';}else{'float:left;';}
				while ( $posts->have_posts() ) : $posts->the_post();
					$id = get_the_id();
					$html .= '<li class="virp-li virp-clearfix '.$args['thumbnail_size'].' ">';
                  
						if ( $args['thumbnail'] ) :

							// Check if has post thumbnail.
							if ( has_post_thumbnail() ) :

								// Custom thumbnail sizes.
								$thumb_id = get_post_thumbnail_id(); // Get the featured image id.
								$img_url  = wp_get_attachment_url( $thumb_id ); // Get img URL.
								$image    = virp_resize( $img_url, $args['thumbnail_width'], $args['thumbnail_height'], true );

								$html .= '<a href="' . esc_url( get_permalink() ) . '"  rel="bookmark">';
									if ( $args['thumbnail_custom'] ) :
										$html .= '<img style= float:none; class="virp-thumbnail align' . esc_attr( $args['thumbnail_align'] ) . '" src="' . esc_url( $image ) . '" alt="' . esc_attr( get_the_title() ) . '">';
									else :
										$html .= get_the_post_thumbnail( get_the_ID(), $args['thumbnail_size'], array( 'alt' => esc_attr( get_the_title() ), 'class' => 'virp-thumbnail align fdfg' . esc_attr( $args['thumbnail_align'] ) ) );
									endif;
								$html .= '</a>';

							elseif ( function_exists( 'get_the_image' ) ) :
								if ( $args['thumbnail_custom'] ) :
									$html .= get_the_image( array( 
										'width'        => (int) $args['thumbnail_width'],
										'height'       => (int) $args['thumbnail_height'],
										'image_class'  => 'virp-thumbnail align' . esc_attr( $args['thumbnail_align'] ),
										'image_scan'   => true,
										'echo'         => false,
										'link_to_post' => true,
									) );
								else:
									$html .= get_the_image( array( 
										'size'         => $args['thumbnail_size'],
										'image_class'  => 'virp-thumbnail align' . esc_attr( $args['thumbnail_align'] ),
										'image_scan'   => true,
										'echo'         => false,
										'link_to_post' => true,
									) );
								endif;

							// Display nothing.
							else :
								$html .= null;
							endif;

						endif;

						$html .= '<a class="virp-title" href="' . esc_url( get_permalink() ) . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'virp' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark">' . esc_attr( get_the_title() ) . '</a>';

						if ( $args['show_category'] ) :
						$categories =  get_the_category( $id );
						$separator = ', ';
						$output = '';
							if($categories){
								foreach($categories as $category) {
									$output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
							}
							$html .= '<br/><span class="virp-meta">Categories:&nbsp;'.  trim($output, $separator) .'</span>' ;	
						}

						endif;
						
						if ( $args['show_tags'] ) :
						$tags =  get_the_tags( $id );
						$separator = ', ';
						$output = '';
							if($tags){
								foreach($tags as $tag) {
									$output .= '<a href="'.get_category_link( $tag->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $tag->name ) ) . '">'.$tag->name.'</a>'.$separator;
							}
							$html .=  '&nbsp;<span class="virp-meta"><i class="fa fa-tags"></i>&nbsp;'.trim($output, $separator).'</span>';
						}

						endif;

						if ( $args['excerpt'] ) :
							$html .= '<div class="virp-summary">' . wp_trim_words( apply_filters( 'virp_excerpt', get_the_excerpt() ), $args['excerpt_length'], ' &hellip;' );
							if ( $args['readmore'] ) :
								$html .= '<a href="' . esc_url( get_permalink() ) . '" class="virp-more-link">' . $args['readmore_text'] . '</a></div>';
							else:
								$html .='</div>';	
							endif;
						endif;
						$html .='<div class="virp-clear"></div>';
						if ( $args['author_name'] ) :
							$html .= '<span class="virp-meta"><i class="fa fa-user"></i> <a href="'.get_author_posts_url( get_the_author_meta( "ID" ) ).'">' . get_the_author()  . '</a></span>';
						endif;
						
						if ( $args['date'] ) :
							$date = get_the_date();
							if ( $args['format'] ) :
								$date = $args['format'];
								
							endif;
							$html .= '&nbsp;<span class="virp-meta"><i class="fa fa-calendar"></i>&nbsp;' . esc_html( get_the_date( $date ) ) . '</span>';
						endif;
						
						if ( $args['comment_count'] ) :
							$comments_count = wp_count_comments( $id );
							
							$html .= '&nbsp;<span class="virp-meta"><i class="fa fa-comments"></i>&nbsp;' . esc_html($comments_count->approved). '</span>';
						endif;
					$html .='<div class="virp-clear"></div>';	
					$html .= '</li>';
					
				endwhile;

			$html .= '</ul>';

		$html .= '</div>';

	endif;

	// Reset Post Data.
	wp_reset_postdata();

	do_action( 'virp_after_loop' );
	
	// Return the posts markup.
	return $args['before'] . $html . $args['after'];

}

function virp_get_posts( $args = array() ) {

	// Query arguments.
	$query = array(
		'posts_per_page'      => $args['limit'],
		'orderby'             => $args['orderby'],
		'post_type'           => $args['post_type'],
	);

	// Limit posts by category.
	if ( ! empty( $args['cat'] ) ) {
		$query['category__in'] = $args['cat'];
	}
	
	$query = apply_filters( 'virp_query', $query );

	$posts = new WP_Query( $query );
	
	return $posts;

}