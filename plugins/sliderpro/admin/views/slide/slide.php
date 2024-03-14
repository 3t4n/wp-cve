<div class="slide<?php echo ( $slide_type === 'posts' || $slide_type === 'gallery' || $slide_type === 'flickr' ) ? ' dynamic-slide' : '' ; ?>">
	<span class="spinner slide-spinner"></span>
	
	<div class="slide-preview">
		<?php 
			if ( $slide_type === 'custom' ) {
				if ( $slide_image !== '' ) {
					echo '<img src="' . esc_url( $slide_image ) . '" />';
				} else {
					echo '<p class="no-image">' . __( 'Click to add image', 'sliderpro' ) . '</p>';
				}
			} else if ( $slide_type === 'posts' ) {
				echo '<p>[ ' . __( 'Posts Slides', 'sliderpro' ) . ' ]</p>';
			} else if ( $slide_type === 'gallery' ) {
				echo '<p>[ ' . __( 'Gallery Slides', 'sliderpro' ) . ' ]</p>';
			} else if ( $slide_type === 'flickr' ) {
				echo '<p>[ ' . __( 'Flickr Slides', 'sliderpro' ) . ' ]</p>';
			}
		?>
	</div>

	<div class="slide-controls">
		<a class="delete-slide" href="#" title="Delete Slide">Delete</a>
		<a class="duplicate-slide" href="#" title="Duplicate Slide">Duplicate</a>
	</div>

	<div class="slide-buttons"> 
		<a class="edit-main-image" href="#" title="Edit Main Image">Image</a>
		<a class="edit-thumbnail" href="#" title="Edit Thumbnail">Thumbnail</a>
		<a class="edit-layers" href="#" title="Edit Layers">Layers</a>
		<a class="edit-caption" href="#" title="Edit Caption">Caption</a>
		<a class="edit-html" href="#" title="Edit HTML">HTML</a>
		<a class="edit-settings" href="#" title="Edit Settings">Settings</a>
	</div>
</div>
