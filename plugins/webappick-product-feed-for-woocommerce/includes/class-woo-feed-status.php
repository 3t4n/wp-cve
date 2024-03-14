<?php

/**
 * Class Woo_Feed_Status
 *
 * @since 5.2.6
 * @author Ohidul Islam [ceo@webappick.com]
 *
 */
class Woo_Feed_Status {

	public $success = '<span style="color: #0a9581" class="dashicons dashicons-yes-alt"></span>';
	public $warning = '<span style="color: #f8b82d" class="dashicons dashicons-warning"></span>';
	public $danger = '<span style="color: #f65021" class="dashicons dashicons-dismiss"></span>';

	/**
	 * Get Status Page Info
	 * @return array
	 */
	public function get_woo_feed_status(){

        $status = woo_feed_get_cached_data( 'woo_feed_status_page_info' );
        if ( false === $status ) {
            $status = [
                $this->woocommerce_version(), // WooCommerce Version
                $this->get_available_product_types(),
                $this->total_products(), // Count Total Products
                $this->product_per_batch(), // Product Per branch
                $this->product_query_type(),
                $this->default_customer_location(),
                $this->server_info(),
                $this->multi_language(),
                $this->multi_currency(),
                $this->wordpress_debug(),
                $this->wordpress_cron(),
                $this->upload_dir_writable(),
                $this->exclude_feed_caching(),
                $this->wordpress_multisite(),
            ];

            $status = array_merge($status, $this->server_info());

            woo_feed_set_cache_data('woo_feed_status_page_info', $status);
        }

        return $status;
	}

	/**
	 * Get CTX Feed Log.
	 */
	public function get_logs( ) {
		$fatal_errors = Woo_Feed_Log_Handler_File::get_log_file_path('woo-feed-fatal-errors');

		if ( file_exists($fatal_errors) && $fatal_errors ) {
			return file_get_contents($fatal_errors); //phpcs:ignore
		}

		return "No Logs Found.";
	}

	/**
	 * Get Wordpress.org plugin information.
	 *
	 * @param $slug
	 *
	 * @return false|mixed
	 */
	private function plugin_info( $slug ) {

		if ( empty($slug) ) {
			return false;
		}

		$args = (object) array(
			'slug'   => $slug,
			'fields' => array(
				'sections'    => false,
				'screenshots' => false,
				'versions'    => false,
			),
		);
		$request = array(
			'action'  => 'plugin_information',
			'request' => serialize( $args), //phpcs:ignore
		);
		$url = 'http://api.wordpress.org/plugins/info/1.0/';
		$response = wp_remote_post( $url, array( 'body' => $request ) );

		if ( is_wp_error($response) ) {
			return false;
		}
		return unserialize( $response['body']); //phpcs:ignore

	}



	/**
	 * Check WordPress multisite status.
	 */
	private function wordpress_multisite( ) {
		$status = $this->success;
		$message = 'No';

		if ( defined( 'WOO_FEED_PRO_VERSION' ) && is_multisite() ) {
			$message = "WordPress Multisite is enabled. If you have hosted your sites into subdomain like fr.example.com then you need a license for each site. If you have five subdomain then you need the Five site license.";
		}

		return [
			'label'   => 'WordPress Multi Site',
			'status'  => $status,
			'message' => $message,
		];
	}

	/**
	 * Get WooCommerce Version Status.
	 */
	private function woocommerce_version( ) {
		$installed_version = (function_exists('WC')) ? WC()->version : '1.0.0';
		$latest_version = $this->plugin_info('woocommerce');

		if ( version_compare($installed_version,'3.0','<') ) {
			$status = $this->danger;
			$message = $installed_version." - You are using a old version of WooCommerce. To use our plugin your WooCommerce version should be 3.0 or later.";
		}elseif ( version_compare($latest_version->version,$installed_version,'>') ) {
			$status = $this->warning;
			$message = $installed_version." - You are not using the latest version of WooCommerce. Update WooCommerce plugin to its latest version: ".$latest_version->version;
		}else {
			$status = $this->success;
			$message = $installed_version." - You are using the latest version of WooCommerce.";
		}

		return [
			'label'   => 'WooCommerce Version',
			'status'  => $status,
			'message' => $message,
		];
	}

	/**
	 * Get Multi Language Status.
	 */
	private function multi_language( ) {
		$status = $this->success;
		$message = "No";
		$doc = '';
		$multi_lang_plugin = '';

		/**
		 * polylang/polylang.php Polylang
		 * polylang-pro/polylang.php Polylang Pro
		 * gtranslate/gtranslate.php  GTranslate
		 * translatepress-multilingual/index.php TranslatePress - Multilingual
		 * weglot/weglot.php Weglot Translate
		 * google-language-translator/google-language-translator.php Google Language Translator
		 * sitepress-multilingual-cms/sitepress.php WPML Multilingual CMS
		 */

		if ( is_plugin_active('sitepress-multilingual-cms/sitepress.php') ) {
			$multi_lang_plugin = 'WPML';
			$doc = "<br/><br/>You can read this doc about: <a href='https://webappick.com/docs/woo-feed/miscellaneous/how-to-make-feed-for-wpml-languages/'>How to make feed for WPML languages?</a>";
		}elseif ( is_plugin_active("polylang/polylang.php") ) {
			$multi_lang_plugin = 'Polylang';
			$doc = "<br/><br/>You can read this doc about: <a href='https://webappick.com/docs/woo-feed/faq-for-woocommerce-product-feed/how-to-make-feed-for-polylang-languages/'>How to make feed for Polylang languages?</a>";
		}elseif ( is_plugin_active("polylang-pro/polylang.php") ) {
			$multi_lang_plugin = 'Polylang Pro';
			$doc = "<br/><br/>You can read this doc about: <a href='https://webappick.com/docs/woo-feed/faq-for-woocommerce-product-feed/how-to-make-feed-for-polylang-languages/'>How to make feed for Polylang languages?</a>";
		}elseif ( is_plugin_active('gtranslate/gtranslate.php') ) {
			$multi_lang_plugin = 'GTranslate';
		}elseif ( is_plugin_active('translatepress-multilingual/index.php') ) {
			$multi_lang_plugin = 'TranslatePress - Multilingual';
		}elseif ( is_plugin_active('weglot/weglot.php') ) {
			$multi_lang_plugin = 'Weglot Translate';
		}elseif ( is_plugin_active('google-language-translator/google-language-translator.php') ) {
			$multi_lang_plugin = 'Google Language Translator';
		}

		if ( ! empty($multi_lang_plugin) ) {
			if ( ! in_array($multi_lang_plugin,[ 'WPML', 'Polylang', 'Polylang Pro', 'TranslatePress - Multilingual' ],true) ) {
				$status = $this->warning;
				$message = "<b>$multi_lang_plugin</b> is not compatible for multi-language feed. You can not make feed for each language. Supported multi-language plugins are <b>WPML</b> & <b>Polylang</b>.";
			}elseif ( is_plugin_active('webappick-product-feed-for-woocommerce/woo-feed.php') ) {
				$status = $this->warning;
				$message = "You are using <b>$multi_lang_plugin</b> for multi language site. But the free version of this plugin is not compatible with $multi_lang_plugin. Using <a target='_blank' href='https://webappick.com/plugin/woocommerce-product-feed-pro/'><b>Woo Feed Pro</b></a> you can make feed for each language.";
				$message .= $doc;
			}else {
				$message = "<b>$multi_lang_plugin</b>";
			}
		}

		return [
			'label'   => 'Multi Language Site',
			'status'  => $status,
			'message' => $message,
		];
	}

	/**
	 * Get Multi Currency Status.
	 */
	private function multi_currency( ) {

		$status = $this->success;
		$message = "No";
		$free_version = is_plugin_active( 'webappick-product-feed-for-woocommerce/woo-feed.php' );

		$supported_multi_currency = "Below Multi Currency Plugins are compatible with <a target='_blank' href='https://webappick.com/plugin/woocommerce-product-feed-pro/'><b>Woo Feed Pro</b></a>. ";
		$supported_multi_currency .= "<br/>";
		$supported_multi_currency .= "<ul>";
		$supported_multi_currency .= "<li>☞  <a href='https://aelia.co/shop/currency-switcher-woocommerce/'>Currency Switcher WooCommerce</a> by Aelia</li>";
		$supported_multi_currency .= "<li>☞  <a href='https://codecanyon.net/item/woocommerce-currency-switcher/8085217'>WooCommerce Currency Switcher</a> by Realmag777</li>";
		$supported_multi_currency .= "<li>☞  <a href='https://wordpress.org/plugins/woocommerce-multilingual/'>WooCommerce Multilingual</a></li>";
		$supported_multi_currency .= "<li>☞  <a href='https://wordpress.org/plugins/polylang/'>Polylang</a></li>";
		$supported_multi_currency .= "<ul/>";

		$multi_currency_plugin = false;
		if ( is_plugin_active( 'woocommerce-aelia-currencyswitcher/woocommerce-aelia-currencyswitcher.php' ) ) {
			$multi_currency_plugin = 'Currency Switcher WooCommerce by Aelia';
		} elseif ( is_plugin_active( 'woocommerce-currency-switcher/index.php' ) ) {
			$multi_currency_plugin = 'WooCommerce Currency Switcher by Realmag777';
		} elseif ( is_plugin_active( 'woocommerce-multilingual/wpml-woocommerce.php' ) ) {
			$multi_currency_plugin = 'WooCommerce Multilingual';
		} elseif ( is_plugin_active( 'polylang/polylang.php' ) ) {
			$multi_currency_plugin = 'PolyLang';
		}elseif ( is_plugin_active( 'polylang-pro/polylang.php' ) ) {
			$multi_currency_plugin = 'PolyLang Pro';
		}


		if ( 'No' !== $this->multi_language()['message'] ) {
			if ( $multi_currency_plugin && $free_version ) {
				$status = $this->warning;
				$message = "<b>$multi_currency_plugin</b> is installed. Free version of Woo Feed does not support multi currency feed. To make feed for individual currency, you need the <a target='_blank' href='https://webappick.com/plugin/woocommerce-product-feed-pro/'><b>Woo Feed Pro</b>.</a>";
			}elseif ( $free_version ) {
				$message = $supported_multi_currency;
			}elseif ( $multi_currency_plugin ) {
				$status = $this->success;
				$message = "<b>$multi_currency_plugin</b><br/><br/>";
				$message .= $supported_multi_currency;
			}
		}


		return [
			'label'   => 'Multi Currency Site',
			'status'  => $status,
			'message' => $message,
		];
	}

	/**
	 * Get PHP Version Status.
	 */
	private function wordpress_debug( ) {
		$message = 'Ok';
		if ( defined('WP_DEBUG') && ! WP_DEBUG ) {
			$status = $this->success;
			$message = "<b>WP_DEBUG</b> is <b>false</b>. If you having any issue with the plugin then set WP_DEBUG to true so that you can find the issue from logs. You can learn more about debugging in WordPress from <a href='https://wordpress.org/support/article/debugging-in-wordpress/'><b>here</b></a>. ";
		}else {
			$status = $this->warning;
			if ( defined('WP_DEBUG_LOG') && ! WP_DEBUG_LOG ) {
				$message = "<b>WP_DEBUG_LOG</b> is <b>false</b>. Plugin can not write error logs if WP_DEBUG_LOG is set to false. You can learn more about debugging in WordPress from <a href='https://wordpress.org/support/article/debugging-in-wordpress/'><b>here</b></a>";
			}
		}

		return [
			'label'   => 'WP DEBUG Status',
			'status'  => $status,
			'message' => $message,
		];
	}

	/**
	 * Get PHP Version Status.
	 */
	private function wordpress_cron( ) {
		$message = 'Ok';
		$status = $this->success;
		if ( defined('DISABLE_WP_CRON') && true === DISABLE_WP_CRON ) {
			$status = $this->warning;
			$message = "WordPress cron is disabled. The <b>Auto Feed Update</b> will not run if WordPress cron is Disabled.";
		}

		return [
			'label'   => 'WP CRON',
			'status'  => $status,
			'message' => $message,
		];
	}

	/**
	 * Get PHP Version Status.
	 */
	private function product_per_batch( ) {
		$status = $this->success;
		$message = woo_feed_get_options('per_batch');
		//TODO AI Message
		return [
			'label'   => 'Product Per Batch',
			'status'  => $status,
			'message' => $message,
		];
	}

    /**
     * Get Product Query Type.
     */
    private function product_query_type( ) {
        $status = $this->success;
        $message = woo_feed_get_options('product_query_type');
        if ( 'wc' == $message ) {
            $message = "WC_Product_Query";
        }elseif ( 'wp' == $message ) {
            $message = "WP_Query";
        }else {
            $message = "Both";
        }
        return [
            'label'   => 'Product Query Type',
            'status'  => $status,
            'message' => $message,
        ];
    }

    /**
     * @return array
     */
    private function server_info( ) {
        $report             = wc()->api->get_endpoint_data( '/wc/v3/system_status' );
        $environment        = $report['environment'];
        $theme              = $report['theme'];
        $active_plugins     = $report['active_plugins'];
        $info = array();
        $info[] = [
            'label'   => '',
            'status'  => '',
            'message' => "<h3>Server Info</h3>",
        ];
        if ( ! empty($environment) ) {
            foreach ( $environment as $key => $value ) {

                if ( true === $value ) {
                    $value = 'Yes';
                }elseif ( false === $value ) {
                    $value = 'No';
                }

                if ( in_array($key,[ 'wp_memory_limit', 'php_post_max_size', 'php_max_input_vars', 'max_upload_size' ]) ) {
                   $value = $this->formatBytes($value,2);
                }

                $info[] = [
                    'label'   => ucwords(str_replace([ '_', 'wp' ],[ ' ', 'WP' ],$key)),
                    'status'  => $this->success,
                    'message' => $value,
                ];
            }
        }

        $info[] = [
            'label'   => '',
            'status'  => '',
            'message' => "<h3>Installed Theme</h3>",
        ];

        if ( ! empty($theme) ) {
            $new_version = "";
            $status = $this->success;
            if ( version_compare($theme['version'],$theme['version_latest']) ) {
                $new_version = ' (Latest:'.$theme['version_latest'].')';
                $status = $this->warning;
            }

            $info[] = [
                'label'   => $theme['name'],
                'status'  => $status,
                'message' => $theme['version'].$new_version,
            ];
        }

        $info[] = [
            'label'   => '',
            'status'  => '',
            'message' => "<h3>Installed Plugins</h3>",
        ];

        if ( ! empty($active_plugins) ) {
            foreach ( $active_plugins as $key => $plugin ) {
                $new_version = "";
                $status = $this->success;
                if ( version_compare($plugin['version'],$plugin['version_latest']) ) {
                    $new_version = ' (Latest:'.$plugin['version_latest'].')';
                    $status = $this->warning;
                }

                $info[] = [
                    'label'   => $plugin['name']. ' ('.$plugin['author_name'].')',
                    'status'  => $status,
                    'message' => $plugin['version'].$new_version,
                ];
            }
        }



        return $info;
    }

    public function formatBytes( $bytes, $precision = 2 ) {
        $units = array( 'B', 'KB', 'MB', 'GB', 'TB' );

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
         $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[ $pow ];
    }

    /**
     * Get WooCommerce General Info.
     */
    private function default_customer_location( ) {
        $status = $this->success;
        $customer_location = get_option('woocommerce_default_customer_address');

        $message = "<b>Default Customer Location</b><br/>";

        if ( 'base' == $customer_location ) {
            $message .= "Shop Base Address<br/>";
        }elseif ( 'geolocation' == $customer_location ) {
            $message .= "Geolocate<br/>";
        }elseif ( 'geolocation_ajax' == $customer_location ) {
            $message .= "Geolocate (with page caching support)<br/>";
        }else {
            $message .= "$customer_location<br/>";
        }


        $message .= "<br/><b>Store Address</b><br/>";
        $message .= get_option('woocommerce_store_address')."<br/>";
        $message .= get_option('woocommerce_store_city')."<br/>";
        $message .= get_option('woocommerce_default_country')."<br/>";
        $message .= get_option('woocommerce_store_postcode')."<br/>";


        return [
            'label'   => 'Default Customer Location',
            'status'  => $status,
            'message' => $message,
        ];
    }

	/**
	 * Get Total Product Status.
	 */
	private function total_products( ) {
		$status = $this->success;
		$message = '';

		$wc_args = array(
			'limit'            => -1,
			'offset'           => 0,
			'status'           => 'publish',
			'type'             => array( 'simple', 'variable', 'grouped', 'external', 'composite', 'bundle', 'yith_bundle', 'yith-composite', 'subscription', 'variable-subscription', 'woosb' ),
			'orderby'          => 'date',
			'order'            => 'DESC',
			'return'           => 'ids',
			'suppress_filters' => false,
		);

		$wp_args = array(
			'posts_per_page'         => -1,
			'post_type'              => 'product',
			'post_status'            => 'publish',
			'order'                  => 'DESC',
			'fields'                 => 'ids',
			'cache_results'          => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'suppress_filters'       => false,
		);

		$wp_query = get_posts($wp_args);
		$wc_query = (new WC_Product_Query($wc_args))->get_products();

		$wc_query_total_product = count($wc_query);
		$wp_query_total_product = count($wp_query);

		$both_query = count(array_unique(array_merge($wp_query,$wc_query)));

		// Total Products By WP Query.
		if ( $wp_query_total_product ) {
			$message .= "WP Query: ".$wp_query_total_product."<br/>";
		}

		// Total Products By WC Product Query.
		if ( $wc_query_total_product ) {
			$message .= "WC Product Query: ".$wc_query_total_product."<br/>";
		}

		// Total Products By Both WP Query & WC Product Query.
		if ( $both_query ) {
			$message .= "Both Query: ".$both_query."<br/>";
		}

		$message .= "<br/><b>Product Total by Types.</b><br/>";

		// Product Totals by Product Type (WP Query)
		$type_totals = $this->get_product_total_by_type();
		if ( ! empty($type_totals) ) {
			foreach ( $type_totals as $type => $total ) {
				$message .= "☞  ". ucwords($type)." Product: ".$total."<br/>";
			}
		}

		// Total Product Variations (WP Query)
		$total_variations = $this->get_total_product_variation();
		if ( $total_variations ) {
			$message .= "☞  Product Variations: ".$total_variations."<br/>";
		}

		return [
			'label'   => 'Total Products',
			'status'  => $status,
			'message' => $message,
		];
	}

	/**
	 * Count total product variations.
	 * @return int
	 */
	private function get_total_product_variation(){
		$args = array(
			'posts_per_page'         => - 1,
			'post_type'              => 'product_variation',
			'post_status'            => 'publish',
			'order'                  => 'DESC',
			'fields'                 => 'ids',
			'cache_results'          => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'suppress_filters'       => false,
		);

		return ( new WP_Query( $args ) )->post_count;
	}

	/**
	 * Count products by type.
	 * @return array
	 */
	private function get_product_total_by_type( ) {
		$product_types = get_terms( 'product_type');
		$product_count = [];
		$args = array(
			'posts_per_page'         => - 1,
			'post_type'              => 'product',
			'post_status'            => 'publish',
			'order'                  => 'DESC',
			'fields'                 => 'ids',
			'cache_results'          => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'suppress_filters'       => false,
		);
		if ( ! empty($product_types) ) {
			foreach ( $product_types as $product_type ) {
				$args['tax_query']  = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => 'product_type',
						'field'    => 'name',
						'terms'    => $product_type->name,
					),
				);
				$product_count[ $product_type->name ] = (new WP_Query($args))->post_count;
			}
		}

		return $product_count;
	}

	/**
	 * @return array
	 */
	public function upload_dir_writable() {
		$status = $this->success;
		$message = "Ok - Writable.";

		$upload_dir = wp_get_upload_dir();
		$feed_dir = $upload_dir['basedir']."/woo-feed";
		if ( ! file_exists($feed_dir) ) {
			$status = $this->danger;
			$message = "Upload directory is not writable. Give the <code>wp-content/uploads/</code> directory file write permission so that the plugin can save the feed file.";
		}
		return [
			'label'   => 'Upload Directory',
			'status'  => $status,
			'message' => $message,
		];
	}

	/**
	 * Get Caching Status.
	 */
	private function exclude_feed_caching( ) {
		$status = $this->success;
		$message = "No.";
		$cache_plugins = array(
			'breeze/breeze.php'                          => 'Breeze',
			'cache-cleaner/cache-cleaner.php'            => 'Cache Cleaner - Scheduled',
			'cache-enabler/cache-enabler.php'            => 'Cache Enabler',
			'cloudflare/cloudflare.php'                  => 'Cloudflare',
			'comet-cache/comet-cache.php'                => 'Comet Cache',
			'hummingbird-performance/wp-hummingbird.php' => 'Hummingbird',
			'hyper-cache/plugin.php'                     => 'Hyper Cache',
			'litespeed-cache/litespeed-cache.php'        => 'LiteSpeed Cache',
			'speed-booster-pack/speed-booster-pack.php'  => 'Speed Booster Pack',
			'swift-performance-lite/performance.php'     => 'Swift Performance Lite',
			'w3-total-cache/w3-total-cache.php'          => 'W3 Total Cache',
			'wp-optimize/wp-optimize.php'                => 'WP-Optimize - Clean, Compress, Cache',
			'wp-cloudflare-page-cache/wp-cloudflare-super-page-cache.php' => 'WP Cloudflare Super Page Cache',
			'wp-fastest-cache/wpFastestCache.php'        => 'WP Fastest Cache',
			'wp-rocket/wp-rocket.php'                    => 'WP Rocket',
			'wp-super-cache/wp-cache.php'                => 'WP Super Cache',
		);
		$active_plugins = get_option('active_plugins');

		$cache_plugins_installed = false;

		foreach ( $active_plugins as $key => $active_plugin ) {
			if ( array_key_exists($active_plugin,$cache_plugins) ) {
				$cache_plugins_installed = $cache_plugins[ $active_plugin ];
			}
		}

		if ( $cache_plugins_installed ) {
			$status = $this->warning;
			$message = "<b>$cache_plugins_installed</b> plugin is installed on your site. Please make sure that feed file URL or directory is excluded from caching. If not excluded, your feed file may not be updated instantly after feed update. Please read this doc about: <a href='https://webappick.com/docs/woo-feed/faq-for-woocommerce-product-feed/how-to-solve-feed-configuration-not-updating-issue/'>How to exclude feed file URL or Directory from caching?</a>";
		}


		return [
			'label'   => 'Cache Plugin Installed',
			'status'  => $status,
			'message' => $message,
		];
	}

    /**
     * Get available woocommerce product types
     */
	private function get_available_product_types(){
        $types = wc_get_product_types();
        $status = $this->success;
        $message = '';
        if ( ! empty($types) ) {
            foreach ( $types as $key => $type ) {
                $message .= "☞  ". ucwords($type)." [".$key."]<br/>";
            }
        }

        return [
            'label'   => 'Product Types',
            'status'  => $status,
            'message' => $message,
        ];
    }
}
