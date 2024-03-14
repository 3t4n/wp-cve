<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Extensions\Builtin;

use WPDesk\ShopMagic\Integration\Mailchimp\AddToMailChimpListAction;
use WPDesk\ShopMagic\Marketing\Workflow\Components\Actions\AddToListAction;
use WPDesk\ShopMagic\Marketing\Workflow\Components\Actions\DeleteFromListAction;
use WPDesk\ShopMagic\Marketing\Workflow\Components\Filters\CustomerListFilter;
use WPDesk\ShopMagic\Marketing\Workflow\Components\Filters\CustomerNotSubscribedToListFilter;
use WPDesk\ShopMagic\Workflow\Action\Builtin\SendMail\SendMailAction;
use WPDesk\ShopMagic\Workflow\Action\Builtin\SendMail\SendPlainTextMailAction;
use WPDesk\ShopMagic\Workflow\Action\Builtin\SendMail\SendRawHTMLMailAction;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Comment\CommentAdded;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Customer\CustomerAccountCreated;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Customer\CustomerOptedIn;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Customer\CustomerOptedOut;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Post\PostPublished;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Post\PostUpdated;
use WPDesk\ShopMagic\Workflow\Extensions\AbstractExtension;
use WPDesk\ShopMagic\Workflow\Filter\Builtin\Customer\CustomerIdFilter;
use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Customer;
use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Post;
use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Shop;

final class CoreExtension extends AbstractExtension {

	public function get_actions(): array {
		return [
			SendMailAction::class,
			SendPlainTextMailAction::class,
			SendRawHTMLMailAction::class,
			AddToListAction::class,
			DeleteFromListAction::class,
			AddToMailChimpListAction::class,
		];
	}

	public function get_filters(): array {
		return [
			CustomerIdFilter::class,
			CustomerListFilter::class,
			CustomerNotSubscribedToListFilter::class,
		];
	}

	public function get_events(): array {
		return [
			CommentAdded::class,
			CustomerAccountCreated::class,
			CustomerOptedIn::class,
			CustomerOptedOut::class,
			PostPublished::class,
			PostUpdated::class,
		];
	}

	public function get_placeholders(): array {
		return [
			Customer\CustomerEmail::class,
			Customer\CustomerFirstName::class,
			Customer\CustomerLastName::class,
			Customer\CustomerName::class,
			Customer\CustomerPhone::class,
			Customer\CustomerUnsubscribeUrl::class,
			Customer\CustomerUsername::class,

			Post\PostContent::class,
			Post\PostId::class,
			Post\PostLink::class,
			Post\PostTitle::class,

			Shop\ShopDescription::class,
			Shop\ShopTitle::class,
			Shop\ShopUrl::class,
		];
	}

}
