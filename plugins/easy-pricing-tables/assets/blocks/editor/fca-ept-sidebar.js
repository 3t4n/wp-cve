function fca_ept_sidebar_settings( props ){
	var wp = window.wp
	var el = wp.element.createElement
	var checkIcon = el( 'span', { className: 'dashicons dashicons-yes' } )
	var learnMoreButton = el( wp.components.Button, {
		variant: 'primary',
		onClick: function(){
			window.open( 'https://fatcatapps.com/easypricingtables/', '_blank' )
		}
	},'Upgrade Now' )	
	
	return el( wp.blockEditor.InspectorControls, { key: 'ept-inspector-controls' },
			el( 'div', { className: 'fca-ept-sidebar-items' },
			
			fca_ept_colorpanel_settings( props ),
			fca_ept_font_settings( props ),
			fca_ept_misc_settings( props ),
			fcaEptEditorData.edition === 'Free' ? null : fca_ept_css_settings( props ),			
			el( wp.components.PanelHeader, { },
				el( wp.components.Button, {
					variant: 'tertiary',
					onClick: ( function(){
						props.setAttributes( { showLayoutPickerScreen: true } ) 
					} ),
				},
					'Change template'
				)
			),
			
			fcaEptEditorData.edition === 'Free' ? el( wp.components.PanelBody, { 
					className: 'fca-ept-get-premium',
					title: '',
					initialOpen: true 
				},
					el( 'h2', {
						className: 'get-premium-features'
					}, "Upgrade to Premium and Build Better Pricing Tables. You'll Get:" ),
					el( 'p', {}, checkIcon, 'Nine Gorgeous Designs' ),
					el( 'p', {}, checkIcon, 'Fully Customizable (Colors, Fonts, CSS etc.)' ),
					el( 'p', {}, checkIcon, '700+ Icons to Add to Your Tables' ),
					el( 'p', {}, checkIcon, 'Priority Email Support' ),
					el( 'p', {}, checkIcon, 'Tooltips' ),
					el( 'p', {}, checkIcon, 'Font Picker with 12+ fonts' ),
					el( 'p', {}, checkIcon, 'Pricing Toggles - switch between currencies or monthly/yearly pricing' ),
					learnMoreButton
				
			) : null
		)
	)
}

function fca_ept_misc_settings( props ){
	var wp = window.wp
	var el = wp.element.createElement
	
	var selectedLayout = props.attributes.selectedLayout
	
	return el( wp.components.PanelBody, { 
		title: 'Options',
		className: 'fca-ept-misc-settings',
		initialOpen: false
		},
		
		( fcaEptEditorData.toggle_integration ? 
		
			el( wp.components.ToggleControl, { 
				label: 'Monthly / yearly toggle',
				checked: props.attributes.togglePeriodToggle,
				help: "Add a toggle to the top of your pricing table, giving users the option to switch between yearly / monthly pricing. " + (props.attributes.togglePeriodToggle ? '(Enabled)' :  '(Disabled)' ),
				onChange: function(){
					props.setAttributes( { togglePeriodToggle: !props.attributes.togglePeriodToggle } )
				}
			})	
			: null
		 ),		
			el( wp.components.ToggleControl, { 
				label: 'Show Images',
				checked: props.attributes.showImagesToggle,
				onChange: function( newVal ){
					var isEnabled = !props.attributes.showImagesToggle
					var columnSettings = JSON.parse( props.attributes.columnSettings )
					if( isEnabled ) {
						for ( var i = 0; i < columnSettings.length; i++ ) {	
													
							if( ( typeof( columnSettings[i].planImage ) == 'undefined' ) || columnSettings[i].planImage == '' ){
								columnSettings[i].planImage = fca_ept_get_planImage( props, i )
							}
						}
					} else { 
						
						for (var i = 0; i < columnSettings.length; i++ ) {
							columnSettings[i].planImage = ''
						}
					}
					props.setAttributes( { showImagesToggle: isEnabled } )
					props.setAttributes( { columnSettings: JSON.stringify( columnSettings ) } )
				}
			}),
			( props.attributes.showImagesToggle ?
				el( wp.components.ToggleControl, { 
					label: 'Match Image Heights',
					checked: props.attributes.matchHeightsToggle,
					onChange: function( newVal ){
						fca_ept_handle_image_heights_toggle( props, newVal )
					}
				}) : null
			),
		
		( [ 'layout2', 'layout7', 'layout8' ].indexOf( props.attributes.selectedLayout ) !== -1 ? 
			el( wp.components.ToggleControl, { 
				label: 'Show plan subtext',
				checked: props.attributes.showPlanSubtextToggle,
				onChange: function(){ 
					props.setAttributes( { showPlanSubtextToggle: !props.attributes.showPlanSubtextToggle } )
				}
			})
			: null
		 ),
		 
		( [ 'layout2', 'layout3', 'layout4', 'layout6', 'layout8', 'layout9' ].indexOf( props.attributes.selectedLayout ) !== -1 ? 
			el( wp.components.ToggleControl, { 
				label: 'Show price subtext',
				checked: props.attributes.showPriceSubtextToggle,
				onChange: function(){ 
					props.setAttributes( { showPriceSubtextToggle: !props.attributes.showPriceSubtextToggle } )
				}
			}) : null
		 ),
		el( wp.components.ToggleControl, { 
			label: 'Show buttons',
			checked: props.attributes.showButtonsToggle,
			onChange: function(){
				props.setAttributes( { showButtonsToggle: !props.attributes.showButtonsToggle } )
			}
		}),
		( props.attributes.showButtonsToggle ?
			el( wp.components.ToggleControl, { 
				label: 'Open links in new tab',
				checked: props.attributes.urlTargetToggle,
				className: 'fca-ept-open-new-tab-toggle', 
				onChange: function( val ){ 
					props.setAttributes( { urlTargetToggle: !props.attributes.urlTargetToggle } )
				}
			}) : null
		)	
	)
}

function fca_ept_colorpanel_settings( props ){
	var wp = window.wp
	var el = wp.element.createElement
	
	var selectedLayout = props.attributes.selectedLayout
	var presetColors = [
		{
			name: 'Black',
			slug: 'fca-black',
			color: '#333333'
		},
		{
			name: 'White',
			slug: 'fca-white',
			color: '#ffffff'
		},
		{
			name: 'Gray',
			slug: 'fca-grey',
			color: '#f2f2f2'
		},
		{
			name: 'Dark Gray',
			slug: 'fca-grey',
			color: '#4c4c4c'
		},			
		{
			name: 'Light Blue',
			slug: 'fca-lightblue',
			color: '#3498db'
		},

		{
			name: 'Blue',
			slug: 'fca-blue',
			color: '#0f61d8'
		},
		{
			name: 'Purple',
			slug: 'fca-purple',
			color: '#6236ff'
		},
		{
			name: 'Green',
			slug: 'fca-green',
			color: '#278806'
		},
		{
			name: 'Yellow',
			slug: 'fca-yellow',
			color: '#ffd232'
		},
		{
			name: 'Red',
			slug: 'fca-red',
			color: '#dd4632'
		},
		{
			name: 'Orange',
			slug: 'fca-orange',
			color: '#fa6400'
		},
		{
			name: 'Aqua',
			slug: 'fca-aqua',
			color: '#01a3a4'
		},

	]
	//var presetColors =  eval( 'fca_ept_' + selectedLayout + '_presetColors' )
	var colorSettingsArray = [
		{
			label: "Background",
			colors: presetColors,
			value: props.attributes.layoutBGColor,
			clearable: false,
			onChange: function( newValue ){ 
				props.setAttributes( { "layoutBGColor": newValue } )
				
				if ( props.attributes.selectedLayout === 'layout1' ) {
					props.setAttributes( { "layoutBGTint1": fca_ept_hexToRGB( newValue, 0, 10 ) } )
					props.setAttributes( { "layoutBGTint2": fca_ept_hexToRGB( newValue, 0, 15 ) } )
					props.setAttributes( { "layoutBGTint3": fca_ept_hexToRGB( newValue, 0, 30 ) } )
					props.setAttributes( { "layoutBGTint4": fca_ept_hexToRGB( newValue, 0, 107 ) } )
				}
			}
		},	
		{
			label: "Text",
			value: props.attributes.layoutFontColor,
			colors: presetColors,
			clearable: false,
			onChange: function( newValue ){ 
				props.setAttributes( { "layoutFontColor": newValue } )
				props.setAttributes( { "layoutFontColor1": fca_ept_hexToRGB( newValue, 0.4, 0 ) } )
			}
		},
		{
			label: selectedLayout === 'layout6' ? "Popular Text" : "Plan Text",
			value: props.attributes.layoutFontColor1,
			colors: presetColors,
			clearable: false,
			onChange: function( newValue ){ 
				props.setAttributes( { "layoutFontColor1": newValue } )
			}
		},
		{
			"label": "Button",
			"value": props.attributes.buttonColor,
			"colors": presetColors,
			"clearable": false,
			"onChange": function( newValue ){ 
				if( !newValue ){
					newValue = fca_ept_main_attributes.buttonColor.default
				}
				props.setAttributes( { "buttonColor": newValue } )
				props.setAttributes( { "popularBorderColor": fca_ept_hexToRGB( newValue, 0, 35 ) } )
				props.setAttributes( { "priceSubtextColor": fca_ept_hexToRGB( newValue, 0.4, 0 ) } )
				props.setAttributes( { "buttonBorderColor": fca_ept_hexToRGB( newValue, 0, 55 ) } )
			}
		},
		{
			"label": "Button Font",
			"value": props.attributes.buttonFontColor,
			"colors": presetColors,
			"clearable": false,
			"onChange": function( newValue ){ 
				props.setAttributes( { "buttonFontColor": newValue } )
			}
		},
		{
			"label": "Accent",
			"value": props.attributes.accentColor,
			"colors": presetColors,
			"clearable": false,
			"onChange": function( newValue ){ 
				props.setAttributes( { "accentColor": newValue } )
				props.setAttributes( { "planSvgColor": fca_ept_hexToRGB( newValue, 0.3, 0 ) } )
				props.setAttributes( { "buttonBorderColorPop": fca_ept_hexToRGB( newValue, 0, 55 ) } )
			}
		}
	]
	
	if ( selectedLayout != 'layout6' && selectedLayout != 'layout2' ) {
		delete colorSettingsArray[2]
	}


	return el( wp.components.PanelBody, {
			title: "Colors",
			initialOpen: false,
		},
		el( wp.blockEditor.PanelColorSettings, {
			colorSettings: colorSettingsArray,
			
			initialOpen: false
		})
	)
}

function fca_ept_font_settings( props ){
	var wp = window.wp
	var el = wp.element.createElement
	
	var fca_ept_fonts = [
		{
			label: 'Roboto',
			value: 'Roboto'
		},
		{
			label: 'Open Sans',
			value: 'Open Sans'
		},
		{
			label: 'Lato',
			value: 'Lato'
		},
		{
			label: 'Oswald',
			value: 'Oswald'
		},
		{
			label: 'Source Sans Pro',
			value: 'Source Sans Pro'
		},
		{
			label: 'Montserrat',
			value: 'Montserrat'
		},
		{
			label: 'Merriweather',
			value: 'Merriweather'
		},
		{
			label: 'Raleway',
			value: 'Raleway'
		},
		{
			label: 'PT Sans',
			value: 'PT Sans'
		},
		{
			label: 'Lora',
			value: 'Lora'
		},
		{
			label: 'Noto Sans',
			value: 'Noto Sans'
		},
		{
			label: 'Nunito Sans',
			value: 'Nunito Sans'
		},
		{
			label: 'Concert One',
			value: 'Concert One'
		},
		{
			label: 'Prompt',
			value: 'Prompt'
		},
		{
			label: 'Work Sans',
			value: 'Work Sans'
		},
		{
			label: 'Sans serif',
			value: 'sans-serif'
			
		}
	]
	
	return fcaEptEditorData.edition === 'Free' ? null : el( wp.components.PanelBody, { 
		title: 'Font',
			initialOpen: false 
		},

		el( wp.components.SelectControl, {
			label: 'Choose a font:',
			value: props.attributes.fontFamily,
			options: fca_ept_fonts,
			onChange: function( selected ){ 
				if ( selected ){
					props.setAttributes( { fontFamily: selected } )
				}
			}
		})
	)
}