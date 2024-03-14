/**
 * WordPress dependencies
 */
const {
    addFilter,
} = wp.hooks;

/**
 * Internal dependencies
 */
import ColumnBlockEdit from './edit.jsx';
import ColumnBlockSave from './save.jsx';
import changeColumnSize from './change-column-size';

const CNVS_PREVENT_UPDATE = 'CNVS_PREVENT_UPDATE';

/**
 * Custom block Edit output for Column block.
 *
 * @param {JSX} edit Original block edit.
 * @param {Object} blockProps Block data.
 *
 * @return {JSX} Block edit.
 */
function editRender( edit, blockProps ) {
	if ( 'canvas/column' === blockProps.name ) {
		return (
			<ColumnBlockEdit { ...blockProps } />
		);
	}

    return edit;
}

/**
 * Custom block register data for Column block.
 *
 * @param {Object} blockData Block data.
 *
 * @return {Object} Block data.
 */
function registerData( blockData ) {
	if ( 'canvas/column' === blockData.name ) {
		blockData.save = ColumnBlockSave;
	}

    return blockData;
}

/**
 * Compensate adjacent columns, when changing size directly in field.
 *
 * @param {Mixed} val Attribute value.
 * @param {String} name Attribute name.
 * @param {Object} blockProps Block data.
 *
 * @return {Mixed} Attribute value.
 */
function onFieldChange( val, name, blockProps ) {
	if ( 'canvas/column' === blockProps.name && 'size' === name ) {
		changeColumnSize( blockProps.clientId, val, true );
		return CNVS_PREVENT_UPDATE;
	}

    return val;
}

addFilter( 'canvas.customBlock.editRender', 'canvas/column/editRender', editRender );
addFilter( 'canvas.customBlock.registerData', 'canvas/column/registerData', registerData );
addFilter( 'canvas.customBlock.onFieldChange', 'canvas/column/changeColumnSize', onFieldChange );
