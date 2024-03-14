/* global appLocalizer */
import React, { Component } from 'react';
class Header extends Component {
	// sliderjs start-------

	componentDidMount() {
		var $ = jQuery;
		var cs = 1;

		// to show default banner at first -------
		$( '#txt0' ).show();
		$( '.mvx-pro-txt:not(#txt0)' ).hide();

		$( document ).ready( function () {
			$( '.mvx-pro-txt' ).each( function ( e ) {
				if ( e != 0 ) $( this ).hide();
			} );

			// preview button click function ------
			$( '.p-prev' ).click( function () {
				if ( cs > 1 ) {
					cs--;

					if (
						$( '.mvx-logo-top .mvx-pro-txt:visible' ).prev()
							.length != 0
					) {
						$( '.mvx-logo-top .mvx-pro-txt:visible' )
							.prev()
							.show()
							.next()
							.hide();
						$( '.mvx-pro-txt' ).css(
							'animation',
							'.5s slide-right'
						);
					} else {
						$( '.mvx-logo-top .mvx-pro-txt:visible' ).show();
						$( '.mvx-logo-top .mvx-pro-txt:first' ).hide();
					}
				}
				return false;
			} );

			// next button click function ------
			$( '.p-next' ).click( function () {
				if ( cs < 4 ) {
					cs++;

					if (
						$( '.mvx-logo-top .mvx-pro-txt:visible' ).next()
							.length != 0
					) {
						$( '.mvx-logo-top .mvx-pro-txt:visible' )
							.next()
							.show()
							.prev()
							.hide();
						$( '.mvx-pro-txt' ).css(
							'animation',
							'.5s slide-left'
						);
					} else {
						$( '.mvx-logo-top .mvx-pro-txt:visible' ).show();
						$( '.mvx-logo-top .mvx-pro-txt:first' ).hide();
					}
				}
				return false;
			} );
		} );

		//slider js end here------
	}

	render() {
		return (
			<>
			{catalogappLocalizer.pro_active ?
			<div className="mvx-sidebar">
				<div className="mvx-banner-top">
					<div className="mvx-logo-top">
						<div className="mvx-pro-txt" id="txt0">
							<div className="mvx-pro-txt-items">
								<h3>
									Upgrade to WooCommerce Quote and Product
									Enquiry{ ' ' }
								</h3>
								<p>
									To unlock advanced catalog features, try
									WooCommerce Quote and Product Enquiry. Go
									Pro!{ ' ' }
								</p>
								<a
									href="https://multivendorx.com/woocommerce-request-a-quote-product-catalog/"
									className="mvx-btn btn-red"
								>
									Go to pro
								</a>
							</div>
						</div>
						<div className="mvx-pro-txt" id="txt1">
							<div className="mvx-pro-txt-items">
								<h3>Customisable Enquiry Form</h3>
								<p>
									Create a fully customizable product inquiry
									form by using a variety of options. Go Pro!{ ' ' }
								</p>
								<a
									href="https://multivendorx.com/woocommerce-request-a-quote-product-catalog/"
									className="mvx-btn btn-red"
								>
									Go to pro
								</a>
							</div>
						</div>
						<div className="mvx-pro-txt" id="txt2">
							<div className="mvx-pro-txt-items">
								<h3>Multi-Enquiry Mini Cart</h3>
								<p>
									Send enquiries about different products from
									different vendors to the same enquiry cart
									with mini cart option.{ ' ' }
								</p>
								<a
									href="https://multivendorx.com/woocommerce-request-a-quote-product-catalog/"
									className="mvx-btn btn-red"
								>
									Go to pro
								</a>
							</div>
						</div>
						<div className="mvx-pro-txt" id="txt3">
							<div className="mvx-pro-txt-items">
								<h3>Manage Checkout Configurations</h3>
								<p>
									Enable both catalog and checkout mode
									together.{ ' ' }
								</p>
								<a
									href="https://multivendorx.com/woocommerce-request-a-quote-product-catalog/"
									className="mvx-btn btn-red"
								>
									Go to pro
								</a>
							</div>
						</div>
					</div>
					<div className="message-banner-sliding">
						<a href="#" className="p-prev">
							<i className="mvx-catalog icon-previous" />
						</a>
						<a href="#" className="p-next">
							<i className="mvx-catalog icon-next" />
						</a>
					</div>
				</div>
			</div>
			: ''}
			</>
		);
	}
}
export default Header;
