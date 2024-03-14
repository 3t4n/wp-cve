<?php
/**
 * 
 * @package  Opal Portfolios
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function portfolio_grid_shortcode( $atts, $content = null) {
	
	extract(shortcode_atts(array(
		'limit' 				=> '20',
		'category' 				=> '',
		'order'					=> 'DESC',
		'orderby'				=> 'date',
		'column' 					=> 3,
		'padding'				=> '',
		'style'					=> 'classic',
		'masonry'				=> 'no',
		'pagination'			=> 'no',
		'show_category'			=> 'yes',
		'show_description'		=> 'yes',
		'show_readmore'			=> 'no',

	), $atts));
	

	$posts_per_page 		= !empty($limit) 						? $limit						: '20';
	$column 				= !empty($column) 						? $column 						: 3;
	$cat 					= (!empty($category))					? explode(',',$category) 		: '';
	$order 					= ( strtolower($order) == 'asc' ) 		? 'ASC' 						: 'DESC';
	$orderby 				= !empty($orderby) 						? $orderby 						: 'date';

	$args_template = array(
		'show_category'		=> $show_category,
		'show_description'	=> $show_description,
		'show_readmore'		=> $show_readmore,
	);
	// Required enqueue_script
	wp_enqueue_style( 'isotope-css' );
	wp_enqueue_script( 'isotope' );
	wp_enqueue_script( 'lightgallery-js' );

	global $post;

	if ( get_query_var( 'paged' ) ) {
    	$paged = get_query_var( 'paged' );
	} elseif ( get_query_var( 'page' ) ) {
	    $paged = get_query_var( 'page' );
	} else {
	    $paged = 1;
	} 

	$args = array ( 
        'post_type'      => PE_POST_TYPE,
        'orderby'        => $orderby, 
        'order'          => $order,
        'posts_per_page' => $posts_per_page,
        'paged'			 => $paged
    );
	     
	if($cat != "") {
		$args['tax_query'] = array(
			array(
				'taxonomy' 	=> PE_CAT,
				'field' 	=> 'slug',
				'terms' 	=> $cat
			)
		);
	}
	
    $query 			= new WP_Query($args);
	$post_count 	= $query->post_count;
	$_id = time()+rand();	
	$terms = get_terms( PE_CAT ,array('orderby' => 'id', 'slug' => $cat,));

	if(!empty($padding) && $padding == 'yes' || $padding == '' ) { 
		$show_padding = 'show_padding';
	}
	$grid_class = '';
	$grid_class .= 'column-'.$column . ' ';
	$grid_class .= 'grid-style-'.$style. ' ';
	if(!empty($padding) && $padding == 'yes' || $padding == '' ) {
		$grid_class .= 'show_padding';
	}
	if(!empty($masonry) && $masonry == 'yes' ) {
		$grid_class .= ' grid_masonry';
	}

 	ob_start();  

 	?>
 
 	<div class="portfolio-main-wrapper lightgallery-detect-container" >

		<div class="grid portfolio-entries clearfix <?php echo esc_attr($grid_class); ?>">

			<?php
			$i = 1;
			while ($query->have_posts()) : $query->the_post(); 
				if($i%($column+1) == 0 ) {
					$clear_both = "first";
				}else {
					$clear_both = "";
				} ?>
				
				<div class="grid-item <?php echo esc_attr($clear_both); ?>" data-portfolio="portfolio-<?php the_ID();?>">
					<?php if ($style === "boxed"):?>
						<?php echo Opalportfolio_Template_Loader::get_template_part( 'content-portfolio-boxed', $args_template); ?>
					<?php elseif ($style === "list"):?>
						<?php echo Opalportfolio_Template_Loader::get_template_part( 'content-portfolio-list', $args_template); ?>
					<?php else: ?>
						<?php echo Opalportfolio_Template_Loader::get_template_part( 'content-portfolio-classic', $args_template); ?>
					<?php endif; ?>	
			    </div>

			<?php $i++; endwhile; ?>
		</div>
		<?php if( $pagination === "yes" ) : ?>
			<div class="portfolio_navigation clearfix">
				<?php echo portfolio_pagination( $query->max_num_pages, "", $paged ); ?>
			</div>
		<?php endif; ?>
	</div>
	<?php 	
		wp_reset_query();
		$content .= ob_get_clean();
	    return $content;
}
add_shortcode("portfolio_grid", "portfolio_grid_shortcode");