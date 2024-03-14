var wp = window.wp
var el = wp.element.createElement
var $ = window.jQuery

wp.blocks.registerBlockType( 'fatcatapps/easy-pricing-tables', {

	title: 'Easy Pricing Table',

	icon: 'money-alt',

	category: 'common',
	
	description: '',

	attributes: fca_ept_main_attributes(),

	supports: { 
		align: ( fcaEptEditorData.theme_support.block_styles || fcaEptEditorData.theme_support.wide ),
		html: false,
		reusable: false,
		removable: false,
		customClassName: false,
	},

	edit: fca_ept_main_edit,

	save: (function(){ return null }) //SERVER SIDE RENDER HANDLES IT

})

function fca_ept_main_edit( props ){
	if ( fcaEptEditorData.debug ) {
		console.log( props )
	}
	
	//WAIT UNTIL RENDER??
	window.setTimeout (function(){
		fca_ept_handle_image_heights_toggle( props )
	}, 50 )
	
	fca_ept_reusable_block_init()
	if ( props.attributes.showLayoutPickerScreen === true ){
		return fca_ept_layout_picker_screen( props )	
	} else {
		return el( wp.element.Fragment, {},
			fca_ept_toolbar_controls( props ),			
			fca_ept_icon_dropdown( props ),	
			fca_ept_sidebar_settings( props ),		
			fca_ept_tooltip_modal( props ),
			fca_ept_button_modal( props ),
			fca_ept_woo_modal( props ),
			fca_ept_confirm_modal( props ),
			eval( 'fca_ept_' + props.attributes.selectedLayout + '_block_edit' )( props )
		)
	}
}



/********************/
/* Shared functions */
/********************/

function fca_ept_layout_picker_screen( props ) {
	
	var learnMoreButton = el( wp.components.Button, {
		variant: 'primary',
		onClick: function(){
			window.open( 'https://fatcatapps.com/easypricingtables/', '_blank' )
		}
	},'Learn more' )
		
	return el( wp.element.Fragment, { },
		el( wp.components.Modal, {
			title: 'Choose a template',
			isDismissible: false,
			shouldCloseOnClickOutside: ( props.attributes.selectedLayout !== '' ),
			className: 'fca-ept-modal',
			onRequestClose: function(){				 
				props.setAttributes({ showLayoutPickerScreen: false })
			}
		},
		el( 'div', { 
				className: 'fca-ept-layout-selection',
			},
			
			el( 'div', {
					className: 'layout-container' 
				},
				
				el( 'div', {
					className: 'layout',
					onClick: function(){ 
						fca_ept_maybe_set_defaults( props )
						fca_ept_set_layout1_attributes( props )
					}
				},					
					el( 'img', {
						width: 600,
						height: 401,
						src: fcaEptEditorData.directory + '/assets/blocks/screenshots/layout1.png',
					}),
					learnMoreButton
				),

				el( 'div', {
					className: 'layout',
					onClick: function(){ 
						fca_ept_maybe_set_defaults( props )
						fca_ept_set_layout2_attributes( props )
					}
				},					
					el( 'img', {
						
						width: 600,
						height: 410,
						src: fcaEptEditorData.directory + '/assets/blocks/screenshots/layout2.png',
					}),
					learnMoreButton
				),

				el( 'div', {
					className: fcaEptEditorData.edition === 'Free' ? 'layout pro-only' : 'layout',
					onClick: function(){
						if ( fcaEptEditorData.edition === 'Free' ) {
							window.open( 'https://fatcatapps.com/easypricingtables/', '_blank' )
						} else {
							fca_ept_maybe_set_defaults( props )						
							fca_ept_set_layout3_attributes( props )
						}
					}
				},					
					el( 'img', {
						
						width: 600,
						height: 357,
						src: fcaEptEditorData.directory + '/assets/blocks/screenshots/layout3.png',
					}),
					learnMoreButton
				),

				el( 'div', {
					className: fcaEptEditorData.edition === 'Free' ? 'layout pro-only' : 'layout',
					onClick: function(){
						if ( fcaEptEditorData.edition === 'Free' ) {
							window.open( 'https://fatcatapps.com/easypricingtables/', '_blank' )
						} else {
							fca_ept_maybe_set_defaults( props )						
							fca_ept_set_layout4_attributes( props )
						}
					}
				},					
					el( 'img', {
						width: 600,
						height: 257,
						src: fcaEptEditorData.directory + '/assets/blocks/screenshots/layout4.png',
					}),
					learnMoreButton
				),

				el( 'div', {
					className: fcaEptEditorData.edition === 'Free' ? 'layout pro-only' : 'layout',
					onClick: function(){
						if ( fcaEptEditorData.edition === 'Free' ) {
							window.open( 'https://fatcatapps.com/easypricingtables/', '_blank' )
						} else {
							fca_ept_maybe_set_defaults( props )						
							fca_ept_set_layout5_attributes( props )
						}
					}
				},					
					el( 'img', {
						
						width: 600,
						height: 252,
						src: fcaEptEditorData.directory + '/assets/blocks/screenshots/layout5.png',
					}),
					learnMoreButton
				),

				el( 'div', {
					className: fcaEptEditorData.edition === 'Free' ? 'layout pro-only' : 'layout',
					onClick: function(){
						if ( fcaEptEditorData.edition === 'Free' ) {
							window.open( 'https://fatcatapps.com/easypricingtables/', '_blank' )
						} else {
							fca_ept_maybe_set_defaults( props )						
							fca_ept_set_layout6_attributes( props )
						}
					}
				},					
					el( 'img', {
						
						width: 600,
						height: 311,
						src: fcaEptEditorData.directory + '/assets/blocks/screenshots/layout6.png',
					}),
					learnMoreButton
				),
				el( 'div', {
					className: fcaEptEditorData.edition === 'Free' ? 'layout pro-only' : 'layout',
					onClick: function(){
						if ( fcaEptEditorData.edition === 'Free' ) {
							window.open( 'https://fatcatapps.com/easypricingtables/', '_blank' )
						} else {
							fca_ept_maybe_set_defaults( props )						
							fca_ept_set_layout7_attributes( props )
						}
					}
				},					
					el( 'img', {
						
						width: 600,
						height: 350,
						src: fcaEptEditorData.directory + '/assets/blocks/screenshots/layout7.png',
					}),
					learnMoreButton
				),
				el( 'div', {
					className: fcaEptEditorData.edition === 'Free' ? 'layout pro-only' : 'layout',
					onClick: function(){
						if ( fcaEptEditorData.edition === 'Free' ) {
							window.open( 'https://fatcatapps.com/easypricingtables/', '_blank' )
						} else {
							fca_ept_maybe_set_defaults( props )						
							fca_ept_set_layout8_attributes( props )
						}
					} 
				},					
					el( 'img', {
						
						width: 600,
						height: 304,
						src: fcaEptEditorData.directory + '/assets/blocks/screenshots/layout8.png',
					}),
					learnMoreButton
				),
				el( 'div', {
					className: fcaEptEditorData.edition === 'Free' ? 'layout pro-only' : 'layout',
					onClick: function(){
						if ( fcaEptEditorData.edition === 'Free' ) {
							window.open( 'https://fatcatapps.com/easypricingtables/', '_blank' )
						} else {
							fca_ept_maybe_set_defaults( props )						
							fca_ept_set_layout9_attributes( props )
						}
					} 
				},					
					el( 'img', {
						
						width: 600,
						height: 253,
						src: fcaEptEditorData.directory + '/assets/blocks/screenshots/layout9.png',
					}),
					learnMoreButton
				)
				
				)//END LAYOUT CONTAINER
			) // end div
		) //END MODAL
	) // end fragment
}

function fca_ept_main_attributes() {
	return {
		// EDITOR DATA
		align: { type: 'string' },
		selectedLayout: { type: 'string', default: '' },
		selectedCol: { type: 'int', default: 0 }, 
		selectedSection: { type: 'string', default: 'features' },
		tableID: { type: 'string', default: '' },
		customCSS: { type: 'string', default: '' },
		
		columnSettings: { type: 'string', default: '' },
		comparisonText: { type: 'string', default: '<li>Comparison 1</li><li>Comparison 2</li><li>Comparison 3</li><li>Comparison 4</li>' },
		
		selectedRange: { type: 'string', default: '' },
		showLayoutPickerScreen: { type: 'boolean', default: true },
		showIconDropdown: { type: 'boolean', default: false },
		showWooModal: { type: 'boolean', default: false },
		showURLModal: { type: 'boolean', default: false },
		showTooltipModal: { type: 'boolean', default: false },
			tooltipModalText: { type: 'string', default: '' },
		
		// EXTRA SETTINGS 		
		showImagesToggle: { type: 'boolean', default: false },	
		matchHeightsToggle: { type: 'boolean', default: true },		
		showPlanSubtextToggle: { type: 'boolean' },
		showPriceSubtextToggle: { type: 'boolean' },
		showButtonsToggle: { type: 'boolean' },
		urlTargetToggle: { type: 'boolean', default: false },
		
		togglePeriodToggle: { type: 'boolean', default: false }, 
			togglePeriod: { type: 'boolean', default: false },
			togglePeriodText1: { type: 'string', default: 'Monthly' },
			togglePeriodText2: { type: 'string', default: 'Yearly' },
		
		// COLORS
		layoutBGColor: { type: 'string', default: '#f2f2f2' },
		
		layoutBGTint1: { type: 'string', default: 'rgb(245, 245, 245)' },
		layoutBGTint2: { type: 'string', default: '#eeeeee' },
		layoutBGTint3: { type: 'string', default: '#dddddd' },
		layoutBGTint4: { type: 'string', default: '#7f8c8d' },
		
		layoutFontColor: { type: 'string', default: '#333333' },
		layoutFontColor1: { type: 'string', default: '#dddddd' },
		popularBorderColor: { type: 'string', default: 'rgb(220, 175, 15)' },
		planSvgColor: { type: 'string', default: 'rgba(15, 97, 216, 0.3)' },
		priceSubtextColor: { type: 'string', default: '#0c1f28' },
		buttonColor: { type: 'string', default: '#6236ff' },
		buttonFontColor: { type: 'string', default: '#ffffff' },
		buttonBorderColor: { type: 'string', default: 'rgb(0,103,103)' },
		buttonBorderColorPop: { type: 'string', default: 'rgb(200,104,12)' },
		accentColor: { type: 'string', default: '#6236ff' },
		
		// FONT SETTINGS
		fontFamily: { type: 'string', default: 'sans-serif' },
		popularFontSize: { type: 'string', default: '75%' }, 
		planFontSize: { type: 'string', default: '300%' }, 
		planSubtextFontSize: { type: 'string', default: '100%' }, 
		priceFontSize: { type: 'string', default: '400%' }, 
		pricePeriodFontSize: { type: 'string', default: '100%' }, 
		priceBillingFontSize: { type: 'string', default: '81.25%' }, 
		featuresFontSize: { type: 'string', default: '125%' }, 
		buttonFontSize: { type: 'string', default: '150%' }, 
		toggleFontSize: { type: 'string', default: '112.5%' }, 
	}
}

function fca_ept_default_columnSettings() {
	return [
		{
			columnPopular: false,
			popularText: 'Most popular',
			planText1: 'Starter',
			planText2: 'Starter',
			planSvg: '&#xf155;',
			planImage: '',
			planSubText: 'For getting started',
			priceText1: '$29',
			priceText2: '$290',
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
			useCustomWooTitle2: false,
		},
		{
			columnPopular: true,
			popularText: 'Most popular',
			planText1: 'Pro',
			planText2: 'Pro',
			planSvg: '&#xf09d;',
			planImage: '',
			planSubText: 'Best for most users',
			priceText1: '$39',
			priceText2: '$390',
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
			useCustomWooTitle2: false,
		},
		{
			columnPopular: false,
			popularText: 'Most popular',
			planText1: 'Elite',
			planText2: 'Elite',
			planSvg: '&#xf219;',
			planImage: '',
			planSubText: 'For enterprises',
			priceText1: '$49',
			priceText2: '$490',
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
			useCustomWooTitle2: false,
		}
	]
}
