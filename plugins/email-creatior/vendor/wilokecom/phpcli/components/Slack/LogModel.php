<?php

#namespace WilokeTest;

class LogModel {
	const LOW    = 'low';
	const HIGH   = 'high';
	const MEDIUM = 'medium';
	const DEV    = 'dev';

	public static function isValidLogLevel( $level ): bool {
		return in_array( $level, [ self::LOW, self::HIGH, self::MEDIUM ] );
	}

	public static function slackPostMessage( $postID, $warningLevel ) {
		if ( $warningLevel == self::HIGH ) {
			$action = 'Cần xử lý ngay lập tức lỗi này';
			$img    = 'https://media.giphy.com/media/xUOwGi5bbHxbT1XncA/giphy.gif';
			$level  = 'Cực kì nghiêm trọng';
		} else if ( $warningLevel == self::MEDIUM ) {
			$action = 'Cần xử lý lỗi này trong vòng 1h';
			$img    = 'https://media.giphy.com/media/3o6gbcjYiGrpaLXy7K/giphy.gif';
			$level  = 'Nghiêm trọng';
		} else {
			$action = 'Cần xử lý lỗi này trong vòng 1 ngày';
			$img    = 'https://media.giphy.com/media/dAF6BJLMOXckZO8AIL/giphy.gif';
			$level  = 'Cần xử lý sớm';
		}

		$aBlocks = [
			[
				'type' => 'section',
				'text' => [
					'type' => 'mrkdwn',
					'text' => sprintf(
						'Có một lỗi đang xảy ra trong hệ thống của chúng ta. Mức độ cảnh báo: *%s*. Hành động: *%s*. Mô tả cụ thể: %s',
						$level,
						$action,
						get_post_field( 'post_content', $postID )
					)
				]
			],
			[
				'type'      => 'section',
				'block_id'  => uniqid( 'block_id' ),
				'text'      => [
					'type' => 'mrkdwn',
					'text' => sprintf(
						'<%s|%s>',
						add_query_arg(
							[ 'action' => 'edit', 'post' => $postID ],
							admin_url( 'post.php' ) ),
						get_the_title( $postID )
					)
				],
				'accessory' => [
					'type'      => 'image',
					'image_url' => $img,
					'alt_text'  => get_the_title( $postID )
				]
			]
		];

		PostMessage::postMessage(
			'Có một lỗi xảy ra với hệ thống. Mức độ: ' . $warningLevel,
			$aBlocks,
			$warningLevel
		);
	}

	private static function sendWarningEmail( $postID, $warningLevel ) {
		if ( $warningLevel == self::MEDIUM || $warningLevel == self::HIGH ) {
			wp_mail(
				'support@myshopkit.app',
				sprintf( 'Warning: %s %s', get_the_title( $postID ), $warningLevel ),
				sprintf( 'Có lỗi xảy ra trong hệ thống MyShopKit. Tên lỗi: %s. Mức độ: %s. Log ID: %s',
					get_the_title( $postID ), $warningLevel, $postID )
			);
		}
	}

	/**
	 * @throws Exception
	 */
	public static function insert( $title, string $warningLevel = 'low', $info = [] ): bool {
		if ( ! self::isValidLogLevel( $warningLevel ) ) {
			throw new Exception( 'Invalid log level' );
		}

		if ( empty( $title ) ) {
			$title = uniqid();
		}

		$postID = wp_insert_post( [
			'post_type'    => NameHelper::autoPrefix( 'log' ),
			'post_title'   => $title,
			'post_status'  => 'publish',
			'post_content' => is_string( $info ) ? $info : json_encode( $info )
		] );

		if ( $postID ) {
			update_post_meta( $postID, NameHelper::autoPrefix( 'warningLevel' ), $warningLevel );
		}

		self::slackPostMessage( $postID, $warningLevel );
		self::sendWarningEmail( $postID, $warningLevel );

		return true;
	}

	public static function delete( $postID ): bool {
		if ( get_post_field( 'post_type', $postID ) == NameHelper::autoPrefix( 'log' )
			|| ( current_user_can( 'administrator' ) ||
				get_current_user_id() == get_post_field( 'post_author', $postID ) )
		) {
			wp_delete_post( $postID, true );
		}

		return true;
	}
}
