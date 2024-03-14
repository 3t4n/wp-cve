<?php
	if(!current_user_can('manage_options'))
	{
		die('Access Denied');
	}
	global $wpdb;

	$table_name3 = $wpdb->prefix . "totalsoft_ptable_manager";
	$Total_Soft_PT = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name3 WHERE id > %d", 0));
?>
<script type="text/javascript">
	jQuery(document).ready(function () {
		jQuery('#TS_PT_Media_Insert').on('click', function () {
			var id = jQuery('#TS_PT_Media_Select option:selected').val();
			window.send_to_editor('[Total_Soft_Pricing_Table id="' + id + '"]');
			tb_remove();
			return false;
		});
	});
</script>
<form method="POST">
	<div id="TSPTable" style="display: none;">
		<?php
			$new_ptable_link = admin_url('admin.php?page=Total_Soft_Pricing_Table');
			$new_ptable_link_n = wp_nonce_url( '', 'edit-menu_', 'TS_PTable_Nonce' );

			if ($Total_Soft_PT && !empty($Total_Soft_PT)) { ?>
				<h3>Select The Pricing Table</h3>
				<select id="TS_PT_Media_Select">
					<?php
						foreach ($Total_Soft_PT as $Total_Soft_PT1)
						{
							?> <option value="<?php echo $Total_Soft_PT1->id; ?>"> <?php echo $Total_Soft_PT1->Total_Soft_PTable_Title; ?> </option> <?php
						}
					?>
				</select>
				<button class='button primary' id='TS_PT_Media_Insert'>Insert Table</button>
			<?php } else {
				printf('<p>%s<a class="button" href="%s">%s</a></p>', 'You have not created any Pricing Tables yet' . '<br>', $new_ptable_link . $new_ptable_link_n, 'Create New Table');
			}
		?>
	</div>
</form>