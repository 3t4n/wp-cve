<?php // Banhammer - Contextual Help

if (!defined('ABSPATH')) exit;

function banhammer_get_help_sidebar() {
	
	return '<p><strong>'. esc_html__('More Information', 'banhammer') .'</strong></p>'.
		
		'<p>'. 
			esc_html__('Visit the', 'banhammer') .' <a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/banhammer/installation/">'. esc_html__('Banhammer Docs', 'banhammer') .'</a> '. esc_html__('at WordPress.org.', 'banhammer') .
		'</p>'.
		
		'<p><strong>'. esc_html__('Support Banhammer!', 'banhammer') .'</strong></p>'.
		
		'<ul>'.
		'<li><a target="_blank" rel="noopener noreferrer" href="https://monzillamedia.com/donate.html">'. esc_html__('Donate&nbsp;&raquo;', 'banhammer') .'</a></li>'.
		'<li><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/banhammer-pro/">'. esc_html__('Get Banhammer Pro&nbsp;&raquo;', 'banhammer') .'</a></li>'.
		'<li><a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/plugin/'. BANHAMMER_SLUG .'/reviews/?rate=5#new-post">'. esc_html__('Give 5&#10025; Rating&nbsp;&raquo;', 'banhammer') .'</a></li>'.
		'</ul>'.
		
		'<p><strong>'. esc_html__('Follow &amp; Share', 'banhammer') .'</strong></p>'.
		
		'<ul>
			<li><a target="_blank" rel="noopener noreferrer" href="https://twitter.com/perishable">Follow on Twitter &raquo;</a></li>
			<li><a target="_blank" rel="noopener noreferrer" href="https://twitter.com/intent/tweet?text='. rawurlencode('Banhammer! Deluxe traffic control by Jeff Starr.') .'&url=https://wordpress.org/plugins/banhammer/&hashtags=WordPress,security,plugin">Share on Twitter &raquo;</a></li>
			<li><a target="_blank" rel="noopener noreferrer" href="https://www.facebook.com/sharer/sharer.php?quote='. rawurlencode('Banhammer! Deluxe traffic control by Jeff Starr. #WordPress #security #plugin') .'&u='. rawurlencode('https://wordpress.org/plugins/banhammer/') .'">Share on Facebook &raquo;</a></li>
		</ul>';
	
}



function banhammer_get_testing_notes() {
	
	return array(
		
		'id' => 'banhammer-testing',
		'title' => esc_attr__('Testing', 'banhammer'),
		'content' => '<p><strong>'. esc_html__('Testing Banhammer', 'banhammer') .'</strong></p>'.
			
			'<p>'. 
				esc_html__('How do you know if the plugin is working? Like if you want to customize the banned response? ', 'banhammer') . 
				esc_html__('Well, there are several ways to go about it.', 'banhammer') .  
			'</p>'.
			
			'<p><strong>'. esc_html__('Method One (easiest)', 'banhammer') .'</strong></p>'.
			
			'<p>'. esc_html__('Configure the following Basic Settings:', 'banhammer') .'</p>'.
			
			'<ul>'.
				'<li>'. esc_html__('Enable Plugin - enable',  'banhammer') .'</li>'.
				'<li>'. esc_html__('Ignore Users  - disable', 'banhammer') .'</li>'.
				'<li>'. esc_html__('Login Page    - disable', 'banhammer') .'</li>'.
				'<li>'. esc_html__('Admin Area    - disable', 'banhammer') .'</li>'.
			'</ul>'.
			
			'<p>'. 
				esc_html__('After saving the changes, you will be able to ban your own visits to the front-end (non-admin) pages on your site, without actually banning yourself from the Admin Area or Login Page. ', 'banhammer') . 
				esc_html__('Just remember to restore access via the Tower when you are finished testing.', 'banhammer') .  
			'</p>'.
			
			'<p><strong>'. esc_html__('Method Two (moderate)', 'banhammer') .'</strong></p>'.
			
			'<p>'. 
				esc_html__('Create a new WordPress user and log in using a second browser. Then as you surf around the site, you can monitor and ban the user via the first browser.', 'banhammer') . 
			'</p>'.
			
			'<p><strong>'. esc_html__('Method Three (advanced)', 'banhammer') .'</strong></p>'.
			
			'<p>'. 
				esc_html__('Open two browser tabs. Tab 1 is the Armory. Tab 2 is a good proxy service. With Banhammer enabled, visit your site&rsquo;s homepage via proxy. ', 'banhammer') . 
				esc_html__('Then jump over to the Armory and ban the proxy IP address. Then retry the proxy visit to the homepage; it should be denied access. ', 'banhammer') . 
				esc_html__('Remember to restore access or delete the banned IP via the Tower when finished testing.', 'banhammer') . 
			'</p>'
		
	);
	
}



function banhammer_get_important_notes() {
	
	return array(
		
		'id' => 'banhammer-important',
		'title' => esc_attr__('Important', 'banhammer'),
		'content' => '<p><strong>'. esc_html__('With great power..', 'banhammer') .'</strong></p>'.
			
			'<p>'. 
				esc_html__('Please be careful not to ban any important IP addresses. Before banning some target, verify the IP and host name. ',    'banhammer') . 
				esc_html__('Verifying the IP address is important because you do not want to accidentally ban major search engines and services. ', 'banhammer') . 
				esc_html__('A good way to verify any IP address is to do a reverse lookup. The result should match the host name. ',                'banhammer') . 
				esc_html__('For an example of how to verify a bot, check out', 'banhammer') . 
				' <a target="_blank" rel="noopener noreferrer" href="https://perishablepress.com/spoofed-search-engine-bot/">'. esc_html__('this article', 'banhammer') .'</a> '.
				esc_html__('at Perishable Press.', 'banhammer') . 
			'</p>'.
			
			'<p><strong>'. esc_html__('Pro Tip:', 'banhammer') .'</strong> '. esc_html__('In the Armory, you can click on the IP Address or Host Name to do a quick whois lookup.', 'banhammer') .'</p>'.
			
			'<p><strong>'. esc_html__('Don&rsquo;t ban yourself!', 'banhammer') .'</strong></p>'.
			
			'<p>'. 
				esc_html__('Please be careful not to ban yourself when using Banhammer. ', 'banhammer') . 
				esc_html__('The Basic Settings are powerful; use them wisely. Here are some things that can help mitigate any accidents:', 'banhammer') . 
			'</p>'.
			
			'<ul>'.
				'<li>'. esc_html__('Be mindful when monitoring traffic; always know your own IP address and WP username.', 'banhammer') .'</li>'.
				'<li>'. esc_html__('Disable the setting "Login Page", so you always have access to the Login Page.',       'banhammer') .'</li>'.
				'<li>'. esc_html__('Enable the setting "Ignore Users", so you always can access the Tower, and your own visits will not be logged in the Armory.', 'banhammer') .'</li>'.
			'</ul>'.
			
			'<p><strong>'. esc_html__('Whoops! How do I get back in?', 'banhammer') .'</strong></p>'.
			
			'<p>'. 
				esc_html__('Worst-case scenario say you accidentally ban yourself. As site admin, it is easy to restore access. Follow these steps:', 'banhammer') . 
			'</p>'.
			
			'<ol>'.
				'<li><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/wp/addons/banhammer-unlock.zip">'. esc_html__('Download the Banhammer Unlock plugin', 'banhammer') .'</a></li>'.
				'<li>'. esc_html__('Upload the Unlock plugin to your server at:', 'banhammer') .' <code>/wp-content/mu-plugins/</code></li>'.
				'<li>'. esc_html__('If the mu-plugins directory does not exist, go ahead and create it', 'banhammer') .'</li>'.
				'<li>'. esc_html__('After uploading the plugin, Banhammer will be disabled, so you can log in and restore access via the Tower', 'banhammer') .'</li>'.
				'<li>'. esc_html__('Once you have restored access, delete the Banhammer Unlock plugin from the server', 'banhammer') .'</li>'.
				'<li>'. esc_html__('After deleting the Unlock plugin, Banhammer once again will be enabled', 'banhammer') .'</li>'.
			'</ol>'.
			
			'<p>'. 
				esc_html__('Alternately, if you banned yourself by IP address, you can bypass the ban by using a trustworthy proxy service to log in to your site.', 'banhammer') . 
			'</p>'
		
	);
	
}



function banhammer_get_pro_notes() {
	
	return array(
		
		'id' => 'banhammer-get-pro',
		'title' => esc_attr__('Pro Version', 'banhammer'),
		'content' => '<p><strong>'. esc_html__('Experience the next level: Banhammer Pro!', 'banhammer') .'</strong></p>'.
			
			'<p>'. 
				esc_html__('Banhammer Pro brings awesome new features like whitelisting, bot detection, and editable targets. Plus, Banhammer Pro is lightweight, fast, and easy on resources. ', 'banhammer') . 
				esc_html__('The Pro version gives you some awesome new features:', 'banhammer') . 
			'</p>'.
			
			'<ul>'.
				'<li>'. esc_html__('Warn, Ban, Restore, or Whitelist any target', 'banhammer') .'</li>'.
				'<li>'. esc_html__('Target by IP, UA, user, request, or referrer', 'banhammer') .'</li>'.
				'<li>'. esc_html__('Whitelist, restore, warn or ban any target', 'banhammer') .'</li>'.
				'<li>'. esc_html__('Display custom banned message for any target', 'banhammer') .'</li>'.
				'<li>'. esc_html__('Edit/customize targets for greater control', 'banhammer') .'</li>'.
				'<li>'. esc_html__('View advanced data like POST and FILES', 'banhammer') .'</li>'.
				'<li>'. esc_html__('Enable/disable logging with a click', 'banhammer') .'</li>'.
				'<li>'. esc_html__('Add private notes for any target', 'banhammer') .'</li>'.
				'<li>'. esc_html__('Automatic email alerts', 'banhammer') .'</li>'.
				'<li>'. esc_html__('Smart bot detection', 'banhammer') .'</li>'.
				'<li>'. esc_html__('Self-ban safety net', 'banhammer') .'</li>'.
			'</ul>'.
			
			'<p>'. esc_html__('..and much more! There are too many features to list them all :)', 'banhammer') .'</p>'.
			
			'<p><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/banhammer-pro/">'. esc_html__('Learn more &amp; get Banhammer Pro at Plugin Planet&nbsp;&raquo;', 'banhammer') .'</a></p>'
		
	);
	
}



function banhammer_settings_contextual_help() {
	
	$screen = get_current_screen();
	
	if ($screen->id !== 'toplevel_page_banhammer') return;
	
	$screen->set_help_sidebar(banhammer_get_help_sidebar());
	
	$screen->add_help_tab(
		
		array(
			
			'id' => 'banhammer-intro',
			'title' => esc_attr__('Introduction', 'banhammer'),
			'content' => 
				'<p><strong>'. esc_html__('Banhammer: Defend. Protect. Secure.', 'banhammer') .'</strong></p>'.
				
				'<p>'. 
					esc_html__('Banhammer is a WordPress security plugin that enables you to monitor traffic and ban any user or bot with a click. ', 'banhammer') . 
					esc_html__('Increase site security by blocking unwanted visitors. Banhammer is lightweight, fast, and easy on resources.',        'banhammer') . 
				'</p>'.
				
				'<p><strong>'. esc_html__('Quick Tour', 'banhammer') .'</strong></p>'.
				
				'<ul>'.
					'<li><a target="_blank" rel="noopener noreferrer" href="'. esc_url(admin_url('admin.php?page=banhammer')) .'">'.        esc_html__('Banhammer Settings', 'banhammer') .'</a> &ndash; '. esc_html__('Configure options', 'banhammer')     .'</li>'.
					'<li><a target="_blank" rel="noopener noreferrer" href="'. esc_url(admin_url('admin.php?page=banhammer-armory')) .'">'. esc_html__('Banhammer Armory',   'banhammer') .'</a> &ndash; '. esc_html__('Monitor site traffic', 'banhammer')  .'</li>'.
					'<li><a target="_blank" rel="noopener noreferrer" href="'. esc_url(admin_url('admin.php?page=banhammer-tower')) .'">'.  esc_html__('Banhammer Tower',    'banhammer') .'</a> &ndash; '. esc_html__('Manage banned targets', 'banhammer') .'</li>'.
				'</ul>'.
				
				'<p><strong>'. esc_html__('Useful Resources', 'banhammer') .'</strong></p>'.
				
				'<ul>'.
					'<li><a target="_blank" rel="noopener noreferrer" href="'. BANHAMMER_URL .'readme.txt">'.                         esc_html__('View the plugin readme.txt file',   'banhammer') .'</a></li>'.
					'<li><a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/banhammer/">'.              esc_html__('Visit the Banhammer Documentation', 'banhammer') .'</a></li>'.
					'<li><a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/banhammer/installation/">'. esc_html__('Visit the Installation Scrolls',    'banhammer') .'</a></li>'.
					'<li><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/support/#contact">'.            esc_html__('Contact the plugin developer',      'banhammer') .'</a></li>'.
					'<li><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/banhammer-pro/">'.              esc_html__('Check out Banhammer Pro',           'banhammer') .'</a></li>'.
				'</ul>'.
				
				'<p><strong>'. esc_html__('About the Developer', 'banhammer') .'</strong></p>'.
				
				'<p>'. 
					esc_html__('Banhammer is developed by', 'banhammer') .' <a target="_blank" rel="noopener noreferrer" href="https://twitter.com/perishable">'. esc_html__('Jeff Starr', 'banhammer') .'</a>, '.
					esc_html__('15-year', 'banhammer') .' <a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/">'. esc_html__('WordPress developer', 'banhammer') .'</a> '.
					esc_html__('and', 'banhammer') .' <a target="_blank" rel="noopener noreferrer" href="https://books.perishablepress.com/">'. esc_html__('book author', 'banhammer') .'</a>.'.
				'</p>'
			
		)
	);
	
	$screen->add_help_tab(
		
		array(
			
			'id' => 'banhammer-overview',
			'title' => esc_attr__('Overview', 'banhammer'),
			'content' => 
				'<p><strong>'. esc_html__('You shall not pass!', 'banhammer') .'</strong></p>'.
				
				'<p>'. 
					esc_html__('Banhammer enables you to monitor traffic and ban any user or bot. ', 'banhammer') . 
					esc_html__('To view your site&rsquo;s traffic, visit the Armory. There you can ban or warn (flag) anything you wish. ', 'banhammer') . 
					esc_html__('Once you have banned something, it will be locked in the Tower, where you can manage all banned users and bots.', 'banhammer') . 
				'</p>'.
				
				'<p><strong>'. esc_html__('How to use Banhammer', 'banhammer') .'</strong></p>'.
				
				'<p>'. esc_html__('Here is an overview of how to use the plugin:', 'banhammer') .'</p>'.
				
				'<ol>'.
					'<li>'. esc_html__('Configure the plugin settings as desired', 'banhammer') .'</li>'.
					'<li>'. esc_html__('Visit the Armory to monitor traffic and ban or warn any user or bot', 'banhammer') .'</li>'.
					'<li>'. esc_html__('Visit the Tower to manage banned items', 'banhammer') .'</li>'.
				'</ol>'.
				
				'<p><strong>'. esc_html__('Some general notes', 'banhammer') .'</strong></p>'.
				
				'<p>'. esc_html__('Two important points to understand:', 'banhammer') .'</p>'.
				
				'<ul>'.
					'<li>'. esc_html__('The Armory is a temporary snapshot of current traffic', 'banhammer') .'</li>'.
					'<li>'. esc_html__('The Tower is a permanent record of all banned items', 'banhammer') .'</li>'.
				'</ul>'.
				
				'<p>'. 
					esc_html__('Note that further information is available in the Armory and Tower Help tabs.', 'banhammer') .  
				'</p>'
			
		)
	);
	
	$screen->add_help_tab(
		
		array(
			
			'id' => 'banhammer-settings',
			'title' => esc_attr__('Settings', 'banhammer'),
			'content' => 
				'<p><strong>'. esc_html__('Basic Settings', 'banhammer') .'</strong></p>'.
				
				'<ul>'.
					'<li>'. esc_html__('Enable Plugin &ndash; Enable or disable Banhammer', 'banhammer') .'</li>'.
					'<li>'. esc_html__('Ignore Users &ndash; Ignore logged-in users',       'banhammer') .'</li>'.
					'<li>'. esc_html__('Login Page &ndash; Protect the WP Login Page',      'banhammer') .'</li>'.
					'<li>'. esc_html__('Admin Area &ndash; Protect WP Admin Area',          'banhammer') .'</li>'.
				'</ul>'.
				
				'<p><strong>'. esc_html__('Banhammer Response', 'banhammer') .'</strong></p>'.
				
				'<ul>'.
					'<li>'. esc_html__('Banned Response &ndash; For all banned requests, display a message or redirect to any URL. Options include:', 'banhammer') .
						'<ul>'.
							'<li>'. esc_html__('Default Message &ndash; Display a stylish banned message', 'banhammer') .'</li>'.
							'<li>'. esc_html__('Custom Message &ndash; Display any custom text/markup',    'banhammer') .'</li>'.
							'<li>'. esc_html__('Redirect &ndash; Redirect the request to any URL',         'banhammer') .'</li>'.
						'</ul>'.
					'</li>'.
					'<li>'. esc_html__('Custom Message &ndash; Specify any text/markup for custom banned message', 'banhammer') .'</li>'.
					'<li>'. esc_html__('Redirect URL &ndash; Specify any valid URL for banned redirects',          'banhammer') .'</li>'.
				'</ul>'.
				
				'<p><strong>'. esc_html__('Advanced Settings', 'banhammer') .'</strong></p>'.
				
				'<ul>'.
					'<li>'. esc_html__('Target Key &ndash; May be used to manually add targets (see below for info)',  'banhammer') .'</li>'.
					'<li>'. esc_html__('Status Code &ndash; Select a status code for banned responses (default: 403)', 'banhammer') .'</li>'.
					'<li>'. esc_html__('Reset Armory &ndash; Time interval to auto-clear Armory (default: One Day)',   'banhammer') .'</li>'.
					'<li>'. esc_html__('Reset Options &ndash; Restore default plugin options',                         'banhammer') .'</li>'.
				'</ul>'.
				
				'<p><strong>'. esc_html__('Target Key', 'banhammer') .'</strong></p>'.
				
				'<p>'. 
					esc_html__('The "Target Key" may be used to manually add targets to Tower. ',                        'banhammer') . 
					esc_html__('Before using the Target Key, remember to click "Save Changes" in the plugin settings. ', 'banhammer') . 
					esc_html__('Currently only IP addresses may be added via this method. ',                             'banhammer') . 
					esc_html__('To add an IP, request the following URL via your browser address bar:',                  'banhammer') . 
				'</p>'.
				
				'<pre><code>'. admin_url('?banhammer-key=[KEY]&banhammer-ip=[IP]') .'</code></pre>'.
				
				'<p>'. esc_html__('Replace [IP] with the IP you want to add, and replace [KEY] with your Target Key.', 'banhammer') .'</p>'.
				
				'<p><em>'. esc_html__('Important! Never share your Target Key, always keep it secret.', 'banhammer') .'</em></p>'.
				
				'<p><strong>'. esc_html__('Auto-Clear Data', 'banhammer') .'</strong></p>'.
				
				'<p>'. 
					esc_html__('The "Reset Armory" setting tells Banhammer when to flush all data in the Armory. ',                                'banhammer') . 
					esc_html__('Remember, all Armory data is temporary. Clearing the Armory at a regular interval is important for performance. ', 'banhammer') .  
					esc_html__('Any banned items will remain banned and available in the Tower. Here are some things to consider:',                'banhammer') . 
				'</p>'.
				
				'<ul>'.
					'<li>'. esc_html__('Sites with less traffic are fine with longer time intervals',   'banhammer') .'</li>'.
					'<li>'. esc_html__('Sites with more traffic should choose a shorter time interval', 'banhammer') .'</li>'.
					'<li>'. esc_html__('If in doubt, go with the default and adjust if/when needed',    'banhammer') .'</li>'.
				'</ul>'
			
		)
	);
	
	$screen->add_help_tab(
		
		banhammer_get_important_notes()
		
	);
	
	$screen->add_help_tab(
		
		banhammer_get_testing_notes()
		
	);
	
	$screen->add_help_tab(
		
		banhammer_get_pro_notes()
		
	);
	
}
add_action('load-toplevel_page_banhammer', 'banhammer_settings_contextual_help');



function banhammer_armory_contextual_help() {
	
	$screen = get_current_screen();
	
	if ($screen->id !== 'banhammer_page_banhammer-armory') return;
	
	$screen->set_help_sidebar(banhammer_get_help_sidebar());
	
	$screen->add_help_tab(
		
		array(
			
			'id' => 'banhammer-armory',
			'title' => esc_attr__('Banhammer Armory', 'banhammer'),
			'content' => '<p><strong>'. esc_html__('Welcome to the Banhammer Armory!', 'banhammer') .'</strong></p>'.
				
				'<p>'. esc_html__('Here you can monitor traffic and drop the banhammer on any unwanted users or bots.',   'banhammer') .'</p>'.
				
				'<p><strong>'. esc_html__('How it works', 'banhammer') .'</strong></p>'.
				
				'<ul>'.
					'<li>'. esc_html__('When someone visits your site, a new entry will be logged and displayed in the Armory.',                   'banhammer') .'</li>'.
					'<li>'. esc_html__('Each logged entry provides data about the visit, including IP address and WP username (if applicable).',   'banhammer') .'</li>'.
					'<li>'. esc_html__('Each logged entry provides "Ban" and "Warn" buttons, enabling you to warn or ban the target.',             'banhammer') .'</li>'.
					'<li>'. esc_html__('The target will be either the IP address or the WP username, which you can select via the "Target" menu.', 'banhammer') .'</li>'.
					'<li>'. esc_html__('You can manage all banned and warned items in the Tower.',                                                 'banhammer') .'</li>'.
				'</ul>'.
				
				'<p><strong>'. esc_html__('Auto-Clear Data', 'banhammer') .'</strong></p>'.
				
				'<p>'. 
					esc_html__('Please understand that all of the data that is displayed in the Armory is temporary. ', 'banhammer') . 
					esc_html__('It will be cleared automatically at regular intervals to help optimize performance. ',  'banhammer') . 
					esc_html__('Visit the Help tab on the Settings page to learn more about auto-clearing the Armory.', 'banhammer') . 
				'</p>'.
				
				'<p><strong>'. esc_html__('Armory controls', 'banhammer') .'</strong></p>'.
				
				'<p>'. 
					esc_html__('The Armory provides numerous controls for navigating and sorting data. ', 'banhammer') . 
					esc_html__('Everything is designed to be as intuitive as possible. Spend a few minutes playing around to get a better idea of how everything works. ',     'banhammer') .
					esc_html__('And remember, everything displayed here in the Armory is temporary, so you can do no harm. Just be careful about who/what you decide to ban!', 'banhammer') . 
				'</p>'.
				
				'<p><strong>'. esc_html__('Pro Version', 'banhammer') .'</strong></p>'.
				
				'<p>'. 
					esc_html__('Note that the Pro version gives you a better Armory experience. ', 'banhammer') . 
					esc_html__('Pro gets you things like private notes, custom ban messages, smart bot detection, and much more. ', 'banhammer') . 
					esc_html__('Plus you have more control to ban targets based on IP address, WP user, user agent, referrer, and request URI. ', 'banhammer') . 
					'<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/banhammer-pro/">'. esc_html__('Check out Banhammer Pro', 'banhammer') .'&nbsp;&raquo;</a>'.
				'</p>'
			
		)
	);
	
	$screen->add_help_tab(
		
		banhammer_get_important_notes()
		
	);
	
	$screen->add_help_tab(
		
		banhammer_get_testing_notes()
		
	);
	
	$screen->add_help_tab(
		
		array(
			
			'id' => 'banhammer-notes',
			'title' => esc_attr__('Notes', 'banhammer'),
			'content' => '<p><strong>'. esc_html__('Performance Tip!', 'banhammer') .'</strong></p>'.
				
				'<p>'. 
					esc_html__('The first time a logged entry is displayed in the Armory, additional data are fetched behind the scenes. ', 'banhammer') . 
					esc_html__('So as you navigate pages, you may notice that pages containing new entries take a bit more time to load. ', 'banhammer') .  
					esc_html__('Subsequent views should be nice and speedy via Ajax.', 'banhammer') . 
				'</p>'.
				
				'<p>'. 
					esc_html__('So with that in mind, it is optimal for performance to keep the number of items per page to a minimum. ', 'banhammer') . 
					esc_html__('Try to keep it anywhere under 10 or so and you should be good. ',                                         'banhammer') . 
					esc_html__('Displaying more rows requires more server resources, so display fewer rows if you experience slowness. ', 'banhammer') . 
					esc_html__('To change the number of entries displayed per page, click "Tools" and go to "Display [x] rows".',         'banhammer') . 
				'</p>'.
				
				'<p><strong>'. esc_html__('Row Limits', 'banhammer') .'</strong></p>'.
				
				'<p>'. 
					esc_html__('Due to limitations with free GeoIP-lookup services, the number of visible Armory rows in the free version of Banhammer is limited to 10. ', 'banhammer') . 
					esc_html__('The Pro version of Banhammer is limited to 50 rows. Unfortunately limits are necessary to help prevent abuse. ', 'banhammer') . 
					esc_html__('Also, if you happen to know (or host) any good and FREE GeoIP/lookup APIs, please let me know.', 'banhammer') . 
				'</p>'.
				
				'<p>'. 
					'<strong>'. esc_html__('Tip:', 'banhammer') .'</strong> '. 
					esc_html__('To change the Row Limit, enter a number and then press the Enter key on your keyboard.', 'banhammer') . 
				'</p>'.
				
				'<p><strong>'. esc_html__('Basic View vs. Advanced View', 'banhammer') .'</strong></p>'.
				
				'<p>'. 
					esc_html__('Under the Tools menu you can toggle between "Basic view" and "Advanced view". ',                     'banhammer') . 
					esc_html__('Basic view gives you a streamlined summary. Advanced view gives you complete data for each entry. ', 'banhammer') . 
					esc_html__('Note that you can toggle each entry individually between Basic and Advanced. ',                      'banhammer') . 
					esc_html__('So for example, you can monitor traffic in Basic view, and then toggle open ',                       'banhammer') . 
					esc_html__('(double-click) any entry that may need banning. The default is Advanced view.',                      'banhammer') . 
				'</p>'.
				
				'<p><strong>'. esc_html__('Sound Effects!', 'banhammer') .'</strong></p>'.
				
				'<p>'. 
					esc_html__('Banhammer sound effects can be enabled by clicking "Tools" and then "Enable sound fx". ', 'banhammer') . 
					esc_html__('When enabled, the sounds will be played whenever an action button is clicked. ',          'banhammer') . 
					esc_html__('This includes the Ban, Warn, Restore, and Delete buttons. ',                              'banhammer') . 
					esc_html__('The Armory provides Ban and Warn buttons. The Tower provides all four. ',                 'banhammer') . 
					esc_html__('Note that enabling sound fx in the Armory applies to the Tower as well.',                 'banhammer') . 
				'</p>'.
				
				'<p>'. 
					esc_html__('Note that the sound effects are a work in progress. Finding quality open source audio is challenging. ', 'banhammer') . 
					esc_html__('If you are able to contribute better effects, please let me know. ',                                     'banhammer') . 
					esc_html__('And of course, the sound effects can be disabled entirely by clicking "Disable sound fx".',              'banhammer') . 
				'</p>'.
				
				'<p><em>'. esc_html__('Now, on to battle!', 'banhammer') .'</em></p>'
			
		)
	);
	
	$screen->add_help_tab(
		
		banhammer_get_pro_notes()
		
	);
	
}
add_action('load-banhammer_page_banhammer-armory', 'banhammer_armory_contextual_help');



function banhammer_tower_contextual_help() {
	
	$screen = get_current_screen();
	
	if ($screen->id !== 'banhammer_page_banhammer-tower') return;
	
	$screen->set_help_sidebar(banhammer_get_help_sidebar());
	
	$screen->add_help_tab(
		
		array(
			
			'id' => 'banhammer-tower',
			'title' => esc_attr__('Banhammer Tower', 'banhammer'),
			'content' => 
				'<p><strong>'. esc_html__('Welcome to the Banhammer Tower!', 'banhammer') .'</strong></p>'.
				
				'<p>'. esc_html__('Here you can manage all targets. Each target:', 'banhammer') .'</p>'.
				
				'<ul>'.
					'<li>'. esc_html__('must be added via the Armory',                  'banhammer') .'</li>'.
					'<li>'. esc_html__('may be a WP user or an IP address',             'banhammer') .'</li>'.
					'<li>'. esc_html__('may be banned, warned, or restored',            'banhammer') .'</li>'.
					'<li>'. esc_html__('is unique and appears only once in the Tower',  'banhammer') .'</li>'.
					'<li>'. esc_html__('can be restored or deleted only via the Tower', 'banhammer') .'</li>'.
				'</ul>'.
				
				'<p>'. 
					esc_html__('So for example, after banning a target via the Armory, you can visit the Tower to restore access or change status to warning. ',  'banhammer') .
					esc_html__('Note that each target shows the number of banned, warned, or restored hits (center column). See the Notes tab for more details.', 'banhammer') .
				'</p>'.
				
				'<p><strong>'. esc_html__('How it works', 'banhammer') .'</strong></p>'.
				
				'<ul>'.
					'<li>'. esc_html__('To ban or warn a target and add it to the Tower, visit the Armory.',                                                       'banhammer') .'</li>'.
					'<li>'. esc_html__('In the Armory, each logged entry provides "Ban" and "Warn" buttons, enabling you to warn or ban the target.',              'banhammer') .'</li>'.
					'<li>'. esc_html__('The target will be either the IP address or the WP username (if applicable), which you can select via the "Target" menu.', 'banhammer') .'</li>'.
					'<li>'. esc_html__('After banning or warning a target, visit the Tower to manage and view the number of blocked/warned hits.',                 'banhammer') .'</li>'.
				'</ul>'.
				
				'<p><strong>'. esc_html__('Tower Controls', 'banhammer') .'</strong></p>'.
				
				'<p>'. 
					esc_html__('The Tower provides numerous controls for managing and sorting data. ',                    'banhammer') . 
					esc_html__('Everything is designed to be as intuitive as possible. ',                                 'banhammer') . 
					esc_html__('Note that it is totally fine to ban, warn, and restore any item as often as desired. ',   'banhammer') . 
					esc_html__('Deleting however is final, unless you find the target in the Armory and warn/ban again.', 'banhammer') . 
				'</p>'.
				
				'<p><strong>'. esc_html__('Pro Version', 'banhammer') .'</strong></p>'.
				
				'<p>'. 
					esc_html__('Note that the Pro version provides a much better Tower experience. ', 'banhammer') . 
					esc_html__('Pro gets you things like paged results, editable targets, private notes, and custom ban messages. ', 'banhammer') . 
					'<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/banhammer-pro/">'. esc_html__('Check out Banhammer Pro', 'banhammer') .'&nbsp;&raquo;</a>'.
				'</p>'
			
		)
	);
	
	$screen->add_help_tab(
		
		banhammer_get_important_notes()
		
	);
	
	$screen->add_help_tab(
		
		banhammer_get_testing_notes()
		
	);
	
	$screen->add_help_tab(
		
		array(
			
			'id' => 'banhammer-notes',
			'title' => esc_attr__('Notes', 'banhammer'),
			'content' => '<p><strong>'. esc_html__('About Actions', 'banhammer') .'</strong></p>'.
				
				'<p>'. esc_html__('Here in the Tower, there are four types of actions (buttons):', 'banhammer') .'</p>'.
				
				'<ul>'.
					'<li>'. esc_html__('Ban &ndash; target is flagged with red color and denied access to your site',       'banhammer') .'</li>'.
					'<li>'. esc_html__('Warn &ndash; target is flagged with orange color and allowed access to your site',  'banhammer') .'</li>'.
					'<li>'. esc_html__('Restore &ndash; target is flagged with grey color and allowed access to your site', 'banhammer') .'</li>'.
					'<li>'. esc_html__('Delete &ndash; target is removed from Tower and allowed access to your site',       'banhammer') .'</li>'.
				'</ul>'.
				
				'<p>'. 
					esc_html__('The "target" may be either a WP user or IP address. Banned targets will be redirected or served the Banhammer response. ', 'banhammer') . 
					esc_html__('To customize the response, visit the settings, "Banned Response" and "Status Code".', 'banhammer') . 
				'</p>'.
				
				'<p><strong>'. esc_html__('Load an Example', 'banhammer') .'</strong></p>'.
				
				'<p>'. 
					esc_html__('If there are no items displayed in the Tower, you can', 'banhammer') .
					' <a class="banhammer-example-link" href="#banhammer">'. esc_html__('click here', 'banhammer') .'</a> '. 
					esc_html__('to load an example. That will load an IP address that has "warned" status. ', 'banhammer') .
					esc_html__('Note that the IP address is not real, so feel free to ban, warn, restore, and delete as desired. ', 'banhammer') .
					esc_html__('For best performance, it is best to delete the example item when not needed.', 'banhammer') .
				'</p>'.
				
				'<p><strong>'. esc_html__('Sound Effects!', 'banhammer') .'</strong></p>'.
				
				'<p>'. 
					esc_html__('Banhammer provides sound effects for fun. ',                               'banhammer') . 
					esc_html__('To enable, visit the Armory &gt; Tools &gt; "Enable sound fx". ',          'banhammer') . 
					esc_html__('When enabled, sounds are played whenever an action button is clicked. ',   'banhammer') .  
					esc_html__('In the Tower, this includes the Ban, Warn, Restore, and Delete buttons. ', 'banhammer') . 
					esc_html__('For more information, visit the Help tab in the Armory (Notes section).',  'banhammer') . 
				'</p>'
			
		)
	);
	
	$screen->add_help_tab(
		
		banhammer_get_pro_notes()
		
	);
	
}
add_action('load-banhammer_page_banhammer-tower', 'banhammer_tower_contextual_help');
