<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Blocks\Generic;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Video extends \FireBox\Core\Blocks\Block
{
	/**
	 * Block identifier.
	 * 
	 * @var  string
	 */
	protected $name = 'video';

	/**
	 * Callback
	 * 
	 * @param   array   $attributes
	 * @param   string  $content
	 * 
	 * @return  mixed
	 */
	public function render_callback($attributes, $content)
	{
		if (!class_exists('\FPFramework\Base\Widgets\Helper'))
		{
			return;
		}

		$payload = [
			'videoUrl' => $attributes['videoUrl'],

			// Video
			'padding' => $attributes['padding'],
			'margin' => $attributes['margin'],
			
			// Player
			'autoplay' => $attributes['autoplay'],
			'autopause' => $attributes['autopause'],
			'mute' => $attributes['mute'],
			'loop' => $attributes['loop'],
			'startTime' => $attributes['startTime'],
			'endTime' => $attributes['endTime'],
			'branding' => $attributes['branding'],
			'controls' => $attributes['controls'],
			'privacyMode' => $attributes['privacyMode'],
			
			// Cover Image
			'coverImageType' => $attributes['coverImageType'],
			'coverImage' => $attributes['coverImage'],

			// Border
			'borderColor' => $attributes['borderColor'],
			'borderWidth' => $attributes['borderWidth'],
			'borderStyle' => $attributes['borderStyle'],
			'borderRadius' => $attributes['borderRadius'],
			'borderRadius' => $attributes['borderRadius'],

			// Colors
			'backgroundColor' => $attributes['backgroundColor'],

			// Box Shadow
			'boxShadow' => $attributes['boxShadow'],
		];
		
		return \FPFramework\Base\Widgets\Helper::render('Video', $payload);
	}
	
	/**
	 * Registers assets both on front-end and back-end.
	 * 
	 * @return  void
	 */
	public function assets()
	{
		\FPFramework\Base\Widgets\Video::register_assets();
	}
}