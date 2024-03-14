<?php
/**
 * Fired during recipe search.
 *
 * This class defines all code necessary to run during the recipe search.
 *
 * @since      1.0.0
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/includes
 * @author     Blossom <test@test.com>
 */
class Blossom_Recipe_Maker_Search_Template {


	public function __construct() {

		add_action( 'show_recipe_search_form_action', array( $this, 'recipe_search_form_template' ) );

	}

	public function recipe_search_form_template() {

		$submitted_get_data = blossom_recipe_maker_get_submitted_data( 'get' );

		$options = get_option( 'br_recipe_settings', array() );
		$pageID  = $options['pages']['recipe_search'];
		$nonce   = wp_create_nonce( 'recipe-search-nonce' );

		?>
		<h4 class="br-search-title"><?php esc_html_e( 'Search for a Recipe', 'blossom-recipe-maker' ); ?></h4>

		<form method="get" action='<?php echo esc_url( get_permalink( $pageID ) ); ?>' id="blossom-recipe-search-form">
			<div class="recipe-search-form">
				<input type="hidden" name="recipe-search-nonce" value="<?php echo esc_attr( $nonce ); ?>">

				<div class='recipe-search-field recipe-keyword'>
					<h4><?php esc_html_e( 'Keyword', 'blossom-recipe-maker' ); ?></h4>
					<input class="search-keyword" placeholder="<?php esc_html_e( 'Search …', 'blossom-recipe-maker' ); ?>" value="<?php echo isset( $submitted_get_data['search'] ) ? esc_attr( $submitted_get_data['search'] ) : ''; ?>" name="search" id="search" type="text">
				</div>
		<?php
		$categories = get_categories( 'taxonomy=recipe-category' );
		$select     = "<div class='recipe-search-field recipe-category'><h4>"
		. __( 'Category', 'blossom-recipe-maker' ) .
		"</h4><div class='custom-select'><select name='recipe-category' id='recipe-category'>";

		$select .= "<option value='-1'>" . __( 'Category', 'blossom-recipe-maker' ) . '</option>';

		foreach ( $categories as $category ) {
			if ( $category->count > 0 ) {
				 $select .= "<option value='" . $category->slug . "' " . ( isset( $submitted_get_data['recipe-category'] ) && $category->slug == $submitted_get_data['recipe-category'] ? 'selected = "selected"' : '' ) . '>' . $category->name . '</option>';
			}
		}

		$select .= '</select></div></div>';

		if ( ! isset( $options['recipe_search']['category'] ) ) {
			echo wp_kses_post( $select );
		}

				$cuisines = get_categories( 'taxonomy=recipe-cuisine' );
				$select   = "<div class='recipe-search-field recipe-cuisine'><h4>"
				. __( 'Cuisine', 'blossom-recipe-maker' ) .
				"</h4><div class='custom-select'><select name='recipe-cuisine' id='recipe-cuisine'>";

				$select .= "<option value='-1'>" . __( 'Cuisine', 'blossom-recipe-maker' ) . '</option>';

		foreach ( $cuisines as $cuisine ) {
			if ( $cuisine->count > 0 ) {
				$select .= "<option value='" . $cuisine->slug . "' " . ( isset( $submitted_get_data['recipe-cuisine'] ) && $cuisine->slug == $submitted_get_data['recipe-cuisine'] ? 'selected="selected"' : '' ) . '>' . $cuisine->name . '</option>';
			}
		}

				$select .= '</select></div></div>';

		if ( ! isset( $options['recipe_search']['cuisine'] ) ) {
			echo wp_kses_post( $select );
		}

				$cooking_methods = get_categories( 'taxonomy=recipe-cooking-method' );
				$select          = "<div class='recipe-search-field recipe-cooking-method'><h4>"
				. __( 'Cooking Method', 'blossom-recipe-maker' ) .
				"</h4><div class='custom-select'><select name='recipe-cooking-method' id='recipe-cooking-method'>";

				$select .= "<option value='-1'>" . __( 'Cooking Method', 'blossom-recipe-maker' ) . '</option>';

		foreach ( $cooking_methods as $cooking_method ) {
			if ( $cooking_method->count > 0 ) {
				$select .= "<option value='" . $cooking_method->slug . "' " . ( isset( $submitted_get_data['recipe-cooking-method'] ) && $cooking_method->slug == $submitted_get_data['recipe-cooking-method'] ? 'selected="selected"' : '' ) . '>' . $cooking_method->name . '</option>';
			}
		}

				$select .= '</select></div></div>';

		if ( ! isset( $options['recipe_search']['cooking_method'] ) ) {
			echo wp_kses_post( $select );
		}

				$tags   = get_categories( 'taxonomy=recipe-tag' );
				$select = "<div class='recipe-search-field recipe-tag'><h4>"
				. __( 'Tags', 'blossom-recipe-maker' ) .
				"</h4><div class='custom-select'><select name='recipe-tag' id='recipe-tag'>";

				$select .= "<option value='-1'>" . __( 'Tags', 'blossom-recipe-maker' ) . '</option>';

		foreach ( $tags as $tag ) {
			if ( $tag->count > 0 ) {
				$select .= "<option value='" . $tag->slug . "' " . ( isset( $submitted_get_data['recipe-tag'] ) && ( $tag->slug == $submitted_get_data['recipe-tag'] ) ? 'selected="selected"' : '' ) . '>' . $tag->name . '</option>';
			}
		}

				$select .= '</select></div></div>';

		if ( ! isset( $options['recipe_search']['tag'] ) ) {
			echo wp_kses_post( $select );
		}

		?>
				<div class='recipe-search-field-submit'>
				   <input type="submit" name="recipe_search" value="<?php esc_attr_e( 'Search', 'blossom-recipe-maker' ); ?>">
				</div>

			</div>
		</form>
		<?php
	}

}
new Blossom_Recipe_Maker_Search_Template();
