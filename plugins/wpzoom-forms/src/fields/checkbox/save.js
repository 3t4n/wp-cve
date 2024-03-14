import { useBlockProps, RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

const Save = ( { attributes } ) => {
	const blockProps = useBlockProps.save();
	const { id, name, defaultValue, label, showLabel, required } = attributes;

	return <>
		<input
			type="checkbox"
			name={ id }
			id={ id }
			checked={ true == defaultValue }
			onChange={ e => {} }
			required={ !! required }
			{ ...blockProps }
		/>

		{ showLabel && <label htmlFor={ id }>
			<RichText.Content
				tagName="label"
				value={ label }
				htmlFor={ id }
			/>
			{ required && <sup className="wp-block-wpzoom-forms-required">{ __( '*', 'wpzoom-forms' ) }</sup> }
		</label> }
	</>;
};

export default Save;