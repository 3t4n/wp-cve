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
		category,
		location,	
		columns,
		listings_per_page,		
		filterby,
		orderby,
		order,		
		featured,			
		header,
		show_excerpt,
		show_category,
		show_location,
		show_price,
		show_date,
		show_user,
		show_views,
		show_custom_fields,
		pagination,
	} = attributes;

	const locationsList = useSelect( ( select ) => {
		const terms = select( 'core' ).getEntityRecords( 'taxonomy', 'acadp_locations', {
			'per_page': 100
		});		

		let options = [{ 
			label: '— ' + __( 'All Locations' ) + ' —', 
			value: 0
		}];

		if ( terms && terms.length > 0 ) {		
			let grouped = GroupByParent( terms, parseInt( acadp_blocks.base_location ) );
			let tree = BuildTree( grouped );
			
			options = [ ...options, ...tree ];
		}

		return options;
	});

	const categoriesList = useSelect( ( select ) => {
		const terms = select( 'core' ).getEntityRecords( 'taxonomy', 'acadp_categories', {
			'per_page': 100
		});

		let options = [{ 
			label: '— ' + __( 'All Categories' ) + ' —', 
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
			doAction( 'acadp_init_listings', attributes );
		}
	});

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Listings settings' ) }>
					<PanelRow>
						<SelectControl
							label={ __( 'Select template' ) }
							value={ view }
							options={ [
								{ label: __( 'List' ), value: 'list' },
								{ label: __( 'Grid' ), value: 'grid' },
								{ label: __( 'Map' ), value: 'map' }
							] }
							onChange={ ( value ) => setAttributes( { view: value } ) }
						/>
					</PanelRow>

					<PanelRow>
						<SelectControl
							label={ __( 'Select location' ) }
							value={ location }
							options={ locationsList }
							onChange={ ( value ) => setAttributes( { location: Number( value ) } ) }
						/>
					</PanelRow>

					<PanelRow>
						<SelectControl
							label={ __( 'Select category' ) }
							value={ category }
							options={ categoriesList }
							onChange={ ( value ) => setAttributes( { category: Number( value ) } ) }
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
							label={ __( 'Number of listings' ) }
							help={ __( 'Enter the number of listings to show per page. Add "0" to show all listings.' ) }
							value={ listings_per_page }							
							min={ 1 }
							max={ 100 }
							onChange={ ( value ) => setAttributes( { listings_per_page: value } ) }
						/>
					</PanelRow>

					<PanelRow>
						<SelectControl
							label={ __( 'Filter by' ) }
							value={ filterby }
							options={ [
								{ label: __( 'None' ), value: '' },
								{ label: __( 'Featured' ), value: 'featured' }
							] }
							onChange={ ( value ) => setAttributes( { filterby: value } ) }
						/>
					</PanelRow>

					<PanelRow>
						<SelectControl
							label={ __( 'Order by' ) }
							value={ orderby }
							options={ [
								{ label: __( 'Title' ), value: 'title' },
								{ label: __( 'Date posted' ), value: 'date' },
								{ label: __( 'Price' ), value: 'price' },
								{ label: __( 'Views count' ), value: 'views' },
								{ label: __( 'Random' ), value: 'rand' }
							] }
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
							label={ __( 'Show featured' ) }
							help={ __( 'Show or hide featured listings at the top of normal listings.' ) }
							checked={ featured }
							onChange={ () => setAttributes( { featured: ! featured } ) }
						/>
					</PanelRow>

					<PanelRow>
						<ToggleControl
							label={ __( 'Show header' ) }
							help={ __( 'The header section consist of the "Listings count", "Views switcher", and "Sort by" options.' ) }
							checked={ header }
							onChange={ () => setAttributes( { header: ! header } ) }
						/>
					</PanelRow>

					<PanelRow>
						<ToggleControl
							label={ __( 'Show excerpt (short description)' ) }
							checked={ show_excerpt }
							onChange={ () => setAttributes( { show_excerpt: ! show_excerpt } ) }
						/>
					</PanelRow>

					<PanelRow>
						<ToggleControl
							label={ __( 'Show category name' ) }
							checked={ show_category }
							onChange={ () => setAttributes( { show_category: ! show_category } ) }
						/>
					</PanelRow>

					<PanelRow>
						<ToggleControl
							label={ __( 'Show location name' ) }
							checked={ show_location }
							onChange={ () => setAttributes( { show_location: ! show_location } ) }
						/>
					</PanelRow>

					<PanelRow>
						<ToggleControl
							label={ __( 'Show price' ) }
							checked={ show_price }
							onChange={ () => setAttributes( { show_price: ! show_price } ) }
						/>
					</PanelRow>

					<PanelRow>
						<ToggleControl
							label={ __( 'Show date' ) }
							checked={ show_date }
							onChange={ () => setAttributes( { show_date: ! show_date } ) }
						/>
					</PanelRow>

					<PanelRow>
						<ToggleControl
							label={ __( 'Show user' ) }
							checked={ show_user }
							onChange={ () => setAttributes( { show_user: ! show_user } ) }
						/>
					</PanelRow>

					<PanelRow>
						<ToggleControl
							label={ __( 'Show views' ) }
							checked={ show_views }
							onChange={ () => setAttributes( { show_views: ! show_views } ) }
						/>
					</PanelRow>

					<PanelRow>
						<ToggleControl
							label={ __( 'Show custom fields' ) }
							checked={ show_custom_fields }
							onChange={ () => setAttributes( { show_custom_fields: ! show_custom_fields } ) }
						/>
					</PanelRow>

					<PanelRow>
						<ToggleControl
							label={ __( 'Show pagination' ) }
							checked={ pagination }
							onChange={ () => setAttributes( { pagination: ! pagination } ) }
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>

			<div { ...useBlockProps() }>
				<Disabled>
					<ServerSideRender
						block="acadp/listings"
						attributes={ attributes }
					/>
				</Disabled>
			</div>
		</>
	);
}
