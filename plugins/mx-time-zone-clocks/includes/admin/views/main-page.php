<div class="mx-main-page-text-wrap">

	<?php mxmtzc_include_view( 'components/hire-developer' ); ?>
	<?php mxmtzc_include_view( 'components/olena-theme' ); ?>

	<!-- <nav class="mxmlb_admin_nav_bar">
		
			<ul>
				<li class="mxmlb_active_item_menu">
					<a href="<?php echo admin_url(); ?>admin.php?page=mxmtzc-mx-time-zone-clocks-menu">Main settings</a>			
				</li>
				<li class="">
					<a href="<?php echo admin_url(); ?>admin.php?page=mx_clocks_additional_plugins">Additional Plugins</a>			
				</li>
				<li class=" mx-offer-link">
					<a href="<?php echo admin_url(); ?>admin.php?page=mx_clocks_offer">Need help with WordPress?</a>
				</li>
			</ul>

		</nav> -->

	<!-- <div class="mxmtzc_review">

		<p>
			Is this plugin useful for you? Please leave positive feedback <a href="https://wordpress.org/plugins/mx-time-zone-clocks/#reviews" target="_blank">here</a>.
		</p>

		<p>
			Thank you!
		</p>

	</div> -->

	<!-- display clock -->
	<div class="mxmlb_mx-block_wrap">

		<h3><?php echo __('How to display the clock', 'mxmtzc-domain'); ?></h3>

		<p>
			<?php echo __('Choose a way to display the clock', 'mxmtzc-domain'); ?>
		</p>

		<p>
			<?php echo __('By default', 'mxmtzc-domain'); ?> - <b>Clock with arrows</b>
		</p>

		<p class="mxmtzc_display_clock_wrap">

			<input type="radio" id="mxmtzc_display_clock2" value="arrow" name="mxmtzc_display_clock" checked="checked" />
			<label for="mxmtzc_display_clock2"><?php echo __('Clock with arrows', 'mxmtzc-domain'); ?></label>

		</p>

		<p class="mxmtzc_display_clock_wrap">

			<input type="radio" id="mxmtzc_display_clock1" value="digital" name="mxmtzc_display_clock" />
			<label for="mxmtzc_display_clock1"><?php echo __('Digital clock', 'mxmtzc-domain'); ?></label>

		</p>

	</div>

	<div class="mxmlb_mx-block_wrap mx-clock-apperiance_box">

		<?php

		$array_of_clock_disign = array('clock-face30.png', 'clock-face29.png', 'clock-face28.png', 'clock-face27.png', 'clock-face26.png', 'clock-face1.png', 'clock-face2.png', 'clock-face4.png', 'clock-face5.png', 'clock-face6.png', 'clock-face7.png', 'clock-face8.jpg', 'clock-face9.jpg', 'clock-face10.png', 'clock-face11.png', 'clock-face12.png', 'clock-face13.jpg', 'clock-face14.jpg', 'clock-face15.jpg', 'clock-face17.jpg', 'clock-face18.png', 'clock-face19.png', 'clock-face20.jpg', 'clock-face21.png', 'clock-face22.jpg', 'clock-face23.png', 'clock-face24.png', 'clock-face25.png');

		?>

		<h3><?php echo __('Available design of clock', 'mxmtzc-domain'); ?></h3>

		<p><?php echo __('You should click on the clock, the design you like.', 'mxmtzc-domain'); ?></p>

		<p>
			<?php echo __('By default', 'mxmtzc-domain'); ?> - <b>the first clock</b>
		</p>

		<div class="mx-time-zone-design-list">

			<?php foreach ($array_of_clock_disign as $key => $value) : ?>

				<div class="mx-time-zone-design-item">
					<img src="<?php echo MXMTZC_PLUGIN_URL; ?>includes/admin/assets/img/<?php echo $value; ?>" data-image-src="<?php echo $value; ?>" alt="">
				</div>

			<?php endforeach; ?>

		</div>

	</div>

	<!-- upload clock -->
	<div class="mxmlb_mx-block_wrap">

		<h3><?php echo __('Upload clock', 'mxmtzc-domain'); ?></h3>

		<h4><?php echo __('The recommended size of image is 120x120px.', 'mxmtzc-domain'); ?></h4>

		<!-- image upload -->
		<div class="mx-image-uploader">

			<?php

			$image_url = '';

			?>

			<button class="mxmtzc_upload_image" <?php echo $image_url ? 'style="display: none;"' : ''; ?>>Choose image</button>

			<!-- here we will save an id of image -->
			<input name="mxmtzc_clock_upload" id="mxmtzc_clock_upload" type="hidden" class="mxmtzc_upload_image_save" value="" />

			<!-- show an image -->
			<img src="<?php echo $image_url ? $image_url : ''; ?>" style="width: 120px;" alt="" class="mxmtzc_upload_image_show" <?php echo $image_url ? 'style="display: none;"' : ''; ?> />

			<!-- remove image -->
			<a href="#" class="mxmtzc_upload_image_remove" <?php echo !$image_url ? 'style="display: none;"' : ''; ?>>Remove Image</a>

		</div>

		<h4><?php echo __('How can I resize an image?', 'mxmtzc-domain'); ?></h4>

		<div class="mxmtzc_upload_clock_resize">
			<a href="<?php echo MXMTZC_PLUGIN_URL; ?>includes/admin/assets/img/resize-1.jpg" target="_blank"><img src="<?php echo MXMTZC_PLUGIN_URL; ?>includes/admin/assets/img/resize-1.jpg" alt=""></a>
			<a href="<?php echo MXMTZC_PLUGIN_URL; ?>includes/admin/assets/img/resize-2.jpg" target="_blank"><img src="<?php echo MXMTZC_PLUGIN_URL; ?>includes/admin/assets/img/resize-2.jpg" alt=""></a>
		</div>

	</div>

	<!-- time zone -->
	<div class="mxmlb_mx-block_wrap">

		<h3><?php echo __('Set Time Zone', 'mxmtzc-domain'); ?></h3>

		<p>
			<?php echo __('Here you can find all time zones:', 'mxmtzc-domain'); ?>
			<a href="https://timezonedb.com/time-zones" target="_blank">timezonedb.com/</a>
		</p>

		<p>
			<?php echo __('If you want set time zone, fill it below.', 'mxmtzc-domain'); ?>
		</p>

		<p>
			<?php echo __('By default', 'mxmtzc-domain'); ?> - <b>Australia/Sydney</b>
		</p>

		<p>
			<input type="text" id="mxmtzc_time_zone_name" placeholder="<?php echo __('Australia/Sydney', 'mxmtzc-domain'); ?>" />
		</p>

	</div>

	<!-- city name -->
	<div class="mxmlb_mx-block_wrap">

		<h3><?php echo __('Set name of city', 'mxmtzc-domain'); ?></h3>

		<p>
			<?php echo __('If you want set name of the city, fill it below.', 'mxmtzc-domain'); ?>
		</p>

		<p>
			<?php echo __('By default', 'mxmtzc-domain'); ?> - <b>Wilton</b>
		</p>

		<p>
			<input type="text" id="mxmtzc_city_name" placeholder="<?php echo __('Wilton', 'mxmtzc-domain'); ?>" />
		</p>

	</div>

	<!-- format of date -->
	<div class="mxmlb_mx-block_wrap">

		<h3><?php echo __('Set the time format', 'mxmtzc-domain'); ?></h3>

		<p>
			<?php echo __('Select one option', 'mxmtzc-domain'); ?>
		</p>

		<p>
			<?php echo __('By default', 'mxmtzc-domain'); ?> - <b>12</b>
		</p>

		<p class="mxmtzc_time_format_wrap">

			<input type="radio" id="mxmtzc_time_format1" value="12" name="mxmtzc_time_format" checked="checked" />
			<label for="mxmtzc_time_format1"><?php echo __('12-Hour Time Format', 'mxmtzc-domain'); ?></label>

		</p>

		<p class="mxmtzc_time_format_wrap">

			<input type="radio" id="mxmtzc_time_format2" value="24" name="mxmtzc_time_format" />
			<label for="mxmtzc_time_format2"><?php echo __('24-Hour Time Format', 'mxmtzc-domain'); ?></label>

		</p>

	</div>

	<!-- language -->
	<div class="mxmlb_mx-block_wrap">

		<h3><?php echo __('Set the language attribute to clock', 'mxmtzc-domain'); ?></h3>

		<p>
			<?php echo __('Here you can find all attributes of the language:', 'mxmtzc-domain'); ?>
			<a href="https://timezonedb.com/time-zones" target="_blank">timezonedb.com/</a>
		</p>

		<p>
			<?php echo __('By default', 'mxmtzc-domain'); ?> - <b>en</b>
		</p>

		<p>
			<input type="text" id="mxmtzc_language_attr" placeholder="<?php echo __('en', 'mxmtzc-domain'); ?>" />
		</p>

	</div>

	<!-- language -->
	<div class="mxmlb_mx-block_wrap">

		<h3><?php echo __('Set the language attribute to days', 'mxmtzc-domain'); ?></h3>

		<p>
			<?php echo __('Here you can find all attributes of the language:', 'mxmtzc-domain'); ?>
			<a href="https://timezonedb.com/time-zones" target="_blank">timezonedb.com/</a>
		</p>

		<p>
			<?php echo __('By default', 'mxmtzc-domain'); ?> - <b>en</b>
		</p>

		<p>
			<input type="text" id="mxmtzc_language_for_days_attr" placeholder="<?php echo __('en', 'mxmtzc-domain'); ?>" />
		</p>

	</div>

	<!-- show days -->
	<div class="mxmlb_mx-block_wrap">

		<h3><?php echo __('Show day, month and year', 'mxmtzc-domain'); ?></h3>

		<p>
			<?php echo __('By default', 'mxmtzc-domain'); ?> - <b>Shown</b>
		</p>

		<p class="mxmtzc_show_days_wrap">

			<input type="radio" id="mxmtzc_show_days1" value="show" name="mxmtzc_show_days" checked="checked" />
			<label for="mxmtzc_show_days1"><?php echo __('Shown', 'mxmtzc-domain'); ?></label>

		</p>

		<p class="mxmtzc_show_days_wrap">

			<input type="radio" id="mxmtzc_show_days2" value="hidden" name="mxmtzc_show_days" />
			<label for="mxmtzc_show_days2"><?php echo __('Hidden', 'mxmtzc-domain'); ?></label>

		</p>

	</div>

	<!-- font size -->
	<div class="mxmlb_mx-block_wrap">

		<h3><?php echo __('Set clock\'s font size', 'mxmtzc-domain'); ?></h3>

		<p>
			<?php echo __('By default', 'mxmtzc-domain'); ?> - <b>Depends on your theme settings</b>
		</p>

		<p>
			<input type="number" id="mxmtzc_font_size_attr" /> <span>px</span>
		</p>

	</div>

	<!-- show seconds -->
	<div class="mxmlb_mx-block_wrap">

		<h3><?php echo __('Show seconds in the clock', 'mxmtzc-domain'); ?></h3>

		<p>
			<?php echo __('By default', 'mxmtzc-domain'); ?> - <b>Shown</b>
		</p>

		<p class="mxmtzc_show_seconds_wrap">

			<input type="radio" id="mxmtzc_show_seconds1" value="show" name="mxmtzc_show_seconds" checked="checked" />
			<label for="mxmtzc_show_seconds1"><?php echo __('Shown', 'mxmtzc-domain'); ?></label>

		</p>

		<p class="mxmtzc_show_seconds_wrap">

			<input type="radio" id="mxmtzc_show_seconds2" value="hidden" name="mxmtzc_show_seconds" />
			<label for="mxmtzc_show_seconds2"><?php echo __('Hidden', 'mxmtzc-domain'); ?></label>

		</p>

	</div>

	<!-- arrow type -->
	<div class="mxmlb_mx-block_wrap">

		<h3><?php echo __('Type of arrows', 'mxmtzc-domain'); ?></h3>

		<p>
			<?php echo __('By default', 'mxmtzc-domain'); ?> - <b>Classical</b>
		</p>

		<p class="mxmtzc_show_days_wrap">

			<input type="radio" id="mxmtzc_arrow_type1" value="classical" name="mxmtzc_arrow_type" checked="checked" />
			<label for="mxmtzc_arrow_type1"><?php echo __('Classical', 'mxmtzc-domain'); ?></label>

		</p>

		<p class="mxmtzc_show_days_wrap">

			<input type="radio" id="mxmtzc_arrow_type2" value="modern" name="mxmtzc_arrow_type" />
			<label for="mxmtzc_arrow_type2"><?php echo __('Modern', 'mxmtzc-domain'); ?></label>

		</p>

	</div>

	<!-- super simple clock -->
	<div class="mxmlb_mx-block_wrap">

		<h3><?php echo __('Super Simple Clock', 'mxmtzc-domain'); ?></h3>

		<p>
			<?php echo __('By default', 'mxmtzc-domain'); ?> - <b>False</b>
		</p>

		<p class="mxmtzc_super_simple_wrap">

			<input type="radio" id="mxmtzc_super_simple1" value="false" name="mxmtzc_super_simple" checked="checked" />
			<label for="mxmtzc_super_simple1"><?php echo __('False', 'mxmtzc-domain'); ?></label>

		</p>

		<p class="mxmtzc_super_simple_wrap">

			<input type="radio" id="mxmtzc_super_simple2" value="true" name="mxmtzc_super_simple" />
			<label for="mxmtzc_super_simple2"><?php echo __('True', 'mxmtzc-domain'); ?></label>

		</p>

	</div>


	<!-- arrows color -->
	<div class="mxmlb_mx-block_wrap mxmlb_arrows_color_wrapper">

		<h3><?php echo __('Arrows Color', 'mxmtzc-domain'); ?></h3>

		<p>
			<?php echo __('By default', 'mxmtzc-domain'); ?> - <b>Unset</b>
		</p>

		<div class="mxmtzc_arrows_color_wrap mxmtzc_df">

			<input type="radio" id="mxmtzc_arrows_color1" value="unset" name="mxmtzc_arrows_color" checked="checked" />
			<label for="mxmtzc_arrows_color1"><?php echo __('Unset', 'mxmtzc-domain'); ?></label>
			<div class="mxmtzc_arrows_color_show" style="width: 20px; height: 20px; background: white;"></div>

		</div>

		<div class="mxmtzc_arrows_color_wrap mxmtzc_df">

			<input type="radio" id="mxmtzc_arrows_color2" value="white" name="mxmtzc_arrows_color" />
			<label for="mxmtzc_arrows_color2"><?php echo __('White', 'mxmtzc-domain'); ?></label>
			<div class="mxmtzc_arrows_color_show" style="width: 20px; height: 20px; background: white;"></div>

		</div>

		<div class="mxmtzc_arrows_color_wrap mxmtzc_df">

			<input type="radio" id="mxmtzc_arrows_color5" value="pink" name="mxmtzc_arrows_color" />
			<label for="mxmtzc_arrows_color5"><?php echo __('Pink', 'mxmtzc-domain'); ?></label>
			<div class="mxmtzc_arrows_color_show" style="width: 20px; height: 20px; background: pink;"></div>

		</div>

		<div class="mxmtzc_arrows_color_wrap mxmtzc_df">

			<input type="radio" id="mxmtzc_arrows_color4" value="red" name="mxmtzc_arrows_color" />
			<label for="mxmtzc_arrows_color4"><?php echo __('Red', 'mxmtzc-domain'); ?></label>
			<div class="mxmtzc_arrows_color_show" style="width: 20px; height: 20px; background: red;"></div>

		</div>
		
		<div class="mxmtzc_arrows_color_wrap mxmtzc_df">

			<input type="radio" id="mxmtzc_arrows_color6" value="green" name="mxmtzc_arrows_color" />
			<label for="mxmtzc_arrows_color6"><?php echo __('Green', 'mxmtzc-domain'); ?></label>
			<div class="mxmtzc_arrows_color_show" style="width: 20px; height: 20px; background: green;"></div>

		</div>

		<div class="mxmtzc_arrows_color_wrap mxmtzc_df">

			<input type="radio" id="mxmtzc_arrows_color7" value="black" name="mxmtzc_arrows_color" />
			<label for="mxmtzc_arrows_color7"><?php echo __('Black', 'mxmtzc-domain'); ?></label>
			<div class="mxmtzc_arrows_color_show" style="width: 20px; height: 20px; background: black;"></div>

		</div>

		<div class="mxmtzc_arrows_color_wrap mxmtzc_df">

			<input type="radio" id="mxmtzc_arrows_color3" value="blue" name="mxmtzc_arrows_color" />
			<label for="mxmtzc_arrows_color3"><?php echo __('Blue', 'mxmtzc-domain'); ?></label>
			<div class="mxmtzc_arrows_color_show" style="width: 20px; height: 20px; background: blue;"></div>

		</div>

		<div class="mxmtzc_arrows_color_wrap mxmtzc_df">

			<input type="radio" id="mxmtzc_arrows_color8" value="yellow" name="mxmtzc_arrows_color" />
			<label for="mxmtzc_arrows_color8"><?php echo __('Yellow', 'mxmtzc-domain'); ?></label>
			<div class="mxmtzc_arrows_color_show" style="width: 20px; height: 20px; background: yellow;"></div>

		</div>

	</div>

	<!-- shortcode -->
	<div class="mxmlb_mx-block_wrap" id="mx_time_zone_shortcode">

		<div class="mx-time-zone-shortcode">

			<span><?php echo __('Copy this shortcode to any page or article:', 'mxmtzc-domain'); ?></span>
			<div class="mxmtzc_time_zone_shortcode_body">[mxmtzc_time_zone_clocks time_zone="<span id="mxmtzc_time_zone_value">Australia/Sydney</span>"
				city_name="<span id="mxmtzc_city_name_value">Wilton</span>"
				time_format="<span id="mxmtzc_time_format_value">12</span>"
				digital_clock="<span id="mxmtzc_digital_clock_value">false</span>"
				lang="<span id="mxmtzc_lang_value">en</span>"
				lang_for_date="<span id="mxmtzc_language_for_days_attr_value">en</span>"
				clock_type="<span id="mxmtzc_clock_type_value">clock-face2.png</span>"
				show_days="<span id="mxmtzc_show_days_value">true</span>" clock_font_size="<span id="mxmtzc_font_size_attr_value"></span>"
				show_seconds="<span id="mxmtzc_show_seconds_value">true</span>"
				arrow_type="<span id="mxmtzc_arrow_type_value">classical</span>"
				super_simple="<span id="mxmtzc_super_simple_value">false</span>"
				arrows_color="<span id="mxmtzc_arrows_color_value">unset</span>"
				clock_upload="<span id="mxmtzc_clock_upload_value">false</span>"]</div>

			<button id="mxCopyShortcode" class="mxmtzc_copy_button">
				<i class="fa fa-clone" aria-hidden="true"></i>
			</button>

		</div>

	</div>

</div>

<!-- save notice -->
<div class="mxmtzc_save_notice">
	<p>
		<?php echo __('Your shortcode has been updated. You can copy it below.', 'mxmtzc-domain'); ?>
	</p>
	<i class="icon-arrow-down"></i>
</div>