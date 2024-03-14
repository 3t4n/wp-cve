<?php

defined( 'ABSPATH' ) || die();

class Cookie_Notice_Consent_Embeds {

	/**
	 * Constructor
	 */
	public function __construct( $instance ) {
		$this->cnc = $instance;
		// Add actions in init, since settings need to be loaded earlier
		add_action( 'init', array( $this, 'init_embed_block' ) );
	}
	
	/**
	 * Maybe add oembed html filter
	 */
	public function init_embed_block() {
		if( $this->cnc->settings->get_option( 'general_settings', 'block_embeds' ) )
			add_filter( 'embed_oembed_html', array( $this, 'block_embed' ), 99, 4 );
	}
	
	/**
	 * Replace embed with placeholder if embeds are blocked
	 */
	public function block_embed( $cached_html, $url, $atts, $post_id ) {
		if( ! $this->cnc->helper->is_cookie_consent_set() || ! $this->cnc->helper->is_cookie_category_accepted( 'functional' ) ) {
			$privacy_policy_tag_start = '';
			$privacy_policy_tag_end = '';
			if( function_exists( 'get_privacy_policy_url' ) && ! empty( $privacy_policy_url = get_privacy_policy_url() ) ) {
				$privacy_policy_tag_start = '<a href="' . $privacy_policy_url . '">';
				$privacy_policy_tag_end = '</a>';
			}
			$embed_content = htmlspecialchars( $cached_html, ENT_QUOTES );
			$embed_provider = parse_url( $url )['host'];
			ob_start();
			?>
			<div class="cookie-notice-consent__embed cookie-notice-consent__embed-placeholder">
				<p class="cookie-notice-consent__embed-blocked-notice">
					<?php echo sprintf( esc_html__( '%1$sThis external content from %3$s%2$s has been blocked due to your cookie settings. By loading this embedded content, you agree to our %4$sPrivacy Policy%5$s.', 'cookie-notice-consent' ),
					'<a href="' . $url . '">',
					'</a>',
					$embed_provider,
					$privacy_policy_tag_start,
					$privacy_policy_tag_end
				); ?></p>
				<a role="button" tabindex="0" class="cookie-notice-consent__embed-unblock" data-embed-content="<?php echo $embed_content; ?>" data-embed-provider="<?php echo $embed_provider; ?>"><?php _e( 'Show external content', 'cookie-notice-consent' ); ?></a>
			</div>
			<?php
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
		return $cached_html;
	}
	
}