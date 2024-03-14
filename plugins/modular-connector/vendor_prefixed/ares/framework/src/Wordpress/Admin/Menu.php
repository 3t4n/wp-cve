<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Wordpress\Admin;

use Modular\ConnectorDependencies\Illuminate\Contracts\View\Factory as ViewFactory;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\App;
/**
 * This class register menus in the admin panel.
 *
 * @link https://developer.wordpress.org/reference/functions/add_menu_page/
 * @link https://developer.wordpress.org/reference/functions/add_submenu_page/
 * @link https://developer.wordpress.org/reference/functions/add_options_page/
 * @link https://developer.wordpress.org/reference/functions/add_management_page/
 * @link https://developer.wordpress.org/reference/functions/add_theme_page/
 * @link https://developer.wordpress.org/reference/functions/add_plugins_page/
 * @link https://developer.wordpress.org/reference/functions/add_users_page/
 * @link https://developer.wordpress.org/reference/functions/add_pages_page/
 * @link https://developer.wordpress.org/reference/functions/add_comments_page/
 * @link https://developer.wordpress.org/reference/functions/add_dashboard_page/
 * @link https://developer.wordpress.org/reference/functions/add_links_page/
 * @link https://developer.wordpress.org/reference/functions/add_media_page/
 * @link https://developer.wordpress.org/reference/functions/add_posts_page/
 * @since 1.1.0
 * @internal
 */
abstract class Menu implements MenuInterface
{
    /**
     * The capability required for this menu
     * to be displayed to the user.
     *
     * @var string
     */
    protected string $capability = 'activate_plugins';
    /**
     * The slug name to refer to this menu by. Should be unique for this
     * menu page and only include lowercase alphanumeric,
     * dashes, and underscores characters to be
     * compatible with sanitize_key().
     *
     * @var string
     */
    public string $slug;
    /**
     * Type of Menu Settings
     *
     * Allowed values: 'menu', 'submenu', 'options', 'management', 'theme', 'plugins', 'users', 'pages',
     *   'comments', 'dashboard', 'links', 'media', 'posts'
     *
     * @var string|null
     */
    protected string $type = 'menu';
    /**
     * The URL to the icon to be used for this menu.
     *
     * Pass a base64-encoded SVG using a data URI, which will be colored to match the color scheme.
     * This should begin with 'data:image/svg+xml;base64,'.
     *
     * Pass the name of a Dashicons helper class to use a font icon, e.g. 'dashicons-chart-pie'.
     *
     * Pass 'none' to leave div.wp-menu-image empty so an icon can be added via CSS.
     *
     * @var string|null
     */
    protected string $icon = '';
    /**
     * The position in the menu order this item should appear.
     *
     * @link https://developer.wordpress.org/reference/functions/add_menu_page/#menu-structure
     * @var int|null
     */
    protected ?int $position = null;
    /**
     * Submenu items which is going to display
     * Please, use classes
     *
     * @var array
     */
    protected array $submenus = [];
    /**
     * Indicate the final method
     * for a specific request method
     *
     * ['post' => 'method']
     *
     * @var array
     */
    protected array $methods = [];
    /**
     * The text to be used for the menu.
     *
     * @return  string
     */
    public abstract function menuTitle() : string;
    /**
     * The text to be displayed in the title tags of
     * the page when the menu is selected.
     *
     * @var string
     */
    public abstract function pageTitle() : string;
    /**
     * The function to be called to output the content for this page.
     *
     * @return void
     */
    public abstract function render() : void;
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param string|null $view
     * @param \Illuminate\Contracts\Support\Arrayable|array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    protected function view($view = null, $data = [], $mergeData = [])
    {
        $factory = App::make(ViewFactory::class);
        if (\func_num_args() === 0) {
            return $factory;
        }
        return $factory->make($view, $data, $mergeData);
    }
    public final function process()
    {
        $request = App::make('request');
        $method = 'render';
        if ($request->method() !== 'GET') {
            $method = $this->methods[\strtolower($request->method())] ?? \false;
        }
        if (!$method) {
            throw new \Exception('Method ' . $request->method() . ' not allowed');
        }
        // Call to method
        $this->{$method}();
    }
    /**
     * Register
     *
     * @return void
     * @throws \Exception
     */
    public function register()
    {
        if ($this->type === 'menu' && !empty($this->submenus)) {
            \array_walk($this->submenus, function ($submenu) {
                /**
                 * @var Submenu $submenu
                 */
                App::make($submenu)->setParent($this)->register();
            });
        }
        \add_action('admin_menu', function () {
            if ($this->type === 'menu') {
                \add_menu_page($this->pageTitle(), $this->menuTitle(), $this->capability, $this->slug, [$this, 'process'], $this->icon, $this->position);
            } else {
                if (\in_array($this->type, ['options', 'management', 'theme', 'plugins', 'users', 'pages', 'comments', 'dashboard', 'links', 'media', 'posts'])) {
                    /**
                     * @see add_management_page()
                     * @see add_theme_page()
                     * @see add_plugins_page()
                     * @see add_users_page()
                     * @see add_pages_page()
                     * @see add_comments_page()
                     * @see add_dashboard_page()
                     * @see add_links_page()
                     * @see add_media_page()
                     * @see add_posts_page()
                     */
                    $function = 'add_' . $this->type . '_page';
                    $function($this->pageTitle(), $this->menuTitle(), $this->capability, $this->slug, [$this, 'process'], $this->position);
                } else {
                    throw new \Exception('Unsupported type: ' . $this->type);
                }
            }
        });
    }
}
