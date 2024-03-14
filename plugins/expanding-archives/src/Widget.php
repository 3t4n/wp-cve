<?php
/**
 * Widget.php
 *
 * @package   expanding-archives
 * @copyright Copyright (c) 2022, Ashley Gibson
 * @license   GPL2+
 * @since     2.0
 */

namespace Ashleyfae\ExpandingArchives;

use Ashleyfae\ExpandingArchives\Helpers\ArchiveRenderer;
use Ashleyfae\ExpandingArchives\Helpers\DateQuery;

class Widget extends \WP_Widget
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            'ng_expanding_archives',
            __('Expanding Archives', 'expanding-archives'),
            [
                'description' => __('Adds expandable archives of your old posts.', 'expanding-archives'),
            ]
        );
    }

    /**
     * Parses the instance values by merging in the defaults.
     *
     * @since 2.0
     *
     * @param  array|mixed  $instance
     *
     * @return array
     */
    private function getInstanceValues($instance): array
    {
        if (! is_array($instance)) {
            $instance = [];
        }

        return wp_parse_args($instance, [
            'title'          => '',
            'expand_current' => true,
        ]);
    }

    /**
     * Displays the front-end of the widget.
     *
     * @since 2.0
     *
     * @param  array  $args  Widget arguments.
     * @param  array  $instance  Saved values from database.
     *
     * @return void
     */
    public function widget($args, $instance)
    {
        $instance = $this->getInstanceValues($instance);

        echo $args['before_widget'] ?? '';

        $title = apply_filters('widget_title', $instance['title'] ?? '');

        //If title section is filled out, display title.  If not, display nothing.
        if (! empty($title)) {
            echo $args['before_title'].$title.$args['after_title'];
        }

        $displayer = new ArchiveRenderer();
        if (! empty($instance['expand_current'])) {
            $displayer->expandCurrent();
        }

        $displayer->render();

        echo $args['after_widget'] ?? '';
    }

    /**
     * Displays the admin widget form.
     *
     * @since 2.0
     *
     * @see WP_Widget::form()
     *
     * @param  array  $instance  Saved values from database.
     */
    public function form($instance)
    {
        $instance = $this->getInstanceValues($instance);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                <?php esc_html_e('Title:'); ?>
            </label>
            <input
                type="text"
                id="<?php echo $this->get_field_id('title'); ?>"
                class="widefat"
                name="<?php echo $this->get_field_name('title'); ?>"
                value="<?php echo esc_attr($instance['title']); ?>"
            >
        </p>

        <p>
            <input
                type="checkbox"
                id="<?php echo $this->get_field_id('expand_current'); ?>"
                name="<?php echo $this->get_field_name('expand_current'); ?>"
                value="1" <?php checked($instance['expand_current']); ?>
            >
            <label for="<?php echo $this->get_field_id('expand_current'); ?>">
                <?php esc_html_e('Automatically expand current month'); ?>
            </label>
        </p>
        <?php
    }

    /**
     * Sanitizes the widget form values as they are saved.
     *
     * @since 2.0
     *
     * @see WP_Widget::update()
     *
     * @param  array  $new_instance  Values just sent to be saved.
     * @param  array  $old_instance  Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        return [
            'title'          => sanitize_text_field(strip_tags($new_instance['title'] ?? '')),
            'expand_current' => ! empty($new_instance['expand_current']),
        ];
    }

}
