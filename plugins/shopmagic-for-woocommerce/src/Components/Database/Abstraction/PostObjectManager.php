<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Database\Abstraction;

use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectPersister;
use WPDesk\ShopMagic\Helper\CapabilitiesCheckTrait;

/**
 * @template T of object
 * @template-implements ObjectPersister<T>
 */
abstract class PostObjectManager implements DAO\ObjectPersister {
	use CapabilitiesCheckTrait;

	/** @var DAO\ObjectRepository<T> */
	protected $repository;

	/**
	 * @param DAO\ObjectRepository<T> $repository
	 */
	public function __construct(
		DAO\ObjectRepository $repository
	) {
		$this->repository = $repository;
	}

	public function get_repository(): DAO\ObjectRepository {
		return $this->repository;
	}

	public function find( $id ): object {
		return $this->repository->find( $id );
	}

	/**
	 * @param object    $item
	 *
	 * @phpstan-param T $item
	 * @return bool
	 * @throws PersisterException
	 */
	public function save( object $item ): bool {
		$this->check_permissions( $item );

		return $this->do_save( $item );
	}

	/**
	 * @param object    $item
	 *
	 * @phpstan-param T $item
	 * @return void
	 * @throws PersisterException
	 */
	private function check_permissions( object $item ): void {
		if ( ! $this->can_handle( $item ) ) {
			throw new ForbiddenEntity(
				sprintf(
					'Object manager `%s` is not capable of persisting instance of `%s` class',
					self::class,
					get_class( $item )
				)
			);
		}
		if ( ! $this->can_modify() ) {
			throw new InsufficientPermission(
				__( 'You are not allowed to modify this post. Check your permissions.',
					'shopmagic-for-woocommerce' )
			);
		}
		if ( $this->is_expected_post_type( $item ) ) {
			throw new InvalidEntity(
				__(
					'Expected resource type mismatch. You probably try to access resource of other kind than requested.',
					'shopmagic-for-woocommerce'
				)
			);
		}
	}

	private function can_modify(): bool {
		return (bool) $this->allowed_capability();
	}

	/**
	 * @param T $item
	 *
	 * @phpstan-assert-if-true T $item
	 * @return bool
	 */
	public function is_expected_post_type( object $item ): bool {
		$expected_post_type = $this->expected_post_type();

		return $item->get_id() !== null &&
		       $expected_post_type !== null &&
		       get_post( $item->get_id() )->post_type !== $expected_post_type;
	}

	protected function expected_post_type(): ?string {
		return null;
	}

	/**
	 * @param T $item
	 *
	 * @return bool
	 */
	abstract protected function do_save( object $item ): bool;

	/**
	 * @throws PersisterException
	 */
	public function delete( object $item ): bool {
		$this->check_permissions( $item );

		return $this->do_delete( $item );
	}

	/**
	 * @param T $item
	 *
	 * @return bool
	 */
	abstract protected function do_delete( object $item ): bool;

	public function refresh( object $item ): object {
		return $item;
	}
}
