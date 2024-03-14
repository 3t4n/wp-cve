/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

const {
	Component,
	Fragment,
} = wp.element;

const {
	PanelBody,
	SelectControl,
	Placeholder,
} = wp.components;

const {
	InspectorControls,
} = wp.blockEditor;

const {
	sidebars,
} = window.canvasWidgetizedBlock;


/**
 * Component
 */
export default class WidgetizedAreaBlockEdit extends Component {
	constructor() {
		super( ...arguments );
	}

	render() {
		const {
			attributes,
			setAttributes,
		} = this.props;

		const {
			area,
		} = attributes;

		const options = [
			{
				label: __( 'Select Widgetized Area' ),
				value: '',
			},
		];

		if ( sidebars && Object.keys( sidebars ).length ) {
			Object.keys( sidebars ).forEach( ( name ) => {
				options.push( {
					label: sidebars[ name ].name,
					value: sidebars[ name ].id,
				} );
			} );
		}

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody>
						<SelectControl
							label={ __( 'Widgetized Area' ) }
							value={ area }
							options={ options }
							onChange={ ( val ) => { setAttributes( { area: val } ) } }
						/>
					</PanelBody>
				</InspectorControls>
				<Placeholder>
					<SelectControl
						label={ __( 'Widgetized Area' ) }
						value={ area }
						options={ options }
						onChange={ ( val ) => { setAttributes( { area: val } ) } }
					/>
				</Placeholder>
			</Fragment>
		);
	}
}
