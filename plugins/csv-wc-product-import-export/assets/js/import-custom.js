function getFileNameWithExt(event) {

	if (!event || !event.target || !event.target.files || event.target.files.length === 0) {
		return;
	}

	const name = event.target.files[0].name;
	const lastDot = name.lastIndexOf('.');

	const fileName = name.substring(0, lastDot);
	const ext = name.substring(lastDot + 1);

	if(ext=='csv'){		
		setTimeout(function(){
			jQuery('#upload2-error').remove();
		},100);
	}
}

jQuery(document).ready(function(){	

	if(jQuery("#import-upload-form2").length>0){
		let file = document.getElementById("upload2");
		let back = document.getElementById("upload");

		file.addEventListener('change', function() {
		  let files = this.files;
		  let dt = new DataTransfer();
		  for(let i=0; i<files.length; i++) {
		    let f = files[i];
		    dt.items.add(
		      new File(
		        [f.slice(0, f.size, f.type)],
		        f.name
		    ));
		  }
		  back.files = dt.files;
		});
	}	

	function parse_query_string(query) {
		var vars = query.split("&");
		var query_string = {};
		for (var i = 0; i < vars.length; i++) {
		  var pair = vars[i].split("=");
		  var key = decodeURIComponent(pair[0]);
		  var value = decodeURIComponent(pair[1]);
		  // If first entry with this name
		  if (typeof query_string[key] === "undefined") {
			query_string[key] = decodeURIComponent(value);
			// If second entry with this name
		  } else if (typeof query_string[key] === "string") {
			var arr = [query_string[key], decodeURIComponent(value)];
			query_string[key] = arr;
			// If third or later entry with this name
		  } else {
			query_string[key].push(decodeURIComponent(value));
		  }
		}
		return query_string;
	}

	jQuery("#cron_upload_file").click(function(){
		jQuery('#import_error').remove();
		jQuery('#import_success').remove();
	});

	function log_refresh(){
		jQuery("#refresh_log").unbind();
		jQuery("#refresh_log").click(function(e){
			e.preventDefault();
			var datac = jQuery('#cron_sort').val();
			var datao = jQuery('#cron_order').val();
			var paged = jQuery('#cron_paged').val();
			cron_list_table(paged,datac,datao);
		});
	}

	jQuery("#import-upload-form2").validate({
		rules: {
			import: {
				required: true,
				extension: "csv"
			}
		},
		messages:{
			import: {
				required: "Please select a csv file.",
				extension: "Extension not allowed, please select only csv file."
			}
		},
		errorPlacement: function (error, element)
		{
			error.appendTo(element.parent());
		},
		errorElement : 'div',
        errorClass : 'error',
		submitHandler: function(){			
			var file_data = jQuery("#import-upload-form2").find('input#upload2').prop('files')[0];
			var formData1 = new FormData();
			formData1.append('file', file_data);
			formData1.append('is_cron','0');
    		formData1.append('action', 'piecfw_import_validation');
			formData1.append('fields', jQuery("#import-upload-form2").serialize());
			jQuery.ajax({
			    url: my_ajax_object.ajax_url,
			    dataType: "json",
			    type: "post",
		        contentType: false,
		        processData: false,
			    data: formData1,
			    beforeSend: function(){
			    	jQuery('.loading_wrap').show();
			    	jQuery('#import_error').remove();
					jQuery('#import_success').remove();
			    },
			    success: function(msg){
					jQuery.each(msg, function(k, v){
						jQuery('.loading_wrap').hide();
			    		
			    		if(k=='success'){
			    			jQuery('#upload2').parent().append('<div id="import_success" class="updated">File '+v+' uploaded successfully.</div>');
			    			jQuery("#import-upload-form").find('input#filename').val(v);
			    			jQuery('#import-upload-form').submit();
			    		}else{
			    			jQuery('#upload2').parent().append(v);
			    		}
					});
			    }
			});
		}
	});

	jQuery("#cron-upload-form").validate({
		rules: {
			start_date: "required",
			frequency: "required",
			piecfw_import: {
				required: true,
				extension: "csv"
			}
		},
		messages:{
			start_date: {
				required: "Please select schedule date and time."
			},
			piecfw_import: {
				required: "Please select a csv file.",
				extension: "Extension not allowed, please select only csv file."
			}
		},
		errorPlacement: function (error, element)
		{
			error.appendTo(element.parent());
		},
		errorElement : 'div',
        errorClass : 'error',
		submitHandler: function(){			
			var file_data = jQuery("#cron-upload-form").find('input#piecfw_import').prop('files')[0];
			var formData1 = new FormData();
			formData1.append('file', file_data);
			formData1.append('is_cron','1');
    		formData1.append('action', 'piecfw_import_validation');
			formData1.append('start_date', jQuery("#start_date").val());
			formData1.append('frequency', jQuery("#frequency").val());
			jQuery.ajax({
			    url: my_ajax_object.ajax_url,
			    dataType: "json",
			    type: "post",
		        contentType: false,
		        processData: false,
			    data: formData1,
			    beforeSend: function(){
			    	jQuery('.loading_wrap').show();
			    	jQuery('#import_error').remove();
					jQuery('#import_success').remove();
			    },
			    success: function(msg)
			    {
			    	jQuery.each(msg, function(k, v){
						jQuery('.loading_wrap').hide();
			    		jQuery('#piecfw_import').parent().append(v);
					});

					jQuery("#start_date").val('');
					jQuery("#frequency").val('piecfw_one_time');
					jQuery("#piecfw_import").val('');
					jQuery('#cron_paged').val(1);

					cron_list_table(1,'','');
			    }
			});
		}
	});

	if(jQuery('#cron_list_table').length>0){
		function cron_list_page(){
			jQuery('.page-numbers').click(function(e) {
				e.preventDefault();
				var datac = jQuery('#cron_sort').val();
				var datao = jQuery('#cron_order').val();
				var param = jQuery(this).attr('href').split("?");
				var query = parse_query_string(param[1]);
				jQuery('#cron_paged').val(query.paged);
				cron_list_table(query.paged,datac,datao);
			});
			jQuery('.cron_sort').click(function(e) {
				e.preventDefault();
				var datac = jQuery(this).attr('data-sort');
				var datao = jQuery(this).attr('data-order');
				var param = jQuery(this).attr('href').split("?");
				var query = parse_query_string(param[1]);
				jQuery('#cron_sort').val(datac);
				jQuery('#cron_order').val(datao);
				jQuery('#cron_paged').val(query.paged);
				cron_list_table(query.paged,datac,datao);
			});
			jQuery('.cron_status_id').change(function(e) {
				e.preventDefault();
				var cron_id = jQuery(this).attr('dataid');
				var cron_status = jQuery(this).val();
				jQuery.ajax({
					url: my_ajax_object.ajax_url,
					type: "post",
					data: {
						action:'piecfw_cron_status',
						cron_id:cron_id,
						cron_status:cron_status						
					},
					beforeSend: function(){
						jQuery('.loading_wrap').show();
					},
					success: function(data){
						jQuery('.loading_wrap').hide();
					}
				});
			});	
			jQuery('.cron_delete').click(function(e) {
				e.preventDefault();
				if(confirm("Are you sure you want to delete this cron?")){
					var cron_id = jQuery(this).attr('dataid');
					jQuery.ajax({
						url: my_ajax_object.ajax_url,
						type: "post",
						data: {
							action:'piecfw_cron_delete',
							cron_id:cron_id					
						},
						beforeSend: function(){
							jQuery('.loading_wrap').show();
						},
						success: function(data){
							jQuery('.loading_wrap').hide();
							var datac = jQuery('#cron_sort').val();
							var datao = jQuery('#cron_order').val();
							jQuery('#cron_paged').val(1);
							cron_list_table(1,datac,datao);
						}
					});
				}
			});	
		}
	}

	function cron_list_table(paged,datac,datao){
		jQuery.ajax({
			url: my_ajax_object.ajax_url,
			type: "post",
			data: {
                action:'piecfw_cron_list',
                paged:paged,
				sort:datac,
				order:datao
            },
			beforeSend: function(){
				jQuery('.loading_wrap').show();
			},
			success: function(data){
				jQuery('.loading_wrap').hide();
				jQuery('#cron_list_table').html(data);
				cron_list_page(paged,datac,datao);
				log_refresh();
			}
		});
	}
	
	jQuery('#start_date').datetimepicker({
		minDate: 0,
    	minTime: 0,
	});
	
	if(jQuery('#cron_list_table').length>0){
		jQuery('#cron_paged').val(1);
		cron_list_table(1,'','');
	}	

	if(jQuery('#filelog_list_table').length>0){
		function filelog_list_page(){
			jQuery('.page-numbers').click(function(e) {
				e.preventDefault();
				var datac = jQuery('#filelog_sort').val();
				var datao = jQuery('#filelog_order').val();
				var param = jQuery(this).attr('href').split("?");
				var query = parse_query_string(param[1]);
				filelog_list_table(query.paged,datac,datao);
			});
			jQuery('.log_sort').click(function(e) {
				e.preventDefault();
				var datac = jQuery(this).attr('data-sort');
				var datao = jQuery(this).attr('data-order');
				var param = jQuery(this).attr('href').split("?");
				var query = parse_query_string(param[1]);
				jQuery('#filelog_sort').val(datac);
				jQuery('#filelog_order').val(datao);
				filelog_list_table(query.paged,datac,datao);
			});
			jQuery('.log_delete').click(function(e) {
				e.preventDefault();
				if(confirm("Are you sure you want to delete this log?")){
					var log_id = jQuery(this).attr('dataid');
					jQuery.ajax({
						url: my_ajax_object.ajax_url,
						type: "post",
						data: {
							action:'piecfw_log_delete',
							log_id:log_id					
						},
						beforeSend: function(){
							jQuery('.loading_wrap').show();
						},
						success: function(data){
							jQuery('.loading_wrap').hide();
							var datac = jQuery('#log_sort').val();
							var datao = jQuery('#log_order').val();
							filelog_list_table(1,datac,datao);
						}
					});
				}
			});	
		}
	}

	function filelog_list_table(paged,datac,datao){
		jQuery.ajax({
			url: my_ajax_object.ajax_url,
			type: "post",
			data: {
                action:'piecfw_filelog_list',
                paged:paged,
				sort:datac,
				order:datao
            },
			beforeSend: function(){
				jQuery('.loading_wrap').show();
			},
			success: function(data){
				jQuery('.loading_wrap').hide();
				jQuery('#filelog_list_table').html(data);
				filelog_list_page(paged,datac,datao);
			}
		});
	}

	if(jQuery('#filelog_list_table').length>0){
		filelog_list_table(1,'','');
	}	

	if(jQuery('#datalog_list_table').length>0){
		function datalog_list_page(){
			jQuery('.page-numbers').click(function(e) {
				e.preventDefault();
				var datac = jQuery('#datalog_sort').val();
				var datao = jQuery('#datalog_order').val();
				var datafile = jQuery('#datalog_file').val();
				var param = jQuery(this).attr('href').split("?");
				var query = parse_query_string(param[1]);
				datalog_list_table(query.paged,datac,datao,datafile);
			});
			jQuery('.log_sort').click(function(e) {
				e.preventDefault();
				var datac = jQuery(this).attr('data-sort');
				var datao = jQuery(this).attr('data-order');
				var datafile = jQuery(this).attr('data-file');
				var param = jQuery(this).attr('href').split("?");
				var query = parse_query_string(param[1]);
				jQuery('#datalog_sort').val(datac);
				jQuery('#datalog_order').val(datao);
				jQuery('#datalog_file').val(datafile);
				datalog_list_table(query.paged,datac,datao,datafile);
			});
		}
	}

	function datalog_list_table(paged,datac,datao,datafile){
		jQuery.ajax({
			url: my_ajax_object.ajax_url,
			type: "post",
			data: {
                action:'piecfw_datalog_list',
                paged:paged,
				sort:datac,
				order:datao,
				log_file:datafile
            },
			beforeSend: function(){
				jQuery('.loading_wrap').show();
			},
			success: function(data){
				jQuery('.loading_wrap').hide();
				jQuery('#datalog_list_table').html(data);
				datalog_list_page(paged,datac,datao,datafile);
			}
		});
	}

	if(jQuery('#datalog_list_table').length>0){
		var datafile = jQuery('#datalog_file').val();
		datalog_list_table(1,'','',datafile);
	}	
});