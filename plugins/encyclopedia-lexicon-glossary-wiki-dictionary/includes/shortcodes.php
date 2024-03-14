<?php

namespace WordPress\Plugin\Encyclopedia;

abstract class Shortcodes
{

    public static function init(): void
    {
        add_Shortcode('encyclopedia_related_items', [static::class, 'Related_Items']);
    }

    public static function Related_Items($attributes = [])
    {
        $attributes = is_Array($attributes) ? $attributes : [];

        $attributes = Array_Merge([
            'number' => 5
        ], $attributes);

        $related_items = PostRelations::getTermRelatedItems($attributes);

        return Template::load('encyclopedia-related-items.php', [
            'attributes' => $attributes,
            'related_items' => $related_items
        ]);
    }
}

Shortcodes::init();
