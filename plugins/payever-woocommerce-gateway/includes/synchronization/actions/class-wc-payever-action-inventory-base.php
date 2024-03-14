<?php

if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Synchronization_Action_Handler_InventoryBase' ) ) {
	return;
}

use Payever\Sdk\ThirdParty\Action\ActionHandlerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

abstract class WC_Payever_Synchronization_Action_Handler_InventoryBase implements ActionHandlerInterface, LoggerAwareInterface {

	use WC_Payever_WP_Wrapper_Trait;

	/** @var LoggerInterface */
	protected $_logger;

	/**
	 * @inheritDoc
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->_logger = $logger;
	}

	/**
	 * @param string $sku
	 * @param int $expectedResult
	 * @param int|null $diff +/-
	 *
	 * @throws \Exception
	 */
	public function change_stock( $sku, $expectedResult, $diff = null ) {
		$this->_logger->info(
			sprintf( 'Inventory qty will change for SKU=%s, expected=%d diff=%d', $sku, $expectedResult, $diff )
		);

		$wc_product_id = $this->get_wp_wrapper()->wc_get_product_id_by_sku( $sku ) ?: WC_Payever_Helper::instance()->get_product_variation_id_by_sku( $sku );
		if ( ! $wc_product_id ) {
			throw new \UnexpectedValueException( sprintf( 'Product not found by SKU=%s', $sku ) );
		}
		$wc_product = $this->get_wp_wrapper()->wc_get_product( $wc_product_id );
		$update_result = $this->update_product_stock( $wc_product, $diff, $expectedResult, $wc_product_id );
		if ( $update_result ) {
			return $update_result;
		}

		$new_stock    = $this->get_wp_wrapper()->get_post_meta( $wc_product_id, '_stock', true );
		$stock_status = $new_stock > 0 ? 'instock' : 'outofstock';
		$this->get_wp_wrapper()->update_post_meta( $wc_product_id, '_stock_status', $stock_status );

		$this->_logger->info(
			sprintf(
				'Inventory qty changed for SKU=%s, diff=%d, new qty=%d, inStock=%d',
				$sku,
				$diff,
				$new_stock,
				$stock_status
			)
		);

		return $new_stock;
	}

	/**
	 * @param $wc_product
	 * @param $diff
	 * @param $expectedResult
	 * @param $wc_product_id
	 * @return bool|int|void|null
	 */
	private function update_product_stock( $wc_product, $diff, $expectedResult, $wc_product_id ) {
		if ( $this->get_wp_wrapper()->version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			if ( 'yes' === $wc_product->get_manage_stock() && null !== $diff ) {
				$diff = (float) $diff;
				if ( abs( $diff ) === $diff ) {
					return $this->get_wp_wrapper()->wc_update_product_stock( $wc_product, $diff, 'increase', true );
				}

				return $this->get_wp_wrapper()->wc_update_product_stock( $wc_product, abs( $diff ), 'decrease', true );
			}
			$wc_product->set_manage_stock( 'yes' );
			$wc_product->set_stock_quantity( $expectedResult );
			$wc_product->save();
			return;
		}
		$do_process_diff = 'yes' === get_post_field( '_manage_stock', $wc_product_id, 'db' );
		if ( $do_process_diff && null !== $diff ) {
			$stock = get_post_field( '_stock', $wc_product_id, 'db' );
			wc_update_product_stock( $wc_product_id, (float) $stock + (float) $diff );
			return;
		}
		update_post_meta( $wc_product_id, '_manage_stock', 'yes' );
		wc_update_product_stock( $wc_product_id, $expectedResult );
	}
}
