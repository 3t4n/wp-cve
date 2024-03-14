(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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
		 
	$(window).load(function() {
		$('.treechart-color-field').wpColorPicker();

		function addSection() {
			var addSection = $("#add-section").dialog({
				autoOpen: false,
				height: 200,
				width: 350,
				modal: true,
				buttons: {
					'Add Section': function() {
						$.post(ajaxurl, {
							action: 'add_book_section',
							name: $("#name").val(),
							post_ID: $('#post_ID').val()
						}).done(function(data) {
							addSection.dialog('close');
							data = JSON.parse(data);
							var html = '\
								<li class="section connectedSortable clear" data-id="' + data.ID + '">\
									<div class="section-inner">\
										<a href="' + data.edit_link + '">' + data.post_title + '</a>\
										<span class="btngroup">\
											<button type="button" class="add-book-element">\
												<span class="dashicons dashicons-plus-alt"></span>\
											</button>\
											<button type="button" class="drag">\
												<span class="dashicons dashicons-menu"></span>\
											</button>\
											<button type="button" class="delete">\
												<span class="dashicons dashicons-trash"></span>\
											</button>\
										</span>\
									</div>\
									<ul class="elements connectedSortable clear">\
									</ul>\
								</li>';
							$('.sections').append(html);
							addElement();
							deleteSectionElement();
							sectionsSortable();
						}).fail(function(data) {
							console.log(data);
						});
					},
					Cancel: function() {
						addSection.dialog('close');
					}
				},
				close: function() {}
			});
			$('.add-book-section').button().on("click", function() {
				addSection.dialog("open");
			});
		}

		function addElement() {
			var addElement = $("#add-element").dialog({
				autoOpen: false,
				height: 200,
				width: 350,
				modal: true,
				buttons: {
					'Add Element': function() {
						var id = addElement.data('id');
						$.post(ajaxurl, {
							action: 'add_book_element',
							name: $("#element_name").val(),
							post_ID: id
						}).done(function(data) {
							data = JSON.parse(data);
							console.log($('[data-id=' + id + ']'));
							addElement.dialog('close');
							var html = '\
								<li class="element clear" data-id="' + data.ID + '">\
									<table width="100%">\
										<tr>\
											<td width="30"><div style="width: 200px;">\
												<a href="' + data.edit_link + '">' + data.post_title + '</a>\
											</div></td>\
											<td width="30"><div style="width: 150px;">\
												<input type="checkbox" checked> Printing\
											</div></td>\
											<td width="30"><div style="width: 150px;">\
												Word Count - Off\
											</div></td>\
											<td width="30"><div style="width: 150px;">\
												Page # -\
											</div></td>\
											<td width="100">\
												<input type="checkbox"> TOC\
											</td>\
											<td width="10"><div style="width: 150px; float: right;">\
												<span class="btngroup">\
													<button type="button" class="drag">\
														<span class="dashicons dashicons-menu"></span>\
													</button>\
													<button type="button" class="delete">\
														<span class="dashicons dashicons-trash"></span>\
													</button>\
												</span></div>\
											</td>\
										</tr>\
									</table>\
								</li>\
								';
							$('[data-id=' + id + ']').find('.elements').append(html);
							deleteSectionElement();
							elementsSortable();
						}).fail(function(data) {
							console.log(data);
						});
					},
					Cancel: function() {
						addElement.dialog('close');
					}
				},
				close: function() {}
			});
			$('.add-book-element').button().on("click", function() {
				var id = $(this).parents('.section').data('id');
				addElement.data('id', id).dialog("open");
			});
		}

		function deleteSectionElement() {
			var deleteSectionElement = $("#delete-section-element").dialog({
				autoOpen: false,
				height: 200,
				width: 350,
				modal: true,
				buttons: {
					'Yes, delete it!': function() {
						var id = deleteSectionElement.data('id');
						console.log(id);
						$.post(ajaxurl, {
							action: 'delete_book_section_element',
							post_ID: id,
						}).done(function(data) {
							console.log(data);
							$('[data-id=' + data + ']').remove();
							deleteSectionElement.dialog('close');
						}).fail(function(data) {
							console.log(data);
						});
					},
					Cancel: function() {
						deleteSectionElement.dialog('close');
					}
				},
				close: function() {}
			});
			$('.delete').button().on("click", function() {
				var id = $(this).parents('li').data('id');
				deleteSectionElement.data('id', id).dialog("open");
			});
		}

		function sectionsSortable(){
			$('.sections').sortable({
				connectWith: ".sections",
				axis: 'y',
				handle: '.drag',
				cancel: '',
				update: function(event, ui) {
					$('.sections').addClass('wait');
					var neworeder = $(this).find('.section');
					var oreder = [];
					for (var i = 0; i < neworeder.length; i++) {
						oreder[i] = $(neworeder[i]).data('id');
					}

					$.post(ajaxurl, {
						action: 'update_menu_order',
						'post_IDs': oreder.toString()
					}).done(function(data) {
					$('.sections').removeClass('wait');

					}).fail(function(data) {
					$('.sections').removeClass('wait');

					});
					
				},
			}).disableSelection();
		}

		function elementsSortable(){
			$('.elements').sortable({
				connectWith: ".elements",

				handle: '.drag',
				cancel: '',
				update: function(event, ui) {
					$('.sections').addClass('wait');
					
					var neworeder = $(this).find('.element');
					var neworeder_parent = $(this).parents('.section').data('id');
					var oreder = [];
					for (var i = 0; i < neworeder.length; i++) {
						oreder[i] = $(neworeder[i]).data('id');
					}
					$.post(ajaxurl, {
						action: 'update_menu_order',
						'post_IDs': oreder.toString(),
						'post_IDs_parent': neworeder_parent,
					}).done(function(data) {
					$('.sections').removeClass('wait');

					}).fail(function(data) {
					$('.sections').removeClass('wait');

					});
				},
			}).disableSelection();

		}





		addSection()
		addElement();
		deleteSectionElement();
		sectionsSortable();
		elementsSortable();



	});


	$(window).load(function(){
		$('.inline-update').each(function(){
			$(this).change(function(){
					$('.sections').addClass('wait');

				if($(this).is(':checked')) {
					var checked = 'on';
				} else {
					var checked = 'null';
				}

				var checked = checked;
				var name = $(this).data('name');
				var elid = $(this).data('elid');

					$.post(ajaxurl, {
						action: 'inline_update_el_meta',
						'id': elid,
						'meta': name,
						'value': checked,
					}).done(function(data) {
					$('.sections').removeClass('wait');

					}).fail(function(data) {
						$('.sections').removeClass('wait');
					});
			})
		})













	})

})( jQuery );
