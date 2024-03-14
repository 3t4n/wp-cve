<?php

#namespace WilokeTest;

class WilokeClass
{
	public function __construct()
	{
		add_shortcode('sample_shortcode', [$this, 'renderShortcode']);
	}

	public function renderShortcode($aAtts, $content = '')
	{
		$aAtts = shortcode_atts(
			[],
			$aAtts
		);

		ob_start();
		?>

		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
}
