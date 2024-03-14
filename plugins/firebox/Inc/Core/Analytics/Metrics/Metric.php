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

namespace FireBox\Core\Analytics\Metrics;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

abstract class Metric
{
	protected $start_date = null;

	protected $end_date = null;

	protected $filters = [];

	protected $offset = null;
	
	protected $limit = null;
	
	protected $type = 'list';

	protected $query_placeholders = [];

	protected $sql_filters = '';

	protected $wpdb;

	protected $options = [];

	protected $is_single_day = false;

	/**
	 * @param    $type   Can be either "list" or "count"
	 */
	public function __construct($start_date = null, $end_date = null, $type = 'list', $options = [])
	{
		global $wpdb;
		$this->wpdb = $wpdb;
		
		$this->start_date = $start_date;
		$this->end_date = $end_date;
		$this->type = $type;
		$this->query_placeholders = [
			$this->start_date,
			$this->end_date
		];
		$this->options = $options;
		$this->is_single_day = \FireBox\Core\Analytics\Helpers\Date::isSingleDay($this->start_date, $this->end_date);
	}

	protected function applyFilters()
	{
		$table_name = 'l';
		
		if (get_class($this) === 'FireBox\Core\Analytics\Metrics\Conversions')
		{
			$table_name = 'bl';
		}
		
		// Campaign
		if (array_key_exists('campaign', $this->filters) && isset($this->filters['campaign']['value']) && is_array($this->filters['campaign']['value']))
		{
			$this->sql_filters .= 'AND ' . $table_name . '.box IN (' . implode(',', array_map('intval', $this->filters['campaign']['value'])) . ')';
		}

		// Country
		if (array_key_exists('country', $this->filters) && isset($this->filters['country']['value']) && is_array($this->filters['country']['value']))
		{
			$this->sql_filters .= 'AND ' . $table_name . '.country IN (' . implode(',', array_fill(0, count($this->filters['country']['value']), array_merge($this->query_placeholders, $this->filters['country']['value']))) . ')';
		}

		// Device
		if (array_key_exists('device', $this->filters) && isset($this->filters['device']['value']) && is_array($this->filters['device']['value']))
		{
			$this->sql_filters .= 'AND ' . $table_name . '.device IN (' . implode(',', array_fill(0, count($this->filters['device']['value']), array_merge($this->query_placeholders, $this->filters['device']['value']))) . ')';
		}

		// Event
		if (array_key_exists('event', $this->filters) && isset($this->filters['event']['value']) && is_array($this->filters['event']['value']))
		{
			$this->sql_filters .= 'AND ld.event IN (' . implode(',', array_fill(0, count($this->filters['event']['value']), array_merge($this->query_placeholders, $this->filters['event']['value']))) . ')';
		}

		$allowed_types = ['contains', 'not_contains', 'equals'];

		// Page
		if (array_key_exists('page', $this->filters) && isset($this->filters['page']['value']) && isset($this->filters['page']['type']) && is_scalar($this->filters['page']['type']) && array_intersect([$this->filters['page']['type']], $allowed_types) && is_array($this->filters['page']['value']))
		{
			$type = $this->filters['page']['type'];
			foreach ($this->filters['page']['value'] as $page)
			{
				if ($type === 'contains')
				{
					$this->sql_filters .= 'AND ' . $table_name . '.page LIKE %s';
					$this->query_placeholders[] = '%' . $page . '%';
				}
				else if ($type === 'not_contains')
				{
					$this->sql_filters .= 'AND ' . $table_name . '.page NOT LIKE %s';
					$this->query_placeholders[] = '%' . $page . '%';
				}
				else if ($type === 'equals')
				{
					$this->sql_filters .= 'AND ' . $table_name . '.page = %s';
					$this->query_placeholders[] = $page;
				}
			}
		}

		// Referrer
		if (array_key_exists('referrer', $this->filters) && isset($this->filters['referrer']['value']) && isset($this->filters['referrer']['type']) && is_scalar($this->filters['referrer']['type']) && array_intersect([$this->filters['referrer']['type']], $allowed_types) && is_array($this->filters['referrer']['value']))
		{
			$type = $this->filters['referrer']['type'];
			foreach ($this->filters['referrer']['value'] as $referrer)
			{
				if ($type === 'contains')
				{
					$this->sql_filters .= 'AND ' . $table_name . '.referrer LIKE %s';
					$this->query_placeholders[] = '%' . $referrer . '%';
				}
				else if ($type === 'not_contains')
				{
					$this->sql_filters .= 'AND ' . $table_name . '.referrer NOT LIKE %s';
					$this->query_placeholders[] = '%' . $referrer . '%';
				}
				else if ($type === 'equals')
				{
					$this->sql_filters .= 'AND ' . $table_name . '.referrer = %s';
					$this->query_placeholders[] = $referrer;
				}
			}
		}
	}

	protected function getJOINs()
	{
		if (get_class($this) !== 'FireBox\Core\Analytics\Metrics\Views')
		{
			return;
		}

		$joins = '';

		if ((array_key_exists('event', $this->filters) && isset($this->filters['event']['value']) && is_array($this->filters['event']['value']) && count($this->filters['event']['value'])) || $this->type === 'events')
		{
			$joins .= "LEFT JOIN {$this->wpdb->prefix}firebox_logs_details as ld
						ON ld.log_id = l.id";
		}
		
		return $joins;
	}

	abstract public function getData();

	protected function getWherePeriod($prefix = 'l')
	{
		if (!$this->start_date || !$this->end_date)
		{
			return;
		}

		return ' AND ' . $prefix . '.date BETWEEN outer_query.start_date AND outer_query.end_date';
	}

	public function onAfterGetData(&$data = [])
	{
		// Fix timezone on dates
		if ($this->isSingleDay() || in_array($this->type, ['popular_view_times']))
		{
			\FireBox\Core\Analytics\Helpers\Date::fixTimezoneInHourlyData($data);
		}

		// Transform country codes to names when the country filter is used
		if ($this->type === 'countries')
		{
			foreach ($data as &$item)
			{
				$item->code = $item->label;
				$item->label = \FPFramework\Helpers\CountriesHelper::getCountryName($item->label) ?? $item->label;
			}
		}

		// Set the device name
		if ($this->type === 'devices')
		{
			foreach ($data as &$item)
			{
				$item->label = fpframework()->_('FPF_' . strtoupper($item->label));
			}
		}

		// Set the event name
		if ($this->type === 'events')
		{
			foreach ($data as &$item)
			{
				$item->label = fpframework()->_('FPF_' . strtoupper($item->label) . '_EVENT');
			}
		}

		// Remove https://www. from URLs
		if ($this->type === 'referrers')
		{
			$regex = '/^(https?:\/\/(www\.)?|www\.)/i';

			foreach ($data as &$item)
			{
				$item->full_label = $item->label;
				$item->label = rtrim(preg_replace($regex, '', $item->label), '/');
			}
		}
		
		// Remove site from URLs
		if ($this->type === 'pages')
		{
			$site_url = get_site_url();

			foreach ($data as &$item)
			{
				$item->full_label = $item->label;
				$item->label = str_replace($site_url, '', $item->label);
			}
		}

		// Make top campaigns linkable
		if ($this->type === 'top_campaign')
		{
			$baseURL = admin_url('admin.php?page=firebox-analytics&campaign=');
			foreach ($data as &$item)
			{
				$item->link = $baseURL . $item->id;
			}
		}
	}

	protected function getLimit()
	{
		if (!is_scalar($this->limit))
		{
			return;
		}

		$this->query_placeholders[] = $this->limit;
		return 'LIMIT %d';
	}

	protected function getOffset()
	{
		if (!is_scalar($this->offset))
		{
			return;
		}

		$this->query_placeholders[] = $this->offset;
		return 'OFFSET %d';
	}

	public function setFilters($filters = [])
	{
		$this->filters = $filters;
	}

	public function setOffset($offset = null)
	{
		$this->offset = (int) $offset;
	}

	public function setLimit($limit = null)
	{
		$this->limit = (int) $limit;
	}

	public function isSingleDay()
	{
		return $this->is_single_day;
	}
}