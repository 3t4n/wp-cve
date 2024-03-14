<?php


if (!function_exists('ai_post_generator_add_integration_code_body_academy')) {


	function ai_post_generator_add_integration_code_body_academy()
	{


?>



<div id="wp-body-content" class="container">
	<?php
			ai_post_generator_add_integration_code_header()
			?>

	<div class="wrap container" id="autowriter-content">

		<div class="my-5 text-center">
			<iframe style="width:80%; height:auto; min-height:300px; max-width:700px;"
				src="https://www.youtube.com/embed/iLFgrcu3Qx4">
			</iframe>
		</div>
		<div class="my-5 text-center">
			<h4>
				If you have any doubts, don't hesitate to visit our <a target="_blank"
					href="https://autowriter.tech/video-academy">academy</a>
			</h4>
		</div>

		<div class="accordion" id="accordionExample">
			<div class="accordion-item">
				<h4 class="accordion-header" id="headingOne">
					<button class="accordion-button" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
						How does it work?
					</button>
				</h4>
				<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						<strong>Autowriter</strong> uses language model GPT-3 to create the content for you.

						All you have to do is write the title you want for your post and the plugin generates relevant
						and coherent content based on that input.
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h4 class="accordion-header" id="headingTwo">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
						What are tokens?
					</button>
				</h4>
				<div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						Tokens are the data structures that represent the words and phrases that make up the text.

						They are used to represent the text in a more efficient way, and allow the AI to better
						understand the language used in the text.
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h4 class="accordion-header" id="headingThree">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						How do I choose the language of the post?
					</button>
				</h4>
				<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						<strong>The language in which the post is generated depends on the language in which the title
							is written.</strong>
						Sometimes, if the title is too short or has spelling mistakes, it may detect a different
						language. If this happens, you should simply write a longer title to make sure that the
						Autowriter software detects the language correctly.
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