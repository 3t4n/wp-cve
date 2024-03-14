<?php

/**
 * Description: Template file for admin render settings
 * 
 * @package Channelize Shopping
 * 
 * 
 */
defined('ABSPATH') || exit; 
$options = get_option('channelize_live_shopping_settings');
$checkboxValue = isset($options['enableMiniPlayer']) ? $options['enableMiniPlayer'] : '';
?>
<div class="channelize_live_shopping_custom_setting">
	<h1>Live Shopping & Video Streams</h1>
	<div class="wrap" id="wpcf7-integration">
		<h1>Settings</h1>
		<p>If you open any Shopping Show on the store and click on the <b>Add-to-cart</b> or <b>Product know More </b>buttons,  mini-player will launch and appear in the right bottom corner of your screen as <a href="<?php echo CHLS_PLUGIN_URL.'assets/images/EnableMiniPlayer.png'?>" target="_blank" >shown here</a>.</p>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th>Enable Mini-Player</th>
					<td> 
						<label class="switch">
							<input type="checkbox" class="channelize_live_shopping_custom_setting_mini_player" 
							<?php echo ($checkboxValue == 'true')?"checked":"" ?> > 
							<span class="slider round"></span>
						</label>
					</td>
				</tr>
			</tbody>	
		</table>
	</div>		
</div>	

<script type="text/javascript">
	jQuery(document).ready(function() 
	{ 
		jQuery(".channelize_live_shopping_custom_setting .channelize_live_shopping_custom_setting_mini_player").on('change', function()
		{
			var enableMiniPlayer = jQuery(this).is(':checked');
			jQuery.ajax({
				type: 'POST',
				data: {enableMiniPlayer},
				url:  '<?php echo site_url('?ch-lsc-ajax=update_settings') ?>',
				success:function(res){
				}
			});
		});
	});
</script>