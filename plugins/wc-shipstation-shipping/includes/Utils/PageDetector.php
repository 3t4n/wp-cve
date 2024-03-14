<?php
/*********************************************************************/
/* PROGRAM    (C) 2022 FlexRC                                        */
/* PROPERTY   604-1097 View St                                        */
/* OF         Victoria, BC, V8V 0G9                                   */
/*            CANADA                                                 */
/*            Voice (604) 800-7879                                   */
/*********************************************************************/

namespace OneTeamSoftware\WooCommerce\Utils;

defined('ABSPATH') || exit; // Exit if accessed directly

if (!class_exists(__NAMESPACE__ . '\\PageDetector')):

class PageDetector
{
	public function isCart()
	{
		$isCart = false;
		if (function_exists('wc_get_cart_url')) {
			$isCart = $this->isRequestForUrl(wc_get_cart_url());
		}

		return $isCart;
	}

	public function isCheckout()
	{
		$isCheckout = false;
		if (function_exists('wc_get_checkout_url')) {
			$isCheckout = $this->isRequestForUrl(wc_get_checkout_url());
		}

		return $isCheckout;
	}

	private function isRequestForUrl($url)
	{
		$isRequestForUrl = false;
		$url = rtrim($url, '/');

		if (defined('DOING_AJAX') && DOING_AJAX) {
			if (isset($_SERVER['HTTP_REFERER']) && $url == rtrim(preg_replace('/\?.*/', '', $_SERVER['HTTP_REFERER']), '/')) {
				$isRequestForUrl = true;
			}	
		} else {
			$requestedUrl = sprintf('%s://%s%s',
				(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http'),
				isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '',
				isset($_SERVER['REQUEST_URI']) ? preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']) : ''
			);

			$requestedUrl = rtrim($requestedUrl, '/');
			
			if ($url == $requestedUrl) {
				$isRequestForUrl = true;
			}
		}
		
		return $isRequestForUrl;
	}
}

endif;