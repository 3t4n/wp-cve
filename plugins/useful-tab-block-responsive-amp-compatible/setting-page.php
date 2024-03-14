<?php

namespace beginner_blogger_com_useful_tab_block_free;

defined('ABSPATH') || exit;

function premium_block()
{
?>
    <div class="premium-box">
        <div class="box-title"><?php _e("PREMIUM VERSION", 'useful-tab-block'); ?></div>
        <div class="box-content">
            <div class="column-2">
                <div>
                    <figure>
                        <video src="<?php echo plugins_url("images/premium.mov", __FILE__); ?>" controls muted autoplay playsinline loop></video>
                    </figure>
                </div>
                <div>
                    <p><?php _e("The premium version provides eight additional functionalities:", 'useful-tab-block') ?>
                    <ol class="utb-number-list has-border">
                        <li><?php _e("<strong>Rearrangement</strong> of tabs", "useful-tab-block"); ?></li>
                        <li><?php _e("<strong>Fontawesome icons</strong> in tab labels", "useful-tab-block"); ?></li>
                        <li><?php _e("<strong>Add/remove</strong> tabs more easily and intuitively", "useful-tab-block"); ?></li>
                        <li><?php _e("<strong>Custom colors</strong> for label texts and backgrounds", "useful-tab-block"); ?></li>
                        <li><?php _e("Cast <strong>shadow</strong>", "useful-tab-block"); ?></li>
                        <li><?php _e("Make tabs <strong>horizontally scrollable</strong>", "useful-tab-block"); ?></li>
                        <li><?php _e("<strong>Links</strong> to open the page with the specified tab opened", "useful-tab-block"); ?></li>
                        <li><?php _e("<strong>Color picker</strong> support", "useful-tab-block"); ?></li>
                    </ol>
                    <?php _e("You can add six favorite colors in your color palette!", 'useful-tab-block'); ?>
                    </p>

                    <div class="store-box">
                        <span class="store-title"><?php _e("Check it out!", 'useful-tab-block'); ?></span>
                        <ul class="utb-list chevron">
                            <li><a href="https://beginner-blogger-com.stores.jp" rel="noopener"><strong><?php _e("STORES.jp", "useful-tab-block"); ?></strong></a></li>
                            <li><a href="https://bbc000tommy.gumroad.com/l/useful-tab-block-premium" rel="noopener"><strong><?php _e("Gumroad", "useful-tab-block"); ?></strong></a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php
}

function useful_tab_block_admin_function()
{
?>
    <div class="admin-settings-useful-tab-block">

        <h1><?php _e("Useful Tab Block – Responsive & AMP-Compatible", 'useful-tab-block'); ?></h1>
        <p><?php _e("Thank you for installing <strong>Useful Tab Block – Responsive & AMP-Compatible</strong>.", 'useful-tab-block'); ?></p>

        <?php premium_block(); ?>

        <div class="developper-link">
            <a href="https://www.beginner-blogger.com/contact/" rel="noopener">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <?php _e("Suggestions/Feedback", 'useful-tab-block'); ?>
            </a>
        </div>


        <h2><?php _e("Specifications", 'useful-tab-block'); ?></h2>
        <ol class="utb-number-list has-border">
            <li><?php _e("Maximum number of tabs: 10", 'useful-tab-block'); ?></li>
            <li><?php _e("You can customize:", 'useful-tab-block'); ?></li>
            <ul class="utb-list chevron">
                <li><?php _e("<strong>Label background color</strong> (checked/unchecked)", 'useful-tab-block'); ?></li>
                <li><?php _e("<strong>Label text color</strong> (checked/unchecked)", 'useful-tab-block') ?></li>
                <li><?php _e("<strong>Border type</strong> (solid, dotted, and dashed) and <strong>border color</strong>",  'useful-tab-block') ?></li>
                <li><?php _e("<strong>Label shape</strong> (rect, round, and top round)", 'useful-tab-block') ?></li>
            </ul>
        </ol>

        <div class="column-2">
            <div>
                <figure>
                    <img src="<?php echo plugins_url("images/gutenberg.png", __FILE__); ?>">
                </figure>
            </div>
            <div>
                <p><?php _e("You can find Useful Tab Block in <strong>DESIGN</strong> category.", 'useful-tab-block'); ?>
                </p>
            </div>
        </div>

        <p><?php _e("If you want to shorctut, you can create one by just typing <code>/useful</code> in a paragraph block.", 'useful-tab-block'); ?></p>
        <figure>
            <img src="<?php echo plugins_url("images/shortcut.png", __FILE__); ?>" width="500">
        </figure>


        <div class="column-2">
            <div>
                <figure>
                    <img src="<?php echo plugins_url("images/blue_border.png", __FILE__); ?>">
                </figure>
            </div>
            <div>
                <p><?php _e("Put your content in the blue border box (the blue border does not appear on the frontend).", 'useful-tab-block') ?>
                </p>
            </div>
        </div>


        <h2><?php _e("Note", 'useful-tab-block'); ?></h2>
        <p><?php _e("Depending on the WordPress theme you use, you may see a color picker in <strong>Color Setting</strong> panel in the inspector.", 'useful-tab-block'); ?></p>

        <div class="column-2">
            <div>
                <figure>
                    <img src="<?php echo plugins_url("images/color_palette.png", __FILE__); ?>">
                </figure>
            </div>
            <div>
                <p><?php _e('This plugin, however, <span class="red-bold">DOES NOT SUPPORT</span> this color picker. Even if you choose a color with it, nothing visually changes in Useful Tab Blocks.', 'useful-tab-block'); ?>
                </p>
            </div>
        </div>

        <p><?php _e("Instead, this plugin has prepared plenty of colors!", 'useful-tab-block'); ?></p>
        <p><?php _e("<strong>Furthermore, in the premium version, you can define custome colors!</strong>", 'useful-tab-block'); ?></p>

        <?php premium_block(); ?>


        <p><?php _e("For the latest information, please check <a href='https://www.beginner-blogger.com/useful-tab-block' rel='noopener'>the developer's website</a>.", "useful-tab-block"); ?> <?php _e("Although it is written in Japanese, English comments are welcome! (The developer understands French, too!)", "useful-tab-block"); ?></p>
    </div>
<?php
}



// Page setting
function useful_tab_block_admin_setup_menu()
{
    add_menu_page(
        // Title
        __('Useful Tab Block – Responsive & AMP-Compatible', 'useful-tab-block'),
        // Menu name
        'Useful Tab',
        'manage_options',
        // Slug
        'useful-tab-block',
        // Function to be executed
        __NAMESPACE__ . '\useful_tab_block_admin_function',
        // Icon
        "dashicons-table-row-after",
        // Position
        30.00000001
    );
}

add_action('admin_menu', __NAMESPACE__ . '\useful_tab_block_admin_setup_menu');


function useful_tab_block_admin_style()
{
    $dir = dirname(__FILE__);
    $style_css = "/css/style.css";
    // CSS
    wp_enqueue_style(
        'useful_tab_block_admin_style',
        plugins_url($style_css, __FILE__),
        array(),
        filemtime("$dir/$style_css")
    );

    // Fontawesome
    wp_enqueue_style(
        "useful-tab-block-fontawesome",
        "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.css",
        array(),
        null
    );
}

add_action('admin_enqueue_scripts', __NAMESPACE__ . '\useful_tab_block_admin_style');


// Font Awesome Setting
function useful_tab_block_admin_sri($html, $handle)
{
    if ($handle === "useful-tab-block-fontawesome") {
        return str_replace(
            "/>",
            'integrity="sha512-4wfcoXlib1Aq0mUtsLLM74SZtmB73VHTafZAvxIp/Wk9u1PpIsrfmTvK0+yKetghCL8SHlZbMyEcV8Z21v42UQ==" crossorigin="anonymous"' . "/>",
            $html
        );
    }
    return $html;
}

add_filter("style_loader_tag", __NAMESPACE__ . "\useful_tab_block_admin_sri", 10, 2);
