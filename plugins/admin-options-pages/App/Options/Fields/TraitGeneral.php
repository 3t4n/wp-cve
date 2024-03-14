<?php

namespace AOP\App\Options\Fields;

trait TraitGeneral
{
    /**
     * @param      $description
     * @param bool $italic
     *
     * @return string
     */
    private function description($description, $italic = false)
    {
        return $description ? sprintf(
            '<p %s>%s</p>',
            $italic ? 'class="description"' : '',
            stripslashes($description)
        ) : '';
    }
}
