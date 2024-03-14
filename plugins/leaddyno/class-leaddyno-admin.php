<?php
/**
 * LeadDyno_Admin
 * Plugin Name: LeadDyno WordPress Plugin
 * Version: 1.10.6
 * Plugin URI: http://www.leaddyno.com/wordpress/
 * Description: Integrates LeadDyno on your WordPress site
 * Author: LeadDyno
 * Author URI: http://www.leaddyno.com/
 *
 * @package  leaddyno
 * @link http://www.leaddyno.com/wordpress/
 */

/**
 * The LeadDyno WordPress plugin allows easy integration of your Wordpress/Woocommerce site with LeadDyno
 */
function leaddyno_init() {
}

add_action( 'plugins_loaded', 'leaddyno_init' );

if ( ! class_exists( 'LeadDyno_Admin' ) ) {

	/**
	 * Class LeadDyno_Admin
	 *
	 * Creates the admin for the LeadDyno for WordPress plugin
	 */
	class LeadDyno_Admin {

		/**
		 * Construct of class LeadDyno_Admin
		 *
		 * @access private
		 * @link   http://codex.wordpress.org/Function_Reference/add_action
		 * @link   http://codex.wordpress.org/Function_Reference/add_filter
		 */
		public function __construct() {
			$this->filename = __FILE__;

			add_action( 'admin_menu', array( &$this, 'register_settings_page' ) );

			add_filter( 'plugin_action_links', array( &$this, 'add_action_link' ), 10, 2 );

			add_action( 'admin_print_styles', array( &$this, 'config_page_styles' ) );

			$this->leaddyno_admin_warnings();
		}

		/**
		 * Class config styles
		 */
		public function config_page_styles() {
				wp_enqueue_style( 'leaddyno-admin-css', WP_CONTENT_URL . '/plugins/' . plugin_basename( dirname( __FILE__ ) ) . '/leaddyno.css' );
		}

		/**
		 * Class register setting page
		 */
		public function register_settings_page() {
			add_options_page( 'LeadDyno Configuration', 'LeadDyno', 'manage_options', 'leaddyno', array( &$this, 'config_page' ) );
		}

		/**
		 * Class plugin option url
		 */
		public function plugin_options_url() {
			return admin_url( 'options-general.php?page=leaddyno' );
		}

		/**
		 * Add a link to the settings page to the plugins list
		 * Class add action url
		 *
		 * @param string $links the string to source url.
		 * @param string $file the string to source file.
		 *
		 * @return string
		 */
		public function add_action_link( $links, $file ) {
		    if ($file == plugin_basename(__FILE__)) {
                $settings_link = '<a href="' . $this->plugin_options_url() . '">' . __( 'Settings' ) . '</a>';
                array_unshift( $links, $settings_link );
            }
			return $links;
		}

		/**
		 * Create a postbox widget
		 *
		 * @param string $id the string to source url.
		 * @param string $title the string to source file.
		 * @param string $content the string to source file.
		 */
		public function postbox( $id, $title, $content = array() ) {
			?>
			<div id="<?php echo esc_html( $id ); ?>" class="postbox">
				<h3 class="hndle"><span><?php echo esc_html( $title ); ?></span></h3>
				<div class="inside">
					<?php print_r( $content ); ?>
				</div>
			</div>
			<?php
			$this->toc .= '<li><a href="#' . $id . '">' . $title . '</a></li>';
		}

		/**
		 * Create a form table from an array of rows
		 *
		 * @param string $rows the string to source url.
		 *
		 * @return string
		 */
		public function form_table( $rows ) {
			$content = '<table class="form-table">';
			$i       = 1;
			foreach ( $rows as $row ) {
				$class = '';
				if ( $i > 1 ) {
					$class .= 'ld_row';
				}
				if ( 0 === $i % 2 ) {
					$class .= ' even';
				}
				$content .= '<tr class="' . $row['id'] . '_row ' . $class . '"><th valign="top" scope="row">';
				if ( isset( $row['id'] ) && '' !== $row['id'] ) {
					$content .= '<label for="' . $row['id'] . '">' . $row['label'] . ':</label>';
				} else {
					$content .= $row['label'];
				}
				$content .= '</th><td valign="top">';
				$content .= $row['content'];
				$content .= '</td></tr>';
				if ( isset( $row['desc'] ) && ! empty( $row['desc'] ) ) {
					$content .= '<tr class="' . $row['id'] . '_row ' . $class . '"><td colspan="2" class="ld_desc">' . $row['desc'] . '</td></tr>';
				}
				$i++;
			}
			$content .= '</table>';
			return $content;
		}


		/**
		 * Creates  warnings for empty fields in the admin
		 *
		 * @uses leaddyno_get_options()
		 * @uses leaddyno_warning()
		 * @link http://codex.wordpress.org/Function_Reference/add_action
		 */
		public function leaddyno_admin_warnings() {
			$options = leaddyno_get_options();
			if ( ( ! $options['public_key'] || empty( $options['public_key'] ) || ! $options['private_key'] || empty( $options['private_key'] ) ) && ! $_POST ) { // Input var okay.

				/** Outputs a warning */
				function leaddyno_warning() {
					echo "<div id='leaddynowarning' class='updated fade'><p><strong>";
					echo 'LeadDyno is almost ready.';
					echo '</strong>';
					echo 'You must <a href="' . esc_url( admin_url( 'options-general.php?page=leaddyno' ) ) . '">enter your LeadDyno public and private API keys</a> for it to work.';
					echo '</p></div>';
					echo "<script type=\"text/javascript\">setTimeout(function(){jQuery('#leaddynowarning').hide('slow');}, 10000);</script>";
				}
				add_action( 'admin_notices', 'leaddyno_warning' );
				return;
			}
		}

		/**
		 * Creates the configuration page for LeadDyno for WordPress
		 *
		 * @uses leaddyno_get_options()
		 * @link http://codex.wordpress.org/Function_Reference/current_user_can
		 * @link http://codex.wordpress.org/Function_Reference/check_admin_referer
		 * @link http://codex.wordpress.org/Function_Reference/update_option
		 * @link http://codex.wordpress.org/Function_Reference/wp_nonce_field
		 */
		public function config_page() {
			$options = leaddyno_get_options();

			if ( isset( $_POST['submit'] ) ) { // Input var okay.

				if ( ! current_user_can( 'manage_options' ) ) {
					die( 'You cannot edit the LeadDyno settings.' );
				}
				check_admin_referer( 'leaddyno-config' );

				foreach ( array( 'public_key', 'private_key', 'domain' ) as $option_name ) {
					if ( isset( $_POST[ $option_name ] ) ) { // Input var okay.
						$options[ $option_name ] = sanitize_text_field( wp_unslash( $_POST[ $option_name ] ) ); // Input var okay.
					} else {
						$options[ $option_name ] = '';
					}
				}
				foreach ( array( 'ignore_admin', 'enable_paypal', 'disable_autowatch' ) as $option_name ) {
					if ( isset( $_POST[ $option_name ] ) ) { // Input var okay.
						$options[ $option_name ] = true;
					} else {
						$options[ $option_name ] = false;
					}
				}
				if ( leaddyno_get_options() !== $options ) {
					update_option( 'leaddyno', $options );
					$message = 'LeadDyno settings have been updated.';
				}
			}

			if ( isset( $error ) && '' !== $error ) {
			?>
				<div id="message" class="error"><?php echo esc_html( $error ); ?></div>
				<?php
			} elseif ( isset( $message ) && '' !== $message ) {
			?>
				<div id="updatemessage" class="updated fade"><p><?php echo esc_html( $message ); ?></p></div>
				<?php
				echo "<script type=\"text/javascript\">setTimeout(function(){jQuery('#updatemessage').hide('slow');}, 3000);</script>";
			}
			?>
			<div class="wrap">
				<h2>LeadDyno Configuration</h2>
				<div class="postbox-container" style="width:100%;">
					<div class="metabox-holder">
						<div class="meta-box-sortables">
							<form action="" method="post" id="leaddyno-conf" enctype="multipart/form-data">
								<?php
								wp_nonce_field( 'leaddyno-config' );

								$content = '<p style="text-align:left; margin: 0 10px; font-size: 13px; line-height: 150%;">Go to your <a href="https://app.leaddyno.com/settings/account" target="_new">LeadDyno Settings Page</a> and you will find your Public Key and Private Key.</p>';

								$rows   = array();
								$rows[] = array(
									'id'      => 'public_key',
									'label'   => 'Public Key',
									'desc'    => '',
									'content' => '<input class="text" type="text" value="' . $options['public_key'] . '" name="public_key" id="public_key"/>',
								);

								$rows[] = array(
									'id'      => 'private_key',
									'label'   => 'Private Key',
									'desc'    => '',
									'content' => '<input class="text" type="text" value="' . $options['private_key'] . '" name="private_key" id="private_key"/>',
								);

								$content .= ' ' . $this->form_table( $rows );
								$this->postbox( 'leaddyno_settings', 'LeadDyno Settings', $content );

								$rows   = array();
								$rows[] = array(
									'id'      => 'domain',
									'label'   => 'Domain',
									'desc'    => 'Recommended setting is to leave this blank. Overrides your website URL in special cases.',
									'content' => '<input class="text" type="text" value="' . $options['domain'] . '" name="domain" id="domain"/>',
								);

								$rows[] = array(
									'id'      => 'ignore_admin',
									'label'   => 'Ignore Admin users',
									'desc'    => 'If you are using a caching plugin, such as W3 Total Cache or WP-Supercache, please ensure that you have it configured to NOT use the cache for logged in users. Otherwise, admin users <em>will still</em> be tracked.',
									'content' => '<input type="checkbox" ' . checked( $options['ignore_admin'], true, false ) . ' name="ignore_admin" id="ignore_admin"/>',
								);

								$rows[] = array(
									'id'      => 'enable_paypal',
									'label'   => 'Enable Paypal Purchase Tracking',
									'desc'    => 'If you use paypal for purchases, enable this option to have those purchases automatically tracked by LeadDyno.',
									'content' => '<input type="checkbox" ' . checked( $options['enable_paypal'], true, false ) . ' name="enable_paypal" id="enable_paypal"/>',
								);

								$rows[] = array(
									'id'      => 'disable_autowatch',
									'label'   => 'Disable Auto Watch',
									'desc'    => 'If the autoWatch() javascript API is interfering with other scripts, enable this option to turn it off.',
									'content' => '<input type="checkbox" ' . checked( $options['disable_autowatch'], true, false ) . ' name="disable_autowatch" id="disable_autowatch"/>',
								);
								$this->postbox( 'leaddyno_settings', 'Advanced Settings', $this->form_table( $rows ) );
								?>
								<div class="submit">
									<input type="submit" class="button-primary" name="submit" value="Update LeadDyno Settings &raquo;" />
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		<?php
		}
	}
	$leaddyno_admin = new LeadDyno_Admin();
}

/**
 * Loads LeadDyno-options set in WordPress.
 * If already set: trim some option. Otherwise load defaults.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_option
 * @uses leaddyno_defaults()
 * @return array Returns the trimmed/default options for leaddyno
 */
function leaddyno_get_options() {
	$options = get_option( 'leaddyno' );
	if ( ! is_array( $options ) ) {
		leaddyno_defaults();
	} else {
		$options['public_key']  = trim( $options['public_key'] );
		$options['private_key'] = trim( $options['private_key'] );
	}
	return $options;
}

function leaddyno_user_agent() {
    $ua_str = "LD-Wordpress/" . $wp_version;
    if ( class_exists( 'woocommerce' ) ) {
        $ua_str = $ua_str . " WooCommerce/" . $woocommerce->version;
    }
    return $ua_str;
}

/**
 * Default options for LeadDyno for WordPress plugin
 *
 * @link http://codex.wordpress.org/Function_Reference/add_option
 */
function leaddyno_defaults() {
	$options = array(
		'public_key'    => '',
		'private_key'   => '',
		'enable_paypal' => false,
		'ignore_admin'  => false,
	);
	add_option( 'leaddyno', $options );
}

/**
 * Add leaddyno scripts to footer
 *
 * @return bool
 *
 * @link http://codex.wordpress.org/Function_Reference/current_user_can
 */
function leaddyno_script() {
	$options = leaddyno_get_options();

	if ( is_preview() ) {
		return false;
	}

	// Bail early if current user is admin and ignore admin is true.
	if ( $options['ignore_admin'] && current_user_can( 'manage_options' ) ) {
		echo "\n<!-- LeadDyno JS not shown because you're an administrator and you've configured LeadDyno to ignore administrators. -->\n";
		return false;
	}

	// Bail early if public key is not set.
	if ( ! $options['public_key'] ) {
		echo "\n<!-- LeadDyno JS not shown because public key is not set. -->\n";
		return false;
	}
	?>

<!-- LeadDyno JS - generated by LeadDyno Wordpress Plugin. -->
<script type="text/javascript" src="https://static.leaddyno.com/js"></script>
<script>
LeadDyno.key = "<?php echo esc_html( $options['public_key'] ); ?>";
LeadDyno.channel = "wordpress";
<?php
    if ( $options['domain'] ) {
        echo "LeadDyno.domain = '" . esc_html( $options['domain'] ) . "';\n";
    }
?>
LeadDyno.recordVisit();
<?php
    if ( $options['enable_paypal'] ) {
        echo "LeadDyno.initPaypal();\n";
    }
?>
<?php
    if ( ! $options['disable_autowatch'] ) {
        echo "LeadDyno.autoWatch();\n";
    }
?>
</script>

<?php

	return true;
}

add_action( 'wp_footer', 'leaddyno_script', 90 );
add_action( 'woocommerce_order_status_changed', 'leaddyno_order_status_changed' );

/**
 * Tracks purchases from Woocommerce in LeadDyno
 *
 * @uses leaddyno_get_options()
 * @param string $order_id the string to source url.
 *
 * @return string
 */
function leaddyno_order_status_changed( $order_id ) {

	$options = leaddyno_get_options();

	if ( ! $options['private_key'] ) {
		return;
	}

	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {

		$order = new WC_Order( $order_id );
		if ( $order ) {

			if ( 'pending' === $order->get_status() || 'processing' === $order->get_status() || 'completed' === $order->get_status() ) {

					$code = '';
				if ( $order->get_used_coupons() ) {
						$coupons = $order->get_used_coupons();
						$code    = $coupons[0];
				}

					$total = $order->get_total() - $order->get_total_shipping() - $order->get_total_tax();

					$req = array(
						'key'             => $options['private_key'],
						'email'           => $order->get_billing_email(),
						'purchase_code'   => ltrim( $order->get_order_number(), '#' ),
						'purchase_amount' => $total,
						'code'            => $code,
					);

					 $url              = 'https://api.leaddyno.com/v1/purchases';
						$fields_string = http_build_query( $req );
						$ch            = curl_init();
						curl_setopt( $ch, CURLOPT_URL, $url );
						curl_setopt( $ch, CURLOPT_POST, 1 );
						curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
						curl_setopt( $ch, CURLOPT_USERAGENT, leaddyno_user_agent());
						curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
						$ld_result = curl_exec( $ch );
						curl_close( $ch );
						$ld_json = json_decode( $ld_result );

			} elseif ( 'cancelled' === $order->get_status() || 'refunded' === $order->get_status() ) {

					$req = array(
						'key'           => $options['private_key'],
						'email'         => $order->get_billing_email(),
						'purchase_code' => ltrim( $order->get_order_number(), '#' ),
					);

					$url           = 'https://api.leaddyno.com/v1/purchases';
					$fields_string = http_build_query( $req );
					$ch            = curl_init();
					curl_setopt( $ch, CURLOPT_URL, $url );
					curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'DELETE' );
					curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
					curl_setopt( $ch, CURLOPT_USERAGENT, leaddyno_user_agent());
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
					$ld_result = curl_exec( $ch );
					curl_close( $ch );
					$ld_json = json_decode( $ld_result );
			}
		}
	}
}

add_action( 'mm_payment_received', 'mm_track_commission' );

/**
 * Class mm track commission
 *
 * @param string $data the string to source url.
 *
 * @return string
 */
function mm_track_commission( $data ) {
	$options = leaddyno_get_options();

	if ( ! $options['private_key'] ) {
		return;
	}

	// access coupons associated with the order.
	$couponcode = '';
	$coupons    = json_decode( stripslashes( $data['order_coupons'] ) );
	foreach ( $coupons as $coupon ) {
		$couponcode = $coupon->code;
		break;
	}
	$req = array(
		'key'             => $options['private_key'],
		'email'           => $data['email'],
		'purchase_code'   => $data['order_number'],
		'purchase_amount' => $data['order_total'] - $data['order_shipping'],
		'plan_code'       => $data['membership_level_name'],
		'code'            => $couponcode,
	);

	$url           = 'https://api.leaddyno.com/v1/purchases';
	$fields_string = http_build_query( $req );
	$ch            = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_POST, 1 );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
	curl_setopt( $ch, CURLOPT_USERAGENT, leaddyno_user_agent());
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	$ld_result = curl_exec( $ch );
	curl_close( $ch );
	$ld_json = json_decode( $ld_result );
}

add_action( 'mm_refund_issued', 'mm_track_commission_cancel' );

/**
 * Class mm track commission cancel
 *
 * @param string $data the string to source url.
 *
 * @return string
 */
function mm_track_commission_cancel( $data ) {
	$options = leaddyno_get_options();

	if ( ! $options['private_key'] ) {
		return;
	}

	$req = array(
		'key'           => $options['private_key'],
		'email'         => $data['email'],
		'purchase_code' => $data['order_number'],
	);

	$url           = 'https://api.leaddyno.com/v1/purchases';
	$fields_string = http_build_query( $req );
	$ch            = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'DELETE' );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
	curl_setopt( $ch, CURLOPT_USERAGENT, leaddyno_user_agent());
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	$ld_result = curl_exec( $ch );
	curl_close( $ch );
	$ld_json = json_decode( $ld_result );
}

add_action( 'mm_member_status_change', 'mm_track_status_change' );

/**
 * Class mm track status change
 *
 * @param string $data the string to source url.
 *
 * @return string
 */
function mm_track_status_change( $data ) {
	$options = leaddyno_get_options();

	if ( ! $options['private_key'] ) {
		return;
	}

	if ( '2' === $data['status'] || '7' === $data['status'] || '8' === $data['status'] ) {

		$req = array(
			'key'   => $options['private_key'],
			'email' => $data['email'],
		);

		$url           = 'https://api.leaddyno.com/v1/purchases';
		$fields_string = http_build_query( $req );
		$ch            = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'DELETE' );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
		curl_setopt( $ch, CURLOPT_USERAGENT, leaddyno_user_agent());
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$ld_result = curl_exec( $ch );
		curl_close( $ch );
		$ld_json = json_decode( $ld_result );
	}
}


