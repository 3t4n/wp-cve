<?php

class Tac_admin_languages
{

    public static function register_languages()
    {
        register_setting('tac_admin_language_settings', 'tac_lang_content');
        add_settings_section("tac_admin_lang_section", "Custom texts", array("Tac_admin_languages", "section_html"), 'tac_admin_language_settings');

        foreach (Tac_admin::$customText as $key1 => $service) {
            register_setting('tac_admin_language_settings', $service['id']);

            $args = array('id' => $service['id'], 'value' => $service['value'], "placeholder" => $service['placeholder']);

            $function = $service['function'] == '' ? 'languages_content_textarea_html' : $service['function'];


            add_settings_field($service['id'], $service['title'], array("Tac_admin_languages", $function), 'tac_admin_language_settings', "tac_admin_lang_section", $args);
        }

        # Other settings that needs to be set before the initialisation
        add_settings_field('tac_lang_content', 'Texts Customisation', array('Tac_admin_languages', 'languages_content_html'), 'tac_admin_language_settings', 'tac_admin_lang_section');
    }

    public static function section_html()
    {
        echo 'Fill the with the content of the variable tarteaucitronCustomText';
    }

    public static function languages_content_textarea_html($args)
    {
        ?>
        <div class="row">
            <div class="col-md-9">
                 <textarea name="<?php echo $args['id'] ?>" rows="4"
                           cols="100" placeholder="e.g. : <?php echo $args["placeholder"] ?>"><?php echo get_option($args['id'], $args['value']) ?></textarea>
            </div>

        </div>
        <?php
    }

    public static function languages_content_html()
    {
        ?>
        <div class="row">
            <div class="col-md-9">
                 <textarea name="tac_lang_content" rows="20"
                           cols="100"><?php echo get_option('tac_lang_content') ?></textarea>
            </div>
            <div class="col-md-3">
                <p>For example to modify the value of the "adblock" </p>
                <p>{"adblock": "Hello, this is my new custom text for the adblock message"}</p>
            </div>
        </div>
        <?php
    }

    public static function tac_menu_languages()
    {
        ?>
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-4">Texts Customization</h1>
            </div>
        </div>
        <?php

        echo '<h1>' . get_admin_page_title() . '</h1>';
        ?>
        <p>The placeholder is in english, but the real value of the text depend on the language selected and the value you put on the following options.</p>
        <form method="post" action="options.php">

            <?php submit_button("Save"); ?>
            <!--                generation automatique des champs pour les options tac_admin_settings -->
            <?php settings_fields('tac_admin_language_settings') ?>

            <?php do_settings_sections('tac_admin_language_settings') ?>

            <?php submit_button("Save"); ?>
        </form>
        <?php


    }
}
