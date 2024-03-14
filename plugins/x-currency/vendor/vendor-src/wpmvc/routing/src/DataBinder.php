<?php

namespace XCurrency\WpMVC\Routing;

class DataBinder
{
    protected string $namespace;
    protected string $version = '';
    public function set_namespace(string $namespace)
    {
        $this->namespace = $namespace;
    }
    public function set_version(string $version)
    {
        $this->version = $version;
    }
    public function get_namespace() : string
    {
        return $this->namespace;
    }
    public function get_version() : string
    {
        return $this->version;
    }
}
