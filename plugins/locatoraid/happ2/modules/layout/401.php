<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Layout_401_HC_MVC extends _HC_MVC
{
	public function __toString()
	{
		return '' . $this->render();
	}

	public function render( $body = NULL )
	{
		$header = '401 Unauthorized';
		$content = $body;

		$out = $this->app->make('/layout/view/content-header-menubar')
			->set_content( $content )
			->set_header( $header )
			;
		return $out;
	}
}