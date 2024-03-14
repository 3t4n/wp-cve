/**
* @version $ Id; block.jquery.js 21-03-2012 03:22:10 Ahmed Said $
*
* CJT Block jQuery Plugin
*/

/**
* JQuery wrapper for the CJTBlockPlugin object.
*/
( function ( $ ) {

	/**
	* put your comment there...
	*
	*/
	$.fn.CJTPLSHooksDropdown = function ( arg ) {

		var result = this
		var args = arguments

		this.each(

			function () {

				// Initialize the Plugin only once for each element on the chain
				if ( this.CJTPLSHooksDropdown ) {

					result = this.CJTPLSHooksDropdown[ arg ].call( $this, args )

					return
				}

				// Prepare PLugin vars
				var $this = $( this )

				options = arg

				// Initialize NEW Dropdown jQuery Plugin for the HTML element
				this.CJTPLSHooksDropdown = new function () {

					// Initialize object vars
					var listValueElement
					var listElement
					var listElements

					/**
					* put your comment there...
					*
					*/
					var getItemValue = function ( link ) {

						var hookName = link.prop( 'href' ).match( /#(.+)/ )[ 1 ]

						return hookName
					}

					/**
					* put your comment there...
					*
					*/
					var init = function () {

						// Get list elements jQuery object
						listValueElement = $this.find( '.value>a' )
						listElement = $this.find( '.hooks-dropdown-list' )
						listElements = listElement.find( 'li:not(.cjt-promo-disabled)' )

						listValueElement.click(

							function () {

								listElement.toggle()

								return false
							}
						)

						// list elements click event
						listElements.find( 'a' ).click(

							function ( event ) {

								var link = $( this )

								// Reflect selected value
								var hookName = getItemValue( link )

								listValueElement.text( link.text() )

								// Set selected value CSS
								listElements.removeClass( 'selected' )
								link.parent().addClass( 'selected' )

								// ITem selection event
								$this.trigger( 'change', [ $this, hookName, link ] )

								return false
							}
						)

						// Initially select the selected element
						listValueElement.text( listElements.filter( '.selected' ).find( 'a' ).text() )

					}

					/**
					*
					*/
					this.getOptions = function () {

						return options
					}

					/**
					*
					*/
					this.setValue = function ( args ) {

						listElements.find( 'a' ).each(

							function () {

								var link = $( this )
								var hookName = getItemValue( link )

								if ( hookName == args[ 1 ] ) {

									listValueElement.text( link.text() )

									listElements.removeClass( 'selected' )
									link.parent().addClass( 'selected' )

									return false
								}

							}

						)

					}

					this.toggle = function () {

						listElement.toggle()

					}

					// Initialize on construction
					init()

				}

			}
		)

		// Chaining
		return result
	}

	/**
	*
	*/
	var notifySaveChangesProto = function ( block ) {

		/**
		* put your comment there...
		*
		* @param block
		*/
		this.initDIFields = function () {
			// Initialize notification saqve change singlton object.
			block.changes = []

			// Initialize vars.
			var model = block.block
			var aceEditor = model.aceEditor
			var fields = model.getDIFields()

			/**
			* Create common interface for ace editor to
			* be accessed like other HTML elements.
			*
			*
			* Bind method for bind events like HTML Elements +
			* Method to get hash copy from stored content.
			*/
			aceEditor.type = 'aceEditor' // Required for _oncontentchanged to behave correctly.
			aceEditor.bind = function ( e, h ) {

				this.getSession().doc.on( e, h )
			}
			aceEditor.cjtSyncInputField = function () {

				this.cjtBlockSyncValue = hex_md5( this.getSession().getValue() )
			}

			// Hack jQuery Object by pushing
			// ace Editor into fields list, increase length by 1.
			fields[ fields.length++ ] = aceEditor

			// For all fields call cjtSyncInputField and give a unique id.
			$.each( fields, $.proxy(

				function ( index, field ) {

					this.initElement( field )

				}, this )

			)

			// Notify Changes for Block name when key pressed
			model.box.find( 'input:text.block-name' ).on( 'keyup', $.proxy( block._oncontentchanged, block ) )

			// Chaining.
			return this
		},

			/**
			* put your comment there...
			*
			* @param element
			*/
			this.initElement = function ( field ) {

				// Assign weight number used to identify the field.
				field.cjtBlockFieldId = CJTBlocksPage.blocks.getUFI()

				// Create default cjtSyncInputField method if not exists.
				if ( field.cjtSyncInputField == undefined ) {

					if ( field.type == 'checkbox' ) {

						field.cjtSyncInputField = function () {

							this.cjtBlockSyncValue = $( this ).prop( 'checked' )

						}

					}
					else {

						field.cjtSyncInputField = function () {

							this.cjtBlockSyncValue = this.value
						}

					}

					// Create interface to "bind" too.
					field.bind = function ( e, h ) {

						$( this ).bind( e, h )
					}

				}

				// Sync field.
				field.cjtSyncInputField()

				// Bind to change event.
				field.bind( 'change', $.proxy( block._oncontentchanged, block ) )
			}
	}

	/**
	* Default block features and options.
	*
	* @var object
	*/
	var defaultOptions = {
		showObjectsPanel: true,
		calculatePinPoint: 1,
		restoreRevision: { fields: [ 'code' ] }
	}

	/**
	* Element to loads for each block.
	*
	* This element is commonly used in various places inside the Plugin.
	* there is no need to find them everytime we need it. Load it only one time.
	*
	* @var object
	*/
	var autoLoadElements = {
		editBlockName: 'div.edit-block-name',
		blockName: 'span.block-name',
		insideMetabox: 'div.inside'
	}

	/**
	* Block jQuery Plugin.
	*
	* The purpose is to handle all block functionality and UI.
	*
	* @version 6
	* @Author Ahmed Said
	*/
	CJTBlockPluginBase = function () {

		/**
		* Block model for accessing block properties.
		*
		* @var CJTBlock
		*/
		this.block

		/**
		*
		*
		*/
		this.changes

		/**
		*
		*/
		this.defaultDocks

		/**
		*
		*/
		this.editBlockActionsToolbox

		/**
		*
		*/
		this.editorToolbox

		/**
		* Commonly accessed elements stored here.
		*
		* @var object
		*/
		this.elements

		/**
		*
		*/
		this.extraDocks = []

		/**
		* Block features and options.
		*
		* @var object
		*/
		this.features

		/**
		*
		*/
		this.editorLangsToolbox

		/**
		*
		*/
		this.flaggedActionsToolbox

		/**
		*
		*/
		this.hooksDropdown

		/**
		*
		*/
		this.infoBar

		/**
		*
		*/
		this.internalChanging = false

		/**
		*
		*
		*
		*/
		this.toolbox = null

		// Block Plugins
		CJTBlockObjectPluginDockModule.plug( this )

		/**
		*
		*/
		this._onclosepanelwindow = function ( event ) {

			var assignPanel = this.block.box.find( '.cjt-panel-item.cjt-panel-window-assignments' )
			var panelWnds = this.block.box.find( '.cjt-panel-item' )

			// Make sure all panel windows are hidden
			panelWnds.hide()

			// Always display the assignment panel
			assignPanel.show()

			// Hide close button
			$( event.target ).hide()

			return false
		}

		/**
		*
		*
		*/
		this._oncontentchanged = function ( event ) {
			// Dont process internal changes.
			if ( this.internalChanging ) {
				return
			}
			// Initialize.
			var element
			var id // Give every field an id for tracing change.
			var newValue // Field new value.
			var enable // Used to enable/disable save button
			// based on detected changes.
			var isFieldChanged
			var isChanged
			var syncValue // This is the value stored in server.
			// Get value, id and sync value based on the input field type.
			if ( event.target == undefined ) { // Ace editor event system don't
				element = this.block.aceEditor
				// pass aceEditor object as context!
				newValue = hex_md5( element.getSession().getValue() )
			}
			else { // All HTML native types.
				element = event.target
				// Use field "value" property for getting new
				// context except checkboxes uses checked property.
				newValue = ( element.type == 'checkbox' ) ? newValue = $( element ).prop( 'checked' ) : element.value
			}
			id = element.cjtBlockFieldId
			syncValue = element.cjtBlockSyncValue
			// Detect if value is changes.
			isFieldChanged = ( newValue != syncValue )
			isChanged = CJTBlocksPage.blocks.calculateChanges( this.changes, id, isFieldChanged )
			// Enable button is there is a change not saved yet, disable it if not.
			this.editBlockActionsToolbox.buttons.save.enable( isChanged )
			// Notify blocks page.
			CJTBlocksPage.blockContentChanged( this.block.id, isChanged )
		}

		/**
		* Event handler for delete the block.
		*
		* The method delete the block from the UI but not permenant from the server.
		* Save all Changes should be called in order to save deleted blocks.
		*/
		this._ondelete = function () {
			// Conformation message.
			var confirmMessage = CJTJqueryBlockI18N.confirmDelete
			// Show Block name!
			confirmMessage = confirmMessage.replace( '%s', this.block.get( 'name' ) )
			// Confirm deletion!
			if ( confirm( confirmMessage ) ) {

				this.block.box.trigger( 'BeforeDeleteBlock', [ this ] )

				// Delete block.
				CJTBlocksPage.deleteBlocks( this.block.box )

				this.block.box.trigger( 'BlockDeleted', [ this ] )
			}
		}

		/**
		*
		*
		*
		*
		*/
		this._ondisplayrevisions = function ( event ) {

			// Restore revision only when block is opened.
			if ( this.block.box.hasClass( 'closed' ) ) {
				return false
			}
			// Initialize form request.
			var revisionsFormParams = {
				id: this.block.get( 'id' ),
				activeFileId: this.codeFile.file.activeFileId
			}
			var url = CJTBlocksPage.server.getRequestURL( 'block', 'get_revisions', revisionsFormParams )

			var genericPanelWindow = $( '.cjpageblock .cjt-panel-genericwnd' )

			genericPanelWindow
				.empty()
				.append( '<iframe style="width:100%;height:100%;" src="' + url + '"></iframe>' )

			this._onPaneledItems( event )

			return false
		}

		/**
		*
		*
		*
		*
		*/
		this._ongetinfo = function ( event ) {

			var sections = {
				'info': 'info',
				'assignment-info': 'assignment'
			}

			var windowName = $( event.target ).prop( 'href' ).match( /#(.+)/ )[ 1 ]
			var sectionName = sections[ windowName ]

			// Server request.
			var requestData = {

				// Server paramerers.
				id: this.block.get( 'id' ),
				show_section: sectionName
			}

			var url = CJTBlocksPage.server.getRequestURL( 'block', 'get_info_view', requestData )

			this.showPanelGenericWindow( event, url )
		}

		/**
		*
		*/
		this._onlookuptemplates = function ( event ) {

			// Initialize.
			var panelWnd = this.block.box.find( '.cjt-panel-item.cjt-panel-window-templates-lookup' )
			var frameHeight = parseInt( panelWnd.css( 'height' ) )
			var blockId = this.block.get( 'id' )
			var iframe = panelWnd.find( 'iframe' )
			var iframeHeight = frameHeight - 35

			// Stay inactive if the toolbox is didsabled, as the toolbox
			// has no class for enable/disable state we might use on of its buttons
			if ( this.toolbox.buttons[ 'location-switch' ].jButton.hasClass( 'cjttbs-disabled' ) ) {

				return
			}

			this._onPaneledItems( event )

			iframe.css( 'height', iframeHeight )

			if ( !CJTToolBox.forms.templatesLookupForm[ blockId ] ) {
				CJTToolBox.forms.templatesLookupForm[ blockId ] = {}
			}
			var lookupForm = CJTToolBox.forms.templatesLookupForm[ blockId ]
			// This method will fired only once when the
			// Templates popup button is hovered for the first time.
			if ( !iframe.get( 0 ).__cjt_loaded ) {
				var request = { blockId: blockId }
				// Pass block object to the form when loaded.
				lookupForm.inputs = { blockPlugin: this, block: this.block, button: $( event.target ), height: iframeHeight }
				// Set frame Source to templates lookup view URL.
				var templatesLookupViewURL = CJTBlocksPage.server.getRequestURL( 'templatesLookup', 'display', request )
				iframe.prop( 'src', templatesLookupViewURL )
				// Mark loaded.
				iframe.get( 0 ).__cjt_loaded = true
			}
			else {
				// Pass frame height when refreshed.
				lookupForm.inputs.height = iframeHeight
				lookupForm.form.refresh()
			}
			/** @TODO Tell Block toolbox to deatach/unbind popup callback */
			return true // Tell CJTToolBox to Show Popup menu as normal.
		}

		/**
		*
		*/
		this._onPaneledItems = function ( event ) {

			var link = $( event.target )
			var windowName = link.prop( 'href' ).match( /#(.+)/ )[ 1 ]
			var panelWindow = this.block.box.find( '.cjt-panel-item.cjt-panel-window-' + windowName )
			var panelArea = this.block.box.find( '.cjpageblock' )

			// Hide all panel windows
			panelArea.find( '.cjt-panel-item' ).hide()

			// Display panel
			panelWindow.show()

			// Display Close button
			panelArea.find( '.close-panel-window' ).show()
		}


		/**
		* Don't show popup menus if Block is minimized!
		*/
		this._onpopupmenu = function ( targetElement, button ) {
			var show = true
			if ( this.block.box.hasClass( 'closed' ) ) {
				show = false
			}
			else {
				// Some Popup forms need to be re-sized if fullscree is On!
				if ( button.params.fitToScreen == true ) {
					this.dock( targetElement, 25 )
				}
			}
			return show
		}

		/**
		*
		*/
		this._onpostboxopened = function () {
			// If aceEditor is undefined then the
			// block is no loaded yet,
			// loads it.
			if ( this.block.aceEditor == undefined ) {
				this._onload()
			}
			else {
				// Update ACE Editor region.
				this.block.aceEditor.resize()
			}
		}

		/**
		* Event handler for saving block data.
		*
		* The method send the block data to the server.
		* @see CJTBlock.saveDIFields method for more details about fields.
		*
		*/
		this._onsavechanges = function () {

			var saveButton = this.editBlockActionsToolbox.buttons[ 'save' ]

			// Dont save unless there is a change!
			if ( saveButton.jButton.hasClass( 'cjttbs-disabled' ) ) {
				// Return REsolved Dummy Object for standarizing sake!
				return CJTBlocksPage.server.getDeferredObject().resolve().promise()
			}

			// Queue User Direct Interact fields (code, etc...).
			var data = { calculatePinPoint: this.features.calculatePinPoint, createRevision: 1 }

			// Push DiFields inside Ajax queue.
			this.block.queueDIFields()

			// Add code file flags to the queue.
			var queue = this.block.getOperationQueue( 'saveDIFields' )
			queue.add( { id: this.block.get( 'id' ), property: 'activeFileId', value: this.codeFile.file.activeFileId } )

			// But save button into load state (Inactive and Showing loading icon).
			if ( this.block.get( 'name' ).match( /^[A-Za-z0-9\!\#\@\$\&\*\(\)\[\]\x20\-\_\+\?\:\;\.]{1,50}$/ ) ) {
				saveButton.loading( true )
				this.enable( false )

				this.block.box.trigger( 'PreSaveBlock', [ this ] )
			}

			// Send request to server.
			return this.block.sync( 'saveDIFields', data )

				.success( $.proxy(

					function ( response ) {

						var responseBlockData = response[ this.block.get( 'id' ) ]

						// Stop loading effect and disable the button.
						saveButton.loading( false, false )

						// Sync fields with server value.
						// This refrssh required for notifying saving
						// change to detect changes.
						var diFields = this.block.getDIFields()
						// Push aceEditor into diFields list.
						diFields[ diFields.length++ ] = this.block.aceEditor
						diFields.each(
							function () {
								this.cjtSyncInputField()
							}
						)

						// Reset changes list.
						this.changes = []

						// Tell blocks page that block is saved and has not changed yet.
						CJTBlocksPage.blockContentChanged( this.block.id, false )

						// Reflect Info bar updated information
						this.infoBar.find( '.block-info-name>strong' ).text( responseBlockData.name.value )
						this.infoBar.find( '.block-shortcode > input:text' ).val( '[cjtoolbox name="' + responseBlockData.name.value + '"]' )

						var blockModifiedDate = new Date( responseBlockData.lastModified.value )

						this.infoBar.find( '.block-modified-date>strong' ).text(
							blockModifiedDate.getDate().toString().padStart( 2, 0 ) + '-' +
							( blockModifiedDate.getMonth() + 1 ).toString().padStart( 2, 0 ) + '-' +
							blockModifiedDate.getFullYear() + ', ' +
							blockModifiedDate.getHours() + ':' +
							blockModifiedDate.getMinutes()
						)

						// Fire BlockSaved event.
						this.onBlockSaved()

						this.block.box.trigger( 'BlockSaved', [ this ] )

					}, this )
				)
				.error( $.proxy(

					function () {

						saveButton.loading( false )

					}, this )

				).complete( $.proxy(

					function ( response ) {

						// Enable block
						this.enable( true )

					}, this )

				)

		}

		/**
		*
		*
		*
		*
		*/
		this._onswitcheditorlang = function ( event, params ) {

			var jLanguageSwitcher = this.block.box.find( '.cjttbl-switch-editor-language' )
			var languageSwitcher = jLanguageSwitcher.get( 0 )

			// Note: Event and params parameter is passed but unused,
			// we need only selectedValue.
			// Set editor mode.
			var editorMode = 'ace/mode/' + params.lang
			this.block.aceEditor.getSession().setMode( editorMode )

			// Save editor language for block.
			this.block.set( 'editorLang', params.lang )

			jLanguageSwitcher.text( CJTJqueryBlockI18N[ 'editorLang_' + params.lang ] )
		}

		/**
		* Event handler for switch block flag.
		*
		* @param event Javascript event object.
		* @param object Toolbox evenr parameters.
		*/
		this._onswitchflag = function ( event, params ) {
			var promise
			var target = $( event.target )
			var oldValue = this.block.get( params.flag )
			var flagButton = this.flaggedActionsToolbox.buttons[ params.flag + '-switch' ]

			// Put the Flag button into load state (Inactive + loading icon).
			flagButton.loading( true )

			// Switch flag state.
			this.block.switchFlag( params.flag, params.newValue ).success( $.proxy(
				function ( rState ) {
					var oldCSSClass = params.flag + '-' + oldValue
					var newCSSClass = params.flag + '-' + rState.value
					target.removeClass( oldCSSClass ).addClass( newCSSClass )
						// Switch title based on current FLAG and the new VALUE.
						.attr( 'title', CJTJqueryBlockI18N[ params.flag + '_' + rState.value + 'Title' ] )
				}, this )
			)

			// Update on server.
			promise = this.block.sync( params.flag )

				.complete( $.proxy(

					function () {
						flagButton.loading( false )

					}, this )

				)

			return promise
		}

		/**
		*
		*
		*
		*
		*/
		this.enable = function ( state ) {
			var elements = this.block.box.find( 'input:checkbox,input:text, textarea, select' )
			switch ( state ) {
				case true: // Enable block.
					elements.removeAttr( 'disabled' )
					break
				case false: // Disable block.
					elements.attr( 'disabled', 'disabled' )
					break
			}

			this.toolbox.enable( state )
			this.flaggedActionsToolbox.enable( state )
			this.editBlockActionsToolbox.buttons[ 'delete' ].enable( state )

			// Enable or Disable ACEEditor.
			// Enable = true then setReadnly = false and vise versa.
			this.block.aceEditor.setReadOnly( !state )
		}

		/**
		* Make block code is the active element.
		*
		* @return false.
		*/
		this.focus = function () {
			this.block.aceEditor.focus()
		}

		/**
		* Initialize Block Plugin object.
		*
		*
		*/
		this.initCJTPluginBase = function ( node, args ) {

			// Initialize object properties!
			var model = this.block = new CJTBlock( this, node )
			this.features = $.extend( defaultOptions, args )

			// Initialize Events.
			this.onBlockSaved = function () { }

			// DOn't TOGGLE block when block name get/lost focus
			model.box.find( 'input:text.block-name' ).click( function ( event ) { event.stopPropagation() } )

			// Load commonly used elements.
			this.elements = {}
			$.each( autoLoadElements, $.proxy(
				function ( name, selector ) {
					this.elements[ name ] = this.block.box.find( selector )
				}, this )
			)

			// Move edit-block-name edit area and tasks-bar outside Wordpress metabox "inside div".
			this.elements.insideMetabox.before( model.box.find( '.edit-block-name, .block-toolbox' ) )

			/*  Info bar won't be exists if the block is initially closed
			*   this is just for the code to avoid writing more IF conditions
			*   block info item will be queried again on the load method
			*/
			this.infoBar = this.block.box.find( '.cjt-info-bar' )

			// HInitialize ooks dropdown list
			this.hooksDropdown = model.box.find( '.hooks-dropdown' ).CJTPLSHooksDropdown( {} )

				/* Revert item value is failed to change location value */
				/* Reflect Hooks icon and text values when successfully changes hook */
				.on( 'change', $.proxy(

					function ( event, dropdown, hookName, jItem ) {

						var currentHookName = model.get( 'location' )

						model.set( 'location', hookName ).error( $.proxy(

							function () {

								dropdown.CJTPLSHooksDropdown( 'setValue', currentHookName )

							} ), this )

							.done( $.proxy(

								function () {

									// Reflect Hook button status when hook changed
									this.toolbox.buttons[ 'location-switch' ].jButton
										.removeClass( 'location-' + currentHookName )
										.addClass( 'location-' + hookName )

										.prop( 'title', jItem.prop( 'title' ) )
										.text( jItem.text() )

										.removeClass( 'bad-location-specified' )
										.prev().removeClass( 'bad-location-specified' )

								}, this )
							)

						model.sync( 'location' )

					}, this )

				).get( 0 ).CJTPLSHooksDropdown

			// Activate toolbox.
			this.toolbox = model.box.find( '.block-toolbox' ).CJTToolBox( {
				context: this,
				handlers: {

					'assignment-info': { callback: this._ongetinfo },
					'block-info': { callback: this._ongetinfo },

					'location-switch': {
						type: 'Popup',
						params: {
							_type: {
								targetElement: '.hooks-dropdown',
								setTargetPosition: false
							}
						}
					},
					'templates': {
						type: 'Popup',
						params: {
							_type: {
								onPopup: this._onpopupmenu,
								targetElement: '.templates',
								setTargetPosition: true
							}
						}
					},
					'templates-lookup': { callback: this._onlookuptemplates },
					'templates-manager': { callback: CJTBlocksPage._onmanagetemplates },
					'code-files': { callback: function () { } } /* This is dummy unless updated by code file controller object on the load method */
				}
			} ).get( 0 ).CJTToolBox

			this.toolbox.buttons[ 'templates' ].jButton.click( $.proxy( this._onlookuptemplates, this ) )

			// Editor Language Toolbox
			this.editorLangsToolbox = model.box.find( '.cjt-toolbox.editor-langs-toolbox' ).CJTToolBox( {

				context: this,
				handlers: {

					'switch-editor-language': {
						type: 'Popup',
						params: {
							// Parameters for PopupList type button.
							_type: {
								onPopup: this._onpopupmenu,
								targetElement: '.editor-langs',
								setTargetPosition: false
							}
						}
					},

					'editor-language-css': { callback: this._onswitcheditorlang, params: { lang: 'css' } },
					'editor-language-html': { callback: this._onswitcheditorlang, params: { lang: 'html' } },
					'editor-language-javascript': { callback: this._onswitcheditorlang, params: { lang: 'javascript' } },
					'editor-language-php': { callback: this._onswitcheditorlang, params: { lang: 'php' } }

				}

			} ).get( 0 ).CJTToolBox

			// Disable Toolbox until block is loaded
			this.toolbox.enable( false )
			this.editorLangsToolbox.enable( false )

			// Move State and Location buttons to be before block name
			this.flaggedActionsToolbox = model.box.find( '.cjt-toolbox.flagged-actions-toolbox' )
				.insertBefore( model.box.find( '.hndle .block-name' ) )
				.CJTToolBox( {

					context: this,
					handlers: {
						'state-switch': { callback: this._onswitchflag, params: { flag: 'state' } },
					}

				} ).get( 0 ).CJTToolBox

			// Move State and Location buttons to be before block name
			this.editBlockActionsToolbox = model.box.find( '.cjt-toolbox.edit-block-toolbox' )
				.insertAfter( model.box.find( '.hndle .block-name' ) )
				.CJTToolBox( {

					context: this,
					handlers: {

						'save': { callback: this._onsavechanges, params: { enable: false } },
						'delete': { callback: this._ondelete },

					}

				} ).get( 0 ).CJTToolBox

			// Initialized-event (Regardless if loaded or not)
			this.block.box.trigger( 'Initialized', [ this ] )

			// If the code editor element is presented then
			// the block is already opened and no need to load later.
			if ( model.box.find( '.code-editor' ).length ) {

				// Load nd Trigger Load events
				this.loadTLE()

			}

			// Display block.
			// !important: Blocks come from server response doesn't need this but the newly added blocks does.
			// need sometime to be ready for display.
			model.box.css( { display: 'block' } ).addClass( 'cjt-block' )
		}

		/**
		*
		*/
		this.initInfoBar = function () {

			this.infoBar = this.block.box.find( '.cjt-info-bar' )

			// Copy Shortcode
			this.infoBar.find( '.block-shortcode .copyshortcode' ).click( $.proxy(

				function () {

					var shortcodeEle = this.infoBar.find( '.block-shortcode input' )

					shortcodeEle.focus()
					shortcodeEle.select()

					document.execCommand( 'copy' )

					return false

				}, this )
			)

			// Update Assignment count whenever the block is saved
			this.block.box.on( 'BlockSaved', $.proxy(

				function () {

					CJTBlocksPage.server.send( 'block', 'getAllAssignment', { blockId: this.block.get( 'id' ) } ).done( $.proxy(

						function ( response ) {

							this.infoBar.find( '.block-assignment-count .show-assignment-info' ).text( response )

						}, this )

					)

				}, this )

			)


			// Editor Language
			this.infoBar.find( '.block-editor-lang strong' ).text( this.block.get( 'editorLang' ) )

			// Allow info bar to be extensible
			this.block.box.trigger( 'InitInfoBar', [ this, this.infoBar ] )
		}

		/**
		*
		*/
		this._onload = function () {
			// Initialize.
			var model = this.block
			// Show loading block progress.
			var loadingPro = $( '<div class="loading-block">' + CJTJqueryBlockI18N.loadingBlock + ' . . .</div>' ).prependTo( this.elements.insideMetabox.prepend() )
			// Retrieve Block HTML content.
			CJTBlocksPage.server.send( 'blocksPage', 'loadBlock', { blockId: model.get( 'id' ), isLoading: true } )
				.success( $.proxy(
					function ( blockContent ) {

						// Remove loading bloc progress.
						loadingPro.remove()

						// Add block content
						this.elements.insideMetabox.append( blockContent.content )

						// Load block.
						this.loadTLE()

					}, this )
				)
		}

		/**
		*
		*/
		this.load = function () {

			var model = this.block

			// Broadcast block event
			this.block.box.trigger( 'cjtBlockLoaded', [ this ] )

			// LOAD MODEL.
			model.load()

			// Editor default options.
			this.block.aceEditor.setOptions( { showPrintMargin: false } )

			// Initialize info bar
			this.initInfoBar()

			// Enable HEader button Toolbox
			this.toolbox.enable( true )
			this.editorLangsToolbox.enable( true )

			// Initialize editor toolbox.
			this.editorToolbox = model.box.find( '.editor-toolbox' ).CJTToolBox( {
				context: this,
				handlers: {}
			} ).get( 0 ).CJTToolBox


			// Default to DOCK!!
			this.defaultDocks = [
				{
					element: $( this.block.aceEditor.container ),
					pixels: 7
				},
				{
					element: this.block.box.find( '.cjpageblock .cjt-panel-genericwnd' ),
					pixels: 64
				},
				{
					element: this.block.box.find( '.cjt-panel-item.cjt-panel-window-templates-lookup' )

						.on( 'CJTDockedItemResized',

							function ( event, item ) {

								if ( CJTToolBox.forms.templatesLookupForm[ model.get( 'id' ) ] !== undefined ) {

									item.height = item.height - 35

									item.element.find( 'iframe' ).css( 'height', item.height )
									CJTToolBox.forms.templatesLookupForm[ model.get( 'id' ) ].inputs.height = item.height
									CJTToolBox.forms.templatesLookupForm[ model.get( 'id' ) ].form.refresh()

								}

							}
						)
						.on( 'CJTBlockExitFullScreen',

							function ( event, item ) {

								if ( CJTToolBox.forms.templatesLookupForm[ model.get( 'id' ) ] !== undefined ) {

									var originalHeight = item.element.height()
									var iframeHeight = originalHeight - 35

									item.element.css( 'height', originalHeight )
									item.element.find( 'iframe' ).css( 'height', iframeHeight )

									CJTToolBox.forms.templatesLookupForm[ model.get( 'id' ) ].inputs.height = iframeHeight
									CJTToolBox.forms.templatesLookupForm[ model.get( 'id' ) ].form.refresh()

								}

							}
						),
					pixels: 27
				}
			]

			// Show hidden toolbox buttons.
			this.editorLangsToolbox.buttons[ 'switch-editor-language' ].jButton.removeClass( 'waitingToLoad' )
			this.block.box.find( '.cjt-toolbox.block-toolbox' ).find( '.waitingToLoad' ).removeClass( 'waitingToLoad' )
			this.editBlockActionsToolbox.buttons[ 'save' ].jButton.removeClass( 'waitingToLoad' )

			// Register COMMAND-KEYS.
			this.registerCommands()

			// Switch Block state if required, if state is empty nothing will happen.
			// Until now only 'restore' state is supported to prevent saving restored block.
			this.switchState( this.features.state )

			// Prepare input elements for notifying user changes.
			this.notifySaveChanges = ( new notifySaveChangesProto( this ) ).initDIFields()

			// Set theme object.

			this.theme = {}

			/*
			this.theme.backgroundColor = 'white';
			this.theme.color = 'black';
			this.theme.altTextColor = 'snow';
			*/

			// LOAD EVENT.
			if ( this.onLoad !== undefined ) {

				this.onLoad()
			}

			// Block Code File.
			this.codeFile = new CJTBlockFile( this )

			this.block.box.find( '.cjpageblock a.close-panel-window' ).click( $.proxy( this._onclosepanelwindow, this ) )

			this.block.box.trigger( 'cjtBlockPostLoading', [ this ] )
		}

		/**
		*
		*/
		this.loadTLE = function () {

			this.block.box.trigger( 'BlockBeforeLoadProc', [ this ] )

			this.load()

			this.block.box.trigger( 'BlockAfterLoadProc', [ this ] )

		}

		/**
		*
		*/
		this.registerCommands = function () {
			var editorCommands = this.block.aceEditor.commands
			var commands = [
				{
					name: 'Save-Changes',
					bindKey: {
						win: 'Ctrl-S',
						mac: 'Command-J'
					},
					exec: $.proxy( this._onsavechanges, this )
				}
			]
			/** Add Our Ace Save, Full screen and Code-Auto-Completion commands */
			editorCommands.addCommands( commands )
		}

		/**
		*
		*/
		this.restoreRevision = function ( revisionId, data ) {
			// Create new revision control action.
			this.revisionControl = new CJTBlockOptionalRevision( this, data, revisionId )
			// Display the revision + enter revision mode.
			this.revisionControl.display()
		}

		/**
		*
		*/
		this.setFeatures = function ( features ) {
			this.features = features
		}

		/**
		*
		*/
		this.showPanelGenericWindow = function ( event, url ) {

			var genericPanelWindow = $( '.cjpageblock .cjt-panel-genericwnd' )

			$.get( url ).done( $.proxy(

				function ( content ) {

					genericPanelWindow
						.empty()
						.append( content )

					this._onPaneledItems( event )

				}, this )

			)
		}

		/*
		*
		*
		*
		*/
		this.switchState = function ( state ) {
			switch ( state ) {
				case 'restore':
					// Hide block toolbox.
					this.toolbox.jToolbox.hide()
					// Disable all fields.
					this.enable( false )
					// Change state
					this.state = 'restore'
				default:
					// Nothing for now
					break
			}
		}

	} // End class.

	/**
	*	jQuery Plugin interface.
	*/
	$.fn.CJTBlock = function ( args ) {
		/**
		* Process every block object.
		*/
		return this.each( function () {

			// If this is the first time to be called for this element
			// create new CJTBlockPlugin object for the this element.
			if ( this.CJTBlock == undefined ) {
				this.CJTBlock = new CJTBlockPlugin( this, args )
			}
			else {
				// Otherwise change options
				this.CJTBlock.setOptions( args )
			}
			return this
		} )

	} // End Plugin class.

} )( jQuery )