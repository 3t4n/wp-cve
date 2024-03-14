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

namespace FPFramework\Admin\Includes;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Helpers\FormHelper;

class MetaboxManager
{
	/**
	 * Nonce verification value
	 * 
	 * @var  string
	 */
	const nonce_value = 'fpframework_metaboxes_save_data';
	
	/**
	 * The meta option prefix used to store all meta data
	 * 
	 * @var  string
	 */
	public static $fields_prefix = 'fpframework_fields';

	/**
	 * All metaboxes
	 * 
	 * @var  array
	 */
	protected $metaboxes = [];

	public function init()
	{
		// filter metaboxes
		$this->metaboxes = apply_filters('fpframework/metaboxes_filter', $this->metaboxes);

		// add meta boxes
		add_action('add_meta_boxes', [$this, 'initMetaBoxes']);
		
		// save meta boxes data
		add_action('save_post', [$this, 'saveMetaboxesData']);
	}

	/**
	 * Gets the settings from the metabox
	 * 
	 * @param   string   $cpt
	 * 
	 * @return  array
	 */
	private function getMetaboxSettingsByCPT($cpt)
	{
		$settings = [];

		foreach ($this->metaboxes as $key => $data)
		{
			$path = $data['path'];

			$cpt = strtolower($cpt);
			
			if (!in_array($cpt, array_map('strtolower', $data['names'])))
			{
				continue;
			}
		
			$className = $path . ucfirst($cpt);
			
			if (!class_exists($className))
			{
				continue;
			}

			$instance = new $className();
			$settings = $instance->getSettings();
			break;
		}
		
		return $settings;
	}

	/**
	 * Save Metaboxes data
	 * 
	 * @param   string  $post_id
	 * 
	 * @return  void
	 */
	public function saveMetaboxesData($post_id)
	{
		if (!isset($_POST['_fpframework_metabox_nonce']) || (isset($_POST['_fpframework_metabox_nonce']) && !wp_verify_nonce(sanitize_text_field($_POST['_fpframework_metabox_nonce']), self::nonce_value)))
		{
			return;
		}

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		{
			return;
		}

		if ($parent_id = wp_is_post_revision($post_id))
		{
			$post_id = $parent_id;
		}
		
		if (!$post_type = get_post_type($post_id))
		{
			return;
		}

		$fields = isset($_POST[self::$fields_prefix]) ? $_POST[self::$fields_prefix] : [];

		// if no fields are passed, do not proceed with saving
		// if we do not return here, when trashing an item,
		// it will overwrite and delete the meta options
		if (empty($fields))
		{
			return;
		}

		// Get the form settings of the CPT
		$form_settings = $this->getMetaboxSettingsByCPT($post_type);

		// Filters the fields values
		FormHelper::filterFields($fields, $form_settings);

		$fields = apply_filters('fpframework/metabox/after_filter', $fields, $post_id, $post_type);

		// saves the post meta field values
		update_post_meta($post_id, 'fpframework_meta_settings', $fields);
	}

	/**
	 * Initializes all found metaboxes
	 * 
	 * @return  void
	 */
	public function initMetaBoxes()
	{
		if (!$this->canRunMetaboxes())
		{
			return false;
		}

		foreach ($this->metaboxes as $meta)
		{
			if (!isset($meta['names']) || !count($meta['names']))
			{
				continue;
			}
			
			foreach ($meta['names'] as $mb)
			{
				$metabox = $meta['path'] . ucfirst(strtolower($mb));

				if (!class_exists($metabox))
				{
					continue;
				}
				
				// initialize the Metabox class
				$instance = new $metabox();

				// add the metabox
				$this->addMetabox($instance);
			}
		}
	}

	/**
	 * Adds the metabox
	 * 
	 * @param   object  $instance
	 * 
	 * @return  void
	 */
	private function addMetabox($instance)
	{
		add_meta_box($instance->slug,
					 fpframework()->_($instance->title),
					 [$instance, $instance->callback],
					 $instance->screen,
					 $instance->context,
					 $instance->priority);
	}

	/**
	 * Checks if we have any metaboxes to run
	 * 
	 * @return  boolean
	 */
	private function canRunMetaboxes()
	{
		if (!count($this->metaboxes))
		{
			return false;
		}

		return true;
	}
}