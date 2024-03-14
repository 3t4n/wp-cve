<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Queue;

use DateTimeInterface;

interface Queue {

	public function add( string $hook, array $args = [], string $group = '' ): int;

	public function schedule( DateTimeInterface $time, string $hook, array $args = [], string $group = '');

	public function cancel( int $action_id ): void;

	public function cancel_all( string $hook, array $args = [], string $group = '' ): void;

	public function search( array $args = [] ): array;
}
