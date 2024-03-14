<?php

namespace TotalContest\Contest\Commands;

use TotalContest\Contracts\Contest\Model as Contest;
use TotalContest\Contracts\Log\Repository as LogRepository;
use TotalContestVendors\TotalCore\Contracts\Http\Request;
use TotalContestVendors\TotalCore\Helpers\Command;
use TotalContestVendors\TotalCore\Helpers\Embed;
use TotalContestVendors\TotalCore\Helpers\Misc;
use TotalContestVendors\TotalCore\Helpers\Strings;
use TotalContestVendors\TotalCore\Traits\Cookies;

/**
 * Class CreateSubmission
 * @package TotalContest\Contest\Commands
 */
class CreateSubmission extends Command {
	use Cookies;

	/**
	 * @var Contest
	 */
	protected $contest;
	/**
	 * @var Request
	 */
	protected $request;
	/**
	 * @var LogRepository
	 */
	protected $log;
	/**
	 * @var Embed
	 */
	protected $embed;

	/**
	 * CreateSubmission constructor.
	 *
	 * @param Contest $contest
	 * @param Request $request
	 * @param LogRepository $log
	 * @param Embed $embed
	 */
	public function __construct( Contest $contest, Request $request, LogRepository $log, Embed $embed ) {
		$this->contest = $contest;
		$this->request = $request;
		$this->log     = $log;
		$this->embed   = $embed;
	}

	/**
	 * Handle submission.
	 *
	 * @return bool|\WP_Error
	 */
	protected function handle() {
		/**
		 * Fires before saving the submission.
		 *
		 * @param \TotalContest\Contest\Model $contest Contest model object.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/before/contest/command/create', $this->contest );

		try {
			// Content
			$submissionAttributes = [
				'fields'   => [],
				'contents' => [],
				'token'    => Misc::generateUid(),
				'meta'     => [ 'schema' => '1.0' ],
			];
			// Submission fields
			$fields                          = $this->contest->getFormFieldsDefinitions();
			$submissionAttributes['fields']  = $this->contest->getForm()->toArray();
			$submissionThumbnailAttachmentId = null;
			$submissionAttachments           = [];
			$thumbnailSourceField            = $this->contest->getSettingsItem( 'contest.submissions.preview.source' );

			foreach ( $fields as $field ):
				if ( $field['type'] === 'embed' ):
					$url                                                = strip_shortcodes( esc_url( $submissionAttributes['fields'][ $field['name'] ] ) );
					$submissionAttributes['contents'][ $field['name'] ] = [
						// Type
						'type'      => 'embed',
						// Provider
						'embed'     => $this->embed->getProvider( $url ),
						// Thumbnail
						'thumbnail' => [
							'id'  => '',
							'url' => $this->embed->getProviderThumbnail( $url ),
						],
						// Preview
						'preview'   => sprintf( '[embed]%s[/embed]', $url ),
						// Embed shortcode
						'content'   => sprintf( '[embed]%s[/embed]', $url ),
					];
				elseif ( in_array( $field['type'], [ 'image', 'video', 'audio', 'file' ] ) ):
					$urlField = esc_url( (string) $this->request->request( "totalcontest.{$field['name']}_url" ) );

					if ( ! empty( $urlField ) && ! empty( $submissionAttributes['fields'][ $field['name'] ] ) ):
						throw new \ErrorException( esc_html__( 'Cannot upload a file and add file through service for the same submission.',
							'totalcontest' ) );
					endif;

					if ( ! empty( $urlField ) ):
						$submissionAttributes['contents'][ $field['name'] ] = [
							// Type
							'type'      => 'embed',
							// Provider
							'embed'     => $this->embed->getProvider( $urlField ),
							// Thumbnail
							'thumbnail' => [
								'id'  => '',
								'url' => $this->embed->getProviderThumbnail( $urlField ),
							],
							// Preview
							'preview'   => sprintf( '[embed]%s[/embed]', $urlField ),
							// Embed shortcode
							'content'   => sprintf( '[embed]%s[/embed]', $urlField ),
						];
					elseif ( isset($_FILES['totalcontest']) && $_FILES['totalcontest']['error'][ $field['name'] ] === UPLOAD_ERR_OK ):
						// Prepare uploaded file
						$submissionUploadedFile = [];
						foreach ( (array) $_FILES['totalcontest'] as $key => $value ) :
							$submissionUploadedFile[ $key ] = $value[ $field['name'] ];
						endforeach;


						// Handle upload
						$submissionAttachmentFile = wp_handle_upload( $submissionUploadedFile, [ 'test_form' => false ] );

						// Bail on error
						if ( ! empty( $submissionAttachmentFile['error'] ) ):
							throw new \ErrorException( $submissionAttachmentFile['error'] );
						endif;

						// Prepare attachment
						$submissionAttachmentArgs = [
							'guid'           => $submissionAttachmentFile['file'],
							'post_mime_type' => $submissionAttachmentFile['type'],
							'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $submissionAttachmentFile['file'] ) ),
							'post_content'   => '',
							'post_status'    => 'inherit',
						];

						// Insert attachment
						$submissionAttachmentId = wp_insert_attachment( $submissionAttachmentArgs,
							$submissionAttachmentFile['file'] );
						if ( $submissionAttachmentId instanceof \WP_Error ):
							throw new \ErrorException( $submissionAttachmentId->get_error_message(),
								$submissionAttachmentId->get_error_code() );
						endif;

						// Update metadata
						wp_update_attachment_metadata(
							$submissionAttachmentId,
							wp_generate_attachment_metadata( $submissionAttachmentId, $submissionAttachmentFile['file'] )
						);

						// Append content and thumbnails
						$submissionAttachmentFileType                       = explode( '/', $submissionAttachmentFile['type'] )[0];
						$submissionAttachmentFileType                       = in_array( $submissionAttachmentFileType, [ 'image', 'video', 'audio', 'text' ] ) ? $submissionAttachmentFileType : 'file';
						$submissionAttributes['contents'][ $field['name'] ] = [
							// Type
							'type'       => 'attachment',
							// Attachment
							'attachment' => [
								'id'   => $submissionAttachmentId,
								'type' => $submissionAttachmentFileType,
								'url'  => $submissionAttachmentFile['url'],
							],
							// Thumbnail
							'thumbnail'  => [
								'id'  => $submissionAttachmentId,
								'url' => wp_get_attachment_thumb_url( $submissionAttachmentId ),
							],
							// Preview
							'preview'    => sprintf( '[totalcontest-image id="%d" src="%s"]', $submissionAttachmentId,
								esc_attr( wp_get_attachment_thumb_url( $submissionAttachmentId ) ) ),
							// Embed shortcode
							'content'    => sprintf( '[totalcontest-%s id="%d" src="%s"]', $submissionAttachmentFileType,
								$submissionAttachmentId, esc_attr( $submissionAttachmentFile['url'] ) ),
						];

						$submissionAttributes['fields'][ $field['name'] ] = $submissionAttachmentId;

						// Add to IDs for linking purpose later on
						$submissionAttachments[] = [
							'id'   => $submissionAttachmentId,
							'type' => $submissionAttachmentFileType
						];

						// Set thumbnail ID
						if ( $thumbnailSourceField == $field['name'] && $submissionAttachmentFileType === 'image' ):
							$submissionThumbnailAttachmentId = $submissionAttachmentId;
						endif;
					endif;
				elseif ( $field['type'] === 'textarea' || $field['type'] === 'richtext' ):
					// Strip shortcode and escape html.
					$submissionAttributes['contents'][ $field['name'] ] = [
						// Type
						'type'      => 'text',
						// Text
						'text'      => [],
						// Thumbnail
						'thumbnail' => [
							'id'  => '',
							'url' => '',
						],
						// Preview
						'preview'   => sprintf( '[totalcontest-text]%s[/totalcontest-text]',
							wp_trim_excerpt( strip_tags( strip_shortcodes( $submissionAttributes['fields'][ $field['name'] ] ) ) ) ),
						// Content
						'content'   => esc_html( strip_shortcodes( $submissionAttributes['fields'][ $field['name'] ] ) ),
					];
				endif;
			endforeach;

			$altSerialization = TotalContest()->option( 'advanced.altSerialization' );

			if ( $altSerialization ):
				$submissionAttributes['contents'] = base64_encode( serialize( $submissionAttributes['contents'] ) );
				$fields                           = $submissionAttributes['fields'];
				$submissionAttributes['fields']   = base64_encode( serialize( $submissionAttributes['fields'] ) );
				wp_remove_targeted_link_rel_filters();
			endif;

			// Prepare submission post
			$submissionPostArgs = [
				'post_type'     => TC_SUBMISSION_CPT_NAME,
				'post_parent'   => $this->contest->getId(),
				'post_content'  => wp_slash( json_encode( $submissionAttributes, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) ),
				'post_author'   => get_current_user_id(),
				'post_status'   => $this->contest->getSettingsItem( 'contest.submissions.requiresApproval' ) ? 'pending' : 'publish',
				'post_date_gmt' => get_gmt_from_date( current_time( 'mysql' ) ),
			];

			// Insert the submission
			$submissionPostId = wp_insert_post( $submissionPostArgs, true );

			// Bail when something went wrong
			if ( $submissionPostId instanceof \WP_Error ):
				throw new \ErrorException( $submissionPostId->get_error_message(), $submissionPostId->get_error_code() );
			else:
				// Update title
				wp_update_post( [
					'ID'         => $submissionPostId,
					'post_title' => Strings::template(
						$this->contest->getSettingsItem( 'contest.submissions.title' ),
						[
							'fields' => $altSerialization ? $fields : $submissionAttributes['fields'],
							'user'   => wp_get_current_user()->to_array(),
							'id'     => $submissionPostId,
						]
					),
				] );
				// Assign category, if any
				if ( $altSerialization && ! empty( $fields['category'] ) ):
					wp_set_post_terms( $submissionPostId, (array) $fields['category'], TC_SUBMISSION_CATEGORY_TAX_NAME );
				elseif ( ! $altSerialization && ! empty( $submissionAttributes['fields']['category'] ) ):
					wp_set_post_terms( $submissionPostId, (array) $submissionAttributes['fields']['category'], TC_SUBMISSION_CATEGORY_TAX_NAME );
				endif;
			endif;

			// Update attachment post_parent, if any
			if ( ! empty( $submissionAttachments ) ):
				foreach ( $submissionAttachments as $submissionAttachmentId ):
					wp_update_post( [ 'ID' => $submissionAttachmentId['id'], 'post_parent' => $submissionPostId ] );
				endforeach;
			endif;

			// Set some default attributes
			update_post_meta( $submissionPostId, '_tc_votes', 0 );
			update_post_meta( $submissionPostId, '_tc_rate', 0 );
			update_post_meta( $submissionPostId, '_tc_views', 0 );

			// Update thumbnail, if any
			if ( ! empty( $submissionThumbnailAttachmentId ) ):
				set_post_thumbnail( $submissionPostId, $submissionThumbnailAttachmentId );
			endif;

			// Store token to allow contestant to change his participation
			$this->setCookie( $this->contest->getPrefix( "token_{$submissionPostId}" ), $submissionAttributes['token'] );

			// Log
			$log = $this->log->create( [
				'contest_id'    => $this->contest->getId(),
				'submission_id' => $submissionPostId,
				'action'        => 'submission',
				'status'        => 'accepted',
				'details'       => $altSerialization ? [ 'fields' => $fields ] : [ 'fields' => $submissionAttributes['fields'] ],
			] );

			// Share it for other of commands in queue
			static::share( 'submissionId', $submissionPostId );
			static::share( 'log', $log );

			// Purge cache
			if ( $submissionPostArgs['post_status'] === 'publish' ):
				Misc::purgePluginsCache();
			endif;

			/**
			 * Fires after saving the submission.
			 *
			 * @param int $submissionPostId Submission ID.
			 * @param \TotalContest\Contest\Model $contest Contest model object.
			 * @param \TotalContest\Contracts\Log\Model $contest Log model object.
			 *
			 * @since 2.0.0
			 */
			do_action( 'totalcontest/actions/after/contest/command/create', $submissionPostId, $this->contest, $log );

			return $submissionPostId;
		} catch ( \Exception $e ) {
			// Log
			$this->log->create( [
				'contest_id'    => $this->contest->getId(),
				'submission_id' => empty( $submissionPostId ) ? 0 : $submissionPostId,
				'action'        => 'submission',
				'status'        => 'rejected',
				'details'       => [
					'error'  => [ 'code' => $e->getCode(), 'message' => $e->getMessage() ],
					'fields' => [],
				],
			] );

			return new \WP_Error( $e->getCode(), $e->getMessage() );
		}
	}
}
