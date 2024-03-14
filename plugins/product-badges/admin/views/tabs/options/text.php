<?php

if ( ! defined( 'LION_BADGES_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
?>

<div class="tab_options_group">
	<?php
		$horizontal_tabs = new Lion_Badge_Option_Tabs_Horizontal( 'options', 'text' );
		$horizontal_tabs->add_tab( 'text', __( 'Text', 'lionplugins' ) );
		$horizontal_tabs->add_tab( 'font', __( 'Font', 'lionplugins' ) );
		$horizontal_tabs->add_tab( 'padding', __( 'Padding', 'lionplugins' ) );
		$horizontal_tabs->generate();
	?>
</div>
