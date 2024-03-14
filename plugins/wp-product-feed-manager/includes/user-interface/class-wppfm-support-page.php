<?php

/**
 * WPPFM Product Feed Manager Page Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Support_Page' ) ) :

	/**
	 * Option Form Class
	 *
	 * @since        3.2.0
	 */
	class WPPFM_Support_Page {

		private $_plugin_version_mapping = [
			'full'   => 'pfm',
			'google' => 'gfm',
			'free'   => 'glfree',
		];

		/**
		 * Generates the main part of the Support page
		 *
		 * @since 3.2.0
		 *
		 * @return string The html code for the main part of the Support page
		 */
		public function display() {
			$html  = $this->add_data_storage();
			$html .= $this->support_page();

			return $html;
		}

		private function support_page() {
			$html  = '<div class="wppfm-page__title wppfm-center-page__title" id="wppfm-support-title"><h1>' . esc_html__( 'Feed Manager - Support', 'wp-product-feed-manager' ) . '</h1></div>';
			$html .= '</div>';

			// Feed Manager Settings Table
			$html .= '<div class="wppfm-page-layout__main" id="wppfm-feed-manager-support-layout">';
			$html .= '<div class="wppfm-support-wrapper wppfm-auto-center-page-wrapper">';
			$html .= $this->support_content();
			$html .= '</div>';
			$html .= '</div>';

			return $html;
		}

		private function support_content() {
			$html =  $this->getting_started_card();
			$html .= $this->user_guide_card();
			$html .= $this->need_support_card();

			if ( 'free' === WPPFM_PLUGIN_VERSION_ID ) { // The check-in card is only for the free version
				$html .= $this->tips_and_tricks_card();
			}

			$html .= $this->show_your_love_card();
			$html .= $this->documentation_card();
			// $html .= $this->join_our_community_card(); // Switched off until the community funnel is ready
			$html .= $this->request_a_feature_card();

			return $html;
		}

		private function card_header( $header_text ) {
			return
			'<div class="wppfm-support-card__header">
				<h2 class="wppfm-support-card__title">' . $header_text . '</h2>
			</div>';
		}

		private function card_content( $content_html ) {
			return
			'<div class="wppfm-support-card__content">' .
				$content_html .
			'</div>';
		}

		private function card_icon( $icon = null ) {
			if ( null === $icon ) {
				return '';
			}

			return
			'<div class="wppfm-support-card__icon">
				<img src="' . WPPFM_PLUGIN_URL . '/images/' . $icon . '" alt="Icon" />
			</div>';
		}

		private function card_action( $action_text, $action_url ) {
			$card_action_id = 'wppfm-' . str_replace( ' ', '-', strtolower( $action_text ) ) . '-button';

			return
			'<div class="wppfm-support-card__footer">
				<div class="wppfm-inline-button-wrapper">
					<a href="' . $action_url . '" target="_blank" class="wppfm-button wppfm-blue-button" id="' . $card_action_id . '">' . $action_text . '</a>
				</div>
			</div>';
		}

		private function getting_started_card() {
			$content_html  = '<p>' . esc_html__( 'Getting started with your WooCommerce Product Feed Manager is easier than you could imagine. All our customers are not feed marketeers and we want to make your life easier.', 'wp-product-feed-manager' ) . '</p>';
			$content_html .= '<div class="wppfm-video-wrapper">';
			$content_html .= '<iframe class="wppfm-youtube-element" id="wppfm-getting-started-video" src="https://www.youtube.com/embed/68v63Q9jhIw"></iframe>';
			$content_html .= '</div>';

			$html  = '<div class="wppfm-support-card" id="wppfm-getting-started-support-card">';
			$html .= $this->card_header( esc_html__( 'Getting Started With Product Feed Manager', 'wp-product-feed-manager' ) );
			$html .= $this->card_content( $content_html );
			$html .= '</div>';

			return $html;
		}

		private function user_guide_card() {
			$content_html  = '<p>' . esc_html__( 'Please check the following articles to get started with your WooCommerce Product Feed Manager.', 'wp-product-feed-manager' ) . '</p>';
			$content_html .= '<ul>';
			$content_html .= '<li><a href="' . WPPFM_EDD_SL_STORE_URL . 'help-item/getting-started/create-a-basic-product-feed/?utm_source=pl_sup_tab&utm_medium=textlink&utm_campaign=help_items&utm_id=PST.14224" target="_blank">' . esc_html__( 'Create a basic product feed', 'wp-product-feed-manager' ) . '</a></li>';
			$content_html .= '<li><a href="' . WPPFM_EDD_SL_STORE_URL . 'help-item/using-product-feed-manager/navigating-your-plugin-settings-dashboard/?utm_source=pl_sup_tab&utm_medium=textlink&utm_campaign=help_items&utm_id=PST.14224" target="_blank">' . esc_html__( 'Navigating your plugin settings dashboard', 'wp-product-feed-manager' ) . '</a></li>';
			$content_html .= '<li><a href="' . WPPFM_EDD_SL_STORE_URL . 'help-item/using-product-feed-manager/category-mapping-in-your-product-feed/?utm_source=pl_sup_tab&utm_medium=textlink&utm_campaign=help_items&utm_id=PST.14224" target="_blank">' . esc_html__( 'Category mapping', 'wp-product-feed-manager' ) . '</a></li>';
			$content_html .= '<li><a href="' . WPPFM_EDD_SL_STORE_URL . 'help-item/using-product-feed-manager/adding-data-to-a-product-feed-attribute/?utm_source=pl_sup_tab&utm_medium=textlink&utm_campaign=help_items&utm_id=PST.14224" target="_blank">' . esc_html__( 'Adding data to a product feed attribute', 'wp-product-feed-manager' ) . '</a></li>';
			$content_html .= '<li><a href="' . WPPFM_EDD_SL_STORE_URL . 'help-item/using-product-feed-manager/using-the-unique-product-identifier-function/?utm_source=pl_sup_tab&utm_medium=textlink&utm_campaign=help_items&utm_id=PST.14224" target="_blank">' . esc_html__( 'Using the unique product identifier function', 'wp-product-feed-manager' ) . '</a></li>';
			$content_html .= '<li><a href="' . WPPFM_EDD_SL_STORE_URL . 'help-item/using-product-feed-manager/how-to-set-automatic-feed-updates/?utm_source=pl_sup_tab&utm_medium=textlink&utm_campaign=help_items&utm_id=PST.14224" target="_blank">' . esc_html__( 'How to set automatic feed updates', 'wp-product-feed-manager' ) . '</a></li>';
			$content_html .= '<li><a href="' . WPPFM_EDD_SL_STORE_URL . 'help-item/using-product-feed-manager/how-to-use-data-from-unsupported-plugins-in-the-feed/?utm_source=pl_sup_tab&utm_medium=textlink&utm_campaign=help_items&utm_id=PST.14224" target="_blank">' . esc_html__( 'How to use data from unsupported plugins in the feed', 'wp-product-feed-manager' ) . '</a></li>';
			$content_html .= '<li><a href="' . WPPFM_EDD_SL_STORE_URL . 'help-item/using-product-feed-manager/how-to-create-repeating-fields-in-sub-attributes/?utm_source=pl_sup_tab&utm_medium=textlink&utm_campaign=help_items&utm_id=PST.14224" target="_blank">' . esc_html__( 'How to create repeating fields in sub-attributes', 'wp-product-feed-manager' ) . '</a></li>';
			$content_html .= '<li><a href="' . WPPFM_EDD_SL_STORE_URL . 'help-item/using-product-feed-manager/how-to-use-the-advanced-product-filter/?utm_source=pl_sup_tab&utm_medium=textlink&utm_campaign=help_items&utm_id=PST.14224" target="_blank">' . esc_html__( 'How to use the advanced product filter', 'wp-product-feed-manager' ) . '</a></li>';
			$content_html .= '<li><a href="' . WPPFM_EDD_SL_STORE_URL . 'help-item/using-product-feed-manager/the-channel-manager-explained/?utm_source=pl_sup_tab&utm_medium=textlink&utm_campaign=help_items&utm_id=PST.14224" target="_blank">' . esc_html__( 'The Channel Manager explained', 'wp-product-feed-manager' ) . '</a></li>';
			$content_html .= '<li><a href="' . WPPFM_EDD_SL_STORE_URL . 'help-item/faq/?utm_source=pl_sup_tab&utm_medium=textlink&utm_campaign=help_items&utm_id=PST.14224" target="_blank">' . esc_html__( 'Frequently asked questions', 'wp-product-feed-manager' ) . '</a></li>';
			$content_html .= '</ul>';

			$html  = '<div class="wppfm-support-card" id="wppfm-user-guide-support-card">';
			$html .= $this->card_header( esc_html__( 'User Guide', 'wp-product-feed-manager' ) );
			$html .= $this->card_content( $content_html );
			$html .= '</div>';

			return $html;
		}

		private function need_support_card() {
			$content_html = '<p>' . esc_html__( 'Our Experts would like to assist you with your query and any help you need.', 'wp-product-feed-manager' ) . '</p>';

			$html  = '<div class="wppfm-support-card" id="wppfm-need-support-support-card">';
			$html .= $this->card_icon( 'support.png' );
			$html .= $this->card_header( esc_html__( 'Need Expert Support?', 'wp-product-feed-manager' ) );
			$html .= $this->card_content( $content_html );
			$html .= $this->card_action( esc_html__( 'Contact Support', 'wp-product-feed-manager' ), WPPFM_EDD_SL_STORE_URL . 'support/?ref=' . $this->_plugin_version_mapping[WPPFM_PLUGIN_VERSION_ID] . '&utm_source=pl_sup_tab&utm_medium=textlink&utm_campaign=support_request&utm_id=PST.14225' );
			$html .= '</div>';

			return $html;
		}

		private function tips_and_tricks_card() {
			$action_text = esc_html__( 'Sign Up Now', 'wp-product-feed-manager' );

			$content_html  = '<div class="wppfm-support-card__footer">';
			$content_html .= '<p>' . esc_html__( 'Learn how to get the most out of your WooCommerce Product Feed Manager and your online Marketing with the many tips, tricks and news in our news letter.', 'wp-product-feed-manager' ) . '</p>';
			$content_html .= '<p><input name="wppfm-subscription-address" class="wppfm-support-card-input-field" id="wppfm-sign-up-mail-input" type="email" placeholder="Your Email"></p>';
			$content_html .= '<div class="wppfm-inline-button-wrapper" id="wppfm-sign-up-button-wrapper">';
			$content_html .= '<a href="#" class="wppfm-button wppfm-blue-button" id="wppfm-sign-up-button">' . $action_text . '</a>';
			$content_html .= '</div>';
			$content_html .= '</div>';

			$html  = '<div class="wppfm-support-card wppfm-action-card" id="wppfm-sign-up-support-card">';
			$html .= $this->card_header( esc_html__( 'Get Tips And Tricks In Your Mailbox', 'wp-product-feed-manager' ) );
			$html .= $this->card_content( $content_html );
			$html .= '</div>';

			return $html;
		}

		private function show_your_love_card() {
			$content_html = '<p>' . esc_html__( 'We need your help to keep developing the plugin. Please review it and spread the love to keep us motivated.', 'wp-product-feed-manager' ) . '</p>';

			$html  = '<div class="wppfm-support-card" id="wppfm-need-support-support-card">';
			$html .= $this->card_icon( 'love.png' );
			$html .= $this->card_header( esc_html__( 'Show Your Love', 'wp-product-feed-manager' ) );
			$html .= $this->card_content( $content_html );
			$html .= $this->card_action( esc_html__( 'Leave a Review', 'wp-product-feed-manager' ), 'https://wordpress.org/support/plugin/wp-product-feed-manager/reviews/#new-post' );
			$html .= '</div>';

			return $html;
		}

		private function documentation_card() {
			$content_html = '<p>' . esc_html__( 'Get detailed and guided instructions to level up your feeds with the necessary setup.', 'wp-product-feed-manager' ) . '</p>';

			$html  = '<div class="wppfm-support-card" id="wppfm-need-support-support-card">';
			$html .= $this->card_icon( 'documentation.png' );
			$html .= $this->card_header( esc_html__( 'Documentation', 'wp-product-feed-manager' ) );
			$html .= $this->card_content( $content_html );
			$html .= $this->card_action( esc_html__( 'Visit Documentation', 'wp-product-feed-manager' ), WPPFM_EDD_SL_STORE_URL . 'help-center/?utm_source=pl_sup_tab&utm_medium=textlink&utm_campaign=help_center&utm_id=PST.14226' );
			$html .= '</div>';

			return $html;
		}

		private function join_our_community_card() {
			$content_html = '<p>' . esc_html__( 'We have a strong community where we discuss ideas and help each other.', 'wp-product-feed-manager' ) . '</p>';

			$html  = '<div class="wppfm-support-card" id="wppfm-need-support-support-card">';
			$html .= $this->card_icon( 'community.png' );
			$html .= $this->card_header( esc_html__( 'Join Our Community', 'wp-product-feed-manager' ) );
			$html .= $this->card_content( $content_html );
			$html .= $this->card_action( esc_html__( 'Join Community', 'wp-product-feed-manager' ), 'https://www.wpproductfeedmanager.com/documentation/' );
			$html .= '</div>';

			return $html;
		}

		private function request_a_feature_card() {
			$content_html = '<p>' . esc_html__( 'If you need any feature on your WooCommerce Product Feed Manager that we currently do not have, please send us a request with your wishes and requirements.', 'wp-product-feed-manager' ) . '</p>';

			$html  = '<div class="wppfm-support-card" id="wppfm-need-support-support-card">';
			$html .= $this->card_icon( 'request.png' );
			$html .= $this->card_header( esc_html__( 'Request a Feature', 'wp-product-feed-manager' ) );
			$html .= $this->card_content( $content_html );
			$html .= $this->card_action( esc_html__( 'Request a Feature', 'wp-product-feed-manager' ), WPPFM_EDD_SL_STORE_URL . 'help-center/feature-request/?utm_source=pl_sup_tab&utm_medium=textlink&utm_campaign=feature_request&utm_id=PST.14227' );
			$html .= '</div>';

			return $html;
		}

		/**
		 * Stores data in the DOM for the Feed Manager Settings page
		 *
		 * @return string The html code for the data storage
		 */
		private function add_data_storage() {
			$current_user = wp_get_current_user();

			return
				'<div id="wppfm-support-page-data-storage" class="wppfm-data-storage-element" 
				data-wppfm-username="' . $current_user->user_login . '"
				data-wppfm-plugin-version-id="' . WPPFM_PLUGIN_VERSION_ID . '" 
				data-wppfm-plugin-version-nr="' . WPPFM_VERSION_NUM . '">
			</div>';
		}
	}

	// end of WPPFM_Support_Page class

endif;
