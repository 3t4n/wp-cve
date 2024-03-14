jQuery(function($) { /* handle shortcode creation */

	function submit_shortcode_form() {
		var $form = $('.proto_shortcode_form');
		var fields = $form.find('.proto_shortcode_options');
		var output = $(document).find('.proto_shortcode_result');
		var count = fields.length;
		var details = '';
		var taxonomies = '';
		var subcaptions = $form.find('.featured-image-pro-subcaptions');
		var count = 0;
		var subcaptiondetails = '';
		subcaptions.each( function () {
			count++;
			var subcaptiontype = $(this).find('.subcaption_type').val();
			var subcaptionfield = $(this).find('.' + subcaptiontype).val();
			var cast = $(this).find('.subcaption_cast').val();
			var title = $(this).find('.subcaption_label').val();
			subcaptiondetails += " subcaptiontype"   + count + "='" + subcaptiontype +
								"' subcaptionfield" + count + "='" + subcaptionfield +
								"' subcaptioncast" + count + "='" + cast + "'";
			if (title != "")
				subcaptiondetails += "subcaptiontitle" + count + "='" + title + "'";


		});

		fields.each(function() {
			var ftype = $(this).attr('type'); //the type
			var fname = $(this).data('name'); //the field name
			var fdefault = $(this).data('default') != null ? $(this).data('default') : ''; //te default value
			var falways = $(this).data('always') ? true : false; //always display
			var generate = $(this).data('generate') != null ? $(this).data('generate') : true;
			switch (ftype) {
			case 'checkbox':
				{
					vdefault = fdefault == fname || fdefault == 1 || fdefault == true ? 1 : 0;
					not = $(this).data('not') ? 1 : 0;
					if (not) value = $(this).is(':checked') ? 0 : 1;
					else value = $(this).is(':checked') ? 1 : 0;
					bvalue = value > 0 ? 'true' : 'false';
					if (vdefault !== value || falways) details += ' ' + fname + '=' + bvalue;
					break;
				}
			case 'number':
				value = parseInt($(this).val());
				fdefault = parseInt(fdefault);
				if (isNaN(value)) value = fdefault;
				if (fdefault !== value || falways == true) details += ' ' + fname + "='" + value + "'";
				break;
			case 'text':
				if (generate) {
					value = $(this).val();
					if (fdefault != value || falways == true) details += ' ' + fname + "='" + value + "'";
				}
				break;
			case 'radio':
				if ($(this).is(':checked')) {
					value = $(this).val();
					if (fdefault != value || falways == true) details += ' ' + fname + "='" + value + "'";
					if ($(this).hasClass('proto_radio_sort')) {
						select = $(this).parent().find('.proto-select')
						value = select.val();
						fname = select.data('name');
						details += ' ' + fname + "='" + value + "'";
					}
				}
				break;
			default:
				if ($(this).hasClass('proto_taxonomies')) {
					var values = '';
					var taxonomy = $(this).data('taxonomy');
					var posttype = $(this).data('posttype');
					acats = $(this).val(); //should return an array
					if (acats != null && acats.length > 0) {
						if (Array.isArray(acats))
							cats = acats;
						else
							cats = Array.prototype.join.call(acats, ",");
						if (taxonomies == '') taxonomies = taxonomies + taxonomy;
						else taxonomies = taxonomies + ',' + taxonomy;
						details += ' ' + taxonomy + "_terms='" + cats + "'";
						details += ' ' + taxonomy + "_field='id'";
					}
				} else if (($(this).hasClass('meta_query_compare') && ($(this).val() == 'EXISTS' || $(this).val() == 'NOT EXISTS')) || ($(this).hasClass('meta_query_values'))) { //if meta values have been selected //if meta values have been selected
					var field = $(this).data('name'); //get the field name
					var aselvalue = $(this).val(); //get the array of selected values
					var key = $('#featured_image_pro_post_masonry_options_meta_queries').val(); // get the key name
					select = $('.meta_query_compare');
					if (((aselvalue != null && aselvalue.length > 0) || select.val() == 'EXISTS' || select.val() == 'NOT EXISTS') && key != null && key != '') {
						var exists = (select.val() == 'EXISTS' || select.val() == 'NOT EXISTS');
						if (($(this).hasClass('meta_query_compare') && exists)) {
							details += " meta_queries='" + key + "'";
							details += ' ' + key + "_compare='" + select.val() + "'";
						} else if (aselvalue != null && aselvalue.length > 0 && !exists) {
							if (Array.isArray(aselvalue))
								meta_query_values = "'" + aselvalue.join(',') + "'";
							else
								meta_query_values = "'" + aselvalue + "'";
							details += " meta_queries='" + key + "'";
							details += ' ' + key + "_value=" + meta_query_values;
							select = $('.meta_query_compare');
							details += ' ' + key + "_compare='" + select.val() + "'";
						}
					}
				} else if (generate) {
					assoc = $(this).data('assoc') //see if this is the name we want to write the value to
					if (assoc != null && assoc != '') { //if not,
						var assocfield = $(document).find('#featured_image_pro_post_masonry_options_' + assoc);
						var fname = assocfield.val();
					}
					value1 = $(this).val();
					if (Array.isArray(value1)) {
						value = value1.toString();
					}
					else {
						value = value1;
					}

					if (value != null && fname != null && fname != '') {
						 if (fdefault !== value || falways)
						 	details += ' ' + fname + "='" + value + "'";
					}
				}
			}
		});
		if (taxonomies != '') details += " taxonomies='" + taxonomies + "'";
		details = " [featured_image_pro " + details + subcaptiondetails + "]";

		output.text(details);

		output.trigger('focus');
		output.trigger('select');
	};

	//================Get checkbox values comma delimited

	function proto_get_checkbox_values(mcheckbox) {
		var values = '';
		var cats = $(mcheckbox).find('input:checked');
		cats.each(function() {
			if (values != '') values = values + ',';
			values = values + $(this).val();
		});
		return values;
	}
	//=================Submit form submit_save_shortcode
	$('#submit_save_shortcode').on('click', function() {

		event.preventDefault();
		$.when(submit_shortcode_form()).done(function()
		{
			$('#pspm_form').submit();

		});
	});

	$('#submit_generate_shortcode').on('click', function() {

		submit_shortcode_form();
		event.preventDefault();

	});


	//	$('.proto_shortcode_form').submit(submit_shortcode_form);
	//=================Tabs
/*	var tab=$('#featured-image-admin-tabindex').val();
	$('#featured-image-admin-tabs').tabs();
	$('#featured-image-admin-pro-tabs').tabs(  );

	$('#featured-image-admin-pro-tabs').tabs( {active: tab});*/
	jQuery(document).ready(function($) {
$('#tabs').tabs();

//hover states on the static widgets
$('#dialog_link, ul#icons li').on( 'mouseover',
function() { $(this).addClass('ui-state-hover'); },
function() { $(this).removeClass('ui-state-hover'); }
);
});
	//=================SubCaptions

	$(document).on("click", '#featured-image-pro-addsubcaption' ,function() {
		var ipost_type = $('#featured_image_pro_post_masonry_options_post_type option:selected').val();
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			dataType: 'json',
			data: {
				action: 'proto_subcaption',
				post_type: ipost_type,
			}
		}).done(function(response) {
			$('#proto-subcaptions').append(response);
		}).fail(function(jqXHR, textStatus, errorThrown) {
			proto_log_error(jqXHR, textStatus, errorThrown);
		}).always(function(msg) {})
	});
	$(document).on('click', '.featured-image-pro-remove-caption', function() {

		var box = $(this).closest('.featured-image-pro-subcaptions');
		box.remove();


	});
	$(document).on('change', '.featured-image-pro-subcaption-types .subcaption_type', function() {

		$type = ($(this).val());
		var box = $(this).closest('.featured-image-pro-subcaptions');
		box.find('.subcaption-type').hide();
		box.find('.subcaption-' + $type).show();

	});
	//==================Modal Form
	$('.modal-body .proto_shortcode_form .submit').append(' <button type="button" class="button button-secondary" id="featured-image-shortcode-insert">Insert Shortcode</button>');
	$(document).on('click', '.modal-body #featured-image-shortcode-insert', function() {

		if (tinyMCE.activeEditor != null) {
			tinyMCE.activeEditor.execCommand('mceInsertContent', false, $('.proto_shortcode_result').text());
		} else {
			alert('Cannot insert in text mode. Copy/paste instead.');
		}
	});
	$('#featured_image_pro_post_masonry_options_meta_query_values').select2({placeholder: "Select Meta Value/s"});
	$('#featured_image_pro_post_masonry_options_taxonomy').select2({placeholder: "Select taxonomy/taxonomies"});
	$('#featured_image_pro_posts .proto_masonry_select2').select2({placeholder: "Select post/posts"});
	$('#featured_image_pro_post_masonry_options_authors_list').select2({placeholder: "Select author/authors"});
	//On change meta key
	jQuery(document).on('change', '#featured_image_pro_post_masonry_options_meta_queries', function() {
		proto_meta_values();
	});

	function proto_meta_values() {
		var meta_key = $('#featured_image_pro_post_masonry_options_meta_queries option:selected').text();
		var ipost_type = $('#featured_image_pro_post_masonry_options_post_type option:selected').val();
	//	window.location.hash = '#featured_image_pro_post_meta_value_content'; //Set focus on the field
		$('featured_image_pro_post_meta_value_content').text('');
		$('#featured_image_pro_spinnerid').css('display', 'block');
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			dataType: 'json',
			data: {
				action: 'proto_metadata',
				post_type: ipost_type,
				meta_key: meta_key
			}
		}).done(function(response) {
			$('#featured_image_pro_post_meta_value_content').html(response);
			$('#featured_image_pro_post_masonry_options_meta_query_values').select2({placeholder: "Select Meta Value/s"});
		}).fail(function(jqXHR, textStatus, errorThrown) {
			proto_log_error(jqXHR, textStatus, errorThrown);
		}).always(function(msg) {
			$('#featured_image_pro_spinnerid').css('display', 'none');
		})
	}
	//Log ajax error

	function proto_log_error(jqXHR, textStatus, errorThrown) {
		console.log('<p>status code: ' + jqXHR.status + '</p><p>errorThrown: ' + errorThrown + '</p><p>jqXHR.responseText:</p><div>' + jqXHR.responseText + '</div>');
		console.log('jqXHR:');
		console.log(jqXHR);
		console.log('textStatus:');
		console.log(textStatus);
		console.log('errorThrown:');
		console.log(errorThrown);
	}
	//========================Ajax for sort taxonomy fields

	function proto_subcaption_tax_fields(container, ipost_type) {
		var parent = container.closest('.proto_subcaption');
		field = parent.data('fieldname');
		parent.text('');
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			dataType: 'json',
			data: {
				action: 'proto_subcaptiontaxonomy',
				post_type: ipost_type,
				field: field
			}
		}).done(function(response) {
			parent.html(response);
		}).fail(function(jqXHR, textStatus, errorThrown) {
			proto_log_error(jqXHR, textStatus, errorThrown);
		})
	}
	//===========================Ajax for sort meta fields

	function proto_subcaption_meta_fields(container, ipost_type) {
		var parent = container.closest('.proto_subcaption');
		field = parent.data('fieldname');
		$('#featured_image_pro_spinnerid').css('display', 'block');
		parent.text('');
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			dataType: 'json',
			data: {
				action: 'proto_subcaptionmetadata',
				post_type: ipost_type,
				field: field,
			}
		}).done(function(response) {
			parent.html(response);
			$('#featured_image_pro_spinnerid').css('display', 'none');
		}).fail(function(jqXHR, textStatus, errorThrown) {
			proto_log_error(jqXHR, textStatus, errorThrown);
		})
	}

	//=========================Ajax for post type change - lots of stuff here
	$('#featured_image_pro_post_masonry_options_post_type').on('change', function() {
		var ipost_type = $('#featured_image_pro_post_masonry_options_post_type option:selected').val();
	//	window.location.hash = '#featured_image_pro_posts'; //Set focus on the field
		$('#featured_image_pro_posts').text('');
		$('#featured_image_pro_spinnerid').css('display', 'block');
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			dataType: 'json',
			data: {
				action: 'proto_posts',
				post_type: ipost_type
			}
		}).done(function(response) {
			$('#featured_image_pro_posts').html(response);
			$('#featured_image_pro_posts .proto_masonry_select2').select2({placeholder: "Select posts"});
		}).fail(function(jqXHR, textStatus, errorThrown) {
			proto_log_error(jqXHR, textStatus, errorThrown);
		})
		/* taxonomy query */
	//	window.location.hash = '#featured_image_pro_taxonomies'; //Set focus on the field
		$('#featured_image_pro_taxonomies').text('');
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			dataType: 'json',
			data: {
				action: 'proto_taxonomy',
				post_type: ipost_type
			}
		}).done(function(response) {
			$('#featured_image_pro_taxonomies').html(response);
		}).fail(function(jqXHR, textStatus, errorThrown) {
			proto_log_error(jqXHR, textStatus, errorThrown);
		})
		/* catagory in query */
//		window.location.hash = '#featured_image_pro_category_in';
		$('#featured_image_pro_category_in').text('');
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			dataType: 'json',
			data: {
				action: 'category_query',
				post_type: ipost_type
			}
		}).done(function(response) {
			$('#featured_image_pro_category_in').html(response);
		}).fail(function(jqXHR, textStatus, errorThrown) {
			//alert('An error occurred... Look at the console (F12 or Ctrl+Shift+I, Console tab) for more information!');
			proto_log_error(jqXHR, textStatus, errorThrown);
		})
	//	window.location.hash = '#featured_image_pro_tag_in';
		$('#featured_image_pro_tag_in').text('');
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			dataType: 'json',
			data: {
				action: 'tag_query',
				post_type: ipost_type
			}
		}).done(function(response) {
			$('#featured_image_pro_tag_in').html(response);
		}).fail(function(jqXHR, textStatus, errorThrown) {
			proto_log_error(jqXHR, textStatus, errorThrown);
		})
//		window.location.hash = '#featured_image_pro_isotaxonomy';
		$('#featured_image_pro_isotaxonomy').text('');
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			dataType: 'json',
			data: {
				action: 'proto_filteredtaxonomy',
				post_type: ipost_type
			}
		}).done(function(response) {
			$('#featured_image_pro_isotaxonomy').html(response);

		}).fail(function(jqXHR, textStatus, errorThrown) {
			proto_log_error(jqXHR, textStatus, errorThrown);
		})
//		window.location.hash = '#featured_image_pro_meta_sort';
		$('#featured_image_pro_meta_sort').text('');
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			dataType: 'json',
			data: {
				action: 'proto_metakeys',
				post_type: ipost_type
			}
		}).done(function(response) {
			$('#featured_image_pro_meta_sort').html(response);
		}).fail(function(jqXHR, textStatus, errorThrown) {
			proto_log_error(jqXHR, textStatus, errorThrown);
		})
	//	window.location.hash = '#featured_image_pro_post_meta_value_content';
		$('#featured_image_pro_meta_key').text('');
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			dataType: 'json',
			data: {
				action: 'proto_metaquerykeys',
				post_type: ipost_type
			}
		}).done(function(response) {
			console.log(response);
			$('#featured_image_pro_meta_key').html(response);
			proto_meta_values();
		}).fail(function(jqXHR, textStatus, errorThrown) {
			proto_log_error(jqXHR, textStatus, errorThrown);
		}).always(function(msg) {
			$('#featured_image_pro_spinnerid').css('display', 'none');
//			window.location.hash = '#featured_image_pro_posts';
		})
		metas = $(".featured-image-pro-subcaptions").find(".meta");
		metas.each(function() {
			proto_subcaption_meta_fields($(this), ipost_type);
		});
		taxs = $(".featured-image-pro-subcaptions").find(".taxonomy");
		taxs.each(function() {
			proto_subcaption_tax_fields($(this), ipost_type);
		});

	});
});