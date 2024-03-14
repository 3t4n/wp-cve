
( function( blocks, editor, element ) {

	var el  = element.createElement
	
	var optins = fca_eoi_gutenblock_script_data.optins
	var twostep_optins = fca_eoi_gutenblock_script_data.twostep_optins

	blocks.registerBlockType( 'optin-cat/gutenblock', {
		title: 'Optin Cat Form',
		icon: 'email',
		category: 'widgets',
		keywords: ['email', 'optin', 'form' ],
		edit: function( props ) {
			return [
				el(
					wp.blockEditor.InspectorControls,
					{ 
						key: 'eoi-controls'
					},	
					el( wp.components.PanelBody, { },					
						el( wp.components.SelectControl,
							{	
								
								label: 'Select an Optin',
								value: props.attributes.post_id,
								options: optins,
								onChange: function( newValue ){ props.setAttributes({ post_id: newValue }) }
							}
						)
					),
					el( wp.components.PanelBody, { },
						el( wp.components.ButtonGroup, {},
							props.attributes.post_id == 0 ? '' : 
							el(
								wp.components.Button,
								{	
									href: fca_eoi_gutenblock_script_data.editurl + '?post=' + props.attributes.post_id + '&action=edit',
									target: '_blank',
									variant: "secondary",
									style: {
										margin: '0 6px 0 0'
									}
								},
								'Edit Optin'
							),
							el(
								wp.components.Button,
								{	
									href: fca_eoi_gutenblock_script_data.newurl,
									target: '_blank',
									variant: "primary"
									
								},
								'New Optin'
							)
						)
					)
				),
				el( wp.serverSideRender, {
					block: 'optin-cat/gutenblock',
					attributes:  props.attributes,
				})
			]
		},

		save: function( props ) {
			return null
		},
	} )

	

	blocks.registerBlockType( 'optin-cat/gutenblock-twostep', {
		title: 'Optin Cat Two-Step Optin',
		icon: 'email',
		category: 'widgets',
		keywords: ['email', 'optin', 'form' ],
		edit: function( props ) {
			return [
				el(
					wp.blockEditor.InspectorControls,
					{ 
						key: 'eoi-controls-2'
					},	
					el( wp.components.PanelBody, { },					
						el( wp.components.SelectControl,
							{	
								
								label: 'Select an Optin',
								value: props.attributes.post_id,
								options: twostep_optins,
								onChange: function( newValue ){ props.setAttributes({ post_id: newValue }) }
							}
						)
					),
					el( wp.components.PanelBody, { },
						el( wp.components.ButtonGroup, {},
							props.attributes.post_id == 0 ? '' : 
							el(
								wp.components.Button,
								{	
									href: fca_eoi_gutenblock_script_data.editurl + '?post=' + props.attributes.post_id + '&action=edit',
									target: '_blank',
									variant: "secondary",
									style: {
										margin: '0 6px 0 0'
									}
								},
								'Edit Optin'
							),
							el(
								wp.components.Button,
								{	
									href: fca_eoi_gutenblock_script_data.newurl,
									target: '_blank',
									variant: "primary"
									
								},
								'New Optin'
							)
						)
					)
				),
				el( wp.serverSideRender, {
					block: 'optin-cat/gutenblock-twostep',
					attributes:  props.attributes,
				})
			]
		},

		save: function( props ) {
			return null
		},
	} )
	
}(
	window.wp.blocks,
	window.wp.editor,
	window.wp.element
))