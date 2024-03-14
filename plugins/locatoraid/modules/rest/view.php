<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Rest_View_LC_HC_MVC extends _HC_MVC
{
	public function render()
	{
		ob_start();
		require( dirname(__FILE__) . '/view.html.php' );
		$out = ob_get_contents();
		ob_end_clean();

		return $out;
	}
}