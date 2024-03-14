<?php 

require_once( EXPAND_DIVI_PATH . 'inc/functions.php' );

global $html, $wp;

$options = get_option( 'expand_divi' );

$post_url = get_permalink();
$post_title = get_the_title();
$post_image_url = get_the_post_thumbnail_url();

$option_fields = ['share_icons_text', 'facebook_share_icon', 'twitter_share_icon', 'pinterest_share_icon', 'whatsapp_share_icon', 'linkedin_share_icon', 'reddit_share_icon', 'gmail_share_icon', 'email_share_icon'];

foreach ($option_fields as $option_field) {
	isset( $options[$option_field] ) ? $$option_field = $options[$option_field] : $$option_field = '';
}

$html = '<div class="expand_divi_share_icons">';
	if ( ( $share_icons_text !== '' ) ) {
		$html .= '<h4>' . __( esc_html( $share_icons_text ), 'expand-divi' ) . '</h4>';
	}
	$html .= '<ul>';
		if ( ( $facebook_share_icon == 'on' ) ) {
			$html .= '<li class="expand_divi_facebook_icon"><a href="https://www.facebook.com/sharer.php?u=';
			$html .= $post_url;
			$html .= '" target="_blank" rel="external" title="Share on Facebook">Facebook</a></li>';
		}
		if ( ( $twitter_share_icon == 'on' ) ) {
			$html .= '<li class="expand_divi_twitter_icon"><a href="https://twitter.com/intent/tweet?text=';
			$html .= $post_title;
			$html .= '&url=';
			$html .= $post_url;
			$html .= '" target="_blank" rel="external" title="Share on Twitter">Twitter</a></li>';
		}
		if ( expand_divi_is_mobile() && ( $whatsapp_share_icon == 'on' ) ) {
			$html .= '<li class="expand_divi_whatsapp_icon"><a href="whatsapp://send?text=';
			$html .= $post_title;
			$html .= ' ';
			$html .= $post_url;
			$html .= '" target="_blank" rel="external" title="Share on WhatsApp">WhatsApp</a></li>';
		}
		if ( ( $pinterest_share_icon == 'on' ) ) {
			$html .= '<li class="expand_divi_pinterest_icon"><a href="https://pinterest.com/pin/create/button/?url=';
			$html .= $post_url;
			$html .= '&description=';
			$html .= $post_title;
			$html .= '&media=';
			$html .= $post_image_url;
			$html .= '" target="_blank" rel="external" title="Share on Pinterest">Pinterest</a></li>';
		}
		if ( ( $linkedin_share_icon == 'on' ) ) {
			$html .= '<li class="expand_divi_linkedin_icon"><a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=';
			$html .= $post_url;
			$html .= '&amp;title=';
			$html .= $post_title;
			$html .= '" target="_blank" rel="external" title="Share on Linkedin">Linkedin</a></li>';
		}
		if ( ( $reddit_share_icon == 'on' ) ) {
			$html .= '<li class="expand_divi_reddit_icon"><a href="https://www.reddit.com/submit?url=';
			$html .= $post_url;
			$html .= '&title=';
			$html .= $post_title;
			$html .= '" target="_blank" rel="external" title="Share on Reddit">Reddit</a></li>';
		}
		if ( ( $gmail_share_icon == 'on' ) ) {
			$html .= '<li class="expand_divi_gmail_icon"><a href="https://mail.google.com/mail/u/0/?view=cm&fs=1&su=';
			$html .= $post_url;
			$html .= '&body=';
			$html .= $post_title . '. ' . $post_url;
			$html .= '&ui=2&tf=1" target="_blank" rel="external" title="Email to someone">Gmail</a></li>';
		}
		if ( ( $email_share_icon == 'on' ) ) {
			$html .= '<li class="expand_divi_email_icon"><a href="mailto:?Subject=';
			$html .= $post_title;
			$html .= '&amp;Body=';
			$html .= $post_title . '. ' . $post_url;
			$html .= '" target="_blank" rel="external" title="Email to someone">Email</a></li>';
		}
	$html .= '</ul>';
$html .= '</div>';