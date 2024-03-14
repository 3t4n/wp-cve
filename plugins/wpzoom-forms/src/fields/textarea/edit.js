import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { Fragment, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { PanelBody, TextControl, ToggleControl, RangeControl } from '@wordpress/components';

const Edit = props => {
	const blockProps = useBlockProps();
	const { attributes, setAttributes, clientId } = props;
	const { id, name, cols, rows, placeholder, label, showLabel, required } = attributes;

	useEffect( () => {
		if ( ! id ) {
			setAttributes( { id: 'input_' + clientId.substr( 0, 8 ) } );
		}
	}, [] );

	return <>
		<InspectorControls>
			<PanelBody title={ __( 'Options', 'wpzoom-forms' ) }>
				<TextControl
					label={ __( 'Name', 'wpzoom-forms' ) }
					value={ name }
					placeholder={ __( 'e.g. My Textarea Field', 'wpzoom-forms' ) }
					onChange={ value => setAttributes( { name: value } ) }
				/>

				<RangeControl
					label={ __( 'Columns', 'wpzoom-blocks' ) }
					max={ 500 }
					min={ 1 }
					value={ Number( cols ) }
					onChange={ value => setAttributes( { cols: value } ) }
				/>

				<RangeControl
					label={ __( 'Rows', 'wpzoom-blocks' ) }
					max={ 100 }
					min={ 1 }
					value={ Number( rows ) }
					onChange={ value => setAttributes( { rows: value } ) }
				/>

				<TextControl
					label={ __( 'Placeholder', 'wpzoom-forms' ) }
					value={ placeholder }
					onChange={ value => setAttributes( { placeholder: value } ) }
				/>

				<ToggleControl
					label={ __( 'Show Label', 'wpzoom-forms' ) }
					checked={ !! showLabel }
					onChange={ value => setAttributes( { showLabel: !! value } ) }
				/>

				{ showLabel && <TextControl
					label={ __( 'Label', 'wpzoom-forms' ) }
					value={ label }
					onChange={ value => setAttributes( { label: value } ) }
				/> }

				<ToggleControl
					label={ __( 'Required', 'wpzoom-forms' ) }
					checked={ !! required }
					onChange={ value => setAttributes( { required: !! value } ) }
				/>
			</PanelBody>
		</InspectorControls>

		<Fragment>
			{ showLabel && <label htmlFor={ id }>
				<RichText
					tagName="label"
					placeholder={ __( 'Label', 'wpzoom-forms' ) }
					value={ label }
					htmlFor={ id }
					onChange={ value => setAttributes( { label: value } ) }
				/>
				{ required && <sup className="wp-block-wpzoom-forms-required">{ __( '*', 'wpzoom-forms' ) }</sup> }
			</label> }

			<textarea
				name={ id }
				id={ id }
				cols={ cols }
				rows={ rows }
				placeholder={ placeholder }
				required={ !! required }
				{ ...blockProps }
			></textarea>
		</Fragment>
	</>;
};

export default Edit;