<?php

namespace AForms\Shell;

use Aura\Payload\Payload;
use Aura\Di\ContainerBuilder;

class Dispatcher 
{
    protected $container;
    protected $urlHelper;
    protected $adminPages;  // page -> {pointer, types, template}[]
    protected $shortcodes;  // name -> {pointer, types, template}[]
    protected $ajaxes;      // action -> {pointer, types}[]
    protected $hooks;       // name -> {pointer, types}[]

    public static function newInstance($configs) 
    {
        $builder = new ContainerBuilder();
        $container = $builder->newConfiguredInstance($configs);
        return new Dispatcher($container, $container->get('urlHelper'));
    }

    public function __construct($container, $urlHelper) 
    {
        $this->container = $container;
        $this->urlHelper = $urlHelper;
        $this->adminPages = array();
        $this->shortcodes = array();
        $this->ajaxes = array();
        $this->hooks = array();
    }

    public function getService($name) 
    {
        return $this->container->get($name);
    }

    protected function call($pointer, $args) 
    {
        if ($pointer) {
            $object = $this->container->newInstance($pointer);
            return call_user_func_array($object, $args);
        } else {
            return null;
        }
    }

    public function addAdmin($page, $template, $types = null, $pointer = null) 
    {
        if (is_null($types)) {
            $types = array();
        }
        if (! isset($this->adminPages[$page])) {
            $this->adminPages[$page] = array();
        }
        $this->adminPages[$page][] = (object)array('pointer' => $pointer, 'types' => $types, 'template' => 'admin/'.$template);
    }

    public function addShort($name, $template, $types = null, $pointer = null) 
    {
        if (is_null($types)) {
            $types = array();
        }
        if (! isset($this->shortcodes[$name])) {
            $this->shortcodes[$name] = array();
        }
        $this->shortcodes[$name][] = (object)array('pointer' => $pointer, 'template' => 'front/'.$template, 'types' => $types);
    }

    public function addAjax($action, $types = null, $pointer = null) 
    {
        if (is_null($types)) {
            $types = array();
        }
        if (! isset($this->ajaxes[$action])) {
            $this->ajaxes[$action] = array();
        }
        $this->ajaxes[$action][] = (object)array('pointer' => $pointer, 'types' => $types);
    }

    public function addHook($name, $template, $pointer = null) 
    {
        $types = array();
        $this->hooks[$name][] = (object)array('pointer' => $pointer, 'template' => $template, 'types' => $types);
    }

    public function install() 
    {
        $payload = new Payload();
        $args = array($payload);
        return $this->call('AForms\App\Admin\Install', $args);
    }

    public function restrictAccess() 
    {
        global $wp_query;

        $postId = get_queried_object_id();

        $payload = new Payload();
        $args = array($postId, $payload);
        $payload = $this->call('AForms\App\Front\Restrict', $args);
        if ($payload->getStatus() != 'SUCCESS') {
            $wp_query->init();
            $wp_query->set_404();
            status_header(404);
            nocache_headers();
            $path = get_query_template('404', array('index'));
            if ($path) {
                include($path);
            }
            exit;
        }
    }

    // browser GET.  there params, no inputs, outputs html
    public function adminPage() 
    {
        $page = $_REQUEST['page'];
        $path = isset($_REQUEST['path']) ? $_REQUEST['path'] : null;
        list($adminPage, $args) = $this->match($path, $this->adminPages[$page]);
        $pointer = $adminPage->pointer;
        $input = null;
        $payload = new Payload();
        $payload->setInput($input);
        $args[] = $input;
        $args[] = $payload;

        $payload = $this->call($pointer, $args);

        $r = $this->container->newInstance('AForms\Shell\HtmlResponder');
        $r->setEcho(true);
        $r($adminPage->template, $payload);
    }

    // browser GET.  there params, there inputs, outputs html
    public function shortcode($atts, $content, $name) 
    {
        if (isset($atts['path'])) {
            $path = $atts['path'];
            unset($atts['path']);
        } else {
            $path = null;
        }
        list($shortcode, $args) = $this->match($path, $this->shortcodes[$name]);
        $pointer = $shortcode->pointer;
        $input = (object)$atts;
        $payload = new Payload();
        $payload->setInput($input);
        $args[] = $input;
        $args[] = $payload;

        $payload = $this->call($pointer, $args);

        $r = $this->container->newInstance('AForms\Shell\HtmlResponder');
        $r->setEcho(false);
        $out = $r($shortcode->template, $payload);
        return $out;
    }

    protected function getAjaxInput() 
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST': 
            case 'PUT': 
            case 'PATCH': 
                if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
                    $input = json_decode(file_get_contents('php://input'));
                } else {
                    $input = null;
                }
                break;
            case 'GET': 
            case 'HEAD': 
            case 'DELETE': 
                $arr = $_GET;
                unset($arr['action']);
                unset($arr['path']);
                $input = json_decode(json_encode($arr), false);
                break;
            default: 
                $input = null;
        }
        return $input;
    }

    // ajax.  there params, there inputs, outputs json
    public function ajax() 
    {
        $action = $_REQUEST['action'];
        check_ajax_referer($action, $this->urlHelper->getNonceName());
        $path = isset($_REQUEST['path']) ? $_REQUEST['path'] : null;
        list($ajax, $args) = $this->match($path, $this->ajaxes[$action]);

        $pointer = $ajax->pointer;
        $input = $this->getAjaxInput();
        $payload = new Payload();
        $payload->setInput($input);
        $args[] = $input;
        $args[] = $payload;

        $payload = $this->call($pointer, $args);

        $rclass = 'AForms\Shell\JsonResponder';
        $r = $this->container->newInstance($rclass);
        $r->setEcho(true);
        $r($payload);
    }

    protected function hookX($input) 
    {
        $name = current_filter();
        if (! $name || empty($this->hooks[$name])) {
            // executing meta box
            $name = $input[1]['id'];
        }

        list($hook, $args_) = $this->match('', $this->hooks[$name]);
        $pointer = $hook->pointer;
        $payload = new Payload();
        $payload->setInput($input);
        $args = array();
        $args[] = $input;
        $args[] = $payload;

        $payload = $this->call($pointer, $args);

        if ($hook->template) {
            $r = $this->container->newInstance('AForms\Shell\HtmlResponder');
            $r->setEcho(true);
            $r($hook->template, $payload);
        } else {
            return $payload->getOutput();
        }
    }

    public function hook0() 
    {
        return $this->hookX(array());
    }

    public function hook1($input0) 
    {
        return $this->hookX(array($input0));
    }

    public function hook2($input0, $input1) 
    {
        return $this->hookX(array($input0, $input1));
    }

    public function hook3($input0, $input1, $input2) 
    {
        return $this->hookX(array($input0, $input1, $input2));
    }

    public function hook4($input0, $input1, $input2, $input3) 
    {
        return $this->hookX(array($input0, $input1, $input2, $input3));
    }

    public function hook5($input0, $input1, $input2, $input3, $input4) 
    {
        return $this->hookX(array($input0, $input1, $input2, $input3, $input4));
    }

    protected function match($path, $specs) 
    {
        foreach ($specs as $spec) {
            $params = $this->parsePath($path, $spec->types);
            if (! is_null($params)) {
                return array($spec, $params);
            }
        }
        $this->abort('no matching handler');
    }

    protected function parsePath($path, $types) 
    {
        if (! $path) {
            $params = array();
        } else {
            $params = explode('_', $path);
        }
        if (count($params) != count($types)) {
            // parameter count mismatch
            return null;
        }
        $len = count($params);
        for ($i = 0; $i < $len; $i++) {
            switch ($types[$i]) {
                case 'int': 
                    $params[$i] = intval($params[$i]);
                    break;
                case 'bool': 
                    $params[$i] = ($params[$i] == 'T') ? true : false;
                    break;
                case 'string': 
                    break;
                default: 
                    if ($params[$i] != $types[$i]) {
                        // keyword mismatch
                        return null;
                    }
                    break;
            }
        }
        return $params;
    }

    protected function abort($message) 
    {
        echo "ERROR: ".$message;
        wp_die();
    }
}