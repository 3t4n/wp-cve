<?php

namespace TotalContest;

use TotalContest\Contracts\Contest\Model as ContestModel;
use TotalContest\Log\Export;
use TotalContestVendors\TotalCore\Helpers\Misc;


/**
 * Bootstrap
 * @package TotalContest
 * @since   1.0.0
 */
class Bootstrap {
	/**
	 * Bootstrap constructor.
	 * @since 1.0.0
	 */
	public function __construct() {
		// Define asynchronous loading state (enbaled | disabled)
		define( 'TC_ASYNC', (bool) TotalContest()->option( 'performance.async.enabled' ) );

		// Listen to flush rewrite rules requests
		add_action( 'totalcontest/actions/urls/flush', 'flush_rewrite_rules' );

		// Query variables and endpoints
		add_action( 'init', [ $this, 'endpoints' ] );
		add_filter( 'query_vars', [ $this, 'registerQueryVars' ] );
		add_filter( 'request', [ $this, 'setupQueryVars' ] );

		// Structured Data
		if ( TotalContest()->option( 'general.structuredData.enabled' ) ):
			TotalContest( 'decorators.structuredData' );
		endif;

		// Commands
		$this->registerDefaultCommands();

		// Requests
		add_action( is_admin() ? 'admin_init' : 'parse_query', [ $this, 'parseRequest' ] );

		// Prepare posts and shortcodes
		add_action( 'wp', [ $this, 'preparePost' ] );

		// Privacy
		TotalContest( 'admin.privacy' );

		// Export
		add_action( Export::ACTION_NAME, [ Export::class, 'process' ] );

		/**
		 * Fires when TotalContest complete the bootstrap phase.
		 *
		 * @since 2.0.0
		 * @order 4
		 */
		do_action( 'totalcontest/actions/bootstrap' );
	}


	/**
	 * Register default commands.
	 */
	public function registerDefaultCommands() {
		// Contest
		add_filter( 'totalcontest/commands/contest/submission:create', function ( $previousCommandResult, $contest ) {
			// Create submission
			$create = TotalContest( 'contests.commands.create.submission', [ $contest ] )->execute( $previousCommandResult );

			// Send notification if submission has been created successfully.
			if ( ! ( $create instanceof \WP_Error ) ):
				$submission = TotalContest( 'submissions.repository' )->getById( $create );

				if ( $contest->getSettingsItem( 'notifications.submission.new' ) ):
					TotalContest( 'contests.commands.send.notification', [ $contest, $submission ] )->execute( $previousCommandResult );
				endif;

				return $submission;
			endif;

			return $create;
		}, 10, 2 );

		// Submission
		add_filter( 'totalcontest/commands/submission/count:vote', function ( $previousCommandResult, $submission ) {
			return TotalContest( 'submissions.commands.vote', [ $submission ] )->execute( $previousCommandResult );
		}, 10, 2 );

		add_filter( 'totalcontest/commands/submission/count:view', function ( $previousCommandResult, $submission ) {
			return TotalContest( 'submissions.commands.view', [ $submission ] )->execute( $previousCommandResult );
		}, 10, 2 );

	}

	public function parseRequest() {
		if ( empty( $GLOBALS['wp_query'] ) ):
			return;
		endif;

		$queryVarsMap = [
			'tc_current_page' => 'currentPage',
			'tc_action'       => 'action',
			'tc_custom_page'  => 'customPage',
			'tc_category'     => 'category',
			'tc_submission'   => 'submissionId',
			'tc_menu'         => 'menu',
		];

		if ( empty( $_REQUEST['totalcontest'] ) ):
			$_REQUEST['totalcontest'] = [];
		else:
			$_REQUEST['totalcontest'] = (array) $_REQUEST['totalcontest'];
		endif;

		foreach ( $queryVarsMap as $queryVar => $fieldName ):
			$queryVarValue = get_query_var( $queryVar );
			if ( $queryVarValue ):
				$_REQUEST['totalcontest'][ $fieldName ] = $queryVarValue;
			endif;
		endforeach;

		$request                 = TotalContest( 'http.request' );
		$request['totalcontest'] = $_REQUEST['totalcontest'];

		if ( isset( $_REQUEST['totalcontest']['action'] ) ):
			// Capture actions
			add_action( 'wp', [ $this, 'route' ] );
			add_action( 'wp_ajax_totalcontest', [ $this, 'route' ] );
			add_action( 'wp_ajax_nopriv_totalcontest', [ $this, 'route' ] );
		endif;

		/**
		 * Fires after TotalContest have parsed the request.
		 *
		 * @since 2.0.0
		 * @order 4
		 */
		do_action( 'totalcontest/actions/parse-request' );
	}


	/**
	 * Endpoints.
	 */
	public function endpoints() {
		add_rewrite_endpoint( 'landing', EP_PERMALINK | EP_PAGES );
		add_rewrite_endpoint( 'participate', EP_PERMALINK | EP_PAGES );
		add_rewrite_endpoint( 'submissions', EP_PERMALINK | EP_PAGES );
		add_rewrite_endpoint( 'submission', EP_PERMALINK | EP_PAGES );
		add_rewrite_endpoint( 'content', EP_PERMALINK | EP_PAGES );

		/**
		 * Fires after TotalContest have registered the endpoints.
		 *
		 * @since 2.0.0
		 * @order 4
		 */
		do_action( 'totalcontest/actions/endpoints' );
	}

	/**
	 * Setup query vars.
	 *
	 * @param $vars
	 *
	 * @return mixed
	 */
	public function setupQueryVars( $vars ) {
		if ( isset( $vars['landing'] ) ):
			$vars['tc_action'] = 'landing';
		endif;

		if ( isset( $vars['participate'] ) ):
			$vars['tc_action'] = 'participate';
		endif;

		if ( isset( $vars['submission'] ) ):
			$vars['tc_action']     = 'submission';
			$vars['tc_submission'] = (int) $vars['submission'];
		endif;

		if ( isset( $vars['submissions'] ) ):
			$vars['tc_action'] = 'submissions';
		endif;

		if ( isset( $vars['content'] ) ):
			$vars['tc_action']      = 'content';
			$vars['tc_custom_page'] = sanitize_text_field( $vars['content'] );
		endif;

		return $vars;
	}

	/**
	 * Add extra query vars.
	 * @since 1.0.0
	 */
	public function registerQueryVars( $vars ) {
		$vars[] = 'tc_current_page';
		$vars[] = 'tc_action';
		$vars[] = 'tc_custom_page';
		$vars[] = 'tc_category';
		$vars[] = 'tc_submission';
		$vars[] = 'tc_menu';

		/**
		 * Filters the registered query vars.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		return apply_filters( 'totalcontest/filters/query-vars', $vars );
	}

	/**
	 * Process requests.
	 * @since 1.0.0
	 */
	public function route() {
		if ( empty( $_REQUEST['totalcontest']['submissionId'] ) ):
			TotalContest( 'contests.controller' );
		else:
			TotalContest( 'submissions.controller' );
		endif;

		$action = stripslashes( (string) $_REQUEST['totalcontest']['action'] );
		$method = strtolower( filter_input( INPUT_SERVER, 'REQUEST_METHOD' ) ?: filter_var( $_SERVER['REQUEST_METHOD'], FILTER_SANITIZE_STRING ) );
		/**
		 * Fires before processing a request.
		 *
		 * @param string $method HTTP method.
		 * @param string $action Action name.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/before/request', $action, $method );
		/**
		 * Fires when TotalContest receives a request.
		 *
		 * @param string $method HTTP method.
		 * @param string $action Action name.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/request', $action, $method );
		/**
		 * Fires when TotalContest receives a request (specific HTTP method and action name).
		 *
		 * @param string $method HTTP method.
		 * @param string $action Action name.
		 *
		 * @since 2.0.0
		 */
		do_action( "totalcontest/actions/request/{$method}/{$action}", $action, $method );
		/**
		 * Fires when TotalContest receives a request (specific action name).
		 *
		 * @param string $method HTTP method.
		 * @param string $action Action name.
		 *
		 * @since 2.0.0
		 */
		do_action( "totalcontest/actions/request/{$action}", $action, $method );
		/**
		 * Fires after processing a request.
		 *
		 * @param string $method HTTP method.
		 * @param string $action Action name.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/after/request', $action, $method );

		if ( Misc::isDoingAjax() ):
			/**
			 * Fires when TotalContest receives an AJAX request.
			 *
			 * @param string $method HTTP method.
			 * @param string $action Action name.
			 *
			 * @since 2.0.0
			 */
			do_action( 'totalcontest/actions/ajax-request', $action, $method );

			/**
			 * Fires when TotalContest receives an AJAX request (specific HTTP method and action name).
			 *
			 * @param string $method HTTP method.
			 * @param string $action Action name.
			 *
			 * @since 2.0.0
			 */
			do_action( "totalcontest/actions/ajax-request/{$method}/{$action}", $action, $method );

			/**
			 * Fires when TotalContest receives an AJAX request (specific action name).
			 *
			 * @param string $method HTTP method.
			 * @param string $action Action name.
			 *
			 * @since 2.0.0
			 */
			do_action( "totalcontest/actions/ajax-request/{$action}", $action, $method );
		endif;
	}


	/**
	 * Prepare posts (contest, submission).
	 * @since 1.0.0
	 */
	public function preparePost() {
		// Check whether is an archive page or singular.
		if ( is_single() || is_archive() ):
			// Get current post type.
			$currentPostType = get_post_type();
			// Callback map.
			$postTypeCallback = [
				TC_CONTEST_CPT_NAME    => [
					'async'     => 'contestPostAsync',
					'default'   => 'contestPost',
					'head'      => 'contestHeadSection',
					'title'     => 'contestTitle',
					'the_title' => 'contestTheTitle',
				],
				TC_SUBMISSION_CPT_NAME => [
					'async'     => 'submissionPostAsync',
					'default'   => 'submissionPost',
					'head'      => 'submissionHeadSection',
					'title'     => 'submissionTitle',
					'the_title' => 'submissionTheTitle',
				],
			];

			// Check current post type if covered by the map
			if ( isset( $postTypeCallback[ $currentPostType ] ) ):
				// Callback type.
				$callbackType = defined( 'TC_ASYNC' ) && TC_ASYNC ? 'async' : 'default';
				// Hide content when is archive, otherwise call the appropriate callback.
				$callback = is_archive() ? '__return_null' : [
					$this,
					$postTypeCallback[ $currentPostType ][ $callbackType ],
				];
				// Content
				add_filter( 'the_content', $callback, 99 );
				// Meta tags
				add_action( 'wp_head', [ $this, $postTypeCallback[ $currentPostType ]['head'] ], 0 );
				// Title
				add_filter( 'wp_title_parts', [ $this, $postTypeCallback[ $currentPostType ]['title'] ], 0 );
				add_filter( 'the_title', [ $this, $postTypeCallback[ $currentPostType ]['the_title'] ], 0, 2 );

				// We need to take care of the output when It's embedded
				if ( function_exists( 'is_embed' ) && is_embed() ):
					add_action( 'embed_content', function () use ( $callback ) {
						echo $callback( '' );
					}, 99 );
					remove_all_filters( 'get_the_excerpt' );
					add_filter( 'the_excerpt_embed', '__return_empty_string' );
					add_filter( 'embed_site_title_html', '__return_empty_string' );
					remove_all_actions( 'embed_content_meta' );
				endif;
			endif;
		endif;
	}

	/**
	 * Prepare contest post.
	 *
	 * @param string $content Content
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function contestPost( $content ) {
		$contest = TotalContest( 'contests.repository' )->getById( $GLOBALS['post']->ID );

		return $contest ? $contest->render() : '';
	}

	/**
	 * Prepare contest post for async loading.
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function contestPostAsync( $content ) {
		//@TODO: Improve this.
		return $this->contestPost( '' );
	}

	/**
	 * Contest title.
	 *
	 * @param $title
	 *
	 * @return mixed
	 */
	public function contestTitle( $title ) {
		$contest = TotalContest( 'contests.repository' )->getById( $GLOBALS['post']->ID );
		if ( $contest ):
			$seo      = $contest->getSeoAttributes();
			$title[0] = $seo['title'];
		endif;

		return $title;
	}

	/**
	 * Contest title.
	 *
	 * @param $title
	 * @param $id
	 *
	 * @return string
	 */
	public function contestTheTitle( $title, $id ) {
		$contest = TotalContest( 'contests.repository' )->getById( $id );
		if ( $contest ):
			$seo   = $contest->getSeoAttributes();
			$title = $seo['title'];
		endif;

		return $title;
	}

	/**
	 * Contest head section.
	 */
	public function contestHeadSection() {
		$contest = TotalContest( 'contests.repository' )->getById( $GLOBALS['post']->ID );

		if ( $contest ):
			$seo = $contest->getSeoAttributes();
			$this->printMetaTags( $seo['title'], $seo['description'], $contest->getThumbnail() );
		endif;

		/**
		 * Fires after printing contest meta tags.
		 *
		 * @param ContestModel $contest Contest object.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/contest/after/head', $contest );
	}

	/**
	 * Print meta tags.
	 *
	 * @param      $title
	 * @param      $description
	 * @param null $image
	 */
	protected function printMetaTags( $title, $description, $image = null ) {
		if ( $title ):
			printf( '<meta property="og:title" content="%s" />' . PHP_EOL, esc_attr( $title ) );
		endif;

		if ( $description ):
			printf( '<meta property="og:description" content="%s" />' . PHP_EOL, esc_attr( $description ) );
			printf( '<meta property="description" content="%s" />' . PHP_EOL, esc_attr( $description ) );
		endif;

		if ( $image ):
			printf( '<meta property="og:image" content="%s" />' . PHP_EOL, esc_attr( $image ) );
		endif;
	}

	/**
	 * Prepare submission post.
	 *
	 * @param $content
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function submissionPost( $content ) {
		$submission = TotalContest( 'submissions.repository' )->getById( $GLOBALS['post']->ID );
		TotalContest( 'submissions.controller' )->index();

		return $submission ? $submission->render() : '';
	}

	/**
	 * Prepare submission post for async loading.
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function submissionPostAsync( $content ) {
		return $this->submissionPost( '' );
	}

	/**
	 * Submission title.
	 *
	 * @param $title
	 *
	 * @return mixed
	 */
	public function submissionTitle( $title ) {
		$submission = TotalContest( 'submissions.repository' )->getById( $GLOBALS['post']->ID );
		if ( $submission ):
			$seo      = $submission->getSeoAttributes();
			$title[0] = $seo['title'];
		endif;

		return $title;
	}

	/**
	 * Submission title.
	 *
	 * @param $title
	 *
	 * @return mixed
	 */
	public function submissionTheTitle( $title, $id ) {
		$submission = TotalContest( 'submissions.repository' )->getById( $id );
		if ( $submission ):
			$seo   = $submission->getSeoAttributes();
			$title = $seo['title'];
		endif;

		return $title;
	}

	/**
	 * Submission head section.
	 */
	public function submissionHeadSection() {
		$submission = TotalContest( 'submissions.repository' )->getById( $GLOBALS['post']->ID );

		//@TODO: Add fallback to default
		$cssUrl = TotalContest()->env( 'cache.url' ) . "css/{$submission->getContest()->getPresetUid()}.css";

		wp_enqueue_style( 'contest', $cssUrl );

		if ( $submission ):
			$seo = $submission->getSeoAttributes();
			$this->printMetaTags( $seo['title'], $seo['description'], $submission->getThumbnailUrl() );
		endif;

		do_action( 'totalcontest/actions/submission/head-section', $submission );
	}

	public function prepareExport( $context ) {
		Export::process( $context );
	}
}
