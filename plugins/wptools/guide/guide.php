<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * @author William Sergio Minozzi
 * @copyright 2021
 */
$wptools_help = '';
$wptools_help .= '<h4>' .esc_attr__("This plugin has currently more than 44 tools to help you to manage your WordPress site, included the dashboard with CPU/Disk/Memory usage charts and more info.", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '<br>';
$wptools_help .=  '1) '. esc_attr__("Show the PHP errors, limited to 200 last errors (to avoid freeze your browser). Just click Show PHP Erros voice at the menu", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '2) '.   esc_attr__("Increase the memory limit, time limit and max upload file size limit without editing any WordPress or PHP files. Just Click General Settings Tab", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '3) '.   esc_attr__("Show the PHPINFO (PHP info) with a lot of info about your PHP server configuration, also server IP.", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '4) '.   esc_attr__("Show Percentage Server Load (CPU Usage) Average for the last minute at top admin bar.", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '5) '.   esc_attr__("Disable WordPress Native Sitemap Automatic Creation (or only user's sitemap).", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '6) '.   esc_attr__("Disables the default notification email sent by a site after an automatic core, theme or plugin update.", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '7) '.   esc_attr__("Add Google Analytics GA Tracking ID (Univeral Analytics) on footer.", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '8) '.   esc_attr__("Add Google Search Central (formerly Google Webmasters) HTML TAG and Bing Meta Name","wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '9) '.   esc_attr__("Alert on Top Admin Bar if WordPress Debug is active.", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '10) '.   esc_attr__("Hide Admin Bar from non Administrators.", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '11) '.   esc_attr__("Deactivate Lazy Load functionality (added in WP version 5.5)", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '12) '.   esc_attr__("Deactivate Emojis functionality (support for emoji's in older browsers)", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '13) '.   esc_attr__("Page Load Info: Number of SQL queries per page and page load time.", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '14) '.   esc_attr__("Record and send emails notifications when PHP notices, warnings and errors happens.", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '15) '.   esc_attr__("Show and edit rebots.txt.", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '16) '.   esc_attr__("Show and test mySQL tables (name, status, size and last update).", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '17) '.   esc_attr__("Bypass WordPress debug (if WP_DEBUG = false) and show errors and warnings on screen. (Don't use in production!)", "wptools");
$wptools_help .= '<br>';
$wptools_help .= '18) '.   esc_attr__("Show Cron Jobs table.", "wptools");
$wptools_help .= '<br>';
$wptools_help .= '19) '.   esc_attr__("Show file .htaccess", "wptools");
$wptools_help .= '<br>';
$wptools_help .= '20) '.   esc_attr__("Show file wp-config.php", "wptools");
$wptools_help .= '<br>';
$wptools_help .= '21) '.   esc_attr__("Show Cookies", "wptools");
$wptools_help .= '<br>';
$wptools_help .= '22) '. esc_attr__('Restores the previous ("classic") widgets settings screens and disables the block editor from managing widgets.', 'wptools');
$wptools_help .= '<br>';
$wptools_help .= '23) '. esc_attr__('Disable the WP Admin Bar / Toolbar on the frontend of sites. (it does not affect the dashboard)', 'wptools');
$wptools_help .= '<br>';
$wptools_help .= '24) '. esc_attr__('Button to Show Errors on Admin Bar (Also Javascript errors!)', 'wptools');
$wptools_help .= '<br>';
$wptools_help .= '25) '. esc_attr__('Show File and Folders Permissions', 'wptools');
$wptools_help .= '<br>';
$wptools_help .= '26) '. esc_attr__('Show JQuery and Migrate Versions (look Javascript and JQuery).', 'wptools');
$wptools_help .= '<br>';
$wptools_help .= '27) '. esc_attr__('Erase readme.html and licence.txt files at root folder.', 'wptools');
$wptools_help .= '<br>';
$wptools_help .= '28) '. esc_attr__('Show disk size, disk used, disk free and Top Bigger Folders.', 'wptools');
$wptools_help .= '<br>';
$wptools_help .= '29)'. esc_attr__('Remove WP icon from admin toolbar (top left).', 'wptools');
$wptools_help .= '<br>';
$wptools_help .= '30) '. esc_attr__('Replace WordPress logo at Login Page.', 'wptools');
$wptools_help .= '<br>';
$wptools_help .= '31) '. esc_attr__('Server Benchmark (performance).', 'wptools');
$wptools_help .= '<br>';
$wptools_help .= '32) '.   esc_attr__("Show the mySQL  (database info) with a lot of info about your mySQL server configuration.", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '33) '.   esc_attr__("Disable javascript console logs for non administrators.", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '34) '.   esc_attr__("Show and check file permissions (included wp-config.php).", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '35) '.   esc_attr__("Show and delete transients.", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '36) '.   esc_attr__("Enables the WordPress database tools to optimize and repair InnoDB and MyISAM database tables.", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '37) '.   esc_attr__("Disable Self PingBack.", "wptools"); 
$wptools_help .= '<br>';


$wptools_help .= '38) '.   esc_attr__("Show Search Engine Visibility WordPress Setup.", "wptools");
$wptools_help .= '<br>';
$wptools_help .= '39) '.   esc_attr__("Show Server Rooth Path.", "wptools");
$wptools_help .= '<br>';
$wptools_help .= '40) '.   esc_attr__("Site Health Alert.", "wptools");
$wptools_help .= '<br>';
$wptools_help .= '41) '.   esc_attr__("Show PHP Extensions Loaded.", "wptools");
$wptools_help .= '<br>';
$wptools_help .= '42) '.   esc_attr__("Show PHP Disabled Functions.", "wptools");
$wptools_help .= '<br>';
$wptools_help .= '43) '.   esc_attr__("Show MYSQL Table Prefix.", "wptools");
$wptools_help .= '<br>';
$wptools_help .= '44) '.   esc_attr__("Show Database charset (character_set_system).", "wptools");


$wptools_help .= '<br>';



$wptools_help .= '<br>';
$wptools_help .=    esc_attr__("Go to other TABS and enable that you want.", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '<br>';
$wptools_help .=    esc_attr__("Check also the left menu for more tools (Dashboard => WP Tools).", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '<br>';
$wptools_help .=   esc_attr__("Visit our site for more details.", "wptools"); 
$wptools_help .= ' ';
$wptools_help .= '<a href="http://wptoolsplugin.com">Plugin Site</a>';
$wptools_help .= '<br>';
$wptools_help .= '<br>';
$wptools_help .=   esc_attr__("That is all. Enjoy.", "wptools"); 
$wptools_help .= '<br>';
$wptools_help .= '<br>';
$wptools_help .= '</h4>'; ?>
