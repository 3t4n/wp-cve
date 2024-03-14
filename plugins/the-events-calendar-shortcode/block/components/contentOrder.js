import { useMemo, useEffect, useRef, forwardRef } from '@wordpress/element';
import { Dashicon } from '@wordpress/components';
import { applyFilters } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';
import {
	DndContext,
	closestCenter,
	PointerSensor,
	useSensor,
	useSensors,
} from '@dnd-kit/core';
import {
	arrayMove,
	SortableContext,
	verticalListSortingStrategy,
	useSortable,
} from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import config from '../config/contentOrder';

export default function ContentOrder( props ) {
	const { setAttributes, attributes } = props;
	const filteredConfig = applyFilters( 'ecs.contentOrderConfig', config );
	const designConfig = filteredConfig?.[ attributes.design ] ?? [];
	const { contentorder = designConfig } = attributes;

	let items = contentorder;

	// check for conditional items in design config
	const conditionalItems = designConfig.filter(
		( item ) => typeof item.conditional !== 'undefined'
	);

	// check if we need to add back conditional items
	if ( conditionalItems.length > 0 ) {
		conditionalItems.forEach( ( conditionalItem ) => {
			const attribute = conditionalItem.value;
			const index = items.findIndex( ( item ) => item.value === attribute );
			if ( index === -1 ) {
				items.push( conditionalItem );
			}
		} );
	}

	// see if any conditionals are not satisfied and remove them
	items = items.filter( ( item ) => {
		if ( typeof item.conditional === 'undefined' ) {
			return true;
		}

		const { attribute, comparison, value } = item.conditional;

		if ( typeof attributes[ attribute ] === 'undefined' ) {
			return false;
		}

		let result = false;

		switch ( comparison ) {
			case '===':
				result = attributes[ attribute ] === value;
				break;
			case '!==':
				result = attributes[ attribute ] !== value;
				break;
		}

		return result;
	} );

	// remove duplicate items
	items = items.filter(
		( item, index, self ) =>
			index === self.findIndex( ( t ) => t.value === item.value )
	);

	const itemIds = useMemo( () => items.map( ( item ) => item.value ), [ items ] );

	const sensors = useSensors(
		useSensor( PointerSensor, {
			activationConstraint: {
				distance: 8,
			},
		} )
	);

	/**
	 * Handle the drag end event
	 *
	 * @param {Event} event
	 */
	function handleDragEnd( event ) {
		const { active, over } = event;

		if ( active.id !== over.id ) {
			const oldIndex = itemIds.findIndex( ( item ) => item === active.id );
			const newIndex = itemIds.findIndex( ( item ) => item === over.id );
			const newAttributes = arrayMove( items, oldIndex, newIndex );

			setAttributes( { contentorder: newAttributes } );
		}
	}

	/**
	 * Change the checked property on the individiual contentorder item
	 *
	 * @param {Event} event
	 */
	function handleCheckboxChange( event ) {
		const { checked, value } = event.target;

		const newAttributes = items.map( ( item ) => {
			if ( item.value === value ) {
				item.checked = checked;
			}

			return item;
		} );

		setAttributes( { contentorder: newAttributes } );
	}

	// get previous attributes
	const previousDesignRef = useRef( attributes.design );

	// set contentorder to the new design config when the design changes
	useEffect( () => {
		if ( attributes.design !== previousDesignRef.current ) {
			setAttributes( { contentorder: designConfig } );

			previousDesignRef.current = attributes.design;
		}
	}, [ attributes.design ] );

	return items.length > 0 ? (
		<DndContext
			sensors={ sensors }
			collisionDetection={ closestCenter }
			onDragEnd={ handleDragEnd }
		>
			<SortableContext
				items={ itemIds }
				strategy={ verticalListSortingStrategy }
			>
				{ items.map( ( item ) => (
					<SortableItem
						key={ item.value }
						item={ item }
						onCheckboxChange={ handleCheckboxChange }
					/>
				) ) }
			</SortableContext>
		</DndContext>
	) : (
		<p>{ __( 'n/a', 'the-events-calendar-shortcode' ) }</p>
	);
}

function SortableItem( props ) {
	const { attributes, listeners, setNodeRef, transform, transition } =
		useSortable( { id: props.item.value } );

	const style = {
		transform: CSS.Translate.toString( transform ),
		transition,
		cursor: 'move',
	};

	return (
		<Item
			data-item-id={ props.item.value }
			item={ props.item }
			ref={ setNodeRef }
			style={ style }
			onCheckboxChange={ props.onCheckboxChange }
			{ ...attributes }
			{ ...listeners }
			{ ...props }
		/>
	);
}

const Item = forwardRef( ( { item, onCheckboxChange, ...props }, ref ) => {
	const containerCSSClass = item.checked
		? 'ecs-contentorder-item'
		: 'ecs-contentorder-item unchecked';

	return (
		<div { ...props } ref={ ref } className={ containerCSSClass }>
			<Dashicon icon="menu-alt2" />
			<span className="ecs-contentorder-item-inner">
				<input
					type="checkbox"
					onChange={ ( event ) => onCheckboxChange( event, item ) }
					checked={ item.checked }
					value={ item.value }
				/>
				{ item.label }
			</span>
		</div>
	);
} );
