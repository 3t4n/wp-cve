<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Wordpress\Template;

use Modular\ConnectorDependencies\Ares\Framework\Wordpress\Post\RegisterPost;
/** @internal */
class LayoutCustomPost extends RegisterPost
{
    /**
     * Core feature(s) the post type supports.
     *
     * @var array
     * @link https://developer.wordpress.org/reference/functions/register_post_type/#supports
     */
    protected array $supports = ['title', 'editor', 'author'];
    /**
     * Post type key. Must not exceed 20 characters and may
     * only contain lowercase alphanumeric characters,
     * dashes, and underscores.
     *
     * @var string
     * @link https://developer.wordpress.org/reference/functions/register_post_type/
     */
    protected string $postType = 'ares_layouts';
    /**
     * Whether a post type is intended for use publicly either
     * via the admin interface or by front-end users.
     *
     * @var bool
     * @link https://developer.wordpress.org/reference/functions/register_post_type/#public
     */
    protected bool $public = \false;
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
    protected string $menuIcon = 'dashicons-editor-kitchensink';
    /**
     * order in menu
     *
     * @var int
     * @link https://developer.wordpress.org/reference/functions/register_post_type/#menu_position
     */
    protected int $menuPosition = 60;
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
    protected bool $hasArchive = \false;
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
    protected function singularName() : string
    {
        return \__('Layout', 'ares');
    }
    /**
     * Plural name for labels
     *
     * @return string
     */
    protected function label() : string
    {
        return \__('Layouts', 'ares');
    }
    /**
     *  A short descriptive summary of what the post type is.
     *
     * @return string
     * @link https://developer.wordpress.org/reference/functions/register_post_type/#description
     */
    protected function description() : string
    {
        return '';
    }
}
