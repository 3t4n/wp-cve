const { __ } = wp.i18n; // Import __() from wp.i18n
const { Component } = wp.element;

var el = wp.element.createElement,
    registerBlockType = wp.blocks.registerBlockType,
    TextControl = wp.components.TextControl,
    SelectControl = wp.components.SelectControl,
    ColorPalette = wp.components.ColorPalette,
    NumberControl = wp.components.__experimentalNumberControl,
    InspectorControls = wp.editor.InspectorControls,
    blockStyle = { fontFamily:'Roboto', backgroundColor: '#900', color: '#fff', padding: '20px' };

const iconEl = el('svg', { width: 20, height: 20 },
  el('path', { d: "M16.1,16.6h-2.5c-1,0-1.9-0.6-2.4-1.5L11,14.5c-0.2-0.4-0.5-0.6-0.9-0.6c-0.4,0-0.8,0.2-0.9,0.6l-0.3,0.6 c-0.4,0.9-1.3,1.5-2.4,1.5H3.9c-2.2,0-3.9-1.8-3.9-3.9V7.3c0-2.2,1.8-3.9,3.9-3.9h12.2c2.2,0,3.9,1.8,3.9,3.9v1.5 c0,0.4-0.3,0.8-0.8,0.8c-0.4,0-0.8-0.3-0.8-0.8V7.3c0-1.3-1.1-2.3-2.3-2.3H3.9C2.6,4.9,1.6,6,1.6,7.3v5.4c0,1.3,1.1,2.3,2.3,2.3 h2.6c0.4,0,0.8-0.2,0.9-0.6l0.3-0.6c0.4-0.9,1.3-1.5,2.4-1.5c1,0,1.9,0.6,2.4,1.5l0.3,0.6c0.2,0.4,0.5,0.6,0.9,0.6h2.5 c1.3,0,2.3-1.1,2.3-2.3c0-0.4,0.3-0.8,0.8-0.8c0.4,0,0.8,0.3,0.8,0.8C20,14.9,18.2,16.6,16.1,16.6L16.1,16.6z M16.7,9.4 c0-1.3-1.1-2.3-2.3-2.3C13,7.1,12,8.1,12,9.4s1.1,2.3,2.3,2.3C15.6,11.7,16.7,10.7,16.7,9.4L16.7,9.4z M15.1,9.4 c0,0.4-0.4,0.8-0.8,0.8c-0.4,0-0.8-0.4-0.8-0.8s0.4-0.8,0.8-0.8C14.8,8.6,15.1,9,15.1,9.4L15.1,9.4z M8,9.4C8,8.1,7,7.1,5.7,7.1 S3.3,8.1,3.3,9.4s1.1,2.3,2.3,2.3S8,10.7,8,9.4L8,9.4z M6.4,9.4c0,0.4-0.4,0.8-0.8,0.8c-0.4,0-0.8-0.4-0.8-0.8s0.4-0.8,0.8-0.8 C6.1,8.6,6.4,9,6.4,9.4L6.4,9.4z M6.4,9.4" } )
);
class wpvredit extends Component {

      constructor() {
        super( ...arguments );

        this.state = {
            fullwidth: '',
            data: [{value: "0", label: "None"}],
            border_style_option: [
                {value: "none", label: "none"},
                {value: "solid", label: "Solid"},
                {value: "dotted", label: "Dotted"},
                {value: "dashed", label: "Dashed"},
                {value: "double", label: "Double"},
            ],
            colors : [
                { name: 'red', color: '#f00' },
                { name: 'white', color: '#fff' },
                { name: 'blue', color: '#00f' },
            ],
            width_unit: [
                {value: "px", label: "px"},
                {value: "%", label: "%"},
                {value: "vw", label: "vw"},
                {value: "fullwidth", label: "Fullwidth"},
            ],
            height_unit_option: [
                {value: "px", label: "px"},
                {value: "vh", label: "vh"},
            ],
            radius_unit_option: [
                {value: "px", label: "px"},
            ],
            mobile_height_unit_option: [
                {value: "px", label: "px"},
            ],
          };
      }

    componentDidMount() {
		wp.apiFetch( { path : 'wpvr/v1/panodata' } ).then( data => {
            this.setState({data: data});
        } );
	}

    render() {

            return [

            el( InspectorControls, {},
                el( SelectControl, {
                    className : 'wpvr-base-control',
                    label: 'Id',
                    value: this.props.attributes.id,

                    onChange: ( value ) => {
                        this.props.setAttributes( { id: value } );
                    },
                    options: this.state.data,
                } )
            ),
            
            el( InspectorControls, {},
                el( TextControl, {
                    className : 'wpvr-base-control wpvr-width-base-control',
                    label: 'Width',
                    value: this.props.attributes.width,
                    onChange: ( value ) => { this.props.setAttributes( { width: value } ) },
                } )
            ),
            el( InspectorControls, {},
                el( SelectControl, {
                    className : 'wpvr-base-control wpvr-width-unit-control',
                    label: ' ',
                    value: this.props.attributes.width_unit,
                    onChange: ( value ) => {
                        this.props.setAttributes( { width_unit: value } )
                        if(value == 'fullwidth'){
                            this.props.setAttributes( { width: value } )
                            this.props.setAttributes( { width_unit: '' } )
                        }
                    },
                    options: this.state.width_unit,
                } )
            ),
            
            el( InspectorControls, {},
                el( NumberControl, {
                    className : 'wpvr-base-control wpvr-height-base-control',
                    label: 'Height',
                    value: this.props.attributes.height,
                    onChange: ( value ) => { this.props.setAttributes( { height: value } ); },
                } )
            ),
            el( InspectorControls, {},
                el( SelectControl, {
                    className : 'wpvr-base-control wpvr-height-unit-control',
                    label: ' ',
                    value: this.props.attributes.height_unit,

                    onChange: ( value ) => {
                        this.props.setAttributes( { height_unit: value } );
                    },
                    options: this.state.height_unit_option,
                } )
            ),
                el( InspectorControls, {},
                    el( NumberControl, {
                        className : 'wpvr-base-control wpvr-radius-base-control',
                        label: 'Radius',
                        value: this.props.attributes.radius,
                        onChange: ( value ) => { this.props.setAttributes( { radius: value } ); },
                    } )
                ),


                el( InspectorControls, {},
                    el( SelectControl, {
                        className : 'wpvr-base-control wpvr-radius-unit-control',
                        label: ' ',
                        value: this.props.attributes.radius_unit,

                        onChange: ( value ) => {
                            this.props.setAttributes( { radius_unit: value } );
                        },
                        options: this.state.radius_unit_option,
                    } )
                ),
            el( InspectorControls, {},
                el( NumberControl, {
                    className : 'wpvr-base-control wpvr-mobile-height-base-control',
                    label: 'Mobile Height',
                    value: this.props.attributes.mobile_height,
                    onChange: ( value ) => { this.props.setAttributes( { mobile_height: value } ); },
                } )
            ),
            el( InspectorControls, {},
                el( SelectControl, {
                    className : 'wpvr-base-control wpvr-mobile-height-unit-control',
                    label: ' ',
                    value: this.props.attributes.mobile_height_unit,

                    onChange: ( value ) => {
                        this.props.setAttributes( { mobile_height_unit: value } );
                    },
                    options: this.state.mobile_height_unit_option,
                } )
            ),
            el( InspectorControls, {},
                el( TextControl, {
                    className : 'wpvr-base-control wpvr-border-width-base-control',
                    label: 'Border Width',
                    value: this.props.attributes.border_width,
                    onChange: ( value ) => { this.props.setAttributes( { border_width: value } ); },
                } )
            ),

            el( InspectorControls, {},
                el( SelectControl, {
                    className : 'wpvr-base-control wpvr-border-style-base-control',
                    label: 'Border Style',
                    value: this.props.attributes.border_style,

                    onChange: ( value ) => {
                        this.props.setAttributes( { border_style: value } );
                    },
                    options: this.state.border_style_option,
                } )
            ),
            el( InspectorControls, {},
                el( ColorPalette, {
                    className : 'wpvr-base-control wpvr-border-color-base-control',
                    label: 'Border Color',
                    colors : this.state.colors,
                    value : this.props.attributes.border_color,
                    onChange: ( value ) => { this.props.setAttributes( { border_color: value } ); },
                } )
            ),



            <p className="wpvr-block-content">
                WPVR id={this.props.attributes.id}, Width={this.props.attributes.width}{this.props.attributes.width_unit}, Height={this.props.attributes.height}{this.props.attributes.height_unit}, Mobile Height={this.props.attributes.mobile_height}{this.props.attributes.mobile_height_unit}, Radius={this.props.attributes.radius}{this.props.attributes.radius_unit}
            </p>


        ];

      }
}
registerBlockType( 'wpvr/wpvr-block', {
    title: 'WPVR',
    icon: iconEl,
    category: 'common',


    edit: wpvredit,

    save: function(props) {
        return null;
    },
} );
