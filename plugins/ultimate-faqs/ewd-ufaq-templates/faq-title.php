<div class='ewd-ufaq-faq-title <?php echo ( ! $this->get_option( 'disable-faq-toggle' ) ? 'ewd-ufaq-faq-toggle' : '' ) ?>'>
	
	<a class='ewd-ufaq-post-margin'  href='<?php echo esc_attr( $this->get_anchor_permalink() ); ?>'>

		<div class='ewd-ufaq-post-margin-symbol <?php echo esc_attr( $this->get_color_block_shape() ); ?>'>
			<span ><?php echo esc_attr( $this->get_toggle_symbol() ); ?></span>
		</div>

		<div class='ewd-ufaq-faq-title-text'>

			<<?php echo esc_attr( $this->get_option( 'styling-faq-heading-type' ) ); ?>>
				<?php echo wp_kses_post( $this->faq_title ); ?>
			</<?php echo esc_attr( $this->get_option( 'styling-faq-heading-type' ) ); ?>>

		</div>

		<div class='ewd-ufaq-clear'></div>

	</a>
	
</div>