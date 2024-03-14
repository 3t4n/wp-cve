<?php
/**
 * Contains code for the parcel point render class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Shipping_Method\Parcel_Point
 */

namespace Boxtal\BoxtalConnectWoocommerce\Shipping_Method\Parcel_Point;

use Boxtal\BoxtalConnectWoocommerce\Util\Misc_Util;
use Boxtal\BoxtalConnectWoocommerce\Util\Shipping_Rate_Util;
use Boxtal\BoxtalConnectWoocommerce\Branding;

/**
 * Render class.
 *
 * Adds relay map link if configured.
 */
class Render {

	/**
	 * Run class.
	 *
	 * @void
	 */
	public function run() {
		add_action( 'woocommerce_after_shipping_rate', array( $this, 'add_parcelpoint_choice' ), 10, 2 );
	}

	/**
	 * Format a parcelpoint address into a one line string
	 *
	 * @param \StdClass $parcelpoint in object format.
	 * @return string one line address
	 */
	private function get_parcelpoint_address( $parcelpoint ) {
		$address = $parcelpoint->address;

		$ziptown = array();
		if ( null !== $parcelpoint->zipcode ) {
			$ziptown[] = $parcelpoint->zipcode;
		}
		if ( null !== $parcelpoint->city ) {
			$ziptown[] = $parcelpoint->city;
		}
		$ziptown = implode( ', ', $ziptown );

		$result = implode( ' ', array( $address, $ziptown ) );

		if ( null !== $parcelpoint->distance ) {
			$distance = round( $parcelpoint->distance / 100 ) / 10;
			/* translators: parcel point distance */
			$result .= ' (' . sprintf( __( '%skm away', 'boxtal-connect' ), $distance ) . ')';
		}

		return $result;
	}

	/**
	 * Add relay map link to shipping method choice.
	 *
	 * @param \WC_Shipping_Rate $shipping_rate shipping rate.
	 * @param string|int        $package_key key of package in cart.
	 * @return void
	 */
	public function add_parcelpoint_choice( $shipping_rate, $package_key ) {
		if ( Misc_Util::should_display_parcel_point_link( $shipping_rate ) ) {
			$points_response = Controller::init_points( Controller::get_recipient_address(), $shipping_rate, $package_key );

			if ( $points_response ) {
				$label                = '<span class="' . Branding::$branding_short . '-parcel-point">';
				$chosen_parcel_point  = Controller::get_chosen_point( Shipping_Rate_Util::get_id( $shipping_rate ), $package_key );
				$parcel_point_address = null;
				if ( null === $chosen_parcel_point ) {
					$closest_parcel_point = Controller::get_closest_point( Shipping_Rate_Util::get_id( $shipping_rate ), $package_key );
					$label               .= '<span class="' . Branding::$branding_short . '-parcel-client-' . $package_key . '">' . __( 'Closest parcel point:', 'boxtal-connect' ) . ' <span class="' . Branding::$branding_short . '-parcel-name-' . $package_key . '">' . $closest_parcel_point->name . '</span></span>';
					$parcel_point_address = $this->get_parcelpoint_address( $closest_parcel_point );
				} else {
					$label               .= '<span class="' . Branding::$branding_short . '-parcel-client-' . $package_key . '">' . __( 'Your parcel point:', 'boxtal-connect' ) . ' <span class="' . Branding::$branding_short . '-parcel-name-' . $package_key . '">' . $chosen_parcel_point->name . '</span></span>';
					$parcel_point_address = $this->get_parcelpoint_address( $chosen_parcel_point );
				}

				if ( null !== $parcel_point_address ) {
					$label .= '<br/><small class="' . Branding::$branding_short . '-parcel-address-' . $package_key . '"/>' . esc_html( $parcel_point_address ) . '</small>';
				}

				$label .= '<br/><span class="' . Branding::$branding_short . '-select-parcel" data-package_key="' . $package_key . '" data-branding="' . Branding::$branding_short . '">' . __( 'Choose another', 'boxtal-connect' ) . '</span>';
				$label .= '</span>';
				// phpcs:ignore
				echo $label;
			}
		}
	}
}
