( function( $ ) {

	'use strict';

	var LaStudioKitEditor = {

		activeSection: null,

		editedElement: null,

		activedSubTab: null,

		modal: false,

		modalConditions: false,

		init: function() {

			window.elementor.channels.editor.on( 'section:activated', LaStudioKitEditor.onAnimatedBoxSectionActivated );

			window.elementor.channels.editor.on( 'section:activated', LaStudioKitEditor.onSearchSectionActivated );

			window.elementor.on( 'preview:loaded', function() {
				window.elementor.$preview[0].contentWindow.LaStudioKitEditor = LaStudioKitEditor;
				LaStudioKitEditor.onPreviewLoaded();
			});

			$(document).on('lakit:editor:tab_active', LaStudioKitEditor.onTabActive );

			$(document).on('click', '#elementor-panel .elementor-control.elementor-control-type-tab', function (e){
				var classList = this.classList.toString().match(/\selementor-control-([A-Za-z0-9._%-]*)\s/);
				var _tab_active = typeof classList[1] !== "undefined" ? classList[1] : null;
				window.LaStudioKitEditor.activedSubTab = _tab_active;
				$(document).trigger('lakit:editor:tab_active', [_tab_active]);
			});

			LaStudioKitEditor.fixSmallBrowser();

		},

		fixSmallBrowser: function (){
			if(elementor.getPreferences('lakit_fix_small_browser') == 'yes'){
				$('body').addClass('fix-small-browser');
			}
			else{
				$('body').removeClass('fix-small-browser');
			}
			elementor.settings.editorPreferences.addChangeCallback('lakit_fix_small_browser', function(val){
				if(val == 'yes'){
					$('body').addClass('fix-small-browser');
				}
				else{
					$('body').removeClass('fix-small-browser');
				}
			});
		},

		onSearchSectionActivated: function( sectionName, editor ) {

			var editedElement = editor.getOption( 'editedElementView' );

			if ( 'lakit-search' !== editedElement.model.get( 'widgetType' ) ) {
				return;
			}

			window.LaStudioKitEditor.activeSection = sectionName;

			var isPopup = -1 !== [ 'section_popup_style', 'section_popup_close_style', 'section_form_style' ].indexOf( sectionName );

			if ( isPopup ) {
				editedElement.$el.find( '.lakit-search' ).addClass( 'lakit-search-popup-active' );
			} else {
				editedElement.$el.find( '.lakit-search' ).removeClass( 'lakit-search-popup-active' );
			}

		},

		onTabActive: function(event, _tab_active){
			var editedElement = window.LaStudioKitEditor.editedElement;
			if(editedElement){
				if('lakit-animated-box' === editedElement.model.get( 'widgetType' )){
					var	allowActiveTabs = ['tab_back_general_styles','tab_back_box_inner_styles', 'tab_back_icon_styles', 'tab_back_title_styles', 'tab_back_subtitle_styles', 'tab_back_description_styles', 'tab_back_overlay', 'tab_back_order'],
						allowDeactiveTabs = ['tab_front_general_styles', 'tab_front_box_inner_styles', 'tab_front_icon_styles', 'tab_front_title_styles', 'tab_front_subtitle_styles', 'tab_front_description_styles', 'tab_front_overlay', 'tab_front_order'];
					if(allowActiveTabs.includes(_tab_active)){
						editedElement.$el.find( '.lakit-animated-box' ).addClass( 'flipped' );
						editedElement.$el.find( '.lakit-animated-box' ).addClass( 'flipped-stop' );
					}
					else if(allowDeactiveTabs.includes(_tab_active)){
						editedElement.$el.find( '.lakit-animated-box' ).removeClass( 'flipped' );
						editedElement.$el.find( '.lakit-animated-box' ).removeClass( 'flipped-stop' );
					}
				}
			}
		},

		onAnimatedBoxSectionActivated: function( sectionName, editor ) {

			window.LaStudioKitEditor.activedSubTab = null;

			var editedElement = editor.getOption( 'editedElementView' ),
				prevEditedElement = window.LaStudioKitEditor.editedElement;

			if ( prevEditedElement && 'lakit-animated-box' === prevEditedElement.model.get( 'widgetType' ) ) {

				prevEditedElement.$el.find( '.lakit-animated-box' ).removeClass( 'flipped' );
				prevEditedElement.$el.find( '.lakit-animated-box' ).removeClass( 'flipped-stop' );

				window.LaStudioKitEditor.editedElement = null;
			}

			if ( 'lakit-animated-box' !== editedElement.model.get( 'widgetType' ) ) {
				return;
			}

			editedElement.$el.find( '.lakit-animated-box' ).addClass( 'flipped' );
			editedElement.$el.find( '.lakit-animated-box' ).addClass( 'flipped-stop' );

			window.LaStudioKitEditor.editedElement = editedElement;
			window.LaStudioKitEditor.activeSection = sectionName;

			var isBackSide = -1 !== [ 'section_back_content', 'section_action_button_style' ].indexOf( sectionName );

			if ( isBackSide ) {
				editedElement.$el.find( '.lakit-animated-box' ).addClass( 'flipped' );
				editedElement.$el.find( '.lakit-animated-box' ).addClass( 'flipped-stop' );
			} else {
				editedElement.$el.find( '.lakit-animated-box' ).removeClass( 'flipped' );
				editedElement.$el.find( '.lakit-animated-box' ).removeClass( 'flipped-stop' );
			}

		},

		onPreviewLoaded: function() {
			var elementorFrontend = $('#elementor-preview-iframe')[0].contentWindow.elementorFrontend;

			elementorFrontend.elements.$document.on('click', '.lakit-tabs__edit-cover', LaStudioKitEditor.showTemplatesModal );
			elementorFrontend.elements.$document.on('click', '.lastudio-kit-edit-template-link', LaStudioKitEditor.showTemplatesModal );
			elementorFrontend.elements.$document.on('click', '.lakit-tabs-new-template-link', function (e){
				window.location.href = $( this ).attr( 'href' );
			} );

			LaStudioKitEditor.getModal().on( 'hide', function() {
				window.elementor.reloadPreview();
			});
		},

		showTemplatesModal: function(evt) {
			if(evt){
				evt.preventDefault();
			}
			var editLink = $( this ).data( 'template-edit-link' );

			LaStudioKitEditor.showModal( editLink );
		},

		showModal: function( link ) {
			var $iframe,
				$loader;

			LaStudioKitEditor.getModal().show();

			$( '#lakit-tabs-template-edit-modal .dialog-message').html( '<iframe src="' + link + '" id="lakit-tabs-edit-frame" width="100%" height="100%"></iframe>' );
			$( '#lakit-tabs-template-edit-modal .dialog-message').append( '<div id="lakit-tabs-loading"><div class="elementor-loader-wrapper"><div class="elementor-loader"><div class="elementor-loader-boxes"><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div></div></div><div class="elementor-loading-title">Loading</div></div></div>' );

			$iframe = $( '#lakit-tabs-edit-frame' );
			$loader = $( '#lakit-tabs-loading');

			$iframe.on( 'load', function() {
				$loader.fadeOut( 300 );
			} );
		},

		getModal: function() {
			if ( ! LaStudioKitEditor.modal ) {
				this.modal = elementor.dialogsManager.createWidget( 'lightbox', {
					id: 'lakit-tabs-template-edit-modal',
					closeButton: true,
					hide: {
						onBackgroundClick: false
					}
				} );
			}
			return LaStudioKitEditor.modal;
		},

		getDropdownLabel: function ( a1, a2 ){
			console.log(a1);
			console.log(a2);
		}
	};

	$( window ).on( 'elementor:init', LaStudioKitEditor.init );

	window.LaStudioKitEditor = LaStudioKitEditor;

}( jQuery ) );
