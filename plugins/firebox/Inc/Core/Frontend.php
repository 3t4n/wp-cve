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

namespace FireBox\Core;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Frontend
{
    public function __construct()
    {
		// ensure we run only on front-end
		if (is_admin())
		{
			return;
		}

		// Prepare Gutenberg Blocks that contain attributes by FireBox
		new \FireBox\Core\FB\BoxBlocksParser();

		/**
		 * Event that runs before any rendering of popups.
		 */
		do_action('firebox/boxes/before_render');

		add_action('template_redirect', [$this, 'render']);

		\FireBox\Core\Helpers\Actions::run();

		/**
		 * Event that runs after popups have been rendered.
		 */
		do_action('firebox/boxes/after_render');
	}
	
	/**
	 * Renders all boxes on front-end
	 * 
	 * @return  void
	 */
	public function render()
	{
		if ($this->checkForBoxPreview())
		{
			return;
		}

		// Don't render the popup if previewing with third party page builders
		if (!$this->canRenderInEditors())
		{
			return;
		}

		// increment session counter
		\FPFramework\Libs\Functions::incrementSession();

		/**
		 * Adds all boxes at the end of page.
		 */
		firebox()->boxes->render();
	}

	/**
	 * Don't render when previewing with third party page builders.
	 * 
	 * @return  boolean
	 */
	private function canRenderInEditors()
	{
		// Don't render the popup if previewing with Elementor
		if (class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->preview->is_preview_mode())
		{
			return;
		}

		// Don't render the popup if previewing with Beaver Builder
		if (class_exists('\FLBuilderModel') && method_exists('\FLBuilderModel', 'is_builder_active') && \FLBuilderModel::is_builder_active())
		{
			return;
		}
		
		return true;
	}

	/**
	 * Check if we are previewing a box and do not show other boxes.
	 * 
	 * @return  boolean
	 */
	private function checkForBoxPreview()
	{
		$previewer = new Previewer();
		return $previewer->init();
	}
}