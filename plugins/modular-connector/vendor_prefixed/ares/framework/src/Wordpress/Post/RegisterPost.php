<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Wordpress\Post;

/**
 * @link https://developer.wordpress.org/reference/functions/register_post_type/
 * @internal
 */
abstract class RegisterPost implements RegisterPostInterface
{
    /**
     * Post type key. Must not exceed 20 characters and may
     * only contain lowercase alphanumeric characters,
     * dashes, and underscores.
     *
     * @var string
     * @link https://developer.wordpress.org/reference/functions/register_post_type/
     */
    protected string $postType;
    /**
     * Core feature(s) the post type supports.
     *
     * @var array
     * @link https://developer.wordpress.org/reference/functions/register_post_type/#supports
     */
    protected array $supports = [];
    /**
     * Core feature(s) the post type supports to remove.
     *
     * @var array
     * @link https://developer.wordpress.org/reference/functions/register_post_type/#supports
     */
    protected array $unsupported = [];
    /**
     * Whether a post type is intended for use publicly either
     * via the admin interface or by front-end users.
     *
     * @var bool
     * @link https://developer.wordpress.org/reference/functions/register_post_type/#public
     */
    protected bool $public = \true;
    /**
     * Whether the post type is hierarchical
     *
     * @var bool
     * @link https://developer.wordpress.org/reference/functions/register_post_type/#hierarchical
     */
    protected bool $hierarchical = \false;
    /**
     * Whether queries can be performed on the front end
     * for the post type as part of parse_request()
     *
     * @var bool
     * @link https://developer.wordpress.org/reference/functions/register_post_type/#publicly_queryable
     */
    protected bool $publiclyQueryable = \true;
    /**
     * Whether to generate and allow a UI
     * for managing this post type
     * in the admin
     *
     * @var bool
     * @link https://developer.wordpress.org/reference/functions/register_post_type/#show_ui
     */
    protected bool $showUi = \true;
    /**
     * Where to show the post type in the admin menu.
     * To work, $show_ui must be true. If true,
     * the post type is shown in
     * its own top level menu.
     * If false, no menu
     * is shown
     *
     * @var bool
     * @link https://developer.wordpress.org/reference/functions/register_post_type/#show_in_menu
     */
    protected bool $showInMenu = \true;
    /**
     * Makes this post type available via the admin bar
     *
     * @var bool
     * @link https://developer.wordpress.org/reference/functions/register_post_type/#show_in_admin_bar
     */
    protected bool $showInAdminBar = \true;
    /**
     * The url to the icon to be used for this menu
     *
     * @var string
     * @link https://developer.wordpress.org/reference/functions/register_post_type/#menu_icon
     */
    protected string $menuIcon = 'dashicons-admin-users';
    /**
     * Order in menu
     *
     * @var int
     * @link https://developer.wordpress.org/reference/functions/register_post_type/#menu_position
     */
    protected int $menuPosition = 52;
    /**
     * Array of capabilities for this post type
     *
     * @var array|string
     * @link https://developer.wordpress.org/reference/functions/register_post_type/#capability_type
     */
    protected string $capabilityType = 'post';
    /**
     * Whether there should be post type archives,
     * or if a string, the archive slug to use.
     *
     * @var bool
     * @link https://developer.wordpress.org/reference/functions/register_post_type/#has_archive
     */
    protected bool $hasArchive = \true;
    /**
     * Whether to allow this post type to be exported
     *
     * @var bool
     * @link https://developer.wordpress.org/reference/functions/register_post_type/#can_export
     */
    protected bool $canExport = \true;
    /**
     * Singular name for labels
     *
     * @return string
     */
    protected abstract function singularName() : string;
    /**
     * Plural name for labels
     *
     * @return string
     */
    protected abstract function label() : string;
    /**
     * @return array
     * @link https://developer.wordpress.org/reference/functions/get_post_type_labels/
     */
    protected function labels() : array
    {
        return ['singular_name' => \__($this->singularName(), 'ares')];
    }
    /**
     * Return post type
     *
     * @return string
     */
    public function postType() : string
    {
        return $this->postType;
    }
    /**
     *  A short descriptive summary of what the post type is.
     *
     * @return string
     * @link https://developer.wordpress.org/reference/functions/register_post_type/#description
     */
    protected abstract function description() : string;
    /**
     * Rewrite section for labels
     * Return [] if not needed
     *
     * @return array
     * @link https://developer.wordpress.org/reference/functions/register_post_type/#rewrite
     */
    protected function rewrite() : array
    {
        return [];
    }
    /**
     * Custom post type data
     *
     * @return array
     */
    public function getPostType() : array
    {
        return ['label' => $this->label(), 'labels' => $this->labels(), 'description' => $this->description(), 'supports' => $this->supports, 'public' => $this->public, 'publicly_queryable' => $this->publiclyQueryable, 'hierarchical' => $this->hierarchical, 'has_archive' => $this->hasArchive, 'can_export' => $this->canExport, 'show_in_admin_bar' => $this->showInAdminBar, 'show_ui' => $this->showUi, 'show_in_menu' => $this->showInMenu, 'menu_position' => $this->menuPosition, 'menu_icon' => $this->menuIcon, 'capability_type' => $this->capabilityType, 'rewrite' => $this->rewrite()];
    }
    /**
     * Register new post type
     *
     * @return $this
     */
    protected function registerPostType() : self
    {
        \register_post_type($this->postType, $this->getPostType());
        return $this;
    }
    /**
     * Register new taxonomy (category)
     *
     * @return $this
     */
    protected function unregisterSupports() : self
    {
        if ($unsupported = $this->unsupported) {
            foreach ($unsupported as $support) {
                \remove_post_type_support($this->postType, $support);
            }
        }
        return $this;
    }
    /**
     * Call methods for register options
     */
    public function register() : void
    {
        $this->registerPostType()->unregisterSupports();
    }
}
