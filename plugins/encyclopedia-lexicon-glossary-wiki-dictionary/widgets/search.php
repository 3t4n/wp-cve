<?php

namespace WordPress\Plugin\Encyclopedia;

use WP_Widget;

class Search_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'encyclopedia_search',
            sprintf(I18n::__('%s Search'), PostTypeLabels::getEncyclopediaType()),
            ['description' => sprintf(I18n::__('A search form for your %s.'), PostTypeLabels::getItemPluralName())]
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
            'title' => '',
            'search_mode' => 'normal'
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

        <h4><?php I18n::_e('Search mode:') ?></h4>

        <p>
            <label for="<?php echo $this->get_Field_Id('search_mode_normal') ?>">
                <input type="radio" id="<?php echo $this->get_Field_Id('search_mode_normal') ?>" name="<?php echo $this->get_Field_Name('search_mode') ?>" value="normal" <?php checked($options->search_mode, 'normal') ?>>
                <strong><?php I18n::_e('Normal') ?></strong>: <?php printf(I18n::__('Will match all %s which contains the search phrase in the title or the content field.'), PostTypeLabels::getItemPluralName()) ?>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_Field_Id('search_mode_prefix') ?>">
                <input type="radio" id="<?php echo $this->get_Field_Id('search_mode_prefix') ?>" name="<?php echo $this->get_Field_Name('search_mode') ?>" value="prefix" <?php checked($options->search_mode, 'prefix') ?>>
                <strong><?php I18n::_e('Prefix') ?></strong>: <?php printf(I18n::__('Will match all %s which title field starts with the search phrase.'), PostTypeLabels::getItemPluralName()) ?>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_Field_Id('search_mode_exact') ?>">
                <input type="radio" id="<?php echo $this->get_Field_Id('search_mode_exact') ?>" name="<?php echo $this->get_Field_Name('search_mode') ?>" value="exact" <?php checked($options->search_mode, 'exact') ?>>
                <strong><?php I18n::_e('Exact') ?></strong>: <?php printf(I18n::__('Will match all %s which title or content field matches exactly the search phrase.'), PostTypeLabels::getItemPluralName()) ?>
            </label>
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

        # Display Widget
        echo $widget->before_widget;
        !empty($widget->title) && print($widget->before_title . $widget->title . $widget->after_title);
        echo Template::load('searchform-encyclopedia.php', [
            'widget' => $widget,
            'options' => $options
        ]);
        echo $widget->after_widget;
    }
}

Search_Widget::registerWidget();
