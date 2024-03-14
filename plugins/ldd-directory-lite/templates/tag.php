<?php
/*
* File version: 2
*/
get_header();
?>
<div class=" bootstrap-wrapper">
	<?php
		/**
		 * ldd_before_main_content hook.
		 *
		 * @hooked ldd_output_content_wrapper - 10 (outputs opening divs for the content)
		 */
		do_action( 'ldd_before_main_content' );
		$version = 2;
	?>

	<?php echo ldl_get_header();  ?>

	<div class="col-md-12 abcd">
		<div class="list-group">
			<?php echo ldl_get_categories( get_queried_object()->term_id ); ?>
		</div>
	</div>

	<?php
		$paged          = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$posts_per_page = get_option( 'posts_per_page' );

		if ( have_posts() ) :
			global $wp_query;

			$sort_by    = ldl()->get_option( 'directory_listings_sort', 'business_name' );
			$sort_order = ldl()->get_option( 'directory_listings_sort_order', 'asc' );
			$sub_check  = ldl()->get_option( 'subcategory_listings', 0 );
			$subcategory_listings = ($sub_check == 0) ? true : false;

		

           
			$term_array = $wp_query->get_queried_object();
			$tag_id     = $term_array->term_id;
			$tax_query  = array(
				array(
					'taxonomy' => LDDLITE_TAX_TAG,
					'field'    => 'id',
					'terms'    => $tag_id,
					
				)
			);
            
           	if ( $sort_by == "business_name" ):
				query_posts( array(
					'orderby'        => 'title',
					'order'          => $sort_order,
					'post_type'      => LDDLITE_POST_TYPE,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'tax_query'      => $tax_query
				) );
				elseif ( $sort_by == "id" ):
				query_posts( array(
					'orderby'        => 'ID',
					'order'          => $sort_order,
					'post_type'      => LDDLITE_POST_TYPE,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'tax_query'      => $tax_query
				) );
			/* Featured Listings and other listings combination with pagination */
			elseif ( $sort_by == "featured" ):
				query_posts( array(
					'tax_query'      => $tax_query,
					'orderby'        => 'menu_order',
					'order'          => $sort_order,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'post_type'      => LDDLITE_POST_TYPE,
				) );
			elseif ( $sort_by == "zip" ):
				query_posts( array(
					'meta_key'       => '_lddlite_postal_code',
					'order'          => $sort_order,
					'orderby'        => 'meta_value',
					'post_type'      => LDDLITE_POST_TYPE,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'tax_query'      => $tax_query
				) );
			
				elseif ( $sort_by == "country" ):
				query_posts( array(
					'meta_key'       => '_lddlite_country',
					'order'          => $sort_order,
					'orderby'        => 'meta_value',
					'post_type'      => LDDLITE_POST_TYPE,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'tax_query'      => $tax_query

				) );
				elseif ( $sort_by == "city" ):
				query_posts( array(
					'meta_key'       => '_lddlite_city',
					'order'          => $sort_order,
					'orderby'        => 'meta_value',
					'post_type'      => LDDLITE_POST_TYPE,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'tax_query'      => $tax_query

				) );
				elseif ( $sort_by == "state" ):
				query_posts( array(
					'meta_key'       => '_lddlite_state',
					'order'          => $sort_order,
					'orderby'        => 'meta_value',
					'post_type'      => LDDLITE_POST_TYPE,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'tax_query'      => $tax_query

				) );
			elseif ( $sort_by == "category" ):
				query_posts( array(
					'order'          => $sort_order,
					'post_type'      => LDDLITE_POST_TYPE,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'tax_query'      => $tax_query
				) );
			elseif ( $sort_by == "random" ):
				query_posts( array(
					'orderby'        => 'rand',
					'order'          => $sort_order,
					'post_type'      => LDDLITE_POST_TYPE,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'tax_query'      => $tax_query
				) );
			endif;
			
			$listing_view = ldl()->get_option( 'directory_view_type', 'compact' );
			if ( $listing_view == "grid" ) {
				?>
				<div class='grid js-isotope' data-isotope-options='{ \"itemSelector\": \".grid-item\", \"layoutMode\": \"fitRows\" }'>
                <?php
			}
			while ( have_posts() ) {
				the_post();
				ldl_get_template_part( 'loop/listing', $listing_view );
			}
			if ( $listing_view == "grid" ) {
				?> </div>
                <?php
				wp_enqueue_script( 'isotope-pkgd', LDDLITE_URL . '/public/js/isotope.pkgd.min.js' );
			}
			wp_reset_postdata();
			?>
			<div class="clearfix"></div>
		<?php else : ?>
			<?php ldl_get_template_part( 'loop/no-listings-found.php' ); ?>
		<?php endif; ?>

	<?php
		/**
		 * ldd_after_directory_loop hook.
		 *
		 * @hooked ldd_default_pagination - 10
		 */
		do_action( 'ldd_after_directory_loop' );
	?>
	<?php
		/**
		 * ldd_after_main_content hook.
		 *
		 * @hooked ldd_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'ldd_after_main_content' );
	?>
	</div>
<?php get_footer(); ?>