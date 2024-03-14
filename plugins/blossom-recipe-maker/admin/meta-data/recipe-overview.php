<?php
/**
 * Provides the 'Recipe Details' view for the corresponding tab in the Post Meta Box.
 *
 * @link       test.com
 * @since      1.0.0
 *
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/admin/meta-data
 */
?>
 
<div id="blossom-recipe-tab-recipe-overview" class="inside">

	<div id="br-recipe-overview">

		<h1><?php esc_html_e( 'Overview', 'blossom-recipe-maker' ); ?></h1>

		<?php
		$post_id = get_the_ID();
		$details = get_post_meta( $post_id, 'br_recipe', true );

		if ( get_post_status( $post_id ) == 'publish' ) {
			?>
			<div class="br-recipe-shortcode">

				<h4><?php esc_html_e( 'Recipe Shortcode', 'blossom-recipe-maker' ); ?>
					<span class="recipe-shortcode-tooltip" title="<?php esc_html_e( 'You can use this shortcode to display your Recipe in posts/pages.', 'blossom-recipe-maker' ); ?>">
					<i class="far fa-question-circle"></i>
				</span>
				</h4>

				<?php

				$recipe_shortcode = '[recipe-maker id=' . "'" . $post_id . "'" . ']';

				?>
				<input type="text" id="shortcode" value="<?php echo esc_html( $recipe_shortcode ); ?>" readonly onClick="this.setSelectionRange(0, this.value.length)"/>

			</div>
		
			<?php
		}

		?>
		
		<div class="br-recipe-meta-difficulty">

			<?php
			$levels = Blossom_Recipe_Maker_Functions::difficulty_levels();

			?>
			
			<label for="br_recipe_difficulty_level">
				<h4><?php esc_html_e( 'Difficulty Level', 'blossom-recipe-maker' ); ?></h4>
			</label>

			<select id="br_recipe_difficulty_level" name="br_recipe[details][difficulty_level]">
				
				<option value=""><?php esc_html_e( '--Select--', 'blossom-recipe-maker' ); ?></option>
				<?php
				foreach ( $levels as $level => $name ) {
					?>

						<option value="<?php echo esc_attr( $level ); ?>"  
												  <?php
													if ( isset( $details['details']['difficulty_level'] ) && ( $details['details']['difficulty_level'] == $level ) ) {
														echo esc_attr( 'selected' ); }
													?>
						> <?php echo esc_attr( $name ); ?> 
						</option>
						<?php

				}
				?>
				
			</select>

		</div>

		<div class="br-recipe-meta-prep-time">

			<label for="br_recipe_prep_time">
				<h4><?php esc_html_e( 'Preparation Time (mins)', 'blossom-recipe-maker' ); ?></h4>
			</label>
			<input id="br_recipe_prep_time" name="br_recipe[details][prep_time]" placeholder="10" min="0" type="number" value="<?php echo isset( $details['details']['prep_time'] ) ? esc_attr( $details['details']['prep_time'] ) : ''; ?>" onchange="calcTotalTime()">			

		</div>

		<div class="br-recipe-meta-cook-time">

			<label for="br_recipe_cook_time">
				<h4><?php esc_html_e( 'Cook Time (mins)', 'blossom-recipe-maker' ); ?></h4>
			</label>
			<input id="br_recipe_cook_time" name="br_recipe[details][cook_time]" placeholder="20" min="0" type="number" value="<?php echo isset( $details['details']['cook_time'] ) ? esc_attr( $details['details']['cook_time'] ) : ''; ?>" onchange="calcTotalTime()">

		</div>

		<div class="br-recipe-meta-total-time">
			
			<label for="br_recipe_total_time">
				<h4><?php esc_html_e( 'Total Time (mins)', 'blossom-recipe-maker' ); ?></h4>
			</label>
			<input id="br_recipe_total_time" name="br_recipe[details][total_time]" placeholder="30" min="0" type="number" value="<?php echo ( isset( $details['details']['prep_time'] ) && ( ! empty( $details['details']['prep_time'] ) ) && isset( $details['details']['cook_time'] ) && ( ! empty( $details['details']['cook_time'] ) ) ) ? ( esc_attr( absint( $details['details']['prep_time'] ) + absint( $details['details']['cook_time'] ) ) ) : ''; ?>" readonly/>

		</div>

	</div>
 
</div>
