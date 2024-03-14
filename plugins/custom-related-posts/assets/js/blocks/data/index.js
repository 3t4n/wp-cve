const { apiFetch } = wp;
const { registerStore, dispatch } = wp.data;

import Data from '../data/helpers';

const DEFAULT_STATE = {
    relations: {
        to: {},
        from: {},
    },
};

registerStore( 'custom-related-posts', {
	reducer( state = DEFAULT_STATE, action ) {
        let newState = JSON.parse( JSON.stringify( state ) );

		switch ( action.type ) {
            case 'SET_RELATIONS':
                newState.relations = action.relations;
                return newState;
			case 'SET_ORDER':
				let orderedRelations = {};

				let index = 0;
				for ( let id of action.order ) {
					if ( state.relations.to.hasOwnProperty( id ) ) {
						orderedRelations[ id ] = {
							...state.relations.to[id],
							order: index,
						};
						index++;
					}
				}

				// Make sure every relation stays.
				for ( let id of Object.keys( state.relations.to ) ) {
					if ( ! orderedRelations.hasOwnProperty( id ) ) {
						orderedRelations[ id ] = {
							...state.relations.to[id],
							order: index,
						};
					}
				}

				newState.relations.to = orderedRelations;

				return newState;
			case 'ADD_RELATION_TO':
                newState.relations.to[action.post.id] = action.post;
				return newState;

            case 'ADD_RELATION_BOTH':
                newState.relations.to[action.post.id] = action.post;
                newState.relations.from[action.post.id] = action.post;
				return newState;

			case 'ADD_RELATION_FROM':
                newState.relations.from[action.post.id] = action.post;
				return newState;

			case 'REMOVE_RELATION_TO':
                delete newState.relations.to[action.target];
				return newState;

			case 'REMOVE_RELATION_FROM':
				delete newState.relations.from[action.target];
				return newState;
		}

		return state;
	},

	actions: {
        setRelations( relations ) {
			return {
				type: 'SET_RELATIONS',
				relations,
			};
		},
		setOrder( postId, order ) {
			Data.setOrder( postId, order );

			return {
				type: 'SET_ORDER',
				order,
			}
		},
		addRelationTo( postId, post ) {
			Data.saveRelation( postId, post.id, 'to' );

			return {
				type: 'ADD_RELATION_TO',
				post,
			};
		},
		addRelationBoth( postId, post ) {
			Data.saveRelation( postId, post.id, 'both' );

			return {
				type: 'ADD_RELATION_BOTH',
				post,
			};
		},
		addRelationFrom( postId, post ) {
			Data.saveRelation( postId, post.id, 'from' );

			return {
				type: 'ADD_RELATION_FROM',
				post,
			};
		},
		removeRelationTo( postId, target ) {
			Data.removeRelation( postId, target, 'to' );

			return {
				type: 'REMOVE_RELATION_TO',
				target,
			};
		},
		removeRelationFrom( postId, target ) {
			Data.removeRelation( postId, target, 'from' );

			return {
				type: 'REMOVE_RELATION_FROM',
				target,
			};
		},
	},

	selectors: {
        getRelations( state, args ) {
			return state.relations;
        },
	},

	resolvers: {
        getRelations( state, args ) {
			const request = apiFetch( { path: `custom-related-posts/v1/relations/${ args.postId }` } );

			request.then( ( relations ) => {
				// Make sure relations are an object.
				if ( Array.isArray( relations.to ) ) {
					relations.to = {};
				}

				if ( Array.isArray( relations.from ) ) {
					relations.from = {};
				}

				dispatch( 'custom-related-posts' ).setRelations( relations );
			} );
		},
	},
} );