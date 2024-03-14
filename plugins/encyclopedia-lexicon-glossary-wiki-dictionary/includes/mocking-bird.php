<?php

namespace WordPress\Plugin\Encyclopedia;

abstract class MockingBird
{
    public static
        $banner_id;

    public static function init(): void
    {
        add_action('admin_menu', [static::class, 'addProLink'], 20);
        add_action('registered_post_type', [static::class, 'changePostTypeLabels'], 10, 2);
        add_action('all_admin_notices', [static::class, 'printProBanner']);
        add_action('enqueue_block_editor_assets', [static::class, 'moveProBannerInsideBlockEditor'], 20);
    }

    public static function getProNotice(string $message_id = 'option'): string
    {
        $author_link = I18n::_x('https://dennishoppe.de/en/wordpress-plugins/encyclopedia', 'Link to the authors website');

        $arr_message = [
            'upgrade' => I18n::__('Upgrade to Pro'),
            'upgrade_url' => $author_link,
            'feature' => sprintf(I18n::__('Available in the <a href="%s" target="_blank">Pro Version</a> only.'), $author_link),
            'unlock' => sprintf('<a href="%1$s" title="%2$s" class="upgrade-encyclopedia" target="_blank"><span class="dashicons dashicons-lock"></span><span class="dashicons dashicons-unlock onhover"></span> <span class="onhover">%3$s</span></a>', $author_link, I18n::__('Unlock this feature'), I18n::__('Upgrade to Pro')),
            'option' => sprintf(I18n::__('This option is changeable in the <a href="%s" target="_blank">Pro Version</a> only.'), $author_link),
            'count_limit' => sprintf(I18n::__('In the <a href="%1$s" target="_blank">Pro Version of Encyclopedia</a> you will take advantage of unlimited %2$s and many more features.'), $author_link, PostTypeLabels::getItemPluralName()),
        ];

        if (empty($arr_message[$message_id]))
            return '';
        else
            return $arr_message[$message_id];
    }

    public static function printProNotice(string $message_id = 'option'): void
    {
        echo static::getProNotice($message_id);
    }

    public static function countItems(): int
    {
        static $count = -1;
        if ($count < 0) {
            $count_items = (array) WP_Count_Posts(PostType::post_type_name);
            $count_items = Array_Merge([
                'publish' => 0,
                'future' => 0,
                'pending' => 0,
                'draft' => 0,
                'private' => 0,
            ], $count_items);
            $count_items = Array_Map('intval', $count_items);
            $count = $count_items['publish'] + $count_items['future'] + $count_items['pending'] + $count_items['draft'] + $count_items['private'];
        }
        return $count;
    }

    public static function addProLink(): void
    {
        if (static::countItems() >= 2) {
            $menu_label = '<span style="color:#00a32a;font-weight:bold;text-transform:uppercase">' . static::getProNotice('upgrade') . '</span>';
            add_SubMenu_Page('edit.php?post_type=' . PostType::post_type_name, null, $menu_label, 'edit_posts', static::getProNotice('upgrade_url'));
        }
    }

    public static function changePostTypeLabels(string $post_type, $post_type_obj): void
    {
        if ($post_type == PostType::post_type_name && is_Admin() && static::countItems() >= 5) {
            $suffix = sprintf(' (%s)', I18n::__('Free Version'));
            $post_type_obj->labels->name .= $suffix;
            $post_type_obj->labels->menu_name .= $suffix;
        }
    }

    public static function printProBanner(): void
    {
        global $current_screen;
        static::$banner_id = uniqid(chr(random_int(97, 122)));

        $green = '#00a32a';
        $gray = '#c3c4c7';

        if ($current_screen->base == 'settings_page_encyclopedia-options' || ($current_screen->post_type == PostType::post_type_name && static::countItems() >= 8)) : ?>
            <div id="<?php echo static::$banner_id ?>">
                <a href="<?php static::printProNotice('upgrade_url') ?>" title="<?php static::printProNotice('upgrade') ?>" target="_blank">
                    <span style="top:0;right:0;background:<?php echo $gray ?>;border-radius:0 0 0 5px;color:black;"><?php echo I18n::__('Enjoy all features of the Pro Version') ?></span>
                    <img src="<?php echo Core::$base_url ?>/assets/img/plugin-logo-1544x500.png" alt="" width="1544" height="500">
                    <span style="bottom:0;left:0;border-radius:0 5px 0 0;"><?php echo I18n::__('If you like the free version, you will love <u><strong>Encyclopedia Pro</strong></u>!') ?></span>
                </a>
            </div>
            <style type="text/css">
                #<?php echo static::$banner_id ?> {
                    margin-top: 20px;
                    position: relative;
                    width: 90%;
                    max-width: 726px;
                    display: block;
                    clear: both;
                    border: 2px solid <?php echo $green ?>;
                }

                .block-editor #<?php echo static::$banner_id ?> {
                    width: 280px;
                    margin: 0;
                }

                #<?php echo static::$banner_id ?>>a {
                    display: block;
                    text-decoration: none;
                }

                #<?php echo static::$banner_id ?>>a>img {
                    display: block;
                    width: 100%;
                    height: auto;
                    margin: 0;
                    padding: 0;
                }

                #<?php echo static::$banner_id ?>>a>span {
                    position: absolute;
                    background: <?php echo $green ?>;
                    color: white;
                    padding: 3px 5px;
                    display: inline-block;
                }

                .block-editor #<?php echo static::$banner_id ?>>a>span {
                    position: static;
                    display: block;
                }
            </style>
            <script type="text/javascript">
                const moveEncyclopediaProBannerInsideBlockEditor = function() {
                    let {
                        select,
                        subscribe
                    } = wp.data ?? {};

                    let
                        body = document.querySelector('body'),
                        block_editor_active = body ? body.classList.contains('block-editor-page') : false,
                        banner_id = '<?php echo static::$banner_id ?>',
                        banner = document.getElementById(banner_id),
                        banner_moved = false,
                        listener = subscribe ? subscribe(function() {
                            if (banner_moved) return;

                            let editor_sidebar = document.querySelector('.edit-post-sidebar');

                            if (editor_sidebar) {
                                editor_sidebar.before(banner);
                                banner_moved = true;
                            }
                        }) : false;
                }
            </script>
        <?php endif;
    }

    public static function moveProBannerInsideBlockEditor(): void
    {
        wp_add_inline_script('wp-editor', '
            document.addEventListener("DOMContentLoaded", function(){
                if (typeof moveEncyclopediaProBannerInsideBlockEditor === "function")
                    moveEncyclopediaProBannerInsideBlockEditor();
            });            
        ');
    }
}

MockingBird::init();
