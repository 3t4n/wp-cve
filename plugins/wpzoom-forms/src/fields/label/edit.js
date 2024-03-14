import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { Fragment, useEffect } from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { PanelBody, ToggleControl, SelectControl } from '@wordpress/components';

const Edit = props => {
	const blockProps = useBlockProps();
	const { attributes, setAttributes, clientId } = props;
	const { id, name, forInput, required } = attributes;

	useEffect( () => {
		if ( ! id ) {
			setAttributes( { id: 'input_' + clientId.substr( 0, 8 ) } );
		}
	}, [] );

	const wpzoomFormBlocks = blocks => {
		let result = [];

		blocks.forEach( block => {
			if ( block.name.startsWith( 'wpzoom-forms/' ) && ! block.name.endsWith( 'label-field' ) ) {
				result.push( { value: block.attributes.id, label: block.attributes.name } );
			}

			if ( block.innerBlocks ) {
				result = [ ...result, ...wpzoomFormBlocks( block.innerBlocks ) ];
			}
		} );

		return result;
	};

	const allBlocks = useSelect( select => select( 'core/block-editor' ).getBlocks(), [] );
	const allwpzoomFormBlocks = allBlocks && allBlocks.length > 0 ? wpzoomFormBlocks( allBlocks ) : [];
	const label = allwpzoomFormBlocks?.find( x => x.value == forInput )?.label;

	const inputSelect = <>
		<SelectControl
			label={ __( 'For Input', 'wpzoom-forms' ) }
			value={ forInput }
			options={ allwpzoomFormBlocks.length > 0 ? allwpzoomFormBlocks : [ { value: '-1', label: __( 'No inputs found...', 'wpzoom-forms' ) } ] }
			onChange={ value => setAttributes( { forInput: value } ) }
		/>
		<ToggleControl
			label={ __( 'Required', 'wpzoom-forms' ) }
			checked={ !! required }
			onChange={ value => setAttributes( { required: !! value } ) }
		/>
	</>;

	return <>
		<InspectorControls>
			<PanelBody title={ __( 'Options', 'wpzoom-forms' ) }>
				{ allwpzoomFormBlocks.length > 0 ? inputSelect : <Disabled>{ inputSelect }</Disabled> }
			</PanelBody>
		</InspectorControls>

		<Fragment>
			<RichText
				tagName="label"
				placeholder={ __( 'Label', 'wpzoom-forms' ) }
				value={ name }
				htmlFor={ forInput }
				onChange={ value => setAttributes( { name: value } ) }
				data-required={ !! required }
				{ ...blockProps }
			/>

			{ required && (
				<sup className="wp-block-wpzoom-forms-required">{ __( '*', 'wpzoom-forms' ) }</sup>
			) }
		</Fragment>
	</>;
};

export default Edit;