import { useBlockProps } from '@wordpress/block-editor';

export default function save( props ) {
	const { attributes } = props;

	return (
		<div { ...useBlockProps.save() }>
			{ attributes.sliderId !== -1 && `[sliderpro id="${ attributes.sliderId }"]` }
		</div>
	);
}
