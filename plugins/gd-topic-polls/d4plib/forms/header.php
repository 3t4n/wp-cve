<?php

use Dev4Press\v43\Core\Quick\Sanitize;
use function Dev4Press\v43\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_panels    = panel()->a()->panels();
$_panel     = panel()->a()->panel;
$_subpanels = panel()->subpanels();
$_subpanel  = panel()->current_subpanel();
$_classes   = panel()->wrapper_class();
$_features  = false;

if ( panel()->a()->plugin()->f() ) {
	$_features = $_panel == 'features';
}

?>
<div class="<?php echo Sanitize::html_classes( $_classes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
	<?php panel()->include_notices(); ?>

    <div class="d4p-header">
        <div class="d4p-navigator">
            <ul>
                <li class="d4p-nav-button">
                    <a href="#"><?php echo panel()->r()->icon( $_panels[ $_panel ]['icon'] );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $_panels[ $_panel ]['title'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
					<?php if ( $_panel != 'install' && $_panel != 'update' ) { ?>
                        <ul>
							<?php

							foreach ( $_panels as $panel => $obj ) {
								if ( ! isset( $obj['type'] ) ) {
									$scope = $obj['scope'] ?? array();
									$add   = true;

									if ( ! empty( $scope ) && is_multisite() ) {
										$current = is_network_admin() ? 'network' : 'blog';
										$add     = in_array( $current, $scope );
									}

									if ( $add ) {
										if ( $panel != $_panel ) {
											echo '<li><a href="' . esc_url( panel()->a()->panel_url( $panel ) ) . '">' . panel()->r()->icon( $obj['icon'], 'fw' ) . $obj['title'] . '</a></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										} else {
											echo '<li class="d4p-nav-current">' . panel()->r()->icon( $obj['icon'], 'fw' ) . $obj['title'] . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										}
									}
								}
							}

							?>
                        </ul>
					<?php } ?>
                </li>
				<?php if ( ! empty( $_subpanels ) ) { ?>
                    <li class="d4p-nav-button">
                        <a href="#"><?php echo panel()->r()->icon( $_subpanels[ $_subpanel ]['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $_subpanels[ $_subpanel ]['title'] ); ?></a>
                        <ul>
							<?php

							foreach ( $_subpanels as $subpanel => $obj ) {
								$_feature_status = '';

								if ( isset( $obj['skip'] ) && in_array( 'menu', $obj['skip'] ) ) {
									continue;
								}

								if ( $_features && $subpanel != 'index' && isset( $obj['active'] ) ) {
									$icon = 'd4p-ui-times';

									if ( $obj['hidden'] ) {
										$icon = 'd4p-ui-eye-slash';
									} else if ( $obj['active'] || $obj['always_on'] ) {
										$icon = 'd4p-ui-check';
									}

									$_feature_status = '<i class="d4p-features-mark d4p-icon ' . $icon . '"></i>';
								}

								$_url = $obj['url'] ?? panel()->a()->panel_url( $_panel, $subpanel );

								if ( $subpanel != $_subpanel ) {
									echo '<li><a href="' . esc_url( $_url ) . '">' . panel()->r()->icon( $obj['icon'], 'fw' ) . esc_html( $obj['title'] ) . $_feature_status . '</a></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								} else {
									echo '<li class="d4p-nav-current">' . panel()->r()->icon( $obj['icon'], 'fw' ) . esc_html( $obj['title'] ) . $_feature_status . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}
							}

							?>
                        </ul>
                    </li>
				<?php } ?>
				<?php echo panel()->header_fill(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </ul>
        </div>
        <div class="d4p-plugin">
			<?php echo panel()->a()->title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
    </div>
	<?php panel()->include_messages(); ?>
    <div class="d4p-main">
