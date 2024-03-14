<?php

namespace Modular\ConnectorDependencies\Illuminate\Support\Traits;

use Modular\ConnectorDependencies\Illuminate\Container\Container;
/** @internal */
trait Localizable
{
    /**
     * Run the callback with the given locale.
     *
     * @param  string  $locale
     * @param  \Closure  $callback
     * @return mixed
     */
    public function withLocale($locale, $callback)
    {
        if (!$locale) {
            return $callback();
        }
        $app = Container::getInstance();
        $original = $app->getLocale();
        try {
            $app->setLocale($locale);
            return $callback();
        } finally {
            $app->setLocale($original);
        }
    }
}
