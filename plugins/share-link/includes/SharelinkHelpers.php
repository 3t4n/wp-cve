<?php

class SharelinkHelpers {
    public static function render($view, array $args = null) {
        if (!is_null($args)) {
            extract($args);
        }
        include_once __DIR__ . '/../views/sharelink-' . $view . '.php';
    }
}