<?php
/**
 * Provides the 'Search' view for the corresponding tab in the Settings Page.
 *
 * @link  test.com
 * @since 1.0.0
 *
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/includes/backend/settings
 */
?>

<div id="blossom-recipe-search-settings-tab" class="inside hidden">

	<?php
	$options   = get_option( 'br_recipe_settings', true );
	$shortcode = 'BLOSSOM_RECIPE_MAKER_SEARCH_RESULTS';

	if ( isset( $options['pages']['recipe_search'] ) && $options['pages']['recipe_search'] != '' ) {
		$pageID = $options['pages']['recipe_search'];
		$page   = get_post( $pageID );

		if ( $page ) {
			$title        = $page->post_title;
			$existingPage = get_page_by_title( $title );

			if ( ! empty( $existingPage ) && 'page' === $existingPage->post_type && ( $existingPage->post_status == 'publish' ) ) {
				$postID  = $existingPage->ID;
				$content = $existingPage->post_content;

				if ( ! has_shortcode( $content, $shortcode ) ) {
					wp_update_post(
						array(
							'ID'           => $postID,
							'post_content' => '[' . $shortcode . ']',
						)
					);
				}
			}
		}
	}

	?>

	<div class="blossom-recipe-fields-settings">

		<h3><?php esc_html_e( 'Blossom Recipe Search Shortcode', 'blossom-recipe-maker' ); ?></h3>

		<div class="recipe-note">
			<p><?php esc_html_e( 'Use below shortcode to display Recipe Search Bar.', 'blossom-recipe-maker' ); ?></p>
		</div>

		<input  id="search-shortcode" type="text" name="search-shortcode" value="[Blossom_Recipe_Maker_Search]" readonly onClick="this.setSelectionRange(0, this.value.length)">
	</div>

	<div class="blossom-recipe-fields-settings">

		<h3><?php esc_html_e( 'Recipe Search Results Page ', 'blossom-recipe-maker' ); ?><span class="required">*</span></h3>

		<div class="recipe-note">
			<p><?php esc_html_e( 'This is the recipe search result page where [BLOSSOM_RECIPE_MAKER_SEARCH_RESULTS] shortcode is required.', 'blossom-recipe-maker' ); ?></p>
		</div>

		<?php
		$blossom_recipe_search_results = isset( $options['pages']['recipe_search'] ) ? esc_attr( $options['pages']['recipe_search'] ) : '';
		wp_dropdown_pages(
			array(
				'name'              => 'br_recipe_settings[pages][recipe_search]',
				'echo'              => 1,
				'show_option_none'  => esc_html__( '&mdash; Select &mdash;', 'blossom-recipe-maker' ),
				'option_none_value' => '0',
				'selected'          => esc_attr( $blossom_recipe_search_results ),
			)
		);
		?>

	</div>

	<div class="blossom-recipe-fields-settings">
			<h3><?php esc_html_e( 'Blossom Recipe Search Fields', 'blossom-recipe-maker' ); ?></h3>
				<span class="recipe-settings-tooltip" title="<?php esc_html_e( 'Check the above checkboxes to hide the fields from the recipe search form.', 'blossom-recipe-maker' ); ?>">
				<i class="far fa-question-circle"></i>
			</span>
			

			<div class="blossom-recipe-settings has_checkbox">
				<h4><?php esc_html_e( 'Hide Category : ', 'blossom-recipe-maker' ); ?></h4>

				<input type="checkbox" id="br_recipe_settings[recipe_search][category]" name="br_recipe_settings[recipe_search][category]" value="1" 
				<?php
				if ( isset( $options['recipe_search']['category'] ) && $options['recipe_search']['category'] != '' ) {
					echo esc_attr( 'checked' );
				}
				?>
				>
				<label for="br_recipe_settings[recipe_search][category]" class="checkbox-label"></label>
			</div>

			<div class="blossom-recipe-settings has_checkbox">
				<h4><?php esc_html_e( 'Hide Cuisine : ', 'blossom-recipe-maker' ); ?></h4>
				<input type="checkbox" id="br_recipe_settings[recipe_search][cuisine]" name="br_recipe_settings[recipe_search][cuisine]" value="1" 
				<?php
				if ( isset( $options['recipe_search']['cuisine'] ) && $options['recipe_search']['cuisine'] != '' ) {
					echo esc_attr( 'checked' );
				}
				?>
				>
				<label for="br_recipe_settings[recipe_search][cuisine]" class="checkbox-label"></label>
			</div>

			<div class="blossom-recipe-settings has_checkbox">
				<h4><?php esc_html_e( 'Hide Cooking Method : ', 'blossom-recipe-maker' ); ?></h4>
				<input type="checkbox" id="br_recipe_settings[recipe_search][cooking_method]" name="br_recipe_settings[recipe_search][cooking_method]" value="1" 
				<?php
				if ( isset( $options['recipe_search']['cooking_method'] ) && $options['recipe_search']['cooking_method'] != '' ) {
					echo esc_attr( 'checked' );
				}
				?>
				>
				<label for="br_recipe_settings[recipe_search][cooking_method]" class="checkbox-label"></label>
			</div>
			
			<div class="blossom-recipe-settings has_checkbox">
				<h4><?php esc_html_e( 'Hide Tags : ', 'blossom-recipe-maker' ); ?></h4>
				<input type="checkbox" id="br_recipe_settings[recipe_search][tag]" name="br_recipe_settings[recipe_search][tag]" value="1" 
				<?php
				if ( isset( $options['recipe_search']['tag'] ) && $options['recipe_search']['tag'] != '' ) {
					echo esc_attr( 'checked' );
				}
				?>
				>
				<label for="br_recipe_settings[recipe_search][tag]" class="checkbox-label"></label>
			</div>

	</div>


</div>
