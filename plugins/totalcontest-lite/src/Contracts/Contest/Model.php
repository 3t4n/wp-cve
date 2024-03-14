<?php

namespace TotalContest\Contracts\Contest;

use TotalContest\Form\ParticipateForm;
use TotalContestVendors\TotalCore\Contracts\Form\Field;
use TotalContestVendors\TotalCore\Contracts\Form\Form;
use TotalContestVendors\TotalCore\Contracts\Helpers\Arrayable;
use TotalContestVendors\TotalCore\Contracts\Helpers\DateTime;
use TotalContestVendors\TotalCore\Contracts\Helpers\Renderable;

/**
 * Interface Model
 * @package TotalContest\Contracts\Contest
 */
interface Model extends Arrayable, \JsonSerializable, Renderable {
	/**
	 * Has landing page.
	 *
	 * @return bool
	 */
	public function hasLandingPage();

	/**
	 * Get settings item.
	 *
	 * @param bool $needle Settings name.
	 * @param bool $default Default value.
	 *
	 * @return mixed|array|null
	 * @since 1.0.0
	 */
	public function getSettingsItem( $needle, $default = null );

	/**
	 * Get settings section or item.
	 *
	 * @param bool $section Settings section.
	 * @param bool $args Path to setting.
	 *
	 * @return mixed|array|null
	 * @since 1.0.0
	 */
	public function getSettings( $section = false, $args = false );

	/**
	 * Get submissions count.
	 *
	 * @return int
	 */
	public function getSubmissionsCount();

	/**
	 * Get contest id.
	 *
	 * @return int
	 * @since 1.0.0
	 */
	public function getId();

	/**
	 * Get seo attributes.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function getSeoAttributes();

	/**
	 * Get contest title.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function getTitle();

	/**
	 * Get share attributes.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function getShareAttributes();

	/**
	 * Get contest thumbnail.
	 *
	 * @return false|string
	 * @since 1.0.0
	 */
	public function getThumbnail();

	/**
	 * Get time left to start.
	 *
	 * @param string $type Either 'contest' or 'vote'
	 *
	 * @return int|\DateInterval
	 * @since 1.0.0
	 */
	public function getTimeLeftToStart( $type = 'contest' );

	/**
	 * Get start date.
	 *
	 * @param string $type
	 *
	 * @return DateTime|null
	 * @since 2.0.0
	 */
	public function getStartDate( $type = 'contest' );

	/**
	 * Get end date.
	 *
	 * @param string $type
	 *
	 * @return DateTime|null
	 * @since 2.0.0
	 */
	public function getEndDate( $type = 'contest' );

	/**
	 * Get time left to end.
	 *
	 * @param string $type Either 'contest' or 'vote'
	 *
	 * @return int|\DateInterval
	 * @since 1.0.0
	 */
	public function getTimeLeftToEnd( $type = 'contest' );

	/**
	 * Get form fields.
	 *
	 * @return Field[]
	 * @since 2.0.0
	 */
	public function getFormFields();

	/**
	 * Get form fields definitions.
	 *
	 * @return array
	 * @since 2.0.0
	 */
	public function getFormFieldsDefinitions();

	/**
	 * Get upload form.
	 *
	 * @return ParticipateForm Form object
	 * @since 1.0.0
	 */
	public function getForm();

	/**
	 * Set upload form.
	 *
	 * @param Form $form
	 *
	 * @return Form Form object
	 * @since 1.0.0
	 */
	public function setForm( Form $form );

	/**
	 * @return array|mixed|null|\WP_Post
	 */
	public function getContestPost();

	/**
	 * Get categories terms objects.
	 *
	 * @return \WP_Term[]|int|\WP_Error
	 * @since 2.0.0
	 */
	public function getCategories();

	/**
	 * Has category field.
	 *
	 * @return bool
	 * @since 2.1.0
	 */
	public function hasCategoryField();

	/**
	 * Get menu items.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function getMenuItems();

	/**
	 * Get menu item visibility.
	 *
	 * @param $item
	 *
	 * @return boolean
	 * @since    1.1.0
	 */
	public function getMenuItemVisibility( $item );

	/**
	 * Get url.
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function getUrl( $args = [] );

	/**
	 * Get AJAX url.
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function getAjaxUrl( $args = [] );

	/**
	 * Get landing url.
	 *
	 * @return mixed
	 */
	public function getLandingUrl( $args = [] );

	/**
	 * Get participate url.
	 *
	 * @param array $args
	 *
	 * @return mixed
	 */
	public function getParticipateUrl( $args = [] );

	/**
	 * Get participate ajax url.
	 *
	 * @param array $args
	 *
	 * @return mixed
	 */
	public function getParticipateAjaxUrl( $args = [] );

	/**
	 * Get submissions url.
	 *
	 * @param array $args
	 *
	 * @return mixed
	 */
	public function getSubmissionsUrl( $args = [] );

	/**
	 * Get submissions ajax url.
	 *
	 * @param array $args
	 *
	 * @return mixed
	 */
	public function getSubmissionsAjaxUrl( $args = [] );

	/**
	 * Get custom page url.
	 *
	 * @param $pageId
	 *
	 * @return mixed
	 */
	public function getCustomPageUrl( $pageId, $args = [] );

	/**
	 * Get custom page ajax url.
	 *
	 * @param $pageId
	 *
	 * @return mixed
	 */
	public function getCustomPageAjaxUrl( $pageId, $args = [] );

	/**
	 * Get limitations bag.
	 *
	 * @return \TotalContestVendors\TotalCore\Contracts\Limitations\Bag
	 */
	public function getLimitations();

	/**
	 * Get restrictions bag.
	 *
	 * @return \TotalContestVendors\TotalCore\Contracts\Restrictions\Bag
	 */
	public function getRestrictions();

	/**
	 * Get submissions divided per row.
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public function getSubmissionsRows( $args = [] );

	/**
	 * Get submissions with pagination.
	 *
	 * @param array $args
	 *
	 * @return mixed
	 */
	public function getSubmissionsWithPagination( $args = [] );

	/**
	 * Get submissions.
	 *
	 * @param array $args
	 *
	 * @return \TotalContest\Submission\Model[]
	 */
	public function getSubmissions( $args = [] );

	/**
	 * Get column width in percentage.
	 *
	 * @return float|int
	 */
	public function getColumnWidth();

	/**
	 * Get pagination for current query.
	 *
	 * @return array
	 */
	public function getPaginationItems();

	/**
	 * Check if there is previous page for current query.
	 *
	 * @return bool
	 */
	public function hasPreviousPage();

	/**
	 * Get previous page for current query.
	 *
	 * @return array
	 */
	public function getPreviousPagePaginationItem();

	/**
	 * Check if there is next page for current query.
	 *
	 * @return bool
	 */
	public function hasNextPage();

	/**
	 * Get next page for current query.
	 *
	 * @return array
	 */
	public function getNextPagePaginationItem();

	/**
	 * Get error object.
	 *
	 * @return null|\WP_Error
	 */
	public function getError();

	/**
	 * Set error object.
	 *
	 * @param string|\WP_Error $error
	 *
	 * @return void
	 */
	public function setError( $error );

	/**
	 * Has error object.
	 *
	 * @return bool
	 */
	public function hasError();

	/**
	 * Get error message.
	 *
	 * @return null|string
	 */
	public function getErrorMessage();

	/**
	 * Get sort by items.
	 *
	 * @return array
	 */
	public function getSortByItems();

	/**
	 * Get sort directions items.
	 *
	 * @return array
	 */
	public function getSortDirectionItems();

	/**
	 * Get filter by items.
	 *
	 * @return array
	 */
	public function getFilterByItems();

	/**
	 * Get current page.
	 *
	 * @return int
	 */
	public function getCurrentPage();

	/**
	 * Set current page.
	 *
	 * @param $page
	 */
	public function setCurrentPage( $page );

	/**
	 * Get prefix.
	 *
	 * @param string $append
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function getPrefix( $append = '' );

	/**
	 * Get current screen.
	 *
	 * @return string Current screen.
	 * @since 1.0.0
	 */
	public function getScreen();

	/**
	 * Set current screen.
	 *
	 * @param $screen string Screen name.
	 *
	 * @return $this
	 * @since 1.0.0
	 */
	public function setScreen( $screen );

	/**
	 * Is current screen.
	 *
	 * @param $screen string Screen name.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function isScreen( $screen );

	/**
	 * Is landing screen.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function isLandingScreen();

	/**
	 * Is participate screen.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function isParticipateScreen();

	/**
	 * Is submissions screen.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function isSubmissionsScreen();

	/**
	 * Is submission screen.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function isSubmissionScreen();

	/**
	 * Is password protected.
	 *
	 * @return bool
	 */
	public function isPasswordProtected();

	/**
	 * Is custom page screen.
	 *
	 * @param string $pageId
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function isCustomPageScreen( $pageId = null );

	/**
	 * Get content pages
	 *
	 * @return array
	 * @since 2.0.0
	 */
	public function getCustomPages();

	/**
	 * Get current content page.
	 *
	 * @param null $id
	 *
	 * @return array|null
	 * @since 1.0.0
	 */
	public function getCustomPage( $id = null );

	/**
	 * Is content page.
	 *
	 * @param $pageId
	 *
	 * @return bool
	 */
	public function isCustomPage( $pageId );

	/**
	 * Get current content page Id.
	 *
	 * @return string|null
	 * @since 1.0.0
	 */
	public function getCustomPageId();

	/**
	 * Set current content Id.
	 *
	 * @param $customPage
	 *
	 * @return $this
	 * @since 1.0.0
	 */
	public function setCustomPageId( $customPage );

	/**
	 * Get menu visibility.
	 *
	 * @return string Current screen.
	 * @since 1.0.0
	 */
	public function getMenuVisibility();

	/**
	 * Set menu visibility.
	 *
	 * @param $visible
	 *
	 * @return $this
	 * @since 1.0.0
	 */
	public function setMenuVisibility( $visible );

	/**
	 * Set menu item visibility.
	 *
	 * @param $slug
	 *
	 * @return $this
	 * @since    1.1.0
	 */
	public function setMenuItemVisibility( $slug );

	/**
	 * Get menu item visibility.
	 *
	 * @return array
	 * @since    1.1.0
	 */
	public function getMenuItemsVisibility();

	/**
	 * Set menu item visibility.
	 *
	 * @param $items
	 *
	 * @return $this
	 * @since    1.1.0
	 */
	public function setMenuItemsVisibility( $items );

	/**
	 * Get template id.
	 *
	 * @return mixed
	 * @since 2.0.0
	 */
	public function getTemplateId();

	/**
	 * Get preset id.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public function getPresetUid();

	/**
	 * Get vote scale.
	 *
	 * @return int
	 * @since 2.0.0
	 */
	public function getVoteScale();

	/**
	 * Get vote type.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public function getVoteType();

	/**
	 * Get vote criteria.
	 *
	 * @return array
	 * @since 2.0.0
	 */
	public function getVoteCriteria();

	/**
	 * Get votes count.
	 *
	 * @return int
	 * @since 2.0.0
	 */
	public function getVotes();


	/**
	 * Get contest votes formatted number.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public function getVotesNumber();

	/**
	 * Get contest votes with label.
	 * @return string
	 * @since 2.0.0
	 */
	public function getVotesWithLabel();

	/**
	 * Get votes count from log.
	 *
	 * @return int
	 * @since 2.0.0
	 */
	public function getVotesFromLogs();

	/**
	 * Get received votes.
	 *
	 * @return int
	 * @since 2.0.0
	 */
	public function getReceivedVotes();

	/**
	 * Increment votes.
	 *
	 * @param int $by
	 *
	 * @return int
	 * @since 2.0.0
	 */
	public function incrementVotes( $by = 1 );

	/**
	 * Is contest vote is rate.
	 *
	 * @return bool
	 * @since 2.0.0
	 */
	public function isRateVoting();

	/**
	 * Is contest vote is count.
	 *
	 * @return bool
	 * @since 2.0.0
	 */
	public function isCountVoting();

	/**
	 * Is contest accepting submissions.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function isAcceptingSubmissions();

	/**
	 * Get contest permalink.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public function getPermalink();

	/**
	 * Get contest current action.
	 *
	 * @return mixed
	 * @since 2.0.0
	 */
	public function getAction();

	/**
	 * Set contest current action.
	 *
	 * @param $action
	 *
	 * @return mixed
	 * @since 2.0.0
	 */
	public function setAction( $action );

	/**
	 * Edit link in WordPress dashboard.
	 * @return string
	 */
	public function getAdminEditLink();

	/**
	 * Get log page in WordPress dashboard.
	 * @return string
	 */
	public function getAdminLogLink();

	/**
	 * Get submissions page in WordPress dashboard.
	 * @return string
	 */
	public function getAdminSubmissionsLink();

	/**
	 * Save model.
	 *
	 * @return bool
	 */
	public function save();
}
