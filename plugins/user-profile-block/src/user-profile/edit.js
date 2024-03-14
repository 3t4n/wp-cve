import * as React from 'react';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import {
	Disabled,
	SelectControl,
	PanelBody,
	Placeholder,
} from '@wordpress/components';

import { ServerSideRender } from '@wordpress/editor';
import metadata from './block.json';

const Edit = ( { className, attributes, setAttributes } ) => {
	const { userId } = attributes;
	const authors = useSelect( ( select ) => {
		const users = select( 'core' ).getEntityRecords( 'root', 'user' );
		//console.log(select( 'core' ).getAuthors())
		return users || [];
	}, [] );
	const userControl = (
		<SelectControl
			label={ 'User' }
			onChange={ ( id ) => {
				setAttributes( { userId: Number( id ) } );
			} }
			value={ `${ userId }` }
			options={ [
				{
					value: '0',
					label: __( 'None' ),
				},
				...authors.map( ( { id, name, slug } ) => ( {
					value: `${ id }`,
					label: `${ name } (${ slug })`,
				} ) ),
			] }
		/>
	);
	return (
		<>
			<InspectorControls>
				<PanelBody title={ 'User profile settings' }>
					{ userControl }
				</PanelBody>
			</InspectorControls>
			{ userId ? (
				<Disabled>
					<ServerSideRender
						className={ className }
						block={ metadata.name }
						attributes={ attributes }
					/>
				</Disabled>
			) : (
				<Placeholder
					icon="admin-users"
					label="User Profile"
					instructions="Select user."
				>
					{ userControl }
				</Placeholder>
			) }
		</>
	);
};
export default Edit;
