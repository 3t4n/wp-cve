<h1>UpdraftCentral - <?php esc_html_e('Remote Control for WordPress', 'updraftcentral');?></h1>

<?php echo wp_kses($first_link, wp_kses_allowed_html('post'));?> | 
	<a href="https://updraftplus.com/news/"><?php esc_html_e('News', 'updraftcentral');?></a>  | 
	<a href="https://twitter.com/updraftplus"><?php esc_html_e('Twitter', 'updraftcentral');?></a> | 
	<a href="<?php echo esc_attr($support_forum);?>"><?php esc_html_e('Support forum', 'updraftcentral');?></a> | 
	<a href="<?php echo esc_attr($idea_suggestion);?>"><?php esc_html_e('Suggest idea', 'updraftcentral');?></a> | 
	<a href="https://updraftplus.com/newsletter-signup"><?php esc_html_e('Newsletter sign-up', 'updraftcentral');?></a> | 
	<a href="http://david.dw-perspective.org.uk"><?php esc_html_e("Lead developer's homepage", 'updraftcentral');?></a> | 
	<a href="<?php echo esc_attr($faqs);?>">FAQs</a> | <a href="https://www.simbahosting.co.uk/s3/shop/"><?php esc_html_e('More plugins', 'updraftcentral');?></a> - <?php esc_html_e('Version', 'updraftcentral');?>: <?php echo esc_html($updraft_central::VERSION); ?>
	<br>

<div style="width:800px;">

	<h2><?php esc_html_e('Getting started', 'updraftcentral');?></h2>

	<p><?php esc_html_e('Welcome to UpdraftCentral! This is the dashboard plugin, which you install on the site where you want to have your dashboard for controlling other sites. (On the controlled sites, you install UpdraftPlus).', 'updraftcentral');?></p>

	<p><?php echo sprintf(esc_html__('UpdraftCentral runs on the front-end of your site. To get started, you must create a front-end page for your site, to contain the dashboard. i.e. Go to %s.'), '<a href="'.esc_url(admin_url('post-new.php?post_type=page')).'">'.sprintf(esc_html__('Pages %s Add New', 'updraftcentral'), esc_html('->')).'</a>');?></p>

	<p><?php esc_html_e('In your new front-end page, put this shortcode: [updraft_central] . This will allow logged-in site administrators, who visit that page, to use UpdraftCentral.', 'updraftcentral');?></p>
	
	<p><a href="https://updraftplus.com/faqs/can-allow-non-admin-users-updraftcentral-dashboard/"><?php esc_html_e('If you want users with roles to also be able to use UpdraftCentral (note that every user has their own list of sites - giving users access to UpdraftCentral does not give them access to your sites, only to their own list of sites), then please see this FAQ for instructions.', 'updraftcentral');?></a>
	
	<p><?php esc_html_e('Then, to start using UpdraftCentral, simply visit the page, and you can begin adding sites.', 'updraftcentral');?>

</div>
