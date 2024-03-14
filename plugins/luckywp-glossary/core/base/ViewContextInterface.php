<?php

namespace luckywp\glossary\core\base;

interface ViewContextInterface
{

    /**
     * @param string $view
     * @return array
     */
    public function getViewFiles($view);
}
