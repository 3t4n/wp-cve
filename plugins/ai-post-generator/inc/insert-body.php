<?php
if (!function_exists('ai_post_generator_add_integration_code_body')) {

	/**
	 *
	 * 	 * Insert Code into HTML body
	 *
	 * 	 *
	 *
	 * 	 * @since  1.0.0
	 *
	 * 	 * @author Kekotron
	 *
	 * 	 */

	function ai_post_generator_add_integration_code_body()
	{
		/*
		$get_data_ai_concepts = autowriter_callAPI('GET', "https://webator.es/gpt3_api/get_concepts.php", false);
		$concepts = json_decode($get_data_ai_concepts, true);
		*/

?>

<div id="wp-body-content" class="container">
	<?php
			ai_post_generator_add_integration_code_header()
			?>
	<div class="wrap" id="autowriter-content">
		<ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">

			<li class="nav-item" role="presentation">

				<button class="nav-link active" style="border: 1px solid #0d6efd;" id="pills-home-tab"
					data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab"
					aria-controls="pills-home" aria-selected="true">New Post</button>
			</li>
			<li class="nav-item" role="presentation">

				<button class="nav-link" style="border: 1px solid #0d6efd;" id="pills-train-tab" data-bs-toggle="pill"
					data-bs-target="#pills-train" type="button" role="tab" aria-controls="pills-train"
					aria-selected="false">My Posts</button>

			</li>
			<li class="nav-item" role="presentation">

				<button class="nav-link" style="border: 1px solid #0d6efd;" data-bs-toggle="pill" type="button"
					role="tab" aria-selected="false"
					onclick="window.location.href= '<?php echo get_admin_url(); ?>admin.php?page=autowriter_upgrade_plan';">Upgrade
					Plan</button>


			</li>

		</ul>

		<div class="container tab-content" id="pills-tabContent">

			<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

				<div class="container px-0 mt-4">

					<div class="row my-5">

						<!--
							<h3 class="text-center mt-5 mb-2">New Post</h3>
						-->

						<div class="form-group my-4">

							<input class="form-control gpt3-title" type="text" name="title" id="title"
								placeholder="Title of Blog">

						</div>

						<div class="container">

							<div class="my-4 d-flex align-items-end justify-content-center">

								<h4>Table of content</h4>

								<div class="ms-3 me-2"><svg onclick="table_of_content(this)" class="regpt"
										xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
										<path
											d="M142.9 142.9c62.2-62.2 162.7-62.5 225.3-1L327 183c-6.9 6.9-8.9 17.2-5.2 26.2s12.5 14.8 22.2 14.8H463.5c0 0 0 0 0 0H472c13.3 0 24-10.7 24-24V72c0-9.7-5.8-18.5-14.8-22.2s-19.3-1.7-26.2 5.2L413.4 96.6c-87.6-86.5-228.7-86.2-315.8 1C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5c7.7-21.8 20.2-42.3 37.8-59.8zM16 312v7.6 .7V440c0 9.7 5.8 18.5 14.8 22.2s19.3 1.7 26.2-5.2l41.6-41.6c87.6 86.5 228.7 86.2 315.8-1c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.2 62.2-162.7 62.5-225.3 1L185 329c6.9-6.9 8.9-17.2 5.2-26.2s-12.5-14.8-22.2-14.8H48.4h-.7H40c-13.3 0-24 10.7-24 24z" />
									</svg>

								</div>
								<span style="font-size: small">-0.2 posts</span>

							</div>

							<div class="treeview js-treeview">

								<ul class="ul-gpt3" id="ul-gpt3">

									<li>

										<div class="treeview__level" data-level="A" data-value="1">

											<div class="treeview__level-btns me-2">

												<div class="btn btn-default btn-sm level-add"><span
														class="fa fa-plus"></span>

													<div class="gpt3-buttons">

														<div class="btn btn-default btn-sm level-same"><span
																class="fa fa-arrow-down"></span></div>

														<div class="btn btn-default btn-sm level-sub"><span
																class="fa fa-arrow-right"></span></div>

														<div class="btn btn-default btn-sm level-remove"><span
																class="fa fa-trash text-danger"></span></div>

													</div>

												</div>

											</div>

											<span class="level-title mx-2">1.</span>

											<input class="gpt3-input" type="text">

										</div>

										<ul class="ul-gpt3">

										</ul>

									</li>

								</ul>

							</div>

							<template id="levelMarkup">

								<li>

									<div class="treeview__level" data-level="A">

										<div class="treeview__level-btns me-2">

											<div class="btn btn-default btn-sm level-add"><span
													class="fa fa-plus"></span>

												<div class="gpt3-buttons">

													<div class="btn btn-default btn-sm level-same"><span
															class="fa fa-arrow-down"></span></div>

													<div class="btn btn-default btn-sm level-sub"><span
															class="fa fa-arrow-right"></span></div>

													<div class="btn btn-default btn-sm level-remove"><span
															class="fa fa-trash text-danger"></span></div>

												</div>

											</div>

										</div>

										<span class="level-title mx-2">Titulo</span>

										<input class="gpt3-input" type="text">

									</div>

									<ul class="ul-gpt3">

									</ul>

								</li>

							</template>

							<template id="levelMarksame">

								<div class="treeview__level" data-level="A">

									<div class="treeview__level-btns me-2">

										<div class="btn btn-default btn-sm level-add"><span class="fa fa-plus"></span>

											<div class="gpt3-buttons">

												<div class="btn btn-default btn-sm level-same"><span
														class="fa fa-arrow-down"></span></div>

												<div class="btn btn-default btn-sm level-sub"><span
														class="fa fa-arrow-right"></span></div>

												<div class="btn btn-default btn-sm level-remove"><span
														class="fa fa-trash text-danger"></span></div>

											</div>

										</div>

									</div>

									<span class="level-title mx-2">Titulo</span>

									<input class="gpt3-input" type="text">

								</div>

								<ul class="ul-gpt3">

								</ul>

							</template>
							<div class="mt-5 pt-5 d-flex align-items-end justify-content-center">

								<h4 id="autowriter-rand">Post Cover</h4>

								<div class="ms-3"><svg onclick="get_images(this)" class="regpt"
										xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
										<path
											d="M142.9 142.9c62.2-62.2 162.7-62.5 225.3-1L327 183c-6.9 6.9-8.9 17.2-5.2 26.2s12.5 14.8 22.2 14.8H463.5c0 0 0 0 0 0H472c13.3 0 24-10.7 24-24V72c0-9.7-5.8-18.5-14.8-22.2s-19.3-1.7-26.2 5.2L413.4 96.6c-87.6-86.5-228.7-86.2-315.8 1C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5c7.7-21.8 20.2-42.3 37.8-59.8zM16 312v7.6 .7V440c0 9.7 5.8 18.5 14.8 22.2s19.3 1.7 26.2-5.2l41.6-41.6c87.6 86.5 228.7 86.2 315.8-1c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.2 62.2-162.7 62.5-225.3 1L185 329c6.9-6.9 8.9-17.2 5.2-26.2s-12.5-14.8-22.2-14.8H48.4h-.7H40c-13.3 0-24 10.7-24 24z" />
									</svg>

								</div>

								<div class="ms-3"><svg onclick="show_pop_img()" xmlns="http://www.w3.org/2000/svg"
										class=" regpt bi bi-pencil" viewBox="0 0 16 16">
										<path
											d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
									</svg>

								</div>
								<span style="font-size: small">-0.1 posts</span>
							</div>

							<div class="ai-images" id="ai-images">

							</div>
							<div id="ai-trained-model" style="display: none;"
								class="text-center my-5 flex-column flex-xl-row align-items-center justify-content-center">
								<h4 class="my-3 me-3">Use Trained Model?</h4>
								<select id="ai-train-select" aria-label="Default select example">
									<option data-id="no" selected>-</option>
								</select>
							</div>
							<div class="text-center my-5">

								<div class="d-flex my-5 justify-content-evenly">

									<button class="btn btn-lg btn-primary w-auto"
										id="gpt3-button-create">Create</button>

									<!--

                                        <button class="btn btn-lg btn-success w-auto" id="gpt3-button-draft" data-type="draft" >Save as draft</button>
										                                        <button class="btn btn-lg btn-primary w-auto" id="gpt3-button-create" data-type="publish">Save and Publish</button>

                                        -->

								</div>

								<div class="lds-dual-ring mt-5" id="loader"></div>

								<div class="gpt3-loading" id="gpt3-loading">

									<div class="gpt3-progress-circle"></div>

									<div class="mt-2"> Please wait! Do not leave the site.</div>

								</div>

								<div id="form-errors" class="mt-5" style="display: none;"></div>

							</div>



						</div>

					</div>

				</div>



				<div id="pop-img-cont"></div>
				<!--
				<div id="response-editor-autowriter" class="my-5" style="display: none;">

					<ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
						<li class="nav-item m-2" role="presentation">
							<button class="nav-link active" style="border: 1px solid #0d6efd;" id="pills-preview-tab"
								data-bs-toggle="pill" data-bs-target="#pills-preview" type="button" role="tab"
								aria-controls="pills-preview" aria-selected="false"><i
									class="fa-solid fa-eye"></i></button>
						</li>
						<li class="nav-item m-2" role="presentation">

							<button class="nav-link" style="border: 1px solid #0d6efd;" id="pills-free-tokens-tab"
								data-bs-toggle="pill" data-bs-target="#pills-free-tokens" type="button" role="tab"
								aria-controls="pills-free-tokens" aria-selected="false"><i
									class="fa-solid fa-highlighter"></i></button>
						</li>
					</ul>
					<div class="tab-content" id="pills-tabContent">
						<div class="tab-pane fade show active" id="pills-preview" role="tabpanel"
							aria-labelledby="pills-preview-tab">
							<div class="lds-dual-ring" id="autowriter-load-frame"></div>
							<iframe id="ai-iframe" class="ai-iframe"></iframe>
						</div>
						<div class="tab-pane fade" id="pills-free-tokens" role="tabpanel"
							aria-labelledby="pills-free-tokens-tab">
							<div class="toolbar">
								<button data-command='undo'><i class='fa-solid fa-rotate-left'></i></button>
								<button class="me-3" data-command='redo'><i
										class='fa-solid fa-rotate-right'></i></button>
								<div class="ai-action-wrapper"><i class='fa fa-robot'></i>
									<div class="ai-action-palette flex-column" style="font-size: 1.2rem;">
										<div class="d-flex flex-row align-items-center justify-content-center mb-2">
											<span class="me-3" onclick="togle_info('info-continue')">Continue<i
													class="fa fa-question-circle"
													style="font-size: small; display:inline;margin-left:5px;"></i><small
													id="info-continue"
													style="color:black;font-size:small;display:none;">(place the cursor
													on the desired area)</small></span><button class="m-0"
												onclick="ai_rewrite(this, 'continue')" data-command='gpt'><i
													class='fa fa-plus'></i></button>
										</div>
										<div class="d-flex flex-row align-items-center justify-content-center">
											<span class="me-3" onclick="togle_info('info-rewrite')">Rewrite<i
													class="fa fa-question-circle"
													style="font-size: small; display:inline;margin-left:5px;"></i><small
													id="info-rewrite"
													style="color:black;font-size:small; display:none">(select the
													desired text)</small></span><button class="m-0" data-command='gpt'
												onclick="ai_rewrite(this, 'rewrite')"><i
													class='fa fa-spinner'></i></button>
										</div>
									</div>
								</div>
								<div class="ai-action-wrapper"><i class='fa fa-image'></i>
									<div class="ai-action-palette p-2 flex-column"
										style="font-size: initial;left: -218px;" id="ai-images-palette">
										<div class="d-flex flex-row align-items-center justify-content-center">
											<input id="ai_rich_images_input" type="text" class="gpt3-input2 me-2"
												placeholder="Search image">
											<button class="m-0" onclick="do_rich_images(this)" data-command='gpt'><i
													class='fa fa-rotate'></i></button>
										</div>
										<div class="d-flex flex-row align-items-center justify-content-between overflow-auto"
											id="ai-rich-images" style="max-width: 350px;">
										</div>
									</div>
								</div>
								<button class="ms-3 ai-show" id='ai-richeditor-show'><i
										class='fa fa-ellipsis-v'></i></button>
							</div>
							<div class="toolbar" id="ai-richeditor">
								<button data-command='bold'><i class='fa fa-bold'></i></button>
								<button data-command='h1'>H1</button>
								<button data-command='h2'>H2</button>
								<button data-command='h3'>H3</button>
								<button data-command='p'>P</button>
								<button data-command='createlink'><i class='fa fa-link'></i></button>
								<button data-command='insertUnorderedList'><i class='fa fa-list-ul'></i></button>
								<button data-command='insertOrderedList'><i class='fa fa-list-ol'></i></button>
								<div class="fore-wrapper"><i class='fa fa-font' style='color:#C96;'></i>
									<div class="fore-palette">
									</div>
								</div>
								<div class="back-wrapper"><i class='fa fa-font' style='background:#C96;'></i>
									<div class="back-palette">
									</div>
								</div>
								<button data-command='italic'><i class='fa fa-italic'></i></button>
								<button data-command='underline'><i class='fa fa-underline'></i></button>
								<button data-command='justifyLeft'><i class='fa fa-align-left'></i></button>
								<button data-command='justifyCenter'><i class='fa fa-align-center'></i></button>
								<button data-command='justifyRight'><i class='fa fa-align-right'></i></button>
								<button data-command='justifyFull'><i class='fa fa-align-justify'></i></button>
							</div>
							<div id='autowriter-editor' contenteditable="true">
							</div>
						</div>
					</div>

				</div>
				-->
				<div id="response-gpt3" style="display:none;">

					<div id="gpt3-text"></div>

					<!--
					<div id="response-gpt3-buttons" class="d-flex my-5 justify-content-evenly">
						<svg id="gpt3-button-re" class="regpt" xmlns="http://www.w3.org/2000/svg"
							viewBox="0 0 512 512">
							<path
								d="M142.9 142.9c62.2-62.2 162.7-62.5 225.3-1L327 183c-6.9 6.9-8.9 17.2-5.2 26.2s12.5 14.8 22.2 14.8H463.5c0 0 0 0 0 0H472c13.3 0 24-10.7 24-24V72c0-9.7-5.8-18.5-14.8-22.2s-19.3-1.7-26.2 5.2L413.4 96.6c-87.6-86.5-228.7-86.2-315.8 1C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5c7.7-21.8 20.2-42.3 37.8-59.8zM16 312v7.6 .7V440c0 9.7 5.8 18.5 14.8 22.2s19.3 1.7 26.2-5.2l41.6-41.6c87.6 86.5 228.7 86.2 315.8-1c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.2 62.2-162.7 62.5-225.3 1L185 329c6.9-6.9 8.9-17.2 5.2-26.2s-12.5-14.8-22.2-14.8H48.4h-.7H40c-13.3 0-24 10.7-24 24z" />
						</svg>
						
						<button class="btn btn-lg btn-success w-auto" id="gpt3-button-draft" data-type="draft">Draft <i
								class="fa-solid fa-cloud ms-2"></i></button>
								<button class="btn btn-lg btn-primary w-auto" id="gpt3-button-publish"
								data-type="publish">Publish <i class="fa-solid fa-floppy-disk ms-2"></i></button>
								
								
							</div>
						-->

				</div>

			</div>


			<!--Content Plan-->
			<div class="tab-pane fade" id="pills-train" role="tabpanel" aria-labelledby="pills-train-tab">
				<div style="background: #0000ff0d;border-radius: 7px;">
					<div class="my-5 py-4 d-flex flex-row justify-content-center align-items-center">
						<p class="mb-0 me-3" style="font-size: large;">Add topic to write about</p>
						<button class="btn btn-primary " id="ai-new-content-plan"><i class="fa fa-plus"></i></button>
					</div>
					<div id="new-content-plan" class="my-5 ai-show-none">
						<div class="ai-add-train">
							<p class="py-3" style="font-size: large;">Write the topic you want to post about
							</p>
							<input type="text" class="form-control gpt3-title my-4" id="ai-concept-title"
								maxlength="200" placeholder="Example: BlockChain."></input>


							<p class="my-3" style="font-size: inherit;">Language:</p>
							<input type="text" class="form-control my-4" id="ai-concept-idiom" maxlength="200"
								placeholder="English"></input>
							<label for="n_titles" class="form-label">Number of post titles: <span
									id="n_titles_value">15</span></label>
							<input type="range" class="form-range" default="15" min="5" max="25" step="1" id="n_titles">
							<p> Cost: - <span id="ai-cost-titles">1.5</span> posts</p>
							<button class="btn btn-primary my-4 d-block m-auto" id="ai-add-train">Create titles <span
									id="load-titles" class=" ms-2 load-titles none"><i
										class="fa fa-spinner"></i></span></button>
							<div class="text-danger text-center" id="form-errors-concept"></div>
						</div>

					</div>
				</div>
				<!--
				<p class="my-5" style="font-size: inherit; text-align:center;">In this section, you can create
					your content plan. </p>
				<button class="btn btn-primary btn-lg d-block m-auto" id="ai-new-content-plan"><i
						class="fa fa-plus"></i></button>
						-->

				<div id="response-editor-autowriter" class="my-5" style="display: none;">
					<button id="ai-close-editor">x</button>
					<ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
						<li class="nav-item m-2" role="presentation">
							<button class="nav-link active" style="border: 1px solid #0d6efd;" id="pills-preview-tab"
								data-bs-toggle="pill" data-bs-target="#pills-preview" type="button" role="tab"
								aria-controls="pills-preview" aria-selected="false"><i
									class="fa-solid fa-eye"></i></button>
						</li>
						<li class="nav-item m-2" role="presentation">

							<button class="nav-link" style="border: 1px solid #0d6efd;" id="pills-free-tokens-tab"
								data-bs-toggle="pill" data-bs-target="#pills-free-tokens" type="button" role="tab"
								aria-controls="pills-free-tokens" aria-selected="false"><i
									class="fa-solid fa-highlighter"></i></button>
						</li>
					</ul>
					<div class="tab-content" id="pills-tabContent">
						<div class="tab-pane fade show active" id="pills-preview" role="tabpanel"
							aria-labelledby="pills-preview-tab">
							<div class="lds-dual-ring" id="autowriter-load-frame"></div>
							<iframe id="ai-iframe" class="ai-iframe"></iframe>
						</div>
						<div class="tab-pane fade" id="pills-free-tokens" role="tabpanel"
							aria-labelledby="pills-free-tokens-tab">
							<div class="toolbar">
								<button data-command='undo'><i class='fa-solid fa-rotate-left'></i></button>
								<button class="me-3" data-command='redo'><i
										class='fa-solid fa-rotate-right'></i></button>
								<div class="ai-action-wrapper"><i class='fa fa-robot'></i>
									<div class="ai-action-palette flex-column" style="font-size: 1.2rem;">
										<div class="d-flex flex-row align-items-center justify-content-center mb-2">
											<span class="me-3" onclick="togle_info('info-continue')">Continue<i
													class="fa fa-question-circle"
													style="font-size: small; display:inline;margin-left:5px;"></i><small
													id="info-continue"
													style="color:black;font-size:small;display:none;">(place the cursor
													on the desired area. Cost of -0.2 posts)</small></span><button class="m-0"
												onclick="ai_rewrite(this, 'continue')" data-command='gpt'><i
													class='fa fa-plus'></i></button>
										</div>
										<div class="d-flex flex-row align-items-center justify-content-center">
											<span class="me-3" onclick="togle_info('info-rewrite')">Rewrite<i
													class="fa fa-question-circle"
													style="font-size: small; display:inline;margin-left:5px;"></i><small
													id="info-rewrite"
													style="color:black;font-size:small; display:none">(select the
													desired text. Cost of -0.4 posts)</small></span><button class="m-0" data-command='gpt'
												onclick="ai_rewrite(this, 'rewrite')"><i
													class='fa fa-spinner'></i></button>
										</div>
									</div>
								</div>
								<div class="ai-action-wrapper"><i class='fa fa-image'></i>
									<div class="ai-action-palette p-2 flex-column"
										style="font-size: initial;left: -218px;" id="ai-images-palette">
										<div class="d-flex flex-row align-items-center justify-content-center">
											<input id="ai_rich_images_input" type="text" class="gpt3-input2 me-2"
												placeholder="Search image">
											<button class="m-0" onclick="do_rich_images(this)" data-command='gpt'><i
													class='fa fa-rotate'></i></button>
										</div>
										<div class="d-flex flex-row align-items-center justify-content-between overflow-auto"
											id="ai-rich-images" style="max-width: 350px;">
										</div>
									</div>
								</div>
								<button class="ms-3 ai-show" id='ai-richeditor-show'><i
										class='fa fa-ellipsis-v'></i></button>
							</div>
							<div class="toolbar" id="ai-richeditor">
								<button data-command='bold'><i class='fa fa-bold'></i></button>
								<button data-command='h1'>H1</button>
								<button data-command='h2'>H2</button>
								<button data-command='h3'>H3</button>
								<button data-command='p'>P</button>
								<button data-command='createlink'><i class='fa fa-link'></i></button>
								<button data-command='insertUnorderedList'><i class='fa fa-list-ul'></i></button>
								<button data-command='insertOrderedList'><i class='fa fa-list-ol'></i></button>
								<div class="fore-wrapper"><i class='fa fa-font' style='color:#C96;'></i>
									<div class="fore-palette">
									</div>
								</div>
								<div class="back-wrapper"><i class='fa fa-font' style='background:#C96;'></i>
									<div class="back-palette">
									</div>
								</div>
								<button data-command='italic'><i class='fa fa-italic'></i></button>
								<button data-command='underline'><i class='fa fa-underline'></i></button>
								<button data-command='justifyLeft'><i class='fa fa-align-left'></i></button>
								<button data-command='justifyCenter'><i class='fa fa-align-center'></i></button>
								<button data-command='justifyRight'><i class='fa fa-align-right'></i></button>
								<button data-command='justifyFull'><i class='fa fa-align-justify'></i></button>
							</div>
							<!--
						-->
							<div id='autowriter-editor' contenteditable="true">
							</div>
						</div>
					</div>
					<div id="response-gpt3-buttons" style="display:none;" class="my-5 justify-content-evenly">
						<button class="btn btn-lg btn-primary w-auto" id="gpt3-button-publish"
							data-type="publish">Publish <i class="fa-solid fa-floppy-disk ms-2"></i></button>


					</div>

				</div>
				<div class="my-5">
					<div style="width:100%; overflow-x:auto;" class="payment-scrollbar pb-4">
						<table id="concepts-table" class="display responsive nowrap" style="text-align: center;"
							cellspacing="0" width="90%">
							<thead>
								<tr>
									<th scope="col">Title</th>
									<th scope="col">Status</th>
									<th scope="col">Action</th>
									<th scope="col" id="ai-title-date">Date</th>
								</tr>
							</thead>
							<tbody id="ai-train-tbody">
							</tbody>
						</table>
					</div>
				</div>
				<div id="pop-concept-cont"></div>

			</div>

			<!--END BUY TOKENS-->
			<!--FREE TOKENS-->
			<div class="tab-pane fade" id="pills-free-tokens" role="tabpanel" aria-labelledby="pills-free-tokens-tab">



			</div>
			<!--END FREE TOKENS-->

		</div>

	</div>

	<div class="wrap mt-5" style="text-align: right;">

		<p>Developed By <a href="https://autowriter.tech" target="_blank">AutoWriter</a></p>

	</div>

</div>

<?php

	}
}