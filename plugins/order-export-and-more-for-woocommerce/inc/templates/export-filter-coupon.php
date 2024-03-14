<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}
?><div class="row jem-accordion-content jem-padded-rows">
    <div class="col-md-12">
        <div class="row">
        <p style="font-size: 16px;">This is a PRO feature. <a href="#" class="open-jem-pro-dialog" data-pro-feature="coupon-filter">Get PRO with a <b>45% discount</b>.</a></p>
            <div class="form-inline ">
                <div class="form-group">
                    <div class="input-group mob_mbtm10">
                        <div class="jem-input-group-addon input-group-prepend jem-input-group-addon-bordered">
                            <span class="input-group-text"><?php esc_attr_e('ANY COUPON', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="If the order has a coupon it will get exported, all others will not"></i></span>
                        </div>
                    </div>
                    <div class="checkbox margin_left10">
                        <input disabled type="checkbox" class="" id="any-coupon" name="any-coupon" style="margin-top: 0px;"><strong><?php esc_attr_e('Include orders with ANY coupon', 'order-export-and-more-for-woocommerce'); ?></strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-inline ">
                <div class="form-group">
                    <div class="input-group">
                        <div class="jem-input-group-addon input-group-prepend jem-input-group-addon-bordered">
                            <span class="input-group-text"><?php esc_attr_e('SPECIFIC COUPON(S)', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Selecte the coupon(s) you want exported"></i></span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="row">
            <div class="form-group order-status-picker">
                <select disabled id="selected-coupons" class="form-control jem-input-group-addon " multiple="multiple" style="width: 100%">
                </select>
            </div>
        </div>
    </div>
</div>
