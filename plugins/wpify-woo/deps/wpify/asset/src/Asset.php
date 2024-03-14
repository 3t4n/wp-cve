<?php

namespace WpifyWooDeps\Wpify\Asset;

class Asset
{
    /** @var bool */
    private $is_done = \false;
    /** @var bool */
    private $is_registered = \false;
    /** @var AssetConfigInterface */
    private $config;
    public function __construct(AssetConfigInterface $config)
    {
        $this->config = $config;
        $this->init();
        $this->setup();
    }
    public function init()
    {
        if ($this->config->get_auto_register()) {
            if ($this->config->get_is_login()) {
                if (did_action('login_enqueue_scripts') || doing_action('login_enqueue_scripts')) {
                    $this->register();
                    $this->enqueue();
                } else {
                    add_action('login_enqueue_scripts', array($this, 'register'));
                    add_action('login_enqueue_scripts', array($this, 'enqueue'), 20);
                }
            } elseif ($this->config->get_is_admin()) {
                if (did_action('admin_enqueue_scripts') || doing_action('admin_enqueue_scripts')) {
                    $this->register();
                    $this->enqueue();
                } else {
                    add_action('admin_enqueue_scripts', array($this, 'register'));
                    add_action('admin_enqueue_scripts', array($this, 'enqueue'), 20);
                }
            } else {
                if (did_action('wp_enqueue_scripts') || doing_action('wp_enqueue_scripts')) {
                    $this->register();
                    $this->enqueue();
                } else {
                    add_action('wp_enqueue_scripts', array($this, 'register'));
                    add_action('wp_enqueue_scripts', array($this, 'enqueue'), 20);
                }
            }
        }
    }
    public function register()
    {
        if ($this->config->get_type() === AssetConfigInterface::TYPE_SCRIPT) {
            $this->is_registered = wp_register_script($this->config->get_handle(), $this->config->get_src(), $this->config->get_dependencies(), $this->config->get_version(), $this->config->get_in_footer());
            if (!empty($this->config->get_variables())) {
                $variables = $this->config->get_variables();
                if (\is_callable($variables)) {
                    $variables = $variables();
                }
                if (\is_array($variables)) {
                    $script = array();
                    foreach ($variables as $name => $value) {
                        $script[] = 'var ' . $name . '=' . wp_json_encode($value) . ';';
                    }
                    wp_add_inline_script($this->config->get_handle(), \join('', $script), 'before');
                }
            }
            if (!empty($this->config->get_script_before())) {
                wp_add_inline_script($this->config->get_handle(), $this->config->get_script_before(), 'before');
            }
            if (!empty($this->config->get_script_after())) {
                wp_add_inline_script($this->config->get_handle(), $this->config->get_script_after());
            }
            if (!empty($this->config->get_text_domain())) {
                wp_set_script_translations($this->config->get_handle(), $this->config->get_text_domain(), $this->config->get_translations_path());
            }
        } elseif ($this->config->get_type() === AssetConfigInterface::TYPE_STYLE) {
            $this->is_registered = wp_register_style($this->config->get_handle(), $this->config->get_src(), $this->config->get_dependencies(), $this->config->get_version(), $this->config->get_media());
        }
        if ($this->is_registered) {
            return $this->config->get_handle();
        }
        return $this->is_registered;
    }
    public function setup()
    {
    }
    public function enqueue()
    {
        if (!$this->is_registered) {
            $this->register();
        }
        if (\call_user_func($this->config->get_do_enqueue(), $this->config) && !$this->is_done && $this->is_registered) {
            if ($this->config->get_type() === AssetConfigInterface::TYPE_SCRIPT) {
                wp_enqueue_script($this->config->get_handle());
            } elseif ($this->config->get_type() === AssetConfigInterface::TYPE_STYLE) {
                wp_enqueue_style($this->config->get_handle());
            }
        }
    }
    public function print()
    {
        if (!$this->is_registered) {
            $this->register();
        }
        if ($this->is_registered && !$this->is_done) {
            if ($this->config->get_type() === AssetConfigInterface::TYPE_SCRIPT) {
                if (wp_scripts()->do_item($this->config->get_handle())) {
                    $this->is_done = \true;
                }
            } elseif ($this->config->get_type() === AssetConfigInterface::TYPE_STYLE) {
                if (wp_styles()->do_item($this->config->get_handle())) {
                    $this->is_done = \true;
                }
            }
        }
    }
    public function get_config() : AssetConfigInterface
    {
        return $this->config;
    }
    public function set_config(AssetConfigInterface $config) : void
    {
        $this->config = $config;
    }
    public function get_is_done() : bool
    {
        return $this->is_done;
    }
}
