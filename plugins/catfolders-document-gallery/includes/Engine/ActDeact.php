<?php
namespace CatFolder_Document_Gallery\Engine;

use CatFolder_Document_Gallery\Utils\SingletonTrait;

/**
 * Activate and deactive method of the plugin and relates.
 */
class ActDeact {

	use SingletonTrait;

	protected function __construct() {}

	public static function install_catf_dg_admin_notice() {
		/* translators: %s: Woocommerce link */
		echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'CatFolders Document Gallery is enabled but not effective. It requires %s in order to work', 'catfolders-document-gallery' ), '<a href="' . esc_url( admin_url( 'plugin-install.php?s=CatFolders&tab=search&type=term' ) ) . '">CatFolders</a>' ) . '</strong></p></div>';
		return false;
	}

	public static function activate() {

	}

	public static function deactivate() {

	}

}
