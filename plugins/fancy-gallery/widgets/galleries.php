<?php

namespace WordPress\Plugin\GalleryManager;

use WP_Widget, WP_Query;

class Galleries_Widget extends WP_Widget
{
    public function __construct()
    {
        # Setup the Widget data
        parent::__construct(
            'galleries',
            I18n::__('Galleries'),
            ['description' => I18n::__('Displays some of your galleries.')]
        );
    }

    public static function registerWidget(): void
    {
        if (doing_Action('widgets_init'))
            register_Widget(static::class);
        else
            add_Action('widgets_init', [static::class, __FUNCTION__]);
    }

    public function getDefaultOptions(): array
    {
        # Default settings
        return [
            'title' => I18n::__('Galleries'),
            'number' => 5,
            'orderby' => 'date',
            'order' => 'DESC'
        ];
    }

    public function loadOptions(array &$arr_options): void
    {
        $arr_options = Array_Filter($arr_options);
        $arr_options = Array_Merge($this->getDefaultOptions(), $arr_options);
        setType($arr_options, 'OBJECT');
    }

    public function Form($options): void
    {
        $this->loadOptions($options);
        ?>
        <p>
            <label for="<?php echo $this->get_Field_Id('title') ?>"><?php I18n::_e('Title:') ?></label>
            <input type="text" id="<?php echo $this->get_Field_Id('title') ?>" name="<?php echo $this->get_Field_Name('title') ?>" value="<?php echo esc_Attr($options->title) ?>" class="widefat">
            <small><?php I18n::_e('Leave blank to use the widget default title.') ?></small>
        </p>

        <p>
            <label for="<?php echo $this->get_Field_Id('number') ?>"><?php I18n::_e('Number of galleries:') ?></label>
            <input type="number" id="<?php echo $this->get_Field_Id('number') ?>" name="<?php echo $this->get_Field_Name('number') ?>" value="<?php echo esc_Attr($options->number) ?>" min="1" step="1" max="<?php echo PHP_INT_MAX ?>" class="widefat">
        </p>

        <p>
            <label for="<?php echo $this->get_Field_Id('orderby') ?>"><?php I18n::_e('Order by:') ?></label>
            <select id="<?php echo $this->get_Field_Id('orderby') ?>" name="<?php echo $this->get_Field_Name('orderby') ?>" class="widefat">
                <option value="title" <?php selected($options->orderby, 'title') ?>><?php I18n::_e('Title') ?></option>
                <option value="date" <?php selected($options->orderby, 'date') ?>><?php I18n::_e('Date') ?></option>
                <option value="modified" <?php selected($options->orderby, 'modified') ?>><?php I18n::_e('Modified') ?></option>
                <option value="rand" <?php selected($options->orderby, 'rand') ?>><?php I18n::_e('Randomly') ?></option>
                <option value="comment_count" <?php selected($options->orderby, 'comment_count') ?>><?php I18n::_e('Number of comments') ?></option>
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

        # query galleries
        $options->galleries = new WP_Query(array(
            'post_type' => PostType::post_type_name,
            'posts_per_page' => $options->number,
            'has_password' => false,
            'orderby' => $options->orderby,
            'order' => $options->order
        ));

        if ($options->galleries->have_Posts()){
            # generate widget title
            $widget->title = apply_Filters('widget_title', $options->title, (array) $options, $this->id_base);
    
            # Display Widget
            echo Template::load('galleries-widget', [
                'widget' => $widget,
                'options' => $options
            ]);
    
            # Reset the global post and query vars
            WP_Reset_Postdata();
        }
    }
}

Galleries_Widget::registerWidget();
