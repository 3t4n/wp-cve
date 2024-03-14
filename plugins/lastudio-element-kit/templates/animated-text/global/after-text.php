<?php
/**
 * Animated after text template
 */
?>
<span class="lakit-animated-text__after-text">
	<?php
		echo $this->str_to_spanned_html( $this->get_settings_for_display('after_text_content'), 'word' );
	?>
</span>
