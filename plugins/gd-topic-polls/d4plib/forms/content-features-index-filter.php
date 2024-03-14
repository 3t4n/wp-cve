<?php

use function Dev4Press\v43\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( panel()->a()->plugin()->f()->network_mode() && ! is_network_admin() ) {
	$buttons = panel()->get_filter_buttons_for_override();
} else {
	$buttons = panel()->get_filter_buttons();
}

?>

<div class="d4p-features-filter">
    <div class="d4p-features-filter-buttons">
		<?php

		foreach ( $buttons as $code => $button ) {
			$class = ( $button['default'] ?? false ) ? 'is-selected' : '';
			echo '<button class="' . esc_attr( $class ) . '" data-selector="' . esc_attr( $button['selector'] ) . '" data-filter="' . esc_attr( $code ) . '" type="button">' . esc_html( $button['label'] ) . '</button>';
		}

		?>
    </div>
    <div class="d4p-features-filter-search">
        <input aria-label="<?php esc_html_e( 'Search features by keyword', 'd4plib' ); ?>" placeholder="<?php esc_html_e( 'Search...', 'd4plib' ); ?>" type="text"/><i class="d4p-icon d4p-ui-clear"></i>
    </div>
</div>
