<?php
/*
Plugin Name: Shadows
Plugin URI: http://deepport.net/computing/wordpress-shadows-plugin/
Description: Adds a range of shadow types to images, divs and blockquotes
Version: 0.3.5
Author: Andrew Radke
Author URI: http://deepport.net/
*/

/*  Copyright 2009  Andrew Radke  (email : andrew@deepport.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function shadow_curls($content) {
	$plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );

	$curl_shadow_height = get_option('curl_shadow_height');
	$curl_shadow_height = $curl_shadow_height ? $curl_shadow_height : '10px';

	$flat_shadow_height = get_option('flat_shadow_height');
	$flat_shadow_height = $flat_shadow_height ? $flat_shadow_height : '10px';

	$curl_shadow_opacity = get_option('curl_shadow_opacity');
	if ($curl_shadow_opacity) {
		if ($curl_shadow_opacity < 10) $curl_shadow_opacity = "0".$curl_shadow_opacity;
		if ($curl_shadow_opacity < 100) {
#			$curl_shadow_opacity = "filter: alpha(opacity=$curl_shadow_opacity);-moz-opacity:.$curl_shadow_opacity;opacity:.$curl_shadow_opacity;";
			$curl_shadow_opacity = "-moz-opacity:.$curl_shadow_opacity;opacity:.$curl_shadow_opacity;";
		} else {
			$curl_shadow_opacity = '';
		}
	}

	$flat_shadow_opacity = get_option('flat_shadow_opacity');
	if ($flat_shadow_opacity) {
		if ($flat_shadow_opacity < 10) $flat_shadow_opacity = "0".$flat_shadow_opacity;
		if ($flat_shadow_opacity < 100) {
#			$flat_shadow_opacity = "filter: alpha(opacity=$flat_shadow_opacity);-moz-opacity:.$flat_shadow_opacity;opacity:.$flat_shadow_opacity;";
			$flat_shadow_opacity = "-moz-opacity:.$flat_shadow_opacity;opacity:.$flat_shadow_opacity;";
		} else {
			$flat_shadow_opacity = '';
		}
	}

	preg_match_all ( '/<img [^>]*[" ]shadow_(curl|flat|osx|osx_small)[" ][^>]*\/?>/ims',$content, $images );
	foreach ( $images[0] as $image) {
		$image_replace = $image;

		$width = preg_replace ('/.*width="?(\d*).*/i', '\\1', $image);
		if ($width == $image) $width = '100%';

		$align = preg_replace ('/.*class="([^"]* |)(align[^ "]*).*/i', '\\2', $image);
		if ($align == $image) $align = 'alignnone';

		$type = preg_replace ('/.*[" ]shadow_(curl|flat|osx|osx_small)[" ].*/i', '\\1', $image);

		if ($type == 'curl') {
			$height = $curl_shadow_height;
			$opacity = $curl_shadow_opacity;
		}
		if ($type == 'flat') {
			$height = $flat_shadow_height;
			$opacity = $flat_shadow_opacity;
		}

		$style = preg_replace ('/.*style="([^"]*)".*/i', '\\1', $image);
		if ($style == $image) {
			$style = '';
		} else {
			$borderwidthleft = 0;
			$borderwidthright = 0;
			preg_match_all ( '/border[^;"]*/ims',$style, $borders );
			foreach ( $borders[0] as $border) {
				$borderwidth = preg_replace ('/border-(top|bottom)/i', '\\1', $border);
				if ($borderwidth != $border) continue;

				$borderwidth = preg_replace ('/border-left[^\d]*(\d*)px.*/i', '\\1', $border);
				if ($borderwidth != $border) {
					$borderwidthleft = $borderwidth;
					continue;
				}
				$borderwidth = preg_replace ('/border-right[^\d]*(\d*)px.*/i', '\\1', $border);
				if ($borderwidth != $border) {
					$borderwidthright = $borderwidth;
					continue;
				}
				$borderwidth = preg_replace ('/[^\d]*(\d*)px.*/i', '\\1', $border);
				$borderwidthleft = $borderwidth;
				$borderwidthright = $borderwidth;
			}
			$width = $width + $borderwidthleft + $borderwidthright;

			preg_match_all ( '/margin[^;"]*/ims',$style, $margins );
			$style = '';
			foreach ( $margins[0] as $margin) {
				$style .= $margin . ";";
				$image_replace = str_replace ($margin, '' , $image_replace);
			}
		}
		
		if (($type == 'curl') || ($type == 'flat')) {
			$pre_image = '<div style="overflow:hidden;display:table;line-height:0;text-align:center;width:'.$width.'px;'.$style.'" class="'.$align.'">';
			$post_image = '<br/><img src="'.$plugin_url.'/shadow_'.$type.'.png" class="shadow_img" style="margin:0 !important;height:'.$height.';width:100%;'.$opacity.'"></div>';
		} elseif ($type == 'osx') {
			$width += 30;
			$pre_image = '<div style="overflow:hidden;width:'.$width.'px; '.$style.'" class="'.$align.'">
<div style="background: transparent url('.$plugin_url.'/shadow_osx.png) no-repeat left top; width: 30px; height: 7px; float: left;" class="shadow_img"></div>
<div style="background: transparent url('.$plugin_url.'/shadow_osx.png) no-repeat right top; width: 30px; height: 7px; float: right;" class="shadow_img"></div>
<div style="background: transparent url('.$plugin_url.'/shadow_osx_top.png) repeat-x center top; margin: 0 30px; height: 7px;" class="shadow_img"></div>
<table style="margin:0;padding:0;width:100%;empty-cells:show;border-collapse:collapse;"><tr>
<td style="margin:0;padding:0;border-width:0;background: transparent url('.$plugin_url.'/shadow_osx.png) no-repeat left -7px; width: 15px; height: 25px;" class="shadow_img"></td>
<td rowspan=2 style="margin:0;padding:0;border-width:0; background-color: transparent; line-height:1px;">
';
			$post_image = '
</td>
<td style="margin:0;padding:0;border-width:0;background: transparent url('.$plugin_url.'/shadow_osx.png) no-repeat right -7px; width: 15px; height: 25px;" class="shadow_img"></td>
</tr>
<tr>
<td style="background: transparent url('.$plugin_url.'/shadow_osx_left.png) repeat-y left center; width: 15px;margin:0;padding:0;border-width:0;" class="shadow_img"></td>
<td style="background: transparent url('.$plugin_url.'/shadow_osx_right.png) repeat-y right center; width: 15px;margin:0;padding:0;border-width:0;" class="shadow_img"></td>
</tr>
</table>
<div style="background: transparent url('.$plugin_url.'/shadow_osx.png) no-repeat left bottom; width: 30px; height: 23px; float: left;" class="shadow_img"></div>
<div style="background: transparent url('.$plugin_url.'/shadow_osx.png) no-repeat right bottom; width: 30px; height: 23px; float: right;" class="shadow_img"></div>
<div style="background: transparent url('.$plugin_url.'/shadow_osx_bottom.png) repeat-x center bottom; margin: 0 30px; height: 23px;" class="shadow_img"></div>
</div>
';
		} elseif ($type == 'osx_small') {
			$width += 12;
			$pre_image = '<div style="overflow:hidden;width:'.$width.'px; '.$style.'" class="'.$align.'">
<div style="background: transparent url('.$plugin_url.'/shadow_osx_small.png) no-repeat left top; width: 30px; height: 2px; float: left;" class="shadow_img"></div>
<div style="background: transparent url('.$plugin_url.'/shadow_osx_small.png) no-repeat right top; width: 30px; height: 2px; float: right;" class="shadow_img"></div>
<div style="background: transparent url('.$plugin_url.'/shadow_osx_small_top.png) repeat-x center top; margin: 0 30px; height: 2px;" class="shadow_img"></div>
<table style="margin:0;padding:0;width:100%;empty-cells:show;border-collapse:collapse;"><tr>
<td style="margin:0;padding:0;border-width:0;background: transparent url('.$plugin_url.'/shadow_osx_small.png) no-repeat left -2px; width: 6px; height: 25px;" class="shadow_img"></td>
<td rowspan=2 style="margin:0;padding:0;border-width:0; background-color: transparent; line-height:1px;">
';
			$post_image = '
</td>
<td style="margin:0;padding:0;border-width:0;background: transparent url('.$plugin_url.'/shadow_osx_small.png) no-repeat right -2px; width: 6px; height: 25px;" class="shadow_img"></td>
</tr>
<tr>
<td style="background: transparent url('.$plugin_url.'/shadow_osx_small_left.png) repeat-y left center; width: 6px;margin:0;padding:0;border-width:0;" class="shadow_img"></td>
<td style="background: transparent url('.$plugin_url.'/shadow_osx_small_right.png) repeat-y right center; width: 6px;margin:0;padding:0;border-width:0;" class="shadow_img"></td>
</tr>
</table>
<div style="background: transparent url('.$plugin_url.'/shadow_osx_small.png) no-repeat left bottom; width: 30px; height: 10px; float: left;" class="shadow_img"></div>
<div style="background: transparent url('.$plugin_url.'/shadow_osx_small.png) no-repeat right bottom; width: 30px; height: 10px; float: right;" class="shadow_img"></div>
<div style="background: transparent url('.$plugin_url.'/shadow_osx_small_bottom.png) repeat-x center bottom; margin: 0 30px; height: 10px;" class="shadow_img"></div>
</div>
';		}

		### Add the styles required to the image
		if (($type == 'curl') || ($type == 'flat')) {
			$image_style = 'padding:0 !important; margin:0 !important; max-width:100% !important;';
		} elseif (($type == 'osx') || ($type == 'osx_small')) {
			$image_style = 'padding:0 !important; margin:0 !important; vertical-align:text-bottom !important; min-height: 25px !important;';
		}
		if (strpos($image, "style=") == true) {
			$image_replace = preg_replace ('/style="[^"]*/', "\\0; $image_style", $image_replace);
		} else {
			$image_replace = preg_replace ('/\/?>$/', ' style="'.$image_style.'">', $image_replace);
		}

		### Remove any align class from the image that might have associated style
		$image_replace = preg_replace ('/(class=")([^"]* |)(align[^ "]*)/i', '\\1\\2', $image_replace);

		$replace = "$pre_image$image_replace$post_image";
		$content = str_replace ($image, $replace , $content);
	}

	preg_match_all ( '/<(div|blockquote) [^>]*[" ]shadow_(curl|flat|osx|osx_small)[" ][^>]*>.*<\/\\1>/imsU',$content, $objects );
	foreach ( $objects[0] as $object) {
		$object_type = preg_replace ('/^<(div|blockquote) .*/ims', '\\1', $object);
		$object_replace = $object;

		$width = preg_replace ('/^<'.$object_type.' [^>]*[" ](width:\s*\d*)(em|px|%).*/ims', '\\1\\2', $object);
		if ($width == $object) $width = '';

		$align = preg_replace ('/^<'.$object_type.' [^>]*class="([^"]* |)(align[^ "]*).*/ims', '\\2', $object);
		if ($align == $object) $align = 'alignnone';

		$type = preg_replace ('/^<'.$object_type.' [^>]*class="([^"]* |)shadow_(curl|flat|osx|osx_small)[" ].*/ims', '\\2', $object);

		if ($type == 'curl') {
			$height = $curl_shadow_height;
			$opacity = $curl_shadow_opacity;
		}
		if ($type == 'flat') {
			$height = $flat_shadow_height;
			$opacity = $flat_shadow_opacity;
		}

		$style = preg_replace ('/^<'.$object_type.' [^>]*style="([^"]*)".*/ims', '\\1', $object);
		if ($style == $object) {
			$style = '';
		} else {
			preg_match_all ( '/margin[^;"]*/ims',$style, $margins );
			$style = '';
			foreach ( $margins[0] as $margin) {
				$style .= $margin . ";";
				$object_replace = str_replace ($margin, '' , $object_replace);
			}
		}

		if (($type == 'curl') || ($type == 'flat')) {
			$pre_div = '<div style="overflow:hidden;'.$width.'; '.$style.'" class="'.$align.'">';
			$post_div = '<img src="'.$plugin_url.'/shadow_'.$type.'.png" class="aligncenter shadow_img" style="margin:0 !important;height:'.$height.';width:100%;'.$opacity.'"></div>';
		} elseif ($type == 'osx') {
			$pre_div = '<div style="overflow:hidden;'.$width.'; '.$style.'" class="'.$align.'">
<div style="background: transparent url('.$plugin_url.'/shadow_osx.png) no-repeat left top; width: 30px; height: 7px; float: left;" class="shadow_img"></div>
<div style="background: transparent url('.$plugin_url.'/shadow_osx.png) no-repeat right top; width: 30px; height: 7px; float: right;" class="shadow_img"></div>
<div style="background: transparent url('.$plugin_url.'/shadow_osx_top.png) repeat-x center top; margin: 0 30px; height: 7px;" class="shadow_img"></div>
<table style="margin:0;padding:0;width:100%;empty-cells:show;border-collapse:collapse;"><tr>
<td style="margin:0;padding:0;border-width:0;background: transparent url('.$plugin_url.'/shadow_osx.png) no-repeat left -7px; width: 15px; height: 25px;" class="shadow_img"></td>
<td rowspan=2 style="margin:0;padding:0;border-width:0; background-color: transparent;">
';
			$post_div = '
</td>
<td style="margin:0;padding:0;border-width:0;background: transparent url('.$plugin_url.'/shadow_osx.png) no-repeat right -7px; width: 15px; height: 25px;" class="shadow_img"></td>
</tr>
<tr>
<td style="background: transparent url('.$plugin_url.'/shadow_osx_left.png) repeat-y left center; width: 15px;margin:0;padding:0;border-width:0;" class="shadow_img"></td>
<td style="background: transparent url('.$plugin_url.'/shadow_osx_right.png) repeat-y right center; width: 15px;margin:0;padding:0;border-width:0;" class="shadow_img"></td>
</tr>
</table>
<div style="background: transparent url('.$plugin_url.'/shadow_osx.png) no-repeat left bottom; width: 30px; height: 23px; float: left;" class="shadow_img"></div>
<div style="background: transparent url('.$plugin_url.'/shadow_osx.png) no-repeat right bottom; width: 30px; height: 23px; float: right;" class="shadow_img"></div>
<div style="background: transparent url('.$plugin_url.'/shadow_osx_bottom.png) repeat-x center bottom; margin: 0 30px; height: 23px;" class="shadow_img"></div>
</div>
';
		} elseif ($type == 'osx_small') {
			$pre_div = '<div style="overflow:hidden;'.$width.'; '.$style.'" class="'.$align.'">
<div style="background: transparent url('.$plugin_url.'/shadow_osx_small.png) no-repeat left top; width: 30px; height: 2px; float: left;" class="shadow_img"></div>
<div style="background: transparent url('.$plugin_url.'/shadow_osx_small.png) no-repeat right top; width: 30px; height: 2px; float: right;" class="shadow_img"></div>
<div style="background: transparent url('.$plugin_url.'/shadow_osx_small_top.png) repeat-x center top; margin: 0 30px; height: 2px;" class="shadow_img"></div>
<table style="margin:0;padding:0;width:100%;empty-cells:show;border-collapse:collapse;"><tr>
<td style="margin:0;padding:0;border-width:0;background: transparent url('.$plugin_url.'/shadow_osx_small.png) no-repeat left -2px; width: 6px; height: 25px;" class="shadow_img"></td>
<td rowspan=2 style="margin:0;padding:0;border-width:0; background-color: transparent;">
';
			$post_div = '
</td>
<td style="margin:0;padding:0;border-width:0;background: transparent url('.$plugin_url.'/shadow_osx_small.png) no-repeat right -2px; width: 6px; height: 25px;" class="shadow_img"></td>
</tr>
<tr>
<td style="background: transparent url('.$plugin_url.'/shadow_osx_small_left.png) repeat-y left center; width: 6px;margin:0;padding:0;border-width:0;" class="shadow_img"></td>
<td style="background: transparent url('.$plugin_url.'/shadow_osx_small_right.png) repeat-y right center; width: 6px;margin:0;padding:0;border-width:0;" class="shadow_img"></td>
</tr>
</table>
<div style="background: transparent url('.$plugin_url.'/shadow_osx_small.png) no-repeat left bottom; width: 30px; height: 10px; float: left;" class="shadow_img"></div>
<div style="background: transparent url('.$plugin_url.'/shadow_osx_small.png) no-repeat right bottom; width: 30px; height: 10px; float: right;" class="shadow_img"></div>
<div style="background: transparent url('.$plugin_url.'/shadow_osx_small_bottom.png) repeat-x center bottom; margin: 0 30px; height: 10px;" class="shadow_img"></div>
</div>
';
		}

		### Add the styles required to the original object
		if (($type == 'curl') || ($type == 'flat')) {
			$object_style = 'margin:0 !important; max-width:100% !important;';
		} elseif (($type == 'osx') || ($type == 'osx_small')) {
			$object_style = 'margin:0 !important; max-width:100% !important; min-height: 25px !important; border: 1px solid #d4d4d4;';
		}

		# We need to check for a style on the object and not just something inside it
		preg_match ( '/^<'.$object_type.' [^>]*style="/i',$object, $check );
		if ($check) {
			$object_replace = preg_replace ('/^<'.$object_type.' [^>]*style="[^"]*/i', "\\0; $object_style", $object_replace);
		} else {
			$object_replace = preg_replace ('/^(<'.$object_type.'[^>]*)>/i', '\\1 style="'.$object_style.'">', $object_replace);
		}

		### Strip any width from the original object
		$object_replace = preg_replace ('/^(<'.$object_type.' [^>]*[" ])width:\s*\d*(em|px|%)/i', '\\1', $object_replace);

		### Remove any align class from the image that might have associated style
		$object_replace = preg_replace ('/^(<'.$object_type.' [^>]*class=")([^"]* |)(align[^ "]*)/ims', '\\1\\2', $object_replace);

		$replace = "$pre_div\n$object_replace\n$post_div\n";
		$content = str_replace ($object, $replace , $content);
	}

	return $content;
}

function ie6_headers() {
	$plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );
	echo '
<!--[if lt IE 7]>
<link rel="stylesheet" href="'.$plugin_url.'/ie6.css" type="text/css" media="all" />
<![endif]-->
';
}


add_filter('the_content', 'shadow_curls', 20);
add_action('wp_head', 'ie6_headers');

include_once (dirname (__FILE__)."/options.php");
add_action('admin_menu', 'shadows_plugin_menu');
function shadows_plugin_menu() {
	add_options_page('Shadows Options', 'Shadows', 8, __FILE__, 'shadows_plugin_options');
}

?>
