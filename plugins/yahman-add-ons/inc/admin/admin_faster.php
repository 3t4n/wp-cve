<?php
defined( 'ABSPATH' ) || exit;
/**
 * Admin Faster Page
 *
 * @package YAHMAN Add-ons
 */
function yahman_addons_admin_faster($option,$option_key,$option_checkbox){

	foreach ($option_key['faster'] as $key => $value  ) {
		$faster[$key] = $option['faster'][$key];
	}

	foreach ($option_checkbox['faster'] as $key => $value  ) {
		$faster[$key] = isset($option['faster'][$key]) ? true: false;
	}

	?>

	<div id="ya_faster_content" class="tab_content ya_box_design">
		<h2><?php esc_html_e('Faster','yahman-add-ons'); ?></h2>

		<div class="ya_setting_content">
			<div class="ya_tooltip_wrap">
				<label for="faster_remove_line_breaks">
					<?php esc_html_e('Remove All Whitespace','yahman-add-ons'); ?>
				</label>
				<div class="ya_tooltip">
					<?php esc_html_e('Remove unnecessary whitespace and line breaks from the source code.','yahman-add-ons'); ?>
				</div>
			</div>
			<div class="ya_checkbox">
				<input name="yahman_addons[faster][remove_line_breaks]" type="checkbox" id="faster_remove_line_breaks"<?php checked(true, $faster['remove_line_breaks']); ?> class="ya_checkbox" />
				<label for="faster_remove_line_breaks"></label>
			</div>
		</div>

		<div class="ya_hr"></div>


		<div class="ya_setting_content">
			<div class="ya_tooltip_wrap">
				<label for="faster_preconnect_url">
					<?php esc_html_e('Preconnect url','yahman-add-ons'); ?>
				</label>
				<div class="ya_tooltip">
					<?php echo sprintf( esc_html__('Multiple %s must be separated by a comma.', 'yahman-add-ons') , esc_html__( 'URL', 'yahman-add-ons' ) ); ?>
				</div>
			</div>
			<textarea name="yahman_addons[faster][preconnect_url]" rows="4" cols="48" id="faster_preconnect_url" class="ya_textbox" /><?php echo $faster['preconnect_url']; ?></textarea>
		</div>




		<h3><?php esc_html_e('Cache','yahman-add-ons'); ?></h3>

		<div class="ya_setting_content">
			<div class="ya_tooltip_wrap">
				<label for="faster_cache">
					<?php esc_html_e('Cache','yahman-add-ons'); ?>
				</label>
				<div class="ya_tooltip">
					<?php esc_html_e('Create a cache pages.','yahman-add-ons'); ?>
				</div>
			</div>
			<div class="ya_checkbox">
				<input name="yahman_addons[faster][cache]" type="checkbox" id="faster_cache"<?php checked(true, $faster['cache']); ?> class="ya_checkbox" />
				<label for="faster_cache"></label>
			</div>
		</div>

		<div class="ya_setting_content">
			<div class="ya_tooltip_wrap">
				<label for="faster_cache_period">
					<?php esc_html_e('Cache period','yahman-add-ons'); ?>
				</label>
				<div class="ya_tooltip">
					<?php esc_html_e('Set the cache update period.','yahman-add-ons'); ?>
				</div>
			</div>
			<select name="yahman_addons[faster][cache_period]" id="faster_cache_period">
				<option value="1"<?php selected( $faster['cache_period'], '1' ); ?>>1</option>
				<option value="2"<?php selected( $faster['cache_period'], '2' ); ?>>2</option>
				<option value="3"<?php selected( $faster['cache_period'], '3' ); ?>>3</option>
				<option value="4"<?php selected( $faster['cache_period'], '4' ); ?>>4</option>
				<option value="5"<?php selected( $faster['cache_period'], '5' ); ?>>5</option>
				<option value="6"<?php selected( $faster['cache_period'], '6' ); ?>>6</option>
				<option value="7"<?php selected( $faster['cache_period'], '7' ); ?>>7</option>
				<option value="14"<?php selected( $faster['cache_period'], '14' ); ?>>14</option>
				<option value="21"<?php selected( $faster['cache_period'], '21' ); ?>>21</option>
				<option value="28"<?php selected( $faster['cache_period'], '28' ); ?>>28</option>
				</select><?php esc_html_e('Days','yahman-add-ons'); ?>
			</div>

			<div class="ya_setting_content">
				<div class="ya_tooltip_wrap">
					<label for="faster_cache_delete">
						<?php esc_html_e('Cache all delete','yahman-add-ons'); ?>
					</label>
					<div class="ya_tooltip">
						<?php esc_html_e('Delete all of the cache.','yahman-add-ons'); ?>
					</div>
				</div>
				<div class="ya_checkbox">
					<input name="yahman_addons_reset[faster][cache]" type="checkbox" id="faster_cache_delete" class="ya_checkbox" />
					<label for="faster_cache_delete"></label>
				</div>
			</div>

			<div class="ya_setting_content">
				<div class="ya_tooltip_wrap">
					<label for="faster_cache_post_not_in">
						<?php esc_html_e('Enter the post ID that does not use this function','yahman-add-ons'); ?>
					</label>
					<div class="ya_tooltip">
						<?php esc_html_e( 'The post ID you enter will not be cached.', 'yahman-add-ons'); ?>
						<br>
						<?php echo sprintf( esc_html__('Separate multiple %s with ,(comma).', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?>
					</div>
				</div>
				<input name="yahman_addons[faster][cache_post_not_in]" type="text" id="faster_cache_post_not_in" value="<?php echo $faster['cache_post_not_in']; ?>" class="widefat" />
			</div>

			<div class="ya_setting_content">
				<div class="ya_tooltip_wrap">
					<label for="faster_cache_parent_not_in">
						<?php esc_html_e('Enter the ID of the parent page that does not use this function','yahman-add-ons'); ?>
					</label>
					<div class="ya_tooltip">
						<?php esc_html_e( 'The post ID you enter will not be cached.', 'yahman-add-ons'); ?>
						<br>
						<?php echo sprintf( esc_html__('Separate multiple %s with ,(comma).', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?>
						<br>
						<?php esc_html_e( 'Child pages that belong to the parent page are also included.', 'yahman-add-ons'); ?>
						<br>
					</div>
				</div>
				<input name="yahman_addons[faster][cache_parent_not_in]" type="text" id="faster_cache_parent_not_in" value="<?php echo $faster['cache_parent_not_in']; ?>" class="widefat" />
			</div>





		</div>




		<?php
	}
