<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EU_COOKIES_BAR_Data {
	private $params;
	protected static $instance = null;

	/**
	 * EU_COOKIES_BAR_Data constructor.
	 * Init setting
	 */
	public function __construct() {
		global $eu_cookies_bar_settings;
		if ( ! $eu_cookies_bar_settings ) {
			$eu_cookies_bar_settings = get_option( 'eu_cookies_bar_params', array() );
		}
		$this->params = $eu_cookies_bar_settings;
		$privacy_page = get_option( 'wp_page_for_privacy_policy', '' );
		$privacy      = '';

		if ( (int) $privacy_page > 0 ) {
			$post = get_post( $privacy_page );
			if ( $post ) {
				$privacy = $post->post_content;
			}
		} else {
			$privacy = '<header>
<h3>' . esc_html__( 'Privacy Policy', 'eu-cookies-bar' ) . '</h3>
</header>
<div>
<h4>' . esc_html__( 'What information do we collect?', 'eu-cookies-bar' ) . '</h4>
' . esc_html__( 'We collect information from you when you register on our site or place an order.

When ordering or registering on our site, as appropriate, you may be asked to enter your: name, e-mail address or mailing address.', 'eu-cookies-bar' ) . '
<h4>' . esc_html__( 'What do we use your information for?', 'eu-cookies-bar' ) . '</h4>
' . esc_html__( 'Any of the information we collect from you may be used in one of the following ways:

To personalize your experience
(your information helps us to better respond to your individual needs)
To improve our website
(we continually strive to improve our website offerings based on the information and feedback we receive from you)
To improve customer service
(your information helps us to more effectively respond to your customer service requests and support needs)

To process transactions
Your information, whether public or private, will not be sold, exchanged, transferred, or given to any other company for any reason whatsoever, without your consent, other than for the express purpose of delivering the purchased product or service requested.

To administer a contest, promotion, survey or other site feature

To send periodic emails
The email address you provide for order processing, will only be used to send you information and updates pertaining to your order.', 'eu-cookies-bar' ) . '
<h4>' . esc_html__( 'How do we protect your information?', 'eu-cookies-bar' ) . '</h4>
' . esc_html__( 'We implement a variety of security measures to maintain the safety of your personal information when you place an order or enter, submit, or access your personal information.

We offer the use of a secure server. All supplied sensitive/credit information is transmitted via Secure Socket Layer (SSL) technology and then encrypted into our Payment gateway providers database only to be accessible by those authorized with special access rights to such systems, and are required to?keep the information confidential.

After a transaction, your private information (credit cards, social security numbers, financials, etc.) will not be kept on file for more than 60 days.', 'eu-cookies-bar' ) . '
<h4>' . esc_html__( 'Do we use cookies?', 'eu-cookies-bar' ) . '</h4>
' . esc_html__( 'Yes (Cookies are small files that a site or its service provider transfers to your computers hard drive through your Web browser (if you allow) that enables the sites or service providers systems to recognize your browser and capture and remember certain information

We use cookies to help us remember and process the items in your shopping cart, understand and save your preferences for future visits, keep track of advertisements and compile aggregate data about site traffic and site interaction so that we can offer better site experiences and tools in the future. We may contract with third-party service providers to assist us in better understanding our site visitors. These service providers are not permitted to use the information collected on our behalf except to help us conduct and improve our business.

If you prefer, you can choose to have your computer warn you each time a cookie is being sent, or you can choose to turn off all cookies via your browser settings. Like most websites, if you turn your cookies off, some of our services may not function properly. However, you can still place orders by contacting customer service.', 'eu-cookies-bar' ) . '

<strong>' . esc_html__( 'Google Analytics', 'eu-cookies-bar' ) . '</strong>

' . esc_html__( 'We use Google Analytics on our sites for anonymous reporting of site usage and for advertising on the site. If you would like to opt-out of Google Analytics monitoring your behaviour on our sites please use this link', 'eu-cookies-bar' ) . ' (<a href="https://tools.google.com/dlpage/gaoptout/">https://tools.google.com/dlpage/gaoptout/</a>)
<h4>' . esc_html__( 'Do we disclose any information to outside parties?', 'eu-cookies-bar' ) . '</h4>
' . esc_html__( 'We do not sell, trade, or otherwise transfer to outside parties your personally identifiable information. This does not include trusted third parties who assist us in operating our website, conducting our business, or servicing you, so long as those parties agree to keep this information confidential. We may also release your information when we believe release is appropriate to comply with the law, enforce our site policies, or protect ours or others rights, property, or safety. However, non-personally identifiable visitor information may be provided to other parties for marketing, advertising, or other uses.', 'eu-cookies-bar' ) . '
<h4>' . esc_html__( 'Registration', 'eu-cookies-bar' ) . '</h4>
' . esc_html__( 'The minimum information we need to register you is your name, email address and a password. We will ask you more questions for different services, including sales promotions. Unless we say otherwise, you have to answer all the registration questions.

We may also ask some other, voluntary questions during registration for certain services (for example, professional networks) so we can gain a clearer understanding of who you are. This also allows us to personalise services for you.

To assist us in our marketing, in addition to the data that you provide to us if you register, we may also obtain data from trusted third parties to help us understand what you might be interested in. This ‘profiling’ information is produced from a variety of sources, including publicly available data (such as the electoral roll) or from sources such as surveys and polls where you have given your permission for your data to be shared. You can choose not to have such data shared with the Guardian from these sources by logging into your account and changing the settings in the privacy section.

After you have registered, and with your permission, we may send you emails we think may interest you. Newsletters may be personalised based on what you have been reading on theguardian.com. At any time you can decide not to receive these emails and will be able to ‘unsubscribe’.', 'eu-cookies-bar' ) . '

<strong>' . esc_html__( 'Logging in using social networking credentials', 'eu-cookies-bar' ) . '</strong>

' . esc_html__( 'If you log-in to our sites using a Facebook log-in, you are granting permission to Facebook to share your user details with us. This will include your name, email address, date of birth and location which will then be used to form a Guardian identity. You can also use your picture from Facebook as part of your profile. This will also allow us and Facebook to share your, networks, user ID and any other information you choose to share according to your Facebook account settings. If you remove the Guardian app from your Facebook settings, we will no longer have access to this information.

If you log-in to our sites using a Google log-in, you grant permission to Google to share your user details with us. This will include your name, email address, date of birth, sex and location which we will then use to form a Guardian identity. You may use your picture from Google as part of your profile. This also allows us to share your networks, user ID and any other information you choose to share according to your Google account settings. If you remove the Guardian from your Google settings, we will no longer have access to this information.

If you log-in to our sites using a twitter log-in, we receive your avatar (the small picture that appears next to your tweets) and twitter username.', 'eu-cookies-bar' ) . '
<h4>' . esc_html__( 'Children’s Online Privacy Protection Act Compliance', 'eu-cookies-bar' ) . '</h4>
' . esc_html__( 'We are in compliance with the requirements of COPPA (Childrens Online Privacy Protection Act), we do not collect any information from anyone under 13 years of age. Our website, products and services are all directed to people who are at least 13 years old or older.', 'eu-cookies-bar' ) . '
<h4>' . esc_html__( 'Updating your personal information', 'eu-cookies-bar' ) . '</h4>
' . esc_html__( 'We offer a ‘My details’ page (also known as Dashboard), where you can update your personal information at any time, and change your marketing preferences. You can get to this page from most pages on the site – simply click on the ‘My details’ link at the top of the screen when you are signed in.', 'eu-cookies-bar' ) . '
<h4>' . esc_html__( 'Online Privacy Policy Only', 'eu-cookies-bar' ) . '</h4>
' . esc_html__( 'This online privacy policy applies only to information collected through our website and not to information collected offline.', 'eu-cookies-bar' ) . '
<h4>' . esc_html__( 'Your Consent', 'eu-cookies-bar' ) . '</h4>
' . esc_html__( 'By using our site, you consent to our privacy policy.', 'eu-cookies-bar' ) . '
<h4>' . esc_html__( 'Changes to our Privacy Policy', 'eu-cookies-bar' ) . '</h4>
' . esc_html__( 'If we decide to change our privacy policy, we will post those changes on this page.', 'eu-cookies-bar' ) . '

</div>';
		}
		$args         = array(
			'enable'                                     => '1',
			'expire'                                     => '180',
			'privacy_policy'                             => $privacy,
			'privacy_policy_url'                         => '',
			'strictly_necessary'                         => 'wordpress_test_cookie,woocommerce_cart_hash',
			'strictly_necessary_family'                  => 'PHPSESSID,wordpress_sec_,wp-settings-,wordpress_logged_in_,wp_woocommerce_session_',
			'cookies_bar_message'                        => 'We use cookies to personalise content and ads, to provide social media features and to analyse our traffic. We also share information about your use of our site with our social media, advertising and analytics partners.',
			'cookies_bar_position'                       => 'bottom',
			'cookies_bar_show_button_accept'             => '1',
			'cookies_bar_button_accept_title'            => esc_html__( 'Accept', 'eu-cookies-bar' ),
			'cookies_bar_button_accept_color'            => '#ffffff',
			'cookies_bar_button_accept_bg_color'         => '#0ec50e',
			'cookies_bar_button_accept_border_radius'    => 0,
			'cookies_bar_show_button_decline'            => '',
			'cookies_bar_button_decline_title'           => esc_html__( 'Decline', 'eu-cookies-bar' ),
			'cookies_bar_button_decline_color'           => '#ffffff',
			'cookies_bar_button_decline_bg_color'        => '#ff6666',
			'cookies_bar_button_decline_border_radius'   => 0,
			'cookies_bar_show_button_close'              => '',
			'cookies_bar_on_close'                       => 'none',
			'cookies_bar_on_scroll'                      => 'none',
			'cookies_bar_on_page_redirect'               => 'none',
			'cookies_bar_font_size'                      => '14',
			'cookies_bar_color'                          => '#ffffff',
			'cookies_bar_bg_color'                       => '#000000',
			'cookies_bar_opacity'                        => '0.7',
			'cookies_bar_border_radius'                  => 0,
			'cookies_bar_padding'                        => 0,
			'cookies_bar_margin'                         => 0,
			'user_cookies_settings_enable'               => '1',
			'user_cookies_settings_heading_title'        => 'Privacy & Cookie policy',
			'user_cookies_settings_heading_color'        => '#ffffff',
			'user_cookies_settings_heading_bg_color'     => '#249fd0',
			'user_cookies_settings_button_save_color'    => '#ffffff',
			'user_cookies_settings_button_save_bg_color' => '#249fd0',
			'user_cookies_settings_bar_position'         => 'right',
			'custom_css'                                 => '',
			'block_until_accept'                         => '',
		);
		$this->params = apply_filters( 'eu_cookies_bar_params', wp_parse_args( $this->params, $args ) );
	}

	/**
	 * Get add to cart redirect
	 * @return mixed|void
	 */
	public function get_params( $name = '' ) {
		return isset( $this->params[ $name ] ) ? apply_filters( 'eu_cookies_bar_get_' . $name, $this->params[ $name ] ) : false;
	}

	public static function get_instance( $new = false ) {
		if ( $new || null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}