<?php
// direct access is disabled
defined('ABSPATH') || exit;
?>

<div class="wrap">

	<h1><?php _e('Sticky Social Icons', 'sticky-social-icons'); ?></h1>

	<div id="settings-wrapper">

		<ul id="settings-tabs">
			<li class="<?php if ($current_tab == 'settings') echo 'active'; ?> "><a
					href="<?php echo $tab_url; ?>settings">
					<?php _e('Settings', 'sticky-social-icons'); ?> </a></li>
			<li class="<?php if ($current_tab == 'icons') echo 'active'; ?> "><a href="<?php echo $tab_url; ?>icons">
					<?php _e('Icons', 'sticky-social-icons'); ?> </a></li>
		</ul>

		<form method="POST" action="options.php" id="sanil-ssi-form">

			<?php if ($current_tab == 'settings') : ?>

			<!-- settings tabs -->

			<!-- generate nonce -->
			<?php settings_fields("sticky_social_icons_settings"); ?>

			<div class="section">
				<!-- display form fields -->
				<?php do_settings_sections($this->settings_page_slug . '-settings'); ?>
			</div>

			<?php submit_button(__('Save Changes', 'sticky-social-icons'), 'primary', 'btn-submit'); ?>


			<?php elseif ($current_tab == 'icons') : ?>

			<!-- icons tabs -->

			<!-- generate nonce -->
			<?php settings_fields("sticky_social_icons_icons"); ?>

			<div class="section">
				<!-- display form fields -->
				<?php do_settings_sections($this->settings_page_slug . '-icons'); ?>
			</div>

			<!-- availabel icons -->
			<div class="section cards mb-50">

				<h3 class="section-title"><?php _e('AVAILABLE ICONS', 'sticky-social-icons'); ?> </h3>

				<div class="section-contents">

					<div class="icons-search-wrapper">
						<input type="text" name="icon_search"
							placeholder="<?php _e('Click here to search for more icons ', 'sticky-social-icons'); ?>"
							id="icon-search">
					</div>
					<!--icon-search-wrapper-->

					<p><strong><?php _e('Click on icon to select', 'sticky-social-icons'); ?></strong></p>
					<div id="available-icons-container"></div>
					<!--icons-container-->

				</div>
				<!--section-contents-->

			</div>
			<!--section cards-->


			<!-- selected icons -->
			<div class="section cards " id="selected-icons-section" style="display: none;">

				<h3 class="section-title"><?php _e('SELECTED ICONS', 'sticky-social-icons'); ?> </h3>

				<div class="section-contents">
					<div id="selected-icons-container"></div>
					<!--icons-container-->
				</div>
				<!--section-contents-->

			</div>
			<!--section cards-->


			<?php submit_button(__('Save Changes', 'sticky-social-icons'), 'primary', 'btn-submit'); ?>


			<?php endif; ?>

		</form>

	</div>
	<!--settings-wrapper-->

</div>
<!--wrap-->