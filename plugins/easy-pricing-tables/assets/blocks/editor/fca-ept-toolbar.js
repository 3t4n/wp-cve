
function fca_ept_toolbar_controls( props ){

	var IncreaseFontsizeIcon = ( el( 'svg', {
			role: 'img',
			focusable: 'false',
			viewBox: '2 2 20 20',
			width: '20',
			height: '20'
			},  
			el( 'path', {
				d: 'M2 4V7H7V19H10V7H15V4H2Z',
				fill: '#111111'
			}),
			el( 'rect',{
				x: '13',
				y: '12',
				width: '8',
				height: '2',
				fill: '#000'
			}),
			el( 'rect',{
				x: '18',
				y: '9',
				width: '8',
				height: '2',
				transform: 'rotate(90 18 9)',
				fill: '#000'
			})
		)
	)

	var DecreaseFontsizeIcon = ( el( 'svg', {
			role: 'img',
			focusable: 'false',
			viewBox: '2 2 20 20',
			width: '20',
			height: '20'
			}, 
			el( 'path', {
				d: 'M4 4V7H9V19H12V7H17V4H4Z',
				fill: '#111111'
			}),
			el( 'rect' ,{
				x: '15',
				y: '12',
				width: '6',
				height: '2',
				fill: '#000'
			})
		) 
	)
	var wooIsPressed = props.attributes.showWooModal
	var columnSettings = JSON.parse( props.attributes.columnSettings ) 
	if( columnSettings ) {
		var selectedCol = props.attributes.selectedCol
		var wooProductID = props.attributes.togglePeriod ? columnSettings[selectedCol].wooProductID2 : columnSettings[selectedCol].wooProductID1
		if( wooProductID ) {
			 wooIsPressed = true
		}
	}
	var featuredIsPressed = columnSettings[selectedCol].columnPopular
	var WooIcon = ( el( 'svg', {
			role: 'img',
			focusable: 'false',
			viewBox: '0 0 1024 1024',
			width: '20',
			height: '20'
			}, 
			el( 'path', {
				d: 'M612.192 426.336c0-6.896-3.136-51.6-28-51.6-37.36 0-46.704 72.256-46.704 82.624 0 3.408 3.152 58.496 28.032 58.496 34.192-.032 46.672-72.288 46.672-89.52zm202.192 0c0-6.896-3.152-51.6-28.032-51.6-37.28 0-46.608 72.256-46.608 82.624 0 3.408 3.072 58.496 27.952 58.496 34.192-.032 46.688-72.288 46.688-89.52zM141.296.768c-68.224 0-123.504 55.488-123.504 123.92v650.72c0 68.432 55.296 123.92 123.504 123.92h339.808l123.504 123.936V899.328h278.048c68.224 0 123.52-55.472 123.52-123.92v-650.72c0-68.432-55.296-123.92-123.52-123.92h-741.36zm526.864 422.16c0 55.088-31.088 154.88-102.64 154.88-6.208 0-18.496-3.616-25.424-6.016-32.512-11.168-50.192-49.696-52.352-66.256 0 0-3.072-17.792-3.072-40.752 0-22.992 3.072-45.328 3.072-45.328 15.552-75.728 43.552-106.736 96.448-106.736 59.072-.032 83.968 58.528 83.968 110.208zM486.496 302.4c0 3.392-43.552 141.168-43.552 213.424v75.712c-2.592 12.08-4.16 24.144-21.824 24.144-46.608 0-88.88-151.472-92.016-161.84-6.208 6.896-62.24 161.84-96.448 161.84-24.864 0-43.552-113.648-46.608-123.936C176.704 436.672 160 334.224 160 327.328c0-20.672 1.152-38.736 26.048-38.736 6.208 0 21.6 6.064 23.712 17.168 11.648 62.032 16.688 120.512 29.168 185.968 1.856 2.928 1.504 7.008 4.56 10.432 3.152-10.288 66.928-168.784 94.96-168.784 22.544 0 30.4 44.592 33.536 61.824 6.208 20.656 13.088 55.216 22.416 82.752 0-13.776 12.48-203.12 65.392-203.12 18.592.032 26.704 6.928 26.704 27.568zM870.32 422.928c0 55.088-31.088 154.88-102.64 154.88-6.192 0-18.448-3.616-25.424-6.016-32.432-11.168-50.176-49.696-52.288-66.256 0 0-3.888-17.92-3.888-40.896s3.888-45.184 3.888-45.184c15.552-75.728 43.488-106.736 96.384-106.736 59.104-.032 83.968 58.528 83.968 110.208z',
				fill: wooIsPressed ? '#ffffff' : '#111111'
			})
	))
	
	return( el( wp.blockEditor.BlockControls, { key: 'ept-toolbar-controls' },	

		el( wp.components.ToolbarGroup, {},
			
			el( wp.components.DropdownMenu, { 
				icon: 'insert',
				label: 'Insert',
				controls: [
					{
						title: 'New column before',
						icon: 'table-col-before',
						onClick: function(){ fca_ept_add_column( props, 'before' ) },
					},
					{
						title: 'New column after',
						icon: 'table-col-after',
						onClick: function(){ fca_ept_add_column( props, 'after' ) },
					},
					{
						title: 'New row before',
						icon: 'table-row-before',
						onClick: function(){ fca_ept_add_row( props, 'before' ) },
					},
					{
						title: 'New row after',
						icon: 'table-row-after',
						onClick: function(){ fca_ept_add_row( props, 'after' ) },
					}
				]
				
			}),			
			el( wp.components.ToolbarButton, {
				icon: 'trash', 
				label: 'Remove column',
				onClick: function(){ 
					props.setAttributes( { showConfirmModal: true } )
				}
			}),
			el( wp.components.ToolbarButton, { 
				icon: 'arrow-left-alt2', 
				label: 'Move column to the left', 
				onClick: function(){ fca_ept_move_column( props, 'left' ) }
			}),
			el( wp.components.ToolbarButton, {
				icon: 'arrow-right-alt2',
				label: 'Move column to the right',
				onClick: function(){ fca_ept_move_column( props, 'right' ) }
			}),
			el( wp.components.ToolbarButton, {
				isPressed: featuredIsPressed,
				icon: 'star-filled',
				label: 'Toggle "Featured Column" style',
				onClick: function(){ fca_ept_set_popular( props ) }
			}),
			fcaEptEditorData.woo_integration ? el( wp.components.ToolbarButton, {
				icon: WooIcon,
				isPressed: wooIsPressed,
				label: 'Get data from WooCommerce',
				className: 'fca-ept-woo-toolbar-button',
				onClick: function(){					
					props.setAttributes({ showWooModal: true })
				}
			}) : null			
		),
		
		( fcaEptEditorData.edition !== 'Free' && props.attributes.selectedRange !== '' ? el( wp.components.ToolbarGroup, {},
			el( wp.components.ToolbarButton, {
				className: 'fca-ept-icon-button',
				icon: 'heart',
				label: 'Add icon',
				isPressed: props.attributes.showIconDropdown, 
				onClick: function(){
					//RN NOTE FORCE OPEN...
					window.setTimeout( function(){
						var inputToFocus = document.querySelectorAll('.fca-ept-fa-icons label')
						if( inputToFocus.length ){ 
							inputToFocus[0].click()					
						}							
					}, 35)
					props.setAttributes({ showIconDropdown: true })
					
				}
			}), 
		
			el( wp.components.ToolbarButton, {
				className: 'fca-ept-tooltip-button',
				icon: 'editor-help',
				label: 'Add tooltip',
				isPressed: props.attributes.showTooltipModal,
				onClick: function(){
					props.setAttributes({ showIconDropdown: false }) 
					props.setAttributes({ tooltipText: '' })		
					props.setAttributes({ showTooltipModal: true })
				}
			})
		) : null ),
		el( wp.components.ToolbarGroup, {
				className: 'fca-ept-fontsize',
				style: {
					borderRight: 0,
					marginRight: '-8px'
				
				}
			},
			el( wp.components.ToolbarButton, {
				className: 'fca-ept-increase-fontsize',
				icon: IncreaseFontsizeIcon,
				label: 'Increase font size',
				onClick: function(){ fca_ept_increase_fontsize( props ) }
			}),
			el( wp.components.ToolbarButton, {
				icon: DecreaseFontsizeIcon,
				label: 'Decrease font size',
				onClick: function(){ fca_ept_decrease_fontsize( props ) } 
			})
		),

		el( wp.blockEditor.MediaUploadCheck, {},
			el( wp.blockEditor.MediaUpload, {
				allowedTypes: [ 'image' ],
				autoOpen: true,
				render: function( mediaOpen ){
					
					return el( wp.components.Button, {
						style: {
							display: 'none'
						},
						className: 'fca-ept-mediaOpen',
						variant: 'primary',
						onClick: mediaOpen.open,
						},
					'Media Library' )
				},
				onSelect: function( media ) {
					fca_ept_update_planimage( props, media.url )
				},
			})
		)		
	))
}

function fca_ept_increase_fontsize ( props ){
	var section = props.attributes.selectedSection
	var fontSizeStr = eval( 'props.attributes.' + section + 'FontSize' ).toString()
	var fontSizeAttr = section + 'FontSize' 
	var fontsize = ( Number( fontSizeStr.slice( 0,-1 ) ) ) + 15

	var fontSizeObj = JSON.parse( '{"' + fontSizeAttr + '": "' + fontsize + '%"' + '}' )

	props.setAttributes( fontSizeObj )

}

function fca_ept_decrease_fontsize ( props ){
	var section = props.attributes.selectedSection
	var fontSizeStr = eval( 'props.attributes.' + section + 'FontSize' ).toString()
	var fontSizeAttr = section + 'FontSize'
 	var fontsize = ( Number( fontSizeStr.slice( 0,-1 ) ) ) - 15

 	var fontSizeObj = JSON.parse( '{"' + fontSizeAttr + '": "' + fontsize + '%"' + '}' )

	props.setAttributes( fontSizeObj )

}

function fca_ept_set_popular( props ){

	var columnSettings = JSON.parse( props.attributes.columnSettings ) 
	var selectedCol = parseInt( props.attributes.selectedCol )
	var tableID = props.attributes.tableID 
	
	columnSettings[selectedCol].columnPopular = !columnSettings[selectedCol].columnPopular
	
	props.setAttributes( { columnSettings: JSON.stringify( columnSettings ) } )

}

function fca_ept_move_column( props, direction ){

	var columnSettings = JSON.parse( props.attributes.columnSettings ) 
	var selectedCol = parseInt( props.attributes.selectedCol )
	var fromPosition = selectedCol
	var columnData = columnSettings.splice( fromPosition, 1 )[0]
	var toPosition = selectedCol + 1

	if ( direction === 'left' ){
		toPosition = selectedCol -1 < 0 ? columnSettings.length : selectedCol -1
	}
	if ( direction === 'right' ){
		toPosition = selectedCol +1 > columnSettings.length ? 0 : selectedCol +1
	}

	columnSettings.splice( toPosition, 0, columnData )
	
	props.setAttributes ( { selectedCol: toPosition } )
	props.setAttributes( { columnSettings: JSON.stringify( columnSettings ) } )

}

function fca_ept_add_column ( props, pos ){
	var columnSettings = JSON.parse( props.attributes.columnSettings ) 
	var newList = Array.from( columnSettings )
	 
	var plans = [ 
		'Starter',
		'Pro',
		'Elite',
		'Custom'
	]

	var planSubtexts = [
		'For getting started',
		'Best for most users',
		'For enterprises',
		'For custom requirements',
	]

	var newColumn = {
		columnPopular: false,
		popularText: 'Most popular',
		planText1: plans[columnSettings.length] ? plans[columnSettings.length] : plans[3],
		planText2: plans[columnSettings.length] ? plans[columnSettings.length] : plans[3],
		planSvg: '&#xf19c;',
		planImage: props.attributes.showImagesToggle ? fcaEptEditorData.directory + '/assets/images/placeholder-300.png' : '',
		planSubText: planSubtexts[columnSettings.length] ? planSubtexts[columnSettings.length] : planSubtexts[3],
		priceText1: '$' + ( ( columnSettings.length * 10 ) + 29 ),
		priceText2: '$' + ( ( columnSettings.length * 100 ) + 290 ),
		pricePeriod1: 'per month',
		pricePeriod2: 'per year',
		priceBilling1: 'billed monthly',
		priceBilling2: 'billed annually',
		featuresText: '<li>Feature 1</li><li>Feature 2</li><li>Feature 3</li><li>Feature 4</li>',
		buttonText: 'Add to Cart',
		buttonURL1: 'https://www.fatcatapps.com',
		buttonURL2: 'https://www.fatcatapps.com',
		wooProductID1: '',
		wooProductID2: '',
		useCustomWooTitle1: false,
		useCustomWooTitle2: false
	}
	
	if ( pos === 'after' ) {
		newList.push( newColumn )
	}
	if ( pos === 'before' ) {
		newList.unshift( newColumn )
	}
	
	props.setAttributes ( { selectedCol: ( newList.length - 1 ) } )
	props.setAttributes( { columnSettings: JSON.stringify( newList ) } )

}

function fca_ept_add_row ( props, pos ){
	var columnSettings = JSON.parse( props.attributes.columnSettings )
	var hasComparison = ( [ 'layout5', 'layout9' ].indexOf( props.attributes.selectedLayout ) !== -1 )
	var comparisonText = ''
	
	for( var i = 0; i < columnSettings.length; i++ ) {
		
		if ( pos === 'after' ) {
			columnSettings[i].featuresText = columnSettings[i].featuresText + "<li>Feature</li>"
			if( hasComparison ) {
				comparisonText = props.attributes.comparisonText + "<li>Comparison</li>"
			}
		}
		if ( pos === 'before' ) {
			columnSettings[i].featuresText = "<li>Feature</li>" + columnSettings[i].featuresText
			if( hasComparison ) {
				comparisonText =  "<li>Comparison</li>" + props.attributes.comparisonText
			}
		} 
		
		
	}
	props.setAttributes( { columnSettings: JSON.stringify( columnSettings ) } )
	if( hasComparison ) {
		props.setAttributes({ comparisonText: comparisonText })
	}

}

function fca_ept_del_column ( props ){

	var columnSettings = JSON.parse( props.attributes.columnSettings ) 
	var selectedCol = parseInt( props.attributes.selectedCol )

	if ( columnSettings.length > 1 ){

		columnSettings.splice( ( selectedCol ), 1 )
		props.setAttributes( { selectedCol: ( columnSettings.length - 1 ) } )
		props.setAttributes( { columnSettings: JSON.stringify( columnSettings ) } )
	}
} 

function fca_ept_icon_dropdown( props ){
	var isSelected = props.isSelected
	var showIconDropdown = props.attributes.showIconDropdown
	// icons
	
	return ( isSelected && showIconDropdown ) ? el( wp.components.Modal, { 
			isDismissible: false,
			shouldCloseOnClickOutside: true,
			title: "Add icon",
			className: 'fca-ept-fa-icons-modal',
			onRequestClose: function(){	
				props.setAttributes({ showIconDropdown: false })
			}
		},
		el( wp.components.ComboboxControl, {
			
			hideLabelFromVision: true,
			label: 'Choose an icon',
			className: 'fca-ept-fa-icons',
			value: '',
			allowReset: false,
			options: fcaEptEditorData.fa_classes,
			onChange: function( newValue ){ 				
				fca_ept_add_icon( props, newValue )			
			}
		})
		
	) : null 
}

function fca_ept_woo_modal( props ) {
	var isSelected = props.isSelected
	var showWooModal = props.attributes.showWooModal
	var columnSettings = JSON.parse( props.attributes.columnSettings ) 
	var selectedCol = props.attributes.selectedCol
	
	var selectedWooProduct = props.attributes.togglePeriod ? columnSettings[selectedCol].wooProductID2 : columnSettings[selectedCol].wooProductID1
	// icons
	return ( isSelected && showWooModal ) ? el( wp.components.Modal, { 
			isDismissible: false,
			shouldCloseOnClickOutside: true,
			title: "WooCommerce Product - Column " +  Number( props.attributes.selectedCol + 1 ),
			onRequestClose: function(){	
				props.setAttributes({ showWooModal: false })
			}
		},		
	
		el( wp.components.ComboboxControl, {
			
			label: 'Choose your product',
			help: "Select a WooCommerce product and we'll import the featured image and price.",
			className: 'fca-ept-woo-products',
			value: selectedWooProduct,
			allowReset: true,
			options: woo_products,
			onChange: function( newValue ){ 
				
				var columnSettings = JSON.parse( props.attributes.columnSettings ) 
				var selectedCol = props.attributes.selectedCol
				if ( props.attributes.togglePeriod ){
					columnSettings[selectedCol].useCustomWooTitle2 = false
					columnSettings[selectedCol].wooProductID2 = Number( newValue )
				} else {
					columnSettings[selectedCol].useCustomWooTitle1 = false
					columnSettings[selectedCol].wooProductID1 = Number( newValue )
				}
				props.setAttributes( { showWooModal: false } )
				props.setAttributes( { columnSettings: JSON.stringify( columnSettings ) } )
				
			}, 
			onFilterValueChange: function(){
				null
			}
		})
		
	) : null
}

function fca_ept_add_icon( props, newValue ){
	var selectedRange = props.attributes.selectedRange
	
	if( selectedRange && newValue ){
		
		var icon = document.createElement( 'span' )
		icon.innerHTML = newValue
		icon.style.fontFamily = 'FontAwesome'
		selectedRange.insertNode( icon )
		props.setAttributes({ showIconDropdown: false })
		fca_ept_update_section_nu( props )
		 
	}
}

function fca_ept_tooltip_modal( props ){
	
	var isSelected = props.isSelected
	var showTooltipModal = props.attributes.showTooltipModal
	var showTooltipEdit = fca_ept_is_tooltip_selected( props.attributes.selectedRange )
	
	if( isSelected == false || showTooltipModal == false ) {		
		return null
	} else if ( showTooltipEdit ) {
		return el( wp.components.Modal, { 
			isDismissible: false,
			shouldCloseOnClickOutside: true,
			title: "Edit tooltip",
			onRequestClose: function(){				
				props.setAttributes({ showTooltipModal: false })
			}
		},		
		el( wp.components.TextareaControl, { 
			value: props.attributes.tooltipText,
			label: "Tooltip Text",
			onChange: function( newVal ){				
				props.setAttributes( { tooltipText: newVal } )
			}
		}),
		el( wp.components.Button, {
			variant: 'primary',
			onClick: function(){
				var selectedRange = props.attributes.selectedRange
				selectedRange.startContainer.parentElement.dataset.tooltip = props.attributes.tooltipText
				
				fca_ept_update_section_nu( props )
				props.setAttributes({ showTooltipModal: false })
			}
			},
		'Save' )
		
		)
		
	} else {
		return el( wp.components.Modal, { 
			isDismissible: false,
			shouldCloseOnClickOutside: true,
			title: "Add a tooltip",
			onRequestClose: function(){				
				props.setAttributes({ showTooltipModal: false })	
			}
		},		
		el( wp.components.TextareaControl, { 
			value: props.attributes.tooltipText,
			label: "Tooltip Text",
			onChange: function( newVal ){
				props.setAttributes( { tooltipText: newVal } )
			}
		}),
		el( wp.components.Button, {
			variant: 'primary',
			disabled: props.attributes.tooltipText ? false : true,
			onClick: function(){
				fca_ept_add_tooltip( props )
			}
			},
		'Add' )
		)
	}
	
}

function fca_ept_add_tooltip( props ){

	var selectedRange = props.attributes.selectedRange
	var tooltipText = props.attributes.tooltipText

	if( selectedRange ){

		// add tooltip
		var span = document.createElement( 'span' )
		span.className = 'fca-ept-tooltip'
		span.innerHTML = "&#xf059;"
		span.style.fontFamily = 'FontAwesome'
		span.dataset.tooltip = tooltipText
		selectedRange.insertNode( span )
		fca_ept_update_section_nu( props )
		
		props.setAttributes({ showTooltipModal: false })


	} else {
		alert( 'Please click on where you would like the tooltip to be inserted in the table and then click \"Insert\"' )
	}

}

function fca_ept_update_section_nu( props ){

	var columnSettingsData = JSON.parse( props.attributes.columnSettings )
	var tableID = props.attributes.tableID
	var selectedCol = props.attributes.selectedCol
	var togglePeriodEnabled = props.attributes.togglePeriodToggle
	var togglePeriod = props.attributes.togglePeriod
	var tooltipMode = props.attributes.tooltipMode
	var section = props.attributes.selectedSection
	
	switch ( section ){

		case 'toggle':
			if( $( '#fca-ept-table-' + tableID + ' .fca-ept-toggle-period-container' ).is( ':visible' ) ){
				props.setAttributes({ togglePeriodText2: $( '.fca-ept-toggle-1' ).first().html() })
				props.setAttributes({ togglePeriodText2: $( '.fca-ept-toggle-2' ).first().html() })
			}
			break
		case 'popular':
			props.setAttributes({ popularText: $( '#fca-ept-table-' + tableID + ' .fca-ept-popular-text' ).eq( selectedCol ).html() })
			break
		case 'plan':
			if( togglePeriodEnabled && togglePeriod ){
				columnSettingsData[selectedCol].planText2 = $( '#fca-ept-table-' + tableID + ' .fca-ept-plan' ).eq( selectedCol ).html()
			} else {
				columnSettingsData[selectedCol].planText1 = $( '#fca-ept-table-' + tableID + ' .fca-ept-plan' ).eq( selectedCol ).html()
			}
			break
		case 'planSvg':
			columnSettingsData[selectedCol].planSvg = $( '#fca-ept-table-' + tableID + ' .fca-ept-plan-svg' ).eq( selectedCol ).html()
			break
		case 'planSubtext':
			columnSettingsData[selectedCol].planSubText = $( '#fca-ept-table-' + tableID + ' .fca-ept-plan-subtext' ).eq( selectedCol ).html()
			break
		case 'price':
			if( togglePeriodEnabled && togglePeriod ){
				columnSettingsData[selectedCol].priceText2 = $( '#fca-ept-table-' + tableID + ' .fca-ept-price' ).eq( selectedCol ).html()
			} else {
				columnSettingsData[selectedCol].priceText1 = $( '#fca-ept-table-' + tableID + ' .fca-ept-price' ).eq( selectedCol ).html()
			}
			break
		case 'pricePeriod':
			if( togglePeriodEnabled && togglePeriod ){
				columnSettingsData[selectedCol].pricePeriod2 = $( '#fca-ept-table-' + tableID + ' .fca-ept-price-period' ).eq( selectedCol ).html()
			} else {
				columnSettingsData[selectedCol].pricePeriod1 = $( '#fca-ept-table-' + tableID + ' .fca-ept-price-period' ).eq( selectedCol ).html()
			}
			break
		case 'priceBilling':
			if( togglePeriodEnabled && togglePeriod ){
				columnSettingsData[selectedCol].priceBilling2 = $( '#fca-ept-table-' + tableID + ' .fca-ept-price-billing' ).eq( selectedCol ).html()
			} else {
				columnSettingsData[selectedCol].priceBilling1 = $( '#fca-ept-table-' + tableID + ' .fca-ept-price-billing' ).eq( selectedCol ).html()
			}
			break
		case 'features':
			columnSettingsData[selectedCol].featuresText = $( '#fca-ept-table-' + tableID + ' .fca-ept-features' ).eq( selectedCol ).html()
			// comparison for layout 5
			if( $( '.fca-ept-comparison' ).is( ':visible' ) ){
				props.setAttributes({ comparisonText: $( '#fca-ept-table-' + tableID + ' .fca-ept-comparison' ).first().html() })
			}
			break
		case 'button':
			columnSettingsData[selectedCol].buttonText = $( '#fca-ept-table-' + tableID + ' .fca-ept-button' ).eq( selectedCol ).html()
			break
		default:
			return
	}

	props.setAttributes( { columnSettings: JSON.stringify( columnSettingsData ) } )
	
}
