<?php

namespace WordPress\Plugin\Encyclopedia;

use WP_Widget;

class Taxonomy_Cloud_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'encyclopedia_taxonomy_cloud',
            sprintf(I18n::__('%s Taxonomy Cloud'), PostTypeLabels::getEncyclopediaType()),
            ['description' => I18n::__('A cloud of your most used taxonomy terms.')]
        );
    }

    public static function registerWidget(): void
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
            'title'    => '',
            'taxonomy' => false,
            'number'   => null,
            'orderby'  => 'name',
            'order'    => 'RAND'
        ];
    }

    public function loadOptions(array &$options): void
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
            <input type="text" id="<?php echo $this->get_Field_Id('title') ?>" name="<?php echo $this->get_Field_Name('title') ?>" value="<?php echo esc_Attr($options->title) ?>" class="widefat">
        </p>

        <p>
            <label for="<?php echo $this->get_Field_Id('taxonomy') ?>"><?php I18n::_e('Taxonomy:') ?></label>
            <select id="<?php echo $this->get_Field_Id('taxonomy') ?>" name="<?php echo $this->get_Field_Name('taxonomy') ?>" class="widefat">
                <?php foreach (get_Object_Taxonomies(PostType::post_type_name) as $taxonomy) : $taxonomy = get_Taxonomy($taxonomy) ?>
                    <option value="<?php echo $taxonomy->name ?>" <?php selected($options->taxonomy, $taxonomy->name) ?>><?php echo esc_Attr($taxonomy->labels->name) ?></option>
                <?php endforeach ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_Field_Id('number') ?>"><?php I18n::_e('Number:') ?></label>
            <input type="number" id="<?php echo $this->get_Field_Id('number') ?>" name="<?php echo $this->get_Field_Name('number') ?>" value="<?php echo esc_Attr($options->number) ?>" min="0" max="<?php echo PHP_INT_MAX ?>" step="1" class="widefat">
            <small><?php I18n::_e('Leave blank to show all.') ?></small>
        </p>

        <p>
            <label for="<?php echo $this->get_Field_Id('orderby') ?>"><?php I18n::_e('Order by:') ?></label>
            <select id="<?php echo $this->get_Field_Id('orderby') ?>" name="<?php echo $this->get_Field_Name('orderby') ?>" class="widefat">
                <option value="name" <?php selected($options->orderby, 'name') ?>><?php I18n::_e('Name') ?></option>
                <option value="count" <?php selected($options->orderby, 'count') ?>><?php printf(I18n::__('Number of %s'), PostTypeLabels::getItemPluralName()) ?></option>
                <option value="id" <?php selected($options->orderby, 'id') ?>>ID</option>
                <option value="slug" <?php selected($options->orderby, 'slug') ?>><?php I18n::_e('URL Slug') ?></option>
                <option value="description" <?php selected($options->orderby, 'description') ?>><?php I18n::_e('Description') ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_Field_Id('order') ?>"><?php I18n::_e('Order:') ?></label>
            <select id="<?php echo $this->get_Field_Id('order') ?>" name="<?php echo $this->get_Field_Name('order') ?>" class="widefat">
                <option value="RAND" <?php selected($options->order, 'RAND') ?>><?php I18n::_e('Random') ?></option>
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

        # Check if the Taxonomy is alive
        if (Taxonomy_Exists($options->taxonomy)) {
            # Display Widget
            echo $widget->before_widget;

            !empty($widget->title) && print($widget->before_title . $widget->title . $widget->after_title);

            $cloud_parameters = [
                'taxonomy'   => $options->taxonomy,
                'number'     => (int) $options->number, # "0" means no limit; empty string and (bool) false will result in default limit
                'order'      => $options->order,
                'orderby'    => $options->orderby,
            ];

            echo '<ul class="taxonomy-cloud">';
            WP_Tag_Cloud($cloud_parameters);
            echo '</ul>';

            echo $widget->after_widget;
        }
    }
}

Taxonomy_Cloud_Widget::registerWidget();
