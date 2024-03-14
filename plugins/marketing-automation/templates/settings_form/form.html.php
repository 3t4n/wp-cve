<div class="wrap">
	<div id="{icon}" class="icon32">
		<br />
	</div>

	<h2><?php echo $title; ?></h2>

	<form id="RefreshVboutSettingsForm" method="post" action="options.php">
		<input type="hidden" value="vbout-settings" name="option_page">
		<input type="hidden" value="refresh" name="action">
		<input id="_wpnonce" type="hidden" value="4d29588777" name="_wpnonce">
		<input type="hidden" value="/wp-admin/admin.php?page=vbout-settings" name="_wp_http_referer">
	</form>
	
	<form method="post" action="options.php">
		<?php echo $hidden_fields; ?>
		
		<?php echo $api_status; ?>

		<table class="form-table">
			<?php echo $input_fields; ?>

			<tr valign="top">
				<th scope="row">
					<label>&nbsp;</label>
				</th>
				<td>
					<input type="submit" class="button-primary" value="<?php echo $submit; ?>" />
				</td>
			</tr>
		</table>
	</form>
</div>
<script type="text/javascript">
	jQuery('document').ready(function() {
		jQuery('document').on('click', '#RefreshVboutSettings', function(e) { 
			e.preventDefault();
			
			jQuery('#RefreshVboutSettingsForm').submit();
		});
	});
</script>