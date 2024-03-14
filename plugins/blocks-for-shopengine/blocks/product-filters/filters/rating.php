<?php 
	namespace Elementor; 
	defined('ABSPATH') || exit;
	// check if the collapse enabled
	$collapse	   = false;
	$collapse_expand = '';

	if( $settings['shopengine_filter_view_mode']['desktop'] === 'collapse' ) {
		$collapse	   = true;
	}

	if(($settings['shopengine_filter_rating_expand_collapse']['desktop'] === true || 
		isset($_GET['rating_filter']) && 
		!empty($_GET['rating_nonce']) && 
		wp_verify_nonce( sanitize_text_field( wp_unslash($_GET['rating_nonce']) ), "rating_filter" )) ) {
		$collapse_expand = 'open';
	}
?>

<div class="shopengine-filter-single <?php echo esc_attr( $collapse ? 'shopengine-collapse' : '' ) ?>">
	<form
		action="" method="get"
		class="shopengine-filter shopengine-filter-rating">
		
		<?php 
			/**
			 * 
			 * show filter title
			 * 
			 */
			if ( isset( $settings['shopengine_filter_rating_title']['desktop'] ) ) : 
		?>
			<div class="shopengine-filter <?php echo esc_attr( $collapse_expand ) ?>">
				<h3 class="shopengine-product-filter-title">
					<?php 
						echo esc_html($settings['shopengine_filter_rating_title']['desktop']);
						if( $collapse ) echo '<i class="eicon-chevron-right shopengine-collapse-icon"></i>';
					?>
				</h3>
			</div>
		<?php 
			endif; // end of filter title 
			if( $collapse ) echo '<div class="shopengine-collapse-body '. esc_attr($collapse_expand) .'">';
		?>

		<div class="shopengine-filter-rating__labels">
			<?php
			/**
			 * 
			 * loop through list item
			 * 
			 */ 
			for( $i = 5; $i >=1; $i-- ) : 
			?>
				<a 
					href="#" class="rating-label-triger shopengine-rating-name-<?php echo esc_attr($i) ?>" 
					data-target="rating_filter_input" 
					for="rating_filter" 
					data-rating="<?php echo esc_attr( $i ) ?>">

					<span class="shopengine-filter-rating__labels--mark">
						<span>
							<?php render_icon( $settings['shopengine_check_icon']['desktop'], [ 'aria-hidden' => 'true' ] );  ?>
						</span>
					</span>
					<div class="shopengine-filter-rating__labels__stars">

						<div class="shopengine-filter-rating__labels-star inactive">  
							<?php for( $star = 1; $star <= 5; $star++ ) : 
								render_icon( $settings['shopengine_star_rating']['desktop'], [ 'aria-hidden' => 'true' ] ); 
							endfor; ?>
						</div>
						
						<div class="shopengine-filter-rating__labels-star active">  
							<?php for( $star = 1; $star <= $i; $star++ ) : 
								render_icon( $settings['shopengine_star_rating']['desktop'], [ 'aria-hidden' => 'true' ] ); 
							endfor; ?>
						</div>

					</div>
				</a>
			<?php endfor; ?>
		</div>
		
		<?php 
			if( $collapse ) echo '</div>'; // end of collapse body container
		?>

		<div class="shopengine-filter-price-fields">
			<input type="hidden" id="rating_filter_input" name="rating_filter" />
			<input type="hidden" name="rating_nonce" value="<?php echo esc_attr(wp_create_nonce("rating_filter")) ?>">
		</div>
	</form>
</div>