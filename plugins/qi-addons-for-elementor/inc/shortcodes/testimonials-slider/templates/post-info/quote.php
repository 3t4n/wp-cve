<?php

if ( empty( $item_quote_icon['value'] ) ) {
	?>
	<div class="qodef-e-quote">
		<?php qi_addons_for_elementor_render_svg_icon( 'quote', 'qodef-e-quote-icon' ); ?>
	</div>
	<?php
} else {
	?>
	<div class="qodef-e-quote">
		<?php \Elementor\Icons_Manager::render_icon( $item_quote_icon, array( 'aria-hidden' => 'true' ) ); ?>
	</div>
	<?php
}
