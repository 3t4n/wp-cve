<?php

namespace luckywp\tableOfContents\core\base;

use luckywp\tableOfContents\core\Core;

/**
 * @property string $basename
 */
abstract class BasePlugin extends ServiceLocator
{

    /**
     * @var string
     */
    public $slug;

    /**
     * @var string
     */
    public $version;

    /**
     * @var string
     */
    public $fileName;

    /**
     * @var string
     */
    public $dir;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $prefix;

    /**
     * @var string
     */
    public $textDomain;

    /**
     * @var string
     */
    public $domainPath = 'languages/';

    /**
     * @var array
     */
    public $bootstrap = [];

    /**
     * @var array
     */
    public $pluginsLoadedBootstrap = [];

    public function __construct(array $config = [])
    {
        Core::initialize($this);
        add_action('plugins_loaded', function () {
            if ($this->textDomain) {
                load_plugin_textdomain($this->textDomain, false, basename($this->dir) . '/' . $this->domainPath);
            }
            $this->bootstrap($this->pluginsLoadedBootstrap);
        });
        parent::__construct($config);
    }

    protected function bootstrap($bootstrap)
    {
        foreach ($bootstrap as $mixed) {
            if (is_string($mixed) && $this->has($mixed)) {
                $this->get($mixed);
            }
        }
    }

    public function run($version, $fileName, $prefix)
    {
        $this->slug = basename($fileName, '.php');
        $this->textDomain = $this->slug;
        $this->version = $version;
        $this->fileName = $fileName;
        $this->dir = dirname($fileName);
        $this->url = plugins_url('', $fileName);
        $this->prefix = $prefix;
        $this->bootstrap($this->bootstrap);
    }

    public function getBasename()
    {
        return plugin_basename($this->fileName);
    }
}
