<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly	?>

<div class="wrap snow-storm">
	<h1><?php _e('Snow Storm', 'snow-storm'); ?></h1>
	<form action="" method="post" id="snow-storm-form">	
		<?php

		wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
		wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false); 
		
		?>
	
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="postbox-container-1" class="postbox-container">
					<?php do_action('submitpage_box'); ?>
					<?php do_meta_boxes('settings_page_snow-storm', 'side', false); ?>
				</div>
				<div id="postbox-container-2" class="postbox-container">
					<?php do_meta_boxes('settings_page_snow-storm', 'high', false); ?>
					<?php do_meta_boxes('settings_page_snow-storm', 'normal', false); ?>
                    <?php do_meta_boxes('settings_page_snow-storm', 'advanced', false); ?>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
(function($) {
	$document = $(document);
	$plugininstall = $('.plugin-install-newsletters');
	
	$plugininstall.on('click', '.install-now', function() {
		tb_show('<?php _e('Install Newsletter Plugin', 'snow-storm'); ?>', $(this).attr('href'), false);
		return false;
	});
	
	$plugininstall.on('click', '.activate-now', function() {
		window.location = $(this).attr('href');
	});
	
	$document.on('wp-plugin-installing', function(event, args) {
		$plugininstall.find('.install-now').html('<i class="fa fa-refresh fa-spin fa-fw"></i> <?php echo __('Installing', 'snow-storm'); ?>').prop('disabled', true);
	});
	
	$document.on('wp-plugin-install-success', function(event, response) {	
		$plugininstall.find('.install-now').html('<i class="fa fa-check fa-fw"></i> <?php _e('Activate', 'snow-storm'); ?>').attr('href', response.activateUrl).prop('disabled', false);
		$plugininstall.find('.install-now').removeClass('install-now').addClass('activate-now')
	});
	
	$document.on('wp-plugin-install-error', function(event, response) {
		alert('<?php _e('An error occurred, please try again.', 'snow-storm'); ?>');
		$plugininstall.find('.install-now').html('<i class="fa fa-check fa-fw"></i> <?php echo __('Install Now', 'snow-storm'); ?>').prop('disabled', false);
	});
})(jQuery);
</script>