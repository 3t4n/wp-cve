import axios from 'axios';

let abortController = null;

export const loadOptions = ( { searchString, ajaxUrl, formData, setFilteredOptions, setFilteredValue, setPointId, pointId } ) => {
	if ( abortController !== null ) {
		abortController.abort();
	}
	const abortControllerLocal = new AbortController();

	axios.post( ajaxUrl, formData, { signal: abortControllerLocal.signal } ).then( function ( response ) {
		if ( response.status === 200 ) {
			const options = response.data.items.map( ( item ) => {
				return {
					label: item.text,
					value: String(item.id),
				}
			} );
			setFilteredOptions( options );
			setFilteredValue( searchString );
			if ( setPointId ) {
				if ( pointId && options.filter( ( option ) => option.value === pointId ).length > 0 ) {
					setPointId( pointId );
				} else {
					setPointId( options[ 0 ].value );
				}
			}
		} else {
			window.console.log( response.statusText );
		}
	} )
};
