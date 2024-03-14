import { useBlockProps, RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

const Save = ( { attributes } ) => {
	const blockProps = useBlockProps.save();
	const { id, name, options, defaultValue, label, showLabel, multiple, required } = attributes;

	return <>
		{ showLabel && <label htmlFor={ id }>
			<RichText.Content
				tagName="label"
				value={ label }
				htmlFor={ id }
			/>
			{ required && <sup className="wp-block-wpzoom-forms-required">{ __( '*', 'wpzoom-forms' ) }</sup> }
		</label> }

		<select
			name={ id }
			id={ id }
			required={ !! required }
			multiple={ !! multiple }
			defaultValue={ defaultValue }
			{ ...blockProps }
		>
			{ options.map( ( option, index ) => <option key={ index } value={ option }>{ option }</option> ) }
		</select>
	</>;
};

export default Save;