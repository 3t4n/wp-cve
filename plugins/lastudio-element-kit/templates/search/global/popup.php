<?php
/**
 * Popup template
 */
$settings = $this->get_settings();

$this->add_render_attribute( 'lakit-search-popup', 'class', 'lakit-search__popup' );

if ( isset( $settings['full_screen_popup'] ) && 'true' === $settings['full_screen_popup'] ) {
	$this->add_render_attribute( 'lakit-search-popup', 'class', 'lakit-search__popup--full-screen' );
}

if ( isset( $settings['popup_show_effect'] ) ) {
	$this->add_render_attribute( 'lakit-search-popup', 'class', sprintf( 'lakit-search__popup--%s-effect', $settings['popup_show_effect'] ) );
}
?>
<div <?php $this->print_render_attribute_string( 'lakit-search-popup' ); ?>>
	<div class="lakit-search__popup-content"><?php
		include $this->_get_global_template( 'form' );
		include $this->_get_global_template( 'popup-close' );
	?></div>
</div>
<?php include $this->_get_global_template( 'popup-trigger' ); ?>