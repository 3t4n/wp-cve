import { useBlockProps, RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

const Save = ( { attributes } ) => {
	const blockProps = useBlockProps.save();
	const { id, name, forInput, required } = attributes;

	return <>
		<RichText.Content
			tagName="label"
			value={ name }
			htmlFor={ forInput }
			data-required={ !! required }
			{ ...blockProps }
		/>

		{ required && (
			<sup className="wp-block-wpzoom-forms-required">{ __( '*', 'wpzoom-forms' ) }</sup>
		) }
	</>;
};

export default Save;