<?php

    $default = array(	array( 'slug' => 'paypal', 'label' => __("PayPal", 
    			"wdm-ultimate-auction")),
			array( 'slug' => 'wire_transfer', 'label' => __("Wire Transfer", 
				"wdm-ultimate-auction")),
			array( 'slug' => 'mailing_address', 'label' => __("Cheque", 
				"wdm-ultimate-auction")),
			array( 'slug' => 'cash', 'label' => __("Cash", "wdm-ultimate-auction"))
		    );
?>
<ul class="subsubsub">
    <?php
    
	$link = '';
	
	$methods = apply_filters('ua_add_payment_header_link', $default);
	
	if(isset($_GET['method'])){
	    $link = esc_attr($_GET['method']);
	}
	
	foreach( $methods as $list){
	    if(empty($link)){
			$link = 'paypal';
	    }
	    
	    ?>
	    <li><a href="?page=payment&method=<?php echo $list['slug'];?>" class="<?php echo $link == $list['slug'] ? 'current' : ''; ?>"><?php echo $list['label'];?></a>|</li>
	    <?php
	}
    ?>
</ul>
<p class="clear">
    
<?php
    if(!isset($_GET['method']) || empty($_GET['method']) || $_GET['method'] == 'paypal'){

	if(isset($_POST['wdm_paypal_address'])){		
	    update_option('wdm_paypal_address', esc_attr($_POST['wdm_paypal_address']));
	}
	
	if(isset($_POST['wdm_account_mode'])){
	    update_option('wdm_account_mode', esc_attr($_POST['wdm_account_mode']));
	}
	
	do_action('ua_payment_update_settings_paypal', $_POST );
	
	?>
	<form id="wdm-payment-form" class="auction_settings_section_style" action="" method="POST">
	    <?php  echo "<h3>".__("PayPal", "wdm-ultimate-auction")."</h3>"; ?>
	    <table class="form-table">
	    <tr valign="top">
		<th scope="row">
		    <label for="wdm_paypal_id"><?php _e("PayPal Email Address", "wdm-ultimate-auction"); ?></label>
		</th>
		<td>
		    <input class="wdm_settings_input email" type="text" id="wdm_paypal_id" name="wdm_paypal_address" value="<?php echo get_option('wdm_paypal_address');?>" />
		    <?php echo paypal_auto_return_url_notes(); ?>
		</td>
	    </tr>
	    <tr valign="top">
		<th scope="row">
		    <label for="wdm_account_mode_id"><?php _e("PayPal Account Type", "wdm-ultimate-auction"); ?></label>
		</th>
		<td>
		    <?php
			$options = array("Live", "Sandbox");
			add_option('wdm_account_mode','Live');
			foreach($options as $option) {
			    $checked = (get_option('wdm_account_mode')== $option) ? ' checked="checked" ' : '';
			    echo "<input ".$checked." value='$option' name='wdm_account_mode' type='radio' /> $option <br />";
			}
			printf("<div class='ult-auc-settings-tip'>".__("Select 'Sandbox' option when testing with your %s email address.", "wdm-ultimate-auction")."</div>", "sandbox PayPal");?>
		</td>
	    </tr>
	    </table>
	    <?php
	    do_action('ua_payment_register_settings_paypal');
	    submit_button(__("Save Changes", "wdm-ultimate-auction")); ?>
	</form>
	<?php
    }
    elseif(isset($_GET['method']) && ($_GET['method'] == 'wire_transfer')){
	if(isset($_POST['wdm_wire_transfer'])){
	    update_option('wdm_wire_transfer', esc_attr($_POST['wdm_wire_transfer']));
	}

	?>
	<form id="wdm-payment-form" class="auction_settings_section_style" action="" method="POST">
	    <?php  echo "<h3>".__("Wire Transfer", "wdm-ultimate-auction")."</h3>"; ?>
	    <table class="form-table">
	    <tr valign="top">
		<th scope="row">
		    <label for="wdm_wire_transfer_id"><?php _e("Wire Transfer Details", "wdm-ultimate-auction"); ?></label>
		</th>
		<td>
		    <textarea class="wdm_settings_input" id="wdm_wire_transfer_id" name="wdm_wire_transfer"><?php echo get_option('wdm_wire_transfer');?></textarea>
    <br />
    <div class="ult-auc-settings-tip"><?php _e("Enter your wire transfer details. This will be sent to the highest bidder.", "wdm-ultimate-auction");?></div>
		</td>
	    </tr>
	    </table>
	    <?php submit_button(__("Save Changes", "wdm-ultimate-auction")); ?>
	</form>
	<?php
    }
    elseif(isset($_GET['method']) && ($_GET['method'] == 'mailing_address')){
	if(isset($_POST['wdm_mailing_address'])){
	    update_option('wdm_mailing_address', esc_attr($_POST['wdm_mailing_address']));
	}

	?>
	<form id="wdm-payment-form" class="auction_settings_section_style" action="" method="POST">
	    <?php  echo "<h3>".__("Cheque", "wdm-ultimate-auction")."</h3>"; ?>
	    <table class="form-table">
	    <tr valign="top">
		<th scope="row">
		    <label for="wdm_mailing_id"><?php _e("Mailing Address & Cheque Details", "wdm-ultimate-auction"); ?></label>
		</th>
		<td>
        <textarea class="wdm_settings_input" id="wdm_mailing_id" name="wdm_mailing_address"><?php echo get_option('wdm_mailing_address');?></textarea>
    <div class="ult-auc-settings-tip"><?php _e("Enter your mailing address where you want to receive checks by mail. This will be sent to the highest bidder.", "wdm-ultimate-auction");?></div>
		</td>
	    </tr>
	    </table>
	    <?php submit_button(__("Save Changes", "wdm-ultimate-auction")); ?>
	</form>
	<?php
    }
    elseif(isset($_GET['method']) && ($_GET['method'] == 'cash')){
	
        if(isset($_POST['wdm_cash'])){	
            update_option('wdm_cash', esc_attr($_POST['wdm_cash']));	
        }	
             ?>	
        <form id="wdm-payment-form" class="auction_settings_section_style" action="" method="POST">
            <?php  echo "<h3>".__("Cash", "wdm-ultimate-auction")."</h3>"; ?>
            <table class="form-table">	
            <tr valign="top">
                <th scope="row">
                    <label for="wdm_cash_id"><?php _e("Customer Message (optional)", "wdm-ultimate-auction"); ?></label>	
                </th>	
                <td>	
        <textarea class="wdm_settings_input" id="wdm_cash_id" name="wdm_cash"><?php echo get_option('wdm_cash');?></textarea>	
    <div class="ult-auc-settings-tip"><?php _e("By choosing this payment method, PRO would send a congratulatory email mentioning that final bidder should pay in cash the final bidding amount to auctioneer for the auctioned item.", "wdm-ultimate-auction");?></div>
                </td>
            </tr>
            </table>
            <?php submit_button(__("Save Changes", "wdm-ultimate-auction")); ?>
        </form>
        <?php	
    }
    elseif(isset($_GET['method'])){
		do_action('ua_payment_register_settings', esc_attr($_GET['method']));
    }
    