<?php

namespace WC_BPost_Shipping\Factory;

use Bpost\BpostApiClient\Bpost;
use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;

/**
 * Class WC_BPost_Shipping_Factory_Form builds form for bpost admin page
 * @package WC_BPost_Shipping\Factory
 */
class WC_BPost_Shipping_Factory_Form {
	/** @var WC_BPost_Shipping_Adapter_Woocommerce */
	private $adapter;

	/**
	 * WC_BPost_Shipping_Factory_Form constructor.
	 *
	 * @param WC_BPost_Shipping_Adapter_Woocommerce $adapter
	 */
	public function __construct( WC_BPost_Shipping_Adapter_Woocommerce $adapter ) {
		$this->adapter = $adapter;
	}

	/**
	 * @param string $title Plugin id/title used for activation/deactivation
	 *
	 * @return array
	 */
	public function get_settings_form( $title ) {
		return array_merge(
			array(
				'enabled' => array(
					'title'       => bpost__( 'Enable' ),
					'type'        => 'checkbox',
					'label'       => sprintf( bpost__( 'Enable %s' ), $title ),
					'default'     => 'yes',
					'description' => '',
				),
			),
			$this->get_api(),
			$this->get_logs(),
			$this->get_free_shipping(),
			$this->get_label(),
			$this->get_label_storage(),
			$this->get_label_api(),
			$this->get_google()
		);
	}

	/**
	 * API part
	 * @return array
	 */
	public function get_api() {
		return array(
			'api_title'      => array(
				'title' => bpost__( 'bpost API: shipping Manager connection settings' ),
				'type'  => 'title',
			),
			'api_account_id' => array(
				'title'       => bpost__( 'Account id' ),
				'type'        => 'text',
				'description' => bpost__( 'You need a user account from bpost to use this module. Call 02/201 11 11 for more information or visit:  http://bpost.freshdesk.com/solution/articles/174847' ),
				'desc_tip'    => true,
				'default'     => '',
			),
			'api_passphrase' => array(
				'title'       => bpost__( 'Passphrase' ),
				'type'        => 'text',
				'description' => bpost__( 'Enter your bpost account id' ),
				'desc_tip'    => true,
				'default'     => '',
			),
			'api_url'        => array(
				'title'       => bpost__( 'bpost API URL' ),
				'type'        => 'text',
				'description' => bpost__( 'Enter the front-end URL of the API' ),
				'desc_tip'    => true,
				// TODO Bpost::API_URL is repeated at three linked position. Is it really needed ?
				'default'     => Bpost::API_URL,
			),
		);
	}

	/**
	 * Free Shipping part
	 * @return array
	 */
	public function get_free_shipping() {
		return array(
			'free_shipping_title' => array(
				'title'       => bpost__( 'Free shipping' ),
				'type'        => 'title',
				'description' => sprintf(
					bpost__(
						'This plugin supports the free shipping options. Setup them %s here %s
					<p>Free shipping is allowed only for countries configured in SHM backend and %s Woocommerce > Settings > General %s > Specific countries.<br />
					Make sure you ship to corresponding countries (%sWoocommerce > Settings > Shipping > Shipping options%s > Restrict shipping to Location(s)).</p>'
					),
					'<a href="' . $this->adapter->admin_url( 'edit.php?post_type=shop_coupon' ) . '" title="' . bpost__( 'Go to the coupons management page' ) . '">',
					'</a>',
					'<a href="' . $this->adapter->admin_url( 'admin.php?page=wc-settings&tab=general' ) . '">',
					'</a>',
					'<a href="' . $this->adapter->admin_url( 'admin.php?page=wc-settings&tab=shipping' ) . '">',
					'</a>'
				),
			),
			'free_shipping_items' => array(
				'title'   => bpost__( 'Free shipping settings' ),
				'type'    => 'jsonarray',
				'default' => '',
			),
		);
	}

	/**
	 * Labelling part
	 * @return array
	 */
	public function get_label() {
		return array(
			'label_title'      => array(
				'title' => bpost__( 'bpost labels settings' ),
				'type'  => 'title',
			),

			'label_format'     => array(
				'title'   => bpost__( 'bpost labels size' ),
				'default' => 'A4',
				'type'    => 'select',
				'options' => array(
					'A4' => bpost__( 'A4' ),
					'A6' => bpost__( 'A6' ),
				),
			),

			'label_return'     => array(
				'title'   => bpost__( 'Enable return labels' ),
				'type'    => 'checkbox',
				'default' => 'no',
			),

			'label_cache_time' => array(
				'default' => '',
				'title'   => bpost__( 'Cache duration' ),
				'type'    => 'select',
				'options' => array(
					'P0W' => bpost__( 'No cache (could be slower)' ),
					'P1W' => bpost__( '1 week' ),
					'P2W' => bpost__( '2 weeks' ),
					'P3W' => bpost__( '3 weeks' ),
					'P1M' => bpost__( '1 month' ),
					'P2M' => bpost__( '2 month' ),
					'P6M' => bpost__( '6 months' ),
					'P1Y' => bpost__( '1 year' ),
					''    => bpost__( 'Infinity (never clear cache)' ),
				),
			),
		);
	}

	/**
	 * Label storage part
	 * @return array
	 */
	public function get_label_storage() {
		$upload_folder = $this->adapter->wp_upload_dir();

		return array(
			'label_storage_title'    => array(
				'title'       => bpost__( 'bpost labels local storage location (advanced setting)' ),
				'type'        => 'title',
				'description' => bpost__(
					'
By default bpost labels are stored as WP attachment entities in the /wp-content/uploads/* directory.<br>
You can change this setting and store labels as "simple" files (no more handled as WP attachments) in any local directory of your choice.<br>
In such case bpost labels will still work correctly accessed from Woocommerce but, for example, they will no more appear in the WP file manager.<br>
If the labels are stored as simple files, their file path will be <code>{root_folder_path}/{order_id}-(a4|a6)-(return|noReturn).pdf}</code>.<br>
⚠ Be caution when changing bpost labels storage location: <br>
- ensure your server settings allow PHP to write in the directory you defined otherwise plugin will not work anymore,<br>
- previously retreived labels (if any) are not moved to the new path, you have to do it manually to ensure that previous labels remain available from WP.
'
				),
			),

			'label_storage_as_files' => array(
				'title'   => bpost__( 'Store bpost labels as simple files' ),
				'type'    => 'checkbox',
				'default' => 'no',
			),

			'label_storage_path'     => array(
				'title'   => bpost__( 'Labels stored as simple files: root folder path' ),
				'default' => $upload_folder['basedir'] . '/bpost-labels',
			),
		);
	}

	/**
	 * Label API part
	 * @return array
	 */
	public function get_label_api() {
		return array(
			'label_api_title' => array(
				'title'       => bpost__( 'Labels website API' ),
				'type'        => 'title',
				'description' => bpost__(
					'
You can generate and retrieve labels from third-party systems using the API exposed by your website.
To use it this API, you have to pass the bpost labels ID along with an non-empty API key. The one must appears in the GET parameter "bpost_key" of your HTTP call.<br>
Example: <code>https://my-webshop.be/wp-admin/wc-api/bpost-label?post_ids[0]=1234&post_ids[1]=5678&bpost_key=mysecretkey2019!</code>, where <code>mysecretkey2019!</code> is your Website API key.
'
				),
			),
			'label_api_key'   => array(
				'title' => bpost__( 'Website API key' ),
			),
		);
	}

	/**
	 * Google API key part
	 * @return array
	 */
	public function get_google() {
		return array(
			'google_title'   => array(
				'title' => bpost__( 'Google' ),
				'type'  => 'title',
			),

			'google_api_key' => array(
				'title' => bpost__( 'API key for maps' ),
			),

		);
	}

	/**
	 * Logs part
	 * @return array
	 */
	public function get_logs() {
		return array(
			'logs_title'      => array(
				'title'       => bpost__( 'bpost transactions logs' ),
				'type'        => 'title',
				'description' => sprintf(
					bpost__(
						'<p>When errors occur, the plugin write logs in an file. You can see the file in WooCommerce status (%sWooCommerce > System Status > Logs%s).
					<p>To see all interactions between the plugin on your website and the bpost Shipping Manager API, you need to enable the "Debug mode".<br />
					To clean the log file, check the "Clean" checkbox.</p>'
					),
					'<a href="' . $this->adapter->admin_url( 'admin.php?page=wc-status&tab=logs' ) . '" title="' . bpost__( 'Go to the logs page' ) . '">',
					'</a>'
				),
			),
			'logs_debug_mode' => array(
				'title'   => bpost__( 'Debug mode' ),
				'type'    => 'checkbox',
				'default' => 'no',
				'label'   => bpost__( 'Log interactions between plugin and bpost services (API/Shipping Manager)' ),
			),
			'logs_clean'      => array(
				'title'   => bpost__( 'Clean' ),
				'type'    => 'checkbox',
				'label'   => bpost__( 'Clear the log file' ),
				'default' => 'no',
			),
		);
	}
}
