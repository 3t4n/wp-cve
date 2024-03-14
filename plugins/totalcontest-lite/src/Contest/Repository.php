<?php

namespace TotalContest\Contest;

use TotalContest\Contracts\Contest\Model as ModelContract;
use TotalContest\Contracts\Contest\Repository as RepositoryContract;
use TotalContest\Contracts\Form\Factory as FormFactory;
use TotalContestVendors\TotalCore\Contracts\Http\Request;
use TotalContestVendors\TotalCore\Helpers\Arrays;

/**
 * Contest repository
 * @package TotalContest\Contest
 * @since   1.0.0
 */
class Repository implements RepositoryContract {
	/**
	 * @var Request $request
	 */
	protected $request;
	/**
	 * @var FormFactory $formFactory
	 */
	protected $formFactory;

	/**
	 * Repository constructor.
	 *
	 * @param Request $request
	 * @param FormFactory $formFactory
	 */
	public function __construct( Request $request, FormFactory $formFactory ) {
		$this->request     = $request;
		$this->formFactory = $formFactory;
	}

	/**
	 * Get contests.
	 *
	 * @param $query
	 *
	 * @return ModelContract[]
	 */
	public function get( $query ) {

		$args = Arrays::parse( $query, [
			'page'           => 1,
			'perPage'        => 10,
			'orderBy'        => 'date',
			'orderDirection' => 'DESC',
			'wpQuery'        => [],
		] );
		// Models
		$contestModels = [];
		// Query
		$wpQueryArgs = Arrays::parse(
			[
				'post_type'      => TC_CONTEST_CPT_NAME,
				'paged'          => $args['page'],
				'posts_per_page' => $args['perPage'],
				'order'          => $args['orderDirection'],
				'orderby'        => $args['orderBy'],
			],
			$args['wpQuery']
		);

		/**
		 * Filters the list of arguments used for get contests query.
		 *
		 * @param array $wpQueryArgs WP_Query arguments.
		 * @param array $args Arguments.
		 * @param array $query Query.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$wpQueryArgs = apply_filters( 'totalcontest/filters/contests/get/query', $wpQueryArgs, $args, $query );

		$wpQuery = new \WP_Query( $wpQueryArgs );

		// Iterate and convert each row to log model
		foreach ( $wpQuery->get_posts() as $contestPost ):
			$contestModels[] = $this->getById( $contestPost );
		endforeach;

		/**
		 * Filters the results of get contests query.
		 *
		 * @param \TotalContest\Contest\Model[] $contestModels Contests models.
		 * @param array $wpQueryArgs WP_Query arguments.
		 * @param array $args Arguments.
		 * @param array $query Query.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$contestModels = apply_filters( 'totalcontest/filters/contests/get/results', $contestModels, $wpQueryArgs, $args, $query );

		// Return models
		return $contestModels;
	}

	/**
	 * Get a contest by id.
	 *
	 * @param $contest
	 *
	 * @return ModelContract|null
	 * @since 1.0.0
	 */
	public function getById( $contest ) {
		$attributes = [];
		// Post
		if ( $contest instanceof \WP_Post ):
			$attributes['post'] = $contest;
		else:
			$attributes['post'] = get_post( $contest );
		endif;

		if ( ! $attributes['post'] || $attributes['post']->post_type !== TC_CONTEST_CPT_NAME ):
			return null;
		endif;

		$attributes['id']            = $attributes['post']->ID;
		$attributes['contestId']     = $this->request->request( 'totalcontest.contestId' );
		$attributes['action']        = $this->request->request( 'totalcontest.action' );
		$attributes['currentPage']   = (int) $this->request->request( 'totalcontest.page', get_query_var( 'tc_current_page', get_query_var( 'paged', 1 ) ) );
		$attributes['sortBy']        = $this->request->request( 'totalcontest.sortBy' );
		$attributes['sortDirection'] = $this->request->request( 'totalcontest.sortDirection' );
		$attributes['filter']        = $this->request->request( 'totalcontest.filter' );
		$attributes['filterBy']      = $this->request->request( 'totalcontest.filterBy' );
		$attributes['customPage']    = $this->request->request( 'totalcontest.customPage' );
		$attributes['context']       = $this->request->request( 'totalcontest.context' );
		$attributes['menu']          = (bool) $this->request->request( 'totalcontest.menu', 1 );

		$container = TotalContest()->container();

		if ( ! $container->has( "contest.instances.{$attributes['id']}" ) ):

			/**
			 * Filters the contest model attributes after retrieving.
			 *
			 * @param array $attributes Entry attributes.
			 *
			 * @return array
			 * @since 2.0.0
			 */
			$attributes = apply_filters( 'totalcontest/filters/contests/get/attributes', $attributes );

			$contestModel = new Model( $attributes );
			$contestModel->setFormResolver( function ( $model ) {
				return $this->formFactory->makeParticipateForm( $model );
			} );

			/**
			 * Filters the contest model after creation and before adding it to container.
			 *
			 * @param \TotalContest\Contest\Model $model Contest model object.
			 * @param array $attributes Contest attributes.
			 *
			 * @return array
			 * @since 2.0.0
			 */
			$contestModel = apply_filters( 'totalcontest/filters/contests/get/model', $contestModel, $attributes );

			$container->share( "contest.instances.{$attributes['id']}", $contestModel );
		endif;

		return $container->get( "contest.instances.{$attributes['id']}" );
	}

	/**
	 * Count contests.
	 *
	 * @return int
	 * @since 2.1.8
	 */
	public function count() {
		return (int) wp_count_posts( TC_CONTEST_CPT_NAME )->publish;
	}
}
