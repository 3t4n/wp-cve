/**
 * WordPress dependencies
 */

const {
    __,
} = wp.i18n;

const {
	Component,
} = wp.element;

const {
	Icon,
	Toolbar,
	DropdownMenu,
} = wp.components;


/**
 * Component
 */
export default class ExtendAlignmentToolbar extends Component {
	constructor() {
		super( ...arguments );
	}

	render() {
		const DEFAULT_ALIGNMENT_CONTROLS = [
			{
				icon: <Icon icon="editor-textcolor" />,
				title: __( 'Default Align Text' ),
				align: 'default',
			},
			{
				icon: <Icon icon="editor-alignleft" />,
				title: __( 'Align Text Left' ),
				align: 'left',
			},
			{
				icon: <Icon icon="editor-aligncenter" />,
				title: __( 'Align Text Center' ),
				align: 'center',
			},
			{
				icon: <Icon icon="editor-alignright" />,
				title: __( 'Align Text Right' ),
				align: 'right',
			},
		];

		const {
			value,
			onChange,
			alignmentControls = DEFAULT_ALIGNMENT_CONTROLS,
		} = this.props;

		function applyOrUnset( align ) {
			return () => onChange( value === align ? undefined : align );
		}

		const activeAlignment = alignmentControls.find( function( control ) {
			return ( value === control.align || ( 'default' === control.align && ( 'none' === value || 'default' === value || ! value ) ) );
		} );

		return (
			<Toolbar>
				<DropdownMenu
					icon={ activeAlignment ? activeAlignment.icon : <Icon icon="editor-textcolor" /> }
					label={ activeAlignment ? activeAlignment.title : __( 'Change text alignment' ) }
					controls={ alignmentControls.map( ( control ) => {
						const { align } = control;

						const isActive = ( value === align || ( 'default' === align && ( 'none' === value || 'default' === value || ! value ) ) );

						return {
							...control,
							isActive,
							onClick: applyOrUnset( align ),
						};
					} ) }
				/>
			</Toolbar>
		);
	}
}
