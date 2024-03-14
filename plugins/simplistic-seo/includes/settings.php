<?php
// SETTINGS PAGE
//-----------------------------------------------------------------------

function sseo_adminmenu()
{
    add_options_page(
        __('SEO settings', 'simplistic-seo'),
        __('SEO settings', 'simplistic-seo'),
        'manage_options',
        'seo_settings',
        'sseo_settingspage'
    );
}

add_action('admin_menu', 'sseo_adminmenu');

function sseo_settingspage()
{
    $sseo_post_types = sseo_get_post_types();
    $sseo_taxonomies = get_terms();

    $postsForSitemap = get_posts(array('numberposts' => -1, 'orderby' => 'modified', 'post_type' => 'any', 'order' => 'DESC'));
    $termsForSitemap = get_taxonomies([],'objects');

    $exclude = [
        'wpcode_location',
        'wpcode_tags',
        'wp_theme',
        'wp_template_part_area',
'link_category',
'fb_product_set',
        'language',
        'term_language',
        'wpcode_tags',
        'wpcode_location',
        'wpcode_type',
        'term_translations',
        'post_translations',
        'term_translations',
        'product_visibility',
        'nav_menu',
        'category',
        'product_type',
        'post_format'
        ];
?>

<div class="wrap">
    <h1><?php _e('SEO settings', 'simplistic-seo'); ?></h1>
    <form method="post" action="options.php">
        <?php settings_fields('sseo_settings');
            do_settings_sections('sseo_settings'); ?>

        <h2><?php _e('Title and Metadescription', 'simplistic-seo'); ?></h2>

        <table class="form-table sseo-form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="sseo_title_pattern"><?php _e('Post-Types', 'simplistic-seo'); ?></label>
                    </th>
                    <td>
                        <p class="description">
                            <?php _e('Active or deactive SEO settings for the following post types. (Shows the metabox on following types)', 'simplistic-seo'); ?>
                        </p>
                        <?php if ($sseo_post_types) { ?>
                        <ul>

                            <li>
                                <label class="header">
                                    <?php _e('Post Types', 'simplistic-seo'); ?>
                                </label>
                                <label class="header">
                                    <?php _e('Post Types Categories', 'simplistic-seo'); ?>

                                </label>
                            </li>

                            <?php foreach ($sseo_post_types  as $post_type) {
                                        $option_name = 'sseo_activate_type_' . $post_type->name;
                                        $option_name_category = 'sseo_activate_type_categorie' . $post_type->name;
                                        $post_type_object = get_post_type_object($post_type->name);
                                        $post_type_publicly_queryable = $post_type_object->publicly_queryable;


                                        if ($post_type_publicly_queryable || $post_type->name === "page") {
                                    ?>

                            <li>
                                <label for="<?php echo $option_name; ?>">
                                    <input name="<?php echo $option_name; ?>" type="checkbox"
                                        id="<?php echo $option_name; ?>" value="1"
                                        <?php checked(1, get_option($option_name), true); ?>>
                                    <?php echo $post_type->label; ?>
                                </label>
                                <?php
                                                if (count(get_object_taxonomies($post_type->name)) > 0) { ?>
                                <label for="<?php echo $option_name_category; ?>">
                                    <input name="<?php echo $option_name_category; ?>" type="checkbox"
                                        id="<?php echo $option_name_category; ?>" value="1"
                                        <?php checked(1, get_option($option_name_category), true); ?>>
                                    <?php echo $post_type->label . " categories"; ?>
                                </label>
                                <?php } ?>
                            </li>
                            <?php }
                                    } ?>
                        </ul>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="sseo_title_pattern"><?php _e('Title', 'simplistic-seo'); ?></label>
                    </th>
                    <td>
                        <input name="sseo_title_pattern" type="text" class="regular-text" id="sseo_title_pattern"
                            value="<?php echo esc_attr(get_option('sseo_title_pattern', '{pagetitle} – {sitetitle}')); ?>" />
                        <p class="description">
                            <?php _e('The title will be generated following this pattern, if there is no other title specified for a post or page.', 'simplistic-seo'); ?>
                        </p>
                        <p class="description"><?php _e('Placeholder:', 'simplistic-seo'); ?> <a
                                class="sseo-input-placeholder" data-placeholder="{sitetitle}"
                                data-target="sseo_title_pattern"><?php _e('Sitetitle', 'simplistic-seo'); ?></a><a
                                class="sseo-input-placeholder" data-placeholder="{sitedesc}"
                                data-target="sseo_title_pattern"><?php _e('Sitedescription', 'simplistic-seo'); ?></a><a
                                class="sseo-input-placeholder" data-placeholder="{pagetitle}"
                                data-target="sseo_title_pattern"><?php _e('Pagetitle', 'simplistic-seo'); ?></a></p>
                    </td>
                </tr>
            </tbody>
        </table>

        <h2><?php _e('Sitemap & Twitter Cards', 'simplistic-seo'); ?></h2>

        <table class="form-table sitemap__content">
            <tbody>
                <tr>
                    <th scope="row"><?php _e('Sitemap XML', 'simplistic-seo'); ?></th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _e('Sitemap XML', 'simplistic-seo'); ?></span>
                            </legend>
                            <label for="sseo_activate_sitemap">
                                <input name="sseo_activate_sitemap" type="checkbox" id="sseo_activate_sitemap" value="1"
                                    <?php checked(1, get_option('sseo_activate_sitemap'), true); ?>>
                                <?php _e('Generate sitemap.xml automatically', 'simplistic-seo'); ?>
                            </label>
                            <?php if (file_exists(ABSPATH . "sitemap.xml")) { ?>
                            <p><?php _e('Sitemap URL:', 'simplistic-seo'); ?> <a
                                    href="<?php echo esc_url(bloginfo('url') . '/sitemap.xml'); ?>"
                                    target="_blank"><?php echo esc_url(bloginfo('url') . '/sitemap.xml'); ?></a></p>
                            <?php } ?>
                        </fieldset>
                        <fieldset class="sitemap-exclude__wrapper">
                            <legend class="sitemap-exclude">
                                <span><?php _e('Sitemap XML exclude', 'simplistic-seo'); ?></span>
                                <select size="6" multiple id="sseo-page-list">
                                    <?php
                                        $page_ids = get_all_page_ids();
                                        $excluded_pages = get_option('sseo_sitemap_exclude');
                                        $excluded_pages_parsed = json_decode($excluded_pages);

                                        foreach ($page_ids as $id) {
                                            $page_title = get_the_title($id);
                                            if ($excluded_pages_parsed === null || !in_array($page_title, $excluded_pages_parsed)) {
                                                echo '<option value="' . $page_title . '" >' .  $page_title  . '</option>';
                                            }
                                        }
                                        ?>


                                </select>
                                <div id="sseo-add-to-exclude" class="sseo-btn"> add >> </div>
                                <div id="sseo-remove-from-exclude" class="sseo-btn">
                                    << remove </div>

                                        <input hidden name="sseo_sitemap_exclude" id="sseo_sitemap_exclude"
                                            value='<?php echo $excluded_pages; ?>' />
                                        <select size="6" multiple name="sseo_sitemap_exclude_select"
                                            id="sseo_sitemap_exclude_select">

                                            <?php

                                                foreach ($excluded_pages_parsed as $page) {
                                                    echo '<option value="' . $page . '">' . $page . '</option>';
                                                }
                                                ?>
                                        </select>
                            </legend>
                        </fieldset>
                        <br>
                       
                        <?php if ($sseo_post_types) { ?>
                            <fieldset>
                        <ul>

                            <li>
                                <label class="header">
                                    <?php _e('Post Types', 'simplistic-seo'); ?>
                                </label>
                               
                            </li>
                            <?php 
                           
                            foreach ($sseo_post_types  as $post_type) {
                                        $option_name = 'sseo_activate_type_sitemap_' . $post_type->name;
                                       
                                        $post_type_object = get_post_type_object($post_type->name);
                                        $post_type_publicly_queryable = $post_type_object->publicly_queryable;

                                        if ($post_type_publicly_queryable || $post_type->name === "page") {
                                    ?>

                            <li>
                                <label for="<?php echo $option_name; ?>">
                                    <input name="<?php echo $option_name; ?>" type="checkbox"
                                        id="<?php echo $option_name; ?>" value="1"
                                        <?php checked(1, get_option($option_name), true); ?>>
                                    <?php echo $post_type->label; ?>
                                </label>               
                            </li>
                            
            
                                <?php } }?>
                                <li>
                                <label class="header">
                                    <?php _e('Categories', 'simplistic-seo'); ?>
                                </label>
                            
                            </li>
                            <?php
                   
                            foreach ($termsForSitemap  as $taxonomie) {
                                $option_name = 'sseo_activate_type_categories_sitemap_' . $taxonomie->name;
                                if (in_array($taxonomie->name,$exclude)){
                                    continue;
                                  }
                                ?>
                                <li>
                                <label for="<?php echo $taxonomie->label; ?>">
                                    <input name="<?php echo $option_name; ?>" type="checkbox"
                                        id="<?php echo $option_name; ?>" value="1"
                                        <?php checked(1, get_option($option_name), true); ?>>
                                    <?php echo $taxonomie->label; ?>
                                </label>     

                            </li>
                            <?php
                            }
                            ?>

                        </ul>
                        </fieldset>
                        <?php } ?>

                        <br>
                    
                        <fieldset>
                            <label for="sseo_activate_sitemap_multidomain">
                                <input name="sseo_activate_sitemap_multidomain" type="checkbox"
                                    id="sseo_activate_sitemap_multidomain" value="1"
                                    <?php checked(1, get_option('sseo_activate_sitemap_multidomain'), true); ?>>
                                <?php _e('Generate sitemap-{lang}.xml for each domain automatically', 'simplistic-seo'); ?>
                            </label>
                            <p></p>
                            <textarea placeholder="add domain for each row" rows="5"
                                name="sseo_activate_sitemap_multidomain_domains" type="text"
                                id="sseo_activate_sitemap_multidomain_domains"><?php form_option('sseo_activate_sitemap_multidomain_domains'); ?></textarea>
                        
                       
                        <?php
                            $domains = sseo_get_multi_domains();
                            foreach($domains as $domain){
                         if (file_exists(ABSPATH . "sitemap-" . $domain . ".xml")) { ?>
                            <p><?php _e('Sitemap URL:', 'simplistic-seo'); ?> <a
                                    href="<?php echo esc_url(bloginfo('url') . '/sitemap-' . $domain . '.xml'); ?>"
                                    target="_blank"><?php echo esc_url(bloginfo('url') . '/sitemap-' . $domain . '.xml'); ?></a></p>
                            <?php }}?>
                            <br>
                             <br>
                            Copy this to you .htaccess to make an automated redirect to the files.
                            <pre><code><?php
                                foreach($domains as $domain){
                                    echo "# Rewrite sitemap.xml for " . $domain . " to sitemap-" . $domain . ".xml \n";
                                    echo "RewriteCond %{HTTP_HOST} \." . $domain . "$ [NC] \n";
                                    echo "RewriteRule ^sitemap\.xml$ sitemap-" . $domain . ".xml [R=301,L] \n\n";
                                }?></code>
                            </pre>
                            </fieldset>
                            
                        
                                                
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Twitter cards', 'simplistic-seo'); ?></th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _e('Twitter cards', 'simplistic-seo'); ?></span>
                            </legend>
                            <label for="sseo_activate_twittercard">
                                <input name="sseo_activate_twittercard" type="checkbox" id="sseo_activate_twittercard"
                                    value="1" <?php checked(1, get_option('sseo_activate_twittercard'), true); ?>>
                                <?php _e('Enable Twitter cards', 'simplistic-seo'); ?>
                            </label>
                        
                        </fieldset>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php submit_button(); ?>

    </form>
</div>

<?php }

function seo_register_settings()
{
    $sseo_post_types = sseo_get_post_types();
    $termsForSitemap = get_taxonomies([],'objects');

    register_setting('sseo_settings', 'sseo_title_pattern');
    register_setting('sseo_settings', 'sseo_activate_sitemap');
    register_setting('sseo_settings', 'sseo_activate_sitemap_multidomain');
    register_setting('sseo_settings', 'sseo_activate_sitemap_multidomain_domains');
    register_setting('sseo_settings', 'sseo_activate_twittercard');
    register_setting('sseo_settings', 'sseo_sitemap_exclude');

    if ($sseo_post_types) {
        foreach ($sseo_post_types as $post_type) {
            $option_name = 'sseo_activate_type_' . $post_type->name;
            $option_name_category = 'sseo_activate_type_categorie' . $post_type->name;

            register_setting('sseo_settings', $option_name);
            register_setting('sseo_settings', $option_name_category);

            $option_name = 'sseo_activate_type_sitemap_' . $post_type->name;
            register_setting('sseo_settings', $option_name);
         
        }

    }

    foreach ($termsForSitemap  as $taxonomie) {
        $option_name = 'sseo_activate_type_categories_sitemap_' . $taxonomie->name;
        register_setting('sseo_settings', $option_name);
    }
   
}

add_action('admin_init', 'seo_register_settings');



?>