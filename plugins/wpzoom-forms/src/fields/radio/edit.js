import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { Fragment, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { PanelBody, TextControl, SelectControl, ToggleControl, Flex, FlexBlock, FlexItem, Card, CardBody, CardHeader, IconButton } from '@wordpress/components';

const Edit = props => {
	const blockProps = useBlockProps( { className: 'unstyled-list' } );
	const { attributes, setAttributes, clientId } = props;
	const { id, name, options, defaultValue, label, showLabel, required } = attributes;

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
					placeholder={ __( 'e.g. My Radio Field', 'wpzoom-forms' ) }
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
						{ options.map( ( option, index ) => (
							<Fragment key={ index }>
								<Flex>
									<FlexBlock>
										<TextControl
											value={ options[ index ] }
											onChange={ value => optionChange( value, index ) }
										/>
									</FlexBlock>

									{ options.length > 1 && <FlexItem>
										<IconButton
											icon="no-alt"
											label={ __( 'Delete Item', 'wpzoom-forms' ) }
											onClick={ () => optionRemove( index ) }
										/>
									</FlexItem> }
								</Flex>
							</Fragment>
						) ) }
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

			<ul { ...blockProps }>
				{ options.map( ( option, index ) =>
					<li key={ index }>
						<label>
							<input
								type="radio"
								name={ id }
								id={ id }
								value={ option }
								checked={ option == defaultValue }
								onChange={ e => {} }
								required={ !! required }
							/>
							{ option }
						</label>
					</li>
				) }
			</ul>
		</Fragment>
	</>;
};

export default Edit;