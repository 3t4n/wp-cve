<?php

namespace Shop_Ready\base\rest;

Abstract class Base{

    public $namespace = 'shop-ready/v1';
    public $config_array = [

    ];

    /**
     * service initializer
     * @return void
     * @since 1.0
     */
    abstract protected function register();
  
 }