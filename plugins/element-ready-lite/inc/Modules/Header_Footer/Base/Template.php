<?php

namespace Element_Ready\Modules\Header_Footer\Base;

class Template
{

	public function register()
	{

		if (!file_exists(WP_PLUGIN_DIR . '/elementor/elementor.php')) {
			return;
		}

		if (!element_ready_get_modules_option('header_footer_builder')) {
			return;
		}

		add_action('wp', array($this, 'hooks'));
		add_action('wp_head', array($this, 'wp_head'));
		add_action('element_ready_header_builder', array($this, 'header_template'), 10);

		// Get Footer Template
		add_action('element_ready_footer_builder', array($this, 'footer_template'), 10);
		add_filter('element_ready_header_page_override', array($this, 'header_page_override'), 10);
		add_filter('element_ready_footer_page_override', array($this, 'footer_page_override'), 10);
	}

	public function header_page_override()
	{
		global $post;
		$isElementor_library = isset($_GET['elementor_library']) ? true : false;
		$pId = get_the_ID() ? get_the_ID() : (is_object($post) ? $post->ID : null);
		$id                    = $isElementor_library ? $pId : '--';
		$page_id               = $isElementor_library ? $pId : '--';

		// page override
		if (is_page() || is_singular('docs')) {

			$page_id = get_post_meta(get_the_ID(), 'element_ready_builder_header_layout_style', true);
		}

		if (element_ready_elementor_page_meta_settings('element_ready_header_template') > 0) {

			$page_id = element_ready_elementor_page_meta_settings('element_ready_header_template');
		}

		if (is_numeric($page_id)) {
			return $page_id;
		} else {

			$builder_header_layout = element_ready_get_components_option('header_template');

			if (!$builder_header_layout) {

				return false;
			}

			$id = element_ready_get_hf_option('header_template');


			if (is_singular('docs')) {

				if (element_ready_get_hf_option('wedocs_header_template')) {
					$id = element_ready_get_hf_option('wedocs_header_template');
				}
			}


			if (!is_numeric($id) || $id == -1) {
				return false;
			}
		}

		return $id;
	}

	public function footer_page_override()
	{
		global $post;
		$isElementor_library = isset($_GET['elementor_library']) ? true : false;
		$pId = get_the_ID() ? get_the_ID() : $post->ID;
		$id                    = $isElementor_library ? $pId : '--';
		$page_id               = $isElementor_library ? $pId : '--';

		// page override
		if (is_page() || is_singular('docs')) {

			$page_id = get_post_meta(get_the_ID(), 'element_ready_builder_footer_layout_style', true);
		}

		//elementor editor
		if (element_ready_elementor_page_meta_settings('element_ready_footer_template') > 0) {

			$page_id = element_ready_elementor_page_meta_settings('element_ready_footer_template');
		}

		if (is_numeric($page_id)) {

			return $page_id;
		} else {

			$builder_layout = element_ready_get_components_option('footer_template');

			if (!$builder_layout) {

				return false;
			}

			$id = element_ready_get_hf_option('footer_template');

			if (is_singular('docs') && is_integer(element_ready_get_hf_option('wedocs_footer_template'))) {
				$id = element_ready_get_hf_option('wedocs_footer_template');
			}

			if (!is_numeric($id) || $id == -1) {
				return false;
			}
		}

		return $id;
	}

	public function hooks()
	{

		if (element_ready_get_modules_option('header_footer_builder') && element_ready_get_components_option('header_template')) {
			add_action('get_header', array($this, 'render_header'), 100);
		}

		if (element_ready_get_modules_option('header_footer_builder') && element_ready_get_components_option('footer_template')) {
			add_action('get_footer', array($this, 'render_footer'));
		}
	}

	public function wp_head()
	{
		wp_reset_postdata();
	}

	public function header_template()
	{

		if (!element_ready_get_modules_option('header_footer_builder')) {
			return;
		}

		if (!element_ready_get_components_option('header_template')) {
			return;
		}

		$path   = ELEMENT_READY_DIR_PATH . 'inc/Modules/Header_Footer/Templates/content/content-header.php';
		$header = $this->display_template();
		$this->render($header, $path);
	}

	public function footer_template()
	{

		if (!element_ready_get_modules_option('header_footer_builder')) {
			return;
		}

		$path   = ELEMENT_READY_DIR_PATH . 'inc/Modules/Header_Footer/Templates/content/content-footer.php';
		$footer = $this->display_template('all', 'footer');
		$this->render($footer, $path);
	}

	public function render($header, $path)
	{

		if ($header->have_posts()) {
			while ($header->have_posts()) {
				$header->the_post();
				load_template($path);
			}
			wp_reset_postdata();
		}
	}

	public function render_header()
	{

		$header_id = $this->template_header_id();

		if ($header_id) {
			require ELEMENT_READY_DIR_PATH . 'inc/Modules/Header_Footer/Templates/default/header.php';
			$templates   = array();
			$templates[] = 'header.php';
			remove_all_actions('wp_head');
			ob_start();
			locate_template($templates, true);
			ob_get_clean();
		}
	}

	public function render_footer()
	{

		$footer_id = $this->template_footer_id();

		if ($footer_id) {
			require ELEMENT_READY_DIR_PATH . 'inc/Modules/Header_Footer/Templates/default/footer.php';
			$templates   = array();
			$templates[] = 'footer.php';
			remove_all_actions('wp_footer');
			ob_start();
			locate_template($templates, true);
			ob_get_clean();
		}
	}

	public function template_header_id()
	{

		$header = $this->display_template();
		if (!$header) {
			return false;
		}
		while ($header->have_posts()) {
			$header->the_post();
			$id = get_the_ID();
		}
		wp_reset_postdata();
		return $id;
	}

	public function template_footer_id()
	{

		$footer = $this->display_template($page_type = 'all', $type = 'footer');

		if (!$footer) {
			return false;
		}

		while ($footer->have_posts()) {
			$footer->the_post();
			$id = get_the_ID();
		}

		wp_reset_postdata();

		return $id;
	}



	public function display_template($page_type = 'all', $type = 'header')
	{

		if (empty($page_type)) {
			return false;
		}

		$args = [
			'post_type' => 'nmo'
		];

		$override = false;
		if ($type == 'header') {
			$override = apply_filters("element_ready_header_page_override", false);
			if ($override) {
				$args = array(
					'p'         => $override,
					'post_type' => 'element-ready-hf-tpl'
				);
			}
		}

		if ($type == 'footer') {
			$override = apply_filters("element_ready_footer_page_override", false);
			if ($override) {
				$args = array(
					'p'         => $override,
					'post_type' => 'element-ready-hf-tpl'
				);
			}
		}
		$header = new \WP_Query($args);
		if ($header->have_posts()) {
			return $header;
		} else {
			return false;
		}
	}
}
