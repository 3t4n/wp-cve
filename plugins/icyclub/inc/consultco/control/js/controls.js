var consultup = {

	/**
	 * An object containing definitions for controls.
	 */
	

	/**
	 * An object containing definitions for input fields.
	

	/**
	 * An object containing definitions for settings.
	 */
	setting: {

		/**
		 * Gets the value of a setting.
		 *
		 * This is a helper function that allows us to get the value of
		 * control[key1][key2] for example, when the setting used in the
		 * customizer API is "control".
		 *
		 * @param {string} [setting] The setting for which we're getting the value.
		 * @returns {mixed} Depends on the value.
		 */
		

		/**
		 * Sets the value of a setting.
		 *
		 * This function is a bit complicated because there any many scenarios to consider.
		 * Example: We want to save the value for my_setting[something][3][something-else].
		 * The control's setting is my_setting[something].
		 * So we need to find that first, then figure out the remaining parts,
		 * merge the values recursively to avoid destroying my_setting[something][2]
		 * and also take into account any defined "key" arguments which take this even deeper.
		 *
		 * @param {object|string} [element] The DOM element whose value has changed,
		 *                                  or an ID.
		 * @param {mixed}         [value]   Depends on the control-type.
		 * @param {string}        [key]     If we only want to save an item in an object
		 *                                  we can define the key here.
		 * @returns {void}
		 */
		set: function ( element, value, key ) {
			var setting,
			    parts,
			    currentNode   = '',
			    foundNode     = '',
			    subSettingObj = {},
			    currentVal,
			    subSetting,
			    subSettingParts;

			// Get the setting from the element.
			setting = element;
			if ( _.isObject( element ) ) {
				if ( jQuery( element ).attr( 'data-id' ) ) {
					setting = element.attr( 'data-id' );
				} else {
					setting = element.parents( '[data-id]' ).attr( 'data-id' );
				}
			}

			(
				parts = setting.split( '[' )
			),

				// Find the setting we're using in the control using the customizer API.
				_.each( parts, function ( part, i ) {
					part = part.replace( ']', '' );

					// The current part of the setting.
					currentNode = 0 === i ? part : '[' + part + ']';

					// When we find the node, get the value from it.
					// In case of an object we'll need to merge with current values.
					if ( !_.isUndefined( wp.customize.instance( currentNode ) ) ) {
						foundNode  = currentNode;
						currentVal = wp.customize.instance( foundNode ).get();
					}
				} );

			// Get the remaining part of the setting that was unused.
			subSetting = setting.replace( foundNode, '' );

			// If subSetting is not empty, then we're dealing with an object
			// and we need to dig deeper and recursively merge the values.
			if ( '' !== subSetting ) {
				if ( !_.isObject( currentVal ) ) {
					currentVal = {};
				}
				if ( '[' === subSetting.charAt( 0 ) ) {
					subSetting = subSetting.replace( '[', '' );
				}
				subSettingParts = subSetting.split( '[' );
				_.each( subSettingParts, function ( subSettingPart, i ) {
					subSettingParts[i] = subSettingPart.replace( ']', '' );
				} );

				// If using a key, we need to go 1 level deeper.
				if ( key ) {
					subSettingParts.push( key );
				}

				// Converting to a JSON string and then parsing that to an object
				// may seem a bit hacky and crude but it's efficient and works.
				subSettingObj =
					'{"' +
					subSettingParts.join( '":{"' ) +
					'":"' +
					value +
					'"' +
					'}'.repeat( subSettingParts.length );
				subSettingObj = JSON.parse( subSettingObj );

				// Recursively merge with current value.
				jQuery.extend( true, currentVal, subSettingObj );
				value = currentVal;
			} else {
				if ( key ) {
					currentVal      = !_.isObject( currentVal ) ? {} : currentVal;
					currentVal[key] = value;
					value           = currentVal;
				}
			}
			wp.customize.control( foundNode ).setting.set( value );
		}
	}
};

(
	function () {
		'use strict';

		/**
		 * A dynamic color-alpha control.
		 *
		 * @class
		 * @augments wp.customize.Control
		 * @augments wp.customize.Class
		 */
		wp.customize.consultupDynamicControl = wp.customize.Control.extend( {
			

			/**
			 * Add bidirectional data binding links between inputs and the setting(s).
			 *
			 * This is copied from wp.customize.Control.prototype.initialize(). It
			 * should be changed in Core to be applied once the control is embedded.
			 *
			 * @private
			 * @returns {void}
			 */
			_setUpSettingRootLinks: function () {
				var control = this,
				    nodes   = control.container.find( '[data-customize-setting-link]' );

				nodes.each( function () {
					var node = jQuery( this );

					wp.customize( node.data( 'customizeSettingLink' ), function ( setting ) {
						var element = new wp.customize.Element( node );
						control.elements.push( element );
						element.sync( setting );
						element.set( setting() );
					} );
				} );
			},

			/**
			 * Add bidirectional data binding links between inputs and the setting properties.
			 *
			 * @private
			 * @returns {void}
			 */
			_setUpSettingPropertyLinks: function () {
				var control = this,
				    nodes;

				if ( !control.setting ) {
					return;
				}

				nodes = control.container.find( '[data-customize-setting-property-link]' );

				nodes.each( function () {
					var node         = jQuery( this ),
					    element,
					    propertyName = node.data( 'customizeSettingPropertyLink' );

					element = new wp.customize.Element( node );
					control.propertyElements.push( element );
					element.set( control.setting()[propertyName] );

					element.bind( function ( newPropertyValue ) {
						var newSetting = control.setting();
						if ( newPropertyValue === newSetting[propertyName] ) {
							return;
						}
						newSetting               = _.clone( newSetting );
						newSetting[propertyName] = newPropertyValue;
						control.setting.set( newSetting );
					} );
					control.setting.bind( function ( newValue ) {
						if ( newValue[propertyName] !== element.get() ) {
							element.set( newValue[propertyName] );
						}
					} );
				} );
			},

			

			/**
			 * Embed the control in the document.
			 *
			 * Override the embed() method to do nothing,
			 * so that the control isn't embedded on load,
			 * unless the containing section is already expanded.
			 *
			 * @returns {void}
			 */
			embed: function () {
				var control   = this,
				    sectionId = control.section();

				if ( !sectionId ) {
					return;
				}

				wp.customize.section( sectionId, function ( section ) {
					if (
						'consultup-expanded' === section.params.type ||
						section.expanded() ||
						wp.customize.settings.autofocus.control === control.id
					) {
						control.actuallyEmbed();
					} else {
						section.expanded.bind( function ( expanded ) {
							if ( expanded ) {
								control.actuallyEmbed();
							}
						} );
					}
				} );
			},

			/**
			 * Deferred embedding of control when actually
			 *
			 * This function is called in Section.onChangeExpanded() so the control
			 * will only get embedded when the Section is first expanded.
			 *
			 * @returns {void}
			 */
			actuallyEmbed: function () {
				var control = this;
				if ( 'resolved' === control.deferred.embedded.state() ) {
					return;
				}
				control.renderContent();
				control.deferred.embedded.resolve(); // This triggers control.ready().
			},

			/**
			 * This is not working with autofocus.
			 *
			 * @param {object} [args] Args.
			 * @returns {void}
			 */
			focus: function ( args ) {
				var control = this;
				control.actuallyEmbed();
				wp.customize.Control.prototype.focus.call( control, args );
			},

			

			
		} );
	}()
);



/**
 * Control: Sortable.
 */
/* global consultupControlLoader */
wp.customize.controlConstructor['consultup-sortable'] = wp.customize.Control.extend( {

	// When we're finished loading continue processing
	ready: function () {
		'use strict';

		var control = this;

		// Init the control.
		if (
			!_.isUndefined( window.consultupControlLoader ) &&
			_.isFunction( consultupControlLoader )
		) {
			consultupControlLoader( control );
		} else {
			control.initconsultupControl();
		}
	},

	initconsultupControl: function () {
		'use strict';

		var control = this;
		control.container.find( '.consultup-controls-loading-spinner' ).hide();

		// Set the sortable container.
		control.sortableContainer = control.container.find( 'ul.sortable' ).first();

		// Init sortable.
		control.sortableContainer
		       .sortable( {

			       // Update value when we stop sorting.
			       stop: function () {
				       control.updateValue();
			       }
		       } )
		       .disableSelection()
		       .find( 'li' )
		       .each( function () {

			       // Enable/disable options when we click on the eye of Thundera.
			       jQuery( this )
				       .find( 'i.visibility' )
				       .click( function () {
					       jQuery( this )
						       .toggleClass( 'dashicons-visibility-faint' )
						       .parents( 'li:eq(0)' )
						       .toggleClass( 'invisible' );
				       } );
		       } )
		       .click( function () {

			       // Update value on click.
			       control.updateValue();
		       } );
	},

	
} );