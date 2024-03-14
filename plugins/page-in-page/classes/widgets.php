<?php
/**
 * Widget: Page In Page
 * Descr.: Insert a page in another specifying title and content templates
 * 
 * Example page template
 * <code>
 * <div class="my-wp-page">
 *	<h3 class="my-wp-page-title"><a href="${page_link}">{$page_title}</a></h3>
 *	<div class="my-wp-page-content">
 *		<div class="my-wp-page-image"><img src="${page_image}" /></div>
 *		<div class="my-wp-page-text">${page_content}</div>
 *	</div>
 * </div>
 * </code>
 *
 **/

class TWL_Page_IN_Page_Widget extends WP_Widget {
	
	private $explain = "Insert the contents of a page in another page";

	private $show_page_title_field_name;
	private $show_page_title_field_id;
	
	private $show_page_title_link_field_name;
	private $show_page_title_link_field_id;

	private $show_page_content_field_name;
	private $show_page_content_field_id;

	private $show_page_image_field_name;
	private $show_page_image_field_id;

	private $show_page_image_link_field_name;
	private $show_page_image_link_field_id;

	private $page_field_name;
	private $page_field_id;

	private $title_field_name;
	private $title_field_id;

	private $output_template_id;
	private $output_template_name;

	private $example_link = 'http://cyriltata.blogspot.com/2013/11/wordpress-plugin-page-in-page.html';

	public function __construct() {
		parent::__construct(
			'twl_page_in_page_widget',
			__('Page In Page', TWL_PIP_TEXT_DOMAIN),
			array('description' => __($this->explain, TWL_PIP_TEXT_DOMAIN))
		);
	}

	public function widget($args, $instance) {
		// outputs the content of the widget
		$config = $args + $instance;
		$config['is_widget'] = true;
		$pager = TWL_Page_IN_Page_Page::get_instance();
		$pager->configure($config)->display($config['page']);
	}

 	public function form($instance) {
		// outputs the options form on admin
		$this->field_names();
		$instance += $this->defaults();

		$show_title_checked = !empty($instance['show_page_title']) ? 'checked="checked"' : '';
		$show_title_link_checked = !empty($instance['show_title_as_link']) ? 'checked="checked"' : '';
		$show_content_checked = !empty($instance['show_page_content']) ? 'checked="checked"' : '';
		$show_image_checked = !empty($instance['show_featured_image']) ? 'checked="checked"' : '';
		$show_image_link_checked = !empty($instance['show_featured_image_as_link']) ? 'checked="checked"' : '';
		$title  = !empty($instance['title']) ? $instance['title'] : '';
		$pageID = !empty($instance['page'])  ? $instance['page']  : '';
		$output_template = !empty($instance['output_template'])  ? $instance['output_template']  : '';?>

		<p>
			<label for="<?php echo $this->title_field_id; ?>"><?php _e('Title: ', TWL_PIP_TEXT_DOMAIN); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->title_field_id; ?>" name="<?php echo $this->title_field_name; ?>" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->page_field_id ?>"><?php _e('Select Page: ', TWL_PIP_TEXT_DOMAIN); ?></label>
			<select id="<?php echo $this->page_field_id ?>" name="<?php echo $this->page_field_name ?>" class="widefat">
				<option value=""><?php _e('Select page..', TWL_PIP_TEXT_DOMAIN); ?></option>
				<?php
					$pages = get_pages(); 
					foreach ($pages as $page) {
						$selected = ($pageID == $page->ID) ? 'selected="selected"' : '';
						echo '<option value="' . $page->ID . '" '.$selected.'>' . $page->post_title . '</option>';
					}
				?>
			</select>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->show_page_title_field_id; ?>" name="<?php echo $this->show_page_title_field_name; ?>" <?php echo $show_title_checked; ?> />
			<label for="<?php echo $this->show_page_title_field_id; ?>"><?php _e('Show Page Title', TWL_PIP_TEXT_DOMAIN); ?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->show_page_title_link_field_id; ?>" name="<?php echo $this->show_page_title_link_field_name; ?>" <?php echo $show_title_link_checked; ?> />
			<label for="<?php echo $this->show_page_title_link_field_id; ?>"><?php _e('Show Title as link', TWL_PIP_TEXT_DOMAIN); ?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->show_page_content_field_id; ?>" name="<?php echo $this->show_page_content_field_name; ?>" <?php echo $show_content_checked; ?> />
			<label for="<?php echo $this->show_page_content_field_id; ?>"><?php _e('Show Page Content', TWL_PIP_TEXT_DOMAIN); ?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->show_page_image_field_id; ?>" name="<?php echo $this->show_page_image_field_name; ?>" <?php echo $show_image_checked; ?> />
			<label for="<?php echo $this->show_page_image_field_id; ?>"><?php _e('Show Featured Image', TWL_PIP_TEXT_DOMAIN); ?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->show_page_image_link_field_id; ?>" name="<?php echo $this->show_page_image_link_field_name; ?>" <?php echo $show_image_link_checked; ?> />
			<label for="<?php echo $this->show_page_image_link_field_id; ?>"><?php _e('Show Featured Image as link', TWL_PIP_TEXT_DOMAIN); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->output_template_id; ?>"><?php _e('Output Template: ', TWL_PIP_TEXT_DOMAIN); ?></label>
			<textarea class="widefat" id="<?php echo $this->output_template_id; ?>" name="<?php echo $this->output_template_name; ?>"><?php echo esc_attr($output_template); ?></textarea>
			<br />
			<div style="font-size: 11px;">
				slugs to use in your HTML template <b>${page_title}</b>, <b>${page_content}</b>, <b>${page_link}</b>, <b>${page_image}</b>.
				<a href="<?php echo $this->example_link; ?>" target="_blank">see example</a>. If you specify a template checked settings are ignored so include necessary slugs.
			</div>
		</p><?php 
	}

	public function update($new_instance, $old_instance) {
		// processes widget options to be saved
		$instance = $old_instance;
		$instance['title'] = !empty($new_instance['title']) ? $new_instance['title'] : '';
		$instance['show_page_title'] = !empty($new_instance['show_page_title']);
		$instance['show_page_content'] = !empty($new_instance['show_page_content']);
		$instance['show_title_as_link'] = !empty($new_instance['show_title_as_link']);
		$instance['show_featured_image'] = !empty($new_instance['show_featured_image']);
		$instance['show_featured_image_as_link'] = !empty($new_instance['show_featured_image_as_link']);
		$instance['page'] = $new_instance['page'];
		$instance['output_template'] = $new_instance['output_template'];
		return $instance;
	}

	private function field_names() {
		$this->show_page_title_field_name = $this->get_field_name('show_page_title');
		$this->show_page_title_field_id = $this->get_field_id('show_page_title');

		$this->show_page_content_field_name = $this->get_field_name('show_page_content');
		$this->show_page_content_field_id= $this->get_field_id('show_page_content');

		$this->show_page_title_link_field_name = $this->get_field_name('show_title_as_link');
		$this->show_page_title_link_field_id= $this->get_field_id('show_title_as_link');

		$this->show_page_image_field_name = $this->get_field_name('show_featured_image');
		$this->show_page_image_field_id= $this->get_field_id('show_featured_image');

		$this->show_page_image_link_field_name = $this->get_field_name('show_featured_image_as_link');
		$this->show_page_image_link_field_id= $this->get_field_id('show_featured_image_as_link');

		$this->page_field_id = $this->get_field_id('page');
		$this->page_field_name = $this->get_field_name('page');

		$this->title_field_id = $this->get_field_id('title');
		$this->title_field_name = $this->get_field_name('title');

		$this->output_template_id = $this->get_field_id('output_template');
		$this->output_template_name = $this->get_field_name('output_template');
	}

	private function defaults() {
		return array(
			'show_page_title' => 1,
			'show_title_as_link' => 0,
			'show_page_content' => 1,
			'show_featured_image' => 0,
			'show_featured_image_as_link' => 0,
		);
	}

}

// register widgets
function twl_pip_register_widgets() {
    register_widget('TWL_Page_IN_Page_Widget');
}
add_action('widgets_init', 'twl_pip_register_widgets');