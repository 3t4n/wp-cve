import OnSelectControl from './OnSelectControls';
import InspectorControls from './InspectorControls';
import BlockControls from './BlockControls';
import Typed from './Typed';

const Edit = ( props ) => (
	<div className={ props.className }>
		<Typed { ...props.attributes } />
		<OnSelectControl { ...props } />
		<InspectorControls { ...props } />
		<BlockControls { ...props } />
	</div>
);

export default Edit;
