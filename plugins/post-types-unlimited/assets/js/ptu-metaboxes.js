( function() {
	const metabox = document.querySelector( '.ptu-metabox' );

	if ( ! metabox ) {
		return;
	}

	// Debounce.
	const debounce = (fn, delay, timeout = 0) => (args) => {
		clearTimeout(timeout);
		timeout = setTimeout(() => fn(args), delay);
	};

	// Auto add name when writting the title for new posts only.
	if ( document.body.classList.contains( 'post-new-php' ) ) {
		const titleInput = document.querySelector( '#titlewrap #title' );
		const nameInput = document.querySelector( '#ptu-metabox-field--name' );
		const labelInput = document.querySelector( '#ptu-metabox-field--label' );
		if ( titleInput ) {
			titleInput.addEventListener( 'keyup', debounce( () => {
				const value = titleInput.value;
				if ( nameInput ) {
					nameInput.value = value.replace(/ /g, '_' ).toLowerCase();
				}
				if ( labelInput ) {
					labelInput.value = value;
				}
			}, 250 ) );
		}
	}

	// TABS.
	document.addEventListener( 'click', function( event ) {
		const tabLink = event.target.closest( '.ptu-metabox-tab-link' );

		if ( ! tabLink ) {
			return;
		}

		event.preventDefault();

		const allTabLinks = document.querySelectorAll( '.ptu-metabox-tab-link' );
		const targetSection = document.getElementById( tabLink.getAttribute( 'aria-controls' ) );

		document.querySelectorAll( '.ptu-metabox-tab-link' ).forEach( el => {
			el.setAttribute( 'aria-selected', 'false' );
		} );

		tabLink.setAttribute( 'aria-selected', 'true' );

		document.querySelectorAll( '.ptu-metabox-section' ).forEach( el => {
			el.classList.add( 'hidden' );
		} );

		targetSection.classList.remove( 'hidden' );
	} );

	// Close modal helper.
	const closeModal = ( modal ) => {
		modal.querySelector( '.ptu-metabox-modal__icons' ).innerHTML = '';
		modal.querySelector( '.ptu-metabox-modal__search' ).value = '';
		modal.style.display = 'none';
		modal.classList.remove( 'ptu-metabox-modal--active' );
		document.querySelector( 'body' ).classList.remove( 'modal-open' );
	};

	// Modal popup open.
	document.addEventListener( 'click', function( event ) {
		const button = event.target.closest( '.ptu-metabox-icon-select__button' );

		if ( ! button ) {
			return;
		}

		const wrap = button.closest( '.ptu-metabox-icon-select' );
		const modal = wrap.querySelector( '.ptu-metabox-modal' );
		const modalIconsContainer = modal.querySelector( '.ptu-metabox-modal__icons' );

		let iconsHtml = '';
		choices = JSON.parse( modal.getAttribute( 'data-ptu-icons-list' ) );
		for (const key in choices ) {
			let icon = choices[key];
			iconsHtml += `<button type="button" class="ptu-metabox-modal__icon-button components-button" data-ptu-icon="${icon}"><span class="ptu-metabox-modal__icon"><span class="dashicons dashicons-${icon}" aria-hidden="true"></span></span><span class="ptu-metabox-modal__icon-label">${icon}</span></button>`;
		}
		modalIconsContainer.innerHTML = iconsHtml;
		modal.style.display = '';
		document.querySelector( 'body' ).classList.add( 'modal-open' );
		modal.classList.add( 'ptu-metabox-modal--active' );
		modal.querySelector( '.ptu-metabox-modal__search' ).focus();
	} );

	// Close modal on escape.
	document.addEventListener( 'keydown', event => {
		const modal = event.target.closest( '.ptu-metabox-modal' );
		if ( modal && 'Escape' === event.key ) {
			closeModal( modal );
		}
	} );

	// Modal close button.
	document.addEventListener( 'click', function( event ) {
		const button = event.target.closest( '.ptu-metabox-modal__close' );
		if ( button ) {
			event.preventDefault();
			closeModal( button.closest( '.ptu-metabox-modal' ) );
		}
	} );

	// Select Icon.
	document.addEventListener( 'click', event => {
		const button = event.target.closest( '.ptu-metabox-modal__icon-button' );
		if ( button ) {
			const value = button.getAttribute( 'data-ptu-icon' );
			const wrap = button.closest( '.ptu-metabox-icon-select' );
			const preview = wrap.querySelector( '.ptu-metabox-icon-preview' );
			wrap.querySelector( 'input[type="text"]').value = value;
			if ( preview ) {
				preview.innerHTML = `<span class="dashicons dashicons-${value}" aria-hidden="true"></span>`;
			}
			closeModal( button.closest( '.ptu-metabox-modal' ) );
		}
	} );

	// Modal search.
	const onModalSearch = ( event ) => {
		const modalSearch = event.target.closest( '.ptu-metabox-modal__search' );
		if ( modalSearch ) {
			const $this  = jQuery( modalSearch );
			const value  = $this.val().toLowerCase();
			const $icons = $this.closest( '.ptu-metabox-modal' ).find( '.ptu-metabox-modal__icons button' );
			$icons.filter( function() {
				jQuery( this ).toggle( jQuery( this ).attr( 'data-ptu-icon' ).toLowerCase().indexOf( value ) > -1 );
			} );
		}
	};

	document.addEventListener( 'keyup', onModalSearch );

	// Conditional Logic.
	const conditionalLogic = ( tr, field, operator, value ) => {
		let check = true;
		let fieldVal = field.value;
		if ( 'checkbox' === field.getAttribute( 'type' ) ) {
			fieldVal = field.checked.toString();
		}
		switch ( operator ) {
			case '=':
				check = value == fieldVal;
				break;
			case '!=':
				check = value !== fieldVal;
				break;
		}
		if ( check ) {
			tr.classList.remove( 'hidden' );
		} else {
			tr.classList.add( 'hidden' );
		}
	};

	document.querySelectorAll( '[data-ptu-condition]' ).forEach( tr => {
		const condition = JSON.parse( tr.getAttribute( 'data-ptu-condition' ) );
		const conditionalField = document.querySelector( `#ptu-metabox-field--${condition[0]}` );
		if ( conditionalField ) {
			conditionalLogic( tr, conditionalField, condition[1], condition[2] );
			conditionalField.addEventListener( 'change', event => {
				conditionalLogic( tr, conditionalField, condition[1], condition[2] );
			} );
		} else {
			tr.classList.add( 'hidden' );
		}
	} );

} ) ();
