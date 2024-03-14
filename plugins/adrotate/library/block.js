/*--------------------------------------//
 WordPress Blocks
 Version: 1.0
 Original code: Arnan de Gans
 Copyright: See notice in adrotate.php
//--------------------------------------//
 Changelog:
//--------------------------------------//
 17 April 2022
 * Initial release
//--------------------------------------*/
var el = wp.element.createElement,
	__ = wp.i18n.__,
	registerBlockType = wp.blocks.registerBlockType,
	RichText = wp.blocks.RichText,
	BlockBoxStyle = { 'box-sizing': 'border-box', position: 'relative', padding: '1em', 'min-height': '20px', width: '100%', margin: '0', color: '#1e1e1e', 'border-radius': '2px', 'background-color': '#f7f7f7', 'box-shadow': 'inset 0 0 0 1px #1e1e1e', outline: '1px solid transparent', 'background-image': 'linear-gradient(to bottom right, #f7f7f7, #1fa4d1)' };
    
registerBlockType('adrotate/advert', {
	title: __('AdRotate Advert', 'adrotate'), 
	icon: 'editor-code',
	category: 'custom-adrotate',
	description: __('Show a single advert by entering an advert ID.', 'adrotate'),
	keywords: ['ad', 'advert', 'adrotate', 'banner', 'ads'],

	attributes: {
		advert_id: { 
			type: 'string', 
			selector: 'input',
		},
	},
 	supports: { 
	 	html: false,
	},
  
	edit: function( props ) {
	    function onChangeText( e ) {
			props.setAttributes( { advert_id: e.target.value } );
	    }

		if(isNaN(props.attributes.advert_id)) props.attributes.advert_id = 0;
		
		return el('div', { 
			className: props.className + 'components-placeholder widefat',
			style: BlockBoxStyle,	
		},
			el('div', {
				className: 'components-placeholder__label',
				style: { 'font-size': '18pt', 'font-weight': '400' },
			}, __('AdRotate Advert', 'adrotate')),
			el('label', {
				className: 'components-placeholder__instructions group-' + props.attributes.advert_id,
			}, __('Enter an Advert ID (numbers only):', 'adrotate')),
			el('input', {
                className: 'components-text-control__input group-' + props.attributes.advert_id,
                onChange: onChangeText,
                value: Number(props.attributes.advert_id),
                isSelected: props.isSelected,
                style: { 'background-color': '#fefefe' }
            }),
			el('div', {
				className: 'components-placeholder__instructions group-' + props.attributes.group_id,
				style: { 'font-size': '70%', 'font-style': 'italic' },
			}, __('You can find the advert ID in Manage Adverts. Any special markup, code or layout styles can be applied in the advert itself or by placing the advert in a group.', 'adrotate')),
		);
	},
	 
    save: function( props ) {
        return null;
    },
} );

registerBlockType('adrotate/group', {
    title: __('AdRotate Group', 'adrotate'), 
    icon: 'editor-code',
    category: 'custom-adrotate',
    description: __('Show a group of adverts by entering a group ID.', 'adrotate'),
    keywords: ['ad', 'advert', 'adrotate', 'banner', 'group'],

	attributes: {
		group_id: { 
			type: 'string', 
			selector: 'input',
		},
	},
	supports: { 
	 	html: false,
	},
  
    edit: function( props ) {
        function onChangeText( e ) {
            props.setAttributes( { group_id: e.target.value } );
        }
 
		if(isNaN(props.attributes.group_id)) props.attributes.group_id = 0;
		
		return el('div', { 
			className: props.className + 'components-placeholder widefat',
			style: BlockBoxStyle,	
		},
			el('div', {
				className: 'components-placeholder__label',
				style: { 'font-size': '18pt', 'font-weight': '400' },
			}, __('AdRotate Group', 'adrotate')),
			el('label', {
				className: 'components-placeholder__instructions group-' + props.attributes.group_id,
			}, __('Enter a group ID (numbers only):', 'adrotate')),
			el('input', {
                className: 'components-text-control__input group-' + props.attributes.group_id,
                onChange: onChangeText,
                value: Number(props.attributes.group_id),
                isSelected: props.isSelected,
                style: { 'background-color': '#fefefe' }
            }),
			el('div', {
				className: 'components-placeholder__instructions group-' + props.attributes.group_id,
				style: { 'font-size': '70%', 'font-style': 'italic' },
			}, __('You can find the group ID in Manage Groups. Any special markup, code or layout styles can be applied in the group wrapper when editing the group.', 'adrotate')),
		);
    },
 
    save: function( props ) {
        return null;
    },
} );