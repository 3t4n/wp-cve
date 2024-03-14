<?php

namespace ZPOS\Model;

class BillingVat
{
	public const BASE_KEY = 'billing_tax_vat';
	public const TYPE_KEY = '_' . self::BASE_KEY . '_type';
	public const ID_KEY = '_' . self::BASE_KEY . '_id';

	private $order;
	private $order_id = 0;
	private $customer_id = 0;
	private $is_native_order_saving;

	public function __construct(/* \WC_Order|int */ $initiator, bool $is_native_order_saving = false)
	{
		if ($initiator instanceof \WC_Order) {
			$this->order = $initiator;
			$this->order_id = $initiator->get_id();
			$this->customer_id = $initiator->get_customer_id();
			$this->is_native_order_saving = $is_native_order_saving;
		} elseif (is_int($initiator)) {
			if (!get_userdata($initiator) && 0 !== $initiator) {
				throw new \Exception('Customer with ID ' . $initiator . 'doesn\'t exists.');
			}

			$this->customer_id = $initiator;
		} else {
			throw new \Exception(
				'An instance of the WC_Order class or an integer (customer ID) must be passed.'
			);
		}
	}

	public function get_formatted_data(): string
	{
		$type_code = $this->get_type_code();
		$id = $this->get_id();

		if (empty($type_code) && empty($id)) {
			return '';
		}

		return (empty($type_code) ? '' : $type_code . ' - ') . $id;
	}

	public function get_type_code(): string
	{
		$types = VatControl::get_types();
		$type = $this->get_data(self::TYPE_KEY);

		if (empty($type) || !in_array($type, array_keys($types), true)) {
			return '';
		}

		return $types[$type]['code'];
	}

	public function get_type(): string
	{
		return $this->get_data(self::TYPE_KEY);
	}

	public function get_id(): string
	{
		return $this->get_data(self::ID_KEY);
	}

	public function add_type(string $value): void
	{
		$this->add_data(self::TYPE_KEY, $value);
	}

	public function add_id(string $value): void
	{
		$this->add_data(self::ID_KEY, $value);
	}

	public function save_post_data(): void
	{
		$clear_key = self::TYPE_KEY . '_clear';

		if (isset($_POST[$clear_key]) && '1' === $_POST[$clear_key]) {
			$this->add_type('');
		} elseif (isset($_POST[self::TYPE_KEY])) {
			$this->add_type($_POST[self::TYPE_KEY]);
		}

		if (isset($_POST[self::ID_KEY])) {
			$this->add_id($_POST[self::ID_KEY]);
		}
	}

	public function render_control(bool $is_required = false): void
	{
		VatControl::render(
			self::TYPE_KEY,
			$this->get_type(),
			self::ID_KEY,
			$this->get_id(),
			$is_required
		);
	}

	private function get_data(string $key): string
	{
		if (0 !== $this->order_id) {
			$data = $this->order->get_meta($key);

			if ($data) {
				return $data;
			}
		}

		if (0 !== $this->customer_id) {
			return get_user_meta($this->customer_id, $key, true);
		}

		return '';
	}

	private function add_data(string $key, string $value): void
	{
		$value = sanitize_text_field(wp_unslash($value));

		if (0 !== $this->order_id) {
			$this->order->update_meta_data($key, $value);

			if (!$this->is_native_order_saving) {
				$this->order->save();
			}
		}

		if (0 !== $this->customer_id) {
			update_user_meta($this->customer_id, $key, $value);
		}
	}
}
