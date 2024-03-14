<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * VikAppointments HTML media helper.
 *
 * @since 1.7.2
 */
abstract class VAPHtmlMedia
{
	/**
	 * Displays an image tag for the specified media file.
	 * 
	 * @param 	string 	$image  Either the image name or a complete URI.
	 * @param 	array 	$attrs  An array of attributes to be used.
	 *                          - loading   The loading mode ("lazy" or default "eager");
	 *                          - alt       The default alternate text;
	 *                          - title     The default title;
	 *                          - caption   The default caption;
	 *                          - small     True to display the image thumbnail (false by default);
	 *                          - relative  True to use a relative path (false by default).
	 * 
	 * @return 	string 	The resulting img tag.
	 */
	public static function display($image, array $attrs = [])
	{
		// check if we have a URL
		if (strpos($image, '/') === false)
		{
			// nope, just the image name...
			$imageName = $image;

			// Detect the type of image to use.
			if (empty($attrs['small']))
			{
				// use original image
				$image = VAPMEDIA_URI . $imageName;
			}
			else
			{
				// use thumbnail image
				$image = VAPMEDIA_SMALL_URI . $imageName;
			}
		}
		else
		{
			// extract base name from URL
			$imageName = basename($image);
		}

		if (!empty($attrs['relative']))
		{
			// remove root from given URL
			$image = str_replace(JUri::root(), '', $image);
		}

		// inject source image
		$attrs['src'] = $image;

		$translator = VAPFactory::getTranslator();
		// translate the specified option
		$media = $translator->translate('media', $imageName);

		// check whether the alternate text has been specified
		if (!empty($media->alt))
		{
			// inject alternate text
			$attrs['alt'] = $media->alt;
		}

		// check whether the title has been specified
		if (!empty($media->title))
		{
			// inject media title
			$attrs['title'] = $media->title;
		}

		// check whether the caption has been specified
		if (!empty($media->caption))
		{
			// inject caption
			$attrs['caption'] = $media->caption;
		}

		if (!empty($attrs['caption']))
		{
			$attrs['data-caption'] = $attrs['caption'];
		}

		// unset attributes that shouldn't be appended into the HTML tag
		unset($attrs['small'], $attrs['caption'], $attrs['relative']);

		$attrs_str = '';

		// build HTML attributes
		foreach ($attrs as $k => $v)
		{
			$attrs_str .= ' ' . $k . '="' . htmlspecialchars($v, ENT_QUOTES, 'UTF-8') . '"';
		}

		return "<img{$attrs_str} />";
	}
}
