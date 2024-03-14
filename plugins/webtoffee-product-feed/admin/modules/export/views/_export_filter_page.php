<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wt_pf_export_main">
    <p><?php echo esc_html( $step_info['description'] ); ?></p>
    <form class="wt_pf_export_filter_form">
	    <table class="form-table wt-pfd-form-table wt-pfd-export-filter-table">				
			<?php
			Webtoffee_Product_Feed_Sync_Common_Helper::field_generator($filter_screen_fields, $filter_form_data);
			?>
	    </table>
    </form>
</div>