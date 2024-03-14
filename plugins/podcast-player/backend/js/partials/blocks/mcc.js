/**
 * WordPress dependencies
 */
const { CheckboxControl } = wp.components;

function MultipleCheckboxControl( { listItems, selected, onItemChange, label } ) {
	return (
		<div className='components-base-control'>
			<label class="components-base-control__label">{ label }</label>
			<ul className="multibox__checklist">
				{ listItems.map( ( item ) => (
					<li
						key={ item.value }
						className="multibox__checklist-item"
					>
						<CheckboxControl
							label={item.label}
							checked={ selected.includes(item.value) }
							onChange={ () => { onItemChange(item.value) } }
						/>
					</li>
				) ) }
			</ul>
		</div>
	);
}

export default MultipleCheckboxControl;
