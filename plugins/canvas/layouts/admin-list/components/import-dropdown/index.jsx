/**
 * External dependencies
 */
const { flow } = window.lodash;

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { Dropdown, Button } = wp.components;

/**
 * Internal dependencies
 */
import './style.scss';
import ImportForm from '../import-form';

function ImportDropdown( { onUpload } ) {
	return (
		<Dropdown
			position="bottom right"
			contentClassName="list-layout-blocks-import-dropdown__content"
			renderToggle={ ( { isOpen, onToggle } ) => (
				<Button
					type="button"
					aria-expanded={ isOpen }
					onClick={ onToggle }
					isPrimary
				>
					{ __( 'Import from JSON', 'canvas' ) }
				</Button>
			) }
			renderContent={ ( { onClose } ) => (
				<ImportForm onUpload={ flow( onClose, onUpload ) } />
			) }
		/>
	);
}

export default ImportDropdown;
