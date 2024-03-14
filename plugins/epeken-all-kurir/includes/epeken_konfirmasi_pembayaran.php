<?php

add_filter('woocommerce_my_account_my_orders_actions','epeken_add_konfirmasi_pembayaran_button',1,2);
function epeken_konfirmasi_pembayaran_popup() {
                        ?>
                        <div  id="div_epeken_popup">
                                        <p style='margin: 0 auto; text-align: center;padding-top: 5%;'>
                        <?php echo __("Give me one second",'epeken-all-kurir'); ?><br>
                        <img style="display: block; margin: 0 auto;" src='<?php echo plugins_url('../assets/ajax-loader.gif',__FILE__); ?>'>
                                        </p>
                        </div>
                        <?php
}
function epeken_add_konfirmasi_pembayaran_button($actions,$order) {
	global $wp, $wpdb;
	$table_prefix = $wpdb->prefix;
	$page_id_konfirmasi_pembayaran = $wpdb->get_var('SELECT ID FROM '.$table_prefix."posts WHERE post_content LIKE '%[epeken_konfirmasi_pembayaran%' AND post_parent = 0 limit 0,1");

	$setting = get_option('woocommerce_epeken_courier_settings');
	$enable_button_konfirmasi_pembayaran = $setting['enable_btn_konfirmasi_pembayaran'];
	$url_konfirmasi_pembayaran = $setting['url_konfirmasi_pembayaran'];

	if($enable_button_konfirmasi_pembayaran === 'no') {
		return $actions;
	}	
        $order_number = $order->get_order_number();
	$order = new WC_Order($order_number);
        if($order -> get_status() !== 'on-hold' && $order -> get_status() !== 'pending')
                return $actions;
        $action['name'] = 'Konfirmasi Pembayaran';
	
	if (!empty($url_konfirmasi_pembayaran))
	 	 $action['url'] = $url_konfirmasi_pembayaran;
	else
		 $action['url'] = get_permalink($page_id_konfirmasi_pembayaran).'?order_id='.$order_number;
	
        array_push($actions, $action);
        return $actions;
}

function epeken_konfirmasi_pembayaran_shortcode_details($atts) {
	global $wpdb;
	
	if(!empty($atts)) 
     	 $atts = array_change_key_case( (array) $atts, CASE_LOWER );
	
	if (is_multisite()) //doesn't support multisite
             return;
	
	ob_start();
	$message = '';
	$woo_session = WC() -> session;
	if (!empty($woo_session))
		$message = $woo_session -> get('result_msg');
	//$message = WC() -> session -> get('result_msg');
	$pesan = '';
	if(!empty($message)) {
		$class = "woocommerce-warning";
		if($message === 'Order number tidak ditemukan. Silakan mencoba lagi.' || $message === "Semua Field Harus Diisi, Tidak boleh kosong") {
			$class = "woocommerce-error";
			if($message === 'Order number tidak ditemukan. Silakan mencoba lagi.')
				$pesan = __('Order not found. Please try again.','epeken-all-kurir');
			elseif($message === 'Semua Field Harus Diisi, Tidak boleh kosong')
				$pesan = __('All fields are mandatory.','epeken-all-kurir');
		}
		if($class === "woocommerce-error") {
			  ?> 
			  <div class="<?php echo $class; ?>">
			  <?php
			    $table_prefix = $wpdb->prefix;
				$page_id_konfirmasi_pembayaran = $wpdb->get_var('SELECT ID FROM '.$table_prefix.'posts WHERE post_content LIKE "%[epeken_konfirmasi_pembayaran]%" AND post_parent = 0 limit 0,1');			  
				$url_kp = get_permalink($page_id_konfirmasi_pembayaran);
				echo $pesan; echo '<span style="position: relative;float: right;"><a href="'.$url_kp.'" class="button">'.__('Back to Payment Confirmation page.','epeken-all-kurir').'</a></span>'; 
			  ?>  
              </div>
              <?php   }else{
              ?>  
              <div class="<?php echo $class; ?>"><?php echo $message; echo '<span style="position: relative;float: right;"><a href="'.$url_kp.'" class="button">'.__('Back to Payment Confirmation page.','epeken-all-kurir').'</a></span>'; ?></div> 
              <?php   }
		WC() -> session -> set('result_msg', null); 
		return;
	}	
        $order_id = array_key_exists('order_id',$_GET) ? sanitize_text_field($_GET['order_id']) : '';
        $order = new WC_Order($order_id);

	epeken_konfirmasi_pembayaran_popup(); //popup message here.

        ?>
        <p style="margin-bottom: 20px;"><?php echo __('Please fill this form to make payment confirmation.','epeken-all-kurir');?></p>
		<div class="sepeken_form">
		<form enctype="multipart/form-data">
		<?php if(!empty($order_id)) { ?>
                <div class="sepeken_td_header">
                        <?php $label_order_id = __('Order Number','epeken-all-kurir'); 
			     echo $label_order_id.'(*)';
			?> 
                </div>
                <div class="sepeken_td">
                        <?php echo $order->get_order_number(); ?> | <a href="<?php echo $order->get_view_order_url(); ?>">View Order</a>
                        <input type="hidden" value="<?php echo $order->get_order_number(); ?>" name="orderid_pembayaran" id="orderid_pembayaran"/>
                </div>
		<?php }else{ ?>
		<div class="sepeken_td_header">
			<?php $label_order_id = __('Order Number','epeken-all-kurir');
                             echo $label_order_id.'(*)';
                        ?>
                </div>
		<div class="sepeken_td">	
			<input type="number" name="orderid_pembayaran" id="orderid_pembayaran"/>
		</div>
		<?php } ?>
                <div class="sepeken_td_header">
                        <?php echo __('Payment Date','epeken-all-kurir'); echo '(*)';?>
                </div>
                <div class="sepeken_td">
                        <input type="text" name="tgl_pembayaran" id="tgl_pembayaran"></input>
		<script type="text/javascript">
                jQuery(document).ready(function($){
                        $("#tgl_pembayaran").datepicker({
                                dateFormat: "dd-mm-yy",
                                onSelect: function(dateText, inst) {
                                        var date = $.datepicker.parseDate(inst.settings.dateFormat || $.datepicker._defaults.dateFormat, dateText, inst.settings);
                                        var dateText1 = $.datepicker.formatDate("d M yy", date, inst.settings);
                                        date.setDate(date.getDate() + 7);
                                        var dateText2 = $.datepicker.formatDate("D, d M yy", date, inst.settings);
                                        $("#tgl_pembayaran").val(dateText1);
                                }
                        });
                });
        </script>
                </div>
		<div style="clear: both; zoom: 1"></div>
                <div class="sepeken_td_header">
                        <?php echo __('Bank Account Name','epeken-all-kurir'); echo '(*)';?>
                </div>
                <div class="sepeken_td">
                        <input type="text" name="nama_pembayar" id="nama_pembayar"></input>
                </div>
                <div class="sepeken_td_header">
                        <?php echo __('Bank Name','epeken-all-kurir'); echo '(*)';?>
                </div>
                <div class="sepeken_td">	
					<?php if (is_array($atts) && array_key_exists('bank', $atts)) {
							$banks = explode(",",$atts['bank']);
							?>
							<select name="nama_bank" id="nama_bank">
							<?php foreach($banks as $bank) {
								echo "<option value='".$bank."'>".$bank."</option>";
							}?>
							</select>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									$('#nama_bank').select2();
								});
							</script>
							<?php
					} else {?>
                        <input type="text" name="nama_bank" id="nama_bank"></input>
					<?php } ?>
                </div>
		<div class="sepeken_td_header">
                        <?php echo __('Transfer Amount','epeken-all-kurir'); echo '(*)';?>
                </div>
                <div class="sepeken_td">
                        <input type="number" name="transfer_amount" id="transfer_amount"></input>
                </div>
                <div class="sepeken_td_header">
                        <?php echo __('Notes','epeken-all-kurir');?>
                </div>
                <div class="sepeken_td">
                        <textarea style="height: 100px;" name="notes_pembayaran" id="notes_pembayaran" ></textarea>
                </div>
				<div class="sepeken_td_header">
					<div style="margin-top: 20px">
					<?php echo __('Transfer Receipt','epeken-all-kurir');?>
					</div>
				</div>
				<div class="sepeken_td">
						<input type="file" id="imgbuktitransfer" name="var_imgbuktitransfer">
				</div>
                <div class="sepeken_td_header">
                        &nbsp;
                </div>
                <div class="sepeken_td">
                        <button id="submit_konfirmasi">Submit</button>
                </div>
		</form>
        </div>
		<script type="text/javascript">
				jQuery(document).ready(function($){
							   konfirmasi_pembayaran();
				});
        </script>
        <?php
	$final = ob_get_clean();
	return $final;
}

add_shortcode('epeken_konfirmasi_pembayaran', 'epeken_konfirmasi_pembayaran_shortcode_details');
add_action('wp_ajax_submit_konfirmasi_pembayaran','epeken_submit_konfirmasi_pembayaran');
add_action('wp_ajax_nopriv_submit_konfirmasi_pembayaran','epeken_submit_konfirmasi_pembayaran');

$epeken_upload_dir = '/epeken';
function epeken_konfirmasi_pembayaran_dir($dir) {
	global $epeken_upload_dir;
	return array(
          'path'   => $dir['basedir'] . $epeken_upload_dir,
          'url'    => $dir['baseurl'] . $epeken_upload_dir,
          'subdir' => $epeken_upload_dir,
    	) + $dir;	
}

function epeken_submit_konfirmasi_pembayaran() {
	global $epeken_upload_dir;
	$tmpdir = WP_CONTENT_DIR.'/uploads'.$epeken_upload_dir;
	$logger = new WC_Logger();
	wp_mkdir_p($tmpdir);

	$order_id = sanitize_text_field($_POST['orderid']);
	$tgl_pembayaran = sanitize_text_field($_POST['tglpembayaran']);
	$nama_pembayar = sanitize_text_field($_POST['namapembayar']);
	$nama_bank = sanitize_text_field($_POST['namabank']);    
	$transfer_amount = sanitize_text_field($_POST['transferamount']);
	$notes_pembayaran = sanitize_text_field($_POST['notespembayaran']);
	$nextNonce = sanitize_text_field($_POST['nextNonce']);

	if(!wp_verify_nonce($nextNonce,'epeken-konfirmasi-pembayaran')){
		die('Invalid Invocation');
	}     
	
	if (!function_exists('wp_handle_upload')) {
	   require_once(ABSPATH . 'wp-admin/includes/file.php');
	}
	
	$uploadedfile = $_FILES['file'];
    	$upload_overrides = array('test_form' => false);
	
	add_filter('upload_dir', 'epeken_konfirmasi_pembayaran_dir');	
	$movefile = wp_handle_upload($uploadedfile, $upload_overrides);
	remove_filter('upload_dir', 'epeken_konfirmasi_pembayaran_dir');

	$order_factory = new WC_Order_Factory();
    	$order = $order_factory -> get_order((int)$order_id);
	$is_payment_confirmed = get_post_meta($order_id,'payment_confirmation', true);
     	if ($movefile && !isset($movefile['error'])) {
		 if($order == false) {
			 WC() -> session -> set ('result_msg', 'Order number tidak ditemukan. Silakan mencoba lagi.');
			 return;
		 }
		 
		 echo __('Transfer receipt was uploaded successfully.','epeken-all-kurir'); echo '<br>';
		 update_post_meta($order_id, 'url_bukti_upload', $movefile['url']);
		 $note = "Customer telah mengupload bukti pembayaran sebagai berikut:";
		 if($is_payment_confirmed === 'yes'){
			 $note = "Customer memperbarui bukti pembayaran sebagai berikut:";
		 }
		 $string_note = 
		 $note.'<br>
		 <a href="'.$movefile['url'].'" target="_blank">
		 <img style="width: 100%;height: auto;" src="'.$movefile['url'].'"/>
		 </a>';
		$order->add_order_note($string_note);
		$options = get_option('woocommerce_epeken_courier_settings');
		$option_is_processing_after_konfirmasi = $options['epeken_processing_order_status_after_konfirmasi'];
		
		echo 'Order #'.$order_id.__(' is declared as PAID by customer. Your order will be processed soon. Thank you.','epeken-all-kurir');
		WC() -> session -> set('result_msg', 'Konfirmasi pembayaran sudah dilakukan untuk Pesanan #'.$order_id.'. Kami akan segera memproses pesanan Anda. Terima kasih.');
		update_post_meta($order_id, 'payment_confirmation', 'yes');
		update_post_meta($order_id, 'tanggal_pembayaran', $tgl_pembayaran);
		update_post_meta($order_id, 'nama_pembayar', $nama_pembayar);
		update_post_meta($order_id, 'bank_pembayar', $nama_bank);
		update_post_meta($order_id, 'notes_konfirmasi', $notes_pembayaran);
		update_post_meta($order_id, 'transfer_amount', $transfer_amount);
		do_action('epeken_after_konfirmasi_pembayaran', $order);

		try{
		 if($option_is_processing_after_konfirmasi === 'yes') {
         	  $order -> update_status('processing');
	 	  epeken_send_email_konfirmasi_pembayaran($order,$tgl_pembayaran, $nama_pembayar,$nama_bank,$transfer_amount,$notes_pembayaran,false);
        	 }else{
		  epeken_send_email_konfirmasi_pembayaran($order,$tgl_pembayaran, $nama_pembayar,$nama_bank,$transfer_amount,$notes_pembayaran,true);
		 }
		}catch(Exception $e){
		  $logger ->  add('epeken-all-kurir', 'Error when sending email: '.$e->getMessage());
		}
		return;
    	} else {
	      echo __("You did not attach your transfer receipt. Please try again and attach your transfer receipt.","epeken-all-kurir");
	      WC() -> session -> set('result_msg','Wajib upload bukti pembayaran. Mohon coba kembali. Terima kasih.');
	      return;
    	}
	if (empty($order_id) || empty($tgl_pembayaran) || empty ($nama_pembayar) || empty($nama_bank) || empty($transfer_amount)) {
	      echo __("All fields are mandatory.",'epeken-all-kurir');
	      WC() -> session -> set('result_msg', 'Semua Field Harus Diisi, Tidak boleh kosong');
		return;
	}      
	if ($order == false) {
		echo "Order ".$order_id.__(" not found",'epeken-all-kurir');
		WC() -> session -> set('result_msg',"Order ".$order_id." tidak ditemukan"); 
		return;
	}
	if($is_payment_confirmed === 'yes')
	{
		echo __("You have confirmed this order payment. We are processing this order. Thank you.", 'epeken-all-kurir');
		WC() -> session -> set ('result_msg','konfirmasi pembayaran sudah dilakukan untuk order ini. Kami akan segera memproses order ini. Terima kasih.');
		return;
	}
	if(!empty($order->post)) {
        	$string_note = "Konfirmasi Pembayaran dengan Detail : Order #".$order_id." pada tanggal ".$tgl_pembayaran.", dari pemilik rekening dengan nama: ". $nama_pembayar.", rekening bank ".$nama_bank.", notes: ".$notes_pembayaran.' Mohon memeriksa kembali rekening pembayaran sudah masuk ke rekening sebelum melakukan pemrosesan order atau pengiriman barang. Silakan hubungi Admin jika membutuhkan bantuan.';
        	$order->add_order_note($string_note);
	}else {
		//echo __("Order is not found. Please try again with valid order ID.",'epeken-all-kurir');
		WC() -> session -> set('result_msg','Order number tidak ditemukan. Silakan mencoba lagi.');
		return;
	}
}

function epeken_body_email_konfirmasi_pembayaran($order_id, $tgl_pembayaran, $nama_pembayar,$nama_bank,$transfer_amount, $notes_pembayaran) {
	$url_konfirmasi_pembayaran = get_post_meta($order_id,'url_bukti_upload', true);
	$tmpar = explode("/",$url_konfirmasi_pembayaran);
	$f = end($tmpar);
	
	$tmpar = explode(".",$f);
	$e = strtolower(end($tmpar));
	
	$info_konfirmasi = "";
	if(in_array($e,array('jpg','jpeg','png','gif'))){
		$info_konfirmasi = "<p><img src='".$url_konfirmasi_pembayaran."' /></p>";
	}else{
		$info_konfirmasi = " <a href='".$url_konfirmasi_pembayaran."' target='_blank' style='width:40% !important;'>Download Bukti Pembayaran</a>";
	}
	$judul = __('Order','epeken_all_kurir').' #'.$order_id.' '.__('Payment Confirmation','epeken-all-kurir'); 
	$content = '<p style="text-align: justify" align="justify"><span style="font-family: \'trebuchet ms\', geneva, sans-serif">'.__('Dear Customer,','epeken-all-kurir').' ,</span></p>
		<p style="text-align: justify"><span style="font-family: \'trebuchet ms\', geneva, sans-serif">Order #'.$order_id.__(' is declared as PAID. We will check and process this order soon.','epeken-all-kurir').'</span></p>
		<p style="text-align: justify"><span style="font-family: \'trebuchet ms\', geneva, sans-serif">
		<strong>Details</strong><br>
		'.__('Order Number','epeken-all-kurir').': '.$order_id.'<br>
		'.__('Payment Date','epeken-all-kurir').': '.$tgl_pembayaran.'<br>
		'.__('Bank Account Name','epeken-all-kurir').': '.$nama_pembayar.'<br>
		'.__('Bank Name','epeken-all-kurir').': '.$nama_bank.'<br>
		'.__('Transfer Amount','epeken-all-kurir').': '.$transfer_amount.'<br>
		'.__('Notes','epeken-all-kurir').': '.$notes_pembayaran.'<br>
		'.__('Payment Receipt','epeken-all-kurir').': '.$info_konfirmasi.'<br>
		</p>
		<p style="text-align: justify" align="justify"><span style="font-family: \'trebuchet ms\', geneva, sans-serif">'.__('Thank you,','epeken-all-kurir').'</span></p>';
	$email_script = epeken_email_template_html($judul, $content);
	return $email_script;
}
function epeken_send_email_konfirmasi_pembayaran($order, $tgl_pembayaran, $nama_pembayar,$nama_bank,$transfer_amount,$notes_pembayaran, $is_to_admin=true) {
		$order_id = $order -> id;
		$to = '';
		$email = $order -> billing_email;
		$email_from = get_option('woocommerce_email_from_address');
		$email_korespondensi = get_option('epeken_email_korespondensi');
		$first_name = $order -> billing_first_name;
		$body_email = epeken_body_email_konfirmasi_pembayaran($order->ID,$tgl_pembayaran, $nama_pembayar,$nama_bank,$transfer_amount,$notes_pembayaran);
		if(empty($first_name)) {$first_name = $order -> shipping_first_name;}
		if (!empty($email)) {
		 $to = $email;
		 $admin_email = get_option('admin_email');
		if($is_to_admin) {
		 $to = $admin_email;
		}
		 $subject = 'Order #'.$order->ID.' '.__('Payment Confirmation','epeken-all-kurir');
		 $headers  = "From: ".$email_from."\r\n";
		 if(trim($email_korespondensi) !== $to)
		 	$headers .= "Bcc: ".$email_korespondensi."\r\n";
		 $headers .= "Reply-To: ".$email_from."\r\n";
		 $headers .= "MIME-Version: 1.0\r\n";
		 $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		 wc_mail($to, $subject, $body_email, $headers);
		}   
}
?>
