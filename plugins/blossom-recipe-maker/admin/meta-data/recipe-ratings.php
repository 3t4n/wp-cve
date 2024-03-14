<?php
/**
 * Provides the 'Recipe Ratings' view for the corresponding tab in the Post Meta Box.
 *
 * @link       test.com
 * @since      1.0.0
 *
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/admin/meta-data
 */
?>
 
<div id="blossom-recipe-tab-recipe-ratings" class="inside hidden">

	<div class="br-recipe-rating">

		<h1><?php esc_html_e( 'Rating', 'blossom-recipe-maker' ); ?>
			<span class="recipe-excerpt-tooltip" title="<?php esc_html_e( 'Use this section to add ratings to your recipe.', 'blossom-recipe-maker' ); ?>">
					<i class="far fa-question-circle"></i>
			</span>
		</h1>

		<div class="brm-comment-form-rating">

			<h4><?php esc_html_e( 'Rate this Recipe', 'blossom-recipe-maker' ); ?></h4>

			<?php
			$recipe = get_post_meta( get_the_ID(), 'br_recipe', true );

			if ( isset( $recipe['ratings'] ) ) {
				$rating = $recipe['ratings'];
			} else {
				$rating = 0;
			}

			?>
			<div class="brm-rating-stars">
				<?php
				for ( $i = 1; $i <= 5; $i++ ) {

					if ( $i <= $rating ) {
						echo '<span class="brm-rating-star rated" data-rating="' . esc_attr( $i ) . '">';
					} else {
						echo '<span class="brm-rating-star" data-rating="' . esc_attr( $i ) . '">';
					}
					ob_start();
					echo wp_kses_post( file_get_contents( BLOSSOM_RECIPE_MAKER_URL . '/public/images/star-empty.svg' ) );
					$star_icon = ob_get_contents();
					ob_end_clean();

					echo wp_kses_post( apply_filters( 'brm_comment_rating_star_icon', $star_icon ) );

                    echo '</span>'; // @phpcs:ignore;
				}
				?>
			</div>
			<input id="brm-comment-rating" name="br_recipe[ratings]" type="hidden" value="<?php echo esc_attr( $rating ); ?>">
		</div>

	</div>
</div>
