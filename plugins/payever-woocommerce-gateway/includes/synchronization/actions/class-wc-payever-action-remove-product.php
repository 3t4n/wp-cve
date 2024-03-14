<?php

if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Synchronization_Action_Handler_RemoveProduct' ) ) {
	return;
}

use Payever\Sdk\Products\Http\RequestEntity\ProductRemovedRequestEntity;
use Payever\Sdk\ThirdParty\Action\ActionHandlerInterface;
use Payever\Sdk\ThirdParty\Action\ActionPayload;
use Payever\Sdk\ThirdParty\Action\ActionResult;
use Payever\Sdk\ThirdParty\Enum\ActionEnum;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class WC_Payever_Synchronization_Action_Handler_RemoveProduct implements ActionHandlerInterface, LoggerAwareInterface {

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
	 * @inheritdoc
	 */
	public function getSupportedAction() {
		return ActionEnum::ACTION_REMOVE_PRODUCT;
	}

	/**
	 * @inheritdoc
	 */
	public function handle( ActionPayload $action_payload, ActionResult $action_result ) {
		/** @var ProductRemovedRequestEntity $productRemovedEntity */
		$productRemovedEntity = $action_payload->getPayloadEntity();
		$sku                  = $productRemovedEntity->getSku();

		$this->_logger->info( sprintf( 'Product will be removed SKU=%s', $sku ) );

		$wc_product_id = $this->get_wp_wrapper()->wc_get_product_id_by_sku( $sku ) ?:
			WC_Payever_Helper::instance()->get_product_variation_id_by_sku( $sku );
		if ( ! $wc_product_id ) {
			throw new \UnexpectedValueException( sprintf( 'Product not found by SKU=%s', $sku ) );
		}
		$this->delete_product( $wc_product_id );
		$action_result->incrementDeleted();
		$this->_logger->info( sprintf( 'Product SKU=%s has been removed', $sku ) );
	}

	/**
	 * @param $wc_product_id
	 * @return void
	 */
	private function delete_product( $wc_product_id ) {
		if ( $this->get_wp_wrapper()->version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			$wc_product = $this->get_wp_wrapper()->wc_get_product( $wc_product_id );
			$wc_product->delete();

			return;
		}
		$this->get_wp_wrapper()->wp_trash_post( $wc_product_id );
	}
}
