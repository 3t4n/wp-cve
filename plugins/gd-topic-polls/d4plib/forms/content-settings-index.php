<?php

use function Dev4Press\v43\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d4p-content">
    <div class="d4p-features-wrapper">
		<?php

		panel()->include_generic( 'element', 'subpanels', 'blocks', array( 'class' => '_is-settings' ) );

		?>
    </div>
</div>
