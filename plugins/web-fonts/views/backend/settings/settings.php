<div class="wrap">
	<?php screen_icon(); ?>

	<h2><?php printf(__('Web Fonts - %s'), call_user_func(array($provider_class_name, 'get_provider_name'))); ?></h2>
	
	<?php call_user_func(array($provider_class_name, 'settings_page')); ?>
</div>