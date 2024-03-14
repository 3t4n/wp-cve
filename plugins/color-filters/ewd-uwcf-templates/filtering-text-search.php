<div class='ewd-uwcf-text-search'>
	<input type='text' class='ewd-uwcf-text-search-input <?php echo ( $this->get_option( 'text-search-autocomplete' ) ? 'ewd-uwcf-text-search-autocomplete' : '' ); ?>' name='ewd-uwcf-text-search-input' placeholder='<?php _e( 'Search Products...', 'color-filters' ); ?>' value='<?php echo ( ! empty( $_GET['s'] ) ? esc_attr( sanitize_text_field( $_GET['s'] ) ) : '' ); ?>' />
	<?php if ( ! $this->get_option( 'text-search-autocomplete' ) ) { ?> <div class='ewd-uwcf-text-search-submit'><?php _e( 'Search', 'color-filters' ); ?></div> <?php } ?>
</div>