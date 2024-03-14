<?php

class R34OnThisDay extends WP_Widget {

	public $version = '';
	public $default_title = 'On This Day';
	public $default_no_posts_message = 'Nothing has ever happened on this day. <em>Ever.</em>';
	public $default_see_all_link_text = 'See all...';
	public $default_posts_per_page = 10;
	public $displayed_lists = array();

	protected $shortcode_defaults = array(
		'after_title' => '</h3>',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'before_widget' => '<aside class="widget widget_r34otd">',
		'categories' => null,
		'day' => null,
		'month' => null,
		'no_posts_message' => 'Nothing has ever happened on this day. Ever.',
		'posts_per_page' => 10,
		'see_all_link_text' => 'See all...',
		'show_archive_link' => false,
		'show_post_date' => false,
		'show_post_dates' => null, // Alias
		'show_post_excerpt' => false,
		'show_post_excerpts' => null, // Alias
		'show_post_thumbnail' => false,
		'show_post_thumbnails' => null, // Alias
		'title' => 'On This Day',
		'use_post_date' => false,
	);

	public function __construct() {
		parent::__construct('r34otd', 'On This Day');
		
		$this->version = $this->_get_version();
		
		// Enqueue admin CSS
		add_action('admin_enqueue_scripts', function() {
			wp_enqueue_style('r34otd-admin-css', plugin_dir_url(__FILE__) . 'r34otd-admin.css', array(), $this->version);
		}, 10);

		// Enqueue front-end CSS
		add_action('wp_enqueue_scripts', function() {
			wp_enqueue_style('r34otd-css', plugin_dir_url(__FILE__) . 'r34otd-style.css', array(), $this->version);
		}, 10);

		// Add ICS shortcode
		add_shortcode('on_this_day', array(&$this, 'shortcode'));
		
		// Change excerpt parameters for OTD widget
		add_filter('excerpt_length', array(&$this, 'excerpt_length'));
		add_filter('excerpt_more', array(&$this, 'excerpt_more'));
		
		// Default text strings with translations
		$this->default_title = __('On This Day', 'r34otd');
		$this->default_no_posts_message = __('Nothing has ever happened on this day. Ever.', 'r34otd');
		$this->default_see_all_link_text = __('See all...', 'r34otd');
		$this->shortcode_defaults['default_title'] = $this->default_title;
		$this->shortcode_defaults['default_no_posts_message'] = $this->default_no_posts_message;
		$this->shortcode_defaults['default_see_all_link_text'] = $this->default_see_all_link_text;
		
	}
	
	public function excerpt_length($number) {
		global $r34otd_loop, $r34otd_excerpt_length;
		if (!empty($r34otd_loop) && !empty($r34otd_excerpt_length)) {
			$number = $r34otd_excerpt_length;
		}
		return $number;
	}

	public function excerpt_more($more_string) {
		global $r34otd_loop;
		if (!empty($r34otd_loop)) {
			$more_string = '&hellip;';
		}
		return $more_string;
	}

	public function form($instance) {
		// Walker for category checkboxes
		$walker = null;
		if (class_exists('Walker_Category_Checklist_Widget')) {
			$walker = new Walker_Category_Checklist_Widget(
				$this->get_field_name('categories'),
				$this->get_field_id('categories')
			);
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">
				<strong><?php _e('Title', 'r34otd'); ?>:</strong>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title'] ? $instance['title'] : $this->default_title); ?>" /><br />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('no_posts_message'); ?>">
				<strong><?php _e('Message to display if no posts are found', 'r34otd'); ?>:</strong>
				<input class="widefat" id="<?php echo $this->get_field_id('no_posts_message'); ?>" name="<?php echo $this->get_field_name('no_posts_message'); ?>" type="text" value="<?php echo esc_attr($instance['no_posts_message'] ? $instance['no_posts_message'] : $this->default_no_posts_message); ?>" /><br />
				<small class="r34otd-small"><?php _e('Leave blank to hide widget altogether if no posts are found.', 'r34otd'); ?></small>
			</label>
		</p>
		<hr />
		<p>
			<label for="<?php echo $this->get_field_id('posts_per_page'); ?>">
				<strong><?php _e('Maximum posts to display', 'r34otd'); ?>:</strong>
				<input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" type="number" max="999" min="1" size="3" step="1" value="<?php echo intval($instance['posts_per_page'] ? $instance['posts_per_page'] : $this->default_posts_per_page); ?>" /><br />
			</label>
		</p>
		<?php
		// This doesn't work with the widget block editor introduced in WordPress 5.8
		if (!get_theme_support('widgets-block-editor')) {
			?>
			<p>
				<label for="<?php echo $this->get_field_id('categories'); ?>">
					<strong><?php _e('Category (optional)', 'r34otd'); ?>:</strong>
					<ul class="r34otd-scrolling"><?php wp_category_checklist(0, 0, $instance['categories'], false, $walker, false); ?></ul>
					<small class="r34otd-small"><?php _e('If none are selected, results will include all categories.', 'r34otd'); ?></small>
				</label>
			</p>
			<?php
		}
		?>
		<hr />
		<p>
			<label for="<?php echo $this->get_field_id('show_archive_link'); ?>">
				<input class="widefat" id="<?php echo $this->get_field_id('show_archive_link'); ?>" name="<?php echo $this->get_field_name('show_archive_link'); ?>" type="checkbox"<?php if (!empty($instance['show_archive_link'])) { echo ' checked="checked"'; } ?>" /> <strong><?php _e('Show On This Day archive link', 'r34otd'); ?></strong><br />
				<small class="r34otd-small"><?php _e('Adds a "See all..." link at the bottom of the widget, which takes the user to an archive page listing all posts for the current date.', 'r34otd'); ?></small>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('see_all_link_text'); ?>">
				<strong><?php _e('Text to display for "See all..." link', 'r34otd'); ?>:</strong>
				<input class="widefat" id="<?php echo $this->get_field_id('see_all_link_text'); ?>" name="<?php echo $this->get_field_name('see_all_link_text'); ?>" type="text" value="<?php echo esc_attr($instance['see_all_link_text'] ? $instance['see_all_link_text'] : $this->default_see_all_link_text); ?>" />
			</label>
		</p>
		<hr />
		<p>
			<label for="<?php echo $this->get_field_id('show_post_thumbnail'); ?>">
				<input class="widefat" id="<?php echo $this->get_field_id('show_post_thumbnail'); ?>" name="<?php echo $this->get_field_name('show_post_thumbnail'); ?>" type="checkbox"<?php if (!empty($instance['show_post_thumbnail'])) { echo ' checked="checked"'; } ?>" /> <strong><?php _e('Show post featured images (if available)', 'r34otd'); ?></strong><br />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_post_date'); ?>">
				<input class="widefat" id="<?php echo $this->get_field_id('show_post_date'); ?>" name="<?php echo $this->get_field_name('show_post_date'); ?>" type="checkbox"<?php if (!empty($instance['show_post_date'])) { echo ' checked="checked"'; } ?>" /> <strong><?php _e('Show full post dates', 'r34otd'); ?></strong><br />
				<small class="r34otd-small"><?php _e('<strong>Note:</strong> The year will always be displayed.', 'r34otd'); ?></small>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_post_excerpt'); ?>">
				<input class="widefat" id="<?php echo $this->get_field_id('show_post_excerpt'); ?>" name="<?php echo $this->get_field_name('show_post_excerpt'); ?>" type="checkbox"<?php if (!empty($instance['show_post_excerpt'])) { echo ' checked="checked"'; } ?>" /> <strong><?php _e('Show post excerpts', 'r34otd'); ?></strong><br />
			</label>
		</p>
		<hr />
		<p>
			<label for="<?php echo $this->get_field_id('use_post_date'); ?>">
				<input class="widefat" id="<?php echo $this->get_field_id('use_post_date'); ?>" name="<?php echo $this->get_field_name('use_post_date'); ?>" type="checkbox"<?php if (!empty($instance['use_post_date'])) { echo ' checked="checked"'; } ?>" /> <strong><?php _e('Use post date', 'r34otd'); ?></strong><br />
				<small class="r34otd-small"><?php _e('When viewing an individual post, the widget will show posts from the same date as the current post, not today&#39;s date. On main blog or archive pages, widget will still show posts from today&#39;s date.', 'r34otd'); ?></small>
			</label>
		</p>
		<p>
			<small class="r34otd-small"><strong><?php _e('Tip: ', 'r34otd'); ?></strong><?php _e('Add multiple widgets, one with "Use post date" checked and one without, to display both today&rsquo;s historical posts and those for the current post&rsquo;s date. If the lists are the same (e.g. the current post was published on today&rsquo;s date), only one will display.', 'r34otd'); ?></small>
		</p>
		<hr />
		<p>
			<small class="r34otd-small"><?php printf(__('You can also insert On This Day anywhere using the %1$s shortcode. %2$sLearn More...%3$s', 'r34otd'), '<code>[on_this_day]</code>', '<a href="' . admin_url('options-general.php?page=r34otd') . '">', '</a>'); ?></small>
		</p>
		<?php
	}

	public function shortcode($atts) {

		// Don't do anything in admin
		if (is_admin()) { return; }

		// Extract attributes
		extract(shortcode_atts($this->shortcode_defaults, $atts, 'on_this_day'));
		
		// Handle alias attribute names
		if ($show_post_dates !== null) { $show_post_date = $show_post_dates; }
		if ($show_post_excerpts !== null) { $show_post_excerpt = $show_post_excerpts; }
		if ($show_post_thumbnails !== null) { $show_post_thumbnail = $show_post_thumbnails; }
		
		// Assemble arguments
		$args = array(
			'after_title' => $after_title,
			'after_widget' => $after_widget,
			'before_title' => $before_title,
			'before_widget' => $before_widget,
		);
		
		// Assemble "instance" so we can use the widget() method
		$instance = array(
			'categories' => (!empty($categories) ? explode(',', $categories) : null),
			'day' => $day,
			'month' => $month,
			'no_posts_message' => $no_posts_message,
			'posts_per_page' => $posts_per_page,
			'see_all_link_text' => $see_all_link_text,
			'show_archive_link' => ($show_archive_link == 'true' ? true : false),
			'show_post_date' => ($show_post_date == 'true' ? true : false),
			'show_post_excerpt' => (!empty($show_post_excerpt) ? intval($show_post_excerpt) : false),
			'show_post_thumbnail' => ($show_post_thumbnail == 'true' ? true : false),
			'title' => $title,
			'use_post_date' => ($use_post_date == 'true' ? true : false),
		);
		
		// Convert category slugs to term IDs
		if (!empty($instance['categories'])) {
			foreach ((array)$instance['categories'] as $key => $value) {
				if (!intval($value) && $cat = get_category_by_slug(trim($value))) {
					$instance['categories'][$key] = $cat->term_id;
				}
			}
		}
		
		ob_start();
		$this->widget($args, $instance);
		return ob_get_clean();
		
	}

	public function update($new_instance, $old_instance) {
		return $new_instance;
	}
	
	public function widget($args, $instance) {
		extract($args);
		
		// Bail out now if we're on an archive page and this instance is using post date
		if (!is_single() && !empty($instance['use_post_date'])) { return false; }

		// Set title
		if (empty($instance['title'])) {
			$instance['title'] = $this->default_title;
		}

		// Set no posts message
		if (empty($instance['no_posts_message'])) {
			$instance['no_posts_message'] = $this->default_no_posts_message;
		}
		
		// Set see all link text
		if (empty($instance['see_all_link_text'])) {
			$instance['see_all_link_text'] = $this->default_see_all_link_text;
		}

		// Get historical posts
		// Current post's date
		if (is_singular() && !empty($instance['use_post_date'])) {
			$date_query = null;
			$monthnum = get_the_date('n');
			$day = get_the_date('j');
		}
		// Arbitrary date
		elseif (!empty($instance['month']) && !empty($instance['day'])) {
			$date_query = null;
			$monthnum = intval($instance['month']);
			$day = intval($instance['day']);
		}
		// Today
		else {
			$date_query = array(array('before' => array('year' => wp_date('Y'))));
			$monthnum = wp_date('n');
			$day = wp_date('j');
		}
		$historic_posts = get_posts(array(
			'date_query' => $date_query,
			'monthnum' => $monthnum,
			'day' => $day,
			'category' => (!empty($instance['categories']) ? implode(',',$instance['categories']) : null),
			'posts_per_page' => intval($instance['posts_per_page']),
		));
		
		// Skip display if list is empty and no_posts_message is blank
		if (empty($historic_posts) && empty(trim($instance['no_posts_message']))) { return false; }
		
		// Add hash of this list to displayed, to prevent duplicates if widget is used more than once on a page
		// Check for empty posts ensures no_posts_message will still display
		// @todo Add an option for when a site owner DOES want redundant lists to display
		if (!empty($historic_posts)) {
			$serialized = sha1(serialize($historic_posts));
			if (in_array($serialized, $this->displayed_lists)) { return false; }
			$this->displayed_lists[] = $serialized;
		}
		
		// Build widget display
		echo $before_widget;

		// Widget title
		echo $before_title . $instance["title"] . $after_title;
		?>

		<ul class="r34otd">
			<?php
			if (!empty($historic_posts)) {
				global $r34otd_loop, $r34otd_excerpt_length;
				$r34otd_loop = true;
				$r34otd_excerpt_length = (intval($instance['show_post_excerpt']) > 1 ? intval($instance['show_post_excerpt']) : 25);
				foreach ($historic_posts as $hpost) {
					// Get the permalink (to avoid running this function multiple times)
					$hpost_permalink = get_permalink($hpost->ID);
					?>
					<li>
						<?php
						if (!empty($instance['show_post_thumbnail']) && $hpost_thumbnail = get_the_post_thumbnail($hpost->ID)) {
							echo '<a href="' . esc_url($hpost_permalink) . '">' . wp_kses_post($hpost_thumbnail) . '</a>';
						}
						?>
						<div class="r34otd-headline"><a href="<?php echo $hpost_permalink; ?>"><?php echo get_the_title($hpost->ID); ?></a></div>
						<?php
						$r34otd_date_format = !empty($instance['show_post_date']) ? get_option('date_format') : 'Y';
						echo '<div class="r34otd-dateline post-date">' . get_the_date($r34otd_date_format, $hpost) . '</div>';
						if (!empty($instance['show_post_excerpt']) && $hpost_excerpt = get_the_excerpt($hpost->ID)) {
							echo '<div class="r34otd-excerpt post-excerpt">' . wp_kses_post($hpost_excerpt) . '</div>';
						}
						?>
					</li>
					<?php
				}
				$r34otd_loop = false;
			}

			else {
				echo '<li>' . $instance['no_posts_message'] . '</li>';
			}
			?>
		</ul>

		<?php
		if (!empty($instance['show_archive_link']) && !empty($historic_posts)) {
			// Current post's date
			if (!empty($instance['use_post_date'])) {
				$archive_link = home_url('/archives/otd/' . wp_date('md', strtotime($monthnum . '/' . $day . '/' . wp_date('Y') . ' 12:00 PM')) . '/');
			}
			// Arbitrary date
			elseif (!empty($instance['month']) && !empty($instance['day'])) {
				$archive_link = home_url('/archives/otd/' . wp_date('md', strtotime(intval($instance['month']) . '/' . intval($instance['day']) . '/' . wp_date('Y') . ' 12:00 PM')) . '/');
			}
			// Today
			else {
				$archive_link = home_url('/archives/otd/');
			}
			?>
			<p><a href="<?php echo esc_url($archive_link); ?>"><?php echo $instance['see_all_link_text']; ?></a></p>
			<?php
		}
		
		echo $after_widget;
	}

	private function _get_version() {
		if (!function_exists('get_plugin_data')) {
			require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}
		$plugin_data = get_plugin_data(dirname(__FILE__) . '/r34-on-this-day.php');
		return $plugin_data['Version'];
	}

}


// Add category checklist capability for widget configuration
// Based on: http://wordpress.stackexchange.com/questions/124772/using-wp-category-checklist-in-a-widget
add_action('admin_init', 'r34otd_admin_init', 10, 0);
function r34otd_admin_init() {
	require_once(ABSPATH . 'wp-admin/includes/template.php');
	class Walker_Category_Checklist_Widget extends Walker_Category_Checklist {
		private $name;
		private $id;
		function __construct($name = '', $id = '') {
			$this->name = $name;
			$this->id = $id;
		}
		function start_el(&$output, $cat, $depth = 0, $args = array(), $id = 0) {
			extract($args);
			if (empty($taxonomy)) $taxonomy = 'category';
			$class = in_array($cat->term_id, $popular_cats) ? ' class="popular-category"' : '';
			$id = $this->id . '-' . $cat->term_id;
			$checked = checked(in_array($cat->term_id, $selected_cats), true, false);
			$output .= "\n<li id='{$taxonomy}-{$cat->term_id}'$class>" 
				. '<label class="selectit"><input value="' 
				. $cat->term_id . '" type="checkbox" name="' . $this->name 
				. '[]" id="in-'. $id . '"' . $checked 
				. disabled(empty($args['disabled']), false, false) . ' /> ' 
				. esc_html(apply_filters('the_category', $cat->name)) 
				. '</label>';
		  }
	}
}


// Register widget
add_action('widgets_init', 'r34otd_widgets_init', 10, 0);
function r34otd_widgets_init() {
	return register_widget("R34OnThisDay");
}
