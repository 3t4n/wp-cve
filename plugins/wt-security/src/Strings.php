<?php
/**
 * Code associated with translation strings in twig templates
 */

if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
	if (!headers_sent()) {
		header('HTTP/1.1 403 Forbidden');
	}
	die('Protected By WebTotem!');
}

// common strings
__('No data', 'wtotem');
__('Has no action ready yet', 'wtotem');
__('Show more', 'wtotem');
__('Load more', 'wtotem');

// activation.html.twig
__('Activate the plugin', 'wtotem');
__('Welcome friend!', 'wtotem');
__('Sign in to continue to WebTotem', 'wtotem');
__('API-KEY code', 'wtotem');
__('ACTIVATE', 'wtotem');
__('You can receive the keys in your personal account <a>cabinet</a>', 'wtotem');
__('or read the activation <a>manual</a>', 'wtotem');

// agents.html.twig
__('Agent manager installation', 'wtotem');

// agents_installation.html.twig
__('Install', 'wtotem');
__('Installed', 'wtotem');
__('Installing', 'wtotem');
__('Reinstall agents', 'wtotem');
__('Failed to install', 'wtotem');
__('Failed to install Agent Manager', 'wtotem');

// allow_deny_list.html.twig
__('IP Address', 'wtotem');
__('Date added', 'wtotem');
__('Actions', 'wtotem');
__('No IP address here', 'wtotem');
__('Remove', 'wtotem');

// allow_url_list.html.twig
__('URL address', 'wtotem');
__('No URL address here', 'wtotem');

// antivirus_logs.html.twig
__('Permissions or access rights has been modified', 'wtotem');
__('Move to quarantine', 'wtotem');

// antivirus.html.twig
__('Last scan', 'wtotem');
__('File', 'wtotem');
__('Permission', 'wtotem');
__('Shows permissions or access rights configurations.', 'wtotem');
__('Time', 'wtotem');
__('Status', 'wtotem');
__('Need more support', 'wtotem');
__('Let\'s talk!', 'wtotem');

//antivirus_filter_form.html.twig
__('Scanned files', 'wtotem');
__('Changed files', 'wtotem');
__('Deleted files', 'wtotem');
__('Infected files', 'wtotem');
__('New files', 'wtotem');
__('Permissions changed', 'wtotem');
__('Download', 'wtotem');
__('Rescan', 'wtotem');


// attacks_map.html.twig
__('Country', 'wtotem');
__('Attack map', 'wtotem');

// dots_loader.html.twig
__('Please wait', 'wtotem');
__('We are still crawling your site', 'wtotem');

//chart_periods.html.twig
__('Yearly', 'wtotem');
__('Monthly', 'wtotem');
__('Weekly', 'wtotem');
__('Daily', 'wtotem');

// firewall.html.twig
__('Type/IP', 'wtotem');
__('Attack location', 'wtotem');
__('Report', 'wtotem');
__('Hostname', 'wtotem');
__('Source', 'wtotem');
__('Request', 'wtotem');
__('User agent', 'wtotem');
__('Time', 'wtotem');
__('Type', 'wtotem');
__('Category', 'wtotem');
__('Country', 'wtotem');
__('Payload', 'wtotem');

// firewall_stats.html.twig
__('Firewall needs up to 2 days to finish training', 'wtotem');
__('Suspicious events', 'wtotem');
__('Blocked', 'wtotem');
__('Low risk', 'wtotem');
__('Attack from', 'wtotem');
__('Pending', 'wtotem');
__('Suspicious event - any event, either blocked or non blocked because of being low risk, with a sign of a malicious request.', 'wtotem');
__('Blocked - a suspicious event, found to be critical enough to get blocked.', 'wtotem');
__('Low risk - a suspicious event with a feature of a malicious request yet not critical enough to get blocked.', 'wtotem');

// footer.html.twig
__('Your best friend in cybersecurity world', 'wtotem');
__('All rights reserved', 'wtotem');
__('How would you rate our product?', 'wtotem');
__('What disappointed or displeased you?', 'wtotem');
__('Additional feedback', 'wtotem');
__('If you have any additional feedback, please type it in here...', 'wtotem');
__('Submit feedback', 'wtotem');

// help.html.twig
__('Help center', 'wtotem');
__('General information', 'wtotem');
__('Our tools', 'wtotem');
__('Description of statuses', 'wtotem');
__('Instructions', 'wtotem');
__('Documentation', 'wtotem');

// ignore_ports.html.twig
__('Port', 'wtotem');

// layout.html.twig
__('Dashboard', 'wtotem');
__('Firewall', 'wtotem');
__('Antivirus', 'wtotem');
__('Settings', 'wtotem');
__('Reports', 'wtotem');
__('Help', 'wtotem');

// monitoring.html.twig
__('SSL module', 'wtotem');
__('Displays the status of the SSL Certificate.', 'wtotem');
__('Days left', 'wtotem');
__('Issue date', 'wtotem');
__('Expiry date', 'wtotem');
__('Availability module', 'wtotem');
__('Performance', 'wtotem');
__('Response time', 'wtotem');
__('Downtime', 'wtotem');
__('Last test', 'wtotem');
__('Reputation module', 'wtotem');
__('Checks website entries in 60+ blacklisting authorities.', 'wtotem');
__('Deny lists entries', 'wtotem');
__('Check the performance of your site every minute. Stay informed about the problems of accessibility of the site.', 'wtotem');
__('Presence of the site', 'wtotem');
__('Redirect', 'wtotem');
__('IP address', 'wtotem');
__('Site protection', 'wtotem');
__('Check time', 'wtotem');
__('Cert name', 'wtotem');

// open_paths_page.html.twig
__('Open paths detected', 'wtotem');
__('paths', 'wtotem');

// popup.html.twig
__('Continue', 'wtotem');
__('Cancel', 'wtotem');
__('Are you sure?', 'wtotem');

// ports_form.html.twig
__('Port scanner deny list', 'wtotem');
__('Open ports', 'wtotem');
__('Type port number', 'wtotem');
__('Technology', 'wtotem');
__('Add to ignore list', 'wtotem');
__('Ports list', 'wtotem');
__('Ignored ports', 'wtotem');

// quarantine.html.twig
__('Quarantine', 'wtotem');
__('Files in quarantine', 'wtotem');
__('Date', 'wtotem');

// quarantine_logs.html.twig
__('Restore file', 'wtotem');

// reports.html.twig
__('Generate report', 'wtotem');
__('Generate new report', 'wtotem');
__('Report data', 'wtotem');
__('Generated time', 'wtotem');
__('Type', 'wtotem');
__('Modules', 'wtotem');

// reports_form.html.twig
__('Report settings', 'wtotem');
__('Select report period', 'wtotem');
_n( '%s month','%s months', 1, 'wtotem' );
__('year', 'wtotem');
__('Select Date', 'wtotem');
__('Date from', 'wtotem');
__('Date to', 'wtotem');
__('Choose modules', 'wtotem');
__('Availability', 'wtotem');
__('Ports', 'wtotem');
__('Scoring', 'wtotem');
__('Close', 'wtotem');

// scanning.html.twig
__('Port scanner', 'wtotem');
__('Detects open ports on the server. Potentially, open ports can be dangerous and used by hackers.', 'wtotem');
__('Deface scanner', 'wtotem');
__('Tracks the possible hacker attack with the main page substitution.', 'wtotem');
__('Found words', 'wtotem');
__('Disc usage', 'wtotem');
__('Shows the Disc load and its accessible memory.', 'wtotem');
__('Total', 'wtotem');
__('Use', 'wtotem');
__('Free', 'wtotem');
__('Need more support?', 'wtotem');
__('Open ports', 'wtotem');
__('More', 'wtotem');
__('Everything is ok', 'wtotem');
__('No open ports found', 'wtotem');
__('No open path found', 'wtotem');
__('Open paths', 'wtotem');
__('Display potentially dangerous open paths', 'wtotem');
__('Last test', 'wtotem');
__('Status', 'wtotem');
__('ForceCheck', 'wtotem');

// score.html.twig
__('Overall Security Grade', 'wtotem');
__('Scoring module', 'wtotem');
__('Assesses overall site security, identifies vulnerabilities, misconfigurations, and data leak, as well as gives recommendations for their elimination.', 'wtotem');
__('Your security grade is higher than %s of the companies in your industry.','wtotem');
__('Tested on:', 'wtotem');
__('Server Ip:', 'wtotem');
__('Location:', 'wtotem');
__('Full scoring', 'wtotem');

// server_status_cpu.html.twig
__('CPU Load average', 'wtotem');
__('Shows the CPU load', 'wtotem');

// server_status_ram.html.twig
__('Random access memory', 'wtotem');
__('RAM', 'wtotem');
__('Shows the RAM load', 'wtotem');

// settings_form.html.twig
__('Module settings', 'wtotem');
__('If you do not need any module, then you can disable it', 'wtotem');
__('Server status', 'wtotem');
__('Availability/SSL', 'wtotem');
__('Deface', 'wtotem');
__('Reputation', 'wtotem');
__('Technologies', 'wtotem');
__('IP lists configuration', 'wtotem');
__('Firewall configuration', 'wtotem');
__('Allow list', 'wtotem');
__('Deny list', 'wtotem');
__('URL Allow list', 'wtotem');
__('Type IPv4 or IPv6 address or a mask (104.122.249.38 or 104.122.*.*)', 'wtotem');
__('Add IP', 'wtotem');
__('Multi-adding IP', 'wtotem');
__('Add URL', 'wtotem');
__('Agent installation', 'wtotem');
__('If you have any problems with our agent, we advise you to reinstall it', 'wtotem');
__('API-key change', 'wtotem');
__('DoS limits', 'wtotem');
__('Limits the number of requests per minute from an IP address.', 'wtotem');
__('Login attempts', 'wtotem');
__('Limits the number of login attempts per minute.', 'wtotem');
__('DoS limits (requests per minute)', 'wtotem');
__('Save settings', 'wtotem');
__('Incorrect IP addresses', 'wtotem');
__('IP addresses success added', 'wtotem');
__('How to use?', 'wtotem');
__('Example:', 'wtotem');
__('Add IP list', 'wtotem');
__('Notifications', 'wtotem');
__('Send me notifications on e-mail', 'wtotem');
__('This option protects you from hackers detected on other websites connected to our global defence network.','wtotem');
__('If you want to add several IP addresses at once, you can add the address separated by commas.','wtotem');
__('Two-Factor Authentication','wtotem');
__('Deactivate 2FA','wtotem');
__('Activate 2FA','wtotem');
__('Enable Two-factor authorization','wtotem');
__('1. Scan Code or Enter Key','wtotem');
__('Scan the code below with your mobile app to add this account. Some authenticator apps also allow you to type in the text version instead.','wtotem');
__('2. Enter Code from mobile app','wtotem');
__('Use one of these codes to log in if you lose access to your authenticator device.','wtotem');
__('Enter the code from your mobile app below to verify and activate two-factor authentication for this account','wtotem');
__('Enable reCAPTCHA','wtotem');
__('Enable reCAPTCHA on login pages','wtotem');
__('Authorization attempts','wtotem');
__('The number of login and password reset attempts on the login page','wtotem');
__('Login attempt counter','wtotem');
__('Password reset attempt counter','wtotem');
__('Number of attempts (per minute)','wtotem');
__('Set limits','wtotem');
__('Minutes of ban','wtotem');
__('Select interval','wtotem');
__('minutes','wtotem');
__('hour','wtotem');
__('hours','wtotem');
__('Other options','wtotem');
__('Hide WP version','wtotem');
__('Two-factor authentication is currently active on your account. You may deactivate it by clicking the button below','wtotem');
__('Makes two-factor authorization available to all users of the site','wtotem');
__('Enables two-factor authorization for the current user','wtotem');
__('Scan QR','wtotem');
__('Enter key','wtotem');
__('Enter the code','wtotem');
__('This Login attempts function belongs to the WAF agent itself. It is replaced with the "Authorization attempts limit" integration for WordPress. You can access it below in the setting.','wtotem');



//  country_blocking_modal.html.twig
__('save','wtotem');
__('close','wtotem');
__('Block countries','wtotem');
__('Name of the country','wtotem');
__('Select all countries','wtotem');
__('Access blocked to','wtotem');
__('countries','wtotem');
__('Country blocking','wtotem');
__('Block countries you want to limit access to your website.','wtotem');
__('Attack from','wtotem');
__('countries blocked from','wtotem');
__('Select all','wtotem');

// User profile
__('WebTotem two-factor protection','wtotem');
__('Edit 2FA Settings','wtotem');
__('Disactivate 2FA','wtotem');

// waf_filter_form.html.twig
__('Blocked', 'wtotem');

// multisite.html.twig
__('Services status', 'wtotem');
__('Site name', 'wtotem');
__('Report page', 'wtotem');
__('All stats', 'wtotem');

// multisite.html.twig
__('Try reinstalling the agents or changing the API key', 'wtotem');
__('Data access error', 'wtotem');

// scan_logs.html.twig
__('Start scanning', 'wtotem');
__('Scan is running', 'wtotem');
__('Refresh', 'wtotem');
__('Refreshing', 'wtotem');
__('Automatic scanning every 24 hours', 'wtotem');
__('Until the next automatic scan', 'wtotem');
__('Scans', 'wtotem');
__('Confidential files', 'wtotem');
__('In this section you can find information about confidential files. These are files that may contain sensitive data. As well as a list of found links, scripts and frames on the site pages.', 'wtotem');
__('Audit logs', 'wtotem');
__('Log of user actions in the admin panel.', 'wtotem');
__('Links', 'wtotem');
__('Scripts', 'wtotem');
__('iFrames', 'wtotem');
__('Time', 'wtotem');
__('User', 'wtotem');
__('Event', 'wtotem');
__('All', 'wtotem');
__('User authentication succeeded', 'wtotem');
__('User authentication failed', 'wtotem');
__('User account created', 'wtotem');
__('User account deleted', 'wtotem');
__('User account edited', 'wtotem');
__('Attempt to reset password', 'wtotem');
__('Password retrieval attempt', 'wtotem');
__('User added to website', 'wtotem');
__('User removed from website', 'wtotem');
__('WordPress updated', 'wtotem');
__('User account deleted', 'wtotem');
__('Bookmark link added', 'wtotem');
__('Bookmark link edited', 'wtotem');
__('Category created', 'wtotem');
__('Publication was published', 'wtotem');
__('Publication was updated', 'wtotem');
__('Post status has been changed', 'wtotem');
__('Post deleted', 'wtotem');
__('Post moved to trash', 'wtotem');
__('Media file added', 'wtotem');
__('Plugin activated', 'wtotem');
__('Plugin deactivated', 'wtotem');
__('Theme activated', 'wtotem');
__('Settings changed', 'wtotem');
__('Plugins deleted', 'wtotem');
__('Plugin editor used', 'wtotem');
__('Plugin installed', 'wtotem');
__('Plugins updated', 'wtotem');
__('Theme deleted', 'wtotem');
__('Theme editor used', 'wtotem');
__('Theme installed', 'wtotem');
__('Themes updated', 'wtotem');
__('Widget deleted', 'wtotem');
__('Widget added', 'wtotem');
__('There is nothing', 'wtotem');
__('Congratulations!<br>There\'s nothing here', 'wtotem');
__('Are you sure you want to delete the file?', 'wtotem');
__('Delete', 'wtotem');
__('Copy name', 'wtotem');
__('Copy path', 'wtotem');
__('Name copied', 'wtotem');
__('Path copied', 'wtotem');
__('Link', 'wtotem');
__('Script', 'wtotem');
__('iframe', 'wtotem');
__('Internal', 'wtotem');
__('External', 'wtotem');
__('Path', 'wtotem');
__('File name', 'wtotem');
__('Last modify', 'wtotem');
__('Size', 'wtotem');

// prompt.html.twig
__('Continue deactivation', 'wtotem');
__('Go back to plugins', 'wtotem');

// antivirus_history.html.twig
__('Week', 'wtotem');
__('Month', 'wtotem');
__('Year', 'wtotem');
__('Scan', 'wtotem');
__('Start time', 'wtotem');
__('End time', 'wtotem');
__('Duration', 'wtotem');
__('Scanned', 'wtotem');
__('Infected', 'wtotem');
__('History', 'wtotem');

//antivirus_history_items.html.twig
__('Passed a full scan', 'wtotem');
__('Partial scan', 'wtotem');

//antivirus_logs.html.twig
__('Everything is okay', 'wtotem');
__('Infected files found', 'wtotem');
__('Scanning is partially completed', 'wtotem');

// antivirus_scan_status.html.twig
__('Scan process', 'wtotem');
__('Scanned', 'wtotem');
__('Scanning started at', 'wtotem');
__('The scan has not been launched yet', 'wtotem');
__('Force scan', 'wtotem');
__('Scan is running', 'wtotem');

// antivirus_stats.html.twig
__('Antivirus Log', 'wtotem');
__('View all', 'wtotem');
__('at', 'wtotem');
__('Antivirus Log', 'wtotem');
__('Scan history', 'wtotem');
__('Scanned', 'wtotem');
__('Infected Files', 'wtotem');

// quarantine.html.twig
__('Infected', 'wtotem');
__('Quarantine', 'wtotem');

// quarantine_logs.html.twig
__('Offset', 'wtotem');
__('Row', 'wtotem');
__('Description', 'wtotem');
__('No files in quarantine', 'wtotem');
__('No infected files found', 'wtotem');
__('They are most likely in quarantine', 'wtotem');
