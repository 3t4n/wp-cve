<?php
/**
 * Template Name: Cuisine Template
 * This template can be overridden by copying it to yourtheme/templates/template-recipe-cuisine.php.
 */
get_header(); ?>

<div class="wrap">

	<?php
	// get the currently queried taxonomy term, for use later in the template file
	global $post;
	$taxonomyName   = 'recipe-cuisine';
	$termchildren   = get_terms( $taxonomyName );
	$options        = get_option( 'br_recipe_settings', array() );
	$posts_per_page = ( isset( $options['no_of_recipes'] ) && ( ! empty( $options['no_of_recipes'] ) ) ) ? $options['no_of_recipes'] : get_option( 'posts_per_page' );
	?>
	<header class="page-header">
		<h2 class="page-title" data-id="<?php echo esc_attr( $taxonomyName ); ?>"><?php the_title(); ?></h2>
		<?php
		if ( has_post_thumbnail() ) :
			?>
			<div class="page-feat-image">
			<?php
			the_post_thumbnail();
			?>
			   
			</div>
			<?php
		endif;
		$post    = get_post( $post->ID );
		$content = $post->post_content;
		if ( ! empty( $content ) ) :
			?>
			<div class="page-content">
			<?php
			$content = apply_filters( 'the_content', $post->post_content );
			echo wp_kses_post( $content );
			?>
			</div>
			<?php
		endif;
		?>
	</header>

	<div id="primary" class="content-area" itemscope itemtype="http://schema.org/ItemList">
		<main id="main" class="site-main" role="main">
			<?php

			if ( $termchildren ) {
				?>
					 
				<div class="recipe-cuisine-holder">
				<?php
				foreach ( $termchildren as $child ) {
					$term                   = get_term_by( 'id', $child->term_id, $taxonomyName );
					$term_link              = get_term_link( $term );
					$child_term_description = term_description( $term, $taxonomyName );
					?>
						<div class="recipe-cuisine-<?php echo esc_attr( $term->name ); ?>"> 
							<div class="item">
								<div class="img-holder">
									<a href="<?php echo esc_url( $term_link ); ?>">
					<?php
					$image_id = get_term_meta( $child->term_id, 'taxonomy-thumbnail-id', true );
					if ( isset( $image_id ) && $image_id != '' ) {
						$img_size = apply_filters( 'br_cuisine_img_size', 'recipe-maker-single-size' );
						echo wp_kses_post( wp_get_attachment_image( $image_id, $img_size ) );
					}
					?>
										<h2 class="child-title" itemprop="name"><?php echo esc_attr( $term->name ); ?></h2>
									</a>                                    
								</div>
					<?php
					if ( ! empty( $child_term_description ) ) :
						?>
									<div class="desc-holder">
						<?php echo esc_html( $child_term_description ); ?>
									</div>
						<?php
					endif;
					?>
							</div>

					<?php

					$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

					$cuisine_tax_post_args = array(
						'post_type'                => 'blossom-recipe',
						'posts_per_page'           => $posts_per_page,
						'wpse_search_or_tax_query' => true,
						'order'                    => 'ASC',
						'tax_query'                => array(
							array(
								'taxonomy'         => $taxonomyName,
								'field'            => 'slug',
								'terms'            => $term->slug,
								'include_children' => false,
							),
						),
						'paged'                    => $paged,
					);

					$cuisine_tax_posts = new WP_Query( $cuisine_tax_post_args );

					if ( $cuisine_tax_posts->have_posts() ) :

						while ( $cuisine_tax_posts->have_posts() ) :
							$cuisine_tax_posts->the_post();

							do_action( 'br_recipe_archive_action' );

						endwhile;

						if ( $child->count > $posts_per_page ) {
							echo '<div class="load-cuisines"><a href="' . esc_url( $term_link ) . '"><span>' . esc_html__( 'See More Recipes', 'blossom-recipe-maker' ) . '</span></a></div>';
						}
						wp_reset_postdata();

					endif;
					?>
						</div>
					<?php

				}
				?>
				</div> 
					
				<?php
			}

			?>
			
		</main>
	</div>

</div><!-- .wrap -->

<?php get_footer(); ?>
