<?php
namespace tmc\revisionmanager\src\Components;

/**
 * @author jakubkuranda@gmail.com
 * Date: 18.09.2018
 * Time: 11:06
 */

use shellpress\v1_4_0\src\Shared\Components\IComponent;
use tmc\revisionmanager\src\App;
use WP_Post;
use WP_User;

class Notifications extends IComponent {

	const CRON_JOB_COLLECTIVE_NOTIFICATION  = 'rm_tmc_collectiveNotification';
	const SEND_QUICK_EMAIL_TEST_ACTION_NAME = 'rm_tmc_quickEmailTest';

	/** @var int[] */
	private $_postsIdsToCollectiveNotify = array();

	/** @var bool */
	private $_postsIdsToCollectiveNotifyLoaded = false;

	/**
	 * Called on creation of component.
	 *
	 * @return void
	 */
	protected function onSetUp() {

		//  ----------------------------------------
		//  Cron-related actions
		//  ----------------------------------------

		$this::s()->event->addOnActivate(       array( $this, '_a_registerCronJobs' ) );
		$this::s()->event->addOnUpdate(         array( $this, '_a_registerCronJobs' ) );
		$this::s()->event->addOnDeactivate(     array( $this, '_a_unregisterCronJobs' ) );

		add_action( $this::CRON_JOB_COLLECTIVE_NOTIFICATION, array( $this, '_a_sendCollectiveNotificationsCron' ) );

		//  ----------------------------------------
		//  Actions
		//  ----------------------------------------

		add_action( 'wp_ajax_' . $this::SEND_QUICK_EMAIL_TEST_ACTION_NAME, array( $this, '_a_ajaxQuickEmailTestCallback' ) );

	}

	/**
	 * Adds post ID to list for further process.
	 *
	 * @param int $postId
	 *
	 * @return int[]
	 */
	public function addPostToCollectiveNotify( $postId ) {

		$postIds = $this->getPostsForCollectiveNotify();

		if( ! in_array( (int) $postId, $postIds ) ){
			$postIds[] = (int) $postId;
		}

		return $this->setPostsForCollectiveNotify( $postIds );

	}

	/**
	 * Removes post ID from list for further process.
	 *
	 * @param int $postId
	 *
	 * @return void
	 */
	public function removePostFromCollectiveNotify( $postId ) {

		$postsIds = $this->getPostsForCollectiveNotify();

		if( ( $key = array_search( (int) $postId, $postsIds ) ) !== false ) {
			unset( $postsIds[$key] );
		}

		$this->setPostsForCollectiveNotify( $postsIds );

	}

	/**
	 * Returns list of post ID's we need to process.
	 * It actually loads it from database, so use it wisely.
	 *
	 * @return int[]
	 */
	public function loadPostsForCollectiveNotifyFromDb() {

		$postIds = (array) get_option( $this::s()->getPrefix( '_listOfPostsToNotify' ), array() );

		return $this->setPostsForCollectiveNotify( $postIds );

	}

	/**
	 * Synchronizes cached values with database.
	 *
	 * @return void
	 */
	public function flushPostsForCollectiveNotifyIntoDb() {

		update_option( $this::s()->getPrefix( '_listOfPostsToNotify' ), $this->getPostsForCollectiveNotify() );

	}

	/**
	 * Removes all ID's from cache.
	 *
	 * @return int[]
	 */
	public function clearPostsForCollectiveNotify() {

		return $this->setPostsForCollectiveNotify( array() );

	}

	/**
	 * Returns cached values or loads it from database.
	 *
	 * @return int[]
	 */
	public function getPostsForCollectiveNotify() {

		if( $this->_postsIdsToCollectiveNotifyLoaded ){
			return $this->_postsIdsToCollectiveNotify;
		} else {
			$this->_postsIdsToCollectiveNotifyLoaded = true;
			return $this->loadPostsForCollectiveNotifyFromDb();
		}

	}

	/**
	 * Just sets cached values.
	 * If you want to save them, you need to call flushPostsForCollectiveNotifyIntoDb().
	 *
	 * @param int|int[] $postIds
	 *
	 * @return int[]
	 */
	public function setPostsForCollectiveNotify( $postIds ) {

		$this->_postsIdsToCollectiveNotifyLoaded = true;

		return $this->_postsIdsToCollectiveNotify = (array) $postIds;

	}

	/**
	 * Returns list of e-mail addresses based on chosen role from settings.
	 * Caution! It is not filtered yet (addresses are not excluded).
	 *
	 * @return string[]
	 */
	public function getEmailsForNotificationByChosenRole() {

		$roleForNotifications   = App::i()->settings->getRoleForNotifications();
		$addresses              = array();

		if( $roleForNotifications ){

			$users = (array) get_users( array( 'role' => $roleForNotifications ) );

			foreach( $users as $user ){ /** @var WP_User $user */
				$addresses[] = $user->user_email;
			}

		}

		return $addresses;

	}

	/**
	 * Checks if given post has the same authors email.
	 *
	 * @param string $emailAddress
	 * @param WP_Post|int $post
	 *
	 * @return bool
	 */
	public function isEmailOriginalPostsAuthor( $emailAddress, $post ) {

		if( ! $post = get_post( $post ) ) return false; //  Bail early. Could not get post.

		if( $user = get_user_by( 'ID', $post->post_author ) ){

			if( $user->user_email === $emailAddress ){

				return true;    //  Given post has the same author email as compared.

			}

		}

		return false;

	}

	/**
	 * Sends ONE e-mail about pending revision.
	 * If there are given multiple revisions ID's, messages will be connected together.
	 *
	 * @param int[]|int $revisionsIds
	 * @param string $targetEmail
	 *
	 * @return bool
	 */
	public function sendNotificationAboutRevision( $revisionsIds, $targetEmail ) {

		$revisionsIds = (array) $revisionsIds;  //  Parse everything to array.

		//  Prepare message ( replace extra codes with data )

		$innerHtml = '';

		foreach( $revisionsIds as $revisionId ) {

			if( get_post_status( $revisionId ) ){   //  Does it exist?

				$content = apply_filters( 'the_content', App::i()->settings->getNotificationContent() );
				$content = App::i()->utilities->replaceCodes( $content, $revisionId );

				$innerHtml .= $content . PHP_EOL;

			}

		}

		$html = sprintf( '<html><head></head><body>%1$s</body></head></html>', $innerHtml );

		//  Prepare subject ( replace extra codes with data )
		//  It always gets the first revision to parse codes inside subject.

		$subject = App::i()->settings->getNotificationSubject();
		$subject = App::i()->utilities->replaceCodes( $subject, empty( $revisionsIds ) ? 0 : $revisionsIds[0] );

		//  Send e-mail

		return App::s()->messages->sendEmail( $subject, $html, $targetEmail );

	}

	//  ================================================================================
	//  ACTIONS
	//  ================================================================================

	/**
	 * Called on plugin activation and update.
	 *
	 * @internal
	 *
	 * @return void
	 */
	public function _a_registerCronJobs() {

		if( ! wp_next_scheduled( $this::CRON_JOB_COLLECTIVE_NOTIFICATION ) ){
			wp_schedule_event( time(), 'daily', $this::CRON_JOB_COLLECTIVE_NOTIFICATION );
		}

	}

	/**
	 * Called on plugin deactivation.
	 *
	 * @internal
	 *
	 * @return void
	 */
	public function _a_unregisterCronJobs() {

		wp_clear_scheduled_hook( $this::CRON_JOB_COLLECTIVE_NOTIFICATION );

	}

	/**
	 * Called once a day.
	 *
	 * @internal
	 *
	 * @return void
	 */
	public function _a_sendCollectiveNotificationsCron() {

		$revisionsIds = (array) $this->loadPostsForCollectiveNotifyFromDb();

		//  ----------------------------------------
		//  Send messages
		//  ----------------------------------------

		if( ! empty( $revisionsIds ) &&  App::i()->settings->getNotificationType() === 'collective' ){

			$emailsByRole   = App::i()->notifications->getEmailsForNotificationByChosenRole();
			$excludedEmails = App::i()->settings->getExcludedEmailsFromNotifications();

			foreach( $emailsByRole as $email ){

				//  ----------------------------------------
				//  Check if this email should receive notification
				//  ----------------------------------------

				//  Bail early if address is excluded.
				if( in_array( $email, $excludedEmails ) ) continue;

				//  ----------------------------------------
				//  Prepare revisionIds based on email
				//  ----------------------------------------

				$revisionsIdsForThisEmail = $revisionsIds;  //  By default all revisionIds are assigned.

				//  If only authors should receive notifications, filter revisions.
				if( App::i()->settings->getWhoReceivesNotifications() === 'authors' ){

					$revisionsIdsForThisEmail = array();    //  Clear array.

					foreach( $revisionsIds as $revisionId ){

						if( $this->isEmailOriginalPostsAuthor( $email, App::i()->revisions->getLinkedPostId( $revisionId ) ) ){
							$revisionsIdsForThisEmail[] = $revisionId;
						}

					}

				}

				//  ----------------------------------------
				//  Send notification
				//  ----------------------------------------

				App::i()->notifications->sendNotificationAboutRevision( $revisionsIdsForThisEmail, $email );

			}

		}

		//  ----------------------------------------
		//  Clear up
		//  ----------------------------------------

		$this->clearPostsForCollectiveNotify();
		$this->flushPostsForCollectiveNotifyIntoDb();

	}

	/**
	 * Called on admin ajax hook rm_tmc_quickEmailTest.
	 * Sends fake revision test mail.
	 */
	public function _a_ajaxQuickEmailTestCallback() {

		//  ----------------------------------------
		//  Prepare data
		//  ----------------------------------------

		$emailTarget    = array_key_exists( 'emailTarget', $_POST ) ? sanitize_email( $_POST['emailTarget'] ) : '';
		$emailSubject   = array_key_exists( 'emailSubject', $_POST ) ? sanitize_text_field( $_POST['emailSubject'] ) : '';
		$emailContent   = array_key_exists( 'emailContent', $_POST ) ? apply_filters( 'the_content', wp_unslash( wp_kses_post( $_POST['emailContent'] ) ) ) : '';

		//  Modify with random post

		$randomPost = App::i()->utilities->getRandomPost();

		if( $randomPost ){
			$emailContent = App::i()->utilities->replaceCodes( $emailContent, $randomPost );
		}

		//  ----------------------------------------
		//  Send message
		//  ----------------------------------------

		$html = sprintf( '<html><head></head><body>%1$s</body></head></html>', $emailContent );

		$result = App::s()->messages->sendEmail( $emailSubject, $html, $emailTarget );

		wp_die( (int) $result );    //  Return 0/1

	}

}