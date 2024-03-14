jQuery(function($){

	dominator_ui.controllers.search_rules = function( $elm, data, e ){
		if( undefined !== data.enabled ){
			if( data.enabled ){
				$elm.addClass('wcpt-search-enabled');
				$elm.removeClass('wcpt-search-disabled');
			}else{
				$elm.addClass('wcpt-search-disabled');				
				$elm.removeClass('wcpt-search-enabled');
			}
		}

		if( undefined !== data.items ){
			var total_enabled = 0;
			
			$.each(data.items, function(index, item){
				if( 
					item &&
					item.enabled 
				){					
					++total_enabled;
				}
			})

			$elm.children('.wcpt-search__enabled-count')
				.text(total_enabled + ' / ' + data.items.length)
				.removeClass('wcpt-search__enabled-count--all wcpt-search__enabled-count--none');

			if( data.items.length == total_enabled ){
				$elm.children('.wcpt-search__enabled-count').addClass('wcpt-search__enabled-count--all');
			}else if( ! total_enabled ){
				$elm.children('.wcpt-search__enabled-count').addClass('wcpt-search__enabled-count--none');
			}

			$('.wcpt-search__enable-all, .wcpt-search__disable-all', $elm).removeClass('wcpt-search__disable');

			if( total_enabled == data.items.length ){
				$('.wcpt-search__enable-all', $elm).addClass('wcpt-search__disable');
			}
			
			if( ! total_enabled ){
				$('.wcpt-search__disable-all', $elm).addClass('wcpt-search__disable');
			}
		}

		if( e ){ 
		// get

		}else{
		// set
			$('.wcpt-search__enable-all, .wcpt-search__disable-all', $elm)
				.off('click.dom_ui')
				.on('click.dom_ui', function(e){
					e.preventDefault();

					if( $(e.target).hasClass('wcpt-search__disable') ){
						return;
					}

					$.each(data.items, function( index, item ){
						item.enabled = $(e.target).hasClass('wcpt-search__enable-all');
					})
					dominator_ui.init( $elm, data );
			})

		}
	}	

	dominator_ui.controllers.laptop_navigation = function( $elm, data, e ){

		var filters = [];

		// ensure header
		if( typeof data.header === 'undefined' ){
			data.header = {
				rows: []
			};
		}

		// ensure the correct 'position' for left_sidebar elements
		if( data.header.rows.length ){
			// each header row
			$.each(data.header.rows, function(index, row){
				$.each(row.columns, function(c_index, column){
					if(
						column.template &&
						typeof column.template[0] !== 'undefined' &&
						column.template[0].elements &&
						column.template[0].elements.length
					){
						$.each(column.template[0].elements, function(index, filter){
							filters.push($.extend({}, filter));
							if(
								typeof filter.position == 'undefined' ||
								filter.position === 'left_sidebar'
							){
								filter.position = 'header'; // upward change
								$('[data-id="'+ filter.id +'"]').data('wcpt-data', $.extend({}, filter)); // change on block element
							}
						})
					}
				})
			})
		}

		// ensure left_sidebar
		if( typeof data.left_sidebar === 'undefined' ){
			data.left_sidebar = [];
		}

		// ensure the correct 'position' for left_sidebar elements
		if(
			data.left_sidebar.length &&
			data.left_sidebar[0].elements.length
		){
			$.each(data.left_sidebar[0].elements, function(index, filter){
				filters.push($.extend({}, filter));
				if(
					typeof filter.position == 'undefined' ||
					filter.position === 'header'
				){
					filter.position = 'left_sidebar'; // upward change
					$('[data-id="'+ filter.id +'"]').data('wcpt-data', $.extend({}, filter)); // change on block element
				}
			})
		}

		// detect duplicate filters
		var errors = [],
				multi_permitted = ['attribute_filter', 'custom_field_filter', 'taxonomy_filter', 'search', 'html', 'text', 'icon', 'space', 'tooltip', 'media_image', 'apply_reset', 'date_picker_filter'],
				used_singular_filters = {},
				used_attribute_filters = {},
				used_custom_field_filters = {},
				used_taxonomy_filters = {};

		$.each(filters, function( index, filter ){
			if( ! filter.type ){
				return; // continue;
			}

			if( $.inArray( filter.type, multi_permitted ) === -1 ){
				if( typeof used_singular_filters[filter.type] === 'undefined' ){
					used_singular_filters[filter.type] = 1;
				}else{
					used_singular_filters[filter.type] = used_singular_filters[filter.type] + 1;
				}

			}else{
				// multiple instances allowed for these filters but not with same settings
				switch (filter.type) {
					case 'attribute_filter':

						if( typeof filter.attribute_name !== 'undefined' ){
							if( typeof used_attribute_filters[filter.attribute_name] === 'undefined' ){
								used_attribute_filters[filter.attribute_name] = 1;
							}else{
								used_attribute_filters[filter.attribute_name] = used_attribute_filters[filter.attribute_name] + 1;
							}
						}

						break;

					case 'custom_field_filter':

						if( typeof filter.field_name !== 'undefined' ){
							if( typeof used_custom_field_filters[filter.field_name] === 'undefined' ){
								used_custom_field_filters[filter.field_name] = 1;
							}else{
								used_custom_field_filters[filter.field_name] = used_custom_field_filters[filter.field_name] + 1;
							}
						}

						break;

					case 'taxonomy_filter':

						if( typeof filter.taxonomy !== 'undefined' ){
							if( typeof used_taxonomy_filters[filter.taxonomy] === 'undefined' ){
								used_taxonomy_filters[filter.taxonomy] = 1;
							}else{
								used_taxonomy_filters[filter.taxonomy] = used_taxonomy_filters[filter.taxonomy] + 1;
							}
						}

						break;

					default:
				}

			}
		})

		// gather singular filter errors
		$.each(used_singular_filters, function(filter, count){
			if( count > 1 ){

				switch (filter) {
					case 'download_csv':
						filter = 'Download CSV';		
						break;
				
					default:
						filter = (filter.charAt(0).toUpperCase() + filter.slice(1)).replace(/_/g, " "); // uppercase first char and replace _ with space
				}

				errors.push('You are using "' + filter + '" ' + count + ' times. Please use only once to avoid errors.' );
			}
		})

		// gather attribute filter errors
		$.each(used_attribute_filters, function(attribute_name, count){
			if( count > 1 ){
				errors.push('You are using the "Attribute filter" with the attribute "' + attribute_name + '" ' + count + ' times. Please use only once to avoid errors.' );
			}
		})

		// gather custom field filter errors
		$.each(used_custom_field_filters, function(field_name, count){
			if( count > 1 ){
				errors.push('You are using the "Custom field filter" with the field name "' + field_name + '" ' + count + ' times. Please use only once to avoid errors.' );
			}
		})

		// gather taxonomy filter errors
		$.each(used_taxonomy_filters, function(taxonomy, count){
			if( count > 1 ){
				errors.push('You are using the "Taxonomy filter" with the taxonomy "' + taxonomy + '" ' + count + ' times. Please use only once to avoid errors.' );
			}
		})

		if( ! errors.length ){
			$('.wcpt-navigation-errors').hide();

		}else{
			$('.wcpt-navigation-errors').show();
			$('.wcpt-navigation-errors .wcpt-navigation-errors__warning').remove();
			var errors = '<li class="wcpt-navigation-errors__warning">' + errors.join('</li><li class="wcpt-navigation-errors__warning">') + '</li>';
			$('.wcpt-navigation-errors .wcpt-navigation-errors__warnings').html(errors);

		}
	}

	dominator_ui.controllers.taxonomy_terms = function( $elm, data, e ){

		if( e ){ // get

		}else{ // set
			var $term = $('[wcpt-model-key="term"]'),
					$taxonomies = $('[wcpt-model-key="taxonomy"]');

			$taxonomies.off('change.wcpt_get_terms').on('change.wcpt_get_terms', function(){
				var $this = $(this),
						taxonomy = $this.val(),
						$term = $this.siblings('[wcpt-model-key="term"]'),
						term = $term.data('wcpt-data'),
						$loading = $this.siblings('.wcpt-loading-term');

				if( $term.attr('data-wcpt-for-taxonomy') == taxonomy  ){
					return;
				}

				$.ajax({
					type: "POST",
					url: ajaxurl,

					beforeSend: function(){
						$term.hide();
						$loading.show();
					},

					data: {
						action: 'wcpt_get_attribute_terms',
						taxonomy: taxonomy,
					},

					success: function(data){
						if( ! data  ) {
							return;
						}

						$term.attr('data-wcpt-for-taxonomy', taxonomy);
						$term.html('<option value="">Select a term</option>');

						$.each(data, function(key, term){
							$term.append('<option value="'+ term.slug +'">'+ term.name +'</option>');
						})

						if( term ){
							$term.val(term);
						}

						$term.show();
						$loading.hide();
					}
				});
			})

			$term.each(function(){
				var $this = $(this),
						$taxonomy = $this.siblings('[wcpt-model-key="taxonomy"]'),
						term = $this.data('wcpt-data');

				if( $taxonomy.val() ){
					$taxonomy.change();
				}
			})

		}
	}

	dominator_ui.controllers.category = function( $elm, data, e ){
		var $hierarchy = $elm.closest('.wcpt-hierarchy');

		if( e ){ // get

			var $target = $(e.target),
					checked = $target.prop('checked');

	    // find and check any child terms
	    if ($target.hasClass('wcpt-hr-parent-term')) {
	      var ct_selector 	= 'input[type=checkbox], input[type=radio]',
	        	$child_terms 	= $target.closest('label').siblings('.wcpt-hr-child-terms-wrapper').find(ct_selector);
	      $child_terms.prop('checked', checked);
	    }

	    // find and uncheck any parent terms
	    var $ancestors = $target.parents('.wcpt-hr-child-terms-wrapper');
	    if ($ancestors.length) {
	      $ancestors.each(function() {
	        var $parent_term = $(this).siblings('label').find('.wcpt-hr-parent-term');
	        $parent_term.prop('checked', false);
	      })
	    }

			var tt_id = [];
			$('input[type=checkbox], input[type=radio]', $hierarchy).filter(':checked').each(function(){
				tt_id.push($(this).val());
			})

			// upstream
			var $parent = $elm.data('wcpt-parent');
			$parent.data('wcpt-data')['category'] = tt_id;
			$parent.trigger('change');

		}else{ // set

			// reveal selected sub cats
			// -- cache last child
			if( ! $hierarchy.data('wcpt-last-child') ){
				var $last_child = $( 'input[type=checkbox], input[type=radio]', $hierarchy ).last();
				$hierarchy.data('wcpt-last-child', $last_child);
			}

			// if at last child reveal checked cats
			if( $hierarchy.data('wcpt-last-child')[0] === $elm[0] ){
				$hierarchy.find( 'input[type=checkbox], input[type=radio]' ).filter(':checked').each(function(){
					var $sc_wrap = $(this).parents('.wcpt-hr-child-terms-wrapper');
					$sc_wrap.parent().addClass('wcpt-show-sub-categories');
				})
			}
		}

		// break cat term cache on change
		// otherwise nav/col cat elm will keep
		// getting same labels from cache even
		// when user has changed query > cats
		if( 
			typeof wcpt_terms_cache != 'undefined' && 
			wcpt_terms_cache.product_cat 
		){
      delete wcpt_terms_cache.product_cat;
    }
	}

	dominator_ui.controllers.columns = function( $elm, data, e ){
		// update column device tabs		
		var $device_tabs = $elm.children('.wcpt-editor-tab-columns__device-tabs');
		$device_tabs.data('wcpt-columns', data);
		$device_tabs.trigger('update.wcpt');
	}

	dominator_ui.controllers.device_columns = function( $elm, data, e ){
		var device = $elm.attr('wcpt-model-key'),
				$columns = $elm.data('wcpt-children'),
				$no_columns_message = $elm.find('.wcpt-no-device-columns-message');

		// 'no device columns' message
		$no_columns_message.toggleClass('wcpt-hide', !! data.length);
		$elm.toggleClass('wcpt-editor-columns-container--empty', ! data.length)

		$columns.each(function(){
			var $column = $(this);

			// update column index
			var column_index = $column.attr('wcpt-model-key-index'),
					$column_index = $column.find('.wcpt-column-count');

			$column_index
				.text( parseInt( column_index ) + 1 )
				.attr('title', (device[0].toUpperCase() + device.slice(1)) + ' column #' + ( parseInt( column_index ) + 1 ) )

			// update column device icon
			var device_icon = {
						'laptop': 'square', 
						'tablet': 'tablet', 
						'phone': 'smartphone'
					},
					$device_icon = $column.find('.wcpt-column-device-icon-container');

			$device_icon.html( '<img class="wcpt-column-device-icon wcpt-column-device-icon--'+ device +'" src="'+ window.wcpt_icons_url + '/' + device_icon[device] + '.svg" />' );
		});
		
	}

	dominator_ui.controllers.column_settings = function( $elm, data, e ){
		if( ! e ){ // set

			// ensure ids
			if( ! window.wcpt_timestamp ){
				window.wcpt_timestamp = Date.now();
			}

			if( ! data.heading.id || ! data.cell.id ){
				data.heading.id = window.wcpt_timestamp++;
				data.cell.id = window.wcpt_timestamp++;
			}

			// init tabs
			$('.wcpt-tabs', $elm).wcpt_tabs();

			// init block editors
			// -- heading
			$('.wcpt-column-heading-editor', $elm).wcpt_block_editor({
				add_element_partial: 'add-column-heading-element',
				edit_row: false,
				add_row: false,
				connect_with: '.wcpt-column-heading-editor .wcpt-block-editor-row',
				data: data.heading.content,
			});

			// -- cell template
			$('.wcpt-column-template-editor', $elm).wcpt_block_editor({
				add_element_partial: 'add-column-cell-element',
				edit_row: 'cell-row',
				add_row: true,
				connect_with: '.wcpt-column-template-editor .wcpt-block-editor-row',
				data: data.cell.template,
			});

		}else{ // get

		}
	}

	dominator_ui.controllers['edit-element-lightbox'] = function( $elm, data, e ){

		// mark active style prop options
		var active_html_class = 'wcpt-editor-active-option';

		// -- style
		// -- -- remove previous feedback
		$elm.find(`.${active_html_class}`).removeClass(`${active_html_class}`); 
		$elm.find('.wcpt-editor-active-props-count').remove(); 				

		if( data.style ){
			var used_style_props = [];

			for ( const [selector, props] of Object.entries(data.style) ){
				for( const [prop, value] of Object.entries( props ) ){
					if( value ){
						used_style_props.push( {
							selector: selector,
							prop: prop
						} );
					}
				}
			}

			// -- -- highlight active props
			used_style_props.forEach(( selector_props )=>{
				$elm
					.find( `[wcpt-model-key="${selector_props.selector}"]` )
						.find( `[wcpt-model-key="${selector_props.prop}"]` )
							.addClass(active_html_class);
			});

			// -- -- show selector > active prop count
			$elm.find('[wcpt-model-key="style"] > div').each(function(){
				var $this__style_ops_accordion = $(this),
						active_prop_count = $this__style_ops_accordion.find(`.${active_html_class}`).length;
				if( active_prop_count ){
					$('.wcpt-toggle-label', $this__style_ops_accordion).append(`<span class="wcpt-editor-active-props-count" title="Helps you know at a glance how many options are being used">${active_prop_count} option${ active_prop_count > 1 ? 's' : '' }</span>`);
				}
			});
		}

		// -- condition
		// -- -- mark active condition prop options
		if( data.condition ){
			var active_condition_props = [];
					conditions = [
						'custom_field_enabled',
						'attribute_enabled',
						'category_enabled',
						'price_enabled',
						'stock_enabled',
						'product_type_enabled',
						'store_timings_enabled',
						'user_role_enabled'
					];

			conditions.forEach((i)=>{
				if( data.condition[i] ){
					active_condition_props.push(i);
				}
			});

			// -- -- highlight active props
			active_condition_props.forEach(( condition_prop )=>{
				$elm
					.find( `[wcpt-model-key="condition"] [wcpt-model-key="${condition_prop}"]` )
						.addClass(active_html_class);
			});

			// -- -- show selector > active prop count
			$elm.find('[wcpt-model-key="condition"] > .wcpt-toggle-label').each(function(){
				var $this__toggle_label = $(this);
				if( active_condition_props.length ){
					$this__toggle_label.append(`<span class="wcpt-editor-active-props-count" title="Helps you know at a glance how many options are being used">${active_condition_props.length} option${ active_condition_props.length > 1 ? 's' : '' }</span>`);
				}
			});
		}		

		if( ! e ){ // set

			// select icon
			$('.wcpt-select-icon').select2({
				templateResult: function(icon){
					var img = '<img class="wcpt-icon-rep" src="' + wcpt_icons_url + '/' + icon.id + '.svg">',
				  		$icon = $('<span>' + img + '<span class="wcpt-icon-name">' + icon.text + '</span>' + '</span>');
				  return $icon;
				},
				templateSelection: function(icon){
					var img = '<img class="wcpt-icon-rep" src="' + wcpt_icons_url + '/' + icon.id + '.svg">',
				  		$icon = $('<span>' + img + '<span class="wcpt-icon-name">' + icon.text + '</span>' + '</span>');
				  return $icon;
				},
				dropdownParent: $elm,
			});

			// media image
			if( data.media_id !== 'undefined' && data.url !== 'undefined' ){
				var $url = $elm.data('wcpt-children').filter('[wcpt-model-key="url"]'),
						$img = data.url ? $('<img src="'+ data.url +'">') : '',
						$button = $url.siblings('.wcpt-select-media-button'),
			  		mediaUploader = null;
				$url.siblings('.wcpt-selected-media-display').html($img);

				$button.on('click', function(e){
			    e.preventDefault();
			    var $this = $(this);

			    // If the uploader object has already been created, reopen the dialog
			      if (mediaUploader) {
			      mediaUploader.open();
			      return;
			    }

			    // Extend the wp.media object
			    mediaUploader = wp.media.frames.file_frame = wp.media({
			      title: 'Choose Image',
			      button: {
			      text: 'Choose Image'
			    }, multiple: false });

			    // When a file is selected, grab the URL and set it as the text field's value
			    mediaUploader.on('select', function() {
			      attachment = mediaUploader.state().get('selection').first().toJSON();
			      $this.siblings('[wcpt-model-key="media_id"]').val(attachment.id).change();
			      $this.siblings('[wcpt-model-key="url"]').val(attachment.url).change();
			      $this.siblings('.wcpt-selected-media-display').html('<img style="" src="'+ attachment.url +'"/>');
			    });

			    // Open the uploader dialog
			    mediaUploader.open();

			  });

			}

			// relabel rules
			if(
				(
					data.attribute_name && 
					data.attribute_name !== '_custom' &&
					(
						typeof data.relabels === 'undefined' ||
						typeof window.wcpt_terms_cache === 'undefined' ||
						typeof window.wcpt_terms_cache[data.attribute_name] === 'undefined'
					)
				) ||
				data.taxonomy
			){

				// show 'loading...'
				$elm.find('[data-loading="terms"]').show();
				$elm.find('[wcpt-model-key="relabels"]').hide();

				var taxonomy = data.taxonomy ? data.taxonomy : 'pa_' + data.attribute_name,
						limit_terms;
				if( taxonomy == 'product_cat' ){
					limit_terms = $.extend( [], wcpt.data.query.category );
				}

				verify_terms( data.relabels, taxonomy, limit_terms ).then(function(modified_terms){

					if( modified_terms ){

						// update data
						data.relabels = $.extend(true, [], modified_terms);

						// limit number of terms length
						data.relabels.length = Math.min(data.relabels.length, 50);

						// downstream
						var $relabels = $('[wcpt-model-key="relabels"]', $elm);
						if( $elm.is('[data-partial="attribute_filter"], [data-partial="category_filter"], [data-partial="taxonomy_filter"]') ){
							$relabels.html( dominator_ui.row_templates.relabel_rule_term_filter_element_2 ); // row template
						}else{
							$relabels.html( dominator_ui.row_templates.relabel_rule_term_column_element_2 ); // row template
						}
						dominator_ui.init($relabels, data.relabels);

						// upstream
						$elm.change();

					}

					// hide 'loading...'
					$elm.find('[data-loading="terms"]').hide();
					$elm.find('[wcpt-model-key="relabels"]').show();

				})

			}

		} else { // get

			// attribute changed
			if( $(e.target).is('select[wcpt-model-key="attribute_name"], select[wcpt-model-key="taxonomy"]') ){

				// custom attribute - no relabels.. bail
				if( 
					data.attribute_name &&
					data.attribute_name === '_custom'
				){
					return;
				}

				// select_variation.. bail
				if( $(e.target).closest('[wcpt-row-template="identify_variation"]').length ){
					return;
				}

				// show 'loading...'
				$elm.find('[data-loading="terms"]').show();
				$elm.find('[wcpt-model-key="relabels"]').hide();

				var taxonomy = data.taxonomy ? data.taxonomy : 'pa_' + data.attribute_name;

				get_terms( taxonomy ).then(function(terms){ // async

					// limit length
					terms.length = Math.min(terms.length, 50);

					// update data
					data.relabels = $.extend(true, [], terms);

					// downstream
					var $relabels = $('[wcpt-model-key="relabels"]', $elm);
					if( $elm.is('[data-partial="attribute_filter"], [data-partial="category_filter"], [data-partial="taxonomy_filter"]') ){
						$relabels.html( dominator_ui.row_templates.relabel_rule_term_filter_element_2 ); // row template
					}else{
						$relabels.html( dominator_ui.row_templates.relabel_rule_term_column_element_2 ); // row template
					}
					dominator_ui.init($relabels, data.relabels);

					// upstream
					$elm.change();

					// hide 'loading...'
					$elm.find('[data-loading="terms"]').hide();
					$elm.find('[wcpt-model-key="relabels"]').show();

				});
			}
		}

		// always 

		// custom field range & price - auto 'single:true'		
		if( 
			data.type == 'price_filter' ||
			data.type == 'rating_filter' ||
			(
				data.type == 'custom_field_filter' &&
				data.compare == 'BETWEEN'
			)
		){
			if( ! data.single ){				
				$elm.find('[wcpt-model-key="single"]').prop('checked', true);
				data.single = true;
				$elm.change(); // refresh required to trigger prop conditions
			}
		}		
	}

	dominator_ui.controllers.relabels = function( $elm, data, e ){
		if( ! e ){ // set
			var $tabs = $( '.wcpt-tabs', $elm ).wcpt_tabs(),
					tabs = $tabs.data('wcpt_tabs');

			// enable clear label tab if clear label field has been set previously
			if( data.clear_label ){
				tabs.ctrl.enable_tab_index(1);
			}

			// remove clear label field if clear label tab is disabled
			$tabs.on('tab_disabled', function(e, index){
				if( index == 1 ){
					$elm.find('input[wcpt-model-key="clear_label"]').val('').change();
				}
			})

		}else{ // get

		}
	}

	dominator_ui.controllers.rating_options = function( $elm, data, e ){
		if( ! e ){ // set
			var $tabs = $( '.wcpt-tabs', $elm ).wcpt_tabs(),
					tabs = $tabs.data('wcpt_tabs');

			// enable clear label tab if clear label field has been set previously
			if( data.clear_label ){
				tabs.ctrl.enable_tab_index(1);
			}

			// remove clear label field if clear label tab is disabled
			$tabs.on('tab_disabled', function(e, index){
				if( index == 1 ){
					$elm.find('input[wcpt-model-key="clear_label"]').val('').change();
				}
			})

		}else{ // get

		}
	}

	dominator_ui.controllers.range_options = function( $elm, data, e ){
		if( ! e ){ // set
			var $tabs = $( '.wcpt-tabs', $elm ).wcpt_tabs(),
					tabs = $tabs.data('wcpt_tabs');

			// enable clear label tab if clear label field has been set previously
			if( data.clear_label ){
				tabs.ctrl.enable_tab_index(1);
			}

			// remove clear label field if clear label tab is disabled
			$tabs.on('tab_disabled', function(e, index){
				if( index == 1 ){
					$elm.find('input[wcpt-model-key="clear_label"]').val('').change();
				}
			})

		}else{ // get

		}
	}

	dominator_ui.controllers.manual_options = function( $elm, data, e ){
		if( ! e ){ // set
			$( '.wcpt-tabs', $elm ).wcpt_tabs();

		}else{ // get

		}
	}

	// property list row
	dominator_ui.controllers.property_list_row = function( $elm, data, e ){
		if( ! e ){ // set
			var $tabs = $( '.wcpt-tabs', $elm ).wcpt_tabs(),
					tabs = $tabs.data('wcpt_tabs');

		}else{ // get

		}
	};

	// archive override row
	dominator_ui.controllers.archive_override_row = function( $elm, data, e ){
		if( ! e ){ // set
			$( 'select[multiple]', $elm ).select2({ 
				multiple: true, 
				closeOnSelect: false, 
				width: '100%' 
			});

		}else{ // get
				
		}
	}

	// download csv column heading
	dominator_ui.controllers.download_csv_column = function( $elm, data, e ){
		if( ! e ){ // set
		}else{ // get
			if( ! data.column_heading ){
				$('input[wcpt-model-key="column_heading"]', $elm).css({
					'border-color': '#e53935',
					'background': 'rgb(244 67 54 / 5%)'
				})
			}else{
				$('input[wcpt-model-key="column_heading"]', $elm).css({
					'border-color': '',
					'background': ''
				})
			}

		}
	}

	/* initial data */

	// sku
	dominator_ui.initial_data.element_sku = {
		variable_switch: true,
		style: {},
		condition: {},
	};

	// custom field filter
	dominator_ui.initial_data.element_custom_field_filter = {
		heading: false,
		display_type: 'dropdown',
		manager: '',
		acf_field_type: 'basic',
		manual_options: [],
		range_options: [],
		compare: 'IN',
		show_all_label: 'Show all',
		field_type: 'NUMERIC',
		field_type__exact_match: 'CHAR',
		order__exact_match: 'ASC',
		non_numeric_value_treatement: 'convert',
		ignore_values: 'n.a.| n/a | - | _ | * ',
		empty_value_treatement: 'exclude',
		heading_format__op_selected: 'only_heading',		
		search_enabled: false,
		search_placeholder: 'Search',		
	};

	//-- manual option
	dominator_ui.initial_data.custom_field_filter_manual_option = {
		'label': '[custom_field_value]',
	};

	//-- range option
	dominator_ui.initial_data.custom_field_filter_range_option = {
		'label': '[custom_field_value]',
	};

	// date picker filter
	dominator_ui.initial_data.element_date_picker_filter = {
		date_source: 'publish_date',
		custom_field_type: 'numeric',
		acf_field_type: 'date_picker',
		heading: 'Date picker',
		display_type: 'dropdown',
		heading_format__op_selected: 'only_heading',
		filter_option_labels: {
			start_date	: 'Events from date:',
			end_date		: 'Events until date:',
			apply				: 'Apply',
			reset				: 'Reset',
		},
		clear_filter_labels: {
			start_date	: 'From date: {date}',
			end_date		: 'Until date: {date}',
			date_format : 'j M, Y',
		},
		style: {}
	};	

	// date
	dominator_ui.initial_data.element_date = {
		format: 'j M, Y',
		date_source: 'publish_date',
		custom_field_type: 'numeric',
		acf_field_type: 'date_picker',
		condition: {},
		style: {},
	};

	// attribute filter
	dominator_ui.initial_data.element_attribute_filter = {
		display_type: 'dropdown',
		relabels: [],
		heading: [{"style":{},"elements":[{"type":"text","style":{},"text":"[attribute] "}],"type":"row"}],
		show_all_label: 'Show all',
		click_action: false,
		heading_format__op_selected: 'only_heading',
		search_enabled: false,
		search_placeholder: 'Search [attribute]',		
	};

	//-- relabel
	dominator_ui.initial_data.attribute_filter_relabel_rule = {
		label: '[term_name]',
	};

	// taxonomy filter
	dominator_ui.initial_data.element_taxonomy_filter = {
		display_type: 'dropdown',
		relabels: [],
		heading: [{"style":{},"elements":[{"type":"text","style":{},"text":"[taxonomy] "}],"type":"row"}],
		heading_format__op_selected: 'only_heading',								
		show_all_label: 'Show all',
		pre_open_depth: 1,
		click_action: false,
		search_enabled: false,
		search_placeholder: 'Search [taxonomy]',
	};

	//-- relabel
	dominator_ui.initial_data.taxonomy_filter_relabel_rule = {
		label: '[term_name]',
	};

	// tags filter
	dominator_ui.initial_data.element_tags_filter = {
		display_type: 'dropdown',
		relabels: [],
		taxonomy: 'product_tag',		
		heading: [{"style":{},"elements":[{"type":"text","style":{},"text":"Product tags "}],"type":"row"}],
		heading_format__op_selected: 'only_heading',
		show_all_label: 'Show all',
		pre_open_depth: 1,
		click_action: false,
		search_enabled: false,
		search_placeholder: 'Search Tags',
	};

	// //-- relabel
	// dominator_ui.initial_data.tags_filter_relabel_rule = {
	// 	label: '[term_name]',
	// };

	// search
	dominator_ui.initial_data.element_search = {
		heading: '',
		placeholder: 'Search',
		clear_label: 'Search: "[kw]"',
		target: ['title', 'content'],
		custom_fields: [],
		attributes: [],
		keyword_separator: ' ',
		reset_others: true,
	};

	// checkbox
	dominator_ui.initial_data.element_checkbox = {
		heading_enabled: false,
		style: {},
		condition: {},
	};	

	// add selected to cart
	dominator_ui.initial_data.element_add_selected_to_cart = {
		add_selected_label: 'Add selected ({total_qty}) for {total_cost}',
		add_selected_label__single_item: '',
		add_selected__unselected_label: 'Add selected items to cart',
		select_all_label: 'Select all',
		clear_all_label: 'Clear all',

		select_all_enabled: true,
		clear_all_enabled: true,
		duplicate_enabled: true,

		style: {
			'[id].wcpt-add-selected--unselected > .wcpt-add-selected__add': {
				opacity: 0.5,
			},
		}
	};	

	// tooltip
	dominator_ui.initial_data.element_tooltip__nav = {
		label: [{"style":{},"condition":{},"elements":[{"type":"icon","style":{},"name":"help-circle"}],"type":"row"}],
		content: 'This content will appear when label is hovered.',
		hover_permitted: true,
		trigger: 'hover',
		style: {},
		condition: {},
	};

	// tooltip
	dominator_ui.initial_data.element_tooltip = {
		label: [{"style":{},"condition":{},"elements":[{"type":"icon","style":{},"name":"help-circle"}],"type":"row"}],
		content: 'This content will appear when label is hovered.',
		hover_permitted: true,
		trigger: 'hover',
		style: {},
		condition: {},
	};

	// result count filter
	dominator_ui.initial_data.element_result_count = {
		message: 'Showing [first_result] – [last_result] of [total_results] results',
		single_page_message: 'Showing all [total_results] results',
		single_result_message: 'Showing the single result',
		no_results_message: '',
		style: {}
	};

	// availability filter
	dominator_ui.initial_data.element_availability_filter = {
		display_type: 'dropdown',
		heading: 'In Stock',
		hide_label: 'Hide out of stock',
	};

	// on sale filter
	dominator_ui.initial_data.element_on_sale_filter = {
		display_type: 'dropdown',
		heading: 'On Sale',
		on_sale_label: 'Only on sale items',
	};

	// category filter
	dominator_ui.initial_data.element_category_filter = {
		display_type: 'dropdown',
		heading: 'Category',
		heading_format__op_selected: 'only_heading',								
		relabels: [],
		show_all_label: 'Show all',
		taxonomy: 'product_cat',
		hide_empty: false,
		pre_open_depth: 0,
		click_action: false,
		search_enabled: false,
		search_placeholder: 'Search Category',
	};

	// csv download
	dominator_ui.initial_data.element_download_csv = {
		label: 'Download CSV',
		columns: [
			{
				column_heading: 'Name',
				property: 'title',
			},

			{
				column_heading: 'SKU',				
				property: 'sku',
			},			

			{
				column_heading: 'Regular price',
				property: 'regular_price',
			},

			{
				column_heading: 'Sale price',
				property: 'sale_price',
			},
		],
	};


	// sort by
	dominator_ui.initial_data.element_sort_by = {
		dropdown_options: [

			// popularity (sales)
			{
				orderby: 'popularity',
				order: 'DESC',
				meta_key: '',
				label: 'Sort by Popularity',
			},

			// rating
			{
				orderby: 'rating',
				order: 'DESC',
				meta_key: '',
				label: 'Sort by Rating',
			},

			// price - ASC
			{
				orderby: 'price',
				order: 'ASC',
				meta_key: '',
				label: 'Sort by Price low to high',
			},

			// price - DESC
			{
				orderby: 'price-desc',
				order: 'DESC',
				meta_key: '',
				label: 'Sort by Price high to low',
			},

			// date
			{
				orderby: 'date',
				order: 'DESC',
				meta_key: '',
				label: 'Sort by Newness',
			},

			// title - ASC
			{
				orderby: 'title',
				order: 'ASC',
				meta_key: '',
				label: 'Sort by Name A - Z',
			},

			// title - DESC
			{
				orderby: 'title',
				order: 'DESC',
				meta_key: '',
				label: 'Sort by Name Z - A',
			},

		],
	};

	// sort by - option
	dominator_ui.initial_data.sortby_option = {
		orderby: 'price-desc',
		order: 'DESC',
		meta_key: '',
		label: 'Sort by ...',
	};

	// results per page
	dominator_ui.initial_data.element_results_per_page = {
		heading: '[limit] per page',
		dropdown_options: [
			{
				label: '10 per page',
				results: 10,
			},
			{
				label: '20 per page',
				results: 20,
			},
			{
				label: '30 per page',
				results: 30,
			},
		],
	};

	// results per page - option
	dominator_ui.initial_data.results_per_page_option = {
		results: 10,
		label: '10 per page',
	};

	// category
	dominator_ui.initial_data.element_category = {
		separator: ' ⋅ ',
		empty_relabel: false,
		relabels: [],
		taxonomy: 'product_cat',
		style: {},
		condition: {},
	};

	// attribute
	dominator_ui.initial_data.element_attribute = {
		separator: ' ⋅ ',
		empty_relabel: false,
		relabels: [],
		click_action: '',
		condition: {},
	};

	//-- relabel
	dominator_ui.initial_data.relabels = [];

	// attribute
	dominator_ui.initial_data.element_taxonomy = {
		separator: ' ⋅ ',
		empty_relabel: false,
		relabels: [],
		style: {},
		condition: {},
	};

	// tags
	dominator_ui.initial_data.element_tags = {
		separator: ' ⋅ ',
		empty_relabel: false,
		relabels: [],
		taxonomy: 'product_tag',
		click_action: '',
		style: {},
		condition: {},
	};

	// custom_field
	dominator_ui.initial_data.element_custom_field = {
		default_relabel: '[custom_field_value]',
		empty_relabel: '',
		relabel_rules: [],
		manager: '',
		media_img_size: 'thumbnail',
		img_val_type: 'url',
		display_as: 'text',
		pdf_link_label: 'Download',
		pdf_val_type: 'url',
		style: {},
		condition: {},
	};

	//-- relabel
	dominator_ui.initial_data.custom_field_relabel_rule = {
		label: '[custom_field_value]',
		compare: '=',
	};

	// dot
	dominator_ui.initial_data.element_dot = {
		style: {},
	};

	// dot__col
	dominator_ui.initial_data.element_dot__col = {
		style: {},
		condition: {},
	};

	// space
	dominator_ui.initial_data.element_space = {
		width: '',
		style: {},
	};

	// space__col
	dominator_ui.initial_data.element_space__col = {
		width: '',
		style: {},
		condition: {},
	};

	// title
	dominator_ui.initial_data.element_title = {
		link: 'product_page',
		custom_field: '',
		custom_field_default_product_page: true,
		style: {},
		condition: {},
	};

	// content
	dominator_ui.initial_data.element_content = {
		limit: '',
		toggle_enabled: false,
		show_more_label: 'Show more (+)',
		show_less_label: 'Show less (-)',
		read_more_label: '',
		truncation_symbol: '',
		shortcode_action: '',
		style: {},
		condition: {},
	};

	// excerpt
	dominator_ui.initial_data.element_excerpt = {
		limit: '',
		toggle_enabled: false,
		show_more_label: 'Show more (+)',
		show_less_label: 'Show less (-)',
		read_more_label: '',
		truncation_symbol: '',		
		style: {},
		condition: {},
	};

	// short description
	dominator_ui.initial_data.element_short_description = {
		limit: '',
		generate: true,
		toggle_enabled: false,
		show_more_label: 'Show more (+)',
		show_less_label: 'Show less (-)',
		read_more_label: '',
		truncation_symbol: '',		
		style: {},
		condition: {},
	};	

	// total
	dominator_ui.initial_data.element_total = {
		output_template: '{n}',
		no_output_template: '',
		variable_switch: true,
		style: {},
		condition: {},
	};	

	// text
	dominator_ui.initial_data.element_text = {
		text: '',
		style: {},
	};

	// text__col
	dominator_ui.initial_data.element_text__col = {
		text: '',
		style: {},
		condition: {},
	};

	// html
	dominator_ui.initial_data.element_html = {
		html: '',
		style: {},
	};

	// html__col
	dominator_ui.initial_data.element_html__col = {
		html: '',
		style: {},
		condition: {},
	};	

	// select__variation
	dominator_ui.initial_data.element_select__variation = {
		type: 'radio',
		style: {},
		condition: {},
	};

	// select__variation
	dominator_ui.initial_data.element_select__variation = {
		type: 'radio',
		style: {},
		condition: {},
	};

	// price__variation
	dominator_ui.initial_data.element_price__variation = {
		style: {},
		condition: {},
	};

	// price__variation
	dominator_ui.initial_data.element_quantity = {
		condition: {},
		display_type: 'input',
		controls: 'browser',
		qty_label: 'Qty: ',
		max_qty: 10,
		initial_value: 'min',
		qty_warning: 'Max: [max]',
		min_qty_warning: 'Min: [min]',
		qty_step_warning: 'Step: [step]',
		return_to_initial: true,
		style: {},
		condition: {},
	};

	// property list
	dominator_ui.initial_data.element_property_list = {
		show_more_label: 'Show more',
		show_less_label: 'Show less',
		rows: [{
				"property_name":[{"style":{},"elements":[],"type":"row"}],
				"property_value":[{"style":{},"elements":[],"type":"row"}],
				"condition":{"action":"show","product_type":[]},
			}],
		initial_reveal: 4,
		columns: 1,
		style: {},
		condition: {},
	};

	// property list row
	dominator_ui.initial_data.property_list_row = {
		property_name: false,
		property_value: false,
		condition: {
			action: 'show',
		},
		style: {},
	};

	// dimensions
	dominator_ui.initial_data.element_dimensions = {
		variable_switch: true,				
		condition: {},
		style: {},
	};

	// on sale
	dominator_ui.initial_data.element_on_sale = {
		"template":[{"style":[],"elements":[{"type":"text","style":{"[id]":{}},"text":"-[percent_diff]%"}],"type":"row"}],
		"style":{"[id]":{}},
		"variable_switch": false, 
		"condition": {}
	};

	// availability
	dominator_ui.initial_data.element_availability = {
		out_of_stock_message: 'Out of stock',
		single_stock_message: 'Only 1 left',
		on_backorder_message: 'On backorder',
		on_backorder_managed_message: '[stock] left (can backorder)',
		low_stock_threshold: 3,
		low_stock_message: 'Only [stock] left',
		in_stock_message: 'In stock',
		in_stock_managed_message: '[stock] in stock',
		variable_switch: true,		
		style: {},
		condition: {},
	};

	// stock
	dominator_ui.initial_data.element_stock = {
		range_labels: '',
		style: {},
		condition: {},
		variable_switch: true,
	};

	// product_id
	dominator_ui.initial_data.element_product_id = {
		variable_switch: true,		
		style: {},
		condition: {},
	};

	// price
	dominator_ui.initial_data.element_price = {
		"sale_template":[{"style":{},"elements":[
			{"type":"sale_price","style":{".wcpt-product-on-sale [id]":{}}},
			{"type":"regular_price","style":{".wcpt-product-on-sale [id]":{}}}
		]}],

		"template":[{"style":{},"elements":[
			{"type":"regular_price","style":{".wcpt-product-on-sale [id]":{}}}
		]}],

		"variable_template":[{"style":[],"elements":[
			{"type":"lowest_price","style":{"[id]":{}}},
			{"type":"text","style":{"[id]":{"margin-right":"6px"}},"text":"-"},
			{"type":"highest_price","style":{"[id]":{}}}
		]}],"style":{"[id]":{}},"type":"price",

		style: {},
		condition: {},
	};

	// product image
	dominator_ui.initial_data.element_product_image = {
		size: 'thumbnail',
		placeholder_enabled: true,
		click_action: 'product_page',
		zoom_trigger: '',
		zoom_scale: '2.0',
		custom_zoom_scale: '1.75',
		icon_when: 'always',
		lightbox_color_theme: 'black',		
		include_gallery: true,
		style: {
			'[id]': {},
			'[id] > .wcpt-lightbox-icon': {},
		},
		condition: {},
	};

	// gallery
	dominator_ui.initial_data.element_gallery = {
		max_images: 3,
		see_more_label: '+{n} more',
		include_featured: false,
		lightbox_color_theme: 'black',
		style: {},
		condition: {},
	};	

	// icon
	dominator_ui.initial_data.element_icon = {
		name: 'chevron-right',
		style: {},
	};

	// icon__col
	dominator_ui.initial_data.element_icon__col = {
		name: 'chevron-right',
		style: {},
		condition: {},
	};

	// button
	dominator_ui.initial_data.element_button = {
		label: 'Buy here',
		target: '_self',
		custom_field: '',
		link: 'product_link',
		custom_field_empty_relabel: false,
		use_default_template: false,
		condition: {},
	};

	// select variation
	dominator_ui.initial_data.element_select_variation = {
		display_type: 'dropdown',

		// naming rules
		hide_attributes: true,
		attribute_term_separator: ': ',
		attribute_separator: ', ',

		//radio_single
		variation_name: '',
		template: [{"style":{},"elements":[{"type":"select__variation","style":{},"condition":[],"html_class":""},{"text":"[variation_name]: ","style":{},"condition":[],"type":"text"},{"style":{},"condition":[],"type":"price__variation","html_class":""}],"type":"row"}],
		attribute_terms: [{'taxonomy': '', 'term': ''}],
		not_exist_template: false,
		out_of_stock_template: false,
		non_variable_template: false,

		style: {
			'[id] > .wcpt-select-variation-dropdown': {},
			'[id].wcpt-select-varaition-radio-multiple-wrapper': {},
		},
		condition: {},
	};

	// cart form
	dominator_ui.initial_data.element_cart_form = {
		visible_elements: [
			'quantity',
			'button',
			'availability',
			'variation_description',
			'variation_price',			
			'variation_attributes',
		],
		style: {},
		condition: {},
	};

	// shortcode
	dominator_ui.initial_data.element_shortcode = {
		shortcode: '',
		style: {},
		condition: {},
	};

	// product link
	dominator_ui.initial_data.element_product_link = {
		suffix   : '',
		template : 'View details',
		target   : '_self',
		condition: {},
		style: {},
	};

	// apply / reset
	dominator_ui.initial_data.element_apply_reset = {
		apply_label: 'Apply',
		reset_label: 'Reset',
		style: {},
	};

	// rating
	dominator_ui.initial_data.element_rating = {
		"template":[{"style":[],"elements":[{"type":"rating_number","style":{"[id]":{}},"trim_decimal":true,"decimals":true,"dec_point":"."},{"type":"rating_stars","style":{"[id]":{},"[id] i:after":{},"[id] i:before":{}}},{"type":"review_count","style":{},"brackets":true}],"type":"row"}],"type":"rating","style":{"[id]":{}},
		"not_rated": '',
		"rating_source": 'woocommerce',
		"condition": {},
	};

	// sorting
	dominator_ui.initial_data.element_sorting = {
		orderby: 'title',
		meta_key: '',
	};

	// media_image
	dominator_ui.initial_data.element_media_image = {
		url: '',
		media_id: '',
		use_external_source: false,
		external_source: '',
	};

	// media_image__col
	dominator_ui.initial_data.element_media_image__col = {
		url: '',
		media_id: '',
		use_external_source: false,
		external_source: '',
		condition: {},
		style: {},
	};

	// Columns
	dominator_ui.initial_data.column_settings = {
		name: '',
		heading: {
			content: null,
			style: {}
		},
		cell: {
			template: null,
			style: {}
		}
	};

	dominator_ui.initial_data.columns = {
    laptop: [
			dominator_ui.initial_data.column_settings
		],
		tablet: [

		],
		phone: [

		],

  };

	// Navigation

	// conditions
	dominator_ui.panel_conditions.cf_show_all_label	= function( $elm, data ){ // parent $elm

		if(
			// if 'exact match' comparison is selected && single option enabled
			( data.compare == 'IN' && data.single ) ||
			// or if 'within range' comparison match is selected
			( data.compare == 'BETWEEN' )
		){
			return true;
		}else{
			return false;
		}
	};

	// nav controller
	dominator_ui.controllers.nav_header_row = function( $elm, data, e ){

		if( typeof data.ratio == 'undefined' ){
			data.ratio = '100-0';
		}

		$elm.removeClass('wcpt-ratio-100-0 wcpt-ratio-70-30 wcpt-ratio-50-50 wcpt-ratio-30-70 wcpt-ratio-0-100');
		$elm.addClass('wcpt-ratio-' + data.ratio);
	}

	// nav initial data
	dominator_ui.initial_data.nav_header_row = {
		columns_enabled : 'left-right',
		ratio : '100-0',
		columns         : {
			left  : { template: false },
			center: { template: false },
			right : { template: false },
		}
	};

	dominator_ui.initial_data.navigation = {
		laptop: {
			header: {
				rows: [
					dominator_ui.initial_data.nav_header_row,
				],
			},
			left_sidebar: [],
		},
		tablet: false,
		phone: false,
	};

	dominator_ui.initial_data.element_price_filter = {
		heading: 'Price range',
		heading_format__op_selected: 'only_heading',				
		style: {},
		show_all_label: 'Any price',
		single: true,
		range_options: [
			{
				label: 'Upto $50',
				min: '0',
				max: '50',
			},
			{
				label: '$51 - $100',
				min: '51',
				max: '100',
			},
			{
				label: 'Over $100',
				min: '100',
				max: '',
			},
		]
	};

	dominator_ui.initial_data.price_range_row_2 = {
		label: '$100 - $200',
		min: '100',
		max: '200',
	};

	dominator_ui.initial_data.element_rating_filter = {
		heading: 'Rating',
		heading_format__op_selected: 'only_heading',						
		style: {},
		show_all_label: 'Show all',
		rating_options: [
			{
				label: '',
				value: '5',
				enabled: false,
			},
			{
				label: '& Up',
				value: '4+',
				enabled: true,
			},
			{
				label: '& Up',
				value: '3+',
				enabled: true,
			},
			{
				label: '& Up',
				value: '2+',
				enabled: true,
			},
			{
				label: '& Up',
				value: '1+',
				enabled: true,
			},
		]
	};

	dominator_ui.initial_data.rating_filter_row = {
		label: '& Up',
		value: '1+',
	};

	dominator_ui.initial_data.element_filter_modal = {
		label: [
			{"style":{},"elements":
				[
					{"type":"icon","style":{},"name":"filter"},
					{"type":"text","style":{},"text":"Filter"}
				],
				"type":"row"
			}
		],
		style: {},
	};

	dominator_ui.initial_data.element_sort_modal = {
		label: [
			{"style":{},"elements":
				[
					{"type":"icon","style":{},"name":"bar-chart"},
					{"type":"text","style":{},"text":"Sort"}
				],
				"type":"row"
			}
		],
		style: {},
	};

	dominator_ui.initial_data.archive_override_rule = {
		category: [],
		attribute: [],
		tag: [],
		table_id: '',
	};

	// button
	dominator_ui.initial_data.element_clear_filters = {
		reset_label: 'Clear filters',
	};

	function get_terms( taxonomy, limit_terms ){
		return new Promise((resolve, reject) => {
			var terms = terms_cache( taxonomy );
			if( ! terms ){
				return get_terms_from_server( taxonomy, limit_terms ).then( function(terms){
					terms_cache( taxonomy, terms );
					resolve(terms);
				} );
			}else{
				resolve(terms);
				return terms;
			}
		})
	}

	function terms_cache( taxonomy, terms ){
		if( ! window.wcpt_terms_cache ){
			window.wcpt_terms_cache = {};
		}
		if( terms ){ // set
			window.wcpt_terms_cache[taxonomy] = terms;
			return;
		}else{ // get
			if( typeof window.wcpt_terms_cache[taxonomy] == 'undefined' ){
				return false;
			}
			return window.wcpt_terms_cache[taxonomy]
		}
	}

	function get_terms_from_server(taxonomy, limit_terms){
		return new Promise((resolve, reject) => {
			var ajax_data = {
					action		  : 'wcpt_get_terms',
					taxonomy	  : taxonomy,
					limit_terms : limit_terms,
				},
				terms = [];

			$.post(ajaxurl, ajax_data, function(terms){
				resolve( terms );
				return terms;
			});
		})
	}

	function verify_terms( current_terms, taxonomy, limit_terms ){		
		return new Promise((resolve, reject) => {
			get_terms(taxonomy, limit_terms).then(function(terms){
				var modified = false;

				// ensure all terms are included in current terms
				for( var i= 0; i < terms.length; i++ ){
					var included = false;
					for( var ii= 0; ii < current_terms.length; ii++ ){
						if( current_terms[ii].term == terms[i].term ){
							included = true;
						}
					}

					if( ! included ){
						current_terms.push( $.extend( {}, terms[i] ) );
						modified = true;
					}
				}

				// ensure no current terms are from outside of terms
				var remove = []
				for( var i= 0; i < current_terms.length; i++ ){
					var included = false;
					for( var ii= 0; ii < terms.length; ii++ ){
						if( current_terms[i].term == terms[ii].term ){
							included = true;
						}
					}

					if( ! included ){
						remove.push(i);
						modified = true;
					}
				}

				if( remove.length ){
					for( var i= 0; i < remove.length; i++ ){
						current_terms.splice( remove[i], 1 );
					}
				}

				if( modified ){
					resolve(current_terms);
				}else{
					resolve(false);
				}

				return terms;
			})
		})
	}

})