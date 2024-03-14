<?php

namespace VisualComposer\Modules\Editors\Frontend;

use VisualComposer\Framework\Container;
use VisualComposer\Framework\Illuminate\Support\Module;
use VisualComposer\Helpers\Access\UserCapabilities;
use VisualComposer\Helpers\Traits\EventsFilters;
use VisualComposer\Helpers\Traits\WpFiltersActions;
use VisualComposer\Helpers\Url;

class MenuController extends Container implements Module
{
    use WpFiltersActions;
    use EventsFilters;

    protected $bufferStarted = false;

    public function __construct()
    {
        /** WordPress admin_menu */
        $this->wpAddAction('admin_footer', 'addStyleFixes');
        $this->wpAddAction('admin_menu', 'updateAddNewMenus');

        /** WordPress edit.php page */
        $this->wpAddAction(
            'in_admin_header',
            'startBuffer'
        );
        $this->wpAddAction(
            'admin_footer',
            'endBuffer'
        );

        $wpMenuHelper = vchelper('WpMenu');
        $this->addFilter(
            'vcv:ajax:dropdown:menu:updateList:adminNonce',
            [$wpMenuHelper, 'getMenuList'],
            11
        );
    }

    protected function startBuffer()
    {
        // @codingStandardsIgnoreLine
        global $parent_file;
        // @codingStandardsIgnoreLine
        if (isset($parent_file) && preg_match('/(?:edit|post).php(.*)?/', $parent_file)) {
            $this->bufferStarted = true;
            ob_start(); // Starting buffer to overwrite page-title-action link "Add New"
        }
    }

    protected function endBuffer(UserCapabilities $userCapabilitiesHelper)
    {
        // @codingStandardsIgnoreLine
        global $parent_file, $post_type;
        // @codingStandardsIgnoreLine
        if (!$this->bufferStarted) {
            return;
        }
        // @codingStandardsIgnoreLine
        if (isset($parent_file) && preg_match('/(?:edit|post).php(?:\?post_type=)?(.*)?/', $parent_file, $matches)) {
            $content = ob_get_clean();
            $this->bufferStarted = false;
            // @codingStandardsIgnoreLine
            $postType = !empty($matches[1]) ? $matches[1] : $post_type;
            $postTypesList = [
                'vcv_headers',
                'vcv_footers',
                'vcv_sidebars',
                'vcv_templates',
                'vcv_popups',
                'vcv_layouts',
            ];
            if (
                $userCapabilitiesHelper->isEditorEnabled($postType)
                && !in_array($postType, $postTypesList)
            ) {
                $content = preg_replace_callback(
                    '/\<[a] href="(.[^\"]+)" class="page-title-action"\>(.[^\<\/]+)\<\/a\>/',
                    function ($data) {
                        $urlHelper = vchelper('Url');
                        $newUrl = vcfilter(
                            'vcv:frontend:url',
                            $urlHelper->query($data[1], ['vcv-action' => 'frontend']),
                            ['query' => ['vcv-action' => 'frontend'], 'sourceId' => null]
                        );
                        $newLink = '<a href="' . $newUrl . '" class="page-title-action">' .
                            __(
                                'Add New with Visual Composer',
                                'visualcomposer'
                            ) . '</a>';

                        return $data[0] . $newLink;
                    },
                    $content
                );
            }
            $outputHelper = vchelper('Output');
            $outputHelper->printNotEscaped($content);
        }
    }

    protected function addStyleFixes()
    {
        evcview('settings/partials/admin-menu');
    }

    protected function updateAddNewMenus()
    {
        global $submenu;
        if (isset($submenu) && !empty($submenu)) {
            foreach ($submenu as $key => $linksData) {
                $match = preg_match('/edit.php(?:\?post_type\=)?(.*)?/', $key, $matches);
                if ($match) {
                    $postType = !empty($matches[1]) ? $matches[1] : 'post';
                    /** @see \VisualComposer\Modules\Editors\Frontend\MenuController::addLinkToSubmenu */
                    $this->call(
                        'addLinkToSubmenu',
                        [
                            $postType,
                            $key,
                            $linksData,
                        ]
                    );
                }
            }
        }
    }

    /**
     * @param $postType
     * @param $key
     * @param $linksData
     * @param \VisualComposer\Helpers\Access\UserCapabilities $userCapabilitiesHelper
     * @param \VisualComposer\Helpers\Url $urlHelper
     */
    protected function addLinkToSubmenu(
        $postType,
        $key,
        $linksData,
        UserCapabilities $userCapabilitiesHelper,
        Url $urlHelper
    ) {
        $globalsHelper = vchelper('Globals');
        $submenuCopy = $globalsHelper->get('submenu');
        if ($userCapabilitiesHelper->isEditorEnabled($postType)) {
            foreach ($linksData as $linkIndex => $link) {
                $linkMatch = preg_match('/post-new.php(.*)?/', $link[2]);
                if ($linkMatch) {
                    // Now we know the post_type and can check for "Add New"
                    $newIndex = $linkIndex + 1;
                    while (isset($submenuCopy[ $key ][ $newIndex ])) {
                        $newIndex++;
                    }
                    $linkInfo = isset($link[3]) ? $link[3] : null;
                    $linkClass = isset($link[4]) ? $link[4] : null;
                    $linkClass .= ' vcv-dashboard-admin-menu--add';
                    $submenuCopy[ $key ][ $newIndex ] = [
                        __('Add New with Visual&nbsp;Composer', 'visualcomposer'),
                        $link[1],
                        vcfilter(
                            'vcv:frontend:url',
                            $urlHelper->query($link[2], ['vcv-action' => 'frontend']),
                            ['query' => ['vcv-action' => 'frontend'], 'sourceId' => null]
                        ),
                        $linkInfo,
                        $linkClass,
                    ];
                }
            }
            $globalsHelper->set('submenu', $submenuCopy);
        }
    }
}
