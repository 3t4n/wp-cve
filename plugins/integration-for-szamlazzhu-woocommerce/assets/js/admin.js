jQuery(document).ready(function($) {

	//Settings page
	var wc_szamlazz_settings = {
		settings_groups: ['accounts', 'coupon', 'vatnumber', 'emails', 'email-notify', 'receipt', 'accounting', 'automation', 'vat-override', 'eusafa', 'advanced'],
		$additional_account_table: $('.wc-szamlazz-settings–inline-table-accounts'),
		$notes_table: $('.wc-szamlazz-settings-notes'),
		$automations_table: $('.wc-szamlazz-settings-automations'),
		$vat_overrides_table: $('.wc-szamlazz-settings-vat-overrides'),
		$eusafa_table: $('.wc-szamlazz-settings-eusafas'),
		$advanced_table: $('.wc-szamlazz-settings-advanced-options'),
		activation_nonce: '',
		init: function() {
			this.init_toggle_groups();
			this.toggle_sections();

			$('.wc-szamlazz-settings-section-title').on('click', this.toggle_section);

			//Activate/deactivate pro version
			this.activation_nonce = $('.wc-szamlazz-settings-sidebar').data('nonce');
			$('#woocommerce_wc_szamlazz_pro_email').keypress(this.submit_pro_on_enter);
			$('#wc_szamlazz_activate_pro').on('click', this.submit_activate_form);
			$('#wc_szamlazz_deactivate_pro').on('click', this.submit_deactivate_form);
			$('#wc_szamlazz_validate_pro').on('click', this.submit_validate_form);

			$('.wc-szamlazz-settings-widget-rating .button-secondary').on('click', this.hide_rate_request);
			$('.wc-szamlazz-settings-widget-addons .button-secondary').on('click', this.hide_addons);

			$('.wc_szamlazz_receipt_templates_preview').tipTip( {
				'content': '<div class="wc_szamlazz_receipt_templates_preview_image"></div>',
				'fadeIn': 50,
				'fadeOut': 50,
				'delay': 200,
				'enter': function() {
					$('#tiptip_holder').css({'max-width': '270px'});
					$('#tiptip_content').addClass('wc_szamlazz_tiptip_wider');
				},
				exit: function() {
					$('#tiptip_holder').css({'max-width': '200px'});
					$('#tiptip_content').removeClass('wc_szamlazz_tiptip_wider');
				}
			});

			//Show loading indicators
			var document_types = ['invoice', 'proform', 'deposit', 'void', 'delivery'];
			var nonce = $('#wc_szamlazz_load_email_ids_nonce').data('nonce');
			document_types.forEach(function(type){
				var $select = $('#woocommerce_wc_szamlazz_email_attachment_'+type).parent();
				$select.block({
					message: null,
					overlayCSS: {
						background: '#F5F5F5 url(' + wc_szamlazz_params.loading + ') no-repeat center',
						backgroundSize: '16px 16px',
						opacity: 0.6
					}
				});
			});

			//Load email id values
			var data = {
				action: 'wc_szamlazz_get_email_ids',
				nonce: nonce
			};

			$.post(ajaxurl, data, function(response) {
				response.data.forEach(function(select){
					var selectField = $('#woocommerce_wc_szamlazz_email_attachment_'+select.field);
					select.options.forEach(function(field){
						var option = new Option(field.label, field.id, false, field.selected);
						selectField.append(option).trigger('change');
					});
					selectField.parent().unblock();
				});
			});

			//Load additional accounts table
			this.$additional_account_table.find('tfoot a').on('click', this.add_now_account_row);
			this.$additional_account_table.on('click', 'a.delete-row', this.delete_account_row);
			this.$additional_account_table.on('change', 'select', this.change_account_select_class);

			if(this.$additional_account_table.find('tbody tr').length < 1) {
				this.add_now_account_row();
			}

			//Conditional logic controls
			var conditional_fields = [this.$notes_table, this.$vat_overrides_table, this.$eusafa_table, this.$automations_table, this.$advanced_table];
			var conditional_fields_ids = ['notes', 'vat_overrides', 'eusafas', 'automations', 'advanced_options'];

			//Setup conditional fields for notes, vat rates and automations
			conditional_fields.forEach(function(table, index){
				var id = conditional_fields_ids[index];
				var singular = id.slice(0, -1);
				singular = singular.replace('_', '-');
				table.on('change', 'select.condition', {group: id}, wc_szamlazz_settings.change_x_condition);
				table.on('change', 'select.wc-szamlazz-settings-repeat-select', function(){wc_szamlazz_settings.reindex_x_rows(id)});
				table.on('click', '.add-row', {group: id}, wc_szamlazz_settings.add_new_x_condition_row);
				table.on('click', '.delete-row', {group: id}, wc_szamlazz_settings.delete_x_condition_row);
				table.on('change', 'input.condition', {group: id}, wc_szamlazz_settings.toggle_x_condition);
				table.on('click', '.delete-'+singular, {group: id}, wc_szamlazz_settings.delete_x_row);
				$('.wc-szamlazz-settings-'+singular+'-add a:not([data-disabled]').on('click', {group: id, table: table}, wc_szamlazz_settings.add_new_x_row);

				//If we already have some notes, append the conditional logics
				table.find('ul.conditions[data-options]').each(function(){
					var saved_conditions = $(this).data('options');
					var ul = $(this);

					saved_conditions.forEach(function(condition){
						var sample_row = $('#wc_szamlazz_'+id+'_condition_sample_row').html();
						sample_row = $(sample_row);
						sample_row.find('select.condition').val(condition.category);
						sample_row.find('select.comparison').val(condition.comparison);
						sample_row.find('select.value').removeClass('selected');
						sample_row.find('select[data-condition="'+condition.category+'"]').val(condition.value).addClass('selected').attr('disabled', false);
						ul.append(sample_row);
					});
				});

				if(table.find('.wc-szamlazz-settings-'+singular).length < 1) {
					$('.wc-szamlazz-settings-'+singular+'-add a:not([data-disabled]').trigger('click');
				}

				//Reindex the fields
				wc_szamlazz_settings.reindex_x_rows(id);

			});

		},
		init_toggle_groups: function() {
			$.each(wc_szamlazz_settings.settings_groups, function( index, value ) {
				var checkbox = $('.wc-szamlazz-toggle-group-'+value);
				var group_items = $('.wc-szamlazz-toggle-group-'+value+'-item').parents('tr');
				var group_items_hide = $('.wc-szamlazz-toggle-group-'+value+'-item-hide').parents('tr');
				var single_items_hide = $('.wc-szamlazz-toggle-group-'+value+'-cell-hide');
				var single_items_show = $('.wc-szamlazz-toggle-group-'+value+'-cell-show');
				var checked = checkbox.is(":checked");

				if(value == 'emails' && $('.wc-szamlazz-toggle-group-'+value+':checked').length) {
					checked = true;
				}

				if(checked) {
					group_items.show();
					group_items_hide.hide();
					single_items_hide.hide();
					single_items_show.show();
				} else {
					group_items.hide();
					group_items_hide.show();
					single_items_hide.show();
					single_items_show.hide();
				}
				checkbox.change(function(e){
					e.preventDefault();

					var checked = $(this).is(":checked");
					if(value == 'emails' && $('.wc-szamlazz-toggle-group-'+value+':checked').length) {
						checked = true;
					}

					if(checked) {
						group_items.show();
						group_items_hide.hide();
						single_items_hide.hide();
						single_items_show.show();
					} else {
						group_items.hide();
						group_items_hide.show();
						single_items_hide.show();
						single_items_show.hide();
					}
				});
			});
		},
		submit_pro_on_enter: function(e) {
			if (e.which == 13) {
				$('#wc_szamlazz_activate_pro').click();
				return false;
			}
		},
		submit_activate_form: function() {
			var key = $('#woocommerce_wc_szamlazz_pro_key').val();
			var button = $(this);
			var form = button.parents('.wc-szamlazz-settings-widget');

			var data = {
				action: 'wc_szamlazz_license_activate',
				key: key,
				nonce: wc_szamlazz_settings.activation_nonce
			};

			form.block({
				message: null,
				overlayCSS: {
					background: '#ffffff url(' + wc_szamlazz_params.loading + ') no-repeat center',
					backgroundSize: '16px 16px',
					opacity: 0.6
				}
			});

			form.find('.wc-szamlazz-settings-widget-pro-notice').hide();

			$.post(ajaxurl, data, function(response) {
				//Remove old messages
				if(response.success) {
					window.location.reload();
					return;
				} else {
					form.find('.wc-szamlazz-settings-widget-pro-notice p').html(response.data.message);
					form.find('.wc-szamlazz-settings-widget-pro-notice').show();
				}
				form.unblock();
			});

			return false;
		},
		submit_deactivate_form: function() {
			var button = $(this);
			var form = button.parents('.wc-szamlazz-settings-widget');

			var data = {
				action: 'wc_szamlazz_license_deactivate',
				nonce: wc_szamlazz_settings.activation_nonce
			};

			form.block({
				message: null,
				overlayCSS: {
					background: '#ffffff url(' + wc_szamlazz_params.loading + ') no-repeat center',
					backgroundSize: '16px 16px',
					opacity: 0.6
				}
			});

			form.find('.notice').hide();

			$.post(ajaxurl, data, function(response) {
				//Remove old messages
				if(response.success) {
					window.location.reload();
					return;
				} else {
					form.find('.notice p').html(response.data.message);
					form.find('.notice').show();
				}
				form.unblock();
			});
			return false;
		},
		submit_validate_form: function() {
			var button = $(this);
			var form = button.parents('.wc-szamlazz-settings-widget');

			var data = {
				action: 'wc_szamlazz_license_validate',
				nonce: wc_szamlazz_settings.activation_nonce
			};

			form.block({
				message: null,
				overlayCSS: {
					background: '#ffffff url(' + wc_szamlazz_params.loading + ') no-repeat center',
					backgroundSize: '16px 16px',
					opacity: 0.6
				}
			});

			form.find('.notice').hide();

			$.post(ajaxurl, data, function(response) {
				window.location.reload();
			});
			return false;
		},
		hide_rate_request: function() {
			var nonce = $(this).data('nonce');
			var form = $(this).parents('.wc-szamlazz-settings-widget');
			var data = {
				action: 'wc_szamlazz_hide_rate_request',
				nonce: nonce
			};

			form.block({
				message: null,
				overlayCSS: {
					background: '#ffffff url(' + wc_szamlazz_params.loading + ') no-repeat center',
					backgroundSize: '16px 16px',
					opacity: 0.6
				}
			});

			$.post(ajaxurl, data, function(response) {
				form.slideUp();
			});
		},
		hide_addons: function() {
			var nonce = $(this).data('nonce');
			var form = $(this).parents('.wc-szamlazz-settings-widget');
			var data = {
				action: 'wc_szamlazz_hide_addons',
				nonce: nonce
			};

			form.block({
				message: null,
				overlayCSS: {
					background: '#ffffff url(' + wc_szamlazz_params.loading + ') no-repeat center',
					backgroundSize: '16px 16px',
					opacity: 0.6
				}
			});

			$.post(ajaxurl, data, function(response) {
				form.slideUp();
			});
		},
		toggle_section: function() {
			$(this).toggleClass('open');

			//Remember selection
			var sections = [];
			$('.wc-szamlazz-settings-section-title.open').each(function(){
				sections.push($(this).find('h3').attr('id'));
			});
			localStorage.setItem('wc_szamlazz_settings_open_sections', JSON.stringify(sections));
		},
		toggle_sections: function() {
			var data = JSON.parse(localStorage.getItem('wc_szamlazz_settings_open_sections'));
			if(data) {
				data.forEach(function(section_id){
					$('#'+section_id).parent().addClass('open');
				});
			} else {
				$('#woocommerce_wc_szamlazz_section_auth').parent().addClass('open');
				$('#woocommerce_wc_szamlazz_section_invoice').parent().addClass('open');
			}
		},
		add_now_account_row: function() {
			var sample_row = $('#wc_szamlazz_additional_accounts_sample_row').html();
			wc_szamlazz_settings.$additional_account_table.find('tbody').append(sample_row);
			wc_szamlazz_settings.reindex_account_rows();
			return false;
		},
		delete_account_row: function() {
			var row = $(this).closest('tr').remove();
			wc_szamlazz_settings.reindex_account_rows();

			//Add empty row if no rows left
			if(wc_szamlazz_settings.$additional_account_table.find('tbody tr').length < 1) {
				wc_szamlazz_settings.add_now_account_row();
			}

			return false;
		},
		reindex_account_rows: function() {
			var sample_row = $('#wc_szamlazz_additional_accounts_sample_row').html();
			wc_szamlazz_settings.$additional_account_table.find('tbody tr').each(function(index){
				$(this).find('input, select').each(function(){
					var name = $(this).data('name');
					name = name.replace('X', index);
					$(this).attr('name', name);
				});
			});
			return false;
		},
		change_account_select_class: function() {
			if(this.selectedIndex === 0) {
				$(this).addClass('placeholder');
			} else {
				$(this).removeClass('placeholder');
			}
		},
		change_x_condition: function(event) {
			var condition = $(this).val();

			//Hide all selects and make them disabled(so it won't be in $_POST)
			$(this).parent().find('select.value').removeClass('selected').prop('disabled', true);
			$(this).parent().find('select.value[data-condition="'+condition+'"]').addClass('selected').prop('disabled', false);
		},
		add_new_x_condition_row: function(event) {
			var sample_row = $('#wc_szamlazz_'+event.data.group+'_condition_sample_row').html();
			$(this).closest('ul').append(sample_row);
			wc_szamlazz_settings.reindex_x_rows(event.data.group);
			return false;
		},
		delete_x_condition_row: function(event) {
			$(this).parent().remove();
			wc_szamlazz_settings.reindex_x_rows(event.data.group);
			return false;
		},
		reindex_x_rows: function(group) {
			var group = group.replace('_', '-');
			$('.wc-szamlazz-settings-'+group).find('.wc-szamlazz-settings-repeat-item').each(function(index){
				$(this).find('textarea, select, input').each(function(){
					var name = $(this).data('name');
					name = name.replace('X', index);
					$(this).attr('name', name);
				});

				//Reindex conditions too
				$(this).find('li').each(function(index_child){
					$(this).find('select').each(function(){
						var name = $(this).data('name');
						name = name.replace('Y', index_child);
						name = name.replace('X', index);
						$(this).attr('name', name);
					});
				});

				$(this).find('.wc-szamlazz-settings-repeat-select').each(function(){
					var val = $(this).val();
					var label = $(this).find('option:selected').text();
					$(this).parent().find('label span').text(label);
					$(this).parent().find('label span').text(label);
					$(this).parent().find('label i').removeClass().addClass(val);
				});

				//For automations, hide a couple of unnecessary fields
				if(group == 'automations') {
					var document_icon = $(this).find('.wc-szamlazz-settings-automation-document').val();
					$(this).find('.wc-szamlazz-settings-automation-option').show();
					if(document_icon == 'delivery') {
						$(this).find('.wc-szamlazz-settings-automation-option:not(:last)').hide();
					} else if(document_icon == 'paid') {
						$(this).find('.wc-szamlazz-settings-automation-option:not(:first)').hide();
					}
				}

				//For advanced options, hide a couple of unnecessary fields
				if(group == 'advanced-options') {
					var selected_property = $(this).find('.wc-szamlazz-settings-advanced-option-property').val();
					$(this).find('.wc-szamlazz-settings-advanced-option-option').hide();
					$(this).find('.property-value').prop('disabled', true);
					$(this).find('.wc-szamlazz-settings-advanced-option-option.option-'+selected_property).show();
					$(this).find('.wc-szamlazz-settings-advanced-option-option.option-'+selected_property+' .property-value').prop('disabled', false);
				}

			});
			return false;
		},
		add_new_x_row: function(event) {
			var group = event.data.group;
			var table = event.data.table;
			var singular = group.slice(0, -1);
			var sample_row = $('#wc_szamlazz_'+singular+'_sample_row').html();
			var sample_row_conditon = $('#wc_szamlazz_'+group+'_condition_sample_row').html();
			sample_row = $(sample_row);
			sample_row.find('ul').append(sample_row_conditon);
			table.append(sample_row);
			wc_szamlazz_settings.reindex_x_rows(group);
			return false;
		},
		toggle_x_condition: function(event) {
			var group = event.data.group;
			var checked = $(this).is(":checked");
			var note = $(this).closest('.wc-szamlazz-settings-repeat-item').find('ul.conditions');
			if(checked) {
				//Add empty row if no condtions exists
				if(note.find('li').length < 1) {
					var sample_row = $('#wc_szamlazz_'+group+'_condition_sample_row').html();
					note.append(sample_row);
				}
				note.show();
			} else {
				note.hide();
			}

			//Slightly different for notes
			if(group == 'notes') {
				var append = $(this).closest('.wc-szamlazz-settings-note').find('.wc-szamlazz-settings-note-if-append');
				if(checked) {
					append.show();
				} else {
					append.hide();
				}
			}

			//Slightly different for automations
			if(group == 'automations') {
				var automation = $(this).closest('.wc-szamlazz-settings-automation').find('.wc-szamlazz-settings-automation-if');
				if(checked) {
					automation.show();
				} else {
					automation.hide();
				}
			}

			wc_szamlazz_settings.reindex_x_rows(event.data.group);
		},
		delete_x_row: function(event) {
			$(this).closest('.wc-szamlazz-settings-repeat-item').remove();
			wc_szamlazz_settings.reindex_x_rows(event.data.group);
			return false;
		},

	}

	//Metabox functions
	var wc_szamlazz_metabox = {
		prefix: 'wc_szamlazz_',
		prefix_id: '#wc_szamlazz_',
		prefix_class: '.wc-szamlazz-',
		$metaboxContent: $('#wc_szamlazz_metabox .inside'),
		$disabledState: $('.wc-szamlazz-metabox-disabled'),
		$optionsContent: $('.wc-szamlazz-metabox-generate-options'),
		$autoMsg: $('.wc-szamlazz-metabox-auto-msg'),
		$generateContent: $('.wc-szamlazz-metabox-generate'),
		$optionsButton: $('#wc_szamlazz_invoice_options'),
		$generateButtonInvoice: $('#wc_szamlazz_invoice_generate'),
		$previewButton: $('#wc_szamlazz_invoice_preview'),
		$generateButtonReceipt: $('#wc_szamlazz_receipt_generate'),
		$receiptRowVoidNote: $('.wc-szamlazz-metabox-receipt-void-note'),
		$invoiceRow: $('.wc-szamlazz-metabox-invoices-invoice'),
		$receiptRow: $('.wc-szamlazz-metabox-invoices-receipt'),
		$proformRow: $('.wc-szamlazz-metabox-invoices-proform'),
		$deliveryRow: $('.wc-szamlazz-metabox-invoices-delivery'),
		$depositRow: $('.wc-szamlazz-metabox-invoices-deposit'),
		$voidedRow: $('.wc-szamlazz-metabox-invoices-void'),
		$correctedRow: $('.wc-szamlazz-metabox-invoices-corrected'),
		$voidedReceiptRow: $('.wc-szamlazz-metabox-invoices-void_receipt'),
		$completeRow: $('.wc-szamlazz-metabox-rows-data-complete'),
		$voidRow: $('.wc-szamlazz-metabox-rows-data-void'),
		$correctRow: $('.wc-szamlazz-metabox-rows-data-correct'),
		$messages: $('.wc-szamlazz-metabox-messages'),
		$reverseReceiptButton: $('#wc_szamlazz_reverse_receipt'),
		nonce: $('.wc-szamlazz-metabox-content').data('nonce'),
		order: $('.wc-szamlazz-metabox-content').data('order'),
		$uploadDocumentButton: $('.wc-szamlazz-invoice-upload'),
		is_receipt: false,
		init: function() {
			this.$optionsButton.on( 'click', this.show_options );
			$(this.prefix_class+'invoice-toggle').on( 'click', this.toggle_invoice );

			this.$previewButton.on( 'click', this.show_preview);
			this.$generateButtonInvoice.on( 'click', this.generate_invoice );
			this.$generateButtonReceipt.on( 'click', this.generate_receipt );

			this.$completeRow.find('a').on( 'click', this.mark_completed );
			this.$voidRow.find('a').on( 'click', this.void_invoice );
			this.$correctRow.find('a').on( 'click', this.correct_invoice );

			this.$messages.find('a').on( 'click', this.hide_message );

			this.$reverseReceiptButton.on( 'click', this.reverse_receipt );

			if(this.$generateButtonReceipt.length) {
				this.is_receipt = true;
			}

			this.$uploadDocumentButton.on( 'click', this.show_upload_modal );

			$( 'body' ).on( 'submit', '#wc-szamlazz-modal-upload-form', this.upload_document);
			$( 'body' ).on( 'change', '#wc_szamlazz_document_upload_file', this.on_upload_document_change);

			/*
			//Prevent invoice duplicates on order status change caused by WP bug when you douple click the order update button
			var $orderForm = $("form#post");
			$orderForm.submit(function(){
				$orderForm.submit(function(){
					return false;
				});
			});
			*/

		},
		loading_indicator: function(button, color) {
			wc_szamlazz_metabox.hide_message();
			button.block({
				message: null,
				overlayCSS: {
					background: color+' url(' + wc_szamlazz_params.loading + ') no-repeat center',
					backgroundSize: '16px 16px',
					opacity: 0.6
				}
			});
		},
		show_options: function() {
			wc_szamlazz_metabox.$optionsButton.toggleClass('active');
			wc_szamlazz_metabox.$optionsContent.slideToggle();
			return false;
		},
		toggle_invoice: function() {
			var note = '';

			//Ask for message
			if($(this).hasClass('off')) {
				note = prompt("Számlakészítés kikapcsolása. Mi az indok?", "Ehhez a rendeléshez nem kell számla.");
				if (!note) {
					return false;
				}
			}

			//Create request
			var data = {
				action: wc_szamlazz_metabox.prefix+'toggle_invoice',
				nonce: wc_szamlazz_metabox.nonce,
				order: wc_szamlazz_metabox.order,
				note: note
			};

			//Show loading indicator
			wc_szamlazz_metabox.loading_indicator(wc_szamlazz_metabox.$metaboxContent, '#fff');

			//Make request
			$.post(ajaxurl, data, function(response) {

				//Replace text
				wc_szamlazz_metabox.$disabledState.find('span').text(note);

				//Hide loading indicator
				wc_szamlazz_metabox.$metaboxContent.unblock();

				//Show/hide divs based on response
				if (response.data.state == 'off') {
					wc_szamlazz_metabox.$disabledState.slideDown();
					wc_szamlazz_metabox.$optionsContent.slideUp();
					wc_szamlazz_metabox.$autoMsg.slideUp();
					wc_szamlazz_metabox.$generateContent.slideUp();
					wc_szamlazz_metabox.$voidedRow.slideUp();
				} else {
					wc_szamlazz_metabox.$disabledState.slideUp();
					wc_szamlazz_metabox.$autoMsg.slideDown();
					wc_szamlazz_metabox.$generateContent.slideDown();
				}
			});

			return false;
		},
		show_document_response: function(response) {

			if(response.data.type == 'invoice') {
				wc_szamlazz_metabox.$autoMsg.slideUp();
				wc_szamlazz_metabox.$generateContent.slideUp();
				wc_szamlazz_metabox.$voidedRow.slideUp();

				wc_szamlazz_metabox.$invoiceRow.find('strong').text(response.data.name);
				wc_szamlazz_metabox.$invoiceRow.find('a').attr('href', response.data.link);
				wc_szamlazz_metabox.$invoiceRow.slideDown();
				wc_szamlazz_metabox.$completeRow.slideDown();
				wc_szamlazz_metabox.$voidRow.slideDown();

				if(response.data.completed) {
					wc_szamlazz_metabox.$completeRow.find('a').addClass('completed');
					wc_szamlazz_metabox.$completeRow.find('a').text(response.data.completed);
				}

				if(response.data.delivery) {
					wc_szamlazz_metabox.$deliveryRow.find('strong').text(response.data.delivery.name);
					wc_szamlazz_metabox.$deliveryRow.find('a').attr('href', response.data.delivery.link);
					wc_szamlazz_metabox.$deliveryRow.slideDown();
				}
			}

			if(response.data.type == 'proform') {
				$('#wc_szamlazz_invoice_normal').prop('checked', true);
				wc_szamlazz_metabox.$optionsContent.slideUp();
				wc_szamlazz_metabox.$proformRow.find('strong').text(response.data.name);
				wc_szamlazz_metabox.$proformRow.find('a').attr('href', response.data.link);
				wc_szamlazz_metabox.$proformRow.slideDown();
				wc_szamlazz_metabox.$voidedRow.slideUp();
			}

			if(response.data.type == 'delivery') {
				$('#wc_szamlazz_invoice_normal').prop('checked', true);
				wc_szamlazz_metabox.$optionsContent.slideUp();
				wc_szamlazz_metabox.$deliveryRow.find('strong').text(response.data.name);
				wc_szamlazz_metabox.$deliveryRow.find('a').attr('href', response.data.link);
				wc_szamlazz_metabox.$deliveryRow.slideDown();
				wc_szamlazz_metabox.$voidedRow.slideUp();
			}

			if(response.data.type == 'deposit') {
				$('#wc_szamlazz_invoice_normal').prop('checked', true);
				wc_szamlazz_metabox.$optionsContent.slideUp();
				wc_szamlazz_metabox.$depositRow.find('strong').text(response.data.name);
				wc_szamlazz_metabox.$depositRow.find('a').attr('href', response.data.link);
				wc_szamlazz_metabox.$depositRow.slideDown();
				wc_szamlazz_metabox.$voidedRow.slideUp();
			}

		},
		generate_invoice: function() {
			var $this = $(this);
			var r = confirm($this.data('question'));
			var type = 'invoice';
			if (r != true) {
				return false;
			}

			var account = $('#wc_szamlazz_invoice_account').val();
			var lang = $('#wc_szamlazz_invoice_lang').val();
			var doc_type = $('#wc_szamlazz_invoice_doc_type').val();
			var note = $('#wc_szamlazz_invoice_note').val();
			var deadline = $('#wc_szamlazz_invoice_deadline').val();
			var completed = $('#wc_szamlazz_invoice_completed').val();
			var proform = $('#wc_szamlazz_invoice_proform').is(':checked');
			var delivery = $('#wc_szamlazz_invoice_delivery').is(':checked');
			var deposit = $('#wc_szamlazz_invoice_deposit').is(':checked');
			if (proform) type = 'proform';
			if (delivery) type = 'delivery';
			if (deposit) type = 'deposit';

			//Create request
			var data = {
				action: wc_szamlazz_metabox.prefix+'generate_invoice',
				nonce: wc_szamlazz_metabox.nonce,
				order: wc_szamlazz_metabox.order,
				account: account,
				lang: lang,
				doc_type: doc_type,
				note: note,
				deadline: deadline,
				completed: completed,
				type: type
			};

			//Show loading indicator
			wc_szamlazz_metabox.loading_indicator(wc_szamlazz_metabox.$metaboxContent, '#fff');

			//Make request
			$.post(ajaxurl, data, function(response) {

				//Hide loading indicator
				wc_szamlazz_metabox.$metaboxContent.unblock();

				//Show success/error messages
				wc_szamlazz_metabox.show_messages(response);

				//On success and error
				if(response.data.error) {

				} else {
					wc_szamlazz_metabox.show_document_response(response);
				}

			});

			return false;
		},
		generate_receipt: function() {
			var $this = $(this);
			var r = confirm($this.data('question'));
			if (r != true) {
				return false;
			}

			//Create request
			var data = {
				action: wc_szamlazz_metabox.prefix+'generate_receipt',
				nonce: wc_szamlazz_metabox.nonce,
				order: wc_szamlazz_metabox.order
			};

			//Show loading indicator
			wc_szamlazz_metabox.loading_indicator(wc_szamlazz_metabox.$metaboxContent, '#fff');

			//Make request
			$.post(ajaxurl, data, function(response) {

				//Hide loading indicator
				wc_szamlazz_metabox.$metaboxContent.unblock();

				//Show success/error messages
				wc_szamlazz_metabox.show_messages(response);

				//On success and error
				if(response.data.error) {

				} else {
					wc_szamlazz_metabox.$autoMsg.slideUp();
					wc_szamlazz_metabox.$generateContent.slideUp();
					wc_szamlazz_metabox.$receiptRow.find('strong').text(response.data.name);
					wc_szamlazz_metabox.$receiptRow.find('a').attr('href', response.data.link);
					wc_szamlazz_metabox.$receiptRow.slideDown();
					wc_szamlazz_metabox.$voidRow.slideDown();
				}

			});

			return false;
		},
		mark_completed_timeout: false,
		mark_completed: function() {
			var $this = $(this);

			//Do nothing if already marked completed
			if($this.hasClass('completed')) return false;

			if($this.hasClass('confirm')) {

				//Reset timeout
				clearTimeout(wc_szamlazz_metabox.mark_completed_timeout);

				//Show loading indicator
				wc_szamlazz_metabox.loading_indicator(wc_szamlazz_metabox.$completeRow, '#fff');

				//Create request
				var data = {
					action: wc_szamlazz_metabox.prefix+'mark_completed',
					nonce: wc_szamlazz_metabox.nonce,
					order: wc_szamlazz_metabox.order,
				};

				$.post(ajaxurl, data, function(response) {

					//Hide loading indicator
					wc_szamlazz_metabox.$completeRow.unblock();

					//Show success/error messages
					wc_szamlazz_metabox.show_messages(response);

					if(response.data.error) {
						//On success and error
						$this.fadeOut(function(){
							$this.text($this.data('trigger-value'));
							$this.removeClass('confirm');
							$this.fadeIn();
						});
					} else {
						//On success and error
						$this.fadeOut(function(){
							$this.text(response.data.completed);
							$this.addClass('completed');
							$this.fadeIn();
							$this.removeClass('confirm');
						});
					}

				});

			} else {
				wc_szamlazz_metabox.mark_completed_timeout = setTimeout(function(){
					$this.fadeOut(function(){
						$this.text($this.data('trigger-value'));
						$this.fadeIn();
						$this.removeClass('confirm');
					});
				}, 5000);

				$this.addClass('confirm');
				$this.fadeOut(function(){
					$this.text('Biztos?')
					$this.fadeIn();
				});
			}

			return false;

		},
		void_invoice_timeout: false,
		void_invoice: function() {
			var $this = $(this);

			//Do nothing if already marked completed
			if($this.hasClass('confirm')) {

				//Reset timeout
				clearTimeout(wc_szamlazz_metabox.void_invoice_timeout);

				//Show loading indicator
				wc_szamlazz_metabox.loading_indicator(wc_szamlazz_metabox.$voidRow, '#fff');

				//Set request route
				var request_suffix = wc_szamlazz_metabox.is_receipt ? 'void_receipt' : 'void_invoice';

				//Create request
				var data = {
					action: wc_szamlazz_metabox.prefix+request_suffix,
					nonce: wc_szamlazz_metabox.nonce,
					order: wc_szamlazz_metabox.order,
				};

				$.post(ajaxurl, data, function(response) {

					//Hide loading indicator
					wc_szamlazz_metabox.$voidRow.unblock();

					//Show success/error messages
					wc_szamlazz_metabox.show_messages(response);

					//On success and error
					if(response.data.error) {

					} else {

						wc_szamlazz_metabox.$invoiceRow.slideUp();
						wc_szamlazz_metabox.$completeRow.slideUp();
						wc_szamlazz_metabox.$deliveryRow.slideUp();
						wc_szamlazz_metabox.$depositRow.slideUp();
						wc_szamlazz_metabox.$receiptRow.slideUp();
						wc_szamlazz_metabox.$correctRow.slideUp();
						wc_szamlazz_metabox.$proformRow.slideUp();
						wc_szamlazz_metabox.$voidRow.slideUp(function(){
							$this.text(response.data.completed);
							$this.removeClass('confirm');
						});

						//If we need to delete the proform invoice too, hide that one too
						if(wc_szamlazz_params.delete_proform_too == 'yes') {
							wc_szamlazz_metabox.$proformRow.slideUp();
						}

						wc_szamlazz_metabox.$generateContent.slideDown();
						wc_szamlazz_metabox.$autoMsg.slideDown();

						//Reload page if we voided a receipt
						if(wc_szamlazz_metabox.is_receipt) {
							wc_szamlazz_metabox.$voidedReceiptRow.find('strong').text(response.data.name);
							wc_szamlazz_metabox.$voidedReceiptRow.find('a').attr('href', response.data.link);
							wc_szamlazz_metabox.$voidedReceiptRow.slideDown();

							wc_szamlazz_metabox.$receiptRowVoidNote.slideDown();
							wc_szamlazz_metabox.$generateContent.slideUp();
						} else {
							//If theres no name, it was a proform delete
							if(response.data.name) {
								wc_szamlazz_metabox.$voidedRow.find('strong').text(response.data.name);
								wc_szamlazz_metabox.$voidedRow.find('a').attr('href', response.data.link);
								wc_szamlazz_metabox.$voidedRow.slideDown();
							}
						}

					}

					//On success and error
					$this.fadeOut(function(){
						$this.text($this.data('trigger-value'));
						$this.fadeIn();
						$this.removeClass('confirm');
					});

				});

			} else {
				wc_szamlazz_metabox.void_invoice_timeout = setTimeout(function(){
					$this.fadeOut(function(){
						$this.text($this.data('trigger-value'));
						$this.fadeIn();
						$this.removeClass('confirm');
					});
				}, 5000);

				$this.addClass('confirm');
				$this.fadeOut(function(){
					$this.text($this.data('question'))
					$this.fadeIn();
				});
			}

			return false;

		},
		correct_invoice_timeout: false,
		correct_invoice: function() {
			var $this = $(this);

			//Do nothing if already marked completed
			if($this.hasClass('confirm')) {

				//Reset timeout
				clearTimeout(wc_szamlazz_metabox.correct_invoice_timeout);

				//Show loading indicator
				wc_szamlazz_metabox.loading_indicator(wc_szamlazz_metabox.$correctRow, '#fff');

				//Set request route
				var request_suffix = wc_szamlazz_metabox.is_receipt ? 'void_receipt' : 'void_invoice';

				//Create request
				var account = $('#wc_szamlazz_invoice_account').val();
				var lang = $('#wc_szamlazz_invoice_lang').val();
				var note = $('#wc_szamlazz_invoice_note').val();
				var deadline = $('#wc_szamlazz_invoice_deadline').val();
				var completed = $('#wc_szamlazz_invoice_completed').val();

				//Create request
				var data = {
					action: wc_szamlazz_metabox.prefix+'generate_invoice',
					nonce: wc_szamlazz_metabox.nonce,
					order: wc_szamlazz_metabox.order,
					account: account,
					lang: lang,
					note: note,
					deadline: deadline,
					completed: completed,
					type: 'corrected'
				};

				$.post(ajaxurl, data, function(response) {

					//Hide loading indicator
					wc_szamlazz_metabox.$correctRow.unblock();

					//Show success/error messages
					wc_szamlazz_metabox.show_messages(response);

					//On success and error
					if(response.data.error) {

					} else {
						wc_szamlazz_metabox.$voidRow.slideUp(function(){
							$this.text(response.data.completed);
							$this.removeClass('confirm');
						});

						wc_szamlazz_metabox.$correctRow.slideUp(function(){
							$this.text(response.data.completed);
							$this.removeClass('confirm');
						});

						//Show corrected invoice id and download link
						wc_szamlazz_metabox.$correctedRow.find('strong').text(response.data.name);
						wc_szamlazz_metabox.$correctedRow.find('a').attr('href', response.data.link);
						wc_szamlazz_metabox.$correctedRow.slideDown();
					}

					//On success and error
					$this.fadeOut(function(){
						$this.text($this.data('trigger-value'));
						$this.fadeIn();
						$this.removeClass('confirm');
					});

				});

			} else {
				wc_szamlazz_metabox.void_invoice_timeout = setTimeout(function(){
					$this.fadeOut(function(){
						$this.text($this.data('trigger-value'));
						$this.fadeIn();
						$this.removeClass('confirm');
					});
				}, 5000);

				$this.addClass('confirm');
				$this.fadeOut(function(){
					$this.text($this.data('question'))
					$this.fadeIn();
				});
			}

			return false;

		},
		show_messages: function(response) {
			if(response.data.messages && response.data.messages.length > 0) {
				this.$messages.removeClass('wc-szamlazz-metabox-messages-success');
				this.$messages.removeClass('wc-szamlazz-metabox-messages-error');

				if(response.data.error) {
					this.$messages.addClass('wc-szamlazz-metabox-messages-error');
				} else {
					this.$messages.addClass('wc-szamlazz-metabox-messages-success');
				}

				$ul = this.$messages.find('ul');
				$ul.html('');

				$.each(response.data.messages, function(i, value) {
					var li = $('<li>')
					li.append(value);
					$ul.append(li);
				});
				this.$messages.slideDown();
			}
		},
		hide_message: function() {
			wc_szamlazz_metabox.$messages.slideUp();
			return false;
		},
		reverse_receipt: function() {
			//Create request
			var data = {
				action: wc_szamlazz_metabox.prefix+'reverse_receipt',
				nonce: wc_szamlazz_metabox.nonce,
				order: wc_szamlazz_metabox.order
			};

			//Show loading indicator
			wc_szamlazz_metabox.loading_indicator(wc_szamlazz_metabox.$metaboxContent, '#fff');

			//Make request
			$.post(ajaxurl, data, function(response) {
				window.location.reload();
			});
		},
		show_preview: function() {
			var note = $('#wc_szamlazz_invoice_note').val();
			var deadline = $('#wc_szamlazz_invoice_deadline').val();
			var completed = $('#wc_szamlazz_invoice_completed').val();
			var account = $('#wc_szamlazz_invoice_account').val();
			var url = $(this).data('url');
			var params = {'note': note, 'deadline': deadline, 'completed': completed, 'account': account};
			url += '&' + $.param(params);

			//Change url to include options
			$(this).attr('href', url);

			return true;
		},
		show_upload_modal: function() {
			$(this).WCBackboneModal({
				template: 'wc-szamlazz-modal-upload',
				variable : {order_id: wc_szamlazz_metabox.order}
			});

			$('#wc_szamlazz_mark_paid_date').datepicker({
				dateFormat: 'yy-mm-dd',
				numberOfMonths: 1,
				showButtonPanel: true,
				maxDate: 0
			});
			return false;
		},
		on_upload_document_change: function(e) {
			var fileInput = e.target;
			if (fileInput.files.length > 0) {
				var filename = fileInput.files[0].name;
				var nameWithoutExtension = filename.split('.').slice(0, -1).join('.');
				$('.wc-szamlazz-modal-upload #wc_szamlazz_document_upload_name').val(nameWithoutExtension);
			}
		},
		upload_document: function(e) {
			e.preventDefault();

			//Collect form data
			var form = $(this);
			var formdata = (window.FormData) ? new FormData(form[0]) : null;
			var data = (formdata !== null) ? formdata : form.serialize();

			//Validate
			var document_name = $('.wc-szamlazz-modal-upload #wc_szamlazz_document_upload_name').val();
			var document_file = $('.wc-szamlazz-modal-upload #wc_szamlazz_document_upload_file').val();
			var valid = true;

			//Append nonce and action
			formdata.append('action', 'wc_szamlazz_upload_document');
			formdata.append('nonce', wc_szamlazz_metabox.nonce);
			formdata.append('order', wc_szamlazz_metabox.order);

			if(!document_name) {
				valid = false;
				$('#wc_szamlazz_document_upload_name').parent().addClass('validate');
				setTimeout(function(){
					$('#wc_szamlazz_document_upload_name').parent().removeClass('validate');
				}, 1000);
			}

			if(!document_file) {
				valid = false;
				$('#wc_szamlazz_document_upload_file').parent().addClass('validate');
				setTimeout(function(){
					$('#wc_szamlazz_document_upload_file').parent().removeClass('validate');
				}, 1000);
			}

			//If valid, submit form
			if(valid) {

				//Show loading indicator
				wc_szamlazz_metabox.loading_indicator($('.wc-szamlazz-modal-upload-form'), '#fff');

				//Make POST request
				$.ajax({
					type: 'POST',
					url: ajaxurl,
					contentType: false,
					processData: false,
					dataType: 'JSON',
					status: 200,
					data: formdata,
					success: function(response){
						console.log(response);

						//Show success/error messages
						wc_szamlazz_bulk_actions.show_messages(response, 'uploader-results');

						//Hide loading indicator
						$('.wc-szamlazz-modal-upload-form').unblock();

						//On success, update metabox
						wc_szamlazz_metabox.show_document_response(response);
						$('.modal-close-link').trigger('click');

					}
				});

			}

			return false;
		}
	}

	// Hide notice
	$( '.wc-szamlazz-notice .wc-szamlazz-hide-notice').on('click', function(e) {
		e.preventDefault();
		var el = $(this).closest('.wc-szamlazz-notice');
		$(el).find('.wc-szamlazz-wait').remove();
		$(el).append('<div class="wc-szamlazz-wait"></div>');
		if ( $('.wc-szamlazz-notice.updating').length > 0 ) {
			var button = $(this);
			setTimeout(function(){
				button.triggerHandler( 'click' );
			}, 100);
			return false;
		}
		$(el).addClass('updating');
		$.post( ajaxurl, {
				action: 	'wc_szamlazz_hide_notice',
				security: 	$(this).data('nonce'),
				notice: 	$(this).data('notice'),
				remind: 	$(this).hasClass( 'remind-later' ) ? 'yes' : 'no'
		}, function(){
			$(el).removeClass('updating');
			$(el).fadeOut(100);
		});
	});

	//Background generate actions
	var wc_szamlazz_background_actions = {
		$menu_bar_item: $('#wp-admin-bar-wc-szamlazz-bg-generate-loading'),
		$link_stop: $('#wc-szamlazz-bg-generate-stop'),
		$link_refresh: $('#wc-szamlazz-bg-generate-refresh'),
		finished: false,
		nonce: '',
		init: function() {
			this.$link_stop.on( 'click', this.stop );
			this.$link_refresh.on( 'click', this.reload_page );

			//Store nonce
			this.nonce = this.$link_stop.data('nonce');

			//Refresh status every 5 second
			var refresh_action = this.refresh;
			setTimeout(refresh_action, 5000);

		},
		reload_page: function() {
			location.reload();
			return false;
		},
		stop: function() {
			var data = {
				action: 'wc_szamlazz_bg_generate_stop',
				nonce: wc_szamlazz_background_actions.nonce,
			}

			$.post(ajaxurl, data, function(response) {
				wc_szamlazz_background_actions.mark_stopped();
			});
			return false;
		},
		refresh: function() {
			var data = {
				action: 'wc_szamlazz_bg_generate_status',
				nonce: wc_szamlazz_background_actions.nonce,
			}

			if(!wc_szamlazz_background_actions.finished) {
				$.post(ajaxurl, data, function(response) {
					if(response.data.finished) {
						wc_szamlazz_background_actions.mark_finished();
					} else {
						//Repeat after 5 seconds
						setTimeout(wc_szamlazz_background_actions.refresh, 5000);
					}

				});
			}
		},
		mark_finished: function() {
			this.finished = true;
			this.$menu_bar_item.addClass('finished');
		},
		mark_stopped: function() {
			this.mark_finished();
			this.$menu_bar_item.addClass('stopped');
		}
	}

	//Bulk actions
	var wc_szamlazz_bulk_actions = {
		init: function() {
			var printAction = $('#wc-szamlazz-bulk-print');
			var downloadAction = $('#wc-szamlazz-bulk-download');
			printAction.on( 'click', this.printInvoices );
			if(printAction.length) {
				printAction.trigger('click');
			}

			$( '#wpbody' ).on( 'click', '#doaction', function() {
				if($('#bulk-action-selector-top').val() == 'wc_szamlazz_bulk_grouped_generate') {
					wc_szamlazz_bulk_actions.show_grouped_modal();
					return false;
				}

				if($('#bulk-action-selector-top').val() == 'wc_szamlazz_bulk_generator') {
					wc_szamlazz_bulk_actions.show_generator_modal();
					return false;
				}
			});

			$( '#wpbody' ).on( 'click', '#doaction2', function() {
				if($('#bulk-action-selector-bottom').val() == 'wc_szamlazz_bulk_grouped_generate') {
					wc_szamlazz_bulk_actions.show_grouped_modal();
					return false;
				}

				if($('#bulk-action-selector-bottom').val() == 'wc_szamlazz_bulk_generator') {
					wc_szamlazz_bulk_actions.show_generator_modal();
					return false;
				}
			});

			$(document).on( 'click', '#generate_grouped_invoice', this.generate_grouped_invoices );
			$(document).on( 'click', '#wc_szamlazz_bulk_generator', this.bulk_generator );
			$(document).on( 'change', '.wc-szamlazz-modal-bulk-generator-form input[name="bulk_invoice_extra_type"]', this.toggle_bulk_generator_options );

			//Listen for keyboard shortcuts
			var mPressed = false;
			$(window).keydown(function(evt) {
				if (evt.which == 77) { //m
					mPressed = true;
				}
			}).keyup(function(evt) {
				if (evt.which == 77) { //m
					mPressed = false;
				}
			});

			//Mark order as paid in order manager
			$( '#wpbody' ).on( 'click', 'a.wc-szamlazz-mark-paid-button', function() {
				if($(this).hasClass('paid')) return false;
				var order_id = $(this).data('order');
				var nonce = $(this).data('nonce');
				var today = $.datepicker.formatDate('yy-mm-dd', new Date());

				if(mPressed) {
					$(this).addClass('paid');
					$(this).tipTip({ content: 'Fizetve: '+today });
					$('#tiptip_content').text('Fizetve: '+today);

					//Create request
					var data = {
						action: wc_szamlazz_metabox.prefix+'mark_completed',
						nonce: nonce,
						order: order_id,
					};

					//Make an ajax call in the background. No error handling, since this usually works just fine
					$.post(ajaxurl, data, function(response) { });

				} else {
					$(this).WCBackboneModal({
						template: 'wc-szamlazz-modal-mark-paid',
						variable : {order_id: order_id}
					});

					$('#wc_szamlazz_mark_paid_date').datepicker({
						dateFormat: 'yy-mm-dd',
						numberOfMonths: 1,
						showButtonPanel: true,
						maxDate: 0
					});
				}

				return false;
			});

			//Mark order as paid in order manager
			$( 'body' ).on( 'click', '#wc_szamlazz_mark_paid', function() {
				var order_id = $(this).data('order');
				var nonce = $(this).data('nonce');
				var date = $('#wc_szamlazz_mark_paid_date').val();

				//Create request
				var data = {
					action: wc_szamlazz_metabox.prefix+'mark_completed',
					nonce: nonce,
					order: order_id,
					date: date
				};

				//Change to a green checkmark and update tooltip text
				$('a.wc-szamlazz-mark-paid-button[data-order="'+order_id+'"]').addClass('paid');
				$('a.wc-szamlazz-mark-paid-button[data-order="'+order_id+'"]').tipTip({ content: 'Fizetve: '+date });

				//Make an ajax call in the background. No error handling, since this usually works just fine
				$.post(ajaxurl, data, function(response) { });

				//Close modal
				$('.modal-close-link').trigger('click');

				return false;
			});

		},
		printInvoices: function() {
			var pdf_url = $(this).data('pdf');
			if (typeof printJS === 'function') {
				printJS(pdf_url);
				return false;
			}
		},
		show_grouped_modal: function() {
			var checkedOrders = jQuery("#the-list input[name='post[]']:checked");
			var orderIds = [];
			var ul = $('<ul/>');
			ul.addClass('wc-szamlazz-modal-grouped-generate-list');

			$(checkedOrders).each(function(i) {
				var order_id = $(checkedOrders[i]).val();
				var column_name = $(checkedOrders[i]).parents('.type-shop_order').find('a.order-view').text();
				ul.append('<li><label><input type="radio" name="main_order_id" value="'+order_id+'"> '+column_name+'</label></li>');
				orderIds.push(order_id);
			});

			if(checkedOrders.length === 0) {
				orderIds = false;
			}

			$(this).WCBackboneModal({
				template: 'wc-szamlazz-modal-grouped-generate',
				variable : {orders: ul.prop("outerHTML"), orderIds: orderIds}
			});
			return false;
		},
		show_generator_modal: function() {
			var checkedOrders = jQuery("#the-list input[name='post[]']:checked");
			var orderIds = [];
			var ul = $('<ul/>');

			$(checkedOrders).each(function(i) {
				var order_id = $(checkedOrders[i]).val();
				var column_name = $(checkedOrders[i]).parents('.type-shop_order').find('a.order-view').text();
				ul.append('<li>'+column_name+'</li>');
				orderIds.push(order_id);
			});

			if(checkedOrders.length === 0) {
				orderIds = false;
			}

			$(this).WCBackboneModal({
				template: 'wc-szamlazz-modal-bulk-generator',
				variable : {orders: ul.prop("outerHTML"), orderIds: orderIds}
			});

			$('#wc_szamlazz_bulk_invoice_completed').datepicker({
				dateFormat: 'yy-mm-dd',
				numberOfMonths: 1,
				showButtonPanel: true
			});
			return false;
		},
		generate_grouped_invoices: function() {
			var orderIds = $(this).data('orders');
			var nonce = $(this).data('nonce');
			var mainOrder = $('input[name=main_order_id]:checked', '.wc-szamlazz-modal-grouped-generate-list').val();

			if(!mainOrder) {
				$('.wc-szamlazz-modal-grouped-generate-list').addClass('validate');
				setTimeout(function(){
					$('.wc-szamlazz-modal-grouped-generate-list').removeClass('validate');
				}, 1000);
				return false;
			}

			//Show loading indicator
			wc_szamlazz_metabox.loading_indicator($('.wc-szamlazz-modal-grouped-generate-form'), '#fff');

			//Create request
			var data = {
				action: wc_szamlazz_metabox.prefix+'generate_grouped_invoice',
				nonce: nonce,
				orders: orderIds,
				main_order: mainOrder
			};

			$.post(ajaxurl, data, function(response) {

				//Hide loading indicator
				$('.wc-szamlazz-modal-grouped-generate-form').unblock();

				//Show success/error messages
				wc_szamlazz_bulk_actions.show_messages(response, 'grouped-generate-results');

				if(response.data.error) {

				} else {
					$('.wc-szamlazz-modal-grouped-generate-download').slideDown();
					$('.wc-szamlazz-modal-grouped-generate-download-invoice').find('strong').text(response.data.name);
					$('.wc-szamlazz-modal-grouped-generate-download-invoice').attr('href', response.data.link);
					$('.wc-szamlazz-modal-grouped-generate-download-order').attr('href', response.data.order_link);
					$('.wc-szamlazz-modal-grouped-generate-form, .wc-szamlazz-modal-grouped-generate footer').slideUp();
				}

			});

			return false;
		},
		show_messages: function(response, id) {
			$messages = $('.wc-szamlazz-modal-'+id);
			if(response.data.messages && response.data.messages.length > 0) {
				$messages.removeClass('wc-szamlazz-metabox-messages-success');
				$messages.removeClass('wc-szamlazz-metabox-messages-error');

				if(response.data.error) {
					$messages.addClass('wc-szamlazz-metabox-messages-error');
				} else {
					$messages.addClass('wc-szamlazz-metabox-messages-success');
				}

				$ul = $messages.find('ul');
				$ul.html('');

				$.each(response.data.messages, function(i, value) {
					var li = $('<li>')
					li.append(value);
					$ul.append(li);
				});
				$messages.slideDown();
			}
		},
		bulk_generator: function() {
			var orderIds = $(this).data('orders');
			var nonce = $(this).data('nonce');

			//Show loading indicator
			wc_szamlazz_metabox.loading_indicator($('.wc-szamlazz-modal-bulk-generator-form'), '#fff');

			//Pass other options too
			var type = 'invoice';
			var account = $('#wc_szamlazz_bulk_invoice_account').val();
			var lang = $('#wc_szamlazz_bulk_invoice_lang').val();
			var doc_type = $('#wc_szamlazz_bulk_invoice_doc_type').val();
			var note = $('#wc_szamlazz_bulk_invoice_note').val();
			var deadline = $('#wc_szamlazz_bulk_invoice_deadline').val();
			var completed = $('#wc_szamlazz_bulk_invoice_completed').val();
			var proform = $('#wc_szamlazz_bulk_invoice_proform').is(':checked');
			var delivery = $('#wc_szamlazz_bulk_invoice_delivery').is(':checked');
			var deposit = $('#wc_szamlazz_bulk_invoice_deposit').is(':checked');
			var type_void = $('#wc_szamlazz_bulk_invoice_void').is(':checked');
			if (proform) type = 'proform';
			if (delivery) type = 'delivery';
			if (deposit) type = 'deposit';
			if (type_void) type = 'void';

			//Create request
			var data = {
				action: wc_szamlazz_metabox.prefix+'bulk_generator',
				nonce: nonce,
				orders: orderIds,
				options: {
					account: account,
					lang: lang,
					doc_type: doc_type,
					note: note,
					deadline: deadline,
					completed: completed,
					document_type: type
				}
			};

			//Submit ajax request
			$.post(ajaxurl, data, function(response) {

				//Hide loading indicator
				$('.wc-szamlazz-modal-bulk-generator-form').unblock();

				//Show success/error messages
				wc_szamlazz_bulk_actions.show_messages(response, 'bulk-generator-results');

				if(response.data.error) {

				} else {
					if(response.data.generated) {
						response.data.generated.forEach(function(generated){
							var row = '';
							console.log(generated);
							if(generated.error || (generated.link && generated.link == 'proform_deleted')) {
								row = '<div class="wc-szamlazz-modal-bulk-generator-download-error"><span>'+generated.order_number+'</span> <em>'+generated.messages[0]+'</em></div>';
							} else {
								row = '<a target="_blank" href="'+generated.link+'" class="wc-szamlazz-modal-bulk-generator-download-document document-'+type+'"><span>'+generated.order_number+'</span> <strong>'+generated.name+'</strong></a>';
							}
							$('.wc-szamlazz-modal-bulk-generator-download').append(row);
						});
					}

					$('.wc-szamlazz-modal-bulk-generator-form, .wc-szamlazz-modal-bulk-generator footer').slideUp();
					$('.wc-szamlazz-modal-bulk-generator-download').slideDown();
				}

			});

			return false;
		},
		toggle_bulk_generator_options: function() {
			if($('#wc_szamlazz_bulk_invoice_void').is(':checked')) {
				$('.hidden-if-void').hide();
			} else {
				$('.hidden-if-void').show();
			}
		}
	}

	//Metabox
	if($('#wc_szamlazz_metabox').length) {
		wc_szamlazz_metabox.init();
	}

	//Init settings page
	if($('#woocommerce_wc_szamlazz_section_auth').length) {
		wc_szamlazz_settings.init();
	}

	//Init background generate loading indicator
	if($('#wp-admin-bar-wc-szamlazz-bg-generate-loading').length) {
		wc_szamlazz_background_actions.init();
	}

	//Init bulk actions
	if($('.wc-szamlazz-bulk-actions').length || $('#tmpl-wc-szamlazz-modal-grouped-generate').length) {
		wc_szamlazz_bulk_actions.init();
	}

	//Migrate button
	$( '.wc-szamlazz-notice .wc-szamlazz-migrate-button').on('click', function(e) {
		e.preventDefault();
		var $this = $(this);
		var $el = $(this).closest('.wc-szamlazz-notice');
		$el.find('.wc-szamlazz-wait').remove();
		$el.append('<div class="wc-szamlazz-wait"></div>');
		$el.addClass('updating');
		$.post( ajaxurl, {
			action: 'wc_szamlazz_migrate',
			security: $(this).data('nonce')
		}, function(){
			window.location.href = $this.attr('href');
		});
	});

	//Store management links
	if(window.location.search.indexOf('page=wc-admin') > -1) {
		var waitForEl = function(selector, callback) {
			if (!jQuery(selector).size()) {
				setTimeout(function() {
					window.requestAnimationFrame(function(){ waitForEl(selector, callback) });
				}, 100);
			}else {
				callback();
			}
		};

		waitForEl('.woocommerce-quick-links__category', function() {
			var sampleLink = $('.woocommerce-quick-links__item').last();
			var category = sampleLink.parent();
			var newLink = sampleLink.clone();
			newLink.find('div').text('Számlázz.hu');
			newLink.find('a').attr('href', wc_szamlazz_params.settings_link);
			newLink.find('svg').html('<path d="M18.3165537,3 L18.3165537,5.83530482 L18.7478807,5.85409233 L18.7478807,5.85409233 L19.0672771,5.86436452 L19.0672771,5.86436452 L19.7246728,5.88095014 L19.7246728,5.88095014 L19.957369,5.88555849 L19.957369,5.88555849 L21,5.9044586 L20.9997502,6.09681268 L20.9997502,6.09681268 L20.9886755,6.41885343 L20.9886755,6.41885343 L20.9362008,7.65648738 L20.9362008,7.65648738 L20.8780175,8.9634171 L20.8780175,8.9634171 L20.650086,14.4243826 L20.650086,14.4243826 L20.5717498,16.2730029 L20.5717498,16.2730029 L20.3578922,21.3918046 L20.3578922,21.3918046 L20.3384187,21.7800918 L20.3384187,21.7800918 L20.3323313,21.8733128 L20.3323313,21.8733128 L20.3296935,21.9010939 L20.3296935,21.9010939 L20.3117135,22 L20.1026137,22 L19.9119896,21.9899033 L19.9119896,21.9899033 L18.713643,21.9349534 L18.713643,21.9349534 L18.3916692,21.9194664 L18.3916692,21.9194664 L17.942755,21.8996968 L17.942755,21.8996968 L17.2579329,21.8718208 L17.2579329,21.8718208 L14.0416941,21.7507541 L14.0416941,21.7507541 L13.3788273,21.7280749 L13.3788273,21.7280749 L13.0619025,21.7181321 L13.0619025,21.7181321 L12.4977009,21.6983977 L12.4977009,21.6983977 L11.8710143,21.6745607 L11.8710143,21.6745607 L7.13933558,21.4829852 L7.13933558,21.4829852 L6.72742515,21.4682029 L6.72742515,21.4682029 L6.34498659,21.456674 L6.34498659,21.456674 L6.09686954,21.4505645 L6.09686954,21.4505645 L5.89034022,21.4470503 L5.89034022,21.4470503 L5.85440059,21.4467977 L5.85440059,21.4467977 L5.49177154,21.4467698 L5.47434656,19.3894449 L3,19.3721565 L3,3 L18.3165537,3 Z M19.8238141,6.95905369 L18.3078412,6.95905369 L18.3078412,19.3894449 L13.632317,19.3895823 L9.98820016,19.3973733 L9.98820016,19.3973733 L8.36208406,19.4064525 L8.36208406,19.4064525 L6.68538238,19.4240218 L6.7048798,19.9075976 L6.7048798,19.9075976 L6.71239317,20.0521423 L6.71239317,20.0521423 L6.7180572,20.1361208 L6.7180572,20.1361208 L6.7238487,20.2060598 L6.7238487,20.2060598 L6.72942774,20.2584179 L6.72942774,20.2584179 L6.73326809,20.2840185 L6.73326809,20.2840185 L6.73558487,20.293747 C6.73631876,20.2959499 6.73701194,20.2970883 6.73765731,20.2970883 C6.73816981,20.2975967 6.73973745,20.2981351 6.74231059,20.2986999 L6.76050919,20.3014077 L6.76050919,20.3014077 L6.79127644,20.3044006 L6.79127644,20.3044006 L6.8864949,20.3109563 L6.8864949,20.3109563 L7.01992206,20.3177971 L7.01992206,20.3177971 L7.18351396,20.3243528 L7.18351396,20.3243528 L7.36922667,20.3300535 L7.36922667,20.3300535 L7.43465634,20.3316652 L7.43465634,20.3316652 L7.78161444,20.3460667 L7.78161444,20.3460667 L9.21066322,20.4003664 L9.21066322,20.4003664 L10.2250487,20.4403366 L10.2250487,20.4403366 L11.5749412,20.4890593 L11.5749412,20.4890593 L12.6100442,20.5256715 L12.6100442,20.5256715 L15.7541802,20.6445517 L15.7541802,20.6445517 L17.8635044,20.7292994 L17.8635044,20.7292994 L18.2537326,20.7473969 L18.2537326,20.7473969 L18.6375199,20.7623221 L18.6375199,20.7623221 L18.9390029,20.7713244 L18.9390029,20.7713244 L19.0222652,20.7725205 L19.0222652,20.7725205 L19.2400774,20.7811647 L19.2749274,20.6515014 L19.2790201,20.5997003 L19.2790201,20.5997003 L19.282907,20.5293203 L19.282907,20.5293203 L19.2863823,20.4450571 L19.2863823,20.4450571 L19.2892403,20.3516065 L19.2892403,20.3516065 L19.2912752,20.2536642 L19.2912752,20.2536642 L19.2920729,20.188193 L19.2920729,20.188193 L19.2930618,20.0503906 L19.2930618,20.0503906 L19.2984588,19.8140891 L19.2984588,19.8140891 L19.3085402,19.4819934 L19.3085402,19.4819934 L19.3291082,18.9073945 L19.3291082,18.9073945 L19.3435441,18.5468843 L19.3840312,17.5893691 L19.3840312,17.5893691 L19.4753146,15.2747953 L19.4753146,15.2747953 L19.5323994,13.8005906 L19.5323994,13.8005906 L19.7569095,8.31912348 L19.7569095,8.31912348 L19.8063892,7.28753412 L19.8063892,7.28753412 L19.8238141,6.95905369 Z M7.33047293,4.06323931 L4.13262343,4.06323931 L4.13262343,18.2743403 L17.0445305,18.2743403 L17.0445305,4.0978162 L11.1751709,4.07190344 L11.1751709,4.07190344 L8.72055293,4.065322 L8.72055293,4.065322 L7.33047293,4.06323931 L7.33047293,4.06323931 Z M5.85769603,7.51228389 L12.357212,7.51228389 L12.357212,8.67060965 L11.1396631,8.66905096 L11.1396631,8.66905096 L10.2119476,8.66619418 L10.2119476,8.66619418 L8.22526542,8.65707189 L8.22526542,8.65707189 L5.85769603,8.64467698 L5.85769603,8.64467698 L5.85769603,7.51228389 Z M14.8260661,5.92195751 L15.3455954,5.92174704 L15.3455954,5.92174704 L15.3455954,7.11464968 L5.85769603,7.11464968 L5.85769603,5.96496815 L11.1234556,5.93422155 L11.1234556,5.93422155 L12.5770286,5.92742741 L12.5770286,5.92742741 L13.8112893,5.9235505 L13.8112893,5.9235505 L14.8260661,5.92195751 L14.8260661,5.92195751 Z"></path>');
			category.append(newLink);
		});
	}

});
