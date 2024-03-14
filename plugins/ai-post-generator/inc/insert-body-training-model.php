<?php


if (!function_exists('ai_post_generator_add_integration_code_body_training_model')) {


	function ai_post_generator_add_integration_code_body_training_model()
	{


?>



<div id="wp-body-content" class="container">
	<?php
			ai_post_generator_add_integration_code_header()
			?>

	<div class="wrap container" id="autowriter-content">
		<p class="my-4" style="font-size: large;">In this section you can define your brand, products, or
			anything you want AutoWriter to write
			about.</p>
		<div class="ai-add-train" style="background: #e9f2ff;">
			<input class="form-control gpt3-title my-4" type="text" name="ai-concept-title" id="ai-concept-title"
				placeholder="Title">
			<textarea class="form-control gpt3-title my-4" id="ai-concept-textarea" maxlength="1000" rows="3"
				placeholder="Description"></textarea>

			<div style="text-align:right;">
				<span id="ai-chars">0</span><span>/1000</span>
			</div>
			<button class="btn btn-primary my-4 d-block m-auto" id="ai-add-train">Add</button>
			<div class="text-danger text-center" id="form-errors-concept"></div>
		</div>

		<div class="my-5">
			<table id="concepts-table" class="display responsive nowrap" style="text-align: center;" cellspacing="0"
				width="90%">
				<thead>
					<tr>
						<th scope="col">Title</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody id="ai-train-tbody">
				</tbody>
			</table>
		</div>
		<div id="pop-concept-cont"></div>


	</div>


	<div class="wrap mt-5" style="text-align: right;">



		<p>Developed By <a href="https://autowriter.tech" target="_blank">AutoWriter</a></p>



	</div>


</div>

<?php


	}
}