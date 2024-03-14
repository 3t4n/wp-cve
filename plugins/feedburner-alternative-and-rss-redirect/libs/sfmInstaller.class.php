<?php
class sfmInstaller {

	public static function SFMgetInstance()
  	{
		  static $instance = NULL;

		  if (is_null($instance)) {
			 $instance = new self();
		  }

		return $instance;
	}
  	function __construct()
  	{
    	global $wpdb;
    	/* add admin notices */
   		add_action('admin_notices', array(&$this,'sfm_activation_msg'));
  	}

   	/* call the installation hook */
    public function sfmInstaller()
    {
        global $wpdb;

        /* check for CURL enabled on server */
        // if(!function_exists('curl_init'))
		// {
		// 	echo '<div class="error"><p> Error: It seems that CURL is disabled on your server. Please contact your server administrator to install / enable CURL.</p></div>'; die;
        // }
        /* install sf_redirect table */

        $sql="CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."sfm_redirects`(
          `rid` int(10) unsigned NOT NULL AUTO_INCREMENT,
		      `feedSetup_url` varchar(255)  NOT NULL,
          `blog_rss` text  NOT NULL,
          `sf_feedid` varchar(255)  NOT NULL,
          `feed_url` text NOT NULL,
          `feed_subUrl` text NOT NULL,
          `id_on_blog` int(10) NOT NULL,
          `feed_type` varchar(255) NOT NULL,
	  	    `verification_code` varchar(255) NOT NULL,
          `redirect_status` int(10) NOT NULL,
           PRIMARY KEY (`rid`),
           UNIQUE KEY `id` (`rid`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
        ";
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($sql);

		add_option('SFM_installDate',date('Y-m-d h:i:s'));
		add_option('SFM_RatingDiv', "no");
		update_option('sfm_activate', 1);
		update_option('SFM_pluginVersion', 3.1);
		update_option('sfm_permalink_structure', get_option('permalink_structure'));

    }
    /* uninstall  plugin  */
    public function sfmUninstaller()
    {
       	global $wpdb;

      	delete_option('sfm_activate');
      	delete_option('sfm_permalink_structure');
		    delete_option('SFM_pluginVersion');
      	$wpdb->query('DROP TABLE IF EXISTS `'.$wpdb->prefix.'sfm_redirects`');
    }
    /* display message on activation */
    public function sfm_activation_msg()
    {
        global $wp_version;

        if(get_option('sfm_activate',false)==1)
        {
            echo "<div class=\"updated\" >" . "<p>Thank you for installing the <b>Feedburner alternative and RSS redirect</b> Plugin. Please go to the <a href=\"admin.php?page=sfm-options-page\">plugin's settings page </a> to configure it. </p></div>"; update_option('sfm_activate',0);
        }
        $path=pathinfo($_SERVER['REQUEST_URI']);
        update_option('sfm_activate',0);

		if($wp_version<3.5 &&  $path['basename']=="admin.php?page=sfm-options-page")
        {
            echo "<div class=\"update-nag\" >" . "<p ><b>You're using an old Wordpress version, which may cause several of your plugins to not work correctly. Please upgrade</b></p></div>";
        }
    }
}
/* end of class */