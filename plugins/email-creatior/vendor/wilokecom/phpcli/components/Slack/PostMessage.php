<?php

#namespace WilokeTest;

class PostMessage {
	const CHANNEL_ID = 'C027Q7QSTSB';
	const ENDPOINT   = 'https://hooks.slack.com/services/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';
	const DangerURL  = 'https://media.giphy.com/media/3og0IOa1X349KZ8E1i/giphy.gif?cid=ecf05e476c67vna1ircc3hyvnkskrm815kfyn9pc9ipg5ecl';

	private static function convertLevelToNumber($level): int
	{
		if ($level == LogModel::LOW) {
			return 1;
		}

		if ($level == LogModel::MEDIUM) {
			return 2;
		}

		if ($level == LogModel::HIGH) {
			return 3;
		}

		if (defined('WP_DEBUG') && WP_DEBUG && $level == LogModel::DEV) {
			return 4;
		}

		return 0;
	}

	private static function buildBlock($message, $aInfo, $imgUrl = ''): array
	{
		if (is_array($aInfo) && isset($aInfo[0]) && isset($aInfo[0]['type'])) {
			return $aInfo; // block already
		}

		$aMessage = [
			'type' => 'section',
			'text' => [
				'type' => 'mrkdwn',
				'text' => $message
			]
		];

		if (!empty($imgUrl)) {
			$aMessage['accessory'] = [
				'type'      => 'image',
				'image_url' => $imgUrl,
				'alt_text'  => 'alt text for image'
			];
		}
		$aBlocks[] = $aMessage;

		$aBlocks[] = [
			'type' => 'section',
			'text' => [
				'type' => 'mrkdwn',
				'text' => is_array($aInfo) ? json_encode($aInfo) : $aInfo
			]
		];

		return $aBlocks;
	}

	public static function postMessage($message, $aInfo, $level = 'low', $imgUrl = '')
	{
		$currentLevelNumber = self::convertLevelToNumber($level);
		$postMessageLevelNumber = self::convertLevelToNumber(defined('POST_SLACK_MESSAGE_LEVEL') ?
			POST_SLACK_MESSAGE_LEVEL : LogModel::HIGH);

		if ($currentLevelNumber < $postMessageLevelNumber) {
			return false;
		}

		$aInfo['time'] = date('Y-m-d h:s', time());
		wp_remote_post(
			defined('SLACK_ENDPOINT') ? SLACK_ENDPOINT : self::ENDPOINT,
			[
				'headers'  => [
					'Content-Type' => 'application/json',
				],
				'blocking' => false,
				'body'     => json_encode([
					'channel' => defined('SLACK_CHANNEL_ID') ? SLACK_CHANNEL_ID : self::CHANNEL_ID,
					'text'    => $message,
					'blocks'  => self::buildBlock($message, $aInfo, $imgUrl)
				])
			]
		);
	}
}
