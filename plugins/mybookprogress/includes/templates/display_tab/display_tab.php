<div class="mbp-alert-message-container">
	<div class="mbp-alert-message"><?php _e('Your Mailing List settings are not configured properly!', 'mybookprogress'); ?><br><div class="mbp-alert-message-desc"><?php _e('Your readers will not be able to subscribe to your updates. Click here to enter your mailing list information.', 'mybookprogress'); ?></div></div>
</div>
<div class="mbp-section mbp-widget-section">
	<div class="mbp-section-header"><?php _e('Widgets', 'mybookprogress'); ?></div>
	<div class="mbp-section-content">
		<div class="mbp-section-description">
			<?php _e('Add a widget to your sidebar to show off your progress!', 'mybookprogress'); ?>
		</div>
		<?php
			global $wp_registered_sidebars;
			$sidebars_widgets = wp_get_sidebars_widgets();
			$has_widget = array();
			foreach($sidebars_widgets as $sidebar_name => $sidebar_widgets) {
				if(empty($sidebar_widgets)) { continue; }
				foreach($sidebar_widgets as $sidebar_widget) {
					if(strpos($sidebar_widget, 'mbp_widget') !== false) {
						$has_widget[] = $sidebar_name;
						break;
					}
				}
			}
			$selected_sidebar = empty($has_widget) ? null : $has_widget[0];
		?>
		<div class="mbp-widget-section-inputs">
			<a class="mbp-widget-button" target="_blank" href="<?php echo(admin_url('widgets.php')); ?>"></a>
			<div class="mbp-widget-sidebar-container">
				<select class="mbp-widget-sidebar">
					<?php foreach($wp_registered_sidebars as $id => $data) { ?>
						<option value="<?php echo($id); ?>" <?php selected($id, $selected_sidebar); ?> data-has-widget="<?php echo(in_array($id, $has_widget) ? 'yes' : 'no'); ?>" ><?php echo($data['name']); ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	</div>
</div>
<div class="mbp-section mbp-shortcode-section">
	<div class="mbp-section-header"><?php _e('Shortcodes', 'mybookprogress'); ?></div>
	<div class="mbp-section-content">
		<div class="mbp-section-description">
			 <?php _e('Want to display your book progress in a blog post? In the classic editor, look for this "Insert Shortcode" button above your post editor:', 'mybookprogress'); ?>
		</div>
		<img src="<?php echo(plugins_url('img/help/shortcode-help.png', MBP_ROOT)); ?>">
		<div class="mbp-section-description"> Otherwise you can form a shortcode like this:<br />
			<strong>&lsqb;mybookprogress book="1" showsubscribe="true" simplesubscribe="true"&rsqb;</strong>
			where 1 is the id number of the book you want to show and you want to show a subscribe button and you want only the simple email form rather than a link to the full mailchimp subscribe form.
		</div>
	</div>
</div>