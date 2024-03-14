const { __ } = wp.i18n;

const { Fragment } = wp.element;
const { compose } = wp.compose;
const { withDispatch } = wp.data;

import Data from '../data/helpers';
import Relation from './relation';

const relationsList = ( relations, onRemoveRelation, onChangeOrder ) => {
	let orderedRelations = Object.values( relations );
	orderedRelations.sort( (a, b) => a.order - b.order );

	return (
		<ul>
			{
				orderedRelations.map( ( relation, index ) => {
					let post = relations[ relation.id ];

					const allowUp = 0 < index;
					const allowDown = index < orderedRelations.length - 1;

					let onChangeRelationOrder = false;
					if ( false !== onChangeOrder ) {
						onChangeRelationOrder = ( up ) => {
							let orderedIds = orderedRelations.map( ( a ) => a.id );

							let swapIndex = index;
							if ( up && allowUp ) {
								swapIndex--;
							} else if ( ! up && allowDown ) {
								swapIndex++;
							}

							let temp = orderedIds[ swapIndex ];
							orderedIds[ swapIndex ] = orderedIds[ index ];
							orderedIds[ index ] = temp;

							onChangeOrder( orderedIds );
						}
					}
					
					return (
						<Relation
							post={ post }
							allowUp={ allowUp }
							allowDown={ allowDown }
							key={ relation.id }
							onRemove={ onRemoveRelation }
							onChangeOrder={ onChangeRelationOrder }
						/>
					)
				})
			}
		</ul>
	);
};

function Relations( props ) {
	const { relations, relationToIDs, relationFromIDs } = props;

	return (
		<Fragment>
			{
				0 < relationToIDs.length
				&&
				<Fragment>
					<h3>{ __( 'This post links to' )}</h3>
					{ relationsList(relations.to, props.onRemoveRelationTo, props.onChangeOrder ) }
				</Fragment>
			}
			{
				0 < relationFromIDs.length
				&&
				<Fragment>
					<h3>{ __( 'This post get links from' )}</h3>
					{ relationsList(relations.from, props.onRemoveRelationFrom, false ) }
				</Fragment>
			}
		</Fragment>
) };

const applyWithDispatch = withDispatch( ( dispatch, ownProps ) => {
	const { removeRelationTo, removeRelationFrom, setOrder } = dispatch( 'custom-related-posts' );

    return {
		onRemoveRelationTo: ( target ) => {
			return removeRelationTo( ownProps.postId, target );
		},
		onRemoveRelationFrom: ( target ) => {
			return removeRelationFrom( ownProps.postId, target );
		},
		onChangeOrder: ( order ) => {
			return setOrder( ownProps.postId, order );
		},
    }
} );

export default compose(
    Data.selectRelationsForCurrentPost,
    applyWithDispatch
)( Relations );