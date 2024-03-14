/**
 * WordPress dependencies
 */
const {
	PlainText,
} = wp.editor;

const {
	Dashicon,
	ServerSideRender,
} = wp.components;

const {
	Fragment,
} = wp.element;

const {
	__,
} = wp.i18n;

const edit = ( { attributes, setAttributes, instanceId } ) => {
	const inputId = `blocks-nutrifox-input-${ instanceId }`;
	const placeholderStyle = {
		display: 'flex',
		flexDirection: 'row',
		padding: '14px',
		backgroundColor: '#f8f9f9',
		fontSize: '13px',
		fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
	};
	const labelStyle = {
		display: 'flex',
		alignItems: 'center',
		whiteSpace: 'nowrap',
		marginRight: '8px',
		fontWeight: 600,
		flexShrink: 0,
	};
	const spanStyle = {
		marginLeft: '8px',
	};
	return (
		<Fragment>
			<div className="wp-nutrifox-shortcode" style={ placeholderStyle }>
				<label htmlFor={ inputId } style={ labelStyle }>
					<Dashicon icon="carrot" />
					<span style={ spanStyle }>{ __( 'Nutrifox Label' ) }</span>
				</label>
				<PlainText
					id={ inputId }
					className="input-control"
					value={ attributes.url }
					placeholder={ __( 'Paste Nutrifox URL or ID here…' ) }
					onChange={ ( url ) => setAttributes( { url } ) }
				/>
			</div>
			{ attributes.url &&
				<ServerSideRender block="nutrifox/nutrifox" attributes={ attributes } />
			}
		</Fragment>
	);
};

// Renders dynamically on the frontend.
const save = () => {
	return null;
};

const blockRegistration = {
	title: __( 'Nutrifox Label', 'nutrifox' ),
	icon: 'carrot',
	category: 'common',
	html: false,
	attributes: {
		id: {
			type: 'number',
		},
		url: {
			type: 'string',
		},
	},
	edit,
	save,
	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'nutrifox',
				attributes: {
					id: {
						type: 'number',
						shortcode: ( props ) => {
							if ( typeof props.named.id === 'undefined' ) {
								return 0;
							}
							return parseInt( props.named.id );
						},
					},
				},
			},
		],
	},
};

export default blockRegistration;
