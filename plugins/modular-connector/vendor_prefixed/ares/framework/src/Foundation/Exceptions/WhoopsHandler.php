<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Foundation\Exceptions;

use Modular\ConnectorDependencies\Illuminate\Filesystem\Filesystem;
use Modular\ConnectorDependencies\Illuminate\Foundation\Exceptions\WhoopsHandler as FoundationWhoopsHandler;
use Modular\ConnectorDependencies\Illuminate\Support\Arr;
use Modular\ConnectorDependencies\Whoops\Handler\PrettyPageHandler;
/** @internal */
class WhoopsHandler extends FoundationWhoopsHandler
{
    /**
     * WordPress environment secrets.
     *
     * @var array
     */
    protected $secrets = ['DB_PASSWORD', 'DATABASE_URL', 'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT'];
    /**
     * Create a new Whoops handler for debug mode.
     *
     * @return \Whoops\Handler\PrettyPageHandler
     */
    public function forDebug()
    {
        return \Modular\ConnectorDependencies\tap(new PrettyPageHandler(), function ($handler) {
            $handler->handleUnconditionally(\true);
            $this->registerApplicationPaths($handler)->registerBlacklist($handler)->registerEditor($handler);
        });
    }
    /**
     * Get the application paths except for the "vendor" directory.
     *
     * @return array
     */
    protected function directoriesExceptVendor()
    {
        return Arr::except(\array_flip((new Filesystem())->directories(\Modular\ConnectorDependencies\base_path())), [\Modular\ConnectorDependencies\base_path('vendor')]);
    }
    /**
     * Register the blacklist with the handler.
     *
     * @param \Whoops\Handler\PrettyPageHandler $handler
     * @return $this
     */
    protected function registerBlacklist($handler)
    {
        $blacklist = \array_merge_recursive(['_ENV' => $this->secrets, '_SERVER' => $this->secrets], \Modular\ConnectorDependencies\config('app.debug_blacklist', config('app.debug_hide', [])));
        foreach ($blacklist as $key => $secrets) {
            foreach ($secrets as $secret) {
                $handler->blacklist($key, $secret);
            }
        }
        return $this;
    }
    /**
     * Register the editor with the handler.
     *
     * @param \Whoops\Handler\PrettyPageHandler $handler
     * @return $this
     */
    protected function registerEditor($handler)
    {
        if (\Modular\ConnectorDependencies\config('app.editor', \false)) {
            $handler->setEditor(\Modular\ConnectorDependencies\config('app.editor'));
        }
        return $this;
    }
}
