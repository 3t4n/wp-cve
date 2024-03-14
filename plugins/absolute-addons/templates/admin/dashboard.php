<?php
/**
 * Dashboard Main Layout
 *
 * @package ABSP
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}
?>
<div class="wrap absp-dashboard">
	<div class="dashboard-header">
		<div class="page-title">
			<h1 class="wp-heading-inline">
				<span><?php esc_html_e( 'Welcome to', 'absolute-addons' ); ?></span>
				<span class="absp-plugin-name"><?php esc_html_e( 'Absolute Addons', 'absolute-addons' ); ?></span>
			</h1>
			<span class="version"><?php
				/* translators: 1. Plugin Version Number */
				printf( esc_html__( 'Version %s', 'absolute-addons' ), esc_html( ABSOLUTE_ADDONS_VERSION ) );
				?></span>
		</div>
		<div class="absp-branding">
			<img src="<?php absp_plugin_url('assets/images/logo.png' ); ?>" alt="">
		</div>
	</div>
	<hr class="wp-header-end">
	<div class="absp-dashboard--wrap">
		<div class="absp-dashboard-tabs" role="tablist">
			<div class="absp-dashboard-tabs__nav">
				<?php
				foreach ( self::get_registered_tabs() as $slug => $data ) {
					$slug = esc_attr( strtolower( $slug ) );
					$class = 'absp-dashboard-tabs__nav-item absp-dashboard-tabs__nav-item--' . $slug;

					if ( empty( $data['renderer'] ) || ! is_callable( $data['renderer'] ) ) {
						$class .= ' nav-item-is--link';
					}

					if ( ! empty( $data['href'] ) ) {
						$href = esc_url( $data['href'] );
					} else {
						$href = '#' . $slug;
					}

					$tab_title = isset( $data['tab_title'] ) ? $data['tab_title'] : '';
					$tab_title = ! $tab_title && isset( $data['page_title'] ) ? $data['page_title'] : $tab_title;
					$tab_title = ! $tab_title && isset( $data['menu_title'] ) ? $data['menu_title'] : $tab_title;

					printf(
						'<a href="%1$s" aria-controls="tab-content-%2$s" id="tab-nav-%2$s" class="%3$s" role="tab">%4$s</a>',
						esc_url( $href ),
						esc_attr( $slug ),
						esc_attr( $class ),
						esc_html( $tab_title )
					);
				}
				?>
				<button style="display: none;" class="absp-dashboard-tabs__nav-btn absp-dashboard-btn absp-dashboard-btn--lg absp-dashboard-btn--save" type="submit"><?php esc_html_e( 'SAVE SETTINGS', 'absolute-addons' ); ?></button>
			</div>
			<div class="clear"></div>
			<div class="absp-dashboard-tabs__content">
				<?php
				foreach ( self::get_registered_tabs() as $slug => $data ) {
					if ( empty( $data['renderer'] ) || ! is_callable( $data['renderer'] ) ) {
						continue;
					}

					$class = 'absp-dashboard-tabs__content-item';

					$slug = esc_attr( strtolower( $slug ) );
					?>
					<div class="<?php echo esc_attr( $class ); ?>" id="tab-content-<?php echo esc_attr( $slug ); ?>" role="tabpanel" aria-labelledby="tab-nav-<?php echo esc_attr( $slug ); ?>">
						<?php call_user_func( $data['renderer'], $slug, $data ); ?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<?php
// End of file dashboard.php.
