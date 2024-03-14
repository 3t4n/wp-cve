<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooNotify_Bulk_360Messenger' ) && class_exists( 'WooNotify_360Messenger_Bulk' ) ) {
	class WooNotify_Bulk_360Messenger extends WooNotify_360Messenger_Bulk {

		public function __call( $name, $arguments ) {
			if ( method_exists( $this, $name ) ) {
				return call_user_func_array( [ $this, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}

		public static function __callStatic( $name, $arguments ) {
			if ( method_exists( __CLASS__, $name ) ) {
				return call_user_func_array( [ __CLASS__, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}
	}
}

if ( ! class_exists( 'WooNotify_Gateways_360Messenger' ) && class_exists( 'WooNotify_360Messenger_Gateways' ) ) {
	class WooNotify_Gateways_360Messenger extends WooNotify_360Messenger_Gateways {

		public function __call( $name, $arguments ) {
			if ( method_exists( $this, $name ) ) {
				return call_user_func_array( [ $this, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}

		public static function __callStatic( $name, $arguments ) {
			if ( method_exists( __CLASS__, $name ) ) {
				return call_user_func_array( [ __CLASS__, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}
	}
}

if ( ! class_exists( 'WooNotify_Helper' ) && class_exists( 'WooNotify_360Messenger_Helper' ) ) {
	class WooNotify_Helper extends WooNotify_360Messenger_Helper {

		public function __call( $name, $arguments ) {
			if ( method_exists( $this, $name ) ) {
				return call_user_func_array( [ $this, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}

		public static function __callStatic( $name, $arguments ) {
			if ( method_exists( __CLASS__, $name ) ) {
				return call_user_func_array( [ __CLASS__, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}
	}
}

if ( ! class_exists( 'WooNotify_Metabox_360Messenger' ) && class_exists( 'WooNotify_360Messenger_Metabox' ) ) {
	class WooNotify_Metabox_360Messenger extends WooNotify_360Messenger_Metabox {

		public function __call( $name, $arguments ) {
			if ( method_exists( $this, $name ) ) {
				return call_user_func_array( [ $this, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}

		public static function __callStatic( $name, $arguments ) {
			if ( method_exists( __CLASS__, $name ) ) {
				return call_user_func_array( [ __CLASS__, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}
	}
}

if ( ! class_exists( 'WooNotify_Notification_360Messenger' ) && class_exists( 'WooNotify_360Messenger_Product_Events' ) ) {
	class WooNotify_Notification_360Messenger extends WooNotify_360Messenger_Product_Events {

		public function __call( $name, $arguments ) {
			if ( method_exists( $this, $name ) ) {
				return call_user_func_array( [ $this, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}

		public static function __callStatic( $name, $arguments ) {
			if ( method_exists( __CLASS__, $name ) ) {
				return call_user_func_array( [ __CLASS__, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}
	}
}

if ( ! class_exists( 'WooNotify_Order_360Messenger' ) && class_exists( 'WooNotify_360Messenger_Orders' ) ) {
	class WooNotify_Order_360Messenger extends WooNotify_360Messenger_Orders {

		public function __call( $name, $arguments ) {
			if ( method_exists( $this, $name ) ) {
				return call_user_func_array( [ $this, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}

		public static function __callStatic( $name, $arguments ) {
			if ( method_exists( __CLASS__, $name ) ) {
				return call_user_func_array( [ __CLASS__, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}
	}
}

if ( ! class_exists( 'WooNotify_Tab_360Messenger' ) && class_exists( 'WooNotify_360Messenger_Product_Tab' ) ) {
	class WooNotify_Tab_360Messenger extends WooNotify_360Messenger_Product_Tab {

		public function __call( $name, $arguments ) {
			if ( method_exists( $this, $name ) ) {
				return call_user_func_array( [ $this, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}

		public static function __callStatic( $name, $arguments ) {
			if ( method_exists( __CLASS__, $name ) ) {
				return call_user_func_array( [ __CLASS__, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}
	}
}

if ( ! class_exists( 'WooNotify_Settings_Api' ) && class_exists( 'WooNotify_360Messenger_Settings_Api' ) ) {
	class WooNotify_Settings_Api extends WooNotify_360Messenger_Settings_Api {

		public function __call( $name, $arguments ) {
			if ( method_exists( $this, $name ) ) {
				return call_user_func_array( [ $this, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}

		public static function __callStatic( $name, $arguments ) {
			if ( method_exists( __CLASS__, $name ) ) {
				return call_user_func_array( [ __CLASS__, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}
	}
}

if ( ! class_exists( 'WooNotify_Settings_360Messenger' ) && class_exists( 'WooNotify_360Messenger_Settings' ) ) {
	class WooNotify_Settings_360Messenger extends WooNotify_360Messenger_Settings {

		public function __call( $name, $arguments ) {
			if ( method_exists( $this, $name ) ) {
				return call_user_func_array( [ $this, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}

		public static function __callStatic( $name, $arguments ) {
			if ( method_exists( __CLASS__, $name ) ) {
				return call_user_func_array( [ __CLASS__, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}
	}
}

if ( ! class_exists( 'WooNotify_Widget_360Messenger' ) && class_exists( 'WooNotify_360Messenger_Subscription' ) ) {
	class WooNotify_Widget_360Messenger extends WooNotify_360Messenger_Subscription {

		public function __call( $name, $arguments ) {
			if ( method_exists( $this, $name ) ) {
				return call_user_func_array( [ $this, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}

		public static function __callStatic( $name, $arguments ) {
			if ( method_exists( __CLASS__, $name ) ) {
				return call_user_func_array( [ __CLASS__, $name ], $arguments );
			}
			_deprecated_function( __METHOD__, '4.0.0' );

			return false;
		}
	}
}

class WooNotify_360Messenger_Deprecated_Hooks {

	public function __construct() {
		$this->filters();
		$this->actions();
	}

	private function filters() {

		add_filter( 'WooNotify_360Messenger_gateways', function ( $gateway ) {
			return apply_filters( 'persianwoo360Messenger_360Messenger_gateway', $gateway );
		}, 10, 1 );

		add_filter( 'WooNotify_settings_sections', function ( $sections ) {
			return apply_filters( 'persianwoo360Messenger_settings_sections', $sections );
		}, 10, 1 );

		add_filter( 'WooNotify_main_settings', function ( $settings ) {
			return apply_filters( '360Messenger_main_settings_settings', $settings );
		}, 10, 1 );

		add_filter( 'WooNotify_super_admin_settings', function ( $settings ) {
			return apply_filters( '360Messenger_super_admin_settings_settings', $settings );
		}, 10, 1 );

		add_filter( 'WooNotify_buyer_settings', function ( $settings ) {
			return apply_filters( '360Messenger_buyer_settings_settings', $settings );
		}, 10, 1 );

		add_filter( 'WooNotify_product_admin_settings', function ( $settings ) {
			return apply_filters( '360Messenger_product_admin_settings_settings', $settings );
		}, 10, 1 );

		add_filter( 'WooNotify_notif_settings', function ( $settings ) {
			return apply_filters( '360Messenger_notif_settings_settings', $settings );
		}, 10, 1 );

		add_filter( 'WooNotify_settings_fields', function ( $settings_fields ) {
			return apply_filters( 'persianwoo360Messenger_settings_section_content', $settings_fields );
		}, 10, 1 );

		add_filter( 'WooNotify_shortcodes_list', function ( $shortcodes ) {
			return apply_filters( 'woonotify_360Messenger_shortcode_list', $shortcodes );
		}, 10, 1 );

		add_filter( 'WooNotify_order_360Messenger_body_before_replace', function ( $content, $shortcodes, $shortcodes_values, $order_id, $order, $product_ids ) {
			return apply_filters( 'woonotify_360Messenger_content_replace', $content, $shortcodes, $shortcodes_values, $order_id, $order, $product_ids );
		}, 10, 6 );

		add_filter( 'WooNotify_order_360Messenger_body_after_replace', function ( $content, $order_id, $order, $product_ids ) {
			return apply_filters( 'woonotify_360Messenger_content', $content, $order_id, $order, $product_ids );
		}, 10, 4 );
	}

	private function actions() {

		add_action( 'WooNotify_before_product_newsletter_form', function ( $product ) {
			do_action( 'ps_woo_360Messenger_before_notif_form', $product );
		}, 10, 1 );

		add_action( 'WooNotify_after_product_newsletter_form', function ( $product ) {
			do_action( 'ps_woo_360Messenger_after_notif_form', $product );
		}, 10, 1 );

		add_action( 'WooNotify_product_360Messenger_tab', function ( $product_id ) {
			do_action( 'woocommerce_product_360Messenger', $product_id );
		}, 10, 1 );

		if ( class_exists( 'WooNotify_360Messenger_Settings' ) ) {
			if ( method_exists( 'WooNotify_360Messenger_Settings', 'settingSections' ) ) {

				$sections = WooNotify_360Messenger_Settings::settingSections();
				$form_ids = wp_list_pluck( $sections, 'id' );

				foreach ( (array) $form_ids as $form_id ) {

					add_action( 'WooNotify_settings_form_top_' . $form_id, function ( $form ) use ( $form_id ) {
						do_action( 'ps_woo_360Messenger_form_top_' . $form_id, $form );
					}, 10, 1 );

					add_action( 'WooNotify_settings_form_bottom_' . $form_id, function ( $form ) use ( $form_id ) {
						do_action( 'ps_woo_360Messenger_form_bottom_' . $form_id, $form );
					}, 10, 1 );

					add_action( 'WooNotify_settings_form_submit_' . $form_id, function ( $form ) use ( $form_id ) {
						echo '<div style="padding-right: 10px">';
						do_action( 'ps_woo_360Messenger_form_submit_' . esc_html($form_id), esc_html($form) );
						echo '</div>';
					}, 10, 1 );

				}
			}
		}
	}

}

new WooNotify_360Messenger_Deprecated_Hooks();

global $WooNotify, $persianwoo360Messenger, $persianwoohelper;
if ( ! empty( $WooNotify ) ) {
	$persianwoo360Messenger = $persianwoohelper = $WooNotify;
}