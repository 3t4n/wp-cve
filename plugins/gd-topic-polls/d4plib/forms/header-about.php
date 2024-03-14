<?php

use Dev4Press\v43\Core\Quick\Sanitize;
use function Dev4Press\v43\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_panels    = panel()->a()->panels();
$_subpanels = panel()->subpanels();
$_subpanel  = panel()->current_subpanel();
$_classes   = panel()->wrapper_class();

$_plugin_title = sprintf(
/* translators: About page welcome message. %1$s: Plugin Name. %2%s: Pro suffix. %3%s: Plugin version. */
	__( 'Welcome to %1$s%2$s %3$s', 'd4plib' ),
	panel()->a()->title(),
	panel()->a()->settings()->i()->is_pro() ? ' Pro' : '',
	panel()->a()->settings()->i()->version
);

?>
<div class="<?php echo Sanitize::html_classes( $_classes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
    <div class="d4p-about-head-wrapper">
        <div class="d4p-about-information">
            <h1><?php echo esc_html( $_plugin_title ); ?></h1>
            <p class="d4p-about-text">
				<?php echo esc_html( panel()->a()->settings()->i()->description() ); ?>
            </p>
        </div>
        <div class="d4p-about-badge">
            <div class="d4p-about-badge-inner" style="background-color: <?php echo esc_attr( panel()->a()->settings()->i()->color() ); ?>;">
				<?php echo panel()->r()->icon( 'plugin-' . panel()->a()->plugin ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php

				/* translators: About page plugin sign. %s: Plugin version. */
				printf( esc_html__( 'Version %s', 'd4plib' ), panel()->a()->settings()->i()->version ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				?>
            </div>
        </div>
    </div>

    <h2 class="nav-tab-wrapper wp-clearfix">
		<?php

		if ( panel()->a()->variant == 'submenu' ) {
			echo '<a href="' . esc_url( panel()->a()->panel_url() ) . '" class="nav-tab"><i class="d4p-icon d4p-ui-home"></i></a>';
		}

		foreach ( $_subpanels as $_tab => $obj ) {
			echo '<a href="' . esc_url( panel()->a()->panel_url( 'about', $_tab ) ) . '" class="nav-tab' . ( $_tab == $_subpanel ? ' nav-tab-active' : '' ) . '">' . esc_html( $obj['title'] ) . '</a>';
		}

		?>
    </h2>

    <div class="d4p-about-inner">
