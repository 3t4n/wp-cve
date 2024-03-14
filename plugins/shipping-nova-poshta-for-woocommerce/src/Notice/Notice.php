<?php
/**
 * Admin area notices
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Notice;

use NovaPoshta\Cache\TransientCache;

/**
 * Class Notice
 *
 * @package NovaPoshta\Notice
 */
class Notice {

	/**
	 * Cache key
	 */
	const NOTICES_KEY = 'np-notices';

	/**
	 * List of notices
	 *
	 * @var array
	 */
	protected $notices = [];

	/**
	 * Transient cache
	 *
	 * @var TransientCache
	 */
	protected $transient_cache;

	/**
	 * Notice constructor.
	 *
	 * @param TransientCache $transient_cache Transient Cache.
	 */
	public function __construct( TransientCache $transient_cache ) {

		$this->transient_cache = $transient_cache;
	}

	/**
	 * Add hooks
	 */
	public function hooks() {

		add_action( 'admin_init', [ $this, 'init' ] );
		add_action( 'admin_notices', [ $this, 'notices' ] );
		add_action( 'shutdown', [ $this, 'save' ] );
	}

	/**
	 * Init method
	 */
	public function init() {

		$notices = $this->transient_cache->get( self::NOTICES_KEY );

		if ( is_array( $notices ) ) {
			$this->transient_cache->delete( self::NOTICES_KEY );
			$this->notices = $notices;
		}
	}

	/**
	 * Show notices
	 */
	public function notices() {

		do_action( 'shipping_nova_poshta_for_woocommerce_print_notice', $this );

		if ( empty( $this->notices ) ) {
			return;
		}
		foreach ( $this->notices as $notice ) {
			$this->show( $notice['type'], $notice['message'], $notice['btn_label'], $notice['btn_url'] );
		}
		$this->notices = [];
	}

	/**
	 * Register plugin notice
	 *
	 * @param string $type      Type of notice.
	 * @param string $message   Message of notice.
	 * @param string $btn_label Button label.
	 * @param string $btn_url   Button url.
	 */
	public function add( string $type, string $message, string $btn_label = '', string $btn_url = '' ) {

		$this->notices[] = [
			'type'      => $type,
			'message'   => $message,
			'btn_label' => $btn_label,
			'btn_url'   => $btn_url,
		];

		$this->notices = array_unique( $this->notices, SORT_REGULAR );
	}

	/**
	 * Show notice
	 *
	 * @param string $type      Type of notice.
	 * @param string $message   Message of notice.
	 * @param string $btn_label Button label.
	 * @param string $btn_url   Button url.
	 */
	protected function show( string $type, string $message, string $btn_label = '', string $btn_url = '' ) {

		require NOVA_POSHTA_PATH . 'templates/admin/notice.php';
	}

	/**
	 * Save notices on one minute
	 */
	public function save() {

		if ( ! empty( $this->notices ) ) {
			$this->transient_cache->set( self::NOTICES_KEY, $this->notices, 60 );
		}
	}
}
