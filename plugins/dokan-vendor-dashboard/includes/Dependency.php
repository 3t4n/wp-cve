<?php

namespace WeDevs\DokanVendorDashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Dependency resolver.
 *
 * @since 1.0.0
 */
class Dependency {

	/**
	 * Minimum required version of Dokan.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $min_dokan_version = '3.7.10';

	/**
	 * Minimum required version of Dokan Pro.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $min_dokan_pro_version = '3.7.13';

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Registers necessary hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'plugins_loaded', array( $this, 'verify_initial_dependencies' ) );
	}

	/**
	 * Checks if dependencies are satisfied.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function verify_dependencies() {
		if ( ! function_exists( 'WC' ) || ! function_exists( 'dokan' ) ) {
			return false;
		}

		if ( version_compare( dokan()->version, $this->min_dokan_version, '<' ) ) {
			$this->show_admin_notice(
				sprintf(
					/* translators: %1$s) opening <strong> tag, %2$s) closing </strong> tag, %3$s) opening <strong> tag, %4$s) plugin version, %5$s) closing </strong> tag, %6$s) opening <strong> tag, %7$s) plugin version, %8$s) closing </strong> tag, */
					__( 'Minimum required version of %1$sDokan%2$s is %3$s%4$s%5$s. The currently installed version is %6$s%7$s%8$s. Please install the required version.', 'dokan-vendor-dashboard' ),
					'<strong>',
					'</strong>',
					'<strong>',
					$this->min_dokan_version,
					'</strong>',
					'<strong>',
					dokan()->version,
					'</strong>',
				)
			);

			return false;
		}

		if ( function_exists( 'dokan_pro' ) && version_compare( dokan_pro()->version, $this->min_dokan_pro_version, '<' ) ) {
			$this->show_admin_notice(
				sprintf(
					/* translators: %1$s) opening <strong> tag, %2$s) closing </strong> tag, %3$s) opening <strong> tag, %4$s) plugin version, %5$s) closing </strong> tag, %6$s) opening <strong> tag, %7$s) plugin version, %8$s) closing </strong> tag, */
					__( 'Minimum required version of %1$sDokan Pro%2$s is %3$s%4$s%5$s. The currently installed version is %6$s%7$s%8$s. Please install the required version.', 'dokan-vendor-dashboard' ),
					'<strong>',
					'</strong>',
					'<strong>',
					$this->min_dokan_pro_version,
					'</strong>',
					'<strong>',
					dokan_pro()->version,
					'</strong>',
				)
			);

			return false;
		}

		return true;
	}

	/**
	 * Checks initial dependencies.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function verify_initial_dependencies() {
		if ( ! function_exists( 'WC' ) || ! function_exists( 'dokan' ) ) {
			$this->show_admin_notice(
				sprintf(
					/* translators: %1$s) opening <strong> tag, %2$s) closing </strong> tag, %3$s) opening <strong> tag, %4$s) closing </strong> tag, */
					__( 'Both %1$sWooCommerce%2$s and %3$sDokan%4$s plugins are required. Please install both the plugins first.', 'dokan-vendor-dashboard' ),
					'<strong>',
					'</strong>',
					'<strong>',
					'</strong>',
				)
			);

			return false;
		}

		return true;
	}

	/**
	 * Shows necessary admin notice.
	 *
	 * @since 1.0.0
	 *
	 * @param string $notice
	 *
	 * @return void
	 */
	public function show_admin_notice( $notice ) {
		add_action(
            'admin_notices', function() use ( $notice ) {
				echo wp_kses(
                    sprintf(
						/* translators: %1$s) opening <strong> tag, %2$s) closing </strong> tag, %3$s) opening <div> and <p> tags, %4$s) notice text, %5$s) closing </p> and </div> tags */
                        __( '%1$s%2$sDokan Vendor Dashboard:%3$s %4$s%5$s', 'dokan-vendor-dashboard' ),
                        '<div class="notice notice-error"><p>',
                        '<strong>',
                        '</strong>',
                        $notice,
                        '</p></div>'
                    ),
                    array(
						'div'    => array(
							'class' => array(),
						),
						'p'      => array(),
						'strong' => array(),
						'br'     => array(),
						'a'      => array(
							'href'   => array(),
							'target' => array(),
						),
                    )
				);
			}
        );
	}
}
