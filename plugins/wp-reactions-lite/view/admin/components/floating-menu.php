<?php
use WP_Reactions\Lite\Config;
?>
<div class="floating-menu">
    <ul>
        <?php
        foreach (Config::$top_menu_items as $top_menu_item) {
            $showOnLicense = isset($top_menu_item["showOnLicense"]) ? $top_menu_item["showOnLicense"] : false;
            $active_class = '';
            if (strpos($top_menu_item["link"], $_GET['page']) !== false) {
                $active_class = 'active';
            }
            ?>
            <li>
                <a class="<?php echo $active_class; ?>"
                   target="<?php echo $top_menu_item["target"]; ?>"
                   href="<?php echo $top_menu_item["link"]; ?>">
                    <span class="<?php echo $top_menu_item["icon"]; ?>"></span><?php echo $top_menu_item["name"]; ?>
                </a>
            </li>
            <?php
        }
        ?>
    </ul>
</div>
