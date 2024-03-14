/* global wpforms_builder, wpf */
'use strict';

var WPFormsBuilderGooglesheets = window.WPFormsBuilderGooglesheets || ( function( document, window, $ ) {

	/**
	 * Public functions and properties.
	 *
	 * @since 1.0.0
	 *
	 * @type {object}
	 */
	var app = {

		/**
		 * jQuery selector for holder.
		 *
		 * @since 1.0.0
		 *
		 * @type {object}
		 */
		$holder: $( '.wpforms-panel-content-section-wf_googlesheets' ),

		/**
		 * Start the engine.
		 *
		 * @since 1.0.0
		 */
		init: function() {

			// Do that when DOM is ready.
			$( document ).ready( app.ready );
		},

		/**
		 * DOM is fully loaded.
		 *
		 * @since 1.0.0
		 */
		ready: function() {

			app.events();
			$('#wpforms-panel-field-wpgs_spreadsheets-1-gs_sheet_integration_mode option[value="automatic_disabled"]').prop('disabled', true);
			
		},

		/**
		 * Register JS events.
		 *
		 * @since 1.0.0
		 */
		events: function() {

			$( '#wpforms-builder' )
				.on( 'wpformsSaved', app.wpformsSaved )
				.on( 'wpformsFieldAdd', app.showReloadWarning )
				.on( 'wpformsFieldDelete', app.showReloadWarning )
				.on( 'change.wpformsGooglesheets', '#wpforms-panel-field-settings-gsheetconnector-wpforms', app.googlesheetsToggle )

				.on( 'change', '.select_spreadsheet_input', app.populateSheetInfo)
				.on( 'change', '.select_tabs_input, .select_spreadsheet_input', app.populateSheetLink)
				.on( 'click', '.force_reload p a', app.saveAndReload);
			
			
		},
		
		
		
		populateSheetLink: function( e, data ) {
			var $parent = $(this).parents(".wpforms-builder-settings-block-content");
			
			var $sheet_id = $parent.find(".select_spreadsheet_input").val();
			var $tab_id = $parent.find(".select_tabs_input").val();

			var $link = "https://docs.google.com/spreadsheets/d/"+$sheet_id+"/edit#gid="+$tab_id+"";
			
			$parent.find("a.google_sheet_url").attr("href", $link);
		},
		
		populateSheetLink2: function( $element ) {
			var $parent = $element.parents(".wpforms-builder-settings-block-content");
			
			var $sheet_id = $parent.find(".select_spreadsheet_input").val();
			var $tab_id = $parent.find(".select_tabs_input").val();

			var $link = "https://docs.google.com/spreadsheets/d/"+$sheet_id+"/edit#gid="+$tab_id+"";
			
			$parent.find("a.google_sheet_url").attr("href", $link);
		},
		
		populateSheetInfo: function( e, data ) {
			//console.log($(this).val());
			var $selected_option = $(this).val();
			var $parent = $(this).parents(".wpforms-builder-settings-block-content");
				
			if( $selected_option == "create_new" ){
				$parent.find(".gs_sheet_select_tab_wrapper").hide();
				$parent.find(".gs_sheet_create_new_name_wrapper").show();
			}
			else {
				
				$parent.find(".gs_sheet_select_tab_wrapper").show();
				$parent.find(".gs_sheet_create_new_name_wrapper").hide();
				
				var $sheets = $("#gs_sheet_select_sheets_list").val();
				var $decodeSheets = JSON.parse($sheets);
				
				
				$.each( $decodeSheets, function(sheetName, sheetObject) {			
					var $sheetID = sheetObject.id;				
					var $tab_options = "";
					
					if( $sheetID == $selected_option ) {
						$.each( sheetObject.tabId, function(tabName, tabID) {			
							$tab_options += '<option value="'+tabID+'">'+tabName+'</option>';
						});
						
						$parent.find(".select_tabs_input").html($tab_options);
					}
					
				} );
				
			}
		},
		
		saveAndReload: function( e, data ) {
			$(".wfgs_force_reload").val(1);
			WPFormsBuilder.formSave();
		},
		
		showReloadWarning: function( e, data ) {
			$( ".reload_warning" ).show();		 
		},
		
		
		wpformsSaved: function( e, data ) {
			
			if( typeof data != 'undefined' && typeof data.force_reload != 'undefined' && data.force_reload == 1 ) {
				
				window.location.reload(true);
			}
		},

		/**
		 * Toggle the displaying googlesheet settings depending on if the
		 * googlesheets are enabled.
		 *
		 * @since 1.0.0
		 */
		googlesheetsToggle: function() {

			app.$holder
				.find( '.wpforms-builder-settings-block-googlesheet, .wpforms-webooks-add' )
				.toggleClass( 'hidden', '0' === $( this ).val() );
		},

	};

	// Provide access to public functions/properties.
	return app;

}( document, window, jQuery ) );

WPFormsBuilderGooglesheets.init();

