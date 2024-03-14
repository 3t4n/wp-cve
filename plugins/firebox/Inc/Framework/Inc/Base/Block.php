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

abstract class Block
{
	/**
	 * Whether the block is loaded from a file or not.
	 * 
	 * @var  bool
	 */
	protected $file = false;
	
	/**
	 * Block namespace.
	 * 
	 * @var  string
	 */
	protected $namespace;
	
	/**
	 * Block identifier.
	 * 
	 * @var  string
	 */
	protected $name;
	
	/**
	 * Front-end style.
	 * 
	 * @var  string|array
	 */
	protected $style;
	
	/**
	 * Front-end script.
	 * 
	 * @var  string
	 */
	protected $script;
	
	/**
	 * Editor style.
	 * 
	 * @var  string|array
	 */
	protected $editor_style;
	
	/**
	 * Editor script.
	 * 
	 * @var  string
	 */
	protected $editor_script;
	
	/**
	 * Category.
	 * 
	 * @var  string
	 */
	protected $category;
	
	/**
	 * Keywords that will be used in search results.
	 * 
	 * @var  array
	 */
	protected $keywords;
	
	public function __construct()
	{
		// front-end scripts
		add_action('wp_enqueue_scripts', [$this, 'public_assets'], 0);

		// front-end and back-end scripts
		if (method_exists($this, 'enqueue_block_assets'))
		{
			add_action('enqueue_block_assets', [$this, 'enqueue_block_assets'], 0);
		}
		
		// back-end scripts only
		add_action('enqueue_block_editor_assets', [$this, 'assets'], 10);
	}
	
	/**
	 * Register the block.
	 * 
	 * @return  void
	 */
	public function register()
	{
		if ($this->getNamespace() === 'firebox')
		{
			$path = $this->getBlockSourceDir($this->getName());

			$settings = [];

			if (!is_admin() && method_exists($this, 'render_callback'))
			{
				$settings['render_callback'] = [$this, 'render_callback'];
			}
			
			register_block_type_from_metadata($path, $settings);
			return;
		}
		
		register_block_type(
			$this->getNamespace() . '/' . $this->getName(),
			$this->getPayload()
		);
	}

	/**
	 * Returns the block payload.
	 * 
	 * @return  array
	 */
	private function getPayload()
	{
		$payload = [];

		if ($this->style)
		{
			$payload['style'] = $this->style;
		}

		if ($this->script)
		{
			$payload['script'] = $this->script;
		}

		if ($this->editor_style)
		{
			$payload['editor_style'] = $this->editor_style;
		}

		if ($this->editor_script)
		{
			$payload['editor_script'] = $this->editor_script;
		}

		if (method_exists($this, 'getAttributes'))
		{
			$payload['attributes'] = $this->getAttributes();
		}

		if ($this->category)
		{
			$payload['category'] = $this->category;
		}

		if ($this->keywords)
		{
			$payload['keywords'] = $this->keywords;
		}

		if (!is_admin() && method_exists($this, 'render_callback'))
		{
			$payload['render_callback'] = [$this, 'render_callback'];
		}
		
		return $payload;
	}

	/**
	 * Returns the block namespace.
	 * 
	 * @return  string
	 */
	protected function getNamespace()
	{
		return $this->namespace;
	}

	/**
	 * Returns the block name.
	 * 
	 * @return  string
	 */
	protected function getName()
	{
		return $this->name;
	}

	/**
	 * Overriden by child classes to register their public (front-end) assets.
	 * 
	 * @return  mixed
	 */
	public function public_assets()
	{
		return true;
	}

	/**
	 * Overriden by child classes to register their assets.
	 * 
	 * @return  mixed
	 */
	public function assets()
	{
		return true;
	}
}