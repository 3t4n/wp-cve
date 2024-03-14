<?php





require_once(AI_POST_GENERATOR_PLUGIN_DIR . '/functions.php');

require_once(AI_POST_GENERATOR_PLUGIN_DIR . '/inc/insert-body-header.php');

/**

 * Create the administration menus in the left-hand nav and load the JavaScript conditionally only on that page

 */

if (!function_exists('ai_post_generator_add_my_admin_menus')) {

	function ai_post_generator_add_my_admin_menus()
	{

		$my_page =  add_menu_page('Create post', 'AutoWriter', 'manage_options', 'ai_post_generator', 'ai_post_generator_add_integration_code_body', 'data:image/svg+xml;base64,' . base64_encode(file_get_contents(AI_POST_GENERATOR_PLUGIN_DIR . '/images/icon.svg')));

		add_submenu_page(
			'edit.php',
			'AutoWriter',
			'AI Post' . '<span class="update-plugins"><span class="plugin-count">1</span></span>',
			'manage_options',
			'ai_post_generator',
			'ai_post_generator_add_integration_code_body'
		);

		add_submenu_page(
			'ai_post_generator',
			'AutoWriter',
			'Dashboard',
			'manage_options',
			'ai_post_generator',
			'ai_post_generator_add_integration_code_body'
		);
		


		$my_page4 = add_submenu_page(
			'ai_post_generator',
			'Training model',
			'Training model',
			'manage_options',
			'autowriter_training_model',
			'ai_post_generator_add_integration_code_body_training_model'
		);
		$my_page3 = add_submenu_page(
			'ai_post_generator',
			'Academy',
			'Academy',
			'manage_options',
			'autowriter_academy',
			'ai_post_generator_add_integration_code_body_academy'
		);

		$my_page2 = add_submenu_page(
			'ai_post_generator',
			'Upgrade Plan',
			'Upgrade Plan',
			'manage_options',
			'autowriter_upgrade_plan',
			'ai_post_generator_add_integration_code_body_buy_tokens'
		);
		$my_page5 = add_submenu_page(
			'ai_post_generator',
			'Settings',
			'Settings',
			'manage_options',
			'autowriter_settings',
			'ai_post_generator_add_integration_code_body_settings'
		);




		// Load the JS conditionally

		//add_action( 'wp_enqueue_scripts', 'ai_post_generator_enqueue_css' );



		add_action('load-' . $my_page, 'load_admin_ai_post_generator_body_js');
		add_action('load-' . $my_page2, 'load_admin_ai_post_generator_tokens_js');
		add_action('load-' . $my_page4, 'load_admin_ai_post_generator_training_js');
		add_action('load-' . $my_page3, 'load_admin_ai_post_generator_academy_js');
		add_action('load-' . $my_page5, 'load_admin_ai_post_generator_settings_js');
	}




	add_action('wp_enqueue_scripts', 'ai_post_generator_enqueue_css', 1);

	//Review
	add_action('admin_enqueue_scripts', 'ai_post_generator_enqueue_css_review', 1);



	add_action('admin_menu', 'ai_post_generator_add_my_admin_menus'); // hook so we can add menus to our admin left-hand menu

}


if (!function_exists('load_admin_ai_post_generator_body_js')) {
	// This function is only called when our plugin's page loads!

	function load_admin_ai_post_generator_body_js()
	{

		// Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it

		add_action('admin_enqueue_scripts', 'ai_post_generator_body_enqueue_js');
	}
}

if (!function_exists('load_admin_ai_post_generator_tokens_js')) {
	// This function is only called when our plugin's page loads!

	function load_admin_ai_post_generator_tokens_js()
	{

		// Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it

		add_action('admin_enqueue_scripts', 'ai_post_generator_tokens_enqueue_js');
	}
}

if (!function_exists('load_admin_ai_post_generator_training_js')) {
	// This function is only called when our plugin's page loads!

	function load_admin_ai_post_generator_training_js()
	{

		// Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it

		add_action('admin_enqueue_scripts', 'ai_post_generator_training_enqueue_js');
	}
}
if (!function_exists('load_admin_ai_post_generator_settings_js')) {
	// This function is only called when our plugin's page loads!

	function load_admin_ai_post_generator_settings_js()
	{

		// Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it

		add_action('admin_enqueue_scripts', 'ai_post_generator_settings_enqueue_js');
	}
}
if (!function_exists('load_admin_ai_post_generator_academy_js')) {
	// This function is only called when our plugin's page loads!

	function load_admin_ai_post_generator_academy_js()
	{

		// Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it

		add_action('admin_enqueue_scripts', 'ai_post_generator_academy_enqueue_js');
	}
}
if (!function_exists('ai_post_generator_return_json')) {

	function ai_post_generator_return_json($response = array())
	{

		header('Content-Type: application/json');

		exit(json_encode($response));
	}
}




if (!function_exists('ai_post_generator_stripAccents')) {

	function ai_post_generator_stripAccents($str)
	{

		return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
	}
}
if (!function_exists('ai_post_generator_shortcode')) {

	function ai_post_generator_shortcode()
	{



		return get_ai_post_generator_toc(ai_post_generator_auto_id_headings(get_the_content()));
	}




	add_filter('the_content', 'ai_post_generator_auto_id_headings');



	add_shortcode('ai_post_generator_toc', 'ai_post_generator_shortcode');
}




if (!function_exists('ai_post_generator_get_Posts')) {

	function ai_post_generator_get_Posts()
	{
		$args = array(

			'post_type' => 'post',
			'post_status' => 'any',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key'     => '_autowriter_post',
					'value'   => '1',
				)
			)
		);
		$posts = get_posts($args);
		$all_posts = array();
		foreach ($posts as $post){
			$each_post = new stdClass();
			$each_post->id = $post->ID;
			$each_post->status = $post->post_status;
			$each_post->content = $post->post_content;
			$each_post->title = $post->post_title;
			$each_post->date = $post->post_date;
			$each_post->url = $post->guid;
			$all_posts[]=$each_post;
		}


		ai_post_generator_return_json(array('exito' => true, 'array' => $all_posts));
	}


	add_action('wp_ajax_ai_post_generator_get_Posts', 'ai_post_generator_get_Posts');
}

if (!function_exists('ai_post_generator_data_Publish')) {

	function ai_post_generator_data_Publish()
	{

		if (!isset($_POST['text']) || !isset($_POST['title'])) {

			ai_post_generator_return_json(array('exito' => false, 'error' => 'Mensaje vacío'));
		}


		if ($_POST['type'] == "publish") {

			$type = "publish";
		} elseif ($_POST['type'] == "draft") {

			$type = "draft";
		} else {

			$type = "draft";
		}


		$type = sanitize_text_field($type);

		$title = sanitize_text_field(wp_strip_all_tags($_POST['title']));

		$url = trim(preg_replace('/[^a-z0-9-]+/', '-', ai_post_generator_stripAccents(strtolower($title))), '-');
		
		// time config
		$minutes_to_add = 0;
		$DateTime = new DateTime(null, new DateTimeZone('Europe/Madrid'));
		$DateTime->add(new DateInterval('PT' . $minutes_to_add . 'M'));
		$now = $DateTime->format('Y-m-d H:i:s');


		$my_post = array(

			'post_title' => $title,

			'post_url' => $url,

			'post_content' => wp_kses_post('[ai_post_generator_toc]' . $_POST['text']),

			'post_status' => $type,

			'post_author' => get_current_user_id(),

			'post_date' => $now,

		);



		$id = wp_insert_post($my_post);

		add_post_meta($id, '_autowriter_post', '1', true);

		if ($_POST['im']) {

			$image = sanitize_text_field(wp_strip_all_tags($_POST['im']));

			$image_id = ai_post_generator_download_img($image, "Image " . $title, $url . "-image");







			if ($image_id) {







				add_post_meta($id, '_thumbnail_id', $image_id, true);







				add_post_meta($image_id, '_wp_attachment_image_alt', $title, true);
			}
		}
		$post   = get_post($id);


		ai_post_generator_return_json(array('exito' => true, 'content' => $post->post_content, 'url' => $post->guid, 'id' => $id));
	}


	add_action('wp_ajax_ai_post_generator_data_Publish', 'ai_post_generator_data_Publish');
}

if (!function_exists('ai_post_generator_data_Preview')) {

	function ai_post_generator_data_Preview()
	{

		if (!isset($_POST['text']) || !isset($_POST['id'])) {

			ai_post_generator_return_json(array('exito' => false, 'error' => 'Mensaje vacío'));
		}
		// Después (Validar y Sanitizar):
		$id = ( isset( $_POST['id'] ) && is_numeric( $_POST['id'] ) ) ? intval( $_POST['id'] ) : '';


		$data = array('ID' => $id, 'post_content' => wp_kses_post($_POST['text']));
		wp_update_post($data);



		ai_post_generator_return_json(array('exito' => true));
	}


	add_action('wp_ajax_ai_post_generator_data_Preview', 'ai_post_generator_data_Preview');
}

if (!function_exists('ai_post_generator_saveas_Publish')) {

	function ai_post_generator_saveas_Publish()
	{

		if (!isset($_POST['id'])) {

			ai_post_generator_return_json(array('exito' => false, 'error' => 'Mensaje vacío'));
		}
		// time config
				// time config
		$minutes_to_add = 0;
		$DateTime = new DateTime(null, new DateTimeZone('Europe/Madrid'));
		$DateTime->add(new DateInterval('PT' . $minutes_to_add . 'M'));
		$now = $DateTime->format('Y-m-d H:i:s');
		
		// Después (Validar y Sanitizar):
		$id = ( isset( $_POST['id'] ) && is_numeric( $_POST['id'] ) ) ? intval( $_POST['id'] ) : '';
		$data = array('ID' => $id, 'post_status' => 'publish', 'post_content' => wp_kses_post($_POST['text']) ,'post_date' => $now);



		wp_update_post($data);

		ai_post_generator_return_json(array('exito' => true, 'id' => $id));
	}


	add_action('wp_ajax_ai_post_generator_saveas_Publish', 'ai_post_generator_saveas_Publish');
}

if (!function_exists('ai_post_generator_delete_Post')) {

	function ai_post_generator_delete_Post()
	{

		if (!isset($_POST['id'])) {

			ai_post_generator_return_json(array('exito' => false, 'error' => 'Mensaje vacío'));
		}
		// Después (Validar y Sanitizar):
		$id = ( isset( $_POST['id'] ) && is_numeric( $_POST['id'] ) ) ? intval( $_POST['id'] ) : '';
		wp_delete_post($_POST['id']);

		ai_post_generator_return_json(array('exito' => true, 'id' => $id));
	}


	add_action('wp_ajax_ai_post_generator_delete_Post', 'ai_post_generator_delete_Post');
}


if (!function_exists('ai_post_generator_enqueue_css_review')) {

	function ai_post_generator_enqueue_css_review()
	{

		$my_css_ver = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/my-styles-review.css'));

		$my_js_ver = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/review.js'));

		wp_enqueue_style(

			'my-styles-ai-review',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/my-styles-review.css",

			null,

			$my_css_ver

		);
		wp_enqueue_script(

			'my-js-ai-review',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/review.js",

			null,

			$my_js_ver

		);
	}
}

if (!function_exists('ai_post_generator_enqueue_css')) {

	function ai_post_generator_enqueue_css()
	{

		$my_css_ver = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/my-styles-toc.css'));



		$my_js_ver = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/table-of-content.js'));

		wp_enqueue_style(

			'my-styles-toc',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/my-styles-toc.css",

			null,

			$my_css_ver

		);		// Enqueue the script

		wp_enqueue_script(

			'my-functions1',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/table-of-content.js",

			null,

			$my_js_ver,

			true

		);
	}
}

if (!function_exists('ai_post_generator_body_enqueue_js')) {

	function ai_post_generator_body_enqueue_js()
	{



		// Create version codes

		$my_css_ver = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/my-styles.css'));

		$my_css_bootstrap = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/bootstrap.min.css'));
		
		$my_js_ver  = date('ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/main.js'));

		$my_js_header  = date('ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/header.js'));

		$my_js_bootstrap  = date('ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/bootstrap.bundle.min.js'));

		$my_js_circle  = date('ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/circle-progress.min.js'));



		// Enqueue the stylesheet
		wp_enqueue_style(

			'my-styles',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/my-styles.css",

			null,

			$my_css_ver

		);


		wp_enqueue_style(

			'my-styles2',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/bootstrap.min.css",

			null,

			$my_css_bootstrap

		);

		wp_enqueue_style(

			'my-styles3',

			"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css",

			null,

			null

		);







		wp_enqueue_script(

			'my-functions2',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/bootstrap.bundle.min.js",

			null,

			$my_js_bootstrap,

			true

		);

		wp_enqueue_script(

			'my-functions-header',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/header.js",

			null,

			$my_js_header,

			true

		);

		wp_enqueue_script(

			'my-functions3',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/circle-progress.min.js",

			null,

			$my_js_circle,

			true

		);


		wp_enqueue_script(

			'my-functions4',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/main.js",

			null,

			$my_js_ver,

			true

		);

		//DATATABLE

		$my_css_datatable = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/datatable.min.css'));
		wp_enqueue_style(

			'my-styles-datatable',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/datatable.min.css",

			null,

			$my_css_datatable

		);
		
		$my_js_datatable  = date('ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/datatable.min.js'));

		wp_enqueue_script(

			'my-functions-datatable',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/datatable.min.js",

			null,

			$my_js_datatable,

			true

		);

	}
}

if (!function_exists('ai_post_generator_tokens_enqueue_js')) {

	function ai_post_generator_tokens_enqueue_js()
	{



		// Create version codes

		$my_css_ver = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/my-styles.css'));
		$my_css_checkout = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/checkout.css'));

		$my_js_header = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/header.js'));

		$my_js_checkout = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/checkout.js'));
		
		$my_js_checkout2 = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/single-posts-checkout.js'));

		$my_css_bootstrap = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/bootstrap.min.css'));

		$my_js_bootstrap  = date('ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/bootstrap.bundle.min.js'));




		// Enqueue the stylesheet
		wp_enqueue_style(

			'my-styles',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/my-styles.css",

			null,

			$my_css_ver

		);


		wp_enqueue_style(

			'my-styles2',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/bootstrap.min.css",

			null,

			$my_css_bootstrap

		);

		wp_enqueue_style(

			'my-styles-checkout',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/checkout.css",

			null,

			$my_css_checkout

		);

		wp_enqueue_style(

			'my-styles3',

			"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css",

			null,

			null

		);








		wp_enqueue_script(

			'my-functions2',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/bootstrap.bundle.min.js",

			null,

			$my_js_bootstrap,

			true

		);


		wp_enqueue_script(

			'my-functions-header',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/header.js",

			null,

			$my_js_header,

			true

		);

		wp_enqueue_script(

			'my-functions-checkout',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/checkout.js",

			null,

			$my_js_checkout,

			true

		);
		// Enqueue the script

		wp_enqueue_script(

			'my-strype',

			"https://js.stripe.com/v3/",

			null,

			null,

			true

		);

		wp_enqueue_script(

			'my-functions-checkout2',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/single-posts-checkout.js",

			null,

			$my_js_checkout2,

			true

		);

	}
}


if (!function_exists('ai_post_generator_academy_enqueue_js')) {

	function ai_post_generator_academy_enqueue_js()
	{



		// Create version codes

		$my_css_ver = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/my-styles.css'));

		$my_css_checkout = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/checkout.css'));

		$my_js_header = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/header.js'));

		$my_css_bootstrap = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/bootstrap.min.css'));

		$my_js_bootstrap  = date('ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/bootstrap.bundle.min.js'));




		// Enqueue the stylesheet
		wp_enqueue_style(

			'my-styles',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/my-styles.css",

			null,

			$my_css_ver

		);

		wp_enqueue_style(

			'my-styles1',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/checkout.css",

			null,

			$my_css_checkout

		);

		wp_enqueue_style(

			'my-styles2',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/bootstrap.min.css",

			null,

			$my_css_bootstrap

		);

		wp_enqueue_style(

			'my-styles3',

			"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css",

			null,

			null

		);






		wp_enqueue_script(

			'my-functions2',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/bootstrap.bundle.min.js",

			null,

			$my_js_bootstrap,

			true

		);


		wp_enqueue_script(

			'my-functions-header',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/header.js",

			null,

			$my_js_header,

			true

		);

	}
}

if (!function_exists('ai_post_generator_training_enqueue_js')) {

	function ai_post_generator_training_enqueue_js()
	{



		// Create version codes

		$my_css_ver = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/my-styles.css'));


		$my_css_bootstrap = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/bootstrap.min.css'));

		$my_js_ver  = date('ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/training-model.js'));

		$my_js_header  = date('ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/header.js'));

		$my_js_bootstrap  = date('ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/bootstrap.bundle.min.js'));



		// Enqueue the stylesheet
		wp_enqueue_style(

			'my-styles',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/my-styles.css",

			null,

			$my_css_ver

		);


		wp_enqueue_style(

			'my-styles2',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/bootstrap.min.css",

			null,

			$my_css_bootstrap

		);

		wp_enqueue_style(

			'my-styles3',

			"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css",

			null,

			null

		);





		wp_enqueue_script(

			'my-functions2',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/bootstrap.bundle.min.js",

			null,

			$my_js_bootstrap,

			true

		);

		wp_enqueue_script(

			'my-functions-header',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/header.js",

			null,

			$my_js_header,

			true

		);



		wp_enqueue_script(

			'my-functions4',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/training-model.js",

			null,

			$my_js_ver,

			true

		);

		//DATATABLE

		$my_css_datatable = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/datatable.min.css'));
		wp_enqueue_style(

			'my-styles-datatable',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/datatable.min.css",

			null,

			$my_css_datatable

		);
		
		$my_js_datatable  = date('ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/datatable.min.js'));

		wp_enqueue_script(

			'my-functions-datatable',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/datatable.min.js",

			null,

			$my_js_datatable,

			true

		);
	}
}


if (!function_exists('ai_post_generator_settings_enqueue_js')) {

	function ai_post_generator_settings_enqueue_js()
	{



		// Create version codes

		$my_css_ver = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/my-styles.css'));


		$my_css_bootstrap = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/bootstrap.min.css'));

		$my_js_ver  = date('ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/settings.js'));

		$my_js_header  = date('ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/header.js'));

		$my_js_bootstrap  = date('ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/bootstrap.bundle.min.js'));



		// Enqueue the stylesheet
		wp_enqueue_style(

			'my-styles',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/my-styles.css",

			null,

			$my_css_ver

		);


		wp_enqueue_style(

			'my-styles2',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/bootstrap.min.css",

			null,

			$my_css_bootstrap

		);

		wp_enqueue_style(

			'my-styles3',

			"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css",

			null,

			null

		);




		wp_enqueue_script(

			'my-functions2',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/bootstrap.bundle.min.js",

			null,

			$my_js_bootstrap,

			true

		);

		wp_enqueue_script(

			'my-functions-header',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/header.js",

			null,

			$my_js_header,

			true

		);



		wp_enqueue_script(

			'my-functions4',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/settings.js",

			null,

			$my_js_ver,

			true

		);

		//DATATABLE

		$my_css_datatable = date('Ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'css/datatable.min.css'));
		wp_enqueue_style(

			'my-styles-datatable',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "css/datatable.min.css",

			null,

			$my_css_datatable

		);
		
		$my_js_datatable  = date('ymd-Gis', filemtime(AI_POST_GENERATOR_PLUGIN_DIR . 'js/datatable.min.js'));

		wp_enqueue_script(

			'my-functions-datatable',

			trailingslashit(AI_POST_GENERATOR_PLUGIN_URL) . "js/datatable.min.js",

			null,

			$my_js_datatable,

			true

		);
	}
}

//Review
add_action('admin_notices', 'ai_post_generator_solicitud_review');
function ai_post_generator_solicitud_review() {
    // Solo muestra la notificación a los usuarios que pueden instalar plugins.
    if (!current_user_can('install_plugins')) {
        return;
    }

    // Si el usuario ya ha descartado la notificación, no la muestres de nuevo.
    if (get_option('ai_post_generator_review_dismissed')) {
        return;
    }

    // Si el usuario ha seleccionado "Maybe Later", verifica el transitorio.
    if (get_transient('ai_post_generator_request_review')) {
        return;
    }

    // Enlace para dejar una valoración.
    $url = 'https://wordpress.org/support/plugin/ai-post-generator/reviews/#postform';
    // Enlace para soporte
    $url_support = 'https://autowriter.tech/contact/';

    // Coloca aquí el HTML de tu notificación.
    echo '<div id="ai_post_generator-review-notice" class="notice notice-info ai_post_generator-notice" data-purpose="review">
        <div class="ai_post_generator-notice-thumbnail">
            <img src="' . AI_POST_GENERATOR_PLUGIN_URL . '/images/icon-256x256.gif" alt="">
        </div>
        <div class="ai_post_generator-notice-text">
            <div class="ai_post_generator-notice-header">
                <h3>LIFE\'S <strong>A BEACH!</strong></h3>
                <a href="#" class="close-btn notice-dismiss-temporarily ai-post-generator-notice-dismiss-temporarily">×</a>
            </div>
            <p>That phrase was just a cheeky wave to grab your attention. <span class="dashicons dashicons-smiley smile-icon"></span> </p><p>Hey, we hope you\'re having a blast with our <strong>AI Post Generator</strong> plugin. If it\'s not too much to ask, could you spare a minute to write us a review?</p>
            <p class="extra-pad"><strong>Why, you ask?</strong> <br>
			Well, each review powers up our developers like a can of energy drink. It fuels them to bring you zippier updates, cooler features, and fewer bugs. <span class="dashicons dashicons-smiley smile-icon"></span><br>
			Plus, it keeps our <strong>free support</strong> running, kind of like having your own team of AI nerds on call. And all this in return for your feedback. </br> Sweet deal, right?</p>
            <div class="ai_post_generator-notice-links">
                <ul class="ai_post_generator-notice-ul">
                    <li><a class="button button-primary" href="' . $url . '" target="_blank"><span class="dashicons dashicons-external"></span>Sure, I\'d love to!</a></li>
                    <li><a href="#" class="button button-secondary notice-dismiss-permanently ai-post-generator-notice-dismiss-permanently"><span class="dashicons dashicons-smiley"></span>I already did!</a></li>
                    <li><a href="#" class="button button-secondary notice-dismiss-temporarily ai-post-generator-notice-dismiss-temporarily"><span class="dashicons dashicons-dismiss"></span>Maybe later</a></li>
                                        <li><a href="' . $url_support . '" class="button button-secondary notice-have-query" target="_blank"><span class="dashicons dashicons-testimonial"></span>I have a query</a></li>
                </ul>
                <a href="#" class="notice-dismiss-permanently ai-post-generator-notice-dismiss-permanently">Never show again</a>
            </div>
        </div>
    </div>';
}
// Si el usuario ha optado por descartar la notificación, guarda esta preferencia.
function ai_post_generator_dismiss_review() {
    if (isset($_POST['ai_post_generator_review_dismiss']) && $_POST['ai_post_generator_review_dismiss'] == 1) {
        update_option('ai_post_generator_review_dismissed', '1');
        wp_die();
    } elseif (isset($_POST['ai_post_generator_review_later']) && $_POST['ai_post_generator_review_later'] == 1) {
        set_transient('ai_post_generator_request_review', 'yes', 10 * HOUR_IN_SECONDS);
        wp_die();
    }
    // Los siguientes comentarios se pueden eliminar si ya no son necesarios.
    /*
    delete_option('ai_post_generator_review_dismissed');
    delete_transient('ai_post_generator_request_review');
    */
    
}
add_action('wp_ajax_ai_post_generator_dismiss_review', 'ai_post_generator_dismiss_review');