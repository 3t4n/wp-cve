<?php
/**
 * Template Menu Items Device.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<li id="fdp-menu-mobile" data-section="eos-dp-mobile" class="hover eos-dp-setts-menu-item"><a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_mobile' ) ); ?>"><?php esc_html_e( 'Mobile', 'freesoul-deactivate-plugins' ); ?></a></li>
<li id="fdp-menu-desktop" data-section="eos-dp-desktop" class="hover
<?php
echo $_GET['page'] === 'eos_dp_desktop' ? ' eos-active' : '';
echo '' === $pro ? ' fdp-pro-feature fdp-dismiss-pro-notice' : '';
?>
 eos-dp-setts-menu-item"><a href="<?php echo '' === $pro ? esc_url( EOS_DP_DOCUMENTATION_URL . '/device/' ) : esc_url( admin_url( 'admin.php?page=eos_dp_desktop' ) ); ?>"><?php esc_html_e( 'Desktop', 'freesoul-deactivate-plugins' ); ?></a></li>
