<h2 id="fonts-com-web-fonts-nav" class="nav-tab-wrapper">
	<a class="nav-tab <?php if('setup' == $current_tab) { ?>nav-tab-active<?php } ?>" href="<?php esc_attr_e(add_query_arg(array('tab' => 'setup'), $base_url)); ?>"><?php _e('Set Up'); ?></a>
	
	<span id="web-fonts-nav-is-setup" class="<?php echo ($is_setup ? '' : 'hide-if-js'); ?>">	
		<a class="nav-tab <?php if('projects' == $current_tab) { ?>nav-tab-active<?php } ?>" href="<?php esc_attr_e(add_query_arg(array('tab' => 'projects'), $base_url)); ?>"><?php _e('Projects'); ?></a>
		<a class="nav-tab <?php if('fonts' == $current_tab) { ?>nav-tab-active<?php } ?>" href="<?php esc_attr_e(add_query_arg(array('tab' => 'fonts'), $base_url)); ?>"><?php _e('Fonts'); ?></a>
	</span>
	
	<span id="web-fonts-nav-is-not-setup" class="<?php echo ($is_setup ? 'hide-if-js' : ''); ?>">
		<span class="nav-tab"><?php _e('Projects'); ?></span>
		<span class="nav-tab"><?php _e('Fonts'); ?></span>
	</span>
</h2>