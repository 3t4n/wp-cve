<?php

namespace AOP\App\Options;

class MenuPage
{
    /**
     * @var
     */
    private $pageTitle;

    /**
     * @var
     */
    private $menuTitle;

    /**
     * @var
     */
    private $capability;

    /**
     * @var
     */
    private $menuSlug;

    /**
     * @var
     */
    private $iconUrl;

    /**
     * @var
     */
    private $position;

    /**
     * @param $args
     */
    public function run($args)
    {
        $this->pageTitle  = $args['page_title'];
        $this->menuTitle  = $args['menu_title'];
        $this->capability = $args['capability'];
        $this->menuSlug   = $args['menu_slug'];
        $this->iconUrl    = $args['icon_url'];
        $this->position   = !empty($args['position']) ? $args['position'] : '30';

        add_action('admin_menu', function () {
            $this->addMenuPage();
        });
    }

    private function addMenuPage()
    {
        add_menu_page(
            $this->pageTitle,
            $this->menuTitle,
            $this->capability,
            $this->menuSlug,
            '',
            $this->iconUrl,
            $this->position
        );
    }
}
