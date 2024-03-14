<p>
	<strong><?php _e('Hints', 'onetrt'); ?></strong>:
	<?php echo sprintf(__('You can regenerate thumbnails for particular images if you select them in %s then perform a "Regen. thumbnails" action (from "bulk actions" dropdown menu).',"onetrt"),'<a href="'.$onetrt_dashboard_vars['media_image_list_uri'].'">'.__("Media &gt; Library &gt; Images","onetrt")."</a>" ); ?>
	<?php _e('Also check out the settings and change them to suit your needs.', 'onetrt'); ?>
</p>

<!-- Statics and processing area -->
<div class="datadisplay">
	<ul class="stats">
		<li><strong><?php _e('Total images', 'onetrt'); ?></strong>: <?php echo $onetrt_dashboard_vars['atts_count']; ?></li>
		<li><strong title="<?php _e('The number of currently registered custom thumbnails. If it\'s zero it means that there is no custom thumbnail size only defaults.', 'onetrt'); ?>"><?php _e('Custom dims', 'onetrt'); ?></strong>: <?php echo $onetrt_dashboard_vars['custom_dims']; ?></li>
		<li><strong title="<?php _e('Number of selected images to generate.', 'onetrt'); ?>"><?php _e('Queued images', 'onetrt'); ?></strong>: <?php echo $onetrt_dashboard_vars['ids_count']; ?></li>
	</ul>

	<div class="progress">
		<div id="progressbar">
			<div class="bar"></div>
			<div class="percent">0%</div>
			<div class="estimated"><?php echo sprintf(__('Estimated time ~ %s','onetrt'),'<span>'.__('...','onetrt').'</span>'); ?></div>
			<div class="remaining"><?php _e('Remaining:','onetrt');?> <span><?php echo $onetrt_dashboard_vars['ids_count']; ?></span></div>
		</div>
		<ul id="progresslog"></ul>
	</div>
	<div style="clear:both;"></div>
</div>

<!-- Process buttons -->
<div class="btnset">
	<?php if (!$onetrt_dashboard_vars['is_total_regen']) : ?>
	<input type="button" id="onetrt-start" class="button button-primary button-large" value="<?php _e('Regenerate thumbnails for selected','onetrt'); ?>" />
	<a href="<?php echo $reset_uri; ?>" id="onetrt-reset" class="button"><?php _e('Reset selection','onetrt'); ?></a>
	<?php else : ?>
	<input type="button" id="onetrt-start" class="button<?php echo $onetrt_dashboard_vars['is_total_regen'] ? " button-primary button-large " : ""; ?>" value="<?php _e('Regenerate all thumbnails','onetrt'); ?>" />
	<?php endif; ?>
	<a href="<?php echo $onetrt_dashboard_vars['cancel_uri']; ?>" id="onetrt-cancel" class="button hidden"><?php _e('Cancel','onetrt'); ?></a>
</div>

<div style="clear:both;"></div>