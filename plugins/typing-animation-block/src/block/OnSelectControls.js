const { TextControl } = wp.components;

const OnSelectControl = ( { attributes, setAttributes, isSelected } ) => {
	if ( ! isSelected ) {
		return null;
	}
	return <div style={ { padding: '0.25rem' } }><TextControl
		label="Text to animate"
		value={ attributes.strings.join( '|' ) }
		onChange={ ( text ) => setAttributes( { strings: text.split( '|' ) } ) }
	/></div>;
};

export default OnSelectControl;
