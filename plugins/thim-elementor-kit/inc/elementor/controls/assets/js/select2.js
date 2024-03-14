jQuery( window ).on( 'elementor:init', function() {
	'use strict';

	const ControlBaseDataView = elementor.modules.controls.BaseData;

	const control = ControlBaseDataView.extend( {

		getSelect2Placeholder() {
			return this.ui.select.find( `[value="${ this.getControlPlaceholder() }"]` ).text() || this.ui.select.children( 'option:first[value=""]' ).text();
		},

		getSelect2DefaultOptions() {
			const defaultOptions = {
					allowClear: true,
					placeholder: this.getSelect2Placeholder(),
					dir: elementorCommon.config.isRTL ? 'rtl' : 'ltr',
				},
				lockedOptions = this.model.get( 'lockedOptions' );

			// If any non-deletable options are passed, remove the 'x' element from the template for selected items.
			if ( lockedOptions ) {
				defaultOptions.templateSelection = ( data, container ) => {
					if ( lockedOptions.includes( data.id ) ) {
						jQuery( container ).addClass( 'e-non-deletable' ).find( '.select2-selection__choice__remove' ).remove();
					}

					return data.text;
				};
			}

			return defaultOptions;
		},

		getSelect2Options() {
			return jQuery.extend( this.getSelect2DefaultOptions(), this.model.get( 'select2options' ) );
		},

		updatePlaceholder() {
			if ( this.getControlPlaceholder() ) {
				this.select2Instance.parent().find( '.select2-selection__placeholder' ).addClass( 'e-select2-placeholder' );
			}
		},

		applySavedValue() {
			ControlBaseDataView.prototype.applySavedValue.apply( this, arguments );

			const select = this.ui.select,
				isMultiple = this.model.get( 'multiple' ),
				isSortable = this.model.get( 'sortable' ),
				elementSelect2Data = select.data( 'select2' );

			// Checking if the element itself was initiated with select2 functionality in case of multiple renders.
			if ( ! elementSelect2Data ) {
				this.select2Instance = select.select2( {
					$element: select,
					options: this.getSelect2Options(),
				} ).on( 'select2:select', function( evt ) {
					if ( isMultiple ) {
						const id = evt.params.data.id;

						const element = select.children( 'option[value=' + id + ']' );

						const parent = element.parent();

						element.detach();

						parent.append( element );

						select.trigger( 'change' );
					}
				} );

				this.updatePlaceholder();
				this.handleLockedOptions();
			} else {
				select.trigger( 'change' );
			}

			// sortables
			if ( isSortable && isMultiple ) {
				const select2Rendered = this.select2Instance.parent().find( 'ul.select2-selection__rendered' );

				select2Rendered.sortable( {
					placeholder: 'ui-state-highlight select2-selection__choice',
					forcePlaceholderSize: true,
					items: '>li:not(.select2-search__field)',
					tolerance: 'pointer',
					update() {
						select2Rendered.children( 'li[title]' ).each( function( i, obj ) {
							const element = select.children( 'option' ).filter( function() {
								return jQuery( this ).html() == obj.title;
							} );

							const parent = element.parent();

							element.detach();

							parent.append( element );

							select.trigger( 'change' );
						} );
					},
				} );
			}
		},

		handleLockedOptions() {
			const lockedOptions = this.model.get( 'lockedOptions' );

			if ( lockedOptions ) {
				this.ui.select.on( 'select2:unselecting', ( event ) => {
					if ( lockedOptions.includes( event.params.args.data.id ) ) {
						event.preventDefault();
					}
				} );
			}
		},

		onReady() {
			elementorDevTools.deprecation.deprecated( 'onReady', '3.0.0' );
		},

		getInputValue( input ) { // eslint-disable-line no-unused-vars
			return ControlBaseDataView.prototype.getInputValue.apply( this, arguments ) ?? '';
		},

		onBaseInputChange() {
			ControlBaseDataView.prototype.onBaseInputChange.apply( this, arguments );

			this.updatePlaceholder();
		},

		onBeforeDestroy() {
			// We always destroy the select2 instance because there are cases where the DOM element's data cache
			// itself has been destroyed but the select2 instance on it still exists
			this.ui.select.select2( 'destroy' );

			this.$el.remove();
		},
	} );

	elementor.addControlView( 'thim-ekit-select2', control );

	// Fix select2 sortable error in repeater.
	// override ControlRepeaterItemView onSortUpdate.
	const ControlRepeaterItemView = elementor.modules.controls.Repeater;

	const onSortUpdateCallbacks = ControlRepeaterItemView.prototype.onSortUpdate;
	ControlRepeaterItemView.prototype.onSortUpdate = function( event, ui ) {
		if ( event.target !== this.ui.fieldContainer[ 0 ] ) {
			return;
		}

		// callback ControlRepeaterItemView onSortUpdate.
		onSortUpdateCallbacks.apply( this, arguments );
	};
} );
