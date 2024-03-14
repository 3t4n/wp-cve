<?php

namespace WordPress\Plugin\GalleryManager;

use WP_Widget;

class Random_Images_Widget extends WP_Widget
{
    public function __construct()
    {
        # Setup the Widget data
        parent::__construct(
            'random-gallery-images',
            I18n::__('Random Images'),
            ['description' => I18n::__('Displays some random images from your galleries.')]
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
            'title' => I18n::__('Random Images'),
            'number_of_images' => 12,
            'columns' => 3,
            'thumb_size' => 'thumbnail',
        ];
    }

    public function loadOptions(array &$arr_options): void
    {
        $arr_options = Array_Filter($arr_options);
        $arr_options = Array_Merge($this->getDefaultOptions(), $arr_options);
        setType($arr_options, 'OBJECT');
    }

    public function getRandomGalleries(int $count): array
    {
        $arr_galleries = get_Posts([
            'post_type' => PostType::post_type_name,
            'posts_per_page' => $count,
            'has_password' => false,
            'orderby' => 'rand'
        ]);

        return $arr_galleries;
    }

    public function getRandomImages(int $count): array
    {
        $arr_random_galleries = $this->getRandomGalleries($count);
        $arr_random_gallery_ids = Array_Map(function ($gallery) {
            return $gallery->ID;
        }, $arr_random_galleries);

        $arr_images = get_Posts(array(
            'post_parent__in' => $arr_random_gallery_ids,
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'posts_per_page' => $count,
            'orderby' => 'rand',
        ));

        return $arr_images;
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
            <label for="<?php echo $this->get_Field_Id('number_of_images') ?>"><?php I18n::_e('Number of images:') ?></label>
            <input type="number" id="<?php echo $this->get_Field_Id('number_of_images') ?>" name="<?php echo $this->get_Field_Name('number_of_images') ?>" value="<?php echo esc_Attr($options->number_of_images) ?>" min="1" step="1" max="<?php echo PHP_INT_MAX ?>" class="widefat">
        </p>

        <p>
            <label for="<?php echo $this->get_Field_Id('columns') ?>"><?php I18n::_e('Columns:') ?></label>
            <select name="<?php echo $this->get_Field_Name('columns') ?>" id="<?php echo $this->get_Field_Id('columns') ?>" class="widefat">
                <?php for ($columns = 1; $columns < 10; $columns++) : ?>
                    <option value="<?php echo $columns ?>" <?php selected($options->columns, $columns) ?>><?php echo $columns ?></option>
                <?php endfor ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_Field_Id('thumb_size') ?>"><?php I18n::_e('Thumbnail size:') ?></label>
            <?php echo Thumbnails::getDropdown([
                'name' => $this->get_Field_Name('thumb_size'),
                'id' => $this->get_Field_Id('thumb_size'),
                'selected' => $options->thumb_size,
                'class' => 'widefat'
            ]) ?>
        </p>
        <?php
    }

    public function Widget($widget, $options): void
    {
        # Load widget args
        setType($widget, 'OBJECT');

        # Load options
        $this->loadOptions($options);

        # Get random images
        $arr_images = $this->getRandomImages($options->number_of_images);

        if (!empty($arr_images)){
            $options->image_ids = Array_Map(function ($image) {
                return $image->ID;
            }, $arr_images);

            # generate widget title
            $widget->title = apply_Filters('widget_title', $options->title, (array) $options, $this->id_base);
    
            # Add attachment link filter
            add_Filter('wp_get_attachment_link', [static::class, 'filterAttachmentLink'], 10, 2);
    
            # Display Widget
            echo Template::load('random-images-widget', [
                'widget' => $widget,
                'options' => $options
            ]);
    
            # Remove attachment link filter
            remove_Filter('wp_get_attachment_link', [static::class, 'filterAttachmentLink'], 10, 2);
        }
    }

    public static function filterAttachmentLink(string $link, int $attachment_id): string
    {
        if (Post::isGalleryImage($attachment_id)) {
            $image = get_Post($attachment_id);
            $gallery = get_Post($image->post_parent);
            $gallery_name = esc_Attr($gallery->post_title);
            $gallery_url = esc_Attr(get_Permalink($gallery->ID));
            $link = Str_Replace('<a ', "<a data-gallery-name='{$gallery_name}' data-gallery-url='{$gallery_url}' ", $link);
        }

        return $link;
    }
}

Random_Images_Widget::registerWidget();
