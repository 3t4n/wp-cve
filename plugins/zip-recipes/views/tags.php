<?php
$index = 0;
if ($recipe->keywords || $recipe->preview ) {
    ?>
    <h4 class="zrdn-tags-label zrdn-recipe-label"><?php _e( "Tags", "zip-recipes" ) ?></h4>
	<?php
	echo '<div class="zrdn-tags-container">';
	if ( is_array($recipe->keywords ) ) {
		foreach ( $recipe->keywords as $tag ) {
			// Check if the tag exists
			$existing_tag = term_exists( $tag->name, 'post_tag' );
			if ( $existing_tag ) {
				// Get the tag link using the tag ID
				$tag_link = get_tag_link( $existing_tag['term_id'] );
			} else {
				$tag_link = "#";
            }
			$index ++
			?>
			<div class="zrdn-tag-item">
				<strong><a href="<?php echo $tag_link ?>"><?php echo $tag->name ?></a></strong><?php
				if ( count( $recipe->keywords ) > $index ) {
					echo ',';
				}
				?>
			</div>
			<?php
		}
	} else {
		_e("No tags found", 'zip-recipes');
	}
}
if ($recipe->keywords) echo '</div>';

