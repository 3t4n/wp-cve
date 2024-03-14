<?php
    global $post;
    $page_id = $post->ID;
    global $wpdb;
    $file="";
    $curl = plugin_dir_url( $file ); 
    $plugin_directory = basename(dirname(__DIR__)); 
    $plugin_url = $curl.''.$plugin_directory;
    $wp_skt_choose_currency_paypal = $wpdb->prefix . "skt_choose_currency_paypal";
    $select_choose_currency_paypal = $wpdb->get_row("SELECT * FROM $wp_skt_choose_currency_paypal WHERE id='1'");
    $get_choose_stripe_count = $wpdb->num_rows;
    if ($get_choose_stripe_count <= 0) {
        $for_paypal_payment ="USD";
        $for_paypal_sign ="&#36;";
    }else{
        $type_currency_id_paypal = $select_choose_currency_paypal->type_currency_id;
        $currency_symbol_id_paypal = $select_choose_currency_paypal->currency_symbol_id;
        $skt_country_type_currency = $wpdb->prefix . "skt_country_type_currency";
        $select_type_currency_stripe = $wpdb->get_row("SELECT * FROM $skt_country_type_currency WHERE id='$type_currency_id_paypal'");
        $for_paypal_payment =  $select_type_currency_stripe->currency_stripe;
        $for_paypal_sign =  $select_type_currency_stripe->currency_sign;
    }
    $page_id = get_queried_object_id();
    $get_pageurl = get_the_permalink($page_id);
?>
<div class="paypal_hide_show skt_donation_box skt_donation_form">
    <div class="fgrow">
        <label for="skt_select_plan"><?php echo esc_attr( get_option('skt_donation_stripe_type_of_payment_label') ); ?></label>
        <select id="skt_select_plan"  name="select_plan">
            <option value="Daily"><?php esc_attr_e('Normal','skt-donation');?>
            </option>
            <option value="Weekly"><?php esc_attr_e('Subscription','skt-donation');?>
            </option>
        </select>
    </div>
    <div id="skt_change_event_one">
        <form class="slt_form_horizontal" method="post">
            <?php if(esc_attr(get_option('skt_donation_first_name_show')) !="false"){ ?>
            <label><?php echo esc_attr( get_option('skt_donation_stripe_first_name_lable') ); ?></label>
            <input type="text" name="first_name"  id="skt_fname" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_first_name') ); ?>" value="" required></br></br>
            <?php } ?>
            <?php if(esc_attr(get_option('skt_donation_last_name_show')) !="false"){ ?>
            <label><?php echo esc_attr( get_option('skt_donation_stripe_last_name_lable') ); ?></label>
            <input type="text" name="last_name" id="skt_lname" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_last_name') ); ?>" value="" required></br></br>
            <?php }?>
            <?php if(esc_attr(get_option('skt_donation_email_show')) !="false"){ ?>
            <label><?php echo esc_attr( get_option('skt_donation_stripe_email_lable') ); ?></label>
            <input type="text" name="email" id="skt_email" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_email') ); ?>" value="" required></br></br>
            <?php } ?>
            <?php if(esc_attr(get_option('skt_donation_phone_show')) !="false"){ ?>
            <label><?php echo esc_attr( get_option('skt_donation_stripe_phone_name_lable') ); ?></label>
            <input type="text" name="phone" id="skt_phone" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_phone_name') ); ?>" value="" required></br></br>
            <?php } ?>
            <label><?php echo esc_attr( get_option('skt_donation_stripe_amount_lable') ); ?></label>
            <input type="text" name="donation_amount" id="skt_donation_amount" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_amount') ); ?>" value="<?php echo esc_attr($donation_amount);?>" required></br></br>
            <input type="hidden" name="payment_in_currency" value="<?php echo esc_attr($for_paypal_payment);?>">
            <input type="text" name="currency_sign" value="<?php echo esc_attr($for_paypal_sign);?>" readonly>
            <input type="hidden" name="mode" value="paypal_normal">

            <div class="skt-dontaion_paypal_button">
                <input type="submit" name="submit" value="PayPal">
            </div>
        </form> 
    </div>
    <div id="skt_change_event_two">
        <form class="slt_form_horizontal" method="post" action="">
            <?php if(esc_attr(get_option('skt_donation_first_name_show')) !="false"){ ?>
            <label><?php echo esc_attr( get_option('skt_donation_stripe_first_name_lable') ); ?></label>
            <input type="text" name="first_name" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_first_name') ); ?>" value="" required></br></br>
            <?php } ?>
            <?php if(esc_attr(get_option('skt_donation_last_name_show')) !="false"){ ?>
                <label><?php echo esc_attr( get_option('skt_donation_stripe_last_name_lable') ); ?></label>
            <input type="text" name="last_name" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_last_name') ); ?>" value="" required></br></br>
            <?php }?>
            <?php if(esc_attr(get_option('skt_donation_email_show')) !="false"){ ?>
                <label><?php echo esc_attr( get_option('skt_donation_stripe_email_lable') ); ?></label>
            <input type="email" name="email" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_email') ); ?>" value="" required></br></br>
            <?php } ?>
            <?php if(esc_attr(get_option('skt_donation_phone_show')) !="false"){ ?>
                <label><?php echo esc_attr( get_option('skt_donation_stripe_phone_name_lable') ); ?></label>
            <input type="text" name="phone" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_phone_name') ); ?>" value="" required></br></br>
            <?php } ?>
            <select name="paypal_recurring" required>
                <?php 
                    if(get_option('skt_donation_day_show')=="true"){ ?>
                        <option value="Daily"><?php esc_attr_e('Daily','skt-donation');?></option>
                   <?php }
                    if(get_option('skt_donation_week_show')=="true"){?>
                        <option value="Weekly"><?php esc_attr_e('Weekly','skt-donation');?></option>
                   <?php }
                    if(get_option('skt_donation_month_show')=="true"){?>
                        <option value="Month"><?php esc_attr_e('Monthly','skt-donation');?></option>
                   <?php }
                    if(get_option('skt_donation_annual_show')=="true"){?>
                        <option value="Yearly"><?php esc_attr_e('Yearly','skt-donation');?></option>
                    <?php } 
                ?>
            </select>
            <label><?php echo esc_attr( get_option('skt_donation_stripe_amount_lable') ); ?></label>
            <input type="text" name="donation_amount" id="skt_donation_amount2" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_amount') ); ?>" value="<?php echo esc_attr($donation_amount);?>" required></br></br>
            <?php wp_nonce_field( 'paypal_subscriptionnormal', 'add_paypal_nonce' ); ?>
            <input type="hidden" name="payment_in_currency" value="<?php echo esc_attr($for_paypal_payment);?>">
            <input type="text" name="currency_sign" value="<?php echo esc_attr($for_paypal_sign);?>" readonly>
            <input type="hidden" name="page_id" value="<?php echo esc_attr($page_id);?>"/>
            <input type="hidden" name="paypal_mode_subscription" value="paypal_mode">
            <input type="submit" name="submit" value="<?php esc_attr_e('Submit','skt-donation');?>" class="button">
        </form> 
    </div>
</div>

<!-- Latest compiled JavaScript -->
<!--************* END FOR PAYPAL INTEGRATION JAVASCRIPT CODE *************-->
<script type="text/javascript">
jQuery(document).ready(function() {
    var selected_paypal = "selected";
    if(selected_paypal =="selected"){
        jQuery("#skt_change_event_two").hide();
        jQuery("#skt_change_event_one").show();
    }
    jQuery('#skt_select_plan').on('change', function() {
        if (this.value === 'Daily') {
            jQuery("#skt_change_event_two").hide();
            jQuery("#skt_change_event_one").show();
        } else if (this.value === 'Weekly') {
            jQuery("#skt_change_event_one").hide();
            jQuery("#skt_change_event_two").show();
        }
    });
});
</script>