<?php

class Tac_admin_scripts
{
    public static function register_settings()
    {


        # header script settings
        register_setting('tac_admin_script_settings', 'tac_header_script_content');

        add_settings_section('tac_admin_script_section', 'Tarteaucitron.js parameters', array('Tac_admin_scripts', 'section_html'), 'tac_admin_script_settings');


        foreach (Tac_admin::$init as $key1 => $service) {
            register_setting('tac_admin_script_settings', $service['id']);

//            $value = $service['value']; # == "" ? null : $service['code']['js'];
//            $codehtml = $service['code']['html'] == "null" ? null : $service['code']['html'];
            $args = array('id' => $service['id'], 'value' => $service['value'], "comment" => $service['comment']);

            $function = $service['function'] == '' ? 'script_html' : $service['function'];
            switch ($service['type']) {
                case "text":
                    $function = "script_html";
                    break;
                case "options":
                    $args["options"] = $service['options'];
                    $function = "script_options_html";
                    break;
                case "boolean":
                    $args["options"] = $service['options'];
                    $function = "script_options_html";
                    break;
                case "integer":
                    $function = "script_integer_html";
                    break;
                default:
                    $function = "script_html";
            }

            add_settings_field($service['id'], $service['title'], array($service['class'], $function), 'tac_admin_script_settings', "tac_admin_script_section", $args);
        }

        # Other settings that needs to be set before the initialisation
        add_settings_field('tac_header_script_content', 'Header script content', array('Tac_admin_scripts', 'header_content_html'), 'tac_admin_script_settings', 'tac_admin_script_section');
    }

    public static function section_html()
    {
        echo 'Fill in the contents of the Tarteaucitron.js initialization scripts.';
    }


    public static function header_content_html()
    {

        ?>
        <div class="row">
            <div class="col-md-7">
                 <textarea name="tac_header_script_content" rows="15"
                           cols="100"><?php echo get_option('tac_header_script_content') ?></textarea>
            </div>
            <div class="col-md-5">
                <p>All other settings that need to be set before the initialisation of tarteaucitron.js</p>
                <p>If a settings ins't in the above options but are already present in the script tarteaucitron.js, you
                    can put the code here. (Only for the initialisation script).</p>
            </div>
        </div>
        <?php
    }

    public static function script_options_html($args)
    {

        ?>

        <div class="row">
            <div class="col-md-3">
                <select name="<?php echo $args['id'] ?>">
                    <?php
                    foreach ($args['options'] as $opt) {
                        ?>
                        <option value="<?php echo $opt ?>" <?php selected(get_option($args['id'], ''), $opt); ?>> <?php echo $opt ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-9">
                <p><?php echo $args['comment']; ?></p>
            </div>
        </div>

        <?php
    }

    public static function script_integer_html($args)
    {
        ?>

        <div class="row">
            <div class="col-md-3">
                <input name="<?php echo $args['id'] ?>" type="number"
                       value="<?php echo get_option($args['id'], $args['value']) ?>"/>
            </div>
            <div class="col-md-9">
                <p><?php echo $args['comment']; ?></p>
            </div>
        </div>

        <?php
    }

    public static function script_html($args)
    {
        ?>
        <div class="row">
            <div class="col-md-3">
                <input name="<?php echo $args['id'] ?>" type="text"
                       value="<?php echo get_option($args['id'], $args['value']) ?>"/>

            </div>
            <div class="col-md-9">
                <p><?php echo $args['comment']; ?></p>
            </div>
        </div>
        <?php
    }


    public static function tac_menu_script()
    {

        echo '<h1>' . get_admin_page_title() . '</h1>';

        ?>
        <form method="post" action="options.php">

            <!-- generation automatique des champs pour les options tac_admin_settings -->
            <?php submit_button("Save"); ?>
            <hr>

            <?php settings_fields('tac_admin_script_settings') ?>

            <?php do_settings_sections('tac_admin_script_settings') ?>

            <?php submit_button("Save"); ?>
        </form>
        <?php


    }
}
