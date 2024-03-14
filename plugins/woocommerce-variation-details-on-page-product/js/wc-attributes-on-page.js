(function(){
	'use strict';

	var Sorter = {
		data   : window.mp_wc_variations,
		els    : {},
		values : {},

		/*
		 * Control existence of DOM objects
		 */
		checkConditions : function() {
			var els = this.els;

			if ( 0 === els.hook.length ) {
				console.log( '%cError VDOPP:', 'font-weight: bold; color: red', 'Sorry, it seems that I can\'t find the DOM object were data will be hooked.' );
				return false;
			}

			return true;
		},

		setVars : function() {
			var data = this.data,
				els = this.els,
				values = this.values,
				variations = this.data.variations.replace(/&quot;/g, '"'),
				property;

			values.placeholder = data.att_data_sel;
			values.beforeSize = data.att_before_size;
			values.beforeWeight = data.att_before_weight;
			values.afterSize = data.att_after_size;
			values.afterWeight = data.att_after_weight;
			values.variations = JSON.parse( variations );

			if ( '.' === values.placeholder.charAt(0) ) {
				values.typeDataSelector = 'class';
			} else if ( '#' === values.placeholder.charAt(0) ) {
				values.typeDataSelector = 'id';
			} else {
				console.log( '%cError VDOPP:', 'font-weight: bold; color: red', 'Misconfiguration on Data Selector. Please, verify first char.' );
				return false;
			}

			values.dataSelector = values.placeholder.substring(1);

			els.selectors = {};
			for ( property in values.variations[0] ) {
				if ( values.variations[0].hasOwnProperty( property ) ) {
					if ( -1 !== property.indexOf( 'attribute_' ) ) {
						els.selectors[ property.replace( 'attribute_', '' ) ] = document.querySelectorAll( '[name="' + property + '"]' );
					}
				}
			}

			// Allow override the hook by the shortcode
			if ( null !== document.querySelector( '.mp_wc_vdopp_variations' ) ) {
				els.hook = document.querySelector( '.mp_wc_vdopp_variations' );
			}

			// Create placeholder and append it to hook
			els.placeholder = document.createElement('DIV');
			els.placeholder.setAttribute( values.typeDataSelector, values.dataSelector );
			els.hook.appendChild( els.placeholder );

			return true;
		},

		cleanup : function() {
			var els = this.els,
				variations = this.values.variations,
				variationAttributes = [],
				counterAttributes = {},
				tempSelectors = {},
				variation, attribute;

			// Collect all attributes
			for ( variation in els.selectors ) {
				if ( ! els.selectors.hasOwnProperty( variation ) ) {
					continue;
				}
				if ( '' === els.selectors[ variation ][0].id ) {
					return false;
				}

				if ( -1 === variationAttributes.indexOf( 'attribute_' + variation ) ) {
					variationAttributes[ variation ] = 'attribute_' + variation;
				}
			}

			// Check what attributes are used for variations
			for ( attribute in variationAttributes ) {
				if ( ! variationAttributes.hasOwnProperty( attribute ) ) {
					continue;
				}
				counterAttributes[ variationAttributes[ attribute ] ] = 0;

				for ( variation in variations ) {
					if ( ! variations.hasOwnProperty( variation ) ) {
						continue;
					}

					if ( 0 !== variations[ variation ][ variationAttributes[ attribute ] ].length ) {
						counterAttributes[ variationAttributes[ attribute ] ]++;
					}
				}
			}

			// Remove selectors not used for variations
			for ( attribute in els.selectors ) {
				if ( ! els.selectors.hasOwnProperty( attribute ) ) {
					continue;
				}
				if ( 0 !== counterAttributes[ 'attribute_' + attribute ] ) {
					tempSelectors[ attribute ] = els.selectors[ attribute ];
				}
			}

			els.selectors = tempSelectors;

			els.selectorType = {};

			// Check if valid/supported selector
			for ( variation in els.selectors ) {
				if ( ! els.selectors.hasOwnProperty( variation ) ) {
					continue;
				}

				els.selectorType[ variation ] = els.selectors[ variation ][0].tagName;

				if ( -1 === ['SELECT', 'INPUT'].indexOf( els.selectorType[ variation ] ) ) {
					console.log( '%cError VDOPP:', 'font-weight: bold; color: red', 'This plugin is intended to work only with dropdown lists and radio buttons as variations selectors.' );
					return false;
				}

			}

			delete this.data;

			return true;
		},

		filter : function( index ) {
			if ( this.variations[ index ][ 'attribute_' + this.variation ][0] !== this.value && this.variations[ index ][ 'attribute_' + this.variation ][1] !== this.value ) {
				delete this.dirtyVariations[ index ];
			}
		},

		toogleData : function() {
			var els = this.els,
				selectors = this.els.selectors,
				values = this.values,
				variations = this.values.variations,
				selection = {},
				filter = {},
				selectedVariation = [],
				string = [],
				dirtySelectedVariation, variation, index, value;

			for ( variation in selectors ) {
				if ( ! selectors.hasOwnProperty( variation ) ) {
					continue;
				}

				for ( index in selectors[ variation ] ) {
					if ( ! selectors[ variation ].hasOwnProperty( index ) ) {
						continue;
					}

					switch ( els.selectorType[ variation ] ) {
					case 'SELECT' :
						value = selectors[ variation ][ index ].value;
						if ( '' === value ) {
							delete selection[ selectors[ variation ][ index ].id ];
						} else {
							selection[ selectors[ variation ][ index ].id ] = value;
						}
						break;

					case 'INPUT' :
						if ( selectors[variation][index].checked ) {
							selection[ selectors[ variation ][ index ].id ] = selectors[variation][index].value;
						}
						break;

					default :
						break;

					}
				}
			}

			if ( Object.keys( selection ).length !== Object.keys( els.selectorType ).length ) {
				els.placeholder.innerHTML = '';
				return;
			}

			dirtySelectedVariation = JSON.parse( JSON.stringify( variations ) );

			for ( variation in selection ) {
				if ( ! selection.hasOwnProperty(variation) ) {
					continue;
				}

				filter = {
					value           : selection[ variation ],
					variation       : variation,
					variations      : variations,
					dirtyVariations : dirtySelectedVariation
				};

				Object.keys( variations ).forEach( this.filter.bind( filter ) );
			}

			for ( index in dirtySelectedVariation ) {
				if ( ! dirtySelectedVariation.hasOwnProperty( index ) ) {
					continue;
				}

				selectedVariation.push( dirtySelectedVariation[ index ] );
			}

			selectedVariation = selectedVariation.shift();

			if ( 0 < selectedVariation.dimensions.length ) {
				string.push( values.beforeSize + selectedVariation.dimensions + values.afterSize );
			}

			if ( 0 < selectedVariation.weight.length ) {
				string.push( values.beforeWeight + selectedVariation.weight + values.afterWeight );
			}

			els.placeholder.innerHTML = '<div ' + values.typeDataSelector + '="' + values.dataSelector + '">' + string.join( '<br>' ) + '</div>';
		},

		addListner : function() {
			var selectors = this.els.selectors,
				variation, index;

			for ( variation in selectors ) {
				if ( ! selectors.hasOwnProperty( variation ) ) {
					continue;
				}

				for ( index in selectors[ variation ] ) {
					if ( ! selectors[ variation ].hasOwnProperty( index ) ) {
						continue;
					}

					selectors[ variation ][ index ].addEventListener( 'change', this.toogleData.bind( this ) );
					selectors[ variation ][ index ].addEventListener( 'select', this.toogleData.bind( this ) );
				}
			}
		},

		init : function() {
			var data = this.data,
				els = this.els,
				controlPass;

			if ( 'undefined' === typeof data ) {
				return;
			}

			els.selectors = [].slice.call( document.querySelectorAll( data.att_dom_sel ) );
			els.hook = document.querySelector( data.att_data_hook );

			controlPass = this.checkConditions();
			if ( false === controlPass ) {
				return;
			}

			controlPass = this.setVars();
			if ( false === controlPass ) {
				return;
			}

			controlPass = this.cleanup();
			if ( false === controlPass ) {
				return;
			}

			this.addListner();
			this.toogleData();
		}
	};

	Sorter.init();

})();
