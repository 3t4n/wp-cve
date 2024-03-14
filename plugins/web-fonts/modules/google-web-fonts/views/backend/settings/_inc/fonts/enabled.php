<div id="google-web-fonts-enabled-fonts-list">
	<div id="google-web-fonts-enabled-fonts-list-inner">
		<h3><?php _e('Enabled Fonts'); ?></h3>
		
		<ul data-bind="visible: enabled_fonts().length == 0">
			<li><?php _e('You do not have any fonts enabled.'); ?></li>
		</ul>
		
		<ul data-bind="foreach: enabled_fonts" id="google-web-fonts-enabled-fonts-list-items">
			<li>
				<label data-bind="text: family_name"></label><br />
				<span data-bind="style: { 'font-family': family(), 'font-weight': weight(), 'font-style': style() }">Quick Brown Fox Jumped Over The Lazy Dog</span>
			</li>
		</ul>
		
		<input type="button" class="button button-primary google-web-fonts-close-thickbox" value="<?php _e('Close'); ?>" />
	</div>
</div>