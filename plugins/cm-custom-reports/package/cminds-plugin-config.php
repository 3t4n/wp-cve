<?php

ob_start();
include plugin_dir_path(__FILE__) . 'views/plugin_compare_table.php';
$plugin_compare_table = ob_get_contents();
ob_end_clean();

$cminds_plugin_config = array(
	'plugin-is-pro'				 => FALSE,
	'plugin-is-addon'			 => FALSE,
	'plugin-version'			 => '1.1.11',
	'plugin-abbrev'				 => 'cmcr',
	'plugin-file'				 => CMCR_PLUGIN_FILE,
    'plugin-affiliate'               => '',
    'plugin-redirect-after-install'  => admin_url( 'admin.php?page=cm-custom-reports-settings' ),
    'plugin-show-guide'              => TRUE,
    'plugin-guide-text'              => '<div style="display:block">
        <ol>
            <li>Go to <strong>"Reports"</strong> under the CM Custom Reports  menu</li>
            <li>Choose the report you want to generate</li>
            <li>You can adjust the dates or select the report graph type.</li>
            <li>You can also download the generated report output to a PDF format</li>
        </ol>
    </div>',
    'plugin-guide-video-height'      => 240,
    'plugin-guide-videos'            => array(
        array( 'title' => 'Installation tutorial', 'video_id' => '164061174' ),
    ),
	'plugin-dir-path'			 => plugin_dir_path( CMCR_PLUGIN_FILE ),
	'plugin-dir-url'			 => plugin_dir_url( CMCR_PLUGIN_FILE ),
	'plugin-basename'			 => plugin_basename( CMCR_PLUGIN_FILE ),
	'plugin-icon'				 => '',
	'plugin-name'				 => CMCR_NAME,
	'plugin-license-name'		 => CMCR_NAME,
    'plugin-campign'             => '?utm_source=customreportsfree&utm_campaign=freeupgrade',    
	'plugin-slug'				 => '',
	'plugin-short-slug'			 => 'custom-reports',
	'plugin-menu-item'			 => CMCR_SLUG_NAME,
	'plugin-textdomain'			 => CMCR_SLUG_NAME,
     'plugin-upgrade-text'           => 'Good Reasons to Upgrade to Pro',
    'plugin-upgrade-text-list'      => array(
        array( 'title' => 'Introduction to custom reports manager', 'video_time' => '0:00' ),
        array( 'title' => 'Reports available in the primium version', 'video_time' => '0:30' ),
        array( 'title' => 'Graphic version of report output', 'video_time' => '0:45' ),
        array( 'title' => 'CSV outout of the reports', 'video_time' => '0:55' ),
        array( 'title' => 'Schedule Reports', 'video_time' => '1:00' ),
        array( 'title' => 'Email templates for sent reports', 'video_time' => '1:10' ),
    ),
    'plugin-upgrade-video-height'   => 240,
    'plugin-upgrade-videos'         => array(
        array( 'title' => 'Custom Reports Premium Features', 'video_id' => '121942578' ),
    ),
	'plugin-userguide-key'		 => '306-cm-custom-reports',
	'plugin-store-url'			 => 'https://www.cminds.com/wordpress-plugins-library/purchase-cm-custom-reports-plugin-for-wordpress?utm_source=customreports&utm_campaign=freeupgrade&upgrade=1',
	'plugin-support-url'		 => 'https://www.cminds.com/contact/',
	'plugin-review-url'			 => 'https://wordpress.org/support/view/plugin-reviews/cm-custom-reports',
	'plugin-changelog-url'		 => CMCR_RELEASE_NOTES,
	'plugin-licensing-aliases'	 => array( '' ),
	'plugin-compare-table'	 => $plugin_compare_table,

);