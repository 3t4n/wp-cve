/**
 * WordPress dependencies
 */
const { CheckboxControl } = wp.components;

function EpisodeCheckboxControl( { listItems, selected, onItemChange, label } ) {

	// Allow HTML in the string.
	const entityToChar = str => { 
		const textarea = document.createElement('textarea'); 
		textarea.innerHTML = str; 
		return textarea.value; 
	}
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
							label={entityToChar(item.label)}
							checked={ selected.includes(item.value) }
							onChange={ () => { onItemChange(item.value) } }
							disabled={selected.includes('') && '' !== item.value}
							className={(selected.includes('') && '' !== item.value) ? 'checkbox-disabled' : ''}
						/>
					</li>
				) ) }
			</ul>
		</div>
	);
}

export default EpisodeCheckboxControl;
