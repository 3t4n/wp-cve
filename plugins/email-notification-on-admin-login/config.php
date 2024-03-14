<?php
# Start a session if not already started
# so that our $_SESSION variables would work
	if(session_id() == ''){
		session_start();
	}
# Gets the current domain
	$website_name =  get_bloginfo("wpurl");
# Gets the admin email
	$admin_email =  get_option("admin_email"); 
# Edit the below constant only if you want to change the email address the 
# notification would be sent to
	define("ADMIN_EMAIL", "$admin_email");
# ♫ This is where the story ends, this is goodbye ♫
# EOF  