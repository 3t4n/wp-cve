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
 * @subpackage Blossom_Recipe/includes
 * @author     Blossom <test@test.com>
 */
class Blossom_Recipe_Maker_Print_Functions {


	public function __construct() {

		add_action( 'br_recipe_call_to_action', array( $this, 'br_recipe_call_to_action_buttons' ) );

		add_filter( 'template_redirect', array( $this, 'redirect_to_print_template' ) );

		add_filter( 'query_vars', array( $this, 'add_recipe_query_vars_filter' ) );

	}

	function add_recipe_query_vars_filter( $vars ) {

		$vars[] = 'print';
		return $vars;
	}

	function redirect_to_print_template() {

		$print = get_query_var( 'print', false );

		if ( $print !== false ) {
			if ( $theme_file = locate_template( array( 'template-recipe-print.php' ) ) ) {
				$template_path = $theme_file;
			} else {
				$template_path = BLOSSOM_RECIPE_MAKER_TEMPLATE_PATH . '/template-recipe-print.php';
			}

			load_template( $template_path );

			exit();
		}
	}

	function br_recipe_call_to_action_buttons( $post_id ) {
		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}
		$options        = get_option( 'br_recipe_settings', array() );
		$recipe         = get_post_meta( $post_id, 'br_recipe', true );
		$filteredrecipe = array_filter( $recipe['details'] );

		if ( isset( $recipe['details'] ) && ! empty( $filteredrecipe ) ) {
			?>
		<div>
			<span class="blossom-recipe-print">
					
			<?php
			$recipe_post_url = get_permalink( $post_id );

			?>
				<a target="_blank" rel="nofollow" href="<?php echo esc_url( add_query_arg( 'print', $post_id, $recipe_post_url ) ); ?>" class="br_recipe_print_button" data-br-recipe-id="<?php echo esc_attr( $post_id ); ?>">
			<?php
			echo ! empty( $options['print_btn_txt'] ) ? esc_html( $options['print_btn_txt'] ) : esc_html__( 'Print', 'blossom-recipe-maker' );
			?>
				</a>
				
			</span>
		</div>
			<?php
		}

	}

}
new Blossom_Recipe_Maker_Print_Functions();
