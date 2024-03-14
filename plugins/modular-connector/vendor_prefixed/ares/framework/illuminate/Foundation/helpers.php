<?php

namespace Modular\ConnectorDependencies;

use Modular\ConnectorDependencies\Illuminate\Container\Container;
use Modular\ConnectorDependencies\Illuminate\Contracts\Auth\Access\Gate;
use Modular\ConnectorDependencies\Illuminate\Contracts\Auth\Factory as AuthFactory;
use Modular\ConnectorDependencies\Illuminate\Contracts\Broadcasting\Factory as BroadcastFactory;
use Modular\ConnectorDependencies\Illuminate\Contracts\Bus\Dispatcher;
use Modular\ConnectorDependencies\Illuminate\Contracts\Cookie\Factory as CookieFactory;
use Modular\ConnectorDependencies\Illuminate\Contracts\Debug\ExceptionHandler;
use Modular\ConnectorDependencies\Illuminate\Contracts\Routing\ResponseFactory;
use Modular\ConnectorDependencies\Illuminate\Contracts\Routing\UrlGenerator;
use Modular\ConnectorDependencies\Illuminate\Contracts\Support\Responsable;
use Modular\ConnectorDependencies\Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Modular\ConnectorDependencies\Illuminate\Contracts\View\Factory as ViewFactory;
use Modular\ConnectorDependencies\Illuminate\Foundation\Bus\PendingClosureDispatch;
use Modular\ConnectorDependencies\Illuminate\Foundation\Bus\PendingDispatch;
use Modular\ConnectorDependencies\Illuminate\Foundation\Mix;
use Modular\ConnectorDependencies\Illuminate\Http\Exceptions\HttpResponseException;
use Modular\ConnectorDependencies\Illuminate\Queue\CallQueuedClosure;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\Date;
use Modular\ConnectorDependencies\Illuminate\Support\HtmlString;
use Modular\ConnectorDependencies\Symfony\Component\HttpFoundation\Response;
if (!\function_exists('Modular\\ConnectorDependencies\\abort')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param  \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable|int  $code
     * @param  string  $message
     * @param  array  $headers
     * @return never
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @internal
     */
    function abort($code, $message = '', array $headers = [])
    {
        if ($code instanceof Response) {
            throw new HttpResponseException($code);
        } elseif ($code instanceof Responsable) {
            throw new HttpResponseException($code->toResponse(request()));
        }
        \Modular\ConnectorDependencies\app()->abort($code, $message, $headers);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\abort_if')) {
    /**
     * Throw an HttpException with the given data if the given condition is true.
     *
     * @param  bool  $boolean
     * @param  \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable|int  $code
     * @param  string  $message
     * @param  array  $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @internal
     */
    function abort_if($boolean, $code, $message = '', array $headers = [])
    {
        if ($boolean) {
            abort($code, $message, $headers);
        }
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\abort_unless')) {
    /**
     * Throw an HttpException with the given data unless the given condition is true.
     *
     * @param  bool  $boolean
     * @param  \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable|int  $code
     * @param  string  $message
     * @param  array  $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @internal
     */
    function abort_unless($boolean, $code, $message = '', array $headers = [])
    {
        if (!$boolean) {
            abort($code, $message, $headers);
        }
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\action')) {
    /**
     * Generate the URL to a controller action.
     *
     * @param  string|array  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     * @internal
     */
    function action($name, $parameters = [], $absolute = \true)
    {
        return \Modular\ConnectorDependencies\app('url')->action($name, $parameters, $absolute);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\app')) {
    /**
     * Get the available container instance.
     *
     * @param  string|null  $abstract
     * @param  array  $parameters
     * @return mixed|\Illuminate\Contracts\Foundation\Application
     * @internal
     */
    function app($abstract = null, array $parameters = [])
    {
        if (\is_null($abstract)) {
            return Container::getInstance();
        }
        return Container::getInstance()->make($abstract, $parameters);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\app_path')) {
    /**
     * Get the path to the application folder.
     *
     * @param  string  $path
     * @return string
     * @internal
     */
    function app_path($path = '')
    {
        return \Modular\ConnectorDependencies\app()->path($path);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     * @internal
     */
    function asset($path, $secure = null)
    {
        return \Modular\ConnectorDependencies\app('url')->asset($path, $secure);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\auth')) {
    /**
     * Get the available auth instance.
     *
     * @param  string|null  $guard
     * @return \Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     * @internal
     */
    function auth($guard = null)
    {
        if (\is_null($guard)) {
            return \Modular\ConnectorDependencies\app(AuthFactory::class);
        }
        return \Modular\ConnectorDependencies\app(AuthFactory::class)->guard($guard);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\back')) {
    /**
     * Create a new redirect response to the previous location.
     *
     * @param  int  $status
     * @param  array  $headers
     * @param  mixed  $fallback
     * @return \Illuminate\Http\RedirectResponse
     * @internal
     */
    function back($status = 302, $headers = [], $fallback = \false)
    {
        return \Modular\ConnectorDependencies\app('redirect')->back($status, $headers, $fallback);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\base_path')) {
    /**
     * Get the path to the base of the install.
     *
     * @param  string  $path
     * @return string
     * @internal
     */
    function base_path($path = '')
    {
        return \Modular\ConnectorDependencies\app()->basePath($path);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\bcrypt')) {
    /**
     * Hash the given value against the bcrypt algorithm.
     *
     * @param  string  $value
     * @param  array  $options
     * @return string
     * @internal
     */
    function bcrypt($value, $options = [])
    {
        return \Modular\ConnectorDependencies\app('hash')->driver('bcrypt')->make($value, $options);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\broadcast')) {
    /**
     * Begin broadcasting an event.
     *
     * @param  mixed|null  $event
     * @return \Illuminate\Broadcasting\PendingBroadcast
     * @internal
     */
    function broadcast($event = null)
    {
        return \Modular\ConnectorDependencies\app(BroadcastFactory::class)->event($event);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\cache')) {
    /**
     * Get / set the specified cache value.
     *
     * If an array is passed, we'll assume you want to put to the cache.
     *
     * @param  dynamic  key|key,default|data,expiration|null
     * @return mixed|\Illuminate\Cache\CacheManager
     *
     * @throws \Exception
     * @internal
     */
    function cache()
    {
        $arguments = \func_get_args();
        if (empty($arguments)) {
            return \Modular\ConnectorDependencies\app('cache');
        }
        if (\is_string($arguments[0])) {
            return \Modular\ConnectorDependencies\app('cache')->get(...$arguments);
        }
        if (!\is_array($arguments[0])) {
            throw new \Exception('When setting a value in the cache, you must pass an array of key / value pairs.');
        }
        return \Modular\ConnectorDependencies\app('cache')->put(\key($arguments[0]), \reset($arguments[0]), $arguments[1] ?? null);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\config')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return mixed|\Illuminate\Config\Repository
     * @internal
     */
    function config($key = null, $default = null)
    {
        if (\is_null($key)) {
            return \Modular\ConnectorDependencies\app('config');
        }
        if (\is_array($key)) {
            return \Modular\ConnectorDependencies\app('config')->set($key);
        }
        return \Modular\ConnectorDependencies\app('config')->get($key, $default);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string  $path
     * @return string
     * @internal
     */
    function config_path($path = '')
    {
        return \Modular\ConnectorDependencies\app()->configPath($path);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\cookie')) {
    /**
     * Create a new cookie instance.
     *
     * @param  string|null  $name
     * @param  string|null  $value
     * @param  int  $minutes
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool|null  $secure
     * @param  bool  $httpOnly
     * @param  bool  $raw
     * @param  string|null  $sameSite
     * @return \Illuminate\Cookie\CookieJar|\Symfony\Component\HttpFoundation\Cookie
     * @internal
     */
    function cookie($name = null, $value = null, $minutes = 0, $path = null, $domain = null, $secure = null, $httpOnly = \true, $raw = \false, $sameSite = null)
    {
        $cookie = \Modular\ConnectorDependencies\app(CookieFactory::class);
        if (\is_null($name)) {
            return $cookie;
        }
        return $cookie->make($name, $value, $minutes, $path, $domain, $secure, $httpOnly, $raw, $sameSite);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\csrf_field')) {
    /**
     * Generate a CSRF token form field.
     *
     * @return \Illuminate\Support\HtmlString
     * @internal
     */
    function csrf_field()
    {
        return new HtmlString('<input type="hidden" name="_token" value="' . csrf_token() . '">');
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\csrf_token')) {
    /**
     * Get the CSRF token value.
     *
     * @return string
     *
     * @throws \RuntimeException
     * @internal
     */
    function csrf_token()
    {
        $session = \Modular\ConnectorDependencies\app('session');
        if (isset($session)) {
            return $session->token();
        }
        throw new \RuntimeException('Application session store not set.');
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\database_path')) {
    /**
     * Get the database path.
     *
     * @param  string  $path
     * @return string
     * @internal
     */
    function database_path($path = '')
    {
        return \Modular\ConnectorDependencies\app()->databasePath($path);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\decrypt')) {
    /**
     * Decrypt the given value.
     *
     * @param  string  $value
     * @param  bool  $unserialize
     * @return mixed
     * @internal
     */
    function decrypt($value, $unserialize = \true)
    {
        return \Modular\ConnectorDependencies\app('encrypter')->decrypt($value, $unserialize);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\dispatch')) {
    /**
     * Dispatch a job to its appropriate handler.
     *
     * @param  mixed  $job
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     * @internal
     */
    function dispatch($job)
    {
        return $job instanceof \Closure ? new PendingClosureDispatch(CallQueuedClosure::create($job)) : new PendingDispatch($job);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\dispatch_sync')) {
    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * Queueable jobs will be dispatched to the "sync" queue.
     *
     * @param  mixed  $job
     * @param  mixed  $handler
     * @return mixed
     * @internal
     */
    function dispatch_sync($job, $handler = null)
    {
        return \Modular\ConnectorDependencies\app(Dispatcher::class)->dispatchSync($job, $handler);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\dispatch_now')) {
    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * @param  mixed  $job
     * @param  mixed  $handler
     * @return mixed
     *
     * @deprecated Will be removed in a future Laravel version.
     * @internal
     */
    function dispatch_now($job, $handler = null)
    {
        return \Modular\ConnectorDependencies\app(Dispatcher::class)->dispatchNow($job, $handler);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\encrypt')) {
    /**
     * Encrypt the given value.
     *
     * @param  mixed  $value
     * @param  bool  $serialize
     * @return string
     * @internal
     */
    function encrypt($value, $serialize = \true)
    {
        return \Modular\ConnectorDependencies\app('encrypter')->encrypt($value, $serialize);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\event')) {
    /**
     * Dispatch an event and call the listeners.
     *
     * @param  string|object  $event
     * @param  mixed  $payload
     * @param  bool  $halt
     * @return array|null
     * @internal
     */
    function event(...$args)
    {
        return \Modular\ConnectorDependencies\app('events')->dispatch(...$args);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\info')) {
    /**
     * Write some information to the log.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     * @internal
     */
    function info($message, $context = [])
    {
        \Modular\ConnectorDependencies\app('log')->info($message, $context);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\logger')) {
    /**
     * Log a debug message to the logs.
     *
     * @param  string|null  $message
     * @param  array  $context
     * @return \Illuminate\Log\LogManager|null
     * @internal
     */
    function logger($message = null, array $context = [])
    {
        if (\is_null($message)) {
            return \Modular\ConnectorDependencies\app('log');
        }
        return \Modular\ConnectorDependencies\app('log')->debug($message, $context);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\lang_path')) {
    /**
     * Get the path to the language folder.
     *
     * @param  string  $path
     * @return string
     * @internal
     */
    function lang_path($path = '')
    {
        return \Modular\ConnectorDependencies\app('path.lang') . ($path ? \DIRECTORY_SEPARATOR . $path : $path);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\logs')) {
    /**
     * Get a log driver instance.
     *
     * @param  string|null  $driver
     * @return \Illuminate\Log\LogManager|\Psr\Log\LoggerInterface
     * @internal
     */
    function logs($driver = null)
    {
        return $driver ? \Modular\ConnectorDependencies\app('log')->driver($driver) : \Modular\ConnectorDependencies\app('log');
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\method_field')) {
    /**
     * Generate a form field to spoof the HTTP verb used by forms.
     *
     * @param  string  $method
     * @return \Illuminate\Support\HtmlString
     * @internal
     */
    function method_field($method)
    {
        return new HtmlString('<input type="hidden" name="_method" value="' . $method . '">');
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\mix')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param  string  $path
     * @param  string  $manifestDirectory
     * @return \Illuminate\Support\HtmlString|string
     *
     * @throws \Exception
     * @internal
     */
    function mix($path, $manifestDirectory = '')
    {
        return \Modular\ConnectorDependencies\app(Mix::class)(...\func_get_args());
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\now')) {
    /**
     * Create a new Carbon instance for the current time.
     *
     * @param  \DateTimeZone|string|null  $tz
     * @return \Illuminate\Support\Carbon
     * @internal
     */
    function now($tz = null)
    {
        return Date::now($tz);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\old')) {
    /**
     * Retrieve an old input item.
     *
     * @param  string|null  $key
     * @param  mixed  $default
     * @return mixed
     * @internal
     */
    function old($key = null, $default = null)
    {
        return \Modular\ConnectorDependencies\app('request')->old($key, $default);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\policy')) {
    /**
     * Get a policy instance for a given class.
     *
     * @param  object|string  $class
     * @return mixed
     *
     * @throws \InvalidArgumentException
     * @internal
     */
    function policy($class)
    {
        return \Modular\ConnectorDependencies\app(Gate::class)->getPolicyFor($class);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\public_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param  string  $path
     * @return string
     * @internal
     */
    function public_path($path = '')
    {
        return \Modular\ConnectorDependencies\app()->make('path.public') . ($path ? \DIRECTORY_SEPARATOR . \ltrim($path, \DIRECTORY_SEPARATOR) : $path);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\redirect')) {
    /**
     * Get an instance of the redirector.
     *
     * @param  string|null  $to
     * @param  int  $status
     * @param  array  $headers
     * @param  bool|null  $secure
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     * @internal
     */
    function redirect($to = null, $status = 302, $headers = [], $secure = null)
    {
        if (\is_null($to)) {
            return \Modular\ConnectorDependencies\app('redirect');
        }
        return \Modular\ConnectorDependencies\app('redirect')->to($to, $status, $headers, $secure);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\report')) {
    /**
     * Report an exception.
     *
     * @param  \Throwable|string  $exception
     * @return void
     * @internal
     */
    function report($exception)
    {
        if (\is_string($exception)) {
            $exception = new \Exception($exception);
        }
        \Modular\ConnectorDependencies\app(ExceptionHandler::class)->report($exception);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\request')) {
    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return \Illuminate\Http\Request|string|array|null
     * @internal
     */
    function request($key = null, $default = null)
    {
        if (\is_null($key)) {
            return \Modular\ConnectorDependencies\app('request');
        }
        if (\is_array($key)) {
            return \Modular\ConnectorDependencies\app('request')->only($key);
        }
        $value = \Modular\ConnectorDependencies\app('request')->__get($key);
        return \is_null($value) ? \Modular\ConnectorDependencies\value($default) : $value;
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\rescue')) {
    /**
     * Catch a potential exception and return a default value.
     *
     * @param  callable  $callback
     * @param  mixed  $rescue
     * @param  bool  $report
     * @return mixed
     * @internal
     */
    function rescue(callable $callback, $rescue = null, $report = \true)
    {
        try {
            return $callback();
        } catch (\Throwable $e) {
            if ($report) {
                report($e);
            }
            return \Modular\ConnectorDependencies\value($rescue, $e);
        }
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\resolve')) {
    /**
     * Resolve a service from the container.
     *
     * @param  string  $name
     * @param  array  $parameters
     * @return mixed
     * @internal
     */
    function resolve($name, array $parameters = [])
    {
        return \Modular\ConnectorDependencies\app($name, $parameters);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\resource_path')) {
    /**
     * Get the path to the resources folder.
     *
     * @param  string  $path
     * @return string
     * @internal
     */
    function resource_path($path = '')
    {
        return \Modular\ConnectorDependencies\app()->resourcePath($path);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\response')) {
    /**
     * Return a new response from the application.
     *
     * @param  \Illuminate\Contracts\View\View|string|array|null  $content
     * @param  int  $status
     * @param  array  $headers
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     * @internal
     */
    function response($content = '', $status = 200, array $headers = [])
    {
        $factory = \Modular\ConnectorDependencies\app(ResponseFactory::class);
        if (\func_num_args() === 0) {
            return $factory;
        }
        return $factory->make($content, $status, $headers);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\route')) {
    /**
     * Generate the URL to a named route.
     *
     * @param  array|string  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     * @internal
     */
    function route($name, $parameters = [], $absolute = \true)
    {
        return \Modular\ConnectorDependencies\app('url')->route($name, $parameters, $absolute);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\secure_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @return string
     * @internal
     */
    function secure_asset($path)
    {
        return asset($path, \true);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\secure_url')) {
    /**
     * Generate a HTTPS url for the application.
     *
     * @param  string  $path
     * @param  mixed  $parameters
     * @return string
     * @internal
     */
    function secure_url($path, $parameters = [])
    {
        return url($path, $parameters, \true);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\session')) {
    /**
     * Get / set the specified session value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return mixed|\Illuminate\Session\Store|\Illuminate\Session\SessionManager
     * @internal
     */
    function session($key = null, $default = null)
    {
        if (\is_null($key)) {
            return \Modular\ConnectorDependencies\app('session');
        }
        if (\is_array($key)) {
            return \Modular\ConnectorDependencies\app('session')->put($key);
        }
        return \Modular\ConnectorDependencies\app('session')->get($key, $default);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\storage_path')) {
    /**
     * Get the path to the storage folder.
     *
     * @param  string  $path
     * @return string
     * @internal
     */
    function storage_path($path = '')
    {
        return \Modular\ConnectorDependencies\app('path.storage') . ($path ? \DIRECTORY_SEPARATOR . $path : $path);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\today')) {
    /**
     * Create a new Carbon instance for the current date.
     *
     * @param  \DateTimeZone|string|null  $tz
     * @return \Illuminate\Support\Carbon
     * @internal
     */
    function today($tz = null)
    {
        return Date::today($tz);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\trans')) {
    /**
     * Translate the given message.
     *
     * @param  string|null  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     * @internal
     */
    function trans($key = null, $replace = [], $locale = null)
    {
        if (\is_null($key)) {
            return \Modular\ConnectorDependencies\app('translator');
        }
        return \Modular\ConnectorDependencies\app('translator')->get($key, $replace, $locale);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\trans_choice')) {
    /**
     * Translates the given message based on a count.
     *
     * @param  string  $key
     * @param  \Countable|int|array  $number
     * @param  array  $replace
     * @param  string|null  $locale
     * @return string
     * @internal
     */
    function trans_choice($key, $number, array $replace = [], $locale = null)
    {
        return \Modular\ConnectorDependencies\app('translator')->choice($key, $number, $replace, $locale);
    }
}
if (!\function_exists('__')) {
    /**
     * Translate the given message.
     *
     * @param  string|null  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return string|array|null
     * @internal
     */
    function __($key = null, $replace = [], $locale = null)
    {
        if (\is_null($key)) {
            return $key;
        }
        return trans($key, $replace, $locale);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\url')) {
    /**
     * Generate a url for the application.
     *
     * @param  string|null  $path
     * @param  mixed  $parameters
     * @param  bool|null  $secure
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     * @internal
     */
    function url($path = null, $parameters = [], $secure = null)
    {
        if (\is_null($path)) {
            return \Modular\ConnectorDependencies\app(UrlGenerator::class);
        }
        return \Modular\ConnectorDependencies\app(UrlGenerator::class)->to($path, $parameters, $secure);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\validator')) {
    /**
     * Create a new Validator instance.
     *
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Contracts\Validation\Factory
     * @internal
     */
    function validator(array $data = [], array $rules = [], array $messages = [], array $customAttributes = [])
    {
        $factory = \Modular\ConnectorDependencies\app(ValidationFactory::class);
        if (\func_num_args() === 0) {
            return $factory;
        }
        return $factory->make($data, $rules, $messages, $customAttributes);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\view')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $data
     * @param  array  $mergeData
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     * @internal
     */
    function view($view = null, $data = [], $mergeData = [])
    {
        $factory = \Modular\ConnectorDependencies\app(ViewFactory::class);
        if (\func_num_args() === 0) {
            return $factory;
        }
        return $factory->make($view, $data, $mergeData);
    }
}
