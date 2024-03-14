import classnames from 'classnames';
import { Button, Icon, TextControl, VisuallyHidden } from '@wordpress/components';
import { useSelect } from 'downshift';
import { useState } from '@wordpress/element';

const itemToString = ( item ) => item && item.name;

const stateReducer = (
	{ selectedItem },
	{ type, changes, props: { items } }
) => {
	switch ( type ) {
		case useSelect.stateChangeTypes.ToggleButtonKeyDownArrowDown:
			return {
				selectedItem:
					items[
						selectedItem
							? Math.min(
									items.indexOf( selectedItem ) + 1,
									items.length - 1
							  )
							: 0
					],
			};
		case useSelect.stateChangeTypes.ToggleButtonKeyDownArrowUp:
			return {
				selectedItem:
					items[
						selectedItem
							? Math.max( items.indexOf( selectedItem ) - 1, 0 )
							: items.length - 1
					],
			};
		default:
			return changes;
	}
};

export default function SearchableSelectControl( {
	className,
	hideLabelFromVision,
	label,
	selectPlaceholder,
	searchPlaceholder,
	noResultsLabel,
	options: items,
	onChange: onSelectedItemChange,
	value: _selectedItem,
} ) {
	const {
		getLabelProps,
		getToggleButtonProps,
		getMenuProps,
		getItemProps,
		isOpen,
		highlightedIndex,
		selectedItem,
	} = useSelect( {
		initialSelectedItem: items[ 0 ],
		items,
		itemToString,
		onSelectedItemChange,
		selectedItem: _selectedItem,
		stateReducer,
	} );

	const [ filteredItems, setFilteredItems ] = useState( items );

	const menuProps = getMenuProps( {
		className: classnames( 'components-custom-select-control__menu', 'components-searchable-select-control__menu' ),
		'aria-hidden': ! isOpen,
	} );

	if (
		menuProps[ 'aria-activedescendant' ] &&
		menuProps[ 'aria-activedescendant' ].slice(
			0,
			'downshift-null'.length
		) === 'downshift-null'
	) {
		delete menuProps[ 'aria-activedescendant' ];
	}

	return (
		<div
			className={ classnames(
				'components-custom-select-control',
				'components-searchable-select-control',
				className
			) }
		>
			{ hideLabelFromVision ? (
				<VisuallyHidden as="label" { ...getLabelProps() }>
					{ label }
				</VisuallyHidden>
			) : (
				/* eslint-disable-next-line jsx-a11y/label-has-associated-control, jsx-a11y/label-has-for */
				<label
					{ ...getLabelProps( {
						className: classnames( 'components-custom-select-control__label', 'components-searchable-select-control__label' ),
					} ) }
				>
					{ label }
				</label>
			) }
			<Button
				{ ...getToggleButtonProps( {
					'aria-label': label,
					'aria-labelledby': undefined,
					className: classnames( 'components-custom-select-control__button', 'components-searchable-select-control__button' ),
					isSmall: false,
				} ) }
			>
				<span className="components-searchable-select-control__button-text" title={ itemToString( selectedItem ) }>
					{ itemToString( selectedItem ) || selectPlaceholder }
				</span>
				<Icon
					icon={ ( <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z" /></svg> ) }
					className={ classnames( 'components-custom-select-control__button-icon', 'components-searchable-select-control__button-icon' ) }
				/>
			</Button>
			<div { ...menuProps }>
				{ isOpen &&
					<div className={ classnames( 'components-custom-select-control__menu-inner', 'components-searchable-select-control__menu-inner' ) }>
						<TextControl
							placeholder={ searchPlaceholder }
							className={ classnames( 'components-custom-select-control__search', 'components-searchable-select-control__search' ) }
							onChange={ ( value ) => {
								if ( items.length > 0 ) {
									let filtered = items.filter( x => x.name.toLowerCase().search( value.toLowerCase().trim() ) != -1 );

									if ( filtered.length < 1 ) {
										filtered.push( { key: '-2', name: noResultsLabel, style: { opacity: '0.7', pointerEvents: 'none' } } );
									}

									setFilteredItems( filtered );
								}
							} }
						/>

						<ul className={ classnames( 'components-custom-select-control__items', 'components-searchable-select-control__items' ) }>
							{ filteredItems.map( ( item, index ) => (
								// eslint-disable-next-line react/jsx-key
								<li
									{ ...getItemProps( {
										item,
										index,
										key: item.key,
										className: classnames(
											item.className,
											'components-custom-select-control__item',
											'components-searchable-select-control__item',
											{
												'is-highlighted':
													index === highlightedIndex
											},
											{
												'is-selected':
													item && selectedItem && item.key == selectedItem.key
											}
										),
										style: item.style,
									} ) }
								>
									{ item && selectedItem && item.key == selectedItem.key && (
										<Icon
											icon={ ( <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9 18.6L3.5 13l1-1L9 16.4l9.5-9.9 1 1z" /></svg> ) }
											className={ classnames( 'components-custom-select-control__item-icon', 'components-searchable-select-control__item-icon' ) }
										/>
									) }
									{ item.name }
								</li>
							) ) }
						</ul>
					</div>
				}
			</div>
		</div>
	);
}
