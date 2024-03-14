<?php

namespace TotalContest\Submission;


use TotalContest\Contracts\Contest\Repository as ContestRepository;
use TotalContest\Contracts\Form\Factory as FormFactory;
use TotalContest\Contracts\Submission\Model as ModelContract;
use TotalContest\Contracts\Submission\Repository as RepositoryContract;
use TotalContestVendors\TotalCore\Contracts\Http\Request as RequestContract;
use TotalContestVendors\TotalCore\Helpers\Arrays;
use TotalContestVendors\TotalCore\Helpers\Sql;

/**
 * Class Repository
 *
 * @package TotalContest\Submission
 */
class Repository implements RepositoryContract {

	protected $contestRepository;
	protected $request;
	protected $formFactory;
	protected $database;

	/**
	 * Repository constructor.
	 *
	 * @param  RequestContract  $request
	 * @param  ContestRepository  $contestRepository
	 * @param  FormFactory  $formFactory
	 * @param  \wpdb  $database
	 */
	public function __construct( RequestContract $request, ContestRepository $contestRepository, FormFactory $formFactory, \wpdb $database ) {
		$this->request           = $request;
		$this->database          = $database;
		$this->contestRepository = $contestRepository;
		$this->formFactory       = $formFactory;
	}


	/**
	 * Get submissions.
	 *
	 * @param $query
	 *
	 * @return ModelContract[]
	 * @since 2.0.0
	 */
	public function get( $query ) {
		// Models
		$submissionModels = [];

		// Query
		$wpQuery = $this->prepareWPQuery( $query );

		// Iterate and convert each row to log model
		foreach ( $wpQuery->get_posts() as $submissionPost ):
			$submissionModels[] = $this->getById( $submissionPost );
		endforeach;

		/**
		 * Filters the results of get submissions query.
		 *
		 * @param  \TotalContest\Submission\Model[]  $submissionModels  Submissions models.
		 * @param  array  $wpQueryArgs  WP_Query arguments.
		 * @param  array  $args  Arguments.
		 * @param  array  $query  Query.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$submissionModels = apply_filters( 'totalcontest/filters/submissions/get/results',
		                                   $submissionModels,
		                                   $wpQuery,
		                                   $query );

		// Return models
		return $submissionModels;
	}

	/**
	 * @param $query
	 *
	 * @return array|mixed
	 */
	public function paginate( $query ) {
		// Query
		$wpQuery = $this->prepareWPQuery( $query );

		return [
			// Count submissions
			'count'   => (int) $wpQuery->found_posts,
			// Per page
			'perPage' => (int) $wpQuery->get( 'posts_per_page' ),
			// Count available pages
			'pages'   => (int) $wpQuery->max_num_pages,
			// Get submissions models
			'items'   => empty( $wpQuery->posts ) ? [] : array_map( [ $this, 'getById' ], $wpQuery->posts ),
		];
	}

	/**
	 * @param  array  $query
	 *
	 * @return \WP_Query
	 */
	protected function prepareWPQuery( $query = [] ) {
		$args = Arrays::parse( $query, [
			'page'           => 1,
			'perPage'        => 10,
			'orderBy'        => 'date',
			'status'         => 'publish',
			'orderDirection' => 'DESC',
			'contest'        => null,
			'wpQuery'        => [],
		] );

		// Query
		$wpQueryArgs = Arrays::parse(
			[
				'post_type'      => TC_SUBMISSION_CPT_NAME,
				'paged'          => $args['page'],
				'posts_per_page' => $args['perPage'],
				'order'          => strtoupper( $args['orderDirection'] ),
				'orderby'        => $args['orderBy'],
				'post_parent'    => $args['contest'],
				'post_status'    => $args['status'],
			],
			$args['wpQuery']
		);


		// Override order by argument
		if ( $wpQueryArgs['orderby'] !== 'date' && $wpQueryArgs['orderby'] !== 'title' ):
			$wpQueryArgs['meta_key'] = "_tc_{$wpQueryArgs['orderby']}";
			$wpQueryArgs['orderby']  = 'meta_value_num';
		endif;

		if ( ! empty( $query['filter'] ) && $query['filterBy'] === 'category' ):
			$wpQueryArgs['tax_query'] = [
				[
					'taxonomy' => TC_SUBMISSION_CATEGORY_TAX_NAME,
					'field'    => 'term_id',
					'terms'    => (array) $query['filter'],
				],
			];
		endif;

		/**
		 * Filters the list of arguments used for get submissions query.
		 *
		 * @param  array  $wpQueryArgs  WP_Query arguments.
		 * @param  array  $args  Arguments.
		 * @param  array  $query  Query.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$wpQueryArgs = apply_filters( 'totalcontest/filters/submissions/get/query', $wpQueryArgs, $args, $query );

		return new \WP_Query( $wpQueryArgs );
	}

	/**
	 * Get a submission.
	 *
	 * @param $submission
	 *
	 * @return null|ModelContract
	 * @since    1.0.0
	 */
	public function getById( $submission ) {
		if ( empty( $submission ) ):
			return null;
		endif;

		$attributes = [];

		// Post
		if ( $submission instanceof \WP_Post ):
			$attributes['post'] = $submission;
		else:
			$attributes['post'] = get_post( $submission );
		endif;

		if ( ! $attributes['post'] || $attributes['post']->post_type !== TC_SUBMISSION_CPT_NAME ):
			return null;
		endif;

		$attributes['id'] = $attributes['post']->ID;

		$container = TotalContest()->container();

		if ( ! $container->has( "submission.instances.{$attributes['id']}" ) ):
			$contest = $this->contestRepository->getById( $attributes['post']->post_parent );

			if ( empty( $contest ) ):
				return null;
			endif;

			$attributes['cookieToken'] = $this->request->cookie( $contest->getPrefix( 'token' ) );

			/**
			 * Filters the submission model attributes after retrieving.
			 *
			 * @param  array  $attributes  Entry attributes.
			 *
			 * @return array
			 * @since 2.0.0
			 */
			$attributes = apply_filters( 'totalcontest/filters/submissions/get/attributes', $attributes );

			$submissionModel = new Model( $attributes, $contest );

			if ( $contest->getSettingsItem( 'vote.type' ) === 'rate' ):
				$submissionModel->setForm( $this->formFactory->makeRateForm( $submissionModel ) );
			else:
				$submissionModel->setForm( $this->formFactory->makeVoteForm( $submissionModel ) );
			endif;

			/**
			 * Filters the submission model after creation and before adding it to container.
			 *
			 * @param  \TotalContest\Submission\Model  $model  Submission model object.
			 * @param  array  $attributes  Submission attributes.
			 *
			 * @return array
			 * @since 2.0.0
			 */
			$submissionModel = apply_filters( 'totalcontest/filters/contests/get/model',
			                                  $submissionModel,
			                                  $attributes );

			$container->share( "submission.instances.{$attributes['id']}", $submissionModel );
		endif;

		return $container->get( "submission.instances.{$attributes['id']}" );
	}

	/**
	 * @param  array  $query
	 *
	 * @return int
	 */
	public function count( $query ) {
		// Basic arguments query
		$queryArgs = [
			'post_type' => TC_SUBMISSION_CPT_NAME,
		];

		if ( ! empty( $query['contest'] ) ):
			$queryArgs['post_parent'] = (int) $query['contest'];
		endif;

		if ( ! empty( $query['status'] ) ):
			$queryArgs['post_status'] = (string) $query['status'];
		endif;

		$where = Sql::generateWhereClause( $queryArgs );
		$query = "SELECT COUNT(*) FROM {$this->database->posts} {$where}";

		return (int) $this->database->get_var( $query );
	}

	/**
	 * @return int
	 */
	public function countVotes() {
		global $wpdb;

		return (int) $wpdb->get_var( $wpdb->prepare( "SELECT sum(meta_value) FROM $wpdb->postmeta WHERE meta_key = %s",
		                                             '_tc_votes' ) );
	}

}
