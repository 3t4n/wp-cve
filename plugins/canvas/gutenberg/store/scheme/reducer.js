
function reducer( state = { scheme: '' }, action ) {

	switch ( action.type ) {
	case 'UPDATE_SCHEME':
		return {
			...state,
			scheme: action.scheme,
		};
	// no default
	}
	return state;
}

export default reducer;
