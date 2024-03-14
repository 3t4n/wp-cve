jQuery(document).ready(function ($) {
	$('#testcompleted').remove()
	jQuery('#lnkbio_group_container').hide()
	
	var data = {
		'action': 'lnkbio_api_getgroups'
	};
	$.ajax({
		url: ajaxurl,
		type: 'POST',
		data: data,
		success: function (res) {
			if(res && res.status && res.options && res.options != "") {
				jQuery('#lnkbio_group_container').show()
				jQuery('#lnkbio_group').html(res.options)
			} else {
				jQuery('#lnkbio_group_container').hide()
			}
		},
		dataType: "json"
	});

	$('#LB_test').on('click', function () {
		$('#testcompleted').remove()
		$("#test_spinner").addClass("is-active"); 
		var data = {
			'action': 'lnkbio_api_test'
		};
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: data,
			success: function (res) {
				$("#test_spinner").removeClass("is-active"); 
				if(res) {
					jQuery('#LB_cont').append('<div class="updated" id="testcompleted">Test completed. All good</div>')
				}
			}
		});
	});

	$('#LB_mass').on('click', function () {
		$('#masscompleted').remove()
		LB_massImport(true)
	});


	function LB_massImport(force_restart) {
		$("#mass_spinner").addClass("is-active"); 
		var data = {
			'action': 'lnkbio_api_mass',
			'force_restart': force_restart
		};
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: data,
			success: function (res) {
				if (res.status) {
					$('#masscompleted').remove()
					if (res.completed) {
						jQuery('#LB_cont').append('<div class="updated" id="masscompleted">All done! Imported '+res.num_done+'/'+res.num_posts+"</div>")
						$("#mass_spinner").removeClass("is-active"); 
					} else {
						jQuery('#LB_cont').append('<div class="updated" id="masscompleted">Importing posts: '+res.num_done+'/'+res.num_posts+"</div>")
						LB_massImport(false)
					}
					
				}
			},
			dataType: "json"
		});
	}
});

