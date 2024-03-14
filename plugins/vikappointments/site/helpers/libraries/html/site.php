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
 * VikAppointments HTML site helper.
 *
 * @since 1.7
 */
abstract class VAPHtmlSite
{
	/**
	 * Returns the image with the requested flag.
	 *
	 * @param 	string 	$code 	 The county code or a langtag.
	 * @param   array   $config  An array of configuration options.
	 *                           This array can contain a list of key/value pairs where values are boolean.
	 *
	 * @return  string  The HTML image tag.
	 */
	public static function flag($code, $config = array())
	{
		if (preg_match("/^[a-z]{2,3}-([a-z]{2,2})$/i", $code, $match))
		{
			// we have a langtag, find the last match
			$code2 = end($match);
		}
		else
		{
			// use the given code (only 2 chars)
			$code2 = substr($code, 0, 2);
		}

		// build a lookup to adjust the tags when supplied in the WordPress format
		$lookup = array(
			'el' => 'GR',
		);

		if (isset($lookup[$code2]))
		{
			$code2 = $lookup[$code2];
		}

		// find country name
		$country = JHtml::fetch('vaphtml.countries.withcode', $code2);

		if (!$country)
		{
			// build a list of exceptions
			$unitags = array(
				'AA' => 'Arabic Unitag',
			);

			if (isset($unitags[$code2]))
			{
				$country = $unitags[$code2];
			}
			else
			{
				// country not found, return given code
				return $code;
			}
		}
		else
		{
			// use name only
			$country = $country->name;
		}

		$attrs = array();
		$attrs['src'] 	= VAPASSETS_URI . 'css/flags/' . strtolower($code2) . '.png';
		$attrs['title'] = $country . " ($code)";
		$attrs['alt'] 	= $attrs['title'];
		$attrs['style'] = array();
		$attrs['class'] = array('flag');

		if (isset($config['width']))
		{
			$attrs['style'][] = 'width: ' . $config['width'] . (is_int($config['width']) ? 'px' : '') . ';';
		}

		if (isset($config['height']))
		{
			$attrs['style'][] = 'height: ' . $config['height'] . (is_int($config['height']) ? 'px' : '') . ';';
		}

		if (isset($config['class']))
		{
			if (is_array($config['class']))
			{
				$attrs['class'] = array_merge($attrs['class'], $config['class']);
			}
			else
			{
				$attrs['class'][] = $config['class'];
			}
		}

		// make attributes HTML compatible
		foreach ($attrs as $k => &$attr)
		{
			if (is_array($attr))
			{
				$attr = implode(' ', $attr);
			}

			$attr = " {$k}=\"" . htmlspecialchars($attr, ENT_QUOTES, 'UTF-8') . "\"";
		}

		// convert attributes list in HTML string
		$attrs = implode(' ', $attrs);

		// create IMG tag
		return "<img{$attrs}/>";
	}

	/**
	 * Method to sort a column in a grid.
	 *
	 * @param   string  $title          The link title.
	 * @param   string  $order          The order field for the column.
	 * @param   string  $direction      The current direction.
	 * @param   string  $selected       The selected ordering.
	 * @param   string  $task           An optional task override.
	 * @param   string  $new_direction  An optional direction for the new column.
	 * @param   string  $tip            An optional text shown as tooltip title instead of $title.
	 * @param   string  $form           An optional form selector.
	 *
	 * @return  string
	 */
	public static function sort($title, $order, $direction = 'asc', $selected = '', $task = null, $new_direction = 'asc', $tip = '', $form = null)
	{
		if (!$tip && preg_match("/ordering$/i", $order))
		{
			// force "Ordering" as tooltip in order to avoid
			// display the HTML of the icon
			$tip = 'JGRID_HEADING_ORDERING';
		}
		
		// render grid HTML
		$html = JHtml::fetch('grid.sort', $title, $order, $direction, $selected, $task, $new_direction, $tip, $form);

		// turn off tooltip or popover
		$html = preg_replace("/\bhas(?:Tooltip|Popover)\b/", '', $html);

		// replace IcoMoon with FontAwesome
		$html = preg_replace_callback("/<(?:span|i).*?class=\"([a-zA-Z0-9-_\s]+)\".*?>.*?<\/(?:span|i)>/i", function($match)
		{
			if (preg_match("/\bicon-arrow-up-3\b/", end($match)))
			{
				$icon = 'sort-up';
			}
			else
			{
				$icon = 'sort-down';
			}

			return '<i class="fas fa-' . $icon . '"></i>';
		}, $html);

		return $html;
	}
}
