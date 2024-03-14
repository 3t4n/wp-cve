<?php
/**
 * Provides the 'Shortcodes' view for the corresponding tab in the Settings Page.
 *
 * @link  test.com
 * @since 1.0.0
 *
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/includes/backend/settings
 */
?>

<div id="blossom-recipe-shortcode-settings-tab" class="inside hidden">

	<div class="blossom-recipe-fields-settings">

		<h3><?php esc_html_e( 'Recent Recipes Shortcode', 'blossom-recipe-maker' ); ?></h3>

		<div class="recipe-note">
			<p><?php esc_html_e( 'Use below shortcode to display Recent Recipes anywhere in your website.', 'blossom-recipe-maker' ); ?></p>
		</div>

		<input  id="recent-shortcode" type="text" name="recent-shortcode" value="[brm-recipes title='Recent Recipes' num_posts=3]" readonly onClick="this.setSelectionRange(0, this.value.length)">

	</div>

	<div class="blossom-recipe-fields-settings">

		<h3><?php esc_html_e( 'Popular Recipes Shortcode', 'blossom-recipe-maker' ); ?></h3>

		<div class="recipe-note">
			<p><?php esc_html_e( 'Use below shortcode to display Popular Recipes anywhere in your website.', 'blossom-recipe-maker' ); ?></p>
		</div>

		<h4><?php esc_html_e( 'Popular Recipes by Views', 'blossom-recipe-maker' ); ?></h4>

		<input  id="popular-view-shortcode" type="text" name="popular-view-shortcode" value="[brm-recipes popular='views' title='Popular Recipes' num_posts=3]" readonly onClick="this.setSelectionRange(0, this.value.length)">

		<h4><?php esc_html_e( 'Popular Recipes by Comments', 'blossom-recipe-maker' ); ?></h4>

		<input  id="popular-comments-shortcode" type="text" name="popular-comments-shortcode" value="[brm-recipes popular='comments' title='Popular Recipes' num_posts=3]" readonly onClick="this.setSelectionRange(0, this.value.length)">
	</div>

	<div class="recipe-note">
		<p><?php esc_html_e( 'replace the title and num_posts values as per your need.', 'blossom-recipe-maker' ); ?></p>
	</div>
	
</div>
