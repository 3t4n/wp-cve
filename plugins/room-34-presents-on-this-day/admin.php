<?php

add_action('restrict_manage_posts', 'r34otd_restrict_manage_posts', 10, 0);

function r34otd_restrict_manage_posts() {
	global $pagenow, $typenow;
	if (is_admin() && $pagenow == 'edit.php' && $typenow = 'post') {
		?>
		<select name="monthnum">
			<option value=""><?php _e('All months', 'r34otd'); ?></option>
			<?php
			for ($i = 1; $i <= 12; $i++) {
				?>
				<option value="<?php echo intval($i); ?>"<?php
				if ($i == get_query_var('monthnum')) {
					echo ' selected="selected"';
				}
				?>><?php echo wp_date('F', mktime(0,0,0,$i,2,date('Y'))); ?></option>
				<?php
			}
			?>
		</select>
		<select name="day">
			<option value=""><?php _e('All days', 'r34otd'); ?></option>
			<?php
			for ($i = 1; $i <= 31; $i++) {
				?>
				<option value="<?php echo intval($i); ?>"<?php
				if ($i == get_query_var('day')) {
					echo ' selected="selected"';
				}
				?>><?php echo $i; ?></option>
				<?php
			}
			?>
		</select>
		<?php
	}
}

add_action('admin_menu', function() {
	add_options_page(
		__('On This Day', 'r34otd'),
		__('On This Day', 'r34otd'),
		'edit_posts',
		'r34otd',
		'r34otd_options_page',
		null
	);
}, 10);

function r34otd_options_page() {

	// We need this later
	$plugin_data = get_plugin_data(plugin_dir_path(__FILE__) . 'r34-on-this-day.php');

	?>
	<div class="wrap r34otd">
		
		<h2><?php echo get_admin_page_title(); ?></h2>
		
		<div class="metabox-holder columns-2">
	
			<div class="column-1">
			
				<div class="postbox" id="widget-guide">
				
					<h3 class="hndle"><span><?php _e('Widget Guide', 'r34otd'); ?></span></h3>
					
					<div class="inside">
					
						<p><?php printf(__('If your theme supports sidebars, you can use On This Day as a %1$swidget%2$s. All options described below are directly configurable using the Widget interface.', 'r34otd'), '<a href="' . admin_url('widgets.php') . '">', '</a>'); ?></p>

					</div>
				
				</div>
	
				<div class="postbox" id="shortcode-guide">
				
					<h3 class="hndle"><span><?php _e('Shortcode Guide', 'r34otd'); ?></span></h3>
				
					<div class="inside">
					
						<p><?php printf(__('You can insert On This Day anywhere in your content that supports shortcodes. Using the default settings is as simple as entering the shortcode:<br /><br />%1$s', 'r34otd'), '<code>[on_this_day]</code>'); ?></p>
					
						<p><?php printf(__('The following options are also available to customize your shortcode. Each should be inserted inside the square brackets as such: %1$s', 'r34otd'), '<br /><br /><code>[on_this_day categories="news,events" title="On This Day"]</code>'); ?></p>
					
						<dl>
							<dt>after_title</dt>
							<dd><?php printf(__('HTML code to insert after the title. Must be used with %1$s. %2$s Default value: %3$s', 'r34otd'), '<strong>before_title</strong>', '<br />', '<code>&lt;/h3&gt;</code>'); ?></dd>
						
							<dt>after_widget</dt>
							<dd><?php printf(__('HTML code to insert after the entire block. Must be used with %1$s. %2$s Default value: %3$s', 'r34otd'), '<strong>before_widget</strong>', '<br />', '<code>&lt;/aside&gt;</code>'); ?></dd>
						
							<dt>before_title</dt>
							<dd><?php printf(__('HTML code to insert before the title. Must be used with %1$s. %2$s Default value: %3$s', 'r34otd'), '<strong>after_title</strong>', '<br />', '<code>&lt;h3 class="widget-title"&gt;</code>'); ?></dd>
						
							<dt>before_widget</dt>
							<dd><?php printf(__('HTML code to insert after the entire block. Must be used with %1$s. %2$s Default value: %3$s', 'r34otd'), '<strong>before_widget</strong>', '<br />', '<code>&lt;aside class="widget widget_r34otd"&gt;</code>'); ?></dd>
						
							<dt>categories</dt>
							<dd><?php _e('A comma-separated list of categories to include. Accepts either category term IDs or slugs (or a combination of both). Omit or leave blank to include all categories.', 'r34otd'); ?></dd>
						
							<dt>day</dt>
							<dd><?php printf(__('Use with %1$s to retrieve posts for an arbitrary date instead of the current date. Must be a number between 1 and 31.', 'r34otd'), '<strong>month</strong>'); ?></dd>
						
							<dt>month</dt>
							<dd><?php printf(__('Use with %1$s to retrieve posts for an arbitrary date instead of the current date. Must be a number between 1 and 12.', 'r34otd'), '<strong>day</strong>'); ?></dd>
						
							<dt>no_posts_message</dt>
							<dd><?php printf(__('Text to display when there are no matching posts. %1$s Default value: %2$s', 'r34otd'), '<br />', '<code>' . __('Nothing has ever happened on this day. Ever.', 'r34otd') . '</code>'); ?></dd>
						
							<dt>posts_per_page</dt>
							<dd><?php printf(__('Maximum number of posts to retrieve. %1$s Default value: %2$s', 'r34otd'), '<br />', '<code>10</code>'); ?></dd>
						
							<dt>see_all_link_text</dt>
							<dd><?php printf(__('Text to display with link to archive. Only displays if %1$s is set to %2$s %3$s Default value: %4$s', 'r34otd'), '<strong>show_archive_link</strong>', '<code>true</code>.', '<br />', '<code>' . __('See all...', 'r34otd') . '</code>'); ?></dd>
						
							<dt>show_archive_link</dt>
							<dd><?php printf(__('Set to %1$s to add a link to a full archive page of all posts for the currently selected date below the list. Link will display the text set for %2$s.', 'r34otd'), '<code>true</code>', '<strong>see_all_link_text</strong>'); ?></dd>
						
							<dt>show_post_date</dt>
							<dd><?php printf(__('Set to %1$s to display full post dates in the list. (Note: The year of the post will always be displayed.)', 'r34otd'), '<code>true</code>'); ?></dd>
						
							<dt>show_post_excerpt</dt>
							<dd><?php printf(__('Set to %1$s to display post excerpts in the list. (Default length: 25 words.) Set to a number greater than 1 to customize the excerpt length (in words).', 'r34otd'), '<code>true</code>'); ?></dd>
						
							<dt>show_post_thumbnail</dt>
							<dd><?php printf(__('Set to %1$s to display post thumbails (featured images) in the list.', 'r34otd'), '<code>true</code>'); ?></dd>
						
							<dt>title</dt>
							<dd><?php printf(__('Text to display as a title above the list. %1$s Default value: %2$s', 'r34otd'), '<br />', '<code>' . __('On This Day', 'r34otd') . '</code>'); ?></dd>
						
							<dt>use_post_date</dt>
							<dd><?php printf(__('Set to %1$s to use the publish date of the current post rather than today. Only applies when used on a single post or page, not on archive pages or the main blog page. Overrides %2$s and %3$s if they are set.', 'r34otd'), '<code>true</code>', '<strong>month</strong>', '<strong>day</strong>'); ?></dd>
						
						</dl>
				
					</div>
				
				</div>

			</div>
	
			<div class="column-2">
			
				<img src="<?php echo plugin_dir_url(__FILE__); ?>/on-this-day-icon.svg" alt="<?php esc_attr_e('On This Day'); ?>" style="display: block; height: auto; margin: 0 auto 1.5em auto; width: 160px;" />

				<p><strong><?php _e('Thank You!', 'r34otd'); ?></strong>
				<?php printf(__('This plugin is free to use. If you find it to be of value, we welcome your %1$sdonation%2$s (suggested amount: US $9), to help fund future development.', 'r34otd'), '<a href="https://room34.com/payments/?type=WordPress%20Plugin&plugin=On+This+Day+(by+Room+34)&amt=9" target="_blank">', '</a>'); ?></p>

				<a href="https://room34.com/about/payments/?type=WordPress+Plugin&plugin=Room+34+Presents+On+This+Day&amt=9" target="_blank"><img src="<?php echo plugin_dir_url(__FILE__); ?>/room34-logo-on-white.svg" alt="Room 34 Creative Services" style="display: block; height: auto; margin: 1.5em auto 0.5em auto; width: 160px;" /></a> 

				<p style="text-align: center;"><small>On This Day (by Room 34) v. <?php echo $plugin_data['Version']; ?></small></p>

			</div>
		
		</div>
		
	</div>
	<?php
}