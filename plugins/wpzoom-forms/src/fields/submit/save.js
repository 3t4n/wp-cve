import { useBlockProps } from '@wordpress/block-editor';

const Save = ( { attributes } ) => {
	const blockProps = useBlockProps.save();
	const { id, name } = attributes;

	return <input
		type="submit"
		id={ id }
		value={ name }
		{ ...blockProps }
	/>;
};

export default Save;