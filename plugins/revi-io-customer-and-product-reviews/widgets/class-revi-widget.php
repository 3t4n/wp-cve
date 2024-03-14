<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class revi_Widget extends WP_Widget
{
	//Constructor
	function __construct()
	{
		$this->revi_options = get_option('revi_options');
		$this->revi_configuration = get_option('revi_configuration');

		$widget_ops = array(
			'classname' => 'revi-io-customer-and-product-reviews',
			'description' => __('Displays Revi widget', 'revi-io-customer-and-product-reviews')
		);
		parent::__construct('revi_box', 'Revi widget', $widget_ops);
	}

	function widget($args, $instance)
	{
		if (!empty($instance['select'])) {
			$function = "revi_load_widget_" . $instance['select'] . "";
			call_user_func($function);
		}
	}

	// Update widget settings
	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['select']   = isset($new_instance['select']) ? wp_strip_all_tags($new_instance['select']) : '';
		return $instance;
	}

	public function form($instance)
	{
		// Set widget defaults
		$defaults = array(
			'select'   => 'vertical',
		);
		// Parse current settings with defaults
		extract(wp_parse_args((array) $instance, $defaults)); ?>

		<p>
			<label for="<?php echo $this->get_field_id('select'); ?>"><?php _e('Select', 'revi-io-customer-and-product-reviews'); ?></label>
			<select name="<?php echo $this->get_field_name('select'); ?>" id="<?php echo $this->get_field_id('select'); ?>" class="widefat">
				<?php
				// Your options array
				$options = array(
					'vertical' => __('Vertical', 'revi-io-customer-and-product-reviews'),
					'wide' => __('Wide', 'revi-io-customer-and-product-reviews'),
					'small' => __('Small', 'revi-io-customer-and-product-reviews'),
					'floating' => __('Floating', 'revi-io-customer-and-product-reviews'),
					'general' => __('General', 'revi-io-customer-and-product-reviews'),
				);

				// Loop through options and add each one to the select dropdown
				foreach ($options as $key => $name) {
					echo '<option value="' . esc_attr($key) . '" id="' . esc_attr($key) . '" ' . selected($select, $key, false) . '>' . $name . '</option>';
				} ?>
			</select>
		</p>

<?php
	}
}
