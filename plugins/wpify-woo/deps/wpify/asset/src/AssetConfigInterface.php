<?php

namespace WpifyWooDeps\Wpify\Asset;

interface AssetConfigInterface
{
    const TYPE_SCRIPT = 'script';
    const TYPE_STYLE = 'style';
    public function get_config_object() : object;
    public function get_is_admin() : bool;
    public function set_is_admin(bool $is_admin) : self;
    public function get_is_login() : bool;
    public function set_is_login(bool $is_login) : self;
    public function get_do_enqueue() : callable;
    public function set_do_enqueue(callable $do_enqueue) : self;
    public function get_handle() : string;
    public function set_handle(string $handle) : self;
    public function get_src() : string;
    public function set_src(string $src) : self;
    public function get_dependencies() : array;
    public function set_dependencies(array $dependencies) : self;
    public function get_version() : ?string;
    public function set_version(string $version) : self;
    public function get_in_footer() : bool;
    public function set_in_footer(bool $in_footer) : self;
    public function get_type() : ?string;
    public function set_type(string $type) : self;
    public function get_media() : ?string;
    public function set_media(string $media) : self;
    public function get_variables();
    public function set_variables($variables) : self;
    public function get_script_before() : ?string;
    public function set_script_before(string $script_before) : self;
    public function get_script_after() : ?string;
    public function set_script_after(string $script_after) : self;
    public function get_text_domain() : ?string;
    public function set_text_domain(string $text_domain) : self;
    public function get_translations_path() : ?string;
    public function set_translations_path(string $translations_path) : self;
    public function get_auto_register() : bool;
    public function set_auto_register(bool $auto_register) : self;
}
