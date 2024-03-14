<?php

namespace ZPOS\Model;

class SplitOrder
{
	private const PARENT_ID_KEY = 'zpos_split_parent_id';
	private const CHILD_IDS_KEY = 'zpos_split_child_ids';

	private $parent;
	private $children = [];

	public function __construct(/* int|\WC_Order */ $order_id)
	{
		if ($order_id instanceof \WC_Order) {
			$order = $order_id;
		} else {
			$order = wc_get_order($order_id);
		}

		if (!$order instanceof \WC_Order) {
			throw new \Exception("Order #$order_id not found");
		}

		$child_ids = $order->get_meta(self::CHILD_IDS_KEY);

		if (is_array($child_ids)) {
			$this->parent = $order;

			$this->set_children($child_ids);

			return;
		}

		$parent_id = $order->get_meta(self::PARENT_ID_KEY);

		if (is_int($parent_id)) {
			$order = wc_get_order($parent_id);

			if (!$order instanceof \WC_Order) {
				throw new \Exception("Parent order #$parent_id not found");
			}

			$this->parent = $order;
			$child_ids = $order->get_meta(self::CHILD_IDS_KEY);

			$this->set_children($child_ids);

			return;
		}

		$this->parent = $order;
	}

	public static function is_split_order(\WC_Order $order): bool
	{
		return $order->get_meta(self::PARENT_ID_KEY) || $order->get_meta(self::CHILD_IDS_KEY);
	}

	public function get_parent(): \WC_Order
	{
		return $this->parent;
	}

	public function get_children(): array
	{
		return $this->children;
	}

	public function get_child(string $payment_method_id): ?\WC_Order
	{
		if (empty($this->children[$payment_method_id])) {
			return null;
		}

		return $this->children[$payment_method_id];
	}

	public function split(SplitPayment ...$split_payments): void
	{
		$children = array_map([$this, 'handle_child'], $split_payments);

		$this->handle_parent(...$children);
	}

	public function calculate(): void
	{
	}

	private function set_children(array $order_ids): void
	{
		foreach ($order_ids as $order_id) {
			$order = wc_get_order($order_id);

			if (!$order instanceof \WC_Order) {
				throw new \Exception("Child order #$order_id not found");
			}

			$payment_method = $order->get_payment_method();

			if (!$payment_method) {
				throw new \Exception("Child order #$order_id has no payment method");
			}

			$this->children[$payment_method] = $order;
		}
	}

	private function handle_child(SplitPayment $split_payment): \WC_Order
	{
		$payment_method_id = $split_payment->get_method_id();

		if (isset($this->children[$payment_method_id])) {
			$child = $this->children[$payment_method_id];
		} else {
			$child = new \WC_Order();
			$child->set_payment_method($payment_method_id);
		}

		$parent_id = $child->get_meta(self::PARENT_ID_KEY);

		if (!$parent_id) {
			$child->update_meta_data(self::PARENT_ID_KEY, $this->parent->get_id());
		}

		$child->set_total($split_payment->get_amount());
		$child->save();

		$this->children[$payment_method_id] = $child;

		return $child;
	}

	private function handle_parent(\WC_Order ...$children): void
	{
		$child_ids = $this->parent->get_meta(self::CHILD_IDS_KEY) ?? [];
		$total = $this->parent->get_total();

		foreach ($children as $child) {
			$child_id = $child->get_id();
			$total -= $child->get_total();

			if (!in_array($child_id, $child_ids, true)) {
				$child_ids[] = $child_id;
			}
		}

		$this->parent->set_total($total);
		$this->parent->update_meta_data(self::CHILD_IDS_KEY, $child_ids);
		$this->parent->save();
	}
}
