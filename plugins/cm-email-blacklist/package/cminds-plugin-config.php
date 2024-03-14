<?php
ob_start();
include plugin_dir_path(__FILE__) . 'views/plugin_compare_table.php';
$plugin_compare_table = ob_get_contents();
ob_end_clean();

$cminds_plugin_config = array(
	'plugin-is-pro'				 => FALSE,
	'plugin-is-addon'			 => FALSE,
	'plugin-version'			 => '1.4.7',
	'plugin-abbrev'				 => 'cmeb',
	'plugin-file'				 => CMEB_PLUGIN_FILE,
	'plugin-dir-path'			 => plugin_dir_path( CMEB_PLUGIN_FILE ),
	'plugin-dir-url'			 => plugin_dir_url( CMEB_PLUGIN_FILE ),
	'plugin-basename'			 => plugin_basename( CMEB_PLUGIN_FILE ),
    'plugin-campign'             => '?utm_source=blacklistfree&utm_campaign=freeupgrade',
	'plugin-icon'				 => '',
    'plugin-affiliate'               => '',
    'plugin-redirect-after-install'  => admin_url( 'admin.php?page=cmeb_menu' ),
       'plugin-show-guide'              => TRUE,
    'plugin-guide-text'              => '    <div style="display:block">
        <ol>
            <li>The plugin only works when at least one of the General Options is checked. If both are used, then the domain has to be either Whitelisted or not in the Free Domain list.</li>
            <li>Go to <strong>"Plugin Settings"</strong> and decide which method to use: Whitelist or Free Domain List</li>
            <li>If you choose to use the Whitelist method <strong>"add the domains"</strong> from which user can register to your site</li>
            <li>If you chosen to use the Free Domains - registration to your site will be limited and will not include any domain appearing in the free domains list.</li>
            <li>You can use the testing option to test the ability to register with different domains </li>
        </ol>
    </div>',
    'plugin-guide-video-height'      => 240,
    'plugin-guide-videos'            => array(
        array( 'title' => 'Installation tutorial', 'video_id' => '158514903' ),
    ),
	'plugin-name'				 => CMEB_NAME,
	'plugin-license-name'		 => CMEB_NAME,
	'plugin-slug'				 => '',
	'plugin-short-slug'			 => 'email-blacklist',
	'plugin-menu-item'			 => CMEB_MENU_ITEM,
	'plugin-textdomain'			 => CMEB_SLUG_NAME,
       'plugin-upgrade-text'           => 'Good Reasons to Upgrade to Pro',
    'plugin-upgrade-text-list'      => array(
        array( 'title' => 'Introduction to email blacklist', 'video_time' => '0:00' ),
        array( 'title' => 'Blacklist and Whitelist domain explained', 'video_time' => '0:54' ),
        array( 'title' => 'Blacklist and Whitelist domain settings', 'video_time' => '1:24' ),
        array( 'title' => 'Free domain list from SpamAssassin', 'video_time' => '1:57' ),
        array( 'title' => 'User defined domain blacklist', 'video_time' => '2:46' ),
        array( 'title' => 'Domain tester', 'video_time' => '3:20' ),
        array( 'title' => 'User defined domain whitelist', 'video_time' => '3:37' ),
        array( 'title' => 'Registration log', 'video_time' => '4:55' ),
        array( 'title' => 'Frontend display of messages', 'video_time' => '5:27' ),
    ),
    'plugin-upgrade-video-height'   => 240,
    'plugin-upgrade-videos'         => array(
        array( 'title' => 'Email Blacklist Premium Features', 'video_id' => '123027044' ),
    ),
	'plugin-userguide-key'		 => '2339-cm-email-blacklist-cmrb-free-version-guide',
	'plugin-store-url'			 => 'https://www.cminds.com/wordpress-plugins-library/purchase-cm-email-registration-blacklist-plugin-for-wordpress?utm_source=blacklistfree&utm_campaign=freeupgrade&upgrade=1',
	'plugin-support-url'		 => 'https://www.cminds.com/contact/',
	'plugin-video-tutorials-url'		 => 'https://www.videolessonsplugin.com/video-lesson/lesson/email-domain-blacklist-plugin/',
	'plugin-review-url'			 => 'https://www.cminds.com/wordpress-plugins-library/email-registration-blacklist-plugin-for-wordpress/#reviews',
	'plugin-changelog-url'		 => 'https://www.cminds.com/wordpress-plugins-library/purchase-cm-email-registration-blacklist-plugin-for-wordpress/#changelog',
	'plugin-licensing-aliases'	 => array( '' ),
	'plugin-compare-table'	 => $plugin_compare_table,
);