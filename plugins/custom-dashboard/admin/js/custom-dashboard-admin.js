jQuery(document).ready(function () {
	
	// For DashBoard Icon Change
	 jQuery('#wpadminbar #wp-admin-bar-comments .ab-item span').removeClass('');
	 jQuery('#adminmenu div.dashicons-dashboard').removeClass('dashicons-before');
	 jQuery('#adminmenu div.dashicons-admin-post').removeClass('dashicons-before');
	 jQuery('#adminmenu div.dashicons-admin-media').removeClass('dashicons-before');
	 jQuery('#adminmenu div.dashicons-admin-page').removeClass('dashicons-before');
	 jQuery('#adminmenu div.dashicons-admin-comments').removeClass('dashicons-before');
	 jQuery('#adminmenu div.dashicons-admin-appearance').removeClass('dashicons-before');
	 jQuery('#adminmenu div.dashicons-admin-plugins').removeClass('dashicons-before');
	 jQuery('#adminmenu div.dashicons-admin-users').removeClass('dashicons-before');
	 jQuery('#adminmenu div.dashicons-admin-tools').removeClass('dashicons-before');
	 jQuery('#adminmenu div.dashicons-admin-settings').removeClass('dashicons-before');
	 jQuery('#adminmenu div.dashicons-admin-generic').removeClass('dashicons-before');
	 
	 jQuery('#wpadminbar #wp-admin-bar-comments .ab-item .ab-icon').addClass('fa fa-comments');
	 jQuery('#adminmenu div.dashicons-dashboard').addClass('fa fa-dashboard');
	 jQuery('#adminmenu div.dashicons-admin-post').addClass('fa fa-edit');
	 jQuery('#adminmenu div.dashicons-admin-media').addClass('fa fa-photo');
	 jQuery('#adminmenu div.dashicons-admin-page').addClass('fa fa-file-o');
	 jQuery('#adminmenu div.dashicons-admin-comments').addClass('fa fa-comments-o');
	 jQuery('#adminmenu div.dashicons-admin-appearance').addClass('fa fa-send-o');
	 jQuery('#adminmenu div.dashicons-admin-plugins').addClass('fa fa-plug');
	 jQuery('#adminmenu div.dashicons-admin-users').addClass('fa fa-user');
	 jQuery('#adminmenu div.dashicons-admin-tools').addClass('fa fa-wrench');
	 jQuery('#adminmenu div.dashicons-admin-settings').addClass('fa fa-gears');
	 jQuery('#adminmenu div.dashicons-admin-generic').addClass('fa fa-paint');	

});
