<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Layout_404_HC_MVC extends _HC_MVC
{
	public function __toString()
	{
		return '' . $this->render();
	}

	public function render()
	{
		$header = '404 Page Not Found';
		$content = 'The page you requested was not found';

		$out = $this->app->make('/layout/view/content-header-menubar')
			->set_content( $content )
			->set_header( $header )
			;
		return $out;
	}
}