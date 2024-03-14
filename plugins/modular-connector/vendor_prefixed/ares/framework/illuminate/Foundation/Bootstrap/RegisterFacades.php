<?php

namespace Modular\ConnectorDependencies\Illuminate\Foundation\Bootstrap;

use Modular\ConnectorDependencies\Illuminate\Contracts\Foundation\Application;
use Modular\ConnectorDependencies\Illuminate\Foundation\AliasLoader;
use Modular\ConnectorDependencies\Illuminate\Foundation\PackageManifest;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\Facade;
/** @internal */
class RegisterFacades
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($app);
        AliasLoader::getInstance(\array_merge($app->make('config')->get('app.aliases', []), $app->make(PackageManifest::class)->aliases()))->register();
    }
}
