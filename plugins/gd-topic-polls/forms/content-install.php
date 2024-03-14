<?php

use function Dev4Press\v43\Functions\panel;

?>
<div class="d4p-content">
    <div class="d4p-setup-wrapper">
        <div class="d4p-update-info">
			<?php

			include( GDPOL_PATH . 'forms/setup/database.php' );
			include( GDPOL_PATH . 'forms/setup/rules.php' );

			gdpol_settings()->set( 'install', false, 'info' );
			gdpol_settings()->set( 'update', false, 'info', true );

			?>

            <div class="d4p-install-block">
                <h4>
					<?php esc_html_e( 'All Done', 'gd-topic-polls' ); ?>
                </h4>
                <div>
					<?php esc_html_e( 'Installation completed.', 'gd-topic-polls' ); ?>
                </div>
            </div>

            <div class="d4p-install-confirm">
                <a class="button-primary" href="<?php echo panel()->a()->panel_url( 'about' ) ?>&install"><?php esc_html_e( 'Click here to continue', 'gd-topic-polls' ); ?></a>
            </div>
        </div>
		<?php echo gdpol()->recommend( 'install' ); ?>
    </div>
</div>
