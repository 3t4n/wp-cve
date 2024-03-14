<?php
/**
 * Template Menu Items Fronentd URLs.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<li data-section="eos-dp-plugin-by-url" class="eos-dp-submenu-item hover"><a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_one_place' ) ); ?>"><?php esc_html_e( 'Plugin by URL', 'freesoul-deactivate-plugins' ); ?></a></li>
<li data-section="eos-dp-url" class="eos-dp-submenu-item hover"><a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_url' ) ); ?>"><?php esc_html_e( 'Frontend URLs', 'freesoul-deactivate-plugins' ); ?></a></li>
<li data-section="eos-dp-admin-url" class="eos-dp-submenu-item hover"><a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_admin_url' ) ); ?>"><?php esc_html_e( 'Backend URLs', 'freesoul-deactivate-plugins' ); ?></a></li>
<li data-section="eos-dp-translation-url" class="eos-dp-submenu-item hover
<?php
echo '' === $pro ? ' fdp-pro-feature fdp-dismiss-pro-notice fdp-show-only-on-pro' : '';
?>
"><a href="<?php echo '' === $pro ? esc_url( EOS_DP_DOCUMENTATION_URL . '/translation-urls/' ) : esc_url( admin_url( 'admin.php?page=eos_dp_translation_urls' ) ); ?>"><?php esc_html_e( 'Translation URLs', 'freesoul-deactivate-plugins' ); ?></a></li>
