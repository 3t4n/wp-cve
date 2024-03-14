<?php
/**
 * Podcast player options page
 *
 * @package Podcast Player
 * @since 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Podcast_Player\Helper\Functions\Markup as Markup_Fn;

?>

<div id="pp-options-page" class="pp-options-page">
	<div class="pp-options-header">
		<div class="pp-options-title">
			<h3><a class="pp-options-title-link" href="https://easypodcastpro.com/podcast-player/"><?php esc_html_e( 'Podcast Player', 'podcast-player' ); ?></a></h3>
		</div>
		<div class="pp-options-links">
			<a class="pp-options-link" href="https://wordpress.org/support/plugin/podcast-player/" target="_blank"></a>
		</div>
	</div>
	<div class="pp-options-main">
		<div id="pp-options-content" class="pp-options-content">
			<ul class="pp-options-menu">
				<?php
				foreach ( $this->modules as $key => $args ) {
					printf(
						'<li class="pp-module-item"><a href="%1$s" class="pp-module-item-link"><span class="pp-module-text">%2$s</span></a></li>',
						esc_url( admin_url( 'admin.php?page=pp-' . $key ) ),
						esc_html( $args['label'] )
					);
				}
				?>
			</ul>
			<div class="pp-options-content-wrapper">
				<div class="pp-options-content-area">
					<?php
					$located = Markup_Fn::locate_admin_template( $current_page );
					if ( $located ) {
						printf( '<div id="pp-options-module-%s" class="pp-module-content">', esc_attr( $current_page ) );
						include_once $located;
						echo '</div>';
					}
					?>
				</div>
				<div class="pp-options-footer">
					<div class="pp-options-copyright"><span><?php esc_html_e( 'Vedathemes', 'podcast-player' ); ?> &copy; <?php echo esc_html( date_i18n( __( 'Y', 'podcast-player' ) ) ); ?></span></div>
				</div>
			</div>
		</div>
		<div class="pp-options-sidebar">
			<?php require PODCAST_PLAYER_DIR . '/backend/admin/templates/sidebar.php'; ?>
		</div>
	</div>
	<div class="pp-action-feedback" id="pp-action-feedback">
		<span class="dashicons dashicons-update"></span>
		<span class="dashicons dashicons-no"></span>
		<span class="dashicons dashicons-yes"></span>
		<span class="pp-feedback"></span>
		<span class="pp-error-close"><span class="dashicons dashicons-no"></span></span>
	</div>
</div>
