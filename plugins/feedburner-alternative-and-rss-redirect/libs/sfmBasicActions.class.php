<?php

/* all redirect and admin opiton class */
class sfmBasicActions
{
    public $sfm_server_url="";
    public static function SFMgetInstance()
	{
		static $instance = NULL;

		if (is_null($instance)) {
			$instance = new self();
		}

		return $instance;
	}
   	public  function __construct()
    {
      
		  /* load javascript and css */
     	add_action('admin_init', array(&$this,'sfmAddJqueryAndCss'));
       
     	/* load css to front */
     	add_action('init', array(&$this,'sfmAddJqueryAndCss'));
       
     	/* add admin menu */
     	add_action('admin_menu', array(&$this,'sfmAdminMenus'));
       
     	/* add admin notices */
     	//add_action('admin_notices', array(&$this,'sfm_activation_msg'));
       
     	/* list all active feeds */
     	add_action('admin_init', array(&$this,'sfmListActiveRss'));
       
     	/* register sfm widget  */
     	add_action( 'widgets_init', array(&$this,'register_sfm_widgets'));
       
     	/* load all classes */
      $sfmRedirectObj = new sfmRedirectActions();
	    $sfmShortCode = new sfmShortCodes();
      
    }

    /* create plugin menu in admin */
    public function sfmAdminMenus()
    {
        global $wpdb;
        add_menu_page('RSS Redirect', 'RSS Redirect', 'administrator','sfm-options-page',array(&$this,'sfmAdminView'),SFM_PLUGURL."/images/logo.png");
        add_submenu_page('sfm-options-page', 'RSS Redirect', 'RSS Redirect','administrator', 'sfm-options-page', array(&$this,'sfmAdminView'));

    }

    /* load the admin section view */
    public function sfmAdminView()
    {
		include SFM_DOCROOT . '/views/sfm_admin_view.php';
    }

    /* load admin javascript and CSS */
    public function sfmAddJqueryAndCss()
    {
        global $wp_version;

	  	/* load javascript and css only to plugni page */
        $path=pathinfo($_SERVER['REQUEST_URI']);

		if(is_admin() && $path['basename']=="admin.php?page=sfm-options-page")
		{
			wp_enqueue_script("jQuery");

			wp_register_script('SFM_custom', SFM_PLUGURL . 'js/sfm-custom.js', array('jquery'));
			wp_enqueue_script("SFM_custom");

			/* initilaize the ajax url in javascript */
			wp_localize_script( 'SFM_custom', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		}
    
	   	/* include css */
      wp_enqueue_style("SFMCss", SFM_PLUGURL . 'css/sfm_style.css' );
  		if($wp_version<3.8)
  		{
  		    wp_enqueue_style("SFMCss2", SFM_PLUGURL . 'css/sfm_style3.5.css' );
  		}
  		if(!is_admin())
  		{
  			wp_enqueue_style("SFMCSS", SFM_PLUGURL . 'css/sfm_widgetStyle.css' );
  		}
    }
    
    /* List all RSS links */
    public function sfmListActiveRss()
    {
      
    	/* get the comment feed url */
     	$return_data=array();
     	$comments_link=get_bloginfo('comments_rss2_url');
     	$return_data['comment_url']=$comments_link;
      /* get categoires feed url */
      $cat_argu=array(
  			'type' 		=> 'post',
  			'orderby' 	=> 'name',
  			'order'   	=> 'ASC',
  		);

      $wp_categoires=get_categories($cat_argu);
      $return_data['categoires']=$wp_categoires;
      
      /* get the authors */
      global $wpdb;
      $wp_authors=$wpdb->get_results('select distinct p.post_author,u.user_login from ' . $wpdb->posts . ' p LEFT JOIN ' . $wpdb->users . ' u on p.post_author=u.ID where p.post_status="publish" and p.post_type="post"',ARRAY_A);
      $return_data['authors']=$wp_authors;
      return $return_data;
      
    }
    /* register widget to wordpress */
    public function  register_sfm_widgets()
	{
		register_widget( 'sfmWidget' );
    }

}
/* end of class */