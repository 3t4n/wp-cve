<?php

namespace WordPress\Plugin\GalleryManager;

use WP_Widget;

class Taxonomies_Widget extends WP_Widget
{
    public function __construct()
    {
        # Setup the Widget data
        parent::__construct(
            'gallery-taxonomies',
            I18n::__('Gallery Taxonomies'),
            ['description' => I18n::__('Displays your gallery taxonomies like categories, tags, events, photographers, etc.')]
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
            'title'      => I18n::__('Gallery Taxonomies'),
            'taxonomy'   => false,
            'number'     => null,
            'show_count' => false,
            'orderby'    => 'name',
            'order'      => 'ASC',
            #'exclude'    => false
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
        # Load options
        $this->loadOptions($options);
        ?>

        <p>
            <label for="<?php echo $this->get_Field_Id('title') ?>"><?php I18n::_e('Title:') ?></label>
            <input type="text" id="<?php echo $this->get_Field_Id('title') ?>" name="<?php echo $this->get_Field_Name('title') ?>" value="<?php echo esc_Attr($options->title) ?>" class="widefat">
            <small><?php I18n::_e('Leave blank to use the widget default title.') ?></small>
        </p>

        <p>
            <label for="<?php echo $this->get_Field_Id('taxonomy') ?>"><?php I18n::_e('Taxonomy:') ?></label>
            <select id="<?php echo $this->get_Field_Id('taxonomy') ?>" name="<?php echo $this->get_Field_Name('taxonomy') ?>" class="widefat">
                <?php foreach (get_Object_Taxonomies(PostType::post_type_name) as $taxonomy) : $taxonomy = get_Taxonomy($taxonomy) ?>
                    <option value="<?php echo $taxonomy->name ?>" <?php selected($options->taxonomy, $taxonomy->name) ?>><?php echo HTMLSpecialChars($taxonomy->labels->name) ?></option>
                <?php endforeach ?>
            </select><br>
            <small><?php I18n::_e('Please choose the taxonomy the widget should display.') ?></small>
        </p>

        <p>
            <label for="<?php echo $this->get_Field_Id('number') ?>"><?php I18n::_e('Number of terms:') ?></label>
            <input type="number" id="<?php echo $this->get_Field_Id('number') ?>" name="<?php echo $this->get_Field_Name('number') ?>" value="<?php echo esc_Attr($options->number) ?>" min="1" step="1" max="<?php echo PHP_INT_MAX ?>" class="widefat">
            <small><?php I18n::_e('Leave blank to show all.') ?></small>
        </p>

        <?php /*
            <p>
            <label for="<?php echo $this->get_Field_Id('exclude') ?>"><?php I18n::_e('Exclude:') ?></label>
            <input type="text" value="<?php echo esc_Attr($options->exclude) ?>" name="<?php echo $this->get_Field_Name('exclude') ?>" id="<?php echo $this->get_Field_Id('exclude') ?>" class="widefat">
            <small><?php I18n::_e('Term IDs, separated by commas.') ?></small>
            </p>
        */ ?>

        <p>
            <input type="checkbox" id="<?php echo $this->get_Field_Id('show_count') ?>" name="<?php echo $this->get_Field_Name('show_count') ?>" value="1" <?php checked($options->show_count) ?>>
            <label for="<?php echo $this->get_Field_Id('show_count') ?>"><?php I18n::_e('Show gallery counts.') ?></label>
        </p>

        <p>
            <label for="<?php echo $this->get_Field_Id('orderby') ?>"><?php I18n::_e('Order by:') ?></label>
            <select id="<?php echo $this->get_Field_Id('orderby') ?>" name="<?php echo $this->get_Field_Name('orderby') ?>" class="widefat">
                <option value="name" <?php selected($options->orderby, 'name') ?>><?php I18n::_e('Name') ?></option>
                <option value="count" <?php selected($options->orderby, 'count') ?>><?php I18n::_e('Gallery count') ?></option>
                <option value="ID" <?php selected($options->orderby, 'ID') ?>>ID</option>
                <option value="slug" <?php selected($options->orderby, 'slug') ?>><?php I18n::_e('Slug') ?></option>
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

        # Check if the Taxonomy is alive
        if (Taxonomy_Exists($options->taxonomy)){
            # generate widget title
            $widget->title = apply_Filters('widget_title', $options->title, (array) $options, $this->id_base);
    
            # Display Widget
            echo Template::load('gallery-taxonomies-widget', [
                'widget' => $widget,
                'options' => $options
            ]);            
        }
    }
}

Taxonomies_Widget::registerWidget();
