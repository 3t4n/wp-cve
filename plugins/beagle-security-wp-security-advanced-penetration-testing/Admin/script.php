<script>
	// for first loader button
	function BeagleWP_Token_Input() {
		var access_token = document.getElementById("access_token").value;
		var application_token = document.getElementById("application_token").value;
		if (access_token.length == 32 && application_token.length == 32) {
			document.getElementById("continueSave").style.display = "none";
			document.getElementById("spinnerSave").style.display = "block";
		}
	}
	// for delete application
	function BeagleWP_delete_Confirm() {
		Swal.fire({
			text: 'Are you sure you want to delete this application?',
			icon: 'warning',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Delete'
		}).then((result) => {
			if (result.isConfirmed) {
				// ajax call
				var data = {
					'action': 't4a_ajax_call_delete'
				};
				jQuery.post(ajaxurl, data, function(response) {
					// analyse response
					try {
						var deleteDataResponse = JSON.parse(response);
						if (deleteDataResponse == true || deleteDataResponse == 1 || deleteDataResponse != null) {
							Swal.fire(
								'Application deleted!',
								'',
								'success'
							);
							location.reload();
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Delete failed!',
								text: 'Something went wrong!',
							});
						}
					} catch (err) {
						console.log(err);
					}
				})
			}
		});
	}

	// for verify domain
	function BeagleWP_verifyDomain_ByUser() {

		document.getElementById("verifyDomain").style.display = "none";
		document.getElementById("verifyDomainHide").style.display = "block";
		var data = {
			'action': 't4a_ajax_call_verify'
		};
		jQuery.post(ajaxurl, data, function(response) {
			try {
				var verifyResponse = JSON.parse(response);
				if (verifyResponse.status != "Failed") {
					var data = {
						'action': 't4a_ajax_call_verify_update'
					};
					jQuery.post(ajaxurl, data, function(response) {
						location.reload();
					});
				} else {
					var data = {
						'action': 't4a_ajax_call_verify_update_failed'
					};
					jQuery.post(ajaxurl, data, function(response) {});
					document.getElementById("verifyDomainHide").style.display = "none";
					document.getElementById("verifyError").style.display = "block";
				}
			} catch (err) {
			}
		});
	}

	// for automatic domain verify fail info
	function BeagleWP_show_Msg() {
		Swal.fire({
			title: '',
			text: "Domain verification failed. Try the other verification methods available by logging in to your Beagle Security account.",
			icon: 'info',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			confirmButtonText: 'LOG IN'
		}).then((result) => {
			if (result.isConfirmed) {
				window.open("https://beaglesecurity.com/login", '_blank');
			}
		})
	}

	// for status
	function BeagleWP_get_Data() {
		try {
			document.getElementById("statusGet").style.display = "none";
			document.getElementById("spinner").style.display = "block";
			// ajax call
			var data = {
				'action': 't4a_ajax_call'
			};
			jQuery.post(ajaxurl, data, function(response) {
				// analyse response
				try {
					var dataresponse = JSON.parse(response);
					if (dataresponse.status != null || dataresponse.status != 'undefined') {
						var width = dataresponse.progress;
						var status = dataresponse.status;
						document.getElementById("progress").style.width = width + "%";
						document.getElementById("progress").innerText = width + "%";
						document.getElementById("status").innerText = dataresponse.status + " : ";
						document.getElementById("message").innerText = dataresponse.message;
						document.getElementById("statusGet").style.display = "block";
						document.getElementById("spinner").style.display = "none";
						if (status == 'completed') {
							document.getElementById("statusbar").style.display = "none";
							location.reload();
							BeagleWP_get_Result();
						} else {
							document.getElementById("resultData").style.display = "none";
						}
					} else {
						console.log("Error occures!.");
					}
				} catch (err) {
				}
			});
		} catch (err) {
		}
	}

	// for get result
	function BeagleWP_get_Result() {
		try {
			document.getElementById("statusbar").style.display = "none";
		} catch (err) {
		}
		document.getElementById("resultData").style.display = "flex";
		var data = {
			'action': 't4a_ajax_call_result'
		};
		jQuery.post(ajaxurl, data, function(response) {
			try {
				var data = {
					'action': 't4a_ajax_call_result'
				};
				jQuery.post(ajaxurl, data, function(response) {
					var dataresponse = JSON.parse(response);
					var dataResult = JSON.parse(dataresponse.result);
					if(dataResult!=null) {
						var totalBug = dataResult.vulnerability_summary.critical + dataResult.vulnerability_summary.high + dataResult.vulnerability_summary.medium + dataResult.vulnerability_summary.low + dataResult.vulnerability_summary.very_low;
						var score = dataResult.score;
						if(score <= 2){
							document.getElementById("progressClass").classList.add('p' + dataResult.score + '0');
							document.getElementById("progressClass").classList.add('criticalBug');
						} else if(score <= 4){
							document.getElementById("progressClass").classList.add('p' + dataResult.score + '0');
							document.getElementById("progressClass").classList.add('highBug');
						} else if(score <= 6){
							document.getElementById("progressClass").classList.add('p' + dataResult.score + '0');
							document.getElementById("progressClass").classList.add('mediumBug');
						} else if(score <= 8){
							document.getElementById("progressClass").classList.add('p' + dataResult.score + '0');
							document.getElementById("progressClass").classList.add('lowBug');
						} else if(score <= 10){
							document.getElementById("progressClass").classList.add('p' + dataResult.score + '0');
							document.getElementById("progressClass").classList.add('verylowBug');
						}
						document.getElementById("progressCount").innerText = dataResult.score;
						document.getElementById("criticalBug").innerText = dataResult.vulnerability_summary.critical;
						document.getElementById("highBug").innerText = dataResult.vulnerability_summary.high;
						document.getElementById("mediumBug").innerText = dataResult.vulnerability_summary.medium;
						document.getElementById("lowBug").innerText = dataResult.vulnerability_summary.low;
						document.getElementById("verylowBug").innerText = dataResult.vulnerability_summary.very_low;
						document.getElementById("totalBug").innerText = totalBug;
						document.getElementById("genDate").innerText = " " + dataResult.generated_date;
					}
				});
			} catch (err) {
			}
		});
	}
</script>