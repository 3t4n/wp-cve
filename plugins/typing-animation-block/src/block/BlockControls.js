import ElementToolbar from './ElementToolbar';

const { BlockControls } = wp.blockEditor;

const MyBlockControls = ( { attributes, setAttributes } ) => {
	return <BlockControls>
		<ElementToolbar
			selectedElement={ attributes.element }
			onChange={ ( element ) => setAttributes( { element } ) }
		/>
	</BlockControls>;
};

export default MyBlockControls;
