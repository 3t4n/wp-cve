<?php

namespace WordPress\Plugin\Encyclopedia;

use WP_Widget, WP_Query;

class Items_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'encyclopedia_items',
            sprintf('%s %s', PostTypeLabels::getEncyclopediaType(), PostTypeLabels::getItemPluralName()),
            ['description' => sprintf(I18n::__('A list of your %s.'), PostTypeLabels::getItemPluralName())]
        );
    }

    public static function registerWidget()
    {
        if (doing_Action('widgets_init'))
            register_Widget(static::class);
        else
            add_action('widgets_init', [static::class, __FUNCTION__]);
    }

    private function getDefaultOptions(): array
    {
        # Default settings
        return [
            'title'   => '',
            'number_of_items' => null,
            'orderby' => 'title',
            'order'   => 'ASC',
        ];
    }

    private function loadOptions(array &$options): void
    {
        setType($options, 'ARRAY');
        $options = Array_Filter($options);
        $options = Array_Merge($this->getDefaultOptions(), $options);
        setType($options, 'OBJECT');
    }

    public function Form($options): void
    {
        $this->loadOptions($options);

?>
        <p>
            <label for="<?php echo $this->get_Field_Id('title') ?>"><?php I18n::_e('Title:') ?></label>
            <input type="text" id="<?php echo $this->get_Field_Id('title') ?>" name="<?php echo $this->get_Field_Name('title') ?>" value="<?php echo HTMLSpecialChars($options->title) ?>" class="widefat">
        </p>

        <p>
            <label for="<?php echo $this->get_Field_Id('number_of_items') ?>"><?php I18n::_e('Number of terms:') ?></label>
            <input type="number" id="<?php echo $this->get_Field_Id('number_of_items') ?>" name="<?php echo $this->get_Field_Name('number_of_items') ?>" value="<?php echo esc_Attr($options->number_of_items) ?>" min="0" max="<?php echo PHP_INT_MAX ?>" step="1" class="widefat">
            <small><?php I18n::_e('Leave blank to show all terms.') ?></small>
        </p>

        <p>
            <label for="<?php echo $this->get_Field_Id('orderby') ?>"><?php I18n::_e('Order by:') ?></label>
            <select id="<?php echo $this->get_Field_Id('orderby') ?>" name="<?php echo $this->get_Field_Name('orderby') ?>" class="widefat">
                <option value="title" <?php selected($options->orderby, 'title') ?>><?php I18n::_e('Title') ?></option>
                <option value="ID" <?php selected($options->orderby, 'ID') ?>>ID</option>
                <option value="author" <?php selected($options->orderby, 'author') ?>><?php I18n::_e('Author') ?></option>
                <option value="date" <?php selected($options->orderby, 'date') ?>><?php I18n::_e('Date') ?></option>
                <option value="modified" <?php selected($options->orderby, 'modified') ?>><?php I18n::_e('Last modification') ?></option>
                <option value="rand" <?php selected($options->orderby, 'rand') ?>><?php I18n::_e('Random') ?></option>
                <option value="comment_count" <?php selected($options->orderby, 'comment_count') ?>><?php I18n::_e('Comment Count') ?></option>
                <option value="menu_order" <?php selected($options->orderby, 'menu_order') ?>><?php I18n::_e('Menu Order') ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_Field_Id('order') ?>"><?php I18n::_e('Order:') ?></label>
            <select id="<?php echo $this->get_Field_Id('order') ?>" name="<?php echo $this->get_Field_Name('order') ?>" class="widefat">
                <option value="ASC" <?php selected($options->order, 'ASC') ?>><?php I18n::_e('Ascending') ?></option>
                <option value="DESC" <?php selected($options->order, 'DESC') ?>><?php I18n::_e('Descending') ?></option>
            </select>
        </p>
<?php
    }

    public function Widget($widget, $options): void
    {
        # Load widget args
        setType($widget, 'OBJECT');

        # Load options
        $this->loadOptions($options);

        # Load widget title
        $widget->title = apply_Filters('widget_title', $options->title, (array) $options, $this->id_base);

        # Load the Query
        $widget->items = new WP_Query([
            'post_type' => PostType::post_type_name,
            'orderby' => $options->orderby,
            'order' => $options->order,
            'nopaging' => (bool) empty($options->number_of_items),
            'posts_per_page' => (int) $options->number_of_items,
            'ignore_sticky_posts' => true
        ]);

        if ($widget->items->have_Posts()) {
            # Display Widget
            echo $widget->before_widget;
            !empty($widget->title) && print($widget->before_title . $widget->title . $widget->after_title);
            echo Template::load('encyclopedia-items-widget.php', ['widget' => $widget, 'options' => $options]);
            echo $widget->after_widget;

            # Reset Post data
            WP_Reset_Postdata();
        }
    }
}

Items_Widget::registerWidget();
