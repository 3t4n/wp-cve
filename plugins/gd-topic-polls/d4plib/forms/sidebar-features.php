<?php

use function Dev4Press\v43\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_panel     = panel()->a()->panel_object();
$_subpanel  = panel()->a()->subpanel;
$_subpanels = panel()->subpanels();
$_features  = panel()->a()->plugin()->f();
$_beta      = $_features->is_beta( $_subpanel );

$_url_base  = panel()->a()->current_url();
$_url_reset = add_query_arg(
	array(
		'single-action'   => 'reset',
		panel()->a()->v() => 'getback',
		'_wpnonce'        => wp_create_nonce( panel()->a()->plugin_prefix . '-feature-reset-' . $_subpanel ),
	),
	$_url_base
);
$_url_copy  = '';

if ( panel()->a()->plugin()->f()->network_mode() && ! is_network_admin() ) {
	$_url_copy = add_query_arg(
		array(
			'single-action'   => 'network-copy',
			panel()->a()->v() => 'getback',
			'_wpnonce'        => wp_create_nonce( panel()->a()->plugin_prefix . '-feature-network-copy-' . $_subpanel ),
		),
		$_url_base
	);
}

?>
<div class="d4p-sidebar">
    <div class="d4p-panel-scroller d4p-scroll-active">
        <div class="d4p-panel-title">
            <div class="_icon">
				<?php echo panel()->r()->icon( $_panel->icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <h3><?php echo esc_html( $_panel->title ); ?></h3>
			<?php

			echo '<h4>' . panel()->r()->icon( $_subpanels[ $_subpanel ]['icon'] ) . $_subpanels[ $_subpanel ]['title'] . '</h4>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			if ( $_beta ) {
				echo '<div class="_beta"><i class="d4p-icon d4p-ui-flask"></i> <span>' . esc_html__( 'Beta Feature', 'd4plib' ) . '</span></div>';
			}

			?>
            <div class="_info">
				<?php echo esc_html( $_subpanels[ $_subpanel ]['info'] ); ?>
            </div>
        </div>

        <div class="d4p-panel-control">
            <button type="button" class="button-secondary d4p-feature-more-ctrl"><?php esc_html_e( 'More Controls', 'd4plib' ); ?></button>
            <div class="d4p-feature-more-ctrl-options" style="display: none">
                <p><?php esc_html_e( 'If you want, you can reset all the settings for this Feature to default values.', 'd4plib' ); ?></p>
                <a class="button-primary" href="<?php echo esc_url( $_url_reset ); ?>"><?php esc_html_e( 'Reset Feature Settings', 'd4plib' ); ?></a>

				<?php

				if ( ! empty( $_url_copy ) ) {
					?>

                    <hr/>
                    <p><?php esc_html_e( 'You can also copy the settings from the main Network settings for this feature.', 'd4plib' ); ?></p>
                    <a class="button-primary" href="<?php echo esc_url( $_url_copy ); ?>"><?php esc_html_e( 'Copy Network Settings', 'd4plib' ); ?></a>

					<?php
				}

				?>
            </div>
            <div class="d4p-panel-buttons">
                <input type="submit" value="<?php esc_attr_e( 'Save Settings', 'd4plib' ); ?>" class="button-primary"/>
            </div>
        </div>
        <div class="d4p-return-to-top">
            <a href="#wpwrap"><?php esc_html_e( 'Return to top', 'd4plib' ); ?></a>
        </div>
    </div>
</div>
