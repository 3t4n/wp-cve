<?php
add_action('admin_notices', 'sfm_admin_ratingNotice', 10);
function sfm_admin_ratingNotice()
{
	global $wp_version;

	if(isset($_GET['page']) && $_GET['page'] == "sfm-options-page")
	{
		$install_date = get_option('SFM_installDate');
		$display_date = date('Y-m-d h:i:s');
		$datetime1 = new DateTime($install_date);
		$datetime2 = new DateTime($display_date);
		$diff_inrval = round(($datetime2->format('U') - $datetime1->format('U')) / (60*60*24));

		if($diff_inrval >= 30 && get_option('SFM_RatingDiv')=="no")
		{
			$reviewUrl = "https://wordpress.org/support/view/plugin-reviews/feedburner-alternative-and-rss-redirect";
			$dismissUrl = "?sfm-dismiss-ratingNotice=true";

			echo '<div class="updated">
				<p>
					We noticed you\'ve been using the RSS redirect plugin for more than 30 days. If you\'re happy with it, could you please do us a BIG favor and give it a 5-star rating on Wordpress?
				</p>
				<ul>
					<li><a href="'.$reviewUrl.'" target="_new" title="Ok, you deserved it">Ok, you deserved it</a></li>
					<li><a href="'.$dismissUrl.'" title="I already did">I already did</a></li>
					<li><a href="'.$dismissUrl.'" title="No, not good enough">No, not good enough</a></li>
				</ul>
			</div>';
		}
	}
}

add_action('admin_init', 'sfm_dismiss_admin_ratingNotice');
function sfm_dismiss_admin_ratingNotice()
{
	if ( isset($_REQUEST['sfm-dismiss-ratingNotice']) && $_REQUEST['sfm-dismiss-ratingNotice'] == 'true' )
	{
		update_option( 'SFM_RatingDiv', "yes" );
		header("Location: ".site_url()."/wp-admin/admin.php?page=sfm-options-page");
	}
}
