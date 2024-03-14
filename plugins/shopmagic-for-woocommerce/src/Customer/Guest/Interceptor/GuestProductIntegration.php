<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\Guest\Interceptor;

use WPDesk\ShopMagic\Components\HookProvider\HookProvider;

final class GuestProductIntegration implements HookProvider {
	public const PRIORITY_BEFORE_DEFAULT = - 100;

	/** @var GuestInterceptor */
	private $interceptor;

	public function __construct( GuestInterceptor $interceptor ) {
		$this->interceptor = $interceptor;
	}

	public function hooks(): void {
		add_action(
			'comment_post',
			function ( int $comment_ID ): void {
				$this->catch_guest( $comment_ID );
			},
			self::PRIORITY_BEFORE_DEFAULT
		);
	}

	private function catch_guest( int $comment_ID ): void {
		$comment = get_comment( $comment_ID );

		if ( ! $comment instanceof \WP_Comment ) {
			return;
		}

		try {
			$this->interceptor->intercept( $comment );
		} catch ( \Exception $e ) {
		}
	}
}
