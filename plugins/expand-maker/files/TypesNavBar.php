<?php

namespace yrm;

class TypesNavBar
{
    public static function render()
    {

        ob_start();
        ?>
        <div id="crontrol-header-yrm-groups">
            <ul class="nav nav-tab-wrapper">
                <?php echo self::renderOptions()?>
            </ul>
        </div>
        <?php
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    private static function renderOptions()
    {
        global $YRM_TYPES;
        $groups = $YRM_TYPES['groupList'];
        $activeGroupName = self::getActiveGroupName();
        $url = YRM_TYPES_PAGE_URL;
        $urls = '';

        foreach ($groups as $groupKey => $groupTitle) {
            $activeClass = '';

            if ($activeGroupName == $groupKey) {
                $activeClass = 'nav-tab-active';
            }
            $urls .= '<a href="'.esc_attr($url).'&yrm_group_name='.esc_attr($groupKey).'" class="nav-tab '.esc_attr($activeClass).'">'.esc_attr($groupTitle).'</a>';
        }

        return $urls;
    }

    private static function getActiveGroupName()
    {
        $groupName = 'all';
        if (!empty($_GET['yrm_group_name'])) {
            $groupName = esc_attr($_GET['yrm_group_name']);
        }

        return $groupName;
    }
}