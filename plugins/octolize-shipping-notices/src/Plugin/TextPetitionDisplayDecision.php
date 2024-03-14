<?php
/**
 * Class TextPetitionDisplayDecision
 */

namespace Octolize\Shipping\Notices\Plugin;

use Octolize\Shipping\Notices\Helpers\WooCommerceSettingsPageChecker;
use Octolize\Shipping\Notices\WooCommerceSettings\WooCommerceSettingsPage;
use OctolizeShippingNoticesVendor\WPDesk\RepositoryRating\DisplayStrategy\DisplayDecision;

/**
 * Text Petition Display Decision.
 *
 * @codeCoverageIgnore
 */
class TextPetitionDisplayDecision implements DisplayDecision {

	/**
	 * @var WooCommerceSettingsPageChecker
	 */
	private $settings_page_checker;

	/**
	 * @param WooCommerceSettingsPageChecker $settings_page_checker .
	 */
	public function __construct( WooCommerceSettingsPageChecker $settings_page_checker ) {
		$this->settings_page_checker = $settings_page_checker;
	}

	/**
	 * @return bool
	 */
	public function should_display(): bool {
		return $this->settings_page_checker->is_settings_page_section( WooCommerceSettingsPage::SECTION_ID );
	}
}
