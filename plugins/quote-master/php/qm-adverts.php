<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function qm_adverts()
{
  wp_enqueue_style( 'qm_adverts_style', plugins_url( '../css/adverts.css' , __FILE__ ) );
	$advert = "";
	$advert_text = "";
	if ( get_option('mlw_advert_shows') == 'true' )
	{
		$random_int = rand(0, 5);
		switch ($random_int) {
			case 0:
				$advert_text = "Need support or features? Check out our Premium Support options! Visit our <a class='help_decide_link' href=\"http://mylocalwebstop.com/shop/\">WordPress Store</a> for details!";
				break;
			case 1:
				$advert_text = "Is Quote Master beneficial to your website? Please help by giving us a review on WordPress.org by going <a class='help_decide_link' href=\"http://wordpress.org/support/view/plugin-reviews/quote-master\">here</a>!";
				break;
			case 2:
				$advert_text = "Want help installing and configuring one of our plugins? Check out our Plugin Installation services. Visit our <a class='help_decide_link' href=\"http://mylocalwebstop.com/shop/\">WordPress Store</a> for details!";
				break;
			case 3:
				$advert_text = "Would you like to support this plugin but do not need or want premium support? Please consider our inexpensive 'Advertisements Be Gone' add-on which will get rid of these ads. Visit our <a class='help_decide_link' href=\"http://mylocalwebstop.com/shop/\">Plugin Add-On Store</a> for details!";
				break;
			case 4:
				$advert_text = "Need help keeping your plugins, themes, and WordPress up to date? Want around the clock security monitoring and off-site back-ups? How about WordPress training videos, a monthly status report, and support/consultation? Check out our <a class='help_decide_link' href=\"http://mylocalwebstop.com/wordpress-maintenance-services/\">WordPress Maintenance Services</a> for more details!";
				break;
			case 5:
				$advert_text = "Setting up a new site? Let us take care of the set-up so you back to running your business. Check out our <a class='help_decide_link' href=\"http://mylocalwebstop.com/shop/\">WordPress Store</a> for more details!";
				break;
			default:
				$advert_text = "Need support or features? Check out our Premium Support options! Visit our <a class='help_decide_link' href=\"http://mylocalwebstop.com/shop/\">Plugin Add-On Store</a> for details!";
		}
		$advert .= "
			<div class=\"help_decide\">
			<p>$advert_text</p>
			</div>";
	}
	return $advert;
}
?>
