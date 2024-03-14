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

namespace FireBox\Core\Blocks;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

abstract class Block extends \FPFramework\Base\Block
{
	/**
	 * Block namespace.
	 * 
	 * @var  string
	 */
	protected $namespace = 'firebox';

	protected function getBlockSourceDir($block = '')
	{
		$ds = DIRECTORY_SEPARATOR;

		return implode($ds, [rtrim(FBOX_PLUGIN_DIR, $ds), 'media', 'admin', 'js', 'blocks', $block, 'block.json']);
	}
}