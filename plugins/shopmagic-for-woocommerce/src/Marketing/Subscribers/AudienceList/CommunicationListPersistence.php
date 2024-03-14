<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Subscribers\AudienceList;

use WPDesk\ShopMagic\Helper\PostMetaContainer;

/**
 * Do you need to set/get some additional communication data? It's here.
 */
final class CommunicationListPersistence extends PostMetaContainer {

	/** @var string */
	public const FIELD_TYPE_KEY = 'type';

	/** @var string */
	public const FIELD_CHECKOUT_AVAILABLE_KEY = 'checkout_available';

	/** @var string */
	public const FIELD_CHECKBOX_LABEL_KEY = 'checkout_label';

	/** @var string */
	public const FIELD_CHECKBOX_DESCRIPTION_KEY = 'checkout_description';

}
