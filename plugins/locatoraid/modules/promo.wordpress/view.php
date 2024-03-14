<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Promo_Wordpress_View_LC_HC_MVC extends _HC_MVC
{
	public function render()
	{
		$is_me = $this->app->make('/app/lib')->isme();

		if( ! $is_me ){
			return;
		}

		if( isset($_GET['hca']) && ('promo' == $_GET['hca']) ){
			return;
		}

		ob_start();
		require( dirname(__FILE__) . '/view.html.php' );
		$out = ob_get_contents();
		ob_end_clean();

		return $out;
	}
}