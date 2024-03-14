<?php
add_action(
	'scc_render_notices',
	function ( $d ) {
		$notifications = new SCC_Notifications( 'diag', null );
		$notifications->output();
	}
);
