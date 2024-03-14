var el = wp.element.createElement;
const { registerBlockType } = wp.blocks;
const { Button, Dashicon, Tooltip, IconButton, Toolbar, Placeholder, ToggleControl, SelectControl, TextControl } = wp.components;
const { BlockIcon, InspectorControls } = wp.blockEditor;
const { withState } = wp.compose;

registerBlockType('wptab/wptab-tableau-public-viz-block', {
	title: 'Tableau Public Viz Block',
	icon: 'analytics',
	keywords: ['tableau'],
	description: 'Embed a Viz from Tableau Public',
	category: 'embed',
	attributes: {
		divId: {
		    type: 'string'
		},
	  	url: {
		    type: 'string'
		},
		hideTabs: {
			type: 'boolean',
			default: false,
		},
		hideToolbar: {
			type: 'boolean',
			default: false,
		},
		height: {
			type: 'string'
		},
		width: {
			type: 'string'
		},
		device: {
			type: 'string'
		}
	},
	supports: {
		align: true
	},

  	edit: function(props) {
  		
	  	var url = props.attributes.url,
	  		hideTabs_ = props.attributes.hideTabs,
	  		hideToolbar_ = props.attributes.hideToolbar,
	  		device_ = props.attributes.device,
	  		width = props.attributes.width,
	  		height = props.attributes.height,
	  		icon = props.icon,
	  		label = props.title;
	  		patterns = /^https?:\/\/(public\.)?tableau\.com\/.+/i;


		var hideTabsToggle = (0,withState)({
		  	hideTabs: hideTabs_
		})(function (_ref) {
		  	var hideTabs = _ref.hideTabs,
		        setState = _ref.setState;
			return el(ToggleControl, {
				label: "Hide Tabs",
				help: hideTabs ? 'Tabs are hidden' : 'Tabs are displayed.',
				checked: hideTabs,
				onChange: function onChange() {
				  	return setState(function (state) {
				  		props.setAttributes({hideTabs: !state.hideTabs});
					    return {
					      	hideTabs: !state.hideTabs
					    };
				  	});
				}
			});
		});


		var hideToolbarToggle = (1,withState)({
			hideToolbar: hideToolbar_
		})(function (_ref1) {
		  	var hideToolbar = _ref1.hideToolbar,
		      	setState = _ref1.setState;
		  	return el(ToggleControl, {
			    label: "Hide Toolbar",
			    help: hideToolbar ? 'Toolbar is hidden' : 'Toolbar is displayed.',
			    checked: hideToolbar,
			    onChange: function onChange() {
					return setState(function (state) {
						props.setAttributes({hideToolbar: !state.hideToolbar});
						return {
						  	hideToolbar: !state.hideToolbar
						};
					});
			    }
		  	});
		});

		var selectDevice = (3,withState)({
		  	device: device_
		})(function (_ref3) {
		  	var device = _ref3.device,
		        setState = _ref3.setState;
		  	return el(SelectControl, {
			    label: "Device",
			    value: device,
			    options: [
			    	{
				      	label: 'Default',
				      	value: ''
				    }, 
				    {
				      	label: 'Desktop',
				      	value: 'desktop'
				    }, 
				    {
				      	label: 'Tablet',
				      	value: 'tablet'
				    }, 
				    {
				      	label: 'Phone',
				      	value: 'phone'
				    }],
			    onChange: function onChange(device){props.setAttributes({device: device});}
			});
		});

	    function updateUrl(event) {
	    	props.setAttributes({url: event.target.value});
	    	divId_ = event.target.value.split('/').pop();
	    	props.setAttributes({divId: divId_});

	    }
 
 	    function updateWidth(event) {props.setAttributes({width: event})}

	    function updateHeight(event) {props.setAttributes({height: event})}

		return [
				el(
					Placeholder, {
			    		icon: el(
			    					BlockIcon, {
			      						icon: 'analytics',
			      						showColors: true
			    					}
			    				),
			    		label: label,
			    		className: "wp-block-embed"
			  		},
			  		el("form", 
				  		{
					        onSubmit: function onSubmit(event) {
					          	return event.preventDefault();
					        }
					    },
				 		el("input", {
					    	type: "url",
					    	value: url || '',
					    	className: "components-placeholder__input",
					    	"aria-label": label,
					    	placeholder: 'Enter the Tableau Public URL to embed here…',
					    	onChange: updateUrl
				  		}),
				  	// 	el(Button, {
						 //    isLarge: true,
						 //    type: "submit"
						 //  }, 'Embed'
						 // )
		  			),
	                el( InspectorControls, { key: "inspector" },
	                	el('h2', {}, 'Tableau Viz Settings'),
						el(hideTabsToggle),
						el(hideToolbarToggle),
						el(selectDevice),
						el('div', { className: "block-library-image__dimensions" }, 
					        el('p', { className: "block-library-image__dimensions__row" }, 'Viz Dimensions'), 
					        el('div', { className: "block-library-image__dimensions__row" }, 
								el(TextControl, {
							        type: "number",
							        className: "block-library-image__dimensions__width",
							        label: 'Width',
							        value: width,
							        min: 100,
							        onChange: updateWidth
							    }), 
								el(TextControl, {
							        type: "number",
							        className: "block-library-image__dimensions__height",
							        label: 'Height',
							        value: height,
							        min: 100,
							        onChange: updateHeight
							    })
							),
				        )
	                )
		  		)
		];

	},
  	save: function(props) {
	    return el("div", {
			id: "viz-container-" + props.attributes.divId,
			"data-url": props.attributes.url,
			"data-hide-tabs": props.attributes.hideTabs,
			"data-hide-toolbar": props.attributes.hideToolbar,
			"data-device": props.attributes.device,
			"data-height": props.attributes.height,
			"data-width": props.attributes.width
		});
 	}

})