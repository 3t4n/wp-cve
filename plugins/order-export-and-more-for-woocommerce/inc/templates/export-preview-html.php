<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}
?><div class="row jem-filter-header jem-accordion v_middle_centr jem_acc_header hid" id="pbx" style="">
    <h4 class="mbtm_n"><?php esc_attr_e('Preview', 'order-export-and-more-for-woocommerce'); ?> <span class="acc_icons"><i class="jem-accordion-icon fa fa-plus-circle fa-2x"></i><i class="jem-accordion-icon fa fa-minus-circle fa-2x" style="display: none"></i></span></h4>
</div>
<div id="preview-accordian" class="row jem-accordion-content disable_negtive_margin meta-data-content" style="display: none">
    <div class="table-responsive">
        <table id="previewTable" style="display:none" class="table">
            <tr></tr>
        </table>
    </div>
</div>