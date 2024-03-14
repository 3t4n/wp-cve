<?php
/**
 * Template Menu Items Users.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<li data-section="eos-dp-logged" class="hover
<?php
echo '' === $pro ? ' fdp-pro-feature fdp-dismiss-pro-notice' : '';
?>
 eos-dp-setts-menu-item"><a href="<?php echo '' === $pro ? esc_url( EOS_DP_DOCUMENTATION_URL . '/users/' ) : esc_url( admin_url( 'admin.php?page=eos_dp_logged' ) ); ?>"><?php esc_html_e( 'Logged Users', 'freesoul-deactivate-plugins' ); ?></a></li>
<li data-section="eos-dp-unlogged" class="hover
<?php
echo '' === $pro ? ' fdp-pro-feature fdp-dismiss-pro-notice' : '';
?>
 eos-dp-setts-menu-item"><a href="<?php echo '' === $pro ? esc_url( EOS_DP_DOCUMENTATION_URL . '/users/' ) : esc_url( admin_url( 'admin.php?page=eos_dp_unlogged' ) ); ?>"><?php esc_html_e( 'unlogged Users', 'freesoul-deactivate-plugins' ); ?></a></li>
