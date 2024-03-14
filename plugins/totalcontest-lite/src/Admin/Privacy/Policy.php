<?php

namespace TotalContest\Admin\Privacy;

use TotalContest\Contracts\Log\Repository as LogRepository;
use TotalContestVendors\TotalCore\Contracts\Foundation\Environment;

/**
 * Class Policy
 * @package TotalContest\Admin\Privacy
 */
class Policy {
	/**
	 * @var Environment $env
	 */
	protected $env;
	/**
	 * @var LogRepository $logRepository
	 */
	protected $logRepository;

	/**
	 * Policy constructor.
	 *
	 * @param Environment   $env
	 * @param LogRepository $logRepository
	 */
	public function __construct( Environment $env, LogRepository $logRepository ) {
		$this->env           = $env;
		$this->logRepository = $logRepository;

		add_action( 'admin_init', [ $this, 'suggestion' ] );
		add_filter( 'wp_privacy_personal_data_exporters', [ $this, 'registerExporter' ] );
		add_filter( 'wp_privacy_personal_data_erasers', [ $this, 'registerEraser' ] );
	}

	/**
	 * @param $exporters
	 *
	 * @return mixed
	 */
	public function registerExporter( $exporters ) {
		$exporters[ $this->env['slug'] ] = [
			'exporter_friendly_name' => $this->env['name'],
			'callback'               => [ $this, 'exporter' ],
		];

		return $exporters;
	}

	/**
	 * @param $erasers
	 *
	 * @return mixed
	 */
	public function registerEraser( $erasers ) {
		$erasers[ $this->env['slug'] ] = [
			'eraser_friendly_name' => $this->env['name'],
			'callback'             => [ $this, 'eraser' ],
		];

		return $erasers;
	}

	/**
	 * @param     $email
	 * @param int $page
	 *
	 * @return array
	 */
	public function exporter( $email, $page = 1 ) {
		$perRequest = 200; // Limit us to avoid timing out
		$page       = (int) $page;

		$exported = [];

		$user = get_user_by( 'email', $email );

		if ( $user ):
			$log = $this->logRepository->get( [
				'perPage'    => $perRequest,
				'page'       => $page,
				'conditions' => [
					'user_id' => $user->ID,
				],
			] );

			foreach ( $log as $entry ):
				$exported[] = [
					'group_id'    => 'votes',
					'group_label' => esc_html__( 'Votes', 'totalcontest' ),
					'item_id'     => $entry->getId(),
					'data'        => [
						[
							'name'  => esc_html__( 'IP', 'totalcontest' ),
							'value' => $entry->getIp(),
						],
						[
							'name'  => esc_html__( 'Useragent', 'totalcontest' ),
							'value' => $entry->getUseragent(),
						],
						[
							'name'  => esc_html__( 'Status', 'totalcontest' ),
							'value' => $entry->getStatus(),
						],
						[
							'name'  => esc_html__( 'Date', 'totalcontest' ),
							'value' => $entry->getDate(),
						],
					],
				];
			endforeach;

		endif;

		return [
			'data' => $exported,
			'done' => count( $exported ) < $perRequest,
		];
	}

	/**
	 * @param     $email
	 * @param int $page
	 *
	 * @return array
	 */
	public function eraser( $email, $page = 1 ) {
		$user          = get_user_by( 'email', $email );
		$itemsRetained = [];

		if ( $user ):
			$logQuery = [
				'conditions' => [
					'user_id' => $user->ID,
				],
			];

			$itemsRetained = $this->logRepository->anonymize( $logQuery );
		endif;

		$logCount = $user ? $this->logRepository->count( $logQuery ) : 0;

		return [
			'items_removed'  => false,
			'items_retained' => $itemsRetained,
			'messages'       => [],
			'done'           => $logCount === 0,
		];
	}

	/**
	 * Suggestion.
	 */
	public function suggestion() {
		if ( ! function_exists( 'wp_add_privacy_policy_content' ) ):
			return;
		endif;
		ob_start();
		include __DIR__ . '/views/privacy-policy.php';
		$content = ob_get_clean();

		wp_add_privacy_policy_content(
			$this->env['name'],
			wp_kses_post( wpautop( $content, false ) )
		);
	}
}
