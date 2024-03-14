<!-- Snow Storm Settings -->

<?php
	
if (!defined('ABSPATH')) exit; // Exit if accessed directly	
	
$snowstorm_snowColor = get_option('snowstorm_snowColor');	
	
?>

<table class="form-table">
	<tbody>
		<tr>
			<th><label for="pp"><?php _e('Show on Posts/Pages', "snow-storm"); ?></label></th>
			<td>
				<?php $pp = get_option('snowstorm_pp'); ?>				
				<select name="pp[]" id="pp" multiple="multiple">
					<?php if (!empty($pp)) : ?>
						<?php foreach ($pp as $post_id) : ?>
							<option selected="selected" value="<?php echo $post_id; ?>"><?php echo get_the_title($post_id); ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
				
				<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('#pp').select2({
						ajax: {
						    url: ajaxurl + "?action=snowstorm_searchpp",
						    dataType: 'json',
						    delay: 250,
						    data: function (params) {
						      return {
						        q: params.term, // search term
						        page: params.page
						      };
						    },
						    processResults: function (data, params) {
						      // parse the results into the format expected by Select2
						      // since we are using custom formatting functions we do not need to
						      // alter the remote JSON data, except to indicate that infinite
						      // scrolling can be used
						      params.page = params.page || 1;
						 
						      return {
						        results: data.items,
						        pagination: {
						          more: (params.page * 30) < data.total_count
						        }
						      };
						    },
						    cache: true
					  	},
					  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
					  minimumInputLength: 1,
					}).next().css('width', "auto").css('min-width', "300px");
				});
				</script>
				
				<span class="howto"><small><?php _e('(optional)'); ?></small> <?php _e('Search and select specific posts/pages to show falling snow on.', "snow-storm"); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="snowColor"><?php _e('Snow Color', "snow-storm"); ?></label></th>
			<td>				
				<input type="text" name="snowColor" value="<?php echo esc_attr(stripslashes($snowstorm_snowColor)); ?>" id="snowColor" />
				
				<script type="text/javascript">
				jQuery(document).ready(function() {
					if (jQuery.isFunction(jQuery.fn.wpColorPicker)) {
						jQuery('#snowColor').wpColorPicker();
					}
				});
				</script>
			</td>
		</tr>
		<tr>
			<th><label for="flakesMax"><?php _e('Total Amount of Snow', "snow-storm"); ?></label></th>
			<td>
				<input class="widefat" style="width:65px;" type="text" name="flakesMax" value="<?php echo esc_attr(stripslashes(get_option('snowstorm_flakesMax'))); ?>" id="flakesMax" />
				<span class="howto"><?php _e('Limit the total amount of snow made (falling + sticking). Default value is 128.', "snow-storm"); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="flakesMaxActive"><?php _e('Falling Amount of Snow', "snow-storm"); ?></label></th>
			<td>
				<input type="text" name="flakesMaxActive" value="<?php echo esc_attr(stripslashes(get_option('snowstorm_flakesMaxActive'))); ?>" id="flakesMaxActive" class="widefat" style="width:65px;" />
				<span class="howto"><?php _e('Limit the amount of snow falling at once. Default value is 64.', "snow-storm"); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="animationInterval"><?php _e('Animation Interval', "snow-storm"); ?></label></th>
			<td>
				<input type="text" name="animationInterval" value="<?php echo esc_attr(stripslashes(get_option('snowstorm_animationInterval'))); ?>" id="animationInterval" class="widefat" style="width:65px;" />
				<span class="howto"><?php _e('Speed of the animation. 25 is fast and smooth with high CPU usage, while 50 is conservative and slower.', "snow-storm"); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="excludeMobile_Y"><?php _e('Mobile Support', "snow-storm"); ?></label></th>
			<td>
				<label><input <?php echo (get_option('snowstorm_excludeMobile') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="excludeMobile" value="N" id="excludeMobile_N" /> <?php _e('On', "snow-storm"); ?></label>
				<label><input <?php echo (get_option('snowstorm_excludeMobile') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="excludeMobile" value="Y" id="excludeMobile_Y" /> <?php _e('Off', "snow-storm"); ?></label>
				<span class="howto"><?php _e('Animated snow is not recommended for mobiles due to CPU and batteries.', "snow-storm"); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="followMouse_N"><?php _e('Follow Mouse Cursor', "snow-storm"); ?></label></th>
			<td>
				<label><input <?php echo (get_option('snowstorm_followMouse') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="followMouse" value="Y" id="followMouse_Y" /> <?php _e('Yes', "snow-storm"); ?></label>
				<label><input <?php echo (get_option('snowstorm_followMouse') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="followMouse" value="N" id="followMouse_N" /> <?php _e('No', "snow-storm"); ?></label>
				<span class="howto"><?php _e('Should the snow movement respond to the users mouse cursor?', "snow-storm"); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="snowStick_Y"><?php _e('Snow Stick', "snow-storm"); ?></label></th>
			<td>
				<label><input <?php echo (get_option('snowstorm_snowStick') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="snowStick" value="Y" id="snowStick_Y" /> <?php _e('On', "snow-storm"); ?></label>
				<label><input <?php echo (get_option('snowstorm_snowStick') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="snowStick" value="N" id="snowStick_N" /> <?php _e('Off', "snow-storm"); ?></label>
				<span class="howto"><?php _e('Whether or not the snow should "stick" at the bottom of the screen.', "snow-storm"); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="useMeltEffect_Y"><?php _e('Melt Effect', "snow-storm"); ?></label></th>
			<td>
				<label><input <?php echo (get_option('snowstorm_useMeltEffect') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="useMeltEffect" value="Y" id="useMeltEffect_Y" /> <?php _e('On', "snow-storm"); ?></label>
				<label><input <?php echo (get_option('snowstorm_useMeltEffect') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="useMeltEffect" value="N" id="useMeltEffect_N" /> <?php _e('Off', "snow-storm"); ?></label>
				<span class="howto"><?php _e('When recycling falling snow, have it melt if the browser supports it.', "snow-storm"); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="useTwinkleEffect_N"><?php _e('Twinkle Effect', "snow-storm"); ?></label></th>
			<td>
				<label><input <?php echo (get_option('snowstorm_useTwinkleEffect') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="useTwinkleEffect" value="Y" id="useTwinkleEffect_Y" /> <?php _e('On', "snow-storm"); ?></label>
				<label><input <?php echo (get_option('snowstorm_useTwinkleEffect') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="useTwinkleEffect" value="N" id="useTwinkleEffect_N" /> <?php _e('Off', "snow-storm"); ?></label>
				<span class="howto"><?php _e('Allow snow to "twinkle" in and out while falling.', "snow-storm"); ?></span>
			</td>
		</tr>
	</tbody>
</table>