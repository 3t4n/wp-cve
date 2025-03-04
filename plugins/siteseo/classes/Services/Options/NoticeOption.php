<?php

namespace SiteSEO\Services\Options;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

use SiteSEO\Constants\Options;

class NoticeOption
{
	/**
	 * @since 6.0.0
	 *
	 * @return array
	 */
	public function getOption()
	{
		return get_option(Options::KEY_OPTION_NOTICE);
	}

	/**
	 * @since 6.0.0
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function searchOptionByKey($key)
	{
		$data = $this->getOption();

		if (empty($data)) {
			return null;
		}

		if (! isset($data[$key])) {
			return null;
		}

		return $data[$key];
	}

	/**
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function getNoticeReview(){
		return $this->searchOptionByKey('notice-review');
	}

	/**
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function getNoticeUSM(){
		return $this->searchOptionByKey('notice-usm');
	}

	/**
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function getNoticeWizard(){
		return $this->searchOptionByKey('notice-wizard');
	}

	/**
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function getNoticeSEOConsultant(){
		return $this->searchOptionByKey('notice-seo-consultant');
	}

	/**
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function getNoticeAMPAnalytics(){
		return $this->searchOptionByKey('notice-amp-analytics');
	}

	/**
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function getNoticeTagDiv(){
		return $this->searchOptionByKey('notice-tagdiv');
	}

	/**
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function getNoticeTitleTag(){
		return $this->searchOptionByKey('notice-title-tag');
	}

	/**
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function getNoticeCacheSitemap(){
		return $this->searchOptionByKey('notice-cache-sitemap');
	}

	/**
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function getNoticeSwift(){
		return $this->searchOptionByKey('notice-swift');
	}

	/**
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function getNoticeEnfold(){
		return $this->searchOptionByKey('notice-enfold');
	}

	/**
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function getNoticeSSL(){
		return $this->searchOptionByKey('notice-ssl');
	}

	/**
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function getNoticeNoIndex(){
		return $this->searchOptionByKey('notice-noindex');
	}

	/**
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function getNoticeRSSUseExcerpt(){
		return $this->searchOptionByKey('notice-rss-use-excerpt');
	}

	/**
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function getNoticeGAIds(){
		return $this->searchOptionByKey('notice-ga-ids');
	}

	/**
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function getNoticeDivideComments(){
		return $this->searchOptionByKey('notice-divide-comments');
	}

	/**
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function getNoticePostsNumber(){
		return $this->searchOptionByKey('notice-posts-number');
	}

	/**
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function getNoticeGoogleBusiness(){
		return $this->searchOptionByKey('notice-google-business');
	}

	/**
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function getNoticeSearchConsole(){
		return $this->searchOptionByKey('notice-search-console');
	}

	/**
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function getNoticeGoPro(){
		return $this->searchOptionByKey('notice-go-pro');
	}
}
