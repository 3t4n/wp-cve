<?php
namespace MbSocial;
defined('ABSPATH') or die('No direct access permitted');

use \maxbuttons\maxUtils as maxUtils;

// temp class ( hopefully ) for doing FA conversion on the server
class FAUtils
{
	protected static $instance = null;

	protected $faicons_array = null;
	protected $fashims_array = null;
	protected $faicons_searcharray = null;

	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = new FAUtils();

		}

		return self::$instance;
	}

	protected function getFAIcons()
	{
		if (! is_null($this->faicons_array) )
		{
			return $this->faicons_array;
		}

 		$dir =  MB()->get_plugin_path() . "assets/libraries/font-awesome-5";

		$icon_list = array();

		$icon_json = file_get_contents($dir . '/icons_processed.json');
		$file_icon_array = json_decode($icon_json, true);

		$icon_array = array();
		$search_array = array();

		if ( is_array($file_icon_array))
		{
			foreach($file_icon_array as $name => $data)
			{
					$styles = $data['styles']; // [brands, solid, regular]
					$nice_name = $data['label'];

					$style_tag = '';
					foreach($styles as $style)
					{
						 switch ($style)
						 {
							 case 'brands':
							 		$style_tag = 'fab';
									$category = 'brands';
							 break;
							 case 'solid':
							 	  $style_tag = 'fas';
									$category = 'solid';
							 break;
							 case 'regular':
							 		$style_tag = 'far';
									$category = 'regular';
							 break;
						 }
						 $full_icon = $style_tag . ' fa-' . $name;
						 $icon_array[$name][$style]['icon']  = $full_icon;
						 $icon_array[$name][$style]['name'] = $name;
						 $icon_array[$name][$style]['category'] = $category;
						 $icon_array[$name][$style]['nice_name'] = $nice_name;
						 $search_array[$full_icon]['path'] = $data['svg'][$style]['path'];
						 $search_array[$full_icon]['viewbox'] = $data['svg'][$style]['viewBox'];
					}

			}
		}
		$this->faicons_array = $icon_array;
		$this->faicons_searcharray = $search_array;
		return $icon_array;

	}

	public function getFASVG($args)
	{
			$defaults = array('icon'=> null,
												'title' => null,
												'size' => 20,
									);
			$args = wp_parse_args($args, $defaults);

			if (is_null($args['icon']) || strlen($args['icon']) <= 0)
			{
				return '';
			}

			if (is_null ($this->faicons_searcharray))
			{
				$this->getFAIcons();
			}

			$icons = $this->faicons_searcharray;

			$icon = $args['icon'];
			$size = $args['size'];

			if (! isset($icons[$icon]))
			{
				echo 'Icon not in icon set: ' . $icon;
			}

			$faicon_svg = $icons[$icon]['path'];
			$faicon_viewbox = implode(' ', $icons[$icon]['viewbox']);
			//$faicon_viewbox = '0 0 '  . $args['size'] . ' '  . $args['size'];

			$svg = '<svg class="svg-mbp-fa" width="' . $size . '" height="' . $size . '" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="' . $faicon_viewbox . '"><path fill="currentColor" d="' . $faicon_svg . '"></path></svg>';

			return $svg;
	}

	public function checkShims($icon)
	{
		if (is_null($this->fashims_array ) )
		{
 			$conversion_path = MB()->get_plugin_path() . '/assets/libraries/font-awesome-5/shims.json';
			$this->fashims_array = json_decode(file_get_contents($conversion_path), ARRAY_A);
		}

		$old_value = $icon;

		$old_value = str_replace('fa-','', $old_value);
		$old_value = str_replace('fa', '', $old_value);
		$old_value = trim($old_value);

		return $this->searchNewFA($old_value, $this->fashims_array);
	}

	public function searchNewFA($old, $conversion_array)
	{
	 $new = false;
	 foreach($conversion_array as $key => $items)
	 {
		 if ($items[0] == $old)
		 {
			 $new = (strlen($items[1]) > 0) ? $items[1] : 'fas'; // if not set, fas is the set
			 $new .= ' fa-';
			 $new .= (strlen($items[2]) > 0) ? $items[2] :  $old ;
		 }

	 }

	if (! $new)
	{
		 $new = 'fas fa-'. $old;
	}

	 return $new;
}

}
