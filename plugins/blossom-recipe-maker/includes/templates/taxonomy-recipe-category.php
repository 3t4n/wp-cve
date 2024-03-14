<?php
/**
 * The template for displaying recipe taxonomy pages
 *
 * This template can be overridden by copying it to yourtheme/taxonomy-recipe-category.php.
 */
get_header(); ?>

<div class="wrap">

	<?php
	// get the currently queried taxonomy term, for use later in the template file
	$term         = get_queried_object();
	$termId       = $term->term_id;
	$termSlug     = $term->slug;
	$taxonomyName = $term->taxonomy;
	$postType     = 'blossom-recipe';

	$options        = get_option( 'br_recipe_settings', array() );
	$posts_per_page = ( isset( $options['no_of_recipes'] ) && ( ! empty( $options['no_of_recipes'] ) ) ) ? $options['no_of_recipes'] : get_option( 'posts_per_page' );

	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

	$parent_tax_post_args = array(
		'post_type'      => $postType, // Your Post type Name that You Registered
		'posts_per_page' => -1,
		'order'          => 'ASC',
		'tax_query'      => array(
			array(
				'taxonomy'         => $taxonomyName,
				'field'            => 'slug',
				'terms'            => $termSlug,
				'include_children' => false,
			),
		),
		'paged'          => $paged,
	);

	$parent_tax_post = new WP_Query( $parent_tax_post_args );

	if ( $parent_tax_post->have_posts() ) :
		?>
		<header class="page-header">
		<?php
		the_archive_title( '<h1 class="page-title">', '</h1>' );
		the_archive_description( '<div class="taxonomy-description">', '</div>' );
		?>
		</header><!-- .page-header -->

		<div id="primary" class="content-area" itemscope itemtype="http://schema.org/ItemList">
		<main id="main" class="site-main" role="main">
			<div class="parent-taxonomy-wrap">

		<?php
		while ( $parent_tax_post->have_posts() ) :
			$parent_tax_post->the_post();

			do_action( 'br_recipe_archive_action' );

		endwhile;
		echo '</div>';

	endif;
	wp_reset_postdata();

	$catChildren = get_term_children( $termId, $taxonomyName );

	foreach ( $catChildren as $child ) {
		$term                   = get_term_by( 'id', $child, $taxonomyName );
		$term_link              = get_term_link( $term );
		$child_term_description = term_description( $term, $taxonomyName );

		$paged       = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$child_query = new WP_Query(
			array(
				'post_type'      => $postType,
				'posts_per_page' => -1,
				'tax_query'      => array(
					array(
						'taxonomy' => $taxonomyName,
						'field'    => 'slug',
						'terms'    => $term->slug,
					),
				),
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'paged'          => $paged,

			)
		);

		if ( $child_query->have_posts() ) :
			?>
			<div class="child-taxonomy-wrap">
				<h2 class="child-title">
					<a href="<?php echo esc_url( $term_link ); ?>">
			   <?php echo esc_attr( $term->name ); ?>
					</a>
				</h2>
			   <?php
				if ( ! empty( $child_term_description ) ) :
					?>
						<div class="child-description">
					<?php echo esc_html( $child_term_description ); ?>
						</div>
					<?php
				endif;

				while ( $child_query->have_posts() ) :
					$child_query->the_post();

					do_action( 'br_recipe_archive_action' );

				endwhile;
				echo '</div>';
		endif;

	}
	wp_reset_postdata();

	?>
	</main><!-- #main -->
	</div><!-- #primary -->

</div><!-- .wrap -->

<?php get_footer(); ?>
