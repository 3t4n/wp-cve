<?php
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/screen.php' );
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}



class Click5_Grid extends WP_List_Table { 
	protected $table;
    public function __construct() {
		parent::__construct(
			array(
				'post_type' => 'c5_history',
				'plural'    => 'records',
				'screen'    => null,
			)
		);
        global $wpdb;
		$prefix = apply_filters( 'wp_c5_history_db_tables_prefix', $wpdb->base_prefix );
		$this->table  = $wpdb->base_prefix . 'c5_history';
        add_screen_option(
            'per_page',
            array(
                'default' => 20,
                'label'   => __( 'Records per page', 'c5_history' ),
                'option'  => 'edit_c5_history_per_page',
            )
        );
		set_screen_options();

		
    }

	public function click5_clear_filter_params(){
		$_SESSION['cookie-filter-by-module'] = 'all';
		$_SESSION['cookie-filter-by-user'] = 'all';
		$_SESSION['cookie-filter-by-month'] = '0';
	}

	public function click5_clear_search_params(){
		$_SESSION['search_param'] = null;
	}

	public function no_items() {
		?>
			<div class="c5_history-list-table-no-items" style="text-align: center;">
			<br><br><br>
				<b><?php esc_html_e( 'You don’t have any history logs yet, which means that you have just installed our plugin.', 'c5_history' ); ?></b>
				<br><br>
				<?php esc_html_e( 'Give us some time and you will notice new activities being recorded in our logs.', 'c5_history' ); ?>
				<br><br>
				<?php 
					if((esc_attr(get_option("click5_history_log_technical_issue")) !== "1" && esc_attr(get_option("click5_history_log_critical_error")) !== "1" && esc_attr(get_option("click5_history_log_404")) !== "1") || empty(esc_attr(get_option("click5_history_log_alert_email")))){
						?>
						<b><a href="<?php echo esc_url(admin_url('admin.php?page=history-log-by-click5%2Fhistory-log-by-click5.php&tab=alerts')) ?>">Meanwhile make sure to enable Email Alerts!</a></b>
						<?php
					}
				?>
				<br><br><br><br>
			</div>
		<?php
	}

    public function get_columns() {
		return apply_filters(
			'wp_c5_history_list_table_columns',
			array(
				'date' => __( 'Date', 'c5_history' ),
				'description' => __( 'Event Description', 'c5_history' ),
				'user' => __( 'User', 'c5_history' ),
				'plugin' => __( 'Plugin&nbsp;/&nbsp;Module', 'c5_history' ),
			)
		);
	}

    public function columns_sortable() {
		return array(
			'date' => array( 'date', false ),
		);
	}

    public function prepare_items() { 
        $this->_column_headers = array(
			$this->get_columns(),
            array(),
            $this->columns_sortable(),
			$this->get_columns()['date']
		);	
		$this->items = $this->fetch_data();
		$total = $this->count_rows();

		$this->set_pagination_args(
			array(
				'total_items' => $total,
				'per_page'    => $this->get_items_per_page( 'edit_c5_history_per_page', 20 ),
			)
		);

    }

	public function get_pagenum() {
		$pagenum = isset( $_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 0;
	 
		if ( isset( $_REQUEST['max_page'] ) && $pagenum > absint($_REQUEST['max_page']) ) {
			$pagenum = absint($_REQUEST['max_page']);
		}

		if( (isset($_POST['filter_button']) || !empty($_POST['searchRecords'])) && isset($_POST['click5_filter_nonce'])){
			return 1;	
		}
	 
		return max( 1, $pagenum );
	}

    public function fetch_data() {
        $params = array();
		$order = filter_input( INPUT_GET, 'order' );
		if ( $order ) {
			$params['order'] = $order;
		}
		$orderby = filter_input( INPUT_GET, 'orderby' );
		if ( $orderby ) {
			$params['orderby'] = $orderby;
		}
		
        $params['paged'] = $this->get_pagenum();

        if ( ! isset( $params['records_per_page'] ) ) {
			$params['records_per_page'] = $this->get_items_per_page( 'edit_c5_history_per_page', 20 );
		}
		$params['records_per_page'] = apply_filters( 'c5_history_records_per_page', $params['records_per_page'] );

		$items = $this->get_rows($params);

		return $items;
    }

	public function get_rows($options) {
        global $wpdb;
		global $wp;
		$join  = '';
		$where = 'AND NULLIF(user, "") IS NOT NULL';
		$searchRecords = isset($_POST['searchRecords']) ? $_POST['searchRecords'] : null;

		if( isset($_POST['filter_button']) && isset($_POST['click5_filter_nonce'])){
			session_reset();
			//wp_redirect( admin_url( 'admin.php?page=history-log-by-click5%2Fhistory-log-by-click5.php&paged=1' ) );
			$this->click5_clear_search_params();
			$nonce = $_POST['click5_filter_nonce'];          
            if(wp_verify_nonce($nonce, 'click5_filter_nonce') )
            {
				$mod_param = esc_html($_POST['filter-by-module']);
				$mod_param = str_replace("%%%"," ", $mod_param);
				$mod_param = html_entity_decode($mod_param);

				if(isset($_POST['filter-by-module']))
					$_SESSION['cookie-filter-by-module'] = $mod_param;

				if($mod_param != "all") {
					$where .= " AND plugin='$mod_param'";
				}
				$user_param = esc_html($_POST['filter-by-user']);
				$user_param = str_replace("%%%"," ", $user_param);
				if($user_param == "WordPress") {
					$user_param = "WordPress Core";
				}
				if(isset($_POST['filter-by-user']))
					$_SESSION['cookie-filter-by-user'] = $user_param;

				if($user_param != "all") {
					$where .= " AND user='$user_param'";
				}

				$month_param = esc_html($_POST['filter-by-month']);

				$_SESSION['cookie-filter-by-month'] = $month_param;

				if($month_param != "0") {
					$date_sql =  substr($month_param, 0, -2) . "-" . substr($month_param, -2) . "-01";
					$date_check = new DateTime($date_sql);
					$where .= " AND date >= '" . $date_check->modify('first day of this month')->format('Y-m-d') . "' AND date <= '" . $date_check->modify("last day of this month")->format('Y-m-d'). "'";
				}
			}

			$_SESSION['filter_where'] = $where;
		} 
		else if( isset($_POST['search_button']) && isset($_POST['click5_filter_nonce']) && !empty($_POST['searchRecords'])){
			session_reset();
			//wp_redirect( admin_url( 'admin.php?page=history-log-by-click5%2Fhistory-log-by-click5.php&paged=1' ) );
			if(!empty($_POST['searchRecords'])){
				$this->click5_clear_filter_params();
			}
				
			$nonce = $_POST['click5_filter_nonce'];          
            if(wp_verify_nonce($nonce, 'click5_filter_nonce'))
            {
				$search_param = sanitize_text_field($_POST['searchRecords']);
				$_SESSION['search_param'] = $search_param;
				//$where .= " AND description like '%$search_param%'";
				$where .= " AND ((description like '%$search_param%') or (plugin like '%$search_param%') or (user like '%$search_param%')) and user !=''";
			}
			$_SESSION['filter_where'] = $where;
		}
		else if(!isset($_GET['paged']) && !isset($_POST['paged']))
		{
			$this->click5_clear_filter_params();
			$this->click5_clear_search_params();
			$_SESSION['filter_where'] = NULL;
		}

		if(!is_null($_SESSION['filter_where']) && empty($searchRecords)){
			if((!is_null($_SESSION['search_param']) && $searchRecords === '') || isset($_POST['filter_button'])){
				$this->click5_clear_search_params();
				$_SESSION['filter_where'] = str_replace("WHERE","AND",$where);
			}else{
				$where .= " ".$_SESSION['filter_where'];
			}
			
		}


        $limits   = '';
		$page     = absint( $options['paged'] );
		$per_page = absint( $options['records_per_page'] );

        if ( $per_page >= 0 ) {
			$offset = absint( ( $page - 1 ) * $per_page );
			$limits = "LIMIT {$offset}, {$per_page}";
		}

        $orderby = "date";
        $order = 'DESC';
		if($options['order']?? null)
		{
			if ( 'ASC' === strtoupper( $options['order'] ) ) {
				$order = 'ASC';
			}
		}
		
		if(!isset($_POST['click5_filter_nonce']) && empty($searchRecords))
			$where = $_SESSION['filter_where'].' AND NULLIF(user, "") IS NOT NULL';

		$orderby = sprintf( 'ORDER BY %s %s', $orderby, $order );
        $query = "SELECT *
		FROM $this->table
		{$join}
		WHERE 1=1 {$where}
		{$orderby}
		{$limits}";
		$query = $query;
		$query = $wpdb->prepare($query);
		$results1 = $wpdb->get_results($query);
			
		$final_result = array_map(function($item) {
			global $wpdb;
			$tab_name = $wpdb->base_prefix . 'users';
			$r2_query = $wpdb->prepare("SELECT * FROM $tab_name");
			$results2 = $wpdb->get_results($r2_query);
			if($item->user == "WordPress Core"  || $item->user == "History Log by click5" || $item->user == "UpdraftPlus - Backup/Restore" || $item->user == "Limit Login Attempts Reloaded") {
				return $item;
			} else {
				if (is_array($results2) || is_object($results2))
				{
					foreach($results2 as $user_data) {
						if($user_data->user_login == $item->user) {
							$item->user = $user_data->display_name;
							return $item;
						}		
					}
				}			
			}
		}, $results1);
		return $final_result;
    }

	public function count_rows() {
		global $wpdb;
		$searchRecords = isset($_POST['searchRecords']) ? $_POST['searchRecords'] : null;
		$where ="WHERE NULLIF(user, '') IS NOT NULL";
		if(isset($_POST['filter_button']) && isset($_POST['click5_filter_nonce'])){
			$nonce = $_POST['click5_filter_nonce'];          
            if(wp_verify_nonce($nonce, 'click5_filter_nonce'))
            { 
				$user_param = sanitize_text_field($_POST['filter-by-user']);
				$user_param = str_replace("%%%"," ", $user_param);
				if($user_param != "all") {
					$where .= " AND user='$user_param'";
				}
				$mod_param = sanitize_text_field($_POST['filter-by-module']);
				
				$mod_param = str_replace("%%%"," ", $mod_param);
				if($mod_param != "all") {
					$where .= " AND plugin='$mod_param'";
				}
				$month_param = sanitize_text_field($_POST['filter-by-month']);
				if($month_param != "0") {
					$date_sql =  substr($month_param, 0, -2) . "-" . substr($month_param, -2) . "-01";
					$date_check = new DateTime($date_sql);
					$where .= " AND date >= '" . $date_check->modify('first day of this month')->format('Y-m-d') . "' AND date <= '" . $date_check->modify("last day of this month")->format('Y-m-d'). "'";
				}	
			}
		}
		if(isset($_POST['search_button']) && isset($_POST['click5_filter_nonce']) && !empty($_POST['searchRecords'])){
			$nonce = $_POST['click5_filter_nonce'];          
            if(wp_verify_nonce($nonce, 'click5_filter_nonce'))
            { 
				$search_param = sanitize_text_field($_POST['searchRecords']);
				
				$where .= " AND ((description like '%$search_param%') or (plugin like '%$search_param%') or (user like '%$search_param%')) and user !='' ";
			}
		}

		if(!is_null($_SESSION['filter_where']) && empty($searchRecords)){
			$where .= " ".$_SESSION['filter_where'];
		}
		/*if(!is_null($_SESSION['filter_where']) && empty($searchRecords)){
			if((!is_null($_SESSION['search_param']) && $searchRecords === '') || isset($_POST['filter_button'])){
				$_SESSION['filter_where'] = $where;
			}else{
				$where .= " ".$_SESSION['filter_where'];
			}
			
		}*/

		if(!isset($_POST['click5_filter_nonce']))
			$where = "WHERE NULLIF(user, '') IS NOT NULL ".$_SESSION['filter_where'];

		$q = "SELECT COUNT(*) FROM $this->table {$where}";
		$results2 = $wpdb->get_var($q);
		return $results2;
	}

	public function display() {
		parent::display();
	}

	public function column_default( $data, $column ) {
		$out    = '';
		switch ( $column ) {
			case 'date':
				$date_format = esc_attr(get_option('date_format'));
            	$time_format = esc_attr(get_option('time_format'));     
				$current_offset = get_option( 'gmt_offset' );     
				if($current_offset == "0") {
					$current_offset = "UTC";
				}	
				if($date_format == null || $date_format == "" || $date_format == " " || $time_format == null || $time_format == "" || $time_format == " ") {
					$out = "";
				} else {
					$date_time_format = $date_format . " " . $time_format;
					$date_db = new DateTime($data->date);
					$time_zone = get_option('timezone_string');
					if ( get_option( 'timezone_string' ) || ! empty( $current_offset )) {
						$time_offset = get_option('gmt_offset');
						if($time_offset == "0") {
							$time_offset = "UTC";
						}
						if($time_offset>0)
						{
							$time_offset = "+".$time_offset;
						}
						else if($time_offset==0)
						{
							$time_offset = "+0";
						}
						
						try
						{
							$date_time_zone = new DateTimeZone($time_offset);
							$date_time_zone_format = $date_db->setTimezone($date_time_zone);		
							$out =$date_time_zone_format->format($date_time_format);
						}
						catch(Exception $ex)
						{
							$date_time_format = $date_format . " " . $time_format;
							$out = date($date_time_format, strtotime($data->date));
						}
					} else {
						$date_time_zone = new DateTimeZone($time_zone);
						$date_time_zone_format = $date_db->setTimezone($date_time_zone);		
						$out =$date_time_zone_format->format($date_time_format);
					}
					
					//$out = date($date_time_format, strtotime(new DateTime($data->date, new DateTimeZone($time_zone))));
					
				}
				break;

			case 'description':
				if($data->description == null || $data->description == "" || $data->description == " ") {
					$out = "";
				} else {
					$out = $data->description;
				}
				break;

			case 'user':
				if($data == null || $data->user == null || $data->user == "" || $data->user == " ") {
					$out = "";	
				} else {
					$out = $data->user;
				}
				break;	
			
			case 'plugin':
				if($data->plugin == null || $data->plugin == "" || $data->plugin == " ") {
					$out = "";
				} else {
					$out = $data->plugin;
				}
				break;	

			default:
				$out = "no data";
		}

		$tags = wp_kses_allowed_html( 'post' );
		$tags['time'] = array('datetime' => true, 'class' => true);
		$tags['img']['srcset'] = true;
		echo wp_kses( $out, $tags );
	}
}