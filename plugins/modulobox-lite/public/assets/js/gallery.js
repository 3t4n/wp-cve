/**
 * ModuloBox Gallery PACKAGED v1.0.0
 * A simple justified layout
 *
 * @author Themeone [https://theme-one.com/]
 * Copyright © 2016 All Rights Reserved.
 */

/* global define, module, ModuloBox_Gallery */

( function( root, factory ) {

	if ( typeof define === 'function' && define.amd ) {
		// AMD
		define( factory );
	} else if ( typeof exports === 'object' && module.exports ) {
		// Node, CommonJS-like
		module.exports = factory();
	} else {
		// Browser globals (root is window)
		root.ModuloBox_Gallery = factory();
	}

}( window, function factory() {

	'use strict';

	// globally unique identifiers
	var GUID = 0;
	// internal store of all ModuloBox_Gallery intances
	var instances = {};
	// default settings
	var defaults = {
		rowHeight : 220,
		spacing   : 4
	};

	/**
	 * ModuloBox_Gallery
	 * @constructor
	 * @param {*} element
	 * @param {Object} options
	 */
	function ModuloBox_Gallery( element, options ) {

		// query element
		if ( typeof element === 'string' ) {
    		element = document.querySelector( element );
		}

		// if no element
		if ( ! element ) {
			return false;
		}

		// do not initialize twice on same element
		if ( element && element.GUID ) {

			var instance = instances[ element.GUID ];
			return instance;

		}

		// set defaults
		this.element = element;
		this.options = this.extend( defaults, options );

		// set negative margin to match full width
		var margin = parseInt( this.options.spacing, 10 );
		this.element.style.margin =  margin > 0 ? '0 ' + ( - margin / 2 ) + 'px' : '';

		// add guid for current instance
		var guid = this.guid = ++GUID;
		this.element.GUID = guid;
		instances[ guid ] = this;

		this.init();

	}

	var proto = ModuloBox_Gallery.prototype;

	/**
	 * Initialize ModuloBox Gallery
	 */
	proto.init = function() {

		// layout gallery
		this.getGalleryWidth();
		this.getItems();
		this.layout();

		// add resize event listener
		window.addEventListener( 'resize', this.resize.bind(this), true );
		// make sure the layout is correct after the window is fully loaded
		window.addEventListener( 'load', this.resize.bind(this), false );

	}

	/**
	 * Extend an Object
	 * @param {Object} a
	 * @param {Object} b
	 * @return {Object} a
	 */
	proto.extend = function( a, b ) {

		for ( var prop in b ) {
			if ( b.hasOwnProperty( prop) ) {
				a[ prop ] = b[ prop ];
			}
		}

		return a;

	};

	/**
	 * Get gallery width
	 */
	proto.getGalleryWidth = function() {

		this.width = parseInt( getComputedStyle( this.element, null ).width, 10 );

	};

	/**
	 * Get items in gallery
	 */
	proto.getItems = function() {
		
		this.items = [];
		var items  = this.element.querySelectorAll( 'img' );

		for ( var i = 0, l = items.length; i < l; i++ ) {
	
			var item   = this.items[i] = {};
			var holder = items[i].parentNode;

			while( holder.tagName !== 'FIGURE' ) {
     			holder = holder.parentNode;
		    }

			item.holder = holder;
			item.height = items[i].getAttribute( 'height' );
			item.width  = items[i].getAttribute( 'width' );
			item.width  = item.width * ( this.options.rowHeight / item.height );

		}

	};

	/**
	 * Set item size in width/height and margin
	 */
	proto.setItemSize = function( item ) {

		item.holder.style.width  = item.width + 'px';
		item.holder.style.height = item.height + 'px';
		item.holder.style.margin = this.options.spacing / 2 + 'px';

	};

	/**
	 * Layout grid items
	 */
	proto.layout = function() {

        var rowWidth = 0,
			rowRatio = 1,
			rowItems = [];
		
        for ( var i = 0, il = this.items.length; i < il; i++ ) {

			rowItems.push({
				holder : this.items[i].holder,
				height : this.items[i].height,
				width  : this.items[i].width
			});

            rowWidth += this.items[i].width + this.options.spacing;

            if ( rowWidth >= this.width ) {

                var totalWidth = 0,
					margins    = rowItems.length * this.options.spacing;
					rowRatio   = ( this.width - margins ) / ( rowWidth - margins );

                for ( var r = 0, rl = rowItems.length; r < rl; r++ ) {

					rowItems[r].height = Math.ceil( this.options.rowHeight * rowRatio );
                    rowItems[r].width  = Math.ceil( rowItems[r].width * rowRatio);
					totalWidth += rowItems[r].width + this.options.spacing;

					if ( totalWidth > this.width ) {
						rowItems[r].width -= totalWidth > this.width ? totalWidth - this.width : 0;
					}

					this.setItemSize( rowItems[r] );

                }

                rowItems = [];
				rowWidth = 0;

            }

        }

        // layout last row
        for ( var l = 0, ll = rowItems.length; l < ll; l++ ) {

			rowItems[l].width  = Math.floor( rowItems[l].width * rowRatio );
			rowItems[l].height = Math.floor( this.options.rowHeight * rowRatio );
            this.setItemSize( rowItems[l] );

        }

        // If gallery width changed (scrollbar added/removed)
		this.resize();

    };

	/**
	 * Resize event
	 */
	proto.resize = function( a, b ) {

		// store old gallery width
		var oldWidth = this.width;
		// get new width
		this.getGalleryWidth();

		// if the width change
        if ( oldWidth !== this.width ) {
			this.layout();
		}

	};

	return ModuloBox_Gallery;

}));

( function() {

	'use strict';

	var galleries = document.querySelectorAll( '.mobx-gallery' );

	for ( var i = 0, l = galleries.length; i < l; i++ ) {

		var settings = JSON.parse( galleries[i].getAttribute( 'data-settings' ) );
		galleries[i].removeAttribute( 'data-settings' );

		new ModuloBox_Gallery( galleries[i], settings );

	}

} )();
