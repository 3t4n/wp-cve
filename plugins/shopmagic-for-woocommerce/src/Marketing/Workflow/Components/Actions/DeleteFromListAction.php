<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Marketing\Workflow\Components\Actions;

use WPDesk\ShopMagic\Exception\CannotModifyList;
use WPDesk\ShopMagic\Exception\CannotProvideItemException;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SingleListSubscriber;

final class DeleteFromListAction extends AbstractListAction {
	public function get_id(): string {
		return 'shopmagic_delete_from_list_action';
	}

	public function get_name(): string {
		return esc_html__( 'Delete E-mail from List', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return esc_html__('Remove client from marketing list by email.', 'shopmagic-for-woocommerce');
	}

	protected function do_list_action( string $email, int $list_id, string $list_name ): bool {
		try {
			/** @var SingleListSubscriber $customer_opt */
			$customer_opt = $this->repository->get_subscribed_to_list( $email, $list_id );
			if ( ! $customer_opt->is_active() ) {
				return false;
			}

			$customer_opt->set_active( false );
			$this->manager->save( $customer_opt );
		} catch ( CannotProvideItemException $cannotProvideItemException ) {
			throw new CannotModifyList(
				sprintf(
					// translators: %1$s Customer email, %2$s Marketing List name.
					esc_html__( 'There\'s no customer with email %1$s subscribed to list: %2$s.', 'shopmagic-for-woocommerce' ),
					$email,
					$list_name
				)
			);
		}

		if ( $this->logger !== null ) {
			$this->logger->info(
				sprintf(
					// translators: %1$s Customer email, %2$s Marketing List name.
					esc_html__( 'Customer email %1$s successfully deleted from list: %2$s.', 'shopmagic-for-woocommerce' ),
					$email,
					$list_name
				)
			);
		}

		return true;
	}
}
