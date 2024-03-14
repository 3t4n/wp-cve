<?php

namespace TotalContest\Admin\Log;

use TotalContestVendors\TotalCore\Admin\Pages\Page as AdminPageContract;
use TotalContestVendors\TotalCore\Helpers\Tracking;

/**
 * Class Page
 *
 * @package TotalContest\Admin\Log
 */
class Page extends AdminPageContract {
	public function assets() {
		// TotalContest
		wp_enqueue_script( 'totalcontest-admin-log' );
		wp_enqueue_style( 'totalcontest-admin-log' );
		wp_localize_script( 'totalcontest-admin-log', 'TotalContestLog', [
			'contestId'    => $this->request->query( 'contest' ),
			'submissionId' => $this->request->query( 'submission' ),
		] );
	}

	public function render() {
		$contestId = $this->request->query( 'contest' );
		$contest   = $contestId ? TotalContest( 'contests.repository' )->getById( $this->request->query( 'contest' ) ) : null;

		/**
		 * Filters the list of columns in log browser.
		 *
		 * @param  array  $columns  Array of columns.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$columns = apply_filters(
			'totalcontest/filters/admin/log/columns',
			[
				'status'     => [ 'label' => esc_html__( 'Status', 'totalcontest' ), 'default' => true, ],
				'action'     => [ 'label' => esc_html__( 'Action', 'totalcontest' ), 'default' => true, ],
				'date'       => [ 'label' => esc_html__( 'Date', 'totalcontest' ), 'default' => true, ],
				'ip'         => [ 'label' => esc_html__( 'IP', 'totalcontest' ), 'default' => true, ],
				'browser'    => [ 'label' => esc_html__( 'Browser', 'totalcontest' ), 'default' => false, ],
				'contest'    => [ 'label' => esc_html__( 'Contest', 'totalcontest' ), 'default' => true, ],
				'submission' => [ 'label' => esc_html__( 'Submission', 'totalcontest' ), 'default' => true, ],
				'user_name'  => [ 'label' => esc_html__( 'Name', 'totalcontest' ), 'default' => false, ],
				'user_id'    => [ 'label' => esc_html__( 'ID', 'totalcontest' ), 'default' => false, ],
				'user_login' => [ 'label' => esc_html__( 'Username', 'totalcontest' ), 'default' => true, ],
				'user_email' => [ 'label' => esc_html__( 'Email', 'totalcontest' ), 'default' => false, ],
				'details'    => [
					'label'   => esc_html__( 'Details', 'totalcontest' ),
					'default' => false,
					'compact' => true,
				],
			]
		);

		if ( $contest ) {
			foreach ( $contest->getFormFieldsDefinitions() as $field ):
				$columns[ 'form_field_' . $field['name'] ] = [
					'label'   => ($field['label'] ?: $field['name']) . ' (Form field)',
					'default' => false,
					'content' => "{{entry.attributes.details['fields.{$field['name']}'] || entry.attributes.details['fields.{$field['name']}[0]']}}",
				];
			endforeach;
			foreach ( $contest->getVoteFormFieldsDefinitions() as $field ):
				$columns[ 'vote_field_' . $field['name'] ] = [
					'label'   => ($field['label'] ?: $field['name']) . ' (Vote form)',
					'default' => false,
					'content' => "{{entry.attributes.details['fields.{$field['name']}'] || entry.attributes.details['fields.{$field['name']}[0]']}}",
				];
			endforeach;
		}

		/**
		 *
		 * Filters the list of available formats that can be used for export.
		 *
		 * @param  array  $formats  Array of formats [id => label].
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$formats = apply_filters(
			'totalcontest/filters/admin/log/formats',
			[
				'html' => esc_html__( 'HTML', 'totalcontestl' ),
				
			]
		);

		include_once __DIR__ . '/views/index.php';
	}
}
