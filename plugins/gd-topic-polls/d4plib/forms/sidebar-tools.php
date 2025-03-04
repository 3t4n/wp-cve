<?php

use function Dev4Press\v43\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_panel     = panel()->a()->panel_object();
$_subpanel  = panel()->a()->subpanel;
$_subpanels = panel()->subpanels();

if ( $_subpanels[ $_subpanel ]['method'] == 'post' ) {
	panel()->settings_fields( '', true );
}

$_button_id = $_subpanels[ $_subpanel ]['button_id'] ?? panel()->a()->plugin_prefix . '-tools-' . $_subpanel;

?>
<div class="d4p-sidebar">
    <div class="d4p-panel-scroller d4p-scroll-active">
        <div class="d4p-panel-title">
            <div class="_icon">
				<?php echo panel()->r()->icon( $_panel->icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <h3><?php echo esc_html( $_panel->title ); ?></h3>
			<?php

			if ( $_subpanel != 'index' ) {
				echo '<h4>' . panel()->r()->icon( $_subpanels[ $_subpanel ]['icon'] ) . esc_html( $_subpanels[ $_subpanel ]['title'] ) . '</h4>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			?>
            <div class="_info">
				<?php echo esc_html( $_subpanels[ $_subpanel ]['description'] ?? $_subpanels[ $_subpanel ]['info'] ); ?>
            </div>
        </div>
		<?php if ( $_subpanel != 'index' && $_subpanels[ $_subpanel ]['method'] != '' ) { ?>
            <div class="d4p-panel-buttons">
				<?php if ( $_subpanels[ $_subpanel ]['method'] == 'get' ) { ?>
                    <a id="<?php echo esc_attr( $_button_id ); ?>" type="button" href="<?php echo esc_url( $_subpanels[ $_subpanel ]['button_url'] ); ?>" class="button-primary"><?php echo esc_html( $_subpanels[ $_subpanel ]['button_label'] ); ?></a>
				<?php } else if ( $_subpanels[ $_subpanel ]['method'] == 'ajax' ) { ?>
                    <button id="<?php echo esc_attr( $_button_id ); ?>" type="button" class="button-primary"><?php echo esc_html( $_subpanels[ $_subpanel ]['button_label'] ); ?></button>
				<?php } else { ?>
                    <button id="<?php echo esc_attr( $_button_id ); ?>" type="submit" class="button-primary"><?php echo esc_html( $_subpanels[ $_subpanel ]['button_label'] ); ?></button>
				<?php } ?>
            </div>
            <div class="d4p-return-to-top">
                <a href="#wpwrap"><?php esc_html_e( 'Return to top', 'd4plib' ); ?></a>
            </div>
		<?php } ?>
    </div>
</div>
