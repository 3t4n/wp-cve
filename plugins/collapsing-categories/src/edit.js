/**
 * WordPress components that create the necessary UI elements for the block
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-components/
 */
//import { map, filter } from 'lodash';
import { TextControl } from '@wordpress/components';
import { withSelect, subscribe } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';

import ServerSideRender from "@wordpress/server-side-render";
const { InspectorControls } = wp.blockEditor;
const { ToggleControl, PanelBody, PanelRow, CheckboxControl, SelectControl, ColorPicker, Placeholder, Spinner } = wp.components;

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @param {Object}   props               Properties passed to the function.
 * @param {Object}   props.attributes    Available block attributes.
 * @param {Function} props.setAttributes Function that updates individual attributes.
 *
 * @return {WPElement} Element to render.
 */
function Edit( props ) {
	if ( props.attributes.__internalWidgetId ) {
		props.setAttributes( {blockId: props.attributes.__internalWidgetId });
	} else if ( props.clientId ) {
		props.setAttributes( {blockId: props.clientId});
	} else {
		props.setAttributes( {blockId: 'block-1'});
	}

	function excludedPostTypes( postType ) {
		let excludePostTypes = [ 'wp_block', 'wp_template', 'wp_template_part', 'wp_navigation', 'nav_menu_item' ];
		if ( ! excludePostTypes.includes( postType.slug ) ) {
			return postType;
		}
	}

	function excludedTaxonomies( taxonomy ) {
		let excludeTaxonomies = [ 'nav_menu' ];
		if ( ! excludeTaxonomies.includes( taxonomy.slug ) ) {
			return taxonomy;
		}
	}

	const getPostTypeOptions = () => {
		const selectOption = {
			label: __( '- Select -' ),
			value: '',
			disabled: true,
		};
		let postTypeOptions = [];
		if ( props.postTypes ) {
			postTypeOptions = props.postTypes.filter(excludedPostTypes).map(
				item => {
					return {
						value: item.slug,
						label: item.name,
					};
				}
			);
		}
		 return [ selectOption, ...postTypeOptions ];
	 }
	 const getTaxonomyOptions = () => {
		const selectOption = {
			label: __( '- Select -' ),
			value: '',
			disabled: true,
		};
		let taxonomyOptions = [];
		if ( props.taxonomies ) {
			taxonomyOptions = props.taxonomies.filter( excludedTaxonomies ).map(
				item => {
					return {
						value: item.slug,
						label: item.name,
					};
				}
			);
		}

    return [ selectOption, ...taxonomyOptions ];
  };
	const blockProps = useBlockProps();
	return (
		<div {...blockProps}>
				<ServerSideRender block="collapsing/categories" attributes={props.attributes} />
				<InspectorControls>
					<PanelBody
						title={__("Collapsing Category settings", "collapsing-categories")}
						initialOpen={true}
					>
						<PanelRow>
							<TextControl
								label={__("Title", "collapsing-categories")}
								 autoComplete="off"
									value={ props.attributes.widgetTitle || '' }
									onChange={ ( nextValue ) => {
										props.setAttributes( { widgetTitle: nextValue } );
									} }
							/>
						</PanelRow>
						<PanelRow>
							<SelectControl
								label={__("Expanding shows")}
								value={props.attributes.showPosts}
								options={[
									{label: __("Sub-categories and posts"), value: '1'},
									{label: __("Just sub-categories"), value: '0'},
								]}
								onChange={(newval) => { props.setAttributes( {showPosts: newval })}}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label={__("Show post count")}
								checked={props.attributes.showPostCount}
								onChange={(value) => { props.setAttributes( { showPostCount: value } );} }
							/>
						</PanelRow>
						<PanelRow>
							<SelectControl
								label={__("Sort categories by", 'collapsing-categories')}
								value={props.attributes.catSort}
								options={[
									{label: __("Name", 'collapsing-categories'), value: 'catName'},
									{label: __("Id", 'collapsing-categories'), value: 'catId'},
									{label: __("Slug", 'collapsing-categories'), value: 'catSlug'},
									{label: __("Category Order", 'collapsing-categories'), value: 'catOrder'},
									{label: __("Category Count", 'collapsing-categories'), value: 'catCount'},
								]}
								onChange={(newval) => { props.setAttributes( {catSort: newval })}}
							/>
							<SelectControl
								value={props.attributes.catSortOrder}
								options={[
									{label: __("Ascending", 'collapsing-categories'), value: 'ASC'},
									{label: __("Descending", 'collapsing-categories'), value: 'DESC'},
								]}
								onChange={(newval) => { props.setAttributes( {catSortOrder: newval })}}
							/>
						</PanelRow>
						<PanelRow>
							<SelectControl
								label={__("Sort posts by", 'collapsing-categories')}
								value={props.attributes.postSort}
								options={[
									{label: __("Title", 'collapsing-categories'), value: 'postTitle'},
									{label: __("Id", 'collapsing-categories'), value: 'postId'},
									{label: __("Comment count", 'collapsing-categories'), value: 'postComment'},
									{label: __("Post date", 'collapsing-categories'), value: 'postDate'},
									{label: __("Post order", 'collapsing-categories'), value: 'postSortOrder'},
								]}
								onChange={(newval) => { props.setAttributes( {postSort: newval })}}
							/>
							<SelectControl
								value={props.attributes.postSortOrder}
								options={[
									{label: __("Ascending", 'collapsing-categories'), value: 'ASC'},
									{label: __("Descending", 'collapsing-categories'), value: 'DESC'},
								]}
								onChange={(newval) => { props.setAttributes( {postSortOrder: newval })}}
							/>
						</PanelRow>
						<PanelRow>
							<SelectControl
								label={__("Expanding and collapsing characters")}
								value={props.attributes.expand}
								options={[
									{label: __("▶ ▼"), value: '0'},
									{label: __("+ —"), value: '1'},
									{label: __("[+] [—]"), value: '2'},
									{label: __("Images (1)"), value: '3'},
									{label: __("Images (2)"), value: '5'},
									{label: __("Custom"), value: '4'},
								]}
								onChange={(newval) => { props.setAttributes( {expand: newval })}}
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label={__("Custom expand", "collapsing-categories")}
								 autoComplete="off"
									value={ props.attributes.customExpand || '' }
									onChange={ ( nextValue ) => {
										props.setAttributes( { customExpand: nextValue } );
									} }
							/>
							<TextControl
								label={__("Custom collapse", "collapsing-categories")}
								 autoComplete="off"
									value={ props.attributes.customCollapse || '' }
									onChange={ ( nextValue ) => {
										props.setAttributes( { customCollapse: nextValue } );
									} }
							/>
						</PanelRow>
						<PanelRow>
							<SelectControl
								label={__("Taxonomy type", 'collapsing-categories')}
								value={props.attributes.taxonomy}
								options={getTaxonomyOptions()}
								onChange={(newval) => { props.setAttributes( {taxonomy: newval })}}
							/>
						</PanelRow>
						<PanelRow>
							<SelectControl
								label={__("Post type", 'collapsing-categories')}
								value={props.attributes.post_type}
								options={getPostTypeOptions()}
								onChange={(newval) => { props.setAttributes( {post_type: newval })}}
							/>
						</PanelRow>
						<PanelRow>
							<SelectControl
								label={__("Clicking on category/term name:")}
								value={props.attributes.linkToCat}
								options={[
									{label: __("Links to category archive"), value: '1'},
									{label: __("Expands to show sub-categories and/or posts"), value: '0'},
								]}
								onChange={(newval) => { props.setAttributes( {linkToCat: newval });console.log(props)}}
							/>
						</PanelRow>
						<PanelRow>
							<SelectControl
								label={__("Style")}
								value={props.attributes.style}
								options={[
									{label: __("Theme"), value: 'theme'},
									{label: __("Kubrick"), value: 'kubrick'},
									{label: __("Twenty Ten"), value: 'twentyten'},
									{label: __("block left"), value: 'block_left'},
									{label: __("block right"), value: 'block_right'},
									{label: __("No arrows"), value: 'no_arrows'},
								]}
								onChange={(newval) => { props.setAttributes( {style: newval })}}
							/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>
				<InspectorControls group="advanced">
						<PanelRow>
							<ToggleControl
								label={__("Accordion style (only one expanded at a time)")}
								checked={props.attributes.accordion}
								onChange={(newval) => { props.setAttributes( {accordion: newval })}}
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label={__("Link block title to url:", "collapsing-categories")}
								 autoComplete="off"
									value={ props.attributes.titleLink || '' }
									onChange={ ( nextValue ) => {
										props.setAttributes( { titleLink: nextValue } );
									} }
							/>
						</PanelRow>
						<PanelRow>
							<SelectControl
								label={__("Include RSS Link?")}
								value={props.attributes.catFeed}
								options={[
									{label: __("None"), value: 'none'},
									{label: __("Text"), value: 'text'},
									{label: __("Image"), value: 'image'},
								]}
								onChange={(newval) => { props.setAttributes( {catFeed: newval })}}
							/>
						</PanelRow>
						<PanelRow>
							<CheckboxControl
								label={__("Exclude post X from categories A and B when A or B is excluded")}
								checked={props.attributes.excludeAll}
								onChange={(newval) => { props.setAttributes( {excludeAll: newval })}}
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label={__("Exclude posts older than N days", "collapsing-categories")}
								 autoComplete="off"
									value={ props.attributes.olderThan || '' }
									onChange={ ( nextValue ) => {
										props.setAttributes( { olderThan: nextValue } );
									} }
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label={__("Truncate post title to N characters", "collapsing-categories")}
								 autoComplete="off"
									value={ props.attributes.postTitleLength || '' }
									onChange={ ( nextValue ) => {
										props.setAttributes( { postTitleLength: nextValue } );
									} }
							/>
						</PanelRow>
						<PanelRow>
							<SelectControl
								label={__("")}
								value={props.attributes.inExclude}
								options={[
									{label: __("Include"), value: 'include'},
									{label: __("Exclude"), value: 'exclude'},
								]}
								onChange={(newval) => { props.setAttributes( {inExclude: newval })}}
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label={__("these categories/terms (input slugs separated by commas)", "collapsing-categories")}
								 autoComplete="off"
									value={ props.attributes.inExcludeCats || '' }
									onChange={ ( nextValue ) => {
										props.setAttributes( { inExcludeCats: nextValue } );
									} }
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label={__("Auto-expand these categories (input slugs, separated by commas):", "collapsing-categories")}
								 autoComplete="off"
									value={ props.attributes.defaultExpand || '' }
									onChange={ ( nextValue ) => {
										props.setAttributes( { defaultExpand: nextValue } );
									} }
							/>
						</PanelRow>
						<PanelRow>
							<CheckboxControl
								label={__("Show Post Date")}
								checked={props.attributes.showPostDate}
								onChange={(newval) => { props.setAttributes( {showPostDate: newval })}}
							/>
						</PanelRow>
							<SelectControl
								value={props.attributes.postDateAppend}
								options={[
									{label: __( "Before title" ), value: 'before'},
									{label: __( "After title" ), value: 'after'},
								]}
								onChange={(newval) => { props.setAttributes( {postDateAppend: newval })}}
							/>
						<PanelRow>
							<TextControl
								label={__("Post Date Format", "collapsing-categories")}
								 autoComplete="off"
									value={ props.attributes.postDateFormat || '' }
									onChange={ ( nextValue ) => {
										props.setAttributes( { postDateFormat: nextValue } );
									} }
									help={ __( "<a href='http://php.net/date' target='_blank'> information about date formatting syntax</a>" ) }
							/>
						</PanelRow>
						<PanelRow>
							<CheckboxControl
								label={__("Show Top Level Categories")}
								checked={props.attributes.showTopLevel}
								onChange={(newval) => { props.setAttributes( {showTopLevel: newval })}}
							/>
						</PanelRow>
						<PanelRow>
							<CheckboxControl
								label={__("Show debugging information")}
								checked={props.attributes.debug}
								onChange={(newval) => { props.setAttributes( {debug: newval })}}
							/>
						</PanelRow>
				</InspectorControls>
			</div>
	);
}

export default withSelect( ( select ) => {
	return {
		taxonomies: select( coreStore ).getTaxonomies( { per_page: -1 } ),
		postTypes: select( coreStore ).getPostTypes( { per_page: -1 } ),
	};
} )( Edit );
