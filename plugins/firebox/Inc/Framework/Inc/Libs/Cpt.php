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

namespace FPFramework\Libs;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Libs\Registry;

class Cpt
{
	/**
	 * Arguments
	 * 
	 * @var  object
	 */
	private $args;
	
	public function __construct($args = null)
	{
		if (!$args)
		{
			return;
		}
		
		$this->args = new Registry($args);

		if (!empty($this->args->get('extra_columns')))
		{
			// add extra columns to custom post type list page
			if ($this->args->get('extra_columns'))
			{
				add_filter('manage_' . $this->args->get('name') . '_posts_columns', [$this, 'addExtraColumns']);
			}

			// set sortable columns
			if (method_exists($this, 'setSortableColumns'))
			{
				add_filter('manage_edit-' . $this->args->get('name') . '_sortable_columns', [$this, 'setSortableColumns']);
			}

			// add values to extra columns
			if (method_exists($this, 'addExtraColumnsValues'))
			{
				add_action('manage_' . $this->args->get('name') . '_posts_custom_column' , [$this, 'addExtraColumnsValues'], 10, 2);
			}
		}
	}

	/**
	 * Set whether the CPT can run.
	 * 
	 * @return  void
	 */
	protected function canRun()
	{
		return true;
	}

	/**
	 * Registers the Custom Post Type
	 * 
	 * @return  void
	 */
	public function register()
	{
		if (!$this->canRun())
		{
			return;
		}
		
		$singular = $this->args->get('singular');
		$plural = $this->args->get('plural');

        // Default labels.
        $labels = [
            'name'               => sprintf( fpframework()->_('FPF_%S'), $plural ),
            'singular_name'      => sprintf( fpframework()->_('FPF_%S'), $singular ),
            'menu_name'          => sprintf( fpframework()->_('FPF_%S'), $plural ),
            'all_items'          => sprintf( fpframework()->_('FPF_%S'), $plural ),
            'add_new'            => fpframework()->_('FPF_ADD_NEW'),
            'add_new_item'       => sprintf( fpframework()->_('FPF_ADD_NEW_%S'), $singular ),
            'edit_item'          => sprintf( fpframework()->_('FPF_EDIT_%S'), $singular ),
            'new_item'           => sprintf( fpframework()->_('FPF_NEW_%S'), $singular ),
            'view_item'          => sprintf( fpframework()->_('FPF_VIEW_%S'), $singular ),
            'search_items'       => sprintf( fpframework()->_('FPF_SEARCH_%S'), $plural ),
            'not_found'          => sprintf( fpframework()->_('FPF_NO_%S_FOUND'), $plural ),
            'not_found_in_trash' => sprintf( fpframework()->_('FPF_NO_%S_FOUND_IN_TRASH'), $plural ),
            'parent_item_colon'  => sprintf( fpframework()->_('FPF_PARENT_%S'), $singular )
		];
		
		$payload = [
			'labels' => $labels
		];

		$payload['show_in_rest'] = $this->args->get('show_in_rest', true);
		$payload['public'] = $this->args->get('public', true);
		$payload['publicly_queryable'] = $this->args->get('publicly_queryable', true);
		$payload['show_ui'] = $this->args->get('show_ui', true);
		$payload['exclude_from_search'] = $this->args->get('exclude_from_search', false);
		$payload['show_in_nav_menus'] = $this->args->get('show_in_nav_menus', true);
		$payload['has_archive'] = $this->args->get('has_archive', true);
		
		if (!$this->args->get('is_public'))
		{
			$payload['public'] = false;
			$payload['publicly_queryable'] = false;
			$payload['show_ui'] = true;
			$payload['exclude_from_search'] = true;
			$payload['show_in_nav_menus'] = false;
			$payload['has_archive'] = false;
		}

		$payload['show_in_menu'] = $this->args->get('show_in_menu', true);
		
		$payload['rewrite'] = $this->args->get('rewrite', true) ? ['slug' => $this->args->get('slug')] : false;

		if (!empty($this->args->get('supports')))
		{
			$payload['supports'] = $this->args->get('supports');
		}

		if (!empty($this->args->get('capability_type')))
		{
			$payload['capability_type'] = $this->args->get('capability_type');
		}

		if (!empty($this->args->get('hierarchical')))
		{
			$payload['hierarchical'] = $this->args->get('hierarchical');
		}

        // Default options.
        $defaults = [
			'label' => $singular,
            'labels' => $labels,
			'public' => true,
			'map_meta_cap' => true,
			'hierarchical' => $this->args->get('hierarchical', false),
            'rewrite' => [
                'slug' => $this->args->get('slug'),
			]
		];

		$payload = array_replace_recursive( $defaults, $payload );
		
		// register custom post type
		register_post_type($this->args->get('name'), $payload);
		
		// register taxonomies
		$this->registerTaxonomies($this->args);
	}

	/**
	 * Register taxonomies
	 * 
	 * @param   array  $args
	 * 
	 * @return  void
	 */
	private function registerTaxonomies($args)
	{
		if (!$args)
		{
			return;
		}
		
		if (!$args->get('custom_taxonomies'))
		{
			return;
		}

		$cpt_slug = $args->get('slug');

		$custom_taxonomies = $args->get('custom_taxonomies');
		if (!$custom_taxonomies && !is_array($custom_taxonomies) && !count($custom_taxonomies))
		{
			return;
		}

		foreach ($custom_taxonomies as $tax => $data)
		{
			$singular = $data->singular;
			$plural = $data->plural;
			
			$cap_slug = $cpt_slug . '_' . $tax;

			$labels = [
				'name'                  => _x( $plural, 'taxonomy general name', 'fpf-framework' ),
				'singular_name'         => _x( $singular, 'taxonomy singular name', 'fpf-framework' ),
				'search_items'          => __( 'Search ' . $plural, 'fpf-framework' ),
				'all_items'             => __( 'All ' . $plural, 'fpf-framework' ),
				'parent_item'           => __( 'Parent ' . $singular, 'fpf-framework' ),
				'parent_item_colon'     => __( 'Parent ' . $singular . ':', 'fpf-framework' ),
				'edit_item'             => __( 'Edit ' . $singular, 'fpf-framework' ),
				'update_item'           => __( 'Update ' . $singular, 'fpf-framework' ),
				'add_new_item'          => __( 'Add New ' . $singular, 'fpf-framework' ),
				'new_item_name'         => __( 'New ' . $singular . ' Name', 'fpf-framework' ),
				'menu_name'             => __( $plural, 'fpf-framework' ),
				'choose_from_most_used' => __( 'Choose from most used ' . $tax, 'fpf-framework' ),
			];

			$show_admin_column = isset($data->show_admin_column) ? $data->show_admin_column : true;

			$tax_args = [
				'labels'        => $labels,
				'hierarchical' => true,
				'show_admin_column' => $show_admin_column,
				'rewrite'      => [
					'slug' => $cpt_slug . '/' . $tax,
					'with_front' => false
				],
				'capabilities' => [
					'manage_terms' => 'manage_' . $cap_slug . '_terms',
					'edit_terms' => 'edit_' . $cap_slug . '_terms',
					'assign_terms' => 'assign_' . $cap_slug . '_terms',
					'delete_terms' => 'delete_' . $cap_slug . '_terms'
				]
			];

			register_taxonomy( $cap_slug, $this->args->get('name'), $tax_args );
			register_taxonomy_for_object_type( $cap_slug, $this->args->get('name') );
		}
	}

	/**
	 * Adds exta columns to the Custom Post Type List View
	 * 
	 * @param   array  $columns
	 * 
	 * @return  array
	 */
	public function addExtraColumns($columns)
	{
		$columns_with_index = [];

		foreach ($this->args->get('extra_columns') as $key => $value)
		{
			$value = !is_string($value) ? (array) $value : $value;
			
			$label = isset($value['label']) ? $value['label'] : $value;

			// if we were given an index, store it with the column key and re-order it later
			if (isset($value['index']))
			{
				$columns_with_index[$key] = [
					'index' => $value['index'],
					'label' => $label
				];
			}
			
			$columns[$key] = $label;
		}

		// get columns keys, used in the reordering process
		$columns_keys = array_keys($columns);

		// check and re-order columns on given index
		if ($columns_with_index)
		{
			foreach ($columns_with_index as $col => $data)
			{
				$columns = \FPFramework\Helpers\ArrayHelper::insertAfter($columns, $columns_keys[$data['index']], $col, $data['label']);
			}
		}
	
		return $columns;
	}
}