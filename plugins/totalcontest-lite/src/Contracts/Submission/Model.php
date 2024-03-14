<?php

namespace TotalContest\Contracts\Submission;

use JsonSerializable;
use TotalContestVendors\TotalCore\Contracts\Form\Field;
use TotalContestVendors\TotalCore\Contracts\Form\Form;
use TotalContestVendors\TotalCore\Contracts\Helpers\Arrayable;
use TotalContestVendors\TotalCore\Contracts\Helpers\DateTime;
use TotalContestVendors\TotalCore\Contracts\Helpers\Renderable;
use TotalContestVendors\TotalCore\Contracts\Limitations\Bag as LimitationsBag;
use TotalContestVendors\TotalCore\Contracts\Restrictions\Bag as RestrictionsBag;

/**
 * Interface Model
 * @package TotalContest\Contracts\Submission
 */
interface Model extends Arrayable, JsonSerializable, Renderable {
	/**
	 * Get submissions votes count.
	 *
	 * @return int
	 * @since 2.0.0
	 */
	public function getVotes();

	/**
	 * Get submission votes formatted number.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public function getVotesNumber();

	/**
	 * Get submission votes with label.
	 * @return string
	 * @since 2.0.0
	 */
	public function getVotesWithLabel();

	/**
	 * Get received votes.
	 *
	 * @return int
	 * @since 2.0.0
	 */
	public function getReceivedVotes();

	/**
	 * Get received views.
	 *
	 * @return int
	 * @since 2.0.0
	 */
	public function getReceivedViews();

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
	 * Increment views.
	 *
	 * @param int $by
	 *
	 * @return int
	 * @since 2.0.0
	 */
	public function incrementViews( $by = 1 );

	/**
	 * Increment rate.
	 *
	 * @param array $values
	 *
	 * @return array
	 * @since 2.0.0
	 */
	public function incrementRatings( $values = [] );

	/**
	 * Get contest id.
	 *
	 * @return int
	 * @since 2.0.0
	 */
	public function getId();

	/**
	 * Get contest date diff.
	 *
	 * @return \DateInterval
	 * @since 2.0.0
	 */
	public function getDateDiff();

	/**
	 * Get contest date diff (for human).
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public function getDateDiffForHuman();

	/**
	 * Get submission content.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public function getContent();

	/**
	 * Get submission attributes.
	 *
	 * @return array|null
	 * @since 2.0.0
	 */
	public function getAttributes();

	/**
	 * Get attributes section or item.
	 *
	 * @param mixed $attribute Attribute name or dot notation representation
	 * @param null  $default   Default value to return when setAttribute is missing
	 *
	 * @return array|mixed|null
	 * @since    2.0.0
	 */
	public function getAttribute( $attribute, $default = null );

	/**
	 * Get field.
	 *
	 * @param      $field
	 * @param null $default
	 *
	 * @return array|mixed|null
	 */
	public function getField( $field, $default = null );

	/**
	 * Get fields.
	 *
	 * @return array
	 * @since 2.0.0
	 */
	public function getFields();

	/**
	 * Get visible fields.
	 *
	 * @return array
	 * @since 2.0.0
	 */
	public function getVisibleFields();

	/**
	 * Get bindings.
	 *
	 * @return array
	 * @since 1.1.0
	 */
	public function getBindings();

	/**
	 * Get submission post.
	 *
	 * @return \WP_Post
	 * @since 2.0.0
	 */
	public function getSubmissionPost();

	/**
	 * Get contest.
	 *
	 * @return null|string|\TotalContest\Contest\Model
	 * @since 2.0.0
	 */
	public function getContest();

	/**
	 * Get submission title.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public function getTitle();

	/**
	 * Get contest date.
	 *
	 * @return DateTime
	 * @since 2.0.0
	 */
	public function getDate();

	/**
	 * Get submission views count.
	 *
	 * @return int
	 * @since 2.0.0
	 */
	public function getViews();

	/**
	 * Get submission views formatted number.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public function getViewsNumber();

	/**
	 * Get submission views with label.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public function getViewsWithLabel();

	/**
	 * Get rate.
	 *
	 * @return float
	 * @since 2.0.0
	 */
	public function getRate();

	/**
	 * Get submission rate formatted number.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public function getRateNumber();

	/**
	 * Get submission rate with label.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public function getRateWithLabel();

	/**
	 * Get submission permalink.
	 *
	 * @return false|string
	 * @since 2.0.0
	 */
	public function getPermalink( $args = [] );

	/**
	 * Get thumbnail url.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public function getThumbnailUrl();

	/**
	 * Get preview
	 */
	public function getPreview();

	/**
	 * @return \WP_Term|null
	 */
	public function getCategory();

	/**
	 * @return string|null
	 */
	public function getCategoryName();

	/**
	 * Get submission type.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public function getType();

	/**
	 * Get subtitle.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public function getSubtitle();

	/**
	 * Get seo attributes.
	 *
	 * @return array
	 * @since 2.0.0
	 */
	public function getSeoAttributes();

	/**
	 * Get ratings.
	 *
	 * @return array
	 * @since 2.0.0
	 */
	public function getRatings();

	/**
	 * Get detailed ratings.
	 *
	 * @return array
	 * @since 2.0.0
	 */
	public function getDetailedRatings();

	/**
	 * Is submission embeddable
	 */
	public function isEmbeddable();

	/**
	 * Is submission approved.
	 *
	 * @return bool
	 * @since 2.0.0
	 */
	public function isApproved();

	/**
	 * Is submission accepting votes.
	 *
	 * @return bool
	 * @since 2.0.0
	 */
	public function isAcceptingVotes();

	/**
	 * Get limitations bag.
	 *
	 * @return LimitationsBag
	 */
	public function getLimitations();

	/**
	 * Get restrictions bag.
	 *
	 * @return RestrictionsBag
	 */
	public function getRestrictions();

	/**
	 * Is a winning submission.
	 *
	 * @return bool
	 * @since 2.0.0
	 */
	public function isWinner();

	/**
	 * Get error message.
	 *
	 * @return mixed
	 */
	public function getError();

	/**
	 * Set error message.
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
	 * Has voted.
	 *
	 * @return bool
	 */
	public function hasVoted();

	/**
	 * Get error message.
	 *
	 * @return mixed
	 */
	public function getErrorMessage();

	/**
	 * Whether the current user owns the submission.
	 *
	 * @return bool
	 * @since 2.0.0
	 */
	public function isOwner();

	/**
	 * Get submission's author.
	 *
	 * @return \WP_User
	 * @since 1.2.0
	 */
	public function getAuthor();

	/**
	 * Get vote form.
	 *
	 * @return Form Form object
	 * @since 2.0.0
	 */
	public function getForm();

	/**
	 * Set vote form.
	 *
	 * @param Form $form
	 *
	 * @return Form Form object
	 * @since 2.0.0
	 */
	public function setForm( Form $form );

	/**
	 * Get form fields.
	 *
	 * @return Field[]
	 * @since 2.0.0
	 */
	public function getFormFields();

	/**
	 * Get submission URL with arguments.
	 *
	 * @param array $args
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public function getUrl( $args = [] );

	/**
	 * Get prefix.
	 *
	 * @param string $append
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public function getPrefix( $append = '' );

	/**
	 * Get current screen.
	 *
	 * @return string Current screen.
	 * @since 2.0.0
	 */
	public function getScreen();

	/**
	 * Set current screen.
	 *
	 * @param $screen string Screen name.
	 *
	 * @return $this
	 * @since 2.0.0
	 */
	public function setScreen( $screen );

	/**
	 * Get template instance.
	 *
	 * @return mixed
	 * @since 1.4.0
	 */
	public function getTemplateId();

	/**
	 * Edit link in WordPress dashboard.
	 * @return string
	 */
	public function getAdminEditLink();

	/**
	 * Get submission current action.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public function getAction();

	/**
	 * Set submission current action.
	 *
	 * @param $action
	 *
	 * @return void
	 * @since 2.0.0
	 */
	public function setAction( $action );

	/**
	 * Save model.
	 *
	 * @return bool
	 */
	public function save();
}
