<?php
/**
 * Animated before text template
 */
?>
<span class="lakit-animated-text__before-text">
	<?php
		echo $this->str_to_spanned_html( $this->get_settings_for_display('before_text_content'), 'word' );
	?>
</span>