<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="trackship_admin_content">
	<section class="trackship_analytics_section">
		<div class="woocommerce trackship_admin_layout">
			<div class="">
				<input type="hidden" id="nonce_trackship_shipments" value="<?php echo esc_attr( wp_create_nonce( '_trackship_shipments' ) ); ?>">
				<table class="widefat dataTable fixed fullfilments_table hover" cellspacing="0" id="active_shipments_table" style="width: 100%;">
					<thead>
						<tr class="tabel_heading_th">
							<th id="columnname" class="manage-column column-columnname" scope="col"><input type="checkbox" class="all_checkboxes"></th>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php esc_html_e('Order', 'woocommerce'); ?></th>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php esc_html_e('Shipped date', 'trackship-for-woocommerce'); ?></th>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php esc_html_e('Updated at', 'trackship-for-woocommerce'); ?></th>
							<th id="columnname" class="manage-column column-destination" scope="col"><?php esc_html_e('Tracking Number', 'trackship-for-woocommerce'); ?></th>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php esc_html_e('Shipping carrier', 'trackship-for-woocommerce'); ?></th>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php esc_html_e('Shipment status', 'trackship-for-woocommerce'); ?></th>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php esc_html_e('Ship from', 'trackship-for-woocommerce'); ?></th>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php esc_html_e('Ship to', 'trackship-for-woocommerce'); ?></th>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php esc_html_e('Ship State', 'trackship-for-woocommerce'); ?></th>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php esc_html_e('Ship City', 'trackship-for-woocommerce'); ?></th>
							<th id="columnname" class="manage-column column-destination" scope="col"><?php esc_html_e('Latest Event', 'trackship-for-woocommerce'); ?></th>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php esc_html_e('Customer', 'trackship-for-woocommerce'); ?></th>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php esc_html_e('Shipping time', 'trackship-for-woocommerce'); ?></th>
							<th id="columnname" class="manage-column column-destination" scope="col"><?php esc_html_e('Delivery date', 'trackship-for-woocommerce'); ?></th>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php esc_html_e('Actions', 'trackship-for-woocommerce'); ?></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</section>
</div>
