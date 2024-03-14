<?php

namespace luckywp\glossary\core\admin;

use luckywp\glossary\core\admin\helpers\AdminUrl;
use luckywp\glossary\core\base\Controller;
use luckywp\glossary\core\Core;

class AdminController extends Controller
{

    private $_action;

    public function init()
    {
        parent::init();
        if (AdminUrl::isPage($this->id)) {
            $methodName = 'handle' . ucfirst($this->getAction());
            if (method_exists($this, $methodName)) {
                add_action('wp_loaded', [$this, $methodName]);
            }
        }
    }

    public function getAction()
    {
        if ($this->_action === null) {
            $this->_action = Core::$plugin->request->get('action', 'index');
        }
        return $this->_action;
    }

    public static function router()
    {
        /** @var self $controller */
        $controller = static::getInstance();
        $methodName = 'action' . ucfirst($controller->getAction());
        if (!method_exists($controller, $methodName)) {
            $controller->notAllowed();
        }
        $controller->$methodName();
    }

    public function notAllowed()
    {
        wp_die(esc_html__('Sorry, you are not allowed to access this page.', 'luckywp-glossary'), 403);
    }
}
