/* global catalogappLocalizer */
import React, { Component } from 'react';
import { render } from 'react-dom';
import { BrowserRouter as Router, useLocation } from 'react-router-dom';
import MvxTab from './tabs';
import Banner7 from '../../../woocommerce-catalog-enquiry-pro/src/index';

class Catalog_Backend_Endpoints_Load extends Component {
	constructor( props ) {
		super( props );
		this.state = {};
		this.Catalog_backend_endpoint_load =
			this.Catalog_backend_endpoint_load.bind( this );
	}

	useQuery() {
		return new URLSearchParams( useLocation().hash );
	}

	Catalog_backend_endpoint_load() {
		// For active submneu pages
		const $ = jQuery;
		const menuRoot = $( '#toplevel_page_' + 'catalog' );
		const currentUrl = window.location.href;

		const currentPath = currentUrl.substr(
			currentUrl.indexOf( 'admin.php' )
		);

		menuRoot.on( 'click', 'a', function () {
			const self = $( this );

			$( 'ul.wp-submenu li', menuRoot ).removeClass( 'current' );

			if ( self.hasClass( 'wp-has-submenu' ) ) {
				$( 'li.wp-first-item', menuRoot ).addClass( 'current' );
			} else {
				self.parents( 'li' ).addClass( 'current' );
			}
		} );

		$( 'ul.wp-submenu a', menuRoot ).each( function ( index, el ) {
			if ( $( el ).attr( 'href' ) === currentPath ) {
				$( el ).parent().addClass( 'current' );
			} else {
				$( el ).parent().removeClass( 'current' );

				// if user enter page=catalog
				if (
					$( el ).parent().hasClass( 'wp-first-item' ) &&
					currentPath === 'admin.php?page=catalog'
				) {
					$( el ).parent().addClass( 'current' );
				}
			}
		} );
		const location = this.useQuery();
		if ( location.get( 'tab' ) && location.get( 'tab' ) === 'settings' ) {
			return (
				<MvxTab
					model="catalog-settings"
					query_name={ location.get( 'tab' ) }
					subtab={ location.get( 'subtab' ) }
					funtion_name={ this }
				/>
			);
		} else if (
			location.get( 'tab' ) &&
			location.get( 'tab' ) === 'customer'
		) {
			return <Banner7 query_name={ location.get( 'tab' ) } />;
		}
		return <div>sdfsd</div>;
	}

	render() {
		return (
			<Router>
				<this.Catalog_backend_endpoint_load />
			</Router>
		);
	}
}
export default Catalog_Backend_Endpoints_Load;
