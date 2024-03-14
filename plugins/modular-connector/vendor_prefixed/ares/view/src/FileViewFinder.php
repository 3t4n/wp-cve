<?php

namespace Modular\ConnectorDependencies\Ares\View;

use Modular\ConnectorDependencies\Ares\Builder\Builder\Element;
use Modular\ConnectorDependencies\Illuminate\Support\Collection;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\App;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\View;
use Modular\ConnectorDependencies\Illuminate\Support\Str;
use Modular\ConnectorDependencies\Illuminate\View\FileViewFinder as FileViewFinderFoundation;
/** @internal */
class FileViewFinder extends FileViewFinderFoundation
{
    /**
     * Normalizes file path separators
     *
     * @param mixed $path
     * @param string $separator
     * @return mixed
     */
    public function normalizePath($path, $separator = '/')
    {
        if (\is_array($path)) {
            return \array_map([$this, 'normalizePath'], $path);
        }
        return \preg_replace('#/+#', $separator, \strtr($path, '\\', '/'));
    }
    /**
     * Remove recognized extensions from path
     *
     * @param string $file relative path to view file
     * @return string view name
     */
    public function stripExtensions($path)
    {
        $extensions = \implode('|', \array_map('preg_quote', $this->getExtensions()));
        return \preg_replace("/\\.({$extensions})\$/", '', $path);
    }
    /**
     * Get list of view paths relative to the base path
     *
     * @return \Illuminate\Support\Collection
     */
    private function getRelativeViewPaths()
    {
        return Collection::make($this->getPaths())->map(function ($viewsPath) {
            $path = App::basePath();
            return $this->getRelativePath("{$path}/", $viewsPath);
        });
    }
    /**
     * Get relative path of target from specified base
     *
     * @param string $basePath
     * @param string $targetPath
     * @return string
     *
     * @copyright Fabien Potencier
     * @license   MIT
     * @link      https://github.com/symfony/routing/blob/v4.1.1/Generator/UrlGenerator.php#L280-L329
     */
    private function getRelativePath($basePath, $targetPath)
    {
        $basePath = $this->normalizePath($basePath);
        $targetPath = $this->normalizePath($targetPath);
        if ($basePath === $targetPath) {
            return '';
        }
        $sourceDirs = \explode('/', \ltrim($basePath, '/'));
        $targetDirs = \explode('/', \ltrim($targetPath, '/'));
        \array_pop($sourceDirs);
        $targetFile = \array_pop($targetDirs);
        foreach ($sourceDirs as $i => $dir) {
            if (isset($targetDirs[$i]) && $dir === $targetDirs[$i]) {
                unset($sourceDirs[$i], $targetDirs[$i]);
            } else {
                break;
            }
        }
        $targetDirs[] = $targetFile;
        $path = \str_repeat('../', \count($sourceDirs)) . \implode('/', $targetDirs);
        return $path === '' || $path[0] === '/' || ($colonPos = \strpos($path, ':')) !== \false && ($colonPos < ($slashPos = \strpos($path, '/')) || $slashPos === \false) ? "./{$path}" : $path;
    }
    /**
     * Get possible relative locations of view files
     *
     * @param string $path Absolute or relative path to possible view file
     * @return string[]
     */
    public function getPossibleViewFilesFromPath($path)
    {
        $path = $this->getPossibleViewNameFromPath($path);
        return $this->getPossibleViewFiles($path);
    }
    /**
     * Check if the path is from a plugin
     *
     * @param $path
     * @return bool
     */
    public function isPluginPath($path)
    {
        return \defined('WP_PLUGIN_DIR') && Str::startsWith($path, \WP_PLUGIN_DIR);
    }
    /**
     * Get an array of possible view files.
     *
     * @param string $path
     * @return array
     */
    protected function getPossiblePluginViewFiles($name)
    {
        if (!$this->isPluginPath($name)) {
            return [];
        }
        $name = Str::replace('/', '.', \trim(Str::replace(\WP_PLUGIN_DIR, '', $name), '/'));
        return \array_map(function ($extension) use($name) {
            return \str_replace('.', '/', $name) . '.' . $extension;
        }, $this->extensions);
    }
    /**
     * Get possible view name based on path
     *
     * @param string $path Absolute or relative path to possible view file
     * @return string
     */
    public function getPossibleViewNameFromPath($file)
    {
        $namespace = null;
        $view = $this->normalizePath($file);
        $paths = $this->getPaths();
        if ($this->isPluginPath($file)) {
            $paths = \array_merge($paths, [\WP_PLUGIN_DIR]);
        }
        $paths = \array_map([$this, 'normalizePath'], $paths);
        $hints = \array_map([$this, 'normalizePath'], $this->getHints());
        $view = $this->stripExtensions($view);
        $view = \str_replace($paths, '', $view);
        foreach ($hints as $hintNamespace => $hintPaths) {
            $test = \str_replace($hintPaths, '', $view);
            if ($view !== $test) {
                $namespace = $hintNamespace;
                $view = $test;
                break;
            }
        }
        $view = \ltrim($view, '/\\');
        if ($namespace) {
            $view = "{$namespace}::{$view}";
        }
        return $view;
    }
    /**
     * Locate available view files.
     *
     * @param mixed $file
     * @return array
     */
    public function locate($file)
    {
        if (\is_array($file)) {
            return \array_merge(...\array_map([$this, 'locate'], $file));
        }
        return $this->getRelativeViewPaths()->flatMap(function ($viewPath) use($file) {
            return \Modular\ConnectorDependencies\collect($this->getPossibleViewFilesFromPath($file))->merge([$file])->map(function ($file) use($viewPath) {
                return "{$viewPath}/{$file}";
            });
        })->unique()->map(function ($file) {
            return \trim($file, '\\/');
        })->toArray();
    }
    /**
     * Locate available controller class for view
     *
     * @param mixed $file
     * @return array
     */
    public function getPossibleControllerFromView($view) : ?string
    {
        $controllerName = \implode('\\', \array_map(function ($word) {
            return Str::ucfirst(Str::camel($word));
        }, \explode('.', $view)));
        $namespaces = \Modular\ConnectorDependencies\app()->make('config')->get('app.controllers');
        foreach ($namespaces as $namespace) {
            $controller = \trim($namespace['namespace'], '\\') . "\\{$controllerName}Controller";
            if (\class_exists($controller)) {
                return $controller;
            }
        }
        return null;
    }
    /**
     * Get view name for builder element
     *
     * @param Element $element
     * @return string
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getPossibleViewNameForBuilderElement(Element $element)
    {
        $base_name = $element->getBaseName();
        $reflectionClass = new \ReflectionClass($element);
        $namespace = $reflectionClass->getNamespaceName();
        $className = Str::snake(\str_ireplace('\\', '', class_basename($reflectionClass->getName())));
        $prefixView = '';
        // Search "prefix" of view by Element Namespace
        foreach (\Modular\ConnectorDependencies\app()->make('config')->get('builder.builder') as $element) {
            if (Str::startsWith($namespace, $element['namespace'])) {
                $namespace = \trim(Str::replace($element['namespace'], '', $reflectionClass->getNamespaceName()), '\\');
                $prefixView = $element['prefix_view'];
                break;
            }
        }
        // Normalize name of view
        $namespace = \array_map(function ($name) {
            return Str::snake($name);
        }, \explode('\\', $namespace));
        $namespace = \implode('.', $namespace);
        // Search
        $baseView = $prefixView . 'builder';
        $posibleViews = [\implode('.', [$baseView, $namespace, $className]), \implode('.', [$baseView, $namespace, $base_name]), \implode('.', [$baseView, $className]), \implode('.', [$baseView, $base_name])];
        $posibleViews = \array_filter($posibleViews, function ($view) {
            return View::exists($view);
        });
        if (!isset($posibleViews[0])) {
            return $baseView . '.' . $base_name;
        }
        return $posibleViews[0];
    }
}
