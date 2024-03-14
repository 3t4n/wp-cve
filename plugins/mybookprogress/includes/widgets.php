<?php

function mbp_register_widget() {
	register_widget('MyBookProgress_Widget');
}
add_action('widgets_init', 'mbp_register_widget');

class MyBookProgress_Widget extends WP_Widget {
	function __construct() {
		parent::__construct('mbp_widget', 'MyBookProgress', array('description' => __('Display your book progress bars.', 'mybookprogress')));
		$this->defaultargs = array('title' => 'My Book Progress', 'simplesubscribe' => true, 'display' => 'all', 'manual_books' => array());
		add_action('admin_enqueue_scripts', array('MyBookProgress_Widget', 'enqueue_admin_resources'));
	}

	public static function enqueue_admin_resources() {
		global $pagenow;
		if($pagenow == 'widgets.php') {
			wp_enqueue_script('mbp-widgets-script', plugins_url('js/widgets.js', dirname(__FILE__)), array('jquery'), MBP_VERSION, true);
		}
	}

	function widget($args, $instance) {
		$instance = wp_parse_args($instance, $this->defaultargs);
		if($instance['display'] == 'single') { $instance['display'] = 'manual'; }
		if(!empty($instance['single_book'])) { $instance['manual_books'] = array((int)$instance['single_book']); }
		echo($args['before_widget']);
		echo($args['before_title']);
		echo(empty($args['title']) ? $instance['title'] : $args['title']);
		echo($args['after_title']);
		echo('<div class="mbp-widget">');

		$options = array('location' => 'shortcode', 'simplesubscribe' => $instance['simplesubscribe']);
		$books = null;
		if($instance['display'] === 'manual') {
			$books = array();
			foreach($instance['manual_books'] as $book_id) {
				$books[] = mbp_get_book($book_id);
			}
		}
		echo(mbp_format_books_progress($options, $books));

		echo('</div>');
		echo('<div style="clear:both;"></div>');
		echo($args['after_widget']);
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['simplesubscribe'] = !empty($new_instance['simplesubscribe']);
		$instance['display'] = strval($new_instance['display']);
		if(is_array($new_instance['manual_books']) && count($new_instance['manual_books'])<2){
			$new_manual_books = implode($new_instance['manual_books']);
		} else {
			$new_manual_books = $new_instance['manual_books'];
		}
		$instance['manual_books'] = (array)json_decode($new_manual_books);
		unset($instance['single_book']);
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args($instance, $this->defaultargs);
		if($instance['display'] == 'single') { $instance['display'] = 'manual'; }
		if(!empty($instance['single_book'])) { $instance['manual_books'] = array((int)$instance['single_book']); }
		?>
		<div class="mbp-widget-editor" onmouseover="mbp_initialize_widget_editor(this);">
			<p>
				<label for="<?php echo($this->get_field_id('title')); ?>"><?php _e('Title', 'mybookprogress'); ?>:</label>
				<input type="text" name="<?php echo($this->get_field_name('title')); ?>" id="<?php echo($this->get_field_id('title')); ?>" value="<?php echo($instance['title']); ?>">
			</p>
			<?php if(mbp_get_setting('mailinglist_type') == 'mailchimp') { ?>
			<p>
				<input type="checkbox" name="<?php echo($this->get_field_name('simplesubscribe')); ?>" id="<?php echo($this->get_field_id('simplesubscribe')); ?>" <?php checked($instance['simplesubscribe'], true); ?>>
				<label for="<?php echo($this->get_field_id('simplesubscribe')); ?>"><?php _e('Use simple subscribe form', 'mybookprogress'); ?></label>
			</p>
			<?php } ?>
			<p>
				<label for="<?php echo($this->get_field_id('display')); ?>"><?php _e('Display', 'mybookprogress'); ?>:</label>
				<select class="mbp-widget-book-display" name="<?php echo($this->get_field_name('display')); ?>" id="<?php echo($this->get_field_id('display')); ?>">
					<option value="all" <?php selected($instance['display'], 'all'); ?>><?php _e('All Books', 'mybookprogress'); ?></option>
					<option value="manual" <?php selected($instance['display'], 'manual'); ?>><?php _e('Choose Manually', 'mybookprogress'); ?></option>
				</select>
			</p>
			<div class="mbp-widget-book-selector" <?php if($instance['display'] !== 'manual') { echo('style="display:none"'); } ?>>
				<label for="mbp-book-selector"><?php _e('Select Books:', 'mybookprogress'); ?></label></br>
				<select class="mbp-book-selector">
					<option value=""><?php _e('-- Choose One --', 'mybookprogress'); ?></option>
					<?php
						$books = mbp_get_books();
						foreach($books as $book) {
							$title = mbp_get_book_title($book);
							if(strlen($title) > 25) { $title = substr($title, 0, 25).'...'; }
							echo('<option value="'.$book['id'].'">'.substr($title, 0, 25).(strlen($title) > 25 ? '...' : '').'</option>');
						}
					?>
				</select>
				<input type="button" class="mbp-book-adder button" value="<?php _e('Add', 'mybookprogress'); ?>" /><br>

				<?php
					echo('<ul class="mbp-book-list">');
					foreach($instance['manual_books'] as $book_id) {
						$book = mbp_get_book($book_id);
						if($book) {
							$title = mbp_get_book_title($book);
							if(strlen($title) > 25) { $title = substr($title, 0, 25).'...'; }
							echo('<li data-id="'.$book['id'].'" class="mbp-book">'.$title.'<a class="mbp-book-remover">X</a></li>');
						}
					}
					echo('</ul>');
				?>
				<input class="mbp-manual-books" id="<?php echo($this->get_field_id('manual_books')); ?>" name="<?php echo($this->get_field_name('manual_books')); ?>" type="hidden" value="<?php echo(json_encode($instance['manual_books'])); ?>">
			</div>
		</div>
		<?php
	}
}
