<?php

namespace WpifyWooDeps\Wpify\Asset;

class AssetConfig implements AssetConfigInterface
{
    /** @var bool */
    private $is_admin = \false;
    /** @var bool */
    private $is_login = \false;
    /** @var callable */
    private $do_enqueue = '__return_true';
    /** @var string */
    private $handle;
    /** @var string */
    private $src;
    /** @var string[] */
    private $dependencies = array();
    /** @var ?string */
    private $version;
    /** @var boolean */
    private $in_footer = \false;
    /** @var ?string */
    private $type;
    /** @var ?string */
    private $media;
    /** @var array|callable */
    private $variables;
    /** @var string */
    private $script_before;
    /** @var string */
    private $script_after;
    /** @var string */
    private $text_domain;
    /** @var string */
    private $translations_path;
    /** @var bool */
    private $auto_register = \true;
    /**
     * Creates a configuration object
     *
     * @param array|object|AssetConfigInterface|null $args
     */
    public function __construct($args = null)
    {
        if (\is_array($args)) {
            $args = (object) $args;
        }
        if ($args instanceof AssetConfigInterface) {
            $args = $args->get_config_object();
        }
        if (\is_object($args)) {
            if (!empty($args->src)) {
                $this->src = $args->src;
                $this->handle = $args->handle ?? $this->generate_handle($args->src);
                $this->type = $args->type ?? $this->get_file_type($args->src);
            }
            $this->is_admin = $args->is_admin ?? $this->is_admin;
            $this->is_login = $args->is_login ?? $this->is_login;
            $this->do_enqueue = $args->do_enqueue ?? $this->do_enqueue;
            $this->handle = $args->handle ?? $this->handle;
            $this->dependencies = $args->dependencies ?? $this->dependencies;
            $this->version = $args->version ?? $this->version;
            $this->in_footer = $args->in_footer ?? $this->in_footer;
            $this->media = $args->media ?? $this->media;
            $this->type = $args->type ?? $this->type;
            $this->variables = $args->variables ?? $this->variables;
            $this->script_before = $args->script_before ?? $this->script_before;
            $this->script_after = $args->script_after ?? $this->script_after;
            $this->text_domain = $args->text_domain ?? $this->text_domain;
            $this->translations_path = $args->translations_path ?? $this->translations_path;
            $this->auto_register = $args->auto_register ?? $this->auto_register;
        }
    }
    public function get_config_object() : object
    {
        return (object) array('is_admin' => $this->is_admin, 'is_login' => $this->is_login, 'do_enqueue' => $this->do_enqueue, 'handle' => $this->handle, 'src' => $this->src, 'dependencies' => $this->dependencies, 'version' => $this->version, 'in_footer' => $this->in_footer, 'type' => $this->type, 'media' => $this->media, 'variables' => $this->variables, 'script_before' => $this->script_before, 'script_after' => $this->script_after, 'text_domain' => $this->text_domain, 'translations_path' => $this->translations_path, 'auto_register' => $this->auto_register);
    }
    private function generate_handle($src) : string
    {
        return sanitize_title(\pathinfo($src, \PATHINFO_FILENAME) . '-' . \md5($src));
    }
    private function get_file_type($src) : ?string
    {
        $extension = \current(\explode('?', \pathinfo($src, \PATHINFO_EXTENSION)));
        if ($extension === 'js') {
            return self::TYPE_SCRIPT;
        } else {
            if ($extension === 'css') {
                return self::TYPE_STYLE;
            }
        }
        return null;
    }
    public function get_is_admin() : bool
    {
        return $this->is_admin;
    }
    public function set_is_admin(bool $is_admin) : AssetConfigInterface
    {
        $this->is_admin = $is_admin;
        return $this;
    }
    public function get_is_login() : bool
    {
        return $this->is_login;
    }
    public function set_is_login(bool $is_login) : AssetConfigInterface
    {
        $this->is_login = $is_login;
        return $this;
    }
    public function get_do_enqueue() : callable
    {
        return $this->do_enqueue;
    }
    public function set_do_enqueue(callable $do_enqueue) : AssetConfigInterface
    {
        $this->do_enqueue = $do_enqueue;
        return $this;
    }
    public function get_handle() : string
    {
        return $this->handle;
    }
    public function set_handle(string $handle) : AssetConfigInterface
    {
        $this->handle = $handle;
        return $this;
    }
    public function get_src() : string
    {
        return $this->src;
    }
    public function set_src(string $src) : AssetConfigInterface
    {
        $this->src = $src;
        if (empty($this->handle)) {
            $this->handle = $this->generate_handle($src);
        }
        if (empty($this->type)) {
            $this->type = $this->get_file_type($src);
        }
        return $this;
    }
    public function get_dependencies() : array
    {
        return $this->dependencies;
    }
    public function set_dependencies(array $dependencies) : AssetConfigInterface
    {
        $this->dependencies = $dependencies;
        return $this;
    }
    public function merge_dependencies(array $dependencies = array()) : AssetConfigInterface
    {
        $this->dependencies = \array_unique(\array_merge($this->dependencies, $dependencies));
        return $this;
    }
    public function get_version() : ?string
    {
        return $this->version;
    }
    public function set_version(string $version) : AssetConfigInterface
    {
        $this->version = $version;
        return $this;
    }
    public function get_in_footer() : bool
    {
        return $this->in_footer;
    }
    public function set_in_footer(bool $in_footer) : AssetConfigInterface
    {
        $this->in_footer = $in_footer;
        return $this;
    }
    public function get_type() : ?string
    {
        return $this->type;
    }
    public function set_type(string $type) : AssetConfigInterface
    {
        $this->type = $type;
        return $this;
    }
    public function get_media() : ?string
    {
        return $this->media;
    }
    public function set_media(string $media) : AssetConfigInterface
    {
        $this->media = $media;
        return $this;
    }
    public function get_variables()
    {
        return $this->variables;
    }
    public function set_variables($variables) : AssetConfigInterface
    {
        $this->variables = $variables;
        return $this;
    }
    public function get_script_before() : ?string
    {
        return $this->script_before;
    }
    public function set_script_before(string $script_before) : AssetConfigInterface
    {
        $this->script_before = $script_before;
        return $this;
    }
    public function get_script_after() : ?string
    {
        return $this->script_after;
    }
    public function set_script_after(string $script_after) : AssetConfigInterface
    {
        $this->script_after = $script_after;
        return $this;
    }
    public function get_text_domain() : ?string
    {
        return $this->text_domain;
    }
    public function set_text_domain(string $text_domain) : AssetConfigInterface
    {
        $this->text_domain = $text_domain;
        return $this;
    }
    public function get_translations_path() : ?string
    {
        return $this->translations_path;
    }
    public function set_translations_path(string $translations_path) : AssetConfigInterface
    {
        $this->translations_path = $translations_path;
        return $this;
    }
    public function get_auto_register() : bool
    {
        return $this->auto_register;
    }
    public function set_auto_register(bool $auto_register) : AssetConfigInterface
    {
        $this->auto_register = $auto_register;
        return $this;
    }
}
