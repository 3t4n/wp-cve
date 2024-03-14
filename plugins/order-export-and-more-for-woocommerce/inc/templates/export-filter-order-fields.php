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

$data = $settings;
?>
<div class="row jem-accordion-content jem-padded-rows">
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <div class="form-inline ">

                    <div class="form-group">
                        <div class="input-group">
                            <div class="jem-input-group-addon input-group-prepend jem-input-group-addon-bordered">
                                <span class="input-group-text"><?php esc_attr_e('ORDER STATUS', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select the status of orders to export - leave BLANK to export everything"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="form-inline ">

                    <div class="form-group order-status-picker">
                        <select class="form-control jem-input-group-addon order-status-picker jem-select2-multiline" id="order-status" multiple="multiple" style="width: 50%">
                            <option value='wc-pending' <?php JEMEXP_lite::wp_kses_wf(jemoe_is_selected('wc-pending', $data->getOrderStatus())); ?>>
                                <?php esc_attr_e('Pending Payment', 'order-export-and-more-for-woocommerce'); ?>
                            </option>
                            <option value='wc-failed' <?php JEMEXP_lite::wp_kses_wf(jemoe_is_selected('wc-failed', $data->getOrderStatus())); ?>>
                                <?php esc_attr_e('Failed', 'order-export-and-more-for-woocommerce'); ?>
                            </option>
                            <option value='wc-processing' <?php JEMEXP_lite::wp_kses_wf(jemoe_is_selected('wc-processing', $data->getOrderStatus())); ?>>
                                <?php esc_attr_e('Processing', 'order-export-and-more-for-woocommerce'); ?>
                            </option>
                            <option value='wc-completed' <?php JEMEXP_lite::wp_kses_wf(jemoe_is_selected('wc-completed', $data->getOrderStatus())); ?>>
                                <?php esc_attr_e('Completed', 'order-export-and-more-for-woocommerce'); ?>
                            </option>
                            <option value='wc-on-hold' <?php JEMEXP_lite::wp_kses_wf(jemoe_is_selected('wc-on-hold', $data->getOrderStatus())); ?>><?php esc_attr_e('On Hold', 'order-export-and-more-for-woocommerce'); ?>
                            </option>
                            <option value='wc-cancelled' <?php JEMEXP_lite::wp_kses_wf(jemoe_is_selected('wc-cancelled', $data->getOrderStatus())); ?>>
                                <?php esc_attr_e('Cancelled', 'order-export-and-more-for-woocommerce'); ?>
                            </option>
                            <option value='wc-refunded' <?php JEMEXP_lite::wp_kses_wf(jemoe_is_selected('wc-refunded', $data->getOrderStatus())); ?>>
                                <?php esc_attr_e('Refunded', 'order-export-and-more-for-woocommerce'); ?>
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="form-inline ">

                    <div class="form-group order_field_condi">

                        <div class="input-group mob_mbtm10" style="float: left;">
                            <div class="jem-input-group-addon input-group-prepend mob_mbtm10">
                                <span class="input-group-text" id="basic-addon1"><?php esc_attr_e('ORDER FIELDS', 'order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select a field to filter your orders"></i></span>
                            </div>

                            <select class="form-control jem-input-group-addon jem-select2" id="order-filter-by-anything">
                                <option value="initial" selected="selected"><?php esc_attr_e('Filter by anything..', 'order-export-and-more-for-woocommerce'); ?></option>

                                <?php JEMEXP_lite::wp_kses_wf($filter_html); ?>
                            </select>

                        </div>
                        <select class="form-control jem-input-group-addon " id="fba-condition" style="margin-left: 10px;" disabled>
                            <option value="initial" selected="selected"><?php esc_attr_e('Conditions..', 'order-export-and-more-for-woocommerce'); ?></option>
                        </select>
                        <select class="form-control jem-input-group-addon" id="fba-value-text" style="margin-left: 10px;" disabled>
                            <option value="initial" selected="selected"><?php esc_attr_e('Values..', 'order-export-and-more-for-woocommerce'); ?></option>
                        </select>
                        <a class="btn icon-btn btn-success disabled" id="jem-add-fba-item" href="#" disabled>
                            <span class="jem-accordion-icon fa fa-plus-circle fa-2x img-circle text-danger"></span>
                            <?php esc_attr_e('Add', 'order-export-and-more-for-woocommerce'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    /**
     * Searches an array for a value and if found returns " SELECTED "
     *
     * @param $needle
     * @param $haystack
     * @return string
     */
    function jemoe_is_selected($needle, $haystack)
    {
        if (!is_array($haystack)) {
            return '';
        }

        if (in_array($needle, $haystack)) {
            return ' SELECTED ';
        } else {
            return '';
        }
    }

    generate_fba_item();

    /**
     * Creates any existing FBA's from the defaults
     */
    ?>

    <div id="order-filter-holder" class="jem-rows col-md-12">
        <?php
        $sd  = count($data->getOrderFiltersFba());
        $cls = 'hid';
        if ($sd > 0) {
            $cls = '';
        }
        ?>
        <div class="row <?php esc_attr_e($cls); ?>">
            <div class="col-md-9">
                <div class="ordr_condi__headr">
                    <div class="col-md-3 col-sm-3">
                        <h5><?php esc_attr_e('Fields Name', 'order-export-and-more-for-woocommerce'); ?></h5>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <h5><?php esc_attr_e('Conditions', 'order-export-and-more-for-woocommerce'); ?></h5>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <h5><?php esc_attr_e('Values', 'order-export-and-more-for-woocommerce'); ?></h5>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <h5><?php esc_attr_e('Action', 'order-export-and-more-for-woocommerce'); ?></h5>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $existing_fba_rows = generate_existing_fba_filters($data);
        ?>
    </div>
</div>

<?php
/**
 * @param $data JEMEXP_Export_Data
 * @return string
 */
function generate_existing_fba_filters($data)
{
    $ret = '';
    // loop through all of the FBA filters
    foreach ($data->getOrderFiltersFba() as $key => $val) {
        $ret .= generate_fba_item('display', '', $val['label'], $val['select'], $val['selectlabel'], $val['value'], $val['datatype'], $val['name']);
    }

    return $ret;
}


/**
 * This creates a signle FBA item
 *
 * @param bool   $display
 * @param string $label
 * @param string $condition
 * @param string $value
 * @param string $type
 * @param string $name
 */
function generate_fba_item($display = false, $id = 'fba-template-item', $label = '', $condition = '', $conditionLabel = '', $value = '', $type = '', $name = '')
{
    if ($display) {
        $display = '';
    } else {
        $display = 'display: none;';
    }
?>
    <div id="<?php esc_attr_e($id); ?>" style="<?php esc_attr_e($display); ?>">
        <div class="row jem-order-field-filter-item" style="padding-top:0px!important">
            <div class="col-md-9">
                <div class="form-horizontal">
                    <div class="ordr_condi_output">
                        <div class="col-sm-3 field-name first_col_ordr"><B><?php esc_attr_e($label); ?></B></div>
                        <div class="col-sm-3 condition" value="<?php esc_attr_e($condition); ?>"><?php esc_attr_e($conditionLabel); ?>
                        </div>
                        <div class="col-sm-3 value"><?php esc_attr_e($value); ?>
                        </div>
                        <div class="col-sm-3 last_ordr_col">
                            <a class="btn icon-btn btn-danger jem-remove-order-filter-item" href="#">
                                <span class="fa fa-trash img-circle text-danger"></span>
                                Delete
                            </a>
                        </div>
                        <input type="hidden" class="jem-form-type" value="<?php esc_attr_e($value); ?>">
                        <input type="hidden" class="jem-form-data-type" value="<?php esc_attr_e($type); ?>">
                        <input type="hidden" class="jem-form-name" value="<?php esc_attr_e($name); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}

?>


<!-- our hidden divs we use as the template for new filter rows -->
<!-- we have multiple of them for each type of data text, date and number -->
<?php
function generate_filter_template_text($label = '', $select = '', $value = '')
{
?>
    <div id="filter-template-text" style="display: none;">
        <div class="row">
            <div class="form-horizontal">
                <div class="form-group jem-order-field-filter-item row">
                    <label for="junk" class="col-sm-2 control-label field-name">Email</label>
                    <div class="col-sm-2">
                        <select class="form-control jem-input-group-addon jem-form-select " name="sort_by" id="junk">
                            <option value='='>is equal to</option>
                            <option value='!='>is NOT equal to</option>
                            <option value='LIKE'>contains</option>
                        </select>
                    </div>
                    <div class="col-sm-3 jem-select-query-input">
                        <select class="form-control jem-input-group-addon jem-form-value-holder jem-form-value" name="will_be_replaced" disabled style="width: 100%;">
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <a class="btn icon-btn btn-danger jem-remove-order-filter-item" href="#">
                            <span class="fa btn-glyphicon fa-trash img-circle text-danger"></span>
                            Delete
                        </a>
                    </div>
                    <input type="hidden" class="jem-form-type">
                    <input type="hidden" class="jem-form-data-type">
                    <input type="hidden" class="jem-form-name">
                </div>
            </div>
        </div>

    </div>
<?php
}

function generate_filter_template_date()
{
?>
    <div id="filter-template-date" style="display: none;">
        <div class="row">

            <div class="form-horizontal">
                <div class="form-group jem-order-field-filter-item row">
                    <label for="junk" class="col-sm-2 control-label field-name">Email</label>
                    <div class="col-sm-2">
                        <select class="form-control jem-input-group-addon jem-form-select " name="sort_by" id="junk">
                            <option value='='>is equal to</option>
                            <option value='!='>is NOT equal to</option>
                            <option value='less'>is less than</option>
                            <option value='greater'>is greater than</option>
                        </select>
                    </div>
                    <div class="col-sm-3">

                        <input type="text" class="form-control jem-input-group-addon jem-form-value" name="will_be_replaced" style="width: 100%;">
                        </input>
                    </div>
                    <div class="col-sm-2">
                        <a class="btn icon-btn btn-danger jem-remove-order-filter-item" href="#">
                            <span class="fa btn-glyphicon fa-trash img-circle text-danger"></span>
                            Delete
                        </a>
                    </div>
                    <input type="hidden" class="jem-form-type">
                    <input type="hidden" class="jem-form-data-type">
                    <input type="hidden" class="jem-form-name">
                </div>
            </div>
        </div>
    </div>
<?php
}

function generate_filter_template_number()
{
?>
    <div id="filter-template-number" style="display: none;">
        <div class="row">

            <div class="form-horizontal">
                <div class="form-group jem-order-field-filter-item">
                    <label for="junk" class="col-sm-2 control-label field-name">Email</label>
                    <div class="col-sm-2">
                        <select class="form-control jem-input-group-addon jem-form-select " name="sort_by" id="junk">
                            <option value='='>is equal to</option>
                            <option value='!='>is NOT equal to</option>
                            <option value='less'>is less than</option>
                            <option value='greater'>is greater than</option>
                        </select>
                    </div>
                    <div class="col-sm-3">

                        <input type="text" class="form-control jem-input-group-addon jem-form-value" name="will_be_replaced" style="width: 100%;">
                        </input>
                    </div>
                    <div class="col-sm-2">
                        <a class="btn icon-btn btn-danger jem-remove-order-filter-item" href="#">
                            <span class="glyphicon btn-glyphicon glyphicon-trash img-circle text-danger"></span>
                            Delete
                        </a>
                    </div>
                    <input type="hidden" class="jem-form-type">
                    <input type="hidden" class="jem-form-data-type">
                    <input type="hidden" class="jem-form-name">
                </div>
            </div>
        </div>
    </div>
<?php
}


// Generate the blank templates
generate_filter_template_text();
generate_filter_template_number();
generate_filter_template_date();


?>