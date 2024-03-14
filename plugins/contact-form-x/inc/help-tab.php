<?php // Contact Form X Help Tab

if (!defined('ABSPATH')) exit;

function contactformx_get_help_sidebar() {
	
	return '<p><strong>'. esc_html__('Contact Form X', 'contact-form-x') .'</strong></p>'.
	
	'<p>'. 
		esc_html__('Visit the', 'contact-form-x') .' <a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/contact-form-x/installation/">'. 
		esc_html__('CFX Docs', 'contact-form-x') .'</a> '. esc_html__('at WordPress.org.', 'contact-form-x') . 
	'</p>'.
	
	'<p><strong>'. esc_html__('Show Support!', 'contact-form-x') .'</strong></p>'.
	
	'<ul>'.
		'<li><a target="_blank" rel="noopener noreferrer" href="https://monzillamedia.com/donate.html">'. esc_html__('Make a Donation&nbsp;&raquo;', 'contact-form-x') .'</a></li>'.
		'<li><a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/plugin/'. CONTACTFORMX_SLUG .'/reviews/?rate=5#new-post">'. esc_html__('Give 5&#10025; Rating&nbsp;&raquo;', 'contact-form-x') .'</a></li>'. 
	'</ul>'. 
	
	'<p><strong>'. esc_html__('Follow &amp; Share', 'contact-form-x') .'</strong></p>'.
	
	'<div class="share-buttons">
		<div class="share-button">
			<a target="_blank" rel="noopener noreferrer" href="https://twitter.com/perishable">Follow on Twitter &raquo;</a>
		</div>
		<div class="share-button">
			<a target="_blank" rel="noopener noreferrer" href="https://twitter.com/intent/tweet?text='. rawurlencode('Contact Form X: blazing fast Ajax-powered contact form by Jeff Starr.') .'&url=https://wordpress.org/plugins/contact-form-x/&hashtags=WordPress,plugin">Share on Twitter &raquo;</a>
		</div>
		<div class="share-button">
			<a target="_blank" rel="noopener noreferrer" href="https://www.facebook.com/sharer/sharer.php?quote='. rawurlencode('Contact Form X: blazing fast Ajax-powered contact form by Jeff Starr.') .'&u='. rawurlencode('https://wordpress.org/plugins/contact-form-x/') .'">Share on Facebook &raquo;</a>
		</div>
	</div>';
	
}

function contactformx_settings_contextual_help() {
	
	$screen = get_current_screen();
	
	if ($screen->id !== 'settings_page_contactformx') return;
	
	$screen->set_help_sidebar(contactformx_get_help_sidebar());
	
	$screen->add_help_tab(
		
		array(
			
			'id' => 'cfx-intro',
			'title' => esc_attr__('Introduction', 'contact-form-x'),
			'content' => 
				'<p><strong>'. esc_html__('Contact Form X', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. esc_html__('CFX delivers a complete contact form experience that your visitors will love.', 'contact-form-x') .'</p>'. 
				
				'<p><strong>'. esc_html__('Quick Tour', 'contact-form-x') .'</strong></p>'.
				
				'<p>'. esc_html__('Contact Form X organizes its settings into different tabs: ', 'contact-form-x') .'</p>'. 
				
				'<ul>'.
					'<li><a href="'. esc_url(admin_url('options-general.php?page=contactformx&tab=tab1')) .'">'. esc_html__('Email',      'contact-form-x') .'</a> &ndash; '. esc_html__('Add email recipients',    'contact-form-x') .'</li>'.
					'<li><a href="'. esc_url(admin_url('options-general.php?page=contactformx&tab=tab2')) .'">'. esc_html__('Form',       'contact-form-x') .'</a> &ndash; '. esc_html__('Choose your form fields', 'contact-form-x') .'</li>'.
					'<li><a href="'. esc_url(admin_url('options-general.php?page=contactformx&tab=tab3')) .'">'. esc_html__('Customize',  'contact-form-x') .'</a> &ndash; '. esc_html__('Customize your form',     'contact-form-x') .'</li>'.
					'<li><a href="'. esc_url(admin_url('options-general.php?page=contactformx&tab=tab4')) .'">'. esc_html__('Appearance', 'contact-form-x') .'</a> &ndash; '. esc_html__('Change form appearance',  'contact-form-x') .'</li>'.
					'<li><a href="'. esc_url(admin_url('options-general.php?page=contactformx&tab=tab5')) .'">'. esc_html__('Advanced',   'contact-form-x') .'</a> &ndash; '. esc_html__('Advanced plugin options', 'contact-form-x') .'</li>'.
				'</ul>'.
				
				'<p><strong>'. esc_html__('Useful Resources', 'contact-form-x') .'</strong></p>'.
				
				'<ul>'.
					'<li><a target="_blank" rel="noopener noreferrer" href="'. CONTACTFORMX_URL .'readme.txt">'.              esc_html__('View the plugin readme.txt file', 'contact-form-x') .'</a></li>'.
					'<li><a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/contact-form-x/">'. esc_html__('Visit the plugin documentation',  'contact-form-x') .'</a></li>'.
					'<li><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/support/#contact">'.    esc_html__('Contact the plugin developer',    'contact-form-x') .'</a></li>'.
				'</ul>'.
				
				'<p><strong>'. esc_html__('About the Developer', 'contact-form-x') .'</strong></p>'.
				
				'<p>'. 
					esc_html__('Contact Form X is developed by', 'contact-form-x') .' <a target="_blank" rel="noopener noreferrer" href="https://twitter.com/perishable">'. esc_html__('Jeff Starr', 'contact-form-x') .'</a>, '.
					esc_html__('15-year', 'contact-form-x') .' <a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/">'. esc_html__('WordPress developer', 'contact-form-x') .'</a> '.
					esc_html__('and', 'contact-form-x') .' <a target="_blank" rel="noopener noreferrer" href="https://books.perishablepress.com/">'. esc_html__('book author', 'contact-form-x') .'</a>.'.
				'</p>'
			
		)
	);
	
	$screen->add_help_tab(
		
		array(
			
			'id' => 'cfx-usage',
			'title' => esc_attr__('Usage', 'contact-form-x'),
			'content' => 
				'<p><strong>'. esc_html__('Usage', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. esc_html__('Three ways to display the contact form:', 'contact-form-x') .'</p>'. 
				
				'<ul>'. 
					'<li>'. esc_html__('Widget: visit Appearance &gt; Widgets', 'contact-form-x') .'</li>'. 
					'<li>'. esc_html__('Shortcode:', 'contact-form-x') .' <code>[contactformx]</code></li>'. 
					'<li>'. esc_html__('Template Tag:', 'contact-form-x') .' <code>&lt;?php echo contactformx(); ?&gt;</code></li>'. 
				'</ul>'.
				
				'<p>'. 
					esc_html__('The shortcode enables display on posts and pages. The widget enables display in sidebars and footers. And the template tag enables display via your theme template. ', 'contact-form-x') . 
					esc_html__('So basically you can display the contact form anywhere. Or in multiple locations, it&rsquo;s all good.', 'contact-form-x') .
				'</p>'.
				
				'<p>'.
					'<strong>'. esc_html__('Pro Tip:', 'contact-form-x') .'</strong> '.
					esc_html__('If using the template tag to display the form, you can bullet-proof the call by checking if the function exists, like this:', 'contact-form-x') .
				'</p>'.
				
				'<p><code>&lt;?php if (function_exists(\'contactformx\')) echo contactformx(); ?&gt;</code></p>'. 
				
				'<p>'. esc_html__('Writing it that way prevents errors if the plugin is disabled or unavailable.', 'contact-form-x') .'</p>'. 
				
				'<p>'. esc_html__('For more information about shortcodes or widgets, visit the WordPress documentation.', 'contact-form-x') .'</p>'
			
		)
	);
	
	$screen->add_help_tab(
		
		array(
			
			'id' => 'cfx-email',
			'title' => esc_attr__('Email Settings', 'contact-form-x'),
			'content' => 
				'<p><strong>'. esc_html__('Number of Recipients', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. esc_html__('This setting enables you to add or remove recipients. How it works? First, enter the number of recipients that you want. Then click Save Changes.', 'contact-form-x') .'</p>'. 
				
				'<p><strong>'. esc_html__('Email Recipients', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. 
					esc_html__('Each recipient must have a Name, To address, and From address. See note below about the From address. ', 'contact-form-x') . 
					esc_html__('Tip: make sure to whitelist/allow the To/From email address in your email client, apps, and services. This helps to ensure that you always will receive your email messages.', 'contact-form-x') .
				'</p>'.
				
				'<p><strong>'. esc_html__('About the &ldquo;Email Address From&rdquo; setting', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. 
					esc_html__('This setting enables you to customize the address that is used as the &ldquo;From&rdquo; header for email messages. ',  'contact-form-x') . 
					esc_html__('By default, CFX uses the same address that is used by WordPress to send alert messages. ',                              'contact-form-x') . 
					esc_html__('So if WordPress is installed on yourdomain.com, the default From value is something like "wordpress@yourdomain.com". ', 'contact-form-x') . 
					esc_html__('That address is proven to work great for most setups. ',                                                                'contact-form-x') . 
					esc_html__('But the option is there if you know what you are doing and want to change it to something else. ',                      'contact-form-x') . 
					esc_html__('If in doubt for this setting, just use the default value, &ldquo;wordpress@yourdomain.com&rdquo; ',                     'contact-form-x') . 
					esc_html__('(replace &ldquo;yourdomain.com&rdquo; with your actual domain name).',                                                  'contact-form-x') . 
				'</p>'
			
		)
	);
	
	$screen->add_help_tab(
		
		array(
			
			'id' => 'cfx-form',
			'title' => esc_attr__('Form Settings', 'contact-form-x'),
			'content' => 
				'<p><strong>'. esc_html__('Form Fields', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. esc_html__('Here you may choose which fields to display in the form. Each field may be set to Required, Optional, or Disabled.', 'contact-form-x') .'</p>'.
				
				'<p>'. esc_html__('Important: when displaying the Challenge Question, reCaptcha, and/or Agree fields, it is recommended to set them as &ldquo;Required&rdquo;.', 'contact-form-x') .'</p>'.
				
				'<p>'. esc_html__('Tip: you can drag/drop to change the order in which fields are displayed on the form.', 'contact-form-x') .'</p>'.
				
				'<p>'. esc_html__('Tip: you can customize the labels for each of these fields under the Customize tab.', 'contact-form-x') .'</p>'.
				
				'<p>'. esc_html__('Note: if you choose to display the Google reCaptcha field, you will need to enter your public and private keys via the Customize tab &gt; &ldquo;Google reCaptcha&rdquo;.', 'contact-form-x') .'</p>'.
				
				'<p>'. esc_html__('Note: to include all extra form fields and user data with email messages, enable the option &ldquo;Extra Email Info&rdquo; under the Advanced tab.', 'contact-form-x') .'</p>'
				
		)
	);
	
	$screen->add_help_tab(
		
		array(
			
			'id' => 'cfx-customize',
			'title' => esc_attr__('Customize', 'contact-form-x'),
			'content' => 
				'<p><strong>'. esc_html__('Customize Settings', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'.
					esc_html__('The settings provided here enable you to change the text that is displayed for field labels, placeholders, error messages and more. ', 'contact-form-x') .
					esc_html__('Additional settings provided for Google reCaptcha keys, reCaptcha theme, Agree to Terms text, and Challenge question/answer. ', 'contact-form-x') . 
				'</p>'.
				
				'<p>'. 
					esc_html__('Basically all of the Customize settings should be self-explanatory. Please', 'contact-form-x') .
					' <a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/support/#contact">'. esc_html__('let me know', 'contact-form-x') .'</a> '. 
					esc_html__('if anything needs further explanation, or if I may provide any help with the plugin.', 'contact-form-x') . 
				'</p>'
			
		)
	);
	
	$screen->add_help_tab(
		
		array(
			
			'id' => 'cfx-appearance',
			'title' => esc_attr__('Appearance', 'contact-form-x'),
			'content' => 
				'<p><strong>'. esc_html__('Appearance Settings', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. esc_html__('Here you can change the appearance of the contact form. Choose from any of these options:', 'contact-form-x') .'</p>'.
				
				'<ul>'. 
					'<li>
						<strong>'. esc_html__('Default:', 'contact-form-x') .'</strong> '. esc_html__('Clean fresh look fits with your theme', 'contact-form-x') .
						' (<a target="_blank" rel="noopener noreferrer" href="'. CONTACTFORMX_URL .'img/example-default.jpg">'. esc_html__('view example', 'contact-form-x') .'</a>)'.  
					'</li>'. 
					'<li>
						<strong>'. esc_html__('Classic:', 'contact-form-x') .'</strong> '. esc_html__('Clean and fresh, requires less vertical space', 'contact-form-x') .
						' (<a target="_blank" rel="noopener noreferrer" href="'. CONTACTFORMX_URL .'img/example-classic.jpg">'. esc_html__('view example', 'contact-form-x') .'</a>)'.  
					'</li>'. 
					'<li>
						<strong>'. esc_html__('Micro:', 'contact-form-x') .'</strong> '. esc_html__('Clean and simple, fits well in small areas', 'contact-form-x') .
						' (<a target="_blank" rel="noopener noreferrer" href="'. CONTACTFORMX_URL .'img/example-micro.jpg">'. esc_html__('view example', 'contact-form-x') .'</a>)'.  
					'</li>'. 
					'<li>
						<strong>'. esc_html__('Synthetic:', 'contact-form-x') .'</strong> '. esc_html__('Complete form styles for fussy WP themes', 'contact-form-x') .
						' (<a target="_blank" rel="noopener noreferrer" href="'. CONTACTFORMX_URL .'img/example-synthetic.jpg">'. esc_html__('view example', 'contact-form-x') .'</a>)'.  
					'</li>'. 
					'<li>
						<strong>'. esc_html__('Dark:', 'contact-form-x') .'</strong> '. esc_html__('Complete form styles for dark WP themes', 'contact-form-x') .
						' (<a target="_blank" rel="noopener noreferrer" href="'. CONTACTFORMX_URL .'img/example-dark.jpg">'. esc_html__('view example', 'contact-form-x') .'</a>)'.  
					'</li>'. 
					'<li>
						<strong>'. esc_html__('None:', 'contact-form-x') .'</strong> '. esc_html__('Disable form style', 'contact-form-x') .
					'</li>'. 
				'</ul>'.

				'<p>'. 
					esc_html__('Note: to use a style, you only need to select it from the menu. No need to modify the CSS code unless you want to customize things :)', 'contact-form-x') .
				'</p>'
			
		)
	);
	
	$screen->add_help_tab(
		
		array(
			
			'id' => 'cfx-advanced',
			'title' => esc_attr__('Advanced', 'contact-form-x'),
			'content' => 
				'<p><strong>'. esc_html__('Success Display', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. esc_html__('This setting enables you to choose what should be displayed after successful form submission. The success message always will be displayed, and here you can choose to also display the form or a summary of the sent message, with or without the &ldquo;Reset Form&rdquo; button, etc.', 'contact-form-x') .'</p>'.
				
				'<p><strong>'. esc_html__('Targeted Loading', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. esc_html__('By default, CFX assets (CSS and JavaScript files) are loaded on every page. Here you may restrict asset loading to specific URL(s). Separate multiple URLs with a comma. Note: leave blank to load CFX assets on all pages. Note: the URLs must be complete/full URLs, for example:', 'contact-form-x') .'</p>'.
				
				'<p><code>https://example.com/path/whatever/</code></p>'.
				
				'<p><strong>'. esc_html__('Widget Shortcodes', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. esc_html__('By default, WordPress does not display shortcode content inside of widgets. So if you try to add the CFX shortcode inside of a widget, the contact form will *not* be displayed. So enable this option to enable widgets to display content from *any* shortcode (even those from other plugins).', 'contact-form-x') .'</p>'.
				
				'<p><strong>'. esc_html__('Mail Function', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. esc_html__('Here you can choose to use PHP mail() function instead of wp_mail(). This can be useful for troubleshooting purposes.', 'contact-form-x') .'</p>'.
				
				'<p><strong>'. esc_html__('Extra Email Info', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. esc_html__('When enabled, this setting tells the plugin to append some extra information to each email. This includes all form data plus extra meta information about the sender (e.g., IP address, referrer, user agent, and so forth.', 'contact-form-x') .'</p>'.
				
				'<p><strong>'. esc_html__('Data Collection', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. esc_html__('When this setting is enabled, the plugin will collect and use identifiable user data, including IP address, host, user agent, and referrer.', 'contact-form-x') .'</p>'.
				
				'<p><strong>'. esc_html__('Disable Database Storage', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. esc_html__('By default, all submitted emails are stored in the WP database as a custom post type (cfx_email). When this option is enabled, no email-related data will be stored in the database.', 'contact-form-x') .'</p>'.
				
				'<p><strong>'. esc_html__('Dashboard Widget', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. esc_html__('Here you can disable the CFX dashboard widget. Leave unchecked to display the widget to Admin-level users. Or check the box to disable for all users.', 'contact-form-x') .'</p>'.
				
				'<p><strong>'. esc_html__('Dashboard Widget Access', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. esc_html__('When enabled, the dashboard widget is displayed to Admin users only. To allow Editor-level users to also view the dashboard widget, enable this option.', 'contact-form-x') .'</p>'.
				
				'<p><strong>'. esc_html__('Show Support', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. esc_html__('Enable this option to display a small line about the plugin. Doing this helps to get the word out about the plugin, so development can continue strong into the future.', 'contact-form-x') .'</p>'.
				
				'<p><strong>'. esc_html__('Reset Widget', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. esc_html__('Here you can remove all email data from the database, so the dashboard widget will have no messages to display.', 'contact-form-x') .'</p>'.
				
				'<p><strong>'. esc_html__('Reset Options', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. esc_html__('Here you can restore all plugin settings to their factory defaults. An alternate way of doing this is to delete and then reinstall the plugin via the WP Plugins screen.', 'contact-form-x') .'</p>'
			
		)
	);
	
	$screen->add_help_tab(
		
		array(
			
			'id' => 'cfx-troubleshooting',
			'title' => esc_attr__('Troubleshooting', 'contact-form-x'),
			'content' => 
				'<p><strong>'. esc_html__('Troubleshooting', 'contact-form-x') .'</strong></p>'. 
				
				'<p>'. esc_html__('Emails not received? Need help troubleshooting?', 'contact-form-x') .'</p>'.
				'<p>'. esc_html__('Check out this extensive guide at Perishable Press:', 'contact-form-x') .'</p>'.
				'<p><a target="_blank" rel="noopener noreferrer" href="https://perishablepress.com/email-troubleshooting-guide/">'. esc_html__('Email Troubleshooting Guide', 'contact-form-x') .'&nbsp;&raquo;</a></p>'
			
		)
	);
	
}
add_action('load-settings_page_contactformx', 'contactformx_settings_contextual_help');
