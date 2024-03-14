<?php
/**
 * Provides the 'Recipe Notes' view for the corresponding tab in the Post Meta Box.
 *
 * @link       test.com
 * @since      1.0.0
 *
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/admin/meta-data
 */
?>
 
<div id="blossom-recipe-tab-recipe-notes" class="inside hidden">

	<div class="br-recipe-notes">

		<h1><?php esc_html_e( 'Recipe Notes', 'blossom-recipe-maker' ); ?>
			<span class="recipe-excerpt-tooltip" title="<?php esc_html_e( 'Use this section to add recipe notes or anything you like.', 'blossom-recipe-maker' ); ?>">
					<i class="far fa-question-circle"></i>
			</span>
		</h1>

		<?php
		$notes   = get_post_meta( get_the_ID(), 'br_recipe', true );
		$content = '';

		if ( isset( $notes['notes'] ) ) {
				$content = html_entity_decode( $notes['notes'] );
				// print_r($content);
		}
		?>
	
	<?php
	$options = array(
		'textarea_rows' => 7,
		'textarea_name' => 'br_recipe[notes]',
	);

	wp_editor( $content, 'br_recipe_notes', $options );

	?>

	</div>
</div>
