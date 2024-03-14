<?php

namespace src\api;

class WF_Routes{

    /**
     * Register callback route
     */
    public static function register_routes(){
        $callback_controller = new WF_Organisation_Callback_Controller();
        $callback_controller->register_routes();
    }
}
