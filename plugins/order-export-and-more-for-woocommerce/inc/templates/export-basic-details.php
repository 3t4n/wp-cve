<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}
// This is done to turn on intellisense!

/**
 * This holds the Export Data Object!
 *
 * @var $data JEMEXP_Export_Data
 */

$data = $this->settings;
?>
<!-- START export-basic-details --->

<div class="row">
    <?php JEMEXP_lite::wp_kses_wf($top_section_html); ?>
    <div class="col-md-12 col-sm-12">
        <!--Report format & output-->
        <div class="row jem-filter-header jem-accordion v_middle_centr jem_acc_header" style="">
            <h4 class="mbtm_n"><?php esc_attr_e('Report Format & Output', 'order-export-and-more-for-woocommerce'); ?> <span class="acc_icons"><i class="jem-accordion-icon fa fa-plus-circle fa-2x"></i><i class="jem-accordion-icon fa fa-minus-circle fa-2x" style="display: none"></i></span></h4>
        </div>
        <div class="jem-accordion-content meta-data-content report_acc_sec" style="display: none;">
            <div class="row jem-no-row-margin">
                <div class="col-md-6">
                    <label class="sr-only"> <?php esc_attr_e('Sort by', 'order-export-and-more-for-woocommerce'); ?></label>
                    <div class="input-group">
                        <div class="jem-input-group-addon input-group-prepend">
                            <span class="input-group-text"><?php esc_attr_e('SORT BY', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="How the output will be sorted"></i></span>
                        </div>
                        <select class="form-control jem-input-group-addon" id="sort-by">
                            <option id="sort-by" value="date" <?php selected('date', $data->getSortBy()); ?>>
                                <?php esc_attr_e('Date', 'order-export-and-more-for-woocommerce'); ?>
                            </option>
                            <option id="sort-by" value="order_id" <?php selected('order_id', $data->getSortBy()); ?>>
                                <?php esc_attr_e('Order ID', 'order-export-and-more-for-woocommerce'); ?>
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <div class="jem-input-group-addon input-group-prepend">
                            <span class="input-group-text"><?php esc_attr_e('IN', 'order-export-and-more-for-woocommerce'); ?></span>
                        </div>
                        <select class="form-control jem-input-group-addon" id="sort-order">
                            <option id="order-by" value="asc" <?php selected('asc', $data->getOrderBy()); ?>>
                                <?php esc_attr_e('Ascending Order', 'order-export-and-more-for-woocommerce'); ?>
                            </option>
                            <option id="order-by" value="desc" <?php selected('desc', $data->getOrderBy()); ?>>
                                <?php esc_attr_e('Descending Order', 'order-export-and-more-for-woocommerce'); ?>
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row jem-no-row-margin" style="padding: 0px;">
                <div class="col-md-6">
                    <label class="sr-only"> <?php esc_attr_e('Date Format', 'order-export-and-more-for-woocommerce'); ?></label>
                    <div class="input-group">
                        <div class="jem-input-group-addon input-group-prepend">
                            <span class="input-group-text"><?php esc_attr_e('DATE FORMAT', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="How do you want the dates to appear?"></i></span>
                        </div>
                        <select class="form-control jem-input-group-addon" id="date-format">
                            <option value="F j, Y" <?php selected(@$data->getDateFormat(), 'F j, Y'); ?>><?php esc_attr_e(current_time('F j, Y')); ?></option>
                            <option value="Y/m/d" <?php selected(@$data->getDateFormat(), 'Y/m/d'); ?>><?php esc_attr_e(current_time('Y/m/d')); ?></option>
                            <option value="m/d/Y" <?php selected(@$data->getDateFormat(), 'm/d/Y'); ?>><?php esc_attr_e(current_time('m/d/Y')); ?></option>
                            <option value="d/m/Y" <?php selected(@$data->getDateFormat(), 'd/m/Y'); ?>><?php esc_attr_e(current_time('d/m/Y')); ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <div class="jem-input-group-addon input-group-prepend">
                            <span class="input-group-text"><?php esc_attr_e('TIME FORMAT', 'order-export-and-more-for-woocommerce'); ?></span>
                        </div>
                        <select class="form-control jem-input-group-addon" id="time-format">
                            <option value="g:i a" <?php selected(@$data->getTimeFormat(), 'g:i m'); ?>><?php esc_attr_e(current_time('g:i a')); ?></option>
                            <option value="g:i A" <?php selected(@$data->getTimeFormat(), 'g:i A'); ?>><?php esc_attr_e(current_time('g:i A')); ?></option>
                            <option value="H:i" <?php selected(@$data->getTimeFormat(), 'H:i'); ?>><?php esc_attr_e(current_time('H:i')); ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row jem-no-row-margin" style="padding: 0px; ">
                <div class="col-md-6">
                    <div class="input-group jem-wrap-padding">
                        <div class="jem-input-group-addon input-group-prepend">
                            <span class="input-group-text"><?php esc_attr_e('FILENAME', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="What do you want to call the file - don't forget the extension eg .csv"></i></span>
                        </div>
                        <input type="text" class="form-control jem-input-group-addon " id="filename" size="35" value="<?php esc_attr_e($data->getFilename()); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group jem-wrap-padding">
                        <div class="jem-input-group-addon input-group-prepend">
                            <span class="input-group-text"><?php esc_attr_e('CHARACTER ENCODING', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="If you don't know what this is - probably don't change it"></i></span>
                        </div>
                        <select class="form-control jem-input-group-addon" id="encoding">
                            <option value="UTF-8" <?php selected($data->getEncoding(), 'UTF-8'); ?>>
                                <?php esc_attr_e('UTF-8', 'order-export-and-more-for-woocommerce'); ?>
                            </option>
                            <option value="UTF-16" <?php selected($data->getEncoding(), 'UTF-16'); ?>>
                                <?php esc_attr_e('UTF-16', 'order-export-and-more-for-woocommerce'); ?>
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row jem-no-row-margin" style="padding: 0px;">
                <div class="col-md-6">
                    <div class="input-group">
                        <div class="jem-input-group-addon input-group-prepend">
                            <span class="input-group-text"><?php esc_attr_e('FIELD DELIMITER', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="What character do you want to use to seperate the columns in the export?"></i></span>
                        </div>
                        <input type="text" class="form-control jem-input-group-addon " id="delimiter" size="5" value="<?php esc_attr_e($data->getDelimiter()); ?> ">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <div class="jem-input-group-addon input-group-prepend">
                            <span class="input-group-text"><?php esc_attr_e('LINE BREAK', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="If you don't understand this then you should probably not change it"></i></span>
                        </div>
                        <input type="text" class="form-control jem-input-group-addon " id="linebreak" size="5" value="<?php esc_attr_e($data->getLineBreak()); ?>">
                    </div>
                </div>
            </div>
            <div class="row jem-no-row-margin" style="padding: 0px;">
                <div class="col-md-12">
                    <div class="input-group">
                        <div class="jem-input-group-addon input-group-prepend">
                            <span class="input-group-text"><?php esc_attr_e('EXPORT LINE ITEMS AS', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="When you have more than one product do you want them all on one row or a row for each product?"></i></span>
                        </div>

                        <select class="form-control jem-input-group-addon" id="product-grouping">
                            <option value="rows" <?php selected($data->getProductGrouping(), 'rows'); ?>>
                                <?php esc_attr_e('Each line item on a seperate row', 'order-export-and-more-for-woocommerce'); ?>
                            </option>
                            <option value="columns" <?php selected($data->getProductGrouping(), 'columns'); ?>>
                                <?php esc_attr_e('Each line item in separate columns', 'order-export-and-more-for-woocommerce'); ?>
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row top-buffer20">
                <div class="col-md-12">
                    <button type="submit" id="save-settings" class="btn btn-primary jem-dark-blue jem-input-group-addon"><?php esc_attr_e('SAVE SETTINGS', 'order-export-and-more-for-woocommerce'); ?>
                    </button>
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-12 col-sm-12 jem-rows jem-padded-rows">
        <div class="jem-new-orders">
            <div class="row jem-filter-header jem-accordion v_middle_centr jem_acc_header" style="">
                <h4 class="mbtm_n"><?php esc_attr_e('Export New Orders Only', 'order-export-and-more-for-woocommerce'); ?> <span class="acc_icons"><i class="jem-accordion-icon fa fa-plus-circle fa-2x"></i><i class="jem-accordion-icon fa fa-minus-circle fa-2x" style="display: none"></i></span></h4>
            </div>
            <div class="jem-accordion-content meta-data-content" style="display: none;">
                <div class="row disble_padd">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="input-group align_center_flex">
                                <input type="checkbox" id="export-new-orders" style="margin-top: 0px;" <?php checked(true, $data->getExportNewOrders()); ?>> <b><?php esc_attr_e('Only export new orders', 'order-export-and-more-for-woocommerce'); ?></b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row disble_padd">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="jem-input-group-addon input-group-prepend">
                                    <span class="input-group-text"><?php esc_attr_e('STARTING AFTER ORDER #', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="What order number should the export start from (it wil start with the NEXT order)"></i></span>
                                </div>

                                <input type="text" class="form-control jem-input-group-addon " id="starting-order-number" size="9" value="<?php esc_attr_e($data->getStartingFromNum()); ?> ">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row disble_padd">
                    <div class="col-md-12">
                        <h5><?php esc_attr_e('Only update this if you want to reset the starting point - otherwise let the plugin do it!', 'order-export-and-more-for-woocommerce'); ?></h5>
                        <br>
                        <a href="https://jem-products.com/how-to-export-only-new-orders-in-woocommerce/"><?php esc_attr_e('Click here for detailed instructions', 'order-export-and-more-for-woocommerce'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Filters-->
<div class="row jem-filter-header jem-accordion v_middle_centr jem_acc_header" style="">
    <h4 class="mbtm_n"><?php esc_attr_e('Filter', 'order-export-and-more-for-woocommerce'); ?> <span class="acc_icons"><i class="jem-accordion-icon fa fa-plus-circle fa-2x"></i><i class="jem-accordion-icon fa fa-minus-circle fa-2x" style="display: none"></i></span></h4>
</div>
<div class="row jem-accordion-content meta-data-content disable_negtive_margin" style="display: none;">
    <div class="col-md-12 disable_padd_l_r">
        <!-- Nav tabs -->

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="order-filter-tab" data-bs-toggle="tab" href="#order-filter" role="tab" aria-controls="order-filter-tab" aria-selected="true"><?php esc_attr_e('Order Filter', 'order-export-and-more-for-woocommerce'); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="product-filter-tab" data-bs-toggle="tab" href="#product-filter" role="tab" aria-controls="product-filter-tab" aria-selected="false"><?php esc_attr_e('Product Filter', 'order-export-and-more-for-woocommerce'); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="coupon-filter-tab" data-bs-toggle="tab" href="#coupon-filter" role="tab" aria-controls="coupon-filter-tab" aria-selected="false"><?php esc_attr_e('Coupon Filter', 'order-export-and-more-for-woocommerce'); ?></a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="order-filter" role="tabpanel" aria-labelledby="order-filter-tab">
                <?php require 'export-filter-order-fields.php'; ?>
            </div>
            <div class="tab-pane fade" id="product-filter" role="tabpanel" aria-labelledby="product-filter-tab">
                <?php require 'export-filter-product-fields.php'; ?>
            </div>
            <div class="tab-pane fade" id="coupon-filter" role="tabpanel" aria-labelledby="coupon-filter-tab">
                <?php require 'export-filter-coupon.php'; ?>
            </div>
        </div>
    </div>
</div>


<?php require 'export-fields-to-export.php'; ?>


<?php JEMEXP_lite::wp_kses_wf($bottom_buttons_html); ?>

<div id="jem-export-modal-preview" class="modal show" aria-modal="true" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-success">
        <div class="modal-content">
            <div id="jem-success-modal-header" class="modal-header modal-success-color">
                <div class="icon-box">
                    <span id="jem-success-modal-icon" class="fa fa-check" aria-hidden="true" style="font-size:3em;"></span>
                </div>
            </div>
            <div class="modal-body text-center">
                <h4><?php esc_attr_e('Getting Preview Data', 'order-export-and-more-for-woocommerce'); ?></h4>
                <div class='loader'></div>
            </div>
        </div>
    </div>
</div>

<div id="jem-export-modal" class="modal show" aria-modal="true" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-success">
        <div class="modal-content">
            <div id="jem-success-modal-header" class="modal-header modal-success-color">
                <div class="icon-box">
                    <span id="jem-success-modal-icon" class="fa fa-check" aria-hidden="true" style="font-size:3em;"></span>
                </div>
            </div>
            <div class="modal-body text-center">
                <h4><?php esc_attr_e('Downloading', 'order-export-and-more-for-woocommerce'); ?></h4>
                <div class='loader'></div>
                <div>
                    <div class='jemxp-progress-notice'>
                        <div id="jemxp-progress" class='jemxp-progress'>
                            <div id="jemxp-progress-label" class='jemxp-progress-label'></div>
                            <div id="jemxp-progress-bar" class='jemxp-progress-bar' style="width:100%">
                                <div id="jemxp-progress-meter"></div>
                            </div>
                        </div>
                    </div>
                    <div id="jemxp-progress-wrapper" class='jemxp-progress-wrapper'><?php esc_attr_e('Starting Export', 'order-export-and-more-for-woocommerce'); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="jem-export-modal-message" class="modal bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="modalmsg">
    <div class="modal-dialog jem-modal-error" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="icon-box">
                    <span class="jem-accordion-icon fa fa-ban gi-5x"></span>
                </div>
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body text-center">
                <h4><?php esc_attr_e('Oops!', 'order-export-and-more-for-woocommerce'); ?></h4>
                <p id="jem-modal-message"><?php esc_attr_e('No orders were found matching your export criteria', 'order-export-and-more-for-woocommerce'); ?></p>
                <button class="btn btn-success" data-bs-dismiss="modal"><?php esc_attr_e('Try Again', 'order-export-and-more-for-woocommerce'); ?></button>
            </div>

        </div>
    </div>
</div>
<!-- START export-basic-details --->
