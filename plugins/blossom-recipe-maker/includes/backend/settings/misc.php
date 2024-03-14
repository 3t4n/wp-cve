<?php
/**
 * Provides the 'Misc' view for the corresponding tab in the Settings Page.
 *
 * @link  test.com
 * @since 1.0.0
 *
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/includes/backend/settings
 */
?>

<div id="blossom-recipe-misc-settings-tab" class="inside">

	<?php
	$br_recipe_settings = get_option( 'br_recipe_settings', true );
	?>

	<div class="blossom-recipe-fields-settings">
		<h4><?php esc_html_e( 'Global Recipe Toogles : ', 'blossom-recipe-maker' ); ?></h4>

		<div class="recipe-note">
			<p><?php esc_html_e( 'You can quickly hide or show different recipe elements with these checkboxes.', 'blossom-recipe-maker' ); ?></p>
		</div>

		<div class="blossom-recipe-settings has_checkbox">

			<h4><?php esc_html_e( 'Adjustable Servings : ', 'blossom-recipe-maker' ); ?></h4>

			<span class="recipe-settings-tooltip" title="<?php esc_html_e( 'If checked it allows user to dynamically adjust the servings of recipes.', 'blossom-recipe-maker' ); ?>">
				<i class="far fa-question-circle"></i>
			</span>


			<input type="checkbox" id="br_recipe_settings[adjust_servings]" class="hide-adjust_servings_box" name="br_recipe_settings[adjust_servings]" value="1" 
			<?php
			if ( isset( $br_recipe_settings['adjust_servings'] ) && $br_recipe_settings['adjust_servings'] == 1 ) {
				echo esc_attr( 'checked' );
			}
			?>
																																									>

			<label for="br_recipe_settings[adjust_servings]" class="checkbox-label"></label>
		</div>

		<div class="blossom-recipe-settings has_checkbox">

			<h4><?php esc_html_e( 'Featured Image : ', 'blossom-recipe-maker' ); ?></h4>

			<span class="recipe-settings-tooltip" title="<?php esc_html_e( 'If checked it shows featured image on single page.', 'blossom-recipe-maker' ); ?>">
				<i class="far fa-question-circle"></i>
			</span>


			<input type="checkbox" id="br_recipe_settings[feat_image]" class="disable-feat-image" name="br_recipe_settings[feat_image]" value="1" 
			<?php
			if ( isset( $br_recipe_settings['feat_image'] ) && $br_recipe_settings['feat_image'] == 1 ) {
				echo esc_attr( 'checked' );
			}
			?>
																																					>
			<label for="br_recipe_settings[feat_image]" class="checkbox-label"></label>

		</div>

		<div class="blossom-recipe-settings has_checkbox">

			<h4><?php esc_html_e( 'Recipes in Home Page : ', 'blossom-recipe-maker' ); ?></h4>

			<span class="recipe-settings-tooltip" title="<?php esc_html_e( 'If checked Recipe posts will show up on your front page and author archive page.', 'blossom-recipe-maker' ); ?>">
				<i class="far fa-question-circle"></i>
			</span>


			<input type="checkbox" id="br_recipe_settings[act_as_posts]" name="br_recipe_settings[act_as_posts]" value="1" 
			<?php
			if ( isset( $br_recipe_settings['act_as_posts'] ) && $br_recipe_settings['act_as_posts'] == 1 ) {
				echo esc_attr( 'checked' );
			}
			?>
																															>
			<label for="br_recipe_settings[act_as_posts]" class="checkbox-label"></label>

		</div>

	</div>

	<div class="blossom-recipe-fields-settings">

		<div class="blossom-recipe-settings">

			<h4><?php esc_html_e( 'Print Button Text : ', 'blossom-recipe-maker' ); ?></h4>

			<input type="text" id="br_recipe_settings[print_btn_txt]" name="br_recipe_settings[print_btn_txt]" value="<?php echo isset( $br_recipe_settings['print_btn_txt'] ) && $br_recipe_settings['print_btn_txt'] != '' ? esc_attr__( $br_recipe_settings['print_btn_txt'], 'blossom-recipe-maker' ) : esc_attr__( 'Print Recipe', 'blossom-recipe-maker' ); ?>" placeholder="<?php esc_attr_e( 'Print button label', 'blossom-recipe-maker' ); ?>">

		</div>
	</div>

	<div class="blossom-recipe-fields-settings">

		<div class="blossom-recipe-settings">

			<h4><?php esc_html_e( 'Recipes Per Page : ', 'blossom-recipe-maker' ); ?></h4>

			<div class="recipe-note">
				<p><?php esc_html_e( 'Select the number of recipes to display in all Recipe Templates.', 'blossom-recipe-maker' ); ?></p>
			</div>

			<input type="number" id="br_recipe_settings[no_of_recipes]" name="br_recipe_settings[no_of_recipes]" min=0 value="<?php echo isset( $br_recipe_settings['no_of_recipes'] ) && $br_recipe_settings['no_of_recipes'] != '' ? esc_attr( $br_recipe_settings['no_of_recipes'] ) : 10; ?>">

		</div>
	</div>



</div>
