<?php
if ( class_exists( 'WP_List_Table_Helper1' ) and ! class_exists( 'WPE_Prayers_Performed_Table' ) ) {
	class WPE_Prayers_Performed_Table extends WP_List_Table_Helper1 {
		public function __construct($tableinfo) {
			parent::__construct( $tableinfo );
		}
		public function column_default( $item, $column_name ) {
            // Return Default values from db except current timestamp field. If currenttimestamp_field is encountered return formatted value.
            $offset = get_option('gmt_offset');          
            if ( ! empty( $this->currenttimestamp_field ) and $column_name == $this->currenttimestamp_field ) {
                $return = date_i18n(get_option('date_format'),strtotime( $item->$column_name )+$offset*3600 ).' '.date_i18n(get_option('time_format'),strtotime( $item->$column_name )+$offset*3600 );
            } else if ( $column_name == $this->col_showing_links ) {
                $actions = array();
                foreach ( $this->actions as $action ) {
                    $action_slug = sanitize_title( $action );
                    $action_label = ucwords( $action );
                    if ( 'delete' == $action_slug ) {
                        $actions[ $action_slug ] = sprintf( '<a href="?page=%s&doaction=%s&'.$this->primary_col.'=%s">'.$action_label.'</a>',$this->admin_listing_page_name,$action_slug,$item->{$this->primary_col} );
                    }else if ( 'edit' == $action_slug ) {
                        $actions[ $action_slug ] = sprintf( '<a href="?page=%s&doaction=%s&'.$this->primary_col.'=%s">'.$action_label.'</a>',$this->admin_add_page_name,$action_slug,$item->{$this->primary_col} );
                    }else if ( 'prayers' == $action_slug ) {
                        $actions[ $action_slug ] = sprintf( '<a href="?page=%s&doaction=%s&'.$this->primary_col.'=%s">'.$action_label.'</a>',$this->admin_view_page_name,$action_slug,$item->{$this->primary_col} );
                    }else { $actions[ $action_slug ] = sprintf( '<a href="?page=%s&doaction=%s&'.$this->primary_col.'=%s">'.$action_label.'</a>',$this->admin_listing_page_name,$action_slug,$item->{$this->primary_col} ); }
                }
                if($item->{$this->col_showing_links}!= '')
                    return sprintf( '%1$s %2$s', $item->{$this->col_showing_links}, $this->row_actions( $actions ) );
                else
                    return sprintf( '%1$s %2$s', $item->prayer_messages, $this->row_actions( $actions ) );
            } else {
                $return = $item->$column_name;
            }
            return $return;
        }
		/**
		 * Output for User Name  column.
		 * @param array $item Map Row.
		 */
		public function column_user_id($item) {
			 $user_info = get_userdata($item->user_id);
			 $actions = array();
			foreach ( $this->actions as $action ) {
					$action_slug = sanitize_title( $action );
					$action_label1 =  $action ;
					if($action_label1 == "delete"){
					$action_label = __('delete',WPE_TEXT_DOMAIN);
					}
				if ( 'delete' == $action_slug )
					$actions[ $action_slug ] = sprintf( '<a href="?page=%s&doaction=%s&'.$this->primary_col.'=%s">'.$action_label.'</a>',$this->admin_listing_page_name,$action_slug,$item->{$this->primary_col} );
			}
			if($item->user_id != 0)
				return sprintf( '%1$s %2$s', $user_info->display_name , $this->row_actions( $actions ) );
			else
				return sprintf( '%1$s %2$s', 'Unknown' , $this->row_actions( $actions ) );
		}


		/* User ip */
		public function column_user_ip($ip)
		{
				//$ip = getenv("HTTP_CLIENT_IP");
				//echo $this->input->ip_address();
				print_r($ip->user_ip);

				//$ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
					 //$actions = array();
				//print_r($ip);
			}




	}
	// Minimal Configuration :)
	global $wpdb;
	$columns   = array( 'user_id' => __('Name',WPE_TEXT_DOMAIN), 'user_ip' => __('IP address',WPE_TEXT_DOMAIN), 'prayer_time' => __('Date',WPE_TEXT_DOMAIN) );
	$sortable  = array( 'user_id','prayer_time' );
	$sql = 'SELECT pu_id , pu.user_id, pu.user_ip, pu.prayer_time FROM '.WPE_TBL_PRAYER.' as p JOIN '.WPE_TBL_PRAYER_USERS.' as pu ON pu.prayer_id = p.prayer_id';
	$sql .=(isset($_GET['prayer_id'])) ? ' WHERE p.prayer_id ='. sanitize_text_field($_GET['prayer_id']) : ' and manage_pr_disp = 0';

	$tableinfo = array(
		'table' => WPE_TBL_PRAYER_USERS,
		'textdomain' => WPE_TEXT_DOMAIN,
		'singular_label' => __('IP address',WPE_TEXT_DOMAIN),
		'plural_label' => __('IP address',WPE_TEXT_DOMAIN),
		'admin_listing_page_name' => 'wpe_manage_prayers_performed',
		'primary_col' => 'pu_id',
		'columns' => $columns,
		'sortable' => $sortable,
		'per_page' => 200,
		'actions' => array( 'delete'),
		'col_showing_links' => 'user_id',
		'currenttimestamp_field' => 'prayer_time',
		'sql' => $sql,
	);
	
	return new WPE_Prayers_Performed_Table( $tableinfo );
}
