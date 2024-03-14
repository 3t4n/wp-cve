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

namespace FireBox\Core\FB;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class BoxBlocksParser
{
    public function __construct()
    {
		add_filter('render_block', [$this, 'maybe_load_block_extension_script'], 10, 2);

		add_filter('render_block', [$this, 'modify_block_output'], 10, 2);

		// DEPRECATED-START - Remove on 2024-01-01
		// filter the post content by setting supported block attributes
		add_filter('render_block', [$this, 'deprecated_modify_block_output'], 10, 2);
		// DEPRECATED-END
	}

	public function maybe_load_block_extension_script($block_content, $block)
	{
		if (isset($block['attrs']['dataFBoxOnClick']) && in_array($block['attrs']['dataFBoxOnClick'], ['copy_clipboard', 'download_file']))
		{
			wp_enqueue_style(
				'firebox-block-extensions',
				FBOX_MEDIA_PUBLIC_URL . 'css/block_extensions.css',
				[],
				FBOX_VERSION
			);
			wp_enqueue_script(
				'firebox-block-extensions',
				FBOX_MEDIA_PUBLIC_URL . 'js/block_extensions.js',
				[],
				FBOX_VERSION,
				true
			);
		}

		return $block_content;
	}

	/**
	 * Filters the block output by appending out custom data attributes
	 * on our supported blocks
	 * 
	 * @param   string  $block_content
	 * @param   array   $block
	 * 
	 * @return  string
	 */
	public function modify_block_output($block_content, $block)
	{
		$atts = $this->getBlockAttributes($block);
		if (!$atts['utmParamsEnabled'])
		{
			return $block_content;
		}

		if (!isset($block['blockName']))
		{
			return $block_content;
		}

		$blockName = $block['blockName'];

		$parsable_blocks = [
			'firebox/button',
			'firebox/image',
			'core/button',
			'core/image'
		];

		if (!in_array($blockName, $parsable_blocks))
		{
			return $block_content;
		}

		// Check if href attribute contains ? then prefix is & else prefix is ?, use regex
		$utmPrefix = preg_match('/href="([^"]*)\?/', $block_content) ? '&' : '?';

		// Find utm_content
		$utmContent = '';
		if ($blockName === 'core/button')
		{
			$utmContent = trim(wp_strip_all_tags($block['innerHTML']));
		}
		else if ($blockName === 'firebox/button')
		{
			$utmContent = isset($block['attrs']['text']) ? trim(wp_strip_all_tags($block['attrs']['text'])) : '';
		}
		else if ($blockName === 'core/image')
		{
			// Get the src attribute value from $block['innerHTML'] using regex
			$utmContent = preg_match('/src="([^"]*)"/', $block['innerHTML'], $matches) ? $matches[1] : '';
		}
		else if ($blockName === 'firebox/image')
		{
			$utmContent = isset($block['attrs']['image']['desktop']['url']) ? $block['attrs']['image']['desktop']['url'] : '';
		}
		
        // Get the UTM Parameters
		$utmParams = $utmPrefix . 'utm_source=' . get_post_type() . '&utm_medium=' . $blockName . '&utm_campaign=' . get_the_title() . '&utm_content=' . $utmContent;

		// Find the href attribute and append to it the utm params
		$block_content = preg_replace('/href="([^"]*)"/', 'href="$1' . $utmParams . '"', $block_content);

		return $block_content;
	}

	// DEPRECATED-START - Remove on 2025-01-01
	/**
	 * Filters the block output by appending out custom data attributes
	 * on our supported blocks
	 * 
	 * @param   string  $block_content
	 * @param   array   $block
	 * 
	 * @return  string
	 */
	public function deprecated_modify_block_output($block_content, $block)
	{
		if (!isset($block['blockName']))
		{
			return $block_content;
		}

		$blockName = $block['blockName'];

		$parsable_blocks = [
			'firebox/buttons',
			'firebox/image',
			'core/buttons',
			'core/image'
		];
		
		if (!in_array($blockName, $parsable_blocks))
		{
			return $block_content;
		}

		/**
		 * Handle Core Buttons Block
		 */
		if (in_array($blockName, ['core/buttons', 'firebox/buttons']))
		{
			$buttons = isset($block['innerBlocks']) ? $block['innerBlocks'] : [];
			if (!$buttons)
			{
				return $block_content;
			}

			foreach ($buttons as $button)
			{
				$block_content = $this->replaceBlockAttributes('a', $button, $block_content);
			}
		}
		/**
		 * Handle Core Image Block
		 */
		else if (in_array($blockName, ['core/image', 'firebox/image']))
		{
			$new_block_html = $block['innerHTML'];

			// check if we have an anchor element and add attributes to this elem.
			$anchorExists = substr_count($new_block_html, '<a ');

			$element = $anchorExists ? 'a' : 'figure';

			$block_content = $this->replaceBlockAttributes($element, $block, $block_content);
		}

		return $block_content;
	}

	/**
	 * Adds the data attributes and class($atts) to the given element($element) within the block content($block_content)
	 * 
	 * @param   string  $element
	 * @param   array   $block
	 * @param   string  $block_element
	 * 
	 * @return  string
	 */
	private function replaceBlockAttributes($element, $block, $block_content)
	{
		$atts = $this->getBlockAttributes($block);

		if (!$atts['enabled'])
		{
			return $block_content;
		}

		$new_block_html = $block['innerHTML'];
		
		// box
		if (!is_null($atts['box']) && $atts['box'] !== 'none')
		{
			if ($atts['box'] !== 'current')
			{
				$new_block_html = str_replace('<' . $element . ' ', '<' . $element . ' data-fbox="' . esc_attr($atts['box']) . '" ', $new_block_html);
			}

			// cmd
			if ($atts['cmd'])
			{
				$new_block_html = str_replace('<' . $element . ' ', '<' . $element . ' data-fbox-cmd="' . esc_attr($atts['cmd']) . '" ', $new_block_html);
			}
	
			// prevent default
			$new_block_html = str_replace('<' . $element . ' ', '<' . $element . ' data-fbox-prevent="1" ', $new_block_html);
		}

		// class
		if (isset($atts['class']) && !empty($atts['class']))
		{
			$new_block_html = preg_replace('/<' . $element . '(.*)class="/', "<" . $element . "$1class=\"" . esc_attr($atts['class']) . ' ', $new_block_html);
		}

		return str_replace($block['innerHTML'], $new_block_html, $block_content);
	}
	// DEPRECATED-END

	/**
	 * Retrieves the block attributes
	 * 
	 * @param   array  $block
	 * 
	 * @return  array
	 */
	private function getBlockAttributes($block)
	{
		$atts = [
			'utmParamsEnabled' => false
		];

		if (isset($block['attrs']['dataFBoxOnClickURLAddParameters']) && $block['attrs']['dataFBoxOnClickURLAddParameters'])
		{
			$atts['utmParamsEnabled'] = true;
		}

		// DEPRECATED-START
		// FireBox settings
		$atts['enabled'] = isset($block['attrs']['dataFBoxEnabled']) && $block['attrs']['dataFBoxEnabled'];

		if (!$atts['enabled'])
		{
			return $atts;
		}
		
		$atts['box'] = isset($block['attrs']['dataFBox']) ? $block['attrs']['dataFBox'] : 'current';
		$atts['cmd'] = isset($block['attrs']['dataFBoxCmd']) ? $block['attrs']['dataFBoxCmd'] : 'open';
		$atts['class'] = isset($block['attrs']['dataFBoxClass']) ? $block['attrs']['dataFBoxClass'] : '';
		// DEPRECATED-END

		return $atts;
	}
}