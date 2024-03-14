<?php
namespace Layered\IfWidget;

class Addon {

	public static function start() {
		return new static;
	}

	protected function __construct() {
		add_filter('if_visibility_rules', [$this, 'promoRules']);
		add_action('admin_footer', [$this, 'adminFooter']);

		if (!class_exists('Layered\MoreVisibilityRules\Plugin')) {
			add_action('admin_more_visibility_rules', [$this, 'sectionPromo']);
		}
	}

	public function getPluginUrl() {
		return 'https://layered.market/plugins/more-visibility-rules?site=' . urlencode(site_url()) . '&utm_source=if-widget&utm_medium=upgrade&utm_campaign=Upgrade%20from%20WordPress';
	}

	public function sectionPromo() {
		?>

		<div class="col addon-promo">
			<span class="price">from $20<small>/<?php _e('yearly', 'if-widget') ?></small></span>
			<h3><?php _e('Get <strong>More Visibility Rules</strong>', 'if-widget') ?></h3>
			<p class="about-text"><?php _e('Premium Addon with more visibility rules and priority support!', 'if-widget') ?></p>

			<ul>
				<li>
					<?php _e('Advanced visibility rules:', 'if-widget') ?>
					<ul>
						<li><?php _e('Visitor location - detect visitor\'s country', 'if-widget') ?></li>
						<li><?php _e('Language - detect visitor\'s selected language', 'if-widget') ?></li>
					</ul>
				</li>
				<li>
					<?php _e('3rd-party plugin integrations:', 'if-widget') ?>
					<ul>
						<li><a href="https://woocommerce.com/products/woocommerce-subscriptions" target="_blank">WooCommerce Subscriptions</a> - <?php _e('Customer has active subscription', 'if-widget') ?></li>
						<li><a href="https://woocommerce.com/products/woocommerce-memberships" target="_blank">WooCommerce Memberships</a> - <?php _e('Customer has active membership plan', 'if-widget') ?></li>
						<li><a href="https://wordpress.org/plugins/groups" target="_blank">Groups</a> - <?php _e('Users are in a Group', 'if-widget') ?></li>
						<li><a href="https://member.wishlistproducts.com" target="_blank">WishList Member</a> - <?php _e('Users above a Membership Level', 'if-widget') ?></li>
						<li><a href="https://astoundify.com/products/wp-job-manager-listing-payments/" target="_blank">Listing Payments</a> - <?php _e('Customer has active Job Manager Listing subscription', 'if-widget') ?></li>
						<li><a href="https://restrictcontentpro.com/" target="_blank">Restrict Content Pro</a> - <?php _e('User has Subscription Level', 'if-widget') ?></li>
					</ul>
				</li>
				<li>
					<?php _e('Priority support', 'if-widget') ?>
				</li>
			</ul>

			<p class="if-widget-text-center">
				<a href="<?php echo $this->getPluginUrl() ?>#pricing" class="button button-primary" target="_blank"><?php _e('Get More Visibility Rules', 'if-widget') ?></a>
			</p>
		</div>

		<?php
	}

	public function promoRules(array $rules) {
		$activePlugins = apply_filters('active_plugins', get_option('active_plugins'));

		$rules['user-location'] = $this->getPromoRule([
			'name'		=>	__('Visitor %s from %s', 'if-widget'),
			'options'	=>	[
				'CA'	=>	__('Spain', 'if-widget'),
				'ES'	=>	__('Canada', 'if-widget'),
				'US'	=>	__('United States', 'if-widget')
			]
		]);

		$rules['languages'] = $this->getPromoRule([
			'name'		=>	__('Language %s one of %s', 'if-widget'),
			'options'	=>	[
				'en_US'		=>	'English (US)',
				'es_ES'		=>	'Spanish (ES)'
			]
		]);

		// Third-party plugin integration - Groups
		if (in_array('groups/groups.php', $activePlugins) && class_exists('Groups_Group')) {
			$rules['user-in-group'] = $this->getPromoRule(__('User %s in group %s', 'if-widget'));
		}

		// Third-party plugin integration - WooCommerce Subscriptions
		// Third-party plugin integration - Listing Payments (for WP Job Manager)
		if (in_array('woocommerce-subscriptions/woocommerce-subscriptions.php', $activePlugins)) {
			$rules['woocommerce-subscriptions'] = $this->getPromoRule(__('User %s subscribed to one of %s', 'if-widget'));
		}

		// Third-party plugin integration - WishList Member
		if (function_exists('wlmapi_the_levels')) {
			$rules['wishlist-member'] = $this->getPromoRule(__('WishList Membership Level %s %s', 'if-widget'));
		}

		// Third-party plugin integration - WooCommerce Memberships
		if (in_array('woocommerce-memberships/woocommerce-memberships.php', $activePlugins)) {
			$rules['woocommerce-memberships'] = $this->getPromoRule(__('User membership plan %s one of %s', 'if-widget'));
		}

		// Third-party plugin integration - Restrict Content Pro
		if (in_array('restrict-content-pro/restrict-content-pro.php', $activePlugins)) {
			$rules['restrict-content-pro'] = $this->getPromoRule(__('Restrict Subscription %s one of %s', 'if-widget'));
		}

		return $rules;
	}

	protected function getPromoRule($options = null) {

		if (!is_null($options) && !is_array($options)) {
			$options = [
				'name'	=>	$options
			];
		}

		return array_merge([
			'name'		=>	__('Promo rule: %s one of %s', 'if-widget'),
			'group'		=>	__('User', 'if-widget'),
			'type'		=>	'multiple',
			'options'	=>	[
				'999'	=>	__('This', 'if-widget'),
				'998'	=>	__('That', 'if-widget'),
				'997'	=>	__('Test', 'if-widget')
			],
			'callback'	=>	'__return_true'
		], $options);
	}

	public function adminFooter() {
		?>
		<div class="if-widget-dialog hidden" title="<?php _e('That\'s a Premium feature', 'if-widget') ?>">
			<p><?php _e('Get <strong>More Visibility Rules</strong> Add-on to enable integrations with third-party plugins, geo location detection and priority support.', 'if-widget') ?></p>
			<p>
				<a href="<?php echo $this->getPluginUrl() ?>#pricing" class="button button-primary if-widget-float-right" target="_blank"><?php _e('Get More Visibility Rules', 'if-widget') ?></a>
				<button class="button"><?php _e('Not right now', 'if-widget') ?></button>
			</p>
		</div>
		<?php
	}

}
