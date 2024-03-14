export const initialState = {};

export const reducer = ( state, action ) => {
	switch ( action.type ) {
		case 'fetch_tables':
			return {
				...state,
				tables: action.payload,
			};
		default:
			return state;
	}
};
