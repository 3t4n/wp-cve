<?php 
add_action('admin_menu', 'CPIW_PincodeAdd');
 
function CPIW_PincodeAdd() {

    add_submenu_page(
        'pin-code',
        __( 'Add Pincodes', 'check-pincode-in-woocommerce'),
        __( 'Add Pincodes', 'check-pincode-in-woocommerce'),
        'manage_options',
        'my-add-pincode-submenu-page',
        'CPIW_PincodeAddCallback' 
    );  

}
  
function CPIW_PincodeAddCallback(){
    
    global $wpdb;
    $tablename=$wpdb->prefix.'cpiw_pincode';

    if(isset($_REQUEST['action']) && $_REQUEST['action'] == "pincode_edit") { 
        $pincode = sanitize_text_field($_REQUEST['id']);
        $cntSQL = "SELECT * FROM {$tablename} where id='".$pincode."'";
        $pincodes_record = $wpdb->get_results($cntSQL, OBJECT);
        if(isset($_GET['update']) && $_GET['update'] == 'exists') { ?>
                <div class="notice notice-error is-dismissible">
                     <p><?php echo  esc_html( __( 'This Pincode Already Exits.', 'check-pincode-in-woocommerce' ) ); ?></p>
                </div>
         <?php 
        }

        if(isset($_GET['update']) && $_GET['update'] == 'success') { ?>
            <div class="notice notice-success is-dismissible">
                 <p><?php echo  esc_html( __( 'This Pincode Successfully Update.', 'check-pincode-in-woocommerce' ) ); ?></p>
            </div>
        <?php } ?>

        <div id="poststuff">
            <div class="postbox">
                <div class="postbox-header">
                    <h2><?php echo __('Update Post Code','check-pincode-in-woocommerce');?></h2>
                </div>
                <div class="inside">
                    <form method="post">
                        <?php wp_nonce_field( 'CPIW_update_pincode_action', 'CPIW_update_pincode_field' ); ?>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th>
                                        <label for="<?php echo esc_attr('Pincode'); ?>"><?php echo esc_html( __( 'Pincode', 'check-pincode-in-woocommerce' ) ); ?>
                                        </label>
                                    </th>
                                    <td>
                                        <input type="text" name="cpiwpincode" value="<?php echo esc_attr($pincodes_record[0]->pincode); ?>"  id="<?php echo esc_attr('Pincode');?>" required="" />
                                        <input type="hidden" name="pincodeid" value="<?php echo esc_attr($pincodes_record[0]->id); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="<?php echo esc_attr('State Name'); ?>"><?php echo esc_html( __( 'State Name', 'check-pincode-in-woocommerce' ) ); ?>
                                        </label>
                                    </th>
                                    <td>
                                        <input type="text" name="cpiwstate" value="<?php echo esc_attr($pincodes_record[0]->state); ?>"  id="<?php echo esc_attr('State'); ?>" required="" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="<?php echo esc_attr('City Name'); ?>"><?php echo esc_html( __( 'City Name', 'check-pincode-in-woocommerce' ) ); ?>
                                        </label>
                                    </th>
                                    <td>
                                        <input type="text" name="cpiwcity" value="<?php echo esc_attr($pincodes_record[0]->city); ?>" id="<?php echo esc_attr('City'); ?>" required="" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="<?php echo esc_attr('Shipping_Amount'); ?>"><?php echo esc_html( __( 'Shipping Amount', 'check-pincode-in-woocommerce' ) ); ?>
                                        </label>
                                    </th>
                                    <td>
                                        <input type="text" name="cpiwshipping" value="<?php echo esc_attr($pincodes_record[0]->ship_amount); ?>" id="<?php echo esc_attr('Shipping_Amount'); ?>" required="" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="<?php echo esc_attr('Delivery_within_days'); ?>"><?php echo esc_html( __( 'Delivery within days', 'check-pincode-in-woocommerce' ) ); ?>
                                        </label>
                                    </th>
                                    <td>
                                        <input type="text" name="cpiwddate" value="<?php echo esc_attr($pincodes_record[0]->ddate); ?>" id="<?php echo esc_attr('Delivery_within_days'); ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="<?php echo esc_attr('Cash_on_Delivery'); ?>"><?php echo esc_html( __( 'Cash on Delivery Availabel', 'check-pincode-in-woocommerce' ) ); ?>
                                        </label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="cpiwcod" value="1" <?php if($pincodes_record[0]->caseondilvery == '1') { echo 'checked'; } ?> />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                      <input type="hidden" name="action" value="cpiw_update_postcode">
                                      <input type="submit" name="cpiw_update_postcode" class="button button-primary" value="Update Pincode">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>


    <?php }else{ 

            if(isset($_GET['add']) && $_GET['add'] == 'success') { ?>
                    <div class="notice notice-success is-dismissible">
                         <p><?php echo  esc_html( __( 'This Pincode Successfully Added.', 'check-pincode-in-woocommerce' ) ); ?></p>
                    </div>
            <?php
            }

            if(isset($_GET['add']) && $_GET['add'] == 'exists') {  ?>
                    <div class="notice notice-error is-dismissible">
                         <p><?php echo  esc_html( __( 'This Pincode Already Exits.', 'check-pincode-in-woocommerce' ) ); ?></p>
                    </div>
            <?php } ?>

            <div id="poststuff">
                <div class="postbox">
                    <div class="postbox-header">
                        <h2><?php echo __('Add Post Code','check-pincode-in-woocommerce');?></h2>
                    </div>
                    <div class="inside">
                        <form method="post">
                            <?php wp_nonce_field( 'CPIW_add_pincode_action', 'CPIW_add_pincode_field' ); ?>
                            <table class="form-table">
                                <tbody>
                                    <tr>
                                        <th>
                                            <label for="<?php echo esc_attr('Pincode'); ?>"><?php echo esc_html( __( 'Pincode', 'check-pincode-in-woocommerce' ) ); ?>
                                            </label>
                                        </th>
                                        <td>
                                            <input type="text" name="cpiwpincode" <?php if(isset($_GET['cpiwpincode']) && $_GET['cpiwpincode'] != '') { echo 'value='.sanitize_text_field( $_GET['cpiwpincode'] ); } ?> id="<?php echo esc_attr('Pincode'); ?>" required="" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="<?php echo esc_attr('State Name'); ?>"><?php echo esc_html( __( 'State Name', 'check-pincode-in-woocommerce' ) ); ?>
                                            </label>
                                        </th>
                                        <td>
                                            <input type="text" name="cpiwstate" <?php if(isset($_GET['cpiwstate']) && $_GET['cpiwstate'] != '') { echo 'value='.sanitize_text_field( $_GET['cpiwstate'] ); } ?>  id="<?php echo esc_attr('State'); ?>" required="" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="<?php echo esc_attr('City Name'); ?>"><?php echo esc_html( __( 'City Name', 'check-pincode-in-woocommerce' ) ); ?>
                                            </label>
                                        </th>
                                        <td>
                                            <input type="text" name="cpiwcity" <?php if(isset($_GET['cpiwcity']) && $_GET['cpiwcity'] != '') { echo 'value='.sanitize_text_field( $_GET['cpiwcity'] ); } ?> id="<?php echo esc_attr('City'); ?>" required="" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="<?php echo esc_attr('Shipping_Amount'); ?>"><?php echo esc_html( __( 'Shipping Amount', 'check-pincode-in-woocommerce' ) ); ?>
                                            </label>
                                        </th>
                                        <td>
                                            <input type="text" name="cpiwshipping" <?php if(isset($_GET['cpiwshipping']) && $_GET['cpiwshipping'] != '') { echo 'value='.sanitize_text_field( $_GET['cpiwshipping'] ); } ?> id="<?php echo esc_attr('Shipping_Amount'); ?>" required="" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="<?php echo esc_attr('Delivery_within_days'); ?>"><?php echo esc_html( __( 'Delivery within days', 'check-pincode-in-woocommerce' ) ); ?>
                                            </label>
                                        </th>
                                        <td>
                                            <input type="text" name="cpiwddate" <?php if(isset($_GET['cpiwddate']) && $_GET['cpiwddate'] != '') { echo 'value='.sanitize_text_field( $_GET['cpiwddate'] ); } ?> id="<?php echo esc_attr('Delivery_within_days'); ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="<?php echo esc_attr('Cash_on_Delivery'); ?>"><?php echo esc_html( __( 'Cash on Delivery Availabel', 'check-pincode-in-woocommerce' ) ); ?>
                                            </label>
                                        </th>
                                        <td>
                                            <input type="checkbox" name="cpiwcod" value="1" <?php if(isset($_GET['cpiwcod']) && $_GET['cpiwcod'] == '1' ) { echo 'checked'; }  { echo 'checked'; } ?>/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                          <input type="hidden" name="action" value="cpiw_add_postcode">
                                          <input type="submit" name="cpiw_add_postcode" class="button button-primary" value="Add Pincode">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>

    <?php } 

    CPIW_PincodeImport();
      
 
}