<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}
?><!-- START export-bottom-buttons-->
<div class="row">
    <div class="col-md-12">
        <div class="form-inline " style="padding-top: 10px;margin-top:10px;">
            <button type="submit" id="export_data" class="btn btn-primary  jem-dark-blue jem-input-group-addon jem-export-button"><?php esc_attr_e('EXPORT','order-export-and-more-for-woocommerce'); ?></button>
            <button type="submit" id="main_preview_bottom_button" class="btn btn-info jem-input-group-addon jem-preview-button" style="margin-left: 10px"><?php esc_attr_e('Preview','order-export-and-more-for-woocommerce'); ?></button>
            <button type="submit" id="main-page-bottom-save-settings" style="margin-left: 10px" class="btn btn-primary jem-dark-blue jem-input-group-addon"><?php esc_attr_e('SAVE SETTINGS','order-export-and-more-for-woocommerce'); ?></button>
		</div>
	</div>
</div>
<!-- Preview Button HTMl -->
<?php include('export-preview-html.php'); ?>
</div>
<!-- END export-bottom-buttons-->
