<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}
?><form id="bulk-form" action="#">

	<div class="jemxp-main-content">

		<div class="row">

    <p style="font-size: 14px;"><a href="#" class="open-jem-pro-dialog" data-pro-feature="scheduled-header">Scheduled Exports is a PRO feature - get PRO with a 45% discount.</a> It's a powerful set of tools that enable you to send export in various formats to selected destinations, on a predefined schedule.<br><br></p>

			<div class="form-inline ">

				<div class="form-group">
					<label class="sr-only"> <?php esc_attr_e('Sort by','order-export-and-more-for-woocommerce'); ?></label>

					<div class="input-group">
						<div class="jem-input-group-addon input-group-prepend">
							<span class="input-group-text"><?php esc_attr_e('BULK ACTIONS','order-export-and-more-for-woocommerce'); ?> <i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="This will delete ALL the selected exports"></i></span>
						</div>

						<select class="form-control jem-input-group-addon" id="schedule-bulk-actions">
							<option id="bulk" value="delete">
								<?php esc_attr_e('Delete','order-export-and-more-for-woocommerce'); ?>
							</option>
						</select>
					</div>
					<button type="button" class="btn btn-primary jem-input-group-addon jem-bulk-action"
							style="margin-left: 16px"><?php esc_attr_e('Apply','order-export-and-more-for-woocommerce'); ?>
					</button>
					<button type="button" id="jem-add-schedule-button"
							class="btn btn-primary jem-dark-blue jem-input-group-addon"
							style="margin-left: 16px"><?php esc_attr_e('Add New Job','order-export-and-more-for-woocommerce'); ?>
					</button>
				</div>
			</div>

		</div>
	</div>

	<?php include_once 'schedule-table.php'; ?>

</form>
