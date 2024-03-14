/**
 * Import block dependencies
 */
import ServerSideRender from '@wordpress/server-side-render';

import { __ } from '@wordpress/i18n';

import {	 
	InspectorControls,
	useBlockProps
} from '@wordpress/block-editor';

import {
	Disabled,
	PanelBody,
	PanelRow,
	RangeControl,
	SelectControl,
	ToggleControl
} from '@wordpress/components';

import { 
	useEffect,
	useRef
} from '@wordpress/element';

import { doAction } from '@wordpress/hooks';

import { useSelect } from '@wordpress/data';

import { 
	BuildTree,
	GroupByParent
 } from '../helper.js';

/**
 * Describes the structure of the block in the context of the editor.
 * This represents what the editor will render when the block is used.
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {

	const {
		view,
		parent,
		columns,
		depth,			
		orderby,
		order,
		show_count,
		hide_empty,			
	} = attributes;

	const categoriesList = useSelect( ( select ) => {
		const terms = select( 'core' ).getEntityRecords( 'taxonomy', 'acadp_categories', {
			'per_page': 100
		});

		let options = [{ 
			label: '— ' + __( 'Select parent' ) + ' —', 
			value: 0
		}];

		if ( terms && terms.length > 0 ) {		
			let grouped = GroupByParent( terms, 0 );
			let tree = BuildTree( grouped );
			
			options = [ ...options, ...tree ];
		}

		return options;
	});

	const mounted = useRef();	
	useEffect(() => {
		if ( ! mounted.current ) {
			// Do componentDidMount logic
			mounted.current = true;
		} else {
			// Do componentDidUpdate logic
			doAction( 'acadp_init_categories', attributes );
		}
	});

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Categories settings' ) }>
					<PanelRow>
						<SelectControl
							label={ __( 'Select template' ) }
							value={ view }
							options={ [
								{ label: __( 'List' ), value: 'text_list' },
								{ label: __( 'Grid' ), value: 'image_grid' }								
							] }
							onChange={ ( value ) => setAttributes( { view: value } ) }
						/>
					</PanelRow>

					<PanelRow>
						<SelectControl
							label={ __( 'Select parent' ) }
							value={ parent }
							options={ categoriesList }
							onChange={ ( value ) => setAttributes( { parent: Number( value ) } ) }
						/>
					</PanelRow>				

					<PanelRow>
						<RangeControl
							label={ __( 'Number of columns' ) }
							value={ columns }							
							min={ 1 }
							max={ 12 }
							onChange={ ( value ) => setAttributes( { columns: value } ) }
						/>
					</PanelRow>

					<PanelRow>
						<RangeControl
							label={ __( 'Depth' ) }
							value={ depth }							
							min={ 1 }
							max={ 12 }
							onChange={ ( value ) => setAttributes( { depth: value } ) }
						/>
					</PanelRow>

					<PanelRow>
						<SelectControl
							label={ __( 'Order by' ) }
							value={ orderby }
							options={[
								{ label: __( 'ID' ), value: 'id' },
								{ label: __( 'Count' ), value: 'count' },
								{ label: __( 'Name' ), value: 'name' },
								{ label: __( 'Slug' ), value: 'slug' }
							]}
							onChange={ ( value ) => setAttributes( { orderby: value } ) }
						/>
					</PanelRow>

					<PanelRow>
						<SelectControl
							label={ __( 'Order' ) }
							value={ order }
							options={ [
								{ label: __( 'Ascending' ), value: 'asc' },
								{ label: __( 'Descending' ), value: 'desc' }
							] }
							onChange={ ( value ) => setAttributes( { order: value } ) }
						/>
					</PanelRow>

					<PanelRow>
						<ToggleControl
							label={ __( 'Show listings count' ) }
							help={ __( 'Check this to show the listings count next to the category name' ) }
							checked={ show_count }
							onChange={ () => setAttributes( { show_count: ! show_count } ) }
						/>
					</PanelRow>

					<PanelRow>
						<ToggleControl
							label={ __( 'Hide empty categories' ) }
							help={ __( 'Check this to hide categories with no listings' ) }
							checked={ hide_empty }
							onChange={ () => setAttributes( { hide_empty: ! hide_empty } ) }
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>

			<div { ...useBlockProps() }>
				<Disabled>
					<ServerSideRender 
						block="acadp/categories" 
						attributes={ attributes } 
					/>
				</Disabled>	
			</div>					
		</>
	);
}
