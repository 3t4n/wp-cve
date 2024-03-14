/*
 * Social Rocket Blocks for Gutenberg
 */

// socialrocket (inline buttons)
( function( wp, $ ) {

	var __ = wp.i18n.__;
	var el = wp.element.createElement;
	var InspectorControls = wp.editor.InspectorControls;
	var registerBlockType = wp.blocks.registerBlockType;
	var RadioControl = wp.components.RadioControl;
	var SelectControl = wp.components.SelectControl;
	var ServerSideRender = wp.components.ServerSideRender;
	var TextControl = wp.components.TextControl;
	
	var blockStyle = {
		color: '#252525',
		padding: '20px',
		fontStyle: 'italic'
	};
	
	var iconEl = el('svg', { width: 20, height: 20, viewBox: "0 0 1792 1792" },
		el('path', {d: "M1504 448q0-40-28-68t-68-28-68 28-28 68 28 68 68 28 68-28 28-68zm224-288q0 249-75.5 430.5t-253.5 360.5q-81 80-195 176l-20 379q-2 16-16 26l-384 224q-7 4-16 4-12 0-23-9l-64-64q-13-14-8-32l85-276-281-281-276 85q-3 1-9 1-14 0-23-9l-64-64q-17-19-5-39l224-384q10-14 26-16l379-20q96-114 176-195 188-187 358-258t431-71q14 0 24 9.5t10 22.5z" })
	);
	
	var setupDone = false;
	
	// this will be used to populate the hidden "networks" attribute.
	// done only once, and order is not important here.
	var networks = [{
		label: 'Default',
		value: '',
	}];
	
	// this will be used to populate the visible "selected networks" sortable
	// done each time edit() is invoked, and order DOES matter here
	var els = [];
	var networksOrder = [];
	var networksEls = function(){
	
		if ( typeof socialRocketAdmin !== "undefined" && socialRocketAdmin.blockChanged ) {
			
			els = [];
			networksOrder = [];
			
			// not an instance of the block, just the data from it.
			var block = currentSelectedBlock();
			
			$.each( block.attributes.networks, function( key, value ) {
				if ( value > '' ) {
					networksOrder.push( { 'key': value, 'value': socialRocketAdmin.networks[value] } );
				}
			});
			$.each( socialRocketAdmin.networks, function( key, value ) {
				if ( $.inArray( key, block.attributes.networks ) === -1 ) {
					networksOrder.push( { 'key': key, 'value': value } );
				}
			});
			
			$.each( networksOrder, function( index, network ) {
				els.push(
					el( 
						'tr',
						{}, 
						el(
							'td',
							{
								class: ( $.inArray( network.key, block.attributes.networks ) > -1 ? 'selected' : '' ),
								'data-network': network.key
							}, 
							network.value )
					),
				);
			})
			
			socialRocketAdmin.blockChanged = false;
		}
		return els;
	};
	
	// initializes our jQuery sortable once gutenberg is good and ready
	var initBlockNetworksSortable = function(){
		/*
		 * This is stupid.  Because gutenberg has no fucking events we can hook into, we have to
		 * watch for any and all changes and wait until just the right sequence of events has
		 * taken place before we can init our jQuery sortable.
		 *
		 * Seriously guys, "onAdd" or "onEdit" events would be nice here.  Obviously gutenberg knows
		 * when a block is added or edited, so why can't it trigger an event at the same time, so
		 * other things (i.e. plugins like ours) could attach a callback?
		 * 
		 * If it did, this would make our lives so much easier dealing with gutenberg!  But no, 
		 * apparently we just can't have nice things under gutenberg.  As stated in one of many issues
		 * over at the gutenberg repo (https://github.com/WordPress/gutenberg/issues/8655):
		 *
		 * "There are no events in Gutenberg. The idea is that there are selectors that can give you
		 * at anytime the data in Gutenberg (for example the list of blocks in the post) and there's
		 * a unique event emitter you can use to track whether the state (entire state) change or not."
		 *
		 * In other words, we can't have a simple and straightforward solution.  :(
		 */
		if (
			// wait until our networkEls have been inserted into the DOM. This means the inspector editor is open.
			$( '#social-rocket-block-networks-sortable-body' ).length &&
			// ...AND make sure what's currently there isn't already initialized, which would mean its 
			// from a previous block that was selected earlier.  We have to wait until that is completely
			// cleared out and our fresh set of (non-initialized) elements are inserted.  Sheesh.
			! $( '#social-rocket-block-networks-sortable-body' ).hasClass( 'ui-sortable' )
		) {
			$( '#social-rocket-block-networks-sortable-body' ).sortable({
				cursor: 'move',
				start: function( event, ui ) {
					var width = $( '#social-rocket-block-networks-sortable-body' ).width();
					ui.helper.children('td').width(width);
				},
				update: function( event, ui ) {
					updateNetworksAttribute();
				}
			});
		} else {
			setTimeout(initBlockNetworksSortable, 1);
		}
	};
	
	// update the hidden networks attribute
	var updateNetworksAttribute = function(){
		var block = currentSelectedBlock();
		var order = [];
		$( '#social-rocket-block-networks-sortable-body' ).find('.selected').each(function(){
			order.push( $(this).data('network') );
		});
		if (
			typeof socialRocketAdmin !== "undefined" &&
			typeof socialRocketAdmin.socialRocketBlocks !== "undefined"
		) {
			/*
			 * This is stupid, but we have no choice.  Our currentSelectedBlock() function can't
			 * get the actual instance of the block, just some data from it.  For example,
			 * currentSelectedBlock() will give us the block attirbutes, but we can't call 
			 * setAttributes() on it.  So we have to get to the real instance another way.  The
			 * only time we have access to the instance is while we're in the edit()
			 * function.  So we save a reference during edit(), and access that when we get here:
			 */
			socialRocketAdmin.socialRocketBlocks[ block.clientId ].setAttributes( { networks: order } );
		}
	};

	// register the block
	registerBlockType( 'social-rocket/socialrocket', {
		
		title: __( '[Social Rocket] Inline Buttons', 'social-rocket' ),
		icon: iconEl,
		category: 'common',
		
		edit: function( props ) {
	
			// This is stupid, but it gives us a way to access the full block instance outside this function,
			// since there's no other fucking way to get the block instance that I can figure out.
			if ( typeof socialRocketAdmin.socialRocketBlocks === "undefined" ) {
				socialRocketAdmin.socialRocketBlocks = {};
			}
			socialRocketAdmin.socialRocketBlocks[props.clientId] = props;
			
			if ( ! setupDone ) {
				jQuery.each( socialRocketAdmin.networks, function( key, value ) {
					networks.push({
						label: value,
						value: key,
					});
				});
				setupDone = true;
			}
			
			return [
				el( ServerSideRender, {
					block: 'social-rocket/socialrocket',
					attributes: props.attributes,
				} ),
				el( InspectorControls, {},
					el( TextControl, {
						label: __('Heading text (optional)', 'social-rocket'),
						value: props.attributes.heading,
						onChange: ( value ) => { props.setAttributes( { heading: value } ); },
					} ),
					el(
						RadioControl,
						{
							id: 'social-rocket-block-networks-toggle',
                            label: __('Networks', 'social-rocket'),
							selected: props.attributes._networks_toggle,
							options: [
								{ value: 'default', label: __('Default (current active networks)', 'social-rocket') },
								{ value: 'custom', label: __('Selected networks', 'social-rocket') }
							],
							onChange: ( value ) => {
								if ( value === 'custom' ) {
									$( '#social-rocket-block-networks-sortable' ).slideDown();
									updateNetworksAttribute();
								} else {
									$( '#social-rocket-block-networks-sortable' ).slideUp();
									props.setAttributes( { networks: [''] } );
								}
								props.setAttributes( { _networks_toggle: value } ); 
							},
						}
					),
					el(
						'table',
						{
							id: 'social-rocket-block-networks-sortable',
							style: { display: ( props.attributes._networks_toggle === 'custom' ? 'block' : 'none' ) }
						},
						[
							el(
								'thead',
								{},
								el(
									'tr',
									{},
									el(
										'td',
										{},
										__('Click to select and drag/drop to reorder:', 'social-rocket')
									)
								)
							),
							el(
								'tbody',
								{
									id: 'social-rocket-block-networks-sortable-body'
								},
								networksEls()
							)
						]
					),
					el(
                        SelectControl,
                        {
							id: 'social-rocket-block-networks',
							multiple: true,
                            value: props.attributes.networks,
							onChange: ( value ) => { props.setAttributes( { networks: value } ); },
                            options: networks
                        }
                    ),
					el(
                        SelectControl,
                        {
                            label: __('Show Counts', 'social-rocket'),
                            value: props.attributes.show_counts,
							onChange: ( value ) => { props.setAttributes( { show_counts: value } ); },
                            options: [
								{ value: '', label: __('Default', 'social-rocket') },
								{ value: 'true', label: __('Yes', 'social-rocket') },
								{ value: 'false', label: __('No', 'social-rocket') }
							]
                        }
                    ),
					el(
                        SelectControl,
                        {
                            label: __('Show Total', 'social-rocket'),
                            value: props.attributes.show_total,
							onChange: ( value ) => { props.setAttributes( { show_total: value } ); },
                            options: [
								{ value: '', label: __('Default', 'social-rocket') },
								{ value: 'before', label: __('Before', 'social-rocket') },
								{ value: 'after', label: __('After', 'social-rocket') },
								{ value: 'none', label: __('None', 'social-rocket') }
							]
                        }
                    ),
					/* TODO: idea for a later version
					el( TextControl, {
						label: __('Social Media Title (optional; defaults to current page setting)', 'social-rocket'),
						value: props.attributes.social_media_title,
						onChange: ( value ) => { props.setAttributes( { social_media_title: value } ); },
					} ),
					el( TextControl, {
						label: __('Social Media Description (optional; defaults to current page setting)', 'social-rocket'),
						value: props.attributes.social_media_description,
						onChange: ( value ) => { props.setAttributes( { social_media_description: value } ); },
					} ),
					*/
					el( TextControl, {
						label: __('Custom Share ID (optional; leave blank to share this post/page)', 'social-rocket'),
						value: props.attributes.id,
						onChange: ( value ) => { props.setAttributes( { id: value } ); },
					} ),
					el( TextControl, {
						label: __('Custom Share Type (optional; leave blank to share this post/page)', 'social-rocket'),
						value: props.attributes.type,
						onChange: ( value ) => { props.setAttributes( { type: value } ); },
					} ),
					el( TextControl, {
						label: __('Custom Share URL (optional; leave blank to share this post/page)', 'social-rocket'),
						value: props.attributes.share_url,
						onChange: ( value ) => { props.setAttributes( { share_url: value } ); },
					} ),
				),
			];
		},
		
		save: function() {
			return null;
		}
		
	} );
	
	// handler for initializing our sortable whenever the selected block changes.
	// This is stupid, but we have no choice.  See comments above.
	var currentSelectedBlock = function(){return wp.data.select('core/editor').getSelectedBlock();}
	var lastSelectedBlockId = null;
	wp.data.subscribe(() => {
		var block = currentSelectedBlock();
		var blockId = null;
		var hasChanged = false;
		
		if ( block ) {
			blockId = block.clientId;
		}
		
		if ( blockId !== lastSelectedBlockId ) {
			hasChanged = true;
			lastSelectedBlockId = blockId;
		}
		
		if ( block && block.name === "social-rocket/socialrocket" ) {
			if ( hasChanged ) {
				socialRocketAdmin.blockChanged = true; // flag used by networksEls() to prevent duplicate runs
				initBlockNetworksSortable( block.clientId );
			}
		}
	});

	// handler for selecting/deselecting sortable list items
	$( document ).on( 'click', '#social-rocket-block-networks-sortable-body td', function() {
		$(this).toggleClass( 'selected' );
		updateNetworksAttribute();
	});
	
}( wp, jQuery ) );


// socialrocket-floating (floating buttons)
// TODO: idea for possible future use
/*
( function( blocks, i18n, element ) {
	var el = element.createElement;
	var __ = i18n.__;

	var blockStyle = {
		color: '#252525',
		padding: '20px',
		fontStyle: 'italic'
	};
	
	var iconEl = el('svg', { width: 20, height: 20, viewBox: "0 0 1792 1792" },
		el('path', {d: "M1504 448q0-40-28-68t-68-28-68 28-28 68 28 68 68 28 68-28 28-68zm224-288q0 249-75.5 430.5t-253.5 360.5q-81 80-195 176l-20 379q-2 16-16 26l-384 224q-7 4-16 4-12 0-23-9l-64-64q-13-14-8-32l85-276-281-281-276 85q-3 1-9 1-14 0-23-9l-64-64q-17-19-5-39l224-384q10-14 26-16l379-20q96-114 176-195 188-187 358-258t431-71q14 0 24 9.5t10 22.5z" })
	);

	blocks.registerBlockType( 'social-rocket/socialrocket-floating', {
		title: __( '[Social Rocket] Floating Buttons', 'social-rocket' ),
		icon: iconEl,
		category: 'common',
		edit: function() {
			return el(
				'p',
				{ style: blockStyle },
				'(your Social Rocket floating buttons will appear on this page, in the position determined by your settings)'
			);
		},
		save: function() {
			return null;
		},
	} );
}(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element
) );
*/

// socialrocket-tweet (click to tweet)
( function( wp, $ ) {

	var __ = wp.i18n.__;
	var el = wp.element.createElement;
	var InspectorControls = wp.editor.InspectorControls;
	var registerBlockType = wp.blocks.registerBlockType;
	var SelectControl = wp.components.SelectControl;
	var ServerSideRender = wp.components.ServerSideRender;
	var TextControl = wp.components.TextControl;
	var ToggleControl = wp.components.ToggleControl;

	var blockStyle = {
		color: '#252525',
		padding: '20px',
		fontStyle: 'italic'
	};
	
	var displayStyles = [];
	
	var iconEl = el('svg', { width: 20, height: 20, viewBox: "0 0 1792 1792" },
		el('path', {d: "M1504 448q0-40-28-68t-68-28-68 28-28 68 28 68 68 28 68-28 28-68zm224-288q0 249-75.5 430.5t-253.5 360.5q-81 80-195 176l-20 379q-2 16-16 26l-384 224q-7 4-16 4-12 0-23-9l-64-64q-13-14-8-32l85-276-281-281-276 85q-3 1-9 1-14 0-23-9l-64-64q-17-19-5-39l224-384q10-14 26-16l379-20q96-114 176-195 188-187 358-258t431-71q14 0 24 9.5t10 22.5z" })
	);
	
	var setupDone = false;

	registerBlockType( 'social-rocket/socialrocket-tweet', {
		
		title: __( '[Social Rocket] Click to Tweet', 'social-rocket' ),
		icon: iconEl,
		category: 'common',
		
		edit: function( props ) {
			if ( ! setupDone ) {
				jQuery.each( socialRocketAdmin.tweet_settings.saved_settings, function( key, value ) {
					displayStyles.push({
						label: value.name,
						value: key,
					});
				});
				if ( displayStyles.length === 0 ) {
					displayStyles.push({
						label: 'Default',
						value: '0',
					});
				}
				setupDone = true;
			}
			return [
				el( ServerSideRender, {
					block: 'social-rocket/socialrocket-tweet',
					attributes: props.attributes,
				} ),
				el( InspectorControls, {},
					el( TextControl, {
						label: __('Quote to be displayed on your page', 'social-rocket'),
						value: props.attributes.quote,
						onChange: ( value ) => { props.setAttributes( { quote: value } ); },
					} ),
					el( TextControl, {
						label: __('Tweet to be shared on Twitter', 'social-rocket'),
						value: props.attributes.tweet,
						onChange: ( value ) => { props.setAttributes( { tweet: value } ); },
					} ),
					el( ToggleControl, {
						label: __('Include the URL', 'social-rocket'),
						checked: props.attributes.include_url === 'true',
						onChange: ( value ) => { props.setAttributes( { include_url: value ? 'true' : 'false' } ); },
					} ),
					el( ToggleControl, {
						label: __('Include "via"', 'social-rocket'),
						checked: props.attributes.include_via === 'true',
						onChange: ( value ) => { props.setAttributes( { include_via: value ? 'true' : 'false' } ); },
					} ),
					el(
                        SelectControl,
                        {
                            label: __('Display Style', 'social-rocket'),
                            value: props.attributes.style_id,
							onChange: ( value ) => { props.setAttributes( { style_id: value } ); },
                            options: displayStyles
                        }
                    ),
					el( TextControl, {
						label: __('Custom URL (optional)', 'social-rocket'),
						value: props.attributes.url,
						onChange: ( value ) => { props.setAttributes( { url: value } ); },
					} ),
					el( TextControl, {
						label: __('Custom Via (optional)', 'social-rocket'),
						value: props.attributes.via,
						onChange: ( value ) => { props.setAttributes( { via: value } ); },
					} ),
					el( TextControl, {
						label: __('Custom CSS class (optional)', 'social-rocket'),
						value: props.attributes.add_class,
						onChange: ( value ) => { props.setAttributes( { add_class: value } ); },
					} ),
				),
			];
		},
		
		save: function() {
			return null;
		}
		
	} );
}( wp, jQuery ) );


/*
 * Extra fields for Image blocks
 */
( function() {
	
	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	
	var getExtraImageControls = function() {
		// define our extra image controls here, and filter by hook gutenberg_extra_image_controls
		var extraImageControls = [
			{
				attribute:   'data-pin-description',
				ignoreEmpty: true,
				label:       __( 'Pin Description', 'social-rocket' ),
				metaKey:     'social_rocket_pinterest_description',
				propKey:     'pinDescription',
				type:        'TextareaControl'
			}
		];
		if ( 
			typeof socialRocketAdmin !== "undefined" &&
			typeof socialRocketAdmin.hooks !== "undefined" &&
			socialRocketAdmin.hooks.gutenberg_extra_image_controls
		) {
			jQuery.each( socialRocketAdmin.hooks.gutenberg_extra_image_controls, function( key, value ) {
				extraImageControls.push( value );
			});
		}
		return extraImageControls;
	};
	
	
	/**
	 * Register our extra attributes for the image block
	 */
	function register_block_attributes_image_extras( settings, name ) {
		
		if ( name !== 'core/image' ) {
			return settings;
		}
		
		var extraImageControls = getExtraImageControls();
		
		var attributesList  = [ 'src', 'alt' ];
		var attributesToAdd = {};
		jQuery.each( extraImageControls, function( index, extraImageControl ) {
			attributesToAdd[ extraImageControl.propKey ] = {
				attribute: extraImageControl.attribute,
				type:      'string',
				selector:  'img',
				source:    'attribute',
				default:   ''
			};
			attributesList.push( extraImageControl.attribute );
		});
		
		settings.attributes = Object.assign( settings.attributes, attributesToAdd );
		
		settings.transforms.from[0] = lodash.merge( settings.transforms.from[0], {
			schema: {
				figure: {
					children: {
						a: {
							children: {
								img: {
									attributes: attributesList
								}
							}
						},
						img: {
							attributes: attributesList
						}
					}
				}
			}
		});
		
		return settings;
		
	}
	
	wp.hooks.addFilter( 'blocks.registerBlockType', 'social-rocket/image', register_block_attributes_image_extras );
	
	
	/*
	 * Fix gutenberg image blocks
	 */
	(function( $ ) {
		$(window).load(function() {
		
			var waiting = false;
			var waitForEl = function( element, callback ){
				if ( element.length ) {
					waiting = false;
					callback( element );
				} else {
					waiting = true;
					setTimeout( function(){ waitForEl( element, callback ) }, 50 );
				}
			};
			
			var needToFix = [];
			$( '.block-editor-block-list__block' ).each( function( index ) {
				if ( $( this ).hasClass( 'has-warning' ) && $( this ).data( 'type' ) === 'core/image' ) {
					needToFix.push( $( this ).attr('id') );
				}
			});
			
			function fixImageBlocks() {
				
				if ( ! needToFix.length ) {
					return; // done
				}
				
				var blockId = needToFix.splice( 0, 1 );
				
				$( '#'+blockId ).find( '.block-editor-warning__secondary .components-button' ).click();
				waitForEl( 
					$( '#editor' ).find( 'button:contains("' + wp.i18n.__('Attempt Block Recovery') + '")' ),
					function( element ){
						$( element ).click(); 
						fixImageBlocks();
					}
				);
			}
			
			if ( socialRocketAdmin.auto_fix_gutenberg ) {
				fixImageBlocks();
			}
			
		});
	})( jQuery );


	/**
	 * Register sidebar controls
	 */
	var new_client_ids = [];
	
	var register_block_inspector_controls_image_extras = wp.compose.createHigherOrderComponent( function( BlockEdit ) {
		
		return function( props ) {
			
			if ( props.name !== 'core/image' ) {
				return el( BlockEdit, props );
			}

			if ( typeof props.attributes.id === 'undefined' ) {
				
				if ( new_client_ids.indexOf( props.clientId ) === -1 ) {
					new_client_ids.push( props.clientId );
				}
				
				return el( BlockEdit, props );
				
			}
			
			var extraImageControls = getExtraImageControls();
			
			// get attributes from attachment (only called when inserting new image block)
			if ( new_client_ids.indexOf( props.clientId ) !== -1 ) {
				
				var attachment = wp.media.attachment( props.attributes.id );
				
				var attributesToSet = {};
				jQuery.each( extraImageControls, function( index, extraImageControl ) {
					attributesToSet[ extraImageControl.propKey ] = attachment.attributes[ extraImageControl.metaKey ];
				});
				
				props.setAttributes( attributesToSet );
				
				new_client_ids.splice( new_client_ids.indexOf( props.clientId ), 1 );
				
			}
			
			// get attributes from props (changes dynamically)
			var propertyValues = {};
			jQuery.each( extraImageControls, function( index, extraImageControl ) {
				propertyValues[ extraImageControl.propKey ] = props.attributes[ extraImageControl.propKey ];
			});
			
			// generate the elements for the inspector panel
			var panelElements = [];
			jQuery.each( extraImageControls, function( index, panelControl ) {
				if ( panelControl.type === 'CheckboxControl' ) {
					var initialChecked = ( typeof panelControl.checkedCb === "function" ?
							                panelControl.checkedCb( propertyValues[ panelControl.propKey ] )
										    : propertyValues[ panelControl.propKey ] );
					panelElements.push(
						el( wp.components.CheckboxControl, {
							checked:  initialChecked,
							label:    panelControl.label,
							onChange: function ( new_value ) {
								var attributeToSet = {};
								attributeToSet[ panelControl.propKey ] = ( typeof panelControl.valueCb === "function" ? panelControl.valueCb( new_value ) : new_value );
								props.setAttributes( attributeToSet );
							},
						})
					);
				} else {
					panelElements.push(
						el( wp.components[ panelControl.type ], {
							value:    propertyValues[ panelControl.propKey ],
							label:    panelControl.label,
							onChange: function ( new_value ) {
								var attributeToSet = {};
								attributeToSet[ panelControl.propKey ] = new_value;
								props.setAttributes( attributeToSet );
							},
						})
					);
				}
			});
			
			return el( wp.element.Fragment, {},
				el( wp.editor.InspectorControls, {},
					el( wp.components.PanelBody, { title: 'Social Rocket' }, panelElements )
				),
				el( BlockEdit, props )
			);
			
		}
		
	});
	
	wp.hooks.addFilter( 'editor.BlockEdit', 'social-rocket/image', register_block_inspector_controls_image_extras );


	/**
	 * Save block attributes
	 */
	function save_block_attributes_image_extras( element, blockType, attributes ) {
		
		if ( blockType.name !== 'core/image' ) {
			return element;
		}
		
		var extraImageControls = getExtraImageControls();
		
		var attr_data = [];
		jQuery.each( extraImageControls, function( index, extraImageControl ) {
			if ( typeof attributes[ extraImageControl.propKey ] !== "undefined" ) {
				value = attributes[ extraImageControl.propKey ];
				if ( ! extraImageControl.ignoreEmpty || ! lodash.isEmpty( value ) ) {
					attr_data.push({
						attribute: extraImageControl.attribute,
						value:     value
					});
				}
			}
		});
		
		// Return if no data is set
		if ( lodash.isEmpty( attr_data ) ) {
			return element;
		}
		
		var element_string = wp.element.renderToString( element );
		
		for( index in attr_data ) {
		
			var attribute = attr_data[index]['attribute'];
			var value     = attr_data[index]['value'].replace( /\"/g, '' ).replace( /</g, '' ).replace( />/g, '' ).replace( /&/g, '&amp;' );
			
			if( element_string.indexOf( attribute ) !== -1 ) {
				continue;
			}
			
			element_string = element_string.replace( '<img ', '<img ' + attribute + '="' + value + '" ' );
			
		}
		
		return el( wp.element.RawHTML, {}, element_string );
		
	}
	
	wp.hooks.addFilter( 'blocks.getSaveElement', 'social-rocket/image', save_block_attributes_image_extras, 50 );
	
})();
