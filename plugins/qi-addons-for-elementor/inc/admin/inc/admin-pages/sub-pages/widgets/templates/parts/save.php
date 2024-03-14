<div class="qodef-save-widgets-holder">
	<div class="qodef-save-success">
		<p class="qodef-field-description">
			<?php esc_html_e( 'Successfully saved!', 'qi-addons-for-elementor' ); ?>
		</p>
	</div>
	<div class="qodef-save-reset-loading">
		<svg class="qodef-save-reset-loading-spinner" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg>
	</div>
	<button class="qodef-btn qodef-btn-outlined"><?php esc_html_e( 'Save', 'qi-addons-for-elementor' ); ?></button>
	<?php wp_nonce_field( 'qi_addons_for_elementor_widgets_ajax_save_nonce', 'qi_addons_for_elementor_widgets_ajax_save_nonce' ); ?>
</div>
