<?php
	global $wpdb;
	$ts_pg_manager_table = $wpdb->prefix . "totalsoft_portfolio_manager";
	$ts_pg_list = $wpdb->get_results($wpdb->prepare("SELECT * FROM $ts_pg_manager_table WHERE id>%d order by id", 0));
	$new_port_link = admin_url('admin.php?page=Total_Soft_Portfolio');
	$ts_pg_select_block = "";
	if ($ts_pg_list && !empty($ts_pg_list)) {
		$ts_pg_select_list = "";
		foreach ($ts_pg_list as $ts_pg_list_option) {
			$ts_pg_select_list .= sprintf(
				'
					<option value="%1$s"> %2$s </option>
				',
				esc_attr($ts_pg_list_option->id),
				esc_html($ts_pg_list_option->TotalSoftPortfolio_Title)
			);
		}
		$ts_pg_select_block .= sprintf(
			'
				<h3>Select The Portfolio</h3>
				<select id="TS_Port_Media_Select">
					%1$s
				</select>
				<button class="button primary" id="TS_Port_Media_Insert">Insert Portfolio</button>
			',
			$ts_pg_select_list
		);
	} else {
		$ts_pg_select_block .= sprintf(
			'
				<p>
					%1$s
					<a class="button" href="%2$s">
						%3$s
					</a>
				</p>
			',
			"You have not created any portfolios yet",
			$new_port_link,
			"Create New Portfolio"
		);
	}
	echo sprintf(
		'
		<script type="text/javascript">
			jQuery(document).ready(function () {
				jQuery("#TS_Port_Media_Insert").on("click", function () {
					var id = jQuery("#TS_Port_Media_Select option:selected").val();
					window.send_to_editor(`[Total_Soft_Portfolio id="${id}"]`);
					tb_remove();
					return false;
				});
			});
		</script>
		<form method="POST">
			<div id="TSPortfolio" style="display: none;">
				%1$s
			</div>
		</form>
		',
		$ts_pg_select_block
	);
?>
