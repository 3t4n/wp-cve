(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(document).ready(function (){
		if ($('#tableini').length) {
			$('#tableini').DataTable({});
		}

		if ($('#dataTableNot2').length) {
			$('#dataTableNot2').DataTable({
				searching: false,
				ordering: false,
				paging: false,
				"bInfo" : false
			});
		}

		if ($('.grid-masonry-wrap').length) {
			$('.grid-masonry-wrap').masonry({
				// options
				itemSelector: '.grid-item-loop'
			});
		}

		if ($('.clockpicker').length) {
			$('.clockpicker').clockpicker({
				placement: 'top',
				align: 'left',
				donetext: 'Done'
			});
		}

		if ($('input.single-daterange').length) {
			$('input.single-daterange').daterangepicker({
				"singleDatePicker": true
			});
		}

		if ($('input.single-daterange2').length) {
			$('input.single-daterange2').daterangepicker({
				"singleDatePicker": true
			});
		}

		if ($('input.multi-daterange').length) {
			$('input.multi-daterange').daterangepicker({
				"startDate": "03/28/2017",
				"endDate": "04/06/2017"
			});
		}

		if ($('#file-3').length) {
			var sakolaUploadField = document.getElementById("file-3");

			sakolaUploadField.onchange = function() {
				var FileSize = this.files[0].size / 1024 / 1024; // in MB
				if(FileSize > 10){
					alert("File is too big!");
					this.value = "";
				};
			};
		}

		if ($('#second-container').length) {
			$('#second-container').simplePagination({
				items_per_page: 1
			});
		}

		//skwp table questions
		if ($('#skwp-table-questions').length) {
			var table = $('#skwp-table-questions').DataTable({
				'columnDefs': [
				{
					'targets': 0,
					'checkboxes': {
						'selectRow': true
					}
				}
			],
			'select': {
				'style': 'multi'
			},
				'order': [[0, 'asc']]
			});
			 
			$('#skwp-table-questions tbody').on( 'click', 'tr', function () {
				$(this).find('input[type="checkbox"]').trigger('click');
			});

			$('#skwp-table-questions tbody').on('click', 'input[type="checkbox"]', function(e){
				var $row = $(this).closest('tr');
			});

			$('#frm-questions-delete').on('submit', function(e) {
				var form = this;

				var rows_selected = table.column(0).checkboxes.selected();

				$.each(rows_selected, function(index, rowId) {
				// Create a hidden element
				$(form).append(
					$('<input>')
						.attr('type', 'hidden')
						.attr('name', 'id_question[]')
						.val(rowId)
				);
			});

			$('input[name="id\[\]"]', form).remove();
			});
		} //skwp table questions end

		//skwp table questions 2
		if ($('#skwp-table-questions').length) {
			var table = jQuery('#skwp_questions_table').DataTable({
				'columnDefs': [
					{
						'targets': 0,
						'checkboxes': {
							'selectRow': true
						}
					}
				],
				'select': {
					'style': 'multi'
				},
				'order': [[0, 'asc']]
			});

			jQuery('#skwp_questions_table tbody').on( 'click', 'tr', function () {
				jQuery(this).find('input[type="checkbox"]').trigger('click');
			});

			jQuery('#skwp_questions_table tbody').on('click', 'input[type="checkbox"]', function(e){
				var $row = jQuery(this).closest('tr');
			});

			jQuery('#frm-questions').on('submit', function(e) {
				var form = this;

				var rows_selected = table.column(0).checkboxes.selected();

				jQuery.each(rows_selected, function(index, rowId) {
					jQuery(form).append(
						jQuery('<input>')
							.attr('type', 'hidden')
							.attr('name', 'id_question[]')
							.val(rowId)
					);
				});
				jQuery('input[name="id\[\]"]', form).remove();
			});
		} //skwp table questions 2 end
	});
	

})( jQuery );
