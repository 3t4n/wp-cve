<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}
?><div class="row jem-accordion-content jem-padded-rows">

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
            <p style="font-size: 16px;">This is a PRO feature. <a href="#" class="open-jem-pro-dialog" data-pro-feature="product-filter">Get PRO with a <b>45% discount</b>.</a></p>
                <div class="input-group">
                    <div class="jem-input-group-addon input-group-prepend jem-input-group-addon-bordered">
                        <span class="input-group-text"><?php esc_attr_e('FILTER BY PRODUCT', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select the products you want included - all other orders will be ignored"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group order-status-picker">
                    <select disabled id="products" class="form-control jem-input-group-addon" id="order-status" multiple="multiple" style="width: 100%">
                    </select>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="input-group">
                    <div class="jem-input-group-addon input-group-prepend jem-input-group-addon-bordered">
                        <span class="input-group-text"><?php esc_attr_e('FILTER BY PRODUCT CATEGORY', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select the categories you want included - all other orders will be ignored"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group order-status-picker">
                    <select disabled id="product_categories" class="form-control jem-input-group-addon jem" multiple="multiple" style="width: 100%">
                    </select>
                </div>
            </div>

        </div>
    </div>
</div>
