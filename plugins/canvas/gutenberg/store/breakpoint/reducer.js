
function reducer( state = { breakpoint: '' }, action ) {
	switch ( action.type ) {
	case 'UPDATE_BREAKPOINT':
		return {
			...state,
			breakpoint: action.breakpoint,
		};
	// no default
	}

	return state;
}

export default reducer;
