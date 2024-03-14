<?php


class Acf_ct_database_manager
{
	public static function get_create_table_sql_query($acf_post_id, $table_name){
		global $wpdb;
		$fields = Acfct_utils::get_acf_keys($acf_post_id);

		$columnQuery = "";
		foreach ($fields as $field) {
			$column_types = self::get_sql_data_type($field['type'], $field, $table_name);
			$single_column_query = "`{$field['name']}` {$column_types} DEFAULT NULL,"; //name tinytext DEFAULT NULL, // @todo - filter
			if($columnQuery === ""){
				$columnQuery = $single_column_query;
			}else{
				$columnQuery = $columnQuery."\r\n".$single_column_query;
			}
		}

		$table_name = $wpdb->prefix . $table_name;
		$post_id_column_name = ACF_CUSTOM_TABLE_POST_ID_COLUMN;

		$charset_collate = $wpdb->get_charset_collate();
		$tableQuery = "CREATE TABLE $table_name (
		`id` bigint unsigned NOT NULL AUTO_INCREMENT,
		`$post_id_column_name` bigint unsigned NOT NULL,
		$columnQuery
		PRIMARY KEY  (`id`),
		KEY `$post_id_column_name` (`$post_id_column_name`)
		) $charset_collate;";

		return $tableQuery;
	}

	public static function get_sql_data_type($acf_field_type, $field, $table_name){
		$map = array(
			'text'				=> 'text',
			'textarea'			=> 'text',
			'number'			=> 'mediumint(9)',
			'range'				=> 'mediumint(9)',
			'email'				=> 'text',
			'url'				=> 'text',
			'password'			=> 'text',
			'image'				=> 'longtext',
			'file'				=> 'longtext',
			'wysiwyg'			=> 'longtext',
			'oEmbed'			=> 'longtext',
			'gallery'			=> 'longtext',
			'select'			=> 'varchar(255)',
			'radio'				=> 'varchar(255)',
			'checkbox'			=> 'varchar(255)',
			'button_group'		=> 'varchar(255)',
			'true_false' 		=> 'int(2)',
			'link' 				=> 'text',
			'post_object' 		=> 'longtext',
			'page_link' 		=> 'text',
			'relationship' 		=> 'longtext',
			'taxonomy' 			=> 'longtext',
			'user' 				=> 'text',
			'google_map' 		=> 'text',
			'date_picker' 		=> 'varchar(255)',
			'date_time_picker' 	=> 'varchar(255)',
			'time_picker'	 	=> 'varchar(255)',
			'color_picker'	 	=> 'varchar(255)',
			'repeater'	 		=> 'longtext',
			'flexible_content'	=> 'longtext',
		);
		$sql_data_type = (array_key_exists($acf_field_type, $map)) ? $map[$acf_field_type] : 'text';

		//filters
		$sql_data_type = apply_filters('acf_ct/set_sql_data_type', $sql_data_type, $field, Acfct_utils::maybe_prefix_table_name($table_name));
		$sql_data_type = apply_filters('acf_ct/set_sql_data_type/name='.$field['name'], $sql_data_type, $field, Acfct_utils::maybe_prefix_table_name($table_name));
		$sql_data_type = apply_filters('acf_ct/set_sql_data_type/key='.$field['key'], $sql_data_type, $field, Acfct_utils::maybe_prefix_table_name($table_name));

		return $sql_data_type;
	}

	public static function create_table($acf_post_id, $table_name = false){
		global $wpdb;
		/**
		 * If table not passed then fetch from db
		 */
		if($table_name === false){
			$table_name = Acfct_utils::get_custom_table_name($acf_post_id);
			if($table_name === false){
				return array(
					'status'	=> 'error',
					'msg'		=> 'Custom table name not found for ACF:'.$acf_post_id,
					'result'	=> array()
				);
			}
		}

		$tableQuery = self::get_create_table_sql_query($acf_post_id, $table_name);

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$create_table_result = dbDelta( $tableQuery );
		$delta_error = $wpdb->last_error;

		$status = 'success';
		$msg = "Success";

		/**
		 * Check dbDelta errors
		 */
		if(empty($delta_error) === false){
			$status = 'error';
			$msg = $delta_error;
			$create_table_result = array();
		}
		/**
		 * Re-verify table created or not
		 */
		else if(self::check_table_exists($table_name) === false){
			$status = 'error';
			$msg = "Error... Please check SQL query";
		}
		/**
		 * If delta result is empty that means no changes found
		 */
		else if(empty($create_table_result)){
			$status = 'warning';
			$msg = "No new changes found";
		}

		/**
		 * Save fields array in a db for comparison
		 */
		if($status === "success" || $status === "warning"){
			update_option('acf_ct_'.$acf_post_id, Acfct_utils::get_acf_keys($acf_post_id), false);
			self::create_log($acf_post_id, $create_table_result);
		}

		return array(
			'status'	=> $status,
			'msg'		=> $msg,
			'result'	=> $create_table_result
		);
	}

	public static function check_table_exists($table_name){
		$prefix_table_name = Acfct_utils::maybe_prefix_table_name($table_name);
		global $wpdb;
		$table_exist = $wpdb->get_results("SHOW TABLES LIKE '$prefix_table_name'", ARRAY_A);
		return empty($table_exist) === false;
	}

	/**
	 * Checks if word is MySQL reserved keyword
	 * @param $word
	 * @return bool
	 */
	public static function is_mysql_reserved_keyword($word){
		$mysql_reserved_keywords = array("add","all","alter","analyze","and","as","asc","auto_increment","bdb","berkeleydb","between","bigint","binary","blob","both","btree","by","cascade","case","change","char","character","check","collate","column","columns","constraint","create","cross","current_date","current_time","current_timestamp","database","databases","day_hour","day_minute","day_second","dec","decimal","default","delayed","delete","desc","describe","distinct","distinctrow","div","double","drop","else","enclosed","errors","escaped","exists","explain","false","fields","float","for","force","foreign","from","fulltext","function","geometry","grant","group","hash","having","help","high_priority","hour_minute","hour_second","if","ignore","in","index","infile","inner","innodb","insert","int","integer","interval","into","is","join","key","keys","kill","leading","left","like","limit","lines","load","localtime","localtimestamp","lock","long","longblob","longtext","low_priority","master_server_id","match","mediumblob","mediumint","mediumtext","middleint","minute_second","mod","mrg_myisam","natural","not","null","numeric","on","optimize","option","optionally","or","order","outer","outfile","precision","primary","privileges","procedure","purge","read","real","references","regexp","rename","replace","require","restrict","returns","revoke","right","rlike","rtree","select","set","show","smallint","some","soname","spatial","sql_big_result","sql_calc_found_rows","sql_small_result","ssl","starting","straight_join","striped","table","tables","terminated","then","tinyblob","tinyint","tinytext","to","trailing","true","types","union","unique","unlock","unsigned","update","usage","use","user_resources","using","values","varbinary","varchar","varcharacter","varying","warnings","when","where","with","write","xor","year_month","zerofill", "id", ACF_CUSTOM_TABLE_POST_ID_COLUMN);
		return in_array($word, $mysql_reserved_keywords);
	}

	/**
	 * Returns column names which are MySQL reserved words
	 * @param $columns
	 * @return array
	 */
	public static function get_invalid_column_names($columns){
		$in_valid_columns = array();
		foreach ($columns as $column){
			if(self::is_mysql_reserved_keyword($column)){
				array_push($in_valid_columns, $column);
			}
		}
		return $in_valid_columns;
	}

	/**
	 * Validate ACF field group
	 * @param $acf_post_id
	 * @return array
	 */
	public static function validate_acf_group($acf_post_id){
		$columns = array_column(Acfct_utils::get_acf_keys($acf_post_id), 'name');
		$invalid_columns = self::get_invalid_column_names($columns);
		$duplicate_columns = array_diff_assoc($columns, array_unique($columns));
		$valid = false;

		if(empty($invalid_columns) === true && empty($duplicate_columns) === true){
			$valid = true;
		}

		return array(
			'valid'	=> $valid,
			'duplicate_columns' => $duplicate_columns,
			'invalid_columns' => $invalid_columns,
		);
	}

	public static function should_update_custom_table($acf_post_id){
		$change_list = self::get_acf_fields_change_list($acf_post_id);

		if($change_list === false){
			return false;
		}

		if(is_array($change_list)){
			return $change_list['should_update'];
		}

		return false;
	}

	public static function get_acf_fields_change_list($acf_post_id){
		$change_list = array(
			'should_update'	=> false,
			'created'		=> false, //table created
			'added'			=> array(),
			'updated'		=> array(),
			'deleted'		=> array()
		);

		/**
		 * Check custom table available or not
		 */
		$customTableName = Acfct_utils::get_custom_table_name($acf_post_id);
		if($customTableName === false){
			return false;
		}

		/**
		 * Check table already created or not
		 */
		$table_exists = self::check_table_exists($customTableName);
		if($table_exists === false){
			$change_list['should_update'] = true;
			$change_list['created'] = true;
			return $change_list;
		}

		$old_acf_fields = get_option('acf_ct_'.$acf_post_id, []);
		$new_acf_fields = Acfct_utils::get_acf_keys($acf_post_id);

		/**
		 * Compare old and new acf field array
		 */
		if($old_acf_fields === $new_acf_fields){
			return false;
		}

		$new_fields = array_diff_key($new_acf_fields, $old_acf_fields);
		$deleted_fields = array_diff_key($old_acf_fields, $new_acf_fields);

		$change_list['added'] = array_merge($change_list['added'], self::_get_field_names($new_fields));
		$change_list['deleted'] = array_merge($change_list['deleted'], self::_get_field_names($deleted_fields));

		/**
		 * Find updated fields
		 */
		$updated_fields = array();
		foreach ($new_acf_fields as $key => $field){
			if(array_key_exists($key, $old_acf_fields)){
				if($old_acf_fields[$key] !== $field){
					/**
					 * If names are not same that means column name is changed
					 * dbDelta will create new column for changed column
					 * So add this field in 'added' list
					 */
					if($old_acf_fields[$key]['name'] !== $field['name']){
						array_push($change_list['added'], $field['name']);
					}
					/**
					 * If type is not same that means column type is changed.
					 * dbDelta will update column type
					 */
					else if($old_acf_fields[$key]['type'] !== $field['type']){
						/**
						 * Check sql data type of new and old type. If both are different then add field in update list
						 */
						if(self::get_sql_data_type($old_acf_fields[$key]['type'], $old_acf_fields[$key], $customTableName) !== self::get_sql_data_type($field['type'], $field, $customTableName)){
							array_push($updated_fields, $field);
						}
					}
				}
			}
		}
		$change_list['updated'] = array_merge($change_list['updated'], self::_get_field_names($updated_fields));

		/**
		 * If new field added or updated then return true
		 */
		if(empty($change_list['added']) === false || empty($change_list['updated']) === false){
			$change_list['should_update'] = true;
			return $change_list;
		}

		/**
		 * If fields deleted then return false
		 * @Note Currently delete column operation is not supported
		 */
		if(empty($change_list['deleted']) === false){
			return false;
		}

		return $change_list;
	}

	/**
	 * Save create table log in post meta
	 * @param $acf_post_id
	 * @param $log
	 * @return array|mixed
	 */
	public static function create_log($acf_post_id, $log){
		$existing_log = self::get_logs($acf_post_id);

		/**
		 * Append new log if new log is not empty
		 */
		if(empty($log) === false){

			/**
			 * Current User details
			 */
			global $current_user;
			wp_get_current_user();

			$new_log = array(
				array(
					'time'	=> time(),
					'user'	=> $current_user->user_login,
					'log'	=>  $log
				)
			);
			$existing_log = array_merge($existing_log, $new_log);
			update_post_meta($acf_post_id, ACF_CT_LOG_KEY, $existing_log);
		}

		return $existing_log;
	}

	/**
	 * Get table logs
	 * @param $acf_post_id
	 * @return array|mixed
	 */
	public static function get_logs($acf_post_id){
		$existing_log = get_post_meta($acf_post_id, ACF_CT_LOG_KEY, true);

		/**
		 * Initialize log if existing log is empty
		 */
		if(empty($existing_log) === true || is_array($existing_log) === false){
			$existing_log = array();
		}

		return $existing_log;
	}

	/**
	 * Get field names from ACF field array
	 * @param $field_array
	 * @return mixed
	 */
	private static function _get_field_names($field_array){
		return array_reduce($field_array, function ($field_names, $field){
			array_push($field_names, $field['name']);
			return $field_names;
		}, []);
	}
}
