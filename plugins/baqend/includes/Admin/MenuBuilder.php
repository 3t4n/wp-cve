<?php

namespace Baqend\WordPress\Admin;

/**
 * Class MenuBuilder created on 2018-07-23.
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\WordPress
 */
class MenuBuilder {

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $icon = '';

    /**
     * @var string
     */
    private $main;

    /**
     * @var array
     */
    private $entries = [];

    /**
     * Builds the WordPress menu.
     *
     * @return array
     */
    public function build() {
        if ( $this->title === null || $this->slug === null || $this->main === null ) {
            throw new \LogicException( 'You must specify main entry, slug, and title.' );
        }

        $capability = 'manage_options';

        // Main menu entry
        list( $slug, $parent_title, $parent_callback ) = $this->find_entry_by_slug( $this->main );
        $parent_slug = $slug;
        $page_title  = $parent_title . ' &lsaquo; ' . $this->title;
        add_menu_page( $page_title, $this->title, $capability, $parent_slug, $parent_callback, $this->icon );

        // Submenus
        $entries = [];
        foreach ( $this->entries as list( $child_slug, $title, $callback, $highlighted ) ) {
            $slug       = $child_slug;
            $page_title = $title . ' &lsaquo; ' . $this->title;
            $menu_title = $highlighted ? '<span class="menu-highlighted">' . $title . '</span>' : $title;
            $html_id    = str_replace( '_', '-', $child_slug );
            add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $slug, $callback );
            $entries[] = [ $slug, $html_id, $title, $highlighted ];
        }

        return $entries;
    }

    /**
     * Add an entry.
     *
     * @param string $label
     * @param string $slug
     * @param callable $callback
     * @param bool $highlighted
     *
     * @return MenuBuilder
     */
    public function entry( $label, $slug, callable $callback, $highlighted = false ) {
        $this->entries[] = [ $slug, $label, $callback, $highlighted ];

        return $this;
    }

    /**
     * @param string $title
     *
     * @return MenuBuilder
     */
    public function title( $title ) {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $slug
     *
     * @return MenuBuilder
     */
    public function slug( $slug ) {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @param string $icon
     *
     * @return MenuBuilder
     */
    public function icon( $icon ) {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @param string $main
     *
     * @return MenuBuilder
     */
    public function main( $main ) {
        if ( $this->find_entry_by_slug( $main ) === null ) {
            throw new \InvalidArgumentException( "There is no entry with slug '$main'.'" );
        }
        $this->main = $main;

        return $this;
    }

    /**
     * @param string $slug
     *
     * @return array|null
     */
    private function find_entry_by_slug( $slug ) {
        foreach ( $this->entries as $entry ) {
            list( $entry_slug ) = $entry;
            if ( $slug === $entry_slug ) {
                return $entry;
            }
        }

        return null;
    }
}
