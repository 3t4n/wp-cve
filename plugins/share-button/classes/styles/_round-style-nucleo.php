<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

roundNucleoStyle::registerStyle();

class roundNucleoStyle extends RoundStyle
{
	public $class = 'roundnucleo single-meta';
	public $name = 'roundnucleo';
	public $description = ' Round Buttons Basic with Nucleo Icons ';

	public $width = '35';
	public $height = '35';

	public $has_label = false;

	public function renderIcon($network)
	{
		$icon = $network->get('icon');
		$icon_type = $network->get('icon_type');

		$network_name = $network->get_nice_name();

		if ($icon_type == 'fa')
		{
			$icon = str_replace('fa-', '', $icon);
			$icon = 'nucleo-logo-' . $icon;

			$icon_type = 'nucleo'; 
		}

		$output = "<i class='mb-icon " . $icon_type . " " . $icon . "' title='" . $network_name . "'  > </i>";
		return apply_filters('mbsocial/rendericon', $output, $this, $network);

	}
}
