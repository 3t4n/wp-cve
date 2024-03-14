<?php


if (!function_exists('ai_post_generator_add_integration_code_body_settings')) {


	function ai_post_generator_add_integration_code_body_settings()
	{

?>


<div id="wp-body-content" class="container">
	<?php
			ai_post_generator_add_integration_code_header()
			?>
	<script>
	</script>
	<div class="wrap container" id="autowriter-content">

		<ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">


			<li class="nav-item" role="presentation">


				<button class="nav-link active" style="border: 1px solid #0d6efd;" id="pills-settings-tab"
					data-bs-toggle="pill" data-bs-target="#pills-settings" type="button" role="tab"
					aria-controls="pills-settings" aria-selected="false">Settings</button>


			</li>


			<li class="nav-item" role="presentation">

				<button class="nav-link" style="border: 1px solid #0d6efd;" id="pills-free-billing-tab"
					data-bs-toggle="pill" data-bs-target="#pills-free-billing" type="button" role="tab"
					aria-controls="pills-free-billing" aria-selected="false">Billing History</button>


			</li>


		</ul>

		<div class="tab-content" id="pills-tabContent">
			<div class="tab-pane fade show active" id="pills-settings" role="tabpanel"
				aria-labelledby="pills-settings-tab">

				<div class="">

					<h4 class="my-5">Subscription Settings</h4>

					<!-- FREE PLAN-->
					<div id="ai-free-plan" style="display:none">
						<p style="font-size: inherit;">Subscription: Free Plan</p>
						<a href='<?php echo get_admin_url(); ?>admin.php?page=autowriter_upgrade_plan'
							class="btn btn-lg btn-primary my-4">Upgrade plan <i class="fa fa-rocket ms-2"></i></a>

					</div>

					<div id="ai-subscription-plan" style="display:none">
						<p style="font-size: inherit;"><b>Subscription:</b> <span id="ai-subscription-name"></span> Plan
						</p>
						<p style="font-size: inherit;"><b>Price / month:</b> <span id="ai-subscription-price"></span> €
						</p>
						<p style="font-size: inherit;"><b>Nº posts / month:</b> <span
								id="ai-subscription-n_posts"></span>
							posts</p>
						<p style="font-size: inherit;"><b>Next payment:</b> <span
								id="ai-subscription-next_payment"></span></p>
						<div class="d-flex flex-column align-items-start">
							<a href='<?php echo get_admin_url(); ?>admin.php?page=autowriter_upgrade_plan'
								class="btn btn-lg btn-primary my-4">Change subscription <i
									class="fa fa-rocket ms-2"></i></a>
							<button class="btn btn-danger my-4" onclick="show_delete_subscription(this)"> Cancel
								Subscription</button>
							<div id="ai-cancel-subscription" style="display:none">
							<p style="font-size: inherit;">Your subscription will be cancelled automatically. 
							You will no longer be charged and will return to the free subscription. 
							Once you cancel your subscription you will no longer be able to access your invoice history.
							This action will not be reversed.</p>
							<button id="ai-cancel-subscription" onclick="ai_cancel_subscription()"
								class="btn btn-danger my-4"> Cancel
								Subscription</button>
							</div>
						</div>
					</div>
				</div>
				<div class="">
					<!--CONTENT-->
					<h4 class="my-5">Personal Settings</h4>
					<p class="my-2">Name</p>
					<input type="text" class="form-control mb-4 gpt3-title" id="ai-name"
						placeholder="Your name"></input>

					<p class="my-2">Contact email</p>
					<input type="text" class="form-control mb-4 gpt3-title" id="ai-email"
						placeholder="example@example.com"></input>

					<button class="btn btn-primary my-4" id="ai-update-settings"
						onclick="ai_edit_settings()">Update</button>


				</div>
			</div>


			<div class="tab-pane fade" id="pills-free-billing" role="tabpanel" aria-labelledby="pills-free-billing-tab">


				<div class="d-flex flex-column align-items-center mt-5">
					<div style="width:100%; overflow-x:auto;" class="payment-scrollbar pb-4">
						<table id="ai-billing-table" class="display responsive nowrap" style="text-align: center;"
							cellspacing="0" width="90%">
							<thead>
								<tr>
									<th scope="col">Link</th>
									<th scope="col">Price</th>
									<th scope="col" id="ai-billing-date">Date</th>
								</tr>
							</thead>
							<tbody id="ai-billing-tbody">
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>


	</div>


	<div class="wrap mt-5" style="text-align: right;">



		<p>Developed By <a href="https://autowriter.tech" target="_blank">AutoWriter</a></p>



	</div>


</div>

<?php


	}
}