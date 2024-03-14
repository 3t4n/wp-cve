'use strict';

import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { RichText, MediaUpload } from '@wordpress/block-editor';
import { SelectControl, DropdownMenu, MenuGroup, MenuItem } from '@wordpress/components';
import { Fragment } from '@wordpress/element';
const { RawHTML } = wp.element;

registerBlockType( 'otw-shortcode-component/otw-shortcode', {
	title: __( 'OTW Shortcode', 'otw-shortcode-component' ),
	icon: 'align-right',
	category: 'layout',
	attributes: {
		otw_code_value: {
			type: 'string',
			default: '',
			attribute: 'otw_code_value'
		},
		otw_code_iname: {
			type: 'string',
			default: '',
			attribute: 'otw_code_iname'
		},
		otw_code_type: {
			type: 'string',
			default: '',
			attribute: 'otw_code_type'
		}
	},
	edit: ( props ) => {
		
		const otwOnMenuClick = ( otw_shortcode_name ) => {
			
			otw_shortcode_component.insert_code = function( code ){
				props.setAttributes( { 
					otw_code_value: code.shortcode_code,
					otw_code_iname: code.iname,
					otw_code_type: otw_shortcode_component.shortcodes[ code.shortcode_type ].title
				} );
				tb_remove();
			};
			otw_shortcode_component.load_shortcode_editor_dialog( otw_shortcode_name );
		};
		
		var codes = [];
		var sub_codes = {};
		
		for( var code_index in otw_shortcode_component.shortcodes ){
		
			if( otw_shortcode_component.shortcodes[ code_index ].enabled == true ){
			
				if( otw_shortcode_component.shortcodes[ code_index ].children == false ){
					var s_obj = otw_shortcode_component.shortcodes[ code_index ];
					s_obj.index = code_index;
					
					codes.push( s_obj );
				}
			}
		}
		
		var t_styles = {
			resize: 'none',
			backgroundColor: 'white',
			height: '30px'
		}
		
		var m_styles = {};
		var b_styles = {};
		var sub_styles = {
			fontStyle: 'italic',
			fontSize: '12px',
			fontWeight: 'normal',
			clear: 'both'
		};
		
		if( props.attributes.otw_code_value == '' ){
			t_styles.display = 'none';
			sub_styles.display = 'none';
			m_styles.display = 'flex';
			b_styles.backgroundColor = 'cornsilk';
		}else{
			t_styles.display = 'block';
			sub_styles.display = 'block';
			m_styles.display = 'none';
		}
		
		
		
		return (
			<div class="block-editor-block-list__block wp-block components-placeholder" style={ b_styles }>
				<label class="components-placeholder__label" style={ m_styles } >
				<DropdownMenu icon="plus-alt" label={ __( 'Select OTW Shortcode', 'otw-shortcode-component' ) } title={ __( 'Select OTW Shortcode', 'otw-shortcode-component' ) }>
				{ ( { onClose } ) => (
					<Fragment>
						<MenuGroup>
							{codes.map((code, index) =>
								<MenuItem onClick={ ( e ) => { otwOnMenuClick( code.index ) } }>{code.title}</MenuItem>
							)}
						</MenuGroup>
						<MenuGroup>
							<MenuItem onClick={ onClose }>{ __( 'Close', 'otw-shortcode-component' ) }
							</MenuItem>
						</MenuGroup>
					</Fragment>
				) }
				</DropdownMenu>{ __( 'Select OTW Shortcode', 'otw-shortcode-component' ) }
				</label>
				<label class="components-placeholder__label" style={ t_styles } >{ __( 'OTW Shortcode', 'otw-shortcode-component' ) }
				
				</label>
				<div style={ sub_styles }>{ props.attributes.otw_code_type } { props.attributes.otw_code_iname }</div>
				<textarea class="block-editor-plain-text blocks-shortcode__textarea" readonly="readonly" style={t_styles} value={ props.attributes.otw_code_value } />
			</div>
		);
	},
	save: ( props ) => {
		
		return (
			<div><RawHTML>{ props.attributes.otw_code_value }</RawHTML></div>
		)
	}
} );