<?php

namespace Hyperpay\Gateways\App;

use Hyperpay\Gateways\Main;

final class View
{
    public static function render($name, $params = [])
    {
        $loader = new \Twig\Loader\FilesystemLoader(Main::ROOT_PATH . "/assets/templates");
        $twig = new \Twig\Environment($loader);
        echo $twig->render($name, $params);
        return;
    }
}
