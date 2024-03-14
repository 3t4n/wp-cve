<?php
/**
 * Create Widget form.
*/
?>

<div class="virp-column">

	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">
			<?php _e( 'Title', 'virp' ); ?>
		</label>
		<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'title_url' ); ?>">
			<?php _e( 'Title URL', 'virp' ); ?>
		</label>
		<input type="text" id="<?php echo $this->get_field_id( 'title_url' ); ?>" name="<?php echo $this->get_field_name( 'title_url' ); ?>" value="<?php echo esc_url( $instance['title_url'] ); ?>" placeholder="<?php echo esc_attr( 'http://' ); ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'limit' ); ?>">
			<?php _e( 'Number of posts to show', 'virp' ); ?>
		</label>
		<input id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="number" step="1" min="-1" value="<?php echo (int) $instance['limit']; ?>" /><br/>
		<small>-1 <?php _e( 'to show all posts.', 'virp' ); ?></small>
	</p>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'post_type' ); ?>">
			<?php _e( 'Post type', 'virp' ); ?>
		</label>
		<select id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>">
			<?php foreach ( get_post_types( array( 'public' => true ), 'objects' ) as $post_type ) { ?>
				<option value="<?php echo esc_attr( $post_type->name ); ?>" <?php selected( $instance['post_type'], $post_type->name ); ?>><?php echo esc_html( $post_type->labels->singular_name ); ?></option>
			<?php } ?>
		</select>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'orderby' ); ?>">
			<?php _e( 'Order By', 'virp' ); ?>
		</label>
		<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
			<option value="rand" <?php selected( $instance['orderby'], 'rand' ); ?>><?php _e( 'Random', 'virp' ) ?></option>
			<option value="title" <?php selected( $instance['orderby'], 'title' ); ?>><?php _e( 'Title', 'virp' ) ?></option>
			<option value="comment_count" <?php selected( $instance['orderby'], 'comment_count' ); ?>><?php _e( 'Comment Count', 'virp' ) ?></option>
			<option value="date" <?php selected( $instance['orderby'], 'date' ); ?>><?php _e( 'Date', 'virp' ) ?></option>
		</select>
	</p>

	<div class="virp_block">
		<label>
			<?php _e( 'Limit to Category', 'virp' ); ?>
		</label>
		<ul>
			<?php foreach ( get_terms( 'category' ) as $category ) : ?>
				<li>
					<input type="checkbox" value="<?php echo (int) $category->term_id; ?>" id="<?php echo $this->get_field_id( 'cat' ) . '-' . (int) $category->term_id; ?>" name="<?php echo $this->get_field_name( 'cat' ); ?>[]" <?php checked( is_array( $instance['cat'] ) && in_array( $category->term_id, $instance['cat'] ) ); ?> />
					<label for="<?php echo $this->get_field_id( 'cat' ) . '-' . (int) $category->term_id; ?>">
						<?php echo esc_html( $category->name ); ?>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>
		</div>

	<?php // Check if the theme support Post Thumbnail feature. ?>
	<?php if ( current_theme_supports( 'post-thumbnails' ) ) : ?>
		<div class="virp_group">
		<p>
			<input type="checkbox" <?php checked( $instance['thumbnail'] ); ?> id="<?php echo $this->get_field_id( 'thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'thumbnail' ); ?>">
				<?php _e( 'Display thumbnail', 'virp' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'thumbnail_size' ); ?>">
				<?php _e( 'Thumbnail Size ', 'virp' ); ?>
			</label>
			<select id="<?php echo $this->get_field_id( 'thumbnail_size' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_size' ); ?>">
				<?php foreach ( get_intermediate_image_sizes() as $size ) { ?>
					<option value="<?php echo esc_attr( $size ); ?>" <?php selected( $instance['thumbnail_size'], $size ); ?>><?php echo esc_html( $size ); ?></option>
				<?php }	?>
			</select>
		</p>

		<p>
			<input type="checkbox" <?php checked( $instance['thumbnail_custom'] ); ?> id="<?php echo $this->get_field_id( 'thumbnail_custom' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_custom' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'thumbnail_custom' ); ?>">
				<?php _e( 'Use custom thumbnail sizes', 'virp' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'thumbnail_width' ); ?>">
				<?php _e( 'Width(px)', 'virp' ); ?>
			</label>
			<input id="<?php echo $this->get_field_id( 'thumbnail_width' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_width' ); ?>" type="text" value="<?php echo (int)( $instance['thumbnail_width'] ); ?>" />
		</p>
		<p>	
			<label for="<?php echo $this->get_field_id( 'thumbnail_width' ); ?>">
				<?php _e( 'Height(px)', 'virp' ); ?>
			</label>
			<input id="<?php echo $this->get_field_id( 'thumbnail_height' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_height' ); ?>" type="text" value="<?php echo (int)( $instance['thumbnail_height'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'thumbnail_align' ); ?>">
				<?php _e( 'Thumbnail Alignment', 'virp' ); ?>
			</label>
			<select id="<?php echo $this->get_field_id( 'thumbnail_align' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_align' ); ?>">
				<option value="left" <?php selected( $instance['thumbnail_align'], 'left' ); ?>><?php _e( 'Left', 'virp' ) ?></option>
				<option value="right" <?php selected( $instance['thumbnail_align'], 'right' ); ?>><?php _e( 'Right', 'virp' ) ?></option>
				<option value="center" <?php selected( $instance['thumbnail_align'], 'center' ); ?>><?php _e( 'Center', 'virp' ) ?></option>
			</select>
		</p>
		</div>
	<?php endif; ?>
	<div class="virp_group">
	<p>
		<input type="checkbox" <?php checked( $instance['excerpt'] ); ?> id="<?php echo $this->get_field_id( 'excerpt' ); ?>" name="<?php echo $this->get_field_name( 'excerpt' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'excerpt' ); ?>">
			<?php _e( 'Display excerpt', 'virp' ); ?>
		</label>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'excerpt_length' ); ?>">
			<?php _e( 'Excerpt Length', 'virp' ); ?>
		</label>
		<input id="<?php echo $this->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $this->get_field_name( 'excerpt_length' ); ?>" type="text" value="<?php echo (int)( $instance['excerpt_length'] ); ?>" />
	</p>
	<p>
		<input id="<?php echo $this->get_field_id( 'readmore' ); ?>" name="<?php echo $this->get_field_name( 'readmore' ); ?>" type="checkbox" <?php checked( $instance['readmore'] ); ?> />
		<label for="<?php echo $this->get_field_id( 'readmore' ); ?>">
			<?php _e( 'Display Readmore', 'virp' ); ?>
		</label>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'readmore_text' ); ?>">
			<?php _e( 'Readmore Text', 'virp' ); ?>
		</label>
		<input id="<?php echo $this->get_field_id( 'readmore_text' ); ?>" name="<?php echo $this->get_field_name( 'readmore_text' ); ?>" type="text" value="<?php echo strip_tags( $instance['readmore_text'] ); ?>" />
	</p>
	<p>
		<input id="<?php echo $this->get_field_id( 'comment_count' ); ?>" name="<?php echo $this->get_field_name( 'comment_count' ); ?>" type="checkbox" <?php checked( $instance['comment_count'] ); ?> />
		<label for="<?php echo $this->get_field_id( 'comment_count' ); ?>">
			<?php _e( 'Display Comment Count', 'virp' ); ?>
		</label>
	</p>
	<p>
		<input id="<?php echo $this->get_field_id( 'author_name' ); ?>" name="<?php echo $this->get_field_name( 'author_name' ); ?>" type="checkbox" <?php checked( $instance['author_name'] ); ?> />
		<label for="<?php echo $this->get_field_id( 'author_name' ); ?>">
			<?php _e( 'Display Author Name', 'virp' ); ?>
		</label>
	</p>
	<p>
		<input id="<?php echo $this->get_field_id( 'show_category' ); ?>" name="<?php echo $this->get_field_name( 'show_category' ); ?>" type="checkbox" <?php checked( $instance['show_category'] ); ?> />
		<label for="<?php echo $this->get_field_id( 'show_category' ); ?>">
			<?php _e( 'Display Category', 'virp' ); ?>
		</label>
	</p>
	<p>
		<input id="<?php echo $this->get_field_id( 'show_tags' ); ?>" name="<?php echo $this->get_field_name( 'show_tags' ); ?>" type="checkbox" <?php checked( $instance['show_tags'] ); ?> />
		<label for="<?php echo $this->get_field_id( 'show_tags' ); ?>">
			<?php _e( 'Display Tags', 'virp' ); ?>
		</label>
	</p>
	</div>
	<div class="virp_group">
		<p>
		<input type="checkbox" <?php checked( $instance['date'] ); ?> id="<?php echo $this->get_field_id( 'date' ); ?>" name="<?php echo $this->get_field_name( 'date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'date' ); ?>">
			<?php _e( 'Display Date', 'virp' ); ?>
		</label>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'format' ); ?>">
				<?php _e( 'Date Format', 'virp' ); ?>
		</label>
		<select id="<?php echo $this->get_field_id( 'format' ); ?>" name="<?php echo $this->get_field_name( 'format' ); ?>">
			<option value="F j, Y" <?php selected( $instance['format'], 'F j, Y' ); ?>><?php _e( 'F j, Y', 'virp' ) ?></option>
			<option value="F, Y" <?php selected( $instance['format'], 'F, Y' ); ?>><?php _e( 'F, Y ', 'virp' ) ?></option>
			<option value="F jS, Y" <?php selected( $instance['format'], 'F jS, Y' ); ?>><?php _e( 'F jS, Y', 'virp' ) ?></option>
			<option value="Y/m/d" <?php selected( $instance['format'], 'Y/m/d' ); ?>><?php _e( 'Y/m/d', 'virp' ) ?></option>
		</select><br/>
		<span>Read more about Date Formats <a href="http://codex.wordpress.org/Formatting_Date_and_Time">Here</a></span>
	</p>
	</div>

	<p>
		<label for="<?php echo $this->get_field_id( 'before' ); ?>">
			<?php _e( 'HTML/Text before the random posts', 'virp' );?>
		</label>
		<textarea id="<?php echo $this->get_field_id( 'before' ); ?>" name="<?php echo $this->get_field_name( 'before' ); ?>" rows="5"><?php echo htmlspecialchars( stripslashes( $instance['before'] ) ); ?></textarea>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'after' ); ?>">
			<?php _e( 'HTML/Text after the random posts', 'virp' );?>
		</label>
		<textarea id="<?php echo $this->get_field_id( 'after' ); ?>" name="<?php echo $this->get_field_name( 'after' ); ?>" rows="5"><?php echo htmlspecialchars( stripslashes( $instance['after'] ) ); ?></textarea>
	</p>

</div>

<div class="clear"></div>