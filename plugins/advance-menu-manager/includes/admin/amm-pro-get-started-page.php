<?php
$plugin_url = DSAMM_PRO_PLUGIN_URL;
$plugin_name = DSAMM_PLUGIN_NAME;
?>
<div class="amm-main-table res-cl">
    <h2><?php echo sprintf( esc_html__('Thanks For Installing %s', 'advance-menu-manager'), esc_html($plugin_name) );?></h2>

    <table class="table-outer">
        <tbody>
            <tr>
                <td class="fr-2">
                    <p class="block gettingstarted"><strong><?php esc_html_e('Getting Started', 'advance-menu-manager'); ?></strong></p>
                    <p class="block textgetting">
                        <span><?php esc_html_e('Advanced Menu Manager Plugins make it simpler for you and your team to effectively create and manage menus for content- heavy blogs and website.', 'advance-menu-manager'); ?></span>
                    </p>
                    <p class="block textgetting"><?php esc_html_e('Create Menu and easy manage as per your needs', 'advance-menu-manager'); ?></p>
                    <p class="block textgetting">
                        <span><strong><?php esc_html_e('Step 1', 'advance-menu-manager'); ?> :</strong> <?php echo sprintf( wp_kses_post( 'Create a menu by <a href="%s">"create a new menu"</a> link as per your needs. You can give a name to your menu as shown in the image. Click on the Save Menu option to save your menu.', 'advance-menu-manager' ), esc_url( site_url().'/wp-admin/admin.php?page=advance-menu-manager-pro&tab=menu-manager-add&section=menu-add' ) );?></span>
                        <span class="gettingstarted">
                            <img style="border: 2px solid #e9e9e9;margin-top: 3%;" src="<?php echo esc_url($plugin_url) . 'images/1-amm.png'; ?>">										
                        </span>
                    </p>
                    <p class="block gettingstarted textgetting">
                        <span class="spacing"><strong><?php esc_html_e('Step 2', 'advance-menu-manager'); ?> :</strong> <?php esc_html_e('Start adding new menu items : Use the Green + sign as shown in the image below to start adding items to your menu.', 'advance-menu-manager'); ?></span>
                        <span class="gettingstarted">
                            <img style="border: 2px solid #e9e9e9;margin-top: 3%;" src="<?php echo esc_url($plugin_url) . 'images/2-AMM.png'; ?>">
                        </span>
                    </p>

                    <p class="block gettingstarted textgetting">
                        <span class="spacing"><strong><?php esc_html_e('Step 3', 'advance-menu-manager'); ?> :</strong> <?php esc_html_e('Add pages/posts : With this screen, it will provide complete details about the pages, posts, categories and custom links. Once you get to this step, you have the complete picture of your pages, the item id, title, item slug, author and publish date too. This is quite handy as you get all the details in one single interface. No going back and forth to check stuff.', 'advance-menu-manager'); ?></span>
                        <span class="gettingstarted">
                            <img style="border: 2px solid #e9e9e9;margin-top: 3%;" src="<?php echo esc_url($plugin_url) . 'images/03-AMM.png'; ?>">
                        </span>
                    </p>

                    <p class="block gettingstarted textgetting">
                        <span class="spacing"><strong><?php esc_html_e('Step 4', 'advance-menu-manager'); ?> :</strong> <?php esc_html_e('After adding pages, posts, categories and custom links you will see below interface and then you need to click on save menu button to save your changes.', 'advance-menu-manager'); ?></span>
                        <span class="gettingstarted">
                            <img style="border: 2px solid #e9e9e9;margin-top: 3%;" src="<?php echo esc_url($plugin_url) . 'images/04-AMM.png'; ?>">
                        </span>
                    </p>

                </td>
            </tr>
        </tbody>
    </table>
</div> 
