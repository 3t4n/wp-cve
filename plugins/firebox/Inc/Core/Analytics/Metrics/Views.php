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

class Views extends Metric
{
	public function getData()
	{
		$this->applyFilters();

		$sql = "SELECT
				{$this->getSelect()}
			FROM
				{$this->wpdb->prefix}firebox_logs as l
				{$this->getJOINs()}
			CROSS JOIN (
				SELECT '%s' AS start_date, '%s' AS end_date
			) AS outer_query
			WHERE
				1
				{$this->getWherePeriod()}
				{$this->sql_filters}
				{$this->getWhere()}
			{$this->getGroupBy()}
			{$this->getOrderBy()}
			{$this->getLimit()}
			{$this->getOffset()}";
		
		$sql = $this->wpdb->prepare($sql, $this->query_placeholders); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$data = [];

		if ($this->type === 'count')
		{
			$column_value = $this->wpdb->get_col($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$data = array_sum(array_map('intval', $column_value));
		}
		else
		{
			$data = $this->wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		}

		return $data;
	}

	private function getSelect()
	{
		$select = '';
		
		switch ($this->type)
		{
			case 'top_campaign':
				$select = 'l.box as id, (select p.post_title from ' . $this->wpdb->prefix . 'posts as p WHERE p.ID = l.box) as label, COUNT(*) as total';
				break;
			
			case 'popular_view_times':
				$select = 'CONCAT(DATE_FORMAT(l.date, \'%H\'), \':00\') as label, COUNT(*) as total';
				break;
			
			case 'countries':
				$select = 'l.country as label, COUNT(*) as total';
				break;
			
			case 'referrers':
				$select = 'l.referrer as label, COUNT(*) as total';
				break;
			
			case 'devices':
				$select = 'l.device as label, COUNT(*) as total';
				break;
			
			case 'events':
				$filter = '';
				
				if (array_key_exists('campaign', $this->filters) && isset($this->filters['campaign']['value']) && is_array($this->filters['campaign']['value']))
				{
					$filter .= 'AND ll.box IN (' . implode(',', array_map('intval', $this->filters['campaign']['value'])) . ')';
				}
				
				$select = '
				IF(ld.event IS NOT NULL, ld.event, \'open\') AS label,
				IF(ld.event IS NOT NULL, COUNT(l.id), (
					SELECT COUNT(ll.id)
					FROM wp_firebox_logs AS ll
					WHERE (ll.date >= outer_query.start_date
					AND ll.date <= outer_query.end_date)
					' . $filter . '
				)) AS total
				';
				break;
			
			case 'pages':
				$select = 'l.page as label, COUNT(*) as total';
				break;
			
			case 'weekly':
				$select = 'DATE_FORMAT(STR_TO_DATE(CONCAT(yearweek(l.date), " ' . firebox()->_('FB_MONDAY') . '"), \'%%X%%V %%W\'), \'%%d %%b %%y\') as label, count(*) as total';
				break;
			
			case 'monthly':
				$select = 'DATE_FORMAT(l.date, \'%%b %%Y\') as label, COUNT(*) as total';
				break;
			
			case 'day_of_week':
				$select = 'DAYNAME(l.date) as label, COUNT(*) as total';
				break;
			
			case 'list':
			default:
				$partA = 'date(l.date) as label';

				if ($this->isSingleDay())
				{
					$partA = 'CONCAT(DATE_FORMAT(l.date, \'%H\'), \':00\') as label';
				}

				$select = $partA . ', COUNT(*) as total';
				break;
			
			case 'count':
				$select = 'COUNT(*) as total';
				break;
		}
		
		return $select;
	}

	private function getGroupBy()
	{
		if ($this->type === 'count')
		{
			return;
		}
		
		$groupby = 'date(l.date)';

		if ($this->isSingleDay() || $this->type === 'popular_view_times')
		{
			$groupby = 'CONCAT(DATE_FORMAT(l.date, \'%H\'), \':00\')';
		}

		if ($this->type === 'top_campaign')
		{
			$groupby = 'l.box';
		}
		else if ($this->type === 'countries')
		{
			$groupby = 'l.country';
		}
		else if ($this->type === 'referrers')
		{
			$groupby = 'l.referrer';
		}
		else if ($this->type === 'devices')
		{
			$groupby = 'l.device';
		}
		else if ($this->type === 'events')
		{
			$groupby = 'ld.event';
		}
		else if ($this->type === 'pages')
		{
			$groupby = 'l.page';
		}
		else if ($this->type === 'weekly')
		{
			$groupby = 'yearweek(l.date)';
		}
		else if ($this->type === 'monthly')
		{
			$groupby = 'YEAR(l.date), MONTH(l.date)';
		}
		else if ($this->type === 'day_of_week')
		{
			$groupby = 'label';
		}

		return 'GROUP BY ' . $groupby;
	}

	private function getOrderBy()
	{
		if ($this->type === 'count')
		{
			return;
		}
		
		$orderby = 'l.date DESC';

		if (in_array($this->type, ['top_campaign', 'countries', 'referrers', 'devices', 'events', 'pages', 'day_of_week']))
		{
			$orderby = 'total DESC';
		}
		
		if (isset($this->options['orderby']))
		{
			$orderby = $this->options['orderby'];
		}
		
		return 'ORDER BY ' . $orderby;
	}

	private function getWhere()
	{
		$where = '';

		if ($this->type === 'popular_view_times' && isset($this->options['weekday']))
		{
			$where = 'AND WEEKDAY(l.date) = \'' . $this->options['weekday'] . '\'';
		}
		
		return $where;
	}
}