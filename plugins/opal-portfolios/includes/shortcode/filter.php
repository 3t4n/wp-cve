<?php
/**
 * 
 * @package  Opal Portfolios
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function portfolio_filter_shortcode( $atts, $content = null) {
	
	extract(shortcode_atts(array(
		'limit' 				=> '20',
		'category' 				=> '',
		'order'					=> 'DESC',
		'orderby'				=> 'date',
		'column' 					=> 3,
		'padding'				=> '',
		'style'					=> 'classic',
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

	global $post;
	       			
	$args = array ( 
        'post_type'      => PE_POST_TYPE,
        'orderby'        => $orderby, 
        'order'          => $order,
        'posts_per_page' => $posts_per_page,
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
 	ob_start();  	
 	?>

 	<div id="portfolio_filter" class="portfolio-main-wrapper  lightgallery-detect-container" >

		<div class="portfolio-filter-select-wrap">
	    	<div class="nav-inner wow fadeInUp">
		      <ul class="nav nav-tabs isotope-filter categories_filter" data-related-grid="isotope-<?php echo esc_attr( $_id ); ?>">
		        <?php
		  
		        	echo '<li class="active" ><a href="javascript:void(0);" title="" data-option-value=".all">'.__('All', 'opalportfolios'	).'</a></li>';

		        if ( !empty($terms) && !is_wp_error($terms) ){
		          	foreach ( $terms as $term ) {
		          		//if($term->slug === )
		            	echo '<li><a href="javascript:void(0);" title="" data-option-value=".'.esc_attr( $term->slug ).'">'.esc_html($term->name).'</a></li>';
		            }
		        }
		        ?>
		      </ul>
		    </div>
	    </div>

		<div class="grid portfolio-entries clearfix <?php echo esc_attr($grid_class); ?>" id="isotope-<?php echo esc_attr( $_id ); ?>">

			<?php
			while ($query->have_posts()) : $query->the_post(); 
				$item_classes = 'all ';
				$item_classes = opalportfolio_terms_related($query->post->ID, $item_classes);	
				?>

				<div class="grid-item  <?php echo esc_attr( $item_classes ); ?>">
					<?php if ($style == "boxed"):?>
						<?php echo Opalportfolio_Template_Loader::get_template_part( 'content-portfolio-boxed', $args_template); ?>
					<?php elseif ($style === "list"):?>
						<?php echo Opalportfolio_Template_Loader::get_template_part( 'content-portfolio-list', $args_template); ?>
					<?php else: ?>
						<?php echo Opalportfolio_Template_Loader::get_template_part( 'content-portfolio-classic', $args_template); ?> 
					<?php endif; ?>	
			    </div>
			<?php endwhile; ?>
		</div>
	</div>
	<?php 	
		wp_reset_query();
		$content .= ob_get_clean();
	    return $content;
}
add_shortcode("portfolio_filter", "portfolio_filter_shortcode");

