<?php
function cwmpAdminCreateLists($args){
	global $wpdb;
	global $table_prefix;
	$html = '';
	$url = '';
	foreach($args as $box){
		$html .= '<div class="mwp-box">';
			$html .= '<div class="col-1">';
			$html .= '<h3>'.$box['title'].'</h3>';
			$html .= '<p>'.$box['description'].'</p>';
			if(!empty($box['button']['url'])){ $html .= '<a href="'.$box['button']['url'].'" class="action">'.$box['button']['label'].'</a>'; }
			if(!empty($box['help'])){ $html .= '<a href="'.$box['help'].'" target="blank">Dúvidas? Veja a documentação</a>'; }
			$html .= '</div>';
			$html .= '<div class="col-2">';
			$html .= '<table class="widefat fixed cwmp_table" cellspacing="0">';
			$html .= '<tbody>';
			$query = "";
			if (!empty($box['bd']['args'])) {
				$query .= "WHERE ";
			}
			if (isset($box['bd']['args'])) {
				foreach ($box['bd']['args'] as $rows) {
					$query .= $rows['action'] . " ";
					$query .= $rows['row'] . "=%s ";
					$values[] = $rows['value'];
				}
			}
			if (!empty($box['bd']['order'])) {
				$order = "ORDER BY " . $box['bd']['order']['value'] . " " . $box['bd']['order']['by'] . "";
			}
			if (!empty($box['bd']['limit'])) {
				$order .= " LIMIT " . $box['bd']['limit']['value'] . "";
			}
			$result = $wpdb->get_results("SELECT * FROM {$table_prefix}{$box['bd']['name']} $query $order");

			if(count($result)==0){
				$html .= '<tr><td>';
				$html .= __('We found no record of your query.', 'checkout-mestres-wp');
				$html .= '</td></tr>';
			}
			foreach($result as $lines){
				$html .= '<tr>';
				foreach($box['bd']['lines'] as $line){
					if($line['type']=="text"){ $html .= '<td>'.$lines->{$line['value']}.'</td>'; }
					if($line['type']=="percent"){ $html .= '<td>'.cwmpFormatPercent($lines->{$line['value']}).'</td>'; }
					if($line['type']=="shipping"){ $html .= '<td>'.cwmpGetNameShipping($lines->{$line['value']}).'</td>'; }
					if($line['type']=="status"){ $html .= '<td>'.cwmpGetStatus($lines->{$line['value']}).'</td>'; }
					if($line['type']=="product"){ $html .= '<td>'.cwmpGetNameProduct($lines->{$line['value']}).'</td>'; }
					if($line['type']=="newsletterSends"){ $html .= '<td>'.cwmpGetNewsletterSends($lines->{$line['value']}).'</td>'; }
					if($line['type']=="newsletterClicks"){ $html .= '<td>'.cwmpGetNewsletterClicks($lines->{$line['value']}).'</td>'; }
					if($line['type']=="newsletterOpen"){ $html .= '<td>'.cwmpGetNewsletterOpen($lines->{$line['value']}).'</td>'; }
					if($line['type']=="icon"){ $html .= '<td style="text-align:center;width:20px;">'.cwmpGetIcon($lines->{$line['value']}).'</td>'; }

					if($line['type']=="bump"){
						$bump = explode(",",$line['value']);
						$html .= '<td style="text-align:left;width:370px;">'.cwmpProductsBump($lines->{$bump[0]},$lines->{$bump[1]}).'</td>'; 
					}
					if($line['type']=="time"){
						$time = explode(",",$line['value']);
						$array = array($lines->{$time[0]},'');
						$return = cwmpFormatTime($lines->{$time[0]},$lines->{$time[1]});
						$html .= '<td style="width:50px;">'.$return.'</td>';
					}
					if($line['type']=="page"){
						if(is_numeric($lines->{$line['value']})){
							$html .= '<td>'.get_the_title($lines->{$line['value']}).'</td>';
						}else{
							$html .= '<td>'.$lines->{$line['value']}.'</td>';
						}
					}
				}
				$html .= '<td style="text-align:center;width:20px;"><a href="'.$box['patch'].'edit&id='.$lines->id.'"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10" cy="10" r="10" fill="#43D19E"/><path d="M12.1904 5.69723C12.3414 5.54146 12.5218 5.41728 12.7212 5.33191C12.9206 5.24655 13.135 5.20171 13.3519 5.2C13.5688 5.1983 13.7839 5.23976 13.9846 5.32198C14.1853 5.4042 14.3677 5.52553 14.521 5.6789C14.6744 5.83228 14.7958 6.01464 14.878 6.21536C14.9602 6.41608 15.0017 6.63115 14.9999 6.84806C14.9982 7.06496 14.9534 7.27935 14.868 7.47875C14.7827 7.67816 14.6585 7.85858 14.5027 8.00953L13.8623 8.64999L11.55 6.33769L12.1904 5.69723ZM11.1091 6.77852L6.04079 11.8469C5.8578 12.0299 5.72472 12.2569 5.65485 12.5065L5.01148 14.8042C4.99662 14.8575 4.99618 14.9137 5.01021 14.9672C5.02425 15.0207 5.05225 15.0695 5.09136 15.1086C5.13046 15.1477 5.17925 15.1757 5.23274 15.1897C5.28623 15.2038 5.34249 15.2033 5.39575 15.1885L7.69308 14.5451C7.94258 14.4753 8.16989 14.3424 8.35309 14.1592L13.4218 9.09124L11.1095 6.77894L11.1091 6.77852Z" fill="white"/></svg></a></td>';
				$html .= '<td style="text-align:center;width:20px;"><a href="'.substr($box['patch'],0,-1).'&action=delete&id='.$lines->id.'"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10" cy="10" r="10" fill="#DA2424"/><path d="M14.0661 13.0907L13.09 14.0658C13.0036 14.1518 12.8867 14.2 12.7648 14.2C12.6429 14.2 12.526 14.1518 12.4396 14.0658L10.0005 11.6264L7.56134 14.0658C7.51857 14.1085 7.46779 14.1423 7.41191 14.1653C7.35604 14.1883 7.29617 14.2001 7.23574 14.1999C7.17532 14.1997 7.11552 14.1876 7.05979 14.1642C7.00406 14.1409 6.95348 14.1067 6.91097 14.0638L5.93391 13.0907C5.84811 13.004 5.79999 12.8869 5.79999 12.7649C5.79999 12.643 5.84811 12.5259 5.93391 12.4392L8.37306 10.0009L5.93391 7.56154C5.84824 7.47519 5.80016 7.35847 5.80016 7.23683C5.80016 7.11518 5.84824 6.99847 5.93391 6.91212L6.90997 5.93598C6.95248 5.89292 7.00311 5.85873 7.05893 5.8354C7.11475 5.81206 7.17465 5.80005 7.23516 5.80005C7.29566 5.80005 7.35556 5.81206 7.41138 5.8354C7.4672 5.85873 7.51784 5.89292 7.56034 5.93598L10.0005 8.37532L12.4396 5.93598C12.4821 5.89295 12.5326 5.85878 12.5883 5.83546C12.6441 5.81214 12.7039 5.80013 12.7643 5.80013C12.8247 5.80013 12.8846 5.81214 12.9403 5.83546C12.996 5.85878 13.0466 5.89295 13.089 5.93598L14.0661 6.91011C14.1519 6.9968 14.2 7.11385 14.2 7.23583C14.2 7.3578 14.1519 7.47485 14.0661 7.56154L11.6269 10.0009L14.0661 12.4392C14.1514 12.5262 14.1992 12.6431 14.1992 12.7649C14.1992 12.8868 14.1514 13.0037 14.0661 13.0907Z" fill="white"/></svg></a></td>';
				
				if(isset($box['action'])){
				if($box['action']['value']=="start"){
					if($lines->status=="0"){
						$html .= '<td style="text-align:center;width:20px;"><a href="'.substr($box['patch'],0,-1).'&action=start&id='.$lines->id.'"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10" cy="10" r="10" fill="#43D19E"/><path d="M14.1074 10.2C14.1076 10.3097 14.0795 10.4176 14.0257 10.5132C13.9719 10.6088 13.8943 10.6888 13.8004 10.7456L7.98211 14.3049C7.88401 14.365 7.77166 14.3978 7.65665 14.3999C7.54165 14.402 7.42816 14.3734 7.32791 14.317C7.22861 14.2615 7.14589 14.1805 7.08826 14.0824C7.03063 13.9844 7.00017 13.8727 7 13.7589V6.64107C7.00017 6.52731 7.03063 6.41564 7.08826 6.31756C7.14589 6.21947 7.22861 6.13851 7.32791 6.08299C7.42816 6.02659 7.54165 5.99798 7.65665 6.00011C7.77166 6.00224 7.88401 6.03503 7.98211 6.0951L13.8004 9.65443C13.8943 9.71116 13.9719 9.79121 14.0257 9.88682C14.0795 9.98242 14.1076 10.0903 14.1074 10.2Z" fill="white"/></svg></a></td>';
					}
					if($lines->status=="1"){
						$html .= '<td style="text-align:center;width:20px;"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10" cy="10" r="10" fill="#EFDD3C"/><path d="M5.5 12C4.67 12 4 11.33 4 10.5C4 9.67 4.67 9 5.5 9C6.33 9 7 9.67 7 10.5C7 11.33 6.33 12 5.5 12ZM10.5 12C9.67 12 9 11.33 9 10.5C9 9.67 9.67 9 10.5 9C11.33 9 12 9.67 12 10.5C12 11.33 11.33 12 10.5 12ZM15.5 12C14.67 12 14 11.33 14 10.5C14 9.67 14.67 9 15.5 9C16.33 9 17 9.67 17 10.5C17 11.33 16.33 12 15.5 12Z" fill="white"/></svg></td>';
					}
					if($lines->status=="2"){
						$html .= '<td style="text-align:center;width:20px;"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10" cy="10" r="10" fill="#43D19E"/><path d="M8.61047 15L5 11.4035L6.64535 9.76448L8.61047 11.7278L14.3547 6L16 7.639L8.61047 15Z" fill="white"/></svg></td>';
					}
				}
				}
				
				$html .= '</tr>';
			}
			$html .= '</tbody>';
			$html .= '</table>';
			$html .= '</div>';
		$html .= '</div>';
		
	}
	echo $html;
}
function cwmpAdminCreateListsOrders($args){
	global $wpdb;
	global $table_prefix;
	$html = '';
	$url = '';

	foreach($args as $box){
		$html .= '<div class="mwp-box">';
			$html .= '<div class="col-1">';
			$html .= '<h3>'.$box['title'].'</h3>';
			$html .= '<p>'.$box['description'].'</p>';
			if(!empty($box['button']['url'])){ $html .= '<a href="'.$box['button']['url'].'" class="action">'.$box['button']['label'].'</a>'; }
			if(!empty($box['help'])){ $html .= '<a href="'.$box['help'].'" target="blank">Dúvidas? Veja a documentação</a>'; }
			$html .= '</div>';
			$html .= '<div class="col-2">';
			$html .= '<table class="widefat fixed cwmp_table" cellspacing="0">';
			$html .= '<tbody>';
			if(isset($box['orders']['status'])){
				$array = explode(",",$box['orders']['status']);
				$orders = wc_get_orders( array( 'numberposts' => -1, 'status' => $array,'date_after' => date('Y-m-d', strtotime( '-4 days' )) ) );
				if(count($orders)==0){
					$html .= '<tr><td>';
					$html .= __('We found no record of your query.', 'checkout-mestres-wp');
					$html .= '</td></tr>';
				}
				foreach($orders as $order){
					$html .= "<tr>";
					$html .= "<td style='width:20%;'>#".$order->get_ID()."<br/>".date_format($order->get_date_created(),"d/m/Y")."<br/>".date_format($order->get_date_created(),"H:i:s")."</td>";
					$html .= "<td style='width:38%;'>".$order->get_billing_first_name()." ".$order->get_billing_last_name()."<br/>".$order->get_billing_email()."<br/>".$order->get_billing_phone()."</td>";
					$html .= "<td style='width:22%;text-align:center;'>".$order->get_payment_method_title()."</td>";
					$html .= "<td style='width:20%;text-align:right;'>".wc_price($order->get_total())."</td>";
					$html .= "</tr>";
				}
			}
			$html .= '</tbody>';
			$html .= '</table>';
			$html .= '</div>';
		$html .= '</div>';
		
	}
	echo $html;
	
}
function cwmpAdminCreateListsCarts($args){
	global $wpdb;
	global $table_prefix;
	$html = '';
	$url = '';
	foreach($args as $box){
		$html .= '<div class="mwp-box">';
			$html .= '<div class="col-1">';
			$html .= '<h3>'.$box['title'].'</h3>';
			$html .= '<p>'.$box['description'].'</p>';
			if(!empty($box['button']['url'])){ $html .= '<a href="'.$box['button']['url'].'" class="action">'.$box['button']['label'].'</a>'; }
			if(!empty($box['help'])){ $html .= '<a href="'.$box['help'].'" target="blank">Dúvidas? Veja a documentação</a>'; }
			$html .= '</div>';
			$html .= '<div class="col-2">';
			$html .= '<table class="widefat fixed cwmp_table" cellspacing="0">';
			$html .= '<tbody>';
$query = "";
$values = array();

if (!empty($box['bd']['args'])) {
    $query .= "WHERE ";
    foreach ($box['bd']['args'] as $rows) {
        $query .= $rows['action'] . " ";
        $query .= $rows['row'] . "=%s ";
        // Adicione o valor como um marcador de posição (%s) para o prepare
        $values[] = $rows['value'];
    }
}

if (!empty($box['bd']['order'])) {
    $order = "ORDER BY " . $box['bd']['order']['value'] . " " . $box['bd']['order']['by'] . "";
}

$result = $wpdb->get_results("SELECT * FROM {$table_prefix}{$box['bd']['name']} $query $order");

				if(count($result)==0){
					$html .= '<tr><td>';
					$html .= __('We found no record of your query.', 'checkout-mestres-wp');
					$html .= '</td></tr>';
				}
			foreach($result as $lines){
				$html .= '<tr>';
				foreach($box['bd']['lines'] as $line){
					if($line['type']=="text"){ $html .= '<td>'.$lines->{$line['value']}.'</td>'; }
					if($line['type']=="shipping"){ $html .= '<td>'.cwmpGetNameShipping($lines->{$line['value']}).'</td>'; }
					if($line['type']=="status"){ $html .= '<td>'.cwmpGetStatus($lines->{$line['value']}).'</td>'; }
					if($line['type']=="product"){ $html .= '<td>'.cwmpGetNameProduct($lines->{$line['value']}).'</td>'; }
					if($line['type']=="whatsappL"){ $html .= '<td style="text-align:center;width:20px;"><a href="https://wa.me/'.preg_replace('/[^0-9]/', '',$lines->{$line['value']}).'">'.cwmpGetIcon('whatsapp').'</a></td>'; }
					if($line['type']=="emailL"){ $html .= '<td style="text-align:center;width:20px;"><a href="mailto:'.$lines->{$line['value']}.'">'.cwmpGetIcon('email').'</a></td>'; }
					if($line['type']=="icon"){ $html .= '<td style="text-align:center;width:20px;">'.cwmpGetIcon($lines->{$line['value']}).'</td>'; }
					if($line['type']=="data"){ $html .= '<td style="text-align:right;width:70px;">'.cwmpFormatData($lines->{$line['value']}).'</td>'; }
					if($line['type']=="time"){
						$time = explode(",",$line['value']);
						$array = array($lines->{$time[0]},'');
						$return = cwmpFormatTime($lines->{$time[0]},$lines->{$time[1]});
						$html .= '<td style="width:50px;">'.$return.'</td>';
					}
					if($line['type']=="productsCart"){
						$sum_cart = array();
						$html .= '<td style="width:150px;">';
						global $wpdb;
						$carts_abandoneds = $wpdb->get_results(
							$wpdb->prepare(
								"SELECT * FROM {$wpdb->prefix}cwmp_cart_abandoned WHERE email = %s",
								$lines->{$line['value']}
							)
						);
						$cwmp_cart_recovery =  str_replace('\"','"',$carts_abandoneds[0]->cart);
						$cwmp_cart_recovery = json_decode($cwmp_cart_recovery);
						$produtos_recuperados = "";
						if($cwmp_cart_recovery){
							foreach($cwmp_cart_recovery as $key => $value){
								$produto = wc_get_product($value->product_id);
								if($produto){
									$sum_cart[] = $value->line_total;
									$produtos_recuperados .= "<a href='".$produto->get_permalink()."' target='blank'>".$produto->get_title()."</a><br/>";
								}
							}
							if(!empty($sum_cart)){
								$html .="<strong>".wc_price(array_sum(($sum_cart)))."</strong>";
							}
							if(isset($produtos_recuperados)){
								$html .= $produtos_recuperados;
							}
							
						}
						unset($sum_cart);
						
					}
					$html .= '</td>';

				}
				$html .= '</tr>';
			}
			$html .= '</tbody>';
			$html .= '</table>';
			$html .= '</div>';
		$html .= '</div>';
		
	}
	echo $html;
}