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

namespace FPFramework\Base\Ajax;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Helpers\SearchDropdownHelper;

class SearchDropdownAjax
{
	public function __construct()
	{
		// Search Dropdown AJAX
		add_action('wp_ajax_fpf_searchdropdown_get_data', [$this, 'fpf_get_data']);
		add_action('wp_ajax_nopriv_fpf_searchdropdown_get_data', [$this, 'fpf_get_data']);

		// Search Dropdown lazy load results AJAX
		add_action('wp_ajax_fpf_searchdropdown_lazyload_results', [$this, 'fpf_lazyload_results']);
		add_action('wp_ajax_nopriv_fpf_searchdropdown_lazyload_results', [$this, 'fpf_lazyload_results']);

	}
	
	/**
	 * Gets the search data via AJAX request
	 * 
	 * @return  array
	 */
	public function fpf_get_data()
	{
		$nonce = isset($_GET['nonce']) ? strval($_GET['nonce']) : null;
		
        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fpf-pa-search-nonce'))
        {
            return [];
        }

		$path = isset($_GET['path']) ? sanitize_text_field($_GET['path']) : '';
		if (!$path)
		{
			return [];
		}

		if (!current_user_can('manage_options'))
		{
			return [];
		}

		$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : null;

		$ids = isset($_GET['ids']) ? wp_unslash($_GET['ids']) : [];
		if (!empty($ids))
		{
			$ids = explode(',', $ids);
			$ids = array_map('esc_attr', $ids);
		}
		
		$hide_ids = isset($_GET['hide_ids']) ? (bool) sanitize_key($_GET['hide_ids']) : true;
		$hide_flags = isset($_GET['hide_flags']) ? (bool) sanitize_key($_GET['hide_flags']) : true;

		$search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
		
		if (!empty($path) && !empty($search) && !is_null($post_id)) {
			// get class
			if (!$helper = SearchDropdownHelper::getHelperClass($path))
			{
				return [];
			}

			// ensure getSearchItems method exists
			if (!method_exists($helper, 'getSearchItems'))
			{
				return [];
			}

			// filter ids
			$ids = apply_filters('fpframework/fields/searchdropdown/filter_get_search_items_ids', $ids, $post_id);

			$parsable_data = $helper->getSearchItems($search, $ids);

			$html = '<ul>';
			if (count($parsable_data))
			{
				$html .= $this->getResults([
					'data' => $parsable_data,
					'hide_ids' => $hide_ids,
					'hide_flags' => $hide_flags
				]);
			}
			else
			{
				$html .= '<li class="skip">' . esc_html(fpframework()->_('FPF_NO_ITEMS_FOUND')) . '</li>';
			}
			$html .= '</ul>';
			
			echo wp_json_encode([
				'html' => (!empty($html)) ? wp_json_encode($html) : ''
			]);
			wp_die();
		}

	}

	/**
	 * Used to lazyload next set of results
	 * 
	 * @return  string
	 */
	public function fpf_lazyload_results()
	{
		$nonce = isset($_GET['nonce']) ? strval($_GET['nonce']) : null;

        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fpf-pa-search-nonce'))
        {
            return [];
        }

		$path = isset($_GET['path']) ? sanitize_text_field($_GET['path']) : '';
		if (!$path)
		{
			return [];
		}

		if (!current_user_can('manage_options'))
		{
			return [];
		}

		$offset = isset($_GET['offset']) ? (int) absint($_GET['offset']) : 0;

		$selected_items = isset($_GET['ids']) && !empty($_GET['ids']) ? sanitize_text_field($_GET['ids']) : [];
		if ($selected_items)
		{
			$selected_items = explode(',', $selected_items);
		}

		$hide_ids = isset($_GET['hide_ids']) ? (bool) sanitize_key($_GET['hide_ids']) : true;
		$hide_flags = isset($_GET['hide_flags']) ? (bool) sanitize_key($_GET['hide_flags']) : true;

		if (!empty($path) && !empty($offset)) {
			// get class
			if (!$helper = SearchDropdownHelper::getHelperClass($path))
			{
				return [];
			}

			// ensure getItems method exists
			if (!method_exists($helper, 'getItems'))
			{
				return [];
			}

			$parsable_data = $helper->getItems($offset);

			$html = '';
			
			if (count($parsable_data))
			{
				$html = $this->getResults([
					'data' => $parsable_data,
					'selected_items' => $selected_items,
					'hide_ids' => $hide_ids,
					'hide_flags' => $hide_flags
				]);
			}
			
			echo wp_json_encode([
				'html' => (!empty($html)) ? $html : ''
			]);
			wp_die();
		}
	}

	/**
	 * Returns the results found.
	 * 
	 * @param   array   $data
	 * @param   string  $path
	 * 
	 * @return  string
	 */
	private function getResults($payload)
	{
		if (!isset($payload['data']))
		{
			return;
		}
		$data = $payload['data'];

		if (!is_array($data))
		{
			return;
		}

		$selected_items = isset($payload['selected_items']) ? $payload['selected_items'] : [];
		$hide_ids = isset($payload['hide_ids']) ? $payload['hide_ids'] : true;
		$hide_flags = isset($payload['hide_flags']) ? $payload['hide_flags'] : true;

		$html = '';
		
        $allowed_img_tags = [
            'img' => [
                'src' => true,
                'alt' => true,
                'style' => true
            ]
		];

		foreach ($data as $item)
		{
			$item = (array) $item;
			$item_class = (in_array($item['id'], $selected_items)) ? 'is-disabled' : '';
			$lang =  isset($item['lang']) ? $item['lang'] : '';

			$lang_img = !$hide_flags && !empty($lang) ? \FPFramework\Helpers\WPHelper::getWPMLFlagUrlFromCode($lang) : '';

			$ID = !$hide_ids ? $item['id'] : '';

			$html .= '<li data-id="' . esc_attr($item['id']) . '" class="' . esc_attr($item_class) . '"><span class="title">' . esc_html($item['title']) . '</span><span class="meta"><span class="text fpf-badge">' . esc_html($ID) . '</span>' . wp_kses($lang_img, $allowed_img_tags) . '</span></li>';
		}

		return $html;
	}
}