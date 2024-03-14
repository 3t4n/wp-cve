const { Button, Dashicon } = wp.components;

const Relation = (props) => {
	const { post } = props;

	if ( ! post ) {
		return null;
	}

	return (
		<li className="crp-relation">
			<Button
				className="crp-remove-relation-button"
				onClick={() => props.onRemove(post.id) }
			>
				<Dashicon icon="trash" />
			</Button>
			<a className="crp-relation-title" href={post.permalink} target="_blank">{ post.title }</a>
			{
				props.hasOwnProperty( 'onChangeOrder' )
				&& false !== props.onChangeOrder
				&&
				<span className="crp-relation-order-buttons">
					<Button
						className="crp-order-up-relation-button"
						onClick={() => props.onChangeOrder( true ) }
						disabled={ ! props.allowUp }
					>
						<Dashicon icon="arrow-up" />
					</Button>
					<Button
						className="crp-order-down-relation-button"
						onClick={() => props.onChangeOrder( false ) }
						disabled={ ! props.allowDown }
					>
						<Dashicon icon="arrow-down" />
					</Button>
				</span>
			}
		</li>
) };

export default Relation;