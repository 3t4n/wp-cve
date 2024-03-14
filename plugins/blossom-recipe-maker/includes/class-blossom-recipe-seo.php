<?php
/**
 * Blossom Recipe Maker SEO Functions
 *
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/includes
 * @since      1.0.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Blossom_Recipe_Maker_SEO {


	public function __construct() {

		add_action( 'blossom_recipe_maker_json_ld_action', array( $this, 'blossom_recipe_maker_json_ld' ) );
	}

	public static function blossom_recipe_maker_json_ld( $post_id = false ) {

		$schema_values = self::blossom_recipe_maker_schema_values( $post_id );

		?>
		<script type="application/ld+json">
		<?php echo wp_json_encode( blossom_recipe_maker_sanitize_array( $schema_values ) ); // @phcs:ignore ?>
		</script>
		<?php
	}

	public static function blossom_recipe_maker_schema_values( $post_id = false ) {

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}
		$recipe  = get_post_meta( $post_id, 'br_recipe', true );
		$gallery = get_post_meta( $post_id, 'br_recipe_gallery', true );

		$recipe_thumbnail = ( has_post_thumbnail( $post_id ) ? get_the_post_thumbnail_url( $post_id, 'recipe-maker-single-size' ) : '' );

		if ( ! $recipe_author = get_the_author_meta( 'display_name' ) ) :
			$recipe_author = '';
		endif;

		$ingredients = array();
		$directions  = array();

		if ( isset( $recipe['ingredient'] ) && ! empty( $recipe['ingredient'] ) ) :
			foreach ( $recipe['ingredient'] as $ing ) :
				if ( isset( $ing['ingredient'] ) && ! empty( $ing['ingredient'] ) ) :
					$ingredient         = $ing['ingredient'];
					$ingredient_cleaned = strip_tags( preg_replace( '~(?:\[/?)[^/\]]+/?\]~s', '', $ingredient ) );
					$ingredients[]      = $ingredient_cleaned;
				endif;
			endforeach;
		endif;

		if ( isset( $recipe['instructions'] ) && ! empty( $recipe['instructions'] ) ) :
			foreach ( $recipe['instructions'] as $dir ) :
				if ( isset( $dir['description'] ) && ! empty( $dir['description'] ) ) :
					$direction         = $dir['description'];
					$direction_cleaned = strip_tags( preg_replace( '~(?:\[/?)[^/\]]+/?\]~s', '', $direction ) );
					$directions[]      = array(
						'@type' => 'HowToStep',
						'text'  => $direction_cleaned,
					);
				endif;
			endforeach;
		endif;

		$categories = '';

		if ( has_term( '', 'recipe-category', $post_id ) ) :
			$categories = get_the_terms( $post_id, 'recipe-category' );
			if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
				$categories = wp_list_pluck( $categories, 'name' );
			endif;
		endif;

		$cuisines = '';

		if ( has_term( '', 'recipe-cuisine', $post_id ) ) :
			$cuisines = get_the_terms( $post_id, 'recipe-cuisine' );
			if ( ! empty( $cuisines ) && ! is_wp_error( $cuisines ) ) :
				$cuisines = wp_list_pluck( $cuisines, 'name' );
			endif;
		endif;

		$methods = '';

		if ( has_term( '', 'recipe-cooking-method', $post_id ) ) :
			$methods = get_the_terms( $post_id, 'recipe-cooking-method' );
			if ( ! empty( $methods ) && ! is_wp_error( $methods ) ) :
				$methods = wp_list_pluck( $methods, 'name' );
			endif;
		endif;

		$keywords = '';

		if ( has_term( '', 'recipe-tag', $post_id ) ) :
			$tags = get_the_terms( $post_id, 'recipe-tag' );
			if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) :
				$keyword_names = wp_list_pluck( $tags, 'name' );
				$keywords      = implode( ', ', $keyword_names );
			endif;
		endif;

		$cook_time  = ( isset( $recipe['details']['cook_time'] ) && $recipe['details']['cook_time'] ? esc_html( $recipe['details']['cook_time'] ) : 0 );
		$prep_time  = ( isset( $recipe['details']['prep_time'] ) && $recipe['details']['prep_time'] ? esc_html( $recipe['details']['prep_time'] ) : 0 );
		$total_time = $cook_time + $prep_time;
		$obj        = new Blossom_Recipe_Maker_Functions();
		$cook_time  = $obj->time_format( $cook_time, 'iso' );
		$prep_time  = $obj->time_format( $prep_time, 'iso' );
		$total_time = $obj->time_format( $total_time, 'iso' );

		$rating = ( isset( $recipe['ratings'] ) && $recipe['ratings'] ) ? $recipe['ratings'] : 0;
		if ( $rating != 0 ) {
			$aggregateRating = array(
				'@type'       => 'AggregateRating',
				'ratingValue' => $rating,
				'ratingCount' => 1,
			);
		} else {
			$aggregateRating = 0;
		}

		$schema_array = false;

		$schema_array = apply_filters(
			'blossom_recipe_maker_schema_array',
			array(
				'@context'           => 'http://schema.org',
				'@type'              => 'Recipe',
				'name'               => get_the_title( $post_id ),
				'image'              => $recipe_thumbnail,
				'author'             => array(
					'@type' => 'Person',
					'name'  => $recipe_author,
				),
				'datePublished'      => get_the_date( 'Y-m-d', $post_id ),
				'description'        => get_the_title( $post_id ),
				'prepTime'           => $prep_time,
				'cookTime'           => $cook_time,
				'totalTime'          => $total_time,
				'recipeYield'        => ( isset( $recipe['details']['servings'] ) && $recipe['details']['servings'] ? $recipe['details']['servings'] : '' ),
				'recipeCategory'     => $categories,
				'recipeCuisine'      => $cuisines,
				'cookingMethod'      => $methods,
				'keywords'           => $keywords,
				'recipeIngredient'   => $ingredients,
				'recipeInstructions' => $directions,
				'aggregateRating'    => $aggregateRating,
			),
			$post_id,
			$recipe
		);

		return $schema_array;

	}

}
new Blossom_Recipe_Maker_SEO();
