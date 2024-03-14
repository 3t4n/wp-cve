<?php
namespace tmc\revisionmanager\src\Components;

/**
 * @author jakubkuranda@gmail.com
 * Date: 08.03.2018
 * Time: 12:49
 */

use Elementor\Plugin;
use FLBuilderModel;
use shellpress\v1_4_0\src\Shared\Components\IComponent;
use shellpress\v1_4_0\src\Shared\Front\Models\HtmlElement;
use stdClass;
use tmc\revisionmanager\src\App;
use WP_Admin_Bar;
use WP_Post;
use WP_Query;
use WP_REST_Request;

class Revisions extends IComponent {

    const CREATE_LINKED_POST_ACTION_NAME        = 'rm_tmc_createLinkedPost';
    const MANUAL_ACCEPT_REVISION_ACTION_NAME    = 'rm_tmc_manualAcceptRevision';
    const DELETE_ALL_REVISIONS_ACTION_NAME      = 'rm_tmc_deleteAllRevisions';

    /** @var int */
    protected $publishingPostId = 0;

	/**
	 * Called on creation of component.
	 *
	 * @return void
	 */
	protected function onSetUp() {

		//  ----------------------------------------
		//  Actions
		//  ----------------------------------------
		
		add_action( 'admin_bar_menu',                                               array( $this, '_a_registerAdminBarToolbar' ), 90 );

		add_action( 'enqueue_block_editor_assets',                                  array( $this, '_a_enqueueBlockEditorAssets' ) );
		add_action( 'wp_enqueue_editor',                                            array( $this, '_a_enqueueSharedEditorAssets' ) );
		add_action( 'wp_enqueue_scripts',                                           array( $this, '_a_enqueueBeaverBuilderAssets' ) );
		add_action( 'elementor/editor/before_enqueue_scripts',                      array( $this, '_a_enqueueElementorEditorAssets' ) );

		add_action( 'post_submitbox_misc_actions',                                  array( $this, '_a_submitboxActionsInfo' ) );
		add_action( 'edit_form_before_permalink',                                   array( $this, '_a_displayChangeInTitle' ), 3 );
		add_action( 'edit_form_after_editor',                                       array( $this, '_a_displayChangeInContent' ), 3 );
		add_action( 'add_meta_boxes',                                               array( $this, '_a_displayChangesInBlockEditor' ), 2, 2 );

		add_action( 'admin_action_' . $this::CREATE_LINKED_POST_ACTION_NAME,        array( $this, '_a_createLinkedPost' ) );

		add_action( 'init',                                                         array( $this, '_a_initRestPublishingHooks' ) );

		//  Merging data.

		add_action( 'save_post',                                                    array( $this, '_a_mergeClassicPostData' ), 999999 );
		add_action( 'admin_action_' . $this::MANUAL_ACCEPT_REVISION_ACTION_NAME,    array( $this, '_a_manualRevisionAcceptance' ) );

		//  Classic publish detecting.

		add_action( 'pending_to_publish',                                           array( $this, '_a_detectPublishClonedPost' ) );
		add_action( 'draft_to_publish',                                             array( $this, '_a_detectPublishClonedPost' ) );
		add_action( 'future_to_publish',                                            array( $this, '_a_detectPublishClonedPost' ) );

		add_action( 'save_post',                                                    array( $this, '_a_sendNotificationAboutPendingPostClone' ), 1000 );

		add_action( 'wp_ajax_' . $this::DELETE_ALL_REVISIONS_ACTION_NAME,           array( $this, '_a_ajaxDeleteAllRevisionsCallback' ) );

		//  ----------------------------------------
		//  Filters
		//  ----------------------------------------

		add_filter( 'post_row_actions',                                             array( $this, '_f_addActionLinksToPostsTable' ), 10, 2 );
		add_filter( 'page_row_actions',                                             array( $this, '_f_addActionLinksToPostsTable' ), 10, 2 );
		add_filter( 'display_post_states',                                          array( $this, '_f_modifyPostTableStates' ), 20, 2 );

	}

	/**
	 * Checks, if current page is the admin edit post page.
	 * Returns bool when is sure about that.
	 * Null, when WP_Screen could not be received.
	 *
	 * @return bool|null
	 */
	public function isOnRevisionEditPage() {

		if( ! is_admin() ) return false;
		if( ! function_exists( 'get_current_screen' ) ) return null;

		//  ----------------------------------------
		//  Check is based on WP_Screen
		//  ----------------------------------------

		$screen = get_current_screen();

		if( $screen
		    && $screen->base === 'post'
		    && App::i()->utilities->isPostStatusAcceptedForRevision()
		    && in_array( $screen->post_type, App::i()->settings->getChosenPostTypesSlugs() )
			&& $this->arePostsLinked( get_the_ID(), $this->getLinkedPostId( get_the_ID() ) )
		){
			return true;
		} else {
			return false;
		}

	}
	
	/**
	 * Checks, if current page is the admin edit post page.
	 * Returns bool when is sure about that.
	 * Null, when WP_Screen could not be received.
	 *
	 * @return bool|null
	 */
	public function isOnEditPageWhichMayBecomeRevision() {
		
		if( ! is_admin() ) return false;
		if( ! function_exists( 'get_current_screen' ) ) return null;
		
		//  ----------------------------------------
		//  Check is based on WP_Screen
		//  ----------------------------------------
		
		$screen = get_current_screen();
		
		if( $screen
		    && $screen->base === 'post'
		    && in_array( $screen->post_type, App::i()->settings->getChosenPostTypesSlugs() )
			&& in_array( get_post_status( get_the_ID() ), array( 'publish' ) )
		){
			return true;
		} else {
			return false;
		}
		
	}
	
	/**
	 * Checks if given post is a created and fully working revision.
	 *
	 * @param WP_Post|int $post
	 */
	public function isPostRevision( $post ) {
		
		if( !($post = get_post( $post )) ) return false;   //  Bail early. Bad post.
		
		$q1 = App::i()->utilities->isPostStatusAcceptedForRevision( $post );            //  Post status ok?
		$q2 = $this->arePostsLinked( $post->ID, $this->getLinkedPostId( $post->ID ) );  //  Posts linked?
		
		return $q1 && $q2;
		
	}
	
	/**
	 * Checks if given post may be used for revision creation.
	 * DOES NOT check capabilities of user.
	 *
	 * @param WP_Post|int $post
	 */
	public function mayPostBeUsedForRevisionCreation( $post ) {
		
		if( !($post = get_post( $post )) ) return false;   //  Bail early. Bad post.
		
		$q1 = in_array( $post->post_type, App::i()->settings->getChosenPostTypesSlugs() );  //  Post type ok?
		$q2 = $post->post_status === 'publish';
		
		return $q1 && $q2;
		
	}

	/**
	 * Returns linked post ID. If there is no meta link, it will return 0.
	 *
	 * @param WP_Post|int|null $post
	 *
	 * @return int
	 */
	public function getLinkedPostId( $post = null ) {

		$post = get_post( $post );

		if( $post ){
			$meta = get_post_meta( $post->ID, 'linked_post_id', true );
		} else {
			$meta = 0;
		}

		return $meta ? (int) $meta : 0;

	}

	/**
	 * Checks if both posts are linked together by checking meta values of both of them.
	 *
	 * @param int $postId1
	 * @param int $postId2
	 *
	 * @return bool
	 */
	public function arePostsLinked( $postId1, $postId2 ) {

		$linkedId1 = $this->getLinkedPostId( $postId1 );
		$linkedId2 = $this->getLinkedPostId( $postId2 );

		if( ( $linkedId1 && $linkedId2 ) && ( intval( $postId1 ) === intval( $linkedId2 ) && intval( $postId2 ) && intval( $linkedId1 ) ) ){
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Links post with other post.
	 *
	 * @param int $postId
	 * @param int $linkedPostId
	 *
	 * @return void
	 */
	public function setLinkedPostId( $postId, $linkedPostId ) {

		update_post_meta( $postId, 'linked_post_id', $linkedPostId );

	}

	/**
	 * Removes any linked post ID from target.
	 *
	 * @param int $postId
	 *
	 * @return void
	 */
	public function removeLinkedPostId( $postId ) {

		delete_post_meta( $postId, 'linked_post_id' );

	}

	/**
	 * Adds post ID to list of  in this call.
	 *
	 * @param int $postId
	 *
	 * @return void
	 */
	public function addPostIdToListNotifiedInThisCall( $postId ) {

		$postIds = $this->getPostsIdsNotifiedInThisCall();

		if( ! in_array( (int) $postId, $postIds ) ){
			$postIds[] = (int) $postId;
		}

		$this->setPostsIdsNotifiedIntThisCall( $postIds );

	}

	/**
	 * Returns list of posts IDs processed in this call.
	 *
	 * @return int[]
	 */
	public function getPostsIdsNotifiedInThisCall() {

		$transientValue = get_transient( App::s()->getPrefix( '_postIdsNotifiedInThisCall' ) );

		return $transientValue ? (array) $transientValue : array();

	}

	/**
	 * @param int[]|int $postIds
	 *
	 * @return bool
	 */
	public function setPostsIdsNotifiedIntThisCall( $postIds ) {

		return (bool) set_transient( App::s()->getPrefix( '_postIdsNotifiedInThisCall' ), (array) $postIds, 30 );

	}

	/**
	 * Checks if given post ID has been processed in this call.
	 *
	 * @param int $postId
	 *
	 * @return bool
	 */
	public function wasPostIdNotifiedInThisCall( $postId ) {

		$postIds = $this->getPostsIdsNotifiedInThisCall();

		return in_array( (int) $postId, $postIds );

	}

	/**
	 * This is just a wrapper around copying post data methods.
	 *
	 * @param int $originalPostId
	 * @param int $revisionPostId
	 *
	 * @return void
	 */
	public function mergeTwoPosts( $originalPostId, $revisionPostId ) {

		$originalPost = get_post( $originalPostId );

		if( $originalPost ){

			$args = array(
				'post_status'	=>  'publish',
				'post_author'	=>  $originalPost->post_author,
				'post_name'		=>  $originalPost->post_name
			);

			//  Do we want to prevent date change?
			if( ! App::i()->settings->shouldRevisionReplaceOriginalPostDate() ){

				$args['post_date']      = $originalPost->post_date;
				$args['post_date_gmt']  = $originalPost->post_date_gmt;

			}

			//  ----------------------------------------
			//  Copy data from revision to original
			//  ----------------------------------------

			do_action( 'tmc/revisionmanager/revisions/merge/before', $revisionPostId, $originalPostId );

			App::i()->utilities->copyPost( $revisionPostId, $originalPostId, $args );
			App::i()->utilities->copyMeta( $revisionPostId, $originalPostId );
			App::i()->utilities->copyTaxonomies( $revisionPostId, $originalPostId );

			do_action( 'tmc/revisionmanager/revisions/merge/after', $revisionPostId, $originalPostId );

		}

	}

	/**
	 * Generates url for manual acceptance of revision.
	 *
	 * @param int $revisionId
	 *
	 * @return string
	 */
	public function generateUrlForManualRevisionAccept( $revisionId ) {

		return add_query_arg(
			array(
				'action'    =>  $this::MANUAL_ACCEPT_REVISION_ACTION_NAME,
				'post'      =>  $revisionId,
				'wp_nonce'  =>  wp_create_nonce( $this::MANUAL_ACCEPT_REVISION_ACTION_NAME )
			),
			get_admin_url( null, 'admin.php' )
		);

	}
	/**
	 * Generates url for manual post clone creation.
	 *
	 * @param int $postId
	 *
	 * @return string
	 */
	public function generateUrlForRevisionCreation( $postId ) {

		return add_query_arg(
			array(
				'action'    =>  $this::CREATE_LINKED_POST_ACTION_NAME,
				'post'      =>  $postId,
				'wp_nonce'  =>  wp_create_nonce( $this::CREATE_LINKED_POST_ACTION_NAME )
			),
			get_admin_url( null, 'admin.php' )
		);

	}

    //  ================================================================================
    //  ACTIONS
    //  ================================================================================

	/**
	 * Adds post publishing hooks to supported post type rest api actions.
	 * Called on rest_api_init.
	 *
	 * @return void
	 */
	public function _a_initRestPublishingHooks() {

		$postTypes = App::i()->settings->getChosenPostTypesSlugs();

		foreach( $postTypes as $postType ){
			add_filter( 'rest_pre_insert_' . $postType, array( $this, '_f_markPostAsPrivateBeforeRestInsert' ), 50, 2 );
		}

	}
	
	/**
	 * Called on admin_bar_menu.
	 *
	 * @param $wpAdminBar WP_Admin_Bar
	 */
	public function _a_registerAdminBarToolbar( $wpAdminBar ) {
	
		$currentPostId = get_the_ID() ?: get_queried_object_id();
		
		if( ! $currentPostId ) return;  //  Bail early. No post.
		
		if(
			( $this->mayPostBeUsedForRevisionCreation( $currentPostId )
			|| $this->isPostRevision( $currentPostId )
			|| $this->isOnRevisionEditPage()
			|| $this->isOnEditPageWhichMayBecomeRevision() )
			&&
			( current_user_can( App::i()->settings->getCapabilityForCopyCreation() )
			|| current_user_can( App::i()->settings->getCapabilityForCopyCreation() ) )
		){
			
			$linkedPostId       = $this->getLinkedPostId( $currentPostId );
			$linkedPostStatus   = get_post_status( $linkedPostId );
			
			$wpAdminBar->add_menu( array(
				'id'        =>  $this::s()->getPrefix( '_toolbar' ),
				'parent'    =>  null,
				'group'     =>  null,
				'title'     =>  __( "Revision Manager", 'rm_tmc' )
			) );
			
			//  Node: Accept revision.
			if( $this->isPostRevision( $currentPostId ) && current_user_can( App::i()->settings->getCapabilityForAcceptingChanges() ) ){
				
				$wpAdminBar->add_node( array(
					'id'        =>  $this::s()->getPrefix( '_toolbar_accept' ),
					'parent'    =>  $this::s()->getPrefix( '_toolbar' ),
					'group'     =>  null,
					'title'     =>  __( "Accept revision (merge with original)", 'rm_tmc' ),
					'href'      =>  $this->generateUrlForManualRevisionAccept( $currentPostId )
				) );
				
			}
			
			//  Node: Show original one.
			if( $this->isPostRevision( $currentPostId ) ){
				
				$wpAdminBar->add_node( array(
					'id'        =>  $this::s()->getPrefix( '_toolbar_edit_original' ),
					'parent'    =>  $this::s()->getPrefix( '_toolbar' ),
					'group'     =>  null,
					'title'     =>  __( "Edit original post", 'rm_tmc' ),
					'href'      =>  get_edit_post_link( $this->getLinkedPostId( $currentPostId ) )
				) );
				
			}
			
			//  Node: Edit connected post revision.
			if( ! $this->isPostRevision( $currentPostId ) && $this->isPostRevision( $linkedPostId ) ){
				
				$wpAdminBar->add_node( array(
					'id'        =>  $this::s()->getPrefix( '_toolbar_edit_connected' ),
					'parent'    =>  $this::s()->getPrefix( '_toolbar' ),
					'group'     =>  null,
					'title'     =>  __( "Edit connected post revision", 'rm_tmc' ),
					'href'      =>  get_edit_post_link( $linkedPostId )
				) );
				
			} else if( $this->mayPostBeUsedForRevisionCreation( $currentPostId ) && current_user_can( App::i()->settings->getCapabilityForCopyCreation() ) ){
				
				$wpAdminBar->add_node( array(
					'id'        =>  $this::s()->getPrefix( '_toolbar_create' ),
					'parent'    =>  $this::s()->getPrefix( '_toolbar' ),
					'group'     =>  null,
					'title'     =>  __( "Create new revision", 'rm_tmc' ),
					'href'      =>  $this->generateUrlForRevisionCreation( $currentPostId )
				) );
				
			}
			
		}
	
	}
	
	/**
	 * Called on wp_enqueue_editor.
	 *
	 * @return void
	 */
	public function _a_enqueueSharedEditorAssets(){
		
		wp_enqueue_style(
			App::s()->getPrefix( '_shared' ),
			App::s()->getUrl( 'assets/css/shared.css' ),
			array(),
			App::s()->getFullPluginVersion()
		);
		
	}

	/**
	 * Called on enqueue_block_editor_assets.
	 *
	 * @return void
	 */
	public function _a_enqueueBlockEditorAssets() {

		if( ! $this->isOnRevisionEditPage() ) return;

		//  ----------------------------------------
		//  Scripts
		//  ----------------------------------------

		$handle = App::s()->getPrefix( '_blockEditor' );

		wp_enqueue_script(
			$handle,
			App::s()->getUrl( 'assets/js/blockEditor.js' ),
			array( 'wp-blocks', 'wp-element', 'wp-editor' ,'jquery' ),
			App::s()->getFullPluginVersion(),
			true
		);

		wp_localize_script(
			$handle,
			$handle,
			array(
				'manualPostMergeUrl'    =>  $this->generateUrlForManualRevisionAccept( get_the_ID() ),
			)
		);

		//  ----------------------------------------
		//  Styles
		//  ----------------------------------------

		wp_enqueue_style(
			App::s()->getPrefix( '_blockEditor' ),
			App::s()->getUrl( 'assets/css/blockEditor.css' ),
			array(),
			App::s()->getFullPluginVersion()
		);

	}

	/**
	 * Called on wp_enqueue_scripts.
	 *
	 * @return void
	 */
	public function _a_enqueueBeaverBuilderAssets() {

		if( ! class_exists( 'FLBuilderModel' ) ) return;                                            //  Bail early. No Beaver Builder.
		if( ! FLBuilderModel::is_builder_active() ) return;                                         //  Bail early. Beaver builder is not active.
		if( ! App::i()->utilities->isPostStatusAcceptedForRevision() ) return;                       //  Bail early. This is not a draft.
		if( ! $this->arePostsLinked( get_the_ID(), $this->getLinkedPostId() ) ) return;             //  Bail early. Posts are not linked.

		$handle = App::s()->getPrefix( '_beaverBuilder' );

		wp_enqueue_script(
			$handle,
			App::s()->getUrl( 'assets/js/beaverBuilder.js' ),
			array( 'fl-builder', 'jquery' ),
			App::s()->getFullPluginVersion(),
			true
		);

		wp_localize_script(
			$handle,
			$handle,
			array(
				'manualPostMergeUrl'    =>  $this->generateUrlForManualRevisionAccept( get_the_ID() ),
			)
		);

	}

	/**
	 * Adds script for merging Elementor pages.
	 * Called on elementor/editor/before_enqueue_scripts
	 *
	 * @retun void
	 */
	public function _a_enqueueElementorEditorAssets(){

		if( ! App::i()->utilities->isPostStatusAcceptedForRevision() ) return;           //  Bail early. This is not a draft.
		if( ! $this->arePostsLinked( get_the_ID(), $this->getLinkedPostId() ) ) return; //  Bail early. Posts are not linked.

		$handle = App::s()->getPrefix( '_elementor' );

		wp_enqueue_script(
			$handle,
			App::s()->getUrl( 'assets/js/elementor.js' ),
			array( 'jquery' ),
			App::s()->getFullPluginVersion(),
			true
		);

		wp_localize_script(
			$handle,
			$handle,
			array(
				'manualPostMergeUrl'    =>  $this->generateUrlForManualRevisionAccept( get_the_ID() ),
			)
		);

	}

	/**
	 * If there is set REQUEST arg for action and wp_nonce, we will try to merge revision and its original.
	 * After that, we will redirect to post type list table.
	 * Called on admin_init.
	 *
	 * @return void
	 */
	public function _a_manualRevisionAcceptance() {

		//  ----------------------------------------
		//  Basic checks
		//  ----------------------------------------

		if( ! current_user_can( App::i()->settings->getCapabilityForAcceptingChanges() ) ){
			wp_die( __( 'Sorry but you can not do that', 'rm_tmc' ) );
		}

		if( ! isset( $_REQUEST['post'] ) ){
			wp_die( __( 'Wrong request. Please try again', 'rm_tmc' ) );
		}

		if( ! in_array( get_post_type( $_REQUEST['post'] ), App::i()->settings->getChosenPostTypesSlugs() ) ){
			wp_die( __( 'This post type is not supported or revision has been already accepted.', 'rm_tmc' ) );
		}

		//  ----------------------------------------
		//  Authentication
		//  ----------------------------------------

		$wpNonce = isset( $_REQUEST['wp_nonce'] ) ? $_REQUEST['wp_nonce'] : null;

		if( wp_verify_nonce( $wpNonce, $this::MANUAL_ACCEPT_REVISION_ACTION_NAME ) ){

			$revisionId = (int) $_REQUEST['post'];
			$originalId = $this->getLinkedPostId( $revisionId );

			//  Merge revision with original post.
			$this->mergeTwoPosts( $originalId, $revisionId );

			$resultOfDelete = wp_delete_post( $revisionId, true );

			//  Bail early. There is a problem with merging.
			if( ! $resultOfDelete ){
				wp_die( __( 'There was a problem while clearing the revision from database.', 'rm_tmc' ) );
			}

			//  Unlink posts.
			$this->removeLinkedPostId( $originalId );

			//  Redirect to post table.
			wp_redirect( admin_url( 'edit.php?post_type=' . get_post_type( $originalId ) ) );
			exit;

		} else {

			wp_die( __( 'Please try again. Basic authentication failed. You should refresh your session.', 'rm_tmc' ) );

		}

	}

    /**
     * Called on custom admin_action hook.
     * Creates duplicate of post and redirects to edition page.
     *
     * @return void
     */
    public function _a_createLinkedPost() {

    	if( ! current_user_can( App::i()->settings->getCapabilityForCopyCreation() ) ) wp_die( __( 'You do not have capability to do this.', 'rm_tmc' ) );

    	//  Authentication.
	    $wpNonce = $this::s()->get( $_REQUEST, 'wp_nonce' );

	    if( wp_verify_nonce( $wpNonce, $this::CREATE_LINKED_POST_ACTION_NAME ) ){

		    $originalPostId = isset( $_GET['post'] ) ? intval( $_GET['post'] ) : null;
		    $linkedPostId = null;

		    $post = get_post( $originalPostId );

		    if( $post ){    //  If such post exists

			    $newPostArgs = array(
				    'post_status'       =>  'draft',
				    'post_date'         =>  null,
				    'post_date_gmt'     =>  null
			    );

			    $linkedPostId = App::i()->utilities->copyPost( $post->ID, null, $newPostArgs );

			    if( $linkedPostId ){

				    //  Copy the rest of data
				    App::i()->utilities->copyMeta( $post->ID, $linkedPostId );
				    App::i()->utilities->copyTaxonomies( $post->ID, $linkedPostId );

				    //  Link both posts together
				    $this->setLinkedPostId( $post->ID, $linkedPostId );
				    $this->setLinkedPostId( $linkedPostId, $post->ID );

				    //  Redirect to edit post page.
				    $redirectUrl = admin_url( 'post.php?action=edit&post=' . $linkedPostId );

				    /**
				     * @since 2.5.91
				     * @param string $redirectUrl
				     * @param int $linkedPostId
				     */
				    $redirectUrl = apply_filters( 'tmc/revisionmanager/revisions/createLinkedPost/redirectUrl', $redirectUrl, $linkedPostId );

				    wp_safe_redirect( $redirectUrl );
				    exit;

			    } else {

				    wp_die( __( 'Something went wrong with creation of post duplicate.', 'rm_tmc' ) );

			    }

		    } else {

			    wp_die( __( 'Wrong post ID', 'rm_tmc' ) );

		    }

	    } else {

		    wp_die( __( 'Please try again. Basic authentication failed. You should refresh your session.', 'rm_tmc' ) );

	    }

    }

    //	==============================================
    //	DETECT REVISION PUBLICATION
    //	==============================================

    /**
     * Called on post status change event ( from pending/draft to publish ).
     *
     * @param WP_Post $post
     *
     * @return void
     */
    public function _a_detectPublishClonedPost( $post ) {

        $postTypes = App::i()->settings->getChosenPostTypesSlugs();

        if( in_array( $post->post_type, $postTypes ) ){

	        $originalPostId = $this->getLinkedPostId( $post->ID );

	        if( $this->arePostsLinked( $originalPostId, $post->ID ) ) {

		        $this->publishingPostId = $post->ID;

	        }

        }

    }

    /**
     * Merges data from copy to original post on classic request.
     * Called on save_post.
     *
     * @param int $postId
     *
     * @return void
     */
    public function _a_mergeClassicPostData( $postId ) {

	    if( wp_doing_ajax() || defined( 'REST_REQUEST' ) ) return;  //  Bail early. This is rest request.

    	if( $this->publishingPostId && $postId == $this->publishingPostId ){

    		$this->publishingPostId = 0;    //  Reset value.

			$originalPostId = $this->getLinkedPostId( $postId );

			if( $this->arePostsLinked( $originalPostId, $postId ) ){

				//  Merge revision with original post.
				$this->mergeTwoPosts( $originalPostId, $postId );

				$resultOfDelete = wp_delete_post( $postId, true );

				//  Bail early. There is a problem with merging.
				if( ! $resultOfDelete ){
					wp_die( __( 'There was a problem while clearing the revision from database.', 'rm_tmc' ) );
				}

				//  Unlink posts.
				$this->removeLinkedPostId( $originalPostId );

				//  Redirect to post table.
				wp_redirect( admin_url( 'edit.php?post_type=' . get_post_type( $originalPostId ) ) );
				exit;

			}

	    }

    }

    //	==============================================
    //	MAIL NOTIFICATION
    //	==============================================

    /**
     * Called on save_post hook.
     * Checks if this post is saved as pending.
     *
     * @param int $postId
     *
     * @return void
     */
    function _a_sendNotificationAboutPendingPostClone( $postId ) {

        $post       = get_post( $postId );
        $postTypes  = App::i()->settings->getChosenPostTypesSlugs();

        if(
            ! wp_is_post_revision( $postId )                //  Not a revision?
            && ! wp_is_post_autosave( $postId )             //  Not a autosave?
            && $post->post_status === 'pending'			    //  Is pending?
            && in_array( $post->post_type, $postTypes )     //  Is chosen by user?
        ){

        	//  ----------------------------------------
        	//  Sending notification about every single revision
        	//  ----------------------------------------

	        if( App::i()->settings->getNotificationType() === 'everySingle' && ! $this->wasPostIdNotifiedInThisCall( $postId ) ){

		        $emailsByRole   = App::i()->notifications->getEmailsForNotificationByChosenRole();
		        $excludedEmails = App::i()->settings->getExcludedEmailsFromNotifications();

		        foreach( $emailsByRole as $email ){

		        	//  ----------------------------------------
		        	//  Check if this email should receive notification
		        	//  ----------------------------------------

			        //  Bail early if only authors should receive notifications and this address is wrong.
			        if( App::i()->settings->getWhoReceivesNotifications() === 'authors' ){
			        	if( ! App::i()->notifications->isEmailOriginalPostsAuthor( $email, $this->getLinkedPostId( $postId ) ) ){
			        		continue;
				        }
			        }

			        //  Bail early if address is excluded.
			        if( in_array( $email, $excludedEmails ) ) continue;

			        //  ----------------------------------------
			        //  Send notification
			        //  ----------------------------------------

			        $result = App::i()->notifications->sendNotificationAboutRevision( $postId, $email );

		        }

		        //  Tell to not make duplicated notifications.
		        $this->addPostIdToListNotifiedInThisCall( $postId );

	        }

	        //  ----------------------------------------
	        //  Adding revisions to collective list
	        //  ----------------------------------------

			App::i()->notifications->addPostToCollectiveNotify( $postId );
	        App::i()->notifications->flushPostsForCollectiveNotifyIntoDb();

        }

    }

    /**
     * Called on admin ajax hook.
     * Deletes all revisions.
     * Require 'manage_options' capability.
     */
    public function _a_ajaxDeleteAllRevisionsCallback() {

        if( current_user_can( App::i()->settings->getCapabilityForAcceptingChanges() ) ){

            $query = new WP_Query( array(
                'post_type'         =>  'revision',
                'post_status'       =>  'inherit',
                'nopaging'          =>  true,
                'fields'            =>  'ids'
            ) );

            $countDeleted = 0;

            foreach( $query->get_posts() as $postId ){  /** @var int $postId */

                $result = wp_delete_post_revision( $postId );

                if( $result && ! is_wp_error( $result ) ){
                    $countDeleted++;
                }

            }

            wp_die( sprintf( __( 'Removed <code>%1$s</code> revisions from database.', 'rm_tmc' ), $countDeleted ) );

        } else {

            wp_die( __( 'Sorry, but you are not allowed to do that.',   'rm_tmc' ) );

        }

    }

    /**
     * Adds info about publishing and setting post status to pending above submit button.
     *
     * @param WP_Post $post
     *
     * @return void
     */
    public function _a_submitboxActionsInfo( $post ) {

        $chosenPostTypes = App::i()->settings->getChosenPostTypesSlugs();

        if( in_array( $post->post_type, $chosenPostTypes ) && $post->post_status !== 'publish' ){

            $linkedPostId       = $this->getLinkedPostId( $post->ID );
            $linkedPostStatus   = get_post_status( $linkedPostId );

            if( $linkedPostId && $linkedPostStatus ){

                if( current_user_can( 'publish_posts' ) ){

                    printf( '<div class="misc-pub-section" style="color: #16a085;"><i class="dashicons dashicons-info"></i> %1$s</div>',
                        __( 'If you publish this post, it will override its original.', 'rm_tmc' )
                    );

                } else {

                    printf( '<div class="misc-pub-section" style="color: #16a085;"><i class="dashicons dashicons-email"></i> %1$s</div>',
                        __( 'If you change this post to pending, administrators will receive notification about it.', 'rm_tmc' )
                    );

                }

            }

        }

    }

	/**
	 * Displays changes in the_title field.
	 * Called on edit_form_before_permalink.
	 *
	 * @param WP_Post $post
	 *
	 * @return void
	 */
    public function _a_displayChangeInTitle( $post ) {

	    if( ! $this->isOnRevisionEditPage() ) return;                               //  Bail early. Not a revision.
	    if( ! App::i()->settings->isWpPostTitleDifferencesEnabled() ) return;       //  Bail early. Option disabled.

    	if( $linkedPost = get_post( $this->getLinkedPostId( $post->ID ) ) ){

			$linkedPostTitle    = $linkedPost->post_title;
			$originalPostTitle  = $post->post_title;

			if( $linkedPostTitle !== $originalPostTitle ){

				echo sprintf( '<div class="rm_tmc_diff">%1$s</div>', wp_text_diff( $linkedPostTitle, $originalPostTitle ) );

			}

	    }

    }

	/**
	 * Displays changes in the_title field.
	 * Called on edit_form_after_editor.
	 *
	 * @param WP_Post $post
	 *
	 * @return void
	 */
	public function _a_displayChangeInContent( $post ) {

		if( ! $this->isOnRevisionEditPage() ) return;                               //  Bail early. Not a revision.
		if( ! App::i()->settings->isWpPostContentDifferencesEnabled() ) return;     //  Bail early. Option disabled.

		if( $linkedPost = get_post( $this->getLinkedPostId( $post->ID ) ) ){

			$linkedPostContent    = $linkedPost->post_content;
			$originalPostContent  = $post->post_content;

			if( strlen( $linkedPostContent ) && strlen( $originalPostContent ) ){   //  All of need any content.

				if( $linkedPostContent !== $originalPostContent ){

					echo sprintf( '<div class="rm_tmc_diff">%1$s</div>', wp_text_diff( $linkedPostContent, $originalPostContent ) );

				}

			}

		}

	}
	
	/**
	 * Displays changes in post title and post content on Gutenberg block editor.
	 * Called on add_meta_boxes.
	 *
	 * @param string $postType
	 * @param WP_Post $post
	 *
	 * @return void.
	 */
	public function _a_displayChangesInBlockEditor( $postType, $post ) {
	
		if( ! get_current_screen()->is_block_editor ) return;                       //  Bai early.
		if( ! $this->isOnRevisionEditPage() ) return;                               //  Bail early.
		if( ! $originalPost = get_post( $this->getLinkedPostId( $post ) ) ) return; //  Bail early. No original post.
		if( ! App::i()->settings->isWpPostTitleDifferencesEnabled() &&
		    ! App::i()->settings->isWpPostContentDifferencesEnabled() ) return;     // Bail early. Do not display differences.
		
		//  Prepare data.
		
		$originalTitle      = $originalPost->post_title;
		$originalContent    = $originalPost->post_content;
		
		$hasTitleChanged    = $originalTitle !== $post->post_title;
		$hasContentChanged  = $originalContent !== $post->post_content;
		
		//  Maybe add metabox.
		
		if( $hasTitleChanged || $hasContentChanged ){
		
			add_meta_box(
				'rm_tmc_block_editor_diff',
				__( 'Revision differences', 'rm_tmc' ),
				function( $post, $metaboxArgs ){
					
					$diffTitleDisplay   = $this::s()->get( $metaboxArgs, 'args/titleDiff' );
					$diffContentDisplay = $this::s()->get( $metaboxArgs, 'args/contentDiff' );
					
					$diffEl = new HtmlElement( 'div' );
					$diffEl->addAttribute( 'class', 'rm_tmc_diff' );
					
					if( $diffTitleDisplay ){
						$diffEl->addContent( $diffTitleDisplay );
					}
					
					//  Line between.
					if( $diffTitleDisplay && $diffContentDisplay ){
						$diffEl->addContent( '<br/><hr/><br/>' );
					}
					
					if( $diffContentDisplay ){
						$diffEl->addContent( $diffContentDisplay );
					}
					
					echo $diffEl;
					
				},
				null,
				'normal',
				'high',
				array(
					'titleDiff'     => $hasTitleChanged && App::i()->settings->isWpPostTitleDifferencesEnabled() ? wp_text_diff( $originalTitle, $post->post_title ) : null,
					'contentDiff'   => $hasContentChanged && App::i()->settings->isWpPostContentDifferencesEnabled() ? wp_text_diff( $originalContent, $post->post_content ) : null,
				)
			);
		
		}
		
	}

    //  ================================================================================
    //  FILTERS
    //  ================================================================================

	/**
	 * This filter is used to change post_status of published post to draft.
	 * This prevents users from seeing copied post on front-end.
	 *
	 * Called on rest_pre_insert_<postType>.
	 *
	 * @param StdClass $preparedPost
	 * @param WP_REST_Request $request
	 *
	 * @return StdClass
	 */
	public function _f_markPostAsPrivateBeforeRestInsert( $preparedPost, $request ) {

		$originalPostId = $this->getLinkedPostId( $preparedPost->ID );

		//  Check original post status. We do not want trashed posts, etc.
		if( $originalPostStatus = get_post_status( $originalPostId ) ){
			if( ! in_array( $originalPostStatus, array( 'trash' ) ) ){

				//  Maybe check prepared post_status.
				if( isset( $preparedPost->post_status ) && in_array( $preparedPost->post_status, array( 'publish', 'future' ) ) ){

					//  Is real prepared post accepted as revision?
					if( $realPreparedPostStatus = get_post_status( $preparedPost->ID ) ){
						if( App::i()->utilities->isPostStatusAcceptedForRevision( $realPreparedPostStatus )){

							//  Are posts even linked?
							if( $this->arePostsLinked( $originalPostId, $preparedPost->ID ) ){

								$preparedPost->post_status = 'private'; //  OK! Mark this post as private.

							}

						}
					}

				}

			}
		}

		return $preparedPost;

	}

    /**
     * @param array $actions
     * @param WP_Post $post
     *
     * @return array
     */
    public function _f_addActionLinksToPostsTable( $actions, $post ) {

        $postTypes = App::i()->settings->getChosenPostTypesSlugs();

        //  Is current post type accepted?
        if( in_array( get_post_type( $post ), $postTypes ) ){

	        $linkedPostId       = $this->getLinkedPostId( $post->ID );
	        $linkedPostStatus   = get_post_status( $linkedPostId );

	        //  Current user can create post copies?
        	if( current_user_can( App::i()->settings->getCapabilityForCopyCreation() ) ){

		        //  ----------------------------------------
		        //  Adding actions to row
		        //  ----------------------------------------

		        if(
			        $this->arePostsLinked( $linkedPostId, $post->ID )
			        && $post->post_status === 'publish'
			        && in_array( $linkedPostStatus, array( 'pending', 'draft', 'future' ) )
		        ){

			        $actions['edit_linked_draft'] = sprintf(
				        '<a class="rm_tmc_row_action" style="color: #a00;" href="post.php?post=%1$s&action=edit">
                        <i class="dashicons dashicons-welcome-edit-page"></i> %2$s
                    </a>',
				        $linkedPostId,
				        __( 'Edit connected revision draft', 'rm_tmc' )
			        );

		        } else if(
			        $this->arePostsLinked( $linkedPostId, $post->ID )
			        && in_array( $post->post_status, array( 'pending', 'draft', 'future' ) )
			        && $linkedPostStatus === 'publish'
		        ){

			        $actions['show_linked_post'] =  sprintf(
				        '<a target="_blank" style="color: #a00;" href="%1$s">
                        <i class="dashicons-before dashicons-visibility"></i> %2$s
                    </a>',
				        get_permalink( $linkedPostId ),
				        __( 'Show original post', 'rm_tmc' )
			        );

		        } else if( $post->post_status === 'publish' ){

			        $actions['create_linked_draft'] =   sprintf(
				        '<a class="rm_tmc_row_action" style="color: #a00;" href="%1$s">
	                    <i class="dashicons dashicons-welcome-add-page"></i> %2$s
	                </a>',
				        $this->generateUrlForRevisionCreation( $post->ID ),
				        __( 'Create revision draft', 'rm_tmc' )
			        );

		        }

	        }

	        //  Current user can merge changes?
        	if( current_user_can( App::i()->settings->getCapabilityForAcceptingChanges() ) ){

        		if(
			        $this->arePostsLinked( $linkedPostId, $post->ID )
			        && in_array( $post->post_status, array( 'pending', 'draft', 'future' ) )
			        && $linkedPostStatus === 'publish'
		        ){

			        $actions['merge_linked_post'] =  sprintf(
				        '<a class="button button-small" href="%1$s">%2$s</a>',
				        $this->generateUrlForManualRevisionAccept( $post->ID ),
				        __( 'Accept revision', 'rm_tmc' )
			        );

		        }

	        }

        }

        return $actions;
    }

    /**
     * Modifies (replaces) default post states display on posts list.
     * Called on display_post_states.
     *
     * @param array $postStates
     * @param WP_Post $post
     *
     * @return array
     */
    public function _f_modifyPostTableStates( $postStates, $post ) {

        $chosenPostTypes = App::i()->settings->getChosenPostTypesSlugs();

        if( in_array( $post->post_type, $chosenPostTypes ) ){

            $linkedPostId = $this->getLinkedPostId( $post->ID );

            //  ----------------------------------------
            //  Modify state display
            //  ----------------------------------------

            if( $this->arePostsLinked( $linkedPostId, $post->ID ) ){

	            if( $post->post_status === 'future' ){
		            $postStates['scheduled'] = __( 'Scheduled Revision ✓', 'rm_tmc' );
	            }

                if( $post->post_status === 'pending' ){
                    $postStates['pending'] = __( 'Revision ✓', 'rm_tmc' );
                }

                if( $post->post_status === 'draft' ){
                    $postStates['draft'] = __( 'Revision ✓', 'rm_tmc' );
                }

            }

        }

        return $postStates;

    }

}