<?php

class CRP_Overview_Page
{

    public function __construct()
    {
//        add_action( 'admin_menu', array( $this, 'register_page' ) );
    }

    public function register_page()
    {
        add_menu_page( 'Custom Related Posts', 'Related Posts', 'manage_options', 'custom_related_posts', array( $this, 'page_content' ), 'dashicons-admin-links' , '22.9'  );
    }

	public function page_content()
	{
		echo 'yo';
	}

}