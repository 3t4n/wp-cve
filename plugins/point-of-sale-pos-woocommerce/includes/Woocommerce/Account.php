<?php

namespace ZPOS\Woocommerce;

use ZPOS\Model;

class Account
{
	public function __construct()
	{
		if (is_admin()) {
			return;
		}

		add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
		add_filter('woocommerce_my_account_my_address_formatted_address', [$this, 'add_data'], 10, 3);
		add_filter('woocommerce_localisation_address_formats', [$this, 'add_data_format']);
		add_filter(
			'woocommerce_formatted_address_replacements',
			[$this, 'add_data_replacement'],
			10,
			2
		);
		add_action('woocommerce_after_edit_address_form_billing', [$this, 'render_control']);
		add_action('woocommerce_customer_save_address', [$this, 'save'], 10, 2);
	}

	public function enqueue_assets(): void
	{
		global $wp;
		$request = explode('/', $wp->request);

		if ('billing' !== end($request) || !is_account_page()) {
			return;
		}

		Model\VatControl::enqueue_assets();
	}

	public function add_data($address, $user_id, $address_type): array
	{
		if ('billing' !== $address_type) {
			return $address;
		}

		$data = (new Model\BillingVat($user_id))->get_formatted_data();

		if (empty($data)) {
			return $address;
		}

		$address[Model\BillingVat::BASE_KEY] = $data;

		return $address;
	}

	public function add_data_format(array $formats): array
	{
		foreach ($formats as $key => &$format) {
			$format .= "\n{" . Model\BillingVat::BASE_KEY . '}';
		}

		return $formats;
	}

	public function add_data_replacement(array $replacements, array $args): array
	{
		$replacements['{' . Model\BillingVat::BASE_KEY . '}'] = $args[Model\BillingVat::BASE_KEY] ?? '';

		return $replacements;
	}

	public function render_control(): void
	{
		$user = wp_get_current_user(); ?>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label><?php echo esc_html(Model\VatControl::get_label()); ?></label>
						<?php (new Model\BillingVat($user->ID))->render_control(); ?>
				</p>
				<?php
	}

	public function save(int $user_id, string $address_type): void
	{
		if (
			'billing' !== $address_type ||
			empty($_POST['woocommerce-edit-address-nonce']) ||
			!wp_verify_nonce($_POST['woocommerce-edit-address-nonce'], 'woocommerce-edit_address')
		) {
			return;
		}

		(new Model\BillingVat($user_id))->save_post_data();
	}
}
