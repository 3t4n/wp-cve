<?php
/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;

abstract class wptsafAbstractLog{
	protected $extension;
	protected $table;
	protected $fieldList;

	public function __construct(wptsafAbstractExtension $extension){
		$this->extension = $extension;
	}

	public function getTable(){
		return $this->table;
	}

	public function insertRow(array $fields = array()){
		global $wpdb;
		$env = wptsafEnv::getInstance()->getData();
		$envFields = array_intersect_key($env, array_flip($this->fieldList));
		$fields = array_merge($envFields, $fields);
		$columns = '`' . implode( '`, `', array_keys( $fields ) ) . '`';
		$placeholders = rtrim(str_repeat('%s, ', count($fields)), ', ');
		$query_format = "INSERT INTO `{$this->table}` ($columns) VALUES ($placeholders)";

		foreach ($fields as $name => $value) {
			if (is_array($value)) {
				$fields[$name] = serialize($value);
			}
		}
		$wpdb->query($wpdb->prepare($query_format, $fields));

		if ($wpdb->last_error) {
			wptsafExtensionSystemLog::getInstance()->getLog()->addDangerMessage(
				$this->extension,
				__('Database query error', 'security-antivirus-firewall')
				. "\n\nError: $wpdb->last_error"
				. "\n\nQuery: $wpdb->last_query"
			);
			return new WP_Error('wptsaf_security', __('Database query error', 'security-antivirus-firewall'));
		}

		do_action('wptsaf_security_extension_log_insert_row', $this->extension, $fields, $wpdb->insert_id);

		return $wpdb->insert_id;
	}


	public function insertRows(array $rows) {
		if (empty($rows)) {
			return 0;
		}

		global $wpdb;
		$env = wptsafEnv::getInstance()->getData();
		$envFields = array_intersect_key($env, array_flip($this->fieldList));
		$rowFields = array_merge($envFields, reset($rows));
		$columns = '`' . implode( '`, `', array_keys($rowFields)) . '`';
		$valuesPlaceholder = '(' . rtrim(str_repeat('%s, ', count($rowFields)), ', ') . ')';
		$queryValues = array();

		foreach ($rows as $key => $row) {
			$row = array_merge($envFields, $row);
			foreach ($row as $name => $value) {
				if (is_array($value)) {
					$row[$name] = serialize($value);
				}
			}
			$queryValues[] = $wpdb->prepare($valuesPlaceholder, $row);
		}

		$wpdb->query("INSERT INTO `{$this->table}` ($columns) VALUES " . implode(', ', $queryValues));

		if ($wpdb->last_error) {
			wptsafExtensionSystemLog::getInstance()->getLog()->addDangerMessage(
				$this->extension,
				__('Database query error', 'security-antivirus-firewall')
				. "\n\nError: $wpdb->last_error"
				. "\n\nQuery: $wpdb->last_query"
			);
			return new WP_Error('wptsaf_security', __('Database query error', 'security-antivirus-firewall'));
		}
		
		return $wpdb->rows_affected;
	}


	public function updateRow(array $fields = array()) {
		global $wpdb;

		if (empty($fields['id'])) {
			return new WP_Error('wptsaf_security', __('ID field is absent', 'security-antivirus-firewall'));
		}

		foreach ($fields as $name => $value) {
			if (is_array($value)) {
				$fields[$name] = serialize($value);
			}
		}

		$result = $wpdb->update($this->table, $fields, array('id' => $fields['id']), '%s', '%d');

		if ($wpdb->last_error) {
			wptsafExtensionSystemLog::getInstance()->getLog()->addDangerMessage(
				$this->extension,
				__('Database query error', 'security-antivirus-firewall')
				. "\n\nError: $wpdb->last_error"
				. "\n\nQuery: $wpdb->last_query"
			);
			return new WP_Error('wptsaf_security', __('Database query error', 'wptsaf_security'));
		}

		do_action('wptsaf_security_extension_log_update_row', $this->extension, $fields);

		return $result;
	}

	public function getRow($id){
		global $wpdb;
		$row = $wpdb->get_row($wpdb->prepare(
			"SELECT * FROM {$this->table} where id = '%d'",
			absint($id)
		), ARRAY_A);

		if ($wpdb->last_error) {
			wptsafExtensionSystemLog::getInstance()->getLog()->addDangerMessage(
				$this->extension,
				__('Database query error', 'security-antivirus-firewall')
				. "\n\nError: $wpdb->last_error"
				. "\n\nQuery: $wpdb->last_query"
			);
			return new WP_Error('wptsaf_security', __('Database query error', 'wptsaf_security'));
		}
		if (!$row) {
			wptsafExtensionSystemLog::getInstance()->getLog()->addDangerMessage(
				$this->extension,
				sprintf(__('Could not find row by id #%d', 'wptsaf_security'), absint($id))
			);
			return new WP_Error(
				'wptsaf_security',
				sprintf(__('Could not find row by id #%d', 'wptsaf_security'), absint($id))
			);
		}

		return $this->prepareRow($row);
	}

	public function getRows($limit = 10, $offset = 0, $order = 'DESC', $orderBy = 'id', array $where = array()){
		global $wpdb;
		$order = in_array(strtoupper($order), array('ASC', 'DESC')) ? $order : 'DESC';

		$whereParams = $this->prepareWhereParams($where);

		if (is_wp_error($whereParams)) {
			return $whereParams;
 		}

		$result = $wpdb->get_results($wpdb->prepare(
			"SELECT * FROM {$this->table}
			{$whereParams}
			ORDER BY {$orderBy} {$order}
			LIMIT %d, %d",
			$offset,
			$limit
		), ARRAY_A);

		if ($wpdb->last_error) {
			wptsafExtensionSystemLog::getInstance()->getLog()->addDangerMessage(
				$this->extension,
				__('Database query error', 'wptsaf_security')
				. "\n\nError: $wpdb->last_error"
				. "\n\nQuery: $wpdb->last_query"
			);
			return new WP_Error('wptsaf_security', __('Database query error', 'wptsaf_security'));
		}
		
		foreach ($result as $rowKey => $row) {
			$result[$rowKey] = $this->prepareRow($row);
		}

		return $result;
	}


	public function getRowsAmount(array $where = array()){
		global $wpdb;
		$whereParams = $this->prepareWhereParams($where);

		if (is_wp_error($whereParams)) {
			return null;
		}
		return $wpdb->get_var("SELECT COUNT(id) as count FROM {$this->table} {$whereParams}");
	}


	protected function prepareWhereParams(array $where){
		global $wpdb;
		$whereParams = array();

		foreach ($where as $params) {
			if (!is_array($params) || 3 !== count($params)) {
				wptsafExtensionSystemLog::getInstance()->getLog()->addWarningMessage(
					$this->extension,
					__('Database query error', 'wptsaf_security')
					. "\n\nError: Wrong 'where' params " . print_r($params, true)
				);
				return new WP_Error('wptsaf_security', __('Database query error', 'wptsaf_security'));
			}

			$column = trim($wpdb->prepare("%s", $params[0]), "'");
			$operator = $params[1];
			$value = trim($wpdb->prepare("%s", $params[2]), "'");
			if (preg_match('/[^=<>]/', $operator)) {
				wptsafExtensionSystemLog::getInstance()->getLog()->addWarningMessage(
					$this->extension,
					__('Database query error', 'wptsaf_security')
					. "\n\nError: Wrong operator in params " . print_r($params, true)
				);
				return new WP_Error('wptsaf_security', __('Database query error', 'wptsaf_security'));
			}

			$whereParams[] = "`{$column}` {$operator} '$value'";
		}

		return $whereParams ? ('WHERE ' . implode(' AND ', $whereParams)) : '';
	}


	public function prepareRow(array $row){
		foreach ($row as $fieldKey => $field) {
			/*echo $fieldKey."<br/>";*/
			//print_r($field);
			/*if($fieldKey == 'client_data'){
				print_r($field)	;
			}*/
			if ( $fieldKey != 'client_data' && is_serialized($field)) {
				$row[$fieldKey] = unserialize($field);
			}
		}

		if (isset($row['date'])) {
			$row['date'] = date(WPTSAF_DATE_FORMAT, $row['date'] + WPTSAF_DATE_GMT_OFFSET);
		}

		if (isset($row['date_gmt'])) {
			$row['date_gmt'] = date(WPTSAF_DATE_FORMAT, $row['date_gmt'] + WPTSAF_DATE_GMT_OFFSET);
		}

		if (isset($row['client_data']) && is_array($row['client_data'])) {
			$row['client_data'] = array_filter($row['client_data']);
		}

		return $row;
	}

	public function rotate(){
		global $wpdb;
		$frequency = $this->extension->getLogRotationFrequency();

		if (!$frequency) {
			return;
		}

		$date = new DateTime('now', new DateTimeZone('UTC'));
		$date->sub(new DateInterval("P{$frequency}"));
		$wpdb->query($wpdb->prepare(
			"DELETE FROM {$this->table} WHERE date_gmt < '%d'",
			$date->getTimestamp()
		));

		if ($wpdb->last_error) {
			wptsafExtensionSystemLog::getInstance()->getLog()->addDangerMessage(
				$this->extension,
				__('Database query error', 'wptsaf_security')
				. "\n\nError: $wpdb->last_error"
				. "\n\nQuery: $wpdb->last_query"
			);
			return new WP_Error('wptsaf_security', __('Database query error', 'wptsaf_security'));
		}

		do_action('wptsaf_security_extension_log_rotate', $this->extension);
	}

	public function clear(){
		global $wpdb;

		$result = $wpdb->query("DELETE FROM {$this->table}");

		if ($wpdb->last_error) {
			wptsafExtensionSystemLog::getInstance()->getLog()->addDangerMessage(
				$this->extension,
				__('Database query error', 'wptsaf_security')
				. "\n\nError: $wpdb->last_error"
				. "\n\nQuery: $wpdb->last_query"
			);
			return new WP_Error('wptsaf_security', __('Database query error', 'wptsaf_security'));
		}

		do_action('wptsaf_security_extension_log_clear', $this->extension);

		return $result;
	}

	public function export(){
		$rowDelimiter = str_repeat('-', 50) . "\n";
		$limit = 10;
		$offset = 0;
		$content = $rowDelimiter;

		while($result = $this->getRows($limit, $offset, 'ASC')) {
			foreach ($result as $row) {
				$content .= $this->formatRow($row);
				$content .= $rowDelimiter;
			}

			$offset += $limit;
		}

		do_action('wptsaf_security_extension_log_export', $this->extension);

		return $content;
	}

	public function formatRow(array $row, $indent = ''){
		$content = '';

		foreach ($row as $key => $value) {
			if (is_array($value)) {
				$content .= "{$indent}{$key}: \n";
				$content .= $this->formatRow($value, $indent . '    ');
			} else {
				$content .= "{$indent}{$key}: {$value}\n";
			}
		}

		return $content;
	}

	abstract public function createTable();

	public function dropTable() {
		global $wpdb;
		$wpdb->query("DROP TABLE `{$this->table}`");
	}
}
