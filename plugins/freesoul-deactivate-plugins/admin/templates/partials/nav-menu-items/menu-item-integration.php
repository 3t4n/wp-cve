<?php
/**
 * Template Menu Items Integration.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<li data-section="eos-dp-control-panel-section" class="hover
<?php
echo '' === $pro ? ' fdp-pro-feature fdp-dismiss-pro-notice' : '';
?>
"><a href="<?php echo '' === $pro ? esc_url( EOS_DP_DOCUMENTATION_URL . '/cleaning-ajax-post-actions/' ) : esc_url( admin_url( 'admin.php?page=eos_dp_ajax' ) ); ?>"><?php esc_html_e( 'Ajax Actions Recorder', 'freesoul-deactivate-plugins' ); ?></a></li>
<li data-section="eos-dp-control-panel-section" class="hover
<?php
echo '' === $pro ? ' fdp-pro-feature fdp-dismiss-pro-notice fdp-show-only-on-pro' : '';
?>
"><a href="<?php echo '' === $pro ? esc_url( EOS_DP_DOCUMENTATION_URL . '/cleaning-ajax-post-actions/' ) : esc_url( admin_url( 'admin.php?page=eos_dp_by_post_requests' ) ); ?>"><?php esc_html_e( 'Post Actions Recorder', 'freesoul-deactivate-plugins' ); ?></a></li>
<?php
do_action( 'eos_dp_actions_menu_items' );
foreach ( $plugins_integration as $plugin_slug => $arr ) {
	if ( $arr['is_active'] ) {
		?>
  <li data-section="eos-dp-<?php echo esc_attr( $plugin_slug ); ?>" class="eos-dp-submenu-item hover">
	<a href="
		<?php
		echo esc_url(
			add_query_arg(
				array(
					'page'       => 'eos_dp_integration',
					'int_plugin' => $plugin_slug,
				),
				admin_url( 'admin.php' )
			)
		);
		?>
				"><?php echo esc_html( strtoupper( str_replace( '-', ' ', $plugin_slug ) ) ); ?></a>
  </li>
		<?php
	}
}
