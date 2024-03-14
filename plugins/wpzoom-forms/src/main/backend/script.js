import { useBlockProps, InspectorControls, InnerBlocks, RichText } from '@wordpress/block-editor';
import { registerBlockType, updateCategory } from '@wordpress/blocks';
import { Card, CardBody, CardHeader, Disabled, Flex, FlexBlock, FlexItem, IconButton, PanelBody, RangeControl, SelectControl, TextControl, ToggleControl, ClipboardButton, Icon, __experimentalHStack as HStack } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { Fragment, useEffect, useState } from '@wordpress/element';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { __, setLocaleData, sprintf } from '@wordpress/i18n';
import { registerPlugin } from '@wordpress/plugins';
import { default as WelcomeGuide } from './welcome';
import { SortableContainer, SortableElement, SortableHandle } from 'react-sortable-hoc';
import { arrayMoveImmutable } from 'array-move';

updateCategory( 'wpzoom-forms', {
	icon: (
		<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 2C0 0.895431 0.895431 0 2 0H14C15.1046 0 16 0.895431 16 2V14C16 15.1046 15.1046 16 14 16H2C0.895431 16 0 15.1046 0 14V2Z" fill="#164777"/>
        <path d="M5.276 12.084H6.032L8.156 7.224L10.268 12.084H11.024L13.148 5.316H13.988V4.104H10.628V5.316H11.708L10.508 9.468L8.552 4.872H7.832L5.876 9.468L4.592 5.316H5.636V4.104H2.276V5.316H3.116L5.276 12.084Z" fill="white"/>
        </svg>
	)
} );

registerPlugin( 'wpzoom-forms-document-settings', {
	icon: '',
	render: props => {
		const postID = useSelect( select => select( 'core/editor' ).getCurrentPostId(), [] );
		const postType = useSelect( select => select( 'core/editor' ).getCurrentPostType(), [] );
		const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );
		const formMethod = meta[ '_form_method' ] || 'email';
		const formEmail = meta[ '_form_email' ] || ( typeof wpzf_formblock !== 'undefined' && 'admin_email' in wpzf_formblock ? wpzf_formblock.admin_email : '' );
		const [ hasCopiedShortcode, setHasCopiedShortcode ] = useState( false );
		const copyBtnStyle = { minHeight: '30px', height: 'auto', minWidth: 'fit-content', margin: '0px 0px 8px 0px' };

		if ( hasCopiedShortcode ) {
			copyBtnStyle.backgroundColor = 'green';
		}

		return <>
			<WelcomeGuide/>
			<PluginDocumentSettingPanel
				name="wpzoom-forms-document-settings"
				className="wpzoom-forms-document-settings"
				title={ __( 'Form Settings', 'wpzoom-forms' ) }
				opened={ true }
			>
				<SelectControl
					label={ __( 'Form Method', 'wpzoom-forms' ) }
					value={ formMethod }
					options={ [
						{
							label: __( 'Save to Database', 'wpzoom-forms' ),
							value: 'db'
						},
						{
							label: __( 'Email', 'wpzoom-forms' ),
							value: 'email'
						}
					] }
					onChange={ value => setMeta( { ...meta, '_form_method': value } ) }
				/>

				{ formMethod == 'email' && <TextControl
					type="email"
					label={ __( 'Send To', 'wpzoom-forms' ) }
					value={ formEmail }
					placeholder={ __( 'someone@somedomain.com', 'wpzoom-forms' ) }
					onChange={ value => setMeta( { ...meta, '_form_email': value } ) }
				/> }
			</PluginDocumentSettingPanel>

			<PluginDocumentSettingPanel
				name="wpzoom-forms-document-settings-details"
				className="wpzoom-forms-document-settings-details"
				title={ __( 'Form Details', 'wpzoom-forms' ) }
				opened={ true }
			>
				<HStack alignment="flex-end">
					<TextControl
						type="text"
						label={ __( 'Shortcode', 'wpzoom-forms' ) }
						value={ '[wpzf_form id="' + postID + '"]' }
						readOnly={ true }
					/>

					<ClipboardButton
						variant="primary"
						style={ copyBtnStyle }
						text={ '[wpzf_form id="' + postID + '"]' }
						label={ __( 'Copy shortcode', 'wpzoom-forms' ) }
						showTooltip={ true }
						onCopy={ () => setHasCopiedShortcode( true ) }
						onFinishCopy={ () => setHasCopiedShortcode( false ) }
					>
						<Icon
							icon={ hasCopiedShortcode
								? <svg viewBox="0 0 24 24"><path d="M16.7 7.1l-6.3 8.5-3.3-2.5-.9 1.2 4.5 3.4L17.9 8z"></path></svg>
								: <svg viewBox="0 0 24 24"><path d="M20.2 8v11c0 .7-.6 1.2-1.2 1.2H6v1.5h13c1.5 0 2.7-1.2 2.7-2.8V8zM18 16.4V4.6c0-.9-.7-1.6-1.6-1.6H4.6C3.7 3 3 3.7 3 4.6v11.8c0 .9.7 1.6 1.6 1.6h11.8c.9 0 1.6-.7 1.6-1.6zm-13.5 0V4.6c0-.1.1-.1.1-.1h11.8c.1 0 .1.1.1.1v11.8c0 .1-.1.1-.1.1H4.6l-.1-.1z"></path></svg>
							}
							size={ 16 }
						/>
					</ClipboardButton>
				</HStack>
			</PluginDocumentSettingPanel>
		</>;
	}
} );

setLocaleData( { 'Publish': [ __( 'Save', 'wpzoom-forms' ) ] } );

const DragHandle = SortableHandle( () => <IconButton
	icon="move"
	label={ __( 'Re-arrange Item', 'wpzoom-forms' ) }
	className="wpzoom-forms-move-button"
/> );

const SortableItem = SortableElement( ( { value, optsId, options, changeCallback, removeCallback } ) => <Fragment>
	<Flex>
		<FlexBlock>
			<TextControl
				value={ value }
				onChange={ val => changeCallback( val, optsId ) }
			/>
		</FlexBlock>

		{ options.length > 1 && <FlexItem>
			<DragHandle />

			<IconButton
				icon="no-alt"
				label={ __( 'Delete Item', 'wpzoom-forms' ) }
				onClick={ () => removeCallback( optsId ) }
			/>
		</FlexItem> }
	</Flex>
</Fragment> );

const SortableList = SortableContainer( ( { items, changeCallback, removeCallback } ) => <div>
	{ items.map( ( value, index ) => <SortableItem
		index={ index }
		optsId={ index }
		value={ value }
		options={ items }
		changeCallback={ changeCallback }
		removeCallback={ removeCallback }
	/> ) }
</div> );

registerBlockType( 'wpzoom-forms/form', {
	title:       __( 'Contact Form', 'wpzoom-blocks' ),
	description: __( 'Add a simple contact form', 'wpzoom-blocks' ),
	icon:        ( <svg width="40" height="40" viewBox="0 0 250 300" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M250 0H50V50H0V300H250V0Z" fill="#083EA7"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M100 50H200V100H100V50ZM100 150V100H50V250H100V200H150V150H100Z" fill="#1FDE91"/>
</svg> ),
	category:    'wpzoom-forms',
	supports:    { align: true, html: false },
	example:     {},
	edit:        () => {
		const blockProps = useBlockProps( { className: 'wpzoom-forms_form' } );

		return <div { ...blockProps }>
			<InnerBlocks
				allowedBlocks={ [
					'wpzoom-forms/text-plain-field',
					'wpzoom-forms/text-name-field',
					'wpzoom-forms/text-email-field',
					'wpzoom-forms/text-website-field',
					'wpzoom-forms/text-phone-field',
					'wpzoom-forms/textarea-field',
					'wpzoom-forms/select-field',
					'wpzoom-forms/checkbox-field',
					'wpzoom-forms/radio-field',
					'wpzoom-forms/label-field',
					'wpzoom-forms/submit-field'
				] }
				template={ [
					[
						'core/group',
						{
							'tagName': 'div'
						},
						[
							[
								'core/columns',
								{
									'isStackedOnMobile': true
								},
								[
									[
										'core/column',
										{
											'width': '100%'
										},
										[
											[
												'wpzoom-forms/text-name-field',
												{
													'id':        'input_name',
													'name':      __( 'Name', 'wpzoom-blocks' ),
													'type':      'text',
													'showLabel': true,
													'label':     __( 'Name', 'wpzoom-blocks' ),
													'required':  true,
													'replyto':   true,
													'className': 'fullwidth'
												}
											],
											[
												'wpzoom-forms/text-email-field',
												{
													'id':        'input_email',
													'name':      __( 'Email', 'wpzoom-blocks' ),
													'type':      'email',
													'showLabel': true,
													'label':     __( 'Email', 'wpzoom-blocks' ),
													'required':  true,
													'replyto':   true,
													'className': 'fullwidth'
												}
											],
											[
												'wpzoom-forms/text-plain-field',
												{
													'id':        'input_subject',
													'name':      __( 'Subject', 'wpzoom-blocks' ),
													'type':      'text',
													'showLabel': true,
													'label':     __( 'Subject', 'wpzoom-blocks' ),
													'required':  true,
													'subject':   true,
													'className': 'fullwidth'
												}
											],
											[
												'wpzoom-forms/textarea-field',
												{
													'id':        'input_message',
													'name':      __( 'Message', 'wpzoom-blocks' ),
													'cols':      '55',
													'rows':      '10',
													'showLabel': true,
													'label':     __( 'Message', 'wpzoom-blocks' ),
													'required':  true,
													'className': 'fullwidth'
												}
											]
										]
									]
								]
							],
							[
								'core/columns',
								{
									'isStackedOnMobile': true
								},
								[
									[
										'core/column',
										{
											'width': '30%'
										},
										[
											[
												'wpzoom-forms/submit-field',
												{
													'id':   'input_submit',
													'name': __( 'Submit', 'wpzoom-blocks' )
												}
											]
										]
									],
									[
										'core/column',
										{
											'width': '70%'
										},
										[
											[
												'core/paragraph',
												{
													'align':   'right',
													'content': __( 'Fields marked with <strong class="has-accent-color has-text-color">*</strong> are required.', 'wpzoom-blocks' ),
													'dropCap': false,
													'style':   {
														'typography': {
															'fontSize': 16
														}
													}
												}
											]
										]
									]
								]
							]
						]
					]
				] }
			/>
		</div>;
	},
	save:        () => {
		const blockProps = useBlockProps.save();

		return <div { ...blockProps }>
			<InnerBlocks.Content />
		</div>;
	}
} );