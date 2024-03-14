<?php

function brm_recent_popular_recipe_shortcode( $atts = '' ) {
	ob_start();
	$atts = shortcode_atts(
		array(
			'num_posts' => '3',
			'popular'   => '',
			'title'     => 'Recipes',
		),
		$atts,
		'brm-recipes'
	);

	$arg = array(
		'post_type'           => 'blossom-recipe',
		'post_status'         => 'publish',
		'posts_per_page'      => $atts['num_posts'],
		'ignore_sticky_posts' => true,
	);
	if ( isset( $atts['popular'] ) && $atts['popular'] == 'views' ) {
		$arg['orderby']  = 'meta_value_num';
		$arg['meta_key'] = '_brm_view_count';
	} elseif ( $atts['popular'] == 'comments' ) {
		$arg['orderby'] = 'comment_count';
	}
	$brm_img_size = apply_filters( 'brm_custom_img_size', 'recipe-maker-thumbnail-size' );
	$qry          = new WP_Query( $arg );

	if ( $qry->have_posts() ) {
		?><div class="custom-shortcode-grid-holder">
			<h3 class="shortcode-title"><?php echo esc_attr( $atts['title'] ); ?></h3>
		<?php
		while ( $qry->have_posts() ) {
			$qry->the_post();
			?>
					<div class="col">
						<div class="holder">
							<div class="img-holder">
								<a target="_blank" href="<?php the_permalink(); ?>" class="post-thumbnail">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( $brm_img_size );
			} else {
				$obj = new Blossom_Recipe_Maker_Functions();
				$obj->brm_get_fallback_svg( $brm_img_size );// falback
			}
			?>
								</a>
							</div>
							<h3 class="entry-title">
								<a target="_blank" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
						</div>
					</div>
			<?php
		}
		wp_reset_postdata();
		?>
		</div>
		<?php
	}

	$output = ob_get_contents();
	echo wp_kses_post( apply_filters( 'brm_recipes_shortcode_filter', $output, $atts ) );
	ob_end_clean();

	return $output;
}

add_shortcode( 'brm-recipes', 'brm_recent_popular_recipe_shortcode' );
