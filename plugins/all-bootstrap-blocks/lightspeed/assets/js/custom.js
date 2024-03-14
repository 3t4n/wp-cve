import {
	registerBlockType
} from '@wordpress/blocks';
import classnames from 'classnames';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import * as editor from '@wordpress/block-editor';
import * as components from '@wordpress/components';
import * as areoi from '../../../blocks/_components/Core.js';
import { registerFormatType } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

export function register_custom_blocks()
{

	let icons = {
		'hero': <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><g><rect fill="none" height="24" width="24"/><path d="M19,3H5C3.89,3,3,3.9,3,5v14c0,1.1,0.89,2,2,2h14c1.1,0,2-0.9,2-2V5C21,3.9,20.11,3,19,3z M19,19H5V7h14V19z M12,10.5 c1.84,0,3.48,0.96,4.34,2.5c-0.86,1.54-2.5,2.5-4.34,2.5S8.52,14.54,7.66,13C8.52,11.46,10.16,10.5,12,10.5 M12,9 c-2.73,0-5.06,1.66-6,4c0.94,2.34,3.27,4,6,4s5.06-1.66,6-4C17.06,10.66,14.73,9,12,9L12,9z M12,14.5c-0.83,0-1.5-0.67-1.5-1.5 s0.67-1.5,1.5-1.5s1.5,0.67,1.5,1.5S12.83,14.5,12,14.5z"/></g></svg>,
		'call-to-action': <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><g><rect fill="none" height="24" width="24"/></g><path d="M18,11c0,0.67,0,1.33,0,2c1.2,0,2.76,0,4,0c0-0.67,0-1.33,0-2C20.76,11,19.2,11,18,11z"/><path d="M16,17.61c0.96,0.71,2.21,1.65,3.2,2.39c0.4-0.53,0.8-1.07,1.2-1.6c-0.99-0.74-2.24-1.68-3.2-2.4 C16.8,16.54,16.4,17.08,16,17.61z"/><path d="M20.4,5.6C20,5.07,19.6,4.53,19.2,4c-0.99,0.74-2.24,1.68-3.2,2.4c0.4,0.53,0.8,1.07,1.2,1.6 C18.16,7.28,19.41,6.35,20.4,5.6z"/><path d="M4,9c-1.1,0-2,0.9-2,2v2c0,1.1,0.9,2,2,2h1v4h2v-4h1l5,3V6L8,9H4z M9.03,10.71L11,9.53v4.94l-1.97-1.18L8.55,13H8H4v-2h4 h0.55L9.03,10.71z"/><path d="M15.5,12c0-1.33-0.58-2.53-1.5-3.35v6.69C14.92,14.53,15.5,13.33,15.5,12z"/></svg>,
		'contact': <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M3 13h8v2H3zm0 4h8v2H3zm0-8h8v2H3zm0-4h8v2H3zm16 2v10h-4V7h4m2-2h-8v14h8V5z"/></svg>,
		'content-with-items': <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><rect fill="none" height="24" width="24"/><path d="M3,5v14h18V5H3z M8.33,17H5V7h3.33V17z M13.67,17h-3.33v-4h3.33V17z M19,17h-3.33v-4H19V17z M19,11h-8.67V7H19V11z"/></svg>,
		'content-with-media': <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M21 4H3c-1.1 0-2 .9-2 2v13c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zM3 19V6h8v13H3zm18 0h-8V6h8v13zm-7-9.5h6V11h-6zm0 2.5h6v1.5h-6zm0 2.5h6V16h-6z"/></svg>,
		'footer': <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="edit-site-template-card__icon" aria-hidden="true" focusable="false"><path fill-rule="evenodd" d="M18 5.5h-8v8h8.5V6a.5.5 0 00-.5-.5zm-9.5 8h-3V6a.5.5 0 01.5-.5h2.5v8zM6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"></path></svg>,
		'header': <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="edit-site-template-card__icon" aria-hidden="true" focusable="false"><path d="M18.5 10.5H10v8h8a.5.5 0 00.5-.5v-7.5zm-10 0h-3V18a.5.5 0 00.5.5h2.5v-8zM6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"></path></svg>,
		'logos': <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><g><rect fill="none" height="24" width="24"/></g><g><path d="M19,3H5L2,9l10,12L22,9L19,3z M9.62,8l1.5-3h1.76l1.5,3H9.62z M11,10v6.68L5.44,10H11z M13,10h5.56L13,16.68V10z M19.26,8 h-2.65l-1.5-3h2.65L19.26,8z M6.24,5h2.65l-1.5,3H4.74L6.24,5z"/></g></svg>,
		'media': <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M20 4v12H8V4h12m0-2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 9.67l1.69 2.26 2.48-3.1L19 15H9zM2 6v14c0 1.1.9 2 2 2h14v-2H4V6H2z"/></svg>,
		'next-and-previous': <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><rect fill="none" height="24" width="24"/><path d="M8.5,8.62v6.76L5.12,12L8.5,8.62 M10,5l-7,7l7,7V5L10,5z M14,5v14l7-7L14,5z"/></svg>,
		'post-details': <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><g><rect fill="none" height="24" width="24"/></g><g><g><path d="M12,2C6.48,2,2,6.48,2,12s4.48,10,10,10s10-4.48,10-10S17.52,2,12,2z M7.35,18.5C8.66,17.56,10.26,17,12,17 s3.34,0.56,4.65,1.5C15.34,19.44,13.74,20,12,20S8.66,19.44,7.35,18.5z M18.14,17.12L18.14,17.12C16.45,15.8,14.32,15,12,15 s-4.45,0.8-6.14,2.12l0,0C4.7,15.73,4,13.95,4,12c0-4.42,3.58-8,8-8s8,3.58,8,8C20,13.95,19.3,15.73,18.14,17.12z"/><path d="M12,6c-1.93,0-3.5,1.57-3.5,3.5S10.07,13,12,13s3.5-1.57,3.5-3.5S13.93,6,12,6z M12,11c-0.83,0-1.5-0.67-1.5-1.5 S11.17,8,12,8s1.5,0.67,1.5,1.5S12.83,11,12,11z"/></g></g></svg>,
		'posts': <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><rect fill="none" height="24" width="24"/><path d="M3,5v14h18V5H3z M19,11h-3.33V7H19V11z M13.67,11h-3.33V7h3.33V11z M8.33,7v4H5V7H8.33z M5,17v-4h3.33v4H5z M10.33,17v-4 h3.33v4H10.33z M15.67,17v-4H19v4H15.67z"/></svg>,
		'search': <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
	};

	for ( const [template_key, template] of Object.entries( areoi_lightspeed_vars.blocks ) ) {
		
		let is_header = ['header', 'footer'].includes( template_key ) ? true : false;

		let link = template + '/block.json';

		fetch( link, { headers: { 'Content-Type': 'application/json; charset=utf-8' }}).then( res => res.json() ).then( meta => {
	        
	        const { name } = meta;

	        function custom_fields( meta, attributes, onChange )
	        {
	        	var output = [];
	        	
	        	for ( const [attribute_key, attribute] of Object.entries( meta.attributes ) ) {
	        		if ( attribute.lightspeed ) {

	        			var data = attribute.lightspeed;
	        			var new_output = '';
	        			
	        			switch( data.type ) {
	        				case 'image':
	        					new_output = areoi.MediaUpload( areoi, attributes, onChange, data.label, 'image', attribute_key )
	        				break;
	        				case 'video':
	        					new_output = areoi.MediaUpload( areoi, attributes, onChange, data.label, 'video', attribute_key )
	        				break;
							case 'toggle':
								new_output = <components.ToggleControl 
	                                label={ data.label }
	                                help={ data.help }
	                                checked={ attributes[attribute_key] }
	                                onChange={ ( value ) => onChange( attribute_key, value ) }
	                            />

							break;
							case 'textarea':
								new_output = <components.TextareaControl 
	                                label={ data.label }
	                                help={ data.help }
	                                value={ attributes[attribute_key] }
	                                onChange={ ( value ) => onChange( attribute_key, value ) }
	                            />
	                        break;
	                        case 'select':
								new_output = <components.SelectControl 
	                                label={ data.label }
	                                help={ data.help }
	                                value={ attributes[attribute_key] }
	                                options={ data.options }
	                                onChange={ ( value ) => onChange( attribute_key, value ) }
	                            />

							break;
							default:
								new_output = <components.TextControl 
	                                label={ data.label }
	                                help={ data.help }
	                                value={ attributes[attribute_key] }
	                                onChange={ ( value ) => onChange( attribute_key, value ) }
	                            />
						}

						output.push( new_output );
	        		}
	        	}

	        	return output;
	        }

	        let loaded = 1;
			let ordered = false;

	        let settings = {
	        	icon: icons[template_key] ? icons[template_key] : null,
	        	edit: ({
	        		attributes,
					setAttributes,
					context,
					clientId
	        	}) => {
			        const blockProps = editor.useBlockProps({
						className: attributes.hasOwnProperty( 'align' ) ? 'align' + attributes.align : '',
					});

					const props = {
						attributes: attributes,
						setAttributes: setAttributes
					}

					const { block_id } = attributes;
				    // if ( ( !block_id || ( block_id != clientId ) ) && loaded == 1 ) {
				    //     setAttributes( { block_id: clientId } );
				    //     loaded++;
				    // }
				    if ( !block_id ) {
			            setAttributes( { block_id: clientId } );
			            loaded++;
			        }

				    function get_block_order()
				    {
				    	var blocks = wp.data.select( 'core/block-editor' ).getBlockOrder();
				    	var order = null;
				    	
				    	blocks.forEach( ( item, index ) => {
				    		if ( clientId == item ) {
				    			order = index + 1;
				    		}
				    	});
				    	
				    	return order;
				    }

				    /*wp.data.subscribe( () => {    	
				    	var has_changed = wp.data.select( 'core/editor' ).hasChangedContent();
				    	if ( !ordered && has_changed && attributes.hasOwnProperty( 'block_order' ) ) {
					    	var order = get_block_order();
					    	if ( attributes.block_order != order && order ) {
					    		ordered = true;
						        setAttributes( { block_order: order } )
						        ordered = false;
						    }
					    }
				    });*/

				    var order = get_block_order();
					if ( attributes.block_order != order && order ) {
						ordered = true;
				        setAttributes( { block_order: order } )
				        ordered = false;
				    }

				    function onChange( key, value ) {
				        setAttributes( { [key]: value } );
				    }


			    	const PostTypesControl = areoi.compose.compose(
				        wp.data.withSelect( function( select, props ) {

				            return wp.data.select('core').getPostTypes({ 
				                per_page: -1
				            });

				        } ) )( function( post_types ) {
				            
				            var output = [{label: 'Loading...', value:''}];

				            if ( typeof post_types != 'undefined' ) {
				                
				                var output = [];

				                for (const [ key, post_type ] of Object.entries( post_types ) ) {
				                    if ( post_type.viewable && post_type.slug != 'attachment' ) {
				                        var new_output = { label: post_type.name, value: post_type.slug }
				                        output.push( new_output );
				                    }
				                }

				            }
				            
				            return (
				                <areoi.components.PanelRow>
				                    <areoi.components.SelectControl
				                        label="Post Type"
				                        labelPosition="top"
				                        help="Specify what posts you would like to display. Child {post type} will display all child posts for the selected post."
				                        value={ attributes.post_type }
				                        options={ output }
				                        onChange={ ( value ) => {
				                            setAttributes( { post_type: value } );
				                            setAttributes( { post_ids: [] } );
				                        } }
				                    />
				                </areoi.components.PanelRow>
				            );
				        }

				    );

				    const PostsDropdownControl = areoi.compose.compose(
				        wp.data.withSelect( function( select, props ) {
				            
				            return {
				                posts: select( 'core' ).getEntityRecords( 'postType', props.post_type, { 
				                    per_page: -1,
				                    orderby : 'title',
				                    order : 'asc',
				                } ),
				            }
				        } ) )( function( props ) {
				            
				            var output = [];
				            if( props.posts ) {

				                var key = 'post_ids';

				                var new_output = <areoi.components.CheckboxControl
				                    label={ 'Show all ' + props.post_type + ' posts' }
				                    labelPosition="top"
				                    value={ 'all' }
				                    checked={ typeof attributes[key] != 'undefined' ? ( attributes[key] ? attributes[key].includes( 'all' ) : false ) : false }
				                    onChange={ function( value ) {
				                        
				                        var newArr = [];
				                        if ( typeof attributes[key] != 'undefined' ) {
				                            var newArr = attributes[key].slice();
				                        }

				                        if ( value ) {
				                            newArr.push( 'all' );
				                        } else {
				                            const index = newArr.indexOf( 'all' );
				                            if ( index > -1 ) {
				                                newArr.splice( index, 1 );
				                            }
				                        }
				                        setAttributes({ [key] : newArr });
				                    }}
				                />;
				                output.push( new_output );

				                props.posts.forEach((post) => {

				                    var new_output = <areoi.components.CheckboxControl
				                        label={ post.title.rendered }
				                        labelPosition="top"
				                        value={ post.id }
				                        checked={ typeof attributes[key] != 'undefined' ? ( attributes[key] ? attributes[key].includes( post.id ) : false ) : false }
				                        onChange={ function( value ) {
				                            
				                            var newArr = [];
				                            if ( typeof attributes[key] != 'undefined' ) {
				                                var newArr = attributes[key].slice();
				                            }

				                            if ( value ) {
				                                newArr.push( post.id );
				                            } else {
				                                const index = newArr.indexOf( post.id );
				                                if ( index > -1 ) {
				                                    newArr.splice( index, 1 );
				                                }
				                            }
				                            setAttributes({ [key] : newArr });
				                        }}
				                    />;
				                    output.push( new_output );
				                });

				            } else {
				               output = <div>
				                    <p class="text-center mb-0">Loading posts.</p>
				               </div>; 
				            }
				            return (
				                <div class="areoi-panel-row">
				                    <div class="areoi-post-list">
				                        { output }
				                    </div>
				                </div>
				            );
				        }
				    );
				    
					return (
			            <div { ...blockProps }>
			                <editor.InspectorControls key="setting">

			            		{
					                ( areoi_lightspeed_vars.pattern || areoi_lightspeed_vars.divider || areoi_lightspeed_vars.transition || areoi_lightspeed_vars.parallax ) && !is_header && 
					                <areoi.components.PanelBody title={ 'Lightspeed' } initialOpen={ false }>

					                    {
					                        areoi_lightspeed_vars.divider && attributes.hasOwnProperty( 'divider' ) &&
				                            <components.PanelRow className="areoi-panel-row">
						                        <components.SelectControl
						                            label="Divider Template"
						                            labelPosition="top"
						                            help={ __( 'Choose a divider template specific for this block.' ) }
						                            value={ attributes.divider }
						                            options={ areoi_lightspeed_vars.templates['dividers'] }
						                            onChange={ ( value ) => onChange( 'divider', value ) }
						                        />
						                    </components.PanelRow>
					                    }

					                    {
					                        attributes.hasOwnProperty( 'pattern' ) &&
				                            <components.PanelRow className="areoi-panel-row">
						                        <components.SelectControl
						                            label="Pattern Template"
						                            labelPosition="top"
						                            help={ __( 'Choose a pattern template specific for this block.' ) }
						                            value={ attributes.pattern }
						                            options={ areoi_lightspeed_vars.templates['patterns'] }
						                            onChange={ ( value ) => onChange( 'pattern', value ) }
						                        />
						                    </components.PanelRow>
					                    }

					                    {
					                        attributes.hasOwnProperty( 'mask' ) &&
				                            <components.PanelRow className="areoi-panel-row">
						                        <components.SelectControl
						                            label="Mask Template"
						                            labelPosition="top"
						                            help={ __( 'Choose a mask template specific for this block.' ) }
						                            value={ attributes.mask }
						                            options={ areoi_lightspeed_vars.templates['masks'] }
						                            onChange={ ( value ) => onChange( 'mask', value ) }
						                        />
						                    </components.PanelRow>
					                    }

					                    {
					                        areoi_lightspeed_vars.transition &&
					                        <areoi.components.PanelRow className={ 'areoi-panel-row areoi-panel-row-no-border' }>
					                            <areoi.components.ToggleControl 
					                                label={ 'Exclude transition' }
					                                help="If checked this block will not implement transitions."
					                                checked={ attributes.exclude_transition }
					                                onChange={ ( value ) => onChange( 'exclude_transition', value ) }
					                            />
					                        </areoi.components.PanelRow>
					                    }

					                    {
					                        areoi_lightspeed_vars.parallax &&
					                        <areoi.components.PanelRow className={ 'areoi-panel-row areoi-panel-row-no-border' }>
					                            <areoi.components.ToggleControl 
					                                label={ 'Exclude parallax' }
					                                help="If checked this block will not implement parallax."
					                                checked={ attributes.exclude_parallax }
					                                onChange={ ( value ) => onChange( 'exclude_parallax', value ) }
					                            />
					                        </areoi.components.PanelRow>
					                    }
					                    
					                </areoi.components.PanelBody>
					            }

			                	<components.PanelBody title={ __( 'Settings' ) } initialOpen={ false }>
				                    
				                    {
				                    	attributes.hasOwnProperty( 'filename' ) && 
				                    	<components.PanelRow className="areoi-panel-row">
					                        <components.SelectControl
					                            label="Template"
					                            labelPosition="top"
					                            help={ __( 'All templates include the same content, but display them in different ways.' ) }
					                            value={ attributes.filename }
					                            options={ areoi_lightspeed_vars.templates[template_key] }
					                            onChange={ ( value ) => onChange( 'filename', value ) }
					                        />
					                    </components.PanelRow>
				                    }

				                    {
				                    	attributes.hasOwnProperty( 'size' ) && !is_header &&
				                    	<components.PanelRow className="areoi-panel-row">
					                        <components.SelectControl
					                            label="Size"
					                            labelPosition="top"
					                            help={ __( 'Changing the size of a block will set a fixed height. Auto = auto, Small = 33vh, Medium = 66vh and Large = 100vh.' ) }
					                            value={ attributes.size }
					                            options={ [
				                                    { label: 'Auto', value: 'auto' },
				                                    { label: 'Small', value: '33vh' },
				                                    { label: 'Medium', value: '66vh' },
				                                    { label: 'Large', value: '100vh' },
				                                ] }
					                            onChange={ ( value ) => onChange( 'size', value ) }
					                        />
					                    </components.PanelRow>

				                    }

				                    {
				                    	attributes.hasOwnProperty( 'padding' ) &&
				                    	<components.PanelRow className="areoi-panel-row">
					                        <components.SelectControl
					                            label="Padding"
					                            labelPosition="top"
					                            help={ __( 'Changing the padding of a block will set a different padding top and bottom. The default is set within your Lightspeed settings.' ) }
					                            value={ attributes.padding }
					                            options={ [
				                                    { label: 'Default', value: '' },
				                                    { label: 'None', value: 'none' },
				                                    { label: 'Extra Small', value: 'xs' },
				                                    { label: 'Small', value: 'sm' },
				                                    { label: 'Medium', value: 'md' },
				                                    { label: 'Large', value: 'lg' },
				                                ] }
					                            onChange={ ( value ) => onChange( 'padding', value ) }
					                        />
					                    </components.PanelRow>

				                    }

				                    {
				                    	attributes.hasOwnProperty( 'alignment' ) && !is_header &&
				                    	<components.PanelRow className="areoi-panel-row">
					                        <components.SelectControl
					                            label="Alignment"
					                            labelPosition="top"
					                            help={ __( 'Changing the alignment of a block will position the content to the left or right of the strip.' ) }
					                            value={ attributes.alignment }
					                            options={ [
					                            	{ label: 'Default', value: '' },
				                                    { label: 'Content Left', value: 'start' },
				                                    { label: 'Content Right', value: 'end' },
				                                ] }
					                            onChange={ ( value ) => onChange( 'alignment', value ) }
					                        />
					                    </components.PanelRow>

				                    }

				                    {
			                    		attributes.hasOwnProperty( 'media_shape' ) &&
			                    		<areoi.components.PanelRow>
						                    <areoi.components.SelectControl
						                        label="Media Shape"
						                        labelPosition="top"
						                        help="Specify what shape you would like your media to be displayed."
						                        value={ attributes.media_shape }
						                        options={ [
						                        	{ value: '', label: 'Default' },
						                        	{ value: 'square', label: 'Square' },
						                        	{ value: 'rectangle', label: 'Rectangle' },
						                        	{ value: 'tall-rectangle', label: 'Tall Rectangle' },
						                        ] }
						                        onChange={ ( value ) => onChange( 'media_shape', value ) }
						                    />
						                </areoi.components.PanelRow>
			                    	}

			                    	{
			                    		attributes.hasOwnProperty( 'media_fit' ) &&
			                    		<areoi.components.PanelRow>
						                    <areoi.components.SelectControl
						                        label="Media Fit"
						                        labelPosition="top"
						                        help="Specify how you would like your media to fir the container."
						                        value={ attributes.media_fit }
						                        options={ [
						                        	{ value: '', label: 'Default' },
						                        	{ value: 'cover', label: 'Cover' },
						                        	{ value: 'contain', label: 'Contain' },
						                        ] }
						                        onChange={ ( value ) => onChange( 'media_fit', value ) }
						                    />
						                </areoi.components.PanelRow>
			                    	}

				                    {
				                    	attributes.hasOwnProperty( 'max_height' ) &&
					                    <components.PanelRow className="areoi-panel-row areoi-panel-row-no-border">
				                            <label className="areoi-panel-row__label">Max Height</label>
				                            <table>
				                                <tr>
				                                    <td>
				                                        <components.TextControl
				                                            label=""
				                                            value={ attributes['max_height'] }
				                                            onChange={ ( value ) => onChange( 'max_height', value ) }
				                                        />
				                                    </td>
				                                    <td>&nbsp;px</td>
				                                </tr>
				                            </table>
				                            <p className="components-base-control__help css-1gbp77-StyledHelp">Specify the maximum height to display all your logos. This will be applied to all of your items for consistency.</p>
				                        </components.PanelRow>
				                    }

				                    {
				                    	attributes.hasOwnProperty( 'max_width' ) &&
				                        <components.PanelRow className="areoi-panel-row areoi-panel-row-no-border">
				                            <label className="areoi-panel-row__label">Max Width</label>
				                            <table>
				                                <tr>
				                                    <td>
				                                        <components.TextControl
				                                            label=""
				                                            value={ attributes['max_width'] }
				                                            onChange={ ( value ) => onChange( 'max_width', value ) }
				                                        />
				                                    </td>
				                                    <td>&nbsp;%</td>
				                                </tr>
				                            </table>
				                            <p className="components-base-control__help css-1gbp77-StyledHelp">Specify the maximum width to display all your media. This will be applied to all of your items for consistency.</p>
				                        </components.PanelRow>
				                    }

				                    {
			                    		attributes.hasOwnProperty( 'container' ) &&
			                    		<areoi.components.PanelRow>
						                    <areoi.components.SelectControl
		                                        label="Container"
		                                        labelPosition="top"
		                                        help="Bootstrap has 3 container types: .container, which sets a max-width at each responsive breakpoint; .container-fluid, which is width: 100% at all breakpoints; and .container-{breakpoint}, which is width: 100% until the specified breakpoint."
		                                        value={ attributes.container }
		                                        options={ [
		                                            { label: '.container', value: 'container' },
		                                            { label: '.container-sm', value: 'container-sm' },
		                                            { label: '.container-md', value: 'container-md' },
		                                            { label: '.container-lg', value: 'container-lg' },
		                                            { label: '.container-xl', value: 'container-xl' },
		                                            { label: '.container-xxl', value: 'container-xxl' },
		                                            { label: '.container-fluid', value: 'container-fluid' },
		                                        ] }
		                                        onChange={ ( value ) => onChange( 'container', value ) }
		                                    />
						                </areoi.components.PanelRow>
			                    	}

			                    	{
			                    		attributes.hasOwnProperty( 'position' ) &&
			                    		<areoi.components.PanelRow>
						                    <areoi.components.SelectControl
		                                        label="Position"
		                                        labelPosition="top"
		                                        help="Select how you would like your header to be positioned."
		                                        value={ attributes.position }
		                                        options={ [
		                                            { label: 'Relative', value: 'position-relative' },
		                                            { label: 'Absolute', value: 'position-absolute' },
		                                            { label: 'Sticky', value: 'position-sticky' },
		                                            { label: 'Fixed', value: 'position-fixed' },
		                                        ] }
		                                        onChange={ ( value ) => onChange( 'position', value ) }
		                                    />
						                </areoi.components.PanelRow>
			                    	}

			                    	{
				                    	attributes.hasOwnProperty( 'logo' ) &&
				                    	<areoi.components.PanelRow className="areoi-panel-row">
			                                <areoi.components.SelectControl
			                                    label="Logo"
			                                    labelPosition="top"
			                                    help="Choose between displaying a logo or an icon. Logos and icons can be added in the plugin settings area."
			                                    value={ attributes.logo }
			                                    options={ [
			                                        { label: 'Default', value: '' },
			                                        { label: 'Logo', value: 'logo' },
			                                        { label: 'Icon', value: 'icon' },
			                                    ] }
			                                    onChange={ ( value ) => onChange( 'logo', value ) }
			                                />
			                            </areoi.components.PanelRow>
			                        }

			                    	{
				                    	attributes.hasOwnProperty( 'logo_height' ) &&
					                    <components.PanelRow className="areoi-panel-row areoi-panel-row-no-border">
				                            <label className="areoi-panel-row__label">Logo Height</label>
				                            <table>
				                                <tr>
				                                    <td>
				                                        <components.TextControl
				                                            label=""
				                                            value={ attributes['logo_height'] }
				                                            onChange={ ( value ) => onChange( 'logo_height', value ) }
				                                        />
				                                    </td>
				                                    <td>&nbsp;px</td>
				                                </tr>
				                            </table>
				                            <p className="components-base-control__help css-1gbp77-StyledHelp">Specify the height to display your logo.</p>
				                        </components.PanelRow>
				                    }

				                    {
				                    	attributes.hasOwnProperty( 'exclude_top_bar' ) &&
					                    <areoi.components.PanelRow className={ 'areoi-panel-row areoi-panel-row-no-border' }>
				                            <areoi.components.ToggleControl 
				                                label={ 'Exclude Top Bar' }
				                                help="If checked the top bar will not be displayed."
				                                checked={ attributes.exclude_top_bar }
				                                onChange={ ( value ) => onChange( 'exclude_top_bar', value ) }
				                            />
				                        </areoi.components.PanelRow>
				                    }

				                    {
				                    	attributes.hasOwnProperty( 'exclude_search' ) &&
					                    <areoi.components.PanelRow className={ 'areoi-panel-row areoi-panel-row-no-border' }>
				                            <areoi.components.ToggleControl 
				                                label={ 'Exclude Search' }
				                                help="If checked the search function will not be displayed."
				                                checked={ attributes.exclude_search }
				                                onChange={ ( value ) => onChange( 'exclude_search', value ) }
				                            />
				                        </areoi.components.PanelRow>
				                    }

				                    {
				                    	attributes.hasOwnProperty( 'exclude_company' ) &&
					                    <areoi.components.PanelRow className={ 'areoi-panel-row areoi-panel-row-no-border' }>
				                            <areoi.components.ToggleControl 
				                                label={ 'Exclude Company' }
				                                help="If checked this block will not display company contact details."
				                                checked={ attributes.exclude_company }
				                                onChange={ ( value ) => onChange( 'exclude_company', value ) }
				                            />
				                        </areoi.components.PanelRow>
				                    }

				                    {
				                    	attributes.hasOwnProperty( 'exclude_social' ) &&
					                    <areoi.components.PanelRow className={ 'areoi-panel-row areoi-panel-row-no-border' }>
				                            <areoi.components.ToggleControl 
				                                label={ 'Exclude Social' }
				                                help="If checked this block will not display social contact details."
				                                checked={ attributes.exclude_social }
				                                onChange={ ( value ) => onChange( 'exclude_social', value ) }
				                            />
				                        </areoi.components.PanelRow>
				                    }

				                    { custom_fields( meta, attributes, onChange ) }
				                    
			                    </components.PanelBody>

			                    {
			                    	( meta.supports.lightspeed_header || meta.supports.lightspeed_footer ) &&
			                    	<components.PanelBody title={ 'Colors' } initialOpen={ false }>
			                    		
			                    		{
			                    			attributes.hasOwnProperty( 'logo_color' ) &&
			                    			<areoi.components.PanelRow className="areoi-panel-row">
				                                <areoi.components.SelectControl
				                                    label="Logo Color"
				                                    labelPosition="top"
				                                    help="Choose between the light or dark variation of your logo / icon."
				                                    value={ attributes.logo_color }
				                                    options={ [
				                                        { label: 'Default', value: '' },
				                                        { label: 'Dark', value: 'dark' },
				                                        { label: 'Light', value: 'light' },
				                                    ] }
				                                    onChange={ ( value ) => onChange( 'logo_color', value ) }
				                                />
				                            </areoi.components.PanelRow>
			                    		}
			                    		{
				                    		attributes.hasOwnProperty( 'top_bar_background' ) &&
				                    		<areoi.components.PanelRow className="areoi-panel-row">
					                            <areoi.components.SelectControl
					                                label="Top Bar Background"
					                                labelPosition="top"
					                                help="Use the Bootstrap background utilities to change the background."
					                                value={ attributes.top_bar_background }
					                                options={ [
					                                    { label: 'None', value: '' },
					                                    { label: 'Primary', value: 'bg-primary' },
					                                    { label: 'Secondary', value: 'bg-secondary' },
					                                    { label: 'Success', value: 'bg-success' },
					                                    { label: 'Danger', value: 'bg-danger' },
					                                    { label: 'Warning', value: 'bg-warning' },
					                                    { label: 'Info', value: 'bg-info' },
					                                    { label: 'Light', value: 'bg-light' },
					                                    { label: 'Dark', value: 'bg-dark' },
					                                    { label: 'Body', value: 'bg-body' },
					                                    { label: 'Transparent', value: 'bg-transparent' },
					                                ] }
					                                onChange={ ( value ) => onChange( 'top_bar_background', value ) }
					                            />
					                        </areoi.components.PanelRow>
					                    }

					                    {
				                    		attributes.hasOwnProperty( 'top_bar_text' ) &&
					                        <areoi.components.PanelRow className="areoi-panel-row">
					                            <areoi.components.SelectControl
					                                label="Top Bar Text"
					                                labelPosition="top"
					                                help="Use the Bootstrap background utilities to change the text color."
					                                value={ attributes.top_bar_text }
					                                options={ [
					                                    { label: 'Default', value: '' },
					                                    { label: 'Primary', value: 'text-primary' },
					                                    { label: 'Secondary', value: 'text-secondary' },
					                                    { label: 'Success', value: 'text-success' },
					                                    { label: 'Danger', value: 'text-danger' },
					                                    { label: 'Warning', value: 'text-warning' },
					                                    { label: 'Info', value: 'text-info' },
					                                    { label: 'Light', value: 'text-light' },
					                                    { label: 'Dark', value: 'text-dark' },
					                                    { label: 'Body', value: 'text-body' },
					                                ] }
					                                onChange={ ( value ) => onChange( 'top_bar_text', value ) }
					                            />
					                        </areoi.components.PanelRow>
					                    }

					                    {
				                    		attributes.hasOwnProperty( 'top_bar_border' ) &&
					                        <areoi.components.PanelRow className="areoi-panel-row">
					                            <areoi.components.SelectControl
					                                label="Top Bar Border"
					                                labelPosition="top"
					                                help="Use the Bootstrap background utilities to change the border color."
					                                value={ attributes.top_bar_border }
					                                options={ [
					                                    { label: 'Default', value: '' },
					                                    { label: 'Primary', value: 'border-primary' },
					                                    { label: 'Secondary', value: 'border-secondary' },
					                                    { label: 'Success', value: 'border-success' },
					                                    { label: 'Danger', value: 'border-danger' },
					                                    { label: 'Warning', value: 'border-warning' },
					                                    { label: 'Info', value: 'border-info' },
					                                    { label: 'Light', value: 'border-light' },
					                                    { label: 'Dark', value: 'border-dark' },
					                                    { label: 'Body', value: 'border-body' },
					                                ] }
					                                onChange={ ( value ) => onChange( 'top_bar_border', value ) }
					                            />
					                        </areoi.components.PanelRow>
					                    }

					                    {
				                    		attributes.hasOwnProperty( 'main_background' ) &&
				                    		<areoi.components.PanelRow className="areoi-panel-row">
					                            <areoi.components.SelectControl
					                                label="Main Background"
					                                labelPosition="top"
					                                help="Use the Bootstrap background utilities to change the background."
					                                value={ attributes.main_background }
					                                options={ [
					                                    { label: 'None', value: '' },
					                                    { label: 'Primary', value: 'bg-primary' },
					                                    { label: 'Secondary', value: 'bg-secondary' },
					                                    { label: 'Success', value: 'bg-success' },
					                                    { label: 'Danger', value: 'bg-danger' },
					                                    { label: 'Warning', value: 'bg-warning' },
					                                    { label: 'Info', value: 'bg-info' },
					                                    { label: 'Light', value: 'bg-light' },
					                                    { label: 'Dark', value: 'bg-dark' },
					                                    { label: 'Body', value: 'bg-body' },
					                                    { label: 'Transparent', value: 'bg-transparent' },
					                                ] }
					                                onChange={ ( value ) => onChange( 'main_background', value ) }
					                            />
					                        </areoi.components.PanelRow>
					                    }

					                    {
				                    		attributes.hasOwnProperty( 'main_text' ) &&
					                        <areoi.components.PanelRow className="areoi-panel-row">
					                            <areoi.components.SelectControl
					                                label="Main Text"
					                                labelPosition="top"
					                                help="Use the Bootstrap background utilities to change the text color."
					                                value={ attributes.main_text }
					                                options={ [
					                                    { label: 'Default', value: '' },
					                                    { label: 'Primary', value: 'text-primary' },
					                                    { label: 'Secondary', value: 'text-secondary' },
					                                    { label: 'Success', value: 'text-success' },
					                                    { label: 'Danger', value: 'text-danger' },
					                                    { label: 'Warning', value: 'text-warning' },
					                                    { label: 'Info', value: 'text-info' },
					                                    { label: 'Light', value: 'text-light' },
					                                    { label: 'Dark', value: 'text-dark' },
					                                    { label: 'Body', value: 'text-body' },
					                                ] }
					                                onChange={ ( value ) => onChange( 'main_text', value ) }
					                            />
					                        </areoi.components.PanelRow>
					                    }

					                    {
				                    		attributes.hasOwnProperty( 'main_border' ) &&
					                        <areoi.components.PanelRow className="areoi-panel-row">
					                            <areoi.components.SelectControl
					                                label="Main Border"
					                                labelPosition="top"
					                                help="Use the Bootstrap background utilities to change the border color."
					                                value={ attributes.main_border }
					                                options={ [
					                                    { label: 'Default', value: 'border-0' },
					                                    { label: 'Primary', value: 'border-primary' },
					                                    { label: 'Secondary', value: 'border-secondary' },
					                                    { label: 'Success', value: 'border-success' },
					                                    { label: 'Danger', value: 'border-danger' },
					                                    { label: 'Warning', value: 'border-warning' },
					                                    { label: 'Info', value: 'border-info' },
					                                    { label: 'Light', value: 'border-light' },
					                                    { label: 'Dark', value: 'border-dark' },
					                                    { label: 'Body', value: 'border-body' },
					                                ] }
					                                onChange={ ( value ) => onChange( 'main_border', value ) }
					                            />
					                        </areoi.components.PanelRow>
					                    }

					                    {
				                    		attributes.hasOwnProperty( 'bottom_bar_background' ) &&
				                    		<areoi.components.PanelRow className="areoi-panel-row">
					                            <areoi.components.SelectControl
					                                label="Bottom Bar Background"
					                                labelPosition="top"
					                                help="Use the Bootstrap background utilities to change the background."
					                                value={ attributes.bottom_bar_background }
					                                options={ [
					                                    { label: 'None', value: '' },
					                                    { label: 'Primary', value: 'bg-primary' },
					                                    { label: 'Secondary', value: 'bg-secondary' },
					                                    { label: 'Success', value: 'bg-success' },
					                                    { label: 'Danger', value: 'bg-danger' },
					                                    { label: 'Warning', value: 'bg-warning' },
					                                    { label: 'Info', value: 'bg-info' },
					                                    { label: 'Light', value: 'bg-light' },
					                                    { label: 'Dark', value: 'bg-dark' },
					                                    { label: 'Body', value: 'bg-body' },
					                                ] }
					                                onChange={ ( value ) => onChange( 'bottom_bar_background', value ) }
					                            />
					                        </areoi.components.PanelRow>
					                    }

					                    {
				                    		attributes.hasOwnProperty( 'bottom_bar_text' ) &&
					                        <areoi.components.PanelRow className="areoi-panel-row">
					                            <areoi.components.SelectControl
					                                label="Bottom Bar Text"
					                                labelPosition="top"
					                                help="Use the Bootstrap background utilities to change the text color."
					                                value={ attributes.bottom_bar_text }
					                                options={ [
					                                    { label: 'Default', value: '' },
					                                    { label: 'Primary', value: 'text-primary' },
					                                    { label: 'Secondary', value: 'text-secondary' },
					                                    { label: 'Success', value: 'text-success' },
					                                    { label: 'Danger', value: 'text-danger' },
					                                    { label: 'Warning', value: 'text-warning' },
					                                    { label: 'Info', value: 'text-info' },
					                                    { label: 'Light', value: 'text-light' },
					                                    { label: 'Dark', value: 'text-dark' },
					                                    { label: 'Body', value: 'text-body' },
					                                ] }
					                                onChange={ ( value ) => onChange( 'bottom_bar_text', value ) }
					                            />
					                        </areoi.components.PanelRow>
					                    }

					                    {
				                    		attributes.hasOwnProperty( 'bottom_bar_border' ) &&
					                        <areoi.components.PanelRow className="areoi-panel-row">
					                            <areoi.components.SelectControl
					                                label="Bottom Bar Border"
					                                labelPosition="top"
					                                help="Use the Bootstrap background utilities to change the border color."
					                                value={ attributes.bottom_bar_border }
					                                options={ [
					                                    { label: 'Default', value: 'border-0' },
					                                    { label: 'Primary', value: 'border-primary' },
					                                    { label: 'Secondary', value: 'border-secondary' },
					                                    { label: 'Success', value: 'border-success' },
					                                    { label: 'Danger', value: 'border-danger' },
					                                    { label: 'Warning', value: 'border-warning' },
					                                    { label: 'Info', value: 'border-info' },
					                                    { label: 'Light', value: 'border-light' },
					                                    { label: 'Dark', value: 'border-dark' },
					                                    { label: 'Body', value: 'border-body' },
					                                ] }
					                                onChange={ ( value ) => onChange( 'bottom_bar_border', value ) }
					                            />
					                        </areoi.components.PanelRow>
					                    }

			                    	</components.PanelBody>
			                    }

			                    {
				                    attributes.hasOwnProperty( 'company_address' ) &&
				                    <components.PanelBody title={ 'Company' } initialOpen={ false }>

				                        <components.PanelRow className="areoi-panel-row">
				                            <components.TextControl
				                                label="Company Address"
				                                value={ attributes.company_address }
				                                onChange={ ( value ) => onChange( 'company_address', value ) }
				                            />
				                        </components.PanelRow>

				                        <components.PanelRow className="areoi-panel-row">
				                            <components.TextControl
				                                label="Company Phone"
				                                value={ attributes.company_phone }
				                                onChange={ ( value ) => onChange( 'company_phone', value ) }
				                            />
				                        </components.PanelRow>

				                        <components.PanelRow className="areoi-panel-row areoi-panel-row-no-border">
				                            <components.TextControl
				                                label="Company Email"
				                                value={ attributes.company_email }
				                                onChange={ ( value ) => onChange( 'company_email', value ) }
				                            />
				                        </components.PanelRow>

				                    </components.PanelBody>
				                }

				                {
				                    attributes.hasOwnProperty( 'social_facebook' ) &&
				                    <components.PanelBody title={ 'Social' } initialOpen={ false }>

				                        <components.PanelRow className="areoi-panel-row">
				                            <components.TextControl
				                                label="Facebook"
				                                value={ attributes.social_facebook }
				                                onChange={ ( value ) => onChange( 'social_facebook', value ) }
				                            />
				                        </components.PanelRow>

				                        <components.PanelRow className="areoi-panel-row">
				                            <components.TextControl
				                                label="Twitter"
				                                value={ attributes.social_twitter }
				                                onChange={ ( value ) => onChange( 'social_twitter', value ) }
				                            />
				                        </components.PanelRow>

				                        <components.PanelRow className="areoi-panel-row">
				                            <components.TextControl
				                                label="Instagram"
				                                value={ attributes.social_instagram }
				                                onChange={ ( value ) => onChange( 'social_instagram', value ) }
				                            />
				                        </components.PanelRow>

				                        <components.PanelRow className="areoi-panel-row">
				                            <components.TextControl
				                                label="YouTube"
				                                value={ attributes.social_youtube }
				                                onChange={ ( value ) => onChange( 'social_youtube', value ) }
				                            />
				                        </components.PanelRow>

				                        <components.PanelRow className="areoi-panel-row">
				                            <components.TextControl
				                                label="LinkedIn"
				                                value={ attributes.social_linkedin }
				                                onChange={ ( value ) => onChange( 'social_linkedin', value ) }
				                            />
				                        </components.PanelRow>

				                        <components.PanelRow className="areoi-panel-row">
				                            <components.TextControl
				                                label="Tik Tok"
				                                value={ attributes.social_tiktok }
				                                onChange={ ( value ) => onChange( 'social_tiktok', value ) }
				                            />
				                        </components.PanelRow>

				                        <components.PanelRow className="areoi-panel-row areoi-panel-row-no-border">
				                            <components.TextControl
				                                label="Pinterest"
				                                value={ attributes.social_pinterest }
				                                onChange={ ( value ) => onChange( 'social_pinterest', value ) }
				                            />
				                        </components.PanelRow>

				                    </components.PanelBody>
				                }

			                    {
			                    	attributes.hasOwnProperty( 'background_display' ) &&
			                    	areoi.Background( areoi, attributes, onChange )
			                    }

			                    {
			                    	( attributes.hasOwnProperty( 'heading' ) || attributes.hasOwnProperty( 'include_post_author' ) ) &&
				                    <components.PanelBody title={ __( 'Content' ) } initialOpen={ false }>
					                    
					                    {
					                    	attributes.hasOwnProperty( 'content_filename' ) && 
					                    	<components.PanelRow className="areoi-panel-row">
						                        <components.SelectControl
						                            label="Content Template"
						                            labelPosition="top"
						                            help={ __( 'All templates include the same content, but display them in different ways.' ) }
						                            value={ attributes.content_filename }
						                            options={ areoi_lightspeed_vars.templates['content'] }
						                            onChange={ ( value ) => onChange( 'content_filename', value ) }
						                        />
						                    </components.PanelRow>
					                    }

					                    {
					                    	attributes.hasOwnProperty( 'content_alignment' ) &&
					                    	<components.PanelRow className="areoi-panel-row">
						                        <components.SelectControl
						                            label="Content Alignment"
						                            labelPosition="top"
						                            value={ attributes.content_alignment }
						                            options={ [
						                            	{ label: 'Default', value: '' },
					                                    { label: 'Left', value: 'start' },
					                                    { label: 'Center', value: 'center' },
					                                    { label: 'Right', value: 'end' },
					                                ] }
						                            onChange={ ( value ) => onChange( 'content_alignment', value ) }
						                        />
						                    </components.PanelRow>

					                    }
					                    {
					                    	attributes.hasOwnProperty( 'is_post_title' ) &&
					                    	<areoi.components.PanelRow className={ 'areoi-panel-row areoi-panel-row-no-border' }>
					                            <areoi.components.ToggleControl 
					                                label={ 'Use Post Title' }
					                                help="If checked the heading will pull through the page / post title."
					                                checked={ attributes.is_post_title }
					                                onChange={ ( value ) => onChange( 'is_post_title', value ) }
					                            />
					                        </areoi.components.PanelRow>
					                    }

					                    {
					                    	attributes.hasOwnProperty( 'sub_heading' ) &&
					                    	<components.BaseControl label={ __( 'Sub Heading' ) }>

										        <editor.RichText
							                        tagName={ 'p' }
							                        inlineToolbar={ false }
							                        value={ attributes.sub_heading }
							                        onChange={ ( value ) => onChange( 'sub_heading', value ) }
							                        placeholder={ __( 'Enter sub heading...' ) }
							                    />
										    </components.BaseControl>
					                    }

					                    {
					                    	attributes.hasOwnProperty( 'heading' ) && ( !attributes.hasOwnProperty( 'is_post_title' ) || !attributes.is_post_title ) &&
					                    	<components.BaseControl label={ __( 'Heading' ) }>

										        <editor.RichText
							                        tagName={ 'h1' }
							                        inlineToolbar={ false }
							                        value={ attributes.heading }
							                        onChange={ ( value ) => onChange( 'heading', value ) }
							                        placeholder={ __( 'Enter heading...' ) }
							                    />
										    </components.BaseControl>
					                    }

					                    {
					                    	attributes.hasOwnProperty( 'is_post_excerpt' ) &&
					                    	<areoi.components.PanelRow className={ 'areoi-panel-row' }>
					                            <areoi.components.ToggleControl 
					                                label={ 'Use Post Excerpt' }
					                                help="If checked the introduction will pull through the page / post excerpt."
					                                checked={ attributes.is_post_excerpt }
					                                onChange={ ( value ) => onChange( 'is_post_excerpt', value ) }
					                            />
					                        </areoi.components.PanelRow>
					                    }

					                    {
					                    	attributes.hasOwnProperty( 'introduction' ) && ( !attributes.hasOwnProperty( 'is_post_excerpt' ) || !attributes.is_post_excerpt ) &&
					                    	<components.BaseControl label={ __( 'Introduction' ) }>
										        <editor.RichText
							                        tagName={ 'div' }
							                        multiline='p'
							                        inlineToolbar={ false }
							                        value={ attributes.introduction }
							                        onChange={ ( value ) => onChange( 'introduction', value ) }
							                        placeholder={ __( 'Add a short paragraph...' ) }
							                    />
										    </components.BaseControl> 
					                    }

					                    {
					                    	attributes.hasOwnProperty( 'include_read_more' ) &&
					                    	<areoi.components.PanelRow className={ 'areoi-panel-row' }>
					                            <areoi.components.ToggleControl 
					                                label={ 'Include Read More' }
					                                help="If checked the introduction will be limited to 400 characters and additional content will be displayed in a modal."
					                                checked={ attributes.include_read_more }
					                                onChange={ ( value ) => onChange( 'include_read_more', value ) }
					                            />
					                        </areoi.components.PanelRow>
					                    }

					                    {
					                    	attributes.hasOwnProperty( 'columns' ) && ( !attributes.hasOwnProperty( 'is_post_excerpt' ) || !attributes.is_post_excerpt ) &&
					                    	areoi.Items( areoi, attributes, onChange, 'Columns', 'columns', 'active_column' )
					                    }

					                    {
					                    	attributes.hasOwnProperty( 'include_cta' ) &&
					                    	<>
					                    		<components.PanelRow>
						                            <components.ToggleControl 
						                                label={ __( 'Include Call to Action' ) }
						                                checked={ attributes.include_cta }
						                                onChange={ ( value ) => onChange( 'include_cta', value ) }
						                            />
						                        </components.PanelRow>

											    {
											    	attributes.include_cta &&
											    	<>
												    	<components.BaseControl label={ __( 'Call to Action' ) }>
													        <editor.RichText
											                        tagName={ 'p' }
											                        inlineToolbar={ false }
											                        value={ attributes.cta }
											                        onChange={ ( value ) => onChange( 'cta', value ) }
											                        placeholder={ __( 'Add a CTA...' ) }
											                    />
													    </components.BaseControl> 

													    <components.PanelRow>
									                        <components.SelectControl
									                            label="Call to Action Size"
									                            labelPosition="top"
									                            help={ __( 'Use the Bootstrap button utilities to change the size of the cta.' ) }
									                            value={ attributes.cta_size }
									                            options={ [
								                                    { label: 'Small', value: 'btn-sm' },
								                                    { label: 'Medium', value: 'btn-md' },
								                                    { label: 'Large', value: 'btn-lg' },
								                                ] }
									                            onChange={ ( value ) => onChange( 'cta_size', value ) }
									                        />
									                    </components.PanelRow>

									                    <div className="areoi-link-control">
									                    	<label class="components-truncate components-text components-input-control__label">Call to Action URL</label>
										                    <editor.__experimentalLinkControl
																searchInputPlaceholder="Search here..."
																value={ {
																	url: attributes.url,
																	opensInNewTab: attributes.opensInNewTab
																} }
																onChange={ ( newUrl ) => {
																	onChange( 'url', newUrl.url )
																	onChange( 'opensInNewTab', newUrl.opensInNewTab )
																} }
																onRemove={ () => {
												                    onChange( 'url', '' )
																	onChange( 'opensInNewTab', false )
												                } }
															>
															</editor.__experimentalLinkControl>
														</div>
								                    </>
											    }
					                    	</>
					                    }

					                    {
					                    	attributes.hasOwnProperty( 'include_search' ) &&
					                    	<components.PanelRow>
					                            <components.ToggleControl 
					                                label={ __( 'Include Search Bar' ) }
					                                checked={ attributes.include_search }
					                                onChange={ ( value ) => onChange( 'include_search', value ) }
					                            />
					                        </components.PanelRow>
					                    }

					                    {
					                    	attributes.hasOwnProperty( 'include_post_author' ) &&
					                    	<components.PanelRow>
					                            <components.ToggleControl 
					                                label={ __( 'Include Post Author' ) }
					                                checked={ attributes.include_post_author }
					                                onChange={ ( value ) => onChange( 'include_post_author', value ) }
					                            />
					                        </components.PanelRow>
					                    }

					                    {
					                    	attributes.hasOwnProperty( 'include_post_date' ) &&
					                    	<components.PanelRow>
					                            <components.ToggleControl 
					                                label={ __( 'Include Post Date' ) }
					                                checked={ attributes.include_post_date }
					                                onChange={ ( value ) => onChange( 'include_post_date', value ) }
					                            />
					                        </components.PanelRow>
					                    }

					                    {
					                    	attributes.hasOwnProperty( 'include_post_categories' ) &&
					                    	<components.PanelRow>
					                            <components.ToggleControl 
					                                label={ __( 'Include Post Categories' ) }
					                                checked={ attributes.include_post_categories }
					                                onChange={ ( value ) => onChange( 'include_post_categories', value ) }
					                            />
					                        </components.PanelRow>
					                    }

					                    {
									    	attributes.hasOwnProperty( 'form_id' ) && areoi_lightspeed_vars.forms && areoi_lightspeed_vars.forms.length && 
									    	<components.PanelRow className="areoi-panel-row">
						                        <components.SelectControl
						                            label="Form"
						                            labelPosition="top"
						                            help={ __( 'Choose a form to display along with the content. These forms are pulled from the Ninja Forms plugin.' ) }
						                            value={ attributes.form_id }
						                            options={ areoi_lightspeed_vars.forms }
						                            onChange={ ( value ) => onChange( 'form_id', value ) }
						                        />
						                    </components.PanelRow>
									    }
									    
									</components.PanelBody>
								}

								{
									( attributes.hasOwnProperty( 'heading_color' ) || attributes.hasOwnProperty( 'introduction_color' ) ) &&
									<components.PanelBody title={ __( 'Content Colors' ) } initialOpen={ false }>

				                    	{
				                    		attributes.hasOwnProperty( 'heading_color' ) && attributes.heading &&
				                    		<components.PanelRow>
								                <components.SelectControl
								                    label={ __( 'Heading Color' ) }
								                    labelPosition="top"
								                    help={ __( 'Use the Bootstrap text color utilities to change the heading color.' ) }
								                    value={ attributes.heading_color }
								                    options={ [
								                        { label: 'Default', value: null },
								                        { label: 'Primary', value: 'text-primary' },
								                        { label: 'Secondary', value: 'text-secondary' },
								                        { label: 'Success', value: 'text-success' },
								                        { label: 'Danger', value: 'text-danger' },
								                        { label: 'Warning', value: 'text-warning' },
								                        { label: 'Info', value: 'text-info' },
								                        { label: 'Light', value: 'text-light' },
								                        { label: 'Dark', value: 'text-dark' },
								                    ] }
								                    onChange={ ( value ) => onChange( 'heading_color', value ) }
								                />
								            </components.PanelRow>
				                    	}

				                    	{
				                    		attributes.hasOwnProperty( 'sub_heading_color' ) && attributes.heading &&
				                    		<components.PanelRow>
								                <components.SelectControl
								                    label={ __( 'Sub Heading Color' ) }
								                    labelPosition="top"
								                    help={ __( 'Use the Bootstrap text color utilities to change the sub heading color.' ) }
								                    value={ attributes.sub_heading_color }
								                    options={ [
								                        { label: 'Default', value: null },
								                        { label: 'Primary', value: 'text-primary' },
								                        { label: 'Secondary', value: 'text-secondary' },
								                        { label: 'Success', value: 'text-success' },
								                        { label: 'Danger', value: 'text-danger' },
								                        { label: 'Warning', value: 'text-warning' },
								                        { label: 'Info', value: 'text-info' },
								                        { label: 'Light', value: 'text-light' },
								                        { label: 'Dark', value: 'text-dark' },
								                    ] }
								                    onChange={ ( value ) => onChange( 'sub_heading_color', value ) }
								                />
								            </components.PanelRow>
				                    	}

				                    	{
				                    		attributes.hasOwnProperty( 'introduction_color' ) &&
				                    		<components.PanelRow>
								                <components.SelectControl
								                    label={ __( 'Introduction Color' ) }
								                    labelPosition="top"
								                    help={ __( 'Use the Bootstrap text color utilities to change the introduction color.' ) }
								                    value={ attributes.introduction_color }
								                    options={ [
								                        { label: 'Default', value: null },
								                        { label: 'Primary', value: 'text-primary' },
								                        { label: 'Secondary', value: 'text-secondary' },
								                        { label: 'Success', value: 'text-success' },
								                        { label: 'Danger', value: 'text-danger' },
								                        { label: 'Warning', value: 'text-warning' },
								                        { label: 'Info', value: 'text-info' },
								                        { label: 'Light', value: 'text-light' },
								                        { label: 'Dark', value: 'text-dark' },
								                    ] }
								                    onChange={ ( value ) => onChange( 'introduction_color', value ) }
								                />
								            </components.PanelRow>
				                    	}
				                    	{
				                    		attributes.hasOwnProperty( 'cta_color' ) && attributes.cta &&
				                    		<components.PanelRow>
								                <components.SelectControl
								                    label={ __( 'Call to Action Color' ) }
								                    labelPosition="top"
								                    help={ __( 'Use the Bootstrap text color utilities to change the cta color.' ) }
								                    value={ attributes.cta_color }
								                    options={ [
								                        { label: 'Default', value: null },
								                        { label: 'Primary', value: 'btn-primary' },
								                        { label: 'Secondary', value: 'btn-secondary' },
								                        { label: 'Success', value: 'btn-success' },
								                        { label: 'Danger', value: 'btn-danger' },
								                        { label: 'Warning', value: 'btn-warning' },
								                        { label: 'Info', value: 'btn-info' },
								                        { label: 'Light', value: 'btn-light' },
								                        { label: 'Dark', value: 'btn-dark' },
								                    ] }
								                    onChange={ ( value ) => onChange( 'cta_color', value ) }
								                />
								            </components.PanelRow>
				                    	}

				                    	{
				                    		attributes.hasOwnProperty( 'post_background_color' ) &&
					                    	<components.PanelRow>
								                <components.SelectControl
								                    label={ __( 'Post Background Color' ) }
								                    labelPosition="top"
								                    help={ __( 'Use the Bootstrap bg color utilities to change the post background color.' ) }
								                    value={ attributes.post_background_color }
								                    options={ [
								                        { label: 'Default', value: "" },
								                        { label: 'Primary', value: 'bg-primary' },
								                        { label: 'Secondary', value: 'bg-secondary' },
								                        { label: 'Success', value: 'bg-success' },
								                        { label: 'Danger', value: 'bg-danger' },
								                        { label: 'Warning', value: 'bg-warning' },
								                        { label: 'Info', value: 'bg-info' },
								                        { label: 'Light', value: 'bg-light' },
								                        { label: 'Dark', value: 'bg-dark' },
								                    ] }
								                    onChange={ ( value ) => onChange( 'post_background_color', value ) }
								                />
								            </components.PanelRow>
								        }

								        {
				                    		attributes.hasOwnProperty( 'post_title_color' ) &&
					                    	<components.PanelRow>
								                <components.SelectControl
								                    label={ __( 'Post Title Color' ) }
								                    labelPosition="top"
								                    help={ __( 'Use the Bootstrap text color utilities to change the post title color.' ) }
								                    value={ attributes.post_title_color }
								                    options={ [
								                        { label: 'Default', value: "" },
								                        { label: 'Primary', value: 'text-primary' },
								                        { label: 'Secondary', value: 'text-secondary' },
								                        { label: 'Success', value: 'text-success' },
								                        { label: 'Danger', value: 'text-danger' },
								                        { label: 'Warning', value: 'text-warning' },
								                        { label: 'Info', value: 'text-info' },
								                        { label: 'Light', value: 'text-light' },
								                        { label: 'Dark', value: 'text-dark' },
								                    ] }
								                    onChange={ ( value ) => onChange( 'post_title_color', value ) }
								                />
								            </components.PanelRow>
								        }

								        {
				                    		attributes.hasOwnProperty( 'post_excerpt_color' ) &&
								            <components.PanelRow>
								                <components.SelectControl
								                    label={ __( 'Post Excerpt Color' ) }
								                    labelPosition="top"
								                    help={ __( 'Use the Bootstrap text color utilities to change the post excerpt color.' ) }
								                    value={ attributes.post_excerpt_color }
								                    options={ [
								                        { label: 'Default', value: "" },
								                        { label: 'Primary', value: 'text-primary' },
								                        { label: 'Secondary', value: 'text-secondary' },
								                        { label: 'Success', value: 'text-success' },
								                        { label: 'Danger', value: 'text-danger' },
								                        { label: 'Warning', value: 'text-warning' },
								                        { label: 'Info', value: 'text-info' },
								                        { label: 'Light', value: 'text-light' },
								                        { label: 'Dark', value: 'text-dark' },
								                    ] }
								                    onChange={ ( value ) => onChange( 'post_excerpt_color', value ) }
								                />
								            </components.PanelRow>
								        }

				                    </components.PanelBody>
								}

								{
									attributes.hasOwnProperty( 'display_posts' ) &&
									<areoi.components.PanelBody title={ 'Posts' } initialOpen={ false }>
			                        	
			                        	{
					                    	attributes.hasOwnProperty( 'is_post_query' ) &&
					                    	<components.PanelRow>
					                            <components.ToggleControl 
					                                label={ __( 'Use Current Page / Post Query' ) }
					                                checked={ attributes.is_post_query }
					                                onChange={ ( value ) => onChange( 'is_post_query', value ) }
					                            />
					                        </components.PanelRow>
					                    }

					                    {
					                    	!attributes.hasOwnProperty( 'is_post_query' ) || ( attributes.hasOwnProperty( 'is_post_query' ) && !attributes.is_post_query ) &&
					                        <>
						                        <PostTypesControl />

						                        <areoi.components.PanelRow className="areoi-panel-row">
						                            <areoi.components.SelectControl
						                                label="Display Posts"
						                                labelPosition="top"
						                                help="Choose whether to display the selected posts or children of the selected posts."
						                                value={ attributes.display_posts }
						                                options={ [
						                                    { label: 'Show selected posts', value: 'selected' },
						                                    { label: 'Show children of selected posts', value: 'children' },
						                                ] }
						                                 onChange={ ( value ) => onChange( 'display_posts', value ) }
						                            />
						                        </areoi.components.PanelRow>

						                        <PostsDropdownControl post_type={ attributes['post_type'] ? attributes['post_type'] : 'post' } />

						                        <areoi.components.PanelRow className="areoi-panel-row">
						                            <areoi.components.SelectControl
						                                label="Order By"
						                                labelPosition="top"
						                                help="Sort retrieved posts by parameter."
						                                value={ attributes.orderby }
						                                options={ [
						                                    { label: 'None', value: 'none' },
						                                    { label: 'Title', value: 'title' },
						                                    { label: 'Menu Order', value: 'menu_order' },
						                                    { label: 'Date', value: 'date' },
						                                    { label: 'Random', value: 'rand' },
						                                ] }
						                                 onChange={ ( value ) => onChange( 'orderby', value ) }
						                            />
						                        </areoi.components.PanelRow>

						                        <areoi.components.PanelRow className="areoi-panel-row">
						                            <areoi.components.SelectControl
						                                label="Order"
						                                labelPosition="top"
						                                help="Designates the ascending or descending order of the 'orderby' parameter."
						                                value={ attributes.order }
						                                options={ [
						                                    { label: 'ASC', value: 'asc' },
						                                    { label: 'DESC', value: 'desc' },
						                                ] }
						                                 onChange={ ( value ) => onChange( 'order', value ) }
						                            />
						                        </areoi.components.PanelRow>

						                        <areoi.components.PanelRow className="areoi-panel-row">
						                            <areoi.components.TextControl
						                                label="Posts Per Page"
						                                labelPosition="top"
						                                help="Specify the number of posts you would like to display."
						                                value={ attributes.posts_per_page }
						                                onChange={ ( value ) => onChange( 'posts_per_page', value ) }
						                            />
						                        </areoi.components.PanelRow>
						                    </>
					                    }

				                        <areoi.components.PanelRow className={ attributes.include_pagination ? 'areoi-panel-row' : '' }>
				                            <areoi.components.ToggleControl 
				                                label={ 'Include Pagination' }
				                                help="Toggle on to display pagination at the bottom of the block."
				                                checked={ attributes.include_pagination }
				                                onChange={ ( value ) => onChange( 'include_pagination', value ) }
				                            />
				                        </areoi.components.PanelRow>

				                        { attributes.include_pagination &&
				                            <areoi.components.PanelRow className={ 'areoi-panel-row' }>
				                                <areoi.components.SelectControl
				                                    label="Pagination Color"
				                                    labelPosition="top"
				                                    help="Use the Bootstrap btn color utilities to change the button color for pagination."
				                                    value={ attributes.pagination_color }
				                                    options={ [
				                                        { label: 'Default', value: 'btn-primary' },
				                                        { label: 'Primary', value: 'btn-primary' },
				                                        { label: 'Primary (Outline)', value: 'btn-outline-primary' },
				                                        { label: 'Secondary', value: 'btn-secondary' },
				                                        { label: 'Secondary (Outline)', value: 'btn-outline-secondary' },
				                                        { label: 'Success', value: 'btn-success' },
				                                        { label: 'Success (Outline)', value: 'btn-outline-success' },
				                                        { label: 'Danger', value: 'btn-danger' },
				                                        { label: 'Danger (Outline)', value: 'btn-outline-danger' },
				                                        { label: 'Warning', value: 'btn-warning' },
				                                        { label: 'Warning (Outline)', value: 'btn-outline-warning' },
				                                        { label: 'Info', value: 'btn-info' },
				                                        { label: 'Info (Outline)', value: 'btn-outline-info' },
				                                        { label: 'Light', value: 'btn-light' },
				                                        { label: 'Light (Outline)', value: 'btn-outline-light' },
				                                        { label: 'Dark', value: 'btn-dark' },
				                                        { label: 'Dark (Outline)', value: 'btn-outline-dark' },
				                                    ] }
				                                    onChange={ ( value ) => onChange( 'pagination_color', value ) }
				                                />
				                            </areoi.components.PanelRow>
				                        }

				                        <areoi.components.PanelRow className={ 'areoi-panel-row' }>
				                            <areoi.components.ToggleControl 
				                                label={ 'Include Media' }
				                                help="Toggle on to display the featured image for each item."
				                                checked={ attributes.include_media }
				                                onChange={ ( value ) => onChange( 'include_media', value ) }
				                            />
				                        </areoi.components.PanelRow>

				                        <areoi.components.PanelRow className={ 'areoi-panel-row' }>
				                            <areoi.components.ToggleControl 
				                                label={ 'Include Title' }
				                                help="Toggle on to display the title for each item."
				                                checked={ attributes.include_title }
				                                onChange={ ( value ) => onChange( 'include_title', value ) }
				                            />
				                        </areoi.components.PanelRow>

				                        <areoi.components.PanelRow className={ 'areoi-panel-row' }>
				                            <areoi.components.ToggleControl 
				                                label={ 'Include Excerpt' }
				                                help="Toggle on to display the excerpt for each item."
				                                checked={ attributes.include_excerpt }
				                                onChange={ ( value ) => onChange( 'include_excerpt', value ) }
				                            />
				                        </areoi.components.PanelRow>

				                        <areoi.components.PanelRow className={ 'areoi-panel-row areoi-panel-row-no-border' }>
				                            <areoi.components.ToggleControl 
				                                label={ 'Include Permalink' }
				                                help="Toggle on to include a stretched link for each item."
				                                checked={ attributes.include_permalink }
				                                onChange={ ( value ) => onChange( 'include_permalink', value ) }
				                            />
				                        </areoi.components.PanelRow>

				                    </areoi.components.PanelBody>
								}

			                    {
			                    	( attributes.hasOwnProperty( 'image' ) || attributes.hasOwnProperty( 'gallery' ) ) &&
				                    <components.PanelBody title={ __( 'Media' ) } initialOpen={ false }>

				                    	{ 
				                    		attributes.hasOwnProperty( 'gallery' ) &&
				                    		areoi.MediaGallery( areoi, attributes, onChange, 'Gallery', ['image', 'video'], 'gallery' ) 
				                    	}
				                    	
				                    	{
					                    	attributes.hasOwnProperty( 'is_post_image' ) &&
					                    	<areoi.components.PanelRow className={ 'areoi-panel-row areoi-panel-row-no-border' }>
					                            <areoi.components.ToggleControl 
					                                label={ 'Use Post Featured Image' }
					                                help="If checked the image will pull through the page / post featured image."
					                                checked={ attributes.is_post_image }
					                                onChange={ ( value ) => onChange( 'is_post_image', value ) }
					                            />
					                        </areoi.components.PanelRow>
					                    }

					                    {
					                    	( attributes.hasOwnProperty( 'image' ) && ( !attributes.hasOwnProperty( 'is_post_image' ) || !attributes.is_post_image ) ) &&
					                    	areoi.MediaUpload( areoi, attributes, onChange, 'Image', 'image', 'image' )
					                    }

				                        {
					                    	attributes.hasOwnProperty( 'video' ) &&
					                    	areoi.MediaUpload( areoi, attributes, onChange, 'Video', 'video', 'video' ) 
					                    }  	

				                    </components.PanelBody>
				                }

			                    {
			                    	attributes.hasOwnProperty( 'items' ) &&
			                    	areoi.Items( areoi, attributes, onChange, 'Items', 'items', 'active_item' )
			                    }
			                        
			                </editor.InspectorControls>

			                {
					            attributes.hasOwnProperty( 'align' ) &&
				                <areoi.editor.BlockControls>
					                { areoi.Alignment( areoi, attributes, onChange ) }
					            </areoi.editor.BlockControls>
					        }

			                <ServerSideRender
				                block={ meta.name }
				                attributes={ attributes }
				                httpMethod="POST"
				            />
			            </div>
					);
			    },
	        }

	        registerBlockType( { name, ...meta }, settings );
	    }).catch(err => {
	        console.log(err);
	    });
	}
}