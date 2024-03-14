<?php
/*Plugin admin pages html css js functions*/

# Woocommerce admin pages footer content
if(!function_exists('myworks_woo_sync_for_xero_wc_admin_pages_footer_content')){
	function myworks_woo_sync_for_xero_wc_admin_pages_footer_content($data=array()){
		global $MWXS_L;

		# Css
		echo '
		<style>
			th#c_xs_mwxs{text-align: center;}
			td.column-c_xs_mwxs{text-align: center;}
			.x_ss{display:none;}
		</style>
		';

		# Js
		$wc_order_id_num_list = $MWXS_L->get_session_val('wc_order_id_num_list',array(),true);		
		#$MWXS_L->_p($wc_order_id_num_list);
		if(is_array($wc_order_id_num_list) && !empty($wc_order_id_num_list)){
			wp_nonce_field( 'myworks_wc_xero_sync_order_sync_status_list', 'order_sync_status_list' );
				
			echo '
			<script>
				jQuery(document).ready(function($){
					var data = {
						"action": \'myworks_wc_xero_sync_order_sync_status_list\',
						"order_sync_status_list": $("#order_sync_status_list").val(),
						"order_id_num_arr":'.json_encode((array) $MWXS_L->array_sanitize($wc_order_id_num_list)).'
					};
					
					$.ajax({
						type: "POST",
						url: ajaxurl,
						data: data,
						cache:  false ,
						datatype: "json",
						success: function(result){
							var w_x_o_a = JSON.parse(result);
							if(!$.isEmptyObject(w_x_o_a)){
								$.each(w_x_o_a, function(key,val){									
									if(!$.isEmptyObject(val)){
										let xl_a_title = "Click to view it in Xero";
										let xl_a_html = "<a style=\'color:white;\' target=\'_blank\' href=\'"+val.Xero_Link+"\'><span title=\'"+xl_a_title+"\'>Synced</span></a>";
										$("#c_xs_"+key).html(\'<span class="x_ss"><span class="mw_qbo_sync_status_span mw_qbo_sync_status_paid">\'+xl_a_html+\'</span></span>\');
									}else{
										$("#c_xs_"+key).html(\'<span class="x_ss"><span class="mw_qbo_sync_status_span mw_qbo_sync_status_due">Not Synced</span></span>\');
									}									
								});
								
								$("span.x_ss").each(function(i) {
									$(this).delay(100*i).fadeIn(100);
								});
							}
						},
						error: function(result) {
							$("div.c_xs_data").html("!");
						}
					});
				});
			</script>
			';
		}		
	}
}

# Hide wp notices in our plugin pages
if(!function_exists('myworks_woo_sync_for_xero_hide_wp_notices')){
	function myworks_woo_sync_for_xero_hide_wp_notices(){
		echo '<style>div.notice, div.notice-info{display:none !important;}</style>';
	}
}

# Select 2
if(!function_exists('myworks_woo_sync_for_xero_get_select2_js')){
	function myworks_woo_sync_for_xero_get_select2_js($item='select',$d_item='',$prevent_lib_load=false){
		global $MWXS_L;
		if(!$MWXS_L->is_s2_dd()){
			return '';
		}

		$item = $MWXS_L->sanitize($item);
		if(empty($item)){
			return '';
		}
		
		$is_ajax_dd = 0;
		if($MWXS_L->is_s2_ajax_dd()){
			$is_ajax_dd = 1;
		}
		
		$json_data_url = '';
		if($d_item=='xero_product'){
			$json_data_url = site_url('index.php?mw_xero_sync_public_api=1&t=get_json_item_list&item=xero_product');
		}
		
		if($d_item=='xero_customer'){
			$json_data_url = site_url('index.php?mw_xero_sync_public_api=1&t=get_json_item_list&item=xero_customer');
		}
		
		$s2_lib = '';
		if(!$prevent_lib_load){		
			# Lib Not needed here
		}
		
		echo '
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery(\''.$MWXS_L->escape($item).'\').addClass(\'mwqs_s2\');
			});

			jQuery(function($){
				//jQuery(\''.$MWXS_L->escape($item).'\').select2();
				jQuery(\''.$MWXS_L->escape($item).'\').each(function(){
					if(jQuery(this).prop(\'multiple\')){
						 jQuery(this).select2();
						 jQuery(this).removeClass(\'mwqs_s2\');
					}
				});

				jQuery(\''.$MWXS_L->escape($item).'\').hover(function(){
					var is_ajax_dd = '.$MWXS_L->escape($is_ajax_dd).';
					if(jQuery(this).hasClass(\'mwqs_dynamic_select\') && is_ajax_dd==1){
						 jQuery(this).select2({
							 ajax: {
							  url: "'.esc_url_raw($json_data_url).'",
							  dataType: \'json\',
							  delay: 250,
							  data: function (params) {
								  return {
									  q: params.term // search term
								  };
							  },
							  processResults: function (data) {
								  return {
									  results: data
								  };
							  },
							  cache: true
						  },
						  minimumInputLength: 3
						 });
					}else{
						jQuery(this).select2();
					}

					jQuery(this).removeClass(\'mwqs_s2\');

				});

				var head = $("head");
				var headlinklast = head.find("link[rel=\'stylesheet\']:last");
				var linkElement = "<style type=\'text/css\'>ul.select2-results__options li:first-child{padding:12px 0;}</style>";

				if (headlinklast.length){
					headlinklast.after(linkElement);
				}
				else {
					head.append(linkElement);
				}

			});
		</script>
		';		
	}
}

# Sweet Alert
if(!function_exists('myworks_woo_sync_for_xero_set_admin_sweet_alert')){
	function myworks_woo_sync_for_xero_set_admin_sweet_alert($save_status=''){
		if($save_status){
			global $MWXS_L;
			if($save_status=='admin-success-green'){
				echo '<script>swal("Rock On!", "Your settings have been saved.", "success")</script>';
			}elseif($save_status=='red lighten-2'){
				echo '<script>swal("Oops!", "Hmmmm something went wrong.", "error")</script>';
			}elseif($save_status!='admin-success-green' && $save_status!='red lighten-2' && $save_status!='error'){
				echo '<script>swal("Rock On!", "'.$MWXS_L->escape($save_status).'", "success")</script>';
			}else{
				echo '<script>swal("Oops!", "Hmmmm something went wrong.", "error")</script>';
			}
			echo '<script type="text/javascript">
			jQuery(document).ready(function(e){
				jQuery(".confirm").on("click",function(e){
					jQuery(".sweet-overlay").hide();
					jQuery(".showSweetAlert").hide();
					jQuery("body").removeClass("stop-scrolling");
				});
			});
			</script>';
		}
	}
}

# Tooltip
if(!function_exists('myworks_woo_sync_for_xero_set_tooltip')){
	function myworks_woo_sync_for_xero_set_tooltip($tt,$ti='?'){
		global $MWXS_L;
		$ti = (string) $ti;
				
		echo '<div class="material-icons tooltipped right tooltip">';
		echo $MWXS_L->escape($ti);		
		echo '<span class="tooltiptext">'.str_replace(['{LB}','{BOLD_S}','{BOLD_E}'],['<br>','<b>','</b>'],$MWXS_L->escape($tt)).'</span>';
		echo '</div>';
	}
}

# Table Sorter
if(!function_exists('myworks_woo_sync_for_xero_get_tablesorter_js')){
	function myworks_woo_sync_for_xero_get_tablesorter_js($item='table'){
		global $MWXS_L;

		$item = $MWXS_L->sanitize($item);
		if(empty($item)){
			return '';
		}
		
		echo '
		<script type="text/javascript">
			jQuery(function($){
				//jQuery(\''.$MWXS_L->escape($item).'\').addClass(\'tablesorter-blue\');
				jQuery(\''.$MWXS_L->escape($item).' th\').css(\'cursor\',\'pointer\');
				
				jQuery(\''.$MWXS_L->escape($item).' th\').each(function(){
					if(jQuery(this).hasClass(\'mwxs_tsns\')){
						jQuery(this).css(\'cursor\',\'context-menu\');
						jQuery(this).attr("disabled", true);
						return;
					}

					var sort_th_title = jQuery(this).attr(\'title\');
					if (sort_th_title == null){
						sort_th_title = \'\';
					}
					
					if(sort_th_title==\'\'){
						sort_th_title = jQuery(this).text();
					}
					
					sort_th_title = jQuery.trim(sort_th_title);				  
					if(sort_th_title!=\'\'){
						sort_th_title = \'Sort By \'+sort_th_title;
						jQuery(this).attr(\'title\',sort_th_title);
					}else{
						//jQuery(this).addClass(\'{sorter: false}\');
						jQuery(this).attr(\'data-sorter\',\'false\');
						jQuery(this).attr(\'data-filter\',\'false\');
					}				  
				});
				
				jQuery(\''.$MWXS_L->escape($item).'\').tablesorter();
			});
		</script>
		';		
	}
}

if(!function_exists('myworks_woo_sync_for_xero_get_log_chart_output')){
	function myworks_woo_sync_for_xero_get_log_chart_output($viewPeriod=''){
		global $MWXS_L;
		$data = $MWXS_L->get_log_chart_data();
		//$MWXS_L->_p($data);
		if (!in_array($viewPeriod, array('today', 'month', 'year'))) {
			$viewPeriod = 'today';
		}
		
		$invoiceData = (isset($data['invoices']['total'][$viewPeriod]))?$data['invoices']['total'][$viewPeriod]:array();
		$clientData = (isset($data['clients']['total'][$viewPeriod]))?$data['clients']['total'][$viewPeriod]:array();
		//$errorData = (isset($data['errors']['total'][$viewPeriod]))?$data['errors']['total'][$viewPeriod]:array();

		$paymentData = (isset($data['payments']['total'][$viewPeriod]))?$data['payments']['total'][$viewPeriod]:array();
		
		$productData = (isset($data['products']['total'][$viewPeriod]))?$data['products']['total'][$viewPeriod]:array();
		//$depositData = (isset($data['deposits']['total'][$viewPeriod]))?$data['deposits']['total'][$viewPeriod]:array();

		if ($viewPeriod == 'today') {
			$graphLabels = array();
			$graphDataInv = array();
			$graphDataCus = array();
			//$graphDataErr = array();
			$graphDataPmnt = array();
			$graphDataPrdt = array();
			$graphDataDpst = array();

			for ($i = 0; $i <= date("H"); $i++) {
				$graphLabels[] = date("ga", mktime($i, date("i"), date("s"), date("m"), date("d"), date("Y")));
				$graphDataInv[] = isset($invoiceData[$i]) ? $invoiceData[$i] : 0;
				$graphDataCus[] = isset($clientData[$i]) ? $clientData[$i] : 0;
				//$graphDataErr[] = isset($errorData[$i]) ? $errorData[$i] : 0;
				
				$graphDataPmnt[] = isset($paymentData[$i]) ? $paymentData[$i] : 0;

				$graphDataPrdt[] = isset($productData[$i]) ? $productData[$i] : 0;
				$graphDataDpst[] = isset($depositData[$i]) ? $depositData[$i] : 0;
			}

		} elseif ($viewPeriod == 'month') {
			$graphLabels = array();
			$graphDataInv = array();
			$graphDataCus = array();
			//$graphDataErr = array();

			$graphDataPmnt = array();
			$graphDataPrdt = array();
			$graphDataDpst = array();
			
			for ($i = 0; $i < 30; $i++) {
				$time = mktime(0, 0, 0, date("m"), date("d") - $i, date("Y"));
				$graphLabels[] = date("jS", $time);
				$graphDataInv[] = isset($invoiceData[date("j F", $time)]) ? $invoiceData[date("j F", $time)] : 0;
				$graphDataCus[] = isset($clientData[date("j F", $time)]) ? $clientData[date("j F", $time)] : 0;
				//$graphDataErr[] = isset($errorData[date("j F", $time)]) ? $errorData[date("j F", $time)] : 0;
				
				$graphDataPmnt[] = isset($paymentData[date("j F", $time)]) ? $paymentData[date("j F", $time)] : 0;

				$graphDataPrdt[] = isset($productData[date("j F", $time)]) ? $productData[date("j F", $time)] : 0;
				$graphDataDpst[] = isset($depositData[date("j F", $time)]) ? $depositData[date("j F", $time)] : 0;
			}

			$graphLabels = array_reverse($graphLabels);

			$graphDataInv = array_reverse($graphDataInv);
			$graphDataCus = array_reverse($graphDataCus);
			//$graphDataErr = array_reverse($graphDataErr);
			
			$graphDataPmnt = array_reverse($graphDataPmnt);

			$graphDataPrdt = array_reverse($graphDataPrdt);
			$graphDataDpst = array_reverse($graphDataDpst);

		} elseif ($viewPeriod == 'year') {

			$graphLabels = array();

			$graphDataInv = array();
			$graphDataCus = array();
			//$graphDataErr = array();
			
			$graphDataPmnt = array();
			$graphDataPrdt = array();
			$graphDataDpst = array();

			for ($i = 0; $i < 12; $i++) {
				$time = mktime(0, 0, 0, date("m") - $i, 1, date("Y"));
				$graphLabels[] = date("F y", $time);
				$graphDataInv[] = isset($invoiceData[date("F Y", $time)]) ? $invoiceData[date("F Y", $time)] : 0;
				$graphDataCus[] = isset($clientData[date("F Y", $time)]) ? $clientData[date("F Y", $time)] : 0;
				//$graphDataErr[] = isset($errorData[date("F Y", $time)]) ? $errorData[date("F Y", $time)] : 0;
				
				$graphDataPmnt[] = isset($paymentData[date("F Y", $time)]) ? $paymentData[date("F Y", $time)] : 0;
				$graphDataPrdt[] = isset($productData[date("F Y", $time)]) ? $productData[date("F Y", $time)] : 0;
				$graphDataDpst[] = isset($depositData[date("F Y", $time)]) ? $depositData[date("F Y", $time)] : 0;
			}

			$graphLabels = array_reverse($graphLabels);

			$graphDataInv = array_reverse($graphDataInv);
			$graphDataCus = array_reverse($graphDataCus);
			//$graphDataErr = array_reverse($graphDataErr);

			$graphDataPmnt = array_reverse($graphDataPmnt);
			$graphDataPrdt = array_reverse($graphDataPrdt);
			$graphDataDpst = array_reverse($graphDataDpst);

		}
		
		$graphLabels = '"' . implode('","', $MWXS_L->array_sanitize($graphLabels)) . '"';
		$graphDataInv = implode(',', $graphDataInv);
		$graphDataCus = implode(',', $graphDataCus);
		//$graphDataErr = implode(',', $graphDataErr);

		$graphDataPmnt = implode(',', $graphDataPmnt);
		$graphDataPrdt = implode(',', $graphDataPrdt);
		$graphDataDpst = implode(',', $graphDataDpst);

		$activeToday = ($viewPeriod == 'today') ? ' active' : '';
		$activeThisMonth = ($viewPeriod == 'month') ? ' active' : '';
		$activeThisYear = ($viewPeriod == 'year') ? ' active' : '';

		#colors
		$client_bg_color_rgb = '220,220,220,0.5';
		$client_border_color_rgb = '220,220,220,1';
		$client_point_bg_color_rgb = '220,220,220,1';
		$client_point_border_color = '#fff';
		
		$payment_bg_color_rgb = '66, 134, 244, 0.5';
		$payment_border_color_rgb = '66, 134, 244, 1';
		$payment_point_bg_color_rgb = '66, 134, 244, 1';
		$payment_point_border_color = '#fff';

		$deposit_bg_color_rgb = '66, 238, 244, 0.5';
		$deposit_border_color_rgb = '66, 238, 244, 1';
		$deposit_point_bg_color_rgb = '66, 238, 244, 1';
		$deposit_point_border_color = '#fff';

		$product_bg_color_rgb = '232, 163, 2, 0.5';
		$product_border_color_rgb = '232, 163, 2,1';
		$product_point_bg_color_rgb = '232, 163, 2, 1';
		$product_point_border_color = '#fff';

		$help_txt = __('Click on colors or labels for enable/disable','myworks-sync-for-xero');		
		
		echo '
		<div style="padding:20px;">
			<div class="btn-group btn-group-sm btn-period-chooser" role="group" aria-label="...">
				<button type="button" class="btn btn-default'.esc_attr($activeToday).'" data-period="today">Today</button>
				<button type="button" class="btn btn-default'.esc_attr($activeThisMonth).'" data-period="month">This Month</button>
				<button type="button" class="btn btn-default'.esc_attr($activeThisYear).'" data-period="year">This Year</button>
			</div>
			<p>'.$MWXS_L->escape($help_txt).'</p>
		</div>
		
		<div style="width:100%;height:450px;">
			<div id="ChartParent_MWQS">
				<canvas id="Chart_MWQS" height="400"></canvas>
			</div>
		</div>
		
		<script>
			jQuery(document).ready(function($) {
				$(\'.btn-period-chooser button\').click(function() {
					$(\'.btn-period-chooser button\').removeClass(\'active\');
					$(this).addClass(\'active\');
					var period = $(this).data(\'period\');
					mw_wc_qbo_sync_refresh_log_chart(period);
				});

				var lineData = {
					labels: ['.str_replace('&quot;','"',$MWXS_L->escape($graphLabels)).'],
					datasets: [
						{
							label: "Customer",
							backgroundColor: "rgba('.$MWXS_L->escape($client_bg_color_rgb).')",
							borderColor: "rgba('.$MWXS_L->escape($client_border_color_rgb).')",
							pointBackgroundColor: "rgba('.$MWXS_L->escape($client_point_bg_color_rgb).')",
							pointBorderColor: "'.$MWXS_L->escape($client_point_border_color).'",
							fill: true,
							data: ['.$MWXS_L->escape($graphDataCus).']
						},
						{
							label: "Order",
							backgroundColor: "rgba(93,197,96,0.5)",
							borderColor: "rgba(93,197,96,1)",
							pointBackgroundColor: "rgba(93,197,96,1)",
							pointBorderColor: "#fff",
							fill: true,
							data: ['.$MWXS_L->escape($graphDataInv).']
						},
						{
							label: "Payment",
							backgroundColor: "rgba('.$MWXS_L->escape($payment_bg_color_rgb).')",
							borderColor: "rgba('.$MWXS_L->escape($payment_border_color_rgb).')",
							pointBackgroundColor: "rgba('.$MWXS_L->escape($payment_point_bg_color_rgb).')",
							pointBorderColor: "'.$MWXS_L->escape($payment_point_border_color).'",
							fill: true,
							data: ['.$MWXS_L->escape($graphDataPmnt).']
						},
						/*
						{
							label: "Deposit",
							backgroundColor: "rgba('.$MWXS_L->escape($deposit_bg_color_rgb).')",
							borderColor: "rgba('.$MWXS_L->escape($deposit_border_color_rgb).')",
							pointBackgroundColor: "rgba('.$MWXS_L->escape($deposit_point_bg_color_rgb).')",
							pointBorderColor: "'.$MWXS_L->escape($deposit_point_border_color).'",
							fill: true,
							data: ['.$MWXS_L->escape($graphDataDpst).']
						},
						*/
						{
							label: "Product / Variation",
							backgroundColor: "rgba('.$MWXS_L->escape($product_bg_color_rgb).')",
							borderColor: "rgba('.$MWXS_L->escape($product_border_color_rgb).')",
							pointBackgroundColor: "rgba('.$MWXS_L->escape($product_point_bg_color_rgb).')",
							pointBorderColor: "'.$MWXS_L->escape($product_point_border_color).'",
							fill: true,
							data: ['.$MWXS_L->escape($graphDataPrdt).']
						},

					]
				};

				var canvas = document.getElementById("Chart_MWQS");
				var parent = document.getElementById(\'ChartParent_MWQS\');

				canvas.width = parent.offsetWidth;
				canvas.height = parent.offsetHeight;

				var ctx = $("#Chart_MWQS");
				
				var options = {
					responsive: true,
					maintainAspectRatio: false,					
					scales: {
							y:{																
								beginAtZero: true,								
								ticks: {									
									precision: 0,									
								}
							}
					},
					elements: {
						line: {
							tension : 0.4  // smooth lines
						},
					},
				}
				
				new Chart(ctx, {
					type: \'line\',
					data: lineData,
					options: options
				});
				
			});
		</script>
		';		
	}
}

#Admin Page
if(!function_exists('myworks_woo_sync_for_xero_filter_reset_show_entries_html')){
	function myworks_woo_sync_for_xero_filter_reset_show_entries_html($page_url,$items_per_page){
		global $MWXS_L;
		
		$fb_t = __('Filter', 'myworks-sync-for-xero');
		$rb_t = __('Reset', 'myworks-sync-for-xero');
		$se_t = __('Show entries', 'myworks-sync-for-xero');
		
		echo '&nbsp;';
		echo '<button onclick="javascript:search_item();" class="btn btn-info">'.$MWXS_L->escape($fb_t).'</button>';
		echo '&nbsp;';
		echo '<button onclick="javascript:reset_item();" class="btn btn-info btn-reset">'.$MWXS_L->escape($rb_t).'</button>';
		echo '&nbsp;';		
		echo '<span class="filter-right-sec">';
		echo '<span class="entries">'.$MWXS_L->escape($se_t).'</span>';
		echo '&nbsp;';
		echo '<select style="width:50px;" onchange="javascript:window.location=\''.esc_url_raw($page_url).'&'.$MWXS_L->escape($MWXS_L->per_page_keyword).'=\'+this.value;">';
		$MWXS_L->only_option($items_per_page,$MWXS_L->show_per_page);
		echo '</select>';
		echo '</span>';
	}
}

# Settings Field
if(!function_exists('myworks_woo_sync_for_xero_g_settings_field')){
	function myworks_woo_sync_for_xero_g_settings_field($sf_type,$sf_data_arr){
		global $MWXS_L;
		$fnp = $MWXS_L->get_s_o_p();
		$f_name = $sf_data_arr['name'];
		$f_name = $fnp.$f_name;
		
		$f_id = $f_name;		
		
		$f_val = $MWXS_L->get_option($f_name);
		if(isset($sf_data_arr['d_val']) && empty($f_val)){
			$f_val = $sf_data_arr['d_val'];
		}
		
		echo '<th class="title-description">';
			echo $MWXS_L->escape($sf_data_arr['f_title']);
		echo '</th>';
		
		echo '<td>';
			echo '<div class="row">';
				echo '<div class="input-field col s12 m12 l12">';					
					if($sf_type == 'option_check'){
						if($f_val == 'check_if_empty'){
							$o_chkd = ' checked';
						}else{
							$o_chkd = ($MWXS_L->option_checked($f_name))?' checked':'';
						}
						
						echo '<p>';
							echo '<input type="checkbox" class="filled-in mwqs_st_chk  production-option" name="'.esc_attr($f_name).'" id="'.esc_attr($f_id).'" value="true"'.esc_attr($o_chkd).'>';
						echo '</p>';
					}
					
					if($sf_type == 'textbox'){
						echo '<input type="text" name="'.esc_attr($f_name).'" id="'.esc_attr($f_id).'" value="'.esc_attr($f_val).'">';
					}
					
					if($sf_type == 'textarea'){
						#cols="50" rows=""						
						echo '<textarea name="'.esc_attr($f_name).'" id="'.esc_attr($f_id).'">'.esc_textarea($f_val).'</textarea>';
					}
					
					if($sf_type == 'select'){
						$s_f_name = $f_name;
						$s_s_val = $f_val;
						
						$s_ext_class = '';
						$is_ajax_dd = false;
						if(isset($sf_data_arr['ajax_dd']) && $sf_data_arr['ajax_dd'] && $MWXS_L->is_s2_ajax_dd()){
							$is_ajax_dd = true;
							$s_ext_class = ' mwqs_dynamic_select';
						}
						
						$s_multiple = '';
						if(isset($sf_data_arr['multiple_select']) && $sf_data_arr['multiple_select']){
							$s_multiple = ' multiple';
							$s_ext_class .= ' mqs_multi';
							
							$s_f_name .= '[]';
							
							if(!empty($s_s_val)){
								$s_s_val = explode(',',$s_s_val);
							}
						}
						
						echo '<select name="'.esc_attr($s_f_name).'" id="'.esc_attr($f_id).'" class="filled-in production-option mw_wc_qbo_sync_select2'.esc_attr($s_ext_class).'"'.esc_attr($s_multiple).'>';
							if($is_ajax_dd){
								if(!empty($f_val)){
									$a_d_d_t = isset($sf_data_arr['a_d_d_t'])?$sf_data_arr['a_d_d_t']:'';
									$a_d_otd = '-';
									
									if($a_d_d_t == 'xero_product'){
										$a_d_otd = $MWXS_L->get_field_by_val($MWXS_L->gdtn('products'),'Name','ItemID',$f_val);
									}
									
									if($a_d_d_t == 'xero_customer'){
										$a_d_otd = $MWXS_L->get_field_by_val($MWXS_L->gdtn('customers'),'Name','ContactID',$f_val);
									}
									
									echo '<option value="'.esc_attr($f_val).'">'.stripslashes($MWXS_L->escape($a_d_otd)).'</option>';
								}
							}else{
								if(isset($sf_data_arr['s_blank_option']) && $sf_data_arr['s_blank_option']){
									echo '<option value=""></option>';
								}
								
								if($sf_data_arr['s_data_src'] == 'Array'){
									$MWXS_L->only_option($s_s_val,$sf_data_arr['s_data_arr']);
								}
								
								if($sf_data_arr['s_data_src'] == 'Options'){
									# Removed
								}

								if($sf_data_arr['s_data_src'] == 'Options_Params' && isset($sf_data_arr['s_data_params']) && isset($sf_data_arr['s_data_function'])){
									if(is_array($sf_data_arr['s_data_params']) && !empty($sf_data_arr['s_data_params']) && $sf_data_arr['s_data_function']){
										$params = $sf_data_arr['s_data_params'];
										if($sf_data_arr['s_data_function'] == 'only_option'){
											if(!isset($params[5])){$params[5] = array();}
											$MWXS_L->only_option($params[0],$params[1],$params[2],$params[3],$params[4],$params[5]);
										}
										
										if($sf_data_arr['s_data_function'] == 'option_html'){
											$MWXS_L->option_html($params[0],$params[1],$params[2],$params[3],$params[4],$params[5],$params[6],$params[7]);
										}
									}
								}
							}							
						echo '</select>';
					}
					
				echo '</div>';
			echo '</div>';
		echo '</td>';
		
		echo '<td>';
			myworks_woo_sync_for_xero_set_tooltip($sf_data_arr['tt_text']);
		echo '</td>';
	}
}

# Compatibility page functions
if(!function_exists('myworks_woo_sync_for_xero_compt_page_option_check_f')){
	function myworks_woo_sync_for_xero_compt_page_option_check_f($f_name_id){
		global $MWXS_L;
		$fnp = $MWXS_L->get_s_o_p();	
		if(!empty($f_name_id) && !empty($fnp)){
			if(!$MWXS_L->start_with($val_field,$fnp)){
				$f_name_id = $fnp.$f_name_id;				
			}
			
			$f_id = $f_name_id;
			$o_chkd = ($MWXS_L->option_checked($f_name_id))?' checked':'';
			echo '<input type="checkbox" class="filled-in mwqs_st_chk  production-option" name="'.esc_attr($f_name_id).'" id="'.esc_attr($f_id).'" value="true"'.esc_attr($o_chkd).'>';
		}
	}
}