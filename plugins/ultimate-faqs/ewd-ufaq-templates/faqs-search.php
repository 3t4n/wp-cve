<div id='ewd-ufaq-jquery-ajax-search'>

	<label class='ewd-ufaq-field-label' for='ewd-ufaq-text-search'>
		<?php echo esc_html( $this->get_label( 'label-enter-question' ) ); ?>:
	</label>

	<?php $this->maybe_print_shortcode_args(); ?>

	<div class="search-field">
		<input
			id='ewd-ufaq-text-search'
			type='search'
			class='ewd-ufaq-text-input <?php echo ( $this->get_option( 'auto-complete-titles' ) ? 'ewd-ufaq-text-auto-complete' : '' ); ?>'
			placeholder='<?php echo esc_attr( $this->get_label( 'label-search-placeholder' ) ); ?>'
			value='<?php echo ( isset( $_GET['faq_search_term'] ) ? esc_attr( $_GET['faq_search_term'] ) : '' ); ?>' />
		<button type="button" class="clear-field" data-state="hidden">x</button>
	</div>

	<?php $this->maybe_print_search_submit(); ?>

</div>