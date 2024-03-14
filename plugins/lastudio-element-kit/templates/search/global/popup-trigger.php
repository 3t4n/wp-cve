<?php
/**
 * Popup trigger
 */
?>
<div class="lakit-search__popup-trigger-container">
	<button type="button" class="lakit-search__popup-trigger main-color" data-title="<?php echo esc_attr( $this->get_settings_for_display('search_trigger_label') ); ?>" aria-label="<?php echo esc_attr( $this->get_settings_for_display('search_placeholder') ); ?>"><?php
		$this->_icon( 'search_popup_trigger_icon', '<span class="lakit-search__popup-trigger-icon lakit-blocks-icon">%s</span>' )
	?></button>
</div>