<?php
/**
 * `sp_news` Shortcode
 * 
 * @package WP News and Scrolling Widgets
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function wpnw_get_news_shortcode( $atts, $content = null ) {

	// Taking some globals
	global $post, $multipage, $paged;

	// SiteOrigin Page Builder Gutenberg Block Tweak - Do not Display Preview
	if( isset( $_POST['action'] ) && ( 'so_panels_layout_block_preview' == $_POST['action'] || 'so_panels_builder_content_json' == $_POST['action'] ) ) {
		return "[sp_news]";
	}

	// setup the query
	extract( shortcode_atts(array(
		"limit"					=> 10,
		"category"				=> '',
		"grid"					=> 1,
		"show_date"				=> 'true',
		"show_category_name"	=> 'true',
		"show_content"			=> 'true',
		"show_full_content"		=> 'false',
		"content_words_limit"	=> 20,
		"order"					=> 'DESC',
		"orderby"				=> 'date',
		'pagination' 			=> 'true',
		"pagination_type"		=> 'numeric',
		'extra_class'			=> '',
		'className'				=> '',
		'align'					=> '',
	), $atts, 'sp_news') );

	// Define variables 
	$unique				= wpnw_get_unique();
	$posts_per_page 	= ! empty( $limit )						? $limit 						: 10;
	$cat 				= ! empty( $category )					? explode( ',', $category )		: '';
	$type				= ( $grid == 'list' )					? 'list'						: 'grid';
	$show_date 			= ( $show_date == 'true' )				? 1								: 0;
	$show_category_name = ( $show_category_name == 'true' )		? 1								: 0;
	$show_content 		= ( $show_content == 'true' )			? 1								: 0;
	$show_full_content 	= ( $show_full_content == 'true' )		? 1								: 0;
	$words_limit 		= ! empty( $content_words_limit )		? $content_words_limit			: 20;
	$pagination 		= ( $pagination == 'false' )			? false							: true;
	$pagination_type   	= ( $pagination_type == 'numeric' )		? 'numeric'						: 'prev-next';
	$order 				= ( strtolower( $order ) == 'asc' )		? 'ASC'							: 'DESC';
	$orderby 			= ! empty( $orderby )					? $orderby						: 'date';
	$align				= ! empty( $align )						? 'align'.$align				: '';
	$extra_class		= $extra_class .' '. $align .' '. $className;
	$extra_class		= wpnw_sanitize_html_classes( $extra_class );
	$multi_page			= ( $multipage || is_single() || is_front_page() || is_archive() ) ? 1 : 0;

	ob_start();

	// Pagination Variable
	$paged = 1;
	if( $multi_page ) {
		$paged = isset( $_GET['news_page'] ) ? $_GET['news_page'] : 1;
	} else if ( get_query_var( 'paged' ) ) {
		$paged = get_query_var( 'paged' );
	} else if ( get_query_var( 'page' ) ) {
		$paged = get_query_var( 'page' );
	}

	$args = array ( 
		'post_type'			=> WPNW_POST_TYPE,
		'post_status'		=> array( 'publish' ),
		'orderby'			=> $orderby,
		'order'				=> $order,
		'posts_per_page'	=> $posts_per_page,
		'paged'				=> ( $pagination ) ? $paged : 1,
	);

	if( $cat != "" ) {
		$args['tax_query'] = array(
			array(
				'taxonomy'	=> WPNW_CAT,
				'field'		=> 'term_id',
				'terms'		=> $cat
			));
	}

	$query = new WP_Query( $args );

	$count			= 0;
	$post_count		= $query->post_count;
	$max_num_pages 	= $query->max_num_pages;

	if( $type == 'list' ) {
		$gridcol = $query->post_count;
	} else {
		$gridcol = ( ! empty( $grid ) && $grid <= 4 ) ? $grid : 1;
	}
?>
	<div class="wpnawfree-plugin news-clearfix <?php echo esc_attr( $extra_class ); ?>" id="wpnw-news-<?php echo esc_attr( $unique ); ?>">

		<?php if ( $query->have_posts() ) :
			while ( $query->have_posts() ) : $query->the_post();

			$count++;
			$news_links = array();
			$terms		= get_the_terms( $post->ID, WPNW_CAT );

			if( $terms ) {
				foreach ( $terms as $term ) {
					$term_link		= get_term_link( $term );
					$news_links[]	= '<a href="'. esc_url( $term_link ) .'">'.wp_kses_post( $term->name ).'</a>';
				}
			}

			$cate_name	= join( ", ", $news_links );

			// CSS class
			$main_wrap_css = "wpnaw-blog-class";
			$main_wrap_css .= $show_date			? ' has-date'		: ' has-no-date';
			$main_wrap_css .= ( $type == 'list' )	? " news-col-list"	: " news-col-{$gridcol}";

			if( $gridcol > 1 ) {
				if( $count % $gridcol == 1 ) {
					$main_wrap_css .= ' wpnaw-first';
				} elseif ( $count % $gridcol == 0 ) {
					$main_wrap_css .= ' wpnaw-last';
				}
			} else {
				$main_wrap_css .= ( $count == 1 ) ? ' wpnaw-first' : '';
				$main_wrap_css .= ( $count >= $post_count ) ? ' wpnaw-last' : '';
			}
		?>

			<div id="post-<?php the_ID(); ?>" class="news type-news <?php echo esc_attr( $main_wrap_css ); ?>">
				<div class="news-inner-wrap-view news-clearfix <?php if ( ! has_post_thumbnail() ) { echo 'wpnaw-news-no-image'; } ?>">

					<?php if ( has_post_thumbnail() ) { ?>
					<div class="news-thumb">
						<?php if( $gridcol == 1 && $type == 'grid' ) { ?>
							<div class="grid-news-thumb">
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'large' ); ?></a>
							</div>
						<?php } else { ?>
							<div class="grid-news-thumb">
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large' ); ?></a>
							</div>
						<?php } ?>
					</div>
					<?php } ?>

					<div class="news-content">
						<?php if( $type == 'grid' && $gridcol == 1 && $show_date ) { ?>
							<div class="date-post">
								<h2><span><?php echo get_the_date('j'); ?></span></h2>
								<p><?php echo get_the_date('M y'); ?></p>
							</div>
						<?php } elseif( $gridcol > 1 ) { ?>
							<div class="grid-date-post">
								<?php 
									echo ( $show_date )												? get_the_date()	: "";
									echo ( $show_date && $show_category_name && $cate_name != '' )	? " / "				: "";
									echo ( $show_category_name && $cate_name != '' )				? wp_kses_post($cate_name)		: "";
								?>
							</div>
						<?php } ?>

						<div class="post-content-text">
							<?php the_title( sprintf( '<h3 class="news-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
							
							if( $show_category_name && $gridcol == 1 ) { ?>
								<div class="news-cat"><?php echo wp_kses_post( $cate_name ); ?></div>
							<?php }

							if( $show_content ) { ?>
								<div class="news-content-excerpt">
									<?php if( empty( $show_full_content ) ) { ?>
										<div class="news-short-content">
											<?php echo wpnw_limit_words( $post->ID, get_the_content(), $words_limit, '...' ); ?>
										</div>
										<a href="<?php the_permalink(); ?>" class="news-more-link"><?php esc_html_e( 'Read More', 'sp-news-and-widget' ); ?></a>
									<?php } else {
										the_content();
									} ?>
								</div><!-- .entry-content -->
							<?php } ?>
						</div>
					</div>
				</div><!-- #post-## -->
			</div><!-- #post-## -->
		<?php endwhile;
		endif;

		if( $pagination && $max_num_pages > 1 ) { ?>
			<div class="news_pagination wpnw-<?php echo esc_attr( $pagination_type ); ?>">
				<?php echo wpnw_news_pagination( array( 'paged' => $paged , 'total' => $max_num_pages, 'multi_page' => $multi_page, 'pagination_type' => $pagination_type, 'unique' => $unique ) ); ?>
			</div>
		<?php } ?>
	</div>

	<?php
	wp_reset_postdata(); // Reset WP Query

	$content .= ob_get_clean();
	return $content;
}

// 'sp_news' shortcode
add_shortcode( 'sp_news', 'wpnw_get_news_shortcode' );