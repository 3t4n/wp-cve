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
	
	<form method="post" action="options.php" id="<?php echo $id; ?>">
		<?php echo $hidden_fields; ?>
		
		<?php echo $flash_message; ?>

		<table class="form-table">
			<?php echo $input_fields; ?>

			<tr valign="top">
				<th scope="row">
					<input type="submit" class="button-primary" value="<?php echo $submit; ?>" />
				</th>
				<td>&nbsp;</td>
			</tr>
		</table>
	</form>
</div>
<script type="text/javascript">
	jQuery('document').ready(function() {
		jQuery('#RefreshVboutSettings').click(function(e) { 
			e.preventDefault();
			
			jQuery('#RefreshVboutSettingsForm').submit();
		});
		
		jQuery('#vbout_default_dummyform').change(function(e) { 
			if (jQuery(this).val() != '') {			
				jQuery(this).next('span').remove();
				jQuery(this).after('<span style="font-weight: bold; padding-left: 10px;">[VbForm id=' + jQuery(this).val() + ']</span>');				
			}
		});	

		jQuery(document).on('click', '#RefreshVboutSettings', function(e) { 
			jQuery('#RefreshVboutSettings').hide();
			jQuery('.RefreshVboutSettingsLoader').show();
		});

		jQuery(document).on('click', '.showpasswordbtn', function(e) {
			$box = jQuery(this).parent();
			$input = $box.find('.showpasswordinput').attr('type');
			if($input=='password'){
				$box.find('.showpasswordinput').attr('type','text');
			}else{
				$box.find('.showpasswordinput').attr('type','password');
			}
		});		
	});
</script>