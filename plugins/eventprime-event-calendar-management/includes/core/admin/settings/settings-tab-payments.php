<?php
$global_options = $options['global'];
$payments = $options['payments'];
$payments_settings = $options['payments_settings'];
$currencies = EventM_Constants::$currencies;
$currencies_position = array( 'before' => '$10 (Before)', 'before_space' => '$ 10 (Before, with space)' ,'after' => '10$ (After)', 'after_space' => '10 $ (After, with space)' );
$active_payment_setting = isset( $_GET['section'] ) ? strtolower( sanitize_text_field( $_GET['section'] ) ) : '';?>
<div class="ep-payments-tab-content"><?php 
    if( ! empty( $active_payment_setting ) ) {?>
        <p class="ep-global-back-btn">
            <?php $back_url = remove_query_arg( 'section' ) ;?>
            <a href="<?php echo esc_url( $back_url );?>" class="ep-back-btn ep-di-flex ep-align-items-center ep-text-decoration-none">
                <span class="material-icons">navigate_before</span> <?php esc_html_e( 'Back', 'eventprime-event-calendar-management ' );?>
            </a>
        </p><?php 
    }
    if( empty( $active_payment_setting ) ) {?>
        <h2>
            <?php esc_html_e( 'Payments', 'eventprime-event-calendar-management' );?>
        </h2><?php
    }?>
    <input type="hidden" name="em_setting_type" value="payment_settings">
</div>
<?php if( isset( $_GET['section'] ) ):?>
    <div class="ep-payment-settings"><?php
        if( count( $payments_settings ) ) {?>
            <div class="ep-payment-setting-page" id="ep-payment-setting-<?php echo esc_attr( $active_payment_setting );?>">
                <div class="ep-payment-setting-title">
                    <?php if( ! empty( $payments[$active_payment_setting]['icon_url'] ) ) {?>
                        <img src="<?php echo esc_url( $payments[$active_payment_setting]['icon_url'] );?>" class="ep-payment-method-logo">
                    <?php }else{ ?>
                        <h2 class="ep-payment-method-title"><?php echo esc_html( $payments[$active_payment_setting]['method'] );?> </h2>
                    <?php }?> 
                </div>
                <?php echo $payments_settings[$active_payment_setting];?>
                <input type="hidden" value="<?php echo esc_attr( $active_payment_setting );?>" name="em_payment_type">
            </div><?php
        }?>
    </div>
<?php else:?>
    <div class="ep-payments-list">
        <table class="ep-payments-table ep-form-table-setting widefat">
            <thead>
                <tr>
                    <!-- <th></th> -->
                    <th><?php esc_attr_e( 'Payment Processor ','eventprime-event-calendar-management' );?></th>
                    <th><?php esc_attr_e( 'Status','eventprime-event-calendar-management' );?></th>
                    <th><?php esc_attr_e( 'Default','eventprime-event-calendar-management' );?></th>
                    <th><?php esc_attr_e( 'Description','eventprime-event-calendar-management' );?></th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody id="ep-payment-sortable">
                <?php
                    if( count( $payments ) ) {
                        foreach( $payments as $key => $method ) {
                            if( isset( $method['show_in_list'] ) && $method['show_in_list'] == 0 ) continue;
                            $tab_url = esc_url( add_query_arg( array( 'settings-updated' => false, 'tab'=> 'payments', 'section'=> $key ) ) );?>
                            <tr class="ep-payment-gateway" id="ep-payment-<?php echo esc_html($key)?>">
                                <!-- <td>
                                    <span class="ep-payment-handle">Drag</span>
                                    <input type='hidden' name='payment_order[]' value="<?php //echo esc_html($key)?>">
                                </td> -->
                                <td class="ep-payment-label">
                                    <?php if( ! empty( $method['icon_url'] ) ) {?>
                                        <img src="<?php echo esc_url( $method['icon_url'] );?>" class="ep-payment-method-logo"><?php
                                    }else{ ?>
                                        <div class="ep-payment-method-title"><?php echo esc_html( $method['method'] );?> </div>
                                    <?php }?>
                                </td>
                                <td class="ep-payment-status">
                                    <label class="ep-toggle-btn">
                                        <?php $enable_key = $method['enable_key'];?>
                                        <input type="checkbox" class="ep-payment-toggle" name="<?php echo esc_attr( $method['enable_key'] );?>" value="<?php echo ( ! empty( $global_options->$enable_key ) ? $global_options->$enable_key : 0 );?>" <?php echo isset( $global_options->$enable_key ) && $global_options->$enable_key == 1 ? 'checked' : '';?> >
                                        <span class="ep-toogle-slider round"></span>
                                    </label>
                                </td>
                                <td class="ep-default-payment-status">
                                    <label class="ep-toggle-btn">
                                        <input type="checkbox" class="ep-default-payment-processor" id="ep_default_payment_<?php echo esc_attr( $method['enable_key'] );?>" name="ep_default_payment_processor" value="<?php echo esc_attr( $method['enable_key'] );?>" <?php if( isset( $global_options->default_payment_processor ) && ! empty( $global_options->default_payment_processor ) && $global_options->default_payment_processor == $method['enable_key'] ){ echo 'checked'; } ?>>
                                        <span class="ep-toogle-slider round"></span>
                                    </label>
                                </td>
                                <td class="ep-payment-description">
                                    <?php echo esc_html($method['description']);?>
                                </td>
                                <td class="ep-payment-setting">
                                    <a href="<?php echo $tab_url;?>" class="button alignright"><?php _e('Manage','eventprime-event-calendar-management');?></a>
                                </td>
                            </tr><?php 
                        }
                    }
                ?>
            </tbody>
        </table>
        <table class="form-table">
            <input type="hidden" name="em_payment_type" value="basic">
            <tbody>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="em_hide_past_events">
                            <?php esc_html_e( 'Currency', 'eventprime-event-calendar-management' );?>
                        </label>
                    </th>
                    <td class="forminp forminp-text">
                    <select name="currency" id="currency" class="ep-form-control">
                        <?php 
                        foreach($currencies as $key => $value){
                            if($global_options->currency == $key){
                            ?>
                                <option value="<?php echo esc_attr($key);?>" selected><?php echo esc_html_e($value,'eventprime-event-calendar-management');?></option>
                            <?php
                            }
                            else{
                            ?>
                                <option value="<?php echo esc_attr($key);?>"><?php echo esc_html_e($value,'eventprime-event-calendar-management');?></option>
                            <?php  
                            }
                        }?>
                    </select>
                        <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Default currency for accepting payments.', 'eventprime-event-calendar-management' );?></div>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="em_default_calendar_view">
                            <?php esc_html_e( 'Currency Symbol Position', 'eventprime-event-calendar-management' );?>
                        </label>
                    </th>
                    <td class="forminp forminp-text">
                        <select name="currency_position" id="currency_position" class="ep-form-control">
                            <?php 
                            foreach($currencies_position as $key => $value){
                                if($global_options->currency_position == $key){?>
                                    <option value="<?php echo esc_attr($key);?>" selected><?php echo esc_html_e($value,'eventprime-event-calendar-management');?></option><?php
                                } else{?>
                                    <option value="<?php echo esc_attr($key);?>"><?php echo esc_html_e($value,'eventprime-event-calendar-management');?></option><?php  
                                }
                            }?>
                        </select>
                        <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Select where you wish to place currency symbol with prices.', 'eventprime-event-calendar-management' );?></div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
<?php endif;?>

<style>
.ep-payments-list .ep-payment-gateway {
    padding: 10px;
    margin-bottom: 20px;
    box-shadow1: rgb(6 24 44 / 0%) 0px 0px 0px 2px, rgb(6 24 44 / 60%) 0px 3px 4px 0px, rgb(255 255 255 / 0%) 0px 0px 0px;
}
table.ep-payments-table {
    margin: 0;
    position: relative;
    table-layout: fixed;
    width:100%;
}
.ep-inactive-payment{
    display:none;
}
table.ep-payments-table th {
    display: table-cell!important;
    padding: 1em!important;
    vertical-align: top;
    text-align:left;
    line-height: 1.75em;
}
table.ep-payments-table thead {
    background: #fff;
}
table.ep-payments-table tr:nth-child(odd) td {
    background: #f9f9f9;
}
table.ep-payments-table {
    border: 1px solid #d1cbcb;
}
table.ep-payments-table tr td {
    padding: 10px;
}
</style>
