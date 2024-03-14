<?php
/**
 * Template Menu Items Backend.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<li data-section="eos-dp-backend-everywhere" class="hover<?php echo '' === $pro ? ' fdp-pro-feature fdp-dismiss-pro-notice' : ''; ?> eos-dp-setts-menu-item"><a href="<?php echo '' === $pro ? esc_url( EOS_DP_DOCUMENTATION_URL . '/backend/' ) : esc_url( admin_url( 'admin.php?page=eos_dp_backend_everywhere' ) ); ?>"><?php esc_html_e( 'Disabled on backend', 'freesoul-deactivate-plugins' ); ?></a></li>
<li data-section="eos-dp-admin" class="hover eos-dp-setts-menu-item"><a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_admin&all=1' ) ); ?>"><?php esc_html_e( 'Backend Singles', 'freesoul-deactivate-plugins' ); ?></a></li>
<li data-section="eos-dp-admin-url" class="eos-dp-submenu-item hover"><a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_admin_url' ) ); ?>"><?php esc_html_e( 'Backend URLs', 'freesoul-deactivate-plugins' ); ?></a></li>
