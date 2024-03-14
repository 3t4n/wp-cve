<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}
?><div class="row jem-filter-header jem-accordion v_middle_centr jem_acc_header" style="">
    <h4 class="mbtm_n"><?php esc_attr_e('Fields to Export', 'order-export-and-more-for-woocommerce'); ?> <span class="acc_icons"><i class="jem-accordion-icon fa fa-plus-circle fa-2x"></i><i class="jem-accordion-icon fa fa-minus-circle fa-2x" style="display: none"></i></span></h4>
</div>
<div class="row jem-accordion-content disable_negtive_margin meta-data-content" style="display: none">

    <div class="col-sm-12 col-md-6 jem-report-list">
        <h4><?php esc_attr_e('Fields to export - drag to reorder', 'order-export-and-more-for-woocommerce'); ?></h4>
        <ul id="export-fields-selected">
            <?php JEMEXP_lite::wp_kses_wf($export_fields_chosen); ?>
        </ul>
    </div>

    <div class="col-sm-12 col-md-6 jem-field-list">
        <h4><?php esc_attr_e('Drag fields to the export list', 'order-export-and-more-for-woocommerce'); ?></h4>
        <div class="radio">
            <label class="radio-inline">
                <input type="radio" name="groupRadio" id="basic" value="basic-order-details-group"> <?php esc_attr_e('Basic', 'order-export-and-more-for-woocommerce'); ?>
            </label>
            <label class="radio-inline">
                <input type="radio" name="groupRadio" id="checkout" value="checkout-information-group"> <?php esc_attr_e('Checkout Information', 'order-export-and-more-for-woocommerce'); ?>
            </label>
            <label class="radio-inline">
                <input type="radio" name="groupRadio" id="shipping" value="shipping-details-group"> <?php esc_attr_e('Shipping Details', 'order-export-and-more-for-woocommerce'); ?>
            </label>
            <label class="radio-inline">
                <input type="radio" name="groupRadio" id="billing" value="billing-details-group"> <?php esc_attr_e('Billing Details', 'order-export-and-more-for-woocommerce'); ?>
            </label><BR>
            <label class="radio-inline">
                <input type="radio" name="groupRadio" id="line-items" value="line-item-details-group"> <?php esc_attr_e('Line Items', 'order-export-and-more-for-woocommerce'); ?>
            </label>
            <label class="radio-inline">
                <input type="radio" name="groupRadio" id="product" value="product-details-group"> <?php esc_attr_e('Product Information', 'order-export-and-more-for-woocommerce'); ?>
            </label>
            <label class="radio-inline">
                <input type="radio" name="groupRadio" id="user" value="user-details-group"> <?php esc_attr_e('User Information', 'order-export-and-more-for-woocommerce'); ?>
            </label>
            <label class="radio-inline">
                <input type="radio" name="groupRadio" id="custom-fields" value="custom-fields-group"> <?php esc_attr_e('Custom Fields', 'order-export-and-more-for-woocommerce'); ?>
            </label>
        </div>
        <div class="row jem-filter-header jem-accordion v_middle_centr jem_acc_header" style="">
            <h4 class="mbtm_n"><?php esc_attr_e('Show Available Meta Data', 'order-export-and-more-for-woocommerce'); ?> <span class="acc_icons"><i class="jem-accordion-icon fa fa-plus-circle fa-2x" style="display: none"></i><i class="jem-accordion-icon fa fa-minus-circle fa-2x"></i></span></h4>
        </div>
        <div class="jem-accordion-content meta-data-content">
            <div id="basic-order-meta-data" data-datatype="postmeta" class="available-meta-data basic-order-details-group checkout-information-group shipping-details-group billing-details-group" style="display: none;">
                <div class="row jem-no-row-margin">
                    <div class="col-md-12">
                    <p>This is a PRO feature. <a href="#" class="open-jem-pro-dialog" data-pro-feature="fields-meta-data">Get PRO with a <b>45% discount</b>.</a></p>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="jem-input-group-addon input-group-prepend">
                                    <span class="input-group-text" style="min-width:150px;"><?php esc_attr_e('BASIC META DATA', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select a field to addd to to your export"></i></span>
                                </div>
                                <?php JEMEXP_lite::wp_kses_wf($basic_meta_data); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row jem-no-row-margin">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="jem-input-group-addon input-group-prepend">
                                    <span class="input-group-text" style="min-width:150px;"><?php esc_attr_e('COLUMN TITLE', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter the name you would like for this column"></i></span>
                                </div>
                                <input type="text" class="form-control jem-input-group-addon meta-data-column-name" placeholder="Enter column name" style="width: 350px;">
                            </div>
                            <button title="This is a PRO feature" type="button" data-pro-feature="add-meta-button" class="open-jem-pro-dialog btn btn-primary jem-dark-blue jem-input-group-addon"><?php esc_attr_e('ADD FIELD', 'order-export-and-more-for-woocommerce'); ?></button>
                        </div>
                    </div>
                </div>
            </div>


            <div id="user-meta-data" class="available-meta-data user-details-group" data-datatype="usermeta" style="display: none;">
                <div class="row jem-no-row-margin">
                    <div class="col-md-12">
                    <p>This is a PRO feature. <a href="#" class="open-jem-pro-dialog" data-pro-feature="fields-meta-data">Get PRO with a <b>45% discount</b>.</a></p>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="jem-input-group-addon input-group-prepend">
                                    <span class="input-group-text" style="min-width:150px;"><?php esc_attr_e('USER META DATA', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select a field to addd to to your export"></i></span>
                                </div>
                                <?php JEMEXP_lite::wp_kses_wf($user_meta_data); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row jem-no-row-margin">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="jem-input-group-addon input-group-prepend">
                                    <span class="input-group-text" style="min-width:150px;"><?php esc_attr_e('COLUMN TITLE', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter the name you would like for this column"></i></span>
                                </div>
                                <input type="text" class="form-control jem-input-group-addon meta-data-column-name" placeholder="Enter column name" style="width: 350px;">
                            </div>
                            <button title="This is a PRO feature" type="button" data-pro-feature="add-meta-button" class="open-jem-pro-dialog btn btn-primary jem-dark-blue jem-input-group-addon"><?php esc_attr_e('ADD FIELD', 'order-export-and-more-for-woocommerce'); ?></button>
                        </div>
                    </div>
                </div>

            </div>


            <div id="product-meta-data" class="available-meta-data product-details-group" data-datatype="productmeta" style="display: none;">
                <div class="row jem-no-row-margin">
                    <div class="col-md-12">
                    <p>This is a PRO feature. <a href="#" class="open-jem-pro-dialog" data-pro-feature="fields-meta-data">Get PRO with a <b>45% discount</b>.</a></p>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="jem-input-group-addon input-group-prepend">
                                    <span class="input-group-text" style="min-width:150px;"><?php esc_attr_e('PRODUCT META DATA', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select a field to addd to to your export"></i></span>
                                </div>
                                <?php JEMEXP_lite::wp_kses_wf($product_meta_data); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row jem-no-row-margin">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="jem-input-group-addon input-group-prepend">
                                    <span class="input-group-text" style="min-width:150px;"><?php esc_attr_e('COLUMN TITLE', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter the name you would like for this column"></i></span>
                                </div>
                                <input type="text" class="form-control jem-input-group-addon meta-data-column-name" placeholder="Enter column name" style="width: 350px;">
                            </div>
                            <button title="This is a PRO feature" type="button" data-pro-feature="add-meta-button" class="open-jem-pro-dialog btn btn-primary jem-dark-blue jem-input-group-addon"><?php esc_attr_e('ADD FIELD', 'order-export-and-more-for-woocommerce'); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="line-item-meta-data" class="available-meta-data line-item-details-group" data-datatype="lineitemmeta" style="display: none;">
                <div class="row jem-no-row-margin">
                    <div class="col-md-12">
                    <p>This is a PRO feature. <a href="#" class="open-jem-pro-dialog" data-pro-feature="fields-meta-data">Get PRO with a <b>45% discount</b>.</a></p>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="jem-input-group-addon input-group-prepend">
                                    <span class="input-group-text" style="min-width:150px;"><?php esc_attr_e('LINE ITEM META DATA', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select a field to addd to to your export"></i></span>
                                </div>
                                <?php JEMEXP_lite::wp_kses_wf($line_item_meta_data); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row jem-no-row-margin">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="jem-input-group-addon input-group-prepend" style="width: 150px">
                                    <span class="input-group-text" style="min-width:150px;"><?php esc_attr_e('COLUMN TITLE', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter the name you would like for this column"></i></span>
                                </div>
                                <input type="text" class="form-control jem-input-group-addon meta-data-column-name" placeholder="Enter column name" style="width: 350px;">
                            </div>
                            <button title="This is a PRO feature" type="button" data-pro-feature="add-meta-button" class="open-jem-pro-dialog btn btn-primary jem-dark-blue jem-input-group-addon"><?php esc_attr_e('ADD FIELD', 'order-export-and-more-for-woocommerce'); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="coupon-meta-data" class="available-meta-data coupon-details-group" data-datatype="couponmeta" style="display: none;">
                <div class="row jem-no-row-margin">
                    <div class="col-md-12">
                    <p>This is a PRO feature. <a href="#" class="open-jem-pro-dialog" data-pro-feature="fields-meta-data">Get PRO with a <b>45% discount</b>.</a></p>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="jem-input-group-addon input-group-prepend">
                                    <span class="input-group-text" style="min-width:150px;"><?php esc_attr_e('COUPON META DATA', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select a field to addd to to your export"></i></span>
                                </div>
                                <?php JEMEXP_lite::wp_kses_wf($coupon_meta_data); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row jem-no-row-margin">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="jem-input-group-addon input-group-prepend">
                                    <span class="input-group-text" style="min-width:150px;"><?php esc_attr_e('COLUMN TITLE', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter the name you would like for this column"></i></span>
                                </div>
                                <input type="text" class="form-control jem-input-group-addon meta-data-column-name" placeholder="Enter column name" style="width: 350px;">
                            </div>
                            <button title="This is a PRO feature" type="button" data-pro-feature="add-meta-button" class="open-jem-pro-dialog btn btn-primary jem-dark-blue jem-input-group-addon"><?php esc_attr_e('ADD FIELD', 'order-export-and-more-for-woocommerce'); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="custom-meta-data" class="available-meta-data custom-fields-group" data-datatype="customfields" style="display: none;">
                <div class="row jem-no-row-margin">
                    <div class="col-md-12">
                    <p>This is a PRO feature. <a href="#" class="open-jem-pro-dialog" data-pro-feature="fields-meta-data">Get PRO with a <b>45% discount</b>.</a></p>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="jem-input-group-addon input-group-prepend">
                                    <span class="input-group-text" style="min-width:150px;"><?php esc_attr_e('COLUMN ID', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Add a custom field to added to your export"></i></span>
                                </div>
                                <input type="text" class="form-control jem-input-group-addon meta-data-column-id" placeholder="Enter column_id" style="max-width: 350px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row jem-no-row-margin">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="jem-input-group-addon input-group-prepend">
                                    <span class="input-group-text" style="min-width:150px;"><?php esc_attr_e('COLUMN TITLE', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter the name you would like for this column"></i></span>
                                </div>
                                <input type="text" class="form-control jem-input-group-addon meta-data-column-name" placeholder="Enter column name" style="max-width: 350px;">
                            </div>
                            <button title="This is a PRO feature" type="button" data-pro-feature="add-meta-button" class="open-jem-pro-dialog btn btn-primary jem-dark-blue jem-input-group-addon"><?php esc_attr_e('ADD FIELD', 'order-export-and-more-for-woocommerce'); ?></button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <p><br>Drag the fields below to the list on the left to include them in the export.</p>
        <ul id="export-field-list">
            <?php JEMEXP_lite::wp_kses_wf($export_field_list); ?>

        </ul>
    </div>

</div>
