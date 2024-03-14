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

class Conversions extends Metric
{
	public function getData()
	{
		$this->applyFilters();

		$sql = "SELECT
				{$this->getSelect()}
			FROM
				{$this->wpdb->prefix}firebox_logs_details as bld
				LEFT JOIN {$this->wpdb->prefix}firebox_logs as bl ON bl.id = bld.log_id
				{$this->getJOINs()}
			CROSS JOIN (
				SELECT '%s' AS start_date, '%s' AS end_date
			) AS outer_query
			WHERE
				1
				{$this->getWherePeriod('bld')}
				AND bld.event = 'conversion'
				{$this->sql_filters}
			{$this->getGroupBy()}
			{$this->getHaving()}
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
				$select = 'bl.box as id, (select p.post_title from ' . $this->wpdb->prefix . 'posts as p WHERE p.ID = bl.box) as label, COUNT(bld.id) as total';
				break;
			
			case 'countries':
				$select = 'bl.country as label, COUNT(bld.id) as total';
				break;
			
			case 'referrers':
				$select = 'bl.referrer as label, COUNT(bld.id) as total';
				break;
			
			case 'devices':
				$select = 'bl.device as label, COUNT(bld.id) as total';
				break;
			
			case 'pages':
				$select = 'bl.page as label, COUNT(bld.id) as total';
				break;
			
			case 'weekly':
				$select = 'DATE_FORMAT(STR_TO_DATE(CONCAT(yearweek(bld.date), " ' . firebox()->_('FB_MONDAY') . '"), \'%%X%%V %%W\'), \'%%d %%b %%y\') as label, COUNT(bld.id) as total';
				break;
			
			case 'monthly':
				$select = 'DATE_FORMAT(bld.date, \'%%b %%Y\') as label, COUNT(bld.id) as total';
				break;
			
			case 'day_of_week':
				$select = 'DAYNAME(bld.date) as label, COUNT(bld.id) as total';
				break;
			
			case 'list':
			default:
				$partA = 'date(bld.date) as label';

				if ($this->isSingleDay())
				{
					$partA = 'CONCAT(DATE_FORMAT(bld.date, \'%H\'), \':00\') as label';
				}

				$select = $partA . ', COUNT(bld.id) as total';
				break;
			
			case 'count':
				$select = 'COUNT(bld.id) as total';
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
		
		$groupby = 'date(bld.date)';

		if ($this->isSingleDay())
		{
			$groupby = 'CONCAT(DATE_FORMAT(bld.date, \'%H\'), \':00\')';
		}
		
		if ($this->type === 'top_campaign')
		{
			$groupby = 'bl.box';
		}
		else if ($this->type === 'countries')
		{
			$groupby = 'bl.country';
		}
		else if ($this->type === 'referrers')
		{
			$groupby = 'bl.referrer';
		}
		else if ($this->type === 'devices')
		{
			$groupby = 'bl.device';
		}
		else if ($this->type === 'pages')
		{
			$groupby = 'bl.page';
		}
		else if ($this->type === 'weekly')
		{
			$groupby = 'yearweek(bld.date)';
		}
		else if ($this->type === 'monthly')
		{
			$groupby = 'YEAR(bld.date), MONTH(bld.date)';
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
		
		$orderby = 'bld.date DESC';

		if (in_array($this->type, ['top_campaign', 'countries', 'referrers', 'devices', 'pages', 'day_of_week']))
		{
			$orderby = 'total DESC';
		}
		
		if (isset($this->options['orderby']))
		{
			$orderby = $this->options['orderby'];
		}

		return 'ORDER BY ' . $orderby;
	}

	private function getHaving()
	{
		$having = '';

		if ($this->type === 'countries')
		{
			$having = 'bl.country IS NOT NULL';
		}

		$having = $having ? 'HAVING ' . $having : '';
		
		return $having;
	}
}