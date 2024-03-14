<?php 
/**
 * Admin Settings page
 * 
 * @author Pavel Kulbakin <p.kulbakin@gmail.com>
 */
class PMLC_Admin_Statistics extends PMLC_Controller_Admin {
	
	public function index() {
		if ($this->input->get('id')) {
			$this->stats();
			return;
		} 
		
		$get = $this->input->get(array(
			'order_by' => 'total_clicks',
			'order' => 'DESC',
			'pagenum' => 1,
			'perPage' => 10,
		));
		extract($get);
		$get['pagenum'] = absint($get['pagenum']);
		$this->data += $get;
		
		$list = new PMLC_Link_List(); $linkTable = $list->getTable();
		$stat = new PMLC_Stat_Record(); $statTable = $stat->getTable();
		$last_24 = new DateTime(); $last_24->modify('-24 hours'); $last_24 = $last_24->format('Y-m-d H:i:s');
		$this->data['list'] = 
			$list->join($statTable, "$statTable.link_id = $linkTable.id")
				->setColumns(
					"$linkTable.*",
					"COUNT($statTable.id) AS total_clicks",
					"COUNT(IF($statTable.registered_on > '$last_24', $statTable.id, NULL)) AS total_clicks_24",
					"COUNT(DISTINCT $statTable.ip_num) AS unique_clicks",
					"COUNT(DISTINCT IF($statTable.registered_on > '$last_24', $statTable.ip_num, NULL)) AS unique_clicks_24"
				)
				->getBy(array('preset' => '', 'is_trashed' => 0), "$order_by $order", $pagenum, $perPage, "$statTable.link_id");
		$this->data['page_links'] = paginate_links( array(
			'base' => add_query_arg('pagenum', '%#%', $this->baseUrl),
			'format' => '',
			'prev_text' => __('&laquo;', 'pmlc_plugin'),
			'next_text' => __('&raquo;', 'pmlc_plugin'),
			'total' => ceil($list->total() / $perPage),
			'current' => $pagenum,
		));
		
		$this->render();
	}
	
	public function stats() {
		$id = $this->input->get('id');
		$this->data['link'] = $link = new PMLC_Link_Record();
		if ($link->getById($id)->isEmpty() or '' != $link->preset or $link->is_trashed) {
			wp_redirect(add_query_arg(array('page' => 'pmlc-admin-statistics'), admin_url('admin.php')));
			die();
		}
		$this->baseUrl = add_query_arg('id', $id, $this->baseUrl);
		
		$get = $this->input->get(array(
			'type' => '',
			's' => '',
			'f' => array(),
			'order_by' => 'registered_on',
			'order' => 'DESC',
			'pagenum' => 1,
			'perPage' => 20,
		));
		$get['f'] += array('sd' => '', 'ed' => ''); // apply default filters
		extract($get);
		$get['pagenum'] = absint($get['pagenum']);
		$this->data += $get;
		
		$by = $this->_construct_by();
		
		$list = new PMLC_Stat_List();
		$this->data['list'] = $list->getBy($by, "$order_by $order", $pagenum, $perPage);
		$this->data['page_links'] = paginate_links(array(
			'base' => add_query_arg('pagenum', '%#%', $this->baseUrl),
			'format' => '',
			'prev_text' => __('&laquo;', 'pmlc_plugin'),
			'next_text' => __('&raquo;', 'pmlc_plugin'),
			'total' => ceil($list->total() / $perPage),
			'current' => $pagenum,
		));
		
		// compose graph data
		$graphData = new PMLC_Stat_List();
		$statTable = $graphData->getTable();
		switch ($type) { // find out proper group by depending on date range to display
			case 'today':
				$start = new DateTime(date('Y-m-d 00:00:00'));
				$end = new DateTime(date('Y-m-d H:00:00'));
				$group_by = 'DATE(registered_on), HOUR(registered_on)';
				$step = '+1 hour';
				$hdiv = 1;
				break;
			case 'yesterday':
				$start = new DateTime(date('Y-m-d 00:00:00')); $start->modify('-1 day');
				$end = new DateTime(date('Y-m-d 23:00:00')); $end->modify('-1 day');
				$group_by = 'DATE(registered_on), HOUR(registered_on)';
				$step = '+1 hour';
				$hdiv = 1;
				break;
			case 'range':
				// specify additiona limit if 1 boudary is missing
				if (empty($f['ed'])) {
					$f['ed'] = date('Y-m-d');
					$by += array("DATE($statTable.registered_on) <=" => $f['ed']);
				}
				if (empty($f['sd'])) {
					$_d = new DateTime($f['ed']); $_d->modify('-1 month');
					$f['sd'] = $_d->format('Y-m-d');
					$by += array("DATE($statTable.registered_on) >=" => $f['sd']);
				}
				$start = new DateTime($f['sd']);
				$end = new DateTime($f['ed']);
				// datect number of days in range specified
				$days = (strtotime($f['ed']) - strtotime($f['sd'])) / 86400;
				if ($days <= 1) {
					$end->modify('+23 hours');
					$group_by = 'DATE(registered_on), HOUR(registered_on)';
					$step = '+1 hour';
					$hdiv = 1;
				} elseif ($days >= 7) {
					$group_by = 'DATE(registered_on)';
					$step = '+1 day';
					$hdiv = 24;
				} else {
					$group_by = 'DATE(registered_on), HOUR(registered_on) % ' . $days;
					$step = '+' . $days . ' hours';
					$hdiv = $days;
				}
				break;
			case 'week':
				$start = new DateTime(date('Y-m-d 00:00:00')); $start->modify('-7 days');
				$end = new DateTime(date('Y-m-d 00:00:00'));
				$group_by = 'DATE(registered_on)';
				$step = '+1 day';
				$hdiv = 24;
				break;
			default:
				$start = new DateTime(date('Y-m-d 00:00:00')); $start->modify('-1 month');
				$end = new DateTime(date('Y-m-d 00:00:00'));
				$by += array("DATE($statTable.registered_on) >" => $start->format('Y-m-d')); // do not exceed 1 month of data for graph
				$group_by = 'DATE(registered_on)';
				$step = '+1 day';
				$hdiv = 24;
				break;
		}
		$clickData = array();
		// prepare result with empty values
		while($start->format('Y-m-d H') <= $end->format('Y-m-d H')) {
			$clickData[$start->format('Y-m-d ' . ('+1 day' == $step ? '00' : 'H')  . ':00:00')] = 0;
			$start->modify($step);
		}
		foreach ($graphData->setColumns(
				'DATE(registered_on) AS date',
				'HOUR(registered_on) DIV ' . $hdiv . ' AS hour',
				'COUNT(id) AS clicks'
			)->getBy($by, 'date', NULL, NULL, 'date,hour') as $r) {
			
			$clickData[sprintf('%s %02d:00:00', $r['date'], $r['hour'] * $hdiv)] = intval($r['clicks']);
		}
		$max = max($clickData);
		if ($max > 0) {
			$ratio = pow(10, ceil(log10($max) - 1)); 
			$max = ceil($max / $ratio) * $ratio;
		}
		$this->data['plotYMax'] = max($max, 12);
		$this->data['plotXTickFormat'] = $hdiv < 24 ? '%#m/%#d/%Y&nbsp;%H:00' : '%#m/%#d/%Y';
		$this->data['plotXTicks'] = array();
		$this->data['plotData'] = array();
		$tickGap = ceil(count($clickData) / 8); $tickNo = 0;
		foreach ($clickData as $date => $clicks) {
			$this->data['plotData'][] = array($date, $clicks);
			if ($tickNo++ % $tickGap == 0 || count($clickData) == $tickNo) {
				$this->data['plotXTicks'][] = $date;
			}
		}
		
		$this->render();
	}
	
	public function export()
	{
		if ( ! ($csv = fopen('php://output', 'a'))) {
			wp_die(__('Unable to open stream for outputting CSV file.', 'pmlc_plugin'));
		}
		$get = $this->input->get(array(
			'order_by' => 'registered_on',
			'order' => 'DESC',
			'pagenum' => NULL,
			'perPage' => 20,
		));
		extract($get);
		
		$columns = array(
			__('Link ID', 'pmlc_plugin'),
			__('Link Name', 'pmlc_plugin'),
			__('Click ID', 'pmlc_plugin'),
			__('Date/Time', 'pmlc_plugin'),
			__('Sub ID', 'pmlc_plugin'),
			__('Country Code', 'pmlc_plugin'),
			__('IP Address', 'pmlc_plugin'),
			__('User Agent', 'pmlc_plugin'),
			__('Destination URL', 'pmlc_plugin'),
			__('Referrer', 'pmlc_plugin'),
		);
		$list = new PMLC_Link_List(); $linkTable = $list->getTable();
		$stat = new PMLC_Stat_Record(); $statTable = $stat->getTable();
		$list->join($statTable, "$statTable.link_id = $linkTable.id")
			->setColumns( // order of listed columns matters and matches the order specified in $columns array above
				"$statTable.link_id",
				"$linkTable.name",
				"$statTable.id",
				"$statTable.registered_on",
				"$statTable.sub_id",
				"$statTable.country",
				"$statTable.ip",
				"$statTable.user_agent",
				"$statTable.destination_url",
				"$statTable.referer"
			)->getBy($this->_construct_by() + array("$linkTable.preset" => '', "$linkTable.is_trashed" => 0), "$order_by $order", $pagenum, $perPage);
		
		// output csv
		header('Content-type: text/csv');
		header('Content-Disposition: attachment; filename="export.csv"');
		fputcsv($csv, $columns);
		foreach ($list as $r) {
			fputcsv($csv, $r);
		}
		die();
	}
	
	protected function _construct_by()
	{
		$stat = new PMLC_Stat_Record(); $statTable = $stat->getTable();
		$by = array();
		$id = $this->input->get('id') and $by += array("$statTable.link_id" => $id);
		switch ($this->input->get('type')) {
			case 'today':
				$by += array("DATE($statTable.registered_on)" => date('Y-m-d'));
				break;
			case 'yesterday':
				$by += array("DATE($statTable.registered_on)" => date('Y-m-d', strtotime('-1 day')));
				break;
			case 'week':
				$by += array("DATE($statTable.registered_on) >=" => date('Y-m-d', strtotime('-7 day')));
				break;
			case 'range':
				$f = $this->input->get('f', array()) + array('sd' => '', 'ed' => '');
				$f['sd'] and $by += array("DATE($statTable.registered_on) >=" => $f['sd']);
				$f['ed'] and $by += array("DATE($statTable.registered_on) <=" => $f['ed']);
				break;
		}
		if ('' != ($s = $this->input->get('s'))) {
			$like = '%' . preg_replace('%\s+%', '%', preg_replace('/[%?]/', '\\\\$0', $s)) . '%';
			$by[] = array(array(
				"$statTable.sub_id LIKE" => $like,
				"$statTable.destination_url LIKE" => $like,
				"$statTable.ip LIKE" => $like,
				"$statTable.country LIKE" => $like,
				"$statTable.user_agent LIKE" => $like,
				"$statTable.referer LIKE" => $like,
			), 'OR');
		}
		return $by;
	}
}