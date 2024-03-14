<div>
<h3><?php __('Help', 'flexible-frontend-login'); ?></h3>
<h4><?php __('Templating and styling', 'flexible-frontend-login'); ?></h4>
<ol>
	<li><?php __('The following steps are completely optional. Only use them if you want to change the structure or styling of the plugin`s appearance.', 'flexible-frontend-login'); ?></li>
	<li><?php __('Copy the folder', 'flexible-frontend-login'); ?> <code>flexible-frontend-login</code> <?php __('from here', 'flexible-frontend-login'); ?>: 
	<code>/wp-content/plugins/flexible-frontend-login/customization</code> <?php __('into your theme`s folder', 'flexible-frontend-login'); ?>: <code>/wp-content/themes/[yourtheme]/flexible-frontend-login</code></li>
	<li><?php __('The ffl.tpl file contains the html markup. It uses spaceholders for or form elements. Make sure you do not delete the form element spaceholders.', 'flexible-frontend-login'); ?><br>
	<?php __('Here is a complete list:', 'flexible-frontend-login') ?>
		<ul style="padding-left:2em;">
			<li>{$label_for_username}</li>
			<li>{$input_username}</li>
			<li>{$label_for_password}</li>
			<li>{$input_password}</li>
			<li>{$send_button}</li>
			<li>{$lost_password}</li>
		</ul>	
	</li>
</ol>
</div>
