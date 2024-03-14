<?php
	$username = smsalert_get_option('smsalert_name', 'smsalert_gateway');
	$password = smsalert_get_option('smsalert_password', 'smsalert_gateway');
	$result    = SmsAlertcURLOTP::getTemplates( $username, $password );
	$templates = (array)json_decode( $result, true );
	$result = SmsAlertcURLOTP::getSenderids($username, $password);
	$senderids = json_decode($result, true);
	$result = json_decode(SmsAlertcURLOTP::getCredits(), true);
	$routes = ($result['description']['routes']);
	$phone = array();
	$tokens = array();
	$count = 0;
	$phone_data = '';
	$search_hide = 'display:none;';
	$send_hide = 'display:block;';
	if ($type == 'order_status_data'){
		$tokens = WooCommerceCheckOutForm::getOrderVariables();
		$search_hide = 'display:block;';
		$send_hide = 'display:none;';
	}
	global $wpdb;
	if ( ! empty( $post_ids ) ) {
	foreach ( $post_ids as $key => $post_id ) {
		if ($type == 'orders_data'){
			$tokens = WooCommerceCheckOutForm::getOrderVariables();
			if ( version_compare( WC_VERSION, '7.1', '<' ) ) {
			   $user_phone = get_post_meta( $post_id, '_billing_phone', true );
			} else {
				$order    = wc_get_order($post_id);
				$user_phone  = $order->get_meta('_billing_phone'); 
			}
			$phone[] =$user_phone;
		}
		elseif($type == 'users_data')
		{
			$tokens = array(
			'[username]'      => 'Username',
			'[store_name]'    => 'Store Name',
			'[email]'         => 'Email',
			'[billing_phone]' => 'Billing Phone',
			'[shop_url]'      => 'Shop Url',
			);
			$user_phone = get_user_meta( $post_id, 'billing_phone', true ); 
			$phone[] =$user_phone;
		} 
		elseif($type == 'abandoned_data')
		{
			$tokens = SA_Abandoned_Cart::getAbandonCartvariables();
			$table_name = $wpdb->prefix . SA_CART_TABLE_NAME;
			$results=$wpdb->get_row("SELECT * FROM $table_name WHERE id = $post_id ", ARRAY_A );
			$phone[] =$results['phone'];
		}
		elseif($type == 'subscribe_data')
		{  
		    $tokens = array(
			'[item_name]'       => 'Product Name',
			'[name]'            => 'Name',
			'[subscribed_date]' => 'Date',
			'[product_url]'     => 'Product Url',
			'[store_name]'      => 'Store Name',
			'[shop_url]'        => 'Shop Url',
			);
			global $wpdb;
			$sql = "SELECT  P.post_title, P.post_status,P.post_content, PM.meta_value FROM {$wpdb->prefix}posts P inner join {$wpdb->prefix}postmeta PM on P.ID = PM.post_id WHERE id = $post_id";
			$results = $wpdb->get_row( $sql, 'ARRAY_A');
			$phone[] = $results['post_title'];
		} 
	}
	$phone_data = implode(',', $phone);
	$count = count($phone);
  
} 
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<style>
#sendbox_section select,#sendbox_section input,#sendbox_section textarea{padding:8px;width:50%;margin-top:5px;}
.sarow button{
	padding: 6px 10px;
    color: #ffff;
    font: inherit;
    background-color: #2271B1;
    border: 1px solid #F0F0F1;
    border-radius: 5px;
    cursor: pointer;
	}
</style>
    <div class="container">
        <h1 >SMS CAMPAIGN</h1>
		<div id="search_section" style="<?php echo $search_hide; ?>">
       <p class="sarow">
        <label>Select Order Status<br>
            <select  name="order_statuses[]" size="10" data-placeholder="Choose Order Status"  id="order_statuses" multiple class="chosen-select">
            <?php 
               $order_statuses = is_plugin_active( 'woocommerce/woocommerce.php' ) ? wc_get_order_statuses() : array();
               foreach ( $order_statuses as $vs  => $order_status ) {
            ?>
            <option value="<?php echo strtolower( $vs );?>"><?php echo  $order_status;?></option>
            <?php
            }
			?>
         </select>
         </label>
        </p>
        <p class="sarow">
              <button type="button" onclick="search_data()" id="btn">SearchData</button>
        </p>
        </div>
        <!-- SEARCH DATA -->
        <div id="send_section" style="<?php echo $send_hide; ?>">
			<div style="display:flex">
			<h3>Total record : <span class="trecord">0</span></h3>
			<a href="#" style="margin-top:20px; margin-left:10px; display:none;" onclick="resetSearch()" id="resetsearch"> modify search </a>				
			 </div>
			<div  id="sendbox_section">
				<p class="sarow">
					<label>
					Senderid:<br>
					<select id="senderid">
                   <?php
					if(!empty($senderids['status']) && $senderids['status'] == 'success')
					{
                        foreach ($senderids['description'] as $key => $senderid) {
							?>
                            <option value="<?php  echo $senderid['Senderid']['sender'];?>"><?php  echo $senderid['Senderid']['sender'];?></option>
                           
		            <?php 
					     }
					}
					else
					{
					?>
				<option value="ESTORE">ESTORE</option>  
					<?php
					}
					?>
                        </select>
                    </label>
                    </p>
                    <?php 
                    if (count($routes)>1)
					{?>
                    <p class="sarow">
                        <label>
                        Route:<br>
                        <select id="route">
                        
                        <?php
                        foreach ($routes as $key => $route) {
						?>
                        <option value="<?php echo $route['route'];?>"><?php echo $route['route'];?></option>
                        <?php  
					    }
					    ?> 
                        </select>
                    </label>
                    </p>
                    <?php
                    }
					if(!empty($templates['status']) && $templates['status'] == 'success')
					{
					?>
                    <p class="sarow">
                        <label>
                        Template:<br>
                    <select name="templates" id="template" onchange="return selecttemplate(this);">
                    <option value="" disabled selected>Select Template...</option>
                    <?php foreach ( $templates['description'] as $template ){
				    ?>
                <option value="<?php echo esc_textarea( $template['Smstemplate']['template'] ); ?>"><?php echo esc_attr( $template['Smstemplate']['title'] ); ?></option>
			   <?php
			   }
			   ?>			
				</select>   
                </label>
                </p>
              <?php
			   }
			   ?>
                    <p class="sarow">
						<div class="smsalert_tokens">
						<?php
						foreach ( $tokens as $vk => $vv ) {
							echo  "<a href='#' onclick='return false;' data-val='".esc_attr($vk)."'>".esc_attr($vv)."</a> | ";
						}
						?>
                        </div>
                        <textarea name="message" id="sa_message" rows="5" cols="40" placeholder="Message"></textarea>
                    </p>
                    <p class="sarow">
                      <button type="button" id="send_sms"  onclick="sendsms()">Send SMS</button>
					  <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" style="margin-top:20px; margin-left:10px;"> Go Back</a>
                    </p>
                <div id="success_message">
                </div>
                <div id="error_message">
                 </div>              
                </div>
        </div>
		</div>
                <script>
                var phone = '<?php echo $phone_data; ?>';      
                var type = '<?php echo $type; ?>';      
                var post_ids = '<?php echo implode(',', $post_ids); ?>';      
                $('.trecord').text('(<?php echo $count; ?>)');
                function selecttemplate(obj) 
			    { 
					jQuery('#sa_message').val(obj.value);
			    }
				$(".chosen-select").chosen();
				
                function search_data(){
                    var order_statuses = $('#order_statuses').val();
                    $('#btn').html('Please Wait..');
                    jQuery.ajax({
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        type:'POST',
                        data:'action=process_campaign&order_statuses='+order_statuses+'&searchdata=',
                          
			             success : function(response) {
							$('#btn').html('SearchData');
							if(response>0) 
							{
                            $('.trecord').text(response);
                            $('#search_section').hide();
                            $('#resetsearch').show();
                            $('#send_section').show();
							}
                        }
                    });
                }
				
			    function resetSearch(){
				 $('#search_section').show();
				 $('#send_section').hide();
				 $('#resetsearch').hide();
				 $('.trecord').text(0);
                }

                function sendsms(){
                    var senderid = $('#senderid').val();
                    var route = ($('#route').val() != undefined)?$('#route').val():'';
                    var template = $('#template').val();
                    var message = $('#sa_message').val();
					var order_statuses = $('#order_statuses').val();
                    $('#send_sms').css('disabled',true).html('Please Wait...');
                    
                    jQuery.ajax({
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        type:'POST',
                        data:'action=process_campaign&senderid='+senderid+'&route='+route+'&message='+message+'&phone='+phone+'&type='+type+'&post_ids='+post_ids+'&order_statuses='+order_statuses,
			             success : function(response){
                            $('#send_sms').css('disabled',false).html('Send SMS');
                             if(response==1)
                             {
                                $('#success_message').html(' <h3>Message sent successfully!</h3>').fadeOut(3e3,function(){jQuery("#success_message").html("")});
                             }else{
                                $('#error_message').html(' <h3>Something went wrong!</h3>').fadeOut(3e3,function(){jQuery("#error_message").html("")});
                             }
                        },
                        
                    });
                }
		function insertAtCaret(e, t) {
			var s = document.getElementById(t);
			if (document.all)
				if (s.createTextRange && s.caretPos) {
					var i = s.caretPos;
					i.text = " " == i.text.charAt(i.text.length - 1) ? e + " " : e
				} else s.value = s.value + e;
			else if (s.setSelectionRange) {
				var r = s.selectionStart,
					o = s.selectionEnd,
					n = s.value.substring(0, r),
					l = s.value.substring(o);
				s.value = n + e + l
			} else alert("This version of Mozilla based browser does not support setSelectionRange")
        }		
        $(document).on("click",".smsalert_tokens a",function(){
			return insertAtCaret($(this).attr("data-val"),
			$("#sa_message").attr("id"));
		});
       		
    </script>