<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}

$data = $this->settings;
?>
<!-- START export-date-range --->

    <div class="col-md-12">
    	<div class="row jem-filter-header jem-accordion v_middle_centr jem_acc_header" style="">
			<h4 class="mbtm_n"><?php esc_attr_e('DATE RANGES OF ORDERS TO INCLUDES IN EXPORT','order-export-and-more-for-woocommerce'); ?> <span class="acc_icons"><i class="jem-accordion-icon fa fa-plus-circle fa-2x"></i><i class="jem-accordion-icon fa fa-minus-circle fa-2x" style="display: none"></i></span></h4>
		</div>
		<div class="jem-accordion-content meta-data-content report_acc_sec" style="display: none;">
			<div class="">
				<div class="panel-body jem-toggle-container" id="jemx-daterange-panel">
					<div>
						<label class="radio-inline margin_r15">
							<input type="radio" class="jem-toggle-trigger" name="export-date-ranges-orders" value="select-range" data-target="#jemx-select-range" <?php checked( 'select-range', $data->getSelectedRange() ); ?>><?php esc_attr_e('Select Range','order-export-and-more-for-woocommerce'); ?>
						</label>
						<label class="radio-inline">
							<input type="radio" class="jem-toggle-trigger" name="export-date-ranges-orders" value="predefined-range" data-target="#jemx-predefined-ranges" <?php checked( 'predefined-range', $data->getSelectedRange() ); ?>><?php esc_attr_e('Predefined Ranges','order-export-and-more-for-woocommerce'); ?>
						</label>
					</div>

					<div id="jemx-select-range" class="panel-collapse collapse jem-toggle-target" style="margin-top: 20px;">
						<div class="form-inline ">
				            <div class="form-group">
								<div class="col-md-5 col-sm-4 col-xs-12 mob_mbtm10">
									<div class="input-group">
										<div class="jem-input-group-addon input-group-prepend">
											<span class="input-group-text"><?php esc_attr_e('FROM DATE ','order-export-and-more-for-woocommerce'); ?><i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="From date of report, inclusive"></i></span>
										</div>
										<input type="text" class="form-control jem-input-group-addon datepicker" id="date-from" value="<?php esc_attr_e($data->getDateFrom()); ?>">
									</div>
								</div>
								<div class="col-md-5 col-sm-4 col-xs-12 mob_mbtm10">
									<div class="input-group">
										<div class="jem-input-group-addon input-group-prepend">
											<span class="input-group-text" id="basic-addon1"><?php esc_attr_e('TO DATE ','order-export-and-more-for-woocommerce'); ?><i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="End date of report, inclusive"></i></span>
										</div>
										<input type="text" class="form-control jem-input-group-addon datepicker" id="date-to" value="<?php esc_attr_e($data->getDateTo()); ?>">
									</div>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-12">
									<button type="submit" id="export_data" class="btn btn-primary jem-dark-blue jem-input-group-addon jem-export-button jemx-predefined-range-export"><?php esc_attr_e('EXPORT','order-export-and-more-for-woocommerce'); ?></button>
								</div>

				            </div>
				        </div>
					</div>

					<?php $pre_ranges = jemx_predefined_date_ranges_data(); ?>

					<div id="jemx-predefined-ranges" class="panel-collapse collapse jem-toggle-target"  style="margin-top: 20px;">
						<div class="radio">
							<label class="radio-block">
								<input type="radio" name="predefinedGroupRadio" id="jemx-todays-orders" value="today" data-datefrom="<?php esc_attr_e($pre_ranges['today']['start_date']); ?>" data-dateto="<?php esc_attr_e( $pre_ranges['today']['end_date']); ?>" <?php checked( 'today', $data->getPredefinedDate() ); ?>> <?php esc_attr_e('Todays Orders','order-export-and-more-for-woocommerce'); ?>
							</label>
							<label class="radio-block">
								<input type="radio" name="predefinedGroupRadio" id="jemx-yesterdays-orders" value="yesterday" data-datefrom="<?php esc_attr_e($pre_ranges['yesterday']['start_date']); ?>" data-dateto="<?php esc_attr_e( $pre_ranges['yesterday']['end_date']); ?>" <?php checked( 'yesterday', $data->getPredefinedDate() ); ?>> <?php esc_attr_e('Yesterdays Orders','order-export-and-more-for-woocommerce'); ?>
							</label>
							<label class="radio-block">
								<input type="radio" name="predefinedGroupRadio" id="jemx-thisweeks-orders" value="thisweek" data-datefrom="<?php esc_attr_e($pre_ranges['thisweek']['start_date']); ?>" data-dateto="<?php esc_attr_e( $pre_ranges['thisweek']['end_date']); ?>" <?php checked( 'thisweek', $data->getPredefinedDate() ); ?>> <?php esc_attr_e('This weeks orders (Sun - Sat)','order-export-and-more-for-woocommerce'); ?>
							</label>
							<label class="radio-block">
								<input type="radio" name="predefinedGroupRadio" id="jemx-lastweeks-orders" value="lastweek" data-datefrom="<?php esc_attr_e($pre_ranges['lastweek']['start_date']); ?>" data-dateto="<?php esc_attr_e( $pre_ranges['lastweek']['end_date']); ?>" <?php checked( 'lastweek', $data->getPredefinedDate() ); ?>> <?php esc_attr_e('Last weeks orders (Sun - Sat)','order-export-and-more-for-woocommerce'); ?>
							</label>
							<label class="radio-block">
								<input type="radio" name="predefinedGroupRadio" id="jemx-monthtodate-orders" value="monthtodate" data-datefrom="<?php esc_attr_e($pre_ranges['monthtodate']['start_date']); ?>" data-dateto="<?php esc_attr_e( $pre_ranges['monthtodate']['end_date']); ?>" <?php checked( 'monthtodate', $data->getPredefinedDate() ); ?>> <?php esc_attr_e('Month to date orders','order-export-and-more-for-woocommerce'); ?>
							</label>
							<label class="radio-block">
								<input type="radio" name="predefinedGroupRadio" id="jemx-lastmonths-orders" value="lastmonth" data-datefrom="<?php esc_attr_e($pre_ranges['lastmonth']['start_date']); ?>" data-dateto="<?php esc_attr_e( $pre_ranges['lastmonth']['end_date']); ?>" <?php checked( 'lastmonth', $data->getPredefinedDate() ); ?>> <?php esc_attr_e('Last months orders','order-export-and-more-for-woocommerce'); ?>
							</label>
							<label class="radio-block">
								<input type="radio" name="predefinedGroupRadio" id="jemx-yeartodate-orders" value="yeartodate" data-datefrom="<?php esc_attr_e($pre_ranges['yeartodate']['start_date']); ?>" data-dateto="<?php esc_attr_e( $pre_ranges['yeartodate']['end_date']); ?>" <?php checked( 'yeartodate', $data->getPredefinedDate() ); ?>> <?php esc_attr_e('Year to date orders','order-export-and-more-for-woocommerce'); ?>
							</label>
						</div>
						<div>
							<button type="submit" id="export_data" class="btn btn-primary jem-dark-blue jem-input-group-addon jem-export-button jemx-predefined-range-export"><?php esc_attr_e('EXPORT','order-export-and-more-for-woocommerce'); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<!-- END export-date-range --->
