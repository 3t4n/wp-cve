<?php

namespace SiteSEO\Services\Options;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

use SiteSEO\Constants\Options;

class SocialOption
{
	/**
	 * @since 4.5.0
	 *
	 * @return array
	 */
	public function getOption() {
		return get_option(Options::KEY_OPTION_SOCIAL);
	}

	/**
	 * @since 4.5.0
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function searchOptionByKey($key) {
		$data = $this->getOption();

		if (empty($data)) {
			return null;
		}

		if ( ! isset($data[$key])) {
			return null;
		}

		return $data[$key];
	}

	/**
	 * @since 4.5.0
	 *
	 * @return string
	 */
	public function getSocialKnowledgeType() {
		return $this->searchOptionByKey('social_knowledge_type');
	}

	/**
	 * @since 4.5.0
	 *
	 * @return string
	 */
	public function getSocialKnowledgeName() {
		return $this->searchOptionByKey('social_knowledge_name');
	}

	/**
	 * @since 4.5.0
	 *
	 * @return string
	 */
	public function getSocialAccountsFacebook() {
		return $this->searchOptionByKey('social_accounts_facebook');
	}

	/**
	 * @since 4.5.0
	 *
	 * @return string
	 */
	public function getSocialAccountsTwitter() {
		return $this->searchOptionByKey('social_accounts_twitter');
	}

	/**
	 * @since 4.5.0
	 *
	 * @return string
	 */
	public function getSocialAccountsPinterest() {
		return $this->searchOptionByKey('social_accounts_pinterest');
	}

	/**
	 * @since 4.5.0
	 *
	 * @return string
	 */
	public function getSocialAccountsInstagram() {
		return $this->searchOptionByKey('social_accounts_instagram');
	}

	/**
	 * @since 4.5.0
	 *
	 * @return string
	 */
	public function getSocialAccountsYoutube() {
		return $this->searchOptionByKey('social_accounts_youtube');
	}

	/**
	 * @since 4.5.0
	 *
	 * @return string
	 */
	public function getSocialAccountsLinkedin() {
		return $this->searchOptionByKey('social_accounts_linkedin');
	}

	/**
	 * @since 4.5.0
	 *
	 * @return string
	 */
	public function getSocialKnowledgeImage() {
		return $this->searchOptionByKey('social_knowledge_img');
	}

	/**
	 * @since 4.5.0
	 *
	 * @return string
	 */
	public function getSocialKnowledgePhone() {
		return $this->searchOptionByKey('social_knowledge_phone');
	}

	/**
	 * @since 4.5.0
	 *
	 * @return string
	 */
	public function getSocialKnowledgeContactType() {
		return $this->searchOptionByKey('social_knowledge_contact_type');
	}

	/**
	 * @since 4.5.0
	 *
	 * @return string
	 */
	public function getSocialKnowledgeContactOption() {
		return $this->searchOptionByKey('social_knowledge_contact_option');
	}

	/**
	 * @since 5.9.0
	 *
	 * @return string
	 */
	public function getSocialTwitterCard() {
		return $this->searchOptionByKey('social_twitter_card');
	}

	/**
	 * @since 5.9.0
	 *
	 * @return string
	 */
	public function getSocialTwitterCardOg() {
		return $this->searchOptionByKey('social_twitter_card_og');
	}

	/**
	 * @since 6.2
	 *
	 * @return string
	 */
	public function getSocialTwitterImg() {
		return $this->searchOptionByKey('social_twitter_card_img');
	}

	/**
	 * @since 5.9.0
	 *
	 * @return string
	 */
	public function getSocialTwitterImgSize() {
		return $this->searchOptionByKey('social_twitter_card_img_size');
	}


	/**
	 * @since 5.9.0
	 *
	 * @return string
	 */
	public function getSocialFacebookImgDefault() {
		return $this->searchOptionByKey('social_facebook_img_default');
	}

	/**
	 * @since 5.9.0
	 *
	 * @return string
	 */
	public function getSocialFacebookImg() {
		return $this->searchOptionByKey('social_facebook_img');
	}

	/**
	 * @since 5.9.0
	 *
	 * @return string
	 */
	public function getSocialFacebookOg() {
		return $this->searchOptionByKey('social_facebook_og');
	}

	/**
	 * @since 5.9.0
	 *
	 * @return string
	 */
	public function getSocialFacebookImgCPT(){
		
		$get_current_cpt = get_post_type();
		$options = $this->searchOptionByKey('social_facebook_img_cpt');
		
		if(empty($options) || !isset($options[$get_current_cpt]['url'])){
			return null;
		}
		
		return $options[$get_current_cpt]['url'];
	}

}
