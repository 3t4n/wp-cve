<?php

ob_start();
include plugin_dir_path(__FILE__) . 'views/plugin_compare_table.php';
$plugin_compare_table = ob_get_contents();
ob_end_clean();

$cminds_plugin_config = array(
    'plugin-is-pro'                 => FALSE,
    'plugin-is-addon'               => FALSE,
    'plugin-version'                => '2.1.2',
    'plugin-abbrev'                 => 'cmf',
    'plugin-short-slug'             => 'footnotes',
    'plugin-campign'                => '?utm_source=footnotesfree&utm_campaign=freeupgrade&upgrade=1',
    'plugin-parent-short-slug'      => '',
    'plugin-affiliate'              => '',
    'plugin-redirect-after-install' => admin_url('admin.php?page=cmf_settings'),
    'plugin-show-guide'             => TRUE,
    'plugin-guide-text'             => '    <div style="display:block">
        <ol>
           <li>Go to <strong>"Add New"</strong> under the CM Footnotes menu</li>
            <li>Fill the <strong>"Title"</strong> of the footnote item and <strong>"Content"</strong></li>
            <li>Click <strong>"Publish" </strong> in the right column.</li>
            <li><strong>View</strong> post or pages in which the footnote term appears to check if the footnotes table appears at the bottom of the post</li>
            <li>From the plugin settings customize the footnotes appearance</li>
            <li><strong>Troubleshooting:</strong> If you get a 404 error once viewing the footnote item,  make sure your WordPress permalinks are set and save them again to refresh</li>
         </ol>
    </div>',
    'plugin-guide-video-height'     => 240,
    'plugin-guide-videos'           => array(
        array('title' => 'Installation tutorial', 'video_id' => '164061211'),
    ),
    'plugin-upgrade-text'           => 'Good Reasons to Upgrade to Pro',
    'plugin-upgrade-text-list'      => array(
        array('title' => 'Introduction to footnotes plugin', 'video_time' => '0:00'),
        array('title' => 'Footnotes examples', 'video_time' => '0:32'),
        array('title' => 'Adding footnotes from front-end', 'video_time' => '1:01'),
        array('title' => 'Adding footnotes from back-end', 'video_time' => '1:31'),
        array('title' => 'Advaced general settings', 'video_time' => '2:02'),
        array('title' => 'Footnotes general index', 'video_time' => '2:27'),
        array('title' => 'Footnotes design and setting', 'video_time' => '3:04'),
        array('title' => 'Import and export footnotes', 'video_time' => '3:58'),
        array('title' => 'Supported shortcodes', 'video_time' => '4:22'),
    ),
    'plugin-upgrade-video-height'   => 240,
    'plugin-upgrade-videos'         => array(
        array('title' => 'Footnotes Plugin Premium Features', 'video_id' => '127328188'),
    ),
    'plugin-file'          => CMF_PLUGIN_FILE,
    'plugin-dir-path'      => plugin_dir_path(CMF_PLUGIN_FILE),
    'plugin-dir-url'       => plugin_dir_url(CMF_PLUGIN_FILE),
    'plugin-basename'      => plugin_basename(CMF_PLUGIN_FILE),
    'plugin-icon'          => '',
    'plugin-name'          => CMF_NAME,
    'plugin-license-name'  => CMF_NAME,
    'plugin-slug'          => '',
    'plugin-menu-item'     => CMF_MENU_OPTION,
    'plugin-textdomain'    => CMF_SLUG_NAME,
    'plugin-userguide-key' => '2846-cm-footnotes-cmf-free-version-guide',
    'plugin-store-url'     => 'https://www.cminds.com/wordpress-plugins-library/cm-footnotes-plugin-for-wordpress/',
    'plugin-review-url'    => 'https://wordpress.org/support/view/plugin-reviews/cm-footnotes',
    'plugin-changelog-url' => CMF_RELEASE_NOTES,
    'plugin-compare-table' => $plugin_compare_table,

);