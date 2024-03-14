
( function( blocks, editor, element ) {

	var el  = element.createElement
	var tables = dh_ptp_gutenblock_script_data.tables

	blocks.registerBlockType( 'easy-pricing-tables/gutenblock', {
		title: 'Pricing Tables (Legacy)',
		icon: 'editor-table',
		category: 'widgets',
		keywords: ['pricing', 'table', 'tables' ],
		supports: {			
			customClassName: false,			
			html: false,
		},
		edit: function( props ) {
			return [
				el(
					wp.blockEditor.InspectorControls,
					{ 
						key: 'ept-controls'
					},	
					el( wp.components.PanelBody, { },					
						el( wp.components.SelectControl,
							{	
								
								label: 'Select a Table',
								value: props.attributes.post_id,
								options: tables,
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
									href: dh_ptp_gutenblock_script_data.editurl + '?post=' + props.attributes.post_id + '&action=edit',
									target: '_blank',
									variant: "secondary",
									style: {
										margin: '0 6px 0 0'
									}
								},
								'Edit Table'
							),
							el(
								wp.components.Button,
								{	
									href: dh_ptp_gutenblock_script_data.newurl,
									target: '_blank',
									variant: "primary"
									
								},
								'New Table'
							)
						)
					)
				),
				el( wp.serverSideRender, {
					key: 'ptp-ssr',
					block: 'easy-pricing-tables/gutenblock',
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