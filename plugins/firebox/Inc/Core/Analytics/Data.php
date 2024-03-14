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

namespace FireBox\Core\Analytics;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Data
{
	private $start_date = null;

	private $end_date = null;
	
	private $metrics = [];

	private $filters = [];

	private $offset = null;

	private $limit = null;

	protected $options = [];
	
	public function __construct($start_date = '', $end_date = '', $options = [])
	{
		$utcTimeZone = new \DateTimeZone('UTC');
		$tz = new \DateTimeZone(wp_timezone()->getName());

		// Make start in UTC
		if ($start_date_obj = \DateTime::createFromFormat('Y/m/d H:i:s', $start_date, $tz))
		{
			$start_date_obj->setTimezone($utcTimeZone);
			$start_date = $start_date_obj->format('Y/m/d H:i:s');
		}

		// Make end_date in UTC
		if ($end_date_obj = \DateTime::createFromFormat('Y/m/d H:i:s', $end_date, $tz))
		{
			$end_date_obj->setTimezone($utcTimeZone);
			$end_date = $end_date_obj->format('Y/m/d H:i:s');
		}
		
		$this->start_date = $start_date;
		$this->end_date = $end_date;
		$this->options = $options;
	}

	/**
	 * Allowed metrics:
	 * 
	 * [
	 * 	  'impressions',
	 * 	  'submissions',
	 * 	  'conversionrate'
	 * ]
	 */
	public function setMetrics($metrics = [])
	{
		$this->metrics = $metrics;
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

	public function getData($type = 'list')
	{
		$data = array_fill_keys($this->metrics, []);

		foreach ($data as $metric_slug => &$metric_data)
		{
			// Validate the given metric name and abort if unknown
			if (!$class_name = \FireBox\Core\Analytics\Helpers\Metrics::getClassFromSlug($metric_slug))
			{
				unset($data[$metric_slug]);
				continue;
			}

			$class = '\FireBox\Core\Analytics\Metrics\\' . $class_name;
			$class = new $class($this->start_date, $this->end_date, $type, $this->options);

			if ($this->filters)
			{
				$class->setFilters($this->filters);
			}

			if ($this->offset)
			{
				$class->setOffset($this->offset);
			}

			if ($this->limit)
			{
				$class->setLimit($this->limit);
			}

			$metric_data = $class->getData();

			$class->onAfterGetData($metric_data);
		}
		
		return $data;
	}
}