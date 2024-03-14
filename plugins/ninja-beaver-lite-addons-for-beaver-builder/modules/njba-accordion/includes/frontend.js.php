(function($) {
	$(function() {
		new NJBAAccordion({
			id: '<?php echo $id ?>',
			defaultItem: <?php echo (isset($settings->open_first) && $settings->open_first) ? '1' : (absint($settings->open_custom) > 0 ? absint($settings->open_custom) : 'false'); ?>
		});
	});
})(jQuery);
