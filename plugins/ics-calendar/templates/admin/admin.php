<?php
global $R34ICS;
?>

<div class="wrap r34ics">

	<h2><?php echo get_admin_page_title(); ?></h2>
	
	<div class="metabox-holder columns-2">
	
		<div class="column-1">
			
			<div id="basic-shortcode-example">

				<h3><?php _e('Basic Shortcode Example', 'r34ics'); ?></h3>

				<p><?php printf(__('Use the following shortcode format in your page content where you would like your calendar to appear, inserting your ICS feed URL between the quotation marks. If you do not know your ICS feed URL, %1$shere&rsquo;s how to find it%2$s.', 'r34ics'), '<strong><a href="https://icscalendar.com/getting-started/#finding-your-ics-feed-url" target="_blank">', '</a></strong>'); ?></p>

				<p><code>[ics_calendar url=&quot;&quot;]</code></p>
						
				<p><?php printf(__('Many additional customization options are available. Use our online %1$sShortcode Builder%2$s to easily create a customized shortcode, or consult the %3$sUser Guide%4$s for more information.', 'r34ics'), '<strong><a href="https://icscalendar.com/shortcode-builder/" target="_blank" style="white-space: nowrap;">', '</a></strong>', '<strong><a href="https://icscalendar.com/user-guide/" target="_blank" style="white-space: nowrap;">', '</a></strong>'); ?></p>
			</div>
			
			<hr />
	
			<?php include_once(plugin_dir_path(__FILE__) . 'utilities.php'); ?>

			<?php
			if (current_user_can('manage_options')) {
				?>
				<hr />

				<div id="admin-options">

					<h3><?php _e('Administrative Options', 'r34ics'); ?></h3>

					<form id="r34ics-admin-options" method="post" action="">
						<?php
						wp_nonce_field('r34ics','r34ics-admin-options-nonce');
					
						include_once(plugin_dir_path(__FILE__) . 'admin-options.php');
						?>

						<p><input type="submit" class="button button-primary" value="<?php echo esc_attr(__('Save Changes', 'r34ics')); ?>" /></p>
					</form>

				</div>
				<?php
			}
			?>
	
		</div>
	
		<div class="column-2">

			<?php include_once(plugin_dir_path(__FILE__) . 'sidebar.php'); ?>
	
		</div>
	
	</div>

</div>