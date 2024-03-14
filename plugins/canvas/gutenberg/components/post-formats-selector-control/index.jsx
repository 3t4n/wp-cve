/**
 * External dependencies
 */
import ReactSelect from 'react-select';

/**
 * WordPress dependencies
 */
const {
    Component,
} = wp.element;

const {
    withSelect,
} = wp.data;

const {
    BaseControl,
} = wp.components;

/**
 * Component
 */
class ComponentPostFormatsSelectorControl extends Component {
	constructor() {
        super( ...arguments );
    }

    render() {
        const {
            value,
            label,
            help,
            onChange,
            postFormats,
        } = this.props;

        if ( ! postFormats.length ) {
            return '';
        }

		return (
            <BaseControl
                label={ label }
                help={ help }
            >
                <ReactSelect
                    options={ postFormats }
                    value={ ( () => {
                        let result = value ? value.split(',') : [];
                        if ( result && result.length ) {
                            result = result.map( ( name ) => {
                                let thisData = {
                                    value: name,
                                    label: name,
                                };

                                // get label from formats list
                                postFormats.map( ( formatData ) => {
                                    if ( formatData.name === name ) {
                                        thisData.label = formatData.name;
                                    }
                                } )

                                return thisData;
                            } );
                            return result;
                        }
                        return [];
                    } )() }
                    isMulti
                    name="filter-by-formats"
                    className="basic-multi-select"
                    classNamePrefix="select"
                    components={ {
                        IndicatorSeparator: () => null,
                        ClearIndicator: () => null,
                    } }
                    onChange={ ( val ) => {
                        let result = '';
                        let slug = '';

                        if ( val ) {
                            val.forEach( ( formatData ) => {
                                result += slug + formatData.value;
                                slug = ',';
                            } );
                        }

                        onChange( result );
                    } }
                />
            </BaseControl>
		);
    }
}

export default withSelect( ( select, props ) => {
    const {
        getThemeSupports,
    } = select( 'core' );

    const themeSupports = getThemeSupports();
    const postFormats = themeSupports.formats ? themeSupports.formats : [];

	return {
        postFormats: postFormats.map( ( format ) => {
            return {
                label: format,
                value: format,
            };
        } ),
	};
})( ComponentPostFormatsSelectorControl );
