( function( blocks, components, i18n, element ) {
    var el = wp.element.createElement,
    registerBlockType = wp.blocks.registerBlockType,
    InspectorControls = wp.editor.InspectorControls,
    blockStyle = { backgroundColor: '#fff', color: '#000' };
    var TextControl = wp.components.TextControl;
    var SelectControl = wp.components.SelectControl;
    var ToggleControl = wp.components.ToggleControl;


registerBlockType( 'gs-wps/wpsshortcodeblock', {
    title: 'GS WooCommerce Products Slider',
 
    icon: 'slides',
 
    category: 'layout',
 
    attributes: {
        
        themes: {
            type: 'select',
            default: 'gs-effect-1'
        },
        numb: {
            type: 'text',
            default: 10
        },
        orders: {
            type: 'select',
            default: 'DESC'
        },
        columns: {
            type: 'select',
            default: '4'
        },
        product_cat: {
            type: 'text',
        },
        dotnav:{
            type: 'checkbox',
            
        },
        // owlnav:{
        //     type: 'checkbox',
        //     default: 1
        // },

        autoplay:{
            type: 'checkbox',
        },

    
    },
 
    edit: function( props ) {
        var focus = props.focus;
        var numb = props.attributes.numb;
        //var owlnav = props.attributes.owlnav;
        var autoplay = props.attributes.autoplay;
        var dotnav = props.attributes.dotnav;
        var product_cat = props.attributes.product_cat;
        var themes = props.attributes.themes;
        var orders = props.attributes.orders;
        var columns = props.attributes.columns;
     

        blockStyle['width'] = '100%';
       
        function onChangetms( newThemes ) {
            props.setAttributes( { themes: newThemes } );
        }
        function onChangeOrders( newOrders ) {
            props.setAttributes( { orders: newOrders } );
        }
        function onChangeColumns( newColumns ) {
            props.setAttributes( { columns: newColumns } );
        }

        
    
        return [ 
            el( 'p', { style: blockStyle }, 'GS WooCommerce Products Slider Block' ),
             
            el( InspectorControls, { key: 'inspector' }, // Display the block options in the inspector panel.
            el( components.PanelBody, {
                title: i18n.__( ' Products Slider Shortcode Attributes ' ),
                className: 'block-logo-attribute',
                initialOpen: true,
            },
            el(
                SelectControl,
                {
                    label: i18n.__( 'Theme ' ),
                    value: themes,
                    onChange: onChangetms,
                    options: [
                      { value: 'gs-effect-1', label: i18n.__( 'Effect (Lite 1)' ) },
                      { value: 'gs-effect-2', label: i18n.__( 'Effect (Lite 2)' ) },
                      { value: 'gs-effect-3', label: i18n.__( 'Effect (Lite 3)' ) },
                      { value: 'gs-effect-4', label: i18n.__( 'Effect (Lite 4)' ) },
                      { value: 'gs-effect-5', label: i18n.__( 'Effect (Lite 5)' ) },
                      
                    ],
                }
            ),

            el( TextControl, {
            type: 'number',
            label: i18n.__( 'Total Products' ),
            value: numb,
            onChange: function( newNumb) {
                props.setAttributes( { numb: newNumb } );
            },
            } ),

            el(
                SelectControl,
                {
                    label: i18n.__( 'Columns ' ),
                    value: columns,
                    onChange: onChangeColumns,
                    options: [
                      { value: '1', label: i18n.__( 'Single Column' ) },
                      { value: '2', label: i18n.__( '2 Columns' ) },
                      { value: '3', label: i18n.__( '3 Columns' ) },
                      { value: '4', label: i18n.__( '4 Columns' ) },
                      { value: '5', label: i18n.__( '5 Columns' ) },
                      
                    ],
                }
            ),

            el(
                SelectControl,
                {
                    label: i18n.__( 'Select Order ' ),
                    value: orders,
                    onChange: onChangeOrders,
                    options: [
                      { value: 'DESC', label: i18n.__( 'DESC' ) },
                      { value: 'ASC', label: i18n.__( 'ASC' ) },
                      
                    ],
                }
            ),
            el( TextControl, {
                type: 'text',
                label: i18n.__( 'Portfolio category' ),
                value: product_cat,
                onChange: function( newProduct_cat ) {
                    props.setAttributes( { product_cat:newProduct_cat } );
                },
            } ),

            el( 'p', { }, 'Ex: singles, albums ' ),

            // el( ToggleControl, {
            //     label: i18n.__( 'Navigation' ),
            //     checked: owlnav,
            //     onChange: function( newowlNav ) {
            //         props.setAttributes( { owlnav:newowlNav } );
            //     },
            // } ),

            el( ToggleControl, {
                label: i18n.__( 'Dots navigation' ),
                checked: dotnav,
                onChange: function( newDotnav ) {
                    props.setAttributes( { dotnav:newDotnav } );
                },
            } ),

            el( ToggleControl, {
                label: i18n.__( 'Autoplay' ),
                checked: autoplay,
                onChange: function( newAutoplay ) {
                    props.setAttributes( { autoplay:newAutoplay } );
                },
            } ),
        ),
    ),

                
        ];
    },
 
    save: function( props ) {
        return null;
    },
} );

} )(
    window.wp.blocks,
    window.wp.components,
    window.wp.i18n,
    window.wp.element,
);