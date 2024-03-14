<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

if (!defined('ABSPATH'))
{
	exit;
}

abstract class FPF_Widget extends \WP_Widget
{
	/**
	 * CSS class
	 *
	 * @var string
	 */
	public $widget_cssclass = '';

	/**
	 * Widget description
	 *
	 * @var string
	 */
	public $widget_description;

	/**
	 * Widget ID
	 *
	 * @var string
	 */
	public $widget_id;

	/**
	 * Widget name
	 *
	 * @var string
	 */
    public $widget_name;
    
	/**
	 * Settings
	 *
	 * @var array
	 */
	public $settings;

    public function __construct()
    {
		$widget_ops = [
			'classname'                   => $this->widget_cssclass,
			'description'                 => $this->widget_description,
			'customize_selective_refresh' => true,
        ];

		parent::__construct($this->widget_id, $this->widget_name, $widget_ops);

        // Flush widget cache on save, delete, theme change
		add_action('save_post', [$this, 'flush_widget_cache']);
		add_action('deleted_post', [$this, 'flush_widget_cache']);
		add_action('switch_theme', [$this, 'flush_widget_cache']);
	}

	/**
	 * Get the cached version of the widget
	 *
	 * @param   array    $args
     * 
	 * @return  boolean
	 */
    public function get_cached_widget($args)
    {
		// If widget_id is missing, don't cache it
        if (empty($args['widget_id']))
        {
			return false;
		}

		$cache = wp_cache_get($this->get_widget_id_for_cache($this->widget_id), 'widget');

        if (!is_array($cache))
        {
			$cache = [];
		}

        if (isset($cache[$this->get_widget_id_for_cache($args['widget_id'])]))
        {
			echo $cache[$this->get_widget_id_for_cache($args['widget_id'])];
			return true;
		}

		return false;
	}

	/**
	 * Caches the widget
	 *
	 * @param   array   $args
	 * @param   string  $content
     * 
	 * @return  string
	 */
    public function cache_widget($args, $content)
    {
		// If widget_id is missing, don't cache it
        if (empty($args['widget_id']))
        {
			return false;
		}

		$cache = wp_cache_get($this->get_widget_id_for_cache($this->widget_id), 'widget');

        if (!is_array($cache))
        {
			$cache = [];
		}

		$cache[$this->get_widget_id_for_cache($args['widget_id'])] = $content;

		wp_cache_set($this->get_widget_id_for_cache($this->widget_id), $cache, 'widget');

		return $content;
	}

	/**
	 * Flushes the cache
     * 
     * @return  void
	 */
    public function flush_widget_cache()
    {
        $protocols = ['https', 'http'];
        
        foreach ($protocols as $scheme)
        {
			wp_cache_delete($this->get_widget_id_for_cache($this->widget_id, $scheme), 'widget');
		}
	}

	/**
	 * Retrieves the widgets title
	 *
	 * @param   array   $instance
     * 
	 * @return  string
	 */
    protected function get_instance_title($instance)
    {
        if (isset($instance['title']))
        {
			return $instance['title'];
		}

        if (isset($this->settings, $this->settings['title'], $this->settings['title']['default']))
        {
			return $this->settings['title']['default'];
		}

		return '';
	}

	/**
	 * Output the html at the start of the widget
	 *
	 * @param   array  $args
	 * @param   array  $instance
     * 
     * @return  void
	 */
    public function widget_start($args, $instance)
    {
		echo $args['before_widget'];

		$title = apply_filters('widget_title', $this->get_instance_title($instance), $instance, $this->id_base);

        if ($title)
        {
			echo $args['before_title'] . $title . $args['after_title'];
		}
	}

	/**
	 * Output the html at the end of the widget
	 *
	 * @param   array  $args
     * 
     * @return  void
	 */
    public function widget_end($args)
    {
		echo $args['after_widget'];
	}

	/**
	 * Updates the widget
	 *
	 * @param   array  $new_instance
	 * @param   array  $old_instance
     * 
	 * @return  array
	 */
    public function update($new_instance, $old_instance)
    {
		$instance = $old_instance;

        if (empty($this->settings))
        {
			return $instance;
		}

		// Loop settings and get values to save.
        foreach ($this->settings as $key => $setting)
        {
            if (!isset($setting['type']))
            {
				continue;
			}

			// Format the value based on settings type.
            switch ($setting['type'])
            {
				case 'number':
					$instance[$key] = absint($new_instance[$key]);

                    if (isset($setting['min']) && '' !== $setting['min'])
                    {
						$instance[$key] = max($instance[$key], $setting['min']);
					}

                    if (isset($setting['max']) && '' !== $setting['max'])
                    {
						$instance[$key] = min($instance[$key], $setting['max']);
					}
					break;
				case 'textarea':
					$instance[$key] = wp_kses(trim(wp_unslash($new_instance[$key])), wp_kses_allowed_html('post'));
					break;
				case 'checkbox':
					$instance[$key] = empty($new_instance[$key]) ? 0 : 1;
					break;
				default:
					$instance[$key] = isset($new_instance[$key]) ? sanitize_text_field($new_instance[$key]) : $setting['default'];
					break;
			}
		}

		$this->flush_widget_cache();

		return $instance;
	}

	/**
	 * Outputs the widget form
	 *
	 * @param   array $instance
     * 
     * @return  void
	 */
    public function form($instance )
    {
        if (empty($this->settings ) )
        {
			return;
		}

        foreach ($this->settings as $key => $setting)
        {
			$class = isset($setting['class']) ? $setting['class'] : '';
			$value = isset($instance[$key]) ? $instance[$key] : $setting['default'];

            switch ($setting['type'])
            {
				case 'text':
					?>
					<p>
						<label for="<?php echo esc_attr($this->get_field_id($key ) ); ?>"><?php echo wp_kses_post($setting['label']); ?></label><?php ?>
						<input class="widefat <?php echo esc_attr($class ); ?>" id="<?php echo esc_attr($this->get_field_id($key ) ); ?>" name="<?php echo esc_attr($this->get_field_name($key ) ); ?>" type="text" value="<?php echo esc_attr($value ); ?>" />
					</p>
					<?php
					break;
				case 'number':
					?>
					<p>
						<label for="<?php echo esc_attr($this->get_field_id($key ) ); ?>"><?php echo wp_kses_post($setting['label']); ?></label>
						<input class="widefat <?php echo esc_attr($class ); ?>" id="<?php echo esc_attr($this->get_field_id($key ) ); ?>" name="<?php echo esc_attr($this->get_field_name($key ) ); ?>" type="number" step="<?php echo esc_attr($setting['step']); ?>" min="<?php echo esc_attr($setting['min']); ?>" max="<?php echo esc_attr($setting['max']); ?>" value="<?php echo esc_attr($value ); ?>" />
					</p>
					<?php
					break;
				case 'select':
					?>
					<p>
						<label for="<?php echo esc_attr($this->get_field_id($key ) ); ?>"><?php echo wp_kses_post($setting['label']); ?></label>
						<select class="widefat <?php echo esc_attr($class ); ?>" id="<?php echo esc_attr($this->get_field_id($key ) ); ?>" name="<?php echo esc_attr($this->get_field_name($key ) ); ?>">
							<?php foreach ($setting['options'] as $option_key => $option_value ) : ?>
								<option value="<?php echo esc_attr($option_key ); ?>" <?php selected($option_key, $value ); ?>><?php echo esc_html($option_value ); ?></option>
							<?php endforeach; ?>
						</select>
					</p>
					<?php
					break;
				case 'textarea':
					?>
					<p>
						<label for="<?php echo esc_attr($this->get_field_id($key ) ); ?>"><?php echo wp_kses_post($setting['label']); ?></label>
						<textarea class="widefat <?php echo esc_attr($class ); ?>" id="<?php echo esc_attr($this->get_field_id($key ) ); ?>" name="<?php echo esc_attr($this->get_field_name($key ) ); ?>" cols="20" rows="3"><?php echo esc_textarea($value ); ?></textarea>
						<?php if (isset($setting['desc']) ) : ?>
							<small><?php echo esc_html($setting['desc']); ?></small>
						<?php endif; ?>
					</p>
					<?php
					break;
				case 'checkbox':
					?>
					<p>
						<input class="checkbox <?php echo esc_attr($class ); ?>" id="<?php echo esc_attr($this->get_field_id($key ) ); ?>" name="<?php echo esc_attr($this->get_field_name($key ) ); ?>" type="checkbox" value="1" <?php checked($value, 1 ); ?> />
						<label for="<?php echo esc_attr($this->get_field_id($key ) ); ?>"><?php echo wp_kses_post($setting['label']); ?></label>
					</p>
					<?php
					break;
				default:
					break;
			}
		}
	}

	/**
	 * Get widget ID plus scheme/protocol to prevent serving mixed content from (persistently) cached widgets.
	 *
	 * @param   string  $widget_id
	 * @param   string  $scheme
     * 
	 * @return  string
	 */
    protected function get_widget_id_for_cache($widget_id, $scheme = '')
    {
        if ($scheme)
        {
			$widget_id_for_cache = $widget_id . '-' . $scheme;
        }
        else
        {
			$widget_id_for_cache = $widget_id . '-' . (is_ssl() ? 'https' : 'http');
		}

		return $widget_id_for_cache;
	}
}