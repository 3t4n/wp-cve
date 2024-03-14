import { InspectorControls } from '@wordpress/block-editor';
import { registerBlockType, updateCategory } from '@wordpress/blocks';
import { Disabled, PanelBody, Placeholder, Button, __experimentalHStack as HStack } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

import SearchableSelectControl from './searchable-select';

const wpzoomFormsIcon = (
<svg width="40" height="40" viewBox="0 0 250 300" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M250 0H50V50H0V300H250V0Z" fill="#083EA7"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M100 50H200V100H100V50ZM100 150V100H50V250H100V200H150V150H100Z" fill="#1FDE91"/>
</svg>
);

updateCategory( 'wpzoom-blocks', {
	icon: (
		<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 2C0 0.895431 0.895431 0 2 0H14C15.1046 0 16 0.895431 16 2V14C16 15.1046 15.1046 16 14 16H2C0.895431 16 0 15.1046 0 14V2Z" fill="#164777"/>
        <path d="M5.276 12.084H6.032L8.156 7.224L10.268 12.084H11.024L13.148 5.316H13.988V4.104H10.628V5.316H11.708L10.508 9.468L8.552 4.872H7.832L5.876 9.468L4.592 5.316H5.636V4.104H2.276V5.316H3.116L5.276 12.084Z" fill="white"/>
        </svg>

	)
} );

registerBlockType( 'wpzoom-forms/form-block', {
	title:       __( 'Contact Form by WPZOOM', 'wpzoom-blocks' ),
	description: __( 'A contact form block for accepting submissions from users.', 'wpzoom-blocks' ),
	icon:        wpzoomFormsIcon,
	category:    'wpzoom-blocks',
	supports:    { align: true, html: false },
	attributes:  {
		formId: {
			type:    'string',
			default: '-1'
		},
		align: {
			type: 'string',
			default: 'none',
		}
	},
	example:     {},
	edit:        props => {
		const { attributes, setAttributes } = props;
		const { formId, align } = attributes;
		const _formId = formId && String( formId ).trim() != '' ? String( formId ) : '-1';
		const posts = useSelect( select => select( 'core' ).getEntityRecords( 'postType', 'wpzf-form', { order: 'asc', orderby: 'title', per_page: -1 } ), [] );
		const forms = posts && posts.length > 0 ? posts.map( x => { return { key: String( x.id ), name: x.title.raw } } ) : [];
		const theForm = forms.find( x => x.key == _formId );

		const formSelect = (
			<SearchableSelectControl
				label={ __( 'Form', 'wpzoom-forms' ) }
				selectPlaceholder={ forms.length < 1 ? __( 'No forms exist...', 'wpzoom-forms' ) : __( 'Select a form...', 'wpzoom-forms' ) }
				searchPlaceholder={ __( 'Search...', 'wpzoom-forms' ) }
				noResultsLabel={ __( 'Nothing found...', 'wpzoom-forms' ) }
				options={ forms }
				value={ typeof theForm !== 'undefined' ? theForm : '' }
				onChange={ ( value ) => setAttributes( { formId: String( value.selectedItem.key ) } ) }
			/>
		);

		const formEditLink = (
			<HStack
				expanded={ true }
				alignment="right"
			>
				<Button
					variant="link"
					text={ __( 'Edit form', 'wpzoom-forms' ) }
					icon={ <svg viewBox="0 0 24 24"><path d="M20.1 5.1L16.9 2 6.2 12.7l-1.3 4.4 4.5-1.3L20.1 5.1zM4 20.8h8v-1.5H4v1.5z"></path></svg> }
					iconSize={ 20 }
					href={ wpzf_formblock.admin_url + 'post.php?post=' + _formId + '&action=edit' }
					style={ { textDecoration: 'none' } }
				/>
			</HStack>
		);

		return (
			<>
				<InspectorControls>
					<PanelBody title={ __( 'Options', 'wpzoom-forms' ) }>
						{ forms.length > 0 ? formSelect : <Disabled>{ formSelect }</Disabled> }
						{ '-1' !== _formId && formEditLink }
					</PanelBody>
				</InspectorControls>

				<Fragment>
					{ '-1' != _formId
						? <ServerSideRender
							block="wpzoom-forms/form-block"
							attributes={ attributes }
						  />
						: <Placeholder
							icon={ wpzoomFormsIcon }
							label={ __( 'Contact Form by WPZOOM', 'wpzoom-forms' ) }
						  >
							{ forms.length > 0 ? formSelect : <Disabled>{ formSelect }</Disabled> }
						  </Placeholder>
					}
				</Fragment>
			</>
		);
	}
} );