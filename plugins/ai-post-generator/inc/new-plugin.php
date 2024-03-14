<?php
// Añade el menú de opciones al dashboard de WordPress.
if (!function_exists('ai_post_other_plugins_add_menu')) {

	function ai_post_other_plugins_add_menu(){

		add_submenu_page(
			'ai_post_generator',
			'Gamify your website',
			'Gamify your website' . '<span class="update-plugins"><span class="plugin-count">1</span></span>',
			'manage_options',
			'new_plugin',
			'ai_post_other_plugins'
		);
		add_submenu_page(
			'edit.php',
			'Gamify your posts',
			'Gamify your posts' . '<span class="update-plugins"><span class="plugin-count">1</span></span>',
			'manage_options',
			'new_plugin',
			'ai_post_other_plugins'
		);
	}
	add_action('admin_menu', 'ai_post_other_plugins_add_menu');
}
if (!function_exists('ai_post_redirect_ai_quiz_activation')) {
	function ai_post_redirect_ai_quiz_activation() {
		if (isset($_GET['action']) && $_GET['action'] == 'activate-plugin' && isset($_GET['plugin']) && $_GET['plugin'] == 'ai-quiz/ai-quiz.php') {
			// Verifica el nonce para asegurarse de que la solicitud sea legítima.
			if (check_admin_referer('activate-plugin_ai-quiz')) {
				// Redirige a la página del plugin.
				wp_redirect(admin_url('admin.php?page=ai-quiz'));
				exit;
			}
		}
	}
	add_action('admin_init', 'ai_post_redirect_ai_quiz_activation');
}


if (!function_exists('ai_post_other_plugins')) {

	function ai_post_other_plugins(){
		$my_css_ver = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/ai-quiz-styles.css'));
		wp_enqueue_style('my-styles-ai-quiz-new-plugin',trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/ai-quiz-styles.css",null,$my_css_ver);
		$my_bootstrap = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/bootstrap.min.css'));
		wp_enqueue_style('my-styles-ai-quiz-new-plugin-bootstrap',trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/bootstrap.min.css",null,$my_bootstrap);
		$my_f_awesome = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/font-awesome.min.css'));
		wp_enqueue_style('my-styles-ai-quiz-new-plugin-fa',trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/font-awesome.min.css",null,$my_f_awesome);
		include_once(ABSPATH . 'wp-admin/includes/plugin.php');
		// Slug del plugin en el repositorio de WordPress y el archivo principal del plugin.
		$plugin_slug = 'ai-quiz'; //La url de mi plugin es así
		$plugin_file = 'ai-quiz/ai-quiz.php'; // El archivo principal se llama así.

		// Comprueba si el plugin está activo.
		if (is_plugin_active($plugin_file)) {
			//wp_redirect(admin_url('admin.php?page=ai_quiz'));
			?>
<script>
window.location.href = "<?php echo admin_url('admin.php?page=ai_quiz');?>"
</script>
<?php
			exit;
			//$button_text = 'Ir a AI Quiz';
		} elseif (file_exists(WP_PLUGIN_DIR . '/' . $plugin_file)) {
			$activate_url = wp_nonce_url(add_query_arg(array(
				'action' => 'activate',
				'plugin' => urlencode($plugin_file) // Aquí se debe usar el archivo del plugin, no el slug.
			), admin_url('plugins.php')), 'activate-plugin_' . $plugin_file); // Asegúrate de que el nonce coincide con este.
			
			?>
<div class="container text-center">
	<div class="wrap">
		<h4 class="my-4 text-center">Gamify your website and improve your SEO</h4>
		<!-- Button to active the plugin -->
		<a  href="<?php echo esc_url($activate_url); ?>" class="btn btn-primary my-2 ai-quiz-download-btn">
			<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
		viewBox="0 0 795.3 851" style="width:50px;" xml:space="preserve">
	<style type="text/css">
		.st0{fill:#5858ed;}
	</style>
	<g>
		<path class="st0" d="M680.4,723.3c-10.1-9.6-8.8-21.1-8.4-34.1c1.3-19.9,5.2-42.8,6.6-64.6c0.1-1.7,0.7-10.3,0.7-11.9
			c0.1-12.9-2.4-27.1-5.8-38.5c-13-40.8-38-74-69.8-99.5l75.9-39.2c7.2-3.7,9.7-12.7,5.6-19.6L440.3,6.8c-7.3-12.2-26-7-26,7.2v261.5
			c19.9,6.9,34.2,25.8,34.2,48c0,28.1-22.8,50.8-50.9,50.8s-50.9-22.8-50.9-50.8c0-22.2,14.3-41.1,34.2-48V14
			c0-14.2-18.7-19.4-26-7.2L110.2,416c-4.1,6.9-1.6,15.9,5.6,19.6l75.9,39.2c-19.1,15.4-35.9,33.7-49.5,55.1
			c-14.7,23.4-25.8,52-26.2,81.2c0.6,25.7,5.8,54,7.3,78.1c0.2,9.6,1.2,17.5-2.5,26c-10.4,17.5-30.8,27.2-54.4,34.7
			c-20.2,6-42.5,9.1-66.5,9.5c60.3,45.9,171.9,44.2,200.7-38.3c12.2-33.7,11.4-68.6,17-102c1-4.2,2.4-6.3,4.1-9.1
			c11.8-18.5,27.8-32.4,46.4-42.6c-0.2,2.2-0.3,4.4-0.3,6.5c-1.1,37.1,5.9,73,7.5,108.4c1.7,26.6,2.5,50.9-0.8,75.4
			c-1.5,10.2-3.4,19.6-8.2,28.1c-21.2,27-78,31.2-112.7,32.2c9.7,7.2,20.3,13.2,31.6,18.2c37.9,16,81.3,22.8,120.1,0.6
			c47.7-29.5,56-102.4,61.8-152.6c2.2-24.1,3.5-47.8,4.7-70.1c2.6-20.5,7.1-44.9,22.3-58.4c1.2-1,2.4-1.9,3.7-2.7
			c5.2,3.6,9.6,8.3,13.2,14.6c7.3,12.8,11,30.8,12.8,46.5c1.1,22.3,2.4,46.1,4.7,70.1c9.2,91.6,31.4,185,146.1,163.5
			c24.5-5.1,47.3-15.1,67.3-29.6c-34.9-1-91.4-5.2-112.7-32.2c-6.9-12.4-8.4-27.3-9.7-41.8c-2.8-41.5,2.7-85.1,6.4-128.3
			c1.5-13,2.4-27,1.8-41.7c-0.1-2.2-0.2-4.4-0.3-6.5c18.7,10.2,35.2,23.8,46.4,42.6c0.9,1.4,2.5,3.6,3.8,7.2
			c2.2,14.1,3.6,27.1,5.2,42.4c2.9,27.3,7,60.3,24,84.9c26.1,38.9,77.8,50.5,120.8,43c25.3-4.2,48.6-14,68-28.3
			C754.8,758.4,707.4,750.3,680.4,723.3z M300.9,508.9c-28.1,0-50.8-22.8-50.8-50.8c0-28.1,22.8-50.9,50.8-50.9
			c28.1,0,50.9,22.8,50.9,50.9C351.8,486.1,329,508.9,300.9,508.9z M494.4,508.9c-28.1,0-50.9-22.8-50.9-50.8
			c0-28.1,22.8-50.9,50.9-50.9s50.9,22.8,50.9,50.9C545.2,486.1,522.5,508.9,494.4,508.9z"/>
	</g>
	</svg>
		Go to AI Quiz
		</a>
	</div>
</div>
<?php
		}  else {
			// El plugin no está activo, muestra el enlace de instalación.
			$install_url = wp_nonce_url(add_query_arg(array(
				'action' => 'install-plugin',
				'plugin' => $plugin_slug
			), admin_url('update.php')), 'install-plugin_' . $plugin_slug);
			$url = $install_url;
			$button_text = 'Install AutoQuiz Maker';
		?>
<div class="container text-center">

	<div class="wrap">
		<h4 class="text-center my-4">Gamify your website and improve your SEO</h4>
		<p class="my-4 text-center" style="font-size: medium;">Install AI Quiz Maker to create automatically funny quizs to enhance your audience.</p>
		<a href="<?php echo esc_url($url); ?>" class="btn btn-primary my-2 ai-quiz-download-btn">
			<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			viewBox="0 0 795.3 851" style="width:50px;" xml:space="preserve">
			<style type="text/css">
				.st0{fill:#5858ed;}
			</style>
			<g>
				<path class="st0" d="M680.4,723.3c-10.1-9.6-8.8-21.1-8.4-34.1c1.3-19.9,5.2-42.8,6.6-64.6c0.1-1.7,0.7-10.3,0.7-11.9
					c0.1-12.9-2.4-27.1-5.8-38.5c-13-40.8-38-74-69.8-99.5l75.9-39.2c7.2-3.7,9.7-12.7,5.6-19.6L440.3,6.8c-7.3-12.2-26-7-26,7.2v261.5
					c19.9,6.9,34.2,25.8,34.2,48c0,28.1-22.8,50.8-50.9,50.8s-50.9-22.8-50.9-50.8c0-22.2,14.3-41.1,34.2-48V14
					c0-14.2-18.7-19.4-26-7.2L110.2,416c-4.1,6.9-1.6,15.9,5.6,19.6l75.9,39.2c-19.1,15.4-35.9,33.7-49.5,55.1
					c-14.7,23.4-25.8,52-26.2,81.2c0.6,25.7,5.8,54,7.3,78.1c0.2,9.6,1.2,17.5-2.5,26c-10.4,17.5-30.8,27.2-54.4,34.7
					c-20.2,6-42.5,9.1-66.5,9.5c60.3,45.9,171.9,44.2,200.7-38.3c12.2-33.7,11.4-68.6,17-102c1-4.2,2.4-6.3,4.1-9.1
					c11.8-18.5,27.8-32.4,46.4-42.6c-0.2,2.2-0.3,4.4-0.3,6.5c-1.1,37.1,5.9,73,7.5,108.4c1.7,26.6,2.5,50.9-0.8,75.4
					c-1.5,10.2-3.4,19.6-8.2,28.1c-21.2,27-78,31.2-112.7,32.2c9.7,7.2,20.3,13.2,31.6,18.2c37.9,16,81.3,22.8,120.1,0.6
					c47.7-29.5,56-102.4,61.8-152.6c2.2-24.1,3.5-47.8,4.7-70.1c2.6-20.5,7.1-44.9,22.3-58.4c1.2-1,2.4-1.9,3.7-2.7
					c5.2,3.6,9.6,8.3,13.2,14.6c7.3,12.8,11,30.8,12.8,46.5c1.1,22.3,2.4,46.1,4.7,70.1c9.2,91.6,31.4,185,146.1,163.5
					c24.5-5.1,47.3-15.1,67.3-29.6c-34.9-1-91.4-5.2-112.7-32.2c-6.9-12.4-8.4-27.3-9.7-41.8c-2.8-41.5,2.7-85.1,6.4-128.3
					c1.5-13,2.4-27,1.8-41.7c-0.1-2.2-0.2-4.4-0.3-6.5c18.7,10.2,35.2,23.8,46.4,42.6c0.9,1.4,2.5,3.6,3.8,7.2
					c2.2,14.1,3.6,27.1,5.2,42.4c2.9,27.3,7,60.3,24,84.9c26.1,38.9,77.8,50.5,120.8,43c25.3-4.2,48.6-14,68-28.3
					C754.8,758.4,707.4,750.3,680.4,723.3z M300.9,508.9c-28.1,0-50.8-22.8-50.8-50.8c0-28.1,22.8-50.9,50.8-50.9
					c28.1,0,50.9,22.8,50.9,50.9C351.8,486.1,329,508.9,300.9,508.9z M494.4,508.9c-28.1,0-50.9-22.8-50.9-50.8
					c0-28.1,22.8-50.9,50.9-50.9s50.9,22.8,50.9,50.9C545.2,486.1,522.5,508.9,494.4,508.9z"/>
			</g>
			</svg>
	<?php echo $button_text; ?></a>
		<div class="my-3 text-center">
			<iframe style="width:80%; height:auto; min-height:300px; max-width:700px;"
				src="https://www.youtube.com/embed/2VM9ENQIcO8?si=NKUb-40xOdpINww1" title="YouTube video player"
				frameborder="0"
				allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
				allowfullscreen></iframe>
		</div>
		<h4 class="text-center my-4">This is just an example of AI Quiz can make yor you</h4>
		<div class="align-items-start justify-content-center" id="face-score" >
			<div id="ai-quiz-share"><i class="fa fa-share-square"></i></div>
			<form class="fr" action="">
				<div class="fr__face" role="img" aria-label="Straight face">
					<div class="fr__face-right-eye" data-right></div>
					<div class="fr__face-left-eye" data-left></div>
					<div class="fr__face-mouth-lower" data-mouth-lower></div>
					<div class="fr__face-mouth-upper" data-mouth-upper></div>
				</div>
				<input class="fr__input " id="face-rating" type="hidden" value="2.5" min="0" max="5">
			</form>
			<h5 class="mb-0" id="score-text">
				<span id="success-score"></span> /
				<span id="total-score"></span>
			</h5>
		</div>
		<div class="d-flex justify-content-center align-items-center mb-4">
			<div id="ai-quiz-pagination" class="ai-quiz-shortcode-scrollbar p-3 pt-0">
				<!-- Aquí se agregarán los botones de navegación -->
			</div>
		</div>

		<div id="ai-quiz-question">
			<div class="ai-quiz-loader d-flex my-5"></div>
		</div>
		<div class="d-flex flex-row align-items-center justify-content-evenly" id="ai-quiz-next-ant-buttons">
			<button id="ai-quiz-prev-btn" style="display: none;"><</button>
			<button id="ai-quiz-next-btn" style="display: none;">></button>
		</div>
		<div>
			<p class="text-end mt-2">Powered by <span><a target="_blank" href="https://wordpress.org/plugins/ai-quiz/">AI-Quiz</a></span></p>
		</div>

		<a href="<?php echo esc_url($url); ?>" class="btn btn-primary my-2 ai-quiz-download-btn">
		<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			viewBox="0 0 795.3 851" style="width:50px;" xml:space="preserve">
			<style type="text/css">
				.st0{fill:#5858ed;}
			</style>
			<g>
				<path class="st0" d="M680.4,723.3c-10.1-9.6-8.8-21.1-8.4-34.1c1.3-19.9,5.2-42.8,6.6-64.6c0.1-1.7,0.7-10.3,0.7-11.9
					c0.1-12.9-2.4-27.1-5.8-38.5c-13-40.8-38-74-69.8-99.5l75.9-39.2c7.2-3.7,9.7-12.7,5.6-19.6L440.3,6.8c-7.3-12.2-26-7-26,7.2v261.5
					c19.9,6.9,34.2,25.8,34.2,48c0,28.1-22.8,50.8-50.9,50.8s-50.9-22.8-50.9-50.8c0-22.2,14.3-41.1,34.2-48V14
					c0-14.2-18.7-19.4-26-7.2L110.2,416c-4.1,6.9-1.6,15.9,5.6,19.6l75.9,39.2c-19.1,15.4-35.9,33.7-49.5,55.1
					c-14.7,23.4-25.8,52-26.2,81.2c0.6,25.7,5.8,54,7.3,78.1c0.2,9.6,1.2,17.5-2.5,26c-10.4,17.5-30.8,27.2-54.4,34.7
					c-20.2,6-42.5,9.1-66.5,9.5c60.3,45.9,171.9,44.2,200.7-38.3c12.2-33.7,11.4-68.6,17-102c1-4.2,2.4-6.3,4.1-9.1
					c11.8-18.5,27.8-32.4,46.4-42.6c-0.2,2.2-0.3,4.4-0.3,6.5c-1.1,37.1,5.9,73,7.5,108.4c1.7,26.6,2.5,50.9-0.8,75.4
					c-1.5,10.2-3.4,19.6-8.2,28.1c-21.2,27-78,31.2-112.7,32.2c9.7,7.2,20.3,13.2,31.6,18.2c37.9,16,81.3,22.8,120.1,0.6
					c47.7-29.5,56-102.4,61.8-152.6c2.2-24.1,3.5-47.8,4.7-70.1c2.6-20.5,7.1-44.9,22.3-58.4c1.2-1,2.4-1.9,3.7-2.7
					c5.2,3.6,9.6,8.3,13.2,14.6c7.3,12.8,11,30.8,12.8,46.5c1.1,22.3,2.4,46.1,4.7,70.1c9.2,91.6,31.4,185,146.1,163.5
					c24.5-5.1,47.3-15.1,67.3-29.6c-34.9-1-91.4-5.2-112.7-32.2c-6.9-12.4-8.4-27.3-9.7-41.8c-2.8-41.5,2.7-85.1,6.4-128.3
					c1.5-13,2.4-27,1.8-41.7c-0.1-2.2-0.2-4.4-0.3-6.5c18.7,10.2,35.2,23.8,46.4,42.6c0.9,1.4,2.5,3.6,3.8,7.2
					c2.2,14.1,3.6,27.1,5.2,42.4c2.9,27.3,7,60.3,24,84.9c26.1,38.9,77.8,50.5,120.8,43c25.3-4.2,48.6-14,68-28.3
					C754.8,758.4,707.4,750.3,680.4,723.3z M300.9,508.9c-28.1,0-50.8-22.8-50.8-50.8c0-28.1,22.8-50.9,50.8-50.9
					c28.1,0,50.9,22.8,50.9,50.9C351.8,486.1,329,508.9,300.9,508.9z M494.4,508.9c-28.1,0-50.9-22.8-50.9-50.8
					c0-28.1,22.8-50.9,50.9-50.9s50.9,22.8,50.9,50.9C545.2,486.1,522.5,508.9,494.4,508.9z"/>
			</g>
			</svg>
		<?php echo $button_text; ?></a>
	</div>
</div>
<script>
	/*
var questions_arr = [{
		"id": "31",
		"user_id": "1",
		"quiz_id": "4",
		"question": "Example 1",
		"type": null,
		"explanation": "Here is the explanation to the question",
		"options": [{
				"id": "148",
				"question_id": "31",
				"answer": "Option 1",
				"answer_bin": "1"
			},
			{
				"id": "149",
				"question_id": "31",
				"answer": "Option2",
				"answer_bin": "0"
			},
			{
				"id": "150",
				"question_id": "31",
				"answer": "Option 3",
				"answer_bin": "0"
			},
			{
				"id": "151",
				"question_id": "31",
				"answer": "Option 4",
				"answer_bin": "0"
			}
		]
	},
	{
		"id": "31",
		"user_id": "1",
		"quiz_id": "4",
		"question": "Example 2",
		"type": null,
		"result": {
			"user": "148",
			"score": 0,
			"answer": "149"
		},
		"explanation": "Here is the explanation to the question",
		"options": [{
				"id": "148",
				"question_id": "31",
				"answer": "Option 1",
				"answer_bin": "1"
			},
			{
				"id": "149",
				"question_id": "31",
				"answer": "Option2",
				"answer_bin": "0"
			},
			{
				"id": "150",
				"question_id": "31",
				"answer": "Option 3",
				"answer_bin": "0"
			},
			{
				"id": "151",
				"question_id": "31",
				"answer": "Option 4",
				"answer_bin": "0"
			}
		]
	},
	{
		"id": "31",
		"user_id": "1",
		"quiz_id": "4",
		"question": "Example 3",
		"result": {
			"user": "149",
			"score": 1,
			"answer": "149"
		},
		"type": null,
		"explanation": "Here is the explanation to the question",
		"options": [{
				"id": "148",
				"question_id": "31",
				"answer": "Option 1",
				"answer_bin": "1"
			},
			{
				"id": "149",
				"question_id": "31",
				"answer": "Option2",
				"answer_bin": "0"
			},
			{
				"id": "150",
				"question_id": "31",
				"answer": "Option 3",
				"answer_bin": "0"
			},
			{
				"id": "151",
				"question_id": "31",
				"answer": "Option 4",
				"answer_bin": "0"
			}
		]
	}
];
*/
var questions_arr = 
	[
		{
			'id': '1',
			'user_id': '1',
			'quiz_id': '4',
			'question': 'What feature does AI Quiz offer for content integration?',
			'type': null,
			'explanation': 'AI Quiz allows quizzes to be automatically inserted into your posts or desired locations via shortcodes.',
			'options': [{
					'id': '1',
					'question_id': '1',
					'answer': 'Automatic quiz generation in posts using shortcodes',
					'answer_bin': '1'
				},
				{
					'id': '2',
					'question_id': '1',
					'answer': 'Automatic post generation from quizzes',
					'answer_bin': '0'
				},
				{
					'id': '3',
					'question_id': '1',
					'answer': 'PDF embedding in posts',
					'answer_bin': '0'
				},
				{
					'id': '4',
					'question_id': '1',
					'answer': 'Direct video upload feature',
					'answer_bin': '0'
				}
			]
		},
		{
			'id': '2',
			'user_id': '1',
			'quiz_id': '4',
			'question': 'Which technology powers the AI Quiz plugin for creating quizzes?',
			'type': null,
			'explanation': "AI Quiz uses the OpenAI's GPT-3 language model to create engaging and interactive quizzes.",
			'options': [{
					'id': '5',
					'question_id': '2',
					'answer': 'GPT-3 language model by OpenAI',
					'answer_bin': '1'
				},
				{
					'id': '6',
					'question_id': '2',
					'answer': "Google's BERT language model",
					'answer_bin': '0'
				},
				{
					'id': '7',
					'question_id': '2',
					'answer': 'Custom-built AI by the plugin developers',
					'answer_bin': '0'
				},
				{
					'id': '8',
					'question_id': '2',
					'answer': "IBM's Watson",
					'answer_bin': '0'
				}
			]
		},
		{
			'id': '3',
			'user_id': '1',
			'quiz_id': '4',
			'question': 'How can users share their quiz results using AI Quiz?',
			'type': null,
			'explanation': 'AI Quiz enables users to share their quiz results on social media, helping to drive more traffic to the site.',
			'options': [{
					'id': '9',
					'question_id': '3',
					'answer': 'Via email notifications',
					'answer_bin': '0'
				},
				{
					'id': '10',
					'question_id': '3',
					'answer': 'Social media sharing',
					'answer_bin': '1'
				},
				{
					'id': '11',
					'question_id': '3',
					'answer': 'Printing results',
					'answer_bin': '0'
				},
				{
					'id': '12',
					'question_id': '3',
					'answer': 'Direct messaging in-app',
					'answer_bin': '0'
				}
			]
		},
		{
			'id': '4',
			'user_id': '1',
			'quiz_id': '4',
			'question': 'What is the primary benefit of using AI Quiz for your website?',
			'type': null,
			'explanation': 'AI Quiz enhances user engagement and boosts SEO rankings through interactive quiz content.',
			'options': [{
					'id': '13',
					'question_id': '4',
					'answer': 'Reducing website loading time',
					'answer_bin': '0'
				},
				{
					'id': '14',
					'question_id': '4',
					'answer': 'Increasing user engagement and SEO rankings',
					'answer_bin': '1'
				},
				{
					'id': '15',
					'question_id': '4',
					'answer': 'Automating customer service',
					'answer_bin': '0'
				},
				{
					'id': '16',
					'question_id': '4',
					'answer': 'Enhancing website security',
					'answer_bin': '0'
				}
			]
		},
		{
			'id': '5',
			'user_id': '1',
			'quiz_id': '4',
			'question': 'What is the process to install the AI Quiz plugin via WordPress Plugin Search?',
			'type': null,
			'explanation': 'The process involves searching for "AI Post Generator" in the WordPress Plugins page, installing, and activating it.',
			'options': [{
					'id': '17',
					'question_id': '5',
					'answer': 'Direct download and installation from an external website',
					'answer_bin': '0'
				},
				{
					'id': '18',
					'question_id': '5',
					'answer': 'Searching and installing via WordPress Plugin Search',
					'answer_bin': '1'
				},
				{
					'id': '19',
					'question_id': '5',
					'answer': 'Manual code insertion in website files',
					'answer_bin': '0'
				},
				{
					'id': '20',
					'question_id': '5',
					'answer': 'Installation via a third-party service',
					'answer_bin': '0'
				}
			]
		},
			{
			"id": "6",
			"user_id": "1",
			"quiz_id": "4",
			"question": "How many languages does the AI Quiz plugin support?",
			"type": null,
			"explanation": "The AI Quiz plugin can generate quizzes in more than 20 languages.",
			"options": [
				{
					"id": "21",
					"question_id": "6",
					"answer": "Only English",
					"answer_bin": "0"
				},
				{
					"id": "22",
					"question_id": "6",
					"answer": "More than 20 languages",
					"answer_bin": "1"
				},
				{
					"id": "23",
					"question_id": "6",
					"answer": "5 languages",
					"answer_bin": "0"
				},
				{
					"id": "24",
					"question_id": "6",
					"answer": "10 languages",
					"answer_bin": "0"
				}
			]
		},
		{
			"id": "7",
			"user_id": "1",
			"quiz_id": "4",
			"question": "Is the AI Quiz WordPress plugin free to use?",
			"type": null,
			"explanation": "AI Quiz offers a free trial, and then quizzes can be purchased with a single click.",
			"options": [
				{
					"id": "25",
					"question_id": "7",
					"answer": "Yes, completely free",
					"answer_bin": "0"
				},
				{
					"id": "26",
					"question_id": "7",
					"answer": "Free trial followed by paid quizzes",
					"answer_bin": "1"
				},
				{
					"id": "27",
					"question_id": "7",
					"answer": "Only available with a subscription",
					"answer_bin": "0"
				},
				{
					"id": "28",
					"question_id": "7",
					"answer": "Free with in-app purchases",
					"answer_bin": "0"
				}
			]
		},
		{
			"id": "8",
			"user_id": "1",
			"quiz_id": "4",
			"question": "What kind of content can AI Quiz generate quizzes from?",
			"type": null,
			"explanation": "AI Quiz can generate quizzes from existing posts, PDFs, webpages, or specific topics.",
			"options": [
				{
					"id": "29",
					"question_id": "8",
					"answer": "Only from existing blog posts",
					"answer_bin": "0"
				},
				{
					"id": "30",
					"question_id": "8",
					"answer": "From posts, PDFs, webpages, or specific topics",
					"answer_bin": "1"
				},
				{
					"id": "31",
					"question_id": "8",
					"answer": "Exclusively from PDF documents",
					"answer_bin": "0"
				},
				{
					"id": "32",
					"question_id": "8",
					"answer": "Only from user-submitted content",
					"answer_bin": "0"
				}
			]
		},
		{
			"id": "9",
			"user_id": "1",
			"quiz_id": "4",
			"question": "How does AI Quiz enhance a website's SEO?",
			"type": null,
			"explanation": "AI Quiz boosts SEO by increasing user engagement and encouraging repeat visits.",
			"options": [
				{
					"id": "33",
					"question_id": "9",
					"answer": "By optimizing keywords in quizzes",
					"answer_bin": "0"
				},
				{
					"id": "34",
					"question_id": "9",
					"answer": "Increasing user engagement and repeat visits",
					"answer_bin": "1"
				},
				{
					"id": "35",
					"question_id": "9",
					"answer": "Direct link-building within quizzes",
					"answer_bin": "0"
				},
				{
					"id": "36",
					"question_id": "9",
					"answer": "Automated ad placement",
					"answer_bin": "0"
				}
			]
		}
	];

	function select_option(x) {
	var options = document.querySelectorAll(".ai-quiz-option");
	for (var i = 0; i < options.length; i++) {
		options[i].classList.remove("selected");
	}
	x.classList.toggle("selected");
	document.getElementById("ai-quiz-checkbutton").style.display = "block";
	}

	class FaceRating {
		constructor(qs) {
			this.input = document.querySelector(qs);
			this.input?.addEventListener("input", this.update.bind(this));
			this.face = this.input?.previousElementSibling;
			this.scoreText = document.querySelector("#score-text");
			this.update();
		}
		update(e) {
			let value = this.input.defaultValue;

			// when manually set
			if (e) value = e.target?.value;
			// when initiated
			else this.input.value = value;

			const min = this.input.min || 0;
			const max = this.input.max || 100;
			const percentRaw = ((value - min) / (max - min)) * 100;
			const percent = +percentRaw.toFixed(2);

			this.input?.style.setProperty("--percent", `${percent}%`);

			// face and range fill colors
			const maxHue = 120;
			const hueExtend = 30;
			const hue = Math.round((maxHue * percent) / 100);

			let hue2 = hue - hueExtend;
			if (hue2 < 0) hue2 += 360;

			const hues = [hue, hue2];
			hues.forEach((h, i) => {
			this.face?.style.setProperty(`--face-hue${i + 1}`, h);
			this.scoreText?.style.setProperty(`--face-hue${i + 1}`, h);
			});

			this.input?.style.setProperty("--input-hue", hue);

			// emotions
			const duration = 1;
			const delay = (-(duration * 0.99) * percent) / 100;
			const parts = ["right", "left", "mouth-lower", "mouth-upper"];

			parts.forEach((p) => {
			const el = this.face?.querySelector(`[data-${p}]`);
			el?.style.setProperty(`--delay-${p}`, `${delay}s`);
			});

			// aria label
			const faces = [
			"Sad face",
			"Slightly sad face",
			"Straight face",
			"Slightly happy face",
			"Happy face",
			];
			let faceIndex = Math.floor((faces.length * percent) / 100);
			if (faceIndex === faces.length) --faceIndex;

			this.face?.setAttribute("aria-label", faces[faceIndex]);
		}
	}

jQuery(document).ready(function ($) {
  $(function () {
    const fr = new FaceRating("#face-rating");

    //Pagination
    const pagination = document.querySelector("#ai-quiz-pagination");
    let startY;
    let startX;
    let scrollLeft;
    let scrollTop;
    let isDown;

    pagination.addEventListener("mousedown", (e) => mouseIsDown(e));
    pagination.addEventListener("mouseup", (e) => mouseUp(e));
    pagination.addEventListener("mouseleave", (e) => mouseLeave(e));
    pagination.addEventListener("mousemove", (e) => mouseMove(e));

    function mouseIsDown(e) {
      isDown = true;
      startY = e.pageY - pagination.offsetTop;
      startX = e.pageX - pagination.offsetLeft;
      scrollLeft = pagination.scrollLeft;
      scrollTop = pagination.scrollTop;
    }
    function mouseUp(e) {
      isDown = false;
    }
    function mouseLeave(e) {
      isDown = false;
    }
    function mouseMove(e) {
      if (isDown) {
        e.preventDefault();
        //Move vertcally
        const y = e.pageY - pagination.offsetTop;
        const walkY = y - startY;
        pagination.scrollTop = scrollTop - walkY;

        //Move Horizontally
        const x = e.pageX - pagination.offsetLeft;
        const walkX = x - startX;
        pagination.scrollLeft = scrollLeft - walkX;
      }
    }

    $("#ai-quiz-share").on("click", function () {
      if (document.getElementById("ai-quiz-custom-pop")) {
        document.getElementById("ai-quiz-custom-pop").remove();
      }
      const div = document.createElement("div");
      div.setAttribute("class", "ai-quiz-popup-container");

      div.setAttribute("id", "ai-quiz-pop");

      div.innerHTML = `
		<div class="ai-quiz-popup flex-column align-items-center" id="ai-quiz-pop-cont">
		<h5 class="mt-4 mb-5">Share your result!</h5>
			<div class="ai-quiz-social__links my-4">
			<button id="ai-quiz-share-twitter" class="ai-quiz-social__link" data-attr="twitter" data-link="https://www.twitter.com/share">
				<i class="fa-brands fa-square-twitter"></i>
			</button>
			<button id="ai-quiz-share-telegram" class="ai-quiz-social__link" data-attr="telegram" data-link="https://www.telegram.com/share">
				<i class="fa-brands fa-telegram"></i>
			</button>
			<button id="ai-quiz-share-whatsapp" class="ai-quiz-social__link" data-attr="whatsapp" data-link="https://www.whatsapp.com/share">
				<i class="fa-brands fa-square-whatsapp"></i>
			</button>
			<button id="ai-quiz-share-clipboard" class="ai-quiz-social__link" data-attr="linkedin" data-link="https://www.linkedin.com/share">
				<i class="fa-regular fa-paste"></i>
			</button>
			</div>
		</div>`;
		//Creamos el elemento donde va a pegarse el popup
		const div_pop = document.createElement("div");
		div_pop.setAttribute("id", "ai-quiz-custom-pop");
		document.body.appendChild(div_pop);

		//Añadimos el popup
		document.getElementById("ai-quiz-custom-pop").appendChild(div);

		document.getElementById("ai-quiz-pop").onclick = function (e) {
			container = document.getElementById("ai-quiz-pop-cont");

			if (container && container !== e.target && !container.contains(e.target)) {
			document.getElementById("ai-quiz-pop").remove();
			}
		};
		var text = "Quiz: AI Quiz Maker Example Quiz\n\n";
		var sc = ""
		var count = 0;
		var scor = 0;
		questions_arr.forEach(q => {
			if('result' in q){
				if(q.result.score){
					sc+="🟩 ";
					scor++;
				}else{
					sc+="🟥 ";
				}
			}else{
				sc+="⬜ ";
			}
			count++;
		});
		text += "Score: " + scor + "/" + count + "\n\n";
		text += sc;
		var url = "https://wordpress.org/plugins/ai-quiz/";
		var encodedText = encodeURIComponent(text);
		var encodedUrl = encodeURIComponent(url);
		//Añadir funciones

		document.getElementById("ai-quiz-share-twitter").addEventListener("click", function () {
			window.open(`https://twitter.com/intent/tweet?text=${encodedText}&url=${encodedUrl}`);
		});
		document.getElementById("ai-quiz-share-telegram").addEventListener("click", function () {
			window.open(`https://telegram.me/share/url?url=${encodedUrl}&text=${encodedText}`);
		});
		document.getElementById("ai-quiz-share-whatsapp").addEventListener("click", function () {
			window.open(`https://web.whatsapp.com/send?text=${encodedText}%0A%0A${encodedUrl}`);
		});
		document.getElementById("ai-quiz-share-clipboard").addEventListener("click", function () {
			navigator.clipboard.writeText(`${text}\n\n${url}`).then(function() {
				alert('Link copiado al portapapeles');
			  }, function() {
				alert('Error al copiar el link al portapapeles');
			  });
		});

    });

    function create_test_question(question, index) {
      var texto;
      texto = `
                  <div class="ai-quiz-question-card" id="ai-quiz-question-card" data-id=${question["id"]} data-arr=${index}>
            <div class="d-flex flex-column justify-content-center align-items-center">
              <div class="mb-5 ai-quiz-question-title">${question["question"]}</div>
              <div class="ai-quiz-options-card flex-column justify-content-center align-items-center w-100 text-center">
          `;
      for (var j = 0; j < question["options"].length; j++) {
        var option_class = "";
        if (
          "result" in questions_arr[index] &&
          questions_arr[index].result.user == question["options"][j]["id"]
        ) {
          option_class = "selected";
          if ("score" in questions_arr[index].result) {
            if (questions_arr[index].result.score == "1") {
              option_class += " success";
            } else {
              option_class += " failed";
            }
          }
        } else {
          if (
            "result" in questions_arr[index] &&
            "score" in questions_arr[index].result &&
            questions_arr[index].result.score == "0" &&
            questions_arr[index].result.answer == question["options"][j]["id"]
          ) {
            option_class += " success";
          }
        }
        texto += `
              <div class="ai-quiz-option my-3 ${option_class}" data-id="${
          question["options"][j]["id"]
        }" ${
          "result" in questions_arr[index] &&
          "score" in questions_arr[index].result
            ? ""
            : 'onclick="select_option(this)"'
        }>${question["options"][j]["answer"]}</div>`;
      }
      if (
        "result" in questions_arr[index] &&
        "score" in questions_arr[index].result &&
        questions_arr[index].explanation
      ) {
        texto += `<div class="ai-quiz-know-what">${questions_arr[index].explanation}</div>`;
      }
      texto += `
              </div>
              ${
                "result" in questions_arr[index] &&
                "score" in questions_arr[index].result
                  ? ""
                  : '<button id="ai-quiz-checkbutton" style="display:none;" class="btn btn-success">Check</button>'
              }
            </div>
          </div>
                  `;
      document.getElementById("ai-quiz-question").innerHTML = texto;

      $("#ai-quiz-checkbutton").on("click", function () {
        check_response("test");
      });

    }
    function check_response() {
      var question_id = document
        .querySelector(".ai-quiz-question-card")
        .getAttribute("data-id");
      var index = document
        .querySelector(".ai-quiz-question-card")
        .getAttribute("data-arr");
      var selected_option = document.querySelector(".ai-quiz-option.selected");
      var selected_option_id = selected_option.getAttribute("data-id");
      questions_arr[index].result = {
        user: selected_option_id,
        score: null,
        answer: null,
      };
      questions_arr[index].options.forEach((opt) => {
        if (opt.answer_bin == "1") {
          questions_arr[index].result.answer = opt.id;
        }
        if (selected_option_id == opt.id && opt.answer_bin == "1") {
          questions_arr[index].result.score = 1;
        } else if (selected_option_id == opt.id && opt.answer_bin == "0") {
          questions_arr[index].result.score = 0;
        }
      });

      loadQuestion(index);
      createNavigationButtons(false);
      document.getElementById("ai-quiz-share").style.display = "block";
      document
        .querySelector(`.ai-quiz-question-circle[data-id="${index}"]`)
        .classList.add("active");
    }

    function loadQuestion(index) {
      console.log(questions_arr);
      var prev_btn = document.getElementById("ai-quiz-prev-btn");
      var next_btn = document.getElementById("ai-quiz-next-btn");
      // Elimina los eventos onclick de los botones
      prev_btn.onclick = null;
      next_btn.onclick = null;

      prev_btn.style.display = "none";
      next_btn.style.display = "none";
      var question = questions_arr[index];
      index = parseInt(index);
      create_test_question(question, index);


      //Next prev buttons
      if (index !== 0) {
        prev_btn.style.display = "block";
      }
      if (index !== questions_arr.length - 1) {
        next_btn.style.display = "block";
      }
      // Asigna las nuevas funciones a los botones
      prev_btn.onclick = function () {
        prevnext_btn(index, "prev");
      };

      next_btn.onclick = function () {
        prevnext_btn(index, "next");
      };
    }
    loadQuestion(0);
    createNavigationButtons();

    function prevnext_btn(index, type) {
      if (type == "next") {
        var n = index + 1;
      } else {
        var n = index - 1;
      }
      document
        .querySelector(`.ai-quiz-question-circle[data-id="${index}"]`)
        .classList.remove("active");
      document
        .querySelector(`.ai-quiz-question-circle[data-id="${n}"]`)
        .classList.add("active");
      loadQuestion(n);

      //Colocar los botones en zona visible del scroll
      var container = document.getElementById("ai-quiz-pagination");
      var button = document.querySelector(".ai-quiz-question-circle.active");

      // Si no hay botón activo, no hagas nada
      if (!button) return;

      var containerRect = container.getBoundingClientRect();
      var buttonRect = button.getBoundingClientRect();

      // Verifica si el botón está completamente visible en el contenedor
      var isCompletelyVisible =
        buttonRect.left >= containerRect.left &&
        buttonRect.right <= containerRect.right;

      // Si el botón no está completamente visible, desplázate hasta él
      if (!isCompletelyVisible) {
        container.scrollLeft =
          button.offsetLeft - (containerRect.width / 2 - buttonRect.width / 2);
      }
    }
    function createNavigationButtons(initial = true) {
      const paginationContainer = $("#ai-quiz-pagination");
      paginationContainer.html("");
      for (let i = 0; i < questions_arr.length; i++) {
        var act = initial && i == 0 ? "active" : "";
        var stat;
        if ("result" in questions_arr[i]) {
          if ("score" in questions_arr[i].result) {
            if (questions_arr[i].result.score > 0.5) {
              stat = "success";
            } else {
              stat = "failed";
            }
          } else {
            stat = "answered";
          }
        } else {
          stat = "pending";
        }
        const button = $(
          '<div data-id="' +
            i +
            '" class="ai-quiz-question-circle ' +
            stat +
            " m-3 " +
            act +
            '"><span></span></div>'
        );
        button.click(function () {
          $("#ai-quiz-pagination .ai-quiz-question-circle").removeClass(
            "active"
          );
          $(this).addClass("active");
          currentQuestionIndex = i;
          loadQuestion(currentQuestionIndex);
        });
        paginationContainer.append(button);
      }
      upload_score(questions_arr);
    }
    function upload_score(arr) {
      var n_success = document.querySelectorAll(
        ".ai-quiz-question-circle.success"
      ).length;
      var n_failed = document.querySelectorAll(
        ".ai-quiz-question-circle.failed"
      ).length;
      var sum = n_success + n_failed;
      if (sum != 0) {
        const fr = new FaceRating("#face-rating");
        document.getElementById("success-score").innerHTML = n_success;
        document.getElementById("total-score").innerHTML = sum;
        document.getElementById("face-score").style.display = "flex";
        document.getElementById("face-rating").value = (n_success / sum) * 5;
        fr.update();
      }
    }

	
  });
});

</script>
<?php
		}
	}
}

?>