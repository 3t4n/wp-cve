<div id="onetRegenThumb" class="wrap">
	<input type="hidden" name="onetrt_nonce" value="<?php echo $nonce; ?>" />
	<input type="hidden" name="onetrt_success" value="<?php echo $onetrt_dashboard_vars['success_uri']; ?>" />
	<h2><?php _e('Regenerate Thumbnails', 'onetrt'); ?> <small><?php echo sprintf(__('by %s', 'onetrt'),"József Koller"); ?></small></h2>
	<?php if (count($update_msg)) : ?>
	<div id="message" class="updated fade below-h2"><p><?php echo implode("</p><p>",$update_msg); ?></p></div>
	<?php endif; ?>

	<div class="left-side metabox-holder">

		<!-- Dashboard -->
			<?php $own->ui_admin_metabox("dashboard"); ?>
		<!-- // Dashboard -->

	</div>
	<div class="right-side metabox-holder">

		<!-- Settings -->
			<?php $own->ui_admin_metabox("settings"); ?>
		<!-- // Settings -->
		<!-- Custom dimension editor -->
			<?php if ($own->opts->advanced) $own->ui_admin_metabox("customdim"); ?>
		<!-- // Custom dimensions editor -->
		<!-- Support block -->
			<?php $own->ui_admin_metabox("support"); ?>
		<!-- // Support block -->

	</div>

	<script type="text/javascript">
		window.onetrt_taskdata = {
			task_id:<?php echo (int)$task_id; ?>,
			item_total: <?php echo (int)$ids_count; ?>,
			update_url: "<?php echo $update_url; ?>"
		};
	</script>

</div>