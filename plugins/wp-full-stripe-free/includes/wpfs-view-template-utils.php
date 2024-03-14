<?php

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2022.04.20.
 * Time: 15:23
 */
class MM_WPFS_ViewTemplateUtils {

	/**
	 * @param MM_WPFS_CustomerPortalModel $model
	 */
	public static function fullstripe_customer_portal_header( $model ) {
		$headerContent = '';
		try {
			$headerContent = apply_filters(
				MM_WPFS::FILTER_NAME_CUSTOMER_PORTAL_HEADER,
				$headerContent,
				array(
					'email'            => $model->getCustomerEmail(),
					'stripeCustomerId' => $model->getStripeCustomer()->id,
					'stripeClient'     => $model->getStripeClient()->getStripeClient()
				)
			);
		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e );
		}
		echo $headerContent;
	}

	/**
	 * @param MM_WPFS_CustomerPortalModel $model
	 */
	public static function fullstripe_customer_portal_footer( $model ) {
		$footerContent = '';
		try {
			$footerContent = apply_filters(
				MM_WPFS::FILTER_NAME_CUSTOMER_PORTAL_FOOTER,
				$footerContent,
				array(
					'email'            => $model->getCustomerEmail(),
					'stripeCustomerId' => $model->getStripeCustomer()->id,
					'stripeClient'     => $model->getStripeClient()->getStripeClient()
				)
			);
		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e );
		}
		echo $footerContent;
	}


}