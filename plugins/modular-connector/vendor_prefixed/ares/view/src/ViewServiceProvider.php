<?php

namespace Modular\ConnectorDependencies\Ares\View;

use Modular\ConnectorDependencies\Ares\View\Engines\CompilerEngine;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\View;
use Modular\ConnectorDependencies\Illuminate\Support\Str;
use Modular\ConnectorDependencies\Illuminate\View\ViewServiceProvider as FoundationViewServiceProvider;
/** @internal */
class ViewServiceProvider extends FoundationViewServiceProvider
{
    /**
     * WordPress template
     *
     * @var string[]
     */
    protected array $templates = ['index', '404', 'archive', 'author', 'category', 'tag', 'taxonomy', 'date', 'home', 'frontpage', 'page', 'paged', 'search', 'single', 'singular', 'attachment', 'embed', 'privacypolicy'];
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
        if (!$this->app->runningInConsole() && $this->app->bound('view')) {
            $this->bindFilters();
        }
    }
    /**
     * Register singleton for compile all views for WP
     *
     * @return void
     */
    public function bindFilters()
    {
        \add_filter('body_class', [$this, 'filterBodyClass'], 10);
        \add_filter('theme_templates', [$this, 'filterThemeTemplates'], 100, 4);
        \add_filter('comments_template', [$this, 'filterCommentsTemplate'], 100, 4);
        \add_filter('template_include', [$this, 'filterTemplateInclude'], 100, 4);
        // Override WC templates
        \add_filter('wc_get_template', [$this, 'filterWCTemplate'], 100, 4);
        \add_filter('wc_get_template_part', [$this, 'filterWCTemplatePart'], 100, 4);
        foreach ($this->templates as $name) {
            \add_filter("{$name}_template_hierarchy", [$this, 'filterTemplateHierarchy'], 10);
        }
    }
    /**
     * Register the view finder implementation.
     *
     * @return void
     */
    public function registerViewFinder()
    {
        $this->app->bind('view.finder', function ($app) {
            $finder = new FileViewFinder($app['files'], $app['config']['view.paths']);
            foreach ($app['config']['view.namespaces'] as $namespace => $hints) {
                $hints = \array_merge(\array_map(function ($path) use($namespace) {
                    return "{$path}/vendor/{$namespace}";
                }, $finder->getPaths()), (array) $hints);
                $finder->addNamespace($namespace, $hints);
            }
            return $finder;
        });
    }
    /**
     * Register the Blade engine implementation.
     *
     * @param \Illuminate\View\Engines\EngineResolver $resolver
     * @return void
     */
    public function registerBladeEngine($resolver)
    {
        $resolver->register('blade', function () {
            return new CompilerEngine($this->app['blade.compiler'], $this->app['files']);
        });
    }
    /**
     * Add classes to body for execute data controllers
     *
     * @param array $classes
     * @return array
     */
    public function filterBodyClass($classes)
    {
        $classes = \Modular\ConnectorDependencies\collect($classes);
        if (\is_single() || \is_page() && !\is_front_page()) {
            $class = \basename(\get_permalink());
            if (!$classes->containsStrict($class)) {
                $classes->push($class);
            }
        }
        return $classes->map(function ($class) {
            return \preg_replace(['/-blade(-php)?$/', '/^page-template-views/'], '', $class);
        })->filter()->all();
    }
    /**
     * Add Blade compatability for theme templates
     * in page attributes (wp-admin)
     *
     * NOTE: Internally, WordPress interchangeably uses "page templates" "post templates" and "theme templates"
     *
     * @return string[] List of theme templates
     */
    public function filterThemeTemplates($_templates, $theme, $post, $post_type)
    {
        $templates = [];
        foreach (\array_reverse($this->app['view.finder']->getPaths()) as $path) {
            /**
             * We use the exact same technique as WordPress core for detecting template files.
             *
             * Caveat: we go infinite levels deep within the views folder.
             *
             * @see \WP_Theme::get_post_templates()
             * @link https://github.com/WordPress/WordPress/blob/5.2.1/wp-includes/class-wp-theme.php#L1146-L1164
             */
            foreach ($this->app['files']->glob("{$path}/**.php") as $full_path) {
                if (!\preg_match('|Template Name:(.*)$|mi', \file_get_contents($full_path), $header)) {
                    continue;
                }
                $types = ['page'];
                if (\preg_match('|Template Post Type:(.*)$|mi', \file_get_contents($full_path), $type)) {
                    $types = \explode(',', \_cleanup_header_comment($type[1]));
                }
                $file = $this->app['view.finder']->getRelativePath("{$path}/", $full_path);
                foreach ($types as $type) {
                    $type = \sanitize_key($type);
                    if (!isset($templates[$type])) {
                        $templates[$type] = [];
                    }
                    $templates[$type][$file] = \_cleanup_header_comment($header[1]);
                }
            }
        }
        // NOTE: We collect $_templates, not $templates.
        return \Modular\ConnectorDependencies\collect($_templates)->merge($templates[$post_type] ?? [])->unique()->toArray();
    }
    /**
     * Get all possibles names of template
     *
     * @param array $files
     *
     * @return array
     */
    public function filterTemplateHierarchy($files)
    {
        return $this->app['view.finder']->locate($files);
    }
    /**
     * Get view data attached.
     *
     * @param string $view
     * @return mixed
     */
    private function getDataFromController(string $view)
    {
        /**
         * @var \Ares\View\FileViewFinder $finder
         */
        $finder = $this->app['view.finder'];
        $controller = $finder->getPossibleControllerFromView($view);
        $generalController = $finder->getPossibleControllerFromView('general');
        $controllers = \Modular\ConnectorDependencies\collect([$controller, $generalController]);
        return $controllers->reduce(function ($data, $controller) {
            if (!!$controller) {
                $controller = new $controller();
                $data = (array) $data;
                $controller->attributesToArray($data);
                return \array_merge($controller->getAttributes(), $data);
            }
            return \array_merge([], $data);
        }, []);
    }
    /**
     * Include compiled Blade view with data attached.
     *
     * Filter: template_include
     *
     * @param string $file
     * @return string
     */
    public function filterTemplateInclude($file)
    {
        $view = \realpath($file);
        $view = $this->app['view.finder']->getPossibleViewNameFromPath($view);
        if (View::exists($view)) {
            $data = $this->getDataFromController($view);
            echo View::make($view, $data)->render();
        } else {
            if ($this->app['view.finder']->isPluginPath($file)) {
                return $file;
            }
        }
        return $this->app->basePath('/index.php');
    }
    /**
     * Include compiled blade view with for comments.
     *
     * Filter: comments_template
     *
     * @param string $file
     * @return string
     */
    public function filterCommentsTemplate($file)
    {
        $view = \realpath($file);
        $view = $this->app['view.finder']->getPossibleViewNameFromPath($view);
        $view = \trim($view, '\\/.');
        if (View::exists($view)) {
            echo View::make($view)->render();
        } else {
            if ($this->app['view.finder']->isPluginPath($file)) {
                return $file;
            }
        }
        return $this->app->basePath('/index.php');
    }
    /**
     * Include compiled Blade of Woocommerce template
     *
     * Filter: wc_get_template
     *
     * @param string $file
     * @param string $template
     * @param array $data
     * @return string
     */
    public function filterWCTemplate($file, $template, $data = [])
    {
        $data = (array) $data;
        $view = 'woocommerce.' . \trim(Str::replace('/', '.', $template), '\\/.');
        $view = $this->app['view.finder']->getPossibleViewNameFromPath($view);
        // First search by template name from WooCommerce Filter
        if (View::exists($view)) {
            echo View::make($view, $data)->render();
            return $this->app->basePath('/index.php');
        }
        return $this->filterTemplateInclude($file);
    }
    /**
     * Include compiled Blade of Woocommerce template part
     *
     * Filter: wc_get_template_part
     *
     * @param string $file
     * @param string $slug
     * @param string $name
     * @return string
     */
    public function filterWCTemplatePart($file, $slug, $name = '')
    {
        $data = $name ? $this->getDataFromController($name) : [];
        // First search by template name from WooCommerce Filter
        $view = "woocommerce.{$slug}-{$name}";
        if (View::exists($view)) {
            echo View::make($view, $data)->render();
            return $this->app->basePath('/index.php');
        }
        // First search by template name from WooCommerce Filter
        $view = "woocommerce.templates.{$slug}-{$name}";
        if (View::exists($view)) {
            echo View::make($view, $data)->render();
            return $this->app->basePath('/index.php');
        }
        // First search by template name from WooCommerce Filter
        $view = "woocommerce.{$slug}";
        if (View::exists($view)) {
            echo View::make($view, $data)->render();
            return $this->app->basePath('/index.php');
        }
        $view = \realpath($file);
        $view = $this->app['view.finder']->getPossibleViewNameFromPath($view);
        $view = \trim($view, '\\/.');
        if (View::exists($view)) {
            $data = $this->getDataFromController($view);
            echo View::make($view, $data)->render();
        } else {
            if ($this->app['view.finder']->isPluginPath($file)) {
                return $file;
            }
        }
        return $this->app->basePath('/index.php');
    }
}
