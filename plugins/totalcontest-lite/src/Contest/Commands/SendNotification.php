<?php

namespace TotalContest\Contest\Commands;

use TotalContest\Contracts\Contest\Model as ContestModel;
use TotalContest\Contracts\Log\Model as LogModel;
use TotalContest\Contracts\Submission\Model as SubmissionModel;
use TotalContest\Notification\Mail;
use TotalContest\Notification\Push;
use TotalContest\Notification\WebHook;
use TotalContestVendors\TotalCore\Contracts\Http\Request;
use TotalContestVendors\TotalCore\Helpers\Command;
use TotalContestVendors\TotalCore\Helpers\Strings;

/**
 * Class SendNotification
 *
 * @package TotalContest\Contest\Commands
 */
class SendNotification extends Command {
	/**
	 * @var ContestModel $contest
	 */
	protected $contest;
	/**
	 * @var SubmissionModel $contest
	 */
	protected $submission;

	/**
	 * @var Request $request
	 */
	protected $request;
	/**
	 * @var LogModel $log
	 */
	protected $log;
	/**
	 * @var array $templateVars
	 */
	protected $templateVars;

	/**
	 * SendNotification constructor.
	 *
	 * @param  ContestModel  $contest
	 * @param  SubmissionModel  $submission
	 * @param  Request  $request
	 */
	public function __construct( ContestModel $contest, SubmissionModel $submission, Request $request ) {
		$this->contest    = $contest;
		$this->submission = $submission;
		$this->request    = $request;
		$this->log        = static::getShared( 'log', [] );

		$contestArray                = $this->contest->toArray();
		$submissionArray             = $this->submission->toArray();
		$submissionArray['editLink'] = get_edit_post_link( $this->submission->getId() );
		$logArray                    = $this->log->toArray();

		$this->templateVars = [
			'contest'    => $contestArray,
			'submission' => $submissionArray,
			'fields'     => $this->submission->getFields(),
			'log'        => $logArray,
			'deactivate' => esc_url( admin_url( "post.php?post={$contestArray['id']}&action=edit&tab=editor>settings>general>notifications" ) ),
		];
	}

	/**
	 * Send notification.
	 *
	 * @return bool|\WP_Error
	 */
	protected function handle() {
		$email   = $this->contest->getSettingsItem( 'notifications.email', [] );
		$push    = $this->contest->getSettingsItem( 'notifications.push', [] );
		$webhook = $this->contest->getSettingsItem( 'notifications.webhook', [] );

		/**
		 * Fires before sending notifications.
		 *
		 * @param  ContestModel  $contest  WebHook settings.
		 * @param  SubmissionModel  $submission  Submission model object.
		 * @param  array  $settings  Notifications settings.
		 * @param  LogModel  $log  Log entry.
		 *
		 * @since 2.0.0
		 */
		do_action(
			'totalcontest/actions/before/contest/command/notify',
			$this->contest,
			$this->submission,
			[
				'email'   => $email,
				'push'    => $push,
				'webhook' => $webhook,
			],
			$this->log
		);

		if ( ! empty( $email['on']['newSubmission'] ) && ! empty( $email['recipient'] ) ):
			$this->sendEmail( $email['recipient'], $this->getTitle(), $this->getTemplate() );
		endif;


		if ( ! empty( $push['on']['newSubmission'] ) && ! empty( $push['appId'] ) && ! empty( $push['apiKey'] ) ):
			$this->sendPush( [ 'All' ],
			                 $this->getTitle(),
			                 $this->getBody(),
			                 [ 'appId' => $push['appId'], 'apiKey' => $push['apiKey'] ] );
		endif;

		if ( ! empty( $webhook['on']['newSubmission'] ) && ! empty( $webhook['url'] ) ):
			$this->sendWebhook( $webhook['url'],
			                    [
				                    'contest'    => $this->contest->toArray(),
				                    'submission' => $this->submission->toArray(),
				                    'log'        => $this->log->toArray(),
			                    ] );
		endif;

		/**
		 * Fires after sending notifications.
		 *
		 * @param  ContestModel  $contest  Contest model object.
		 * @param  SubmissionModel  $submission  Submission model object.
		 * @param  LogModel  $log  Log model object.
		 * @param  string  $title  Notification title.
		 * @param  string  $body  Notification body.
		 * @param  string  $template  Notification HTML template.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/after/contest/command/notify',
		           $this->contest,
		           $this->submission,
		           $this->log,
		           $this->getTitle(),
		           $this->getBody(),
		           $this->getTemplate() );

		return true;
	}

	/**
	 * @param $recipient
	 *
	 * @return bool|\WP_Error
	 */
	private function sendEmail( $recipient, $subject, $body ) {
		try {
			$notification = new Mail();
			$notification->setTo( $recipient )
			             ->setSubject( $subject )
			             ->setBody( $body )
			             ->send();

			return true;
		} catch ( \Exception $e ) {
			return new \WP_Error( $e->getCode(), $e->getMessage() );
		}
	}

	/**
	 * @param       $recipient
	 * @param       $title
	 * @param       $message
	 * @param  array  $args
	 *
	 * @return bool|\WP_Error
	 */
	private function sendPush( $recipient, $title, $message, $args = [] ) {
		try {
			$notification = new Push();
			$notification->setTo( $recipient )
			             ->setSubject( $title )
			             ->setBody( $message )
			             ->setArgs( $args )
			             ->send();

			return true;
		} catch ( \Exception $e ) {
			return new \WP_Error( $e->getCode(), $e->getMessage() );
		}
	}

	/**
	 * @param        $url
	 * @param        $payload
	 * @param  string  $userAgent
	 *
	 * @return bool|\WP_Error
	 */
	private function sendWebhook( $url, $payload, $userAgent = 'TotalContest Notification' ) {
		try {
			$notification = new WebHook();
			$notification->setTo( $url )
			             ->setFrom( $userAgent )
			             ->setBody( $payload )
			             ->send();

			return true;
		} catch ( \Exception $e ) {
			return new \WP_Error( $e->getCode(), $e->getMessage() );
		}
	}

	/**
	 * @return string
	 */
	private function getTitle() {
		$template = TotalContest()->option( 'notifications.title' ) ?: 'New submission on {{contest.title}}';

		return Strings::template( $template, $this->templateVars );
	}

	/**
	 * @return string
	 */
	private function getBody() {
		$template = TotalContest()->option( 'notifications.body' ) ?: 'Someone just submitted an entry on {{contest.title}}';

		return Strings::template( $template, $this->templateVars );
	}

	/**
	 * @return string
	 */
	private function getTemplate() {
		$template = TotalContest()->option( 'notifications.template' ) ?: file_get_contents( __DIR__ . '/views/notifications/new-submission.php' );

		return Strings::template( $template, $this->templateVars );
	}
}
