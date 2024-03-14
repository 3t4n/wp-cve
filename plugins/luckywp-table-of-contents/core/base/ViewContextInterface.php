<?php

namespace luckywp\tableOfContents\core\base;

interface ViewContextInterface
{

    /**
     * @param string $view
     * @return array
     */
    public function getViewFiles($view);
}
