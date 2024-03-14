import ReactSelect from 'react-select';

const { __ } = wp.i18n;

const {
	Component,
} = wp.element;

const {
	BaseControl,
} = wp.components;

/**
 * Component
 */
export default class ReactSelectControl extends Component {
	constructor() {
		super(...arguments);
	}

	render() {
		const {
			val,
			label,
			isMulti,
			onChange,
			options,
		} = this.props;

		return (
			<BaseControl
				label={ label || false }
			>
				<ReactSelect
					label={ label }
					isMulti={ isMulti }
					options={ options }
					value={ ( () => {
						var list = val;

						if ( ! Array.isArray( val ) ) {
							list = [];
						}

						const result = list.map( ( val ) => {
							const el = options.find(function(el){
								if ( val === el['value'] ) {
									return true;
								}
							})

							if ( el ) {
								return {
									value: val,
									label: el['label'],
								};
							}
						} );

						return result;
					} )() }
					onChange={ ( val ) => {
						if ( val ) {
							const result = val.map( ( opt ) => {
								return opt.value;
							} );

							onChange( result );
						} else {
							onChange( [] );
						}
					} }
				/>
			</BaseControl>
		);
	}
}
