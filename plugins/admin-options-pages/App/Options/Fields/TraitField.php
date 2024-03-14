<?php

namespace AOP\App\Options\Fields;

trait TraitField
{
    private function description($description, $italic = false)
    {
        return $description ? sprintf(
            '<p %s>%s</p>',
            $italic ? 'class="description"' : '',
            stripslashes($description)
        ) : '';
    }
}
