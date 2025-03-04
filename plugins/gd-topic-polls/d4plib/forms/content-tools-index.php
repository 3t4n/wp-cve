<?php

use Dev4Press\v43\Core\Quick\Sanitize;
use function Dev4Press\v43\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d4p-content">
    <div class="d4p-features-wrapper">
		<?php

		foreach ( panel()->subpanels() as $subpanel => $obj ) {
			if ( $subpanel == 'index' ) {
				continue;
			}

			$_classes = array(
				'd4p-feature-box',
				'tool-' . $subpanel,
				'_is-tool',
			);

			$url = panel()->a()->panel_url( 'tools', $subpanel );

			if ( isset( $obj['break'] ) ) {
				echo panel()->r()->settings_break( $obj['break'], $obj['break-icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			?>

            <div class="<?php echo Sanitize::html_classes( $_classes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                <div class="_info">
                    <div class="_icon"><i class="d4p-icon d4p-<?php echo esc_attr( $obj['icon'] ); ?>"></i></div>
                    <h4 class="_title"><?php echo esc_html( $obj['title'] ); ?></h4>
                    <p class="_description"><?php echo esc_html( $obj['info'] ); ?></p>
                </div>
                <div class="_ctrl">
                    <div class="_open">
                        <a class="button-primary" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Open', 'd4plib' ); ?></a>
                    </div>
                </div>
            </div>

			<?php

		}

		?>
    </div>
</div>
