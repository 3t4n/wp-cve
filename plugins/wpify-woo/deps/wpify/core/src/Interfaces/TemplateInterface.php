<?php

namespace WpifyWooDeps\Wpify\Core\Interfaces;

interface TemplateInterface
{
    /**
     * Renders the template and returns the result.
     *
     * @param string      $slug The slug name for the generic template.
     * @param string|null $name The name of the specialised template.
     * @param array       $args Additional arguments passed to the template.
     *
     * @return string
     */
    public function render(string $slug, string $name = null, array $args = array()) : string;
    /**
     * Renders the template and prints the result.
     *
     * @param string      $slug The slug name for the generic template.
     * @param string|null $name The name of the specialised template.
     * @param array       $args Additional arguments passed to the template.
     */
    public function print(string $slug, string $name = null, array $args = array()) : void;
}
