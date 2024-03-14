<?php

namespace Flamix\Plugin;

class Init
{
    private string $plugin_dir;
    private string $plugin_code;
    private string $constant_name;

    public function __construct(string $dir, string $constant_name)
    {
        include 'helpers.php';
        $code = explode('/', $dir);
        $this->plugin_dir = $dir;
        $this->constant_name = $constant_name;
        $this->plugin_code = end($code);

        if (defined($constant_name . '_CODE'))
            throw new \Exception('Plugin already defined!');

        define($constant_name . '_CODE', end($code));
        define($constant_name . '_DIR', $dir);
    }

    public static function init(string $dir, string $constant_name): self
    {
        return new Init($dir, $constant_name);
    }

    /**
     * Set logs path.
     *
     * We will use this in flamix_log()
     *
     * @param string $log_path
     * @return $this
     */
    public function setLogsPath(string $log_path): self
    {
        if (!defined('FLAMIX_LOGS_PATH'))
            define('FLAMIX_LOGS_PATH', $log_path);
        
        return $this;
    }

    /**
     * @return string
     */
    public function getPluginDir(): string
    {
        return $this->plugin_dir;
    }

    /**
     * @return string
     */
    public function getPluginCode(): string
    {
        return $this->plugin_code;
    }

    /**
     * Return all constants.
     *
     * @return array
     */
    public function defined(): array
    {
        return [
            $this->constant_name . '_CODE' => constant($this->constant_name . '_CODE'),
            $this->constant_name . '_DIR' => constant($this->constant_name . '_DIR'),
            'FLAMIX_LOGS_PATH' => FLAMIX_LOGS_PATH,
        ];
    }
}