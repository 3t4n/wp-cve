<?php
/**
* Admin log page 
*/
if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if( !class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-screen.php' );//added
    require_once( ABSPATH . 'wp-admin/includes/screen.php' );//added
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
    require_once( ABSPATH . 'wp-admin/includes/template.php' );
}

//Silvasoft log class (admin log datagrid)
if (!class_exists("Silvasoft_Log")) :
    class Silvasoft_Log extends WP_List_Table
    {
		protected $data;
		protected $logcount;
		protected $apiconnector;
		
        function __construct() {
			
		    global $status, $page;
			
			add_action( 'admin_head', array( &$this, 'admin_header' ) );
			
			global $apiconnector;
			$this->apiconnector = $apiconnector;
							  
		    parent::__construct( array(
            'singular'  => __( 'log', 'silvasoft-connector' ),     //singular name of the listed records
            'plural'    => __( 'logs', 'silvasoft-connector' ),   //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
			
			
        ) );
		
        }
		
		//column styles
		function admin_header() {
		  $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
		
		  echo '<style type="text/css">';
		  echo '.wp-list-table .column-time { width: 10%; }';
		  echo '.wp-list-table .column-name { width: 15%; }';
		  echo '.wp-list-table .column-text { width: 46%; }';
		  echo '.wp-list-table .column-woo_order_id { width: 13%; }';
		  echo '.wp-list-table .column-action { width: 16%;text-align:right; }';
		  echo '</style>';
		}
		
		/**
		* Column action to resent failed orders to Silvasoft
		*/
		function resendOrderToSilvasoft($orderid,$creditorder,$partialrefund = false) {
			if(!current_user_can('manage_options'))
			   return false;
			if(!is_admin())
				return false;
	
			$creditorder = ($creditorder === 1 || $creditorder === true || $creditorder === '1') ? true : false;
			
			$response = $this->apiconnector->transferOrderToSilvasoft($orderid,$creditorder || $partialrefund,$partialrefund);
			
			if(isset($_REQUEST['r'])) {
				echo '<div class="notice notice-success is-dismissible"><p>';
				echo _e( 'Actie afgerond. Bekijk hieronder het resultaat of <a href="'.$_REQUEST['r'].'">ga terug naar orderoverzicht</a>', 'my_plugin_textdomain' ); 
				echo '</div>';
				
			}
			
			if($response['status'] === "ok") {
				echo $this->action_success();	
			} else if($response['status'] === "alreadysend") {
				echo $this->action_alreadysend();	
			} else {
				echo $this->action_failure();
			}
		}
		
		/* Notifications */	
		function action_failure() {
			echo '<div class="notice notice-error is-dismissible"><p>';
			echo _e( 'Er is iets fout gegaan! Probeer het later nog eens, of neem contact op met de Silvasoft helpdesk.', 'my_plugin_textdomain' ); 
			echo '</div>';
		}
		function action_alreadysend() {
			echo '<div class="notice notice-error is-dismissible"><p>';
			echo _e( 'Deze order is al succesvol verstuurd naar uw boekhouding en kan daarom niet opnieuw gestuurd worden. Wilt u deze toch opnieuw sturen? Volg dan deze stappen: 
				<ul><li>Stap 1: Open de order die u opnieuw wilt versturen via het menu WooCommerce > Orders </li>
				<li>Stap 2: Zorg dat onder scherm informatie / screen options het tonen van eigen velden / custom fields aan staat.</li>
				<li>Stap 3: Scroll naar beneden op de pagina en zoek naar een blok met het kopje <strong>Eigen velden</strong> of <strong>Custom fields</strong>.</li>				
				<li>Stap 4: In dit blok zoekt u de volgende velden op: <u>silva_send_creditnota</u> en/of <u>silva_send_sale</u>. Beide veleden dient u te verwijderen. </li>
				<li>Stap 5: Verwijder deze beide velden en sla daarna de order opnieuw op. Nu kunt u de order opnieuw versturen. </li>	</ul>			
			', 'silvasoft' ); 
			echo '</div>';
		}
		function action_success() {
			echo '<div class="notice notice-success is-dismissible"><p>';
			echo _e( 'De order is succesvol verstuurd naar Silvasoft!', 'my_plugin_textdomain' ); 
			echo '</div>';
		}
		
		//table columns
		function get_columns(){
		  $columns = array(
			'time' => 'Tijd',
			'name'    => 'Type',
			'text'      => 'Omschrijving',
			'woo_order_id' => 'WooCommerce Order #'
		  );
		  return $columns;
		}
		
		//retreive items from database, taking care of searching and orderring and paging
		function prepare_items() {
			$columns = $this->get_columns();
			$hidden = array();
			$sortable = $this->get_sortable_columns();
			$this->_column_headers = array($columns, $hidden, $sortable);
			$orderby = isset($_GET['orderby'])? $_GET['orderby'] : '';
			$order = isset($_GET['order'])? $_GET['order'] : '';
			
			$per_page = 15;		   
			$search = isset($_REQUEST['s'])? $_REQUEST['s'] : '';
			if(	$search !== '') {
				$per_page = 50;//50 zoekresultaten 
			 }
		   
			$this->getLogCount($search);
			$total_items = $this->logcount;
			$current_page = $this->get_pagenum();
			if(	$search === '') {
				//only if not searching
				$this->set_pagination_args( array(
					'total_items' => $total_items, 
					'per_page'    => $per_page ,
					'current_page' =>  $current_page      
				));
			}
			
			$this->items = $this->getLogData($orderby,$order,$per_page,$current_page,$search);
		}
		
		//which columsn can be sorted?
		function get_sortable_columns() {
		  $sortable_columns = array(
			'time'  => array('time',false),
			'name' => array('name',false),
			'text'   => array('text',false),			
			'woo_order_id'   => array('woo_order_id',false)
		  );
		  return $sortable_columns;
		}
		
		//query logs
		function getLogData($orderby,$order,$per_page,$current_page,$search) {
			global $wpdb;
			$page_number = $current_page;
			
				 	
			$orderString = 'id DESC';
			if($orderby != '' && $order != '') {
				$orderString = $orderby . ' '. $order;
			}
			
		  	$where = '';
			if($search != '') {
				$whereformat = ' WHERE name like \'%%%s%%\' or text like \'%%%s%%\'';
				$where = sprintf($whereformat,$search,$search);
			}
			
			$sql = "SELECT * FROM {$wpdb->prefix}silvasoft_woo_log".$where;
			$sql .= ' ORDER BY '.$orderString;
			$sql .= " LIMIT $per_page";
			
			if($search === '') {
				$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
			}
		
		  	$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		
		  	return $result;
		}
		
		//query logs count
		function getLogCount($search) {
			global $wpdb;			
		
			$where = '';
			if($search != '') {
				$whereformat = ' WHERE name like \'%% %s %%\' or text like \'%% %s %%\'';
				$where = sprintf($whereformat,$search,$search);
			}
		
			$sql = "SELECT count(*) as aantal FROM {$wpdb->prefix}silvasoft_woo_log" .$where;				
		  	$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		
		  	$this->logcount = $result[0]['aantal'];
			return $this->logcount;
		}
	
		//rendering of column contents
		function column_default( $item, $column_name ) {
		  switch( $column_name ) { 
			case 'time':
			case 'name':
			case 'text':
			  return $item[ $column_name ];
			case 'action' :
				return $this->actionCol($item);
			case 'woo_order_id':
				if($item[ $column_name ] != '' && $item[ $column_name ] != NULL)
				return 'Order #'.$item[ $column_name ];
				else return;
			default:
			 	break;
		  }
		}
		
		/** 
		 * Custom column with action button
		 */  
		function actionCol($item) {
			$actions = array();
			if($item['canresend'] === 1 || $item['canresend'] === true || $item['canresend'] === '1') {
				$actions['resend'] = array(
					'url'       => wp_nonce_url(add_query_arg( 
										array(
											'grid_action'=> 'resend',
											'woo_order_id'=> $item['woo_order_id'],
											'creditorder'=> $item['creditorder']										
										)
									)),
					'name'      => __( 'Opnieuw versturen', 'woocommerce' ),
					'action'    => "link",
				);				
			}
			/* Print grid row actions */
			foreach ( $actions as $action ) {
				printf(
					'<a class="button tips %1$s" href="%2$s" data-tip="%3$s">%4$s</a>',
					esc_attr( $action['action'] ),
					esc_url( $action['url'] ),
					sprintf( esc_attr__( '%s product', 'woocommerce' ), $action['name'] ),
					esc_html( $action['name'] )
				);
			}
		}		
	}
endif;