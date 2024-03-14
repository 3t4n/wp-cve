<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// var_dump($calculators);
?>
<div class="row">
	<div class="scc_title_bar" >Redo Migration</div>
</div>
<div class="row ms-2">
	<p>Using this view you can <span style="color:#314af3;font-weight:bold;">migrate</span> your old database and global settings.</p>
</div>
<div class="row ms-2" style="min-height: 300px;">
	<div id="scc-migration-selection" class="col-6">
		<div class="migration_container">
			<div style="font-size:140%;margin-bottom: 20px;" class="sccsubtitle scc_email_quote_label">
				<span style="font-weight:800;">TYPE OF MIGRATION OF</span> DATABASE
			</div>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<label class="input-group-text" for="inputGroupSelect01" style="font-weight: unset;">Options</label>
				</div>
				<select class="custom-select" id="inputGroupSelect01" disabled>
					<option value="1">Automatic migration</option>
				</select>
			</div>
			<button class="btn scc-btn-migrate btn-danger" id="scc-migrate-auto">Migrate</button>
			<p class="scc_migrate_message"></p>
		</div>
	</div>
	<div class=" col-6">
		<div class="migration_container bg-danger text-white" style="padding-top: 30px;padding-right:30px">
			<h4>Notice:</h4>
			<p style="font-size: 15px;">You have to consider the following aspects:</p>
			<ol>
				<li>Each time you run this migration you will loose the calculators you have created in v.7</li>
				<li>Each time you run this migration you will overwrite the global settings to the previous global settings</li>
			</ol>
		</div>
	</div>
</div>
<script>
	jQuery("#scc-migrate-auto").on("click", function() {
		var selected = jQuery("#inputGroupSelect01").val()
		if (selected == "1") {
			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
				if (result.isConfirmed) {
					var sp = showProgress(120000)
					jQuery.ajax({
						url: ajaxurl,
						cache: false,
						timeout: 120000,
						data: {
							action: 'sccMigrateAuto2',
							automatic: true,
						},
						success: function(data) {
							sp.close();
							if (data.passed == true) {
								show_message('green', 'scc-migration-selection', 'Your database was migrated successfully...')
							} else {
								show_message('red', 'scc-migration-selection', 'An error occurred while migrating the database...')
							}
						}
					})
				}
			})
		}
	})

	/**
	 * *handles message of success or error
	 * @param {string} color
	 * @param {string} selector -success
	 * @param {string} message
	 */
	function show_message(color, selector, message) {
		var wq = jQuery("#" + selector).find(".scc_migrate_message")
		wq.html(message).css("color", color)
		jQuery(wq).fadeIn()
		setTimeout(() => {
			jQuery(wq).fadeOut()
		}, 5000);
	}
	/**
	 * *Handles to show progress bar
	 */
	function showProgress(timer) {
		let timerInterval
		var s = Swal.fire({
			title: 'Migrating tables & entries!',
			html: 'Please wait',
			timer: timer,
			allowOutsideClick: false,
			timerProgressBar: true,
			didOpen: () => {
				Swal.showLoading()
				timerInterval = setInterval(() => {
					const content = Swal.getHtmlContainer()
					if (content) {
						const b = content.querySelector('b')
						if (b) {
							b.textContent = Swal.getTimerLeft()
						}
					}
				}, 100)
			},
			willClose: () => {
				clearInterval(timerInterval)
			}
		})
		return s
	}
</script>
<style>
	.migration_container h5 {
		font-weight: bold;
	}

	.scc_migrate_message {
		margin-top: 10px;
		font-weight: bold;
	}

	.migration_container {
		background-color: #f8f9ff;
		border-radius: 5px;
		margin-top: 15px;
		padding-left: 40px;
		/* margin-right: 40px; */
		padding-bottom: 30px;
	}

	.migration_container ol li {
		font-size: 14px;
	}

	.migration_container .scc-btn-migrate {
		/* background-color: #314af3; */
		margin: 15px 0 0 0;
		color: white;
	}

	.migration_container .scc-btn-migrate-delete {
		background-color: red;
		margin: 15px 0 0 0;
		color: white;
	}

	.migration_container .scc-btn-migrate-delete:hover {
		opacity: 0.5;
		color: white;
	}

	.migration_container .scc-btn-migrate-delete:focus {
		color: white;
	}

	.migration_container .scc-btn-migrate:hover {
		color: orange;
	}

	.form-check input[type=checkbox] {
		/* margin: -3px 0 0; */
	}

	.migration_container .form-check label {
		font-weight: normal;
	}

	.migration_container .scc-btn-migrate:disabled {
		opacity: 0.4;
	}

	.migration_container .scc-btn-migrate:focus {
		color: orange;
	}

	.migration_container .scc-btn-migrate:disabled:hover {
		color: white;
	}

	.migration_container ol {
		padding-left: 0;
	}

	.swal2-confirm.swal2-styled {
		background-color: rgb(221, 51, 51) !important;
	}

	.swal2-cancel.swal2-styled {
		background-color: rgb(48, 133, 214) !important;
	}
</style>
