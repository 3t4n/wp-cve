<?php
namespace CbParallax\Admin\Partials;

use WP_Screen;

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The class responsible for creating and displaying the help sidebar.
 *
 * @since             0.9.0
 * @package           bonaire
 * @subpackage        bonaire/admin/partials
 * @author            Demis Patti <demis@demispatti.ch>
 */
class cb_parallax_help_sidebar_display {
	
	/**
	 * Returns a string containing the content of the 'Help Sidebar'.
	 *
	 * @param string $domain
	 * @param WP_Screen $current_screen
	 *
	 * @return string $html
	 *@since 0.9.0
	 */
	public static function help_sidebar_display(WP_Screen $current_screen ): string
    {
		
		$html = $current_screen->get_help_sidebar();
		
		ob_start();
		$html .= ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
}
