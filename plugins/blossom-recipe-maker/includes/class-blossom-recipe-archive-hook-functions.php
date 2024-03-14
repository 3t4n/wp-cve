<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * The template loader of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/admin
 * @author     Blossom <test@test.com>
 */
class Blossom_Recipe_Maker_Archive_Hook_Functions {


	/**
	 * Hook in methods.
	 */
	public function __construct() {

		add_action( 'br_recipe_archive_action', array( $this, 'br_recipe_archive' ) );
	}

	function br_recipe_archive( $post_id ) {
		if ( empty( $post_id ) ) {
			global $post;
			$post_id = $post->ID;
		}

		$recipe = get_post_meta( $post_id, 'br_recipe', true );

		?>
		<div class ="recipe-archive-wrap" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
			<meta itemprop="position" content="<?php echo esc_attr( $post_id ); ?>" />
			<div class="col">        
		<?php
		$recipe_img_size = apply_filters( 'brm_archive_img_size', 'recipe-maker-thumbnail-size' );

		if ( has_post_thumbnail( $post_id ) ) {
			?>
				<div class="img-holder">

					<a href="<?php echo esc_url( get_the_permalink() ); ?>">

			<?php the_post_thumbnail( $recipe_img_size ); ?>

					</a>
				</div>
			<?php
		} else {
			?>
				<div class="svg-holder">
					<a href="<?php echo esc_url( get_the_permalink() ); ?>">
			<?php
			$obj = new Blossom_Recipe_Maker_Functions();
			$obj->brm_get_fallback_svg( $recipe_img_size );// falback
			?>
					</a>
				</div>
			<?php
		}

		?>

			<div class="text-holder">
								
				<h1 class="recipe-title">
				<a href="<?php the_permalink(); ?>">    
		<?php the_title(); ?>
				</a>
				</h1>

		<?php

		do_action( 'br_recipe_category_links_action' );
		?>
				
				<div class="recipe-description">
		<?php
		if ( has_excerpt() ) {
			the_excerpt();
		} else {
			$post    = get_post( $post_id );
			$content = apply_filters( 'get_the_excerpt', $post->content, $post );
			echo wp_kses_post( wp_trim_words( $content, 55, '...' ) );
		}
		?>
				</div>

				<div class="readmore-btn">

					<a itemprop="url" class="more-button" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View Recipe', 'blossom-recipe-maker' ); ?></a>

				</div>            
			</div>
			</div>

		</div>
		<?php

	}

}
new Blossom_Recipe_Maker_Archive_Hook_Functions();
