import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { Fragment, useEffect } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
import { PanelBody, TextControl, ToggleControl, SelectControl, Card, CardBody, CardHeader, IconButton, Flex, FlexBlock, FlexItem } from '@wordpress/components';
import { SortableContainer, SortableElement, SortableHandle } from 'react-sortable-hoc';
import { arrayMoveImmutable } from 'array-move';

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

const Edit = props => {
	const blockProps = useBlockProps();
	const { attributes, setAttributes, clientId } = props;
	const { id, name, options, defaultValue, label, showLabel, multiple, required } = attributes;

	const optionAdd = () => {
		const opts = [ ...options ];
		opts.push( sprintf( __( 'Item #%s', 'wpzoom-forms' ), options.length + 1 ) );
		setAttributes( { options: opts } );
	};

	const optionRemove = ( index ) => {
		const opts = [ ...options ];
		opts.splice( index, 1 );
		setAttributes( { options: opts } );
	};

	const optionChange = ( name, index ) => {
		const opts = [ ...options ];
		opts[ index ] = name;
		setAttributes( { options: opts } );
	};

	const optionsSort = ( oldIndex, newIndex ) => {
		const sorted = arrayMoveImmutable( options, oldIndex, newIndex );
		setAttributes( { options: sorted } );
	};

	useEffect( () => {
		if ( ! id ) {
			setAttributes( { id: 'input_' + clientId.substr( 0, 8 ) } );
		}
	}, [] );

	return <>
		<InspectorControls>
			<PanelBody title={ __( 'Options', 'wpzoom-forms' ) }>
				<TextControl
					label={ __( 'Name', 'wpzoom-forms' ) }
					value={ name }
					placeholder={ __( 'e.g. My Dropdown Select Field', 'wpzoom-forms' ) }
					onChange={ value => setAttributes( { name: value } ) }
				/>

				<Card size="small">
					<CardHeader>
						{ __( 'Items', 'wpzoom-forms' ) }

						<IconButton
							icon="insert"
							label={ __( 'Add Item', 'wpzoom-forms' ) }
							onClick={ optionAdd.bind( this ) }
						/>
					</CardHeader>
					<CardBody>
						<SortableList
							items={ options }
							changeCallback={ optionChange }
							removeCallback={ optionRemove }
							lockAxis="y"
							useDragHandle={ true }
							onSortEnd={ ( { oldIndex, newIndex } ) => optionsSort( oldIndex, newIndex ) }
						/>
					</CardBody>
				</Card>

				<SelectControl
					label={ __( 'Default Value', 'wpzoom-forms' ) }
					value={ defaultValue }
					options={ options.map( ( option, index ) => ( { label: option, value: option } ) ) }
					onChange={ value => setAttributes( { defaultValue: value } ) }
				/>

				<ToggleControl
					label={ __( 'Show Label', 'wpzoom-forms' ) }
					checked={ !! showLabel }
					onChange={ value => setAttributes( { showLabel: !! value } ) }
				/>

				{ showLabel && <TextControl
					label={ __( 'Label', 'wpzoom-forms' ) }
					value={ label }
					onChange={ value => setAttributes( { label: value } ) }
				/> }

				<ToggleControl
					label={ __( 'Allow Multiple Selections', 'wpzoom-forms' ) }
					checked={ !! multiple }
					onChange={ value => setAttributes( { multiple: !! value } ) }
				/>

				<ToggleControl
					label={ __( 'Required', 'wpzoom-forms' ) }
					checked={ !! required }
					onChange={ value => setAttributes( { required: !! value } ) }
				/>
			</PanelBody>
		</InspectorControls>

		<Fragment>
			{ showLabel && <label htmlFor={ id }>
				<RichText
					tagName="label"
					placeholder={ __( 'Label', 'wpzoom-forms' ) }
					value={ label }
					htmlFor={ id }
					onChange={ value => setAttributes( { label: value } ) }
				/>
				{ required && <sup className="wp-block-wpzoom-forms-required">{ __( '*', 'wpzoom-forms' ) }</sup> }
			</label> }

			<select
				name={ id }
				id={ id }
				required={ !! required }
				multiple={ !! multiple }
				defaultValue={ defaultValue }
				{ ...blockProps }
			>
				{ options.map( ( option, index ) => <option key={ index } value={ option }>{ option }</option> ) }
			</select>
		</Fragment>
	</>;
};

export default Edit;