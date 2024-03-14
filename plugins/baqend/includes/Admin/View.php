<?php

namespace Baqend\WordPress\Admin;

/**
 * A view wraps rendering logic to the outside.
 *
 * User: Konstantin Simon Maria Möllers
 * Date: 20.06.17
 * Time: 09:37
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\WordPress\Admin
 */
class View {

    /**
     * @var string
     */
    private $template;

    /**
     * @var string[]
     */
    private $variables = [];

    /**
     * @var array
     */
    private $tabs = [];

    /**
     * @param string $template
     *
     * @return $this
     */
    public function set_template( $template ) {
        $this->template = $template;

        return $this;
    }

    /**
     * @param string $varName
     * @param mixed $value
     *
     * @return $this
     */
    public function assign( $varName, $value ) {
        $this->variables[ $varName ] = $value;

        return $this;
    }

    /**
     * Renders the view
     *
     * @return $this
     */
    public function render() {
        $file = $this->get_renderable_file();

        extract( $this->variables );

        include $file;

        return $this;
    }

    public function set_tabs( array $tabs ) {
        $this->tabs = $tabs;
    }

    private function tabs() {
        ?><h2 id="baqend-tabs" class="nav-tab-wrapper">
        <?php foreach ( $this->tabs as list( $slug, $html_id, $label, $highlighted ) ): ?>
            <?php $this->tab( $slug, $html_id . '-tab', $label, $highlighted ); ?>
        <?php endforeach; ?>
        </h2><?php
    }

    /**
     * Renders a tab.
     *
     * @param string $page The tab's targeted page.
     * @param string $id The HTML ID.
     * @param string $label The label of the tab.
     * @param bool $highlighted If the tab should be highlighted.
     */
    private function tab( $page, $id, $label, $highlighted = false ) {
        global $pagenow;
        $current_page = $pagenow === 'admin.php' ? $_GET['page'] : null;

        $class = $current_page === $page ? 'nav-tab nav-tab-active' : 'nav-tab';
        if ( $highlighted ) {
            $class .= ' nav-tab-highlighted';
            $label = '<i class="iqon-trophy"></i> ' . $label;
        }
        $href = baqend_admin_url( $page );

        echo <<<HTML
<a class="$class" id="$id" href="$href">$label</a>
HTML;
    }

    /**
     * Get the filename of the renderable file
     *
     * @return string
     */
    private function get_renderable_file() {
        return $this->get_view_dir() . $this->template;
    }

    /**
     * Get the view directory
     *
     * @return string
     */
    public function get_view_dir() {
        return plugin_dir_path( dirname( __DIR__ ) ) . 'views/';
    }
}
