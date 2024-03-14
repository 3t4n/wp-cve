<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}
?><div class="form-inline">

    <div class="form-group">
        <label class="sr-only"> <?php esc_attr_e('Export Type:','order-export-and-more-for-woocommerce'); ?></label>

        <div class="input-group">
            <div class="jem-input-group-addon input-group-addon"><?php esc_attr_e('EXPORT TYPE','order-export-and-more-for-woocommerce'); ?></div>
            <select class="form-control jem-input-group-addon" id="export_type">
                <option id="Order" value="Order"><?php esc_attr_e('Order','order-export-and-more-for-woocommerce'); ?></option>
                <option id="Order" value="Order"><?php esc_attr_e('Product','order-export-and-more-for-woocommerce'); ?></option>
                <option id="Order" value="Order"><?php esc_attr_e('Coupon','order-export-and-more-for-woocommerce'); ?></option>
                <option id="Order" value="Order"><?php esc_attr_e('Customer','order-export-and-more-for-woocommerce'); ?></option>
                <option id="Order" value="Order"><?php esc_attr_e('Shipping','order-export-and-more-for-woocommerce'); ?></option>
            </select>


        </div>
        <button type="submit" id="export_data" class="btn btn-primary jem-input-group-addon jem-export-button" style="margin-left: 16px"><?php esc_attr_e('EXPORT','order-export-and-more-for-woocommerce'); ?></button>
    </div>
</div>