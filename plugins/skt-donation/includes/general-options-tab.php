<?php
  function sktdonation_general_optiontab(){
    if (!current_user_can('administrator')) {
      wp_die(__('You do not have sufficient permissions to access this page.'));
    }else{
?>
<div>
  <h3 class="skt-mtop"><?php esc_attr_e('Donation member list','skt-donation');?></h3>
  <table id="skt_donation_myTable" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <td>
          <div class="skt-donations-form-row darkgry">
            <div class="skt-donations-form-column first-name">
              <p><?php esc_attr_e('First Name','skt-donation');?></p>
            </div>
            <div class="skt-donations-form-column last-name">
            <p><?php esc_attr_e('Last Name','skt-donation');?></p>
            </div>
            <div class="skt-donations-form-column email">
              <p><?php esc_attr_e('Email','skt-donation');?></p>
            </div>
            <div class="skt-donations-form-column phone-no">
              <p><?php esc_attr_e('Phone No','skt-donation');?></p>
            </div>
            <div class="skt-donations-form-column amount">
              <p><?php esc_attr_e('Amount','skt-donation');?></p>
            </div>
            <div class="skt-donations-form-column mode">
              <p><?php esc_attr_e('Mode','skt-donation');?></p>
            </div>
            <div class="skt-donations-form-column normal-subscription">
              <p><?php esc_attr_e('Normal/Subscription','skt-donation');?></p>
            </div>
            <div class="skt-donations-form-column transaction-id">
              <p><?php esc_attr_e('Transaction Id','skt-donation');?></p>
            </div>
            <div class="skt-donations-form-column date">
              <p><?php esc_attr_e('Date','skt-donation');?></p>
            </div>
            <div class="skt-donations-form-column delete">
              <p><?php esc_attr_e('Delete','skt-donation');?></p>
            </div>
          </div>
        </td>
      </tr>
    </thead>
    <?php
      global $wpdb;
      $donation_amt_table =$wpdb->prefix.'skt_donation_amount';
      $get_donation_list = $wpdb->get_results ( "SELECT *, IF(subscription_normal='subscriptions', 'Subscriptions', 'Normal') AS subscriptions_status FROM $donation_amt_table" );
      foreach ( $get_donation_list as $get_donation_member ){
    ?>
      <tr>
        <td>
            <div class="skt-donations-form-row">
              <div class="skt-donations-form-column first-name">
                <p><?php echo $get_donation_member->customer_firstname;?></p>
              </div>
              <div class="skt-donations-form-column last-name">
                <p><?php echo esc_attr($get_donation_member->customer_lastname);?></p>
              </div>
              <div class="skt-donations-form-column email">
                <p><?php echo esc_attr($get_donation_member->customer_email);?></p>
              </div>
              <div class="skt-donations-form-column phone-no">
                <p><?php echo esc_attr($get_donation_member->customer_phone);?></p>
              </div>
              <div class="skt-donations-form-column amount">
                <p><?php echo esc_attr($get_donation_member->donation_amount);?></p>
              </div>
              <div class="skt-donations-form-column mode">
                <p><?php echo esc_attr($mode = $get_donation_member->mode);?></p>
              </div>
              <div class="skt-donations-form-column normal-subscription">
                <p>
                  <?php echo esc_attr($get_donation_member->subscriptions_status);?>
                  <?php 
                    if($get_donation_member->duration_of_subscription !=''){
                      echo '('.$get_donation_member->duration_of_subscription.')';
                    }
                  ?>
                </p>
              </div>
              <div class="skt-donations-form-column transaction-id">
                <p>
                  <?php  if( $mode == "paypal"){ echo $get_donation_member->paypal_payment_id;
                   }
                  ?>
                  <?php  if( $mode == "twocheckout"){ echo $get_donation_member->twocheckout_transactionId ;
                   } ?>
                </p>
              </div>
              <div class="skt-donations-form-column date">
                <p><?php echo  esc_attr($get_donation_member->payment_date);?></p>
              </div>
              <div class="skt-donations-form-column delete">
                <p>
                  <?php
                    $get_admin_url = get_admin_url().'?page=sktdonationdeletelist';
                    $delete_id = $get_donation_member->id; ?>
                    <?php 
                      $checknounce = wp_create_nonce( 'my-nonce' );
                      $action_url = $get_admin_url.'&tablename='.$donation_amt_table.'&delete_id='.$delete_id.'&mode_delete=delete_category&checknounce='.$checknounce;
                    ?>
                    <a onclick="return confirm('<?php esc_attr_e('Delete this record?','skt-donation');?>')" href="<?php esc_attr_e($action_url);?>"><?php echo esc_attr('Delete','skt-donation');?></a>
                </p>
              </div>
            </div>
        </td>
      </tr>
      <?php }?>
  </table>
  <script type="text/javascript">
    jQuery(document).ready(function() {
      jQuery('#skt_donation_myTable').DataTable({
        "paging":   true,
        "ordering": false,
        "info":     false,
      });
    });
  </script>
</div>
<?php }  
  }
  $sktdonation_general_optiontab = sktdonation_general_optiontab();
?>