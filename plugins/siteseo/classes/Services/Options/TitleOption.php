<?php

namespace SiteSEO\Services\Options;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

use SiteSEO\Constants\Options;

class TitleOption {
	/**
	 * @since 4.3.0
	 *
	 * @return array
	 */
	public function getOption() {
		return get_option(Options::KEY_OPTION_TITLE);
	}

	/**
	 * @since 4.3.0
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
	 * @since 4.3.0
	 *
	 * @param string $path
	 *
	 * @return string|null
	 */
	public function getTitlesCptNoIndexByPath($path) {
		$data = $this->searchOptionByKey('titles_archive_titles');

		if ( ! isset($data[$path]['noindex'])) {
			return null;
		}

		return $data[$path]['noindex'];
	}

	/**
	 * @since 4.4.0
	 *
	 * @return string
	 */
	public function getSeparator() {
		$separator = $this->searchOptionByKey('titles_sep');
		if ( ! $separator) {
			return '-';
		}

		return $separator;
	}

	/**
	 * @since 4.4.0
	 *
	 * @return string
	 */
	public function getHomeSiteTitle() {
		return $this->searchOptionByKey('titles_home_site_title');
	}

	/**
	 * @since 4.4.0
	 *
	 * @return string
	 */
	public function getHomeSiteTitleAlt() {
		return $this->searchOptionByKey('titles_home_site_title_alt');
	}

	/**
	 * @since 4.4.0
	 *
	 * @return string
	 */
	public function getHomeDescriptionTitle() {
		return $this->searchOptionByKey('titles_home_site_desc');
	}

	/**
	 * @since 5.0.0
	 *
	 * @param int|null $id
	 */
	public function getSingleCptNoIndex($id = null) {
		$arg = $id;

		if (null === $id) {
			global $post;
			if ( ! isset($post)) {
				return;
			}

			$arg = $post;
		}

		$currentCpt = get_post_type($arg);

		$option =  $this->searchOptionByKey('titles_single_titles');

		if ( ! isset($option[$currentCpt]['noindex'])) {
			return;
		}

		return $option[$currentCpt]['noindex'];
	}

	/**
	 * @since 5.0.0
	 *
	 * @param int|null $id
	 */
	public function getSingleCptNoFollow($id = null) {
		$arg = $id;

		if (null === $id) {
			global $post;
			if ( ! isset($post)) {
				return;
			}

			$arg = $post;
		}

		$currentCpt = get_post_type($arg);

		$option =  $this->searchOptionByKey('titles_single_titles');
		if ( ! isset($option[$currentCpt]['nofollow'])) {
			return;
		}

		return $option[$currentCpt]['nofollow'];
	}

		/**
	 * @since 5.7
	 *
	 * @param int|null $id
	 */
	public function getSingleCptDate($id = null) {
		$arg = $id;

		if (null === $id) {
			global $post;
			if ( ! isset($post)) {
				return;
			}

			$arg = $post;
		}

		$currentCpt = get_post_type($arg);

		$option =  $this->searchOptionByKey('titles_single_titles');

		if ( ! isset($option[$currentCpt]['date'])) {
			return;
		}

		return $option[$currentCpt]['date'];
	}

	/**
	 * @since 5.0.0
	 */
	public function getTitleNoIndex() {
		return $this->searchOptionByKey('titles_noindex');
	}

	/**
	 * @since 5.0.0
	 */
	public function getTitleNoFollow() {
		return $this->searchOptionByKey('titles_nofollow');
	}

	/**
	 * @since 5.0.0
	 */
	public function getTitleNoArchive() {
		return $this->searchOptionByKey('titles_noarchive');
	}

	/**
	 * @since 5.0.0
	 */
	public function getTitleNoSnippet() {
		return $this->searchOptionByKey('titles_nosnippet');
	}

	/**
	 * @since 5.0.0
	 */
	public function getTitleNoImageIndex() {
		return $this->searchOptionByKey('titles_noimageindex');
	}

	/**
	 * @since 5.4.1
	 */
	public function getArchivesAuthorTitle(){
		return $this->searchOptionByKey('titles_archives_author_title');
	}

	/**
	 * @since 5.4.1
	 */
	public function getArchivesAuthorDescription(){
		return $this->searchOptionByKey('titles_archives_author_desc');
	}

	/**
	 * @since 5.4.0
	 */
	public function getTitleArchivesDate(){
		return $this->searchOptionByKey('titles_archives_date_title');
	}

	/**
	 * @since 5.4.0
	 */
	public function getTitleArchivesSearch(){
		return $this->searchOptionByKey('titles_archives_search_title');
	}

	/**
	 * @since 5.4.0
	 */
	public function getTitleArchives404(){
		return $this->searchOptionByKey('titles_archives_404_title');
	}

	/**
	 * @since 5.4.0
	 */
	public function getPagedRel(){
		return $this->searchOptionByKey('titles_paged_rel');
	}

	/**
	 * @since 5.4.0
	 */
	public function getTitleBpGroups(){
		return $this->searchOptionByKey('titles_bp_groups_title');
	}

	/**
	 * @since 6.0.0
	 */
	public function getTitleBpGroupsNoindex(){
		return $this->searchOptionByKey('titles_bp_groups_noindex');
	}

	/**
	 * @since 5.9.0
	 */
	public function getBpGroupsDesc(){
		return $this->searchOptionByKey('titles_bp_groups_desc');
	}

	/**
	 * @since 5.9.0
	 */
	public function getArchivesDateDesc(){
		return $this->searchOptionByKey('titles_archives_date_desc');
	}

	/**
	 * @since 5.9.0
	 */
	public function getArchivesSearchDesc(){
		return $this->searchOptionByKey('titles_archives_search_desc');
	}

	/**
	 * @since 5.9.0
	 */
	public function getArchives404Desc(){
		return $this->searchOptionByKey('titles_archives_404_desc');
	}

	/**
	 * @since 5.9.0
	 */
	public function geNoSiteLinksSearchBox(){
		return $this->searchOptionByKey('titles_nositelinkssearchbox');
	}

	/**
	 * @since 6.0.0
	 */
	public function getArchiveAuthorDisable(){
		return $this->searchOptionByKey('titles_archives_author_disable');
	}

	/**
	 * @since 6.0.0
	 */
	public function getArchiveDateDisable(){
		return $this->searchOptionByKey('titles_archives_date_disable');
	}

	/**
	 * @since 6.0.0
	 */
	public function getArchiveAuthorNoindex(){
		return $this->searchOptionByKey('titles_archives_author_noindex');
	}
}
