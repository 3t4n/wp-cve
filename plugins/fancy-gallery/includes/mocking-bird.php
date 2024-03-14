<?php

namespace WordPress\Plugin\GalleryManager;

abstract class Mocking_Bird
{
    public static
        $banner_id;

    public static function init()
    {
        add_Action('gallery_manager_lightbox_wrapper', [static::class, 'printLighboxBranding']);
        add_Action('admin_menu', [static::class, 'addProLink'], 20);
        add_Action('registered_post_type', [static::class, 'changePostTypeLabels'], 10, 2);
        add_Action('all_admin_notices', [static::class, 'printProBanner']);
        add_Action('admin_print_footer_scripts', [static::class, 'moveProBannerInsideBlockEditor'], 20);
    }

    public static function getProNotice(string $message_id): string
    {
        $arr_message = [
            'upgrade' => I18n::__('Upgrade to Pro'),
            'upgrade_url' => '%s',
            'feature' => I18n::__('This feature is available in the <a href="%s" target="_blank">premium version</a>.'),
            'unlock' => sprintf('<a href="%%s" title="%s" class="upgrade-gallery-manager" target="_blank"><span class="dashicons dashicons-lock"></span><span class="dashicons dashicons-unlock onhover"></span> <span class="onhover">%s</span></a>', I18n::__('Unlock this feature'), I18n::__('Upgrade to Pro')),
        ];

        $website_url = I18n::_x('https://dennishoppe.de/en/wordpress-plugins/gallery-manager', 'Link to the authors website');

        if (empty($arr_message[$message_id]))
            return '';
        else
            return sprintf($arr_message[$message_id], $website_url);
    }

    public static function printProNotice($message_id)
    {
        echo static::getProNotice($message_id);
    }

    public static function countItems()
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

    public static function printLighboxBranding()
    {
        if (current_User_Can('install_plugins')) : ?>
            <div style="display:block;position:absolute;top:0;left:0;width:100%;text-align:center;line-height:1.15em">
                <a href="<?php echo static::getProNotice('upgrade_url') ?>" title="<?php echo static::getProNotice('upgrade') ?>" target="_blank" style="display:inline-block;background:rgb(70,180,80);border-radius:0 0 7px 7px;font-size:1.2em;padding:7px;line-height:inherit;color:white;text-decoration:none">
                    <?php I18n::_e('If you like this free version of the lightbox, you will love <u><strong>Gallery Manager Pro</strong></u>!') ?>
                </a>
            </div>
        <?php endif;
    }

    public static function addProLink()
    {
        if (static::countItems() >= 2) {
            $menu_label = '<span style="color:#00a32a;font-weight:bold;text-transform:uppercase">' . static::getProNotice('upgrade') . '</span>';
            add_SubMenu_Page('edit.php?post_type=' . PostType::post_type_name, Null, $menu_label, 'edit_posts', static::getProNotice('upgrade_url'));
        }
    }

    public static function changePostTypeLabels($post_type, $post_type_obj)
    {
        if ($post_type == PostType::post_type_name && is_Admin() && static::countItems() >= 3) {
            $suffix = sprintf(' (%s)', I18n::__('Free Version'));
            $post_type_obj->labels->name .= $suffix;
            $post_type_obj->labels->menu_name .= $suffix;
        }
    }

    public static function printProBanner()
    {
        global $current_screen;
        static::$banner_id = uniqid();

        $green = '#00a32a';

        if ($current_screen->base == 'settings_page_gallery-options' || ($current_screen->post_type == PostType::post_type_name && static::countItems() >= 5)) : ?>
            <div id="<?php echo static::$banner_id ?>" style="margin-top:20px;position:relative;width:90%;max-width:726px;display:block;clear:both;border:2px solid <?php echo $green ?>;">
                <a href="<?php static::printProNotice('upgrade_url') ?>" title="<?php static::printProNotice('upgrade') ?>" target="_blank" style="text-decoration:none">
                    <img src="<?php echo Core::$base_url ?>/assets/img/plugin-logo-1544x500.png" alt="" width="1544" height="500" style="width:100%;height:auto;display:block;margin:0;padding:0">
                    <span style="position:absolute;top:0;right:0;background:#D3D3D3;border-radius:0 0 0 5px;color:black;padding:3px 5px;display:inline-block"><?php I18n::_e('Enjoy all features of the Pro Version') ?></span>
                    <span style="position:absolute;bottom:0;left:0;background:<?php echo $green ?>;border-radius:0 5px 0 0;color:white;padding:3px 5px;display:inline-block"><?php I18n::_e('If you like the free version, you will love <u><strong>Gallery Manager Pro</strong></u>!') ?></span>
                </a>
            </div>
        <?php endif;
    }

    public static function moveProBannerInsideBlockEditor($post_type)
    {
        global $current_screen;
        if (static::$banner_id && $current_screen->base == 'post' && $current_screen->post_type == PostType::post_type_name) : ?>
            <script type="text/javascript">
                (function($) {
                    var
                        $body = $('body'),
                        block_editor_active = $body.hasClass('block-editor-page'),
                        banner_id = '<?php echo static::$banner_id ?>',
                        $banner = $('div#' + banner_id + ':first'),
                        moveProBanner = function() {
                            var $editor_part = $('div.editor-post-title:first');
                            if ($editor_part.length) {
                                stopSearchTimer();
                                $banner
                                    .insertBefore($editor_part)
                                    .css({
                                        margin: '0 auto',
                                        fontSize: '14px'
                                    });
                            }
                        },
                        searchTimer = block_editor_active ? window.setInterval(moveProBanner, 333) : false,
                        stopSearchTimer = function() {
                            window.clearInterval(searchTimer);
                        },
                        emergencyStopTimer = searchTimer ? window.setTimeout(stopSearchTimer, 10000) : false;
                }(jQuery));
            </script>
<?php endif;
    }
}

Mocking_Bird::init();
