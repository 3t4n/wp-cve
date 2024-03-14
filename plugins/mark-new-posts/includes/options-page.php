<script type="text/javascript">
	var options = {
		messages: {
			postStaysNewDays: '<?php _e('Please specify a correct amount of days', self::TEXT_DOMAIN); ?>'
		}
	};
	MarkNewPostsAdminForm(jQuery, options);
</script>
<div class="mnp-options">
	<div class="mnp-title">
		<?php echo self::PLUGIN_NAME ?>
	</div>
	<div class="mnp-row mnp-divider">
		<div class="mnp-col">
			<?php _e('Marker placement', self::TEXT_DOMAIN); ?>
		</div>
		<div class="mnp-col">
			<select id="mnp-marker-placement" class="mnp-input">
				<?php
					$option = $this->options->marker_placement;
					$this->echo_option($option, MarkNewPosts_MarkerPlacement::TITLE_BEFORE, __('Before post title', self::TEXT_DOMAIN));
					$this->echo_option($option, MarkNewPosts_MarkerPlacement::TITLE_AFTER, __('After post title', self::TEXT_DOMAIN));
					$this->echo_option($option, MarkNewPosts_MarkerPlacement::TITLE_BOTH, __('Before and after post title', self::TEXT_DOMAIN));
				?>
			</select>
		</div>
	</div>
	<div class="mnp-row">
		<div class="mnp-col">
			<?php _e('Marker type', self::TEXT_DOMAIN) ?>
		</div>
		<div class="mnp-col">
			<select id="mnp-marker-type" class="mnp-input">
				<?php
					$option = $this->options->marker_type;
					$this->echo_option($option, MarkNewPosts_MarkerType::TEXT_NEW, __('"New" text', self::TEXT_DOMAIN));
					$this->echo_option($option, MarkNewPosts_MarkerType::TEXT, __('"New" text', self::TEXT_DOMAIN) . ' (legacy)');
					$this->echo_option($option, MarkNewPosts_MarkerType::CIRCLE, __('Orange circle', self::TEXT_DOMAIN));
					$this->echo_option($option, MarkNewPosts_MarkerType::FLAG, __('Flag', self::TEXT_DOMAIN));
					$this->echo_option($option, MarkNewPosts_MarkerType::IMAGE_DEFAULT, __('Picture', self::TEXT_DOMAIN));
					$this->echo_option($option, MarkNewPosts_MarkerType::NONE, __('None', self::TEXT_DOMAIN));
				?>
			</select>
		</div>
	</div>
	<div class="mnp-row">
		<div class="mnp-col">
			<input type="checkbox" id="mnp-mark-title-bg" autocomplete="off"
				<?php if ($this->options->mark_title_bg) { echo 'checked="checked"'; } ?>>
			<label for="mnp-mark-title-bg">
				<?php _e('Post title BG colour', self::TEXT_DOMAIN) ?>
			</label>
		</div>
		<div class="mnp-col">
			<input type="text" id="mnp-mark-bg-color" class="mnp-input" value="<?php echo $this->options->mark_bg_color; ?>">
		</div>
	</div>
	<div class="mnp-row mnp-divider">
		<div><?php _e('Consider a post as read:', self::TEXT_DOMAIN); ?></div>
		<div class="mnp-radio">
			<?php
				$this->echo_mark_after_option(MarkNewPosts_MarkAfter::OPENING_POST, __('after it was opened', self::TEXT_DOMAIN));
				$this->echo_mark_after_option(MarkNewPosts_MarkAfter::OPENING_LIST, __('after it was displayed in the list', self::TEXT_DOMAIN));
				$this->echo_mark_after_option(MarkNewPosts_MarkAfter::OPENING_BLOG, __('after any page of the blog was opened', self::TEXT_DOMAIN));
			?>
		</div>
	</div>
	<div class="mnp-row mnp-divider">
		<input type="checkbox" id="mnp-post-stays-new" autocomplete="off"
			<?php if ($this->options->post_stays_new_days) { echo 'checked="checked"'; } ?>>
		<label for="mnp-post-stays-new"><?php
			$template = __('A post only stays highlighted for %s days after publishing', self::TEXT_DOMAIN);
			$input = '<input type="text" id="mnp-post-stays-new-days" autocomplete="off" value="'
				. ($this->options->post_stays_new_days ? $this->options->post_stays_new_days : '') . '" />';
			echo sprintf($template, $input);
		?></label>
	</div>
	<div class="mnp-row">
		<input type="checkbox" id="mnp-all-new-for-new-visitor" autocomplete="off"
			<?php if ($this->options->all_new_for_new_visitor) { echo 'checked="checked"'; } ?>>
		<label for="mnp-all-new-for-new-visitor"><?php _e('Show all existing posts as new to new visitors', self::TEXT_DOMAIN); ?></label>
	</div>
	<div class="mnp-row">
		<input type="checkbox" id="mnp-disable-for-custom-posts" autocomplete="off"
			<?php if ($this->options->disable_for_custom_posts) { echo 'checked="checked"'; } ?>>
		<label for="mnp-disable-for-custom-posts"><?php _e('Disable for custom posts', self::TEXT_DOMAIN); ?></label>
	</div>
	<div class="mnp-row mnp-divider">
		<input type="checkbox" id="mnp-allow-outside-the-loop" autocomplete="off"
			<?php if ($this->options->allow_outside_the_loop) { echo 'checked="checked"'; } ?>>
		<label for="mnp-allow-outside-the-loop"><?php _e('Allow outside the post list', self::TEXT_DOMAIN); ?></label>
    <div class="mnp-note"><?php _e('Experimental. Allows highlighting posts outside of "the loop", f.i., in widgets. Enabling this may cause side effects.', self::TEXT_DOMAIN); ?></div>
	</div>
	<div class="mnp-row mnp-divider">
		<input type="checkbox" id="mnp-use-js" autocomplete="off"
			<?php if ($this->options->use_js) { echo 'checked="checked"'; } ?>>
		<label for="mnp-use-js"><?php _e('Use JavaScript for showing markers', self::TEXT_DOMAIN); ?></label>
		<div class="mnp-note"><?php _e('Enable this option only if the plugin is exploding your blog\'s markup.', self::TEXT_DOMAIN); ?></div>
	</div>
	<div class="mnp-row mnp-divider" style="color: #a80">
		Hey! If you like this plugin, why not give it a nice review on wordpress.org?
	</div>
	<div class="mnp-buttons-set mnp-divider">
		<button id="mnp-reset-options-btn" class="mnp-button mnp-button-save">
			<?php _e('Reset', self::TEXT_DOMAIN); ?>
		</button>
		<button id="mnp-save-options-btn" class="mnp-button mnp-button-reset">
			<?php _e('Save', self::TEXT_DOMAIN); ?>
		</button>
	</div>
	<div class="mnp-clearfix"></div>
	<div id="mnp-message" class="mnp-message"></div>
	<div class="mnp-clearfix"></div>
</div>