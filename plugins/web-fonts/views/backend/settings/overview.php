<div class="wrap">
	<?php screen_icon(); ?>

	<h2><?php _e('Web Fonts - Manage Stylesheet'); ?></h2>
	
	<p><?php _e('Manage your stylesheet from this page. If you do not see the fonts you believe you should from one of the available font providers, click the provider name to configure the available fonts.'); ?></p>
	
	<ul class="web-fonts-font-providers-list">
		<?php foreach(self::$registered_providers as $provider_class_name) { ?>
		<li><a href="<?php esc_attr_e(add_query_arg(array('page' => self::get_page_slug_for_provider($provider_class_name)), admin_url('admin.php'))); ?>"><?php esc_html_e(call_user_func(array($provider_class_name, 'get_provider_name'))); ?></a></li>
		<?php } ?>
	</ul>
	
	<?php include('_inc/selectors.php'); ?>
</div>