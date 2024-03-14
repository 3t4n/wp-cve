<?php

class Tac_admin_services
{

    public static function register_settings()
    {
//        # footer script settings
        register_setting('tac_admin_services_settings', 'tac_footer_script_content');

        # Other
        add_settings_section('tac_footer_script_content', 'All Services available', array('Tac_admin_services', 'ohter_service_html'), 'tac_admin_services_settings');

        foreach (Tac_admin::$services as $key1 => $services) {
            $newSection = true;
            foreach ($services as $key2 => $service) {
                if ($newSection) {
                    $newSection = false;
                    add_settings_section($key1, $service['title'], array($service['class'], $service['function']), 'tac_admin_services_settings');
                    continue;
                }
                register_setting('tac_admin_services_settings', $service['id']);

                $codejs = $service['code']['js'] == "null" ? null : $service['code']['js'];
                $codehtml = $service['code']['html'] == "null" ? null : $service['code']['html'];
                $args = array('id' => $service['id'], 'value' => $service['value'], 'codejs' => $codejs, 'codehtml' => $codehtml);

                $function = $service['function'] == '' ? 'service_html' : $service['function'];
                add_settings_field($service['id'], $service['title'], array($service['class'], $function), 'tac_admin_services_settings', $key1, $args);
            }
        }
    }


    public static function section_html()
    {
        ?>
        <hr>
        <?php

    }

    public static function ohter_service_html()
    {
        ?>
        <div class="alert alert-info pb-1 pt-3 fs-3 mt-3">
            <p class="fs-4">If you need more information about a service, please go to <a
                        href="https://tarteaucitron.io/en/install/" target="_blank">tarteaucitron.io/en/install</a> and check in the
                manual install section
            </p>
            <ol class="fs-5">
                <li>
                    The Javascript code of the service goes in the box bellow.
                </li>
                <li>
                    The HTML part has to be put directly inside your website where you want to use the service.
                </li>
            </ol>
        </div>

        <textarea name="tac_footer_script_content" rows="15"
                  cols="100"><?php echo get_option('tac_footer_script_content') ?></textarea>
        <?php
    }

    public static function service_html($args)
    {
        ?>

        <div class="row">
            <div class="col-md-12">
                <div class="row tax_service align-middle">
                    <div class="col-sm col-md-1">
                        <input type="checkbox" name="<?php echo $args['id'] ?>"
                               value="<?php echo $args['value'] ?>"<?php checked($args['value'] == get_option($args['id'], '')); ?> />
                    </div>
                    <div class="col-sm col-md-2">
                        <!-- Modal -->
                        <div class="modal fade" id="modalJs<?php echo $args['id'] ?>" tabindex="-1" role="dialog"
                             aria-labelledby="modalJs<?php echo $args['id'] ?>Title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalJs<?php echo $args['id'] ?>Title">Js code</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="fs-5 mb-3">This code has to be added inside the text-area a the top of this page.</p>
                                        <code>
                                            <?php echo $args['codejs'] ?>
                                        </code>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Open Modal button-->

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalJs<?php echo $args['id'] ?>" <?php if ($args['codejs'] == null) {
                            echo "disabled";
                        } ?>>
                            JS code
                        </button>

                    </div>
                    <div class="col-sm col-md-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalhtml<?php echo $args['id'] ?>" <?php if ($args['codehtml'] == null) {
                            echo "disabled";
                        } ?>>
                            HTML code
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="modalhtml<?php echo $args['id'] ?>" tabindex="-1" role="dialog"
                             aria-labelledby="modalhtml<?php echo $args['id'] ?>Title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalhtml<?php echo $args['id'] ?>Title">HTML
                                            code</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="fs-5 mb-3">This code has to be added directly inside your page, where you want to use the service</p>
                                        <code>
                                            <?php echo $args['codehtml'] ?>
                                        </code>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm col-md">

                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public static function header_boolean_html()
    {
        ?>
        <input type="checkbox" name="tac_header_script_bool"
               value="1"<?php checked(1 == get_option('tac_header_script_bool', '')); ?> />
        <?php
    }

    public static function tac_menu_services()
    {

        echo '<h1>' . get_admin_page_title() . '</h1>';
        ?>
        <form method="post" action="options.php">

            <?php submit_button("Save"); ?>
            <!--                generation automatique des champs pour les options tac_admin_settings -->
            <?php settings_fields('tac_admin_services_settings') ?>

            <?php do_settings_sections('tac_admin_services_settings') ?>

            <?php submit_button("Save"); ?>
        </form>

        <?php

    }
}
