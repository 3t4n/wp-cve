<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Base;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Libs\Registry;

class Renderer
{
	/**
	 * Data passed to the view
	 * 
	 * @var  mixed
	 */
	private $data;

	private $folder;

	private $path_to_layout = '';

	public function __construct($folder = '')
	{
		$this->folder = $folder;
	}

	public function __get($path_to_layout = '')
	{
		$this->path_to_layout = $path_to_layout;
		return $this;
	}
	
	/**
	 * Renders a layout to the screen
	 * 
	 * @param   string	  $layout
	 * @param   array	  $data
	 * @param   boolean   $return
	 * 
	 * @return  void
	 */
	public function render($layout, $data = [], $return = false)
	{
		$this->data = new Registry($data);

		$filename = $this->folder . $this->path_to_layout . '/' . $layout . '.php';

		if (!file_exists($filename))
		{
			return;
		}
		
		if ($return)
		{
			ob_start();
			include $filename;
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}

		include $filename;
	}

}