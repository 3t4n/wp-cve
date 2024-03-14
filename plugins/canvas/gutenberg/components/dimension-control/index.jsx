/**
 * WordPress dependencies
 */
const {
    __,
} = wp.i18n;

const {
    Component,
    Fragment,
} = wp.element;

const {
    TextControl,
    Notice,
} = wp.components;

/**
 * Component
 */
export default class ComponentDimensionControl extends Component {
	constructor() {
        super( ...arguments );

        this.isValidValue = this.isValidValue.bind( this );
    }

    /**
     * Check if value valid.
     *
     * @param {Mixed} value value to check.
     * @returns {Boolean}
     */
    isValidValue( value ) {
        const validUnits = [ 'fr', 'rem', 'em', 'ex', '%', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ch', 'vh', 'vw', 'vmin', 'vmax' ];

        // Whitelist values.
        if ( ! value || '' === value || 0 === value || '0' === value || 'auto' === value || 'inherit' === value || 'initial' === value ) {
            return true;
        }

        // Skip checking if calc().
        if ( 0 <= value.indexOf( 'calc(' ) && 0 <= value.indexOf( ')' ) ) {
            return true;
        }

        // Get the numeric value.
        const numericValue = parseFloat( value );

        // Get the unit
        const unit = value.replace( numericValue, '' );

        // Allow unitless.
        if ( ! unit ) {
            return true;
        }

        // Check the validity of the numeric value and units.
        return ( ! isNaN( numericValue ) && -1 !== validUnits.indexOf( unit ) );
    }

    render() {
        const {
            value,
            label,
            help,
            onChange,
        } = this.props;

		return (
            <Fragment>
                <TextControl
                    label={ label }
                    help={ help }
                    value={ value }
                    onChange={ onChange }
                />
                { ! this.isValidValue( value ) ? (
                    <Notice status="warning" isDismissible={ false }>
                        { __( 'Invalid dimension value.'  ) }
                    </Notice>
                ) : '' }
            </Fragment>
		);
    }
}
