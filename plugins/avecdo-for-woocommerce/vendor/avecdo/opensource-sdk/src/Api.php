<?php

namespace Avecdo\SDK;

use Avecdo\SDK\Classes\Auth;
use Avecdo\SDK\Classes\Response;
use Avecdo\SDK\POPO\KeySet;

abstract class Api
{
    /**
     * @var
     */
    protected $context;

    /**
     * @param $context
     * @return $this
     */
    public function bindContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return static
     */
    public static function make()
    {
        return new static();
    }

    public function routeRequest(KeySet $keySet)
    {
        if(!Constants::DEBUG_MODE) {
            Auth::enable($keySet);
        }

        $action = isset($_GET['action']) ? $_GET['action'] : '';
        $args = $_GET;


        $this->getRoute($action, $args);
    }

    /**
     * @param $action
     * @param $args
     * @return void
     */
    private function getRoute($action, $args)
    {
        switch ($action) {
            case 'product':

                if(!isset($args['page']) || !is_numeric($args['page'])) {
                    $args['page'] = 1;
                }

                if(!isset($args['limit']) || !is_numeric($args['limit'])) {
                    $args['limit'] = 10000;
                }

                if(!isset($args['last_run'])) {
                    $args['last_run'] = null;
                }

                Response::asJson($this->products($args['page'], $args['limit'], $args['last_run']));
                break;

            case 'category':

                Response::asJson($this->categories());
                break;

            case 'shop':

                Response::asJson($this->shop());
                break;

            default:

                Response::asJsonError('Invalid Request', 400);
                break;
        }
        return null;
    }

    /**
     * @param $page
     * @param $limit
     * @param $lastRun
     * @return array
     */
    public abstract function products($page, $limit, $lastRun);

    /**
     * @return array
     */
    public abstract function categories();

    /**
     * @return array
     */
    public abstract function shop();
}
