<?php
	/**
	 * Class for communicating with the Feedback Company oauth API
	 */
	class feedbackcompany_api
	{
		// default expire times for tokens and caches
		private $expire_days_token = 20;
		private $expire_hours_summary = 1;
		private $expire_days_questions = 20;
		private $expire_days_shopsummary = 1;
		private $expire_days_reviews = 1;
		private $expire_days_productreviews = 5;
		private $expire_days_testimonial = 20;

		// $this->ext is an extension object, with localized functions
		// for interacting with Wordpress
		public $ext = '';

		/**
		 * Constructor is called with instance of 'feedbackcompany_api_ext_wp' as argument
		 *
		 * @param feedbackcompany_api_ext_wp $ext_obj
		 */
		function __construct($ext_obj)
		{
			$this->ext = $ext_obj;

			// get access token if we haven't got one yet
			if (!$this->ext->get_client_option('access_token'))
				$this->oauth_refreshtoken();
		}

		/**
		 * Function for clearing oath authentication cache so a new token is request
		 *
		 * Used from admin.php whenever settings are changed
		 */
		function clear_cache()
		{
			$this->ext->update_client_option('access_token', '');
			$this->ext->delete_cache('lastfailedcall');
		}

		/**
		 * Function for oauth authentication
		 *
		 * Gets a new access token if we don't have one or if our current isn't valid anymore
		 */
		function oauth_refreshtoken()
		{
			if (!$this->ext->get_locale_option('oauth_client_id')
				|| !$this->ext->get_locale_option('oauth_client_secret'))
				return;

			// don't attempt another call if there was a failed call in the last 1800 seconds (half hour)
			// note last failed call time is reset via this::clear_cache() from admin.php if oauth is changed
			$lastfailedcall = $this->ext->get_cache('lastfailedcall');
			if ($lastfailedcall !== false && time() < $lastfailedcall + 1800)
				return;

			$url = 'https://www.feedbackcompany.com/api/v2/oauth2/token'
				. '?client_id='.$this->ext->get_locale_option('oauth_client_id')
				. '&client_secret='.$this->ext->get_locale_option('oauth_client_secret')
				. '&grant_type=authorization_code';

			$result = $this->http_request($url);

			// store access token if successful and check account
			if (isset($result->access_token))
			{
				$this->ext->update_client_option('access_token', $result->access_token);

				// check if product reviews are enabled
				$this->check_product_reviews_enabled();
			}
			// if not, register the time of this failed call
			else
				$this->ext->set_cache('lastfailedcall', time(), 1800);
		}

		/**
		 * Function for making api calls
		 *
		 * @param string $url - the URL to call
		 * @param array $postdata - the data to POST
		 * @param bool $retry - set to false on recursive requests to prevent looping
		 */
		function http_request($url, $postdata = '', $method = null, $retry = true)
		{
			// if oauth credentials aren't present, stop
			if (!$this->ext->get_locale_option('oauth_client_id')
				|| !$this->ext->get_locale_option('oauth_client_secret'))
			{
				return false;
			}

			$args = array();
			$args['headers'] = array();

			// add token to request
			if ($this->ext->get_client_option('access_token'))
				$args['headers']['Authorization'] = 'Bearer '.$this->ext->get_client_option('access_token');

			// add the postdata
			$encoded_postdata = "";
			if ($postdata)
			{
				if (!$method)
					$method = 'POST';

				$encoded_postdata = json_encode($postdata);
				$args['headers']['Content-Type'] = 'application/json; charset=utf-8';
				$args['body'] = $encoded_postdata;
			}

			// default method is GET
			if (!$method)
				$method = 'GET';

			// set method
			$args['method'] = $method;

			// make the call
			$http_response = wp_remote_request($url, $args);

			// if no response due to HTTP error
			if (is_wp_error($http_response))
			{
				$this->ext->log_apierror($url, $method.' '.json_encode($args['headers']).' '.$encoded_postdata, 'WP_Error: '.$http_response->get_error_message());
				return false;
			}

			// decode response
			$response = json_decode($http_response['body']);

			// log the call & response if debug mode is enabled
			if (true == $this->ext->debug())
			{
				$this->ext->log_apierror($url, $method.' '.json_encode($args['headers']).' '.$encoded_postdata, $http_response['body']);
			}

			// if token has somehow become invalid or expired, delete token and retry request
			if (wp_remote_retrieve_response_code($http_response) == 401 && $retry)
			{
				$this->ext->update_client_option('access_token', '');
				$this->oauth_refreshtoken();
				return $this->http_request($url, $postdata, $method, false);
			}

			// log errors if response is not what we expected
			if (null === $response || (isset($response->error) && true === $response->error) || (isset($response->success) && false === $response->success))
			{
				$this->ext->log_apierror($url, $method.' '.json_encode($args['headers']).' '.$encoded_postdata, $http_response['body']);
			}

			return $response;
		}

		/**
		 * Function checks if product reviews are enabled for this account and stores the result
		 */
		function check_product_reviews_enabled()
		{
			$url = 'https://www.feedbackcompany.com/api/v2/shop';
			$result = $this->http_request($url, '', 'GET');

			// no valid result, stop here
			if ($result == null || $result == " " || !is_object($result) || $result->success !== true)
				return;

			// store if product reviews are enabled or not in our config
			if ($result->shop->productReviewsEnabled)
			{
				$this->ext->update_client_option('productreviews_enabled', true);
			}
			else
			{
				$this->ext->update_client_option('productreviews_enabled', false);
				foreach (array('product-summary', 'product-extended') as $widget_type)
				{
					$this->ext->delete_client_option('widget_uuid_' . $widget_type);
					$this->ext->delete_client_option('widget_id_' . $widget_type);
				}
			}
		}

		/**
		 * Functions for registering v2 widgets
		 *
		 * @param string $type - the type of widget to register
		 * @param array $options - options of the widget
		 * @param bool $force_refresh - if true forces registering of new widgets
		 */
		function register_widget($type, $options = null, $force_refresh = false)
		{
			$url = 'https://www.feedbackcompany.com/api/v2/widgets';

			$postdata = array('type' => $type);
			if ($options != null)
				$postdata['options'] = $options;

			// if force_refresh is true or if no widget id can be found, register new widgets
			if ($force_refresh || !$this->ext->get_client_option('widget_id_'.$type))
			{
				$result = $this->http_request($url, $postdata);
			}
			// if force_refresh is false or an existing widget id is found, update the widget id
			else
			{
				$url .= '/'.$this->ext->get_client_option('widget_id_'.$type);
				$postdata['uuid'] = $this->ext->get_client_option('widget_uuid_'.$type);
				$postdata['id'] = $this->ext->get_client_option('widget_id_'.$type);
				$result = $this->http_request($url, $postdata, 'PUT');
			}

			// check response and write uuid and id to options
			if (is_object($result) && isset($result->widget) && isset($result->widget->uuid) && $result->widget->uuid != false)
			{
				$this->ext->update_client_option('widget_uuid_'.$type, $result->widget->uuid);
				$this->ext->update_client_option('widget_id_'.$type, $result->widget->id);
			}
		}

		/**
		 * Function for registering main (badge) widget
		 *
		 * @param  widget options that are sent directly to Feedback Company API
		 */
		function register_widget_main($size = 'small', $amount = 0, $force_refresh = false)
		{
			$options = array('size' => $size, 'amount_of_reviews' => intval($amount));
			$this->register_widget('main', $options, $force_refresh);
		}

		/**
		 * Function for registering bar widget
		 *
		 * @param  widget options that are sent directly to Feedback Company API
		 */
		function register_widget_bar($force_refresh = false)
		{
			$options = array();
			$this->register_widget('bar', $options, $force_refresh);
		}

		/**
		 * Function for registering sticky (floating) widget
		 *
		 * @param  widget options that are sent directly to Feedback Company API
		 */
		function register_widget_sticky($force_refresh = false)
		{
			$options = array();
			$this->register_widget('sticky', $options, $force_refresh);
		}

		/**
		 * Function for registering product summary widget
		 */
		function register_widget_productsummary($force_refresh = false)
		{
			$this->register_widget('product-summary', null, $force_refresh);
		}

		/**
		 * Function for registering product extended widget
		 *
		 * @param  widget options that are sent directly to Feedback Company API
		 */
		function register_widget_productextended($displaytype = 'sidebar', $force_refresh = false)
		{
			$options = array('display_type' => $displaytype);
			$this->register_widget('product-extended', $options, $force_refresh);
		}

		/**
		 * Functions for outputting v2 widgets
		 *
		 * @param string $type - type of widget
		 * @param string $url_params - custom URL parameters
		 * @param string $template_params - custom template parameters
		 */
		function get_widget($type, $url_params = null, $template_params = null)
		{
			// if there is no uuid, try to register the widget
			if (!$this->ext->get_client_option('widget_uuid_'.$type))
			{
				// sanitize $type for our method names
				$methodname = preg_replace("/[^a-zA-Z0-9]+/", "", $type);
				if (method_exists($this, 'register_widget_'.$methodname))
					$this->{'register_widget_'.$methodname}();
			}

			// get our widget data
			$data = array();
			$data['uuid'] = $this->ext->get_client_option('widget_uuid_'.$type);
			$data['prefix'] = uniqid();
			$data['version'] = '1.2.1';
			$data['toggle_element'] = $this->ext->get_locale_option('productreviewsextendedwidget_toggle_element');

			// if there is no uuid, don't output anything
			if (!$data['uuid'])
				return '';

			// start output
			$out = '<!-- Feedback Company Widget (start) -->';

			// include the Feedback Company javascript if it wasn't included on a previous widget
			static $javascript;
			if (!$javascript)
			{
				$javascript = true;
				$out .= '<script type="text/javascript" src="https://www.feedbackcompany.com/widgets/feedback-company-widget.min.js"></script>';
			}

			// output our widget
			if ($url_params !== null)
				$data['urlParams'] = $url_params;
			if ($template_params !== null)
				$data['templateParams'] = $template_params;

			$out .=   '<script type="text/javascript" id="__fbcw__'.$data['prefix'].$data['uuid'].'">'
				. '"use strict";!function(){'
				. 'window.FeedbackCompanyWidgets=window.FeedbackCompanyWidgets||{queue:[],loaders:['
				. ']};var options='.json_encode($data).';if('
				. 'void 0===window.FeedbackCompanyWidget){if('
				. 'window.FeedbackCompanyWidgets.queue.push(options),!document.getElementById('
				. '"__fbcw_FeedbackCompanyWidget")){var scriptTag=document.createElement("script")'
				. ';scriptTag.onload=function(){if(window.FeedbackCompanyWidget)for('
				. ';0<window.FeedbackCompanyWidgets.queue.length;'
				. ')options=window.FeedbackCompanyWidgets.queue.pop(),'
				. 'window.FeedbackCompanyWidgets.loaders.push('
				. 'new window.FeedbackCompanyWidgetLoader(options))},'
				. 'scriptTag.id="__fbcw_FeedbackCompanyWidget",'
				. 'scriptTag.src="https://www.feedbackcompany.com/includes/widgets/feedback-company-widget.min.js"'
				. ',document.body.appendChild(scriptTag)}'
				. '}else window.FeedbackCompanyWidgets.loaders.push('
				. 'new window.FeedbackCompanyWidgetLoader(options))}();'
				. '</script>'
				. '<!-- Feedback Company Widget (end) -->';

			return $out;
		}

		/**
		 * Function for outputting badge widget
		 */
		function get_widget_main()
		{
			return $this->get_widget('main');
		}
		function output_widget_main()
		{
			echo $this->get_widget('main');
		}

		/**
		 * Function for outputting bar widget
		 */
		function get_widget_bar()
		{
			return $this->get_widget('bar');
		}
		function output_widget_bar()
		{
			echo $this->get_widget('bar');
		}

		/**
		 * Function for outputting sticky widget if enabled
		 */
		function get_widget_sticky()
		{
			return $this->get_widget('sticky');
		}
		function output_widget_sticky()
		{
			echo $this->get_widget('sticky');
		}

		/**
		 * Function for outputting product summary widget
		 *
		 * @params  are sent directly to Feedback Company API
		 */
		function get_widget_productsummary($product_id, $product_name, $product_url, $product_image_url)
		{
			$url_params = array('product_external_id' => $product_id);
			$template_params = array('product_name' => $product_name, 'product_url' => $product_url, 'product_image_url' => $product_image_url);
			return $this->get_widget('product-summary', $url_params, $template_params);
		}

		/**
		 * Function for outputting product extended widget
		 *
		 * @params  are sent directly to Feedback Company API
		 */
		function get_widget_productextended($product_id, $product_name, $product_url, $product_image_url)
		{
			$url_params = array('product_external_id' => $product_id);
			$template_params = array('product_name' => $product_name, 'product_url' => $product_url, 'product_image_url' => $product_image_url);
			return $this->get_widget('product-extended', $url_params, $template_params);
		}

		/**
		 * Function for registering an order with Feedback Company so reminders will be sent
		 *
		 * @param array $orderdata - data for this call, created on woocommerce.php
		 */
		function register_order($orderdata, $platform = null)
		{
			$orderdata['invitation'] = array();

			// set invitation options
			$orderdata['invitation']['delay'] = array(
					'unit' => $this->ext->get_locale_option('invitation_delay_unit'),
					'amount' => intval($this->ext->get_locale_option('invitation_delay'))
			);

			// request reminders only if they are enabled
			if ($this->ext->get_locale_option('invitation_reminder_enabled') === "1")
			{
				$orderdata['invitation']['reminder'] = array(
					'unit' => $this->ext->get_locale_option('invitation_reminder_unit'),
					'amount' => intval($this->ext->get_locale_option('invitation_reminder'))
				);
			}

			$url = 'https://www.feedbackcompany.com/api/v2/orders';
			if (null !== $platform)
				$url .= '?platform='.$platform;

			return $this->http_request($url, $orderdata);
		}

		/**
		 * Function for getting structured data (review scores) for a specific product ID
		 *
		 * @param int $product_id - the ID of the product
		 */
		function get_product_reviews_aggregate($product_id)
		{
			$data = $this->ext->get_cache('productreviewsaggregate_'.$product_id);
			if ($data === false)
			{
				$url = 'https://www.feedbackcompany.com/api/v2/product-reviews/aggregate?product_external_id='.$product_id;

				$result = $this->http_request($url);
				if (!$result || !isset($result->success) || !$result->success)
					return;

				$data = array(
					'amount' => $result->product_review_aggregate->amount_of_reviews,
					'score' => $result->product_review_aggregate->average_score / 2,
				);

				// minimum score is 1
				if ($data['score'] < 1)
					$data['score'] = 1;

				$this->ext->set_cache('productreviewsaggregate_'.$product_id, $data, 86400 * $this->expire_days_productreviews);
			}

			return $data;
		}
	}
