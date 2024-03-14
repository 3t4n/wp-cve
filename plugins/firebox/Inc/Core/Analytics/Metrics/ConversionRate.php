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

class ConversionRate extends Metric
{
	public function getData()
	{
		$this->applyFilters();

		$sql = "SELECT
				{$this->getSelect()}
			FROM
				{$this->wpdb->prefix}firebox_logs as l
				LEFT JOIN {$this->wpdb->prefix}firebox_logs_details as bld ON bld.log_id = l.id AND bld.event = 'conversion'
			CROSS JOIN (
				SELECT '%s' AS start_date, '%s' AS end_date
			) AS outer_query
			WHERE
				1
				{$this->getWherePeriod()}
				{$this->sql_filters}
			{$this->getGroupBy()}
			{$this->getHaving()}
			{$this->getOrderBy()}
			{$this->getLimit()}
			{$this->getOffset()}";

		$sql = $this->wpdb->prepare($sql, $this->query_placeholders); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$data = $this->wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		if ($this->type === 'count')
		{
			$data = isset($data[0]->total) ? (float) $data[0]->total : 0;
		}

		return $data;
	}

	private function getSelect()
	{
		$select = '';

		$total_select = '(COUNT(DISTINCT bld.id) / COUNT(DISTINCT l.id)) * 100 AS total';
		
		switch ($this->type)
		{
			case 'top_campaign':
				$select = 'l.box as id, (select p.post_title from ' . $this->wpdb->prefix . 'posts as p WHERE p.ID = l.box) as label, ' . $total_select;
				break;
			
			case 'countries':
				$select = 'l.country as label, ' . $total_select;
				break;
			
			case 'referrers':
				$select = 'l.referrer as label, ' . $total_select;
				break;
			
			case 'devices':
				$select = 'l.device as label, ' . $total_select;
				break;
			
			case 'pages':
				$select = 'l.page as label, ' . $total_select;
				break;
			
			case 'weekly':
				$select = 'DATE_FORMAT(STR_TO_DATE(CONCAT(yearweek(l.date), " ' . firebox()->_('FB_MONDAY') . '"), \'%%X%%V %%W\'), \'%%d %%b %%y\') as label, ' . $total_select;
				break;
			
			case 'monthly':
				$select = 'DATE_FORMAT(l.date, \'%%b %%Y\') as label, ' . $total_select;
				break;

			case 'day_of_week':
				$select = 'DAYNAME(l.date) as label, ' . $total_select;
				break;
			
			case 'list':
			default:
				$partA = 'date(l.date) AS label';

				if ($this->isSingleDay())
				{
					$partA = 'CONCAT(DATE_FORMAT(l.date, \'%H\'), \':00\') as label';
				}
			
				$select = $partA . ', ' . $total_select;
				break;
			
			case 'count':
				$select = $total_select;
				break;
		}
		
		return $select;
	}

	private function getHaving()
	{
		$having = '';
		
		switch ($this->type)
		{
			case 'list':
			case 'top_campaign':
				$having = 'total > 0';
				break;
		}

		$having = $having ? 'HAVING ' . $having : '';
		
		return $having;
	}

	private function getGroupBy()
	{
		if ($this->type === 'count')
		{
			return;
		}
		
		$groupby = 'DATE(l.date)';

		if ($this->isSingleDay() && $this->type !== 'count')
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
		
		$orderby = 'DATE(l.date) desc';

		if (in_array($this->type, ['top_campaign', 'countries', 'referrers', 'devices', 'pages', 'day_of_week']))
		{
			$orderby = 'total desc';
		}

		if (isset($this->options['orderby']))
		{
			$orderby = $this->options['orderby'];
		}
		
		return 'ORDER BY ' . $orderby;
	}
}