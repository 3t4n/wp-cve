<?php

namespace TotalContest\Migrations\Contest\TotalContest;

use TotalContest\Contracts\Migrations\Contest\Transform as TransformContract;
use TotalContest\Migrations\Contest\Templates\Contest;
use TotalContest\Migrations\Contest\Templates\LogEntry;
use TotalContest\Migrations\Contest\Templates\Options;
use TotalContest\Migrations\Contest\Templates\Submission;
use TotalContestVendors\TotalCore\Helpers\Arrays;
use TotalContestVendors\TotalCore\Helpers\Misc;

/**
 * Transform Contest.
 * @package TotalContest\Migrations\Contest\TotalContest
 */
class Transform implements TransformContract {
	/**
	 * Transform contest.
	 *
	 * @param array $raw
	 *
	 * @return Contest
	 */
	public function transformContest( $raw ) {
		// Create template
		$template = new Contest();

		// Title
		$template->setTitle( $raw['title'] );

		// ID
		$template->setId( $raw['id'] );

		$rawFields                            = Arrays::getDotNotation( $raw['content'], 'contest.form.fields', [] );
		$uploadField                          = Arrays::getDotNotation( $raw['content'], 'contest.upload', [] );
		$uploadField['name']                  = $uploadField['type'];
		$uploadField['label']                 = $uploadField['type'];
		$uploadField['validations']           = $uploadField[ $uploadField['type'] ];
		$uploadField['validations']['filled'] = [ 'enabled' => true ];

		array_unshift( $rawFields, $uploadField );

		foreach ( $rawFields as $rawField ): // Question

			if ( empty( $rawField ) ):
				continue;
			endif;

			$field = [
				'uid'          => Misc::generateUid(),
				'type'         => $rawField['type'],
				'name'         => $rawField['name'],
				'label'        => $rawField['label'],
				'defaultValue' => empty( $rawField['default'] ) ? '' : $rawField['default'],
				'options'      => str_replace( ' : ', ':', Arrays::getDotNotation( $rawField, 'options', '' ) ),
				'collapsed'    => true,
				'validations'  => [
					'filled'     => Arrays::getDotNotation( $rawField, 'validations.filled', [ 'enabled' => false ] ),
					'email'      => Arrays::getDotNotation( $rawField, 'validations.email', [ 'enabled' => false ] ),
					'unique'     => Arrays::getDotNotation( $rawField, 'validations.unique', [ 'enabled' => false ] ),
					'filter'     => Arrays::getDotNotation( $rawField, 'validations.filter', [ 'enabled' => false, 'rules' => [] ] ),
					'regex'      => Arrays::getDotNotation( $rawField, 'validations.regex', [ 'enabled' => false, 'type' => 'match', 'pattern' => '', 'modifiers' => [], 'errorMessage' => '' ] ),
					'formats'    => Arrays::getDotNotation( $rawField, 'validations.formats', [ 'enabled' => false, 'extensions' => [] ] ),
					'dimensions' => Arrays::getDotNotation( $rawField, 'validations.dimensions', [ 'enabled' => false ] ),
					'services'   => Arrays::getDotNotation( $rawField, 'validations.services', [ 'enabled' => false ] ),
					'file'       => Arrays::getDotNotation( $rawField, 'validations.file', [ 'enabled' => false ] ),
					'size'       => Arrays::getDotNotation( $rawField, 'validations.size', [ 'enabled' => false ] ),
					'length'     => Arrays::getDotNotation( $rawField, 'validations.length', [ 'enabled' => false ] ),
				],
				'attributes'   => [],
				'template'     => '',
				'placeholder'  => '',
			];

			$template->addField( $field );
		endforeach;

		$submissionsSettings = Arrays::getDotNotation( $raw['content'], 'contest.submissions', [] );
		$template->addSettings( 'contest.submissions', [
			'requiresApproval' => $submissionsSettings['requiresApproval'],
			'title'            => $submissionsSettings['title'],
			'subtitle'         => empty( $submissionsSettings['meta'] ) ? '' : $submissionsSettings['meta'],
			'content'          => sprintf( "%s\n<br>\n{{contents.{$uploadField['type']}.content}}\n<br>\n%s", $submissionsSettings['before'], $submissionsSettings['after'] ),
			'preview'          => [ 'source' => $uploadField['type'], 'default' => '' ],
			'perPage'          => Arrays::getDotNotation( $raw['content'], 'design.pagination.perPage', 9 ),
		] );

		$limitationsSettings = Arrays::getDotNotation( $raw['content'], 'contest.limitations', [] );
		$template->addSettings( 'contest.limitations', $limitationsSettings );

		$frequencySettings = Arrays::getDotNotation( $raw['content'], 'contest.frequency', [] );
		$template->addSettings( 'contest.frequency', $frequencySettings );

		$pagesSettings = Arrays::getDotNotation( $raw['content'], 'pages', [] );
		$template->addSettings( 'pages', $pagesSettings );

		$shareSettings = Arrays::getDotNotation( $raw['content'], 'share', [] );
		$template->addSettings( 'share', [
			'websites' => Arrays::getDotNotation( $shareSettings, 'services', [] ),
		] );

		$voteSettings = Arrays::getDotNotation( $raw['content'], 'vote', [] );
		$template->addSettings( 'vote', $voteSettings );

		$notificationsSettings = Arrays::getDotNotation( $raw['content'], 'notifications', [] );
		$template->addSettings( 'notifications', $notificationsSettings );

		$seoSettings = Arrays::getDotNotation( $raw['content'], 'seo', [] );
		$template->addSettings( 'seo', $seoSettings );

		$template->addSettings( 'backup', $raw );

		return $template;
	}

	/**
	 * Transform options.
	 *
	 * @param array $raw
	 *
	 * @return Options
	 */
	public function transformOptions( $raw ) {
		// Create template
		$template = new Options();

		return $template;
	}

	/**
	 * Transform log.
	 *
	 * @param array $raw
	 *
	 * @return LogEntry
	 */
	public function transformLog( $raw ) {
		// Create template
		$template = new LogEntry();

		// Attributes
		foreach ( $raw as $key => $value ):
			$template[ $key ] = $value;
		endforeach;

		return $template;
	}


	/**
	 * @param $raw
	 *
	 * @return Submission
	 */
	public function transformSubmission( $raw ) {
		// Create template
		$template = new Submission();

		$template->setId( $raw['id'] );
		$template->setNewId( $raw['id'] );
		$template->setTitle( $raw['title'] );
		$template['token']    = $raw['content']['token'];
		$template['fields']   = Arrays::getDotNotation( $raw['content'], 'fields', [] );
		$type                 = $raw['content']['type'];
		$template['contents'] = [
			$type => [
				'type'      => $type,
				$type       => [],
				'thumbnail' => [
					'id'  => attachment_url_to_postid( $raw['content']['thumbnail'] ),
					'url' => $raw['content']['thumbnail'],
				],
				'preview'   => str_replace( [ '[tc-', '[/tc-' ], [ '[totalcontest-', '[/totalcontest-' ], $raw['content']['content'] ),
				'content'   => str_replace( [ '[tc-', '[/tc-' ], [ '[totalcontest-', '[/totalcontest-' ], $raw['content']['content'] ),
			],
		];
		$template['meta']     = [ 'schema' => '1.0' ];
		$template['backup']   = $raw;

		return $template;
	}
}
