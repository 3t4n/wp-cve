<?php

use function Dev4Press\v43\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d4p-wizard-panel-header">
    <p>
		<?php esc_html_e( 'This wizard has reach the end. But, there are a lot more features, a lot more settings available to explore. This was just the starting point, to configure few basic things, before you try exploring the plugin and testing the features you want to use.', 'd4plib' ); ?>
    </p>
</div>

<div class="d4p-wizard-panel-content">
    <div class="d4p-wizard-option-block d4p-wizard-block-yesno">
        <p><?php esc_html_e( 'Do you want to hide this Setup Wizard?', 'd4plib' ); ?></p>
        <div>
            <em><?php esc_html_e( 'Setup Wizard can be hidden, if you don\'t plan to use it for now. To enable or disable at any time the Wizard you can use option in the Advanced panel on the Settings page.', 'd4plib' ); ?></em>
			<?php panel()->a()->wizard()->render_yes_no( 'finish', 'wizard' ); ?>
        </div>
    </div>
</div>
