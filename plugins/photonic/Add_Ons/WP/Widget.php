<?php

namespace Photonic_Plugin\Add_Ons\WP;

use Photonic_Plugin\Core\Photonic;
use WP_Widget;

class Widget extends WP_Widget {
	private $empty_shortcode;
	public $invalid_shortcode;
	public $edit_shortcode;

	public function __construct() {
		$widget_ops = [
			'classname'   => 'widget-photonic',
			'description' => __("A widget for displaying a Photonic Gallery.", 'photonic')
		];

		$control_ops = [];
		$this->empty_shortcode = esc_html__('Click on the icon to start creating a new gallery.', 'photonic');
		$this->invalid_shortcode = esc_html__('The current saved data does not correspond to a Photonic gallery. A new one will be created.', 'photonic');
		$this->edit_shortcode = esc_html__('Click on the icon to edit your gallery.', 'photonic');

		parent::__construct("photonic-widget", __("Photonic Gallery", 'photonic'), $widget_ops, $control_ops);
	}

	public function widget($args, $instance) {
		$title = empty($instance['title']) ? '' : $instance['title'];
		$shortcode = empty($instance['shortcode']) ? '' : $instance['shortcode'];

		echo wp_kses_post($args['before_widget']);
		if ('' !== $title) {
			echo wp_kses_post($args['before_title'] . $title . $args['after_title']);
		}

		// Input is coming via content saved in a widget, so we have to ensure it is safe.
		// The input is expected to be a Photonic shortcode, so the simplest way is to strip out all instances of the Photonic
		// shortcode and verify that the input is blank. If it is blank, then all that the input had was a Photonic shortcode.

		global $photonic_alternative_shortcode;
		$content_without_shortcodes = strip_shortcodes($shortcode);
		$shortcode_tag = esc_attr($photonic_alternative_shortcode ?: 'gallery');

		if (!empty(trim($shortcode)) && has_shortcode($shortcode, $shortcode_tag) && empty(trim($content_without_shortcodes))) {
			// Looks good. Let's proceed.
			$output = do_shortcode($shortcode);
			echo wp_kses($output, Photonic::$safe_tags);
		}

		echo wp_kses_post($args['after_widget']);
	}

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['shortcode'] = sanitize_text_field($new_instance['shortcode']);
		return $instance;
	}

	public function form($instance) {
		global $photonic_alternative_shortcode;
		$tag = $photonic_alternative_shortcode ?: 'gallery';

		$defaults = [
			'title'        => '',
			'custom_class' => '',
			'shortcode'    => ''
		];
		$instance = wp_parse_args(
			(array) $instance,
			$defaults
		);

		add_thickbox();
		$user = get_current_user_id();
		if (0 === $user) {
			$user = wp_rand(1);
		}

		$url = add_query_arg(
			[
				'action'    => 'photonic_wizard',
				'class'     => 'photonic-flow',
				'post_id'   => '',
				'nonce'     => wp_create_nonce('photonic-wizard-' . $user),
				'width'     => '1000',
				'height'    => '600',
				'TB_iframe' => 'true',
			],
			admin_url('admin.php')
		);

		$shortcode = $instance['shortcode'];
		$types = ['default', 'wp', 'flickr', 'smugmug', 'picasa', 'google', 'zenfolio', 'instagram'];
		$layouts = ['square', 'circle', 'random', 'masonry', 'mosaic', 'strip-above', 'strip-below', 'strip-right', 'no-strip'];

		$pattern = get_shortcode_regex([$tag]);
		preg_match_all('/' . $pattern . '/s', $shortcode, $matches, PREG_OFFSET_CAPTURE);
		$type = 'photonic';

		$message = $this->edit_shortcode;
		if (empty($shortcode)) {
			$message = $this->empty_shortcode;
		}
		elseif (!empty($matches) && !empty($matches[0]) && !empty($matches[1]) && !empty($matches[2]) && !empty($matches[3])) {
			foreach ($matches[1] as $index => $start) {
				if ('' === $start[0]) {
					if (!empty($matches[3][$index])) {
						$shortcode_attr = shortcode_parse_atts($matches[3][$index][0]);
						if (!empty($shortcode_attr['type']) && in_array($shortcode_attr['type'], $types, true)) {
							$type = $shortcode_attr['type'];
						}
						elseif (empty($shortcode_attr['type']) && !empty($shortcode_attr['style']) && in_array($shortcode_attr['style'], $layouts, true)) {
							$type = 'wp';
						}
						else {
							$message = $this->invalid_shortcode;
							$shortcode = '';
						}
					}
				}
			}
		}
		else {
			$message = $this->invalid_shortcode;
			$shortcode = '';
		}
		?>
		<div class="photonic-widget">
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title', 'photonic'); ?></label>
				<input id="<?php echo esc_attr($this->get_field_id('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>"
					   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" class="widefat"/>
			</p>

			<input id="<?php echo esc_attr($this->get_field_id('shortcode')); ?>" value="<?php echo esc_attr($shortcode); ?>" type="hidden"
				   name="<?php echo esc_attr($this->get_field_name('shortcode')); ?>" class="photonic-shortcode"/>

			<div class="photonic-source">
				<a class="photonic-wizard <?php echo esc_attr($type); ?>" href="<?php echo esc_url($url); ?>"></a>
				<p>
					<?php echo wp_kses_post($message); ?>
				</p>
			</div>

			<div class="photonic-shortcode-display">
				<?php
				if ('' !== $shortcode) {
					?>
					<h4><?php echo esc_html__('Current shortcode', 'photonic'); ?></h4>
					<code><?php echo wp_kses_post($shortcode); ?></code>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}

}
