<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

// Class for temporary ugly code to keep everything together

class patches
{

  protected static $webfonts;
//'mbsocial/parsecss/', --filter

// From maxbuttons font block -
public static function checkWebFonts($args)
	{
    $defaults = array(
        'font' => '',
        'weight' => '400',

    );

    if (! Install::isPro())
      return;
      

    $args = wp_parse_args($args, $defaults);

		$system_fonts = array('', 'Arial', 'Courier New', 'Georgia', 'Tahoma', 'Times New Roman', 'Trebuchet MS',
	'Verdana');

		$webfontspath = MB()->get_plugin_path(true) . '/assets/fonts/webfonts.json';
		$webfontspath = apply_filters('maxbuttons/webfonts', $webfontspath);

		//$is_bold = (isset($options['bold']) && $options['bold'] == 'bold' ) ? true: false;
		//$is_italic = (isset($options['style']) && $options['style'] == 'italic' ) ? true: false;

    $font = $args['font'];
    $weight = $args['weight'];

		$full_font = $font;

    // system fonts not process by webfonts
		if(in_array($font, $system_fonts))
			 return;

     if ( is_null(static::$webfonts))
       static::$webfonts = json_decode(file_get_contents($webfontspath), true);

     $webfonts = static::$webfonts;

			foreach($webfonts['items'] as $index => $item)
			{
				if ($item['family'] == $font)
				{
					$weight = false;
					$variants = $item['variants'];

					if (in_array('regular', $variants))
					{
						$weight = 400;
					}
					elseif (in_array('300', $variants))
					{
						$weight = 300;
					}
					elseif(in_array('500', $variants))
					{
						$weight = 500;
					}
					break;
				}
			}
			$webfonts = null;

			$font = preg_replace('/\s/', '+', $font);
			$url = '//fonts.googleapis.com/css?family=' . $font . ':' . $weight;

			//$this->webfonts[$full_font] = $url;

      return $url;
	}

}
