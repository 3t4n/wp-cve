<?php
function CPIW_ZipcodeValidatorAfterAddtoCart() {
global $cpiw_comman;  ?>
	<div class="cpiw_main" style="display: <?php if(isset($_COOKIE['Cpiw_Pincode'])  && $_COOKIE['Cpiw_Pincode'] != "no") { echo "none"; } else { echo "flex"; } ?>;background-color: <?php echo esc_attr($cpiw_comman['mainbackcolor']); ?>;">
		<div class="cpiw_inner_first">
	    	<input type="text" name="checkpincode" class="checkpincode" value="" style="" placeholder="<?php echo esc_attr($cpiw_comman['cpiw_pincodeplace_text']); ?>">
      		<input type="button" name="checkpincodebuttom" class="checkpincodebuttom" value="Check" style="color: <?php echo esc_attr($cpiw_comman['checkandchangetxtcolor']); ?>;background-color: <?php echo esc_attr($cpiw_comman['checkandchangebackcolor']); ?>;">
      	</div>
      	<div class="cpiw_main_inner">
	       <h3 style="color: <?php echo esc_attr($cpiw_comman['checkavailbilitycolor']); ?>"><?php echo esc_attr($cpiw_comman['cpiw_checkavail_text']); ?> <span class="Cpiw_avaicode"><?php if(isset($_COOKIE['Cpiw_Pincode']) && $_COOKIE['Cpiw_Pincode'] != "no") { echo 
	       	esc_attr($_COOKIE['Cpiw_Pincode']); } ?></span></h3>
		</div>
		<div class="cpiwc_maindiv_popup"></div>
		 <span class="wczp_empty"><?php echo esc_attr($cpiw_comman['cpiw_emptyfield_text']); ?></span>
	</div>
	<div class="cpiw_inner">
		<div class="cpiw_inner_inner">
			<?php    
                if(isset($_COOKIE['Cpiw_Pincode'])) {

                    $pincode = sanitize_text_field($_COOKIE['Cpiw_Pincode']);
                    $cpiw_record = CPIW_PincodeCheckInDataTable($pincode);
                    $cpiw_totalrecord = count($cpiw_record);
                 
                    if ($cpiw_totalrecord == 1) {
                    

                        $deltxt = "";

                        $date = $cpiw_record[0]->ddate;

                        $cod = $cpiw_record[0]->caseondilvery;

                        $deliverydate = CPIW_DeliveryDate($date);
	                        $showdate = $cpiw_comman['cpiw_dateshow'];
	                        $cpiw_cash_dilivery_shw = $cpiw_comman['cpiw_codshow'];
	                        $delivary_text= $cpiw_comman['cpiw_delivery_date_text'];
	                        
                    		$available_message1 = str_replace("{city_name}","<strong>".$cpiw_record[0]->city."</strong>","YES, We Deliver to {city_name}, {state_name}");
                          	$available_message2 = str_replace("{state_name}","<strong>".$cpiw_record[0]->state."</strong>",$available_message1);
                                        ?>
	                         <div class="pincode_city_and_state">
	                         	<p><?php echo str_replace("{state_name}","<strong>".esc_attr($cpiw_record[0]->state)."</strong>",$available_message1); ?></p>
	                         	 <input type="button" name="cpiwbtn" class="cpiwcheckbtn" value="Change" style="color: <?php echo esc_attr($cpiw_comman['checkandchangetxtcolor']); ?>; background-color: <?php echo esc_attr($cpiw_comman['checkandchangebackcolor']); ?>;">
	                         </div>
                        		<div class="inner" style="background-color: <?php echo esc_attr($cpiw_comman['mainbackcolor']); ?>;">
                        			<?php
                        		if(!empty($cpiw_comman['date_image'])){
				                    $del_avail_icon = wp_get_attachment_url($cpiw_comman['date_image']);
				                }else{
				                    $del_avail_icon = CPIW_PLUGIN_DIR . '/assets/image/schedule.png';
				                }
	                        if($cod == 1) {
	                            $cod_avail = 'Case On Delivery Available';
	                            if(!empty($cpiw_comman['cpiw_cashondeliimg_form'])){
				                        $cod_avail_icon = wp_get_attachment_url($cpiw_comman['cpiw_cashondeliimg_form']);
				                    }else{
				                         $cod_avail_icon = CPIW_PLUGIN_DIR . '/assets/image/money.png';
				                    }
	                        } else {
	                            $cod_avail = 'Case On Delivery Not Available';
                             	if(!empty($cpiw_comman['cpiw_cashondelinotimg_form'])){
			                        $cod_avail_icon = wp_get_attachment_url($cpiw_comman['cpiw_cashondelinotimg_form']);
			                    }else{
			                         $cod_avail_icon = CPIW_PLUGIN_DIR . '/assets/image/money-not.png';
			                    }
	                        }
	                    	 $deltxt = "<div class='cpiw_avacod'><p>".$cod_avail."</p></div>";

	                    	if($showdate == "enable") {

                                echo '<div class="cpiw_avaitxt"><span class="cpiw_delicons"><img src="'.$del_avail_icon.'"></span>';
                                echo '<div class="cpiw_avaddate" style="color:'.$cpiw_comman['deliverydatetextcolor'].';"><p>'.$delivary_text.' '.$deliverydate.'</p></div></div>';
                            }
                            if($cpiw_cash_dilivery_shw == "enable") {

                                echo '<div class="cpiw_dlvrytxt" style="color:'.$cpiw_comman['codtextcolor'].';"><span class="cpiw_tficon"><img src="'.$cod_avail_icon.'"></span>'.$deltxt.'</div>';
                            }
                            ?>
                            	
                            </div>
                             
                            <?php 
	                        	
	                         
	                    }else{ ?>
	                    	<div class="pincode_not_availabels">
	                    	<p><?php echo 'We Are Not Services This Place';	?></p>
	                          	<input type="button" name="cpiwbtn" class="cpiwcheckbtn" value="Change" style="color: <?php echo esc_attr($cpiw_comman['checkandchangetxtcolor']); ?>; background-color: <?php echo esc_attr($cpiw_comman['checkandchangebackcolor']); ?>;">
	                        </div>
	                          	<?php 
                     }
                } 
            ?>
        </div>
    </div>
    <div class="pincode_not_availabel" style="display: none;">
    	<p><?php echo 'We Are Not Services This Place';	?></p>
          	<input type="button" name="cpiwbtn" class="cpiwcheckbtn" value="Change" style="color: <?php echo esc_attr($cpiw_comman['checkandchangetxtcolor']); ?>;background-color: <?php echo esc_attr($cpiw_comman['checkandchangebackcolor']); ?>">
    </div>
       
       
	  
	<?php
}
add_action('init','CPIW_enable_disable_plugin');
function CPIW_enable_disable_plugin(){
	global $cpiw_comman;
	if ($cpiw_comman['cpiw_enable'] == 'enable') {
		add_action( 'woocommerce_after_add_to_cart_button', 'CPIW_ZipcodeValidatorAfterAddtoCart');
	}
}
