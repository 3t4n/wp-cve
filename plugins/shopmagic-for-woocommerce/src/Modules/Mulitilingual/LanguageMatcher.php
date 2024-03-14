<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Modules\Mulitilingual;

use WPDesk\ShopMagic\Workflow\Automation\Automation;

interface LanguageMatcher {

	public function matches( Automation $automation, Language $language ): bool;

}
